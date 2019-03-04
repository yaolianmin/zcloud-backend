<?php

/**
* 模块：任务模块
* 作用：增加 删除 修改用户信息
* 时间：2019-01-23
* 说明：由于采用了restful api方式请求，所创建的控制器TaskManagement 
*       需在config/main.php里面的controller配置 'task-management',
*       其请求方式get post等会自动的选择该控制器的方法，若有特殊命名需求，
*       可在config/main.php的extraPatterns里面配置
*@author :yaolianmin
*@version :
*/

namespace api\controllers;

use Yii;
use yii\rest\ActiveController;
use backend\models\Task_schedule;
use backend\models\TempletFAP;
use api\models\Reboot;
use api\models\User;
use api\models\DevInfo;
use api\models\Actions;
use api\models\SystLog;

Class TaskManagementController extends ActiveController{
	/**
     * 指定调用这个控制器时链接哪个数据模型.
     * 该属性必须设置.
     */
    public $modelClass = '';
   
    public function actions(){
	    $actions = parent::actions();
        /**
         * 注销系统自带的实现方法.
         */
	    unset($actions['index'], $actions['update'], $actions['create'], $actions['delete'], $actions['view']);
	   	return $actions;
     }

    /*
    * 方法：任务管理模块方法
    * 作用：获得任务的相关信息
    * 说明：get请求的方法，默认请求index方法,
    *      这里的index方法只做查询数据的操作
    */
    public function actionIndex(){   
        $get = Yii::$app->request->get();
        //检测是否为本网站的访问请求 
        if( !isset($get['flag']) ){
            return [
                'state'=>1,
                'data'=>'请您用正确的方式访问'
            ];
        }
        // 1.0 task_view页面获得任务列表
        if($get['flag'] == 'get_task_information'){
            $user_power = User::get_user_power_by_username($get['username']);
            if($user_power != 2){
                return [
                    'state'=>1,
                    'data'=>'您没有权限访问任务'
                ];
            }
            try{
                // 表示有mac地址查询
                if($get['where']){
                    $where = ['username'=>$get['username'],'device_mac'=>$get['where']];
                }else{
                    $where = ['username' =>$get['username']];
                }
                $offset = ($get['pages']-1)*$get['page_size']; //需要从哪行开始查询
                $count = Task_schedule::find()->where($where)->count(); //总记录数
                $task = Task_schedule::find()->select('id,device_mac,description,execute_time,status')
                            ->where($where)
                            ->offset($offset)
                            ->limit($get['page_size'])
                            ->asArray()
                            ->all();
                // 添加 index 字段
                if($task){
                    foreach ($task as $key =>$val) {
                        $task[$key]['index'] = $key+1;
                    }
                } 
                return [
                    'count'=>$count,
                    'data'=>$task
                ];
            }catch(\Expection $e){
                 return $e->getMessage();
            }
        // task_edit页面获得任务类型的数据
        }elseif($get['flag'] == 'get_task_type_information'){
            $user_power = User::get_user_power_by_username($get['username']);
            if($user_power != 2){
                return [
                    'state'=>1,
                    'data'=>'您没有权限访问任务'
                ];
            } 
           
            $offset = ($get['pages']-1)*$get['page_size']; //需要从哪行开始查询
            switch ($get['task_type']) {
                case '1': //代表重启 
                    $where = [
                        'user_name'=>$get['username'],
                        'reviewer'=>$get['grop_look'],
                        'group_name'=>$get['grop_name']
                    ]; // 条件
                    $count = Reboot::find()->where($where)->count(); 
                    $data = Reboot::find()->where($where)
                                        ->offset($offset)
                                        ->limit($get['page_size'])
                                        ->asArray()
                                        ->aLL();
                    // 添加 index 字段
                    if($data){
                        foreach ($data as $key =>$val) {
                            $data[$key]['index'] = $key+1;
                        }
                    } 
                    return [
                        'task_type'=>1,// 代表重启
                        'count'=>$count,
                        'data'=>$data,
                    ];
                case '2': //代表固件更新
                    $data = DevInfo::get_task_devInfo($get['grop_look'],$get['username'],$get['grop_name'],$get['pages'],$get['page_size']);
    
                    return [
                        'task_type'=>2,// 代表固件更新
                        'count'=>$data['count'],
                        'data'=>$data['data']
                    ];     
                case '3': //代表模板下发
                    $where = [
                        'user_name'=>$get['grop_look'],
                        'management'=>$get['username'],
                        'group_name'=>$get['grop_name']
                    ]; // 条件
                    $count = DevInfo::find()->where($where)->count(); 
                    $data = DevInfo::find()->select('opemode,mac_addr,model_name,model_type')->where($where)
                                           ->offset($offset)
                                           ->limit($get['page_size'])
                                           ->asArray()
                                           ->aLL();

                    foreach ($data as $key=>$val) {
                        // 这里就不再支持CPE模式的设备和桥模式设备的任务下发
                        if($val['opemode'] == 'CPE' || $val['opemode'] == 'Bridge'){
                            array_splice($data, 1, 1);
                        }
                        // 添加index字段
                        $data[$key]['index']=$key+1;
                        //处理通用私有的显示
                        if($val['model_type'] == 'AC'){
                            $data[$key]['tmp_type'] = '私有';
                        }else{
                            $data[$key]['tmp_type'] = '通用';
                        }
                        //处理模板名的显示
                        switch ($val['model_type']) {
                            case 'AP':
                                $info = TempletFAP::find()->select('templet_name')->where(['username'=>$get['username'],'card_index'=>0,'model_name'=>$val['model_name']])->asArray()->all();
                                break;
                            case 'AC':
                                $table = 'TempletAC';
                                break;
                            case 'VAC':
                                $table = 'TempletVAC';
                                break;
                            case 'CPE':
                                $table = 'TempletCPE';
                                break;   
                        }
                        $data[$key]['tmp_name'] = $info;
                    }

                    return [
                        'task_type'=>3,// 代表模板更新
                        'count'=>$count,
                        'data'=>$data
                    ];  
                case '4': //代表诊断
                   
                    return   '444444'; 
                default:    
            }
        }  
    }


    /*
     * 方法：任务管理模块方法
     * 作用：添加修改任务的相关信息
     * 说明：这里的操作是post的提交
     */
    public function actionCreate(){
        $post = Yii::$app->request->post();
        //检测是否为本网站的访问请求 
        if( !isset($post['flag']) ){
            return [
                'state'=>1,
                'data'=>'请您用正确的方式访问'
            ];
        } 
        // 检测操作的权限
        $user_power = User::get_user_power_by_username($post['username']);
        if($user_power != 2){
            return [
                'state'=>1,
                'data'=>'您没有权限访问任务'
            ];
        }
        // 1.0 重启 按钮操作
        if($post['flag'] == 'submit_task_information'){ 
            $where = [
                'management'=> $post['username'],
                'user_name'=>  $post['grop_look'],
                'group_name'=> $post['grop_name']
            ]; // 条件
            $mac_addr = DevInfo::find()->select('mac_addr,service_start,service_end')->where($where)->asArray()->all();
            foreach ($mac_addr as $val) {
                $reboot_status = Reboot::find()->select('reboot_status')->where(['user_name'=>$post['username'],'mac_addr'=>$val['mac_addr']])->asArray()->one(); 
                if($reboot_status['reboot_status'] == 'YES'){
                    $date = date('Y-m-d H:i');
                    //判断设备是否下线
                    if($date<$val['service_start'] || $date>$val['service_end']){
                       $error_info = $val['mac_addr']. ' is out of service!';
                    }
                    // 立即执行
                    if($post['data']['select_type'] == 1){
                        // 获得添加数据的action_id字段
                        $action_id = Actions::add_and_get_action_id($val['mac_addr']);
                        $status = 'running';
                        $time = $date;
                    //预约执行
                    }elseif($post['data']['select_type'] == 2){
                        if($post['data']['time']){
                            $time = date('Y-m-d H:i',$post['data']['time']/1000);
                        }else{
                            $time = $date;
                        }
                        $status = 'scheduling';
                        $action_id = 0;
                    }

                    $insert_data = [
                        'action_id' =>$action_id,                        
                        'group_name'=>$post['grop_name'],            //组名
                        'reviewer'=>$post['grop_look'],              //组查者
                        'username'=>$post['username'],               //用户名
                        'device_mac'=>$val['mac_addr'],             //mac地址
                        'action_filename'=>'N/A',                   //操作的文件名
                        'task_type'=> 'reboot',                     //任务类型
                        'description'=>$post['data']['description'], // 任务的描述
                        'execute_time'=>$time,                      //执行的时间
                        'date_created'=>$date,                      //添加数据的时间  
                        'status'=>'scheduling',                     //调度方式
                    ];

                    //立即执行和预约的数据库操作
                    $rel = Task_schedule::insert_data_to_task($insert_data);

                    //添加一条日志
                    $log = SystLog::zoam_add_log($post['username'],1,100,'information',$val['mac_addr'].'reboot');
                    return [
                       'state'=>0,
                       'data'=>'添加任务成功'
                    ];
                }
            } 
        // 1.1 重启设备的按钮操作（——-属于重启操作-——）
        }elseif($post['flag'] == 'edit_task_reboot'){
            try{
            //修改重启的动作至数据库
            if($post['status']){
                $status = 'YES';
            }else{
                $status = 'NO';
            }
            $rel = Reboot::updateAll(['reboot_status'=>$status],['id'=>$post['reboot_id']]);
            return [
                'state'=>0,
                'data'=>'操作成功'
            ];
            }catch(\Expection $e){
                return $e->getMessage();
            }
        // 2.0 固件更新操作
        }elseif($post['flag'] == 'fw_upgrade_update_info'){
            if(! $post['table_mac'] ){
                return [
                    'state'=>1,
                    'data'=>'请勾选需要更新的固件'
                ];
            }
            if( ! $post['form_data']['description'] ){
                $post['form_data']['description'] ='Cloud created firmware Task'; 
            }
            //更新数据至数据库中
            foreach ($post['table_mac'] as $val) {
                $rel = DevInfo::updateAll(['fw_path'=>$post['table_info'][$val]],['mac_addr'=>$val]);

                //处理ftp的路径格式
                $exp = explode('@',$post['table_info'][$val]); // ['ftp://192.168.1.1/AN_1022/v1.0.0.img','root:123']
                $host = explode('/', $exp[0]);      // ['ftp:','','192.168.1.1','AN_1022','v1.0.0.img']
                $ftp_server = $exp[1].'@'.$host[2].'/'; // root:123@192.168.1.1/
                $time = explode(' ',microtime());
                $rand_num = $time[1].($time[0] * 1000); // 1550557642988.38
                $last_pos = count($host) -1;
                $fw_name = 'Firmware_'.$rand_num."_".$host[$last_pos];

                // 检测预约还是立即执行
                if(isset($post['form_data']['time'])){
                    $execute_time = date('Y-m-d H:i:s',$post['form_data']['time']/1000);
                    $date = date('Y-m-d H:i:s',time());
                    $status = 'scheduling';
                    $action_id =0;
                }else{
                    $execute_time = date('Y-m-d H:i:s',time());
                    $date = date('Y-m-d H:i:s',time());
                    $status = 'running';
                    // 获得添加数据的action_id字段
                    $action_id = Actions::fw_get_action_id($val,$fw_name,$ftp_server);
                }

                // 插入数据至tast_schedule表
                $insert_data = [
                    'action_id' =>$action_id,                        
                    'group_name'=>$post['grop_name'],            //组名
                    'reviewer'=>$post['grop_look'],              //组查者
                    'username'=>$post['username'],               //用户名
                    'device_mac'=>$val,                         //mac地址
                    'action_filename'=>$fw_name,                //操作的文件名
                    'ftp_server_params'=>$ftp_server,
                    'task_type'=> 'firmware',                     //任务类型
                    'description'=>$post['form_data']['description'], // 任务的描述
                    'execute_time'=>$execute_time,               //执行的时间
                    'date_created'=>$date,               //添加数据的时间  
                    'status'=>$status,                          //调度方式
                ];
                //立即执行和预约的数据库操作
                $rel = Task_schedule::insert_data_to_task($insert_data);
                //添加一条日志
                $log = SystLog::zoam_add_log($post['username'],1,100,'information',$val.'firmware');
                return [
                    'state'=>0,
                    'data'=>'固件更新成功'
                ];
            }
        }
       
    }
    

}
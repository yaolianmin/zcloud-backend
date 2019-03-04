<?php

/**
* 模块：通用设备管理模块
* 作用：获取设备信息 添加修改通用设备信息
* 时间：2018-10-15
* 说明：由于采用了restful api方式请求，所创建的控制器UserManagement 
*      需在config/main.php里面的controller配置 'commom-dev',
*      其请求方式get post等会自动的选择该控制器的方法，若有特殊命名需求，
*      可在config/main.php的extraPatterns里面配置
*@author : yaolianmin
*@version :
*/
namespace api\controllers;

use Yii;
use yii\rest\ActiveController;
use api\models\User;
use api\models\DevInfo;
use api\models\Mgd_dev;

Class CommonDevController extends ActiveController{


    /**
     * 指定调用这个控制器时链接哪个数据模型.
     * 该属性必须设置.
     */
     public $modelClass = '';


    /**
     * 说明：注销父类里面默认的提交页面方式
     *      实现自己自带的控制器和方法
     */
    public function actions(){
        $actions = parent::actions();
        unset($actions['index'], $actions['update'], $actions['create'], $actions['delete'], $actions['view']);
        return $actions;
    }

    /*
    * 方法：通用设备模块方法
    * 作用：获得设备的信息
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
        // 1.0 获得改用的设备信息和通用设备信息
        if($get['flag'] == 'get_common_dev_infor'){
            // 1.1 判断级别，并处理
            $power = User::get_user_power_by_username($get['username']);
            if($power>10 || $power <1){
                return [
                    'state'=>1,
                    'message'=>'您没有通用设备'
                ];
            }else{
                // 1.2 获得该用户的组名（由于前端的created方法不能获得，故在这里需要重新获得）
                $group = User::find()->select('usr_grp_sel_mgt,usr_grp_sel_reviewer,usr_grp_sel_group')->where(['UserName'=>$get['username']])->asArray()->one();
                if(!$group){
                    return [
                        'state'=>2,
                        'message'=>'您没有组名,请选择组名'
                    ];  
                }
                // 1.3 获得devinfor表和managed_device表的信息
                $counts =  DevInfo::find()->where(['management'=>$group['usr_grp_sel_mgt']])->count(); 
                $offset =  ($get['pages']-1)*$get['page_size'];
                $dev_infor = DevInfo::find()->select('model_name,mac_addr,device_status,recent_online')->offset($offset)->limit($get['page_size'])->where(['management'=>$group['usr_grp_sel_mgt']])->asArray()->all();  
                $common_arr = [];
                if($dev_infor){
                    $i = 1;
                    $com_offset = $get['pages']*$get['page_size'];
                    foreach ($dev_infor as $key => $val) {
                        $dev_infor[ $key]['index'] = $key+1;
                        $common = Mgd_dev::find()->where(['dev_mac'=>$val['mac_addr']])->asArray()->all();
                        if($common){
                            foreach ($common as  $ke =>$value) {
                                $common[$ke]['index'] = $i;
                                $common[$ke]['statue'] = $val['device_status'];
                                array_push($common_arr,$common[$ke]);
                                $i++;
                            }
                        }
                    }
                    $com_count = count($common_arr);
                    // 1.4 检测通用设备的总数，并获得相应的页数信息
                    if($com_count > $com_offset){
                        $comm_new = [];
                        $begin = ($get['pages']-1)*$get['page_size']; // 起始行
                        $end = $get['pages']*$get['page_size'];   //结束行
                        for($i = $begin; $i< $end; $i++){
                            $comm_new[$begin] = $common_arr[$begin];
                        }
                        $common_arr  = $comm_new;   
                    }
                }
                // 处理设备是否在线的问题
                $now_time = time();
                foreach ($dev_infor as $ke => $val) {
                    $online_time = strtotime($val['recent_online']);
                    if($now_time > $online_time+300){ //表示超过300秒没有发送
                        $dev_infor[$ke]['device_status'] = 0;
                        // 修改数据库的字段
                        $off_time = date('Y-m-d H:i:s',$now_time); 
                        DevInfo::updateAll(['device_status'=>0,'recent_offline'=>$off_time],['mac_addr'=>$dev_infor[$ke]['mac_addr']]);
                   }
                }
                return [
                    'state'=>0,
                    'dev_count'=>$counts, // 设备的总数
                    'device'=>$dev_infor, 
                    'com_count'=>$com_count, // 通用设备的总数
                    'common_dev'=>$common_arr
                ];
            }

        }
    }


    /**
     * 方法:通用设备管理方法
     * 作用：添加、修改、删除通用设备信息
     * 说明：这里是前端采用post提交的方法
     *       这里的操作都是更改数据库的操作
     *
     */
    public function actionCreate(){
         $post = Yii::$app->request->post(); 
        //检测是否为本网站的访问请求 
        if(!isset($post['flag']) ){
            return [
                'state'=>1,
                'data'=>'请您用正确的方式访问'
            ];
        }
        //1.0 添加通用设备信息
        if($post['flag'] == 'add_common_device'){
            //1.1 检测提交数据的合理性
            $data = Mgd_dev::check_mac_ip_is_reasonable($post);
            if($data['state']){
                return $data;
            }
            // 1.2 将数据添加到数据库中
            $rel = Mgd_dev::add_common_dev_infor($post);
            if($rel == 'success'){
                return ['state'=>0,'message'=>'添加通用设备成功'];
            }else{
                return ['state'=>1,'message'=>'系统升级中，请稍后添加'];
            }
        // 2.0 编辑通用设备
        }elseif($post['flag'] == 'edit_common_device'){
            // 2.1 过滤不合理的数据
            $data = Mgd_dev::check_edit_mac_ip_is_reasonable($post);
            if($data['state']){
                return $data;
            }
            // 2.2 修改数据至数据库中
            $rel = Mgd_dev::edit_common_infor($post);
            return ['state'=>0];
        // 3.0 删除通用设备    
        }elseif($post['flag'] == 'delete_common_device'){
            // 3.1 检测操作者的身份
            $power = User::get_user_power_by_username($post['username']);
            if($power != 2){
                return [
                    'state'=>1,
                    'message'=>'请更换组管理的身份再操作'
                ];
            }
            // 3.2 删除通用设备
            $rel = Mgd_dev::deleteAll(['id'=>$post['dev_id']]);
            return ['state'=>0];
        }   
    }
}
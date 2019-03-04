<?php


namespace frontend\controllers;
use Yii;
use yii\web\Controller;
use frontend\models\Log;
use frontend\models\Login;
use frontend\models\Common;
use yii\data\Pagination;

/**
* 日志管理显示控制器
* 功能 查看日志 导出日志
*
*
*
*  @copyright Copyright (c) 2017 – www.zhiweiya.com
*  @author yaolianmin
*  @version 1.0 2017/10/26 14:06
*/


class LogController extends Controller{
    //日志显示主页面
	public function actionLog(){
       //判断是否存在session用户,没有则返回登陆页面
        if(!isset(Yii::$app->session['user_name'])){
           return $this->redirect(['index/index']); 
        }
        
        //判断是否为普通用户登入
        if(Yii::$app->session['power'] == '15'){
            exit();
        }
		//判断是否是语言切换提交的内容
        if(Yii::$app->request->isGet){
	        $language = Yii::$app->request->get('lang'); 
            $result = Common::change_language($language);
            if($result == 'success'){
            	return $this->redirect(['log/log']);
            }
        }
         
        //获取日志表中的信息
        $export_info = '';//获得导出日志的内容，未导出则不出现
       
        $post_infor='';
        $user_name = Yii::$app->session['user_name'];//用户名
        $user_power = Yii::$app->session['power'];  //用户自己的权限级别

        $get = Yii::$app->request->get(); 
        $log_type = Yii::$app->request->get('log_type');//获取日志类型
        $log_level = Yii::$app->request->get('log_level');//获取日志级别
        $power = Yii::$app->request->get('power');   //获得身份权限
         
        $self_name   = [$user_name,'user_name']; //传递用户名 
        $other_power = [$power,'log_power'];   //传递权限
        $self_type   = [$user_name,$log_type,'log_type','user_name'];//传递用户名、类型
        $self_level  = [$user_name,$log_level,'log_level','user_name'];//传递用户名、级别
        $level_type  = [$user_name,$log_level,$log_type,'user_name']; //传递用户名、级别、类型
        $power_level = [$power,$log_level,'log_level','log_power']; //传递权限 级别
        $power_type  = [$power,$log_type,'log_type','log_power']; //传递权限 类型
        $power_level_type = [$power,$log_level,$log_type,'log_power']; //传递权限 级别 类型

        if((!$log_type) && (!$log_level)){//无条件筛选
            if(!$power||$power == $user_power){ //代表查自己 无条件

                $page = Log::get_log_pages_by_condition($self_name); //日志分页操作
                $list = Log::get_log_info_by_condition($self_name); //每一页获得的内容
                $export_info = Log::get_all_logs_by_condition($self_name); //获得导出日志的内容，若无导出则不生效

            }elseif($power > $user_power){ //代表查别人 无条件

                $page = Log::get_log_pages_by_condition($other_power); 
                $list = Log::get_log_info_by_condition($other_power);
                $export_info = Log::get_all_logs_by_condition($other_power);
            }
        }elseif($log_type && (!$log_level)){ //仅有日志类型
            $post_infor = $get; //传递到视图中的数据 
            if($power == $user_power){

                $page = Log::get_log_pages_by_conditions($self_type);
                $list = Log::get_log_info_by_conditions($self_type);
                $export_info = Log::get_all_logs_by_conditions($self_type);
            }elseif($power > $user_power){

                $page = Log::get_log_pages_by_conditions($power_type);
                $list = Log::get_log_info_by_conditions($power_type);
                $export_info = Log::get_all_logs_by_conditions($power_type);
            }
        }elseif($log_level && (!$log_type)){ //仅有日志级别
            $post_infor = $get; 
            if($power == $user_power){

                $page = Log::get_log_pages_by_conditions($self_level);
                $list = Log::get_log_info_by_conditions($self_level);
                $export_info = Log::get_all_logs_by_conditions($self_level);
            }elseif($power > $user_power){

                $page = Log::get_log_pages_by_conditions($power_level);
                $list = Log::get_log_info_by_conditions($power_level);
                $export_info = Log::get_all_logs_by_conditions($power_level);
            }
        }elseif($log_type &&$log_level) { //有日志类型和级别
            $post_infor = $get; 
            if($power == $user_power){

                $page = Log::get_log_pages_by_level_type($level_type);
                $list = Log::get_log_info_by_level_type($level_type);
                $export_info = Log::get_all_logs_by_level_type($level_type);
            }elseif($power > $user_power){

                $page = Log::get_log_pages_by_level_type($power_level_type);
                $list = Log::get_log_info_by_level_type($power_level_type);
                $export_info = Log::get_all_logs_by_level_type($power_level_type);

            }
        }

        if($power < $user_power){//防止恶意修改查看级别高的人

            $page = Log::get_log_pages_by_condition($self_name);
            $list = Log::get_log_info_by_condition($self_name);
            $export_info = Log::get_all_logs_by_condition($self_name);
        }

        //导出日志
        $export = Yii::$app->request->get('export'); 
        if($export){
            $filename = '../../../downloadExcel/'.$user_name.time().'.csv';//物理路径
            $upload = fopen($filename,'w');
            $ss = '';
            foreach ($export_info as  $val) {          
                $ss = $val['user_name'].'    '.$val['log_level'].'   '.$val['log_type'].'    '.$val['login_ip'].'    '.date('Y-m-d H:i:s',$val['log_time']).'    '.$val['action_info'].' '.$val['info'].' '.$val['item_info']."\n";
                fwrite($upload, $ss);//写入每行内容
            }       
            return $this->redirect(['log/export','filename'=>$filename]);
         }
        
        
        //获得日志设定表单提交的数据
        if(Yii::$app->request->isPost){
                              
            $op_log_type      = Yii::$app->request->post('op_log_type','0'); //日志操作
            $warning_log_type = Yii::$app->request->post('warning_log_type','0'); //日至警告
            $prompt_log_type  = Yii::$app->request->post('prompt_log_type','0'); //日志提示
            $project_log      = Yii::$app->request->post('project_log','0'); //项目类
            $user_log         = Yii::$app->request->post('user_log','0'); // 用户类
            $file_log         = Yii::$app->request->post('file_log','0'); // 文件类
            $device_log       = Yii::$app->request->post('device_log','0'); // 机种类
            $system_log       = Yii::$app->request->post('system_log','0'); // 系统类
            $upgrade_log      = Yii::$app->request->post('upgrade_log','0'); // 升级类
            $log_save_time    = Yii::$app->request->post('log_save_time','0'); // 保存时长
           
            if($log_save_time <= 0){
                $log_save_time = 1;   
            }elseif($log_save_time>30) {
                $log_save_time = 30; 
            }
            
            //修改用户日志设定至数据库中
            $rel = Log::update_log_type($op_log_type,$warning_log_type,$prompt_log_type,$project_log,$user_log,$file_log,$device_log,$system_log,$upgrade_log,$log_save_time);

            //添加一条日志记录
            Common::add_log(1,8,'update','log setting');

            if($rel){
                return $this->redirect(['log/log']);
            }
        }
        
        //提取用户表中的日志设定
 		 $log_sets = Log::get_userlog_type_by_username($user_name);
       
		return $this->render('log',
                            ['log_sets' =>$log_sets,
                             'list' => $list,
                             'page' => $page,
                             'post_infor' =>$post_infor
                            ]);
	}   


    //导出日志功能
    public function actionExport(){

            $export = Yii::$app->request->get('filename');
            //判断文件是否存在
            if(!file_exists($export)){
               echo '404 Not find file!!';
               exit();
            }

            if($export){   
                $size = filesize($export);
                Header('Content-Type: text/csv;charset=utf-8'); //发送指定文件MIME类型的头信息
                Header("Accept-Ranges: bytes");
                Header("Content-Length:".$size); //发送指定文件大小的信息，单位字节
                Header("Content-Disposition:attachment; filename=".$export); //发送描述文件的头信息，附件和文件名   
                readfile($export);

                unlink($export);//删除所在文件
                //添加一条日志
                Common::add_log(1,8,'download','log');
            }    
  
        return $this->renderPartial('export');
    }

}



 
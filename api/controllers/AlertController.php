<?php

/**
* 模块：系统日志模块
* 作用：查看 添加系统的日常操作信息
* 时间：2018-08-31
* 说明：由于采用了restful api方式请求，所创建的控制器SystemLog 
*      需在config/main.php里面的controller配置 'alert-log',
*      其请求方式get post等会自动的选择该控制器的方法，若有特殊命名需求，
*      可在config/main.php的extraPatterns里面配置
*@author :
*@version :
*/
namespace api\controllers;

use Yii;
use yii\rest\ActiveController;
use api\models\DevLog;
use api\models\User;
use api\models\GroupList;

class AlertController extends ActiveController {

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
     * 方法：警告页面方法
     * 作用：根据不通的人，获得相应的警告日志信息
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
        // 1.0 更换组查看着，获得该用户的警告日志
        if($get['flag'] == 'get_dev_log_information'){
            $power =  User::get_user_power_by_username($get['username']);
            if($power == 15){
                if($get['group_look']){
                    $super_group = '';
                }else{
                    $super_group = DevLog::super_get_groups();
                }
                //根据条件获得日志
                $super_log = DevLog::super_get_alert_log($get);
                return ['user'=>$super_group,'log'=>$super_log];
            }elseif($power == 2){
                if($get['group_look']){
                    $mgt_group = '';
                }else{
                    $mgt_group = DevLog::management_get_groups($get['username']);
                }
                //根据条件获得日志
                $mgt_log = DevLog::management_get_alert_log($get);
                return ['user'=>$mgt_group,'log'=>$mgt_log];
            }else{

                 $mgt_log = DevLog::user_get_alert_log($get);
                 return ['user'=>'','log'=>$mgt_log]; 
            }
        // 2.0 获得改组查看者的组名	
        }elseif($get['flag'] == 'get_dev_log_informations'){
           
        }
    }

}
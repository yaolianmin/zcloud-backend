<?php

/**
* 模块：设备日志模块
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
use api\models\DevInfo;

class DevLogController extends ActiveController {

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


    //
    public function actionIndex(){
        $get = Yii::$app->request->get();
        if(!isset($get['flag'])){
            return [
                'state'=>1,
                'message'=>'请您用正去方式访问'
            ];
        }

        if( $get['flag'] == 'get_dev_log_information'){
            $power =  User::get_user_power_by_username($get['user']);
            if($power == 15){
                $dev_log = DevLog::super_get_dev_log($get);  
            }elseif($power ==2){

            }else{

            }
        }




      return 'qqqqqqqq';
    }


}


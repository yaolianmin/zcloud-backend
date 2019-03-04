<?php

/**
* 模块：系统日志模块
* 作用：查看 添加系统的日常操作信息
* 时间：2018-08-31
* 说明：由于采用了restful api方式请求，所创建的控制器SystemLog 
*      需在config/main.php里面的controller配置 'system-log',
*      其请求方式get post等会自动的选择该控制器的方法，若有特殊命名需求，
*      可在config/main.php的extraPatterns里面配置
*@author :
*@version :
*/
namespace api\controllers;

use Yii;
use yii\rest\ActiveController;
use api\models\SystLog;
use api\models\User;




Class SystemLogController extends ActiveController{


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
        unset($actions['index'], $actions['update'], $actions['create'], $actions['delete'],$actions['view']);
        return $actions;
    }


    /**
     * 方法：系统日志的查询方法
     * 作用：根据相关的系统日志，显示信息
     * 说明：get请求的方法，默认请求index方法,
     *      这里的index方法只做 查询 数据的操作
     */
    public function actionIndex(){
    	$get = Yii::$app->request->get();
        //检测是否含有flag动作
    	if(!isset($get['flag'])){
        	return [
        		'state'=>1,
        		'data'=>'请您用正确的方式访问'
        	];
    	}
    	try{
    	// 1.0 刚进入页面或者ajax 提交的条件 提取各级别用户的日志
    	if($get['flag'] == 'get_all_user_system_information'){ 
    		//1.1 获得用户的级别
    		$user_power = User::get_user_power_by_username($get['username']);
    		// 判断系统日志的七个条件是否有重选
    		if($get['powers']){
            	if(count($get['powers']) == 7){ //表明提交的任然是7个条件
            		$sys_pession = '';
            	}else{
            		$sys_pession = $get['powers'];
            	}
    		}else{
    			$sys_pession = '';
    		}
    		$where = [
    			'sys_pession'=> $sys_pession,       // 需要选择的条件
    			'time_begin'=>  $get['begin'],      // 访问的起始时间
    			'time_end'=>    $get['ends'],       // 访问的结束时间
    			'page'=>        $get['pages'],      // 访问的 起始行数
    			'page_size'=>   $get['page_size'],  // 每一个显示多少条
    			'language'=>    $get['language'],   // 以哪种语言返回
                'username'=>    $get['username']
    		];
    		switch ($user_power) {
    			case 15:
    	        	$super = SystLog::super_get_syslog($where);
    			    return $super;
                case 2:
                    $management = SystLog::management_get_syslog($where);
                    return $management;
                case 1:
                    $user = SystLog::user_get_syslog($where);
                    return $user;
    			default:
    				return [
    					'state'=>1,
    					'data'=>'您的权限数据有误，请联系管理员'
    				];
    		}
    	}
        }catch(\Exception $e){
    	   return $e->getMessage();
    	}
    }
}
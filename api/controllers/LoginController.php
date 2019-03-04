<?php
/**
* 模块：登录验证模块
* 作用：验证用户信息，找回用户密码等功能
* 时间：2018-08-07
* 说明：由于采用了restful api方式请求，所创建的控制器Login 
*      需在config/main.php里面的controller配置 'login',
*      其请求方式get post等会自动的选择该控制器的方法，若有特殊命名需求，
*      可在config/main.php的extraPatterns里面配置
*@author
*@version 
*/

namespace api\controllers;

use Yii;
use yii\rest\ActiveController;
use api\models\User;

class LoginController extends ActiveController{
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
	 *方法：用户验证信息方法
	 *说明：vue前端采用get请求，默认使用index方法
	 */
	public function actionIndex(){  
    	$get = Yii::$app->request->get();
    	if($get['flag'] == 'FvXSh_Q1YnxGkpoWK1HF6hhy'){ // 这个flag是前端传输的数值，请勿改动
    		$password = substr(trim($get['password']),10); //过滤密码加密部分（默认的给密码加了前十位干扰数字）
    		$password =  hash('sha256',$password);
    		$rel = User::check_user_login_information($get['username'],$password);
    		if(!$rel['state']){
    			$return = [
					'state'=>0,
					'message'=>'登录成功',
                    'language'=>$rel['language']
				];
    		}else{
    			$return = $rel;
    		}
    	}else{
    		$return = [
				'state'=>1,
				'message'=>'请用正确的方式访问网站'
			];   
    	}  
        return $return;  	
    }
}

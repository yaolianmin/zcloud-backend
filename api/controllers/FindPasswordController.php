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
use api\models\User;
use yii\rest\ActiveController;



class FindPasswordController extends ActiveController {

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
     * 方法：找回密码的方法
     * 
     */
    public function actionIndex(){
        $get = Yii::$app->request->get();
        //检测是否为本网站的访问请求 
        if(!isset($get['flag']) ){
            return [
                'state'=>1,
                'data'=>'请您用正确的方式访问'
            ];
        }
        //检测用户和邮箱是否有效
        if($get['flag'] == 'find_self_password'){ 
            //检测数据是否合理
            if(!$get['user']){
                return [
                    'state'=>1,
                    'message'=>'用户名不能为空'
                ];
            }
            if(!preg_match('/^[a-z0-9]+([._-][a-z0-9]+)*@([0-9a-z]+\.[a-z]{2,14}(\.[a-z]{2})?)$/i',$get['email'])){
                return [
                    'state'=>1,
                    'message'=>'邮箱格式不正确'
                ];
            }
            //查看邮箱是否为数据库的邮箱
            $rel = User::find()->where(['UserName'=>$get['user']])->one();
            if(!$rel){
                return [
                    'state'=>1,
                    'message'=>'该用户不存在'
                ];
            }
            $rels = User::find()->where(['UserName'=>$get['user'],'email'=>$get['email']])->Asarray()->one();
            if($rels){ //表示用户名和邮箱都正确
                return [
                    'state'=>0,
                    'message'=>''
                ];
            }else{
                return [
                    'state'=>1,
                    'message'=>'邮箱错误，请重新填写'
                ];  
            }
        }elseif($get['flag'] == 'store_self_password'){
            $rel = preg_match('/^[0-9a-zA-Z]{6,10}$/',$get['pass']);
            if(!$rel){
                return [
                    'state'=>1,
                    'message'=>'密码格式错误请重填写'
                ];  
            }
            $pass = hash('sha256',$get['pass']);
            $up = User::updateAll(['Password'=>$pass],['UserName'=>$get['user']]);
            //发送邮箱给客户，告知他正在修改密码操作
            try{
                $mail = Yii::$app->mailer->compose();
                $mail->setTo($get['email']);
                $mail->setSubject('ZWA后台云管理系统');
                $mail->setHtmlBody('Hi,你好,您正在修改南京智威信息科技有限公司的云网站密码，如果不是您本人操作，请马上更改密码');
                if($mail->send()){
                    return [
                        'state'=>0,
                        'message'=>'我们已发送信息至您的邮箱中，请查收'
                    ]; 
                }else{
                    return [
                        'state'=>1,
                        'message'=>'系统正在升级，请稍后再试'
                    ]; 
                }
            }catch(\Exception $e){
                $aa = $e->getMessage();
                return [
                    'state'=>1,
                    'message'=>$aa
                ];
            }
        }else{
           return [
                'state'=>1,
                'message'=>'您访问的操作不存在'
            ]; 
        }
    }

}


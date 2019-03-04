<?php

namespace frontend\controllers;
use Yii;
use yii\web\Controller;

use frontend\models\ContactForm;
use frontend\models\UserCenterForm;
use frontend\models\PasswordModifyForm;
use frontend\models\Common;
use frontend\models\User_management;
use frontend\models\Dev_user;
use frontend\models\Decvice;
use frontend\models\UserManagementForm;
use frontend\models\Country;//该表中存储的是国家信息

class UserCenterController extends Controller
{

    public function actionUserCenter()
    {
         
        $model = new UserCenterForm();
        $now_user = Yii::$app->session['user_name'];
        $devices = Decvice::find()->all();
        $country = Country::find()->all();


        /*
         判断是否存在session用户,没有则返回登陆页面
        */
        if(!isset(Yii::$app->session['user_name'])){
           return $this->redirect(['index/index']); 
        }
        /*
        中英文切换
         */
        if(Yii::$app->request->isGet)
        {
	        $language = Yii::$app->request->get('lang'); 
            if($language)
            {
                $result = Common::change_language($language);
                if($result == 'success')
                {
                	return $this->redirect(['user-center/user-center']);
                }
            }
        }

       
       
        if ($model->load(Yii::$app->request->post()) && $model->validate()) 
        {
            
        	$model->update_userSelf($model);
            //Yii::$app->getSession()->setFlash('success', '保存成功');
            $now_user_info = UserManagementForm::get_user_info($now_user);
            $now_user_devinfo = UserManagementForm::get_devinfo_for_user($now_user);

           return $this->render('user-center', ['model' => $model,
                                                'now_user_info'=> $now_user_info,
                                                'device'=>$devices,
                                                'country' =>$country,
                                                'now_user_devinfo'=>$now_user_devinfo]);

        }else{ /*
                一进来获取当前用户的信息
                */

            $now_user_info = UserManagementForm::get_user_info($now_user);
            $now_user_devinfo = UserManagementForm::get_devinfo_for_user($now_user);
            return $this->render('user-center', ['model' => $model,
                                                'now_user_info'=> $now_user_info,
                                                'device' => $devices,
                                                'country' => $country,
                                                'now_user_devinfo'=>$now_user_devinfo]);
        }
    }


    public function actionModifyPassword()
    {


        $password = new PasswordModifyForm();
        $now_user = Yii::$app->session['user_name'];
       
        /*
        中英文切换
         */
        if(Yii::$app->request->isGet)
        {
            $language = Yii::$app->request->get('lang'); 
            if($language)
            {
                $result = Common::change_language($language);
                if($result == 'success')
                {
                    return $this->redirect(['user-center/user-center']);
                }
            }
        }

        /*
        一进来获取当前用户的信息
         */
        $tips ="";
        if ($password->load(Yii::$app->request->post()) && $password->validate()) 
        {
            // var_dump(md5($model->primary_password));
            // $now_user =Yii::$app->session['user_name'];
            // $now_user_info = UserManagementForm::get_user_info($now_user);
            // var_dump($now_user_info['password']);
            // exit;
                    
            $tips = $password->update_UserSelf_password($password);

                    if($tips == "success")
                    {  //修改成功后跳转到登录界面  
                       return $this->redirect(array('index/main','action'=>'111'));
                    }else{

                        return $this->render('modify-password', ['password' => $password]);
                    }

        }else{

            return $this->render('modify-password', ['password' => $password]);
        }

    }








}
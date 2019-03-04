<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\db\Command;
use frontend\models\User_management;
use frontend\models\Dev_user;
use frontend\models\UserManagementForm;
use frontend\models\Common;

/**
 * UserCenterForm is the model behind the user-center form.
 */
class PasswordModifyForm extends Model
{
    public $primary_password;
    public $new_password;
    public $re_password;
    public $flag;
    public function rules()
    {
        return [
            
            [['primary_password','new_password','re_password'], 'required'],
            ['flag','safe'],
            [['new_password'], 'string', 'max' => 32],
            ['primary_password','check_primary_password'],
            [['re_password','new_password'],'check_new_password'],
        ];
    }

    public function check_primary_password($attribute){

        $now_user =Yii::$app->session['user_name'];
        $now_user_info = UserManagementForm::get_user_info($now_user);

        if(md5($this->primary_password) != $now_user_info['password']){

            $this->addError($attribute,'原始密码输入不正确'); 

        }

    }

    public function check_new_password($attribute){

        if($this->re_password != $this->new_password){

            $this->addError($attribute,'两次密码输入不一致'); 
        }

    }

    public  function update_UserSelf_password($model)//更新用户个人的密码
    {
        $now_user =Yii::$app->session['user_name'];
        $user = new User_management(); 

        $count = $user->updateAll(array('password'=>md5($model->new_password),'created_time' => date('Y-m-d H:i:s',time())),
                'user_name=:username',array(':username'=>Yii::$app->session['user_name'])); 
        if($count > 0){
            Common::add_log(1,5,'update',$now_user,'self_password');
            return $ti = "success";
        }else {
            return $ti = "failed";
        }

       
    }


}
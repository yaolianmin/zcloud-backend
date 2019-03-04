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
class UserCenterForm extends Model
{
    public $username;
    public $password;
    public $re_password;
    public $primary_password;
    public $confirmpassword;
    public $email;
    public $power;
    public $device;
    public $give_device;
    public $country;
    public $phone;
    public $remark;
    public $flag;
    public $device_info_show;


    public function rules()
    {
        return [
            // name, email, password and power are required
            [['username', 'email','power','remark','flag'], 'required'],
            // email has to be a valid email address
            ['email', 'email'],
            [['power','phone'], 'integer'],//这里说明level是一个integer的变量
            [['country','password','primary_password'],'safe'],
            [['device_info_show','re_password','remark'], 'string', 'max' => 128],
            [['username'], 'string', 'max' => 32],
            [['password'], 'string', 'max' => 32],
        ];
    }

    

    public  function update_userSelf($model)//更新用户个人的信息
    {
       UserManagementForm::execAction($model);

    }


}

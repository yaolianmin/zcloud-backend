<?php

namespace frontend\models;
use yii\db\ActiveRecord;

/**
* 登录页面操作模型
* 功能：供登录控制器调用函数
*
*
*
*  @copyright Copyright (c) 2017 – www.zhiweiya.com
*  @author yaolianmin
*  @version 1.0 2017/10/18 14:06
*/
class Login extends ActiveRecord{
    public $user_name;
    public $password;
    public $verify;
    public $language;
    public $remember_information;
    
    //连接user_management表
    public static function tableName(){
        return '{{user_management}}';
    }

	public function attributeLabels() {
        return [
            'user_name' => '用户名',
            'password'  => '密码',
            'verify'    => '验证码',
            'language'  => '语种'
        ];
    }

    //验证规则
    public function rules() {
        return [
            [['user_name', 'password', 'verify'], 'required', 'message' => '{attribute}不能为空'],
            ['user_name', 'string', 'min' => 1, 'max' => 32, 'tooShort' => '{attribute}长度必须在3个以上', 'tooLong' => '{attribute}长度必须在32个以内'],
            ['verify', 'match', 'pattern' => '/^[a-zA-Z0-9]{4}$/', 'message' => '{attribute}格式不正确'],
            ['password', 'match', 'pattern' => '/^[a-zA-Z0-9]{6,32}$/', 'message' => '{attribute}密码是6-32位的数字字母'],
            ['language','check_login']
        ];
    }
    
    /**
    * 初始化登录信息
    * 参数：$attribute
    */
    public function check_login($attribute){
        //判断验证码是否超时
        if(isset($_COOKIE['verify'])){
            //检验验证码
            //var_dump($_COOKIE['verify']);
            if($this->verify == strtolower($_COOKIE['verify'])) {
                //查找个人信息
                $remember_information = Login::find()->where(['user_name' => $this->user_name])->asArray()->one();
                if($remember_information){
                    if(md5($this->password) == $remember_information['password']){
                        \Yii::$app->session['language']  = $this->language; //存入语种
                        \Yii::$app->session['user_name'] = $this->user_name; //存入名字
                        \Yii::$app->session['power']     = $remember_information['power']; //存入权限
                    }else{
                        $this->addError($attribute,'密码错误!');
                    }   
                }else{
                    $this->addError($attribute,'用户名错误!');
                }
            }else{
                $this->addError($attribute, '验证码错误!'); 
            } 
        }else{
            $this->addError($attribute, '验证码超时!');  
        }   

    }
   
}
<?php
namespace frontend\models;
use yii\db\ActiveRecord;


/**
* Forget模型
* 验证页面提交的表单数据
* 找回密码
*
*
*
*  @copyright Copyright (c) 2017 – www.zhiweiya.com
*  @author yaolianmin
*  @version 1.0 2017/9/20 10:06
*/
class Forget extends ActiveRecord{
    public $user_name;
    public $passwords;
    public $re_password;
    public $phone;
    public $verify;
    public $user_information; 


    
    /**
    * 链接数据库表
    */
    public static function tableName(){
        return '{{user_management}}';
    }


    public function attributeLabels(){
    	return [
            'user_name'    => '用户名',
            'passwords'    => '密码',
            're_password' => '确认密码',
            'phone'       => '此项内容',
            'verify'      => '验证码'
    	];
    } 


    //验证规则
     public function rules(){
    	return [
            [['user_name', 'passwords', 'verify','re_password','phone'], 'required', 'message' => '{attribute}不能为空'],

            ['passwords', 'match', 'pattern' => '/^[a-zA-Z0-9]{6,10}$/', 'message' => '{attribute}格式不正确'],

            ['re_password','check_password'],

            ['verify', 'check_verify']


    	];
    } 

    
    /**
    * 验证 两次密码是否一致
    * 参数：$attribute
    */
    public function check_password($attribute){
      if($this->passwords != $this->re_password){
          $this->addError($attribute,'两次密码不一致'); 
      } 
    }

    /**
    * 验证 验证码
    * 验证 用户信息是否正确
    * 参数：$attribute
    */
    public function check_verify($attribute){
        //判断验证码是否超时
        if(isset($_COOKIE['verify'])){
            //校验 验证码
            if($this->verify == strtolower($_COOKIE['verify'])){
                //判断是 手机验证提交 还是邮箱提交
                if(preg_match("/^[1][3,4,5,7,8][0-9]{9}$/", $this->phone)){
                    //根据手机信息查找
                    $user_information = self::findOne(['user_name' =>$this->user_name,'phone' =>$this->phone]);
                    if($user_information){
                        //判断密码是否与原来的一样 
                        if($user_information->password != md5($this->passwords)){

                            $result = self::updateAll(['password' =>md5($this->passwords)],['user_name' =>$this->user_name]);
                            if(!$result){//表示修改密码失败
                                $this->addError($attribute,'系统正在升级，稍后再试！');
                            }
                        }else{
                            $this->addError($attribute,'新密码不能与原来一样');
                        }                
                    }else{
                        $this->addError($attribute,'用户名或手机号错误');
                    }           
                }elseif(preg_match("/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i", $this->phone)){
                    //根据邮箱查找
                    $user_information = self::findOne(['user_name' =>$this->user_name,'email' =>$this->phone]);
                    if($user_information){
                        //判断密码是否与原来的一样 
                        if($user_information->password != md5($this->passwords)){

                            $result = self::updateAll(['password' =>md5($this->passwords)],['user_name' =>$this->user_name]);
                            if(!$result){//表示修改密码失败
                               $this->addError($attribute,'系统正在升级，稍后再试！');
                            }
                        }else{
                            $this->addError($attribute,'新密码不能与原来一样');
                        }
                    }else{
                        $this->addError($attribute,'用户名或邮箱错误');
                    }
                }else{
                    $this->addError($attribute,'邮箱或手机格式不正确');  
                }
           }else{
            $this->addError($attribute,'验证码不正确');
           }
       }else{
           $this->addError($attribute,'验证码超时！');
       }
    }




}
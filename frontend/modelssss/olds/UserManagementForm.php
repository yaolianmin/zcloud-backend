<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use frontend\models\User_management;
use frontend\models\Project_management;
use frontend\models\Dev_user;
use yii\db\Command;
use yii\data\Pagination;//分页
use frontend\models\Common;//引入日志
/**
 *UserManagementForm is the model behind the UserManagement Form.
 */
class UserManagementForm extends Model
{
    public $flag;
   //  public $username_search;
   // public $power_info;
   //  public $device_info;
   // public $project_info;
    public $username;
    public $password;
    public $re_password;
    public $email;
    public $power;
    //public $give_device;
    //public $project;
    public $device;
    public $country;
    public $phone;
    public $remark;
    public $powerwer;
    public $radio_apply;
    public $device_info_show;

   
    /**
    *数据验证规则
    */
    public function rules()
    {
        return [
            [['power','username','password','re_password'],'required'],
            [['flag','remark','country'],'string','message' => '{attribute}不能为空'],
            [['power','phone',],'integer'],
            ['password', 'string', 'max' => '128','min'=>'6' , 'message' => '{attribute}格式不正确'],
            //['re_password','check_password'],
            ['email', 'email','message' => '{attribute}格式不对'],
            //[['password','re_password','device','power','username'],'safe'],
            ['device_info_show', 'string', 'max' => 256],
            //[['power_info','device_info','project_info','username_search','device_info_show'], 'string', 'max' => 256],
           // [['username'], 'string', 'max' => 32,'message' => '{attribute}不能为空'],
        ];
    }

    /**
    * 验证 两次密码是否一致
    * 参数：$attribute
    */
    public function check_password($attribute){
      if($this->password != $this->re_password){
          $this->addError($attribute,'两次密码不一致'); 
      } 
    }
    /*function:
    *   新增一个新用户
    */
    public function add_user($model)//新增一个用户
    {
        $user = new User_management();
        
        $count= User_management::find()->where(['user_name' => $model->username])->count();
        
        if($count >0)
        {
                //echo "新增失败，用户名已经存在";
        }else{
                $user->user_name = $model->username;
                $user->password = md5($model->password);
                $user->power = $model->power;
                $user->created_time = date('Y-m-d H:i:s',time());
                $user->phone = $model->phone;
                $user->email=$model->email;
                $user->country = $model->country;
                $user->remark =$model->remark;
                $user->save();
              
                self::add_dev_user($model); 
                Common::add_log(1,5,'add',$model->username,'user');   
                //echo "新增成功"; 
        }
    
    }
    /*funtion:
    *   更新某个用户的信息
    */
    public  function update_user($model)//更新某个用户的信息
    {
        $user = new User_management(); 
        if($model->password == "no_changepassword"){

            $count=$user->updateAll(array('power'=>$model->power,'phone'=>$model->phone,'email'=>$model->email,'country'=>$model->country,
            'remark'=>$model->remark,'created_time' => date('Y-m-d H:i:s',time())),'user_name=:username',array(':username'=>$model->username));
        }else
        {
           $count=$user->updateAll(array('power'=>$model->power,'password'=>md5($model->password),'phone'=>$model->phone,'email'=>$model->email,
            'country'=>$model->country,'remark'=>$model->remark,'created_time' => date('Y-m-d H:i:s',time())),
            'user_name=:username',array(':username'=>$model->username)); 
        }
        
        /**
         * 更新用户分配的机种信息
         * 先删除，再添加
         */
        self::del_dev_user($model);
        self::add_dev_user($model);

        if($count> 0){ 

            //echo "修改成功"; 
            //$time = time();
            //echo date('Y-m-d H:i:s',$time);
            Common::add_log(1,5,'update',$model->username,'information');
        }else{

            //echo "修改失败"; 
        }

    }
    
    /*
    *function: delete
    * 删除某个用户的信息
    *
    */
    public function del_user($model)
    {
        $user = new User_management();
      
        $count = $user->deleteAll('user_name=:name ',array(':name'=>$model->username));
        Project_management::deleteAll('user_name=:name ',array(':name'=>$model->username));
        Dev_user::deleteAll('user_name=:name ',array(':name'=>$model->username));

        //self::del_dev_user($model);
        if($count> 0){ 
            Common::add_log(1,5,'delete',$model->username,'user');
            //echo "删除成功"; 
        }else{

            //echo "删除失败"; 
        }
    }


    /*function:
    *   往dev_user关系表中添加关系记录
    */
    public function add_dev_user($model)
    {   
        $device_info_show = explode(",",$model->device_info_show);
        foreach ( $device_info_show as  $value){
                    $dev_user =new Dev_user();
                    $dev_user->user_name = $model->username;
                    $dev_user->power = $model->power;
                    $dev_user->device_name = $value;
                    $dev_user->project_name= "no-project";
                    $dev_user->save();
                }
    }


    /*function:
    /   在dev_user表中删除记录
    */
    public function del_dev_user($model)
    {
        $dev_user =new Dev_user();
        $dev_user->deleteAll('user_name=:name and project_name =:pro_name',array(':name'=>$model->username,':pro_name'=>'no-project'));
        //Project_management::updateAll()

    }


    /*function：
    *   根据标记值来执行相应的动作
    */
    public function execAction($model)
    {
        if($model->flag == "update")
        {

           self::update_user($model);
        }else if($model->flag == "delete")
        {
            if($model->username == Yii::$app->session['user_name']){
                return;//不能删除自己
            }
            self::del_user($model);
        }else if($model->flag == "add")
        {
            
            self::add_user($model);
             
        }
    }

    /*function;
    *   根据传入的权限字符串进行转换
    */
    public function powerinfo2String($primary)
    {
        if($primary == "管理员,业务员,普通用户" || $primary == "Administrators,Salesman,NormalUser" ){
            $primary = "1,5,15";
           
        }else if($primary == "管理员,业务员" ||  $primary == "Administrators,Salesman"){

            $primary = "1,5";
        }else if($primary == "管理员,普通用户" ||  $primary == "Administrators,NormalUser"){

            $primary = "1,15";
        }else if($primary == "业务员,普通用户" ||  $primary == "Salesman,NormalUser"){

            $primary = "5,15";
        }else if($primary == "管理员" || $primary == "Administrators"){

            $primary = "1";
        }else if($primary == "业务员" || $primary == "Salesman"){

            $primary = "5";
        }else if($primary == "普通用户" || $primary == "NormalUser"){

            $primary = "15";
        }
        return $primary;
    }

    /*function:
    *       获取某个用户的基本信息
    */
    public function get_user_info($user_name)
    {
        $now_user_info = User_management::find()->where(['user_name' => $user_name])->all();
        foreach ($now_user_info as  $value){
           
        }

        return $value;
    }



    public function get_login_user_power($login_user_name)
    {
        $login_user_info= User_management::find()->where(['user_name' => $login_user_name])->all();
        foreach ($login_user_info as $value) 
        {
            $login_user_power=$value['power'];
        }

       return $login_user_power;
    }

    /*function;
    *   获取根据查询条件得到的sql语句
    */
   public function get_user_search_sql_condition($model)
   {
        $login_username_now = Yii::$app->session['user_name'];
        $login_user_power = Yii::$app->session['power'];

        if($login_user_power == 1)//当管理员用户登录查询时
        { 
            $sql_condition = User_management::find();
            if($model->username_search)//当用户名条件不为空时
            {
                $sql_condition->andFilterWhere(['like','user_name', $model->username_search]);

            }
            if($model->power_info)//当权限搜索条件不为空时
            {
                $model->power_info =  explode(",",self::powerinfo2String($model->power_info));
                $sql_condition->andFilterWhere(['power'=>$model->power_info]);
            }
            if($model->device_info!= NULL || $model->project_info!=NULL){ 
                $_dev_search_condition = Dev_user::find();
                if($model->device_info)//当机种搜索条件不为空时
                {
                    $model->device_info = explode(",",$model->device_info);
                    $_dev_search_condition->andFilterWhere(['device_name'=>$model->device_info]);
                }
                if($model->project_info)
                {
                    $model->project_info = explode(",",$model->project_info);
                    $_dev_search_condition->andFilterWhere(['project_name'=>$model->project_info]);
                }

                $_search_by_device = $_dev_search_condition->asArray()->all();
                $str_pr = "";
                foreach ($_search_by_device as  $val) 
                {
                    if($val['user_name']){
                        $str_pr.=$val['user_name'].',';
                    }        
                }
                $_foe = explode(",",$str_pr);
                $sql_condition->andFilterWhere(['user_name'=>$_foe]);
            }

        }else if($login_user_power == 5)//当业务员登录查询时
        {   
            $_project_search_condition = Project_management::find()->andFilterWhere(['project_manager'=>$login_username_now]);

            $_dev_search_condition = Dev_user::find();
            if($model->username_search)//当用户名条件不为空时
            {
                 $_project_search_condition->andFilterWhere(['like','user_name', $model->username_search]);

            }
            if($model->device_info != NULL || $model->project_info != NULL){

                if($model->device_info)//当机种搜索条件不为空时
                {
                    $model->device_info = explode(",",$model->device_info);
                    $_dev_search_condition->andFilterWhere(['device_name'=>$model->device_info]);
                }
                if($model->project_info)//当项目搜索条件不为空时
                {   
                    $model->project_info = explode(",",$model->project_info);
                    $_dev_search_condition->andFilterWhere(['project_name'=>$model->project_info]);
                }
                $_search_by_device = $_dev_search_condition->asArray()->all();
                $str_dev = "";
                foreach ($_search_by_device as  $val) 
                {
                    if($val['user_name']){
                        $str_dev.=$val['user_name'].',';
                    }        
                }
                $_foe_dev = explode(",",$str_dev);
                $_project_search_condition->andFilterWhere(['user_name'=>$_foe_dev]);
            }
            $_search_by_project = $_project_search_condition->asArray()->all();
            $str_pro = "";
            foreach ($_search_by_project as  $val) 
            {
                if($val['user_name']){
                    $str_pro.=$val['user_name'].',';
                }        
            }
            $_foe_pro = explode(",",$str_pro);

            $sql_condition = User_management::find()->andFilterWhere(['user_name'=>$_foe_pro]);
            
            if($model->power_info)//当权限搜索条件不为空时
            {   
                $power_info =  explode(",",self::powerinfo2String($model->power_info));
               
                if($model->power_info == "普通用户" || $model->power_info == "NormalUser"){

                    $sql_condition->andFilterWhere(['power'=>$power_info]);
                }else if($model->power_info ==  "业务员" || $model->power_info == "Salesman"){

                    $sql_condition = User_management::find()->andFilterWhere(['user_name'=>$login_username_now]);
                }else{

                    $sql_condition->orFilterWhere(['user_name'=>$login_username_now]);
                }
  
            }else {
               $sql_condition->orFilterWhere(['user_name'=>$login_username_now]);
            }

        }else if ($login_user_power == 15) {//当普通用户登录查询
            $sql_condition = User_management::find()->andFilterWhere(['user_name'=> $login_username_now]);
        }

        return $sql_condition;
   }
    


    /*function:
    *   获取搜索 用户记录数
    */
    public function get_user_search_pages($sql_condition)
    {
        
        $page_num = $sql_condition->asArray()->count();
        return $page_num;
    }

    /*function:
    *   获取搜索结果
    */
    public function get_search_results($sql_condition,$pagination)
    {

       $user_search_results = $sql_condition->orderBy('power')  
                            ->offset($pagination->offset)  
                            ->limit($pagination->limit)->asArray()                                                     
                            ->all();
        return  $user_search_results;
    }


    /*function:
    *   获取登录时，显示的用户记录数
    */
    public function get_login_pages()
    {
        $login_username_now = Yii::$app->session['user_name'];
        $login_user_power = Yii::$app->session['power'];

        if($login_user_power == 1)//管理员登录时
        {
            
            $page_num = User_management::find()->count();
        }else if ($login_user_power == 5)//业务员登录时 
        {

            $page_num = User_management::find()->where([ 'or',['power' => 15],['user_name' => $login_username_now]])->count();
        }else if($login_user_power == 15)//普通用户登录时 
        {
            $page_num = User_management::find()->where(['user_name' => $login_username_now])->count();
        }

        return $page_num;
    }

    /*function:
    *   获取分页对象
    */
    public function get_usermanagement_pagination($page_num)
    {
        $pagination = new Pagination([  
                        'defaultPageSize' => 15,  
                        'totalCount' => $page_num,    
                        ]);
        
        return $pagination;
    }


    /*function:
    *   获取到第一次登录显示的结果
    */
    public function get_user_showinfo($pagination)
    {
        $login_username_now = Yii::$app->session['user_name'];
        $login_user_power = Yii::$app->session['power'];

        if($login_user_power == 1)
        {
            $_showinfo = User_management::find()->orderBy('power')  
                                ->offset($pagination->offset)  
                                ->limit($pagination->limit)->asArray()                                                     
                                ->all();

        }else if ($login_user_power == 5)//业务员登录时，要查询他下面管理了哪些用户
        {
           $_showinfo = User_management::find()->orderBy('power')
                                                ->where([ 'or',['power' => 15],['user_name' => $login_username_now]])
                                                ->offset($pagination->offset)  
                                                ->limit($pagination->limit)->asArray()                                                     
                                                ->all();
                                                //var_dump($project_showinfo[0]['id']);
        }else if ($login_user_power = 15) 
        {
           $_showinfo = User_management::find()->orderBy('power') 
                                                ->where(['user_name' => $login_username_now]) 
                                                ->offset($pagination->offset)  
                                                ->limit($pagination->limit)->asArray()                                                     
                                                ->all();
        }

        return  $_showinfo;
    }

    /*function:
    *   将用户的机种信息插入用户基本信息中
    */
    public function push_devinfo_in_userinfo($page_num,$no_devinfo_userinfo)
    {
        for($i=0;$i<$page_num;$i++)
        {
            $user_devinfo =  self::get_devinfo_for_user($no_devinfo_userinfo[$i]['user_name']);
            array_push($no_devinfo_userinfo[$i],$user_devinfo);
        }

        return $no_devinfo_userinfo;
    }

    /*function:
    *   根据用户名去找对应用户拥有的机种
    */
    public function get_devinfo_for_user($_username)
    {
        $dev_info_one = Dev_user::find()->andFilterWhere(['user_name' => $_username])
                                        ->andFilterWhere(['=','project_name' , 'no-project'])
                                        ->asArray()->all();
        $str= "";
        foreach ($dev_info_one as  $val) 
        {
            if($val['device_name']){
                $str.=$val['device_name'].',';
            }        
        }
        return $str;
    }

    /*function:
    *   获取项目名用于搜索条件
    */
    public function get_projectname_for_search()
    {
        $login_username_now = Yii::$app->session['user_name'];
        $login_user_power = Yii::$app->session['power'];
        if($login_user_power == 1){

           $_projectname_for_search = Project_management::find()->asArray()->all();
        }else if($login_user_power == 5){

            $_projectname_for_search = Project_management::find()->andFilterWhere(['project_manager' => $login_username_now])
                                                                ->asArray()->all();
        }else if($login_user_power == 15){

            $_projectname_for_search = Project_management::find()->andFilterWhere(['user_name' => $login_username_now])
                                                                ->asArray()->all();
        }

        return $_projectname_for_search;
    }

    /*function;
    *   获取用于机种名用于搜索
    */
    public function get_dev_for_search(){


    }



}

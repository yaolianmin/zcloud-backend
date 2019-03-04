<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use frontend\models\Project_management;//对应数据库的表project_mangagement;
use frontend\models\User_management;
use frontend\models\Dev_user;
use yii\db\Command;
use yii\data\Pagination;//分页
use frontend\models\Common;
/**
 * ProjectManagementForm is the model behind the projectmanagement form.
 */
class ProjectManagementForm extends Model
{
    public $action_flag;
    //public $projectname_search;
   // public $power_condition;
    //public $devname_condition;
    //public $username_condition;
    public $devname_select;
    //public $username_select;
    public $projectname_add;
    public $project_manager;
    public $project_owner;
    public $project_id;
    public $remark;
    public $device_info_show;

   
    /**
    *数据验证规则
    */
    public function rules()
    {
        return [
           
            
            
            [['projectname_add','project_manager','project_owner'],'required'],
            [['action_flag','remark'],'string','message' => '{attribute}不能为空'],
            
            //[['projectname_search','power_condition','devname_condition','username_condition','devname_select','username_select',
            //'device_info_show'], 'string', 'max' => 256],
            [['project_id','devname_select','device_info_show'],'safe'],

            [['projectname_add','project_manager','project_owner'], 'string', 'max' => 32,'message' => '{attribute}不能为空'],
        ];
    }

    /*function:
    *       add a new project
    *       新增一个项目
    */
    public function add_new_project($model)
    {
        $project = new Project_management();
        $count = Project_management::find()->where(['project_name' => $model->projectname_add])->count();
    
        if($count > 0)
        {
                //echo "新增失败，该项目已经存在";
        }else{
                $project->project_name = $model->projectname_add;
                $project->user_name = $model->project_owner;
                $project->project_manager = $model->project_manager;
                $project->created_time = date('Y-m-d H:i:s',time());
                $project->remark =$model->remark; 
                $project->save();

                self::add_dev_user_byproject($model);
                // self::add_dev_user($model); 
                /*
                *写日志
                */
                Common::add_log(1,4,'add',$model->projectname_add,'new project');   
                //echo "新增项目成功"; 
        }
    
    }

    /*function: 
    *       update project info
    *       更新某个项目的信息
    */
    public  function update_project_info($model)
    {
        $project = new Project_management();
        $count = $project->updateAll(array( 'project_name' => $model->projectname_add,
                                            'user_name' => $model->project_owner,
                                            'project_manager' => $model->project_manager,
                                            'remark' => $model->remark,
                                            'created_time' => date('Y-m-d H:i:s',time())),
                                            'id=:projectid',array(':projectid' => $model->project_id)); 
    
        /**
         * 更新用户项目中分配的机种信息
         * 先删除，再添加
         */
        
        self::del_dev_user_byproject($model);
        self::add_dev_user_byproject($model);

        if($count > 0){ 

            //echo "修改项目信息成功"; 
            //$time = time();
            //echo date('Y-m-d H:i:s',$time);
            Common::add_log(1,4,'update',$model->projectname_add,'project information');
        }else{

            //echo "修改项目信息失败"; 
        }

    }
    
    /*
    *function: 
    *       delete one project 
    *       删除某个项目
    */ 
    public function del_project($model)
    {
        $project = new Project_management();
        $count = $project->deleteAll('project_name=:projectname and user_name=:owner',
                                    array(':projectname' => $model->projectname_add,':owner' => $model->project_owner));

 
        self::del_dev_user_byproject($model);
        if($count> 0){ 
            Common::add_log(1,4,'delete',$model->projectname_add,'project');
            //echo "删除成功"; 
        }else{

           // echo "删除失败"; 
        }
    }

    /*function:
    *      get flag exec action
    *      获取标记 执行相应动作
    */
    public function execAction($model)
    {
        if($model->action_flag == "update")
        {

           self::update_project_info($model);
        }else if($model->action_flag == "delete")
        {

            self::del_project($model);
        }else if($model->action_flag == "add")
        {

            self::add_new_project($model);
        }
    }

    /*function:
    * 往dev_user这张表中加入该项目的机种信息
    */
    public function add_dev_user_byproject($model)
    {
        $device_info_show = explode(",",$model->device_info_show);

        if($model->project_id == "0")
        {
            $new_project = Project_management::find()->where(['project_name'=>$model->projectname_add])->asArray()->all();
            $model->project_id = $new_project[0]['id'];
        }

        foreach ( $device_info_show as  $value){
                    $dev_user =new Dev_user();
                    $dev_user->user_name = $model->project_owner;
                    $dev_user->power = 15;
                    //$dev_user->power = $model->power;
                    $dev_user->device_name = $value;
                    $dev_user->project_name = $model->projectname_add;
                    $dev_user->project_id = $model->project_id;
                    $dev_user->save();
                }

    }

    /*function：
    *删除 dev_user表中关于该项目的信息
    *通过项目id去删除
    */
    public function del_dev_user_byproject($model)
    {
        $dev_user =new Dev_user();
        $count = Dev_user::find()->where(['project_id'=>$model->project_id])->count();
        if($count>0)
        {
            $dev_user->deleteAll('project_id=:id',array(':id'=>$model->project_id,));
        }
    }



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


    public function get_user_info($user_name)
    {
        $now_user_info = User_management::find()->where(['user_name' => $user_name])->all();
        foreach ($now_user_info as  $value){
           
        }

        return $value;
    }

    /*function:
    *   获取某个用户的权限
    */
    
    public function get_user_power($_user_name)
    {
        $_user_info= User_management::find()->where(['user_name' => $_user_name])->all();
        foreach ($_user_info as $value) 
        {
            $_user_power=$value['power'];
        }

       return $_user_power;
    }

    /*function:
    *   根据搜索条件获取sql语句
    */
    public function get_project_search_sql_condition($search)
    {
        $login_username_now = Yii::$app->session['user_name'];
        $login_user_power = Yii::$app->session['power'];

        if($search->power_condition == "0"){

                $search->power_condition=NULL;
            }else{
                 $search->power_condition =  explode(",",$search->power_condition);
            }
           
            if($search->devname_condition == "0"){

                $search->devname_condition = NULL;
            }else{
                  $search->devname_condition =  explode(",",$search->devname_condition);
            }

        if($login_user_power == 1)//当管理员用户登录查询时
        {   
            $sql_condition = Project_management::find();
            if($search->projectname_search){//若项目名不为空，追加条件
                
                $sql_condition->andFilterWhere(['like','project_name', $search->projectname_search]);
             }
            if($search->username_condition){
                $user_power = self::get_user_power($search->username_condition);
                if($user_power == 5){

                    $sql_condition->andFilterWhere(['project_manager' =>$search->username_condition]);
                }
                else{
                         $sql_condition->andFilterWhere(['user_name' => $search->username_condition]);
                    }    
            }

            $_dev_search_condition  =  Dev_user::find()->andFilterWhere(['<>','project_name', "no-project"]);
            if($search->power_condition)
            {
                $_dev_search_condition->andFilterWhere(['power' => $search->power_condition]);
            }
            if($search->devname_condition)
            {
                $_dev_search_condition->andFilterWhere(['device_name' => $search->devname_condition]);
            }
            $_dev_search  = $_dev_search_condition->asArray()->all();   
            $str_pr = "";
            foreach ($_dev_search as  $val) 
            {
                if($val['project_id']){
                    $str_pr.=$val['project_id'].',';
                }        
            }
            $_foe = explode(",",$str_pr);

            $sql_condition->andFilterWhere(['id' => $_foe]);

            //$page_num = $sql_condition->asArray()->count();
           // var_dump($page_num);
           
        }else if ($login_user_power == 5)//当业务员登录查询时 
        {

            $redult = Project_management::find()->where(['project_manager' => $login_username_now])->asArray()->all();
            $str= "";
            foreach ($redult as  $val) 
            {
                if($val['id']){
                    $str.=$val['id'].',';
                }        
            }
            $_info = explode(",",$str);
            

            $sql_condition = Project_management::find()->andFilterWhere(['project_manager' => $login_username_now]);

            if($search->projectname_search){//若项目名不为空，追加条件
                
                $sql_condition->andFilterWhere(['like','project_name', $search->projectname_search]);
            }
            if($search->username_condition){//当用户名不为空，追加条件

                $sql_condition->andFilterWhere(['user_name' => $search->username_condition]);
            }
            //根据权限搜索条件和机种名搜索条件 去Dev_user表中去搜索符合条件的项目的ID
            $_dev_search_condition  =  Dev_user::find()->andFilterWhere(['project_id' => $_info]);
            if($search->power_condition)
            {
                $_dev_search_condition->andFilterWhere(['power' => $search->power_condition]);
            }
            if($search->devname_condition)
            {
                $_dev_search_condition->andFilterWhere(['device_name' => $search->devname_condition]);
            }
            $_dev_search  = $_dev_search_condition->asArray()->all();   
            $str_pr = "";
            foreach ($_dev_search as  $val) 
            {
                if($val['project_id']){
                    $str_pr.=$val['project_id'].',';
                }        
            }
            $_foe = explode(",",$str_pr);

            $sql_condition->andFilterWhere(['id' => $_foe]);

            //$page_num = $sql_condition->asArray()->count();
            //var_dump($page_num);            
        }else if($login_user_power == 15)
        {

             $sql_condition = Project_management::find()->andFilterWhere(['user_name' => $login_username_now]);
        }

        return  $sql_condition;
    }

    /*function:
    *   获取搜索 项目记录数
    */
    public function get_project_search_pages($sql_condition)
    {
        
        $page_num = $sql_condition->asArray()->count();
        return $page_num;
    }

    /*function:
    *   获取搜索结果
    */
    public function get_search_results($sql_condition,$pagination)
    {
        $project_search_results = $sql_condition->orderBy('project_name')  
                                ->offset($pagination->offset)  
                                ->limit($pagination->limit)->asArray()                                                     
                                ->all();
        return  $project_search_results;
    }

    /*function:
    *   获取第一次登录显示记录范围数
    */
    public function get_project_pages()
    {
        $login_username_now = Yii::$app->session['user_name'];
        $login_user_power = Yii::$app->session['power'];

        if($login_user_power == 1)
        {
            
            $page_num = Project_management::find()->count();
        }else if ($login_user_power == 5) {

            $page_num = Project_management::find()->where(['project_manager' => $login_username_now])->count();
        }else if ($login_user_power == 15) {

            $page_num = Project_management::find()->where(['user_name' => $login_username_now])->count();
        }

        return $page_num;
    }

    /*function:
    *   获取分页对象
    */
    public function get_project_pagination($page_num)
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
    public function get_project_showinfo($pagination)
    {
        $login_username_now = Yii::$app->session['user_name'];
        $login_user_power = Yii::$app->session['power'];

        if($login_user_power == 1)//管理员第一次登录时
        {
            $project_showinfo = Project_management::find()->orderBy('project_name')  
                                ->offset($pagination->offset)  
                                ->limit($pagination->limit)->asArray()                                                     
                                ->all();

        }else if ($login_user_power == 5) {//业务员登录时，要查询他下面管理了哪些用户

           $project_showinfo = Project_management::find()->orderBy('project_name')
                                ->where(['project_manager' => $login_username_now]) 
                                ->offset($pagination->offset)  
                                ->limit($pagination->limit)->asArray()                                                     
                                ->all();
                                //var_dump($project_showinfo[0]['id']);
        }else if ($login_user_power == 15) {//普通用户第一次登录时，要查询他下面有哪些项目

           $project_showinfo = Project_management::find()->orderBy('project_name')
                                ->where(['user_name' => $login_username_now]) 
                                ->offset($pagination->offset)  
                                ->limit($pagination->limit)->asArray()                                                     
                                ->all();
                                //var_dump($project_showinfo[0]['id']);
        }

        return  $project_showinfo;
    }

    /*function:
    *   将用户的机种信息插入用户项目信息中
    */
    public function push_devinfo_in_projectinfo($page_num,$project_wholeinfo)
    {
        for($i=0;$i<$page_num;$i++)
        {
            $user_devinfo =  self::get_user_devinfo($project_wholeinfo[$i]['id']);
            array_push($project_wholeinfo[$i],$user_devinfo);
        }

        return $project_wholeinfo;
    }

    /*function:
    *   根据项目id去找对应客户拥有的机种
    */
    public function get_user_devinfo($project_id)
    {
        $dev_info_one = Dev_user::find()->where(['project_id' => $project_id])->asArray()->all();
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
    *   获取用户名用于搜索筛选
    */
    public function get_username_for_search()
    {      
        $login_username_now = Yii::$app->session['user_name'];
        $login_user_power = Yii::$app->session['power'];
        if($login_user_power == 5)
        {
            $_username_for_search = Project_management::find()->where(['project_manager'=>$login_username_now])->asArray()->all();
            //$_username_for_search = User_management::find()->where(['power' => 15])->asArray()->all();
        }   
        else{
            $_username_for_search = User_management::find()->where(['power' => 15])->asArray()->all();
            //$_username_for_search = User_management::find()->where(['<>','power' , 1])->asArray()->all();
        }

        return $_username_for_search;
    }

    /*function:
    *   获取用户名用于分配项目管理者
    */
   public function get_username_for_manager()
   {
        $login_user_power = Yii::$app->session['power'];
        if($login_user_power == 1 || $login_user_power == 15)
        {
            $_username_for_manager = User_management::find()->where(['power' => 5])->asArray()->all();
        }else if($login_user_power == 5){

            $_username_for_manager = User_management::find()->where(['user_name' => Yii::$app->session['user_name']])->asArray()->all();
        }  

        return $_username_for_manager;
   }

    /*function:
    *   获取用户名用于分配项目所属者
    */
    public function get_username_for_owner()
    {      
       
        $_username_for_owner = User_management::find()->where(['power' => 15])->asArray()->all();
        return $_username_for_owner;
    }



}

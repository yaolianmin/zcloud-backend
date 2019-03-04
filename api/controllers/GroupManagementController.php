<?php

/**
* 模块：组模块
* 作用： 添加、修改、删除组信息
* 时间：2018-09-25
* 说明：由于采用了restful api方式请求，所创建的控制器UserManagement 
*      需在config/main.php里面的controller配置 'group-management',
*      其请求方式get post等会自动的选择该控制器的方法，若有特殊命名需求，
*      可在config/main.php的extraPatterns里面配置
*@author : yaolianmin
*@version :
*/
namespace api\controllers;

use Yii;
use yii\rest\ActiveController;
use api\models\User;
use api\models\GroupList;



Class GroupManagementController extends ActiveController{


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
    * 方法：组管理模块方法
    * 作用：根据权限获得用户的相关信息(搜索用户，查看自己的用户)
    * 说明：get请求的方法，默认请求index方法,
    *      这里的index方法只做查询数据的操作
    */
    public function actionIndex(){ 
        $get = Yii::$app->request->get();
        //检测是否为本网站的访问请求 
        if( !isset($get['flag']) ){
            return [
                'state'=>1,
                'data'=>'请您用正确的方式访问'
            ];
        }
        // 1.0 刚进入页面获得的组管理员
        if($get['flag'] == 'get_user_group_management'){
            $user_power = User::get_user_power_by_username($get['user']); 
            switch ($user_power) {
                case 15:
                    $super = GroupList::super_get_group_managements($get['user']);
                    return $super;
                case 2:
                    $self = GroupList::self_get_group_managements($get['user']);
                    return $self;
                case 1:
                    $sanji = GroupList::sanji_get_group_managements($get['user']);
                    return $sanji;
                default:
                    break;
            }
        // 2.0  Main页面传输过来获得组管理员信息
        }elseif($get['flag'] == 'get_user_group_name'){
            $group = User::get_user_group_name($get['user']);
            return  [
                'state'=>0,
                'data'=>$group
            ];
        // 3.0 获得每个组管理员的组查看者和组名
        }elseif($get['flag'] == 'get_group_lookers'){
            $return  = GroupList::get_group_name($get['user']);
            return $return;
        // 4.0 获得组查看者的所有组名
        }elseif($get['flag'] == 'get_group_look_group_name'){
            $rel = GroupList::get_group_name_by_looker($get['groupname']);
            return $rel;
        // 5.0 获得每个组名的详细信息 
        }elseif($get['flag'] == 'get_group_name_infor'){ 
            $infor = GroupList::find()->select('id,group_info')->where(['group_list'=>$get['group_name'],'user_name'=>$get['group_look']])->asArray()->one();
            return [
                    'name'=>$get['group_name'],
                    'remark'=>$infor['group_info'],
                    'id' =>$infor['id']
                ];
        }else{
            return  [
                'state'=>1,
                'data'=>'请您访问的操作不存在'
            ];
        }
        
    }


    /**
     * 方法：create方法
     * 作用：添加、修改、删除用户的信息
     * 说明：前端group-management页面post请求
     *      这里响应的是添加、修改、删除的操作
     */
    public function actionCreate(){
        $post = Yii::$app->request->post(); 
        // 0.0 检测是否为本网站的访问
        if(!isset($post['flag']) ){
            return [
                'state'=>1,
                'data'=>'请您用正确的方式访问'
            ];
        }
        // 1.0 添加组名信息
        if($post['flag'] == 'store_group_name'){
            // 1.1 过滤数据的合理性
            if(count($post['group_name'])>25){
                return '添加失败，组名过长';
            } 
            $rel = GroupList::find()->where(['group_list'=>$post['group_name'],'user_name'=>$post['group_look']])->asArray()->one();
            if($rel){
                return '改用户已有此组名';
            }
            // 1.2 存入信息之数据库
            $rel = GroupList::add_group_name($post);
            return $rel;
        // 2.0 修改网站的组至user表中
        }elseif($post['flag'] == 'submit_group_name'){
            if($post['group_n']){
                $rel = GroupList::update_group_to_user($post);
                return $rel;
            }

        // 3.0 修改组名的备注或者组名操作
        }elseif($post['flag'] == 'updata_group_name_infor'){
            // 3.1 过滤不合理的数据
            if(!$post['group_id']){
                return '您修改的组名没有id';
            }
            $rel = GroupList::find()->select('id')->where(['group_list'=>$post['groupnames'],'user_name'=>$post['look']])->asArray()->one();
            if($rel && ($rel['id'] != $post['group_id'])){
                return '该组查看者已有此组名，请重新更名';
            }
            // 3.2 修改数据至数据库中
            $rels = GroupList::update_group_infor($post);
            return $rels;
        // 4.0 删除组名的操作
        }elseif($post['flag'] == 'delete_group'){
            try{
            // 4.1 过滤不合理的数据
            if(!$post['group_id']){
                return '此条组名没有id，无法删除';
            }
            $power = User::get_user_power_by_username($post['user']);
            if($power < 2){
                return '您没有删除的权限';
            }
            // 4.2 删除的是否是已选中的组名
            $isset = GroupList::check_group_is_selected($post);
            // 4.3 删除数据
            $rel = GroupList::deleteAll(['id'=>$post['group_id']]);
            if($isset == 'yes'){ // 返回删除的组名是否
                return ['state'=>1,'message'=>'success'];
            }else{
                return ['state'=>0,'message'=>'success'];
            }
            }catch(\Exception $e){
                return $e->getMessage();  
            }
        }else{
            return [
                'state'=>1,
                'data'=>'您使用的操作不存在'
            ];
        }
    }

}
<?php

/**
* 模块：用户模块
* 作用：增加 删除 修改用户信息
* 时间：2018-08-07
* 说明：由于采用了restful api方式请求，所创建的控制器UserManagement 
*       需在config/main.php里面的controller配置 'user-management',
*       其请求方式get post等会自动的选择该控制器的方法，若有特殊命名需求，
*       可在config/main.php的extraPatterns里面配置
*@author :
*@version :
*/
namespace api\controllers;

use Yii;
use yii\rest\ActiveController;
use api\models\User;



Class UserManagementController extends ActiveController{


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
    * 方法：用户管理模块方法
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
        // 1.0 显示用户信息
        if($get['flag'] == 'get_all_user_information'){ 
            // 1.1 判断操作者的身份级别并获得相应的用户信息
            $user_power = User::get_user_power_by_username($get['username']);
            switch ($user_power) {
                case 15:
                    $super = User::super_get_all_user($get['_page'],$get['page_size']);
                    return [
                        'state'=>0,
                        'data'=>$super
                    ];
                case 2:
                    $NA = User::NA_get_all_user($get['username'],$get['_page'],$get['page_size']);
                    return [
                        'state'=>0,
                        'data'=>$NA
                    ];
                case 1:
                    $self = User::get_self_infor($get['username']);
                    return [
                        'state'=>0,
                        'data'=>$self
                    ];
                default:
                    return [
                        'state'=>1,
                        'data'=>'该用户的权限值不存在'
                    ];                       
            } 
        // 2.0 搜索操作
        }elseif($get['flag'] == 'get_one_user_information'){
            // 2.1 判断操作者的身份级别并获得相应的用户信息
            $user_power = User::get_user_power_by_username($get['username']);
            switch ($user_power) {
                case 15:
                    $super = User::super_search_user_by_username($get['search'],$get['_page'],$get['page_size']);
                    return [
                        'state'=>0,
                        'data'=>$super
                    ];
                case 2:
                    $NA = User::NA_search_user($get['username'],$get['search'],$get['_page'],$get['page_size']);
                    return [
                        'state'=>0,
                        'data'=>$NA
                    ];
                case 1:
                    $self = User::get_self_infor($get['username']);
                    return [
                        'state'=>0,
                        'data'=>$self
                    ];
                default:
                    return [
                        'state'=>1,
                        'data'=>'该用户的权限值不存在'
                    ];
            }
        // 3.0 获得所有的N/A用户
        }elseif($get['flag'] == 'get_all_nas'){
            $NAs = User::get_all_nas($get['username']);
            return $NAs;
        // 4.0 获得每个用户的详细信息
        }elseif($get['flag'] == 'get_user_infor_by_id'){
            // 4.1 根据用户的id获得该用户的详细信息
            $user = User::get_one_user_inforamtion($get['uid']);
            if($user['state']){
                return [
                    'state'=>1,
                    'data' =>'数据库中没有该用户的信息'
                ];  
            }
            return [
                'state'=>0,
                'data' =>$user['data']
            ];
        }else{
            return [
                'state'=>1,
                'data' =>'请您带参数获取相应的信息'
            ];  
        }
    }



    /**
     * 方法：create方法
     * 作用：添加、修改、删除用户的信息
     * 说明：前端user-management页面post请求
     *      这里响应的是添加、修改、删除的操作
     */
    public function actionCreate(){
        $get = Yii::$app->request->post(); 
        // 0.0 检测是否为本网站的访问
        if(!isset($get['flag']) ){
            return [
                'state'=>1,
                'data'=>'请您用正确的方式访问'
            ];
        }
        // 1.0 添加 用户信息操作
        if($get['flag'] == 'add_new_user_infor'){  
            //$get['data'] = json_decode($get['data'],true);
            // 1.1 验证提交过来的数据是否合理
            $rel = User::check_user_information($get['data']);
            if( $rel != 'success'){
                return [
                    'state'=>1,
                    'data'=>$rel
                ];
            }
            // 1.2 检测操作者的级别
            $user_power = User::get_user_power_by_username($get['username']);
            if($user_power > 1){
                // 1.2.1 防止2级用户添加2级用户
                if($user_power == 2 && $get['data']['userlevel'] == 1){
                    return [
                        'state'=>1,
                        'data'=>'您无权限添加同级别的用户'
                    ];
                }
                // 1.3 将数据存入数据库中
                $rel = User::add_new_user($get['data'],$get['username']);
                if($rel == 'success'){
                    return [
                        'state'=>0,
                        'data'=>'添加成功'
                    ];
                }else{
                    return [
                        'state'=>1,
                        'data'=>$rel
                    ];
                }
            }else{
                return [
                    'state'=>1,
                    'data'=>'您无权限添加用户'
                ];
            }
        // 2.0 修改用户的信息
        }elseif($get['flag'] == 'update_new_user_infor'){
            try{
                // 2.1 校验表单提交的数据
                $rel = User::check_update_form_information($get['data'],$get['username']);
                if($rel != 'success'){
                    return [
                        'state'=>1,
                        'data'=>$rel
                    ];
                }
                // 2.2 数据修改至数据库中
                $rel = User::update_user_information($get['data'],$get['username']);
                if($rel == 'success'){
                    return [
                        'state'=>0,
                        'data'=>'修改信息成功'
                    ]; 
                }else{
                    return [
                        'state'=>1,
                        'data'=>$rel
                    ]; 
                }
            }catch( \Exception $e){
                return [
                    'state'=>1,
                    'data'=>$e
                ]; 
            }
        // 3.0 删除用户的操作
        }elseif($get['flag'] == 'delete_one_user'){
            // 3.1 判断操作者的级别是否高于删除者
            $rel = User::judge_power($get['username'],$get['uid']);
            if($rel != 'success'){
                return [
                    'state'=>1,
                    'data'=>'您无权删除该用户'
                ];
            }
            // 3.2 删除用户
            $rel = User::delete_user_by_id($get['uid'],$get['username']);
            return [
                'state'=>0,
                'index'=>$get['index'],
                'data'=>'已删除该用户'
            ];
        }
    } 
}
<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use frontend\models\UserManagementForm;//用于执行增删改的模型
use frontend\models\UserManagementSearchForm;//用于查询的模型
use frontend\models\User_management;
use frontend\models\Decvice;
use frontend\models\Country;//该表中存储的是国家信息
use yii\data\Pagination;//分页
use yii\data\ActiveDataProvider;//活动记录
use frontend\models\Common;

use frontend\models\Dev_user;

class UserManagementController extends Controller
{

    public function actionUserManagement()
    {
	    //判断是否存在session用户,没有则返回登陆页面
        if(!isset(Yii::$app->session['user_name'])){
           return $this->redirect(['index/index']); 
        }

	    $model = new UserManagementForm();//存储提交数据的表单 
	    $search = new UserManagementSearchForm();//存储查询的表单
	    $country = Country::find()->all();
	    $devices = Decvice::find()->all();
		
    	/** 
    	 * 中英文切换*
    	 */
    	if(Yii::$app->request->isGet)
        {
	        $language = Yii::$app->request->get('lang'); 
            if($language)
            {
                $result = Common::change_language($language);
                if($result == 'success')
                {
                	return $this->redirect(['user-management/user-management']);
                }
            }
        }

		/**
		 * 执行 添加、修改、和删除的动作
		 */
		
		if ($model->load(Yii::$app->request->post())&& $model->validate()) 
		{	
			$model->execAction($model);
		}

		
		if ($search->load(Yii::$app->request->get())&& $search->validate()) 
		{
			$old_power_info = $search->power_info;
			$old_device_info = $search->device_info;
			$old_project_info = $search->project_info;

			$_search_condition = UserManagementForm::get_user_search_sql_condition($search);
			$page_num = UserManagementForm::get_user_search_pages($_search_condition);
			$pagination = UserManagementForm::get_usermanagement_pagination($page_num);
			$no_devinfo_user_searchinfo = UserManagementForm::get_search_results($_search_condition,$pagination);
			$_user_search_info = UserManagementForm::push_devinfo_in_userinfo($page_num,$no_devinfo_user_searchinfo);
			$project_for_search = UserManagementForm::get_projectname_for_search();

			$search->power_info = $old_power_info;
			$search->device_info = $old_device_info;
			$search->project_info = $old_project_info;
			return $this->render('user-management', [
							                'model' => $model,
							                'search' => $search,
							                'device' => $devices,//用于机种搜索和分配
							                //'dev_for_search' => $dev_for_search,
							                //'dev_for_give' => $dev_for_give,
							                'country' =>  $country,//用于设置用户的国家/地区
							                'project_for_search' => $project_for_search,
											'username_final_searchinfo' => $_user_search_info,
											'page_num' => $page_num,
											'pagination' => $pagination,
           								 ]);

		} else{
				//echo $login_user_now."【第一次进入该界面】";
				//默认第一次进来的时候展示相应的数据
				
	    		 $page_num = UserManagementForm::get_login_pages();
	    		 $pagination = UserManagementForm::get_usermanagement_pagination($page_num);
	    		 $no_devinfo_user_showinfo = UserManagementForm::get_user_showinfo($pagination);
	    		 $login_user_showinfo =  UserManagementForm::push_devinfo_in_userinfo($page_num,$no_devinfo_user_showinfo);
	    		 $project_for_search = UserManagementForm::get_projectname_for_search();
	    		 
	    		 return $this->render('user-management', [
								                'model' => $model,
								                'search' => $search,
								                'device' => $devices,//用于机种搜索和分配
								                //'dev_for_search' => $dev_for_search,
							                	//'dev_for_give' => $dev_for_give,
								                'country' =>  $country,//用于设置用户的国家/地区
								                'project_for_search' => $project_for_search,
												'username_final_searchinfo' => $login_user_showinfo,
												'page_num' => $page_num,
												'pagination' => $pagination,
	           								 ]);
		    }

	}











}

<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use frontend\models\ProjectManagementForm;//相应的模型,用于存储表单提交的数据
use frontend\models\ProjectSearchForm;
use frontend\models\Project_management;//对应数据库的表
use frontend\models\UserManagementForm;
use frontend\models\User_management;
use yii\data\Pagination;//分页
use yii\data\ActiveDataProvider;//活动记录
use frontend\models\Common;
use frontend\models\Decvice;
use frontend\models\Dev_user;

class ProjectManagementController extends Controller
{

    public function actionProjectManagement()
    {	
    		//判断是否存在session用户,没有则返回登陆页面
	        if(!isset(Yii::$app->session['user_name'])){
	           return $this->redirect(['index/index']); 
	        }
			    
		    $model = new ProjectManagementForm(); 
		    $search = new ProjectSearchForm();
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
	                	return $this->redirect(['project-management/project-management']);
	                }
	            }
	        }
			/*通过ajax获取用户的机种信息
			*
			*/
			if(Yii::$app->request->isGet)
			{
				$_user_name= Yii::$app->request->get('data');
				if($_user_name){
					$now_user_devinfo = UserManagementForm::get_devinfo_for_user($_user_name);
					\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
					return ["flag_delete" => $now_user_devinfo];
				}
				
			}

			/**
			 * 执行 添加、修改、和删除的动作
			 */
			if ($model->load(Yii::$app->request->post()) && $model->validate()) 
			{
				$model->execAction($model);
			}

			if ($search->load(Yii::$app->request->get())&& $search->validate()) 
			{
					$old_devname_condition = $search->devname_condition;
					$old_power_condition = $search->power_condition;
					
					if(!$search->devname_condition)
					{
						$search->devname_condition = "0";
					}
					if($search->power_condition)
					{
						$search->power_condition = ProjectManagementForm::powerinfo2String($search->power_condition);
					}else
					{
						$search->power_condition = "0";
					}
					
					$_search_condition = ProjectManagementForm::get_project_search_sql_condition($search);
					$page_num = ProjectManagementForm::get_project_search_pages($_search_condition);
					$pagination = ProjectManagementForm::get_project_pagination($page_num);
					$nodevinfo_search_results = ProjectManagementForm::get_search_results($_search_condition,$pagination);
					$final_search_info = ProjectManagementForm::push_devinfo_in_projectinfo($page_num,$nodevinfo_search_results);
					$final_search_info = UserManagementForm::push_devinfo_in_userinfo($page_num,$final_search_info);
					$users_for_search = ProjectManagementForm::get_username_for_search();
					$users_for_manager = ProjectManagementForm::get_username_for_manager();
					$users_for_owner = ProjectManagementForm::get_username_for_search();
					
					$search->devname_condition = $old_devname_condition;
					$search->power_condition = $old_power_condition;
					
					return $this->render('project-management', [
									                'model' => $model,
									                'search' => $search,
									                'devices_all' => $devices,
									                'users_for_search' => $users_for_search,
									                'users_for_manager' => $users_for_manager,
									                'users_for_owner' => $users_for_owner,
									                'projectinfo_search' =>$final_search_info,
									                'page_num' => $page_num,
									                'pagination' => $pagination,
		           								 ]);		
			} else{
					//echo "用户".$login_user_now."登录项目管理";
					$page_num = ProjectManagementForm::get_project_pages();
					$pagination = ProjectManagementForm::get_project_pagination($page_num);
					$nodev_projectinfo = ProjectManagementForm::get_project_showinfo($pagination);
					$project_showinfo = ProjectManagementForm::push_devinfo_in_projectinfo($page_num,$nodev_projectinfo);
					$project_showinfo = UserManagementForm::push_devinfo_in_userinfo($page_num,$project_showinfo);
				
					$users_for_search = ProjectManagementForm::get_username_for_search();
					$users_for_manager = ProjectManagementForm::get_username_for_manager();
					$users_for_owner = ProjectManagementForm:: get_username_for_owner();

		    		return $this->render('project-management', [
									                'model' => $model,
									                'search' => $search,
									                'devices_all' => $devices,
									                'users_for_search' => $users_for_search,
									                'users_for_manager' => $users_for_manager,
									                'users_for_owner' => $users_for_owner,
									                'projectinfo_search' =>$project_showinfo,
									                'page_num' => $page_num,
									                'pagination' => $pagination,
		           								 ]);
			    }
	
	}









}

<?php
namespace backend\models\task_management;

use Yii;
use yii\base\Model;
use yii\db\Command;
use yii\web\NotFoundHttpException;
use yii\web\HttpException;

use backend\models\DevInfo;
use backend\models\GroupList;
use backend\models\common\Common_model;
use backend\models\Task_schedule;

/**
 * 任务管理模型
 */
class TaskManagement extends Model
{
	/**
	 * create task
	 * @param  [type] $post_info [description]
	 * @return [type]            [description]
	 */
	public function createTask($post_info)
	{
		if(isset($post_info["select_table_rowdata"]))
		{
            self::setTableActions($post_info);
            self::setTableTaskSchedule($post_info);
        }
	}

	/**
	 * 在task_schedule表中加入一次任务的完整信息
	 * @param 
	 */
	protected function setTableTaskSchedule($post_info)
	{
		$selectTableRowdata = $post_info["select_table_rowdata"];
		foreach($selectTableRowdata as $value) 
        {
        	$model = new Task_schedule();
        	$model->attributes = $post_info;
        	if($post_info["execute_action"] == "now")
	        {
	            $model->execute_time = date('Y-m-d H:i:s',time());
	        }
	        $model->group_name = $value["group_name"];
	        $model->reviewer = $value["user_name"];
	        $model->username = $value["management"];
	        $model->device_mac = $value["mac_addr"];
	        $model->date_created = date('Y-m-d H:i:s',time());
	        //固件更新任务
	        if(isset($post_info["fw_path"])){
	        	$model->ftp_server_params = $post_info["fw_path"];
	        }else{
	        	$model->ftp_server_params = '';
	        }
	        //模板下发任务
	        if(isset($post_info["templet_name"])){
	        	$model->action_filename = $post_info["templet_name"];
	        }else{
	        	$model->action_filename = null;
	        }	  
	        //从actions表中获取action id
	        $_model = new Common_model();
	        $_model->find_model('actions');
	        $_data = $_model::find()->where(['action_start_time'=>date('Y-m-d H:i:s',time())])->andWhere(['device_mac'=>$value["mac_addr"]])->all(); 
	        if($_data){
	        	$model->action_id = intval($_data[0]["action_id"]);
	        }  
	            
        	$model->save();
        }
	}

	/**
	 * 在actions表中加入对应的任务信息
	 * @param 
	 */
	protected function setTableActions($post_info)
	{
		if(isset($post_info["task_type"]))
		{
			$act_Type_Id = 0;
			$task_type = $post_info["task_type"];
			switch ($task_type) 
			{
				case 'templet':
					$act_Type_Id = 2;
					break;
				case 'firmware':
					$act_Type_Id = 3;
					break;
				case 'reboot':
					$act_Type_Id = 4;
					break;
				case 'diagnostic':
					$act_Type_Id = 8;
					break;
				// case 'reboot':
				// 	$act_Type_Id = ;
				// 	break;
				// case 'reboot':
				// 	$act_Type_Id = ;
				// 	break;
				default:
					$act_Type_Id = 0;
					break;
			}

			foreach ( $post_info["select_table_rowdata"] as  $value) 
            {
            	$model = new Common_model();
    			$model->find_model('actions');

            	$model->device_mac = $value["mac_addr"];
            	$model->act_type_id = $act_Type_Id;
            	$model->action_start_time = date('Y-m-d H:i:s',time());
            	//固件更新任务
            	if(isset($post_info["fw_path"])){
	        		$model->ftp_server_params = $post_info["fw_path"];
		        }else{
		        	$model->ftp_server_params = '';
		        }
		        //模板下发任务
		        if(isset($post_info["templet_name"])){
		        	$model->act_params = $post_info["templet_name"];
		        }else{
		        	$model->act_params = '';
		        }	 
            	$model->action_state = 0;
            	$model->save();
            }
		}
	}




}
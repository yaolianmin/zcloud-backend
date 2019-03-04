<?php
namespace backend\models\actfail;

use Yii;
use yii\db\ActiveRecord;
use backend\models\Actions;
use backend\models\Task_schedule;
use backend\models\User;
use backend\models\php\syslogs\Syslog_update;

class Actfail extends ActiveRecord
{
	
	function process_action_fail($action_id, $action_err)
	{
		Actions::update_action_state_for_actfail($action_err,$action_id);

		switch($action_err) {
			case 1:
			case 3:
				$str_err = "failed";
			break;
			case 4:
			case 5:
				$str_err = "running";
			break;
			default:
				$str_err = "failed";
			break;
		}
		
		if (!strcmp($str_err,"failed")) {
			self::task_failed_send_email($action_id,$str_err);
		}
		
		Task_schedule::update_status_for_actfail($str_err,$action_id);
	}
	
	function task_failed_send_email($action_id,$failed_type)
	{
		$current_time = time();
		$last_email_send_time = self::get_last_email_send_time($action_id);
		//var_dump($last_email_send_time);
		//exit;
		if($current_time - $last_email_send_time >= 60*60)
		{
			$row = Task_schedule::select_devinfo_for_ackaction($action_id);
			
			if (!empty($row))
			{
				$file_path = "/var/www/ZOAM/yii-test/backend/models/actfail/".$action_id."_task_execute_failed.csv";
				$fp = fopen($file_path, "w");
				if ($file_path == null)
					return;
				$content_row = sprintf("%s,%s,%s,%s,%s,%s,%s,%s\n", $row['device_mac'],$row['task_type'],$row['description'],$row['execute_time'],mb_convert_encoding($row['group_name'],'GBK','UTF-8'),mb_convert_encoding($row['reviewer'],'GBK','UTF-8'),mb_convert_encoding($row['username'],'GBK','UTF-8'),$failed_type);
				fwrite($fp, $content_row);
				fclose($fp);
				$table_header = mb_convert_encoding("Device Mac Address,Task Type,Description,Execute Time,Group Name,Group Reviewer,Administor,Execute Result",'GBK','UTF-8');
				$file_header = $table_header.PHP_EOL;
				file_put_contents($file_path,$file_header.file_get_contents($file_path));
				
				$email_addr = User::get_email_addr($row['username']);
			
//ÔÝÊ±²»×ö		//php_send_mail($email_addr, 'You have a task execute failed, Please check!', " ", $file_path);//
				Task_schedule::update_email_send_time($current_time,$action_id);
				self::task_failed_write_syslog($action_id,$failed_type);
				unlink($file_path);
			}
			
		}
	}
	
	function get_last_email_send_time($action_id)
	{
		$row = Task_schedule::select_email_send_time($action_id);
		
		if (!empty($row))
		{
			return $row['email_send_time'];
		}
		return 0;
	}
	
	function task_failed_write_syslog($action_id,$failed_type)
	{
		//Task_schedule", "group_name,reviewer,username,device_mac,task_type,description,execute_time,status", "action_id='$action_id'";
		$row = Task_schedule::select_devinfo_for_ackaction($action_id);
		//var_dump('123');
		//exit;
		if (!empty($row))
		{	
			Syslog_update::write_sys_log_error_task("Task failed"," [".$row['device_mac']."] \"".$row['task_type']." ".$row['description']."\" $failed_type",$row['username']);

		}
	}
	
	
	
}


?>
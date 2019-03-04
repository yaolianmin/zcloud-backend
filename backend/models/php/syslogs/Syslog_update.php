<?php
namespace backend\models\php\syslogs;

use Yii;
use yii\db\ActiveRecord;

class Syslog_update extends ActiveRecord
{
	public static function tableName()
    {
        return 'SysLog';//创建一个数据模型链接数据表
    }
	
	function syslog_update($user, $log_type, $log_id_str, $souce, $time, $level, $desc, $change_password_flag=false)
	{	
		$syslog_ids =array( 
						//user management
						'user add' => 0, 
						'delete user' => 1,
						'user edit' => 2,
						
						//login or logout
						'user login' => 10,
						'user logout' => 11,
						'user not exist' => 12,
						'password error' => 13,
						'dynamic code not match' => 14,
		
						//FAP Templet
						'templet FAP add' => 20,
						'templet FAP delete' => 21,
						'templet FAP edit' => 22,
						'templet FAP-VAP edit' => 23,
			
						//AC Templet
						'templet AC add' => 30,
						'templet AC delete' => 31,
						'templet AC edit' => 32,
						'templet AC-TAP edit' => 33,
						//modified by ljh 2014-7-17
						'templet AC-TAP-VAP edit' => 34,
			
						//firmware & config templet & other file operation
						'user download system log file' => 40,
						'download device log' => 41,
						'apply fw to device' => 42,
						'user download ACL file' => 43,
						'web UI download cfg templet file to device' => 44,
						'device download fw file' => 45,
						'device download templet file' => 46, 
						"user download user info file" => 47,
						"user download app report file" => 48,
						"Device list download" => 49,
						
						//alerting
						//web alert and email alert					
						'browse and handle offline alert' => 50,	//user browse alert log
						'ZOAM Cloud System Send E-Mail' => 51,		//E-mail
						
						//boss device operation
						'boss device add' => 60, 
						'boss device edit' => 61,
						'boss device delete' => 62,
						
						//VAC Templet
						'templet VAC add' => 70,
						'templet VAC delete' => 71,
						'templet VAC edit' => 72,
						'templet VAC-VAP edit' => 73,
						
						//Task Mangement for v3.x
						"New task" => 100,
						"Edit task" => 101,
						"Task success" => 102,
						"Task failed" => 103,
		
						//user management for v 3.0.0+
						"admin send mail to reviewer for changing password" => 200,
						"send mail to self for changing password" => 201,
						"user change password" => 202,
						
						'reserved' => 255,
						//device management for v 3.0.0
						"Device assigned group" => 301,
						"Device unassigned group" => 302,
						"Device information edit" => 303	
		); 
							
		$log_types =array( 
						'device log' => 0, 
						'system log' => 1,
		
						'reserved' => 255
		); 
		
		$sys_log_level =array( 
						'information' => 0, 
						'warning' => 1,
						'error' => 2,				
		
						'reserved' => 255
		); 
				
		$log_id = $syslog_ids[$log_id_str];
		//var_dump($log_id);
		//exit;
		
		$model = new Syslog_update();
		$model->username = $user;
		$model->log_type = $log_type;
		$model->log_id = $log_id;
		$model->log_source = $souce;
		$model->log_time = $time;
		$model->log_level = $level;
		$model->log_desc = $desc;
		$model->save();
		
	}



	function write_sys_log_task($syslog_desc,$act_params,$username)
	{
		$syslog_file_name=$act_params;
		$log_id_str = $syslog_desc;
		$souce = $_SERVER["REMOTE_ADDR"];
		
		$time = date('Y-m-d H:i:s');
		$level = 'information';
		$desc = $syslog_desc.":".$syslog_file_name;
//var_dump($desc);
//exit;
		self::syslog_update($username, 1, $log_id_str, $souce, $time, $level, $desc);
	}

	function write_sys_log_error_task($syslog_desc,$act_params,$username)
	{
		$syslog_file_name=$act_params;
		$log_id_str = $syslog_desc;
		$souce = $_SERVER["REMOTE_ADDR"];
		$time = date('Y-m-d H:i:s');
		$level = 'error';
		$desc = $syslog_desc.":".$syslog_file_name;
//var_dump($level);
//exit;	
		self::syslog_update($username, 1, $log_id_str, $souce, $time, $level, $desc);
	}
	
	
}


?>




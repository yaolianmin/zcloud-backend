<?php
namespace backend\models\ackaction;

use Yii;
use yii\db\ActiveRecord;
use backend\models\Actions;
use backend\models\Task_schedule;
use backend\models\Device;
use backend\models\php\syslogs\Syslog_update;

define("A_STATE_UNKNOWN",    0);
define("A_STATE_PRE_ACKED",  1);
define("A_STATE_FULL_ACKED", 2);

class Ackaction extends ActiveRecord
{
	
	function process_action_ack($type, $action_id)
	{	
		
		$result = Actions::select_params_state_id($action_id);
		//var_dump($result);
		//exit;
		
		$dev_type = 0; // AP default
		
		if ($result === false) // this is a misack and just ignore it.
		{
			return;
		}

		// Check whether this action is already fully acked.
		if ($result["action_state"] == A_STATE_FULL_ACKED)
		{
			return;
		}  
		
		if (strcmp($type, "AC") == 0) {
			$dev_type = 1;
		}else if (strcmp($type, "VAC") == 0) {
			$dev_type = 2;
		}else if (strcmp($type, "CPE") == 0) {
			$dev_type = 3;
		}

		if ($result["act_type_id"] == 2 ) { 	/* templet config */
			if ($dev_type == 0)
				$file_path="/var/www/uploadfile/Template_FAP/";
			else if ($dev_type == 1)	
				$file_path="/var/www/uploadfile/Template_AC/";
			else if ($dev_type == 2)	
				$file_path="/var/www/uploadfile/Template_VAC/";
			else if ($dev_type == 3)	
				$file_path="/var/www/uploadfile/Template_CPE/";
		} else if ($result["act_type_id"] == 5 ) { /* gdmtest config */
			$file_path="/var/www/uploadfile/Config_gdmtest/";
		} else if ($result["act_type_id"] == 6 ) { /* bwcontrol config */
			if ($dev_type == 0)
				$file_path="/var/www/uploadfile/Config_bwcontrol_FAP/";
			else if ($dev_type == 1)
				$file_path="/var/www/uploadfile/Config_bwcontrol_AC/";
			else if ($dev_type == 2) {
				echo "Device not support!!";
				exit();
			}		
		}else
			$file_path="";
		
		// Remove old config files
		
		$file_path .= $result["act_params"];
		//var_dump($file_path);
		//exit;
		//act_type_id = 7 => lteficfg
		if (file_exists($file_path) && $result["act_type_id"] == 7) {
			unlink($file_path);//删除文件
		}
		
		Actions::update_action_state_for_ackaction($action_id);
		Task_schedule::update_status_for_ackaction($action_id);
	//var_dump("123");
	//exit;
		self::task_success_update_last_templet($action_id, "success");
		self::task_success_write_syslog($action_id, "success");
		self::task_update_current_portal($action_id, "success");
	}
	
	function task_success_update_last_templet($action_id,$desc)
	{
		$row = Task_schedule::select_info_for_ackaction($action_id);
		//var_dump($row);
		//exit;
		if( $row['task_type'] == "templet" && $desc == "success") 
		{
			$retval = json_decode($row['templet_params']);
			$str_templet = $retval->templet_name;
			$tmp1 = $retval->las_channel;
			if( !empty($tmp1) )  {
				if( !empty($tmp1[0]) ) //card1
					$str_channel = "\"0\":".$retval->las_channel[0];
				if( !empty($tmp1[1]) )  //card2
					$str_channel .= ",\"1\":".$retval->las_channel[1];
				$str_channel = "{".$str_channel."}";
			}
			else $str_channel = "";
			
			$tmp2 = $retval->vap_ssid;
			if( !empty($tmp2) )  {
				if( !empty($tmp2[0]) ) //card1
					$str_vap_ssid = "\"0\":[\"".$tmp2[0]->VAP0_SSID."\",\"".$tmp2[0]->VAP1_SSID."\",\"".$tmp2[0]->VAP2_SSID."\",\"".$tmp2[0]->VAP3_SSID."\",\"".$tmp2[0]->VAP4_SSID."\",\"".$tmp2[0]->VAP5_SSID."\",\"".$tmp2[0]->VAP6_SSID."\",\"".$tmp2[0]->VAP7_SSID."\"]";
				if( !empty($tmp2[1]) ) //card2
					$str_vap_ssid .= ",\"1\":[\"".$tmp2[1]->VAP0_SSID."\",\"".$tmp2[1]->VAP1_SSID."\",\"".$tmp2[1]->VAP2_SSID."\",\"".$tmp2[1]->VAP3_SSID."\",\"".$tmp2[1]->VAP4_SSID."\",\"".$tmp2[1]->VAP5_SSID."\",\"".$tmp2[1]->VAP6_SSID."\",\"".$tmp2[1]->VAP7_SSID."\"]";
				$str_vap_ssid = "{".$str_vap_ssid."}";
			}
			else $str_vap_ssid = "";
			
			$tmp3 = $retval->operation_mode;
			if( !empty($tmp3) )  {
				if( !empty($tmp3[0]) )  //card1
					$str_operation_mode = "\"0\":\"".$tmp3[0]->OperationMode."\"";
				if( !empty($tmp3[1]) )  //card2
					$str_operation_mode .= ",\"1\":\"".$tmp3[1]->OperationMode."\"";
				$str_operation_mode = "{".$str_operation_mode."}";
			}
			else $str_operation_mode = "";
			
			$tmp4 = $retval->bridge_mac;
			if( !empty($tmp4) )  {
				if( !empty($tmp4[0]) )  //card1
					$str_bridge = "\"0\":[\"".$tmp4[0]->WDSMacAddr1."\",\"".$tmp4[0]->WDSMacAddr2."\",\"".$tmp4[0]->WDSMacAddr3."\",\"".$tmp4[0]->WDSMacAddr4."\"]";
				if( !empty($tmp4[1]) )  //card2
					$str_bridge .= ",\"1\":[\"".$tmp4[1]->WDSMacAddr1."\",\"".$tmp4[1]->WDSMacAddr2."\",\"".$tmp4[1]->WDSMacAddr3."\",\"".$tmp4[1]->WDSMacAddr4."\"]";
				$str_bridge = "{".$str_bridge."}";
			}
			else $str_bridge = "";
			
			Device::update_vapinfo_for_ackaction($str_templet,$str_channel,$str_vap_ssid,$str_operation_mode,$str_bridge,$row['device_mac']);
		}
	
	}
	
	
	function task_success_write_syslog($action_id,$failed_type)
	{
		
		$row = Task_schedule::select_devinfo_for_ackaction($action_id);
		
		Syslog_update::write_sys_log_task("Task success"," [".$row['device_mac']."] \"".$row['task_type']." ".$row['description']."\" $failed_type",$row['username']);
	}
	
	function task_update_current_portal($action_id,$desc)
	{
		$row = Task_schedule::select_reviewer_for_ackaction($action_id);
//var_dump($row);
//exit;
		if( $row['task_type'] == "portal" && $desc == "success")
		{
			$user = $row['reviewer'];
			$management = $row['username'];
			$ac_mac = $row['device_mac'];
			$portal = str_replace(".zip", "", $row['action_filename']);
			
			Device::update_used_portal($portal,$ac_mac);
		}
		
	}
	
}


?>
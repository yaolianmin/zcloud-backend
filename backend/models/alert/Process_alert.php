<?php
namespace backend\models\alert;

use yii;

class Process_alert{

	function process_alert($_get_param,$report_info){
		$time = date('Y-m-d H:i:s');
		$alert = json_decode($report_info, true);
		
		$mac = $_get_param['mac'];
		$type = $_get_param['type'];
		$action = $_get_param['action'];
		$fwversion = $_get_param['fwversion'];
		
		$dev_mac =$alert["mac"];
		$log_level=$alert["log_level"];
		$log_desc=$alert["log_desc"];
		$log_id=$alert["log_id"];
		
		//查询新插入的信息，如果与当前最新的信息时差小于1s，则跳过；反之---add by shenwj 2014-11-25 15:12
		//580|2.2.1|Normal|功能|已确认(CONFIRMED)|设备日志出现许多CPU使用100%的告警信息，其中很多是重复.
		$query = "SELECT count(*) FROM DevLog WHERE log_id='$log_id' and timestampdiff(second,time,now()) < 1";

		$result = Yii::$app->db->createCommand($query)->queryAll();
	 	
	 	if($result>0){
	 		$row = $result[0];
	 		if ($row['count(*)'] > 0)
				return;
	 	}
	 	   
		$query = "SELECT model_name, user_name, group_name,model_type FROM DevInfo
	              WHERE mac_addr=\"$mac\" ";
	    $result = Yii::$app->db->createCommand($query)->queryAll();  
	       
	    if($result){
	    	$row = $result[0];
	    	$model_name = $row['model_name'];
			$user_name = $row['user_name'];	//guilent 'user_name'为三级用户！
			$group = $row['group_name'];
			$dev_type = $row['model_type'];
	    }
		
		if ($log_id == 100) {
			$date = $log_desc;
			$log_desc = "TAP ".$dev_mac." had been online at ".$date;
		} else if ($log_id == 101) {
			$date = $log_desc;
			$log_desc = "TAP ".$dev_mac." had been offline at ".$date;
		} else if ($log_id == 102) {
			$date = $log_desc;
			$log_desc = "LTE-Fi ".$dev_mac." had been online at ".$date;
			$query = " 
				insert into ltefi_list set 
					ac_mac=\"".$mac."\",
					ltefi_mac=\"".$dev_mac."\",
					online=\"".$date."\"
				ON DUPLICATE KEY UPDATE
					online=\"".$date."\"
			";
			$result = Yii::$app->db->createCommand($query)->execute();
			
		} else if ($log_id == 103) {
			$date = $log_desc;
			$log_desc = "LTE-Fi ".$dev_mac." had been offline at ".$date;
			$query = " 
				update ltefi_list set 
					offline=\"".$date."\"
				where ac_mac = \"".$mac."\" and ltefi_mac=\"".$dev_mac."\"
			";
			$result = Yii::$app->db->createCommand($query)->execute();
		}
		
		$query = "
				INSERT INTO  DevLog (mac_addr,log_level,log_id,log_desc,user_name,dev_group,dev_type,time,model_name) 
				VALUES (\"".$mac."\",".$log_level.",".$log_id.",\"".$log_desc."\",\"".$user_name."\",\"".$group."\",\"".$dev_type."\",\"".$time."\",\"".$model_name."\")";	

		$result = Yii::$app->db->createCommand($query)->execute();
		
		// If sta assoc, add this to station list first
		$sta_mac = substr($log_desc, 8, 17);
		$query = "";
		if ($log_id == 25) {
			$query = " 
				insert into StationList set
				sta_mac=\"".$sta_mac."\",
				offline=0,
				dev_mac=\"".$mac."\",
				date=\"".date('Y-m-d')."\"
				ON DUPLICATE KEY UPDATE
				offline=0
			";
			$result = Yii::$app->db->createCommand($query)->execute();
	
		} else if ($log_id == 26) {
			$query = " 
				update StationList set offline=1 where dev_mac=\"".$mac."\" and sta_mac=\"".$sta_mac."\"
			";
			$result = Yii::$app->db->createCommand($query)->execute();
			
		} 
		
		return;
	    
	}







}
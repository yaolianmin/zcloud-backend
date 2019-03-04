<?php
namespace backend\models;

use Yii;
use yii\db\ActiveRecord;
use backend\models\Device;
use backend\models\Dev_out_of_service;
use backend\models\Actions;
use backend\models\Vpnclient_list;
use backend\models\Act_type;
use backend\models\php\modelname\Modelname;

define("A_STATE_UNKNOWN",    0);
define("A_STATE_PRE_ACKED",  1);
define("A_STATE_FULL_ACKED", 2);



/**
 * 模型：心跳包模型
 * 时间：2018.11.09
 * 作者：
 * 格式：utf-8
 *
 */

class Heartbeat extends ActiveRecord{

    /**
     * 
     *
     *
     *
     */	
	function heartbeat_handle($value){
		try{
			// 修改机种表的local字段
			Device::updateAll(['local'=> '2'],['mac_addr'=>$value['mac']]);
			// 查看 dev_out_of_service 表中是否含有此设备的mac地址
			$count = Dev_out_of_service::check_dev_service_line($value['mac']);
			if($count){
				Device::updateAll(['device_status'=>2],['mac_addr'=>$value['mac']]);
			}else{

				Device::updateAll(['device_status'=>1],['mac_addr'=>$value['mac']]);
			}
			// 检测 DevInfo 表中的 offine_alarm的值是否为 1
			$offline_alarm = Device::select_offline_alarm($value['mac']);
			if($offline_alarm == '1'){
				// 修改 action_state 字段的值
				Actions::update_action_state($value['mac']);
			}
			 // 添加设备的上线时间
			 Device::check_and_set_online_time($value['mac']);
			
			// 更新设备表的最新上线时间
			 Device::check_and_set_recent_online($value['mac'],$value['modelname']);
			
		     //add by lad 2017-12-08 for Devcie first_seen
			 Device::check_and_set_first_seen($value['mac']);
			
			 Device::update_heartbeat($value);
			
			if(!empty($value['vpnEnable'])){
				Vpnclient_list::update_dev_vpn_switch($value['vpnEnable'],$value['mac']);
			}
			
			Device::add_default_templet_to_TempletFAP($value['mac']);
			// 返回信息给设备端
			self::response_action_list($value['mac']);
		}catch( \Exception $e){
		    return $e->getMessage();
		}
	}

	// function heartbeat_handle_1032CPE($value)
	// {
	// 	Device::update_devinfo_local($value['mac']);//ÎªÇø·Ö1023BridgeÄ£Ê½ÏÂµÄ½üÔ¶¶Ë¶ø´æÔÚµÄ±êÖ¾Î»£¬0Ô¶¶Ë£¬1½ü¶Ë£¬2ÆäËüÉè±¸
		
	// 	$count = Dev_out_of_service::check_dev_service_line($value['mac']);
		
	// 	if($count){
	// 		Device::update_device_status_2($value['mac']);
	// 	}else
	// 		Device::update_device_status_1($value['mac']);
		
	// 	if( "XN-1032" == $value['modelname'] && !empty($value['CPE_flag']) )
	// 	{
	// 		Device::update_CPE_flag($value['mac'],$value['CPE_flag']);
	// 	}
	// 	if(!empty($value['actionID']) && !empty($value['CPE_flag']))
	// 	{
	// 		if( "1" == $value['CPE_flag'] && "0" != $value['actionID'])
	// 		{
	// 			Actions::update_action_state_1032($value['actionID']);
	// 			Task_schedule::update_status_1032($value['actionID']);
	// 		}
	// 	}
		
	// 	$offline_alarm = Device::select_offline_alarm($value['mac']);
		
	// 	if($offline_alarm == "1"){
	// 		Actions::update_action_state($value['mac']);
	// 	}
		
	// 	//add by lad 2017-12-08 for Device online_time
	// 	Device::check_and_set_online_time($value['mac']);
		
	// 	//add by lad 2017-12-08 for Device recent_online and clear recent_offline
	// 	Device::check_and_set_recent_online($value['mac']);
		
	// 	//add by lad 2017-12-08 for Devcie first_seen
	// 	Device::check_and_set_first_seen($value['mac']);
		
	// 	Device::update_heartbeat($value);
		
	// 	if(!empty($value['vpnEnable'])){
	// 		Vpnclient_list::update_dev_vpn_switch($value['vpnEnable'],$value['mac']);
	// 	}
		
	// 	Device::add_default_templet_to_TempletFAP($value['mac']);
		
	// 	self::response_action_list_1032CPE($value['mac'],$value['modelname']);
	// }
	
	// function heartbeat_handle_1023CPE($value)
	// {
	// 	$get_model_name = Modelname::get_dev_model_name($value['modelname']);
	// 	Device::update_devinfo_local($value['mac']);//ÎªÇø·Ö1023BridgeÄ£Ê½ÏÂµÄ½üÔ¶¶Ë¶ø´æÔÚµÄ±êÖ¾Î»£¬0Ô¶¶Ë£¬1½ü¶Ë£¬2ÆäËüÉè±¸
	// 	Actions::delete_act_type_id_6($value['mac']);//É¾³ýÁ÷Á¿¿ØÖÆÈÎÎñ£¬ÔÝÊ±²»ÐèÒª
	// 	Actions::delete_act_type_id_7($value['mac']);//É¾³ýGNSSÈÎÎñ£¬Ö»ÓÐLTEFiÉè±¸²ÅÐèÒª
		
	// 	$count = Dev_out_of_service::check_dev_service_line($value['mac']);
	// 	if($count){
	// 		Device::update_device_status_2($value['mac']);
	// 	}else
	// 		Device::update_device_status_1($value['mac']);
		
	// 	/*if( $get_model_name == $value['modelname'] && !empty($value['flag']) )
	// 	{
	// 		Device::update_flag($value['mac'],$value['flag']);
	// 	}*/
		
	// 	$offline_alarm = Device::select_offline_alarm($value['mac']);
		
	// 	if($offline_alarm == "1"){
	// 		Actions::update_action_state($value['mac']);
	// 	}
		
	// 	//add by lad 2017-12-08 for Device online_time
	// 	Device::check_and_set_online_time($value['mac']);
		
	// 	//add by lad 2017-12-08 for Device recent_online and clear recent_offline
	// 	Device::check_and_set_recent_online($value['mac']);
		
	// 	//add by lad 2017-12-08 for Devcie first_seen
	// 	Device::check_and_set_first_seen($value['mac']);
		
	// 	Device::update_heartbeat_1023($value);
		
	// 	if(!empty($value['vpnEnable'])){
	// 		Vpnclient_list::update_dev_vpn_switch($value['vpnEnable'],$value['mac']);
	// 	}
		
	// 	Device::add_default_templet_to_TempletFAP($value['mac']);
		
	// 	self::response_action_list_1023CPE_Bridge($value['mac'],$value['modelname']);
	// }

	// function heartbeat_handle_1023Bridge($value)
	// {
	// 	$get_model_name = Modelname::get_dev_model_name($value['modelname']);
	// 	Actions::delete_act_type_id_6($value['mac']);//É¾³ýÁ÷Á¿¿ØÖÆÈÎÎñ£¬ÔÝÊ±²»ÐèÒª
	// 	Actions::delete_act_type_id_7($value['mac']);//É¾³ýGNSSÈÎÎñ£¬Ö»ÓÐLTEFiÉè±¸²ÅÐèÒª
	// 	$count = Dev_out_of_service::check_dev_service_line($value['mac']);
	// 	if($count){
	// 		Device::update_device_status_2($value['mac']);
	// 	}else
	// 		Device::update_device_status_1($value['mac']);
		
	// 	if( $get_model_name == $value['modelname'] && !empty($value['local']))
	// 	{
	// 	//var_dump($value['local']);
	// 	//exit;
	// 		//Device::update_flag($value['mac'],$value['flag']);
	// 		Device::update_local($value['mac'],$value['local']);
	// 	}
		
	// 	$offline_alarm = Device::select_offline_alarm($value['mac']);
		
	// 	if($offline_alarm == "1"){
	// 		Actions::update_action_state($value['mac']);
	// 	}
		
	// 	//add by lad 2017-12-08 for Device online_time
	// 	Device::check_and_set_online_time($value['mac']);
		
	// 	//add by lad 2017-12-08 for Device recent_online and clear recent_offline
	// 	Device::check_and_set_recent_online($value['mac']);
		
	// 	//add by lad 2017-12-08 for Devcie first_seen
	// 	Device::check_and_set_first_seen($value['mac']);
		
	// 	Device::update_heartbeat_1023($value);
		
	// 	if(!empty($value['vpnEnable'])){
	// 		Vpnclient_list::update_dev_vpn_switch($value['vpnEnable'],$value['mac']);
	// 	}
		
	// 	Device::add_default_templet_to_TempletFAP($value['mac']);
		
	// 	self::response_action_list_1023CPE_Bridge($value['mac'],$value['modelname']);
		
	// }
	
	function response_action_list($mac){
		//$sql = "SELECT * FROM actions where device_mac='00:00:00:0c:f0:60'";
		$sql = "SELECT action_id, act_type, act_params, ftp_server_params FROM
              act_type, actions
              WHERE actions.device_mac='{$mac}' AND
              actions.act_type_id = act_type.act_type_id AND
              actions.action_state<>" . A_STATE_FULL_ACKED;
		$connection = \Yii::$app->db;
		$command = $connection->createCommand($sql);
		$post = $command->queryAll();
		$count = $connection->createCommand($sql)->query()->rowCount;
		$action = array();
		$action["action_num"]=0;
		$num = 0;
		
		for($num = 0;$num<$count;$num++){
		
			$action["action_list"][$num]["act_type"] = $post[$num]['act_type'];   //ÈÎÎñÀàÐÍ
			$action["action_list"][$num]["act_params"] = $post[$num]['act_params'];  //¶ÔÉè±¸¶ËÏÂ·¢ÈÎÎñÊ±£¬Éæ¼°µ½ÎÄ¼þµÄ²¿·Ö£¨FWÉý¼¶³ýÍâ£©£¬ÕâÀï´æ·ÅµÄÊÇÒªÏÂ·¢µÄÎÄ¼þÃû³Æ
			$action["action_list"][$num]["act_id"] = intval($post[$num]['action_id']);	 //ÈÎÎñID
			$action["action_list"][$num]["ftp_server_params"] = $post[$num]['ftp_server_params'];	  //ÏÂ·¢Éý¼¶FWÈÎÎñÊ±£¬ÕâÀï¼ÇÂ¼´æ·ÅFWµÄFTP serverµÄÕËºÅÃÜÂëÐÅÏ¢

		}
		$action["action_num"]=$num;
		$action["auto_key"] = Device::formRandChar($mac);
		$action["flag"] = true;
		$action["servertime"] = date("Y-m-d H:i:s");
		
		echo json_encode($action);
	}
	
	// function response_action_list_1032CPE($mac,$modelname)
	// {
	// 	$sql = "SELECT action_id, act_type, act_params, ftp_server_params, action_state FROM
 //              act_type, actions
 //              WHERE actions.device_mac='{$mac}' AND
 //              actions.act_type_id = act_type.act_type_id AND
 //              actions.action_state<>" . A_STATE_FULL_ACKED;
	// 		 // <>±íÊ¾²»µÈÓÚ£»A_STATE_FULL_ACKEDÔÚphp/device/utilis.phpÖÐ¶¨ÒåÎª2  ¶ÔÓ¦µÄÊÇÊý¾Ý±íactionsÖÐµÄaction_stateµÄÊý¾Ý   2±íÊ¾³É¹¦£¬1±íÊ¾Ê§°Ü£¬0±íÊ¾Î´Ö´ÐÐ£»
	// 	$connection = \Yii::$app->db;
	// 	$command = $connection->createCommand($sql);
	// 	$post = $command->queryAll();
	// 	$count = $connection->createCommand($sql)->query()->rowCount;
		
	// 	$action = array();
	// 	$action["action_num"]=0;
	// 	$num = 0;
		
	// 	for($num = 0;$num<$count;$num++)
	// 	{
	// 		if("XN-1032" == $modelname && "config" == $post[$num]['act_type'] && "9" == $post[$num]['action_state'])
	// 		{	
	// 			continue; //½áÊø±¾´ÎÑ­»·½øÈëÏÂ´ÎÑ­»·
	// 		}
	// 		$action["action_list"][$num]["act_type"] = $post[$num]['act_type'];   //ÈÎÎñÀàÐÍ
	// 		$action["action_list"][$num]["act_params"] = $post[$num]['act_params'];  //¶ÔÉè±¸¶ËÏÂ·¢ÈÎÎñÊ±£¬Éæ¼°µ½ÎÄ¼þµÄ²¿·Ö£¨FWÉý¼¶³ýÍâ£©£¬ÕâÀï´æ·ÅµÄÊÇÒªÏÂ·¢µÄÎÄ¼þÃû³Æ
	// 		$action["action_list"][$num]["act_id"] = intval($post[$num]['action_id']);	 //ÈÎÎñID
	// 		$action["action_list"][$num]["ftp_server_params"] = $post[$num]['ftp_server_params'];	  //ÏÂ·¢Éý¼¶FWÈÎÎñÊ±£¬ÕâÀï¼ÇÂ¼´æ·ÅFWµÄFTP serverµÄÕËºÅÃÜÂëÐÅÏ¢
	// 		$num++;
	// 	}
	// 	$action["action_num"]=$num;
	// 	$action["auto_key"] = Device::formRandChar($mac);
	// 	$action["flag"] = true;
	// 	$action["servertime"] = date("Y-m-d H:i:s");
		
	// 	echo json_encode($action);   //Í¨¹ýÒÔÉÏµÄ²Ù×÷»ñµÃ±íÖÐ·Ç2£¨¼´¶¨ÒåµÄ0,1£©µÄÈÎÎñ£¬·â×°Îªjson¸ñÊ½·¢ËÍ¸øÉè±¸¶Ë
	// }
	
	// function response_action_list_1023CPE_Bridge($mac,$modelname)
	// {
	// 	$get_model_name = Modelname::get_dev_model_name($modelname);
	// 	$sql = "SELECT action_id, act_type, act_params, ftp_server_params, action_state FROM
 //              act_type, actions
 //              WHERE actions.device_mac='{$mac}' AND
 //              actions.act_type_id = act_type.act_type_id AND
 //              actions.action_state<>" . A_STATE_FULL_ACKED;
	// 		 // <>±íÊ¾²»µÈÓÚ£»A_STATE_FULL_ACKEDÔÚphp/device/utilis.phpÖÐ¶¨ÒåÎª2  ¶ÔÓ¦µÄÊÇÊý¾Ý±íactionsÖÐµÄaction_stateµÄÊý¾Ý   2±íÊ¾³É¹¦£¬1±íÊ¾Ê§°Ü£¬0±íÊ¾Î´Ö´ÐÐ£»
	// 	$connection = \Yii::$app->db;
	// 	$command = $connection->createCommand($sql);
	// 	$post = $command->queryAll();
	// 	$count = $connection->createCommand($sql)->query()->rowCount;
		
	// 	$action = array();
	// 	$action["action_num"]=0;
	// 	$num = 0;
		
	// 	for($num = 0;$num<$count;$num++)
	// 	{
	// 		if($get_model_name == $modelname && "config" == $post[$num]["act_type"] && ("9" == $post[$num]["action_state"] || "8" == $post[$num]["action_state"]))
	// 		{	
	// 			continue; //½áÊø±¾´ÎÑ­»·½øÈëÏÂ´ÎÑ­»·
	// 		}
	// 		$action["action_list"][$num]["act_type"] = $post[$num]['act_type'];   //ÈÎÎñÀàÐÍ
	// 		$action["action_list"][$num]["act_params"] = $post[$num]['act_params'];  //¶ÔÉè±¸¶ËÏÂ·¢ÈÎÎñÊ±£¬Éæ¼°µ½ÎÄ¼þµÄ²¿·Ö£¨FWÉý¼¶³ýÍâ£©£¬ÕâÀï´æ·ÅµÄÊÇÒªÏÂ·¢µÄÎÄ¼þÃû³Æ
	// 		$action["action_list"][$num]["act_id"] = intval($post[$num]['action_id']);	 //ÈÎÎñID
	// 		$action["action_list"][$num]["ftp_server_params"] = $post[$num]['ftp_server_params'];	  //ÏÂ·¢Éý¼¶FWÈÎÎñÊ±£¬ÕâÀï¼ÇÂ¼´æ·ÅFWµÄFTP serverµÄÕËºÅÃÜÂëÐÅÏ¢
	// 		$num++;
	// 	}
	// 	$action["action_num"]=$num;
	// 	$action["auto_key"] = Device::formRandChar($mac);
	// 	$action["flag"] = true;
	// 	$action["servertime"] = date("Y-m-d H:i:s");
		
	// 	echo json_encode($action);   //Í¨¹ýÒÔÉÏµÄ²Ù×÷»ñµÃ±íÖÐ·Ç2£¨¼´¶¨ÒåµÄ0,1£©µÄÈÎÎñ£¬·â×°Îªjson¸ñÊ½·¢ËÍ¸øÉè±¸¶Ë
		
	// }
	
}



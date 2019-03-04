<?php
namespace backend\models\report;

use yii;
use backend\models\DevInfo;//引用DevInfo这张表
use backend\models\StationList;
use api\models\DevSSID;
use backend\models\StationList_real;//引用StationList_real这张表
use backend\models\Stat_sta_onlinetime;
use backend\models\Stat_dev_sta_realtime_bw;

class Process_report{

function report_handle($_get_param,$report_info)
{
$fp = fopen("/var/www/action_info.log", "a+");
fwrite($fp, "000000 ===".print_r($report_info,true)."\r\n");
fwrite($fp, "\r\n");
fclose($fp);

	$_mac = $_get_param['mac'];

	$_reportid =  $_get_param['reportID'];
	$_report_info = json_decode($report_info, true);
	
	switch ($_reportid) {
		case 0:
			$fp = fopen("/var/www/report_info.txt", "a+");
			fwrite($fp, "----------------start---mac = ".print_r($_mac,true)."-----time = ".print_r(date('Y-m-d H:i:s',time()),true)."-----------------\r\n");
			fwrite($fp, "0 ===".print_r($_report_info,true)."\r\n");
			fwrite($fp, "\r\n");
			fclose($fp);
			self::process_report_for_fap($_mac,$_report_info);
			break;
		case 1:
			$fp = fopen("/var/www/ac_info.log", "a+");
			fwrite($fp, "1 ===".print_r($_report_info,true)."\r\n");
			fwrite($fp, "\r\n");
			fclose($fp);
			self::process_report_for_ac($_mac,$_report_info);
			break;
		case 2:
			$fp = fopen("/var/www/ac_info.log", "a+");
			fwrite($fp, "2 ===".print_r($_report_info,true)."\r\n");
			fwrite($fp, "\r\n");
			fclose($fp);
			self::process_report_for_vac($_mac,$_report_info);
			break;
		case 3:
			$fp = fopen("/var/www/ac_info.log", "a+");
			fwrite($fp, "3 ===".print_r($_report_info,true)."\r\n");
			fwrite($fp, "\r\n");
			fclose($fp);
			self::process_report_for_tap($_mac,$_report_info);
			break;
		case 4:
			self::process_report_for_vtap($_mac,$_report_info);
			break;
		case 5:	
			$fp = fopen("/var/www/report_info.txt", "a+");
			fwrite($fp, "5 ===".print_r($_report_info,true)."\r\n");
			fwrite($fp, "\r\n");
			fclose($fp);			
			self::process_report_for_sta($_mac,$_report_info);
			break;
		case 6:
			$fp = fopen("/var/www/report_info.txt", "a+");
			fwrite($fp, "6 ===".print_r($_report_info,true)."\r\n");
			fwrite($fp, "\r\n");
			fclose($fp);
			self::process_report_for_gdm($_mac,$_report_info);
			break;
		case 7:
			$fp = fopen("/var/www/report_info.txt", "a+");
			fwrite($fp, "7 ===".print_r($_report_info,true)."\r\n");
			fwrite($fp, "\r\n");
			fclose($fp);
			self::process_report_for_ltefi_geo($_mac,$_report_info);
			break;
		case 8:
			$fp = fopen("/var/www/report_info.txt", "a+");
			fwrite($fp, "8 ===".print_r($_report_info,true)."\r\n");
			fwrite($fp, "\r\n");
			fclose($fp);
			self::process_report_for_ip_info($_mac,$_report_info);
			break;	
		case 9:
		$fp = fopen("/var/www/report_info.txt", "a+");
			fwrite($fp, "9 ===".print_r($_report_info,true)."\r\n");
			fwrite($fp, "\r\n");
			fclose($fp);
			self::process_report_for_templet_info($_mac,$_report_info);
			break;
		case 10:
			$fp = fopen("/var/www/report_info.txt", "a+");
			fwrite($fp, "10 ===".print_r($_report_info,true)."\r\n");
			fwrite($fp, "\r\n");
			fclose($fp);
			$device_info = self::get_device_info_by_mac($_mac);
			self::process_report_for_sta_apps($_mac,$_report_info, $device_info);
			break;
		case 11:
			$fp = fopen("/var/www/report_info.txt", "a+");
			fwrite($fp, "11 ===".print_r($_report_info,true)."\r\n");
			fwrite($fp, "\r\n");
			fclose($fp);
			self::process_report_for_dev_stats($_mac,$_report_info);
			break;
		case 12:
			self::process_report_for_vpn_client_stats($_mac,$_report_info);
			break;
		case 13:
			self::process_report_for_plugctl($_mac,$_report_info);
			break;
		case 14:
			$fp = fopen("/var/www/report_info.txt", "a+");
			fwrite($fp, "14 ===".print_r($_report_info,true)."\r\n");
			fwrite($fp, "\r\n");
			fclose($fp);
			self::process_report_for_ltefi_geo_fap($_mac,$_report_info);
			break;
		case 15:
			$fp = fopen("/var/www/report_info.txt", "a+");
			fwrite($fp, "15 ===".print_r($_report_info,true)."\r\n");
			fwrite($fp, "\r\n");
			fclose($fp);
			self::process_report_for_ltefi_gps_txrx($_mac,$_report_info);
			break;
		case 16:
			$fp = fopen("/var/www/report_info.txt", "a+");
			fwrite($fp, "16 ===".print_r($_report_info,true)."\r\n");
			fwrite($fp, "\r\n");
			fclose($fp);
			self::process_report_for_ltefi_car_info($_mac,$_report_info);
			break;
		case 17:
			//1023设备运行参数
			$fp = fopen("/var/www/report_info.txt", "a+");
			fwrite($fp, "17 ===".print_r($_report_info,true)."\r\n");
			fwrite($fp, "\r\n");
			fclose($fp);
			$_type = $_get_param['type'];
			$_opemode = $_get_param['opemode'];
			$_cardID = $_get_param['cardID'];
			$_model_name = $_get_param['modelname'];
			self::process_report_for_fap_Templet($_mac,$_type,$_opemode,$_cardID,$_model_name,$_report_info);
			break;
		case 18:
			$fp = fopen("/var/www/report_info.txt", "a+");
			fwrite($fp, "18 ===".print_r($_report_info,true)."\r\n");
			fwrite($fp, "\r\n");
			fclose($fp);
			//1023设备json_data
			self::process_report_for_fap_json_data($_mac,$_report_info);
			break;	
		case 19:
		    self::process_report_for_SP220_info($_mac,$_report_info); 
		    break;	
		default:
			echo "Unsupport Report!!!!";
			break;
	}
   
}

function process_report_for_SP220_info($mac,$jason_data){
    try{
	$nWDSNum = $jason_data["nWDSNum"];//Éè±¸¹ØÁªµÄWDS¸öÊý
	$asso_info = $jason_data["wds_info"];//Éè±¸¹ØÁªµÄWDSÐÅÏ¢
	$lan_ip = $jason_data["lan_ip"];//Éè±¸µÄIPµØÖ·
	$nEthNum = $jason_data["nEthNum"];//Éè±¸ÓÐÏß½Ó¿ÚµÄ¸öÊý
	$eth_info = $jason_data["eth_info"];//Éè±¸ÓÐÏß½Ó¿ÚµÄÐÅÏ¢
	$n2gSsidListNum = $jason_data["n2gSsidListNum"];//Éè±¸2G¿¨Æ¬ËÑË÷µ½µÄÖÜÎ§µÄSSID ¸öÊý
	$G2_list_info = $jason_data["2g_list_info"];//Éè±¸2G¿¨Æ¬ËÑË÷µ½µÄÖÜÎ§µÄSSID
	$n5gSsidListNum = $jason_data["n5gSsidListNum"];//Éè±¸5G¿¨Æ¬ËÑË÷µ½µÄÖÜÎ§µÄSSID ¸öÊý
	$G5_list_info = $jason_data["5g_list_info"];//Éè±¸5G¿¨Æ¬ËÑË÷µ½µÄÖÜÎ§µÄSSID
	$stat_assoc_fail = $jason_data["cli_assoc_fail_Num"];//station¹ØÁªÊ§°ÜµÄ¸öÊý£¬ Count of client association failure 
	$attack_num = $jason_data["AttackNum"];//Total number of tampering attack
	$G2_tx_power = $jason_data["tx_power_2g"];//AP transmission power
	$G5_tx_power = $jason_data["tx_power_5g"];//AP transmission power
	
	if($nWDSNum > 0){
		self::get_associated_signal_strength($mac,$asso_info);
	}
	
	self::get_Devtplist($mac,$lan_ip,$stat_assoc_fail,$attack_num,$G2_tx_power,$G5_tx_power);
	
	if($nEthNum > 0){
		self::get_Ethernet_interface_info($mac,$eth_info);
	}
	
	if($n2gSsidListNum > 0 || $n5gSsidListNum > 0)
		self::get_ssid_list_around_dev($mac,$n2gSsidListNum,$G2_list_info,$n5gSsidListNum,$G5_list_info);
	echo json_encode(array('SP220' => true));
}catch( \Exception $e){
		              echo json_encode(['message'=>$e->getMessage()]); 
		            }
}
/*function:reportid = 0
*	解析ap上报的信息
*/
function process_report_for_fap($mac,$fap_info)
{
	$model = new DevInfo();
	
	$dev_name = $fap_info["dev_name"];
	$hw_version = $fap_info["hw_version"];
	
   	$count = $model->updateAll(array(
   					'dev_name' => $dev_name,
                    'hw_version'  => $hw_version,
                    'terminal_number' => 0
                    ),
                    'mac_addr=:mac',array(':mac'=>$mac));

     if(isset($fap_info['station_info']['sta_app_num'])){

        self::process_report_for_sta($mac,$fap_info['station_info']);

    }
    echo json_encode(array( 'fap' => true ));

}


function process_ac_clear($mac)
{
	// Clear old ip table
	$query = "
		delete from IpList where ac_mac=\"".$mac."\"
	";
	$result = Yii::$app->db->createCommand($query)->execute();

	// Clear old taps
	$query = "
		delete from taplist where ac_mac=\"".$mac."\"
	";
	$result = Yii::$app->db->createCommand($query)->execute();
	
	// offline all staions
	$query = "
		update StationList set offline=1 where dev_mac=\"".$mac."\"
	";
	$result = Yii::$app->db->createCommand($query)->execute();
}

/*function:reportid = 1
*	解析ac上报的信息
*/
function process_report_for_ac($mac,$ac_info)
{
	$dev_name = $ac_info["dev_name"];
	$hw_version = $ac_info["hw_version"];
	$query = "
		UPDATE DevInfo 
		SET 
		dev_name = '$dev_name', 
		hw_version = '$hw_version', 
		terminal_number = 0 
		WHERE mac_addr = '$mac' ";

	$result = Yii::$app->db->createCommand($query)->execute();
	
	self::process_ac_clear($mac);
	self::process_report_for_ip_info($mac,$ac_info["ip_info"]);
	self::process_report_for_templet_info($mac,$ac_info["templet_info"]);
	echo json_encode(array('ac' => true));
}
/*function:reportid = 2
*	解析vac上报的信息
*/
function process_report_for_vac($mac,$vac_info)
{
	$dev_name = $vac_info["dev_name"];
	$hw_version = $vac_info["hw_version"];
	$query = "
		UPDATE DevInfo 
		SET 
		dev_name = '$dev_name', 
		hw_version = '$hw_version', 
		terminal_number = 0 
		WHERE mac_addr = '$mac' ";

	$result = Yii::$app->db->createCommand($query)->execute();
	echo json_encode(array('vac' => true));
}


function update_ltefi_list($mac,$ltefi)
{
	$query = "
		INSERT INTO 
			ltefi_list
		SET
			ac_mac=\"".$mac."\",
			ltefi_mac=\"".$ltefi["mac"]."\",
			ltefi_name=\"".$ltefi["name"]."\",
			ltefi_ip=\"".$ltefi["ip"]."\",
			ltefi_status=".$ltefi["status"].",
			cardID=".$ltefi["cardID"].",
			rx_cur=\"".$ltefi["rx_rate"]["cur"]."\",
			rx_avg=\"".$ltefi["rx_rate"]["avg"]."\",
			rx_max=\"".$ltefi["rx_rate"]["max"]."\",
			tx_cur=\"".$ltefi["tx_rate"]["cur"]."\",
			tx_avg=\"".$ltefi["tx_rate"]["avg"]."\",
			tx_max=\"".$ltefi["tx_rate"]["max"]."\",
			templet_name_1=\"".$ltefi["templet_in_use"][0]["templet_name"]."\",
			templet_name_2=\"".$ltefi["templet_in_use"][1]["templet_name"]."\",
			device_mode=\"".$ltefi["ltefi_info"]["device_mode"]."\",
			imei=\"".$ltefi["ltefi_info"]["IMEI"]."\",
			offline=\"".$ltefi["ltefi_info"]["offline"]."\"
		ON DUPLICATE KEY UPDATE
			ltefi_name=\"".$ltefi["name"]."\",
			ltefi_ip=\"".$ltefi["ip"]."\",
			ltefi_status=".$ltefi["status"].",
			cardID=".$ltefi["cardID"].",
			rx_cur=\"".$ltefi["rx_rate"]["cur"]."\",
			rx_avg=\"".$ltefi["rx_rate"]["avg"]."\",
			rx_max=\"".$ltefi["rx_rate"]["max"]."\",
			tx_cur=\"".$ltefi["tx_rate"]["cur"]."\",
			tx_avg=\"".$ltefi["tx_rate"]["avg"]."\",
			tx_max=\"".$ltefi["tx_rate"]["max"]."\",
			templet_name_1=\"".$ltefi["templet_in_use"][0]["templet_name"]."\",
			templet_name_2=\"".$ltefi["templet_in_use"][1]["templet_name"]."\",
			device_mode=\"".$ltefi["ltefi_info"]["device_mode"]."\",
			imei=\"".$ltefi["ltefi_info"]["IMEI"]."\",
			offline=\"".$ltefi["ltefi_info"]["offline"]."\"
	";
	$result = Yii::$app->db->createCommand($query)->execute();
	
	echo json_encode(array('ltelist' => true));
}
/*function:reportid = 3
*	解析tap上报的信息
*/
function process_report_for_tap($mac,$tap_info)
{
	for( $i=0;$i< $tap_info["tap_num"] ;$i++ ) {
		$tap=$tap_info["tap_list"][$i];
		$rx = $tap["rx_rate"];
		$tx = $tap["tx_rate"];
		if (isset($tap["ltefi_info"]) && strcmp($tap["ltefi_info"]["device_mode"], "")) {
			self::update_ltefi_list($mac,$tap);
		}
		
		$query = "
			INSERT INTO 
				taplist
			SET 
				tap_name=\"".$tap["name"]."\",
				tap_mac=\"".$tap["mac"]."\",
				tap_ip=\"".$tap["ip"]."\",
				tap_status=\"".$tap["status"]."\",
				cardID=".$tap["cardID"].",
				ac_mac=\"".$mac."\",
				rx_cur=\"".$rx["cur"]."\",
				rx_avg=\"".$rx["avg"]."\",
				rx_max=\"".$rx["max"]."\",
				tx_cur=\"".$tx["cur"]."\",
				tx_avg=\"".$tx["avg"]."\",
				tx_max=\"".$tx["max"]."\",
				templet_name_1=\"".$tap["templet_in_use"][0]["templet_name"]."\",
				templet_name_2=\"".$tap["templet_in_use"][1]["templet_name"]."\"
			ON DUPLICATE KEY UPDATE
				tap_name=\"".$tap["name"]."\",
				tap_ip=\"".$tap["ip"]."\",
				tap_status=\"".$tap["status"]."\",
				cardID=".$tap["cardID"].",
				rx_cur=\"".$rx["cur"]."\",
				rx_avg=\"".$rx["avg"]."\",
				rx_max=\"".$rx["max"]."\",
				tx_cur=\"".$tx["cur"]."\",
				tx_avg=\"".$tx["avg"]."\",
				tx_max=\"".$tx["max"]."\",
				templet_name_1=\"".$tap["templet_in_use"][0]["templet_name"]."\",
				templet_name_2=\"".$tap["templet_in_use"][1]["templet_name"]."\"
		";
		$result = Yii::$app->db->createCommand($query)->execute();
	}
	echo json_encode(array('tap' => true));
}
/*function:reportid = 4
*	解析vtap上报的信息
*/
function process_report_for_vtap($mac,$vtap_info) 
{
	// Clear old vtaps
	$query = "
		delete from vtaplist where vac_mac=\"".$mac."\"
	";
	$result = Yii::$app->db->createCommand($query)->execute();
	
	for( $i=0;$i< $vtap_info["vtap_num"] ;$i++ ) {
		$vtap = $vtap_info["vtap_list"][$i];
		$query = "
			INSERT INTO 
				vtaplist
			SET 
				vac_mac=\"".$mac."\",
				vtap_name=\"".$vtap["name"]."\",
				vtap_mac=\"".$vtap["mac"]."\",
				vtap_ip=\"".$vtap["ip"]."\",
				cardID=".$vtap["cardID"].",
				fw_version=\"".$vtap["fw_version"]."\",
				status=\"".$vtap["status"]."\",
				client_num=\"".$vtap["client_num"]."\",
				channel=\"".$vtap["channel"]."\",
				rx_bytes=\"".$vtap["rx_bytes"]."\",
				tx_bytes=\"".$vtap["tx_bytes"]."\"
		";
		
		$query .="
			ON DUPLICATE KEY UPDATE
				vac_mac=\"".$mac."\",
				vtap_name=\"".$vtap["name"]."\",
				vtap_ip=\"".$vtap["ip"]."\",
				cardID=".$vtap["cardID"].",
				fw_version=\"".$vtap["fw_version"]."\",
				status=\"".$vtap["status"]."\",
				channel=\"".$vtap["channel"]."\",
				client_num=\"".$vtap["client_num"]."\",
				rx_bytes=\"".$vtap["rx_bytes"]."\",
				tx_bytes=\"".$vtap["tx_bytes"]."\"
		";

		$result = Yii::$app->db->createCommand($query)->execute();
	}
	echo json_encode(array('vtap' => true));
}
/*function:reportid = 5
*	解析上报station的信息
*/
function process_report_for_sta($mac,$sta_info)
{
	$model = new DevInfo();
	$model2 = new StationList();

	if($sta_info['station_num'] > 0)
	{
		$result = $model->find()->select('terminal_number')->where(['mac_addr' =>$mac])->all();
	
    	$row = $result[0]['terminal_number'];
		$sta_num = $row + $sta_info['station_num'];
		$count = $model->updateAll(['terminal_number' => $sta_num],'mac_addr=:mac',array(':mac'=>$mac));
		$datetime = date('Y-m-d');
		$time = strtotime(date('Y-m-d H:m:s')) - strtotime(date('Y-m-d 00:00:00'));
		$dev_mac = $mac;

		for( $i = 0;$i< $sta_info['station_num'] ;$i++ ) 
		{				
			$sta = $sta_info['station_list'][$i];
			self::process_real_for_sta($mac, $sta);	
			$result = $model2->find()->select(['date',
					 							'online_time',
					 							'tx_bytes',
					 							'rx_bytes',
					 							'online_time_differ',
					 							'tx_bytes_differ',
					 							'rx_bytes_differ'])
			->where(['and',['sta_mac'=>$sta['mac']],['dev_mac'=>$dev_mac]])->all();

			self::process_report_for_sta_bw($mac, $sta);

			$query = 
				"SELECT date, online_time, tx_bytes, rx_bytes, ".
				"online_time_differ, tx_bytes_differ, rx_bytes_differ ".
				//", recent_offline ". //Modified by ljh 2014-10-30 13:07
				"from StationList where sta_mac = \"".$sta['mac']."\" and dev_mac=\"".$dev_mac."\" ".
				"and DATEDIFF(CURDATE(),date)=0";
			$result = Yii::$app->db->createCommand($query)->queryAll();
			$query_tail = "";
			
			$tap_mac = $sta['apmac'];
            // 新增 sp220
			if($model_name == "SP220-C01" || $model_name == "SP220-C02")
			{
				$arr_time = explode(":",$sta["online_time"]);
				$hour = $arr_time[0]*3600;
				$min = $arr_time[1]*60;
				$this_time = $hour+$min+$arr_time[2];
				$sta["online_time"] = $this_time;
			}
			
			$online = doubleval($sta['online_time']);
			$tx_bytes = doubleval($sta['tx_bytes']);
			$rx_bytes = doubleval($sta['rx_bytes']);

			if(isset($sta["TX_packets"])){
				$tx_packets = doubleval($sta["TX_packets"]);
			}
			else{
				$tx_packets = 0;
			}
			if(isset($sta["RX_packets"])){
				$rx_packets = doubleval($sta["RX_packets"]);
			}
			else {
				$rx_packets = 0;
			}
			if(isset($sta["RX_errors"])){
				$rx_error = doubleval($sta["RX_errors"]);
			}
			else{
				$rx_error = 0;
			}
	
			$last_diff_online = $sta['online_time'];
			$last_diff_rx = $sta['rx_bytes'];

			if(isset($sta["RX_packets"])){
				$last_diff_rx_packets = $sta["RX_packets"];
			}
			else {
				$last_diff_rx_packets = 0;
			}
			$last_diff_tx = $sta['tx_bytes'];

			if(isset($sta["TX_packets"])){
				$last_diff_tx_packets = $sta["TX_packets"];
			}
			else {
				$last_diff_tx_packets = 0;
			}
			if(isset($sta["RX_errors"])){
				$last_diff_rx_errors_packets = $sta["RX_errors"];
			}
			else{
				$last_diff_rx_errors_packets = 0;
			}
			
			if($result){
				$row = $result[0];
				
				$last_online = doubleval($row["online_time"]);
				$last_tx_bytes = doubleval($row["tx_bytes"]);
				$last_tx_packets = doubleval($row["tx_packets"]);
				$last_rx_bytes = doubleval($row["rx_bytes"]);
				$last_rx_packets = doubleval($row["rx_packets"]);
				$last_rx_errors_packets = doubleval($row["rx_error_packets"]);
				$online_differ = doubleval($row["online_time_differ"]);
				$tx_bytes_differ = doubleval($row["tx_bytes_differ"]);
				$tx_packets_differ = doubleval($row["tx_packets_differ"]);
				$rx_bytes_differ = doubleval($row["rx_bytes_differ"]);
				$rx_packets_differ = doubleval($row["rx_packets_differ"]);
				$rx_error_packets_differ = doubleval($row["rx_error_packets_differ"]);
				//$last_recent_offline = intval($row['recent_offline']); //Modified by ljh 2014-10-30 13:07
					
				if ($online < $online_differ) {
					$online = $last_online + $online;
					$tx_bytes = $last_tx_bytes + $tx_bytes;
					$rx_bytes = $last_rx_bytes + $rx_bytes;
					$tx_packets = $last_tx_packets + $tx_packets;
					$rx_packets = $last_rx_packets + $rx_packets;
					$rx_error = $last_rx_errors_packets + $rx_error;
				} else {
					$online = $last_online + $online - $online_differ;
					$tx_bytes = $last_tx_bytes + $tx_bytes - $tx_bytes_differ;
					$rx_bytes = $last_rx_bytes + $rx_bytes - $rx_bytes_differ;
					$tx_packets = $last_tx_packets + $tx_packets - $tx_packets_differ;
					$rx_packets = $last_rx_packets + $rx_packets - $rx_packets_differ;
					$rx_error = $last_rx_errors_packets + $rx_error - $rx_error_packets_differ;
				}
				
				$query = "UPDATE StationList SET ";
				$query_tail=
					" WHERE sta_mac = \"".$sta["mac"]."\" and date=\"".
					$datetime."\" and dev_mac=\"".$dev_mac."\"";
			} else {
				
				$query = "INSERT INTO StationList SET ";
				if ($online > $time){
					// Here is a risk, treat the change linear
					$tx_bytes = ceil($tx_bytes * $time / $online);
					$tx_packets = ceil($tx_packets * $time / $online);
					$rx_bytes = ceil($rx_bytes * $time / $online);
					$rx_packets = ceil($rx_packets * $time / $online);
					$rx_error = ceil($rx_error * $time / $online);
					$online = $time;
				}		
				self::remove_old_sta($sta["mac"], $dev_mac);		
			}

			$b64_flag = (isset($_REQUEST['b64']) && intval($_REQUEST['b64']) === 1) ? true : false;
			$is_b64_dev = self::is_base64_dev();
			
	 		if($b64_flag == true || $is_b64_dev == true){
				//ljh 0118/16
				// $query_sta_hostname = mb_convert_encoding(base64_decode($sta["sta_hostname"]), "UTF-8", "GBK");
				// $query_sta_ssid = mb_convert_encoding(base64_decode($sta["ssid"]), "UTF-8", "GBK");

				
				//Test3, 
				$query_sta_hostname = mb_convert_encoding(base64_decode(self::check_base64_decode($sta["sta_hostname"])), "UTF-8", "GBK");
				$query_sta_ssid = mb_convert_encoding(base64_decode(self::check_base64_decode($sta["ssid"])), "UTF-8", "GBK");

				if($model_name == "SP220-C01" || $model_name == "SP220-C02"){
					$query_sta_hostname = $sta["sta_hostname"];
					$query_sta_ssid = $sta["ssid"];
				}
	 		}else{
				$query_sta_hostname = $sta["sta_hostname"];
				$query_sta_ssid = $sta["ssid"];
			}

			$query .= "
				sta_mac=\"".$sta["mac"]."\",
				offline=0,
				sta_rssi=\"".$sta["rssi"]."\",
				tap_mac=\"".$tap_mac."\",
				dev_mac=\"".$dev_mac."\",
				ssid=\"".addslashes($query_sta_ssid)."\",
				card_id=".$sta["card_id"].",
				sta_os=\"".$sta["sta_os"]."\",
				sta_ip=\"".$sta["sta_ip"]."\",
				sta_hostname=\"".addslashes($query_sta_hostname)."\",
				online_time=\"".$online."\",
				tx_bytes=\"".$tx_bytes."\",
				tx_packets=\"".$tx_packets."\",
				rx_bytes=\"".$rx_bytes."\",
				rx_packets=\"".$rx_packets."\",
				rx_error_packets=\"".$rx_error."\",
				online_time_differ=\"".$last_diff_online."\",
				rx_bytes_differ=\"".$last_diff_rx."\",
				tx_bytes_differ=\"".$last_diff_tx."\",
				tx_packets_differ=\"".$last_diff_tx_packets."\",
				rx_packets_differ=\"".$last_diff_rx_packets."\",
				rx_error_packets_differ=\"".$last_diff_rx_errors_packets."\",
				date=\"".$datetime."\"
			";
			$query .= $query_tail;

			Yii::$app->db->createCommand($query)->execute();
		}
 } 
    echo json_encode(array('sta_list' => true));
}

/*function:reportid =6
*/

function process_report_for_gdm($mac,$gdm_info) 
{
	for( $i=0;$i< $gdm_info["gdmstatus_num"] ;$i++ ) {
		$gdm=$gdm_info["gdmstatus_list"][$i];
		
		$query = "
			INSERT INTO 
				gdm_test_report
			SET
				device_mac =\"".$mac."\",
				managed_device=\"".$gdm["mac"]."\",
				test_result = ".$gdm["status"]."
			ON DUPLICATE KEY UPDATE
				device_mac =\"".$mac."\",
				test_result = ".$gdm["status"]."
		";

		Yii::$app->db->createCommand($query)->execute();
	}
	echo json_encode(array('gdm' => true));
}


/*function:reportid =7
*/
function process_report_for_ltefi_geo($mac,$ltefi_info)
{
	// clear old ltefi
	$deadline = 365;
	$query = "
		select distinct mgmt.store_deadline as deadline from ltefi_list as lte 
			inner join DevInfo as dev on lte.ac_mac = dev.mac_addr 
			inner join ltefi_mgmt as mgmt on dev.user_name = mgmt.user_name
			where lte.ac_mac = \"".$mac."\"
	";

	$result = Yii::$app->db->createCommand($query)->queryAll();
	if($result){
		$row = $result[0];
		$deadline = $row["deadline"];
	}
	
	//taodeyu
	$ltefi_list_arr = $ltefi_info["ltefi_list"];
	$ltefi_mac_arr = array();//传过来的ltefi_mac
	foreach($ltefi_list_arr as $each_ltefi){
		$ltefi_mac_arr[] = strtolower($each_ltefi['mac']);
	}
	$ltefi_mac_arr = array_unique($ltefi_mac_arr);
	$ltefi_condition = self::db_create_in_device_php($ltefi_mac_arr, "ltefi_mac");
	$query = "select * from ltefi_mac where $ltefi_condition";

	$result = Yii::$app->db->createCommand($query)->queryAll();
	$ltefi_in_db_arr = array();//在数据库里的ltefi_mac
	if($result){
		$row = $result[0];
		$ltefi_in_db_arr[] = strtolower($row['ltefi_mac']);
	}
	$insert_ltefi_mac_arr = array();//不在数据库里的ltefi_mac,需要插入
	foreach($ltefi_mac_arr as $ltefi_mac){
		if(!in_array($ltefi_mac, $ltefi_in_db_arr)){
			$insert_ltefi_mac_arr[] = $ltefi_mac;
		}
	}
	$insert_ltefi_mac_arr_count = count($insert_ltefi_mac_arr);
	if($insert_ltefi_mac_arr_count > 0){//插入不在数据库里的ltefi_mac
		for($i = 0; $i < $insert_ltefi_mac_arr_count; $i++){
			if(($i % $insert_ltefi_mac_arr_count) == 0){
				$insert_query = "INSERT INTO ltefi_mac (ltefi_mac) VALUES ";
				$insert_query .= "('".$insert_ltefi_mac_arr[$i]."')";
			}else{
				$insert_query .= ",('".$insert_ltefi_mac_arr[$i]."')";
			}
		}
		$result = Yii::$app->db->createCommand($insert_query)->execute();
	}
	//找出所有id
	$query = "select * from ltefi_mac where $ltefi_condition";

	$result = Yii::$app->db->createCommand($query)->queryAll();

	$ltefi_in_db_arr = array();//当前在数据库里的ltefi_mac
	if($result){
		$row = $result[0];
		$ltefi_in_db_arr[strtolower($row['ltefi_mac'])] = $row['id'];
	}
	
	$distance_ltefo_arr = array();//整理成ltefi_mac=>array(list)的形式
	foreach($ltefi_info["ltefi_list"] as $each_ltefi_info){
		$ltefi_mac = strtolower($each_ltefi_info["mac"]);
		if(!empty($distance_ltefo_arr)){
			$distance_ltefo_arr[$ltefi_mac] = array();
		}

		$distance_ltefo_arr[$ltefi_mac][] = $each_ltefi_info;
	}
	$ltefi_info_temp["ltefi_num"] = 0;
	$ltefi_info_temp["ltefi_list"] = array();
	//$LTEFI_LIMIT_DISTANCE = C("LTEFI_LIMIT_DISTANCE");
	$LTEFI_LIMIT_DISTANCE = 50;
	foreach($distance_ltefo_arr as $each_ltefi_info){//根据每个ltefi的list，筛选距离。
		$i = 0;
		$arr_data_temp = array();
		foreach($each_ltefi_info as $each_info){
			if($i != 0){
				if(self::GetDistance($each_info['Lat'], $each_info['Lng'], $arr_data_temp[$i-1]['Lat'], $arr_data_temp[$i-1]['Lng']) < $LTEFI_LIMIT_DISTANCE){
					continue;
				}
			}
			$arr_data_temp[$i] = $each_info;
			$ltefi_info_temp["ltefi_num"] ++;
			$ltefi_info_temp["ltefi_list"][] = $each_info;
			$i++;
		}
	}
	$ltefi_info = $ltefi_info_temp;
	// insert ltefi geography information
	$insert_ltefi_mac_arr_count = $ltefi_info["ltefi_num"];
	for($i = 0; $i < $insert_ltefi_mac_arr_count; $i++){
		$ltefi = $ltefi_info["ltefi_list"][$i];
		if(($i % $insert_ltefi_mac_arr_count) == 0){
			$insert_query = "INSERT INTO ltefi_geo_list (did,sta_num,geo_time,rx_rate,tx_rate,longitude,latitude,velocity) VALUES ";
			$insert_query .= "('";
			$insert_query .= $ltefi_in_db_arr[strtolower($ltefi["mac"])]."','";
			$insert_query .= $ltefi["sta_num"]."','";
			$insert_query .= $ltefi["time"]."','";
			$insert_query .= $ltefi["rx_rate"]."','";
			$insert_query .= $ltefi["tx_rate"]."','";
			$insert_query .= $ltefi["Lng"]."','";
			$insert_query .= $ltefi["Lat"]."','";
			$insert_query .= $ltefi["Velocity"]."')";
		}else{
			$insert_query .= ",('";
			$insert_query .= $ltefi_in_db_arr[strtolower($ltefi["mac"])]."','";
			$insert_query .= $ltefi["sta_num"]."','";
			$insert_query .= $ltefi["time"]."','";
			$insert_query .= $ltefi["rx_rate"]."','";
			$insert_query .= $ltefi["tx_rate"]."','";
			$insert_query .= $ltefi["Lng"]."','";
			$insert_query .= $ltefi["Lat"]."','";
			$insert_query .= $ltefi["Velocity"]."')";
		}
	}
	
	$result = Yii::$app->db->createCommand($insert_query)->execute();
	echo json_encode(array('ltefi_geo' => true));
}


/*function:reportid=8
*/
function process_report_for_ip_info($mac,$ip_info)
{

	$query = "INSERT INTO IpList(ac_mac, tap_mac, interface, mac, ip) VALUES";

	for( $i=0;$i< $ip_info["ip_num"] ;$i++ ) {
		$ip=$ip_info["ip_list"][$i];
		if ($i)
			$query .=", ";
		//ljh 0119/16
		if( $ip["interface"] == "LAN" ) { //is TAP
			$query .= "(\"".$mac."\", \"".$ip["mac"]."\", \"".$ip["interface"]."\", \"".$ip["mac"]."\", \"".$ip["ip"]."\")";
		} else { //is Sta
			$query_get_tap_mac = "select tap_mac from StationList_real where sta_mac=\"".$ip["mac"]."\" AND dev_mac=\"".$mac."\" AND tap_mac is not NULL AND tap_mac != ''; ";
			
			$result = Yii::$app->db->createCommand($query_get_tap_mac)->queryAll();
			
			if ($result){
				$row = $result[0];
				$query .= "(\"".$mac."\", \"".$row['tap_mac']."\", \"".$ip["interface"]."\", \"".$ip["mac"]."\", \"".$ip["ip"]."\")";
			} else {
				$query .= "(\"".$mac."\", NULL, \"".$ip["interface"]."\", \"".$ip["mac"]."\", \"".$ip["ip"]."\")";
			}
				
		}
	}

	Yii::$app->db->createCommand($query)->execute();
	echo json_encode(array('ip_list' => true));
}

/*function:reportid=9
*解析模板信息
*/
function process_report_for_templet_info($mac,$templet_info) 
{		
	for( $i=0;$i< $templet_info["templet_num"] ;$i++ ) {
		$templet=$templet_info["templet_list"][$i];
		
		$query = "
			INSERT INTO 
				TempletTAP
			SET 
				ac_mac=\"".$mac."\",
				templet_name=\"".$templet["name"]."\",
				index_in_ac=\"".$templet["id"]."\"
			ON DUPLICATE KEY UPDATE
				templet_name=\"".$templet["name"]."\"	
		";
		Yii::$app->db->createCommand($query)->execute();
	}

	$query = "delete from TempletTAP where ac_mac = '$mac' and index_in_ac > ".$templet_info["templet_num"];
	Yii::$app->db->createCommand($query)->execute();
	
	echo json_encode(array('templet_list' => true));
}

/*function:reportid=10
*
*/

function process_report_for_sta_apps($mac,$sta_apps, $device_info=array())
{
	$user_name = addslashes(isset($device_info['user_name']) ? $device_info['user_name'] : "");
	$management = addslashes(isset($device_info['management']) ? $device_info['management'] : "");
	$group_name = addslashes(isset($device_info['group_name']) ? $device_info['group_name'] : "");
	$datetime = date("Y-m-d H:i:s");	
	$sta_num = $sta_apps["sta_app_num"];
	
	if ($sta_num == 0)
		return;
	
	for( $i=0;$i< $sta_num;$i++ ) {
		$sta = $sta_apps["sta_app_list"][$i];
		$query="select * from stat_client_realtime_flow where dev_mac=\"".$mac."\" and sta_mac=\"".$sta["sta_mac"]."\" and app_type=\"".$sta["type"]."\"";
		
		$result = Yii::$app->db->createCommand($query)->queryAll();

		if ($result){
			$query = "update stat_client_realtime_flow set ";
			$query_tail = " where dev_mac=\"".$mac."\" and sta_mac=\"".$sta["sta_mac"]."\" and app_type=\"".$sta["type"]."\"";
		} else {
			$query = "INSERT INTO stat_client_realtime_flow set ";
			$query_tail = "";
		}
		$query .="dev_mac = \"".$mac."\",
				sta_mac = \"".$sta["sta_mac"]."\",
				app_type = \"".$sta["type"]."\",
				update_time = \"".$datetime."\",
				tx_bytes = \"".$sta["tx_bytes"]."\",
				rx_bytes = \"".$sta["rx_bytes"]."\",
				user_name =\"".$user_name."\", 
				management=\"".$management."\", 
				group_name=\"".$group_name."\"
	         ";
		$query .=$query_tail;

		$result = Yii::$app->db->createCommand($query)->execute();
	}

	$sta_arr = array();
	foreach($sta_apps["sta_app_list"] as $value){
		if( !is_array($sta_arr[$value["sta_mac"]])){
			$sta_arr[$value["sta_mac"]] = array();
		}
		if(count($sta_arr[$value["sta_mac"]]) < 3){
			$sta_arr[$value["sta_mac"]][] = $value["type"];
		}
	}
	$date = date("Y-m-d");
	foreach($sta_arr as $sta => $each_sta){
		$app_types = "";
		for($i=0; $i < count($each_sta); $i++){
			$app_types .= $each_sta[$i].',';
		}
		$app_types = addslashes(substr($app_types, 0, strlen($app_types)-1));
		$query = "UPDATE StationList set sta_apptype='$app_types' WHERE dev_mac='$mac' and sta_mac='$sta' AND `date`='$date'";

		$result = Yii::$app->db->createCommand($query)->execute();
		
		$query = "UPDATE StationList_real set sta_apptype='$app_types' WHERE dev_mac='$mac' and sta_mac='$sta'";

		$result = Yii::$app->db->createCommand($query)->execute();
	}
	echo json_encode(array('sta_apps' => true));
}	

/*function:reportid=11
*
*/
function process_report_for_dev_stats($mac,$dev_stats)
{
	$datetime = date("Y-m-d H:i:s");
	$query="select * from stat_dev_realtime_bw where dev_mac='$mac'";

	$result = Yii::$app->db->createCommand($query)->queryAll();
	
	if ($result){
		$query = "update stat_dev_realtime_bw set ";
		$query_tail = " where dev_mac='$mac'";
	} else {
		$query = "insert into stat_dev_realtime_bw set ";
		$query_tail = "";
	}
	
	$query .= "dev_mac='$mac', 
			  tx_bps = \"".$dev_stats["tx_throughput"]."\",
			  rx_bps = \"".$dev_stats["rx_throughput"]."\",
			  total_bps = \"".$dev_stats["tot_throughput"]."\",
			  update_time = \"".$datetime."\"
			 ";
	$query .=$query_tail;

	$result = Yii::$app->db->createCommand($query)->execute();

	echo json_encode(array('dev_stats' => true));
}



/*function:reportid=12
*
*/
function process_report_for_vpn_client_stats($mac,$jason_data)
{
	$client_stat = $jason_data["vpnstatus_list"][0]['status'];
	$client_mac = $jason_data["vpnstatus_list"][0]['mac'];
	$client_ip = $jason_data["vpnstatus_list"][0]['ip'];
	$client_ontime = $jason_data["vpnstatus_list"][0]['vpnontime'];
	$query = sprintf("update vpnclient_list set vpn_stat=%d,vpn_client_ip='%s',vpn_link_time=%d where dev_mac='%s'",$client_stat,$client_ip,$client_ontime,$client_mac);
	
	$result = Yii::$app->db->createCommand($query)->execute();
	
	echo json_encode(array('vpn' => true));
}


/*function:reportid=13
*
*/
function process_report_for_plugctl($mac,$jason_data){
	$current = addslashes($jason_data["current"]);//电流
	$voltage =addslashes( $jason_data["voltage"]);//电压
	$watt = addslashes($jason_data["watt"]);//功率(暂未使用)
	$energy = addslashes($jason_data["energy"]);//功耗
	$frequency = addslashes($jason_data["frequency"]);//(暂未使用)
	$dev_switch = addslashes($jason_data["relay_status"]);
	//add by taodeyu 2015-07-09 可能需要与设备端沟通使用数组键的名称
	$device_temper = addslashes($jason_data["device_temper"]);//设备温度
	$ambient_temper = addslashes($jason_data["ambient_temper"]);//环境温度
	$pm25 = addslashes($jason_data["pm25"]);//pm2.5
	
	$alert_current = addslashes($jason_data["alert"]['current']);//电流保护状态
	$alert_voltage = addslashes($jason_data["alert"]['voltage']);//电压保护状态
	$alert_energy = addslashes($jason_data["alert"]['energy']);//功耗保护状态
	$alert_device_temper = addslashes($jason_data["alert"]['device_temper']);//设备温度保护状态
	
	$query = "UPDATE device_1029 SET
			 now_power='$voltage',  
			 now_current='$current',  
			 now_watt='$watt',  
			 now_energy='$energy',  
			 now_frequency='$frequency',
			 now_temper='$device_temper', 
			 now_ambient_temper='$ambient_temper', 
			 now_pm25='$pm25',  
			 
			 dev_switch='$dev_switch', 
			 power_protect_status='$alert_voltage', 
			 current_protect_status='$alert_current', 
			 energy_protect_status='$alert_energy',
			 temper_protect_status='$alert_device_temper'  
			 WHERE mac_addr='$mac'";

	$result = Yii::$app->db->createCommand($query)->execute();
}

/*function:reportid=14
*
*/
function process_report_for_ltefi_geo_fap($mac,$ltefi_info)
{
	$is_dev_reged = self::is_dev_reged($mac);

	if(!$is_dev_reged){
		return;
	}

	self::update_ltefi_list_fap($mac,$ltefi_info);

	//2. 更新ltefi_mac表（索引）
	$ltefi_list_arr[] = $ltefi_info["mac"];
	$ltefi_mac_arr = array();//传过来的ltefi_mac
	foreach($ltefi_list_arr as $each_ltefi){
		$ltefi_mac_arr[] = strtolower($each_ltefi);
	}
	
	$ltefi_mac_arr = array_unique($ltefi_mac_arr);
	print_r($ltefi_mac_arr);
	$ltefi_condition = self::db_create_in_device_php($ltefi_mac_arr, "ltefi_mac");
	$query = "select * from ltefi_mac where $ltefi_condition";

	$result = Yii::$app->db->createCommand($query)->queryAll();
	
	$ltefi_in_db_arr = array();//在数据库里的ltefi_mac
	if($result){
		$row = $result[0];
		$ltefi_in_db_arr[] = strtolower($row['ltefi_mac']);
	}
	
	$insert_ltefi_mac_arr = array();//不在数据库里的ltefi_mac,需要插入
	foreach($ltefi_mac_arr as $ltefi_mac){
		if(!in_array($ltefi_mac, $ltefi_in_db_arr)){
			$insert_ltefi_mac_arr[] = $ltefi_mac;
		}
	}
	$insert_ltefi_mac_arr_count = count($insert_ltefi_mac_arr);
	
	//echo($insert_ltefi_mac_arr_count);
	if($insert_ltefi_mac_arr_count > 0){//插入不在数据库里的ltefi_mac
		for($i = 0; $i < $insert_ltefi_mac_arr_count; $i++){
			if(($i % $insert_ltefi_mac_arr_count) == 0){
				$insert_query = "INSERT INTO ltefi_mac (ltefi_mac) VALUES ";
				$insert_query .= "('".$insert_ltefi_mac_arr[$i]."')";
			}else{
				$insert_query .= ",('".$insert_ltefi_mac_arr[$i]."')";
			}
		}
		echo($insert_query);
		$result = Yii::$app->db->createCommand($insert_query)->execute();
	}
	
	//3. 更新详细信息（坐标/速度等）
	//找出所有id
	$query = "select * from ltefi_mac where $ltefi_condition";

	$result = Yii::$app->db->createCommand($query)->queryAll();
	
	$ltefi_in_db_arr = array();//当前在数据库里的ltefi_mac
	if($result){
		$row = $result[0];
		$ltefi_in_db_arr[strtolower($row['ltefi_mac'])] = $row['id'];
	}
	
	
	$distance_ltefo_arr = array();//整理成ltefi_mac=>array(list)的形式
	print_r($ltefi_in_db_arr);
	
	$ltefi_mac = $ltefi_info["mac"];
	
	if(!empty($distance_ltefo_arr)){

		$distance_ltefo_arr[$ltefi_mac] = array();
	}
	
	$distance_ltefo_arr[$ltefi_mac][] = $ltefi_info;
	print_r($distance_ltefo_arr);
	
	$ltefi_info_temp["ltefi_num"] = 0;
	$ltefi_info_temp["ltefi_list"] = array();
	$LTEFI_LIMIT_DISTANCE = 50;

	foreach($distance_ltefo_arr as $each_ltefi_info){//根据每个ltefi的list，筛选距离。
		$i = 0;
		$arr_data_temp = array();
		foreach($each_ltefi_info as $each_info){
			if($i != 0){
				if(self::GetDistance($each_info['Lat'], $each_info['Lng'], $arr_data_temp[$i-1]['Lat'], $arr_data_temp[$i-1]['Lng']) < $LTEFI_LIMIT_DISTANCE){
					continue;
				}
			}
			$arr_data_temp[$i] = $each_info;
			$ltefi_info_temp["ltefi_num"] ++;
			$ltefi_info_temp["ltefi_list"][] = $each_info;
			$i++;
		}
	}

	$ltefi_info = $ltefi_info_temp;
	// insert ltefi geography information
	$insert_ltefi_mac_arr_count = $ltefi_info["ltefi_num"];
	
	for($i = 0; $i < $insert_ltefi_mac_arr_count; $i++){
		$ltefi = $ltefi_info["ltefi_list"][$i];
		if($ltefi["time"]=='0000-00-00 00:00:00')
		{
			$ltefi["time"] = date("Y-m-d H:i:s");
		}
		
		if(($i % $insert_ltefi_mac_arr_count) == 0){
			
			//$insert_query = "INSERT INTO ltefi_geo_list (did,sta_num,ltefi_mac,geo_time,rx_rate,tx_rate,longitude,latitude,velocity) VALUES ";
			$insert_query = "INSERT INTO ltefi_geo_list (did,sta_num,geo_time,rx_rate,tx_rate,longitude,latitude,cell_id,cell_rssi,velocity) VALUES ";
			$insert_query .= "('";
			$insert_query .= $ltefi_in_db_arr[strtolower($ltefi["mac"])]."','";
			$insert_query .= $ltefi["sta_num"]."','";
			//$insert_query .= $mac."','";
			$insert_query .= $ltefi["time"]."','";
			$insert_query .= $ltefi["rx_rate"]."','";
			$insert_query .= $ltefi["tx_rate"]."','";
			$insert_query .= $ltefi["Lng"]."','";
			$insert_query .= $ltefi["Lat"]."','";
			$insert_query .= $ltefi["cell_id"]."','";
			$insert_query .= $ltefi["cell_rssi"]."','";
			$insert_query .= $ltefi["Velocity"]."')";
		}else{
			$insert_query .= ",('";
			$insert_query .= $ltefi_in_db_arr[strtolower($ltefi["mac"])]."','";
			$insert_query .= $ltefi["sta_num"]."','";
			//$insert_query .= $mac."','";
			$insert_query .= $ltefi["time"]."','";
			$insert_query .= $ltefi["rx_rate"]."','";
			$insert_query .= $ltefi["tx_rate"]."','";
			$insert_query .= $ltefi["Lng"]."','";
			$insert_query .= $ltefi["Lat"]."','";
			$insert_query .= $ltefi["cell_id"]."','";
			$insert_query .= $ltefi["cell_rssi"]."','";
			$insert_query .= $ltefi["Velocity"]."')";
		}
	}

	$result = Yii::$app->db->createCommand($insert_query)->execute();
	echo json_encode(array('ltefi_geo' => true));
}

/*function:reportid = 15
*
*/
function process_report_for_ltefi_gps_txrx($mac,$ltefi_info)
{
	
	$is_dev_reged = self::is_dev_reged($mac);
	if(!$is_dev_reged)
		return false;

	$datetime = date("Y-m-d H:i:s");
	$ltefi_tx = $ltefi_info["4G_tx"];
	$ltefi_rx = $ltefi_info["4G_rx"];
	$Firstroad = $ltefi_info["Firstroad"];
	$Secondroad = $ltefi_info["Secondroad"];
	$Thirdroad = $ltefi_info["Thirdroad"];
	$Fourthroad = $ltefi_info["Fourthroad"];
	
	$insert_query = "INSERT INTO stat_ltefi_txrx (ac_mac,ltefi_mac,tx_bytes,rx_bytes,update_time) VALUES ";
	$insert_query .= "('"."NA";
	$insert_query .= "','".$mac;
	$insert_query .= "','".$ltefi_tx;
	$insert_query .= "','".$ltefi_rx;
	$insert_query .= "','".$datetime;
	$insert_query .= "')";
	
	$result = Yii::$app->db->createCommand($insert_query)->execute();

	
	
	$insert_query3 = "update DevInfo set ";
	$insert_query3 .= "Relay1='" . $Firstroad . "', " ;
	$insert_query3 .= "Relay2='" . $Secondroad . "', " ;
	$insert_query3 .= "Relay3='" . $Thirdroad . "', " ;
	$insert_query3 .= "Relay4='" . $Fourthroad . "'" ;
	$insert_query3 .= " where mac_addr='$mac'";

	$result = Yii::$app->db->createCommand($insert_query3)->execute();

    if($model_name == 'XN-1033'){
		$action = array();
		$action['relay1'] = 1;
		$action['relay2'] = 1;
		$action['relay3'] = 1;
		$action['relay4'] = 1;
		
		echo json_encode($action);
	}
	else {
		echo json_encode(array('result' => true));
	}
	
}

/*function:reportid = 16
*
*/
function process_report_for_ltefi_car_info($mac,$ltefi_info)
{
	$is_dev_reged = self::is_dev_reged($mac);
	if(!$is_dev_reged)
		return false;

	$insert_query = "INSERT INTO ltefi_car_info (";
	$insert_query .= "server_time, ";
	$insert_query .= "gps_utc_time, ";
	$insert_query .= "gps_valid, ";
	$insert_query .= "gps_lat, ";
	$insert_query .= "gps_lat_point, ";
	$insert_query .= "gps_long, ";
	$insert_query .= "gps_long_point, ";
	$insert_query .= "gps_velocity, ";
	$insert_query .= "gps_dir, ";
	$insert_query .= "gps_utc_date, ";
	$insert_query .= "gps_mag_dec, ";
	$insert_query .= "gps_mag_dir, ";
	$insert_query .= "gps_mode, ";
	$insert_query .= "command, ";
	$insert_query .= "ltefi_mac, ";
	$insert_query .= "acc_state, ";
	$insert_query .= "ltefi_date, ";
	$insert_query .= "ltefi_time, ";
	$insert_query .= "ltefi_conn, ";
	$insert_query .= "dio_state, ";
	$insert_query .= "oth_v00, ";
	$insert_query .= "oth_v01, ";
	$insert_query .= "oth_v02, ";
	$insert_query .= "oth_v03, ";
	$insert_query .= "oth_v04, ";
	$insert_query .= "oth_v05, ";
	$insert_query .= "oth_v06, ";
	$insert_query .= "oth_v07, ";
	$insert_query .= "oth_v08, ";
	$insert_query .= "oth_v09";
	$insert_query .= ") VALUES ";
	
	$insert_query .= "('".date("YmdHis");
	$insert_query .= "','".$ltefi_info["gps_utc_time"];
	$insert_query .= "','".$ltefi_info["gps_valid"];
	$insert_query .= "','".$ltefi_info["gps_lat"];
	$insert_query .= "','".$ltefi_info["gps_lat_point"];
	$insert_query .= "','".$ltefi_info["gps_long"];
	$insert_query .= "','".$ltefi_info["gps_long_point"];
	$insert_query .= "','".$ltefi_info["gps_velocity"];
	$insert_query .= "','".$ltefi_info["gps_dir"];
	$insert_query .= "','".$ltefi_info["gps_utc_date"];
	$insert_query .= "','".$ltefi_info["gps_mag_dec"];
	$insert_query .= "','".$ltefi_info["gps_mag_dir"];
	$insert_query .= "','".$ltefi_info["gps_mode"];
	$insert_query .= "','".$ltefi_info["command"];
	$insert_query .= "','".$ltefi_info["ltefi_mac"];
	$insert_query .= "','".$ltefi_info["acc_state"];
	$insert_query .= "','".$ltefi_info["ltefi_date"];
	$insert_query .= "','".$ltefi_info["ltefi_time"];
	$insert_query .= "','".$ltefi_info["ltefi_conn"];
	$insert_query .= "','".$ltefi_info["dio_state"];
	$insert_query .= "','".$ltefi_info["oth_v00"];
	$insert_query .= "','".$ltefi_info["oth_v01"];
	$oth_v02 = '';
	if($ltefi_info["oth_v02"] != 'null')
	{
		$oth_v02=$ltefi_info["oth_v02"];
	}
	$insert_query .= "','".$oth_v02;	
	//$insert_query .= "','".$ltefi_info["oth_v02"];
	$insert_query .= "','".$ltefi_info["oth_v03"];
	$insert_query .= "','".$ltefi_info["oth_v04"];
	$insert_query .= "','".$ltefi_info["oth_v05"];
	$insert_query .= "','".$ltefi_info["oth_v06"];
	$insert_query .= "','".$ltefi_info["oth_v07"];
	$insert_query .= "','".$ltefi_info["oth_v08"];
	$insert_query .= "','".$ltefi_info["oth_v09"];
	$insert_query .= "')";

	$mac = strtolower($mac);
	if(0)
	{
		$datetime = date("Y-m-d H:i:s");
		file_put_contents('./Device.log', $datetime." insert_query= " . $insert_query."\r\n", FILE_APPEND);	
	}

	$result = Yii::$app->db->createCommand($insert_query)->execute();
	
	$insert_query = "INSERT INTO ltefi_car_info_today (";
	$insert_query .= "server_time, ";
	$insert_query .= "ltefi_mac, ";
	$insert_query .= "ltefi_date, ";
	$insert_query .= "ltefi_time, ";
	$insert_query .= "oth_v01";
	$insert_query .= ") VALUES ";
	
	$insert_query .= "('".date("YmdHis");
	$insert_query .= "','".$ltefi_info["ltefi_mac"];
	$insert_query .= "','".$ltefi_info["ltefi_date"];
	$insert_query .= "','".$ltefi_info["ltefi_time"];
	$insert_query .= "','".$ltefi_info["oth_v01"];
	$insert_query .= "')";

	$mac = strtolower($mac);
	if(0)
	{
		$datetime = date("Y-m-d H:i:s");
		file_put_contents('./Device.log', $datetime." insert_query= " . $insert_query."\r\n", FILE_APPEND);	
	}
	
	$result = Yii::$app->db->createCommand($insert_query)->execute();
	
	self::set_update_car_info_for_hantek($mac,$ltefi_info);
	
	echo json_encode(array('result' => true));
}
/*function:reportid = 17
*
*/
function process_report_for_fap_Templet($mac,$type,$opemode,$cardID,$model_name,$fap_info){
	
    try{
	if($opemode == "CPE")
	{
		$query = "SELECT 
				mac_addr 
			  FROM 
				TempletCPE
			  WHERE mac_addr = \"".$mac."\"
	
		";

		$result = Yii::$app->db->createCommand($query)->queryAll();
		if(empty($result)){
			$query = "
			INSERT INTO 
				TempletCPE
			SET 
				mac_addr= '$mac',
				model_name= '$model_name',
				card_id= '$cardID'
				";
			$result = Yii::$app->db->createCommand($query)->execute();		
		}
	}
	
	if($opemode == "Bridge"){
		$query = "SELECT 
				mac_addr 
			  FROM 
				TempletBridge
			  WHERE mac_addr = \"".$mac."\"
	
		";
		$result = Yii::$app->db->createCommand($query)->queryAll();

		if(empty($result ))
		{
			$query = "
			INSERT INTO 
				TempletBridge
			SET 
				mac_addr= '$mac',
				model_name= '$model_name',
				card_id= '$cardID'
				";
			$result = Yii::$app->db->createCommand($query)->execute();
		}
	}else{//ÊÊÓÃÓÚ³ýÁË1023 Bridge ºÍ CPEÍâµÄÄ£Ê½
		$query = "SELECT opemode,all_mac FROM DevInfo WHERE mac_addr = \"".$mac."\"";

		$result = Yii::$app->db->createCommand($query)->queryAll();
        $row_opemode =$result[0];

		$mac_arr = explode(",",$row_opemode['all_mac']);
		
		
		$opemode_info = $row_opemode['opemode'];
		if(is_array($opemode_info)){
			foreach($opemode_info as $key=>$value){
			if(is_array($value)){
				foreach($value as $_key => $val){
					if($val == "CPE")
					{
						$this_mac = $mac_arr[$key];
						$query = "SELECT mac_addr FROM TempletCPE WHERE mac_addr = \"".$this_mac."\"";

						$result = Yii::$app->db->createCommand($query)->queryAll();

						$row_CPE =$result[0];
						if(empty($row_CPE['mac_addr']))
						{
							$query = "
							INSERT INTO 
								TempletCPE
							SET 
								mac_addr= '$this_mac',
								model_name= '$model_name',
								card_id= '$cardID'
								";
							$result = Yii::$app->db->createCommand($query)->execute();
						}
					}else if($val == "WDS"){
						//fwrite($fp, "mac_arr === ".$mac_arr[$key]."\r\n");
						$this_mac = $mac_arr[$key];
						$query = "SELECT mac_addr FROM TempletBridge WHERE mac_addr = \"".$this_mac."\"";
						$result = Yii::$app->db->createCommand($query)->queryAll();

						$row_Bridge = $result[0];
						if(empty($row_Bridge['mac_addr']))
						{
							$query = "
							INSERT INTO 
								TempletBridge
							SET 
								mac_addr= '$this_mac',
								model_name= '$model_name',
								card_id= '$cardID'
								";
							$result = Yii::$app->db->createCommand($query)->execute();
						}
				    }
			    }
		    }
		    }
		}
		
	}
	
	
	$query = "SELECT 
				mac_addr 
			  FROM 
				DevTemplet
			  WHERE mac_addr = \"".$mac."\"
	
	";
	$result = Yii::$app->db->createCommand($query)->queryAll();

 	if($result){
 		$row = $result[0];
 	}
	
	if(empty($result))
	{	
	
		$query = "
			INSERT INTO 
				DevTemplet
			SET 
				mac_addr= '$mac',
				model_name= '$model_name',
				card_id= '$cardID',
				dev_type= '$type',
				dev_opemode= '$opemode' 
				";
		$result = Yii::$app->db->createCommand($query)->execute();
	}
	else if($opemode == "AP" && !empty($row['mac_addr']))
	{
	
		$wireless_mode = $fap_info["wireless_mode"];
		$channel = $fap_info["channel"];
		$date_rate = $fap_info["date_rate"];
		
		$query = "
			UPDATE DevTemplet
			SET 
			dev_opemode = 'AP',
			WirelessMode = '$wireless_mode', 
			Channel = '$channel',
			TransmitRate = '$date_rate'
			WHERE mac_addr = '$mac' ";

		$result = Yii::$app->db->createCommand($query)->execute();
		
		$query = "
		UPDATE DevTemplet
		SET 
		VAP0_VAPEnable = '0',
		VAP1_VAPEnable = '0',
		VAP2_VAPEnable = '0',
		VAP3_VAPEnable = '0',
		VAP4_VAPEnable = '0',
		VAP5_VAPEnable = '0',
		VAP6_VAPEnable = '0',
		VAP7_VAPEnable = '0'
		WHERE mac_addr = '$mac' ";
		$result = Yii::$app->db->createCommand($query)->execute();
		if(is_array($fap_info["ap_vap_auth_info"])){
			foreach($fap_info["ap_vap_auth_info"] as $key => $value){   
				$id = $value["vap_id"];
				$VAPEnable = $value["vap_enable"];
				$ProfileName = $value["vap_profile_name"];
				$SSID = $value["ssid"];
				$SSIDSuppress = $value["broadcast_ssid_enable"];
				$Auth = $value["vap_authtype"];
				$Encryption = $value["vap_encryption"];
				$WepPassPhrase = $value["Wep_Passphrase"];
				$WepKeyDefaultIdx = $value["default_key"];
				$WepKey1 = $value["WepKey1"];
				$WepKey2 = $value["WepKey2"];
				$WepKey3 = $value["WepKey3"];
				$WepKey4 = $value["WepKey4"];
				$WpaPSK = $value["wpa_passphrase"];
				
				$query = "
				UPDATE DevTemplet
				SET 
				VAP".$id."_VAPEnable = '$VAPEnable',
				VAP".$id."_ProfileName = '$ProfileName',
				VAP".$id."_SSID = '$SSID',
				VAP".$id."_SSIDSuppress = '$SSIDSuppress',
				VAP".$id."_Auth = '$Auth',
				VAP".$id."_Encryption = '$Encryption',
				VAP".$id."_WepPassPhrase = '$WepPassPhrase',
				VAP".$id."_WepKeyDefaultIdx = '$WepKeyDefaultIdx',
				VAP".$id."_WepKey1 = '$WepKey1',
				VAP".$id."_WepKey2 = '$WepKey2',
				VAP".$id."_WepKey3 = '$WepKey3 ',
				VAP".$id."_WepKey4 = '$WepKey4',
				VAP".$id."_WpaPSK = '$WpaPSK'
				WHERE mac_addr = '$mac' ";

				$result = Yii::$app->db->createCommand($query)->execute();
			}
		}
		
		
	}else if($opemode == "Bridge" && !empty($row['mac_addr'])){
		$wireless_mode = $fap_info["wireless_mode"];
		$channel = $fap_info["channel"];
		$ChannelMode = $fap_info["channel_mode"];
		$date_rate = $fap_info["date_rate"];
		
		$NetworkAuth = $fap_info["wds_authtype"];
		$DataEncryption = $fap_info["wds_encryption"];
		$wpa_passphrase = $fap_info["wpa_passphrase"];
		
		$Passphrase = $fap_info["Wep_Passphrase"];
		$key_num = $fap_info["default_key"];
		if($fap_info["default_key"] == "0")
		{
			$key_num = Null;
		}
		
		$wep_key1 = $fap_info["WepKey1"];
		$wep_key2 = $fap_info["WepKey2"];
		$wep_key3 = $fap_info["WepKey3"];
		$wep_key4 = $fap_info["WepKey4"];
		
		$wds_mac_addr1 = $fap_info["wds_mac1"];
		$wds_mac_addr2 = $fap_info["wds_mac2"];
		$wds_mac_addr3 = $fap_info["wds_mac3"];
		$wds_mac_addr4 = $fap_info["wds_mac4"];
			
		$query = "
			UPDATE DevTemplet
			SET 
			dev_opemode = 'Bridge',
			WirelessMode = '$wireless_mode', 
			Channel = '$channel',
			ChannelMode = '$ChannelMode',
			TransmitRate = '$date_rate',
			NetworkAuth = '$NetworkAuth',
			DataEncryption = '$DataEncryption',
			wpa_passphrase = '$wpa_passphrase',
			Passphrase = '$Passphrase',
			key_num = '$key_num',
			wep_key1 = '$wep_key1',
			wep_key2 = '$wep_key2',
			wep_key3 = '$wep_key3',
			wep_key4 = '$wep_key4',
			wds_mac_addr1 = '$wds_mac_addr1',
			wds_mac_addr2 = '$wds_mac_addr2',
			wds_mac_addr3 = '$wds_mac_addr3',
			wds_mac_addr4 = '$wds_mac_addr4'
			WHERE mac_addr = '$mac' ";
		$result = Yii::$app->db->createCommand($query)->execute();
		// if (!(@ mysql_query($query, $dbconn))) {
		// 	showerror($query);
		// }
	}
	else if($opemode == "CPE" && !empty($row['mac_addr']))
	{
		$WirelessNetworkName = $fap_info["ssid"];
		$lock_AP_MAC = $fap_info["locked_ap_mac"];
		$WirelessMode = $fap_info["wireless_mode"];
		$TransmitRate = $fap_info["date_rate"];
		
		$NetworkAuth = $fap_info["cpe_authtype"];
		$DataEncryption = $fap_info["cpe_encryption"];
		$Passphrase = $fap_info["Wep_Passphrase"];
		$key_num = $fap_info["default_key"];
		$wep_key1 = $fap_info["WepKey1"];
		$wep_key2 = $fap_info["WepKey2"];
		$wep_key3 = $fap_info["WepKey3"];
		$wep_key4 = $fap_info["WepKey4"];
		$wpa_passphrase = $fap_info["wpa_passphrase"];
		
		$query = "
			UPDATE DevTemplet
			SET 
			dev_opemode = 'CPE',
			WirelessNetworkName = '$WirelessNetworkName', 
			lock_AP_MAC = '$lock_AP_MAC',
			WirelessMode = '$WirelessMode',
			TransmitRate = '$TransmitRate',
			NetworkAuth = '$NetworkAuth',
			DataEncryption = '$DataEncryption',
			Passphrase = '$Passphrase',
			key_num = '$key_num',
			wep_key1 = '$wep_key1',
			wep_key2 = '$wep_key2',
			wep_key3 = '$wep_key3',
			wep_key4 = '$wep_key4',
			wpa_passphrase = '$wpa_passphrase'
			WHERE mac_addr = '$mac' ";

		$result = Yii::$app->db->createCommand($query)->execute();

	}
	}catch( \Exception $e){
		                echo json_encode(['message'=>$e->getMessage()]); 
		            }
}
/*function:reportid = 18
*
*/
function process_report_for_fap_json_data($mac,$fap_info)
{	
	$fap_info = json_encode($fap_info);
	
	if(!empty($fap_info))
	{
		$query = "
		UPDATE DevTemplet 
		SET 
		json_data = '$fap_info'
		WHERE mac_addr = \"".$mac."\" ";

		$result = Yii::$app->db->createCommand($query)->execute();
		// if (!(@ mysql_query($query, $dbconn))) {
		// 	showerror($query);
		// }
	}
	
	if(!empty($fap_info)){
		$elem_arr = [];
		//global $elem_arr;
		$json_data = $fap_info;
		$json_arr = json_decode($json_data);
		//parse_json($json_arr);

		foreach($elem_arr as $key => $value)
		{
			$lower = strtolower($elem_arr[0]['id']);//全部转换为小写
	
			$query = "
			UPDATE DevInfo 
			SET 
			root_mac_addr = \"".$lower."\"
			WHERE mac_addr = \"".$value['id']."\" ";

			$result = Yii::$app->db->createCommand($query)->execute();
			// if (!(@ mysql_query($query, $dbconn))) {
			// 	showerror($query);
			// }
			
			$query = "SELECT 
				mac_addr 
			  FROM 
				DevTemplet
			  WHERE mac_addr = \"".$value['id']."\"
	
			";

			$result = Yii::$app->db->createCommand($query)->queryAll();

			// if (!($result = mysql_query($query, $dbconn)))
			// {
			// 	showerror($query);
			// }
			$row = $result[0];
			//$row = mysql_fetch_array($result);
			
			if(!empty($row['mac_addr']))
			{
				$lower = strtolower($elem_arr[0]['id']);
				$query = "
				UPDATE DevTemplet 
				SET 
				root_mac_addr = \"".$lower."\"
				WHERE mac_addr = \"".$value['id']."\" ";

				$result = Yii::$app->db->createCommand($query)->execute();
				
				// if (!(@ mysql_query($query, $dbconn))) {
				// 	showerror($query);
				// }
			}
			
		}
		
	}
}



function process_real_for_sta($mac, $sta)
{
	$model = new StationList_real();
	$date_time = date("Y-m-d H:i:s");
		
	$result = $model->find()->where(['and',['sta_mac'=>$sta['mac']],['dev_mac'=>$mac]])->all();
	

	if($result){
		$row = $result[0];
		$query = "UPDATE StationList_real SET ";
		self::update_stat_sta_onlinetime($sta['mac'], $sta['apmac'], $mac, $date_time);
		
		$last_recent_offline = $row['recent_offline']; 
		//Modified by ljh 2014-10-30 13:07
		//Modified by ljh 2014-10-30 13:07  -> for 'recent_online' after first online
		if( $last_recent_offline != "" )
		{
			$query .= "recent_offline='',";
			$query .= "recent_online=\"".$date_time."\",";
		}
		else
		{	
			//$query_tail .= "last_seen=\"".$date_time."\",";
		}
		
		$query_tail = " WHERE sta_mac=\"".$sta['mac']."\" AND dev_mac=\"".$mac."\"";
		
	} else {
		$query = "INSERT INTO StationList_real SET ";
		
		//Modified by ljh 2014-11-05 12:35
		self::check_set_for_sta_reconn_diff_dev($sta['mac'], $mac, $date_time);
		self::update_stat_sta_onlinetime($sta['mac'],$sta['apmac'], $mac, $date_time);
		
		//Modified by ljh 2014-10-30 9:44  -> for 'first_seen'
		$query .= "first_seen=\"".$date_time."\",";
		//Modified by ljh 2014-10-30 13:07  -> for 'recent_online' when first online
		$query .= "recent_online=\"".$date_time."\",";
		
		$query_tail = "";
	}
	//guilent add tap mac for 'tap reviewer report function'
	$tap_mac =$sta['apmac'];
	$query .= "tap_mac=\"".$tap_mac."\",";
	
	//Modified by ljh 2014-10-30 9:44 -> for 'last_seen'
	$query .= "last_seen=\"".$date_time."\",";
	//如果是新版本通过base64加密，则对其解码并转码 add by taodeyu 2014-01-04
	$b64_flag = (isset($_REQUEST['b64']) && intval($_REQUEST['b64']) === 1) ? true : false;
	$is_b64_dev = self::is_base64_dev();
	if($b64_flag == true || $is_b64_dev == true){
		//Old 
		//$query_sta_hostname = mb_convert_encoding(base64_decode($sta["sta_hostname"]), "UTF-8", "GBK");
		//$query_sta_ssid = mb_convert_encoding(base64_decode($sta["ssid"]), "UTF-8", "GBK");
		
		//Test1, err
		//$query_sta_hostname = base64_decode(str_replace("\r\n","",$sta["sta_hostname"]));
		//$query_sta_ssid = base64_decode(str_replace("\r\n","",$sta["ssid"]));
		
		//Test2, err
		//$query_sta_hostname = $sta["sta_hostname"];
		//$query_sta_ssid = $sta["ssid"];
		
		//Test3,
	/*file_put_contents('/var/www/html/debug/StationList_real.log', date("Y-m-d H:i:s")." sta_hostname= " . $sta["sta_hostname"] ."\r\n", FILE_APPEND);
	file_put_contents('/var/www/html/debug/StationList_real.log', date("Y-m-d H:i:s")." sta_ssid= " . $sta["ssid"] ."\r\n", FILE_APPEND);
	file_put_contents('/var/www/html/debug/StationList_real.log', date("Y-m-d H:i:s")." sta_hostname2= " . check_base64_decode($sta["sta_hostname"]) ."\r\n", FILE_APPEND);
	file_put_contents('/var/www/html/debug/StationList_real.log', date("Y-m-d H:i:s")." sta_ssid2= " . check_base64_decode($sta["ssid"]) ."\r\n", FILE_APPEND);
	file_put_contents('/var/www/html/debug/StationList_real.log', date("Y-m-d H:i:s")." sta_hostname3= " . base64_decode(check_base64_decode($sta["sta_hostname"])) ."\r\n", FILE_APPEND);
	file_put_contents('/var/www/html/debug/StationList_real.log', date("Y-m-d H:i:s")." sta_ssid3= " . base64_decode(check_base64_decode($sta["ssid"])) ."\r\n", FILE_APPEND);
	*/
		$query_sta_hostname = mb_convert_encoding(base64_decode(self::check_base64_decode($sta["sta_hostname"])), "UTF-8", "GBK");
		$query_sta_ssid = mb_convert_encoding(base64_decode(self::check_base64_decode($sta["ssid"])), "UTF-8", "GBK");
	//file_put_contents('/var/www/html/debug/StationList_real.log', date("Y-m-d H:i:s")." query_sta_hostname= " . $query_sta_hostname ."\r\n", FILE_APPEND);
	//file_put_contents('/var/www/html/debug/StationList_real.log', date("Y-m-d H:i:s")." query_sta_ssid= " . $query_sta_ssid ."\r\n", FILE_APPEND);
	}else{
		$query_sta_hostname = $sta["sta_hostname"];
		$query_sta_ssid = $sta["ssid"];
	}
	
	//add ssid by taodeyu 2015-01-09
	$query .="
		sta_rssi=\"".$sta["rssi"]."\",
		sta_mac=\"".$sta["mac"]."\",
		dev_mac=\"".$mac."\",
		ssid=\"".addslashes($query_sta_ssid)."\",
		sta_hostname=\"".addslashes($query_sta_hostname)."\",
		sta_os=\"".$sta["sta_os"]."\",
		online_time=\"".$sta["online_time"]."\",
		rx_bytes=\"".$sta["rx_bytes"]."\",
		tx_bytes=\"".$sta["tx_bytes"]."\"
	";
	
	$query = $query.$query_tail;
	//file_put_contents('./Device.log', date("Y-m-d H:i:s")." query= " . $query ."\r\n", FILE_APPEND);
	Yii::$app->db->createCommand($query)->execute();
}

function process_report_for_sta_bw($dev_mac, $sta_info)
{	$model = new Stat_dev_sta_realtime_bw();
	$datetime = date("Y-m-d H:i:s");
	$result = $model->find()->where(['and',['dev_mac' => $dev_mac],['sta_mac' => $sta_info['mac']]])->all();
	
	//file_put_contents('./Device.log', date("Y-m-d H:i:s")." query= " . $query ."\r\n", FILE_APPEND);	
	// if (!$result ) {
		
	// }

	if ($result){
		// $mdoel->updateAll(array('dev_mac' => $dev_mac,
		// 						'tap_mac' => $sta_info['apmac'],
		// 						'sta_mac' => $sta_info["mac"],
		// 						'tx_bps' => $sta_info["tx_thpt"],
		// 						'total_bps' => $sta_info["tot_thpt"],
		// 						'update_time' => $datetime),'dev_mac=:devmac',array(':devmac'=>$dev_mac))

		$query = "UPDATE  stat_dev_sta_realtime_bw set";
		$query_tail ="where dev_mac=\"".$dev_mac."\" and sta_mac = \"".$sta_info['mac']."\"";
	} else {
		$query = "INSERT INTO  stat_dev_sta_realtime_bw set";
		$query_tail ="";
	}

	$query .= " dev_mac = \"".$dev_mac."\",
				tap_mac = \"".$sta_info["apmac"]."\",
				sta_mac = \"".$sta_info["mac"]."\",
				tx_bps = \"".$sta_info["rx_thpt"]."\",
				rx_bps = \"".$sta_info["tx_thpt"]."\",
				total_bps = \"".$sta_info["tot_thpt"]."\",
				update_time = \"".$datetime."\"
			  ";
	$query .= $query_tail;
	//file_put_contents("./debug.log", "sta stats >>".$query."\r\n", FILE_APPEND);
	//file_put_contents('./Device.log', date("Y-m-d H:i:s")." query= " . $query ."\r\n", FILE_APPEND);	
	Yii::$app->db->createCommand($query)->execute();		
}


function update_stat_sta_onlinetime($sta_mac, $apmac, $dev_mac, $date_time)
{	
	$model = new StationList_real();
	$model2 = new Stat_sta_onlinetime();
	$result = $model->find()->select(['recent_offline','first_seen'])->where(['and',['sta_mac'=>$sta_mac],['dev_mac'=>$dev_mac]])->all();

	// $query = "SELECT recent_offline,first_seen FROM StationList_real WHERE sta_mac='".$sta_mac."' AND dev_mac='".$dev_mac."'";
	// //file_put_contents('./Device.log', date("Y-m-d H:i:s")." query= " . $query ."\r\n", FILE_APPEND);
	// if (!($result = mysql_query($query, $dbconn))) 
	// {
	// 	//file_put_contents('./Device.log', date("Y-m-d H:i:s")." result= " . print_r($result, true) ."\r\n", FILE_APPEND);
	// 	showerror($query);
	// }
	if($result){	
		$row = $result[0];
		//file_put_contents('./Device.log', date("Y-m-d H:i:s")." row= " . print_r($row, true) ."\r\n", FILE_APPEND);
		//echo "sta_mac:".$sta_mac. "apmac:".$apmac." dev_mac:".$dev_mac."  recent_offline:".$row['recent_offline']."</br>";
		if( ($row['recent_offline'] != '') || ($row['first_seen']=='' && $row['recent_offline']=='') ) 
		{
			$model2->sta_mac = $sta_mac;
			$model2->tap_mac =	$apmac;
			$model2->dev_mac = $dev_mac;
			$model2->online_time = $date_time;
			$model2->offline_time = '';
			$model2->save();

			// $query_insert = "INSERT INTO stat_sta_onlinetime SET sta_mac='$sta_mac', tap_mac='$apmac',dev_mac='$dev_mac',online_time='$date_time',offline_time=''";
				
			// //echo "query_insert:".$query_insert."</br>";
			// //file_put_contents('./Device.log', date("Y-m-d H:i:s")." query_insert= " . $query_insert ."\r\n", FILE_APPEND);
			// if (!(@ mysql_query($query_insert, $dbconn))) 
			// 	showerror($query_insert);
		}
	}
}


function check_set_for_sta_reconn_diff_dev($sta_mac, $mac, $date_time)
{			
	$query = "UPDATE StationList_real SET recent_offline='".$date_time."' WHERE sta_mac='".$sta_mac."' AND recent_offline=''";
	
	//echo "query: $query </br>";
	Yii::$app->db->createCommand($query)->execute();
}


function remove_old_sta($sta_mac, $dev_mac)
{
	
	$query = "
		select count(*) as count, min(date) as min from StationList where sta_mac = \"".
		$sta_mac."\" and dev_mac = \"".$dev_mac."\"
	";
	
	$result = Yii::$app->db->createCommand($query)->queryAll();
	
	if ($result) {
		$row = $result[0];
		$count = $row["count"];
		if ($count >= 7) {
			$min = $row["min"];
			$query = "
				delete from StationList where sta_mac = \"".
				$sta_mac."\" and dev_mac = \"".$dev_mac."\" and date = ".$min."
			";
			Yii::$app->db->createCommand($query)->execute();
		}
	}
}


function update_ltefi_list_fap($mac,$ltefi)
{
	
	$ltefi_status = 0;
	$query = "select device_status from DevInfo where mac_addr='$mac'";

	$result = Yii::$app->db->createCommand($query)->queryAll();
	// if (!(@ $result = mysql_query($query, $dbconn))) {
	// 	showerror($query);
	// }
	$ltefi_in_db_arr = array();//当前在数据库里的ltefi_mac

	if($result){
		$row = $result[0];
		$ltefi_status = $row['device_status'];
	}
	

	$query = "
		INSERT INTO 
			ltefi_list
		SET
			ac_mac=\"N/A\",
			ltefi_mac=\"".$ltefi["mac"]."\",
			ltefi_name=\"".$ltefi["name"]."\",
			ltefi_ip=\"".$ltefi["ip"]."\",
			ltefi_status=\"".$ltefi_status."\",
			cardID=0,
			rx_cur=\"".$ltefi["rx_cur"]."\",
			rx_avg=\"".$ltefi["rx_avg"]."\",
			rx_max=\"".$ltefi["rx_max"]."\",
			tx_cur=\"".$ltefi["tx_cur"]."\",
			tx_avg=\"".$ltefi["tx_avg"]."\",
			tx_max=\"".$ltefi["tx_max"]."\",
			templet_name_1=\"N/A\",
			templet_name_2=\"N/A\",
			device_mode=\"N/A\",
			imei=\"".$ltefi["imei"]."\",
			offline=\"0\"
		ON DUPLICATE KEY UPDATE
			ltefi_name=\"".$ltefi["name"]."\",
			ltefi_ip=\"".$ltefi["ip"]."\",
			ltefi_status=\"".$ltefi_status."\",
			cardID=0,
			rx_cur=\"".$ltefi["rx_cur"]."\",
			rx_avg=\"".$ltefi["rx_avg"]."\",
			rx_max=\"".$ltefi["rx_max"]."\",
			tx_cur=\"".$ltefi["tx_cur"]."\",
			tx_avg=\"".$ltefi["tx_avg"]."\",
			tx_max=\"".$ltefi["tx_max"]."\",
			templet_name_1=\"N/A\",
			templet_name_2=\"N/A\",
			device_mode=\"N/A\",
			imei=\"".$ltefi["imei"]."\",
			offline=\"0\"
	";
	$result = Yii::$app->db->createCommand($query)->execute();
	// if (!(@ mysql_query($query, $dbconn))) {
	// 	showerror($query);
	// } 
	
	//echo json_encode(array('ltelist' => true));
}

function set_update_car_info_for_hantek($mac,$ltefi_info)
{
	$total_bytes = self::get_total_bytes($mac);
	$is_dev_exist = self::is_dev_exist($mac);
	//file_put_contents('./Device.log', date("Y-m-d H:i:s")." total_bytes= " . $total_bytes."\r\n", FILE_APPEND);	
	//file_put_contents('./Device.log', date("Y-m-d H:i:s")." is_dev_exist= " . $is_dev_exist."\r\n", FILE_APPEND);	
	if($is_dev_exist)
	{
		$insert_query = "update update_car_info set ";
		$insert_query .= "server_time='" . date("YmdHis") . "', " ;
		$insert_query .= "gps_utc_time='" . $ltefi_info["gps_utc_time"] . "', " ;
		$insert_query .= "gps_utc_time='" . $ltefi_info["gps_utc_time"] . "', " ;
		$insert_query .= "gps_valid='" . $ltefi_info["gps_valid"] . "', " ;
		$insert_query .= "gps_lat='" . $ltefi_info["gps_lat"] . "', " ;
		$insert_query .= "gps_lat_point='" . $ltefi_info["gps_lat_point"] . "', " ;
		$insert_query .= "gps_long='" . $ltefi_info["gps_long"] . "', " ;
		$insert_query .= "gps_long_point='" . $ltefi_info["gps_long_point"] . "', " ;
		$insert_query .= "gps_velocity='" . $ltefi_info["gps_velocity"] . "', " ;
		$insert_query .= "gps_dir='" . $ltefi_info["gps_dir"] . "', " ;
		$insert_query .= "gps_utc_date='" . $ltefi_info["gps_utc_date"] . "', " ;
		$insert_query .= "gps_mag_dec='" . $ltefi_info["gps_mag_dec"] . "', " ;
		$insert_query .= "gps_mag_dir='" . $ltefi_info["gps_mag_dir"] . "', " ;
		$insert_query .= "gps_mode='" . $ltefi_info["gps_mode"] . "', " ;
		$insert_query .= "command='" . $ltefi_info["command"] . "', " ;
		//$insert_query .= "ltefi_mac='" . $ltefi_info["ltefi_mac"] . "', " ;
		$insert_query .= "acc_state='" . $ltefi_info["acc_state"] . "', " ;
		$insert_query .= "ltefi_date='" . $ltefi_info["ltefi_date"] . "', " ;
		$insert_query .= "ltefi_time='" . $ltefi_info["ltefi_time"] . "', " ;
		$insert_query .= "ltefi_conn='" . $ltefi_info["ltefi_conn"] . "', " ;
		$insert_query .= "dio_state='" . $ltefi_info["dio_state"] . "', " ;
		$insert_query .= "oth_v00='" . $ltefi_info["oth_v00"] . "', " ;
		$insert_query .= "oth_v01='" . $total_bytes . "', " ;
		$oth_v02 = '';
		if($ltefi_info["oth_v02"] != 'null')
		{
			$oth_v02 = $ltefi_info["oth_v02"];
		}
		$insert_query .= "oth_v02='" . $oth_v02 . "', " ;
		//$insert_query .= "oth_v02='" . $ltefi_info["oth_v02"] . "', " ;
		$insert_query .= "oth_v03='" . $ltefi_info["oth_v03"] . "', " ;
		$insert_query .= "oth_v04='" . $ltefi_info["oth_v04"] . "', " ;
		$insert_query .= "oth_v05='" . $ltefi_info["oth_v05"] . "', " ;
		$insert_query .= "oth_v06='" . $ltefi_info["oth_v06"] . "', " ;
		$insert_query .= "oth_v07='" . $ltefi_info["oth_v07"] . "', " ;
		$insert_query .= "oth_v08='" . $ltefi_info["oth_v08"] . "', " ;
		$insert_query .= "oth_v09='" . $ltefi_info["oth_v09"] . "'" ;
		
		$insert_query .= " where ltefi_mac='$mac'";

		if(0)
		{
			$datetime = date("Y-m-d H:i:s");
			file_put_contents('./Device.log', $datetime." insert_query= " . $insert_query."\r\n", FILE_APPEND);	
		}

		$result = Yii::$app->db->createCommand($insert_query)->execute();

		// if (!(@ $result = mysql_query($insert_query, $dbconn))) {
		// 	showerror($query);
		// }
	}
	else{
		$insert_query = "INSERT INTO update_car_info (";
		$insert_query .= "server_time, ";
		$insert_query .= "gps_utc_time, ";
		$insert_query .= "gps_valid, ";
		$insert_query .= "gps_lat, ";
		$insert_query .= "gps_lat_point, ";
		$insert_query .= "gps_long, ";
		$insert_query .= "gps_long_point, ";
		$insert_query .= "gps_velocity, ";
		$insert_query .= "gps_dir, ";
		$insert_query .= "gps_utc_date, ";
		$insert_query .= "gps_mag_dec, ";
		$insert_query .= "gps_mag_dir, ";
		$insert_query .= "gps_mode, ";
		$insert_query .= "command, ";
		$insert_query .= "ltefi_mac, ";
		$insert_query .= "acc_state, ";
		$insert_query .= "ltefi_date, ";
		$insert_query .= "ltefi_time, ";
		$insert_query .= "ltefi_conn, ";
		$insert_query .= "dio_state, ";
		$insert_query .= "oth_v00, ";
		$insert_query .= "oth_v01, ";
		$insert_query .= "oth_v02, ";
		$insert_query .= "oth_v03, ";
		$insert_query .= "oth_v04, ";
		$insert_query .= "oth_v05, ";
		$insert_query .= "oth_v06, ";
		$insert_query .= "oth_v07, ";
		$insert_query .= "oth_v08, ";
		$insert_query .= "oth_v09";
		$insert_query .= ") VALUES ";
		
		$insert_query .= "('".date("YmdHis");
		$insert_query .= "','".$ltefi_info["gps_utc_time"];
		$insert_query .= "','".$ltefi_info["gps_valid"];
		$insert_query .= "','".$ltefi_info["gps_lat"];
		$insert_query .= "','".$ltefi_info["gps_lat_point"];
		$insert_query .= "','".$ltefi_info["gps_long"];
		$insert_query .= "','".$ltefi_info["gps_long_point"];
		$insert_query .= "','".$ltefi_info["gps_velocity"];
		$insert_query .= "','".$ltefi_info["gps_dir"];
		$insert_query .= "','".$ltefi_info["gps_utc_date"];
		$insert_query .= "','".$ltefi_info["gps_mag_dec"];
		$insert_query .= "','".$ltefi_info["gps_mag_dir"];
		$insert_query .= "','".$ltefi_info["gps_mode"];
		$insert_query .= "','".$ltefi_info["command"];
		$insert_query .= "','".$ltefi_info["ltefi_mac"];
		$insert_query .= "','".$ltefi_info["acc_state"];
		$insert_query .= "','".$ltefi_info["ltefi_date"];
		$insert_query .= "','".$ltefi_info["ltefi_time"];
		$insert_query .= "','".$ltefi_info["ltefi_conn"];
		$insert_query .= "','".$ltefi_info["dio_state"];
		$insert_query .= "','".$ltefi_info["oth_v00"];
		$insert_query .= "','".$total_bytes;
		$oth_v02 = '';
		if($ltefi_info["oth_v02"] != 'null')
		{
			$oth_v02=$ltefi_info["oth_v02"];
		}
		$insert_query .= "','".oth_v02;
		//$insert_query .= "','".$ltefi_info["oth_v02"];
		$insert_query .= "','".$ltefi_info["oth_v03"];
		$insert_query .= "','".$ltefi_info["oth_v04"];
		$insert_query .= "','".$ltefi_info["oth_v05"];
		$insert_query .= "','".$ltefi_info["oth_v06"];
		$insert_query .= "','".$ltefi_info["oth_v07"];
		$insert_query .= "','".$ltefi_info["oth_v08"];
		$insert_query .= "','".$ltefi_info["oth_v09"];
		$insert_query .= "')";

		if(0)
		{
			$datetime = date("Y-m-d H:i:s");
			file_put_contents('./Device.log', $datetime." insert_query= " . $insert_query."\r\n", FILE_APPEND);	
		}

		$result = Yii::$app->db->createCommand($insert_query)->execute();

		// if (!(@ $result = mysql_query($insert_query, $dbconn))) {
		// 	showerror($query);
		// }
	}
}



function is_base64_dev(){
	$b64_fw_arr = array(
					"AN-1022-A02"		=> "3.1.2.2", 
					"XN-1022"			=> "3.1.2.2", 
					"ZCA-1300"			=> "3.1.2.2", 
					"ZAP-270"			=> "3.1.2.2", 
					"ZAP-221"			=> "3.1.2.2", 
					"ZAP-670"			=> "3.1.2.2", 
					"APi2750D"			=> "3.1.2.2", 
					"APi2750"			=> "3.1.2.2", 
					"ZN-7100-2DHO"		=> "3.1.2.2", 
					"ZN-7100-2HO"		=> "3.1.2.2", 
					"AN-1022-A02"		=> "3.1.2.2", 
					"XN-1022"			=> "3.1.2.2", 
					"ZN-1000-ZBS"		=> "3.1.2.2", 
					"AN_1022"			=> "3.1.2.2",
					"ZAC-1023-5"		=> "1.2.4.3", 
					"ZAC-1023-2"		=> "1.2.4.3", 
					"ZAC-1023-2-9"		=> "1.2.4.3", 
					"ZAC-1023-5-13"		=> "1.2.4.3", 
					"AP_TEST_102"		=> "1.2.4.3",
					"XN-1033"           => "1.0.0.3"
				);
	$modelname = $_GET["modelname"];
	$fwversion = $_GET["fwversion"];
	
	if(isset($b64_fw_arr[$modelname]) && $b64_fw_arr[$modelname] == $fwversion){
		return true;	
	}
	return false;
}

function check_base64_decode($str)
{
	$str1 = "==";
	$str2 = "=";
	
	$str1_len = strpos($str, $str1);
	$str2_len = strpos($str, $str2);
	
	//echo "'==':$str1_len '=':$str2_len </br>";
	if( $str1_len != FALSE && $str1_len != FALSE ) {
		if( $str1_len < $str2_len ) { // check '=='
			return strstr($str, $str1, true).$str1;
		} else if( $str1_len > $str2_len ) { //check '='
			return strstr($str, $str2, true).$str2;
		} else if( $str1_len == $str2_len ) {
			return strstr($str, $str1, true).$str1;
		}
	} else if( $str1_len != FALSE ) {
		return strstr($str, $str1, true).$str1;
	} else if( $str2_len != FALSE ) {
		return strstr($str, $str2, true).$str2;
	} else 
		return $str;
}



function db_create_in_device_php($item_list, $field_name = '') {
    if (empty($item_list)) {
        return $field_name." IN ('') ";
    } else {
        if (!is_array($item_list)) {
            $item_list = explode(',', $item_list);
        }
        $item_list = array_unique($item_list);
        $item_list_tmp = '';
        foreach($item_list AS $item) {
            if ($item !== '') {
                $item_list_tmp .= $item_list_tmp ? ",'$item'": "'$item'";
            }
        }
        if (empty($item_list_tmp)) {
            return $field_name." IN ('') ";
        } else {
            return $field_name.' IN ('.$item_list_tmp.') ';
        }
    }
}


function get_device_info_by_mac($mac){
	$deive_info = array();
	$query = "SELECT * FROM DevInfo WHERE mac_addr='".$mac."'";

	$result = Yii::$app->db->createCommand($query)->queryAll();
	// if (!($result = mysql_query($query, $dbconn))){
	// 	return false;		
	// }	
	//$row = mysql_fetch_array($result);
	$row = $result[0];
	if(!isset($row['group_name'])){
		return false;	
	}
	$deive_info['user_name'] = $row['user_name'];
	$deive_info['management'] = $row['management'];
	$deive_info['group_name'] = $row['group_name'];
	
	return $deive_info;	
	
}


function is_dev_reged($mac){
	$query = "SELECT group_name FROM DevInfo WHERE mac_addr='".$mac."'";
	
	$result = Yii::$app->db->createCommand($query)->queryAll();

	$row = $result[0];
	$fp = fopen("/var/www/ltefi_info.log", "a+");
			fwrite($fp, "mac===".print_r($mac,true)."\r\n");
			fwrite($fp, "\r\n");
			fclose($fp);

	if($row["group_name"]<>""){
		return 1;	
	}

	return 0;	
}


function GetDistance($lat1, $lng1, $lat2, $lng2){
	$EARTH_RADIUS = 6378137;
	$radLat1 = rad($lat1);
	$radLat2 = rad($lat2);
	$a = $radLat1 - $radLat2;
	$b = rad($lng1) - rad($lng2);
	$s = 2 * asin(sqrt(pow(sin($a/2),2) +
	cos($radLat1)*cos($radLat2)*pow(sin($b/2),2)));
	$s = $s *$EARTH_RADIUS;
	$s = round($s * 10000) / 10000;
   return $s;
}



function get_total_bytes($mac)
{
	$cur_date = date("Ymd");

	$query = "select oth_v01 from ltefi_car_info_today where ltefi_mac='$mac' AND ltefi_date='$cur_date'";
	//file_put_contents('./Device.log', date("Y-m-d H:i:s")." query= " . $query."\r\n", FILE_APPEND);	
	
	$result = Yii::$app->db->createCommand($query)->queryAll();

	// if (!(@ $result = mysql_query($query, $dbconn))) {
	// 	return 0;
	// }
	$total_bytes = 0;//ltefi当日总流量

	if($result){
		$row = $result[0];
		$total_bytes += $row['oth_v01'];
	}
	// while($row = mysql_fetch_array($result)){
	// 	$total_bytes += $row['oth_v01'];
	// }
	
	//file_put_contents('./Device.log', date("Y-m-d H:i:s")." total_bytes= " . $total_bytes."\r\n", FILE_APPEND);	

	return $total_bytes;
}



function is_dev_exist($mac){
	$cur_date = date("Ymd");

	$query = "select oth_v01 from update_car_info where ltefi_mac='$mac'";
	//file_put_contents('./Device.log', date("Y-m-d H:i:s")." query= " . $query."\r\n", FILE_APPEND);
		
	$result = Yii::$app->db->createCommand($query)->queryAll();
	// if (!(@ $result = mysql_query($query, $dbconn))) {
	// 	return 0;
	// }
	$total_bytes = 0;//ltefi当日总流量
	if($result){

		return true;
	}
	// if($row = mysql_fetch_array($result)){
	// 	return true;
	// }

	return false;
}




function get_associated_signal_strength($mac,$asso_info){
	
	$time = date("Y-m-d H:i:s");
	foreach($asso_info as $value)
	{
		$ap_mac = $value["ap_mac"];
		$rssi = $value["wds_rssi"];
		$rx = $value["RX"];//µ¥Î»£ºMbit/s
		$tx = $value["TX"];//µ¥Î»£ºMbit/s

		$query = "SELECT id FROM DevRssi WHERE self_mac = \"".$mac."\" and asso_mac = \"".$ap_mac."\" and TIMESTAMPDIFF(second,time,now())<3600";
		$result = Yii::$app->db->createCommand($query)->queryAll();

		if($result){
			$row = $result[0];
		}

		if(isset($row['id']) && $row['id'] ){
			$query = "
			UPDATE DevRssi 
			SET 
			sta_rssi= '$rssi',
			tx_Mbps= '$tx',
			rx_Mbps= '$rx'
			WHERE id = \"".$row['id']."\" ";
		
			$result = Yii::$app->db->createCommand($query)->execute();
		}else{
			$query = 'insert into DevRssi(self_mac,asso_mac,sta_rssi,tx_Mbps,rx_Mbps,time) values('.$mac.','.$ap_mac.','.$rssi.','.$tx.','.$rx.','.$time.')';	
			$result = Yii::$app->db->createCommand($query)->execute();
		}
	}
}




function get_Devtplist($mac,$lan_ip,$stat_assoc_fail,$attack_num,$G2_tx_power,$G5_tx_power){
	/*
	* °´Ã¿Ð¡Ê±¼ÇÂ¼
	*/
	$datetime = date("Y-m-d H:i:s");

	
	$query = "SELECT id FROM DevtpList WHERE mac_addr = \"".$mac."\" and TIMESTAMPDIFF(second,date,now())<3600 ";

	$result = Yii::$app->db->createCommand($query)->queryAll();
	if($result){
       $row = $result[0];
	}
	if(isset($result[0]['id']) && $result[0]['id']){
		$query = "
		UPDATE DevtpList 
		SET 
		tap_ip= '$lan_ip',
		sta_assoc_fail='$stat_assoc_fail',
		attack_num='$attack_num',
		g2_tx_power='$G2_tx_power',
		g5_tx_power='$G5_tx_power'
		WHERE id = \"".$row['id']."\" ";
	
		$result = Yii::$app->db->createCommand($query)->execute();
	}else{
		$query = "
		INSERT INTO 
			DevtpList
		SET 
			mac_addr = '$mac',
			tap_ip= '$lan_ip',
			sta_assoc_fail='$stat_assoc_fail',
			attack_num='$attack_num',
			g2_tx_power='$G2_tx_power',
			g5_tx_power='$G5_tx_power',
			date= '$datetime'
			";
		$result = Yii::$app->db->createCommand($query)->execute();
	}
}


function get_Ethernet_interface_info($mac,$eth_info){
	/*
	* °´Ã¿Ð¡Ê±¼ÇÂ¼
	*/
	$time = date("Y-m-d H:i:s");
	foreach($eth_info as $value){
		$interface_name = $value["interface name"];//ÓÐÏß½Ó¿ÚÃû³Æ
		$RX_packets = $value["RX_packets"];//½ÓÊÕµ½µÄ°üµÄ¸öÊý
		$RX_errors = $value["RX_errors"];//½ÓÊÕµ½µÄ´íÎó°üµÄ¸öÊý
		$RX_dropped = $value["RX_dropped"];//½ÓÊÜÊ±¶ª°üµÄ¸öÊý
		$TX_packets = $value["TX_packets"];//·¢ËÍ°üµÄ¸öÊý
		$TX_errors = $value["TX_errors"];//·¢ËÍ´íÎó°üµÄ¸öÊý
		$TX_dropped = $value["TX_dropped"];//·¢ËÍÊ±¶ª°üµÄ¸öÊý
		
		$query = "SELECT id,rx_packets,rx_errors,rx_dropped,tx_packets,tx_errors,tx_dropped FROM DevEi_info WHERE mac_addr = \"".$mac."\" and interface_name = \"".$interface_name."\" and TIMESTAMPDIFF(second,time,now())<3600";

		$result = Yii::$app->db->createCommand($query)->queryAll();

		if($result){
			$row = $result[0];
		}else{
            $row = '';
		}
		
		if(isset($row['id']) && $row['id']){
	       /* Ã¿´ÎÈ¡Öµ°´ÕÕ¼ÓÈ¨Æ½¾ùÖµÀ´¼ÇÂ¼£¬×îÐÂÊý¾ÝµÄÈ¨ÖØ20%£¬ÀúÊ·¼ÇÂ¼È¨ÖØ80% */
			$weight_mean_rx_packets = round($RX_packets*0.2 + $row['rx_packets']*0.8);
			$weight_mean_rx_errors = round($RX_errors*0.2 + $row['rx_errors']*0.8);
			$weight_mean_rx_dropped = round($RX_dropped*0.2 + $row['rx_dropped']*0.8);
			$weight_mean_tx_packets = round($TX_packets*0.2 + $row['tx_packets']*0.8);
			$weight_mean_tx_errors = round($TX_errors*0.2 + $row['tx_errors']*0.8);
			$weight_mean_tx_dropped = round($TX_dropped*0.2 + $row['tx_dropped']*0.8);
			$query = "
			UPDATE DevEi_info 
			SET 
			rx_packets= '$weight_mean_rx_packets',
			rx_errors= '$weight_mean_rx_errors',
			rx_dropped= '$weight_mean_rx_dropped',
			tx_packets = '$weight_mean_tx_packets',
			tx_errors = '$weight_mean_tx_errors',
			tx_dropped = '$weight_mean_tx_dropped'
			WHERE id = \"".$row['id']."\" ";
		
			$result = Yii::$app->db->createCommand($query)->execute();
		}else{
		
			$query = "
			INSERT INTO 
				DevEi_info
			SET 
				mac_addr= '$mac',
				interface_name= '$interface_name',
				rx_packets= '$RX_packets',
				rx_errors= '$RX_errors',
				rx_dropped= '$RX_dropped',
				tx_packets = '$TX_packets',
				tx_errors = '$TX_errors',
				tx_dropped = '$TX_dropped',
				time = '$time'
				";
			$result = Yii::$app->db->createCommand($query)->execute();
		}
	}
}



function get_ssid_list_around_dev($mac,$n2gSsidListNum,$G2_list_info,$n5gSsidListNum,$G5_list_info){
	/*
	* °´Ã¿Ð¡Ê±¼ÇÂ¼
	*/
	$time = date("Y-m-d H:i:s");
	if($n2gSsidListNum > 0){
		$card_type = "2G";
		foreach($G2_list_info as $value){
			$ap_mac = $value['Address'];
			$ap_ssid = $value['ESSID'];
			$ap_channel = $value['Channel'];
			$ap_signal = $value['Signal'];
			$ap_encry = $value['Encryption'];
			
			$query = 'SELECT id FROM DevArroundSSID WHERE mac_addr ="'.$mac.'" and card_type ="'.$card_type.'" and address ="'.$ap_mac.'" and Essid ="'.$ap_ssid.'" and TIMESTAMPDIFF(second,time,now())<3600';
			$result = Yii::$app->db->createCommand($query)->queryAll();

			if($result){
				$row = $result[0];
			}else{
	            $row = '';
			}
			
			if(isset($row['id']) && $row['id']){
				
				$query = "
				UPDATE DevArroundSSID 
				SET 
				address = '$ap_mac',
				Essid = '$ap_ssid',
				channel = '$ap_channel',
				signal = '$ap_signal',
				encryption = '$ap_encry'
				WHERE id = \"".$row['id']."\" ";
			
				$result = Yii::$app->db->createCommand($query)->execute();
			}else{
				
				//$query = 'insert into DevArroundSSID set mac_addr ="'.$mac.'",card_type="'.$card_type.'",address= "'.$ap_mac.'",Essid="'.$ap_ssid.'",channel="'.$ap_channel.'",signal="'.$ap_signal.'",encryption="'.$ap_encry.'",time="'.$time.'"';
                $devssid = new DevSSID();
                $devssid->mac_addr = $mac;
                $devssid->card_type = $card_type;
                $devssid->address = $ap_mac;
                $devssid->Essid = $ap_ssid;
                $devssid->channel = $ap_channel;
                $devssid->signal = $ap_signal;
                $devssid->encryption = $ap_encry;
                $devssid->time = $time;
                $devssid->save();
			}
			
		}
	}
	
	if($n5gSsidListNum > 0){
		$card_type = "5G";
		foreach($G5_list_info as $value){
			$ap_mac = $value['Address'];
			$ap_ssid = $value['ESSID'];
			$ap_channel = $value['Channel'];
			$ap_signal = $value['Signal'];
			$ap_encry = $value['Encryption'];
			
			$query = "SELECT id FROM DevArroundSSID WHERE mac_addr = \"".$mac."\" and card_type = \"".$card_type."\" and address = \"".$ap_mac."\" and Essid = \"".$ap_ssid."\" and TIMESTAMPDIFF(second,time,now())<3600";
			$result = Yii::$app->db->createCommand($query)->queryAll();

			if($result){
				$row = $result[0];
			}else{
	            $row = '';
			}
			
			if(isset($row['id']) && $row['id']){
				$query = "UPDATE DevArroundSSID SET address = '$ap_mac',Essid = '$ap_ssid',channel = '$ap_channel',signal = '$ap_signal',encryption = '$ap_encry'WHERE id = \"".$row['id']."\" ";
			
				$result = Yii::$app->db->createCommand($query)->execute();
				
			}else{

				$devssid = new DevSSID();
                $devssid->mac_addr = $mac;
                $devssid->card_type = $card_type;
                $devssid->address = $ap_mac;
                $devssid->Essid = $ap_ssid;
                $devssid->channel = $ap_channel;
                $devssid->signal = $ap_signal;
                $devssid->encryption = $ap_encry;
                $devssid->time = $time;
                $devssid->save();
			}
		}
	}
}

}
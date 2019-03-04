<?php
namespace backend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use backend\models\Stat_dev_onlinetime;

class Device extends ActiveRecord
{
    public static function tableName()
    {
        return '{{DevInfo}}';
    }
	
	function addheartbeat($value)
	{
		$model = new Device();
		
		if(!empty($value['mac']))
			$model->mac_addr = $value['mac'];
		if(!empty($value['type']))
			$model->model_type = $value['type'];
		if(!empty($value['modelname']))
			$model->model_name = $value['modelname'];
		if(!empty($value['fwversion']))
			$model->fw_version = $value['fwversion'];
		if(!empty($value['cardID']))
			$model->card_id = $value['cardID'];
		
		if(!$model->save()){
            return array_values($model->getFirstErrors())[0];
        }
		return $model;
	}
	
	function update_devinfo_local($mac){
		$model = new Device();
		$model::updateAll(['local'=> '2'],['mac_addr'=>$mac]);
	}
	
	function update_device_status_2($mac)
	{
		$model = new Device();
		$model::updateAll(['device_status'=> '2'],['mac_addr'=>$mac]);
	}
	
	function update_device_status_1($mac)
	{
		$model = new Device();
		$model::updateAll(['device_status'=> '1'],['mac_addr'=>$mac]);
	}
	
	function select_offline_alarm($mac)
	{
		$model = new Device();
		$query = $model::find()->select(['offline_alarm'])->where(['mac_addr'=>$mac])->all();
		
		$offline_alarm = $query[0]['offline_alarm'];
		//var_dump($offline_alarm);
		//exit;
		return $offline_alarm;
		
		
	}
	
	function check_and_set_online_time($mac)
	{
		$model = new Device();
		$query = $model::find()->select(['recent_offline','first_seen'])->where(['mac_addr'=>$mac])->all();
		
		$recent_offline = $query[0]['recent_offline'];
		$first_seen = $query[0]['first_seen'];
		
		if( ($recent_offline!='') || ($first_seen=='' && $recent_offline=='' && $mac!='') )
		{
			Stat_dev_onlinetime::insert_online_time_offline_time($mac);
		}
		
		//var_dump($first_seen);
		//exit;
	}
	
	function check_and_set_recent_online($mac,$modelname)
	{
		$date_time = date("Y-m-d H:i:s");
		$model = new Device();
		
		$query = $model::find()->where(['mac_addr'=>$mac])->andWhere(['or',['!=','recent_offline',''],['=','first_seen','']])->one(); 
		if(!empty($query)){
			$query->recent_online = $date_time;
			$query->recent_offline = '';
			$query->save();   //保存
		}

		Device::check_and_set_uptime_downtime($mac,$modelname,$date_time);
	}
	
	function check_and_set_first_seen($mac)
	{
		//"UPDATE DevInfo SET first_seen='".$date_time."' WHERE mac_addr='".$dev_mac."' AND first_seen=''";
		$date_time = date("Y-m-d H:i:s");
		$model = new Device();
		$model::updateAll(['first_seen'=>$date_time],['and', ['mac_addr'=>$mac], ['=','first_seen','']]);
		
	}
	
	function update_heartbeat($value){
		if(!isset($value['cardID'])){
        	$value['cardID'] = 0;
		}
		$date_time = date("Y-m-d H:i:s");
		$model = new Device();
		$query = $model::find()->where(['mac_addr'=>$value['mac']])->one();
		if(!empty($query)){
			$query->offline_alarm = 0;
			$query->last_beat = time();
			$query->last_seen = $date_time;
			$query->model_type = $value['type'];
			$query->opemode = 'AP';//只有ZAC-1023-2/5的设备中有这个字段
			$query->model_name = $value['modelname'];
			$query->fw_version = $value['fwversion'];
			$query->card_id = $value['cardID'];
			$query->save();//保存
		}
	}
	
	function update_heartbeat_1023($value)
	{
		$date_time = date("Y-m-d H:i:s");
		$model = new Device();
		$query = $model::find()->where(['mac_addr'=>$value['mac']])->one();
		if(!empty($query)){
			$query->offline_alarm = 0;
			$query->last_beat = time();
			$query->last_seen = $date_time;
			$query->model_type = $value['type'];
			$query->opemode = $value['opemode'];
			$query->model_name = $value['modelname'];
			$query->fw_version = $value['fwversion'];
			$query->card_id = $value['cardID'];
			$query->save();//保存
		}
	}
	
	function add_default_templet_to_TempletFAP($mac){
		
	}
	
	function formRandChar($mac)
	{
		
		$auto_key = self::getRandChar(8);
		
		//"UPDATE DevInfo SET auto_key='$auto_key' WHERE mac_addr='$mac'";
		$model = new Device();
		$model::updateAll(['auto_key'=>$auto_key],['mac_addr'=>$mac]);
		
		return $auto_key;
	}
	
	function getRandChar($length, $lower = false)
	{
		$str = null;
		$strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
		$max = strlen($strPol)-1;
		for($i=0;$i<$length;$i++){
			$str.=$strPol[rand(0,$max)];
		}
		if($lower == true){
			$str = strtolower($str);	
		}
		return $str;
	}
	
	function update_CPE_flag($mac,$CPE_flag)
	{
		$model = new Device();
		$model::updateAll(['CPE_flag'=>$CPE_flag],['mac_addr'=>$mac]);
		
	}
	
	function update_flag($mac,$flag)
	{
		$model = new Device();
		$model::updateAll(['flag'=>$flag],['mac_addr'=>$mac]);
		//"UPDATE DevInfo SET flag='".$flag."' where mac_addr='".$mac."'";
	}
	
	function update_local($mac,$local)
	{
		$model = new Device();
		$model::updateAll(['local'=>$local],['mac_addr'=>$mac]);
		//"UPDATE DevInfo SET local='".$local."' where mac_addr='".$mac."'"
	}
	
	function update_vapinfo_for_ackaction($str_templet,$str_channel,$str_vap_ssid,$str_operation_mode,$str_bridge,$device_mac)
	{
		$model = new Device();
		$query = $model::find()->where(['mac_addr'=>$device_mac])->one();
		
		if(!empty($query)){
			$query->last_templet_name = $str_templet;
			$query->last_channel = $str_channel;
			$query->last_vap_ssid = $str_vap_ssid;
			$query->op_mode = $str_operation_mode;
			$query->bridge_mac = $str_bridge;
			$query->save();//保存
		}
	}
	
	function update_used_portal($portal,$ac_mac)
	{
		$model = new Device();
		$model::updateAll(['used_portal'=>$portal],['mac_addr'=>$ac_mac]);
		
	}
	
}


<?php
namespace backend\models;

use Yii;
use yii\db\ActiveRecord;

class Vpnclient_list extends ActiveRecord
{
	public static function tableName()
    {
        return 'vpnclient_list';//创建一个数据模型链接数据表
    }
	
	function update_dev_vpn_switch($vpnEnable,$mac)
	{
		//"update vpnclient_list set dev_vpn_switch=$vpnEnable where dev_mac='$mac'";
		$model = new Vpnclient_list();
		$model::updateAll(['dev_vpn_switch'=>$vpnEnable],['dev_mac'=>$mac]);
		
	}
	
}


?>
<?php
namespace api\controllers;

use Yii;
use yii\rest\ActiveController;

class Assoc_sta_list extends ActiveController
{
	public static function tableName()
    {
        return 'assoc_sta_list';//创建一个数据模型链接数据表
    }
	
	function process_alert_assoc($mac, $type, $action, $fwversion,$jason_data)
	{
		$time = date('Y-m-d H:i:s');
		$alert_assoc = json_decode($jason_data, true);

		$sta_mac =$alert_assoc["sta_mac"];
		
		$model = new Assoc_sta_list();
		$model->dev_mac = $mac;
		$model->sta_mac = $sta_mac;
		$model->assoc_time = $time;
		$model->save();
		
		return;
	}
	
}



?>
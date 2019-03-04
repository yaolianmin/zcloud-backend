<?php
namespace backend\models;

use Yii;
use yii\db\ActiveRecord;

class Stat_dev_onlinetime extends ActiveRecord
{
	public static function tableName()
    {
        return 'stat_dev_onlinetime';//创建一个数据模型链接数据表
    }
	

	/**
	 * 功能:添加设备的上线时间
	 * 参数：mac 物理地址
	 * 返回：
	 */
	function insert_online_time_offline_time($mac){
		$date_time = date("Y-m-d H:i:s");
		$model = new Stat_dev_onlinetime();
		$model->dev_mac = $mac;
		$model->online_time = $date_time;
		$model->save();
	}
	
	
}



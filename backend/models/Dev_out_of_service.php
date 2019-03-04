<?php
namespace backend\models;

use Yii;
use yii\db\ActiveRecord;

class Dev_out_of_service extends ActiveRecord{
	public static function tableName(){
        return '{{dev_out_of_service}}';//创建一个数据模型链接数据表
    }
	
	function check_dev_service_line($mac){
		$rel = Dev_out_of_service::find()->where(['mac_addr' => $mac])->asArray()->one();
		return $rel;
	}
	
	
}

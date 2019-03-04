<?php
namespace frontend\models;
use Yii;
use yii\db\ActiveRecord;
use frontend\models\Common;

class Dev_user_for_file extends ActiveRecord
{
	public static function tableName(){
    	  return '{{dev_user}}';
    }
	
	function get_user_dev($user){
		$alldev = Dev_user_for_file::find()->select('device_name')->where(['user_name' => $user])->all();
		return $alldev;
	}
	
}


?>
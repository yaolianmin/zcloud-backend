<?php
namespace frontend\models;
use Yii;
use yii\db\ActiveRecord;
use frontend\models\Common;

class File_to_dev extends ActiveRecord
{
	public static function tableName(){
    	  return '{{dev_management}}';
    }
	
	function get_dev_name(){
		
		$alldev_name = File_to_dev::find()->all();
		return $alldev_name;
	}
	
}


?>
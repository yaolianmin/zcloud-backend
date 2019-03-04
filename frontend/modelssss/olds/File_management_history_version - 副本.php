<?php
namespace frontend\models;
use Yii;
use yii\db\ActiveRecord;
use yii\data\Pagination;
use frontend\models\Common;

class File_management_history_version extends ActiveRecord
{
		/*���ݿ��*/
    public static function tableName(){
    	  return '{{file_history_version}}';
    }
	
	function push_sql($firmware_info){
		$_list = File_management_history_version::find();
		if(!empty($firmware_info)){
			$_list->where(['device_name' => $firmware_info]);
		}
		//$_list = $_list->createCommand()->getRawSql();//�����ʵ��sql���
		$_list = $_list->all();
		return $_list;
	}
	
}



?>
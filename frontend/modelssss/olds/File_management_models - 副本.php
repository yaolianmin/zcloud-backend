<?php

namespace frontend\models;
use Yii;
use yii\db\ActiveRecord;
use yii\data\Pagination;
use frontend\models\Common;
use yii\web\UploadedFile;

class File_management_models extends ActiveRecord
{  
	 /*数据库表*/
    public static function tableName(){
    	  return '{{file_management}}';
    }
	
    public function get_file_management_list($search_firmware){
		$_list = self::push_sql($search_firmware);
        $list = $_list
                ->offset(self::get_file_management_page($search_firmware)->offset)
                ->limit(self::get_file_management_page($search_firmware)->limit)
                ->asArray()
                ->all();
        return $list;
    }
    
    public function get_file_management_page($search_firmware){
		$_list = self::push_sql($search_firmware);
		$count = $_list->count();
		$page = new Pagination(['totalCount' =>$count, 'pageSize' => 10]); 
		return $page;
    }
	
	function push_sql($search_firmware){
		$_list = File_management_models::find();
		if(!empty($search_firmware)) {
			$_list->andFilterWhere(['like', 'firmware_name', $search_firmware]);
		}
		/*if(!empty($device_type)) {
			$_list->andFilterWhere(['in', 'device_type', $device_type]);
		}
		if(!empty($card_type)) {
			$_list->andFilterWhere(['in', 'card_type', $card_type]);
		}
		if(!empty($select_application_scenarios)) {
			$_list->andFilterWhere(['in', 'application_scenarios', $select_application_scenarios]);
		}*/
		//$_list = $_list->createCommand()->getRawSql();//输出真实的sql语句
		return $_list;
	}
	
	public function get_device_type($device_type){
		$device_type_val = Array();
		$device_type_new = explode(',',$device_type);
		foreach($device_type_new as $value){
			if($value == Yii::t('yii','AP')){
				$value_new = "AP";
				$device_type_val[] = $value_new;
			}
			if($value == Yii::t('yii','AC')){
				$value_new = "AC";
				$device_type_val[] = $value_new;
			}
			if($value == Yii::t('yii','CPE')){
				$value_new = "CPE";
				$device_type_val[] = $value_new;
			}
		}
		return $device_type_val;
	}
	
	/*public function get_card_type($card_type){
		$card_type_val = Array();
		$card_type_new = explode(',',$card_type);
		foreach($card_type_new as $value){
			if($value == Yii::t('yii','Single Card 2.4G')){
				$value_new = "s24";
				$card_type_val[] = $value_new;
			}
			if($value == Yii::t('yii','Single Card 5.8G')){
				$value_new = "s58";
				$card_type_val[] = $value_new;
			}
			if($value == Yii::t('yii','Double Card 2.4G')){
				$value_new = "d24";
				$card_type_val[] = $value_new;
			}
			if($value == Yii::t('yii','Double Card 5.8G')){
				$value_new = "d58";
				$card_type_val[] = $value_new;
			}
			if($value == Yii::t('yii','Double 2.4G&5.8G')){
				$value_new = "d2458";
				$card_type_val[] = $value_new;
			}
		}
		return $card_type_val;
	}
	
	public function get_application_scenarios($select_application_scenarios){
		$select_application_scenarios_val = Array();
		$select_application_scenarios_new = explode(',',$select_application_scenarios);
		foreach($select_application_scenarios_new as $value){
			if($value == Yii::t('yii','interior')){
				$value_new = "in";
				$select_application_scenarios_val[] = $value_new;
			}
			if($value == Yii::t('yii','outside')){
				$value_new = "out";
				$select_application_scenarios_val[] = $value_new;
			}
			if($value == Yii::t('yii','interior/outside')){
				$value_new = "in_out";
				$select_application_scenarios_val[] = $value_new;
			}
		}
		return $select_application_scenarios_val;
	}*/
	
	
}
<?php

namespace frontend\models;
use Yii;
use yii\db\ActiveRecord;
use yii\data\Pagination;
use frontend\models\Common;
use yii\web\UploadedFile;
use frontend\models\File_management_history_version;
use frontend\models\Dev_user_for_file;
//use yii\web\Session;

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
		$user = Yii::$app->session['user_name'];
		$power = Yii::$app->session['power'];
		//var_dump($user);
		//exit;
		if($power == 1){
			$_list = File_management_models::find();
			if(!empty($search_firmware)) {
				$_list->andFilterWhere(['like', 'device_name', $search_firmware]);
			}
			//$_list = $_list->createCommand()->getRawSql();//输出真实的sql语句
			return $_list;
		}
		else{
			$devs_user = Array();
			$result = Dev_user_for_file::get_user_dev($user);
			if(!empty($result)){
				$_list = File_management_models::find();
				if(!empty($search_firmware)) {
					foreach($result as $key => $value){
						$devs_user[] = $value['device_name'];
					}
					if(in_array($search_firmware,$devs_user)){
						$_list->Where(['device_name' => $search_firmware]);
					}
					else
						return File_management_models::find()->Where(['device_name' => ""]);
				}
				else{
					foreach($result as $key => $value){
						$devs_user[] = $value['device_name'];
						$_list->orFilterWhere(['device_name' => $value['device_name']]);
					}
				}
			//$_list = $_list->createCommand()->getRawSql();
			//var_dump($_list);
			//exit;
				return $_list;
			}
			return File_management_models::find()->Where(['device_name' => ""]);
			//在不满足任何条件下，返回空语句
		}
		
	}
	
	function add_firmware_info($firmwareName,$deviceName,$version){
		$sql_list = File_management_models::find();
		$sql_list->where(['device_name' => $deviceName]);
		$sql_list->andWhere(['version' => $version]);
		$result = $sql_list->all();
		if(!empty($result))
			return $result;
		
		$sql_list2 = File_management_history_version::find();
		$sql_list2->where(['device_name' => $deviceName]);
		$sql_list2->andWhere(['version' => $version]);
		$result2 = $sql_list2->all();
		if(!empty($result2))
			return $result2;
		
		$list_sql = File_management_models::find();
		$list_sql->where(['device_name' => $deviceName]);
		$result2 = $list_sql->one();
		if(!empty($result2)){
			//var_dump($result2['version']);
			//exit;
			$action = new File_management_history_version();
			$action -> firmware_name = $result2['firmware_name'];
			$action -> device_name = $result2['device_name'];
			$action -> version = $result2['version'];
			$action -> dir = $result2['dir'];
			$action -> save();
			
			File_management_models::deleteAll(['device_name' => $result2['device_name']]);
		}
		
		$article = new File_management_models();
		$article -> firmware_name = $firmwareName;
		$article -> device_name = $deviceName;
		$article -> version = $version;
		$article -> save();
	}
	
	function update_dir($dir,$deviceName,$version){
		File_management_models::updateAll(['dir'=>$dir],['device_name'=>$deviceName,'version'=>$version]);
	}
	
	function delete_filedata($deviceName){
		$id = File_management_models::find()->select('id')->where(['device_name' => $deviceName])->one();
		//var_dump($id['id']);
		//exit;
		File_management_models::deleteAll(['id' => $id['id']]);
		
		$allversion = File_management_history_version::find()->select('version')->where(['device_name' => $deviceName])->all();
		//$count = File_management_history_version::find()->select('version')->where(['device_name' => $deviceName])->count();
		$versionarr = Array();
		if(!empty($allversion)){
			foreach($allversion as $key => $value){
				$versionarr[] = $value['version'];
			}
			$pos = array_search(max($versionarr), $versionarr);
			$tbverison = $versionarr[$pos];
			$tbfile = File_management_history_version::find()->select('id,firmware_name,device_name,version,dir')->where(['device_name' => $deviceName,'version' => $tbverison])->one();
			
			$article = new File_management_models();
			$article -> firmware_name = $tbfile['firmware_name'];
			$article -> device_name = $tbfile['device_name'];
			$article -> version = $tbfile['version'];
			$article -> dir = $tbfile['dir'];
			$article -> save();
			
			File_management_history_version::deleteAll(['id' => $tbfile['id']]);
		}
			
		//var_dump($tbfile['dir']);
		//exit;
	}
	
	
	
}
<?php
namespace frontend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class Device extends ActiveRecord
{
    public static function tableName()
    {
        return 'DevInfo';//创建一个数据模型链接数据表
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
	
	function update_devinfo_local($value)
	{
		$model = new Device();
		$model::updateAll(['local'=> '2'],['mac_addr'=>$value]);
	}
	
	function check_dev_service_line($value)
	{
		$model = new Device();
		$count = $model::find()->where(['mac_addr' => $value])->count();
		return $count;
	}
	
}

?>
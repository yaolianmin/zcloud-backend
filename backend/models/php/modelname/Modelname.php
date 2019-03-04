<?php
namespace backend\models\php\modelname;

use Yii;
use yii\db\ActiveRecord;


class Modelname extends ActiveRecord{	



	function get_dev_model_name($model_name){
		$model_name_dev_1023_CPE_Bridge = array(
				"ZAC-1023-2-9",
				"ZAC-1023-5-13",
				"ZCOM_1027L_O",
				"AS220-C01",
				"SP220-C01",
				"SP220-C02"
				);
				
		
		foreach( $model_name_dev_1023_CPE_Bridge as $value ) {
			if( $model_name == $value ) {
				return $model_name;
			}else
				return "NB";
		}
	}
	
	
}



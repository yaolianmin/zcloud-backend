<?php
namespace backend\models;

use yii\db\ActiveRecord;

class Dev_card_id extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%dev_card_id}}';//创建一个数据模型链接数据表
    }

    public function rules()  
    {  
        // NOTE: you should only define rules for those attributes that  
        // will receive user inputs.  
        return array(  
            // more code...  
            array(['model_name','basic_platform','brand','uploadFileName','template_path'], 'safe'), //Modify the fields in here  
            // more code...  
        );  
    }

	function get_url($model_name)
	{
		$model = new Dev_card_id();
		$query = $model::find()->select(['template_path'])->where(['model_name'=>$model_name])->all();
		
		$template_path = $query[0]['template_path'];
		
		return $template_path;
	}

}
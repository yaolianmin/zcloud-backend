<?php
namespace backend\models;

use yii\db\ActiveRecord;

class Model_test extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%model_test}}';//创建一个数据模型链接数据表
    }

    public function rules()  
    {  
        // NOTE: you should only define rules for those attributes that  
        // will receive user inputs.  
        return array(  
            // more code...  
            array(['model_name','basic_platform','brand','template','uploadFileName'], 'safe'), //Modify the fields in here  
            // more code...  
        );  
    }  


    
}
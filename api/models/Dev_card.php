<?php
namespace api\models;

use yii\db\ActiveRecord;

class Dev_card extends ActiveRecord{


    public static function tableName(){
        return '{{dev_card_id}}';//创建一个数据模型链接数据表
    }


}
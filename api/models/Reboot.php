<?php

namespace api\models;


use yii\db\ActiveRecord;

class Reboot extends ActiveRecord{

	//创建一个数据模型链接数据表
    public static function tableName(){
        return '{{reboot}}';
    }
}
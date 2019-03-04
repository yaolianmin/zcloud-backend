<?php
namespace api\models;

use yii\db\ActiveRecord;

class Vpn_list extends ActiveRecord{


    public static function tableName(){
        return '{{vpnclient_list}}';//创建一个数据模型链接数据表
    }




    
}
<?php
namespace backend\models;

use yii\db\ActiveRecord;

class StationList_real extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%StationList_real}}';//创建一个数据模型链接数据表
    }

}
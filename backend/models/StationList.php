<?php
namespace backend\models;

use yii\db\ActiveRecord;

class StationList extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%StationList}}';//创建一个数据模型链接数据表
    }

}
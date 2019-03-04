<?php
namespace backend\models;

use yii\db\ActiveRecord;

class Stat_sta_onlinetime extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%stat_sta_onlinetime}}';//创建一个数据模型链接数据表
    }

}
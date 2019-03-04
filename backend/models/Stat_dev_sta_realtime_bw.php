<?php
namespace backend\models;

use yii\db\ActiveRecord;

class Stat_dev_sta_realtime_bw extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%stat_dev_sta_realtime_bw}}';//创建一个数据模型链接数据表
    }

}
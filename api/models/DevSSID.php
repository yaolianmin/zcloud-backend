<?php

namespace api\models;

use yii\db\ActiveRecord;

class DevSSID extends ActiveRecord
{
    public static function tableName()
    {
        return '{{DevArroundSSID}}';//创建一个数据模型链接数据表
    }
}
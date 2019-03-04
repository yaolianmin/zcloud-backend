<?php

namespace frontend\models;

use yii\db\ActiveRecord;

class Book extends ActiveRecord
{
    public static function tableName()
    {
        return 'book';//创建一个数据模型链接数据表
    }
}
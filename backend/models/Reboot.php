<?php
namespace backend\models;

use Yii;
use yii\db\ActiveRecord;

class Reboot extends ActiveRecord
{
	public static function tableName()
    {
        return 'reboot';//创建一个数据模型链接数据表
    }

}
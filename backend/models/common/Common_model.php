<?php
namespace backend\models\common;

use Yii;
use yii\db\ActiveRecord;

class Common_model extends ActiveRecord {
    
    public static $tableName;

    public static function tableName()
    {
        return self::$tableName;
    }

    public static function find_model($tableName) 
    {
        self::$tableName = $tableName;
    }
}
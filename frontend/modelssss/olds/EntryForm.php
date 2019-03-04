<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;

class EntryForm extends Model
{
    public $name;
    public $email;
    
    public function rules()
    {
       return [
          [['name', 'email'], 'required'],
          ['email', 'email'],
       ];
    }
}

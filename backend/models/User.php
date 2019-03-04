<?php
namespace backend\models;

use Yii;
use yii\db\ActiveRecord;

class User extends ActiveRecord
{
	public static function tableName()
    {
        return 'user';//创建一个数据模型链接数据表
    }
	
	//获取邮件地址
	function get_email_addr($user_name)
	{
		$model = new User();
		$query = $model::find()->select(['email'])->where(['UserName'=>$user_name])->all();
		
		if(!empty($query[0]['email'])) {
			$email = $query[0]['email'];
			return $email;
		}
		return '';
	}
	
	
}


?>
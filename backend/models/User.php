<?php
namespace backend\models;

use Yii;
use yii\db\ActiveRecord;

class User extends ActiveRecord
{
	public static function tableName()
    {
        return 'user';//����һ������ģ���������ݱ�
    }
	
	//��ȡ�ʼ���ַ
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
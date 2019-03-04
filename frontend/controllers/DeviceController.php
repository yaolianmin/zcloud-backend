<?php
namespace frontend\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\QueryParamAuth;
use yii\data\ActiveDataProvider;
use yii\web\Response;

class DeviceController extends ActiveController
{
    public $modelClass = 'frontend\models\Device';//ָ���������������ʱ�����ĸ�����ģ��
	
	public function behaviors()
	{
		$behaviors = parent::behaviors();
		$behaviors['contentNegotiator']['formats'] = ['application/json' => Response::FORMAT_JSON];
		
		return $behaviors;
	}
	
	public function actions()
    {
        $actions = parent::actions();
        // ע��ϵͳ�Դ���ʵ�ַ���
        unset($actions['index'], $actions['update'], $actions['create'], $actions['delete'], $actions['view']);
        return $actions;
    }
	
	public function actionDevice()
    {
        $model = new $this->modelClass();
		$value = Yii::$app->request->get();
		$model->update_devinfo_local($value['mac']);//Ϊ����1023Bridgeģʽ�µĽ�Զ�˶����ڵı�־λ��0Զ�ˣ�1���ˣ�2�����豸
		
		$count = $model->check_dev_service_line($value['mac']);
		var_dump($count);
		exit;
	/*$fp = fopen("/var/www/action_info.log", "a+");
	fwrite($fp, "mac === ".$value['mac']."\r\n");
	fwrite($fp, "\r\n");
	fclose($fp);*/
	
		$result = $model->addheartbeat($value);
		//exit;
        return $result;
    }
	
}
?>

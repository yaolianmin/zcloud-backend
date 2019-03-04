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
    public $modelClass = 'frontend\models\Device';//指定调用这个控制器时链接哪个数据模型
	
	public function behaviors()
	{
		$behaviors = parent::behaviors();
		$behaviors['contentNegotiator']['formats'] = ['application/json' => Response::FORMAT_JSON];
		
		return $behaviors;
	}
	
	public function actions()
    {
        $actions = parent::actions();
        // 注销系统自带的实现方法
        unset($actions['index'], $actions['update'], $actions['create'], $actions['delete'], $actions['view']);
        return $actions;
    }
	
	public function actionDevice()
    {
        $model = new $this->modelClass();
		$value = Yii::$app->request->get();
		$model->update_devinfo_local($value['mac']);//为区分1023Bridge模式下的近远端而存在的标志位，0远端，1近端，2其它设备
		
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

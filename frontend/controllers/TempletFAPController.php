<?php

namespace frontend\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\QueryParamAuth;
use yii\data\ActiveDataProvider;
use yii\web\Response;

class TempletFAPController extends ActiveController
{
    public $modelClass = 'frontend\models\TempletFAP';//指定调用这个控制器时链接哪个数据模型
	
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
	
	public function actionAddtempletfap()
    {
        $model = new $this->modelClass();
		
		//$value = Yii::$app->request->get();
		//var_dump($value['mac']);
		//exit;
		
		$value = Yii::$app->request->post();
		//var_dump($value['model_name']);
		//exit;
		$result = $model->addtemplet($value);
		
        return $result;
    }
	
	public function actionDeletetempletfap()
    {
		$modelClass = $this->modelClass;
		$value = Yii::$app->request->post();
		//var_dump($value['model_name']);
		//exit;
        $query = $modelClass::find()->select(['id']);
		if(!empty($value['model_name'])) {
			$query->andFilterWhere(['model_name' => $value['model_name']]);
		}
		$query = $query->all();
		//$query = $query->createCommand()->getRawSql();//输出真实的sql语句
		//var_dump($query[0]['id']);
		//exit;
		$id = $query[0]['id'];
		$model = new $this->modelClass();
		$value = Yii::$app->request->post();
		return $model->deleteAll(['id' => $id]);
    }
	
	public function actionUpdatetempletfap()
    {
        $modelClass = $this->modelClass;
		$value = Yii::$app->request->post();
		//var_dump($value['model_name']);
		//exit;
        $query = $modelClass::find()->select(['id']);
		if(!empty($value['model_name'])) {
			$query->andFilterWhere(['model_name' => $value['model_name']]);
		}
		$query = $query->all();
		//$query = $query->createCommand()->getRawSql();//输出真实的sql语句
		//var_dump($query);
		//exit;
		$id = $query[0]['id'];
		
		$model = $modelClass::updateAll(['mac_addr'=> '00:11:22:33:44'],['id'=>$id]);
		
		//print_r($model);
		//exit;
		return "success";
    }
	
	public function actionSearchtempletfap()
    {
        $modelClass = $this->modelClass;
		$value = Yii::$app->request->post();
        $query = $modelClass::find();
		$query->andFilterWhere(['model_name' => $value['model_name']]);
		$query->andFilterWhere(['templet_name' => $value['templet_name']]);
        return new ActiveDataProvider([
            'query' => $query
        ]);
    }
	
}
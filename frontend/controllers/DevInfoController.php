<?php
namespace frontend\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\web\Response;
use common\error\Error;
use yii\web\NotFoundHttpException;
use yii\web\HttpException;

//use frontend\models\ErrorMsgFrom;
class DevInfoController extends ActiveController
{
    public $modelClass = 'frontend\models\DevInfo';//指定调用这个控制器时链接哪个数据模型

 //    public function behaviors()
	// {
	// 	$behaviors = parent::behaviors();
	// 	$behaviors['contentNegotiator']['formats'] = ['application/json' => Response::FORMAT_JSON];

	// 	//解决ajax跨域请求
	// 	$headers = Yii::$app->response->headers;
 //    	$headers->add('Access-Control-Allow-Origin', '*');
 //    	$headers->add('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
 //    	$headers->add('Access-Control-Allow-Headers', 'Content-Type');
		
	// 	return $behaviors;
	// }

    public function actions()
    {
	    $actions = parent::actions();
	    //注销系统自带的实现方法
	    unset($actions['index'], $actions['update'], $actions['create'], $actions['delete'], $actions['view']);
	   	return $actions;
     }



    public function actionAdd_newdev()
    {
    	$model = new $this->modelClass();
    	$query = $model::find();
    	$count = $query->where(['mac_addr' => Yii::$app->request->post('mac_addr')])->count();
    	if($count)
    	{
    		echo "该设备已经存在，添加失败";
    	}else{
	    		$model->load(Yii::$app->getRequest()->getBodyParams(), '');
	    		$model->attributes = Yii::$app->request->post();
				//var_dump($model->attributes);
	    		if(!$model->save())
	    		{
	    			echo "保存数据失败";
	    		}
    	}
    	return $model;
    	
    	// $model = new $this->modelClass();
    	// $model->load(Yii::$app->getRequest()->getBodyParams(), '');
    	// $model->attributes = Yii::$app->request->post();
    	//  if (! $model->save()) {
     //        return array_values($model->getFirstErrors())[0];
     //    }
     //    return $model;

    	
    }



    public function actionDelete_dev()
    {
    	$model = new $this->modelClass();
    	$count = $model->deleteAll('mac_addr=:mac',array(':mac'=>Yii::$app->request->post('mac_addr')));
    	if($count > 0){
    		echo "删除设备成功";
    	}else{
    		echo "删除设备失败";
    	}

    }


   
    public function actionUpdate_devinfo()
    {
    	$model = new $this->modelClass();
    	$count = $model->updateAll(array('dev_name' => Yii::$app->request->post('dev_name'),
    									'model_name' => Yii::$app->request->post('model_name'),
    									'model_type' => Yii::$app->request->post('model_type'),
    									'card_id' => Yii::$app->request->post('card_id'),
    									'device_status' => Yii::$app->request->post('device_status'),
    									'user_name' => Yii::$app->request->post('user_name'),
    									'management' => Yii::$app->request->post('management'),
    									'group_name' => Yii::$app->request->post('group_name'),
    									'beat_interval' => Yii::$app->request->post('beat_interval'),
    									'last_beat' => Yii::$app->request->post('last_beat'),
    									'terminal_number' => Yii::$app->request->post('terminal_number'),
    									'pos_X'  => Yii::$app->request->post('pos_X'),
    									'pox_Y' => Yii::$app->request->post('pox_Y'),
    									'templet'  => Yii::$app->request->post('templet'),
    									'templet_update'  => Yii::$app->request->post('templet_update'),
    									'term_of_Service'  => Yii::$app->request->post('term_of_Service'),
    									'hw_version'  => Yii::$app->request->post('hw_version'),
    									'fw_version'  => Yii::$app->request->post('fw_version'),
    									'offline_alarm'  => Yii::$app->request->post('offline_alarm'),
    									'timestamp' => Yii::$app->request->post('timestamp'),
    									'dragged_time'  => Yii::$app->request->post('stampdragged_time'),
    									'first_seen'  => Yii::$app->request->post('first_seen'),
    									'last_seen'  => Yii::$app->request->post('last_seen'),
    									'recent_online'  => Yii::$app->request->post('recent_online'),
    									'recent_offline'  => Yii::$app->request->post('recent_offline'),
    									'fw_path'  => Yii::$app->request->post('fw_path'),
    									'dev_info1'  => Yii::$app->request->post('dev_info1'),
    									'dev_info2'  => Yii::$app->request->post('dev_info2'),
    									'service_start'  => Yii::$app->request->post('service_start'),
    									'service_end'  => Yii::$app->request->post('service_end'),
    									'expire_email'  => Yii::$app->request->post('expire_email'),
    									'last_templet_name'  => Yii::$app->request->post('last_templet_name'),
    									'last_channel'  => Yii::$app->request->post('last_channel'),
    									'last_vap_ssid'  => Yii::$app->request->post('last_vap_ssid'),
    									'op_mode'  => Yii::$app->request->post('op_mode'),
    									'bridge_mac'  => Yii::$app->request->post('bridge_mac'),
    									'auto_key'  => Yii::$app->request->post('auto_key'),
    									'used_portal'  => Yii::$app->request->post('used_portal'),
    									'CPE_flag'  => Yii::$app->request->post('CPE_flag')    									
            							),
    	'mac_addr=:mac',array(':mac'=>Yii::$app->request->post('mac_addr')));

    	if($count > 0)
    	{
    		$tisi = "更新成功";
    	}else{
    			$tisi = "更新失败";
			}
			return $tisi;
    }


 	public function actionView_devinfo()
    {	
    	$model = new $this->modelClass();
    	$query = $model::find();

    	if(Yii::$app->request->get('dev_name'))
    	{
    		$query ->andFilterWhere(['dev_name'=>Yii::$app->request->get('dev_name')]);
    	}

    	if(Yii::$app->request->get('model_name'))
    	{
    		$query ->andFilterWhere(['model_name'=>Yii::$app->request->get('model_name')]);
    	}

    	if(Yii::$app->request->get('mac_addr'))
    	{
    		$query ->andFilterWhere(['mac_addr'=>Yii::$app->request->get('mac_addr')]);
    	}

    	try{
    		$count =  $query->count();
    	}
    	catch(ExceptionNew $e){

    		throw new HttpException(500, 'file not exist', 10001);
    	}

    	try{

    		$_data = $query->offset((Yii::$app->request->get('_page')-1)*10)->limit(10)->all();
    	}
    	catch(ExceptionNew $e){

    		throw new HttpException(500, 'file not exist', 10001);
    	}



    	return array (	"totalCount" => $count,
    					"data" => $_data,
    				);






    	//throw new NotFoundHttpException('The requested page does not exist.');
    	//throw new HttpException(500, 'file not exist', 10001);
    	//$result = Error::Info(Yii::t('yii','Not exist'),'dev_info/view_devinfo');

  //   	$result = array (
		// "error_message" => Error::Info('','dev_info/view_devinfo'),
		// "data" => $query->all(),);

  //   	//$result = $query->all();
  	//return $result;
  		// if($query->all())


    	// return new ActiveDataProvider([
     //        'query' => $query
     //    ]);

    }



















    // public function actionSearch(){
    // 	echo "this is book new action serach";
    // 	exit;

    // }


    // public function actionIndex()
    // {	echo "this is new index";
   	//  	exit;
    // 	$modelClass = $this->modelClass;
    // 	$query = $modelClass::find()->all();
    // 	var_dump($query);
    //     echo "this is book action index writed by myself";
    // 	exit;
    //     $modelClass = $this->modelClass;
    //     $query = $modelClass::find();
    //     return new ActiveDataProvider([
    //         'query' => $query
    //     ]);
    // }

    // public function actionCreate()
    // {
   		
    //     $model = new $this->modelClass();
    //     // $model->load(Yii::$app->getRequest()
    //     // ->getBodyParams(), '');
    //     $model->attributes = Yii::$app->request->post();
    //     var_dump(Yii::$app->request->post());
   	// 	exit;
    //     //var_dump(Yii::$app->request->post());
    //     if (! $model->save()) {
    //         return array_values($model->getFirstErrors())[0];
    //     }
    //     return $model;
    // }

    // public function actionUpdate($id)
    // {
        
    // 	var_dump(Yii::$app->request->post());
    // 	exit;
    //     $model = $this->findModel($id);
    //     $model->attributes = Yii::$app->request->post();
    //     if (! $model->save()) {
    //         return array_values($model->getFirstErrors())[0];
    //     }
    //     return $model;
    // }

    // public function actionDelete($id)
    // {
    //     return $this->findModel($id)->delete();
    // }

    // public function actionView($id)
    // {
    //     return $this->findModel($id);
    // }

    // protected function findModel($id)
    // {
    //     $modelClass = $this->modelClass;
    //     if (($model = $modelClass::findOne($id)) !== null) {
    //         return $model;
    //     } else {
    //         throw new NotFoundHttpException('The requested page does not exist.');
    //     }
    // }

    // public function checkAccess($action, $model = null, $params = [])
    // {
    //     // 检查用户能否访问 $action 和 $model
    //     // 访问被拒绝应抛出ForbiddenHttpException
    //     // var_dump($params);exit;
    // }





}
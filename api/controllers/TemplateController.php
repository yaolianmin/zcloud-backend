<?php
namespace api\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\QueryParamAuth;
use yii\data\ActiveDataProvider;
use yii\web\Response;

use backend\models\common\Common_model;
use backend\models\Dev_card_id;

class TemplateController extends ActiveController
{
    public $modelClass = '';//����Ҫ�ӣ����̳���yii\rest\ActiveController,
	
	public function actions()
    {
        $actions = parent::actions();
        // ע��ϵͳ�Դ���ʵ�ַ���
        unset($actions['index'], $actions['update'], $actions['create'], $actions['delete'], $actions['view']);
        return $actions;
    }
	
	public function actionIndex()
	{
		$value = Yii::$app->request->get();
		$_model = new Common_model();
        $_model->find_model($value['model_name']);
        $query = $_model->find();

        //$_data = $query->where(['model_name'=>$value['model_name']])->all();
		$_data = $query->where(['model_name'=>$value['model_name']])->offset(( $value['_page']-1)*$value['_limit'])->limit($value['_limit'])->all();
        $_count = intval($query->count());//�ַ���ת���Σ����ǰ�˱������͵Ķ���
        
        return array (  "count" => $_count,
                        "body" => $_data,
                    );
	}

	public function actionEdit_template()
	{
		$value = Yii::$app->request->post();
		//var_dump($value);
		$cardid = $value['cardid'];
		$cardnum = $value['cardnum'];
		
		$_model = new Common_model();
		$_model->find_model($value['model_name']);
		$query = $_model->find();
		
		foreach ($value as $key => $val){
			if($key !== 'model_name' && $key !== 'template_name' && $key !== 'cardid' && $key !== 'cardnum'){
				$result = $query->select([$key])->where(['model_name'=>$value['model_name']])->andWhere(['template_name'=>$value['template_name']])->all();
				$key_res = $result[0][$key];
				$result_arr = explode(",",$key_res);//�ָ��ַ���Ϊ����
				//var_dump($result_arr);
				if($cardnum == "2"){//˫���豸
					if($cardid == '0')
						$key_edit = $val.','.$result_arr[1];
					else if($cardid == '1')
						$key_edit = $result_arr[0].','.$val;
				}else if($cardnum == "1"){//�����豸
					$key_edit = $val;
				}
				//var_dump($key_edit);
				//exit;
				$_model::updateAll([$key=>$key_edit],['and', ['model_name'=>$value['model_name']], ['template_name'=>$value['template_name']]]);
				
			}
			
		}
		return true;
		
	}
	
	public function actionAdd_template()
	{
		$value = Yii::$app->request->post();
		//var_dump($value);
		$cardid = $value['cardid'];
		$cardnum = $value['cardnum'];
		
		$_model = new Common_model();
		$_model->find_model($value['model_name']);
		
		foreach ($value as $key => $val){
			//model_name �� template_name����Ҫ ���ŷָ�
			if($key !== 'cardid' && $key !== 'cardnum'){
				if($key == 'model_name' || $key == 'template_name'){
					$_model->$key = $val;
				}
				if($cardnum == "2"){//˫���豸
					//��ǰ̨����ֵ�����ݿ�1 ��2�����Ÿ���
					foreach ($value as $_key => $_val){
						if($key.'1' == $_key){
							$vals = $val.','.$_val;
							$_model->$key = $vals;
						}
						else if($key.'0' == $_key){
							$vals = $_val.','.$val;
							$_model->$key = $vals;
						}
					}
				}else if($cardnum == "1"){//�����豸
					$_model->$key = $val;
				}
				
				$_model->save();
			}
		}
		return true;
	}
	
	public function actionEdit_vaptemplate()
	{
		$value = Yii::$app->request->post();
		$vap_num = $value['vap_num'];
		
		$_model = new Common_model();
		$_model->find_model($value['model_name']);
		$query = $_model->find();
		
		foreach ($value as $key => $val) {
			//var_dump(" key ------- ".$key);
			//var_dump(" val ------- ".$val);
			if($key !== 'model_name' && $key !== 'template_name' && $key !== 'cardid' && $key !== 'vap_num'){
				$result = $query->select([$key])->where(['model_name'=>$value['model_name']])->andWhere(['template_name'=>$value['template_name']])->all();
				$key_res = $result[0][$key];
				$result_arr = explode(",",$key_res);//�ָ��ַ���Ϊ����
				//var_dump($result_arr);
				$result_arr[$vap_num] = $val;
				//var_dump($result_arr);
				$result_edit = implode(",",$result_arr);
				//var_dump($result_edit);
				$_model::updateAll([$key=>$result_edit],['and', ['model_name'=>$value['model_name']], ['template_name'=>$value['template_name']]]);	
			}
		}
		return true;
		
	}
	
	public function actionDelete($id)
    {
		$value = Yii::$app->request->get();
		$_model = new Common_model();
		$_model->find_model($value['model_name']);
		$_model->deleteAll(['id'=>$id]);
		
		return true;
    }
	
	public function actionGet_templatedata()
	{
		$value = Yii::$app->request->get();
		$model_name = $value['model_name'];
		
		$template_path = Dev_card_id::get_url($model_name);
		$desc_path = $template_path."/desc.txt";
		$contentString = file_get_contents($desc_path);
		$contentArr = explode(",",$contentString);
		
		$viewData_path = $template_path.$contentArr[1];
		//var_dump($viewData_path);
		
        $json_data = file_get_contents($viewData_path);

        $json_arr = json_decode($json_data);
		//var_dump($json_arr);
		return $json_arr;
		
	}
	
	public function actionGet_tap_templatedata()
	{
		$value = Yii::$app->request->get();
		
		
	}
	
}


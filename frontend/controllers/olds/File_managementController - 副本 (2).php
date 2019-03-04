<?php

namespace frontend\controllers;
use Yii;
use yii\web\Controller;
use yii\base\Model;
use frontend\models\File_management_models;
use frontend\models\File_management_history_version;
use frontend\models\Common;
use yii\data\Pagination;
use yii\web\UploadedFile;
use frontend\models\UploadForm;


class File_managementController extends Controller{
	
	public $enableCsrfValidation = false;//post传值时 关闭csrf验证功能
    
	public function actionFile_management()  
    {
		//判断是否是语言切换
		$request = Yii::$app->request;
		
        if($request->isGet){
            $language = $request->get('lang');
			
            if($language){
                $result = Common::change_language($language);
                if($result == 'success'){
                    return $this->redirect(['file_management/file_management']);
                } 
            }  
        }
		
		if(Yii::$app->request->isGet){
			$search_firmware = Yii::$app->request->get('search_firmware');
			///$device_type = Yii::$app->request->get('select_device_type');
			//$card_type = Yii::$app->request->get('select_card_type');
			//$select_application_scenarios = Yii::$app->request->get('select_application_scenarios');
			
			//$device_type = File_management_models::get_device_type($device_type);
			//$card_type_result = File_management_models::get_card_type($card_type);
			//$select_application_scenarios_result = File_management_models::get_application_scenarios($select_application_scenarios);
			
			//$page = File_management_models::get_file_management_page($search_firmware,$device_type,$card_type_result,$select_application_scenarios_result);
			//$list = File_management_models::get_file_management_list($search_firmware,$device_type,$card_type_result,$select_application_scenarios_result);
			$page = File_management_models::get_file_management_page($search_firmware);
			$list = File_management_models::get_file_management_list($search_firmware);
		}
		
		
		$filename = new UploadForm();
        if (Yii::$app->request->isPost) {
			//\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $filename->file = UploadedFile::getInstance($filename, 'file');

            if ($filename->file && $filename->validate()) {         
                $filename->file->saveAs('/var/www/uploadfile/' . $filename->file->baseName . '.' . $filename->file->extension);
            }
			$page = File_management_models::get_file_management_page("","","","");
			$list = File_management_models::get_file_management_list("","","","");
        }
		
		return $this->render('file_management',[
				"page" => $page,
				"list" => $list,
				"filename" => $filename
		]);
		
    }
	
	public function actionHistory_version(){
		$request = Yii::$app->request;
		if ($request->isPost) {
			 \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
			 $data = $request->post();
			 $firmware_info= $data['firmware_info'];
			 $history_info = File_management_history_version::push_sql($firmware_info);
			 
			 return ["history_info" => $history_info];
			
		 }
		return $this->render('history_version');
	}
	
}  

?>
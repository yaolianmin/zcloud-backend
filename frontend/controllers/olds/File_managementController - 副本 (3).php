<?php

namespace frontend\controllers;
use Yii;
use yii\web\Controller;
use yii\base\Model;
use frontend\models\File_management_models;
use frontend\models\File_management_history_version;
use frontend\models\File_to_dev;
use frontend\models\Common;
use yii\data\Pagination;
use yii\web\UploadedFile;
use frontend\models\UploadForm;


class File_managementController extends Controller{

	public $enableCsrfValidation = false;//post传值时 关闭csrf验证功能
    
	public function actionFile_management()  
    {	
		$dev_name = new File_to_dev();
		$filename = new UploadForm();
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
			$page = File_management_models::get_file_management_page($search_firmware);
			$list = File_management_models::get_file_management_list($search_firmware);
			
			$uploadfile = Yii::$app->request->get('uploadfile');
			if($uploadfile){
				$filearr = explode("#",$uploadfile);
				$filedir = end($filearr);
				$filearrs = explode("/",$uploadfile);
				$filenames = end($filearrs);
				//var_dump($filearr[4]);
				//exit;
				if(empty($filearr[4])){
					$session = Yii::$app->session;
					$session->setFlash('not_exist', 'file not exist!');
					return $this->redirect(['file_management/file_management']);
				}
				
				return $this->redirect(['file_management/download','filedir'=>$filedir,'filename'=>$filenames]);
			}
			
			$delete = Yii::$app->request->get('delete');
			
			//isset若变量不存在则返回 FALSE ; 若变量存在且其值为NULL，也返回 FALSE
			if( isset($delete) ){
				$farr = explode("#",$delete);
				$deviceName = $farr[2];
			//var_dump($farr);
			//exit;
				File_management_models::delete_filedata($deviceName);
				//添加一条删除设备日志
				$device_version = $deviceName.'_'.$farr[3];
				Common::add_log(1,6,'delete',$device_version,'device');
				return $this->redirect(['file_management/file_management']);//跳转页面，防止页面刷新再次提交
			}
		
		}
		
		$check_value = "";
		
        if (Yii::$app->request->isPost) {
			
			$page = File_management_models::get_file_management_page("");
			$list = File_management_models::get_file_management_list("");
			
			$status = Yii::$app->request->post('flagstatus');
			$firmwareName = Yii::$app->request->post('firmwareName_name');
			$deviceNames = Yii::$app->request->post('File_to_dev');
			$deviceName = $deviceNames['dev_name'];
		//var_dump($deviceName);
		//exit;
			$version = Yii::$app->request->post('version_name');
			if($status == "add"){
				$check_value = File_management_models::add_firmware_info($firmwareName,$deviceName,$version);
			}
			$dev_data = File_to_dev::get_dev_name();
			if(!empty($check_value) && $status == "add"){
				return $this->render('file_management',[
						"page" => $page,
						"list" => $list,
						"filename" => $filename,
						"check_value" => "error",
						'dev_name' => $dev_name,
						'dev_data' => $dev_data,
				]);
			}
			
			$dir = "/var/www/uploadfile/".$deviceName.'_'.$version;
			if (!is_dir($dir)){
				mkdir($dir);
				chmod($dir, 0777);
			}
		//var_dump($deviceName);
		//exit;
            $filename->file = UploadedFile::getInstance($filename, 'file');
            if ($filename->file && $filename->validate()) {
				$name = $filename->file->baseName . "." . $filename->file->extension;
				$dir = $dir."/". $name;
				$filename->file->saveAs($dir);
				File_management_models::update_dir($dir,$deviceName,$version);
				
				if($status == "edit"){
					//添加一条更新文件日志
					Common::add_log(1,6,'update&upload',$name,'file');
				}else{
					//添加一条添加文件日志
					Common::add_log(1,6,'add&upload',$name,'file');
				}
            }
			return $this->redirect(['file_management']);
		
        }
		
        $dev_data = File_to_dev::get_dev_name();
		//var_dump($dev_data);
		//exit;
		
		/*$model = new Test();
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['index']);
		} else {
			return $this->renderAjax('create', [
				'model' => $model,
			]);
		}*/
		
		return $this->render('file_management',[
			"page" => $page,
			"list" => $list,
			"filename" => $filename,
			"check_value" => $check_value,
			'dev_name' => $dev_name,
            'dev_data' => $dev_data,
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
		 
		 if(Yii::$app->request->isGet){
			$uploadfile = Yii::$app->request->get('uploadfile');
			if($uploadfile){
				$arr = explode("/",$uploadfile);
				if(!isset($arr[1])){
					return $this->render('history_version',[
						"dir_check" => "error",
					]);
				}
				$arr1 = $arr[1];
				$arr2 = $arr[2];
				$arr3 = $arr[3];
				$arr4 = $arr[4];
				$arr5 = $arr[5];
				$bf5 = explode("\"",$arr5);
				$filename = $bf5[0];
				$filedir = '/'.$arr1.'/'.$arr2.'/'.$arr3.'/'.$arr4.'/'.$filename;
				//var_dump($filedir);
				//exit;
				
				return $this->redirect(['file_management/download','filedir'=>$filedir,'filename'=>$filename]);
			}
		}
		 
		return $this->render('history_version');
	}
	
	 public function actionDownload(){

            $export = Yii::$app->request->get('filedir');
			$name = Yii::$app->request->get('filename');
            //判断文件是否存在
			//var_dump($export);
			//exit;
            if(!file_exists($export)){
				echo "Not file exist!";
				exit;
            }

            if($export){   
                $size = filesize($export);
                Header('Content-Type: application/x-img;charset=utf-8'); //发送指定文件MIME类型的头信息
                Header("Accept-Ranges: bytes");
                Header("Content-Length:".$size); //发送指定文件大小的信息，单位字节
                Header("Content-Disposition:attachment; filename=".$name); //发送描述文件的头信息，附件和文件名   
                readfile($export);

            }
			//添加一条下载日志
            Common::add_log(1,6,'download',$name,'file');
  
        return $this->renderPartial('download');
    }
	
	
	
}

?>
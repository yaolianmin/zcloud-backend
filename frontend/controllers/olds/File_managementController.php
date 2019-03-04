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
		$filename_2 = new UploadForm();
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
			
			$uploadfile_2 = Yii::$app->request->get('uploadfile_2');
			if($uploadfile_2){
				$filearr_2 = explode("#",$uploadfile_2);
				$filedir_2 = end($filearr_2);
				$filearrs_2 = explode("/",$uploadfile_2);
				$filenames_2 = end($filearrs_2);
			//var_dump($filearr_2[4]);
			//exit;
				$dir_dt = $filearr_2[4];
				if($dir_dt == "null" || $dir_dt == ""){
			//var_dump($filearr_2[4]);
			//exit;
					$session = Yii::$app->session;
					$session->setFlash('not_exist_2', 'file not exist!');
					return $this->redirect(['file_management/file_management']);
				}
				
				return $this->redirect(['file_management/download','filedir'=>$filedir_2,'filename'=>$filenames_2]);
			}
			
			/*$delete = Yii::$app->request->get('delete');
			//isset若变量不存在则返回 FALSE ; 若变量存在且其值为NULL，也返回 FALSE
			if( isset($delete) ){
			//var_dump($delete);
			//exit;
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
			
			$delete_2 = Yii::$app->request->get('delete_2');
			if( isset($delete_2) ){
				$farr_2 = explode("#",$delete_2);
				$id_2 = $farr_2[0];
			//var_dump($farr_2);
			//exit;
				File_management_history_version::delete_filedata($id_2);
				//添加一条删除设备日志
				$device_version_2 = $farr_2[2].'_'.$farr_2[3];
				Common::add_log(1,6,'delete',$device_version_2,'device');
				return $this->redirect(['file_management/file_management']);//跳转页面，防止页面刷新再次提交
			}*/
			
			$delete_value = Yii::$app->request->get('delete_value');
			if($delete_value){
				$farr = explode("#",$delete_value);
				$deviceName = $farr[2];
			//var_dump($delete_value);
			//exit;
				File_management_models::delete_filedata($deviceName);
				//添加一条删除设备日志
				$device_version = $deviceName.'_'.$farr[3];
				Common::add_log(1,6,'delete',$device_version,'device');
				\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
				return ["flag_delete" => "ok"];
			}
			
			$delete_value_2 = Yii::$app->request->get('delete_value_2');
			if( isset($delete_value_2) ){
				$farr_2 = explode("#",$delete_value_2);
				$id_2 = $farr_2[0];
			//var_dump($farr_2);
			//exit;
				File_management_history_version::delete_filedata($id_2);
				//添加一条删除设备日志
				$device_version_2 = $farr_2[2].'_'.$farr_2[3];
				Common::add_log(1,6,'delete',$device_version_2,'device');
				\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
				return ["flag_delete" => "ok"];
			}
			
			$history_version = Yii::$app->request->get('recipient');
			if($history_version){
				$arr = explode("#",$history_version);
				$result = File_management_history_version::push_sql($arr[2]);
				\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;//返回之前代码中添加返回头信息
				
				return ["status" => "ok","message" => "success","history_version" => $result];
			}
			
		}
		
		$check_value = "";
		
        if (Yii::$app->request->isPost) {
			$page = File_management_models::get_file_management_page("");
			$list = File_management_models::get_file_management_list("");
			
			$status = Yii::$app->request->post('flagstatus');
			$status_2 = Yii::$app->request->post('flagstatus_2');
			$firmwareName = Yii::$app->request->post('firmwareName_name');
			$firmwareName_2 = Yii::$app->request->post('firmwareName_name_2');
			$deviceNames = Yii::$app->request->post('File_to_dev');//机种名
			$deviceNames_2 = Yii::$app->request->post('File_to_dev_2');//机种名
			$deviceName = $deviceNames['dev_name'];
			$deviceName_2 = $deviceNames_2['dev_name'];
		//var_dump($deviceName_2);
		//exit;
			$version = Yii::$app->request->post('version_name');
			$version_2 = Yii::$app->request->post('version_name_2');
			if($status == "add"){
				$check_value = File_management_models::add_firmware_info($firmwareName,$deviceName,$version);
			}
			$dev_data = File_to_dev::get_dev_name();
			if(!empty($check_value) && $status == "add"){
				return $this->render('file_management',[
						"page" => $page,
						"list" => $list,
						"filename" => $filename,
						"filename_2" => $filename_2,
						"check_value" => "error",
						'dev_name' => $dev_name,
						'dev_data' => $dev_data,
				]);
			}
			
			$dir = "/var/www/uploadfile/".$deviceName.'_'.$version;
			$dir_2 = "/var/www/uploadfile/".$deviceName_2.'_'.$version_2;
			if (!is_dir($dir)){
				mkdir($dir);
				chmod($dir, 0777);
			}
			if (!is_dir($dir_2)){
				mkdir($dir_2);
				chmod($dir_2, 0777);
			}
		//var_dump($deviceName);
		//exit;
			if($status_2){
				//只针对有历史版本的，弹框中的值
				$filename_2->file = UploadedFile::getInstance($filename_2, 'file');
				if ($filename_2->file && $filename_2->validate()) {
					$name_2 = $filename_2->file->baseName . "." . $filename_2->file->extension;
					$dir_2 = $dir_2."/". $name_2;
					$filename_2->file->saveAs($dir_2);
					File_management_history_version::update_dir($dir_2,$deviceName_2,$version_2);
					
					Common::add_log(1,6,'update&upload',$name_2,'file');
				}
				return $this->redirect(['file_management']);
			}
		
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
		
		return $this->render('file_management',[
			"page" => $page,
			"list" => $list,
			"filename" => $filename,
			"filename_2" => $filename_2,
			"check_value" => $check_value,
			'dev_name' => $dev_name,
			'dev_data' => $dev_data,
		]);
		
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
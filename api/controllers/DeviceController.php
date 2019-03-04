<?php
namespace api\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\QueryParamAuth;
use yii\data\ActiveDataProvider;
use yii\web\Response;
use backend\models\Heartbeat;
use backend\models\report\Process_report;
use backend\models\alert\Process_alert;
use backend\models\php\modelname\Modelname;
use backend\models\ackaction\Ackaction;
use backend\models\actfail\Actfail;

define("ERR_OK", 0);
define("ERR_BAD_MAC", -1);
define("ERR_BAD_CUSTOMER_KEY", -2);
define("ERR_BAD_CHKSUM", -3);
define("ERR_SHORT_PARAM", -4);
define("ERR_BAD_PARAM", -5);
define("ERR_FILE_NOT_FOUND", -6);
define("ERR_REQ_EXED", -7);
define("ERR_UNREG_CONSOLE", -8);

class DeviceController extends ActiveController{

    public $modelClass = '';//±ØÐëÒª¼Ó£¬Ëü¼Ì³Ð×Ôyii\rest\ActiveController,

	private $b64 = false; //Éè±¸´«¹ýÀ´µÄjsonÖÐµÄsta_hostÊÇ·ñÍ¨¹ýbase64¼ÓÃÜ,Ä¬ÈÏÎª·ñ add by taodeyu 2015-01-04
	
	public function actions()
    {
        $actions = parent::actions();
        unset($actions['index'], $actions['update'], $actions['create'], $actions['delete'], $actions['view']);
        return $actions;
    }
	

    /** 
     * 说明：这里的方法主要跟设备端交互
     *      这里的访问方式仍然和原来老版本的
     *      方式一样.
     *
     *
     *
     *
     */
	public function actionDevice(){
		$value = Yii::$app->request->get(); // 获取设备端传递的参数
		$raw_jason = file_get_contents('php://input', 'r');
		$get_model_name = Modelname::get_dev_model_name($value['modelname']);

		$this->b64 = (isset($value['b64']) && intval($value['b64']) === 1) ? true : false;

		switch ($value['action']){
            // 1.0 心跳包操作
			case "heartbeat":
				if("XN-1032" == $value['modelname']){

					Heartbeat::heartbeat_handle_1032CPE($value);

				}else if($get_model_name == $value['modelname'] && "CPE" == $value['opemode'] ){

					Heartbeat::heartbeat_handle_1023CPE($value);

				}else if($get_model_name == $value['modelname'] && "Bridge" == $value['opemode'] ){

					Heartbeat::heartbeat_handle_1023Bridge($value);
				}else{
			        try{
			        	$infor = Heartbeat::heartbeat_handle($value);
                        //return $infor;
			        }catch( \Exception $e){
		               return [
		                   'state'=>'1000',
		                   'message'=> $e->getMessage()
		               ]; 
		            }
				    
	 			}
	 			break;
	 		// 上报信息
			case "report":
                try{ 
					$raw_jason = file_get_contents('php://input', 'r');
    
					$b64_flag = Process_report::is_base64_dev();
					if($this->b64 == false && $b64_flag == false){
						$raw_jason = str_replace("\\\\", "/", $raw_jason);
						$raw_jason = str_replace('\"', "'", $raw_jason);
						$raw_jason = str_replace("\\", '/', $raw_jason);
						$raw_jason = mb_convert_encoding($raw_jason, "UTF-8", "GBK");	
					}
					Process_report::report_handle($value,$raw_jason);
					break;
				}catch( \Exception $e){
		            return [
		                   'state'=>'00000',
		                   'message'=> $e->getMessage()
		            ]; 
		        }
			case "alert":
				Process_alert::process_alert($value,$raw_jason);
				break;
			case "ackaction":
				// Sanity check
				$action_id = $value['actionID'];
				$ack_type = $value['action'];
				if (!isset($action_id) || !isset($ack_type))
				{
					self::return_error_code(ERR_SHORT_PARAM);
					break;
				}
				Ackaction::process_action_ack($value['type'], $action_id, $ack_type, $value['opemode']);
				break;
			case "actfail":
				$action_id = $value["actionID"];
				$action_err = $value["err"];
				if (!isset($action_id) || !isset($action_err))
				{
					self::return_error_code(ERR_SHORT_PARAM);
					break;
				}
				Actfail::process_action_fail($action_id, $action_err);
				break;
			/*case "load"://Ã»ÓÐ¸Ã¹¦ÄÜ
				$file = $value["file"];
				if (!isset($file))
				{
					return_error_code(ERR_SHORT_PARAM);
					break;
				}

				if (strcmp($file, "globalinfo.xml") == 0)
				{
					return_globalinfo($connection);
				}
				else
				{
					return_playlist($connection, $console_id, $file);
				}
				break;*/
		
			// case "alert_assoc":
			// 	Assoc_sta_list::process_alert_assoc($value["mac"], $value['type'], $value['action'], $value['fwversion'],$raw_jason);
			// 	break;
				
			// default:
			// 	self::return_error_code(ERR_BAD_PARAM);
			// 	break;
		}
		
    }
	
	function return_error_code($error_code)
	{
		$error_list = array(
		ERR_OK                => "OK",
		ERR_BAD_MAC           => "Device MAC not found",
		ERR_BAD_CUSTOMER_KEY  => "No such customer key",
		ERR_BAD_CHKSUM        => "Chsn mismatched",
		ERR_SHORT_PARAM       => "Not enough paramter",
		ERR_BAD_PARAM         => "Parameter not supported",
		ERR_FILE_NOT_FOUND    => "Can't find the specifed file",
		ERR_REQ_EXED          => "Request already executed",
		ERR_UNREG_CONSOLE     => "The console is unregistered",
		);
		
		$xml_output  = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
		$xml_output .= "<rsp stat=\"failed\">\n";
		$xml_output .= "\t<errcode>{$error_code}</errcode>\n";
		$xml_output .= "\t<errdesc>{$error_list[$error_code]}</errdesc>\n";
		$xml_output .= "</rsp>";
		
		echo $xml_output;
	}
	
}




<?php
namespace api\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\db\ActiveRecord;
use common\error\Error;
use yii\web\NotFoundHttpException;
use yii\web\HttpException;
use backend\models\common\Common_model;
use backend\models\dev_management\DeviceManagement;
use api\models\User;
use api\models\Ltefi_485;
use api\models\Vpn_list;
use api\models\DevInfo;
/**
 * device management controller.
 * 设备管理控制器.
 */
class DeviceManagementController extends ActiveController
{   
    /**
     * 指定调用这个控制器时链接哪个数据模型.
     * 该属性必须设置.
     */
     public $modelClass = 'backend\models\DevInfo';
    /**
     * 设置控制器的属性
     * @return $actions
     */
    public function actions()
    {
	    $actions = parent::actions();
        /**
         * 注销系统自带的实现方法.
         */
	    unset($actions['index'], $actions['update'], $actions['create'], $actions['delete'], $actions['view']);
	   	return $actions;
     }

    /**
     * 方法：设备管理
     * 功能：获得该用户所有的设备
     * @return array
     */
    public function actionIndex() {  
        $get = Yii::$app->request->get();
        if(!isset($get['flag'])){
            return [
                'state'=>1,
                'message'=>'请您用正去方式访问'
            ];
        }
        // 1.0 查看数据库操作
        if($get['flag'] == 'get_user_device'){
            $power =  User::get_user_power_by_username($get['user']);
            if($power == 15){

                $devices = DeviceManagement::super_get_devices($get);
            }elseif($power == 2){

                $devices = DeviceManagement::managemant_get_devices($get);
            }elseif($power == 1){

                $devices = DeviceManagement::user_get_devices($get);
            }else{
                return [
                    'state'=>1,
                    'message'=>'您没有用户权限，请联系管理员'
                ]; 
            }
            return $devices;
        // 2.0 修改设备数据库操作
        }elseif($get['flag'] == 'update_mac_infor'){
            try{
                $power =  User::get_user_power_by_username($get['user']);
                if($power < 2){
                    return [
                        'state'=>1,
                        'message'=>'您没有修改设备的权限'
                    ]; 
                }
                // 检测mac地址的合法性
                if(! preg_match('/^[A-Fa-f0-9]{1,2}\:[A-Fa-f0-9]{1,2}\:[A-Fa-f0-9]{1,2}\:[A-Fa-f0-9]{1,2}\:[A-Fa-f0-9]{1,2}\:[A-Fa-f0-9]{1,2}$/',$get['mac'])){
                    return [
                        'state'=>1,
                        'message'=>'Mac地址合适不正确'
                    ]; 
                }
                // 修改数据库的信息
                DevInfo::updateAll([
                    'service_start'=>$get['time_be'],
                    'service_end'=>$get['time_end'],
                    'pos_X'=>$get['re_x'],
                    'pox_Y'=>$get['re_y'],
                    'dev_info1'=>$get['remark1'],
                    'dev_info2'=>$get['remark2'],
                ],['mac_addr'=>$get['mac']]);

                return[
                    'state'=>0,
                    'message'=>'修改成功'
                ];
            }catch( \Exception $e){
                return [
                    'state'=>1,
                    'message'=> $e->getMessage()
                ];   
            }
        // 3.0 删除设备数据库操作
        }elseif( $get['flag'] == 'delete_device_mac'){
           try{
                //检测操作者级别
                $power =  User::get_user_power_by_username($get['user']);
                if($power < 12){
                    return [
                        'state'=>1,
                        'message'=>'您没有删除设备的权限'
                    ];
                }
                //删除 DevInfo 表
               DevInfo::deleteAll(['mac_addr'=>$get['mac_addr']]);
               // 删除 ltefi_rs485 表
                Ltefi_485::deleteAll(['mac_addr'=>$get['mac_addr']]);
                // 删除 vpnclient_list 表
                Vpn_list::deleteAll(['dev_mac'=>$get['mac_addr']]);
                return [
                    'state'=>0,
                    'message'=>'删除成功'
                ];
            }catch( \Exception $e){
               return [
                   'state'=>1,
                   'message'=> $e->getMessage()
               ];   
            } 
        }

    }

    /**
     * Action: Updates an existing device model.
     * @param string $id the primary key of the device model.
     * @return the device model being updated
     */
    public function actionUpdate($mac)
    {
        $model = $this->findModelBymac($mac);
        $model->attributes = Yii::$app->request->post();
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        
        if (!$model->save()) {
            return array_values($model->getFirstErrors())[0];
        }
        return $model;
    }

    /**
     * 方法: 添加设备
     * 说明：当有xml文件的时候，实际就是批量添加设备
     *       如果没有文件，则默认的是单个添加
     *       由于老版本代码中的代码中，批量添加没有添加 ltefi_rs485 vpnclient_list
     *       两张表中，而单个设备有这个需求
     *
     *
     * @return： 返回添加的状态
     */
    public function actionCreate(){
        try{
            $model = new $this->modelClass();
            $_post = Yii::$app->request->post(); 
            $power =  User::get_user_power_by_username($_post['action_user']);
            // 检测权限
            if($power < 12){
                return [
                    'state'=>1,
                    'message'=>'您没有权限添加设备，请联系管理员'
                ];
            }
             // 1.0 批量添加设备
            if ($_FILES){ 
                // 检测文件的格式
                $dir_name =  pathinfo($_FILES['uploadFileName']['name'])['extension'];
                if($dir_name != 'xml'){
                    return [
                        'state'=>1,
                        'message'=>'请上传以xml格式结尾的文件'
                    ];
                }
                /** 检测文件的大小，这里默认限制 8M 以内，若有需要
                 *   可以配置php.ini里面的配置项(太大的传输，有很多配置需要修改)
                 */
                if($_FILES['uploadFileName']['size'] > 1024*8){
                    return [
                        'state'=>1,
                        'message'=>'您上传的文件过大，请更换文件添加'
                    ];
                }
                $fileName = '/var/www/ZOAM/uploadfile/maclist/'.$_FILES["uploadFileName"]["name"];
                // 检测此文件是否已经存在
                if(file_exists($fileName)){
                    return [
                        'state'=>1,
                        'message'=>'您上传的文件已经存在，请更换文件添加'
                    ]; 
                }
                // 保存文件至 /var/www/ZOAM/uploadfile/maclist  目录下
                $rel = DeviceManagement::save_upload_maclistfile();
                if($rel != 'success'){
                    return [
                        'state'=>1,
                        'message'=>$rel
                    ]; 
                }
                $maclist = DeviceManagement::parserUpload_Maclistfile($fileName);
                // 批量添加之数据库中
                $rel = DeviceManagement::batchRegisterDevice($maclist,$_post); 
                return [
                    'state'=>0,
                    'message'=>$rel
                ];
            // 2.0 单个添加设备
            }else{ 
                $query = $model::find();
                $count = $query->where(['mac_addr' =>$_post['mac_addr']])->count();
                // 检测数据库中是否已经存在
                if ($count) {
                    return [
                        'state'=>1,
                        'message'=>'该设备已经存在，添加失败'
                    ];
                }
                // 检测mac地址的合法性  AA:BB:CC:DD:EE:FF
                if(!preg_match('/^[A-Fa-f0-9]{1,2}\:[A-Fa-f0-9]{1,2}\:[A-Fa-f0-9]{1,2}\:[A-Fa-f0-9]{1,2}\:[A-Fa-f0-9]{1,2}\:[A-Fa-f0-9]{1,2}$/',$_post['mac_addr'])){
                    return [
                        'state'=>1,
                        'message'=>'mac地址的合适不正确'
                    ];
                }
                // 添加之数据库的devinfor表中
                $model->attributes = $_post;  
                $model->save();
                
                // 添加至 ltefi_rs485 表中 
                $rel = Ltefi_485::find()->where(['mac_addr'=>$_post['mac_addr']])->asArray()->one();
                if(!$rel){
                    $Ltefi_485 = new Ltefi_485();
                    $Ltefi_485->mac_addr = $_post['mac_addr'];
                    $Ltefi_485->Relay1 = '0';
                    $Ltefi_485->Relay2 = '0';
                    $Ltefi_485->Relay3 = '0';
                    $Ltefi_485->Relay4 = '0';
                    $Ltefi_485->save();
                }

                // 添加至 vpnclient_list 表中
                $rel = Vpn_list::find()->where(['dev_mac'=>$_post['mac_addr']])->asArray()->one();
                if(!$rel){
                    $mac_addr = preg_replace("/:/", "-",$_post['mac_addr']);
                    $vpn_list = new Vpn_list();
                    $vpn_list->dev_mac = $_post['mac_addr']; 
                    $vpn_list->vpn_stat = '0';
                    $vpn_list->vpn_link_time = '0';
                    $vpn_list->vpnclient_enable = '0';
                    $vpn_list->vpn_client_ip = '0.0.0.0';
                    $vpn_list->vpn_client_user = $mac_addr;
                    $vpn_list->vpn_client_passwd = ' ';
                    $vpn_list->save(); 
                }
                return [
                    'state'=>0,
                    'message'=>'添加设备成功'
                ];
            } 
        }catch( \Exception $e){
            return [
                    'state'=>1,
                    'message'=> $e->getMessage()
                ];   
        }
    }

    /**
     * Action: Delete a Registed device.
     * @param string $mac the primary key of the device model.
     * @return 
     */
    public function actionDelete($mac)
    {
        $model = DeviceManagement::findModelBymac($mac);
        return $model->delete();
    }

    /**
     * Action: View a registed device information by mac.
     * @param  string $mac the primary key of the device model
     * @return arry
     */
    public function actionView($mac)
    {
        $modelClass = $this->modelClass;
        $_count = $modelClass::find()->Where(['mac_addr'=>$mac])->count();
        $_data = $modelClass::find()->Where(['mac_addr'=>$mac])->all();

        return array ("count" => $_count,"body" => $_data);
    }

    /**
     * Action:View registed devices information by mac use like search.
     * @return array 
     */
    public function actionViewBySearch()
    {
        $modelClass = $this->modelClass;
        $_get = Yii::$app->request->get();
        $query = $modelClass::find()->Where(['like','mac_addr',$_get['mac_addr_like']]);

        $_count = $query->count();
        $_data = $query->offset(( $_get['_page']-1)*$_get['_limit'])->limit($_get['_limit'])->all();

        return array ("count" => $_count,"body" => $_data);
    }

    /**
     * Action: get manager name list
     * @return array
     */
    public function actionGetManagerList()
    {
        return DeviceManagement::getManagerList();
    }

    /**
     * Function: search a registed device by id.
     * @param string $id the primary key of the device model
     * @return the device model being founded
     * @throws ServerErrorHttpException if there is any error when seraching the model
     */
    protected function findModel($id)
    {
        $modelClass = $this->modelClass;
        if (($model = $modelClass::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested model does not exist.');
        }
    }

    /**
     * Function:search a device by mac
     * @param  string $mac the primary key of the device model
     * @return the device model being founded
     * @throws ServerErrorHttpException if there is any error when seraching the model
     */
    protected function findModelBymac($mac)
    {
        $modelClass = $this->modelClass;
        
        if (($model = $modelClass::find()->Where(['mac_addr'=>$mac])->all()) !== null) {
            return $model[0];
        } else {
            throw new NotFoundHttpException('The requested model does not exist.');
        }
    }




}
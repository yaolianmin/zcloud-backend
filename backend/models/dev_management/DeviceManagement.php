<?php
namespace backend\models\dev_management;

use Yii;
use yii\base\Model;
use yii\db\Command;
use yii\web\NotFoundHttpException;
use yii\web\HttpException;

use backend\models\DevInfo;
use backend\models\GroupList;
use backend\models\common\Common_model;

/**
 * 设备管理模型
 */
class DeviceManagement extends Model {


    /** 
     * 功能：超级用户获得所有的设备信息
     * 参数：pages(从哪一页开始) page_size(每一页显示的条数)
     *       condition(条件)
     * 说明：此处注意：因apache服务器为短链接，只能由客户端发送请求
     *                 当这设备端断开的时候，服务器是无法检测到设备掉线的
     *                 因此，在数据库中的device_status字段不能作为判断设备掉线的
     *                 依据，必须根据时间来算 公式：300s以内
     *
     * 返回：array
     */
    public function super_get_devices($get){
        try{
             if($get['condition']){
               $where = ['like','mac_addr',$get['condition']];
            }else{
                $where = [];
            }
            $offset = ($get['pages']-1)*$get['page_size']; //需要从哪行开始查询
            $count = DevInfo::find()->where($where)->count();
            $infor = DevInfo::find()->select('mac_addr,model_name,model_type,device_status,group_name,user_name,dev_info1,dev_info2,service_end,last_templet_name,pos_X,pox_Y,service_start,recent_online')->where($where)->offset($offset)->limit($get['page_size'])->asArray()->all();
            // 处理设备是否在线的问题
                $now_time = time();
                foreach ($infor as $ke => $val) {
                    $online_time = strtotime($val['recent_online']);
                    if($now_time > $online_time+300){ //表示超过300秒没有发送
                        $infor[$ke]['device_status'] = 0;
                        // 修改数据库的字段
                        $off_time = date('Y-m-d H:i:s',$now_time); 
                        DevInfo::updateAll(['device_status'=>0,'recent_offline'=>$off_time],['mac_addr'=>$infor[$ke]['mac_addr']]);
                   }
                }
            return [
                'count'=>$count,
                'data'=>$infor
            ];
        }catch( \Exception $e){
            return  $e->getMessage();  
        }
    }

    
    /**
     * 功能：二级管理员获得所有的设备信息
     * 参数：user(二级用户名) pages(从哪一页开始) 
     *       condition(条件) page_size(每一页显示的条数)
     * 
     * 说明：此处注意：因apache服务器为短链接，只能由客户端发送请求
     *                 当这设备端断开的时候，服务器是无法检测到设备掉线的
     *                 因此，在数据库中的device_status字段不能作为判断设备掉线的
     *                 依据，必须根据时间来算 公式：300s以内
     *
     * 返回：array
     */
    public function managemant_get_devices($get){
        try{
            if($get['condition']){
               $where = "management='".$get['user']."'and mac_addr like '%" .$get['condition']."%'";
            }else{
                $where = "management='".$get['user']."'";
            }
            $offset = ($get['pages']-1)*$get['page_size']; //需要从哪行开始查询
            $count_sql ='select count(card_id) from DevInfo where '.$where;
            $sql = 'select mac_addr,model_name,model_type,device_status,group_name,user_name,dev_info1,dev_info2,service_end,last_templet_name,pos_X,pox_Y,service_start,recent_online from DevInfo where '.$where.' limit '.$offset.','.$get['page_size'];
            $count  = Yii::$app->db->createCommand($count_sql)->queryAll();
            $infor  = Yii::$app->db->createCommand($sql)->queryAll();
            // 处理设备是否在线的问题
                $now_time = time();
                foreach ($infor as $ke => $val) {
                    $online_time = strtotime($val['recent_online']);
                    if($now_time > $online_time+300){ //表示超过300秒没有发送
                        $infor[$ke]['device_status'] = 0;
                        // 修改数据库的字段
                        $off_time = date('Y-m-d H:i:s',$now_time); 
                        DevInfo::updateAll(['device_status'=>0,'recent_offline'=>$off_time],['mac_addr'=>$infor[$ke]['mac_addr']]);
                   }
                }
            return [
                'count'=>$count[0]['count(card_id)'],
                'data'=>$infor
            ];
        }catch( \Exception $e){
            return  $e->getMessage();  
        }
    }


    /**
     * 功能：三级管理员获得所有的设备信息
     * 参数：user(三级用户名) pages(从哪一页开始) 
     *       condition(条件) page_size(每一页显示的条数)
     * 
     *说明： 由于条件sql语句不止一个，所以采用原生语句
     *
     * 返回：array
     */
    public function user_get_devices($get){
        try{
            if($get['condition']){
               $where = "user_name='".$get['user']."'and mac_addr like '%" .$get['condition']."%'";
            }else{
                $where = "user_name='".$get['user']."'";
            }
            $offset = ($get['pages']-1)*$get['page_size']; //需要从哪行开始查询
            $count_sql ='select count(card_id) from DevInfo where '.$where;
            $sql = 'select mac_addr,model_name,model_type,device_status,group_name,user_name,dev_info1,dev_info2,service_end,last_templet_name,pos_X,pox_Y,service_start,recent_online from DevInfo where '.$where.' limit '.$offset.','.$get['page_size'];
            $count  = Yii::$app->db->createCommand($count_sql)->queryAll();
            $infor  = Yii::$app->db->createCommand($sql)->queryAll();
             // 处理设备是否在线的问题
                $now_time = time();
                foreach ($infor as $ke => $val) {
                    $online_time = strtotime($val['recent_online']);
                    if($now_time > $online_time+300){ //表示超过300秒没有发送
                        $infor[$ke]['device_status'] = 0;
                        // 修改数据库的字段
                        $off_time = date('Y-m-d H:i:s',$now_time); 
                        DevInfo::updateAll(['device_status'=>0,'recent_offline'=>$off_time],['mac_addr'=>$infor[$ke]['mac_addr']]);
                   }
                }
            return [
                'count'=>$count[0]['count(card_id)'],
                'data'=>$infor
            ];
        }catch( \Exception $e){
            return  $e->getMessage();  
        }
    }


	/**
     * Function:search a device by mac
     * @param  string $mac the primary key of the pdevice model
     * @return the device model being founded
     * @throws ServerErrorHttpException if there is any error when seraching the model
     */
    public function findModelBymac($mac)
    {
        $modelClass = $this->modelClass;
        
        if (($model = $modelClass::find()->Where(['mac_addr'=>$mac])->all()) !== null) {
            return $model[0];
        } else {
            throw new NotFoundHttpException('The requested model does not exist.');
        }
    }

    /**
     * Function: save_model_uploadfile description]
     * @param  string $post_info data by post request
     * @return none
     */
    public function save_upload_maclistfile(){
        /**
         * 将上传的文件流保存到相应的目录下
         */
        try{
            exec('chmod -R 777 /var/www/ZOAM/uploadfile');
            move_uploaded_file($_FILES["uploadFileName"]["tmp_name"],"/var/www/ZOAM/uploadfile/maclist/".$_FILES["uploadFileName"]["name"]);
            //修改上传文件的权限为0777
            system("cd /var/www/ZOAM/uploadfile/maclist/;chmod 777 -R ./");

            return 'success';
        }catch( \Exception $e){
            return $e->getMessage();   
        }
    }

    /**
     * Function:parser a XML Fromat file. 
     * eg: Zoam_aplist5AP.xml.
     * @param string $path must be absolute path
     * @return array 
     */
    public function parserUpload_Maclistfile($filename)
    {
    	$con = file_get_contents($filename);
		//XML标签配置
		$xmlTag = array(
		    'ipaddr',
		    'model',
		    'fw_version'
		);
		/**
		 * 先取出Mac地址
		 */
		$arry_mac =array();
		preg_match_all("/mac_id=\".*\"/", $con, $match);
		$arry_mac = $match;

		$data = array();
		foreach( $arry_mac as $key => $value) {
					foreach($value as $k => $v) {
				       $a = explode("mac_id=\"", $v);
				       $v = substr($a[1], 0, strlen($a[1])-1);
				       $data[$k]['mac_id']=$v;
				    }
				}
		/**
		 * 取出其他XML标签中的数据
		 */
		$arr = array();
		foreach($xmlTag as $x) {
		    preg_match_all("/<".$x.">.*<\/".$x.">/", $con, $temp);
		    $arr[] = $temp[0];
		}
		
		//去除XML标签并组装数据
		foreach($arr as $key => $value) {
		    foreach($value as $k => $v) {
		        $a = explode($xmlTag[$key].'>', $v);
		        $v = substr($a[1], 0, strlen($a[1])-2);
		        $data[$k][$xmlTag[$key]] = $v;
		    }
		}

		return $data;
    }

    /**
     * Function: batch registers devices.
     * @param array $maclist  need being batch register devices maclist
     * @param array $post_info fromdata send by post
     * @return [type] [description]
     */
    public function batchRegisterDevice($maclist,$post_info)
    {
    	$_post = $post_info;
    	$arr = array();
    	$index = 0;
    	foreach ( $maclist as  $value) {
    	 	$model = new DevInfo();
    	 	$query = $model::find();

    	 	$count = $query->where(['mac_addr' => $value['mac_id']])->count();
    	 	if ($count) {
    	 		$index++;
    	 		$arr[$index]['mac_addr'] = $value['mac_id'];
    	 		continue;
    	 	} else {
    	 		$model->mac_addr = $value['mac_id'];
    	 		$model->model_name = $value['model'];
    	 		$model->fw_version = $value['fw_version'];
    	 		$model->management = $_post['management'];
    	 		$model->dev_info1 = $_post['dev_info1'];
    	 		$model->dev_info2 = $_post['dev_info2'];
    	 		$model->save();
    	 	}
    	 	
    	 }
    	 if(!$index){
    	 	return  "批量注册成功";
    	 }else{
	    	return  "有".$index."个设备注册失败";
 		 }
    }

    /**
     * Function:获取管理员用户的列表(level=2)
     * @return [type] [description]
     */
    public function getManagerList()
    {
    	$model = new Common_model();
    	$model->find_model('user');
    	$query = $model->find();

    	$_data = $query->where(['Power'=>2])->all();
        $_count = $query->where(['Power'=>2])->count();

        return array (  "count" => $_count,
                        "body" => $_data,
                    );
    }

    

}
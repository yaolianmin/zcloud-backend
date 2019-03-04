<?php

namespace api\models;

use yii\db\ActiveRecord;
use api\models\User;


class Mgd_dev extends ActiveRecord{
    public static function tableName(){
        return '{{managed_device}}';//创建一个数据模型链接数据表
    }

     
    /**
     * 功能：检测Mac地址 ip地址 以及mac地址的合理性
     * 参数：mac地址 IP地址 用户名
     * 返回：何种提示的错误信息
     *
     */
    public function check_mac_ip_is_reasonable($post){
    	if(!preg_match('/^[A-F\d]{2}:[A-F\d]{2}:[A-F\d]{2}:[A-F\d]{2}:[A-F\d]{2}:[A-F\d]{2}$/',$post['manage_mac'])){
                return [
                    'state'=>1,
                    'message'=>'您的设备mac格式地址不正确'
                ];
            }
        if(!preg_match('/^(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$/',$post['mac_ip'])){
                return [
                    'state'=>1,
                    'message'=>'您的设备ip格式地址不正确'
                ];
        }
        if(!$post['dev_mac']){
            return [
                    'state'=>1,
                    'message'=>'您没有选中设备管理'
                ];
        }
        $rel = Mgd_dev::find()->select('management_dev_mac')->where(['management_dev_mac'=>$post['manage_mac']])->asArray()->one();
        if($rel){
            return [
                'state'=>1,
                'message'=>'该设备的mac地址已存在，请更换'
            ];
        }
        $power = User::get_user_power_by_username($post['username']);
        if($power != 2){
            return [
                'state'=>1,
                'message'=>'您不可以添加，请更换组管理员身份'
            ];
        }
        return ['state'=>0];
    }

    /**
     * 功能：添加通用设备到数据库中
     * 参数： $post
     * 返回：true
     *
     */
    public function add_common_dev_infor($post){
    	try{
    		//$transaction= Yii::$app->db->beginTransaction(); // 开启数据库的事务
	    	$mgd_dev = new Mgd_dev();
	    	$mgd_dev->management_dev_mac = $post['manage_mac']; //通用管理MAC地址
	    	$mgd_dev->management_dev_ip = $post['mac_ip'];   //ip
	    	$mgd_dev->dev_mac =  $post['dev_mac'];            //管理的设备的mac地址
	    	$mgd_dev->magement_position = $post['mac_index'];   //设备位置
	    	$mgd_dev->management_comment = $post['mac_desc'];  // 设备描述
	    	$mgd_dev->save();
	    	//$transaction->commit();//提交事务结束
	    	return 'success';
        }catch(\Exception $e){
        	//$transaction->rollback();//如果操作失败, 数据回滚
           return 'false'; 
        }
    }


    /**
     * 功能：检测Mac地址 ip地址 以及mac地址的合理性
     * 参数：mac地址 IP地址 用户名
     * 返回：何种提示的错误信息
     *
     */
    public function check_edit_mac_ip_is_reasonable($post){
    	if(!preg_match('/^[A-F\d]{2}:[A-F\d]{2}:[A-F\d]{2}:[A-F\d]{2}:[A-F\d]{2}:[A-F\d]{2}$/',$post['manage_mac'])){
                return [
                    'state'=>1,
                    'message'=>'您的设备mac格式地址不正确'
                ];
            }
        if(!preg_match('/^(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$/',$post['mac_ip'])){
                return [
                    'state'=>1,
                    'message'=>'您的设备ip格式地址不正确'
                ];
        }
        if(!$post['edit_id']){
            return [
                    'state'=>1,
                    'message'=>'您选中的设备没有id，请刷新网页'
                ];
        }
        $rel = Mgd_dev::find()->select('management_dev_mac')->where(['id'=>$post['edit_id']])->asArray()->one();
        if($rel['management_dev_mac'] != $post['manage_mac']){
        	$rels = Mgd_dev::find()->where(['management_dev_mac'=>$post['manage_mac']])->asArray()->one();
        	if($rels){
        		return [
	                'state'=>1,
	                'message'=>'该设备的mac地址已存在，请更换'
            	];
        	}
        }
        $power = User::get_user_power_by_username($post['username']);
        if($power != 2){
            return [
                'state'=>1,
                'message'=>'您不可以添加，请更换组管理员身份'
            ];
        }
        return ['state'=>0];
    }


    /**
     * 功能：修改通用设备表值数据库中
     * 参数
     * 返回
     */
    public function edit_common_infor($post){
    	 Mgd_dev::updateAll([
                'management_dev_mac'=>$post['manage_mac'],
                'management_dev_ip'=>$post['mac_ip'],
                'magement_position'=>$post['mac_index'],
                'management_comment'=>$post['mac_desc']],['id'=>$post['edit_id']]);
    }

}
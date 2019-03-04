<?php
namespace backend\models;

use yii\db\ActiveRecord;

class DevInfo extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%DevInfo}}';//创建一个数据模型链接数据表
    }

    public function rules()  
   	{  
        // NOTE: you should only define rules for those attributes that  
        // will receive user inputs.  
        return array(  
            // more code...  
            array(['dev_name','model_name','model_type','mac_addr','card_id','device_status','user_name',
            		'management','group_name','beat_interval','last_beat','terminal_number','pos_X','pox_Y',
            		'templet','templet_update','term_of_Service','hw_version','fw_version','offline_alarmtime',
            		'stampdragged_time','first_seen','last_seen','recent_online','recent_offline','fw_path',
            		'dev_info1','dev_info2','service_start','service_end','expire_email','last_templet_name',
            		'last_channel','last_vap_ssid','op_mode','bridge_mac','auto_key','used_portal','CPE_flag'], 'safe'), //Modify the fields in here  
            // more code...  
        );  
    }  


    
}
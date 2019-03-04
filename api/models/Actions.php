<?php
namespace api\models;

use yii\db\ActiveRecord;


class Actions extends ActiveRecord{


    public static function tableName(){
        return '{{actions}}';//创建一个数据模型链接数据表
    }

    /*
     * 功能：添加数据，并返回刚添加数据的action_id
     * 参数：mac_addr(其他参数直接给定，老版本的数据来源)
     * 返回：$action_id
     *
     */
    public function add_and_get_action_id($mac_addr){
    	$actions = new Actions();   
        $actions->device_mac = $mac_addr;  // mac地址
        $actions->act_type_id = 4;                // 代表重启
        $actions->act_params = 'N/A';             //描述
        $actions->action_state = 0;              
        $actions->save();

        //获得刚插入数据的action_id
        $where = [
            'device_mac'=>$mac_addr,
            'act_type_id'=>4,
            'act_params'=>'N/A',
            'action_state'=>0
        ];
        $action_id = Actions::find()->select('action_id')->where($where)->asArray()->one();
        return $action_id['action_id'];
    }



    /*
     * 功能：添加数据，并返回刚添加数据的action_id
     * 参数：mac_addr(其他参数直接给定，老版本的数据来源)
     * 返回：$action_id
     *
     */
    public function fw_get_action_id($mac_addr,$fw_name,$ftp_server){
        $actions = new Actions();   
        $actions->device_mac = $mac_addr;  // mac地址
        $actions->act_type_id = 3;                // 代表重启
        $actions->act_params = $fw_name;             //描述
        $actions->action_state = 0; 
        $actions->ftp_server_params = $ftp_server;          
        $actions->save();

        //获得刚插入数据的action_id
        $where = [
            'device_mac'=>$mac_addr,
            'act_type_id'=>3,
            'act_params'=>$fw_name,
            'action_state'=>0
        ];
        $action_id = Actions::find()->select('action_id')->where($where)->asArray()->one();
        return $action_id['action_id'];
    }
}

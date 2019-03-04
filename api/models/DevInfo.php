<?php
namespace api\models;

use yii\db\ActiveRecord;
use api\models\Dev_card;

class DevInfo extends ActiveRecord{


    public static function tableName(){
        return '{{DevInfo}}';//创建一个数据模型链接数据表
    }


   /**
    *
    *
    *
    *
    *
    */
    public function check_dev_model_name($modelname,$mac_addr){
    	 try{
    	$rel = Dev_card::find()->where(['model_name'=>$modelname])->asArray()->one();
    	if($rel){
    		$card_id = 0;
    		if($rel['dev_type'] == "1022" || $rel['dev_type'] == "1023" || $rel['dev_type'] == "ac1028" || $rel['dev_type'] == "1033" || $rel['dev_type'] == "1032"){
    			if( $rel['card1'] == "2G" ){
    				$card_id = $card_id | 1;
    			}
				else if( $rel['card1'] == "5G" ){
					$card_id = $card_id | 5;
				} 
				if( $rel['card2'] == "2G" ){
				 $card_id = $card_id | 2;
				}
				else if( $rel['card2'] == "5G" ){
					$card_id = $card_id | 10;
				}
				return array("TYPE"=>$rel['dev_type'], "CARD_ID"=>$card_id);
    		}elseif($rel['dev_type'] == "1025" || $rel['dev_type'] == "1027" 
			   || $rel['dev_type'] == "224" || $rel['dev_type'] == "1028" 
			   || $rel['dev_type'] == "1029" || $rel['dev_type'] == "1025-R" || $rel['dev_type'] == "AC1027L" || $rel['dev_type'] == "1030" || $rel['dev_type'] == "1031"){
			   	$Get_card_id = DevInfo::get_card_id_from_DevInfo_by_mac($mac_addr);
			   	if( !$Get_card_id) {
					$card_id = 0;
					if( $rel['card1'] == "2G" ){
						$card_id = $card_id | 1;
					}else if( $rel['card1'] == "5G" ){
						$card_id = $card_id | 5;
					} 	
					if( $rel['card2'] == "2G" ){
					    $card_id = $card_id | 2;
					}else if( $rel['card2'] == "5G" ){
						 $card_id = $card_id | 10;
					}
					return array("TYPE"=>$rel['dev_type'], "CARD_ID"=>$card_id);
				}else{
					return array("TYPE"=>$rel['dev_type'], "CARD_ID"=>$Get_card_id);
				}
    		}
    	}
    	}catch(\Expection $e){
            return $e->getMessage();
        }
    }


    /**
     * 功能：根据mac查询card_id
     * 参数：mac_addr
     * 返回：true false
     *
     */
    public function get_card_id_from_DevInfo_by_mac($mac_addr){
    	$rel = DevInfo::find()->select('card_id')->where(['mac_addr'=>$mac_addr])->asArray()->one();
    	if($rel){
    		return $rel['card_id'];
    	}
    }



    /**
     * 功能：任务下发时根据组管理的信息，获得相应的模板信息
     * 参数 user_name  组查看者， management  组管理者
     *      group_name 组名,  page 哪一页，page_size 每一页的多少
     *
     * 返回：数组
     */
    public  function get_task_devInfo($user_name,$management,$group_name,$page,$page_size){
        $offset = ($page-1)*$page_size; //需要从哪行开始查询 
        $where = [
            'user_name'=>$user_name,
            'management'=>$management,
            'group_name'=>$group_name
        ]; // 条件
        $count = DevInfo::find()->where($where)->count();
        $data = DevInfo::find()->select('mac_addr,model_type,model_name,fw_path,dev_info1')
                               ->where($where)
                               ->offset($offset)
                               ->limit($page_size)
                               ->asArray()
                               ->all();

        return [
            'count'=>$count,
            'data'=>$data
        ];
    }


    
}
<?php
/*
*模型：警告日志模型
*作用：连接DevLog表
*时间：2018-10-25
*/


namespace api\models;

use Yii;
use yii\db\ActiveRecord;
use api\models\User;
use api\models\GroupList;

class DevLog extends ActiveRecord{

	//连接GroupList表
    public static function tableName(){
        return '{{DevLog}}';
    }

    
    /**
     * 功能：超级用户获得组查看者
     * 参数：
     * 返回：
     */
    public function super_get_groups(){
        try{
    	$group = GroupList::find()->select('user_name')->asArray()->all();
    	$user = [];
    	$re_user = [];
    	foreach ($group as $val) {
    		if(!in_array($val['user_name'],$user)){
    			array_push($user, $val['user_name']);
    			array_push($re_user, ['user'=>$val['user_name']]);
    		}	
    	}
    	return [
    		'user_name'=>$re_user
    	];

         }catch(\Expection $e){
            return $e->getMessage();
        }
    }


    /**
     * 功能：根据条件获得警告日志信息
     * 参数：1）group_look-->查看者  
     *       3）mac_addr--> MAC地址 4)time_be-->起始时间
     *       5）time_end-->结束时间  6）pages-->哪一页开始
     *       7）page_size--> 每一页的显示条数
     *
     * 说明：因多条件的查询原生的sql条件写起来方便，框架带的查询条件不好
     *      组合使用，故改用原生的语句
     * 返回：array
     */
    public function super_get_alert_log($get){
    	try{
    	$where = '';
    	// 组查看者
        if($get['group_look']){
            $where = 'user_name="'.$get['group_look'].'" and ';
        }
        // MAC地址
        if($get['mac_addr']){
            $where .= 'mac_addr="'.$get['mac_addr'].'" and ';
        }
    	//时间
    	$time_end = date('Y-m-d',strtotime($get['time_end'])+24*3600);
    	$where .= "time >'".$get['time_be']."' and time <='".$time_end."' order by id desc ";

    	$where_count = $where;
        //起始行数
        $begin = ($get['pages']-1)*$get['page_size'];
        $where .= 'limit '.$begin.','.$get['page_size'];
         
     
        $sql = 'select model_name,mac_addr,time,log_desc,processed from DevLog where '.$where;
        $sql_count = 'select count(id) from DevLog where '.$where_count;
        $datas = Yii::$app->db->createCommand($sql)->queryAll();
        $count = Yii::$app->db->createCommand($sql_count)->queryAll();

        /********* 这里从数据库读取的没有翻译，若后期需要可以自行翻译，可参照系统日志的做法*******/
        foreach ($datas as $key => $val) {
        	$datas[$key]['index'] = $key+1;
        	if($val['processed'] == 1){
        		$datas[$key]['processed'] = 'Yes';
        	}else{
        		$datas[$key]['processed'] = 'No';
        	}
        }
        return  ['datas'=>$datas,'count'=>$count];
        }catch(\Expection $e){
	        return $e->getMessage();
	    }
    }

    /**
     * 功能：二级管理用户获得组名
     * 参数：$user 
     * 返回：
     *
     */
    public function management_get_groups($user){
        $group = GroupList::find()->select('group_list,user_name')->where(['management'=>$user])->asArray()->all();
    	$user = [];
    	$group_list = [];
    	$g_name = []; 
    	$re_user = [];
    	foreach ($group as $val) {
    		array_push($group_list,$val['group_list']);
    		$a = ['model_name'=>$val['group_list']];
    		array_push($g_name,$a);
    		if(!in_array($val['user_name'],$user)){
    			array_push($user,$val['user_name']);
    			array_push($re_user, ['user'=>$val['user_name']]);
    		}	
    	}
    	return [
    		'group_list'=>$group_list,
    		'user_name'=>$re_user,
    		'model_name'=>$g_name,
    	]; 
    }


    
    /**
     * 功能：根据条件获得警告日志信息
     * 参数：1）group_look-->查看者 
     *       3）mac_addr--> MAC地址 4)time_be-->起始时间
     *       5）time_end-->结束时间  6）pages-->哪一页开始
     *       7）page_size--> 每一页的显示条数   8) user_name-->二级用户
     *
     * 说明：因多条件的查询原生的sql条件写起来方便，框架带的查询条件不好
     *      组合使用，故改用原生的语句
     * 返回：array
     */
    public function management_get_alert_log($get){
    	try{
    	$where = '';
    	// 组查看者
    	if($get['group_look']){
    		$where .= 'user_name="'.$get['group_look'].'" and ';
    	}else{
    		$sanji_user = GroupList::find()->select('user_name')->where(['management'=>$get['username']])->asArray()->all();
    		$string = '';
    		$arr = [];
    		foreach ($sanji_user as $val) {
    			if(!in_array($val['user_name'],$arr)){
                     $string .= "'".$val['user_name']."'".',';
                     array_push($arr, $val['user_name']);
    			}
    		}
    		$index = strrpos($string,',');
    		$string = substr($string,0,$index);
    		$where .= "user_name in (".$string.") and ";
    	}
    	
    	//MAC地址
    	if($get['mac_addr']){
    		$where .= 'mac_addr="'.$get['mac_addr'].'" and ';
    	}
    	//时间
    	$time_end = date('Y-m-d',strtotime($get['time_end'])+24*3600);
    	$where .= "time >'".$get['time_be']."' and time <='".$time_end."' order by id desc ";

    	$where_count = $where;
        //起始行数
        $begin = ($get['pages']-1)*$get['page_size'];
        $where .= 'limit '.$begin.','.$get['page_size'];
    
        $sql = 'select model_name,mac_addr,time,log_desc,processed from DevLog where '.$where;
        $sql_count = 'select count(id) from DevLog where '.$where_count;
        $datas = Yii::$app->db->createCommand($sql)->queryAll(); 
        $count = Yii::$app->db->createCommand($sql_count)->queryAll();
         
        /********* 这里从数据库读取的没有翻译，若后期需要可以自行翻译，可参照系统日志的做法*******/
        foreach ($datas as $key => $val) {
        	$datas[$key]['index'] = $key+1;
        	if($val['processed'] == 1){
        		$datas[$key]['processed'] = 'Yes';
        	}else{
        		$datas[$key]['processed'] = 'No';
        	}
        }
        return  ['datas'=>$datas,'count'=>$count];
        }catch(\Expection $e){
	        return $e->getMessage();
	    }
        
    }




     /**
     * 功能：根据条件获得警告日志信息
     * 参数:
     *       3）mac_addr--> MAC地址 4)time_be-->起始时间
     *       5）time_end-->结束时间  6）pages-->哪一页开始
     *       7）page_size--> 每一页的显示条数   8) user_name-->二级用户
     *
     * 说明：因多条件的查询原生的sql条件写起来方便，框架带的查询条件不好
     *      组合使用，故改用原生的语句
     * 返回：array
     */
    public function user_get_alert_log($get){
        try{
        $where = '';
        // 组查看者
            $where .= 'user_name="'.$get['username'].'" and ';
        //MAC地址
        if($get['mac_addr']){
            $where .= 'mac_addr="'.$get['mac_addr'].'" and ';
        }
        //时间
        $time_end = date('Y-m-d',strtotime($get['time_end'])+24*3600);
        $where .= "time >'".$get['time_be']."' and time <='".$time_end."' order by id desc ";

        $where_count = $where;
        //起始行数
        $begin = ($get['pages']-1)*$get['page_size'];
        $where .= 'limit '.$begin.','.$get['page_size'];
    
        $sql = 'select model_name,mac_addr,time,log_desc,processed from DevLog where '.$where;
        $sql_count = 'select count(id) from DevLog where '.$where_count;
        $datas = Yii::$app->db->createCommand($sql)->queryAll(); 
        $count = Yii::$app->db->createCommand($sql_count)->queryAll();
         
        /********* 这里从数据库读取的没有翻译，若后期需要可以自行翻译，可参照系统日志的做法*******/
        foreach ($datas as $key => $val) {
            $datas[$key]['index'] = $key+1;
            if($val['processed'] == 1){
                $datas[$key]['processed'] = 'Yes';
            }else{
                $datas[$key]['processed'] = 'No';
            }
        }
        return  ['datas'=>$datas,'count'=>$count];
        }catch(\Expection $e){
            return $e->getMessage();
        }
        
    }


   
}
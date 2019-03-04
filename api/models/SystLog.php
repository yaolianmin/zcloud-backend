<?php

/**
 * 模型：系统日志模型
 * 作用：连接  系统控制器的使用函数库
 * 时间：2018-08-31 17:20
 * 作者：yaolianmin
 * 说明：此类中的注释请勿删除！！！！！！！！！！！
 */


namespace api\models;

use Yii;
use yii\db\ActiveRecord;
use api\models\User;


class SystLog extends ActiveRecord{
	//连接SysLog表
    public static function tableName(){
        return '{{SysLog}}';
    }




    /**
     * 功能：super用户获得系统日志信息
	 * 参数：sys_pession(需要显示的日志类型，有七个，默认全有) 
	 *      time_begin  需要查询的起始时间 （默认一个星期之前）
	 *      time_end    需要查询的结束时间 （默认是现在的时间）
	 *      page        需要从哪一页开始查 （默认从第一页）
	 *      page_size   每一页显示的多少   （默认每一页显示10条）
	 *      language    是哪种语言返回     （默认中文）
	 * 说明：此函数是超级用户查询系统日志，根据起止时间，每页显示的条数（默认每页10条）
	 *      从哪一页开始查，以及最后用哪种语言显示在前端
	 *      由于条件太多，使用框架自带的函数追加影响查询效率，故使用原生sql
	 * 
     * 返回：array()
     */
    public function super_get_syslog($where){
    	// 起止时间
	    $time_begin = $where['time_begin']; 
        $time_end = date('Y-m-d',strtotime($where['time_end'])+24*3600);//默认加上一天，不然看不到今天的日志
	    // 从哪条开始查
	    $begin = ($where['page']-1)*$where['page_size'];
	    // 查询长度
	    $length = $where['page_size'];
	    // 七个日志条件的查询
	    $condition = ''; 
        try{
	
	        if($where['sys_pession']){ //表示少于七个条件(log_id字段代表这个一个类型的条件)
	        	$condition = self::get_sql_where_infor($where['sys_pession']);
	        }
	        //查询数据库
	        if($condition){
	        	$sql_count = 'select count(id) from SysLog where log_time>="'.$time_begin.'" and log_time<="'.$time_end.'" and ('.$condition.')';
	        	$sql = 'select * from SysLog where log_time>="'.$time_begin.'" and log_time<="'.$time_end.'" and ('.$condition.') order by id desc limit '.$begin.','.$length;
	        }else{
	        	$sql_count = 'select count(id) from SysLog where log_time>="'.$time_begin.'" and log_time<="'.$time_end.'"';
	        	$sql = 'select * from SysLog where log_time>="'.$time_begin.'" and log_time<="'.$time_end.'"  order by id desc limit '.$begin.','.$length;
	        }
	     
	        $count  = Yii::$app->db->createCommand($sql_count)->queryAll();
	        $datas = Yii::$app->db->createCommand($sql)->queryAll();

	        //以下的操作对数据库的信息进行语言转换工作（有点烦）
            $data = self::get_log_desc_language_infor($datas,$where['language']); 
	        return [
	        	'count'=>(int)$count[0]['count(id)'],
	        	'data' =>$data
	        ];
	    }catch(\Expection $e){
	        return $e->getMessage();
	    }
    }

      
     


      /**
     * 功能：二级管理用户获得系统日志信息
	 * 参数：sys_pession(需要显示的日志类型，有七个，默认全有) 
	 *      time_begin  需要查询的起始时间 （默认一个星期之前）
	 *      time_end    需要查询的结束时间 （默认是现在的时间）
	 *      page        需要从哪一页开始查 （默认从第一页）
	 *      page_size   每一页显示的多少   （默认每一页显示10条）
	 *      language    是哪种语言返回     （默认中文）
	 * 说明：此函数是二级用户查询系统日志，根据起止时间，每页显示的条数（默认每页10条）
	 *      从哪一页开始查，以及最后用哪种语言显示在前端
	 *      由于条件太多，使用框架自带的函数追加影响查询效率，故使用原生sql
	 * 
     * 返回：array()
     */
    public function management_get_syslog($where){
    	// 起止时间
	    $time_end = $where['time_begin']; 
        $time_begin = date('Y-m-d',strtotime($where['time_end'])+24*3600);//默认加上一天，不然看不到今天的日志
	    // 从哪条开始查
	    $begin = ($where['page']-1)*$where['page_size'];
	    // 查询长度
	    $length = $where['page_size'];
	    // 七个日志条件的查询
	    $condition = '';
        try{
	
	        if($where['sys_pession']){ //表示少于七个条件(log_id字段代表这个一个类型的条件)
	        	$condition = self::get_sql_where_infor($where['sys_pession']);

	        }
	        //查询数据库
	        $san_ji_users = User::find()->select('UserName')->where(['management'=>$where['username']])->asArray()->all();
	        $arr = '';
	        if($san_ji_users){ // 二级用户的所有组管理成员，把数组转化成字符串
				foreach ($san_ji_users as $value) {
	        		$arr .= "'".$value['UserName']."'".',';
	        	}
	        	$arr = $arr."'".$where['username']."'";
	        }else{
	        	$arr = "'".$where['username']."'";
	        }
	        if($condition){
	        	$sql_count = 'select count(id) from SysLog where username in ('.$arr.') and log_time<="'.$time_begin.'" and log_time>="'.$time_end.'" and ('.$condition.')';
	        	$sql = 'select * from SysLog where username in ('.$arr.') and log_time<="'.$time_begin.'" and log_time>="'.$time_end.'" and ('.$condition.') order by id desc limit '.$begin.','.$length; 
	        }else{
	        	$sql_count = 'select count(id) from SysLog where username in ('.$arr.')  and log_time<="'.$time_begin.'" and log_time>="'.$time_end.'"';
	        	$sql = 'select * from SysLog where username in ('.$arr.') and log_time<="'.$time_begin.'" and log_time>="'.$time_end.'" order by id desc limit '.$begin.','.$length; 
	        }
	        //return $sql;
	        $count  = Yii::$app->db->createCommand($sql_count)->queryAll();
	        $datas = Yii::$app->db->createCommand($sql)->queryAll();
	        //以下的操作对数据库的信息进行语言转换工作（有点烦）
            $data = self::get_log_desc_language_infor($datas,$where['language']);
	        return [
	        	'count'=>(int)$count[0]['count(id)'],
	        	'data' =>$data
	        ];
	    }catch(\Expection $e){
	        return $e->getMessage();
	    }
    }





      /**
     * 功能：普通用户获得系统日志信息
	 * 参数：sys_pession(需要显示的日志类型，有七个，默认全有) 
	 *      time_begin  需要查询的起始时间 （默认一个星期之前）
	 *      time_end    需要查询的结束时间 （默认是现在的时间）
	 *      page        需要从哪一页开始查 （默认从第一页）
	 *      page_size   每一页显示的多少   （默认每一页显示10条）
	 *      language    是哪种语言返回     （默认中文）
	 * 说明：此函数是二级用户查询系统日志，根据起止时间，每页显示的条数（默认每页10条）
	 *      从哪一页开始查，以及最后用哪种语言显示在前端
	 *      由于条件太多，使用框架自带的函数追加影响查询效率，故使用原生sql
	 * 
     * 返回：array()
     */
    public function user_get_syslog($where){
    	// 起止时间
	    $time_end = $where['time_begin']; 
        $time_begin = date('Y-m-d',strtotime($where['time_end'])+24*3600);//默认加上一天，不然看不到今天的日志
	    // 从哪条开始查
	    $begin = ($where['page']-1)*$where['page_size'];
	    // 查询长度
	    $length = $where['page_size'];
	    // 七个日志条件的查询
	    $condition = '';
	    if($where['sys_pession']){
	    	$condition = self::get_sql_where_infor($where['sys_pession']);
	    }

	    if($condition){
	    	$sql_count = 'select count(id) from SysLog where username="'.$where['username'].'" and log_time<="'.$time_begin.'" and log_time>="'.$time_end.'" and ('.$condition.')';
	    	$sql = 'select * from SysLog where username="'.$where['username'].'" and log_time<="'.$time_begin.'" and log_time>="'.$time_end.'" and ('.$condition.') order by id desc limit '.$begin.','.$length;
	    }else{
	    	$sql_count = 'select count(id) from SysLog where username="'.$where['username'].'" and log_time<="'.$time_begin.'" and log_time>="'.$time_end.'"';
	    	$sql = 'select * from SysLog where username="'.$where['username'].'" and log_time<="'.$time_begin.'" and log_time>="'.$time_end.'" order by id desc limit '.$begin.','.$length;
	    }

	    //return $sql;
	    $count  = Yii::$app->db->createCommand($sql_count)->queryAll();
	    $datas = Yii::$app->db->createCommand($sql)->queryAll();
	    //以下的操作对数据库的信息进行语言转换工作（有点烦）
        $data = self::get_log_desc_language_infor($datas,$where['language']);
	    return [
	        'count'=>(int)$count[0]['count(id)'],
	        'data' =>$data
	    ];
    }


    
    /**
     * 功能：根据用户提交的提交赛选日志的条件，获得相应的的sql语言条件
     *
     * 说明：因为前端有七个日志的赛选条件，根据不同的条件过滤不同的sql中
     *       where语句部分，where条件语句越长，查询的效率越慢
     *       下面的字符串代表数据库字段
     *      'syslog_usermgt',      //用户管理类
	 *      'syslog_userloginout', //用户登录退出
	 *      'syslog_templetoper',  //模板操作
	 *      'syslog_downloadfile', //下载文件
	 *      'syslog_alert',        //系统警告
	 *      'syslog_taskmgt',      //任务管理
	 *      'syslog_devicemgt',    // 设备管理
     */
    public function get_sql_where_infor($sys_pession,$condition=''){	
        try{
	    if(in_array('syslog_usermgt',$sys_pession)){ // 表示有用户管理类

		 	$condition.= '0<=log_id and log_id<=9 or ';

		}
		if(in_array('syslog_userloginout',$sys_pession)){ // 表示有登录退出

		    $condition.= '10<=log_id and log_id<=19 or ';

		}
		if(in_array('syslog_templetoper',$sys_pession)){ // 表示有模板操作

		    $condition.= '20<=log_id and log_id<=39 or ';

		}
		if(in_array('syslog_downloadfile',$sys_pession)){ // 表示有下载文件

		    $condition.='40<=log_id and log_id<=49 or ';

		}
		if(in_array('syslog_alert',$sys_pession)){ // 表示有系统警告

		    $condition.='50<=log_id and log_id<=59 or ';

		}
		if(in_array('syslog_taskmgt',$sys_pession)){ // 表示有任务管理

		    $condition.= '100<=log_id and log_id<=199 or ';

		}
		if(in_array('syslog_devicemgt',$sys_pession)){ // 表示有设备管理

		    $condition.='300<=log_id and log_id<=399 or ';
		}
	    if($condition){
			$condition = substr($condition,0,strlen($condition)-3);//去除最后的 or  
		}
	    return $condition;
    }catch(\Expection $e){
        return $e->getMessage();
    }
    }



    /**
    * 功能：每次查询用户的日志，根据提交的语种，返回相应的语言信息
    * 说明：因为老版本的zoam日志，每一条日志的log_desc字段信息都需要翻译
    *      这样每种类型的日志都需要翻译一遍，太麻烦，但是在不改变数据库表的
    *      的结构类型下，只能仿照老版本zoam的translateSysLog.php里面的信息
    *      翻译. 但仍然需要多种情况
    *
    * 参数：直接总数据库查询的日志表数组 array()
    * 返回：array()
    */
    public function get_log_desc_language_infor($data,$language){
    	try{
    	if($data){
    		$log_id = self::get_log_id_information(); //获得对应的log_id数字信息
    		foreach($data as $key =>$infor){
            	$data[$key]['index'] = $key+1; // 默认的每条数据添加一个顺序
            	if( $data[$key]['log_id'] < 4){ //这里的信息属于 A 操作 B   t()函数为翻译语言 其命名处 BaseYii.php的502行

                	$index = strpos($data[$key]['log_desc'], ':');
                	$B = substr($data[$key]['log_desc'], $index+1);
                	$data[$key]['log_desc'] = $data[$key]['username'].' '.Yii::t('yii',$log_id[$data[$key]['log_id']],'',$language).$B;
                                       
            	}elseif($data[$key]['log_id'] == 4){

            		$data[$key]['log_desc'] = Yii::t('yii',$log_id[4],'',$language);

            	}elseif($data[$key]['log_id'] < 20){ // 这里的信息属于 A 操作

            		$data[$key]['log_desc'] = $data[$key]['username'].Yii::t('yii',$log_id[$data[$key]['log_id']],'',$language);
            						  
            	}elseif($data[$key]['log_id'] < 47){ //  这里的信息属于 操作 A

            		$index = strpos($data[$key]['log_desc'], ':');
                	$A = substr($data[$key]['log_desc'], $index);
                	$data[$key]['log_desc'] = Yii::t('yii',$log_id[$data[$key]['log_id']],'',$language).$A;
            						   
            	}elseif($data[$key]['log_id'] < 51){ // 这里的信息属于 A 操作

            		$data[$key]['log_desc'] = $data[$key]['username'].Yii::t('yii',$log_id[$data[$key]['log_id']],'',$language);
            						  
            	}elseif($data[$key]['log_id'] < 73){ // 这里的信息属于 操作 A

            		$index = strpos($data[$key]['log_desc'], ':');
                	$A = substr($data[$key]['log_desc'], $index);
                	$data[$key]['log_desc'] = Yii::t('yii',$log_id[$data[$key]['log_id']],'',$language).$A;

            	}elseif($data[$key]['log_id'] == 100){

                   $index = strpos($data[$key]['log_desc'], '[');
                   $mac_address = substr($data[$key]['log_desc'], $index,19);
                   $data[$key]['log_desc'] = Yii::t('yii',$log_id[100],'',$language).','.Yii::t('yii','Physical address','',$language).$mac_address; 

            	}elseif($data[$key]['log_id'] == 101){

            		$reg = "/Edit task: \[(.+)\] from \"(.+)\" to \"(.+)\"/i";
					preg_match($reg, $data[$key]['log_desc'], $matches); // $matches是 preg_match函数的截取值
					$data[$key]['log_desc'] = Yii::t('yii',$log_id[101],'',$language).','.$matches[2].Yii::t('yii','to','',$language).$matches[3].','.Yii::t('yii','Physical address','',$language).'['.$matches[1].']';
					
            	}elseif($data[$key]['log_id'] < 199){

            		$index_mac = strpos($data[$key]['log_desc'], ':');
            		$mac_address = substr($data[$key]['log_desc'], $index_mac,21);
            		$index_infor = strrpos($data[$key]['log_desc'], '"'); //找到第二个 “ 的位置
            		$length = $index_infor -($index_mac+19);
            		$data[$key]['log_desc'] =Yii::t('yii',$log_id[$data[$key]['log_id']],'',$language).','.Yii::t('yii','Physical address','',$language).$mac_address.' '.Yii::t('yii','describe','',$language).substr($data[$key]['log_desc'], $index_mac+23,$length-4);
					
            	}elseif($data[$key]['log_id'] == 200){

            		$reg = "/(.+) send mail to (.+) for changing password/i";
					preg_match($reg, $data[$key]['log_desc'], $matches);
					$data[$key]['log_desc'] = '【'.$matches[1].'】'.Yii::t('yii',$log_id[200],'',$language).'【'.$matches[2].'】';

            	}elseif($data[$key]['log_id'] == 201){

            		$reg = "/(.+) send mail to (.+) for changing password/i";
					preg_match($reg, $data[$key]['log_desc'], $matches);
					$data[$key]['log_desc'] = '【'.$matches[1].'】'.Yii::t('yii',$log_id[201],'',$language);

            	}elseif($data[$key]['log_id'] == 202){

            		$reg = "/(.+) change password/i";
					preg_match($reg, $data[$key]['log_desc'], $matches);
					$data[$key]['log_desc'] = '【'.$matches[1].'】'.Yii::t('yii',$log_id[202],'',$language);

            	}elseif($data[$key]['log_id'] == 301){

            		$reg = "/Device assigned group:\[(.+)\] assigned to the group of \[(.+)\] in (.+) by (.+)/i";
	                preg_match($reg, $data[$key]['log_desc'], $matches);
                  	$data[$key]['log_desc'] = Yii::t('yii','Group administrator','',$language).$matches[4].Yii::t('yii','Equipment','',$language).'['.$matches[1].']'.Yii::t('yii','Observer Group','',$language).$matches[3].Yii::t('yii','to','',$language).$matches[2].Yii::t('yii','group','',$language);

            	}elseif($data[$key]['log_id'] < 304){  //  A 操作 B

                    $A_index = strpos($data[$key]['log_desc'], 'by')+3;
                    $B_index = strpos($data[$key]['log_desc'], ':');
                    $data[$key]['log_desc'] = substr($data[$key]['log_desc'],$A_index).Yii::t('yii',$log_id[$data[$key]['log_id']],'',$language).substr($data[$key]['log_desc'],$B_index,20);
            	}
            }
            return $data;
    	}
        }catch(\Expection $e){
	        return $e->getMessage();
	    }
    }







    /**
     * 说明：根据数据库查询的log_id字段获得相应数字代表的信息
     *      这些的数字代表的信息基本是三种语言的翻译
     */
    public function get_log_id_information(){
        try{
    	return [
            	'0' => 'user add',
            	'1' => 'delete user',
            	'2' => 'user edit',
            	'4' => 'ZOAM Cloud System Send warning E-Mail.',
            	'10'=> 'user login',
            	'11'=> 'user logout',
            	'12'=>'user not exist',
            	'13'=>'password error',
            	'14'=>'dynamic code not match',
            	'20'=>'templet FAP add',
            	'21'=>'templet FAP delete',
            	'22'=>'templet FAP edit',
            	'23'=>'templet FAP-VAP edit',
            	'30'=>'templet AC add',
            	'31'=>'templet AC delete',
            	'32'=>'templet AC edit',
            	'33'=>'templet AC-TAP edit',
            	'34'=>'templet AC-TAP-VAP edit',
            	'40'=>'user download system log file',
            	'41'=>'download device log',
            	'42'=>'apply fw to device',
            	'43'=>'user download ACL file',
            	'44'=>'web UI download cfg templet file to device',
            	'45'=>'device download fw file',
            	'46'=>'device download templet file',
            	'47'=>'user download user info file',
            	'48'=>'user download app report file',
            	'49'=>'Device list download',
            	'50'=>'browse and handle offline alert',
            	'60'=>'boss device add',
            	'61'=>'boss device edit',
            	'62'=>'boss device delete',
            	'70'=>'templet VAC add',
            	'71'=>'templet VAC delete',
            	'72'=>'templet VAC edit',
            	'100'=>'New task',
            	'101'=>'Edit task',
            	'102'=>'Task success',
            	'103'=>'Task failed',
            	'200'=>'admin send mail to reviewer for changing password',
            	'201'=>'send mail to self for changing password',
            	'202'=>'user change password',
            	'301'=>'Device assigned group',
            	'302'=>'Device unassigned group',
            	'303'=>'Device information edit',
            ]; // 这个数组的数字代表 log_id字段
        }catch(\Expection $e){
            return $e->getMessage();
        }
    }


    /**
     * 功能：zoam系统的日志添加函数
     * 参数：user     用户名
     *      log_type 日志的类型
     *      log_id   定义的数字代表的含义，可以参看下面函数的$log_id数组
     *      level    用户操作的级别 有 （information warning error Nromal）
     *      desc     日志信息的具体描述 例如：xxx登录了用户zoam
     * 返回：true false
     */

    public function zoam_add_log($user,$log_type,$log_id,$level,$desc){
        /**定义log_id的数字含义 请勿删除下面的注释代码,每次添加需要在这里查询 log_id 数字的含义  ！！！！！！！！！！
           例如：登录zoam的操作  其log_id 是10  带表用户登录
            $log_id = [
                //user management
                'user add'=> 0,
                'delete user' => 1,
                'user edit' => 2,
                'ZOAM Cloud System Send warning E-Mail.' => 4,

                //login or logout
                'user login'=> 10,
                'user logout'=> 11,
                'user not exist'=>12,
                'password error'=>13,
                'dynamic code not match'=>14,

                //FAP Templet
                'templet FAP add'=>20,
                'templet FAP delete'=>21,
                'templet FAP edit'=>22,
                'templet FAP-VAP edit'=>23,

                //AC Templet
                'templet AC add'=>30,
                'templet AC delete'=>31,
                'templet AC edit'=>32,
                'templet AC-TAP edit'=>33,
                'templet AC-TAP-VAP edit'=>34,

                //firmware & config templet & other file operation
                'user download system log file'=>40,
                'download device log'=>41,
                'apply fw to device'=>42,
                'user download ACL file'=>43,
                'web UI download cfg templet file to device'=>44,
                'device download fw file'=>45,
                'device download templet file'=>46,
                'user download user info file'=>47,
                'user download app report file'=>48,
                'Device list download'=>49,

                //web alert and email alert
                'browse and handle offline alert'=>50,

                //boss device operation
                'boss device add'=>60,
                'boss device edit'=>61,
                'boss device delete'=>62,

                //VAC Templet
                'templet VAC add'=>70,
                'templet VAC delete'=>71,
                'templet VAC edit'=>72,

                //Task Mangement for v3.x
                'New task'=>100,
                'Edit task'=>101,
                'Task success'=>102,
                'Task failed'=>103,

                //user management for v 3.0.0+
                'admin send mail to reviewer for changing password'=>200,
                'send mail to self for changing password'=>201,
                'user change password'=>202,

                //device management for v 3.0.0
                'Device assigned group'=>301,
                'Device unassigned group'=>302,
                'Device information edit'=>303,
            ]; 


            //这个是log_type对应的日志类型
            $log_type = [
                    'device log' => 0, 
                    'system log' => 1,
                    'reserved' => 255
            ];

            //这里是log_level对应的日志级别
            $log_level = [
                'information' => 0, 
                'warning' => 1,
                'error' => 2,               
                'reserved' => 255
            ];
        */


       $time = date('Y-m-d H:i:s');
       $ip = $_SERVER["REMOTE_ADDR"]; //IP地址
       // 添加数据至数据库中
       $log = new SystLog();
       $log->username = $user;
       $log->log_type = $log_type;
       $log->log_id  =  $log_id;
       $log->log_source =  $ip;
       $log->log_time = $time;
       $log->log_level = $level;
       $log->log_desc = $desc;
       $log->save();
    }

}
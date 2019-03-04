<?php

/*
*模型：用户表模型
*作用：连接user表
*时间：2018-08-07
*/


namespace api\models;

use Yii;
use yii\db\ActiveRecord;
use api\models\SystLog;

class User extends ActiveRecord{

	//连接user表
    public static function tableName(){
        return '{{user}}';
    }


    /*
     * 功能：检测用户登录的信息,并把这个人的语言反馈给前端
     * 参数：$username $password
     * @return: array
     */
    public function check_user_login_information($username,$password){
    	$rel = User::find()->select('id,last_sel_language')->where(['UserName'=>$username,'Password'=>$password])->asArray()->one();
    	if($rel){
            // 0.0 这里需要添加一条进入系统的日志至数据库中
            SystLog::zoam_add_log($username,1,10,'information',$username.' login the ZOAM');

            return [
                    'state'=>0,
                    'language'=>$rel['last_sel_language']
                ];
    	}else{
    		return  [
                    'state'=>1,
                    'message'=>'用户名或密码错误，请重新输入'
                ];
    	}
    }

    /**
     * 功能：获得该用户的权限级别
     * 参数：$username
     * 返回：string
     * 说明：因使用rest模块，每次请求的保存的session都无法保存，
     *      在下一次获取该session值是，没有保存，故只能每次请求前
     *      查询该用户的权限
     */
    public function get_user_power_by_username($username){
    	if($username){
       		$user = User::find()->select('Power')->where(['UserName'=>$username])->asArray()->one();
       		if(isset($user['Power'])){
       			return $user['Power'];
       		}else{
       			return '没有此用户的信息';
       		}	
    	}
    }


    /**
     * 功能：超级用户获得其他用户的信息
     * 参数： $page ,$page_size
     * 返回：array 
     */
    public function super_get_all_user($page,$page_size=10){
    	$offset = ($page-1)*$page_size; //需要从哪行开始查询
    	$where = User::find()->where(['<','Power',10]);
    	$count = $where->count(); //总记录数
    	$all_user =$where->select('id,UserName,Power,management,email')
    	                ->offset($offset)
    	                ->limit($page_size)
    	                ->asArray()
    	                ->all();
        $return = User::handle_select_data($all_user,$count);
    	return $return;
    }


    /**
     * 功能：N/A用户获得其他用户信息
     * 参数：$name $page $page_size
     * 返回：array
     */
    public function NA_get_all_user($name,$page,$page_size=10){
    	try{
    		$offset = ($page-1)*$page_size; //需要从哪行开始查询
		    $where = User::find()->where(['or','UserName=:name','management=:names'],['name'=>$name,'names'=>$name]);
		    $count =$where->count(); //总记录数
		    $all_user = $where->select('id,UserName,Power,management,email')
		    	                ->offset($offset)
		    	                ->limit($page_size)
		    	                ->asArray()
		    	                ->all();
		    $return = User::handle_select_data($all_user,$count);
		    return $return;
    	}catch(\Exception $e){
    		return $e->getMessage(); //异常的信息
    	}	
    }


    /**
     * 功能：普通用户查询自己的信息
     * 参数：$name
	 * 返回：array
     */
    public function get_self_infor($name){
    	$all_user = User::find()->select('id,UserName,Power,management,email')
    	                    ->where(['UserName'=>$name])
    	                    ->asArray()
    	                    ->all();
        $return = User::handle_select_data($all_user,1);
    	return $return;
    }

    /**
     * 功能：对不同级别的用户查询出的数据进行梳理，可直接传递给前端
     * 参数：$data,$count
     * 返回：array
     */
    public function handle_select_data($data,$count){
    	foreach ($data as $key => $val) {
    		$data[$key]['index'] = $key+1;
    		if( $val['Power'] != 1){
    			$data[$key]['management'] = 'N/A';
    		}
    	}
    	$returns = [
            'count'=>$count,
            'data'=>$data
    	];
    	return $returns;
    }


    /**
     * 功能：超级用户搜索操作
     * 参数：$name $page $pagesize
     * 返回：array
     */
    public function super_search_user_by_username($name,$page,$pagesize){
        try{
            $offset = ($page-1)*$pagesize; //需要从哪行开始查询
            $where = User::find()->where(['like','UserName',$name]);
            $count = $where->count();
            $all_user = $where->select('id,UserName,Power,management,email')
                                    ->offset($offset)
                                    ->limit($pagesize)
                                    ->asArray()
                                    ->all();
            $return = User::handle_select_data($all_user,$count);
            return $return;
        }catch(\Exception $e){
            return $e->getMessage();
        }  
    }

    /**
     * 功能：N/A用户搜索操作
     * 参数：$name $search_name $page $pagesize
     * 说明：此查询条件多，适合使用原生sql查询(不可以模糊查询自己的信息)
     * 返回：array
     */
    public function NA_search_user($name,$search_name,$page,$pagesize){
        try{
            $offset = ($page-1)*$pagesize; //需要从哪行开始查询
            $sql = 'select id,UserName,Power,management,email from user where management="'.$name.'" and UserName like "%'.$search_name.'%" limit '.$offset.','.$pagesize;
            $data  = Yii::$app->db->createCommand($sql)->queryAll();
            $count = count($data);
            if(!$count){
                return ''; 
            }
            $return = User::handle_select_data($data,$count);
            return $return;
        }catch(\Exception $e){
            return $e->getMessage();
        }  
    }


    /**
     * 功能：根据用户名获得所有的NA用户
     * 参数：$name
     * 返回：array
     */
    public function get_all_nas($name){
        $power = User::get_user_power_by_username($name);
        if($power == 15){
            $nas = User::find()->select('UserName,id')->where(['Power'=>2])->asArray()->all();
            return $nas;
        }else{
            return '2'; // 2 代表不是超级管理的用户
        }  
    }

    /**
     * 功能：检测添加用户时表单提交的数据是否合理
     * 参数：$data
     * 返回： string
     */
    public function check_user_information($data){
        // 1.0 用户名的检测
        if(!$data['username']){
            return '用户名不能为空';
        }
        if(strlen($data['username'])>12){
            return '用户名不能超过12位字符';
        }
        $rel = User::find()->select('id')->where(['UserName'=>$data['username']])->asArray()->one();
        if($rel){
            return '该用户名已存在，请重新填写用户名';
        }
        // 2.0 密码的检测
        if(strlen($data['password'])<16 || strlen($data['password'])>22){
            return '密码为6-12位数字、字母';
        }
        if(!preg_match('/^[a-zA-Z0-9]{12,22}$/',$data['password'])){
            return '密码为6-12位数字、字母';
        }
        // 3.0 用户级别的检测
        if(!$data['userlevel']){
            return '用户级别不能为空';
        }
        if(($data['userlevel'] !=1) && !$data['usermanagement']){
            return '组管理员不能为空';
        }
        // 4.0 检测用户的邮箱是否为空或者被注册过
        if(!preg_match('/^[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*\.[a-zA-Z0-9]{2,6}$/',$data['Email'])){
            return '邮箱格式不正确';
        }
        $rel = User::find()->select('email')->where(['email'=>$data['Email']])->asArray()->one();
        if($rel){
            return '该邮箱已被注册使用过';
        }
        return 'success';
    }


    /**
     * 功能：新增用户信息至数据库中
     * 参数：$data   $action_user(操作的用户人)
     * 返回：string
     */
    public function add_new_user($data,$action_user){
        try{
            // 1.0 初始化数据
            $password = substr(trim($data['password']),10);
            $password = hash('sha256',$password);
            $power = ($data['userlevel'] == 1)?2:1;
            if($power == 1){ //普通用户
                $permission_page = '[]';
            }else{
                $permission_page = json_encode($data['checkbox']);
            }
            // 2.0 存储数据至user表中
            $transaction= Yii::$app->db->beginTransaction(); // 开启数据库的事务
            $user = new User();
            $user->UserName = $data['username']; //用户名
            $user->Password = $password;   //密码
            $user->Power =  $power;  //权限
            $user->management = $data['usermanagement']; //组管理员
            $user->email =  $data['Email'];         //电子邮箱
            $user->last_sel_language = 'English';    //语言种类
            $user->home_refresh_interval = '5';   
            $user->limit_login = '0';
            $user->limit_login_time = '0'; //限制登录的时间
            $user->syslog_usermgt = '1';   //用户管理日志（是否需要显示，1代表显示）
            $user->syslog_userloginout = '1';  //用户进退系统日志（1代表需要记录）
            $user->syslog_templetoper = '1'; //模板操作日志
            $user->syslog_downloadfile = '1';//下载文件日志
            $user->devlog_general = '1'; //一般设备日志
            $user->devlog_wired = '1';   //有线设备日志
            $user->devlog_wireless = '1';   //无线设备日志
            $user->devlog_sysresourcemonitor = '1';  //系统资源监测日志
            $user->devlog_wiredmonitor = '1';    //有线设备检测日志
            $user->devlog_wirelessmonitor = '1';    //无线设备检测日志
            $user->devlog_networkfailurecheck = '1'; //设备网络失败检测
            $user->permission_page = $permission_page;
            $user->real_name = $data['re_name'];  //真是姓名
            $user->landline_telephone = $data['landline']; //固定电话（0550-2878685）
            $user->mobile_phone = $data['phone'];      //手机号码
            $user->contact_address = $data['address']; //住址
            $user->remarks = $data['remarks'];  //用户备注
            $user->save();

            // 0.0 这里需要添加一条新增用户的日志至数据库中
            SystLog::zoam_add_log($action_user,1,0,'information',$action_user.' add user:'.$data['username']);

            $transaction->commit();//提交事务结束
            return 'success';
        }catch(\Exception $e){
           $transaction->rollback();//如果操作失败, 数据回滚
           return $e->getMessage();
        }    
    }



    /**
     * 功能：根据用户的id获得该用户的详细信息
     * 参数：$uid
     * 返回：array
     * 说明：返回的数据$return['state'] ==1 代表数据库中没有
     *       查询到该用户的信息
     */
    public function get_one_user_inforamtion($uid){
        $infor = User::find()->select('UserName,Power,management,email,permission_page,real_name,landline_telephone,mobile_phone,contact_address,remarks')->where(['id'=>$uid])->asArray()->one();
        if($infor){
            //过滤数据库中的 '\' 字符
            $infor['permission_page'] = json_decode($infor['permission_page'],true);
            if($infor['Power'] == 2){
                $infor['jibie'] = '组管理者';
            }else{
                $infor['jibie'] = '组查看者';
            }
            return ['state'=>0,'data'=>$infor];
        }else{
            return ['state'=>1];
        }
    }


    /**
     * 功能：修改表单的提交，验证数据的合理性
     * 参数：$form (表单内容)  $name(操作者的用户名)
     * 返回：string
     */
    public function check_update_form_information($form,$name){
        if(!$name)
            return '请重新登录再操作';
        //检测用户名是否存在(防止在修改表单的时候，恶意的修改用户名)
        $rel = User::find()->select('id')->where(['UserName'=>$form['username']])->asArray()->one(); 
        if(!$rel){
            return '您修改的用户不存在';
        }
        //验证用户名的合理性(判断操作的权限是否大与修改者的权限)
        $caozuo_power = User::find()->select('Power')->where(['UserName'=>$name])->asArray()->one();
        $xiugai_power = User::find()->select('Power')->where(['UserName'=>$form['username']])->asArray()->one();
        if($caozuo_power['Power'] < $xiugai_power['Power']){ 
            return '您无权修改该用户的信息';
        }
        //检测用户的邮箱是否为空
        if(!preg_match('/^[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*\.[a-zA-Z0-9]{2,6}$/',$form['Email'])){
            return '邮箱格式不正确';
        }
        return 'success';
    }


    /** 
     * 功能：修改表单的数据至数据库中
     * 参数：$form (表单的数据) $action_user(操作的用户人)
     * 返回： string
     */
    public function update_user_information($form,$action_user){
        try{ 
            $transaction= Yii::$app->db->beginTransaction(); // 开启数据库的事务
            $permission = json_encode($form['checkbox']); //因为数据中携带 '\' 符号
            User::updateAll([
                'email'=>$form['Email'],
                'permission_page'=>$permission,
                'real_name'=>$form['re_name'],
                'landline_telephone'=>$form['landline'],
                'mobile_phone'=>$form['phone'],
                'contact_address'=>$form['address'],
                'remarks'=>$form['remarks']],['UserName'=>$form['username']]);
            
            // 0.0 这里需要添加一条修改日志至数据库中
            SystLog::zoam_add_log($action_user,1,2,'information',$action_user.' user edit:'.$form['username']);

            $transaction->commit();//提交事务会真正的执行数据库操作
            return 'success';
        }catch( \Exception $e){
            $transaction->rollback();//如果操作失败, 数据回滚
            return $e->getMessage();  
        }
    }

   
    /**
     * 功能：判断用户是否有权限删除该用户
     * 参数 $name(操作者名)  $uid(删除者的id)
     * 返回：string
     */ 
    public function judge_power($name,$uid){
        if($name&&$uid){
            $caozuo_power = User::find()->select('Power')->where(['UserName'=>$name])->asArray()->one();
            $delete_power = User::find()->select('Power')->where(['id'=>$uid])->asArray()->one();
            if($caozuo_power['Power'] <= $delete_power['Power']){ 
                return false;
            }else{
                return 'success';
            } 
        } 
    }


    /**
     * 功能：根据用户的id删除该用户
     * 参数：$uid(用户的id)   $action_user(操作的用户人)
     * 返回：string
     */
    public function delete_user_by_id($uid,$action_user){
        if($uid){
            $user = User::find()->select('username')->where(['id'=>$uid])->asArray()->one();
            User::deleteAll(['id'=>$uid]);

            // 0.0 这里需要添加一条删除日志值数据库中
            SystLog::zoam_add_log($action_user,1,1,'information',$action_user.' delete user:'.$user['username']);
            return true; 
        } 
    }



    /**
     * 功能：获得该用户的组名信息
     * 参数：用户名$user
     * 返回：array
     */
    public function get_user_group_name($user){
        $infor = User::find()->select('usr_grp_sel_mgt,usr_grp_sel_reviewer,usr_grp_sel_group')->where(['UserName'=>$user])->asArray()->one();
        return $infor;
    }












}




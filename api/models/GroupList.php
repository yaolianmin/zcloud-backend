<?php

/*
*模型：组管理表模型
*作用：连接user表
*时间：2018-09-25
*/


namespace api\models;

use Yii;
use yii\db\ActiveRecord;
use api\models\SystLog;
use api\models\User;

class GroupList extends ActiveRecord{

	//连接GroupList表
    public static function tableName(){
        return '{{GroupList}}';
    }

    

    /**
     * 功能：super用户获得组管理者
     * 返回：array
     */
    public function super_get_group_managements($user){
    	$groups = GroupList::find()->select('management')->asArray()->all();
    	// 去除重复的组管理者
    	$arr_return = [];
    	$arr_group = [];
    	$i = 0;
    	foreach ($groups as $val) {
    		if(!in_array($val['management'],$arr_group)){
    			array_push($arr_group, $val['management']);
    			$arr_return[$i] = ['index'=>$i+1,'management'=>$val['management']];
    			$i++;
    		}
    	}
    	/** 不知何原因，网站头部的组名在这个请求中冲突，若刷新网页，
    	 *  此时的组名为空，故需要填充组名
    	 */
    	$infor = User::get_user_group_name($user);
    	return [
    		'manages'=>$arr_return,
    		'group'=>$infor,
    		'user_poewer'=>111,
    	];
    }


    /**
     * 功能：二级用户获得组管理者
     * 返回：array
     */
    public function self_get_group_managements($user){  	
    	/** 不知何原因，网站头部的组名在这个请求中冲突，若刷新网页，
    	 *  此时的组名为空，故需要填充组名
    	 */
    	$infor = User::get_user_group_name($user);
    	return [
    		'manages'=>[0=>['index'=>1,'management'=>$user]],
    		'group'=>$infor,
    		'user_poewer'=>111,
    	];
    }


    /**
     * 功能：三级用户获得组管理者
     * 返回：array
     */
    public function sanji_get_group_managements($user){ 

         $group = User::find()->select('management')->where(['UserName'=>$user])->asArray()->one(); 	
    	/** 不知何原因，网站头部的组名在这个请求中冲突，若刷新网页，
    	 *  此时的组名为空，故需要填充组名
    	 */
    	$infor = User::get_user_group_name($user);
    	return [
    		'manages'=>[0=>['index'=>1,'management'=>$group['management']]],
    		'group'=>$infor,
    		'user_poewer'=>0,
    	];
    }



    /**
     *功能：获得某个二级用户的所有组查看者和每个组查看者的组名
     *参数：二级用户 $user
     *返回：array
     *
     */
    public function get_group_name($user){
    	$group_name = GroupList::find()->select('group_list,user_name')->where(['management'=>$user])->asArray()->all();
    try{
    	if($group_name){
    		$arr_look = [];
    		$arr_looks = [];
    		$arr_name = [];
    		foreach ($group_name as $val) {
    			if(!in_array($val['user_name'],$arr_look)){
						array_push($arr_look,$val['user_name']);
						array_push($arr_looks,['value'=>$val['user_name']]);
    			}
    			if(!isset($arr_name[$val['user_name']])){
    				$arr_name[$val['user_name']] = [];
    			}
    			array_push($arr_name[$val['user_name']],$val['group_list']);
    		}
    		return [
    			'management'=>$user,
    			'group_look'=>$arr_looks,
    			'group_name'=>$arr_name
    		];
    	
    	}else{
    		return '';
    	}
    }catch( \Exception $e){
        return $e->getMessage();  
    }
    	return $group_name;
    }

 
    /**
     * 功能：根据组查看者获得自己的所有组名
     * 参数：组查看者 $user
     * 返回： array
     */
    public function get_group_name_by_looker($user){
    	$rel = GroupList::find()->select('group_list')->where(['user_name'=>$user])->asArray()->all();
    	if($rel){
    		$arr_return = [];
    		$i = 0;
    		foreach ($rel as $val) { // 此循环主要是更换键名，直接显示在前端
    			$arr_return[$i] = ['value'=>$val['group_list']];
    			$i++;
    		}
    		return $arr_return;
    	}
    }


 
    /**
     * 功能： 添加组名至数据库中
     * 参数： $post(前端提交过来的数据)
     * 返回：错误信息或者成功信息
     *
     */
    public function add_group_name($post){
    	try{
    		// 开启数据库的事务
            $transaction= Yii::$app->db->beginTransaction(); 

    		$group = new GroupList();
	    	$group->group_list = $post['group_name'];
	    	$group->user_name = $post['group_look'];
	    	$group->management = $post['management'];
	    	$group->group_info = $post['remarks'];
	    	$group->located_style = 1;
	    	$group->save();

	    	$transaction->commit();//提交事务结束
            return '添加成功';
    	}catch(\Exception $e){
    		$transaction->rollback();//如果操作失败, 数据回滚
            return '数据库出错，添加失败';
    	}
    }


    /**
     * 功能：组查看者获得自己的组名
     * 参数：$user(组查看者的用户名）
     * 返回：array
     *
     */
    public function group_look_get_groupname($user){
    	$rel = GroupList::find()->select('group_list')->where(['user_name'=>$user])->asArray()->all(); return $rel;
    	if($rel){
    		$arr = [];
    		foreach ($rel as $val) {
    			array_push($arr, ['value'=>$val['group_list']]);
    		}
    		return $arr;
    	}else{
    		return '';
    	}
    }



    /**
     * 功能：修改组管理的组名
     * 参数：前端提交的数据 操作者 组管理员 组查看者 组名
     * 返回 array
     */
    public function update_group_to_user($post){
    	$rel = User::updateAll(['usr_grp_sel_mgt'=>$post['management'],'usr_grp_sel_reviewer'=>$post['look'],'usr_grp_sel_group'=>$post['group_n']],['UserName'=>$post['self']]);
    	return 'success';
    }


    /**
     * 功能：修改组名的信息
     * 参数：组名的id 组名 组名备注
     * 返回：string
     *
     */
    public function update_group_infor($post){
    	$rel = GroupList::updateAll(['group_list'=>$post['groupnames'],'group_info'=>$post['remark']],['id'=>$post['group_id']]);
    	return 'success';
    }


    /**
     * 功能：检测将要删除的组名是否是组管理已选中状态
     * 参数：
     * 返回：yes or no
     *
     */
    public function check_group_is_selected($post){
    	$infor = User::find()->select('usr_grp_sel_mgt,usr_grp_sel_reviewer,usr_grp_sel_group')->where(['UserName'=>$post['user']])->asArray()->one();
    	if(($infor['usr_grp_sel_mgt'] == $post['mgter'])&&($infor['usr_grp_sel_reviewer'] == $post['groupers'])&&($infor['usr_grp_sel_group'] == $post['group_names'])){
    		return 'yes';
    	}else{
    		return 'no';
    	}
    }


}
<?php

/*
*模型：任务表链接模型
*作用：连接Task_schedule表
*时间：2019-01-23
*/

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;

class Task_schedule extends ActiveRecord{
	//创建一个数据模型链接数据表
	public static function tableName(){
        return 'Task_schedule';
    }
	

	/*
	 * 功能：重启 插入数据至数据库中
	 * 说明：该方法的参数均是数据的对应的字段
	 * 返回：
	 *
	 */
    public function insert_data_to_task($where=array()){

    	// 插入数据至数据库中
        $task_schedule = new Task_schedule();
        $task_schedule->action_id = $where['action_id']; 
        $task_schedule->group_name = $where['group_name']; 
        $task_schedule->reviewer = $where['reviewer']; 
        $task_schedule->username = $where['username']; 
        $task_schedule->device_mac = $where['device_mac']; 
        $task_schedule->action_filename = $where['action_filename']; 
        $task_schedule->task_type = $where['task_type']; 
        $task_schedule->description = $where['description'];    // 任务的名称
        $task_schedule->execute_time = $where['execute_time'];  //任务执行时间
        $task_schedule->date_created = $where['date_created'];  //数据的创作时间
        $task_schedule->status = $where['status'];              //running 还是 scheduling
        if(isset($where['ftp_server_params'])){
            $task_schedule->ftp_server_params = $where['ftp_server_params'];  
        }
        $task_schedule->save();

    }


   
	
	
}



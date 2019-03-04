<?php

namespace frontend\models;
use Yii;
use yii\base\Model;
use frontend\models\Log;
use frontend\models\Login;


/**
* 作用：这里添加公共方法，供其他模块调用。
*
*
*
*  @copyright Copyright (c) 2017 – www.zhiweiya.com
*  @author yaolianmin
*  @version 1.0 2017/10/8 10:06
*/

class Common extends Model{

      /**
      *功能：切换语言
      *参数：$language
      *@return
      */
      public function change_language($language=''){
          if($language){
              Yii::$app->session['language']=$language; //将语言修改至session中
              return 'success';               
          }  
      }
  
      /**
      *功能：退出系统，销毁session值
      *@return
      */
      public function exit_system(){

              $session = Yii::$app->session->destroy();
              return 'success';     
      }


    /**
    * 根据参数添加一条日志信息 
    * 参数 日志级别 日志类型 行为 信息 模块 
    * @return true
    */
    public function add_log($log_level,$log_type,$action,$info,$item=''){
        //定义日志级别和类型
        $log_number = [
            //以下三个是日志级别
            1   => 'op_log_type', //日志操作    
            2   => 'warning_log_type', //日志警告    
            3   => 'prompt_log_type', //日志提示    
            
            //以下六个是日志类型
            4   => 'project_log', //日志项目    
            5   => 'user_log', //日志用户    
            6   => 'file_log', //日志文件    
            7   => 'device_log', //日志机种    
            8   => 'system_log', //日志系统    
            9   => 'upgrade_log' //日志升级    
        ];
    
        //用户管理模块信息
        /**
        * add 名字 user             例如 add zhangsan user
        * update 名字 information   例如 update zhangsan information
        * delete 名字 user          例如 delete zhangsan user
        */

        //对应的名称 这里到时需要更改成英文
        $chinese_names = [
          'op_log_type'      => 'operation', //操作
          'warning_log_type' => 'warning', //警告
          'prompt_log_type'  => 'reminder', //提示
          'project_log'      => 'project', //项目
          'user_log'         => 'user', //用户
          'file_log'         => 'file', //文件
          'device_log'       => 'machine', //机种
          'system_log'       => 'system', //系统
          'upgrade_log'      => 'upgrade'  //升级
        ];
        $user_name = \Yii::$app->session['user_name'];
        if($user_name){
          //查询此用户的日志设定
           $log_set_option = Login::find()->select([$log_number[$log_level],$log_number[$log_type],'power'])->where(['user_name' =>$user_name])->one();
           //判断用户日志设定是否需要存储 
           if($log_set_option->$log_number[$log_level] && $log_set_option->$log_number[$log_level]) {

                $time = time();//获得当前日志操作时间
                $IP = $_SERVER['REMOTE_ADDR'];//获得当前日志操作的IP
                $log_l = $chinese_names[$log_number[$log_level]]; //日志级别
                $log_t = $chinese_names[$log_number[$log_type]]; //日志类型
      

                //存入日志表当中
                $log_insert = new Log();
                $log_insert->user_name = $user_name;
                $log_insert->log_level = $log_l;
                $log_insert->log_type  = $log_t;
                $log_insert->login_ip  = $IP;
                $log_insert->log_time  = $time;
                $log_insert->action_info  = $action;
                $log_insert->item_info = $item;
                $log_insert->info      = $info;
                $log_insert->log_power = $log_set_option->power;
                $log_insert->save();

                return true;
           } 
         }
    }


    /**
    * 检测用户日志数量是否超过 1000条
    * 并导出、删除超过的部分
    * 参数：$user_name
    */  
    public function check_log_numbers($user_name){
        if($user_name){
          $count = Log::find()->where(['user_name' =>$user_name])->count();
          if($count>=1000){//代表超过规定数量

              $log_info = Log::find()->where(['user_name' =>$user_name])
                          ->orderBy('log_time asc')
                          ->limit(990)
                          ->asArray()
                          ->all();

              $filename = '../../../downloadExcel/'.$user_name.time().'.csv';
              $upload = fopen($filename,'w');
              $ss = '';
              foreach ($log_info as  $val) {   
                  $ss = $val['user_name'].'    '.$val['log_level'].'   '.$val['log_type'].'    '.$val['login_ip'].'      '.date('Y-m-d H:i:s',$val['log_time']).'       '.$val['action_info'].' '.$val['info'].' '.$val['item_info']."\n";
                  fwrite($upload, $ss);
              }

              $last_time = $log_info[979]['log_time'];//找到第980条日志所在的时间
              //删除数据库规定数量之前的日志
              Log::deleteAll('log_time < :log_time AND user_name = :user_name',[':log_time' =>$last_time,':user_name' =>$user_name]);
             
          }
        }
            
    }

}
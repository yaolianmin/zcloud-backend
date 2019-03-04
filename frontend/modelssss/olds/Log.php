<?php

namespace frontend\models;
use yii\db\ActiveRecord;
use frontend\models\Login;
use yii\data\Pagination;

/**
* Log 模型
* 日志管理模块
* 功能：供日志控制器调用
*
*
*
*
*  @copyright Copyright (c) 2017 – www.zhiweiya.com
*  @author yaolianmin
*  @version 1.0 2017/10/26 10:06
*/

class Log extends ActiveRecord{


      /**
      * 链接数据库表
      */
      public static function tableName(){
         return '{{log_management}}';
      }



      /**
      * 根据表单提交的数据修改日志设定
      * 参数
      * @return true  
      */   
       function update_log_type($op_log_type,$warning_log_type,$prompt_log_type,$project_log,$user_log,$file_log,$device_log,$system_log,$upgrade_log,$log_save_time){
            $uesult = Login::updateALL(['op_log_type' =>$op_log_type,'warning_log_type' =>$warning_log_type,'prompt_log_type' =>$prompt_log_type,'project_log' =>$project_log,'user_log' =>$user_log,'file_log' =>$file_log,'device_log' =>$device_log,'system_log' =>$system_log,'upgrade_log' =>$upgrade_log,'log_save_time' =>$log_save_time,],['user_name' =>\Yii::$app->session['user_name']]);
            if($uesult){
               return true;
            }
       }



      /**
      * 根据用户名查找日志设定选项
      * 参数：$user_name
      * @return
      */
      function get_userlog_type_by_username($user_name){
          if($user_name){

              $info = Login::findOne(['user_name' =>$user_name]);

              if($info){
                   return $info;
              }
          }   
      }

      /**
      * 根据用户名查找所有符合的数据
      * 参数：$condition
      * @return 资源
      */
      public function get_all_information_by($cond){

          if($cond){
              $resouce = self::find()->where(['user_name'=>$cond])->orderBy('log_time desc')->all();
              return $resouce;
          }
      }

   




      /**
      * 根据 $cond 查找用户日志信息
      * 参数 
      * @return 
      */
      public function get_log_info_by_condition($condition){
          if($condition[0]){
              $list = Log::find()->where([$condition[1] =>$condition[0]])
                      ->orderBy('log_time desc')
                      ->offset(self::get_log_pages_by_condition($condition)->offset)
                      ->limit(self::get_log_pages_by_condition($condition)->limit)
                      ->asArray()
                      ->all();
              return $list;
          }
      }

     /**
     * 根据$cond 查询总记录数获得分页
     * 参数 
     * @return 
     */
      public function get_log_pages_by_condition($condition){
          if($condition[0]){
              $count = Log::find()->where([$condition[1] =>$condition[0]])->count();
              $page = new Pagination(['totalCount' =>$count, 'pageSize' => 10]);
              return $page;
          }  
      }

     /**
     * 根据信息导出符合条件的日志所有内容
     * 参数 $name $page
     * @return recourse
     */
     public function get_all_logs_by_condition($condition){
          $all_info = Log::find()->where([$condition[1] =>$condition[0]])
                      ->orderBy('log_time desc')
                      ->asArray()
                      ->all();
          return $all_info;
     } 






      /**
      * 根据日志类型和用户名查找
      * 参数 $name $log_type $list
      * @return 
      */
      public function get_log_info_by_conditions($condition){
          $list = Log::find()->where([$condition[2] => $condition[1],$condition[3] =>$condition[0]])
                  ->orderBy('log_time desc')
                  ->offset(self::get_log_pages_by_conditions($condition)->offset)
                  ->limit(self::get_log_pages_by_conditions($condition)->limit)
                  ->asArray()
                  ->all();
          return $list;
      }
 
      /**
      * 根据日志类型和用户名查找总记录数并分页
      * 参数 $name $log_type $list $count
      * @return                                   
      */                                          
      public function get_log_pages_by_conditions($condition){

          $count = Log::find()->where([$condition[2] => $condition[1],$condition[3] =>$condition[0]])->count(); 

          $page = new Pagination(['totalCount' =>$count, 'pageSize' => 10]); 

          return $page;  
      }
    
     /**
     * 根据信息导出符合条件的日志所有内容
     * 参数 $name $log_type $list $count
     * @return recourse
     */
     public function get_all_logs_by_conditions($condition){
          $all_info = Log::find()->where([$condition[2] => $condition[1],$condition[3] =>$condition[0]])
                      ->orderBy('log_time desc')
                      ->asArray()
                      ->all();
          return $all_info;
     } 






    /**
    * 根据日志级别、日志类型、用户名查找
    * 参数 $name $log_level $log_type $list $condition
    * return 
    */                                         
    public function get_log_info_by_level_type($condition){
        $list = Log::find()->where(['log_level'=>$condition[1],'log_type'=>$condition[2],$condition[3] =>$condition[0]])
                ->orderBy('log_time desc')
                ->offset(self::get_log_pages_by_level_type($condition)->offset)
                ->limit(self::get_log_pages_by_level_type($condition)->limit)
                ->asArray()
                ->all();
        return $list;
    }
    
    /**
    * 根据日志级别、日志类型、用户名查找总记录数并分页
    * 参数 $name $log_level $log_type $list $count $condition
    * return 
    */
    public function get_log_pages_by_level_type($condition){
        $count = Log::find()->where(['log_level'=>$condition[1],'log_type'=>$condition[2],$condition[3] =>$condition[0]])->count(); 
        $page = new Pagination(['totalCount' =>$count, 'pageSize' => 10]); 
        return $page;  
    }

    /**
    * 根据信息导出符合条件的日志所有内容
    * 参数 $name $log_level $log_type $list $count $condition
    * @return recourse
    */
    public function get_all_logs_by_level_type($condition){
        $all_info = Log::find()->where(['log_level'=>$condition[1],'log_type'=>$condition[2],$condition[3] =>$condition[0]])
                      ->orderBy('log_time desc')
                      ->asArray()
                      ->all();
        return $all_info;
    } 
  


}
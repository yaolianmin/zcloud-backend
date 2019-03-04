<?php

namespace frontend\models;
use Yii;
use yii\db\ActiveRecord;
use frontend\models\Dev_user;
use yii\data\Pagination;
use frontend\models\File_management_models;
use frontend\models\File_management_history_version;

/**
* 机种管理模块模型
* 功能：供机种控制器调用
*
*
*
*
*  @copyright Copyright (c) 2017 – www.zhiweiya.com
*  @author yaolianmin
*  @version 1.0 2017/10/26 10:06
*/

class Decvice extends ActiveRecord{

    /**
    * 链接数据库表
    */
    public static function tableName(){
    	  return '{{dev_management}}';
    }



    //超级用户显示最新机种
    function get_new_dev($get){
    	  $list = Decvice::add_conditions($get);

    	   $list = $list->orderBy('id desc')
                ->offset(Decvice::get_total_pages($get)->offset)
                ->limit(Decvice::get_total_pages($get)->limit)
                ->asArray()
                ->all();
    	  return $list;
    }


    /**
    * 根据条件显示分页 超级用户使用
    * 参数
    * @return
    */
    public function get_total_pages($get){
      	$count = Decvice::add_conditions($get)->count();
      	$page = new Pagination(['totalCount' =>$count, 'pageSize' => 10]);
            return $page;
    }

    /**
    * 根据条件追加机种查询条件 超级用户使用
    * 参数
    * @return
    */
    public function add_conditions($get){
     	  $dev_name = isset($get["dev_name"])?$get["dev_name"]:'';
        $dec_type = isset($get["dec_type"])?$get["dec_type"]:'';
        $card_type = isset($get["card_type"])?$get["card_type"]:'';
        $scene = isset($get["scene"])?$get["scene"]:'';

     	  $sql_condition = Decvice::find();
    	  if($dev_name){//若机种名不为空，追加条件
    		    $sql_condition->andWhere(['dev_name' =>$dev_name]);
    	  }
    	  if($dec_type){
    		    $sql_condition->andWhere(['dec_type' =>$dec_type]);
    	  }
    	  if($card_type){
    		    $sql_condition->andWhere(['card_type' =>$card_type]);
    	  }
    	  if($scene){
    		    $sql_condition->andWhere(['scene' =>$scene]);
    	  }

    	return $sql_condition;
     }




    /**
    * 根据用户提交的条件查找机种 非超级用户使用
    * 参数： name
    * @return 
    */
    function get_user_dec_by_name($user_name,$get){

        $list = Decvice::add_condition($user_name,$get); 
        $list = $list->orderBy('id desc')
                      ->offset(Decvice::get_pages($user_name,$get)->offset)
                      ->limit(Decvice::get_pages($user_name,$get)->limit)
                	    ->asArray()
               		    ->all();
        return $list;
    }

    /**
    * 根据用户名获得分页 非超级用户使用
    * 参数：name
    * @return 
    */
    function get_pages($user_name,$get){

    	  $count =  Decvice::add_condition($user_name,$get)->count();
        $page  = new Pagination(['totalCount' =>$count, 'pageSize' =>10]);
        return $page;
    }

   /**
    * 根据条件追加机种查询条件 非超级用户使用
    * 参数
    * @return
    */
    public function add_condition($user_name,$get){
        $dec_type = isset($get["dec_type"])?$get["dec_type"]:'';
        $card_type = isset($get["card_type"])?$get["card_type"]:'';
        $scene = isset($get["scene"])?$get["scene"]:'';

     	  $dec_name = Decvice::get_device_by_name($user_name);

     	  $sql_condition = Decvice::find()->where(['dev_name' =>$dec_name]);
    	  if($dec_type){
    		    $sql_condition->andWhere(['dec_type' =>$dec_type]);
    	  }
    	  if($card_type){
    		    $sql_condition->andWhere(['card_type' =>$card_type]);
    	  }
    	  if($scene){
    		    $sql_condition->andWhere(['scene' =>$scene]);
    	  }
    	  return $sql_condition;
    }


    /**
    * 根据用户名查找属于自己的机种
    * 参数 $user_name
    * @return 
    */
    public function get_device_by_name($user_name){

	   	$dec = Dev_user::find()->select(['device_name'])->where(['user_name' =>$user_name])->asArray()->all(); 
	    $dec_name = [];
	    foreach ($dec as $val) {
	        array_push($dec_name, $val['device_name']);
	    }
	    return $dec_name;	
    }

    


    //判断用户提交的机种是否属于自己
    public function belong_self($user_name,$dev_name){
    	$dec = Dev_user::find()->where(['device_name' =>$dev_name,'user_name' =>$user_name])->asArray()->all(); 
	    if($dec){

        return true; 
      }else{

         return false;
      } 
        
    }


    /**
    * 数据初始化 检验post表单提交的值是否满足要求
    * 参数
    * @return
    */
    public function check_infor($post){

	    if(!$post['dev_name']){
	    	return Yii::t('yii','Device name can not be empty!');
	    }
	    if(!$post['dec_type']){
	       	return Yii::t('yii','Device type can not be empty!');
	    }
	    if(!$post['card_type']){
	       	return Yii::t('yii','Card type can not be empty!');
	    }
	    if(!$post['scene']){
	       	return Yii::t('yii','Application scenarios can not be empty!');
	    }
      	if(!($post['dec_type']=='AP'||$post['dec_type']=='AC'||$post['dec_type']=='CPE')){
            return Yii::t('yii','Please fill in the correct device type!');
      	}
     	if(!($post['card_type']==Yii::t('yii','Single Card 2.4G')||$post['card_type']==Yii::t('yii','Single Card 5.8G')||$post['card_type']==Yii::t('yii','Double Card 2.4G')||$post['card_type']==Yii::t('yii','Double Card 5.8G')||$post['card_type']==Yii::t('yii','Double 2.4G&5.8G'))){ 
     		return Yii::t('yii','Please fill in the correct card type!');
      	}
      	return 'success';		
    }


    /**
    * 修改机种操作
    * 参数
    * @return
    */
    public function update_device($post){
    	//对设备的语种进行处理。填入数据库必须是英文
      	if(Yii::$app->session['language'] == 'zh-CN'|| Yii::$app->session['language'] == 'zh-TW'){
           $card_type = Decvice::change_card_type($post['card_type']);

           $result = Decvice::updateAll(['dev_name' =>$post['dev_name'],'dec_type' =>$post['dec_type'],'scene' =>$post['scene'],'card_type' =>$card_type],['id' =>$post['index']]);
      	   return $result;
      	}




    }


    //对设备进行语种转换 保证存入数据库的语言必须是英语
     public function change_card_type($card_type){
      	if($card_type == '单卡2.4G'||$card_type == '單卡2.4G'){
            $card_type = 'Single Card 2.4G';

      	}elseif ($card_type == '单卡5.8G'||$card_type == '單卡5.8G') {
      		$card_type = 'Single Card 5.8G';

      	}elseif ($card_type == '双卡2.4G'||$card_type == '雙卡2.4G') {
      		$card_type = 'Double Card 2.4G';

      	}elseif ($card_type == '双卡5.8G'||$card_type == '雙卡5.8G') {
      		$card_type = 'Double Card 5.8G';

      	}elseif ($card_type == '双卡2.4G&5.8G'||$card_type == '雙卡2.4G&5.8G') {
      		$card_type = 'Double 2.4G&5.8G';
      	}
      	return $card_type; 
     }





  /**
  * 功能： 检验数据库中是否存在此机种名
  * 返回 
  * @return
  */
  public function checked_dev_name($dev_name){
      $name = Decvice::find()->where(['dev_name' =>$dev_name])->all();
      if($name){
        return Yii::t('yii','Sorry,the device name already exist!');
      }else{
         return 'success';
      }
  }

   /**
   * 添加新的机种
   * 参数
   * @return
   */
   public function add_new_device($post){
    
        //对设备的语种进行处理。填入数据库必须是英文
        $card_type = Decvice::change_card_type($post['card_type']);
        
        $post['remarks'] = isset($post['remarks'])?$post['remarks']:'';
        
        $device = new Decvice();
        $device->dev_name  = $post['dev_name'];
        $device->dec_type  = $post['dec_type'];
        $device->card_type = $card_type;
        $device->scene     = $post['scene'];
        $device->remarks   = $post['remarks'];
        $device->save();
        
        return true;
   }



   /**
   * 功能 删除机种
   * @param $id
   * @return 
   */
   public function delete_device($id){
   		if($id){
          $dev_name =  Decvice::find()->where(['id' =>$id])->one();
          if($dev_name){
              $transaction = Yii::$app->db->beginTransaction(); //开启数据库事物

              $dev = Decvice::findOne($id)->delete(); //机种表删除
              $dev_use = Dev_user::deleteAll(['device_name' =>$dev_name->dev_name]); //用户关系表
              $fie = File_management_models::deleteAll(['device_name' =>$dev_name->dev_name]); //文件表
              $file_history = File_management_history_version::deleteAll(['device_name' =>$dev_name->dev_name]); //历史文件表

              if(!($dev&&$dev_use&&$fie&&$file_history)){ // 判断是否成功
                  $transaction->rollBack(); //事物回滚

              }
              return true;
          }
     			
   		}
   }


}			
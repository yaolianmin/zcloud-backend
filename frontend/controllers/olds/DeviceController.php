<?php


namespace frontend\controllers;
use Yii;
use yii\web\Controller;
use frontend\models\Decvice;
use frontend\models\Common;
use yii\data\Pagination;

/**
* 机种模块
* 功能 增删改查机种
*
* @copyright Copyright (c) 2017 – www.zhiweiya.com
* @author yaolianmin
* @version 1.0 2017/11/15 14:06
*/
class DeviceController extends Controller{

	/**
	* 机种显示主页
	* @param
	*/
	public function actionDevice(){

		 //判断是否存在session用户,没有则返回登陆页面
        if(!isset(Yii::$app->session['user_name'])){
           return $this->redirect(['index/index']); 
        }

        $power     = Yii::$app->session['power']; //用户的权限
        $user_name = Yii::$app->session['user_name']; //用户名

        //判断是否是语言切换提交的内容
        if(Yii::$app->request->isGet){
	        $language = Yii::$app->request->get('lang'); 
            $result = Common::change_language($language);
            if($result == 'success'){
            	return $this->redirect(['device/device']);
            }
        }
        

	        $get = Yii::$app->request->get();
	       
		    if($power == 1||$power == 5){//超级用户 或二级用户直接提取最新机种

	            $list  = Decvice::get_new_dev($get);
	            $pages=  Decvice::get_total_pages($get);
		    }else{//非超级用户显示自己 分配的机种
		        if(Yii::$app->request->get("dev_name")){ //有机种名搜索

		        	$resu = Decvice::belong_self($user_name,$get["dev_name"]);

		        	if($resu){ //代表此机种属于这个用户

		        		$list  = Decvice::get_new_dev($get);
	                    $pages=  Decvice::get_total_pages($get);

		        	}else{ //表示此机种用户无权限查看
		        		$list = [];
		        		$pages = new Pagination(['totalCount' =>0, 'pageSize' => 10]);;
		        	}
		        }else{ //无机种名搜索
		        	$list  = Decvice::get_user_dec_by_name($user_name,$get);
		            $pages = Decvice::get_pages($user_name,$get);
		        }   
		       
		    }


		    //判断是否是删除机种操作
		    $delete = Yii::$app->request->get('delete');
		    if($delete&& $power == 1){ //确定是删除动并且为超级用户
                //事先获得删除的机种名
                $dev_name = Decvice::find()->select(['dev_name'])->where(['id' =>$delete])->one();

		    	$del = Decvice::delete_device($delete);
		    	if($del){

		    		//添加一条日志
		    		Common::add_log(1,7,'delete',$dev_name->dev_name,'device');

		    		return $this->redirect(['device/device']);
		    	} 
		    }
		
       
        $post = '';
        $result = '';
        //post提交 机种添加修改操作
        if (Yii::$app->request->isPost) {
        	if($power == 1){ //判断是否为超级用户

	        	$post = Yii::$app->request->post();
	        	if($post['index']){ //判断是否含有ID 若有，则是修改机种

					$result = Decvice::check_infor($post);
	        		if($result == 'success'){ //代表数据满足要求
                     
	        	 		$res = Decvice::update_device($post); 
	        	 		if($res){
	        	 			//添加一条日志
                            Common::add_log(1,7,'update',$post['dev_name'],'device');
	        	 			return $this->redirect(['device/device']);
	        	 		}
	        		}else{ //非法数据 返回页面重新填写
	        			
	        	 		return $this->render('device',['post' =>$post,'list' =>$list,'pages' =>$pages,'get_infor' =>$get,'tips' =>$result]);
	        		}
	        	}else{  //代表是新增机种
	        		$result = Decvice::check_infor($post);

	        		if($result == 'success'){ //代表数据满足要求
		        		//检测数据库中是否已存在这个机种名
		        	 	$result = Decvice::checked_dev_name($post['dev_name']);
		        	 	if($result == 'success'){ //表示数据库无此机种名
		        	 		$add_device = Decvice::add_new_device($post);
			                if($add_device){
		                        //添加一条日志
		                        Common::add_log(1,7,'add',$post['dev_name'],'device');
			                	return $this->redirect(['device/device']);
			                }
		        	 	}else{

		        	 		return $this->render('device',['post' =>$post,'list' =>$list,'pages' =>$pages,'get_infor' =>$get,'tips' =>$result]);
		        	 	}	     
	        		}else{ //非法数据 返回页面重新填写
	        	 		return $this->render('device',['post' =>$post,'list' =>$list,'pages' =>$pages,'get_infor' =>$get,'tips' =>$result]);
	        		}
	        	}
        	
        	}

        }

	    return $this->render('device',['list' =>$list,'pages' =>$pages,'get_infor' =>$get,'tips' =>$result]);
	}
}
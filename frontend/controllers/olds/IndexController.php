<?php


namespace  frontend\controllers;
use Yii;
use yii\web\Controller;
use frontend\models\Login;
use frontend\models\Forget;
use frontend\models\Common;

/**
*  登录控制器显示
*  功能1 登录主页页面显示
*  功能2 忘记密码页面显示
*  功能3 主页面显示
*
*
*  @copyright Copyright (c) 2017 – www.zhiweiya.com
*  @author yaolianmin
*  @version 1.0 2017/10/18 14:06
*
*/

class IndexController extends Controller{
	/**
    * 登录页面显示 
    * 验证登陆信息
    */
	public function actionIndex(){
        //判断是否存在session
        if(isset(Yii::$app->session['user_name'])){
            return $this->redirect(['index/main']);
        }   
        
         //初始化验证登陆信息
        $model = new Login();
        if (Yii::$app->request->isPost) {//代表post方式,处理表单数据
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                //添加一条日志记录
                Common::add_log(1,8,'login','FW_server');
                //检测该用户日志数量是否超过规定数量
                Common::check_log_numbers(Yii::$app->session['user_name']);
                if(Yii::$app->session['power'] == 1){
                     return $this->redirect(['index/main']);  
                 }else{
                    return $this->redirect(['file_management/file_management']);
                 }
                 
            }
        } 
        return $this->renderPartial('index', ['model' => $model]);
	} 



    /**
    *  登录主页忘记密码模块
    *  验证表单提交信息
    */
    public function actionForget(){
        
        //初始化数据
        $info = new Forget();
        if(Yii::$app->request->isPost) {
            if ($info->load(Yii::$app->request->post()) && $info->validate()) {

            //添加一条日志
            Common::add_log(1,8,'find','your password');
            
            //删除session
            Common::exit_system();

            //修改密码成功， 跳转至登录页   
            return $this->redirect(['index/index']);    
            }
        }       
        return $this->renderPartial('forget',['info' =>$info]);
    }




    /**
    * FW_server主页模块显示
    * 显示系统信息
    */
    public function actionMain(){
        //判断是否存在session用户,没有则返回登陆页面
        if(!isset(Yii::$app->session['user_name'])){
             return $this->redirect(['index/index']); 
        }

        //获得系统运行时长
        $str = file("/proc/uptime");
        $str = trim($str[0]);
        $min = $str / 60;
        $hours = $min / 60;
        $days = floor($hours / 24);
        $hours = floor($hours - ($days * 24));
        $min = floor($min - ($days * 60 * 24) - ($hours * 60));
        $system_itme = [$days,$hours,$min];//系统运行的时长
        //CPU与内存的使用率
        $fp = popen('top -b -n 1 | grep -E "^(Cpu|Mem)"',"r");//获取某一时刻系统cpu和内存使用情况 
        $rs = "";
        while(!feof($fp))
        {
            $rs .= fread($fp,1024);
        }
        pclose($fp);
        $sys_info = explode("\n",$rs);   
        $cpu_info = explode(",",$sys_info[0]);
        $mem_info = explode(",",$sys_info[1]);
        $cpu_usage = trim(trim($cpu_info[0],'Cpu(s): '),'%us');
        $mem_total = trim(trim($mem_info[0],'Mem: '),'k total'); 
        $mem_used = trim($mem_info[1],'k used');
        $mem_usage = round(100*intval($mem_used)/intval($mem_total),2);
        $mem = substr((($mem_used/$mem_total)*100), 0,4);
        $cup_mem = [$cpu_usage,$mem];//CPU与内存使用率
        
        //判断是否是语言切换的提交
        if(Yii::$app->request->isGet){
            $language = Yii::$app->request->get('lang');
            if($language){
                $result = Common::change_language($language);
                if($result == 'success'){
                    return $this->redirect(['index/main']);
                } 
            }          
        }
        
        //退出本次登陆，销毁session，并返回登录页面
        $exit = Yii::$app->request->get('action'); 
        if($exit){
            //添加一条日志
            Common::add_log(1,8,'sign out','FW_server');

            $result_exit = Common::exit_system();
            if($result_exit == 'success'){

                return $this->redirect(['index/index']); 
            }
        } 


         //判断是否为超级用户进入
        if(Yii::$app->session['power']>2){
            return $this->redirect(['file_management/file_management']);
        }

        return $this->render('main',['system_itme' =>$system_itme,'cup_mem' =>$cup_mem]);
    }  
}
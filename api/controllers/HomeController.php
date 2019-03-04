<?php

/**
* 模块：系统主页模块
* 作用：查看 添加系统的日常操作信息
* 时间：2018-10-09
* 说明：由于采用了restful api方式请求，所创建的控制器Home 
*      需在config/main.php里面的controller配置 'home',
*      其请求方式get post等会自动的选择该控制器的方法，若有特殊命名需求，
*      可在config/main.php的extraPatterns里面配置
*@author :
*@version :
*/
namespace api\controllers;

use Yii;
use yii\rest\ActiveController;
use api\models\User;
use api\models\DevInfo;

class HomeController extends ActiveController {

	/**
     * 指定调用这个控制器时链接哪个数据模型.
     * 该属性必须设置.
     */
     public $modelClass = '';


    /**
     * 说明：注销父类里面默认的提交页面方式
     *      实现自己自带的控制器和方法
     */
    public function actions(){
        $actions = parent::actions();
        unset($actions['index'], $actions['update'], $actions['create'], $actions['delete'], $actions['view']);
        return $actions;
    }

    //获取系统cpu内存 设备等使用情况
    public function actionIndex(){
        $get = Yii::$app->request->get();
        // 1.0 获得系统的cpu和内存设备的使用情况
        if($get['flag'] == 'get_system_infor'){
            // 1.1 这里面老版本的事直接手写的，故这里没有重新的添加表记录
            $version = '3.4.1.4(CSC)7'; 

            // 1.2 获得系统运行时长
            $str = file("/proc/uptime");
            $str = trim($str[0]);
            $min = $str / 60;
            $hours = $min / 60;
            $days = floor($hours / 24);
            $hours = floor($hours - ($days * 24));
            $min = floor($min - ($days * 60 * 24) - ($hours * 60));
            $system_itme = [$days,$hours,$min];//系统运行的时长

            // 1.3 CPU与内存的使用率
            $power = User::get_user_power_by_username($get['user']);
            if( $power > 10){ //判断是否为超级管理员，否则执行下面的代码浪费时间
                exec('cat /etc/redhat-release',$centos_info,$bb);
                $fp = popen('top -b -n 1 | grep -E "(Cpu|Mem)"','r');//获取某一时刻系统cpu和内存使用情况 
                $rs = '';
                while(!feof($fp)){
                    $rs .= fread($fp,1024);
                }
                pclose($fp);
                $sys_info = explode("\n",$rs);   
                $cpu_info = explode(",",$sys_info[0]);
                $mem_info = explode(",",$sys_info[1]);

                //判断centos是6.x系列还是7.x系列
                $centos_version_6= substr($centos_info[0],15,1);
                $centos_version_7= substr($centos_info[0],21,1);
                if($centos_version_6 == 6){ //centos6.X系列的 $centos_info 是 CentOS release 6.x (Final)
                    $cpu_usage = trim(trim($cpu_info[0],'Cpu(s): '),'%us');
                    $mem_total = trim(trim($mem_info[0],'Mem: '),'k total'); 
                    $mem_used = trim($mem_info[1],'k used');
                }elseif($centos_version_7 == 7){ //centos7.X系列的 $centos_info 是 CentOS Linux release 7.x.1804 (Core)
                    $cpu_usage = trim(trim($cpu_info[0],'%Cpu(s): '),'%us');
                    $mem_total = trim(trim($mem_info[0],'KiB Mem: '),'total');
                    $mem_used = trim($mem_info[1],'used');
                }
                $mem_usage = round(100*intval($mem_used)/intval($mem_total),2);
                $mem = substr((($mem_used/$mem_total)*100), 0,4);
                $cup_mem = [$cpu_usage,$mem];//CPU与内存使用率 
            }else{
                $cup_mem = '';
            }
            // 1.4 获得该用户的组管理信息
            $group = User::get_user_group_name($get['user']);

            return ['version'=>$version,'satat_time'=>$system_itme,'memC'=>$cup_mem,'data'=>$group,'power'=>$power];
        // 2.0 获得设备的离线上线信息
        }elseif($get['flag'] == 'get_online_num'){
            try{
                // 2.1 获得设备的上线和离线人数
                $power = User::get_user_power_by_username($get['user']);
                if($power > 1){
                    $where = ['group_name'=>$get['group_name'],'management'=>$get['management'],'user_name'=>$get['group_look']];
            
                }else{
                    $where =['group_name'=>$get['group_name'],'user_name'=>$get['group_look']];
                }
                $online = DevInfo::find()->select('recent_online,device_status')->where($where)->asArray()->all();

                // 处理设备是否在线的问题
                $online_num = 0; // 上线人数
                $offline_num = 0; //离线人数
               
                foreach ($online as $ke => $val) {
                    if($val['device_status']){ 
                        $now_time = time();
                        $online_time = strtotime($val['recent_online']);
                        if($now_time > $online_time+300){ //表示超过300秒没有发送
                            $online[$ke]['device_status'] = 0;
                            // 修改数据库的字段
                            $off_time = date('Y-m-d H:i:s',$now_time); 
                            DevInfo::updateAll(['device_status'=>0,'recent_offline'=>$off_time],['mac_addr'=>$online[$ke]['mac_addr']]);
                            $offline_num += 1;
                        }else{
                            $online_num += 1;
                        }
                    }else{
                        $offline_num += 1;
                    }
                    
                }

                // $online_people = DevInfo::find()->select('last_beat,beat_interval')->where($where)->asArray()->all();

                // $online_num = 0; // 上线人数
                // $offline_num = 0; //离线人数
                // $current_time = time();
                // foreach ($online_people as $val) {
                //     if (!$val['beat_interval']){
                //          $val['beat_interval'] = 100;
                //     }
                //     if ($current_time - $val['last_beat'] > 300){
                //         $offline_num += 1;
                //     }else {
                //         $online_num += 1;
                //     }
                // }

                /* 2.2 获得客户端的人数（因老版本的代码，直接追到的是DevInfo表中的 device_status 字段，
                 *     因这个字段都是0，所以不需要再浪费代码，若以后需要更改，可以查询老版本代码
                 *     /var/www/html/php/home/get_zoam_info.php的184行代码的count_sta_num函数
                 */
                $client_num = 0;

                return [$online_num,$offline_num,$client_num];
            }catch(\Expection $e){
                return $e->getMessage();
            }
        }
    }

}
<?php

namespace api\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use api\models\User;
use api\models\GroupList;
use api\models\DevInfo;
use api\models\SystLog;
use backend\models\TempletFAP;

class BookController extends ActiveController
{
   
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

      
    public function actionIndex(){
        $get = Yii::$app->request->get();

         //检测是否为本网站的访问请求 
        if( !isset($get['flag']) ){
            return [
                'state'=>1,
                'data'=>'请您用正确的方式访问'
            ];
        }
        //刚进入页面获得模板信息
        if($get['flag'] == 'get_dev_type_under_grp'){
            // 获得操作者的权限
            $user_power = User::get_user_power_by_username($get['user_name']);
            if($user_power>3){
                return [
                    'state'=>1,
                    'message'=>'您没有设备需要配置'
                ];
            }elseif($user_power == 2){
                try{
                    /**
                     * 说明:此需求很混乱，目前暂时没有完善，仅知道设备的类型下面有
                     *      FAP CPE 和 Bridge 三个，至于 AC没有制作，还有另一个CPE
                     *      为什么和 AP AC一个级别的，此需求，不清楚，若需要明白，请
                     *      找相关人员询问需求
                     */
                    $self = GroupList::self_get_group_managements($get['user_name']);
                if($self['group']['usr_grp_sel_group'] && $self['group']['usr_grp_sel_mgt']&& $self['group']['usr_grp_sel_reviewer']){
                    $sql = "select model_type,model_name,card_id,mac_addr,opemode from DevInfo where management='".$self['group']['usr_grp_sel_mgt']."' and user_name='".$self['group']['usr_grp_sel_reviewer']."' and group_name='".$self['group']['usr_grp_sel_group']."' group by model_name";
                    $datas = Yii::$app->db->createCommand($sql)->queryAll(); 
                    $dev_type = [];

                    $fap = [];
                    $FAP = [];
                    $fap_id = []; 

                    $ap_CPE = []; 
                    $cpe_new =[];
                    $ap_cpe_id = [];

                    $bridge = [];
                    $Bridge = [];
                    $bridge_id = [];
                    foreach ($datas as $val) {
                        if( $val['model_type'] == 'AP' && ($val['opemode'] != 'Bridge' ) && ($val['opemode']!='CPE')){
                            $chk_ret_arr =  DevInfo::check_dev_model_name($val['model_name'],$val['mac_addr']); 
                            if($chk_ret_arr){
                                if(!in_array('FAP', $dev_type)){
                                    array_push($dev_type,['type'=>'FAP']);
                                }
                                array_push($fap, $chk_ret_arr['TYPE']);
                                array_push($FAP, ['name'=>$val['model_name']]);
                                array_push($fap_id, $chk_ret_arr['CARD_ID']);
                            }
                        }elseif($val['model_type'] == 'AC'){
                            if(!in_array('AC', $dev_type)){
                                    array_push($dev_type,['type'=>'AC']);
                            }
                        }elseif( $val['model_type'] == 'AP' && $val['opemode'] == 'CPE'){
                            $chk_ret_arr =  DevInfo::check_dev_model_name($val['model_name'],$val['mac_addr']); 
                            if($chk_ret_arr){
                                 if(!in_array('Bridge', $dev_type)){
                                    array_push($dev_type,['type'=>'CPE']);
                                }
                                array_push($ap_CPE, $chk_ret_arr['TYPE']);
                                array_push($cpe_new, ['name'=>$val['model_name']]);
                                array_push($ap_cpe_id, $chk_ret_arr['CARD_ID']);
                            }
                        }elseif($val['model_type'] == 'AP' && $val['opemode'] == 'Bridge'){
                            $chk_ret_arr =  DevInfo::check_dev_model_name($val['model_name'],$val['mac_addr']); 
                            if($chk_ret_arr){
                                if(!in_array('Bridge', $dev_type)){
                                    array_push($dev_type,['type'=>'Bridge']);
                                }
                                array_push($bridge, $chk_ret_arr['TYPE']);
                                array_push($Bridge, ['name'=>$val['model_name']]);
                                array_push($bridge_id, $chk_ret_arr['CARD_ID']);
                            }
                        }
                    }
                    // 获得胖AP的模板文件信息
                    $count = '';
                    $fap_infor = [];
                    if($fap){
                        $fap_infor = TempletFAP::find()->select('id,templet_name,VAP0_SSID,WirelessMode,VAP0_Auth,Channel')
                                                       ->where(['username'=>$get['user_name'],'card_index'=>'0','model_name'=>$FAP[0]])
                                                       ->offset(0)
                                                       ->limit(10)
                                                       ->asArray()
                                                       ->all();
                        $count = TempletFAP::find()->where(['username'=>$get['user_name'],'card_index'=>'0','model_name'=>$FAP[0]])->count();
                        foreach ($fap_infor as $key => $vals) {
                            if($FAP[0] == 'SP220-C01'|| $FAP[0] == 'SP220-C02'){ 
                                TempletFAP::updateAll(['VAP0_VAPEnable'=>1],['id'=>$val['id']]); 
                            }
                            if($fap_id[0] == 3 || $fap_id[0] == 11 || $fap_id[0] == 15 || $fap_id[0] == 7){
                                $fap_infor[$key]['catdIdMain'] = TempletFAP::find()->select('VAP0_SSID,WirelessMode,VAP0_Auth,Channel')->where(['cardIdMain'=>$vals['id']])->asArray()->one();
                            }  
                        }
                    }
                return [
                    'state'=>0,
                    'dev_type'=>$dev_type,
                    'TYPE'=>['FAP'=>$fap,'CPE'=>$ap_CPE,'Bridge'=>$bridge],
                    'model_name'=>['FAP'=>$FAP,'CPE'=>$cpe_new,'Bridge'=>$Bridge],
                    'card_id'=>['FAP'=>$fap_id,'CPE'=>$ap_cpe_id,'Bridge'=>$bridge_id],
                    'fap_templet'=>$fap_infor,
                    'count'=>$count
                ];
                }else{
                    return [
                        'state'=>1,
                        'message'=>'请选择网站头部的组管理'
                    ];
                }
                }catch(\Expection $e){
                    return $e->getMessage();
                }
                
            }elseif($user_power == 1){
                return [
                    'state'=>1,
                    'message'=>'您没有设备权限配置'
                ];
            }
        // 获得某个模板的详细信息
        }elseif($get['flag'] == 'get_this_templete_infor'){
             $user_power = User::get_user_power_by_username($get['user_name']);
            if($user_power!=2){
                return [
                    'state'=>1,
                    'message'=>'您没有设备需要配置'
                ];
            }
            $infor = TempletFAP::find()->select('PortalEnable,MacAuth,EnableAutoCfgBlackWhiteList,BlackWhiteListURL, BWLAutoUpdateTimeHour,EnableRadiusDesk,UpdateCommandURL,RDAutoUpdateTimeHour,RDAutoUpdateTimeMin,RDGetParameterURL')->where(['id'=>$get['temp_id']])->asArray()->one();
            return $infor;
        // 获得MD5加密的字符串
        }elseif($get['flag'] == 'get_md5s_password'){

            if($get['type'] =='40'){
                $md5 = md5($get['pass']);
                $len = 8;
            }elseif($get['type'] =='104'){
                $md5 = hash('sha256',$get['pass']);
                $len = 16;
            }elseif($get['type'] =='128'){
                $md5 = md5($get['pass']).hash('sha256',$get['pass']);
                $len = 24;
            }
            if(isset($md5)){
                $key1 = substr($md5, 0,$len);
                $key2 = substr($md5, $len,$len);
                $key3 = substr($md5, $len*2,$len);
                $key4 = substr($md5, $len*3,$len);
                return [
                    'key1'=> $key1,
                    'key2'=> $key2,
                    'key3'=> $key3,
                    'key4'=> $key4
                ]; 
            }
        }

    }



     /**
     * 方法：create方法
     * 作用：添加、修改、删除用户的信息
     * 说明：前端页面post请求
     *      这里响应的是添加、修改、删除的操作
     */
    public function actionCreate(){
        $post = Yii::$app->request->post();
        // 0.0 检测是否为本网站的访问
        if(!isset($post['flag']) ){
            return [
                'state'=>1,
                'data'=>'请您用正确的方式访问'
            ];
        }
        //编辑模板信息
        if($post['flag'] == 'edit_templet_information'){
            $user_power = User::get_user_power_by_username($post['user_name']);
            if($user_power!=2){
                return [
                    'state'=>1,
                    'message'=>'您没有设备需要配置'
                ];
            }
            if(!$post['data']['tmp_id']){
                return [
                    'state'=>1,
                    'message'=>'您修改的模板没有id，请联系管理员'
                ];
            }
            if($post['card_id'] == 1){
                
            }elseif($post['card_id'] == 3){
                
            }elseif($post['card_id'] == 5){
                
            }elseif($post['card_id'] == 7){
                 
            }elseif($post['card_id'] == 11){
                // 处理前端提交过来的数组数据
                $PortalEnable =  ($post['data']['door'])?1:0;
                $EnableAutoCfgBlackWhiteList = ($post['data']['black'])?1:0;
                $EnableRadiusDesk = ($post['data']['RadiusDesk'])?1:0;

                TempletFAP::updateAll([
                    'WirelessMode'=>$post['data']['wireless1'],              // 卡1的无限模式
                    // 'Channel'=>,
                    'TransmitRate'=>$post['data']['speed'],                  // 卡1的速率
                    'AutoPower'=>$post['data']['power'],                     // 卡1的启用自动功率调整
                    'BackGroundScan'=>$post['data']['rate'],                 // 卡1的启用频率自动调节
                    'AutoFrequencyAdjustMode'=>$post['data']['rate_type'],   // 卡1的频率自动调节模式
                    'BackGroundScanInterval'=>$post['data']['rate_time'],    // 卡1的频率自动调节间隔
                    'PortalEnable'=> $PortalEnable,                          // 网络门户设置
                    'MacAuth'=>$post['data']['renzheng'],                    //MAC认证
                    'EnableAutoCfgBlackWhiteList'=>$EnableAutoCfgBlackWhiteList,//启用黑白名单设置
                    'BlackWhiteListURL'=>$post['data']['black_url'],         //黑白名单URL
                    'BWLAutoUpdateTimeHour'=>$post['data']['time_up'],       //自动更新小时
                    'EnableRadiusDesk'=>$EnableRadiusDesk,                   // RadiusDesk设置
                    'UpdateCommandURL'=>$post['data']['up_url'],             //更新指令URL
                    'RDAutoUpdateTimeHour'=>$post['data']['time_ups'],       //自动更新小时
                    'RDGetParameterURL'=>$post['data']['get_url'],           // 获取参数URL
                ],['id'=>$post['data']['tmp_id']]);

                TempletFAP::updateAll([
                    'WirelessMode'=>$post['data']['wireless2'],              // 卡2的无限模式
                    'TransmitRate'=>$post['data']['speed2'],                 // 卡2的速率
                    'AutoPower'=>$post['data']['power2'],                    // 卡2的启用自动功率调整
                    'BackGroundScan'=>$post['data']['rate2'],                // 卡2的启用频率自动调节
                    'AutoFrequencyAdjustMode'=>$post['data']['rate_type2'],  // 卡2的频率自动调节模式
                    'BackGroundScanInterval'=>$post['data']['rate_time2'],   // 卡2的频率自动调节间隔
                ],['cardIdMain'=>$post['data']['tmp_id']]);
                $temp_name = TempletFAP::find()->select('model_name')->where(['id'=>$post['data']['tmp_id']])->asArray()->one();
                 // 0.0 这里需要添加一条进入系统的日志至数据库中
                SystLog::zoam_add_log($post['user_name'],1,22,'information','templet FAP-VAP edit'.':'.$temp_name['model_name']);
                return [
                    'state'=>0,
                    'message'=>'修改成功'
                ];
            }elseif($post['card_id'] == 15){
                return [
                    'state'=>1,
                    'message'=>'您修改的模板没有id，请联系管理员'
                ];
            }
        // 添加模板的操作
        }elseif($post['flag'] == 'add_templet_information'){
            // 1.0 过滤不合理的数据
            $power = User::get_user_power_by_username($post['user_name']);
            if($power!=2){
                return [
                    'state'=>1,
                    'message'=>'您没有添加模板的权限'
                ];
            }
            $temp_length = strlen($post['data']['model_na']);
            if($temp_length < 1 || $temp_length > 64){
                return [
                    'state'=>1,
                    'message'=>'模板名称长度是1-64位'
                ];
            }
            if($post['data']['rate_time'] < 60 || $post['data']['rate_time'] > 86400){
                return [
                    'state'=>1,
                    'message'=>'频率调节时间间隔不正确'
                ];
            }
            if($post['data']['rate_time2'] < 60 || $post['data']['rate_time2'] > 86400){
                return [
                    'state'=>1,
                    'message'=>'频率调节时间间隔不正确'
                ];
            }
            if(!$post['dev_type'] || !$post['model_name']){
                return [
                    'state'=>1,
                    'message'=>'您的模板没有设备类型和模块'
                ];
            }
            $rel = TempletFAP::find()->select('id')->where(['templet_name'=>$post['data']['model_na'],'username'=>$post['user_name']])->asArray()->one();
            if($rel){
                return [
                    'state'=>1,
                    'message'=>'您已经有此模板名，请更换模板名称'
                ];  
            }
            // 2。0 存储数据至数据库
            if($post['card_id'] ==1 && $post['model_name'] == 'ZAC-1023-2-9'){

            }elseif($post['card_id'] ==1){

            }elseif($post['card_id'] ==5 && $post['model_name'] == 'ZZAC-1023-5-13'){

            }elseif($post['card_id'] == 5 ){

            }elseif($post['card_id'] == 3 || $post['card_id'] == 11 && $post['model_name'] != 'SP220-C01' && $post['model_name'] != 'SP220-C02'){
                // 处理前端提交过来的数组数据
                $PortalEnable =  ($post['data']['door'])?1:0;
                $EnableAutoCfgBlackWhiteList = ($post['data']['black'])?1:0;
                $EnableRadiusDesk = ($post['data']['RadiusDesk'])?1:0;

                $templet = new TempletFAP();
                $templet->model_name = $post['model_name'];                           // model_name
                $templet->templet_name = $post['data']['model_na'];                   // 模板名
                $templet->username = $post['user_name'];                              // 用户名
                $templet->card_index = 0;                                             // 默认给0
                $templet->RadioEnable = 1;                                            // 默认给1
                $templet->CountryRegion = 8;                                          // 国家 默认是8
                $templet->WirelessMode =  $post['data']['wireless1'];                 // 卡1的 无线模式
                $templet->Channel = $post['data']['channels1'];                       // 卡1的 信道/频率
                $templet->TransmitRate = $post['data']['speed'];                      // 卡1的 速率
                $templet->AutoPower = $post['data']['power'];                         // 卡1的启用自动功率调整
                $templet->BackGroundScan = $post['data']['rate'];                     // 卡1的启用频率自动调节
                $templet->AutoFrequencyAdjustMode = $post['data']['rate_type'];       // 卡1的频率自动调节模式
                $templet->BackGroundScanInterval = $post['data']['rate_time'];        // 卡1的频率自动调节间隔
                $templet->PortalEnable =  $PortalEnable;                              // 网络门户设置
                $templet->MacAuth = $post['data']['renzheng'];                        // MAC认证
                $templet->EnableAutoCfgBlackWhiteList = $EnableAutoCfgBlackWhiteList; // 启用黑白名单设置
                $templet->BlackWhiteListURL = $post['data']['black_url'];             // 黑白名单URL
                $templet->BWLAutoUpdateTimeHour = $post['data']['time_up'];           // 自动更新小时
                $templet->EnableRadiusDesk = $EnableRadiusDesk;                       // RadiusDesk设置
                $templet->UpdateCommandURL = $post['data']['up_url'];                 // 更新指令URL
                $templet->RDAutoUpdateTimeHour = $post['data']['time_ups'];           // 自动更新小时
                $templet->RDGetParameterURL = $post['data']['get_url'];               // 获取参数URL
                $templet->save(); 
                 
                // 查找当前的插入数据的id
                $temp_id = TempletFAP::find()->select('id')->where(['templet_name'=>$post['data']['model_na'],'username'=>$post['user_name']])->asArray()->one();
                // 插入卡2的信息
                $templet = new TempletFAP();
                $templet->model_name = $post['model_name'];                           // model_name
                $templet->templet_name = $post['data']['model_na'];                   // 模板名
                $templet->username = $post['user_name'];                              // 用户名
                $templet->cardIdMain = $temp_id['id'];                                // 卡1的 id
                $templet->card_index = 1;                                             // 默认给0
                $templet->RadioEnable = 1;                                            // 默认给1
                $templet->CountryRegion = 8;                                          // 国家 默认是8
                $templet->WirelessMode =  $post['data']['wireless2'];                 // 卡2的 无线模式
                $templet->Channel = $post['data']['channels2'];                       // 卡2的 信道/频率
                $templet->TransmitRate = $post['data']['speed2'];                     // 卡2的 速率
                $templet->AutoPower = $post['data']['power2'];                        // 卡2的启用自动功率调整
                $templet->BackGroundScan = $post['data']['rate2'];                    // 卡2的启用频率自动调节
                $templet->AutoFrequencyAdjustMode = $post['data']['rate_type2'];      // 卡2的频率自动调节模式
                $templet->BackGroundScanInterval = $post['data']['rate_time2'];       // 卡2的频率自动调节间隔
                $templet->save(); 

                // 0.0 这里需要添加一条进入系统的日志至数据库中
                SystLog::zoam_add_log($post['user_name'],1,22,'information','templet FAP add'.':'.$post['model_name']);

                return [
                    'state'=>0,
                    'message'=>'添加成功'
                ]; 
            }elseif($post['card_id'] == 11 &&($post['model_name'] == 'SP220-C01' || $post['model_name'] == 'SP220-C02')){

            }elseif($post['card_id'] == 15 || $post['card_id'] == 7){

            }
        // 删除模板的操作
        }elseif($post['flag'] == 'del_templet_information'){
            $power = User::get_user_power_by_username($post['user_name']);
            if($power!=2){
                return [
                    'state'=>1,
                    'message'=>'您没有删除模板的权限'
                ];
            }
            $tmp_name = TempletFAP::find()->select('model_name')->where(['id'=>$post['temp_id']])->asArray()->one();
            if($post['card_id'] == 1 || $post['card_id'] == 5){
                $del = TempletFAP::deleteAll(['id'=>$post['temp_id']]);
            }elseif($post['card_id'] == 3 ||$post['card_id'] == 7 ||$post['card_id'] == 11 ||$post['card_id'] == 15 ){
                $del = TempletFAP::deleteAll(['id'=>$post['temp_id']]);
                $del = TempletFAP::deleteAll(['cardIdMain'=>$post['temp_id']]);
            }

            // 0.0 这里需要添加一条进入系统的日志至数据库中
            SystLog::zoam_add_log($post['user_name'],1,21,'information','templet FAP delete'.':'.$tmp_name['model_name']);

            return [
                'state'=>0,
                'message'=>'删除成功'
            ]; 
        // 添加VAP虚拟模板
        }elseif($post['flag'] == 'add_vap_templete'){
            $power = User::get_user_power_by_username($post['user_name']);
            if($power !=2){
                return [
                    'state'=>1,
                    'message'=>'您没有权限'
                ];  
            }
            if(!$post['data']['tmp_id']){
                return [
                    'state'=>1,
                    'message'=>'该模板没有id，请刷新重试'
                ];   
            }
            // 判断是card1 还是card2（因为条件不同）
            if($post['card'] == 1){
                $where = ['id'=>$post['data']['tmp_id']];
            }else{
                $where = ['cardIdMain'=>$post['data']['tmp_id']];
            }
            //判断是哪一个vap（有8个）
            $index = $post['data']['card_vap_num'];
            // 处理encryption
            $wep_key_type=0;
            if($post['data']['data_encryption'] == 40 || $post['data']['data_encryption'] == 104 || $post['data']['data_encryption'] == 128){
                $wep_key_type = $post['data']['data_encryption'];
                $encryption_mode = 1;

            }else if($post['data']['data_encryption'] == 255){    //tkip

                $encryption_mode = 2;

            }else if($post['data']['data_encryption'] == 254){    //aes
            
                $encryption_mode = 4;

            }else if($post['data']['data_encryption'] == 253){    //tkip+aes
            
                $encryption_mode = 6;
            }else{
                $encryption_mode = 0;
            }
            // 处理选中的密钥
            switch ($post['data']['choice']) {
                case 'key1':
                    $key = 1;
                    break;
                case 'key2':
                    $key = 2;
                    break;
                case 'key3':
                    $key = 3;
                    break;
                case 'key4':
                    $key = 4;
                    break; 
            }
            $rel = TempletFAP::updateAll([
                //'VAP'.$index.'_VAPEnable'=>$post['data']['tmp_id'],               // 启用虚拟ap
                'VAP'.$index.'_ProfileName'=>$post['data']['wireless_name'],       //配置文件名称
                'VAP'.$index.'_SSID'=>$post['data']['wireless_flags'],             //无线网络名称
                'VAP'.$index.'_SSIDSuppress'=>$post['data']['wireless_broadcast'], // 广播无线网络名称
                'VAP'.$index.'_Auth'=>$post['data']['renzheng_type'],              // 网络认证模式
                'VAP'.$index.'_Encryption'=>$encryption_mode,                      // 数据加密方式
                'VAP'.$index.'_WepPassPhrase'=>$post['data']['pass'],              //密码
                'VAP'.$index.'_WepKeyType'=>$wep_key_type,
                'VAP'.$index.'_WepKeyDefaultIdx'=>$key,                            //选中的哪一个密钥
                'VAP'.$index.'_WepKey1'=>$post['data']['k1'],
                'VAP'.$index.'_WepKey2'=>$post['data']['k3'],
                'VAP'.$index.'_WepKey3'=>$post['data']['k3'],
                'VAP'.$index.'_WepKey4'=>$post['data']['k4'],
                //'VAP'.$index.'_WpaPSK'=>
            ],$where);
            if($rel){
                return [
                    'state'=>0,
                    'message'=>'添加成功'
                ]; 
            }  
        }
    }
     

}
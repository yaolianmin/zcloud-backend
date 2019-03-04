<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class TempletFAP extends ActiveRecord
{
    public static function tableName()
    {
        return 'TempletFAP';//创建一个数据模型链接数据表
    }
	
	function addtemplet($value)
	{
		$model = new TempletFAP();
		
		if(!empty($value['model_name']))
			$model->model_name = $value['model_name'];
		if(!empty($value['mac_addr']))
			$model->mac_addr = $value['mac_addr'];
		if(!empty($value['templet_name']))
			$model->templet_name = $value['templet_name'];
		if(!empty($value['group_name']))
			$model->group_name = $value['group_name'];
		if(!empty($value['reviewer']))
			$model->reviewer = $value['reviewer'];
		if(!empty($value['username']))
			$model->username = $value['username'];
		if(!empty($value['card_index']))
			$model->card_index = $value['card_index'];
		if(!empty($value['cardIdMain']))
			$model->cardIdMain = $value['cardIdMain'];
		if(!empty($value['OperationMode']))
			$model->OperationMode = $value['OperationMode'];
		if(!empty($value['RadioEnable']))
			$model->RadioEnable = $value['RadioEnable'];
		if(!empty($value['CountryRegion']))
			$model->CountryRegion = $value['CountryRegion'];
		if(!empty($value['WirelessMode']))
			$model->WirelessMode = $value['WirelessMode'];
		if(!empty($value['Channel']))
			$model->Channel = $value['Channel'];
		if(!empty($value['TransmitRate']))
			$model->TransmitRate = $value['TransmitRate'];
		if(!empty($value['AutoPower']))
			$model->AutoPower = $value['AutoPower'];
		if(!empty($value['BackGroundScan']))
			$model->BackGroundScan = $value['BackGroundScan'];
		if(!empty($value['AutoFrequencyAdjustMode']))
			$model->AutoFrequencyAdjustMode = $value['AutoFrequencyAdjustMode'];
		if(!empty($value['BackGroundScanInterval']))
			$model->BackGroundScanInterval = $value['BackGroundScanInterval'];
		if(!empty($value['NetMode']))
			$model->NetMode = $value['NetMode'];
		if(!empty($value['APN']))
			$model->APN = $value['APN'];
		if(!empty($value['MTU']))
			$model->MTU = $value['MTU'];
		if(!empty($value['LocalPortalStatus']))
			$model->LocalPortalStatus = $value['LocalPortalStatus'];
		if(!empty($value['PortalVerificationPage']))
			$model->PortalVerificationPage = $value['PortalVerificationPage'];
		if(!empty($value['RedirectPage']))
			$model->RedirectPage = $value['RedirectPage'];
		if(!empty($value['IPEndMask']))
			$model->IPEndMask = $value['IPEndMask'];
		if(!empty($value['GNSSOptions']))
			$model->GNSSOptions = $value['GNSSOptions'];
		if(!empty($value['WDSMacAddr1']))
			$model->WDSMacAddr1 = $value['WDSMacAddr1'];
		if(!empty($value['WDSMacAddr2']))
			$model->WDSMacAddr2 = $value['WDSMacAddr2'];
		if(!empty($value['WDSMacAddr3']))
			$model->WDSMacAddr3 = $value['WDSMacAddr3'];
		if(!empty($value['WDSMacAddr4']))
			$model->WDSMacAddr4 = $value['WDSMacAddr4'];
		if(!empty($value['PortalMode']))
			$model->PortalMode = $value['PortalMode'];
		if(!empty($value['ServerUrl']))
			$model->ServerUrl = $value['ServerUrl'];
		if(!empty($value['UserName_224']))
			$model->UserName_224 = $value['UserName_224'];
		if(!empty($value['Password_224']))
			$model->Password_224 = $value['Password_224'];
		if(!empty($value['RefreshInterval']))
			$model->RefreshInterval = $value['RefreshInterval'];
		if(!empty($value['PortalServerAddr']))
			$model->PortalServerAddr = $value['PortalServerAddr'];
		if(!empty($value['PortalServerAddrPort']))
			$model->PortalServerAddrPort = $value['PortalServerAddrPort'];
		if(!empty($value['PortalServerAddrPage']))
			$model->PortalServerAddrPage = $value['PortalServerAddrPage'];
		if(!empty($value['PortalServerPort']))
			$model->PortalServerPort = $value['PortalServerPort'];
		if(!empty($value['PortalAPName']))
			$model->PortalAPName = $value['PortalAPName'];
		if(!empty($value['PortalPrimaryRADIUSServer']))
			$model->PortalPrimaryRADIUSServer = $value['PortalPrimaryRADIUSServer'];
		if(!empty($value['PortalSecondaryRADIUSServer']))
			$model->PortalSecondaryRADIUSServer = $value['PortalSecondaryRADIUSServer'];
		if(!empty($value['PortalRADIUSAuthPort_224']))
			$model->PortalRADIUSAuthPort_224 = $value['PortalRADIUSAuthPort_224'];
		if(!empty($value['PortalRADIUSAcctPort_224']))
			$model->PortalRADIUSAcctPort_224 = $value['PortalRADIUSAcctPort_224'];
		if(!empty($value['PortalRADIUSSharedSecret']))
			$model->PortalRADIUSSharedSecret = $value['PortalRADIUSSharedSecret'];
		if(!empty($value['PortalRADIUSNASID']))
			$model->PortalRADIUSNASID = $value['PortalRADIUSNASID'];
		if(!empty($value['ip_lan_ip_addr']))
			$model->ip_lan_ip_addr = $value['ip_lan_ip_addr'];
		if(!empty($value['ip_start_ip_addr']))
			$model->ip_start_ip_addr = $value['ip_start_ip_addr'];
		if(!empty($value['ip_end_ip_addr']))
			$model->ip_end_ip_addr = $value['ip_end_ip_addr'];
		if(!empty($value['ip_subnet_mask']))
			$model->ip_subnet_mask = $value['ip_subnet_mask'];
		if(!empty($value['ip_default_gateway']))
			$model->ip_default_gateway = $value['ip_default_gateway'];
		if(!empty($value['ip_primary_dns_server']))
			$model->ip_primary_dns_server = $value['ip_primary_dns_server'];
		if(!empty($value['ip_lease_time']))
			$model->ip_lease_time = $value['ip_lease_time'];
		if(!empty($value['pw_chk_box']))
			$model->pw_chk_box = $value['pw_chk_box'];
		if(!empty($value['pw_old_passwd']))
			$model->pw_old_passwd = $value['pw_old_passwd'];
		if(!empty($value['pw_new_passwd']))
			$model->pw_new_passwd = $value['pw_new_passwd'];
		if(!empty($value['PortalEnable']))
			$model->PortalEnable = $value['PortalEnable'];
		if(!empty($value['MacAuth']))
			$model->MacAuth = $value['MacAuth'];
		if(!empty($value['WISPRLoginURL']))
			$model->WISPRLoginURL = $value['WISPRLoginURL'];
		if(!empty($value['EnableAutoCfgBlackWhiteList']))
			$model->EnableAutoCfgBlackWhiteList = $value['EnableAutoCfgBlackWhiteList'];
		if(!empty($value['BlackWhiteListURL']))
			$model->BlackWhiteListURL = $value['BlackWhiteListURL'];
		if(!empty($value['BWLAutoUpdateTimeHour']))
			$model->BWLAutoUpdateTimeHour = $value['BWLAutoUpdateTimeHour'];
		if(!empty($value['BWLAutoUpdateTimeMin']))
			$model->BWLAutoUpdateTimeMin = $value['BWLAutoUpdateTimeMin'];
		if(!empty($value['EnableRadiusDesk']))
			$model->EnableRadiusDesk = $value['EnableRadiusDesk'];
		if(!empty($value['UpdateCommandURL']))
			$model->UpdateCommandURL = $value['UpdateCommandURL'];
		if(!empty($value['RDAutoUpdateTimeHour']))
			$model->RDAutoUpdateTimeHour = $value['RDAutoUpdateTimeHour'];
		if(!empty($value['RDAutoUpdateTimeMin']))
			$model->RDAutoUpdateTimeMin = $value['RDAutoUpdateTimeMin'];
		if(!empty($value['RDGetParameterURL']))
			$model->RDGetParameterURL = $value['RDGetParameterURL'];
		
		
		if(!$model->save()){
            return array_values($model->getFirstErrors())[0];
        }
		return $model;
	}
	
	
	
}
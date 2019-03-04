<?php
session_start();
if(!isset($_SESSION['user_name'])){
	exit;
}

function connectMysql(){
	$db_host = $_SESSION["db_host"];
	$db_user = $_SESSION["db_user"];
	$db_pwd = $_SESSION["db_pwd"];
	$db_database = $_SESSION["db_database"];
	$conn = $_SESSION["conn"];
	$coding = $_SESSION["coding"];
	$sql = new mysql($db_host, $db_user, $db_pwd, $db_database, $conn, $coding);
	$sql->connect();
	return $sql;
}

function getPower($uname){
	$sql = connectMysql();
	$table = "user";
	$columnName = "Power";
	$condition = "UserName='$uname'";
	$order_by = "Power";
	$rs = $sql->select($table, $columnName, $condition, $order_by);
	$condition_normal_list;
	if($row=$sql->fetch_array()){
		$user_permission_value = $row['Power'];
	}
	return $user_permission_value;
}

function formDiagnosticCondition($group_name_arr, $mac_addr, $date_from, $date_to, $user_name, $get_user_name=""){
	$condition = db_create_in($group_name_arr, 'group_name');
	$date_to = date("Y-m-d H:i:s", strtotime($date_to)+60);//往后延迟一分钟用于解决刚刚上传的文件的bug
	
	//二级用户只可以查看本组内，一级用户都可看，三级用户都不可看	
	$user_power = getPower($user_name);
	if(2 == $user_power || 4 == $user_power){
		$condition .= " AND user_name = '$user_name'";
	}else if(1 == $user_power){
		$condition .= " AND 1<>1";
	}else if(15 == $user_power && $get_user_name != ""){//一级用户根据查询条件是否有三级用户，找出该三级用户的二级用户进行查询
		$sql = connectMysql();	
		$query = "select * from user WHERE UserName = '$get_user_name'";
		$sql->query($query);
		$getUserInfo = $sql->fetch_array();
		if(isset($getUserInfo['management'])){
			$getUserManagement = $getUserInfo['management'];
			$condition .= " AND user_name = '$getUserManagement'";
		}else{
			$condition .= " AND 1<>1";
		}
	}
	
	if($mac_addr != null){
		$condition .= " AND mac_addr='$mac_addr'";
	}
	
	if($date_from != null){
		$condition .= " AND insert_time >= '$date_from'";
	}
	
	if($date_to != null){
		$condition .= " AND insert_time <= '$date_to'";
	}
	
	return $condition;
}

function db_create_in($item_list, $field_name = '')
{
	if (empty($item_list))
	{
		return $field_name . " IN ('') ";
	}
	else
	{
		if (!is_array($item_list))
		{
			$item_list = explode(',', $item_list);
		}
		$item_list = array_unique($item_list);
		$item_list_tmp = '';
		foreach ($item_list AS $item)
		{
			if ($item !== '')
			{
				$item_list_tmp .= $item_list_tmp ? ",'$item'" : "'$item'";
			}
		}
		if (empty($item_list_tmp))
		{
			return $field_name . " IN ('') ";
		}
		else
		{
			return $field_name . ' IN (' . $item_list_tmp . ') ';
		}
	}
}
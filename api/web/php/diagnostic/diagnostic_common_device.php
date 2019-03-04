<?php
require_once './../config/config.php';
function connectMysql(){
	$db_config = C('DB');
	$db_host = $db_config["HOST"];
	$db_user = $db_config["USER"];
	$db_pwd = $db_config["PASSWORD"];
	$db_database = $db_config["DBNAME"];
	$conn = $db_config["CONN"];
	$coding = $db_config["CODING"];
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

function formDiagnosticCondition($group_name_arr, $mac_addr, $date_from, $date_to, $user_name){
	$condition = db_create_in($group_name_arr, 'group_name');
	$date_to = date("Y-m-d H:i:s", strtotime($date_to)+60);//往后延迟一分钟用于解决刚刚上传的文件的bug
	
	//二级用户只可以查看本组内，一级用户都可看，三级用户都不可看	
	$power = getPower($user_name);
	if(2 == $power || 4 == $power){
		$condition .= " AND user_name = '$user_name'";
	}else if(1 == $power){
		$condition .= " AND 1<>1";
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
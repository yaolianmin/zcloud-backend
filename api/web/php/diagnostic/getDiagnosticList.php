<?php
session_start();
require_once './../mysql/mysql.php';
require_once './diagnostic_common.php';

if(!isset($_SESSION['user_name'])){
	exit;
}
//获取基本数据
$user_name =$_SESSION['user_name'];
$mac_addr = addslashes($_GET['mac_addr']);
$date_from = $_GET['date_from'];
$date_to = $_GET['date_to'];
$group_name_arr = json_decode($_GET['group_name'], true);
$page_num = intval($_GET['page_num']);
$get_user_name = $_GET['user_name'];
$get_reviewer = addslashes($_GET['reviewer']);
if($group_name_arr == false){
	exit;
}

//表中数据多条mac对应多条数据，所以先 group by mac_addr 查询分页
$sql = connectMysql();
$columnName = "count(*) as count";
$condition = formDiagnosticCondition($group_name_arr, $mac_addr, $date_from, $date_to, $user_name, $get_user_name);
if($get_reviewer !== ""){
	$condition .= " AND reviewer='$get_reviewer'";
}
$table = "(select count(*) from diagnostic_list where $condition group by mac_addr) count_mac";
$sql->select($table, $columnName);
$row = $sql->fetch_array();
$macCount = $row['count'];

//分页模块
$pagesize = 10;
$page_mod=$macCount%$pagesize;
if($page_mod != 0){
	$total_page=($macCount-$page_mod)/$pagesize + 1;
}else if($page_mod == 0){
	$total_page=($macCount/$pagesize);
}
if($page_num > $total_page)
	$page_num = $total_page;
if($page_num < 1)
	$page_num = 1;

//根据页码查询显示的mac_addr
$tableName = "diagnostic_list";
$columnName = "*";
$limitmin = ($page_num-1)*$pagesize;
$limit = "limit $limitmin, $pagesize";
$sql->select_limit($tableName, $columnName, $condition." group by mac_addr", "", $limit);
$group_arr = array();
while($row = $sql->fetch_array()){
	$group_arr[] = $row['mac_addr'];
}

//根据查询出来的每个mac_addr查询对应的数据
$mac_addr_arr = array();
$group_arr = array_unique($group_arr);
foreach($group_arr as $each_mac){
	$macTemp = $each_mac;
	$new_condition = "$condition and mac_addr='$macTemp'";
	$sql->select($tableName, $columnName, $new_condition);
	while($row = $sql->fetch_array()){
		$mac_addr_arr[] = $row;
	}
}

//根据查询出来的每个mac_addr查询是否在线
$devinfo_arr = array();
$tableName = "DevInfo";
$devinfo_condition = db_create_in($group_arr, "mac_addr");
$sql->select($tableName, "mac_addr, beat_interval, last_beat", $devinfo_condition);
while($row = $sql->fetch_array()){
	$current_time = time();
	$interval = $row["beat_interval"];
	if ($row["beat_interval"] == null || $row["beat_interval"] == '')
		$interval = 100;
	if ($current_time - $row["last_beat"] > 3 * $interval)
		$dev_status = "OFFLINE";
	else
		$dev_status = "ONLINE";
	$devinfo_arr[$row['mac_addr']] = $dev_status;
}

//组合最终数据
$file_name_arr = array();
foreach($mac_addr_arr as $eachMac){
	if(!isset($file_name_arr[$eachMac['mac_addr']])){
		$file_name_arr[$eachMac['mac_addr']] = array(
					'mac_addr' => $eachMac['mac_addr']
				);
		$file_name_arr[$eachMac['mac_addr']]['file_name'] = array(); 
	}
	$file_name_arr[$eachMac['mac_addr']]['file_name'][] = $eachMac['file_name'];
	$file_name_arr[$eachMac['mac_addr']]['insert_time'][] = $eachMac['insert_time'];
	$file_name_arr[$eachMac['mac_addr']]['id'][] = $eachMac['id'];
	$file_name_arr[$eachMac['mac_addr']]['insert_type'][] = $eachMac['insert_type'];
	$file_name_arr[$eachMac['mac_addr']]['dev_status'] = ($devinfo_arr[$eachMac['mac_addr']]!=null) ? $devinfo_arr[$eachMac['mac_addr']] : "OFFLINE";
}
echo json_encode(array('total_page'=>$total_page, 'data'=>$file_name_arr));

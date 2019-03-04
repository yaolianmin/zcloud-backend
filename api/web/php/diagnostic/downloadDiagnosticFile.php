<?php
session_start();
require_once './../mysql/mysql.php';
require_once './diagnostic_common.php';
require_once './../language/get_language_str.php';

if(!isset($_SESSION['user_name'])){
	exit;
}

$user_name =$_SESSION['user_name'];

$id = addslashes($_GET['id']);
$file_path = "./../../../uploadfile/diagnostic/";
if(15 == getPower($user_name)){
	$condition = "id='$id'";
}else{
	$condition = "user_name='$user_name' AND id='$id'";
}
$sql = connectMysql();
$table = 'diagnostic_list';

$sql->select($table, "*", $condition);
$row = $sql->fetch_array();
//判断文件合法性
if(!is_array($row) || strpos($row['file_name'], '/') || strpos($row['file_name'], '\\')){
	echo get_language("File not exist!");
	exit;	
}

//文件是否存在
$file = $file_path.$row['file_name'];
if(!is_file($file)){
	echo get_language("File not exist!");
	exit;
}

//下载文件
$file_name = $row['file_name'];
header("Content-type: application/octet-stream");
header("Accept-Ranges: bytes");
header("Accept-Length: ".filesize($file));
header("Content-Disposition: attachment; filename=" . $file_name);
echo file_get_contents($file);
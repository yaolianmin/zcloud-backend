<?php
session_start();
require_once './../mysql/mysql.php';
require_once './diagnostic_common.php';
require_once './../language/get_language_str.php';

if(!isset($_SESSION['user_name'])){
	exit;
}

$user_name =$_SESSION['user_name'];
if(getPower($user_name) != 2 && getPower($user_name) != 4){//只有二级用户可以删除
	echo json_encode(array("flag"=>false, "message"=>get_language("File not exist!")));
	exit;	
}

$id = $_POST['id'];
$file_path = "./../../../uploadfile/diagnostic/";

$sql = connectMysql();
$table = 'diagnostic_list';
$condition = "user_name='$user_name' AND id='$id'";
$sql->select($table, "*", $condition);
$row = $sql->fetch_array();
//判断文件合法性
if(!is_array($row) || strpos($row['file_name'], '/') || strpos($row['file_name'], '\\')){
	echo json_encode(array("flag"=>false, "message"=>get_language("File not exist!")));
	exit;	
}

//判断是否删除成功
$sql->delete($table, $condition);
$sqlResult = $sql->getResult();
if($sqlResult === false){
	echo json_encode(array("flag"=>false, "message"=>get_language("Failed to delete the data!")));
	exit;
}

//文件是否存在
$file = $file_path.$row['file_name'];
if(!is_file($file)){
	echo json_encode(array("file"=>false, "message"=>get_language("File not exist!")));
	exit;
}

//文件是否删除成功
if(!unlink($file)){
	echo json_encode(array("flag"=>false, "message"=>get_language("Failed to delete the file!")));	
	exit;
}

echo json_encode(array("flag"=>true));	


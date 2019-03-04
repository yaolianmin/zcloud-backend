<?php
session_start();
if(!isset($_SESSION['user_name'])){
	exit;
}

$user_name =$_SESSION['user_name'];
$mac_addr = $_GET['mac_addr'];
$file_name = $_GET['file_name'];
//防止遍历文件
if(strpos($file_name, '/') || strpos($file_name, '\\')){
	echo json_encode(array("file"=>false));
	exit;
}

$file_path = "./../../../uploadfile/diagnostic/";
$file = $file_path.$file_name;

if(!is_file($file)){
	echo json_encode(array("file"=>false));
	exit;
}

echo json_encode(
		array(
			"file" => nl2br(htmlspecialchars(mb_convert_encoding(file_get_contents($file), "UTF-8", "GBK")))
		)
	);
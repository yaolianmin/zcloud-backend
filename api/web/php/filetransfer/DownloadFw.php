<?php
//zoam3.0及以后版本匹配
//add by shenwj 2015-2-6 12:58:00
require_once "./../config/config.php";
require_once './../mysql/mysql.php';
require_once './../syslog/syslog_update.php';
function db_init_a(){
	$db_config = C('DB');
	$db_host = $db_config['HOST'];
	$db_user = $db_config['USER'];
	$db_pwd = $db_config['PASSWORD'];
	$db_database = $db_config['DBNAME'];
	$conn = $db_config['CONN'];
	$coding = $db_config['CODING'];
	$sql = new mysql($db_host, $db_user, $db_pwd, $db_database, $conn, $coding);
	$sql->connect();
	return $sql;
}

if (!isset($_GET['file']) || !isset($_GET['type']))
{   
	print "no file selsect"; exit();
}
$file_name = $_GET['file'].".".$_GET['type'];

$sql = db_init_a();
$sql->select("actions", "ftp_server_params", "act_params='$file_name'");
if ($row=$sql->fetch_array())
{
	$ftp_server = $row[0];
}
if (isset($ftp_server)){
	$file_name = preg_replace("/^Firmware_/", "", $file_name);
	$file_name = preg_replace("/[0-9]{13}\.[0-9]{3}_/", "", $file_name);
	$redirect = "Location: ftp://".$ftp_server.$file_name;
	//$redirect = "Location: http://192.168.70.29/main.php";
	//echo $redirect;
	//header("Location: http://192.168.70.29/main.php");
	header($redirect);
}
else
{
	print("File is not exist!");
	exit();
}
exit();

//zoam3.0以前版本匹配
header("Content-Type:text/html; charset=utf-8");
include './../mysql/mysql.php';
include './../syslog/syslog_update.php';
session_start();

if (!isset($_GET['file']) || !isset($_GET['type']))
{   
	print "no file selsect"; exit();  
}

$file = $_GET['file'].".".$_GET['type'];  

if(!file_exists("../../../uploadfile/firmware/".$file))   	//检查文件是否存在  
{   
  echo   "File not exist!";  
  exit;    
}
else
{
  $fp = fopen("../../../uploadfile/firmware/".$file,"r");
  
	Header("Content-type: application/octet-stream");
	Header("Accept-Ranges: bytes");
	Header("Accept-Length: ".filesize("../../../uploadfile/firmware/".$file));
	Header("Content-Disposition: attachment; filename=" . $file);
	
	echo fread($fp,filesize("../../../uploadfile/firmware/".$file));
	fclose($fp);	
 	exit();   
}
?>
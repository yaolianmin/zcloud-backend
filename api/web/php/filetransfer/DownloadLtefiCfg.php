<?php
	header("Content-Type:text/html; charset=utf-8");
	include './../mysql/mysql.php';
	include './../syslog/syslog_update.php';
	session_start();

	if (!isset($_GET['file']) || !isset($_GET['type'])) 
	{   
		print "no file selsect"; exit();  
	}  
	$file = $_GET['file'].".".$_GET['type'];
	
	$devtype = $_GET['devtype']; 
	$file_name = "";
	
	//guilent for LTE-Fi FAP
	//if ($devtype == 1)	// AC template type
		$file_name = "../../../uploadfile/GNSS/".$file;
	
	if(!file_exists($file_name) || 
		filesize($file_name) == 0)   	//检查文件是否存在  
	{   
		echo   "File not exist!";  
		exit;    
	} else {
		$fp = fopen($file_name,"r");
		Header("Content-type: application/octet-stream");
		Header("Accept-Ranges: bytes");
		Header("Accept-Length: ".filesize($file_name));
		Header("Content-Disposition: attachment; filename=" . $file);
		echo fread($fp,filesize($file_name));
		fclose($fp);
		exit();
	}
?>
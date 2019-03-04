<?php

	$mac = $_GET["mac"];
	$type = $_GET["type"];
	$modelname = $_GET["modelname"];
	$action = $_GET["action"];	
	$fwversion = $_GET["fwversion"];
	
	$fp = fopen("/var/www/ZOAM_action_info.log", "a+");
	fwrite($fp, "mac === ".$mac."\r\n");
	fwrite($fp, "\r\n");
	fclose($fp);

?>
<?php
require_once './../mysql/mysql.php';
require_once './../config/config.php';
require_once './diagnostic_common_device.php';
//基础数据
$form_name = "diagnostic";
$trueFileType = array('txt',"csv");//文件类型白名单
$mac_addr = addslashes($_POST['mac_addr']);

//判断上传是否成功
if($_FILES[$form_name]['error']){
	echo json_encode(array('flag' => false));
	
	$fp = fopen("/var/www/html/debug/Diagnostic_form_name.log", "a");
	fwrite($fp, "[".date("Y-m-d H:i:s")."] "."$_FILES=".$_FILES[$form_name]['error']."\r\n");
	fclose($fp);
	
	exit;
}

//检查文件类型
$file_name = $_FILES[$form_name]['name'];
$file_name_arr = explode('.', $file_name);
if(count($file_name_arr) <= 0 ){
	echo json_encode(array('flag' => false));
	exit;
}
$file_type = strtolower($file_name_arr[count($file_name_arr)-1]);
if(!in_array($file_type, $trueFileType)){
	echo json_encode(array('flag' => false));
	exit;
}

//生成文件名
$datetime = date('YmdHis');
$mac_addr_form = str_replace(":","-",$mac_addr);
$file_name = "diagnostic_".$mac_addr_form."_dev_$datetime.".$file_type;

//检查设备是否存在
$sql = connectMysql();
$table = "DevInfo";
$condition = "mac_addr='$mac_addr'";
$sql->select($table, '*', $condition);
$dev_info = $sql->fetch_array();
if($dev_info == NULL){
	echo json_encode(array('flag' => false));
	exit;
}

//检查此设备是否已分组
if($dev_info['group_name'] == ""){
	echo json_encode(array('flag' => false));
	exit;	
}

//开启事务
//插入诊断信息
$sql->startTrans();
$mac_addr 	= $dev_info['mac_addr'];
$model_name = $dev_info['model_name'];
$model_name = $dev_info['model_name'];
$dev_type 	= $dev_info['model_type'];
$user_name 	= $dev_info['management'];
$group_name	= $dev_info['group_name'];
$reviewer = $dev_info['user_name'];
$insert_time= date("Y-m-d H:i:s");
$insert_type= 1;

$table = "diagnostic_list";
$columnName = "model_name, dev_type, mac_addr, user_name, group_name, file_name, insert_time, insert_type, reviewer";
$value = "'$model_name', '$dev_type', '$mac_addr', '$user_name', '$group_name', '$file_name', '$insert_time', '$insert_type', '$reviewer'";
$sql->insert($table, $columnName, $value);
if($sql->getResult() === false){
	$sql->rollback();
	echo json_encode(array('flag' => false));
	exit;
}

//删除多余的诊断信息
$condition = "mac_addr='$mac_addr' and user_name='$user_name'"; //删除该二级用户下的所有该设备的诊断数据，不考虑组，当设备从组1转到组2，删除的时候也会删除组1的该设备的诊断数据。
$orderby = " order by id";
$sql->select($table, "*", $condition);
$all_diagnostic_arr = array();
while($row = $sql->fetch_array()){
	$all_diagnostic_arr[] = $row;
}

$file_count = count($all_diagnostic_arr);
$diagnostic_conf = C('DIAGNOSTIC');
if($file_count > $diagnostic_conf['EACH_DIAGNOSTIC_REPORT_NUM']){
	$delete_count = $file_count - $diagnostic_conf['EACH_DIAGNOSTIC_REPORT_NUM'];
	$delete_id_arr = array();
	for($i=0; $i<$delete_count; $i++){
		$delete_id_arr[] = $all_diagnostic_arr[$i]['id'];	
		//删除文件
		$file_path = "./../../../uploadfile/diagnostic/";
		$file = $file_path.$all_diagnostic_arr[$i]['file_name'];
		unlink($file);
	}
	//删除数据
	$del_condition = db_create_in($delete_id_arr, 'id');
	$sql->delete($table, $del_condition);
}

//保存文件
$dir = "./../../../uploadfile/diagnostic/";
$uploadfile = $dir.$file_name;
$temp_name = $_FILES[$form_name]['tmp_name'];
$move_file_result = move_uploaded_file($temp_name, $uploadfile);
if($move_file_result == false){
	$sql->rollback();
	echo json_encode(array('flag' => false));
	exit;
}
$sql->commit();
echo json_encode(array('flag' => true));
?>
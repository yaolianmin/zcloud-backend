<body bgcolor='#efefef'>
<?php
session_start();
require_once './../mysql/mysql.php';
require_once './diagnostic_common.php';
require_once './../config/config.php';
require_once './../language/get_language_str.php';

if(!isset($_SESSION['user_name'])){
	exit;
}

//基础数据
$form_name = "diagnostic";
$trueFileType = array('txt',"csv");//文件类型白名单
$red_center_span_html = '<span style="color:red; font-size:14px">';
$black_center_span_html = '<span style="font-size:14px">';
$span_end_html = '</span>';
$button_style = "style=\"-moz-box-shadow:inset 0px 1px 0px 0px #54a3f7;
				-webkit-box-shadow:inset 0px 1px 0px 0px #54a3f7;
				box-shadow:inset 0px 1px 0px 0px #54a3f7;
				background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #007dc1), color-stop(1, #0061a7));
				background:-moz-linear-gradient(top, #007dc1 5%, #0061a7 100%);
				background:-webkit-linear-gradient(top, #007dc1 5%, #0061a7 100%);
				background:-o-linear-gradient(top, #007dc1 5%, #0061a7 100%);
				background:-ms-linear-gradient(top, #007dc1 5%, #0061a7 100%);
				background:linear-gradient(to bottom, #007dc1 5%, #0061a7 100%);
				filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#007dc1', endColorstr='#0061a7',GradientType=0);
				background-color:#007dc1;
				-moz-border-radius:3px;
				-webkit-border-radius:3px;
				border-radius:3px;
				border:1px solid #124d77;
				display:inline-block;
					cursor:pointer;
				color:#ffffff;
				font-family:arial;
				font-size:13px;
				padding:2px 10px;
				margin: 10px 0;
				text-decoration:none;
				text-shadow:0px 1px 0px #154682;\"";
$back_button_html = '<button onclick="location.href=\'/diagnostic_file_upload_form.php\'" '.$button_style.'>'.get_language("Back").'</button>';
$user_name = $_SESSION['user_name'];
$mac_addr = $_POST['mac_addr'];

//判断上传是否成功
if($_FILES[$form_name]['error']){
	echo $red_center_span_html.get_language("Files Upload Fail!").$span_end_html.$back_button_html;
	exit;
}

//检查文件类型
$file_name = $_FILES[$form_name]['name'];
$file_name_arr = explode('.', $file_name);
if(count($file_name_arr) <= 0 ){
	echo $red_center_span_html.get_language("File format error!").$span_end_html.$back_button_html;
	exit;
}
$file_type = strtolower($file_name_arr[count($file_name_arr)-1]);
if(!in_array($file_type, $trueFileType)){
	echo $red_center_span_html.get_language("File format error!").$span_end_html.$back_button_html;
	exit;
}

//生成文件名
$datetime = date('YmdHis');
$mac_addr_form = str_replace(":","-",$mac_addr);
$file_name = "diagnostic_".$mac_addr_form."_$datetime.".$file_type;

//检查设备是否在该用户下
$sql = connectMysql();
$table = "DevInfo";
$power = getPower($user_name);
if($power == 1 || $power == 4){
	$condition = "user_name='$user_name'";
}else if($power == 2){
	$condition = "management='$user_name'";
}else{
	echo $red_center_span_html.get_language("You have no authority of the operation!");
	exit;
}
$condition .= " AND mac_addr='$mac_addr'";
$sql->select($table, '*', $condition);
$dev_info = $sql->fetch_array();
if($dev_info == NULL){
	echo $red_center_span_html.get_language("No such device!").$span_end_html.$back_button_html;
	exit;
}

//检查此设备是否已分组
if($dev_info['group_name'] == ""){
	echo $red_center_span_html.get_language("The device have no group!").$span_end_html.$back_button_html;
	exit;	
}

//开启事务
//插入诊断信息
$sql->startTrans();
$mac_addr = $dev_info['mac_addr'];
$model_name = $dev_info['model_name'];
$model_name = $dev_info['model_name'];
$dev_type = $dev_info['model_type'];
$user_name = $dev_info['management'];
$group_name = $dev_info['group_name'];
$reviewer = $dev_info['user_name'];
$insert_time = date("Y-m-d H:i:s");
$insert_type = 2;

$table = "diagnostic_list";
$columnName = "model_name, dev_type, mac_addr, user_name, group_name, file_name, insert_time, insert_type, reviewer";
$value = "'$model_name', '$dev_type', '$mac_addr', '$user_name', '$group_name', '$file_name', '$insert_time', '$insert_type', '$reviewer'";
$sql->insert($table, $columnName, $value);
if($sql->getResult() === false){
	$sql->rollback();
	echo $red_center_span_html.get_language("Files Upload Fail!").$span_end_html.$back_button_html;
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
	echo $red_center_span_html.get_language("Files Upload Fail!").$span_end_html.$back_button_html;
	exit;
}
$sql->commit();
echo $black_center_span_html.get_language("Files successfully uploaded.").$span_end_html.$back_button_html;
?>
</body>
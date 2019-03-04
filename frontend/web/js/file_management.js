
function displayblock(id){
	$("#"+id).css("display", "block");	
}

function displaynone(id){
	$("#"+id).css("display", "none");	
}

function show_device_info(value)
{
	document.getElementById("firmware_info_show").style.display="block";
	document.getElementById("btn_submit").style.display="none";
	document.getElementById("uploadfilebtn").style.display="none";
	var firmware_info = value.split('#');
	//alert(firmware_info[0]);
	document.getElementById("firmwareName_show").value = firmware_info[1];
	document.getElementById("file_to_dev-dev_name").value = firmware_info[2];
	document.getElementById("version_show").value = firmware_info[3];
	
	document.getElementById("firmwareName_show").readOnly=true;
	document.getElementById("UploadAttachment_show").readOnly=true;
	document.getElementById("file_to_dev-dev_name").disabled=true;
	document.getElementById("version_show").readOnly=true;
	
}

function edit_device_info(value)
{
	document.getElementById("flagstatus").value="edit";
	document.getElementById("firmware_info_show").style.display="block";
	document.getElementById("btn_submit").style.display="block";
	document.getElementById("uploadfilebtn").style.display="block";
	var firmware_info = value.split('#');
	
	document.getElementById("firmwareName_show").value = firmware_info[1];
	document.getElementById("file_to_dev-dev_name").value = firmware_info[2];
	document.getElementById("version_show").value = firmware_info[3];
	
	document.getElementById("firmwareName_show").readOnly=true;
	document.getElementById("UploadAttachment_show").readOnly=false;
	document.getElementById("file_to_dev-dev_name").disabled=true;
	document.getElementById("version_show").readOnly=true;
}

function add_device_info()
{
	document.getElementById("flagstatus").value="add";
	document.getElementById("firmware_info_show").style.display="block";
	document.getElementById("btn_submit").style.display="block";
	document.getElementById("uploadfilebtn").style.display="block";
	document.getElementById("firmwareName_show").readOnly=false;
	document.getElementById("UploadAttachment_show").readOnly=false;
	document.getElementById("file_to_dev-dev_name").disabled=false;
	document.getElementById("version_show").readOnly=false;
}

function push_select_disable()
{
	document.getElementById("file_to_dev-dev_name").disabled=false;//显示机种，在浏览器F12查找,否则controller无法获取该值
	document.getElementById("file_dev_name").disabled=false;
}

function myrefresh()
{
	window.location.reload();//刷新当前页面
}

function show_device_info_2(value)
{
	document.getElementById("firmware_info_show_2").style.display="block";
	document.getElementById("btn_submit_2").style.display="none";
	document.getElementById("uploadfilebtn_2").style.display="none";
	var firmware_info = value.split('#');
	document.getElementById("firmwareName_show_2").value = firmware_info[1];
	document.getElementById("file_dev_name").value = firmware_info[2];
	document.getElementById("version_show_2").value = firmware_info[3];
	
	document.getElementById("firmwareName_show_2").readOnly=true;
	document.getElementById("UploadAttachment_show_2").readOnly=true;
	document.getElementById("file_dev_name").disabled=true;
	document.getElementById("version_show_2").readOnly=true;
}

function edit_device_info_2(value)
{
	document.getElementById("flagstatus_2").value="edit";
	document.getElementById("firmware_info_show_2").style.display="block";
	document.getElementById("btn_submit_2").style.display="block";
	document.getElementById("uploadfilebtn_2").style.display="block";
	var firmware_info = value.split('#');
	
	document.getElementById("firmwareName_show_2").value = firmware_info[1];
	document.getElementById("file_dev_name").value = firmware_info[2];
	document.getElementById("version_show_2").value = firmware_info[3];
	
	document.getElementById("firmwareName_show_2").readOnly=true;
	document.getElementById("UploadAttachment_show_2").readOnly=false;
	document.getElementById("file_dev_name").disabled=true;
	document.getElementById("version_show_2").readOnly=true;
}

function confirm_disp(delete_value)
{
	//alert(delete_value);
	jQuery.noConflict();
	$("#confirm_delete").modal("show");//显示模态框
	
	$(".btn-ok").click(function () {
		//点击确认，就ajax传值到控制器删除
		$.ajax({
			type: 'get',
			async: false,
			url: "/index.php?r=file_management/file_management",
			data: {
					delete_value:delete_value
			},
			dataType: "json",
			error: function() {
				//alert("search error!!!");
			},
			success: function (data) {
				//alert("success4 :"+JSON.stringify(data));
				myrefresh();//刷新一下页面，确认已删除
			}
		});
		$("#confirm_delete").modal("hide");
	});
	/*$(".btn-cancel").click(function () {
		$("#confirm_delete").modal("hide");
	});*/

	return false;//先返回false阻止删除，然后通过模态框的“确认”和“取消”来选择是否继续。
}

function confirm_disp_2(delete_value_2)
{
	//alert(delete_value);
	jQuery.noConflict();
	$("#confirm_delete").modal("show");//显示模态框
	
	$(".btn-ok").click(function () {
		//点击确认，就ajax传值到控制器删除
		$.ajax({
			type: 'get',
			async: false,
			url: "/index.php?r=file_management/file_management",
			data: {
					delete_value_2:delete_value_2
			},
			dataType: "json",
			error: function() {
				//alert("search error!!!");
			},
			success: function (data) {
				//alert("success4 :"+JSON.stringify(data));
				myrefresh();//刷新一下页面，确认已删除
			}
		});
		$("#confirm_delete").modal("hide");
	});
	/*$(".btn-cancel").click(function () {
		$("#confirm_delete").modal("hide");
	});*/

	return false;
}
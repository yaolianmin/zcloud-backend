
$(function () {
	$("input[name='device_type_name']").click(function () {
		var arr_device_name = new Array();
		$("input[name='device_type_name']:checked").each(function () {
			arr_device_name.push($(this).val());
		});

		$("#select_device_type").val(arr_device_name);
	});
	
	$("input[name='card_type_name']").click(function () {
		var arr_card = new Array();
		$("input[name='card_type_name']:checked").each(function () {
			arr_card.push($(this).val());
		});
		for(var i=0;i<arr_card.length;i++){
			if(arr_card[i] == "24")
				arr_card[i] = single24;
			else if(arr_card[i] == "58")
				arr_card[i] = single58;
			else if(arr_card[i] == "double24")
				arr_card[i] = double24;
			else if(arr_card[i] == "double58")
				arr_card[i] = double58;
			else if(arr_card[i] == "double2458")
				arr_card[i] = double2458;
		}
		$("#select_card_type").val(arr_card);
	});
	
	$("input[name='device_scenarios_name']").click(function () {
		var arr_scenarios = new Array();
		$("input[name='device_scenarios_name']:checked").each(function () {
			arr_scenarios.push($(this).val());
		});
		for(var i=0;i<arr_scenarios.length;i++){
			if(arr_scenarios[i] == "interior")
				arr_scenarios[i] = interior;
			else if(arr_scenarios[i] == "outside")
				arr_scenarios[i] = outside;
			else if(arr_scenarios[i] == "inout")
				arr_scenarios[i] = inout;
		}
		$("#select_application_scenarios").val(arr_scenarios);
	});
});

function displayblock(id){
	$("#"+id).css("display", "block");	
}

function displaynone(id){
	$("#"+id).css("display", "none");	
}

function show_device_Type()
{
	if(document.getElementById("device_type_List").style.display == "none")
	{
		displayblock("device_type_List");
		displaynone("card_type_List");
		displaynone("application_scenarios_List");
	}
	else
		displaynone("device_type_List");
	
}

function show_card_Type()
{
	if(document.getElementById("card_type_List").style.display == "none")
	{
		displaynone("device_type_List");
		displayblock("card_type_List");
		displaynone("application_scenarios_List");
	}
	else
		displaynone("card_type_List");
	
}

function show_application_scenarios()
{
	if(document.getElementById("application_scenarios_List").style.display == "none")
	{
		displaynone("device_type_List");
		displaynone("card_type_List");
		displayblock("application_scenarios_List");
	}
	else
		displaynone("application_scenarios_List");
}

function show_device_info(value)
{
	document.getElementById("firmware_info_show").style.display="block";
	document.getElementById("btn_submit").style.display="none";
	document.getElementById("uploadfilebtn").style.display="none";
	var firmware_info = value.split('_');
	var card_type = "";
	var ApplicationScenarios = "";
	document.getElementById("firmwareName_show").value = firmware_info[0];
	document.getElementById("DeviceType_show").value = firmware_info[1];
	if(firmware_info[2] == "s24")
		card_type = single24;
	else if(firmware_info[2] == "s58")
		card_type = single58;
	else if(firmware_info[2] == "d24")
		card_type =  double24;
	else if(firmware_info[2] == "d58")
		card_type = double58;
	else if(firmware_info[2] == "d2458")
		card_type = double2458;
	
	document.getElementById("CardType_show").value = card_type;
	
	if(firmware_info[3] == "in")
		ApplicationScenarios = interior;
	else if(firmware_info[3] == "out")
		ApplicationScenarios = outside;
	else if(firmware_info[3] == "inout")
		ApplicationScenarios = inout;
	
	document.getElementById("ApplicationScenarios_show").value = ApplicationScenarios;
	document.getElementById("version_show").value = firmware_info[4];
	
	document.getElementById("firmwareName_show").readOnly=true;
	document.getElementById("UploadAttachment_show").readOnly=true;
	document.getElementById("DeviceType_show").readOnly=true;
	document.getElementById("CardType_show").readOnly=true;
	document.getElementById("ApplicationScenarios_show").readOnly=true;
	document.getElementById("version_show").readOnly=true;
}

function edit_device_info(value)
{
	document.getElementById("firmware_info_show").style.display="block";
	document.getElementById("btn_submit").style.display="block";
	document.getElementById("uploadfilebtn").style.display="block";
	var firmware_info = value.split('_');
	var card_type = "";
	var ApplicationScenarios = "";
	document.getElementById("firmwareName_show").value = firmware_info[0];
	document.getElementById("DeviceType_show").value = firmware_info[1];
	if(firmware_info[2] == "s24")
		card_type = single24;
	else if(firmware_info[2] == "s58")
		card_type = single58;
	else if(firmware_info[2] == "d24")
		card_type =  double24;
	else if(firmware_info[2] == "d58")
		card_type = double58;
	else if(firmware_info[2] == "d2458")
		card_type = double2458;
	
	document.getElementById("CardType_show").value = card_type;
	
	if(firmware_info[3] == "in")
		ApplicationScenarios = interior;
	else if(firmware_info[3] == "out")
		ApplicationScenarios = outside;
	else if(firmware_info[3] == "inout")
		ApplicationScenarios = inout;
	
	document.getElementById("ApplicationScenarios_show").value = ApplicationScenarios;
	document.getElementById("version_show").value = firmware_info[4];
	
	document.getElementById("firmwareName_show").readOnly=false;
	document.getElementById("UploadAttachment_show").readOnly=false;
	document.getElementById("DeviceType_show").readOnly=false;
	document.getElementById("CardType_show").readOnly=false;
	document.getElementById("ApplicationScenarios_show").readOnly=false;
	document.getElementById("version_show").readOnly=false;
}



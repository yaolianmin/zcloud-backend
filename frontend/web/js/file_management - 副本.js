
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
			else if(arr_scenarios[i] == "in_out")
				arr_scenarios[i] = in_out;
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

function get_select_device_firmware()
{
	var firmware_name = document.getElementById("search_firmware").value;
	var device_type = document.getElementById("select_device_type").value;
	var card_type = document.getElementById("select_card_type").value;
	var application_scenarios = document.getElementById("select_application_scenarios").value;

	var arr_device_type = new Array();
	arr_device_type = device_type.split(",");
	
	var arr_card_type = new Array();
	arr_card_type = card_type.split(",");
	for(var i=0;i<arr_card_type.length;i++){
		if(arr_card_type[i] == single24)
			arr_card_type[i] = "s24";
		else if(arr_card_type[i] == single58)
			arr_card_type[i] = "s58";
		else if(arr_card_type[i] == double24)
			arr_card_type[i] = "d24";
		else if(arr_card_type[i] == double58)
			arr_card_type[i] = "d58";
		else if(arr_card_type[i] == double2458)
			arr_card_type[i] = "d2458";
	}
	
	var arr_application_scenarios = new Array();
	arr_application_scenarios = application_scenarios.split(",");
	for(var i=0;i<arr_application_scenarios.length;i++){
		if(arr_application_scenarios[i] == interior)
			arr_application_scenarios[i] = "in";
		else if(arr_application_scenarios[i] == outside)
			arr_application_scenarios[i] = "out";
		else if(arr_application_scenarios[i] == in_out)
			arr_application_scenarios[i] = "in_out";
	}
	/*alert("1 : "+arr_device_type);
	alert("2 : "+arr_card_type);
	alert("3 : "+arr_application_scenarios);*/
	
	//arr_device_type = JSON.stringify(arr_device_type);
	//alert(arr_device_type);
	//return;
	$.ajax({
		type: 'post',
		async: false,
		url: "/index.php?r=file_management/file_management",
		data: {
				flag : "1",
				firmware_name:firmware_name, 
				device_type:arr_device_type, 
				card_type:arr_card_type, 
				application_scenarios:arr_application_scenarios
		},
		dataType: "json",
		error: function() {
			alert("search error!!!");
		},
		success: function (data) {
			//alert("sql:"+data.count);
			//alert("success1 : "+JSON.stringify(data.result)+" length : "+data.result.length);
			/*for(var obj in data.result)
			{
				alert(JSON.stringify(data.result[obj].firmware_name));
			}*/
			show_firmware_result(data.result);
		}
	});
}

function show_firmware_result(result)
{
	displayblock("device_Firmware_List");
	displaynone("device_type_List");
	displaynone("card_type_List");
	displaynone("application_scenarios_List");
	//document.getElementById("device_Firmware_List").style.display = "block";
	var html_output = "";
	for(var obj in result)
	{
		var index = parseInt(obj)+1;
		html_output += "<tr>";
		html_output += "<td width='5%' style='border: #000000 1px solid;'>"+index+"</td>";
		html_output += "<td width='15%' style='border: #000000 1px solid;'>"+result[obj].firmware_name+"</td>";
		html_output += "<td width='14%' style='border: #000000 1px solid;'>"+result[obj].device_type+"</td>";
		html_output += "<td width='28%' style='border: #000000 1px solid;'>"+switch_info(result[obj].card_type)+"</td>";
		html_output += "<td width='20%' style='border: #000000 1px solid;'>"+switch_info(result[obj].application_scenarios)+"</td>";
		html_output += "<td width='10%' style='border: #000000 1px solid;'>"+result[obj].version+"</td>";
		html_output += "<td width='8%' style='border: #000000 1px solid;'><input type='radio' name='select_device_firmware' style='height:30px; line-height:30px;' onclick='show_device_info("+JSON.stringify(result[obj])+");'/></td>";
		html_output += "</tr>";
	}
	$("#show_firmware_result").html(html_output);
}

function switch_info(value)
{
	if(value == "s24")
		return single24;
	else if(value == "s58")
		return single58;
	else if(value == "d24")
		return double24;
	else if(value == "d58")
		return double58;
	else if(value == "d2458")
		return double2458;
	else if(value == "in")
		return interior;
	else if(value == "out")
		return outside;
	else if(value == "in_out")
		return in_out;
	else 
		return "";
}

function show_device_info(value)
{
	displayblock("device_handle");
	//alert(value.firmware_name);
}



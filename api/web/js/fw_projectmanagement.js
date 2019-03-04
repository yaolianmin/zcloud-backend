$(function(){

 

$('.btn-power').click(function(){
	
	if($('.power_span').html() == 5){
		$('.che_admin,.che_sale').css('display','none');

	}else if($('.power_span').html() == 1){
		$('.che_admin,.che_sale').css('display','none');
		//$('.che_admin').css('display','none');
	}
	if ($('.power_list').css('display') == 'none' ){

		$('.power_list').css('display','block');
	}else{

		$('.power_list').css('display','none')
	}
	$('.device_list').css('display','none')
	$('.username_list').css('display','none')
})

if($('.power_span').html() == 15 )
{
	$(".btn-add,.btn-delete,.btn-modify,.btn-four-modify,.btn-four-delete").css("display","none");
	
}


$('.btn-device').click(function(){
	
	if ($('.device_list').css('display') == 'none' ){

		$('.device_list').css('display','block');
	}else{

		$('.device_list').css('display','none');
	}
		$('.power_list').css('display','none');
		$('.username_list').css('display','none');
})




$('.btn-username').click(function(){
	
	if ($('.username_list').css('display') == 'none' ){

		$('.username_list').css('display','block');
	}else{

		$('.username_list').css('display','none');
	}
	$('.power_list').css('display','none');
	$('.device_list').css('display','none');
})



$('.power_list input[type=checkbox]').change(function(){
    var result="";
    $('.power_list input[type=checkbox]:checked').each(function(){
           result+=$(this).val()+',';
    });
    if(result!=""){
        result=result.substring(0,result.lastIndexOf(','));
    }
    $("#input-power").val(result);    
})




$('.device_list input[type=checkbox]').change(function(){

    var result="";
    $('.device_list input[type=checkbox]:checked').each(function(){
           result+=$(this).val()+',';
    });
    if(result!=""){
        result=result.substring(0,result.lastIndexOf(','));
    }
    $("#input-device").val(result);    
})



$('.username_list input[type=checkbox]').change(function(){
    var result="";
    $('.username_list input[type=checkbox]:checked').each(function(){
           result+=$(this).val()+',';
    });
    if(result!=""){
        result=result.substring(0,result.lastIndexOf(','));
    }
    $("#input-username").val(result);     
})






$('.btn-add').click(function(){
	
	$('.div-apply,.btn-four-apply,.btn-four-cancel,.div-apply-password').css('display','');
	
	$('.btn-four-modify,.btn-four-delete').css('display','none');

	$('#div-apply-modify').css('display','block');
	$(".apply_projectname_add,.apply_project_manager,.apply_project_owner,.btn-show-dev,.apply-remark").removeAttr("disabled");
	$(".apply_projectname_add,.apply_project_manager,.apply_project_owner,.btn-show-dev,.apply-remark,.device_info_show,.device_info").val('');
	$('.apply_projectname_id').val('0');
	//$('.btn-four-cancel').trigger('click');
 	//点击过后移动到 当前编辑区域
	move_to_modify_div();
	$('.apply-flag').val("add");
	 if(screen.height > document.body.scrollHeight+80){
        $('#page_footer').addClass('bottom');
    }else if(screen.height <document.body.scrollHeight+80){
        $('#page_footer').removeClass('bottom');
    } 
    if ($('.device_list_show,.fff').css('display') == 'block' ){

		$('.device_list_show,.fff').css('display','none');
	}else{

		$('.device_list_show,.fff').css('display','none');
	}

	determine();
	get_user_have_devices();
	 
})




$('.btn-find').click(function() {
	
	$('.div-apply,.btn-four-modify,.btn-four-delete').css('display','');
	if($('.power_span').html() == 15 ){

		$('.btn-four-modify,.btn-four-delete').css('display','none');
	}
	$('.btn-four-apply,.btn-four-cancel,.div-apply-password').css('display','none');

	//点击过后移动到 当前编辑区域
	move_to_modify_div();
	
	$('.apply_projectname_add').val($(this).parents('.tr-user-two').find('.td-projectname').html());
	$('.apply_project_owner').val($(this).parents('.tr-user-two').find('.td-username').html());	
	$('.apply-remark').val($(this).parents('.tr-user-two').find('.td-remark').html());

	$('.apply_project_manager').val($(this).parents('.tr-user-two').find('.td-projectmanager').html());
	$('.apply_projectname_id').val($(this).parents('.tr-user-two').find('.td-id').html());

	$('.device_info').val($(this).parents('.tr-user-two').find('.td-devinfo').html());

	//设置输入框为只读的属性
	$(".apply_projectname_add,.apply_project_manager,.apply_project_owner,.btn-show-dev,.apply-remark").attr("disabled","disabled");

	if ($('.device_list_show,.fff').css('display') == 'block' ){

		$('.device_list_show,.fff').css('display','none');
	}else{

		$('.device_list_show,.fff').css('display','none');
	}
	get_user_have_devices();
})



$('.btn-modify').click(function() {

	$('.btn-four-modify,.btn-four-delete').css('display','none');
	$('.div-apply,.btn-four-apply,.btn-four-cancel').css('display','');
	
	//点击过后移动到 当前编辑区域
	move_to_modify_div();
	$(".apply_projectname_add,.apply_project_manager,.apply_project_owner,.btn-show-dev,device_info,.apply-remark").removeAttr("disabled");
	
	$('.apply_projectname_add').val($(this).parents('.tr-user-two').find('.td-projectname').html());
	$('.apply_project_owner').val($(this).parents('.tr-user-two').find('.td-username').html());	
	$('.apply-remark').val($(this).parents('.tr-user-two').find('.td-remark').html());

	$('.apply_project_manager').val($(this).parents('.tr-user-two').find('.td-projectmanager').html());
	$('.apply_projectname_id').val($(this).parents('.tr-user-two').find('.td-id').html());
	$('.device_info').val($(this).parents('.tr-user-two').find('.td-devinfo').html());

	//$('.device_info').attr('readonly','readonly');
	$('.apply-flag').val("update");
	if ($('.device_list_show,.fff').css('display') == 'block' ){

		$('.device_list_show,.fff').css('display','none');
	}else{

		$('.device_list_show,.fff').css('display','none');
	}
	get_user_have_devices();
})



$('.btn-four-modify').click(function() {

	$('.btn-four-modify,.btn-four-delete').css('display','none');
	$('.btn-four-apply,.btn-four-cancel').css('display','');
	//点击过后移动到 当前编辑区域
	move_to_modify_div();
	$(".apply_projectname_add,.apply_project_manager,.apply_project_owner,.btn-show-dev,.apply-remark").removeAttr("disabled");
	$('.apply-flag').val("update");
	if ($('.device_list_show,.fff').css('display') == 'block' ){

		$('.device_list_show,.fff').css('display','none');
	}else{

		$('.device_list_show,.fff').css('display','none');
	}
	
	get_user_have_devices();
})

	/*
	判断哪个用户被选中
	 */
	function determine(){
		var sel = document.getElementById("projectmanagementform-project_owner");
		var bb = sel.options[sel.selectedIndex].value;
		//alert(bb.length);
		if(bb.length <= 0){
			$('.btn-show-dev').attr('disabled','disabled');
		}else{

			$('.btn-show-dev').removeAttr('disabled');
		}
		

	}
	/*
	获取某个用户拥有的机种,并生成对应的checkbox选项
 	*/
	function get_user_have_devices(){

		var sel = document.getElementById("projectmanagementform-project_owner");
		var bb = sel.options[sel.selectedIndex].value;

		var aa = document.getElementsByClassName('td-username');
		//alert(aa[1].innerHTML);
		var i = aa.length;
		for(var j=0;j<i;j++){

			if(bb == aa[j].innerHTML)
			{
				var cc =j;
				break;
			}
		}
		var s = document.getElementsByClassName('td-devinfo-userhave');
		//alert(s);
		var ss = s[cc].innerHTML;
		ff = ss.split(",");// 在每个逗号(,)处进行分解。	
		$('.fff').html('');
		for(var i = 0 ;i<ff.length - 1;i++){
			$('.fff').append("<label style='margin-right:10px'><input type='checkbox' name='ProjectManagementForm[devname_select][]' value="+ff[i]+">"+ff[i]+"</label>");
			//alert(i);
			}

	}




	var sel=document.getElementById("projectmanagementform-project_owner");
　　sel.onchange = function(){

	$('.device_info').val('');
　　　　//alert(sel.options[sel.selectedIndex].value);
	determine();

	var user_name = sel.options[sel.selectedIndex].value;
	$.ajax({
			type: 'get',
			async: false,
			url: "/index.php?r=project-management/project-management",
			data: {
					data:user_name
			},
			dataType: "json",
			error: function() {
				//alert("search error!!!");
			},
			success: function (data) {
				//alert("success4 :"+data.flag_delete);
				//alert(data.flag_delete);
				ss = data.flag_delete;
			}
		});
			//alert(ss);

				var ff = ss.split(",");// 在每个逗号(,)处进行分解。	
				//alert(ff);
				$('.fff').html('');
				for(var i = 0 ;i<ff.length - 1;i++){
					$('.fff').append("<label style='margin-right:10px'><input type='checkbox' name='ProjectManagementForm[devname_select][]' value="+ff[i]+">"+ff[i]+"</label>");
					//alert(i);
					}
	}

  
          

$('.btn-four-apply').click(function(){
	
	$('.btn-hidden-apply').trigger('click');

})





$('.btn-four-delete').click(function() {
	$('#div-apply-modify').css('display','none');
	$(".apply_projectname_add,.apply_project_manager,.apply_project_owner,.btn-show-dev,.apply-remark").removeAttr("disabled");
	//del();
})
	/*
	删除时，弹出层
	 */
  function del(){
	if(confirm("确认要删除这条记录吗?删除后将无法恢复该记录")){
		$('.apply-flag').val("delete");
		$('.btn-hidden-apply').trigger('click');
	}else{
		$(".apply_projectname_add,.apply_project_manager,.apply_project_owner,.btn-show-dev,.apply-remark").attr("disabled","disabled");
		return;
	}

}      
  
$('.btn-delete').click(function() {
	
	//$('.div-apply').css('display','none');
	$('#div-apply-modify').css('display','none');
	$(".apply_projectname_add,.apply_project_manager,.apply_project_owner,.btn-show-dev,.apply-remark").removeAttr("disabled");
	//点击过后移动到 当前编辑区域
	move_to_modify_div();
	
	$('.apply_projectname_add').val($(this).parents('.tr-user-two').find('.td-projectname').html());
	$('.apply_project_owner').val($(this).parents('.tr-user-two').find('.td-username').html());	
	$('.apply-remark').val($(this).parents('.tr-user-two').find('.td-remark').html());

	$('.apply_project_manager').val($(this).parents('.tr-user-two').find('.td-projectmanager').html());
	$('.apply_projectname_id').val($(this).parents('.tr-user-two').find('.td-id').html());
	//$('.device_info').val($(this).parents('.tr-user-two').find('.td-devinfo').html());

	//del();
})



$('.btn-show-dev').click(function(){

	if ($('.device_list_show,.fff').css('display') == 'none' ){

		$('.device_list_show,.fff').css('display','block');
	}else{

		$('.device_list_show,.fff').css('display','none');
	}
		/******************************/
	// var user_name = sel.options[sel.selectedIndex].value;
	// $.ajax({
	// 		type: 'get',
	// 		async: false,
	// 		url: "/index.php?r=project-management/show",
	// 		data: {
	// 				data:user_name
	// 		},
	// 		dataType: "json",
	// 		error: function() {
	// 			alert("search error!!!");
	// 		},
	// 		success: function (data) {
	// 			//alert("success4 :"+data.flag_delete);
	// 			//alert(data.flag_delete);
	// 			ss = data.flag_delete;
	// 		}
	// 	});
	// 		alert(ss);

	// 			var ff = ss.split(",");// 在每个逗号(,)处进行分解。	
	// 			//alert(ff);
	// 			$('.fff').html('');
	// 			for(var i = 0 ;i<ff.length - 1;i++){
	// 				$('.fff').append("<label style='margin-right:10px'><input type='checkbox' name='ProjectManagementForm[devname_select][]' value="+ff[i]+">"+ff[i]+"</label>");
	// 				//alert(i);
	// 				}
/******************************/
})



$('.device_list_show input[type=checkbox]').change(function(){
    var result="";
    $('.device_list_show input[type=checkbox]:checked').each(function(){
           result+=$(this).val()+',';
    });
    if(result!=""){
        result=result.substring(0,result.lastIndexOf(','));
    }
    $(".device_info").val(result);    
})

$('.fff').change(function(){

	var result="";
    $('.fff input[type=checkbox]:checked').each(function(){
           result+=$(this).val()+',';
    });
    if(result!=""){
        result=result.substring(0,result.lastIndexOf(','));
    }
    $(".device_info").val(result);

})

/*
弹出层中两个按钮的作用
 */

$('.btn-ok').click(function(){

		$('.apply-flag').val("delete");
		$('.btn-hidden-apply').trigger('click');

})
$('.btn-cancel').click(function(){

	return;
})




/*function:
*	点击find、modify、add按钮过后移动到 当前编辑区域
*/
function move_to_modify_div(){
	var oDiv=document.getElementById("div-apply-modify");
	oDiv.scrollIntoView();
}





})
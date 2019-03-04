$(function(){

 


//$("#btn_search_1").attr("disabled","disabled").css("background-color","#EEEEEE;");
if($('.power_span').html() == 1 || $('.power_span').html() == 5)
{
	$(".btn-add,.btn-delete").css("display","");
	
}




$(".apply-username,.apply-email,.apply-password,.apply-phone,.apply-remark,.apply-power,.apply-country,.btn-show-dev").attr("disabled","disabled").css("background-color","#EEEEEE;");


$('.btn-power').click(function(){
	
	if($('.power_span').html() == 15){
		$('.che_admin,.che_sale').css('display','none');


	}else if($('.power_span').html() == 5){
		$('.che_admin').css('display','none');
		//$('.che_admin').css('display','none');
	}
	if ($('.power_list').css('display') == 'none' ){

		$('.power_list').css('display','block');
	}else{

		$('.power_list').css('display','none')
	}
	$('.device_list').css('display','none')
	$('.project_list').css('display','none')
})




$('.btn-device').click(function(){
	
	if ($('.device_list').css('display') == 'none' ){

		$('.device_list').css('display','block');
	}else{

		$('.device_list').css('display','none');
	}
		$('.power_list').css('display','none');
		$('.project_list').css('display','none');
})




$('.btn-project').click(function(){
	
	if ($('.project_list').css('display') == 'none' ){

		$('.project_list').css('display','block');
	}else{

		$('.project_list').css('display','none');
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



$('.project_list input[type=checkbox]').change(function(){
    var result="";
    $('.project_list input[type=checkbox]:checked').each(function(){
           result+=$(this).val()+',';
    });
    if(result!=""){
        result=result.substring(0,result.lastIndexOf(','));
    }
    $("#input-project").val(result);     
})



$('.btn-three-modify').click(function(){
	if ($('.div-apply').css('display') == 'none' ){

		$('.div-apply').css('display','block');
		//点击过后移动到 当前编辑区域
		var oDiv=document.getElementById("div-apply-modify");
   		oDiv.scrollIntoView();
	}else{

		$('.div-apply').css('display','none');
	}
})


$('.btn-add').click(function(){
	var fir = document.getElementsByName("UserManagementForm[device][]");
	
	for(var i=0;i<fir.length;i++){
	    fir[i].checked = false;

	}
	$('.div-apply,#confirm-p,.btn-four-apply,.btn-four-cancel,.div-apply-password').css('display','');
	
	$('.btn-four-modify,.btn-four-delete').css('display','none');

	$('#div-apply-modify').css('display','block');
	
	$(".apply-username,.apply-email,.apply-password,.apply-phone,.apply-remark,.apply-power,.apply-country,.btn-show-dev").removeAttr("disabled");
	$('.apply-username,.apply-power,.apply-password,.apply-email,.apply-dev,.apply-country,.apply-phone,.apply-remark,.device_info,#pw1,#pw2').val('');

	if($('.power_span').html() == 5)
	{
		document.getElementById("usermanagementform-power")[1].style.display="none";
		document.getElementById("usermanagementform-power")[2].style.display="none";
	}
	if($('.power_span').html() == 1)
	{
		document.getElementById("usermanagementform-power")[1].style.display="none";
	}
	//$('.btn-four-cancel').trigger('click');
 	//点击过后移动到 当前编辑区域
 	move_to_modify_div();

	$('.apply-flag').val("add");

	open_or_close();

	 if(screen.height > document.body.scrollHeight+80){
        $('#page_footer').addClass('bottom');
    }else if(screen.height <document.body.scrollHeight+80){
        $('#page_footer').removeClass('bottom');
    } $(window).scrollTop(300);
})




$('.btn-find').click(function() {
	
	$('.div-apply,.btn-four-modify,.btn-four-delete').css('display','');
	if($('.power_span').html() == 15){
	  $('.btn-four-delete').css('display','none');
	}
	
	$('.btn-four-apply,.btn-four-cancel,.div-apply-password').css('display','none');

	//点击过后移动到 当前编辑区域
	move_to_modify_div();
	
	$('.apply-username').val($(this).parents('.tr-user-two').find('.td-name').html());
	$('.apply-email').val($(this).parents('.tr-user-two').find('.td-email').html());
	$('.apply-country').val($(this).parents('.tr-user-two').find('.td-country').html());

	$('.apply-password,.apply-confirmpassword').val($(this).parents('.tr-user-two').find('.td-password').html());

	$('.apply-phone').val($(this).parents('.tr-user-two').find('.td-phone').html());
	$('.apply-remark').val($(this).parents('.tr-user-two').find('.td-remark').html());
	$('.device_info').val($(this).parents('.tr-user-two').find('.td-devinfo').html());
	
	//设置power选择框中选项
	if($(this).parents('.tr-user-two').find('.td-power').html()== "管理员" 
		|| $(this).parents('.tr-user-two').find('.td-power').html()== "Administrators"){
		
		document.getElementById("usermanagementform-power")[1].selected=true;
	}else if($(this).parents('.tr-user-two').find('.td-power').html()== "业务员" 
		|| $(this).parents('.tr-user-two').find('.td-power').html()== "Salesman"){

		document.getElementById("usermanagementform-power")[2].selected=true;
	}else if($(this).parents('.tr-user-two').find('.td-power').html()== "普通用户" 
		|| $(this).parents('.tr-user-two').find('.td-power').html()== "NormalUser"){

		document.getElementById("usermanagementform-power")[3].selected=true;
	}

  	checkbox_checked();
  	open_or_close();
	//设置输入框为只读的属性
	
	$(".apply-username,.apply-email,.apply-password,.apply-phone,.apply-remark,.apply-power,.apply-country,.btn-show-dev").attr("disabled","disabled").css("background-color","#EEEEEE;");
	 
	
	 if(screen.height > document.body.scrollHeight+80){
        $('#page_footer').addClass('bottom');
    }else if(screen.height <document.body.scrollHeight+80){
        $('#page_footer').removeClass('bottom');
    } $(window).scrollTop(300);
})



$('.btn-modify').click(function() {

	$('.div-apply-password,.btn-four-modify,.btn-four-delete').css('display','none');
	$('.div-apply,#confirm-p,.btn-four-apply,.btn-four-cancel').css('display','');
	
	//点击过后移动到 当前编辑区域
	move_to_modify_div();

	$(".apply-email,.apply-password,.apply-phone,.apply-country,.apply-remark,.apply-username,.apply-power,.btn-show-dev").removeAttr("disabled");

	// if($('.power_span').html() == 1 )
	// {
	// 	$(".apply-username,.apply-power,.btn-show-dev").removeAttr("disabled");
	// }

	
	$('.apply-username').val($(this).parents('.tr-user-two').find('.td-name').html());

	$('.apply-email').val($(this).parents('.tr-user-two').find('.td-email').html());
	$('.apply-country').val($(this).parents('.tr-user-two').find('.td-country').html());

	$('.apply-password,.apply-confirmpassword').val("no_changepassword");

	$('.apply-phone').val($(this).parents('.tr-user-two').find('.td-phone').html());
	$('.apply-remark').val($(this).parents('.tr-user-two').find('.td-remark').html());
	$('.device_info').val($(this).parents('.tr-user-two').find('.td-devinfo').html());

	

	if($(this).parents('.tr-user-two').find('.td-power').html()== "管理员" 
		|| $(this).parents('.tr-user-two').find('.td-power').html()== "Administrators"){
		
		document.getElementById("usermanagementform-power")[1].selected=true;
	}else if($(this).parents('.tr-user-two').find('.td-power').html()== "业务员" 
		|| $(this).parents('.tr-user-two').find('.td-power').html()== "Salesman"){

		document.getElementById("usermanagementform-power")[2].selected=true;
	}else if($(this).parents('.tr-user-two').find('.td-power').html()== "普通用户" 
		|| $(this).parents('.tr-user-two').find('.td-power').html()== "NormalUser"){

		document.getElementById("usermanagementform-power")[3].selected=true;
	}
	
	if($('.power_span').html() == 15 ||  $('.power_span').html() == 5)
	{
		$(".apply-username,.apply-power,.btn-show-dev").attr('disabled','disabled');
	}
	if($('.power_span').html() == 1){

		$(".apply-username").attr('disabled','disabled');
	}
	checkbox_checked();
	open_or_close();

	$('.apply-flag').val("update");
	 if(screen.height > document.body.scrollHeight+80){
        $('#page_footer').addClass('bottom');
    }else if(screen.height <document.body.scrollHeight+80){
        $('#page_footer').removeClass('bottom');
    } $(window).scrollTop(300);

})



$('.btn-four-modify').click(function() {

	$('.btn-four-modify,.btn-four-delete,.div-apply-password').css('display','none');
	$('#confirm-p,.btn-four-apply,.btn-four-cancel').css('display','');

	move_to_modify_div();
	$(".apply-email,.apply-password,.apply-phone,.apply-country,.apply-remark").removeAttr("disabled");
	if($('.power_span').html() == 1 )
	{
		$(".apply-power,.btn-show-dev").removeAttr("disabled");
	}
	$('.apply-password,.apply-confirmpassword').val("no_changepassword");
	$('.apply-flag').val("update");

	open_or_close();
	
})



$('.btn-four-apply').click(function(){
	$('.apply-username,.apply-power,.btn-show-dev').removeAttr("disabled");
	
	var pw1 = document.getElementById("pw1").value;
	var pw2 = document.getElementById("pw2").value;

	if(pw1 == pw2){
			
			 $('.apply-power').removeAttr("disabled");
			 $('.btn-hidden-apply').trigger('click');
		}else {
			 /*******************设置模态框 属性**********************/
			 $('.modal-header').html('');
			
			 $('.modal-body').html($('.password_failed').html());
			 $('.modal-body').css('text-align','center');
			 $('.modal-dialog').css('width','20%');
			 $('.modal-backdrop').css('opacity','0.2');
		     /******************************************************/
			$('.btn-modal-show').trigger('click');
			return;
		
	}

})



$('.btn-four-delete').click(function() {
	$('#div-apply-modify').css('display','none');
	$(".apply-username,.apply-power,.apply-email,.apply-password,.apply-phone,.apply-remark").removeAttr("disabled");

	
})



/*************************************************************
*删除时的提示框中的确认和取消按钮
 */
$('.btn-ok').click(function(){
	//alert("不删除");
		$('.apply-flag').val("delete");
		$('.btn-hidden-apply').trigger('click');
})

$('.btn-cancel').click(function(){

	return;
})
/****************************************************/




  function del(){
	if(confirm("确认要删除这条记录吗?删除后将无法恢复该记录")){
		$('.apply-flag').val("delete");
		$('.btn-hidden-apply').trigger('click');
	}else{
		//alert("不删除");
		return;
	}

}      
 

$('.btn-delete').click(function() {
	$('#div-apply-modify,.div-apply-password').css('display','none');

	$(".apply-username,.apply-email,.apply-password,.apply-phone,.apply-remark,.apply-power,.btn-show-dev").removeAttr("disabled");
	
	//点击过后移动到 当前编辑区域
	move_to_modify_div();

	$('.apply-username').val($(this).parents('.tr-user-two').find('.td-name').html());
	
	$('.apply-email').val($(this).parents('.tr-user-two').find('.td-email').html());
	$('.apply-country').val($(this).parents('.tr-user-two').find('.td-country').html());
	//$('.apply-password').val($(this).parents('.tr-user-two').find('.td-password').html());
	$('.apply-password,.apply-confirmpassword').val("no_changepassword");
	$('.apply-phone').val($(this).parents('.tr-user-two').find('.td-phone').html());
	$('.apply-remark').val($(this).parents('.tr-user-two').find('.td-remark').html());

	if($(this).parents('.tr-user-two').find('.td-power').html()== "管理员" 
		|| $(this).parents('.tr-user-two').find('.td-power').html()== "Administrators"){
		
		document.getElementById("usermanagementform-power")[1].selected=true;
	}else if($(this).parents('.tr-user-two').find('.td-power').html()== "业务员" 
		|| $(this).parents('.tr-user-two').find('.td-power').html()== "Salesman"){

		document.getElementById("usermanagementform-power")[2].selected=true;
	}else if($(this).parents('.tr-user-two').find('.td-power').html()== "普通用户" 
		|| $(this).parents('.tr-user-two').find('.td-power').html()== "NormalUser"){

		document.getElementById("usermanagementform-power")[3].selected=true;
	}

})



$('.btn-show-dev').click(function(){

	if ($('.device_list_show').css('display') == 'none' ){

		$('.device_list_show').css('display','block');
	}else{

		$('.device_list_show').css('display','none');
	}
			
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




/*function;
*	获取用户所拥有机种，并将checkbox中的值选中
*/
function checkbox_checked(){
	
	/*
	*每切换一个用户，都要清除checkbox中的选项
	*/
	var fir = document.getElementsByName("UserManagementForm[device][]");
	
	for(var i=0;i<fir.length;i++){
	    fir[i].checked = false;

	}
	/*清除过后根据用户拥有机种选择
	*
	 */
	var ss = $('.device_info').val();
	var ff = ss.split(",");
	for(var i=0;i<ff.length;i++){
		$(".device_list_show input[name= 'UserManagementForm[device][]']").each(function(){  
	  			
	         if($(this).val() == ff[i])    
	         {  
	            $(this).prop('checked','true');    
	         }  

    	}); 
	}
}



/*function：
*	在点击find并打开机种选择未关闭，再切换修改时
*	设定checkbox的关闭与打开
*/
function open_or_close(){

	if ($('.device_list_show').css('display') == 'block' ){

		$('.device_list_show').css('display','none');
	}else{

		$('.device_list_show').css('display','none');
	}
}

/*function:
*	获取表单中的只填充到对应的输入框中
*/

function set_info_in_input(){

	$('.apply-username').val($(this).parents('.tr-user-two').find('.td-name').html());
	$('.apply-email').val($(this).parents('.tr-user-two').find('.td-email').html());
	$('.apply-country').val($(this).parents('.tr-user-two').find('.td-country').html());
	$('.apply-phone').val($(this).parents('.tr-user-two').find('.td-phone').html());
	$('.apply-remark').val($(this).parents('.tr-user-two').find('.td-remark').html());
	$('.device_info').val($(this).parents('.tr-user-two').find('.td-devinfo').html());

}

/*function:
*	点击find、modify、add按钮过后移动到 当前编辑区域
*/
function move_to_modify_div(){
	var oDiv=document.getElementById("div-apply-modify");
	oDiv.scrollIntoView();
}






})
$(function(){

 
$('.btn-modify-info').click(function() {
	
	$('.btn-modify-info,.btn-modify-password').css('display','none');
	$('.btn-three-apply,.btn-three-cancel').css('display','');
	
	//$('.device_info').removeAttr('readonly');
	//$('.btn-show-dev').removeAttr('disabled');
	
	$(".apply-flag,.apply-email,.apply-password,.device_info,.apply-confirmpassword,.apply-phone,.apply-country,.apply-remark,#usermanagementform-power,#usermanagementform-country").removeAttr("disabled");
	$('.apply-password').val("no_changepassword");
	//$('.apply-username').attr('readonly','readonly');
	$('.apply-flag').val("update");
	
})


$('.btn-modify-password').click(function(){
	window.location.href="/index.php?r=user-center/modify-password";

	//$('.btn-modify-info,.btn-modify-password,.user_prinfo').css('display','none');
	//$('.btn-password-sure,.btn-three-cancel,.div-apply-password').css('display','');

	//$('.device_info').removeAttr('readonly');
	//$('.btn-show-dev').removeAttr('disabled');	
	//$(".apply-flag,.apply-email,.apply-password,.device_info,.apply-confirmpassword,.apply-phone,.apply-country,.apply-remark,#usermanagementform-power,#usermanagementform-country").removeAttr("disabled");


})

$('.btn-three-cancel').click(function(){
    window.location.href="/index.php?r=user-center/user-center";
})

//忘记密码
$('.btn-password-forget').click(function(){

    window.location.href="/index.php?r=index/forget&forget=1";
})



$('.btn-password-sure').click(function(){

	var pw1 = document.getElementById("pw1").value;
	var pw2="123456";
	alert(md5(pw2));
	alert(pw1);

})




$('.btn-three-apply').click(function() {

$('.apply-username,.apply-power').removeAttr("disabled");
$('.btn-hidden').trigger('click');

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



})
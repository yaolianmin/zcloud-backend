
/**
*刷新验证码
*/


$(function(){

$('#code').click(function(){
	 $(this).attr('src', '/index.php?r=verify/verify&i='+Math.random());


})






})
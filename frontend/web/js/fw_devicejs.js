$(function(){


   //判断是否存在提示信息，并把页面聚焦底部
  if($('.tips').html()){

  $(window).scrollTop(300);
  
  }
   

 //设备类型按钮
	$('.add1').click(function(){

		$('.dec_select2').hide(); 
		$('.dec_select3').hide();
		$('.dec_select1').toggle();

		
	})
	$('.dec_select1 label').click(function(){
		if($(this).find('input').prop('checked')){
			$('.dec_kinds1 li').eq($(this).index()).css('display','block');
		}else{
			$('.dec_kinds1 li').eq($(this).index()).css('display','none');
		}
		
	})

//卡类型按钮	
	$('.add2').click(function(){

		$('.dec_select1').hide(); 
		$('.dec_select3').hide();
		$('.dec_select2').toggle();

    
   
	})

	$('.dec_select2 label').click(function(){
		if($(this).find('input').prop('checked')){
			$('.dec_kinds2 li').eq($(this).index()).css('display','block');
		}else{
			$('.dec_kinds2 li').eq($(this).index()).css('display','none');
		}
		
	})


//应用场景
	$('.add3').click(function(){

		$('.dec_select1').hide(); 
		$('.dec_select2').hide();
		$('.dec_select3').toggle();
    
    
		
	})
	$('.dec_select3 label').click(function(){
		if($(this).find('input').prop('checked')){
			$('.dec_kinds3 li').eq($(this).index()).css('display','block');
		}else{
			$('.dec_kinds3 li').eq($(this).index()).css('display','none');
		}
		
	})

 //点击修改按钮
 $('.btn-modify').click(function(){
 	
 	$('.bottoms').show();
 	$('.devname').focus();
    $('.dev_name').val($(this).parents('.con').find('.devname').html());
    $('.dec_type').val($(this).parents('.con').find('.dectype').html());
    $('.card_type').val($(this).parents('.con').find('.card').html());
    $('#index').val($(this).parents('.con').find('.id').val());
    $('.remark').val($(this).parents('.con').find('.remarks').html());
   
   
    $(window).scrollTop(300);

 })


 //添加机种按钮
  $('#add_dev').click(function(){
  		$('.bottoms').show();
  		$('.dev_name').focus();
  		
  	
    $(window).scrollTop(300);
  })
  


  //删除机种操作 
  $('.btn-delete').click(function(){
      var del_id = $(this).next().val();
      jQuery.noConflict();
      $("#confirm_delete").modal("show"); //弹出层显示
      //点击确定
  		$('.btn-ok').click(function(){
        
           window.location.href = '/index.php?r=device/device&delete='+del_id; 
      })
  })



  
})



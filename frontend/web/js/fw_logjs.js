$(function(){
    
   
    /**
    * 提交用户级别
    */
    $('#power').change(function(){
    	window.location.href ='/index.php?r=log/log&power='+$(this).val();

    })

    /**
    * 日志类型 "+" 操作
    */
    $('.sun1').click(function(){
        
        $('.log_type_content2').hide();
        $('.log_type_content').toggle();  

        if($('.width4').offset().top > document.body.clientHeight-50){
          $('#page_footer').removeClass('bottom');
        }else{
           $('#page_footer').addClass('bottom');
        }
       
    })

    /**
    * 日志级别 "+" 操作
    */
     $('.sun2').click(function(){

        $('.log_type_content').hide();
        $('.log_type_content2').toggle();


         if($('.width4').offset().top > document.body.clientHeight-50){
          $('#page_footer').removeClass('bottom');
        }else{
           $('#page_footer').addClass('bottom');
        }
    })

     $('.log_type_content label').click(function(){
        if($(this).find('input').prop('checked')){
           $('.log_type_contents li').eq(5-$(this).index()).css('display','block');
        } else{
           $('.log_type_contents li').eq(5-$(this).index()).css('display','none');
        }
        
     })


      $('.log_type_content2 label').click(function(){
        if($(this).find('input').prop('checked')){
            $('.log_type_content2s li').eq(2-$(this).index()).css('display','block');
        } 
        if(!$(this).find('input').prop('checked')){
            $('.log_type_content2s li').eq(2-$(this).index()).css('display','none');
        }
        
     })

    

      if($('.width4').offset().top > document.body.clientHeight-50){
          $('#page_footer').removeClass('bottom');
        }else{
           $('#page_footer').addClass('bottom');
        }
})
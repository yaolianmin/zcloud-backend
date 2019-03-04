<?php
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
$this->title='智威亚科技有限公司';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>智威亚科技有限公司</title>
    <style type="text/css">
    *{
        padding: 0;
        margin: 0;
    }
    body{
        background: url('./images/timg6.jpg');
        background-size: cover; 
        filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src='./images/timg6.jpg',  sizingMethod='scale');
        behavior: url(backgroundsize.min.htc);
    }
    .header{
        width: 100%;
        height: 66px;
    }
    .logo{
        float: left;
        margin-left: 200px;
    }
    .header span{
        float: left ;
        margin-left: 40px;
        font-size: 20px;
        color: #fff;
        line-height: 66px;
    }
     table{
        width: 500px;
        height: 400px;
        margin: 100px 660px;  
     }
     table input{
        height: 24px;
        width: 162px;
        outline: none;
        border: none;
        background-color: rgba(60,131,183,0.4);
     }
     tr{
        border: 2px solid red;
        margin-top: 30px;
     }
    table .tel,table .e-mail{
        width:160px;
        height:25px;
        text-align:center;
        line-height:36px;    
    }
    .tel,.e-mail:hover{
        cursor:pointer;
    }
    .class{
        background-color:rgba(212,183,179,0.4);
        border-bottom: 2px solid blue;
    }
    .vertify{
        width: 90px;
    }
    .ver{
        position: relative;
        top: -2px;
        width: 50px;
        margin-left: -50px;
        cursor:pointer;
    }
    .xing{
        color:red;

        margin-top: 20px;
    }
    #return{
        width: 100px;
        float: right;
        background-color: #3389C6;
        margin-top: 10px;
    }
    #submit{
        margin-top: 10px;
        background-color: #3389C6;
        width:100px;
        height: 24px;
        border:none;   
    }
    .help-block{
        font-size: 10px;
        color: blue;
    }
    </style>
	<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
	<script type="text/javascript">
	$(function(){
        var  he= $(document).height()+'px';
        $('body').height(he); 	

		/**
		*修改手机或邮箱样式
		*/
		$('.tel').click(function(){
			$('.e-mail').removeClass('class');

			$(this).addClass('class');

			$('.name').focus();

			$('.change').html('手机号:');
		})

		$('.e-mail').click(function(){

			$('.tel').removeClass('class');

			$(this).addClass('class');

			$('.name').focus();
             
            $('.change').html('邮箱号:');

		})

        /**
        *刷新验证码
        */
        $('.ver').click(function(){
            $(this).attr('src', '/index.php?r=verify/verify&i='+Math.random());
    
        })

        /**
        * 返回登陆页面
        */
        $('#return').click(function(){
            window.location.href="/index.php?r=index/index";
        })
	})
	</script>
</head>
<body>
  <!-- 头部logo -->
	<div class="header">
		<img src="logo.gif" class="logo">
		<span>智微亚后台管理系统</span>
	</div> 
	<!--找回密码-->
	<div>
		<table border="0" cellpadding="0" cellspacing="0">
            <?php $form  = ActiveForm::begin([
                'action' => ['index/forget'],
                'method' =>'post'
            ]);?>
			<tr>
				<td class="tel class">手机找回</td>
				<td class="e-mail" >邮箱找回</td>
			</tr>
			<tr style="width:200px;">
				<td><hr style="height:1px;border:none;border-top:1px solid #555555;width:300%;margin-top:10px;margin-left:-30px;margin-bottom:10px;" /></td>
			</tr>
			<tr>
				<td>用户名：</td>
				<td><?=$form->field($info,'user_name')->textInput()->label('')?></td>
				<td class="xing">*</td>
			</tr>
			<tr>
				<td class="change">手机号：</td>
				<td><?=$form->field($info,'phone')->textInput(['class' => 'phone'])->label('')?></td>
				<td class="xing">*</td>
			</tr>
			<tr>
				<td>新密码：</td>
				<td><?=$form->field($info,'passwords')->passwordInput()->label('')?></td>
				
			</tr>
			<tr>
				<td>确认密码：</td>
				<td><?=$form->field($info,'re_password')->passwordInput()->label('')?></td>
				
			</tr>
			<tr>
				<td>验证码：</td>
				<td><?=$form->field($info,'verify')->textInput(['class' => 'vertify'])->label('')?></td>
				<td><img src="<?=Url::to(['verify/verify']);?>" class="ver"></td>
				
			</tr>
			<tr>
				<td><?= Html::submitButton('找回密码', ['id'=>'submit'])?></td>		
           <?php
            $form->end();
           ?>
        <td><input type="submit" id="return" value="返回" /></td>
        </tr>
		</table>
	</div>
</body>
</html>
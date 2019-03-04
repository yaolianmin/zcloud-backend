<?php
use yii\helpers\Url;//引入助手类
use yii\widgets\ActiveForm;//引用小部件，生成表单
use yii\helpers\Html;
$this->title='智威亚科技有限公司';	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<link rel="icon"  href="images/favicon.ico">
    <title>智威亚科技有限公司</title>
	<style type="text/css">
    *{
        margin: 0;
        padding: 0;
    }
    body{
    	background: url('./images/loginbg.jpg');
    	background-size: cover; 
    	filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src='./images/loginbg.jpg',sizingMethod='scale');
    	behavior: url(backgroundsize.min.htc);
    }
    .logo{
    	float: left;
    	position: fixed;
    	top:4px;
    	left: 200px;
    }
    .header{
    	width: 100%;
    	height: 66px;
    }
    .header span{
    	position: fixed;
    	top: 0px;
    	left:350px; 
    	font-size: 22px;
    	color: #fff;
    	line-height: 66px;
    }
	.form{
		width: 400px;
		height: 400px;
		/*border: 1px solid red;*/
		margin:10% auto;
		background-color:rgba(240,238,235,0.4);
		/*//position: absolute;*/
		font-weight: bold;
		size:100%;

		font-size: 15px;
		color: #595950;
	}
	.form tr td{
		margin-top: 10px;
		float: left;
	}
	
    .form caption{
    	margin:0 auto;
    	margin-bottom: 20px;
        height: 40px;
        text-align: center;
        line-height: 40px;
        width: 100%;
        /*border: 1px solid red;*/
        background-color: #3FA2C0;
        border-top-left-radius: 6px;
        border-top-right-radius: 6px;
        color: #fff;
        margin-bottom: 60px;
    }
    .form input{
    	height: 24px;
    	width: 150px;
    	border: none;
        outline: none;
    }
    .first{
    	margin-top: -20px !important;
    }
	
	.left{
		width: 70px;
		width: 90px;
		/*border: 1px solid red;*/
	}
	.usename{
		float: left;
		margin-left: 30px;
	}
	.password{
		float: left;
		margin-left: 30px;
	}
	.vertify{
		float: left;
		margin-left: 30px;
	}
	#language{
		margin-top: 0px;
		margin-left: 30px;
	}
    .xing{
    	color:red;
    	margin-top: 6px;
    	width: 10px;
    	font-size: 14px;
    	/*border: 1px solid red;*/
    	padding-top: 4px;
    }    
    #code{
    	float: left;
    	width: 50px;
    	margin-top: -52px;
    	margin-left: 220px;
    }
    #code:hover{
    	cursor:pointer;
    }
    .forget{
    	width: 100px;
    	font-size: 12px;
        position: relative;
        top: 24px;
        left: 110px;
        color: #8F8500;
    }
	#login,#button{
		width: 80px;
        height: 24px;
		background-color: #006FB4;
		color: black;
        color: #fff;
		margin-top: 20px;
		border-radius: 50%;

	}
	select{
		border: none;
	}
	.select{
		margin-top: -10px;
		height: 20px;
		text-align:center;
	}
	.select option{
		width: 70px;
		text-align:center;
		border: none;
	}
	#login{
        margin-left: 30px;
	}
	#button{
    	position: relative;
    	top: 0px;
    	left: 90px;
    }
    .help-block{
    	font-size: 12px;
    	height: 18px;
    	width: 140px;
    	color: #6C5959;
    }
    #ver{
    	width: 90px;
    }
	</style>
	<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
	<script type="text/javascript" src="js/fw_verifyjs.js"></script>
	<script type="text/javascript">
	$(function(){
		var  he= $(document).height()+'px';
		$('body').height(he);

        //检测浏览器版本.过低则不给进入
        var ie =  IEVersion();
        if(ie<9){
           $('.form').css('display','none');
           alert('您的IE浏览器版本过低，无法使本网站的功能，请更新浏览器再登录！');
            
        }   
       
	})

	
	 function IEVersion() {
            var userAgent = navigator.userAgent; //取得浏览器的userAgent字符串  
            var isIE = userAgent.indexOf("compatible") > -1 && userAgent.indexOf("MSIE") > -1; //判断是否IE<11浏览器  
            var isEdge = userAgent.indexOf("Edge") > -1 && !isIE; //判断是否IE的Edge浏览器  
            var isIE11 = userAgent.indexOf('Trident') > -1 && userAgent.indexOf("rv:11.0") > -1;
            
            if(isIE) {
                var reIE = new RegExp("MSIE (\\d+\\.\\d+);");
                reIE.test(userAgent);
                var fIEVersion = parseFloat(RegExp["$1"]);
                if(fIEVersion == 7) {
                    return 7;
                } else if(fIEVersion == 8) {
                    return 8;
                } else if(fIEVersion == 9) {
                    return 9;
                } else if(fIEVersion == 10) {
                    return 10;
                } else {
                    return 6;//IE版本<=7
                }   
            } else if(isEdge) {
                return 'edge';//edge
            } else if(isIE11) {
                return 11; //IE11  
            }else{
                return 15;//不是ie浏览器
            }
    }
       
	</script>
</head>
<body>
    <!-- 头部logo -->
	<!-- <div class="header">
		<img src="logo.gif" class="logo">
		<span>智微亚后台管理系统</span>
	</div> -->
     <!-- 登陆模块 -->
    <div class="form" id="form"> 
        <table>
	    <?php $form = ActiveForm::begin([
	    	            'action' =>['index/index'],
	    				'method' => 'post'
	    ]);?>
	        <caption>请登陆</caption>
	    	<tr  class="first">
		    	<td class="usename left">用户名：</td>
		    	<td><?=$form->field($model,'user_name')->textInput()->label('')?></td>
		    	<td></td>
	    	</tr>
	    	<tr> 
	    		<td class="password left">密码：</td>
	    		<td><?=$form->field($model,'password')->passwordInput()->label('')?></td>
	    		<td></td>
	    	</tr>
	    	
	        <tr>
	         	<td class="vertify left" >验证码：</td>
	        	<td><?=$form->field($model,'verify')->textInput(['id' =>'ver'])->label('')?></td>
	         	<td><img src="/index.php?r=verify/verify" id='code'></td>
	        </tr>
	        <tr >
	        	<td class="language left" id="language">语言：</td>
	        	<td>
	        	   <?= $form->field($model, 'language')->dropDownList(['zh-CN'=>'中文','en'=>'English','zh-TW'=>'中文简体'], ['class'=>'select'])->label('') ?>
	            </td>
	        </tr>
	    	<tr class="login">
	    		<td><?= Html::submitButton('登录', ['id'=>'login'])?></td>
	    		<td class="button"><?= Html::resetButton('重置',['id'=>'button'])?></td>
	    		<td><a href="<?= Url::to(['index/forget']); ?>" class="forget">忘记密码？</a></td>		
	    	</tr>
	    <?php
 		$form->end();
	    ?>
        </table>
    </div>
</body>
</html>
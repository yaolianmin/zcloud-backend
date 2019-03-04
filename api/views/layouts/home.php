<?php
/* @var $this \yii\web\View */
/* @var $content string */
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;

AppAsset::register($this);
$this->registerJsFile('js/jquery-1.10.2.min.js');
$this->registerCssFile('css/fw_headercss.css');//加载CSS样式
$this->registerJsFile('js/fw_headerjs.js');//加载JS样式
$this->registerJsFile('js/menu.js');

$controllerID = \Yii::$app->controller->id;
$actionID = \Yii::$app->controller->action->id;
$url =  $controllerID.'/'.$actionID;
?>
<?php $this->beginPage()?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta  charset='utf-8' />
<!-- <title>Home</title> -->
<title><?= Html::encode($this->title) ?></title>
<?php $this->head() ?>
</head>
<body onload="init()">
<?php $this->beginBody() ?>
<div id="page_container">

      <!--头部公共样式-->
      <div class="logo" id="logo_tag">
          <!-- <img src="./images/logo.gif"> -->
          <div class="right">
              
              <select class="language" id="language" style="text-aline:center">
                    <option value="zh-CN">中文</option>
                    <option value="en">English</option>
                    <option value="zh-TW">中文繁體</option>
              </select>
              <button class="quit"><?=\Yii::t('yii','Sign Out');?></button>
          </div>
      </div>

      <div class="info" id="info_tag"> 
          <ul class="user">
              <li><?=\Yii::t('yii','Current User');?>:</li>
              <li class="user-centos"><?php if(isset(\Yii::$app->session['user_name'])){echo \Yii::$app->session['user_name'];}else{echo '请登录';}?></li>
              <li class="show" id="show"><?=\Yii::t('yii','Homepage');?></li>
          </ul>
          <div class="right-item">
              <ul class="item">
                   <li class="main it"><?=\Yii::t('yii','Homepage');?></li>
                  <li class="manage_ it"><?=\Yii::t('yii','Administration');?></li> 
                  <li class="user_center it"><?=\Yii::t('yii','UserCenter');?></li>
                  <ul class="items" id="items">
                      <li id="document"><?=\Yii::t('yii','File Management');?></li>
                      <li class = "project_management" id="project"><?=\Yii::t('yii','Project Management');?></li>
                      <li class = "user_management"   id="user_management"><?=\Yii::t('yii','User Management');?></li>
                      <li class = "log_management" id="log_management"><?=\Yii::t('yii','Log Management');?></li>
                      <li class="device"><?=\Yii::t('yii','Device Management');?></li>
                     <!--  <li id="Upgrade"><?=\Yii::t('yii','Upgrade Management');?></li>   -->
                  </ul>    
              </ul>
          </div>
          
      </div>
    <!--content-->
    <div id="page_content">
      <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
    <!--Footer-->
     <!-- <div id="page_footer" class='bottom'>
	
        <div class="foot">Zcom Firmwore Server 版权所有 2017-2020</div>
	
     </div> -->
</div>
 
<?php $this->endBody() ?>
</body>
<script type="text/javascript">

  //获得session中的语种
  var lan_select = "<?=\Yii::$app->session['language']?>";
  //修改下拉框中的语言
  function init() {
      $(".language").val(lan_select);
  } 

  //下拉框更换语言
   var  language =document.getElementById('language');//获得下拉框中的值
        language.onchange = function(){
          window.location.href="/index.php?r=<?=$url?>&lang="+this.value;//获得当前页面的路径，并把语言提交到相应的后台
        }


  var log_management = document.getElementById('log_management');
  var user_management = document.getElementById('user_management');
  var main = document.getElementsByClassName('main');
  var Upgrade = document.getElementById('Upgrade');
  var power = "<?=\Yii::$app->session['power']?>";

     if(power>5){//代表普通用户
       log_management.style.display = 'none';
       user_management.style.display = 'none';
       //Upgrade.style.display = 'none';
     }

     //判断权限，显示主页
     if(power==1){
        main[0].style.visibility = 'visible';
     }
  
</script>
</html>
<?php $this->endPage() ?>

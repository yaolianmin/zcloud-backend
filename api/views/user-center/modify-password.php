<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;


$this->title = Yii::t('yii','UserCenter');
$this->registerCssFile('css/fw_usermanagement.css');
$this->registerJsFile('js/jquery-1.10.2.min.js');
$this->registerJsFile('js/fw_usercenter.js');

?>
<div class="div-apply" id="div-apply-modify" style="margin-bottom:10%;margin-top:10%">
        <?php $form = ActiveForm::begin(['id' => 'abc-form',
                'fieldConfig' => [
                    'template' => 
              '<div class="input-group" style="width:100%;text-algin:center;margin-top:30px;margin-left:15%;" >
                  <span class="input-group-addon" style="margin-left:10%;width:15%;color:#fff;background-color:#337ab7;border-color: #2e6da4">
                  {label}
                  </span>
                  <div class="from-input-css " style="width:60%;float:left">
                  {input}
                  </div>
                  <div class="from-error-css " style="width:20%;float:left;margin-left:5px">
                  {error}
                  </div>                 
              </div>',

                    'inputOptions' => ['class' => 'form-control','style'=>'width:100%;height:35px;border-top:1px solid #ccc;
                                        border-bottom:1px solid #ccc;border-right:1px solid #ccc;border-left:1px solid #ccc;'],
                                        //这里配置input里面的样式
                    'options' => ['class' => 'form-horizontal t-margin20','id'=>'form1','enctype'=>"multipart/form-data"],
                ],

                ]); ?>
                

            <div class="div_modify_password" >
                <?= $form->field($password, 'primary_password')->passwordInput(['maxlength' => 20,'class'=>'apply_primary_password','id'=>'pw1','placeholder'=>Yii::t('yii','Please input primary password!')])->label(Yii::t('yii','Primary Password')) ?>

                <?= $form->field($password, 'new_password')->passwordInput(['maxlength' => 20,'class'=>'apply_new_password','id'=>'pw1'])->label(Yii::t('yii','New Password')) ?>

                <?= $form->field($password, 're_password')->passwordInput(['maxlength' => 20,'class'=>'apply_confirmpassword','id'=>'pw2'])->label(Yii::t('yii','Confirmpassword')) ?>
               
              </div>
             <!--  <div style="text-align:center">
                <a class='tips' style="text-align:center;color:red"><?php if(isset($tips)&&($tips!='success')){echo $tips;}?></a>
              </div> -->
                <div style="display:none">
                <?= $form->field($password, 'flag')->textInput(['maxlength' => 20,'class'=>'apply-flag','style'=>'display:none'])?> 
                </div>

                <br>
                 <div style="text-align:center;">
                <?= Html::Button(Yii::t('yii','Apply'), ['class' => 'btn btn-primary btn-three-apply','style' => 'width:8%;margin-right:4%;display:none']) ?>  
                <?= Html::submitButton(Yii::t('yii','Modify'), ['class' => 'btn-hidden','style' => 'display:none']) ?>
                <?= Html::submitButton(Yii::t('yii','Confirm'), ['class' => 'btn btn-primary btn-password-sure','style' => 'width:15%;margin-right:4%;']) ?>
                <?= Html::Button('修改信息', ['class' => 'btn btn-primary btn-modify-info','style' => 'width:8%;margin-right:4%;display:none']) ?>
                <?= Html::Button(Yii::t('yii','Forget Password'), ['class' => 'btn btn-primary btn-password-forget','style' => 'width:15%;']) ?>
                <?= Html::resetButton(Yii::t('yii','Cancel'), ['class' => 'btn btn-primary btn-three-cancel', 'style' =>'width:15%;display:none']) ?>
                <?= Html::Button('修改密码', ['class' => 'btn btn-primary btn-modify-password','style' => 'width:8%;display:none']) ?>
                </div>
            <?php ActiveForm::end();?>
</div>


<script src="js/jquery-1.10.2.min.js"></script><!---先导入jQuery再导入bootstrap---->
<script>
//显示当前模块
  var show = document.getElementById('show');
      show.innerHTML="<?= Yii::t('yii','UserCenter');?>";
</script>
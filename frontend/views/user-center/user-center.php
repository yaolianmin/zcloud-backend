<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\UserCenterForm */

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
                  <div class="from-error-css " style="width:40%;float:left;">
                  {error}
                  </div>                 
              </div>',

                    'inputOptions' => ['class' => 'form-control','style'=>'width:100%;height:35px;border-top:1px solid #ccc;
                                        border-bottom:1px solid #ccc;border-right:1px solid #ccc;border-left:1px solid #ccc;','disabled'=>'disabled'],//这里配置input里面的样式
                    'options' => ['class' => 'form-horizontal t-margin20','id'=>'form1','enctype'=>"multipart/form-data"],
                ],

                ]); ?>
                

                <div class="div-apply-password" style="display:none">
                <?= $form->field($model, 'password')->passwordInput(['maxlength' => 20,'class'=>'apply-password','id'=>'pw1'])->label('原始密码') ?>
                <?= $form->field($model, 're_password')->passwordInput(['maxlength' => 20,'class'=>'apply-confirmpassword','id'=>'pw2','placeholder'=>'请输入当前密码进行验证'])->label(Yii::t('yii','Confirmpassword')) ?>
                <!--<?= $form->field($model, 'password')->passwordInput(['maxlength' => 20,'class'=>'apply-password','id'=>'pw1','value'=>$now_user_info['password']])->label(Yii::t('yii','Password')) ?>
  -->
                
              </div>
                
<div class="user_prinfo">
                <?= $form->field($model, 'username')->textInput(['label' => 'username','class'=>'apply-username','value'=>$now_user_info['user_name']])->label(Yii::t('yii','Username')) ?>
                <!--具体填充内容 field中的name对应model中定义的属性名-->
                 <?= $form->field($model, 'power')->dropDownList(['1'=>Yii::t('yii','Administrators'),'5'=>Yii::t('yii','Salesman'),'15'=>Yii::t('yii','NormalUser')], 
                ['prompt'=>Yii::t('yii','Please select you options'),'class'=>'apply-power','value'=>$now_user_info['power'],'selected'=>'selected'])->label(Yii::t('yii','Power'))?>

                <div class="div_select_dev" style="width:66%;text-algin:center;margin-top:30px;margin-left:15%;">
                       <div class="" >
                            <span class="input-group-addon-1" ><?=Yii::t('yii','Device_name')?></span>
                             <?=Html::activeTextInput($model,'device_info_show',['class'=>'form-control device_info','readonly'=>"readonly",'style'=>'width:70%;float:left','value'=>$now_user_devinfo]);?>
                             <?= Html::Button('', ['class' => 'btn-show-dev btn btn-primary glyphicon glyphicon-plus','style'=>'width:7%','disabled'=>"disabled"]) ?>
                      </div>
                      <div class="device_list_show  div_show_dev" >
                        <?=Html::activeCheckboxList($model,'device', ArrayHelper::map($device,'dev_name', 'dev_name'),['class'=>'device_list_show', 'style'=>'display:none;']);?>
                        <div style="text-align:center;height:20px">
                          <?= Html::Button(Yii::t('yii','Confirm'), ['class' => 'btn btn-primary','style' => 'width:15%;margin-right:4%' ]) ?>
                          <?= Html::resetButton(Yii::t('yii','Cancel'), ['class' => 'btn btn-primary','style' =>'width:15%;']) ?>   
                        </div>
                      </div>
              </div>
                <?= $form->field($model, 'email')->textInput(['maxlength' => 20,'class'=>'apply-email','value'=>$now_user_info['email']])->label(Yii::t('yii','Email')) ?>

              
                 <?= $form->field($model, 'country')->dropDownList(ArrayHelper::map($country,'country_name', 'country_name'), ['prompt'=>Yii::t('yii','Please select you options'),
                'class'=>'apply-country','value'=>$now_user_info['country']])->label(Yii::t('yii','Country')) ?>

                <?= $form->field($model, 'phone')->textInput(['maxlength' => 20,'class'=>'apply-phone','value'=>$now_user_info['phone']])->label(Yii::t('yii','Contactnumber')) ?>

                <?= $form->field($model, 'remark')->textInput(['maxlength' => 20,'class'=>'apply-remark','value'=>$now_user_info['remark']])->label(Yii::t('yii','Remark')) ?> 
                <div style="display:none">
                <?= $form->field($model, 'flag')->textInput(['maxlength' => 20,'class'=>'apply-flag','style'=>'display:none'])?> 
                </div>
</div>
                <br>
                 <div style="text-align:center;">
                <?= Html::Button(Yii::t('yii','Apply'), ['class' => 'btn btn-primary btn-three-apply','style' => 'width:15%;margin-right:4%;display:none']) ?>  
                <?= Html::submitButton('hidden_apply', ['class' => 'btn-hidden','style' => 'display:none']) ?>
                <?= Html::Button(Yii::t('yii','Modify Information'), ['class' => 'btn btn-primary btn-modify-info','style' => 'width:15%;margin-right:4%']) ?>
                <?= Html::resetButton(Yii::t('yii','Cancel'), ['class' => 'btn btn-primary btn-three-cancel', 'style' =>'width:15%;display:none']) ?>
                <?= Html::Button(Yii::t('yii','Modify Password'), ['class' => 'btn btn-primary btn-modify-password','style' => 'width:15%']) ?>
                </div>
            <?php ActiveForm::end();?>
</div>

<script src="js/jquery-1.10.2.min.js"></script><!---先导入jQuery再导入bootstrap---->
<script>
//显示当前模块
  var show = document.getElementById('show');
      show.innerHTML="<?= Yii::t('yii','UserCenter');?>";
</script>
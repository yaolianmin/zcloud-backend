<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ContactForm */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use yii\widgets\LinkPager;
use yii\bootstrap\Modal;
use yii\helpers\Url;


//$this->title = Yii::t('yii','UserManagement');
//$this->registerCssFile('css/fw_usercenter.css');
$this->registerCssFile('css/fw_usermanagement.css');
$this->registerJsFile('js/jquery-1.10.2.min.js');
$this->registerJsFile("js/bootstrap.min.js");
$this->registerJsFile('js/fw_usermanagement.js');

$index=1;
?>
<div >
    <h1><?= Html::encode($this->title) ?></h1>
    <div >
        <div class="user_management_from">
        <?=Html::beginForm('/index.php?r=user-management/user-management','get',['id'=>'form','class'=>'form','data'=>'myself']);?>

            <!--输入用户名搜索-->   
            <br>       
            <div class="input-group  div_username">
            <span class="input-group-addon" style="width:20%;background-color:#007DB8;color: #fff;border:1px solid #000000;" ><?=Yii::t('yii','Username')?></span>
             <?=Html::activeTextInput($search,'username_search',['class'=>'form-control username_search','placeholder'=>Yii::t('yii','Please input user name you want to Search !')]);?>
              <span class="input-group-addon" style="width:5%"> <?= Html::submitButton('', ['id'=>'btn_search_1','class' => 'btn btn-primary glyphicon glyphicon-search '])?>
              </span>
            </div><br>  

           <!--权限选项-->
            <div class="input-group  div_power">
                <span class="input-group-addon" style="width:20%;background-color:#007DB8;color: #fff;border:1px solid #000000;" ><?=Yii::t('yii','Power')?></span>
                <?=Html::activeTextInput($search,'power_info',['class'=>'form-control','id'=>'input-power']);?>
                <span class="input-group-addon" style="width:5%"><?= Html::Button('', ['class' => 'btn-power btn btn-success glyphicon glyphicon-plus']) ?></span>
            </div>
            <span class="power_span" style="display:none"> <?php echo Yii::$app->session['power']; ?></span>
            <div class="power_list div_power_list">
               
            <label class="che_admin">
            <input type="checkbox" value=<?=Yii::t('yii','Administrators'); ?>  >
            <span><?=Yii::t('yii','Administrators');?></span>
          </label>
          <label  class="che_sale">
            <input type="checkbox" value=<?=Yii::t('yii','Salesman');?>> 
            <span><?=Yii::t('yii','Salesman');?></span>
          </label>
          <label>
            <input type="checkbox" value=<?=Yii::t('yii','NormalUser');?> >
            <span><?=Yii::t('yii','NormalUser');?></span>
          </label>

              <div style="text-align:center;height:20px">
                <?= Html::submitButton(Yii::t('yii','Confirm'), ['class' => 'btn btn-primary','style' => 'width:15%;margin-right:4%' ]) ?>
                <?= Html::resetButton(Yii::t('yii','Cancel'), ['class' => 'btn btn-primary', 'id' => 'contact-button','style' =>'width:15%;']) ?>   
              </div>
            </div><br>

           <!--机种选项 -->
            <div class="input-group div_device" >
                  <span class="input-group-addon" style="width:20%;background-color:#007DB8;color: #fff;border:1px solid #000000;"><?=Yii::t('yii','Device_name')?></span>
                   <?=Html::activeTextInput($search,'device_info',['class'=>'form-control','id'=>'input-device']);?>
                  <!--<input type="text" class="form-control" name="device" id="input-device" onmouseover="this.title=this.value" style="text-overflow:ellipsis;overflow:hidden;white-space: nowrap;">-->
                  <span class="input-group-addon" style="width:5%"><?= Html::Button('', ['class' => 'btn-device btn btn-success glyphicon glyphicon-plus']) ?></span>
            </div>
            <div class="device_list div_device_list">
              <?=Html::activeCheckboxList($search,'device', ArrayHelper::map($device,'dev_name', 'dev_name'),['class'=>'device_list', 'style'=>'display:none;']);?>
              <div style="text-align:center;height:20px">
                <?= Html::submitButton(Yii::t('yii','Confirm'), ['class' => 'btn btn-primary','style' => 'width:15%;margin-right:4%' ]) ?>
                <?= Html::resetButton(Yii::t('yii','Cancel'), ['class' => 'btn btn-primary', 'id' => 'contact-button','style' =>'width:15%;']) ?>   
              </div>
            </div><br>

            <!--项目选项-->
            <div class="input-group div_project" >
                  <span class="input-group-addon" style="width:20%;background-color:#007DB8;color: #fff;border:1px solid #000000;"><?=Yii::t('yii','Project_name')?></span>
                  <?=Html::activeTextInput($search,'project_info',['class'=>'form-control','id'=>'input-project']);?>
                  <!--<input type="text" class="form-control" id="input-project" style="text-overflow:ellipsis;overflow:hidden;">-->
                  <span class="input-group-addon" style="width:5%"><?= Html::Button('', ['class' => 'btn-project btn btn-success glyphicon glyphicon-plus']) ?></span>
            </div>
            <div class="project_list  div_project_list" >
              <?=Html::activeCheckboxList($search,'project', ArrayHelper::map($project_for_search,'project_name','project_name'),['class'=>'project_list', 'style'=>'display:none;']);?>
              <div style="text-align:center;height:20px" >
                <?= Html::submitButton(Yii::t('yii','Confirm'), ['class' => 'btn btn-primary','style' => 'width:15%;margin-right:4%' ]) ?>
                <?= Html::resetButton(Yii::t('yii','Cancel'), ['class' => 'btn btn-primary', 'id' => 'contact-button','style' =>'width:15%;']) ?>   
              </div>
            </div><br>
              
    </div>
</div>

<div div-table>
      <table   border="1" style="width:100%;text-align:center;border-color:#ccc" id="myTable"> 
                  <tr class="tr-user-one" >    
                          <td >Index</td>
                          <td ><?=Yii::t('yii','Username')?></td>
                          <td ><?=Yii::t('yii','Power')?></td>
                          <td ><?=Yii::t('yii','Created_time')?></td>
                          <td ><?=Yii::t('yii','Operation')?></td>
                  </tr>
                  <?php foreach ($username_final_searchinfo as $_user_info):?> 
                       <tr class="tr-user-two" name="tr-user-two333">
                        <td class="td-user td-index"><?=$index+$pagination->getpage()*15;?></td>
                        <td class="td-user td-name" ><?= $_user_info['user_name']?></td>
                        <td class="td-user td-power"><?php if($_user_info['power']==1){echo Yii::t('yii','Administrators');}else if($_user_info['power']==5){echo Yii::t('yii','Salesman');}else{echo Yii::t('yii','NormalUser');}?></td>
                        <td class="td-user td-dev-name"><?= $_user_info['created_time']?></td>
                        <td class="td-user td-id" style="display:none"><?= $_user_info['id']?></td>
                        <td class="td-user td-email" style="display:none"><?= $_user_info['email']?></td>
                        <td class="td-user td-country" style="display:none"><?= $_user_info['country']?></td>
                        <td class="td-user td-password" style="display:none"><?= $_user_info['password']?></td>
                        <td class="td-user td-phone" style="display:none"><?= $_user_info['phone']?></td>
                        <td class="td-user td-remark" style="display:none"><?= $_user_info['remark']?></td>
                        <td class="td-user td-devinfo" style="display:none"><?= $_user_info['0']?></td>
                        <td class="td-user td-radio" style="width:15%">
                        <?= Html::Button('', ['class' => 'glyphicon glyphicon-eye-open btn-find' ]) ?>
                        <?= Html::Button('', ['class' => 'glyphicon glyphicon-pencil btn-modify']) ?>
                        <?= Html::Button('', ['class' => 'glyphicon glyphicon-trash btn-delete ','style'=>'display:none', 'onclick' => 'javascript:return confirm_disp("1");']) ?>
                        </td>
                      </tr>
                  <?php  $index++;endforeach;?>
      </table>

      <div class="pagination_b" ><?=Yii::t('yii','No.')?><b><?=$pagination->getpage()*15+1;?>-<?php if(($pagination->getpage()*15+15 )>(Html::encode($page_num))){echo Html::encode($page_num);}else{echo $pagination->getpage()*15+15;}?></b> <?=Yii::t('yii','items')?> , <?=Yii::t('yii','Total Have')?><b> <?= Html::encode($page_num)?> <?=Yii::t('yii','item Records')?>.</div>
      <div class="pagination_a">
         <?= LinkPager::widget(['pagination' => $pagination,
                    //自定义分页样式以及显示内容
                    'prevPageLabel'=>Yii::t('yii','Previous Page'),
                    'nextPageLabel'=>Yii::t('yii','Next Page'),
                    'firstPageLabel' => Yii::t('yii','First Page'),
                    'lastPageLabel' => Yii::t('yii','Last Page'),
                    'options'=>['style'=>'','class'=>"pagination"],
             ]) ?>
      </div>
</div>
<?=Html::endForm();?>

<?= Html::Button(Yii::t('yii','Add_new'), ['class' => ' btn btn-primary btn-add','style'=>'margin-top:20px;display:none']) ?>


<hr style="background-color:#ccc;width:100%;border-top:1px solid #ccc;margin-top:10px; float:left"><br><br>


<div class="div-apply" id="div-apply-modify" style="margin-bottom:10%;display:none;font-weight:normal">
        <?php $form = ActiveForm::begin(['id' => 'abc-form',
                'fieldConfig' => [
                    'template' => 
              '<div class="input-group" style="width:100%;text-algin:center;margin-top:10px;margin-left:15%;">
                  <span class="input-group-addon" style="margin-left:10%;width:15%;color:#fff;background-color:#337ab7;border-color: #2e6da4">
                  {label}
                  </span>
                  <div class="from-input-css " style="width:60%;float:left">
                  {input}
                  </div>
                  <div class="from-error-css " style="width:30%;float:left;margin-left:5px">
                  {error}
                  </div>                 
              </div>',

                    'inputOptions' => ['class' => 'form-control aaa','style'=>'width:100%;height:35px;border-top:1px solid #ccc;
                                        border-bottom:1px solid #ccc;border-right:1px solid #ccc;border-left:1px solid #ccc;'],//这里配置input里面的样式
                    'options' => ['class' => 'form-horizontal t-margin20','id'=>'form1','enctype'=>"multipart/form-data"],
                ],

                ]); ?>

                               
                <?= $form->field($model, 'username')->textInput(['label' => 'username','class'=>'apply-username',])->label(Yii::t('yii','Username')) ?>
                <!--具体填充内容 field中的name对应model中定义的属性名-->
                <div class="div-apply-password" style="display:none">
                <?= $form->field($model, 'password')->passwordInput(['maxlength' => 20,'class'=>'apply-password','id'=>'pw1'])->label(Yii::t('yii','Password')) ?>
  
                <?= $form->field($model, 're_password')->passwordInput(['maxlength' => 20,'class'=>'apply-confirmpassword','id'=>'pw2'])->label(Yii::t('yii','Confirmpassword')) ?>
              </div>
               

                <?= $form->field($model, 'power')->dropDownList(['1'=>Yii::t('yii','Administrators'),'5'=>Yii::t('yii','Salesman'),'15'=>Yii::t('yii','NormalUser')], ['prompt'=>Yii::t('yii','Please select you options'),'class'=>'apply-power'])->label(Yii::t('yii','Power'))?>
                
                <div  style="width:66%;text-algin:center;margin-top:10px;margin-left:15%;">
                       <div class="" >
                            <span class="input-group-addon-1" ><?=Yii::t('yii','Device_name')?></span>
                             <?=Html::activeTextInput($model,'device_info_show',['class'=>'form-control device_info','style'=>'width:70%;float:left','readonly'=>"readonly"]);?>
                             <?= Html::Button('', ['class' => 'btn-show-dev btn btn-primary glyphicon glyphicon-plus','style'=>'width:7%']) ?>
                      </div>
                      <div class="device_list_show  div_show_dev" >
                        <?=Html::activeCheckboxList($model,'device', ArrayHelper::map($device,'dev_name', 'dev_name'),['class'=>'device_list_show', 'style'=>'display:none;','id'=>'device_value']);?>
                        <div style="text-align:center;height:20px">
                          <?= Html::submitButton(Yii::t('yii','Confirm'), ['class' => 'btn btn-primary','style' => 'width:15%;margin-right:4%' ]) ?>
                          <?= Html::resetButton(Yii::t('yii','Cancel'), ['class' => 'btn btn-primary','style' =>'width:15%;']) ?>   
                        </div>
                      </div>
              </div>
                 <?= $form->field($model, 'email')->textInput(['maxlength' => 20,'class'=>'apply-email'])->label(Yii::t('yii','Email')) ?>
            
                <?= $form->field($model, 'country')->dropDownList(ArrayHelper::map($country,'country_name', 'country_name'), ['prompt'=>Yii::t('yii','Please select you options'),
                'class'=>'apply-country'])->label(Yii::t('yii','Country')) ?>


                <?= $form->field($model, 'phone')->textInput(['maxlength' => 20,'class'=>'apply-phone'])->label(Yii::t('yii','Contactnumber')) ?>

                <?= $form->field($model, 'remark')->textInput(['maxlength' => 20,'class'=>'apply-remark'])->label(Yii::t('yii','Remark')) ?> 
                <div style="display:none">
                <?= $form->field($model, 'flag')->textInput(['maxlength' => 20,'class'=>'apply-flag','style'=>'display:none'])?> 
                </div>
                <br>
                 <div style="text-align:center;">
                <?= Html::Button(Yii::t('yii','Apply'), ['class' => 'btn btn-primary btn-four-apply','style' => 'width:8%;margin-right:4%']) ?>
                <?= Html::resetButton(Yii::t('yii','Cancel'), ['class' => 'btn btn-primary btn-four-cancel', 'style' =>'width:8%;']) ?> 
                <?= Html::Button(Yii::t('yii','Modify'), ['class' => 'btn btn-primary btn-four-modify','style' => 'width:8%;margin-right:4%']) ?>
                <?= Html::Button(Yii::t('yii','Delete'), ['class' => 'btn btn-primary btn-four-delete', 'style' =>'width:8%;','onclick' => 'javascript:return confirm_disp("1");']) ?> 
                 <?= Html::submitButton('hidden_apply', ['class' => 'btn btn-primary btn-hidden-apply', 'style' =>'width:8%;display:none']) ?> 
                </div>
            <?php ActiveForm::end();?>
  </div>

</div>

      <?= Html::Button('', ['class' => 'btn-modal-show','style'=>'display:none','data-toggle' => 'modal', 'data-target' => '#page-modal' ]) ?>


<!--********************************start：模态框主体******************************************************-->
<?php
Modal::begin([
    'id' => 'page-modal',
    'footer' => Html::Button(Yii::t('yii','Confirm'), ['class' => 'btn btn-primary btn-7777 ','style' => 'width:18%;','data-dismiss'=>'modal' ]),
    
]);
echo Yii::t('yii','The two password input is inconsistent ,please input again!');  
Modal::end();
?>
<!--********************************end：模态框******************************************************-->

<!--********************************start：弹出框-新增用户密码输入错误**************************************************-->
<div style="text-align:center;display:none" class="password_failed">
          <?=\Yii::t('yii','The two password input is inconsistent ,please input again!');?>
</div>
<!--********************************end：弹出框******************************************************-->



<script>
//显示当前模块
  var show = document.getElementById('show');
  show.innerHTML="<?= Yii::t('yii','User Management');?>";
</script>



<!-- Modal 3 confirm_delete-->
<div class="modal fade" id="confirm_delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document" style="width:20%;">
		<div class="modal-content">
			<div class="modal-header">
			</div>
			<div class="modal-body">
				<div style="text-align:center;">
				<?=\Yii::t('yii','Confirm delete this item?');?>
        
				</div>	
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary btn-ok" data-dismiss="modal" ><?=\Yii::t('yii','Confirm');?></button>
				<button type="button" class="btn btn-default btn-cancel" data-dismiss="modal" ><?=\Yii::t('yii','Cancel');?></button>
			</div>
		</div>
	</div>	
</div>		
<!-- Modal 3 END-->

<script src="js/jquery-1.10.2.min.js"></script><!---先导入jQuery再导入bootstrap---->
<script src="js/bootstrap.min.js"></script>
<script>
function confirm_disp(val)
{
	
	jQuery.noConflict();
 
	$("#confirm_delete").modal("show");//显示模态框

}

</script>


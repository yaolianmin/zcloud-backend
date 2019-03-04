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

$this->title = Yii::t('yii','ProjectManagement');
//$this->registerCssFile('css/fw_usercenter.css');
$this->registerCssFile('css/fw_projectmanagement.css');
$this->registerJsFile('js/jquery-1.10.2.min.js');
$this->registerJsFile('js/fw_projectmanagement.js');
$this->registerJsFile("js/bootstrap.min.js");

$index=1;
?>
<div >
    <div >
        <div class="project_management_from">
        <?=Html::beginForm('/index.php?r=project-management/project-management','get',['id'=>'form','class'=>'form','data'=>'myself']);?>

            <!--输入项目名搜索-->   
            <br>       
            <div class="input-group  div_project">
            <span class="input-group-addon" style="width:20%;background-color:#007DB8;color: #fff;border:1px solid #000000;" ><?=Yii::t('yii','Project_name')?></span>
             <?=Html::activeTextInput($search,'projectname_search',['class'=>'form-control username_search','placeholder'=>Yii::t('yii','Please input primary password!')]);?>
              <span class="input-group-addon" style="width:5%"> <?= Html::submitButton('', ['class' => 'btn btn-primary glyphicon glyphicon-search '])?>
              </span>
            </div><br>  

           <!--用户权限搜索选项-->
            <div class="input-group  div_power">
                <span class="input-group-addon" style="width:20%;background-color:#007DB8;color: #fff;border:1px solid #000000;" ><?=Yii::t('yii','Power')?></span>
                <?=Html::activeTextInput($search,'power_condition',['class'=>'form-control','id'=>'input-power']);?>
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
                <?= Html::resetButton(Yii::t('yii','Cancel'), ['class' => 'btn btn-primary', 'style' =>'width:15%;']) ?>   
              </div>
            </div><br>

           <!--机种名搜索选项 -->
            <div class="input-group div_device" >
                  <span class="input-group-addon" style="width:20%;background-color:#007DB8;color: #fff;border:1px solid #000000;"><?=Yii::t('yii','Device_name')?></span>
                   <?=Html::activeTextInput($search,'devname_condition',['class'=>'form-control','id'=>'input-device']);?>
                  <span class="input-group-addon" style="width:5%"><?= Html::Button('', ['class' => 'btn-device btn btn-success glyphicon glyphicon-plus']) ?></span>
            </div>
            <div class="device_list div_device_list">
              <?=Html::activeCheckboxList($search,'devname_select', ArrayHelper::map($devices_all,'dev_name', 'dev_name'),['class'=>'device_list', 'style'=>'display:none;']);?>
              <div style="text-align:center;height:20px">
                <?= Html::submitButton(Yii::t('yii','Confirm'), ['class' => 'btn btn-primary','style' => 'width:15%;margin-right:4%' ]) ?>
                <?= Html::resetButton(Yii::t('yii','Cancel'), ['class' => 'btn btn-primary', 'style' =>'width:15%;']) ?>   
              </div>
            </div><br>

            <!--用户名搜索选项-->
            <div class="input-group div_username" >
                  <span class="input-group-addon" style="width:20%;background-color:#007DB8;color: #fff;border:1px solid #000000;"><?=Yii::t('yii','Username')?></span>
                  <?=Html::activeTextInput($search,'username_condition',['class'=>'form-control','id'=>'input-username']);?>
                  <span class="input-group-addon" style="width:5%"><?= Html::Button('', ['class' => 'btn-username btn btn-success glyphicon glyphicon-plus']) ?></span>
            </div>
            <div class="username_list  div_username_list" >
              <?=Html::activeCheckboxList($search,'username_select', ArrayHelper::map($users_for_search,'user_name','user_name'),['class'=>'username_list', 'style'=>'display:none;']);?>
              <div style="text-align:center;height:20px">
                <?= Html::submitButton(Yii::t('yii','Confirm'), ['class' => 'btn btn-primary','style' => 'width:15%;margin-right:4%' ]) ?>
                <?= Html::resetButton(Yii::t('yii','Cancel'), ['class' => 'btn btn-primary', 'style' =>'width:15%;']) ?>   
              </div>
            </div><br>
              
    </div>
</div>

<div div-table>
      <table   border="1" style="width:100%;text-align:center;border-color:#ccc" id="myTable"> 
                  <tr class="tr-user-one" >    
                          <td >Index</td>
                          <td ><?=Yii::t('yii','Project_name')?></td>
                          <td ><?=Yii::t('yii','Username')?></td>
                          <td ><?=Yii::t('yii','Created_time')?></td>
                          <td ><?=Yii::t('yii','Operation')?></td>
                  </tr>
                  <?php foreach ($projectinfo_search as $_project_info):?> 
                       <tr class="tr-user-two" name="tr-user-two333">
                        <td class="td-user td-index"><?=$index+$pagination->getpage()*15;?></td>
                        <td class="td-user td-projectname" ><?= $_project_info['project_name']?></td>
                        <td class="td-user td-username" ><?= $_project_info['user_name']?></td>
                        <td class="td-user td-createdtime"><?= $_project_info['created_time']?></td>
                        <td class="td-user td-remark" style="display:none"><?= $_project_info['remark']?></td>

                        <td class="td-user td-id" style="display:none"><?= $_project_info['id']?></td>
                        <td class="td-user td-projectmanager" style="display:none"><?= $_project_info['project_manager']?></td>
                        <td class="td-user td-devinfo" style="display:none"><?= $_project_info['0']?></td>
                        <td class="td-user td-devinfo-userhave" style="display:none"><?= $_project_info['1']?></td>
                        <td class="td-user td-radio" style="width:15%">
                        <?= Html::Button('', ['class' => 'glyphicon glyphicon-eye-open btn-find']) ?>
                        <?= Html::Button('', ['class' => 'glyphicon glyphicon-pencil btn-modify']) ?>
                        <?= Html::Button('', ['class' => 'glyphicon glyphicon-trash btn-delete','onclick' => 'javascript:return confirm_disp("1");']) ?>
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

<?= Html::Button(Yii::t('yii','Add_new'), ['class' => ' btn btn-primary btn-add','style'=>'margin-top:20px']) ?>


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
                <div style="display:none">
                <?= $form->field($model, 'project_id')->hiddenInput(['class'=>'apply_projectname_id',]) ?>  
        </div>
                <?= $form->field($model, 'projectname_add')->textInput(['class'=>'apply_projectname_add'])->label(Yii::t('yii','Project_name')) ?>
                
        <?= $form->field($model, 'project_manager')->dropDownList(ArrayHelper::map($users_for_manager,'user_name', 'user_name'), ['prompt'=>Yii::t('yii','Please select you options'),
                'class'=>'apply_project_manager'])->label(Yii::t('yii','Project Manager')) ?>

                 <?= $form->field($model, 'project_owner')->dropDownList(ArrayHelper::map($users_for_owner,'user_name', 'user_name'), ['prompt'=>Yii::t('yii','Please select you options'),
                'class'=>'apply_project_owner'])->label(Yii::t('yii','Project Owner')) ?>

              <div  style="width:66%;text-algin:center;margin-top:10px;margin-left:15%;">
                       <div class="" >
                            <span class="input-group-addon-1" ><?=Yii::t('yii','Device_name')?></span>
                             <?=Html::activeTextInput($model,'device_info_show',['class'=>'form-control device_info','style'=>'width:70%;float:left','readonly'=>'readonly','placeholder'=>Yii::t('yii','Please click right button give device for project !')]);?>
                             <?= Html::Button('', ['class' => 'btn-show-dev btn btn-primary glyphicon glyphicon-plus','style'=>'width:7%']) ?>
                      </div>
                      <div class="device_list_show  div_show_dev" id="ary"style="display:none">
                        <?=Html::activeCheckboxList($model,'devname_select', ArrayHelper::map($devices_all,'dev_name', 'dev_name'),['class'=>'device_list_show fff', 'style'=>'display:none;']);?>
                        <div style="text-align:center;height:20px">
                          <?= Html::submitButton(Yii::t('yii','Confirm'), ['class' => 'btn btn-primary','style' => 'width:15%;margin-right:4%' ]) ?>
                          <?= Html::resetButton(Yii::t('yii','Cancel'), ['class' => 'btn btn-primary','style' =>'width:15%;']) ?>   
                        </div>
                      </div>
              </div>

                <?= $form->field($model, 'remark')->textInput(['maxlength' => 20,'class'=>'apply-remark'])->label(Yii::t('yii','Remark')) ?> 
                <div style="display:none">
                <?= $form->field($model, 'action_flag')->textInput(['maxlength' => 20,'class'=>'apply-flag','style'=>'display:none'])?> 
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


</div><!-- Modal 3 confirm_delete-->
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

//显示当前模块
  var show = document.getElementById('show');
      show.innerHTML="<?= Yii::t('yii','ProjectManagement');?>";
</script>
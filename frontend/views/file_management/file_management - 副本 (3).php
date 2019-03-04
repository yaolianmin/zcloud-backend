
<?php
use yii\widgets\LinkPager;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Modal;
use yii\bootstrap\Alert;

$this->registerJsFile('js/jquery-1.10.2.min.js');
$this->registerJsFile('js/file_management.js');
$this->registerJsFile('js/zDrag.js');
$this->registerJsFile('js/zDialog.js');

$this->registerCssFile('css/search.css');
$this->registerCssFile('css/font-awesome.min.css');

?>

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<title>file management</title>
	
	<script type="text/javascript">
		var single24 = "<?=\Yii::t('yii','Single Card 2.4G');?>";
		var single58 = "<?=\Yii::t('yii','Single Card 5.8G');?>";
		var double24 = "<?=\Yii::t('yii','Double Card 2.4G');?>";
		var double58 = "<?=\Yii::t('yii','Double Card 5.8G');?>";
		var double2458 = "<?=\Yii::t('yii','Double 2.4G&5.8G');?>";
		var interior = "<?=\Yii::t('yii','interior');?>";
		var outside = "<?=\Yii::t('yii','outside');?>";
		var inout = "<?=\Yii::t('yii','interior/outside');?>";
		var history_version = "<?=\Yii::t('yii','History Version');?>";
		var alert_add_error = "<?=\Yii::t('yii','Add filed,This version of the device already exists!');?>";
		var confirm_value = "<?=\Yii::t('yii','Confirm delete this item?');?>";
		var alert_dir_error = "<?=\Yii::t('yii','This version of the file not exists!');?>";
		
		var tip={   
			$:function(ele){   
				if(typeof(ele)=="object")   
					return ele;   
				else if(typeof(ele)=="string"||typeof(ele)=="number")   
					return document.getElementById(ele.toString());   
				return null;   
			},   
			mousePos:function(e){   
				var x,y;   
				var e = e||window.event;   
				return{ x:e.clientX+document.body.scrollLeft+document.documentElement.scrollLeft,   
						y:e.clientY+document.body.scrollTop+document.documentElement.scrollTop};   
				},   
			start:function(obj){   
				var self = this;   
				var t = self.$("mjs:tip");   
				obj.onmousemove=function(e){   
					var mouse = self.mousePos(e);     
					t.style.left = mouse.x + 10 + 'px';   
					t.style.top = mouse.y + 10 + 'px';   
					t.innerHTML = obj.getAttribute("tips");   
					t.style.display = '';   
				};   
				obj.onmouseout=function(){   
					t.style.display = 'none';   
				};   
			}   
		}
		
		function show_history_version(value)
		{
			var diag = new Dialog();
			diag.Width = 1000;
			diag.Height = 400;
			diag.Title = history_version;
			diag.URL = "/index.php?r=file_management/history_version";
			diag.firmware_info = value;
			diag.show();
		}
		
	</script>
	
</head>
<body>

<?=Html::beginForm('','get',['name'=>'file_management_search']);?>
	<div class="search d1">
		<div class="search_select">
		  <input id="search_firmware" name="search_firmware" value="" type="text" placeholder="<?=\Yii::t('yii','Please enter the name of the device name you want to query……');?>">
		  <?=Html::submitButton('',['class'=>'']);?>
		</div>
	</div>
	


		<div id="device_Firmware_List" class="file_content" style="display:;">
			<table border='1' style="border-color:#ccc;" cellpadding='0' cellspacing='0'>
				<tr>
					<td class="blue">&nbsp;</td>
					<td class="blue"><?=\Yii::t('yii','FirmwareName');?></td>
					<td class="blue"><?=\Yii::t('yii','DeviceName');?></td>
					<td class="blue"><?=\Yii::t('yii','version');?></td>
					<td class="blue"><?=\Yii::t('yii','Choice');?></td>
				</tr>
			
				<?php $i=1;?>
				<?php
				 foreach ($list as $val) {
				?>
				 <tr>
					<td><?php echo ($page->getPage()*10+$i);$i++;?></td>
					<td><?=$val['firmware_name'];?></td>
					<td><?=$val['device_name'];?></td>
					<td>
						<?php $value = implode("#",$val);?>
						<?php
							/*$options = ['onclick' => 'show_history_version("'.$val['device_name'].'")',
										'target' => '_blank',
										'onmouseover' => 'tip.start(this)',
										'tips' => \Yii::t('yii','Click to see the history version'),
							];
							*/
							$options = ['href' => '#',
										'id' => 'create',
										'data-toggle' => 'modal',
										'data-target' => '#create-modal',
										//'class' => 'btn btn-success',
							];
						?>
						<?= Html::beginTag('a', $options) ?>
							<?=$val['version'];?>
						<?= Html::endTag('a') ?>
					</td>
					<td>
						<a target="_blank" onmouseover="tip.start(this)" tips="<?=\Yii::t('yii','View')?>">
							<?= Html::Button('', ['class' => 'glyphicon glyphicon-eye-open btn-tool','onclick' => 'show_device_info("'.$value.'")']) ?>
						</a>
						<a target="_blank" onmouseover="tip.start(this)" tips="<?=\Yii::t('yii','Edit')?>">
							<?php
								$power = Yii::$app->session['power'];
								// 1.超级管理员 5.FAE&Sale 15.普通用户
								if($power != 1){
									$options = ['class' => 'glyphicon glyphicon-pencil btn-tool','style'=>'display:none','onclick' => 'edit_device_info("'.$value.'")'];
								}
								else
									$options = ['class' => 'glyphicon glyphicon-pencil btn-tool','onclick' => 'edit_device_info("'.$value.'")'];
							?>
							<?= Html::Button('', $options) ?>
						</a>
						
						<a target="_blank" onmouseover="tip.start(this)" tips="<?=\Yii::t('yii','Delete')?>">
							<?php
								$power = Yii::$app->session['power'];
								// 1.超级管理员 5.FAE&Sale 15.普通用户
								if($power != 1){
									$options = ['class' => 'glyphicon glyphicon-trash btn-tool','style'=>'display:none','name' => 'delete','value' => $value, 'onclick' => "javascript:return confirm(confirm_value);"];
								}
								else
									$options = ['class' => 'glyphicon glyphicon-trash btn-tool','name' => 'delete','value' => $value, 'onclick' => "javascript:return confirm(confirm_value);"];
							?>
							<?= Html::submitButton('', $options) ?>
						</a>
						<a target="_blank" onmouseover="tip.start(this)" tips="<?=\Yii::t('yii','Download')?>">
							<?= Html::submitButton('', ['class' => 'glyphicon glyphicon-download btn-tool','name' => 'uploadfile','value' => $value]) ?>
						</a>
						
					</td>
				</tr>
				<?php
				   }
				?>
			</table>
<?=Html::endForm();?>
			<div id="mjs:tip" class="tip" style="position:absolute;left:0;top:0;display:none;"></div>
			<div class="records">
				<span><?=\Yii::t('yii','Total Have');?>&nbsp;<?= $page->totalCount;?>&nbsp;<?=\Yii::t('yii','Records');?></span>
				<span><?=\Yii::t('yii','No.');?>&nbsp;<?= $page->getPage() + 1; ?>/<?= $page->getPageCount();?>&nbsp;<?=\Yii::t('yii','Page');?></span>
			</div>
			<div class="fenye_buttons">
				<?php
					$power = Yii::$app->session['power'];
					//var_dump($power);
					//exit;
					// 1.超级管理员 5.FAE&Sale 15.普通用户
					if($power != 1){
						$options = ['class' => ' btn btn-primary btn-add','style'=>'margin-top:10px;display:none','onclick' => 'add_device_info()'];
					}
					else
						$options = ['class' => ' btn btn-primary btn-add','style'=>'margin-top:10px;display:','onclick' => 'add_device_info()'];
				?>
				<?= Html::Button(Yii::t('yii','Add'), $options) ?>
				
				<?php
					if(!empty($check_value)){
						echo "<script LANGUAGE='javascript'>alert(alert_add_error);</script>";
					}
				?>
				<?= LinkPager::widget(['pagination' => $page, 'maxButtonCount' =>5,'prevPageLabel' => Yii::t('yii','Previous Page'),'nextPageLabel' => Yii::t('yii','Next Page'), 'firstPageLabel' => Yii::t('yii','First Page'), 'lastPageLabel' => Yii::t('yii','Last Page')]);?>
			</div>
		</div>

		<div id="firmware_info_show" style="margin-left:10%;margin-right:10%;margin-bottom:10%;display:none;">
			<?php $form = ActiveForm::begin([
				'method'=>'post',
				'options' => ['enctype' => 'multipart/form-data','target'=>'framFile']
				]) ?>
			<input id="flagstatus" name="flagstatus" type="text" value="" style="display:none"/>
			<div class="input-group" style="width:80%;margin-left:10%;margin-right:10%;">
				<span class="input-group-addon" style="width:20%;color:#fff;background-color:#337ab7;border-color: #2e6da4;"><?=\Yii::t('yii','FirmwareName');?></span>
				<input id="firmwareName_show" type="text" name="firmwareName_name" class="form-control" >
			</div>
			
			<div class="input-group" style="width:80%;margin-top:10px;margin-left:10%;margin-right:10%">
				<input id="UploadAttachment_show" type="text" class="form-control" >
				<span class="input-group-addon" style="width:20%;color:#fff;background-color:#337ab7;border-color: #2e6da4;"><?=\Yii::t('yii','Upload Attachment');?></span>
				<?= $form->field($filename, 'file', ['template' => '<div class="form-group" id="uploadfilebtn" style="opacity:0;position:absolute;top:5px;right:30px;"><center><label style="display:none;" class="col-md-2 control-label" for="type-name-field">{label}</label></center><a href="javascript:;" class="file">上传文件{input}</a></div><div class="error_info">{error}</div>'])->fileInput(["onchange"=>"handleFile()"]) ?>
				
			</div>
			
			<div class="input-group" style="width:80%;margin-top:10px;margin-left:10%;margin-right:10%;">
				<span class="input-group-addon" style="width:20%;color:#fff;background-color:#337ab7;border-color: #2e6da4;"><?=\Yii::t('yii','DeviceName');?></span>
				<?= $form->field($dev_name, 'dev_name')->dropDownList(ArrayHelper::map($dev_data,'dev_name', 'dev_name'),['prompt'=>Yii::t('yii','Please select you options'),'class'=>'select_dev_name'])->label(Yii::t('yii','Country'),['style' => 'display:none']);?>
			</div>
			
			<div class="input-group" style="width:80%;margin-top:10px;margin-left:10%;margin-right:10%">
				<span class="input-group-addon" style="width:20%;color:#fff;background-color:#337ab7;border-color: #2e6da4;"><?=\Yii::t('yii','version');?></span>
				<input id="version_show" type="text" name="version_name" class="form-control" >
			</div>
			
			<br>
			
			<div id="btn_submit" style="text-align:center;">
				<?= Html::submitButton(Yii::t('yii','Apply'), ['class' => 'btn btn-primary','style' => 'width:8%;margin-right:4%', 'onclick' => 'push_select_disable()']) ?>
				<?= Html::resetButton(Yii::t('yii','Cancel'), ['class' => 'btn btn-primary', 'id' => 'contact-button','style' =>'width:8%;']) ?>   
			</div>
			
			<?php ActiveForm::end() ?>
		</div>
		
	<script type="text/javascript">
		var type_error_info = "<?=\Yii::t('yii','You can only upload img file type!');?>";
		var myfile = document.getElementById('uploadform-file');
		function handleFile(){
            document.getElementById("UploadAttachment_show").value = myfile.files[0].name;
			var file_name = myfile.files[0].name;
			var arr = file_name.split('.');
			var type = arr[1];
			if(type != 'img')
				alert(type_error_info);
        }
	
    </script>
<?php
if(!empty(Yii::$app->session->getFlash('not_exist'))){
   echo "<script LANGUAGE='javascript'>alert(alert_dir_error);</script>";
}
?>
<?php
/*echo Html::a('创建', '#', [
    'id' => 'create',
    'data-toggle' => 'modal',
    'data-target' => '#create-modal',
    'class' => 'btn btn-success',
]);*/

Modal::begin([
    'id' => 'create-modal',
    'header' => '<h4 class="modal-title">'.Yii::t('yii','History Version').'</h4>',
    'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">Close</a>',
]); 

$requestUrl = Url::toRoute('file_management');
$js = <<<JS
    $(document).on('click', '#create', function () {
        $.get('{$requestUrl}', {},
            function (data) {
                $('.modal-body').html(data);
            }  
        );
    });
JS;
$this->registerJs($js);

Modal::end();

?>
</body>
</html>



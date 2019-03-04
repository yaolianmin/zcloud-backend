
<?php
use yii\widgets\LinkPager;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;

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
		  <input id="search_firmware" name="search_firmware" value="" type="text" placeholder="<?=\Yii::t('yii','Please enter the name of the Firmware you want to query……');?>">
		  <?=Html::submitButton('',['class'=>'']);?>
		</div>
	</div>
	 
	<!--<div class="condition_search d2">
		<div id="device_type">
			<input id="type_name" type="button" value="<?=\Yii::t('yii','Device Type')?>">
			<input id="select_device_type" name="select_device_type" type="text" value="">
			<button type="button" onclick="show_device_Type();"></button>
		</div>
		<div id="device_type_List" style="display:none;">
			<table class='condition' cellpadding='0' cellspacing='0'>
				<tr class='condition_list_1'>
					<td>
						<label><input type="checkbox" name="device_type_name" value="AP"></label>
						<span><?=\Yii::t('yii','AP');?></span>
					</td>
					<td>
						<label><input type="checkbox" name="device_type_name" value="AC"></label>
						<span><?=\Yii::t('yii','AC');?></span>
					</td>
					<td>
						<label><input type="checkbox" name="device_type_name" value="CPE"></label>
						<span><?=\Yii::t('yii','CPE');?></span>
					</td>
				</tr>
				
			</table>
		</div>
	</div>
	
	<div class="condition_search d3">
			<div id="device_type">
				<input id="type_name" type="button" value="<?=\Yii::t('yii','Card Type')?>">
				<input id="select_card_type" name="select_card_type" type="text" value="">
				<button type="button" onclick="show_card_Type();"></button>
			</div>
			<div id="card_type_List" style="display:none;">
				<table class='condition' cellpadding='0' cellspacing='0'>
					<?php
						$options = ['class' => 'condition_list_2_c'];
						$lang_class = \Yii::$app->session['language'];
						if($lang_class === 'en'){
							Html::removeCssClass($options, 'condition_list_2_c');
							Html::addCssClass($options, 'condition_list_2');
						}
					?>
					<?= Html::beginTag('tr', $options) ?>
						<td>
							<label><input type="checkbox" name="card_type_name" value='24' ></label>
							<span><?=\Yii::t('yii','Single Card 2.4G');?></span>
						</td>
						<td>
							<label><input type="checkbox" name="card_type_name" value='58'></label>
							<span><?=\Yii::t('yii','Single Card 5.8G');?></span>
						</td>
						<td>
							<label><input type="checkbox" name="card_type_name" value='double24'></label>
							<span><?=\Yii::t('yii','Double Card 2.4G');?></span>
						</td>
						<td>
							<label><input type="checkbox" name="card_type_name" value='double58'></label>
							<span><?=\Yii::t('yii','Double Card 5.8G');?></span>
						</td>
					<?= Html::endTag('tr') ?>
					<?php
						$options = ['class' => 'condition_list_2_1_c'];
						$lang_class = \Yii::$app->session['language'];
						if($lang_class === 'en'){
							Html::removeCssClass($options, 'condition_list_2_1_c');
							Html::addCssClass($options, 'condition_list_2_1');
						}
					?>
					<?= Html::beginTag('tr', $options) ?>
						<td>
							<label><input type="checkbox" name="card_type_name" value='double2458'></label>
							<span><?=\Yii::t('yii','Double 2.4G&5.8G');?></span>
						</td>
						<td></td>
						<td></td>
						<td></td>
					
					<?= Html::endTag('tr') ?>
				</table>
			</div>
		</div>
		
		<div class="condition_search d4">
			<div id="device_type">
				<input id="type_name" type="button" value="<?=\Yii::t('yii','Application Scenarios')?>">
				<input id="select_application_scenarios" name="select_application_scenarios" type="text" value="">
				<button type="button" onclick="show_application_scenarios();"></button>
			</div>
			<div id="application_scenarios_List" style="display:none;">
				<table class='condition' cellpadding='0' cellspacing='0'>
					<tr class='condition_list_3'>
						<td>
							<label><input type="checkbox" name="device_scenarios_name" value='interior'></label>
							<span><?=\Yii::t('yii','interior');?></span>
						</td>
						<td>
							<label><input type="checkbox" name="device_scenarios_name" value='outside'></label>
							<span><?=\Yii::t('yii','outside');?></span>
						</td>
						<td>
							<label><input type="checkbox" name="device_scenarios_name" value='inout'></label>
							<span><?=\Yii::t('yii','interior/outside');?></span>
						</td>
						
					</tr>
				</table>
			</div>
		</div>-->
	
<?=Html::endForm();?>

		<div id="device_Firmware_List" class="file_content" style="display:;">
			<table border='1' style="border-color:#ccc;" cellpadding='0' cellspacing='0'>
				<tr>
					<td class="blue">&nbsp;</td>
					<td class="blue"><?=\Yii::t('yii','FirmwareName');?></td>
					
					<!--<td class="blue"><?=\Yii::t('yii','Device Type');?></td>-->
					<!--<td class="blue"><?=\Yii::t('yii','Card Type');?></td>-->
					<!--<td class="blue"><?=\Yii::t('yii','interior/outside');?></td>-->
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
					<!--<td><?=$val['device_type'];?></td>-->
					<!--<td><?php
							if($val['card_type'] == "s24")
								echo Yii::t('yii','Single Card 2.4G');
							else if($val['card_type'] == "s58")
								echo Yii::t('yii','Single Card 5.8G');
							else if($val['card_type'] == "d24")
								echo Yii::t('yii','Double Card 2.4G');
							else if($val['card_type'] == "d58")
								echo Yii::t('yii','Double Card 5.8G');
							else if($val['card_type'] == "d2458")
								echo Yii::t('yii','Double 2.4G&5.8G');
						?>
					</td>-->
					<!--<td><?php
							if($val['application_scenarios'] == "in")
								echo Yii::t('yii','interior');
							else if($val['application_scenarios'] == "out")
								echo Yii::t('yii','outside');
							else if($val['application_scenarios'] == "inout")
								echo Yii::t('yii','interior/outside');
						?>
					</td>-->
					<td>
						<?php $value = implode("_",$val);?>
						<?php
							$options = ['onclick' => 'show_history_version("'.$value.'")',
										'target' => '_blank',
										'onmouseover' => 'tip.start(this)',
										'tips' => \Yii::t('yii','Click to see the history version'),
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
							<?= Html::Button('', ['class' => 'glyphicon glyphicon-pencil btn-tool','onclick' => 'edit_device_info("'.$value.'")']) ?>
						</a>
						<a target="_blank" onmouseover="tip.start(this)" tips="<?=\Yii::t('yii','Delete')?>">
							<?= Html::submitButton('', ['class' => 'glyphicon glyphicon-trash btn-tool']) ?>
						</a>
						<a target="_blank" onmouseover="tip.start(this)" tips="<?=\Yii::t('yii','Download')?>">
							<?= Html::Button('', ['class' => 'glyphicon glyphicon-download btn-tool']) ?>
						</a>
					</td>
				</tr>
				<?php
				   }
				?>
			</table>
			<div id="mjs:tip" class="tip" style="position:absolute;left:0;top:0;display:none;"></div>
			<div class="records">
				<span><?=\Yii::t('yii','Total Have');?>&nbsp;<?= $page->totalCount;?>&nbsp;<?=\Yii::t('yii','Records');?></span>
				<span><?=\Yii::t('yii','No.');?>&nbsp;<?= $page->getPage() + 1; ?>/<?= $page->getPageCount();?>&nbsp;<?=\Yii::t('yii','Page');?></span>
			</div>
			<div class="fenye_buttons">
				<?= Html::Button(Yii::t('yii','Add'), ['class' => ' btn btn-primary btn-add','style'=>'margin-top:10px']) ?>
				<?= LinkPager::widget(['pagination' => $page, 'maxButtonCount' =>5,'prevPageLabel' => Yii::t('yii','Previous Page'),'nextPageLabel' => Yii::t('yii','Next Page'), 'firstPageLabel' => Yii::t('yii','First Page'), 'lastPageLabel' => Yii::t('yii','Last Page')]);?>
			</div>
		</div>

		<div id="firmware_info_show" style="margin-left:10%;margin-right:10%;margin-bottom:10%;display:none;">
			<?php $form = ActiveForm::begin([
				'method'=>'post',
				//'id' => 'form-id',
				//'enableAjaxValidation' => true,
				'options' => ['enctype' => 'multipart/form-data','target'=>'framFile']
				]) ?>
			<div class="input-group" style="width:80%;margin-left:10%;margin-right:10%;">
				<span class="input-group-addon" style="width:20%;color:#fff;background-color:#337ab7;border-color: #2e6da4;"><?=\Yii::t('yii','FirmwareName');?></span>
				<input id="firmwareName_show" type="text" class="form-control" >
			</div>
			<div class="input-group" style="width:80%;margin-top:10px;margin-left:10%;margin-right:10%">
				<input id="UploadAttachment_show" type="text" class="form-control" >
				<span class="input-group-addon" style="width:20%;color:#fff;background-color:#337ab7;border-color: #2e6da4;"><?=\Yii::t('yii','Upload Attachment');?></span>
				<?= $form->field($filename, 'file', ['template' => '<div class="form-group" id="uploadfilebtn" style="opacity:0;position:absolute;top:5px;right:30px;"><center><label style="display:none;" class="col-md-2 control-label" for="type-name-field">{label}</label></center><a href="javascript:;" class="file">上传文件{input}</a></div><div class="error_info">{error}</div>'])->fileInput(["onchange"=>"handleFile()"]) ?>
				
			</div>
	
			<div class="input-group" style="width:80%;margin-top:10px;margin-left:10%;margin-right:10%">
				<span class="input-group-addon" style="width:20%;color:#fff;background-color:#337ab7;border-color: #2e6da4;"><?=\Yii::t('yii','Device Type');?></span>
				<input id="DeviceType_show" type="text" class="form-control" >
			</div>
			<div class="input-group" style="width:80%;margin-top:10px;margin-left:10%;margin-right:10%">
				<span class="input-group-addon" style="width:20%;color:#fff;background-color:#337ab7;border-color: #2e6da4;"><?=\Yii::t('yii','Card Type');?></span>
				<input id="CardType_show" type="text" class="form-control" >
			</div>
			<div class="input-group" style="width:80%;margin-top:10px;margin-left:10%;margin-right:10%">
				<span class="input-group-addon" style="width:20%;color:#fff;background-color:#337ab7;border-color: #2e6da4;"><?=\Yii::t('yii','Application Scenarios');?></span>
				<input id="ApplicationScenarios_show" type="text" class="form-control" >
			</div>
			<div class="input-group" style="width:80%;margin-top:10px;margin-left:10%;margin-right:10%">
				<span class="input-group-addon" style="width:20%;color:#fff;background-color:#337ab7;border-color: #2e6da4;"><?=\Yii::t('yii','version');?></span>
				<input id="version_show" type="text" class="form-control" >
			</div>
			
			<br>
			
			<div id="btn_submit" style="text-align:center;">
				<?= Html::submitButton(Yii::t('yii','Apply'), ['class' => 'btn btn-primary','style' => 'width:8%;margin-right:4%']) ?>
				<?= Html::resetButton(Yii::t('yii','Cancel'), ['class' => 'btn btn-primary', 'id' => 'contact-button','style' =>'width:8%;']) ?>   
			</div>
			<?php ActiveForm::end() ?>
		</div>

		 <!--<input type="button" id="b" value="#####" onclick="open2()"/>-->
		
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


</body>
</html>
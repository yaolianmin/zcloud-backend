
<?php
use yii\widgets\LinkPager;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Modal;
use yii\bootstrap\Alert;

$this->registerJsFile('js/jquery-1.10.2.min.js');
$this->registerJsFile('js/bootstrap.min.js');
$this->registerJsFile('js/file_management.js');
$this->registerJsFile('js/page.js');

$this->registerCssFile('css/search.css');
$this->registerCssFile('css/font-awesome.min.css');
//$this->registerCssFile('css/pagination.css');

?>

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<title>file management</title>
	<style>
    	ul,li{margin: 0;padding: 0;list-style: none;}
    	.pageMenu li::selection{background:transparent;}
    	.clearfix{zoom:1;}
		.clearfix:after{content:"";display: block;clear: both;}
		.pageBox{width:800px;background: #eee;border:1px solid #ebebeb;padding: 10px;margin: 0 auto;}
		.pageDiv{width: 98.75%;background: #fff;padding-left: 1.25%;margin-bottom: 10px;}
		.pageDiv li{margin-bottom: 10px;border:1px solid #dbdbdb;width: 21.5%;margin-right: 1.25%;float:left;margin-top: 10px;padding: 1%;text-align: center;}
		.hide{display: none;}
		.notContent{padding: 15px 0;text-align: center;}

		.page{text-align: center;width: 100%;margin: 0 auto;}
		.pageMenu{display: inline-block;position: relative;left: 20%;}
		
		.pageMenu li{border: solid thin #ddd;margin: 3px;float: left;padding: 5px 10px;cursor: pointer;background: #fff;}
		.pageMenu li.firstPage{}
		.pageMenu li.prevPage{}
		.pageMenu li.pageNum{}
		.pageMenu li.nextPage{}
		.pageMenu li.lastPage{}
		.pageMenu li.disabled{ background-color: #DDDDDD;   cursor: not-allowed;}
		.pageMenu li.active{ border: solid thin #0099FF;background-color: #0099FF;color: white;}
		.pageMenu li.last{background: transparent;border:0;position: relative;top: -4px;}
		.page .keuInput{padding: 0 5px;width: 30px;border: solid thin #ddd;height: 29px;outline: none;text-align: center;font-size: 16px;}
		.page .btnSure{padding: 4px 8px;border: solid thin #ddd;outline: none;text-align: center;font-size: 16px;background: #fff;position: relative;top: 2px;}
		.page .btnSure:hover{cursor: pointer;}
		.pageObj{float: left;}
	</style>
	
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
		var FirmwareName = "<?=\Yii::t('yii','FirmwareName');?>";
		var DeviceName = "<?=\Yii::t('yii','DeviceName');?>";
		var version = "<?=\Yii::t('yii','version');?>";
		var Choice = "<?=\Yii::t('yii','Choice');?>";
		
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
		
	</script>
<script src="js/jquery-1.10.2.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<!--<script>  
    $(document).ready(function(){
		jQuery.noConflict();
		$("#new").modal("show")
    })  
</script>-->
</head>
<body>

<?=Html::beginForm('','get',['name'=>'file_management_search','id'=>'file_management_search']);?>
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
				//var_dump($list);
				//exit;
				 foreach ($list as $val) {
				?>
				 <tr>
					<td><?php echo ($page->getPage()*10+$i);$i++;?></td>
					<td><?=$val['firmware_name'];?></td>
					<td><?=$val['device_name'];?></td>
					<td>
						<?php $value = implode("#",$val);?>
						<?php
							$power = Yii::$app->session['power'];
							$options = ['class' => 'btn-tool',
										'data-toggle' => 'modal',
										'data-target' => '#myModal',
										'onmouseover' => 'tip.start(this)',
										'tips' => \Yii::t('yii','Click to see the history version'),
										'data-whatever' => $value,
										'data-power' => $power,
							];
							
						?>
						<?= Html::Button($val['version'],$options) ?>

					</td>
					<td>
						<a target="_blank" onmouseover="tip.start(this)" tips="<?=\Yii::t('yii','View')?>">
							<?= Html::Button('', ['class' => 'glyphicon glyphicon-eye-open btn-tool','onclick' => 'show_device_info("'.$value.'")']) ?>
						</a>
						<a target="_blank" onmouseover="tip.start(this)" tips="<?=\Yii::t('yii','Edit')?>">
							<?php
								$power = Yii::$app->session['power'];
								// 1.超级管理员 5.FAE&Sale 15.普通用户
								if($power == 1){
									$options = ['class' => 'glyphicon glyphicon-pencil btn-tool','onclick' => 'edit_device_info("'.$value.'")'];
								}else
									$options = ['style' => 'display:none;']
								
							?>
							<?= Html::Button('', $options) ?>
						</a>
						
						<a target="_blank" onmouseover="tip.start(this)" tips="<?=\Yii::t('yii','Delete')?>">
							<?php
								$power = Yii::$app->session['power'];
								// 1.超级管理员 5.FAE&Sale 15.普通用户
								if($power == 1){
									$options = ['class' => 'glyphicon glyphicon-trash btn-tool','name' => 'delete','value' => $value, 'onclick' => 'javascript:return confirm_disp("'.$value.'");'];
								}else
									$options = ['style' => 'display:none;']
								
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
					if($power == 1){
						$options = ['class' => ' btn btn-primary btn-add','style'=>'margin-top:10px;display:','onclick' => 'add_device_info()'];
					}else
						$options = ['style' => 'display:none;']
				?>
				<?= Html::Button(Yii::t('yii','Add'), $options) ?>
				
				<?php
					if(!empty($check_value)){
						//echo "<script LANGUAGE='javascript'>alert(alert_add_error);</script>";
						echo "<script>  
								$(document).ready(function(){
									jQuery.noConflict();
									$('#alert_add_error').modal('show')
								})  
							  </script>";
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
			if(type != 'img'){
				jQuery.noConflict();
				$("#type_error_info").modal("show"); 
			}
        }
	
    </script>
<script src="js/jquery-1.10.2.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<?php
if(!empty(Yii::$app->session->getFlash('not_exist'))){
   //echo "<script LANGUAGE='javascript'>alert(alert_dir_error);</script
   echo "<script>  
			$(document).ready(function(){
				jQuery.noConflict();
				$('#not_exist').modal('show')
			})  
		</script>";
}
?>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="myrefresh();"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?=\Yii::t('yii','History Version');?></h4>
      </div>
      <div class="modal-body">

		<div id="device_Firmware_List" class="file_content" style="display:;">
			<?=Html::beginForm('','get',['name'=>'modal_1']);?>
			<table border='1' style="border-color:#ccc;" cellpadding='0' cellspacing='0' id='show_firmware_result'>
			</table>
			<?=Html::endForm();?>
			<div class="notContent hide">
				<?=\Yii::t('yii','There is no historical version!');?>
			</div>
			<div class="page">
				<ul class="pageMenu clearfix">
					<li class="firstPage"><?=\Yii::t('yii','First Page');?></li>
					<li class="prevPage"><?=\Yii::t('yii','Previous Page');?></li>
					<div class="pageObj clearfix">
						
					</div>
					<li class="nextPage"><?=\Yii::t('yii','Next Page');?></li>
					<li class="lastPage"><?=\Yii::t('yii','Last Page');?></li>
				</ul>
			</div>
			
<!------------------------------------------------------------------>				
			<div id="firmware_info_show_2" style="margin-left:10%;margin-right:10%;margin-bottom:10%;display:none;">
				<?php $form = ActiveForm::begin([
					'method'=>'post',
					'options' => ['enctype' => 'multipart/form-data','target'=>'framFile']
					]) ?>
				<input id="flagstatus_2" name="flagstatus_2" type="text" value="" style="display:none"/>
				<div class="input-group" style="width:80%;margin-left:10%;margin-right:10%;">
					<span class="input-group-addon" style="width:20%;color:#fff;background-color:#337ab7;border-color: #2e6da4;"><?=\Yii::t('yii','FirmwareName');?></span>
					<input id="firmwareName_show_2" type="text" name="firmwareName_name_2" class="form-control" >
				</div>
				
				<div class="input-group" style="width:80%;margin-top:10px;margin-left:10%;margin-right:10%">
					<input id="UploadAttachment_show_2" type="text" class="form-control" >
					<span class="input-group-addon" style="width:20%;color:#fff;background-color:#337ab7;border-color: #2e6da4;"><?=\Yii::t('yii','Upload Attachment');?></span>
					<?= $form->field($filename_2, 'file', ['template' => '<div class="form-group" id="uploadfilebtn_2" style="opacity:0;position:absolute;top:5px;right:30px;"><center><label style="display:none;" class="col-md-2 control-label" for="type-name-field">{label}</label></center><a href="javascript:;" class="file">上传文件{input}</a></div><div class="error_info">{error}</div>'])->fileInput(["id" => "uploadform-file_2","onchange"=>"handleFile_2()"]) ?>
					
				</div>
				
				<div class="input-group" style="width:80%;margin-top:10px;margin-left:10%;margin-right:10%;">
					<span class="input-group-addon" style="width:20%;color:#fff;background-color:#337ab7;border-color: #2e6da4;"><?=\Yii::t('yii','DeviceName');?></span>
					<?= $form->field($dev_name, 'dev_name')->dropDownList(ArrayHelper::map($dev_data,'dev_name', 'dev_name'),['prompt'=>Yii::t('yii','Please select you options'),'class'=>'select_dev_name','id' => 'file_dev_name','name' => 'File_to_dev_2[dev_name]'])->label(Yii::t('yii','Country'),['style' => 'display:none']);?>
				</div>
				
				<div class="input-group" style="width:80%;margin-top:10px;margin-left:10%;margin-right:10%">
					<span class="input-group-addon" style="width:20%;color:#fff;background-color:#337ab7;border-color: #2e6da4;"><?=\Yii::t('yii','version');?></span>
					<input id="version_show_2" type="text" name="version_name_2" class="form-control" >
				</div>
				
				<br>
				
				<div id="btn_submit_2" style="text-align:center;">
					<?= Html::submitButton(Yii::t('yii','Apply'), ['class' => 'btn btn-primary','style' => 'width:15%;margin-right:4%', 'onclick' => 'push_select_disable()']) ?>
					<?= Html::resetButton(Yii::t('yii','Cancel'), ['class' => 'btn btn-primary', 'id' => 'contact-button','style' =>'width:15%;']) ?>   
				</div>
				
				<?php ActiveForm::end() ?>
			</div>
<!------------------------------------------------------------------>
			
		</div>
		
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" onclick="myrefresh();"><?=\Yii::t('yii','close');?></button>
      </div>
    </div>
  </div>
<?php
if(!empty(Yii::$app->session->getFlash('not_exist_2'))){
   //echo "<script LANGUAGE='javascript'>alert(alert_dir_error);</script>";
   echo "<script>  
			$(document).ready(function(){
				jQuery.noConflict();
				$('#not_exist').modal('show')
			})  
		</script>";
}
?>
</div>

<script src="js/jquery-1.10.2.min.js"></script><!---先导入jQuery再导入bootstrap---->
<script src="js/bootstrap.min.js"></script>
<script src="js/page.js"></script>
<script src='js/file_management.js'></script>
<script>
 
    $('#myModal').on('show.bs.modal', function (event) {  
        var button = $(event.relatedTarget) // 触发事件的按钮  
        var recipient = button.data('whatever') // 解析出data-whatever内容
		var power = button.data('power');
        var modal = $(this)  
        //modal.find('.modal-title').text('Message To ' + recipient);
		
		$.ajax({
			type: 'get',
			async: false,
			url: "/index.php?r=file_management/file_management",
			data: {
					recipient:recipient
			},
			dataType: "json",
			error: function() {
				alert("search error!!!");
			},
			success: function (data) {
				//alert("result : "+data.history_version);
				//alert("success2 : "+JSON.stringify(data.history_version)+" length : "+data.history_version.length);
				//alert("success3 : "+JSON.stringify(data.page)+" length : "+data.page.length);
				//alert("success4 :"+JSON.stringify(data));
				
				show_firmware_result(data,power);
			}
		});
		
		
    })

	function show_firmware_result(data,power)
	{
		/**********************************************/
		jQuery.noConflict();//本地能运行，服务器上报错,在使用jQuery方法之前写一个jQuery.noConflict();在前面就行了,释放$符号,应该是jQuery的$符号和bootstrap的$冲突了
		$(".file_content").pageFuns({  /*在本地服务器上才能访问哦*/
			
			//interFace:"data/page.json",  /*接口*/
			interFace:data,
			displayCount:10,  /*每页显示总条数*/
			maxPage:2,/*每次最多加载多少页*/
			
			dataFun:function(data,index,displayCount){
				//alert("success2 : "+JSON.stringify(data.device_name));
				//alert("success4 :"+JSON.stringify(data));
				var val = index % displayCount;//取余数，追加表头
				var dataHtml = "";
				
				var eachvalue = data.id+"#"+data.firmware_name+"#"+data.device_name+"#"+data.version+"#"+data.dir;
				//alert(eachvalue);
				if(index == 0 || (val == 0)){
					dataHtml += "<tr>";
					dataHtml += "<td class='blue'>"+'&nbsp'+"</td>";
					dataHtml += "<td class='blue'>"+FirmwareName+"</td>";
					dataHtml += "<td class='blue'>"+DeviceName+"</td>";
					dataHtml += "<td class='blue'>"+version+"</td>";
					dataHtml += "<td class='blue'>"+Choice+"</td>";
					dataHtml += "</tr>";
				}
				dataHtml += "<tr>";
				dataHtml += "<td>"+(index+1)+"</td>";
				dataHtml += "<td>"+data.firmware_name+"</td>";
				dataHtml += "<td>"+data.device_name+"</td>";
				dataHtml += "<td>"+data.version+"</td>";
				dataHtml += "<td>";
				//alert("1");
				dataHtml += "<button type='button' class='glyphicon glyphicon-eye-open btn-tool' onclick='show_device_info_2("+JSON.stringify(eachvalue)+");'></button>";
				if(power == 1){
					dataHtml += "<button type='button' class='glyphicon glyphicon-pencil btn-tool' onclick='edit_device_info_2("+JSON.stringify(eachvalue)+");'></button>";
				}
				if(power == 1){
					dataHtml += "<button type='submit' class='glyphicon glyphicon-trash btn-tool' name='delete_2' value="+JSON.stringify(eachvalue)+" onclick='javascript:return confirm(confirm_value);'></button>";
				}
				dataHtml += "<button type='submit' class='glyphicon glyphicon-download btn-tool' name='uploadfile_2' value="+JSON.stringify(eachvalue)+"></button>";
				dataHtml += "</td>";

				dataHtml += "</tr>";
				return dataHtml;
			},
			pageFuns:function(i){
				var pageHtml = '<li class="pageNum">'+i+'</li>';
					return pageHtml;
			}
		})
		
	}
	
	var type_error_info = "<?=\Yii::t('yii','You can only upload img file type!');?>";
	var myfile_2 = document.getElementById('uploadform-file_2');
	function handleFile_2(){
		document.getElementById("UploadAttachment_show_2").value = myfile_2.files[0].name;
		var file_name = myfile_2.files[0].name;
		var arr = file_name.split('.');
		var type = arr[1];
		if(type != 'img'){
			jQuery.noConflict();
			$("#type_error_info").modal("show");
		}	
	}
	
</script> 
<!-- Modal END-->

<!-- Modal 2 not_exist-->
<div class="modal fade" id="not_exist">  
	<div class="modal-dialog">    
		  <div class="modal-content">    
			<div class="modal-header"></div>
			<div class="modal-body">
				<div style="text-align:center;">
				<?=\Yii::t('yii','This version of the file not exists!');?>
				</div>
			</div>
			<div class="modal-footer">    
			  <button type="button" class="btn btn-default" data-dismiss="modal"><?=\Yii::t('yii','close');?></button> 
			</div>    
		  </div>    
	</div>    
</div> 
<!-- Modal 2 END-->

<!-- Modal 2 alert_add_error-->
<div class="modal fade" id="alert_add_error">  
	<div class="modal-dialog">    
		  <div class="modal-content">    
			<div class="modal-header"></div>
			<div class="modal-body">
				<div style="text-align:center;">
				<?=\Yii::t('yii','Add filed,This version of the device already exists!');?>
				</div>
			</div>
			<div class="modal-footer">    
			  <button type="button" class="btn btn-default" data-dismiss="modal"><?=\Yii::t('yii','close');?></button> 
			</div>    
		  </div>    
	</div>    
</div> 
<!-- Modal 2 END-->

<!-- Modal 3 confirm_delete-->
<div class="modal fade" id="confirm_delete">  
	<div class="modal-dialog">    
		  <div class="modal-content">    
			<div class="modal-header"></div>
			<div class="modal-body">
				<div style="text-align:center;">
				<?=\Yii::t('yii','Confirm delete this item?');?>
				</div>
			</div>
			<div class="modal-footer">    
			  <button type="button" class="btn btn-sm btn-primary btn-ok">确定</button>
			  <button type="button" class="btn btn-sm btn-default btn-cancel">取消</button>
			</div>    
		  </div>    
	</div>    
</div> 
<!-- Modal 3 END-->

<!-- Modal 4 type_error_info-->
<div class="modal fade" id="type_error_info">  
	<div class="modal-dialog">    
		  <div class="modal-content">    
			<div class="modal-header"></div>
			<div class="modal-body">
				<div style="text-align:center;">
				<?=\Yii::t('yii','You can only upload img file type!');?>
				</div>
			</div>
			<div class="modal-footer">    
			  <button type="button" class="btn btn-default" data-dismiss="modal"><?=\Yii::t('yii','close');?></button> 
			</div>    
		  </div>    
	</div>    
</div> 
<!-- Modal 4 END-->
</body>
</html>



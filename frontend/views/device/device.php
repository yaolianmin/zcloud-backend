<?php
use yii\widgets\LinkPager;
use yii\helpers\Html;
$this->title='智威亚科技有限公司';
$this->registerCssFile('css/fw_device.css');
$this->registerJsFile('js/jquery-1.10.2.min.js');
$this->registerJsFile('js/bootstrap.min.js');
$this->registerJsFile('js/fw_devicejs.js');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title></title>
</head>
<body>
	<div >
	    <!-- 条件搜索模块 -->
		<div class="dec_nav">
		    <form action="" method="get">
		    <input type="hidden" name="r" value="device/device" />
			<div class="search">
				<ul>
					<li class="devicea"><?= Yii::t('yii','Device name')?></li>
					<li class='cont'><input type="text" name='dev_name' placeholder="<?= Yii::t('yii','Please input device name')?>" value ="<?php if(isset($get_infor['dev_name'])){echo $get_infor['dev_name'];}?>" autofocus /></li>
					<li class="search_"><?= Html::submitButton('', ['class' => 'btn btn-primary glyphicon glyphicon-search'])?></li>
				</ul>
			</div>
			<div class="dec_kind">
			    <ul >
			    	<li class="devicea"><?= Yii::t('yii','Device type')?></li>
			    	<li class="second1">
			    		<ul class="dec_kinds1">
				    		<li style="display:<?php if(isset($get_infor['dec_type'])){foreach ($get_infor['dec_type'] as $val){ if($val=='AP'){echo 'block';}}}?>">AP</li>
				    		<li style="display:<?php if(isset($get_infor['dec_type'])){foreach ($get_infor['dec_type'] as $val){ if($val=='AC'){echo 'block';}}}?>">AC</li>
				    		<li style="display:<?php if(isset($get_infor['dec_type'])){foreach ($get_infor['dec_type'] as $val){ if($val=='CPE'){echo 'block';}}}?>">CPE</li>
			    		</ul>
			    	</li>
			    	<li class="add1"><span class="btn btn-primary">+</span></li>
			    </ul>	
			</div>
			<div class="dec_select1">
				<ul>
				    <label>
				    	<input type="checkbox" name="dec_type[]" value="AP" <?php if(isset($get_infor['dec_type'])){foreach ($get_infor['dec_type'] as $val){if($val=='AP'){echo 'checked';}}}?>/>
				    	<span>AP</span>
				    </label>
				    <label>
				    	<input type="checkbox" name="dec_type[]" value="AC" <?php if(isset($get_infor['dec_type'])){foreach ($get_infor['dec_type'] as $val){if($val=='AC'){echo 'checked';}}}?>/>
				    	<span>AC</span>
				    </label>
				    <label>
				    	<input type="checkbox" name="dec_type[]" value="CPE" <?php if(isset($get_infor['dec_type'])){foreach ($get_infor['dec_type'] as $val){if($val=='CPE'){echo 'checked';}}}?>/>
				    	<span>CPE</span>
				    </label>
				</ul>
                <div class="button1" >
                	<input type="submit" class="btn btn-primary" value="<?= Yii::t('yii','Confirm')?>" />
                	<input type="button"  class='btn btn-primary' value="<?= Yii::t('yii','Cancel')?>" />
                </div>
			</div>
			<div class="dec_kind">
				<ul >
			    	<li class="devicea"><?= Yii::t('yii','Card type')?></li>
			    	<li class="second2">
			    		<ul class="dec_kinds2">
				    		<li style="display:<?php if(isset($get_infor['card_type'])){foreach ($get_infor['card_type'] as $val){ if($val=='Single Card 2.4G'){echo 'block';}}}?>"><?= Yii::t('yii','Single Card 2.4G')?></li>
				    		<li style="display:<?php if(isset($get_infor['card_type'])){foreach ($get_infor['card_type'] as $val){ if($val=='Single Card 5.8G'){echo 'block';}}}?>"><?= Yii::t('yii','Single Card 5.8G')?></li>
				    		<li style="display:<?php if(isset($get_infor['card_type'])){foreach ($get_infor['card_type'] as $val){ if($val=='Double Card 2.4G'){echo 'block';}}}?>"><?= Yii::t('yii','Double Card 2.4G')?></li>
				    		<li style="display:<?php if(isset($get_infor['card_type'])){foreach ($get_infor['card_type'] as $val){ if($val=='Double Card 5.8G'){echo 'block';}}}?>"><?= Yii::t('yii','Double Card 5.8G')?></li>
				    		<li style="display:<?php if(isset($get_infor['card_type'])){foreach ($get_infor['card_type'] as $val){ if($val=='Double 2.4G&5.8G'){echo 'block';}}}?>"><?= Yii::t('yii','Double 2.4G&5.8G')?></li>
			    		</ul>
			    	</li>
			    	<li class="add2"><span class="btn btn-primary">+</span></li>
			    </ul>	
			</div>
			<div class="dec_select2">
				<ul>
				    <label>
				    	<input type="checkbox" name="card_type[]" value="Single Card 2.4G" <?php if(isset($get_infor['card_type'])){foreach ($get_infor['card_type'] as $val){ if($val=='Single Card 2.4G'){echo 'checked';}}}?>/>
				    	<span><?= Yii::t('yii','Single Card 2.4G')?></span>
				    </label>
				    <label>
				    	<input type="checkbox" name="card_type[]" value="Single Card 5.8G" <?php if(isset($get_infor['card_type'])){foreach ($get_infor['card_type'] as $val){ if($val=='Single Card 5.8G'){echo 'checked';}}}?>/>
				    	<span><?= Yii::t('yii','Single Card 5.8G')?></span>
				    </label>
				    <label>
				    	<input type="checkbox" name="card_type[]" value="Double Card 2.4G" <?php if(isset($get_infor['card_type'])){foreach ($get_infor['card_type'] as $val){ if($val=='Double Card 2.4G'){echo 'checked';}}}?>/>
				    	<span><?= Yii::t('yii','Double Card 2.4G')?></span>
				    </label>
				    <label>
				    	<input type="checkbox" name="card_type[]" value="Double Card 5.8G" <?php if(isset($get_infor['card_type'])){foreach ($get_infor['card_type'] as $val){ if($val=='Double Card 5.8G'){echo 'checked';}}}?>/>
				    	<span><?= Yii::t('yii','Double Card 5.8G')?></span>
				    </label>
				    <label>
				    	<input type="checkbox" name="card_type[]" value="Double 2.4G&5.8G" <?php if(isset($get_infor['card_type'])){foreach ($get_infor['card_type'] as $val){ if($val=='Double 2.4G&5.8G'){echo 'checked';}}}?>/>
				    	<span><?= Yii::t('yii','Double 2.4G&5.8G')?></span>
				    </label>
				</ul>
                <div class="button1" >
                	<input type="submit" class="btn btn-primary" value="<?= Yii::t('yii','Confirm')?>" />
                	<input type="button"  class='btn btn-primary' value="<?= Yii::t('yii','Cancel')?>" />
                </div>
			</div>
			<div class="dec_kind">
			    <ul >
			    	<li class="devicea"><?= Yii::t('yii','Application scenarios')?></li>
			    	<li class="second3">
			    		<ul class="dec_kinds3">
				    		<li style="display:<?php if(isset($get_infor['scene'])){foreach ($get_infor['scene'] as $val){ if($val=='interior'){echo 'block';}}}?>"><?= Yii::t('yii','interior')?></li>
				    		<li style="display:<?php if(isset($get_infor['scene'])){foreach ($get_infor['scene'] as $val){ if($val=='outside'){echo 'block';}}}?>"><?= Yii::t('yii','outside')?></li>
				    		<li style="display:<?php if(isset($get_infor['scene'])){foreach ($get_infor['scene'] as $val){ if($val=='interior/outside'){echo 'block';}}}?>"><?= Yii::t('yii','interior/outside')?></li>
			    		</ul>
			    	</li>
			    	<li class="add3"><span class="btn btn-primary">+</span></li>
			    </ul>	
			</div>
			<div class="dec_select3">
				<ul>
				    <label>
				    	<input type="checkbox" name="scene[]" value="interior" <?php if(isset($get_infor['scene'])){foreach ($get_infor['scene'] as $val){ if($val=='interior'){echo 'checked';}}}?>/>
				    	<span><?= Yii::t('yii','interior')?></span>
				    </label>
				    <label>
				    	<input type="checkbox" name="scene[]" value="outside" <?php if(isset($get_infor['scene'])){foreach ($get_infor['scene'] as $val){ if($val=='outside'){echo 'checked';}}}?>/>
				    	<span><?= Yii::t('yii','outside')?></span>
				    </label>
				    <label>
				    	<input type="checkbox" name="scene[]" value="interior/outside" <?php if(isset($get_infor['scene'])){foreach ($get_infor['scene'] as $val){ if($val=='interior/outside'){echo 'checked';}}}?>/>
				    	<span><?= Yii::t('yii','interior/outside')?></span>
				    </label>
				</ul>
                <div class="button1" >
                	<input type="submit" class="btn btn-primary" value="<?= Yii::t('yii','Confirm')?>" />
                	<input type="button"  class='btn btn-primary' value="<?= Yii::t('yii','Cancel')?>" />
                </div>
			</div>
			</form>
		</div>
		<!-- 机种显示模块 -->
		<div class="content">
			<table border="1">
				<tr>
					<th><?=Yii::t('yii','Order');?></th>
					<th><?=Yii::t('yii','Device name');?></th>
					<th><?=Yii::t('yii','Device type');?></th>
					<th><?=Yii::t('yii','Card type');?></th>
					<th><?=Yii::t('yii','Application scenarios');?></th>
					<th style="width:12%" id="selec"><?=Yii::t('yii','Choice');?></th>
				</tr>
				<?php
				    $i=1;
                   foreach ($list as $val) {  	 
				?>
				<tr class="con">
					<td><?php echo ($pages->getPage()*10+$i);$i++;?></td>
					<td class="devname"><?= $val['dev_name'];?></td>
					<td class="dectype"><?= $val['dec_type'];?></td>
					<td class="card"><?= Yii::t('yii',$val['card_type']);?></td>
					<td class="scene"><?= Yii::t('yii',$val['scene']);?></td>
					<td style="overflow:hidden" class="selec"> 
                        <?= Html::Button('', ['class' => 'glyphicon glyphicon-pencil btn-modify ']) ?>
                        <?= Html::Button('', ['class' => 'glyphicon glyphicon-trash btn-delete']) ?>
                        <input type="hidden" value="<?= $val['id'];?>" name='delete' class='id'>
                    </td>
                    <td class="remarks" style="display:none"><?= $val['remarks']?></td>
				</tr>
				<?php  } ?>
			</table>
			<div class="total">
				<span><?= Yii::t('yii','Total Have');?><?=$pages->totalCount?><?= Yii::t('yii','Records');?></span>
				<span><?= Yii::t('yii','No.');?><?= $pages->getPage() + 1; ?>/<?= $pages->getPageCount();?><?= Yii::t('yii','Page');?></span>
			</div>
			<footer class='foots'>
				<input type="button" class="btn btn-primary" value="<?= Yii::t('yii','Add device');?>" id="add_dev">
				<?= LinkPager::widget(['pagination' => $pages, 'maxButtonCount' =>5,'prevPageLabel' => Yii::t('yii','Previous Page'),'nextPageLabel' => Yii::t('yii','Next Page'), 'firstPageLabel' => Yii::t('yii','First Page'), 'lastPageLabel' => Yii::t('yii','Last Page')]);?>
			</footer>
		</div>
		<!-- 添加机种模块 -->
		<div class="bottoms" style="display:<?php if($tips&&($tips!='success')){echo 'block';}else{echo 'none';}?>">
			<hr style="background-color:#ccc;width:100%;border-top:1px solid #ccc;margin-top:10px; float:left"> 
			<footer class="add_new">
				<form action="" method="post">
				<input type="hidden" id="_csrf" name="<?= \Yii::$app->request->csrfParam;?>" value="<?= \Yii::$app->request->csrfToken?>">
					<ul>
						<li>
							<span><?= Yii::t('yii','Device name');?></span>
							<input type="text" name="dev_name" placeholder="<?= Yii::t('yii','Please input device name');?>" required class="dev_name" value="<?php if(isset($post)){echo $post['dev_name'];}?>" style='outline:none'>
						</li>
						<li>
							<span><?= Yii::t('yii','Device type');?></span>
							<input type="text" name="dec_type" placeholder="<?= Yii::t('yii','Double click to select device type');?>" list ='dec_type' required class="dec_type" value="<?php if(isset($post)){echo $post['dec_type'];}?>" style='outline:none'>
							<datalist id="dec_type">
								<option value="AP" /></option>
								<option value="AC" /></option>
								<option value="CPE" /></option>
							</datalist>		
						</li>
						<li>
							<span><?= Yii::t('yii','Card type');?></span>
							<input type="text" name="card_type" placeholder="<?= Yii::t('yii','Double click to select card type');?>" list = 'card_type' required class="card_type" value="<?php if(isset($post)){echo $post['card_type'];}?>" style='outline:none'>
							<datalist  id='card_type' >
								<option value="<?= Yii::t('yii','Single Card 2.4G')?>"></option>
								<option value="<?= Yii::t('yii','Single Card 5.8G')?>"></option>
								<option value="<?= Yii::t('yii','Double Card 2.4G')?>"></option>
								<option value="<?= Yii::t('yii','Double Card 5.8G')?>"></option>
								<option value="<?= Yii::t('yii','Double 2.4G&5.8G')?>"></option>
							</datalist>
						</li>
						<li>
							<span><?= Yii::t('yii','Application scenarios');?></span>
							<p>
								<input type="radio" name="scene" value="interior" id="radio1" required <?php if(isset($post)&& $post['scene']=='interior'){echo 'checked';}?>>
								<span><?= Yii::t('yii','interior')?></span>
							</p>
							<p>
								<input type="radio" name="scene" value="outside" id="radio2" required <?php if(isset($post)&& $post['scene']=='outside'){echo 'checked';}?>>
								<span><?= Yii::t('yii','outside')?></span>
							</p>
							<p>
								<input type="radio" name="scene" value="interior/outside" id="radio3" required <?php if(isset($post)&& $post['scene']=='interior/outside'){echo 'checked';}?>>
								<span><?= Yii::t('yii','interior/outside')?></span>
							</p>
							<input type="hidden"  name="index" id="index" value="<?php if(isset($post)){echo $post['index'];}?>">
							
						</li>
						<li>
							<span><?= Yii::t('yii','Remarks');?></span>
							<input type="text" name="remarks" value="<?php if(isset($post)){echo $post['remarks'];}?>" style='outline:none' class='remark'>
						</li>
						<a class='tips'><?php if(isset($tips)&&($tips!='success')){echo $tips;}?></a>
						<li style="border:none;margin-top:10px;">
							<input type="submit" class="btn btn-primary sure" value="<?= Yii::t('yii','Confirm')?>">
							<input type="reset" class='btn btn-primary reset' value="<?= Yii::t('yii','Cancel')?>">
						</li>

					</ul>
				</form>
			</footer>	
		</div>
	</div>
<!--弹出层的内容-->
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
</body>
</html>
<script>
    var  add_dev = document.getElementById('add_dev');
    var  selec = document.getElementById('selec');
    var  sel   = document.getElementsByClassName('selec');
	var user_powers = "<?=\Yii::$app->session['power']?>";
    var len = sel.length;
	if(user_powers>2){ //隐藏相应的功能按钮
		add_dev.style.display='none';
		selec.style.display='none';
		for (var i =0; i<len; i++) {
			sel[i].style.display='none';
		};
	}

	//显示当前模块
	var show = document.getElementById('show');
	    show.innerHTML="<?= Yii::t('yii','Device Management');?>";

    //方便修改时应用场景不好切换语言
    var radio1  = document.getElementById('radio1');
    var radio2  = document.getElementById('radio2');
    var radio3  = document.getElementById('radio3');
    
    for (var i = 0; i<len; i++) {
    	sel[i].firstElementChild.onclick =function(){
	    	if(this.parentNode.previousElementSibling.innerHTML=="<?= Yii::t('yii','interior')?>"){ //判断应用场景的值
	    		
	            radio1.checked='true';
	    	}else if(this.parentNode.previousElementSibling.innerHTML=="<?= Yii::t('yii','outside')?>"){
	    		
	    		radio2.checked='true';

	    	}else if(this.parentNode.previousElementSibling.innerHTML=="<?= Yii::t('yii','interior/outside')?>"){
	    		
	    		radio3.checked='true';
	    	}
    	}
    }; 
</script>
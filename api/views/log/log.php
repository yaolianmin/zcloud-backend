
<?php

use yii\helpers\Url;//引入助手类
use yii\widgets\LinkPager;
use yii\bootstrap\ActiveForm;

$this->registerCssFile('css/fw_log.css');
$this->registerJsFile('js/jquery-1.10.2.min.js');
$this->registerJsFile('js/fw_logjs.js');
$this->title='智威亚科技有限公司';
?>

    <div class="wr">
		   <!-- 日志选择-->
		<div class="conditions">
			<div class="log_type">
				<span class="log_type_one"><?=Yii::t('yii','Log Type');?></span>
				<span class="log_type_two">
					<ul class="log_type_contents">
						<li style="display:<?php if(isset($post_infor['log_type'])){foreach($post_infor['log_type'] as $val){if($val == 'upgrade'){echo 'block';}}}else{echo 'none';}?>"><?=Yii::t('yii','upgrade');?></li>
						<li style="display:<?php if(isset($post_infor['log_type'])){foreach($post_infor['log_type'] as $val){if($val == 'system'){echo 'block';}}}else{echo 'none';}?>"><?=Yii::t('yii','system');?></li>
						<li style="display:<?php if(isset($post_infor['log_type'])){foreach($post_infor['log_type'] as $val){if($val == 'file'){echo 'block';}}}else{echo 'none';}?>"><?=Yii::t('yii','file');?></li>
						<li style="display:<?php if(isset($post_infor['log_type'])){foreach($post_infor['log_type'] as $val){if($val == 'project'){echo 'block';}}}else{echo 'none';}?>"><?=Yii::t('yii','project');?></li>
						<li style="display:<?php if(isset($post_infor['log_type'])){foreach($post_infor['log_type'] as $val){if($val == 'user'){echo 'block';}}}else{echo 'none';}?>"><?=Yii::t('yii','user');?></li>
						<li style="display:<?php if(isset($post_infor['log_type'])){foreach($post_infor['log_type'] as $val){if($val == 'machine'){echo 'block';}}}else{echo 'none';}?>"><?=Yii::t('yii','machine');?></li>
					</ul>
				</span>
				<span class="btn-success sun sun1">+</span>
				<form action="" method="get">
				  <input type="hidden" value="log/log" name="r" />
		    </div>
			<div class="log_type_content">
				<ul>
					<label>
						<input type="checkbox" value="machine" name="log_type[]" <?php if(isset($post_infor['log_type'])){foreach ($post_infor['log_type'] as $val) {if($val =='upgrade'){echo 'checked';}}}?>>
						<span><?=Yii::t('yii','machine');?></span>
					</label>
					<label>
						<input type="checkbox" value="user" name="log_type[]"  <?php if(isset($post_infor['log_type'])){foreach ($post_infor['log_type'] as $val) {if($val =='user'){echo 'checked';}}}?>> 
						<span><?=Yii::t('yii','user');?></span>
					</label>
					<label>
						<input type="checkbox" value="project" name="log_type[]" <?php if(isset($post_infor['log_type'])){foreach ($post_infor['log_type'] as $val) {if($val =='project'){echo 'checked';}}}?>>
						<span><?=Yii::t('yii','project');?></span>
					</label>
					<label>
						<input type="checkbox" value="file" name="log_type[]" <?php if(isset($post_infor['log_type'])){foreach ($post_infor['log_type'] as $val) {if($val =='file'){echo 'checked';}}}?>> 
						<span><?=Yii::t('yii','file');?></span>
					</label>
					<label>
						<input type="checkbox" value="system" name="log_type[]"  <?php if(isset($post_infor['log_type'])){foreach ($post_infor['log_type'] as $val) {if($val =='system'){echo 'checked';}}}?>>
						<span><?=Yii::t('yii','system');?></span>
					</label>
					<label>
						<input type="checkbox" value="upgrade" name="log_type[]" <?php if(isset($post_infor['log_type'])){foreach ($post_infor['log_type'] as $val) {if($val =='upgrade'){echo 'checked';}}}?>> 
						<span><?=Yii::t('yii','upgrade');?></span>
					</label>		
				</ul>
				<div class="reset">
					<input type="submit" value=<?=Yii::t('yii','Confirm');?> class="btn btn-primary sub" />
					<input type="reset" value=<?=Yii::t('yii','Cancel');?> class="btn btn-primary sub" />
				</div>
			</div>
			<div class="log_type number_two">
				<span class="log_type_one"><?=Yii::t('yii','Log Level');?></span>
				<span class="log_type_two1"> 
					<ul class="log_type_content2s">
						<li style="display:<?php if(isset($post_infor['log_level'])){foreach($post_infor['log_level'] as $val){if($val == 'reminder'){echo 'block';}}}else{echo 'none';}?>"><?=Yii::t('yii','reminder');?></li>
						<li style="display:<?php if(isset($post_infor['log_level'])){foreach($post_infor['log_level'] as $val){if($val == 'operation'){echo 'block';}}}else{echo 'none';}?>"><?=Yii::t('yii','operation');?></li>
						<li style="display:<?php if(isset($post_infor['log_level'])){foreach($post_infor['log_level'] as $val){if($val == 'warning'){echo 'block';}}}else{echo 'none';}?>"><?=Yii::t('yii','warning');?></li>
					</ul>
				</span>
				<span class="btn-success sun sun2">+</span>
		    </div>
			<div class="log_type_content2">
			    <ul>
				    <label>
						<input type="checkbox" value="warning" name="log_level[]" <?php if(isset($post_infor['log_level'])){foreach ($post_infor['log_level'] as  $val) {if($val =='warning'){echo 'checked';}}}?>>
						<span><?=Yii::t('yii','warning');?></span>
					</label>
					<label>
						<input type="checkbox" value="operation" name="log_level[]" <?php if(isset($post_infor['log_level'])){foreach ($post_infor['log_level'] as  $val) {if($val =='operation'){echo 'checked';}}}?>>
						<span><?=Yii::t('yii','operation');?></span>
					</label>
					<label>
						<input type="checkbox" value="reminder" name="log_level[]" <?php if(isset($post_infor['log_level'])){foreach ($post_infor['log_level'] as  $val) {if($val =='reminder'){echo 'checked';}}}?>>
						<span><?=Yii::t('yii','reminder');?></span>
					</label>
				</ul>
				<div class="reset">
					<input type="submit" value=<?=Yii::t('yii','Confirm');?> class="btn btn-primary sub" />
					<input type="reset" value=<?=Yii::t('yii','Cancel');?> class="btn btn-primary sub" />
				</div>
			</div>
        </div>
		<!--用户级别-->
		<div class="people_kind">
			<div id="select" >
			    <span><?=Yii::t('yii','Level');?>：</span>
		    	<select name="power" id="power">
		    		<option value="1" id="sup_first"><?=Yii::t('yii','Administrators');?></option>
		    		<option value="5" <?php if(isset($list[0]['log_power'])&& $list[0]['log_power'] =='5'){echo 'selected';}?>><?=Yii::t('yii','Salesman');?></option>
		    		<option value="15" <?php if(isset($list[0]['log_power'])&& $list[0]['log_power'] =='15'){echo 'selected';}?>><?=Yii::t('yii','User Member');?></option>
		    	</select>
		    </div>
   		   
		    <!--日志内容-->
		    <div class="log_content">
				<table border='1' style="border-color:#ccc;">
					<tr style="border-color:#ccc;">
						<td class="blue"><?=Yii::t('yii','Order');?></td>
						<td class="blue"><?=Yii::t('yii','Log Level');?></td>
						<td class="blue"><?=Yii::t('yii','Log Type');?></td>
						<td class="blue"><?=Yii::t('yii','Log Information');?></td>
						<td class="blue"><?=Yii::t('yii','Operating Time');?></td>
						<td class="blue"><?=Yii::t('yii','Log Source');?></td>
					</tr>
					<?php $i=1;?>
					<?php
                     foreach ($list as $val) {
					?>
					 <tr>
						<td><?php echo ($page->getPage()*10+$i);$i++;?></td>
						<td><?= Yii::t('yii',$val['log_level']);?></td>
						<td><?= Yii::t('yii',$val['log_type']);?></td>
						<td><?php echo $val['user_name'].' '.Yii::t('yii',$val['action_info']).' '.Yii::t('yii',$val['info']).' '.Yii::t('yii',$val['item_info']);?></td>
						<td><?=date('Y-m-d H:i:s',$val['log_time']);?></td>
						<td><?=$val['login_ip'];?></td>
					</tr>
					<?php }?>
				</table>
				<div class="records">
					<span><?= Yii::t('yii','Total Have');?><?= $page->totalCount;?><?= Yii::t('yii','Records');?></span>
					<span><?= Yii::t('yii','No.');?><?= $page->getPage() + 1; ?>/<?= $page->getPageCount();?><?= Yii::t('yii','Page');?></span>
				</div>
				<div class="buttons">
				    
				     <input type="submit" id='export' class="export btn-primary btn" name="export" value=<?= Yii::t('yii','Export Log'); ?>>
				    
				    </form>
				    <li style="display:<?php if(isset($info_export)&& $info_export ){echo 'block';}?>">
				         <img src="images/success.png" id="images">
				    	 <p id="tips"><?= Yii::t('yii','Export Success');?></p>
				    </li>
					 <?= LinkPager::widget(['pagination' => $page, 'maxButtonCount' =>5,'prevPageLabel' => Yii::t('yii','Previous Page'),'nextPageLabel' => Yii::t('yii','Next Page'), 'firstPageLabel' => Yii::t('yii','First Page'), 'lastPageLabel' => Yii::t('yii','Last Page')]);?>
				</div>
			</div> 
		</div>
		
       <hr style="background-color:#ccc;width:100%;border-top:1px solid #ccc;margin-top:10px; float:left"> 	
	    <!--日志设定-->
		<div class="set_option">
            <span class="option"><?= Yii::t('yii','Log Setting');?>：</span>
			<form action="" method="post">
			    <div class="width">
			    	<span class="log_"><?= Yii::t('yii','Log Level');?>：</span>
					<div class="log_ranks">
						<input type="checkbox" name="op_log_type" value='1' <?php if($log_sets->op_log_type){echo "checked";}?>/>
						<span><?= Yii::t('yii','operation');?></span>
					</div>
					<div class="log_ranks">
						<input type="checkbox" name="warning_log_type" value='1' <?php if($log_sets->warning_log_type){echo "checked";}?>/>
						<span><?= Yii::t('yii','warning');?></span>
					</div>
					<div class="log_ranks">
						<input type="checkbox" name="prompt_log_type" value='1' <?php if($log_sets->prompt_log_type){echo "checked";}?>/>
						<span><?= Yii::t('yii','reminder');?></span>
					</div>
			    </div>
				<div class="width2">
					<span class="log_"><?= Yii::t('yii','Log Type');?>：</span>
					<div class="log_ranks">
						<input type="checkbox" name="project_log" value='1' <?php if($log_sets->project_log){echo "checked";}?> />
						<span><?= Yii::t('yii','project');?></span>
					</div>
					<div class="log_ranks">
						<input type="checkbox" name="file_log" value='1' <?php if($log_sets->file_log){echo "checked";}?>/>
						<span><?= Yii::t('yii','file');?></span>
					</div>
					<div class="log_ranks">
						<input type="checkbox" name="device_log" value='1' <?php if($log_sets->device_log){echo "checked";}?>/>
						<span><?= Yii::t('yii','machine');?></span>
					</div>
					<div class="log_ranks" id="log_use">
						<input type="checkbox" name="user_log" value='1' <?php if($log_sets->user_log){echo "checked";}?>/>
						<span><?= Yii::t('yii','user');?></span>
					</div>
					<div class="log_ranks">
						<input type="checkbox" name="upgrade_log" value='1' <?php if($log_sets->upgrade_log){echo "checked";}?>/>
						<span><?= Yii::t('yii','upgrade');?></span>
					</div>
					<div class="log_ranks">
						<input type="checkbox" name="system_log" value='1' <?php if($log_sets->system_log){echo "checked";}?>/>
						<span><?= Yii::t('yii','system');?></span>
					</div>
				</div>
				<div class="width3">
					<span class="log_"><?= Yii::t('yii','Storage Time');?>：</span>
					<input type="text" value="<?= $log_sets->log_save_time;?>"  name="log_save_time"  pattern='^1-9$|^1[0-9]$|^2[0-9]$|^30$' placeholder=<?= Yii::t('yii','The number between 1-30');?> required/>
				</div>
	            <div class="width4">
	            	<input type="submit" value=<?= Yii::t('yii','Confirm');?> class="sure btn-primary btn" name="log_set_button" />
				    <input type="reset" value=<?= Yii::t('yii','Cancel');?> class="cancel btn-primary btn">
	            </div>
				<input type="hidden" id="_csrf" name="<?= \Yii::$app->request->csrfParam;?>" value="<?= \Yii::$app->request->csrfToken?>">
			</form>
		</div>
	</div>
	<!-- <div style="height:30px;width:100%;text-align:center;line-height:30px;margin-top:30px;color:#BFA6A6;font-size:14px;">Zcom Firmwore Server 版权所有 2017-2020</div> -->
<script type="text/javascript">   
    //判断用户级别，删除相应功能
	var user_powers = "<?=\Yii::$app->session['power']?>";
	var power = document.getElementById('power');
	var sup_first = document.getElementById('sup_first');
	if(user_powers>4){
        power.removeChild(sup_first);
	}
	//显示当前模块
	var show = document.getElementById('show');
	    show.innerHTML="<?= Yii::t('yii','Log Management');?>";

  
	       
</script>


<?php
use yii\widgets\LinkPager;
$this->registerJsFile('js/jquery-1.10.2.min.js');
$this->registerJsFile('js/file_management.js');

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
		var in_out = "<?=\Yii::t('yii','interior/outside');?>";
		
	</script>
</head>
<body>
	<div class="search d1">
		<div class="search_select">
		  <input id="search_firmware" value="" type="text" placeholder="<?=\Yii::t('yii','Please enter the name of the Firmware you want to query……');?>">
		  <button type="button" onclick="get_select_device_firmware()"></button>
		</div>
	</div>
	
	<div class="center">
		<span><strong><?=\Yii::t('yii','Condition Search : ');?></strong></span>
		<div class="condition_search d2">
			<div id="device_type">
				<input id="type_name" type="button" value="<?=\Yii::t('yii','Device Type')?>">
				<input id="select_device_type" type="text" value="">
				<button type="button" onclick="show_device_Type();"></button>
			</div>
			<div id="device_type_List" style="display:none;">
				<table class='condition' cellpadding='0' cellspacing='0'>
					<tr class='condition_list_1'>
						<td>
							<label><input type="checkbox" name="device_type_name" value="AP"></label>
							<span>&nbsp;<?=\Yii::t('yii','AP');?></span>
						</td>
						<td>
							<label><input type="checkbox" name="device_type_name" value="AC"></label>
							<span>&nbsp;<?=\Yii::t('yii','AC');?></span>
						</td>
						<td>
							<label><input type="checkbox" name="device_type_name" value="CPE"></label>
							<span>&nbsp;<?=\Yii::t('yii','CPE');?></span>
						</td>
					</tr>
					
				</table>
			</div>
			
		</div>
		
		<div class="condition_search d3">
			<div id="device_type">
				<input id="type_name" type="button" value="<?=\Yii::t('yii','Card Type')?>">
				<input id="select_card_type" type="text" value="">
				<button type="button" onclick="show_card_Type();"></button>
			</div>
			<div id="card_type_List" style="display:none;">
				<table class='condition' cellpadding='0' cellspacing='0'>
					<tr class='condition_list_2'>
						<td>
							<label><input type="checkbox" name="card_type_name" value='24' ></label>
							<span>&nbsp;<?=\Yii::t('yii','Single Card 2.4G');?></span>
						</td>
						<td>
							<label><input type="checkbox" name="card_type_name" value='58'></label>
							<span>&nbsp;<?=\Yii::t('yii','Single Card 5.8G');?></span>
						</td>
						<td>
							<label><input type="checkbox" name="card_type_name" value='double24'></label>
							<span>&nbsp;<?=\Yii::t('yii','Double Card 2.4G');?></span>
						</td>
						
					</tr>
					<tr class='condition_list_2'>
						<td>
							<label><input type="checkbox" name="card_type_name" value='double58'></label>
							<span>&nbsp;<?=\Yii::t('yii','Double Card 5.8G');?></span>
						</td>
						<td>
							<label><input type="checkbox" name="card_type_name" value='double2458'></label>
							<span>&nbsp;<?=\Yii::t('yii','Double 2.4G&5.8G');?></span>
						</td>
						<td>
						<!---不加这一列，IE浏览器就少一块----->
						</td>
					</tr>
				</table>
			</div>
		</div>
		
		<div class="condition_search d4">
			<div id="device_type">
				<input id="type_name" type="button" value="<?=\Yii::t('yii','Application Scenarios')?>">
				<input id="select_application_scenarios" type="text" value="">
				<button type="button" onclick="show_application_scenarios();"></button>
			</div>
			<div id="application_scenarios_List" style="display:none;">
				<table class='condition' cellpadding='0' cellspacing='0'>
					<tr class='condition_list_3'>
						<td>
							<label><input type="checkbox" name="device_scenarios_name" value='interior'></label>
							<span>&nbsp;<?=\Yii::t('yii','interior');?></span>
						</td>
						<td>
							<label><input type="checkbox" name="device_scenarios_name" value='outside'></label>
							<span>&nbsp;<?=\Yii::t('yii','outside');?></span>
						</td>
						<td>
							<label><input type="checkbox" name="device_scenarios_name" value='in_out'></label>
							<span>&nbsp;<?=\Yii::t('yii','interior/outside');?></span>
						</td>
						
					</tr>
				</table>
			</div>
		</div>
		
		<div id="device_Firmware_List" class="condition_search" style="display:none;">
			<table class='header' cellpadding='0' cellspacing='0'>
				<tr>
					<td width="5%" style='border-left: #000000 1px solid;border-top: #000000 1px solid;'>&nbsp;</td>
					<td width="15%" style='border-left: #000000 1px solid;border-top: #000000 1px solid;'><strong><?=\Yii::t('yii','FirmwareName');?></strong></td>
					<td width="14%" style='border-left: #000000 1px solid;border-top: #000000 1px solid;'><strong><?=\Yii::t('yii','Device Type');?></strong></td>
					<td width="28%" style='border-left: #000000 1px solid;border-top: #000000 1px solid;'><strong><?=\Yii::t('yii','Card Type');?></strong></td>
					<td width="20%" style='border-left: #000000 1px solid;border-top: #000000 1px solid;'><strong><?=\Yii::t('yii','interior/outside');?></strong></td>
					<td width="10%" style='border-left: #000000 1px solid;border-top: #000000 1px solid;'><strong><?=\Yii::t('yii','version');?></strong></td>
					<td width="8%" style='border-left: #000000 1px solid;border-top: #000000 1px solid;border-right: #000000 1px solid;'><strong><?=\Yii::t('yii','Choice');?></strong></td>
				</tr>
			</table>
			
			<table class='list_line' cellpadding='0' cellspacing='0' id='show_firmware_result'></table>
			
		</div>
		
		<div id="device_handle" class="condition_search" style="display:none;">
			<input class="uded" type="button" value="<?=\Yii::t('yii','Upload')?>">
			<input class="uded" type="button" value="<?=\Yii::t('yii','Download')?>">
			<input class="uded" type="button" value="<?=\Yii::t('yii','Edit')?>">
			<input class="uded" type="button" value="<?=\Yii::t('yii','Delete')?>">
			<hr style="height:1px;border:none;border-top:1px dashed #007DB8;" />
			
			<table class='list_line' cellpadding='0' cellspacing='0'>
				<tr>
					<td width="25%" class='info_header'><?=\Yii::t('yii','FirmwareName');?></td>
					<td width="75%" style='border: #000000 1px solid;'><input id="FirmwareName" type="text" value=""></td>
				</tr>
				<tr>
					<td width="25%" class='info_header'><?=\Yii::t('yii','Upload Attachment');?></td>
					<td width="75%" style='border: #000000 1px solid;'></td>
				</tr>
				<tr>
					<td width="25%" class='info_header'><?=\Yii::t('yii','Device Type');?></td>
					<td width="75%" style='border: #000000 1px solid;'><input id="Device_Type" type="text" value=""></td>
				</tr>
				<tr>
					<td width="25%" class='info_header'><?=\Yii::t('yii','Card Type');?></td>
					<td width="75%" style='border: #000000 1px solid;'><input id="Card_Type" type="text" value=""></td>
				</tr>
				<tr>
					<td width="25%" class='info_header'><?=\Yii::t('yii','Application Scenarios');?></td>
					<td width="75%" style='border: #000000 1px solid;'><input id="Application_Scenarios" type="text" value=""></td>
				</tr>
				<tr>
					<td width="25%" class='info_header'><?=\Yii::t('yii','Remarks');?></td>
					<td width="75%" style='border: #000000 1px solid;'><input id="Remarks" type="text" value=""></td>
				</tr>
			</table>
		</div>
		
		
		
	</div>
	
<script type="text/javascript">
var theUL = document.getElementById("show_firmware_result");
var totalPage = document.getElementById("spanTotalPage");
var pageNum = document.getElementById("spanPageNum"); //获取当前页<span>
var spanPre = document.getElementById("spanPre"); //获取上一页<span>
var spanNext = document.getElementById("spanNext"); //获取下一页<span>
var spanFirst = document.getElementById("spanFirst"); //获取第一页<span>
var spanLast = document.getElementById("spanLast"); //获取最后一页<span>
var numberRowsInTable = theUL.getElementsByTagName("tr").length; //记录总条数
var pageSize = 2; //每页显示的记录条数
var page = 1; //当前页，默认第一页


//下一页
function next(){
hideTable();
currentRow = pageSize * page;
maxRow = currentRow + pageSize;
if ( maxRow > numberRowsInTable ) maxRow = numberRowsInTable;
for ( var i = currentRow; i< maxRow; i++ ){
theUL.getElementsByTagName("tr")[i].style.display = '';
}
page++;
if ( maxRow == numberRowsInTable ) {
nextText();
lastText();
}
showPage();
preLink();
firstLink();
}


//上一页
function pre(){
hideTable();
page--;
currentRow = pageSize * page;
maxRow = currentRow - pageSize;
if ( currentRow > numberRowsInTable ) currentRow = numberRowsInTable;
for ( var i = maxRow; i< currentRow; i++ ){
theUL.getElementsByTagName("tr")[i].style.display = '';
}
if ( maxRow == 0 ){
preText();
firstText();
}
showPage();
nextLink();
lastLink();
}


//第一页
function first(){
hideTable();
page = 1;
for ( var i = 0; i<pageSize; i++ ){
theUL.getElementsByTagName("tr")[i].style.display = '';
}
showPage();
firstText();
preText();
nextLink();
lastLink();
}


//最后一页
function last(){
hideTable();
page = pageCount();
currentRow = pageSize * (page - 1);
for ( var i = currentRow; i<numberRowsInTable; i++ ){
theUL.getElementsByTagName("tr")[i].style.display = '';
}
showPage();
preLink();
nextText();
firstLink();
lastText();
}


function hideTable(){
for ( var i = 0; i<numberRowsInTable; i++ ){
theUL.getElementsByTagName("tr")[i].style.display = 'none';
}
}


function showPage(){
pageNum.innerHTML = page;
}


//总共页数
function pageCount(){
return Math.ceil(numberRowsInTable/pageSize);
}
//显示链接
function preLink(){
spanPre.innerHTML = "<a href='javascript:pre();'>上一页</a>";
}
function preText(){
spanPre.innerHTML = "上一页";
}
function nextLink(){
spanNext.innerHTML = "<a href='javascript:next();'>下一页</a>";
}
function nextText(){
spanNext.innerHTML = "下一页";
}
function firstLink(){
spanFirst.innerHTML = "<a href='javascript:first();'>首页</a>";
}
function firstText(){
spanFirst.innerHTML = "首页";
}
function lastLink(){
spanLast.innerHTML = "<a href='javascript:last();'>末页</a>";
}
function lastText(){
spanLast.innerHTML = "末页";
}


//隐藏
function hide(){
for ( var i = pageSize; i<numberRowsInTable; i++ ){
theUL.getElementsByTagName("tr")[i].style.display = 'none';
}
totalPage.innerHTML = pageCount();
pageNum.innerHTML = '1';
nextLink();
lastLink();
}
hide();
</script>	
	
	
</body>
</html>
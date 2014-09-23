<div class="search-left-title">
<p class="fleft">
<?php $currenturl = $_SERVER["REQUEST_URI"];
$currenturl2 = preg_replace("/(&page=\d+)/","",$currenturl);?>


<?php if(preg_match("/searchid/i",$currenturl2)) { ?>
<?php preg_match('/searchid=(\d*)/i',$currenturl2,$searchid);?>
<?php $currenturl2 = str_replace($searchid['0'],"searchid=$search_id",$currenturl2);?>
<?php } else { ?>
<?php isset($search_id) && $currenturl2 = $currenturl2."&searchid=$search_id"?>
<?php } ?>


<?php if(preg_match("/sortway/i",$currenturl2)) { ?>
<?php  preg_match('/sortway=(\d)/i',$currenturl2, $sort1);?>
<?php $nomalsorturl = str_replace($sort1['0'],"sortway=1",$currenturl2);
$newsorturl = str_replace($sort1['0'],"sortway=2",$currenturl2);
$honestsorturl = str_replace($sort1['0'],"sortway=3",$currenturl2);?>
<?php } else { ?>
<?php $nomalsorturl = $currenturl2."&sortway=1";
$newsorturl = $currenturl2."&sortway=2";
$honestsorturl = $currenturl2."&sortway=3";?>
<?php } ?>
<script type="text/javascript">
<!--
//note 默认排序
function changeurl1(){
	location.href="<?php echo $nomalsorturl;?>";
}
//note 按照最新注册排序
function changeurl2(){
	location.href="<?php echo $newsorturl;?>";
}
//note 按照诚信等级排序
function changeurl3(){
	location.href="<?php echo $honestsorturl;?>";
}
//-->
</script>
<strong>排序方式：</strong>
<input name="sort" type="radio" value="1" id="a1" <?php if($sortway=='1') { ?>checked<?php } ?> onclick="changeurl1()"/>
<label <?php if($sortway=='1') { ?>class="f-b-d73c90"<?php } ?> for="a1">默认排序</label>
<input name="sort" type="radio" value="2" id="a2" <?php if($sortway=='2') { ?>checked<?php } ?> onclick="changeurl2()"/>
<label <?php if($sortway=='2') { ?>class="f-b-d73c90"<?php } ?> for="a2">最新注册</label>
<input name="sort" type="radio" value="3" id="a3" <?php if($sortway=='3') { ?>checked<?php } ?> onclick="changeurl3()"/>
<label <?php if($sortway=='3') { ?>class="f-b-d73c90"<?php } ?> for="a3">诚信等级</label>
</p>
<span class="fright">
<strong>显示方式：</strong>
<?php if(preg_match("/condition/i",$currenturl2)) { ?>
<?php $photourl = str_replace("condition=2","condition=3",$currenturl2);
$dataurl = str_replace("condition=3","condition=2",$currenturl2);?>
<?php } else { ?>
<?php $photourl = $currenturl2."&condition=3";
$dataurl = $currenturl2."&condition=2";?>
<?php } ?>
<a href="<?php echo $dataurl;?>" <?php if($condition=='2') { ?> class="go-page-on" <?php } else { ?> class="go-page"<?php } ?>>列表</a>
<a href="<?php echo $photourl;?>" <?php if($condition=='3') { ?> class="go-photo-on" <?php } else { ?> class="go-photo"<?php } ?>>相册</a>
</span>
<div class="clear"></div>
</div>
<script>
function gotoPage() {
pagevalue = document.getElementById("pageGo").value;
if(pagevalue == ''){
pagevalue = document.getElementById("pageGo1").value;
}
pagevalue = parseInt(pagevalue);

if(isNaN(pagevalue)) {
pagevalue = 0;
}

if(pagevalue<1) {
pagevalue = 1;
}
/*	if(pagevalue > <?php echo ceil($total/$pagesize);?>) {
pagevalue = <?php echo ceil($total/$pagesize);?>;
}
*/
window.location.href = "<?php echo $currenturl2;?>&page="+pagevalue;
}
//按回车键就可改变每页显示的数目
function enterHandler(event){
var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;
if (keyCode == 13) {
gotoPage();	//调用函数
}
}
</script>

<script>
function tgopage(p){
	var url = './allmember_ajax.php?n=test&uid=<?php echo $uid;?>&tcid=<?php echo $tc_id;?>&page='+p+'&ispage=1';
	$.get(url,function(data){
		$("#test_showarea").html(data);
	});	
}
function test_tcid(tcid){
	var url = './allmember_ajax.php?n=test&uid=<?php echo $uid;?>&tcid='+tcid;
	$.get(url,function(data){
		$("#item7").html(data);
	});	
}
</script>
<?php if(!isset($_GET['ispage'])) { ?>
<div id="test_showarea">
<?php } ?>
<div style="padding:0px; margin:0px;">
<br/>
<a href="javascript:test_tcid(1);"<?php if($tc_id==1) { ?> style="font-weight:700"<?php } ?>>红娘爱情测试</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
<a href="javascript:test_tcid(11);"<?php if($tc_id==11) { ?> style="font-weight:700"<?php } ?>>红娘趣味测试</a>
<br />
</div>
<div style="border: 1px solid #1C3F80; margin:0 auto; width:100%;">
<table width="100%" cellpadding="3" cellspacing="1" style="background:#FFF">
	<tr>
		<th>题目ID</th>
		<th>标题</th>
		<th>选项</th>
		</tr>
	<?php foreach((array)$test_rs as $v) {?>
	<tr>
		<td align="center"><?php echo $v['qid'];?></td>
		<td><?php echo $v['question'];?></td>
		<td><?php echo $v['show_type'] <= 1 ? $v['opt_txt'] : $opt_arr[$v['show_type']][$v['opt']]?></td>
		</tr>
	<?php } ?>
</table>
</div>
<br/>
<?php if($pages>=2) { ?><div style="text-align:center">第&nbsp;<?php for($i=1;$i<=$pages;$i++){?>
	<a href="javascript:tgopage(<?php echo $i;?>);"style="text-decoration:none;<?php if($page==$i) { ?>border:1px solid #3E679A; background:#7F99BE;<?php } ?>">&nbsp;<?php echo $i;?>&nbsp;</a>&nbsp;
<?php } ?>&nbsp;页</div><?php } ?>

<?php if(!isset($_GET['ispage'])) { ?>
</div>
<br />
<div id="test_result">
<table width="100%" cellpadding="3" cellspacing="1" style="background:#FFF">
	<tr>
		<th>测试分类</th>
		<th>评价</th>
		<th>类型</th>
		</tr>
	<?php foreach((array)$results as $k=>$v) {?>
	<tr>
		<td align="center"><?php if(isset($hntest_cache['children'][$k])) { ?><?php echo $hntest_cache['children'][$k]?><?php } ?></td>
		<td><?php echo $v['result'];?></td>
		<td width="80"><?php echo $v['ctypename'];?></td>
	</tr>
	<?php }?>
</table>
</div>
<?php } ?>
<script type="text/javascript">
function letter(a){
	var url = './allmember_ajax.php?n=message&uid=<?php echo $uid;?>&t='+a;
	$.get(url,function(data){
		$("#letter_showarea").html(data);
	});
}

function letgopage(p){
	var url = './allmember_ajax.php?n=message&uid=<?php echo $uid;?>&t=<?php echo $t;?>&page='+p;
	$.get(url,function(data){
		$("#letter_showarea").html(data);
	});	
}
function regDrag(id){
	$("#dragable_"+id).css("display","block");
	var myDrag=new Endrag("movetop_"+id,"dragable_"+id,0,0); 	
}
function closeDrag(id){
	$("#dragable_"+id).css("display","none");
}
</script>
<script type="text/javascript" src="templates/js/onmousemove_minutes.js"></script>
<div id="letter_showarea">
<div style="padding:0px; margin:0px;">
<br/>
<?php if($t==1) { ?><img src="templates/images/down.gif" /><b><?php } ?><a href="javascript:letter(1);">客服发送短信</a> (<?php echo $total;?>)</b>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
<?php if($t==2) { ?><img src="templates/images/down.gif" /><b><?php } ?><a href="javascript:letter(2);">系统发送短信</a> (<?php echo $stotal;?>)</b>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
<?php if($t==3) { ?><img src="templates/images/down.gif" /><b><?php } ?><a href="javascript:letter(3);">会员上行短信</a>(<?php echo $mtotal;?>)</b>
</div>
<?php if($t==1) { ?>
<div style="border: 1px solid #1C3F80; margin:0 auto; width:100%;">
	<table width="100%" cellpadding="3" cellspacing="1" style="background:#FFF">
		<tr>
			<th>短信内容</th>
			<th>发送时间</th>
			<th>发送客服</th>
			</tr>
		<?php foreach((array)$messages as $v) {?>
		<tr>
			<td align="center"><?php echo $v['content'];?></td>
			<td align="center"><?php echo date("Y-m-d H:i:s",$v['sendtime']);?></td>
			<td align="center"><?php echo $v['name'];?></td>
			</tr>
		<?php } ?>
	</table>
</div><br/>
	<?php if($pages>=2) { ?><div style="text-align:center">第&nbsp;<?php for($i=1;$i<=$pages;$i++){?>
	<a href="javascript:letgopage(<?php echo $i;?>);"style="text-decoration:none;<?php if($page==$i) { ?>border:1px solid #3E679A; background:#7F99BE;<?php } ?>">&nbsp;<?php echo $i;?>&nbsp;</a>&nbsp;
<?php } ?>&nbsp;页</div><?php } ?>

<?php } elseif ($t==2) { ?>
<div style="border: 1px solid #1C3F80; margin:0 auto; width:100%;">
	<table width="100%" cellpadding="3" cellspacing="1" style="background:#FFF">
	<tr>
			<th>触发者id</th>
			<th>短信内容</th>
			<th>发送时间</th>
			<th>发送原因</th>
			
			</tr>
		<?php foreach((array)$messages as $v) {?>
		<tr>
			<td align="center"><?php echo $v['sid'];?></td>
			<td align="center"><?php echo $v['content'];?></td>
			<td align="center"><?php echo date("Y-m-d H:i",$v['sendtime']);?></td>
			<td align="center"><?php echo $v['type'];?></td>
			
			</tr>
		
		<?php } ?>
	</table>
</div><br/>
	<?php if($pages>=2) { ?><div style="text-align:center">第&nbsp;
	<?php for($i=1;$i<=$pages;$i++){?>
	<a href="javascript:letgopage(<?php echo $i;?>);"style="text-decoration:none;<?php if($page==$i) { ?>border:1px solid #3E679A; background:#7F99BE;<?php } ?>">&nbsp;<?php echo $i;?>&nbsp;</a>&nbsp;
	<?php } ?>&nbsp;页</div><?php } ?>
<?php } else { ?>
	<div style="border: 1px solid #1C3F80; margin:0 auto; width:100%;">
	<table width="100%" cellpadding="3" cellspacing="1" style="background:#FFF">
	<tr>
			<th>回复客服</th>
			<th>短信内容</th>
			<th>发送时间</th>			
			</tr>
		<?php foreach((array)$messages as $v) {?>
		<tr>
			<td align="center"><?php echo $GLOBALS['kefu_arr'][$v['sid']]?></td>
			<td align="center"><?php echo $v['rep'];?></td>
			<td align="center"><?php echo date("Y-m-d H:i",$v['reptime']);?></td>			
			</tr>		
		<?php } ?>
	</table>
</div><br/>
	<?php if($pages>=2) { ?><div style="text-align:center">第&nbsp;
	<?php for($i=1;$i<=$pages;$i++){?>
	<a href="javascript:letgopage(<?php echo $i;?>);"style="text-decoration:none;<?php if($page==$i) { ?>border:1px solid #3E679A; background:#7F99BE;<?php } ?>">&nbsp;<?php echo $i;?>&nbsp;</a>&nbsp;
	<?php } ?>&nbsp;页</div><?php } ?>
<?php } ?>
</div>
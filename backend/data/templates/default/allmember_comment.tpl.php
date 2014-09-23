<script type="text/javascript">
function pingjia(a){
	var url = './allmember_ajax.php?n=pingjia&uid=<?php echo $uid;?>&t='+a;
	$.get(url,function(data){
		$("#pingjia_showarea").html(data);
	});
}
function lgopage(p){
	var url = './allmember_ajax.php?n=pingjia&uid=<?php echo $uid;?>&t=<?php echo $t;?>&page='+p;
	$.get(url,function(data){
		$("#pingjia_showarea").html(data);
	});	
}
</script>
<script type="text/javascript" src="templates/js/onmousemove_minutes.js"></script>
<div id="pingjia_showarea">
<div style="padding:0px; margin:0px;">
<br/>
<?php if($t==1) { ?><img src="templates/images/down.gif" /><b><?php } ?><a href="javascript:pingjia(1);">别人对我的评价</a> (<?php echo $cocount1;?>)</b>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
<?php if($t==2) { ?><img src="templates/images/down.gif" /><b><?php } ?><a href="javascript:pingjia(2);">我对别人的评价</a> (<?php echo $cocount2;?>)</b>
</div>
<div style="border: 1px solid #1C3F80; margin:0 auto; width:100%;">
	<table width="100%" cellpadding="3" cellspacing="1" style="background:#FFF">
		<tr>
			<th>评价会员ID</th>
			<th>评价内容</th>
			<th>发方是否删除</th>
			<th>收放是否删除</th>
			<th>评价时间</th>
		</tr>
		<?php foreach((array)$pingjia_list as $v) {?>
		<tr class="tr_<?php if(isset($v['stat'])) { ?><?php echo $v['stat'];?><?php } ?>" id="leer_tr_<?php if(isset($v['lid'])) { ?><?php echo $v['lid'];?><?php } ?>" style="background:#FFF">
			<td align="center">
            <?php $uid = $t == 1 ? $v['cuid'] : $v['uid'];?>
				<a href="#" onclick="parent.addTab('<?php echo $uid;?>资料','index.php?action=allmember&h=view_info&uid=<?php echo $uid;?>','icon')"><?php echo $uid;?></a>
			</td>
			<td align="left"><?php echo $v['content'];?></td>
			<td align="center"><?php echo $v['send_del'] ? '已删除' : '未删除';?></td>
			<td align="center"><?php echo $v['receive_del'] ? '已删除' : '未删除';?></td>
			<td align="center"><?php echo date('Y-m-d H:i',$v['dateline']);?></td>
		</tr>
		<?php } ?>
	</table>
</div><br/>
	<?php if($pages >= 2) { ?><div style="text-align:center">第&nbsp;<?php for($i=1;$i<=$pages;$i++){?>
	<a href="javascript:lgopage(<?php echo $i;?>);"style="text-decoration:none;<?php if($page == $i) { ?>border:1px solid #3E679A; background:#7F99BE;<?php } ?>">&nbsp;<?php echo $i;?>&nbsp;</a>&nbsp;
<?php } ?>&nbsp;页</div><?php } ?>
</div>
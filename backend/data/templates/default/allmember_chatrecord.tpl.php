<script>
function chatgopage(p){
	var url = './allmember_ajax.php?n=chatrecord&uid=<?php echo $uid;?>&page='+p;
	$.get(url,function(data){
		$("#chat_showarea").html(data);
	});	
}

function del_chat_message(cid){
	var url="./allmember_ajax.php?n=delchatinfo&chatid="+cid;
	$.get(url,function(data){
		$("#chat_"+cid).remove();
	});
}
</script>
<!-- <script type="text/javascript" src="templates/js/onmousemove_minutes.js"></script> -->
<div id="chat_showarea">
<br/>
<div style="border: 1px solid #1C3F80; margin:0 auto; width:100%;">
<table width="100%" cellpadding="3" cellspacing="1" style="background:#FFF">
    <thead>
	<tr>	
		<th>发送方</th>
		<th>接收方</th>
		<th>聊天内容</th>
		<th>时间</th>
		<th>读取状态</th>
		<th>历史记录</th>
		<th>是否客服模拟</th>
		<?php if(in_array($GLOBALS['groupid'],$GLOBALS['system_admin'])) { ?><th>操作</th><?php } ?>
		</tr>
	</thead>
	<tbody>
	<?php foreach((array)$chats as $v) {?>

	<tr id = "chat_<?php echo $v['s_id'];?>">
    	<td align="center"><a href="#" onclick="parent.addTab('<?php echo $v['s_fromid'];?>资料','index.php?action=allmember&h=view_info&uid=<?php echo $v['s_fromid'];?>','icon')">
		<span class="from_gender"><?php if($v['is_server'] && $v['s_fromid'] != $uid) { ?> （女）<?php } else { ?>（男）<?php } ?></span>
		<?php echo $v['s_fromid'];?></a></td>
		<td align="center"><a href="#" onclick="parent.addTab('<?php echo $v['s_uid'];?>资料','index.php?action=allmember&h=view_info&uid=<?php echo $v['s_uid'];?>','icon')">
		 <span class="to_gender"><!-- <?php if($v['is_server'] && $v['s_uid'] != $uid) { ?>（男）<?php } else { ?>（女）<?php } ?>  --><?php if($v['is_server'] && $v['s_fromid'] != $uid) { ?>（男）<?php } else { ?>（女）<?php } ?> </span>
		<?php echo $v['s_uid'];?>
		</a></td>
		<td><?php echo $v['s_content'];?></td>
		<td align="center"><?php echo date('Y-m-d H:i:s',$v['s_time']);?></td>
		<td align="center"><?php if($v['s_status']==1) { ?>已读<?php } else { ?>未读<?php } ?></td>
		<td align="center"><a href="#" onclick="parent.addTab('<?php echo $v['s_uid']?>历史记录','index.php?action=allmember&h=view_info&type=chathistory&uid=<?php echo $v['s_uid'];?>&fromid=<?php echo $v['s_fromid'];?>','icon')">查看聊天历史记录</a></td>
		<td align="center"><?php if($v['is_server']==0) { ?>否<?php } else { ?>是<?php } ?></td>
		<?php if(in_array($GLOBALS['groupid'],$GLOBALS['system_admin'])) { ?><td><a href="#" onclick="del_chat_message('"<?php echo $v['s_id']?>"')">删除</a></td><?php } ?>
		</tr>
	<?php } ?>
	</tbody>
</table>
</div>
<br/>
<?php if($pages>=2) { ?><div style="text-align:center">第&nbsp;<?php for($i=1;$i<=$pages;$i++) echo '<a href="javascript:chatgopage('.$i.');">&nbsp;'.$i .'&nbsp;</a>';?>&nbsp;页</div><?php } ?>
</div>
<script type="text/javascript">
function letter(a){
	var url = './allmember_ajax.php?n=letter&uid=<?php echo $uid;?>&t='+a;
	$.get(url,function(data){
		$("#letter_showarea").html(data);
	});
}

function letgopage(p){
	var url = './allmember_ajax.php?n=letter&uid=<?php echo $uid;?>&t=<?php echo $t;?>&page='+p;
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
<?php if($t==1) { ?><img src="templates/images/down.gif" /><b><?php } ?><a href="javascript:letter(1);">收件箱</a> (<?php echo $scount;?>)</b>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
<?php if($t==2) { ?><img src="templates/images/down.gif" /><b><?php } ?><a href="javascript:letter(2);">发件箱</a> (<?php echo $fcount;?>)</b>
</div>
<?php if($t==1) { ?>
<div style="border: 1px solid #1C3F80; margin:0 auto; width:100%;">
	<table width="100%" cellpadding="3" cellspacing="1" style="background:#FFF">
		<tr>
			<th>发件人ID</th>
			<th>发件人身份</th>
			<th>标题</th>
            <th>内容</th>
			<th>发送时间</th>
			<th>是否阅读</th>
			<th>状态(收件人&nbsp;&nbsp;发件人)</th>
			<th>审核</th>
			</tr>
		<?php foreach((array)$letters as $k=>$v) {?>
		<tr>
			<td align="center"><a href="#" onclick="parent.addTab('<?php echo $v['s_fromid'];?>资料','index.php?action=allmember&h=view_info&uid=<?php echo $v['s_fromid'];?>','icon')"><?php echo $v['s_fromid'];?></a></td>
			<td align="center"><?php if($v['s_cid'] ==30) { ?>高级会员<?php } elseif ($v['s_cid'] ==40) { ?>普通会员<?php } elseif ($v['s_cid'] ==50) { ?>红娘<?php } ?></td>
			<td align="left">
				<div id="dragable_<?php echo $k;?>" style="position:absolute; border:1px solid #1C3F80;left:300px; top:300px; width:400px; display:none;">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr id="movetop_<?php echo $k;?>">
							<th style="padding-right:5px; height:10px;cursor:move"><span style="float:left; padding-left:5px; color:#FFF"><?php echo $v['s_title'];?></span><span style="float:right"><a href="javascript:closeDrag(<?php echo $k;?>);">关闭</a></span></th>
							</tr>
						<tr>
							<td style="padding:5px;"><?php echo $v['s_content'];?>
								<?php if($v['newfilename']) { ?>&nbsp;&nbsp;
								<?php $newfilename_arr = explode(" ",trim($v['newfilename']));?>
								<?php foreach((array)$newfilename_arr as $kk=>$vv) {?>
								<?php if(count($newfilename_arr)>1) { ?> 
								<a style="color:green" href="./../data/upload/emailattachment/<?php echo $vv;?>" target="_blank">附件<?php echo ($kk+1)?></a>
								<?php } else { ?>
								<a style="color:green" href="./../data/upload/emailattachment/<?php echo $vv;?>" target="_blank">附件</a>
								<?php } ?>
								<?php }?>
								<?php } ?>
								</td>
							</tr>
						</table>
					</div>
				<a href="javascript:regDrag(<?php echo $k;?>);" style="text-decoration:underline"><?php echo $v['s_title'];?></a>
			</td>
            <td><?php echo $v['s_content'];?></td>
			<td align="center"><?php echo date("Y-m-d H:i",$v['s_time']);?></td>
			<td align="center"><?php if($v['s_status']) { ?>已阅&nbsp;(<?php echo $v['read_time'];?>)<?php } else { ?>未阅读<?php } ?></td>
			<td align="center"><?php if($v['s_uid_del']) { ?>已删除<?php } else { ?>未删除<?php } ?>&nbsp;&nbsp;<?php if($v['s_fromid_del']) { ?>已删除<?php } else { ?>未删除<?php } ?></td>
			<td align="center"><?php if($v['flag']==0) { ?>未审核<?php } elseif ($v['flag']==1) { ?>通过<?php } else { ?>未通过<?php } ?></td>
			</tr>
		<?php }?>
	</table>
</div><br/>
	<?php if($pages>=2) { ?><div style="text-align:center">第&nbsp;<?php for($i=1;$i<=$pages;$i++){?>
	<a href="javascript:letgopage(<?php echo $i;?>);"style="text-decoration:none;<?php if($page==$i) { ?>border:1px solid #3E679A; background:#7F99BE;<?php } ?>">&nbsp;<?php echo $i;?>&nbsp;</a>&nbsp;
<?php } ?>&nbsp;页</div><?php } ?>
<?php } else { ?>
<div style="border: 1px solid #1C3F80; margin:0 auto; width:100%;">
	<table width="100%" cellpadding="3" cellspacing="1" style="background:#FFF">
		<tr>
			<th>收件人ID</th>
			<th>收件人等级</th>
			<th>标题</th>
            <th>内容</th>
			<th>发送时间</th>
			<th>是否阅读</th>
			<th>状态(收件人&nbsp;&nbsp;发件人)</th>
			<th>审核</th>
			</tr>
		<?php foreach((array)$letters as $v) {?>
		<tr>
			<td align="center"><a href="#" onclick="parent.addTab('<?php echo $v['s_uid'];?>资料','index.php?action=allmember&h=view_info&uid=<?php echo $v['s_uid'];?>','icon')"><?php echo $v['s_uid'];?></a></td>
			<td align="center"><?php if($v['s_cid'] ==30) { ?>高级会员<?php } elseif ($v['s_cid'] ==40) { ?>普通会员<?php } elseif ($v['s_cid'] ==50) { ?>红娘<?php } ?></td>
			<td align="left"><div id="dragable_<?php echo $k;?>" style="position:absolute; border:1px solid #1C3F80;left:300px; top:300px; width:400px; display:none;">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr id="movetop_<?php echo $k;?>">
							<th style="text-align:right;padding-right:5px; height:10px;cursor:move"><a href="javascript:closeDrag(<?php echo $k;?>);">关闭</a></th>
							</tr>
						<tr>
							<td style="padding:5px;"><?php echo $v['s_content'];?>
								<?php if($v['newfilename']) { ?>&nbsp;&nbsp;
								<?php $newfilename_arr = explode(" ",trim($v['newfilename']));?>
								<?php foreach((array)$newfilename_arr as $kk=>$vv) {?>
								<?php if(count($newfilename_arr)>1) { ?> 
								<a style="color:green" href="./../data/upload/emailattachment/<?php echo $vv;?>" target="_blank">附件<?php echo ($kk+1)?></a>
								<?php } else { ?>
								<a style="color:green" href="./../data/upload/emailattachment/<?php echo $vv;?>" target="_blank">附件</a>
								<?php } ?>
								<?php }?>
								<?php } ?>
								</td>
							</tr>
						</table>
					</div>
				<a href="javascript:regDrag(<?php echo $k;?>);" style="text-decoration:underline"><?php echo $v['s_title'];?></a></td>
            <td><?php echo $v['s_content'];?></td>
			<td align="center"><?php echo date("Y-m-d H:i:s",$v['s_time']);?></td>
			<td align="center"><?php if($v['s_status']) { ?>已阅&nbsp;(<?php echo $v['read_time'];?>)<?php } else { ?>未阅读<?php } ?></td>
			<td align="center"><?php if($v['s_uid_del']) { ?>已删除<?php } else { ?>未删除<?php } ?>&nbsp;&nbsp;<?php if($v['s_fromid_del']) { ?>已删除<?php } else { ?>未删除<?php } ?></td>
			<td align="center"><?php if($v['flag']==0) { ?>未审核<?php } elseif ($v['flag']==1) { ?>通过<?php } else { ?>拒绝<?php } ?></td>
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
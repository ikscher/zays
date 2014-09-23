<script type="text/javascript" src="templates/js/onmousemove_minutes.js"></script>
<script type="text/javascript">
function commission(a){
	var url = './allmember_ajax.php?n=commission&sid=<?php echo $sid;?>&uid=<?php echo $uid;?>&t='+a;
	$.get(url,function(data){
		$("#co_showarea").html(data);
	});
}
function cgopage(p){
	var url = './allmember_ajax.php?n=commission&sid=<?php echo $sid;?>&uid=<?php echo $uid;?>&t=<?php echo $t;?>&page='+p;
	$.get(url,function(data){
		$("#co_showarea").html(data);
	});	
}


	function pop(obj,uid){
		var append_obj = $('#pic_commission_'+uid);		
		append_obj.css("left", getPosition(obj, "left")+50);
		append_obj.css("top", getPosition(obj, "top") - 180);
		append_obj.show();
		
	}
	function getPosition(what, offsettype) {
		var totaloffset=(offsettype=="left")? what.offsetLeft : what.offsetTop;
		var parentEl=what.offsetParent;
		while (parentEl!=null) {
			totaloffset=(offsettype=="left")? totaloffset+parentEl.offsetLeft : totaloffset+parentEl.offsetTop;
			parentEl=parentEl.offsetParent;
		}
		return totaloffset;
	}
	$(function(){
		$("a[id^='display_pic_']").hover(function(){
			var id_split = this.id.split('_');
		
			pop(this,id_split[3]);
		},function(){
			var id_split = this.id.split('_');
			$("#pic_commission_"+id_split[3]).hide();
		})
	})
</script>
<div id="co_showarea">
<div>
<br/>
<?php if($t==1) { ?><img src="templates/images/down.gif" /><b><?php } ?><a href="javascript:commission(1);">我收到的委托</a> (<?php echo $cocount1;?>)</b>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
<?php if($t==2) { ?><img src="templates/images/down.gif" /><b><?php } ?><a href="javascript:commission(2);">我发送的委托</a> (<?php echo $cocount2;?>)</b>
</div>
<?php if($t==1) { ?>
<div style="border: 1px solid #1C3F80; margin:0 auto; width:100%;">
	<table width="100%" cellpadding="3" cellspacing="1" style="background:#FFF">
		<tr>
			<th>TA的ID</th>
			<th>TA的昵称</th>
			<th>TA的年龄</th>
			<th>在线状态</th>
			<th>发送时间</th>
			<th>工作地区</th>
			<th>委托留言</th>
			<th>会员来源</th>
			<th>发送者</th>
			<th>对方回应</th>
			<th>操作</th>
			</tr>
		<?php foreach((array)$commissions as $v) {?>
		<tr class="tr_<?php echo $v['stat'];?>" id="commission_tr_<?php echo $v['mid'];?>">
			<td align="center">
				<a href="#" id="display_pic_commission_<?php echo $v['uid'];?>" onclick="parent.addTab('<?php echo $v['uid'];?>资料','index.php?action=allmember&h=view_info&uid=<?php echo $v['uid'];?>','icon')"><?php echo $v['uid'];?></a>
				<span style="display:none;position:absolute;z-index:1000;"  id="pic_commission_<?php echo $v['uid'];?>"><?php $img=MooGetphotoAdmin($v['uid'],'mid');?><?php if($img) { ?><img src="<?php echo $img;?>" /><?php } else { ?><?php } ?></span>
			</td>
			<td align="center"><?php if($v['gender']==1) { ?><img src="templates/images/w.gif" alt="女" title="女"/>
				<?php } else { ?><img src="templates/images/m.gif" alt="男" title="男"/>
				<?php } ?><?php echo $v['nickname'];?></td>
			<td align="center"><?php echo date("Y")-$v['birthyear'];?></td>
			<td align="center"><?php if(check_online($v['uid'])) { ?>
							<span style="color:#CF6454;">在线</span>
						<?php } elseif (time()-$v['lastvisit']<24*3600) { ?>
							<span style="color:#B4A23B;">一天内</span>
						<?php } elseif (time()-$v['lastvisit']<7*24*3600) { ?>
							<span style="color:#629DBE;">一周内</span>
						<?php } else { ?>
							<span style="">一周外</span><?php } ?></td>
			<td align="center"><?php echo date("Y-m-d H:i",$v['sendtime']);?></td>
			<td align="center"><?php if(isset($provice_list[$v['province']])) { ?><?php echo $provice_list[$v['province']]?><?php } ?><?php if(isset($city_list[$v['city']])) { ?><?php echo $city_list[$v['city']]?><?php } ?></td>
			<td align="center"><?php if(isset($v['content'])) { ?><?php echo $v['content'];?><?php } ?></td>
			<td align="center"><?php if($v['usertype']==1)echo "本站注册";if($v['usertype']==2)echo "外站加入";if($v['usertype']==3)echo "全权会员";?></td>
			<td align="center"><?php if($v['is_server']==1) { ?>红娘<?php } else { ?>会员<?php } ?></td>
			<td align="center">站内信：<?php echo get_letter_num($v['uid'],$uid);?>&nbsp;&nbsp;委托：<?php echo get_commission_state($v['uid'],$uid,"receive");?>&nbsp;&nbsp; 
            秋波：<?php echo get_leer_num($v['uid'],$uid);?>&nbsp;&nbsp;鲜花：<?php echo get_rose_num($v['uid'],$uid);?>&nbsp;&nbsp;聊天：<?php echo get_chat_num($v['uid'],$uid);?></td>
			<td align="center"><?php if($sid!=52 || in_array($GLOBALS['groupid'],array(60,61,76,75,77,62,63,68,69,70,78,79,81)) ) { ?><a href="javascript:contact(3,<?php echo $uid;?>,<?php echo $v['uid'];?>);">回信</a> | <span id="com_commission_<?php echo $v['uid'];?>"><a href="javascript:sendActive('com','commission',<?php echo $v['uid'];?>,<?php echo $uid;?>);">委托</a></span> | <span id="com_leer_<?php echo $v['uid'];?>"><a href="javascript:sendActive('com','leer',<?php echo $v['uid'];?>,<?php echo $uid;?>);">秋波</a></span> | <span id="com_rose_<?php echo $v['uid'];?>"><a href="javascript:sendActive('com','rose',<?php echo $v['uid'];?>,<?php echo $uid;?>);">鲜花</a></span> | <a href="javascript:delActive('commission',<?php echo $v['mid'];?>);">删除</a><?php } ?></td>
			</tr>
		<?php } ?>
	</table>
</div><br/>
	<?php if($pages>=2) { ?><div style="text-align:center">第&nbsp;<?php for($i=1;$i<=$pages;$i++){?>
	<a href="javascript:cgopage(<?php echo $i;?>);"style="text-decoration:none;<?php if($page==$i) { ?>border:1px solid #3E679A; background:#7F99BE;<?php } ?>">&nbsp;<?php echo $i;?>&nbsp;</a>&nbsp;
<?php } ?>&nbsp;页</div><?php } ?>
<?php } else { ?>
<div style="border: 1px solid #1C3F80; margin:0 auto; width:100%;">
	<table width="100%" cellpadding="3" cellspacing="1" style="background:#FFF">
		<tr>
			<th>TA的ID</th>
			<th>TA的昵称</th>
			<th>TA的年龄</th>
			<th>在线状态</th>
			<th>发送时间</th>
			<th>工作地区</th>
			<th>委托留言</th>
			<th>会员来源</th>
			<th>发送者</th>
			<th>我的回应</th>
			<th>操作</th>
			</tr>
		<?php foreach((array)$commissions as $v) {?>
		
		<tr class="tr_<?php echo $v['stat'];?>" id="commission_tr_<?php echo $v['mid'];?>">
			<td align="center">
				<a href="#" id="display_pic_commission_<?php echo $v['uid'];?>" onclick="parent.addTab('<?php echo $v['uid'];?>资料','index.php?action=allmember&h=view_info&uid=<?php echo $v['uid'];?>','icon')"><?php echo $v['uid'];?></a>
				<span style="display:none;position:absolute;z-index:1000;"  id="pic_commission_<?php echo $v['uid'];?>"><?php $img=MooGetphotoAdmin($v['uid'],'mid');?><?php if($img) { ?><img src="<?php echo $img;?>" /><?php } else { ?><?php } ?></span>
			</td>
			<td align="center"><?php if($v['gender']==1) { ?><img src="templates/images/w.gif" alt="女" title="女"/>
				<?php } else { ?><img src="templates/images/m.gif" alt="男" title="男"/>
				<?php } ?><?php echo $v['nickname'];?></td>
			<td align="center"><?php echo date("Y")-$v['birthyear'];?></td>
			<td align="center"><?php if(check_online($v['uid'])) { ?>
							<span style="color:#CF6454;">在线</span>
						<?php } elseif (time()-$v['lastvisit']<24*3600) { ?>
							<span style="color:#B4A23B;">一天内</span>
						<?php } elseif (time()-$v['lastvisit']<7*24*3600) { ?>
							<span style="color:#629DBE;">一周内</span>
						<?php } else { ?>
							<span style="">一周外</span><?php } ?></td>
			<td align="center"><?php echo date("Y-m-d H:i",$v['sendtime']);?></td>
			<td align="center"><?php if(isset($provice_list[$v['province']])) { ?><?php echo $provice_list[$v['province']]?><?php } ?><?php if(isset($city_list[$v['city']])) { ?><?php echo $city_list[$v['city']]?><?php } ?></td>
			<td align="center"><?php echo $v['content'];?></td>
			<td align="center"><?php if($v['usertype']==1)echo "本站注册";if($v['usertype']==2)echo "外站加入";if($v['usertype']==3)echo "全权会员";?></td>
			<td align="center"><?php if($v['is_server']==1) { ?>红娘<?php } else { ?>会员<?php } ?></td>
			<td align="center">站内信：<?php echo get_letter_num($uid,$v['uid']);?>&nbsp;&nbsp;委托：<?php echo get_commission_state($uid,$v['uid'],"send");?>&nbsp;&nbsp;秋波：<?php echo get_leer_num($uid,$v['uid']);?>&nbsp;&nbsp;鲜花：<?php echo get_rose_num($uid,$v['uid']);?>&nbsp;&nbsp;聊天：<?php echo get_chat_num($uid,$v['uid']);?></td>
			<td align="center"><?php if($sid!=52 || in_array($GLOBALS['groupid'],array(60,61,76,75,77,62,63,68,69,70,78,79,81)) ) { ?><a style="display:none" href="javascript:contact(3,<?php echo $uid;?>,<?php echo $v['uid'];?>);">回信</a> | <span id="com_commission_<?php echo $v['uid'];?>"><a href="javascript:sendActive('com','commission',<?php echo $uid;?>,<?php echo $v['uid'];?>);">委托</a></span> | <span id="com_leer_<?php echo $v['uid'];?>"><a href="javascript:sendActive('com','leer',<?php echo $uid;?>,<?php echo $v['uid'];?>);">秋波</a></span> | <span id="com_rose_<?php echo $v['uid'];?>"><a href="javascript:sendActive('com','rose',<?php echo $uid;?>,<?php echo $v['uid'];?>);">鲜花</a></span> | <a href="javascript:delActive('commission',<?php echo $v['mid'];?>);">删除</a><?php } ?></td>
			</tr>
		<?php } ?>
	</table>
</div><br/>
	<?php if($pages>=2) { ?><br/><div style="text-align:center">第&nbsp;<?php for($i=1;$i<=$pages;$i++){?>
	<a href="javascript:cgopage(<?php echo $i;?>);"style="text-decoration:none;<?php if($page==$i) { ?>border:1px solid #3E679A; background:#7F99BE;<?php } ?>">&nbsp;<?php echo $i;?>&nbsp;</a>&nbsp;
<?php } ?>&nbsp;页</div><?php } ?>
<?php } ?>
</div>
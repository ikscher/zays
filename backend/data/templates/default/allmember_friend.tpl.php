<script type="text/javascript" src="templates/js/onmousemove_minutes.js"></script>
<script type="text/javascript">
function friend(a){
	var url = './allmember_ajax.php?n=friend&uid=<?php echo $uid;?>&t='+a;
	$.get(url,function(data){
		$("#friend_showarea").html(data);
	});
}
function fgopage(p){
	var url = './allmember_ajax.php?n=friend&uid=<?php echo $uid;?>&t=<?php echo $t;?>&page='+p;
	$.get(url,function(data){
		$("#friend_showarea").html(data);
	});	
}

	function pop(obj,uid){
		var append_obj = $('#pic_'+uid);		
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
		
			pop(this,id_split[2]);
		},function(){
			var id_split = this.id.split('_');
			$("#pic_"+id_split[2]).hide();
		})
	})
</script>
<div id="friend_showarea">
<div style="padding:0px; margin:0px;">
<br/>
<?php if($t==1) { ?><img src="templates/images/down.gif" /><b><?php } ?><a href="javascript:friend(1);">我的意中人</a> (<?php echo $fcount1;?>)</b>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
<?php if($t==2) { ?><img src="templates/images/down.gif" /><b><?php } ?><a href="javascript:friend(2);">加我为意中人</a> (<?php echo $fcount2;?>)</b>
</div>
<?php if($t==1) { ?>
<div style="border: 1px solid #1C3F80; margin:0 auto; width:100%;">
	<table width="100%" cellpadding="3" cellspacing="1" style="background:#FFF">
		<tr>
			<th>TA的ID</th>
			<th>TA的昵称</th>
			<th>TA的年龄</th>
			<th>在线状态</th>
			<th>发送数量</th>
			<th>发送时间</th>
			<th>工作地区</th>
			<th>会员来源</th>
			<th>对方回应</th>
			<th>操作</th>
			</tr>
		<?php foreach((array)$friends as $v) {?>
		<tr class="tr_<?php if(isset($v['stat'])) { ?><?php echo $v['stat'];?><?php } ?>" id="friend_tr_<?php echo $v['fid'];?>">
			<td align="center">
				<a href="#" id="display_pic_<?php echo $v['uid'];?>" onclick="parent.addTab('<?php echo $v['uid'];?>资料','index.php?action=allmember&h=view_info&uid=<?php echo $v['uid'];?>','icon')"><?php echo $v['uid'];?></a>
				<span style="display:none;position:absolute;z-index:1000;"  id="pic_<?php echo $v['uid'];?>"><?php $img=MooGetphotoAdmin($v['uid'],'mid');?><?php if($img) { ?><img src="<?php echo $img;?>" /><?php } else { ?><?php } ?></span>
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
			<td align="center"><?php echo get_friend_num($uid,$v['uid']);?></td>
			<td align="center"><?php echo date("Y-m-d H:i",$v['sendtime']);?></td>
			<td align="center"><?php if(isset($provice_list[$v['province']])) { ?><?php echo $provice_list[$v['province']]?><?php } ?><?php if(isset($city_list[$v['city']])) { ?><?php echo $city_list[$v['city']]?><?php } ?></td>
			<td align="center"><?php if($v['usertype']==1)echo "本站注册";if($v['usertype']==2)echo "外站加入";if($v['usertype']==3)echo "全权会员";?></td>
			<td align="center">站内信：<?php echo get_letter_num($v['uid'],$uid);?>&nbsp;&nbsp;委托：<?php echo get_commission_num($v['uid'],$uid);?>&nbsp;&nbsp;秋波：<?php echo get_leer_num($v['uid'],$uid);?>&nbsp;&nbsp;鲜花：<?php echo get_rose_num($v['uid'],$uid);?>&nbsp;&nbsp;聊天：<?php echo get_chat_num($v['uid'],$uid);?></td>
			<td align="center"><a style="display:none" href="javascript:contact(3,<?php echo $uid;?>,<?php echo $v['uid'];?>);">回信</a> | <span id="friend_commission_<?php echo $v['uid'];?>"><a href="javascript:sendActive('friend','commission',<?php echo $v['uid'];?>,<?php echo $uid;?>);">委托</a></span> | <span id="friend_leer_<?php echo $v['uid'];?>"><a href="javascript:sendActive('friend','leer',<?php echo $v['uid'];?>,<?php echo $uid;?>);">秋波</a></span> | <span id="friend_rose_<?php echo $v['uid'];?>"><a href="javascript:sendActive('friend','rose',<?php echo $v['uid'];?>,<?php echo $uid;?>);">鲜花</a></span> | <a href="javascript:delActive('friend',<?php echo $v['fid'];?>);">删除</a></td>
			</tr>
		<?php } ?>
	</table>
</div><br/>
	<?php if($pages>=2) { ?><div style="text-align:center">第&nbsp;<?php for($i=1;$i<=$pages;$i++){?>
	<a href="javascript:fgopage(<?php echo $i;?>);"style="text-decoration:none;<?php if($page==$i) { ?>border:1px solid #3E679A; background:#7F99BE;<?php } ?>">&nbsp;<?php echo $i;?>&nbsp;</a>&nbsp;
<?php } ?>&nbsp;页</div><?php } ?>
<?php } else { ?>
<div style="border: 1px solid #1C3F80; margin:0 auto; width:100%;">
	<table width="100%" cellpadding="3" cellspacing="1" style="background:#FFF">
		<tr>
			<th>TA的ID</th>
			<th>TA的昵称</th>
			<th>TA的年龄</th>
			<th>在线状态</th>
			<th>发送数量</th>
			<th>发送时间</th>
			<th>工作地区</th>
			<th>会员来源</th>
			
			<th>我的回应</th>
			<th>操作</th>
			</tr>
		<?php foreach((array)$friends as $v) {?>
		<tr class="tr_<?php if(isset($v['stat'])) { ?><?php echo $v['stat'];?><?php } ?>" id="friend_tr_<?php if(isset($v['fid'])) { ?><?php echo $v['fid'];?><?php } ?>">
			<td align="center">
				<a href="#" id="display_pic_<?php echo $v['uid'];?>" onclick="parent.addTab('<?php echo $v['uid'];?>资料','index.php?action=allmember&h=view_info&uid=<?php echo $v['uid'];?>','icon')"><?php echo $v['uid'];?></a>
				<span style="display:none;position:absolute;z-index:1000;" id="pic_<?php echo $v['uid'];?>"><?php $img=MooGetphotoAdmin($v['uid'],'mid');?><?php if($img) { ?><img src="<?php echo $img;?>" /><?php } else { ?><?php } ?></span>
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
			<td align="center"><?php echo get_friend_num($v['uid'],$uid);?></td>
			<td align="center"><?php echo date("Y-m-d H:i",$v['sendtime']);?></td>
			<td align="center"><?php if(isset($provice_list[$v['province']])) { ?><?php echo $provice_list[$v['province']]?><?php } ?><?php if(isset($city_list[$v['city']])) { ?><?php echo $city_list[$v['city']]?><?php } ?></td>
			<td align="center"><?php if($v['usertype']==1)echo "本站注册";if($v['usertype']==2)echo "外站加入";if($v['usertype']==3)echo "全权会员";?></td>
			<td align="center">站内信：<?php echo get_letter_num($uid,$v['uid']);?>&nbsp;&nbsp;委托：<?php echo get_commission_num($uid,$v['uid']);?>&nbsp;&nbsp;秋波：<?php echo get_leer_num($uid,$v['uid']);?>&nbsp;&nbsp;鲜花：<?php echo get_rose_num($uid,$v['uid']);?>&nbsp;&nbsp;聊天：<?php echo get_chat_num($uid,$v['uid']);?></td>
			<td align="center"><a href="javascript:contact(3,<?php echo $uid;?>,<?php echo $v['uid'];?>);">回信</a> | <span id="friend_commission_<?php echo $v['uid'];?>"><a href="javascript:sendActive('friend','commission',<?php echo $uid;?>,<?php echo $v['uid'];?>);">委托</a></span> | <span id="friend_leer_<?php echo $v['uid'];?>"><a href="javascript:sendActive('friend','leer',<?php echo $uid;?>,<?php echo $v['uid'];?>);">秋波</a></span> | <span id="friend_rose_<?php echo $v['uid'];?>"><a href="javascript:sendActive('friend','rose',<?php echo $uid;?>,<?php echo $v['uid'];?>);">鲜花</a></span> | <a href="javascript:delActive('friend',<?php echo $v['fid'];?>);">删除</a></td>
			</tr>
		<?php } ?>
	</table>
</div><br/>
	<?php if($pages>=2) { ?><br/><div style="text-align:center">第&nbsp;<?php for($i=1;$i<=$pages;$i++){?>
	<a href="javascript:fgopage(<?php echo $i;?>);"style="text-decoration:none;<?php if($page==$i) { ?>border:1px solid #3E679A; background:#7F99BE;<?php } ?>">&nbsp;<?php echo $i;?>&nbsp;</a>&nbsp;
<?php } ?>&nbsp;页</div><?php } ?>
<?php } ?>
</div>
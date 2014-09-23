<link href="templates/css/general.css" rel="stylesheet" type="text/css" />
<link href="templates/css/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="templates/js/onmousemove_minutes.js"></script>
<script type="text/javascript">
function checkForm(){
	var keyword = $("#keyword").val();
	if(keyword == ""){
		alert("请填写搜索内容");
		return false;
	}
}
function change_s_cid(uid,pid){
	var ch_url = "vipuser_ajax.php?n=upgrade&uid="+uid+"&pid="+pid;
	if(confirm('你确定此会员已支付并让其升级会员吗？')){
	       $.get(ch_url,function(str){
	            if(str=='ok'){
					alert("升级会员成功！");
					$("#tr_"+pid).css('display','none');
				}else{
					alert(str);
				}
	       });
	}
}
</script>
<h1> <span class="action-span1"><a href="###">真爱一生网 管理中心</a> </span><span id="search_id" class="action-span1"> - 会员升级已支付列表</span>
<span class="action-span"><a href="<?php $u=explode('&',$_SERVER['QUERY_STRING']);?>index.php?<?php echo $u[0];?>&<?php echo $u[1];?>">刷新</a></span>
	<div style="clear:both"></div>
</h1>
<div class="list-div" style="padding:5px;">
	<form action="index.php?action=vipuser&h=pay" method="post" onsubmit="return checkForm();">
		搜索：
		<select name="type" id="type">
			<option value="username" <?php if($type=='username') { ?> selected="selected" <?php } ?>>用户名</option>
			<option value="telphone" <?php if($type=='telphone') { ?> selected="selected" <?php } ?>>手机号</option>
			<option value="uid" <?php if($type=='uid') { ?> selected="selected" <?php } ?>>用户ID号</option>
			<option value="pid" <?php if($type=='pid') { ?> selected="selected" <?php } ?>>支付ID号</option>
		</select>
		<input name="keyword" type="text" id="keyword" size="30" value="<?php echo $keyword;?>"  />
		&nbsp;&nbsp;&nbsp;
		<input name="提交" type="submit" value="搜 索" />
	</form>
</div>
<p/>
<div class="list-div" id="listDiv">
	<table cellspacing='1' cellpadding='3' id='list-table'>
		<tr>
			<th>用户ID</th>
			<th>用户名</th>
			<th>用户昵称</th>
			<th>支付ID</th>
			<th>订单号</th>
			<th>支付类型</th>
			<th>升级类型</th>
			<th>支付总金额</th>
			<th>操作</th>
		</tr>
		<?php foreach((array)$ret_list as $p) {?>
		<tr id='tr_<?php echo $p['pid'];?>'>
			<td><a href="#" onclick="parent.addTab('<?php echo $p['uid'];?>资料','index.php?action=allmember&h=view_info&uid=<?php echo $p['uid'];?>','icon')"><?php echo $p['uid'];?></a></td>
			<td><a href="#" onclick="parent.addTab('<?php echo $p['uid'];?>资料','index.php?action=allmember&h=view_info&uid=<?php echo $p['uid'];?>','icon')"><?php echo $p['username'];?></a></td>
			<td><?php echo $p['nickname'];?></td>
			<td><?php echo $p['pid'];?></td>
			<td><?php echo $p['order_id'];?></td>
			<td><?php  echo $bankarr[$p[code]];?></td>
			<td><?php echo $arr_payfor[$p['payfor']];?></td>
			<td><?php echo $p['money'];?></td>
			<td><a href="javascript:change_s_cid(<?php echo $p['uid'];?>,<?php echo $p['pid'];?>);">通过</a></td>
		</tr>
		<?php } ?>
		<tr>
			<td colspan="7" align="center"><?php echo $pages;?></td>
		</tr>
	</table>
</div>

<link href="templates/css/general.css" rel="stylesheet" type="text/css" />
<link href="templates/css/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="templates/js/My97DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="templates/js/onmousemove_minutes.js"></script>
<script type="text/javascript">
function checkForm(){
	var keyword = $("#keyword").val();
	var type = $("#type").val();
	if(keyword == ""){
		alert("请填写搜索内容");
		return false;
	}

	if(type == ""){
		alert("请选择搜索类型");
		return false;
	}
	return true;
}

function change_s_cid1(uid){
	var ch_url = "vipuser_ajax.php?n=star&uid="+uid;
	if(confirm('你确定将此会员设为普通会员吗？')){
		$.get(ch_url,function(str){
			if(str=='ok'){alert("设置会员成功！");$("#trs_"+uid).css('display','none');}else{alert("设置会员失败！");}
		});
	}
}

//分页跳转
function gotoPage() {
	var page = $("#pageGo").val();
	var page = parseInt(page);
	
	if(page<1) page = 1;
	if(page><?php echo ceil($total/$page_per);?>)

	page = <?php echo ceil($total/$page_per);?>;
	window.location.href = "<?php echo $currenturl;?>&page="+page;
}
</script>
<h1>
	<span class="action-span1"><a href="###">真爱一生网 管理中心</a> </span><span id="search_id" class="action-span1"> - 到期城市之星列表</span>
	<span class="action-span"><a href="index.php?action=vipuser&h=city_star">刷新</a></span>
	<div style="clear:both"></div>
</h1>
<div class="list-div" style="padding:5px;">
	<form action="" method="get">
		<select name="type" id="type"  style="vertical-align:middle;">
			<option value="">请选择搜索类型</option>
			<option value="uid">用户ID号</option>
			<option value="username">用户名</option>
			<option value="telphone">手机号</option>
		</select>
		关键字：
		<input name="keyword" type="text" id="keyword" size="15" value="" />
		&nbsp;&nbsp;&nbsp;到期时间段：
		<input type="text" name="bgtime" value="" onFocus="WdatePicker({startDate:'%y-%M-%D 00:00:00',dateFmt:'yyyy-MM-dd',alwaysUseStartDate:true})" style="width:100px;"/>到
		<input type="text" name="endtime" value="" onFocus="WdatePicker({startDate:'%y-%M-%D 00:00:00',dateFmt:'yyyy-MM-dd',alwaysUseStartDate:true})"  style="width:100px;"/>
		&nbsp;&nbsp;
		<select name="renew" id="renew"  style="vertical-align:middle;">
			<option value="">请选择要续费月份</option>
			<?php for($i=$current_month;$i<=12;$i++) {?>
				<option value=<?php echo date("Y-$i-01")?>><?php echo $i;?>月要续费的</option>
			<?php } ?>
		</select>
		&nbsp;&nbsp;
        <input type="hidden" name="action" value="vipuser" />
        <input type="hidden" name="h" value="city_star"  />
		<input name="submit" type="submit" value="搜 索" />
	</form>
</div>
<div class="list-div" style="padding:5px;height:20px;">
<span class="action-span-day"><a href='?action=vipuser&h=city_star&afterday=<?php echo date("Y-m-d",strtotime("+1 day"));?>'>今天到期</a></span>
<?php for($i=2;$i<=$current_day+1;$i++) {?>
	<span class="action-span-day"><a href='?action=vipuser&h=city_star&afterday=<?php echo date("Y-m-d",strtotime("+$i day"));?>'><?php echo ($i-1);?>天到期</a></span>
<?php } ?>
</div>
<p/>
<div class="list-div" id="listDiv">
	<table cellpadding="3" cellspacing="1" id='list-table'>
		<tr>
			<th>用户ID</th>
			<th>用户名</th>
			<th>用户昵称</th>
			<th>会员类型</th>
			<th>开始时间</th>
			<th>结束时间</th>
			<th>操作</th>
		</tr>
		<?php foreach((array)$user as $u) {?>
		<tr id="trs_<?php echo $u['uid'];?>">
			<td align="left"><a href="#" onclick="parent.addTab('<?php echo $u['uid'];?>资料','index.php?action=allmember&h=view_info&uid=<?php echo $u['uid'];?>','icon')"><?php echo $u['uid'];?></a></td>
			<td align="left"><?php echo $u['username'];?></td>
			<td align="left"><?php echo $u['nickname'];?></td>
			<td align="left">
				<?php echo $GLOBALS['member_level'][$u['s_cid']];?>
			</td>
			<td align="left"><?php if($u['bgtime']) echo date("Y-m-d H:i",$u['bgtime']);?></td>
			<td align="left"><?php if($u['endtime']) echo date("Y-m-d H:i",$u['endtime']);?></td>
			<td align="left"><a href="javascript:change_s_cid1(<?php echo $u['uid'];?>);">设为普通会员</a></td>
		</tr>
		<?php } ?>
		<tr>
			<td colspan="7" align="center"><?php echo $pages;?>
				&nbsp;&nbsp;&nbsp;
			转到第
			<input name="pageGo"  id="pageGo" type="text" style="width:20px;height:15px;" value="" /> 页 &nbsp;
			<input type="button"  class="ser_go" value="跳转" onclick="gotoPage()"/>
			</td>
		</tr>
	</table>
</div>

<link href="templates/css/general.css" rel="stylesheet" type="text/css" />
<link href="templates/css/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
//跳转
	function gotoPage(){
		var page=document.getElementById("pageGo").value;
		window.location="index.php?action=check&h=<?php echo $_GET['h'];?>&pass=<?php echo $pass;?>&page="+page;
	}
</script>
<h1>
<span class="action-span1"><a href="###">真爱一生网 管理中心</a> </span><span id="search_id" class="action-span1"> - 会员视频审核</span>
<span class="action-span"><a href="<?php $u=explode('&',$_SERVER['QUERY_STRING']);?>index.php?<?php echo $u[0];?>&<?php echo $u[1];?>">刷新</a></span>
<div style="clear:both"></div>
</h1>
<div class="list-div" style="margin-bottom:10px;padding:5px;">
<form action="#" method="post">
	<input type="hidden" name="pass" value="<?php echo $pass;?>" />
          会员ID：&nbsp;&nbsp;<input type="text" name="uid" value="<?php if(!empty($uid)) { ?><?php echo $uid;?><?php } ?>" />
	<?php if($myservice_idlist && $myservice_idlist!=$GLOBALS['adminid']) { ?>
	客服：<select name="usersid" id="usersid" onchange="option_gourl(this.value,<?php echo $pass;?>)" style="height:21px;margin-top:3px;" >
	   <option value='-1' <?php if($usersid==-1) { ?> selected="selected"<?php } ?>> 不限</option>
	   <option value="0" <?php if($usersid == 0) { ?> selected="selected"<?php } ?>>0：</option>
	   <?php foreach((array)$adminUser as $value) {?>
	   <option value="<?php echo $value['uid'];?>" <?php if($usersid == $value['uid']) { ?> selected="selected"<?php } ?>><?php echo $value['uid'];?>：<?php echo $value['username'];?></option>
	   <?php } ?>
	</select>
<?php } ?> &nbsp;&nbsp;<input type="submit" value=" 查 找 " /><span class="but_a"><a href="index.php?action=check&h=video&pass=1" <?php if(isset($_GET['pass']) && $_GET['pass']==1) { ?>style="font-weight:bold;"<?php } ?>>已审核</a><a href="index.php?action=check&h=video&pass=0" <?php if(isset($_GET['pass']) && $_GET['pass']==0) { ?>style="font-weight:bold;"<?php } ?>>未审核</a></span>
</form>
</div>
<div style=" margin-top:10px;">
	
</div>
<div class="list-div" id="listDiv">
<table width="100%" cellspacing='1' cellpadding='3' id='list-table' style="word-wrap: break-word; overflow: hidden;">
  <tr>
    <th width="%6">会员ID</th>
	<th width="%8">昵称</th>
	<th width="%5">年龄</th>
	<th width="%8">最后登录时间</th>
    <th width="%8">视频上传时间</th>
	<th width="%40">类型</th>
	<th width="%8">审核状态</th>
	<th width="%8">所属客服ID</th>
	<th width="%12">操作</th>
  </tr>
  <?php foreach((array)$check_video as $v) {?>
  <tr onmousemove="this.bgColor='#cfeefe';" onmouseout="this.bgColor='#ffffff';">
    <td align="center"><a href="#" onclick="parent.addTab('查看<?php echo $v['uid'];?>资料','index.php?action=allmember&h=view_info&uid=<?php echo $v['uid'];?>','icon')"><?php echo $v['uid'];?></a></td>
	<td align="center">
		<?php if($v['gender']==1) { ?><img src="templates/images/w.gif" alt="女" title="女"/>
		<?php } else { ?><img src="templates/images/m.gif" alt="男" title="男"/>
		<?php } ?> 
		<a href="#" onclick="parent.addTab('查看<?php echo $v['uid'];?>资料','index.php?action=allmember&h=view_info&uid=<?php echo $v['uid'];?>','icon')"><?php echo $v['nickname'];?></a>
	</td>
	<td align="center"><?php if($v['birthyear'])echo date('Y')-$v['birthyear'];else echo "无";?></td>
	<td align="center"><?php echo date('Y-m-d',$v['lastvisit']);?></td>
    <td align="center"><?php echo date('Y-m-d',$v['toshoot_video_time']);?></td>
    <td align="center" style="word-wrap:break-word;word- break:break-all;">会员视频审核</td>

	<td align="center">
      <?php echo $v['toshoot_video_check'] == 2 ? '审核通过' : '未审核';?>
    </td>
	<td align="center"><a href="index.php?action=check&h=video&pass=<?php echo $pass;?>&usersid=<?php echo $v['sid'];?>"><?php echo $v['sid'];?></a></td>
	
	<td align="center">
   	<a href="#" onclick="parent.addTab('审核会员<?php echo $v['uid'];?>的视频','index.php?action=check&h=member_video&uid=<?php echo $v['uid'];?>&pass=<?php echo $pass;?>','icon')">查看</a>	 
    </td>
  </tr>
  <?php } ?>
  <tr>
  	<td colspan="9" align="center"><?php echo $page_links;?>
     	转到第   <input name="pageGo"  id="pageGo" type="text" style="width:35px" value=""/>  页
     	<input type="button"  class="ser_go" value="跳转" onclick="gotoPage()"/></td>
  	</tr>
  </table>
</div>
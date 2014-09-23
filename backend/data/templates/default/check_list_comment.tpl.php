<link href="templates/css/general.css" rel="stylesheet" type="text/css" />
<link href="templates/css/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="templates/js/onmousemove_minutes.js"></script>
<script type="text/javascript">
//跳转
	function gotoPage(){
		var page=$("#pageGo").attr("value");
		window.location="index.php?action=check&h=<?php echo $_GET['h'];?>&type=list&page="+page;
	}
	
	function jump(id,uid){
	       window.location="index.php?action=check&h=<?php echo $_GET['h'];?>&type=show&id="+id+"&uid="+uid;
	}
	
	function commentCheck(id,pass){
		var id  =  id;
		var pass = pass;
		var url  = "index.php?action=check&h=comment&type=submit&id="+id;
		$.post(url,{id:id,pass:pass,ajax:1},function(str){
			if(str == 'ok'){
				alert('审核通过');
				$("#comment_"+id).html("审核通过");
				$("#tr_"+id).hide();
			}else{
				alert('审核不通过');
				$("#comment_"+id).html("审核不通过");
			}
		})
	}
	//全选
function choose_all(){
	if($("#choose_all").attr("checked")){
		$("input[name='id[]']").attr("checked",true);
	}else{
		$("input[name='id[]']").attr("checked",false);
	}
}
//排序
function chang_order(field,order){
	location.href="<?php echo $currenturl2;?>&field="+field+"&order="+order;
}
</script>
<h1>
<span class="action-span1"><a href="###">真爱一生网 管理中心</a> </span><span id="search_id" class="action-span1"> - <?php echo $lei;?></span>
<span class="action-span"><a href="<?php $u=explode('&',$_SERVER['QUERY_STRING']);?>index.php?<?php echo $u[0];?>&<?php echo $u[1];?>">刷新</a></span>
<div style="clear:both"></div>
</h1>
<div class="list-div" style="margin-bottom:10px;padding:5px;">
<form action="<?php echo $currenturl2;?>" method="post">
	<input type="hidden" name="pass" value="<?php echo $pass;?>" />
	会员ID：&nbsp;&nbsp;<input type="text" name="uid" value="<?php if(!empty($uid)) { ?><?php echo $uid;?><?php } ?>" />   <?php if($myservice_idlist && $myservice_idlist!=$GLOBALS['adminid']) { ?>
	
	客服：<select name="usersid" id="usersid" onchange="option_gourl(this.value,<?php echo $pass;?>)" style="height:21px;margin-top:3px;" >
	   <option value='-1' <?php if($usersid==-1) { ?> selected="selected"<?php } ?>> 不限</option>
	   <option value="0" <?php if($usersid == 0) { ?> selected="selected"<?php } ?>>0：</option>
	   <?php foreach((array)$adminUser as $value) {?>
	   <option value="<?php echo $value['uid'];?>" <?php if($usersid == $value['uid']) { ?> selected="selected"<?php } ?>><?php echo $value['uid'];?>：<?php echo $value['username'];?></option>
	   <?php } ?>
	</select>
<?php } ?> &nbsp;&nbsp;<input type="submit" value=" 查 找 " /><span class="but_a"><a href="<?php echo $currenturl2;?>&pass=1" <?php if(isset($_GET['pass']) && $_GET['pass']==1) { ?>style="font-weight:bold;"<?php } ?>>已审核</a><a href="<?php echo $currenturl2;?>&pass=0" <?php if(isset($_GET['pass']) && $_GET['pass']==0) { ?>style="font-weight:bold;"<?php } ?>>未审核</a></span>
</form>
</div>
<div style=" margin-top:10px;">
	
</div>
<div class="list-div" id="listDiv">
<table cellspacing='1' cellpadding='3' id='list-table'>
  <tr>
    <th><input type="checkbox" id="choose_all" value="choose_all" onclick="choose_all()" />被评价会员ID</th>
	<th>昵称</th>
	<th>年龄</th>
	<th>评价会员ID</th>
	<th><a href="javascript:chang_order('dateline','<?php echo $order;?>')"  style="text-decoration:underline;">评价时间</a></th>
	<th>评价内容</th>
	<th>审核状态</th>
	<th><a href="javascript:chang_order('sid','<?php echo $order;?>')"  style="text-decoration:underline;">所属客服ID</a></th>
	<th>操作</th>
  </tr>
  <?php foreach((array)$list as $v) {?>
  <tr onmousemove="this.bgColor='#cfeefe';"  id="tr_<?php echo $v['uid'];?>" onmouseout="this.bgColor='#ffffff';">
    <td align="center">
		<input type="checkbox" value="<?php echo $v['id'];?>" name="id[]" />
		<a href="#" onclick="parent.addTab('查看<?php echo $v['uid'];?>资料','index.php?action=allmember&h=view_info&uid=<?php echo $v['uid'];?>','icon')"><?php echo $v['uid'];?></a>
	</td>
	<td align="center">
		<?php if($v['gender']==1) { ?><img src="templates/images/w.gif" alt="女" title="女"/>
		<?php } else { ?><img src="templates/images/m.gif" alt="男" title="男"/>
		<?php } ?> 
		<a href="#" onclick="parent.addTab('查看<?php echo $v['uid'];?>资料','index.php?action=allmember&h=view_info&uid=<?php echo $v['uid'];?>','icon')"><?php echo $v['nickname'];?></a>
	</td>
	<td align="center"><?php if($v['birthyear'])echo date('Y')-$v['birthyear'];else echo "无";?></td>
	<td align="center"><a href="#" onclick="parent.addTab('查看<?php echo $v['cuid'];?>资料','index.php?action=allmember&h=view_info&uid=<?php echo $v['cuid'];?>','icon')"><?php echo $v['cuid'];?></a></td>
	<td align="center"><?php echo date('Y-m-d',$v['dateline']);?></td>

	<td width="400" align="left" style="word-break:break-all;white-space:normal;word-wrap:break-word;"><?php echo $v['content']?></td>

	<td align="center">
	<?php if($v['is_pass']==2) { ?>审核未通过<?php } elseif (isset($v['is_pass']) && $v['is_pass']==1) { ?>审核通过<?php } else { ?>未审核<?php } ?>
    	       
    </td>
	<td align="center"><a href="<?php echo $currenturl2;?>&usersid=<?php echo $v['sid'];?>"><?php echo $v['sid'];?></a></td>
	
	<td align="center"><?php if(isset($v['is_pass']) && $v['is_pass'] =='0')echo '<a onclick="commentCheck('.$v['id'].',1);return false;" href="#">通过</a> | <a onclick="commentCheck('.$v['id'].',2);return false;" href="#">不通过</a> | ';  if(isset($v['comment']) && $v['comment']==-1)echo "查看";else echo "<a href=\"index.php?action=check&h=" . $_GET['h']."&page=".$page."&type=show&id=".$v['id']."&uid=".$v['uid']."\">查看</a> "; if($v['is_pass']==1) echo "| <a onclick=commentCheck(".$v['id'].",2);return false; href='#'>不通过</a> ";?><span style="color:#F00" id="comment_<?php echo $v['id'];?>"></span></td>
  </tr>
  <?php } ?>
  <tr>
  	<td colspan="9" align="center">
		
		<?php echo multipage($total,$prepage,$page,$currenturl2)?>
     	转到第   <input name="pageGo"  id="pageGo" type="text" style="width:35px" value=""/>  页
     	<input type="button"  class="ser_go" value="跳转" onclick="gotoPage()"/>
		<?php if(isset($_GET['pass']) && $_GET['pass']==0) { ?>
		<span style="margin-left:50px;" ><input type="button"  value="选择通过" onclick="all_pass()" /></span>
		<?php } ?>
		</td>
  	</tr>
  </table>
</div>
<script>
//全部通过
function all_pass(){
	if(!confirm('确认全部通过？')){
		return;
	}
	var uidlist = '';
	var comma = '';
	$("input[name='id[]']:checked").each(function(){
		uidlist += comma+this.value;
		comma = ',';
	})
	if(uidlist==''){
		alert('请选择会员');
		return;
	}
	location.href="index.php?action=check&h=check_content&uidlist="+uidlist;

}
</script>
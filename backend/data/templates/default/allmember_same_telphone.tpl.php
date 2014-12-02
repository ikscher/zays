<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="templates/css/general.css" rel="stylesheet" type="text/css" />
<link href="templates/css/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../public/system/js/sys1.js?v=1"></script>
<script type="text/javascript" src="templates/js/onmousemove_minutes.js"></script>
<script type="text/javascript">
//显示用户信息
function userdetail(number,arrayobj) {
	var arrname = arrayobj;
		for(i=0;i<arrayobj.length;i++) {
			var valueArray  = arrayobj[i].split(",");
			if(valueArray[0] == number) {
				if(valueArray[0] == '-1') {
					document.write("未选择");
				} else {
					document.write(valueArray[1]);
				}	
			}
	}
}


//排序
function chang_order(field,order){
	location.href="<?php echo $currenturl;?>&field="+field+"&order="+order;
}

$(function(){
	$(".csstab tr").mouseover(function(){
		$(this).addClass("over");
	}).mouseout(function(){
		$(this).removeClass("over");	
	})

	$("#page_per").change(function(){
		var page_per = this.value;
		location.href="<?php echo $currenturl;?>"+"&page_per="+page_per;
	})
})
</script>
<style>
tr.over td {
	background:#cfeefe;
} 

</style>
</head>
<body>
<h1>
<span class="action-span"><a href="index.php?action=allmember&h=<?php echo $_GET['h'];?>&telphone=<?php echo $telphone;?>">刷新</a></span>
<span class="action-span1"><a href="###">真爱一生网 管理中心</a> </span><span id="search_id" class="action-span1"> - <?php if(isset($title)) { ?><?php echo $title;?><?php } ?> </span>
<div style="clear:both"></div>
</h1>
<div>
   <form action="index.php?action=allmember&h=same_telphone" method="POST" >
     电话号码：<input type="text" name="telphone" value=""  /> 
	          <input type="submit" name="submit" value="查询" />
    </form>			  
</div>
<div class="list-div" id="listDiv" > <!--onmouseout="hiddend();" -->
<table cellspacing='1' cellpadding='3' id='list-table' class ="csstab">
  <tr>
    <th>ID</th>
    <th>用户名</th>
    <th>
		年龄
	</th>
    <th>等级</th>
    <th>
		照片
	</th>
    <th>收入</th>
    <th>工作地</th>
	<th>类</th>
    <th>分配时间</th>
    <th>注册时间</th>
	<th>会员类型</th>
    <th>登录</th>
	<th>工号</th>
	<th>客服</th>
    <th>锁定</th>
	<th>是否通过手机验证</th>
	<th>操作</th>
  </tr>
  <?php foreach((array)$member_list as $member) {?>
   <tr>
    <td align="center"><a href="#" class="userinfo" onclick="parent.addTab('查看<?php echo $member['uid'];?>资料','index.php?action=allmember&h=view_info&uid=<?php echo $member['uid'];?>','icon')"><?php echo $member['uid'];?></a></td>
    <td align="left" style="text-indent:30px;"  onmouseover="showinfo(event,<?php echo $member['uid'];?>);"> 
		<?php if($member['gender']==1) { ?><img src="templates/images/w.gif" alt="女" title="女"/>
		<?php } else { ?><img src="templates/images/m.gif" alt="男" title="男"/>
		<?php } ?>
		<a href="#" onclick="parent.addTab('查看<?php echo $member['uid'];?>资料','index.php?action=allmember&h=view_info&uid=<?php echo $member['uid'];?>','icon')"><?php echo $member['username'];?></a>
	</td>
    <td align="center"><?php echo date("Y")-$member['birthyear'];?></td>
    <td align="center"><?php echo $GLOBALS['member_level'][$member['s_cid']];?></td>
    <td align="center"><?php echo isset($member['mainimg']) && $member['mainimg']?"有":"无";?></td>
    <td align="center"><script>userdetail("<?php echo $member['salary'];?>",salary1);</script></td>
    <td align="center"><script>userdetail("<?php echo $member['province'];?>",provice);userdetail("<?php echo $member['city'];?>",city);</script></td>
	<td align="center"><?php if($member['effect_grade']>0) echo $member['effect_grade']-1;?></td>
    <td align="center"><?php if($member['allotdate']) echo date("Y-m-d H:i",$member['allotdate']);?></td>
    <td align="center"><?php echo date("Y-m-d H:i",$member['regdate']);?></td>
	<td align="center">
		<?php if($member['usertype']==1)echo "本站注册";if($member['usertype']==2)echo "外站加入";if($member['usertype']==3)echo "全权会员";?>
	</td>
    <td align="center"><?php echo $member['login_meb'];?></td>
	
    <td align="center">
    	
    	<?php if($member['sid']!=''&&$member['sid']!=0) echo $member['sid'].'号';else echo "暂无";?>
    </td>
	<td align="center"><?php echo $GLOBALS['kefu_arr'][$member['sid']]?></td>
    <td align="center"><?php if($member['is_lock']==1) echo '否';else echo '<font color="#FF0000">是</font>';?></td>
	<td align="center">
		 <?php if($member['telphone']) { ?>
			通过
		 <?php } else { ?>
			未通过
		 <?php } ?>
	</td>
	<td align="center"><a href="index.php?action=allmember&h=del_same_telphone&uid=<?php echo $member['uid'];?>&telphone=<?php echo $telphone;?>" onclick="return confirm('此删除将不可恢复，确定删除吗？')">删除</a></td>
  </tr>
  <?php } ?>
 </table>
 
<div style="padding:5px;background:#fff">
	
</div>

</div>

</body>
</html>
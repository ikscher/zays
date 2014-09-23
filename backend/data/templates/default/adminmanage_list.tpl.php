<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<link href="templates/css/general.css" rel="stylesheet" type="text/css" />
<link href="templates/css/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="templates/js/onmousemove_minutes.js"></script>
<script type="text/javascript">
//设置红娘币参数
function change(uid){
	//$.get()
}
function bulid_input(uid){
	var input=new Array('interval','allot','amount','money');
	for(var i=0;i<4;i++){
		var commid=input[i]+'_'+uid;
		var val=$('#td_'+commid).html();
		var html='<input type="text" name="'+input[i]+'['+uid+']" value="'+val+'" id="'+commid+'">';
		$('#td_'+commid).html(html);
	}
}
function remove_input(uid){
	var input=new Array('interval','allot','amount','money');
	for(var i=0;i<4;i++){
		var commid=input[i]+'_'+uid;
		var val=$('#'+commid).val();
		$('#td_'+commid).html(val);
	}
}
function cheackall(){
	alert($('#change_group_'+uid).attr('checked'));
}
function change_input(uid){
	if($('#change_group_'+uid).attr('checked')){
		bulid_input(uid);
		$("#ok").removeAttr('disabled');
	}else{
		remove_input(uid);
	}
}
</script>
</head>
<body>
<h1>
<span class="action-span"><a href="index.php?action=system_adminmanage&h=addgroup" onclick="parent.addTab('添加组','index.php?action=system_adminmanage&h=addgroup','icon');return false;">添加组</a></span>
<span class="action-span1"><a href="###">真爱一生网 管理中心</a> </span><span id="search_id" class="action-span1"> - 组管理列表 </span>
<span class="action-span"><a href="<?php $u=explode('&',$_SERVER['QUERY_STRING']);?>index.php?<?php echo $u[0];?>&<?php echo $u[1];?>">刷新</a></span>
<div style="clear:both"></div>
</h1>
<div class="list-div" id="listDiv">
<form action="index.php?action=system_adminmanage&h=change" method="post" id="formChange" name="formChange">
<table cellspacing='1' cellpadding='3' id='list-table'>
  <tr>
    <th>组名</th>
    <!-- <th>组长姓名</th> -->
    <th>组成员ID</th>
    <th>所属队</th>
    <th>组说明</th>
    <th>初始化间隔时间(月)</th>
    <th>每日分配次数</th>
    <th>每次分配金额</th>
    <th>初始化金额</th>
    <th>操作</th>
  </tr>
  <?php foreach((array)$manage_list as $group) {?>
  <tr id="group_<?php echo $group['id'];?>">
    <td align="center"><input type="checkbox" name="group[]" value="<?php echo $group['id'];?>" id="change_group_<?php echo $group['id'];?>" onclick="change_input(<?php echo $group['id'];?>)"  ><?php echo $group['manage_name'];?></td>
    <!-- <td align="center"><?php echo $group['leader_username'];?></td> -->
    <td align="center"><?php echo wordwrap($group['manage_list'],90,"<br />",TRUE);?></td>
    <td align="center"><?php echo $group['be_manage_id'];?></td>
    <td align="center"><?php echo $group['manage_desc'];?></td>
    <td align="center" id="td_interval_<?php echo $group['id'];?>"><?php echo isset($group['interval'])?$group['interval']:'';?></td>
    <td align="center" id="td_allot_<?php echo $group['id'];?>"><?php echo isset($group['allot'])?$group['allot']:'';?></td>
    <td align="center" id="td_amount_<?php echo $group['id'];?>"><?php echo isset($group['amount'])?$group['amount']:'';?></td>
    <td align="center" id="td_money_<?php echo $group['id'];?>"><?php  echo isset($group['money'])?$group['money']:'';?></td>
    <td align="center">
    	<a href="index.php?action=system_adminmanage&h=groupmember&id=<?php echo $group['id'];?>" onclick="parent.addTab('管理组<?php echo $group['manage_name'];?>成员','index.php?action=system_adminmanage&h=groupmember&id=<?php echo $group['id'];?>','icon');return false;">管理组成员</a>&nbsp;&nbsp;
    	<a href="index.php?action=system_adminmanage&h=delgroup&id=<?php echo $group['id'];?>" onclick="return confirm('确定删除吗？');">删除组</a>&nbsp;&nbsp;
    	<a href="index.php?action=system_adminmanage&h=editgroup&id=<?php echo $group['id'];?>" onclick="parent.addTab('编辑<?php echo $group['manage_name'];?>组','index.php?action=system_adminmanage&h=editgroup&id=<?php echo $group['id'];?>','icon');return false;">编辑组</a>
    </td>
  </tr>
  <?php } ?>
  </table>
  </form>
<table cellpadding="4" cellspacing="0">
    <tr>
      <td align="right"> <?php echo multi($total,$limit,$page,$currenturl)?></td>
      <td><input type="button" value="保存" id="ok" disabled="disabled" onclick="document.formChange.submit()"></td>
    </tr>
  </table>
</div>
</body>
</html>
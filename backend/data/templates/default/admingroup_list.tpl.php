<link href="templates/css/general.css" rel="stylesheet" type="text/css" />
<link href="templates/css/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="templates/js/onmousemove_minutes.js"></script>
<script>
function remove(groupid){
    if(confirm('您确定要删除该分类吗')){
    }else return false;
    var url="./ajax.php";
    $.post(url,{n:"delGroup",groupid:groupid},function(str){
        if(str=='ok'){
            alert("删除成功");
            $("#group_"+groupid).empty();
            $("#group_"+groupid).remove();
        }
    });
}
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
<h1>
<span class="action-span"><a href="index.php?action=system_admingroup&h=add" onclick="parent.addTab('添加权限','index.php?action=system_admingroup&h=add','icon');return false;">添加权限</a></span>
<span class="action-span1"><a href="###">真爱一生网 管理中心</a> </span><span id="search_id" class="action-span1"> - 管理员分类列表 </span>
<span class="action-span"><a href="<?php $u=explode('&',$_SERVER['QUERY_STRING']);?>index.php?<?php echo $u[0];?>&<?php echo $u[1];?>">刷新</a></span>
<div style="clear:both"></div>
</h1>
<div class="list-div" id="listDiv">
<form action="index.php?action=system_admingroup&h=change" method="post" id="formChange" name="formChange">
<table cellspacing='1' cellpadding='3' id='list-table'>
  <tr>
    <th>组ID</th>
    <th>分类名称</th>
    <th>分类描述</th>
    <th>初始化间隔时间(月)</th>
    <th>每日分配次数</th>
    <th>每次分配金额</th>
    <th>初始化金额</th>
    <th>操作</th>
  </tr>
  <?php foreach((array)$group_list as $group) {?>
  <tr id="group_<?php echo $group['groupid'];?>">
    <td class="first-cell" ><input type="checkbox" name="group[]" value="<?php echo $group['groupid'];?>" id="change_group_<?php echo $group['groupid'];?>" onclick="change_input(<?php echo $group['groupid'];?>)"  ><?php echo $group['groupid'];?></td>
    <td align="left"><?php echo $group['groupname'];?></td>
    <td align="center"><?php echo $group['groupdesc'];?></td>
    <td align="center" id="td_interval_<?php echo $group['groupid'];?>"><?php echo isset($group['interval'])?$group['interval']:'';?></td>
    <td align="center" id="td_allot_<?php echo $group['groupid'];?>"><?php echo isset($group['allot'])?$group['allot']:'';?></td>
    <td align="center" id="td_amount_<?php echo $group['groupid'];?>"><?php echo isset($group['amount'])?$group['amount']:'';?></td>
    <td align="center" id="td_money_<?php echo $group['groupid'];?>"><?php  echo isset($group['money'])?$group['money']:'';?></td>
    <td align="center">
    	<a href="index.php?action=system_admingroup&h=edit&groupid=<?php echo $group['groupid'];?>" title="编辑" onclick="parent.addTab('编辑或修改-<?php echo $group['groupname'];?>-权限','index.php?action=system_admingroup&h=edit&groupid=<?php echo $group['groupid'];?>','icon');return false;"> 编辑或修改权限</a>&nbsp;&nbsp;
    	<a href="javascript:;" onclick="remove(<?php echo $group['groupid'];?>)" title="移除">移除</a>
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
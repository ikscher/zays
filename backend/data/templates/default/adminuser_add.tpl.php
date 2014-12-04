<link href="templates/css/general.css" rel="stylesheet" type="text/css" />
<link href="templates/css/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="templates/js/onmousemove_minutes.js"></script>
<script>
function checkform(){
    var username=$("#username").val();
    var name=$("#name").val();
    var groupid=$("#groupid").val();
    var password=$("#password").val();
    var pwd_confirm=$("#pwd_confirm").val();
    var aid=$("#aid").val();
	var sale_commission = $("#sale_commission").val();
    if(username==''){
        alert('请填写管理员登录名');
        return false;
    }
    if(name==''){
        alert('请填写管理员姓名');
        return false;
    }
    if(groupid==''){
        alert('请选择管理员分类角色');
        return false;
    }
	var re=/^[0-9]+.?[0-9]*$/;
	if(!re.test(sale_commission) || sale_commission>30){
		alert('提成比率必须是正整数，且不得大于30');
		return false;
	}
    if(password==''&&aid==''){
        alert('请填写管理员密码');
        return false;
    }
    if(pwd_confirm!=password){
        alert('密码与确认密码不相同');
        $("#password").attr('value','');
        $("#pwd_confirm").attr('value','');
        return false;
    }
}
</script>
<h1>
<span class="action-span1"><a href="###">真爱一生网 管理中心</a> </span><span id="search_id" class="action-span1"> - 添加管理员 </span>
<span class="action-span"><a href="<?php $u=explode('&',$_SERVER['QUERY_STRING']);?>index.php?<?php echo $u[0];?>&<?php echo $u[1];?>&aid=<?php if(isset($userid)) { ?><?php echo $userid;?><?php } ?>">刷新</a></span>
<div style="clear:both"></div>
</h1>

<div class="main-div">
<form name="theForm" method="post" action="index.php?action=system_adminuser&h=add" onsubmit="return checkform();">
<input type="hidden" name="ispost" value="1"/>
<input type="hidden" name="aid" id="aid" value="<?php if(isset($adminuser['uid'])) { ?><?php echo $adminuser['uid'];?><?php } ?>"/>
<table width="100%">
  <tr>
    <td class="label">管理员编号：</td>
    <td>
      <input name="usercode" type="text"  id="usercode" value="<?php if(isset($adminuser)) { ?><?php echo $adminuser['usercode'];?><?php } ?>" size="10" maxlength="10" />
      <span class="require-field">*</span>
    </td>
  </tr>
  <tr>
    <td class="label">管理员登录名：</td>
    <td>
      <input name="username" type="text" onblur="checkusername()" id="username" value="<?php if(isset($adminuser)) { ?><?php echo $adminuser['username'];?><?php } ?>" size="34" maxlength="20" />
      <span class="require-field">*</span>
    </td>
  </tr>
  <tr>
    <td class="label">管理员姓名：</td>
    <td>
      <input type="text" name="name" id="name" maxlength="20" value="<?php if(isset($adminuser['name'])) { ?><?php echo $adminuser['name'];?><?php } ?>" size="20"/><span class="require-field">*</span>
    </td>
  </tr>
  <tr>
    <td class="label">管理员分类角色：</td>
    <td>
      <select name="groupid" id="groupid">
      <option value="">请选择...</option>
	      <?php foreach((array)$group_list as $group) {?>
	     <option value="<?php echo $group['groupid'];?>" <?php if(isset($adminuser['groupid']) && $adminuser['groupid']==$group['groupid']) echo "selected='selected';";?>><?php echo $group['groupname'];?></option>
	      <?php } ?>
      </select><span class="require-field">*</span>
    </td>
  </tr>
  <tr>
  	<td class="label">是否自动分配客服：</td>
  	<td>
  		<select name="is_allot">
  		<option value="1">是</option>
  		<option value="0">否</option>
  		</select>
  	</td>
  </tr>
  <tr>
	<td class="label">销售提成比率：</td>
	<td>
		<input type="text" name="sale_commission" id="sale_commission" value="<?php if(!empty($adminuser['sale_commission'])) echo $adminuser['sale_commission'];else echo 0;?>" size="4"/>
		<span style="color:#999;">请直接输入数字，如提成比率为20%,则此处输入20即可</span>
	</td>
  </tr>
  <tr>
  	<td class="label">CallCenterID：</td>
  	<td><input name="ccid" type="text" id="ccid" value="<?php if(isset($adminuser['ccid'])) { ?><?php echo $adminuser['ccid'];?><?php } ?>" size="4" /></td>
  	</tr>
  <tr>
  	<td class="label">用户备注：</td>
  	<td><textarea style="width: 200px;" id="desc" name="desc"><?php if(isset($adminuser['userdesc'])) { ?><?php echo $adminuser['userdesc'];?><?php } ?></textarea></td>
  </tr>
   <tr>
    <td class="label">密  码：</td>
    <td>
      <input name="password" type="password" id="password" value="" /> <?php if(isset($isedit) && $isedit) { ?><span style="padding-left:10px; color:#999">为空则保持原密码不变</span><?php } else { ?><span class="require-field">*</span><?php } ?>
    </td>
  </tr>
  <tr>
    <td class="label">确认密码：</td>
    <td>
      <input type="password" name="pwd_confirm" id="pwd_confirm" value="" /> <?php if(isset($isedit) && $isedit) { ?><?php } else { ?><span class="require-field">*</span><?php } ?>
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center" style="padding-left:400px;">
	<input type="hidden" name="isedit" value="<?php if(isset($isedit)) { ?><?php echo $isedit;?><?php } ?>" />
      <input type="submit" value=" 确定 " class="button" />&nbsp;&nbsp;&nbsp;
      <input type="reset" value=" 重置 " class="button" />
    </td>
  </tr>
</table>
</form>
</div>
<link href="templates/css/general.css" rel="stylesheet" type="text/css" />
<link href="templates/css/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="templates/js/onmousemove_minutes.js"></script>
<script>
function getnav(){
    var nav = $("#navcode option:selected").html();
    $("#navname").val(nav);
}
function checkform(){
    var actiondesc=$("#actiondesc").val();
    var actioncode=$("#actioncode").val();
    var navcode=$("#navcode").val();
    if(actiondesc==''){
        alert('请填写操作名称');
        return false;
    }
    if(actioncode==''){
        alert('请填写操作标识符');
        return false;
    }
    if(navcode==''){
        alert('请选择所属导航栏');
        return false;
    }
}
</script>
<h1>
<span class="action-span1"><a href="###">真爱一生网 管理中心</a> </span><span id="search_id" class="action-span1"> - 添加后台操作 </span>
<span class="action-span"><a href="<?php $u=explode('&',$_SERVER['QUERY_STRING']);?>index.php?<?php echo $u[0];?>&<?php echo $u[1];?>">刷新</a></span>
<div style="clear:both"></div>
</h1>
<div class="main-div">
<form name="theForm" method="post" action="index.php?action=system_adminaction&h=add" onsubmit="return checkform();">
<input type="hidden" name="ispost" value="1"/>
<input type="hidden" name="aid" value="<?php if(isset($adminaction['actionid'])) { ?><?php echo $adminaction['actionid'];?><?php } ?>"/>
<table width="100%">
  <tr>
    <td class="label">操作名称:</td>
    <td>
      <input type="text" name="actiondesc" id="actiondesc" maxlength="20" value="<?php if(isset($adminaction['username'])) { ?><?php echo $adminaction['username'];?><?php } ?>" size="34"/><span class="require-field">*</span></td>
  </tr>
  <tr>
    <td class="label">操作标识符:</td>
    <td><input type="text" name="actioncode" id="actioncode" />&nbsp;&nbsp;&nbsp;标识符（由action+'_'+h组成）<span class="require-field">*</span></td>
  </tr>
   <tr>
    <td class="label">所属导航栏:</td>
    <td>
      <select name="navcode" id="navcode" onchange="getnav();">
      <option value="">请选择...</option>
      <?php foreach((array)$menu_nav_arr as $key=>$nav) {?>
      <option value="<?php echo $key;?>"><?php echo $nav;?></option>
      <?php }?>
      </select>&nbsp;&nbsp;&nbsp;<input type="text" name="navname" id="navname" value="" /><span class="require-field">*</span></td>
  </tr>
        <tr>
    <td colspan="2" align="center" style="padding-left:400px;">
      <input type="submit" value=" 确定 " class="button" />&nbsp;&nbsp;&nbsp;
      <input type="reset" value=" 重置 " class="button" />
  </tr>
</table>
</form>
</div>
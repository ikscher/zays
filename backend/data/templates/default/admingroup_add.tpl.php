<link href="templates/css/general.css" rel="stylesheet" type="text/css" />
<link href="templates/css/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="templates/js/onmousemove_minutes.js"></script>
<script>
function check(type){
    var check = $("#"+type+"_check").attr('checked');
    if(check){
        $("#"+type+" :checkbox").attr('checked','checked');
    }else{
        $("#"+type+" :checkbox").attr('checked','');
    }
}
function checkAll(){
    var check = $("#checkall").attr('checked');
    if(check){
        $("#list-table :checkbox").attr('checked','checked');
    }else{
        $("#list-table :checkbox").attr('checked','');
    }
}
function checkform(){
    var groupname = $("#groupname").val();
    var groupdesc = $("#groupdesc").val();
    var geshu = $("#list-table :checked").length;
    //var type = $("#type").val();
    if(groupname==''){
        alert('请填写分类名称');
        return false;
    }
	/*
    if(type == ''){
		alert('请选择管理员分类');
		return false;
    }
	*/
    if(groupdesc==''){
        alert('请填写分类介绍');
        return false;
    }
    if(geshu==0){
        alert('请选择可进行的操作');
        return false;
    }
}
</script>
<h1>
<span class="action-span1"><a href="###">真爱一生网 管理中心</a> </span><span id="search_id" class="action-span1"> - 添加管理员分类  </span>
<span class="action-span"><a href="<?php $u=explode('&',$_SERVER['QUERY_STRING']);?>index.php?<?php echo $u[0];?>&<?php echo $u[1];?>">刷新</a></span>
<div style="clear:both"></div>
</h1>
<form method="POST" action="index.php?action=system_admingroup&h=add" name="theFrom" onsubmit="return checkform();">
<input name="ispost" type="hidden" value="1" />
<div class="list-div">
<table cellspacing='1' id="list-table">
<tr>
   <td width="18%" valign="top" class="first-cell">
       	管理员分类名称 ：
   </td>
   <td>
       <input type="text" name="groupname" id="groupname" value=""/><span class="require-field">*</span>
   </td>
</tr>


<tr>
 	<td width="18%" valign="top" class="first-cell">
     		 管理员分类描述 ：
  	</td>
  	<td>
      	<textarea name="groupdesc" id="groupdesc" style="width:400px;"></textarea><span class="require-field">*</span>
  	</td>
</tr>



<?php foreach((array)$menu_nav_arr as $key=>$navname) {?>
 	<?php $listdata = 'action_list_' . $key;?>
 	<?php $actiondata = $$listdata;?>
 	<?php if(!empty($actiondata)) { ?>
	<tr>
   		<td width="18%" valign="top" class="first-cell">
    		<input name="adminnav[]" type="checkbox" value="<?php echo $key;?>" id="<?php echo $key;?>_action_check" onclick="check('<?php echo $key;?>_action');" class="checkbox"><?php echo $navname;?></td>
      	<td id="<?php echo $key;?>_action">
  			<?php foreach((array)$actiondata as $admin_action) {?>
        	<div style="width:200px;float:left;">
    		<label for="<?php echo $admin_action['actioncode'];?>"><input type="checkbox" name="action_code[]" value="<?php echo $admin_action['actioncode'];?>" id="<?php echo $admin_action['actioncode'];?>" class="checkbox"/>
    		<?php echo $admin_action['actiondesc'];?></label>
   			</div>
  		<?php }?>
    	</td>
	</tr>
	<?php } ?>
<?php } ?>


<tr>
  <td align="center" colspan="2" style="padding-left:400px;">
    <input type="checkbox" name="checkall" id="checkall" value="checkbox" onclick="checkAll();" class="checkbox" />全选      &nbsp;&nbsp;&nbsp;&nbsp;
    <input type="submit"   name="Submit"   value=" 保存 " class="button" />
  </td>
</tr>
</table>
</div>
</form>
<link href="templates/css/general.css" rel="stylesheet" type="text/css" />
<link href="templates/css/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="templates/js/onmousemove_minutes.js"></script>
<script>
function checkform(){
    var manage_name = $("#manage_name").val();
    var manage_desc = $("#manage_desc").val();
    if(manage_name==''){
        alert('请填写组名称');
        return false;
    }
    if(manage_desc==''){
        alert('请填写组描述');
        return false;
    }
    return true;
}

function search_kefu(){
	var keyword = $("#keyword").val();
	if( keyword=="" ){
		alert("搜索内容不能为空！");
		return;
	}
	var url = "./ajax.php";
    $.getJSON(url,{n:"searchKefu",keyword:keyword},function(data){
     	var userStr = data;
		var len = data.length;
		$("#user").empty();
		var optionStr = "<option value =''>搜索出 "+len+" 条结果 请选择</option>";
		for(i=0;i<len;i++){
			optionStr += "<option value='"+data[i]['uid']+"'>"+data[i]['name']+"</option>";
		}
		$("#user").append(optionStr);
    });
}

function group_add_member(){
	if($("#user").val() == ''){
		alert("请选择一个客服");
		return;
	}
	var url="./ajax.php";
	var uid = $("#user").val();
	
	$.get(url,{n:"judgeMember",uid:uid},function(data){
		if(data==1){
			alert("该客服已经在其它组中不能添加到此组！");
		}else{
			if($("#ispost").val() == 0){
				$("#ispost").val(1);
				$("#add").css("display","block");
			}
	
			var name = $("#user option:selected").text();
			$("#user_list").append('<input type="checkbox" name="userlist[]" value="'+uid+'" checked="checked" />&nbsp;'+name);
		}											 
	});
}
</script>
<h1>
<span class="action-span1"><a href="###">真爱一生网 管理中心</a> </span><span id="search_id" class="action-span1"> - 添加组  </span>
<span class="action-span"><a href="<?php $u=explode('&',$_SERVER['QUERY_STRING']);?>index.php?<?php echo $u[0];?>&<?php echo $u[1];?>">刷新</a></span>
<div style="clear:both"></div>
</h1>
<form method="POST" action="index.php?action=system_adminmanage&h=addgroup" name="theFrom" onsubmit="return checkform();">
<input name="ispost" type="hidden" value="1" />
<div class="list-div">
<table cellspacing='1' id="list-table">
	<tr>
	   <td width="18%" valign="top" class="first-cell">
	       	组名称 ：
	   </td>
	   <td>
	       <input type="text" name="manage_name" id="manage_name" value=""/><span class="require-field">*</span>
	   </td>
	</tr>
	<tr>
	 	<td width="18%" valign="top" class="first-cell">
	     	组描述 ：
	  	</td>
	  	<td>
	      	<textarea name="manage_desc" id="manage_desc" style="width:400px;"></textarea><span class="require-field">*</span>
	  	</td>
	</tr>
	<tr>
	 	<td width="18%" valign="top" class="first-cell">
	     	组类别 ：
	  	</td>
	  	<td>
	      	<select style="width:170px;" name="manage_type">
			<option value="1">售前组</option>
			<option value="2">售后组</option>
			</select><span class="require-field">*</span>
	  	</td>
	</tr>
	<tr>
		<td width="18%" valign="top" class="first-cell">
	     	添加组成员 ：
	  	</td>
	  	<td>
	    按客服ID或客服用户名
		<input type="text" id="keyword" name="keyword" value="">
		<input type="button" name="search" value=" 搜索 " onclick="search_kefu();">
		<select style="width:170px;" id="user" name="user">
			<option value="">请选择</option>
		</select>
		<input type="button" onclick="group_add_member();" name="add_member" value="添加成员">
		<div id="user_list"></div>
	  	</td>
	</tr>
	<tr>
		<td colspan="2" align="center"><input type="submit" class="button" value="确定" /></td>
	</tr>
</table>

</div>
</form>
<link href="templates/css/general.css" rel="stylesheet" type="text/css" />
<link href="templates/css/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="templates/js/onmousemove_minutes.js"></script>
<script type="text/javascript">
function checkForm(){
	var content = $("#content").val();
	if( !content){
		alert("请将信息填写完整");
		return false;
	}
}
function checklen(){
	var content = $("#content").val();
	var len = content.length;
	$("#tip").html("内容长度："+len+"字");
}
</script>
<h1> <span class="action-span1"><a href="###">真爱一生网 管理中心</a> </span><span id="search_id" class="action-span1"> - <?php if($isedit) { ?>更改<?php } else { ?>添加<?php } ?>预设短信</span>
<span class="action-span"><a href="<?php $u=explode('&',$_SERVER['QUERY_STRING']);?>index.php?<?php echo $u[0];?>&<?php echo $u[1];?>">刷新</a></span>
	<div style="clear:both"></div>
</h1>
<form action="index.php?action=other&h=smspre_<?php if($isedit) { ?>edit<?php } else { ?>add<?php } ?>" method="post" onsubmit="return checkForm();">
<div class="list-div">
		<table width="100%">
			<tr>
				<td width="100" height="25" align="right" valign="top">短信内容：</td>
				<td align="left"><textarea name="content" id="content" style="width:500px; height:80px;" onkeyup="checklen()"><?php echo $sms['content'];?></textarea></td><td><span>添加/修改说明：<br>1.请用[adminid]代替客服id号，如：您好，我是红娘网[adminid]号客服...</span><br>
<span >2. [smstitle][/smstitle]短信什么时候使用的标签, 例如：[smstitle]感觉意向不错但是没有登录[/smstitle]红娘网红娘提醒您：您已经好几天未登陆了...</span></td>
			</tr>
			<tr>
				<td width="100" height="25" align="right">定义对象：</td>
				<td align="left"><select name="target" id="target">
					<option value="<?php echo $GLOBALS['adminid'];?>" <?php if($sms['targer'] ==$GLOBALS['adminid']) { ?>selected="selected"<?php } ?>>仅自己可用</option>
					<option value="0" <?php if($sms['targer'] ==0 || $sms['targer']=="") { ?>selected="selected"<?php } ?>>所有客服可用</option>
				</select><span id="tip" style="margin-left:10px; color:#999"></span></td><td></td>
			</tr>
		</table>
</div>
<p/>
<input name="ispost" type="hidden" id="ispost" value="1" />
<input name="id" type="hidden" id="id" value="<?php echo $id;?>" />
<input name="提交" type="submit" value=" <?php if($isedit) { ?>更 改<?php } else { ?>添 加<?php } ?> " />
</form>
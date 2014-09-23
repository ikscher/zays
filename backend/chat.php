<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title id='t'>客服模拟会员聊天</title>
<style type="text/css">
body {
	background-color:#D7E4F4;
	margin:0px;
	padding:0px;
	font-size:10pt;
}
</style>
<script type="text/javascript">
function checkID(obj){
	if(parseInt(obj.value)>0) return true;
	var msg = obj.id=='toid' ? '接收消息会员ID' : '发送消息会员ID'; 
	alert(msg);
	obj.focus();
	return false;
}

function sendMes(){
	var to = document.getElementById('toid');
	var from = document.getElementById('fromid');
	if(!checkID(to)) return false;
	if(!checkID(from)) return false;
	location.href = "../index.php?n=chat&h=inline_chat&fid="+from.value+"&tid="+to.value+"&sid=<?php echo $GLOBALS['adminid']; ?>&isback=1";
}
</script>
</head>
<body>
<div style="border:1px solid #1C3F80;">
	<table width="100%" height="100%" border="0" cellspacing="1" cellpadding="1">
		<th height="23" align="left" style="background-color:#3E679A; color:#FFF">
		
		</th>
		<tr>
			<td><div align="center">
					<img src="templates/images/top_logo.png" style="position:relative;top:3px;" />
					<span id="uinfo">
						<input id="toid" type="text" style="width:90px;height:13px;" value="接收消息会员ID" onfocus="this.value=''" />
						
					</span>
			
			</div>
			<div align="center">
				<img src="templates/images/top_logo.gif" style="border:none;background:#fff;height:20px;width:20px;position:relative;top:3px;"/>
				<span id="kinfo"><input type="text" id="fromid" style="width:90px;height:13px;" value="发送消息会员ID" onfocus="this.value=''"/></span>
					
			</div></td>
		</tr>
		<tr><td style="font-size:16px;color:red;font-weight:900;text-align:center;">注意！只有高级会员才能主动发起聊天！</td></tr>
		<tr>
		  <td align="center"><input type="button" value="启动聊天" onclick="sendMes()" /></td>
		</tr>
	</table>
</div>
</body>
</html>

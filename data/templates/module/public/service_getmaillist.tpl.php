<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>我的邮件——我的真爱一生——真爱一生网</title>
<link href="module/service/templates/default/service.css" rel="stylesheet" type="text/css" />
<?php include MooTemplate('system/js_css','public'); ?>
</head>
<script>
function textCounter(field,countfieldId,leavingsfieldId,maxlimit) {
	var countfield = document.getElementById(countfieldId);
	var leavingsfield = document.getElementById(leavingsfieldId);
	if (field.value.length > maxlimit) // if too long...trim it!
	{
		field.value = field.value.substring(0, maxlimit);
		alert(" 限" + maxlimit + "字内！");
	} else { 
		leavingsfield.innerHTML=maxlimit - field.value.length;
		countfield.innerHTML=field.value.length;
	}
}

function text_title(maxlimit){
	var titles = $('#s_title').val();
	if(titles.length > maxlimit){
		$('#s_title').val(titles.substring(0,maxlimit));
		alert(' 限'+maxlimit+"字内！");
	}
}

function check_form(){
	var title = $('#s_title').val();
	var content = $("#s_content").val();
	if($.trim(title) == ''){
		alert('邮件标题不能为空');
		return false;
	}
	
	if($.trim(content) == ''){
		alert('邮件内容不能为空');
		return false;
	}
	return true;
}
</script>
<body>
<div class="main">
	<?php include MooTemplate('system/header','public'); ?>
	<div class="content">
	<p class="c-title"><span class="f-000"><a href="index.php">真爱一生首页</a>&nbsp;&gt;&gt;&nbsp;<a href="index.php?n=service">我的真爱一生</a>&nbsp;&gt;&gt;&nbsp;我发的邮件</span>
		<div class="loaction_right">
				<a href="index.php?n=invite" target="_blank">邀请好友</a>
		</div>
	</p>
	<div class="content-lift">
		<?php include MooTemplate('public/service_left','module'); ?>
		
	</div><!--content-lift end-->
	<!--=====左边结束===-->
	<div class="content-right">
		<div class="c-right-content">
			<div class="r-center-tilte">
			<a href="index.php?n=service" class="r-title-black">&lt;&lt;返回我的真爱一生</a>
			<span class="right-title f-ed0a91">发送邮件</span>			
			</div>
			<div class="r-center-ccontent" style="padding:30px 0 60px; width:753px;">
			<div class="service-title">
				<ul>
					<li ><a href="#" id="two5" onclick="setTab('two',5,5)" ><span>所有来信</span></a></li>
					<li><a href="#" id="two2"onclick="setTab('two',2,5)" ><span>会员来信</span></a></li>
					<li><a href="#" id="two3"onclick="setTab('two',3,5)" ><span>真爱一生来信</span></a></li>
					<li><a href="#" id="two4"onclick="setTab('two',4,5)" class="onthis"><span>我发的邮件</span></a></li>				</ul>
			<div class="clear"></div>
			</div>			
			<!--邮件头部-->
			<div class="email-title">
				<p align="right" style="padding-right:30px; line-height:74px;"><p align="right" style="padding-right:30px; line-height:74px;"><a href="index.php?n=service&h=message" class="f-ed0a91">返回列表</a></p>
			</div>
			<!--邮件头部结束-->
			<!--邮件内容-->
			<div class="email-box">
            	<form id="form1" name="form1" method="post" action="?n=service&h=message&t=send&status=s&sendtoid=<?php echo $contentid;?>" onsubmit="return check_form();">
				<table width="100%" border="0" cellspacing="0" cellpadding="0" class="msg-text-box">
					<tr>
						<td width="7%" height="30" align="right">收件人：</td>
						<td width="93%"><?php echo $send_to_name;?>
                        <input name="s_id" type="hidden" id="s_id" value="<?php echo $s_id;?>" />
                    	<input name="m_id" type="hidden" id="m_id" value="<?php echo $user_arr['uid'];?>" />
                     	<input name="s_cid" type="hidden" id="s_cid" value="<?php echo $user_arr['s_cid'];?>" />
                        </td>
                        
					</tr>
					<tr>
						<td height="30" align="right">标&nbsp;&nbsp;题：</td>
						<td><input name="s_title" type="text" id="s_title" style="width:306px;height:18px;" onkeyup="javascript:text_title(20);"/>
							不要超过20个字</td>
					</tr>
					<tr>
						<td height="30" align="right" valign="top">内&nbsp;&nbsp;容：</td>
						<td><p>
							<textarea name="s_content" id="s_content" style="width:572px; height:304px;" onfocus="textCounter(this,'counter2','leavings2',1500);" onblur="textCounter(this,'counter2','leavings2',1500);" onkeyup="textCounter(this,'counter2','leavings2',1500);"></textarea>
						</p>
							<p class="f-aeaeae">（您已输入<font id="counter2">0</font>字，您还可以输入<font id="leavings2">200</font>字）</p></td>
					</tr>
					<tr>
						<td height="25">&nbsp;</td>
						<td>
							<input name="" type="submit" class="email-button fright" value="发 送" />
							<span><input name="send_mymessage" type="checkbox" id="send_mymessage" value="1" checked="checked" />
							将我的个人资料也发给对方</span>
							<div class="clear"></div>
						 </td>
					</tr>
				</table>
                </form>
				<div class="line" style="margin:25px 0"></div>
				<dl class="msg-tips">
					<dt>温馨提示：</dt>
					<dd>通过我们的匿名邮件系统，收到您的邮件的会员无法看到您的真实邮件地址，Ta将看到“您的用户名@talk96333.com”，这将最大限度的保护了您的个人隐私，为您提高了网络交友的安全性！交友非娱乐、安全最重要！</dd>
					<dt>安全交友提示：</dt>
					<dd>在以下情况下请不要轻易透露您的联系方式（如：电话、手机号码、邮箱、即时通信，通信地址等）</dd>
					<dd>1. 在未充分了解对方前,请不要轻易透露您的联系方式。</dd>
					<dd>2. 当对方无相片或资料填写不完整时,请不要轻易透露您的联系方式。 </dd>
				</dl>
			</div>
			<!--邮件内容结束-->
			</div>
			<div class="r-center-bottom">
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<div class="clear"></div>
	</div>
	
	<!--content end-->
	<?php include MooTemplate('system/footer','public'); ?>
</div><!--main end-->
<script>
function setTab(name,cursel,n){

  /*
  var menu=document.getElementById(name+i);
  var con=document.getElementById("con_"+name+"_"+i);
  menu.className=i==cursel?"hover":"";
  con.style.display=i==cursel?"block":"none";
  */
  if(cursel == 1) {
  	window.location.href="index.php?n=service&h=message&t=supermembermessage";
  }
  
  if(cursel == 2) {
  	window.location.href="index.php?n=service&h=message&t=membermessage";		
  }
  
  if(cursel == 3) {
  	window.location.href="index.php?n=service&h=message&t=hlmessage";		
  }
  
  if(cursel == 4) {
  	window.location.href="index.php?n=service&h=message&t=sendmessage";		
  }
  
  if(cursel == 5) {
  	window.location.href="index.php?n=service&h=message&t=allmessage";		
  }
  

}
</script>
</body>
</html>

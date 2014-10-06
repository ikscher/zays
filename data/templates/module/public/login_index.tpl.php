<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>会员登录-—真爱一生网</title>
<?php include MooTemplate('system/js_css','public'); ?>
<link href="module/login/templates/default/login.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="module/register/templates/public/js/commonValidate.js?v=1"></script>
</head>
<script>
	//检测表单
	
	function checkform(){
	    $('.userTips').html('');
		$('.pwdTips').html('');
		var username = $('#username').val().replace(/\s/g,'');
		var password = $('#password').val().replace(/\s/g,'');
		if(!username){
			$('.userTips').html('请输入用户名');
			$('.userTips').removeClass('h');
			$('.userTips').addClass('s');
			return false;
		}else if (!CheckEmail(username) && !chk_phone(username) && !/^\d{8,8}$/.test(username)){
		    $('.userTips').html('输入的用户名格式不正确');
			$('.userTips').removeClass('h');
			$('.userTips').addClass('s');
			return false;
		}
		if(!password){
			$('.pwdTips').html('请输入密码');
			$('.pwdTips').removeClass('h');
			$('.pwdTips').addClass('s');
			return false;
		}
		return true;
	}

</script>
<body>
<div class="main">
	<div class="top">
		<a href="index.html" class="logo" title="真爱一生网www.zhenaiyisheng.cc"></a>
		<p class="top-tel"></p>
		<span style="position:absolute;right:10px;top:70px;"><a href="login_qq.html" ><img src="public/default/images/login_qq.png" /></a></span>
	</div>
	<div class="content">
		<p class="lgoinTitle"></p>
		<div class="login">
			<span class="right-title fff">欢迎您 登录真爱一生网</span>
		</div><!--content-title end-->
		<div class="c-center">
			
			<!--左边-->
			<div class="login-left">
			    <form id="loginform" name="loginform" action="index.php?n=index&h=submit" method="post" onsubmit="return checkform();">
				<dl class="login-box">
					<dt>帐&nbsp;&nbsp;&nbsp;号：&nbsp;&nbsp;&nbsp;</dt>
					<dd><input type="text" name="username" id="username"   class="form-control" value="" placeholder="ID，手机号，邮箱 都 可以作为账号" class="login-text" /><span class="userTips h"></span></dd>
					<!-- value="<?php echo $u_name;?>" -->
					<dt>密&nbsp;&nbsp;&nbsp;码：&nbsp;&nbsp;&nbsp;</dt>
					<dd><input type="password" name="password" id="password" class="form-control" placeholder="密码" /><span class="pwdTips h"></span></dd>
					
					<dt>&nbsp;</dt>
					<dd >
					    <input style="width:20px;" name="remember" type="checkbox" id="remember" value="1"  /><label for="remember">记住账号</label>
						<a href="#"  onclick="javascript:location.href='index.php?n=login&h=backpassword'" class="f-ed0a91-a">忘记密码？</a>
					</dd>
			
					<dt>&nbsp;</dt>
					<dd><input type="submit" class="btn btn-success ft" value="登 录" /></dd>
					<input type="hidden" name="btnLogin" value="1"/>
					<input type="hidden" id="loginsubmit" name="loginsubmit" value="1"/>
					<input name="returnurl" type="hidden" id="returnurl" value="<?php echo $returnurl;?>" />
				</dl>
				</form>
			</div>
			<!--左边结束-->

			<!--右边-->
			<div class="login-right ">
				<dl class="login-right-text">
					<dt>&nbsp;</dt>
					<dd>1、通过搜索找到您心仪的对象</dd>
					<dd>2、委托红娘帮您联系对方</dd>
					<dd>3、请等待红娘接受委托帮助双方牵线</dd>
					<dd>4、红娘安排您与对方见面约会</dd>
					<dd style="margin-top:16px;"><input name="" type="button" class="btn btn-default" onclick="javascript:location.href='register.html'" value="您还不是会员吗，立即注册&gt;&gt;" /></dd><!--class 以前 go-reg--> 
				</dl>
			</div>
		<!--右边结束-->
			<div class="clear"></div>
		</div><!--c-center-data end-->
		<div class="content-bottom">			</div>
		<!--content-bottom end-->
		<div class="clear"></div>
	</div><!--content end-->
	<div class="footer">
	  <div class="g">品牌：8年专业婚恋服务&nbsp; 专业：庞大的资深红娘队伍&nbsp; 真实：诚信会员验证体系&nbsp; </div>
	  <div class="g">Copyright 2006-2014 真爱一生网.All Right Reserved.<a  href="http://www.miitbeian.gov.cn/" target="_blank">皖ICP备14002819号</a> </div>
	  <div class="g">客服热线：400-8787-920 （周一至周日：9：00-21：00）客服信箱：kefu@zhenaiyisheng.cc</div>
	</div>
</div><!--main end-->
<script type="text/javascript">
    $('#username').focus(function(){
	    $('.userTips').html('');
	});
	
	$('#password').focus(function(){
	    $('.pwdTips').html('');
	});
</script>
</body>
</html>

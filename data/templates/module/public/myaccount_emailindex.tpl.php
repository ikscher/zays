<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>诚信认证--邮箱认证——真爱一生网</title>
<?php include MooTemplate('system/js_css','public'); ?>
<link href="module/myaccount/templates/<?php echo $GLOBALS['style_name'];?>/myaccount.css" rel="stylesheet" type="text/css" />
</head>

<script src="module/search/templates/default/js/syscode.js?v=1" type="text/javascript"></script>
<body>
<?php include MooTemplate('system/header','public'); ?>
<div class="main">
	<div class="content">
	<p class="c-title"><span class="f-000"><a href="index.php?n=service">真爱一生首页</a>&nbsp;&gt;&gt;&nbsp;<a href="index.php?n=myaccount">诚信认证</a>&nbsp;&gt;&gt;&nbsp;邮箱认证</span></p>
	<?php include MooTemplate('system/simp_left','public'); ?><!--content-lift end-->
	<!--=====左边结束===-->
	<div class="content-right">
		<div class="c-right-content">
			<div class="r-center-tilte">
			<a href="index.php?n=myaccount" class="r-title-black">&lt;&lt;返回诚信认证</a>
			<span class="right-title f-ed0a91">邮箱认证</span>
			</div>
			<div class="r-center-ccontent">
				<div class="cer-conter">
					<p class="cer-email">您的注册邮箱是：
                    <span class="f-ed0a91"><?php echo $useremail['username'];?></span>，目前 <span class="f-ed0a91"><?php echo $usercer['email'] == 'yes'?'已验证':'未验证';?></span>，请确认您的邮箱地址是正确的，它将是您找回密码和接收交友信息的邮箱。如果以上邮箱地址不正确或不是您的常用邮箱请 <a href="index.php?n=material&h=password" class="f-ed0a91-a">点击这里</a> 修改。 </p>					
				<div class="cer-line"></div>
					<dl class="cer-email-faq">
						<dt class="f-ed0a91">验证邮箱有哪些好处：</dt>
						<dd>1. 方便找回密码</dd>
						<dd>2. 接收会员的匿名邮件</dd>
						<dd>3. 接收秋波提示</dd>
						<dt>&nbsp;</dt>
						<dd>4. 接收加为好友提示</dd>
						<dd>5. 接收好友信息</dd>
					</dl>
					<div class="clear"></div>
                    <form id="form2" name="form2" method="post" action="">
					<p class="cer-email" style="text-align:center">
                    <input name="emailindexsubmit" type="submit" class="btn btn-default" value="验证邮箱" /></p>
                    </form>
					<div class="cer-line"></div>
					<p class="cer-email"><span class="f-ed0a91">验证邮箱有困难：</span>请拨真爱一生热线：400-8787-920 （免长途）由真爱一生网红娘帮助您解决疑惑。</p>
					<div class="cer-line"></div>
				</div>
				<div class="clear"></div>
			</div>
			<div class="r-center-bottom">
			</div>
		</div>
	</div>
		<div class="clear"></div>
	</div>
	
	<!--content end-->
	<?php include MooTemplate('system/footer','public'); ?><!--foot end-->
</div><!--main end-->
</body>
</html>

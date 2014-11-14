<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>资料设置——真爱一生网</title>
<?php include MooTemplate('system/js_css','public'); ?>
<script type="text/javascript" src="module/register/templates/public/js/syscode.js?v=1"></script>
<style type="text/css">
@import url("module/material/templates/<?php echo $GLOBALS['style_name'];?>/material.css");
</style>
</head>
<body>
<?php include MooTemplate('system/header','public'); ?>
<div class="main">
	<div class="content">
	<p class="c-title"><span class="f-000"><a href="index.php?n=service">真爱一生首页</a>&nbsp;&gt;&gt;&nbsp;资料设置</span><a href="#"></a></p>
	<?php include MooTemplate('public/material_public_left','module'); ?>
	<!--=====左边结束===-->
	<div class="content-right">
    <form  name="form1" method="post" action="index.php?n=material&h=password">
		<div class="c-right-content">
			<div class="r-center-tilte">
			<a href="index.php?n=service" class="r-title-black">&lt;&lt;返回我的真爱一生</a>
			<span class="right-title f-ed0a91">资料设置</span>
			</div>
			<div class="r-center-ccontent">
			<p class="m-accountinfo-title">您将要浏览的信息涉及到个人隐私,为了您的个人信息的安全考虑，请输入密码进入下一步。 </p>
			<h4 style="font-weight:400;">&nbsp;<span class="f-aeaeae">&gt;</span>&nbsp;填写正确的密码：</h4>
			<ul class="pwd-text">
				<li>密码：</li>
				<li><input type="password" name="password" class="pwd-text-in" onfocus="javascript:$(this).addClass('public');" onblur="javascript:$(this).removeClass('public');" /></li>
				<li>
                <input type="hidden" name="check_submit" value="check_submit" />
                <input type="submit" name="material_pwforum1" class="btn btn-default" value="下一步" />&nbsp;&nbsp;<a href="index.php?n=service" class="f-ed0a91-a">返回我的信息中心&nbsp;&gt;&gt;</a>
                </li>
			</ul>
			<div class="clear" style="height:390px;"></div>
			</div>
			<div class="r-center-bottom">
			</div>
			<div class="clear"></div>
		</div>
	</div>
    </form>
		<div class="clear"></div>
	</div>
	
	<?php include MooTemplate('system/footer','public'); ?>
</div><!--main end-->
</body>
</html>
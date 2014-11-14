<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>资料设置——真爱一生网</title>
<?php include MooTemplate('system/js_css','public'); ?>
<script type="text/javascript" src="module/register/templates/public/js/syscode.js?v=1"></script>
<link href="module/material/templates/<?php echo $GLOBALS['style_name'];?>/material.css" type="text/css" rel="stylesheet" />
<style type="text/css">
.left-list li a.left-list19 {
    background-position: 0 -625px;
}
.left-list li a.left-list20 {
    background-position: 0 -661px;
}
</style>
</head>
<body>
<?php include MooTemplate('system/header','public'); ?>
<div class="main">
	
	<div class="content">
	<p class="c-title"><span class="f-000"><a href="index.php?n=service">真爱一生首页</a>&nbsp;&gt;&gt;&nbsp;资料设置</span><a href="#"></a></p>
    <!--左边菜单-->
   <?php include MooTemplate('public/material_public_left','module'); ?>
    <!--content-lift end-->
	<div class="content-right">
		<div class="c-right-content">
			<div class="r-center-tilte">
			<a href="index.php?n=service" class="r-title-black">&lt;&lt;返回我的真爱一生</a>
			<span class="right-title f-ed0a91">资料设置</span>
			</div>
			<div class="r-center-ccontent">
				<ul class="set-mydata">
					<li>
						<dl class="r-list01">
							<dt><a href="index.php?n=material&h=upinfo" target="_blank">修改资料</a></dt>
							<dd><a href="index.php?n=material&h=upinfo" target="_blank" class="f-000-a">基本资料</a> | <a href="index.php?n=material&h=upinfo&actio=1" target="_blank" class="f-000-a">详细资料</a> | <a href="index.php?n=material&h=upinfo&actio=2" target="_blank" class="f-000-a">生活状态</a> | <a href="index.php?n=material&h=upinfo&actio=3" target="_blank" class="f-000-a">兴趣爱好</a></dd>
						</dl>
					</li>
					<li>
						<dl class="r-list02">
                            <dt><a href="index.php?n=space&uid=<?php echo $userid;?>" target="_blank">预览个人资料页</a></dt>
							<dd>这是您在真爱一生网公开展示的个人资料页面，所有会员都能看到。</dd> 
						</dl>
					</li>
                    <li><i class="thenew"><img src="public/default/images/new-icon.gif"></i>
						<dl class="r-list11">
							<dt><a href="index.php?n=material&h=skin" target="_blank">皮肤设置</a></dt>
							<dd>给个人资料页设置皮肤</dd> 
						</dl>
					</li>
					<li>
						<dl class="r-list03">
							<dt><a href="index.php?n=material&h=show" target="_blank">添加/编辑照片</a></dt>
							<dd>更改您的头像，并且上传自己的照片。</dd>
						</dl>
					</li>
					<li>
						<dl class="r-list04">
							<dt><a href="index.php?n=myaccount&h=videoindex" target="_blank">添加/编辑视频</a></dt>
							<dd>更改您的视频，并且上传自己的视频。</dd>
						</dl>
					</li>
					<li>
						<dl class="r-list05">
							<dt><a href="index.php?n=lovestyle" target="_blank">爱情测试</a></dt>
							<dd>发现您的恋爱风格。</dd>
						</dl>
					</li>
					<li>
						<dl class="r-list06">
							<dt><a href="index.php?n=search&h=nickid&info=<?php echo $userid;?>" target="_blank">预览搜索结果页</a></dt>
							<dd>这是您在搜索结果中显示的页面。</dd>
						</dl>
					</li>
					<li>
						<dl class="r-list07">
							<dt><a href="index.php?n=material&h=password" target="_blank">修改密码</a></dt>
							<dd>更改您的密码。</dd>
						</dl>
					</li>
					<li>
						<dl class="r-list08">
							<dt><a href="index.php?n=material&h=screen" target="_blank">屏蔽会员</a></dt>
							<dd>阻止不喜欢的会员给您发秋波、送礼物、发邮件、联系您。</dd>
						</dl>
					</li>
                    <li>
						<dl class="r-list09">
                            <?php if($user['is_phone']==1) { ?>
                                <dt><a href="index.php?n=material&actio=u&staus=0">关闭短信提醒</a></dt> 
                                <dd>如果您不想收到真爱一生网的邮件和委托短信通知，请点此关闭</dd>
							<?php } else { ?>
                            	<dt><a href="index.php?n=material&actio=u&staus=1">开启短信提醒</a></dt>
                            	<dd>您现在不会收到邮件和委托的短信提醒</dd>
							<?php } ?>
						</dl>
					</li>
					<li style="background:none">
						<dl class="r-list10">
                         <?php if($user['showinformation']) { ?>
						 	<dt><a href="#" onclick="javascript:$('#material2').slideToggle(300);return false;" class="f-b-d73c90">隐藏/关闭我的资料</a></dt>
                        	<dd>您的资料目前已公开，所有人都能浏览您的详细资料</dd> 
                         <?php } else { ?>
                         	<dt><a href="index.php?n=material&h=showinformation" class="f-b-d73c90">公开我的资料</a></dt>
					     	<dd>您的资料目前未公开，所有人都不能浏览您的详细资料</dd>
                		 <?php } ?>
						</dl>
					</li>
				</ul>
				<div class="right-bottom-tips" style="display:none" id="material2">
					<div class="r-b-tips-side"></div>
					<div class="r-b-tips-center">
					<a href="#" onclick="javascript:$('#material2').slideUp(300);return false;" class="r-close" ></a>
						<dl><form id="form1" name="form1" method="post" action="index.php?n=material&h=hiddeninformation">
							<dt><strong>请选择您隐藏/关闭个人资料的原因</strong></dt>
							<dd><input name="showinformation" type="radio" value="1" checked="checked" id="a1" /><label for="a1">我已找到正在交往的对象</label></dd>
							<dd><input name="showinformation" type="radio" value="2" id="a2" /><label for="a2">我已找到真爱，即将踏上红地毯</label></dd>
							<dd><input name="showinformation" type="radio" value="3" id="a3" /><label for="a3">我最近很忙，无法及时回复邮件</label></dd>
							<dd><input name="showinformation" type="radio" value="4" id="a4" /><label for="a4">我对真爱一生网的服务不满意</label></dd>
							<span style="margin-top:10px;display:block;height:50px;"><input name="" type="submit" class="btn btn-default" value="确定隐藏资料" /></span>
                            </form>
						</dl>
						
					</div>
					<div class="r-b-tips-side" style="background-position:right"></div>
					<div class="clear"></div>
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
	<?php include MooTemplate('system/footer','public'); ?>
</div><!--main end-->
</body>
</html>

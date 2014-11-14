<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>资料设置——真爱一生网</title>
<?php include MooTemplate('system/js_css','public'); ?>
<link href="module/material/templates/<?php echo $GLOBALS['style_name'];?>/material.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="public/default/js/sys1.js?v=1"></script>
<!-- <script type="text/javascript" src="./module/register/templates/public/js/syscode.js?v=1"></script> -->
</style>
</head>

<body>
<?php include MooTemplate('system/header','public'); ?>
<div class="main">
	<div class="content">
	<!-- <p class="c-title"><span class="f-000"><a href="index.php?n=service">真爱一生首页</a>&nbsp;&gt;&gt;&nbsp;资料设置</span><a href="#"></a></p> -->
	<?php include MooTemplate('public/material_public_left','module'); ?>
	<!--=====左边结束===-->
	 <form id="form1" name="form1" method="post" action="index.php?n=material&h=interest">
    <div class="content-right">
		<div class="c-right-content">
			<div class="r-center-tilte">
			<a href="index.php?n=service" class="r-title-black">&lt;&lt;返回我的真爱一生</a>
			<span class="right-title f-ed0a91">资料设置</span>
			</div>
			<div class="r-center-ccontent">
			<div class="register-step"><p class="step04"><a href="index.php?n=material&h=upinfo">基本资料</a><a href="index.php?n=material&h=upinfo&actio=1">详细资料</a><a href="index.php?n=material&h=upinfo&actio=2">生活态度</a><a href="index.php?n=material&h=upinfo&actio=3" class="in-on">兴趣爱好</a></p><span>填写进度：</span>
			<div class="clear"></div>
			</div>
			<h4>&nbsp;<span class="f-aeaeae">&gt;</span>&nbsp;兴趣爱好：</h4>
			<dl class="c-center-data">				
			<dt>喜欢的活动：</dt>
					<dd style="height:auto">
						<ul class="all-input">
							<script>getCheckbox43rdsok('fondactions','fondactions[]',',<?php echo $userinfo['fondactivity'];?>,',fondactions);</script>
						</ul>						
					</dd>			
			<dt>喜欢的运动：</dt>
					<dd style="height:auto">
						<ul class="all-input">
							<script>getCheckbox43rdsok('fondsports','fondsports[]',',<?php echo $userinfo['fondsport'];?>,',fondsports);</script>
						</ul>						
					</dd>
			<dt>喜欢的音乐：</dt>
					<dd style="height:auto">
						<ul class="all-input">
							<script>getCheckbox43rdsok('fondmusics','fondmusics[]',',<?php echo $userinfo['fondmusic'];?>,',fondmusics);</script>
						</ul>						
					</dd>
			<dt>喜欢的电影：</dt>
					<dd style="height:auto">
						<ul class="all-input">
							<script>getCheckbox43rdsok('fondprograms','fondprograms[]',',<?php echo $userinfo['fondprogram'];?>,',fondprograms);</script>
						</ul>						
					</dd>
			</dl>
			<p class="p-bottom">
            <input type="hidden" name="issubmit" value="提交" />
            <input name="actio" type="hidden" id="actio" value="4" />
            <input name="" type="submit"  class="btn btn-primary go-on" value="提交审核"/>
            </p>
		<div class="clear"></div>
			</div>
			<div class="r-center-bottom">
			</div>
           
			<div class="clear"></div>
		</div>
	</div>
     </form>
		<div class="clear"></div>
	</div>
	
	<!--content end-->
	<?php include MooTemplate('system/footer','public'); ?>
</div><!--main end-->
</body>
</html>

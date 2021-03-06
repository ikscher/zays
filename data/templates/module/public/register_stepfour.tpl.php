<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>会员注册-生活状态填写——真爱一生网</title>
<?php include MooTemplate('system/js_css','public'); ?>
<link href="module/register/templates/<?php echo $GLOBALS['style_name'];?>/css/register-info.css" type="text/css" rel="stylesheet" />
</head>
<body>
<div class="main">
	<?php include MooTemplate('system/header','public'); ?>
	<div class="content">
		<p class="c-title"><span class="f-000"><a href="index.php?n=service">真爱一生首页</a>&nbsp;&gt;&gt;&nbsp;会员注册&nbsp;&gt;&gt;&nbsp;生活状态</span><a href="#"></a></p>
			<form name="register_stepfour" action="index.php?n=register&h=stepfour" method="post"> 
			<div class="content-title">
				<span class="right-title">完善注册信息</span>
			</div><!--content-title end-->
			<div class="c-center">
			<div class="register-step"><p class="step03"><a href="#">基本资料</a><a href="#">详细资料</a><a href="#" class="in-on">生活态度</a><a href="#">兴趣爱好</a></p>
			<div class="clear"></div>
			</div>
			<h4>&nbsp;<span class="f-aeaeae">&gt;</span>&nbsp;生活状态：</h4>
			<dl class="c-center-data">				
			<dt>是否吸烟：</dt>
					<dd>
						<p>
							<script>getSelect('','smoking','smoking','<?php echo $userinfo['smoking'];?>','0',isSmoking);</script>
						</p>						
					</dd>
					<dt>是否饮酒：</dt>
					<dd>
						<p>
							 <script>getSelect('','drinking','drinking','<?php echo $userinfo['drinking'];?>','0',isDrinking);</script>
						</p>						
					</dd>
					<dt>职业类别：</dt>
					<dd>
						<p>
							 <script>getSelect('','occupation3','occupation3','<?php echo $userinfo['occupation'];?>','0',occupation);</script>
						</p>						
					</dd>
					<dt>购车情况：</dt>
					<dd>
						<p>
							 <script>getSelect('','vehicle','vehicle','<?php echo $userinfo['vehicle'];?>','0',vehicle);</script>
						</p>						
					</dd>
					<dt>公司类型：</dt>
					<dd>
						<p>
							<script>getSelect('','corptype','corptype','<?php echo $userinfo['corptype'];?>','0',corptype);</script>
						</p>						
					</dd>
					<dt>是否要孩子：</dt>
					<dd>
						<p>
							<script>getSelect('','wantchildren3','wantchildren3','<?php echo $userinfo['wantchildren'];?>','0',wantchildren);</script>
						</p>						
					</dd>
					<dt>喜欢的美食：</dt>
					<dd class="height-auto">
						<ul class="all-input">
							<script>getCheckbox43rdsok('fondfoods','fondfoods[]',',<?php echo isset($userinfo['fondfood'])?$userinfo['fondfood']:'';?>,',fondfoods);</script>
						</ul>						
					</dd>
				
					<dt>喜欢的地方：</dt>
					<dd class="height-auto">
						<ul class="all-input">
							<script>getCheckbox43rdsok('fondplaces','fondplaces[]',',<?php echo isset($userinfo['fondplace'])?$userinfo['fondplace']:'';?>,',fondplaces);</script>
						</ul>						
					</dd>
					
			</dl>
			<p class="p-bottom"><input name="register_submitfour" type="submit"  class="btn btn-primary go-on" value="保存并继续"/></p>
		<div class="clear"></div>
			</div><!--c-center-data end-->
			<div class="content-bottom">			
			</div>
            </form>
			<!--content-bottom end-->
		<div class="clear"></div>
	</div><!--content end-->
	<?php include MooTemplate('system/footer','public'); ?>
</div><!--main end-->
</body>
</html>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>真爱一生网-信息提示</title>
<link href="./public/default/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="public/default/css/font.css">
<link rel="stylesheet" type="text/css" href="public/default/css/wrong.css">
<link rel="stylesheet" type="text/css" href="public/default/css/global_import_new.css">
<link rel="shortcut icon" href="public/default/images/favicon.ico" type="image/x-icon"/>
<!--<link rel="icon" href="public/default/images/favicon.ico" type="image/x-icon">-->
<script type="text/javascript" src="./public/system/js/jquery-1.8.3.min.js"></script>
<script src="public/system/js/sys.js?v=1"></script>
</head>
<body>
<?php include MooTemplate('system/header','public'); ?>
<!--头部结束-->
<div class="content">
		<div class="c-title">
			<span class="f-000"><a href="index.php">真爱一生首页</a>&nbsp;&gt;&gt;&nbsp;消息提示</span>
			<div class="loaction_right">
				<a href="index.php?n=invite">邀请好友</a>
			</div>
		</div>
			<div class="content-title">
				<span class="right-title">消息提示</span>
			</div><!--content-title end-->
			<div class="c-center">			
				<div class="wrong-tips">
					<div class="wrong-tips-title"></div>
					<div class="wrong-tips-c">
						<div class="wrong-tips-icon">
							<img src="public/system/images/msg/<?php echo $stype;?>.gif" />
						</div>
						<dl class="w-t-c-right">
							<dt>
								<p class="msg-title">提示信息</p>
							</dt>
							<dd><?php echo $message;?></dd>
							<!-- <dd><input name="" type="button" class="msg-button" value="确定" /></dd> -->
						</dl>
						<div class="clear"></div>
					</div>
					<div class="wrong-tips-bottom"></div>
				</div>
			<div class="clear"></div>
			</div><!--c-center-data end-->
			<div class="content-bottom">			
			</div>
			<!--content-bottom end-->
		<div class="clear"></div>
	<?php include MooTemplate('system/footer','public'); ?>
	</div><!--content end-->
</body>
</html>
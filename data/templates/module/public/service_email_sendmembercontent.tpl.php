<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>我的邮箱--我的真爱一生——真爱一生网</title>

<link href="module/service/templates/default/service.css" rel="stylesheet" type="text/css" />
<?php include MooTemplate('system/js_css','public'); ?>
<script src="module/service/templates/public/js/service_submit_page.js"></script>

<script>
// 点击切换各个层
function setTab(name,cursel,n){
    
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

</head>

<body>
<div class="main">
	<?php include MooTemplate('system/header','public'); ?><!--top end-->	
	<div class="content">
	<p class="c-title"><span class="f-000"><a href="index.php">真爱一生首页</a>&nbsp;&gt;&gt;&nbsp;<a href="index.php?n=service">我的真爱一生</a>&nbsp;&gt;&gt;&nbsp;我的邮件</span><a href="index.php?n=invite" target="_blank" class="loaction_right">邀请好友</a></p>
	<div class="content-lift">
		<?php include MooTemplate('public/service_left','module'); ?>
		
	</div><!--content-lift end-->
	<!--=====左边结束===-->
	<div class="content-right">
		<div class="c-right-content">
			<div class="r-center-tilte">
			<a href="index.php?n=service" class="r-title-black">&lt;&lt;返回我的真爱一生</a>
			<span class="right-title f-ed0a91">详细资料</span>
			</div>
			<div class="r-center-ccontent" style="padding:30px 0 10px; width:753px;">
			<div class="service-title">
				<ul>
					<li ><a href="#" id="two5" onclick="javascript:window.location.href='index.php?n=service&h=message&t=allmessage'" ><span>所有来信</span></a></li>
					<li><a href="#" onclick="javascript:location.href='index.php?n=service&h=message&t=membermessage'"><span>会员来信</span></a></li>
					<li><a href="#" onclick="javascript:location.href='index.php?n=service&h=message&t=hlmessage'" ><span>真爱一生来信</span></a></li>
					<li><a href="#" onclick="javascript:location.href='index.php?n=service&h=message&t=sendmessage'"  class="onthis"><span>我发的邮件</span></a></li>
				</ul>
			<div class="clear"></div>
			</div>
			<p  style="padding:20px; text-align:right"><a href="index.php?n=service&h=message&t=sendmessage" class="f-ed0a91">返回列表</a>&nbsp;&nbsp;<a href="index.php?n=service&&h=message&t=detailsendmessage&contentid=<?php echo $upid;?>" class="f-ed0a91">上一封</a>&nbsp;&nbsp;<a href="index.php?n=service&&h=message&t=detailsendmessage&contentid=<?php echo $nextid;?>" class="f-ed0a91">下一封</a></p>
			
			<!--邮件头部-->
			<div class="email-title">
				<dl class="email-title-in">
					<dt>主 题 : <span class="f-b-d73c90"><?php echo htmlspecialchars($memail['s_title'])?></span></dt>
					<dd>
					<div class="email-buttons fright">
					<form id="myform" name="myform" method="post" action="">
                      <input name="delmessagecontent" type="submit" class="email-button" value="删 除" onclick="return confirm('确定删除吗？')" />
                      <input type="hidden" name="messageid" value="<?php echo $contentid;?>">
                      </form>					
					</div>
					发件人： <?php echo $fromname;?> 发送时间：<?php echo date("Y-m-d H:i",$memail['s_time']);?>
					</dd>
				</dl>				
				<div class="clear"></div>
			</div>
			<!--邮件头部结束-->
			<!--邮件内容-->
			<div class="email-box">
				
				<div class="email-box-center2">
						<p> <?php echo $uname;?>：</p>
						<dl>
							<?php echo $memail['s_content'];?>
						</dl>
						
				</div>
				
			</div>
			<!--邮件内容结束-->
			<p  style="padding:20px; text-align:right"><a href="index.php?n=service&h=message&t=sendmessage" class="f-ed0a91">返回列表</a>&nbsp;&nbsp;<a href="index.php?n=service&&h=message&t=detailsendmessage&contentid=<?php echo $upid;?>" class="f-ed0a91">上一封</a>&nbsp;&nbsp;<a href="index.php?n=service&&h=message&t=detailsendmessage&contentid=<?php echo $nextid;?>" class="f-ed0a91">下一封</a></p>
			</div>
			<div class="r-center-bottom">
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<div class="clear"></div>
	</div>
	
	<!--content end-->
	<?php include MooTemplate('system/footer','public'); ?><!--foot end-->
</div><!--main end-->
</body>
</html>

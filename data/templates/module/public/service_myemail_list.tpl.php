<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>我的邮箱——我的真爱一生——真爱一生网</title>
<link href="module/service/templates/default/service.css" rel="stylesheet" type="text/css" />
<?php include MooTemplate('system/js_css','public'); ?>
<script src="module/service/templates/public/js/service_submit_page.js"></script>


<script>
<!--
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
//-->
</script>

<script>
// 分页跳转
function gotoPage() {
    value = document.getElementById("pageGo").value;
    value = parseInt(value);
    
    if(isNaN(value))
        value = 0;
    
    if(value<1)
        value = 1;
    
    if(value> <?php echo ceil($total/$pagesize);?>)
        value = <?php echo ceil($total/$pagesize);?>;
        
    window.location.href = "<?php echo $currenturl2;?>&page="+value;
}
</script>

<script>
// 删除全选功能
function checkAll(chk){  
    var checkboxes = document.getElementsByName("delmessageid[]");
    for(j=0; j < checkboxes.length; j++) {
        checkboxes[j].checked = chk.checked;
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
			<span class="right-title f-ed0a91">我的邮件</span>
			</div>
			<div class="r-center-ccontent" style="padding:30px 0 60px; width:753px;">
			<div class="service-title">
				<ul>
					<li ><a href="#" id="two5" onclick="setTab('two',5,5)" class="onthis"><span>所有来信</span></a></li>
					<li><a href="#" onclick="setTab('two',2,5)" ><span>会员来信</span></a></li>
					<li><a href="#" onclick="setTab('two',3,5)" ><span>真爱一生来信</span></a></li>
					<li><a href="#" onclick="setTab('two',4,5)" ><span>我发的邮件</span></a></li>
				</ul>
			<div class="clear"></div>
			</div>
			<form name="delmemberid" action="index.php?n=service&h=message&t=allmessage" method="post"  onsubmit="return checkPageGo('delmessageid[]')">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" class="my-email-title">
				<tr class="my-e-t">
					<td width="6%" align="right" valign="middle"><input type="checkbox" name="allcheck" id="checkbox" onclick="checkAll(this)" /></td>
					<td width="7%" height="25" align="center">全选</td>
					<td width="16%" height="25" align="center">发件人&nbsp;&nbsp;</td>
					<td width="57%" height="25">主题</td>
					<td width="14%" height="25" align="center">时间</td>
				</tr>
				<tr>
					<td height="25" colspan="5" align="center" class="my-e-tips">提示：<span class="f-ed0a91">超过90天</span>的邮件，将会自动被清空，请您及时备份重要邮件</td>
					</tr>
			</table>
			<table width="100%" border="0" cellspacing="0" cellpadding="0" class="my-email-contenter">
			 <?php $num = 1?>
                 <?php foreach((array)$allmail as $allmails) {?>
				<tr class="<?php echo $num % 2 ==0?'wf5':'wf'?>">
					<td width="6%" align="right"><input name="delmessageid[]" type="checkbox" value="<?php echo $allmails['s_id'];?>" /></td>
					<td width="7%" align="center" valign="middle"> <?php if($allmails['s_status'] == '0') { ?>
                    <img src="module/service/templates/default/images/no-read.gif" />
                    <?php } else { ?>
                    <img src="module/service/templates/default/images/yes-read.gif" />
                    <?php } ?>
                   </td>
				   <?php if($allmails['s_fromid'] != '0' && $allmails['s_cid'] != 50) { ?>
						 <?php $fromname = getfromname($allmails['s_fromid']);?>
						 <?php if(!$fromname) { ?>
						 <?php $fromname = 'ID '.$allmails['s_fromid'];?>
						 <?php } ?>
					<?php } else { ?>
						<?php $fromname = '真爱一生网'?>
					<?php } ?>
					<td width="15%" align="center"> 
					<?php if($allmails['s_fromid'] != '0' && $allmails['s_cid'] != '50') { ?><a class="f30" href="index.php?n=space&h=viewpro&uid=<?php echo $allmails['s_fromid'];?>"><?php echo $fromname;?></a>
					<?php } else { ?>
             			<?php echo $fromname;?>
					<?php } ?> </td>
					<td width="58%"> <a class="<?php echo !$allmails['s_status']?'fzs':'f30'?>" href="index.php?n=service&&h=message&t=detailallmessage&contentid=<?php echo $allmails['s_id'];?>">
	                 
	                    <?php if($allmails['s_cid'] != '3') { ?>
	                    	<?php echo htmlspecialchars(MooStrReplace($allmails['s_title']))?>
	                    <?php } else { ?>
	                    	<?php echo $allmails['s_title'];?>
	                    <?php } ?>
	                    </a></td>
					<td width="14%" align="center" class="f-aeaeae"><?php echo date("Y年m月d日",$allmails['s_time']);?></td>
				</tr>
				<?php $num ++?>
                 <?php } ?>
			</table>
			
			<p>
			<span class="my-e-bottom">
			<a href="#" class="go-page" onclick="gotoPage()">GO</a>
			<?php echo multimail($total,$pagesize,$page,$currenturl2)?>共 <span class="f-b-d73c90"><?php echo $total;?></span> 条消息  转到第
				<input name="" type="text" class="my-e-page" id="pageGo" />
				页</span>
				<input name="delmessage" type="submit" class="btn btn-default" value="删除消息" /></p>
				</form>
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

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>会员动态--我的真爱一生——真爱一生网</title>
<link href="module/service/templates/<?php echo $GLOBALS['style_name'];?>/service.css" rel="stylesheet" type="text/css" />
<?php include MooTemplate('system/js_css','public'); ?>
<script src="module/service/templates/public/js/service_submit_page.js"></script>
<style type="text/css">
	.fixphoto_{max-width:171px;max-height:123px;margin-left:-35px;}
</style>
</head>
<body>
<?php include MooTemplate('system/header','public'); ?>
<div class="main">
	<div class="content">
	<p class="c-title"><span class="f-000"><a href="index.php">真爱一生首页</a>&nbsp;&gt;&gt;&nbsp;<a href="index.php?n=service">我的真爱一生</a>&nbsp;&gt;&gt;&nbsp;会员动态</span><a href="index.php?n=invite" target="_blank" class="loaction_right">邀请好友</a></p>
	<div class="content-lift">
		<?php include MooTemplate('public/service_left','module'); ?>
		
	</div><!--content-lift end-->
	<!--=====左边结束===-->
	<div class="content-right">
		<div class="c-right-content">
			<div class="r-center-tilte">
			<a href="index.php?n=service" class="r-title-black">&lt;&lt;返回我的真爱一生</a>
			<span class="right-title f-ed0a91">我关注的会员动态</span>			
			</div>
			<div class="r-center-ccontent" style="padding:20px 0 60px; width:753px;">
			<!--=======公共头部=====-->
			<div class="service-title">
				<ul>
					<li><a href="#" class="onthis"><span>我关注的会员动态</span></a></li>					
				</ul>
				<div class="clear"></div>
			</div>
			<!--====公共头部结束==-->
			<div class="service-liker">
			<?php if(empty($total)) { ?>
              <div class="norequest">
              <p>您现在还没有关注的会员动态</p>
              <p>立即 <a href="?n=search" class="f-ed0a91-a">搜索TA</a>，发送自己的委托吧</p>
              </div>
           <?php } else { ?>
		   <?php foreach((array)$update_arr as $request) {?>
		   <?php $request=$request['0']?>
      	   <?php $send_user1 = leer_send_user1($request['uid']);?>
				<ul class="service-liker-list">
					<li><input type="checkbox" name="id[]" value="<?php echo $comment['id'];?>"/></li>
					<li>
						<div class="r-service-img">
								<?php if(!empty($send_user1['city_star'])) { ?><a class="citystar2"><img src="module/service/templates/default/images/citystar.gif" /></a><?php } ?>
							<div class="r-s-img-in">
								<p style="width:110px;">
									<a style="display:block;" href="index.php?n=space&h=viewpro&uid=<?php echo $request['uid'];?>" target="_blank">
									<?php if($send_user1['images_ischeck']=='1'&& $send_user1['mainimg']) { ?>
										  <img src="<?php if(MooGetphoto($send_user1['uid'],'mid')) echo MooGetphoto($send_user1['uid'],'mid');
										  elseif(MooGetphoto($send_user1['uid'],'medium')) echo MooGetphoto($send_user1['uid'],'medium');
										  elseif($send_user1['gender'] == '1') echo 'public/system/images/woman_100.gif';
										  else echo 'public/system/images/man_100.gif';
										   ?>"  />
										   <?php } elseif ($send_user1['mainimg']) { ?>
											<?php if($send_user1['gender'] == '1') { ?>
												<img src="public/system/images/woman_100.gif"/>
											<?php } else { ?>
												<img src="public/system/images/man_100.gif"  />
											<?php } ?>
									
										<?php } else { ?>
											<?php if($send_user1['gender'] == '1') { ?>
												<img src="public/system/images/nopic_woman_100.gif" />
											<?php } else { ?>
												<img src="public/system/images/nopic_man_100.gif"  />
											<?php } ?>
										<?php } ?>
								 </a> 
								</p>
							</div>
						</div>						
					</li>
					<li>
						<dl class="liker-heart">
							<dt><?php if($send_user1['s_cid']==20) { ?>
								<a href="index.php?n=payment&h=diamond" target="_blank"><img src="module/index/templates/default/images/zuan0.gif" title="钻石会员" alt="钻石会员"/></a>
								<?php } elseif ($send_user1['s_cid'] == 30) { ?>
								<a href="index.php?n=payment" target="_blank"><img src="module/index/templates/default/images/zuan1.gif" title="高级会员" alt="高级会员"/></a>
								<?php } else { ?>
								<?php } ?>
								<a id="fzs" href="index.php?n=space&h=viewpro&uid=<?php echo $friends['friendid'];?>"><?php $nickname=$send_user1['nickname2']?$send_user1['nickname2']:$send_user1['nickname']?><?php echo $nickname?$nickname:'ID:'.$send_user1['uid']?></a></dt>
							<dd>年龄：<?php if($send_user1['birthyear']) { ?><?php echo (gmdate('Y', time()) - $send_user1['birthyear']);?>岁<?php } else { ?>保密<?php } ?></dd>
							<dd>身高：<script>userdetail('<?php echo $send_user1['height'];?>',height);</script></dd>
							<dd>收入：<script>userdetail('<?php echo $send_user1['salary'];?>',salary1);</script></dd>
							<dd>学历：<script>userdetail('<?php echo $send_user1['education'];?>',education);</script></dd>
							<dd>地址：<?php if($send_user1['province'] == '0' && $send_user1['city'] == '0') { ?>保密<?php } else { ?><script>userdetail('<?php echo $send_user1['province'];?>',provice);userdetail('<?php echo $send_user1['city'];?>',city)</script><?php } ?></dd>
							<dd class="f-ed0a91">&lt; <?php echo activetime($send_user1['lastvisit'],$send_user1['usertype']);?> &gt;</dd>
						</dl>
					</li>
					<li style="float:right">
						<dl class="new-move">
						
							<dd><?php echo date("Y年m月d日 H时i分s秒",$update_arr[$request['uid']][0]['dateline']);?><?php echo $update_arr[$request['uid']][0]['content']?></dd>						
							<dt><input name="" onclick="location.href='index.php?n=service&h=commission&t=sendcontact&sendid=<?php echo $send_user1['uid'];?>'" type="button" class="btn btn-default" value="委托真爱一生联系TA" /></dt>
						</dl>
					</li>
				</ul>
				<?php } ?>
				
				<div class="clear"></div>
				<div class="pages"><span class="fleft" style="padding-left:40px;"><input name="" type="checkbox" onclick="checkAll(this)" value="" />&nbsp;全选&nbsp;<input name="" type="button" class="btn btn-default" value="删除信息" /></span><?php echo multimail($total,$pagesize,$page,$currenturl2)?> <input name="" type="text" size="3" />&nbsp;<input name="" type="button" class="go-page" value="GO" onclick="gotoPage()" style="float:none"/>
					<div class="clear"></div>
				</div>
				<?php } ?>
			</div>
			<div class="clear"></div>
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
// 删除全选功能
function checkAll(chk){  
	var checkboxes = document.getElementsByName("id[]");
	for(j=0; j < checkboxes.length; j++) {
		checkboxes[j].checked = chk.checked;
	}
}


$(document).ready(function(){
	var theImage = new Image();
	
	$(".r-s-img-in a img").each(function(i,w){
		theImage.src = $(w).attr("src");
		var imageWidth = theImage.width;
		var imageHeight = theImage.height;
		if(imageHeight<200){
			//$(w).removeClass('fixphoto');
			$(w).addClass('fixphoto_');
		}else{
			//DrawImage($(w),100,125);
		}
	})
});
</script>
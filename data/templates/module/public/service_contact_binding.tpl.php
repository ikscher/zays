<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>我的绑定--我的真爱一生——真爱一生网</title>
<link href="module/service/templates/default/service.css" rel="stylesheet" type="text/css" />
<?php include MooTemplate('system/js_css','public'); ?>
<script src="module/service/templates/public/js/service_submit_page.js"></script>
</head>

<body>
<div class="main">
	<?php include MooTemplate('system/header','public'); ?><!--top end-->		
	<div class="content">
	<p class="c-title"><span class="f-000"><a href="index.php">真爱一生首页</a>&nbsp;&gt;&gt;&nbsp;<a href="index.php?n=service">我的真爱一生</a>&nbsp;&gt;&gt;&nbsp;我的绑定</span><a href="index.php?n=invite" target="_blank" class="loaction_right">邀请好友</a></p>
	<div class="content-lift">
		<?php include MooTemplate('public/service_left','module'); ?>
	</div><!--content-lift end-->
	<!--=====左边结束===-->
	<div class="content-right">
		<div class="c-right-content">
			<div class="r-center-tilte">
			<a href="index.php?n=service" class="r-title-black">&lt;&lt; 返回我的真爱一生</a><span class="right-title f-ed0a91"><?php if($statue == 1) { ?>（<?php echo $user_s['isbind'] == 0 ? '申请中' : '已绑定' ;?>）<?php } ?>绑定信息</span>			
			</div>
			<div class="r-center-ccontent" style="padding:0 0 60px; width:753px;">
			<?php if($statue == 0) { ?>
			<div class="norequest">
				您现在还没有绑定?
				<p> 马上去 <a href="index.php?n=search" class="f-ed0a91-a">搜索心仪的TA</a>，即刻享受美好的俩人世界。</p>
			</div>
			<?php } else { ?>			
				<dl class="bindtwo">
					<dt><?php if(!empty($user_s['city_star'])) { ?><a class="citystar2"><img src="module/service/templates/default/images/citystar.gif" /></a><?php } ?>
						<div class="cl-photo-img">
							<p><a style="display:block;" href="index.php?n=space&h=viewpro&uid=<?php echo $user_s['uid'];?>" target="_blank">
									<?php if($user_s['images_ischeck']=='1'&& $user_s['mainimg']) { ?>
												  <img src="<?php   
												  if(MooGetphoto($user_s['uid'],'com')) echo MooGetphoto($user_s['uid'],'com');
												  elseif(MooGetphoto($user_s['uid'],'medium')) echo MooGetphoto($user_s['uid'],'medium');
												  elseif($user_s['gender'] == '1')echo 'public/system/images/woman.gif';
												  else  echo 'public/system/images/man.gif';
												  ?>" onload="javascript:DrawImage(this,110,138)" />
									<?php } elseif ($user_s['mainimg']) { ?>
										<?php if($user_s['gender'] == '1') { ?>
											<img src="public/system/images/woman.gif"/>
										<?php } else { ?>
											<img src="public/system/images/man.gif"  />
										<?php } ?>
									<?php } else { ?>
											<?php if($user_s['gender'] == '1') { ?>
												<img src="public/system/images/service_nopic_woman.gif"/>
											<?php } else { ?>
												<img src="public/system/images/service_nopic_man.gif"/>
											<?php } ?>
									 <?php } ?>
									 </a></p>
						</div>
					</dt>
					<dd><?php if($user_s['s_cid'] != 30 && $user_s['s_cid'] != 40 && $user_s['s_cid'] != 50) { ?>
								<a href="index.php?n=payment&h=diamond" target="_blank"><img src="module/index/templates/default/images/zuan0.gif" title="钻石会员" alt="钻石会员"/></a>
								<?php } elseif ($user_s['s_cid'] == 30) { ?>
								<a href="index.php?n=payment" target="_blank"><img src="module/index/templates/default/images/zuan1.gif" title="高级会员" alt="高级会员"/></a>
								<?php } ?><?php $nickname=$user_s['nickname']?><?php echo $nickname?$nickname:'ID '.$user_s['uid']?></dd>
					<dd class="f-ed0a91">&lt; <?php echo activetime($user_s['lastvisit'],$user_s['usertype']);?> &gt;
				   </dd>
					<dd><?php if($user_s['birthyear']) { ?><?php echo (gmdate('Y', time()) - $user_s['birthyear']);?>岁<?php } else { ?>年龄保密<?php } ?>，<script>userdetail('<?php echo $user_s['marriage'];?>',marriage)</script></dd>
					<dd><?php if($user_s['province'] == '0' && $user_s['city'] == '0') { ?>保密<?php } else { ?><script>userdetail('<?php echo $user_s['province'];?>',provice)</script><script>userdetail('<?php echo $user_s['city'];?>',city)</script><?php } ?></dd>
				</dl>
				<img src="module/service/templates/default/images/bind-img.gif" class="fleft" />
				<dl class="bindtwo">
					<dt>
						<div class="cl-photo-img">
							<p><a style="display:block;" href="index.php?n=space&h=viewpro&uid=<?php echo $user_bind['uid'];?>" target="_blank">
							<?php if($user_bind['images_ischeck']=='1'&& $user_bind['mainimg']) { ?>
										  <img src="<?php   
										  if(MooGetphoto($user_bind['uid'],'com')) echo MooGetphoto($user_bind['uid'],'com');
										  elseif(MooGetphoto($user_bind['uid'],'medium')) echo MooGetphoto($user_bind['uid'],'medium');
										  elseif($user_bind['gender'] == '1')echo 'public/system/images/woman.gif';
										  else  echo 'public/system/images/man.gif';
										  ?>" onload="javascript:DrawImage(this,110,138)" />
							<?php } elseif ($user_bind['mainimg']) { ?>
								<?php if($user_bind['gender'] == '1') { ?>
									<img src="public/system/images/woman.gif"/>
								<?php } else { ?>
									<img src="public/system/images/man.gif"  />
								<?php } ?>
							<?php } else { ?>
									<?php if($user_bind['gender'] == '1') { ?>
										<img src="public/system/images/service_nopic_woman.gif"/>
									<?php } else { ?>
										<img src="public/system/images/service_nopic_man.gif"/>
									<?php } ?>
							 <?php } ?></a></p>
						</div>
					</dt><?php $nickname=$user_bind['nickname']?$user_bind['nickname']:$user_bind['nickname']?>
					<dd> <?php if($user_bind['s_cid'] != 30 && $user_bind['s_cid'] != 40 && $user_bind['s_cid'] != 50) { ?>
								<a href="index.php?n=payment&h=diamond" target="_blank"><img src="module/index/templates/default/images/zuan0.gif" title="钻石会员" alt="钻石会员"/></a>
								<?php } elseif ($user_bind['s_cid'] == 30) { ?>
								<a href="index.php?n=payment" target="_blank"><img src="module/index/templates/default/images/zuan1.gif" title="高级会员" alt="高级会员"/></a>
								<?php } ?><?php echo $nickname?$nickname:'ID '.$user_bind['uid']?></dd>
					<dd class="f-ed0a91">&lt; <?php echo activetime($user_bind['lastvisit'],$user_bind['usertype']);?>&gt;</dd>
					<dd><?php if($user_bind['birthyear']) { ?><?php echo (gmdate('Y', time()) - $user_bind['birthyear']);?>岁<?php } else { ?>年龄保密<?php } ?>，<script>userdetail('<?php echo $user_bind['marriage'];?>',marriage)</script></dd>
					<dd><?php if($user_bind['province'] == '0' && $user_bind['city'] == '0') { ?>保密<?php } else { ?><script>userdetail('<?php echo $user_bind['province'];?>',provice)</script><script>userdetail('<?php echo $user_bind['city'];?>',city)</script><?php } ?></dd>
				</dl>
				<div class="clear"></div>
			</div>
			<?php } ?>
			</div>
			<div class="r-center-bottom">
			</div>
			<div class="clear"></div>		
	</div>
	<div class="clear"></div>
	</div>	
	<!--content end-->			
		<div class="clear"></div>
	<?php include MooTemplate('system/footer','public'); ?><!--foot end-->
</div><!--main end-->
</body>
</html>

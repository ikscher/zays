<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>我的黑名单--我的真爱一生——真爱一生网</title>
<link href="module/service/templates/default/service.css" rel="stylesheet" type="text/css" />
<?php include MooTemplate('system/js_css','public'); ?>
<script src="module/service/templates/public/js/service_submit_page.js"></script>
</head>

<body>
<?php include MooTemplate('system/header','public'); ?>
<div class="main"><!--top end-->	
	<div class="content">
	<p class="c-title"><span class="f-000"><a href="index.php">真爱一生首页</a>&nbsp;&gt;&gt;&nbsp;<a href="index.php?n=service">我的真爱一生</a>&nbsp;&gt;&gt;&nbsp;我的黑名单</span><a href="index.php?n=invite" target="_blank" class="loaction_right">邀请好友</a></p>
	<div class="content-lift">
		<?php include MooTemplate('public/service_left','module'); ?>
	</div><!--content-lift end-->
	<!--=====左边结束===-->
	<div class="content-right">
		<div class="c-right-content">
			<div class="r-center-tilte">
			<a href="index.php?n=service" class="r-title-black">&lt;&lt;返回我的真爱一生</a>
			<span class="right-title f-ed0a91">我的黑名单</span>
			</div>
			
			<div class="r-center-ccontent" style="padding:20px 0 60px; width:753px;">
			<form name="form2" action="index.php?n=service&h=black&is_post=add" method="post">
			<div class="blacklist">
			输入您想加入黑名单的会员ID：<input name="black_uid" type="text" class="blacklist-searchid" maxlength="10" /><input name="" type="submit" class="btn btn-default" value="立即加入黑名单" />
			</div>
			</form>
			<!--=======公共头部=====-->
			<div class="service-title">
				<ul>
					<li><a href="#" class="onthis"><span>我的黑名单</span></a></li>
					
				</ul>
				<div class="clear"></div>
			</div>
			<!--====公共头部结束==-->
			<div class="service-liker">
			<?php if(empty($total)) { ?>
              <div class="norequest">
              <p>您现在还没将任何人加入黑名单</p>
              <p>立即 <a href="?n=search" class="f-ed0a91-a">搜索TA</a>，寻找自己的最爱吧</p>
              </div>
           <?php } else { ?>
		   <form name="form2" action="index.php?n=service&h=black&is_post=delblack" method="post">
		   <?php foreach((array)$black_uid as $request) {?>		   
      	   <?php $send_user1 = leer_send_user1($request['mid']);$send_user2 = leer_send_user2($request['mid']);?>
				<ul class="service-liker-list">
					<li><input type="checkbox" name="id[]" value="<?php echo $request['mid'];?>"/></li>
					<li>
						<div class="r-service-img">
						<?php if(!empty($send_user1['city_star'])) { ?><a class="citystar2"><img src="module/service/templates/default/images/citystar.gif" /></a><?php } ?> 
							<div class="r-s-img-in">
								<p> <a style="display:block;" href="index.php?n=space&h=viewpro&uid=<?php echo $request['mid'];?>">
									<?php if($send_user1['images_ischeck']=='1'&& $send_user1['mainimg']) { ?>
										  <img src="
										  <?php if(MooGetphoto($send_user1['uid'],'index')) echo MooGetphoto($send_user1['uid'],'index');
										  elseif(MooGetphoto($send_user1['uid'],'medium')) echo MooGetphoto($send_user1['uid'],'medium');
										  elseif($send_user1['gender'] == '1') echo 'public/system/images/woman_100.gif';
										  else echo 'public/system/images/man_100.gif';
										   ?>
										  " onload="javascript:DrawImage(this,100,125)" />
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
								 </a>  </p>
							</div>
						</div>						
					</li>
					<li>
						<dl class="liker-heart">
							<dt><?php if($send_user1['s_cid'] ==20) { ?>
								<a href="index.php?n=payment&h=diamond" target="_blank"><img src="module/index/templates/default/images/zuan0.gif" title="钻石会员" alt="钻石会员"/></a>
								<?php } elseif ($send_user1['s_cid'] == 30) { ?>
								<a href="index.php?n=payment" target="_blank"><img src="module/index/templates/default/images/zuan1.gif" title="高级会员" alt="高级会员"/></a>
								<?php } else { ?>
								<?php } ?>
								<a id="fzs" href="index.php?n=space&h=viewpro&uid=<?php echo $send_user1['uid'];?>"><?php $nickname=$send_user1['nickname2']?$send_user1['nickname2']:$send_user1['nickname']?><?php echo $nickname?$nickname:'ID:'.$send_user1['uid']?></a>
							</dt>
							<dd>年龄：<?php if($send_user1['birthyear']) { ?><?php echo (gmdate('Y', time()) - $send_user1['birthyear']);?>岁<?php } else { ?>保密<?php } ?></dd>
							<dd>身高：<script>userdetail('<?php echo $send_user1['height'];?>',height);</script></dd>
							<dd>收入：<script>userdetail('<?php echo $send_user1['salary'];?>',salary1);</script></dd>
							<dd>学历：<script>userdetail('<?php echo $send_user1['education'];?>',education);</script></dd>
							<dd>地址：<?php if($send_user1['province'] == '0' && $send_user1['city'] == '0') { ?>保密<?php } else { ?><script>userdetail('<?php echo $send_user1['province'];?>',provice);userdetail('<?php echo $send_user1['city'];?>',city)</script><?php } ?></dd>
							<dd class="f-ed0a91">&lt; <?php echo activetime($send_user1['lastvisit'],$send_user1['usertype']);?> &gt;</dd>
						</dl>
					</li>
					<li class="fright">
						<dl class="r-service-heart">
							<dt>内心独白</dt>
							<dd class="r-service-heart-text"> <?php if($send_user1['uid'] < 1599400) { ?>
									<?php echo $send_user2['introduce']?MooStrReplace(MooCutstr($send_user2['introduce'], 148, $dot = ' ...')):'无内心独白内容'?>
									<?php } else { ?>
									<?php echo $send_user2['introduce']?MooCutstr($send_user2['introduce'], 148, $dot = ' ...'):'无内心独白内容'?>
									<?php } ?></dd>
							
						</dl>	
					</li>
				</ul>
				<?php } ?>
				
				<div class="clear"></div>
				<div class="pages"><span class="fleft" style="padding-left:40px;"><input name="checkall" type="checkbox" value="" />&nbsp;全选<input name="" type="submit" class="my-e-button" value="删除信息" /></span>
                <?php if($page_list) { ?>
                	<?php echo $page_list;?><input name="pageGo"  id="pageGo" type="text" size="3" />&nbsp;<input name="" type="button" class="go-page" value="GO" onclick="gotoPage()" style="float:none"/><?php } ?>
                    
					<script type="text/javascript">
                        $(function() {
                            $("input[name='checkall']").click(function() {
                                if($(this).attr('checked') == true) {
                                    $(":checkbox[name='id[]']").each(function() {
                                        $(this).attr('checked', true);
                                    });
                                } else {
                                    $(":checkbox[name='id[]']").each(function() {
                                        $(this).attr('checked', false);
                                    });
                                }
                            });
                        });
                    </script>
					<div class="clear"></div>
				</div>
				</form>
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

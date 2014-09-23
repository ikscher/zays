<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>身份证查看信息——我的真爱一生——真爱一生网</title>
<link href="module/service/templates/default/service.css" rel="stylesheet" type="text/css" />
<?php include MooTemplate('system/js_css','public'); ?>
<script src="module/service/templates/public/js/service_submit_page.js"></script>

</head>

<body>
<div class="main">
	<?php include MooTemplate('system/header','public'); ?><!--top end-->	
	<div class="content">
	<p class="c-title"><span class="f-000"><a href="index.php">真爱一生首页</a>&nbsp;&gt;&gt;&nbsp;<a href="index.php?n=service">我的真爱一生</a>&nbsp;&gt;&gt;&nbsp;身份证查看信息</span><a href="index.php?n=invite" target="_blank" class="loaction_right">邀请好友</a></p>
	<div class="content-lift">
		<?php include MooTemplate('public/service_left','module'); ?>	
		
	</div><!--content-lift end-->
	<!--=====左边结束===-->
	<div class="content-right">
		<div class="c-right-content">
			<div class="r-center-tilte">
			<a href="index.php?n=service" class="r-title-black">&lt;&lt;返回我的真爱一生</a>
			<span class="right-title f-ed0a91">查看身份证信息</span>			
			</div>
			<div class="r-center-ccontent" style="padding:20px 0 60px; width:753px;">
			<!--=======公共头部=====-->

			<div class="service-title">
				<ul>
					<li><a href="#" class="onthis"><span>已收到的查看请求（<?php echo $total;?>）</span></a></li>
					<li><a href="#" onclick="location.href='index.php?n=service&h=send_request_sms_list'"><span>已发送的查看请求（<?php echo $total2;?>）</span></a></li>
				</ul>
				<div class="clear"></div>
			</div>
			<!--====公共头部结束==-->
			 <form name="delmemberid" action="index.php?n=service&h=delsms" method="post">
			<div class="service-liker">
			<?php if($total == 0) { ?>
              <div class="norequest">
              您现在还没有收到任何查看身份证请求
              <p>立即 <a href="?n=search" class="f-ed0a91-a">搜索TA</a>，发送自己的查看请求吧</p>
              </div>
           <?php } else { ?>
		   <?php foreach((array)$request_list as $request) {?>
      	   <?php $send_user1 = leer_send_user1($request['ruid']);$send_user2 = leer_send_user2($request['ruid']);?>
				<ul class="service-liker-list">
					<li><input type="checkbox" name="delsms[]" value="<?php echo $request['id'];?>"/></li>
					<li>
						<div class="r-service-img">
						<?php if(!empty($send_user1['city_star'])) { ?><a class="citystar2"><img src="module/service/templates/default/images/citystar.gif" /></a>
			  		<?php } ?> 
							<div class="r-s-img-in">
								<p> <a style="display:block;" href="index.php?n=space&h=viewpro&uid=<?php echo $send_user1['uid'];?>" target="_blank">
									<?php if($send_user1['images_ischeck']=='1'&& $send_user1['mainimg']) { ?>
										  <img src="
										  <?php if(MooGetphoto($send_user1['uid'],'index')) echo MooGetphoto($send_user1['uid'],'index');
										  elseif(MooGetphoto($send_user1['uid'],'medium')) echo MooGetphoto($send_user1['uid'],'medium');
										  elseif($send_user1['gender'] == '1') echo 'public/system/images/woman_100.gif';
										  else echo 'public/system/images/man_100.gif';?> " onload="javascript:DrawImage(this,100,125)" />
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
								  </a> </p>
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
								<a id="fzs" href="index.php?n=space&h=viewpro&uid=<?php echo $request['ruid'];?>"><?php $nickname=$send_user1['nickname2']?$send_user1['nickname2']:$send_user1['nickname']?><?php echo $nickname?$nickname:'ID:'.$send_user1['uid']?></a>
						</dt>
							<dd><?php if($send_user1['birthyear']) { ?><?php echo (gmdate('Y', time()) - $send_user1['birthyear']);?>岁<?php } else { ?>年龄保密<?php } ?>，居住<?php if($send_user1['province'] == '0' && $send_user1['city'] == '0') { ?>保密<?php } else { ?>在<script>userdetail('<?php echo $send_user1['province'];?>',provice)</script><script>userdetail('<?php echo $send_user1['city'];?>',city)</script><?php } ?>的<?php echo $send_user1['gender']?'女士':'男士'?>，寻找一位年龄<?php if($send_user2['age1'] == '0') { ?>不限<?php } else { ?>在<?php echo $send_user2['age1'];?>-<?php echo $send_user2['age2'];?>岁<?php } ?>，居住<?php if($send_user2['workprovince'] == '0' && $send_user2['workcity'] == '0') { ?>不限<?php } else { ?>在<script>userdetail('<?php echo $send_user2['workprovince'];?>',provice);userdetail('<?php echo $send_user2['workcity'];?>',city)</script><?php } ?>的<?php echo $send_user1['gender']?'男士':'女士'?></dd>
							
							<dd class="f-ed0a91">&lt;<?php echo activetime($send_user1['lastvisit'],$send_user1['usertype']);?>
						&gt;</dd>
						</dl>
					</li>
					<?php if($request['status'] == 0) { ?>
					<li style="float:right">
						<dl class="ture-data">
							<dd><?php echo date("Y年m月d日 H时i分s秒",$request['dateline']);?></dd>
							<dd>向您第<?php echo $request['request_total'];?>次请求查看身份证信息。</dd>
							<dd class="f-024890"><?php echo $send_user1['gender']?'她':'他';?>如此喜欢您，强烈建议您：</dd>
							<dd><a href="index.php?n=service&h=request_sms_list&id=<?php echo $request['id'];?>&status=1" onclick="return confirm('您确定同意对方查看您的身份证信息吗？')">同意查看</a></dd>
							<dd class="f-024890">如果<?php echo $send_user1['gender']?'她':'他';?>不是您喜欢的类型，您可以</dd>
							<dd><a href="index.php?n=service&h=request_sms_list&id=<?php echo $request['id'];?>&status=2" onclick="return confirm('您确定拒绝对方查看吗？')">拒绝查看</a></dd>
							<dd><span class="f-024890">最后，您还可以选择</span><a href="index.php?n=service&h=request_sms_list&id=<?php echo $request['id'];?>&status=3">考虑一下</a></dd>
						</dl>
					</li>
					<?php } ?>
					<?php if($request['status'] == 1) { ?>
						<li style="float:right">
						<dl class="ture-data">
							<dd>您已经同意<?php echo $send_user1['gender']?'她':'他';?>查看您的身份证信息</dd>
							<!-- <dd>如果想进一步联系，建议您马上</dd>
							<dt><input name="" onclick="location.href='index.php?n=service&h=commission&t=sendcontact&sendid=<?php echo $send_user1['uid'];?>'" type="button" class="get-know-likes" value="委托真爱一生联系TA" /></dt> -->
							
						</dl>
					</li>
					<?php } ?>
					<?php if($request['status'] == 2) { ?>
					<li style="float:right">
						<dl class="ture-data">
							<dd><?php echo date("Y年m月d日",$request['dateline']);?>向您第<?php echo $request['request_total'];?>次请求查看身份证信息。</dd>
							<dd>您已经委婉拒绝了对方，如果您</dd>
							<?php if($request['request_total']>1) { ?><dd><a href="index.php?n=service&h=request_sms_list&id=<?php echo $request['id'];?>&status=1" onclick="return confirm('您确定同意对方查看您的身份证信息吗？')">同意查看</a></dd><?php } ?>
							<dt>改变了主意，就<input name="" onclick="location.href='index.php?n=service&h=commission&t=sendcontact&sendid=<?php echo $send_user1['uid'];?>'" type="button" class="get-know-likes" value="委托真爱一生联系TA" /></dt>
							
						</dl>
					</li>
					<?php } ?>
					<?php if($request['status']==3) { ?>
					<li style="float:right">
						<dl class="ture-data">
							<dd>您正在考虑是否让<?php echo $send_user1['gender']?'她':'他';?>查看，<?php echo $send_user1['gender']?'她':'他';?>如此喜欢您，如果</dd>
							<dd>改变了主意，就</dd>
							<?php if($request['request_total']>1) { ?><dd><a href="index.php?n=service&h=request_sms_list&id=<?php echo $request['id'];?>&status=1" onclick="return confirm('您确定同意对方查看您的身份证信息吗？')">同意查看</a></dd><?php } ?>
							<!-- <dt><input name="" onclick="location.href='index.php?n=service&h=commission&t=sendcontact&sendid=<?php echo $send_user1['uid'];?>'" type="button" class="btn btn-default" value="委托真爱一生联系TA" /></dt> -->
							
						</dl>
					</li>
					<?php } ?>
				</ul>
				<?php } ?>
				<div class="clear"></div>
				<div class="pages"><span class="fleft" style="padding-left:40px;"><input name="" type="checkbox" value="" onclick="checkAll(this)" />&nbsp;全选<input name="" type="submit" class="my-e-button" value="删除信息" /></span><?php echo multimail($total2,$pagesize,$page,$currenturl2)?><input name="pageGo"  id="pageGo" type="text" size="3" />&nbsp;<input name="" type="button" class="go-page" value="GO" onclick="gotoPage()" style="float:none"/>
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
	<div id="view_request_sms" style="display:none;position:absolute;z-index:1000;background:#efefef;font-size:12px;line-height:23px;">
	
</div>
</div><!--main end-->
</body>
</html>
<script>
// 切换tab
function setTab(name,cursel,n) {

  if(cursel == 1) {
  	window.location.href="index.php?n=service&h=leer";
  }
  
  if(cursel == 2) {
  	window.location.href="index.php?n=service&h=leer&t=isendleer";		
  }

}
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
		
	window.location.href = "<?php echo $page_url;?>&page="+value;
}
// 删除全选功能
function checkAll(chk){  
	var checkboxes = document.getElementsByName("delsms[]");
	for(j=0; j < checkboxes.length; j++) {
		checkboxes[j].checked = chk.checked;
	}
}
</script>
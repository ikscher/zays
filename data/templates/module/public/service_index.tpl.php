<!DOCTYPE html >
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>我的真爱一生——真爱一生网</title>
<link href="module/service/templates/default/service.css?v=20140615" rel="stylesheet" type="text/css" />
<?php include MooTemplate('system/js_css','public'); ?>
<style type="text/css">
#rea_name{ position:fixed;left:50%;margin-left :-515px; bottom:150px;_position:absolute;_top:150px;z-index: 3003; display:none;}
#frame{ position:fixed;left:50%;margin-left :-515px; bottom:150px;_position:absolute;_top:200px;z-index: 1;  width:1000px; height:200px;}
</style>
<script type="text/javascript">
	function tabtoggle(e,o){
		if($(o).is(":hidden")){
			$('.list-open').removeClass('list-open').addClass('list-close');
			$('.Unflist dd').hide();
			$(o).show();
			$("span",e).removeClass("list-close").addClass("list-open");
		}else{
			$(o).hide();
			$("span",e).removeClass("list-open").addClass("list-close");
		}
	}

	$(function(){
		var arr = document.cookie.match(new RegExp("(^| )"+"ServerCenter-26244589"+"=([^;]*)(;|$)"));
		if(arr != null) {var div_colse=1;}else{ var div_colse=0;}
		if(div_colse){$('#unfbox').css("display","none");}
		$('#closeDiv').click(function(){
		$('#unfbox').css("display","none");
		var date=new Date();
		date.setTime(date.getTime()+4*3600*1000);
		document.cookie = "ServerCenter-26244589=1;expires=" + date.toGMTString() + ";";
		})
	});
	
	
	function setCookie(name,value){
		var never = new Date();
		//设置never的时间为当前时间加上1天的毫秒值
		never.setTime(never.getTime()+4*3600*1000);    
		var expString = "expires="+ never.toGMTString()+";";
		 document.cookie = name + "="+ escape (value) + ";expires=" + expString+ 'path=/;';
		
	}
	
	
	function getCookie(name) {
		var arr = document.cookie.split('; ');
		var i = 0;
		for(i=0; i<arr.length; i++) {
			var arr2 = arr[i].split('=');
			if(arr2[0] == name) {return arr2[1];}
		}
		return '';
	}
	function removeCookie(name) {
		setCookie(name,'',-1);
	}
	
</script>

</head>

<body>
<?php include MooTemplate('system/header','public'); ?><!--top end-->	
<div class="main">
	
	<div class="content">
	<!-- <p class="c-title"><span class="f-000">真爱一生首页&nbsp;&gt;&gt;&nbsp;我的真爱一生</span><a href="index.php?n=invite" target="_blank" class="loaction_right">邀请好友</a></p> -->
	<div class="content-lift">
		<?php include MooTemplate('public/service_left','module'); ?>
      
    </div><!--content-lift end-->
	<!--=====左边结束===-->
	<div class="content-right">
		<div class="right-basic-data">
			<div class="right-basic-data-tilte">
			<span class="right-title">基本资料</span>
			</div>
			<div class="r-b-data-c">
				<div class="r-basic-data-img fleft">
				<?php if($user1['images_ischeck']=='1'&& $user1['mainimg']) { ?>
							  <img src="<?php   
							  if(MooGetphoto($user1['uid'],'medium')) echo MooGetphoto($user1['uid'],'medium');
							  elseif(MooGetphoto($user1['uid'],'com')) echo MooGetphoto($user1['uid'],'com');
							  elseif($user1['gender'] == '1')echo 'public/system/images/woman.gif';
							  else  echo 'public/system/images/man.gif';
							  ?>" onload="javascript:DrawImage(this,110,138)" />
				<?php } elseif ($user1['mainimg']) { ?>
						<?php if($user1['gender'] == '1') { ?>
									<img src="public/system/images/woman.gif"  />
								<?php } else { ?>
									<img src="public/system/images/man.gif" />
								<?php } ?>
						
						<?php } else { ?>
								<?php if($user1['gender'] == '1') { ?>
									<img src="public/system/images/service_nopic_woman.gif"  />
								<?php } else { ?>
									<img src="public/system/images/service_nopic_man.gif"  />
								<?php } ?>
						<?php } ?>
				</div>
				<p class="name-time"><span class="f-b-d73c90"><?php if($user1['s_cid'] ==20) { ?>钻石会员<?php } elseif ($user1['s_cid'] == 30) { ?>高级会员<?php } elseif ($user1['s_cid']==10) { ?>铂金会员<?php } else { ?>	普通会员<?php } ?></span><span>上次登录：&nbsp;<?php echo date('Y-m-d H:i',$user1['lastvisit'])?></span></p>
				<dl class="user-data fleft">
					<dt>用&nbsp;户&nbsp;名：&nbsp;<?php echo $user1['username']?MooCutstr($user1['username'], 16, $dot = ' ...'):$user1['nickname'];?></dt>
					<dd>ID：<?php echo $user1['uid'];?> </dd>
					<dt><span class="fleft">诚&nbsp;信&nbsp;值：</span> <?php echo get_integrity($user_arr['certification']);?>
					</dt>
					<dd>资料完整度：<span class="f-b-d73c90"><?php echo (int)($user_per_num*100)."%"?></span></dd>
				</dl>
				<p class="name-time"><a href="index.php?n=material" target="_blank">资料设置</a> <a href="index.php?n=material&h=show" target="_blank">我的相册</a> <a href="index.php?n=myaccount" target="_blank">提高诚信值</a> <a href="index.php?n=space&uid=<?php echo $user1['uid'];?>" target="_blank">资料预览</a> <a href="index.php?n=search&h=nickid&info=<?php echo $user['uid'];?>" target="_blank">搜索条件预览</a></p>
			</div>
			<div class="right-basic-data-bottom">
			</div>
		</div>
		<div class="right-tips fright">
		<a href="index.php?n=safetyguide" target="_blank"><img src="module/service/templates/default/images/tips.jpg" /></a>
		
		</div>
		<?php if(($user_per_num<(0.9))||($user1['images_ischeck']!=1)||($certification['telphone']<100)||($certification['sms']!=1)) { ?>
		<div class="gotr-data" id="unfbox">
			<div class="gotr-data-title"><a href="#" class="fright" id="closeDiv" style="padding:5px;"><img src="module/service/templates/default/images/chat-close.gif" /></a><span class="r-title">完善征婚档案</span></div>
			<div class="gotr-data-center">
			<dl class="Unflist">
				<?php if($user_per_num<(0.9)) { ?><dt onclick="tabtoggle(this,'#1')"><span class="list-open">完善资料</span></dt>
				<dd id="1">
					<p>您还有一些个人资料未填写，一份完整的资料页，是吸引潜在伴侣全面深入了解您的关键，同时红娘帮您牵线更准确。</p>
					<a href="index.php?n=material&h=upinfo" class="goto-data" target="_blank">立即完善资料</a>
				</dd>
				<?php } ?>
				<?php if($user1['images_ischeck']!=1) { ?><dt onclick="tabtoggle(this,'#2')"><span class="list-close">上传形象照片</span></dt>
				<dd id="2" style="display: none;">
					<p>您还有一些个人资料未填写，一份完整的资料页，是吸引潜在伴侣全面深入了解您的关键，同时红娘帮您牵线更准确。</p>
					<a href="index.php?n=material&h=show" class="goto-data" target="_blank">立即上传形象照</a>
				</dd>
				<?php } ?>
				<?php if($certification['telphone']<100) { ?><dt onclick="tabtoggle(this,'#3')"><span class="list-close">免费认证手机号</span></dt>
				<dd id="3" style="display: none;">
					<p>您还有一些个人资料未填写，一份完整的资料页，是吸引潜在伴侣全面深入了解您的关键，同时红娘帮您牵线更准确。</p>
					<a href="index.php?n=myaccount&h=telphone" target="_blank" class="goto-data">立即验证手机号</a>
				</dd>
				<?php } ?>
				<?php if($certification['sms']!=1) { ?>
				<dt onclick="tabtoggle(this,'#4')"><span class="list-close">身份通认证</span></dt>
				<dd id="4" style="display: none;">
					<p>您还有一些个人资料未填写，一份完整的资料页，是吸引潜在伴侣全面深入了解您的关键，同时红娘帮您牵线更准确。</p>
					<a href="index.php?n=myaccount&h=smsindex" target="_blank" class="goto-data">立即认证身份通</a>
				</dd>
				<?php } ?>
			</dl>
			</div>
			<div class="gotr-data-bottom"></div>
		</div>
		<?php } ?>

		<!-- <div class="right-happyway">
			<div class="r-center-tilte">
			<span class="right-title">幸福之路动态</span>
			</div>
			<div class="r-center-ccontent">
				<ul class="happyway-data-l">
					<li><span class="r-last01"><?php if(show_total(2,$userid)) { ?>您收到<font class="f-ed0a91"><strong><?php echo show_total(2,$userid)?></strong></font>封邮件<?php } else { ?>您最近没有新邮件<?php } ?></span><a href="index.php?n=service&h=message">查看&gt;&gt;</a></li>
					<li><span class="r-last02"><?php if(show_total(4,$userid)) { ?>您最近收到<font class="f-ed0a91"><strong><?php echo show_total(4,$userid)?></strong></font>个秋波<?php } else { ?>您最近没有收到过秋波<?php } ?></span><a href="index.php?n=service&h=leer">查看&gt;&gt;</a></li>
					<li><span class="r-last03"><?php if(show_total(6,$userid)) { ?> 您有<font class="f-ed0a91"><strong><?php echo show_total(6,$userid)?></strong></font>位意中人<?php } else { ?>您最近没有被加为意中人<?php } ?></span><a href="index.php?n=service&h=liker">查看&gt;&gt;</a></li>
					<li><span class="r-last04"><?php if(show_total(7,$userid)) { ?> 您有<font class="f-ed0a91"><strong><?php echo show_total(7,$userid)?></strong></font>个评价<?php } else { ?>您最近没有收到评价<?php } ?></span><a href="index.php?n=service&h=mycomment">查看&gt;&gt;</a></li>
					<li><span class="r-last05"><?php if($user1['isbind']) { ?>您已经进行了爱情绑定<?php } else { ?>您最近没有进行爱情绑定<?php } ?></span><a href="index.php?n=service&h=commission&t=mebind&bind_id=<?php echo $user_arr['bind_id'];?>">查看&gt;&gt;</a></li>
					<li style="background:none"><span class="r-last06"><?php if(show_total(11,$userid)) { ?> 有<font class="f-ed0a91"><strong><?php echo show_total(11,$userid)?></strong></font>人被您加入黑名单<?php } else { ?>您最近没有使用黑名单<?php } ?></span><a href="index.php?n=service&h=black">查看&gt;&gt;</a></li>
				</ul>
				<ul class="happyway-data-r">
					<li><span class="r-last07"><?php if(show_total(1,$userid)) { ?>有 <font class="f-ed0a91"><strong><?php echo show_total(1,$userid)?></strong></font> 人委托红娘联系您<?php } else { ?>最近没有人委托红娘联系您<?php } ?></span><a href="index.php?n=service&h=commission">查看&gt;&gt;</a></li>
					<li><span class="r-last08"><?php if(show_total(3,$userid)) { ?>您最近收到<font class="f-ed0a91"><strong><?php echo show_total(3,$userid)?></strong></font>朵鲜花<?php } else { ?>您最近没有收到过鲜花<?php } ?></span><a href="index.php?n=service&h=gift">查看&gt;&gt;</a></li>
					<li><span class="r-last09"><?php if(show_total(5,$userid)) { ?> <font class="f-ed0a91"><strong><?php echo show_total(5,$userid)?></strong></font>位会员最近留意过您<?php } else { ?>还没有过人留意过您<?php } ?></span><a href="index.php?n=service&h=mindme&t=iadvertwho">查看&gt;&gt;</a></li>
					<li><span class="r-last10"><?php if(show_total(8,$userid)) { ?> 您有<font class="f-ed0a91"><strong><?php echo show_total(8,$userid)?></strong></font>真实身份请求<?php } else { ?>您最近没有收到真实身份查看请求<?php } ?></span><a href="index.php?n=service&h=request_sms_list">查看&gt;&gt;</a></li>
					<li><span class="r-last11"><?php if(show_total(10,$userid)) { ?> 您有<font class="f-ed0a91"><strong><?php echo show_total(10,$userid)?></strong></font>会员动态<?php } else { ?>您关注的会员没有动态<?php } ?></span><a href="index.php?n=service&h=attention">查看&gt;&gt;</a></li>
				</ul>
			</div>
			<div class="r-center-bottom">
			</div>
		</div> -->
		
		
		<div class="clear"></div>
		<div class="r-like fleft" style="margin-top:8px;">
			<div class="r-like-title">
			<ul><li  id="two1" onclick="setTab('two',1,2)" class="r-like-on">您可能喜欢的人</li><li id="two2" onclick="setTab('two',2,2)" class="r-like-out">您最近关注的人</li></ul></div>
			<div class="r-like-side" >
			</div>
			<div class="r-like-conter">
				<ul class="fleft" id="con_two_1">
				<?php foreach((array)$able_like as $able_likes) {?>
					<li>
						<dl class="r-like-c-data">
							<dt><div><a style="display:block;" href="space_<?php echo $able_likes['uid'];?>.html" target="_blank">
							<?php if($able_likes['mainimg'] && $able_likes['images_ischeck']==1) { ?>       
                       <img src="  <?php if(MooGetphoto($able_likes['uid'],'medium')) echo IMG_SITE.MooGetphoto($able_likes['uid'],'medium');
                      elseif($able_likes['gender'] == '1')echo 'public/system/images/woman_100.gif';
                      else  echo 'public/system/images/man_100.gif';?>" onload="javascript:DrawImage(this,100,125)" width="100" />                
				 <?php } elseif ($able_likes['mainimg']) { ?>
               
            			<?php if($able_likes['gender'] == '1') { ?>
         					<img src="public/system/images/woman_100.gif"  />
         				<?php } else { ?>
          					<img src="public/system/images/man_100.gif" />
          				<?php } ?>
            	
				 <?php } else { ?>
         				<?php if($able_likes['gender'] == '1') { ?>
         					<img src="public/system/images/nopic_woman_100.gif"/>
         				<?php } else { ?>
          					<img src="public/system/images/nopic_man_100.gif" />
          				<?php } ?>
         		<?php } ?></a></div></dt>
							<dd><?php if($able_likes['s_cid'] ==20) { ?><img src="module/index/templates/default/images/zuan0.gif" title="钻石会员" alt="钻石会员"/><?php } elseif ($able_likes['s_cid'] == 30) { ?><img src="module/index/templates/default/images/zuan1.gif" title="高级会员" alt="高级会员"/><?php } else { ?><?php } ?><?php if($able_likes['nickname']) { ?><?php echo MooCutstr($able_likes['nickname'],'10','');?><?php } else { ?>会员<?php echo MooCutstr($able_likes['uid'],'10','');?><?php } ?></dd>
							<dd><?php if($able_likes['birthyear']) { ?><?php echo (gmdate('Y', time()) - $able_likes['birthyear']);?>岁<?php } else { ?>年龄保密<?php } ?></dd>
						</dl>
					</li>
					<?php } ?>
					<p class="r-like-bottom"><a href="<?php echo $search_url;?>" class="f-ed0a91">查看更多&gt;&gt;</a></p>
				</ul>
				<ul class="fleft" id="con_two_2" style="display:none">
				<?php foreach((array)$visitor as $visitors) {?>
                  <?php $send_user1 = leer_send_user1($visitors['visitorid']); ?>
					<li>
						<dl class="r-like-c-data">
							<dt><div><a style="display:block;" href="space_<?php echo $visitors['visitorid'];?>.html" target="_blank">
							 <?php if($send_user1['mainimg'] &&  $send_user1['images_ischeck']=='1' ) { ?>
							  <img class="_photo_ fixphoto" src=" <?php if(MooGetphoto($send_user1['uid'],'mid')) echo IMG_SITE.MooGetphoto($send_user1['uid'],'mid');elseif( $send_user1['gender'] == '1')echo 'public/system/images/woman_100.gif';else  echo 'public/system/images/man_100.gif';?>"   />
							 <?php } elseif ($send_user1['mainimg']) { ?>
										<?php if($send_user1['gender'] == '1') { ?>
											<img src="public/system/images/woman_100.gif" />
										<?php } else { ?>
											<img src="public/system/images/man_100.gif"  />
										<?php } ?>
								
								<?php } else { ?>
										<?php if($send_user1['gender'] == '1') { ?>
											<img src="public/system/images/nopic_woman_100.gif"  />
										<?php } else { ?>
											<img src="public/system/images/nopic_man_100.gif" />
										<?php } ?>
								<?php } ?></a></div></dt>
							<dd><?php if($send_user1['s_cid'] ==20) { ?><a href="index.php?n=payment&h=diamond" target="_blank"><img src="module/index/templates/default/images/zuan0.gif" title="钻石会员" alt="钻石会员"/></a><?php } elseif ($send_user1['s_cid'] == 30) { ?><a href="index.php?n=payment" target="_blank"><img src="module/index/templates/default/images/zuan1.gif" title="高级会员" alt="高级会员"/></a><?php } else { ?><?php } ?><?php $nickname=$send_user1['nickname2']?$send_user1['nickname2']:$send_user1['nickname']?><?php if($nickname) { ?><?php echo MooCutstr($nickname,'10','');?><?php } else { ?>会员<?php echo MooCutstr($send_user1['uid'],'10','');?><?php } ?></dd>
							<dd><?php if($send_user1['birthyear']) { ?><?php echo (gmdate('Y', time()) - $send_user1['birthyear']);?>岁<?php } else { ?>年龄保密<?php } ?></dd>
						</dl>
					</li>
					<?php } ?>
					<p class="r-like-bottom"><a href="?n=service&h=mindme&t=iadvertwho" class="f-ed0a91">查看更多&gt;&gt;</a></p>
				</ul>
				<div class="clear"></div>
				
			</div>
			<div class="r-like-side" style="background-position:bottom;">
			</div>
		</div>
		
		</div>
		<div class="clear"></div>
	</div>
	
	
	<!--content end-->
	<?php include MooTemplate('system/footer','public'); ?><!--foot end-->
</div><!--main end-->

<!-- Modal -->
<div id="myModals" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">系统提示</h3>
  </div>
  <div class="modal-body" style="background:url(module/service/templates/default/images/flower.png) right 100px no-repeat;">
    
  </div>
  
</div>

<script>
	function setTab(name,cursel,n){
	    for(i=1;i<=n;i++){
		   var menu=document.getElementById(name+i);
		   var con=document.getElementById("con_"+name+"_"+i);
		   menu.className=i==cursel?"r-like-on":"r-like-out";
		   con.style.display=i==cursel?"block":"none";
		}
	}
	
	(function(){
		var last_refresh_time,last_login_time;
		var timestamp=parseInt((new Date().getTime())/1000);
		last_refresh_time=getCookie('refresh_myindex');
		last_login_time = getCookie('Moo_last_login_time');
		last_login_time=last_refresh_time?last_refresh_time:last_login_time;
		
		//console.log(last_login_time);
		if(!last_refresh_time || timestamp-last_login_time>14400){
			$.ajax({
				url:'ajax.php?n=login&h=showBrowser',
				dataType:'json',
				success:function(json){
					if(json.length<=0) return false;
					$('#myModals').modal({show:true})
					var html_str='';				
					for(var uid in json){
						var content=[];
						content=json[uid].split(',');
						var posY=-(content[0]-1)*35;
						html_str+='<p style="font-size:14px;margin-bottom:10px;background:url(module/service/templates/default/images/qlj.png) no-repeat 0 '+ posY+'px;height:35px;line-height:35px;padding-left:40px;">';
						var url;
						if(content[0]==1) { url='gift.html';}else if (content[0]==2) { url='leer.html';} else if(content[0]==3) {url='liker.html';}
						html_str+= uid +'已经向你发送了'+content[1]+'，<a href="'+url+'">点击查看</a>';
						html_str+='</p>';
						
					}
					
					html_str+='<p style="margin-top:30px;">【提示】上传形象照，您将获得更多异性会员的关注    <a href="index.php?n=material&h=show">我要上传照片>></a></p>';
					
					$('#myModals .modal-body').append(html_str);
				}
			});
			setCookie('refresh_myindex',timestamp);
			
		}
    })();

</script>


<!--图像修正-->
<script type="text/javascript">
    $(document).ready(function(){
        var theImage = new Image();
		
        $("._photo_").each(function(i,w){
            theImage.src = $(w).attr("src");
            var imageWidth = theImage.width;
            var imageHeight = theImage.height;
            if(imageHeight>170){
                $(w).removeClass('fixphoto');
            }
        })
    });
</script> 
</body>
</html>



<?php if(isset($uid) && $uid) { ?>
	<?php $tid = $uid;?>
<?php } else { ?>
 	<?php $tid = '0';?>
<?php } ?>
<?php if(isset($user_arr['uid']) && $user_arr['uid']) { ?>
 	<?php $fid = $user_arr['uid'];?>
<?php } else { ?>
 	<?php $fid = '0';?>
<?php } ?>
<?php if(isset($serverid) && $serverid) { ?>
	<?php $sid = $serverid;?>
<?php } else { ?>
 	<?php $sid = '0';?>
<?php } ?>

<script type="text/javascript">
var myw;
function pop(){
	if (myw==undefined||myw.closed){
		myw= window.open("index.php?n=chat&h=inline_chat&c=1&fid=<?php echo $fid;?>&tid=<?php echo $tid;?>&sid=<?php echo $sid;?>","<?php echo $fid.'_'.$tid?>","titlebar=no,toolbar=no,location=no,directories=no,menubar=no,scrollbars=no,resizable=no,status=no,width=500, height=440");
	}
	myw.focus();
}

</script>

<script src="module/chat/js/chat.js" type="text/javascript"></script>

<?php $img_host = IMG_SITE;?>

<div class="c-left">
	<div class="c-left-box">
	    <div class="lefttitle">与TA互动</div>	
		<div class="leftbox-in">
			<a  class="modalLink go-know" data-uid="<?php echo $user['uid'];?>"  href="#modalCommission"></a>
			<ul class="leftnav">				
				<li><a  class="modalLink left-list01"  data-uid="<?php echo $user['uid'];?>"  href="#modalLeer"><span>&gt;&gt;</span>送秋波</a></li>
				<li><a  class="modalLink left-list02"  data-uid="<?php echo $user['uid'];?>"  href="#modalSendEmail"><span>&gt;&gt;</span>发邮件</a></li>
				<!-- <li><a  href="index.php?n=service&amp;h=message&amp;t=send&sendtoid=<?php echo $user['uid'];?>" class="left-list02" <?php if($userid == $uid || (!empty($serverid) && $user_arr['sid']==52) ) { ?>onclick='return false'<?php } ?>><span>&gt;&gt;</span>发邮件</a></li> -->
				<li><a  class="modalLink left-list03"  data-uid="<?php echo $user['uid'];?>"  href="#modalGift" ><span>&gt;&gt;</span>送礼物</a></li>
				<li><a  class="modalLink left-list04"  data-uid="<?php echo $user['uid'];?>"  href="#modalLiker"><span>&gt;&gt;</span>加关注</a></li>
				<li><a  href="#" class="left-list05" onclick="<?php if($userid == $uid || (isset($hzn) && $hzn=="hongniangwang")) { ?>return;<?php } else { ?>javascript:window.open('index.php?n=service&h=chat&chatorid=<?php echo $user['uid'];?>', '_blank', 'height=495, width=545, toolbar =no, menubar=no, scrollbars=no, resizable=yes, location=no, status=no')<?php } ?>"><span>&gt;&gt;</span>在线聊天</a> </li>
				<li><a  class="modalLink left-list06"  data-uid="<?php echo $user['uid'];?>"  href="#modalEstimate"><span>&gt;&gt;</span>对TA评价</a></li>
				<li><a  class="modalLink left-list07" data-uid="<?php echo $user['uid'];?>"  href="#modalSMS"> <span>&gt;&gt;</span>获取实名信息</a></li>
				<li><a  class="modalLink left-list08" data-uid="<?php echo $user['uid'];?>"  href="#modalBind" > <span>&gt;&gt;</span>委托红娘绑定TA</a></li>
				<li><a  href="index.php?n=profile&h=report&uid=<?php echo $user['uid'];?>"   class="left-list09" <?php if($userid == $uid || (!empty($serverid) && $user_arr['sid']==52) ) { ?>onclick='return false'<?php } ?> ><span>&gt;&gt;</span>举报该会员</a></li>
			</ul>
		</div>
		<div class="leftbottom"></div>
	</div>
	
	<div class="c-left-box">
	    <div class="lefttitle">为您挑选的会员<span style="height:32px;width:60px;display:inline-block;" class="youableliker">换一组</span></div>
	    <div class="leftbox-in">
		<?php foreach((array)$able_like as $able_likes) {?>
		<dl class="left-likes">
			<dt>
				<div class="left-likes-img">
					<p>
					<a style="display:block;" href="space_<?php echo $able_likes['uid'];?>.html"> 
					   <?php if($able_likes['images_ischeck']=='1'&& $able_likes['mainimg']) { ?>
							  <img id="show_pic" src="<?php   
							  if(MooGetphoto($able_likes['uid'],'index')) echo IMG_SITE.MooGetphoto($able_likes['uid'],'index');
							  elseif(MooGetphoto($able_likes['uid'],'medium')) echo IMG_SITE.MooGetphoto($able_likes['uid'],'medium');
							  elseif($able_likes['gender'] == '1')echo 'public/system/images/woman_100.gif';
							  else  echo 'public/system/images/man_100.gif';
							  ?>" onload="javascript:DrawImage(this,100,125)" width="100"/>
					   <?php } elseif ($able_likes['mainimg']) { ?>
								<?php if($able_likes['gender'] == '1') { ?>
									<img id="show_pic" src="public/system/images/woman_100.gif"  />
								<?php } else { ?>
									<img id="show_pic" src="public/system/images/man_100.gif" />
								<?php } ?>
						
					   <?php } else { ?>
							<?php if($able_likes['gender'] == '1') { ?>
								<img src="public/system/images/nopic_woman_100.gif" />
							<?php } else { ?>
								<img src="public/system/images/nopic_man_100.gif" />
							<?php } ?>
						<?php } ?>
				   </a>
					</p>
				</div>
			</dt>
			
			<dd class="f-b-d73c90"><a href="space_<?php echo $able_likes['uid'];?>.html"  class="f-ed0a91-a"><?php echo $able_likes['nickname']?$able_likes['nickname']:'ID:'.$able_likes['uid']?></a></dd>
			<dd><?php echo $able_likes['birthyear']?(gmdate('Y', time()) - $able_likes['birthyear']).'岁':'年龄保密';?></dd>
			<dd><?php if($able_likes['marriage'] == '0') { ?>婚姻状况:保密<?php } else { ?><script>userdetail('<?php echo $able_likes['marriage'];?>',marriage)</script><?php } ?></dd>
			<dd><?php if($able_likes['education'] == '0') { ?>保密<?php } else { ?><script>userdetail('<?php echo $able_likes['education'];?>',education)</script><?php } ?></dd>
			<dd><?php if($able_likes['salary'] == '0') { ?>薪资:保密<?php } else { ?><script>userdetail('<?php echo $able_likes['salary'];?>',salary1)</script><?php } ?></dd>
		</dl>
		<?php } ?>
	    </div>
		<div class="leftbottom"></div>
	</div>
	
</div><!--content-lift end-->

<?php if($GLOBALS['MooUid']) { ?>
<!--弹出框的遮罩-->
<div class="overlay"></div>
<!--秋波-->
<div id="modalLeer" class="_modal_">
    <h3>给 <span class="nickname"></span> 抛个媚眼吧！</h3>
	<span class="closeL Leer"></span>
	<!--会员信息begin-->
	<div class="zp_zt">
        <div class="zp_tp"></div>
        <div class="uinfo"> 
			<span class="name"></span>
			<span class="touid"></span>
			<span class="address"></span>
		</div>
    </div>
	<!--会员信息end-->
	<div class="zf_cb"></div>
	<input type="hidden" name="total" value="" />
	<div class="leerTips"></div>
	<div class="updown"><a  href="javascript:;" class="down">下一组</a><a  href="javascript:;" class="up">上一组</a></div>
	<a class="leerConfirm fleer " href="#" >赠送秋波</a>
</div>

<!--加关注-->
<div id="modalLiker" class="_modal_">
	<span class="closeL Liker"></span>
	<input type="hidden" name="luid" value='' />
	<div class="zc_xp">
	<p >您确定关注<span class="nickname">幸福网</span>吗？</p><p>关注之后您可以在“我的真爱-<a href="service.html" >我的意中人</a>”里快速找到TA。<br>
				想跟TA有进一步的发展，委托红娘联系TA</p>
		<a class="likerConfirm fliker " href="#" >加关注</a>
    </div>
</div>

<!--查询实名信息-->
<div id="modalSMS" class="_modal_">
	<span class="closeL SMS"></span>
	<input type="hidden" name="luid" value='' />
	<div class="zc_xp">
	<p >您要获取<span class="nickname"></span>的实名信息吗？</p><p>获取成功之后，可以在“我的真爱-<a href="service.html" >真实身份资料</a>”里查找。<br>
				想跟TA有进一步的发展，请委托红娘联系TA</p>
		<a class="SMSConfirm fliker " href="#" >获取TA的信息</a>
    </div>
</div>


<!--委托-->
<div id="modalCommission" class="_modal_">
	<div class="h6_top"><b class="b_v"><img src="public/default/images/commissionBtn.png"></b><span class="closeC"></span></div>
	<div class="tan_k_z">
		<em></em>
		<div class="tan_k_x">
			  <!--内容开始-->
			<div class="lxcss">
			<h6 class="h6_tj">请将您的情况告知红娘</h6>
			<div class="ts">当红娘向<span class="nickname"></span>介绍您时， 您希望红娘着重介绍您的哪些方面？您有没有情况想让红娘帮您去了解？（限 100 字，您还可以输入 <span id="leavings">100</span> 字。）</div> 
			<p><textarea id="commission_content" name="commission_content"  onfocus="javascript:textCounter(this,'leavings',100);" onblur="javascript:textCounter(this,'leavings',100);" onkeyup="javascript:textCounter(this,'leavings',100);"></textarea></p>
			<p class="h50"><a class="commissionConfirm fcommission " href="#" >转告红娘联系TA</a><span class="cTips"></span></p>
			<dl>
				<dt>Question1:什么是委托红娘联系？</dt>
				<dd>“委托红娘联系”就是您委托红娘帮您联系感兴趣的对象。</dd>
				<dt>Question2:红娘怎么帮我联系对方？</dt>
				<dd>红娘收到您的委托后，会通过电话向对方介绍您的优点，并询问他与您的交往意愿。如果对方愿意与您交往，红娘会尽快给您电话反馈，请耐心等候。</dd>
			</dl>
            <input type='hidden' name='cuid' value='' />
			</div> 
		  <!--内容结束-->
		</div>
	</div>
</div>

<!--绑定-->
<div id="modalBind" class="_modal_">
	<div class="h6_top"><b class="b_v"><img src="public/default/images/bindBtn.png"></b><span class="closeC"></span></div>
	<div class="tan_k_z">
		<em></em>
		<div class="tan_k_x">
			  <!--内容开始-->
			<div class="lxcss">
			<h6 class="h6_tj">请将您的情况告知红娘</h6>
			<div class="ts">当红娘向<span class="nickname"></span>介绍您时， 您希望红娘着重介绍您的哪些方面？您有没有情况想让红娘帮您去了解？（限 100 字，您还可以输入 <span id="leavings__">100</span> 字。）</div> 
			<p><textarea id="bind_content" name="bind_content"  onfocus="javascript:textCounter(this,'leavings__',100);" onblur="javascript:textCounter(this,'leavings__',100);" onkeyup="javascript:textCounter(this,'leavings__',100);"></textarea></p>
			<p>请填写您希望的绑定时长：<select id="bind_time" name="bind_time"><option value='6'>6</option><option value='12'>12</option><option value='24'>24</option><option value='30'>30</option><option value='36'>36</option><option value='42'>42</option><option value='48'>48</option><option value='54'>54</option><option value='60'>60</option><option value='66'>66</option><option value='72'>72</option></select>小时</p>
			<p class="h50"><a class="bindConfirm fcommission " href="#" >申请绑定TA</a><span class="bTips"></span></p>
			<dl>
				<dt>Question1:什么是委托红娘绑定？</dt>
				<dd>“委托红娘绑定”就是您委托红娘帮您联系TA与您绑定在一起。</dd>
				<dt>Question2:会员绑定功能介绍？</dt>
				<dd>红娘收到您的绑定申请的时候后，会通过电话征求对方的同意，并询问她与您的绑定意愿。当您们成功绑定之后，任何人将不能向您们发送红娘委托。如果对方愿意与您绑定，红娘会尽快给您电话反馈，请耐心等候。</dd>
			</dl>
            <input type='hidden' name='cuid' value='' />
			</div> 
		  <!--内容结束-->
		</div>
	</div>
</div>

<!--发送礼物-->
<div id="modalGift"  class="_modal_" >
	<div class="h6_top"><b class="b_v"><img src="public/default/images/flowerBtn.png"></b><span class="closeC"></span></div>
	<div class="tan_k_z">
		<em></em>
		<div class="tan_k_v">
			
			<div>
			<ul class="gift">
			     <li><i></i><a href="javascript:;"><span class="h">爱情里，没有规则、没有输赢、没有对错、没有英雄、没有智者、更没有天才……在最美的爱情里，只会有两个傻瓜，牵着彼此的手，傻傻地爱着，傻傻地生活着，傻傻地度过一辈子。</span><img src="data/upload/images/gifts/_gift_01.jpg" width="85" height="85"><p>守候</p></a></li>
				 <li><i></i><a href="javascript:;"><span class="h">我对你的爱，永远不会改变！</span><img src="data/upload/images/gifts/_gift_02.jpg" width="85" height="85"><p>等待爱情</p></a></li>
				 <li><i></i><a href="javascript:;"><span class="h">两片树叶，偶尔会吹到一起。两双眼睛，偶尔会碰到一起。然后，两种情绪纠缠在一起，两张笑容，凝固在一起。遇见你，是我一生的幸运！</span><img src="data/upload/images/gifts/_gift_03.jpg" width="85" height="85"><p>郁金香的沉默</p></a></li>
				 <li><i></i><a href="javascript:;"><span class="h">其实，遇上你就是最幸运的事情。愿这份美好的缘分一直伴随你我！</span><img src="data/upload/images/gifts/_gift_04.jpg" width="85" height="85"><p>幸运你我</p></a></li>
				 <li><i></i><a href="javascript:;"><span class="h">爱是缘分，爱是感动，爱是习惯，爱是忍让，爱是宽容，爱是牺牲，爱是体谅，爱是一种幸福的感觉，爱是一辈子的相依相守，爱是一辈子的心灵承诺。我爱上了你，请允许我把你捧在手心，好吗？</span><img src="data/upload/images/gifts/_gift_05.jpg" width="85" height="85"><p>飞奔不停的爱</p></a></li></ul>
			<div class="clear"></div>
			<p class="h100"><a class="giftConfirm fcommission " href="#" >赠送礼物</a><span class="gTips"></span></p>
			<dl>
				<dt>礼物赠送</dt>
				<dd>您将礼物发送给您心仪的<span class="nickname"></span> ，当您的礼物发送成功后，真爱一生网将通过短信通知<?php echo $TA;?>，让<?php echo $TA;?>第一时间看到您的心意。</dd>
				<dt class="congratulations h">祝福语</dt>
				<dd ></dd>
			</dl>
            <input type='hidden' name='cuid' value='' />
			</div> 

		</div>
	</div>
</div>

<!--评价-->
<div id="modalEstimate" class="_modal_">
	<div class="h6_top"><b class="b_v"><img src="public/default/images/estimateBtn.png"></b><span class="closeC"></span></div>
	<div class="tan_k_z">
		<em></em>
		<div class="tan_k_x">
			  <!--内容开始-->
			<div class="lxcss">
			<h6 class="h6_tj">我对<span class="nickname"></span>的评价</h6>
			<div class="ts">您的评价会提高您的诚信度，对方也会收到您的评价（限 100 字，您还可以输入 <span id="leavings_">100</span> 字。）</div> 
			<p><textarea id="estimate_content" name="estimate_content"  onfocus="javascript:textCounter(this,'leavings_',100);" onblur="javascript:textCounter(this,'leavings_',100);" onkeyup="javascript:textCounter(this,'leavings_',100);"></textarea></p>
			<p class="h50"><a class="estimateConfirm fcommission " href="#" >评价TA</a><span class="eTips"></span></p>
			
            <input type='hidden' name='cuid' value='' />
			</div> 
		  <!--内容结束-->
		</div>
	</div>
</div>


<!--发邮件-->
<div id="modalSendEmail" class="_modal_">
	<div class="h6_top"><b class="b_v"><img src="public/default/images/sendEmailBtn.png"></b><span class="closeC"></span></div>
	<div class="tan_k_z">
		<em></em>
		<div class="tan_k_x">
			  <!--内容开始-->
			<div class="lxcss_">
			<h6 class="h6_tj">收件人：<span class="nickname"></span></h6>
			<div>标题：<input type="text" name="emailTitle" id="emailTitle" onfocus="javascript:textCounter(this,'leavingst',20);" onblur="javascript:textCounter(this,'leavingst',20);" onkeyup="javascript:textCounter(this,'leavingst',20);" />（限20字,还可输入<span id="leavingst">20</span>字）</div>
			<div class="ts">邮件内容：（限 200 字，您还可以输入 <span id="leavingss">200</span> 字。）</div> 
			<p><textarea id="sendEmail_content" name="sendEmail_content"  onfocus="javascript:textCounter(this,'leavingss',200);" onblur="javascript:textCounter(this,'leavingss',200);" onkeyup="javascript:textCounter(this,'leavingss',200);"></textarea></p>
			<p class="h50"><a class="sendEmailConfirm fcommission " href="#" >发送</a><span class="emailTips"></span></p>
			
            <input type='hidden' name='cuid' value='' />
			</div> 
		  <!--内容结束-->
		</div>
	</div>
</div>
<?php } ?>

<script src="public/system/js/jquery.modal.min.js" type="text/javascript"></script>
<?php if($GLOBALS['MooUid']) { ?><script src="public/system/js/jquery.modal.site.js" type="text/javascript"></script><?php } ?>
<script type="text/javascript">
	//发送秋波
	$('a[class^=leerConfirm]').click(function(){
	    var l=$('input[name=leerinfo]:checked').val();
		var info=$('input[name=leerinfo]:checked').next('label').html();
		if(typeof l == 'undefined') {
		    $('.leerTips').html('请选择要向对方发送的告白！！！');
		}else{
		    var uid=$('.uinfo .touid').html().replace(/ID:/,'');
		    $.ajax({
				url:'ajax.php?n=service&h=sendleer',
				data:{uid:uid,info:info},
				type:'post',
				dataType:'text',
				success:function(str){
				    console.log(str);
					if(str=='sameone'){
					    alert('自己不能给自己发送秋波!');	
					}else if(str=='simulate'){
					    alert('不能模拟!');
					}else if(str=='shield'){
					    alert('对方已把您拉入黑名单');
					}else if(str=='gender'){
					    alert('同性之间不能发送秋波');
					}else if(str=='alreadySend'){
					    alert('已经向这个会员发送过秋波了！');
					}else if(str=='limited'){
					    alert('超过当日秋波的发送数量!');
					}else if(str=='telNo'){
					    location.href='index.php?n=myaccount&h=telphone';
					}else{
					    alert('发送成功！');
					}
					$('.closeL').trigger('click');
				}
				
			});
		    
		}
	});
	
	//发送委托
	$('a[class^=commissionConfirm]').click(function(){
	    var content=$.trim($('#commission_content').val());
		if(!content) {
		    $('.cTips').html('请填写您要转达的信息给红娘！'); 
		}else{
		    var uid=$('input[name="cuid"]').val();
		    $.ajax({
				url:'ajax.php?n=service&h=sendCommission',
				data:{uid:uid,content:content},
				type:'post',
				dataType:'text',
				success:function(str){
					if(str=='sameone'){
					    alert('自己不能向自己发送委托!');	
					}else if(str=='simulate'){
					    alert('不能模拟!');
					}else if(str=='shield'){
					    alert('对方已把您拉入黑名单');
					}else if(str=='gender'){
					    alert('同性之间不能发送委托');
					}else if(str=='closeInfo'){
					    alert('对方会员资料已经关闭了！');
					}else if(str=='alreadySend'){
					    alert('已经向这个会员发送过委托了！');
					}else if(str=='limited'){
					    alert('超过当日委托的发送数量（3个）!');
					}else if(str=='telNo'){
					    location.href='index.php?n=myaccount&h=telphone';
					}else{
					    alert('发送成功！');
					}
					$('.closeC').trigger('click');
				}
			});
		}
	});
	
	
	//申请绑定
	$('a[class^=bindConfirm]').click(function(){
	    var content=$.trim($('#bind_content').val());
		var time =$('#bind_time').val();
		if(!content) {
		    $('.bTips').html('请填写您要绑定的信息！'); 
		}else{
		    var uid=$('input[name="cuid"]').val();
		    $.ajax({
				url:'ajax.php?n=service&h=applyBind',
				data:{uid:uid,content:content,time:time},
				type:'post',
				dataType:'text',
				success:function(str){
					if(str=='sameone'){
					    alert('自己不能向自己发送绑定!');	
					}else if(str=='simulate'){
					    alert('不能模拟!');
					}else if(str=='you'){
					    alert('你已经在绑定状态中了！');
					}else if(str=='other'){
					    alert('对方已经在绑定状态中了！');
					}else if(str=='shield'){
					    alert('对方已把您拉入黑名单');
					}else if(str=='gender'){
					    alert('同性之间不能发送绑定');
					}else if(str=='closeInfo'){
					    alert('对方会员资料已经关闭了！');
					}else if(str=='alreadySended'){
					    alert('您已经发送过了绑定！');
					}else if(str=='alreadyBinded'){
					    alert('您已经和对方在绑定状态中！');
					}else if(str=='telNo'){
					    location.href='index.php?n=myaccount&h=telphone';
					}else{
					    alert('发送成功！');
					}
					$('.closeC').trigger('click');
				}
			});
		}
	});
	
	
	//评价
	$('a[class^=estimateConfirm]').click(function(){
	    var content=$.trim($('#estimate_content').val());
		if(!content) {
		    $('.eTips').html('请填写您对TA的评价内容！'); 
		}else{
		    var uid=$('input[name="cuid"]').val();
		    $.ajax({
				url:'ajax.php?n=service&h=sendEstimate',
				data:{uid:uid,content:content},
				type:'post',
				dataType:'text',
				success:function(str){
					if(str=='sameone'){
					    alert('自己不能向自己评价!');	
					}else if(str=='simulate'){
					    alert('不能模拟!');
					}else if(str=='shield'){
					    alert('对方已把您拉入黑名单');
					}else if(str=='gender'){
					    alert('同性之间不能评价');
					}else if(str=='closeInfo'){
					    alert('对方会员资料已经关闭了！');
					}else if(str=='alreadySend'){
					    alert('已经对这个会员评价过了！');
					}else if(str=='limited'){
					    alert('超过当日评价的发送数量（5个）!');
					}else if(str=='telNo'){
					    location.href='index.php?n=myaccount&h=telphone';
					}else{
					    alert('评价成功！');
					}
					$('.closeC').trigger('click');
				}
			});
		}
	});
	
	
	//发邮件
	$('a[class^=sendEmailConfirm]').click(function(){
	    var title=$.trim($('#emailTitle').val());
		var content=$.trim($('#sendEmail_content').val());
		if(!content || !title) {
		    $('.emailTips').html('请务必填写邮件的标题及内容！'); 
		}else{
		    var uid=$('input[name="cuid"]').val();
		    $.ajax({
				url:'ajax.php?n=service&h=sendEmail',
				data:{uid:uid,content:content,title:title},
				type:'post',
				dataType:'text',
				success:function(str){
					if(str=='sameone'){
					    alert('自己不能向自己发邮件!');	
					}else if(str=='simulate'){
					    alert('不能模拟!');
					}else if(str=='shield'){
					    alert('对方已把您拉入黑名单');
					}else if(str=='upgrade'){
					    alert('请升级高级会员！');
						location.href='upgrade.html';
					}else if(str=='gender'){
					    alert('同性之间不能评价');
					}else if(str=='closeInfo'){
					    alert('对方会员资料已经关闭了！');
					}else if(str=='limited'){
					    alert('超过当日发送邮件的发送数量!');
					}else if(str=='telNo'){
					    location.href='index.php?n=myaccount&h=telphone';
					}else{
					    alert('发送邮件成功！');
					}
					$('.closeC').trigger('click');
				}
			});
		}
	});
	//加关注
	$('a[class^=likerConfirm]').click(function(){
		var uid=$('input[name="luid"]').val();
		$.ajax({
			url:'ajax.php?n=service&h=addLiker',
			data:{uid:uid},
			type:'post',
			dataType:'text',
			success:function(str){
				if(str=='sameone'){
					alert('自己不能关注自己!');	
				}else if(str=='simulate'){
					alert('不能模拟!');
				}else if(str=='shield'){
					alert('对方已把您拉入黑名单');
				}else if(str=='gender'){
					alert('同性之间不能关注');
				}else if(str=='closeInfo'){
					alert('对方会员资料已经关闭了！');
				}else if(str=='alreadySend'){
					alert('已经关注过这个会员了！');
				}else if(str=='limited'){
					alert('超过当日关注的数量（20个）!');
				}else if(str=='telNo'){
					location.href='index.php?n=myaccount&h=telphone';
				}else{
					alert('关注成功！');
				}
				$('.closeL').trigger('click');
			}
		});
	
	});
	
	//获取实名信息
	$('a[class^=SMSConfirm]').click(function(){
		var uid=$('input[name="luid"]').val();
		$.ajax({
			url:'ajax.php?n=service&h=getSMS',
			data:{uid:uid},
			type:'post',
			dataType:'text',
			success:function(str){
				if(str=='sameone'){
					alert('您还要获取您自己的信息？');	
				}else if(str=='noHigher'){
				    alert('您不是钻石或高级会员，不能请求获取对方的信息，快升级为钻石或高级会员吧');
					location.href='index.php?n=payment&h=diamond';
				}else if(str=='nosms'){
				    alert('您未进行身份通认证，请先进行身份通认证');
				    location.href='index.php?n=myaccount&h=smsindex';
				}else if(str=='simulate'){
					alert('不能模拟!');
				}else if(str=='shield'){
					alert('对方已把您拉入黑名单');
				}else if(str=='gender'){
					alert('同性之间不能查询信息');
				}else if(str=='closeInfo'){
					alert('对方会员资料已经关闭了！');
				}else if(str=='limited'){
					alert('超过当日获取会员信息的数量（3个）!');
				}else if(str=='telNo'){
					location.href='index.php?n=myaccount&h=telphone';
				}else{
					alert('获取TA的信息成功！');
				}
				$('.closeL').trigger('click');
			}
		});
	
	});
	
	
	//发送礼物
	$('a[class^=giftConfirm]').click(function(){
	    var content=$.trim($('.congratulations').next('dd').html());
		if(!content) {
		    $('.gTips').html('请选择要发送的礼物给TA！'); 
		}else{
		    var uid=$('input[name="cuid"]').val();
		    $.ajax({
				url:'ajax.php?n=service&h=sendGift',
				data:{uid:uid,content:content},
				type:'post',
				dataType:'text',
				success:function(str){
					if(str=='sameone'){
					    alert('自己不能向自己发送委托!');	
					}else if(str=='simulate'){
					    alert('不能模拟!');
					}else if(str=='shield'){
					    alert('对方已把您拉入黑名单');
					}else if(str=='gender'){
					    alert('同性之间不能发送委托');
					}else if(str=='closeInfo'){
					    alert('对方会员资料已经关闭了！');
					}else if(str=='alreadySend'){
					    alert('已经向这个会员发送过礼物了！');
					}else if(str=='nomoregifts'){
					    locaion.href='index.php?n=service&h=gift&t=getmorerose';
					}else if(str=='limited'){
					    alert('超过当日礼物的发送数量（3个）!');
					}else if(str=='telNo'){
					    location.href='index.php?n=myaccount&h=telphone';
					}else{
					    alert('发送成功！');
					}
					$('.closeC').trigger('click');
				}
			});
		}
	});
	
	//清空秋波表白警告语
	$('.zf_cb').on('click','input[name=leerinfo]',function(){
	    $('.leerTips').html('');
	});
	//清空委托话语
	$('p').on('click','#commission_content',function(){
	    $('.cTips').html('');
	});
	//清空绑定话语
	$('p').on('click','#bind_content',function(){
	    $('.bTips').html('');
	});
	//清空邮件警告语
	$('#emailTitle,#sendEmail_content').focus(function(){
	    $('.emailTips').html('');
	});
	
	
	//礼物点击
	$('.gift li').on('click',function(){
	    $(this).siblings().find('i').removeClass('checkP');
	    var congratulations=$(this).find('span').html();
		$('.gTips').html('');
	    if($(this).children('i').hasClass('checkP')){
		    $(this).children('i').removeClass('checkP');
			$('.congratulations').next('dd').html('');
		}else{
		    $('.congratulations').removeClass('h').next('dd').html(congratulations);
			$(this).children('i').addClass('checkP');
		}
	});
	
	var page=1; //initialization
	//上一页
	$('.up').on('click',function(){
	    if(page>1)  page--;
        getPageSH(page);
		sendLeerInfo(page);
	});
	
	//下一页
	$('.down').on('click',function(){ 
	    var total=$('input[name="total"]').val();
		if(page<total) page++;
		getPageSH(page);
		sendLeerInfo(page);
	});
	
	
	function getPageSH(page){
	    var total=$('input[name="total"]').val();
	    if(page==1) {$('.up').addClass('h')} else {$('.up').removeClass('h')};
		if(page>=total){ $('.down').addClass('h')} else {$('.down').removeClass('h')};
	}
	
	
 
    //弹出“发送秋波”界面
	$('a[href="#modalLeer"]').on('click',function(){
	    <?php if($GLOBALS['MooUid']) { ?>
			var img=$('.myself p').html();
			$('.zp_tp').html('<p style="width:110px;height:138px;overflow:hidden;padding-top:5px;">'+img+'</p>');
			var nickname=$('.vmcd').find('.myname').html();
			$('.nickname').html($.trim(nickname));
			$('.uinfo .name').html($.trim(nickname));
			$('.uinfo .touid').html('ID:'+$(this).attr('data-uid'));
			var address=$('.vmcd').find('span.address').html();
			if(address) address=address.replace(/<script[^>]*>(.|\n)*?<\/script>/ig,'');
			$('.uinfo .address').html(address);
			sendLeerInfo(page);
		<?php } else { ?>
		    openLogin();
		<?php } ?>
	});
	
    
	
	//弹出“委托发送”界面
	$('a[href="#modalCommission"]').on('click',function(){
	    <?php if($GLOBALS['MooUid']) { ?>
		    var nickname=$('.vmcd').find('.myname').html();
			$('.nickname').html($.trim(nickname));
			
			var uid=$(this).attr('data-uid');
			$('input[name="cuid"]').val(uid);
		<?php } else { ?>
		    openLogin();
		<?php } ?>
	});
	
	//弹出“申请绑定”界面
	$('a[href="#modalBind"]').on('click',function(){
	    <?php if($GLOBALS['MooUid']) { ?>
		    var nickname=$('.vmcd').find('.myname').html();
			$('.nickname').html($.trim(nickname));
			
			var uid=$(this).attr('data-uid');
			$('input[name="cuid"]').val(uid);
		<?php } else { ?>
		    openLogin();
		<?php } ?>
	});
	
	//弹出“加关注”界面
	$('a[href="#modalLiker"]').on('click',function(){
	    <?php if($GLOBALS['MooUid']) { ?>
		    var nickname=$('.vmcd').find('.myname').html();
			$('.nickname').html($.trim(nickname));
			var uid=$(this).attr('data-uid');
			$('input[name="luid"]').val(uid);
		<?php } else { ?>
		    openLogin();
		<?php } ?>
	});
	
	
	//弹出“获取实名信息”界面
	$('a[href="#modalSMS"]').on('click',function(){
	    <?php if($GLOBALS['MooUid']) { ?>
		    var nickname=$('.vmcd').find('.myname').html();
			$('.nickname').html($.trim(nickname));
			var uid=$(this).attr('data-uid');
			$('input[name="luid"]').val(uid);
		<?php } else { ?>
		    openLogin();
		<?php } ?>
	});
	
	//弹出“评价”界面
	$('a[href="#modalEstimate"]').on('click',function(){
	    <?php if($GLOBALS['MooUid']) { ?>
		    var nickname=$('.vmcd').find('.myname').html();
			$('.nickname').html($.trim(nickname));
			var uid=$(this).attr('data-uid');
			$('input[name="cuid"]').val(uid);
		<?php } else { ?>
		    openLogin();
		<?php } ?>
	});
	
	
	//弹出“发邮件”界面
	$('a[href="#modalSendEmail"]').on('click',function(){
	    <?php if($GLOBALS['MooUid']) { ?>
		    var nickname=$('.vmcd').find('.myname').html();
			$('.nickname').html($.trim(nickname));
			var uid=$(this).attr('data-uid');
			$('input[name="cuid"]').val(uid);
		<?php } else { ?>
		    openLogin();
		<?php } ?>
	});
	
	
	//弹出“送礼物”界面
	$('a[href="#modalGift"]').on('click',function(){
	    <?php if($GLOBALS['MooUid']) { ?>
		    var nickname=$('.vmcd').find('.myname').html();
			$('.nickname').html($.trim(nickname));
			
			var uid=$(this).attr('data-uid');
			$('input[name="cuid"]').val(uid);
		<?php } else { ?>
		    openLogin();
		<?php } ?>
	});
	
	//发送秋波表白语句
	function sendLeerInfo(page){
		$.ajax({
			url:'ajax.php?n=service&h=getleerinfo',
			data:{page:page},
			type:'get',
			dataType:'json',
			success:function(json){
			    var str='';
			    $('input[name="total"]').val(Math.ceil(json['total']/5));
				delete json.total;
				for(var i in json){
					str+='<p><input type="radio" name="leerinfo" value="'+i+'" id="leer'+i+'" /><label for="leer'+i+'">'+json[i]['content']+'</label></p>';
				}
				$('.zf_cb').html(str);
			}
		});
		
    }
	
	
	
	
	//字数
	function textCounter(field,leavingsfieldId,maxlimit) { 
		var leavingsfield = document.getElementById(leavingsfieldId);
		if (field.value.length > maxlimit) 
		{
			field.value = field.value.substring(0, maxlimit);
			alert(" 限" + maxlimit + "字内！");
		} else { 
			leavingsfield.innerHTML=maxlimit - field.value.length;
		}
	}
	
	var offset=0;
	$('.youableliker').on('click',function(event){
	    var that=$(this);
		var len=$('.left-likes').attr('data-len');
		if(typeof len=='undefined') len=0;
		if(offset>parseInt(len/5)) offset=0;
	    //var offset=Math.round(Math.random()*100);//产生一个随机整数
	    $.ajax({
		    url:'ajax.php?n=space&h=youAbleLiker',
			data:{offset:offset},
			type:'POST',
			dataType:'text',
			success:function(str){
			    //console.log(str);
				that.parent().next('.leftbox-in').html(str);

			}
		});
		offset++;
		stopBubble(event);
	});
	
	function stopBubble(evt){
	    var e=(evt)?evt:window.event;  
		if (window.event) {  
		    e.cancelBubble=true;  
		} else {  
		    //e.preventDefault();  
		    e.stopPropagation();  
		}  
	}
	
</script> 
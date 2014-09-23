<div class="c-lift-content">
		<div class="c-lift-title">我的联系情况</div>
		<div class="c-lift-content-in">
			<ul class="left-list">
				<li><a href="message.html" class="left-list01 <?php if($_GET['h']=='message') { ?>fb<?php } ?>" >我的邮件</a></li>
				<li><a href="commission.html" class="left-list02  <?php if(($_GET['h']=='commission'&&empty($_GET['t']))||(isset($_GET['t']) && $_GET['t']=='getmycontact')) { ?>fb<?php } ?>" >我的委托</a></li>
				<li><a href="leer.html" class="left-list03 <?php if($_GET['h']=='leer') { ?>fb<?php } ?>" >我的秋波</a></li>
				<li><a href="gift.html" class="left-list04 <?php if($_GET['h']=='gift') { ?>fb<?php } ?>" >我的礼物</a></li>
				<li><a href="liker.html" class="left-list05 <?php if($_GET['h']=='liker') { ?>fb<?php } ?>" >我的意中人</a></li>
				<li><a href="browser.html" class="left-list06 <?php if($_GET['h']=='mindme') { ?>fb<?php } ?>" >我留意过谁</a></li>
				<li><a href="mycomment.html" class="left-list07 <?php if($_GET['h']=='mycomment' || $_GET['h']=='mycommentwho') { ?>fb<?php } ?>" >我的评价</a></li>
				<li><a href="realinfo.html" class="left-list08 <?php if($_GET['h']=='request_sms_list'||$_GET['h']=='send_request_sms_list') { ?>fb<?php } ?>" >真实身份资料</a></li>
				<li><a href="bind<?php echo $user_arr['bind_id'];?>.html" class="left-list09 <?php if($_GET['h']=='commission'&&$_GET['t']=='mebind') { ?>fb<?php } ?>" >爱情绑定</a></li>
				<li><a href="attention.html" class="left-list10 <?php if($_GET['h']=='attention') { ?>fb<?php } ?>" >关注会员动态</a></li>
				<li><a href="black.html" class="left-list11 <?php if($_GET['h']=='black') { ?>fb<?php } ?>" >我的黑名单</a></li>
			</ul>
			<div class="c-lift-title">其他功能</div>
			<div class="clear"></div>
			<ul class="left-list">
				<li><a href="lovetest.html" target="_blank" class="left-list12">心理测试</a></li>
				<li><a href="safetyguide.html" target="_blank" class="left-list13">安全征婚指南</a></li>
				<li><a href="advices.html" target="_blank" class="left-list14">投诉建议</a></li>
				<li><a href="index.php?n=payment&h=diamond" target="_blank" class="left-list15">会员服务</a></li>
				<li><a href="cityStar.html" target="_blank" class="left-list16">城市之星</a></li>
				<!-- <li><a href="index.php?n=hnintro" target="_blank" class="left-list17">红娘介绍</a></li> -->
				<li><a href="compensate.html" target="_blank" class="left-list18">约会基金赔偿计划</a></li>
			</ul>
		</div>
		<div class="c-lift-bottom"></div>
		</div><!--c-lift-content-->
		<div class="left-tips">
		    <img src="module/service/templates/default/images/getsave.gif" border="0" usemap="#Map"  />
			<map name="Map" id="Map">
			  <area shape="rect" coords="78,76,136,101" href="advices.html" />
			</map>
		</div>

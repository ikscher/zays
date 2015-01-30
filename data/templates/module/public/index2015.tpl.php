<!DOCTYPE html >
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="keywords" content="红娘,征婚,征婚网,婚介,婚恋,婚姻,交友,交友网,相亲,征婚,姻缘,牵线,寻爱">
<meta name="description" content="真爱一生由专业的红娘团队以及情感专家组成的严肃正规的婚恋网站, 针对城市白领提供专业的婚恋服务，帮助单身会员组成美满幸福家庭。">
<link rel="shortcut icon" href="public/default/images/favicon.ico" type="image/x-icon"/>
<link rel="icon" href="public/default/images/favicon.ico" type="image/x-icon">
<title>真爱一生网</title>

<link href="module/index/templates/default/css/index2015.css" type="text/css" rel="stylesheet" />
<link href="module/index/templates/default/css/jquery.vector.map.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="module/index/templates/default/js/sys.min.js"></script>
<script type="text/javascript">
   window.onload=function(){
		  $('#slides').slidesjs({
			width: 688,
			height: 262,
			play: {
			  active: true,
			  auto: true,
			  interval: 4000,
			  swap: true
			}
		  });
		
   };
   
    //检索验证
	function checkSearchForm(){

		var age1 = $("select[name=age_start]").val();
		var age2 = $("select[name=age_end]").val();
		if( age1 == 0 && age2 == 0) {
		  alert("请选择年龄");
		  $("select[name=age_start]").focus();
		  return false;
		}
		if(age1!=null && age1!='' && age1==0 && age2!=null && age2!='' && age2!=0) {
		  alert("请选择年龄下限");
		  $("select[name=age_start]").focus();
		  return false;
		}
		if(age1!=null && age1!='' && age1!=0 && age2!=null && age2!='' && age2==0) {
		  alert("请选择年龄上限");
		  $("select[name=age_end]").focus();
		  return false;
		}
		if(age1!=null && age1!='' && age1!=0 && age2!=null && age2!='' && age2!=0 && age1>age2) {
		  alert("您选择的年龄范围不正确，请重新选择");
		  $("select[name=age_start]").focus();
		  return false;
		}

		$('.searchbtn').val('正在提交...');
		$('.searchbtn').attr('disabled','disabled');
			
		
		return true;
	}

</script>
</head>
<body>
<div class="header nav_bgc">
  <div class="nav clearfix">
    <div class="logo"></div>
    <ul>
      <li><a href="index.html">首页</a></li>
      <li><a href="service.html">空间</a></li>
      <li><a href="search.html">搜索</a></li>
      <li><a href="upgrade.html">服务</a></li>
      <li><a href="material.html">征婚</a></li>
	  <li><a href="http://m.zhenaiyisheng.cc">手机版</a></li>
    </ul>
	<div class="showmap"></div>
	<div id="container" class="map">
    </div>
  </div>
</div>
<div class="main">
  <div class="clearfix">
    <div class="container">
      <div id="slides"><a href="upgrade.html"><img src="module/index/templates/default/images/index2015/coursel01.jpg" /></a><img src="module/index/templates/default/images/index2015/coursel02.jpg" /></div>
    </div>
    <div class="login">
	  <?php if($GLOBALS['MooUid']) { ?>

			<div class="logined clearfix">
			    <p>您的用户名是：<?php echo $GLOBALS['MooUserName']?MooCutstr($GLOBALS['MooUserName'],16):$user_arr['nickname'];?></p>
			    <div>
					
					<p>您的ID是：<?php echo $GLOBALS['MooUid'];?></p>
					<p><a class="f666" href="index.php?n=service&h=message">最新邮件(<?php echo $GLOBALS['new_email_num']?>) </a></p>
					<p><a class="f666" href="service.html">进入我的红娘&gt;&gt;</a></p>
					<p><a class="f666" href="logout.html">安全退出</a></p>
				</div>
				<div class="pl15"><img src="<?php echo $mainimg;?>" width="120" height="150" /></div>
			</div>
	 <?php } else { ?>
		  <form  name="formLogin"   action="index.php?n=index&h=submit" method="post" >
			<span class="login_header">快速登录</span>
			<div class="login_c0"><span>登录账号：</span>
			  <input  class="za_input" type="text" name="username" id="username" value=""  />
			</div>
			<div class="login_c0"><span>登录密码：</span>
			  <input  class="za_input" type="password" name="password" id="password" value="" />
			</div>
			<div class="login_c1">
			  <input type="submit" class="loginbtn" name="btnLogin" value="立即登录，找对象">
			</div>
			<div class="login_c0">
			  <input type="checkbox"  name="autoLogin"  id="autologin" />
			  <label for="autologin">自动登录</label>
			    <span class="fakeuname" >ID/手机号/email</span> 
				<span class="fakepwd" >密码</span> 
				<span class="errorTips" style="display:none"></span>
				<span class="errorTips_" style="display:none"></span>
			  <span class="loginqq"><a href="login_qq.html"><img src="module/index/templates/default/images/index2015/login_qq.png"></a></span> <a  class="f666" href="forget.html">忘记密码</a></div>
		  </form>
		<?php } ?>
    </div>
  </div>
  <div class="recommendMember">
    <div class="samecity"><span class="samecity_">会员推荐</span> <span class="tel400"><img src="module/index/templates/default/images/index2015/tg_head.png" /></span> </div>
    <div class="samecityMember">
      <ul>
	    <?php foreach((array)$userList as $k=>$u) {?>
        <li>
		  <a href="space_<?php echo $u['uid'];?>.html" class="fff">
          <div class="flipcard">
            <div class="back face" style="left:100%">
			  
              <div class="proIntro">
                <h5><?php echo $u['nickname'];?></h5>
                <div class="text"> 学历： <script>sys.getEducation(<?php echo $u['education'];?>);</script> <br>
                  <nobr>职业：<script>sys.getOccupation(<?php echo $u['occupation'];?>);</script></nobr> <br>
                  月薪：<script>sys.getSalary(<?php echo $u['salary'];?>);</script><br>
                  地区：<script>sys.getProvinceValue(<?php echo $u['province'];?>);sys.getCityValue(<?php echo $u['city'];?>);</script> <p class="mt10">认识 <?php echo $u['gender']?'她':'他';?> >></p></div>
              </div>
            </div>
            <div class="front face" style="left: 0px;"><img src="<?php  echo IMG_SITE.MooGetphoto($u['uid'],'mid');?>" width="120" height="150"></div>
          </div>
		  </a>
        </li>
        <?php }?>
      </ul>
    </div>
    <div class="upgradeMember">
      <ul>
	    <?php foreach((array)$textInfo_ as $k_=>$v_) {?>
        <li><span><?php echo MooCutstr(str_replace('您的','',$v_['content']),40,'');?></span></li>
        <?php }?>
      </ul>
    </div>
  </div>
  <div class="search"> 
    <form id="search" name="search" method="get" action="index.php" onSubmit="return checkSearchForm();">
	<input type="hidden" name="n" value="search">
	<input type="hidden" name="h" value="quick">
	<input name="search_type" type="hidden" value="1">
    <span class="isearch">我要找：</span>
    <select name="gender">
      <option value="0" <?php if($user_arr['gender'] == '1') { ?> selected <?php } ?> >男</option>
      <option value="1" <?php if($user_arr['gender'] == '0' || empty($user_arr['gender'])) { ?> selected <?php } ?>>女</option>
    </select>
    <label>年龄</label>
    <span id="age_start"></span>
    至
    <span id="age_end"></span>
    <label>地区</label>
    <span id="province_"></span>
    <span id="city_"></span>
    <input type="checkbox" id="chkfoto" name="photo" value="1" checked="checked"/>
    <label for="hasPhoto">有照片</label>
	<input id="quick_search" name="quick_search"  type="hidden" value="search">
    <input type="submit" name="sbt_search" class="searchbtn" value="开始搜索" />
    <span class="advanceSearch"><a class="f666" href="index.php?n=search&h=super">高级搜索>></a></span>
  </div>
  <div class="vip clearfix">
    <div class="vipMember">
      <ul>
	    <?php foreach((array)$vipuser as $k=>$vu) {?>
        <li>
		  <a  class=" ffff" uid="57088748" href="space_<?php echo $vu['uid'];?>.html" target="_blank">
          <div class="flipcard">
            <div class="back face" style="left:100%">
              <div class="proIntro">
                <h5><?php echo $vu['nickname'];?></h5>
                <div class="text"> 学历：<script>sys.getEducation(<?php echo $vu['education'];?>);</script><br>
                  <nobr>职业：<script>sys.getOccupation(<?php echo $vu['occupation'];?>);</script></nobr> <br>
                  月薪：<script>sys.getSalary(<?php echo $vu['salary'];?>);</script><br>
                  地区：<script>sys.getProvinceValue(<?php echo $vu['province'];?>);sys.getCityValue(<?php echo $vu['city'];?>);</script><p class="mt10">认识 <?php echo $vu['gender']?'她':'他';?>>></p></div>
              </div>
            </div>
            <div class="front face" style="left: 0px;"><a href="#"><img src="<?php  echo IMG_SITE.MooGetphoto($vu['uid'],'mid');?>" width="120" height="150"></a></div>
          </div>
		  </a>
        </li>
		<?php }?>
        
      </ul>
    </div>
    <div class="bulltinboard">
      <ul>
        <li>你们网站和其他网站有什么区别呢？<a href="faq.html">点击查看</a></li>
		<li>通过你们的网站一定能找到对象吗？<a href="faq.html">点击查看</a></li>
		<li>为什么要在你们网站上找对象呢？<a href="faq.html">点击查看</a></li>
		<li>升级后我还需要支付其他的费用吗？<a href="faq.html">点击查看</a></li>
		<li>我能不能先见面再升级会员？<a href="faq.html">点击查看</a></li>
		<li>我怎么得到女孩子的联系方式？<a href="faq.html">点击查看</a></li>
		<li>给你们去电话，为什么有时候打不通？<a href="faq.html">点击查看</a></li>
		<li>为什么你们网站有负面的信息？<a href="faq.html">点击查看</a></li>
		<li>网站会员是不是真实的？<a href="faq.html">点击查看</a></li>
		<li>升级VIP会员后你们是怎么服务的？<a href="faq.html">点击查看</a></li>
		<li>服务到期了还没有找到对象怎么办？<a href="faq.html">点击查看</a></li>
		<li>怎么查询网站的正规性呢？<a href="faq.html">点击查看</a></li>
		<li>升级VIP会员的价格是否可以优惠一些？<a href="faq.html">点击查看</a></li>
      </ul>
    </div>
  </div>
  <div class="story">
    <div class="successfulstory"><span class="successfulstory_">晒幸福</span></div>
    <ul>
	  <?php foreach((array)$storys as $k_=>$s) {?>
      <li class="ShadowA"> <a href="story<?php echo $s['sid'];?>.html"><img src="<?php echo MooGetstoryphoto($s['sid'],$s['uid'],'medium');?>" width="190"/></a>
        <div class="ShadowA-Hover mosaic-overlay" > <span class="ShadowA-HoverBg"></span>
          <div class="ShadowA-Hover-p">
            <p><?php echo $s['name1'];?>&<?php echo $s['name2'];?></p>
            <p class="colorccc"><?php echo date('Y.m.d',$s['story_date']);?></p>
          </div>
        </div>
      </li>
      <?php }?>
    </ul>
  </div>
</div>
<div class="footer">
  <div class="g">品牌：专业婚恋服务&nbsp; 专业：庞大的资深红娘队伍&nbsp; 真实：诚信会员验证体系&nbsp; </div>
  <div class="g">Copyright@2015 真爱一生网.All Right Reserved.<a href="http://www.miitbeian.gov.cn/" target="_blank">皖ICP备14002819号</a> </div>
  <div class="g">客服热线：400-8787-920 （周一至周日：9：00-21：00）客服信箱：kefu@zhenaiyisheng.cc</div>
</div>
<script type="text/javascript" src="public/system/js/jquery-1.8.3.min.js" ></script>
<script type="text/javascript" src="module/index/templates/default/js/jquery.slides.min.js"></script>
<script type="text/javascript" src="module/index/templates/default/js/json2.min.js"></script>
<script type="text/javascript" src="module/register/templates/public/js/commonValidate.js?v=2015"></script>
<script type="text/javascript" src="module/index/templates/default/js/jquery.vector.map.js"></script>
<script type="text/javascript" src="module/index/templates/default/js/china.map.js"></script>
<script type="text/javascript">
    $(function(){sys.init(21,45,<?php echo $province;?>,<?php echo $city;?>);sys.change();});
    $('.loginbtn,.searchbtn').mouseover(function(){
	    $(this).css({'background-color':'#11549e','color':'#fff'});
	}).mouseout(function(){
	    $(this).css({'background-color':'#459bd2','color':'#000'});
	});
	
	
	//滚动插件
	(function($){
		$.fn.extend({
			Scroll:function(opt){
					//参数初始化
					if(!opt) var opt={};
					var _this=this.eq(0).find("ul:first");
					var lineH=_this.find("li:first").height(), //获取行高
						line=opt.line?parseInt(opt.line,10):parseInt(this.height()/lineH,10), //每次滚动的行数，默认为一屏，即父容器高度
						speed=opt.speed?parseInt(opt.speed,10):500, //卷动速度，数值越大，速度越慢（毫秒）
						timer=opt.timer?parseInt(opt.timer,10):3000; //滚动的时间间隔（毫秒）
					if(line==0) line=1;
					var upHeight=0-line*lineH;
					//滚动函数
					
					var timerID;
					//鼠标事件绑定
					_this.hover(function(){
							clearInterval(timerID);
					},function(){
							timerID=setInterval(function(){
							_this.animate({
									marginTop:upHeight
							},speed,function(){
									for(i=1;i<=line;i++){
											_this.find("li:first").appendTo(_this);
									}
									_this.css({marginTop:0});
							});},timer);
					}).mouseout();
			}  		
		});
		
	})(jQuery);

	$(document).ready(function(){
		$(".bulltinboard").Scroll({line:1,speed:500,timer:3000});
		$(".upgradeMember").Scroll({line:1,speed:1000,timer:2000});
	});
	
	$('.samecityMember ul li,.vipMember ul li').hover(function(){
	    $(this).find('.front').animate({left:'-100%'},100);
		 $(this).find('.back').animate({left:'0px'},100);
	},function(){
	      $(this).find('.front').animate({left:'0px'},100);
		 $(this).find('.back').animate({left:'-100%'},100);
	});
	
	
	//登录提交验证
	 $('.fakeuname').on('click',function(){
        $('input[name=username]').trigger('focus');
	});

	$('input[name=username]').focus(function(){
		$('.errorTips').css('display','none');
		$('.fakeuname').css('display','none');
	}).blur(function(){
		var username = $.trim($(this).val());
		if(!username) $('.fakeuname').css('display','');
	});


	$('.fakepwd').on('click',function(){
		$('input[name=password]').trigger('focus');
	});
	$('input[name=password]').focus(function(){
		$('.fakepwd').css('display','none');
		$('.errorTips_').css('display','none');
	}).blur(function(){
		var pwd = $.trim($(this).val());
		if(!pwd) $('.fakepwd').css('display','');
	});



	$('input[name=btnLogin]').on('click',function(){
		var username = $.trim($('input[name=username]').val());
		if(!username || username=='ID/手机号/email'){
			$('.errorTips').html('请输入账号！');
			$('.errorTips').css('display','block');
			$('.fakeuname').css('display','none');
			$('.errorTips_').css('display','none');
			return false;
		}
		if (!chk_phone(username) &&  !CheckEmail(username) && !/^\d{8,10}$/.test(username)){
			$('.errorTips').html('账号格式错误！');
			$('.errorTips').css('display','block');
			$('.fakeuname').css('display','none');
			return false;
		}
	
		var pwd = $.trim($('input[name=password]').val());
		if(!pwd || pwd=='密码'){
			$('.errorTips_').html('请输入密码！');
			$('.errorTips_').css('display','block');
			return false;
		}else{
			$('.errorTips_').css('display','none');
		}
	});
		
	$('.story ul li').hover(function(){
		$(this).children('div.mosaic-overlay').animate({bottom:"0"},'fast');
		$(this).children('div.mosaic-overlay').css({'display':'block'});
	},function(){
		 $(this).children('div.mosaic-overlay').animate({bottom:"-40"},'fast');
	});
	
	

	$(function () {
		//数据可以动态生成，格式自己定义，cha对应china-zh.js中省份的简称
		var dataStatus = [{ cha: 'HKG', name: '香港', des: '' },
						 { cha: 'HAI', name: '海南', des: '' },
						 { cha: 'YUN', name: '云南', des: '' },
						 { cha: 'BEJ', name: '北京', des: 'cp' },
						 { cha: 'TAJ', name: '天津', des: '' },
						 { cha: 'XIN', name: '新疆', des: '' },
						 { cha: 'TIB', name: '西藏', des: '' },
						 { cha: 'QIH', name: '青海', des: '' },
						 { cha: 'GAN', name: '甘肃', des: '' },
						 { cha: 'NMG', name: '内蒙古', des: '' },
						 { cha: 'NXA', name: '宁夏', des: '' },
						 { cha: 'SHX', name: '山西', des: '' },
						 { cha: 'LIA', name: '辽宁', des: '' },
						 { cha: 'JIL', name: '吉林', des: '' },
						 { cha: 'HLJ', name: '黑龙江', des: '' },
						 { cha: 'HEB', name: '河北', des: '' },
						 { cha: 'SHD', name: '山东', des: '' },
						 { cha: 'HEN', name: '河南', des: '' },
						 { cha: 'SHA', name: '陕西', des: '' },
						 { cha: 'SCH', name: '四川', des: '' },
						 { cha: 'CHQ', name: '重庆', des: '' },
						 { cha: 'HUB', name: '湖北', des: '' },
						 { cha: 'ANH', name: '安徽', des: '' },
						 { cha: 'JSU', name: '江苏', des: '' },
						 { cha: 'SHH', name: '上海', des: '' },
						 { cha: 'ZHJ', name: '浙江', des: '' },
						 { cha: 'FUJ', name: '福建', des: '' },
						 { cha: 'TAI', name: '台湾', des: '' },
						 { cha: 'JXI', name: '江西', des: '' },
						 { cha: 'HUN', name: '湖南', des: '' },
						 { cha: 'GUI', name: '贵州', des: '' },
						 { cha: 'GXI', name: '广西', des: '' }, 
						 { cha: 'GUD', name: '广东', des: ''}];
		$('#container').vectorMap({ map: 'china_zh',
			color: "#df3b3b", //地图颜色
			onLabelShow: function (event, label, code) {//动态显示内容
				$.each(dataStatus, function (i, items) {
					if (code == items.cha) {
						//label.html(items.name + items.des);
						label.html(items.name);
					}
				});
			}
		})
		$.each(dataStatus, function (i, items) {
			if (items.des.indexOf('cp') != -1) {//动态设定颜色，此处用了自定义简单的判断
				var josnStr = "{" + items.cha + ":'#00FF00'}";
				$('#container').vectorMap('set', 'colors', eval('(' + josnStr + ')'));
			}
		});
		$('.jvectormap-zoomin').click(); //放大
		
		
		$('.showmap,#container').mouseover(function(){
		     document.getElementById('container').style.display='block';
		    
		});
		
		$('#container').mouseout(function(){
		      document.getElementById('container').style.display='none';
		});
		
	});
  
</script>
<div style="display:none">
	<script src="http://s22.cnzz.com/z_stat.php?id=1000262469&web_id=1000262469" language="JavaScript"></script>
	<script type="text/javascript">
		//var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
		//document.write(unescape("%3Cscript src='" + _bdhmProtocol + "hm.baidu.com/h.js%3Fe78b26575943bb3d1af18f473a6f4f5d' type='text/javascript'%3E%3C/script%3E"));
	</script>
</div>
</body>
</html>

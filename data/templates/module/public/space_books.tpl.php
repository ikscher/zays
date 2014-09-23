<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $user['nickname']?$user['nickname']:'ID:'.$user['uid']?>的征婚交友信息——成就美好姻缘——真爱一生网</title>
<?php include MooTemplate('system/js_css','public'); ?>
<link type='text/css' rel='stylesheet' href='module/space/templates/default/liquidcarousel.css' />
<link rel="stylesheet" type="text/css" href="module/space/templates/default/jquery.fancybox.css?v=2.1.5" media="screen" />
<link rel="stylesheet" type="text/css" href="module/space/templates/default/jquery.fancybox-buttons.css?v=1.0.5" />
<link href="module/space/templates/books/space.css" rel="stylesheet" type="text/css" />
<link href="module/space/templates/default/common.css" rel="stylesheet" type="text/css" />
<link href="public/default/css/gototop.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="module/space/templates/public/js/jquery.mousewheel-3.0.6.pack.js"></script>
<script type="text/javascript" src="module/space/templates/public/js/jquery.fancybox.pack.js?v=2.1.5"></script>
<script type="text/javascript" src="module/space/templates/public/js/jquery.liquidcarousel.pack.js"></script>
<script type="text/javascript" src="module/space/templates/public/js/jquery.fancybox-buttons.js?v=1.0.5"></script>

<script type="text/javascript">

window.onload=function(){
    var uid=<?php echo $userid;?>;
	if(typeof(uid)== "undefined" || uid == 0){
	    //$('#myModal .modal-header button').remove();
	    openLogin();
	}
};

function openLogin(){
	$('#myModal').modal({
		backdrop: 'static',
		keyboard: true
	})
}

$(document).ready(function() {
	$('#liquid').liquidcarousel({
		height: 100,		//the height of the list
		duration: 100,		//the duration of the animation
		hidearrows: true	//hide arrows if all of the list items are visible
	});
});



$('.fancybox-buttons').fancybox({
	openEffect  : 'none',
	closeEffect : 'none',

	prevEffect : 'none',
	nextEffect : 'none',

	closeBtn  : false,

	helpers : {
		title : {
			type : 'inside'
		},
		buttons	: {}
	},

	afterLoad : function() {
		//this.title = 'Image ' + (this.index + 1) + ' of ' + this.group.length + (this.title ? ' - ' + this.title : '');
		this.title = '第 ' + (this.index + 1) + ' 张， 共' + this.group.length +'张'+ (this.title ? ' - ' + this.title : '');
	}
});

$(function(){
	$("input[name=search_type][value=1]").attr("checked","checked");
	$("input[name=search_type]").click(function(){
		if(this.value == 1){
			$("#search_more").css("display","");
			$("#search_uid").css("display","none");
		}
		if(this.value == 2){
			$("#search_uid").css("display","");
			$("#search_more").css("display","none");
		}
		
	})
})

function checkquicksearch(){
	var age1 = document.getElementById("age1").value;
	var age2 = document.getElementById("age2").value;
	if( age1 == 0 && age2 == 0) {
	alert("请选择年龄");
	document.getElementById("age1").focus();
	return false;
	}
	if(age1!=null && age1!='' && age1==0 && age2!=null && age2!='' && age2!=0) {
	alert("请选择年龄下限");
	document.getElementById("age1").focus();
	return false;
	}
	if(age1!=null && age1!='' && age1!=0 && age2!=null && age2!='' && age2==0) {
	alert("请选择年龄上限");
	document.getElementById("age2").focus();
	return false;
	}
	if(age1!=null && age1!='' && age1!=0 && age2!=null && age2!='' && age2!=0 && age1>age2) {
	alert("您选择的年龄范围不正确，请重新选择");
	document.getElementById("age1").focus();
	return false;
	}
	return;
}

</script>
</head>
<body>
	<div class="center" style="background:url(module/space/templates/books/images/center_bg.jpg) top  no-repeat;">
		<div class="head">
			<a href="http://www.zhenaiyisheng.cc/" class="logobox"></a>
			<span class="save"><button type="button" class="btn btn-success">保存皮肤</button></span>
			<div class="toptel"></div>
            <?php if($GLOBALS['MooUid']) { ?>
            	<div class="topmsg">欢迎您 <span class="f-ed0a91"><?php echo MooCutstr($GLOBALS["user_arr"]['nickname'],8,'')?></span> ，您是今天第 <span class="f-000-a"><?php echo MooLogin();?></span>  位登陆用户</div>
                <div class="topmsg">您的ID是 <span class="f-ed0a91"><?php echo $GLOBALS["user_arr"]['uid'];?></span> <a href="index.php?n=service&h=message" class="f-000-a">最新邮件</a>（<span class="f-ed0a91"><?php echo $message_total = header_show_total($GLOBALS["user_arr"]['uid']);?></span>）<a href="index.php?n=material&h=password" class="f-000-a">修改密码</a> <a href="./index.php?n=index&h=logout" class="f-000-a">安全退出</a></div>
            <?php } else { ?>	
                <div class="topmsg2">您好，欢迎来到真爱一生网！[<a href="index.php?n=login" class="f-000-a">请登陆</a>] [<a href="index.php?n=register" class="f-000-a">免费注册</a>]</div>
            <?php } ?>
            <div class="topnav">
				<!-- <p class="nav-side nav-left"></p> -->
			<div class="nav-center">
				<ul>                  
                    <li><a href="index.html">首&nbsp;&nbsp;页</a><b></b></li>
					<li><a href="service.html">我的真爱</a><b></b></li>
					<li><a href="search.html">真爱寻友</a><b></b></li>
					<li><a href="material.html">我要征婚</a><b></b></li>
					<li><a href="lovetest.html">爱情测评</a><b></b></li>
					<li><a href="myaccount.html">信用认证</a><b></b></li>
					<li><a href="vote.html">E见钟情</a><b></b></li>
					<li><a href="introduce.html">服务介绍</a></li>
				</ul>
				<p>
				<a class="upgrade" href="upgrade.html" title="升级成高级、钻石、铂金会员"></a>				
				</p>
			</div>
			<!-- <p class="nav-side nav-right"></p>	 -->
			</div>
			<div class="clear"></div>
		</div><!--head end-->
		
		<!-- <div class="searchbar">
        	<form action="index.php" method="get" onsubmit="return checkquicksearch();">
			<div class="search-cts">
					<label for="s1"><input name="search_type" type="radio" value="1" checked id="s1"/>按条件搜索</label>
					<label for="s2"><input name="search_type" type="radio" value="2" id="s2"/>按ID号搜索</label>
				</div>
				<ul class="search-cts-list" id="search_more">
					<li>我要找:
						<select name="gender">
                        <option value="0" <?php if($GLOBALS["user_arr"]['gender'] == 1) { ?>selected<?php } ?>>男</option>
                        <option value="1" <?php if($GLOBALS["user_arr"]['gender'] == 0) { ?>selected<?php } ?>>女</option>
                        </select>
                    </li>
					<li>年龄:
						<script>getSelect('','age1','agebegin','','21',age);</script>&nbsp;至&nbsp;<script>getSelect('','age2','ageend','','45',age) ;</script>	
                    </li>
					<li>地区:
						<script>getProvinceSelect66('m2 s67','workprovince','workprovince','workcity','','10100000');</script>
						<script>getCitySelect66('m2 s67','workcity','workcity','','');</script>       
                    </li>
					<li><input name="photo" type="checkbox" id="isphoto" value="1" checked="checked"/>有照片</li>
				</ul>
                <input type="text" name="search_uid" id="search_uid" class="reg-info-text" style="display:none;"/>
                <input type="hidden" name="n" value="search" />
				<input type="hidden" name="h" value="quick" />
				<input type="hidden" name="quick_search" value="search" id="quick_search" />
				<input type="submit" id="quick_search" name="" class="search-btn" value="" style="width:106px;" />
         </form>
		</div> -->
		<div class="content">
			
			<div class="c-right">
				<div class="c-rightbox">					
					<div class="right-title"><span class="r-title"><?php if($uid != $userid) { ?><?php echo $user['nickname']?MooCutstr($user['nickname'], 12, ''):'ID:'.$user['uid']?><?php } else { ?>我<?php } ?>的征婚资料</span></div>
					<div class="pInfo r-boxbg01">
						<div class="myself">
							<p style="width:110px;height:138px;overflow:hidden;padding-top:5px;margin-left:8px;">
							<?php if($user['images_ischeck']=='1'&& $user['mainimg']) { ?>
								 <img id="show_pic_1"  class="fixphoto_" src="<?php   
								  if(MooGetphoto($user['uid'],'mid')) echo IMG_SITE.MooGetphoto($user['uid'],'mid');
								  elseif($user['gender'] == '1')echo 'public/system/images/woman_1.gif';
								  else  echo 'public/system/images/man_1.gif';
								  ?>" flag=''/></a>
						   <?php } elseif ($user['mainimg']) { ?>
								<?php if($user['gender'] == '1') { ?>
									<img id="show_pic_1" src="public/system/images/woman.gif"/>
								<?php } else { ?>
									<img id="show_pic_1" src="public/system/images/man.gif" />
								<?php } ?>
								</a>
						   <?php } else { ?>                                        
								<?php if($user['gender'] == '1') { ?>
									<img id="show_pic_1" src="public/system/images/service_nopic_woman.gif"/>
								<?php } else { ?>
									<img  id="show_pic_1" src="public/system/images/service_nopic_man.gif"/>
								<?php } ?>
								</a>
						   <?php } ?> 
						   </p>
						</div>
						
						<div class="vmcd">
							<div>						
								<div>
								<span class="f-ed0a91">
								<?php if($user['s_cid'] == 20) { ?>
								<a href="#"><img src="module/space/templates/default/images/zuan0.gif" /></a>
								<?php } elseif ($user['s_cid'] == 30) { ?>
								<a href="#"><img src="module/space/templates/default/images/zuan1.gif" /></a></span>
								<?php } ?>
								<span class="myname"><?php echo $user['nickname']?$user['nickname']:'ID:'.$user['uid']?></span>&nbsp;<span class="memberid">（ID：<?php echo $user['uid'];?>）</span>	
								<span>诚信度：</span>
									<?php echo get_integrity($user['certification']);?>
								</div>
								
							</div>
							
							<div><?php if($user['birthyear']) { ?><?php echo (date('Y', time()) - $user['birthyear']);?>岁<?php } else { ?>年龄保密<?php } ?>，<?php echo loveStatus($status);?>，居住在<span class="address"><?php if($user['province'] == '0' && $user['city'] == '0') { ?>保密<?php } else { ?><script>userdetail('<?php echo $user['province'];?>',provice)</script><script>userdetail('<?php echo $user['city'];?>',city)</script><?php } ?></span>的<script>userdetail('<?php echo $user['gender'];?>',sex)</script>，寻找一位年龄<?php if($c['age1'] == '0') { ?>不限的<?php } else { ?>在<script>userdetail('<?php echo $c['age1'];?>',age)</script>-<script>userdetail('<?php echo $c['age2'];?>',age)</script>岁<?php } ?><?php if($user['gender'] == 0) { ?>女士<?php } else { ?>男士<?php } ?>。</div>
							<div>
								<ul class="right-cer">
									<?php if(!$usercer['telphone']) { ?>
										<li class="cer-phone-no"><a href="index.php?n=myaccount&h=telphone" title="现在就去验证号码">未通过号码验证</a></li>
									<?php } else { ?>
										<li class="cer-phone-yes">已通过号码验证</li>
									<?php } ?>
									<?php if(!$usercer['sms']) { ?>
										<li class="cer-card-no"><a href="index.php?n=myaccount&h=smsindex" title="现在就去身份通认证">未通过身份通验证</a></li>
									<?php } else { ?>
										<li class="cer-card-yes">已通过身份通认证</li>
									<?php } ?>
									<?php if($usercer['email'] != 'yes') { ?>
										<li class="cer-email-no"><a href="index.php?n=myaccount&h=emailindex" title="现在就去邮箱认证">未通过邮箱验证</a></li>
									<?php } else { ?>
										<li class="cer-email-yes">已通过邮箱认证</li>
									<?php } ?>
									<?php if($usercer['video_check']!=3) { ?>
										<li class="cer-video-no"><a href="index.php?n=myaccount&h=videoindex" title="现在就去视频认证">未通过视频验证</a></li>
									<?php } else { ?>
										<li class="cer-video-yes">已进行视频认证</li>
									<?php } ?>
								</ul>
							</div>
							<div class="clear"></div>
							<div>
							<?php if($usercer['identity_check'] == 3 || $usercer['education_check'] == 3 || $usercer['occupation_check'] == 3 || $usercer['salary_check'] == 3 || $usercer['house_check'] == 3 || $usercer['marriage_check'] == 3) { ?>
								<span class="f-ed0a91">已认证证件：</span><?php if($usercer['identity_check'] == 3) { ?>身份证   <?php } ?><?php if($usercer['education_check'] == 3) { ?>学历证   <?php } ?><?php if($usercer['occupation_check'] == 3) { ?>工作证   <?php } ?><?php if($usercer['salary_check'] == 3) { ?>工资证   <?php } ?><?php if($usercer['house_check'] == 3) { ?>房产证 <?php } ?><?php if($usercer['marriage_check'] == 3) { ?>婚育证<?php } ?> 
							<?php } else { ?>
								<span class="f-ed0a91">已认证证件：</span>暂未验证任何证件 
							<?php } ?>
							</div>
							
						</div>
						<div class="clear"></div>
					</div>
					
				</div>
				
				<div class="c-rightbox">
					<div class="right-title"><?php if($userid == $uid) { ?><a href="index.php?n=material&h=show" class="f-ed0a91-a">修改</a><?php } ?><span class="r-title">相册</span></div>
					<div class="rightbox-in r-boxbg02">
						<div id="liquid" class="liquid">
							<span class="previous"></span>
							<div class="wrapper">
								
								<ul>
									<?php foreach((array)$user_pic as $k=>$v) {?> 
									<li><a  class="fancybox-buttons" data-fancybox-group="button" href="<?php echo IMG_SITE.$v['imgurl']?>" ><img src="<?php echo thumbImgPath('2',$v['pic_date'],$v['pic_name']);?>"   /></a></li>
									<?php }?>
								</ul>
							</div>
							<span class="next"></span>
						</div>
					</div>
				</div>
                <?php if($user['gender'] == '1') { ?>
                    <?php $TA = '她'?>
                <?php } else { ?>
                    <?php $TA = '他'?>
                <?php } ?>
				<div class="c-rightbox">
					<div class="right-title" style="width:740px; height:31px; background:url(module/space/templates/books/images/right-title-bg.gif) repeat-x 0 0; line-height:31px; margin:10px 0 0 3px; padding:0 0 0 10px; color:#000"><?php if($userid == $uid) { ?><a href="index.php?n=material&h=upinfo#person_message" class="fright f-ed0a91-a">修改</a><?php } ?><span class="r-title">基本资料</span></div>
					<div class="rightbox-in r-boxbg02">
						<ul class="dtable">
                            <li>性&nbsp;&nbsp;&nbsp;&nbsp;别：<script>userdetail('<?php echo  $user['gender']?1:0?>',sex);</script></li>
                            <li>年&nbsp;&nbsp;&nbsp;&nbsp;龄：<?php if($user['birthyear']) { ?><?php echo (date('Y', time()) - $user['birthyear']);?>岁<?php } else { ?>保密<?php } ?>
                                <?php if($user2) { ?><?php if(($c2['age1'] == '0' and $c2['age2'] =='0') || ($c2['age1'] == '0' and  (date('Y', time()) - $user['birthyear'])< $c2['age2']) || ($c2['age1'] < (date('Y', time()) - $user['birthyear']) and  (date('Y', time()) - $user['birthyear'])< $c2['age2']) || ($c2['age1'] < (date('Y', time()) - $user['birthyear']) and  $c2['age2'] == '0')) { ?><font title="<?php echo $TA;?>的年龄符合您择偶要求"><img src="public/system/images/123.gif" alt="<?php echo $TA;?>的年龄符合您择偶要求"></font><?php } ?><?php } ?>
                                </li>
                            <li>宗教信仰：<?php if($user['religion'] == '0') { ?>保密<?php } else { ?><script>userdetail('<?php echo $user['religion'];?>',belief)</script><?php } ?></li>
                            <li>身&nbsp;&nbsp;&nbsp;&nbsp;高：<?php if($user['height'] == '0') { ?>保密<?php } else { ?><script>userdetail('<?php echo $user['height'];?>',height)</script>厘米<?php } ?>
                                <?php if($user2) { ?><?php if(($c2['height1'] == '0' and $c2['height2'] =='0') || ($c2['height1'] == '0' and  ($user['height'])< $c2['height2']) || ($c2['height1'] < ($user['height']) and  ($user['height'])< $c2['height2']) || ($c2['height1'] < ($user['height']) and  $c2['height2'] == '0')) { ?><font title="<?php echo $TA;?>的身高符合您择偶要求"><img src="public/system/images/123.gif" alt="<?php echo $TA;?>的身高符合您择偶要求"></font><?php } ?><?php } ?>
                                </li>
                            <li>体&nbsp;&nbsp;&nbsp;&nbsp;重：<?php if($user['weight'] == '0') { ?>保密<?php } else { ?><script>userdetail('<?php echo $user['weight'];?>',weight)</script>公斤<?php } ?>
                                <?php if($user2) { ?><?php if(($c2['weight1'] == '0' and $c2['weight2'] =='0') || ($c2['weight1'] == '0' and  ($user['weight'])< $c2['weight2']) || ($c2['weight1'] < ($user['weight']) and  ($user['weight'])< $c2['weight2']) || ($c2['weight1'] < ($user['weight']) and  $c2['weight2'] == '0')) { ?><font title="<?php echo $TA;?>的体重符合您择偶要求"><img src="public/system/images/123.gif" alt="<?php echo $TA;?>的体重符合您择偶要求"></font><?php } ?><?php } ?>
                                </li>
                            <li>学&nbsp;&nbsp;&nbsp;&nbsp;历：<?php if($user['education'] == '0') { ?>保密<?php } else { ?><script>userdetail('<?php echo $user['education'];?>',education)</script><?php } ?>
                                <?php if($user2) { ?><?php if($c2['education'] == '0' || ($c2['education'] ==  $user['education'])) { ?><font title="<?php echo $TA;?>的学历符合您择偶要求"><img src="public/system/images/123.gif" alt="<?php echo $TA;?>的学历符合您择偶要求"></font><?php } ?><?php } ?>
                                </li>
                            <li>民&nbsp;&nbsp;&nbsp;&nbsp;族：<?php if($user['nation'] == '0') { ?>保密<?php } else { ?><script>userdetail('<?php echo $user['nation'];?>',stock)</script><?php } ?></li>
                            <li>所在地区：<?php if($user['province'] == '0' && $user['city'] == '0') { ?>保密<?php } else { ?><script>userdetail('<?php echo $user['province'];?>',provice)</script><script>userdetail('<?php echo $user['city'];?>',city)</script><?php } ?>
                                <?php if($user2) { ?><?php if(($c2['workprovince']<>'0' && $c2['workcity']<>'0')) { ?><?php if(($c2['workprovince'] ==  $user['province']) && $c2['workcity'] == $user['city']) { ?><font title="<?php echo $TA;?>的所在地区符合您择偶要求"><img src="public/system/images/123.gif" alt="<?php echo $TA;?>的所在地区符合您择偶要求"></font><?php } ?><?php } elseif ($c2['workprovince']<>'0') { ?><?php if($c2['workprovince']==$user['province']) { ?><font title="<?php echo $TA;?>的所在地区符合您择偶要求"><img src="public/system/images/123.gif" alt="<?php echo $TA;?>的所在地区符合您择偶要求"></font><?php } ?><?php } elseif ($c2['workprovince'] =='0') { ?><font title="<?php echo $TA;?>的所在地区符合您择偶要求"><img src="public/system/images/123.gif" alt="<?php echo $TA;?>的所在地区符合您择偶要求"></font><?php } ?><?php } ?>
                                </li>
                            <li>户口地区：<?php if($user['hometownprovince'] == '0' && $user['hometowncity'] == '0') { ?>保密<?php } else { ?><script>userdetail('<?php echo $user['hometownprovince'];?>',provice)</script><script>userdetail('<?php echo $user['hometowncity'];?>',city)</script><?php } ?>
                                <?php if($user2) { ?><?php if(($c2['hometownprovince']<>'0' && $c2['hometowncity']<>'0')) { ?><?php if(($c2['hometownprovince'] ==  $user['hometownprovince']) && $c2['hometowncity'] == $user['hometowncity']) { ?><font title="<?php echo $TA;?>的户口所在地符合您择偶要求"><img src="public/system/images/123.gif" alt="<?php echo $TA;?>的户口所在地符合您择偶要求"></font><?php } ?><?php } elseif ($c2['hometownprovince']<>'0') { ?><?php if($c2['hometownprovince']==$user['hometownprovince']) { ?><font title="<?php echo $TA;?>的户口所在地符合您择偶要求"><img src="public/system/images/123.gif" alt="<?php echo $TA;?>的户口所在地符合您择偶要求"></font><?php } ?><?php } elseif ($c2['hometownprovince'] =='0') { ?><font title="<?php echo $TA;?>的户口所在地符合您择偶要求"><img src="public/system/images/123.gif" alt="<?php echo $TA;?>的户口所在地符合您择偶要求"></font><?php } ?><?php } ?>
                                </li>
                            <li>职&nbsp;&nbsp;&nbsp;&nbsp;业：<?php if($user['occupation'] == '0') { ?>保密<?php } else { ?><script>userdetail('<?php echo $user['occupation'];?>',occupation)</script><?php } ?>
                                <?php if($user2) { ?><?php if($c2['occupation']<>'0') { ?><?php if($c2['occupation'] ==  $user['occupation']) { ?><font title="<?php echo $TA;?>的职业符合您择偶要求"><img src="public/system/images/123.gif" alt="<?php echo $TA;?>的职业符合您择偶要求"></font><?php } ?><?php } elseif ($c2['occupation']=='0') { ?><font title="<?php echo $TA;?>的职业符合您择偶要求"><img src="public/system/images/123.gif" alt="<?php echo $TA;?>的职业符合您择偶要求"></font><?php } ?><?php } ?>
                                </li>
                            <li>婚姻状况：<?php if($user['marriage'] == '0') { ?>保密<?php } else { ?><script>userdetail('<?php echo $user['marriage'];?>',marriage)</script><?php } ?>
                                <?php if($user2) { ?><?php if($c2['marriage']<>'0') { ?><?php if($c2['marriage'] ==  $user['marriage']) { ?><font title="<?php echo $TA;?>的婚姻状况符合您择偶要求"><img src="public/system/images/123.gif" alt="<?php echo $TA;?>的婚姻状况符合您择偶要求"></font><?php } ?><?php } elseif ($c2['marriage']=='0') { ?><font title="<?php echo $TA;?>的婚姻状况符合您择偶要求"><img src="public/system/images/123.gif" alt="<?php echo $TA;?>的婚姻状况符合您择偶要求"></font><?php } ?><?php } ?>
                                </li>
                            <li>生&nbsp;&nbsp;&nbsp;&nbsp;肖：<?php if($user['birthyear'] == '0') { ?>保密<?php } else { ?><?php echo get_signs($user['birthyear'])?><?php } ?></li>
                            <li>公司类型：<?php if($user['corptype'] == '0') { ?>保密<?php } else { ?><script>userdetail('<?php echo $user['corptype'];?>',corptype)</script><?php } ?></li>
                            <li>是否有孩子：<?php if($user['children'] == '0') { ?>保密<?php } else { ?><script>userdetail('<?php echo $user['children'];?>',children)</script><?php } ?>
                                <?php if($user2) { ?><?php if($c2['children']<>'0') { ?><?php if($c2['children'] ==  $user['children']) { ?><font title="<?php echo $TA;?>的有无孩子符合您择偶要求"><img src="public/system/images/123.gif" alt="<?php echo $TA;?>的有无孩子符合您择偶要求"></font><?php } ?><?php } elseif ($c2['children']=='0') { ?><font title="<?php echo $TA;?>的有无孩子符合您择偶要求"><img src="public/system/images/123.gif" alt="<?php echo $TA;?>的有无孩子符合您择偶要求"></font><?php } ?><?php } ?>
                                </li>
                            <li>星&nbsp;&nbsp;&nbsp;&nbsp;座：<?php if($user['constellation'] == '0') { ?>保密<?php } else { ?><script>userdetail('<?php echo $user['constellation'];?>',constellation)</script><?php } ?></li>
                            <li>居住情况：<?php if($user['house'] == '0') { ?>保密<?php } else { ?><script>userdetail('<?php echo $user['house'];?>',house)</script><?php } ?></li>
                            <li>月&nbsp;&nbsp;&nbsp;&nbsp;薪：<?php if($user['salary'] == '0') { ?>保密<?php } else { ?><script>userdetail('<?php echo $user['salary'];?>',salary1)</script><?php } ?>
                                <?php if($user2) { ?><?php if($c2['salary']<>'0') { ?><?php if($c2['salary'] ==  $user['salary']) { ?><font title="<?php echo $TA;?>的月薪符合您择偶要求"><img src="public/system/images/123.gif" alt="<?php echo $TA;?>的月薪符合您择偶要求"></font><?php } ?><?php } elseif ($c2['salary']=='0') { ?><font title="<?php echo $TA;?>的月薪符合您择偶要求"><img src="public/system/images/123.gif" alt="<?php echo $TA;?>的月薪符合您择偶要求"></font><?php } ?><?php } ?>
                                </li>
                            <li>血&nbsp;&nbsp;&nbsp;&nbsp;型：<?php if($user['bloodtype'] == '0') { ?>保密<?php } else { ?><script>userdetail('<?php echo $user['bloodtype'];?>',bloodtype)</script><?php } ?></li>
                            <li>购车情况：<?php if($user['vehicle'] == '0') { ?>保密<?php } else { ?><script>userdetail('<?php echo $user['vehicle'];?>',vehicle)</script><?php } ?></li>
                            <li>兄弟姐妹：<?php if($user['family'] == '0') { ?>保密<?php } else { ?><script>userdetail('<?php echo $user['family'];?>',family)</script><?php } ?></li>
                            <li>绑定情况：<?php if($user['isbind'] == '1') { ?><span style="color:#F00">绑定中...</span><?php } else { ?>未绑定<?php } ?></li>
						</ul>
                        <p>语言能力：<?php $lg = explode(",",$user['language']);?>
                            <?php foreach((array)$lg as $v) {?>
                                <?php if($v== '') { ?>
                                    保密
                                <?php } else { ?>
                                    <script>userdetail('<?php echo $v;?>',tonguegifts)</script>
                                <?php } ?>
                            <?php } ?>
                        </p>
						<div class="clear"></div>
					</div>
				</div>
				<div class="c-rightbox">
					<div class="right-title" style="width:740px; height:31px; background:url(module/space/templates/books/images/right-title-bg.gif) repeat-x 0 0; line-height:31px; margin:10px 0 0 3px; padding:0 0 0 10px; color:#000"><?php if($userid == $uid) { ?><a href="index.php?n=material&h=upinfo&#choose_person" class="fright f-ed0a91-a">修改</a><?php } ?><span class="r-title">择偶要求</span></div>
					<div class="rightbox-in r-boxbg03">
						<ul class="dtable">
                            <li>年&nbsp;&nbsp;&nbsp;&nbsp;龄：<?php if($c['age1'] == '0') { ?>不限<?php } else { ?><script>userdetail('<?php echo $c['age1'];?>',age)</script>~<script>userdetail('<?php echo $c['age2'];?>',age)</script>岁 <?php } ?></li>
                            <li>身&nbsp;&nbsp;&nbsp;&nbsp;高：<?php if($c['height1'] == '0') { ?>不限<?php } else { ?><script>userdetail('<?php echo $c['height1'];?>',height)</script>~<script>userdetail('<?php echo $c['height2'];?>',height)</script>厘米<?php } ?> </li>
                            <li>体&nbsp;&nbsp;&nbsp;&nbsp;重：<?php if($c['weight1'] == '0') { ?>不限<?php } else { ?><script>userdetail('<?php echo $c['weight1'];?>',weight)</script>~<script>userdetail('<?php echo $c['weight2'];?>',weight)</script>公斤<?php } ?></li>
                            <li>体&nbsp;&nbsp;&nbsp;&nbsp;型：<?php if($c['body'] == '0') { ?>不限<?php } else { ?><script>userdetail('<?php echo $c['body'];?>',body<?php echo $user['gender']?0:1;?>)</script><?php } ?></li>
                            <li>是否有照片：<?php if($c['hasphoto'] == '1') { ?>有<?php } else { ?>无<?php } ?></li>
                            <li>征友地区：<?php if($c['hometownprovince'] == '0' && $c['hometowncity'] == '0') { ?>不限<?php } else { ?><script>userdetail('<?php echo $c['hometownprovince'];?>',provice)</script><script>userdetail('<?php echo $c['hometowncity'];?>',city)</script><?php } ?></li>
                            <li>教育程度：<?php if($c['education'] == '0') { ?>不限<?php } else { ?><script>userdetail('<?php echo $c['education'];?>',education)</script><?php } ?></li>
                            <li>职&nbsp;&nbsp;&nbsp;&nbsp;业：<?php if($c['occupation'] == '0') { ?>不限<?php } else { ?><script>userdetail('<?php echo $c['occupation'];?>',occupation)</script><?php } ?></li>
                            <li>月&nbsp;&nbsp;&nbsp;&nbsp;薪：<?php if($c['salary'] == '0') { ?>不限<?php } else { ?><script>userdetail('<?php echo $c['salary'];?>',salary1)</script><?php } ?></li>
                            <li>工&nbsp;作&nbsp;地：<?php if($c['workprovince'] == '0' && $c['workcity'] == '0') { ?>不限<?php } else { ?><script>userdetail('<?php echo $c['workprovince'];?>',provice)</script><script>userdetail('<?php echo $c['workcity'];?>',city)</script><?php } ?></li>
                            <li>是否抽烟：<?php if($c['smoking'] == '0') { ?>不限<?php } else { ?><script>userdetail('<?php echo $c['smoking'];?>',smoking)</script><?php } ?></li>
                            <li>婚姻状况：<?php if($c['marriage'] == '0') { ?>不限<?php } else { ?><script>userdetail('<?php echo $c['marriage'];?>',marriage)</script><?php } ?></li>
                            <li>是否有孩子：<?php if($c['children'] == '0') { ?>不限<?php } else { ?><script>userdetail('<?php echo $c['children'];?>',children)</script><?php } ?></li>
                            <li>是否要孩子：<?php if($c['wantchildren'] == '0') { ?>不限<?php } else { ?><script>userdetail('<?php echo $c['wantchildren'];?>',wantchildren)</script><?php } ?></li>
						</ul>
						<div class="clear"></div>
					</div>
				</div>
				<div class="c-rightbox">
					<div class="right-title" style="width:740px; height:31px; background:url(module/space/templates/books/images/right-title-bg.gif) repeat-x 0 0; line-height:31px; margin:10px 0 0 3px; padding:0 0 0 10px; color:#000"> <?php if($userid == $uid) { ?><a href="index.php?n=material&h=upinfo&actio=2" class="fright f-ed0a91-a">修改</a><?php } ?><span class="r-title">生活方式</span></div>
					<div class="rightbox-in r-boxbg04">
						<ul class="dtable">
                            <li>是否吸烟：<?php if($user['smoking'] == '0') { ?>保密<?php } else { ?><script>userdetail('<?php echo $user['smoking'];?>',isSmoking)</script><?php } ?>
                   				<?php if($user2) { ?><?php if($c2['smoking']<>'0') { ?><?php if($c2['smoking'] ==  $user['smoking']) { ?><font title="<?php echo $TA;?>的是否吸烟符合您择偶要求"><img src="public/system/images/123.gif" alt="<?php echo $TA;?>的是否吸烟符合您择偶要求"></font><?php } ?><?php } elseif ($c2['smoking']=='0') { ?><font title="<?php echo $TA;?>的是否吸烟符合您择偶要求"><img src="public/system/images/123.gif" alt="<?php echo $TA;?>的是否吸烟符合您择偶要求"></font><?php } ?><?php } ?>
                            </li>
                            <li>是否饮酒：<?php if($user['drinking'] == '0') { ?>保密<?php } else { ?>
                                <script>userdetail('<?php echo $user['drinking'];?>',isDrinking)</script><?php } ?>
                            </li>
						</ul>	
                        <p>喜欢的美食：<?php $fd = explode(",",$user['fondfood']);?>
                            <?php foreach((array)$fd as $v) {?>
                                <?php if($v == '0') { ?>
                                    保密
                                <?php } else { ?>
                                     <script>userdetail('<?php echo $v;?>',fondfoods)</script>
                                <?php } ?>
                            <?php } ?>
                        </p>
                        <p>喜欢的地方：<?php $fd = explode(",",$user['fondplace']);?>
                            <?php foreach((array)$fd as $v) {?>
                                <?php if($v == '0') { ?><span class="w350">保密</span><?php } else { ?>
                                    <script>userdetail('<?php echo $v;?>',fondplaces)</script>
                                <?php } ?>
                            <?php } ?>
                        </p>					
						<div class="clear"></div>
					</div>
				</div>
				<div class="c-rightbox">
					<div class="right-title" style="width:740px; height:31px; background:url(module/space/templates/books/images/right-title-bg.gif) repeat-x 0 0; line-height:31px; margin:10px 0 0 3px; padding:0 0 0 10px; color:#000"><?php if($userid == $uid) { ?><a href="index.php?n=material&h=upinfo&actio=3" class="fright f-ed0a91-a">修改</a><?php } ?><span class="r-title">兴趣爱好</span></div>
					<div class="rightbox-in r-boxbg05">
						<p style="margin-top:30px;">喜欢的运动：<?php $fda = explode(",",$user['fondsport']);?>
                            <?php foreach((array)$fda as $v) {?>
                                <?php if($v == '0') { ?><span class="w350">保密</span><?php } else { ?>
                                    <script>userdetail('<?php echo $v;?>',fondsports)</script>
                                <?php } ?>
                            <?php } ?>
                        </p>
                        <p>喜欢的活动：<?php $fda = explode(",",$user['fondactivity']);?>
                            <?php foreach((array)$fda as $v) {?>
                                <?php if($v == '0') { ?><span class="w350">保密</span><?php } else { ?>
                                    <script>userdetail('<?php echo $v;?>',fondactions)</script>
                                <?php } ?>
                            <?php } ?>
                         </p>				
                        <p>喜欢的音乐：<?php $fda = explode(",",$user['fondmusic']);?>
                            <?php foreach((array)$fda as $v) {?>
                                <?php if($v == '0') { ?><span class="w350">保密</span><?php } else { ?>
                                    <script>userdetail('<?php echo $v;?>',fondmusics)</script>
                                <?php } ?>
                            <?php } ?> 
                        </p>
                        <p>喜欢的影视：<?php $fda = explode(",",$user['fondprogram']);?>
                            <?php foreach((array)$fda as $v) {?>
                                <?php if($v == '0') { ?><span class="w350">保密</span><?php } else { ?>
                                    <script>userdetail('<?php echo $v;?>',fondprograms)</script>
                                <?php } ?>
                            <?php } ?>
                        </p>						
                        <div class="clear"></div>
					</div>
				</div>
				<div class="c-rightbox">
					<div class="right-title" style="width:740px; height:31px; background:url(module/space/templates/books/images/right-title-bg.gif) repeat-x 0 0; line-height:31px; margin:10px 0 0 3px; padding:0 0 0 10px; color:#000"><?php if($userid == $uid) { ?><a href="index.php?n=material&h=upinfo&#person_introduce" class="fright f-ed0a91-a">修改</a><?php } ?><span class="r-title">内心独白</span></div>
					<div class="rightbox-in r-boxbg06">
						<p class="my-heart">
                        <?php $u = MooGetGPC('uid','integer');?>
                        <?php if(!$u) { ?>
                            <?php if($c['introduce'] != '') { ?>
                                <?php echo Moorestr($c['introduce'])?>
                            <?php } elseif ($c['introduce_check'] != '') { ?>
                                <?php echo Moorestr($c['introduce_check'])?>
                                （<font style="text-decoration:none;color:#000">正在审核中</font>）
                            <?php } else { ?>
                                您还没有内心独白<font style="cursor:pointer;" color="#c10202" onclick="javascript:location.href='index.php?n=material&h=upinfo'"> 马上填写</font>
                            <?php } ?>           
                        <?php } else { ?>
                             <?php echo $c['introduce']?Moorestr($c['introduce']):'还没有填写内心独白内容'?>
                        <?php } ?>
                        </p>
						<div class="clear"></div>
					</div>
				</div>
			  </div>
			</div><!--c-right end-->
			
			<?php include MooTemplate('public/space_leftmenu_new','module'); ?>
			<div class="clear"></div>
			
		</div><!--content end-->
	</div><!--center end-->
	
	<div class="foot">
		<div class="foot-nav">
			<ul>
				<li><a href="?n=about&h=us" target="_blank">关于我们</a>|</li>
				<li><a href="?n=about&h=contact" target="_blank">联系我们</a>|</li>
				<li><a href="?n=about&h=links" target="_blank">合作伙伴</a>|</li>
				<li><a href="index.php?n=about&h=getsave" target="_blank">意见反馈</a>|</li>
				<li><a href="index.php?n=story" target="_blank">成功故事</a>|</li>
				<li><a href="index.php?n=payment&h=channel" target="_blank">汇款信息</a></li>
			</ul>
		</div>

		<p>Copyright 2006-<?php echo date('Y');?> 真爱一生网.All Right Reserved.皖ICP备14002819</p>
		
	</div><!--foot end-->
    <?php if($GLOBALS['userid'] && !in_array( MooGetGPC('h','string'), array('return','pay','picflash','makepic','history','chat' ) ) && $_GET['n']!='register' ) { ?>
    	<?php include MooTemplate('system/rightbottom','public'); ?>
	<?php } ?>


    <!--登录模态框-->
	<div class="modal fade" id="myModal" tabindex="0" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false" >
	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h5 class="modal-title" id="myModalLabel">登录真爱一生网，您将获取更多会员的征婚信息和更多的会员关注 </h5>
		  </div>
		  <div class="modal-body">
				<div class="login-box">
					<form id="loginform" name="loginform" action="index.php?n=index&h=submit" method="post" onsubmit="return checkLoginForm();">
						<p><input type="text" name="username" id="username"   class="form-control" value=""  class="login-text" /></p>
						<p><input type="password" name="password" id="password" class="form-control"   class="login-text"/></p>
						<p><span class="userTips h"></span><span class="pwdTips h"></span></p>
						<p><input style="width:20px;" name="remember" type="checkbox" id="remember" value="1"  /><label for="remember">记住账号</label>
							<a href="#"  onclick="javascript:location.href='index.php?n=login&h=backpassword'" class="f-ed0a91-a">忘记密码？</a>
							<a href='register.html' class="f-ed0a91-a">我要成为会员</a></p> 
						<p><input type="submit"  name="btnLogin" class="btn btn-success ft" value="登 录" />
						<span class="fakeuname" >ID，手机号，邮箱 都 可以作为账号</span> 
						<span class="fakepwd" >密码</span> 
						<input type="hidden" name="returnurl" value="" />
					</form>
				</div>
				<div class="login-box-right">
					 <span>告诉我一个地址，让我到你心里；给我一个密码，让我打开你的心门。我们衷心祝福所有在真爱一生网认识的会员都能终成眷属！</span>
				</div>
		  </div>
		  <!-- <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
		  </div> -->
		</div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div>
	<!--登录模态框-->
	<script type="text/javascript">
		//检测表单
		function checkLoginForm(){
			$('.userTips').html('');
			$('.pwdTips').html('');
			var username = $('#username').val().replace(/\s/g,'');
			var password = $('#password').val().replace(/\s/g,'');
			if(!username || username==''){
				$('.userTips').html('请输入账号');
				$('.userTips').removeClass('h');
				$('.userTips').addClass('s');
				return false;
			}else if (!CheckEmail(username) && !chk_phone(username) && !/^\d{8,8}$/.test(username)){
				$('.userTips').html('输入的用户名格式不正确');
				$('.userTips').removeClass('h');
				$('.userTips').addClass('s');
				return false;
			}
			if(!password){
				$('.pwdTips').html('请输入密码');
				$('.pwdTips').removeClass('h');
				$('.pwdTips').addClass('s');
				return false;
			}
			return true;
		}
		
		
		//表单输入框的提示信息
		$('input[name=username]').focus(function(){
		   $('.userTips').addClass('h');
		   $('.fakeuname').addClass('h');
			$('.userTips').removeClass('s');
		   $('.fakeuname').removeClass('s');
		}).blur(function(){
		   var username = $.trim($(this).val());
		   if(!username) {$('.fakeuname').removeClass('h');$('.fakeuname').addClass('s');}
		});
		
		$('input[name=password]').focus(function(){
		   $('.fakepwd').addClass('h');
		   $('.pwdTips').addClass('h');
			$('.fakepwd').removeClass('s');
		   $('.pwdTips').removeClass('s');
		}).blur(function(){
		   var pwd = $.trim($(this).val());
		   if(!pwd) { $('.fakepwd').addClass('s');$('.fakepwd').removeClass('h');}
		});
		
		$('.fakeuname').on('click',function(){
		   $('input[name=username]').trigger('focus');
		});
		
		$('.fakepwd').on('click',function(){
		   $('input[name=password]').trigger('focus');
		});
		
		$('.wrapper li a').on('click',function(){var uid=<?php echo $GLOBALS['MooUid'];?>;if(!uid){openLogin();return false;}});
	</script>
	<!--[if lte IE 6]>
	<script type="text/javascript" src="./public/default/js/bootstrap-ie.js"></script>
	<![endif]-->
</body>
</html>
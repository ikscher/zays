<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta  name="viewport"  content="width=device-width,initial-scale=1,user-scalable=0" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="keywords" content="红娘,征婚,征婚网,婚介,婚恋,婚姻,交友,交友网,相亲,征婚,姻缘,牵线,寻爱">
        <meta name="description" content="真爱一生由专业的红娘团队以及情感专家组成的严肃正规的婚恋网站, 针对城市白领提供专业的婚恋服务，帮助单身会员组成美满幸福家庭。">
        <title>真爱一生网</title>
        <link rel="shortcut icon" href="public/default/images/favicon.ico" type="image/x-icon"/>
        <link rel="icon" href="public/default/images/favicon.ico" type="image/x-icon">
        <script type="text/javascript" src="public/system/js/jquery-1.8.3.min.js"></script>
        <script src="reg/ipReal.php"></script>
        <script type="text/javascript" src="module/index/templates/default/js/index.js?v=2"></script>
        <script type="text/javascript" src="module/index/templates/default/js/jquery.lazyload.min.js?v=1"></script>
        <script type="text/javascript">
            var c_provice = "";
            var c_city = "";
            len = provice.length;
            for (i = 0; i < len; i++) {
                provice_arr = provice[i].split(",");
                if (curent_area.indexOf(provice_arr[1]) != -1) {
                    c_provice = provice_arr[0];
                    break;
                }
            }
            len = city.length;
            for (i = 0; i < len; i++) {
                city_arr = city[i].split(",");
                if (curent_area.indexOf(city_arr[1]) != -1) {
                    c_city = city_arr[0];
                    break;
                }
            }
	
	
            /*图片滚动*/
            function AutoScroll(obj){
                $(obj).find("ul:first").animate({
                    marginTop:"-25px"
                },500,function(){
                    $(this).css({marginTop:"0px"}).find("li:first").appendTo(this);
                });
            }
            $(document).ready(function(){
                setInterval('AutoScroll("#scrollDiv")',3000);
            });
        </script>
        <!--[if lte IE 6]>
        <link rel="stylesheet" type="text/css" href="public/default/css/bootstrap-ie6.css" />
        <link rel="stylesheet" type="text/css" href="public/default/css/ie.css" />
        <![endif]-->

        <link href="public/default/css/bootstrap.min.css" rel="stylesheet" media="screen" />
        <link href="module/index/templates/default/css/index.css?v=1" rel="stylesheet" type="text/css"/>

        <script src="module/index/templates/rightbottom_popup/js/jquery.eBox.js?v=2"></script>
        <script type="text/javascript">
            /*
         $(document).ready(function(){

           $.eBox2({
          content:{html:"<image src=\"module/index/templates/default/images/wotion/pop.gif\" />"},

             effect:{type:"slide",speed:2500},
              openOnce:false
           });

        });
             */
		$(function(){ $('.carousel').carousel();});
        </script>
    </head>
    <body>
        <div class="header">
            <div class="d">
                <?php if($GLOBALS['MooUid']) { ?>

                <div class="logined">
                    您的用户名是：<?php echo $GLOBALS['MooUserName']?MooCutstr($GLOBALS['MooUserName'],16):$user_arr['nickname'];?>
                   &nbsp;&nbsp; 您的ID是：<?php echo $GLOBALS['MooUid'];?>&nbsp;&nbsp;
                    最新邮件(<a href="index.php?n=service&h=message"><?php echo $GLOBALS['new_email_num']?>) <a href="service.html">进入我的红娘&gt;&gt;</a> <a href="index.php?n=index&h=logout">安全退出</a>
                </div>
                <?php } else { ?>
                <div class="loginform">
                    <form  name="formLogin"  class="form-inline " action="index.php?n=index&h=submit" method="post" >
                        <input type="text" name="username" id="username" value=""  class="form-control  input-mm f10 login"/>
                        <input type="password" name="password" id="password" value=""  class="form-control  input-sm f10 login"/>
                        <label for="r-pwd">
                            <input type="checkbox" name="autoLogin" id="r-pwd" />
                            一个月内自动登录</label>
                        <input type="submit" value="登录" name="btnLogin" class="btn btn-default" />
                        <input type="button" value="注册" name="register" class="btn btn-default" />
                        <span class="fakeuname" >ID/手机号/email</span> 
                        <span class="fakepwd" >密码</span> 
                        <span class="errorTips" style="display:none"></span>
                        <span class="errorTips_" style="display:none"></span>
                        <a href="forget.html">忘记密码？</a>
                    </form>
                    <span style="position:absolute;right:110px;top:10px;" id="qqLoginBtn"><a href="login_qq.html" ><img src="public/default/images/login_qq.png" /></a></span>
                    <!-- <span style="position:absolute;right:110px;top:10px;cursor:pointer;"><img src="public/default/images/login_qq.png" /></span> -->
                </div>
                <?php } ?>

            </div>


            <!-- <div class="section">
              <div class="picture">
                  <div id="carousel" class="carousel slide" data-ride="carousel">
                    <ol class="carousel-indicators">
                      <li data-target="#carousel-example-generic" data-slide-to="0" ></li>
                      <li data-target="#carousel-example-generic" data-slide-to="1" class="active"></li>
                    </ol>
                    <div class="carousel-inner">
                      <div class="item"> <img  alt="Third slide" src="module/index/templates/default/images/vution/t2.jpg" /> </div>
                      <div class="item active"> <img  alt="Forth slide" src="module/index/templates/default/images/vution/t1.jpg" /> </div>
                    </div>
                  </div>
              </div>
            </div> -->

        </div>
        
        <div class="main">
		    <!--轮播-->
            <div id="myCarousel" class="carousel slide">
				  <ol class="carousel-indicators">
					<li data-target="#myCarousel" data-slide-to="0" class="active"></li>
					<li data-target="#myCarousel" data-slide-to="1"></li>
					<!--<li data-target="#myCarousel" data-slide-to="2"></li> -->
				  </ol>
				  <!-- Carousel items -->
				  <div class="carousel-inner">
					<div class="active item"><a href="upgrade.html"><img  alt="Third slide" src="module/index/templates/default/images/vution/diamond.jpg" /></a></div>
					<div class="item"><a href="upgrade.html"><img  alt="Third slide" src="module/index/templates/default/images/vution/janfirst.jpg" /></a></div>
				  </div>
				  <!-- Carousel nav -->
				  <a class="carousel-control left" href="#myCarousel" data-slide="prev">&lsaquo;</a>
				  <a class="carousel-control right" href="#myCarousel" data-slide="next">&rsaquo;</a>
			</div>
			<!--结束-->
			
            <div>
                <a href="#" class="logo" ></a>
                <span class="top-tel"></span>
				<!-- <span style="position:absolute;left:185px;top:20px;background:url('public/default/images/nationalday.png') no-repeat;width:50px;height:50px;"></span>  -->
            </div>
			
			<!-- <div style="margin:0 auto;width:980px;">
			   <a href="nationalday.html"><img   src="module/index/templates/default/images/vution/nationalday.gif" /> </a>
		    </div> -->
			<div class="lover"><img src="module/index/templates/default/images/vution/vt.jpg" /></div>
			<div id="scrollDiv"><ul><?php if(count($textInfo)>0) { ?><?php foreach((array)$textInfo as $t) {?><li><?php echo $t['content'];?></li><?php } ?><?php } ?></ul></div>
            <div class="search ">
                <div class="searchform">
                    <form id="search" name="search" method="get" action="index.php" onSubmit="return checkSearchForm();">
                        <input type="hidden" name="n" value="search">
                        <input type="hidden" name="h" value="quick">
                        <input name="search_type" type="hidden" value="1">
                        我要找：
                        <select name="gender">
                            <option value="0" <?php if($user_arr['gender'] == '1') { ?> selected <?php } ?> >男士</option>
                            <option value="1" <?php if($user_arr['gender'] == '0' || empty($user_arr['gender'])) { ?> selected <?php } ?>>
                                    女士
                        </option>
                    </select>
                    <input type="checkbox" id="chkfoto" name="photo" value="1" checked="checked">
                    <label for="chkfoto">有照片</label>

                    年　龄：
                    <script>getSelect("",'age1','age_start','','21',age);</script>
                    ～
                    <script>getSelect("",'age2','age_end','','45',age);</script>


                    地　区：
                    <script>getProvinceSelect43rds('','workprovince','workprovince','workcity',c_provice,'10100000');</script>
                    &nbsp;
                    <script>getCitySelect43rds('','workcity','workcity',c_city,'');</script>

                    <input id="quick_search" name="quick_search"  type="hidden" value="search">

                    <input type="submit" id="btnlnSearch" class="sbt" value="" />

                </form>

            </div>


        </div>
        <div class="recommend cf">
            <div class="t"><span class="w"></span></div>
            <div class="line"></div>
            <div class="member">
                <?php foreach((array)$userList as $k=>$u) {?>
                <div class="v"><a href="space_<?php echo $u['uid'];?>.html"><img src="<?php  echo IMG_SITE.MooGetphoto($u['uid'],'mid');?>" class="lazy" data-original="<?php  echo IMG_SITE.MooGetphoto($u['uid'],'mid');?>" /></a><span class="m"><i><?php if(isset($u['s_cid']) && $u['s_cid']==20) { ?> <img src="module/index/templates/default/images/zuan0.gif"  alt="钻石会员"/><?php } elseif ($u['s_cid'] == 30) { ?> <img src="module/index/templates/default/images/zuan1.gif" alt="高级会员"/><?php } ?></i><?php echo $u['nickname'];?></span><span class="n"><?php if(($u['birthyear']>0)) { ?><?php echo date('Y')-$u['birthyear'];?>岁<?php } else { ?> 保密<?php } ?>，<?php foreach((array)$provice_list as $key=>$val) {?>
                        <?php $province='';?>
                        <?php if($u['province']==$key) { ?>
                        <?php 

                        $province=$val;
                        echo $province;
                        break;
                        ?> 
                        <?php } ?>
                        <?php }?>
                        <?php foreach((array)$city_list as $key=>$val) {?>
                        <?php if($u['city']==$key) { ?>
                        <?php 
                        $city=$val;
                        echo $city;
                        break;
                        ?>
                        <?php } ?>
                        <?php }?><?php if(empty($province)) { ?>保密<?php } ?></span></div>
                <?php }?>

            </div>
        </div>
        <div class="story cf">
            <div class="t"><span class="x"></span><span style="float:right;margin-right:10px;margin-top:10px;"><!-- <a style="text-decoration:none;" href="storyList.html">更多>></a> --></span></div>
            <?php foreach((array)$story as $k=>$s) {?>
            <div class="s"> <a href="story<?php echo $s['sid'];?>.html" target="_blank"><img src="<?php echo MooGetstoryphoto($s['sid'],$s['uid'],'medium');?>" class="lazy"  data-original="<?php echo MooGetstoryphoto($s['sid'],$s['uid'],'medium');?>" /></a> <span><?php echo $s['name1'];?>&<?php echo $s['name2'];?></span>
                <div class="line_"></div>
                <div class="detail"><?php echo MooCutstr($s['content'],140);?></div>
            </div>
            <?php }?>

        </div>
    </div>
    <div class="footer">
	    <!-- <div style="position:fixed;top:300px;right:20px;"><img src="module/index/templates/default/images/vution/Animated_Xmas-tree-animation.gif" /></div> -->
        <div class="g">品牌：专业婚恋服务&nbsp; 专业：庞大的资深红娘队伍&nbsp; 真实：诚信会员验证体系&nbsp; </div>
        <div class="g">Copyright@<?php echo date('Y');?> 真爱一生网.All Right Reserved.<a  href="http://www.miitbeian.gov.cn/" target="_blank">皖ICP备14002819号</a> </div>
        <div class="g">客服热线：400-8787-920 （周一至周日：9：00-21：00）客服信箱：kefu@zhenaiyisheng.cc</div>
    </div>

    <script type="text/javascript" src="public/system/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="module/register/templates/public/js/commonValidate.js?v=2"></script>
    <script type="text/javascript">
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
            if (!checkPhone(username) &&  !CheckEmail(username) && !(/\d+/.test(username))){
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
	
        $('#carousel').carousel({
            interval: 3000
        });
	
        $('input[name=register]').on('click',function(){
            location.href='register.html';
        });
	
        $("img.lazy").show().lazyload({ effect:"fadeIn"});
	
    </script>


    <!--[if lte IE 6]>
    <script type="text/javascript" src="public/default/js/bootstrap-ie.js"></script>
    <![endif]-->


    <div style="display:none">
        <script src="http://s22.cnzz.com/z_stat.php?id=1000262469&web_id=1000262469" language="JavaScript"></script>
        <script type="text/javascript">
            //var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
            //document.write(unescape("%3Cscript src='" + _bdhmProtocol + "hm.baidu.com/h.js%3Fe78b26575943bb3d1af18f473a6f4f5d' type='text/javascript'%3E%3C/script%3E"));
        </script>
    </div>
</body>
</html>

<link href="public/default/css/gototop.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="module/register/templates/public/js/commonValidate.js?v=1"></script>
<script language="javascript">
    jQuery(document).ready(function($){
        jQuery("#floatL,#divFloatToolsView").hover(function(){
            jQuery("#floatTools").stop().animate({right:'0px'},200,function(){jQuery("#floatL").removeClass("fImg1").addClass("fImg2");});		
        },function(){
            jQuery("#floatTools").stop().animate({right:'-116px'},200,function(){jQuery("#floatL").removeClass("fImg2").addClass("fImg1");});
        })	
    })
</script>

<!--右侧边栏-->
<div id="floatTools" class="float0831">
    <div id="floatL" class="floatL fImg1"></div>
    <div id="divFloatToolsView" class="floatR">
        <div class="rT1">在线咨询</div>
        <div class="rBox1">
            <p style="margin-bottom:1px;margin-top:1px;"><a target="_blank" href="http://wpa.qq.com/msgrd?v=3&amp;uin=879207733&amp;site=www.zhenaiyisheng.cc&amp;menu=yes" title="红娘为您服务"><img style="cursor: pointer" class="nobg" src="public/default/images/love-consult.png" alt="红娘为您服务" ></a></p>
            <p style="margin-bottom:1px;margin-top:1px;"><a target="_blank" href="http://wpa.qq.com/msgrd?v=3&amp;uin=879203311&amp;site=www.zhenaiyisheng.cc&amp;menu=yes" title="红娘为您服务"><img style="cursor:pointer;" class="nobg" src="public/default/images/love-consult.png" alt="红娘为您服务" ></a></p>
			<p style="margin-bottom:1px;margin-top:1px;"><a target="_blank" href="http://wpa.qq.com/msgrd?v=3&amp;uin=1464958374&amp;site=www.zhenaiyisheng.cc&amp;menu=yes" title="红娘为您服务"><img style="cursor:pointer;" class="nobg" src="public/default/images/love-consult.png" alt="红娘为您服务" ></a></p>
        </div>
        <div class="rBox2">
            <p style="font-weight:bold;height:25px;line-height:25px;font-size:13px;margin-top:5px;margin-bottom:5px;"><a href="login.html">登录</a> <a href="register.html">注册</a></p>
        </div>
        <div class="rBox3">
            <a href="upgrade.html" target="_blank" title="升级为铂金会员"><img class="nobg" src="public/default/images/platium.gif" alt="升级为铂金会员"></a> 
            <a href="upgrade.html" target="_blank" title="升级为钻石会员"><img class="nobg" src="public/default/images/diamond.gif"  alt="升级为钻石会员"></a> 
            <a href="upgrade.html" target="_blank" title="升级为高级会员"><img class="nobg" src="public/default/images/vip.gif"  alt="升级为高级会员"></a> 
        </div>
        <div class="rBox4">

            <div style="display:block;overflow:hidden;width:110px;margin-left:3px;height:30px;line-height:30px;padding-top:7px;font-weight:bold;font-size:14px;color:#FFFFFF;">猎婚专线</div>
            <div style="display:block;overflow:hidden;width:110px;margin-left:3px;height:30px;line-height:30px;padding-top:7px;font-weight:bold;font-size:14px;color:#F13350;background-color:#ECECEC;margin-bottom:3px;">400-8787-920</div>
        </div>
    </div>
    <div id="backtotop" class="showme">
        <ul>
            <!-- <li class="bttbg"></li> -->
            <li class="bttbg"><a href="#"></a></li>
            <?php if(MooGetGPC('n','string')=='search') { ?>
            <li class="prev"><a href="#"></a></li>
            <li class="next"><a href="#"></a></li>
            <?php } ?>
            <li class="advice"><a href="advices.html"></a></li>
        </ul>
    </div>


</div>
<!--右侧边栏-->

<!--登录模态框-->
<div class="modal fade" id="myModal" tabindex="0" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false" >
  <div class="modal-dialog">
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h5 class="modal-title" id="myModalLabel">登录后，您将了解更多会员的征婚信息和吸引更多的异性会员关注 </h5>
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


<div class="footer">
   <div class="foot-nav">
        <ul>
            <li><a href="aboutus.html">关于我们</a>|</li>
            <li><a href="contact.html">联系我们</a>|</li>
            <li> <a href="links.html">合作伙伴</a>|</li>
            <li><a href="advices.html">意见反馈</a>|</li>
            <li><a href="safetyguide.html">安全征婚</a>|</li>
            <li><?php if(!empty($user_arr['s_cid'])) { ?> <a href="index.php?n=index&h=add_vip&s_cid=<?php echo $user_arr['s_cid'];?>">其他信息</a><?php } else { ?><a href="othervip.html">其他信息</a><?php } ?></li>
           
        </ul>
    </div> 

    <div class="bt">Copyright@<?php echo date('Y');?> 真爱一生网.All Right Reserved.<a target="_blank" href="http://www.miitbeian.gov.cn" class="f-000-a">皖ICP备14002819</a><img src="public/default/images/police.jpg" /></div>
    <div class="cnet"><a href='index.php?n=safetyguide'><img src='public/default/images/safe.jpg' /></a><a href="#"><img src='public/default/images/360c.png' /></a><a href="#">  <img src='public/default/images/cnnic.png'  /> </a>
	    <a href="http://www.hfaic.gov.cn/gongshang/public/index.html" title="" target="_blank"><img src='public/default/images/hficb.jpg' /></a>
		<a href="http://www.miitbeian.gov.cn/publish/query/indexFirst.action" title="" target="_blank"><img src='public/default/images/ICP.jpg' /></a>
		<a href="#"><img src="public/default/images/baidu.gif" /></a>
	</div>



</div>


<?php if($GLOBALS['userid'] && !in_array( MooGetGPC('h','string'), array('return','pay','picflash','makepic','history','chat' ) ) && $_GET['n']!='register' ) { ?>
<?php include MooTemplate('system/rightbottom','public'); ?>
<?php } ?>

<script type="text/javascript">
    function openLogin(){
		$('#myModal').modal({
			backdrop: 'static',
			keyboard: true,
			modal:true
		})
	}
	
	$(document).ready(function(){
	    var url=location.href;
		$("input[name='returnurl']").val(url);
	});

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
	
    $('.advice').on('click',function(){
	    location.href='advices.html';
	});
	

	
    function initGoToTop(){
        var b=jQuery(".float0831").position().top - jQuery(window).height()-200;
        jQuery(function(){jQuery(window).scroll(function(){if(jQuery(this).scrollTop()>100){jQuery("#backtotop").addClass("showme");$('.bttbg').css('display','block');}else{jQuery("#backtotop").removeClass("showme");$('.bttbg').css('display','none');}});
            jQuery("#backtotop").click(function(){jQuery("body,html").animate({scrollTop:0},400);$('.bttbg').css('display','none');return false})});if(jQuery(window).scrollTop()==0){jQuery("#backtotop").removeClass("showme");}else{jQuery("#backtotop").addClass("showme");}
    }

    initGoToTop();

    <?php if(MooGetGPC('n','string')=='search') { ?>
    //搜索页面的上一页
    $('#backtotop li.prev a').on('click',function(){
        var args = getQueryStringArgs();
        var page = args['page']?parseInt(args['page']):0;
        if(page>1){
            page=page-1;
        }else{
            page=1;
        }
        var url=location.href;

        if(url.indexOf('page')>=0){
            url=url.replace(/page=\d+/i,'page='+page);
        }else{
            url=url.replace(/#*/g,'')+'&page='+page;
        }
        location.href=url;
	
    });	

    //搜索页面的下一页
    $('#backtotop li.next a').on('click',function(){
        var args = getQueryStringArgs();
        var page = args['page']?parseInt(args['page']):0;
        page=page+1;
        var url=location.href;
        if(url.indexOf('page')>=0){
            url=url.replace(/page=\d+/i,'page='+page);
        }else{
            url=url.replace(/#*/g,'')+'&page='+page;
        }
        location.href=url;
	
    });	


    function getQueryStringArgs(){
        //get query string without the initial ?
        var qs = (location.search.length > 0 ? location.search.substring(1) : "");
        //object to hold data
        var args = {};
        //get individual items
        var items = qs.split('&');
        var item = null,name = null,value = null;
        //assign each item onto the args object
        for (var i=0; i < items.length; i++){
            item = items[i].split('=');
            name = decodeURIComponent(item[0]);
            value = decodeURIComponent(item[1]);
            args[name] = value;
        }
        return args;
    }
    <?php } ?>
	
	
    
</script>

<div style="display:none;">
    <script src="http://s22.cnzz.com/z_stat.php?id=1000262469&web_id=1000262469" language="JavaScript"></script>
    <script>
	    /*
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-48665834-1', 'zhenaiyisheng.cc');
        ga('send', 'pageview');
        */
    </script>
    
	<?php if(!in_array( MooGetGPC('n','string'), array('search','space' ) )) { ?>
    <script type="text/javascript">
	    
        var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
        document.write(unescape("%3Cscript src='" + _bdhmProtocol + "hm.baidu.com/h.js%3Fe78b26575943bb3d1af18f473a6f4f5d' type='text/javascript'%3E%3C/script%3E"));
        
	</script>
	<?php } ?>
</div>
<!--[if lte IE 6]>
<script type="text/javascript" src="public/default/js/bootstrap-ie.js"></script>
<![endif]-->


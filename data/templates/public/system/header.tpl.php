<div class="top">
    <span style="position:absolute;left:185px;top:20px;background:url('public/default/images/nationalday.png') no-repeat;width:50px;height:50px;"></span> 
    <a href="index.php" class="logo" title="真爱一生网www.zhenaiyisheng.cc"></a>
    <span class="top-tel"></span>
    <?php if($GLOBALS['MooUid']) { ?>
    <div class="top-title">欢迎您 <span class="f-ed0a91"><?php echo MooCutstr($GLOBALS["user_arr"]['nickname'],8,'')?> </span>
    您的ID是 <span class="f-ed0a91"><?php echo $GLOBALS["user_arr"]['uid'];?></span> <a href="index.php?n=service&h=message" class="f-000">最新邮件</a>（<span class="f-ed0a91"><?php echo $message_total = header_show_total($GLOBALS["user_arr"]['uid']);?></span>） <a href="index.php?n=material&h=password" class="f-000">修改密码</a> <a href="./index.php?n=index&h=logout" class="f-000">安全退出</a></div>
    <?php } else { ?>
    <div class="top-title">您好，欢迎来到真爱一生网！[<a href="login.html" class="f-024890">请登录</a>] [<a href="register.html" class="f-024890">免费注册</a>]</div>

    <?php } ?>
    
	<?php if(MooGetGPC('h','string','G')!='channel' && MooGetGPC('n','string','G')!='payment') { ?>
    <div >
	    <div class="showself"></div>
		<span class="umember">  <a href="upgrade.html" id='paya'></a></span>
	</div>
	<!---下拉菜单-->
	<div class="menu1">
		<div class="menu2" style="display: block;">
			<div class="menu3">
				<div class="xtbkk">
					<a href="index.php?n=myaccount&h=telphone" target="_blank">
						<div class="xtb"><img src="public/default/images/tb1.jpg" alt="手机验证,内心独白,上传形象照"></div>
						<div class="xtwz">手机验证</div>
					</a>
				</div>
				<div class="xtbkk">
					<a href="index.php?n=material&h=show" target="_blank">
						<div class="xtb"><img src="public/default/images/tb2.jpg" alt="手机验证,内心独白,上传形象照"></div>
						<div class="xtwz">上传形象照</div>
					</a>
				</div>
				<div class="xtbkk">
					<a href="index.php?n=material&h=upinfo" target="_blank">
						<div class="xtb"><img src="public/default/images/tb3.jpg" alt="手机验证,内心独白,上传形象照"></div>
						<div class="xtwz">发布内心独白</div>
					</a>
				</div>
			</div>
		</div>
	</div>
	<?php } ?>
	<!--导航-->
    <div class="nav">
        <div class="nav-center">
            <ul>
                <li><a href="index.html">首&nbsp;&nbsp;页</a><b></b></li>
                <li><a href="service.html">我的真爱</a><b></b></li>
                <li><a href="search.html">搜索</a><b></b></li>
                <li><a href="material.html">我要征婚</a><b></b></li>
                <li><a href="lovetest.html">爱情测评</a><b></b></li>
                <li><a href="myaccount.html">信用认证</a><b></b></li>
                <li><a href="vote.html">E见钟情</a><b></b></li>
                <!-- <li><a href="index.php?n=myaccount&h=videoindex">视频认证</a><b></b></li> -->
                <li><a href="introduce.html">服务介绍</a></li>
            </ul>
        </div>
    </div><!--nav end-->
    <div class="clear"></div>
	
</div><!--top end-->

<script type="text/javascript">
	$(function(){
		//$("input[name=search_type][value=1]").attr("checked","checked");
		$("input[name=search_type]").click(function(){
			if(this.value == 1){
				$("#search_more").css("display","");
				$("#search_uid").css("display","none");
			}
			if(this.value == 2){
				$("#search_uid").css("display","");
				$("#search_more").css("display","none");
				$("#age1").val('21');
				$("#age2").val('45');
			}

		})
	});


</script>
<script type="text/javascript">
    function checkquicksearch(){
        if($("#s2").attr("checked") == true) {
            if($("#search_uid").val() == '') {
                alert("请输入您要查询的ID");
                return false;
            }
        }
        var age1 = document.getElementById("age1").value;
        var age2 = document.getElementById("age2").value;
        
        if(age1==null || age1==0 ) {
            document.getElementById("age1").value=16;
        }
        if(age2==null || age2==0) {
            document.getElementById("age2").value=100;
        }
     
        return true;

    }
    
	$('.showself').mouseenter(function(){
	    $('.menu1').show();
	});
	$('.menu1').mouseleave(function(){
	    $('.menu1').hide();
	});
</script>

<?php if(MooGetGPC('n','string','G')=='search') { ?>
<div class="head-search">
    <p class="head-search-side h-s-left"></p>
    <div class="head-search-center">
        <form action="index.php" method="get" onsubmit="return checkquicksearch();">
		    <input type="hidden" name="n" value="search" />
            <input type="hidden" name="h" value="quick" />
            <input type="hidden" name="quick_search" value="search" id="quick_search" />
            <span>找朋友</span>
            <span style="margin-top:14px;+margin-top:12px;"><label for="s1"><input name="search_type" type="radio" value="1" checked id="s1"/> 按条件</label></span>
            <span style="margin-top:14px;+margin-top:12px;"><label for="s2"><input name="search_type" type="radio" value="2" id="s2"/> 按ID号</label></span>
            <ul class="for-sines" id="search_more">
                <li>&nbsp;性别&nbsp;<select name="gender">
                        <option value="0" <?php if(isset($GLOBALS["user_arr"]['gender']) && $GLOBALS["user_arr"]['gender'] == 1) { ?>selected<?php } ?>>男</option>
                        <option value="1" <?php if(isset($GLOBALS["user_arr"]['gender']) && $GLOBALS["user_arr"]['gender'] == 0) { ?>selected<?php } ?>>女</option>
                    </select>
                </li>
                <li>年龄&nbsp;<script>getSelect('m2 s47','age1','age_start','','21',age);</script>&nbsp;至&nbsp;<script>getSelect('m2 s47','age2','age_end','','45',age);</script>
                </li>
                <li>
                    地区&nbsp;<script>getProvinceSelect43rds('','workprovince','workprovince','workcity','<?php if(isset($work_province)) { ?><?php echo $work_province;?><?php } else { ?>0<?php } ?>','10100000');</script>
                    <script>getCitySelect43rds('','workcity','workcity','<?php if(isset($work_city)) { ?><?php echo $work_city;?><?php } else { ?>0<?php } ?>','');</script>
                </li>
                <li style="margin-top:15px;+margin-top:11px;"><input name="photo" type="checkbox" id="isphoto" value="1" checked="checked" />有照片</li>
            </ul>
            <input type="text" name="search_uid" id="search_uid" class="index-login-text" style="display:none;border:1px solid #ccc;float:left;margin:8px 10px 0 10px;"/>
            <input type="submit" id="quick_search" name="" class="btn default mlt" value="搜索意中人">

        </form>
    </div>

    <p class="head-search-side h-s-right"></p>
    <div class="clear"></div>
</div><!--head-search end-->
<?php } ?>

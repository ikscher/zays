<?php if(!isset($user)) { ?>
<?php $user = array();?>
<?php } ?>
<?php if(!isset($total)) { ?>
<?php $total = 0;?>
<?php } ?>

<script type="text/javascript">
    $(document).ready(function(){
        $(".search-page-info").mouseenter(function(){
            $(this).css("background","url(module/search/templates/default/images/search-page-bg.gif) repeat-x bottom").mouseleave(function(){
                $(this).css("background","");
            });
        });
    });
</script>
<?php if(isset($isdisp) && $isdisp) { ?>
<div style="disptips">没有查询到完全符合条件的会员，下面是我们给您推荐的会员！</div>
<?php } ?>
<?php foreach((array)$user as $k=>$users) {?>
<ul class="search-page-info">  <!--index.php?n=space&uid=<?php echo $users['uid'];?>-->
    <!--头像-->
	<li>
        <div class="search-page-info-img">
            <?php if(isset($users['city_star']) && !empty($users['city_star'])) { ?>
            <a href="#" class="citystar"><img src="module/search/templates/default/images/citystar.gif" /></a>
            <?php } elseif (isset($users['s_cid']) && $users['s_cid'] == 10) { ?>
            <a href="#" class="citystar"><img src="module/search/templates/default/images/platnium_.gif" /></a>
            <?php } ?>
            <div class="s-img-box" onclick="javascript:window.open('space_<?php echo $users['uid'];?>.html','')">	
                <p>
                    <?php if(isset($users['gender']) && $users['gender'] == '0') { ?>
                    <?php if(isset($users['mainimg']) && $users['mainimg'] && $users['images_ischeck']==1) { ?>
                    <img src="<?php 
                         if(MooGetphoto($users['uid'],'mid')) echo IMG_SITE.MooGetphoto($users['uid'],'mid');
                         elseif(MooGetphoto($users['uid'],'medium')) echo IMG_SITE.MooGetphoto($users['uid'],'medium');	  
                         elseif($users['gender'] == '1') echo 'public/system/images/se_woman.jpg';
                         else  echo 'public/system/images/se_man.jpg';
                         ?>"  class="personalphoto fixphoto"  />
                    <?php } else { ?>
                    <img src="public/system/images/se_man.jpg" />					             
                    <?php } ?>
                    <?php } else { ?>
                    <?php if(isset($users['mainimg']) && $users['mainimg'] && isset($users['images_ischeck']) && $users['images_ischeck']==1) { ?>
                    <img src="<?php 
                         if(MooGetphoto($users['uid'],'mid')) echo IMG_SITE.MooGetphoto($users['uid'],'mid');
                         elseif(MooGetphoto($users['uid'],'medium')) echo IMG_SITE.MooGetphoto($users['uid'],'medium');	  
                         elseif($users['gender'] == '1') echo 'public/system/images/se_woman.jpg';
                         else  echo 'public/system/images/se_man.jpg';
                         ?>"  class="personalphoto fixphoto" />
                    <?php } else { ?>
                    <img src="public/system/images/se_woman.jpg" />					             
                    <?php } ?>
                    <?php } ?>
                </p>
            </div>
        </div>
    </li>
    <li class="w450">
        <dl class="pr h150">
            <dt>
            <?php if(isset($users['s_cid']) && $users['s_cid'] ==20) { ?>							
            <a href="index.php?n=payment&h=diamond" target="_blank"><img src="module/search/templates/default/images/zuan0.gif" title="钻石会员" alt="钻石会员"/></a>
            <?php } elseif (isset($users['s_cid']) && $users['s_cid'] == 30) { ?>							
            <a href="index.php?n=payment" target="_blank"><img src="module/search/templates/default/images/zuan1.gif" title="高级会员" alt="高级会员"/></a>
            <?php } else { ?>							
            <?php } ?>
			<a onclick="javascript:window.open('space_<?php echo $users['uid'];?>.html','')" href="#"><span>
            <?php if(isset($users['nickname']) && $users['nickname']!='') { ?>
            <?php echo MooCutstr(trim($users['nickname']),'10','');?>
            <?php } else { ?>
            ID:<?php echo $users['uid'];?>
            <?php } ?>
			</span></a>
			<span style="margin-left:230px;"><?php if(isset($match) && $match=='automatch') { ?>匹配度:<?php echo $users['mark']?>%<?php } else { ?>诚信度:<?php echo get_integrity($users['certification'], 'true');?><?php } ?></span>
            </dt>
			
			<dd>
           
                <?php if(isset($users['birthyear']) && $users['birthyear']) { ?><?php echo (date('Y', time()) - $users['birthyear']);?>岁<?php } else { ?>年龄保密<?php } ?>&nbsp;
                <?php if(isset($users['province']) && isset($users['city']) && ($users['province'] == '0' || $users['province'] == '0') && ($users['city'] == '0' || $users['city'] == 0)) { ?>
                保密
                <?php } else { ?>
                <span class="address"><script><?php if(isset($users['province'])) { ?>userdetail("<?php echo $users['province'];?>",provice)<?php } ?></script>
                <script><?php if(isset($users['city'])) { ?>userdetail("<?php echo $users['city'];?>",city)<?php } ?></script></span>
                <?php } ?>
            
                身高：<?php if(isset($users['height']) && ($users['height'] == '0' || $users['height'] == '0')) { ?>保密<?php } else { ?><script>userdetail("<?php echo $users['height'];?>",height)</script>厘米<?php } ?>
                学历：<?php if(isset($users['education']) && ($users['education'] == '0' || $users['education'] == '0')) { ?>保密<?php } else { ?><script>userdetail("<?php echo $users['education'];?>",education)</script><?php } ?>
            
                
                <?php echo loveStatus($users);?>

            
                <?php $seach = choice_user($users['uid'])?>

                寻找
                <?php if(isset($seach['age1']) && isset($seach['age2']) && ($seach['age1'] == '0' || $seach['age1'] == '0') && ($seach['age2'] == '0' || $seach['age2'] == '0')) { ?>
                年龄不限
                <?php } elseif ($seach['age1'] != '0' && $seach['age2'] == '0') { ?>
                <?php echo $seach['age1'];?>岁以上
                <?php } elseif ($seach['age1'] == '0' && $seach['age2'] != '0') { ?>
                <?php echo $seach['age2'];?>岁以下
                <?php } else { ?>
                <?php echo $seach['age1'];?>-<?php echo $seach['age2'];?>岁
                <?php } ?>
                <?php if(isset($users['gender']) && $users['gender'] == 0) { ?>女士<?php } else { ?>男士<?php } ?>
            

            
                
            </dd>
			 <dd>
                <p class="c999">
                    <?php echo isset($seach['introduce']) && $seach['introduce']?Moorestr(MooCutstr($seach['introduce'], 120, $dot = ' ...')):'无内心独白内容'?>
                </p>
            </dd>
			 <dd class="modalContainer">
			    <a  class="modalLink  bt5"   data-uid="<?php echo $users['uid'];?>"  href="#modalLeer">送秋波</a>
				<a  class="modalLink  bt55"  data-uid="<?php echo $users['uid'];?>"  href="#modalLiker">加关注</a>
                <a  class="modalLink  bt555" data-uid="<?php echo $users['uid'];?>"  href="#modalCommission">委托红娘联系TA</a>
            </dd>
        </dl>
    </li>
   
</ul>
<?php }?>
<div class="clear"></div>
<?php if(isset($isdisp2) && $isdisp2) { ?>
<div id="disptips" class="disptips">还有很多比较符合条件的会员，<a href="<?php echo $currenturl2;?>&dm=1&page=<?php echo $page;?>" class="dispInfo">显示更多条件相似的会员</a></div>
<?php } ?>
<div class="search-page-bottom">
    <?php 
    $currenturl3 = preg_replace("/(&issavesearchname=\d+)/","",$currenturl2);
    $currenturl4 = preg_replace("/(issavesearchname=\d+)/","",$currenturl3);
    $multi_pages = multipage($total,$pagesize,$page,$currenturl4); ?>
    <?php if(!empty($multi_pages)) { ?>
    <?php echo $multi_pages;?>
    <a class="tv">转到第<input name="page"  id="pageGo" type="text" style="width:25px;border:1px solid #ccc;" value="" onkeydown="enterHandler(event)"/>页
        <input type="button"  class="go" value="GO" onclick="gotoPage()"/></a>
    <?php } ?>
</div>
<div class="clear" style="clear:both;"></div>
</div>
<?php if($GLOBALS['MooUid']) { ?>
<!--弹出框的遮罩-->
<div class="overlay"></div>
<!--秋波-->
<div id="modalLeer" class="_modal_">
    <h4>给 <span class="nickname"></span> 抛个媚眼吧！</h4>
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


<!--委托-->
<div id="modalCommission" class="_modal_">
	<div class="h6_top"><b class="b_v"><img src="public/default/images/commissionBtn.png"></b><span class="closeC"></span></div>
	<div class="tan_k_z">
		<em></em>
		<div class="tan_k_v">
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
<?php } ?>

<script src="public/system/js/jquery.modal.min.js" type="text/javascript"></script>
<?php if($GLOBALS['MooUid']) { ?><script src="public/system/js/jquery.modal.site.js" type="text/javascript"></script><?php } ?>
<!--图像修正-->
<script type="text/javascript">
    $(document).ready(function(){
        var theImage = new Image();
        $(".personalphoto").each(function(i,w){
            theImage.src = $(w).attr("src");
            var imageWidth = theImage.width;
            var imageHeight = theImage.height;
            if(imageHeight>=130){
                $(w).removeClass('fixphoto');
                $(w).addClass('fixphoto_');
            }
        })
    });
	

	//发送秋波
	$('a[class^=leerConfirm]').click(function(){
	    var l=$('input[name=leerinfo]:checked').val();
		var info=$('input[name=leerinfo]:checked').next('label').html();
		if(l == undefined) {
		    $('.leerTips').html('请选择要向对方发送的告白！！！');
		}else{
		    var uid=$('.uinfo .touid').html().replace(/ID:/,'');
		    $.ajax({
				url:'ajax.php?n=service&h=sendleer',
				data:{uid:uid,info:info},
				type:'post',
				dataType:'text',
				success:function(str){
					if(str=='sameone'){
					    alert('自己不能给自己发送秋波!');	
					}else if(str=='simulate'){
					    alert('不能模拟!');
					}else if(str=='shield'){
					    alert('对方已屏蔽的信息');
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
					    alert('对方已屏蔽的信息');
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
					alert('不能模拟关注!');
				}else if(str=='shield'){
					alert('对方已屏蔽的信息');
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
	
	//清空秋波表白警告语
	$('.zf_cb').on('click','input[name=leerinfo]',function(){
	    $('.leerTips').html('');
	});
	//清空委托话语
	$('p').on('click','#commission_content',function(){
	    $('.cTips').html('');
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
			var img=$(this).parents('li').siblings('li:eq(0)').find('.s-img-box p').html();
			$('.zp_tp').html('<p style="width:110px;height:138px;overflow:hidden;padding-top:10px;">'+img+'</p>');
			var nickname=$(this).parents('li').find('dt span:eq(0)').html();
			$('.nickname').html($.trim(nickname));
			$('.uinfo .name').html($.trim(nickname));
			$('.uinfo .touid').html('ID:'+$(this).attr('data-uid'));
			var address=$(this).parents('li').find('dd span.address').html();
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
		    var nickname=$(this).parents('li').find('dt span:eq(0)').html();
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
		    var nickname=$(this).parents('li').find('dt span:eq(0)').html();
			$('.nickname').html($.trim(nickname));
			var uid=$(this).attr('data-uid');
			$('input[name="luid"]').val(uid);
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
	
	
</script> 
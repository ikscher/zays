<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>我留意过谁--我的真爱一生——真爱一生网</title>
<link href="module/service/templates/default/service.css" rel="stylesheet" type="text/css" />
<?php include MooTemplate('system/js_css','public'); ?>
<script src="module/service/templates/public/js/service_submit_page.js"></script>

<script>
// 切换tab
function setTab(name,cursel,n){
  if(cursel == 1) {
    // 跳转到谁留意过我
    window.location.href="index.php?n=service&h=mindme&t=whoadverti";
  }
  
  if(cursel == 2) {
    // 跳转到我留意过谁
    window.location.href="index.php?n=service&h=mindme&t=iadvertwho";       
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
        
    window.location.href = "<?php echo $currenturl2;?>&page="+value;
}
// 删除全选功能
function checkAll(chk){  
    var checkboxes = document.getElementsByName("delvisitorid[]");
    for(j=0; j < checkboxes.length; j++) {
        checkboxes[j].checked = chk.checked;
    }
}
</script>
<style type="text/css">
	.fixphoto_{max-width:171px;max-height:123px;margin-left:-35px;}
</style>
</head>

<body>
<div class="main">
	<?php include MooTemplate('system/header','public'); ?><!--top end-->	
	<div class="content">
	<p class="c-title"><span class="f-000"><a href="index.php">真爱一生首页</a>&nbsp;&gt;&gt;&nbsp;<a href="index.php?n=service">我的真爱一生</a>&nbsp;&gt;&gt;&nbsp;我留意过谁</span><a href="index.php?n=invite" target="_blank" class="loaction_right">邀请好友</a></p>
	<div class="content-lift">
		<?php include MooTemplate('public/service_left','module'); ?>	
	</div><!--content-lift end-->
	<!--=====左边结束===-->
	<div class="content-right">
		<div class="c-right-content">
			<div class="r-center-tilte">
			<a href="index.php?n=service" class="r-title-black">&lt;&lt;返回我的真爱一生</a>
			<span class="right-title f-ed0a91">我留意过谁</span>			
			</div>
			<div class="r-center-ccontent" style="padding:20px 0 60px; width:753px;">
				<!--=======公共头部=====-->
			<div class="service-title">
				<ul>
					<li><a href="#"  id="two1" onclick="setTab('two',1,4)"><span>谁留意过我（<?php echo $towho;?>）</span></a></li>
					<li><a href="#" class="onthis" id="two2" onclick="setTab('two',2,4)"><span>我留意过谁（<?php echo $total;?>）</span></a></li>
				</ul>
				<div class="clear"></div>
			</div>
			<!--====公共头部结束==-->
			<div class="service-liker">
			<form name="form1" action="index.php?n=service&h=mindmeindex.php?n=service&h=mindme&t=iadvertwho" method="post" onsubmit="return checkPageGo('delvisitorid[]')">
			<?php if($total == 0) { ?>
            <div class="norequest">
               您还没有看过其他会员的资料，
              <p> 马上去 <a href="index.php?n=search">搜索意中人</a></p>
            </div>
              <?php } else { ?>
			  <?php foreach((array)$visitors as $visitor) {?>
             
				<ul class="service-liker-list">
					<li><input type="checkbox" name="delvisitorid[]" value="<?php echo $visitor['l']['vid'];?>"/></li>
					<li>
						<div class="r-service-img">
						<?php if(!empty($visitor['s']['city_star'])) { ?><a class="citystar2"><img src="module/service/templates/default/images/citystar.gif" /></a><?php } ?>
							<div class="r-s-img-in">
								<p style="width:105px;"> <a style="display:block;" href="index.php?n=space&h=viewpro&uid=<?php echo $visitor['l']['visitorid'];?>" target="_blank">
										 <?php if($visitor['s']['images_ischeck']=='1'&& $visitor['s']['mainimg']) { ?>
											  <img src="<?php if(MooGetphoto($visitor['s'][uid],'mid')) echo MooGetphoto($visitor['s'][uid],'mid');
											  elseif(MooGetphoto($visitor['s'][uid],'medium')) echo MooGetphoto($visitor['s'][uid],'medium');
											  elseif($visitor['s']['gender'] == '1') echo 'public/system/images/woman_100.gif';
											  else echo 'public/system/images/man_100.gif';?>" />
											   <?php } elseif ($visitor['s']['mainimg']) { ?>
												<?php if($visitor['s']['gender'] == '1') { ?>
													<img src="public/system/images/woman_100.gif"/>
												<?php } else { ?>
													<img src="public/system/images/man_100.gif"  />
												<?php } ?>
									
										<?php } else { ?>
											<?php if($visitor['s']['gender'] == '1') { ?>
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
							<dt> <?php if($visitor['s']['s_cid'] ==20) { ?>
									<a href="index.php?n=payment&h=diamond" target="_blank"><img src="module/index/templates/default/images/zuan0.gif" title="钻石会员" alt="钻石会员"/></a>
									<?php } elseif ($visitor['s']['s_cid'] == 30) { ?>
									<a href="index.php?n=payment" target="_blank"><img src="module/index/templates/default/images/zuan1.gif" title="高级会员" alt="高级会员"/></a>
									<?php } else { ?>
									<?php } ?>
									<a id="fzs" href="index.php?n=space&h=viewpro&uid=<?php echo $visitors['visitorid'];?>"><?php echo $visitor['s']['nickname']?$visitor['s']['nickname']:'ID:'.$visitor['s']['uid']?></a>
							</dt>
							<dd>年龄：<?php if($visitor['s']['birthyear']) { ?><?php echo (gmdate('Y', time()) - $visitor['s']['birthyear']);?>岁<?php } else { ?>保密<?php } ?></dd>
							<dd>身高：<script>userdetail("<?php echo $visitor['s'][height];?>",height);</script></dd>
							<dd>收入：<script>userdetail("<?php echo $visitor['s'][salary];?>",salary1);</script></dd>
							<dd>学历：<script>userdetail("<?php echo $visitor['s'][education];?>",education);</script></dd>
							<dd>地址：<?php if($visitor['s'][province] == '0' && $visitor['s'][city] == '0') { ?>保密<?php } else { ?><script>userdetail('<?php echo $visitor['s'][province];?>',provice);userdetail('<?php echo $visitor['s'][city];?>',city)</script><?php } ?></dd>
							<!-- <dd class="f-ed0a91">&lt;<?php echo activetime($visitor['s']['lastvisit'],$visitor['s']['usertype']);?>&gt; </dd> -->
						</dl>
					</li>
					<li class="fright">
						<dl class="r-service-heart">
							<dt>内心独白</dt>
							<dd class="r-service-heart-text">
								<?php echo $visitor['t']['introduce']?MooCutstr($visitor['t']['introduce'], 148, $dot = ' ...'):'无内心独白内容'?>
								</dd>
							<dd>
								<p>浏览时间：<span class="f-ed0a91"><?php echo date("Y年m月d日 ",$visitor['l']['visitortime']);?></span></p></dd>
						</dl>	
					</li>
				</ul>
				<?php } ?>
				<div class="clear"></div>
				<div class="pages"><span class="fleft" style="padding-left:40px;"><input type="checkbox"  name="allcheck" onclick="checkAll(this)" />&nbsp;全选&nbsp;<input name="" type="submit" class="btn btn-default" value="删除信息" /><input type="hidden"  name="delvisitor"  value="删除"></span><?php echo multimail($total,$pagesize,$page,$currenturl2)?><input name="pageGo"  id="pageGo" type="text" size="3" />&nbsp;<input name="" type="button" class="go-page" value="GO" onclick="gotoPage()" style="float:none"/>
					<div class="clear"></div>
				</div>
				<?php } ?>
			</div>
			<div class="clear"></div>
			</div>
			
			 </form>
			<div class="r-center-bottom">
			</div>
			<div class="clear"></div>
		</div>
		
	</div>
	<div class="clear"></div>
	</div>
	
	<!--content end-->
	<?php include MooTemplate('system/footer','public'); ?><!--foot end-->
</div><!--main end-->
<script type="text/javascript">
    $(document).ready(function(){
        var theImage = new Image();
		
        $(".r-s-img-in a img").each(function(i,w){
            theImage.src = $(w).attr("src");
            var imageWidth = theImage.width;
            var imageHeight = theImage.height;
            if(imageHeight<200){
                //$(w).removeClass('fixphoto');
                $(w).addClass('fixphoto_');
            }else{
			    //DrawImage($(w),100,125);
			}
        })
    });
</script>
</body>
</html>

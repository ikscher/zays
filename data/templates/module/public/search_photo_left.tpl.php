<script type="text/javascript">
<!--
$(document).ready(function(){
$(".search-photo-img").mouseenter(function(){
		$(this).css("background","url(module/search/templates/default/images/photo-img-onbg.gif)").mouseleave(function(){
		$(this).css("background","");
	});
});
});	
//-->
</script>
<?php if(isset($isdisp) && $isdisp) { ?>
<div style="height:23px;text-align:center;background-color:#F0E68C;margin:10px;padding:10px;">没有查询到完全符合条件的会员，下面是我们给您推荐的会员！</div>
<?php } ?>
<?php foreach((array)$user as $k=>$users) {?>
<dl class="search-photo-img"  style="cursor:pointer;" onclick="javascript:window.open('space_<?php echo $users['uid'];?>.html','')">	
	<dt>
		<?php if(!empty($users['city_star'])) { ?>
			<a href="#" class="citystar"><img src="module/search/templates/default/images/citystar.gif" /></a>
		<?php } elseif (isset($users['s_cid']) && $users['s_cid'] == 10) { ?>
			<a href="#" class="citystar"><img src="module/search/templates/default/images/platnium_.gif" /></a>
		<?php } ?>
		<div class="s-img-box">	
		<p style="width:100px">
		<?php if(isset($users['gender']) && $users['gender'] == '0') { ?>
			<?php if(isset($users['mainimg']) && $users['mainimg'] && $users['images_ischeck']==1) { ?>
			<img src="<?php 
			  if(MooGetphoto($users['uid'],'mid')) echo MooGetphoto($users['uid'],'mid');
			  elseif(MooGetphoto($users['uid'],'medium')) echo MooGetphoto($users['uid'],'medium');
			  elseif(MooGetphoto($users['uid'],'index')) echo MooGetphoto($users['uid'],'index');
			  elseif($users['gender'] == '1') echo 'public/system/images/se_woman.jpg';
			  else  echo 'public/system/images/se_man.jpg';
			?>"  class="personalphoto fixphoto"  />
			<?php } else { ?>
			 <img src="public/system/images/se_man.jpg" />					             
			<?php } ?>
		<?php } else { ?>
			<?php if(isset($users['mainimg']) && $users['mainimg'] && isset($users['images_ischeck']) && $users['images_ischeck']==1) { ?>
			<img src="<?php 
			  if(MooGetphoto($users['uid'],'mid')) echo MooGetphoto($users['uid'],'mid');
			  elseif(MooGetphoto($users['uid'],'medium')) echo MooGetphoto($users['uid'],'medium');
			  elseif(MooGetphoto($users['uid'],'index')) echo MooGetphoto($users['uid'],'index');
			  elseif($users['gender'] == '1') echo 'public/system/images/se_woman.jpg';
			  else  echo 'public/system/images/se_man.jpg';
			?>"  class="personalphoto fixphoto"  />
			<?php } else { ?>
			 <img src="public/system/images/se_woman.jpg" />					             
			<?php } ?>
		<?php } ?>
		</p>
		</div>
	</dt>
	<dd>
		<span class="f-b-d73c90">
		<?php if(isset($users['s_cid']) && $users['s_cid'] ==20) { ?>							
			<a href="index.php?n=payment&h=diamond" target="_blank"><img src="module/search/templates/default/images/zuan0.gif" title="钻石会员" alt="钻石会员"/></a>
		<?php } elseif (isset($users['s_cid']) && $users['s_cid'] == 30) { ?>							
			<a href="index.php?n=payment" target="_blank"><img src="module/search/templates/default/images/zuan1.gif" title="高级会员" alt="高级会员"/></a>
		<?php } else { ?>							
		<?php } ?>
		<?php if(isset($users['nickname']) && $users['nickname']!='') { ?>
			<?php echo MooCutstr($users['nickname'],'7','');?>
		<?php } else { ?>
			ID:<?php echo MooCutstr($users['uid'],'6','');?>
		<?php } ?>
		</span>
		<?php if(isset($users['birthyear']) && $users['birthyear']) { ?><?php echo (date('Y', time()) - $users['birthyear']);?>岁<?php } else { ?>年龄保密<?php } ?></dd>
	<dd>
		<p class="f-0c7ba4">
			<?php if(isset($users['lastvisit']) && $users['usertype']) { ?>
			<!-- <?php echo activetime($users['lastvisit'],$users['usertype']);?> -->
			<?php echo loveStatus($users);?>
			<?php } ?>
		</p>
	</dd>
	<dd>
		<?php if(isset($match) && $match=='automatch') { ?>匹配度:<?php echo $users['mark']?>%<?php } else { ?>诚信度:<?php echo get_integrity($users['certification'], 'true');?><?php } ?>
	</dd>
	<dd>
		<a href="#" class="go-look" target="blank"  onclick="javascript:window.open('space_<?php echo $users['uid'];?>.html','')">查看</a>
		<a href="index.php?n=service&h=message&t=send&sendtoid=<?php echo $users['uid'];?>" class="go-word">留言</a>
	</dd>
</dl>
<?php }?>
<div class="clear" style="clear:both;"></div>
<?php if(isset($isdisp2) && $isdisp2) { ?>
<div id="disptips" style="height:23px;text-align:center;background-color:#F0E68C;margin:10px;padding:10px;">还有很多比较符合条件的会员，<a href="<?php echo $currenturl2;?>&dm=1&page=<?php echo $page;?>">显示更多条件相似的会员</a></div>
<?php } ?>
<div class="search-page-bottom" style="color:#000;">
<?php 
$currenturl3 = preg_replace("/(&issavesearchname=\d+)/","",$currenturl2);
$currenturl4 = preg_replace("/(issavesearchname=\d+)/","",$currenturl3);
$multi_pages = multipage($total,$pagesize,$page,$currenturl4); ?>
<?php if(!empty($multi_pages)) { ?>
<?php echo $multi_pages;?>
&nbsp;&nbsp;转到   
第<input name="page"  id="pageGo" type="text" style="width:35px;border:1px solid #ccc;" value="" onkeydown="enterHandler(event)"/>页
<input type="button"  class="go" value="GO" onclick="gotoPage()"/>
<?php } ?>
</div>
<div class="clear" style="clear:both;"></div>
</div>
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
</script> 
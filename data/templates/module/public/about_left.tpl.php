<!--左边开始-->
<div class="about-left">
	<div class="about-left-box">
		<div class="about-left-box-side"></div>
		<div class="about-left-box-center">
			<ul class="about-left-nav">
				<li><a href="index.php?n=about&h=us" id="about_us">关于我们</a></li>
                <!--  
				<li><a href="index.php?n=about&h=media" id="about_media">媒体关注</a></li>
                -->
				<li><a href="index.php?n=about&h=contact" id="about_contact">联系我们</a></li>
				<li><a href="index.php?n=about&h=links" id="about_links">合作伙伴</a></li>
				<li><a href="index.php?n=about&h=copyright" id="about_copyright">版权政策</a></li>
				<li><a href="index.php?n=about&h=getsave" id="about_getsave">投诉建议</a></li>
				<li><a href="index.php?n=about&h=clinic" id="about_clinic">情感诊所</a></li>
				<!-- <li><a href="index.php?n=activity&h=activity" id="about_activity">相亲活动</a></li> -->
                <!-- 
				<li><a href="index.php?n=about&h=glory" id="about_glory">荣&nbsp;&nbsp;&nbsp;&nbsp;誉</a></li>
                 -->
			</ul>					
		</div>
		<div class="about-left-box-side" style="background-position:bottom;"></div>
	</div>	
	<div class="about-left-box" style="margin-bottom:20px;">
	<a href="index.php?n=register"><img src="module/about/templates/default/images/go-reg.gif" /></a>
	</div>	
    
    <!-- pufang advertisment 
    <div> <a href="http://www.pufung.com/huodong/sajiaozhuanti.html" target="_blank" ><img  src="module/about/templates/default/images/sajiao_about.jpg" border="0" width="180" height="134" /></a></div>-->
    				
</div>	
<!--左边结束-->

<script type="text/javascript">
	$(document).ready(function(){
		$("#about_<?php echo $left_menu;?>").addClass("onthis");
	});
</script>

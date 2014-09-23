<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>会员评分——E见钟情——真爱一生网</title>
<?php include MooTemplate('system/js_css','public'); ?>
<link href="module/vote/templates/<?php echo $GLOBALS['style_name'];?>/vote.css" type="text/css" rel="stylesheet" />
<script src="public/system/js/list.js" type="text/javascript"></script>
<!-- <script src="public/system/js/syscode.js?v=1" type="text/javascript"></script> -->
<script>
function copyToClipboard(txt) {       
     if(window.clipboardData) {           
              window.clipboardData.setData("Text", txt);       
              alert("复制成功！")      
      } else if(navigator.userAgent.indexOf("Opera") != -1) {       
           window.location = txt;       
      } else if (window.netscape) {       
          try {       
                netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");       
           } catch (e) {       
                alert("被浏览器拒绝！\n请在浏览器地址栏输入'about:config'并回车\n然后将 'signed.applets.codebase_principal_support'设置为'true'");       
           }       
          var clip = Components.classes['@mozilla.org/widget/clipboard;1'].createInstance(Components.interfaces.nsIClipboard);       
          if (!clip)       
               return;       
          var trans = Components.classes['@mozilla.org/widget/transferable;1'].createInstance(Components.interfaces.nsITransferable);       
          if (!trans)       
               return;       
           trans.addDataFlavor('text/unicode');       
          var str = new Object();       
          var len = new Object();       
          var str = Components.classes["@mozilla.org/supports-string;1"].createInstance(Components.interfaces.nsISupportsString);       
          var copytext = txt;       
           str.data = copytext;       
           trans.setTransferData("text/unicode",str,copytext.length*2);       
          var clipid = Components.interfaces.nsIClipboard;       
          if (!clip)       
               return false;       
           clip.setData(trans,null,clipid.kGlobalClipboard);       
           alert("复制成功！")       
      }       
}
</script>
</head>
<body>

<?php include MooTemplate('system/header','public'); ?>
<!--头部结束-->
<div class="clear"></div>
<div class="content">
<div class="c-title">
<span class="f-000"><a href="index.php">真爱一生首页</a>&nbsp;&gt;&gt;&nbsp;E见钟情</span>
<div class="loaction_right">
	<a href="index.php?n=invite" target="_blank">邀请好友</a>
</div>
</div>
<div class="content-lift">
<div class="c-lift-content">
<?php if($last_mem && $last_mem['uid']!='') { ?>
<div class="c-lift-title">		
	上一个会员的平均得分
</div>
<div class="c-lift-content-in">
<div class="clear"></div>
<div class="left-content-c">
<dl class="vote-left">
<dt><?php echo $counts_s;?></dt>
<dd style="padding:15px 0;">根据<?php echo $counts_c;?>位会员评分结果</dd>
<dd class="left-showimg">
<div class="vote-left-img">
<p>
<?php if(isset($last_mem['mainimg']) && $last_mem['images_ischeck']=='1'&& $last_mem['mainimg']) { ?>
	<img src="<?php 
			  if($photo_path = MooGetphoto($last_mem['uid'],'mid')) echo IMG_SITE.$photo_path;
			  elseif($photo_path = MooGetphoto($last_mem['uid'],'medium')) echo IMG_SITE.$photo_path;
			  elseif($last_mem['gender'] == '1')echo 'public/system/images/se_woman.jpg';
			  else  echo 'public/system/images/se_man.jpg';
			  ?>" onload="javascript:DrawImage(this,100,125)" width="95px;"/>
<?php } elseif (isset($last_mem['mainimg']) && $last_mem['mainimg']) { ?>
	   	<?php if($last_mem['gender'] == '1') { ?>
			<img src="public/system/images/se_woman.jpg" />
		<?php } else { ?>
			<img src="public/system/images/se_man.jpg"  />
		<?php } ?>
<?php } else { ?>
		<?php if(isset($last_mem['gender'])) { ?>
			<?php if($last_mem['gender'] == '1') { ?>
				<img src="public/system/images/se_woman.jpg"/>
			<?php } else { ?>
				<img src="public/system/images/se_man.jpg"/>
			<?php } ?>
		<?php } else { ?>
			<?php if(isset($pic['gender']) && $pic['gender'] == '1') { ?>
				<img src="public/system/images/se_woman.jpg"/>
			<?php } else { ?>
				<img src="public/system/images/se_man.jpg"/>
			<?php } ?>
		<?php } ?>
<?php } ?>
</p>
</div>
</dd>
<dd style="padding:15px 0 0;"><a target="_blank" href="index.php?n=space&h=viewpro&uid=<?php echo $last_mem['uid'];?>" class="link1">查看<?php echo $last_mem['gender'] ? '她' : '他'?>的详细资料&gt;&gt;</a></dd>
<dd><a href="###" onclick="copyToClipboard('http://www.zhenaiyisheng.cc/index.php?n=recommend&uid=<?php echo $last_mem['uid'];?>&puid=<?php echo $user_arr['uid'];?>');" class="link1">将<?php echo $last_mem['gender'] ? '她' : '他'?>发给朋友看看&gt;&gt;</a></dd>
<dd><input name="" type="button" class="btn btn-default" value="我也要上传照片" onclick="javascript:window.open('index.php?n=material&amp;h=show')"/></dd>
</dl>
</div>			
</div>
<?php } else { ?>
<div class="c-lift-title">		
	您知道吗？
</div>
<div class="c-lift-content-in">
<dl class="no-vote">
	 <dd>1. 给当前用户评分后，您即可查看对方在真爱一生网的平均得分，以及参与评分的人数。</dd>
	 <dd>2. 如果您喜欢对方，可以查看对方的详细资料并与其联系。</dd>
	 <dt><input name="" type="button" class="up-photo-button" value="我也要上传照片" onclick="javascript:window.open('index.php?n=material&amp;h=show')"/></dt>
 </dl>
 </div>
<?php } ?>
<div class="c-lift-bottom"></div>
</div><!--c-lift-content-->
</div><!--content-lift end-->

<!--=====左边结束===-->
<!--中间开始-->
<div class="vote-cnter">
<div class="vote-cnter-title">
<span class="right-title">请给该会员打分</span>（评分完成后即可查看下一名会员） </div>
<div class="c-center">
<div class="center-mark">
<div class="mark-box">
<p>
<input id="counts_1" name="counts" type="radio" value="1" onclick="javascript:vote_member(this);" />
<input name="counts" id="counts_2" type="radio" value="2"  onclick="javascript:vote_member(this);"/>
<input name="counts" id="counts_3" type="radio" value="3" onclick="javascript:vote_member(this);" />
<input name="counts" id="counts_4" type="radio" value="4" onclick="javascript:vote_member(this);" />
<input name="counts" type="radio" id="counts_5" value="5" onclick="javascript:vote_member(this);" />
<input name="counts" type="radio" id="counts_6" value="6" onclick="javascript:vote_member(this);" />
<input name="counts" type="radio" id="counts_7" value="7"  onclick="javascript:vote_member(this);"/>
<input name="counts" type="radio" id="counts_8" value="8" onclick="javascript:vote_member(this);" />
<input name="counts" type="radio" id="counts_9" value="9" onclick="javascript:vote_member(this);" />
<input name="counts" type="radio" id="counts_10" value="10" onclick="javascript:vote_member(this);" />
</p>
</div>
<p style="text-align:center; padding-top:20px;">给我看
<select name="gender" size="1" id="gender" onchange="javascript:location.href='index.php?n=vote&age=<?php echo $age;?>&sex='+this.value">
	<option value="0">请选择</option>
	<option value="1" <?php if($sex==1) { ?>selected=selected<?php } ?> >男士</option>
	<option value="2" <?php if($sex==2) { ?>selected=selected<?php } ?>>女士</option>
</select>
年龄：
<select name="aged" id="aged" onchange="javascript:location.href='index.php?n=vote&sex=<?php echo $sex;?>&age='+this.value">
   <option value="1" <?php if($age==1) { ?>selected="selected"<?php } ?>>18-25</option>
   <option value="2" <?php if($age==2) { ?>selected="selected"<?php } ?>>26-32</option>
   <option value="3" <?php if($age==3) { ?>selected="selected"<?php } ?>>33-40</option>
   <option value="4" <?php if($age==4) { ?>selected="selected"<?php } ?>>40以上</option>
 </select> 岁
</p>
</div>
<dl class="vote-show">
<dd>
<?php if($vote_mem['images_ischeck']=='1'&& $vote_mem['mainimg']) { ?>
	 <img src="<?php   
	  if(MooGetphoto($vote_mem['uid'],'mid')) echo IMG_SITE.MooGetphoto($vote_mem['uid'],'mid');
	  elseif($vote_mem['gender'] == '1') echo 'public/system/images/se_woman.jpg';
	  else  echo 'public/system/images/se_man.jpg';
	  ?>" />
<?php } elseif ($vote_mem['mainimg']) { ?>
<?php if($vote_mem['gender'] == '1') { ?>
		<img src="public/system/images/se_woman.jpg" />
	<?php } else { ?>
		<img src="public/system/images/se_man.jpg"  />
	<?php } ?>

<?php } else { ?>
	<?php if($vote_mem['gender'] == '1') { ?>
		<img src="public/system/images/se_woman.jpg"  />
	<?php } else { ?>
		<img src="public/system/images/se_man.jpg"  />
	<?php } ?>
<?php } ?>
</dd>
<dd><a target="_blank" href="index.php?n=space&h=viewpro&uid=<?php echo $vote_mem['uid'];?>" class="f-ed0a91-a">查看该会员详细资料</a></dd>
<dd>
转发给朋友看看：<input name="one_copy" id="one_copy" type="text" class="vote-copy-text" value="http://www.zhenaiyisheng.cc/index.php?n=recommend&uid=<?php echo $vote_mem['uid'];?>&puid2=<?php echo $user_arr['uid'];?>" /> <a href="###" onclick="copyToClipboard(document.getElementById('one_copy').value);" class="f-ed0a91-a">复制</a>
</dd>
<div id="flashcopier"></div>
<dt><strong>内心独白：</strong></dt>
<dt>
<?php if(isset($vote_mem['introduce'])) { ?>
	<?php if($vote_mem['uid'] < 1599400) { ?>
	   <?php echo MooStrReplace(Moorestr(strip_tags(MooCutstr($vote_mem['introduce'], 500))))?>
	<?php } else { ?>
	   <?php echo Moorestr(strip_tags(MooCutstr($vote_mem['introduce'], 500)))?>
	<?php } ?>
<?php } ?>
</dt>
</dl>
</div>
<div class="vote-cnter-bottom"></div>	
</div>	
<!--中间结束-->
<!--右边开始-->
<div class="vote-right">
<div class="vote-right-content">		
<span class="right-title fleft">等您打分的会员</span>
<ul class="votr-photos">
<?php if(isset($pic_4)) { ?>
<?php foreach((array)$pic_4 as $pic) {?>
<li>
<div class="votr-photos-list">
<p>
<?php if($pic['images_ischeck']=='1'&& $pic['mainimg']) { ?>
	<img src="<?php   
	  if(MooGetphoto($pic['uid'],'mid')) echo IMG_SITE.MooGetphoto($pic['uid'],'mid');
	  elseif($pic['gender'] == '1')echo 'public/system/images/se_woman_60.jpg';
	  else  echo 'public/system/images/se_man_60.jpg';
	  ?>" width="60" height="75"/>
<?php } elseif ($pic['mainimg']) { ?>
   <?php if($pic['gender'] == '1') { ?>
			<img src="public/system/images/se_woman_60.jpg" width="60" height="75"/>
		<?php } else { ?>
			<img src="public/system/images/se_man_60.jpg" width="60" height="75"/>
		<?php } ?>
	<?php } else { ?>
		<?php if($pic['gender'] == '1') { ?>
			<img src="public/system/images/se_woman_60.jpg" width="60" height="75"/>
		<?php } else { ?>
			<img src="public/system/images/se_man_60.jpg" width="60" height="75"/>
		<?php } ?>
<?php } ?>
</p>
</div>
</li>
<?php } ?>
<?php } ?>
</ul>
<div class="clear"></div>
</div>
<script type="text/javascript">
<!--
function checkRightResearchForm(){
	var age1 = document.getElementById("a1").value;
	var age2 = document.getElementById("a2").value;
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
	//$("#buttonsearch").attr("class","search-button2");
	//$("#buttonsearch").val(" ");
	$("#buttonsearch").val("搜索中...");
	return;
}

//to members scoring
var switchval = true;
function vote_member(obj){
	if(switchval){
		switchval = false;
		var flag = obj.value;
		var url = 'index.php?n=vote&h=vote_into&counts='+flag+'&sex=<?php echo $sex;?>&age=<?php echo $age;?>&uid=<?php echo $vote_mem["uid"];?>';
		location.href = url;
	}
}
//-->
</script>
<div class="vote-right-content">		
<form action="index.php" method="get" onsubmit="return checkRightResearchForm();">
<span class="right-title fleft">快速搜索会员</span>
<ul class="vote-search">
<li>我&nbsp;要&nbsp;找：
<select name="gender">
	<option value="0" <?php if($GLOBALS["user_arr"]['gender'] == 1) { ?>selected<?php } ?>>男</option>
	<option value="1" <?php if($GLOBALS["user_arr"]['gender'] == 0) { ?>selected<?php } ?>>女</option>
</select>		
&nbsp;有照片<input  name="isphoto" type="checkbox"  id="isphoto" value="1" checked="checked"/>
</li>
<li>
<?php $age_start=(date('Y')-$user_arr['birthyear']);$age_end=(date('Y')-$user_arr['birthyear']+8);?>
<p style="margin-left:35px;">年龄
<script>getSelect('','a1','age_start','<?php echo $age_start;?>','21',age);</script>
至&nbsp;
<script>getSelect('','a2','age_end','','<?php echo $age_end;?>',age) ;</script>
</li>
<li>所在地区：
<script>getProvinceSelect43rds('','workprovince','workprovince','workcity2','<?php echo $GLOBALS["user_arr"]["province"];?>','10100000');</script>
<script>getCitySelect43rds('m2 s67','workcity2','workcity','<?php echo $GLOBALS["user_arr"]["city"];?>','');</script>
</li>
<li>
<input type="hidden" name="n" value="search" />
<input type="hidden" name="h" value="quick" />
<input type="hidden" name="quick_search" id="quick_search" value="search" />
<input name="" id="buttonsearch" type="submit" class="btn btn-default start-search" value="开始搜索 &gt;&gt;" />
<input name="" type="button" class="btn btn-default high-search" value="高级搜索 &gt;&gt;" onclick="javascript:window.open('index.php?n=search&h=super')"/></li>
</ul>
</form>
<div class="clear"></div>
</div>
</div>
<!--右边结束-->
<div class="clear"></div>
<?php include MooTemplate('system/footer','public'); ?>
</div>
<!--content end-->
</body>
</html>

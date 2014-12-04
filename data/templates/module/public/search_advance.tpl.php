<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>高级搜索——真爱一生寻友——真爱一生网</title>
<?php include MooTemplate('system/js_css','public'); ?>
<link rel="stylesheet" type="text/css" href="public/<?php echo $GLOBALS['style_name'];?>/css/font.css">

<link rel="stylesheet" type="text/css" href="module/search/templates/<?php echo $GLOBALS['style_name'];?>/h-search.css">
<script src="public/system/js/list.js" type="text/javascript"></script>
<!--<script src="module/search/templates/default/js/syscode.js?v=1" type="text/javascript"></script>-->
<script src="module/search/templates/default/js/common.js" type="text/javascript"></script>
<script src="module/search/templates/default/js/addSearchConditions.js" type="text/javascript"></script>
</head>
<body>
<div class="clear"></div>
<?php include MooTemplate('system/header','public'); ?>
<div class="clear"></div>
<!--头部结束-->
<div class="content">
<div class="c-title">
<span class="f-000"><a href="index.php">真爱一生首页</a>&nbsp;&gt;&gt;&nbsp;<a href="index.php?n=search">真爱一生寻友</a>&nbsp;&gt;&gt;&nbsp;高级搜索</span>
<div class="loaction_right">
	<a href="index.php?n=invite" target="_blank">邀请好友</a>
</div>
<div class="clear"></div>
</div>
<div class="clear"></div>
<div class="search-center">
<p class="h-seache-side-top"></p>
<div class="c-center">
<p class="h-seache-title">高级搜索
</p>
<!--------------------------------->
<div class="search-criteria-tipm clearfix">
<div class="h-search-side"></div>
<div class="s-right">
<div class="tipboxl">
<p class="h-seache-title" style="color:#d73c90">您选择的搜索条件：</p>
<div class="search-criteria-m">
<div id="addSearchCondition"> </div>
<p id="alldelete2" style="display:none" class="textRight"><a href="##" onclick="delAllItem2();" class="lgray underlines">全部清除</a></p>
<script type="text/javascript">
<!--
if($('#marriage_title').size()>0  || $('#education_title').size()>0 || $('#salary_title').size()>0  || $('#height_title').size()>0  || $('#house_title').size()>0  || $('#vehicle_title').size()>0  || $('#hometown_title').size()>0 || $('#ismok_title').size()>0 || $('#idrink_title').size()>0 || $('#child_title').size()>0  || $('#wtchildren_title').size()>0 || $('#body_title').size()>0 || $('#weight_title').size()>0 || $('#stock_title').size()>0 || $('#animals_title').size()>0 || $('#constellation_title').size()>0 || $('#bty_title').size()>0 || $('#occupation_title').size()>0  || $('#corptp_title').size()>0  || $('#belief_title').size()>0 || $('#tonguegift_title').size()>0 || $('#family_title').size()>0 ) {	$('#alldelete2').css("display","");}
//-->
</script>
</div>
</div>
</div>
<script type="text/javascript">
var checkForm = function() {
var sex = document.getElementById("sex").value;
var age1 = document.getElementById("a1").value;
var age2 = document.getElementById("a2").value;
var h1 = document.getElementById("h1").value;
var h2 = document.getElementById("h2").value;
var w1 = document.getElementById("w1").value;
var w2 = document.getElementById("w2").value;
if(sex == null) {
alert("请选择性别");
document.getElementById("sex").focus();
return false;
}
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
if(h1!=null && h1!='' && h1!=0 && h2!=null && h2!='' && h2!=0 && h1>h2) {
alert("您选择的身高范围不正确，请重新选择");
document.getElementById("h1").focus();
return false;
}
if(w1!=null && w1!='' && w1!=0 && w2!=null && w2!='' && w2!=0 && w1>w2) {
alert("您选择的体重范围不正确，请重新选择");
$('#w1').focus();
return false;
}
var ic = document.getElementById("is_commend");
var issavesearchname = document.getElementById("issavesearchname");
var searchname = document.getElementById("searchname");
if(issavesearchname.checked==true) {
	var temp = Trim(searchname.value);
	if(temp == null || temp == ''){
		alert("请输入搜索条件名");
		searchname.focus();
		return false;
	}
	issavesearchname.value = 1;
	searchname.value = temp;
	checkWord(22,event,'对不起！您输入的搜索条件名过长！');
} else if(issavesearchname.checked==false &&  null != temp && temp != '') {
	issavesearchname.value = 1;
	searchname.value = temp;
	issavesearchname.checked=true;
}
if(ic.checked==true) {
	ic.value = 1;
}
//$("#buttonsearch").attr("class","search-button-h2");
//$("#buttonsearch").val("");
$("#buttonsearch").val("搜索中...");
return;
}
</script>
<form id="hs" name="hs" action="index.php" method="get" onsubmit="return checkForm();">
<input type="hidden" value="search" name="n">
<input type="hidden" value="super" name="h">
<input type="hidden" name="advance_search" value="1">
<div class="tipboxr clearfix">
	<ul class="databox clearfix">
		<li>
			<h5>基本条件</h5>
			<ul class="basic">
				<li>我要找：
					<select name="gender" id="sex" onchange=" changeBody(this.value);" class="selectSize">
					<?php if($user_arr['gender'] == '1') { ?>
						<option value="1">女士
						</option>
						<option value="0" selected="selected">男士
					<?php } else { ?>
						<option value="1" selected="selected">女士
						</option>
						<option value="0">男士
					<?php } ?>
						</option>
					</select>
					<input name="photo" type="checkbox" checked="checked" value="1" />有照片</li>
				<?php if(isset($user_arr['birthyear'])) { ?>
				<?php $age_start=(date('Y')-$user_arr['birthyear']);$age_end=(date('Y')-$user_arr['birthyear']+8);?>
				<?php } else { ?>
				<?php $age_start = 0;$age_end = 0;?>
				<?php } ?>
				<li>年&nbsp;&nbsp;龄：
					<script>syscode.select('select50','a1','age_start','<?php echo $age_start;?>','21',syscode.age,syscode.buxian);</script>
						到
					<script>syscode.select('select50','a2','age_end','<?php echo $age_end;?>','45',syscode.age,syscode.buxian);</script>
				</li>
				<li>所在地：
				<?php if(!isset($user_arr['province'])) { ?>
				<?php $user_arr['province'] = 0?>
				<?php } ?>
				<?php if(!isset($user_arr['city'])) { ?>
				<?php $user_arr['city'] = 0?>
				<?php } ?>
					<script>syscode.provinceSelect('selectSize','areaForm.workProvince','workprovince','areaForm.workCity','<?php echo $user_arr["province"];?>','10100000',syscode.buxian);</script>
					<script>syscode.citySelect('selectSize','areaForm.workCity','workcity','<?php echo $user_arr["city"];?>','0',syscode.buxian);</script>
				</li>
			</ul>
			<h5>生活细节</h5>
			<dl>
				<dt id="personal_ismok" class="darrow" onclick="tabtoggle2(this,'#personal_view_ismok')">是否抽烟</dt>
				<dd class="datalist" id="personal_view_ismok" style="display:none">
					<script>syscode.checkbox2('ismok','ismok',',,',syscode.isSmoking,'p',syscode.buxian,'ismok');checkBuXian(',,','ismok_0');</script>

				</dd>
				<dt id="personal_idrink" class="darrow" onclick="tabtoggle2(this,'#personal_view_idrink')">是否喝酒</dt>
				<dd class="datalist" id="personal_view_idrink" style="display:none">
					<script>syscode.checkbox2('idrink','idrink',',,',syscode.isDrinking,'p',syscode.buxian,'idrink');checkBuXian(',,','idrink_0');</script>
				</dd>
				<dt id="personal_child" class="darrow" onclick="tabtoggle2(this,'#personal_view_child')">是否有孩子</dt>
				<dd class="datalist" id="personal_view_child" style="display:none">
					<script>syscode.checkbox2('child','child',',,',syscode.taChildren,'p',syscode.buxian,'child');checkBuXian(',,','child_0');</script>
				</dd>
				<dt id="personal_wtchildren" class="darrow" onclick="tabtoggle2(this,'#personal_view_wtchildren')">是否想要孩子</dt>
				<dd class="datalist" id="personal_view_wtchildren" style="display:none">
					<script>syscode.checkbox2('wtchildren','wtchildren',',,',syscode.taWantChildren,'p',syscode.buxian,'wtchildren');checkBuXian(',,','wtchildren_0');</script>
				</dd>
			</dl>
		</li>
		<li>
			<h5>外形条件</h5>
			<dl style="margin-bottom:10px">
				<dt id="personal_height" class="darrow" onclick="tabtoggle2(this,'#personal_view_height')">身高</dt>
				<dd class="datalist" id="personal_view_height" style="display:none">
					<script>syscode.select('selectSize','h1','height1','0','0',syscode.height,syscode.buxian);</script> 到 <script>syscode.select('selectSize','h2','height2','0','0',syscode.height2,syscode.buxian);</script>
					<input class="addbut" name="" type="button" value="增加" onclick="addHeight();" /></dd>
				<dt id="personal_weight" class="darrow" onclick="tabtoggle2(this,'#personal_view_weight')">体重</dt>
				<dd class="datalist" id="personal_view_weight" style="display:none">
					<script>syscode.select('selectSize','w1','weight1','0','0',syscode.weight,syscode.buxian);</script>
到
					<script>syscode.select('selectSize','w2','weight2','0','0',syscode.weight2,syscode.buxian);</script>
					<input class="addbut" name="" type="button" value="增加" onclick="addWeight();" />
				</dd>
				<dt id="personal_body" class="darrow" onclick="tabtoggle2(this,'#personal_view_body')">体型</dt>
				<dd class="datalist" id="personal_view_body" style="display:none">
					<ul class="clearfix">
						<li id="bodybtbt" class="clearfix">
						<?php if($user_arr['gender'] == '0') { ?>
							<script>syscode.checkbox2('body','body',',,',syscode.body1,'p',syscode.buxian,'body');checkBuXian(',,','body_0');</script>
						<?php } else { ?>
							<script>syscode.checkbox2('body','body',',,',syscode.body0,'p',syscode.buxian,'body');checkBuXian(',,','body_0');</script>
						<?php } ?>
						</li>
					</ul>
				</dd>
			</dl>
			<h5>背景条件</h5>
			<dl>
				<dt id="personal_hometown" class="darrow" onclick="tabtoggle2(this,'#personal_view_hometown')">籍贯</dt>
				<dd class="datalist" id="personal_view_hometown" style="display:none">
					<script>syscode.provinceSelect('selectSize','areaForm.workProvince1','workcityprovince1','areaForm.workCity1','0','0',syscode.buxian);</script>
					<script>syscode.citySelect('selectSize','areaForm.workCity1','workcitycity1','0','',syscode.buxian);</script>
					<input class="addbut" name="" type="button" value="增加" onclick="addHometown();" />
				</dd>
				<dt id="personal_stock" class="darrow" onclick="tabtoggle2(this,'#personal_view_stock')">民族</dt>
				<dd class="datalist" id="personal_view_stock" style="display:none">
					<script>syscode.select('','stock','stock','0','0',syscode.stock,syscode.buxian);</script>
					<input class="addbut" name="" type="button" value="增加" onclick="addStock();" />
				</dd>
				<dt id="personal_animals" class="darrow" onclick="tabtoggle2(this,'#personal_view_animals')">生肖</dt>
				<dd class="datalist" id="personal_view_animals" style="display:none">
					<ul class="clearfix">
						<li class="clearfix">
							<script>syscode.checkbox2('animals','animals',',,',syscode.animals,'p',syscode.buxian,'animals');checkBuXian(',,','animals_0');</script>
						</li>
					</ul>
				</dd>
				<dt id="personal_constellation" class="darrow" onclick="tabtoggle2(this,'#personal_view_constellation')">星座</dt>
				<dd class="datalist" id="personal_view_constellation" style="display:none">
					<ul class="clearfix">
						<li class="clearfix">
							<script>syscode.checkbox2('constellation','constellation',',,',syscode.constellation,'li',syscode.buxian,'constellation');checkBuXian(',,','constellation_0');</script>
					</ul>
				</dd>
				<dt id="personal_bty" class="darrow" onclick="tabtoggle2(this,'#personal_view_bty')">血型</dt>
				<dd class="datalist" id="personal_view_bty" style="display:none">
					<ul class="clearfix">
						<li class="clearfix">
							<script>syscode.checkbox2('bty','bty',',,',syscode.bloodtype,'p',syscode.buxian,'bty');checkBuXian(',,','bty_0');</script>
						</li>
					</ul>
				</dd>
			</dl>
		</li>
		<li>
			<h5>个人资料</h5>
			<dl>
				<dt id="personal_marriage" class="darrow" onclick="tabtoggle2(this,'#personal_view_marriage')">婚姻状况</dt>
				<dd class="datalist" id="personal_view_marriage" style="display:none">
					<ul class="clearfix">
						<li class="clearfix">
							<script>syscode.checkbox2('marriage','marriage',',,',syscode.marriage,'p',syscode.buxian,'marriage');checkBuXian(',,','marriage_0');</script>
						</li>
					</ul>
				</dd>
				<dt id="personal_education" class="darrow" onclick="tabtoggle2(this,'#personal_view_education')">教育程度</dt>
				<dd class="datalist" id="personal_view_education" style="display:none">
					<ul class="clearfix">
						<li class="clearfix">
							<script>syscode.checkbox2('education','education',',,',syscode.education,'p',syscode.buxian,'education');checkBuXian(',,','education_0');</script>
						</li>
					</ul>
				</dd>
				<dt id="personal_salary" class="darrow" onclick="tabtoggle2(this,'#personal_view_salary')">月收入</dt>
				<dd class="datalist" id="personal_view_salary" style="display:none">
					<script>syscode.checkbox2('salary','salary',',,',syscode.salary,'p',syscode.buxian,'salary');checkBuXian(',,','salary_0');</script>
				</dd>
				<dt id="personal_occupation" class="darrow" onclick="tabtoggle2(this,'#personal_view_occupation')">从事职业</dt>
				<dd class="datalist" id="personal_view_occupation" style="display:none">
					<ul class="clearfix">
						<li class="clearfix">
							<script>syscode.checkbox2('occupation','occupation',',,',syscode.occupationbt,'p',syscode.buxian,'occupation');checkBuXian(',,','occupation_0');</script>
						</li>
					</ul>
				</dd>
				<dt id="personal_house" class="darrow" onclick="tabtoggle2(this,'#personal_view_house')">住房情况</dt>
				<dd class="datalist" id="personal_view_house" style="display:none">
					<script>syscode.checkbox2('house','house',',,',syscode.house,'p',syscode.buxian,'house');checkBuXian(',,','house_0');</script>
				</dd>
				<dt id="personal_vehicle" class="darrow" onclick="tabtoggle2(this,'#personal_view_vehicle')">是否购车</dt>
				<dd class="datalist" id="personal_view_vehicle" style="display:none">
					<script>syscode.checkbox2('vehicle','vehicle',',,',syscode.vehicle,'p',syscode.buxian,'vehicle');checkBuXian(',,','vehicle_0');</script>
				</dd>
				<dt id="personal_corptp" class="darrow" onclick="tabtoggle2(this,'#personal_view_corptp')">公司类别</dt>
				<dd class="datalist" id="personal_view_corptp" style="display:none">
					<ul class="clearfix">
						<li class="clearfix">
							<script>syscode.checkbox2('corptp','corptp',',,',syscode.corptype,'p',syscode.buxian,'corptp');checkBuXian(',,','corptp_0');</script>
						</li>
					</ul>
				</dd>
				<dt id="personal_belief" class="darrow" onclick="tabtoggle2(this,'#personal_view_belief')">信仰</dt>
				<dd class="datalist" id="personal_view_belief" style="display:none">
					<ul class="clearfix">
						<li class="clearfix">
							<script>syscode.checkbox2('belief','belief',',,',syscode.belief,'p',syscode.buxian,'belief');checkBuXian(',,','belief_0');</script>
						</li>
					</ul>
				</dd>
				<dt id="personal_tonguegift" class="darrow" onclick="tabtoggle2(this,'#personal_view_tonguegift')">语言能力</dt>
				<dd class="datalist" id="personal_view_tonguegift" style="display:none">
					<script>syscode.checkbox2('tonguegift','tonguegift',',,',syscode.tonguegifts,'p',syscode.buxian,'tonguegift');checkBuXian(',,','tonguegift_0');</script>
				</dd>
				<dt id="personal_family" class="darrow" onclick="tabtoggle2(this,'#personal_view_family')">兄弟姐妹</dt>
				<dd class="datalist" id="personal_view_family" style="display:none">
					<ul class="clearfix">
						<li class="clearfix">
							<script>syscode.checkbox2('family','family',',,',syscode.family,'p',syscode.buxian,'family');checkBuXian(',,','family_0');</script>
						</li>
					</ul>
				</dd>
			</dl>
		</li>
		<div style="clear:both"></div>
	</ul>
	<div class="butbox clearfix">
		<div class="clearfix-p">
			<p><input name="issavesearchname" type="checkbox" value="1" id="issavesearchname" />&nbsp;保存这次搜索条件命名为：<input name="searchname" type="text"  class="clearfix-p-text" id="searchname" onblur="javascript:checkWord(22,event,'对不起！您输入的搜索条件名过长！')"/></p>
			<p><input name="is_commend" type="checkbox" value="1" id="is_commend" />&nbsp;定期推荐符合此搜索条件的会员到我的注册邮箱</p>
		</div>
		<div style="float:right" id="beginsearch">
			<input name="" value="开始搜索"  type="submit" class="btn btn-default search-button-h" id="buttonsearch">
		</div>
	</div>
</div>
<?php if(!strpos($_SERVER["HTTP_USER_AGENT"],"MSIE 6.0")) { ?>
<div class="h-search-side" style="background-position:bottom; font-size:0px; margin-top:0!important; margin-top:-5px;"></div>
<?php } ?>
</form><!-- end form -->
<div style="clear:both"></div>
</div>
<!--------------------------------->
</div>
<p class="h-seache-side-bottom"></p>
</div>
<div class="clear"></div>
<?php include MooTemplate('system/footer','public'); ?>
</div><!--content end-->
</body>
</html>
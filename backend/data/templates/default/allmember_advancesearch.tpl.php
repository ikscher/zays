<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="templates/css/general.css" rel="stylesheet" type="text/css" />
<link href="templates/css/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../public/system/js/sys2.js"></script>
<script type="text/javascript" src="templates/js/My97DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="../module/search/templates/default/js/common.js"></script>
<script type="text/javascript">
//显示用户信息
function userdetail(number,arrayobj) {
	var arrname = arrayobj;
		for(i=0;i<arrayobj.length;i++) {
			var valueArray  = arrayobj[i].split(",");
			if(valueArray[0] == number) {
				if(valueArray[0] == '0' && valueArray[1] != '男士' ) {
					document.write("未选择");
				} else {
					document.write(valueArray[1]);
				}	
			}
	}
}


//当鼠标移过时显示图框
var xPos;
var yPos;
var timerID;
function showinfo(evt,uid){
        
        timerID = setTimeout("xy("+xPos+","+yPos+","+uid+")",500);
}
function xy(xPos,yPos,uid){
		
		//if(xPos+70+600<window.screen.width){
			if(1){
		    // $("#callinfo").css('left',xPos+70);
		      //$("#callinfo").css('top',yPos-70);
		      $("#userid_"+uid).html(uid);
		      $.get("ajax.php?n=callinfo",{uid:uid},function(str){
		              var arr=new Array();
		              arr=str.split('||');
		              $("#usertelphone_"+uid).html(arr[0]);
		              $("#userbezhucontent_"+uid).html(arr[1]);
		              $("#usercause_"+uid).html(arr[2]);
		              $("#usercallstate_"+uid).html(arr[3]);
		              $("#usercallremark_"+uid).html(arr[3]);
		      });
		      $("#callinfo_"+uid).css('display','block');
		//alert(xPos+'|'+yPos);
		      }else {$("#callinfo_"+uid).css('display','none'); }
}
function hiddend(){
	//$(".(callinfo_)").css('display','none');
	$("div[id^='callinfo_']").css('display','none');
	       //$("#callinfo_").css('display','none');
	       clearTimeout(timerID);
	}


//分页跳转
function gotoPage() {
	var page = $("#pageGo").val();
	var page = parseInt(page);
	
	if(page<1) page = 1;
	if(page><?php 
		if(!isset($total)) $total=0;
		echo ceil($total/$page_per);
		?>)

	page = <?php echo ceil($total/$page_per);?>;
	window.location.href = "<?php echo $currenturl;?>&page="+page;
}

//全选
function chooseall(){
	if($("#choose_all").attr("checked")){
		$("input[name='changesid[]']").attr("checked",true);
	}else{
		$("input[name='changesid[]']").attr("checked",false);
	}
}

//分配客服
function changeusersid(){
	var kefuuid = $("#kefuuid option:selected").val(); 
	var uidlist = "";
	$("input[name='changesid[]']:checked").each(function(){
		uidlist = 1;
	})
	if(uidlist==''){
		alert('请选择需要分配的会员');
		return false;
	}
	var pre_url = $("#pre_url").val();
	return true;	
	//location.href="index.php?action=allmember&h=changeusersid&uidlist="+uidlist+"&kefuuid="+kefuuid+"&pre_url="+pre_url;

}
$(function(){
	$(".csstab tr").mouseover(function(){
		$(this).addClass("over");
	}).mouseout(function(){
		$(this).removeClass("over");	
	})
})
</script>
<style type="text/css">
tr.over td{
	background:#cfeefe;
} 

#listDiv .desc{
	color:#333;
	font-weight:bold;
}
.area_select{
	width:100px;
}


a:link {
 color: #0033ff;
 text-decoration: none;
}
a:visited {
 color:#00F;
 text-decoration: none;
}
a:hover {
 text-decoration: none;
 color: #FF0000;
}


.callinfo{
position:absolute;
top:100px;
left:100px;
width:600px;
height:auto;
background:#ffffff;}
#callinfo table{
backgroung:#ffffff;}
#callinfo td{
border:#cccccc solid 1px;
}

*{padding:0;margin:0;}
body{padding:0;margin:0;font-size:12px;}
label {margin-left: 3px;}
.tipboxr{width:180px;float:left;}
.tipboxr .databox { padding:10px 8px 30px; background-color:#fff;}
.tipboxr .databox li{ float:left; margin:0 6px;width:180px;display: inline;}
.tipboxr .databox h5 { font-size:12px; border-bottom:1px dashed #ccc;}
.tipboxr .databox li .basic { background-color:#FBEAF2; border:1px solid #F2DBE2; padding:5px 10px 0 ;margin-bottom:20px;}
.tipboxr .databox li .basic li { float:none; width:145px; margin:0; padding-bottom:5px; display:block}
.tipboxr .databox dl { margin-bottom:17px}
.tipboxr .databox dt {border-bottom:1px dashed #d1d1d1;padding:5px 10px; cursor:pointer; color:#333; width:145px}
.tipboxr .databox dt.current { background-color:#FF99FF;}
.tipboxr .databox dd { background-color:#f5f5f5; padding:8px; line-height:22px;}
.tipboxr .databox dd li{ width:145px}
.tipboxr .databox dd li p.w105{ width:105px; margin:0}
.tipboxr .databox dd li p { float:left; width:80px;}
.selectSize{ width:60px;height:20px;}
.click252 a {
    background: none repeat scroll 0 0 #8DB2E3;
    border-color: #4D6382;
    border-style: solid;
    border-width: 1px 2px 2px 1px;
    color: #E0ECFF;
    display: block;
    font-size: 12px;
    height: 20px;
    line-height: 20px;
    text-align: center;
    text-decoration: none;
    width: 150px;
}
</style>
<script type="text/javascript">
<!--
var tabtoggle2 = function(e,o){
	if($(o).is(":hidden")){
		$('dt.darrow2').removeClass('darrow2').addClass('darrow');
		$('.datalist').hide();
		$(o).show();
		$(e).addClass("darrow2");
		$(e).removeClass("darrow");
	}else{
		$(o).hide();
		$(e).addClass("darrow");
		$(e).removeClass("darrow2");
	}
};
//-->
</script>
</head>
<body>
<h1 style="margin-bottom:15px;">
	<span class="action-span1"><a href="###">真爱一生网 管理中心</a> </span><span id="search_id" class="action-span1"> - 高级搜索 </span>
	<span class="action-span"><a href="index.php?action=allmember&h=advancesearch">刷新</a></span>
	<div style="clear:both"></div>
</h1>


<div class="list-div" id="listDiv" style="background:#fff;"  onmouseout="hiddend();">

	<div style="padding-left:10px;background:#fff"></div>
<form action="" method="get">
	<table cellpadding="4" cellspacing="4">
	<tr>
		<td>
			<span class="desc">会员ID： </span><input type="text" name="uid" value="<?php echo $_GET['uid'];?>" />
		</td>
		<td><span class="desc">成熟度：</span>
			<select name="grade" style="width:150px;">
				<option value="">请选择</option>
				<?php foreach((array)$GLOBALS['grade'] as $key=>$grade) {?>
				<option value="<?php echo $key;?>" <?php if($_GET['grade']==$key) { ?>selected="selected"<?php } ?>><?php echo $grade;?></option>
				<?php }?>
			</select>
		</td>
		<td rowspan="12">
<div class="tipboxr clearfix">
	<ul class="databox clearfix">
		<h5>生活细节</h5>
             <dl>
                <dt id="personal_ismok" <?php if($ismok!='') { ?>class="current"<?php } else { ?>class="darrow"<?php } ?> onclick="tabtoggle2(this,'#personal_view_ismok')">是否抽烟</dt>
                <dd class="datalist" id="personal_view_ismok" style="display:none">
					<script>syscode.checkbox('ismok','ismok[]',',<?php echo $smoking;?>,',syscode.isSmoking,'p',syscode.buxian);</script>
                </dd>
                <dt id="personal_idrink" <?php if($idrink!='') { ?>class="current"<?php } else { ?>class="darrow"<?php } ?> onclick="tabtoggle2(this,'#personal_view_idrink')">是否喝酒</dt>
                <dd class="datalist" id="personal_view_idrink" style="display:none">
                  <script>syscode.checkbox('idrink','idrink[]',',<?php echo $drinking;?>,',syscode.isDrinking,'p',syscode.buxian);</script>
                </dd>
                <dt id="personal_child" <?php if($child!='') { ?>class="current"<?php } else { ?>class="darrow"<?php } ?> onclick="tabtoggle2(this,'#personal_view_child')">是否有孩子</dt>
                <dd class="datalist" id="personal_view_child" style="display:none">
                  <script>syscode.checkbox('child','child[]',',<?php echo $children;?>,',syscode.taChildren,'p',syscode.buxian);</script>
                </dd>
                <dt id="personal_wtchildren" <?php if($wantchildren!='') { ?>class="current"<?php } else { ?>class="darrow"<?php } ?> onclick="tabtoggle2(this,'#personal_view_wtchildren')">是否想要孩子</dt>
                <dd class="datalist" id="personal_view_wtchildren" style="display:none">
                  <script>syscode.checkbox('wantchildren','wantchildren[]',',<?php echo $wantchildren;?>,',syscode.taWantChildren,'p',syscode.buxian);</script>
                </dd>
			</dl>
			<h5>外形条件</h5>
              <dl style="margin-bottom:10px">
                <dt id="personal_height" <?php if($height1!='' || $height2!='') { ?><?php if($height1!='0' || $height2!='0') { ?>class="current"<?php } else { ?>class="darrow"<?php } ?><?php } else { ?>class="darrow"<?php } ?> onclick="tabtoggle2(this,'#personal_view_height')">身高</dt>
                <dd class="datalist" id="personal_view_height" style="display:none">
                  <script>syscode.select('selectSize','height1','height1','<?php echo $height1;?>','0',syscode.height,syscode.buxian);</script> 到 
				  <script>syscode.select('selectSize','height2','height2','<?php echo $height2;?>','0',syscode.height2,syscode.buxian);</script>
				</dd>
				<dt id="personal_weight" <?php if($weight1!='' || $weight2!='') { ?><?php if($weight1!='0' || $weight2!='0') { ?>class="current"<?php } else { ?>class="darrow"<?php } ?><?php } else { ?>class="darrow"<?php } ?> onclick="tabtoggle2(this,'#personal_view_weight')">体重</dt>
                <dd class="datalist" id="personal_view_weight" style="display:none">
                  <script>syscode.select('selectSize','weight1','weight1','<?php echo $weight1;?>','0',syscode.weight,syscode.buxian);</script> 到
                  <script>syscode.select('selectSize','weight2','weight2','<?php echo $weight2;?>','0',syscode.weight2,syscode.buxian);</script>
				</dd>
				<dt id="personal_body" <?php if($body!='') { ?>class="current"<?php } else { ?>class="darrow"<?php } ?> onclick="tabtoggle2(this,'#personal_view_body')">体型</dt>
                <dd class="datalist" id="personal_view_body" style="display:none">
					<script>syscode.checkbox('body','body[]',',<?php echo $body;?>,',syscode.body0,'p',syscode.buxian);</script>
                </dd>
              </dl>
			<!--<h5>背景条件</h5>
			<dl>
				<dt id="personal_hometown" class="darrow" onclick="tabtoggle2(this,'#personal_view_hometown')">籍贯</dt>
				<dd class="datalist" id="personal_view_hometown" style="display:none">
					<script>syscode.provinceSelect('selectSize','areaForm.workProvince1','workcityprovince1','areaForm.workCity1','-1','-1',syscode.buxian);</script>
					<script>syscode.citySelect('selectSize','areaForm.workCity1','workcitycity1','-1','',syscode.buxian);</script>
				</dd>-->
				<!--<dt id="personal_stock" class="darrow" onclick="tabtoggle2(this,'#personal_view_stock')">民族</dt>
				<dd class="datalist" id="personal_view_stock" style="display:none">
					<script>syscode.select('','stock','stock','0','-1',syscode.stock,syscode.buxian);</script>
				</dd>
				<dt id="personal_animals" class="darrow" onclick="tabtoggle2(this,'#personal_view_animals')">生肖</dt>
				<dd class="datalist" id="personal_view_animals" style="display:none">
					<script>syscode.checkbox('animals','animals',',,',syscode.animals,'p',syscode.buxian);</script>
				</dd>
				<dt id="personal_constellation" class="darrow" onclick="tabtoggle2(this,'#personal_view_constellation')">星座</dt>
				<dd class="datalist" id="personal_view_constellation" style="display:none">
					<script>syscode.checkbox('constellation','constellation',',,',syscode.constellation,'p',syscode.buxian);</script>
				</dd>
				<dt id="personal_bty" class="darrow" onclick="tabtoggle2(this,'#personal_view_bty')">血型</dt>
				<dd class="datalist" id="personal_view_bty" style="display:none">
					<script>syscode.checkbox('bty','bty',',,',syscode.bloodtype,'p',syscode.buxian);</script>
				</dd>-->
			<!--</dl>-->
	</ul>
</div>
<div class="tipboxr clearfix">
	<ul class="databox clearfix">
		<h5>个人资料</h5>
			<dl>
			<!--<dt id="personal_marriage" class="darrow" onclick="tabtoggle2(this,'#personal_view_marriage')">婚姻状况</dt>
			<dd class="datalist" id="personal_view_marriage" style="display:none">
				<script>syscode.checkbox('marriage','marriage[]',',,',syscode.marriage,'p',syscode.buxian);</script>
			</dd>-->
			<dt id="personal_education" <?php if($education!='') { ?>class="current"<?php } else { ?>class="darrow"<?php } ?> onclick="tabtoggle2(this,'#personal_view_education')">教育程度</dt>
			<dd class="datalist" id="personal_view_education" style="display:none">
				<script>syscode.checkbox('education','education[]',',<?php echo $education;?>,',syscode.education,'p',syscode.buxian);</script>
			</dd>
			<!-- <dt id="personal_salary" class="darrow" onclick="tabtoggle2(this,'#personal_view_salary')">月收入</dt>
			<dd class="datalist" id="personal_view_salary" style="display:none">
				<script>syscode.checkbox('salary','salary[]',',,',syscode.salary,'p',syscode.buxian);</script>
			</dd> -->
			<dt id="personal_occupation" <?php if($occupation!='') { ?>class="current"<?php } else { ?>class="darrow"<?php } ?> onclick="tabtoggle2(this,'#personal_view_occupation')">从事职业</dt>
			<dd class="datalist" id="personal_view_occupation" style="display:none">
				<script>syscode.checkbox('occupation','occupation[]',',<?php echo $occupation;?>,',syscode.occupationbt,'p',syscode.buxian);</script>
			</dd>
			<dt id="personal_house" <?php if($house!='') { ?>class="current"<?php } else { ?>class="darrow"<?php } ?> onclick="tabtoggle2(this,'#personal_view_house')">住房情况</dt>
			<dd class="datalist" id="personal_view_house" style="display:none">
				<script>syscode.checkbox('house','house[]',',<?php echo $house;?>,',syscode.house,'p',syscode.buxian);</script>
			</dd>
			<dt id="personal_vehicle" <?php if($vehicle!='') { ?>class="current"<?php } else { ?>class="darrow"<?php } ?> onclick="tabtoggle2(this,'#personal_view_vehicle')">是否购车</dt>
			<dd class="datalist" id="personal_view_vehicle" style="display:none">
				<script>syscode.checkbox('vehicle','vehicle[]',',<?php echo $vehicle;?>,',syscode.vehicle,'p',syscode.buxian);</script>
			</dd>
			<dt id="personal_corptp" <?php if($corptp!='') { ?>class="current"<?php } else { ?>class="darrow"<?php } ?> onclick="tabtoggle2(this,'#personal_view_corptp')">公司类别</dt>
			<dd class="datalist" id="personal_view_corptp" style="display:none">
				<script>syscode.checkbox('corptp','corptp[]',',<?php echo $corptype;?>,',syscode.corptype,'p',syscode.buxian);</script>
			</dd>
			<!--<dt id="personal_belief" class="darrow" onclick="tabtoggle2(this,'#personal_view_belief')">信仰</dt>
			<dd class="datalist" id="personal_view_belief" style="display:none">
				<script>syscode.checkbox('belief','belief[]',',,',syscode.belief,'p',syscode.buxian);</script>
			</dd>-->
			<!--<dt id="personal_tonguegift" class="darrow" onclick="tabtoggle2(this,'#personal_view_tonguegift')">语言能力</dt>
			<dd class="datalist" id="personal_view_tonguegift" style="display:none">
				<script>syscode.checkbox('tonguegift','tonguegift[]',',,',syscode.tonguegifts,'p',syscode.buxian);</script>
			</dd>-->
			<dt id="personal_family" <?php if($family!='') { ?>class="current"<?php } else { ?>class="darrow"<?php } ?> onclick="tabtoggle2(this,'#personal_view_family')">兄弟姐妹</dt>
			<dd class="datalist" id="personal_view_family" style="display:none">
				<script>syscode.checkbox('family','family[]',',<?php echo $family;?>,',syscode.family,'p',syscode.buxian);</script>
			</dd>
		</dl>
	</ul>
</div>			
		</td>
	</tr>
	<tr>
		<td><span class="desc">婚　姻：</span>
			<select name="marriage" style="width:150px;">
				<option value="">请选择</option>
				<option value="1" <?php if($_GET['marriage']==1) { ?>selected="selected"<?php } ?>>未婚</option>
				<option value="3" <?php if($_GET['marriage']==3) { ?>selected="selected"<?php } ?>>离异</option>
				<option value="4" <?php if($_GET['marriage']==4) { ?>selected="selected"<?php } ?>>丧偶</option>
			</select>
		</td>
		<td>
			<span class="desc">分配时间：</span>
			<input type="text" name="allotdate1" value="<?php if($_GET['allotdate1']) echo $_GET['allotdate1'];?>" onFocus="WdatePicker({startDate:'%y-%M-%D 00:00:00',dateFmt:'yyyy-MM-dd',alwaysUseStartDate:true})" style="width:100px;"/>到
			<input type="text" name="allotdate2" value="<?php if($_GET['allotdate2']) echo $_GET['allotdate2']?>" onFocus="WdatePicker({startDate:'%y-%M-%D 00:00:00',dateFmt:'yyyy-MM-dd',alwaysUseStartDate:true})"  style="width:100px;"/>
		</td>
	</tr>
	<tr>
		<td><span class="desc">性　别：</span>
			<select name="gender" style="width:150px;">
				<option value="">请选择</option>
				<option value="1" <?php if($_GET['gender'] == '1') { ?>selected="selected"<?php } ?>>男</option>
				<option value="2" <?php if($_GET['gender'] == '2') { ?>selected="selected"<?php } ?>>女</option>
			</select>
		</td>
		<td>
			<span class="desc">注册时间：</span>
			<input type="text" name="regdate1" value="<?php if($_GET['regdate1']) echo $_GET['regdate1'];?>" onFocus="WdatePicker({startDate:'%y-%M-%D 00:00:00',dateFmt:'yyyy-MM-dd',alwaysUseStartDate:true})" style="width:100px;"/>到
			<input type="text" name="regdate2" value="<?php if($_GET['regdate2']) echo $_GET['regdate2'];?>" onFocus="WdatePicker({startDate:'%y-%M-%D 00:00:00',dateFmt:'yyyy-MM-dd',alwaysUseStartDate:true})"  style="width:100px;"/>
		</td>
	</tr>
	<tr>
		<td>
			<span class="desc">会员等级：</span>
			<select name="s_cid" style="width:150px;">
				<option value="">请选择</option>
				<?php foreach((array)$GLOBALS['member_level'] as $key=>$member_level) {?>
					<option value="<?php echo $key;?>" <?php if($_GET['s_cid']==$key && $_GET['s_cid']!='') { ?>selected="selected"<?php } ?>><?php echo $member_level;?></option>
				<?php }?>
			</select>
		</td>
		<td>
			<span class="desc">下次联系时间：</span>
			<input type="text" name="next_contact_time1" value="<?php if($_GET['next_contact_time1']) echo $_GET['next_contact_time1'];?>" onFocus="WdatePicker({startDate:'%y-%M-%D 00:00:00',dateFmt:'yyyy-MM-dd',alwaysUseStartDate:true})" style="width:100px;"/>到
			<input type="text" name="next_contact_time2" value="<?php if($_GET['next_contact_time2']) echo $_GET['next_contact_time2'];?>" onFocus="WdatePicker({startDate:'%y-%M-%D 00:00:00',dateFmt:'yyyy-MM-dd',alwaysUseStartDate:true})"  style="width:100px;"/>
		</td>
	</tr>
	<tr>
		<td><span class="desc">年　龄：</span>
			<input type="text" name="age1" style="width:75px;" value="<?php echo $_GET['age1'];?>" />到
			<input type="text" name="age2" style="width:75px;" value="<?php echo $_GET['age2'];?>" />
		</td>
		<td>
			<span class="desc">最后登录时间：</span>
			<input type="text" name="last_login_time1" value="<?php if($_GET['last_login_time1']) echo $_GET['last_login_time1'];?>" onFocus="WdatePicker({startDate:'%y-%M-%D 00:00:00',dateFmt:'yyyy-MM-dd',alwaysUseStartDate:true})" style="width:100px;"/>到
			<input type="text" name="last_login_time2" value="<?php if($_GET['last_login_time2']) echo $_GET['last_login_time2'];?>" onFocus="WdatePicker({startDate:'%y-%M-%D 00:00:00',dateFmt:'yyyy-MM-dd',alwaysUseStartDate:true})"  style="width:100px;"/>
		</td>
	</tr>
	<tr>
		<td><span class="desc">所在地：</span>
			<script>getProvinceSelect66('area_select','province','province','city',"<?php echo $_GET['province'];?>",'10100000');</script>
			<script>getCitySelect66('area_select','city','city',"<?php echo $_GET['city'];?>",'');</script>
		</td>
		<td><span class="desc">会员收入情况：</span>			
			<select name="salary" id="salary" class="">
				<option selected="selected" value="0">不限</option>
				<option value="1" <?php if($_GET['salary'] == '1') { ?>selected<?php } ?>>1000元以下</option>
				<option value="2" <?php if($_GET['salary'] == '2') { ?>selected<?php } ?>>1001-2000元</option>
				<option value="3" <?php if($_GET['salary'] == '3') { ?>selected<?php } ?>>2001-3000元</option>
				<option value="4" <?php if($_GET['salary'] == '4') { ?>selected<?php } ?>>3001-5000元</option>
				<option value="5" <?php if($_GET['salary'] == '5') { ?>selected<?php } ?>>5001-8000元</option>
				<option value="6" <?php if($_GET['salary'] == '6') { ?>selected<?php } ?>>8001-10000元</option>
				<option value="7" <?php if($_GET['salary'] == '7') { ?>selected<?php } ?>>10001-20000元</option>
				<option value="8" <?php if($_GET['salary'] == '8') { ?>selected<?php } ?>>20001-50000元</option>
				<option value="9" <?php if($_GET['salary'] == '9') { ?>selected<?php } ?>>50000元以上</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>
			
			<!-- <span class="desc">在线状态：</span>最近<input type="text" value="" name="online_time" style="width:20px;" />几分钟内在线 -->
			
			<span class="desc">在线状态：</span>
			<select name="online" style="width:150px;">
				<option value="">请选择</option>
				<option value="1" <?php if($_GET['online'] == 1) { ?>selected<?php } ?>>在线</option>
	  			<option value="2" <?php if($_GET['online'] == 2) { ?>selected<?php } ?>>一天内</option>
	  			<option value="3" <?php if($_GET['online'] == 3) { ?>selected<?php } ?>>一周内</option>
	  			<option value="4" <?php if($_GET['online'] == 4) { ?>selected<?php } ?>>一周外</option>
			</select>
			
			<input type="checkbox" name="master_member" value="1" <?php if(!empty($_GET['master_member'])&&$_GET['master_member']==1) { ?>checked<?php } ?>/>重点会员
		</td>
		<td>
			<span class="desc">按所属客服：</span>
			<select name="sid" style="width:150px;">
				<option value="">请选择</option>
				<?php foreach((array)$kefu_list as $kefu) {?>
				<option value="<?php echo $kefu['uid'];?>" <?php if($_GET['sid']==$kefu['uid']) { ?>selected="selected"<?php } ?>><?php echo $kefu['uid'];?>号 &nbsp;<?php echo $kefu['username'];?></option>
				<?php } ?>
				<option value="123"  <?php if($_GET['sid']=='123') { ?>selected="selected"<?php } ?>>123号</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>
			<span class="desc">手机号：</span><input type="text" name="telphone" value='<?php echo $_GET['telphone'];?>' />
		</td>
		<td>
			<span class="desc">用户名：</span>
			<input type="text" name="username" />
		</td>
	</tr>
	<tr>
		<td>
			<span class="desc">昵称：</span>
			<input type="text" name="nickname" />
		</td>
		<td>
			<span class="desc">姓名：</span>
			<input type="text" name="truename" />
		</td>
	</tr>
	<tr><td><span class="desc">会员来源:</span>
	   	   <select name="usertype" style="width:100px;">
				<option value="" <?php if($_GET['usertype'] == '') { ?>selected<?php } ?>>请选择</option>
	  			<option value="1" <?php if($_GET['usertype'] == 1) { ?>selected<?php } ?>>本站注册</option>
	  			<option value="2" <?php if($_GET['usertype'] == 2) { ?>selected<?php } ?>>外站加入</option>
	  			<option value="3" <?php if($_GET['usertype'] == 3) { ?>selected<?php } ?>>全权会员</option>
	  			<option value="4" <?php if($_GET['usertype'] == 4) { ?>selected<?php } ?>>联盟会员</option>
				<option value="5" <?php if($_GET['usertype'] == 5) { ?>selected<?php } ?>>内部会员</option>
	  		</select>
	    </td>
	 <td><span class="desc">上传照片数目：</span>
		<select name="uploadpicnum" style="width:100px;">
		        <option value="" >请选择</option>
		        <option value="6" <?php if($_GET['uploadpicnum'] == '6') { ?>selected<?php } ?>>没有照片</option>
            	<option value="1" <?php if($_GET['uploadpicnum'] == '1') { ?>selected<?php } ?>>1~5张</option>
             	<option value="2" <?php if($_GET['uploadpicnum'] == '2') { ?>selected<?php } ?>>6~10张</option>
	  			<option value="3" <?php if($_GET['uploadpicnum'] == '3') { ?>selected<?php } ?>>10~15张</option>	
                <option value="4" <?php if($_GET['uploadpicnum'] == '4') { ?>selected<?php } ?>>16~20张</option>		
                <option value="5" <?php if($_GET['uploadpicnum'] == '5') { ?>selected<?php } ?>>超过20张</option>		
        </select>
	</td>
	</tr>
	<tr><td><span class="desc">是否锁定：</span>
	<select name="is_lock" style="width:100px;">
		<option value="">请选择</option>
		<option value="1" <?php if($_GET['is_lock'] == '1') { ?>selected<?php } ?>>否</option>
		<option value="2" <?php if($_GET['is_lock'] == '2') { ?>selected<?php } ?>>是</option>
	</select>
		
	</td>
     <td><span class="desc">最近登录次数：</span>
		<select name="recentloginnum" style="width:100px;">
	            <option value="" >请选择</option>
            	<option value="1" <?php if($_GET['recentloginnum'] == '1') { ?>selected<?php } ?>>登录1~10次</option>
             	<option value="2" <?php if($_GET['recentloginnum'] == '2') { ?>selected<?php } ?>>登录11~20次</option>
	  			<option value="3" <?php if($_GET['recentloginnum'] == '3') { ?>selected<?php } ?>>登录21~30次</option>			
                <option value="4" <?php if($_GET['recentloginnum'] == '4') { ?>selected<?php } ?>>超过30次</option>	
        </select>
	</td>
    </tr>
    
   	<tr>
	<td><span class="desc">超过几天没有联系的：</span>
		<select name="nocontactdays" style="width:100px;">
        		<option value="" >请选择</option>
            	<option value="1" <?php if($_GET['nocontactdays'] == '1') { ?>selected<?php } ?>>1天未联系</option>
             	<option value="2" <?php if($_GET['nocontactdays'] == '2') { ?>selected<?php } ?>>2天未联系</option>
	  			<option value="3" <?php if($_GET['nocontactdays'] == '3') { ?>selected<?php } ?>>3天未联系</option>			
         </select>
	</td>
	<td>
		<span class="desc">籍贯：</span>
		<script>syscode.provinceSelect('selectSize','areaForm.workProvince1','workcityprovince1','areaForm.workCity1',"<?php echo $_GET['workcityprovince1'];?>",'0',syscode.buxian);</script>
		<script>syscode.citySelect('selectSize','areaForm.workCity1','workcitycity1',"<?php echo $_GET['workcitycity1'];?>",'',syscode.buxian);</script>
	</td>
    </tr>
	<?php if($adminid=='252' || $admingroup=='60') { ?>
	<tr>
		<td>
			<span class="click252"><a href="./allmember_ajax.php?n=change_public_members&uid=252&puid=52&photo=1" onclick="return confirm('确定转 有 形象照片的女会员吗？');">一键转女会员, 有形象照</a></span>
			<span class="click252"><a href="./allmember_ajax.php?n=change_public_members&uid=252&puid=52&photo=0" onclick="return confirm('确定转 无 形象照片的女会员吗？');">一键转女会员,无形象照</a></span>
		</td>
		<td><span class="desc">QQ：</span><input type="text" name="qq" />
		    <span class="desc">上传照片时间：</span><input type="text" name="uploadtime" value="<?php if($_GET['uploadtime']) echo $_GET['uploadtime'];?>" onFocus="WdatePicker({startDate:'%y-%M-%D 00:00:00',dateFmt:'yyyy-MM-dd',alwaysUseStartDate:true})" style="width:100px;"/>
		</td>
	</tr>
	<?php } else { ?>
	<tr>
		<td>&nbsp;</td>
		<td><span class="desc">QQ：</span><input type="text" name="qq" />
		    <span class="desc">上传照片时间：</span><input type="text" name="uploadtime" value="<?php if($_GET['uploadtime']) echo $_GET['uploadtime'];?>" onFocus="WdatePicker({startDate:'%y-%M-%D 00:00:00',dateFmt:'yyyy-MM-dd',alwaysUseStartDate:true})" style="width:100px;"/>
		</td>
	</tr>
	<?php } ?>
  </table>
  	
  <div style="text-align:center;margin-left:100px;">
  	<input type="hidden" name="action" value="allmember" />
  	<input type="hidden" name="h" value="advancesearch"  />
  	<input type="submit" value="搜索"  class="button" />
  	<input type="reset" value="重置" class="button" />
  </div>
</form>  

  <div style="height:10px;border-bottom:1px solid #999;margin:10px auto;"></div>
  <form action="index.php?action=allmember&h=changeusersid" method="post">
  <table cellspacing='1' cellpadding='3' id='list-table' class ="csstab">
  <tr>
    <th>ID</th>
    <th>用户名</th>
    <th>年龄</th>
    <th>等级</th>
    <th>照片</th>
    <th>收入</th>
    <th>工作地</th>
	<th>类</th>
    <th>分配时间</th>
    <th>注册时间</th>
	<th>工号

			<input type="checkbox" id="choose_all" value="choose_all" onclick="chooseall()" />

	</th>
    <th>客服</th>
    <th>原客服</th>
    <th>封锁</th>

	<!-- <th> 同号手机数</th> -->
	<th>是否可用</th>

    <th>在线状态</th>

    <th>最后登录时间</th>
    <th>下次联系时间</th>
  </tr>
  <?php foreach((array)$member_list as $member) {?>
   <tr>
    <td align="center"><a href="#" class="userinfo" onclick="parent.addTab('<?php echo $member['uid'];?>资料','index.php?action=allmember&h=view_info&uid=<?php echo $member['uid'];?>','icon')"><?php echo $member['uid'];?></a></td>
    <td align="left" style="text-indent:30px;"  onmouseover="showinfo(event,<?php echo $member['uid'];?>);"> <?php if($member['gender']==1) { ?><img src="templates/images/w.gif" alt="女" title="女"/><?php } else { ?><img src="templates/images/m.gif" alt="男" title="男"/><?php } ?><?php echo $member['username'];?>
		
    </td>
    <td align="center"><?php echo date("Y")-$member['birthyear'];?>
    <div  id="callinfo_<?php echo $member['uid'];?>" style="display:none;position:absolute;background:#FFF;margin-top:-100px;width:600px;">
		<table width="600" border="1" >
		  <tr >
		    <td width="50" height="20"  style="background:#FFF">会员ID</td>
		    <td width="50" id="userid_<?php echo $member['uid'];?>" style="background:#FFF">&nbsp;</td>
		    <td width="50" style="background:#FFF">手机号</td>
		    <td width="78" id="usertelphone_<?php echo $member['uid'];?>" style="background:#FFF">&nbsp;</td>
		    <td width="50" style="background:#FFF">下次联系时间</td>
		    <td width="150" id="usercause_<?php echo $member['uid'];?>" style="background:#FFF">&nbsp;</td>
		  </tr>
		  <tr>
		    <td height="43" id="userbezhu_<?php echo $member['uid'];?>" style="background:#FFF">会员备注</td>
		    <td colspan="5" id="userbezhucontent_<?php echo $member['uid'];?>" style="background:#FFF">&nbsp;</td>
		  </tr>
		  <tr>
		    <td height="43" style="background:#FFF">下次联系要点</td>
		    <td colspan="5" id="usercallremark_<?php echo $member['uid'];?>" style="background:#FFF">&nbsp;</td>
		  </tr>
		</table>
		</div>
    </td>
    <td align="center"><?php if(isset($GLOBALS['member_level'][$member['s_cid']])) { ?><?php echo $GLOBALS['member_level'][$member['s_cid']];?><?php } ?></td>
    <td align="center"><?php echo isset($member['mainimg']) && $member['mainimg']?"有":"无";?></td>
    <td align="center"><script>userdetail("<?php echo $member['salary'];?>",salary1);</script></td>
    <td align="center"><script>userdetail("<?php echo $member['province'];?>",provice);userdetail("<?php echo $member['city'];?>",city);</script></td>
	<td align="center"><?php if($member['effect_grade']>0) echo $member['effect_grade']-1;?></td>
    <td align="center"><?php if($member['allotdate']) echo date("Y-m-d H:i",$member['allotdate']);?></td>
    <td align="center"><?php echo date("Y-m-d H:i",$member['regdate']);?></td>
	<td align="center">
		<?php if(!in_array($GLOBALS['groupid'],$GLOBALS['general_service'])) { ?>
			<input type="checkbox" value="<?php echo $member['uid'];?>" name="changesid[]" />
		<?php } ?>
		<?php echo $member['sid'];?></td>
    <td align="center">
    	<?php if($member['sid']!=''&&$member['sid']!=0&&isset($GLOBALS['kefu_arr'][$member['sid']])) echo $GLOBALS['kefu_arr'][$member['sid']];else echo "暂无";?>
    </td>
    <td align="center">
    	<?php if($member['old_sid']) echo $member['old_sid'].'号&nbsp;';if (!empty($GLOBALS['kefu_arr'][$member['old_sid']])) echo $GLOBALS['kefu_arr'][$member['old_sid']];?>
    </td>
    <td align="center"><?php if($member['is_lock']==1) { ?>否<?php } else { ?>是<?php } ?></td>

	<!-- <td align="center"><a href="#" onclick="parent.addTab('<?php echo $member['telphone'];?>号注册会员','index.php?action=allmember&h=same_telphone&telphone=<?php echo $member['telphone'];?>','icon')">
	<?php if(isset($member['num'])) echo $member['num'];?></a>
	</td> -->
	
	<td align="center"><?php if($member['usertype']==3 ) { ?>
	                      <?php if(empty($member['action_time'])) { ?>
						       未使用
	                      <?php } else { ?> 
						      <?php if((!empty($member['action_time']) && $member['action_time']+3888000>time())) { ?>未过期<?php } else { ?>已过期<?php } ?>
						   <?php } ?>
					   <?php } else { ?>

                       <?php } ?>
     <td>
    <?php if(!empty($member['real_lastvisit'])) { ?>
    <?php if((time()-$member['real_lastvisit']<100)) { ?>
			<span style="color:red">在线&nbsp;&nbsp;&nbsp;<?php echo empty($member['client'])?'':'<img src="templates/images/wap_phone.gif" title="手机wap在线">';?></span>
		<?php } else { ?>
		<?php if(time()-$member['real_lastvisit']<24*3600) { ?>
			<span style="color:#0F0;">一天内</span>
		<?php } elseif ((time()-$member['real_lastvisit']<7*24*3600)&&(time()-$member['real_lastvisit']>24*3600)) { ?>
			<span style="color:#FF5;">一周内</span>
		<?php } else { ?>
             一周外
		<?php } ?>
	<?php } ?>
    <?php } ?>
    </td>
    <td align="center"><?php echo date("Y-m-d H:i:s",$member['last_login_time']);?></td>
    <td align="center"><?php if($member['next_contact_time']) echo date('y-m-d H:i:s',$member['next_contact_time']);?></td>

     
  </tr>
  <?php } ?>
 </table>
 <table cellpadding="4" cellspacing="0">
    <tr>
      <td align="center"><?php echo $page_links;?>
      	&nbsp;&nbsp;&nbsp;
      	转到第   <input name="pageGo"  id="pageGo" type="text" style="width:20px;height:15px;" value="" /> 页 &nbsp;
      <input type="button"  class="ser_go" value="跳转" onclick="gotoPage()"/></td>
      <td>
      	<!-- 只有系统管理员和客服主管才有权限在此分配客服  -->
	
		<?php if(in_array($GLOBALS['groupid'],$GLOBALS['admin_service_arr']) || in_array($GLOBALS['groupid'],$GLOBALS['admin_service_team']) || in_array($GLOBALS['groupid'],$GLOBALS['admin_service_after']) ) { ?>
        <input type="hidden" name="generalmembers" value="generalmembers" />
      	<select id="kefuuid" name="kefuuid">
			<?php foreach((array)$kefu_list as $kefu) {?>
			<option value="<?php echo $kefu['uid'];?>"><?php echo $kefu['uid'];?>号&nbsp;<?php echo $kefu['username'];?>&nbsp;<?php echo $kefu['member_count'];?>&nbsp;(<?php echo $kefu['allot_member'];?>)&nbsp;三：(<?php echo $kefu['three_day'];?>)七:(<?php echo $kefu['seven_day'];?>)</option>
			<?php } ?>
		</select>
			<input type="hidden" value="<?php echo $currenturl;?>" name="pre_url" id="pre_url" />
	      	<input type="submit" onclick="return changeusersid();" value="分配给此客服">
      	<?php } ?>
      </td>
    </tr>
  </table>
  </form>
</div>



</body>
</html>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="templates/css/general.css" rel="stylesheet" type="text/css" />
<link href="templates/css/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../public/system/js/sys1.js?v=1"></script>
<script type="text/javascript" src="templates/js/onmousemove_minutes.js"></script>
<script type="text/javascript" src="templates/js/My97DatePicker/WdatePicker.js"></script>
<script type="text/javascript">
//显示用户信息
function userdetail(number,arrayobj) {
	var arrname = arrayobj;
		for(i=0;i<arrayobj.length;i++) {
			var valueArray  = arrayobj[i].split(",");
			if(valueArray[0] == number) {
				if(valueArray[0] == '0' && valueArray[1] != '男士') {
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
        evt=evt || window.event;
	if(evt.pageX){          
		xPos=evt.pageX;
		yPos=evt.pageY;
	} else {      
		xPos=evt.clientX+document.body.scrollLeft-document.body.clientLeft;
		yPos=evt.clientY+document.body.scrollTop-document.body.clientTop;      
	}
        timerID = setTimeout("xy("+xPos+","+yPos+","+uid+")",500);
}
function xy(xPos,yPos,uid){
		
		if(xPos+70+600<window.screen.width){
		      $("#callinfo").css('left',xPos+70);
		      $("#callinfo").css('top',yPos-70);
		      $("#userid").html(uid);
		      $.get("ajax.php?n=callinfo",{uid:uid},function(str){
		              var arr=new Array();
		              arr=str.split('||');
		              $("#usertelphone").html(arr[0]);
		              $("#userbezhucontent").html(arr[1]);
		              $("#usercause").html(arr[2]);
		              $("#usercallstate").html(arr[3]);
		              $("#usercallremark").html(arr[3]);
		      });
		      $("#callinfo").css('display','block');
		//alert(xPos+'|'+yPos);
		      }else {$("#callinfo").css('display','none'); }
}
function hiddend(){
	       $("#callinfo").css('display','none');
	       clearTimeout(timerID);
	}


//分页跳转
function gotoPage() {
	var page = $("#pageGo").val();
	var page = parseInt(page);
	
	if(page<1) page = 1;
	if(page><?php echo ceil($total/$page_per);?>)

	page = <?php echo ceil($total/$page_per);?>;
	window.location.href = "<?php echo $currenturl;?>&page="+page;
}



//分配客服
function changeusersid(){
	var s_page_val = $("#s_page").val();
	var e_page_val = $("#e_page").val();
	var pre_url = $("#pre_url").val();
	var kefuuid = $("#kefuuid option:selected").val(); 
	
	if($.trim(s_page_val) != '' && $.trim(e_page_val) != ''){ //输入页数成批转换
		if(s_page_val<=0){
			alert('起始页数输入不正确');return false;
		}
		if(e_page_val > <?php echo ceil($total/$page_per);?>){
			alert('结束页数输入不正确');return false;
		}
		if(parseInt(s_page_val)>parseInt(e_page_val)){
			alert('起始页不得大于或等于结束页数');return false;
		}
		location.href="index.php?action=allmember&h=changeusersid_bat&s_page="+s_page_val+"e_page="+e_page_val+"&kefuuid="+kefuuid+"&pre_url="+pre_url;
		return;
	}else{
		var uidlist = "";
		$("input[name='changesid[]']:checked").each(function(){
			uidlist = 1;
		})
		
		if(uidlist==''){
			alert('请选择需要分配的会员');
			return false;
		}
		return true;	
	}

}

//排序
function chang_order(field,order){
	location.href="<?php echo $currenturl;?>&order="+field+"&method="+order;
}



$(function(){
	$(".csstab tr").mouseover(function(){
		$(this).addClass("over");
	}).mouseout(function(){
		$(this).removeClass("over");	
	})

})
</script>
<style>
tr.over td {
	background:#cfeefe;
} 

.callinfo{
position:absolute;
top:100px;
left:100px;
width:600px;
height:125px;
background:#ffffff;}
#callinfo table{
backgroung:#ffffff;}
#callinfo td{
border:#cccccc solid 1px;
}
</style>
</head>
<body>
<h1>
<span class="action-span"><a href="<?php echo $currenturl;?>">刷新当前页</a></span>
<span class="action-span"><a href="<?php echo $currenturl;?>&clear=1">刷新全部</a></span>
<span class="action-span1"><a href="###">真爱一生网 管理中心</a> </span><span id="search_id" class="action-span1"> - <?php echo $title;?> </span>
<div style="clear:both"></div>
</h1>
<div>
<form action="" method="get">
<input type='hidden' name='clear' value='1' />
  <tr>
  	<td colspan="8" style="text-align:left">
		搜索内容：
		<input name="keyword" type="text" id="keyword" value="<?php if(isset($_GET['keyword'])) echo $_GET['keyword']?>"/>
		<select name="choose" id="choose">
		    <option value="">不选择</option>
		    <option value="uid"<?php if($condition['uid']) { ?>selected="selected"<?php } ?>>ID号</option>
			<option value="username" <?php if($condition['username']) { ?>selected="selected"<?php } ?>>用户名</option>
		
			<option value="nickname" <?php if($condition['nickname']) { ?>selected="selected"<?php } ?>>昵称</option>
			<option value="telphone" <?php if($condition['telphone']) { ?>selected="selected"<?php } ?>>手机号</option>
			<option value="sid" <?php if($condition['sid']) { ?>selected="selected"<?php } ?>>客服id</option>
		</select>
		&nbsp;&nbsp;
		性别:
		<select name="gender">
			<option value="3">全部</option>
			<option value="1" <?php if($gender == '1') { ?>selected="selected"<?php } ?>>男</option>
			<option value="2" <?php if($gender == '2') { ?>selected="selected"<?php } ?>>女</option>
		</select>
		 &nbsp;
		<span>
			年龄:
			<select name="age_start" id="age1" class="m2 s47">
				<option value="-1">不限</option>
				
				<?php foreach((array)$age_arr as $v1) {?>
				<option value="<?php echo $v1;?>" <?php if($age_start == $v1) { ?>selected="selected"<?php } ?>><?php echo $v1;?></option>
				<?php } ?>
			</select>
			至
			<select name="age_end" id="age2" class="m2 s47"><option value="-1">不限</option>
				<?php foreach((array)$age_arr as $v2) {?>
				<option value="<?php echo $v2;?>" <?php if($age_end == $v2) { ?>selected="selected"<?php } ?>><?php echo $v2;?></option>
				<?php } ?>
			</select>
		</span>
	  
		&nbsp;&nbsp;&nbsp;
		省  份:&nbsp;&nbsp;<select name="province">
			<option value="">请选择</option>
			<option value="10102000" <?php if($province == '10102000') { ?>selected="selected"<?php } ?>>北京</option>
			<option value="10103000" <?php if($province == '10103000') { ?>selected="selected"<?php } ?>>上海</option>
			<option value="10101201" <?php if($province == '10101201') { ?>selected="selected"<?php } ?>>深圳</option>
			<option value="10101002" <?php if($province == '10101002') { ?>selected="selected"<?php } ?>>广州</option>
			<option value="10101000" <?php if($province == '10101000') { ?>selected="selected"<?php } ?>>广东</option>
			<option value="10104000" <?php if($province == '10104000') { ?>selected="selected"<?php } ?>>天津</option>
			<option value="10105000" <?php if($province == '10105000') { ?>selected="selected"<?php } ?>>重庆</option>
			<option value="10106000" <?php if($province == '10106000') { ?>selected="selected"<?php } ?>>安徽</option>
			<option value="10107000" <?php if($province == '10107000') { ?>selected="selected"<?php } ?>>福建</option>
			<option value="10108000" <?php if($province == '10108000') { ?>selected="selected"<?php } ?>>甘肃</option>
			<option value="10109000" <?php if($province == '10109000') { ?>selected="selected"<?php } ?>>广西</option>
			<option value="10110000" <?php if($province == '10110000') { ?>selected="selected"<?php } ?>>贵州</option>
			<option value="10111000" <?php if($province == '10111000') { ?>selected="selected"<?php } ?>>海南</option>
			<option value="10112000" <?php if($province == '10112000') { ?>selected="selected"<?php } ?>>河北</option>
			<option value="10113000" <?php if($province == '10113000') { ?>selected="selected"<?php } ?>>河南</option>
			<option value="10114000" <?php if($province == '10114000') { ?>selected="selected"<?php } ?>>黑龙江</option>
			<option value="10115000" <?php if($province == '10115000') { ?>selected="selected"<?php } ?>>湖北</option>
			<option value="10116000" <?php if($province == '10116000') { ?>selected="selected"<?php } ?>>湖南</option>
			<option value="10117000" <?php if($province == '10117000') { ?>selected="selected"<?php } ?>>吉林</option>
			<option value="10118000" <?php if($province == '10118000') { ?>selected="selected"<?php } ?>>江苏</option>
			<option value="10119000" <?php if($province == '10119000') { ?>selected="selected"<?php } ?>>江西</option>
			<option value="10120000" <?php if($province == '10120000') { ?>selected="selected"<?php } ?>>辽宁</option>
			<option value="10121000" <?php if($province == '10121000') { ?>selected="selected"<?php } ?>>内蒙古</option>
			<option value="10122000" <?php if($province == '10122000') { ?>selected="selected"<?php } ?>>宁夏</option>
			<option value="10123000" <?php if($province == '10123000') { ?>selected="selected"<?php } ?>>青海</option>
			<option value="10124000" <?php if($province == '10124000') { ?>selected="selected"<?php } ?>>山东</option>
			<option value="10125000" <?php if($province == '10125000') { ?>selected="selected"<?php } ?>>山西</option>
			<option value="10126000" <?php if($province == '10126000') { ?>selected="selected"<?php } ?>>陕西</option>
			<option value="10127000" <?php if($province == '10127000') { ?>selected="selected"<?php } ?>>四川</option>
			<option value="10128000" <?php if($province == '10128000') { ?>selected="selected"<?php } ?>>西藏</option>
			<option value="10129000" <?php if($province == '10129000') { ?>selected="selected"<?php } ?>>新疆</option>
			<option value="10130000" <?php if($province == '10130000') { ?>selected="selected"<?php } ?>>云南</option>
			<option value="10131000" <?php if($province == '10131000') { ?>selected="selected"<?php } ?>>浙江</option>
			<option value="10132000" <?php if($province == '10132000') { ?>selected="selected"<?php } ?>>澳门</option>
			<option value="10133000" <?php if($province == '10133000') { ?>selected="selected"<?php } ?>>香港</option>
			<option value="10134000" <?php if($province == '10134000') { ?>selected="selected"<?php } ?>>台湾</option>
		</select>
		&nbsp;
		
		<span>
			有无照片&nbsp;:&nbsp;
		<select name="uploadpicnum" style="width:100px;">
		        <option value="" >请选择</option>
		        <option value="6" <?php if(isset($uploadpicnum) && $uploadpicnum == '6') { ?>selected<?php } ?>>没有照片</option>
            	<option value="1" <?php if(isset($uploadpicnum) && $uploadpicnum == '1') { ?>selected<?php } ?>>1~5张</option>
             	<option value="2" <?php if(isset($uploadpicnum) && $uploadpicnum == '2') { ?>selected<?php } ?>>6~10张</option>
	  			<option value="3" <?php if(isset($uploadpicnum) && $uploadpicnum == '3') { ?>selected<?php } ?>>10~15张</option>	
                <option value="4" <?php if(isset($uploadpicnum) && $uploadpicnum == '4') { ?>selected<?php } ?>>16~20张</option>		
                <option value="5" <?php if(isset($uploadpicnum) && $uploadpicnum == '5') { ?>selected<?php } ?>>超过20张</option>		
        </select>
	   &nbsp;&nbsp;	
			
		<span >来源</span>
		<input type="text"id="source" name="source"  value="<?php if(isset($_GET['source'])) echo $_GET['source']?>"   style="width:230px;"/>
		
		<br>
		&nbsp;
		<span class="desc">在线状态:</span>
			<select name="online" style="width:70px;">
				<option value="">请选择</option>
				<option value="1" <?php if(isset($_GET['online']) && $_GET['online'] == 1) { ?>selected<?php } ?>>在线</option>
	  			<option value="2" <?php if(isset($_GET['online']) && $_GET['online'] == 2) { ?>selected<?php } ?>>一天内</option>
	  			<option value="3" <?php if(isset($_GET['online']) && $_GET['online'] == 3) { ?>selected<?php } ?>>一周内</option>
	  			<option value="4" <?php if(isset($_GET['online']) && $_GET['online'] == 4) { ?>selected<?php } ?>>一周外</option>
		</select>
		&nbsp;&nbsp;
		<span class="desc">会员类型:</span>
	   	   <select name="usertype" style="width:70px;">
				<option value="" <?php if(isset($_GET['usertype']) && $_GET['usertype'] == '') { ?>selected<?php } ?>>请选择</option>
	  			<option value="1" <?php if(isset($_GET['usertype']) && $_GET['usertype'] == 1) { ?>selected<?php } ?>>本站注册</option>
	  			<option value="2" <?php if(isset($_GET['usertype']) && $_GET['usertype'] == 2) { ?>selected<?php } ?>>外站加入</option>
	  			<option value="3" <?php if(isset($_GET['usertype']) && $_GET['usertype'] == 3) { ?>selected<?php } ?>>全权会员</option>
	  			<option value="4" <?php if(isset($_GET['usertype']) && $_GET['usertype'] == 4) { ?>selected<?php } ?>>联盟会员</option>
				<option value="5" <?php if(isset($_GET['usertype']) && $_GET['usertype'] == 5) { ?>selected<?php } ?>>内部会员</option>
	  	</select>	
	  			
		&nbsp;
		
		
		<span>
			<span>收  入:&nbsp;&nbsp;</span>			
			<select name="salary" id="salary" class="">
				<option selected="selected" value="-1">不限</option>
				<option value="1" <?php if($salary == '1') { ?>selected<?php } ?>>1000元以下</option>
				<option value="2" <?php if($salary == '2') { ?>selected<?php } ?>>1001-2000元</option>
				<option value="3" <?php if($salary == '3') { ?>selected<?php } ?>>2001-3000元</option>
				<option value="4" <?php if($salary == '4') { ?>selected<?php } ?>>3001-5000元</option>
				<option value="5" <?php if($salary == '5') { ?>selected<?php } ?>>5001-8000元</option>
				<option value="6" <?php if($salary == '6') { ?>selected<?php } ?>>8001-10000元</option>
				<option value="7" <?php if($salary == '7') { ?>selected<?php } ?>>10001-20000元</option>
				<option value="8" <?php if($salary == '8') { ?>selected<?php } ?>>20001-50000元</option>
				<option value="9" <?php if($salary == '9') { ?>selected<?php } ?>>50000元以上</option>
			</select>
		</span>
		&nbsp;
		<span>
			每页显示&nbsp;:&nbsp;
			<select name="page_per" id="page_per">
				<option value="">请选择</option>
				<option value="20" <?php if($page_per==20) { ?>selected<?php } ?>>20</option>
				<option value="30"  <?php if($page_per==30) { ?>selected<?php } ?>>30</option>
				<option value="50"  <?php if($page_per==50) { ?>selected<?php } ?>>50</option>
				<option value="80"  <?php if($page_per==80) { ?>selected<?php } ?>>80</option>
				<option value="100"  <?php if($page_per==100) { ?>selected<?php } ?>>100</option>
				<option value="200"  <?php if($page_per==200) { ?>selected<?php } ?>>200</option>
				<option value="300"  <?php if($page_per==300) { ?>selected<?php } ?>>300</option>
			</select>
			条
		</span>
		&nbsp;
		
		<span >注册时间：
			<!-- <input type="text" id="startTime" name="startTime" value="<?php if($startTime) echo $startTime;?>" onFocus="WdatePicker({startDate:'%y-%M-%D 00:00:00',dateFmt:'yyyy-MM-dd',alwaysUseStartDate:true})" style="width:100px;"/> 到 -->
			<input type="text"id="regdate" name="regdate"  value=""  onFocus="WdatePicker({startDate:'%y-%M-%D 00:00:00',dateFmt:'yyyy-MM-dd',alwaysUseStartDate:true})"  style="width:100px;"/>
	    </span>
		
		<span><input type="checkbox" id="mo" name="isMobileChecked" value="1" <?php if(isset($isMobileChecked)) { ?>checked<?php } ?>/><label for="mo">手机验证</label></span>
	</td>
	  </tr>
	  <input name="" type="submit" value="搜 索"/>&nbsp;&nbsp;&nbsp;&nbsp;
	  <input type="hidden" name="action" value="allmember" />
	  <input type="hidden" name="h" value="<?php echo $GLOBALS['h'];?>" />
	  &nbsp;
	  
	  	<span style="color:red;"><?php echo $total;?>位会员</span>
</form>
</div>
<div style=" margin-top:5px; margin-bottom:5px; color:#960">
排序方式：
<?php foreach((array)$sort_arr as $key=>$val) {?>
	<a href="<?php echo $currenturl;?>&order=<?php echo $key;?>&method=del">× </a>
    <span style="background-color:#CCC"><a href="<?php echo $currenturl;?>&order=<?php echo $key;?>&method=replace"><?php echo $allow_order[$key]?>
    <?php if($val == 'desc') { ?>
    	&nbsp;↓&nbsp;
    <?php } else { ?>
    	&nbsp;↑&nbsp;
    <?php } ?>
    </a></span>
    &#12288;
<?php }?>
</div>
<div class="list-div" id="listDiv" onmouseout="hiddend();">
<form action="index.php?action=allmember&h=changeusersid" method="post">
<table cellspacing='1' cellpadding='3' id='list-table' class ="csstab">
  <tr>
    <th><a href="javascript:chang_order('uid','<?php echo $rsort_arr['uid'];?>')"  style="text-decoration:underline;">ID</a></th>
    <th>用户名</th>
    <th>
		<a href="javascript:chang_order('birthyear','<?php echo $rsort_arr['birthyear'];?>')"  style="text-decoration:underline;">年龄</a>
	</th>
    <th>等级</th>
    <th>
		<a href="javascript:chang_order('images_ischeck','<?php echo $rsort_arr['images_ischeck'];?>')"  style="text-decoration:underline;">照片</a>
	</th>
    <th><a href="javascript:chang_order('salary','<?php echo $rsort_arr['salary'];?>')"  style="text-decoration:underline;">收入</a></th>
    <th>工作地</th>
	<th>短信<input type="checkbox" id="sms_all" value="1" onclick="smsAll();" /></th>

    <th><a href="javascript:chang_order('regdate','<?php echo $rsort_arr['regdate'];?>')"  style="text-decoration:underline;">注册时间</a></th>
    <?php if(in_array($GLOBALS['groupid'],$GLOBALS['admin_vip_level_check'])) { ?>
	<th>来源</th>
	<?php if($_GET['h'] == 'general' || $_GET['h'] == 'high' || $_GET['h'] == 'diamond' || $_GET['h'] == 'regnoallot_members') { ?>
	<th>电话验证</th>
	<th>原因</th>
	<?php } ?>
	<?php } ?>
	<!--<th><a href="index.php?action=allmember&h=<?php echo $_GET['h'];?>&order_tel=1&page=<?php echo $_GET['page'];?>" style="text-decoration:underline;"> 同号手机数</a></th>-->
    <th>
		<a href="javascript:chang_order('real_lastvisit','<?php echo $rsort_arr['real_lastvisit'];?>')"  style="text-decoration:underline;">在线状态</a>
    </th>
	<th><a href="javascript:chang_order('usertype','<?php echo $rsort_arr['usertype'];?>')"  style="text-decoration:underline;">会员类型</a></th>
    <th><a href="javascript:chang_order('login_meb','<?php echo $rsort_arr['login_meb'];?>')"  style="text-decoration:underline;">登录</a></th>
	<th>工号<input type="checkbox" id="choose_all" value="choose_all" onclick="chooseall();" /></th>
	<th><a href="javascript:chang_order('sid','<?php echo $rsort_arr['sid'];?>')"  style="text-decoration:underline;">客服</a></th>
    <th>原客服</th>
    <th><a href="javascript:chang_order('is_lock','<?php echo $rsort_arr['is_lock'];?>')"  style="text-decoration:underline;">锁定</a></th>
	<?php if(isset($renewals) && $renewals=='yes') { ?>
	<th><a href="javascript:chang_order('expiredstatus','<?php echo $rsort_arr['expiredstatus'];?>')">续费状态</a></th>
	<?php } ?>
	<?php if(isset($process) && $process=='yes') { ?>
	<th><a href="javascript:chang_order('memberprogress','<?php echo $rsort_arr['memberprogress'];?>')">会员进展</a></th>
	<?php } ?>
    <th>操作</th>
  </tr>
  <?php foreach((array)$member_list as $member) {?>
   <tr>
    <td align="center"><a href="#" class="userinfo" onclick="parent.addTab('查看<?php echo $member['uid'];?>资料','index.php?action=allmember&h=view_info&uid=<?php echo $member['uid'];?>','icon')"><?php echo $member['uid'];?></a></td>
    <td align="left" style="text-indent:30px;"  onmouseover="showinfo(event,<?php echo $member['uid'];?>);"> 
		<?php if($member['gender']==1) { ?><img src="templates/images/w.gif" alt="女" title="女"/>
		<?php } else { ?><img src="templates/images/m.gif" alt="男" title="男"/>
		<?php } ?>
		<a href="#" onclick="parent.addTab('查看<?php echo $member['uid'];?>资料','index.php?action=allmember&h=view_info&uid=<?php echo $member['uid'];?>','icon')"><?php echo $member['username'];?></a>
	</td>
    <td align="center"><?php echo date("Y")-$member['birthyear'];?></td>
    <td align="center"><?php echo $GLOBALS['member_level'][$member['s_cid']];?></td>
    <td align="center"><?php echo $member['mainimg']?"有":"无";?></td>
    <td align="center"><script>userdetail("<?php echo $member['salary'];?>",salary1);</script></td>
    <td align="center"><script>userdetail("<?php echo $member['province'];?>",provice);userdetail("<?php echo $member['city'];?>",city);</script></td>
	<td align="center" data-uid="<?php echo $member['uid'];?>"><input type="checkbox" value="1" name="uid[]" /></td>
  
    <td align="center"><?php echo date("Y-m-d H:i",$member['regdate']);?></td>
	<?php if(in_array($GLOBALS['groupid'],$GLOBALS['admin_vip_level_check']) || in_array($GLOBALS['groupid'],$GLOBALS['admin_the_service_arr'])) { ?>
    <td align="center"><?php echo wordwrap($member['source'],20,"<br />",true)?></td>
    <?php if($_GET['h'] == 'general' || $_GET['h'] == 'high' || $_GET['h'] == 'diamond' || $_GET['h']=='regnoallot_members') { ?>
    <td align="center">
    <?php if(($member['telphone_ifcheck'] == '0' || $member['telphone_ifcheck'] == '')) { ?>
              未验证
    <?php } else { ?>          
              已验证
    <?php } ?>
    </td>
    <td align="center">
    <?php if($member['checkreason'] == '0') { ?>
          未知
    <?php } ?>
    <?php if($member['checkreason'] == '1') { ?>
          关机
    <?php } ?>
    <?php if($member['checkreason'] == '2') { ?>
          打通无人接听
    <?php } ?>
    <?php if($member['checkreason'] == '3') { ?>
          接听无意验证
    <?php } ?>
    <?php if($member['checkreason'] == '4') { ?>
         空号     
    <?php } ?>
    <?php if($member['checkreason'] == '5') { ?>
        喜欢网站
    <?php } ?>
   <?php if($member['checkreason'] == '6') { ?>
         试试看
    <?php } ?>
    </td>
    <?php } ?>
	<?php } ?>


    <td>
    <?php if(!empty($member['real_lastvisit'])) { ?>
    <?php if((time()-$member['real_lastvisit']<100)) { ?>
			<span style="color:red">在线</span>
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

	<td align="center">
		<?php if($member['usertype']==1)echo "本站注册";if($member['usertype']==2)echo "外站加入";if($member['usertype']==3)echo "全权会员";?>
	</td>
    <td align="center"><?php echo $member['login_meb'];?></td>
	
    <td align="center">
    	<input type="checkbox" value="<?php echo $member['uid'];?>" name="changesid[]" />
    	<?php if($member['sid']!='' && $member['sid']!=0) echo $member['sid'].'号';else echo "暂无";?>
    </td>
	<td align="center"><?php if(isset($GLOBALS['kefu_arr'][$member['sid']])) echo $GLOBALS['kefu_arr'][$member['sid']]?></td>
    <td align="center"><?php if(isset($GLOBALS['kefu_arr'][$member['old_sid']])) echo $GLOBALS['kefu_arr'][$member['old_sid']]?></td>
    <td align="center"><?php if($member['is_lock']==1) echo '否';else echo '<font color="#FF0000">是</font>';?></td>
	<?php if(isset($renewals) && $renewals=='yes') { ?>
	<td align="center"><?php if($member['expiredstatus']==0) { ?>0：默认<?php } else { ?><?php echo $expired_status_grade[$member['expiredstatus']];?><?php } ?></td>
	<?php } ?>
	<?php if(isset($process) && $process=='yes') { ?>
	<td align="center"><?php if($member['memberprogress']==0) { ?>0：默认<?php } else { ?><?php echo $member_progress_grade[$member['memberprogress']];?><?php } ?></td>
	<?php } ?>
    <td align="center">

      <a href="#" onclick="parent.addTab('查看<?php echo $member['uid'];?>资料','index.php?action=allmember&h=record&uid=<?php echo $member['uid'];?>&sid=<?php echo $member['sid'];?>','icon')">录音</a>
	  <?php if((in_array($member['s_cid'],array(20,30)) && $member['usertype']==1) && !in_array($GLOBALS['groupid'],$GLOBALS['admin_service_arr']) && !in_array($GLOBALS['groupid'],$GLOBALS['admin_aftersales_service'])) { ?>
	 
	  <?php } elseif ($member['usertype']==3	&& !in_array($GLOBALS['groupid'],$GLOBALS['admin_service_arr']) && !in_array($GLOBALS['groupid'],$GLOBALS['admin_aftersales_service']) && !in_array($GLOBALS['groupid'],$GLOBALS['admin_service_group'])) { ?>
					
	  <?php } else { ?>
	  &nbsp;&nbsp;|&nbsp;&nbsp;
      <a href="#" onclick="parent.addTab('修改会员<?php echo $member['uid'];?>资料','index.php?action=allmember&h=edit_info&uid=<?php echo $member['uid'];?>')">修改资料</a>
	  <?php } ?>
	</td>
  </tr>
  <?php } ?>
 </table>
 
<div style="padding:5px;background:#fff">
	
</div>

<table cellpadding="4" cellspacing="0">
    <tr>
      <td align="center"><?php echo $page_links;?>
      	&nbsp;&nbsp;&nbsp;
      	转到第   <input name="pageGo"  id="pageGo" type="text" style="width:20px;height:15px;" value="" /> 页 &nbsp;
      <input type="button"  class="ser_go" value="跳转" onclick="gotoPage()"/></td>
      <td>
      <!-- 从<input type="text" value="" name="s_page" id="s_page" style="width:30px;" />到<input type="text" value="" name="e_page" id="e_page" style="width:30px;"/>页　分配给 -->	
        <input type="hidden" name="generalmembers" value="generalmembers" />
        <select id="kefuuid" name="kefuuid">
			<?php foreach((array)$kefu_list as $kefu) {?>
			<option value="<?php echo $kefu['uid'];?>"><?php echo $kefu['usercode'];?>号&nbsp;<?php echo $kefu['username'];?>&nbsp;<?php echo $kefu['member_count'];?>&nbsp;(<?php echo $kefu['allot_member'];?>)&nbsp;三：(<?php echo $kefu['three_day'];?>)七:(<?php echo $kefu['seven_day'];?>)</option>
			<?php } ?>
		</select>
		<input type="hidden" value="<?php echo $currenturl;?>" name="pre_url" id="pre_url" />
      	<input type="submit" onclick="return changeusersid();" value="分配给此客服">
     
      </td>
    </tr>
  </table>
  </form>
</div>

<div class="callinfo" id="callinfo" style="display:none;">
<table width="600" border="1">
  <tr>
    <td width="50" height="20">会员ID</td>
    <td width="50" id="userid">&nbsp;</td>
    <td width="50">手机号</td>
    <td width="78" id="usertelphone">&nbsp;</td>
    <td width="50">下次联系时间</td>
    <td width="150" id="usercause">&nbsp;</td>
  </tr>
  <tr>
    <td height="43" id="userbezhu">会员备注</td>
    <td colspan="5" id="userbezhucontent">&nbsp;</td>
  </tr>
  <tr>
    <td height="43">下次联系要点</td>
    <td colspan="5" id="usercallremark">&nbsp;</td>
  </tr>
</table>
</div>
<script type="text/javascript">

//全选
function chooseall(){
	if($("#choose_all").attr("checked")){
		$("input[name='changesid[]']").attr("checked",true);
	}else{
		$("input[name='changesid[]']").attr("checked",false);
	}
}

function smsAll(){
    var _f_=false;
	if($("#sms_all").attr("checked")){
		$("input[name='uid[]']").attr("checked",true);
		_f_=true;
	}else{
		$("input[name='uid[]']").attr("checked",false);
	}
	
	var uid=new Array();
    $("input[name^='uid']").each(function(i,w){
	    var key=$(w).parent('td').attr('data-uid');
		var flag=0;
		if(_f_) flag=1;
		var s=key+'-'+flag;
	    uid.push(s);
	});
    
    $.ajax({
	    url:'./allmember_ajax.php?n=sendsms',
		dataType:'text',
		type:'post',
		data:{uid:uid},
		success:function(json){
		    console.log(json);
		}
	});
}


   
$("input[name^='uid']").click(function(){
    var uid=$(this).parent('td').attr('data-uid');
	var flag=0;
	if($(this).attr('checked')) flag=1;
    $.ajax({
	    url:'./allmember_ajax.php?n=sendsms',
		dataType:'json',
		type:'post',
		data:{uid:uid,flag:flag},
		success:function(json){
		    console.log(json);
		}
	});
});
</script>
</body>
</html>
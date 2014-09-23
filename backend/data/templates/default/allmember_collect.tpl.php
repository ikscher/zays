<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="templates/css/general.css" rel="stylesheet" type="text/css" />
<link href="templates/css/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../public/system/js/sys.js?v=1"></script>
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
	var s_page_val = $("#s_page").val();
	var e_page_val = $("#e_page").val();
	var pre_url = $("#pre_url").val();
	var kefuuid = $("#kefuuid option:selected").val(); 
	if($.trim(s_page_val) != '' && $.trim(e_page_val) != ''){ //输入页数成批转换
		if(s_page_val<=0){
			alert('起始页数输入不正确');return;
		}
		if(e_page_val > <?php echo ceil($total/$page_per);?>){
			alert('结束页数输入不正确');return;
		}
		if(parseInt(s_page_val)>parseInt(e_page_val)){
			alert('起始页不得大于或等于结束页数');return;
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

//月收入，年龄筛选条件
$(function(){
	$('#birthyear,#salary').click(function(){
		var birthyear = $('#birthyear:checked').val() ? $('#birthyear:checked').val() : '';
		var salary = $('#salary:checked').val() ? $('#salary:checked').val() : '';
		window.location.href = "<?php echo $currenturl;?>&birthyear="+birthyear+"&salary="+salary;			   
	})
})

$(function(){
	$(".csstab tr").mouseover(function(){
		$(this).addClass("over");
	}).mouseout(function(){
		$(this).removeClass("over");	
	})

	$("#page_per").change(function(){
		var page_per = this.value;
		location.href="<?php echo $currenturl;?>"+"&page_per="+page_per;
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
<span class="action-span"><a href="<?php if(isset($currenturl1)) { ?><?php echo $currenturl1;?><?php } ?>">刷新全部</a></span>
<span class="action-span1"><a href="###">真爱一生网 管理中心</a> </span><span id="search_id" class="action-span1"> - <?php echo $title;?> </span>
<div style="clear:both"></div>
</h1>
<div>
<form action="" method="get">
<input type='hidden' name='clear' value='1' />
  <tr>
  	<td colspan="8" style="text-align:left">
		搜索内容：
		<input name="keyword" type="text" id="keyword" value="<?php echo @$condition['uid'].@$condition['username'].@$condition['nickname'].@$condition['telphone'].@$condition['sid'];?>"/>
		<select name="choose" id="choose">
		    <option value="" <?php if(empty($condition)) { ?>selected="selected" <?php } ?>>不选择</option>
		    <option value="uid" <?php if(!empty($condition['uid'])) { ?>selected="selected"<?php } ?>>ID号</option>
			<option value="username" <?php if(!empty($condition['username'])) { ?>selected="selected"<?php } ?>>用户名</option>
		
			<option value="nickname" <?php if(!empty($condition['nickname'])) { ?>selected="selected"<?php } ?>>昵称</option>
			<option value="telphone" <?php if(!empty($condition['telphone'])) { ?>selected="selected"<?php } ?>>手机号</option>
			<option value="sid" <?php if(isset($condition['sid']) && $condition['sid'] != 0) { ?>selected="selected"<?php } ?>>客服id</option>
		</select>
		<?php if(isset($grade) && $grade) { ?>
		   <select name="effect_grade" id="efffect_grade" class="m2 s47">
				<option value="0">不限</option>
				
				<?php foreach((array)$grade as $k=>$v) {?>
				  <option    <?php if($effect_grade + 1== $k) { ?> selected="selected" value="<?php echo $effect_grade;?> "  <?php } ?>><?php echo $v;?></option>
				<?php }?>
			</select>  
		<?php } ?>
		性别:
		<select name="gender">
			<option value="3">全部</option>
			<option value="2" <?php if(isset($condition['gender']) && $condition['gender'] == 0) { ?>selected="selected"<?php } ?>>男</option>
			<option value="1" <?php if(isset($condition['gender']) && $condition['gender'] == 1) { ?>selected="selected"<?php } ?>>女</option>
		</select><span>
			年龄:
			<select name="age_start" id="age1" class="m2 s47">
				<option value="0">不限</option>
				<?php foreach((array)$age_arr as $v1) {?>
				<option value="<?php echo $v1;?>" <?php if(isset($age_start) && $age_start == $v1) { ?>selected="selected"<?php } ?>><?php echo $v1;?></option>
				<?php } ?>
			</select>
			至
			<select name="age_end" id="age2" class="m2 s47"><option value="0">不限</option>
				<?php foreach((array)$age_arr as $v2) {?>
				<option value="<?php echo $v2;?>" <?php if(isset($age_end) && $age_end == $v2) { ?>selected="selected"<?php } ?>><?php echo $v2;?></option>
				<?php } ?>
			</select>
		</span><span class="desc">在线状态:</span>
			<select name="online" style="width:70px;">
				<option value="0">请选择</option>
				<option value="1" <?php if(isset($_GET['online']) && $_GET['online'] == 1) { ?>selected<?php } ?>>在线</option>
	  			<option value="2" <?php if(isset($_GET['online']) &&  $_GET['online'] == 2) { ?>selected<?php } ?>>一天内</option>
	  			<option value="3" <?php if(isset($_GET['online']) &&  $_GET['online'] == 3) { ?>selected<?php } ?>>一周内</option>
	  			<option value="4" <?php if(isset($_GET['online']) &&  $_GET['online'] == 4) { ?>selected<?php } ?>>一周外</option>
		</select>
		
		  省：<script>getProvinceSelect43rds('','province','province','city','','10100000');</script>
          市：<script>getCitySelect43rds('','city','city','','');</script>
		
		
		是否锁定:
		<select name="islock">
			<option value="">全部</option>
			<option value="1" <?php if(isset($condition['is_lock']) && $condition['is_lock']==1) { ?>selected="selected"<?php } ?>>否</option>
			<option value="2" <?php if(isset($condition['is_lock']) && !$condition['is_lock']) { ?>selected="selected"<?php } ?>>是</option>
		</select>
		
		
	
		<span >注册时间：</span>
		<!-- <input type="text" id="startTime" name="startTime" value="<?php if($startTime) echo $startTime;?>" onFocus="WdatePicker({startDate:'%y-%M-%D 00:00:00',dateFmt:'yyyy-MM-dd',alwaysUseStartDate:true})" style="width:100px;"/> 到 -->
		<input type="text"id="regdate" name="regdate"  value=""  onFocus="WdatePicker({startDate:'%y-%M-%D 00:00:00',dateFmt:'yyyy-MM-dd',alwaysUseStartDate:true})"  style="width:100px;"/>
	
	    <?php if($usertype==3) { ?>
		    <input type="radio" name="isExpire" value="1" <?php if($isExpire==1) { ?> checked="checked" <?php } ?> />使用过期
		    <input type="radio" name="isExpire" value="2" <?php if($isExpire==2) { ?> checked="checked" <?php } ?> />使用未过期
			<input type="radio" name="isExpire" value="3" <?php if($isExpire==3) { ?> checked="checked" <?php } ?> />未使用的
		<?php } ?>
		
		<input type="hidden" name="action" value="allmember" />
		<input type="hidden" name="h" value="<?php echo $GLOBALS['h'];?>" />
		<input name="" type="submit" value="搜 索"/>&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:red;"><?php echo $total;?>位会员</span>
&nbsp;&nbsp;
		<?php if(isset($processlink)) { ?>
		会员进展：
		<!-- <?php foreach((array)$processlink as $k=>$v) {?> -->
		<span class="processlink"><a href="index.php?action=allmember&h=<?php echo $_GET['h'];?>&process=<?php echo $k;?>"><?php echo $v;?></a></span>
		<!-- <?php }?> -->
		<?php } ?>
		<?php if(isset($renewalslink)) { ?>
		续费状态：
		<!-- <?php foreach((array)$renewalslink as $k=>$v) {?> -->
		<span class="expiredlink"><a href="index.php?action=allmember&h=<?php echo $_GET['h'];?>&renewals=<?php echo $k;?>"><?php echo $v;?></a></span>
		<!-- <?php }?> -->
		<?php } ?>
		<?php if($_GET['h']=='regnoallot_members' || $_GET['h']=='goodnoallot_members') { ?>
		<span class="but_a">
        	<a <?php if(isset($condition['gender']) && !$condition['gender']) { ?>style="font-weight:bold;"<?php } ?> href="<?php echo $currenturl;?>&gender=2">男性会员</a>
			<a <?php if(isset($condition['gender']) && $condition['gender']==1) { ?>style="font-weight:bold;"<?php } ?>href="<?php echo $currenturl;?>&gender=1">女性会员</a>
			&nbsp;&nbsp;&nbsp;年龄25-35<input type="checkbox" value="birthyear" name="birthyear" id="birthyear" <?php if($birthyear) { ?>checked<?php } ?>>&nbsp;&nbsp;&nbsp;
            月收入3000元以上<input type="checkbox" value="salary" name="salary" id="salary" <?php if($salary) { ?>checked<?php } ?>><br />
        </span>
		<span>
			每页显示
			<select name="page_per" id="page_per">
				<option value="20" <?php if($page_per==20) { ?>selected<?php } ?>>20</option>
				<option value="30"  <?php if($page_per==30) { ?>selected<?php } ?>>30</option>
				<option value="50"  <?php if($page_per==50) { ?>selected<?php } ?>>50</option>
				<option value="80"  <?php if($page_per==80) { ?>selected<?php } ?>>80</option>
				<option value="100"  <?php if($page_per==100) { ?>selected<?php } ?>>100</option>
				<option value="200"  <?php if($page_per==200) { ?>selected<?php } ?>>200</option>
			</select>
			条
		</span>
		<?php } ?>

	

	</td>
	  </tr>
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
	<th>类</th>
    <th><a href="javascript:chang_order('allotdate','<?php echo $rsort_arr['allotdate'];?>')"  style="text-decoration:underline;">分配时间</a></th>
    <th><a href="javascript:chang_order('regdate','<?php echo $rsort_arr['regdate'];?>')"  style="text-decoration:underline;">注册时间</a></th>
	
	
	<?php if($usertype==3) { ?><th><a href="javascript:chang_order('action_time','<?php echo $rsort_arr['action_time'];?>')"  style="text-decoration:underline;">使用时间</a></th><?php } ?>
	
	
    <?php if(in_array($GLOBALS['groupid'],$GLOBALS['admin_vip_level_check'])) { ?>
	<th>来源</th>
	<?php if($_GET['h'] == 'general' || $_GET['h'] == 'high' || $_GET['h'] == 'diamond') { ?>
	<th>电话验证</th>
	<th>原因</th>
	<?php } ?>
	<?php } ?>
	<!--<th><a href="index.php?action=allmember&h=<?php echo $_GET['h'];?>&order_tel=1&page={_GET['page']}" style="text-decoration:underline;"> 同号手机数</a></th>-->
    <th>
		<a href="javascript:chang_order('real_lastvisit','<?php echo $rsort_arr['real_lastvisit'];?>')"  style="text-decoration:underline;">在线状态</a>
    </th>
	<th><a href="javascript:chang_order('usertype','<?php echo $rsort_arr['usertype'];?>')"  style="text-decoration:underline;">会员类型</a></th>
    <th><a href="javascript:chang_order('login_meb','<?php echo $rsort_arr['login_meb'];?>')"  style="text-decoration:underline;">登录</a></th>
	<th>工号<input type="checkbox" id="choose_all" value="choose_all" onclick="chooseall()" /></th>
	<th><a href="javascript:chang_order('sid','<?php echo $rsort_arr['sid'];?>')"  style="text-decoration:underline;">客服</a></th>
    <th><a href="javascript:chang_order('is_lock','<?php echo $rsort_arr['is_lock'];?>')"  style="text-decoration:underline;">锁定</a></th>
	<?php if(isset($renewalsapper) && $renewalsapper=='yes') { ?>
	<th><a href="javascript:chang_order('renewalstatus','<?php echo $rsort_arr['renewalstatus'];?>')">续费状态</a></th>
	<?php } ?>
	<?php if(isset($process) && $process=='yes') { ?>
	<th><a href="javascript:chang_order('memberprogress','<?php echo $rsort_arr['memberprogress'];?>')">会员进展</a></th>
	<?php } ?>
    <th>操作</th>
  </tr>
  <?php foreach((array)$member_list as $member) {?>
    <?php if(($isExpire==3 && $member['action_time']=='') || $isExpire==2 || $isExpire==1 || !$isExpire) { ?>
	 
   <tr>
    <td align="center"><a href="#" class="userinfo" onclick="parent.addTab('查看<?php echo $member['uid'];?>资料','index.php?action=allmember&h=view_info&uid=<?php echo $member['uid'];?>','icon')"><?php echo $member['uid'];?></a></td>
    <td align="left" style="text-indent:10px;"  onmouseover="showinfo(event,<?php echo $member['uid'];?>);"> 
		<?php if($member['gender']==1) { ?><img src="templates/images/w.gif" alt="女" title="女"/>
		<?php } else { ?><img src="templates/images/m.gif" alt="男" title="男"/>
		<?php } ?>
		<a href="#" onclick="parent.addTab('查看<?php echo $member['uid'];?>资料','index.php?action=allmember&h=view_info&uid=<?php echo $member['uid'];?>','icon')"><?php echo $member['username'];?></a>
	</td>
    <td align="center"><?php echo date("Y")-$member['birthyear'];?></td>
    <td align="center"><?php if($member['s_cid'] =='10') { ?>铂金会员<?php } elseif (isset($GLOBALS['member_level'][$member['s_cid']])) { ?><?php echo $GLOBALS['member_level'][$member['s_cid']];?><?php } ?></td>
    <td align="center"><?php echo isset($member['mainimg']) && $member['mainimg']?"有":"无";?></td>
    <td align="center"><script>userdetail("<?php echo $member['salary'];?>",salary1);</script></td>
    <td align="center"><script>userdetail("<?php echo $member['province'];?>",provice);userdetail("<?php echo $member['city'];?>",city);</script></td>
	<td align="center"><?php if($member['effect_grade']>0) echo $member['effect_grade']-1;?></td>
    <td align="center"><?php if($member['allotdate']) echo date("Y-m-d H:i",$member['allotdate']);?></td>
    <td align="center"><?php echo date("Y-m-d H:i",$member['regdate']);?></td>
	<td align="center"><?php if(empty($member['action_time'])) { ?>未使用<?php } else { ?><?php echo date("Y-m-d H:i:s",$member['action_time']);?><?php } ?></td>
	
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

	<td align="center">
		全权会员
	</td>
    <td align="center"><?php echo $member['login_meb'];?></td>
	
    <td align="center">
    	<input type="checkbox" value="<?php echo $member['uid'];?>" name="changesid[]" />
    	<?php if($member['sid']!='' && $member['sid']!=0) echo $member['sid'].'号';else echo "暂无";?>
    </td>
	<td align="center"><?php if($member['sid']) echo $GLOBALS['kefu_arr'][$member['sid']]?></td>
    <td align="center"><?php if($member['is_lock']==1) echo '否';else echo '<font color="#FF0000">是</font>';?></td>
	<?php if(isset($renewalsapper) && $renewalsapper=='yes') { ?>
	<td align="center"><?php if(isset($member['renewalstatus']) && $member['renewalstatus']==0) { ?>0：默认<?php } elseif (isset($member['renewalstatus']) && isset($expired_status_grade[$member['renewalstatus']])) { ?><?php echo $expired_status_grade[$member['renewalstatus']];?><?php } ?></td>
	<?php } ?>
	<?php if(isset($process) && $process=='yes') { ?>
	<td align="center"><?php if(isset($member['memberprogress']) && $member['memberprogress']==0) { ?>0：默认<?php } elseif (isset($member['memberprogress']) && isset($member_progress_grade[$member['memberprogress']])) { ?><?php echo $member_progress_grade[$member['memberprogress']];?><?php } ?></td>
	<?php } ?>
    <td align="center">

      <a href="#" onclick="parent.addTab('查看<?php echo $member['uid'];?>资料','index.php?action=allmember&h=record&uid=<?php echo $member['uid'];?>&sid=<?php echo $member['sid'];?>','icon')">录音</a>
	  <?php if((in_array($member['s_cid'],array(20,30)) && $member['usertype']==1) && !in_array($GLOBALS['groupid'],$GLOBALS['admin_service_arr']) && !in_array($GLOBALS['groupid'],$GLOBALS['admin_aftersales_service'])) { ?>
	 
	  <?php } elseif ($member['usertype']==3	&& !in_array($GLOBALS['groupid'],$GLOBALS['admin_service_arr']) && !in_array($GLOBALS['groupid'],$GLOBALS['admin_aftersales_service']) && !in_array($GLOBALS['groupid'],$GLOBALS['admin_service_group'])) { ?>

      <?php } else { ?>
	  &nbsp;&nbsp;|&nbsp;&nbsp;
      <a href="#" onclick="parent.addTab('修改会员<?php echo $member['uid'];?>资料','index.php?action=allmember&h=edit_info&uid=<?php echo $member['uid'];?>')">修改资料</a>
	  <?php } ?>
      <?php if($member['usertype']==3) { ?>
      &nbsp;&nbsp;|&nbsp;&nbsp;<a href="index.php?action=allmember&h=delcollect&uid=<?php echo $member['uid'];?>">删除</a>
      <?php } ?>
	</td>
  </tr>
     <?php } ?>
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
      	<?php if(isset($generalmembers) && $generalmembers=='generalmembers') { ?>
      	<input type="hidden" name="generalmembers" value="generalmembers" />
        <?php } ?>
      	<select id="kefuuid" name="kefuuid">
			<?php foreach((array)$kefu_list as $kefu) {?>
			<option value="<?php echo $kefu['uid'];?>"><?php echo $kefu['uid'];?>号&nbsp;<?php echo $kefu['username'];?>&nbsp;<?php echo $kefu['member_count'];?>&nbsp;(<?php echo $kefu['allot_member'];?>)&nbsp;三：(<?php echo $kefu['three_day'];?>)七:(<?php echo $kefu['seven_day'];?>)</option>
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
</body>
</html>
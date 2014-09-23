<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="templates/css/general.css" rel="stylesheet" type="text/css" />
<link href="templates/css/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="templates/js/jquery.floatDiv.js"></script>
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
        timerID = setTimeout("xy("+xPos+","+yPos+","+uid+")",400);
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
					  $("#callinfo").css('display','block');
		      });

		      
		//alert(xPos+'|'+yPos);
		      }else {$("#callinfo").css('display','none'); }
}
function hiddend(){
	       $("#callinfo").css('display','none');
	       clearTimeout(timerID);
	}


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
function choose_all(){
	if($("#choose_all").attr("checked")){
		$("input[name='changesid[]']").attr("checked",true);
	}else{
		$("input[name='changesid[]'").attr("checked",false);
	}
}

//分配客服
function changeusersid(){
	var kefuuid = $("#kefuuid option:selected").val(); 
	var uidlist = "";
	$("input[name='changesid[]']:checked").each(function(){
		uidlist += this.value+',';
	})
	if(uidlist==''){
		alert('请选择需要分配的会员');
		return;
	}
	location.href="index.php?action=allmember&h=changeusersid&uidlist="+uidlist+"&kefuuid="+kefuuid;

}

$(function(){
	$(".csstab tr").click(function(){
		$(".csstab tr").removeClass("over");
		$(this).addClass("over");
	})
	$("#listDiv input[name^='is_awoke_']").click(function(){
		var uid = this.value;
		$.get("myuser_ajax.php?n=change_awoke",{uid:uid},function(str){
			if(str == 'error') alert('意外出错,请稍后再试');
		})
	})
})

//排序
function chang_order(field,order){
	location.href="index.php?action=myuser&h=<?php echo $_GET['h'];?>&order="+field+"&method="+order;
}
function view_transfer(evt,uid){
	var evt = evt;
	//alert(200+evt*50);
	//$("#transfer_box").css("top",200+evt*50);
	//alert(evt);
	evt=evt || window.event;
	if(evt.pageX){          
		xPos1=evt.pageX;
		yPos1=evt.pageY;
	} else {      
		xPos1=evt.clientX+document.body.scrollLeft-document.body.clientLeft;
		yPos1=evt.clientY+document.body.scrollTop-document.body.clientTop;      
	}
	$("#transfer_box").css("left",xPos1-550);
	$("#transfer_box").css("top",yPos1);
	$("#transfer_box").show();
	url = "myuser_ajax.php?n=transfer&uid="+uid;
	$.getJSON(url,function(data){
		var str = '';
		if(data != 0){
			str = "原客服ID："+data.sid+"<br>服务期限："+data.servicetime+"个月<br>付款金额："+data.payments+"元<br>委托会员ID："+data.otheruid+"<br>模拟聊天记录："+data.chatnotes+"<br>升级会员情况："+data.intro+"<br>委托会员情况："+data.otherintro+"<br>最后一次沟通情况："+data.lastcom+"<br>备注："+data.remark;
		}else{
			str = '没有交接内容';
		}
		$("#transfer_box p").html(str);
	})
}

</script>
<style>
tr.over td {
	background:#cfeefe;
} 
#transfer_box{
	border:1px solid #1C3F80;
	background:#CEDAEA;
	left:400px;
	top:200px;
	position:absolute;
	width:400px;
	z-index:10;
}
#transfer_box span{
	display:block;
	margin:1px;
	padding:3px;
	float:right;
	background-color:#FFF;
}
#transfer_box p{
	margin:5px;
	padding-bottom:3px;
}
a img{
	border:0px;
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

.bgcolorchange{
    background:#f00;
}
.c01{color:#FFCC00;}.c02{color:#660000;}.c03{color:#666600;}.c04{color:#66CC00;}.c05{color:#336600;#330000;}.c06{color:#FF66CC;}.c07{color:#6600CC;}.c08{color:#3366FF;}.c19{color:#330099;}
.c10{color:#9933FF;}.c11{color:#CCCC00;}.c12{color:#996600;}.c13{color:#009900;}.c14{color:#6600FF;}.c15{color:#003366;}.c16{color:#006699;}.c17{color:#FF6699;}.c18{color:#00FF00;}.c19{color:#336699;}
.c20{color:#CC9933;}.c21{color:#33FF00;}.c22{color:#9933CC;}.c23{color:#CC0099;}.c24{color:#CC6633;}.c25{color:#663300;}.c26{color:#99CC00;}.c27{color:#66CC00;}.c28{color:#006600;}.c29{color:#6699FF;}
.c30{color:#333399;}.c31{color:#CCCC33;}

</style>
</head>
<body>
<h1>
<span class="action-span"><a href="index.php?action=myuser&h=<?php echo $_GET['h'];?>">刷新当前页</a></span>
<span class="action-span"><a href="index.php?action=myuser&h=<?php echo $_GET['h'];?>&clear=1">全部刷新</a></span>
<span class="action-span1"><a href="###">真爱一生网 管理中心</a> </span><span id="search_id" class="action-span1"> -- <?php echo $title;?></span>
<div style="clear:both"></div>
</h1>
<div>
<form action="" method="get">
  <tr>
  	<td colspan="8" style="text-align:left">
		日期搜索：
		<input type="text" name="search_date" value="<?php if(isset($_GET['search_date'])) echo $_GET['search_date'];?>" onFocus="WdatePicker({startDate:'%y-%M-%D 00:00:00',dateFmt:'yyyy-MM-dd',alwaysUseStartDate:true})" style="width:100px;"/>
		<input type="radio" name="search_type" value="1" <?php if((isset($_GET['search_type'])?$_GET['search_type']:'')==1 || empty($_GET['search_type'])) { ?>checked="checked"<?php } ?> />注册
		<input type="radio" name="search_type" value="2" <?php if((isset($_GET['search_type'])?$_GET['search_type']:'')==2) { ?>checked="checked"<?php } ?>/>分配
		&nbsp;
		UID:<input type="text" name="uid" value="<?php echo isset($_GET['uid'])?$_GET['uid']:'';?>" style="width:100px;" />
		<input type="hidden" name="action" value="myuser" />
		<input type="hidden" name="h" value="<?php echo $GLOBALS['h'];?>" />
		
		<input name="" type="submit" value="搜 索"/>&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:red;"><?php echo $total;?>位会员(<?php echo $online_member_total;?>位在线)</span><?php if((isset($h)?$h:'')=='new_member') { ?> 当天新分会员<?php echo $allot_member;?>个<?php } ?>
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
<table cellspacing='1' cellpadding='3' id='list-table' class ="csstab">
  <tr>
    <th><a href="javascript:chang_order('uid', '<?php echo $rsort_arr['uid'];?>')"  style="text-decoration:underline;">ID</a></th>
    <th>昵称</th>
    <th><a href="javascript:chang_order('birthyear', '<?php echo $rsort_arr['birthyear'];?>')"  style="text-decoration:underline;">年龄</a></th>
    <th>等级</th>
    <th>照片</th>
    <th><a href="javascript:chang_order('salary', '<?php echo $rsort_arr['salary'];?>')"  style="text-decoration:underline;">收入</a></th>
    <th>工作地</th>
    <th><a href="javascript:chang_order('allotdate', '<?php echo $rsort_arr['allotdate'];?>')"  style="text-decoration:underline;">分配时间</a></th>
    <?php if(in_array($GLOBALS['groupid'],$GLOBALS['admin_aftersales_service']) || in_array($GLOBALS['groupid'],$GLOBALS['admin_aftersales_service2'])) { ?>
    <th>会员服务时间</th>
    <?php } else { ?>
    <th><a href="javascript:chang_order('regdate', '<?php echo $rsort_arr['regdate'];?>')"  style="text-decoration:underline;">注册时间</a></th>
    <?php } ?>
    <?php if(in_array($GLOBALS['groupid'],$GLOBALS['admin_vip_level_check'])) { ?>
	<th>来源</th>
	<?php } ?>
    <th><a href="javascript:chang_order('login_meb', '<?php echo $rsort_arr['login_meb'];?>')"  style="text-decoration:underline;">登录</a></th>
    <th><a href="javascript:chang_order('sid', '<?php echo isset($rsort_arr['sid'])?$rsort_arr['sid']:'';?>')"  style="text-decoration:underline;">客服</th>
    <th>
		<a href="javascript:chang_order('last_login_time', '<?php echo $rsort_arr['last_login_time'];?>')"  style="text-decoration:underline;">最后登录时间</a>
	</th>
	<th><a href="index.php?action=myuser&h=<?php echo $_GET['h'];?>&order_tel=1&page=<?php echo isset($_GET['page'])?$_GET['page']:'';?>" style="text-decoration:underline;"> 同号手机数</a></th>
    <th><a href="javascript:chang_order('next_contact_time', '<?php echo $rsort_arr['next_contact_time'];?>')"  style="text-decoration:underline;">下次联系时间</a></th>
	<th>重点会员</th>
	<th>
		<a href="javascript:chang_order('real_lastvisit', '<?php echo $rsort_arr['real_lastvisit'];?>')"  style="text-decoration:underline;">在线状态</a>
    </th>
    <th>操作</th>
	
	<th>上线提醒</th>
	<?php if((isset($destroy_order)?$destroy_order:'') == 'yes') { ?>
		<th>毁单理由</td>
	<?php } ?>
	<th>操作</th>
  </tr>
  <?php foreach((array)@$member_list as $k=>$member) {?>
   <tr id="myuser_<?php echo $member['uid'];?>" >
    <td align="center">
    	<a href="#" class="userinfo" onclick="parent.addTab('查看<?php echo $member['uid'];?>资料','index.php?action=allmember&h=view_info&uid=<?php echo $member['uid'];?>','icon')"><?php echo $member['uid'];?></a>
    </td>
    <td align="left" style="text-indent:30px;"  onmouseover="showinfo(event,<?php echo $member['uid'];?>);"> 
		<?php if($member['gender']==1) { ?><img src="templates/images/w.gif" alt="女" title="女"/>
		<?php } else { ?><img src="templates/images/m.gif" alt="男" title="男"/>
		<?php } ?>
		<a href="#" onclick="parent.addTab('查看<?php echo $member['uid'];?>资料','index.php?action=allmember&h=view_info&uid=<?php echo $member['uid'];?>','icon')"><?php echo $member['nickname'];?></a>
	</td>
    <td align="center"><?php echo date("Y")-$member['birthyear'];?></td>
    <td align="center"><?php echo $GLOBALS['member_level'][$member['s_cid']];?></td>
    <td align="center"><?php echo @$member['mainimg']?"有":"无";?></td>
    <td align="center"><script>userdetail("<?php echo $member['salary'];?>",salary1);</script></td>
    <td align="center"><script>userdetail("<?php echo $member['province'];?>",provice);userdetail("<?php echo $member['city'];?>",city);</script></td>
    <td align="center" class="<?php echo 'c'.date('d',$member['allotdate']);?>"><?php if($member['allotdate']) echo date("Y-m-d H:i",$member['allotdate']);?></td>
    <td align="center">
	<?php if(in_array($GLOBALS['groupid'],$GLOBALS['admin_aftersales_service']) || in_array($GLOBALS['groupid'],$GLOBALS['admin_aftersales_service2'])) { ?>
		<?php if($member['bgtime']) echo date("Y-m-d",$member['bgtime']);?>
		
		<?php if($member['endtime']) echo '到'.date("Y-m-d",$member['endtime']);?>
	<?php } else { ?>
		<?php  if($member['regdate']) echo date("Y-m-d H:i",$member['regdate']);?>
	<?php } ?>
    
    </td>
	<?php if(in_array($GLOBALS['groupid'],$GLOBALS['admin_vip_level_check'])) { ?>
    <td align="center"><?php echo wordwrap($member['source'],10,"<br />",true)?></td>
	<?php } ?>
    <td align="center"><?php echo $member['login_meb'];?></td>
    <td align="center">
    	<a href="index.php?action=myuser&h=<?php echo $h;?>&sid=<?php echo $member['sid'];?>"><?php echo @$GLOBALS['kefu_arr'][$member['sid']];?></a>
    </td>
    <td align="center"><?php echo date('Y-m-d H:i:s',$member['last_login_time']);?></td>
	<td align="center"><a href="#" onclick="parent.addTab('<?php echo $member['telphone'];?>号注册会员','index.php?action=allmember&h=same_telphone&telphone=<?php echo $member['telphone'];?>','icon')">
	</a>
	</td>
     <td align="center"><?php if(!empty($member['next_contact_time'])) echo date('Y-m-d H:i:s',$member['next_contact_time']);?></td>
	 <td align="center"><?php if($member['master_member']==1) { ?>是<?php } else { ?>否<?php } ?></td>
	 <td align="center">
		<?php if((time()-$member['real_lastvisit']<100)) { ?>
					<span style="color:red">在线</span>
				<?php } else { ?>
					<?php if(time()-$member['real_lastvisit']<24*3600) { ?>
						<span style="color:#0F0;">一天内</span>
					<?php } elseif ((time()-$member['real_lastvisit']<7*24*3600)&&(time()-$member['real_lastvisit']>24*3600)) { ?>
						<span style="color:#FF5;">一周内</span>
					<?php } else { ?>
                    	超过一周
					<?php } ?>
				<?php } ?>
	 </td>
    <td align="center">
      <a href="#" onclick="parent.addTab('修改会员<?php echo $member['uid'];?>资料','index.php?action=allmember&h=edit_info&uid=<?php echo $member['uid'];?>')">修改资料</a>
     </td>
	 <td align="center"><input type="checkbox" value="<?php echo $member['uid'];?>" <?php if($member['is_awoke']==1) { ?>checked="checked"<?php } ?>name="is_awoke_<?php echo $member['uid'];?>" /></td>
	<?php if(@$destroy_order=='yes') { ?>
		<td><?php echo $member['info'];?></td>
	<?php } ?>
	 <td align="center">
		<a href="#" onclick="parent.delmyuser(<?php echo $member['uid'];?>)">删除</a>
		<!--<a href="index.php?action=myuser&h=delmyuser&uid=<?php echo $member['uid'];?>" onclick="return confirm('确定删除吗？')">删除</a>-->
        <?php if(in_array($GLOBALS['groupid'],$GLOBALS['admin_aftersales_service']) || in_array($GLOBALS['groupid'],$GLOBALS['admin_aftersales_service2'])) { ?>
        <a href="#" onclick="view_transfer(event,<?php echo $member['uid'];?>);return false;">交接信息</a>
        <?php } ?>     
	 </td>
  </tr>
  <?php }?>
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
      </td>
    </tr>
  </table>
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
<div id="transfer_box" style="display:none">
<span style="cursor:pointer;" onclick="javascript:$('#transfer_box').hide(100)">关闭</span>
<p>内容正在加载...</p>
</div>
</html>
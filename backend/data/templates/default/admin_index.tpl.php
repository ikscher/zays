<!DOCTYPE html >
<html >
<head id="Head1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>真爱一生网后台管理系统</title>
<link href="./templates/css/default.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="./templates/js/themes/default/easyui.css" />
<link rel="stylesheet" type="text/css" href="./templates/js/themes/icon.css" />
<link rel="stylesheet" type="text/css" href="./templates/css/rightbottom.css" />
<script type="text/javascript" src="./../public/system/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="./templates/js/jquery.easyui.js"></script>
<script type="text/javascript" src='./templates/js/outlook.js'></script>
<script type="text/javascript" src="./templates/js/onmousemove_minutes.js"></script>
<!-- <?php if($GLOBALS['ccid']) { ?><script type="text/javascript" src="./templates/js/callcenter.js"></script><?php } ?> -->

</head>
<body class="easyui-layout" style="overflow-y: hidden"  scroll="no">

<div id="start_load" style="position:absolute; z-index:100000; height:2046px;top:0px;left:0px; width:100000px; background:white; text-align:center;"> 
<img src="./templates/images/noscript.gif" alt='抱歉，请开启脚本支持！' />
</div>

<div region="north" split="true" border="false" style="overflow: hidden; height: 30px;background: url(./templates/images/layout-browser-hd-bg.gif) #7f99be repeat-x center 50%;line-height: 20px;color: #fff; font-family: Verdana, 微软雅黑,黑体"> 
	<span style="position:absolute;top:<?php if($GLOBALS['ccid']) { ?>-6px<?php } else { ?>2px<?php } ?>;left:190px;">
		<?php if($GLOBALS['ccid']) { ?>
		<object id='MDCAgent' name='MDCAgent' classid='clsid:9D47A02F-2CD4-4C18-B224-FC8B2894D72D' codebase='MDCAgent.cab#version=1,0,1,7' width="90" height="32"></object>
		<OBJECT id="dbio" name="dbio" classid="clsid:216394DE-07AE-422B-9F2E-A2644BC96B79"></OBJECT>
		<?php } ?>
		
		<?php if($pass == 'yes') { ?>
		<a href="javascript:openChatWin();" style="margin-left:5px;">
			<img src="./templates/images/top_logo.gif" style="border:none;background:#fff;"/>
		</a>
		<?php } ?>
	</span> 
	<span style="float:right; padding-right:20px;" class="head"> 
		<?php if((!empty($chang_user) && !empty($_GET['change_uid'])) || !empty($GLOBALS['_MooCookie']['change_identity'])) { ?> 
			<span style="font-weight:bold;color:red;font-size:14px;"> 您当前登录的用户名是：<?php echo $chang_user['username'];?> , ID是:<?php echo $chang_user['usercode'];?>。 <a href="index.php?action=login&h=logout_change_identify">返回我的身份</a> 
				<span onclick="parent.location.href='index.php'" style="cursor:pointer;font-size:14px;color:#fff;" id="change_identify"></span> 
			</span> 
		<?php } ?>
	
	<!-- <span id="ccc"></span>秒 -->
	当前登录客服：<?php echo $GLOBALS['username'];?>  ID：<?php echo $GLOBALS['usercode'];?>    身份：<?php echo $GLOBALS['groupname'];?> <a href="#" id="loginOut">安全退出</a> <?php if((in_array($GLOBALS['groupid'],$GLOBALS['system_admin']) ||  in_array($GLOBALS['groupid'],$GLOBALS['admin_service_arr']))) { ?><a href='javascript:;' id="lostContact">失连</a> <?php } ?></span> <span style="padding-left:10px; font-size: 16px; font-family:微软雅黑,黑体"> <img src="./templates/images/top_logo.png" width="20" height="20" align="absmiddle" /> 真爱一生网客服管理系统 </span> 
</div>
<div region="south" split="true" style="height:1px;background:#D2E0F2;display:none;">
	<!--<div class="footer">
			<a href="###"><img src="./templates/images/callcenter_ico.gif" width="23" height="22" /></a>
			<a href="###"><img src="./templates/images/callcenter_ico.gif" width="23" height="22" /></a>
		</div>-->
</div>
<!-- 左侧导航内容 -->
<div region="west" split="true" title="导航菜单" style="width:180px;" id="west">
	<div class="easyui-accordion" fit="true" border="false"> </div>
</div>
<!-- 默认首页 -->
<div id="mainPanle" region="center" style="background: #eee; overflow-y:hidden;height:100px;">
	<div id="tabs" class="easyui-tabs"  fit="true" border="false" >
		<div title="欢迎您<?php echo $GLOBALS['username'];?>"  id="home">
			<h1> 欢迎使用真爱一生网客服管理系统，您的用户名是:
				<?php if(!empty($chang_user)) { ?> <?php echo $chang_user['username'];?> <?php } else { ?> <?php echo $GLOBALS['username'];?> <?php } ?>
				您的登录ID是:
				<?php if(!empty($chang_user)) { ?> <?php echo $chang_user['usercode'];?> <?php } else { ?> <?php echo $GLOBALS['usercode'];?> <?php } ?>
				您的当前身份是:
				<?php if(!empty($chang_user) && isset($chang_user['groupname'])) { ?> <?php echo $chang_user['groupname'];?> <?php } else { ?> <?php if(isset($GLOBALS['groupname'])) { ?><?php echo $GLOBALS['groupname'];?><?php } ?> <?php } ?> </h1>
		</div>
	</div>
</div>

<!-- 删除我的会员 -->
<div id="w" class="easyui-window" title="请输入删除(放弃)理由" collapsible="false" minimizable="false"
	maximizable="false" icon="icon-save"  style="width:300px;height:150px;padding:5px;background:#fafafa;">
	<textarea style="width:260px;height:80px;" name="del_myuser_desc" id="del_myuser_desc"></textarea>
	<div style="margin-top:2px;width:260px;margin-left:10px;">
		<input type="hidden" value="" name="del_myuser_uid" id="del_myuser_uid" />
		<a id="btnEp" class="easyui-linkbutton" icon="icon-ok" href="javascript:void(0)" >确定</a>
		<a id="btnCancel" class="easyui-linkbutton" icon="icon-cancel" href="javascript:void(0)">取消</a>
	</div>
</div>
	
<!-- 右击菜单 -->
<div id="mm" class="easyui-menu" style="width:150px;">
	<div id="mm-tabclose">关闭</div>
	<div id="mm-tabcloseall">全部关闭</div>
	<!--<div id="mm-tabcloseother">除此之外全部关闭</div>-->
	<div class="menu-sep"></div>
	<div id="mm-tabcloseright">当前页右侧全部关闭</div>
	<div id="mm-tabcloseleft">当前页左侧全部关闭</div>
</div>
<!-- 右下角 -->

<?php if(!in_array($GLOBALS['adminid'],$GLOBALS['userWarning'])) { ?>

<div class="online clearfix" id="imonline" style="display:none;">
	<div class="chat-window" id="msg">
		<div class="chat-head" style="cursor:pointer;">
			<div class="head-name">备注信息</div>
			<div class="head-btn"><a class="minimize" style="cursor:pointer;"></a></div>
		</div>
		<div class="chat-conv">
			<!-- <p>您有<a href="javascript:;">0条新消息</a></p> -->
		</div>
	</div>
	<div class="tixingBox" onclick="">最近备注</div>
	<input id="sid" type="hidden" value="<?php echo $GLOBALS['adminid'];?>" />
	<div class="notimeBox" onclick="">备注回电</div>
	<div class="jiluBox" onclick="">所有备注
		<div style="clear:both;"></div>
	</div>
</div>

<?php } ?>

<script type="text/javascript" src="./templates/js/jquery.floatDiv.js"></script>
<script type="text/javascript"src="./templates/js/rightbottom.js"></script>
<div id="congratulate_remark" style="display:none;z-index:999999999999999999;cursor:pointer;">
	<div style="position:relative;">
		<div style="position:absolute;top:50px;left:130px;font-weight:bold;font-size:25px;color:red;" id="congratulate_sid"></div>
		<img src="./templates/images/2.gif"  alt="fasdfasdf" /> </div>
</div>
<script type="text/javascript">
  
	 var _menus = {"menus":
					[
						<?php echo $str;?>
					]
				  };
       
	$(function() {
		$('#loginOut').click(function() {
			$.messager.confirm('系统提示', '您确定要退出本次登录吗?', function(r) {
				if (r) {
					location.href = 'index.php?action=login&h=logout';
				}
			});
		})
		
		//三分钟没操作提醒
		$("body").mousemove(function(){
			mousemove_3minutes_remark();
		})
		
		
		
		//删除我的会员-提示窗--开始start
		openDelMyuser();
		closeDelMyuser();
		$('#btnEp').click(function(uid) {
			var uid = $("#del_myuser_uid").val();
			$('#btnEp').hide();
			//document.getElementById('#btnEp').style.display= 'none';

			var del_myuser_desc = $("#del_myuser_desc").val();
			if(uid==''){
				msgShow('系统提示', '您删除的用户不存在！', 'warning');
				$('#btnEp').show();
                return false;
			}
			if ($.trim(del_myuser_desc) == '') {
                msgShow('系统提示', '请输入删除理由！', 'warning');
				$('#btnEp').show();
                return false;
            }
			$.post('myuser_ajax.php?n=delmyuser&uid='+uid+'&deldesc='+del_myuser_desc, function(msg) {
			$('#btnEp').show();
               if(msg == 'ok'){
					msgShow('系统提示', '删除成功', 'info');
					$("#del_myuser_desc").val('')
					closeDelMyuser();
					iframe_body.document.getElementById("myuser_"+uid).style.display = 'none';
			   }
            })
			
        })
		$('#btnCancel').click(function(){
			closeDelMyuser();
		})
		//结束end
		
		//note 登录CallCenter
		<!-- <?php if($GLOBALS['ccid']) { ?> AgentLogin(<?php echo $GLOBALS['ccid'];?>,1); <?php } ?> -->
	});
	
	
	////////////////////////////////删除我的会员-提示窗////////////////////////////////////
	//关闭删除提示框
	function closeDelMyuser() {
		$('#w').window('close');
	}
		
	 //设置登录窗口
	function openDelMyuser() {
		$('#w').window({
			title: '请输入删除(放弃)理由',
			width: 300,
			modal: true,
			shadow: true,
			closed: true,
			height: 160,
			resizable:false
		});
	}

	//删除我的会员		
	function delmyuser(uid){
		$("#del_myuser_uid").val(uid);
		$('#w').window('open');
	}
		
	

	//三分钟没操作提醒
	time_3minutes = 0;
	var timerId = 0;
	function mousemove_3minutes_remark(){
		time_3minutes = 0;
		if(timerId == 0) timerId = setInterval('mousemove_3minutes_count()',180000);
	}
	//提醒计时
	function mousemove_3minutes_count(){
		time_3minutes++;
		if(time_3minutes>=1800){
			var ajaxUrl = "ajax.php?n=nooperation_remark&adminid=<?php echo $GLOBALS['adminid'];?>&sec="+time_3minutes;
			$.get(ajaxUrl,function(str){
					clearInterval(timerId);
					timerId = 0;
			})
			
		}
		//$("#ccc").html(time_3minutes);		
	}
	//timerId = setInterval('mousemove_3minutes_count()',1000);

	//note 每五分钟执行一次，检查当前客服所管辖会员的下次联系时间是否接近
	function next_contact_alert(){
		var url = "ajax.php?n=check_next_contact";
		$.get(url)
	}
	next_contact_alert();
	setInterval("next_contact_alert()",300000);

	var i=0
	function openChatWin(){
		i++;
		window.open('index.php?action=chat',i,'width=520,height=480,status=no,location=no');	
	}

		
	
	$('#lostContact').click(function(){
		$.ajax({
			url:'ajax.php?n=lostContact',
			type:'get',
			dataType:'text',
			success:function(){
				location.href=location.href;
			}
		});
	});
    

</script>
</body>
</html>
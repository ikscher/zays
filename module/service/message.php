<?php
/*
 * Created on 8/14/2009
 *
 * Tianyong
 *
 * module/service/message.php
 */
//mark 己改  by chuzx
/***************************************一些模板用的函数******************************/
//note 一般会员来信内容读取
function member_email_content($contentid) {
	global $_MooClass,$dbTablePre,$userid,$user_arr;
	
	//如果是本站注册会员
	if($user_arr['usertype'] == 1){
			$m_level = get_userrank($userid);//会员等级
			if($m_level != 1){ //不是高级会员
				if($user_arr['gender'] == 0){//男性
					if(!checkIsMobileCertical($userid)){ //没有通过了手机验证
						MooMessage('您只有先通过手机短信验证才能读取电子邮件,现在就去认证吧','index.php?n=myaccount&h=index2','03');
					}else{
						if(!checkIsOver($userid)){ //过了试用期
							$sql = "select count(*) as c from " . $dbTablePre . "services where s_status = '1'  and s_uid = " . $userid . " and read_time = '" . date("Y-m-d") . "'";
							$arr = $_MooClass['MooMySQL']->getOne($sql,true);
							$today_read_count = $arr['c'];
							if($today_read_count > 5){
								MooMessage('对不起，您不是钻石或高级会员，每天只可以看5条电子邮件!','javascript:history.go(-1)','04');
							}
						}
					}
				}else{	//女性
					if(!checkIsOver($userid)){ //过了试用期
						if(!checkIsMobileCertical($userid)){ //没有通过了手机验证
							MooMessage('您只有先通过手机短信验证才能读取电子邮件,现在就去认证吧','index.php?n=myaccount&h=index2','03');
						}
					}
				}
			}
	}

	//note 获得上一封，下一封邮件
//	$advance_member_arr = $_MooClass['MooMySQL']->getAll("SELECT s_id FROM {$dbTablePre}services WHERE s_uid = '$userid' AND  flag = '1' and s_uid_del='0' order by s_time desc ",true);
//	
//	
//	$total = count($advance_member_arr);
//	foreach($advance_member_arr as $k => $v) {
//		if($advance_member_arr[$k]['s_id'] == $contentid) {
//			$up = ($k - 1) <= 0 ? '0' : ($k - 1);
//			$next = ($k + 1) >= $total ? ($total- 1) : ($k+1);
//			$upid = $advance_member_arr[$up]['s_id'];
//			$nextid = $advance_member_arr[$next]['s_id'];
//			//地址栏参数是否正确
//			$flg = true;
//		}
//	}
	//重新实现上一封，下一封邮件
	$allmessg_arr = $_MooClass['MooMySQL']->getOne("SELECT s_id,s_time FROM {$dbTablePre}services WHERE s_uid = '$userid' and flag = '1' and s_uid_del='0' and s_id=$contentid");
	if ($allmessg_arr){
		$where = "s_uid = '$userid' and flag = '1' and s_uid_del='0'";
		list($upid,$nextid) = getUpNextId($allmessg_arr['s_id'],$where,$allmessg_arr['s_time']);
		$flg = true;
	}
	//地址栏参数是否正确
	if(!$flg){
		MooMessage('您浏览的不是您的邮件，返回','javascript:history.go(-1)','04');
	}
	
	//note 删除记录
	$delmessagecontent = MooGetGPC('delmessagecontent','string');
	$messageid = MooGetGPC('messageid','integer');
	if($delmessagecontent && $messageid) {
		$time = time();
		//$_MooClass['MooMySQL']->query("DELETE FROM {$dbTablePre}services WHERE s_id = '$messageid' and s_uid = '$userid'");
		$_MooClass['MooMySQL']->query("update {$dbTablePre}services set s_uid_del='1',s_uid_deltime='{$time}' WHERE s_id = '$messageid' and s_uid = '$userid'");
		MooMessage("删除消息成功",'index.php?n=service&h=message&t=membermessage','05');
	}
	
	//note 显示查询的内容	
	$memail = $_MooClass['MooMySQL']->getOne("SELECT * FROM {$dbTablePre}services WHERE s_id = '$contentid' and flag = '1' and s_uid_del='0'",true);
	

	//note 更新已经阅读状态
	if(!$memail['s_status']){
		$_MooClass['MooMySQL']->query("UPDATE {$dbTablePre}services SET s_status = '1',read_time='".date('Y-m-d')."' WHERE s_id = '$contentid'",true);
	}
	//note 发件人的昵称或ID
	$from_name = MooMembersData($memail['s_fromid']);
	$fromname = $from_name['nickname']?$from_name['nickname']:'ID '.$from_name['uid'];
	$uname = $user_arr['nickname']?$user_arr['nickname']:'ID:'.$user_arr['uid'];
	
	require MooTemplate('public/service_email_membercontent', 'module');
}

//note 真爱一生会员来信内容
function hl_member_email_content($contentid) {
	global $_MooClass,$dbTablePre,$userid,$user_arr;
	
	//note 获得上一封，下一封邮件
//	$advance_member_arr = $_MooClass['MooMySQL']->getAll("SELECT s_id FROM {$dbTablePre}services WHERE s_uid = '$userid' AND s_cid = '3' and s_uid_del='0' order by s_time desc ");
//	
//	$total = count($advance_member_arr);
//	foreach($advance_member_arr as $k => $v) {
//		if($advance_member_arr[$k]['s_id'] == $contentid) {
//			$up = ($k - 1) <= 0 ? '0' : ($k - 1);
//			$next = ($k + 1) >= $total ? ($total- 1) : ($k+1);
//			$upid = $advance_member_arr[$up]['s_id'];
//			$nextid = $advance_member_arr[$next]['s_id'];
//			//地址栏参数是否正确
//			$flg = true;
//		}
//	}
	//重新实现上一封，下一封邮件
	$allmessg_arr = $_MooClass['MooMySQL']->getOne("SELECT s_id,s_time FROM {$dbTablePre}services WHERE s_uid = '$userid' and s_cid='3' and s_uid_del='0' and s_id=$contentid");
	if ($allmessg_arr){
		$where = "s_uid = '$userid' and s_cid='3' and s_uid_del='0'";
		list($upid,$nextid) = getUpNextId($allmessg_arr['s_id'],$where,$allmessg_arr['s_time']);
		$flg = true;
	}
	//地址栏参数是否正确
	if(!$flg){
		MooMessage('您浏览的不是您的邮件，返回','javascript:history.go(-1)','04');
	}
	
	//note 删除记录
	$delmessagecontent = MooGetGPC('delmessagecontent','string');
	$messageid = MooGetGPC('messageid','integer');
	if($delmessagecontent && $messageid) {
		$_MooClass['MooMySQL']->query("DELETE FROM {$dbTablePre}services WHERE s_id = '$messageid' and s_uid = '$userid'");
		MooMessage("删除消息成功",'index.php?n=service&h=message&t=hlmessage','02');
	}
	
	//note 显示查询的内容	
	$memail = $_MooClass['MooMySQL']->getOne("SELECT * FROM {$dbTablePre}services WHERE s_id = '$contentid' and s_uid_del='0'");
	//note 更新已经阅读状态
	if(!$memail['s_status']){
		$_MooClass['MooMySQL']->query("UPDATE {$dbTablePre}services SET s_status = '1',read_time='".date('Y-m-d')."' WHERE s_id = '$contentid'");
	}
	//note 当前会员昵称
	$fromname = '真爱一生';
	$uname = $user_arr['nickname']?$user_arr['nickname']:'ID:'.$user_arr['uid'];
	require MooTemplate('public/service_email_hlmembercontent', 'module');
}

//note 我发出去的邮件
function send_member_email_content($contentid) {
	global $_MooClass,$dbTablePre,$userid,$user_arr;
	
	//note 获得上一封，下一封邮件
//	$advance_member_arr = $_MooClass['MooMySQL']->getAll("SELECT s_id FROM {$dbTablePre}services WHERE s_fromid = '$userid' and s_fromid_del='0' order by s_time desc");
//	
//	$total = count($advance_member_arr);
//	foreach($advance_member_arr as $k => $v) {
//		if($advance_member_arr[$k]['s_id'] == $contentid) {
//			$up = ($k - 1) <= 0 ? '0' : ($k - 1);
//			$next = ($k + 1) >= $total ? ($total- 1) : ($k+1);
//			$upid = $advance_member_arr[$up]['s_id'];
//			$nextid = $advance_member_arr[$next]['s_id'];
//			//地址栏参数是否正确
//			$flg = true;
//		}
//	}
	//重新实现上一封，下一封邮件
	$allmessg_arr = $_MooClass['MooMySQL']->getOne("SELECT s_id,s_time FROM {$dbTablePre}services WHERE s_fromid = '$userid' and s_fromid_del='0' and s_id=$contentid");
	if ($allmessg_arr){
		$where = "s_fromid = '$userid' and s_fromid_del='0'";
		list($upid,$nextid) = getUpNextId($allmessg_arr['s_id'],$where,$allmessg_arr['s_time']);
		$flg = true;
	}
	//地址栏参数是否正确
	if(!$flg){
		MooMessage('您浏览的不是您的邮件，返回','javascript:history.go(-1)','04');
	}
	
	//note 删除记录
	$delmessagecontent = MooGetGPC('delmessagecontent','string');
	$messageid = MooGetGPC('messageid','integer');
	if($delmessagecontent && $messageid) {
		$time = time();
		//$_MooClass['MooMySQL']->query("DELETE FROM {$dbTablePre}services WHERE s_id = '$messageid' and s_fromid = '$userid'");
		$_MooClass['MooMySQL']->query("update {$dbTablePre}services set s_fromid_del='1',s_fromid_deltime='{$time}' WHERE s_id = '$messageid' and s_fromid = '$userid'");
		MooMessage("删除消息成功",'index.php?n=service&h=message&t=sendmessage','05');
	}
	
	//note 显示查询的内容	
	$memail = $_MooClass['MooMySQL']->getOne("SELECT * FROM {$dbTablePre}services WHERE s_id = '$contentid' and s_fromid_del='0'");
	
	//note 更新已经阅读状态
	//note 会员自己发的消息不可更改状态
	//$_MooClass['MooMySQL']->query("UPDATE {$dbTablePre}services SET s_status = '1',read_time='".date('Y-m-d')."' WHERE s_id = '$contentid'");
	
	//note 收件人的昵称或ID
	$sendname = MooGetGPC('sendname','string');
	if(!$sendname){
		$send_name = $_MooClass['MooMySQL']->getOne("select uid,nickname from {$dbTablePre}members_search where uid='".$memail['s_uid']."'");
		$sendname = $send_name['nickname']?$send_name['nickname']:'ID '.$send_name['uid'];
	}
		//note 发件人的昵称或ID
	$from_name = MooMembersData($memail['s_fromid']);
	$fromname = $from_name['nickname']?$from_name['nickname']:'ID '.$from_name['uid'];
	$uname = $user_arr['nickname']?$user_arr['nickname']:'ID:'.$user_arr['uid'];
	require MooTemplate('public/service_email_sendmembercontent', 'module');
}

//note 会员来信列表(普通会员+高级会员)
function member_email_list(){
	global $_MooClass,$dbTablePre,$userid,$pagesize,$user_arr;
	
	
	//note 获取删除提交的变量
	$memail     = array();
	$delmessage = MooGetGPC('delmessage','string');
	$delmessageid = MooGetGPC('delmessageid','array');
	
	//note 删除提交的数据
	if($delmessage && count($delmessageid)) {
		$sidArr = implode(',',$delmessageid);
		$time = time();
		//$_MooClass['MooMySQL']->query("DELETE FROM {$dbTablePre}services WHERE s_id in($sidArr) and s_uid = '$userid'");
		$_MooClass['MooMySQL']->query("update {$dbTablePre}services set s_uid_del='1',s_uid_deltime='{$time}' WHERE s_id in($sidArr) and s_uid = '$userid'");
		MooMessage("删除消息成功",'index.php?n=service&h=message&t=membermessage','05');
	}
	
	//note 如果提交的数据不存在，或者是提交的时候没有选中
	if($delmessage && !count($delmessageid)) {
		MooMessage('请选择要删除的邮件','index.php?n=service&h=message&t=membermessage','01');
		exit;
	}
	
	//note 获得当前url
	$currenturl = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"]; 
	$currenturl = preg_replace("/(&page=\d+)/","",$currenturl);
	$currenturl2 = preg_replace("/7651/","7652",$currenturl);
	
	//note 获得第几页
	$page = empty($_GET['page']) ? 1:intval($_GET['page']);
		
	//note limit查询开始位置
	$start = ($page - 1) * $pagesize;
		
	//$query = $_MooClass['MooMySQL']->query("SELECT * FROM {$dbTablePre}services WHERE s_uid = '$userid' AND s_cid = '2' and s_uid_del='0'");
	//$total = $_MooClass['MooMySQL']->numRows($query);
	$sql = "SELECT count(*) as c FROM {$dbTablePre}services WHERE s_uid = '$userid' AND s_cid IN(0,1,2) and flag = '1' and s_uid_del='0'";
	$ret_count = $_MooClass['MooMySQL']->getOne($sql,true);
	$total = $ret_count['c'];
	
	if($total){
		$memail = $_MooClass['MooMySQL']->getAll("SELECT * FROM {$dbTablePre}services WHERE s_uid = '$userid' AND s_cid IN(0,1,2) and flag = '1' and s_uid_del='0' order by s_time desc LIMIT $start,$pagesize");
	}
	require MooTemplate('public/service_email_memberlist', 'module');
}

//note 真爱一生来信列表
function hl_email_list(){
	global $_MooClass,$dbTablePre,$userid,$pagesize,$user_arr;
	//note 获取删除提交的变量
	$delmessage = MooGetGPC('delmessage','string');
	$delmessageid = MooGetGPC('delmessageid','array');
	
	//note 删除提交的数据
	if($delmessage && count($delmessageid)) {
		$sidArr = implode(',',$delmessageid);
		$_MooClass['MooMySQL']->query("DELETE FROM {$dbTablePre}services WHERE s_id in($sidArr) and s_uid = '$userid'");
		MooMessage("删除消息成功",'index.php?n=service&h=message&t=hlmessage','05');
	}
	
	//note 如果提交的数据不存在，或者是提交的时候没有选中
	if($delmessage && !count($delmessageid)) {
		MooMessage('请选择要删除的邮件','index.php?n=service&h=message&t=hlmessage','01');
		exit;
	}
	
	//note 获得当前url
	$currenturl = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"]; 
	$currenturl = preg_replace("/(&page=\d+)/","",$currenturl);
	$currenturl2 = preg_replace("/7651/","7652",$currenturl);
		
	//note 获得第几页
	$page = empty($_GET['page']) ? 1:intval($_GET['page']);
		
	//note limit查询开始位置
	$start = ($page - 1) * $pagesize;
	
//	$query = $_MooClass['MooMySQL']->query("SELECT * FROM {$dbTablePre}services WHERE s_uid = '$userid' AND s_cid = '3' and s_uid_del='0'");
//	$total = $_MooClass['MooMySQL']->numRows($query);
    $sql = "SELECT count(*) as c FROM {$dbTablePre}services WHERE s_uid = '$userid' AND s_cid = '3' and s_uid_del='0'";	
    $ret_count = $_MooClass['MooMySQL']->getOne($sql);
    $total = $ret_count['c'];
	$memail = array();
	if($total){
		$memail = $_MooClass['MooMySQL']->getAll("SELECT * FROM {$dbTablePre}services WHERE s_uid = '$userid' AND s_cid = '3' and s_uid_del='0' order by s_time desc LIMIT $start,$pagesize");
	}
	require MooTemplate('public/service_email_hlmemberlist', 'module');
}

//note 我发出去的邮件
function send_email_list() {
	global $_MooClass,$dbTablePre,$userid,$pagesize,$user_arr;
	//note 获取删除提交的变量
	$delmessage = MooGetGPC('delmessage','string');
	$delmessageid = MooGetGPC('delmessageid','array');
	
	if($delmessage && count($delmessageid)) {
		$time = time();
		$sidArr = implode(',',$delmessageid);
		//$_MooClass['MooMySQL']->query("DELETE FROM {$dbTablePre}services WHERE s_id in($sidArr) and s_fromid = '$userid'");
		$_MooClass['MooMySQL']->query("update {$dbTablePre}services set s_fromid_del='1',s_fromid_deltime='{$time}' WHERE s_id in($sidArr) and s_fromid = '$userid'");
		MooMessage("删除邮件成功",'index.php?n=service&h=message&t=sendmessage','05');
	}
	
	//note 如果提交的数据不存在，或者是提交的时候没有选中
	if($delmessage && !count($delmessageid)) {
		MooMessage('请选择要删除的邮件','index.php?n=service&h=message&t=sendmessage','01');
		exit;
	}
	
	//note 获得当前url
	$currenturl = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"]; 
	$currenturl = preg_replace("/7651/","7652",$currenturl);
	$currenturl2 = preg_replace("/(&page=\d+)/","",$currenturl);
	
		
	//note 获得第几页
	$page = empty($_GET['page']) ? 1:intval($_GET['page']);
	//note limit查询开始位置
	$start = ($page - 1) * $pagesize;

	$sql = "SELECT count(*) as c FROM {$dbTablePre}services WHERE s_fromid = '$userid' and s_fromid_del='0'";
	$ret_count = $_MooClass['MooMySQL']->getOne($sql);
	$total = $ret_count['c'];
	$memail = array();
	if($total){
		$memail = $_MooClass['MooMySQL']->getAll("SELECT * FROM {$dbTablePre}services WHERE s_fromid = '$userid' and s_fromid_del='0' order by s_time desc LIMIT $start,$pagesize");
	}
	require MooTemplate('public/service_email_sendlist', 'module');
}

//note 发送/回复 消息
function to_send_message(){
	global $_MooClass,$dbTablePre,$userid,$pagesize,$user_arr,$timestamp,$serverid;
	//print_r($_POST);
	//note s_cid 发送者的权限（回复时才有）
	$s_cid = MooGetGPC('s_cid','integer','P');
	//echo $s_cid;exit;
	
	//收件人id
	$sid = MooGetGPC('s_id','integer','P');
	//发件人id
	$mid = MooGetGPC('m_id','integer','P');
	
	///echo $sid;exit;
	
	$s_title = MooGetGPC('s_title','string','P');
	$s_title = rtrim($s_title);
	//对主题字数限制
	$s_title = MooStrReplace(MooCutstr($s_title, 30, $dot = ''));
	$s_content = MooGetGPC('s_content','string','P');
	//note 特殊字符    
	$s_content = rtrim(MooStrReplace(safeFilter($s_content)));
	$send_mymessage = MooGetGPC('send_mymessage','integer','P');
	$message_back = MooGetGPC('message_back','integer','P');
	//note 双方屏蔽不给操作
	if(MooGetScreen($mid,$sid)){
		MooMessage('因特殊原因，消息发送失败',"index.php?n=service&h=rose&t=getmorerose",'01');
		exit;
	}
	if($mid && $sid && $s_title && $s_content){
		$m_level = get_userrank($userid);//会员等级
		if($m_level == 2 && $user_arr['usertype']!=3 ){ //普通会员需要审核（除采集会员之外）
			//if($user_arr['gender'] == 0){	//如果是男方发的，需要审核,0表示
				$send_status = $_MooClass['MooMySQL']->query("insert into {$dbTablePre}services (s_cid,s_uid,s_fromid,s_title,s_content,s_time,sid,flag) values ('{$s_cid}','{$sid}','{$mid}','{$s_title}','{$s_content}',".time().",'{$user_arr['sid']}','0')");			
		}else{ 
			$send_status = $_MooClass['MooMySQL']->query("insert into {$dbTablePre}services (s_cid,s_uid,s_fromid,s_title,s_content,s_time,sid,flag) values ('{$s_cid}','{$sid}','{$mid}','{$s_title}','{$s_content}',".time().",'{$user_arr['sid']}','1')");
		}
		
			
	   //发送短信和邮件
	   include_once("./module/crontab/crontab_config.php");
	   $res= MooMembersData($sid);
//	   $send_user_info = $_MooClass['MooMySQL']->getAll("select * from `{$dbTablePre}members_search` a  left join  {$dbTablePre}members_choice b  on a.uid=b.uid  where a.uid = '$userid'");
		$send_user_info = array_merge(MooGetData("members_choice", 'uid',$userid),MooMembersData($userid));
//	   $send_user_info = $send_user_info[0];
	   //头像路径
	   $path = thumbImgPath(2,$send_user_info['pic_date'],$send_user_info['pic_name'],$send_user_info['gender']);
	   if(file_exists($path)){
	   	 $img_path = $path;
	   }else{
	   		if($send_user_info['gender'] == 1){
	   			$img_path = "/public/images/service_nopic_woman.gif";
	   		}else{
	   			$img_path = "/public/images/service_nopic_man.gif";
	   		}
	   }
	   $send_username = $send_user_info['nickname'] ?  $send_user_info['nickname'] : $send_user_info['uid'];//发送者用户名
	   $send_user_grade = $send_user_info['gender'] == 1 ? "女" : "男"; //发送者性别
	   $province = $send_user_info['province'] ? $provice_list[$send_user_info['province']] : '';//省
	   $city = $send_user_info['city'] ? $city_list[$send_user_info['city']] : ''; //市
	   $height = $send_user_info['height'] ? $height_list[$send_user_info['height']] : "未知"; //身高
	   ob_start();
	   require_once MooTemplate('public/mail_space_messagetpl', 'module'); //模板
	   $body = ob_get_clean();
	  
	   MooSendMail($res['username'],"真爱一生网系统温馨提示",$body,"",false,$sid);
	  
	  
	   $week_time = 24*3600*7;//一周时间秒数
	   $interval_time = $timestamp - $user_arr['last_login_time'];//当前时间-最后登录时间
	   $date1 = date("Y-m-d",strtotime("last Monday"));
	   $date2 = date("Y-m-d",strtotime("Sunday"));
	   
	   if($interval_time > $week_time){//不活跃用户每周发一条短信
	   		$_MooClass['MooMySQL']->query("INSERT INTO {$dbTablePre}today_send SET uid = '".$sid."', sid = '".$userid."',phone = '".$res['telphone']."',sendtime = '".date("Y-m-d")."'" );
	   		$cos = $_MooClass['MooMySQL']->getOne("select count(*) as c from {$dbTablePre}today_send where uid='$sid' and sendtime>='$date1' and sendtime<='$date2'",true);
	    	if($cos['c']<=1){
			  //fanglin暂时屏蔽
			  Push_message_intab($sid,$res['telphone'],"邮件","用户ID：".$userid.",".$send_user_grade.",已给您发送电子邮件,请登录www.zhenaiyisheng.cc查看！4008787920【真爱一生网】",$userid);
		   }
	   }else{ //活跃用户每天一条
		     //每天该用户超过5条信息不发送短信
		   $cos = $_MooClass['MooMySQL']->getOne("select count(*) as c from {$dbTablePre}today_send where uid='$sid' and sendtime='".date("Y-m-d")."'",true);
		   if($cos['c']<5){
		   	  $_MooClass['MooMySQL']->query("INSERT INTO {$dbTablePre}today_send SET uid = '".$sid."', sid = '".$userid."',phone = '".$res['telphone']."',sendtime = '".date("Y-m-d")."'" );
			  //fanglin暂时屏蔽
			  Push_message_intab($sid,$res['telphone'],'邮件',"用户ID：".$userid.",".$send_user_grade.",已给您发送电子邮件,请登录www.zhenaiyisheng.cc！4008787920【真爱一生网】",$userid);
		   }
	   }
	

		//note 发送资料给接收方
		if($send_mymessage){
			
		}
		//note 备份邮件操作
		if($message_back){
			//note 发送MAIL时所需信息
			$user = $_MooClass['MooMySQL']->getOne("select username,nickname from {$dbTablePre}members_search where uid='$mid'");
			//note 发送MAIL时收信人的邮箱
			$ToAddress = $user['username'];
			//note 发送MAIL时主题
			$ToSubject = '提示：您在真爱一生网所发的消息备份';
			//note 发送MAIL时Body内容所需信息
			$username = $user['nickname'];
			//note 发送MAIL时Body内容所需信息
			$toname = $_MooClass['MooMySQL']->getOne("select nickname from {$dbTablePre}members_search where uid='$sid'");
			if($username){
				$ToBody = $username;
			}else{
				$ToBody = 'ID号为'.$mid.'的会员<br>';
			}
			if($toname['nickname']){
				$ToBody .= '：您好，您于'.date('Y-m-d H:i:s',time()).'，在真爱一生网发送电子邮件给'.$toname['nickname'].'，内容如下：<br>';
			}else{			
				$ToBody .= '：您好，您于'.date('Y-m-d H:i:s',time()).'，在真爱一生网发送电子邮件给ID号为'.$sid.'的会员，内容如下：<br>';
			}
			if($username){
				$ToBody .= '&nbsp;&nbsp;&nbsp;&nbsp;发件人：'.$username.'<br>';
			}else{
				$ToBody .= '&nbsp;&nbsp;&nbsp;&nbsp;发件人：ID号为'.$mid.'<br>';
			}
			if($toname['nickname']){
				$ToBody .= '&nbsp;&nbsp;&nbsp;&nbsp;收件人：'.$toname['nickname'].'<br>';
			}else{
				$ToBody .= '&nbsp;&nbsp;&nbsp;&nbsp;收件人：ID号为'.$sid.'<br>';
			}
			$ToBody .= '&nbsp;&nbsp;&nbsp;&nbsp;主题：'.$s_title.'<br>';
			$ToBody .= '&nbsp;&nbsp;&nbsp;&nbsp;正文：<br>&nbsp;&nbsp;&nbsp;&nbsp;'.$s_content;
			MooSendMail($ToAddress,$ToSubject,$ToBody,$is_template = true,$sid);
		}
		//添加成功提示信息
		if($send_status){
			/*普通会员对全权会员反馈白名单*/
			if($res['usertype'] == '3' && $user_arr['usertype'] != '3' && !$serverid){
				white_list($res['uid'],$user_arr['uid']);
			}
			/*客服模拟全权记录*/
			if($user_arr['usertype'] == '3' && $serverid && $res['usertype'] != '3'){
				$action = '站内信';
				fulllog($user_arr['uid'],$serverid,$action,$res);
			}
			//提醒所属客服
			$usid = $user_arr['sid'];
			$title = '您的会员 '.$mid.' 向 '.$sid.' 发送了邮件';
			$awoketime = $timestamp+3600;
			$sql_remark = "insert into {$dbTablePre}admin_remark set sid='{$usid}',title='{$title}',content='{$title}',awoketime='{$awoketime}',dateline='{$timestamp}'";
			$res = $_MooClass['MooMySQL']->query($sql_remark);

			if($user_arr['gender'] == 0){ //男方发的
				MooMessage('发送成功,真爱一生审核后对方即可以收到。','index.php?n=service&h=message&t=sendmessage','05');
			}else{
				MooMessage('发送成功','index.php?n=service&h=message&t=sendmessage','05');
			}

		}else{
			MooMessage('发送失败','index.php?n=service&h=message&t=send&sendtoid='.$sid,'03');
		}
	}else{
		MooMessage('数据填写不完整','index.php?n=service&h=message&t=send&sendtoid='.$sid,'02');
	}
}

//note 所有消息
function allmessage_list(){
	global $_MooClass,$dbTablePre,$userid,$pagesize,$user_arr;
	
	//note 获取删除提交的变量
	$delmessage = MooGetGPC('delmessage','string');
	$delmessageid = MooGetGPC('delmessageid','array');
	$allmail = array();
	//note 删除提交的数据
	if($delmessage && count($delmessageid)) {
		$sidArr = implode(',',$delmessageid);
		$time = time();
		//$_MooClass['MooMySQL']->query("DELETE FROM {$dbTablePre}services WHERE s_id in($sidArr) and s_uid = '$userid'");
		$_MooClass['MooMySQL']->query("update {$dbTablePre}services set s_uid_del='1',s_uid_deltime='{$time}' WHERE s_id in($sidArr) and s_uid = '$userid'");
		MooMessage("删除邮件成功",'index.php?n=service&h=message&t=allmessage','05');
	}
	
	//note 如果提交的数据不存在，或者是提交的时候没有选中
	if($delmessage && !count($delmessageid)) {
	   MooMessage("请选择要删除的邮件",'index.php?n=service&h=message&t=allmessage','02');
	}
	
	//note 获得当前url
	$currenturl = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"]; 
	$currenturl = preg_replace("/7651/","7652",$currenturl);
	$currenturl2 = preg_replace("/(&page=\d+)/","",$currenturl);

	//note 获得第几页
	
//	$page = max(1,intval($_GET['page']));
	$page = empty($_GET['page']) ? 1 : intval($_GET['page']);
	//note limit查询开始位置
	$start = ($page - 1) * $pagesize;
	
	
	//note 显示数据
//	$query = $_MooClass['MooMySQL']->query("SELECT * FROM {$dbTablePre}services WHERE s_uid = '$userid' and s_uid_del='0'");
//	$total = $_MooClass['MooMySQL']->numRows($query);
	$ret_count = $_MooClass['MooMySQL']->getOne("SELECT count(s_uid) as c FROM {$dbTablePre}services WHERE s_uid = '$userid' and flag = '1' and s_uid_del='0'",true);
	$total = $ret_count['c'];//2009-11-22日修改(得到总数)
	
	if($total){
		$allmail = $_MooClass['MooMySQL']->getAll("SELECT * FROM {$dbTablePre}services WHERE s_uid = '$userid' and flag = '1' and s_uid_del='0' order by s_time desc LIMIT $start,$pagesize");
	}
	
	require MooTemplate('public/service_myemail_list', 'module');

}
//note 所有消息（详细）
function allmessage_detail($contentid){
	global $_MooClass,$dbTablePre,$userid,$user_arr;
	
	//note 获得上一封，下一封邮件
//	$allmessg_arr = $_MooClass['MooMySQL']->getAll("SELECT s_id FROM {$dbTablePre}services WHERE s_uid = '$userid' and flag = '1' and s_uid_del='0' order by s_time desc");
//	
//	$total = count($allmessg_arr);
//	foreach($allmessg_arr as $k => $v) {
//		if($allmessg_arr[$k]['s_id'] == $contentid) {
//			$up = ($k - 1) <= 0 ? '0' : ($k - 1);
//			$next = ($k + 1) >= $total ? ($total- 1) : ($k+1);
//			$upid = $allmessg_arr[$up]['s_id'];
//			$nextid = $allmessg_arr[$next]['s_id'];
//			//地址栏参数是否正确
//			$flg = true;
//		}
//	}
//	echo $upid,'$',$nextid,'$',$contentid;
//exit;
	//重新实现上一封，下一封邮件
	$allmessg_arr = $_MooClass['MooMySQL']->getOne("SELECT s_id,s_time FROM {$dbTablePre}services WHERE s_uid = '$userid' and flag = '1' and s_uid_del='0' and s_id=$contentid");
	if ($allmessg_arr){
		$where = "s_uid = '$userid' and flag = '1' and s_uid_del='0'";
		list($upid,$nextid) = getUpNextId($allmessg_arr['s_id'],$where,$allmessg_arr['s_time']);
		$flg = true;
	}
	
	//地址栏参数是否正确
	if(!$flg){
		MooMessage('您浏览的不是您的邮件，返回','javascript:history.go(-1)','04');
	}
	
	//note 删除记录
	$delmessagecontent = MooGetGPC('delmessagecontent','string');
	$messageid = MooGetGPC('messageid','integer');
	if($delmessagecontent && $messageid) {
		$time = time();
		//$_MooClass['MooMySQL']->query("DELETE FROM {$dbTablePre}services WHERE s_id = '$messageid' and s_uid = '$userid'");
		$_MooClass['MooMySQL']->query("update {$dbTablePre}services set s_uid_del='1',s_uid_deltime = '{$time}' WHERE s_id = '$messageid' and s_uid = '$userid'");
		MooMessage("删除消息成功",'index.php?n=service&h=message&t=allmessage','05');
	}
	//note 显示查询的内容	
	$memail = $_MooClass['MooMySQL']->getOne("SELECT * FROM {$dbTablePre}services WHERE s_id = '$contentid' and flag = '1' and s_uid_del='0'");
	
	
	//如果是本站注册会员
	if($user_arr['usertype'] == 1 && $memail['s_cid'] != 3){
			$m_level = get_userrank($userid);//会员等级
			if($m_level != 1){ //不是高级会员
				if($user_arr['gender'] == 0){//男性
					if(!checkIsMobileCertical($userid)){ //没有通过了手机验证
						MooMessage('您只有先通过手机短信验证才能读取电子邮件,现在就去认证吧','index.php?n=myaccount&h=index2','03');
					}else{
						if(!checkIsOver($userid)){ //过了试用期
							$sql = "select count(*) as c from " . $dbTablePre . "services where s_status = '1'  and s_uid = " . $userid . " and read_time = '" . date("Y-m-d") . "'";
							$arr = $_MooClass['MooMySQL']->getOne($sql,true);
							$today_read_count = $arr['c'];
							if($today_read_count > 5){
								MooMessage('对不起，您不是钻石或高级会员，每天只可以看5条电子邮件!','javascript:history.go(-1)','03');
							}
						}
					}
				}else{	//女性
					if(!checkIsOver($userid)){ //过了试用期
						if(!checkIsMobileCertical($userid)){ //没有通过了手机验证
							MooMessage('您只有先通过手机短信验证才能读取电子邮件,现在就去认证吧','index.php?n=myaccount&h=index2','03');
						}
					}
				}
			}
	}

	//note 更新已经阅读状态
	if(!$memail['s_status']){
		$_MooClass['MooMySQL']->query("UPDATE {$dbTablePre}services SET s_status = '1',read_time='".date('Y-m-d')."' WHERE s_id = '$contentid'");
	}
	//note 发件人的昵称或ID
	if($memail['s_fromid'] == 0){
		$fromname = '真爱一生网';
		//note 当前会员昵称		
		$uname = $user_arr['nickname']?$user_arr['nickname']:'ID:'.$user_arr['uid'];
	}else{
		$from_name = $_MooClass['MooMySQL']->getOne("select uid,nickname from {$dbTablePre}members_search where uid='".$memail['s_fromid']."'");
		$fromname = $from_name['nickname']?$from_name['nickname']:'ID '.$from_name['uid'];
		$uname = $user_arr['nickname']?$user_arr['nickname']:'ID:'.$user_arr['uid'];
	}
	require MooTemplate('public/service_email_allmessagecontent', 'module');
}

//note 会员回复真爱一生来信
function to_send_hn_message(){
	global $_MooClass,$dbTablePre,$userid,$user_arr;
	$s_title = MooGetGPC('s_title','string','P');
	$s_title = rtrim($s_title);
	//对主题字数限制
	$s_title = MooCutstr($s_title, 30, $dot = '');
	$s_content = MooGetGPC('s_content','string','P');
	//note 特殊字符
	$s_content = rtrim($s_content);
	
	if($s_title && $s_content){
		//note 客服ID
		$user = $_MooClass['MooMySQL']->getOne("select uid,sid,telphone from {$dbTablePre}members_search where uid='$userid'");
		if(empty($user['sid'])){
			$sid = 20;
		}else{
			$sid = $user['sid'];
		}
		//note 添回复的消息、添加发送的消息
		$remark_title = "{$userid}会员给给您发消息了，请速查看。";
		$awoketime = time()+600;
		$sql = "INSERT INTO `{$dbTablePre}admin_remark` SET sid='{$sid}',title='{$remark_title}',content='$s_content',awoketime='{$awoketime}',dateline='{$GLOBALS['timestamp']}'";
		$send_status = $_MooClass['MooMySQL']->query($sql);
		//邮件回复提醒
		$sql = "INSERT INTO {$GLOBALS['dbTablePre']}services(s_cid,s_uid,s_fromid,s_title,s_content,s_time,is_server,sid,flag)
				VALUES(3,{$userid},'{$sid}','真爱一生消息','您好！您的消息红娘已经收到，我们将在一个工作日内解决！4008787920','{$GLOBALS['timestamp']}','1','{$sid}','1')";
		$ret = $GLOBALS['_MooClass']['MooMySQL']->query($sql);
		//短信回复提醒
		Push_message_intab($userid,$user['telphone'],"邮件回复","您的消息红娘已经收到，我们将在一个工作日内解决。4008787920！","system");

		//添加成功提示信息
		if($send_status){
			MooMessage('发送成功','javascript:history.go(-2)','05');
		}else{
			MooMessage('发送失败','javascript:history.go(-1)','03');
		}
	}else{
		MooMessage('数据填写不完整','javascript:history.go(-1)','03');
	}
}
/**
 * 
 * 返回邮件的上一封，下一封邮件ID
 * @param unknown_type $s_id 本邮件id
 * @param unknown_type $where 部分查询条件
 * @param unknown_type $s_time 邮件发送时间
 * return array($upid,$nextid)
 */
function getUpNextId($s_id,$where,$s_time){
	global $dbTablePre,$_MooClass;
	$return = array();
	$upid   = $_MooClass['MooMySQL']->getOne("SELECT s_id FROM {$dbTablePre}services WHERE $where and s_time >$s_time order by s_time asc limit 1");
	$nextid = $_MooClass['MooMySQL']->getOne("SELECT s_id FROM {$dbTablePre}services WHERE $where and s_time <$s_time order by s_time desc limit 1");
	$return[] = $upid ? $upid['s_id'] : $s_id;
	$return[] = $nextid ? $nextid['s_id'] : $s_id;
	return $return;
}

/***************************************控制层****************************************/ 
//require_once './../ework/include/config.php';
$_GET['t'] = empty($_GET['t']) ? '' : $_GET['t'];
switch ($_GET['t']) {
	//note 会员来信列表
	case "supermembermessage" :
		advance_member_email_list();
		break;
		
	case "membermessage" :
		member_email_list();	
		break;

	case "hlmessage" :
		hl_email_list();
		break;

	case "sendmessage":
		send_email_list();
		break;
				
	//note 一般会员来信详细内容
	case "detailmembermessage" :
		$contentid = MooGetGPC('contentid','integer');
		member_email_content($contentid);
		break;
		
	//note 真爱一生来信详细内容
	case "detailhlmembermessage" :
		$contentid = MooGetGPC('contentid','integer');
		hl_member_email_content($contentid);
		break;

	//note 我发的信息
	case "detailsendmessage" :
		$contentid = MooGetGPC('contentid','integer');
		send_member_email_content($contentid);
		break;
		
	//note 发信息****************************
	case "send" :
		$contentid = MooGetGPC('sendtoid','string','G');		
		//note 处理接收的数据
		$status = MooGetGPC('status','string','G');

		//不可给同性别发送消息
		$receive_info = getUserInfo($contentid);
		
 		$receive_gender = $receive_info['gender'];
 		if($user_arr['gender'] == $receive_gender){			
 			MooMessage('不可给同性别发送消息', 'javascript:history.go(-1)','02');
 		}
        
 		//系统管理员权限
	    /*$result=$_MooClass['MooMySQL']->getOne("select groupid from web_admin_user where uid='{$serverid}'");
	    $groupid=$result['groupid'];
	    
	    //$GLOBALS['system_admin'] = array(60);
	    if(in_array($groupid,$GLOBALS['system_admin'])){
	        $serverid=null;
	    }*/
	    
 		$result=$_MooClass['MooMySQL']->getOne("select groupid from web_admin_user where uid='{$serverid}'");
	    $groupid=$result['groupid'];
 		
        if($serverid && $user_arr['usertype']!=3){
		   MooMessage('对不起您不能模拟操作','javascript:history.go(-1);','04');
		   exit;
	    }
        
	    if($serverid && $user_arr['usertype']==3 && !in_array($groupid,$GLOBALS['admin_aftersales'])){
	       MooMessage('对不起您不能模拟操作','javascript:history.go(-1);','04');
		   exit;
	    }
	    
		//如果是本站注册会员
		if($user_arr['usertype'] == 1){
				$m_level = get_userrank($userid);//会员等级
				if($m_level != 1){ //不是高级会员
							if(!checkIsMobileCertical($userid)){ //没有通过了手机验证
								MooMessage('您还没有进行手机验证,现在就去认证吧','index.php?n=myaccount&h=myaccount','03');
							}else{
								//if(!checkIsOver($userid)){ //过了试用期
									MooMessage('只有钻石或高级会员才可以发送电子邮件给对方,马上升级为钻石或高级会员吧', 'index.php?n=payment','03');
								//}
							}					
				}
		}
 		
		if($status == 's'){
			to_send_message();
			return;
		}
		//note 显示发送信息的窗口
		if($contentid){
			//note 显示消息接收方的会员名，无则显示ID号
			if($send_to_name = getfromname($contentid)){
			}else{
				$send_to_name = 'ID '.$contentid;
			}
			$s_id = $contentid;
			//note 包含发送消息模板页
			require MooTemplate('public/service_getmaillist', 'module');
		}else{
			MooMessage('没有收件人的ID，不可发送消息', 'index.php?n=search&h=basic','002');
		}
		break;		

	//note 回复信息****************************
	case "hf" :
		$contentid = MooGetGPC('sendtoid','integer');
		$s_title = str_replace(' ','+',MooGetGPC('title','string'));
		$s_title = base64_decode($s_title);
		//note 处理接收的数据
		$status = MooGetGPC('status','string','G');
		if($status == 's'){
			to_send_message();
			return;
		}
		//note 显示回复信息的窗口
		if($contentid){
			//如果是本站注册会员
			if($user_arr['usertype'] == 1){
					$m_level = get_userrank($userid);//会员等级
					if($m_level != 1){ //不是高级会员
							if(!checkIsMobileCertical($userid)){ //没有通过了手机验证
								MooMessage('您还没有进行手机验证,现在就去认证吧','index.php?n=myaccount&h=index2','03');
							}else{
//								if(!checkIsOver($userid)){ //过了试用期
									MooMessage('只有钻石或高级会员才可以发送电子邮件给对方,马上升级为钻石或高级会员吧', 'index.php?n=payment','03');
//								}
							}
					}
			}
		
			 $time=time();

			//note 显示消息接收方的会员名，无则显示ID号
			if(getfromname($contentid)){
				$send_to_name = getfromname($contentid);
			}else{
				$send_to_name = 'ID '.$contentid;
			}
			$s_id = $contentid;
			
			if(strstr($s_title,'回复：')) $s_title = $s_title;
			else $s_title = '回复：'.$s_title;
			
			//note 包含回复消息模板页
			require MooTemplate('public/service_getmaillist_hf', 'module');
		}else{
			MooMessage('没有收件人的ID，不可发送消息', 'index.php?n=search&h=basic','01');
		}
		break;
		
	//note 回复真爱一生信息****************************
	case "hfhn" :
		$s_title = str_replace(' ','+',MooGetGPC('title','string'));
		$s_title = base64_decode($s_title);
		//note 是否提交数据
		$status = MooGetGPC('status','string','G');
		if($status == 's'){
			to_send_hn_message();
			return;
		}
		//note 包含回复消息模板页
		require MooTemplate('public/service_getmaillist_hf_hn', 'module');
		break;
			
	//note 所有邮件
	case 'allmessage':
		allmessage_list();
		break;
	//note 所有消息（详细）
	case 'detailallmessage':
		$contentid = MooGetGPC('contentid','integer');
		allmessage_detail($contentid);
		break;
	default :
		allmessage_list();
		break;
}

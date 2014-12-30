<?php
//note 加载框架配置参数
require './include/config.php';
//note 加载MooPHP框架
require '../framwork/MooPHP.php';
//note 包含共公方法
include './include/function.php';
include './include/ajax_function.php';
include './include/allmember_function.php';


// 站内短信处理函数
function ajax_websms(){
	$id = MooGetGPC('id','integer','G');
	if($id){
		$sql = "UPDATE {$GLOBALS['dbTablePre']}services set dealstate = '1' WHERE s_id = {$id}";	
		$GLOBALS['_MooClass']['MooMySQL']->query($sql);
		//写日志
		serverlog(3,$GLOBALS['dbTablePre'].'services',"{$GLOBALS['adminid']}号客服{$GLOBALS['username']}在后台操作互动管理-》站内短信-》{$id}这条记录已处理",$GLOBALS['adminid']);
	}
}

// 会员秋波处理函数
function ajax_leer(){
	$id = MooGetGPC('id','integer','G');
	if($id){
		$sql = "UPDATE {$GLOBALS['dbTablePre']}service_leer set dealstate = '1' WHERE lid = {$id}";	
		$GLOBALS['_MooClass']['MooMySQL']->query($sql);
		//写日志
		serverlog(3,$GLOBALS['dbTablePre'].'service_leer',"{$GLOBALS['adminid']}号客服{$GLOBALS['username']}在后台操作互动管理-》会员秋波-》{$id}这条记录已处理",$GLOBALS['adminid']);
	}
}

//客服模拟发送秋波
function ajax_sendLeer(){
    global $dbTablePre;
    $sendfromuid=MooGetGPC('sendfrom','string','G');
    $sendtouid  =MooGetGPC('sendto','string','G');
    $sendtime=strtotime(MooGetGPC('sendtime','string','G'));
   
    $sendfrom=MooMembersData($sendfromuid);
    $sendto  =MooMembersData($sendtouid);
    


    if($sendfrom['usertype']!=3){
        exit('sender');
	}

	if($sendto['usertype']==3){
	    exit('receiver');
	}
	
	if(MooGetScreen($sendfromuid,$sendtouid)==1) exit('shield');

	if($sendfrom['gender']==$sendto['gender']) exit('gender');
	
	$is_first_send=true;
	$leer = $GLOBALS['_MooClass']['MooMySQL']->getOne("SELECT * FROM {$dbTablePre}service_leer WHERE receiveuid = '$sendtouid' AND senduid = '$sendfromuid' ",true);

	if(isset($leer['lid'])) {
			$is_first_send = false;
			$lid = $leer['lid'];
		   //note 如果已经发送过秋波，就增加发送秋波的次数
		   $GLOBALS['_MooClass']['MooMySQL']->query("UPDATE {$dbTablePre}service_leer SET num = num + 1,receivenum = receivenum + 1,sendtime = '$sendtime',receivetime = '$sendtime',receive_del = 0,is_read=0  WHERE lid = '$lid'");     
		   //note 如果已经收到这个人的秋波，已经拒绝，现在改变注意，又发送秋波给这个人,拒绝状态2更改为0
		   $GLOBALS['_MooClass']['MooMySQL']->query("UPDATE {$dbTablePre}service_leer SET stat = '0' WHERE senduid = '$sendtouid' AND receiveuid = '$sendfromuid' AND stat = '2'"); 
	}else {
		//note 发送新的秋波，写入数据库 发送者，接受者，发送时间
		$GLOBALS['_MooClass']['MooMySQL']->query("INSERT INTO {$dbTablePre}service_leer SET sendtime = '$sendtime',receivetime = '$sendtime',receivenum = '1', num = '1', senduid  = '{$sendfromuid}',receiveuid = '$sendtouid'");
	}
	
	$gender=$sendfrom['gender']==1?'女':'男';

	if($is_first_send && $sendto['is_phone'] && $sendto['telphone']) {
	    Push_message_intab($sendtouid,$sendto['telphone'],"秋波","会员ID：".$sendfromuid.",已给您发送秋波, 请及时把握您的缘分！400-8787-920",$sendfromuid,$sendtime);
	}
}	


//客服模拟发送鲜花
function ajax_sendRose(){
    global $dbTablePre;
    $sendfromuid=MooGetGPC('sendfrom','string','P');
    $sendtouid  =MooGetGPC('sendto','string','P');
    $sendtime=strtotime(MooGetGPC('sendtime','string','P'));
   
    $sendfrom=MooMembersData($sendfromuid);
    $sendto  =MooMembersData($sendtouid);
    


    if($sendfrom['usertype']!=3){
        exit('1');
	}

	if($sendto['usertype']==3){
	    exit(3);
	}
	
	if(MooGetScreen($sendfromuid,$sendtouid)) exit(0);

	if($sendfrom['gender']==$sendto['gender']) exit(10);
	
	$is_first_send=true;
	$rose = $_MooClass['MooMySQL']->getOne("SELECT * FROM {$dbTablePre}service_rose WHERE receiveuid = '$sendtouid' AND senduid = '$sendfromuid' ORDER BY rid DESC LIMIT 1");
	
	
	if($rose['rid']) {
		$is_first_send = false;
		$rid = $rose['rid'];
		//note 如果已经发送过玫瑰，就增加发送玫瑰的次数
		$_MooClass['MooMySQL']->query("UPDATE {$dbTablePre}service_rose SET num = num + 1, receivenum = receivenum + 1,sendtime = '$timestamp',receivetime='$timestamp',receive_del=0,send_del=0 WHERE rid = '$rid'");
	}else {
		//note 发送新的玫瑰，写入数据库 发送者，接受者，发送时间
		$_MooClass['MooMySQL']->query("INSERT INTO {$dbTablePre}service_rose SET num = 1, receivenum = 1, sendtime = '$timestamp',receivetime='$timestamp',senduid  = '{$sendfromuid}',receiveuid = '$sendtouid' ");
	}
		
	$gender=$sendfrom['gender']==1?'女':'男';
	if($is_first_send && $sendto['is_phone'] && $sendto['telphone']) Push_message_intab($sendtouid,$sendto['telphone'],"鲜花","真爱一生网 用户ID：".$sendfromuid.",".$gender.",已给您发送鲜花, 请及时把握您的缘分！4008787920【真爱一生网】",$sendfromuid,$sendtime);
}	
    
   


// 会员聊天处理函数
function ajax_chat(){
	$id = MooGetGPC('id','integer','G');
	if($id){
		$sql = "UPDATE {$GLOBALS['dbTablePre']}service_chat set dealstate = '1' WHERE s_id = {$id}";	
		//echo $sql;
		$GLOBALS['_MooClass']['MooMySQL']->query($sql);
		//写日志
		serverlog(3,$GLOBALS['dbTablePre'].'service_chat',"{$GLOBALS['adminid']}号客服{$GLOBALS['username']}在后台操作互动管理-》会员聊天-》{$id}这条记录已处理",$GLOBALS['adminid']);
	}
}
// 会员委托处理函数
function ajax_contact(){
	$id = MooGetGPC('id','integer','G');
	if($id){
		$sql = "UPDATE {$GLOBALS['dbTablePre']}service_contact set dealstate = '1' WHERE mid = {$id}";	
		$GLOBALS['_MooClass']['MooMySQL']->query($sql);
		//写日志
		serverlog(3,$GLOBALS['dbTablePre'].'service_contact',"{$GLOBALS['adminid']}号客服{$GLOBALS['username']}在后台操作互动管理-》会员委托-》{$id}这条记录已处理",$GLOBALS['adminid']);
	}
}
// 会员意中人处理函数
function ajax_friend(){
	$id = MooGetGPC('id','integer','G');
	if($id){
		$sql = "UPDATE {$GLOBALS['dbTablePre']}service_friend set dealstate = '1' WHERE fid = {$id}";	
		$GLOBALS['_MooClass']['MooMySQL']->query($sql);
		//写日志
		serverlog(3,$GLOBALS['dbTablePre'].'service_friend',"{$GLOBALS['adminid']}号客服{$GLOBALS['username']}在后台操作互动管理-》会员意中人-》{$id}这条记录已处理",$GLOBALS['adminid']);
	}
}
// 会员建议处理函数
function ajax_getadvice(){
	$id = MooGetGPC('id','integer','G');
	if($id){
		$sql = "UPDATE {$GLOBALS['dbTablePre']}service_getadvice set dealstate = '1' WHERE gid = {$id}";	
		$GLOBALS['_MooClass']['MooMySQL']->query($sql);
		//写日志
		serverlog(3,$GLOBALS['dbTablePre'].'service_getadvice',"{$GLOBALS['adminid']}号客服{$GLOBALS['username']}在后台操作互动管理-》会员建议-》{$id}这条记录已处理",$GLOBALS['adminid']);
	}
}
// 会员鲜花处理函数
function ajax_rose(){
	$id = MooGetGPC('id','integer','G');
	if($id){
		$sql = "UPDATE {$GLOBALS['dbTablePre']}service_rose set dealstate = '1' WHERE rid = {$id}";	
		$GLOBALS['_MooClass']['MooMySQL']->query($sql);
		//写日志
		serverlog(3,$GLOBALS['dbTablePre'].'service_rose',"{$GLOBALS['adminid']}号客服{$GLOBALS['username']}在后台操作互动管理-》会员鲜花-》{$id}这条记录已处理",$GLOBALS['adminid']);
	}
}
// 会员提醒处理函数
function ajax_remark(){
	$id = MooGetGPC('id','integer','G');
	if($id){
		$sql = "UPDATE {$GLOBALS['dbTablePre']}admin_remark set dealstate = '1' WHERE id = {$id}";	
		$GLOBALS['_MooClass']['MooMySQL']->query($sql);
		//写日志
		serverlog(3,$GLOBALS['dbTablePre'].'admin_remark',"{$GLOBALS['adminid']}号客服{$GLOBALS['username']}在后台操作互动管理-》我的提醒-》{$id}这条记录已处理",$GLOBALS['adminid']);
	}
}
function ajax_bind(){
	$id     = MooGetGPC('id','integer','G');
	$length_time = MooGetGPC('length','integer','G')*3600;
	$time   = time();
	$sql    = "SELECT a_uid,b_uid from {$GLOBALS['dbTablePre']}members_bind WHERE id=".$id;
	$ab_uid = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	
	$sql = "UPDATE {$GLOBALS['dbTablePre']}members_base SET bind_id={$id},isbind=1 
			WHERE uid in({$ab_uid['a_uid']},{$ab_uid['b_uid']})";
	$up1 = $GLOBALS['_MooClass']['MooMySQL']->query($sql);
	
	$sql = "UPDATE {$GLOBALS['dbTablePre']}members_bind 
			SET bind=1,start_time={$time},length_time={$length_time} WHERE id=".$id;
	$up2 = $GLOBALS['_MooClass']['MooMySQL']->query($sql);
	
	if (MOOPHP_ALLOW_FASTDB){
		$value = array();
		$value['bind_id']=$id;
		$value['isbind']=1;
		MooFastdbUpdate("members_base",'uid',$ab_uid['a_uid'],$value);
		MooFastdbUpdate("members_base",'uid',$ab_uid['b_uid'],$value);
	}
	
	if($up1 && $up2){
		echo 1;
	}else{
		echo 0;
	}
}

function ajax_bind_del(){
	$id     = MooGetGPC('id','integer','G');
	$sql    = "SELECT a_uid,b_uid from {$GLOBALS['dbTablePre']}members_bind WHERE id=".$id;
	$ab_uid = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	
	$sql = "UPDATE {$GLOBALS['dbTablePre']}members_base SET bind_id=0,isbind=0 
			WHERE uid in({$ab_uid['a_uid']},{$ab_uid['b_uid']})";
	$up  = $GLOBALS['_MooClass']['MooMySQL']->query($sql);
	
	if (MOOPHP_ALLOW_FASTDB){
		$value = array();
		$value['bind_id']=0;
		$value['isbind']=0;
		MooFastdbUpdate("members_base",'uid',$ab_uid['a_uid'],$value);
		MooFastdbUpdate("members_base",'uid',$ab_uid['b_uid'],$value);
	}
	
	$sql = "DELETE FROM {$GLOBALS['dbTablePre']}members_bind WHERE id=".$id;
	$del = $GLOBALS['_MooClass']['MooMySQL']->query($sql);
	
	if($up && $del){
		echo 1;
	}else{
		echo 0;
	}
	
}

function ajax_this_login(){
	$time = time();
	$uid = MooGetGPC('luid','integer','G');
	$serverid = $GLOBALS['adminid'];
   
	$sql = "select * from {$GLOBALS['dbTablePre']}members_search where uid='$uid'";
	$userinfo = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
 
	if(!$userinfo){
		echo '用户不存在';exit();
	}
	/*
	$useronline = $GLOBALS['_MooClass']['MooMySQL']->getOne("select * from {$GLOBALS['dbTablePre']}membersession where uid='$uid'");
	if($useronline){
		//echo '此用户在线';exit();
	}*/
	$userinfo_uid = $userinfo['uid'];
	$userinfo_pw = $userinfo['password'];
	//模拟用户登录
    MooSetCookie('auth', '', -86400 * 365);
    MooSetCookie('kefu', '', -86400 * 365);
	
	MooSetCookie('auth',MooAuthCode("$userinfo_uid\t$userinfo_pw",'ENCODE'),86400);	
	MooSetCookie('kefu',MooAuthCode("hongniangwang\t$serverid",'ENCODE'),86400);
    if (!session_id()) session_start();
    $_SESSION['s_userid']=$userinfo['uid'];
	//更新模拟时间
	$GLOBALS['_MooClass']['MooMySQL']->query("update `{$GLOBALS['dbTablePre']}member_admininfo` set real_lastvisit='$time' where uid='$uid'");
	if($GLOBALS['groupid']!=60){
		$GLOBALS['_MooClass']['MooMySQL']->query("update `{$GLOBALS['dbTablePre']}members_login` set lastvisit='$time' where uid='$uid'");
		if(MOOPHP_ALLOW_FASTDB) {
			$value['lastvisit']=$time;
			MooFastdbUpdate('members_login','uid',$uid,$value);
			}
	}
	//$kefu = $_MooCookie['kefu'];exit($kefu);
	serverlog(4,$GLOBALS['dbTablePre'].'members_search',"{$GLOBALS['adminid']}号客服{$GLOBALS['username']}在后台以会员{$userinfo['uid']}的身份查看{$uid}",$GLOBALS['adminid'],$userinfo['uid']);
	echo 'ok';
}
/***************************************控制层(V)***********************************/
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // 过去的时间

$n=MooGetGPC('n','string');
//判断是否登录


adminUserInfo();
if(empty($GLOBALS['adminid'])){
	echo 'nologin';exit;
}

switch($n){
	//note 站内短信
	case 'websms':
		ajax_websms();
		break;
	//note 会员秋波
	case 'leer':
		ajax_leer();
		break;
	case 'sendLeer':
	    ajax_sendLeer();
		break;
	//note 会员聊天
	case 'chat':
		ajax_chat();
		break;
	//note 会员委托
	case 'contact':
		ajax_contact();
		break;
	//note 会员意中人
	case 'friend':
		ajax_friend();
		break;
	//note 会员建议
	case 'getadvice':
		ajax_getadvice();
		break;
	//note 会员鲜花
	case 'rose':
		ajax_rose();
		break;
	case 'sendRose':
	    ajax_sendRose();
		break;
	case 'remark':
		ajax_remark();
		break;
	//note 模拟会员登录
	case 'this_login':
		ajax_this_login();
		break;
	case 'bind_del':
		ajax_bind_del();
		break;
	case 'bind_bind':
		ajax_bind();
		break;
}
?>
<?php
/*  
	Copyright (c) 2009 

	$Id: login.php 399 2009-06-15
	@author:fanglin
*/


/***************************************   逻辑层(M)/表现层(V)   ****************************************/
//note 客服登陆验证
function login_login() {
	global $_MooClass,$dbTablePre,$memcached;
	/*
	$seccode1 = strtolower(MooGetGPC('vertify_code','string','P'));
	$seccode2 = MooGetGPC('seccode','string','C');
	$session_seccode = $memcached->get($seccode2);

	if($seccode1 != $session_seccode){
		MooMessageAdmin("验证码填写不正确，请确认。", "index.php?action=login",'','',3);
	}
	*/		
	$username=MooGetGPC('username','string','P');
	$password=MooGetGPC('password','string','P');
	$password=md5($password);
	//判断用户名和密码是否为空
	if($username==''||$password==''){
		MooMessageAdmin('用户名或密码不能为空','index.php?n=login',1);
	}
	$userinfo = $_MooClass['MooMySQL']->getOne("SELECT * FROM {$dbTablePre}admin_user WHERE `username`='$username' LIMIT 1 ",true);
	
	if($userinfo['uid'] && $userinfo['password'] == $password){
		MooSetCookie('admin',MooAuthCode("{$userinfo['uid']}\t{$userinfo['password']}",'ENCODE'),86400);
		//note 写入session表需要的字段值
		$online_ip = GetIP();
		$lastactive = $GLOBALS['timestamp'];
		//note 提取快到期的高级用户并加入备注中
		$nowtime=time();
		$endtime=$nowtime+8*24*60*60;
		$_MooClass['MooMySQL']->query("DELETE FROM {$dbTablePre}custom_remark WHERE `keyword`='会员到期' AND `cid`='{$userinfo['uid']}'");
		$remark=$_MooClass['MooMySQL']->getAll("SELECT `uid`,`endtime` FROM {$dbTablePre}members_search WHERE `sid`={$userinfo['uid']} AND `s_cid`=30 AND `endtime`<{$endtime}",0,0,0,true);
		for($i=0;$i<count($remark);$i++){
		      $content="尊敬的客服，您的红娘号为".$remark[$i]['uid']."的会员将于".date('Y-m-d',$remark[$i]['endtime'])."到期，请尽快与该会员联系";
		      $_MooClass['MooMySQL']->query("INSERT INTO {$dbTablePre}custom_remark SET `cid`={$userinfo['uid']},`keyword`='会员到期',`content`='$content',`awoketime`='{$remark[$i]['endtime']}'");
		}
		
		//更新最后登录相关记录
		$sql = "UPDATE {$dbTablePre}admin_user SET lastlogin='$nowtime',lastip='{$online_ip}' WHERE uid='{$userinfo['uid']}'";
		$GLOBALS['_MooClass']['MooMySQL']->query($sql);
		
		$sid_list = '';
		//得到我所管理的客服id列表
		$sid_list = get_mymanage_serviceid_list($userinfo['uid'],$userinfo['groupid']);
		$time = time();
		$sql = "REPLACE INTO {$GLOBALS['dbTablePre']}admin_usersession SET uid='{$userinfo['uid']}',groupid='{$userinfo['groupid']}',dateline='{$time}',sid_list='{$sid_list}'";
		$GLOBALS['_MooClass']['MooMySQL']->query($sql);
		
		
		//添加操作日志
		serverlog(3,$dbTablePre."admin_usersession","{$userinfo['uid']}成功登陆后台",$userinfo['uid']);
		
		MooMessageAdmin('登陆成功','index.php?n=main',1);
	} else{
		MooMessageAdmin('用户名或密码错误','index.php?n=login',1);
	}
}

//退出登录
function login_out(){
	//note 清空cookie，同时清空session表记录
	MooClearAdminCookie();
	MooMessageAdmin('安全退出成功','index.php?n=login',1);
}


//退出当前登录的用户身份,切换用户时退出
function logout_change_identify(){
	MooSetCookie('change_identity', '', -86400 * 365);
	$GLOBALS['change_adminid'] = 0;
	MooMessageAdmin('成功返回','index.php',1);
}

//note 客服登陆页面
function login_index() {
	global $memcached;
	$seccode = md5(uniqid(rand(), true));
	MooSetCookie('seccode',$seccode,time()+3600,'');
	$session_seccode = $memcached->set($seccode , '' , 0 , 300);
	require adminTemplate("login_index");
}

//验证码
function login_code(){
	//验证码
	ob_clean();
	$img = MooAutoLoad('MooSeccode');
	$img -> outCodeImage(100,20,4);
}


/***************************************   控制层(C)   ****************************************/

$name = MooGetGPC('h','string','G') == '' ? 'index' : MooGetGPC('h','string','G');

//允许的方法
$names = array('login','index','logout','logout_change_identify','seccode');

if(!in_array($name, $names)) {
	MooMessageAdmin('没有这个页面', 'index.php', 0);
}

switch($name){
	case 'login': 
		login_login(); 
		break;
	case 'index':
		login_index();
		break;
	case 'logout':
		login_out();
		break;
	case 'logout_change_identify':
		logout_change_identify();
	break;
	case 'seccode':
		login_code();
	break;
	default:
		login_index();
		break;
}
?>
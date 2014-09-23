<?php
//note 加载框架配置参数
require './include/config.php';
//note 加载MooPHP框架
require '../framwork/MooPHP.php';
//note 包含共公方法
include './include/function.php';
include './include/allmember_function.php';
//note 检查会员ID
function ajax_checkid(){
	$id = MooGetGPC('id','integer','G');
	$sql = "SELECT username,nickname,gender,s_cid FROM {$GLOBALS['dbTablePre']}members_search WHERE uid = {$id}";
	$member = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
	if( empty($member) ){
		echo json_encode(0);exit;
	}
	$member['isonline'] = check_online($id);
	echo json_encode($member);
	exit;
}
//note 发送消息
function ajax_sendmes(){
	$sender = MooGetGPC('sender','integer','P');
	$receiver = MooGetGPC('receiver','integer','P');
	$mes = MooGetGPC('mes','string','P');
	
	$time = time();
	$sql = "INSERT INTO {$GLOBALS['dbTablePre']}service_chat(s_uid,s_fromid,s_content,s_time,s_status,is_server,dealstate)
			VALUE({$receiver},{$sender},'{$mes}',{$time},0,{$GLOBALS['adminid']},1)";
	$GLOBALS['_MooClass']['MooMySQL']->query($sql);
	
	//伪造会员在线
	$time = time();
	$sql = "UPDATE {$GLOBALS['dbTablePre']}members_login SET lastvisit = {$time} WHERE uid = {$sender}";
	$GLOBALS['_MooClass']['MooMySQL']->query($sql);
	
	if(MOOPHP_ALLOW_FASTDB)	MooFastdbUpdate('members_login','uid',$sender);
	
	//写日志
	serverlog(4,$GLOBALS['dbTablePre'].'service_chat',"{$GLOBALS['adminid']}号客服{$GLOBALS['username']}在后台以会员{$sender}的身份与会员{$receiver}聊天",$GLOBALS['adminid']);
	echo 'ok';exit;
}
//note 获取消息
function ajax_getmes(){
	$sender = MooGetGPC('sender','integer','G');
	$receiver = MooGetGPC('receiver','integer','G');
	$chat_id = MooGetGPC('chat_id','integer','G');
	
	$sql = "SELECT * FROM {$GLOBALS['dbTablePre']}service_chat WHERE s_uid = {$sender} AND s_fromid = {$receiver} AND s_status = 0 and s_id>{$chat_id}";
	$mes = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
	foreach( $mes as $k=>$v ){
		$mes[$k]['s_time'] = date("H:i:s",$mes[$k]['s_time']);
		$mes[$k]['s_content'] = htmlspecialchars($mes[$k]['s_content']);
		$sql = "UPDATE {$GLOBALS['dbTablePre']}service_chat SET s_status = 1 WHERE s_id = {$v['s_id']}";
		$GLOBALS['_MooClass']['MooMySQL']->query($sql);
	}
	echo json_encode($mes);exit;
}
//note 检查在线状态
function ajax_checkonline(){
	$checkid = MooGetGPC('checkid','integer','G');
	$ret = check_online($checkid);
	if($ret){echo 1;}else{echo 0;}
}
//note 聊天会员菜单
function ajax_menu(){
	$page = max(1,MooGetGPC('page','integer','G'));
    $limit = 10;
    $offset = ($page-1)*$limit;
    
    $sender = MooGetGPC('sender','integer','G');
	$receiver = MooGetGPC('receiver','integer','G');
	
	//$sender= 2158447;
	//$receiver = 2158450;
	if( empty($receiver)) exit("empty receiver");
	
	$sql = "SELECT uid,nickname FROM {$GLOBALS['dbTablePre']}members_search WHERE uid = {$receiver}";
	$user = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
    
	$sql = "SELECT DISTINCT(s_uid) FROM {$GLOBALS['dbTablePre']}service_chat WHERE s_fromid = {$receiver} ORDER BY s_time DESC LIMIT {$offset},{$limit}";
	$mem = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
	$mem_list = array();
	foreach($mem as $v){
		array_push($mem_list,$v['s_uid']);
	}
	$id_list = implode(',',$mem_list);
	
	if(!empty($id_list)){
		$sql = "SELECT uid,nickname FROM {$GLOBALS['dbTablePre']}members_search WHERE uid IN ({$id_list}) AND uid != {$receiver}";
		$members = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
	}else{
		echo '会员'.$receiver.'暂无聊天记录 <a href="javascript:history.back();">返回</a>';
		exit;
	}
	//写日志
	serverlog(1,$GLOBALS['dbTablePre'].'service_chat',"{$GLOBALS['adminid']}号客服{$GLOBALS['username']}查看会员{$receiver}的聊天",$GLOBALS['adminid']);
	require_once(adminTemplate('chat_menu'));
}
//note 显示聊天记录
function ajax_show(){
	$page = max(1,MooGetGPC('page','integer','G'));
    $limit = 2;
    $offset = ($page-1)*$limit;
    
	$uid = MooGetGPC('uid','integer','G');
	$sid = MooGetGPC('sid','integer','G');
	$date = MooGetGPC('date','string','G');
	
	if(empty($date)){
		$sql = "SELECT DISTINCT(FROM_UNIXTIME(s_time,'%Y-%m-%d')) AS date FROM {$GLOBALS['dbTablePre']}service_chat WHERE (s_uid = {$uid} AND s_fromid = {$sid}) OR (s_uid = {$sid} AND s_fromid = {$uid}) ORDER BY s_time DESC";
		$date_arr = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);	
		$date = $date_arr[$page-1]['date'];
	}
	
	if(empty($date)) exit("会员{$sid}与会员{$uid}暂无聊天记录!");
	$st = strtotime($date);
	$et = $st + 86400;
	
	$sql = "SELECT c.*,s.nickname FROM {$GLOBALS['dbTablePre']}service_chat c
			LEFT JOIN {$GLOBALS['dbTablePre']}members_search s
			ON c.s_fromid = s.uid
			WHERE ((c.s_uid = {$uid} AND c.s_fromid = {$sid}) OR (c.s_uid = {$sid} AND c.s_fromid = {$uid})) AND c.s_time BETWEEN {$st} AND {$et} ORDER BY c.s_time ASC";
	$mes = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
	
	$currenturl = "chat_ajax.php?n=show&uid={$uid}&sid={$sid}";
   	$pages = multipage( count($date_arr), $limit, $page, $currenturl );

	require_once(adminTemplate('chat_history_list'));
}
/***************************************控制层(V)***********************************/
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // 过去的时间

//判断是否登录
adminUserInfo();
if(empty($GLOBALS['adminid'])){
	echo 'nologin';exit;
}

$n = MooGetGPC('n','string');

switch($n){
	//note 检查会员ID
	case 'checkid':
		ajax_checkid();	
	break;
	//note 发送消息
	case 'sendmes':
		ajax_sendmes();	
	break;
	//note 获取消息
	case 'getmes':
		ajax_getmes();	
	break;
	//note 检查在线状态
	case 'checkonline':
		ajax_checkonline();	
	break;
	//note 聊天会员菜单
	case 'menu':
		ajax_menu();	
	break;
	//note 显示聊天记录
	case 'show':
		ajax_show();	
	break;
}
?>
<?php
$pagesize = 10;         //服务中心信息每页显示的条目数

include "./module/service/function.php";
include "./ework/include/config.php";
include "module/andriod/function.php";

$back_url='index.php?'.$_SERVER['QUERY_STRING'];

$serverid = Moo_is_kefu();

if (isset($_MooCookie['kefu'])){ 
	$kefu = MooAuthCode($_MooCookie['kefu'], 'DECODE');
	if($kefu) list($hzn,$adminid) = $arr = explode("\t", $kefu);
}


$and_uuid = isset($_GET['uuid'])? $_GET['uuid'] : '';
$uid =  isset($_GET['uid'])?$_GET['uid']:'';
if($uid){
	$userid = $mem_uid = $memcached->get('uid_'.$uid);
}
$uuid = $memcached->get('uuid_'.$userid);
$error[] = array("getand_uuid"=>$and_uuid,"getuid"=>$uid,"userid"=>$userid,"mem_uuid"=>$uuid);
$checkuuid = check_uuid($and_uuid,$userid);
if(!$checkuuid){
    $error = "uuid_error";
    echo return_data($error,false);exit;	
}
else{
	
	$user_arr = MooMembersData($userid);
}


$h = empty($_GET['s']) ? '' : $_GET['s'];
switch ($h){
	case 'newcon':
		require 'newcon.php';
		break;
	
	default:
		require 'newcon.php';
		break;
		
		
}
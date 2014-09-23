<?php
include "module/andriod/function.php";

global $_MooClass, $dbTablePre, $userid,$memcached,$user_arr;
$status = array ();
$uid =  $_GET['uid'] = isset($_GET['uid'])?$_GET['uid']:'';
if($uid){
	$userid = $mem_uid = $memcached->get('uid_'.$uid);
}
$user_arr = MooMembersData($userid);
$and_uuid = isset($_GET['uuid'])? $_GET['uuid'] : '';
$checkuuid = check_uuid($and_uuid,$userid);
if(!$checkuuid){
    $error = "uuid_error";
    echo return_data($error,false);exit;	
}
//登录会员信息
if($userid){
    $sql = "select s.uid, s.birthyear, s.height, s.marriage, s.education, s.city, s.salary, s.certification, b.mainimg from {$dbTablePre}members_search s
     left join {$dbTablePre}members_base b on b.uid = s.uid where s.uid = $userid";
    $user = $_MooClass['MooMySQL']->getOne($sql);
	$all_len = 0;
	if ($userid) {
		$all_len = ( int ) (getUserinfo_per ( $user_arr ) * 100);
	}
	echo $all_len;exit;
	$user['all_len'] = $all_len;
	$user['cer'] = get_integrity($user['certification']);
	echo return_data($user);exit;
}
	
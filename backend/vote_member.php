<?php 
	//note 配置文件
require './include/config.php';

//note 加载Moo框架
require '../framwork/MooPHP.php';

//note 加载后台共用方法文件
require './include/function.php';

//note 权限菜单分类配置文件  
require './include/menu.inc.php';

$db = $_MooClass['MooMySQL'];


if($_REQUEST['act'] == ''){
	$act = 'list';
}else{
	$act = $_REQUEST['act'];
}


if($act == 'list'){
	$sql = "select uid from vote_member";
	$uid_c = $db->getAll($sql);
	$count = count($uid_c);
	$page_num = 15;
	$page_all =  $count/$page_num;
	for($i=0;$i<$page_all;$i++){
		$page[$i] = $i+1;
	}
	if(empty($_REQUEST['page_now'])){
		$page_now = 1;
	}else{
		$page_now = $_REQUEST['page_now'];
	}
	$page_one = ($page_now-1)*$page_num;
	$sql = "select * from  vote_member order by uid desc limit $page_one,$page_num";
	$member = $db->getAll($sql);
	foreach($member as $k => $v){
		$member[$k]['birthdate'] = substr($v['birthdate'],0,4).'年'.substr($v['birthdate'],4,2).'月'.substr($v['birthdate'],6,2).'日';
		if($member[$k]['marriage'] == 0){
			$member[$k]['marriage'] = "未婚";
		}else if($member[$k]['marriage'] == 1){
			$member[$k]['marriage'] = "已婚";
		}else if($member[$k]['marriage'] == 2){
			$member[$k]['marriage'] = "丧偶";
		}else{
			$member[$k]['marriage'] = "未选择";
		}
		if($member[$k]['education'] == 0){
			$member[$k]['education'] = "高中以下";
		}else if($member[$k]['education'] == 1){
			$member[$k]['education'] = "职高";
		}else if($member[$k]['education'] == 2){
			$member[$k]['education'] = "大专";
		}else if($member[$k]['education'] == 3){
			$member[$k]['education'] = "一般本科";
		}else if($member[$k]['education'] == 4){
			$member[$k]['education'] = "重点本科";
		}else if($member[$k]['education'] == 5){
			$member[$k]['education'] = "研究生";
		}else if($member[$k]['education'] == 6){
			$member[$k]['education'] = "研究生以上";
		}else{
			$member[$k]['education'] = "未选择";
		}
	}
	require_once(adminTemplate('vote_member'));
}else if($act == 'listall'){
	if(empty($_REQUEST['uid'])){
		header("location:vote_member.php?act=list");
	}else{
		$uid = $_REQUEST['uid'];
		$sql = "select t1.*,t2.vote_title,t3.sub_time,t3.vote_result from vote_member as t1 left join vote_sub as t3 on t1.uid=t3.uid left join vote as t2 on t3.vid = t2.vid where t1.uid = '$uid'";
		$result = $db->getAll($sql);
		foreach($result as $k => $v){
			if($k=='0'){
				$arr[] = $v;
			}
			$arr['vote'][$k][] = $v['vote_title'];
			$str = str_replace(':1',"",$v['vote_result']);
			$arr['vote'][$k][] = $str;
		}
		
		$arr[0]['birthdate'] = substr($arr[0]['birthdate'],0,4).'年'.substr($arr[0]['birthdate'],4,2).'月'.substr($arr[0]['birthdate'],6,2).'日';
		if($arr[0]['marriage'] == 0){
			$arr[0]['marriage'] = "未婚";
		}else if($arr[0]['marriage'] == 1){
			$arr[0]['marriage'] = "已婚";
		}else if($arr[0]['marriage'] == 2){
			$arr[0]['marriage'] = "丧偶";
		}else{
			$arr[0]['marriage'] = "未选择";
		}
		if($arr[0]['education'] == 0){
			$arr[0]['education'] = "高中以下";
		}else if($arr[0]['education'] == 1){
			$arr[0]['education'] = "职高";
		}else if($arr[0]['education'] == 2){
			$arr[0]['education'] = "大专";
		}else if($arr[0]['education'] == 3){
			$arr[0]['education'] = "一般本科";
		}else if($arr[0]['education'] == 4){
			$arr[0]['education'] = "重点本科";
		}else if($arr[0]['education'] == 5){
			$arr[0]['education'] = "研究生";
		}else if($arr[0]['education'] == 6){
			$arr[0]['education'] = "研究生以上";
		}else{
			$arr[0]['education'] = "未选择";
		}

		require_once(adminTemplate('vote_member_one'));
	}
}else if($act == 'delect'){
	
	if(empty($_REQUEST['uid'])){
		header("location:vote_member.php?act=list");
	}else{
		$uid = $_REQUEST['uid'];
		$sql = "DELETE FROM vote_member WHERE uid = $uid";
		$db->query($sql);
		$sql = "DELETE FROM vote_sub WHERE uid = $uid";
		$db->query($sql);
		MooMessageAdmin('删除成功','vote_member.php?act=list',1);
	}
}else if($act == 'edit'){
	if(empty($_REQUEST['uid'])){
		header("location:vote_member.php?act=list");
	}else{
		$uid = $_REQUEST['uid'];
		$sql = "select t1.*,t2.vote_title,t3.sub_time,t3.vote_result,t3.vid from vote_member as t1 left join vote_sub as t3 on t1.uid=t3.uid left join vote as t2 on t3.vid = t2.vid where t1.uid = '$uid'";
		$result = $db->getAll($sql);
		foreach($result as $k => $v){
			if($k=='0'){
				$arr[] = $v;
			}
			$arr['vote'][$v[vid]][] = $v['vote_title'];
			$str = str_replace(':1',"",$v['vote_result']);
			$arr['vote'][$v[vid]][] = $str;
		}
		
			$arr[0]['birthdatey'] = substr($arr[0]['birthdate'],0,4);
			$arr[0]['birthdatem'] = substr($arr[0]['birthdate'],4,2)>10?substr($arr[0]['birthdate'],4,2):substr($arr[0]['birthdate'],5,1);
			$arr[0]['birthdated'] = substr($arr[0]['birthdate'],6,2)>10?substr($arr[0]['birthdate'],6,2):substr($arr[0]['birthdate'],7,1);
		
		require_once(adminTemplate('vote_member_edit'));
	}
}else if($act == 'update'){
	if(empty($_REQUEST['uid'])){
		header("location:vote_member.php?act=list");
	}else{
		$obtain = $_REQUEST;
		if($obtain['month'] == '0'){
			$birthdatem = '00';
		}else if($obtain['month'] < '10'){
			$birthdatem = '0'.$obtain['month'];
		}else{
			$birthdatem = $obtain['month'];
		}
		if($obtain['day'] == '0'){
			$birthdated = '00';
		}else if($obtain['day'] < '10'){
			$birthdated = '0'.$obtain['day'];
		}else{
			$birthdated = $obtain['day'];
		}
		if($obtain['year'] == '0'){
			$birthdatey = '1900';
		}else{
			$birthdatey = $obtain['year'];
		}
		$birthdate = $birthdatey.$birthdatem.$birthdated;
		$sql = "update vote_member set nick_name = '$_REQUEST[nick_name]',sex = '$_REQUEST[sex]',region = '$_REQUEST[workprovince]',region_city = '$_REQUEST[workCity]',birthdate = '$birthdate',phone = '$_REQUEST[phone]',cellphone = '$_REQUEST[cellphone]',marriage = '$_REQUEST[marriage]',education = '$_REQUEST[education]',email = '$_REQUEST[email]' where uid ='$_REQUEST[uid]'";
		$db->query($sql);
		foreach($obtain as $k => $v){
			
			if(strpos($k,'esult') == '1'){
				$arr[substr($k,6)] = $v;
			}
			
		}
		foreach($arr as $k => $v){
			$result  = explode(';',$v);
			$result1 .= implode(':1;',$result);
			
			
			$sql = "update vote_sub set vote_result = '$result1' where uid = '$_REQUEST[uid]' and vid = '$k'";
			$db->query($sql);
			$result1 = "";
		}
		header("location:vote_member.php?act=list");
		
	}
	
}

?>
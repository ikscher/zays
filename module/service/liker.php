<?php
/*
 * Created on 8/14/2009
 *
 * Tianyong
 *
 * module/service/liker.php
 */


//note 我的意中人列表
function whoismyfriend() {
	global $_MooClass,$dbTablePre,$userid,$pagesize,$user_arr;
	
	$pagesize = 4;
	//note 获取删除提交的变量
	$delfriend = MooGetGPC('delfriend','string');
	$delfriendid = MooGetGPC('delfriendid','array');
	
	//note 删除提交的数据
	if($delfriend && count($delfriendid)) {
		foreach($delfriendid as $v) {
			$_MooClass['MooMySQL']->query("DELETE FROM {$dbTablePre}service_friend WHERE fid = '$v'");
		}
		MooMessage("删除意中人成功",'index.php?n=service&h=liker','05');
	}
	
	//note 如果提交的数据不存在，或者是提交的时候没有选中
	if($delfriend && !count($delfriendid)) {
		MooMessage('请选择要删除的意中人','index.php?n=service&h=liker','01');
		exit;
	}
	
	
	//note 获得当前url
	$currenturl = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"]; 
	$currenturl2 = preg_replace("/(&page=\d+)/","",$currenturl);
		
	//note 获得第几页
	$page = empty($_GET['page']) ? 1 : $_GET['page'];
		
	//note limit查询开始位置
	$start = ($page - 1) * $pagesize;

	//note 已收到的统计总数
	$query = $_MooClass['MooMySQL']->getOne("select count(1) as num FROM {$dbTablePre}service_friend WHERE uid = '$userid'");
	$total = $query['num'];
	$total2Arr = $_MooClass['MooMySQL']->getOne("SELECT count(*) FROM {$dbTablePre}service_friend WHERE friendid = '$userid'");
	$total2 = $total2Arr['count(*)'];
	//note 查询出意中人相关信息
	if($total){
		$results  = $_MooClass['MooMySQL']->getAll("SELECT * FROM {$dbTablePre}service_friend WHERE uid = '$userid' order by sendtime desc LIMIT $start,$pagesize");
		
		$friends=array();
		foreach($results as $k=>$v){
			$send_user1=array();
			$send_user2=array();
			$friend=array();

			$send_user1 = leer_send_user1($v['friendid']);
			$send_user2 = leer_send_user2($v['friendid']);
			
			$friend['l']=$v;  
			$friend['s']=$send_user1;
			$friend['t']=$send_user2;
			$friends[$k]=$friend;
		}
	}
	require  MooTemplate('public/service_friend_myfriendlist', 'module');
}
//note 谁加我为意中人
function whoaddme(){
	global $_MooClass,$dbTablePre,$userid,$pagesize,$user_arr;
	
	$pagesize = 4;
	//note 获得当前url
	$currenturl = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"]; 
	$currenturl2 = preg_replace("/(&page=\d+)/","",$currenturl);
	$currenturl2 = preg_replace("/7651/","7652",$currenturl2);
		
	//note 获得第几页
	$page = empty($_GET['page']) ? 1 : $_GET['page'];
		
	//note limit查询开始位置
	$start = ($page - 1) * $pagesize;

	//note 查出谁加我为意中人的总数
	$ret_count = $_MooClass['MooMySQL']->getOne("SELECT count(*) as c FROM {$dbTablePre}service_friend WHERE friendid = '$userid'");
	$total = $ret_count['c'];
	$total2Arr = $_MooClass['MooMySQL']->getOne("SELECT count(*) FROM {$dbTablePre}service_friend WHERE uid = '$userid'");
	$total2 = $total2Arr['count(*)'];
	//note 
	if($total){
		$results  = $_MooClass['MooMySQL']->getAll("SELECT * FROM {$dbTablePre}service_friend WHERE friendid = '$userid' order by sendtime desc LIMIT $start,$pagesize");
		
		$friends=array();
		foreach($results as $k=>$v){
			$send_user1=array();
			$send_user2=array();
			$friend=array();

			$send_user1 = leer_send_user1($v['uid']);
			$send_user2 = leer_send_user2($v['uid']);
			
			$friend['l']=$v;  
			$friend['s']=$send_user1;
			$friend['t']=$send_user2;
			$friends[$k]=$friend;
		}
	}
	require MooTemplate('public/service_friend_whoaddmylist', 'module');
}
/*****************************************************************************************************/

//控制部分
switch ($_GET['t']) {
	case "iaddliker" :
		whoismyfriend();
		break;

	
	case 'whoaddme':
		whoaddme();
		break;
	default :
		whoismyfriend();
}


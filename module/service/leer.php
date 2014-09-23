<?php

/*
 * Created on 8/14/2009
 *
 * Tianyong
 *
 * module/service/leer.php
 */

//note 已发送秋波的列表
function sendleerto() {
	global $_MooClass,$dbTablePre,$userid,$pagesize,$user_arr;
	$pagesize = 4;
	//note 获取删除提交的变量
	$delleer = MooGetGPC('delleer','string');
	$delleerid = MooGetGPC('delleerid','array');
	
	// print_r($delleerid);

	//note 删除提交的数据
	if($delleer && count($delleerid)) {
		$time = time();
		foreach($delleerid as $v) {
			//$_MooClass['MooMySQL']->query("DELETE FROM {$dbTablePre}service_leer WHERE lid = '$v'");
			$_MooClass['MooMySQL']->query("UPDATE {$dbTablePre}service_leer SET send_del=1,send_deltime = '{$time}' WHERE lid = '$v'");
		}
		MooMessage("秋波删除成功",'index.php?n=service&h=leer&t=isendleer','05');
	}
	
	//note 如果提交的数据不存在，或者是提交的时候没有选中
	if($delleer && !count($delleerid)) {
		MooMessage('请选择要删除秋波','index.php?n=service&h=leer&t=isendleer','01');
		exit;
	}
	
	
	//分页
	$page_per = 4;
	$page_url = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"]; 
	$page_url = preg_replace( "/(&page=\d+)/","",$page_url );
	$page_now = MooGetGPC( 'page', 'integer', 'G');
	
	$temp = $_MooClass['MooMySQL']->getOne("select count(1) as num FROM {$dbTablePre}service_leer WHERE senduid  = '$userid' ",true); //and send_del=0 and is_server=0
	$item_num = $temp['num'];
	$page_num = ceil( $item_num / $page_per );
	if( $page_now > $page_num ) $page_now = $page_num;
	if( $page_now < 1 ) $page_now = 1;
	//读数据
	$start = ($page_now - 1) * $page_per;
	$results  = $_MooClass['MooMySQL']->getAll("SELECT * FROM {$dbTablePre}service_leer WHERE senduid = '$userid' and send_del=0 ORDER BY sendtime desc LIMIT $start,$page_per");//and send_del=0 and is_server=0

	$leers=array();
	foreach($results as $k=>$v){
	    $send_user1=array();
	    $send_user2=array();
		$leer=array();

	    $send_user1 = leer_send_user1($v['receiveuid']);
		$send_user2 = leer_send_user2($v['receiveuid']);
		
		$leer['l']=$v;  
		$leer['s']=$send_user1;
		$leer['t']=$send_user2;
		$leers[$k]=$leer;
	}

    

	//note 已收到的总数
	$temp = $_MooClass['MooMySQL']->getOne("SELECT sum(case when receivenum>=1 then 1 else 0 end) as num FROM {$dbTablePre}service_leer WHERE receiveuid = '$userid' and receive_del=0",true);
	$receive_num = empty( $temp['num'] ) ? 0 : $temp['num'];

	//note 已发送的总数
	$temp = $_MooClass['MooMySQL']->getOne("SELECT sum(case when num>=1 then 1 else 0 end) as num FROM {$dbTablePre}service_leer WHERE senduid = '$userid' and send_del=0 ",true);//and send_del=0 and is_server=0
	$send_num = empty( $temp['num'] ) ? 0 : $temp['num'];
	require MooTemplate('public/service_leer_sendleerto', 'module');
}


//note 已收到秋波的列表
function getmyleers() {
	global $_MooClass,$dbTablePre,$userid,$pagesize,$user_arr;
	
	//note 处理回复秋波
	$repeatleer = MooGetGPC('repeatleer','string');
	$repeatleerid = MooGetGPC('repeatleerid','integer');
	if($repeatleer && $repeatleerid) {
		$_MooClass['MooMySQL']->query("UPDATE {$dbTablePre}service_leer SET stat = '$repeatleer' WHERE senduid = '$repeatleerid' AND receiveuid = '$userid' ");
		header("location:index.php?n=service&h=leer");
	}
	
	//note 处理委婉拒绝秋波
	$refuseleer = MooGetGPC('refuseleer','string');
	$refuseleerid = MooGetGPC('refuseleerid','integer');
	if($refuseleer && $refuseleerid) {
		$_MooClass['MooMySQL']->query("UPDATE {$dbTablePre}service_leer SET stat = '2' WHERE senduid = '$refuseleerid' AND receiveuid = '$userid' ");
		header("location:index.php?n=service&h=leer");
	}
	
	
	//note 获取删除提交的变量
	$delleer = MooGetGPC('delleer','string');
	$delleerid = MooGetGPC('delleerid','array');
	
	//note 删除提交的数据
	if($delleer && count($delleerid)) {
		$time = time();
		foreach($delleerid as $v) {
			//$_MooClass['MooMySQL']->query("DELETE FROM {$dbTablePre}service_leer WHERE lid = '$v'");
			$_MooClass['MooMySQL']->query("UPDATE {$dbTablePre}service_leer SET receive_del=1,receive_deltime='{$time}',receivenum = '0' WHERE lid = '$v'");
		}
		MooMessage("秋波删除成功",'index.php?n=service&h=leer');
	}
	//note 如果提交的数据不存在，或者是提交的时候没有选中
	if($delleer && !count($delleerid)) {
		MooMessage('请选择要删除的秋波','index.php?n=service&h=leer','01');
		exit;
	}
	
	
	//分页
	$page_per = 4;
	$page_url = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"]; 
	$page_url = preg_replace( "/(&page=\d+)/","",$page_url );

	
	$page_now = MooGetGPC( 'page', 'integer', 'G');
	
	$temp = $_MooClass['MooMySQL']->getOne("select count(1) as num FROM {$dbTablePre}service_leer WHERE receiveuid  = '$userid' and receive_del=0");
	$item_num = $temp['num'];
	$page_num = ceil( $item_num / $page_per );
	if( $page_now > $page_num ) $page_now = $page_num;
	if( $page_now < 1 ) $page_now = 1;
	//读数据
	$start = ($page_now - 1) * $page_per;
	$results  = $_MooClass['MooMySQL']->getAll("SELECT * FROM {$dbTablePre}service_leer WHERE receiveuid = '$userid' and receive_del=0  ORDER BY receivetime desc LIMIT $start,$page_per");
	

	$leers=array();
	foreach($results as $k=>$v){
	    $send_user1=array();
	    $send_user2=array();
	    $send_user1 = leer_send_user1($v['senduid']);$send_user2 = leer_send_user2($v['senduid']);
		$leers[$k]['l']=$v;  
		$leers[$k]['s']=$send_user1;
		$leers[$k]['t']=$send_user2;
		
	}

	//note 已收到的总数
	$temp = $_MooClass['MooMySQL']->getOne("SELECT sum(case when receivenum>=1 then 1 else 0 end) as num FROM {$dbTablePre}service_leer WHERE receiveuid = '$userid' and receive_del=0");
	$receive_num = empty( $temp['num'] ) ? 0 : $temp['num'];

	//note 已发送的总数
	$temp = $_MooClass['MooMySQL']->getOne("SELECT sum(case when num>=1 then 1 else 0 end) as num FROM {$dbTablePre}service_leer WHERE senduid = '$userid' and send_del=0 ");//AND send_del = 0 AND is_server=0
	$send_num = empty( $temp['num'] ) ? 0 : $temp['num'];
	
	require MooTemplate('public/service_leer_getmyleers', 'module');
}

//控制部分
$t=MooGetGPC('t','string','G');
switch ($t) {
	//note 我收到的秋波列表
	case "igetleer" :
		getmyleers();
		break;
		
	//note 我发出的秋波列表	
	case "isendleer" :
		sendleerto();
		break;
		
	//note 默认页面跳转到我收到的秋波列表
	default :
		getmyleers();
}


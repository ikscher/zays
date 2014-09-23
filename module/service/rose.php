<?php
/*
 * Created on 8/14/2009
 *
 * Tianyong
 *
 * module/service/rose.php
 */

//note 获得更多的玫瑰
function get_more_rose() {
	global $_MooClass,$dbTablePre,$userid,$user_arr;
	//note 已收到的玫瑰总数
	$rose_num1 = $_MooClass['MooMySQL']->getOne("SELECT sum(receivenum) num FROM {$dbTablePre}service_rose WHERE receiveuid = '$userid' and receive_del=0 and is_server=0 LIMIT 1");
	$num1 = $rose_num1['num'];
	
	//note 已发送的玫瑰总数
	$rose_num2 = $_MooClass['MooMySQL']->getOne("SELECT sum(num) num FROM {$dbTablePre}service_rose WHERE senduid = '$userid' and is_server=0");
	$num2 = $rose_num2['num'];
	
	require  MooTemplate('public/service_rose_getmorerose', 'module');	
}

//note 发送的玫瑰花
function get_send_rose() {
	global $_MooClass,$dbTablePre,$userid,$pagesize,$user_arr;

	//note 获取删除提交的变量
	$delrose = MooGetGPC('delrose','string');
	$delroseid = MooGetGPC('delroseid','array');
	$time = time(); 
	//note 删除提交的数据
	if($delrose && count($delroseid)) {
		foreach($delroseid as $v) {
			$_MooClass['MooMySQL']->query("UPDATE  {$dbTablePre}service_rose SET send_del=1,send_deltime='{$time}' WHERE rid = '$v'");
		}
		MooMessage("鲜花删除成功",'index.php?n=service&h=gift&t=isendrose','05');
	}
	//note 如果提交的数据不存在，或者是提交的时候没有选中
	if($delrose && !count($delroseid)) {
		MooMessage('请选择要删除的鲜花','index.php?n=service&h=gift&t=isendrose','01');
		exit;
	}
	
	
	//分页
	$page_per = 4;
	$page_url = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"]; 
	$page_url = preg_replace( "/(&page=\d+)/","",$page_url );
	$page_url = preg_replace( "/7651/","7652",$page_url );
	$page_now = MooGetGPC( 'page', 'integer', 'G');
	
	$temp = $_MooClass['MooMySQL']->getOne("select count(1) as num FROM {$dbTablePre}service_rose WHERE senduid  = '$userid' and send_del=0 and is_server=0");
	$item_num = $temp['num'];
	$page_num = ceil( $item_num / $page_per );
	if( $page_now > $page_num ) $page_now = $page_num;
	if( $page_now < 1 ) $page_now = 1;
	
	$start = ($page_now - 1) * $page_per;
	$results  = $_MooClass['MooMySQL']->getAll("SELECT * FROM {$dbTablePre}service_rose WHERE senduid = '$userid' and send_del=0 ORDER BY sendtime desc LIMIT $start,$page_per");
	
	$roses=array();
	foreach($results as $k=>$v){
	    $send_user1=array();
	    $send_user2=array();
		$rose=array();

	    $send_user1 = leer_send_user1($v['receiveuid']);
		$send_user2 = leer_send_user2($v['receiveuid']);
		
		$rose['l']=$v;  
		$rose['s']=$send_user1;
		$rose['t']=$send_user2;
		$roses[$k]=$rose;
	}
	
	
	//note 已收到的玫瑰总数
	$temp = $_MooClass['MooMySQL']->getOne("SELECT sum(case when receivenum>=1 then 1 else 0 end) as num FROM {$dbTablePre}service_rose WHERE receiveuid = '$userid' and receive_del=0");
	$receive_num = empty( $temp['num'] ) ? 0 : $temp['num'];

	//note 已发送的玫瑰总数
	$temp = $_MooClass['MooMySQL']->getOne("SELECT sum(case when num>=1 then 1 else 0 end) as num FROM {$dbTablePre}service_rose WHERE senduid = '$userid'  and send_del=0");
	$send_num = empty( $temp['num'] ) ? 0 : $temp['num'];

	require MooTemplate('public/service_rose_getsendrose', 'module');
	
}


//note 获得玫瑰花
function get_receive_rose() {
	global $_MooClass,$dbTablePre,$userid,$pagesize,$user_arr;
	
	//note 获取删除提交的变量
	$delrose = MooGetGPC('delrose','string');
	$delroseid = MooGetGPC('delroseid','array');
	//note 删除提交的数据
	if($delrose && count($delroseid)) {
		$ids = "'".implode( "','", $delroseid )."'";
		$time = time();
		$_MooClass['MooMySQL']->query("UPDATE  {$dbTablePre}service_rose SET receive_del=1,receive_deltime='{$time}', receivenum = 0 WHERE rid  IN ( $ids )");
		MooMessage("鲜花删除成功",'index.php?n=service&h=gift&t=igetrose','05');
	}
	//note 如果提交的数据不存在，或者是提交的时候没有选中
	if($delrose && !count($delroseid)) {
		MooMessage('请选择要删除的鲜花','index.php?n=service&h=gift&t=igetrose','01');
		exit;
	}
	
	//分页
	$page_per = 4;
	$page_url = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"]; 
	$page_url = preg_replace( "/(&page=\d+)/","",$page_url );
	$page_now = MooGetGPC( 'page', 'integer', 'G');
	
	$temp = $_MooClass['MooMySQL']->getOne("select count(1) as num FROM {$dbTablePre}service_rose WHERE receiveuid  = '$userid' and send_del=0",true);
	$item_num = $temp['num'];
	$page_num = ceil( $item_num / $page_per );
	if( $page_now > $page_num ) $page_now = $page_num;
	if( $page_now < 1 ) $page_now = 1;
	
	$start = ($page_now - 1) * $page_per;
	$results  = $_MooClass['MooMySQL']->getAll("SELECT * FROM {$dbTablePre}service_rose WHERE receiveuid = '$userid' and receive_del=0 ORDER BY receivetime desc LIMIT $start,$page_per");
	
	$roses=array();
	foreach($results as $k=>$v){
	    $send_user1=array();
	    $send_user2=array();
		$rose=array();

	    $send_user1 = leer_send_user1($v['senduid']);
		$send_user2 = leer_send_user2($v['senduid']);
		
		$rose['l']=$v;  
		$rose['s']=$send_user1;
		$rose['t']=$send_user2;
		$roses[$k]=$rose;
	}
	
	
	
	//note 已收到的玫瑰总数
	$temp = $_MooClass['MooMySQL']->getOne("SELECT sum(case when receivenum>=1 then 1 else 0 end) as num FROM {$dbTablePre}service_rose WHERE receiveuid = '$userid' and receive_del=0");
	$receive_num = empty( $temp['num'] ) ? 0 : $temp['num'];

	//note 已发送的玫瑰总数
	$temp = $_MooClass['MooMySQL']->getOne("SELECT sum(case when num>=1 then 1 else 0 end) as num FROM {$dbTablePre}service_rose WHERE senduid = '$userid' and send_del=0");
	$send_num = empty( $temp['num'] ) ? 0 : $temp['num'];

	require MooTemplate('public/service_rose_getreceiverose', 'module');
	
}

//控制部分
$t=MooGetGPC('t','string','G');
switch ($t) {
	//note 收到的鲜花列表
	case "igetrose" :
		get_receive_rose();
		break;

	//note 	发送的鲜花列表
	case "isendrose" :
		get_send_rose();
		break;
	
	//note 	获得更多的鲜花
	case "getmorerose" : 
		get_more_rose();
		break;
		
	//note 默认跳转到收到的鲜花列表
	default :
		get_receive_rose();
}

?>
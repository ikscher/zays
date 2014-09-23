<?php
include "module/andriod/function.php";
$name=$_GET['d'] = isset($_GET['d']) ? $_GET['d'] : '';
if(empty($name)){
	$a = $_GET['n'] = isset($_GET['n']) ? $_GET['n']:'';
	if($a = 'andriod'){
		header("Location:http://".$_SERVER['SERVER_NAME']."/index.php?n=andriod&d=index&h=index");
	}
}


$names = array('login','index','register','search','bothblong','space','more','service','mail','shadow');

//模块判断
if( !in_array($name, $names) ){
    MooMessage('没有这个页面', "http://".$_SERVER['SERVER_NAME']."/index.php?n=andriod&d=index&h=index",'01');
}

//$uid =  $_GET['uid'] = isset($_GET['uid'])?$_GET['uid']:'';
//$and_uuid = isset($_GET['uuid'])? $_GET['uuid'] : '';
//if($uid){
//	$userid = $mem_uid = $memcached->get('uid_'.$uid);
//}
//check_online($userid, $and_uuid);
include_once(strtolower($name)."/index.php");

?>

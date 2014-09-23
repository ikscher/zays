<?php
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // 过去的时间

//note 加载框架配置参数
require './include/config.php';
//note 加载MooPHP框架
require '../framwork/MooPHP.php';
//note 包含共公方法
include './include/function.php';
include './include/ajax_function.php';
/*******************************************逻辑层(M)/表现层(V)*****************************************/

function get_class_children(){
	$parent = MooGetGPC('parent','integer','G');
	$select = MooGetGPC('select','integer','G');
	$sql = "SELECT * FROM {$GLOBALS['dbTablePre']}test_class WHERE `parent`='{$parent}' ";
	$temp = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	if(empty($temp)){
		echo 'no';exit;
	}
	
	$str = "<option value=''>请选择</option>";

	foreach($temp as $v){
		$sel = '';
		if($v['tc_id'] == $select){ $sel = ' selected="selected"';}
		$str .= "<option value='{$v['tc_id']}'{$sel}>{$v['test_name']}</option>";
	}
	echo $str;
}

/***********************************************控制层(C)*****************************************/
$h=MooGetGPC('h','string');
//判断是否登录
adminUserInfo();
if(empty($GLOBALS['adminid'])){
	echo 'nologin';exit;
}
switch($h){
	case 'class_children':
		get_class_children();
	break;
}
?>
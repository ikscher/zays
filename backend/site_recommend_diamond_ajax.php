<?php
/**
*	客服后台的网站管理下AJAX调用
*
*/

//note 加载框架配置参数
require './include/config.php';
//note 加载MooPHP框架
require '../framwork/MooPHP.php';

//note 包含共公方法
include './include/function.php';

function searchuid(){
	$uid = MooGetGPC('uid','integer','G');
	$sql = "SELECT s.uid,s.nickname,s.birthyear,s.marriage,s.s_cid,b.mainimg FROM {$GLOBALS['dbTablePre']}members_search as s left join {$GLOBALS['dbTablePre']}members_base as b on s.uid=b.uid  WHERE s.uid='{$uid}'";
	$res = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	if($res){
		$img = MooGetphotoAdmin($res['uid'],'small');
		if($img) array_push($res,$img);
		else 	array_push($res,'无');
		echo json_encode(array(0=>$res));
	}else{
		echo json_encode(array(0=>'no'));
	}
}



/***************************************控制层(V)***********************************/
$n=MooGetGPC('n','string');
//判断是否登录
adminUserInfo();
if(empty($GLOBALS['adminid'])){
	echo 'nologin';exit;
}

switch($n){
	case 'searchuid':
		searchuid();
	break;
	default:
		searchuid();
	break;
}

?>
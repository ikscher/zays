<?php






/***************************************控制层****************************************/ 
$pagesize = 10;         //服务中心信息每页显示的条目数

include "./module/service/function.php";
include "./ework/include/config.php";

$back_url='index.php?'.$_SERVER['QUERY_STRING'];

$serverid = Moo_is_kefu();

if (isset($_MooCookie['kefu'])){ 
	$kefu = MooAuthCode($_MooCookie['kefu'], 'DECODE');
	if($kefu) list($hzn,$adminid) = $arr = explode("\t", $kefu);
}



$h = empty($_GET['h']) ? '' : $_GET['h'];
switch ($h){
	case 'main':
		require 'main.php';
		break;
	//编辑头像
	case 'album':
		require 'album.php';
		break;
	//修改资料
	case 'material':
		require 'material.php'; 
		break;
	case 'liker':
		require 'liker.php';
		break;
	case 'message':
		require 'message.php';
		break;
	case 'commission':
		require 'commission.php';
		break;
	case 'leer':
		require 'leer.php';
		break;
	default:
		require 'main.php';
		break;
		
		
}
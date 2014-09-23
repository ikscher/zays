<?php

 

error_reporting(E_ALL ^ E_NOTICE);

header('Content-Type: text/html; charset=UTF-8');
date_default_timezone_set('PRC');
/*header("Content-type: text/html; charset=utf-8");
header("Expires: Mon 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D d M Y H:i:s") . " GMT");
header("Cache-Control: no-store no-cache must-revalidate");
header("Cache-Control: post-check=0 pre-check=0",false);
*/
//note 配置文件
require './include/config.php';
define('FROMEWORK', true);
//note 加载Moo框架
require '../framwork/MooPHP.php';

//note 加载后台共用方法文件
require './include/function.php';

//note 权限菜单分类配置文件
require './include/menu.inc.php';

//如果脚本中断，关闭数据库连接
register_shutdown_function(array($_MooClass['MooMySQL'],'close'));

//note 取得动作和菜单变量     
$menu = MooGetGPC('menu', 'string', 'G');
$action = MooGetGPC('action', 'string', 'G');
$h = MooGetGPC('h', 'string', 'G');

//判断是否登录
adminUserInfo();




//允许的单ip列  '221.123.138.202',
$allow_ip = array('123.138.48.99','220.115.131.239','220.115.130.46','60.166.218.207','223.243.41.244','114.97.99.181');
//允许访问ip地址段
//$allow_ip_segment = array('10.20.21.100-10.20.21.254'); //例array('218.59.80.90-218.59.80.100','60.12.1.1-60.12.1.100')
$cur_ip = GetIP();



if(!in_array($cur_ip,$allow_ip))
{
    if(empty($memcached->get('token'))){
    	$t=MooGetGPC('token','string')?MooGetGPC('token','string'):'';
		$memcached->set('token',$t,0,200000);
	}
	
	if(empty($memcached->get('token')) || $memcached->get('token')!='zays87920'){
		echo 'GET OUT -- '.$cur_ip;
		exit;
	}
}




if(($action != 'login' || empty($action))&&empty($GLOBALS['adminid'])){
    $url='index.php?action=login&h=index';
    MooMessageAdmin('请登录',$url,1);
}
 
/*
//允许的单ip列  '221.123.138.202',
$allow_ip = array('60.166.28.54','114.80.200.237','220.178.112.174','61.190.44.98','127.0.0.1','220.178.123.74','221.130.166.242','120.193.108.166','61.190.22.14','61.190.10.254','124.73.152.192','114.70.200.172','112.228.212.24','114.70.200.169','120.65.131.172','124.73.146.201','114.96.190.146','60.166.30.182','58.242.215.101','114.96.155.154','183.160.111.106','36.4.165.244');
//允许访问ip地址段
$allow_ip_segment = array('10.20.20.100-10.20.20.254'); //例array('218.59.80.90-218.59.80.100','60.12.1.1-60.12.1.100')
$cur_ip = GetIP();

if(!in_array($cur_ip,$allow_ip)){
	$allow_ip_bool = false;
	foreach($allow_ip_segment as $value){
		$ip_segment_arr = explode('-', $value);
		if(count($ip_segment_arr)==2 && !empty($ip_segment_arr[0]) && !empty($ip_segment_arr[1])){
			$cur_ip_long = ip2long($cur_ip);
			if(ip2long($ip_segment_arr[0])<=$cur_ip_long && $cur_ip_long<=ip2long($ip_segment_arr[1])){
				$allow_ip_bool = true;				
			}
		}
		if($allow_ip_bool) break;
	}
	
	
    if(!$allow_ip_bool && (strpos($cur_ip,'192.168') === false)){
	    echo 'GET OUT -- '.$cur_ip;exit;
	}
   
	
} 
*/
   /* $cur_ip = GetIP();
    //$cur_ip=$_GET['ip'];
    if(!in_array($cur_ip,$allow_ip)){
        if(strpos($cur_ip,'60.166.') === FALSE){
           echo 'GET OUT';exit;        
        }
    }*/


	
//切换身份
$chang_user = check_change_identity();

//note 所有的模块名
$actionlist=array(
    'site','site_lovetest','site_lovequestion','site_lovetype','site_loveclinic','site_media','site_diamond',
    'system_adminaction','system_adminuser','system_admingroup','system_adminlog','system_adminteam','system_adminmanage',
    'admin','login','check','allmember','vipuser','active_SendMMSStat','active_email','active_uplink','active_commission',
	'active_websms','active_leer','active_rose','check_memberinfo','diamond_music','active_liker','active_chat','active_bind',
	'myuser','other','other_rightbottom','financial_telphonetime','financial_orderok','financial_ordertotal','financial_totalwage',
	'other_members_transfer','hntest','site_story','site_skin','financial_tele_info','financial_wordbook','financial_urgencyclient',
	'financial_assert','financial_ahtv','financial_ahtv_reguser','site_recommend_diamond','financial','system_adminmembers',
	'active_cooperation','active_activity','active_activity_acceding','activity','activity_member','activity_kefu','text_show',
	'matchmaker','chat','financial_joinstatistics','lovestation');

$mtime = explode(' ', microtime());
$starttime = $mtime[1] + $mtime[0];

$action = $action == '' ? 'admin' : $action;

//note 判断页面是否存在,不存在弹出提示，否则加载相应模块
if(!in_array($action,$actionlist)){
    MooMessageAdmin('您要打开的页面不存在！','index.php?action=admin');
}else{
    require $action.'.php'; 
}

$mtime2 = explode(' ', microtime());
$endtime = $mtime2[1] + $mtime2[0];
echo '页面执行时间：'.($endtime-$starttime);
//$_MooClass['MooMySQL']->close();
?>

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

//允许的IP访问后台
require './include/allow_ip.php';

//如果脚本中断，关闭数据库连接
register_shutdown_function(array($_MooClass['MooMySQL'],'close'));

//note 取得动作和菜单变量     
$menu = MooGetGPC('menu', 'string', 'G');
$action = MooGetGPC('action', 'string', 'G');
$h = MooGetGPC('h', 'string', 'G');

//判断是否登录
adminUserInfo();




//允许的单ip列  '221.123.138.202',
$allow_ip = array('127.0.0.1');
//允许访问ip地址段
//$allow_ip_segment = array('10.20.21.100-10.20.21.254'); //例array('218.59.80.90-218.59.80.100','60.12.1.1-60.12.1.100')
$cur_ip = GetIP();



if(!in_array($cur_ip,$allow_ip)){   
    $token=$_MooCookie['token'];
	if(empty($token) || $token!='vip999'){
		$t=$_GET['token']?$_GET['token']:'';
		MooSetCookie('token',$t,21600);
	}
	
	$token=$_MooCookie['token'];
   
	
	if(empty($token) || $token!='vip999'){
		echo '你当前的ip:  '.$cur_ip;
		exit;
	}
}

if(isset($_GET['token']) && $_GET['token']=='vip999' && isset($_GET['ip']) ){	
	$keys=array(0=>'a',1=>'b',2=>'c',3=>'d',4=>'e',5=>'f',6=>'g',7=>'h',8=>'i',9=>'j',10=>'k',11=>'l',12=>'m',13=>'n',14=>'o',15=>'p',16=>'q',17=>'r',18=>'s',19=>'t',20=>'u',21=>'v',22=>'w',23=>'x',24=>'y',25=>'z');
	
	$pattern='/^((([1-9]|([1-9]\d)|(1\d\d)|(2([0-4]\d|5[0-5])))\.)(([1-9]|([1-9]\d)|(1\d\d)|(2([0-4]\d|5[0-5])))\.){2}([1-9]|([1-9]\d)|(1\d\d)|(2([0-4]\d|5[0-5])))(\,)?)+$/';
	
	if(preg_match($pattern, $_GET['ip'])) {
		$file='include/allow_ip.php';
		$ip_=explode(',',$_GET['ip']);
		$ip=array();
		foreach($ip_ as $k=>$v){
			$ip[$keys[$k]]=$v;
		}
		$new_ip=array_merge($allow_ip,$ip);
		
		$str= "<?php\n\n  \$allow_ip= ".var_export($new_ip,true).";\n\n?>";
		file_put_contents($file, $str);
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

/* $mtime2 = explode(' ', microtime());
$endtime = $mtime2[1] + $mtime2[0];
echo '页面执行时间：'.($endtime-$starttime); */
//$_MooClass['MooMySQL']->close();
?>

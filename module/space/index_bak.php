<?php
/*  
	Copyright (c) 2009 

	$Id: index.php 399 2009-10-14 07:11:04Z techice $
*/

$name = empty($_GET['n'])?'index':$_GET['n'];
//note 加载框架配置参数
require 'config.php';

//note 加载框架
require 'framwork/MooPHP.php';

//允许的方法
$names = array(
	'login', 'index', 'register', 'lostpasswd', 'inputpwd','myaccount', 'viewspace', 'relatekw', 'ajax', 
	'seccode', 'sendmail', 'material', 'search', 'service', 'payment','safetyguide','lovestyle','loveing','story',
	'about','return','invite','vote','profile','recommend', 'crontab', 'pop','clinic','space','hnintro','paymenttest'
);


//获取推广参数
MooGetFromwhere();
//用户信息
MooUserInfo();
$user_arr=$user=UserInfo();
$uid = $userid =$MooUid;
//模块判断
if( !in_array($name, $names) ){
	MooMessage('没有这个页面', 'index.php','01');
}

//时间相关
if($uid){    
    //更新COOKIE 成活时间
    MooUpateCookie($uid);
	$new_email_num=header_show_total($uid);
}
//获取皮肤名称
$style_uid = MooGetGPC('uid', 'integer', 'G');
$skiname = MooGetGPC('skiname','string','G');
//新邮件数


if( !empty($style_uid) && $style_uid != $uid ){	//采用他人的样式
	if(MOOPHP_ALLOW_FASTDB){
		$style_user_arr = MooFastdbGet('members','uid',$style_uid);
	}else{
		$style_user_arr = $_MooClass['MooMySQL']->getOne("select * from " . $dbTablePre . "members where uid='" . $style_uid . "' and is_lock = '1'");		
	}
}else{
	$style_uid = $uid;
	$style_user_arr = $user_arr;
}
$style_name = 'default';
include_once("module/".strtolower($name)."/index.php");

$_MooClass['MooMySQL']->close();
@ $memcached->close();
@ $fastdb->close();

?>
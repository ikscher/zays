<?php
function sendemail(){
	global $_MooClass;
	$start=$_GET['s']?$_GET['s']:0;
	$userMsg = $_MooClass['MooMySQL']->getAll("select  uid,username from web_members_search where usertype=2 limit $start,100",0,0,0,true);
	if(empty($userMsg)){exit('ok');}
	//$userMsg = array(array('uid'=>1599408,'username'=>'1254100129@qq.com'));
	//$ua =array();
	//$a = MooReadFileadmin('public/mailtamp/template1.html', false); var_dump($a);
	foreach($userMsg  as $u){
		$verifycode = strtoupper(md5('hongniangwang'.$u['uid'].$u['username']));
		$ToBody = "点击激活：<a href='http://".MOOPHP_HOST."/index.php?n=myaccount&h=activeaccount&y=active&p=".$u['username']."&uid=".$u['uid']."&verifycode=".$verifycode."'>http://".MOOPHP_HOST."/index.php?n=myaccount&h=activeaccount&y=active&p=".$u['username']."&uid=".$u['uid']."&verifycode=".$verifycode."</a>";		
			
		//exit($ToBody);
		$ToAddress = $u['username'];
		$ToSubject = "真爱一生网账户密码激活修改通知";
		//发送邮件
		$end = MooSendMail($ToAddress,$ToSubject,$ToBody,"public/system/mailtamp/template1.html",$is_template = true,$u['uid']);
	}
		$start+=100;
		echo '<html><head><meta http-equiv="refresh" content="2;url=?s='.$start.'&y=sendemail&h=activeaccount&n=myaccount"> </head><body></body></html>';

}
function sendemail_once(){
global $_MooClass;
	$start=$_GET['s']?$_GET['s']:0;
	$userMsg = $_MooClass['MooMySQL']->getAll("select  uid,username from web_members_search where usertype=1 limit $start,100",0,0,0,true);
	//$userMsg = array(array('uid'=>1599408,'username'=>'1254100129@qq.com'));
	if(empty($userMsg)){exit('ok');}
	//$ua =array();
	//$a = MooReadFileadmin('public/mailtamp/template1.html', false); var_dump($a);
	
	$ToBody  = "  尊敬的会员您好，真爱一生网最近进行了改版，新增了钻石会员，并且前期有些用户已经开始进行了尝试性使用，服务初期，我们进行优惠促销活动，原价1999元/6个月，现价1399元/6个月，如果您现在是，或者曾经是我们的高级会员，那么您还可以享受更多的优惠。我们对前50名升级的钻石会员采取以下优惠：1、如果您现在是高级会员升级钻石会员，将享受服务期顺延的方式，即从您升级钻石会员开始，我们将把您为服务完的高级会员的时间进行延长，就是6个月加上您剩余的高级会员的时间，并且您只需付两个价格的差价，即1100元即可。2、如果您以前是高级会员的，现在升级钻石会员，您也只需付差价，1100元即可享受钻石会员的服务。数量有限，希望各位尊敬的会员能抓住机会，为了自己明天的幸福，马上行动起来。钻石会员服务内容及详情，请登录真爱一生网 www.zhenaiyisheng.cc查看钻石会员介绍，或者直接拨打客服热线4006780405，直接找您的真爱一生进行了解。";
	
	foreach($userMsg  as $u){
		//$verifycode = strtoupper(md5('hongniangwang'.$u['uid'].$u['username']));
		
		//$ToBody = "点击激活：<a href='http://".MOOPHP_HOST."/index.php?n=myaccount&h=activeaccount&y=active&p=".$u['username']."&uid=".$u['uid']."&verifycode=".$verifycode."'>http://".MOOPHP_HOST."/index.php?n=myaccount&h=activeaccount&y=active&p=".$u['username']."&uid=".$u['uid']."&verifycode=".$verifycode."</a>";		
			
		//exit($ToBody);
		$ToAddress = $u['username'];
		$ToSubject = "真爱一生网推陈出新，新版闪亮登场，推出钻石会员服务";
		//发送邮件
		$end = MooSendMail($ToAddress,$ToSubject,$ToBody,$is_template = true,$u['uid']);
	}
		$start+=100;
		echo '<html><head><meta http-equiv="refresh" content="2;url=?s='.$start.'&y=sendemail_once&h=activeaccount&n=myaccount"> </head><body></body></html>';

}
function  active_email(){
global $_MooClass;
	$uid=$u['uid']=MooGetGPC('uid', 'string');
	$verifycode=MooGetGPC('verifycode', 'string');
	$username=$u['username']=MooGetGPC('p', 'string');
	if($verifycode==strtoupper(md5('hongniangwang'.$u['uid'].$u['username']))){
		$online_ip = GetIP();
		$t = time();
		$pass = md5('123456');
		$r = $_MooClass['MooMySQL']->getOne("select * from web_activelog where uid=$uid  limit 1",true);
		if($r['username']==$username){
			MooMessage("您已经激活过了", "index.php","05");
		}else{
			//$_MooClass['MooMySQL']->query("update web_members_search,web_members_login set password='$pass',usertype=1,regdate='$t',last_login_time = '$t',login_meb = login_meb+1,lastip='$online_ip',lastvisit='$t'  where uid='$uid'");
			$_MooClass['MooMySQL']->query("update web_members_search as s,web_members_login as l set s.password='$pass',s.usertype=1,s.regdate='$t',l.last_login_time = '$t',l.lastip='$online_ip',l.lastvisit='$t'  where s.uid='$uid' and l.uid='$uid'");
			searchApi('members_man members_women')->updateAttr(array('usertype','regdate'),array($uid=>array(1,$t)));
			$_MooClass['MooMySQL']->query("insert into web_activelog(uid,username,activetime) values('$uid','$username','$t')");
		}
		MooSetCookie('auth',MooAuthCode("$uid\t$pass",'ENCODE'),86400);
		MooSetCookie('username',$u['username'],time()+3600);
		if(MOOPHP_ALLOW_FASTDB){
			$user11 = MooFastdbGet('members_search', 'uid', $uid);
			$meb = $user11['login_meb'];
			$val_s = $val_l = array();
			$val_s['password'] = $pass;
			$val_s['usertype'] = 1;
			$val_s['regdate'] = $t;
			$val_l['last_login_time'] = $t;
			$val_l['login_meb'] = $meb + 1;
			$val_l['lMooFastdbUpdateastip'] = $online_ip;
			$val_l['lastvisit'] = $t;
			MooFastdbUpdate('members_search','uid',$uid, $val_s);//!!
			MooFastdbUpdate('members_login','uid',$uid, $val_l);
		}
		//$_MooClass['MooMySQL']->query("INSERT INTO `web_membersession` SET `username`= '$u[username]',`password`='$pass',`ip` = '$online_ip',`lastactive` = '$t',`uid` = '$uid'");
		MooMessage("验证激活成功", "index.php","05");
	}else{
		MooMessage("参数有误！请注册", "index.php","02");
	}
}

if(!in_array(MooGetGPC('y', 'string'),array('sendemail','sendemail_once','active'))){
exit('error');
}

switch(MooGetGPC('y', 'string')){
	case 'sendemail': sendemail(); break;
	case 'active': active_email(); break;
	case 'sendemail_once': sendemail_once(); break;
}
?>
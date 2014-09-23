<?php
/**
@param 通过地址栏用户名和密码登陆
@param return null
*/

function verifyemail_login($uid='',$pwd=''){ //邮箱验此函数出错，有可能是	MooFastdbUpdate
	global $_MooClass,$dbTablePre,$userid;
//	if($userid){
//		return;
//	}
	/* $uid = MooGetGPC('uid','string',G);
	$pwd = MooGetGPC('t','string',G); */
	//查找表
	$sql="SELECT * FROM {$dbTablePre}members_search WHERE uid='$uid' and password='$pwd'";

	$user = $_MooClass['MooMySQL']->getOne($sql,true);
	
	//note 验证通过
	if($user) {
		MooSetCookie('auth',MooAuthCode("$user[uid]\t$user[password]",'ENCODE'),86400);
		//note 写入session表需要的字段值
		$online_ip = GetIP();
		$lastactive = $GLOBALS['timestamp'];
		$uid = $user['uid'];
		//note 更新用户的最近登录ip和最近登录时间
		$updatesqlarr = array(
			'lastip' => $online_ip,
			'lastvisit' => $lastactive	
		);
		$wheresqlarr = array(
			'uid' => $uid
		);
		updatetable("members_search",$updatesqlarr,$wheresqlarr);
		
		if(MOOPHP_ALLOW_FASTDB){
			$user11 = array();
			$user11['lastip'] = $online_ip;
			$user11['lastvisit'] = $lastactive;
			MooFastdbUpdate('members_search','uid',$uid, $user11); //!!
		}
		//note 先删除表里面已存在对应用户的session
		//$_MooClass['MooMySQL']->query("DELETE FROM `{$dbTablePre}membersession` WHERE `uid` ='$uid'");	
		//$_MooClass['MooMySQL']->query("REPLACE INTO `{$dbTablePre}membersession` SET `username`= '$user[username]',`password`='$user[password]',`ip` = '$online_ip',`lastactive` = '$lastactive',`uid` = '$uid'");
	}
}

/**
@param （忘记密码）通过地址栏用户名和新密码登陆
@param return null
*/
function find_pwd(){
	global $_MooClass,$dbTablePre,$userid,$_MooCookie;
//	if($userid){
//		return;
//	}
	$uid = MooGetGPC('uid','string',G);
	$pwd = MooGetGPC('upwd','string',G);
/*	echo md5($uid).'<br>';
	echo md5($pwd);
	print_r($_COOKIE);
	exit;
*/
	if($_MooCookie['findpwd'] == md5($pwd) && md5($uid) == $_MooCookie['finduser']){
		$newpwd = md5(base64_decode($pwd));
		//note 修改密码
		//$_MooClass['MooMySQL']->query("update {$dbTablePre}members set password = '{$newpwd}' where uid = '{$uid}'");
		//if(MOOPHP_ALLOW_FASTDB){
//			MooFastdbUpdate('members','uid',$uid);
//		}
		MooSetCookie('auth',MooAuthCode("{$uid}\t{$newpwd}",'ENCODE'),86400);
		//note 写入session表需要的字段值
		$online_ip = GetIP();
		$lastactive = $GLOBALS['timestamp'];
		//$uid = $user['uid'];
		//note 更新用户的最近登录ip和最近登录时间
		$updatesqlarr = array(
			'lastip' => $online_ip,
			'lastvisit' => $lastactive,
			'password' => $newpwd
		);
		$wheresqlarr = array(
			'uid' => $uid
		);
		updatetable("members_search",$updatesqlarr,$wheresqlarr);
		if(MOOPHP_ALLOW_FASTDB){
			$val = array();
			$val['lastip'] = $online_ip;
			$val['lastvisit'] = $lastactive;
			$val['password'] = $newpwd;
			MooFastdbUpdate('members_search','uid',$uid, $val);//!!
		}
		//note 先删除表里面已存在对应用户的session
		//$_MooClass['MooMySQL']->query("DELETE FROM `{$dbTablePre}membersession` WHERE `uid` ='$uid'");	
		//$_MooClass['MooMySQL']->query("REPLACE INTO `{$dbTablePre}membersession` SET `username`= '$user[username]',`password`='$user[password]',`ip` = '$online_ip',`lastactive` = '$lastactive',`uid` = '$uid'");
		return 1;
	}
	return 0;
}

//获得会员的资料完整度 makui
function getUserinfo($member_inf){
	global $dbTablePre,$_MooClass;
	//print_r($member_inf);
	if(MOOPHP_ALLOW_FASTDB){
		$mem_field = MooFastdbGet('members_base','uid',$member_inf['uid']);	
	}else{
		$mem_field = $_MooClass['MooMySQL']->getOne("SELECT * FROM {$dbTablePre}members_base as mf WHERE uid='{$member_inf['uid']}'",true);
	}
	$member_info_all=array_merge($member_inf,$mem_field);
	//$user_info_num=getUserinfo($member_info);
	
	$info_array=array('nickname','telphone','gender','brithyear','brithmonth','brithday','province','city','marriage','education','salary','house','children','height','oldsex','mainimg','truename','nature','weight','body','naimalyear','constellation','bloodtype','hometownprovince','hometowncity','nation','religion','finishschool','family','language','smoking','drinking','occupation','vehicle','corptype','wantchildren','fondfood','fondplace','fondactivity','fondsports','fondmusic','fondprogram','blacklist','qq','msn','currentprovince','currentcity','friendprovince');
	foreach($info_array as $v){
		if($member_info_all[$v]){
			$i++;
		}
		
	}
	return (int)(($i/48)*100);
	//echo count($info_array);
}
?>
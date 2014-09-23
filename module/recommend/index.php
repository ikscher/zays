<?php 
function index_index(){
	global $_MooClass,$dbTablePre,$userid,$memcached;
    $uid=MooGetGPC('uid','integer'); 
	$puid=MooGetGPC('puid','integer'); 
    if(!$uid){
	  	if(!$userid){
	  		MooMessage('没有找到此会员','register.html');
	  	}
		else{
		    MooMessage('没有找到此会员','index.php?n=search');
		}
	}

	if(MOOPHP_ALLOW_FASTDB){
		$user = MooFastdbGet('members','uid',$uid);
		$user = array_merge($user,MooFastdbGet('memberfield','uid',$uid));
		$c = MooFastdbGet('choice','uid',$uid);
	}else{
		$user = $_MooClass['MooMySQL']->getOne("SELECT * FROM {$dbTablePre}members where uid='$uid'");
		$user2 = $_MooClass['MooMySQL']->getOne("SELECT * FROM {$dbTablePre}memberfield where uid='$uid'");
		$user = array_merge($user,$user2);
		unset($user2);
		$c = $_MooClass['MooMySQL']->getOne("SELECT * FROM {$dbTablePre}choice as mf WHERE uid='$uid'");
	}
	//$tuijian=$_MooClass['MooMySQL']->getAll("select * from {$dbTablePre}members where gender='$user[gender]' and is_vote='1' order by uid limit 7");
	$en_year = $user['birthyear'];
	$st_year = $en_year - 2;
	$en_year += 2;
	$sql_new="SELECT uid,nickname,gender,birthyear,mainimg,images_ischeck FROM {$dbTablePre}members where birthyear between $st_year and $en_year and gender='$user[gender]' and images_ischeck=1 and is_lock=1 order by regdate desc limit 6";
	$tuijian=$_MooClass['MooMySQL']->getAll($sql_new);
	if(!$user){
		if(!$userid){
	  		MooMessage('没有您查看的会员','register.html');
	  	}
		else{
		    MooMessage('没有您查看的会员','index.php?n=search');
		}
	}else{
		MooSetCookie('puid',$puid,time()+3600,'');
		include MooTemplate('public/recommend_index', 'module');
	}
}

index_index();
?>
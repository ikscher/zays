<?php
/*
 * Created on 2009-10-26
 *
 * To change the template for this generated file go to*
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
include "./module/{$name}/function.php";

function index_index(){
	global $_MooClass,$dbTablePre,$uid,$user,$last_login_time;
	global $user_arr;

	// 参数获取
	$p=MooGetGPC('p','integer','G');
	$sex=MooGetGPC('sex', 'int', 'G');
	$age=MooGetGPC('age','integer','G');
	if (!in_array($age, array(1, 2, 3, 4))) {
		$age = 1;
	}

	if (!in_array($sex, array(1, 2))) {
		switch($user['gender']){
			case '0':
				$sex=1;
				break;
			case '1':
				$sex=0;
				break;
		}
	} else {
		$sex -= 1;
	}
	$last_arr = get_lastvid();
	if(isset($last_arr['lastvid'])) $lastid = $last_arr['lastvid'];
	if(isset($last_arr['t3'])) $vote_key = unserialize($last_arr['t3']);

	$last_mem = get_lastmembers();//最后一条ID
	
	$last_c = $_MooClass['MooMySQL']->getOne("select count(*) as c, sum(counts) as s from {$dbTablePre}vote where vid='$last_mem[uid]'");
	if(isset($last_c['c'])){
		$counts_s=$last_c['c'] ? round(($last_c['s']/$last_c['c']),1) : 0;
		$counts_c=$last_c['c'];
	}
	$member_uid_list = getMemberByVote($user_arr, $sex, $age, $vote_key);
	while(empty($member_uid_list[1])) {
		$age++;
		if ($age > 4) {
			break;
		}
		$member_uid_list = getMemberByVote($user_arr, $sex, $age, $vote_key);
	}
//
//	if (empty($member_uid_list[1])) {
//		if ($sex == 0) {
//			$sex = 1;
//		} else {
//			$sex = 0;
//		}
//		$age = 0;
//		while(empty($member_uid_list[1])) {
//			$age++;
//			if ($age > 4) {
//				break;
//			}
//			$member_uid_list = getMemberByVote($user_arr, $sex, $age, $vote_key);
//		}
//	}
	if(empty($member_uid_list[1])){
		MooMessage("暂无等待您评分的会员",'index.php?n=service');
	}
	// 被评论会员
	$vote_mem = getVoteMembers($member_uid_list[1][0]);
	// 更新操作的key
	$file = $age . "_" . $sex . "_" . $user_arr["province"] . "_" . $user_arr["city"];
	if(isset($member_uid_list[0])) $vote_key[$file] = $member_uid_list[0];
	if (count($member_uid_list[1]) == 1) {
		unset($vote_key[$file]);
	}
	
	$sql = "update " . $dbTablePre . "members_action set t3 = '" . serialize($vote_key) . "' where uid = '" . $uid . "'";
	$_MooClass['MooMySQL']->query($sql);

	unset($member_uid_list[1][0]);
	if (count($member_uid_list[1]) > 0) {
		$uid_list = implode(',', $member_uid_list[1]);
		$member_list = get4members($uid_list);
		$pic_4 = array();
		foreach ($member_uid_list[1] as $tmp) {
			foreach ($member_list as $tmp1) {
				if ($tmp == $tmp1["uid"]) {
					$pic_4[] = $tmp1;
					continue;
				}
			}
		}
	}
	$sex += 1;
	include MooTemplate('public/vote', 'module');
}

function vote(){
	global $user_arr;
	global $_MooClass,$dbTablePre,$uid,$last_login_time;
	$sex=MooGetGPC('sex','integer','G');
	$age=MooGetGPC('age','integer','G');
	$vid=MooGetGPC('uid','integer','G');
	$counts=MooGetGPC('counts','integer','G');
	$_MooClass['MooMySQL']->query("insert into {$dbTablePre}vote (uid,vid,counts) values('$uid','$vid','$counts')");
	//members_action($vid);
	header("Location:index.php?n=vote&sex=".$sex."&age=".$age);
}

$h = MooGetGPC('h', 'string');
$back_url='index.php?'.$_SERVER['QUERY_STRING'];
if(!$uid) {header("location:login.html");}  //&back_url=".urlencode($back_url)
switch ($h) {
	case 'vote_into':
		vote();
		break;
	default:
		index_index();
		break;
}
?>
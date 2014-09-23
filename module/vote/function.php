<?php 

//���û�������¼

function members_action($vid){
	global $_MooClass,$dbTablePre,$uid;
    $r=$_MooClass['MooMySQL']->getOne("select * from {$dbTablePre}members_action where uid='$uid' limit 1"); 
	$date=time();
    if($r){
	  
	  if($r['vote_id']){
	    $vote_id=$r['vote_id'].','.$vid;
	  
	  }
	  else {
	    
	   $vote_id=$vid;
	  }
	  
	  
	 $_MooClass['MooMySQL']->query("update {$dbTablePre}members_action set lastvid='$vid',lasttime='$date',vote_id='{$vote_id}'  where uid='$uid'");  
	}
	else{
	 $_MooClass['MooMySQL']->query("insert into {$dbTablePre}members_action (uid,lastvid,lasttime,vote_id) values('$uid','$vid','$date','$vid') ");
	}
	
}

//ȡ�û�������۵��û�ID
function get_lastvid(){
	global $_MooClass,$dbTablePre,$uid;
	$r = $_MooClass['MooMySQL']->getOne("select lastvid, t3 from {$dbTablePre}members_action where uid='$uid'");
	
	if (empty($r)) {
		$sql = "insert into " . $dbTablePre . "members_action (uid) values (" . $uid . ")";
		$_MooClass['MooMySQL']->query($sql);
		return array();
	}
	return $r;
}

//ȡ�����һ��
function get_lastmembers($lastvid=""){
	global $_MooClass,$dbTablePre,$uid;
	
	$sql = "select vid from " . $dbTablePre . "vote where uid = '" . $uid . "' order by id desc limit 1";
	$vote_uid = $_MooClass['MooMySQL']->getOne($sql);
	
	if (count($vote_uid) > 0) {
		if(MOOPHP_ALLOW_FASTDB){
			$user = MooFastdbGet('members_search','uid',$vote_uid["vid"]);
			$user2 = MooFastdbGet('members_base','uid',$vote_uid["vid"]);
			if(is_array($user) && is_array($user2))
				$user = array_merge($user,$user2);
			elseif(!is_array($user) && is_array($user2))
				$user = $user2;
			return $user;
		}else{
			$sql = "select s.*,b.mainimg from " . $dbTablePre ."members_search s left join ".$dbTablePre."members_base b on s.uid=b.uid where s.uid = '" . $vote_uid["vid"] . "'";
			return $_MooClass['MooMySQL']->getOne($sql);
		}
	}
	return array();
}

//ȡ��һ�����۵��û���Ϣ
function getVoteMembers($uid){
	global $_MooClass,$dbTablePre;
	if(MOOPHP_ALLOW_FASTDB){
		$user = MooFastdbGet('members_search','uid',$uid);
		$user2 = MooFastdbGet('members_base','uid',$uid);
		$u_introduce = MooFastdbGet('members_introduce','uid',$uid);
		if(empty($u)){$u=array();}
		$user = array_merge($user,$user2,$u_introduce);
		return $user;
	}else{
		$sql="select a.uid,c.mainimg,a.gender,a.images_ischeck,b.introduce  
		          from {$dbTablePre}members_search a
		          left join  {$dbTablePre}members_base c  on (a.uid=c.uid) 
		          left join {$dbTablePre}members_introduce b on  (a.uid=b.uid) 
		          where  a.uid = '" . $uid . "'".$sqls." limit 1";
		return   $_MooClass['MooMySQL']->getOne($sql); 
	}
}
/*
function get_votemembers2($p){
   global $_MooClass,$dbTablePre,$uid;
 
  				 return $_MooClass['MooMySQL']->getOne("select a.uid,a.mainimg,b.introduce  from {$dbTablePre}members a,{$dbTablePre}choice b where a.uid=b.uid and a.uid='$p'  limit 1");
        
}
*/
//ȡ��4��Ҫ���۵��û�ͼƬ
function get4members($uid_list){
	global $_MooClass,$dbTablePre;
   
	if(MOOPHP_ALLOW_FASTDB){
		$uid_list = explode(',',$uid_list);
		//$user = array();
		foreach($uid_list as $v){
			$res_search_arr = MooFastdbGet('members_search','uid',$v);
			$res_base_arr = MooFastdbGet('members_base','uid',$v);
			if(!is_array($res_search_arr)) $res_search_arr=array();
			if(!is_array($res_base_arr)) $res_base_arr=array();
			$res_all = array_merge($res_search_arr,$res_base_arr);
			$user[] = $res_all;
		}
		return $user;
	}else{
		$sql="select s.uid,b.mainimg,s.images_ischeck,s.gender 
		      	from {$dbTablePre}members_search s 
		      	left join {$dbTablePre}members_base b on s.uid=b.uid
		      	where s.uid in (" . $uid_list . ") limit 4";
		return $_MooClass['MooMySQL']->getAll($sql,false,true,3600);
	}
}

/**
 * ��ȡҪ���5���
 * param $user_arr array
 * param $sex int
 * param $age int
 * param $vote_key array
 */
function getMemberByVote($user_arr, $sex, $age, $vote_key) {
	global $user_arr,$memcached;
	$file = $age . "_" . $sex . "_" . $user_arr["province"] . "_" . $user_arr["city"];
	//echo $file;exit;
// 	$cache_file = MOOPHP_DATA_DIR.'/cache/cache_' . $file . '.php';
	
	$data = array();
	// ��ȡ����ֵ
	if (!$data = $memcached->get($file)){
		updateVoteFeel("", "", $user_arr["province"], $user_arr["city"], $age, $sex);
		$data = $memcached->get($file);
	}
	
//	if (empty($data)) {
//		// ���ǹ���ʱ��Ĭ�ϸ��Ϻ�10103000
//		if ($user_arr["province"] == 2 && substr($user_arr["city"], 3) == 0) {
//			$default_city = 10103000;
//			$file = $age . "_" . $sex . "_" . $default_city;
//			$cache_file = MOOPHP_DATA_DIR.'/cache/cache_' . $file . '.php';
//			
//			if (file_exists($cache_file) && filemtime($cache_file) + 86400 > time()) {
//				$data = require_once($cache_file);
//			} else {
//				updateVoteFeel("", "", $default_city, 0, $age, $sex, $file);
//				
//				$data = @require_once($cache_file);
//			}
//		}
//	}
	if (isset($vote_key[$file])) {
		$return[1] = array_slice($data, 1 + array_search($vote_key[$file], array_keys($data)), 5);
	} else {
		$return[1] = array_slice($data, 0 , 5);
	}
	if(isset($return[1][0])){
		$key = array_search($return[1][0], $data);
		$return[0] = $key;
	}
	return $return;
}
?>
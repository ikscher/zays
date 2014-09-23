<?php
/*
 * 生成加密uid,做为andriod需要的唯一随机id（uuid）*/
function user_md5_id($uid){
	$str = "hongniang";
	if(isset($uid) && !empty($uid)){
		$time = time();
		$uuid = MD5($uid.$time.$str);
		return $uuid;
	}
	
}

/*
 * 判断是否成功合法成功登录
 * @parm $and_uuid 唯一随机id*/
function check_uuid($and_uuid,$mem_uid){
	global $uuid,$memcached;
	if($mem_uid){
		$uuid = $memcached->get ( 'uuid_'.$mem_uid);
	}
	$error = array(); 
	if($uuid == $and_uuid && $uuid!='' && $and_uuid!=''){
		return true;
	}else{
		return false;
	}
}

/*
 * 记录用户在线
 * @parm $userid  登录用户id
 * @and_uuid   登录用户uuid*/
//function check_online($userid,$and_uuid){
//	global $uuid,$userid,$memcached;
//	if($userid){
//		$uuid = $memcached->get('uuid_'.$userid);
//	}
//	if($uuid == $and_uuid && $uuid!='' && $and_uuid!=''){
//		$memcached->set('uuid_'.$userid, $uuid, 0, 1800);
//		$memcached->set('uid_'.$userid, $userid, 0, 1800);
//	}
//}

/*
 * 返回正确数据或错误原因
 * @parm $data 数据或错误原因
 * @parm boolean $type true:数据；false:错误*/
function return_data($data ,$type = true){
	$return = array();
//    $data = $data[0];
	if($type == true){   
		$return['return'] = "true";  
		$return['return_content'] = $data;

	}else{
		$return['return'] = "false";
		$return['error'] = $data;
	}
	$return = json_encode($return);
	return $return;
}
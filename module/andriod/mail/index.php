<?php
include "module/andriod/function.php";


   
//note 已收到秋波的列表
function getmyleers() {
	global $_MooClass,$dbTablePre,$userid,$pagesize,$user_arr,$memcached;
	$uid =  $_GET['uid'] = isset($_GET['uid'])?$_GET['uid']:'';
	if($uid){
		$userid = $mem_uid = $memcached->get('uid_'.$uid);
	}
	$and_uuid = isset($_GET['uuid'])? $_GET['uuid'] : '';
	$checkuuid = check_uuid($and_uuid,$userid);
    if(!$checkuuid){
    	$error = "uuid_error";
    	echo return_data($error,false);exit;	
    }
	//分页
	if($userid){
		$page_per = 10;
		$page_url = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"]; 
		$page_url = preg_replace( "/(&page=\d+)/","",$page_url );
		$page_now = MooGetGPC( 'page', 'integer', 'G');
		
		$temp = $_MooClass['MooMySQL']->getOne("select count(1) as num FROM {$dbTablePre}service_leer WHERE receiveuid  = '$userid' and receive_del=0");
		$item_num = $temp['num'];
		$page_num = ceil( $item_num / $page_per );
//		if( $page_now > $page_num ) $page_now = $page_num;
//		if( $page_now < 1 ) $page_now = 1;
		//读数据
		$start = ($page_now - 1) * $page_per;
		$leer  = $_MooClass['MooMySQL']->getAll("SELECT senduid,stat FROM {$dbTablePre}service_leer WHERE receiveuid = '$userid' and receive_del=0  ORDER BY receivetime desc LIMIT $start,$page_per");
		if(empty($leer)){
			$errors = "empty";
			echo return_data($errors);exit;
		}
		$sendid = array();
		if(!empty($leer)){
			foreach ($leer as $k =>$v){
				$sendid[] = $v['senduid'];
			}
		}
		$send_uid = implode(',', $sendid);
		$senduser = $_MooClass['MooMySQL']->getAll("select s.uid, s.nickname,s.birthyear,s.marriage,s.education,s.province,s.city,s.salary, b.mainimg,l.* from {$dbTablePre}members_search s left join {$dbTablePre}members_base b on s.uid=b.uid left join {$dbTablePre}service_leer l on s.uid=l.senduid where s.uid in($send_uid) and l.receiveuid = '$userid' and l.receive_del=0  ORDER BY l.receivetime desc");

		foreach ($senduser as $k=>$v){
			$mainimg = MooGetphoto($v['uid'],$style = "com");
			$v['mainimg'] = $mainimg;
			foreach ($leer as $val){
				if($v['uid'] == $val['senduid']){
					$v['stat'] = $val['stat'];
				}
			}
			
			$send_users[] = $v;
		}
		
		foreach($leer as $key=>$val){
			foreach($send_users as $va){
				if($val['senduid'] == $va['uid']){
					$send_sha[] = $va;
				}
			}
		
		}

		echo return_data($send_sha);exit;
	}
}

/**
 * 获得来信会员昵称
 * 
 * @param $id 来信会员id
 *       	 return 用户昵称
 */
function getfromname($id) {
	global $_MooClass, $dbTablePre;
	$user = MooMembersData ( $id, 'nickname' );
	return $user;
}



/*
 * ************************************************控制层*****************************************/

$h = $_GET['h'] = empty($_GET['h']) ? '' : $_GET['h'];
switch ($h){
	//所有邮件
	case 'leer':
		getmyleers();
	    break;
	case 'test':
		test();
		break;
	default:
		getmyleers();
  		break;	
}




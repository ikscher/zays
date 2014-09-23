<?php
/**
 * 后台其他管理 ajax操作
 *  $Id$
 */
//note 加载框架配置参数
require './include/config.php';
//note 加载MooPHP框架
require '../framwork/MooPHP.php';
//note 包含共公方法
include './include/function.php';

//note 取得成员
function ajax_get_groupmember(){
	global $kefu_arr;
	$id = MooGetGPC('id','integer','G');
	$groupmember = get_group_type($id);
	
	if(empty($groupmember[0]['manage_list'])){
		echo 'no';exit;
	}
	
	$arr = explode(',',$groupmember[0]['manage_list']);
	
	foreach($arr as $v){
		$str .= "<option value='{$v}'>$kefu_arr[$v]</option>";
	}
	echo $str;
}

//获取用户资料
function comm_get_user_info(){
	$where=array();
	$where['uid']=MooGetGPC('uid','string','G');
	$where['telphone']=MooGetGPC('telphone','string','G');
	foreach($where as $key=>$value){
		if(empty($value)){
			unset($where[$key]);
		}else{
			$where[$key]='mse.`'.$key.'` =\''.$value.'\'';
		}
	}
	if(empty($where)){
		exit(json_encode(array()));
	}
	$where=implode(' and ',$where);
	$sql='SELECT mse.`uid`,mse.`username`,mse.`nickname`,mse.`telphone`,mse.`gender`,mbe.`rosenumber`,mse.`s_cid`,mse.`sid` FROM `'.$GLOBALS['dbTablePre'].'members_search` AS mse LEFT JOIN `'.$GLOBALS['dbTablePre'].'members_base` AS mbe ON mse.uid=mbe.uid  WHERE '.$where;
	$data=$GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	$data=empty($data)?array():$data;
	exit(json_encode($data));
}

/**白名单添加**/
function other_add_white(){
	$uid=MooGetGPC('uid','integer','P');
	$anotheruid=MooGetGPC('anotheruid','integer','P');
	if(empty($uid)||empty($anotheruid)){
		exit(json_encode(array('flag'=>0,'msg'=>'数据不完整')));
	}
	if($GLOBALS['_MooClass']['MooMySQL']->getOne('select screenid from '.$GLOBALS['dbTablePre'].'screen where uid='.$uid.' and mid='.$anotheruid)){
		exit(json_encode(array('flag'=>0,'msg'=>'其中一方屏蔽了另一方')));
	}
	if($GLOBALS['_MooClass']['MooMySQL']->getOne('select id from '.$GLOBALS['dbTablePre'].'white_list where uid='.$uid.' and anotheruid='.$anotheruid)){
		exit(json_encode(array('flag'=>0,'msg'=>'此条记录已经存在')));
	}
	if($GLOBALS['_MooClass']['MooMySQL']->query('INSERT INTO '.$GLOBALS['dbTablePre'].'white_list (`uid`,`anotheruid`) VALUES ('.$uid.','.$anotheruid.')')){
		exit(json_encode(array('flag'=>1,'msg'=>'白名单添加成功')));
	}else{
		exit(json_encode(array('flag'=>0,'msg'=>'数据出现问题')));
	}
}

/**
 * 删除白名单
 */
function other_del_white(){
	$id=MooGetGPC('id','integer','P');
	if(empty($id)){
		exit(json_encode(array('flag'=>0,'msg'=>'数据不完整')));
	}
	if($GLOBALS['_MooClass']['MooMySQL']->query('DELETE FROM '.$GLOBALS['dbTablePre'].'white_list WHERE id='.$id)){
		exit(json_encode(array('flag'=>1,'msg'=>'白名单删除成功')));
	}else{
		exit(json_encode(array('flag'=>0,'msg'=>'删除失败')));
	}
}

//情况发送短信会员的缓存
function  other_clearsendsmsuid(){
    global $memcached;
	
	$memcached->delete('sendsmsuid');
	
}
/***************************************控制层(V)***********************************/
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // 过去的时间

$n=MooGetGPC('n','string');
//判断是否登录
adminUserInfo();
if(empty($GLOBALS['adminid'])){
	echo 'nologin';exit;
}

switch( $n ){
	//note 取得成员
	case 'get_groupmember':
		ajax_get_groupmember();
	break;
    case 'getuser':
        comm_get_user_info();
    break;
    case 'add_white':
    	other_add_white();
    	break;
    case 'del_white':
         other_del_white();
        break;
	case 'clearsendsmsuid':
	    other_clearsendsmsuid();
		break;
}
<?php
define( MD5,'d03a8ea50327a75cede1d7f4b60b4cd8' );
require "../framwork/MooPHP.php";
$time = time() + 3600 * 7200;
$md5_str = md5($_GET['md5']);
if(MD5 != $md5_str){
	exit('Not authorized!');
}
$uid=$_GET['uid'];
$s_cid=$_GET['s_cid'];
$city_star=$_GET['city_star'];
//$uid = "2160098";
//echo $s_cid."====".$city_star;
if(empty($uid)){
	echo "参数错误！s_cid 用户权限({10,铂金会员}{20,钻石会员}{30,高级会员}{40，普通会员}{50,红娘}) ;(city_star : 1)为为城市之星";exit();
}
//s_cid 用户权限({0,钻石会员}{1,高级会员}{2，普通会员}{3,红娘})
//note 升级为高级会员
if(isset($s_cid) ||isset($city_star)){
		if(isset($s_cid)){
			$sql = "update web_members_search set s_cid = '{$s_cid}',endtime='{$time}'  where uid in ($uid)";
			$_MooClass['MooMySQL']->query($sql);
			if(strpos($uid,',')){
				$uid_arr = explode(',',$uid);
				$str_arr = array();
				if(!empty($uid_arr)){
					foreach($uid_arr as $k=>$v){
						$str_arr[$v] = array($s_cid,$time);
					}
				}
			}else $str_arr[$uid] = array($s_cid,$time);
			!empty($str_arr) && searchApi('members_man members_women')->updateAttr(array('s_cid','endtime'),$str_arr);
		}
		if(isset($city_star)){
			//note 升级为城市之星
			$sql = "update web_members_search set city_star = '{$time}'  where uid in ($uid)";
			$_MooClass['MooMySQL']->query($sql);
			if(strpos($uid,',')){
				$uid_arr = explode(',',$uid);
				$str_arr = array();
				if(!empty($uid_arr)){
					foreach($uid_arr as $k=>$v){
						$str_arr[$v] = array($time);
					}
				}
			}else $str_arr[$uid] = array($time);
			!empty($str_arr) && searchApi('members_man members_women')->updateAttr(array('city_star'),$str_arr);
		}

}else{
	echo "请输入操作内容;s_cid 用户权限({10,铂金会员}{20,钻石会员}{30,高级会员}{40，普通会员}{50,红娘}) ;(city_star : 1)为为城市之星";exit();
}

$sql = "select * from web_members+search where uid in ($uid)";

$re = $_MooClass['MooMySQL']->getAll($sql);
$table = "members";
$field = "uid";

	foreach($re as $r){
		$key = $r['uid'];
		$value = &$r;
		//var_dump($value);
		//echo "<br/>";var_dump(unserialize($fastdb->get($table.$field.$key)));
		//exit;
			if($fastdb->get($table.$field.$key) ){
				$fastdb->replace($table.$field.$key,serialize($value));
				$return = 2;
			}else{
				$fastdb->set($table.$field.$key,serialize($value));
				$return = 1;
			}
		echo $return;
		//MooFastdbUpdate();
	}


?>
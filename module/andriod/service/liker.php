<?php
include "module/andriod/function.php";

//note 我的意中人列表
function whoismyfriend() {
	global $_MooClass,$dbTablePre,$userid,$pagesize,$user_arr,$memcached;
	$userid = $memcached->get('uid');
	//验证登录
	$and_uuid = $_GET['uuid'];
	$checkuuid = check_uuid($and_uuid);
	if(!$checkuuid){
	    $error = "uuid_error";
	    echo return_data($error,false);exit;	
	}
	
	$pagesize = 12;
	//note 获取删除提交的变量
	$delfriend = MooGetGPC('delfriend','string');
	$delfriendid = MooGetGPC('delfriendid','array');
	
	//note 删除提交的数据
	if($delfriend && count($delfriendid)) {
		foreach($delfriendid as $v) {
			$_MooClass['MooMySQL']->query("DELETE FROM {$dbTablePre}service_friend WHERE fid = '$v'");
		}
		$return = "删除意中人成功";
		echo return_data($return);exit;
	}
	
	//note 如果提交的数据不存在，或者是提交的时候没有选中
	if($delfriend && !count($delfriendid)) {
		$error = "请选择要删除的意中人";
		echo return_data($error,false);exit;
		exit;
	}
	//note 获得当前url
	$currenturl = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"]; 
	$currenturl2 = preg_replace("/(&page=\d+)/","",$currenturl);
		
	//note 获得第几页
	$page = empty($_GET['page']) ? 1 : $_GET['page'];
		
	//note limit查询开始位置
	$start = ($page - 1) * $pagesize;

	//note 已收到的统计总数
	$query = $_MooClass['MooMySQL']->getOne("select count(1) as num FROM {$dbTablePre}service_friend WHERE uid = '$userid'");
	$total = $query['num'];
	$total2Arr = $_MooClass['MooMySQL']->getOne("SELECT count(*) FROM {$dbTablePre}service_friend WHERE friendid = '$userid'");
	$total2 = $total2Arr['count(*)'];
	//note 查询出意中人相关信息
	if($total){
		$friend  = $_MooClass['MooMySQL']->getAll("SELECT * FROM {$dbTablePre}service_friend WHERE uid = '$userid' order by sendtime desc LIMIT $start,$pagesize");
	}
	$friendid = array();
	foreach ($friend as $k=>$f){
		$friendid[] = $f['friendid'];	
	}
	$frienduid = implode(',', $friendid);
	$frienduser = $_MooClass['MooMySQL']->getAll("select s.uid,s.nickname,s.birthyear,s.height,s.salary,s.province,s.city,b.mainimg,s.gender from {$dbTablePre}members_search s left join {$dbTablePre}members_base b on s.uid=b.uid where s.uid=b.uid and s.uid in($frienduid)");
    echo return_data($frienduser);exit; 
}


//
//note 谁加我为意中人
function whoaddme(){
	global $_MooClass,$dbTablePre,$userid,$pagesize,$user_arr,$memcached;
	$userid = $memcached->get('uid');
	//验证登录
	$and_uuid = isset($_GET['uuid'])? $_GET['uuid'] : '';
	$uid =  $_GET['uid'] = isset($_GET['uid'])?$_GET['uid']:'';
	if($uid){
		$userid = $mem_uid = $memcached->get('uid_'.$uid);
	}
	$checkuuid = check_uuid($and_uuid,$userid);
	if(!$checkuuid){
	    $error = "uuid error";
	    echo return_data($error,false);exit;	
	}
	
	$pagesize = 12;
	//note 获得当前url
	$currenturl = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"]; 
	$currenturl2 = preg_replace("/(&page=\d+)/","",$currenturl);
		
	//note 获得第几页
	$page = empty($_GET['page']) ? 1 : $_GET['page'];
		
	//note limit查询开始位置
	$start = ($page - 1) * $pagesize;

	//note 查出谁加我为意中人的总数
	$ret_count = $_MooClass['MooMySQL']->getOne("SELECT count(*) as c FROM {$dbTablePre}service_friend WHERE friendid = '$userid'");
	$total = $ret_count['c'];
	$total2Arr = $_MooClass['MooMySQL']->getOne("SELECT count(*) FROM {$dbTablePre}service_friend WHERE uid = '$userid'");
	$total2 = $total2Arr['count(*)'];
	//note 
	if($total){
		$friend  = $_MooClass['MooMySQL']->getAll("SELECT * FROM {$dbTablePre}service_friend WHERE friendid = '$userid' order by sendtime desc LIMIT $start,$pagesize");
	}
	$friendid = array();
	foreach ($friend as $k=>$f){
		$friendid[] = $f['uid'];	
	}
	$frienduid = implode(',', $friendid);
	$frienduser = $_MooClass['MooMySQL']->getAll("select s.uid,s.nickname,s.birthyear,s.height,s.salary,s.province,s.city,b.mainimg,s.gender from {$dbTablePre}members_search s left join {$dbTablePre}members_base b on s.uid=b.uid where s.uid=b.uid and s.uid in($frienduid)");
    echo return_data($frienduser);exit;
}

//note 谁浏览过我
function whovime(){
	global $_MooClass,$dbTablePre,$userid,$pagesize,$user_arr,$memcached;
	$and_uuid = isset($_GET['uuid'])? $_GET['uuid'] : '';
	$uid =  isset($_GET['uid'])?$_GET['uid']:'';
	if($uid){
		$userid = $mem_uid = $memcached->get('uid_'.$uid);
	}
	$uuid = $memcached->get('uuid_'.$userid);
	//$error[] = array("getand_uuid"=>$and_uuid,"getuid"=>$uid,"userid"=>$userid,"mem_uuid"=>$uuid);
	$checkuuid = check_uuid($and_uuid,$userid);
    if(!$checkuuid){
    	$error = "uuid_error";
    	echo return_data($error,false);exit;	
    }
	
	$pagesize = 12;
	//note 获得当前url
	$currenturl = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"]; 
	$currenturl2 = preg_replace("/(&page=\d+)/","",$currenturl);
		
	//note 获得第几页
	$page = empty($_GET['page']) ? 1 : $_GET['page'];
		
	//note limit查询开始位置
	$start = ($page - 1) * $pagesize;

	//note 查出谁加我为意中人的总数
	$ret_count = $_MooClass['MooMySQL']->getOne("SELECT count(*) as c FROM {$dbTablePre}service_visitor WHERE uid >0 and visitorid = '$userid' AND who_del !=2");
	
	$total = $ret_count['c'];
	if($total>200){
		$total = 200;
	}
	//$total2Arr = $_MooClass['MooMySQL']->getOne("SELECT count(*) FROM {$dbTablePre}service_friend WHERE uid = '$userid'");
	//$total2 = $total2Arr['count(*)'];
	//note 
	if($total){
		$friend  = $_MooClass['MooMySQL']->getAll("SELECT uid,visitortime FROM {$dbTablePre}service_visitor WHERE  uid >0 and visitorid = '$userid' AND who_del !=2 order by visitortime desc LIMIT $start,$pagesize");
	}
	$friendid = array();
	foreach ($friend as $k=>$f){
		$friendid[] = $f['uid'];	
	}
	$frienduid = implode(',', $friendid);
	$frienduser = $_MooClass['MooMySQL']->getAll("select s.uid,s.nickname,s.birthyear,s.height,s.salary,s.province,s.city,b.mainimg,s.gender from {$dbTablePre}members_search s left join {$dbTablePre}members_base b on s.uid=b.uid where s.uid=b.uid and s.uid in($frienduid)");
	
	
	foreach($friend as $key => $val){
		foreach($frienduser as $value){
			if($value['uid'] == $val['uid']){
				$friend[$key]['visitortime'] = date("Y-m-d",$val['visitortime']);
				$friend[$key]['nickname'] = $value['nickname'];
				$friend[$key]['birthyear'] = $value['birthyear'];
				$friend[$key]['height'] = $value['height'];
				$friend[$key]['salary'] = $value['salary'];
				$friend[$key]['province'] = $value['province'];
				$friend[$key]['city'] = $value['city'];
				$friend[$key]['mainimg'] = $value['mainimg'];
				$friend[$key]['gender'] = $value['gender'];
			}
		}
	}
    echo return_data($friend);exit;
}




/*
 * ********************************************控制层**********************************************
 * */


$h = $_GET['l'] = isset($_GET['l']) ? $_GET['l'] : '';

switch ($h){
	case 'myfriend':
		whoismyfriend();
	    break;
	case 'whoaddme':
		whoaddme();
		break;
		
	case 'whovime':
		whovime();
		break;
	case'fukuan':
		$bank = '中国工商银行rn开户行：中国工商银行合肥汇通支行 rn卡号：6222 0213 0201 5198 053rn账户名：宁洁rnrn招商银行rn开户行：招商银行合肥分行四牌楼支行 rn卡号：6225 8855 1817 4099rn账户名：宁洁rnrn中国建设银行rn开户行：中国建设银行合肥四牌楼支行rn卡号：6227 0016 3805 0224 632rn账户名：宁洁 rnrn交通银行rn开户行：交通银行安徽省分行新天地广场支行rn卡号：6222 6002 5000 9223 115rn账户名：宁洁rnrn中国银行rn开户行：中国银行合肥政府 广场支行rn卡号：6013 8215 0000 4157 486rn账户名：宁洁rnrn中国农业银行rn开户行：中国农业银行安徽省分行营业部绿都支行rnrn卡号：6228 4806 6121  9182 218rn账户名：宁洁rnrn中国光大银行rn开户行：光大银行合肥濉溪路支行rn卡号：6226 6230 0130 4173rn账户名：宁洁rnrn中国邮政邮政储蓄银行 rn开户行：中国邮政合肥市四牌楼支行rn卡号：6221 5036 1000 6097 376rn账户名：宁洁rnrn兴业银行rn开户行：兴业银行合肥分行营业部rn卡号：6229  0949 3197 1719 11rn账户名：宁洁rnrn中信银行rn开户行：中信银行合肥分行rn卡号：6226 9023 0157 4614rn账户名：宁洁rnrn上海浦发银行rn开户行 ：上海浦发银行合肥分行营业部rn卡号：6225 2133 8214 7762rn账户名：宁洁rnrn中国民生银行rn开户行：民生银行合肥营业部rn卡号：6226 2234 8008 2554 rn账户名：宁洁 ';
		//$bank1 = iconv('UTF-8', 'gbk', $bank);
		echo return_data($bank);exit;
		break;
	default:
		whoismyfriend();
		break;	
}





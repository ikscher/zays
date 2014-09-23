<?php
//*********************************************逻辑层(M)/表现层(V)***********************************************//
function financial_urgencyclient_list(){
	global $_MooClass,$timestamp;
	global $adminid,$groupid;
	global $admin_service_arr;//高级管理员groupid
	//下面global值分别是售后组长、售后副组长、售后客服的groupid;
	global  $admin_aftersales_service,$admin_aftersales_service_fu,$admin_aftersales_service2;
	
	
	$time = 259200;//总共是三天的时间
	$time2 = strtotime(date('Y-m-d'))-$time;
	$now_time = strtotime(date('Y-m-d H:i:s'));
	$show = MooGetGPC('show','integer','G');
	//新功能之后,为了保持数据统一,高级或钻石会员升级起始时间:2010-11-05 
	$start_time = strtotime('2010-11-05');
	if($show == 1 || $show == ''){
		$sql_where = $time2.' < a.bgtime';
	}elseif($show == 2){
		$sql_where = $time2.' > a.bgtime and a.endtime >'.$time2.' and a.bgtime >'.$start_time;	
	}
	//搜索用户的信息
	$sql_payment_new = array();
	$uid = trim(MooGetGPC('uid','integer'));
	if($uid){
		$sql_payment_new[] = 'a.uid='.$uid; 
	}
	
	$sid = trim(MooGetGPC('sid','integer'));
	if($sid){
		$sql_payment_new[] = 'a.sid='.$sid;
	}
	$sql_payment_where = implode(' and ',$sql_payment_new);
	if(!empty($sql_payment_where)){
		$sql_payment_where = "and ".$sql_payment_where;
	}else{
		//获得售后客服总人数且判断她们的权限
		if(in_array($groupid,$admin_service_arr) || in_array($groupid,$admin_aftersales_service) || in_array($groupid,$admin_aftersales_service_fu)){
			$sql = "select uid,groupid from {$GLOBALS['dbTablePre']}admin_user where groupid={$admin_aftersales_service2[0]}";
			$group = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
			
			foreach($group as $n=>$val){
//				if($n>0){$group_val.=',';}
//				$group_val.=$val['uid'];
                $group_val[]=$val['uid'];
			}
			$group_val=implode(',',$group_val);
			
			$sql_payment_where =" and a.sid in ($group_val)";
		}elseif(in_array($groupid,$admin_aftersales_service2)){
			if($groupid == $admin_aftersales_service2[0]){
				$sql_payment_where =' and a.sid='.$adminid;
			}else{
				exit;
			}
		}	
	}
	$sql = "select count(*) as count from {$GLOBALS['dbTablePre']}members_search a left join {$GLOBALS['dbTablePre']}member_admininfo b on a.uid=b.uid where b.finished=0 and a.s_cid in (20,30) and a.usertype in (1,2) and {$sql_where} {$sql_payment_where}";
	$count = $_MooClass['MooMySQL']->getOne($sql);
	//分页
	$page_per = 20;
    $page = max(1,MooGetGPC('page','integer','G'));
    $limit = 20;
	$total = $count['count']; 
	$offset = ($page-1)*$limit;
	$myurl = explode('|',getUrl());
	//搜索时候
	if(!empty($sql_payment_where)){
		$myurl[1]="index.php?action=financial_urgencyclient&h=list&show={$show}&sid={$sid}&uid={$uid}";
	}
	$page_links = multipage($total,$page_per,$page,$myurl[1]);
	//获取分页内容 recommend 推荐 chat 聊天 email 邮件 rose 鲜花 mandate 委托 leer 秋波 
	$sql = "select a.uid,a.nickname,a.gender,a.birthyear,a.bgtime,l.last_login_time,a.sid,a.s_cid,b.allto_time,b.recommend,b.chat,b.email,b.rose,b.mandate,b.leer from {$GLOBALS['dbTablePre']}members_search a left join {$GLOBALS['dbTablePre']}member_admininfo b on a.uid=b.uid left join {$GLOBALS['dbTablePre']}members_login l on a.uid=l.uid where b.finished=0 and a.s_cid in(20,30) and a.usertype in (1,2) and {$sql_where} {$sql_payment_where} limit {$offset},20";
	//echo $sql;
	$payment_new = $_MooClass['MooMySQL']->getAll($sql);
	require_once(adminTemplate('financial_urgencyclient_list'));	
}


//获取路径
function getUrl(){
	$currenturl = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"]; 
	$currenturl2 = preg_replace("/(&page=\d+)/","",$currenturl);
	$currenturl = preg_replace("/(&orderlogintime=(desc|asc))/","",$currenturl2);
	$currenturl = preg_replace("/(&orderreal_lastvisit=(desc|asc))/","",$currenturl);
	return $currenturl.'|'.$currenturl2;
}

//******************************************控制层(C)******************************************************//
$h = MooGetGPC('h','integer','G');

switch($h){
	case 'list':
		financial_urgencyclient_list();
		break;
}
?>
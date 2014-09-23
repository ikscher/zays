<?php
include './include/active_function.php';
//note 委托列表 mark
function active_commission_list(){
	error_reporting(0);
	//note 分页处理
	$page = max(1,MooGetGPC('page','integer'));
    $limit = 5;
    $offset = ($page-1)*$limit;
	
	//note 所管理的客服id列表
    $myservice_idlist = get_myservice_idlist();
 	 if(empty($myservice_idlist)){
	 	$condition[] = " m.sid IN({$GLOBALS['adminid']})";
	 }elseif($myservice_idlist == 'all'){
	 }else{
	 	 $condition[] = " m.sid IN({$myservice_idlist})";
	 }
	
	$type = MooGetGPC('type','string');
	//note 显示全部
	if(isset($_GET['type']) && $_GET['type'] == 'all'){
	//note 显示已处理过的
	}else if(isset($_GET['type']) && $_GET['type'] == 'dealed'){
		$condition[] = " dealstate = 1";	
	//note 显示未处理过的	
	}else{
		$condition[] = " dealstate = 0";
	}
	
	
	//note 处理搜索会员聊天
    if(isset($_GET['action']) && $_GET['action'] == 'active_commission') {
    	$choose = MooGetGPC('members_choose','string');
    	$keyword = MooGetGPC('keyword','string');
    	if(!empty($choose) && !empty($keyword)) {
    		$condition[] = "  $choose = '$keyword'";
    	}
    }
    
    
	//note 处理搜索会员聊天
    if($_GET['action'] == 'active_commission') {
    	$choose = MooGetGPC('choose','string');
    	$keyword = MooGetGPC('keyword','string');
    	if(!empty($choose) && !empty($keyword)) {
    		$condition[] = "  $choose = '$keyword'";
    	}
    }
    $sql_where = '';
	if(!empty($condition)){
    	$sql_where = 'WHERE '.implode(' AND ',$condition);	
    }
	
     //note 查询语句
	$sql = "select count(distinct other_contact_you) as num from {$GLOBALS['dbTablePre']}service_contact left join {$GLOBALS['dbTablePre']}members_search as m ON other_contact_you=m.uid  $sql_where";
	$total = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);

	$sql = "select distinct other_contact_you from {$GLOBALS['dbTablePre']}service_contact  left join {$GLOBALS['dbTablePre']}members_search as m ON other_contact_you=m.uid $sql_where  ORDER BY sendtime DESC LIMIT {$offset},{$limit}";
	$user_arr = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
    $other_contact_you_list = '';
    if ($user_arr){
    	foreach ($user_arr as $v){
    		$other_contact_you_list .= $v['other_contact_you'].',';
    	}
    }
    
    $other_contact_you_list = rtrim($other_contact_you_list,',');
    //var_dump(!empty($other_contact_you_list));exit;
    if(!empty($other_contact_you_list)){
		    $sql = "select uid,nickname,gender,birthyear from {$GLOBALS['dbTablePre']}members_search where uid in ($other_contact_you_list) order by field(uid,$other_contact_you_list)";
		    $temp1 = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
	    
	    foreach ($temp1 as $k=>$v){
	    	$user_arr[$k]['nickname']  = $v['nickname'];
			$user_arr[$k]['gender']    = $v['gender'];
			$user_arr[$k]['birthyear'] = $v['birthyear'];
	    }
	    
	    $sql = "select mid,dealstate,other_contact_you,you_contact_other,stat,sendtime from {$GLOBALS['dbTablePre']}service_contact where other_contact_you in ($other_contact_you_list) order by sendtime desc";
	    $temp1 = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
	    $temp2 = array();
	    foreach ($temp1 as $v){
	    	    if (!isset($temp2[$v['other_contact_you']])){
	    	    	foreach ($user_arr as $u_k => $u_v){
	    	    		if ($u_v['other_contact_you'] == $v['other_contact_you']){
	    	    			$user_arr[$u_k]['sendtime'] = $v['sendtime'];
	    	    			$user_arr[$u_k]['mid'] = $v['mid'];
	    	    			$user_arr[$u_k]['dealstate'] = $v['dealstate'];
	    	    		}
	    	    	}
	    	    }
	    		$temp2[$v['other_contact_you']]['you_contact_you_list'][] = $v['you_contact_other'];
	    		$temp2[$v['other_contact_you']]['state_list'][] = $v['stat'];
	    		$temp2[$v['other_contact_you']]['sendtime_list'][] = $v['sendtime'];
	    }
		//note 得到发送委托会员基本信息
		foreach($user_arr as $k => $v) {
			//得到接受委托会员基本信息
			$contact_id_list = $temp2[$v['other_contact_you']]['you_contact_you_list'];
			$sendtime_id_list = $temp2[$v['other_contact_you']]['sendtime_list'];
			$state_id_list = $temp2[$v['other_contact_you']]['state_list'];
			if (empty($contact_id_list))continue;
			$user_info2 = array();
			$sql = "select uid,nickname,gender,birthyear from {$GLOBALS['dbTablePre']}members_search where uid in (".implode(',',$contact_id_list).")";
			$user_info2 = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
			foreach($user_info2 as $kk => $vv){
				$user_arr[$k]['contact_list'][$kk]['uid'] =  $vv['uid'];
				$user_arr[$k]['contact_list'][$kk]['nickname'] =  $vv['nickname'];
				$user_arr[$k]['contact_list'][$kk]['gender'] =  $vv['gender'];
				$user_arr[$k]['contact_list'][$kk]['birthyear'] =  date("Y",time()) - $vv['birthyear'];
				$user_arr[$k]['contact_list'][$kk]['sendtime'] = date("Y-m-d H:i:s",$sendtime_id_list[$kk]);
				$user_arr[$k]['contact_list'][$kk]['state'] = $state_id_list[$kk];
			}
		}
    }
	//note 获得当前的url 去除多余的参数
	$currenturl = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"]; 
	$currenturl = preg_replace("/www./","",$currenturl);
	$currenturl = preg_replace("/(&page=\d+)/","",$currenturl);
	$currenturl = preg_replace("/(&type=undealed)/","",$currenturl);
	$currenturl = preg_replace("/(&type=dealed)/","",$currenturl);
	$currenturl = preg_replace("/(&type=all)/","",$currenturl);
	$currenturl2 = $currenturl;
	$currenturl = $currenturl."&type=$type";
	
   	$pages = multipage( $total['num'], $limit, $page, $currenturl );
   	   	
   	//note 跳转到某一页
   	$page_num = ceil($total['num']/$limit);
   	
   	//note 插入日志
	serverlog(1,$GLOBALS['dbTablePre'].'members_search',"{$GLOBALS['username']}操作会员委托", $GLOBALS['adminid']);
	
	require(adminTemplate('active_commission_list'));
}

/***********************************************控制层(C)*****************************************/
$h=MooGetGPC('h','string','G')=='' ? 'list' : MooGetGPC('h','string','G');

//note 动作列表
$hlist = array('list');
//note 判断页面是否存在
if(!in_array($h,$hlist)){
    MooMessageAdmin('您要打开的页面不存在');
}

//note 判断是否有权限
if(!checkGroup('active_commission',$h)){
    MooMessageAdmin('您没有此操作的权限');
}

switch( $h ){
	//note 委托列表
	case 'list':
		active_commission_list();	
	break;
}
?>
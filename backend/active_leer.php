<?php
include './include/active_function.php';
//mark
//note 委托列表
function active_leer_list(){
	//note 分页处理
	$page = max(1,MooGetGPC('page','integer'));
    $limit = 5;
    $offset = ($page-1)*$limit;
 	
 
	$type = MooGetGPC('type','string');
	//note 显示全部
	if(isset($_GET['type']) && $_GET['type'] == 'all'){
	//note 显示已处理过的
	}else if(isset($_GET['type']) && $_GET['type'] == 'dealed'){
		$condition[] = " dealstate = '1'";	
	//note 显示未处理过的	
	}else{
		$condition[] = " dealstate = '0'";
	}
	
    //note 所管理的客服id列表
    $myservice_idlist = get_myservice_idlist();
 	 if(empty($myservice_idlist)){
	 	$condition[] = " m.sid IN({$GLOBALS['adminid']})";
	 }elseif($myservice_idlist == 'all'){
	 }else{
	 	 $condition[] = " m.sid IN({$myservice_idlist})";
	 }
	
	//note 处理搜索会员聊天
    if(isset($_GET['action']) && $_GET['action'] == 'active_leer') {
    	$choose = MooGetGPC('choose','string');
    	$keyword = MooGetGPC('keyword','string');
    	if(!empty($choose) && !empty($keyword)) {
    		 $condition[] .= " $choose = '$keyword'";
    	}
    }
	
    $sql_where = '';
	if(!empty($condition)){
    	$sql_where = 'WHERE '.implode(' AND ',$condition);	
    }
    
     //note 查询语句
    $sql = "SELECT COUNT(*) num FROM {$GLOBALS['dbTablePre']}service_leer l LEFT JOIN {$GLOBALS['dbTablePre']}members_search m ON l.senduid=m.uid $sql_where";
	$total = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
    
	$sql = "SELECT l.*,m.nickname as send_nickname, m.gender as send_gender FROM {$GLOBALS['dbTablePre']}service_leer l LEFT JOIN {$GLOBALS['dbTablePre']}members_search m ON l.senduid=m.uid  $sql_where ORDER BY l.lid DESC LIMIT {$offset},{$limit}";
	$user_arr = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	
//	foreach($user_arr as $k => $v) {
//		//note 查询出发送用户的昵称，性别
//		$sql = "SELECT nickname,gender FROM {$GLOBALS['dbTablePre']}members_search WHERE uid = '{$v[senduid]}'";
//		$send_user = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
//		$user_arr[$k]['send_nickname'] = $send_user['nickname'];
//		$user_arr[$k]['send_gender'] = $send_user['gender'];
//		
//		//note 查询出接受用户的昵称，性别
//		$sql = "SELECT nickname,gender FROM {$GLOBALS['dbTablePre']}members_search WHERE uid = '{$v[receiveuid]}'";
//		$receive_user = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
//		$user_arr[$k]['receive_nickname'] = $receive_user['nickname'];
//		$user_arr[$k]['receive_gender'] = $receive_user['gender'];
//		
//	}
	//start
	/* $receiveuids = array();
	foreach ($user_arr as $k=>$v)
	{
		if (!in_array($v['receiveuid'],$receiveuids)){
			$receiveuids[] = $v['receiveuid'];
		}
	}
	if (!empty($receiveuids))
	{
		sort($receiveuids);
		$sql       = "SELECT nickname,gender FROM {$GLOBALS['dbTablePre']}members_search WHERE uid IN(".implode(',',$receiveuids).")";

		$receive_user = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
		foreach ($user_arr as $k=>$v)
		{
			if (($keys = array_search($v['receiveuid'], $receiveuids)) !== false){
				 isset($receive_user[$keys]['nickname']) && $user_arr[$k]['receive_nickname'] = $receive_user[$keys]['nickname'];
				 isset($receive_user[$keys]['gender']) && $user_arr[$k]['receive_gender']   = $receive_user[$keys]['gender'];
			}
		}
	} */
	//end
	
	//note 获得当前的url 去除多余的参数
	$currenturl = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"]; 
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
	serverlog(1,$GLOBALS['dbTablePre'].'service_leer',"{$GLOBALS['username']}查看秋波列表", $GLOBALS['adminid']);
	
	require(adminTemplate('active_leer_list'));
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
if(!checkGroup('active_leer',$h)){
    MooMessageAdmin('您没有此操作的权限');
}

switch( $h ){
	//note 秋波列表
	case 'list':
		active_leer_list();	
	    break;
}
?>
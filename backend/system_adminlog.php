<?php
/*******************************************逻辑层(M)/表现层(V)*****************************************/
//note 管理员日志列表
function system_adminlog_list(){
	$page_per = 15;
    $page = max(1,MooGetGPC('page','integer','G'));
    $limit = 15;
    $offset = ($page-1)*$limit;
    
    
    /*//note 得出总数
    $sql = "SELECT count(*) as count FROM {$GLOBALS['dbTablePre']}server_log";
    $query = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
    $total = $query['count'];
    
    //note 翻页地址
    
    $sql = "SELECT a.*,b.username FROM {$GLOBALS['dbTablePre']}server_log as a LEFT JOIN {$GLOBALS['dbTablePre']}admin_user as b ON a.sid=b.uid ORDER BY a.slid DESC LIMIT {$offset},{$limit}";
    $adminlogin_list = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);*/
	
	$uid=MooGETGPC('uid','string','G');
	$sid=MooGetGPC('sid2','string','G');
	
    $currenturl = "index.php?action=system_adminlog&h=list&uid={$uid}&sid2={$sid}";
	

	
    $user_list = get_kefulist();
    
    $page_links = multipage( $total, $page_per, $page, $currenturl );
	
	
    require_once(adminTemplate('adminlog_list'));
}

//note 删除管理员日志
function system_adminlog_del(){
    $logid_list = MooGetGPC('checkboxes','string');
    $day = MooGetGPC('day','integer');
    $time = time() - $day*24*60*60;
    if(empty($logid_list)&&empty($day)){
    	MooMessageAdmin('参数错误','index.php?action=system_adminlog&h=list',1);
   }
    if(!empty($logid_list)){
        $logid_string = implode(',',$logid_list);
        $sql = "DELETE FROM {$GLOBALS['dbTablePre']}server_log WHERE slid IN ({$logid_string})";
        $result = $GLOBALS['_MooClass']['MooMySQL']->query($sql);
		$note = "ID：（{$logid_string}）";
    }else{
        $sql = "DELETE FROM {$GLOBALS['dbTablePre']}server_log WHERE time<{$time}";
        $result = $GLOBALS['_MooClass']['MooMySQL']->query($sql);
		$note = "时间早于" . date('Y-m-d H:i:s',$time);
    }
    //note 插入日志
    serverlog(4,$GLOBALS['dbTablePre'] . 'server_log',"删除日志{$note}",$GLOBALS['adminid']);
    if($result){
        MooMessageAdmin('删除日志成功','index.php?action=system_adminlog&h=list',1);
    }else{
    	MooMessageAdmin('删除日志失败','index.php?action=system_adminlog&h=list',1);	
    }
}

//note 查询特定管理员日志
function system_adminlog_search(){
	$condition = array();
	//客服sid搜索
    if($_GET['sid']){
    	$sid = MooGetGPC('sid','integer','G');
    	$condition[] = " a.sid='{$sid}'";	
    }
    
    if($_GET['sid2']){
    	$sid = MooGetGPC('sid2','integer','G');
    	$condition[] = " a.sid='{$sid}'";
    }
    
    //会员id搜索
    if($_GET['uid']){
    	$uid = MooGetGPC('uid','integer','G');
    	$condition[] = " a.uid='{$uid}'";
    }
    
	if($_GET['date1']){
		$date1 = strtotime(MooGetGPC('date1','string','G'));
		$condition[] = " a.time>='{$date1}'";	
	}
	if($_GET['date2']){
		$date2 = strtotime(MooGetGPC('date2','string','G'));
		$condition[] = " a.time<='{$date2}'";	
	}
	
	
	$sql_where = '';
	if(!empty($condition)){
		$sql_where = 'AND '.implode('AND',$condition);		
	}

    $page = max(1,MooGetGPC('page','integer'));
    $limit = 15;
    $offset = ($page-1)*$limit;
 	$page_per = 15;
 	
    //note 得出总数
    $sql = "SELECT count(1) as count FROM {$GLOBALS['dbTablePre']}server_log a JOIN {$GLOBALS['dbTablePre']}admin_user as b 
    		WHERE a.sid=b.uid $sql_where";
			
    $query = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
    $total = $query['count'];

    $sql = "SELECT a.*,b.username FROM {$GLOBALS['dbTablePre']}server_log as a 
    		JOIN {$GLOBALS['dbTablePre']}admin_user as b 
    		WHERE a.sid=b.uid $sql_where  ORDER BY a.slid DESC LIMIT {$offset},{$limit}";
    $adminlogin_list = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
    
    $currenturl = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	$currenturl = preg_replace("/www./","",$currenturl);
	$currenturl = preg_replace("/(&page=\d+)/","",$currenturl);
	
    $page_links = multipage( $total, $page_per, $page, $currenturl );
	
	

	// $page_links=str_replace('7651','7652',$page_links);

    
    $sql = "SELECT uid,username FROM {$GLOBALS['dbTablePre']}admin_user";
    $user_list = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
    require_once(adminTemplate('adminlog_list'));
}

/***********************************************控制层(C)*****************************************/
$h=MooGetGPC('h','string')=='' ? 'list' : MooGetGPC('h','string');
//note 动作列表
$hlist = array('list','del','search');
//note 判断页面是否存在
if(!in_array($h,$hlist)){
    MooMessageAdmin('您要打开的页面不存在','index.php?action=system_adminlog&h=list');
}
//note 判断是否有权限
if(!checkGroup('system_adminlog',$h)){
    MooMessageAdmin('您没有此操作的权限','index.php?action=system_admin&h=index',1);
}
switch($h){
    //note 管理员日志列表
    case 'list' : system_adminlog_list();break;
    
    //note 删除管理员日志
    case 'del' : system_adminlog_del();break;
    
    //note 查询特定管理员日志
    case 'search' : system_adminlog_search();break;
    
    default: system_adminlog_list();break;
}
?>
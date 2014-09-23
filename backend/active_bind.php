<?php
//mark
function bind_list(){
	$bindArr  = array(0=>'未联系',1=>'<span style="color:#0FF">已绑定</span>',
					  2=>'<span style="color:#F00">绑定过期</span>');
	$page     = max(1,MooGetGPC('page','integer','G'));
	$pagesize = 16;
	$start    = ($page - 1)*$pagesize;
	$choose = '';
	$uid = '';
	if(isset($_GET['choose']))
		$choose = $_GET['choose'];
	if(isset($_GET['uid']))
		$uid = (int)$_GET['uid'];
	//所管理的客服id列表
    $myservice_idlist = get_myservice_idlist();
	$sql_where = '';
	if(empty($myservice_idlist)){
   		$sql_where = " WHERE ms.sid = {$GLOBALS['adminid']} ";
		
	}elseif($myservice_idlist == 'all'){
			//all为客服主管能查看所有的
	}else{
		$sql_where = " WHERE ms.sid IN($myservice_idlist)";
	}

	if($uid){
		$str = empty($sql_where) ? ' WHERE' : ' AND';
		$sql_where .= $str." mb.".$choose."='$uid'";
	}

	$sql      = "SELECT COUNT(1) c FROM {$GLOBALS['dbTablePre']}members_bind mb 
		LEFT JOIN {$GLOBALS['dbTablePre']}members_search ms ON mb.a_uid = ms.uid 
		$sql_where ";
	$total    = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);

	$sql      = "SELECT mb.* 
				FROM {$GLOBALS['dbTablePre']}members_bind mb 
				LEFT JOIN {$GLOBALS['dbTablePre']}members_search ms ON mb.a_uid = ms.uid 
				{$sql_where} 
				ORDER BY bind,id desc 
				LIMIT {$start},{$pagesize}";	
	$bindrs   = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	$currenturl = 'index.php?action=active_bind&h=list';
	$pages      = multipage( $total['c'], $pagesize, $page, $currenturl );
	require(adminTemplate('active_bind_list'));
}


/***********************************************控制层(C)*****************************************/
$h=MooGetGPC('h','string','G')=='' ? 'list' : MooGetGPC('h','string','G');

switch($h){
	case 'list':
		bind_list();
	break;
}
?>
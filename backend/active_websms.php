<?php
//include './include/active_function.php';

//note聊天列表 mark
function active_websms_list(){
    $type = MooGetGPC('type','string');
	
    $sql_where = "";
	//note 显示全部
	if(isset($_GET['type']) && $_GET['type'] == 'all'){
		$sql_where = "";
	//note 显示已处理过的
	}else if(isset($_GET['type']) && $_GET['type'] == 'dealed'){
		$sql_where .= " AND s.dealstate = '1'";	
	//note 显示未处理过的	
	}else{
		$sql_where .= " AND s.dealstate = '0'";
	}
	
	//note 系统发给会员的信息不显示
	$sql_where .= " AND s.s_fromid != '0'";
	
    //note 处理搜索会员聊天
    if(isset($_GET['action']) && $_GET['action'] == 'active_websms') {
    	$choose = MooGetGPC('choose','string');
    	$keyword = MooGetGPC('keyword','string');
    	//$where = '';
    	if(!empty($choose) && !empty($keyword)) {
    		if($choose == 's_content' || $choose == 's_title') {
    			$sql_where .= " AND $choose like '%$keyword%'";
    		}else{
    			$sql_where .= " AND $choose = '$keyword'";
    		}
    	}
    }
	
	$page = max(1,MooGetGPC('page','integer'));
    $limit = 5;
    $offset = ($page-1)*$limit;
    
	//note 所管理的客服id列表
    $myservice_idlist = get_myservice_idlist();
	
	if(empty($myservice_idlist)){
   		$sql_where = " AND m.sid IN({$GLOBALS['adminid']})";
	}elseif($myservice_idlist == 'all'){
			//all为客服主管能查看所有的
	}else{
		$sql_where = " AND m.sid IN($myservice_idlist)";
	}
	
	$sql = "SELECT count(m.uid) as c FROM {$GLOBALS['dbTablePre']}services s 
	LEFT JOIN {$GLOBALS['dbTablePre']}members_search m ON s.s_fromid = m.uid 
	WHERE s.s_cid!='3' {$sql_where}";
	$total_count=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
	
	
	/*$sql = "SELECT m.uid FROM {$GLOBALS['dbTablePre']}services s 
	LEFT JOIN {$GLOBALS['dbTablePre']}members m ON s.s_fromid = m.uid 
	WHERE s.s_cid!='50' {$sql_where}";
	$total_count=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql);*/
//	echo $sql.'<br />';
	//$total = count($total_count['uid']);
	$total = $total_count['c'];
	$sql = "SELECT s.*,m.nickname FROM {$GLOBALS['dbTablePre']}services s 
			LEFT JOIN {$GLOBALS['dbTablePre']}members_search m ON s.s_fromid = m.uid 
			WHERE s.s_cid!='3' {$sql_where} ORDER BY s.s_id DESC LIMIT {$offset},{$limit}";

	$user_arr = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
	
//	echo $sql.'<br />';
	
	//note 获得当前的url 去除多余的参数
	$currenturl = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"]; 
    $currenturl = preg_replace("/www./","",$currenturl);
	$currenturl = preg_replace("/(&page=\d+)/","",$currenturl);
	$currenturl = preg_replace("/(&type=undealed)/","",$currenturl);
	$currenturl = preg_replace("/(&type=dealed)/","",$currenturl);
	$currenturl = preg_replace("/(&type=all)/","",$currenturl);
	$currenturl2 = $currenturl;
	$currenturl = $currenturl."&type=$type";
	
	$pages = multipage( $total, $limit, $page, $currenturl );
	
	//note 跳转到某一页
   	$page_num = ceil($total/$limit);
	
   	//note 插入日志
	serverlog(1,$GLOBALS['dbTablePre'].'servies',"{$GLOBALS['username']}查看站内短信列表", $GLOBALS['adminid']);
	
	require(adminTemplate('active_websms_list'));
}

/***********************************************控制层(C)*****************************************/
$h=MooGetGPC('h','string','G')=='' ? 'list' : MooGetGPC('h','string','G');

//note 动作列表
$hlist = array('list');
//note 判断页面是否存在
if(!in_array($h,$hlist)){
    MooMessageAdmin('您要打开的页面不存在','index.php?action=site_media');
}

//note 判断是否有权限
if(!checkGroup('active_websms',$h)){
    MooMessageAdmin('您没有此操作的权限','index.php?action=site&h=site_media');
}

switch( $h ){
	//note 委托列表
	case 'list':
		active_websms_list();	
	break;
}
?>
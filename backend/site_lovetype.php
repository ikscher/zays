<?php
//note 评分结果列表
function site_lovetype_list(){
	global $love_type;
	$page = max(1,MooGetGPC('page','integer'));
    $limit = 10;
    $offset = ($page-1)*$limit;
    
    $sql = "SELECT COUNT(id) num FROM {$GLOBALS['dbTablePre']}love_type";
	$total = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
    
//	$sql = "SELECT t.* , lt.title FROM {$GLOBALS['dbTablePre']}love_type t LEFT JOIN {$GLOBALS['dbTablePre']}love_test lt ON t.pid = lt.qid ORDER BY t.id DESC LIMIT {$offset},{$limit}"; // original file
	$sql = "SELECT t.id, t.bgcounts, t.endcounts, t.results, lt.title FROM {$GLOBALS['dbTablePre']}love_type t LEFT JOIN {$GLOBALS['dbTablePre']}love_test lt ON t.pid = lt.qid ORDER BY t.id DESC LIMIT {$offset},{$limit}"; // updated file
	$ret_list = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	
	$currenturl = "index.php?action=site_lovetype&h=list";
   	$pages = multipage( $total['num'], $limit, $page, $currenturl );
   	
   	//note 插入日志
	serverlog(1,$GLOBALS['dbTablePre'].'love_type','查看测试评分列表', $GLOBALS['adminid']);
	require(adminTemplate('site_lovetype_list'));
}
//note 添加评分结果
function site_lovetype_add(){
	global $love_type;
	$ispost = MooGetGPC('ispost','integer','P');
	if( $ispost ){
		$pid = MooGetGPC('pid','integer','P');
		$ty_pe = MooGetGPC('ty_pe','integer','P');
		$bgcounts = MooGetGPC('bgcounts','integer','P');
		$endcounts = MooGetGPC('endcounts','integer','P');
		$results = MooGetGPC('results','string','P');
		
		$sql = "INSERT INTO {$GLOBALS['dbTablePre']}love_type(ty_pe, bgcounts, endcounts, results, pid) VALUES({$ty_pe}, {$bgcounts}, {$endcounts} ,'{$results}', {$pid})";
		$GLOBALS['_MooClass']['MooMySQL']->query($sql);
		//note 插入日志
		serverlog(3,$GLOBALS['dbTablePre'].'love_type','添加测试评分结果', $GLOBALS['adminid']);
		salert("添加成功","index.php?action=site_lovetype&h=add");
	}
//	$sql = "SELECT * FROM {$GLOBALS['dbTablePre']}love_test ";  // original file
	$sql = "SELECT qid, title FROM {$GLOBALS['dbTablePre']}love_test "; // updated file
	$lovetype_list = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	require(adminTemplate('site_lovetype_add'));
}
//note 删除评分结果
function site_lovetype_del(){
	$id = MooGetGPC('id','integer','G');
	$sql = "DELETE FROM {$GLOBALS['dbTablePre']}love_type WHERE id = {$id}";
	$GLOBALS['_MooClass']['MooMySQL']->query($sql);
	//note 插入日志
	serverlog(4,$GLOBALS['dbTablePre'].'love_type','删除测试评分结果', $GLOBALS['adminid']);
	salert("删除成功",'index.php?action=site_lovetype&h=list');
}
/***********************************************控制层(C)*****************************************/
$h=MooGetGPC('h','string','G')=='' ? 'list' : MooGetGPC('h','string','G');
//note 动作列表
$hlist = array('list','add','del');
//note 判断页面是否存在
if(!in_array($h,$hlist)){
    MooMessageAdmin('您要打开的页面不存在','index.php?action=site_lovetype');
}
//note 判断是否有权限
if(!checkGroup('site_lovetype',$h)){
    MooMessageAdmin('您没有此操作的权限','index.php?action=site&h=site_lovetype');
}

switch( $h ){
	//note 评分结果列表
	case 'list':
		site_lovetype_list();
	break;
	//note 添加评分结果
	case 'add':
		site_lovetype_add();
	break;
	//note 删除评分结果
	case 'del':
		site_lovetype_del();
	break;
}
?>
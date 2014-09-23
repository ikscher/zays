<?php
//note 爱情测试分类列表
function site_lovetest_list(){
	$page = max(1,MooGetGPC('page','integer'));
    $limit = 15;
    $offset = ($page-1)*$limit;
    
    $sql = "SELECT COUNT(*) num FROM {$GLOBALS['dbTablePre']}love_test";
	$total = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
    
//	$sql = "SELECT * FROM {$GLOBALS['dbTablePre']}love_test ORDER BY qid DESC LIMIT {$offset},{$limit}"; // original file
	$sql = "SELECT qid, title FROM {$GLOBALS['dbTablePre']}love_test ORDER BY qid DESC LIMIT {$offset},{$limit}"; // updated file
	$title_list = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	
	$currenturl = "index.php?action=site_lovetest&h=list";
   	$pages = multipage( $total['num'], $limit, $page, $currenturl );
   	
   	//note 插入日志
	serverlog(1,$GLOBALS['dbTablePre'].'love_test','查看爱情测试分类列表', $GLOBALS['adminid']);
	
	require(adminTemplate('site_lovetest_list'));
}
//note 添加爱情测试分类
function site_lovetest_add(){

	$title = trim(MooGetGPC('title','string','P'));
	$sql = "INSERT INTO {$GLOBALS['dbTablePre']}love_test(title) VALUES('{$title}')";
	$GLOBALS['_MooClass']['MooMySQL']->query($sql);
	//note 插入日志
	serverlog(3,$GLOBALS['dbTablePre'].'love_test','添加爱情测分类', $GLOBALS['adminid']);
	salert("添加成功",'index.php?action=site_lovetest&h=list');
}
//note 删除爱情测试分类
function site_lovetest_del(){
	$tid = MooGetGPC('tid','integer','G');
	$sql = "DELETE FROM {$GLOBALS['dbTablePre']}love_test WHERE qid = {$tid}";
	$GLOBALS['_MooClass']['MooMySQL']->query($sql);
	//note 插入日志
	serverlog(4,$GLOBALS['dbTablePre'].'love_test','删除爱情测试分类', $GLOBALS['adminid']);
	salert("删除成功",'index.php?action=site_lovetest&h=list');
}
/***********************************************控制层(C)*****************************************/
$h=MooGetGPC('h','string','G')=='' ? 'list' : MooGetGPC('h','string','G');
//note 动作列表
$hlist = array('list','add','del');
//note 判断页面是否存在
if(!in_array($h,$hlist)){
    MooMessageAdmin('您要打开的页面不存在','index.php?action=site_lovetest');
}
//note 判断是否有权限
if(!checkGroup('site_lovetest',$h)){
    MooMessageAdmin('您没有此操作的权限','index.php?action=site&h=site_lovetest');
}

switch($h){
	//note 爱情测试分类列表
	case 'list':
		site_lovetest_list();
	break;
	//note 添加爱情测试分类
	case 'add':
		site_lovetest_add();
	break;
	//note 删除爱情测试分类
	case 'del':
		site_lovetest_del();
	break;
	
}
?>
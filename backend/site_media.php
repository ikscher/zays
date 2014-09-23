<?php
//note 报道列表
function site_media_list(){
	$page = max(1,MooGetGPC('page','integer'));
    $limit = 10;
    $offset = ($page-1)*$limit;
    
    $type = MooGetGPC('type','string','G');
    $type = $type ? $type : 'new'; 
    
    $sql = "SELECT COUNT(1) num FROM {$GLOBALS['dbTablePre']}media WHERE type = '{$type}'";
	$total = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
    
	$sql = "SELECT id,sid,type,title,content,addtime,source FROM {$GLOBALS['dbTablePre']}media WHERE type = '{$type}' ORDER BY id DESC LIMIT {$offset},{$limit}";
	$media_list = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	
	$currenturl = "index.php?action=site_media&h=list&type={$type}";
   	$pages = multipage( $total['num'], $limit, $page, $currenturl );
   	
   	//note 插入日志
	serverlog(1,$GLOBALS['dbTablePre'].'media','查看媒体报道列表', $GLOBALS['adminid']);
	require(adminTemplate('site_media_list'));
}
//note 添加媒体报道
function site_media_add(){
	$ispost = MooGetGPC('ispost','integer','P');
	if( $ispost ){
		$title = MooGetGPC('title','string','P');
		$type = MooGetGPC('type','string','P');
		$addtime = strtotime(MooGetGPC('addtime','string','P'));
		$source = MooGetGPC('source','string','P');
		$content = MooGetGPC('content','string','P');
		if(empty($content)){salert("内容不能为空");}
		$sql = "INSERT INTO {$GLOBALS['dbTablePre']}media(sid,type,title,content,addtime,source) VALUES({$GLOBALS['adminid']}, '{$type}', '{$title}' ,'{$content}', '{$addtime}', '{$source}')";
		$GLOBALS['_MooClass']['MooMySQL']->query($sql);
		//note 插入日志
		serverlog(3,$GLOBALS['dbTablePre'].'media',"{$GLOBALS['username']}添加媒体报道", $GLOBALS['adminid']);
		salert("添加成功 点击继续添加","index.php?action=site_media&h=add");
	}
	require(adminTemplate('site_media_add'));
}
//note 删除媒体报道
function site_media_del(){
	$id = MooGetGPC('id','integer','G');
	$sql = "DELETE FROM {$GLOBALS['dbTablePre']}media WHERE id = {$id}";
	$GLOBALS['_MooClass']['MooMySQL']->query($sql);
	//note 插入日志
	serverlog(4,$GLOBALS['dbTablePre'].'media',"{$GLOBALS['username']}删除媒体报道", $GLOBALS['adminid']);
	salert("删除成功",'index.php?action=site_media&h=list');
}
//note 编辑媒体报道
function site_media_edit(){
	
	$ispost = MooGetGPC('ispost','integer','P');
	if( $ispost ){
		$id = MooGetGPC('id','integer','P');
		$title = MooGetGPC('title','string','P');
		$type = MooGetGPC('type','string','P');
		$addtime = strtotime(MooGetGPC('addtime','string','P'));
		$source = MooGetGPC('source','string','P');
		$content = MooGetGPC('content','string','P');
		if(empty($content)){salert("内容不能为空");}
		$sql = "UPDATE {$GLOBALS['dbTablePre']}media SET sid = {$GLOBALS['adminid']},type = '{$type}', title = '{$title}', content = '{$content}', addtime = '{$addtime}', source = '{$source}' WHERE id={$id}";
		$GLOBALS['_MooClass']['MooMySQL']->query($sql);
		//note 插入日志
		serverlog(2,$GLOBALS['dbTablePre'].'media',"{$GLOBALS['username']}编辑媒体报道", $GLOBALS['adminid']);
		salert("编辑成功","index.php?action=site_media&h=edit&id=".$id);
	}
	
	$isedit = 1;
	$id = MooGetGPC('id','integer','G');
	$sql = "SELECT * FROM {$GLOBALS['dbTablePre']}media WHERE id = {$id}";
	$news = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	require(adminTemplate('site_media_add'));
}
/***********************************************控制层(C)*****************************************/
$h=MooGetGPC('h','string','G')=='' ? 'list' : MooGetGPC('h','string','G');
//note 动作列表
$hlist = array('list','add','del','edit');
//note 判断页面是否存在
if(!in_array($h,$hlist)){
    MooMessageAdmin('您要打开的页面不存在','index.php?action=site_media');
}
//note 判断是否有权限
if(!checkGroup('site_media',$h)){
    MooMessageAdmin('您没有此操作的权限','index.php?action=site&h=site_media');
}

switch( $h ){
	//note 报道列表
	case 'list':
		site_media_list();	
	break;
	//note 添加媒体报道
	case 'add':
		site_media_add();
	break;
	//note 删除媒体报道
	case 'del':
		site_media_del();
	break;
	//note 编辑媒体报道
	case 'edit':
		site_media_edit();
	break;
}
?>
<?php
//note 需要解决用户提交的情感问题
function site_clinic_solution(){
	$page = max(1,MooGetGPC('page','integer'));
    $limit = 15;
    $offset = ($page-1)*$limit;
	$uid = MooGetGPC('s_uid','integer');
	$type = MooGetGPC('s_type','integer');
	$where = " WHERE uid ".($uid ? '=' : '>')." '{$uid}' and sid='0' ".($type ? 'and type='.$type : '');
   	
    $sql = "SELECT COUNT(*) num FROM {$GLOBALS['dbTablePre']}love_clinic {$where}";
	$total = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	$sql = "SELECT * FROM {$GLOBALS['dbTablePre']}love_clinic {$where} ORDER BY sort DESC LIMIT {$offset},{$limit}";
	$ret_list = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
   	$currenturl = "index.php?action=site_loveclinic&h=solution&uid=".$uid."&type=".$type;
   	$pages = multipage( $total['num'], $limit, $page, $currenturl );
   	
   	//note 插入日志
	//serverlog(1,$GLOBALS['dbTablePre'].'love_clinic','查看情感问题列表', $GLOBALS['adminid']);
	require(adminTemplate('site_loveclinic_solution'));
}
//note 情感诊所问题列表
function site_loveclinic_list(){
	
	$page = max(1,MooGetGPC('page','integer'));
    $limit = 15;
    $offset = ($page-1)*$limit;
    
   	$type = MooGetGPC('type','integer','G') ?  MooGetGPC('type','integer','G') : 1;
	$where = " WHERE sid > '0' and type = " .$type;
   	
    $sql = "SELECT COUNT(*) num FROM {$GLOBALS['dbTablePre']}love_clinic {$where}";
	$total = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
    
	$sql = "SELECT * FROM {$GLOBALS['dbTablePre']}love_clinic {$where} ORDER BY sort DESC LIMIT {$offset},{$limit}";
	$ret_list = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
   	
   	//最上面一条 可上移
   	if($page != 1){
   		$upable = 1;
   		$preoffset = $offset-1;
   		$sql = "SELECT id,sort FROM {$GLOBALS['dbTablePre']}love_clinic {$where} ORDER BY sort DESC LIMIT {$preoffset},1";
		$pre_ret = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
   	}
   	//最下面一条 可下移
   	if($page != ceil($total['num']/$limit)){
   		$downable = 1;
   		$nextoffset = $offset+$limit;
   		$sql = "SELECT id,sort FROM {$GLOBALS['dbTablePre']}love_clinic {$where} ORDER BY sort DESC LIMIT {$nextoffset},1";
		$next_ret = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
   	}
   	$currenturl = "index.php?action=site_loveclinic&h=list&type=".$type;
   	$pages = multipage( $total['num'], $limit, $page, $currenturl );
   	
   	//note 插入日志
	serverlog(1,$GLOBALS['dbTablePre'].'love_clinic','查看情感问题列表', $GLOBALS['adminid']);
	require(adminTemplate('site_loveclinic_list'));
}
//note 添加情感问题
function site_loveclinic_add(){
	$ispost = MooGetGPC('ispost','integer','P');
	if( $ispost ){
		$title = MooGetGPC('title','string','P');
		$type = MooGetGPC('type','integer','P');
		$question = MooGetGPC('question','string','P');
		$answer = MooGetGPC('answer','string','P');
		$addtime = time();
		$sql = "SELECT sort FROM {$GLOBALS['dbTablePre']}love_clinic WHERE type = {$type} ORDER BY sort DESC LIMIT 0,1";
		$ret = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
		$sort = $ret['sort'] + 1;
		
		$sql = "INSERT INTO {$GLOBALS['dbTablePre']}love_clinic(sid,type,title,question,answer,sort,add_time) VALUES('{$GLOBALS['adminid']}', {$type}, '{$title}' ,'{$question}', '{$answer}', '{$sort}', '{$addtime}')";
		$GLOBALS['_MooClass']['MooMySQL']->query($sql);
		//note 插入日志
		serverlog(3,$GLOBALS['dbTablePre'].'love_clinic','添加诊所问题',$GLOBALS['adminid']);
		salert("添加成功 点击继续添加","index.php?action=site_loveclinic&h=add");
	}
	require(adminTemplate('site_loveclinic_add'));
}
//note 删除媒体报道
function site_loveclinic_del(){
	$id = MooGetGPC('id','integer','G');
	$sql = "DELETE FROM {$GLOBALS['dbTablePre']}love_clinic WHERE id = {$id}";
	$GLOBALS['_MooClass']['MooMySQL']->query($sql);
	//note 插入日志
	serverlog(4,$GLOBALS['dbTablePre'].'love_clinic','删除情感诊所问题', $GLOBALS['adminid']);
	salert("删除成功",'index.php?action=site_loveclinic&h=list');
}
//note 编辑媒体报道
function site_loveclinic_edit(){
	$ispost = MooGetGPC('ispost','integer','P');
	if( $ispost ){
		$id = MooGetGPC('id','integer','P');
		$uid = MooGetGPC('uid','integer','P');
		$title = MooGetGPC('title','string','P');
		$type = MooGetGPC('type','integer','P');
		$question = MooGetGPC('question','string','P');
		$answer = MooGetGPC('answer','string','P');

		$sql = "UPDATE {$GLOBALS['dbTablePre']}love_clinic SET sid='{$GLOBALS['adminid']}', type = '{$type}', title = '{$title}', question = '{$question}', answer = '{$answer}' WHERE id = {$id}";
		$GLOBALS['_MooClass']['MooMySQL']->query($sql);
		//note 插入日志
		serverlog(2,$GLOBALS['dbTablePre'].'love_clinic','编辑情感诊所问题', $GLOBALS['adminid'],$uid);
		salert("编辑成功","index.php?action=site_loveclinic&h=edit&id=".$id);
	}
	
	$isedit = 1;
	$id = MooGetGPC('id','integer','G');
	$sql = "SELECT * FROM {$GLOBALS['dbTablePre']}love_clinic WHERE id = {$id}";
	$question = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	require(adminTemplate('site_loveclinic_add'));
}
//note 上下移动情感问题
function site_loveclinic_sort(){
	$type = MooGetGPC('type','integer','G');
	$page = MooGetGPC('page','integer','G');
	$c = MooGetGPC('c','string','G');
	$id = explode('.',$c);
	
	$sql = "UPDATE {$GLOBALS['dbTablePre']}love_clinic SET sort = {$id[3]} WHERE id = {$id[0]}";
	$GLOBALS['_MooClass']['MooMySQL']->query($sql);
	$sql = "UPDATE {$GLOBALS['dbTablePre']}love_clinic SET sort = {$id[1]} WHERE id = {$id[2]}";
	$GLOBALS['_MooClass']['MooMySQL']->query($sql);
	
	header("Location:index.php?action=site_loveclinic&h=list&type={$type}&page={$page}");
}
/***********************************************控制层(C)*****************************************/
$h=MooGetGPC('h','string','G')=='' ? 'list' : MooGetGPC('h','string','G');
//note 动作列表
$hlist = array('list','add','del','edit','sort', 'solution');
//note 判断页面是否存在
if(!in_array($h,$hlist)){
    MooMessageAdmin('您要打开的页面不存在','index.php?action=site_loveclinic');
}
//note 判断是否有权限
if(!checkGroup('site_loveclinic',$h)){
	MooMessageAdmin('您没有此操作的权限','index.php?action=site&h=site_loveclinic');
}

switch( $h ){
	//note 需要解决情感问题
	case 'solution':
		site_clinic_solution();
	break;
	//note 情感问题列表
	case 'list':
		site_loveclinic_list();	
	break;
	//note 添加情感问题
	case 'add':
		site_loveclinic_add();
	break;
	//note 删除情感问题
	case 'del':
		site_loveclinic_del();
	break;
	//note 编辑情感问题
	case 'edit':
		site_loveclinic_edit();
	break;
	//note 上下移动情感问题
	case 'sort':
		site_loveclinic_sort();
	break;
}
?>
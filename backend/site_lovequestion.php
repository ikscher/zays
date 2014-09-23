<?php
//note 爱情测试题列表
function site_lovequestion_list(){
	global $love_type;
	$page = max(1,MooGetGPC('page','integer'));
    $limit = 15;
    $offset = ($page-1)*$limit;
    
    $sql = "SELECT COUNT(lid) num FROM {$GLOBALS['dbTablePre']}love_question";
    
	$total = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
    
	$sql = "SELECT lid,title,qid,ty_pe,ask1,ask2,ask3,ask3,ask4,ask5 FROM {$GLOBALS['dbTablePre']}love_question ORDER BY lid DESC LIMIT {$offset},{$limit}";
	$title_list = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	
	$currenturl = "index.php?action=site_lovequestion&h=list";
   	$pages = multipage( $total['num'], $limit, $page, $currenturl );
   	
   	//note 插入日志
	serverlog(1,$GLOBALS['dbTablePre'].'love_question','查看爱情测试题列表', $GLOBALS['adminid']);
	require(adminTemplate('site_lovequestion_list'));
}
//note 添加爱情测试题目
function site_lovequestion_add(){
	global $love_type;
	$ispost  = MooGetGPC('ispost','integer','P');
	if( $ispost ){
		$qid  = MooGetGPC('lovetype','integer','P');
		$ty_pe  = MooGetGPC('ty_pe','integer','P');
		$title = MooGetGPC('title','string','P');
		$ask1 = MooGetGPC('askvalue1','integer','P');
		$ask2 = MooGetGPC('askvalue2','integer','P');
		$ask3 = MooGetGPC('askvalue3','integer','P');
		$ask4 = MooGetGPC('askvalue4','integer','P');
		$ask5 = MooGetGPC('askvalue5','integer','P');
		
		$sql = "INSERT INTO {$GLOBALS['dbTablePre']}love_question(qid,title,ty_pe,ask1,ask2,ask3,ask4,ask5) VALUES({$qid},'{$title}',{$ty_pe},{$ask1},{$ask2},{$ask3},{$ask4},{$ask5})";
		$GLOBALS['_MooClass']['MooMySQL']->query($sql);
		//note 插入日志
		serverlog(3,$GLOBALS['dbTablePre'].'love_question','添加爱情测试题目', $GLOBALS['adminid']);
		salert("添加成功","index.php?action=site_lovequestion&h=add");
	}
	$sql = "SELECT * FROM {$GLOBALS['dbTablePre']}love_test ";
	$lovetype_list = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	
	require(adminTemplate('site_lovequestion_add'));
}
//note 删除爱情测试题目
function site_lovequestion_del(){
	$tid = MooGetGPC('tid','integer','G');
	$sql = "DELETE FROM {$GLOBALS['dbTablePre']}love_question WHERE lid = {$tid}";
	$GLOBALS['_MooClass']['MooMySQL']->query($sql);
	//note 插入日志
	serverlog(4,$GLOBALS['dbTablePre'].'love_question','删除爱情测试题目', $GLOBALS['adminid']);
	salert("删除成功",'index.php?action=site_lovequestion&h=list');
}
/***********************************************控制层(C)*****************************************/
$h=MooGetGPC('h','string','G')=='' ? 'list' : MooGetGPC('h','string','G');
//note 动作列表
$hlist = array('list','add','del');
//note 判断页面是否存在
if(!in_array($h,$hlist)){
    MooMessageAdmin('您要打开的页面不存在','index.php?action=site_lovequestion');
}
//note 判断是否有权限
if(!checkGroup('site_lovequestion',$h)){
    MooMessageAdmin('您没有此操作的权限','index.php?action=site&h=site_lovequestion');
}
//测试类型
$love_type=array(1=>'理想的爱情',2=>'吸引您的他/她',3=>'爱情的习性(初相识)',4=>'爱情的习性(相恋中)',5=>'爱情的习性(承诺与顾虑)',6=>'爱情的习性(婚姻家庭)',7=>'潜伏的危机',8=>'隐藏的一面',9=>'红娘给您的忠告');
switch($h){
	//note 爱情测试题列表
	case 'list':
		site_lovequestion_list();
	break;
	//note 添加爱情测试题目
	case 'add':
		site_lovequestion_add();
	break;
	//note 删除爱情测试题目
	case 'del':
		site_lovequestion_del();
	break;
	
}
?>
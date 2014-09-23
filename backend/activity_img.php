<?php
//note 配置文件
require './include/config.php';

//note 加载Moo框架
require '../framwork/MooPHP.php';

//note 加载后台共用方法文件
require './include/function.php';

//note 权限菜单分类配置文件  
require './include/menu.inc.php';
$db = $_MooClass['MooMySQL'];
function activite_list(){
	 $db = $GLOBALS['db'];
	 $page_per = 4;
	 if($_REQUEST['page_now']){
	 	$page = $_REQUEST['page_now'];
	 }else{
	 	$page = 1;
	 }
	 $limit = 4;
	 $offset = ($page-1)*$limit;
    $sql = "SELECT COUNT(mid) AS COUNT from web_activity_img";
    $result = $db->getOne($sql);
    $total = $result['COUNT'];
    $pagenum = ceil($total/$page_per);
    for($i=1;$i<=$pagenum;$i++){
    	$page_num[] = $i;
    }
   $page_now = $page;
    $sql="select * from web_activity_img  order by mid desc LIMIT {$offset},{$limit} ";
	$content = $db->getAll($sql);
	
	require(adminTemplate('activity_img'));
    exit;
}
function activite_add(){
	require(adminTemplate('activity_img_add'));
	exit;
}
function activite_delect(){
	$db = $GLOBALS['db'];
	$mid = $_REQUEST['mid'];
	$sql = "delete FROM web_activity_img WHERE mid = '$mid'";
	$db->query($sql);
	MooMessageAdmin('删除成功','activity_img.php',2);
	exit;
}
function activite_edit(){
	$db = $GLOBALS['db'];
	$mid = $_REQUEST['mid'];
	$sql = "select * from web_activity_img where mid = '$mid'";
	$content = $db->getOne($sql);
	
	$group = $content[group];
	$sort = $content[sort];
	$addresses = explode('/',$content[address]);
	$address = array_pop($addresses);
	$hreffs = explode('=',$content[href]);
	$href = array_pop($hreffs);
	
	require(adminTemplate('activity_img_add'));
	exit;
}
function activite_sub(){
	
	$db = $GLOBALS['db'];
	$href  = 'http://www.7651.com/index.php?n=activity&h='.$_REQUEST['href'];
	$address = 'module/activity/templates/default/images/activity_new/'.$_REQUEST['address'];
	$group = $_REQUEST['group'];
	$sort = $_REQUEST['sort'];
	if($_REQUEST['mid']){
		$mid = $_REQUEST['mid'];
		$sql = "update web_activity_img SET `group` = '$group',`address` = '$address',`href` = '$href',`sort` = '$sort' where mid = '$mid'";
		$db->query($sql);
		MooMessageAdmin('编辑成功','activity_img.php',2);
		exit;
	}else{
		$sql = "select mid from web_activity_img where `href` = '$href' or `address` = '$address' or (`group` = '$group' and `sort` = '$sort')";
		if($db->getOne($sql)){
			MooMessageAdmin('信息有错误','activity_img.php?act=add',2);
			exit;
		}else{
			$sql = "insert into web_activity_img (`group`,`address`,`href`,`sort`) values ('$group','$address','$href','$sort')";
			$db->query($sql);
			MooMessageAdmin('添加成功','activity_img.php',2);
			exit;
		}
	
	}
	
}
$h=MooGetGPC('h','string','R')=='' ? 'list' : MooGetGPC('h','string','R');
$act=MooGetGPC('act','string','R')=='' ? 'list' : MooGetGPC('act','string','R');
switch($act){
    case 'sub':
        activite_sub();
    break;
    case 'delect':
    	 activite_delect();
    break;
    case 'edit':
    	 activite_edit();
    break;
    case 'add':
        activite_add();
    break;
    default:
    	activite_list();
    break;
}

?>
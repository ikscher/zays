<?php
//mark
//志愿者合作
$cooperation_config=array('0'=>'志愿者服务','1'=>'合作举办活动','2'=>'活动赞助','3'=>'员工联谊','4'=>'策划企业活动','5'=>'其他','6'=>'主持人');
$activities = array('0'=>'爱的宣言','1'=>'只因在人群中多看你一眼','2 '=>'太原倾城热恋','3'=>'成都线下相亲享有派对','4'=>'香水魔咒','6'=>'爱的足迹','7'=>'北方有佳人','8'=>'原来，幸福离我们那么近','9'=>'瞬间情意，一生记忆','10'=>'有没有那么一首歌会让你想起我','11'=>'缘分，只有今生没有来世','12'=>'弱水三千，我只取一瓢','13'=>'牵手的那一刻，爱已永恒');
function cooperation_list(){
    $page = max(1,MooGetGPC('page','integer','G'));
    $limit = 20;
    $offset = ($page-1)*$limit;
    $where=array();
    $where['cooperation']=MooGetGPC('cooperation','integer','P');
    $where['province']=MooGetGPC('province','integer','P');
    $where['city']=MooGetGPC('city','integer','P');
    if(!empty($where)){
           foreach($where as $key=>$value){
               if(!empty($value)){
                   $where[$key]='`'.$key.'`=\''.$value.'\'';
               }else{
                unset($where[$key]);
               }
           }
    }
    $where=empty($where)?'':'where '.implode(' and ',$where);
    $count=$GLOBALS['_MooClass']['MooMySQL']->getOne('SELECT COUNT(`id`) AS num FROM '.$GLOBALS['dbTablePre'].'cooperation '.$where);
    $data=$GLOBALS['_MooClass']['MooMySQL']->getAll('SELECT * FROM '.$GLOBALS['dbTablePre'].'cooperation '.$where.' ORDER BY id DESC LIMIT  '.$offset.','.$limit);
    $currenturl = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"]; 
	$currenturl2 = preg_replace("/(&page=\d+)/","",$currenturl);
    $total['num'] = isset($total['num']) && $total['num'] ? $total['num'] : 0;
	$pages = multipage( $total['num'], $limit, $page, $currenturl );
    $page_num = ceil($total['num']/$limit);
    $cooperation_config=$GLOBALS['cooperation_config'];
    $activities=$GLOBALS['activities'];
    serverlog(1,$GLOBALS['dbTablePre'].'cooperation',"{$GLOBALS['username']}查看志愿者合作列表", $GLOBALS['adminid']);
    require(adminTemplate('active_cooperation_list'));
    exit;
}

function cooperation_search(){
	
    $page = max(1,MooGetGPC('page','integer','G'));
    $limit = 20;
    $offset = ($page-1)*$limit;
    $where=array();
    $where['sid']=MooGetGPC('sid','integer','P');
    $where['activities']=MooGetGPC('activities','string','P');
    $where['cooperation']=MooGetGPC('cooperation','integer','P');
    if(!empty($where)){
           foreach($where as $key=>$value){
               if(!empty($value)){
                   $where[$key]='`'.$key.'`=\''.$value.'\'';
               }else{
                unset($where[$key]);
               }
           }
    }
    $where=empty($where)?'':'where '.implode(' and ',$where);
    $count=$GLOBALS['_MooClass']['MooMySQL']->getOne('SELECT COUNT(`id`) AS num FROM '.$GLOBALS['dbTablePre'].'cooperation '.$where);
    $data=$GLOBALS['_MooClass']['MooMySQL']->getAll('SELECT * FROM '.$GLOBALS['dbTablePre'].'cooperation '.$where.' ORDER BY id DESC LIMIT  '.$offset.','.$limit);
    
    $currenturl = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"]; 
	$currenturl2 = preg_replace("/(&page=\d+)/","",$currenturl);
    $total['num'] = isset($total['num']) && $total['num'] ? $total['num'] : 0;
	$pages = multipage( $total['num'], $limit, $page, $currenturl );
    $page_num = ceil($total['num']/$limit);
    $cooperation_config=$GLOBALS['cooperation_config'];
    $activities=$GLOBALS['activities'];
    $activitie = MooGetGPC('activities','string','P');
    $sids = MooGetGPC('sid','integer','P');
    serverlog(1,$GLOBALS['dbTablePre'].'cooperation',"{$GLOBALS['username']}查看志愿者合作列表", $GLOBALS['adminid']);
    require(adminTemplate('active_cooperation_list'));
    exit;
}
function cooperation_edit(){
	 $uid=MooGetGPC('uid','integer','R');
	 $data=$GLOBALS['_MooClass']['MooMySQL']->getAll('SELECT * FROM '.$GLOBALS['dbTablePre'].'cooperation where uid = '.$uid);
	 $activities=$GLOBALS['activities'];
	 $v = $data[0];
	 
	  require(adminTemplate('active_cooperation_edit'));
	 exit;
}
function cooperation_update(){
	$uid=MooGetGPC('uid','integer','R');
	$activities=MooGetGPC('activities','string','R');
	$sql = "UPDATE {$GLOBALS['dbTablePre']}cooperation set activities = '$activities' WHERE uid = '$uid'";
	$GLOBALS['_MooClass']['MooMySQL']->query($sql);
	$cooperation_config=$GLOBALS['cooperation_config'];
	$activities=$GLOBALS['activities'];
	require(adminTemplate('active_cooperation_list'));
    exit;
}
function cooperation_remark_update(){
	$cooperation_config=$GLOBALS['cooperation_config'];
	$activities=$GLOBALS['activities'];
	$uid=MooGetGPC('uid','integer','R');
	$remark=MooGetGPC('remark','string','R');
	$sql = "UPDATE {$GLOBALS['dbTablePre']}cooperation set remark = '$remark' WHERE uid = '$uid'";
	$GLOBALS['_MooClass']['MooMySQL']->query($sql);
	require(adminTemplate('active_cooperation_list'));
	exit;
}
function cooperation_remark(){
	 $uid=MooGetGPC('uid','integer','R');
	 $data=$GLOBALS['_MooClass']['MooMySQL']->getAll('SELECT * FROM '.$GLOBALS['dbTablePre'].'cooperation where uid = '.$uid);
	 $v = $data['0'];
	  require(adminTemplate('active_cooperation_remark'));
	 exit;
}


$h=MooGetGPC('h','string','R')=='' ? 'list' : MooGetGPC('h','string','R');
$act=MooGetGPC('act','string','R')=='' ? 'list' : MooGetGPC('act','string','R');
//note 动作列表
$hlist = array('list','search','edit','remark','update');
//note 判断页面是否存在
if(!in_array($h,$hlist)){
    MooMessageAdmin('您要打开的页面不存在','index.php?action=site_media');
}

//note 判断是否有权限
if(!checkGroup('active_cooperation',$h)){
    MooMessageAdmin('您没有此操作的权限','index.php?action=site&h=site_media');
}
switch($act){
    case 'search':
        cooperation_search();
    break;
    case 'edit':
       cooperation_edit();
    break;
    case 'remark':
       cooperation_remark();
    break;
    case 'remark_update':
       cooperation_remark_update();
    break;
    case 'update':
       cooperation_update();
    break;
    default:
    	cooperation_list();
    break;
}
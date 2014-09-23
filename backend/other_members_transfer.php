<?php
function transfer_list(){
	$page = max(1,MooGetGPC('page','integer','G'));
    $limit = 16;
    $offset = ($page-1)*$limit;
    
	$uid = MooGetGPC('uid','integer','P');
	$sid = MooGetGPC('sid','integer','P');
	$s_str = $and = '';
	if($uid > 0){ $s_str .= 'uid like \'%'.$uid.'%\'';$and = ' AND ';}
	if($sid > 0){ $s_str .= $and.'sid='.$sid;}
	if(!empty($s_str)){
		$s_str = ' WHERE '.$s_str;
	}
	
	$sql = "SELECT * FROM {$GLOBALS['dbTablePre']}members_transfer {$s_str} ORDER BY id DESC LIMIT {$offset},{$limit}";
	$transfer = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	$total = getcount('members_transfer',$s_str);
	$currenturl = "index.php?action=other_members_transfer&h=list";
   	$pages = multipage( $total, $limit, $page, $currenturl );
	require_once(adminTemplate('transfer_list'));	
}

function transfer_modify(){
	$id = MooGetGPC('id','integer','G');
	if($_POST['ispost']){
		$id = MooGetGPC('modifyid','integer','P');
		$data['sid'] = MooGetGPC('sid','integer','P');
		$data['uid'] = MooGetGPC('uid','integer','P');
		$data['servicetime'] = MooGetGPC('servicetime','integer','P');
		$data['payments'] = MooGetGPC('payments','integer','P');
		$data['otheruid'] = MooGetGPC('otheruid','integer','P');
		$data['chatnotes'] = MooGetGPC('chatnotes','string','P');
		$data['intro'] = MooGetGPC('intro','string','P');
		$data['otherintro'] = MooGetGPC('otherintro','string','P');
		$data['lastcom'] = MooGetGPC('lastcom','string','P');
		$data['remark'] = MooGetGPC('remark','string','P');
		
		foreach($data as $k => $v){
			$set_pram[] .= $k."='{$v}'";
		}
		$set_pram = implode(',',$set_pram);
		$sql = "UPDATE {$GLOBALS['dbTablePre']}members_transfer SET {$set_pram} WHERE id=".$id;
		$GLOBALS['_MooClass']['MooMySQL']->query($sql);
		salert('修改成功！','index.php?action=other_members_transfer&h=list');
		
	}elseif($id > 0){
		$sql = "SELECT * FROM {$GLOBALS['dbTablePre']}members_transfer WHERE `id`=".$id;
		$transfer = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	}
	
	require adminTemplate('transfer_modify');
}

function transfer_del(){
	$id = MooGetGPC('id','integer','G');
	$sql = "DELETE FROM {$GLOBALS['dbTablePre']}members_transfer WHERE `id`=".$id;
	$GLOBALS['_MooClass']['MooMySQL']->query($sql);
	salert("删除成功","index.php?action=other_members_transfer&h=list");
}
/***********************************************控制层(C)*****************************************/
$h=MooGetGPC('h','string');

//note 动作列表
$hlist = array('list','modify','del');

//note 判断页面是否存在
if(!in_array($h,$hlist)){
	salert('您要打开的页面不存在');
}
//note 判断是否有权限
if(!checkGroup('other_members_transfer',$h)){
	salert('您没有修改此操作的权限');
}

switch($h){
	case 'list':
		transfer_list();
	break;
	case 'modify':
		transfer_modify();	
	break;
	case 'del':
		transfer_del();	
	break;
	default:
		transfer_list();
	break;
}
?>
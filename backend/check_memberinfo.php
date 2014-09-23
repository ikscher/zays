<?php
/**
 * 检查普通客服修改用户资料
 * @author:fanglin
 */

function check_memberinfo_list(){
	//所管理的客服id列表
	$sql_where  = '';
    $myservice_idlist = get_myservice_idlist();

	if(empty($myservice_idlist)){
		$sql_where = " AND sid IN({$GLOBALS['adminid']})";
	}elseif($myservice_idlist == 'all'){
			//all为客服主管能查看所有的
	}else{
		$sql_where = " AND sid IN($myservice_idlist)";
	}
	
	$sql = "SELECT * FROM {$GLOBALS['dbTablePre']}admin_editmembersinfo_sql WHERE status=0 $sql_where ORDER BY id DESC";
	$list = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
	require adminTemplate("check_memberinfo_list");
}

function check_memberinfo_detail(){
	$id = MooGetGPC('id','integer','G');
	$sql = "SELECT * FROM {$GLOBALS['dbTablePre']}admin_editmembersinfo_sql WHERE id='{$id}'";
	$res = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
	$uid = $res['uid'];
	
	//审核通过
	if($_GET['status'] == 1){
		
		$sql = base64_decode($res['exec_members_sql']);
		$GLOBALS['_MooClass']['MooMySQL']->query($sql);
		//var_dump($sql);
		$sql = base64_decode($res['exec_memberfield_sql']);
		$GLOBALS['_MooClass']['MooMySQL']->query($sql);
		//var_dump($sql);

		$sql = "UPDATE {$GLOBALS['dbTablePre']}admin_editmembersinfo_sql SET status=1 WHERE id='{$id}'";
		$GLOBALS['_MooClass']['MooMySQL']->query($sql);
		
		//note 更新用户数据				
		//fastsearch_update($uid,1);
		//fastsearch_update($uid,2);
		
		salert('操作成功');exit;
	}
	//审核不通过
	if($_GET['status'] == 2){
		$sql = "UPDATE {$GLOBALS['dbTablePre']}admin_editmembersinfo_sql SET status=2 WHERE id='{$id}'";
		$GLOBALS['_MooClass']['MooMySQL']->query($sql);
		salert('操作成功，您已拒绝此次对会员资料的修改');exit;
	}
	$userinfo = $GLOBALS['_MooClass']['MooMySQL']->getOne("SELECT * FROM {$GLOBALS['dbTablePre']}members WHERE uid='$uid'",true);
	$userfield = $GLOBALS['_MooClass']['MooMySQL']->getOne("SELECT * FROM {$GLOBALS['dbTablePre']}memberfield WHERE uid='$uid'",true);
	
	//修改过的
	$userinfo_new = $GLOBALS['_MooClass']['MooMySQL']->getOne("SELECT * FROM {$GLOBALS['dbTablePre']}members_edithistory WHERE uid='$uid'",true);
	$userfield_new = $GLOBALS['_MooClass']['MooMySQL']->getOne("SELECT * FROM {$GLOBALS['dbTablePre']}memberfield_edithistory WHERE uid='$uid'",true);
	
	require adminTemplate("check_memberinfo_detail");
}

/***********************************************控制层(C)*****************************************/
$h=MooGetGPC('h','string');

//note 动作列表
$hlist = array('list','detail');

//note 判断页面是否存在
if(!in_array($h,$hlist)){
	salert('您要打开的页面不存在');
}
//note 判断是否有权限
if(!checkGroup('check_memberinfo',$h)){
  salert('您没有修改此操作的权限');
}

switch($h){
	case 'list':
		check_memberinfo_list();
	break;
	case 'detail':
		check_memberinfo_detail();
	break;
    default:
    	exit('文件不存在');
    break;
    
}
?>
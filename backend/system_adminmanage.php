<?php
/**
 * 组管理程序
 * @author:fanglin
 * 2010年06月23日
 */

/*******************************************逻辑层(M)/表现层(V)*****************************************/
//note 组管理列表
function system_adminmanage_grouplist(){
	$total=$limit=$page=$currenturl='';
	$manage_list='';
	
	/* $sql="update web_admin_user set groupid='81' where uid=45 or uid=177";
	$GLOBALS['_MooClass']['MooMySQL']->query($sql); */
	
	if(in_array($GLOBALS['groupid'],$GLOBALS['admin_service_arr'])){//主管
	   $sql="select * from web_admin_manage where type=1 order by id asc";
	}elseif(in_array($GLOBALS['groupid'],$GLOBALS['admin_service_team']) || in_array($GLOBALS['groupid'],$GLOBALS['admin_service_manager'])){//队长
	   $sql="select id,manage_list from web_admin_manage where type=2  and leader_uid={$GLOBALS['adminid']}";
	   $list=$GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	   $comma='';
	   foreach($list as $value){
	      $manage_list.=$comma.$value['manage_list'];
		  $comma=",";
	   }   
	   
	   $sql="select * from web_admin_manage where id in({$manage_list}) order by id asc";
	}elseif(in_array($GLOBALS['groupid'],$GLOBALS['admin_service_after'])){//售后主管
		$sql = "SELECT * FROM web_admin_manage WHERE type=1 and manage_type=2 ORDER BY id ASC";
	}else{//组长
	   
	   $sql="select id,manage_list from web_admin_manage where type=1 order by id asc";
	   $list=$GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	   if(!empty($list)){
	     foreach($list as $value){
		    $manage_list='';
	        $id='';
	        $manage_list=$value['manage_list'];
			
			$manage_arr=explode(',',$manage_list);
			if(in_array($GLOBALS['adminid'],$manage_arr)){
			   $id=$value['id'];
	           $where=" where id={$id}";
			   break;
			}
	     }
		}
		$sql="select * from web_admin_manage where id={$id} ";
	   
	}

	
	
	//原先代码
	/* if(in_array($GLOBALS['groupid'],$GLOBALS['admin_service_after'])){
		$sql = "SELECT * FROM {$GLOBALS['dbTablePre']}admin_manage WHERE type=1 and manage_type=2 ORDER BY id ASC";
	}else{
		$sql = "SELECT * FROM {$GLOBALS['dbTablePre']}admin_manage WHERE type=1 ORDER BY id ASC";
	} */
	
   	$manage_list = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
    require_once(adminTemplate('adminmanage_list'));
}

//note 添加组
function system_adminmanage_addgroup(){
	if($_POST){
		$manage_list = MooGetGPC('userlist','string','P');
		if($manage_list){
			$manage_list = array_unique($manage_list);
			$arr['manage_list'] = implode(',',$manage_list);	
		}
		$arr['manage_name'] =  MooGetGPC('manage_name','string','P');
		$arr['manage_desc'] =  MooGetGPC('manage_desc','string','P');
		$arr['manage_type'] =  MooGetGPC('manage_type','integer','P');
		$arr['type'] = 1;
		$arr['dateline'] = time();
		inserttable('admin_manage',$arr);
		salert('添加成功');exit;
	}
    require_once(adminTemplate('adminmanage_addgroup'));
}

//管理组成员
function system_adminmanage_groupmember(){
	$id = MooGetGPC('id','integer','G');
	$sql = "SELECT manage_list FROM {$GLOBALS['dbTablePre']}admin_manage WHERE id='$id'";
	$group = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	
	if($group['manage_list']){
		$sql = "SELECT u.uid,u.usercode,u.username,u.name,g.groupname FROM {$GLOBALS['dbTablePre']}admin_user u
				LEFT JOIN {$GLOBALS['dbTablePre']}admin_group g USING(groupid)
				WHERE uid IN({$group['manage_list']})";
		$admin_user_list = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql); 
		
	}
	require_once(adminTemplate('adminmanage_groupmember'));
}

//添加组成员
function system_adminmanage_addgroupmember(){
    $choose_user=array();
	$id = MooGetGPC('id','integer','P');
	$choose_user_ = MooGetGPC('userlist','array','P');
    
    $choose_user_list_=implode(',',$choose_user_);
    $sql="select uid from web_admin_user where usercode in ({$choose_user_list_})";

    $result=$GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
    foreach($result as $k=>$v){
        $choose_user[$k]=$v['uid'];
    }

	$manage_list = get_manage_member($id);
	if(!empty($manage_list)){
		$manage_list = explode(',',$manage_list);
		$choose_user = array_unique(array_merge($choose_user,$manage_list));
	}else{
		$choose_user = array_unique($choose_user);
	}
	asort($choose_user);
	$choose_user_list = implode(',',$choose_user);
	$sql = "UPDATE {$GLOBALS['dbTablePre']}admin_manage SET manage_list='{$choose_user_list}' WHERE id='{$id}'";
	$GLOBALS['_MooClass']['MooMySQL']->query($sql);
	salert('添加成功','index.php?action=system_adminmanage&h=groupmember&id='.$id);
	exit;
}

//删除组
function system_adminmanage_delgroup(){
	$id = MooGetGPC('id','integer','G');
	del_record('admin_manage','id',$id);
	salert('删除成功','index.php?action=system_adminmanage&h=grouplist');	
}

//编辑组
function system_adminmanage_editgroup(){
	if($_POST){
		$id = MooGetGPC('id','integer','P');
		$arr['manage_name'] =  MooGetGPC('manage_name','string','P');
		$arr['manage_desc'] =  MooGetGPC('manage_desc','string','P');
		$arr['manage_type'] =  MooGetGPC('manage_type','integer','P');
		updatetable('admin_manage',$arr,array('id'=>$id));
		salert('修改成功');exit;
	}
	$id = MooGetGPC('id','integer','G');
	$sql = "SELECT * FROM {$GLOBALS['dbTablePre']}admin_manage WHERE id='$id'";
	$group = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	require_once(adminTemplate('adminmanage_editgroup'));
}

//删除组成员
function system_adminmanage_del_groupmember(){
	$id = MooGetGPC('groupid','integer','G');
	$uid = MooGetGPC('uid','integer','G');
	
	//得到组成员列表
	$group_member = get_manage_member($id);
	$group_member_arr = explode(',',$group_member);
	
	$key = array_search($uid,$group_member_arr);
	unset($group_member_arr[$key]);
	
	$group_member_list = implode(',',$group_member_arr);
	
	$sql = "UPDATE {$GLOBALS['dbTablePre']}admin_manage SET manage_list='{$group_member_list}' WHERE id='$id'";
	$GLOBALS['_MooClass']['MooMySQL']->query($sql);
	
	salert('删除成功','index.php?action=system_adminmanage&h=groupmember&id='.$id);	
}

//设为组长
function system_adminmanage_set_leader(){
	$id = MooGetGPC('groupid','integer','G');
	$uid = MooGetGPC('uid','integer','G'); 
	
	$sql = "SELECT username FROM  {$GLOBALS['dbTablePre']}admin_user WHERE uid='{$uid}'";
	$user = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	
	$arr['leader_uid'] = $uid;
	$arr['leader_username'] = $user['username'];
	updatetable('admin_manage',$arr,array('id'=>$id));
	salert('设置成功','index.php?action=system_adminmanage&h=groupmember&id='.$id);	
}

function system_adminmanage_change(){
	$referer=$_SERVER['HTTP_REFERER'];
	$group=MooGetGPC('group','array','P');
	if(empty($group)){
		MooMessageAdmin('请确认信息的完整性',$referer,1);
	}
	$interval=MooGetGPC('interval','array','P');
	$allot=MooGetGPC('allot','array','P');
	$amount=MooGetGPC('amount','array','P');
	$money=MooGetGPC('money','array','P');
	$data=$update=$uids=array();
	foreach ($group as $value){
		$data[]='(\''.$value.'\',\''.$interval[$value].'\',\''.$allot[$value].'\',\''.$amount[$value].'\',\''.$money[$value].'\')';
	}
	$sql='SELECT `id`,`manage_list` FROM`'.$GLOBALS['dbTablePre'].'admin_manage` WHERE `id` in ('.implode(',',$group).')';
	$manage_list_data=$GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	foreach($manage_list_data as $v){
		$manage_list=array();
		if(!empty($v['manage_list'])){
			$manage_list=explode(',', $v['manage_list']);
			foreach($manage_list as $uid){
				if(!empty($uid)){
					$uids[$uid]=$v['id'];
				}
			}
		}
	}
	$userdata=$GLOBALS['_MooClass']['MooMySQL']->getAll('SELECT `uid` FROM`'.$GLOBALS['dbTablePre'].'admin_user` WHERE `uid` in ('.implode(',',array_keys($uids)).')');
	foreach($userdata as $uid){
		if(!empty($uid['uid'])){
			$groupid=$uids[$uid['uid']];
			$update[]='(\''.$uid['uid'].'\',\''.$interval[$groupid].'\',\''.$allot[$groupid].'\',\''.$amount[$groupid].'\',\''.$money[$groupid].'\')';
		}
	}
	$group_sql=empty($data)?'':'insert into `'.$GLOBALS['dbTablePre'].'admin_manage` (`id`,`interval`,`allot`,`amount`,`money`) values  '.implode(',', $data).' on duplicate key update `interval`=values(`interval`),`allot`=values(`allot`),`amount`=values(`amount`),`money`=values(`money`)';
	$user_sql=empty($update)?'':'insert into `'.$GLOBALS['dbTablePre'].'admin_user` (`uid`,`interval`,`allot`,`amount`,`money`) values  '.implode(',', $update).' on duplicate key update `interval`=values(`interval`),`allot`=values(`allot`),`amount`=values(`amount`),`money`=values(`money`)';
	$user_result=empty($user_sql)?TRUE:$GLOBALS['_MooClass']['MooMySQL']->query($user_sql);
	$group_result=empty($group_sql)?TRUE:$GLOBALS['_MooClass']['MooMySQL']->query($group_sql);
	if($user_result && $group_result){
		MooMessageAdmin('群组红娘币规则设置成功',$referer,1);
	}else{
		MooMessageAdmin('群组红娘币规则设置失败<br/>GROUP_SQL=>'.$group_sql.'<br/>USER_SQL=>'.$user_sql,$referer,1);
	}
}
/***********************************************控制层(C)*****************************************/
$h=MooGetGPC('h','string')=='' ? 'list' : MooGetGPC('h','string');

//note 动作列表
$hlist = array('grouplist','addgroup','groupmember','addgroupmember','delgroup','editgroup','del_groupmember','set_leader','change');
//note 判断页面是否存在
if(!in_array($h,$hlist)){
	salert('打开的页面不存在');exit;    
}
//note 判断是否有权限
$nav_list = checkGroup('system_adminmanage',$h);
if(!$nav_list){
	salert('您没有此操作权限');exit;
}

include './include/system_function.php';

switch($h){
	//组管理
    case 'grouplist' : 
    	system_adminmanage_grouplist();
    break;
    case 'addgroup' : 
    	system_adminmanage_addgroup();
    break;
    case 'groupmember':
    	system_adminmanage_groupmember();
    break;
    case 'addgroupmember':
    	system_adminmanage_addgroupmember();
    break;
    case 'delgroup':
    	system_adminmanage_delgroup();
    break;
    case 'editgroup':
    	system_adminmanage_editgroup();
    break;
    case 'del_groupmember':
    	system_adminmanage_del_groupmember();
    break;
    case 'set_leader':
		system_adminmanage_set_leader();
	break;
	case 'change':
		system_adminmanage_change();
		break;
}
?>
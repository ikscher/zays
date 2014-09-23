<?php
/**
 * 队管理程序
 * @author:fanglin
 * 2010年06月23日
 */

/*******************************************逻辑层(M)/表现层(V)*****************************************/
//note 队管理列表
function system_adminteam_teamlist(){
	$total=$limit=$page=$currenturl=$group='';
	$sql = "SELECT * FROM {$GLOBALS['dbTablePre']}admin_manage WHERE type=2 ORDER BY id ASC";
   	$team_list = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
   
    require_once(adminTemplate('adminteam_list'));
}

//note 添加队
function system_adminteam_addteam(){
	if($_POST){
		$manage_list = MooGetGPC('grouparr','string','P');
		if($manage_list){
			$manage_list = array_unique($manage_list);
			$arr['manage_list'] = implode(',',$manage_list);	
		}
		$arr['manage_name'] =  MooGetGPC('manage_name','string','P');
		$arr['manage_desc'] =  MooGetGPC('manage_desc','string','P');
		$arr['leader_uid'] = MooGetGPC('team_leader','string','P');
		$arr['type'] = 2;
		inserttable('admin_manage',$arr);
		salert('添加成功');exit;
	}
    require_once(adminTemplate('adminteam_addteam'));
}

//管理队成员
function system_adminteam_teammember(){
	$id = MooGetGPC('id','integer','G');
	$sql = "SELECT manage_list FROM {$GLOBALS['dbTablePre']}admin_manage WHERE id='$id'";
	$group = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	
	if($group['manage_list']){
		$sql = "SELECT * FROM {$GLOBALS['dbTablePre']}admin_manage WHERE id IN({$group['manage_list']})";
		$manage_list = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql); 
	}
	require_once(adminTemplate('adminteam_teammember'));
}

//添加队成员
function system_adminteam_addteammember(){
	$id = MooGetGPC('id','integer','P');
	$choose_user = MooGetGPC('grouparr','string','P');

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
	salert('添加成功','index.php?action=system_adminteam&h=teammember&id='.$id);
	exit;
}

//删除组
function system_adminteam_delteam(){
	$id = MooGetGPC('id','integer','G');
	del_record('admin_manage','id',$id);
	salert('删除成功','index.php?action=system_adminteam&h=teamlist');	
}

//编辑队
function system_adminteam_editteam(){
	if($_POST){
		$id = MooGetGPC('id','integer','P');
		$arr['manage_name'] =  MooGetGPC('manage_name','string','P');
		$arr['manage_desc'] =  MooGetGPC('manage_desc','string','P');
		$arr['leader_uid'] = MooGetGPC('team_leader','string','P');		
		updatetable('admin_manage',$arr,array('id'=>$id));
		salert('修改成功');exit;
	}
	$id = MooGetGPC('id','integer','G');
	$sql = "SELECT * FROM {$GLOBALS['dbTablePre']}admin_manage WHERE id='$id'";
	$group = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	require_once(adminTemplate('adminteam_editteam'));
}

//删除队成员
function system_adminteam_del_teammember(){
	$groupid = MooGetGPC('groupid','integer','G');
	$id = MooGetGPC('$id','integer','G');
	
	//得到组成员列表
	$group_member = get_manage_member($groupid);
	$group_member_arr = explode(',',$group_member);
	
	$key = array_search($id,$group_member_arr);
	unset($group_member_arr[$key]);
	
	$group_member_list = implode(',',$group_member_arr);
	
	$sql = "UPDATE {$GLOBALS['dbTablePre']}admin_manage SET manage_list='{$group_member_list}' WHERE id='$groupid'";
	$GLOBALS['_MooClass']['MooMySQL']->query($sql);
	
	salert('删除成功','index.php?action=system_adminteam&h=teammember&id='.$groupid);	
}

//设为组长
function system_adminteam_set_leader(){
	$id = MooGetGPC('groupid','integer','G');
	$uid = MooGetGPC('uid','integer','G'); 
	
	$sql = "SELECT username FROM  {$GLOBALS['dbTablePre']}admin_user WHERE uid='{$uid}'";
	$user = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	
	$arr['leader_uid'] = $uid;
	$arr['leader_username'] = $user['username'];
	updatetable('admin_manage',$arr,array('id'=>$id));
	salert('设置成功','index.php?action=system_adminmanage&h=groupmember&id='.$id);	
}

/**
 *
 * 大队红娘奖励设置
 */
function system_adminteam_change(){
	$referer=$_SERVER['HTTP_REFERER'];
	$group=MooGetGPC('group','array','P');
	if(empty($group)){
		MooMessageAdmin('请确认信息的完整性',$referer,1);
	}
	$interval=MooGetGPC('interval','array','P');
	$allot=MooGetGPC('allot','array','P');
	$amount=MooGetGPC('amount','array','P');
	$money=MooGetGPC('money','array','P');
	$sql='SELECT `id`,`leader_uid`,`manage_list` FROM`'.$GLOBALS['dbTablePre'].'admin_manage` WHERE `id` in ('.implode(',',$group).')';
	$manage_list_data=$GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	$data=$updata=$manages=$leader_uid=array();
	foreach ($manage_list_data as $manage){
		if(!empty($manage['manage_list'])){
			$manage_list=array();
			$manage_list=explode(',', $manage['manage_list']);
			foreach($manage_list as $v){
				if(!empty($v)){
					$manages[$v]=$manage['id'];
				}
			}
		}
		if(!empty($manage['leader_uid'])){
			$leader_uid=explode(',', $manage['leader_uid']);
			foreach($manage_list as $v){
				if(!empty($v)){
					$updata[]='(\''.$v.'\',\''.$interval[$manage['id']].'\',\''.$allot[$manage['id']].'\',\''.$amount[$manage['id']].'\',\''.$money[$manage['id']].'\')';
					$manages[$v]=$manage['id'];
				}
			}
				
		}
		$data[]='(\''.$manage['id'].'\',\''.$interval[$manage['id']].'\',\''.$allot[$manage['id']].'\',\''.$amount[$manage['id']].'\',\''.$money[$manage['id']].'\')';
	}
	if(!empty($manages)){
		$sql='SELECT `id`,`manage_list` FROM`'.$GLOBALS['dbTablePre'].'admin_manage` WHERE `id` in ('.implode(',',array_keys($manages)).')';
		$manage_list_data=$GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
		foreach ($manage_list_data as $val){
			$groupid=$manages[$val['id']];
			if(!empty($val['manage_list'])){
				$leader_uid=explode(',', $val['manage_list']);
				foreach($leader_uid as $uid){
					if(!empty($uid)){
						$updata[]='(\''.$uid.'\',\''.$interval[$groupid].'\',\''.$allot[$groupid].'\',\''.$amount[$groupid].'\',\''.$money[$groupid].'\')';
					}
				}
			}
			$data[]='(\''.$val['id'].'\',\''.$interval[$groupid].'\',\''.$allot[$groupid].'\',\''.$amount[$groupid].'\',\''.$money[$groupid].'\')';
		}
	}
	$user_sql=$manage_sql='';
	if(!empty($updata)){
		$user_sql='insert into `'.$GLOBALS['dbTablePre'].'admin_user` (`uid`,`interval`,`allot`,`amount`,`money`) values  '.implode(',', $updata).' on duplicate key update `interval`=values(`interval`),`allot`=values(`allot`),`amount`=values(`amount`),`money`=values(`money`)';
	}
	if(!empty($data)){
		$manage_sql='insert into `'.$GLOBALS['dbTablePre'].'admin_manage` (`id`,`interval`,`allot`,`amount`,`money`) values  '.implode(',', $data).' on duplicate key update `interval`=values(`interval`),`allot`=values(`allot`),`amount`=values(`amount`),`money`=values(`money`)';
	}
	$user_result=empty($user_sql)?TRUE:$GLOBALS['_MooClass']['MooMySQL']->query($user_sql);
	$manage_result=empty($manage_sql)?TRUE:$GLOBALS['_MooClass']['MooMySQL']->query($manage_sql);
	if($user_result && $manage_result){
		MooMessageAdmin('队红娘币限额设置成功',$referer,1);
	}else{
		MooMessageAdmin('队红娘币限额设置失败<br/>USER_SQL=>'.$user_sql.'<br/>MANAGE_SQL=>'.$manage_sql,$referer,1);
	}
}
/***********************************************控制层(C)*****************************************/
$h=MooGetGPC('h','string')=='' ? 'list' : MooGetGPC('h','string');

//note 动作列表
$hlist = array('teamlist','addteam','teammember','addteammember','delteam','editteam','del_teammember','set_leader','change');
//note 判断页面是否存在
if(!in_array($h,$hlist)){
	salert('打开的页面不存在');exit;    
}
//note 判断是否有权限
$nav_list = checkGroup('system_adminteam',$h);
if(!$nav_list){
	salert('您没有此操作权限');exit;
}

include './include/system_function.php';

switch($h){
	//组管理
    case 'teamlist' : 
    	system_adminteam_teamlist();
    break;
    case 'addteam' : 
    	system_adminteam_addteam();
    break;
    case 'teammember':
    	system_adminteam_teammember();
    break;
    case 'addteammember':
    	system_adminteam_addteammember();
    break;
    case 'delteam':
    	system_adminteam_delteam();
    break;
    case 'editteam':
    	system_adminteam_editteam();
    break;
    case 'del_teammember':
    	system_adminteam_del_teammember();
    break;
    case 'set_leader':
		system_adminteam_set_leader();
	break;
    case 'change':
    	system_adminteam_change();
    	break;
}
?>
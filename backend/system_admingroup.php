<?php
/**
 * 权限处理文件
 * @author:fanglin
 * 2010-06-15
 */

/*******************************************逻辑层(M)/表现层(V)*****************************************/
//note 权限列表
function system_admingroup_list(){
	$total=$limit=$page=$currenturl='';
	$sql = "SELECT * FROM {$GLOBALS['dbTablePre']}admin_group ORDER BY groupid ASC";
    $group_list = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);

    require_once(adminTemplate('admingroup_list'));
}

//note 添加权限
function system_admingroup_add(){
    global $menu_nav_arr;
    if(MooGetGPC('ispost','integer','P')){
        $groupname = MooGetGPC('groupname','string','P');
        $groupdesc = MooGetGPC('groupdesc','string','P');
        $action_code = MooGetGPC('action_code','array','P');
        $admin_nav = MooGetGPC('adminnav','array','P');
        //$type = MooGetGPC('type','integer','P');
        if(empty($groupname)||empty($groupdesc)||empty($action_code)){
        	MooMessageAdmin('请将信息填写完整','index.php?action=system_admingroup&h=add',1);
        }
        $action = implode(",", $action_code);
        $admin_nav = implode(",", $admin_nav);
        $sql = "INSERT INTO {$GLOBALS['dbTablePre']}admin_group SET groupname='{$groupname}',type='{$type}', groupdesc='{$groupdesc}',nav='{$admin_nav}',action='{$action}'";
        $result = $GLOBALS['_MooClass']['MooMySQL']->query($sql);
        //note 插入日志
        serverlog(3,$GLOBALS['dbTablePre'].'admin_group',$GLOBALS['username'].'添加权限'.$groupname, $GLOBALS['adminid']);
        if($result){
           salert('添加成功','index.php?action=system_admingroup&h=add');
        }else{
           salert('添加失败','index.php?action=system_admingroup&h=add');	
        }
    }
  
     foreach($menu_nav_arr as $key => $navcode){
     	 $sql = "SELECT * FROM {$GLOBALS['dbTablePre']}admin_action WHERE navcode = '{$key}'";
     	 $var = "action_list_{$key}";
    	 $$var = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
     }
    require_once(adminTemplate('admingroup_add'));
}

//note 修改管理员角色
function system_admingroup_edit(){
    global $menu_nav_arr;
    $groupid = MooGetGPC('groupid','integer');
    if(MooGetGPC('ispost','integer')){
        $groupname = MooGetGPC('groupname','string','P');
        $groupdesc = MooGetGPC('groupdesc','string','P');
        $action_code = MooGetGPC('action_code','string','P');
        $admin_nav = MooGetGPC('adminnav','string','P');
        $login_type = MooGetGPC('login_type','string','P');
        //$type = MooGetGPC('type','integer','P');
        if(empty($groupname)||empty($groupdesc)||empty($action_code)){
        	MooMessageAdmin('请将信息填写完整','index.php?action=system_admingroup&h=add',1);
        }
        $action = implode(",", $action_code);
        $admin_nav = @implode(",", $admin_nav);
        
        $sql = "UPDATE {$GLOBALS['dbTablePre']}admin_group SET groupname='{$groupname}', groupdesc='{$groupdesc}',nav='{$admin_nav}',action='{$action}' WHERE groupid='{$groupid}'";
   
        $result = $GLOBALS['_MooClass']['MooMySQL']->query($sql);
        //note 插入日志
        serverlog(3,$GLOBALS['dbTablePre'] . 'admin_group',$GLOBALS['username']."编辑{$groupname}权限",$GLOBALS['adminid']);
        if($result){
			salert('编辑成功');
        }else{
        	salert('编辑失败');
        }
    }

     foreach($menu_nav_arr as $key => $navcode){
     	 $sql = "SELECT * FROM {$GLOBALS['dbTablePre']}admin_action WHERE navcode = '{$key}'";
     	 $var = "action_list_{$key}";
    	 $$var = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
     }
    $sql = "SELECT * FROM {$GLOBALS['dbTablePre']}admin_group WHERE groupid='{$groupid}'";
    $group = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
    $action = explode(",", $group['action']);
    $nav = explode(",", $group['nav']);
    
    $sql = "SELECT * FROM {$GLOBALS['dbTablePre']}admin_group ORDER BY groupid ASC";
    $group_list = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
    
    require_once(adminTemplate('admingroup_edit'));
}

/**
 * 红娘币基础参数设置-权限组
 */
function system_admingroup_change(){
	$group=MooGetGPC('group','array','P');
	$interval=MooGetGPC('interval','array','P');
	$allot=MooGetGPC('allot','array','P');
	$amount=MooGetGPC('amount','array','P');
	$money=MooGetGPC('money','array','P');
	$data=array();
	$update=array();
	foreach ($group as $value){
		$data[]='(\''.$value.'\',\''.$interval[$value].'\',\''.$allot[$value].'\',\''.$amount[$value].'\',\''.$money[$value].'\')';
		$update[$value]='`interval`=\''.$interval[$value].'\',`allot`=\''.$allot[$value].'\',`amount`=\''.$amount[$value].'\',`money`=\''.$money[$value].'\'';
	}
	if(!empty($data)){
		$group_sql='insert into `'.$GLOBALS['dbTablePre'].'admin_group` (`groupid`,`interval`,`allot`,`amount`,`money`) values  '.implode(',', $data).' on duplicate key update `interval`=values(`interval`),`allot`=values(`allot`),`amount`=values(`amount`),`money`=values(`money`)';
		if($GLOBALS['_MooClass']['MooMySQL']->query($group_sql)){
			foreach($update as $k=>$v){
				$GLOBALS['_MooClass']['MooMySQL']->query('UPDATE `'.$GLOBALS['dbTablePre'].'admin_user` SET '.$v.' WHERE `groupid`='.$k);
			}
			MooMessageAdmin('群组红娘币规则设置成功','index.php?action=system_admingroup&h=list',1);
		}else{
			MooMessageAdmin('群组红娘币规则设置失败<br/>GROUP_SQL=>'.$group_sql,'index.php?action=system_admingroup&h=list',1);
		}
	}else{
		MooMessageAdmin('请确认信息的完整性','index.php?action=system_admingroup&h=list',1);
	}
}


/***********************************************控制层(C)*****************************************/
$h=MooGetGPC('h','string')=='' ? 'list' : MooGetGPC('h','string');

//note 动作列表
$hlist = array('list','add','edit','change');
//note 判断页面是否存在
if(!in_array($h,$hlist)){
    MooMessageAdmin('您要打开的页面不存在','index.php?action=admin&h=index');exit;
}
//note 判断是否有权限
$nav_list = checkGroup('system_admingroup',$h);
if(!$nav_list){
	salert('您没有此操作权限');exit;
	//MooMessageAdmin('您没有此操作的权限','index.php?action=admin&h=index',1);
}

include './include/system_function.php';

switch($h){
    //note 管理员角色列表
    case 'list' : 
    	system_admingroup_list();
    break;
    //note 添加管理员角色
    case 'add' : 
    	system_admingroup_add();
    break;
    //note 修改管理员角色
    case 'edit' : 
    	system_admingroup_edit();
    break;
    case 'change':
    	system_admingroup_change();
    	break;
    default: 
    	system_admingroup_list();
    break;
}
?>
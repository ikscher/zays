<?php
/*******************************************逻辑层(M)/表现层(V)*****************************************/
//note 所有操作列表
function system_adminaction_list(){
	$page_per = 20;
    $page = max(1,MooGetGPC('page','integer','G'));
    $limit = 20;
    $offset = ($page-1)*$limit;
    $where = '';
    $navcode = MooGetGPC('navcode','string','G');
    
    if($navcode){ $where = " WHERE navcode = '{$navcode}'";}
    
    //note 得出总数
    $sql = "SELECT count(1) as count FROM {$GLOBALS['dbTablePre']}admin_action {$where}";
    $query = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
    $total = $query['count'];
    //note 翻页地址
    $currenturl = "index.php?action=system_adminaction&h=list&navcode={$navcode}";
    $sql = "SELECT * FROM {$GLOBALS['dbTablePre']}admin_action {$where} ORDER BY id DESC LIMIT {$offset},{$limit}";
    $action_list = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
    
    $page_links = multipage( $total, $page_per, $page, $currenturl );
    require_once(adminTemplate('adminaction_list'));
}

//note 添加操作
function system_adminaction_add(){
	global $menu_nav_arr;
    if(MooGetGPC('ispost','integer','P')){
        $actiondesc = MooGetGPC('actiondesc','string','P');
        $actioncode = MooGetGPC('actioncode','string','P');
        $navcode = MooGetGPC('navcode','string','P');
        $navname = MooGetGPC('navname','string','P');

        if(empty($actiondesc)||empty($actioncode)||empty($navcode)||empty($navname)){
        	MooMessageAdmin('请将信息填写完整','index.php?action=adminaction&h=add',1);
       	}
        $sql = "INSERT INTO {$GLOBALS['dbTablePre']}admin_action SET navname='{$navname}',navcode='{$navcode}',actioncode='{$actioncode}',actiondesc='{$actiondesc}'";
        $result = $GLOBALS['_MooClass']['MooMySQL']->query($sql);
        if($result){
        	salert('添加成功');
        	//MooMessageAdmin('添加成功','index.php?action=adminaction&h=add',1);
        }else{
        	salert('添加失败');
        	//MooMessageAdmin('添加失败','index.php?action=adminaction&h=add',1);
        }
    }
    require_once(adminTemplate('adminaction_add'));
}
//note 修改操作
function system_adminaction_edit(){
    global $menu_nav_arr,$dbTablePre;
    $actionid = MooGetGPC('actionid','integer');
    $ispost = MooGetGPC('ispost','integer');
    if(empty($actionid)){
    	MooMessageAdmin('参数错误','index.php?action=adminaction&h=list',1);
   	}
    if($ispost){
        $actiondesc = MooGetGPC('actiondesc','string');
        $actioncode = MooGetGPC('actioncode','string');
        $navcode = MooGetGPC('navcode','string');
        $navname = MooGetGPC('navname','string');
        if(empty($actiondesc)||empty($actioncode)||empty($navcode)||empty($navname)){$admin->showMessage('请将信息填写完整','index.php?action=adminaction&h=add',1);}
        $sql = "UPDATE {$GLOBALS['dbTablePre']}admin_action SET navname='{$navname}',navcode='{$navcode}',actioncode='{$actioncode}',actiondesc='{$actiondesc}' WHERE id='{$actionid}'";
        $result = $GLOBALS['_MooClass']['MooMySQL']->query($sql);
        if($result){
        	 //note 插入日志
            serverlog(2,$GLOBALS['dbTablePre'].'admin_action',"{$GLOBALS['username']}修改操作{$actionid}",$GLOBALS['adminid']);
        	salert('修改成功','index.php?action=system_adminaction&h=list');
        	//MooMessageAdmin('修改成功','index.php?action=system_adminaction&h=list',1);
        }else{
        	salert('修改失败','index.php?action=system_adminaction&h=list');
        	//MooMessageAdmin('修改失败','index.php?action=system_adminaction&h=list',1);	
        }
    }
    $sql = "SELECT * FROM {$GLOBALS['dbTablePre']}admin_action WHERE id='{$actionid}'";
    $adminaction = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
    require_once(adminTemplate('adminaction_edit'));
}
/***********************************************控制层(C)*****************************************/
$h=MooGetGPC('h','string')=='' ? 'list' : MooGetGPC('h','string');
//note 动作列表
$hlist = array('list','add','edit');
//note 判断页面是否存在
if(!in_array($h,$hlist)){
    MooMessageAdmin('您要打开的页面不存在','index.php?action=system_adminaction&h=list');
}
//note 判断是否有权限
if(!checkGroup('system_adminaction',$h)){
    MooMessageAdmin('您没有此操作的权限','index.php?action=admin&h=index',1);
}
switch($h){
    //note 所有操作列表
    case 'list' : system_adminaction_list();break;
    
    //note 添加操作
    case 'add' : system_adminaction_add();break;
    
    //note 修改操作
    case 'edit' : system_adminaction_edit();break;
    
    default: system_adminaction_list();break;
}
?>
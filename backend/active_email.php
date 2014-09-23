<?php
//include './include/active_function.php';

//note 鲜花列表
//mark
function active_email_list(){
	//note 分页处理
	$page = max(1,MooGetGPC('page','integer'));
    $limit = 5;
    $offset = ($page-1)*$limit;
 	
	$type = MooGetGPC('type','string');
	//note 显示全部
	if(isset($_GET['type']) && $_GET['type'] == 'all'){
	//note 显示已处理过的
	}else if(isset($_GET['type']) && $_GET['type'] == 'dealed'){
		$condition[] = " a.dealstate = '1'";	
	//note 显示未处理过的	
	}else{
		$condition[] = " a.dealstate = '0'";
	}
	
    //note 所管理的客服id列表
    $myservice_idlist = get_myservice_idlist();
 	 if(empty($myservice_idlist)){
	 	$condition[] = " a.sid IN({$GLOBALS['adminid']})";
	 }elseif($myservice_idlist == 'all'){
	 }else{
	 	 $condition[] = " a.sid IN({$myservice_idlist})";
	 }
	
    //note 处理
    if(isset($_GET['action']) && $_GET['action'] == 'active_email') {
    	$choose = MooGetGPC('choose','string');
    	$keyword = MooGetGPC('keyword','string');
    	if(!empty($choose) && !empty($keyword)) {
    		$condition[] = " $choose like '$keyword"."%'";
    	}
    }
	
	// var_dump($condition);
    
    $sql_where = '';
    if(!empty($condition)){
    	$sql_where = ' and  '.implode(' AND ',$condition);	
    }
	//note 查询语句
    //$sql = "SELECT COUNT(a.id) num FROM {$GLOBALS['dbTablePre']}admin_remark a ,{$GLOBALS['dbTablePre']}members_search m  where a.title like '%会员给给你发消息了，请速查看。%'  and  SUBSTRING_INDEX(a.title,'会',1)=m.uid $sql_where";
	//$total = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	
	$sql = "SELECT COUNT(a.id) num FROM {$GLOBALS['dbTablePre']}admin_remark a   where a.title like '%给您发消息了，请速查看。'  $sql_where";
	$total = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
    
    
	$sql = "SELECT a.id as id,a.sid as sid,a.title as title,a.content as content,a.dateline as dateline ,a.dealstate as dealstate FROM {$GLOBALS['dbTablePre']}admin_remark a  where (a.title like '%给您发消息了，请速查看。')  $sql_where ORDER BY a.`dateline` DESC  LIMIT {$offset},{$limit}";
	
	//$sql = "SELECT a.id as id,a.sid as sid,a.title as title,a.content as content,a.dateline as dateline ,a.dealstate as dealstate,m.uid as uid,m.nickname as nickname FROM {$GLOBALS['dbTablePre']}admin_remark a , {$GLOBALS['dbTablePre']}members_search m where (a.title like '%给你发消息了，请速查看。%') and  m.uid=SUBSTRING_INDEX(a.title,'会',1) $sql_where ORDER BY a.`dateline` DESC  LIMIT {$offset},{$limit}";
	
	$user_arr = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
    

	
	//chuzx 获取客服用户名
	$sids = array();//保存sid
	foreach ($user_arr as $k=>$v)
	{
		if (in_array($v['sid'],$sids)) continue;
		$sids[] = $v['sid'];
	}

	if (!empty($sids))
	{
		$sql   = "SELECT username FROM {$GLOBALS['dbTablePre']}admin_user WHERE uid IN (".implode(',',$sids).")";
		$names = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
		
		/* $sql  = "select uid,username from web_members_search";
		$members=$GLOBALS['_MooClass']['MooMySQL']->getAll($sql); */

		foreach ($user_arr as $k=>$v)
		{
			if (($keys = array_search($v['sid'], $sids)) !== false){
			  $user_arr[$k]['username'] = $names[$keys]['username'];
			}
			
			if(preg_match("/\d{5,9}/",$v['title'],$matches)){
			   $user_arr[$k]['uid']=$matches[0];
			}
		}
	}
	//end chuzx
	

	
	
	
	//note 获得当前的url 去除多余的参数
	/* $currenturl = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"]; 
	$currenturl = preg_replace("/(&page=\d+)/","",$currenturl);
	$currenturl = preg_replace("/(&type=undealed)/","",$currenturl);
	$currenturl = preg_replace("/(&type=dealed)/","",$currenturl);
	$currenturl = preg_replace("/(&type=all)/","",$currenturl);
	$currenturl2 = $currenturl;
	$currenturl = $currenturl."&type=$type";
	echo $currenturl; */

	$currenturl2=$currenturl="index.php?action=active_email&h=list&type={$type}&choose={$choose}&keyword={$keyword}";
	
	$pages = multipage( $total['num'], $limit, $page, $currenturl );
   
   	//note 跳转到某一页
   	$page_num = ceil($total['num']/$limit);
   	
	require(adminTemplate('active_email_list'));

	
	
	
}

/***********************************************控制层(C)*****************************************/
$h=MooGetGPC('h','string','G')=='' ? 'list' : MooGetGPC('h','string','G');

//note 动作列表
$hlist = array('list');
//note 判断页面是否存在
if(!in_array($h,$hlist)){
    MooMessageAdmin('您要打开的页面不存在','index.php?action=site_media');
}

//note 判断是否有权限
if(!checkGroup('active_email',$h)){
    MooMessageAdmin('您没有此操作的权限','index.php?action=site&h=site_media');
}

switch( $h ){
	//note 鲜花列表
	case 'list':
		active_email_list();	
	break;
}
?>
<?php
//note 委托列表
function financial_rewardpunish_list(){
    /*//note 处理搜索会员聊天
    if($_GET['action'] == 'active_chat') {
    	$choose = MooGetGPC('choose','string');
    	$keyword = MooGetGPC('keyword','string');
    	$where = '';
    	if(!empty($choose) && !empty($keyword)) {
    		if($choose == 's_content') {
    			$where = " WHERE $choose like '%$keyword%'";
    		}else{
    			$where = " WHERE $choose = '$keyword'";
    		}
    	}
    }
    
    //note 登陆系统后客服显示自己范围内的， 主管显示全部    
    $return_arr = active_chat_loginlist($where,$keyword);
    $where = $return_arr['where'];
    $member_arr = $return_arr['members'];
	
    //note 分页处理
	$page = max(1,MooGetGPC('page','integer'));
    $limit = 15;
    $offset = ($page-1)*$limit;
    
    //note 查询语句
    if($where != '1') { //note 只查询属于该客服下的用户
	    //note 数据库查询
	    $sql = "SELECT COUNT(*) num FROM {$GLOBALS['dbTablePre']}service_chat $where";
		$total = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	    
		$sql = "SELECT * FROM {$GLOBALS['dbTablePre']}service_chat $where ORDER BY s_id DESC LIMIT {$offset},{$limit}";
		$user_arr = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
    }
    
	//note 搜索分页处理
	if(!empty($choose) && !empty($keyword)) {
		$currenturl = "index.php?action=active_chat&h=list&choose=$choose&keyword=".urlencode($keyword);
	}else {
		$currenturl = "index.php?action=active_chat&h=list";
	}
   	$pages = multipage( $total['num'], $limit, $page, $currenturl );

	   	
   	//note 跳转到某一页
   	$page_num = ceil($total['num']/$limit);
*/
	   	
   	$sql = "SELECT * FROM {$GLOBALS['dbTablePre']}financial_rewardpunish";
	$user_arr = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	foreach($user_arr as $k => $v){
		$sql = "SELECT * FROM {$GLOBALS['dbTablePre']}admin_user WHERE uid='$v[uid]'";
		$user = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
		$user_arr[$k]['name'] = $user['name'];
	}
   	
   	//note 插入日志
	serverlog(1,$GLOBALS['dbTablePre'].'financial_rewardpunish',"{$GLOBALS['username']}查看奖惩订单列表", $GLOBALS['adminid']);
	
	//note 调用模板
	require(adminTemplate('financial_rewardpunish_list'));
}

/***********************************************控制层(C)*****************************************/
$h=MooGetGPC('h','string','G')=='' ? 'list' : MooGetGPC('h','string','G');

//note 动作列表
$hlist = array('list');
//note 判断页面是否存在
if(!in_array($h,$hlist)){
    MooMessageAdmin('您要打开的页面不存在','index.php?n=main');
}

//note 判断是否有权限
if(!checkGroup('financial_rewardpunish',$h)){
    MooMessageAdmin('您没有此操作的权限','index.php?n=main');
}

switch( $h ){
	//note 委托列表
	case 'list':
		financial_rewardpunish_list();	
	break;
}
?>
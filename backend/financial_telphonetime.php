<?php
//include './include/active_function.php';
//note 委托列表
function financial_telphonetime_list(){
    $sid_get = 1;
	$sid = $sid_get + 800;
	
	//note 查询出所有的客服
	$sql = "SELECT * FROM {$GLOBALS['dbTablePre']}admin_user";
	$user_arr = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	
	/*
	$sql = "SELECT * FROM {$GLOBALS['dbTablePre']}admin_user WHERE uid='$v[uid]'";
	
	$sql = "SELECT * FROM {$GLOBALS['dbTablePre']}financial_telphonetime";
	$user_arr = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	foreach($user_arr as $k => $v){
		$sql = "
		SELECT * FROM {$GLOBALS['dbTablePre']}admin_user WHERE uid='$v[uid]'";
		$user = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
		$user_arr[$k]['name'] = $user['name'];
	}
	*/
	/*
	//note 处理导出功能
	if($_GET['export']) {
		ob_clean();
		if(empty($user_arr)){
			echo '没有找到符合条件的财务报表';
			exit;
		}
		
		$today = date("Y-m-d");
		header("Content-type:application/vnd.ms-excel; charset=gbk"); 
		header("Content-Disposition:attachment; filename={$today}.xls");
		
		echo iconv('utf-8','gbk','客服')."\t";
		echo iconv('utf-8','gbk','电话时间')."\t";
		echo iconv('utf-8','gbk','日期')."\t\n";

		foreach($user_arr as $k => $v){
			echo iconv('utf-8','gbk',$v['name'])."\t";
			echo iconv('utf-8','gbk',$v['telphonetime'])."\t";
			echo iconv('utf-8','gbk',date("Y-m-d",$v['dateline']))."\t\n";
		}
		exit;			
	}
	*/
	
   	//note 插入日志
	serverlog(1,$GLOBALS['dbTablePre'].'financial_telphonetime',"{$GLOBALS['username']}查看个人电话时间", $GLOBALS['adminid']);
	
	//note 调用模板
	require(adminTemplate('financial_telphonetime_list'));
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
if(!checkGroup('financial_telphonetime',$h)){
    MooMessageAdmin('您没有此操作的权限','index.php?n=main');
}

switch( $h ){
	//note 显示电话时间列表
	case 'list':
		financial_telphonetime_list();	
	break;
}
?>
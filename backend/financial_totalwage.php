<?php
//note 工资报表
function financial_totalwage_list(){
	//note 搜索小组
    //wxm
	$sql = "SELECT id,manage_name FROM {$GLOBALS['dbTablePre']}admin_manage";
	$group_arr = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	
    //note 处理搜索会员财务报表
    if($_GET['action'] == 'financial_totalwage') {
    	$choose = MooGetGPC('choose','string');
    	$keyword = MooGetGPC('keyword','string');
    	$bgtime = MooGetGPC('bgtime','string');
    	$endtime = MooGetGPC('endtime','string');
    	$groups = MooGetGPC('group','string');
    	$where = ' WHERE 1=1 ';
    	//note 搜索各个客服
    	if(!empty($choose) && !empty($keyword)) {
    		if($choose == 'uid') {
    			$sql = "SELECT uid FROM {$GLOBALS['dbTablePre']}admin_user WHERE name = '{$keyword}'";
				$server = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
				$sid = $server['uid'];
    		}
    		$where .= " AND $choose = '{$sid}'";
    	}
    	//note 按照时间搜索
    	if(!empty($bgtime) &&  !empty($endtime)){
    		$bgtime_arr = explode("-",$bgtime);
    		$bgtime_mk = mktime(0,0,0,$bgtime_arr[1],$bgtime_arr[2],$bgtime_arr[0]);
    		$endtime_arr = explode("-",$endtime);
    		$endtime_mk = mktime(0,0,0,$endtime_arr[1],$endtime_arr[2],$endtime_arr[0]);
    		if($endtime < $bgtime){
    			echo "<script>alert('结束日期不能小于开始日期');window.location.href='index.php?action=financial_orderok&h=list'</script>";
    			exit;
    		}
    		$where .= " AND dateline >= '{$bgtime_mk}' AND dateline <= '{$endtime_mk}'";
    	}
    	//note 按照组搜索
    	if(!empty($groups)){
    		$sql = "SELECT manage_list FROM {$GLOBALS['dbTablePre']}admin_manage WHERE id = '{$groups}'";
			$list = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
			$where .= " AND uid in ({$list['manage_list']})";
    	}
    	//$keyword = '';
    	//$groups = '';
    	
    
    }
    
    //note 分页处理
	$page = max(1,MooGetGPC('page','integer'));
    $limit = 10;
    $offset = ($page-1)*$limit;
    
    //note 数据库查询
    $sql = "SELECT COUNT(*) num FROM {$GLOBALS['dbTablePre']}financial_orderok $where";
	$total = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
    
	$sql = "SELECT * FROM {$GLOBALS['dbTablePre']}financial_orderok $where ORDER BY id DESC LIMIT {$offset},{$limit}";
	$user_arr = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	
//    foreach($user_arr as $k => $v){
//
//		$sql = "SELECT name FROM {$GLOBALS['dbTablePre']}admin_user WHERE uid='$v[uid]'";
//		$user = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
//		$user_arr[$k]['name'] = $user['name'];
//		//var_dump($user_arr);
//	}
     //wxm
	 foreach($user_arr as $k => $v){
        $uid_list[] = $v['uid'];
	 }
	 $uidlist=implode(',', $uid_list);
	 $sql="SELECT name FROM {$GLOBALS['dbTablePre']}admin_user WHERE uid in ($uidlist)";
	 $user = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	 foreach ($user as $k => $v){
        $user_arr[$k]['name'] = $user['name'];
	 }
	 	
	//note 处理导出功能
	//if($_GET['export']) {
	$export=MooGetGPC('export','string','G');
	if($export){
		//note 导出满足查询条件的全部
		$sql = "SELECT * FROM {$GLOBALS['dbTablePre']}financial_orderok $where ORDER BY id DESC";
		$user_arr1 = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
		
	    foreach($user_arr1 as $k => $v){
			$sql = "SELECT name FROM {$GLOBALS['dbTablePre']}admin_user WHERE uid='$v[uid]'";
			$user = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
			$user_arr1[$k]['name'] = $user['name'];
		}
		
		ob_clean();
		if(empty($user_arr1)){
			echo '没有找到符合条件的财务报表';
			exit;
		}
		
		$today = date("Y-m-d");
		header("Content-type:application/vnd.ms-excel; charset=gbk"); 
		header("Content-Disposition:attachment; filename={$today}.xls");
		
		echo iconv('utf-8','gbk','客服')."\t";
		echo iconv('utf-8','gbk','成功订单金额')."\t";
		echo iconv('utf-8','gbk','电话时间金额')."\t";
		echo iconv('utf-8','gbk','月份')."\t";
		echo iconv('utf-8','gbk','提成百分比')."\t";
		echo iconv('utf-8','gbk','薪水提成总计')."\t";
		echo iconv('utf-8','gbk','备注')."\t\n";
		
		foreach($user_arr1 as $k => $v){
			echo iconv('utf-8','gbk',$v['name'])."\t";
			echo iconv('utf-8','gbk','200元')."\t";
			echo iconv('utf-8','gbk','100元')."\t";
			echo iconv('utf-8','gbk','2010年6月份')."\t";
			echo iconv('utf-8','gbk','20%')."\t";
			echo iconv('utf-8','gbk','160元')."\t";
			echo iconv('utf-8','gbk','')."\t\n";
		}
		exit;			
	}
    
	//note 搜索分页处理
	if(!empty($choose) && !empty($keyword)) {
		$currenturl = "index.php?action=financial_totalwage&h=list&choose=$choose&keyword=".urlencode($keyword);
	}else {
		$currenturl = "index.php?action=financial_totalwage&h=list";
	}
	
   	$pages = multipage( $total['num'], $limit, $page, $currenturl );
   	
   	//note 跳转到某一页
   	$page_num = ceil($total['num']/$limit);
	
	
   	//note 插入日志
	serverlog(1,$GLOBALS['dbTablePre'].'financial_totalwage',"{$GLOBALS['username']}查看财务报表记录", $GLOBALS['adminid']);
	
	//note 调用模板
	require(adminTemplate('financial_totalwage_list'));
}

//note 财务备注
function financial_totalwage_remark(){
	$id = MooGetGPC('id','integer','G');
	$type = MooGetGPC('type','string','G');
	$sql = "SELECT remark FROM {$GLOBALS['dbTablePre']}financial_orderok  WHERE id='{$id}'";
	$row = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	if($_POST['ispost']){
		$id = MooGetGPC('id','string','P');
		$remark = MooGetGPC('content','string','P');
		$sql = "UPDATE {$GLOBALS['dbTablePre']}financial_orderok SET remark = '{$remark}' WHERE id='{$id}'";
		$query = $GLOBALS['_MooClass']['MooMySQL']->query($sql);
		if($query){
			//note 插入日志
			if($type=='add'){
				serverlog(4,$GLOBALS['dbTablePre'].'financial_orderok',"{$GLOBALS['username']}写财务报表提成记录ID为:{$id}的备注", $GLOBALS['adminid']);
				MooMessageAdmin('备注添加成功','index.php?action=financial_totalwage&h=list');
			}
			
			if($type == 'update'){
				serverlog(3,$GLOBALS['dbTablePre'].'financial_orderok',"{$GLOBALS['username']}修改财务报表提成记录ID为:{$id}的备注", $GLOBALS['adminid']);
				MooMessageAdmin('备注修改成功','index.php?action=financial_totalwage&h=list');
				
			}
		}
	}
	
	//note 调用模板
	require(adminTemplate('financial_totalwage_add'));
   	
}

/***********************************************控制层(C)*****************************************/
$h=MooGetGPC('h','string','G')=='' ? 'list' : MooGetGPC('h','string','G');

//note 动作列表
$hlist = array('list','remark');
//note 判断页面是否存在
if(!in_array($h,$hlist)){
    MooMessageAdmin('您要打开的页面不存在','index.php?n=main');
}

//note 判断是否有权限
if(!checkGroup('financial_totalwage',$h)){
    MooMessageAdmin('您没有此操作的权限','index.php?n=main');
}

switch( $h ){
	//note 委托列表
	case 'list':
		financial_totalwage_list();	
	break;
	case 'remark':
		financial_totalwage_remark();
	break;		
}
?>
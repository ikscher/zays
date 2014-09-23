<?php
//note 成功订单列表
function financial_orderok_list(){
	//note 小组查询
	$sql = "SELECT id,manage_name FROM {$GLOBALS['dbTablePre']}admin_manage";
	$group_arr = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	
	
    //note 处理搜索会员聊天
    if($_GET['action'] == 'financial_orderok') {
    	$choose = MooGetGPC('choose','string');
    	$keyword = MooGetGPC('keyword','string');
    	$bgtime = MooGetGPC('bgtime','string');
    	$endtime = MooGetGPC('endtime','string');
    	$where = ' WHERE 1=1 ';
    	if(!empty($choose) && !empty($keyword)) {
    		if($choose == 'uid') {
    			$sql = "SELECT uid FROM {$GLOBALS['dbTablePre']}admin_user WHERE name = '{$keyword}'";
				$server = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
				$sid = $server['uid'];
    		}
    		$where .= " AND $choose = '{$sid}'";
    	}
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
    }
	
    //note 分页处理
	$page = max(1,MooGetGPC('page','integer'));
    $limit = 10;
    $offset = ($page-1)*$limit;
    
    //note 数据库查询
    $sql = "SELECT COUNT(*) num FROM {$GLOBALS['dbTablePre']}financial_orderok $where";
	$total = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
    
	$sql = "SELECT id,uid,orderoknum,dateline,paymentid,remark FROM {$GLOBALS['dbTablePre']}financial_orderok $where ORDER BY id DESC LIMIT {$offset},{$limit}";
	$user_arr = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
//    foreach($user_arr as $k => $v){
//    	//note 查询出姓名
//		$sql = "SELECT name FROM {$GLOBALS['dbTablePre']}admin_user WHERE uid='$v[uid]'";
//		$user = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
//		$user_arr[$k]['name'] = $user['name'];
//		print_r($expression);
//		//note 查询出订单金额
//		$sql = "SELECT pay_money FROM {$GLOBALS['dbTablePre']}payment_new WHERE id = '$v[paymentid]'";
//		$order = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
//		$user_arr[$k]['pay_money'] = $order['pay_money'];
//		
//	}
    
	foreach ($user_arr as $k => $v){
//		$va[]=$v[uid];
//		$p[]=$v[paymentid];
		$id[]=$v['id'];
	}
	
//	$vuid=implode(',', $va);
//	$pid=implode(',', $p);
    if(isset($id)){
	$id=implode(',', $id);
	$sql="SELECT * FROM {$GLOBALS['dbTablePre']}financial_orderok o join {$GLOBALS['dbTablePre']}admin_user u on o.uid=u.uid join {$GLOBALS['dbTablePre']}payment_new n on o.paymentid=n.id where o.id in ($id) ORDER BY o.id DESC";
	$order = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
    
    foreach ($order as $k => $v){
    	$user_arr[$k]['name'] = $v['name'];
    	$user_arr[$k]['pay_money'] = $v['pay_money'];	
    }
    }


	
	//note 处理导出功能
	$export=MooGetGPC('export','string','G');
	if($export) {
		//note 查询满足条件的全部导出
		$sql = "SELECT id,uid,orderoknum,dateline,paymentid,remark FROM {$GLOBALS['dbTablePre']}financial_orderok $where ORDER BY id DESC";
		$user_arr1 = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
		var_dump($user_arr1);exit;
//	    foreach($user_arr1 as $k => $v){
//	    	//note 查询出姓名
//			$sql = "SELECT name FROM {$GLOBALS['dbTablePre']}admin_user WHERE uid='$v[uid]'";
//			$user = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
//			$user_arr1[$k]['name'] = $user['name'];
//			//note 查询出订单金额
//			$sql = "SELECT pay_money FROM {$GLOBALS['dbTablePre']}payment_new WHERE id = '$v[paymentid]'";
//			$order = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
//			$user_arr1[$k]['pay_money'] = $order['pay_money'];
//			
//		}
		
		foreach ($user_arr1 as $k => $v){
        
			$id1[]=$v['id'];
		}
		
		$id1=implode(',', $id1);
		$sql="SELECT * FROM {$GLOBALS['dbTablePre']}financial_orderok o join {$GLOBALS['dbTablePre']}admin_user u on o.uid=u.uid join {$GLOBALS['dbTablePre']}payment_new n on o.paymentid=n.id where o.id in ($id1) ORDER BY o.id DESC";
		$order = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	
	    foreach ($order as $k => $v){
	    	$user_arr1[$k]['name'] = $v['name'];
	    	$user_arr1[$k]['pay_money'] = $v['pay_money'];	
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
		echo iconv('utf-8','gbk','成功订单数量')."\t";
		echo iconv('utf-8','gbk','成功订单金额')."\t";
		echo iconv('utf-8','gbk','日期')."\t\n";
		
		foreach($user_arr1 as $k => $v){
			echo iconv('utf-8','gbk',$v['name'])."\t";
			echo iconv('utf-8','gbk',$v['orderoknum'])."\t";
			echo iconv('utf-8','gbk',$v['pay_money']).iconv('utf-8','gbk','元')."\t";
			echo iconv('utf-8','gbk',date("Y-m-d",$v['dateline']))."\t\n";
			
		}
		exit;			
	}
	
    
	//note 搜索分页处理
	if(!empty($choose) && !empty($keyword)) {
		$currenturl = "index.php?action=financial_orderok&h=list&choose=$choose&keyword=".urlencode($keyword);
	}else {
		$currenturl = "index.php?action=financial_orderok&h=list";
	}
	
	
   	$pages = multipage( $total['num'], $limit, $page, $currenturl );
   	
   	//note 跳转到某一页
   	$page_num = ceil($total['num']/$limit);
	
   	//note 插入日志
	serverlog(1,$GLOBALS['dbTablePre'].'financial_orderok',"{$GLOBALS['username']}查看成功订单记录列表", $GLOBALS['adminid']);
	
	//note 调用模板
	require(adminTemplate('financial_orderok_list'));
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
if(!checkGroup('financial_orderok',$h)){
    MooMessageAdmin('您没有此操作的权限','index.php?n=main');
}

switch( $h ){
	//note 成功订单列表
	case 'list':
		financial_orderok_list();

	break;
}
?>
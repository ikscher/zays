<?php
//note 委托列表
function financial_ordertotal_list(){

    //note 处理搜索会员聊天
    if($_GET['action'] == 'financial_ordertotal') {
    	$bgtime = MooGetGPC('bgtime','string');
    	$endtime = MooGetGPC('endtime','string');
    	$where = ' WHERE 1=1 ';
    	if(!empty($bgtime) &&  !empty($endtime)){
    		$bgtime_arr = explode("-",$bgtime);
    		$bgtime_mk = mktime(0,0,0,$bgtime_arr[1],$bgtime_arr[2],$bgtime_arr[0]);
    		$endtime_arr = explode("-",$endtime);
    		$endtime_mk = mktime(0,0,0,$endtime_arr[1],$endtime_arr[2],$endtime_arr[0]);
    		if($endtime < $bgtime){
    			echo "<script>alert('结束日期不能小于开始日期');window.location.href='index.php?action=financial_ordertotal&h=list'</script>";
    			exit;
    		}
    		$where .= " AND dateline >= '{$bgtime_mk}' AND dateline <= '{$endtime_mk}'";
    	}
    }
	
    //note 处理导出功能
    $export=MooGetGPC('export','string','G');
	//if($_GET['export']) {
	if($export){
		ob_clean();
		if(empty($user_arr)){
			echo '没有找到符合条件的财务报表';
			exit;
		}
		
		$today = date("Y-m-d");
		header("Content-type:application/vnd.ms-excel; charset=gbk"); 
		header("Content-Disposition:attachment; filename={$today}.xls");
		
		echo iconv('utf-8','gbk','成功订单总数')."\t";
		echo iconv('utf-8','gbk','日期')."\t\n";
		
		foreach($user_arr as $k => $v){
			echo iconv('utf-8','gbk',$v['name'])."\t";
			echo iconv('utf-8','gbk',date("Y-m-d",$v['dateline']))."\t\n";
		}
		exit;			
	}
    

    //note 数据库查询
    $sql = "SELECT COUNT(*) num FROM {$GLOBALS['dbTablePre']}financial_orderok $where";
	$total = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);

    
	
   	//note 插入日志
	serverlog(1,$GLOBALS['dbTablePre'].'financial_orderok',"{$GLOBALS['username']}查看成功订单总数记录列表", $GLOBALS['adminid']);
	
	//note 调用模板
	require(adminTemplate('financial_ordertotal_list'));
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
if(!checkGroup('financial_orderok',$h)){
    MooMessageAdmin('您没有此操作的权限','index.php?n=main');
}

switch( $h ){
	//note 成功订单列表
	case 'list':
		financial_ordertotal_list();	
	break;
	
}
?>
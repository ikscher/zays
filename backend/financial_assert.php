<?php
//******************************************逻辑层(M)/表现层(V)************************************************//

//显示售后所有组的基本信息
function financial_assert_list(){
	$date = date('m');
	$year = date('Y');
	if($date == 4 || $date == 6 || $date == 9 || $date == 11 ){
		$date_num = 30;
	}elseif($date == 2){
		if($year % 4 == 0){
			$date_num = 29;
		}else{
			$date_num = 28;	
		}
	}else{
		$date_num = 31;	
	}
	//统计几个小时内所做的事情
	$time1 = MooGetGPC('time1','integer','G');
	if(empty($time1)){$time1 = 0;}
//	if($time1 == 0){
//		//超过3天数据
//		$min_time = strtotime(date('Y-m-d'))-259200;
//	}elseif($time1 == 1){
//		//超过5天
//		$min_time = strtotime(date('Y-m-d H:i:s'))-432000;	
//	}elseif($time1 == 2){
//		//超过7天
//		$min_time = strtotime(date('Y-m-d H:i:s'))-604800;	
//	}elseif($time1 == 3){
//		//超过9天
//		$min_time = strtotime(date('Y-m-d H:i:s'))-777600;
//	}elseif($time1 == 4){
//		//超过11天
//		$min_time = strtotime(date('Y-m-d H:i:s'))-950400;
//	}elseif($time1 == 5){
//		//超过13天
//		$min_time = strtotime(date('Y-m-d H:i:s'))-1123200;
//	}elseif($time1 == 6){
//		//超过半个月
//		$min_time = strtotime(date('Y-m-d H:i:s'))-1296000;
//	}elseif($time1 == 7){
//		//超过一个月
//		$min_time = strtotime(date('Y-m-d H:i:s'))-2592000;
//	}
	
	switch ($time1){
		case '0':
			$min_time = strtotime(date('Y-m-d'))-259200;
		break;
		case '1':
			$min_time = strtotime(date('Y-m-d H:i:s'))-432000;
		break;
		case '2':
			$min_time = strtotime(date('Y-m-d H:i:s'))-604800;
		break;
		case '3':
			$min_time = strtotime(date('Y-m-d H:i:s'))-777600;
		break;
		case '4':
			$min_time = strtotime(date('Y-m-d H:i:s'))-950400;
		break;
		case '5':
			$min_time = strtotime(date('Y-m-d H:i:s'))-1123200;
		break;
		case '6':
			$min_time = strtotime(date('Y-m-d H:i:s'))-1296000;
		break;
		case '7':
			$min_time = strtotime(date('Y-m-d H:i:s'))-2592000;
		break;
		default:		
	}
	
	$sql_where  = " and b.dateline < '{$min_time}'";
	
	$url_txt = 'data/group_after.txt';
	if(file_exists($url_txt)){
		$fopen = fopen($url_txt,'r');
		$fread = fread($fopen,30);
		fclose($fopen);
	}
	$group_arr = explode(',',$fread);
	
	//统计小组数目
	foreach($group_arr as $group){
		$g[]=$group;
//		$sql = "select manage_list,manage_name,id from {$GLOBALS[dbTablePre]}admin_manage where id = {$group}";
//		$arr[]= $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	}
	
	$g=implode(',',$g);	
	$sql = "select manage_list,manage_name,id from {$GLOBALS['dbTablePre']}admin_manage where id in($g)";
	$arr=$GLOBALS['_MooClass']['MooMySQL']->getAll($sql);   
	//统计小组底下客服数目
	foreach($arr as $kefu){
		$arr_kefu[] = explode(',',$kefu['manage_list']);
	}
	//统计三天外未完成的数目
	$time = 259200;//总共是三天的时间
	$time2 = strtotime(date('Y-m-d'))-$time;
	$now_time = strtotime(date('Y-m-d H:i:s'));
	//新功能之后,为了保持数据统一,高级或钻石会员升级起始时间:2010-11-05 
	$default_time = strtotime(date('2010-11-05'));
	
	foreach($arr as $sid){		
		if($sid['manage_list']){
			$sql = "select count(*) as count from {$GLOBALS['dbTablePre']}members_search a left join {$GLOBALS['dbTablePre']}member_admininfo b on a.uid=b.uid where b.finished=0 and a.s_cid in (20,30) and a.usertype in (1,2) and a.bgtime < '{$time2}' and a.endtime > '{$time2}' and a.bgtime > '{$default_time}' and a.sid in ({$sid['manage_list']})";
			$three_out[] = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
		}
	}
	
	//统计三天内未完成的数目
	foreach($arr as $sid){
		if($sid['manage_list']){
			$sql = "select count(*) as count from {$GLOBALS['dbTablePre']}members_search a left join {$GLOBALS['dbTablePre']}member_admininfo b on a.uid=b.uid where b.finished=0 and a.s_cid in (20,30) and a.usertype in (1,2) and a.bgtime > '{$time2}' and a.sid in ({$sid['manage_list']})";
			$three_in[] = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
		}
	}
	//统计本月三天后的数目
	$once_month = strtotime(date('Y-m'));
	$over_month = mktime(0,0,0,$date,$date_num,$year);
	if(date('Y') == '2010' && date('m') == '11'){
		$default_sql_where = " and mem.bgtime > '{$default_time}'";
	}else{
		$default_sql_where = " and mem.bgtime > '{$once_month}'";	
	}
	foreach($arr as $sid){
		if($sid['manage_list']){
			$sql = "SELECT count(*) as count from {$GLOBALS['dbTablePre']}members_search mem left join {$GLOBALS['dbTablePre']}member_admininfo adif on mem.uid = adif.uid where ((mem.`bgtime`<'{$time2}' and adif.finished = '0' and mem.bgtime <>0 and mem.endtime > '{$time2}') or (adif.finished = '1' and adif.period>'259200')) and  mem.sid in ({$sid['manage_list']}) $default_sql_where and mem.bgtime< '{$over_month}' and mem.usertype=1";
			$three_through[] = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
		}
	}
	//统计小组总会员数目
	foreach($arr as $sid){
		if($sid['manage_list']){
			$sql = "select count(*) as count from {$GLOBALS['dbTablePre']}members_search where sid in ({$sid['manage_list']}) and  s_cid in (20,30) and usertype in (1,2) and bgtime < '{$min_time}' and endtime > '{$min_time}'";             
			$arr_uid[] = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
		}
	}
	//统计三大步完成了，多少天内客服没有联系的会员
	foreach($arr as $sid){
		if($sid['manage_list']){
			$sql = "select count(*) as count from {$GLOBALS['dbTablePre']}members_search a left join {$GLOBALS['dbTablePre']}member_admininfo b on a.uid=b.uid where a.s_cid in (20,30) and a.usertype in (1,2) and  a.sid in ({$sid['manage_list']}) and b.finished=1  $sql_where";
			$all_uid[] = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
		}
	}
	require_once(adminTemplate('financial_assert_list'));	
}     

//显示当前组下的所有客服月维护情况
function financial_assert_month(){
	$date = date('m');
	$year = date('Y');
	if($date == 4 || $date == 6 || $date == 9 || $date == 11 ){
		$date_num = 30;
	}elseif($date == 2){
		if($year % 4 == 0){
			$date_num = 29;
		}else{
			$date_num = 28;	
		}
	}else{
		$date_num = 31;	
	}
	//统计几个小时内所做的事情
	$time1 = MooGetGPC('time1','integer','G');
	if(empty($time1)){$time1 = 0;}
	
	
//	if($time1 == 0){
//		//3天内数据
//		$min_time = strtotime(date('Y-m-d'))-259200;
//	}elseif($time1 == 1){
//		//5天内
//		$min_time = strtotime(date('Y-m-d H:i:s'))-432000;	
//	}elseif($time1 == 2){
//		//7天时内
//		$min_time = strtotime(date('Y-m-d H:i:s'))-604800;	
//	}elseif($time1 == 3){
//		//9天内
//		$min_time = strtotime(date('Y-m-d H:i:s'))-777600;
//	}elseif($time1 == 4){
//		//11天内
//		$min_time = strtotime(date('Y-m-d H:i:s'))-950400;
//	}elseif($time1 == 5){
//		//13天内
//		$min_time = strtotime(date('Y-m-d H:i:s'))-1123200;
//	}elseif($time1 == 6){
//		//半个月内
//		$min_time = strtotime(date('Y-m-d H:i:s'))-1296000;
//	}elseif($time1 == 7){
//		//一个月内
//		$min_time = strtotime(date('Y-m-d H:i:s'))-2592000;
//	}
	
	switch ($time1){
		case '0':
			$min_time = strtotime(date('Y-m-d'))-259200;
		break;
		case '1':
			$min_time = strtotime(date('Y-m-d H:i:s'))-432000;
		break;
		case '2':
			$min_time = strtotime(date('Y-m-d H:i:s'))-604800;
		break;
		case '3':
			$min_time = strtotime(date('Y-m-d H:i:s'))-777600;
		break;
		case '4':
			$min_time = strtotime(date('Y-m-d H:i:s'))-950400;
		break;
		case '5':
			$min_time = strtotime(date('Y-m-d H:i:s'))-1123200;
		break;
		case '6':
			$min_time = strtotime(date('Y-m-d H:i:s'))-1296000;
		break;
		case '7':
			$min_time = strtotime(date('Y-m-d H:i:s'))-2592000;
		break;
		default:
		
	}
	
	$sql_where  = " and b.dateline < '{$min_time}'";
	
	$groupid = MooGetGPC('groupid','string','G');
	$sql = "select manage_list,manage_name,id from {$GLOBALS['dbTablePre']}admin_manage where id = {$groupid}";
	$arr = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	if(!$arr){
		exit('目前改组还没有成员！');
	}
	$kefu_sid = explode(',',$arr['manage_list']);

	//统计客服名称
	$sql = "select name,uid from {$GLOBALS['dbTablePre']}admin_user where uid in({$arr['manage_list']})";
	$kefu_username = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	//统计客服三天外未完成的数目
	$time = 259200;//总共是三天的时间
	$time2 = strtotime(date('Y-m-d'))-$time;
	//新功能之后,为了保持数据统一,高级或钻石会员升级起始时间:2010-11-05 
	$default_time = strtotime(date('2010-11-05'));
	foreach ($kefu_sid as $k=>$v){
		$k_sid[]=$v;
	}
	$three_out = array();
	$sid = implode(',', $k_sid);
	$sql = "select count(*) as count from {$GLOBALS['dbTablePre']}members_search a left join {$GLOBALS['dbTablePre']}member_admininfo b on a.uid=b.uid where b.finished=0 and a.s_cid in (20,30) and a.usertype in (1,2) and a.bgtime < '{$time2}' and a.bgtime > '{$default_time}' and a.endtime > '{$time2}' and a.sid in ($sid)";
	$three_out[] = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	
//	foreach($kefu_sid as $sid){	
//		$sql = "select count(*) as count from {$GLOBALS['dbTablePre']}members_search a left join {$GLOBALS['dbTablePre']}member_admininfo b on a.uid=b.uid where b.finished=0 and a.s_cid in (20,30) and a.usertype in (1,2) and a.bgtime < '{$time2}' and a.bgtime > '{$default_time}' and a.endtime > '{$time2}' and a.sid='{$sid}'";
//		$three_out[] = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
//	}
	//统计三天内未完成的数目
	$sql = "select count(*) as count from {$GLOBALS['dbTablePre']}members_search a left join {$GLOBALS['dbTablePre']}member_admininfo b on a.uid=b.uid where b.finished=0 and a.s_cid in (20,30) and a.usertype in (1,2) and a.bgtime > '{$time2}' and a.sid in ($sid)";
	$three_in[] = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	
//	foreach($kefu_sid as $sid){
//		$sql = "select count(*) as count from {$GLOBALS['dbTablePre']}members_search a left join {$GLOBALS['dbTablePre']}member_admininfo b on a.uid=b.uid where b.finished=0 and a.s_cid in (20,30) and a.usertype in (1,2) and a.bgtime > '{$time2}' and a.sid='{$sid}'";
//		$three_in[] = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
//	}
	//统计本月三天后不管完成还是未完成的数目
	$once_month = strtotime(date('Y-m'));
	$over_month = mktime(0,0,0,$date,$date_num,$year);
	$bgtimes =  strtotime(date('Y-m-d'))-259200;
	$now_time = strtotime(date('Y-m-d H:i:s'));
	if(date('Y') == '2010' && date('m') == '11'){
		$default_sql_where = " and mem.bgtime > '{$default_time}'";
	}else{
		$default_sql_where = " and mem.bgtime > '{$once_month}'";	
	}
	
	$sql = "SELECT count(*) as count FROM  {$GLOBALS['dbTablePre']}members_search mem left join {$GLOBALS['dbTablePre']}member_admininfo adif on mem.uid = adif.uid where ((mem.bgtime<'{$bgtimes}' and adif.finished = '0' and mem.bgtime <>0 and mem.endtime > '{$bgtimes}') or (adif.finished = '1' and adif.period>'259200')) and mem.sid in ($sid) $default_sql_where and mem.bgtime< '{$over_month}' and mem.usertype=1";
	$three_through[] = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	
//	foreach($kefu_sid as $sid){
//		$sql = "SELECT count(*) as count FROM  {$GLOBALS['dbTablePre']}members_search mem left join {$GLOBALS['dbTablePre']}member_admininfo adif on mem.uid = adif.uid where ((mem.bgtime<'{$bgtimes}' and adif.finished = '0' and mem.bgtime <>0 and mem.endtime > '{$bgtimes}') or (adif.finished = '1' and adif.period>'259200')) and mem.sid='{$sid}' $default_sql_where and mem.bgtime< '{$over_month}' and mem.usertype=1";
//		$three_through[] = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
//	}
	//统计客服总手上的会员数目
	
	$sql = "select count(*) as count from {$GLOBALS['dbTablePre']}members_search where sid in ($sid) and  s_cid in (20,30) and usertype in (1,2) and bgtime < '{$min_time}' and endtime > '{$min_time}'";
	$arr_uid[] = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	
//	foreach($kefu_sid as $sid){
//		$sql = "select count(*) as count from {$GLOBALS['dbTablePre']}members_search where sid='{$sid}' and  s_cid in (20,30) and usertype in (1,2) and bgtime < '{$min_time}' and endtime > '{$min_time}'";
//		$arr_uid[] = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
//	}

	//统计三大步完成了，多少天内客服没有联系的会员
	
	$sql = "select count(*) as count from {$GLOBALS['dbTablePre']}members_search a left join {$GLOBALS['dbTablePre']}member_admininfo b on a.uid=b.uid where a.s_cid in (20,30) and a.usertype in (1,2) and  a.sid in ($sid) and b.finished=1  $sql_where";
	$all_uid[] = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	
//	foreach($kefu_sid as $sid){
//		$sql = "select count(*) as count from {$GLOBALS['dbTablePre']}members_search a left join {$GLOBALS['dbTablePre']}member_admininfo b on a.uid=b.uid where a.s_cid in (20,30) and a.usertype in (1,2) and  a.sid='{$sid}' and b.finished=1  $sql_where";
//		$all_uid[] = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
//	}
	require_once(adminTemplate('financial_assert_month'));
}




//查客服个人月维护情况列表
function financial_assert_own(){
	$sid = MooGetGPC('sid','string','G');
	$username = MooGetGPC('username','string','G');
	$say = MooGetGPC('say','string','G');
	//分页
	$page_per = 20;
    $page = max(1,MooGetGPC('page','integer','G'));
    $limit = 20;
    $offset = ($page-1)*$limit;
	echo $say.'<br>';
	echo $sid.'<br>';
	if($say == 'yes'){
		//$sql = "select count(*) as count from {$GLOBALS['dbTablePre']}members a left join {$GLOBALS['dbTablePre']}member_admininfo  b on a.uid=b.uid where a.sid='{$sid}'";
		//$count = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
		$time = MooGetGPC('time1','string');
		if(!$time){
			$time1 = strtotime(date('Y-m-d'));
			$time2 = strtotime(date('Y-m-d'))+86400;
		}else{
			if($time == 1){
				$time2 = strtotime(date('Y-m-d'));
				$time1=$time2-259200;
			}elseif($time == 2){
				$time2 = strtotime(date('Y-m-d'));
				$time1=$time2-604800;	
			}elseif($time == 3){
				$date = date('m');
				$year = date('Y');
				if($date == 4 || $date == 6 || $date == 9 || $date == 11 ){
					$date_num = 30;
				}elseif($date == 2){
					if($year % 4 == 0){
						$date_num = 29;
					}else{
						$date_num = 28;	
					}
				}else{
					$date_num = 31;	
				}
			$time2 = mktime(0,0,0,$date,$date_num,$year);
			$time1=strtotime(date('Y-m'));
			}
		}	

		$sql = "select distinct(uid) from {$GLOBALS['dbTablePre']}member_sellinfo where mid='{$sid}' and effect_grade != ''  and dateline >= '{$time1}' and dateline <= '{$time2}'";
		echo $sql.'<br>';
		$count = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
		$total = count($count);
		$count_arr = array_slice($count,$offset,20);
		$now_count = count($count_arr);
		$count_arr_uid = '';
		foreach($count_arr as $k=>$val){
			if($k>0){$count_arr_uid.=',';}
			$count_arr_uid.=$val['uid'];
		}
		if($count_arr_uid){$sql_arr_uid = " and uid in ({$count_arr_uid})";}
		//分页内容 
		$sql = "select a.uid,a.username,a.bgtime,a.endtime,b.finished,b.danger_leval,b.allto_time,b.recommend,b.chat,b.email,b.rose,b.mandate,b.leer from {$GLOBALS['dbTablePre']}members_search a left join {$GLOBALS['dbTablePre']}member_admininfo b on a.uid=b.uid where a.sid='{$sid}' '{$sql_arr_uid}' and a.s_cid in (20,30) and a.usertype in (1,2)";
		$collect_val = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
		$currenturl = "index.php?n=financial_assert&h=own&say=yes&time1={$time}&username={$username}&sid='{$sid}'";
		$page_links = multipage($total,$page_per,$page,$currenturl);
	}else{
		$time = MooGetGPC('time1','string');
		if(!$time){
			$time1 = strtotime(date('Y-m-d'));
			$time2 = strtotime(date('Y-m-d'))+86400;
		}else{
			if($time == 1){
				$time2 = strtotime(date('Y-m-d'));
				$time1=$time2-259200;
			}elseif($time == 2){
				$time2 = strtotime(date('Y-m-d'));
				$time1=$time2-604800;	
			}elseif($time == 3){
				$date = date('m');
				$year = date('Y');
				if($date == 4 || $date == 6 || $date == 9 || $date == 11 ){
					$date_num = 30;
				}elseif($date == 2){
					if($year % 4 == 0){
						$date_num = 29;
					}else{
						$date_num = 28;	
					}
				}else{
					$date_num = 31;	
				}
			$time2 = mktime(0,0,0,$date,$date_num,$year);
			$time1=strtotime(date('Y-m'));
			}
		}	

		$sql = "select distinct(uid) from {$GLOBALS['dbTablePre']}member_sellinfo where mid='{$sid}' and effect_grade == ''  and dateline >= '{$time1}' and dateline <= '{$time2}'";
		echo $sql.'<br>';
		$count = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
		$total = count($count);
		$count_arr = array_slice($count,$offset,20);
		$now_count = count($count_arr);
		$count_arr_uid = '';
		foreach($count_arr as $k=>$val){
			if($k>0){$count_arr_uid.=',';}
			$count_arr_uid.=$val['uid'];
		}
		if($count_arr_uid){$sql_arr_uid = " and uid in ({$count_arr_uid})";}
		//分页内容 
		$sql = "select a.uid,a.username,a.bgtime,a.endtime,b.finished,b.danger_leval,b.allto_time,b.recommend,b.chat,b.email,b.rose,b.mandate,b.leer from {$GLOBALS['dbTablePre']}members_search a left join {$GLOBALS['dbTablePre']}member_admininfo b on a.uid=b.uid where a.sid='{$sid}' '{$sql_arr_uid}' and a.s_cid in (20,30) and a.usertype in (1,2)";
		$collect_val = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
		$currenturl = "index.php?n=financial_assert&h=own&say=yes&time1={$time}&username={$username}&sid='{$sid}'";
		$page_links = multipage($total,$page_per,$page,$currenturl);
	}
	
	//统计对每个会员的维护次数
	//foreach($collect_val as $val){
//		for($i=1;$i<=5;$i++){
//			$sql = "select count(*) as count from {$GLOBALS['dbTablePre']}member_sellinfo  where uid='{$val['uid']}' and mid='{$sid}' and effect_grade = '{$i}'";
//			$follow_num[$val['uid']][] = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
//		}
//	}
	require_once(adminTemplate('financial_assert_own'));
}

































































//******************************************控制层(C)************************************************//
$h = MooGetGPC('h','string','G');

switch($h){
	//显示所有组的详细情况
	case 'list':
	
		financial_assert_list();
		break;
	//查看组的月维护情况列表
	case 'month':
		
		financial_assert_month();
		break;
	//查客服个人月维护情况列表
	case  'own':
	
		financial_assert_own();
		break;
}

?>
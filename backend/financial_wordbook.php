<?php
//******************************************逻辑层(M)/表现层(V)************************************************//

function financial_wordbook_list(){
	
	global $adminid,$groupid;
	global $admin_aftersales;
	global $admin_service_arr;//高级管理员groupid
	//下面global值分别是售后组长、售后副组长、售后客服的groupid;
	global  $admin_aftersales_service,$admin_aftersales_service_fu,$admin_aftersales_service2,$admin_service_manager;
	$sid = MooGetGPC('sid','string','P');
	//搜索售后客服
	if($sid){
		$sid_where = (int)$sid ? "a.uid='{$sid}'" : "a.username='{$sid}'";
		$sql = "select a.uid,a.username,a.name,a.lastlogin,a.groupid,b.groupname from {$GLOBALS['dbTablePre']}admin_user a left join {$GLOBALS['dbTablePre']}admin_group b on a.groupid=b.groupid where {$sid_where}";
		$member_backinfo = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
		
		$sql = "select manage_name  from {$GLOBALS['dbTablePre']}admin_manage where find_in_set('{$member_backinfo['uid']}',manage_list)";
		$groupname_uid = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
		require_once(adminTemplate('financial_wordbook_list'));	
		exit;
	}
	

	//判断是主管，还是售后组长等售后客服
	if(in_array($groupid,$GLOBALS['admin_service_after']) || in_array($groupid,$admin_service_arr)){
		
		$url_txt = 'data/group_after.txt';
		if(file_exists($url_txt)){
			$fopen = fopen($url_txt,'r');
			$fread = fread($fopen,30);
			fclose($fopen);
		}
		$group_arr = explode(',',$fread);
		
		//wwwhongniang
		foreach($group_arr as $group){
			
			$g[]=$group;
//			$sql = "select manage_list,manage_name from {$GLOBALS['dbTablePre']}admin_manage where id = {$group}";
//			$arr[]= $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
		}
			
	    $gr=implode(',', $g);	    
	    $sql= "select manage_list,manage_name from {$GLOBALS['dbTablePre']}admin_manage where id in ($gr)";
	    $arr = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
		
		foreach($arr as $arr_uids){
			
			if($arr_uids['manage_list']){
				$sql = "select a.uid,a.username,a.name,a.lastlogin,b.groupname from {$GLOBALS['dbTablePre']}admin_user a left join {$GLOBALS['dbTablePre']}admin_group b on a.groupid=b.groupid where a.uid in ({$arr_uids['manage_list']})";
				$group_num[] = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
			}
		}
		
	}elseif(in_array($groupid,$admin_aftersales_service) || in_array($groupid,$admin_aftersales_service_fu) || in_array($groupid,$admin_service_manager)){
		//echo '123';
		$sql = "select manage_name,manage_list from {$GLOBALS[dbTablePre]}admin_manage where find_in_set({$adminid},manage_list)";
		$group_list = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
		$sql2 = "select a.uid,a.username,a.name,a.lastlogin,b.groupname from {$GLOBALS[dbTablePre]}admin_user a left join {$GLOBALS['dbTablePre']}admin_group b on a.groupid=b.groupid where a.uid in ({$group_list['manage_list']})";
		$group_num = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql2);
	}elseif(in_array($groupid,$admin_aftersales_service2)){
		$sql = "select manage_name  from {$GLOBALS['dbTablePre']}admin_manage where find_in_set({$adminid},manage_list)";
		$groupname_uid = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
		$sql2_where = "a.uid={$adminid}";
		$sql2 = "select a.uid,a.username,a.name,a.lastlogin,b.groupname from {$GLOBALS['dbTablePre']}admin_user a left join {$GLOBALS['dbTablePre']}admin_group b on a.groupid=b.groupid where {$sql2_where}";
		$member_backinfo = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql2);
	}
	require_once(adminTemplate('financial_wordbook_list'));	
}


//售后客服工作情况月报表列表页
function financial_wordbook_month(){
	global $adminid,$groupid;
	//生成月份
	$bgtime = strtotime(date('Y-m'));
	//echo $bgtime.'<br>';
	//echo date('Y-m-d H:i:s',$bgtime).'<br>';
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
	
	//分页
	$page_per = 6;
    $page = max(1,MooGetGPC('page','integer','G'));
    $limit = 6;
    $offset = ($page-1)*$limit;
	$sid = MooGetGPC('sid','integer');
	$username = MooGetGPC('username','string');
	$count_date = 0;
	//取得分页内容  
	
	$sql = "select sid,uid from {$GLOBALS['dbTablePre']}members_search  where sid='{$sid}' and s_cid in (20,30) and usertype in (1,2) and bgtime > '{$bgtime}' limit {$offset},6";
	
	//echo $sql.'<br>';
	$member_backinfo = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	$member_arr = $member_backinfo;
	//去除分页中重复的内容
	$members_arrs = '';
	foreach($member_backinfo as $k=>$val){
		if($k>0)$members_arrs.=',';
		$members_arrs.=$val['uid'];
	}
	//echo $members_arrs.'<br><br>';
	if($members_arrs){
		$sql = "select uid,comment,dateline from {$GLOBALS['dbTablePre']}member_sellinfo where sid='{$sid}' and uid in ({$members_arrs})";
		//echo $sql.'<br><br>';
		$member_arrs = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	}
	//print_r($member_arrs);
	//分页 
	$sql_1 = "select count(*) as count from {$GLOBALS['dbTablePre']}members_search  where  sid='{$sid}' and s_cid in (20,30) and usertype in (1,2) and bgtime > '{$bgtime}'";
	
	$count = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql_1);
	$total = $count['count'];
	$count_page = ceil($total/6);
	//echo 'count_page:'.$count_page;
	require_once(adminTemplate('financial_wordbook_month'));
}

//按月份搜索，某个客服的工作情况
function financial_wordbook_search(){
	global $adminid,$groupid;
	//搜索
	$get_year = MooGetGPC('year','string');
	$get_month = MooGetGPC('month','string');
	//echo $get_year.'年<br>';
	//echo $get_month.'月<br>';
	$count_year = date('Y')-$get_year;
	$count_month = date('m')-$get_month;
	if($count_year){$month = $count_year*12;}
	$count_date = $month+$count_month;
	
	$bgtime = mktime(0,0,0,$get_month,1,$get_year);
	$show_time = date('Y年-m月',$bgtime);
	//生成月份
	if($get_year && $get_month){
		$date = $get_month;
		$year = $get_year;
	}else{
		$date = date('m');
		$year = date('Y');
	}
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
	$max_bgtime = mktime(0,0,0,$get_month,$date_num,$get_year);
	//分页
	$page_per = 6;
    $page = max(1,MooGetGPC('page','integer','G'));
    $limit = 6;
    $offset = ($page-1)*$limit;
	$sid = MooGetGPC('sid','integer');
	$username = MooGetGPC('username','string');
	//取得分页内容
	$sql = "select uid from {$GLOBALS['dbTablePre']}members_search  where sid='{$sid}' and s_cid in (20,30) and usertype in (1,2) and bgtime >= {$bgtime} and bgtime <= {$max_bgtime} limit {$offset},6";
	//echo $sql.'<br>';
	$member_backinfo = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	//去除分页中重复的内容
	$member_arr = $member_backinfo;
	$member_arrs = '';
	foreach($member_backinfo as $k=>$val){
		if($k>0)$member_arrs.=',';
		$member_arrs.=$val['uid'];
	}
	if($member_arrs){
		$sql_where = "and uid in ($member_arrs)";
		$sql = "select uid,comment,dateline from {$GLOBALS['dbTablePre']}member_sellinfo where sid='{$sid}' $sql_where";
		$member_arrs = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	}
	//分页
	$sql_1 = "select count(*) as count from {$GLOBALS['dbTablePre']}members_search where sid='{$sid}' and s_cid in (20,30) and usertype in (1,2) and bgtime >= {$bgtime} and bgtime <= {$max_bgtime}";
	$count = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql_1);
	$total = $count['count'];
	$count_page = ceil($total/6);
	//echo '<br>search_count_page:'.$count_page;
	require_once(adminTemplate('financial_wordbook_search'));	
}


//售后客服日报表
function financial_wordbook_day(){
	global $adminid,$groupid;
	$sid = MooGetGPC('sid','integer');
	$username = MooGetGPC('username','string');
	//分页
	$page_per = 20;
    $page = max(1,MooGetGPC('page','integer','G'));
    $limit = 20;
    $offset = ($page-1)*$limit;
	$time = MooGetGPC('time1','string');
	if(!$time){
		$time1 = strtotime(date('Y-m-d'));
		$time2 = strtotime(date('Y-m-d'))+86400;
	}else{
		$time1 = strtotime($time);
		$time2 = strtotime($time)+86400;
		
	}
	$sql = "select distinct(uid) from {$GLOBALS['dbTablePre']}member_sellinfo where sid='{$sid}' and effect_grade != '' and dateline >= '{$time1}' and dateline <= '{$time2}'";
	$count = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	$currenturl = "index.php?action=financial_wordbook&h=day&sid={$sid}&username={$username}&time1={$time}";
	$total = count($count);
	$page_links = multipage($total,$page_per,$page,$currenturl);
	$count_arr = array_slice($count,$offset,20);
	$count_arr_uid = '';
	foreach($count_arr as $k=>$val){
		if($k>0){$count_arr_uid.=',';}
		$count_arr_uid.=$val['uid'];
	}
	if($count_arr_uid){$sql_arr_uid = " and uid in ({$count_arr_uid})";}
	$member_backinfo = $count_arr;
	$count_day = count($count_arr);
	//操作者mid 所属客服 sid 会员 uid
	$sql = "select mid,uid,sid,effect_grade,comment,dateline from {$GLOBALS['dbTablePre']}member_sellinfo where sid='{$sid}' {$sql_arr_uid} and effect_grade != ''  and dateline >= '{$time1}' and dateline <= '{$time2}'";
	$sellinfo_arr = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	require_once(adminTemplate('financial_wordbook_day'));	
}



//导出生成EXCEL报表
function financial_wordbook_export(){
	$new_month = $get_month = MooGetGPC('month','string','G');
	$get_year = MooGetGPC('year','string','G');
	$sid = MooGetGPC('sid','string','G');
	$username = MooGetGPC('username','string','G');
	//echo $sid.'<br>'.$get_year.'<br>'.$new_month;
	//生成月份
	$date = $get_month;
	$year = $get_year;
	
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
	//note 查询满足条件的全部导出 sid month
	$start_time = mktime(0,0,0,$date,1,$year);
	$end_time = mktime(0,0,0,$date,$date_num,$year);
	$sql = "select uid from {$GLOBALS['dbTablePre']}members_search where sid='{$sid}' and bgtime >= '{$start_time}' and bgtime <= '{$end_time}'";
	//echo $sql.'<br><br>';
	$manage_uid = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	//print_r($manage_uid);
	//echo '<br><br>';
	//重组会员UID
	$join_uid = '';
	foreach($manage_uid as $k=>$val){
		if($k>0)$join_uid.=',';
		$join_uid.=$val['uid'];
	}
	if($join_uid){
		$sql = "select mid,uid,comment,dateline from {$GLOBALS['dbTablePre']}member_sellinfo where sid='{$sid}' and uid in ({$join_uid})";
		//echo $sql.'<br><br>';
		$all_val = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	}
	//print_r($all_val);
	//把选择的日期--至当前日期，相隔的月份打印出来
	$month_num = date('m')-$get_month;
	$year_num = (date('Y')-$get_year)*12;
	$month_allnum = $month_num+$year_num;
	//echo $month_allnum;exit;
	
	ob_clean();
	if(empty($manage_uid)){
		echo '没有找到符合条件的会员报表';
		exit;
	}
	$text_date = iconv('utf-8','gbk',"客服{$username}{$get_year}年{$get_month}月升级的所有会员的维护日志");
	header("Content-type:application/vnd.ms-excel; charset=gbk"); 
	header("Content-Disposition:attachment; filename={$text_date}.xls");
	
	echo iconv('utf-8','gbk','会员UID')."\t";
	//统计会员数量
	$count_uid = count($manage_uid)-1;
	foreach($manage_uid as $k=>$val){
		if($count_uid == $k){
			echo iconv('utf-8','gbk',$val['uid'])."\t\n";
			break;
		}
		echo iconv('utf-8','gbk',$val['uid'])."\t";
	}
	for($i=0;$i<=$month_allnum;$i++){
		if($i==0){
			for($j=1;$j<=$date_num;$j++){
				if($j<=9){
					$for_date = $get_year.'-'.$get_month.'-0'.$j;
				}else{
					$for_date = $get_year.'-'.$get_month.'-'.$j;	
				}
				echo iconv('utf-8','gbk',$get_year).iconv('utf-8','gbk','年').iconv('utf-8','gbk',$get_month).iconv('utf-8','gbk','月').iconv('utf-8','gbk',$j).iconv('utf-8','gbk','日')."\t";
				foreach($manage_uid as $p=>$val){
					if($count_uid == $p){
						$f='';
						$num = 1;
						foreach($all_val as $val_uid){
							$val_date = strtotime(date('Y-m-d',$val_uid['dateline']));
							if($val_uid['uid'] == $val['uid'] && $val_date == strtotime($for_date)){
								$f.='('.$num.')'.$val_uid['comment'];
								$num++;
							}	
						}
						echo iconv('utf-8','gbk',$f)."\t\n";
						break;
					}
					$txt_val='';
					$number =1;
					foreach($all_val as $val_uid){
						$val_date = strtotime(date('Y-m-d',$val_uid['dateline']));
						if($val_uid['uid'] == $val['uid'] && $val_date == strtotime($for_date)){
							$txt_val.='('.$number.')'.$val_uid['comment'];
							$number++;
						}	
					}
					echo iconv('utf-8','gbk',$txt_val)."\t";
				}
			}	
			continue;
		}
		$new_month = $new_month+1;
		
		if($new_month >12){
			//生成月份
			$date = 1;
			$get_year = $get_year+1;
			$new_month = 1;
			if($date == 4 || $date == 6 || $date == 9 || $date == 11 ){
				$date_num = 30;
			}elseif($date == 2){
				if($get_year % 4 == 0){
					$date_num = 29;
				}else{
					$date_num = 28;	
				}
			}else{
				$date_num = 31;	
			}
			for($m=1;$m<=$date_num;$m++){
				if($m<=9){
					$for_dates2 = $get_year.'-'.$new_month.'-0'.$m;
				}else{
					$for_dates2 = $get_year.'-'.$new_month.'-'.$m;	
				}
				echo iconv('utf-8','gbk',$get_year).iconv('utf-8','gbk','年').iconv('utf-8','gbk',$new_month).iconv('utf-8','gbk','月').iconv('utf-8','gbk',$m).iconv('utf-8','gbk','日')."\t";
				foreach($manage_uid as $p=>$val){
					if($count_uid == $p){
						$f1 = '';
						$num1 = 1;
						foreach($all_val as $val_uid){
							$val_date1 = strtotime(date('Y-m-d',$val_uid['dateline']));
							if($val_uid['uid'] == $val['uid'] && $val_date1 == strtotime($for_dates2)){
								$f1.='('.$num1.')'.$val_uid['comment'];
								$num1++;
							}	
						}
						echo iconv('utf-8','gbk',$f1)."\t\n";
						break;
					}
					$f2='';
					$num2=1;
					foreach($all_val as $val_uid){
						$val_date2 = strtotime(date('Y-m-d',$val_uid['dateline']));
						if($val_uid['uid'] == $val['uid'] && $val_date2 == strtotime($for_dates2)){
							$f2.='('.$num2.')'.$val_uid['comment'];
							$num2++;
						}	
					}
					echo iconv('utf-8','gbk',$f2)."\t";
				}
			}
		}else{
			//生成月份
			$date = $new_month;
			$year = $get_year;
			
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
			for($n=1;$n<=$date_num;$n++){
				if($n<=9){
					$for_dates1 = $get_year.'-'.$new_month.'-0'.$n;
				}else{
					$for_dates1 = $get_year.'-'.$new_month.'-'.$n;	
				}
				echo iconv('utf-8','gbk',$get_year).iconv('utf-8','gbk','年').iconv('utf-8','gbk',$new_month).iconv('utf-8','gbk','月').iconv('utf-8','gbk',$n).iconv('utf-8','gbk','日')."\t";
				foreach($manage_uid as $p=>$val){
					if($count_uid == $p){
						$f3='';
						$num3 = 1;
						foreach($all_val as $val_uid){
							$val_date1 = strtotime(date('Y-m-d',$val_uid['dateline']));
							if($val_uid['uid'] == $val['uid'] && $val_date1 == strtotime($for_dates1)){
								$f3.='('.$num3.')'.$val_uid['comment'];
								$num3++;
							}	
						}
						echo iconv('utf-8','gbk',$f3)."\t\n";
						break;
					}
					$f4='';
					$num4=1;
					foreach($all_val as $val_uid){
						$val_date2 = strtotime(date('Y-m-d',$val_uid['dateline']));
						if($val_uid['uid'] == $val['uid'] && $val_date2 == strtotime($for_dates1)){
							$f4.='('.$num4.')'.$val_uid['comment'];
							$num4++;
						}	
					}
					echo iconv('utf-8','gbk',$f4)."\t";
				}
			}
		}
	}
	exit;
}









//获取路径
function getUrl(){
	$currenturl = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"]; 
	$currenturl2 = preg_replace("/(&page=\d+)/","",$currenturl);
	$currenturl = preg_replace("/(&orderlogintime=(desc|asc))/","",$currenturl2);
	$currenturl = preg_replace("/(&orderreal_lastvisit=(desc|asc))/","",$currenturl);
	return $currenturl.'|'.$currenturl2;
}


//******************************************控制层(C)************************************************//
$h = MooGetGPC('h','string','G');

switch($h){
	case 'list' :
		financial_wordbook_list();
		break;
	case 'search':
		//报表搜索
		financial_wordbook_search();
		break;
	case 'month':
		//月报表
		financial_wordbook_month();
		break;
	case  'day':
		//日报表
		financial_wordbook_day();
		break;
		//导出成EXCEL报表
	case 'export':
		financial_wordbook_export();
		break;
}




?>



<?php
/**
 * 我的用户
 * @author:fanglin
 */

include './include/myuser_function.php';

//我的用户搜索
function myuser_search_new_xingxing(){
	$members_condition = $member_admininfo_condition = array();
	$page=$page_per=$total=$sql=$no_search_value_sql_where='';
	$index = '';
	$spx_search = $bases = $login = $member_admin = array();
	$uid = empty($_GET['uid']) ? '' : MooGetGPC('uid','integer','G');
	//sphinx
	isset($_GET['gender'])&&$index = $_GET['gender'] == '' ? '' : ($_GET['gender']== '1' ? 'members_women' : 'members_man');
	!empty($_GET['marriage']) && $spx_search[] = array('marriage',MooGetGPC('marriage','integer','G'));
	!empty($_GET['s_cid']) && $spx_search[] = array('s_cid',MooGetGPC('s_cid','integer','G'));
	!empty($_GET['province']) && $spx_search[] = array('province',MooGetGPC('province','integer','G'));
	if (!empty($_GET['regdate1'])&&!empty($_GET['regdate2'])){
		$regdate1 = strtotime(MooGetGPC('regdate1','string','G'));
		$regdate2 = strtotime(MooGetGPC('regdate2','string','G'));
		$spx_search[] = array('regdate',array($regdate1,$regdate2));
	}else {
		!empty($_GET['regdate1'])&&$spx_search[] = array('regdate',array(strtotime(MooGetGPC('regdate1','string','G')),time()));
		!empty($_GET['regdate2'])&&$spx_search[] = array('regdate',array(0,strtotime(MooGetGPC('regdate2','string','G'))));
	}
	if (!empty($_GET['age1'])&&!empty($_GET['age2'])){
		$age1 = MooGetGPC('age1','integer','G');
		$tmp_start = gmdate('Y', time()) - $age1;
		$age2 = MooGetGPC('age2','integer','G');
		$tmp_end = gmdate('Y',time())-$age2;
		$spx_search[] = array('birthyear',array($tmp_end,$tmp_start));
	}else {
		!empty($_GET['age1'])&&$spx_search[] = array('birthyear',array(0,gmdate('Y', time()) - MooGetGPC('age1','integer','G')));
		!empty($_GET['age2'])&&$spx_search[] = array('birthyear',array(gmdate('Y', time()) - MooGetGPC('age2','integer','G'),gmdate('Y', time())));
	}
	!empty($_GET['city']) && $spx_search[] = array('city',MooGetGPC('city','integer','G'));
	!empty($_GET['salary']) && $spx_search[] = array('salary',MooGetGPC('salary','integer','G'));
	!empty($_GET['sid']) && $spx_search[] = array('sid',MooGetGPC('sid','integer','G'));
	!empty($_GET['telphone']) && $spx_search[] = array('telphone',MooGetGPC('telphone','integer','G'));
		//所管理的客服id列表
	$myservice_idlist = get_myservice_idlist();
	if(!empty($myservice_idlist) && $myservice_idlist!='all' ){
		$myservice_idlist = str_replace(',','|',$myservice_idlist);
		$spx_search[] = array('sid',$myservice_idlist);
	}else{
		$spx_search[] = array('sid',$GLOBALS['adminid']);
	}
	!empty($_GET['username']) && $spx_search[] = array('username',trim(MooGetGPC('username','string','G')));
	!empty($_GET['nickname']) && $spx_search[] = array('nickname',trim(MooGetGPC('nickname','string','G')));
	!empty($_GET['truename']) && $spx_search[] = array('truename',trim(MooGetGPC('truename','string','G')));
	
	
	//members_base表
	!empty($_GET['allotdate1'])&&$bases['allotdate1'] = " b.allotdate>=".strtotime(MooGetGPC('allotdate1','string','G'));
	!empty($_GET['allotdate2'])&&$bases['allotdate2'] = " b.allotdate<=".strtotime(MooGetGPC('allotdate1','string','G'));
	
	//members_login表
	if(!empty($_GET['last_login_time1'])){
		$last_login_time1 = strtotime(MooGetGPC('last_login_time1','string','G'));
		$login['lt'] = " l.last_login_time>='{$last_login_time1}'";
	}
	if(!empty($_GET['last_login_time2'])){
		$last_login_time2 = strtotime(MooGetGPC('last_login_time2','string','G'));
		$login['lt'] = " l.last_login_time<='{$last_login_time2}'";
	}
		
	//admininfo表
	!empty($_GET['grade'])&&$member_admin['grade'] = "ma.effect_grade='". MooGetGPC('grade','integer','G')."'";
	if(!empty($_GET['next_contact_time1'])){
		$next_contact_time1 = strtotime(MooGetGPC('next_contact_time1','string','G'));
		$member_admin['nxt1'] = "ma.next_contact_time>='{$next_contact_time1}'";
	}
	if(!empty($_GET['next_contact_time2'])){
		$next_contact_time2 = strtotime(MooGetGPC('next_contact_time2','string','G'));
		$member_admin['nxt2'] = "ma.next_contact_time<='{$next_contact_time2}'";
	}
	if(isset($_GET['master_member'])){
		$master_member = MooGetGPC('master_member','integer','G');
		$member_admin['mst'] = " ma.master_member='{$master_member}'";
	}
	//当前在线，即最后访问时间小于600秒的
	if(!empty($_GET['online'])){
		$time = time()-600;
		$member_admin['onl'] = " ma.real_lastvisit>{$time}";	
	}
	//----------------以上为条件
	//判断要查几个表
	$i = 0;
	$arr = array('spx_search','bases','login','member_admin');
	foreach ($arr as $v){
		if (count($$v)>0)$i++;
	}
	//只选择了性别一个条件
	if ($index&&count($spx_search)==0)$i++;
	$ids = '';
	//条件只在一个表中
	if ($uid){
		$ids = $uid;
	}elseif ($i==1){
		//分页
		$page_per = 20;
	    $page = get_page();
	    $limit = 20;
	    $offset = ($page-1)*$limit;
		if ($index||count($spx_search)>0){//只走sphinx
			$index = $index=='' ? 'members_man members_women' : $index;
			$sp  = searchApi($index);
			$res = $sp->getResult($spx_search);//,array($offset,$limit));
			$total = $res['total_found'];
			$ids_arr = $sp->getIds();
			$ids = $ids_arr ? implode(',',$ids_arr) : ''; 
		}elseif (count($bases)>0){//只查base表
			$total_sql = "select count(*) as c from {$GLOBALS['dbTablePre']}members_base as b where ".implode(' and ',$bases);
			$temp      = $GLOBALS['_MooClass']['MooMySQL']->getOne($total_sql,true);
			$total     = $temp['c'];
			$ids_arr   = $GLOBALS['_MooClass']['MooMySQL']->getAll("select uid from {$GLOBALS['dbTablePre']}members_base as b where ".implode(' and ',$bases)." limit $offset,$limit",0,0,0,true);
			if ($ids_arr){
				foreach ($ids_arr as $v){
					$ids .= $v['uid'].',';
				}
				//exit('ddd');
				$ids = rtrim($ids,',');
			}
		}elseif (count($login)>0){//只查login表
			$total_sql = "select count(*) as c from {$GLOBALS['dbTablePre']}members_login as l where ".implode(' and ',$login);
			$temp      = $GLOBALS['_MooClass']['MooMySQL']->getOne($total_sql,true);
			$total     = $temp['c'];
			$ids_arr   = $GLOBALS['_MooClass']['MooMySQL']->getAll("select uid from {$GLOBALS['dbTablePre']}members_login as l where ".implode(' and ',$login)." limit $offset,$limit",0,0,0,true);
			if ($ids_arr){
				foreach ($ids_arr as $v){
					$ids .= $v['uid'].',';
				}
				$ids = rtrim($ids,',');
			}
		}elseif (count($member_admin)>0){
			$total_sql = "select count(*) as c from {$GLOBALS['dbTablePre']}member_admininfo as ma where ".implode(' and ',$member_admin);
			$temp      = $GLOBALS['_MooClass']['MooMySQL']->getOne($total_sql,true);
			$total     = $temp['c'];
			$ids_arr   = $GLOBALS['_MooClass']['MooMySQL']->getAll("select uid from {$GLOBALS['dbTablePre']}member_admininfo as ma where ".implode(' and ',$member_admin)." limit $offset,$limit",0,0,0,true);
			if ($ids_arr){
				foreach ($ids_arr as $v){
					$ids .= $v['uid'].',';
				}
				$ids = rtrim($ids,',');
			}
		}
	}else {
		myuser_search_old();
		return;
	}
	
	if(!empty($ids)){
		$sql = "SELECT m.uid,m.salary,m.username,m.telphone,m.gender,m.birthyear,m.s_cid,m.province,m.city,b.allotdate,m.regdate,m.sid,l.last_login_time,l.login_meb,m.nickname,b.is_awoke,ma.next_contact_time,ma.master_member,ma.real_lastvisit
			    FROM {$GLOBALS['dbTablePre']}members_search m
			    left join {$GLOBALS['dbTablePre']}members_base b on m.uid= b.uid
				LEFT JOIN {$GLOBALS['dbTablePre']}member_admininfo ma ON b.uid=ma.uid 
				left join {$GLOBALS['dbTablePre']}members_login l on ma.uid= l.uid
				where m.uid in ({$ids}) ORDER BY l.lastvisit DESC";
		$members =$GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
		$tel=get_telphone($members);
	}
	if(!empty($members)){
		foreach($members as $user){
			$user['num']=$tel[$user['telphone']];
			$member_list[]=$user;
		}
		//当点击同号手机数时排序
		if(!empty($_GET['order_tel'])){
			foreach ($member_list as $key => $row) {
				$edition[$key] = $row[num];
			}
			array_multisort($edition, SORT_DESC, $member_list);//二维数组排序
		}
	}
	
	echo "<span style='display:none;'>$sql</span>";

	//note 获得当前的url 去除多余的参数page=
	$myurl = explode('|',getUrl());
	$currenturl2 = $myurl[0];

	$page_links = multipage( $total, $page_per, $page, $myurl[1] );
	require_once(adminTemplate('myuser_search'));
}

//我的用户搜索
function myuser_search(){
	$members_condition=$members_base_condition=$members_login_condition=$member_admininfo_condition= array();
	$page=$page_per=$total=$sql=$no_search_value_sql_where='';
	
	$page_per = 20;
    $page = get_page();
    $limit = 20;
	    
	if(!empty($_GET['uid'])){
		$uid = MooGetGPC('uid','integer','G');
		$members_condition[] =  " m.uid='{$uid}'";
	}
	
	if(!empty($_GET['grade'])){
		$effect_grade = MooGetGPC('grade','integer','G');
		$member_admininfo_condition[] = " ma.effect_grade='{$effect_grade}'";
	}
	
	if(!empty($_GET['marriage'])){
		$marriage = MooGetGPC('marriage','integer','G');
		$members_condition[] = " m.marriage='{$marriage}'";
	}
	//add s_cid
   if(!empty($_GET['s_cid'])){
		$s_cid = MooGetGPC('s_cid','integer','G');
		$members_condition[] = " m.s_cid='{$s_cid}'";
	}

	if(!empty($_GET['gender'])){
		$gender = MooGetGPC('gender','integer','G');
		$members_condition[] = " m.gender='{$gender}'";
	}
	if(!empty($_GET['regdate1'])){
		$regdate1 = strtotime(MooGetGPC('regdate1','string','G'));
		$members_condition[] = " m.regdate>='{$regdate1}'";
	}
	if(!empty($_GET['regdate2'])){
		$regdate2 = strtotime(MooGetGPC('regdate2','string','G'));
		$members_condition[] = " m.regdate<='{$regdate2}'";
	}
	
	if(!empty($_GET['next_contact_time1'])){
		$next_contact_time1 = strtotime(MooGetGPC('next_contact_time1','string','G'));
		$member_admininfo_condition[] = " ma.next_contact_time>='{$next_contact_time1}'";
	}
	if(!empty($_GET['next_contact_time2'])){
		$next_contact_time2 = strtotime(MooGetGPC('next_contact_time2','string','G'));
		$member_admininfo_condition[] = " ma.next_contact_time<='{$next_contact_time2}'";
	}
	if(!empty($_GET['age1'])){
		$age1 = MooGetGPC('age1','integer','G');
		$tmp_start = gmdate('Y', time()) - $age1;
		$members_condition[] = " m.birthyear<='{$tmp_start}'";
	}
	if(!empty($_GET['age2'])){
		$age2 = MooGetGPC('age2','integer','G');
		$tmp_end = gmdate('Y',time())-$age2;
		$members_condition[] = " m.birthyear>='{$tmp_end}'";
	}
	
	if(!empty($_GET['last_login_time1'])){
		$last_login_time1 = strtotime(MooGetGPC('last_login_time1','string','G'));
		$members_login_condition[] = " l.last_login_time>='{$last_login_time1}'";
	}
	if(!empty($_GET['last_login_time2'])){
		$last_login_time2 = strtotime(MooGetGPC('last_login_time2','string','G'));
		$members_login_condition[] = " l.last_login_time<='{$last_login_time2}'";
	}

	if(isset($_GET['province']) && $_GET['province'] != '0'){
		$province = MooGetGPC('province','integer','G');
		$members_condition[] = " m.province='{$province}'";
	}
	
	if(isset($_GET['city']) && $_GET['city'] != '0'){
		$city = MooGetGPC('city','integer','G');
		$members_condition[] = " m.city='{$city}'";
	}
	
	if(isset($_GET['salary']) && $_GET['salary'] != '0'){
		$salary = MooGetGPC('salary','integer','G');
		$members_condition[] = " m.salary='{$salary}'";
	}
	
	if(isset($_GET['sid'])){
		$sid = MooGetGPC('sid','integer','G');
		$members_condition[] = " m.sid='{$sid}'";
	}
	if(isset($_GET['master_member'])){
		$master_member = MooGetGPC('master_member','integer','G');
		$member_admininfo_condition[] = " ma.master_member='{$master_member}'";
	}
	
	if(!empty($_GET['telphone'])){
		$telphone = trim(MooGetGPC('telphone','string','G'));
		$members_condition[] =  " m.telphone='{$telphone}'";
	}
	//所管理的客服id列表
	$myservice_idlist = get_myservice_idlist();
	if(!empty($myservice_idlist) && $myservice_idlist!='all' ){
		$members_condition[] =  " sid IN($myservice_idlist)";
	}else{
		$members_condition[] = (' sid='.$GLOBALS['adminid']);
	}
	
	//当前在线，即最后访问时间小于600秒的
	if(!empty($_GET['online'])){
		$time = time()-600;
		$member_admininfo_condition[] = " ma.real_lastvisit>{$time}";	
	}
	
	if(!empty($_GET['username'])){
		$username = trim(MooGetGPC('username','string','G'));
		$members_condition[] = " m.username LIKE '%$username%'";
	}
	
	if(!empty($_GET['nickname'])){
		$nickname = trim(MooGetGPC('nickname','string','G'));
		$members_condition[] = " m.nickname LIKE '%$nickname%'";
	}
	if(!empty($_GET['truename'])){
		$truename = trim(MooGetGPC('truename','string','G'));
		$members_condition[] = " m.truename LIKE '%$truename%'";
	}
	
	//members_field表
	$members_field_sql = $members_field_left_join = '';
	
	if(!empty($_GET['allotdate1'])){
		$allodate1 = strtotime(MooGetGPC('allotdate1','string','G'));
		$members_base_condition[] = " b.allotdate>='{$allodate1}'";
	}
	if(!empty($_GET['allotdate2'])){
		$allodate2 = strtotime(MooGetGPC('allotdate2','string','G'));
		$members_base_condition[] = " b.allotdate<='{$allodate2}'";
	}
	
	$every_arr = array_merge($members_condition,$members_base_condition,$members_login_condition,$member_admininfo_condition);
	$sql_where = implode(' and ',$every_arr);
	$arr = array('members_condition','members_login_condition','members_base_condition','member_admininfo_condition');
	$i   = 0;
	foreach ($arr as $v){
		if (count($$v)>0) $i++;
	}
	$ids = '';
	if ($i == 2){
		//分页
		$page_per = 20;
	    $page = get_page();
	    $limit = 20;
	    $offset = ($page-1)*$limit;
	    
		$join_table_arr = array('members_condition'=>"{$GLOBALS['dbTablePre']}members_search",'members_base_condition'=>"{$GLOBALS['dbTablePre']}members_base",'members_login_condition'=>"{$GLOBALS['dbTablePre']}members_login",'member_admininfo_condition'=>"{$GLOBALS['dbTablePre']}member_admininfo");
		$alias = array('members_condition'=>"m",'members_base_condition'=>"b",'members_login_condition'=>"l",'member_admininfo_condition'=>"ma");
		$jj = 0;
		foreach ($join_table_arr as $k=>$j){
			
			if (count($$k)>0){
				$jj++;
				$table  = 'table'.$jj;
				$a      = 'a'.$jj;
				$$table = $j;
				$$a     = $alias[$k];
			}
		}

		$sql_count      = "select count(*) as count from $table1 as $a1 left join $table2 as $a2 on $a1.uid=$a2.uid where 1 and $sql_where ";
		$count = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql_count,true);
		$total = $count['count'];
		$sql   = "select $a1".".uid from $table1 as $a1 left join $table2 as $a2 on $a1.uid=$a2.uid  where 1 and $sql_where order by ma.real_lastvisit desc limit $offset,$limit";
		$ids_arr = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
		if ($ids_arr){
			foreach ($ids_arr as $v){
				$ids .= $v['uid'].',';
			}
			$ids = rtrim($ids,',');
		}
		
	}
//	$members_sql = $member_admininfo_sql = $sql_where = '';
	if(empty($ids)&&$sql_where){
		//分页
	    $offset = ($page-1)*$limit;
	    
		$sql = "SELECT COUNT(*) AS count
				    FROM {$GLOBALS['dbTablePre']}members_search m
					LEFT JOIN {$GLOBALS['dbTablePre']}member_admininfo ma ON m.uid=ma.uid 
					left join {$GLOBALS['dbTablePre']}members_base b on m.uid= b.uid
					left join {$GLOBALS['dbTablePre']}members_login l on l.uid= m.uid
					where {$sql_where}";
		$count = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
		$total = $count['count'];
		
		$sql = "SELECT m.uid,m.salary,m.username,m.telphone,m.gender,m.birthyear,m.s_cid,m.province,m.city,b.allotdate,
				m.regdate,m.sid,l.last_login_time,l.login_meb,m.nickname,b.is_awoke,
				ma.next_contact_time,ma.master_member,ma.real_lastvisit
			    FROM {$GLOBALS['dbTablePre']}members_search m
			    left join {$GLOBALS['dbTablePre']}members_base b on m.uid= b.uid
			    left join {$GLOBALS['dbTablePre']}members_login l on m.uid= l.uid
				LEFT JOIN {$GLOBALS['dbTablePre']}member_admininfo ma ON m.uid=ma.uid 
				where {$sql_where} ORDER BY ma.real_lastvisit DESC LIMIT {$offset},{$limit}";
		$members =$GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
		
		$tel=get_telphone($members);
	}elseif ($ids){
		$sql = "SELECT m.uid,m.salary,m.username,m.telphone,m.gender,m.birthyear,m.s_cid,m.province,m.city,b.allotdate,m.regdate,m.sid,l.last_login_time,l.login_meb,m.nickname,b.is_awoke,ma.next_contact_time,ma.master_member,ma.real_lastvisit
			    FROM {$GLOBALS['dbTablePre']}members_search m
			    left join {$GLOBALS['dbTablePre']}members_base b on m.uid= b.uid
				LEFT JOIN {$GLOBALS['dbTablePre']}member_admininfo ma ON b.uid=ma.uid 
				left join {$GLOBALS['dbTablePre']}members_login l on ma.uid= l.uid
				where m.uid in ({$ids}) ORDER BY l.lastvisit DESC";
		$members =$GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
		$tel=get_telphone($members);
	}
	
	if(!empty($members)){
		foreach($members as $user){
			$user['num']=$tel[$user['telphone']];
			$member_list[]=$user;
		}
		//当点击同号手机数时排序
		if(!empty($_GET['order_tel'])){
			foreach ($member_list as $key => $row) {
				$edition[$key] = $row[num];
			}
			array_multisort($edition, SORT_DESC, $member_list);//二维数组排序
		}
	}
	
	echo "<span style='display:none;'>$sql</span>";

	//note 获得当前的url 去除多余的参数page=
	$myurl = explode('|',getUrl());
	$currenturl2 = $myurl[0];

	$page_links = multipage( $total, $page_per, $page, $myurl[1] );
	require_once(adminTemplate('myuser_search'));
}


function myuser_members_list($h){
	$allow_grade = array(
		'new_member' => 1,
		'continue_communication' => 2,
		'have_need' => 3,
		'accept_service' => 4,
		'accept_price' => 5,
		'confirm_pay' => 6,
		'destroy_order' => 7,
		'reverse_member' => 8,
		'give_up' => 9	
	);
	$grade_name = array(
		'new_member' => '新分用户',
		'continue_communication' => '可继续沟通',
		'have_need' => '有急迫动机需求',
		'accept_service' => '认可网站服务',
		'accept_price' => '认可价格',
		'confirm_pay' => '确认付款',
		'destroy_order' => '毁单',
		'reverse_member' => '倒退会员',		
		'give_up' => '放弃会员'
	);
	$page_per = 20;
    $page = get_page();
    $limit = 20;
    $offset = ($page-1)*$limit;
 
	//获取查询条件及排序
	$grade = $allow_grade[$h];
//	echo $grade;die;
	$where = get_search_condition('');
	$allow_order = array(
		'uid' => 'ID',
		'birthyear' => '年龄',
		'salary' => '收入',
		'allotdate' => '分配时间',
		'ma.real_lastvisit' => '最后登陆时间',
		'login_meb' => '登录次数',
	    'sid'=>'客服',
		'regdate' => '注册时间',
		'last_login_time' => '最后登录时间',
		'next_contact_time' => '下次联系时间',
		'real_lastvisit' => '在线状态'
	);

	$update_arr['birthyear'] = 1;
	$sort_str = "allotdate";
	$query_builder = get_query_builder($where, $allow_order, '', '', $sort_str, 'desc', $update_arr);
	
	$where = $query_builder['where'];// AND  m.sid=0
	
	$sql_sort = $query_builder['sort'];
	$sort_arr = $query_builder['sort_arr'];
	$rsort_arr = $query_builder['rsort_arr'];
    $sql_where = " WHERE ma.effect_grade={$grade}";

	$where = $sql_where.$where;
	$total = get_myuser_count($where);
	$members = get_myuser_list($where, $sql_sort, "LIMIT {$offset},{$limit}");
	$tel=get_telphone($members);
	if(!empty($members)){
		foreach($members as $user){
			$user['num']=$tel[$user['telphone']];
			$member_list[]=$user;
		}
		//当点击同号手机数时排序
		if(isset($_GET['order_tel'])){
			foreach ($member_list as $key => $row) {
				$edition[$key] = $row['num'];
			}
			array_multisort($edition, SORT_DESC, $member_list);//二维数组排序
		}
	}
	
	//毁单理由
	if($h=='destroy_order'){
		$destroy_order = 'yes';
		if(isset($member_list)){
			foreach($member_list as $key => $user){
				$user['info'] = get_destory_order($user['uid']);
				$member_list[$key] = $user;
			}
		}
	}
	
	//来源
	if(isset($member_list)) {
		foreach($member_list as $key => $user) {
			if(preg_match_all("/(wf=\w+)&?|(st=\w+)&?/i", $user['source'], $matches)) {
				$member_list[$key]['source'] = $matches[1][0]."<br />".$matches[2][1];
			}
		}
	}
//	echo "<pre>";
//	print_r($member_list);die;
	//当前在线会员
	$online_member_total = get_online_member_total($sql_where);
	//新分会员里显示当天分配数量
	if($h=='new_member'){
		$allodate = strtotime(date("Y-m-d 00:00:00"));
		$sql_new = "SELECT allot_member,allot_time FROM {$GLOBALS['dbTablePre']}admin_user where uid={$GLOBALS['adminid']}";
		$admin_user_inf= $GLOBALS['_MooClass']['MooMySQL']->getOne($sql_new,true);
		if($admin_user_inf['allot_time']>$allodate){
			$allot_member=$admin_user_inf['allot_member'];
		}else{$allot_member=0;}
	}
	
	//note 获取分页链接	
	$currenturl = "index.php?action=myuser&h=$h";
	$page_links = multipage( $total, $page_per, $page, $currenturl );
	$title =$grade_name[$h];
	include_once(adminTemplate('myuser_members_list'));
}

//删除我的用户
function myuser_delmyuser(){
	global $h;
	$currenturl = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"]; 
   //所管理的客服id列表
   $myservice_idlist = get_myservice_idlist(); 
   
	$uid = MooGetGPC('uid','integer','G');
	$sql = "SELECT sid FROM {$GLOBALS['dbTablePre']}members_search WHERE uid='{$uid}'";
	$sid = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
	if(($sid['sid'] == $GLOBALS['adminid']) || $myservice_idlist=='all' || in_array($sid['sid'],$myservice_idlist)){
		//members表sid清0
		$sql = "UPDATE {$GLOBALS['dbTablePre']}members_search  SET sid='0' WHERE uid='{$uid}' ";
		
		$GLOBALS['_MooClass']['MooMySQL']->query($sql);
		searchApi('members_man members_women')->updateAttr(array('sid'),array($uid=>array(0)));
		if(MOOPHP_ALLOW_FASTDB) {
			$arr = array();
			$arr['sid'] = 0;
			MooFastdbUpdate('members_search','uid',$uid, $arr);
		}
		
		//对应的维护会员总数减1
		$sql = "UPDATE {$GLOBALS['dbTablePre']}admin_user SET member_count=member_count-1 WHERE uid='{$sid['sid']}'";
		$GLOBALS['_MooClass']['MooMySQL']->query($sql);
		
		//后台扩展表标记为删除状态
		$sql = "UPDATE {$GLOBALS['dbTablePre']}member_admininfo SET is_delete=1 WHERE uid='{$uid}'";
		$GLOBALS['_MooClass']['MooMySQL']->query($sql);
		
		//写日志
		serverlog(3,$GLOBALS['dbTablePre'].'members_search',"{$GLOBALS['username']}客服将自己的会员{$uid}删除放弃",$GLOBALS['adminid'],$uid);
		salert('删除成功');
	}else{
		salert('操作不合法');
	}
}


//note 最后操作小记录列表
function myuser_note_list(){
	global $h;
	$page_per = 20;
    $page = get_page();
    $limit = 20;
    $offset = ($page-1)*$limit;
    
	$sid = MooGetGPC('sid','integer','G');
	if(isset($_GET['sid'])){
		$sql_where = " WHERE m.sid=".$sid;
	}else{
		$sql_where = " WHERE 1";
	}
	$where = get_search_condition($sql_where);
	$total = get_myuser_count($where);
    $get_order = isset($_GET['order'])?$_GET['order']:'';
	$order = $get_order=='desc' ? 'asc' : 'desc';
	/*
	$sql = "SELECT m.uid,m.username,m.gender,m.birthyear,m.salary,m.province,b.is_awoke,m.nickname,
			m.city,b.allotdate,m.regdate,m.sid,l.last_login_time,m.s_cid,l.login_meb,b.source,
			ma.next_contact_time,ma.master_member,ma.real_lastvisit,ma.dateline
			FROM {$GLOBALS['dbTablePre']}members_search m
			left join {$GLOBALS['dbTablePre']}members_base as b on m.uid=b.uid
			left join {$GLOBALS['dbTablePre']}members_login as l on m.uid=l.uid
			right JOIN {$GLOBALS['dbTablePre']}member_admininfo ma
			ON m.uid=ma.uid 
			$where
			ORDER BY ma.dateline $order LIMIT {$offset},{$limit}";
	*/
	//add start
    $sql_get_admininfo_ids = "
    		SELECT ma.uid,ma.next_contact_time, ma.master_member, ma.real_lastvisit, ma.dateline,
    		m.uid,m.username,m.gender,m.birthyear,m.salary,m.province,m.nickname,m.city,
    		m.regdate,m.sid,m.s_cid
    		FROM {$GLOBALS['dbTablePre']}member_admininfo ma
			left join {$GLOBALS['dbTablePre']}members_search m 
			on ma.uid=m.uid
            $where 
			order by ma.dateline $order LIMIT {$offset},{$limit} 	
    ";
            
    $ids_res_arr = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql_get_admininfo_ids,0,0,0,true);
    

    $member_list= $page_links=null;//初始
    if($ids_res_arr){
	    $save_goon_id = array();
	    for($i=0;$i<12;$i++){
	    	if(!empty($ids_res_arr[$i])){
	    		if($ids_res_arr[$i]['uid'] != null){
	    			$save_goon_id[]=$ids_res_arr[$i]['uid'];
	    		}
	    	}
	    }
	    $goonids_str = implode(',', $save_goon_id);
	    
	    $sql_get_bls = "
	       SELECT b.is_awoke,b.allotdate,l.last_login_time,l.login_meb,b.source
			FROM {$GLOBALS['dbTablePre']}members_base as b
			left join {$GLOBALS['dbTablePre']}members_login as l on b.uid=l.uid
			where b.uid in (".$goonids_str.")";
	    
	    $bls_res = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql_get_bls,0,0,0,true);
	    //merge
	    $count_goonres = count($ids_res_arr);
	    $merge_all_arr=array();
	    for($i=0;$i<$count_goonres;$i++){
	    	if(!empty($bls_res[$i])){
	    		$merge_all_arr[] = array_merge($bls_res[$i],$ids_res_arr[$i]);
	    	}
	    }
	    $member_list= $merge_all_arr;
    }
	//add end
	
	//$member_list =$GLOBALS['_MooClass']['MooMySQL']->getAll($sql); 

	//当前在线会员
	//$online_member_total = get_online_member_total($sql_where);
	$online_member_total =MooUserIsOnlineNum();
	//note 获得当前的url 去除多余的参数
	$myurl = explode('|',getUrl());
    $currenturl=$myurl[1];
	$currenturl=preg_replace("/(http:\/\/)(www.)([-\w]+\.[a-z]+)(:\d{2,4})*(\/\w+\/)/",'',$currenturl);

	if($member_list){
		$page_links = multipage( $total, $page_per, $page, $currenturl );
	}
	require_once(adminTemplate('myuser_note_list'));
}

function getUrl(){
	$currenturl = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"]; 
	$currenturl2 = preg_replace("/(&page=\d+)/","",$currenturl);
	$currenturl = preg_replace("/(&orderlogintime=(desc|asc))/","",$currenturl2);
	$currenturl = preg_replace("/(&orderreal_lastvisit=(desc|asc))/","",$currenturl);
	return $currenturl.'|'.$currenturl2;
}

function myuser_high_member(){	
	$page_per = 20;
    $page = get_page();
    $limit = 20;
    $offset = ($page-1)*$limit;
 	
	$where = get_search_condition('');
	$allow_order = array(
		'uid' => 'ID',
		'birthyear' => '年龄',
		'salary' => '收入',
		'allotdate' => '分配时间',
		'lastvisit' => '最后登陆时间',
		'login_meb' => '登录次数',
		'regdate' => '注册时间',
		'last_login_time' => '最后登录时间',
		'next_contact_time' => '下次联系时间',
		'real_lastvisit' => '在线状态'
	);

	$update_arr['birthyear'] = 1;
	$query_builder = get_query_builder($where, $allow_order, '', '', 'lastvisit', 'desc', $update_arr);
	
	$where = $query_builder['where'];
	$sql_sort = $query_builder['sort'];
	$sort_arr = $query_builder['sort_arr'];
	$rsort_arr = $query_builder['rsort_arr'];
	
    $sql_where = " WHERE s_cid in (20, 30)";

	$where = $sql_where.$where;
	
	$total = get_myuser_count($where);
	
	$member_list = get_myuser_list($where, $sql_sort, "LIMIT {$offset},{$limit}");
		
	//来源
	if($member_list) {
		foreach($member_list as $key => $user) {
			if(preg_match_all("/(wf=\w+)&?|(st=\w+)&?/i", $user['source'], $matches)) {
				$member_list[$key]['source'] = $matches[1][0]."<br />".$matches[2][1];
			}
		}
	}
//	echo "<pre>";
//	print_r($member_list);die;
	//当前在线会员
	//$online_member_total = get_online_member_total($sql_where);old
	$online_member_total =MooUserIsOnlineNum();
		
	//note 获取分页链接	
	$currenturl = "index.php?action=myuser&h=high_member";

	$page_links = multipage( $total, $page_per, $page, $currenturl );

	$title = "新分客户列表";
	include_once(adminTemplate('myuser_members_list'));
}


//重点跟进会员
function myuser_goonlist(){
    //global $allow_order, $rsort;  
	$where=$link='';
    //允许的排序方式
	$allow_order = array(
	    'uid' => 'ID',
	    'birthyear' => '年龄',
	    'effect_grade'=>'类',
	    'images_ischeck' => '照片',
	    'salary' => '收入',
	    'allotdate' => '分配时间',
	    'regdate' => '注册时间',
	    'next_contact_time' => '下次联系时间',
	    'usertype' => '会员类型',
	    'login_meb' => '登陆次数',
	    'sid' => '工号/客服',
	    'is_lock' => '是否锁定'
	);
	
	//需要修正的排序
	$rsort = array(
	    'birthyear' => 1
	);
  
    $condition = '';

    
    //提交删除
    if(isset($_POST['submit'])){
        $id=$_POST['changesid'];

        foreach($id as $uid){
            $sql="delete from {$GLOBALS['dbTablePre']}member_goon where uid='{$uid}'";
            $GLOBALS['_MooClass']['MooMySQL']->query($sql);
        }
        
    }
    
	$userid=$_GET['usersid'];
    //客服访问权限
    $myservice_idlist = get_myservice_idlist();
    if(empty($myservice_idlist)){
        $condition .= " m.sid IN({$GLOBALS['adminid']}) ";
    }elseif($myservice_idlist == 'all'){
        if(isset($userid)) {$condition .="  g.uid=$userid";}
        
            //all为客服主管能查看所有的
    }else{
        $condition .= "  m.sid IN($myservice_idlist) ";
        if(isset($userid)){$condition .="  g.uid=$userid";}
    }
    
   
    
    //选择条件
   $keyword = trim(MooGetGPC('keyword','string','G'));
  $choose = MooGetGPC('choose','string','G');
    
    if($condition){
	    if(!empty($choose) && !empty($keyword)){
	        $condition = " and m.$choose ='$keyword'";  
	    }
    }else{
        if(!empty($choose) && !empty($keyword)){
            $condition = " m.$choose ='$keyword'";  
        }
    }
    
    if($condition){
      $where=" where $condition";
    }
	

        
    //条件组合
    $query_builder = get_query_builder($where, $allow_order, '', '', '', '', $rsort);
    
    

    $sql_sort = $query_builder['sort'];
    $sort_arr = $query_builder['sort_arr'];
    $rsort_arr = $query_builder['rsort_arr'];
	
	
    
   $sql = "SELECT count(id) AS c FROM {$GLOBALS['dbTablePre']}member_goon g 
            LEFT JOIN {$GLOBALS['dbTablePre']}members_search m
            ON g.uid=m.uid
            $where ";

            
    $members_total = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
     $total = $members_total['c'];
  
    $page_per = 12;
    $limit = 12;
    if(isset($_GET['page_per'])){
        $page_per = intval(MooGetGPC('page_per','integer','G'));
        $limit = $page_per;
        $link = "&page_per=$page_per";
    }
    $page = get_page();
    $page_total = max(1, ceil($total/$limit));
    $page = min($page, $page_total);
    $offset = ($page-1)*$limit;
    
    $sql_get_goonids = "SELECT g.uid,g.effect_grade as effect_grade,g.next_contact_time as next_contact_time 
    		FROM {$GLOBALS['dbTablePre']}member_goon g 
            LEFT JOIN {$GLOBALS['dbTablePre']}members_search m on g.uid=m.uid
            LEFT JOIN {$GLOBALS['dbTablePre']}members_base as b ON g.uid=b.uid
            $where {$sql_sort} LIMIT {$offset},{$limit}";
    /*
    $sql_get_goonids= "SELECT uid,effect_grade,next_contact_time 
    		FROM {$GLOBALS['dbTablePre']}member_goon g  
    		$where {$sql_sort} LIMIT {$offset},{$limit}
    		";
    		*/
	
    $goonid_res_arr = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql_get_goonids,0,0,0,true);
    

    if($goonid_res_arr){
	    $save_goon_id = array();
	    for($i=0;$i<12;$i++){
	    	if($goonid_res_arr[$i]['uid'] != null){
	    		$save_goon_id[]=$goonid_res_arr[$i]['uid'];
	    	}
	    }
	   
	    $goonids_str = implode(',', $save_goon_id);
	    
	    $sql_get_sb = "select * from {$GLOBALS['dbTablePre']}members_search s
	             left join {$GLOBALS['dbTablePre']}members_base b on s.uid=b.uid 
	             where s.uid in (".$goonids_str.")";
	    
	    $sb_res = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql_get_sb,0,0,0,true);
	    //merge
	    $count_goonres = count($goonid_res_arr);
	    $merge_all_arr=array();
	    for($i=0;$i<$count_goonres;$i++){
	    	if(!empty($sb_res[$i])){
	    		$merge_all_arr[] = array_merge($goonid_res_arr[$i],$sb_res[$i]);
	    	}
	    }
	   
	    $member_list= $merge_all_arr;
    }/*else{
    	return true;
    }*/
    //$member_list = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
   $page_links=$currenturl=null;
  $currenturl = "index.php?action=myuser&h=goon_list".$link."&keyword={$keyword}&choose={$choose}";
   if(!empty($member_list)){
    	$page_links = multipage( $total, $page_per, $page, $currenturl );
    	
   }
    //add end

    $title = '重点跟进的会员';
    require_once(adminTemplate('myuser_goonlist'));
}

//小记查询
function myuser_record(){
    global $grade;
    $slave_mysql =  $GLOBALS['_MooClass']['MooMySQL'];
    $condition = array();

	$specialDateArr=array();
	$specialDateStr='';
	$total="";
	$limit=6;
	$page="";
	$notes=array();
	
	
    //选择条件
    $uid = trim(MooGetGPC('uid','string','G'));
	if(!empty($uid)){
	    $condition[]= " uid ={$uid} ";
	}	
 
	
	$endTime=MooGetGPC('endTime','string','G');
	
	
	if(!empty($endTime)){
	   $endtimestamp=strtotime($endTime."+1 day");
	   $starttimestamp=strtotime($endTime);
	   $condition[] = " dateline<'{$endtimestamp}' and dateline>='{$starttimestamp}'"; 
    }
	
	
	 //客服访问权限
    $myservice_idlist = get_myservice_idlist();
    if(empty($myservice_idlist)){
        $condition[]= " mid IN({$GLOBALS['adminid']}) ";
    }elseif($myservice_idlist == 'all'){
        //if(isset($_GET['usersid'])){$condition .="  g.sid='$usersid'";}
        
        //all为客服主管能查看所有的
		
    }else{
        $condition []= "  mid IN($myservice_idlist) ";
      
    }
	
	
    $where=" where 1  ";
    if(!empty($condition)){
		$condition_sql = implode(' and ',$condition);
	    $where .= " and  $condition_sql";
	}else{
	    $where .="";
    }
	
	//当前月份有小记的高亮显示出来
	if(!empty($uid)){
	   if(empty($endTime)) { 
	     $endTime2=date('Y-m-d');
	   }else{
	     $endTime2=$endTime;
	   }

	   $curMonthFirst=strtotime(date('Ym',strtotime($endTime2)).'01');
	   $curMonthLast=strtotime(date('Ym',strtotime($endTime2)).date('t')."+1 day");
	   
	   $where2=" where uid={$uid} and dateline<'{$curMonthLast}' and dateline>='{$curMonthFirst}'";
	}
	
	if(MooGetGPC('isSubmit','string')){
	
		
		
		$total = getcount('member_backinfo',$where);

		$page = intval(MooGetGPC('page','integer','G'));
		if($page<=0) $page=1;
		$page_total = max(1, ceil($total/$limit));
		$page = min($page, $page_total);
		$offset = ($page-1)*$limit;
		
		$sql = "SELECT id,dateline, mid, uid,manager, effect_grade, effect_contact, master_member, next_contact_time, interest, different, service_intro, next_contact_desc, comment FROM {$GLOBALS['dbTablePre']}member_backinfo $where ORDER BY id DESC LIMIT {$offset},{$limit}"; // updated file
		$notes = $slave_mysql->getAll($sql,0,0,0,true);	
		
		
		//当前月份有小记的高亮显示出来
		if(!empty($where2)){
			$sql = "SELECT id,dateline FROM {$GLOBALS['dbTablePre']}member_backinfo $where2 "; // updated file
			$notesDate = $slave_mysql->getAll($sql,0,0,0,true);	
			
			
			foreach($notesDate as $v){
			   $dateline=Date('Y-m-d',$v['dateline']);
			   if(!in_array($dateline,$specialDateArr)){
				  $specialDateArr[]=$dateline;
			   }
			}
			
			$comma='';
			foreach($specialDateArr as $value){
			   $specialDateStr.=$comma."'".$value."'";
			   // if(!empty($value)) $specialDateStr.=",";
			   $comma=",";
			 
			}
		
		}
		 // echo $specialDateStr;
		
	}
	
    $title="查看会员小记";
	if(!empty($uid)){
	   $title="查看会员{$uid}的小记";
	}
	
	
	 $currenturl = "index.php?action=myuser&h=record&uid={$uid}&endTime={$endTime}&isSubmit=1";

	 $page_links = multipage( $total, $limit, $page, $currenturl );
   
    
    require_once(adminTemplate('myuser_record'));
}


//查看组长对客服的评论
function myuser_remark(){
    $sid=$groupid='';
    $condition = array();

    $manage_list=$link='';
    //提交删除
    if(isset($_POST['submit'])?$_POST['submit']:''){
        $id=$_POST['changesid'];

 
        foreach($id as $v){
            $sql="delete from {$GLOBALS['dbTablePre']}member_effectgrade where id='{$v}'";
            $GLOBALS['_MooClass']['MooMySQL']->query($sql);
        }
        
    }
    
    //客服访问权限
    $myservice_idlist = get_myservice_idlist();
    if(empty($myservice_idlist)){
        $condition[]= " sid IN({$GLOBALS['adminid']}) ";
    }elseif($myservice_idlist == 'all'){
        //if(isset($_GET['usersid'])){$condition .="  g.sid='$usersid'";}
        
        //all为客服主管能查看所有的
		$adminUser=$GLOBALS['_MooClass']['MooMySQL']->getAll("select uid,username from web_admin_user",0,0,0,true);
    }else{
        $condition []= "  sid IN($myservice_idlist) ";
       // if(isset($_GET['usersid'])){$condition .="  g.sid='$usersid'";}
	   $adminUser=$GLOBALS['_MooClass']['MooMySQL']->getAll("select uid,username from web_admin_user  where uid in ($myservice_idlist) ",0,0,0,true);
    }
    
    $sid=MooGetGPC('sid','integer','G');
	$groupid=MooGetGPC('groupid','string','G');
        
    if($groupid){
        $group = get_group_type($groupid);
        $manage_list = $group[0]['manage_list'];
        $condition[] = " sid IN($manage_list)";
    }elseif ($groupid && $sid){
	    $condition[] = " sid='{$sid}'";
	}
    $group_list = get_group_type();
	

	if($manage_list){
	   $manage_arr=explode(',',$manage_list);
	   foreach($manage_arr as $value){
	      $adminUser=$GLOBALS['_MooClass']['MooMySQL']->getOne("select uid,username from web_admin_user  where uid=$value",true);
		  $manage_uid=$adminUser['uid'];
		  $manage_username=$adminUser['username'];
		  $manage[]=array("uid"=>$manage_uid,"username"=>$manage_username);
		}
		  
	}
	
    //选择条件
    $sid = trim(MooGetGPC('sid','string','G'));
	if(!empty($sid)){
	    unset($condition);
	    $condition[]= " sid ={$sid} ";
	}	
    // print_r($condition);
	/*$startTime=MooGetGPC('startTime','string','G');
	if(!empty($startTime)){
	   $starttimestamp=strtotime($startTime);
	   $condition[] = " dateline>='{$starttimestamp}'"; 
    }*/
	
	$endTime=MooGetGPC('endTime','string','G');
	
	
	if(!empty($endTime)){
	   $endtimestamp=strtotime($endTime."+1 day");
	   $condition[] = " dateline<'{$endtimestamp}'"; 
    }
	
    $where=" where 1  ";
    if(!empty($condition)){
		$condition_sql = implode(' and ',$condition);
	    $where .= " and  $condition_sql";
	}else{
	    $where .="";
    }
	
    
    $sql = "SELECT count(id) AS c FROM {$GLOBALS['dbTablePre']}member_effectgrade    $where ";
    $remark_total = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
    $total = $remark_total['c'];
    

    $limit=6;
	
    $page = intval(MooGetGPC('page','integer','G'));
	if($page<=0) $page=1;
    $page_total = max(1, ceil($total/$limit));
    $page = min($page, $page_total);
    $offset = ($page-1)*$limit;
    
    $sql = "SELECT id,sid,gid,remark,dateline  FROM {$GLOBALS['dbTablePre']}member_effectgrade  $where  order by dateline desc  LIMIT {$offset},{$limit}";
    
	
    $remark_list = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
    

	if(!empty($sid)){
	   $title="查看客服{$sid}的评语";
	}else{
	   $title = '客服盘库评语';
	}
	
    $currenturl = "index.php?action=myuser&h=remark&sid={$sid}&groupid={$groupid}";
  
    $page_links = multipage( $total, $limit, $page, $currenturl );
   
    if(in_array($GLOBALS['groupid'],$GLOBALS['admin_service_arr'] )){
	   $isAdmin=1; 
	}
    
    require_once(adminTemplate('myuser_remark'));
}

/***********************************************控制层(C)*****************************************/
$h=MooGetGPC('h','string');
//note 动作列表
$hlist = array('search','new_member','continue_communication','have_need','accept_service','remark','record','accept_price','confirm_pay','destroy_order','reverse_member','high_member','give_up','delmyuser','note_list','goon_list');

//note 判断页面是否存在
if(!in_array($h,$hlist)){
	salert('您要打开的页面不存在');
}
//note 判断是否有权限
if(!checkGroup('myuser',$h)){
  salert('您没有修改此操作的权限');
}

$allow_class = array('new_member', 'continue_communication', 'have_need', 'accept_service', 'accept_price', 'confirm_pay', 'destroy_order', 'reverse_member', 'give_up');
if(in_array($h, $allow_class)) {
	myuser_members_list($h);
	return;
}

switch($h){
	case 'search':
		myuser_search();
	    break;
	case 'goon_list':
		myuser_goonlist();
		break;
	case 'delmyuser':
		myuser_delmyuser();
	    break;
	case 'note_list':
		myuser_note_list();
	    break;
	case 'high_member':
		myuser_high_member();
	    break;
	case 'remark':
	    myuser_remark();
	    break;
	case 'record':
	    myuser_record();
	    break;
    default:
    	exit('页面不存在');
        break;
    
}
?>
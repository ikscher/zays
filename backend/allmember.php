<?php
/**
 * 全部会员
 * @author:fanglin
 */
error_reporting(0);
include './include/allmember_function.php';



//普通会员
function allmember_general(){
	global $allow_order, $rsort, $h,$_MooCookie;

	
    //MooSetCookie('sql_where', '',-86400);

	$condition = array();
	
	//获取查询条件及语句
    $sql_where = get_search_condition('');

	

    $query_builder = get_query_builder($sql_where, $allow_order, '', '', 'regdate', 'desc', $rsort);
    
    $condition['regdate']=$condition['uid']=$condition['username']=$condition['nickname']=$condition['telphone']=$condition['sid'] = '';
   
    $total = 0;
    $page_per = 20;
    $limit = 20;
    $page = get_page();
    $member_list = array();
    $where = '';
    $sort_arr = $query_builder['sort_arr'];
	$rsort_arr = $query_builder['rsort_arr'];
	
	$age_arr = array();
    for($i=18;$i<100;$i++){                  
    	$age_arr[] = $i;	
    }
	
//    if(!empty($query_builder['where'])){
    	
    	
    //if(!empty($_MooCookie['sql_where'])){
		//普通会员
		$s_cid = 40;
	    $sql_where = "where m.usertype=1 and m.bgtime=0 and m.s_cid=".$s_cid;
		
		$where = $sql_where.$query_builder['where'];
		$sql_sort = $query_builder['sort'];
	
		 //获取本次查询条件
		$sp_condition = get_condition($where);	

    	//$condition = array_merge($condition, $sp_condition);
		
		
		$offset = ($page-1)*$limit;
		
        
	    if(strpos($where, 'ma.real_lastvisit') === false){
	
			$limits = array($offset, $page_per);
		    $sp_result = sphinx_search($sp_condition, $limits, $sql_sort);
			
		    $total = $sp_result['total'];

		    if($total > 0)
		    	$member_list = $sp_result['member_list'];
		}
	
		if($total == 0 && !isset($sp_result['member_list'])){
			
  			$where = str_replace("AND  m.gender='3'", '', $where);
  			$total = get_allmember_count($where);	
	    	$member_list = get_member_list($where, $sql_sort, "limit $offset, {$page_per}");
  		}
//    }
	//来源
	/*if(!empty($member_list)) {
		foreach($member_list as $key => $user) {
			if(preg_match_all("/(wf=\w+)&?|(st=\w+)&?/i", $user['source'], $matches)) {
				$member_list[$key]['source'] = $matches[1][0]."<br />".$matches[2][1];
			}
		}
	}

	*/
	if(!empty($member_list)) {
		//note 找出这一分页总共的用户id
		foreach($member_list as $key=>$v){
			$arr_uid[] = $v['uid'];	
			if(preg_match_all("/(wf=\w+)&?|(st=\w+)&?/i", $v['source'], $matches)) {
				$member_list[$key]['source'] = $matches[1][0]."<br />".$matches[2][1];
			}
		}
		$member_list_arr = array();
		$member_list_arr = $member_list;
		$member_list = array();
		$member_list_uid = implode(",",$arr_uid);
		//note在用户数组里面增加手机是否验证
		$member_list = get_ifcheck($member_list_uid,$member_list_arr);
	}
	
	$currenturl = "index.php?action=allmember&h={$h}";
	$currenturl1 = "index.php?action=allmember&h={$h}&clear=1";
	$page_links = multipage($total, $page_per, $page, $currenturl);
	
	$kefu_list = get_kefulist();

//	echo "<pre>";
//	print_r($condition);
	$title = '普通会员列表';
	$generalmembers = 'generalmembers';
	require_once(adminTemplate('allmember_general'));
}

//由高级会员降级的普通会员
function allmember_downgeneral(){
	global $renewals_status_grade,$renewalslink, $allow_order, $rsort, $h;
	
	$renewals = MooGetGPC('renewals','integer','G');
	
	$condition = array();
    $condition['uid']=$condition['username']=$condition['nickname']=$condition['telphone']=$condition['sid'] = '';
	
    //普通会员
	$s_cid = 40;
    $sql_where = 'where m.usertype=1 and m.s_cid='.$s_cid;
	if(isset($_GET['renewals'])) {
		$sql_where .= " and ma.renewalstatus = '{$renewals}'";
	}
	//获取查询条件及语句
    $where = get_search_condition('');
    //echo $where;
	$query_builder = get_query_builder($where, $allow_order, '', '', 'regdate', 'desc', $rsort);
	
	$where = $sql_where.$query_builder['where'];
	$sql_sort = $query_builder['sort'];
	$sort_arr = $query_builder['sort_arr'];
	$rsort_arr = $query_builder['rsort_arr'];
	$total = 0;
	$page_per = 20;
    $page = get_page();
	$limit = 20;

    $offset = ($page-1)*$limit;
    $member_list = array();
	//获取本次查询条件
	$sp_condition = get_condition($where);
    if(!isset($_GET['renewals']) && strpos($where, 'ma.renewalstatus') === false  && strpos($where, 'ma.real_lastvisit') === false) {
    	$sp_condition['bgtime'] = array(946656000, 10000000000);
		$limits = array($offset, $page_per);
    	$sp_result = sphinx_search($sp_condition, $limits, $sql_sort);
    	$total = $sp_result['total'];
    	if($total > 0)
    		$member_list = $sp_result['member_list'];
    	
    }
    
	$condition = array_merge($condition, $sp_condition);
	
	if($total == 0 && !isset($sp_result['member_list'])){
		$where = str_replace("AND  m.gender='3'", '', $where);
		$member_list = get_member_list($where.' and m.bgtime>946656000', $sql_sort, "limit {$offset},{$page_per}");
		$total = get_allmember_count($where.' and m.bgtime>946656000');
	}
	
	$page_total = max(1, ceil($total/$limit));
	$page = min($page, $page_total);
	//来源
	if($member_list) {
		foreach($member_list as $key => $user) {
			if(preg_match_all("/(wf=\w+)&?|(st=\w+)&?/i", $user['source'], $matches)) {
				$member_list[$key]['source'] = $matches[1][0]."<br />".$matches[2][1];
			}
		} 
	}
	/*$tel=get_telphone($members);
	if($members){
		foreach($members as $user){
			$user['num']=$tel[$user['telphone']];
			$member_list[]=$user;
		}
		//当点击同号手机数时排序
		if($_GET['order_tel']){
			foreach ($member_list as $key => $row) {
				$edition[$key] = $row['num'];
			}
			array_multisort($edition, SORT_DESC, $member_list);//二维数组排序
		}
	}*/

	$renewalsapper = 'yes';

	$clear = MooGetGPC('clear','integer','G');

	$currenturl1 = "index.php?action=allmember&h=downgeneral&page=1&clear=1";

	$currenturl = $_SERVER["REQUEST_URI"]; 
	$currenturl = preg_replace("/(&page=\d+)/","",$currenturl);

	$page_links = multipage( $total, $page_per, $page, $currenturl );
	
	$kefu_list = get_kefulist();
	
	$age_arr = array();
    for($i=18;$i<100;$i++){
    	$age_arr[] = $i;	
    }

	$title = 'VIP转为的普通会员';
	$generalmembers = 'generalmembers';
	require_once(adminTemplate('allmember_general'));
}

//高级会员
function allmember_high(){
	global $member_progress_grade,$processlink, $allow_order, $rsort, $h;

	$vipprocess = MooGetGPC('process','integer','G');

	$condition = array();
    $condition['uid']=$condition['username']=$condition['nickname']=$condition['telphone']=$condition['sid'] = '';
    
    //高级会员
	$s_cid = 30;
    $sql_where = 'where m.s_cid='.$s_cid;
	
	if(isset($_GET['process'])){
		$sql_where .= " and ma.memberprogress='{$vipprocess}'";
	}

	//获取查询条件及语句
    $where = get_search_condition('');

	$query_builder = get_query_builder($where, $allow_order, '', '', 'regdate', 'desc', $rsort);
	
	$where = $sql_where.$query_builder['where'];

	$sql_sort = $query_builder['sort'];
	$sort_arr = $query_builder['sort_arr'];
	$rsort_arr = $query_builder['rsort_arr'];
	
	$page_per = 20;
    $page = get_page();
    $limit = 20;
    $offset = ($page-1)*$limit;
	$total = 0;
	$member_list = array();
    //获取本次查询条件
	$sp_condition = get_condition($where);
	if(!isset($_GET['process']) && strpos($where, 'ma.memberprogress') === false && strpos($where, 'ma.real_lastvisit') === false) {
		$limits = array($offset, $page_per);
    	$sp_result = sphinx_search($sp_condition, $limits, $sql_sort);
    	$total = $sp_result['total'];
    	if($total > 0)
    		$member_list = $sp_result['member_list'];

    	
    }	
	$condition = array_merge($condition, $sp_condition);
	if($total == 0 && !isset($sp_result['member_list'])){
		$where = str_replace("AND  m.gender='3'", '', $where);
		$member_list = get_member_list($where, $sql_sort, "limit {$offset},{$page_per}");
		$total = get_allmember_count($where);
	}
	
	$page_total = max(1, ceil($total/$limit));
	$page = min($page, $page_total);
   
	$age_arr = array();
    for($i=18;$i<100;$i++){
    	$age_arr[] = $i;	
    }
	
	//来源
	/*if($member_list) {
		foreach($member_list as $key => $user) {
			if(preg_match_all("/(wf=\w+)&?|(st=\w+)&?/i", $user['source'], $matches)) {
				$member_list[$key]['source'] = $matches[1][0]."<br />".$matches[2][1];
			}
		}
	}*/
	/*$tel=get_telphone($members);
	if($members){
		foreach($members as $user){
			$user['num']=$tel[$user['telphone']];
			$member_list[]=$user;
		}
		//当点击同号手机数时排序
		if($_GET['order_tel']){
			foreach ($member_list as $key => $row) {
				$edition[$key] = $row['num'];
			}
			array_multisort($edition, SORT_DESC, $member_list);//二维数组排序
		}
	}*/

    if(!empty($member_list)) {
		//note 找出这一分页总共的用户id
		foreach($member_list as $key=>$v){
			$arr_uid[] = $v['uid'];
			if(preg_match_all("/(wf=\w+)&?|(st=\w+)&?/i", $v['source'], $matches)) {
				$member_list[$key]['source'] = $matches[1][0]."<br />".$matches[2][1];
			}	
		}
		$member_list_arr = array();
		$member_list_arr = $member_list;
		$member_list = array();
		$member_list_uid = implode(",",$arr_uid);
		//note在用户数组里面增加手机是否验证
		$member_list = get_ifcheck($member_list_uid,$member_list_arr);
	}	
	$process = 'yes';
	

	$currenturl1 = "index.php?action=allmember&h=high&page=1&clear=1";

	$currenturl = $_SERVER["REQUEST_URI"]; 
	$currenturl = preg_replace("/(&page=\d+)/","",$currenturl);

	$page_links = multipage( $total, $page_per, $page, $currenturl );

	$kefu_list = get_kefulist();
	
	
	//获取本次查询条件
	//$condition = get_condition($where);
	$title = '高级会员列表';
	require_once(adminTemplate('allmember_general'));
}

//钻石会员
function allmember_diamond(){
	global $member_progress_grade,$processlink, $allow_order, $rsort, $h;
   
	$vipprocess = MooGetGPC('process','integer','G');
	
	$clear = MooGetGPC('clear','integer','G');
	
    //钻石会员
	$s_cid = 20;
    $sql_where = 'where m.s_cid='.$s_cid;
	
	if(isset($_GET['process'])){
		$sql_where .= " and ma.memberprogress='{$vipprocess}'";
		//$res = $GLOBALS['_MooClass']['MooMySQL']->getOne("select count(*) count from {$GLOBALS['dbTablePre']}members m LEFT JOIN {$GLOBALS['dbTablePre']}member_admininfo ma ON m.uid=ma.uid ".$sql_where);
	}
	
	//获取查询条件及语句
    $where = get_search_condition('');
	$query_builder = get_query_builder($where, $allow_order, '', '', 'regdate', 'desc', $rsort);
	
	
	$where = $sql_where.$query_builder['where'];
	
	$sql_sort = $query_builder['sort'];
	$sort_arr = $query_builder['sort_arr'];
	$rsort_arr = $query_builder['rsort_arr'];
	
	$page_per = 20;
    $page = get_page();
    $limit = 20;
    $offset = ($page-1)*$limit;
    $total = 0;
    $condition = array();
    $condition['uid']=$condition['username']=$condition['nickname']=$condition['telphone']=$condition['sid'] = '';
    $member_list = array();
	 //获取本次查询条件
	$sp_condition = get_condition($where);

	if(!isset($_GET['process']) && strpos($where, 'ma.memberprogress') === false && strpos($where, 'ma.real_lastvisit') === false) {
		$limits = array($offset, $page_per);
    	$sp_result = sphinx_search($sp_condition, $limits, $sql_sort);
    	
    	$total = $sp_result['total'];
	
    	if($total > 0)
    		$member_list = $sp_result['member_list'];
    	
    }	
  
	$condition = array_merge($condition, $sp_condition);
	if($total == 0 && !isset($sp_result['member_list'])){
		$where = str_replace("AND  m.gender='3'", '', $where);
		$member_list = get_member_list($where, $sql_sort, "limit {$offset},{$page_per}");
		$total = get_allmember_count($where);
	}
	
	$page_total = max(1, ceil($total/$limit));
	$page = min($page, $page_total);
	
	$age_arr = array();
    for($i=18;$i<100;$i++){
    	$age_arr[] = $i;	
    }
	
	//来源
//	if($member_list) {
//		foreach($member_list as $key => $user) {
//			if(preg_match_all("/(wf=\w+)&?|(st=\w+)&?/i", $user['source'], $matches)) {
//				$member_list[$key]['source'] = $matches[1][0]."<br />".$matches[2][1];
//			}
//		}
//	}
	/*$tel=get_telphone($members);
	if($members){
		foreach($members as $user){
			$user['num']=$tel[$user['telphone']];
			$member_list[]=$user;
		}
		//当点击同号手机数时排序
		if($_GET['order_tel']){
			foreach ($member_list as $key => $row) {
				$edition[$key] = $row['num'];
			}
			array_multisort($edition, SORT_DESC, $member_list);//二维数组排序
		}
	}*/
	if(!empty($member_list)) {	
		//note 找出这一分页总共的用户id
		foreach($member_list as $key=>$v){
			$arr_uid[] = $v['uid'];
			if(preg_match_all("/(wf=\w+)&?|(st=\w+)&?/i", $v['source'], $matches)) {
				$member_list[$key]['source'] = $matches[1][0]."<br />".$matches[2][1];
			}	
		}
		$member_list_arr = array();
		$member_list_arr = $member_list;
		$member_list = array();
		$member_list_uid = implode(",",$arr_uid);
		//note在用户数组里面增加手机是否验证
		$member_list = get_ifcheck($member_list_uid,$member_list_arr);
	}
	$process = 'yes';

	$currenturl1 = "index.php?action=allmember&h=diamond&page=1&clear=1";

	$currenturl = $_SERVER["REQUEST_URI"]; 
	$currenturl = preg_replace("/(&page=\d+)/","",$currenturl);

	$page_links = multipage( $total, $page_per, $page, $currenturl );
	
	$kefu_list = get_kefulist();
	
	//获取本次查询条件
	//$condition = get_condition($where);
	$title = '钻石会员列表';
	require_once(adminTemplate('allmember_general'));
}

//note 未分配的VIP会员
function allmember_nosid_vip(){
	global $allow_order, $rsort;
	
	/////////////////////fanglin	
   //$sql_where = 'where m.s_cid IN(0,1) and m.sid=0';
   if(isset($_GET['choose']) && $_GET['choose']=='s_cid') unset($_GET['choose']);
   $sql_where = 'where m.s_cid in (10,20, 30) and m.sid=23';

	//$condition[] = $sql_where;
/*	$condition[] = '';
	$keyword = trim(MooGetGPC('keyword','string','G'));
    $choose = MooGetGPC('choose','string','G');
    $gender = MooGetGPC('gender','integer','G');
    $province = MooGetGPC('province','integer','G');
    $islock = MooGetGPC('islock','integer','G');
    
    if(!empty($choose) && !empty($keyword)){
    	$condition[] = " m.$choose like '%$keyword%'";
    }
    if($gender == 2 || $gender == 1){
    	if($gender == 1) $condition[] = " m.gender='1'";	
    	else $condition[] = " m.gender='0'";
    }
    if(!empty($province)){
    	$condition[] = " m.province='$province'";
    }
    if(!empty($islock)){
    	if($islock == 1) $condition[] = " m.is_lock='1'";	
    	else $condition[] = " m.is_lock='0'";
   }*/ 
    
	
	/////////////////////fanglin
    /*
    //所管理的客服id列表
   $myservice_idlist = get_myservice_idlist();

   if(empty($myservice_idlist)){
   		$condition[] = " ma.old_sid IN({$GLOBALS['adminid']})";
   }elseif($myservice_idlist == 'all'){
   		//all为客服主管能查看所有的
   }else{
   		$condition[] = " ma.old_sid IN($myservice_idlist)";
   }*/
   
    //$where = implode(' AND ',$condition);
	
	$where = get_search_condition();
    
	//if($GLOBALS['adminid']==23)  {
	   $where=preg_replace("/AND(\s)*m.sid(\s)*IN\((\d+\,)*(\d{1,5})?\)/","",$where);
	   
	//}
	
	//preg_replace("/ AND m.sid IN((\d+,)*\d*$)/","",$where);
	
	$query_builder = get_query_builder($where, $allow_order, '', '', 'regdate', 'desc', $rsort);
	$query_builder['where'];
	$where = $sql_where.$query_builder['where'];
	$sql_sort = $query_builder['sort'];
	$sort_arr = $query_builder['sort_arr'];
	$rsort_arr = $query_builder['rsort_arr'];
	
	$page_per = 20;
    $page = get_page();
    $limit = 20;
    $offset = ($page-1)*$limit;

	$total = 0;
    $condition = array();
    $condition['uid']=$condition['username']=$condition['nickname']=$condition['telphone']=$condition['sid'] = '';
    
    //echo $where.'<br/>-----';//enky testnote
	 //获取本次查询条件
	$sp_condition = get_condition($where);
 // var_dump($sp_condition);//enky testnote
	$member_list = array();
    /* if(strpos($where, 'ma.real_lastvisit') === false){
		$limits = array($offset, $page_per);
	    $sp_result = sphinx_search($sp_condition, $limits, $sql_sort);
	    $total = $sp_result['total'];
	    if($total > 0)
	    	$member_list = $sp_result['member_list'];
	}  */
  
	$condition = array_merge($condition, $sp_condition);
	$condition['sid'] = '';
	if($total == 0 && !isset($sp_result['member_list'])){
		$where = str_replace("AND  m.gender='3'", '', $where);
		$member_list = get_member_list($where, $sql_sort, "limit {$offset},{$page_per}");
		$total = get_allmember_count($where);
	}
	
	$page_total = max(1, ceil($total/$limit));
	$page = min($page, $page_total);
	
	$age_arr = array();
    for($i=18;$i<100;$i++){
    	$age_arr[] = $i;	
    }
	//$member_list = get_member_list($where, $sql_sort, "limit {$offset},{$page_per}");
	
	//来源
	if($member_list) {
		foreach($member_list as $key => $user) {
			if(preg_match_all("/(wf=\w+)&?|(st=\w+)&?/i", $user['source'], $matches)) {
				$member_list[$key]['source'] = $matches[1][0]."<br />".$matches[2][1];
			}
		}
	}
	/*$tel=get_telphone($members);
	if($members){
		foreach($members as $user){
			$user['num']=$tel[$user['telphone']];
			$member_list[]=$user;
		}
		//当点击同号手机数时排序
		if($_GET['order_tel']){
			foreach ($member_list as $key => $row) {
				$edition[$key] = $row['num'];
			}
			array_multisort($edition, SORT_DESC, $member_list);//二维数组排序
		}
	}*/
	//echo "<span style='display:none;'>$sql</span>";
	$currenturl = "index.php?action=allmember&h=nosid";
	$currenturl = "index.php?action=allmember&h=nosid&clear=1";
	$page_links = multipage( $total, $page_per, $page, $currenturl );
	
	$kefu_list = get_kefulist();
	
	//获取本次查询条件
	//$condition = get_condition($where);
	$title = '未分配VIP会员列表';
	require_once(adminTemplate('allmember_general'));
}

//note 客服放弃会员列表
function allmember_giveup(){
	global $allow_order, $rsort;

    $sql_where = 'where m.sid = 52 AND ma.is_delete = 1 ';

	$condition[] = '';

	$keyword = trim(MooGetGPC('keyword','string','G'));
    $choose = MooGetGPC('choose','string','G');
    $gender = MooGetGPC('gender','integer','G');
    $province = MooGetGPC('province','integer','G');
    $islock = MooGetGPC('islock','integer','G');
    
    if(!empty($choose) && !empty($keyword)){
		if($choose == 'uid'){
			$condition[] = " m.{$choose} = '{$keyword}'";
		}else{
			$condition[] = " m.{$choose} like '%{$keyword}%'";
		}
    	$link .= "{$choose}={$keyword}";	
    }
    if($gender == 2 || $gender == 1){
    	if($gender == 1) $condition[] = " m.gender='1'";	
    	else $condition[] = " m.gender='0'";	
    	$link .= "&gender=$gender";
    }
    if(!empty($province)){
    	$condition[] = " m.province='$province'";	
    	$link .= "province=$province";
    }
    if(!empty($islock)){
    	if($islock == 1) $condition[] = " m.is_lock='1'";	
    	else $condition[] = " m.is_lock='0'";	
    	$link .= "&lock=$islock";
    }
        
    //所管理的客服id列表
   $myservice_idlist = get_myservice_idlist();

   if(empty($myservice_idlist)){
   		$condition[] = " ma.old_sid IN({$GLOBALS['adminid']})";
   }elseif($myservice_idlist == 'all'){
   		//all为客服主管能查看所有的
   }else{
   		$condition[] = " ma.old_sid IN($myservice_idlist)";
   }
   
    $where = implode(' AND ',$condition);
//    $where = get_search_condition($sql_where);
	$query_builder = get_query_builder($where, $allow_order, '', '', 'regdate', 'desc', $rsort);
	
	$where = $sql_where.$query_builder['where'];
	$sql_sort = $query_builder['sort'];
	$sort_arr = $query_builder['sort_arr'];
	$rsort_arr = $query_builder['rsort_arr'];
	
	$page_per = 20;
    $page = get_page();
    $limit = 20;
	$total = get_allmember_count($where);
	$page_total = max(1, ceil($total/$limit));
	$page = min($page, $page_total);
    $offset = ($page-1)*$limit;
	
	$member_list = get_member_list($where, $sql_sort, "limit {$offset},{$page_per}");
	
	//来源
	if($member_list) {
		foreach($member_list as $key => $user) {
			if(preg_match_all("/(wf=\w+)&?|(st=\w+)&?/i", $user['source'], $matches)) {
				$member_list[$key]['source'] = $matches[1][0]."<br />".$matches[2][1];
			}
		}
	}
	
	//	$member_list = get_member_list($where,"LIMIT {$offset},{$limit}");
	//echo "<span style='display:none;'>$sql</span>";
	$currenturl = "index.php?action=allmember&h=giveup";
	$currenturl1 = "index.php?action=allmember&h=giveup&clear=1";
	$page_links = multipage( $total, $page_per, $page, $currenturl );
	
	$kefu_list = get_kefulist();
	
	//获取本次查询条件
	$condition = get_condition($where);
	$title = '删除会员列表';
	$generalmembers = "generalmembers";
	require_once(adminTemplate('allmember_general_recycle'));

}


//note 客服删除不可回收的会员列表
function allmember_notgiveup(){
	global $allow_order, $rsort;

    $sql_where = 'where m.sid = 123 AND ma.is_delete = 2 ';

	$condition[] = '';

	$keyword = trim(MooGetGPC('keyword','string','G'));
    $choose = MooGetGPC('choose','string','G');
    $gender = MooGetGPC('gender','integer','G');
    $province = MooGetGPC('province','integer','G');
    $islock = MooGetGPC('islock','integer','G');
    
    $link = '';
    if(!empty($choose) && !empty($keyword)){
    	if($choose == 'uid'){
			$condition[] = " m.{$choose} = '{$keyword}'";
		}else{
			$condition[] = " m.{$choose} like '%{$keyword}%'";
		}
    	$link .= "$choose=$keyword";	
    }
    if($gender == 2 || $gender == 1){
    	if($gender == 1) $condition[] = " m.gender='1'";	
    	else $condition[] = " m.gender='0'";	
    	$link .= "&gender=$gender";
    }
    if(!empty($province)){
    	$condition[] = " m.province='$province'";	
    	$link .= "province=$province";
    }
    if(!empty($islock)){
    	if($islock == 1) $condition[] = " m.is_lock='1'";	
    	else $condition[] = " m.is_lock='0'";	
    	$link .= "&lock=$islock";
    }
    
/*	$order = 'DESC';
	$order2 = '';
    if($_GET['field']){
    	$field = MooGetGPC('field','string','G');
    	$order = $_GET['order']=='DESC' ? 'ASC' : 'DESC';
    	if($field=='lastvisit'){
			$order2 = "ORDER BY ma.{$field} ".$order;
		}else{
			$order2 = "ORDER BY m.{$field} ".$order;
		}
    	$link .= "&field={$field}&order={$_GET['order']}";
    }*/
    
    //所管理的客服id列表
   $myservice_idlist = get_myservice_idlist();

   if(empty($myservice_idlist)){
   		$condition[] = " ma.old_sid IN ({$GLOBALS['adminid']})";
   }elseif($myservice_idlist == 'all'){
   		//all为客服主管能查看所有的
   }else{
   		$condition[] = " ma.old_sid IN($myservice_idlist)";
   }
   
    $where = implode(' AND ',$condition);
//    $where = get_search_condition($sql_where);
	$query_builder = get_query_builder($where, $allow_order, '', '', 'regdate', 'desc', $rsort);
	
	$where = $sql_where.$query_builder['where'];
	$sql_sort = $query_builder['sort'];
	$sort_arr = $query_builder['sort_arr'];
	$rsort_arr = $query_builder['rsort_arr'];
	
	$page_per = 20;
    $page = get_page();
    $limit = 20;
	$total = get_allmember_count($where);
	$page_total = max(1, ceil($total/$limit));
	$page = min($page, $page_total);
    $offset = ($page-1)*$limit;
	
	$member_list = get_member_list($where, $sql_sort, "limit {$offset},{$page_per}");
	
	//来源
	if($member_list) {
		foreach($member_list as $key => $user) {
			if(preg_match_all("/(wf=\w+)&?|(st=\w+)&?/i", $user['source'], $matches)) {
				$member_list[$key]['source'] = $matches[1][0]."<br />".$matches[2][1];
			}
		}
	}
	/*$tel=get_telphone($members);
	if($members){
		foreach($members as $user){
			$user['num']=$tel[$user['telphone']];
			$member_list[]=$user;
		}
		//当点击同号手机数时排序
		if($_GET['order_tel']){
			foreach ($member_list as $key => $row) {
				$edition[$key] = $row['num'];
			}
			array_multisort($edition, SORT_DESC, $member_list);//二维数组排序
		}
	}*/

	//	$member_list = get_member_list($where,"LIMIT {$offset},{$limit}");
	//echo "<span style='display:none;'>$sql</span>";
	$currenturl = "index.php?action=allmember&h=notgiveup&".$link;
	$currenturl1 = "index.php?action=allmember&h=notgiveup&clear=1";
	$page_links = multipage( $total, $page_per, $page, $currenturl );
	
	$kefu_list = get_kefulist();
	
	//获取本次查询条件
	$condition = array();
    $condition['uid']=$condition['username']=$condition['nickname']=$condition['telphone']=$condition['sid'] = '';
	$condition = array_merge($condition, get_condition($where));
	$title = '删除会员列表';
	require_once(adminTemplate('allmember_general_notrecycle'));

}

//采集加入的会员
function allmember_collect(){
	global $allow_order, $rsort,$dbTablePre;
	
    
    //采集会员
	$usertype = 3;
    $sql_where = 'where usertype='.$usertype ;

    //获取查询条件及语句
    //$where = get_search_condition('');
		
	$condition[] = $where;
	
	$keyword = trim(MooGetGPC('keyword','string','G'));
    $choose = MooGetGPC('choose','string','G');
    $gender = MooGetGPC('gender','integer','G');
    $province = MooGetGPC('province','integer','G');
	$city=MooGetGPC('city','integer','G');
    $islock = MooGetGPC('islock','integer','G');
    $age_start =  MooGetGPC('age_start','integer','G');
    $age_end =  MooGetGPC('age_end','integer','G');
	$regdate=MooGetGPC('regdate','string','G');
	$endTime=MooGetGPC('end','string','G');

	
   
	
    if(!empty($choose) && !empty($keyword)){
		if($choose=='username'||$choose=='nickname'){
			$condition[] = " m.$choose like '%$keyword%'";
		}else{
			$condition[] = " m.$choose = '$keyword'";
		}
    	
    }
    
    if($gender == 2 || $gender == 1){
    	if($gender == 1) {
			$condition[] = " m.gender='1'";	
		} else {
			$condition[] = " m.gender='0'";
		}
    }elseif($gender == 3){
    	$condition[] = "m.gender>=0 and m.gender<=1 "; 	
    }
    if(!empty($province)){
        if($province=='10101201' || $province=='10101002'){
    	    $condition[] = " m.city='$province'";
        }else{
            $condition[] = " m.province='$province'";
        }
    }
	
	if(!empty($city)){
        $condition[] = " m.city='$city'";
       
    }
	
    if(!empty($islock)){
    	if($islock == 1) {
			$condition[] = " m.is_lock='1'";	
		} else {
			$condition[] = " m.is_lock='0'";
		}
    }
	if($age_start || $age_end){
		$age_start = $age_start ? $age_start : 0;
		$age_end = $age_end ? $age_end : 100;
		$age1 = min($age_start, $age_end);
		$age2 = max($age_start, $age_end);
		
		$age1 = date('Y') - $age1;
		$age2 = date('Y') - $age2;
		$condition[] = " m.birthyear >= $age2";
		$condition[] = " m.birthyear <= $age1";
		
	}
	
	//当前在线1,2为一天，3为一周内，4为一周外
	if(isset($_GET['online'])){
		$online = MooGetGPC('online','string','G');
		switch($online){
			case 1:
				$time = time()-100;
				$condition[] = " ma.real_lastvisit>={$time}";
			break;
			case 2:
				$time = time()-24*3600;
				$condition[] = " ma.real_lastvisit>={$time}";
			break;
			case 3:
				$time1 = time()- 7*24*3600;
				$time2 = time() - 24*3600;
				$condition[] = " ma.real_lastvisit >= {$time1}";  
				$condition[] = " ma.real_lastvisit <= {$time2}";
			break;
			case 4:
				$time = time()- 7*24*3600;
				$condition[] = " ma.real_lastvisit <= {$time}";
			break;	
		}

			
	}
    

   
   
   //注册时间
   if($regdate){
   	 $regstamp=strtotime('+1 day',strtotime($regdate));
	 $regstampstart=strtotime('-9 day',strtotime($regdate));
   	 $condition[] = "   m.regdate>='{$regstampstart}' and m.regdate<='{$regstamp}'";
   }
   
  
  

   
	
	//即去掉了所管理的客服id列表，只加入sid=1的
	if (in_array($GLOBALS['groupid'],$GLOBALS['admin_service_arr']) || in_array($GLOBALS['groupid'],$GLOBALS['admin_all_group'] ) || in_array($GLOBALS['groupid'],$GLOBALS['admin_service_team'])){
	    $condition[] ="  m.sid=1 ";
	}
   
   
   $where = implode(' AND ',$condition);

	
	$query_builder = get_query_builder($where, $allow_order, '', '', 'action_time', 'asc', $rsort);// get_query_builder($where, $allow_order, '', '', 'regdate', 'desc', $rsort);

	$where = $sql_where.$query_builder['where'];
	
	
	
	$sql_sort = $query_builder['sort'];
	$sort_arr = $query_builder['sort_arr'];
	$rsort_arr = $query_builder['rsort_arr'];
	
	
	
	$order=MooGetGPC('order','string','G');
	$method=MooGetGPC('method','string','G');

	if(!empty($order) && !empty($method)){
	   $sql_sort="order by {$order}  {$method}";
	}
	
	
	if (empty($sql_sort)){//default order by uid desc
		$sql_sort="order by uid desc";
		
	}
	
	
    //过期全权
	$isExpire=MooGetGPC('isExpire','string','G');
    $expiretime=strtotime("-45 day");
    if($isExpire==1){
       $where .=" and f.action_time<{$expiretime}"; //使用过期
    }elseif ($isExpire==2){
       $where .=" and  f.action_time>={$expiretime}";//使用未过期
    }elseif ($isExpire==3){//未使用的
       //$where .=" and m.uid!=f.uid";
    }   
	
	
	$page_per = 20;
    $page = get_page();
    $limit = 20;
    $offset = ($page-1)*$limit;
	
	//$member_list = get_member_list($where, $sql_sort, "limit {$offset},{$page_per}");
	
    
	$total = 0;
    //$condition = array();
    $condition['uid']=$condition['username']=$condition['nickname']=$condition['telphone']=$condition['sid'] = '';
    
	 //获取本次查询条件
	 
	 $sp_condition = get_condition($where);
     
	

	$member_list = array();
	if(strpos($where, 'ma.real_lastvisit') === false) {
		$limits = array($offset, $page_per);
	    $sp_result = sphinx_search($sp_condition, $limits, $sql_sort);
	    $total = $sp_result['total'];
	    if($total > 0)
	    	$member_list = $sp_result['member_list'];
	} 

	$condition = array_merge($condition, $sp_condition);
	if($total == 0 && !isset($sp_result['member_list'])){
		$where = str_replace("AND  m.gender='3'", '', $where);
		$member_list = get_member_list($where, $sql_sort, "limit {$offset},{$page_per}");
		
		//$total = get_allmember_count($where);
		$sql = "select count(*) as count from {$dbTablePre}members_search as m left join {$dbTablePre}member_admininfo as ma on m.uid = ma.uid left join {$dbTablePre}full_log  f on m.uid=f.uid {$where} limit 1";
        
	    $result = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
		$total=$result['count'];
	}
	
	$page_total = max(1, ceil($total/$limit));
	$page = min($page, $page_total);
	
	$age_arr = array();
    for($i=18;$i<100;$i++){
    	$age_arr[] = $i;	
    }
	
	//来源
	if($member_list) {
		foreach($member_list as $key => $user) {
			if(preg_match_all("/(wf=\w+)&?|(st=\w+)&?/i", $user['source'], $matches)) {
				$member_list[$key]['source'] = $matches[1][0]."<br />".$matches[2][1];
			}
		}
	}
	$currenturl = "index.php?action=allmember&h=collect&order={$order}&method={$method}&isExpire={$isExpire}&province={$province}&city={$city}";
	

    //$currenturl = "index.php?action=allmember&h=collect&page={$page}&clear=1";
	$page_links = multipage( $total, $page_per, $page, $currenturl );
	
	 $kefu_list = get_kefulist();
	
	//获取本次查询条件
	//$condition = get_condition($where);

	$title = '全权会员列表';
	require_once(adminTemplate('allmember_collect'));
}





//外站加入的会员
function allmember_outsite(){
	global $allow_order, $rsort;
    
    //外站加入的会员
	$usertype = 2;
    $sql_where = 'where m.usertype='.$usertype;
    //获取查询条件及语句
    $where = get_search_condition('');
	$query_builder = get_query_builder($where, $allow_order, '', '', 'regdate', 'desc', $rsort);
	
	$where = $sql_where.$query_builder['where'];
	$sql_sort = $query_builder['sort'];
	$sort_arr = $query_builder['sort_arr'];
	$rsort_arr = $query_builder['rsort_arr'];
	
	$page_per = 20;
    $page = get_page();
    $limit = 20;
    $offset = ($page-1)*$limit;
	
    
	$total = 0;
    $condition = array();
    $condition['uid']=$condition['username']=$condition['nickname']=$condition['telphone']=$condition['sid'] = '';
    
	 //获取本次查询条件
	$sp_condition = get_condition($where);
	$member_list = array();
	if(strpos($where, 'ma.real_lastvisit') === false){
		$limits = array($offset, $page_per);
	    $sp_result = sphinx_search($sp_condition, $limits, $sql_sort);
	    $total = $sp_result['total'];
	    if($total > 0)
	    	$member_list = $sp_result['member_list'];
	}

	$condition = array_merge($condition, $sp_condition);
	if($total == 0 && !isset($sp_result['member_list'])){
		$where = str_replace("AND  m.gender='3'", '', $where);
		$member_list = get_member_list($where, $sql_sort, "limit {$offset},{$page_per}");
		$total = get_allmember_count($where);
	}
	
	$page_total = max(1, ceil($total/$limit));
	$page = min($page, $page_total);
	
	$age_arr = array();
    for($i=18;$i<100;$i++){
    	$age_arr[] = $i;	
    }
    
	//$member_list = get_member_list($where, $sql_sort, "limit {$offset},{$page_per}");
	
	/*$tel=get_telphone($members);
	if($members){
		foreach($members as $user){
			$user['num']=$tel[$user['telphone']];
			$member_list[]=$user;
		}
		//当点击同号手机数时排序
		if($_GET['order_tel']){
			foreach ($member_list as $key => $row) {
				$edition[$key] = $row['num'];
			}
			array_multisort($edition, SORT_DESC, $member_list);//二维数组排序
		}
	}*/
	$currenturl = "index.php?action=allmember&h=outsite";
	$currenturl1 = "index.php?action=allmember&h=outsite&clear=1";
	$page_links = multipage( $total, $page_per, $page, $currenturl );
	
	$kefu_list = get_kefulist();
	
	//获取本次查询条件
	//$condition = get_condition($where);
//	$order = $_GET['order']=='DESC' ? 'ASC' : 'DESC';
	$title = '外站加入会员列表';
	require_once(adminTemplate('allmember_general'));
}

//联盟会员
function allmember_alliance(){
	global $allow_order, $rsort;
	
    //联盟会员
	$usertype = 4;
	$sql_where = 'where m.usertype='.$usertype;
    //获取查询条件及语句
    $where = get_search_condition('');
	$query_builder = get_query_builder($where, $allow_order, '', '', 'regdate', 'desc', $rsort);
	$where = $sql_where.$query_builder['where'];
	$sql_sort = $query_builder['sort'];
	$sort_arr = $query_builder['sort_arr'];
	$rsort_arr = $query_builder['rsort_arr'];
	
	
	$page_per = 20;
    $page = get_page();
    $limit = 20;
    $offset = ($page-1)*$limit;
	
    
	$total = 0;
    $condition = array();
    $condition['uid']=$condition['username']=$condition['nickname']=$condition['telphone']=$condition['sid'] = '';
    
	 //获取本次查询条件
	$sp_condition = get_condition($where);

	$member_list = array();
	if(strpos($where, 'ma.real_lastvisit') === false){
		$limits = array($offset, $page_per);
    	$sp_result = sphinx_search($sp_condition, $limits, $sql_sort);
    	$total = $sp_result['total'];
    	if($total > 0)
    		$member_list = $sp_result['member_list'];
	}
	$condition = array_merge($condition, $sp_condition);
	if($total == 0 && !isset($sp_result['member_list'])){
		$where = str_replace("AND  m.gender='3'", '', $where);
		$member_list = get_member_list($where, $sql_sort, "limit {$offset},{$page_per}");
		$total = get_allmember_count($where);
	}
	
	$page_total = max(1, ceil($total/$limit));
	$page = min($page, $page_total);
	
	$age_arr = array();
    for($i=18;$i<100;$i++){
    	$age_arr[] = $i;	
    }
	//$member_list = get_member_list($where, $sql_sort, "limit {$offset},{$page_per}");
	
	//来源
	if($member_list) {
		foreach($member_list as $key => $user) {
			if(preg_match_all("/(wf=\w+)&?|(st=\w+)&?/i", $user['source'], $matches)) {
				$member_list[$key]['source'] = $matches[1][0]."<br />".$matches[2][1];
			}
		}
	}
	/*$tel=get_telphone($members);
	if($members){
		foreach($members as $user){
			$user['num']=$tel[$user['telphone']];
			$member_list[]=$user;
		}
		//当点击同号手机数时排序
		if($_GET['order_tel']){
			foreach ($member_list as $key => $row) {
				$edition[$key] = $row['num'];
			}
			array_multisort($edition, SORT_DESC, $member_list);//二维数组排序
		}
	}*/
	$currenturl = "index.php?action=allmember&h=alliance";
	$currenturl = "index.php?action=allmember&h=alliance&clear=1";
	$page_links = multipage( $total, $page_per, $page, $currenturl );
	
	$kefu_list = get_kefulist();
	
	//获取本次查询条件
	//$condition = get_condition($where);

	$title = '联盟会员列表';
	require_once(adminTemplate('allmember_general'));
}

function allmember_inside(){
	global $allow_order, $rsort;
	
    //内部会员
	$usertype = 5;
	$sql_where = 'where m.usertype='.$usertype;
    //获取查询条件及语句
    $where = get_search_condition('');
	$query_builder = get_query_builder($where, $allow_order, '', '', 'regdate', 'desc', $rsort);
	
	$where = $sql_where.$query_builder['where'];
	$sql_sort = $query_builder['sort'];
	$sort_arr = $query_builder['sort_arr'];
	$rsort_arr = $query_builder['rsort_arr'];
	
	$page_per = 20;
    $page = get_page();
    $limit = 20;
    $offset = ($page-1)*$limit;
	
	$total = 0;
    $condition = array();
    $condition['uid']=$condition['username']=$condition['nickname']=$condition['telphone']=$condition['sid'] = '';
    
	 //获取本次查询条件
	$sp_condition = get_condition($where);
	
	$member_list = array();
	if(strpos($where, 'ma.real_lastvisit') === false){
		$limits = array($offset, $page_per);
	    $sp_result = sphinx_search($sp_condition, $limits, $sql_sort);
	    $total = $sp_result['total'];
	    if($total > 0)
	    	$member_list = $sp_result['member_list'];
	}

	$condition = array_merge($condition, $sp_condition);
	if($total == 0 && !isset($sp_result['member_list'])){
		$where = str_replace("AND  m.gender='3'", '', $where);
		$member_list = get_member_list($where, $sql_sort, "limit {$offset},{$page_per}");
		$total = get_allmember_count($where);
	}
	
	$page_total = max(1, ceil($total/$limit));
	$page = min($page, $page_total);
	
	$age_arr = array();
    for($i=18;$i<100;$i++){
    	$age_arr[] = $i;	
    }
    
	//$member_list = get_member_list($where, $sql_sort, "limit {$offset},{$page_per}");
	
	//来源
	if($member_list) {
		foreach($member_list as $key => $user) {
			if(preg_match_all("/(wf=\w+)&?|(st=\w+)&?/i", $user['source'], $matches)) {
				$member_list[$key]['source'] = $matches[1][0]."<br />".$matches[2][1];
			}
		}
	}
	/*$tel=get_telphone($members);
	if($members){
		foreach($members as $user){
			$user['num']=$tel[$user['telphone']];
			$member_list[]=$user;
		}
		//当点击同号手机数时排序
		if($_GET['order_tel']){
			foreach ($member_list as $key => $row) {
				$edition[$key] = $row['num'];
			}
			array_multisort($edition, SORT_DESC, $member_list);//二维数组排序
		}
	}*/
	$currenturl = "index.php?action=allmember&h=inside";
	$currenturl1 = "index.php?action=allmember&h=inside&clear=1";
	$page_links = multipage( $total, $page_per, $page, $currenturl );
	
	$kefu_list = get_kefulist();
	
	//获取本次查询条件
	//$condition = get_condition($where);
	$title = '内部会员列表';
	require_once(adminTemplate('allmember_general'));
}

//注册未分配普通会员列表
function allmember_regnoallot_members(){
	global $allow_order, $rsort;
 
/*
	$sql_where = "WHERE m.sid=0 AND m.usertype=1 AND 
				 (m.height='-1' OR m.salary='-1' OR m.children='-1' OR m.oldsex='-1'
				 OR c.age1='-1' OR c.age2='-1' OR c.marriage='-1' OR c.children='-1' OR c.body='-1')";
*/
	if(isset($_GET['usertype'])) unset($_GET['usertype']);
    if(in_array($GLOBALS['groupid'],$GLOBALS['admin_service_arr'])){
    	$sql_where = "WHERE m.sid=1 AND m.usertype=1 AND m.is_well_user=0 AND m.s_cid=40 AND m.bgtime=0";
    }else{
    	$sql_where = "WHERE m.sid='{$GLOBALS['adminid']}' AND m.usertype=1 AND m.is_well_user=0 AND m.s_cid=40 AND m.bgtime=0";
    }
   
    $total = 0;
    $condition = array();

	$keyword = trim(MooGetGPC('keyword','string','G'));
    $choose = MooGetGPC('choose','string','G');
    $gender = MooGetGPC('gender','string','G');
    $province = MooGetGPC('province','integer','G');
    $salary = MooGetGPC('salary','integer','G');
	$regdate=MooGetGPC('regdate','string','G');
    $source = MooGetGPC('source','string','G');
    $isMobileChecked = MooGetGPC('isMobileChecked','string','G');
    //note 新增加的搜索条件
    $age_start = MooGetGPC('age_start','integer','G');
    $age_end = MooGetGPC('age_end','integer','G');
    $age_arr = array();
    for($i=18;$i<100;$i++){
    	$age_arr[] = $i;	
    }
   //note 年龄条件
    if(!empty($age_start) || !empty($age_end)){
	    if ($age_end > 0 && $age_start > 0) {
	        $tmp_start = date('Y', time()) - intval($age_start);
	        $tmp_end = date('Y', time()) - intval($age_end);
	        if ($age_start > $age_end) {
	            $condition[] =  " m.birthyear >= '" . $tmp_start. "'";
	            $condition[] =  " m.birthyear <= '" . $tmp_end . "'";
	        } else {
	            $condition[] = " m.birthyear >= '" . $tmp_end . "'";
	            $condition[] = " m.birthyear <= '" . $tmp_start . "'";
	        }
	    } else if ($age_start > 0) {
	        $tmp_start = date('Y', time()) - intval($age_start);
	        $condition[] = " m.birthyear <= '" . $tmp_start . "'";
	    } else if ($age_end > 0) {
	        $tmp_end = date('Y', time()) - intval($age_end);
	        $condition[] = " m.birthyear >= '" . $tmp_end . "'";
	    }
	}
	
    //note 收入条件
	if(isset($_GET['salary']) && $_GET['salary'] != '-1'){
		$condition[] = " m.salary='{$salary}'";
	}
	
    //note 用户类型
	if(isset($_GET['usertype'])){
		$usertype=MooGetGPC('usertype','integer','G');
		$condition[] = " usertype='{$usertype}'";
	}
	
	//当前在线1,2为一天，3为一周内，4为一周外
	if(isset($_GET['online'])){
		$online = MooGetGPC('online','string','G');
		switch($online){
			case 1:
				$time = time()-100;
				$condition[] = " ma.real_lastvisit>={$time}";
			break;
			case 2:
				$time = time()-24*3600;
				$condition[] = " ma.real_lastvisit>={$time}";
			break;
			case 3:
				$time1 = time()- 7*24*3600;
				$time2 = time() - 24*3600;
				$condition[] = " ma.real_lastvisit >= {$time1}";  
				$condition[] = " ma.real_lastvisit <= {$time2}";
			break;
			case 4:
				$time = time()- 7*24*3600;
				$condition[] = " ma.real_lastvisit <= {$time}";
			break;	
		}

			
	}
	
	//note 搜索上传照片数目条件
	if(isset($_GET['uploadpicnum'])){
		$uploadpicnum=MooGetGPC('uploadpicnum','integer','G');
		switch($uploadpicnum){
			case 6:
				$condition[] = " m.images_ischeck = 0 ";	
				break;
			case 1:
				$condition[] = " m.pic_num >= 1 and m.pic_num <= 5 ";		
				break;
			case 2:	
				$condition[] = " m.pic_num >= 6 and m.pic_num <= 10 ";		
				break;
			case 3:	
				$condition[] = " m.pic_num >= 11 and m.pic_num <= 15 ";		
				break;
			case 4:	
				$condition[] = " m.pic_num >= 16 and m.pic_num <= 20 ";		
				break;
			case 5:	
				$condition[] = " m.pic_num >= 20 and m.pic_num >= 10000";		
				break;			
		}
		
		
	}
	
    if(!empty($source)){
    	if(!empty($source)) $condition[] = " mb.source like '%{$source}%'";	
		//if(!empty($source)) $condition[] = " mb.source={$source}'";	
    }
   
    if(!empty($choose) && !empty($keyword)){
//    	$condition[] = " m.$choose like '%$keyword%'";
       $condition[] = " m.$choose = $keyword";
    }
    if($gender == 2 || $gender == 1){
    	//note 性别男    		
    	if($gender == 1) $condition[] = " m.gender='0'";
    	//note 性别女
    	if($gender == 2) $condition[] = " m.gender='1'";
    }elseif($gender == 3){
    	 $condition[] = " m.gender='3'";
    }
    
    if(!empty($province)){
    	$condition[] = " m.province='$province'";
    }
	
	if(!empty($regdate)){
	  $regstamp=strtotime('+1 day',strtotime($regdate));
	  $regstampstart=strtotime('-9 day',strtotime($regdate));
   	  $condition[] = "   m.regdate>='{$regstampstart}' and m.regdate<='{$regstamp}'";
	
	}
    
    
    if(!empty($isMobileChecked)){
        $condition[]=" c.telphone>1";
    }
    
/*	$order = 'DESC';
    if($_GET['field']){
    	$field = MooGetGPC('field','string','G');
    	$order = $_GET['order']=='DESC' ? 'ASC' : 'DESC';
		if($field=='lastvisit'){
			$order2 = "ORDER BY a.{$field} ".$order;
		}else{
			$order2 = "ORDER BY m.{$field} ".$order;
		}
			$link .= "&field={$field}&order={$_GET['order']}";
    }else{
		$order2 = "ORDER BY m.salary desc,m.images_ischeck desc";
	}*/
    
    $where = implode(' AND ',$condition);
	
	$query_builder = get_query_builder($where, $allow_order, '', '', 'regdate', 'desc', $rsort);
	
	$where = empty($query_builder['where']) ? $sql_where : $sql_where.' AND'.$query_builder['where'];
	$sql_sort = $query_builder['sort'];
	$sort_arr = $query_builder['sort_arr'];
	$rsort_arr = $query_builder['rsort_arr'];
//    $where = get_search_condition($sql_where);
//	$link = get_search_link();

	/*
	$sql = "SELECT count(*) AS c FROM {$GLOBALS['dbTablePre']}members m 
			LEFT JOIN {$GLOBALS['dbTablePre']}choice c
			ON m.uid=c.uid
			$where AND m.uid NOT IN(SELECT uid FROM {$GLOBALS['dbTablePre']}member_admininfo)";
			*/
  /*  $sql = "SELECT count(*) AS c FROM {$GLOBALS['dbTablePre']}members_search m 
			LEFT JOIN {$GLOBALS['dbTablePre']}member_admininfo a
			ON m.uid=a.uid
			$where";
			
	$members_total = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	$total = $members_total['c'];*/
	
	$page_per = 20;
	$limit = 20;
	$link = '';
	if(isset($_GET['page_per'])){
		$page_per = MooGetGPC('page_per','integer','G');
 		$limit = $page_per;
 		$link = "&page_per=$page_per";
	}
    $page = get_page();
    $offset = ($page-1)*$limit;

    $condition = array();
    $condition['uid']=$condition['username']=$condition['nickname']=$condition['telphone']=$condition['sid'] = '';
	 //获取本次查询条件
	$sp_condition = get_condition($where);
	$member_list = array();
	
	//372库数据错误，未分配的显示都是已分配的组员，暂时屏蔽下面代码
	
	/*
	 if(!strpos($where, 'ma.real_lastvisit')>0){
		$limits = array($offset, $page_per);
	    $sp_result = sphinx_search($sp_condition, $limits, $sql_sort);
	    $total = $sp_result['total'];
	    if($total > 0)
	    	$member_list = $sp_result['member_list'];
	} */
    
	$condition = array_merge($condition, $sp_condition);
	

	if($total == 0 && !isset($sp_result['member_list'])){
		$where = preg_replace("/AND\s+m.gender='3'/", '', $where);
		$member_list = get_member_list($where, $sql_sort, "limit {$offset},{$page_per}");
		$total = get_allmember_count($where);
		
	}
	
	
	$page_total = max(1, ceil($total/$limit));
	$page = min($page, $page_total);
	/*
	$sql = "SELECT m.* FROM {$GLOBALS['dbTablePre']}members m 
			LEFT JOIN {$GLOBALS['dbTablePre']}choice c
			ON m.uid=c.uid
			$where AND m.uid  NOT IN(SELECT uid FROM {$GLOBALS['dbTablePre']}member_admininfo) {$order2}  LIMIT {$offset},{$limit}";
		*/
	/*$sql = "SELECT m.*,a.is_delete,a.real_lastvisit, a.old_sid FROM {$GLOBALS['dbTablePre']}members_search m 
			LEFT JOIN {$GLOBALS['dbTablePre']}member_admininfo a
			ON m.uid=a.uid
			$where {$sql_sort} LIMIT {$offset},{$limit}";
		//echo $sql;
	$member_list = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);*/


    if(!empty($member_list)) {
		//note 找出这一分页总共的用户id
		foreach($member_list as $key=>$v){
			$arr_uid[] = $v['uid'];
			if(preg_match_all("/(wf=\w+)&?|(st=\w+)&?/i", $v['source'], $matches)) {
				$member_list[$key]['source'] = $matches[1][0]."<br />".$matches[2][1];
			}	
		}
		$member_list_arr = array();
		$member_list_arr = $member_list;
		$member_list = array();
		$member_list_uid = implode(",",$arr_uid);
		//note在用户数组里面增加手机是否验证
		$member_list = get_ifcheck($member_list_uid,$member_list_arr);
	}	
       
        
	/*$tel=get_telphone($members);
	if($members){
		foreach($members as $user){
			$user['num']=$tel[$user['telphone']];
			$member_list[]=$user;
		}
		//当点击同号手机数时排序
		if($_GET['order_tel']){
			foreach ($member_list as $key => $row) {
				$edition[$key] = $row['num'];
			}
			array_multisort($edition, SORT_DESC, $member_list);//二维数组排序
		}
	}*/
//echo "<span style='display:none;'>$sql</span>";
	$currenturl = "index.php?action=allmember&h=regnoallot_members".$link;
	$currenturl1 = "index.php?action=allmember&h=regnoallot_members&clear=1";
	
	$page_links = multipage( $total, $page_per, $page, $currenturl );

	$kefu_list = get_kefulist();

   
	//获取本次查询条件
	//$condition = get_condition($where);
	$title = '未分配普通会员列表';

	require_once(adminTemplate('allmember_regnoallot_members'));
}

//注册未分配优质会员
function allmember_goodnoallot_members(){
	global $allow_order, $rsort;
	
 	if(in_array($GLOBALS['groupid'],$GLOBALS['admin_service_arr'])){
    	$sql_where = "WHERE m.sid=1 AND m.usertype=1 AND m.is_well_user=1 AND m.s_cid=40";
    }else{
    	$sql_where = "WHERE m.sid='{$GLOBALS['adminid']}' AND m.usertype=1 AND m.is_well_user=1 AND m.s_cid=40";
    }
	$condition[] = '';

	$keyword = trim(MooGetGPC('keyword','string','G'));
    $choose = MooGetGPC('choose','string','G');
    $gender = MooGetGPC('gender','integer','G');
    $province = MooGetGPC('province','integer','G');
    $islock = MooGetGPC('islock','integer','G');
	$birthyear = MooGetGPC('birthyear','string','G');
	$source=MooGetGPC('source','string','G');
	$salary = MooGetGPC('salary','string','G');
		//note 新增加的搜索条件
    $age_start = MooGetGPC('age_start','integer','G');
    $age_end = MooGetGPC('age_end','integer','G');
    //echo $birthyear;
    if(!empty($choose) && !empty($keyword)){
    	$condition[] = " m.$choose like '%$keyword%'";	
    }
    if($gender == 2 || $gender == 1){
    	if($gender == 1) $condition[] = " m.gender='1'";	
    	else $condition[] = " m.gender='0'";
    }elseif($gender == 3){
    	
    	$condition[] = " m.gender='3'";
    }
    
    if(!empty($province)){
    	$condition[] = " m.province='$province'";
    }
    if(!empty($islock)){
    	if($islock == 1) $condition[] = " m.is_lock='1'";	
    	else $condition[] = " m.is_lock='0'";
    }
  	if(!empty($birthyear)){
		$max_age = date('Y')-35;
		$min_age = date('Y')-25;
		//$condition[] = "m.birthyear between $max_age and $min_age";
		$condition[] = "m.birthyear >= {$max_age}";
		$condition[] = "m.birthyear <= {$min_age}";
	}else{

	   //note 年龄条件
	    if(!empty($age_start) || !empty($age_end)){
		    if ($age_end > 0 && $age_start > 0) {
		        $tmp_start = date('Y', time()) - intval($age_start);
		        $tmp_end = date('Y', time()) - intval($age_end);
		        if ($age_start > $age_end) {
		            $condition[] =  " m.birthyear >= '" . $tmp_start. "'";
		            $condition[] =  " m.birthyear <= '" . $tmp_end . "'";
		        } else {
		            $condition[] = " m.birthyear >= '" . $tmp_end . "'";
		            $condition[] = " m.birthyear <= '" . $tmp_start . "'";
		        }
		    } else if ($age_start > 0) {
		        $tmp_start = date('Y', time()) - intval($age_start);
		        $condition[] = " m.birthyear <= '" . $tmp_start . "'";
		    } else if ($age_end > 0) {
		        $tmp_end = date('Y', time()) - intval($age_end);
		        $condition[] = " m.birthyear >= '" . $tmp_end . "'";
		    }
		}
	}
	if(!empty($salary)){
		$condition[] = "m.salary >= 3";
		$condition[] = "m.salary <= 100";
	}
	
	
	
	//当前在线1,2为一天，3为一周内，4为一周外
	if(isset($_GET['online'])){
		$online = MooGetGPC('online','string','G');
		switch($online){
			case 1:
				$time = time()-100;
				$condition[] = " ma.real_lastvisit>={$time}";
			break;
			case 2:
				$time = time()-24*3600;
				$condition[] = " ma.real_lastvisit>={$time}";
			break;
			case 3:
				$time1 = time()- 7*24*3600;
				$time2 = time() - 24*3600;
				$condition[] = " ma.real_lastvisit >= {$time1}";  
				$condition[] = " ma.real_lastvisit <= {$time2}";
			break;
			case 4:
				$time = time()- 7*24*3600;
				$condition[] = " ma.real_lastvisit <= {$time}";
			break;	
		}

			
	}
	
    $where = implode(' AND ',$condition);
	$query_builder = get_query_builder($where, $allow_order, '', '', 'regdate', 'desc', $rsort);
	//print_r($query_builder);
	//$where = $sql_where.$query_builder['where'];
	$where = $sql_where.$where;
	//echo '<br><br>'.$where;
	$sql_sort = $query_builder['sort'];
	$sort_arr = $query_builder['sort_arr'];
	$rsort_arr = $query_builder['rsort_arr'];
	
	/*$sql = "SELECT count(*) AS c FROM {$GLOBALS['dbTablePre']}members m 
			LEFT JOIN {$GLOBALS['dbTablePre']}member_admininfo a
			ON m.uid=a.uid
			$where ";
	$members_total = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	$total = $members_total['c'];
	*/
	
	$age_arr = array();
    for($i=18;$i<100;$i++){
    	$age_arr[] = $i;	
    }
    $total = 0;
	$page_per = 20;
	$limit = 20;
	$link = '';
	if(isset($_GET['page_per'])){
		$page_per = MooGetGPC('page_per','integer','G');
 		$limit = $page_per;
 		$link = "&page_per=$page_per";
	}
    $page = get_page();
    $offset = ($page-1)*$limit;
    
    $condition = array();
    $condition['uid']=$condition['username']=$condition['nickname']=$condition['telphone']=$condition['sid'] = '';
	 //获取本次查询条件
	$sp_condition = get_condition($where);

	$member_list = array();
	
    if(strpos($where, 'ma.real_lastvisit')<0){
		$limits = array($offset, $page_per);
	    $sp_result = sphinx_search($sp_condition, $limits, $sql_sort);
	    $total = $sp_result['total'];
	    if($total > 0)
	    	$member_list = $sp_result['member_list'];
	} 
   
	$condition = array_merge($condition, $sp_condition);
	
	if($total == 0 && !isset($sp_result['member_list'])){
		$where = str_replace("AND  m.gender='3'", '', $where);
		$member_list = get_member_list($where, $sql_sort, "limit {$offset},{$page_per}");
		$total = get_allmember_count($where);
	}
	
	$page_total = max(1, ceil($total/$limit));
	$page = min($page, $page_total);
	/*$sql = "SELECT m.*,a.is_delete,a.real_lastvisit FROM {$GLOBALS['dbTablePre']}members m 
			LEFT JOIN {$GLOBALS['dbTablePre']}member_admininfo a
			ON m.uid=a.uid
			$where {$sql_sort} LIMIT {$offset},{$limit}";
	
	$member_list = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);*/
	
	//来源
	if($member_list) {
		foreach($member_list as $key => $user) {
			if(preg_match_all("/(wf=\w+)&?|(st=\w+)&?/i", $user['source'], $matches)) {
				$member_list[$key]['source'] = $matches[1][0]."<br />".$matches[2][1];
			}
		}
	}
	/*$tel=get_telphone($members);
	//echo $tel;
	if($members){
		foreach($members as $user){
			$user['num']=$tel[$user['telphone']];
			$member_list[]=$user;
		}
		//当点击同号手机数时排序
		if($_GET['order_tel']){
			foreach ($member_list as $key => $row) {
				$edition[$key] = $row['num'];
			}
			array_multisort($edition, SORT_DESC, $member_list);//二维数组排序
		}
	}*/
	//echo "<span style='display:none;'>$sql</span>";
	$currenturl = "index.php?action=allmember&h=goodnoallot_members".$link;
	$currenturl1 = "index.php?action=allmember&h=goodnoallot_members&clear=1";

	$url2="&keyword={$keyword}&choose={$choose}&gender={$gender}&province={$province}&islock={$islock}&birthyear={$birthyear}&salary={$salary}&age_start={$age_start}&age_end={$age_end}&online={$_GET[online]}";
	//echo '<br><br><br>'.$currenturl;
	$currenturl2=$currenturl.$url2;
	$page_links = multipage( $total, $page_per, $page, $currenturl2);
    $kefu_list = get_kefulist();
	
	//获取本次查询条件
	//$condition = get_condition($where);
	//echo "<br><br>";
	//print_r($condition);
	$title = '未分配优质会员列表';
	require_once(adminTemplate('allmember_general'));
}
//可以二次回收的会员
function allmember_can_recovery(){
	global $allow_order, $rsort;	
 	
    $sql_where = "WHERE m.usertype=1 AND m.is_well_user=1 AND m.s_cid=40";
    
	$condition[] = '';

	$keyword = trim(MooGetGPC('keyword','string','G'));
    $choose = MooGetGPC('choose','string','G');
    $gender = MooGetGPC('gender','integer','G');
    $province = MooGetGPC('province','integer','G');
	$city = MooGetGPC('city','integer','G');
    $islock = MooGetGPC('islock','integer','G');
	// $birthyear = MooGetGPC('birthyear','string','G');
	$salary = MooGetGPC('salary','string','G');
	// $age_start=MooGetGPC('age_start','integer','G');
	// $age_end=MooGetGPC('age_end','integer','G');
    
    if(!empty($choose) && !empty($keyword)){
    	$condition[] = " m.$choose like '%$keyword%'";	
    }
    if($gender == 2 || $gender == 1){
    	if($gender == 1) $condition[] = " m.gender='1'";	
    	else $condition[] = " m.gender='0'";
    }elseif($gender == 3){
    	$condition[] = " m.gender='3'";
    }
    
    if(!empty($province)){
    	$condition[] = " m.province='$province'";
    }
	
	
	if(!empty($city)){
    	$condition[] = " m.city='$city'";
    }
	
    if(!empty($islock)){
    	if($islock == 1) $condition[] = " m.is_lock='1'";	
    	else $condition[] = " m.is_lock='0'";
    }
	
	$age_start=25;$age_end=35;
  	// if(!empty($birthyear)){
		$max_age = date('Y')-$age_end;
		$min_age = date('Y')-$age_start;
		//$condition[] = "m.birthyear between $max_age and $min_age";
		$condition[] = "m.birthyear >= {$max_age}";
		$condition[] = "m.birthyear <= {$min_age}";
	// }
	if(!empty($salary)){
		$condition[] = "m.salary >= 3";
		$condition[] = "m.salary <= 100";
	}
	//当前在线1,2为一天，3为一周内，4为一周外
	if(isset($_GET['online'])){
		$online = MooGetGPC('online','string','G');
		switch($online){
			case 1:
				$time = time()-100;
				$t=time();
				$condition[] = " ma.real_lastvisit>={$time}";
				$condition[] = " ma.real_lastvisit<={$t}";
			break;
			case 2:
				$time = time()-24*3600;
				$t = time()-100;
				$condition[] = " ma.real_lastvisit>={$time}";
				$condition[]=  " ma.real_lastvisit<={$t}";
			break;
			case 3:
				$time1 = time()- 7*24*3600;
				$time2 = time() - 24*3600;
				$condition[] = " ma.real_lastvisit >= {$time1}";  
				$condition[] = " ma.real_lastvisit <= {$time2}";
			break;
			case 4:
				$time = time()- 7*24*3600;
				$condition[] = " ma.real_lastvisit <= {$time}";
				$condition[] = " ma.real_lastvisit >= 0";
			break;	
		}

			
	}
	

	
    $where = implode(' AND ',$condition);
	$query_builder = get_query_builder($where, $allow_order, '', '', 'regdate', 'desc', $rsort);
	
	$where = $sql_where.$where;
	//echo '<br><br>'.$where;
	$sql_sort = $query_builder['sort'];
	$sort_arr = $query_builder['sort_arr'];
	$rsort_arr = $query_builder['rsort_arr'];
	
	/*$sql = "SELECT count(*) AS c FROM {$GLOBALS['dbTablePre']}members_search m 
			LEFT JOIN {$GLOBALS['dbTablePre']}member_admininfo a
			ON m.uid=a.uid
			$where ";
	$members_total = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	$total = $members_total['c'];*/
	
	$page_per = 20;
	$limit = 20;
	$link = '';
	if(isset($_GET['page_per'])){
		$page_per = MooGetGPC('page_per','integer','G');
 		$limit = $page_per;
 		$link = "&page_per=$page_per";
	}

    $page = get_page();
    $offset = ($page-1)*$limit;
    
	$total = 0;
	$condition = array();
    $condition['uid']=$condition['username']=$condition['nickname']=$condition['telphone'] = '';
    //获取本次查询条件
	
	
	$sp_condition = get_condition($where);

	$member_list = array();
	if(strpos('ma.real_lastvisit', $where) === false){
		$sp_condition['sid'] = '52 | 123';
		$limits = array($offset, $page_per);
	    $sp_result = sphinx_search($sp_condition, $limits, $sql_sort);
	    $total = $sp_result['total'];
	    if($total > 0)
	    	$member_list = $sp_result['member_list'];
	}	

	$condition = array_merge($condition, $sp_condition);
	if($total == 0 && !isset($sp_result['member_list'])){
		$where = str_replace("AND  m.gender='3'", '', $where);
		$member_list = get_member_list($where. ' AND m.sid in (52,123)', $sql_sort, "limit {$offset},{$page_per}");
		$total = get_allmember_count($where. ' AND m.sid in (52,123)');
	}
	
	$page_total = max(1, ceil($total/$limit));
	$page = min($page, $page_total);
   
	$age_arr = array();
    for($i=18;$i<100;$i++){
    	$age_arr[] = $i;	
    }
    
    $condition['sid'] = '';
	/*$sql = "SELECT m.*,a.is_delete,a.real_lastvisit FROM {$GLOBALS['dbTablePre']}members_search m 
			LEFT JOIN {$GLOBALS['dbTablePre']}member_admininfo a
			ON m.uid=a.uid
			$where {$sql_sort} LIMIT {$offset},{$limit}";
	
	$member_list = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);*/
	
	//来源
	if($member_list) {
		foreach($member_list as $key => $user) {
			if(preg_match_all("/(wf=\w+)&?|(st=\w+)&?/i", $user['source'], $matches)) {
				$member_list[$key]['source'] = $matches[1][0]."<br />".$matches[2][1];
			}
		}
	}
	
	//echo "<span style='display:none;'>$sql</span>";
	 $currenturl = "index.php?action=allmember&h=can_recovery".$link;
	$currenturl1 = "index.php?action=allmember&h=can_recovery&clear=1";
	//echo '<br><br><br>'.$currenturl;
	
	$url2="&keyword={$keyword}&choose={$choose}&gender={$gender}&province={$province}&islock={$islock}&birthyear={$birthyear}&salary={$salary}&age_start={$age_start}&age_end={$age_end}&online={$_GET[online]}";
	//echo '<br><br><br>'.$currenturl;
	$currenturl2=$currenturl.$url2;
 
	$page_links = multipage( $total, $page_per, $page, $currenturl2 );
	
	$kefu_list = get_kefulist();
	
	//获取本次查询条件
	//$condition = get_condition($where);
	//echo "<br><br>";
	//print_r($condition);
	$title = '可再分配优质会员';
	require_once(adminTemplate('allmember_general'));
}



//联系不上的会员
function allmember_uncontact(){
    global $allow_order, $rsort;    
    
    $condition = '';

    $keyword = trim(MooGetGPC('keyword','string','G'));
    $choose = MooGetGPC('choose','string','G');

    if(!empty($choose) && !empty($keyword)){
        $condition = " m.$choose like '%$keyword%'";  
    }
   
    if(isset($_POST['submit'])){
    	$id=$_POST['changesid'];
		$str_arr = array();
        foreach($id as $uid){
        	$sql="select mid,comment from {$GLOBALS['dbTablePre']}member_backinfo where uid='{$uid}' and  effect_grade=11 and effect_contact='2'";
        	$result=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
        	$now = time();
        	$GLOBALS['_MooClass']['MooMySQL']->query("update web_members_search set sid={$result['mid']} where uid={$uid}");
        	isset($result['mid']) && $result['mid'] && $str_arr[$uid] = array((int)$result['mid']);
        	$comment=$result['comment']."  【已处理】";
        	$GLOBALS['_MooClass']['MooMySQL']->query("update web_member_backinfo set comment='{$comment}' where uid={$uid} and effect_contact=2") ;	
        }

        !empty($str_arr) && $rs=searchApi("members_man members_women") -> updateAttr(array('sid'),$str_arr);
    }
    
    $sql_where = " WHERE b.effect_grade =11 and m.sid=389 ";
    $where = '';
    if($condition){
      $where=" and $condition";
    }
    
    $query_builder = get_query_builder($where, $allow_order, '', '', 'regdate', 'desc', $rsort);
    
    $where = $sql_where.$where;

    $sql_sort = $query_builder['sort'];
    $sort_arr = $query_builder['sort_arr'];
    $rsort_arr = $query_builder['rsort_arr'];
    
    $sql = "SELECT count(id) AS c FROM {$GLOBALS['dbTablePre']}members_search m 
            LEFT JOIN {$GLOBALS['dbTablePre']}member_backinfo b
            ON m.uid=b.uid
            $where ";
    $members_total = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
    $total = $members_total['c'];
    
    $page_per = 12;
    $limit = 12;
    $link = '';
    if(isset($_GET['page_per'])){
        $page_per = intval(MooGetGPC('page_per','integer','G'));
        $limit = $page_per;
        $link = "&page_per=$page_per";
    }
    $page = get_page();
    $page_total = max(1, ceil($total/$limit));
    $page = min($page, $page_total);
    $offset = ($page-1)*$limit;
    
    $sql = "SELECT m.* FROM {$GLOBALS['dbTablePre']}members_search m 
            LEFT JOIN {$GLOBALS['dbTablePre']}members_search mb on m.uid=mb.uid LEFT JOIN {$GLOBALS['dbTablePre']}member_backinfo b
            ON m.uid=b.uid
            $where {$sql_sort} LIMIT {$offset},{$limit}";
    
    $member_list = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
  
   
    $currenturl = "index.php?action=allmember&h=uncontact".$link;
  
    $page_links = multipage( $total, $page_per, $page, $currenturl );
   
    
  
    $title = '联系不上的会员';
    require_once(adminTemplate('allmember_uncontact'));
}



//查看用户资料
function allmember_view_info(){
   
	global $grade;
	global $sell_after_grade,$reprating;
//	global $slave_dbHost, $slave_dbUser, $slave_dbPasswd, $slave_dbName, $slave_dbPconnect, $slave_dbCharset;
	$slave_mysql =  $GLOBALS['_MooClass']['MooMySQL']; 
//	$slave_mysql->connect($slave_dbHost, $slave_dbUser, $slave_dbPasswd, $slave_dbName, $slave_dbPconnect, $slave_dbCharset);
	$adminright='';
	$uid = MooGetGPC('uid','integer','G');

	 
	 
	//显示聊天历史记录
	$chathistory = MooGetGPC('type','string','G');
	if($chathistory){
		allmember_chathistory();
		exit;
	}
	
	$member_admininfo=array();

	$re = $slave_mysql->getOne("SELECT groupid,member_count,allot_member,allot_time FROM {$GLOBALS['dbTablePre']}admin_user WHERE uid='{$GLOBALS['adminid']}'",true);
	if(in_array($re['groupid'],array(70,76,77))) $salesAfter=1;
	if($re['groupid']=='67'){//售前客服看不到本站注册的高级会员
		$sql = "SELECT s_cid,usertype FROM {$GLOBALS['dbTablePre']}members_search WHERE uid={$uid}";
		$user_type = $slave_mysql->getOne($sql,true);
		if($user_type['s_cid'] ==40||$user_type['usertype']!=1){
			$sql = "SELECT * FROM {$GLOBALS['dbTablePre']}members_search WHERE uid={$uid}";
			$member = $slave_mysql->getOne($sql,true);
		}else{ salert('你没有权限查看此会员！');exit;}
	}else{
	    $sql = "SELECT * FROM {$GLOBALS['dbTablePre']}members_search WHERE uid={$uid}";
	    $member = $slave_mysql->getOne($sql,true);
	}
    
    //售后普通客服不能查看售前4、5类会员
    /* if(in_array($re['groupid'],$GLOBALS['general_service_sale'])){
        $sql = "select effect_grade from {$GLOBALS['dbTablePre']}member_admininfo where uid='{$uid}'";
        $res = $slave_mysql->getOne($sql,true);
        $sql_uid = "SELECT sid,usertype FROM {$GLOBALS['dbTablePre']}members_search WHERE uid='{$uid}'";
        $res_uid = $slave_mysql->getOne($sql_uid,true);
        $sid = $res_uid['sid'];
        $res_sid = $slave_mysql->getOne("SELECT groupid FROM {$GLOBALS['dbTablePre']}admin_user WHERE uid='{$sid}'",true);
        if(in_array($res['effect_grade'],array(5,6)) && $res_uid['usertype']=='1' && !in_array($res_sid['groupid'],$GLOBALS['general_service_sale'])){
            salert('你没有权限查看此会员！');exit;
        }
    } */


	

	$sql = "SELECT * FROM {$GLOBALS['dbTablePre']}members_base WHERE uid={$uid}"; // updated file
	$member2 = $slave_mysql->getOne($sql,true);

	$sql = "SELECT * FROM {$GLOBALS['dbTablePre']}members_login WHERE uid={$uid}"; // updated file
	$member3 = $slave_mysql->getOne($sql,true);
	if(!is_array($member)) $member = array();
	if(!is_array($member2)) $member2 = array();
	if(!is_array($member3)) $member3 = array();
	$member = $member2 = array_merge($member, $member2, $member3);
	

	/*
	if(!empty($member['birth'])){
    	list($y,$m,$d) = explode('-',$member['birth']);//birth modify
		$member['birthmonth'] = $m;
		$member['birthday'] = $d;
		$y=$member['birthyear'];
		$star_sx = get_signs($y, $m,$d);
    }*/

	
	//note 查询telphone归属地
	$teladdr = getphone($member['telphone']);//iconv("gb2312","UTF-8",getphone($member['telphone']));
	$teladdr2 = getphone($member['callno']);//iconv("gb2312","UTF-8",getphone($member['callno']));
	
	//$tel_count = 0;
	//此手机号注册的会员数
	//if(!empty($member['telphone'])){
	//	$sql = "SELECT COUNT(1) c FROM {$GLOBALS['dbTablePre']}members_search WHERE telphone='{$member['telphone']}' and is_lock=1";
	//	$tel_count =  $slave_mysql->getOne($sql);
	//}
	
	
	//note 获得手机号是否验证
	if($member['telphone'] != '' || $member['telphone'] != '0'){
		$sql = "SELECT telphone FROM {$GLOBALS['dbTablePre']}certification WHERE uid = '{$uid}'";		
		$row = $slave_mysql->getOne($sql);
		$tel_ifcheck = $row['telphone'];
	}
	
	//note是否可以看到联系方式
	$is_look = 0;
    if(isset($member_admininfo['effect_grade']) && in_array($member_admininfo['effect_grade'],array(6,5))&&$adminid!=$member['sid']&&$member['s_cid'] ==40&&!in_array($admingroup,$GLOBALS['admin_service_arr'])){
        $is_look='1';
    }
	
	
	//IP情况
	$sql = "SELECT mid,effect_grade, effect_contact, master_member, next_contact_time, checkreason, real_lastvisit, last_ip, finally_ip, danger_leval FROM {$GLOBALS['dbTablePre']}member_admininfo WHERE uid='{$uid}'"; // updated file
	$member_admininfo = $slave_mysql->getOne($sql);
	// var_dump($member_admininfo);
	
	//重点跟进会员（明日跟进会员）
	$sql="select uid from web_member_goon where uid='{$uid}'";
	$member_goon=$slave_mysql->getOne($sql,true);
	
	
	//可控预测会员
	$sql="select flag,isforcast from web_member_isControl where uid='{$uid}'";
	
	$member_Control=$slave_mysql->getOne($sql,true);
	

	
	MooPlugins('ipdata');
	if(isset($member_admininfo['last_ip'])) $last_ip = convertIp($member_admininfo['last_ip']);
	if(isset($member_admininfo['finally_ip'])) $finally_ip = convertIp($member_admininfo['finally_ip']);
	
	//危险等级
	if(isset($member_admininfo['danger_leval'])) $danger_leval = $member_admininfo['danger_leval'];
	
	/* $page = get_page();
	$total = getcount('member_backinfo','WHERE uid='.$uid);
    $limit = 8;
	
	$page_total = max(1, ceil($total/$limit));
	$page = min($page, $page_total);
    $offset = ($page-1)*$limit;

	$sql = "SELECT dateline, mid, manager, effect_grade, effect_contact, master_member, next_contact_time, interest, different, service_intro, next_contact_desc, comment FROM {$GLOBALS['dbTablePre']}member_backinfo WHERE uid = {$uid} ORDER BY id DESC LIMIT {$offset},{$limit}"; // updated file
	$notes = $slave_mysql->getAll($sql,0,0,0,true);	

	$currenturl = "index.php?action=allmember&h=view_info&uid={$uid}";
   	$pages = multipage( $total, $limit, $page, $currenturl ); */
    $sql = "SELECT dateline, mid, manager, effect_grade, effect_contact, master_member, next_contact_time, interest, different, service_intro, next_contact_desc, comment FROM {$GLOBALS['dbTablePre']}member_backinfo WHERE uid = {$uid} ORDER BY id DESC limit 10"; // updated file
	$notes = $slave_mysql->getAll($sql,0,0,0,true);	
	
	
	//note 查询所属九型性格
	$sql = "SELECT type FROM {$GLOBALS['dbTablePre']}enneagram WHERE uid = {$uid}"; // updated file
	$enneagram_row = $slave_mysql->getOne($sql);
		
	//note_显示相册中的普通照片
	$sql = "SELECT imgurl,pic_date,pic_name FROM {$GLOBALS['dbTablePre']}pic WHERE syscheck=1 and isimage='0' and uid='$uid'";
	$user_pic = $slave_mysql->getAll($sql);
	
	$adminid = $GLOBALS['adminid'];
	$admingroup = $GLOBALS['groupid'];


	//note 哪些人可以看到售后
	if(in_array($admingroup,$GLOBALS['admin_aftersales'])){
		$adminright = "sellpass";
	}
    
    //====参加活动的会员====
    //$activity = $slave_mysql->getOne("SELECT uid,username,regtime,channel FROM {$GLOBALS['dbTablePre']}ahtv_reguser where  uid='{$uid}' and  isattend=1");
    //全权会员提醒
    $action_msg='';
    $color='#F00';
    $action_button=false;
    if($member['usertype']==3){
    	$action_button=true;
    	$action_time=$slave_mysql->getOne('SELECT `action_time` FROM `'.$GLOBALS['dbTablePre'].'full_log` WHERE `uid`='.$uid);
    	if(!empty($action_time)){
    		if($action_time['action_time']+3888000>time()){
    			$color='#0F0';
    		    $action_msg='<span class="action_msg">可以使用，首次使用时间：'.date('Y-m-d H:i:s',$action_time['action_time']).'</span>。';
			
    		}else {
    			$action_button=false;
				$startUsed=date('Y-m-d H:i:s',$action_time['action_time']);
    			//$action_msg='此全权会员已经注册，首次使用时间：'.$startUsed.',终止时间：'.date("Y-m-d",strtotime ($startUsed."+45 day")).'距离首次登陆（访问）已<span class="action_msg">超过45天</span>';
				$action_msg='<span class="action_msg">使用已过期，首次使用时间：'.$startUsed.'</span>';
				
    		}
    	}
    }
	require_once(adminTemplate('allmember_view_info'));
}

//分配给某客服
function allmember_changeusersid(){
	
	$uid = MooGetGPC('kefuuid','integer','P');
	$uidarr = isset($_POST['changesid']) && $_POST['changesid'] ? $_POST['changesid'] : array();
	
	
	
	$uidlist = implode(',', $uidarr);
	
	if(empty($uid) || empty($uidlist)){
		salert('操作出错,请重试');exit;
	}
	//$uidlist .= ',';
	$pre_url = $_POST['pre_url'];
	//正则取得action和h参数，做为记录uid的参数
	//index.php?action=allmember&h=regnoallot_members
	preg_match('/index.php\?action=([a-zA-z1-9_]*)&h=([a-zA-z1-9_]*)/', $pre_url, $matchs);
	$remember_action = isset($matchs[1]) ? $matchs[1] : 'changeusersid';
	$remember_h = isset($matchs[2]) ? $matchs[2] : '';
	$remember_adminid = in_array($GLOBALS['groupid'],$GLOBALS['admin_service_arr']) ? '0' : $GLOBALS['adminid'];
	$remember_key = $remember_action .'_'. $remember_h .'_'.$remember_adminid;
/*
	$sql = "SELECT COUNT(*) c FROM {$GLOBALS['dbTablePre']}member_admininfo ma LEFT JOIN {$GLOBALS['dbTablePre']}members m ON ma.uid=m.uid AND m.uid='{$uid}' AND ma.effect_grade!=10";
	$kefu_have_members_total = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
*/
	$re = $GLOBALS['_MooClass']['MooMySQL']->getOne("SELECT groupid,member_count,allot_member,three_day,seven_day ,allot_time FROM {$GLOBALS['dbTablePre']}admin_user WHERE uid='{$uid}'",true);
	$time = time();
	
	
	if(in_array($re['groupid'],$GLOBALS['general_service_pre']) && $re['member_count'] >= 150){//note 普通售前客服分配最大限制数为150
       salert('此客服已经超过了最多150个会员的限制，不能再分配',$pre_url);exit;
    } 
    
    
    if(in_array($re['groupid'],$GLOBALS['admin_service_group']) && $re['member_count'] >= 1000 && $uid!='52' && $uid!='372'){//note 销售组长分配最大限制数为1000
       salert('此组长已经超过了最多1000个会员的限制，不能再分配',$pre_url);exit;
    } 
	        

	
	$generalmembers = isset($_POST['generalmembers']) ? MooGetGPC('generalmembers','string','P') : '';


	//note 限制给售后客服分配的高级、钻石会员数量
	//note 该客服的所属groupid组  售后客服限制分配给她的本站注册的高级、钻石会员不超过100
	if($generalmembers!='generalmembers'){
		if(in_array($re['groupid'],$GLOBALS['general_service_sale'])){
			//note 查找该客服下有多少本站注册的钻石、高级会员  未过期的
			/*$sql_sale = "select count(*) count from {$GLOBALS['dbTablePre']}members_search where sid='{$uid}' and s_cid in(20,30) and usertype=1 and endtime>=".$time;
			$res_sale = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql_sale);
			$sale_count = $res_sale['count'];*/
			$filter = array();
			$filter[] = array('sid',$uid);
			$filter[] = array('s_cid','20|30');
			$filter[] = array('usertype',1);
			$time = time();
			$filter[] = array('endtime', array($time, intval($time) + 3600*24*365));//这里需要区间，时间向后推荐三年
			$limits = array(0, 1);
			$sp = searchApi('members_women members_man');
			$sp->setQueryType(true);
			$sp->getResultOfReset($filter, $limits);
			$sale_count = $sp->getRs('total_found');
			echo "此客服{$uid}下本站注册的钻石、高级会员  未过期的有 ".$sale_count." 人<br>";
			//$uidlist1 = trim($uidlist,',');
			$uidarr = explode(',',$uidlist);
			$countarr = count($uidarr);
			echo "要分给他的会员数为 ".$countarr." 人<br>";

			$allcount = $countarr+$sale_count;
			echo "此客服下的会员数将为 ".$allcount." 人";
	//		echo $allcount;die;
			if($allcount>=121){
				salert('分配给此客服的本站注册的高级、钻石会员已经超过了最多120个会员的限制，不能再分配',$pre_url);exit;
			}
		}
	}

	
	$uidarr = explode(',',$uidlist);

	$len = count($uidarr);

	if(date("m-d",$re['allot_time']) != date("m-d")){
		$GLOBALS['_MooClass']['MooMySQL']->query("UPDATE {$GLOBALS['dbTablePre']}admin_user SET allot_member=0 WHERE uid='{$uid}'");
		$re['allot_member'] = 0;
	}
	
	//每天最多可给每个客服分配的会员数量(//note 售后连长 /组长/售后/客服每天分配数不作限制)
	if(in_array($re['groupid'],$GLOBALS['admin_service_manager']) || in_array($re['groupid'],$GLOBALS['admin_service_group']) || in_array($re['groupid'],$GLOBALS['admin_aftersales_service']) || in_array($re['groupid'],$GLOBALS['admin_aftersales_service2'])){ 
		$today_total_allot = 99999;
	}elseif(in_array($GLOBALS['groupid'],$GLOBALS['admin_service_arr'])||in_array($GLOBALS['groupid'],$GLOBALS['admin_service_team'])){ //客服主管分会员时不要限制
		$today_total_allot = 99999;
	}elseif(in_array($GLOBALS['groupid'],$GLOBALS['admin_service_manager'])){
	    $today_total_allot = 500;
	}elseif(in_array($re['groupid'],$GLOBALS['admin_resource_manager'])){
	    $today_total_allot = 500;
	}else{
		$today_total_allot = 200;
	}
	
	//今天已经分配给此客服的会员总数---> 30-已分配数-当前正分配数
	$meb = $today_total_allot - $re['allot_member'] - $len;
	
	if($meb>=0){
		$i = 0;
		//note 分配来源,如果从客服删除会员列表不重复会员数-1
		//$reduce = preg_match("/&h=giveup/",$_SERVER['HTTP_REFERER']);
		//print_r($uidarr);
		echo "<pre>";
		$GLOBALS['_MooClass']['MooMySQL']->query('begin'); 
		foreach($uidarr as $v){
		
		    //已分配会员重新分配给其它客服时，原客服分配总数减去
			
			$sid_arr=array();
			$sql="SELECT sid,s_cid from {$GLOBALS['dbTablePre']}members_search where uid='$v'";
			$sid_arr=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
			$sid=$sid_arr['sid'];
			$s_cid=$sid_arr['s_cid'];
			
			if($uid==$sid) continue;//已经被分配过了给这个客服
		    //售前组长和大区不能对售后会员进行分配 主要针对高级搜索的bug处理
			//echo $GLOBALS['username'];exit;
			
			//if(!preg_match("/^[Vv](\.)*/",$GLOBALS['username']) && $s_cid<40){
            //   salert('您不能分配VIP会员！');exit;
		    //   continue;
			//}
			
			
			//查询原客服 member_count  status
			$sql = "SELECT member_count FROM {$GLOBALS['dbTablePre']}admin_user WHERE uid = '{$sid}'";		
		    $uid_info = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
		    
			
			if( !empty($sid) && $uid_info['member_count']>0){//有问题  原先客服的会员总数减1
					$sql = "UPDATE {$GLOBALS['dbTablePre']}admin_user SET member_count=member_count-1 WHERE uid='$sid'";
					$GLOBALS['_MooClass']['MooMySQL']->query($sql);
					
				
			}
			
			//更新members表sid
			$now = time(); //分配会员更新updatetime
			$sql = "UPDATE {$GLOBALS['dbTablePre']}members_search SET sid='$uid'  WHERE uid='{$v}'";
			$a = $GLOBALS['_MooClass']['MooMySQL']->query($sql);
			searchApi('members_man members_women')->updateAttr(array('sid'),array($v=>array($uid)));
			sphinx_remember_uids($remember_key, $v);//记录更新id
			$sql = "UPDATE {$GLOBALS['dbTablePre']}members_base SET allotdate='{$time}' WHERE uid='{$v}'";
			$a = $GLOBALS['_MooClass']['MooMySQL']->query($sql);
			if(MOOPHP_ALLOW_FASTDB){
				$value1 = $value2 = array(); 
				$value1['sid']=$uid;
				$value1['updatetime']=$now;
				$value2['allotdate']=$time;
				MooFastdbUpdate('members_search','uid',$v,$value1);
				MooFastdbUpdate('members_base','uid',$v,$value2);
			}

			//插入分配记录表
			$sql = "INSERT INTO {$GLOBALS['dbTablePre']}allotuser (uid,sid,allot_sid,allot_con,allot_time) 
					VALUES('{$v}','{$GLOBALS['adminid']}','{$uid}','正常分配给{$uid}号客服','{$time}')";
					
			$GLOBALS['_MooClass']['MooMySQL']->query($sql);

			//判断member_admininfo表中是否有此会员
			$sql = "SELECT uid FROM {$GLOBALS['dbTablePre']}member_admininfo WHERE uid='{$v}'";
			$temp = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
			if( empty($temp) ){
				$sql = "INSERT INTO {$GLOBALS['dbTablePre']}member_admininfo SET uid='{$v}',effect_grade=1,allto_time='{$time}'";
			}else{//更改为新分客户
				$sql = "UPDATE {$GLOBALS['dbTablePre']}member_admininfo SET effect_grade=1,allto_time='{$time}' WHERE uid='{$v}'";
			}
			$GLOBALS['_MooClass']['MooMySQL']->query($sql);
			unset($temp);
			
			//更新member_sellinfo表的sid
			//判断member_sellinfo表中是否有此会员的信息
			$sqlsell = "SELECT uid FROM {$GLOBALS['dbTablePre']}member_sellinfo WHERE uid='{$v}'";
			$tempsell = $GLOBALS['_MooClass']['MooMySQL']->getAll($sqlsell,0,0,0,true);
			if(!empty($tempsell)){
				$sql_sellinfo = "UPDATE {$GLOBALS['dbTablePre']}member_sellinfo SET sid='{$uid}' WHERE uid='{$v}'";
				$GLOBALS['_MooClass']['MooMySQL']->query($sql_sellinfo);
			}
			unset($tempsell);

			//写日志
			serverlog(3,$GLOBALS['dbTablePre'].'members',"{$GLOBALS['username']}客服将{$v}会员分配给{$uid}号客服",$GLOBALS['adminid'],$v);

			$i++;
			//note 普通售前客服分配最大限制数为300
			$member_count = $re['member_count'] + $i;
			
		}
		$GLOBALS['_MooClass']['MooMySQL']->query('commit');
		
		//更新客服当前分配数及分配总数
		/*$sql1="SELECT count(*) as num from {$GLOBALS['dbTablePre']}members_search where sid='{$uid}'";
		$new_member_count=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql1);*/
		$filter = array();
		$filter[] = array('sid',$uid);
		$limits = array(0, 1);
		$sp = searchApi('members_women members_man');
		$sp->setQueryType(false);
		$result=$sp->getResultOfReset($filter, $limits);

		$new_member_count = $sp->getRs('total_found');
		$sql = "UPDATE {$GLOBALS['dbTablePre']}admin_user SET allot_member=allot_member+{$i},member_count='{$new_member_count}',allot_time= '{$time}' WHERE uid='{$uid}'";
		$GLOBALS['_MooClass']['MooMySQL']->query($sql);
	}else{
		salert('已超过今天可分配最大数量100个');exit;
	}
	$current_total_allo = $re['allot_member'] + $i;
	
	
	if($time>=strtotime('2011-05-01')){
	    $msg = $i < $len ? ',达到240上限'.($len - $i).'个未分配。' : '' ;
	}else{
	    $msg = $i < $len ? ',达到300上限'.($len - $i).'个未分配。' : '' ;
	}
	salert("分配成功,今天已分配给此客服会员总数{$current_total_allo}".$msg, $pre_url);exit;
}
//note 删除会员到不可回收站
function  allmember_deletemember(){
	//note 删除的uid
	$uidlist = MooGetGPC('uidlist','string','G');

	$pre_url = $_GET['pre_url'];
	
	if(empty($uidlist)){
		salert('操作出错,请重试',$pre_url);exit;
	}
	
	$uidlist1 = trim($uidlist,',');
	$uidarr = explode(',',$uidlist1);
	$now = time();
	if(!empty($uidarr)){
		$str_arr = array();
		foreach($uidarr as $v){
			$sql = "update {$GLOBALS['dbTablePre']}member_admininfo set is_delete = '2' where uid = {$v}";
			$GLOBALS['_MooClass']['MooMySQL']->query($sql);
			$sql = "update {$GLOBALS['dbTablePre']}members_search set sid = '123'  where uid = {$v}";
			$str_arr[$v] = array(123);
			$GLOBALS['_MooClass']['MooMySQL']->query($sql);
		}
		!empty($str_arr) && searchApi("members_man members_women")->updateAttr(array('sid'),$str_arr);
	}
	
	salert('已删除成功！', $pre_url);exit;
}

//编辑用户资料
function allmember_edit_info(){
    global $fastdb;
	if($_POST){
		$uid = MooGetGPC('uid','integer','P');
		if(empty($uid)){
			salert('参数不正确');exit;
		}
		
		
		$validation = MooAutoLoad('MooValidation');
		
		//search
		$search1=array("nickname"=>"昵称","username"=>"Email","truename"=>"姓名","telphone"=>"电话","marriage"=>"婚姻","height"=>"身高","salary"=>"收入","education"=>"学历","children"=>"小孩","house"=>"住房","province"=>"工作身份","city"=>"工作城市","hometownProvince"=>"籍贯省份","hometownCity"=>"籍贯城市","corptype"=>"公司类别","is_lock"=>"封锁","city_star"=>"城市之星","usertype"=>"会员类型","s_cid"=>"会员级别","gender"=>"性别","occupation"=>"职业","nation"=>"民族","smoking"=>"吸烟","drinking"=>"喝酒","body"=>"体形","weight"=>"体重","nature"=>"性格","family"=>"兄弟姐妹");
	    $search2=array( array("marriage"=>array("0"=>"不限","1"=>"未婚","3"=>"离异","4"=>"丧偶")),
						array("salary"=>array("0"=>"不限","1"=>"1000元以下","2"=>"1001-2000元","3"=>"2001-3000元","4"=>"3001-5000元","5"=>"5001-8000元","6"=>"8001-10000元","7"=>"10001-20000元","8"=>"20001-50000元","9"=>"50000元以上")),
						array("education"=>array("0"=>"不限","3"=>"高中及以下","4"=>"大专","5"=>"大学本科","6"=>"硕士","7"=>"博士")),
						array("children"=>array("0"=>"不限","1"=>"没有","3"=>"有，我们住在一起","4"=>"有，我们偶尔一起住","5"=>"有，但不在身边","2"=>"保密")),		
						array("house"=>array("0"=>"不限","1"=>"和父母家人同住","2"=>"自有物业","3"=>"租房","4"=>"婚后有房","5"=>"保密")),
						array("province"=>array("0"=>"不限","10102000"=>"北京","10103000"=>"上海","10101201"=>"深圳","10101002"=>"广州","10101000"=>"广东","10104000"=>"天津","10105000"=>"重庆","10106000"=>"安徽","10107000"=>"福建","10108000"=>"甘肃","10109000"=>"广西","10110000"=>"贵州","10111000"=>"海南","10112000"=>"河北","10113000"=>"河南","10114000"=>"黑龙江","10115000"=>"湖北","10116000"=>"湖南","10117000"=>"吉林","10118000"=>"江苏","10119000"=>"江西","10120000"=>"辽宁","10121000"=>"内蒙古","10122000"=>"宁夏","10123000"=>"青海","10124000"=>"山东","10125000"=>"山西","10126000"=>"陕西","10127000"=>"四川","10128000"=>"西藏","10129000"=>"新疆","10130000"=>"云南","10131000"=>"浙江","10132000"=>"澳门","10133000"=>"香港","10134000"=>"台湾","2"=>"国外")),
                        array("hometownProvince"=>array("0"=>"不限","10102000"=>"北京","10103000"=>"上海","10101201"=>"深圳","10101002"=>"广州","10101000"=>"广东","10104000"=>"天津","10105000"=>"重庆","10106000"=>"安徽","10107000"=>"福建","10108000"=>"甘肃","10109000"=>"广西","10110000"=>"贵州","10111000"=>"海南","10112000"=>"河北","10113000"=>"河南","10114000"=>"黑龙江","10115000"=>"湖北","10116000"=>"湖南","10117000"=>"吉林","10118000"=>"江苏","10119000"=>"江西","10120000"=>"辽宁","10121000"=>"内蒙古","10122000"=>"宁夏","10123000"=>"青海","10124000"=>"山东","10125000"=>"山西","10126000"=>"陕西","10127000"=>"四川","10128000"=>"西藏","10129000"=>"新疆","10130000"=>"云南","10131000"=>"浙江","10132000"=>"澳门","10133000"=>"香港","10134000"=>"台湾","2"=>"国外")),
						array("city"=>array("0"=>"不限","10101201"=>"深圳","10101002"=>"广州","10101003"=>"佛山","10101004"=>"湛江","10101005"=>"珠海","10101006"=>"肇庆","10101007"=>"东莞","10101008"=>"惠州","10101011"=>"中山","10101012"=>"茂名","10101013"=>"汕头","10101014"=>"梅州","10101015"=>"韶关","10101016"=>"江门","10101018"=>"清远","10101020"=>"潮州","10101022"=>"阳江","10101023"=>"河源","10101026"=>"揭阳","10101028"=>"汕尾","10101068"=>"云浮","10105001"=>"重庆","10106001"=>"合肥","10106002"=>"淮南","10106003"=>"蚌埠","10106004"=>"宿州","10106005"=>"阜阳","10106006"=>"六安","10106007"=>"巢湖","10106008"=>"滁州","10106009"=>"芜湖","10106011"=>"安庆","10106012"=>"黄山","10106013"=>"铜陵","10106027"=>"贵池","10106042"=>"淮北","10106030"=>"桐城","10106038"=>"明光","10106075"=>"马鞍山","10106078"=>"天长","10106079"=>"池州","10106080"=>"宣城","10106099"=>"亳州","10107001"=>"福州","10107002"=>"厦门","10107010"=>"莆田","10107003"=>"泉州","10107013"=>"宁德","10107004"=>"南平","10107007"=>"漳州","10107008"=>"龙岩","10107009"=>"三明","10108001"=>"兰州","10108002"=>"张掖","10108003"=>"武威","10108004"=>"酒泉","10108006"=>"金昌","10108022"=>"临夏","10108007"=>"天水","10108008"=>"定西","10108012"=>"甘南","10108009"=>"平凉","10108023"=>"嘉峪关","10108064"=>"庆阳","10108078"=>"白银","10108084"=>"陇南","10109001"=>"南宁","10109002"=>"柳州","10109003"=>"钦州","10109004"=>"百色","10109005"=>"玉林","10109006"=>"防城港","10109007"=>"桂林","10109008"=>"梧州","10109083"=>"崇左","10109009"=>"河池","10109010"=>"北海","10109014"=>"贵港","10109018"=>"来宾","10109089"=>"贺州","10110001"=>"贵阳","10110002"=>"六盘水","10110004"=>"凯里","10110005"=>"都匀","10110006"=>"安顺","10110007"=>"遵义","10110021"=>"毕节","10110026"=>"兴义","10110055"=>"铜仁","10110082"=>"黔西南","10110083"=>"黔东南","10110084"=>"黔南","10111001"=>"海口","10111002"=>"三亚","10111006"=>"文昌","10111007"=>"琼海","10111020"=>"儋州","10111021"=>"五指山","10112001"=>"石家庄","10112002"=>"衡水","10112003"=>"邢台","10112004"=>"邯郸","10112005"=>"沧州","10112006"=>"唐山","10112007"=>"廊坊","10112008"=>"秦皇岛","10112009"=>"承德","10112010"=>"保定","10112011"=>"张家口","10113001"=>"郑州","10113002"=>"新乡","10113003"=>"安阳","10113004"=>"许昌","10113005"=>"驻马店","10113006"=>"漯河","10113007"=>"信阳","10113008"=>"周口","10113009"=>"洛阳","10113099"=>"济源","10113010"=>"平顶山","10113011"=>"三门峡","10113012"=>"南阳","10113013"=>"开封","10113014"=>"商丘","10113015"=>"鹤壁","10113016"=>"濮阳","10113017"=>"焦作","10114001"=>"哈尔滨","10114002"=>"绥化","10114053"=>"黑河","10114003"=>"佳木斯","10114004"=>"牡丹江","10114005"=>"齐齐哈尔","10114007"=>"大庆","10114067"=>"七台河","10114008"=>"大兴安岭","10114009"=>"鸡西","10114042"=>"伊春","10114020"=>"鹤岗","10114024"=>"双鸭山","10115001"=>"武汉","10115002"=>"黄石","10115004"=>"鄂州","10115005"=>"襄樊","10115006"=>"咸宁","10115007"=>"十堰","10115008"=>"宜昌","10115009"=>"恩施","10115010"=>"荆州","10115011"=>"黄冈","10115012"=>"荆门","10115013"=>"孝感","10115016"=>"神农架林区","10115034"=>"天门","10115062"=>"随州","10115066"=>"仙桃","10116001"=>"长沙","10116002"=>"株洲","10116003"=>"益阳","10116004"=>"岳阳","10116005"=>"常德","10116007"=>"娄底","10116008"=>"怀化","10116009"=>"衡阳","10116010"=>"邵阳","10116011"=>"郴州","10116013"=>"张家界","10116014"=>"湘潭","10116075"=>"永州","10116097"=>"湘西","10117001"=>"长春","10117002"=>"吉林","10117003"=>"延吉","10117042"=>"长白","10117004"=>"通化","10117006"=>"四平","10117007"=>"白城","10117008"=>"松原","10117028"=>"辽源","10117049"=>"延边","10118001"=>"南京","10118002"=>"苏州","10118003"=>"无锡","10118004"=>"徐州","10118005"=>"常州","10118006"=>"镇江","10118051"=>"泰州","10118007"=>"连云港","10118008"=>"淮安","10118009"=>"盐城","10118010"=>"扬州","10118011"=>"南通","10118017"=>"常熟","10118063"=>"宿迁","10119001"=>"南昌","10119002"=>"九江","10119003"=>"景德镇","10119004"=>"上饶","10119005"=>"鹰潭","10119006"=>"宜春","10119007"=>"萍乡","10119008"=>"赣州","10119009"=>"吉安","10119010"=>"抚州","10119040"=>"新余","10119042"=>"井岗山","10120001"=>"沈阳","10120002"=>"铁岭","10120003"=>"抚顺","10120004"=>"鞍山","10120005"=>"营口","10120006"=>"大连","10120007"=>"本溪","10120008"=>"丹东","10120009"=>"锦州","10120010"=>"朝阳","10120011"=>"阜新","10120012"=>"盘锦","10120013"=>"辽阳","10120014"=>"葫芦岛","10121001"=>"呼和浩特","10121002"=>"集宁","10121003"=>"包头","10121004"=>"临河","10121005"=>"乌海","10121007"=>"海拉尔","10121008"=>"赤峰","10121009"=>"锡林浩特","10121011"=>"通辽","10121024"=>"乌兰浩特","10121089"=>"锡林郭勒盟","10121090"=>"阿拉善盟","10121091"=>"兴安","10121092"=>"鄂尔多斯","10121093"=>"呼伦贝尔","10121094"=>"巴彦淖尔","10121095"=>"乌兰察布","10122001"=>"银川","10122002"=>"石嘴山","10122003"=>"固原","10122010"=>"吴忠","10122011"=>"中卫","10123001"=>"西宁","10123002"=>"果洛","10123003"=>"玉树","10123004"=>"格尔木","10123005"=>"海西","10123045"=>"海东","10123046"=>"海北","10123047"=>"黄南","10123048"=>"海南藏族自治州","10124001"=>"青岛","10124002"=>"威海","10124003"=>"济南","10124004"=>"淄博","10124005"=>"聊城","10124006"=>"德州","10124007"=>"东营","10124008"=>"潍坊","10124009"=>"烟台","10124011"=>"泰安","10124012"=>"菏泽","10124013"=>"临沂","10124014"=>"枣庄","10124015"=>"济宁","10124016"=>"日照","10124068"=>"莱芜","10124018"=>"滨州","10125001"=>"太原","10125003"=>"忻州","10125005"=>"大同","10125006"=>"临汾","10125008"=>"运城","10125009"=>"阳泉","10125010"=>"长治","10125011"=>"晋城","10125107"=>"晋中","10125108"=>"吕梁","10126001"=>"西安","10126002"=>"渭南","10126003"=>"延安","10126005"=>"榆林","10126006"=>"宝鸡","10126007"=>"安康","10126008"=>"汉中","10126010"=>"铜川","10126011"=>"咸阳","10127001"=>"成都","10127125"=>"巴中","10127002"=>"乐山","10127003"=>"凉山","10127005"=>"绵阳","10127007"=>"阿坝","10127008"=>"雅安","10127009"=>"甘孜","10127010"=>"广元","10127117"=>"遂宁","10127011"=>"南充","10127013"=>"内江","10127014"=>"自贡","10127015"=>"宜宾","10127016"=>"泸州","10127017"=>"攀枝花","10127018"=>"德阳","10127056"=>"眉山","10127070"=>"广安","10127137"=>"达州","10128001"=>"拉萨","10128002"=>"那曲","10128003"=>"昌都","10128004"=>"山南","10128005"=>"日喀则","10128006"=>"阿里","10128007"=>"林芝","10129001"=>"乌鲁木齐","10129002"=>"石河子","10129003"=>"乌苏","10129004"=>"克拉玛依","10129006"=>"阿勒泰","10129007"=>"巴音郭楞","10129008"=>"哈密","10129009"=>"吐鲁番","10129010"=>"阿克苏","10129011"=>"喀什","10129012"=>"和田","10129013"=>"图木舒克","10129014"=>"五家渠","10129081"=>"奎屯","10129086"=>"塔城","10129088"=>"克孜勒苏","10129089"=>"博尔塔拉","10129017"=>"昌吉","10129090"=>"伊犁","10130001"=>"昆明","10130002"=>"曲靖","10130003"=>"昭通","10130005"=>"文山","10130007"=>"大理","10130008"=>"楚雄","10130098"=>"红河","10130009"=>"临沧","10130010"=>"保山","10130011"=>"玉溪","10130030"=>"丽江","10130052"=>"普洱","10130127"=>"西双版纳","10130128"=>"德宏","10130129"=>"怒江","10130130"=>"迪庆","10131001"=>"杭州","10131002"=>"温州","10131003"=>"宁波","10131004"=>"绍兴","10131005"=>"湖州","10131006"=>"嘉兴","10131009"=>"金华","10131010"=>"丽水","10131011"=>"衢州","10131012"=>"台州","10131013"=>"义乌","10131014"=>"温岭","10131015"=>"舟山","10132001"=>"澳门","10133001"=>"香港","10134001"=>"台湾","10500000"=>"美国","10600000"=>"加拿大","10700000"=>"日本","10800000"=>"澳大利亚","10900000"=>"英国","11000000"=>"法国","11100000"=>"德国","11200000"=>"俄罗斯","11300000"=>"新西兰","11400000"=>"泰国","11500000"=>"马来西亚","11600000"=>"印度尼西亚","11700000"=>"菲律宾","11800000"=>"新加坡","11900000"=>"韩国","12000000"=>"缅甸","12100000"=>"越南","12200000"=>"柬埔寨","12300000"=>"老挝","12400000"=>"印度","12500000"=>"文莱","12600000"=>"巴基斯坦","12700000"=>"朝鲜","12800000"=>"尼泊尔","12900000"=>"斯里兰卡","13000000"=>"土耳其","13100000"=>"乌克兰","13200000"=>"意大利","13300000"=>"芬兰","13400000"=>"荷兰","13500000"=>"挪威","13600000"=>"葡萄牙","13700000"=>"西班牙","13800000"=>"瑞典","13900000"=>"瑞士","14000000"=>"阿根廷","14100000"=>"巴西","14200000"=>"智利","14300000"=>"墨西哥","14400000"=>"秘鲁","14500000"=>"奥地利","14600000"=>"比利时","14700000"=>"丹麦","14800000"=>"希腊","14900000"=>"匈牙利","15000000"=>"哥伦比亚","15100000"=>"委内瑞拉","15200000"=>"爱尔兰","15300000"=>"保加利亚","15400000"=>"冰岛","15500000"=>"卢森堡","15600000"=>"罗马尼亚","15700000"=>"以色列","15800000"=>"埃及","15900000"=>"南非","16000000"=>"奥克兰","16100000"=>"喀麦隆","16200000"=>"毛里求斯","16300000"=>"马达加斯加","16400000"=>"其它地区")),
						array("hometownCity"=>array("0"=>"不限","10101201"=>"深圳","10101002"=>"广州","10101003"=>"佛山","10101004"=>"湛江","10101005"=>"珠海","10101006"=>"肇庆","10101007"=>"东莞","10101008"=>"惠州","10101011"=>"中山","10101012"=>"茂名","10101013"=>"汕头","10101014"=>"梅州","10101015"=>"韶关","10101016"=>"江门","10101018"=>"清远","10101020"=>"潮州","10101022"=>"阳江","10101023"=>"河源","10101026"=>"揭阳","10101028"=>"汕尾","10101068"=>"云浮","10105001"=>"重庆","10106001"=>"合肥","10106002"=>"淮南","10106003"=>"蚌埠","10106004"=>"宿州","10106005"=>"阜阳","10106006"=>"六安","10106007"=>"巢湖","10106008"=>"滁州","10106009"=>"芜湖","10106011"=>"安庆","10106012"=>"黄山","10106013"=>"铜陵","10106027"=>"贵池","10106042"=>"淮北","10106030"=>"桐城","10106038"=>"明光","10106075"=>"马鞍山","10106078"=>"天长","10106079"=>"池州","10106080"=>"宣城","10106099"=>"亳州","10107001"=>"福州","10107002"=>"厦门","10107010"=>"莆田","10107003"=>"泉州","10107013"=>"宁德","10107004"=>"南平","10107007"=>"漳州","10107008"=>"龙岩","10107009"=>"三明","10108001"=>"兰州","10108002"=>"张掖","10108003"=>"武威","10108004"=>"酒泉","10108006"=>"金昌","10108022"=>"临夏","10108007"=>"天水","10108008"=>"定西","10108012"=>"甘南","10108009"=>"平凉","10108023"=>"嘉峪关","10108064"=>"庆阳","10108078"=>"白银","10108084"=>"陇南","10109001"=>"南宁","10109002"=>"柳州","10109003"=>"钦州","10109004"=>"百色","10109005"=>"玉林","10109006"=>"防城港","10109007"=>"桂林","10109008"=>"梧州","10109083"=>"崇左","10109009"=>"河池","10109010"=>"北海","10109014"=>"贵港","10109018"=>"来宾","10109089"=>"贺州","10110001"=>"贵阳","10110002"=>"六盘水","10110004"=>"凯里","10110005"=>"都匀","10110006"=>"安顺","10110007"=>"遵义","10110021"=>"毕节","10110026"=>"兴义","10110055"=>"铜仁","10110082"=>"黔西南","10110083"=>"黔东南","10110084"=>"黔南","10111001"=>"海口","10111002"=>"三亚","10111006"=>"文昌","10111007"=>"琼海","10111020"=>"儋州","10111021"=>"五指山","10112001"=>"石家庄","10112002"=>"衡水","10112003"=>"邢台","10112004"=>"邯郸","10112005"=>"沧州","10112006"=>"唐山","10112007"=>"廊坊","10112008"=>"秦皇岛","10112009"=>"承德","10112010"=>"保定","10112011"=>"张家口","10113001"=>"郑州","10113002"=>"新乡","10113003"=>"安阳","10113004"=>"许昌","10113005"=>"驻马店","10113006"=>"漯河","10113007"=>"信阳","10113008"=>"周口","10113009"=>"洛阳","10113099"=>"济源","10113010"=>"平顶山","10113011"=>"三门峡","10113012"=>"南阳","10113013"=>"开封","10113014"=>"商丘","10113015"=>"鹤壁","10113016"=>"濮阳","10113017"=>"焦作","10114001"=>"哈尔滨","10114002"=>"绥化","10114053"=>"黑河","10114003"=>"佳木斯","10114004"=>"牡丹江","10114005"=>"齐齐哈尔","10114007"=>"大庆","10114067"=>"七台河","10114008"=>"大兴安岭","10114009"=>"鸡西","10114042"=>"伊春","10114020"=>"鹤岗","10114024"=>"双鸭山","10115001"=>"武汉","10115002"=>"黄石","10115004"=>"鄂州","10115005"=>"襄樊","10115006"=>"咸宁","10115007"=>"十堰","10115008"=>"宜昌","10115009"=>"恩施","10115010"=>"荆州","10115011"=>"黄冈","10115012"=>"荆门","10115013"=>"孝感","10115016"=>"神农架林区","10115034"=>"天门","10115062"=>"随州","10115066"=>"仙桃","10116001"=>"长沙","10116002"=>"株洲","10116003"=>"益阳","10116004"=>"岳阳","10116005"=>"常德","10116007"=>"娄底","10116008"=>"怀化","10116009"=>"衡阳","10116010"=>"邵阳","10116011"=>"郴州","10116013"=>"张家界","10116014"=>"湘潭","10116075"=>"永州","10116097"=>"湘西","10117001"=>"长春","10117002"=>"吉林","10117003"=>"延吉","10117042"=>"长白","10117004"=>"通化","10117006"=>"四平","10117007"=>"白城","10117008"=>"松原","10117028"=>"辽源","10117049"=>"延边","10118001"=>"南京","10118002"=>"苏州","10118003"=>"无锡","10118004"=>"徐州","10118005"=>"常州","10118006"=>"镇江","10118051"=>"泰州","10118007"=>"连云港","10118008"=>"淮安","10118009"=>"盐城","10118010"=>"扬州","10118011"=>"南通","10118017"=>"常熟","10118063"=>"宿迁","10119001"=>"南昌","10119002"=>"九江","10119003"=>"景德镇","10119004"=>"上饶","10119005"=>"鹰潭","10119006"=>"宜春","10119007"=>"萍乡","10119008"=>"赣州","10119009"=>"吉安","10119010"=>"抚州","10119040"=>"新余","10119042"=>"井岗山","10120001"=>"沈阳","10120002"=>"铁岭","10120003"=>"抚顺","10120004"=>"鞍山","10120005"=>"营口","10120006"=>"大连","10120007"=>"本溪","10120008"=>"丹东","10120009"=>"锦州","10120010"=>"朝阳","10120011"=>"阜新","10120012"=>"盘锦","10120013"=>"辽阳","10120014"=>"葫芦岛","10121001"=>"呼和浩特","10121002"=>"集宁","10121003"=>"包头","10121004"=>"临河","10121005"=>"乌海","10121007"=>"海拉尔","10121008"=>"赤峰","10121009"=>"锡林浩特","10121011"=>"通辽","10121024"=>"乌兰浩特","10121089"=>"锡林郭勒盟","10121090"=>"阿拉善盟","10121091"=>"兴安","10121092"=>"鄂尔多斯","10121093"=>"呼伦贝尔","10121094"=>"巴彦淖尔","10121095"=>"乌兰察布","10122001"=>"银川","10122002"=>"石嘴山","10122003"=>"固原","10122010"=>"吴忠","10122011"=>"中卫","10123001"=>"西宁","10123002"=>"果洛","10123003"=>"玉树","10123004"=>"格尔木","10123005"=>"海西","10123045"=>"海东","10123046"=>"海北","10123047"=>"黄南","10123048"=>"海南藏族自治州","10124001"=>"青岛","10124002"=>"威海","10124003"=>"济南","10124004"=>"淄博","10124005"=>"聊城","10124006"=>"德州","10124007"=>"东营","10124008"=>"潍坊","10124009"=>"烟台","10124011"=>"泰安","10124012"=>"菏泽","10124013"=>"临沂","10124014"=>"枣庄","10124015"=>"济宁","10124016"=>"日照","10124068"=>"莱芜","10124018"=>"滨州","10125001"=>"太原","10125003"=>"忻州","10125005"=>"大同","10125006"=>"临汾","10125008"=>"运城","10125009"=>"阳泉","10125010"=>"长治","10125011"=>"晋城","10125107"=>"晋中","10125108"=>"吕梁","10126001"=>"西安","10126002"=>"渭南","10126003"=>"延安","10126005"=>"榆林","10126006"=>"宝鸡","10126007"=>"安康","10126008"=>"汉中","10126010"=>"铜川","10126011"=>"咸阳","10127001"=>"成都","10127125"=>"巴中","10127002"=>"乐山","10127003"=>"凉山","10127005"=>"绵阳","10127007"=>"阿坝","10127008"=>"雅安","10127009"=>"甘孜","10127010"=>"广元","10127117"=>"遂宁","10127011"=>"南充","10127013"=>"内江","10127014"=>"自贡","10127015"=>"宜宾","10127016"=>"泸州","10127017"=>"攀枝花","10127018"=>"德阳","10127056"=>"眉山","10127070"=>"广安","10127137"=>"达州","10128001"=>"拉萨","10128002"=>"那曲","10128003"=>"昌都","10128004"=>"山南","10128005"=>"日喀则","10128006"=>"阿里","10128007"=>"林芝","10129001"=>"乌鲁木齐","10129002"=>"石河子","10129003"=>"乌苏","10129004"=>"克拉玛依","10129006"=>"阿勒泰","10129007"=>"巴音郭楞","10129008"=>"哈密","10129009"=>"吐鲁番","10129010"=>"阿克苏","10129011"=>"喀什","10129012"=>"和田","10129013"=>"图木舒克","10129014"=>"五家渠","10129081"=>"奎屯","10129086"=>"塔城","10129088"=>"克孜勒苏","10129089"=>"博尔塔拉","10129017"=>"昌吉","10129090"=>"伊犁","10130001"=>"昆明","10130002"=>"曲靖","10130003"=>"昭通","10130005"=>"文山","10130007"=>"大理","10130008"=>"楚雄","10130098"=>"红河","10130009"=>"临沧","10130010"=>"保山","10130011"=>"玉溪","10130030"=>"丽江","10130052"=>"普洱","10130127"=>"西双版纳","10130128"=>"德宏","10130129"=>"怒江","10130130"=>"迪庆","10131001"=>"杭州","10131002"=>"温州","10131003"=>"宁波","10131004"=>"绍兴","10131005"=>"湖州","10131006"=>"嘉兴","10131009"=>"金华","10131010"=>"丽水","10131011"=>"衢州","10131012"=>"台州","10131013"=>"义乌","10131014"=>"温岭","10131015"=>"舟山","10132001"=>"澳门","10133001"=>"香港","10134001"=>"台湾","10500000"=>"美国","10600000"=>"加拿大","10700000"=>"日本","10800000"=>"澳大利亚","10900000"=>"英国","11000000"=>"法国","11100000"=>"德国","11200000"=>"俄罗斯","11300000"=>"新西兰","11400000"=>"泰国","11500000"=>"马来西亚","11600000"=>"印度尼西亚","11700000"=>"菲律宾","11800000"=>"新加坡","11900000"=>"韩国","12000000"=>"缅甸","12100000"=>"越南","12200000"=>"柬埔寨","12300000"=>"老挝","12400000"=>"印度","12500000"=>"文莱","12600000"=>"巴基斯坦","12700000"=>"朝鲜","12800000"=>"尼泊尔","12900000"=>"斯里兰卡","13000000"=>"土耳其","13100000"=>"乌克兰","13200000"=>"意大利","13300000"=>"芬兰","13400000"=>"荷兰","13500000"=>"挪威","13600000"=>"葡萄牙","13700000"=>"西班牙","13800000"=>"瑞典","13900000"=>"瑞士","14000000"=>"阿根廷","14100000"=>"巴西","14200000"=>"智利","14300000"=>"墨西哥","14400000"=>"秘鲁","14500000"=>"奥地利","14600000"=>"比利时","14700000"=>"丹麦","14800000"=>"希腊","14900000"=>"匈牙利","15000000"=>"哥伦比亚","15100000"=>"委内瑞拉","15200000"=>"爱尔兰","15300000"=>"保加利亚","15400000"=>"冰岛","15500000"=>"卢森堡","15600000"=>"罗马尼亚","15700000"=>"以色列","15800000"=>"埃及","15900000"=>"南非","16000000"=>"奥克兰","16100000"=>"喀麦隆","16200000"=>"毛里求斯","16300000"=>"马达加斯加","16400000"=>"其它地区")),
						array("corptype"=>array("0"=>"不限","1"=>"政府机关","2"=>"事业单位","3"=>"世界500强","4"=>"外资企业","5"=>"上市公司","6"=>"国营企业","7"=>"私营企业","8"=>"自有公司")),
						array("is_lock"=>array("0"=>"是","1"=>"否")),
						array("usertype"=>array("1"=>"本站","2"=>"外站","3"=>"全权","4"=>"内部")),
						array("s_cid"=>array("10"=>"铂金","20"=>"钻石","30"=>"高级","40"=>"普通")),
						array("gender"=>array("0"=>"男","1"=>"女")),
						array("occupation"=>array("0"=>"请选择","1"=>"金融业","2"=>"计算机业","3"=>"商业","4"=>"服务行业","5"=>"教育业","6"=>"工程师","7"=>"主管，经理","8"=>"政" + "府部门","9"=>"制造业","10"=>"销售/广告/市场","11"=>"资讯业","12"=>"自由业","13"=>"农渔牧","14"=>"医生","15"=>"律师","16"=>"教师","17"=>"幼师","18"=>"会计师","19"=>"设计师","20"=>"空姐","21"=>"护士","22"=>"记者","23"=>"学者","24"=>"公务员","26"=>"职业经理人","27"=>"秘书","28"=>"音乐家","29"=>"画家","30"=>"咨询师","31"=>"审计师","32"=>"注册会计师","33"=>"军人","34"=>"警察","35"=>"学生","36"=>"待业中","37"=>"消防员","38"=>"经纪人","39"=>"模特","40"=>"教授","41"=>"IT工程师","42"=>"摄影师","43"=>"企业高管","44"=>"作家","99"=>"其他行业")),
						array("nation"=>array("0"=>"不限","1"=>"汉族","2"=>"藏族","3"=>"朝鲜族","4"=>"蒙古族","5"=>"回族","6"=>"满族","7"=>"维吾尔族","8"=>"壮族","9"=>"彝族","10"=>"苗族","11"=>"侗族","12"=>"瑶族","13"=>"白族","14"=>"布依族","15"=>"傣族","16"=>"京族","17"=>"黎族","18"=>"羌族","19"=>"怒族","20"=>"佤族","21"=>"水族","22"=>"畲族","23"=>"土族","24"=>"阿昌族","25"=>"哈尼族","26"=>"高山族","27"=>"景颇族","28"=>"珞巴族","29"=>"锡伯族","30"=>"德昂(崩龙)族","31"=>"保安族","32"=>"基诺族","33"=>"门巴族","34"=>"毛南族","35"=>"赫哲族","36"=>"裕固族","37"=>"撒拉族","38"=>"独龙族","39"=>"普米族","40"=>"仫佬族","41"=>"仡佬族","42"=>"东乡族","43"=>"拉祜族","44"=>"土家族","45"=>"纳西族","46"=>"傈僳族","47"=>"布朗族","48"=>"哈萨克族","49"=>"达斡尔族","50"=>"鄂伦春族","51"=>"鄂温克族","52"=>"俄罗斯族","53"=>"塔塔尔族","54"=>"塔吉克族","55"=>"柯尔克孜族","56"=>"乌兹别克族","57"=>"国外")),
						array("smoking"=>array("-1,不限","0,都可以","1,在意")),
						//array("nature"=>array("0"=>"请选择","1"=>"幽默开朗","2"=>"浪漫感性","3"=>"精明能干","4"=>"乐观积极","5"=>"热情豪爽","6"=>"爱心多多","7"=>"活泼大方","8"=>"浪漫感性","9"=>"认真理性","10"=>"感慨大方","11"=>"成熟稳重","12"=>"多愁善感","13"=>"温柔贤惠","14"=>"婉约含蓄","15"=>"保密")),
						array("drinking"=>array("0"=>"请选择","1"=>"不喝酒","2"=>"稍微喝一点/社交场合喝","3"=>"喝的很凶","5"=>"保密")),
						array("body"=>array("0"=>"请选择","1"=>"一般","2"=>"瘦长","3"=>"运动员型","4"=>"比较胖","5"=>"体格魁梧","6"=>"苗条","7"=>"高大美丽","8"=>"丰满","9"=>"富线条美","10"=>"矮壮结实","11"=>"皮肤白皙","12"=>"长发飘飘","13"=>"时尚卷发","14"=>"干练短发","15"=>"黝黑健康","16"=>"眉清目秀","17"=>"浓眉大眼","0"=>"保密")),
	                );
		 
		$sql='select nickname,username,truename,telphone,marriage,height,salary,education,children,house,province,city,corptype,is_lock,city_star,usertype,s_cid,bgtime,endtime,gender,birthyear,password,occupation,nation,hometownProvince,hometownCity,smoking,drinking,body,weight,family from `'.$GLOBALS['dbTablePre'].'members_search` where `uid`='.$uid;
		$userInfo=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
		
		$update1_arr = $update2_arr  = $update3_arr=array();
		//note members表字段
		if(isset($_POST['nickname']))$update1_arr['nickname'] = MooGetGPC('nickname','string','P');
		if(isset($_POST['username']))$update1_arr['username'] = MooGetGPC('username','string','P');
		if(isset($_POST['truename']))$update1_arr['truename'] = MooGetGPC('truename','string','P');
		if(isset($_POST['telphone']))$update1_arr['telphone'] = MooGetGPC('telphone','string','P');
		if(isset($_POST['marriage1']))$update1_arr['marriage'] = MooGetGPC('marriage1','integer','P');
		if(isset($_POST['height']))$update1_arr['height'] = MooGetGPC('height','integer','P');
		if(isset($_POST['salary']))$update1_arr['salary'] = MooGetGPC('salary','integer','P');
		if(isset($_POST['education1']))$update1_arr['education'] = MooGetGPC('education1','integer','P');
		if(isset($_POST['children1']))$update1_arr['children'] = MooGetGPC('children1','integer','P');
		if(isset($_POST['house']))$update1_arr['house'] = MooGetGPC('house','integer','P');
		if(isset($_POST['workprovince']))$update1_arr['province'] = MooGetGPC('workprovince','integer','P');
		if(isset($_POST['workCity']))$update1_arr['city'] = MooGetGPC('workCity','integer','P');
		if(isset($_POST['corptype']))$update1_arr['corptype'] = MooGetGPC('corptype','integer','P');
		if(isset($_POST['family']))$update1_arr['family'] = MooGetGPC('family','integer','P');
		
		
		//$update1_arr['sid'] = MooGetGPC('sid','integer');
		if(isset($_POST['is_lock'])) $update1_arr['is_lock'] = MooGetGPC('is_lock','integer','P');
		$update1_arr['city_star'] = MooGetGPC('city_star','integer','P');
		
        if(!empty($update1_arr['city_star'])){
            $city_star=$GLOBALS['_MooClass']['MooMySQL']->getOne('select `city_star` from `'.$GLOBALS['dbTablePre'].'members_search` where `uid`='.$uid,true);
            $now_t=time();
            if($city_star['city_star']<$now_t){
                $update1_arr['city_star']=3600*24*31+$now_t;
            }
        }
		
		if(isset($_POST['usertype'])) {
		   $update1_arr['usertype'] = MooGetGPC('usertype','integer','P');
		   
		   if($update1_arr['usertype']==5)  $update1_arr['sid']=600; //内部会员直接 设定 客服60号
		}
		
		
		if(isset($_POST['s_cid'])){
			$update1_arr['s_cid'] = MooGetGPC('s_cid','integer','P');
		}
		
		if(isset($_POST['bgtime']) && !empty($_POST['bgtime'])){
			$update1_arr['bgtime'] = strtotime(MooGetGPC('bgtime','string','P'));
		}
		
		if(isset($_POST['endtime']) && !empty($_POST['endtime'])){
			$update1_arr['endtime'] = strtotime(MooGetGPC('endtime','string','P'));
		}
		
		if(isset($_POST['gender']))$update1_arr['gender'] = MooGetGPC('gender','integer','P');
		if(isset($_POST['presex']))$presex = MooGetGPC('presex','integer','P');
		//if(isset($_POST['year']))$update1_arr['birthyear'] = MooGetGPC('year','integer','P');
		//if(isset($_POST['month']))$update1_arr['birthmonth'] = MooGetGPC('month','integer','P');
		//if(isset($_POST['day']))$update1_arr['birthday'] = MooGetGPC('day','integer','P');
		
		
		$password = trim(MooGetGPC('password','string','P'));
		if($password){
			$update1_arr['password'] = md5($password);
		}		
		//$update1_arr['introduce'] = MoogetGPC('introduce','string','P');
		//note memberfield表字段
		if(isset($_POST['occupation2']))$update1_arr['occupation'] = MooGetGPC('occupation2','integer','P');
		if(isset($_POST['stock2']))$update1_arr['nation'] = MooGetGPC('stock2','integer','P');
		if(isset($_POST['hometownProvince']))$update1_arr['hometownProvince'] = MooGetGPC('hometownProvince','integer','P');
		if(isset($_POST['hometownCity']))$update1_arr['hometownCity'] = MooGetGPC('hometownCity','integer','P');
		//$update2_arr['wantchildren'] = MooGetGPC('wantchildren2','integer','P');
		if(isset($_POST['issmoking']))$update1_arr['smoking'] = MooGetGPC('issmoking','integer','P');
		if(isset($_POST['isdrinking']))$update1_arr['drinking'] = MooGetGPC('isdrinking','integer','P');
		if(isset($_POST['body']))$update1_arr['body'] = MooGetGPC('body','integer','P');
		if(isset($_POST['weight']))$update1_arr['weight'] = MooGetGPC('weight','integer','P');
		
		
		$userBase=$GLOBALS['_MooClass']['MooMySQL']->getOne('select nature,qq,msn,birth,oldsex from `'.$GLOBALS['dbTablePre'].'members_base` where `uid`='.$uid,true);
		
		
		
		if(isset($_POST['nature3']))$update2_arr['nature'] = MooGetGPC('nature3','integer','P');
		if(isset($_POST['qq']))$update2_arr['qq'] = MooGetGPC('qq','string','P');
		if(isset($_POST['msn']))$update2_arr['msn'] = MooGetGPC('msn','string','P');
		if(isset($_POST['oldsex']))$update2_arr['oldsex'] = MooGetGPC('oldsex','integer','P');
		
		if(isset($_POST['year']) || isset($_POST['month']) || isset($_POST['day'])){//birth modify
			$year = isset($_POST['year']) && $_POST['year'] ? $_POST['year'] : 0;
			$month = isset($_POST['month']) && $_POST['month'] ? $_POST['month'] : 0;
			$day = isset($_POST['day']) && $_POST['day'] ? $_POST['day'] : 0;
			if(!$year) $time=0;
			elseif(!$month) $time=$year.'-01-01';
			elseif(!$day) $time=$year.'-'.$month.'-01';
			else $time=$year.'-'.$month.'-'.$day;
			$update2_arr['birth'] =date('Y-m-d',strtotime( $time));
			
		}
	    
		
		$sql='select age1,age2,height1,height2,weight1,weight2,salary,workprovince,workcity,hometownprovince,hometowncity,smoking,education,children,wantchildren,nature from `'.$GLOBALS['dbTablePre'].'members_choice` where `uid`='.$uid;
		$userChoice=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
		
		
		//note choice表字段
		if(isset($_POST['spouse_age1']))$update3_arr['age1'] = MooGetGPC('spouse_age1','integer','P');
		if(isset($_POST['spouse_age2']))$update3_arr['age2'] = MooGetGPC('spouse_age2','integer','P');
		if(isset($_POST['spouse_minheight']))$update3_arr['height1'] = MooGetGPC('spouse_minheight','integer','P');
		if(isset($_POST['spouse_maxheight']))$update3_arr['height2'] = MooGetGPC('spouse_maxheight','integer','P');
		if(isset($_POST['spouse_minweight']))$update3_arr['weight1'] = MooGetGPC('spouse_minweight','integer','P');
		if(isset($_POST['spouse_maxweight']))$update3_arr['weight2'] = MooGetGPC('spouse_maxweight','integer','P');
		
		if(isset($_POST['spouse_salary']))$update3_arr['salary'] = MooGetGPC('spouse_salary','integer','P');
		if(isset($_POST['spouse_workprovince']))$update3_arr['workprovince'] = MooGetGPC('spouse_workprovince','integer','P');
		if(isset($_POST['spouse_workCity']))$update3_arr['workCity'] = MooGetGPC('spouse_workCity','integer','P');
		if(isset($_POST['spouse_smoking']))$update3_arr['smoking'] = MooGetGPC('spouse_smoking','integer','P');
		if(isset($_POST['spouse_hometownprovince']))$update3_arr['hometownprovince'] = MooGetGPC('spouse_hometownprovince','integer','P');
		if(isset($_POST['spouse_hometowncity']))$update3_arr['hometowncity'] = MooGetGPC('spouse_hometowncity','integer','P');
		
		if(isset($_POST['spouse_education']))$update3_arr['education'] = MooGetGPC('spouse_education','integer','P');
		if(isset($_POST['spouse_children']))$update3_arr['children'] = MooGetGPC('spouse_children','integer','P');
		if(isset($_POST['spouse_wantchildren']))$update3_arr['wantchildren'] = MooGetGPC('spouse_wantchildren','integer','P');
		if(isset($_POST['spouse_character']))$update3_arr['nature'] = MooGetGPC('spouse_character','integer','P');
		
	    $where_arr=array('uid'=>$uid);
		
			
		$modifyinfo=''; //initialize modify information
		$comma="，";
		
		
	    $update1arr=array();
		foreach($update1_arr as $key=>$val){
	        foreach($search1 as $k=>$v){ //修改的字段名
			  if($k==$key) {
			    $column=$v;
			  }
			}
			
		    $update1arr[$key]=$val;
			
			
			if ( $userInfo[$key]!=$val){  //修改了值

			    foreach($search2 as $v1){   //循环数组
			         foreach($v1 as $k2=>$v2){
                    
					    if($k2==$key){  //如果出现在$search2数组中的
							 foreach($v2 as $k3=>$v3){
							    if($userInfo[$key]==$k3){ //对应修改的字段原先的值
								   $xx=$v3;
								}
								
								if($val==$k3){ //对应修改的字段现在的值
								   $val=$v3;
								   
								}
							 
							 }
							 break;
					         
						}elseif($k2!=$key && in_array($key,array("username","nickname","height","truename","telphone","weight","family"))) {  //如果不出现在$search2数组中的，直接显示的，比如姓名，电话，体重
						   $xx=$userInfo[$key];
						}
					
					
					}
				  
				  
				}
		    	$modifyinfo .=$column."原:".$xx."|现:".$val;
				$modifyinfo .=$comma;
			}
		} 
		
		
		foreach($update2_arr as $key=>$val){ 
			$update2arr[$key]=$val;
			
			if(!empty($val) && $userBase[$key]!=$val) {
			   $modifyinfo .=$key."原:".$userBase[$key]."|现:".$val;
			   $modifyinfo .=$comma;
            }
		} 
	
	    foreach($update3_arr as $key=>$val){
		
            $update3arr[$key]=$val;
			if(!empty($val) && $userChoice[$key]!=$val) {
			
			   //echo $val.'and'.$key.'and'.$userChoice[$key];exit;
			   $modifyinfo .="配偶".$key."原:".$userChoice[$key]."|现:".$val;
			   $modifyinfo .=$comma;
            }
        } 
        
		
		if(!isset($modifyinfo)){
		  echo "<script>alert('未修改任何信息');location.href='index.php?action=allmember&h=edit_info&uid={$uid}';</script>";exit;
		}
		
		
		$now = time();
		$update1arr['updatetime'] = $now;
		if(count($update1arr)>=1){		
		        
				updatetable('members_search',$update1arr,$where_arr);
				
				if(MOOPHP_ALLOW_FASTDB) {
				   $fastdb->set('members_searchuid'.$uid,null); 
				   
				   MooFastdbUpdate('members_search','uid',$uid,$update1arr);	
			
                }		

				if ($update1arr['s_cid'] ==20){
				   $sql = "REPLACE INTO {$GLOBALS['dbTablePre']}diamond (uid ,username ,skin ,status, nickname, gender, birthyear,bgtime,endtime)
							VALUES ( '{$uid}', '{$update1arr['username']}', '".($update1arr['gender']==0?'cyan':'red')."', '1', '{$update1arr['nickname']}','{$update1arr['gender']}','{$update1arr['birthyear']}','{$update1arr['bgtime']}','{$update1arr['endtime']}')";        
					if (!$GLOBALS['_MooClass']['MooMySQL']->query($sql)){
						echo "操作失败!无法添加钻石会员数据.请联系管理员...";
						exit();
					}
				
				}
				
						   
		}
		
		
		
		if(count($update2arr)>=1){							
				updatetable('members_base',$update2arr,$where_arr);
				
				if ($update2arr['birth']){
				   $yourage=date('Y',strtotime($update2arr['birth']));
				   $sql = "update web_members_search set birthyear=".$yourage ." where uid={$uid}";
				   $GLOBALS['_MooClass']['MooMySQL']->query($sql);
				   if(MOOPHP_ALLOW_FASTDB) MooFastdbUpdate('members_search','uid',$uid,array("birthyear"=>$yourage));	
				   
				}
				
				
				if(MOOPHP_ALLOW_FASTDB) MooFastdbUpdate('members_base','uid',$uid,$update2arr);			
	    }	   
		
        
        if(count($update3arr)>=1){            
		
                updatetable('members_choice',$update3arr,$where_arr);
                
                if(MOOPHP_ALLOW_FASTDB) {
				   // $fastdb->set('members_choiceuid'.$uid,null); 
				   MooFastdbUpdate('members_choice','uid',$uid,$update3arr);   
                }				   
        }   
	    
	    
		if(!empty($password)){
			$userinfo = $GLOBALS['_MooClass']['MooMySQL']->getOne("SELECT username FROM {$GLOBALS['dbTablePre']}members_search WHERE uid='$uid'",true);
			$content = "尊敬的用户：您在真爱一生网的密码现在改为".$password;
			MooSendMail($userinfo['username'],"修改密码",$content,'../public/system/mailtamp/template.html',$is_template = true,$uid);
			insert_admin_log($uid,"{$GLOBALS['username']}客服成功修改{$uid}会员密码为{$password}");
			//fangin暂时屏蔽
			//暂时不改
			if($userinfo['telphone']){
				if(SendMsg($userinfo['telphone'],$content)){
					$GLOBALS['_MooClass']['MooMySQL']->query("INSERT INTO {$GLOBALS['dbTablePre']}smslog(sid,uid,content,sendtime) VALUES('{$GLOBALS['adminid']}','$uid','$content','{$GLOBALS['timestamp']}')");
				}
			}
		}
		
		
		if(!isset($tag)) $tag = '';
		//添加操作日志
		//serverlog(4,$GLOBALS['dbTablePre']."members_search和member_base","{$GLOBALS['username']}修改用户{$uid}资料",$GLOBALS['adminid'],$uid);
		serverlog(4,$GLOBALS['dbTablePre']."members_search和member_base",$modifyinfo,$GLOBALS['adminid'],$uid);
		echo "<script>alert('修改成功{$tag}');location.href='index.php?action=allmember&h=edit_info&uid={$uid}';</script>";exit;
		//salert("修改成功{$tag}");exit;
	}
	
	$uid = MooGetGPC('uid','integer','G');	

	$userinfo = $GLOBALS['_MooClass']['MooMySQL']->getOne("SELECT * FROM {$GLOBALS['dbTablePre']}members_search WHERE uid='$uid'",true);
	$userfield = $GLOBALS['_MooClass']['MooMySQL']->getOne("SELECT * FROM {$GLOBALS['dbTablePre']}members_base WHERE uid='$uid'",true);
	$spouseinfo = $GLOBALS['_MooClass']['MooMySQL']->getOne("SELECT * FROM {$GLOBALS['dbTablePre']}members_choice WHERE uid='$uid'",true);

	$userinfo = $userfield = $userinfo + $userfield;
	
	
    if(in_array($GLOBALS['groupid'],$GLOBALS['admin_service_arr'] )|| in_array($GLOBALS['groupid'],$GLOBALS['admin_service_after'])|| in_array($GLOBALS['groupid'],$GLOBALS['admin_service_team']) || in_array($GLOBALS['groupid'],$GLOBALS['admin_complaints']) || (in_array($GLOBALS['groupid'],$GLOBALS['admin_all_group']) && $userinfo['usertype']==3) || in_array($GLOBALS['groupid'],$GLOBALS['admin_resource_manager']) || in_array($GLOBALS['groupid'],$GLOBALS['admin_service_manager'])  || (in_array($GLOBALS['groupid'],$GLOBALS['general_service']) && $userinfo['usertype']==3)){
        $is_admin=0;//管理人员可以修改特殊字段
	}else{
		$is_admin=1;
	}
	
	
	 require adminTemplate("allmember_edit_info");
	
}

//note 添加小记
function allmember_note(){
	global $reprating,$adminid,$timestamp;
	$ispost = MooGetGPC('ispost','integer','P');
	if( $ispost ){
		$uid = MooGetGPC('uid','integer','P');
		$grade = MooGetGPC('grade','integer','P');
		$contact = MooGetGPC('contact','integer','P');
		$master = MooGetGPC('master','integer','P');
		$time = strtotime(MooGetGPC('time','string','P'));
		$interest = MooGetGPC('interest','string','P');
		$different = MooGetGPC('different','string','P');
		$service = MooGetGPC('service','string','P');
		$desc = MooGetGPC('desc','string','P');
		$comment = MooGetGPC('comment','string','P');
		$phonecall = MooGetGPC('phonecall','integer','P');
		//note 手机是否验证原因
		$checkreason = MooGetGPC('checkreason','integer','P');
		//note 手机号码
		$telphone = MooGetGPC('telphone','string','P');
		$dateline = time();
		//采集使用
		$usertype = MooGetGPC('usertype','string','P');
        //如果客服已经给全权会员做了小记，那么这个会员就处于使用状态
        if($usertype==3){
            $action_time= $GLOBALS['_MooClass']['MooMySQL']->getOne('SELECT `action_time` FROM `web_full_log` WHERE `uid`=\''.$uid.'\'',true);
            if(empty($action_time)){
                $GLOBALS['_MooClass']['MooMySQL']->query('INSERT INTO `web_full_log` (`uid`, `action_time`) VALUES (\''.$uid.'\', \''.$dateline.'\')');
            }
            
        }
		
		$sql = "INSERT INTO {$GLOBALS['dbTablePre']}member_backinfo(mid,manager,uid,effect_grade,effect_contact,master_member,next_contact_time,interest,different,service_intro,next_contact_desc,comment,dateline,phonecall)
				VALUES({$GLOBALS['adminid']},'{$GLOBALS['username']}',{$uid},{$grade},{$contact},{$master},'{$time}','{$interest}','{$different}','{$service}','{$desc}','{$comment}','{$dateline}','{$phonecall}')";
		$GLOBALS['_MooClass']['MooMySQL']->query($sql);
		
//		$sql = "SELECT * FROM {$GLOBALS['dbTablePre']}member_admininfo WHERE uid='{$uid}'"; // original file
		$sql = "SELECT uid FROM {$GLOBALS['dbTablePre']}member_admininfo WHERE uid='{$uid}'"; // updated file
		$member_admininfo = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
		if(!empty($member_admininfo)){
			$dateline = time();
			$sql = "UPDATE {$GLOBALS['dbTablePre']}member_admininfo SET effect_grade='{$grade}',effect_contact='{$contact}',master_member='{$master}',next_contact_time='{$time}',dateline='{$dateline}',checkreason='{$checkreason}' WHERE uid='{$uid}'";
		}else{
			$sql = "INSERT INTO {$GLOBALS['dbTablePre']}member_admininfo(uid,effect_grade,effect_contact,master_member,next_contact_time,dateline,checkreason)
				VALUES('{$uid}','{$grade}','{$contact}','{$master}','{$time}','{$dateline}','{$checkreason}')";
		}
	
		
		$GLOBALS['_MooClass']['MooMySQL']->query($sql);
		
			
		//NOTE 3,4,5类会员保存小记后台提醒客服组长
		$gid = getGroupID($adminid);
		$wokao=$grade - 1;
		if(in_array($wokao,array('3','4','5'))){
			$title = '会员'.$uid.'转为 '.$wokao.'类';
			$awoketime = $timestamp+3600;
			$sql_remark = "insert into {$GLOBALS['dbTablePre']}admin_remark set sid='{$gid}',title='{$title}',content='{$title}',awoketime='{$awoketime}',dateline='{$timestamp}'";
			$res = $GLOBALS['_MooClass']['MooMySQL']->query($sql_remark);
		}
		
	

		//发送短信评价
		/* if($phonecall=='1'){
			if($telphone!='0'){
				$arrtxt = implode(" ",$reprating);
				$mes = "回复数字对专属红娘进行评价：".$arrtxt." 。谢谢！回复免费。【真爱一生网】";
				$ret = SendMsg($telphone,$mes);
				if($ret!==false){
					$sql = "INSERT INTO {$GLOBALS['dbTablePre']}smslog(sid,uid,content,sendtime) VALUES('{$GLOBALS['adminid']}','{$uid}','{$mes}','{$dateline}')";
					$sid = $GLOBALS['_MooClass']['MooMySQL']->query($sql);
				}else{
					$sql = "INSERT INTO {$GLOBALS['dbTablePre']}smslog(sid,uid,content,sendtime) VALUES('{$GLOBALS['adminid']}','{$uid}','短信发送失败','{$dateline}')";
					$sid = $GLOBALS['_MooClass']['MooMySQL']->query($sql);
				}
			}
		} */
		
		
		// 会员跟踪步骤 mycount 统计
		try {
			$m = $GLOBALS['_MooClass']['MooMySQL']->getOne("SELECT b.source as source FROM web_members_search s left join web_members_base b on s.uid=b.uid  where s.uid='{$uid}' ",true);
			// 只统计有渠道的
			$source = trim($m['source']);
			if ( !empty($source) ) {
				$str_md5 = md5($uid . ($grade - 1) . MOOPHP_AUTHKEY);
				$apiurl  = "http://tg.zhenaiyisheng.cc/hongniang/hongniang_effect_grade_import.php?uid=" . $uid . "&md5=" . $str_md5 . "&grade=" . ($grade - 1);
				$result  = file_get_contents($apiurl);
			}
		}
		catch (Exception $ex) {
		}
		//note 
				
		//添加操作日志
		//serverlog(4,$GLOBALS['dbTablePre']."members_backinfo","{$GLOBALS['username']}保存用户{$uid}的小记",$GLOBALS['adminid'],$uid);
		salert("小记保存成功！","index.php?action=allmember&h=view_info&uid={$uid}");
	}

}
//note 高级搜索,fanglin
function allmember_advancesearch(){
    
	//初始化
	$member_list = array();
	$qsh = array ('workcitycity1','workcityprovince1','province','city','uid','grade','height1','height2','weight1','weight2','username','allotdate1','allotdate2','truename','regdate1','regdate2','nickname','next_contact_time1','next_contact_time2','age1','age2', 'last_login_time1','last_login_time2','telphone','salary','gender','sid','nocontactdays', 's_cid','usertype','uploadpicnum','is_lock','recentloginnum','marriage','online','qq','action','allmember','debug');
	foreach ($qsh as $vq){
		$_GET[$vq] = empty($_GET[$vq]) ? '' : $_GET[$vq];
	}
	$arr = array('ismok'=>'smoking','idrink'=>'drinking','child'=>'children','wantchildren'=>'wantchildren','height1'=>'','height2'=>'',
	'weight1'=>'','weight2'=>'','body'=>'body','education'=>'education','occupation'=>'occupation','house'=>'house','vehicle'=>'vehicle','corptp'=>'corptype','family'=>'family');
	
	foreach ($arr as $k=>$v){
		$k!=$v && $$v = '';
		$$k = empty($_GET[$k]) ? '' : $_GET[$k];
//		$$v = '';
		if (!empty($$k)&&is_array($$k)){
			$$v = implode(',',$$k);
		}
	}

	////
	$adminid      = $GLOBALS['adminid'];
	$admingroup   = $GLOBALS['groupid'];
	$page1        = MooGetGPC('page','integer', 'G');
		//分页
	$page_per     = 50;
	$page         = get_page();
	$limit        = 50;
	$offset       = ($page-1)*$limit;
    //note 消除记忆功能
	if(!$page1){$offset = 0;$page = 1;}    
	$members_condition  = $member_admininfo_condition = $memberfield_condition = array();
	$sql_search                = array();
	$spx_search                = array();
	
	//精确查询
	!empty($_GET['uid'])&&$sql_search['uid'] = "m.uid=".MooGetGPC('uid','integer','G');//精确查询
	
	//sphinx查询
	!empty($_GET['usertype']) && $spx_search[] = array('usertype',MooGetGPC('usertype','integer','G'));
	!empty($_GET['marriage']) && $spx_search[] = array('marriage',MooGetGPC('marriage','integer','G'));
    $gender = !empty($_GET['gender']) ? $_GET['gender'] : '';
    switch ($gender){
    	case 1:$spx_index = 'members_man';break;
    	case 2:$spx_index = 'members_women';break;
    	default:$spx_index= 'members_man members_women';
    }
    
   !empty($_GET['regdate1'])&&!empty($_GET['regdate2'])&&$spx_search[] = array('regdate',array(strtotime(MooGetGPC('regdate1','string','G')),strtotime(MooGetGPC('regdate2','string','G'))));
   !empty($_GET['s_cid'])&&$spx_search[] = array('s_cid',MooGetGPC('s_cid','integer','G'));	
   if (!empty($_GET['age1'])||!empty($_GET['age2'])){
	   	$age1         = empty($_GET['age1']) ?  0 : MooGetGPC('age1','integer','G');
	   	$age2         = empty($_GET['age2']) ?  99: MooGetGPC('age2','integer','G');
	   	$tmp_start1   = gmdate('Y', time()) - $age1;
	   	$tmp_start2   = gmdate('Y', time()) - $age2;
   		$spx_search[] = array('birthyear',array($tmp_start2,$tmp_start1));
   }
   
   !empty($_GET['uploadtime'])&&$sql_search['uploadtime'] = "p.pic_date=".date('Y/m/d',strtotime(MooGetGPC('uploadtime','string','G')));
   !empty($_GET['last_login_time1'])&&$sql_search['login_last_login_time1'] = "l.last_login_time>=".strtotime(MooGetGPC('last_login_time1','string','G'));
   !empty($_GET['last_login_time2'])&&$sql_search['login_last_login_time2'] = "l.last_login_time<=".strtotime(MooGetGPC('last_login_time2','string','G'));
   !empty($_GET['province'])&&$_GET['province']!='-1'&&$spx_search[] = array('province',MooGetGPC('province','integer','G'));
   !empty($_GET['city'])&&$_GET['city']!='-1'&&$spx_search[] = array('city',MooGetGPC('city','integer','G'));
   !empty($_GET['salary'])&&$_GET['salary']!='-1'&&$spx_search[] = array('salary',MooGetGPC('salary','integer','G'));
   !empty($_GET['sid'])&&$spx_search[] = array('sid',MooGetGPC('sid','integer','G'));
   !empty($_GET['is_lock'])&&$spx_search[] = array('is_lock',MooGetGPC('is_lock','integer','G'));

   
   !empty($_GET['master_member'])&&$sql_search['admin_master_member'] = " ma.master_member=".MooGetGPC('master_member','integer','G');
    
   !empty($_GET['telphone'])&&$spx_search[] = array('telphone',MooGetGPC('telphone','string','G'));
  

	//note 搜索上传照片数目条件
	if(!empty($_GET['uploadpicnum'])){
		$uploadpicnum=MooGetGPC('uploadpicnum','integer','G');
		switch($uploadpicnum){
			case 6:
				$sql_search['base_mainimg'] = "b.mainimg = ''";		
				break;
			case 1:
				$spx_search[] = array('pic_num',array(1,5));		
				break;
			case 2:	
				$spx_search[] = array('pic_num',array(6,10));		
				break;
			case 3:	
				$spx_search[] = array('pic_num',array(11,15));	
				break;
			case 4:	
				$spx_search[] = array('pic_num',array(16,20));		
				break;
			case 5:	
				$spx_search[] = array('pic_num',array(0,20),EXCLUDE_TRUE);		
				break;			
		}
	}
	!empty($_GET['username'])&&$spx_search[] = array('username',trim(MooGetGPC('username','string','G')));//精确查询
	!empty($_GET['nickname'])&&$spx_search[] = array('nickname',trim(MooGetGPC('nickname','string','G')));
	!empty($_GET['truename'])&&$spx_search[] = array('truename',trim(MooGetGPC('truename','string','G')));
	!empty($_GET['workcityprovince1'])&&$_GET['workcityprovince1']!='-1'&&$spx_search[] = array('hometownprovince',MooGetGPC('workcityprovince1','integer','G'));
	!empty($_GET['workcitycity1'])&&$_GET['workcitycity1']!='-1'&&$spx_search[] = array('hometowncity',MooGetGPC('workcitycity1','integer','G'));
	!empty($_GET['ismok'])&&$spx_search[] = array('smoking',implode('|',$_GET['ismok']));
	!empty($_GET['idrink'])&&$spx_search[] = array('drinking',implode('|',$_GET['idrink']));
	!empty($_GET['child'])&&$spx_search[] = array('children',implode('|',$_GET['child']));
	!empty($_GET['wantchildren'])&&$spx_search[] = array('wantchildren',implode('|',$_GET['wantchildren']));
	if((!empty($_GET['height1'])&&$_GET['height1']!='-1')||(!empty($_GET['height2'])&&$_GET['height2']!='-1')){
		$height1   = empty($_GET['height1']) ? 0 : $_GET['height1'];
		$height2   = empty($_GET['height2']) ? 300: $_GET['height2'];
		$spx_search[] = array('height',array($height1,$height2));
	}

	if((!empty($_GET['weight1'])&&$_GET['weight1']!='-1')||(!empty($_GET['weight2'])&&$_GET['weight2']!='-1')){
		$weight1   = empty($_GET['weight1']) ? 0 : $_GET['weight1'];
		$weight2   = empty($_GET['weight2']) ? 300: $_GET['weight2'];
		$spx_search[] = array('weight',array($weight1,$weight2));
	}
	
	!empty($_GET['body'])&&$spx_search[] = array('body',implode('|',$_GET['body']));
	!empty($_GET['education'])&&$spx_search[] = array('education',implode('|',$_GET['education']));
	!empty($_GET['occupation'])&&$spx_search[] = array('occupation',implode('|',$_GET['occupation']));
	!empty($_GET['house'])&&$spx_search[] = array('house',implode('|',$_GET['house']));
	!empty($_GET['vehicle'])&&$spx_search[] = array('vehicle',implode('|',$_GET['vehicle']));
	!empty($_GET['corptp'])&&$spx_search[] = array('corptype',implode('|',$_GET['corptp']));
	!empty($_GET['family'])&&$spx_search[] = array('family',implode('|',$_GET['family']));
	
	
	
	
	//sql查询
	!empty($_GET['grade'])&&$sql_search['admin_grade'] = "ma.effect_grade=".MooGetGPC('grade','integer','G');
	!empty($_GET['allotdate1'])&&$sql_search['base_allotdate1'] =  "b.allotdate>=".strtotime(MooGetGPC('allotdate1','string','G'));
	!empty($_GET['allotdate2'])&&$sql_search['base_allotdate2'] =  "b.allotdate<=".strtotime(MooGetGPC('allotdate2','string','G'));
	!empty($_GET['next_contact_time1'])&&$sql_search['admin_nct1'] = "ma.next_contact_time>=".strtotime(MooGetGPC('next_contact_time1','string','G'));
   !empty($_GET['next_contact_time2'])&&$sql_search['admin_nct2'] = "ma.next_contact_time<=".strtotime(MooGetGPC('next_contact_time2','string','G'));
	//note 超过几天没有联系的 
	if(!empty($_GET['nocontactdays']) && $_GET['nocontactdays']){
		$nocontactdays = MooGetGPC('nocontactdays','integer','G');
		$nowtime = time();
		$oneday = $nowtime - 86400;
		$twoday = $nowtime - 2*86400;
		$threeday = $nowtime - 3*86400;
		$fourday = $nowtime - 4*86400;
		switch($nocontactdays){
			case 1:
				$sql_search['admin_dateline'] = " ma.dateline < '{$oneday}' and ma.dateline >= '{$twoday}' ";
				break;
			case 2:
				$sql_search['admin_dateline'] = " ma.dateline < '{$twoday}' and ma.dateline >= '{$threeday}' ";
				break;
			case 3:
				$sql_search['admin_dateline'] = " ma.dateline < '{$threeday}' and ma.dateline >= '{$fourday}' ";
				break;		
		}
	}
	//note 登录次数  + 最后登录时间 （3天）= 最近登录次数
	if(!empty($_GET['recentloginnum'])){
		$recentloginnum = MooGetGPC('recentloginnum','integer','G');
		$nowtime = time();
		$threeday = $nowtime - 3*86400;
		switch($recentloginnum){
			case 1:
				$sql_search['login_login_meb'] = " l.login_meb <= 10 and l.login_meb >=1 and l.last_login_time > '{$threeday}' ";		
				break;
			case 2:
				$sql_search['login_login_meb'] = " l.login_meb <= 20 and l.login_meb >=11 and l.last_login_time > '{$threeday}' ";		
				break;
			case 3:
				$sql_search['login_login_meb'] = " l.login_meb <= 21 and l.login_meb >=30 and l.last_login_time > '{$threeday}' ";		
				break;
			case 4:
				$sql_search['login_login_meb'] = " l.login_meb >=30 and l.last_login_time > '{$threeday}' ";		
				break;	
		}					
	}
	//当前在线1,2为一天，3为一周内，4为一周外
	if(!empty($_GET['online'])){
		$online = MooGetGPC('online','string','G');
		switch($online){
			case 1:
				$time = time()-100;
				$sql_search['admin_real_lastvisit'] = " ma.real_lastvisit>{$time}";
			break;
			case 2:
				$time = time()-24*3600;
				$sql_search['admin_real_lastvisit'] = " ma.real_lastvisit>{$time}";
			break;
			case 3:
				$time1 = time()- 7*24*3600;
				$time2 = time() - 24*3600;
				$sql_search['admin_real_lastvisit'] = " ma.real_lastvisit > {$time1} and  ma.real_lastvisit < {$time2}";
			break;
			case 4:
				$time = time()- 7*24*3600;
				$sql_search['admin_real_lastvisit'] = " ma.real_lastvisit < {$time}";
			break;	
		}
	}
	
	if(!empty($_GET['qq'])){
		$qq = $_GET['qq'];
		$sql_qq = "select qq from {$GLOBALS['dbTablePre']}members_base where qq='{$qq}'";
		$res_qq = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql_qq,true);
		if(!empty($res_qq['qq'])){
			$sql_search['base_qq'] = "b.qq='{$qq}'";
		}else{
			$spx_search[] = array('username',"{$qq}.com");
		}
	}
    
     $callno=MooGetGPC('callno','string','G');
    if(!empty($callno)) $sql_search['base_callno']="b.callno='{$callno}'";
   
	
	//普通客服和组长搜索不到封锁的和关闭资料的会员
	if(in_array($GLOBALS['groupid'],$GLOBALS['admin_all_group'])||in_array($GLOBALS['groupid'],$GLOBALS['general_service'])){
		$sql_search['ext_sql'] = " ((m.showinformation=1 and m.is_lock=1 ))";// or (m.usertype=1)
	}

	//sphinx条件不为空
	$found_ids = array();
   
  
	if (!empty($spx_search)||!empty($gender)){
		
		if(($offset+$limit)>1000){
			allmember_advancesearch_old();
			return '';
		}
		
		$sp = searchApi($spx_index);
		if (empty($sql_search)){
                  
			if ($offset>=1000){
				allmember_advancesearch_old();
				return;
			}

			$rs = $sp->getResult($spx_search,array($offset,$limit));
	                
			$found_ids = $sp->getIds();
                      
                    
		}else {
                        
			$rs = $sp->getResult($spx_search,array(0,1000));
			
			if ($rs['total_found'] > 1000){
			allmember_advancesearch_old();
			return;
			}else {
				$found_ids = $sp->getIds();
			}
		}
	}

	
	if (!empty($sql_search) || !empty($spx_search)||!empty($gender)){

		if (empty($sql_search)){//只用sphinx查询
			$total = $rs['total_found'];
			
			$ids   = implode(',',$found_ids);
      
			if ($rs){
				$sql_f = "SELECT m.uid,m.username,m.is_lock,m.gender,m.birthyear,b.mainimg,m.telphone,m.salary,m.s_cid,m.province,m.city,b.allotdate,m.regdate,m.sid,m.usertype,l.last_login_time,ma.next_contact_time,ma.effect_grade,ma.old_sid,ma.real_lastvisit,f.action_time FROM {$GLOBALS['dbTablePre']}members_search m LEFT JOIN {$GLOBALS['dbTablePre']}member_admininfo ma ON m.uid=ma.uid left join {$GLOBALS['dbTablePre']}members_base as b on b.uid=m.uid  left join {$GLOBALS['dbTablePre']}members_login as l on l.uid=b.uid  left join web_full_log as f on m.uid=f.uid  where m.uid in({$ids})";
			}
		
		}elseif (empty($spx_search)){
			allmember_advancesearch_old();
			return;
		}else {
         
			if ($rs){
				$where_sql = implode(' and ',$sql_search);
				$where_sql = ltrim(ltrim($where_sql,' '),'and');

				$ids       = implode(',',$found_ids);
				$sql       = "SELECT m.uid FROM {$GLOBALS['dbTablePre']}members_search m LEFT JOIN {$GLOBALS['dbTablePre']}member_admininfo ma ON m.uid=ma.uid left join {$GLOBALS['dbTablePre']}members_base as b on b.uid=m.uid left join {$GLOBALS['dbTablePre']}members_login as l on l.uid=b.uid  left join web_full_log as f on f.uid=m.uid left join {$GLOBALS['dbTablePre']}pic as p on m.uid=p.uid where $where_sql and m.uid in($ids)";
				
				$r=$GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
		
				$u=array();
				if(!empty($r)){
					foreach($r as $v){
						array_push($u,$v['uid']);
					}
				}
				$u=array_unique($u);
				$total=sizeof($u);
				$ul=implode(',',$u);
	            
				$sql_f       = "SELECT m.uid,m.username,m.is_lock,m.gender,m.birthyear,b.mainimg,m.telphone,m.salary,m.s_cid,m.province,m.city,b.allotdate,m.regdate,m.sid,m.usertype,l.last_login_time,ma.next_contact_time,ma.effect_grade,ma.old_sid,ma.real_lastvisit,f.action_time FROM {$GLOBALS['dbTablePre']}members_search m LEFT JOIN {$GLOBALS['dbTablePre']}member_admininfo ma ON m.uid=ma.uid left join {$GLOBALS['dbTablePre']}members_base as b on b.uid=m.uid left join {$GLOBALS['dbTablePre']}members_login as l on l.uid=b.uid left join web_full_log as f on m.uid=f.uid  where m.uid in($ul) limit $offset,$limit";
			}
		}
	}
	
	

	$member_list = array();
	$page_links  = '';
	$currenturl  = '';
	$currenturl2 = '';
	if(!empty($sql_f)){
		$members = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql_f,0,0,0,true);
		$member_list=$members;
		if(isset($_GET['order_tel'])&&$_GET['order_tel']){
			$tel=get_telphone($members);
			if($members){
				foreach($members as $user){
					$user['num']=$tel[$user['telphone']];
					$member_list[]=$user;
				}
				//当点击同号手机数时排序
				
					foreach ($member_list as $key => $row) {
						$edition[$key] = $row[num];
					}
					array_multisort($edition, SORT_DESC, $member_list);//二维数组排序
				
			}
		}
		//note 获得当前的url 去除多余的参数page=
		$currenturl = $_SERVER["REQUEST_URI"]; 
	
		$currenturl2 = preg_replace("/(&page=\d+)/","",$currenturl);

		$page_links = multipage( $total, $page_per, $page, $currenturl2 );
		
		
	}
    

	//所有客服列表
	/* $sql = "SELECT * FROM {$GLOBALS['dbTablePre']}admin_user ORDER BY uid ASC";
	$kefu_list = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
	
 	//所管理的客服id列表
   $myservice_idlist = get_myservice_idlist();
   $myservice_sid_arr = array();
   if($myservice_idlist != 'all' && $myservice_idlist!=''){
   		$myservice_sid_arr = explode(',',$myservice_idlist);
       foreach($myservice_sid_arr as $key=>$value){
           if(empty($value)){
               unset($myservice_sid_arr[$key]);
           }
       }
   		$sql = "SELECT * FROM {$GLOBALS['dbTablePre']}admin_user WHERE uid IN (".implode(',',$myservice_sid_arr).") ORDER BY uid ASC";

		
		$kefu_list = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
   } */
    $kefu_list = get_kefulist();
	require "data/kefulist_cache_.php";

	require adminTemplate("allmember_advancesearch");
}

//note 高级搜索,fanglin
function allmember_advancesearch_old(){
    
	$member_list = array();
	$page_links  = '';
	$arr = array('ismok'=>'smoking','idrink'=>'drinking','child'=>'children','wantchildren'=>'wantchildren','height1'=>'','height2'=>'',
	'weight1'=>'','weight2'=>'','body'=>'body','education'=>'education','occupation'=>'occupation','house'=>'house','vehicle'=>'vehicle','corptp'=>'corptype','family'=>'family');
	foreach ($arr as $k=>$v){
		$k!=$v && $$v = '';
		$$k = empty($_GET[$k]) ? '' : $_GET[$k];
//		$$v = '';
		if (!empty($$k)&&is_array($$k)){
			$$v = implode(',',$$k);
		}
	}
	
	$adminid = $GLOBALS['adminid'];
	$admingroup = $GLOBALS['groupid'];
	$page1 = MooGetGPC('page','integer', 'G');
	//分页
	$page_per = 50;
    $page = get_page();
    $limit = 50;
    $offset = ($page-1)*$limit;
    //note 消除记忆功能
	if(!$page1){$offset = 0;$page = 1;}    
	$members_condition = $member_admininfo_condition = $memberfield_condition = array();
	
	if($_GET['uid']){
		$uid = MooGetGPC('uid','integer','G');
			$members_condition[] =  " m.uid='{$uid}'";
	}

	if($_GET['grade'] != ''){
		$effect_grade = MooGetGPC('grade','integer','G');
		//$member_backinfo_sql .= " effect_grad='{$effect_grade}'";
		$member_admininfo_condition[] = " ma.effect_grade='{$effect_grade}'";
	}
	
	if($_GET['usertype'] ){
	$usertype=MooGetGPC('usertype','integer','G');
	$members_condition[]=" m.usertype='{$usertype}'";
	}

	if($_GET['marriage']){
		$marriage = MooGetGPC('marriage','integer','G');
		$members_condition[] = " m.marriage='{$marriage}'";
	}
	
	if($_GET['allotdate1']){
		$allodate1 = strtotime(MooGetGPC('allotdate1','string','G'));
		$members_condition[] = " b.allotdate>='{$allodate1}'";
	}
	if($_GET['allotdate2']){
		$allodate2 = strtotime(MooGetGPC('allotdate2','string','G'));
		$members_condition[] = " b.allotdate<='{$allodate2}'";
	}
	//note 搜索上传照片数目条件
	if(!empty($_GET['uploadpicnum'])){
		$uploadpicnum=MooGetGPC('uploadpicnum','integer','G');
		switch($uploadpicnum){
			case 6:
				$members_condition[] = " b.mainimg = '' ";		
				break;
			case 1:
				$members_condition[] = " m.pic_num >= 1 and m.pic_num <= 5 ";		
				break;
			case 2:	
				$members_condition[] = " m.pic_num >= 6 and m.pic_num <= 10 ";		
				break;
			case 3:	
				$members_condition[] = " m.pic_num >= 11 and m.pic_num <= 15 ";		
				break;
			case 4:	
				$members_condition[] = " m.pic_num >= 16 and m.pic_num <= 20 ";		
				break;
			case 5:	
				$members_condition[] = " m.pic_num > 20 ";		
				break;			
			
		}
		
	}
	
	//note 超过几天没有联系的 
	if($_GET['nocontactdays']){
		$nocontactdays = MooGetGPC('nocontactdays','integer','G');
		$nowtime = time();
		$oneday = $nowtime - 86400;
		$twoday = $nowtime - 2*86400;
		$threeday = $nowtime - 3*86400;
		$fourday = $nowtime - 4*86400;
		switch($nocontactdays){
			case 1:
				$member_admininfo_condition[] = " ma.dateline < '{$oneday}' and ma.dateline >= '{$twoday}' ";
				break;
			case 2:
				$member_admininfo_condition[] = " ma.dateline < '{$twoday}' and ma.dateline >= '{$threeday}' ";
				break;
			case 3:
				$member_admininfo_condition[] = " ma.dateline < '{$threeday}' and ma.dateline >= '{$fourday}' ";
				break;		
		}
		
	}
	
	//note 登录次数  + 最后登录时间 （3天）= 最近登录次数
	if($_GET['recentloginnum']){
		$recentloginnum = MooGetGPC('recentloginnum','integer','G');
		$nowtime = time();
		$threeday = $nowtime - 3*86400;
	
		switch($recentloginnum){
			case 1:
				$members_condition[] = " l.login_meb <= 10 and l.login_meb >=1 and l.last_login_time > '{$threeday}' ";		
				break;
			case 2:
				$members_condition[] = " l.login_meb <= 20 and l.login_meb >=11 and l.last_login_time > '{$threeday}' ";		
				break;
			case 3:
				$members_condition[] = " l.login_meb <= 21 and l.login_meb >=30 and l.last_login_time > '{$threeday}' ";		
				break;
			case 4:
				$members_condition[] = " l.login_meb >=30 and l.last_login_time > '{$threeday}' ";		
				break;	
		}					
		
	}

	if($_GET['gender'] != ''){
		$gender = MooGetGPC('gender','integer','G');
		if($gender == '1'){$gender = 0;}
		if($gender == '2'){$gender = 1;}
		$members_condition[] = " m.gender='{$gender}'";
	}
	if($_GET['regdate1']){
		$regdate1 = strtotime(MooGetGPC('regdate1','string','G'));
		$members_condition[] = " m.regdate>='{$regdate1}'";
	}
	if($_GET['regdate2']){
		$regdate2 = strtotime(MooGetGPC('regdate2','string','G'));
		$members_condition[] = " m.regdate<='{$regdate2}'";
	}
	
	
	
	if($_GET['s_cid'] != ''){
		$s_cid = MooGetGPC('s_cid','integer','G');
		$members_condition[] = " m.s_cid='{$s_cid}'";
	}
	
	if($_GET['next_contact_time1']){
		$next_contact_time1 = strtotime(MooGetGPC('next_contact_time1','string','G'));
		$member_admininfo_condition[] = " ma.next_contact_time>='{$next_contact_time1}'";
	}
	if($_GET['next_contact_time2']){
		$next_contact_time2 = strtotime(MooGetGPC('next_contact_time2','string','G'));
		$member_admininfo_condition[] = " ma.next_contact_time<='{$next_contact_time2}'";
	}
	
	if($_GET['age1']){
		$age1 = MooGetGPC('age1','integer','G');
		$tmp_start = gmdate('Y', time()) - $age1;
		$members_condition[] = " m.birthyear<='{$tmp_start}'";
	}
	if($_GET['age2']){
		$age2 = MooGetGPC('age2','integer','G');
		$tmp_end = gmdate('Y',time())-$age2;
		$members_condition[] = " m.birthyear>='{$tmp_end}'";
	}
	
	if($_GET['last_login_time1']){
		$last_login_time1 = strtotime(MooGetGPC('last_login_time1','string','G'));
		$members_condition[] = " l.last_login_time>='{$last_login_time1}'";
	}
	if($_GET['last_login_time2']){
		$last_login_time2 = strtotime(MooGetGPC('last_login_time2','string','G'));
		$members_condition[] = " l.last_login_time<='{$last_login_time2}'";
	}
	
	if($_GET['uploadtime']){
	    $uploadtime = date('Y/m/d',strtotime(MooGetGPC('uploadtime','string','G')));
		$members_condition[] = " p.pic_date='{$uploadtime}'";
	}

	if(!empty($_GET['province']) && $_GET['province'] != '-1'){
		$province = MooGetGPC('province','integer','G');
		$members_condition[] = " m.province='{$province}'";
	}
	
	if(!empty($_GET['city']) && $_GET['city'] != '-1'){
		$city = MooGetGPC('city','integer','G');
		$members_condition[] = " m.city='{$city}'";
	}
	
	if(!empty($_GET['salary']) && $_GET['salary'] != '-1'){
		$salary = MooGetGPC('salary','integer','G');
		$members_condition[] = " m.salary='{$salary}'";
	}
	
	if($_GET['sid']){
		 $sid = MooGetGPC('sid','integer','G');
		 $members_condition[] = " m.sid='{$sid}'";
	}
	
	if(!empty($_GET['master_member'])){
		$master_member = MooGetGPC('master_member','integer','G');
		$member_admininfo_condition[] = " ma.master_member='{$master_member}'";
	}
	
	
	if($_GET['telphone']){
		$telphone = trim(MooGetGPC('telphone','string','G'));
		$members_condition[] =  " m.telphone='{$telphone}'";
	}
	
	//当前在线1,2为一天，3为一周内，4为一周外
	if($_GET['online']){
		$online = MooGetGPC('online','string','G');
		switch($online){
			case 1:
				$time = time()-100;
				$member_admininfo_condition[] = " ma.real_lastvisit>{$time}";
			break;
			case 2:
				$time = time()-24*3600;
				$member_admininfo_condition[] = " ma.real_lastvisit>{$time}";
			break;
			case 3:
				$time1 = time()- 7*24*3600;
				$time2 = time() - 24*3600;
				$member_admininfo_condition[] = " ma.real_lastvisit > {$time1} and  ma.real_lastvisit < {$time2}";
			break;
			case 4:
				$time = time()- 7*24*3600;
				$member_admininfo_condition[] = " ma.real_lastvisit < {$time}";
			break;	
		}
			
	}
	
	
	if($_GET['username']){
		$username = trim(MooGetGPC('username','string','G'));
		$members_condition[] = " m.username LIKE '%$username%'";
	}
	
	if($_GET['nickname']){
		$nickname = trim(MooGetGPC('nickname','string','G'));
		$members_condition[] = " m.nickname LIKE '%$nickname%'";
	}
	
	//members_field表
	$members_field_sql = $members_field_left_join = '';

	if($_GET['truename']){
		$truename = trim(MooGetGPC('truename','string','G'));
		$memberfield_condition[] = " m.truename LIKE '%$truename%'";
	}

	if(!empty($_GET['workcityprovince1'])&&$_GET['workcityprovince1']!='-1'){
		$hometownprovince = MooGetGPC('workcityprovince1','integer','G');
		$memberfield_condition[] = " m.hometownprovince='{$hometownprovince}'";
	}
	if(!empty($_GET['workcitycity1'])&&$_GET['workcitycity1']!='-1'){
		$hometowncity = MooGetGPC('workcitycity1','integer','G');
		$memberfield_condition[] = " m.hometowncity='{$hometowncity}'";
	}
	
	if(!empty($_GET['ismok'])&&$_GET['ismok']!='-1'){
		$ismok = $_GET['ismok'];
		$smoking = implode(',',$ismok);
		$memberfield_condition[] = " m.smoking in($smoking)";
	}

	if(!empty($_GET['idrink'])&&$_GET['idrink']!='-1'){
		$idrink = $_GET['idrink'];
		$drinking = implode(',',$idrink);
		$memberfield_condition[] = " m.drinking in($drinking)";
	}

	if(!empty($_GET['child'])&&$_GET['child']!='-1'){
		$child = $_GET['child'];
		$children = implode(',',$child);
		$members_condition[] = " m.children in($children)";
	}

	if(!empty($_GET['wantchildren'])&&$_GET['wantchildren']!='-1'){
		$wantchild = $_GET['wantchildren'];
		$wantchildren = implode(',',$wantchild);
		$memberfield_condition[] = " m.wantchildren in($wantchildren)";
	}

	$height1 = $_GET['height1'];
	$height2 = $_GET['height2'];
	if(!empty($_GET['height1'])||!empty($_GET['height2'])){
		if ($height1 && $height2 && ($height2 > $height1)) {
		   $members_condition[] = ' (m.height >= ' . $height1 . ' AND m.height <= ' . $height2 . ')';
		} else if ($height1 && $height2 && ($height2 < $height1)) {
		   $members_condition[] = ' (m.height <= ' . $height1 . ' AND m.height >= ' . $height2 . ')';
		} else if ($height1 && !$height2) {
			$members_condition[] = ' m.height >= ' . $height1;
		} else if ($height2 && !$height1) {
			$members_condition[] = ' m.height <=' . $height2;
		} else if ($height1 && $height2 && $height1 == $height2) {
			$members_condition[] = ' m.height =' . $height1;
		}
	}

	$weight1 = $_GET['weight1'];
	$weight2 = $_GET['weight2'];
	if(!empty($_GET['weight1'])||!empty($_GET['weight2'])){
		if ($weight1 && $weight2 && ($weight2 > $weight1)) {
			$memberfield_condition[] = ' (m.weight >= ' . $weight1 . ' AND m.weight <= ' . $weight2 . ')';
		} else if ($weight1 && $weight2 && ($weight2 < $weight1)) {
			$memberfield_condition[] = ' (m.weight <= ' . $weight1 . ' AND m.weight >= ' . $weight2 . ')';
		} else if ($weight1 && !$weight2 ) {
			$memberfield_condition[] = ' m.weight >= ' . $weight1;
		} else if ($weight2 && !$weight1) {
			$memberfield_condition[] = ' m.weight <=' . $weight2;
		} else if ($weight1 && $weight2 && $weight1 == $weight2) {
			$memberfield_condition[] = ' m.weight =' . $weight1;
		}
	}

	
	if(!empty($_GET['body'])&&$_GET['body']!='0'){
		$body = $_GET['body'];
		$body = implode(',',$body);
		$memberfield_condition[] = " m.body in($body)";
	}

	if(!empty($_GET['education'])&&$_GET['education']!='0'){
		$education = $_GET['education'];
		$education = implode(',',$education);
		$members_condition[] = " m.education in($education)";
	}
	
	if(!empty($_GET['occupation'])&&$_GET['occupation']!='0'){
		$occupation = $_GET['occupation'];
		$occupation = implode(',',$occupation);
		$memberfield_condition[] = " m.occupation in($occupation)";
	}

	if(!empty($_GET['house'])&&$_GET['house']!='0'){
		$house = $_GET['house'];
		$house = implode(',',$house);
		$members_condition[] = " m.house in($house)";
	}
	
	if(!empty($_GET['vehicle'])&&$_GET['vehicle']!='0'){
		$vehicle = $_GET['vehicle'];
		$vehicle = implode(',',$vehicle);
		$memberfield_condition[] = " m.vehicle in($vehicle)";
	}
	
	if(!empty($_GET['corptp'])&&$_GET['corptp']!='0'){
		$corptp = $_GET['corptp'];
		$corptype = implode(',',$corptp);
		$memberfield_condition[] = " m.corptype in($corptype)";
	}
	
	if(!empty($_GET['family'])&&$_GET['family']!='0'){
		$family = $_GET['family'];
		$family = implode(',',$family);
		$memberfield_condition[] = " m.family in($family)";
	}
	
	
	
	if($_GET['qq']){
		$qq = $_GET['qq'];
		$sql_qq = "select qq from {$GLOBALS['dbTablePre']}members_base where qq='{$qq}'";
		$res_qq = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql_qq,true);
		if(!empty($res_qq['qq'])){
			$memberfield_condition[] = " b.qq='{$qq}'";
		}else{
			$members_condition[] = " m.username LIKE '%$qq%'";
		}
	}
	
     $callno=MooGetGPC('callno','string','G');
    if(!empty($callno)) $memberfield_condition[]="b.callno='{$callno}'";
	
	
	$members_sql = $member_admininfo_sql = $memberfield_sql = $sql_where = '';
	
	//members表
	if(!empty($members_condition)){
		$members_sql = implode(' AND ',$members_condition);
		//普通客服和组长搜索不到封锁的和关闭资料的会员
		if(in_array($GLOBALS['groupid'],$GLOBALS['admin_all_group'])||in_array($GLOBALS['groupid'],$GLOBALS['general_service'])){
			$members_sql=$members_sql ? $members_sql." and ((m.showinformation=1 and m.is_lock=1 ) or (m.usertype=1))" : "((m.showinformation=1 and m.is_lock=1 ) or (m.usertype=1))" ;
		}
	}
	
	if(!empty($member_admininfo_condition)){
		$member_admininfo_sql = implode(' AND ',$member_admininfo_condition);
	}

	if(!empty($memberfield_condition)){
//		$members_field_left_join = "LEFT JOIN {$GLOBALS['dbTablePre']}members_base b ON m.uid=b.uid";
		$memberfield_sql = implode(' AND ',$memberfield_condition);
	}
	
	if($members_sql && $member_admininfo_sql && $memberfield_sql){
		$sql_where = ' WHERE '.$members_sql .' AND '.$member_admininfo_sql .' AND '. $memberfield_sql;
	}elseif($members_sql && $member_admininfo_sql){
		$sql_where = ' WHERE '.$members_sql .' AND '.$member_admininfo_sql;
	}elseif($member_admininfo_sql && $memberfield_sql){
		$sql_where = ' WHERE '.$member_admininfo_sql .' AND '.$memberfield_sql;
	}elseif($members_sql && $memberfield_sql){
		$sql_where = ' WHERE '.$members_sql .' AND '.$memberfield_sql;
	}elseif($members_sql){
		$sql_where = ' WHERE '.$members_sql;
	}elseif($member_admininfo_sql){
		$sql_where = ' WHERE '.$member_admininfo_sql;
	}elseif($memberfield_sql){
		$sql_where = ' WHERE '.$memberfield_sql; //members_field表
	}
   
	if(!empty($members_sql) || !empty($member_admininfo_sql) || !empty($memberfield_sql)){
	    
        
		$sql = "SELECT m.uid FROM {$GLOBALS['dbTablePre']}members_search m LEFT JOIN {$GLOBALS['dbTablePre']}member_admininfo ma ON m.uid=ma.uid LEFT JOIN {$GLOBALS['dbTablePre']}members_base b ON ma.uid=b.uid  left join {$GLOBALS['dbTablePre']}members_login as l on ma.uid=l.uid left join web_full_log as f on m.uid=f.uid left join web_pic as p on m.uid=p.uid {$sql_where}";
	    $r=$GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
		$u=array();
		if(!empty($r)){
		    foreach($r as $v){
		        array_push($u,$v['uid']);
            }
        }
        $u=array_unique($u);
        $total=sizeof($u);
        $ul=implode(',',$u);
		
		$sql = "SELECT m.uid,m.username,m.gender,m.birthyear,b.mainimg,m.telphone,m.salary,m.s_cid,m.is_lock,m.province,m.city,b.allotdate,m.regdate,m.sid,m.usertype,l.last_login_time,ma.next_contact_time,ma.effect_grade,ma.old_sid,ma.real_lastvisit,f.action_time FROM {$GLOBALS['dbTablePre']}members_search m LEFT JOIN {$GLOBALS['dbTablePre']}member_admininfo ma ON m.uid=ma.uid LEFT JOIN {$GLOBALS['dbTablePre']}members_base b ON ma.uid=b.uid  left join {$GLOBALS['dbTablePre']}members_login as l on ma.uid=l.uid left join web_full_log as f on m.uid=f.uid  where m.uid in ($ul) LIMIT {$offset},{$limit}";
		
        
		// echo $sql;
		$members = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);

		$member_list=$members;
		if(!empty($_GET['order_tel'])){
			$tel=get_telphone($members);
			if($members){
				foreach($members as $user){
					$user['num']=$tel[$user['telphone']];
					$member_list[]=$user;
				}
				//当点击同号手机数时排序
				
					foreach ($member_list as $key => $row) {
						$edition[$key] = $row[num];
					}
					array_multisort($edition, SORT_DESC, $member_list);//二维数组排序
			}
		}
		//note 获得当前的url 去除多余的参数page=
		$currenturl = $_SERVER["REQUEST_URI"]; 
	
		$currenturl2 = preg_replace("/(&page=\d+)/","",$currenturl);

		$page_links = multipage( $total, $page_per, $page, $currenturl2 );
	}
    
	//所有客服列表
	/* $sql = "SELECT * FROM {$GLOBALS['dbTablePre']}admin_user ORDER BY uid ASC";
	$kefu_list = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
	
 	//所管理的客服id列表
   $myservice_idlist = get_myservice_idlist();
   $myservice_sid_arr = array();
   if($myservice_idlist != 'all' && $myservice_idlist!=''){
   		$myservice_sid_arr = explode(',',$myservice_idlist);
       foreach($myservice_sid_arr as $key=>$value){
           if(empty($value)){
               unset($myservice_sid_arr[$key]);
           }
       }
   		$sql = "SELECT * FROM {$GLOBALS['dbTablePre']}admin_user WHERE uid IN (".implode(',',$myservice_sid_arr).") ORDER BY uid ASC";
		$kefu_list = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
   } */
	$kefu_list = get_kefulist();	
    require "data/kefulist_cache_.php";

	require adminTemplate("allmember_advancesearch");
}

function allmember_chathistory(){
	$uid = MooGetGPC('uid','integer','G');
	$fromid = MooGetGPC('fromid','integer','G');
	$type = MooGetGPC('type','string','G');
	
	//note 分页处理
	$page = max(1,MooGetGPC('page','integer'));
    $limit = 15;
    $offset = ($page-1)*$limit;
  
	/*//note 查询语句
    $sql = "SELECT COUNT(*) num FROM {$GLOBALS['dbTablePre']}service_chat WHERE (s_uid = {$uid} AND s_fromid = {$fromid}) OR (s_uid = {$fromid} AND s_fromid = {$uid})";
	//echo $sql;
	$total = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
	//	$sql = "SELECT * FROM {$GLOBALS['dbTablePre']}service_chat WHERE (s_uid = {$uid} AND s_fromid = {$fromid}) OR (s_uid = {$fromid} AND s_fromid = {$uid}) ORDER BY s_id ASC LIMIT {$offset},{$limit}"; // original file
	$sql = "SELECT s_time, is_server, s_fromid, s_uid, s_content FROM {$GLOBALS['dbTablePre']}service_chat WHERE (s_uid = {$uid} AND s_fromid = {$fromid}) OR (s_uid = {$fromid} AND s_fromid = {$uid}) ORDER BY s_id ASC LIMIT {$offset},{$limit}"; // updated file
	$user_arr = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
	*/
    $data = getChatListById($uid,$fromid,$page,$limit);

    $total = $data['total'];
    $user_arr = $data['data'];
    foreach($user_arr as $k=>$v){
    	$chats[$k]['s_id'] = $v['id'];
    	$chats[$k]['s_uid'] = $v['toid'];
    	$chats[$k]['s_fromid'] = $v['fromid'];
    	$chats[$k]['s_content'] = $v['content'];
    	$chats[$k]['s_time'] = $v['time'];
    	$chats[$k]['s_status'] = $v['status'];
    	$chats[$k]['is_server'] = $v['serverid'];
    	$chats[$k]['dealstate'] = $v['isdeal'];
    }
    $user_arr = $chats;
  	  
	foreach($user_arr as $k => $v) {
		//note 查询出发送用户的昵称，性别
		$sql = "SELECT nickname,gender FROM {$GLOBALS['dbTablePre']}members_search WHERE uid = '{$v[s_fromid]}'";
		$send_user = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
		$user_arr[$k]['send_nickname'] = $send_user['nickname'];
		$user_arr[$k]['send_gender'] = $send_user['gender'];
		
		//note 查询出接受用户的昵称，性别
		$sql = "SELECT nickname,gender FROM {$GLOBALS['dbTablePre']}members_search WHERE uid = '{$v[s_uid]}'";
		$receive_user = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
		$user_arr[$k]['receive_nickname'] = $receive_user['nickname'];
		$user_arr[$k]['receive_gender'] = $receive_user['gender'];
	}
    
	//note 获得当前的url 去除多余的参数
	$currenturl = $_SERVER["REQUEST_URI"]; 
	$currenturl = preg_replace("/(&page=\d+)/","",$currenturl);
	$currenturl = preg_replace("/(&type=chathistory)/","",$currenturl);
	$currenturl2 = $currenturl;
	$currenturl = $currenturl."&type=$type";
	
   	$pages = multipage( $total['num'], $limit, $page, $currenturl );
   	
   	//note 跳转到某一页
   	$page_num = ceil($total['num']/$limit);
	
	
	//note 插入日志
	serverlog(1,$GLOBALS['dbTablePre'].'service_chat',"{$GLOBALS['username']}查看聊天记录列表", $GLOBALS['adminid']);
	

	require adminTemplate("allmember_chathistory");
}

function allmember_record(){
	$sid_get = MooGetGPC('sid', 'integer');
	
	$sql = "SELECT ccid FROM {$GLOBALS['dbTablePre']}admin_user WHERE uid = '{$sid_get}'";
	$row = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
	$record_id =  $row['ccid'];	
	//$sid = $sid_get + 800;
	$sid = $record_id;
	
	require adminTemplate("allmember_record");
}

function allmember_listen_open(){
	MooClearCookie();
	$lurl = MooGetGPC('lurl', 'string');
	$len = MooGetGPC('len', 'string');
	$title = MooGetGPC('title', 'string');
	$recodetime = MooGetGPC('recodetime', 'string');
	$title = $GLOBALS['kefu_arr'][$title];
	$afterurl = explode(":",$lurl);
	$url = 'http://192.168.0.170'.$afterurl[1];
	require adminTemplate("allmember_listen_open");	
}

function allmember_same_telphone(){
	$telphone = MooGetGPC('telphone','string','G');
	// if(empty($telphone)) return;
	
	
	// $page = 1;
	// $pagesize = 20;
	// if(isset($_GET['page'])) $page = $_GET['page'];
	$offset = ($page -1)*$pagesize; 
//	$sql = "SELECT * FROM {$GLOBALS['dbTablePre']}members  WHERE is_lock=1 and  telphone='{$telphone}'"; // original file
//	$sql = "SELECT * FROM {$GLOBALS['dbTablePre']}members_search WHERE is_lock=1 and telphone='{$telphone}' limit 10";
	// $sql = "SELECT m.uid, m.gender, m.username, m.birthyear, m.s_cid, b.mainimg, m.salary, m.province, m.city, bi.effect_grade, b.allotdate, m.regdate, m.usertype, l.login_meb, m.sid, m.is_lock, m.telphone FROM {$GLOBALS['dbTablePre']}members_search m left join {$GLOBALS['dbTablePre']}members_base b on m.uid=b.uid left join {$GLOBALS['dbTablePre']}members_login l on m.uid=b.uid left join {$GLOBALS['dbTablePre']}member_backinfo bi on m.uid=bi.uid WHERE m.is_lock=1 and  m.telphone='{$telphone}' limit $offset,$pagesize"; // updated file
    
	if(MooGetGPC('telphone','string','G')){
       $sql = "SELECT m.uid, m.gender, m.username, m.birthyear, m.s_cid, b.mainimg, m.salary, m.province, m.city,  b.allotdate, m.regdate, m.usertype,  m.sid, m.is_lock  FROM {$GLOBALS['dbTablePre']}members_search m left join {$GLOBALS['dbTablePre']}members_base b on m.uid=b.uid   WHERE m.is_lock=1 and  (m.telphone='{$telphone}'  or b.callno='{$telphone}') "; // updated file
 
	   $member_list = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
    }
	require adminTemplate("allmember_same_telphone");	
}

function allmember_del_same_telphone(){
	$uid = MooGetGPC('uid','integer','G');
	$telphone = MooGetGPC('telphone','string','G');
	if(empty($telphone)) return;
	//$sql = "DELETE FROM {$GLOBALS['dbTablePre']}members WHERE uid='{$uid}'";
	$sql = "SELECT sid FROM {$GLOBALS['dbTablePre']}members_search WHERE uid='{$uid}'";
	$sid = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
	$now = time();
	$sql = "UPDATE {$GLOBALS['dbTablePre']}members_search  SET sid='0',is_lock=0 WHERE uid='{$uid}' ";
	searchApi('members_man members_women')->updateAttr(array('sid','is_lock'),array($uid => array(0,0)));
	
	$GLOBALS['_MooClass']['MooMySQL']->query($sql);
	if(MOOPHP_ALLOW_FASTDB) {
		$value = array();
		$value['sid']=0;
		$value['is_lock']=0;
		$value['updatetime'] = $now;
		MooFastdbUpdate('members_search','uid',$uid,$value);
	}
	
	//对应的维护会员总数减1
	$sql = "UPDATE {$GLOBALS['dbTablePre']}admin_user SET member_count=member_count-1 WHERE uid='{$sid['sid']}'";
	$GLOBALS['_MooClass']['MooMySQL']->query($sql);
	
	serverlog(2,$GLOBALS['dbTablePre'].'members',"{$GLOBALS['username']}客服将有同号手机会员{$uid}删除",$GLOBALS['adminid'],$uid);
	salert('删除成功','index.php?action=allmember&h=same_telphone&telphone='.$telphone);
}

//输入页数批量分配会员
function allmember_changeusersid_bat(){//未完善
	//////////////现在暂不需要//////////////
	$uid = MooGetGPC('kefuuid','integer','G');//客服uid
	$s_page = MooGetGPC('s_page','integer','G');
	$e_page = MooGetGPC('s_page','integer','G');
	

	$re = $GLOBALS['_MooClass']['MooMySQL']->getOne("SELECT groupid,member_count,allot_member,allot_time FROM {$GLOBALS['dbTablePre']}admin_user WHERE uid='{$uid}'",true);
	if($re['member_count'] >= 1000){//note 分配限制
		salert('此客服已经超过了最多1000个会员的限制，不能再分配');exit;
	}
}

//note 售后
function allmember_sellafter(){
	global $reprating;
	$ispost = MooGetGPC('ispost','integer','P');
	if($ispost){
		$adminid = $GLOBALS['adminid'];
		$manager = $GLOBALS['username'];
		$effect = MooGetGPC('effect','integer','P');
		$isdanger = MooGetGPC('isdanger','integer','P');
		$awoketime = strtotime(MooGetGPC('time','string','P'));
		$comment = MooGetGPC('sellcomment','string','P');
		$uid = MooGetGPC('uid','integer','P');
		$fromid = MooGetGPC('chathavenum','integer','P');
		$renewals_status = MooGetGPC('renewals_status','integer','P');
		$member_progress = MooGetGPC('member_progress','integer','P');
		$phonecall = MooGetGPC('phonecall','integer','P');
		//note 手机号码
		$telphone = MooGetGPC('telphone','string','P');
		//采集会员使用手册
		$usertype = MooGetGPC('usertype','string','P');
		//if($usertype == 3){
//			$sql = "select mid from {$GLOBALS['dbTablePre']}member_admininfo where uid={$uid}";
//			$user_member = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
//			if($user_member){
//				if(!empty($user_member['mid']) && $user_member['mid'] != $adminid){
//					salert('此会员其他客服正在使用！','index.php?action=allmember&h=view_info&uid='.$uid);
//				}elseif($user_member['mid'] == 999999999){
//					salert('此会员以被封锁！','index.php?action=allmember&h=view_info&uid='.$uid);
//				}elseif(empty($user_member['mid'])){
//					$sql = "update {$GLOBALS['dbTablePre']}member_admininfo set mid={$adminid} where uid={$uid}";
//					$GLOBALS['_MooClass']['MooMySQL']->query($sql);
//				}
//			}//else{
////				$sql = "insert into {$GLOBALS['dbTablePre']}member_admininfo set uid='{$uid}',mid='{$adminid}',effect_grade=1";
////				$GLOBALS['_MooClass']['MooMySQL']->query($sql);	
////			}
//		}
		if($fromid!=0){
			$sql = "SELECT max(s_time) s_time FROM {$GLOBALS['dbTablePre']}service_chat WHERE s_uid='{$uid}' AND s_fromid='{$fromid}'";
			$res = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
			$s_time = $res['s_time'];
			$time = time();
			$comtime = strtotime('-2 hours',$time);
			if($s_time =='' || $s_time<$comtime){
				//添加操作日志
				serverlog(4,$GLOBALS['dbTablePre']."members_backinfo","{$GLOBALS['username']}保存{$uid}的售后信息",$GLOBALS['adminid'],$uid);
				salert("你没有聊天,售后信息保存失败！","index.php?action=allmember&h=view_info&uid={$uid}");
				exit;
			}else{
				//聊天的数量
				$sql_chat = "SELECT chat FROM {$GLOBALS['dbTablePre']}member_admininfo WHERE uid='{$uid}'";
				$res_chat = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql_chat,true);
				//note 聊天
				$chat = intval($res_chat['chat'])+1;
			}
		}else{
			//聊天的数量
			$sql_chat = "SELECT chat FROM {$GLOBALS['dbTablePre']}member_admininfo WHERE uid='{$uid}'";
			$res_chat = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql_chat,true);
			//note 聊天
			$chat = intval($res_chat['chat']);
		}

		//后台统计
		//推荐的数量
		$sql_recommend = "SELECT count(*) count FROM {$GLOBALS['dbTablePre']}member_sellinfo WHERE uid='{$uid}' AND effect_grade='1'";
		$res_recommend = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql_recommend,true);
		//邮件的数量
		$sql_email = "SELECT count(*) as count FROM {$GLOBALS['dbTablePre']}services s WHERE s.s_uid='{$uid}' AND s.s_cid in(1,2) AND s.is_server='1'";
		$res_email = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql_email,true);
		//委托的数量
		$sql_commiss = "SELECT count(*) as count FROM {$GLOBALS['dbTablePre']}service_contact s WHERE s.you_contact_other = '{$uid}'";
		$res_commiss = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql_commiss,true);
		//鲜花的数量
		$sql_rose = "SELECT SUM(fakenum) sum FROM {$GLOBALS['dbTablePre']}service_rose WHERE receiveuid = '{$uid}'";
		$res_rose = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql_rose,true);
		//秋波的数量
		$sql_leer = "SELECT SUM(fakenum) sum FROM {$GLOBALS['dbTablePre']}service_leer WHERE receiveuid = '{$uid}'";
		$res_leer = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql_leer,true);
		
		
		//note 邮件
		$email = intval($res_email['count']);
		//note 委托
		$commiss = intval($res_commiss['count']);
		//note 鲜花
		$rose = intval($res_rose['sum']);
		//note 秋波
		$leer = intval($res_leer['sum']);
		//note 推荐
		$recommend = $effect == '1' ? (intval($res_recommend['count'])+1) : intval($res_recommend['count']);
//		echo '推荐：'.$recommend.'聊天：'.$chat.'邮件：'.$email.'委托：'.$commiss.'鲜花：'.$rose.'秋波：'.$leer;die;
		
		//note 查找所属客服id
		$sql_id = "SELECT sid,bgtime FROM {$GLOBALS['dbTablePre']}members_search WHERE uid='{$uid}'";
		$res_id = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql_id,true);
		$sid='';
		if(!empty($res_id)){
			$sid = $res_id['sid'];
		}else{
			$groupid = $GLOBALS['admin_aftersales_service'][0];
			$sql_sid = "SELECT uid FROM {$GLOBALS['dbTablePre']}admin_user WHERE groupid='{$groupid}'";
			$res_sid = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql_sid,0,0,0,true);
			$sid = $res_sid[0]['uid'];
		}

		$dateline = time();
		$sql = "INSERT INTO {$GLOBALS['dbTablePre']}member_sellinfo SET mid='{$adminid}',manager='{$manager}',sid='{$sid}',uid='{$uid}',effect_grade='{$effect}',next_contact_time='{$awoketime}',comment='{$comment}',dateline='{$dateline}',phonecall='{$phonecall}'";
	//	echo $sql;die;
		$res = $GLOBALS['_MooClass']['MooMySQL']->query($sql);
		//note 更新危险等级
		$sql_danger = "UPDATE {$GLOBALS['dbTablePre']}member_admininfo SET dateline='{$dateline}',renewalstatus='{$renewals_status}',memberprogress='{$member_progress}',danger_leval='{$isdanger}',recommend='{$recommend}',chat='{$chat}',email='{$email}',rose='{$rose}',mandate='{$commiss}',leer='{$leer}' WHERE uid='{$uid}'";
		$res_danger = $GLOBALS['_MooClass']['MooMySQL']->query($sql_danger);

		//note 插入时间段
		$timenow = time();
		$period = $timenow-$res_id['bgtime'];
		//note 更新admininfo表的finished
		$pre_finish = "UPDATE {$GLOBALS['dbTablePre']}member_admininfo SET finished=";
		$where_finish = " WHERE uid='{$uid}'";
		
		//note 查找admininfo表的finished完成的状态
		$res_finished = $GLOBALS['_MooClass']['MooMySQL']->getOne("SELECT finished FROM {$GLOBALS['dbTablePre']}member_admininfo".$where_finish,true);
		$res_admininfo = $res_finished['finished'];
		
		//note 此会员的升级后三大步骤 完成的
		//note 模拟
		$simulated_count_all = $chat+$email;
		//note 关注
		$oncern_count_all = $commiss+$rose+$leer;
//			echo $count_recommend,$simulated_count_all,$oncern_count_all;
		if($res_admininfo == '0'){
			if($recommend>=1 && $simulated_count_all>=2 && $oncern_count_all>=3){
				$sql_finish = $pre_finish."'1',period='{$period}'".$where_finish;
				$res_finish = $GLOBALS['_MooClass']['MooMySQL']->query($sql_finish);
			}
		}

		//查找管理员所属组
		$sql_adminid = "SELECT sid FROM {$GLOBALS['dbTablePre']}members_search WHERE uid={$uid}";
		$res_adminid = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql_adminid,true);
		$adminid = $res_adminid['sid'];
		$sql_admin = "SELECT groupid FROM {$GLOBALS['dbTablePre']}admin_user WHERE uid='{$adminid}'";
		$res_admin = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql_admin,true);
		$groupid = $res_admin['groupid'];
		if($awoketime>$dateline){
			//note 记录下次联系时间
			$title = '与'.$uid.'的下次联系时间是'.date("Y-m-d H:i:s",$awoketime);
			$sql_atime = "INSERT INTO {$GLOBALS['dbTablePre']}admin_remark SET sid='{$adminid}',groupid='{$groupid}',title='{$title}',content='{$title}',awoketime='{$awoketime}',send_id='{$adminid}'";
//			echo $sql_atime;die;
			$res_atime = $GLOBALS['_MooClass']['MooMySQL']->query($sql_atime);
		}

//		$phonecall = 1;
		//发送短信评价
		if($phonecall=='1'){
			if($telphone!='0'){
				$arrtxt = implode(" ",$reprating);
				$mes = "回复数字对专属红娘进行评价：".$arrtxt." 。谢谢！回复免费。";
				$ret = SendMsg($telphone,$mes);
				if($ret!==false){
					$sql = "INSERT INTO {$GLOBALS['dbTablePre']}smslog(sid,uid,content,sendtime) VALUES('{$GLOBALS['adminid']}','{$uid}','{$mes}','{$dateline}')";
					$sid = $GLOBALS['_MooClass']['MooMySQL']->query($sql);
				}else{
					$sql = "INSERT INTO {$GLOBALS['dbTablePre']}smslog(sid,uid,content,sendtime) VALUES('{$GLOBALS['adminid']}','{$uid}','短信发送失败','{$dateline}')";
					$sid = $GLOBALS['_MooClass']['MooMySQL']->query($sql);
				}
			}
		}
		
		//添加操作日志
		serverlog(4,$GLOBALS['dbTablePre']."members_backinfo","{$GLOBALS['username']}保存{$uid}的售后信息",$GLOBALS['adminid'],$uid);
		salert("售后信息保存成功！","index.php?action=allmember&h=view_info&uid={$uid}");
	}

}

/**
 * 优质会员--增加sphinx程序
 */
function allmember_excellent(){
	$qs = trim(MooGetGPC('quicksearch','integer','G'));
	//$qs = 0;
	if(!$qs){
		allmember_excellent_sql();
		exit;
	}else{
		global  $rsort;
		$total = 0;
		$page_per = 20;
		$page = MooGetGPC('page','integer','G');
		$page = $page ? $page : 1;
		$offset = ($page-1)*$page_per;
    
	    //允许的排序方式
		$allow_order = array(
		    'uid' => 'ID',
		    'birthyear' => '年龄',
		    'images_ischeck' => '照片',
		    'salary' => '收入',
		    'allotdate' => '分配时间',
		    'regdate' => '注册时间',
		    'lastvisit' => '最后登陆时间',
		    'login_meb' => '登陆次数',
		    'sid' => '工号/客服',
		    'is_lock' => '是否锁定',
		    'real_lastvisit' => '在线状态'
		);
		
		$keyword = trim(MooGetGPC('keyword','string','G'));
	    $choose = MooGetGPC('choose','string','G');
	    $where = '';
	    $cond = array();

	    if(!empty($choose) && !empty($keyword)){
	    	if($choose == 'uid') $cond[] = array('@id',$keyword,false);
	    	else $cond[] = array($choose,$keyword,false);
	    	$where = "m.$choose='$keyword'"; 
	    }
	    $order = trim(MooGetGPC('order','string','G'));

	    if($order == 'salary' || $order == 'allotdate﻿' || $order == 'real_lastvisit﻿' || $order == 'login_meb﻿'){//sphinx不能做排序
	    	allmember_excellent_sql();
			exit;
	    }
	    $sort_str = '';
	    $query_builder = get_query_builder($where, $allow_order, '', '', 'regdate', 'desc', $rsort);

	    $sql_sort = $query_builder['sort'];
	    $sort_arr = $query_builder['sort_arr'];
	    $rsort_arr = $query_builder['rsort_arr'];
	 	if($sql_sort){
	 		if(strpos($sql_sort,'uid desc')) $sort_str = '@id desc';
	 		elseif(strpos($sql_sort,'uid asc')) $sort_str = '@id asc';
	 		else $sort_str = substr($sql_sort,9);
	 	}
	    $member_list=array();
		
		$field = trim(MooGetGPC('f','string','G'));

		if(!$field) $field = 'age';

		$result = '';
		if($field != 'isattend'){
			if(isset($query_builder['sql_sort'])){
				$n = 0;
			    foreach($query_builder['rsort_arr'] as $k=>$v){
			    	if($k == 'uid') $k='@id';
			    	if($n<5){
			    		if(!$sort_str) $sort_str = $k.' '.$v;
			    		else $sort_str .= ','.$k.' '.$v;
			    	}else break;
			    	$n++;
			    }
			}
			$index = 'members_man members_women';
			$limit = array($offset,$page_per);
			$cl = searchApi($index);
		    if($field == 'age'){
		    	$year = date('Y',time())-25;
		    	$cond[] = array('birthyear',array($year,99),false);
		    }elseif($field == 'sal'){
		    	$cond[] = array('salary','4|5|6|7|8|9',false);
		    }elseif($field == 'province'){
		    	$cond[] = array('province','10102000|10103000|10101201|10101002|10101000|10105000',false);
		    }elseif($field == 'city'){
		    	$cond[] = array('city','10127001',false);
		    }
	    	
		    $rs = $cl -> getResult($cond,$limit,$sort_str);
		    $ids = $cl -> getIds();
		    if(is_array($ids) && !empty($ids)){
		    	$total = $rs['total_found'];
		    	$ids_str = implode(',',$ids);
			    $sql = "SELECT m.uid,m.nickname,m.gender,m.birthyear,m.salary,m.province,m.city,m.s_cid,ml.lastvisit,mb.source,ml.login_meb,mb.allotdate,m.regdate,m.sid,m.is_lock,a.isattend FROM {$GLOBALS['dbTablePre']}members_search m 
		            LEFT JOIN {$GLOBALS['dbTablePre']}members_base mb on m.uid=mb.uid LEFT JOIN {$GLOBALS['dbTablePre']}members_login ml on m.uid= ml.uid LEFT JOIN {$GLOBALS['dbTablePre']}ahtv_reguser a
		            ON m.uid=a.uid where m.uid in ($ids_str)";
			    $result=$GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
		    }
		}elseif($field == 'isattend'){
			$where_sql = "a.isattend=1";
			if($where) $where_sql = $where.' and '.$where_sql;
			$sql_count = "SELECT count(a.uid) as c FROM {$GLOBALS['dbTablePre']}members_search m 
			            LEFT JOIN {$GLOBALS['dbTablePre']}members_base mb on m.uid=mb.uid LEFT JOIN {$GLOBALS['dbTablePre']}members_login ml on m.uid= ml.uid LEFT JOIN {$GLOBALS['dbTablePre']}ahtv_reguser a
			            ON m.uid=a.uid where $where_sql";
			$rs = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql_count,true);
			if(isset($rs['c']) && $rs['c']) $total = $rs['c'];
			if($total>0){
				$sql2 = "SELECT m.uid,m.nickname,m.gender,m.birthyear,m.salary,m.province,m.city,m.s_cid,ml.lastvisit,mb.source,ml.login_meb,mb.allotdate,m.regdate,m.sid,m.is_lock,a.isattend FROM {$GLOBALS['dbTablePre']}members_search m 
				            LEFT JOIN {$GLOBALS['dbTablePre']}members_base mb on m.uid=mb.uid LEFT JOIN {$GLOBALS['dbTablePre']}members_login ml on m.uid= ml.uid LEFT JOIN {$GLOBALS['dbTablePre']}ahtv_reguser a
				            ON m.uid=a.uid where $where_sql limit $offset,$page_per";
				$result=$GLOBALS['_MooClass']['MooMySQL']->getAll($sql2,0,0,0,true);
			}
		}else{
			allmember_excellent_sql();
			exit;
		}
		
		if(!empty($result)){
	        foreach($result as $v){
	            $grade=0;
	            //年龄
	            if (empty($v['birthyear'])){
	                $age=0;
	            }else{
	               $age=Date('Y')-$v['birthyear'];
	            }
	            if ($age>=25) $grade++;
	            
	            //收入
	            $salary=$v['salary'];
	            if($salary>=4)  $grade++;
	            
	            //地区
	            $province=$v['province'];
	            $city=$v['city'];
	            
	            $array_district=array("10102000","10103000","10101201","10101002","10105000");
	            
	            if(in_array($province, $array_district) || ($city=="10127001")) $grade++;
	            
	            if($v['isattend']) $grade++;
	            
	            if($grade){
	                $uid=$v['uid'];
	                $nickname=$v['nickname'];
	                $gender=$v['gender'];
	                $s_cid=$v['s_cid'];
	                $birthyear=$v['birthyear'];
	                $salary=$v['salary'];
	                $lastvisit=$v['lastvisit'];
	                $login_meb=$v['login_meb'];
	                $province=$v['province'];
	                $city=$v['city'];
	                $source=$v['source'];
	                $allotdate=$v['allotdate'];
	                $regdate=$v['regdate'];
	                $sid=$v['sid'];
	                $islock=$v['is_lock'];
	                $isattend=$v['isattend'];
	                $member_list[]=array('uid'=>$uid,'nickname'=>$nickname, 'gender'=>$gender,'s_cid'=>$s_cid,'birthyear'=>$birthyear,'salary'=>$salary,'lastvisit'=>$lastvisit,'login_meb'=>$login_meb,'province'=>$province,'city'=>$city,'source'=>$source,'allotdate'=>$allotdate,'regdate'=>$regdate,'sid'=>$sid,'islock'=>$islock,'grade'=>$grade,'isattend'=>$isattend);
	            }
	        }
	    }
	    
	    $page_total = max(1, ceil($total/$page_per));
	    $page = min($page, $page_total);
	    
	    //来源
	    if($member_list) {
	        foreach($member_list as $key => $user) {
	            if(preg_match_all("/(wf=\w+)&?|(st=\w+)&?/i", $user['source'], $matches)) {
	                $member_list[$key]['source'] = $matches[1][0]."<br />".$matches[2][1];
	            }
	        }
	    }

	    $currenturl = "index.php?action=allmember&h=excellent";//&province={$provinceL}";//.$link;
	    $currenturl1 = "index.php?action=allmember&h=excellent&choose={$choose}&keyword={$keyword}clear=1";
	    $page_links = multipage( $total, $page_per, $page, $currenturl1 );

	    $condition = array();
	    $condition['m.uid']=$condition['m.username']=$condition['m.nickname']=$condition['m.telphone']=$condition['m.sid'] = '';
	    //获取本次查询条件
	    $condition = array_merge($condition,  get_condition($where));

	    $title = '优质会员';
	    require_once(adminTemplate('allmember_excellent'));
	}
}

//优质会员
function allmember_excellent_sql(){
   
    global  $rsort;    
    
    //允许的排序方式
	$allow_order = array(
	    'uid' => 'ID',
	    'birthyear' => '年龄',
	    'images_ischeck' => '照片',
	    'salary' => '收入',
	    'allotdate' => '分配时间',
	    'regdate' => '注册时间',
	    'lastvisit' => '最后登陆时间',
	    'login_meb' => '登陆次数',
	    'sid' => '工号/客服',
	    'is_lock' => '是否锁定'
	);

    $year=Date('Y')-25;
    $sql_where = "  m.birthyear <=".$year."  or m.salary>=4 or m.province in ('10102000','10103000','10101201','10101002','10101000','10105000') or m.city='10127001' or a.isattend=1";

    
    //$condition[] = '';

    $keyword = trim(MooGetGPC('keyword','string','G'));
    $choose = MooGetGPC('choose','string','G');
    $where = '';
    if(!empty($choose) && !empty($keyword)){
      $where = " m.$choose = '$keyword'";  
    }

    $query_builder = get_query_builder($where, $allow_order, '', '', 'regdate', 'desc', $rsort);
 
    $member_list=array();
          
    if(empty($where)){
      
    	$where=$sql_where;
	    $sql_sort = $query_builder['sort'];
	    $sort_arr = $query_builder['sort_arr'];
	    $rsort_arr = $query_builder['rsort_arr'];
	    

	    
	    $sql="select count(m.uid) as c from web_members_search   m 
	            LEFT JOIN web_ahtv_reguser a
	            ON m.uid=a.uid  where $where ";
	    
	    $members_total = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
	    $total = $members_total['c'];
	    
	    $page_per = 20;
	    $limit = 20;
	    /*if($_GET['page_per']){
	        $page_per = MooGetGPC('page_per','integer','G');
	        $limit = $page_per;
	        $link = "&page_per=$page_per";
	    }*/
	    $page = get_page();
	    $page_total = max(1, ceil($total/$limit));
	    $page = min($page, $page_total);
	    $offset = ($page-1)*$limit;
	    
	
	    
	    $sql = "SELECT m.uid,m.nickname,m.gender,m.birthyear,m.salary,m.province,m.city,m.s_cid,ml.lastvisit,mb.source,ml.login_meb,mb.allotdate,m.regdate,m.sid,m.is_lock,a.isattend FROM {$GLOBALS['dbTablePre']}members_search m 
	            LEFT JOIN {$GLOBALS['dbTablePre']}members_base mb on m.uid=mb.uid LEFT JOIN {$GLOBALS['dbTablePre']}members_login ml on m.uid= ml.uid LEFT JOIN {$GLOBALS['dbTablePre']}ahtv_reguser a
	            ON m.uid=a.uid where $where $sql_sort LIMIT {$offset},{$limit}";
    
    }else{
    	
        $sql_sort = $query_builder['sort'];
        $sort_arr = $query_builder['sort_arr'];
        $rsort_arr = $query_builder['rsort_arr'];

        
        $sql="select count(m.uid) as c from web_members_search m left join web_ahtv_reguser a on m.uid=a.uid  where   $where  and ($sql_where) ";
        
//        echo $sql;exit;
        $members_total = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
        $total = $members_total['c'];
        
        $page_per = 20;
        $limit = 20;
       
        $page = get_page();
        $page_total = max(1, ceil($total/$limit));
        $page = min($page, $page_total);
        $offset = ($page-1)*$limit;
        
    
        
       /* $sql = "SELECT m.uid,m.nickname,m.birthyear,m.salary,m.province,m.city,m.s_cid,m.lastvisit,m.source,m.login_meb,m.allotdate,m.regdate,m.sid,m.is_lock,a.isattend FROM {$GLOBALS['dbTablePre']}members m 
                LEFT JOIN {$GLOBALS['dbTablePre']}ahtv_reguser a
                ON m.uid=a.uid  where  {$where}  and ({$sql_where}) {$sql_sort}   LIMIT {$offset},{$limit}";*/
        $sql = "SELECT m.uid,m.nickname,m.gender,m.birthyear,m.salary,m.province,m.city,m.s_cid,ml.lastvisit,mb.source,ml.login_meb,mb.allotdate,m.regdate,m.sid,m.is_lock,a.isattend FROM {$GLOBALS['dbTablePre']}members_search m 
	            LEFT JOIN {$GLOBALS['dbTablePre']}members_base mb on m.uid=mb.uid LEFT JOIN {$GLOBALS['dbTablePre']}members_login ml on m.uid= ml.uid LEFT JOIN {$GLOBALS['dbTablePre']}ahtv_reguser a
	            ON m.uid=a.uid where {$where}  and ({$sql_where}) {$sql_sort}     LIMIT {$offset},{$limit}";
    }
    //echo $sql;
    $result=$GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);

    if(!empty($result)){
        foreach($result as $v){
            $grade=0;
            //年龄
            if (empty($v['birthyear'])){
                $age=0;
            }else{
               $age=Date('Y')-$v['birthyear'];
            }
            if ($age>=25) $grade++;
            
            //收入
            $salary=$v['salary'];
            if($salary>=4)  $grade++;
            
            //地区
            $province=$v['province'];
            $city=$v['city'];
            
            $array_district=array("10102000","10103000","10101201","10101002","10105000");
            
            if(in_array($province, $array_district) || ($city=="10127001")) $grade++;
            
            if($v['isattend']) $grade++;
            
            if($grade){
                
                $uid=$v['uid'];
                $nickname=$v['nickname'];
                $gender=$v['gender'];
                $s_cid=$v['s_cid'];
                $birthyear=$v['birthyear'];
                $salary=$v['salary'];
                $lastvisit=$v['lastvisit'];
                $login_meb=$v['login_meb'];
                $province=$v['province'];
                $city=$v['city'];
                $source=$v['source'];
                $allotdate=$v['allotdate'];
                $regdate=$v['regdate'];
                $sid=$v['sid'];
                $islock=$v['is_lock'];
                $isattend=$v['isattend'];
                
                $member_list[]=array('uid'=>$uid,'nickname'=>$nickname, 'gender'=>$gender,'s_cid'=>$s_cid,'birthyear'=>$birthyear,'salary'=>$salary,'lastvisit'=>$lastvisit,'login_meb'=>$login_meb,'province'=>$province,'city'=>$city,'source'=>$source,'allotdate'=>$allotdate,'regdate'=>$regdate,'sid'=>$sid,'islock'=>$islock,'grade'=>$grade,'isattend'=>$isattend);
       
            }
            
            
            
        }
    }

    //============================================================================================================
 
    
    //来源
    if($member_list) {
        foreach($member_list as $key => $user) {
            if(preg_match_all("/(wf=\w+)&?|(st=\w+)&?/i", $user['source'], $matches)) {
                $member_list[$key]['source'] = $matches[1][0]."<br />".$matches[2][1];
            }
        }
    }
    
    //echo "<span style='display:none;'>$sql</span>";
    $currenturl = "index.php?action=allmember&h=excellent";//&province={$provinceL}";//.$link;
    $currenturl1 = "index.php?action=allmember&h=excellent&choose={$choose}&keyword={$keyword}clear=1";
    //echo '<br><br><br>'.$currenturl;
    $page_links = multipage( $total, $page_per, $page, $currenturl1 );
    
    
    //$kefu_list = get_kefulist();
    
    $condition = array();
    $condition['m.uid']=$condition['m.username']=$condition['m.nickname']=$condition['m.telphone']=$condition['m.sid'] = '';
    //获取本次查询条件
    $condition = array_merge($condition,  get_condition($where));
    
   
    $title = '优质会员';
    require_once(adminTemplate('allmember_excellent'));
}

/**
 * 删除采集会员
 */
function allmember_del_collect(){
    $uid=MooGetGPC('uid','integer','G');
    if(empty($uid)){
        salert('变量传递错误','index.php?action=allmember&h=collect&clear=1');
    }
     $usertype=$GLOBALS['_MooClass']['MooMySQL']->getOne('select gender,usertype from `'.$GLOBALS['dbTablePre'].'members_search` where uid='.$uid,true);
     if(empty($usertype) || $usertype['usertype']!=3){
         salert('你要删除的用户不存在或者不是英才会员','index.php?action=allmember&h=collect&clear=1');
     }
     //删除图片
     $imgs=$GLOBALS['_MooClass']['MooMySQL']->getAll('select imgurl,pic_date,pic_name from `'.$GLOBALS['dbTablePre'].'pic` where uid='.$uid,0,0,0,true);
     if(!empty($imgs)){
         foreach($imgs as $img){
             $img_extension=strtolower(pathinfo($img['pic_name'], PATHINFO_EXTENSION));
             $img_name=str_replace('.'.$img_extension,'',$img['pic_name']);
             $img_md5=md5($img_name);
             $img_path='../'.PIC_PATH."/".$img['pic_date'];
             is_file('../'.$img['imgurl'])?unlink('../'.$img['imgurl']):'';
             $img_s_array=array('41_57','139_189','171_244','orgin');
             foreach($img_s_array as $value){
                 $file_name=$img_path.'/'.$value.'/'.(($value=='orgin')?$img_name.'_nowater':$img_md5).'.'.$img_extension;
                 is_file($file_name)?unlink($file_name):'';
             }
         }
     }
     $user_img_array=array('big','small','medium','mid');
     foreach($user_img_array as $style){
        $userimg=MooGetphotoAdmin($uid,$style);
        is_file($userimg)?unlink($userimg):'';
     }
     //音乐文件
     $muiscs=$GLOBALS['_MooClass']['MooMySQL']->getAll('select path from `'.$GLOBALS['dbTablePre'].'art_music` where uid='.$uid,0,0,0,true);
     if(!empty($muiscs)){
         foreach($muiscs as $muisc){
             $muisc_path='../data/mp3/'.$muisc['path'];
             is_file($muisc_path)?unlink($muisc_path):'';
         }
     }
     $user_table=array('members_choice','members_base','members_search','certification','comment','congratulate_remark','mail_queue','allotuser','member_admininfo','members_action','mmslog','screen','validuser_id','test_member','test_vote','tmp','today_send','uplinkcontent','uplinkdata','vote','services','service_visitor','service_leer','service_rose','service_chat','service_friend','service_visitor','smsauths','smslog','smslog_sys','server_log','service_getadvice','service_contact','ifyouaretheone','pic');//用户相关的表
     foreach($user_table as $table){
         $table_name=in_array($table,array('membersfastadvance','membersfastsort'))?$table.($usertype['gender']?'_women':'_man'):$table;
         if(!$GLOBALS['_MooClass']['MooMySQL']->getOne('SHOW TABLES LIKE \''.$GLOBALS['dbTablePre'].$table_name.'\'',true)){
            continue;
         }
         if(MOOPHP_ALLOW_FASTDB){
            if(in_array($table_name,array('members_search','members_base','certification','members_choice'))){
                $GLOBALS['fastdb']->delete($table_name.'uid'.$uid);
            }
         }
         $sql='delete from `'.$GLOBALS['dbTablePre'].$table_name.'` where ';
         switch($table_name){
             case 'today_send':
                 $where='`sid`='.$uid.' or `uid`='.$uid;
             break;
             case 'services':
                 $where='s_uid='.$uid;
             break;
             case 'service_visitor':
                 $where='uid='.$uid.' or visitorid='.$uid;
             break;
             case 'service_leer':
                 $where='receiveuid='.$uid.' or senduid='.$uid;
             break;
             case 'service_rose':
                 $where='receiveuid='.$uid.' or senduid='.$uid;
             break;
             case 'service_chat':
                 $where='s_uid='.$uid.' or s_fromid='.$uid;
             break;
             case 'service_friend':
                 $where='uid='.$uid.' or friendid='.$uid;
             break;
             case 'service_contact':
                 $where='`other_contact_you`='.$uid.' or you_contact_other='.$uid;
             break;
            default:
                 $where='`uid`='.$uid;
             break;
         }
         $sql=$sql.$where;
         $GLOBALS['_MooClass']['MooMySQL']->query($sql);
     }
     salert('英才会员已经删除了','index.php?action=allmember&h=collect&clear=1');
}

//采集会员发布
function allmember_release_collect(){
    $uid=MooGetGPC('uid','integer','G');
    $reuid=MooGetGPC('reuid','integer','G');

}

//400模拟 模块
function allmember_foo(){
	global $dbTablePre;
    $condition = '';

    /*$keyword = trim(MooGetGPC('keyword','string','P'));
    $choose = MooGetGPC('choose','string','P');

    if(!empty($choose) && !empty($keyword)){
        $condition = " $choose = '$keyword'";  
    }*/
    
    $telephone=MooGetGPC('telephone','integer','P');
    $memberid=MooGetGPC('memberid','integer','P')==0?'':MooGetGPC('memberid','integer','P');
    
    $qq=MooGetGPC('qq','integer','P');
    $callno=MooGetGPC('callno','integer','P');
    
    $SearchType=MooGetGPC('SearchType','string','P');
    
    if($SearchType=='tel'){//手机
       $condition=" where m.telphone={$telephone}";
    }elseif($SearchType=='memberid'){
       $condition=" where m.uid={$memberid}";
    }elseif($SearchType=='qq'){
       $condition="  where mf.qq={$qq}";
    }elseif($SearchType=='callno'){
       $condition=" where mf.callno={$callno}";
    }
    
    $uid = $sid = $flag = $province = $city = 0;
	$nickname= $username = $regdate = $allotdate = $content = '';
    if(MooGetGPC('submit','string','P')){
    	$uid=MooGetGPC('uid','integer','P');
    	$sid=MooGetGPC('sid','integer','P');
    	$content=MooGetGPC('content','string','P');
    	$flag=MooGetGPC('type','integer','P');
   
       //提醒所属客服
        $timestamp=time();
        $date=date('Y-m-d H:i:s',$timestamp);
        $title="会员  {$uid} 回电！";
        $content=$uid."于{$date}：".$content;
	    
	    //$awoketime = $timestamp+3600;
	    $sql_remark = "insert into {$GLOBALS['dbTablePre']}admin_remark set sid='{$sid}',title='{$title}',content='{$content}',awoketime='',dateline='{$timestamp}',flag=$flag,send_id={$GLOBALS['adminid']}";
	    $res = $GLOBALS['_MooClass']['MooMySQL']->query($sql_remark);
	    serverlog(4,$dbTablePre."admin_remark","会员".$uid."回电发送",$GLOBALS['adminid']);
        $currenturl = "index.php?action=allmember&h=foo";
        salert("发送成功！",$currenturl);
    }
    
    if (MooGetGPC('searchSubmit','string','P') && !empty($condition)){
	    $sql="select m.uid as uid,m.nickname as nickname,m.province as province,m.city as city,mf.allotdate as allotdate,m.regdate as regdate,m.sid as sid from web_members_search m left join web_members_base mf on m.uid=mf.uid  $condition limit 1";

	    $result=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
	    
	    $uid=$result['uid'];
	    $nickname=$result['nickname'];
	    $sid=$result['sid'];
	    $regdate=$result['regdate'];
	    $allotdate=$result['allotdate'];
	    $province=$result['province'];
	    $city=$result['city'];
	    
	    
	    if(!empty($sid)){
		    $sql="select username from web_admin_user where uid={$sid}";
		    $adminUser=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
		    $username=$adminUser['username'];
	    }
    }
    
    $currenturl = "index.php?action=allmember&h=foo";
    
    $title = '400查询';
    require_once(adminTemplate('allmember_foo'));
}


//400回电查询
function allmember_fooquery(){
	$page_per = 10;
    $page = max(1,MooGetGPC('page','integer'));
    $limit = 10;
    $offset = ($page-1)*$limit;
 
    
    //得到所属客服下的
    $myservice_idlist = get_myservice_idlist();
    
    $listGroup=explode(',',$myservice_idlist);//如果组长  ，列出成员表
    
	
    //echo $myservice_idlist.'and'.$GLOBALS['adminid'];
	$admin_userinfo_check = $GLOBALS['admin_userinfo_check'][0];

	$sql = "select uid,username from web_admin_user where groupid = $admin_userinfo_check";
	$name = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
	
	
	
	//if(MooGetGPC('Dealsubmit','string','P')){
	$deal=MooGetGPC('channel','integer','R');
	$send_id = MooGetGPC('send_id','integer','R');
	$sid= MooGetGPC('sid','integer','R');
	$starttime = MooGetGPC('startdate','string','R');
	$endtime= MooGetGPC('enddate','string','R');
	$start_time =strtotime($starttime); 
	$end_time=strtotime($endtime+'+1 day');
	$channel=MooGetGPC('channel','string','R');
	//}
	
	$isPost=MooGetGPC('Dealsubmit','string','P');
	$page=MooGetGPC('page','string','R');
	
	$total = 0;
	$remark = array();
    if(empty($myservice_idlist)){
		if($isPost || $page){ 
			if($send_id == 0){
				$where = " and status = '$deal' and dateline>='$start_time' and dateline<='$end_time'";
			}else{
				$where = " and status = '$deal' and dateline>='$start_time' and dateline<='$end_time' and send_id = '$send_id'";
			}
			
			$sql="select count(id) as total from web_admin_remark where  flag>0 $where  and sid in ({$GLOBALS['adminid']})";
			$num = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
			$total = $num['total'];
			
			$sql="select id,sid,title,content,dateline,status,flag from web_admin_remark where  flag>0 $where and sid in ({$GLOBALS['adminid']})  limit {$offset},{$limit} ";
			$remark=$GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
		   
		}
    }elseif($myservice_idlist == 'all'){ //all为客服主管能查看所有的
        $adminUser=$GLOBALS['_MooClass']['MooMySQL']->getAll("select uid,username from web_admin_user",0,0,0,true);
    	if($isPost || $page){
			
			if($send_id == 0){
				$where = "and status = '$deal' and dateline>='$start_time' and dateline<='$end_time'";
			}else{
				$where = "and status = '$deal' and dateline>='$start_time' and dateline<='$end_time' and send_id = '$send_id'";
			}
			
    	    if($sid > 0){
			   $where = "and status = '$deal' and dateline>='$start_time' and dateline<='$end_time' and sid = '$sid'";
			}
			
			$sql = "select count(id) as num_1 from web_admin_remark where  flag=1 $where";
	        $num_1=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
	        $sql = "select count(id) as num_2 from web_admin_remark where  flag=2 $where";
	        $num_2=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
	        $sql = "select count(id) as num_3 from web_admin_remark where  flag=3 $where";
	        $num_3=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
	        $total = $num_1['num_1']+$num_2['num_2']+$num_3['num_3'];
	        
	        
	       	$sql = "select id,sid,title,content,dateline,status,flag from web_admin_remark where  flag>0 $where limit {$offset},{$limit}";
	    	$remark=$GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
	    	
    	}
    }else{ //组长以上权限

    	$adminUser=$GLOBALS['_MooClass']['MooMySQL']->getAll("select uid,username from web_admin_user  where uid in ($myservice_idlist)",0,0,0,true);
		if($isPost || $page){
			if($sid == 0){
				$where = "and status = '$deal' and dateline>='$start_time' and dateline<='$end_time' and sid in ($myservice_idlist)";
			}else{
				$where = "and status = '$deal' and dateline>='$start_time' and dateline<='$end_time' and sid = '$sid'";
			}
			
			$sql = "select count(id) as num_1 from web_admin_remark where  flag=1 $where ";
	        $num_1=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
	        $sql = "select count(id) as num_2 from web_admin_remark where  flag=2 $where ";
	        $num_2=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
	        $sql = "select count(id) as num_3 from web_admin_remark where  flag=3 $where ";
	        $num_3=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
	        $total = $num_1['num_1']+$num_2['num_2']+$num_3['num_3'];

			
			$sql="select id,sid,title,content,dateline,status,flag from web_admin_remark where flag>0 $where  limit {$offset},{$limit}";
			$remark=$GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
		}
    }
   
    $currenturl = "index.php?action=allmember&h=fooquery&startdate=$starttime&enddate=$endtime&channel=$channel&send_id=$send_id";
       
    $pages = multipage( $total, $page_per, $page, $currenturl );
    $page_num = ceil($total/$limit);
    
    $title = '400回电查询';
    require_once(adminTemplate('allmember_fooquery'));
    
}


//按客服分类会员列表
function allmember_class(){
	global $allow_order, $rsort,$grade;
	$get=MooGetGPC('clear','integer','G');
	
	$total=0;
	$page_links='';
	$sort_arr=$member_list=array();

	$currenturl = "index.php?action=allmember&h=class";
	$currenturl1 = "index.php?action=allmember&h=class&clear=1";
	
	//$condition = array();
	//$condition['uid']=$condition['username']=$condition['nickname']=$condition['telphone']=$condition['sid'] = '';
	
	$sql_where = 'where 1';
	//获得参数
	/* $effect_grade=MooGetGPC('effect_grade','integer','G');
	
	
	
	$isControl=MooGetGPC('isControl','string','G');
	$isForcast=MooGetGPC('isForcast','string','G');
	
   
	
	$keyword=MooGetGPC('keyword','string','G');
	$endTime=MooGetGPC('end','string','G');
	$startTime=MooGetGPC('start','string','G'); */
	//获取查询条件及语句
	$where = get_search_condition('');

	
	
	
	//if(!empty($keyword)) $where = $where." AND m.sid = $keyword";


	$query_builder = get_query_builder($where, $allow_order, '', '', 'regdate', 'desc', $rsort);
	$where = $sql_where.$query_builder['where'];
	$sql_sort = $query_builder['sort'];
	$sort_arr = $query_builder['sort_arr'];
	$rsort_arr = $query_builder['rsort_arr'];
	
	$kefu_list = get_kefulist();
    //分类会员
	if($get==1){
		
		$page_per = 20;
		$page = get_page();
		$limit = 20;
		$total = get_allmember_count($where);

		$page_total = max(1, ceil($total/$limit));
		$page = min($page, $page_total);
		$offset = ($page-1)*$limit;
		//$offset = 0;//enky add
        
		$member_list = get_member_list($where, $sql_sort, "limit {$offset},{$page_per}");
		
		//来源
		if($member_list) {
			foreach($member_list as $key => $user) {
				if(preg_match_all("/(wf=\w+)&?|(st=\w+)&?/i", $user['source'], $matches)) {
					$member_list[$key]['source'] = $matches[1][0]."<br />".$matches[2][1];
				}
			}
		}
		
		// echo $url = "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
		$currenturl = "index.php?action=allmember&h=class";
		//$currenturl1 = "index.php?action=allmember&h=class&clear=1";
		
		
		$currenturl1 = "index.php?action=allmember&h=class&clear=1&effect_grade=$effect_grade&keyword=$keyword&end=$endTime&start=$startTime";
		$page_links = multipage( $total, $page_per, $page, $currenturl1 );
	
		
		
		$age_arr = array();
		for($i=18;$i<100;$i++){
			$age_arr[] = $i;	
		} 
		
		//获取本次查询条件
		$condition = get_condition($where);
	}
	$title = '分类会员列表';
	require_once(adminTemplate('allmember_general'));
}



/***********************************************控制层(C)*****************************************/
$h=MooGetGPC('h','string');

//note 动作列表
$hlist = array('general','high','diamond','downgeneral','collect','edit_info','outsite','alliance',
			   'inside','view_info','changeusersid','deletemember','note','advancesearch','nosid','class',
			   'giveup','notgiveup','regnoallot_members','goodnoallot_members','can_recovery','record','listen_open','same_telphone','del_same_telphone','sellafter','uncontact','excellent','delcollect','releasecollect','foo','fooquery');

//note 判断页面是否存在
if(!in_array($h,$hlist)){
	salert('您要打开的页面不存在d');
}
////note 判断是否有权限
//if(!checkGroup('allmember',$h)){
//  salert('您没有此操作的权限');
//}

//允许的排序方式
$allow_order = array(
	'uid' => 'ID',
	'birthyear' => '年龄',
	'images_ischeck' => '照片',
	'salary' => '收入',
	'allotdate' => '分配时间',
	'regdate' => '注册时间',
	'real_lastvisit' => '在线状态',
	'lastvisit' => '最后登陆时间',
	'usertype' => '会员类型',
	'login_meb' => '登陆次数',
	'sid' => '工号/客服',
	'is_lock' => '是否锁定',
	'renewalstatus' => '续费状态',
	'memberprogress' => '会员进展',
	'action_time'=>'使用时间'
);

//需要修正的排序
$rsort = array(
	'birthyear' => 1
	//'regdate'=>
);
header("Cache-Control: no-cache");
switch($h){
    case 'general':
    	allmember_general();
   	    break;
	//note 高级会员列表
    case 'high':
    	allmember_high();
        break;
    case 'diamond':
    	allmember_diamond();
        break;
	case 'class':
	    allmember_class();
		break;
    case 'collect':
    	allmember_collect();
        break;
    case 'outsite':
    	allmember_outsite();
        break;
    case 'alliance':
    	allmember_alliance();
        break;
    case 'inside':
    	allmember_inside();
        break;
    case 'view_info':
    	allmember_view_info();
        break;
	//note 分配会员给某个客服
    case 'changeusersid':
    	allmember_changeusersid();
        break;
   	//note 删除会员到不可回收
    case 'deletemember':
    	allmember_deletemember();
        break;
    case 'edit_info':
    	allmember_edit_info();
        break;
	//note 保存小记
    case 'note':
    	allmember_note();
        break;
    case 'advancesearch':
    	allmember_advancesearch();
        break;
    //未分配高级会员列表
	case 'nosid':
		allmember_nosid_vip();
	    break;
	//被客服删除会员列表
	case 'giveup':
		allmember_giveup();
	    break;
	//被客服删除不可回收会员列表
	case 'notgiveup':
		allmember_notgiveup();
	    break;
	//由高级会员降级的普通会员
	case 'downgeneral':
		allmember_downgeneral();
	    break;
	//注册未分配会员 
	case 'regnoallot_members':
		allmember_regnoallot_members();
	    break;
	//未分配优质会员
	case 'goodnoallot_members':
		allmember_goodnoallot_members();
	    break;
	//录音
	case 'record':
		allmember_record();
	    break;
	case 'listen_open':
		allmember_listen_open();
	    break;
	//输入页数批量分配会员
	case 'changeusersid_bat':
		allmember_changeusersid_bat();
	    break;
	case 'same_telphone':
		allmember_same_telphone();
	    break;
	case 'del_same_telphone':
		allmember_del_same_telphone();
	    break;
	//可二次回收的会员
	case 'can_recovery':
		allmember_can_recovery();
	    break;
	case 'uncontact':
		allmember_uncontact();
        break;
	case 'excellent':
		allmember_excellent();
		break;
	//售后
	case 'sellafter':
		allmember_sellafter();
	break;
    case 'delcollect':
        allmember_del_collect();
         break;
    case 'releasecollect':
        allmember_release_collect();
        break;
    case 'foo':
    	allmember_foo();
    	break;
    case 'fooquery':
    	allmember_fooquery();
    	break;
    default:
    	exit('文件不存在');
    break;
    
}


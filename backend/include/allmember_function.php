<?php
/**
 * 全部会员栏目用到的方法
 * @author:fanglin
 */

//获取符合条件的会员数量
function get_allmember_count($where) {
	global $_MooClass, $dbTablePre;
	
	if (preg_match('/mb.source/',$where)){
        
	    $sql = "select count(*) as count from {$dbTablePre}members_search as m left join {$dbTablePre}member_admininfo as ma on m.uid = ma.uid left join {$dbTablePre}members_base as mb on m.uid=mb.uid {$where} limit 1";
        if(preg_match('/c.telphone/',$where)) {
            $sql = "select count(*) as count from {$dbTablePre}members_search as m left join {$dbTablePre}member_admininfo as ma on m.uid = ma.uid left join {$dbTablePre}members_base as mb on m.uid=mb.uid left join  {$dbTablePre}certification as c on m.uid=c.uid {$where} limit 1";
        }
    }else{
	    $sql = "select count(*) as count from {$dbTablePre}members_search as m left join {$dbTablePre}member_admininfo as ma on m.uid = ma.uid {$where} limit 1";
        if(preg_match('/c.telphone/',$where)) {
            $sql = "select count(*) as count from {$dbTablePre}members_search as m left join {$dbTablePre}member_admininfo as ma on m.uid = ma.uid left join {$dbTablePre}certification as c on m.uid = c.uid {$where} limit 1";
        }
	}
	$count = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	return $count['count'];
}
 
//得到会员列表
function get_member_list($where, $sort, $limit='') {
	global $h;
	$sql='';
	$sort_pre = array(
	'lastvisit'=>'ml',
	'uid'=>'m',
	'real_lastvisit'=>'ma',
	'birthyear'=>'m',
	'images_ischeck'=>'m',
	'salary'=>'m',
	'allotdate'=>'mb',
	'regdate'=>'m',
	'usertype'=>'m',
	'login_meb'=>'ml',
	'sid'=>'m',
	'is_lock'=>'m',
	'action_time'=>'f'
	);

	if ($sort){
		foreach ($sort_pre as $k=>$v){
			$sort = str_replace(" ".$k," $v.$k",$sort);
		}
	}
	
	$otherlimit = '';
	
	
	if(strpos($where, 'm.uid')===false){
		//第一次检查$where里多少个表 m. ml. mb. ma.
	 	$table_arr = array(
	    		'm'=>'members_search',
	            'mb'=>'members_base',
	            'ml'=>'members_login',
	    		'ma'=>'member_admininfo',
                'f'=>'full_log',
                'c'=>'certification'
               
		 );
		    //$alias = array('m','mb','ml','ma');
	
	    //m.gender=1 and ml.lastvisit order
	    $table_array= array();
	    foreach ($table_arr as $k=>$v){
	    	if(strpos($where, $k.'.')!== false){
	    		$table_array[] = array('pre'=>$k,'table'=>$v);
	    	}elseif(strpos($sort, $k.'.')!== false){
	    		$table_array[] = array('pre'=>$k,'table'=>$v);
	    	}	    		
	    }
	  	
	    $table_num = count($table_array);
		
		
	    if($table_num){
		    if($table_num == 1){	    		
		    	$sql = "select {$table_array[0]['pre']}.uid from {$GLOBALS['dbTablePre']}{$table_array[0]['table']} {$table_array[0]['pre']} {$where} {$sort} {$limit}";
		    }else if($table_num == 2){
		    	$sql = "select {$table_array[0]['pre']}.uid from {$GLOBALS['dbTablePre']}{$table_array[0]['table']} {$table_array[0]['pre']} left join {$GLOBALS['dbTablePre']}{$table_array[1]['table']} {$table_array[1]['pre']} on {$table_array[0]['pre']}.uid={$table_array[1]['pre']}.uid {$where} {$sort} {$limit}";
		    		
		    }else if($table_num==3){
			     $sql = "select {$table_array[0]['pre']}.uid from {$GLOBALS['dbTablePre']}{$table_array[0]['table']} {$table_array[0]['pre']} left join {$GLOBALS['dbTablePre']}{$table_array[1]['table']} {$table_array[1]['pre']} on {$table_array[0]['pre']}.uid={$table_array[1]['pre']}.uid   left join {$GLOBALS['dbTablePre']}{$table_array[2]['table']} {$table_array[2]['pre']} on {$table_array[0]['pre']}.uid={$table_array[2]['pre']}.uid  {$where} {$sort} {$limit}";

			}
		
		   
		   $result = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
		   if(empty($result)) {
		      return array();
		    }
		   
		   $uids = array();
		   foreach($result as $value){
		   	 $uids[] = $value['uid'];
		   }
		   if($uids){
		   	$where = ' where m.uid in (' . implode(',', $uids). ')';
		   }else{
		   	$where = null;
		   }
	    }else {
	    	$otherlimit = $limit;
		
	    }
    }
	
	
	
	$from = "FROM {$GLOBALS['dbTablePre']}members_search m LEFT JOIN {$GLOBALS['dbTablePre']}members_base mb on m.uid=mb.uid LEFT JOIN {$GLOBALS['dbTablePre']}members_login ml on m.uid=ml.uid LEFT JOIN {$GLOBALS['dbTablePre']}member_admininfo ma ON m.uid=ma.uid LEFT JOIN {$GLOBALS['dbTablePre']}full_log f on m.uid=f.uid $where {$sort} {$otherlimit}";//del ｛$limit｝
	if($h == 'downgeneral'){
		$sql = "SELECT m.*,mb.source,mb.mainimg,mb.allotdate,ml.login_meb,ma.effect_grade,ma.real_lastvisit,ma.checkreason,ma.renewalstatus,ma.old_sid ".$from;
	}elseif($h == 'diamond' || $h == 'high'){
		$sql = "SELECT m.*,mb.source,mb.mainimg,mb.allotdate,ml.login_meb,ma.effect_grade,ma.real_lastvisit,ma.checkreason,ma.memberprogress,ma.old_sid ".$from;
	}else{
		$sql = "SELECT m.*,mb.source,mb.mainimg,mb.allotdate,ml.login_meb,ma.effect_grade,ma.real_lastvisit,ma.checkreason,ma.old_sid,f.action_time ".$from;
	}
	
	
	$result = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
	
	
	echo "<span style='display:none;'>$sql</span>";
	return $result;
} 


//得到全部会员分类 中会员列表搜索条件
function get_search_condition($where = ''){

	$condition[] = $where;
	
	$keyword = trim(MooGetGPC('keyword','string','G'));
    $choose = MooGetGPC('choose','string','G');
    $gender = MooGetGPC('gender','integer','G');
    $province = MooGetGPC('province','integer','G');
    $islock = MooGetGPC('islock','integer','G');
    $age_start =  MooGetGPC('age_start','integer','G');
    $age_end =  MooGetGPC('age_end','integer','G');
    $effect_grade=MooGetGPC('effect_grade','G');
	$regdate=MooGetGPC('regdate','string','G');
	$endTime=MooGetGPC('end','string','G');
	$startTime=MooGetGPC('start','string','G');
	
	$isControl=MooGetGPC('isControl','string','G');
    $isForcast=MooGetGPC('isForcast','string','G');
    
	$cf=MooGetGPC('cf','string','G');

	
	//可控预测的会员操作
	$uidControlArr=array();
	if($isControl=='yes'){
	   if (empty($cf)){
	      $sql="select uid from web_member_isControl where dateline>={$startTime} and dateline<{$endTime} and sid=$keyword and flag=1";
	   }else{
	      $sql="select uid from web_member_isControl where sid=$keyword and flag=1";
	   }
	   $uidControlArr=$GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	   
	   $uidlist="";
	   $comma="";
	   foreach ($uidControlArr as $v){
	      $uidlist .=$comma .$v['uid'];
		  $comma=",";
	   }
	  
	   $condition[]=" m.uid in ({$uidlist})";
	
	
	}
	
	//可控预测的会员操作
    $uidForcastArr=array();
	if($isForcast=='yes'){
	   
	   if (empty($cf)){
	      $sql="select uid from web_member_isControl where dateline>={$startTime} and dateline<{$endTime} and sid=$keyword and isforcast=1";
	   }else{
	      $sql="select uid from web_member_isControl where  sid=$keyword and isforcast=1";
	   }
	   $uidForcastArr=$GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	   
	   $uidlist="";
	   $comma="";
	   foreach ($uidForcastArr as $v){
	      $uidlist .=$comma .$v['uid'];
		  $comma=",";
	   }
	   
	   $condition[]=" m.uid in ({$uidlist})";
	
	
	}
   
	
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
    
    //所管理的客服id列表
   $myservice_idlist = get_myservice_idlist();

   if(empty($myservice_idlist)){
   		$condition[] = " m.sid IN({$GLOBALS['adminid']})";
   }elseif($myservice_idlist == 'all'){
   		//all为客服主管能查看所有的
   }else{
   		$condition[] = " m.sid IN($myservice_idlist)";
   }
   
 //分类
   if(!empty($effect_grade)){
   	  $condition[] = " ma.effect_grade ={$effect_grade}";
   }
   
   //分类 起始日期结束日期
   if (empty($isControl) &&  empty($isForcast)){
	   if($endTime){
		 $condition[] = "   ma.dateline<'{$endTime}'";
		 
	    }
   }
   
   if (empty($isControl) && empty($isForcast)){
	   if ($startTime){
		$condition[] = "   ma.dateline>'{$startTime}'";
	   }
    }
   
   //注册时间
   if($regdate){
   	 $regstamp=strtotime('+1 day',strtotime($regdate));
	 $regstampstart=strtotime('-9 day',strtotime($regdate));
   	 $condition[] = "   m.regdate>='{$regstampstart}' and m.regdate<='{$regstamp}'";
   }
   
  
 

   $result = implode(' AND ',$condition);
   

   return $result;
}


//得到全部会员分类 中会员列表搜索条件
function sphinx_search($filters, $limits, $sorts){
	if(isset($filters['uid'])) return array('total'=>0);
	if(($limits[0]+$limits[1])>=1000) return array('total'=>0);
	$allow_sorts = array('uid', 'birthyear', 'images_ischeck','regdate', 'sid', 'is_lock');
	$sort = '';
	if(!empty($sorts)){
		$sort = str_replace('order by ', '', $sorts);
		$sort_arr = explode(' ', trim($sort));
		if(!in_array($sort_arr[0], $allow_sorts)) return array('total'=>0);
		$sort = str_replace('uid', '@id', $sort);		
	}
	$filter = array();
	
	$index = (!isset($filters['gender']) || $filters['gender']==3) ? 'members_women  members_man' : ($filters['gender']==1 ? 'members_women' : 'members_man');
	
	unset($filters['gender']);

	foreach($filters as $key=>$value){
		if (strpos($key,'in')&&strpos($key,'(')){
			list($key,$value) = explode('in',$key);
			$key = trim($key);
			$value = str_replace(',','|',str_replace(array('(',')'),'',$value));
			$filter[] = array($key, $value);
		}else {
			$filter[] = array($key, $value);
		}
	}
	
	//过滤记忆uid
	if(isset($_GET['h']) && isset($_GET['action'])){
		$remember_adminid = in_array($GLOBALS['groupid'],$GLOBALS['admin_service_arr']) ? '0' : $GLOBALS['adminid'];
		$uids = sphinx_remember_uids($_GET['action'].'_'.$_GET['h'].'_'.$remember_adminid);
		if(!empty($uids)){
			$filter[] = array('@id', implode('|', $uids), true);
		}
	}
	
	//exit;
	$sp = searchApi($index);
	$sp->setQueryType(true);
	$sp->getResultOfReset($filter, $limits, $sort);
	$total = $sp->getRs('total_found');
   
	$member_list = array();
	
  	if($total>0 && $uids = $sp->getIds()){		
  		$where = 'where m.uid in ('.implode(',', $uids).')';
	    $member_list = get_member_list($where, $sorts);
	}

	return array('total'=>$total, 'member_list'=>$member_list);
}


function get_search_link(){
	$result = '';
	$page = MooGetGPC('page','integer','G');
	$keyword = trim(MooGetGPC('keyword','string','G'));
    $choose = MooGetGPC('choose','string','G');
    $gender = MooGetGPC('gender','integer','G');
    $province = MooGetGPC('province','integer','G');
    $islock = MooGetGPC('islock','integer','G');
	if(!empty($choose) && !empty($keyword)){
    	$result .= "&$choose=$keyword";	
    }
    //排序用
    if($_GET['field']){
    	$field = MooGetGPC('field','string','G');
    	$result .= "&field={$field}&order={$_GET['order']}";
    }
    $result .= "&gender=$gender";
    $result .= "&province=$province";
    $result .= "&islock=$islock";
    $result .= "&page=$page";
	if(isset($_GET['process'])){
		$vipprocess = MooGetGPC('process','integer','G');
		$result .= "&process={$vipprocess}";
	}
	if(isset($_GET['expired'])){
		$expired = MooGetGPC('expired','integer','G');
		$result .= "&expired={$expired}";
	}
    return $result;
}

//普通客服修改会员资料，得到操作的sql
function get_operation_sql($tablename, $setsqlarr, $wheresqlarr, $silent=0,$uid) {
	global $_MooClass;
	global $dbTablePre;

	$setsql = $comma = '';
	foreach ($setsqlarr as $set_key => $set_value) {
		$setsql .= $comma.'`'.$set_key.'`'.'=\''.$set_value.'\'';
		$comma = ', ';
	}
	$where = $comma = '';
	if(empty($wheresqlarr)) {
		$where = '1';
	} elseif(is_array($wheresqlarr)) {
		foreach ($wheresqlarr as $key => $value) {
			$where .= $comma.'`'.$key.'`'.'=\''.$value.'\'';
			$comma = ' AND ';
		}
	} else {
		$where = $wheresqlarr;
	}
	$sql = 'UPDATE '.$dbTablePre.$tablename.' SET '.$setsql.' WHERE '.$where;
	$sql = base64_encode($sql);
	return $sql;
}

//普通会员编辑用户资料的sql
function insert_edit_membersinfo_sql($members_sql,$memberfield_sql,$uid){
	//$edit_sql = base64_encode($edit_sql);
	$sql = "INSERT INTO {$GLOBALS['dbTablePre']}admin_editmembersinfo_sql SET uid='{$uid}',exec_members_sql='{$members_sql}',exec_memberfield_sql='{$memberfield_sql}',sid='{$GLOBALS['adminid']}',username='{$GLOBALS['username']}',dateline='{$GLOBALS['timestamp']}'";

	$GLOBALS['_MooClass']['MooMySQL']->query($sql);
}

//判断用户在线状态
function check_online($uid){
	return MooUserIsOnline($uid);
	/*$sql = "SELECT * FROM {$GLOBALS['dbTablePre']}membersession WHERE uid='$uid'";
	$useronline = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	if($useronline['uid']!=''){
		return true;
	}else{
		return false;
	}*/
}

//取得a发送给b的站内信数
function get_letter_num($fromid,$toid){
	return getcount("services","WHERE s_fromid='{$fromid}' AND s_uid = '{$toid}' AND is_server != 1");
}
//取得a发送给b的委托数
function get_commission_num($fromid,$toid){
	return getcount("service_contact","WHERE other_contact_you = '{$fromid}' AND you_contact_other = '{$toid}' AND is_server != 1");
}

//取得a发送给b的状态
function get_commission_state($fromid,$toid,$who){
	if($who == "send") {
		$sql = "SELECT stat FROM {$GLOBALS['dbTablePre']}service_contact WHERE other_contact_you = '{$fromid}' AND you_contact_other = '{$toid}' ORDER BY mid DESC LIMIT 1";
		$result = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
		switch($result['stat']){
			case 1:
				$stat = "等待对方回应";
				break;
			case 2:
				$stat = "对方已接受";
				break;
			case 3:
				$stat = "对方正在考虑";
				break;
			case 4:
				$stat = "对方不愿接受";
				break;
			default:
				$stat = "状态不明确";
		}
	}
	if($who == "receive") {
		$sql = "SELECT stat FROM {$GLOBALS['dbTablePre']}service_contact WHERE other_contact_you = '{$fromid}' AND  you_contact_other = '{$toid}' ORDER BY mid DESC LIMIT 1";
		$result = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);error_log($sql);
		switch($result['stat']){
			case 1:
				$stat = "为等待回应的请求";
				break;
			case 2:
				$stat = "已接受的请求";
				break;
			case 3:
				$stat = "考虑中的请求";
				break;
			case 4:
				$stat = "拒绝的请求";
				break;	
			default:
				$stat = "状态不明确";
		}
		//echo $sql = "SELECT stat FROM {$GLOBALS['dbTablePre']}service_contact WHERE other_contact_you = '{$fromid}' AND you_contact_other = '{$toid}'"; 
	}
	return $stat;
}
//取得a发送给b的秋波数
function get_leer_num($fromid,$toid){
	return getcount("service_leer","WHERE senduid = '{$fromid}' AND receiveuid = '{$toid}' AND is_server != 1");
}
//取得a发送给b的鲜花数
function get_rose_num($fromid,$toid){
	return getcount("service_rose","WHERE senduid = '{$fromid}' AND receiveuid = '{$toid}' AND is_server != 1");
}
//取得a发送给b的意中人数
function get_friend_num($fromid,$toid){
	return getcount("service_friend","WHERE uid = '{$fromid}' AND friendid = '{$toid}'");
}
//取得a发送给b的聊天数
function get_chat_num($fromid,$toid){
	return getcount("service_chat","WHERE s_fromid = '{$fromid}' AND s_uid = '{$toid}' AND is_server != 1");
}

//得到生肖和星座
function  get_signs($year,$month,$day){
    
	$value=array();
    if($month && $day){
		if   ($month   ==   1   &&   $day   >=20   ||   $month   ==   2   &&   $day   <=18)   {$value[0]   =   "水瓶座 ";}
		if   ($month   ==   2   &&   $day   >=19   ||   $month   ==   3   &&   $day   <=20)   {$value[0]   =   "双鱼座 ";}
		if   ($month   ==   3   &&   $day   >=21   ||   $month   ==   4   &&   $day   <=19)   {$value[0]   =   "白羊座 ";}
		if   ($month   ==   4   &&   $day   >=20   ||   $month   ==   5   &&   $day   <=20)   {$value[0]   =   "金牛座 ";}
		if   ($month   ==   5   &&   $day   >=21   ||   $month   ==   6   &&   $day   <=21)   {$value[0]   =   "双子座 ";}
		if   ($month   ==   6   &&   $day   >=22   ||   $month   ==   7   &&   $day   <=22)   {$value[0]   =   "巨蟹座 ";}
		if   ($month   ==   7   &&   $day   >=23   ||   $month   ==   8   &&   $day   <=22)   {$value[0]   =   "狮子座 ";}
		if   ($month   ==   8   &&   $day   >=23   ||   $month   ==   9   &&   $day   <=22)   {$value[0]   =   "处女座 ";}
		if   ($month   ==   9   &&   $day   >=23   ||   $month   ==   10   &&   $day   <=22)   {$value[0]   =   "天秤座 ";}
		if   ($month   ==   10   &&   $day   >=23   ||   $month   ==   11   &&   $day   <=21)   {$value[0]   =   "天蝎座 ";}
		if   ($month   ==   11   &&   $day   >=22   ||   $month   ==   12   &&   $day   <=21)   {$value[0]   =   "射手座 ";}
		if   ($month   ==   12   &&   $day   >=22   ||   $month   ==   1   &&   $day   <=19)   {$value[0]   =   "摩羯座 ";}
    }else{
	    $value[0]='';
	}
	
	$animals = array( '鼠', '牛', '虎', '兔', '龙', '蛇', '马', '羊', '猴', '鸡', '狗', '猪');
	$key= ($year - 1900) % 12;
	
	$value[0]=urlencode($value[0]);
	$value[1]=urlencode($animals[$key]);

    return   $value;//返回数组，0为星座,1为属相
} 


function get_telphone($member_list){
	if(!empty($member_list)){
		foreach($member_list as $v){
			if(!empty($v['telphone'])){
				$tel_array[] = "'$v[telphone]'";
			}
		}
		if(!empty($tel_array)){
			$tel_list = join(',',$tel_array);
			$sql_tel="SELECT COUNT(*) c,telphone FROM web_members_search WHERE is_lock=1 and telphone IN ($tel_list) GROUP BY telphone";
			$result =$GLOBALS['_MooClass']['MooMySQL']->getAll($sql_tel);
			foreach($result as $k=>$v){
				$arr[$v['telphone']] = $v['c']; 
			}
		}
		return $arr;
	}
}


/**
 * 
 * 在用户数组里面增加手机是否验证
 * @param string $member_list_uid 用于in语句的用户id
 * @param array $member_list_arr 用户数组
 * @return array 返回增加手机验证的用户数组
 */
function get_ifcheck($member_list_uid,$member_list_arr){
	$sql = "select uid,telphone from web_certification where uid in({$member_list_uid})";
	$result = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	foreach($member_list_arr as $k => $v){
		foreach($result as $v1){
			if($v1['uid'] == $v['uid']){
				$member_list_arr[$k]['telphone_ifcheck'] = $v1['telphone'];	
			}				
		}
		if(!isset($member_list_arr[$k]['telphone_ifcheck'])) $member_list_arr[$k]['telphone_ifcheck'] = 0;		 
	}
	return $member_list_arr;
}

// 北京易速信彩信技术有限公司彩信接口
function send_mms($title,$tele,$content){
	 $name = '9414'; //用户名
     $pass = 'wjt0908';  //密码
	 
	 $title = base64_encode($title);
	 $title = iconv("UTF-8", "gb2312//IGNORE", "$title");
	 //$content = "1,jpg,$tmp1;1,txt,$tmp2;2,jpg,$tmp3;2,txt,$tmp4;3,jpg,$tmp5;3,txt,$tmp6;4,gif,$tmp7;";
	$url="http://218.246.34.171/daredo/pushsms/push_mms_id.jsp?loginname=$name&loginpass=$pass&phone=$tele&title=$title&content=$content";
	 if(@file_get_contents($url)=='ok'){return "ok";}else{return "mis";}
	    /*echo '<body onload="document.a.submit()">';
		echo '<form method="post" action="http://218.246.34.171/daredo/pushsms/push_mms_id.jsp" name="a">';
		echo '<input  name ="loginname" type="text" value="'.$name.'">';
		echo '<input  name ="loginpass" type="text" value="'.$pass.'">';
		echo '<input  name ="phone" type="text" value="'.$tele.'">';
		echo '<input  name ="title" type="text" value="' . $title . '">';
		echo '<input  name ="content" type="text" value="' . $content . '">';
		echo '</form>';
		exit();*/
}

function send_mms_yimei($title,$phone,$zip_name){
// 上海亿美彩信技术有限公司设置
        $name = 'SH-gcwl'; //用户名
        $pass = 'admin168';  //密码
        $zipfile = "data/upload/".$zip_name;		
		
        $fp = fopen($zipfile, 'rb');
        $content = fread($fp, filesize($zipfile));
        fclose($fp);
		
        function sdmms($uname, $psw, $title, $unum, $cont, $type){
            $ws = 'http://mmsplat.eucp.b2m.cn/MMSCenterInterface/MMS.asmx?wsdl';
            $soapclient = new SoapClient($ws);
			//$soapclient->setOutgoingEncoding("UTF-8");
            $param['userName'] = $uname;
            $param['password'] = $psw;
            $param['title'] = $title;
            $param['userNumbers'] = $unum;
            $param['MMSContent'] = $cont;
            $param['sendType'] = $type;
            $return = $soapclient->__soapCall('SendMMS', array('paramters' => $param));
            $result = $return->SendMMSResult;
            return $result;
        }

        return sdmms($name, $pass, $title, $phone, $content, 1);
        
}

//过期会员的续费判定
function get_determing_status($uid){
	$sql = "select renewalstatus from web_member_admininfo where uid='{$uid}'";
	$res = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	return $res['renewalstatus'];
}

function  get_member_progress($uid){
	$sql = "select memberprogress from web_member_admininfo where uid='{$uid}'";
	$res = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	return $res['memberprogress'];
}


function dump_sql($sql){
	$is_display = true;
	$display = $is_display ? '' : 'none';
	if(is_array($sql)){
		echo "<span style='display:{$display};'>";
		echo var_dump($sql);
		echo "</span>";
	}else{
		echo "<span style='display:{$display};'>$sql</span>";
	}
}

//记录当前有更新数据的id, 主要用于sphinx的检索
function sphinx_remember_uids($location, $uid=0){
	global $memcached,$timestamp;
	$key = 'sphinx_remember_'.$location;
	$uids = array();
	$method = 'set';
	if($uids_ser = $memcached->get($key)){	
		$uids = unserialize($uids_ser);
		$method = 'replace';
	}
	
	if($uid){
		$uids[$uid] = $timestamp;
		return $memcached->$method($key, serialize($uids), 0, 120);	
	}
		
	foreach($uids as $uid=>$time){
		if($timestamp-$time>120) unset($uids[$uid]);
	}
	
	return array_keys($uids);
	
}

/**
 * 时间的友好转换
 * @param int $time 
 */
function getDateStyle_time($time){
	$t=time()-$time;
	$f=array(
			'31536000'=>'年',
			'2592000'=>'个月',
			'604800'=>'星期',
			'86400'=>'天',
			'3600'=>'小时',
			'60'=>'分钟',
			'1'=>'秒'
	);

	foreach ($f as $k=>$v)
	{
		if (0 != $c=floor($t/(int)$k))
		{
			return $c.$v;
		}
	}
}

/**
 * 得到聊天信息总数
 * @param $uid 用户ID
 */
function getChatTotal($uid){
	return getChatList($uid,true);
}

/**
 * 得到聊天信息列表
 * @param $uid
 * @param $os
 * @param $uid2
 * @param $pagenum
 * @param pagesize
 */
function getChatList($uid,$ot=false,$uid2=0,$pagenum=1,$pagesize=10){
	global $_MooClass;
	include_once '../module/chat/ChatMsg.class.php';
	$cm = new ChatMsg($_MooClass['MooMySQL']);
	$data = $cm -> getAllMsg($uid,$pagenum,$pagesize,$uid2,$ot);
	return $data;
}

/**
 * 
 */
function getChatListById($uid,$uid2,$pagenum,$pagesize){
	return getChatList($uid,false,$uid2,$pagenum,$pagesize);
}


//NOTE  根据客服ID得到所在组的组长id
/**
*  argument0 $sid :  客服ID  int
*  return   $gid ： 组长ID  int
*/

function getGroupID($sid){
    $result=$res=array();
    $sql=$id='';
    $sql="select id,manage_list from web_admin_manage where type=1";
    $result=$GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
    foreach($result as $key=>$value){
      $manage_list=$value['manage_list'];
	  $manage_arr=explode(",",$manage_list);
	  if(in_array($sid,$manage_arr)){
	     $id=$value['id'];
		 break;
	  }
    }
   
    $sql="select manage_list from web_admin_manage where type=1 and id={$id}";
    $res=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql,0,0,0,true);
    
	$groupMember=explode(',',$res['manage_list'] );
	
	foreach($groupMember as $gid){
       $sql="select groupid from web_admin_user where uid={$gid}";
	   $re=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql,0,0,0,true);
       if(in_array($re['groupid'],$GLOBALS['admin_all_group'])){
	      return $gid;
		  break;
	   }
    }
   
    return false;
}


?>
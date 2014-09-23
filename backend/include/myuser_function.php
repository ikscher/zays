<?php
/**
 * 我的用户栏目用到的方法
 * @author:fanglin
 */

//得到我的用户总数
function get_myuser_count($where){
	
	$sql = "SELECT COUNT(*) AS count FROM ";
	$sql_table = '';
	$pre = '';
	if(strpos($where, 'm.')){
		$sql_table = "{$GLOBALS['dbTablePre']}members_search m ";
		$pre = 'm';
	}
	if(strpos($where, 'ma.')){
		if($sql_table==''){
			$sql_table = " {$GLOBALS['dbTablePre']}member_admininfo ma ";
			$pre = 'ma';
		}else{
		 	$sql_table .= " LEFT JOIN {$GLOBALS['dbTablePre']}member_admininfo ma
			ON m.uid=ma.uid ";
		}
	}
	
	if(strpos($where, 'b.')){
		$sql_table .= $sql_table=='' ? " {$GLOBALS['dbTablePre']}members_base b " : " LEFT JOIN {$GLOBALS['dbTablePre']}members_base b
			on {$pre}.uid=b.uid ";
	}
			
	if($sql_table == '') $sql_table = "{$GLOBALS['dbTablePre']}members_search m ";
	$sql = $sql.$sql_table.$where;
	$result = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
	return $result['count'];
}

//得到搜索条件
function get_search_condition($where){
	global $h;
	$condition[] = $where;
	
	$sid = MooGetGPC('sid', 'integer', 'G');
	if($sid) {
		$condition[] = " m.sid=$sid";
	}
	if(!empty($_GET['search_date'])){
		if($_GET['search_type'] == 1){
			$field = 'm.regdate';
		}else{
			$field = 'b.allotdate';
		}
		$search_date = strtotime(MooGetGPC('search_date','string','G'));
		$search_date2 = $search_date+60*60*24;
		$condition[] = 	" $field>='{$search_date}' AND $field<='{$search_date2}'";
	}
	
	if(!empty($_GET['uid'])){
		$uid = trim(MooGetGPC('uid','integer','G'));
		$condition[] = " m.uid='{$uid}'";
	}

   //所管理的客服id列表
   $myservice_idlist = get_myservice_idlist(); 
   
   if(empty($myservice_idlist)){
		if($h=='give_up'){
			if(in_array($GLOBALS['groupid'],$GLOBALS['admin_aftersales'])){
				//售后能看到放弃会员列表
			}
		}else{
			$condition[] = " m.sid IN({$GLOBALS['adminid']})";
		}
   }elseif($myservice_idlist == 'all'){
   		//all为客服主管能查看所有的
   }elseif($h=='give_up'){
		if(in_array($GLOBALS['groupid'],$GLOBALS['admin_aftersales'])){
				//售后能看到放弃会员列表
		}else{
			$condition[] = " m.sid IN($myservice_idlist)";
		}
   }else{
   		$condition[] = " m.sid IN($myservice_idlist)";
   }
	if($GLOBALS['adminid'] == 23 && $h == 'high_member'){//针对售后组长（王海红）显示过期的
	   $condition[] = "m.endtime < ".time();
	}

	$result = implode(' AND ',$condition);
	/*
	 *如果是销售客服，返回WHERE ma.effect_grade=7 AND m.sid IN(62) 
	 *如果是组长，返回WHERE ma.effect_grade=7 AND m.sid IN(16,22,26,30,31,39,51,62,66,69,74,82,86,88,162,211,213,216,222,229)
	 *如果是$myservice_idlist == 'all'，返回的是 WHERE ma.effect_grade=7
	 */
//	echo $result;die;
	return $result;
}


function get_search_link(){
	$result = '';
	if($_GET['search_date']){
		$search_date = MooGetGPC('search_date','string','G');
		$search_type = MooGetGPC('search_type','string','G');
		$result .= "&search_date=".$search_date;
		$result .= "&search_type=".$search_type;	
	
	}
    //排序用
    if($_GET['field']){
    	$field = MooGetGPC('field','string','G');
    	$order = MooGetGPC('order','string','G');
    	$result .= "&field={$field}&order={$order}";
    }
	if($_GET['sid']){
		$sid = MooGetGPC('sid','integer','G');
		$result .= "&sid={$sid}";
	}
    return $result;
}

//得到我的用户列表
function get_myuser_list($where, $sql_sort, $limit=''){
	
//	$sql = "SELECT m.uid,m.username,m.telphone,m.gender,m.birthyear,m.salary,m.province,b.is_awoke,m.nickname,
//			m.city,b.allotdate,m.regdate,m.sid,l.last_login_time,m.s_cid,m.bgtime,m.endtime,l.login_meb,b.source,
//			ma.next_contact_time,ma.master_member,ma.real_lastvisit
//			FROM {$GLOBALS['dbTablePre']}members_search m
//			left join {$GLOBALS['dbTablePre']}members_base b on m.uid=b.uid
//			left join {$GLOBALS['dbTablePre']}members_login l on m.uid=l.uid
//			LEFT JOIN {$GLOBALS['dbTablePre']}member_admininfo ma
//			ON m.uid=ma.uid 
//			{$where}
//			{$sql_sort} {$limit}";
			
   //$where =WHERE ma.effect_grade=3 AND m.sid=0  
   //add start
   $login_sort = '';
  if (strpos($sql_sort,'last_login_time')||strpos($sql_sort,'login_meb')||(strpos($sql_sort,'lastvisit')&&!strpos($sql_sort,'real_lastvisit'))){
  	$login_sort = $sql_sort;
  	$sql_sort   = '';
  }
   $sql_uid = "SELECT m.uid,m.username,m.telphone,m.gender,m.birthyear,m.salary,m.province,m.nickname,
			m.city,m.regdate,m.sid,m.s_cid,m.bgtime,m.endtime,
			ma.next_contact_time,ma.master_member,ma.real_lastvisit
			FROM {$GLOBALS['dbTablePre']}members_search m
			LEFT JOIN {$GLOBALS['dbTablePre']}member_admininfo ma
			ON m.uid=ma.uid 
			LEFT JOIN {$GLOBALS['dbTablePre']}members_base b
			on m.uid=b.uid
			{$where} {$sql_sort} {$limit}
			";
    $ids_res_arr = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql_uid,0,0,0,true);
 	if($ids_res_arr){
	    $save_goon_id = array();
	    for($i=0;$i<20;$i++){
	    	if(!empty($ids_res_arr[$i])){
	    		if($ids_res_arr[$i]['uid'] != null){
	    			$save_goon_id[]=$ids_res_arr[$i]['uid'];
	    		}
	    	}
	    }
	    $goonids_str = implode(',', $save_goon_id);
	    if (empty($login_sort)){
	    	$login_sort = " order by field(b.uid,$goonids_str)";
	    }
	    $sql_get_bl = "
	       SELECT b.is_awoke,b.allotdate,l.last_login_time,l.login_meb,b.source FROM {$GLOBALS['dbTablePre']}members_base b left join {$GLOBALS['dbTablePre']}members_login l on b.uid=l.uid 
	        LEFT JOIN {$GLOBALS['dbTablePre']}member_admininfo ma
			ON b.uid=ma.uid  where b.uid in (".$goonids_str.") $login_sort";
	    
	    $bl_res = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql_get_bl,0,0,0,true);
	    //merge
	    $count_goonres = count($ids_res_arr);
	    $merge_all_arr=array();
	    for($i=0;$i<$count_goonres;$i++){
	    	if(!empty($bl_res[$i])){
	    		$merge_all_arr[] = array_merge($ids_res_arr[$i],$bl_res[$i]);
	    	}
	    }
	   
	    $result= $merge_all_arr;
	    return $result;
    }else{
    	return false;
    }
    
    //add end
// $result =$GLOBALS['_MooClass']['MooMySQL']->getAll($sql); 
// return $result;

}

function get_telphone($member_list){
	$arr=null;
	if(!empty($member_list['telphone'])){
		foreach($member_list as $v){
			if(!empty($v['telphone'])){
				$tel_array[] = "'$v[telphone]'";
			}
		}
		
			$tel_list = join(',',$tel_array);
			$sql_tel="SELECT COUNT(*) c,telphone FROM web_members_search WHERE telphone IN ($tel_list) GROUP BY telphone";
			$result =$GLOBALS['_MooClass']['MooMySQL']->getAll($sql_tel);
			foreach($result as $k=>$v){
				$arr[$v['telphone']] = $v['c']; 
			}
	}
	return $arr;
}

//note 得到毁单会员的理由
function get_destory_order($uid){
	$destoryinfo = '';
	$i = 1;
	$sql = "select uid,effect_grade,interest,different,service_intro,next_contact_desc,comment from {$GLOBALS['dbTablePre']}member_backinfo where uid='{$uid}' and effect_grade='7'";
	$res = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	if($res){
		foreach($res as $k=>$v){
			$arr[] = $i++;
			$arr[] = '.';
			$arr[] = $v['interest'];
			$arr[] = $v['different'];
			$arr[] = $v['service_intro'];
			$arr[] = $v['next_contact_desc'];
			$arr[] = $v['comment'];
		}
		$destoryinfo = implode(' ',$arr);
	}
	return $destoryinfo;
}

?>
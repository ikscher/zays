<?php
require '../../../framwork/MooPHP.php';


	        //按ID搜索
	        if (isset($_GET['search_type']) && $_GET['search_type'] == 2) {
	            $search_uid = trim(MooGetGPC('search_uid', 'integer', 'G'));
	            header("Location:index.php?n=search&h=nickid&info=" . $search_uid);
	            exit;
	        }
	        //note 性别
	        $gender = isset($_GET['gender']) ? trim(MooGetGPC('gender', 'integer', 'G')) : 1;
	
	        //note 年龄范围
	        $age_start = isset($_GET['age_start']) ? trim(MooGetGPC('age_start', 'integer', 'G')) : 0;
	        $age_end = isset($_GET['age_end']) ? trim(MooGetGPC('age_end', 'integer', 'G')) : 0;
	
	        //note 工作省、城市
	        $work_province = isset($_GET['workprovince']) ? trim(MooGetGPC('workprovince', 'integer', 'G')) : 0;
	        $work_city = isset($_GET['workcity']) ? trim(MooGetGPC('workcity', 'integer', 'G')) : 0;
	        
	      
			
	        //note 是否显示相片
	        $photo = isset($_GET['photo']) ? trim(MooGetGPC('photo', 'integer', 'G')) : '0';
			//echo $gender,"<br />",$age_start,"<br />",$age_end,"<br />",$work_province,"<br />",$work_city,"<br />",$photo;die;
			/**if(isset($_GET['searchid'])){
				$search_id = trim(MooGetGPC('searchid', 'integer', 'G'));
				search_index($search_id, $gender, $age_start, $age_end, $work_province, $work_city, $photo, $marriage=0, $salary=0, $education=0, $height1=0, $height2=0, $home_townprovince=0, $home_towncity=0, $house=0, $vehicle=0);
				exit;
			}*/
	        //note 快速搜索处理功能函数
	        if($age_start == -1) $age_start = 0;
	        if($age_end == -1) $age_end = 0;
	        if($work_province == -1) $work_province = 0;
	        elseif($work_province == -2) $work_province = 2;
	        if($work_city == -1) $work_city = 0;
	        quick_search_page($gender, $age_start, $age_end, $work_province, $work_city, $photo);
	        
	        
	        /**
 * 新快速查询
 * 采用sphinx查询
 * @author likefei
 * @date 2011-11-14
 */
function quick_search_page($gender, $age_start, $age_end, $work_province, $work_city, $photo) {
	global $_MooClass, $dbTablePre;
    global $user_arr, $userid,$last_login_time,$isdelcond,$isselectarea,$isdispmore,$isresult;
    
    $currenturl = 'http://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
    $currenturl2 = preg_replace("/(&page=\d+)/", "", $currenturl);
	if(isset($_GET['condition']) && $_GET['condition'] == '2') {
        $pagesize = 6;
    }else if(isset($_GET['condition']) && $_GET['condition'] == '3') {
        $pagesize = 16;
    }else{
        $pagesize = 6;
    }
    $page = 1;
    if(isset($_GET['page']) && $_GET['page']) $page = $_GET['page'];
    $offset = ($page-1)*$pagesize;
	$blacklist = BlackList();
	
	$index = 'members_women';
	if($gender == 0) $index = 'members_man';
	if($age_start || $age_end){
		if($age_start==0) $age_start=18;
		if($age_end==0) $age_end=99;
	}
	
//	$sort_str = 'city_star desc,s_cid asc,@weight desc,city desc,province asc';
//	$sortway = '1';
//	if(isset($_GET['sortway']) && $_GET['sortway'] == '2'){
//		//note 最新注册
//		$sortway = '2';
//		$sort_str = 'regdate desc,city_star desc,s_cid asc,@weight desc,city desc';
//	}elseif(isset($_GET['sortway']) && $_GET['sortway'] == '3'){
//		//note 诚信等级
//		$sortway = '3';
//		$sort_str = 'certification desc,city_star desc,s_cid asc,@weight desc,city desc';
//	}
	
	$cond[] = array('is_lock','1',false);
	if($photo) $cond[] = array('images_ischeck','1',false);
	$cond[] = array('usertype','1',false);
	if(is_array($blacklist) && !empty($blacklist))  $cond[] = array('@id',implode('|',$blacklist),true);
	if($age_start && $age_end){
		$year = date('Y',time());
		$cond[] = array('birthyear',array(($year-$age_end),($year-$age_start)),false);
	}
	if($work_province) $cond[] = array('province',$work_province,false);
	elseif(isset($user_arr['province']) && $user_arr['province'] && !$work_city) $cond[] = array('province',$user_arr['province'],false);
	if($work_city) $cond[] = array('city',$work_city,false);
	elseif(isset($user_arr['city']) && $user_arr['city'] && !$work_province) $cond[] = array('city',$user_arr['city'],false);
	$dispmore=false;
	if($work_province || $work_city){
		$isselectarea = true;
		$isdispmore = false;
		$dispmore = MooGetGPC('dm');
		if($dispmore) $isdispmore=true;
	}
	
	$sortway = MooGetGPC('sortway');
	$sort_str = getSortsStr($sortway,$cond);
	
	$rs = sphinx_search($index,$cond,$sort_str,$page,$pagesize);
	
	if(!$rs || is_array($rs) && empty($rs)){//没有结果
		//MooMessage("您指定的搜索不存在或已过期", "index.php?n=search", '03');
		include MooTemplate('public/search_error', 'module');
	    exit;
	}else{
		$data = $rs['user'];

		$total = $rs['total'];
		if($total > 600) $total = 600;
		$user_list = implode(',',$data);
		if(empty($data)) {
			//MooMessage("您指定的搜索不存在或已过期", "index.php?n=search", '03');
			include MooTemplate('public/search_error', 'module');
	        exit;
		}else{
			$user = array();
			$sortway = isset($_GET['sortway']) ? $_GET['sortway'] : '1';
	        $sql="select s.*,b.mainimg,b.showinformation_val from `{$dbTablePre}members_search` s left join `{$dbTablePre}members_base` b on s.uid=b.uid where s.uid in ({$user_list})";
	        
	        $user = $_MooClass['MooMySQL']->getAll($sql);
			
			$sql="select lastvisit from web_members_login where uid in ({$user_list})";
			$user_lastvisit=$_MooClass['MooMySQL']->getAll($sql);
			
			foreach($user_lastvisit as $key=>$val){
			   $user[$key]['lastvisit']=$val['lastvisit'];
			}

	    }
		//会员基本信息补充
		if($user){
			$user = userbasicadd($user);
			//排序
			$tmp_user = array();
			foreach($data as $k=>$v){
				foreach($user as $key=>$val){
					if($v == $val['uid']){
						$tmp_user[] = $user[$key];
						break;
					}
				}
			}
			$user = $tmp_user;
			foreach($user as $va){
				echo $va['nickname'].'<br />';
			}
			exit;
		}

	}    
}




//基本查询用户信息补充
function userbasicadd($users=array()) {
	global $_MooClass, $dbTablePre;
	if(!$users) {
		return;
	}
	$allowarr = array();
	foreach($users as $val) {
		$allowarr[] = $val['uid'];
	}
	$users1 = array();
	if(MOOPHP_ALLOW_FASTDB) {
		foreach($allowarr as $uid) {
			$users1[] = MooFastdbGet('members_search', 'uid', $uid);
		}
	} else {
		$allowstr = implode(',', $allowarr);
		$users1 = $_MooClass['MooMySQL']->getAll("select gender, nickname from {$dbTablePre}members_search where uid in(".$allowstr.")");
	}
	$count = count($users);
	for($i = 0; $i < $count; $i++) {
		$users[$i] = array_merge($users[$i], $users1[$i]);
	}
	return $users;
}
	    
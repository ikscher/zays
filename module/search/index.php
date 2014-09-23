<?php

/* * ************************************* 逻辑层(M)/表现层(V) *************************************** */

include "./module/{$name}/function.php";
include "./module/{$name}/advance_search.php";
include "./module/{$name}/basic_search.php";

/**
 * 功能列表
 */

//note 基本查询调用模板
function basic_search() {
    global $_MooClass, $dbTablePre, $userid, $user, $user_arr,$last_login_time;
    //note 显示这个用户所有的搜索条件
	if($userid!='') $search_arr = $_MooClass['MooMySQL']->getAll("SELECT * FROM {$dbTablePre}searchbak WHERE uid = '$userid'");
    include MooTemplate('public/search_basic', 'module');
}

//note 高级查询调用模板
function advance_search() {
    global $_MooClass, $dbTablePre, $user, $user_arr,$last_login_time;
    include MooTemplate('public/search_advance', 'module');
}

//note 编辑搜索
function edit_search($s) {
    global $_MooClass, $dbTablePre, $user, $user_arr, $userid,$last_login_time;
    $sr = $_MooClass['MooMySQL']->getOne("select * from `{$dbTablePre}searchbak` where `scid` = '$s'");
	$body = $sr['searchcondition'];
	//note 是否发送邮箱
	$is_commend = $sr['is_commend'];
//	echo $sr['searchcondition'];
//	echo "<br>是否发送邮箱=".$is_commend;
    if (empty($sr)) {
        MooMessage("对不起，没有找到相应的搜索条件", "index.php?n=search&h=list");
    }

    if ($sr["uid"] != $userid) {
        MooMessage("对不起，这不是您的搜索条件", "index.php?n=search&h=list");
    }

    include MooTemplate('public/search_edit', 'module');
}

//note 显示搜索条件
function list_search() {
    global $_MooClass, $dbTablePre, $userid, $user_arr,$last_login_time;
    $sr = $_MooClass['MooMySQL']->getAll("select * from `{$dbTablePre}searchbak` where uid='$userid'");
    include MooTemplate('public/search_list', 'module');
}

function list_ser() {
    global $_MooClass, $dbTablePre, $userid, $user_arr,$last_login_time;
    return $_MooClass['MooMySQL']->getAll("select * from `{$dbTablePre}searchbak` where uid='$userid'");
}

//note 直接查询以前的搜索条件
function show_search($scid) {
    global $_MooClass, $dbTablePre, $user_arr,$last_login_time;
//	echo $scid;die;
    //note 获取搜索条件
    $sr = $_MooClass['MooMySQL']->getOne("select * from `{$dbTablePre}searchbak` where `scid` = '$scid'");
    //note 以下是不同的会员信息正则转换
    $body = $sr['searchcondition'];
    preg_match("/<gender>(.*)<\/gender>/isU", $body, $gender_arr);
    $gender = $gender_arr[1];

    preg_match("/<age_start>(.*)<\/age_start>/isU", $body, $age_start_arr);
    $age_start = $age_start_arr[1];

    preg_match("/<age_end>(.*)<\/age_end>/isU", $body, $age_end_arr);
    $age_end = $age_end_arr[1];

    preg_match("/<work_province>(.*)<\/work_province>/isU", $body, $work_province_arr);
    $work_province = $work_province_arr[1];

    preg_match("/<work_city>(.*)<\/work_city>/isU", $body, $work_city_arr);
    $work_city = $work_city_arr[1];

    preg_match("/<marriage>(.*)<\/marriage>/isU", $body, $marriage_arr);
    if ($marriage_arr[1] == 0 || $marriage_arr[1] == '-2')
        $marriage = $marriage_arr[1];
    else
        $marriage = explode(",", $marriage_arr[1]);

    preg_match("/<salary>(.*)<\/salary>/isU", $body, $salary_arr);
    if ($salary_arr[1] == 0 || $salary_arr[1] == '-2')
        $salary = $salary_arr[1];
    else
        $salary = explode(",", $salary_arr[1]);

    preg_match("/<education>(.*)<\/education>/isU", $body, $education_arr);
    if ($education_arr[1] == 0 || $education_arr[1] == '-2')
        $education = $education_arr[1];
    else
        $education = explode(",", $education_arr[1]);

    preg_match("/<height1>(.*)<\/height1>/isU", $body, $height1_arr);
    $height1 = $height1_arr[1];

    preg_match("/<height2>(.*)<\/height2>/isU", $body, $height2_arr);
    $height2 = $height2_arr[1];

    preg_match("/<weight1>(.*)<\/weight1>/isU", $body, $weight1_arr);
    $weight1 = $weight1_arr[1];

    preg_match("/<weight2>(.*)<\/weight2>/isU", $body, $weight2_arr);
    $weight2 = $weight2_arr[1];

    preg_match("/<body1>(.*)<\/body1>/isU", $body, $body_arr);
    if (isset($body_arr[1]) && ($body_arr[1] == 0 || $body_arr[1] == '-2'))
        $body1 = $body_arr[1];
    elseif(isset($body_arr[1]))
        $body1 = explode(",", $body_arr[1]);
    else $body1 = 0;

    preg_match("/<smoking>(.*)<\/smoking>/isU", $body, $smoking_arr);
    if ($smoking_arr[1] == 0 || $smoking_arr[1] == '-2')
        $smoking = $smoking_arr[1];
    else
        $smoking = explode(",", $smoking_arr[1]);

    preg_match("/<drinking>(.*)<\/drinking>/isU", $body, $drinking_arr);
    if ($drinking_arr[1] == 0 || $drinking_arr[1] == '-2')
        $drinking = $drinking_arr[1];
    else
        $drinking = explode(",", $drinking_arr[1]);

    preg_match("/<occupation>(.*)<\/occupation>/isU", $body, $occupation_arr);
    if ($occupation_arr[1] == 0 || $occupation_arr[1] == '-2')
        $occupation = $occupation_arr[1];
    else
        $occupation = explode(",", $occupation_arr[1]);

    preg_match("/<house>(.*)<\/house>/isU", $body, $house_arr);
    if ($house_arr[1] == 0 || $house_arr[1] == '-2')
        $house = $house_arr[1];
    else
        $house = explode(",", $house_arr[1]);

    preg_match("/<vehicle>(.*)<\/vehicle>/isU", $body, $vehicle_arr);
    if ($vehicle_arr[1] == 0 || $vehicle_arr[1] == '-2')
        $vehicle = $vehicle_arr[1];
    else
        $vehicle = explode(",", $vehicle_arr[1]);

    preg_match("/<corptype>(.*)<\/corptype>/isU", $body, $corptype_arr);
    if ($corptype_arr[1] == 0 || $corptype_arr[1] == '-2')
        $corptype = $corptype_arr[1];
    else
        $corptype = explode(",", $corptype_arr[1]);

    preg_match("/<children>(.*)<\/children>/isU", $body, $children_arr);
    if ($children_arr[1] == 0 || $children_arr[1] == '-2')
        $children = $children_arr[1];
    else
        $children = explode(",", $children_arr[1]);

    preg_match("/<wantchildren>(.*)<\/wantchildren>/isU", $body, $wantchildren_arr);
    if ($wantchildren_arr[1] == 0 || $wantchildren_arr[1] == '-2')
        $wantchildren = $wantchildren_arr[1];
    else
        $wantchildren = explode(",", $wantchildren_arr[1]);

    preg_match("/<home_townprovince>(.*)<\/home_townprovince>/isU", $body, $home_townprovince_arr);
    $home_townprovince = $home_townprovince_arr[1];

    preg_match("/<home_towncity>(.*)<\/home_towncity>/isU", $body, $home_towncity_arr);
    $home_towncity = $home_towncity_arr[1];

    preg_match("/<nation>(.*)<\/nation>/isU", $body, $nation_arr);
    $nation = $nation_arr[1];


    preg_match("/<animalyear>(.*)<\/animalyear>/isU", $body, $animalyear_arr);
    if ($animalyear_arr[1] == 0 || $animalyear_arr[1] == '-2')
        $animalyear = $animalyear_arr[1];
    else
        $animalyear = explode(",", $animalyear_arr[1]);

    preg_match("/<constellation>(.*)<\/constellation>/isU", $body, $constellation_arr);
    if ($constellation_arr[1] == 0 || $constellation_arr[1] == '-2')
        $constellation = $constellation_arr[1];
    else
        $constellation = explode(",", $constellation_arr[1]);

    preg_match("/<bloodtype>(.*)<\/bloodtype>/isU", $body, $bloodtype_arr);
    if ($bloodtype_arr[1] == 0 || $bloodtype_arr[1] == '-2')
        $bloodtype = $bloodtype_arr[1];
    else
        $bloodtype = explode(",", $bloodtype_arr[1]);

    preg_match("/<religion>(.*)<\/religion>/isU", $body, $religion_arr);
    if ($religion_arr[1] == 0 || $religion_arr[1] == '-2')
        $religion = $religion_arr[1];
    else
        $religion = explode(",", $religion_arr[1]);

    preg_match("/<family>(.*)<\/family>/isU", $body, $family_arr);
    if ($family_arr[1] == 0 || $family_arr[1] == '-2')
        $family = $family_arr[1];
    else
        $family = explode(",", $family_arr[1]);

    preg_match("/<language1>(.*)<\/language1>/isU", $body, $language_arr);
    if (isset($language_arr[1]) && ($language_arr[1] == 0 || $language_arr[1] == '-2'))
        $language = $language_arr[1];
    elseif(isset($language_arr[1]))
        $language = explode(",", $language_arr[1]);
    else
    	$language = 0;
    
    preg_match("/<photo>(.*)<\/photo>/isU", $body, $photo_arr);
    $photo = $photo_arr[1];
    advance_search_page($gender, $age_start, $age_end, $work_province, $work_city, $marriage, $salary, $education, $height1, $height2, $weight1, $weight2, $body1, $smoking, $drinking, $occupation, $house, $vehicle, $corptype, $children, $wantchildren, $home_townprovince, $home_towncity, $nation, $animalyear, $constellation, $bloodtype, $religion, $family, $language, $photo);
}

//note 删除搜索条件
function del_search($s) {
    global $_MooClass, $dbTablePre, $user_arr,$last_login_time;
    $sr = $_MooClass['MooMySQL']->query("delete from `{$dbTablePre}searchbak` where `scid` = '$s'");
    header("Location:index.php?n=search&h=list");
}

//note 按照昵称或者id搜索
function nickid_search($s) {
    global $_MooClass, $dbTablePre, $user_arr, $userid,$last_login_time;
    $returnurl = 'index.php?' . $_SERVER['QUERY_STRING']; //返回的url
    $url3 = 0;
    $is_online_url = 0;
    $is_data = true;
    $page = 1;
	//note  每页显示个数
	if (isset($_GET['condition']) && $_GET['condition'] == '2') {
		$pagesize = 6;
	} else if (isset($_GET['condition']) && $_GET['condition'] == '3') {
		$pagesize = 16;
	} else {
		$pagesize = 6;
	}
//	echo $s;die;
	if(empty($s)){
		MooMessage("请输入用户ID号！", "index.php?n=search");
		exit;
	}
    $s = trim($s);
	$user = array();
    if (is_numeric($s)) {
        if(MOOPHP_ALLOW_FASTDB){
            $user[0] = MooFastdbGet('members_search', 'uid', $s);
            $user2 = MooFastdbGet('members_base', 'uid', $s);
            if(is_array($user[0]) && !empty($user[0]))
            	$user[0] = array_merge($user[0],$user2);
            if ($user[0]['is_lock'] != 1) {
                $user[0] = array();
            }
        } else {
            $user[0] = $_MooClass['MooMySQL']->getOne("select s.*,b.mainimg from `{$dbTablePre}members_search` s left join `{$dbTablePre}members_base` b on s.uid=b.uid where s.is_lock = 1 and s.uid = '$s'");
        }
		$sortway = isset($_GET['sortway']) ? $_GET['sortway'] : '1';
		$sortway = intval($sortway);
		if(empty($user[0])){
			MooMessage("非常抱歉!此会员资料已关闭！", "index.php?n=search",'01');
			exit;
		}
        //未找到,准备推荐
//        if (empty($u)) {
//            $ps = '非常抱歉!没有您所找的会员，在您附近地区还有以下会员，您看看有中意的吗？';
//            $u = $_MooClass['MooMySQL']->getAll("select * from `{$dbTablePre}members` where gender !='{$user_arr['gender']}' and city='{$user_arr['city']}' and is_lock='1'  LIMIT 6");
//            $p_n = 6 - count($u);
//            if ($p_n > 0) {
//                $tmp = $_MooClass['MooMySQL']->getAll("select * from `{$dbTablePre}members` where gender !='{$user_arr['gender']}' and province='{$user_arr['province']}' and city!='{$user_arr['city']}' and is_lock='1'  LIMIT $p_n");
//                $u = $u + $tmp;
//            }
//            $user1 = $u;
//        } else {
//            $user1[] = $u;
//        }

//		print_r($user);die;
		$gender = !empty($user[0]['gender']) ? $user[0]['gender'] : '0';
		$age_start = !empty($user[0]['birthyear']) ? (date("Y") - $user[0]['birthyear']) : 0;
		$age_end = 0;
		$work_province = !empty($user[0]['province']) ? $user[0]['province'] : 0;
		$work_city = !empty($user[0]['city']) ? $user[0]['city'] : 0;
		$photo = !empty($user[0]['photo']) ? $user[0]['photo'] : '1';

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
		include MooTemplate('public/'.$htm, 'module');
    } else {
		//note 获得第几页
		if(isset($_GET['page'])) 
        	$page = intval(max(1, $_GET['page']));
		$sql = "select count(1) as count from `{$dbTablePre}members_search` where is_lock=1 and nickname='$s' limit 600";
		$count = $_MooClass['MooMySQL']->getOne($sql);
		//总条数
		$total = $count['count'];
		if (empty($total)) {
            MooMessage("非常抱歉!没有您所找的会员！", "index.php?n=search",'01');
        }
		//总页数
		$total_pages = ceil($total / $pagesize);
        $page = min($page, $total_pages);
		
		$is_data = true;
		$offset = ($page - 1) * $pagesize;
		
        $user = $_MooClass['MooMySQL']->getAll("select s.*,b.mainimg from `{$dbTablePre}members_search` s,`{$dbTablePre}members_base` b on s.uid=b.uid where is_lock = 1 and nickname = '$s' limit {$offset}, $pagesize");
		$gender = !empty($user['0']['gender']) ? $user['0']['gender'] : '0';
		$age_start = !empty($user['0']['birthyear']) ? (date("Y") - $user['0']['birthyear']) : 0;
		$age_end = 0;
		$work_province = !empty($user['0']['province']) ? $user['0']['province'] : 0;
		$work_city = !empty($user['0']['city']) ? $user['0']['city'] : 0;
		$photo = !empty($user['0']['photo']) ? $user['0']['photo'] : 0;
		//note 选择不同的显示模式
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
		include MooTemplate('public/'.$htm, 'module');
    }
}

//note 谁在找我搜索
//function look_search($memberid) {
//    global $_MooClass, $dbTablePre, $user_arr,$userid,$last_login_time;
//	
//    $url3 = 0;
//    $is_online_url = 0;
//    if ($memberid) {
//        //note  每页显示个数
//        if ($_GET['condition'] == '2') {
//            $pagesize = 6;
//        } else if ($_GET['condition'] == '3') {
//            $pagesize = 16;
//        } else {
//            $pagesize = 6;
//        }
//
//        //note 获得第几页
//        $page = intval(max(1, $_GET['page']));
//
//        $user_1 = $_MooClass['MooMySQL']->getOne("select * from `{$dbTablePre}members_search` as m,`{$dbTablePre}members_base` as mf where m.uid=mf.uid and m.is_lock = 1 and m.uid='$memberid'");
//        //note 基本SQL语句
//		$sql = "select uid from {$dbTablePre}choice where";
//        $count_sql = "select count(*) as count";
//		$pre_sql = " from {$dbTablePre}members_search where is_lock ='1'";
//        //note 性别符合
//        if ($user_1['gender'] == '0') {
//            $pre_sql .= "and gender=1 ";
//        } elseif ($user_1['gender'] == '1') {
//            $pre_sql .= " and gender=0 ";
//        }
//        //note 年龄上的符合
//        $age = date('Y', time()) - $user_1['birthyear'];
//        $sql .= " age1 <= $age and (age2 >= $age or age2 = -1)";
//		
//        //note 身高上的符合
//        $height = $user_1['height'] ? $user_1['height'] : -1;
//         $sql .= " and height1 <= $height and (height2 = -1 or height2 >= $height) ";
//
//        //note 体重上的符合
//        $weight = $user_1['weight'] ? $user_1['weight'] : -1;
//        $sql .= " and (weight1 = -1 or weight1<= $weight) and (weight2 = -1 or weight2 >= $weight) ";
//
//        //note 是否有图片上的符合
//        if (!$user_1['mainimg']) {
//            $sql .= " and hasphoto=-1 ";
//        }
//		
//		//省市
//        $city = $user_1["city"] ? $user_1["city"] : -1;
//		$province = $user_1["province"] ? $user_1["province"] : -1;
//        if ($province != -1) {
//            $sql .= " and (workprovince = -1 or workprovince = '$province')";
//
//            if ($city != -1) {
//                $sql .= " and (workcity = -1 or workcity = '$city')";
//            }
//        } else {
//            $sql .= " and workcity = -1 and workprovince = -1";
//		}
//
//        // 婚姻
//		$marriage = $user_1["marriage"] ? $user_1["marriage"] : -1;
//        if ($marriage != 1) {
//            $sql .= " and (marriage = -1 or marriage = '$marriage')";
//        }
//
//        // 教育
//		$education = $user_1["education"] ? $user_1["education"] : -1;
//        $sql .= " and education <= '$education'";
//		
//        // 收入
//		$salary = $user_1["salary"] ? $user_1["salary"] : -1;
//		$sql .= " and salary <= '$salary'";
//
//        // 有无孩子
//		$children = $user_1["children"] ? $user_1["children"] : -1;
//		$sql .= " and (children = -1 or children = '$children')";
//		
//        // 择偶性格
//		$nature = $user_1["nature"] ? $user_1["nature"] : -1;
//        $sql .= " and (nature = -1 or nature = '$nature')";
//
//        // 体型
//		$body = $user_1["body"] ? $user_1["body"] : -1;
//        $sql .= " and (body = -1 or body = '$body')";
//
//        // 职业
//		$occupation = $user_1["occupation"] ? $user_1["occupation"] : -1;
//        $sql .= " and (occupation = -1 or occupation = '$occupation')";
//
//        // 籍贯
//		$hometowncity = $user_1["hometowncity"] ? $user_1["hometowncity"] : -1;
//		$hometownprovince = $user_1["hometownprovince"] ? $user_1["hometownprovince"] : -1;
//        if ($hometownprovince != -1) {
//            $sql .= " and (hometownprovince = -1 or hometownprovince = '$hometownprovince')";
//
//            if ($hometowncity != -1) {
//                $sql .= " and (hometowncity = -1 or hometowncity = '$hometowncity')";
//            }
//        } else {
//            $sql .= " and hometowncity = -1 and hometownprovince = -1";
//		}
//
//        // 民族
//		$nation = $user_1["nation"] ? $user_1["nation"] : -1;
//        $sql .= " and (nation = -1 or nation = '$nation')";
//
//        // 是否要孩子
//		$wantchildren = $user_1["wantchildren"] ? $user_1["wantchildren"] : -1;
//		$sql .= " and (wantchildren = -1 or wantchildren = '$wantchildren')";
//
//        // 喝酒
//		$drinking = $user_1["drinking"] ? $user_1["drinking"] : -1;
//        if ($drinking != 1) {
//            $sql .= " and drinking <= 0";
//        }
//
//        // 抽烟
//		$smoking = $user_1["smoking"] ? $user_1["smoking"] : -1;
//        if ($smoking != 1) {
//            $sql .= " and smoking <= 0";
//        }
//		
//        echo $sql;
//		//获取符合条件的会员uid
//		$result = $_MooClass['MooMySQL']->getAll($sql." limit 0, 600");
//		$allowarr = array();
//		
//		foreach($result as $uid) {
//			$allowarr[] = $uid['uid'];
//		}
//		$allowstr = implode(',', $allowarr);
//		
//		//筛选会员
//        $total_count = $_MooClass['MooMySQL']->getOne($count_sql.$pre_sql." and uid in({$allowstr})");
//		
//        $total_condition = $total = $total_count['count'];
//
//        if ($total == 0) {
//            include MooTemplate('public/search_error', 'module');
//            exit;
//        }
//
//        $total_pages = ceil($total / $pagesize);
//        $page = min($page, $total_pages);
//		
//		$sortway = isset($_GET['sortway']) ? $_GET['sortway'] : '1';
//		$sortway = intval($sortway);
//		$sort_str = '';
//		if($user_arr['s_cid'] != 2) {
//			$sort_str = ', `usertype` asc';
//		}
//		if($sortway == '1'){
//			//note 默认排序
//			$sort = "`city_star` DESC{$sort_str},";
//		}elseif($sortway == '2'){
//			//note 最新注册排序
//			$sort = "`regdate` DESC, `city_star` desc{$sort_str},";
//		
//		}elseif($sortway == '3'){
//			//note 诚信等级排序
//			$sort = "`certification` DESC, `city_star` desc{$sort_str},";
//		}else{
//			//note 默认排序
//			$sort = "`city_star` DESC{$sort_str},";
//		}
//		$sort .= " images_ischeck desc,";
//        $is_data = true;
//		$offset = ($page - 1) * $pagesize;
//		$user = $_MooClass['MooMySQL']->getAll("select * {$pre_sql} and uid in({$allowstr}) order by {$sort} s_cid asc, birthyear desc limit {$offset}, $pagesize");
//		echo '<br/>'."select * {$pre_sql} and uid in({$allowstr}) order by {$sort} s_cid asc, birthyear desc limit {$offset}, $pagesize";
//		if(!empty($memberid)){
//			$gender = !empty($user_arr['gender']) ? $user_arr['gender'] : '0';
//			$age_start = !empty($user_arr['birthyear']) ? (date("Y") - $user_arr['birthyear']) : -1;
//			$age_end = 0;
//			$work_province = !empty($user_arr['province']) ? $user_arr['province'] : 0;
//			$work_city = !empty($user_arr['city']) ? $user_arr['city'] : 0;
//			$photo = !empty($user_arr['photo']) ? $user_arr['photo'] : '1';
//			$searchme = "yes";
//		}
//		
//        //note 选择不同的显示模式
//		if ($_GET['condition'] == '2') {
//			$condition = '2';
//			$htm = 'search_page';
//		} else if ($_GET['condition'] == '3') {
//			$condition = '3';
//			$htm = 'search_photo';
//		} else {
//			$condition = '2';
//			$htm = 'search_page';
//		}
//		include MooTemplate('public/'.$htm, 'module');
//    }
//}
function look_search($memberid) {
    global $_MooClass, $dbTablePre, $user_arr,$userid,$last_login_time,$memcached;
	//if(isset($user_arr['uid']) && $user_arr['uid']) $memberid=$user_arr['uid'];

    $url3 = 0;
    $is_online_url = 0;
    if($memberid) {
        //note 每页显示个数
        if (isset($_GET['condition']) && $_GET['condition'] == '2') {
            $pagesize = 6;
        } else if (isset($_GET['condition']) && $_GET['condition'] == '3') {
            $pagesize = 16;
        } else {
            $pagesize = 6;
        }
		$index = 'choice';
        //note 获得第几页
        $page = 1;
        if(isset($_GET['page']))
        	$page = intval(max(1, $_GET['page']));
        $offset = ($page-1)*$pagesize;
        $limit = array($offset,$pagesize);
        //$sorts = array();
        $sortway = isset($_GET['sortway']) ? $_GET['sortway'] : '1';
		$sortway = intval($sortway);
		if($sortway=='2'){
			$sort[] = 'regdate desc';
		}else if($sortway == '3'){
			$sort[] = 'certification desc';
		}
		$sort[] = 'city_star desc';
		$sort[] = 'usertype desc';
		//$sort[] = '@weight desc';
		$sort[] = 's_cid asc';
		$sort[] = 'images_ischeck desc';
		$sort[] = 'birthyear desc';
		$sort = array_slice($sort,0,5);
		$sort_str = implode(',',$sort);
		//$sorts = array('mode' => 'extended','field' => $sort_str);
		$user_1 = $_MooClass['MooMySQL']->getOne("select gender,birthyear,height,weight,mainimg,province,city,marriage,education,salary,children,nature,body,occupation,hometownprovince,hometowncity,nation,wantchildren,drinking,smoking from `{$dbTablePre}members_search` as m,`{$dbTablePre}members_base` as mf where m.uid=mf.uid and m.is_lock = 1 and m.uid='$memberid'");
		$cl = searchApi('choice');
		$cl -> setQueryType(true);
		$cond[] = array('gender',$user_1['gender'],false);
		if($user_1['birthyear']){
			$age = (int)(date('Y',time()) - $user_1['birthyear']);
			$cond[] = array('age1',array(18,$age),false);
			$cond[] = array('age2',array($age,99),false);
		}
    	if($user_1['height']){
			$cond[] = array('height1',array(150,(int)$user_1['height']),false);
			$cond[] = array('height2',array((int)$user_1['height'],201),false);
		}
    	if($user_1['weight']){
			$cond[] = array('weight1',array(40,$user_1['weight']),false);
			$cond[] = array('weight2',array($user_1['weight'],81),false);
		}
		if($user_1['mainimg']) 
			$cond[] = array('hasphoto','0',true);
		if($user_1['province'])
			$cond[] = array('workprovince',$user_1['province'].'|0',false);
		if($user_1['city'])
			$cond[] = array('workcity',$user_1['city'].'|0',false);
		if($user_1['marriage']) 
			$cond[] = array('marriage',$user_1['marriage'].'|0');
		if($user_1['education']) 
			$cond[] = array('education',$user_1['education'].'|0');
		if($user_1['salary']) 
			$cond[] = array('salary',$user_1['salary'].'|0');
		if($user_1['children']) 
			$cond[] = array('children',$user_1['children'].'|0');
		if($user_1['nature']) 
			$cond[] = array('nature',$user_1['nature'].'|0');
		if($user_1['body']) 
			$cond[] = array('body',$user_1['body'].'|0');
		if($user_1['occupation']) 
			$cond[] = array('occupation',$user_1['occupation'].'|0');
		if($user_1['hometownprovince']) 
			$cond[] = array('hometownprovince',$user_1['hometownprovince'].'|0');
		if($user_1['hometowncity']) 
			$cond[] = array('hometowncity',$user_1['hometowncity'].'|0');
		if($user_1['nation']) 
			$cond[] = array('nation',$user_1['nation'].'|0');
		if($user_1['wantchildren']) 
			$cond[] = array('wantchildren',$user_1['wantchildren'].'|0');
		if($user_1['drinking']) 
			$cond[] = array('drinking',$user_1['drinking'].'|0');
		if($user_1['smoking']) 
			$cond[] = array('smoking',$user_1['smoking'].'|0');
		$rs = $cl -> getResultOfReset($cond,array(0,600),'@weight desc');
		//print_r($cl);
		$ids = $cl -> getIds();
		if(is_array($ids) && !empty($ids)){
			if($user_1['gender']) $index_search = 'members_man';
			else $index_search = 'members_women';
			$cl = searchApi($index_search);
			$cond = array();
			$cond[] = array('is_lock','1',false);
			$cond[] = array('@id',implode('|',$ids),false);
			$rs2 = $cl -> getResultOfReset($cond,$limit,$sort_str);
			$total = 0;
			if(isset($rs2["total_found"])) $total = $rs2["total_found"];
			$total_pages = ceil($total/$pagesize);
	    	$page = min($page, $total_pages);
			if(!$page){
				$rs3 = $cl -> getResult($cond,array($pagesize),$sort_str);
				$total = 0;
				if(isset($rs3['total_found'])) $total = $rs3['total_found'];
				$total_pages = ceil($total/$pagesize);
				if(!$total_pages) $total_pages = 1;
	    		header('Location:index.php?n=search&h=look&page='.$total_pages);
	    		exit;
	    	}
			$ids2 = $cl -> getIds();
			if(is_array($ids2) && !empty($ids2)){
				$allowstr = implode(',',$ids2);
				$user = $_MooClass['MooMySQL']->getAll("select s.*,b.mainimg from {$dbTablePre}members_search s left join {$dbTablePre}members_base b on s.uid=b.uid where s.uid in({$allowstr}) order by {$sort_str}");
			}else{
				 include MooTemplate('public/search_error', 'module');exit;
			}
		}else{
            include MooTemplate('public/search_error', 'module');
            exit;
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
	include MooTemplate('public/'.$htm, 'module');
}

//自动匹配
function automatch_search(){
	global $_MooClass, $dbTablePre, $user_arr, $userid,$last_login_time;
	
    if ($userid){
        /* 以下分页参数设置 */
        //note  每页显示个数
        if (isset($_GET['condition']) && $_GET['condition'] == '2') {
            $pagesize = 6;
        } else if (isset($_GET['condition']) && $_GET['condition'] == '3') {
            $pagesize = 16;
        } else {
            $pagesize = 6;
        }

        //note 获得第几页
        $page = 1;
        if(isset($_GET['page']))
        	$page = intval(max(1, $_GET['page']));

        if($user_arr['gender']==1){
            $gender=0;
        }elseif($user_arr['gender']==0){
            $gender=1;
        }else{
        	$gender=1;
        }
        
        //从数据库中搜索出来的是符合本人（学历，身高，年龄）条件的
        if($user_arr['birthyear']){
        	$age=date('Y')-$user_arr['birthyear'];
            $where =" where a.gender='{$gender}' and b.age1<='{$age}' and b.age2>='{$age}' and a.education>={$user_arr['education']} and b.height1<={$user_arr['height']} and b.height2>={$user_arr['height']} and a.province={$user_arr['province']} ";
        }else{
        	$where =" where a.gender='{$gender}' and  a.education>={$user_arr['education']} and b.height1<={$user_arr['height']} and b.height2>={$user_arr['height']} and a.province={$user_arr['province']} ";
        }
        
        $sql = "select count(*) as c from web_members_search a left join web_members_choice b on a.uid=b.uid $where";

        $result=$_MooClass['MooMySQL']->getOne($sql);
        $total = 0;
        if(isset($result['c']) && $result['c']) $total = $result['c'];
        $is_del_cond = false;

        if(!$total){//如果所有条件不能查询出数据则降低条件--去掉地区和学历
        	if($gender) $choice_gender = 0;
        	else $choice_gender = 1;
        	$is_del_cond = true;
	        if($user_arr['birthyear']){
	        	$age=date('Y')-$user_arr['birthyear'];
	            $where =" where a.gender='{$gender}' and b.age1<='{$age}' and b.age2>='{$age}' and b.height1<={$user_arr['height']} and b.height2>={$user_arr['height']}";
	        	$where_choice = " where gender='{$choice_gender}' and age1<='{$age}' and age2>='{$age}' and height1<={$user_arr['height']} and height2>={$user_arr['height']}";
	        }else{
	        	$where =" where a.gender='{$gender}' and b.height1<={$user_arr['height']} and b.height2>={$user_arr['height']}";
	        	$where_choice = " where gender='{$choice_gender}' and height1<={$user_arr['height']} and height2>={$user_arr['height']}";
	        }
	        $sql2 = "select count(*) as c from web_members_choice {$where_choice}";
	        $result=$_MooClass['MooMySQL']->getOne($sql2);
        	if(isset($result['c']) && $result['c']) $total = $result['c'];
        }
       
        $match='automatch';
        if($total > 600) $total = 600;

        $total_pages = ceil($total/$pagesize);
		if($total_pages < $page){
			Header('Location:index.php?n=search&h=automatch&page='.$total_pages);exit;
		}
        $offset = ($page - 1) * $pagesize;
        
        $order_province=$user_arr['province'];
        $order_city=$user_arr['city'];
        
        $sortway = isset($_GET['sortway']) ? $_GET['sortway'] : '1';
        $sortway = intval($sortway);
        if($sortway == '1'){
           //note 默认排序
           $sort = "order by ";
        }elseif($sortway == '2'){
           //note 最新注册排序
           $sort = "order by a.regdate DESC,";
            
        }elseif($sortway == '3'){
          //note 诚信等级排序
           $sort = "order by a.certification DESC,";
        }else{
         //note 默认排序
           $sort = "order by ";
        }
        
        $sort .= " a.province<>'{$order_province}',a.city<>'{$order_city}',a.city desc,a.province desc,a.s_cid asc, a.images_ischeck desc,a.pic_num desc "; //,m.bgtime desc, m.birthyear desc
        
        
        if($total>0){
	        $sql="select a.uid,a.gender,a.birthyear,a.nickname,a.height,a.education,a.province,a.city,a.smoking,a.drinking,a.images_ischeck,a.s_cid,a.body,a.certification,a.salary,a.weight,a.marriage,a.children,b.workprovince,b.workcity,c.mainimg from web_members_search a left join web_members_choice b on a.uid=b.uid left join web_members_base c on a.uid=c.uid  {$where}  $sort limit {$offset}, $pagesize";
	        //echo $sql;
	        $user=array();
	        $userlist=$_MooClass['MooMySQL']->getAll($sql);
	        foreach($userlist as $list){
		        //匹配度计算
		        $mark = 0;
		        if(MOOPHP_ALLOW_FASTDB){
		            $cho = MooFastdbGet('members_choice','uid',$user_arr['uid']);   
		        }else{
		            $cho = $_MooClass['MooMySQL']->getOne("SELECT * FROM {$dbTablePre}members_choice WHERE uid='{$user_arr['uid']}'");
		        }
		        
		        $year = isset($cho['birthyear']) && $cho['birthyear'] ? isset($_GET['condition']) && $cho['birthyear'] : $user_arr['birthyear'];
		        if( ($year-5) <= $list['birthyear'] && $list['birthyear'] <= ($year+5)){
		            $mark += 9;
		        }else{ $mark += 6; }
		        
		        if( $cho['height1'] <= $list['height'] && $list['height'] <= ($cho['height2'])){
		            $mark += 7;
		        }else{ $mark += 5; }
		        
		        if( $cho['weight1'] <= $list['weight'] && $list['weight'] <= ($cho['weight2'])){
		            $mark += 5;
		        }else{ $mark += 3; }
		        
		        if( $cho['workprovince'] == $list['workprovince']){
		            $mark += 8;
		        }else{ $mark += 6; }
		        
		        if( $cho['workcity'] == $list['workcity']){
		            $mark += 16;
		        }else{$mark += 10;}
		        
		        if( $cho['education'] == $list['education']){
		            $mark += 8;
		        }else{ $mark += 5; }
		        
		        if( $cho['salary'] == $list['salary']){
		            $mark += 9;
		        }else{ $mark += 7; }
		        
		        if( $cho['marriage'] == $list['marriage']){
		            $mark += 8;
		        }else{ $mark += 5; }
		        
		        if( $cho['children'] == $list['children']){
		            $mark += 8;
		        }else{ $mark += 6; }
		        
		        if( $cho['drinking'] == $list['drinking']){
		            $mark += 5;
		        }else{ $mark += 3; }
		        
		        if( $cho['smoking'] == $list['smoking']){
		            $mark += 5;
		        }else{ $mark += 2; }
		        
		        if( $cho['body'] == $list['body']){
		           $mark += 12;
		        }else{ $mark += 8; }
	
		        if(!isset($list['lastvisit'])) $list['lastvisit'] = 0;
		        if(!isset($list['mainimg'])) $list['mainimg'] = 0;
		        if(!isset($list['citystar'])) $list['citystar'] = 0;
		        $user[]=array('citystar'=>$list['citystar'],'height'=>$list['height'],'education'=>$list['education'],'mainimg'=>$list['mainimg'],'gender'=>$list['gender'],'images_ischeck'=>$list['images_ischeck'],'s_cid'=>$list['s_cid'],'nickname'=>$list['nickname'],'uid'=>$list['uid'],'birthyear'=>$list['birthyear'],'lastvisit'=>$list['lastvisit'],'certification'=>$list['certification'],'mark'=>$mark);
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
	       include MooTemplate('public/'.$htm, 'module');
        } else {
           include MooTemplate('public/search_error', 'module');
           exit;
        }
    }
        
}
//note 和谁天生一对
function bothbelong_search($memberid) {
    global $_MooClass, $dbTablePre, $user_arr, $userid,$last_login_time;

    if ($memberid){
        /* 以下分页参数设置 */
        //note  每页显示个数
        if (isset($_GET['condition']) && $_GET['condition'] == '2') {
            $pagesize = 6;
        } else if (isset($_GET['condition']) && $_GET['condition'] == '3') {
            $pagesize = 16;
        } else {
            $pagesize = 6;
        }

        //note 获得第几页
        $page = 1;
        if(isset($_GET['page']))
        	$page = intval(max(1, $_GET['page']));

        //note limit查询开始位置
        $start = ($page - 1) * $pagesize;
		
		//当前用户所符合的uid *********
		$user_1 = $_MooClass['MooMySQL']->getOne("select m.uid,m.birthyear,m.height,m.weight,m.province,m.city,m.education,m.marriage,m.salary,m.children,m.body,mf.nature,m.occupation,m.nation,m.wantchildren,m.hometownprovince,m.hometowncity,m.smoking,m.drinking,mf.mainimg from `{$dbTablePre}members_search` as m,`{$dbTablePre}members_base` as mf where m.uid=mf.uid and m.is_lock = 1 and m.uid='$memberid'");
       
		//note 基本SQL语句
		$where_uid = "select uid from {$dbTablePre}members_choice where";
        
		/*$count_sql = "select count(*) as count";
		$pre_sql = " from {$dbTablePre}members where is_lock ='1' ";
        if ($user_1['gender'] == '0') {
            $pre_sql .= "and gender=1 ";
        } elseif ($user_1['gender'] == '1') {
            $pre_sql .= " and gender=0 ";
        }*/

		
        
        //note 年龄上的符合
        $age = date('Y', time()) - $user_1['birthyear'];
        $where_uid .= " age1 <= $age and (age2 >= $age or age2 = 0)";
		
        //note 身高上的符合
        $height = $user_1['height'] ? $user_1['height'] : 0;
         $where_uid .= " and height1 <= $height and (height2 = 0 or height2 >= $height) ";

        //note 体重上的符合
        $weight = $user_1['weight'] ? $user_1['weight'] : 0;
        $where_uid .= " and (weight1 = 0 or weight1<= $weight) and (weight2 = 0 or weight2 >= $weight) ";

        //note 是否有图片上的符合
        if (!$user_1['mainimg']) {
            $where_uid .= " and hasphoto=0 ";
        }

        // 省市
		$city = $user_1["city"] ? $user_1["city"] : 0;
		$province = $user_1["province"] ? $user_1["province"] : 0;
        if ($province != 0) {
            $where_uid .= " and (workprovince = 0 or workprovince = '$province')";

            if ($city != 0) {
                $where_uid .= " and (workcity = 0 or workcity = '$city')";
            }
        } else {
            $where_uid .= " and workcity = 0 and workprovince = 0";
		}

        // 婚姻
		$marriage = $user_1["marriage"] ? $user_1["marriage"] : 0;
        if ($marriage != 1) {
            $where_uid .= " and (marriage = 0 or marriage = '$marriage')";
        }

        // 教育
		$education = $user_1["education"] ? $user_1["education"] : 0;
        $where_uid .= " and education <= '$education'";
		
        // 收入
		$salary = $user_1["salary"] ? $user_1["salary"] : 0;
		$where_uid .= " and salary <= '$salary'";

        // 有无孩子
		$children = $user_1["children"] ? $user_1["children"] : 0;
		$where_uid .= " and (children = 0 or children = '$children')";
		
        // 择偶性格
		$nature = $user_1["nature"] ? $user_1["nature"] : 0;
        $where_uid .= " and (nature = 0 or nature = '$nature')";

        // 体型
		$body = $user_1["body"] ? $user_1["body"] : 0;
        $where_uid .= " and (body = 0 or body = '$body')";

        // 职业
		$occupation = $user_1["occupation"] ? $user_1["occupation"] : 0;
        $where_uid .= " and (occupation = 0 or occupation = '$occupation')";

        // 籍贯
		$hometowncity = $user_1["hometowncity"] ? $user_1["hometowncity"] : 0;
		$hometownprovince = $user_1["hometownprovince"] ? $user_1["hometownprovince"] : 0;
        if ($hometownprovince != 0) {
            $where_uid .= " and (hometownprovince = 0 or hometownprovince = '$hometownprovince')";

            if ($hometowncity != 0) {
                $where_uid .= " and (hometowncity = 0 or hometowncity = '$hometowncity')";
            }
        } else {
            $where_uid .= " and hometowncity = 0 and hometownprovince = 0";
		}

        // 民族
		$nation = $user_1["nation"] ? $user_1["nation"] : 0;
        $where_uid .= " and (nation = 0 or nation = '$nation')";

        // 是否要孩子
		$wantchildren = $user_1["wantchildren"] ? $user_1["wantchildren"] : 0;
		$where_uid .= " and (wantchildren = 0 or wantchildren = '$wantchildren')";

        // 喝酒
		$drinking = $user_1["drinking"] ? $user_1["drinking"] : 0;
        if ($drinking != 1) {
            $where_uid .= " and drinking <= 0";
        }

        // 抽烟
		$smoking = $user_1["smoking"] ? $user_1["smoking"] : 0;
        if ($smoking != 1) {
            $where_uid .= " and smoking <= 0";
        }
		
        //note 当前用户择偶信息*****************
        if (MOOPHP_ALLOW_MEMCACHED) {
            $user_1 = MooFastdbGet('members_choice', 'uid', $memberid);
        } else {
            $user_1 = $_MooClass['MooMySQL']->getOne("select age1,age2,height1,height2,weight1,weight2,workprovicne,workcity,hasphoto,marriage,education,salary,children,smoking,drinking,body,wantchildren,occupation,hometownprovince,homecity,nation from `{$dbTablePre}members_choice` where `uid` = '$memberid'");
        }
        
        /*if (MOOPHP_ALLOW_MEMCACHED) {
            $memberfield = MooFastdbGet('memberfield', 'uid', $memberid);
        } else {
            $memberfield = $_MooClass['MooMySQL']->getOne("select * from " . $dbTablePre . "memberfield where uid = '" . $memberid . "'");
        }*/

        //note  找出符合当前会员择偶条件的会员
        //基本SQL语句
        //$pre_sql = "select m.*";
		$pre_sql = "select m.uid";
		
        $sql_table1 = " from `{$dbTablePre}members_search` as m left join `{$dbTablePre}members_base` as mf on (m.uid=mf.uid) left join " . $dbTablePre . "members_choice as c on (m.uid = c.uid) left join ".$dbTablePre."members_introduce i on m.uid=i.uid where m.is_lock = 1  ";
        
		$sql_table2 = " from `{$dbTablePre}members_search` as m  where m.is_lock = 1  ";
    
		$sql = '';
        //note 择偶对象性别
        if ($user_arr['gender'] == '0') {
            $sql .= 'and m.gender=1 ';
      
			$gender = '1';
        } else {
            $sql .= 'and m.gender=0 ';
    
			$gender = '0';
        }
        //note 择偶对象出生年区间
        $age1 = $user_1['age1'] == 0 ? 0 : $user_1['age1'];
        $age2 = $user_1['age2'] == 0 ? 120 : $user_1['age2'];
        
		$year = date('Y', time());
		$year_end = $year - $age1;
		$year_start = $year -$age2;
		$sql .= " and (m.birthyear <= $year_end and m.birthyear >= $year_start)";
		$age_start = $age1 == 0 ? '0' : $age1;
		$age_end = $age2 == 120 ? '0' : $age2;

        //note 择偶对象体重区间
        $weight1 = $user_1['weight1'];
        $weight2 = $user_1['weight2'] == 0 ? 300: $user_1['weight2'];
		
		$sql .= " and (m.weight >= $weight1 and m.weight <= $weight2)";
		$weight2 = $weight2 == 300 ? '0' : $weight2;
   
        //note 择偶对象身高区间
        $height1 = $user_1['height1'];
        $height2 = $user_1['height2'] == 0 ? 300 : $user_1['height2'];
		$sql .= " and (m.height >= $height1 and m.height <= $height2)";
		$height2 = $height2 == 300 ? '0' : $height2;
    
        //note 是否有相片
        if ($user_1['hasphoto']) {
            $sql .= " and m.images_ischeck = '1' ";
        }
		$photo = $user_1['hasphoto'];

        // 省城市
		$work_province = $user_1["workprovince"] ? $user_1["workprovince"] : 0;
		$work_city = $user_1["workcity"] ? $user_1["workcity"] : 0;
		if($user_1['workprovince'] != '0') {
			$sql .= " and m.province = " . $user_1["workprovince"];
		}
		if($user_1['workcity'] != '0') {
			 $sql .= " and m.city = " . $user_1["workcity"];
		}
      

        // 婚姻状况
		$marriage = $user_1["marriage"] ? $user_1["marriage"] : 0;
        if ($marriage != '0') {
            $sql .= " and m.marriage = " . $marriage;
        }
		
		//教育程度
		$education = $user_1["education"] ? $user_1["education"] : 0;
        if ($education != '0') {
            $sql .= " and m.education = " . $education;
        }

        // 是否抽烟
		$smoking = $user_1["smoking"] ? $user_1["smoking"] : 0;
        if ($smoking == 1) {
            $sql .= " and m.smoking = 1";
        }

        // 收入
		$salary = $user_1["salary"] ? $user_1["salary"] : 0;
        if ($salary != 0) {
            $sql .= " and m.salary >= " . $salary;       
		}

        // 有无孩子
		$children = $user_1["children"] ? $user_1["children"] :0;
        if ($user_1["children"] != 0) {
            $sql .= " and m.children = " . $children;
        }

        // 体形
		$body = $user_1["body"] ? $user_1["body"] : 0;
        if ($user_1["body"] != 0) {
            $sql .= " and m.body = " . $body;
		}

        // 职业
		$occupation = $user_1["occupation"] ? $user_1["occupation"] : 0;
        if ($user_1["occupation"] != 0) {
            $sql .= " and m.occupation = " . $occupation;
		}

        // 户口地区
		$hometownprovince = $user_1["hometownprovince"] ? $user_1["hometownprovince"] : 0;
		if($user_1['hometownprovince'] != 0) {
			 $sql .= " and m.hometownprovince = " . $hometownprovince;
		}
		
		$hometowncity = $user_1["hometowncity"] ? $user_1["hometowncity"] : 0;
		if($user_1['hometowncity'] != 0) {
			$sql .= " and m.hometowncity = " . $hometowncity;
		}
       
        // 民族
		$nation = $user_1["nation"] ? $user_1["nation"] : 0;
        if ($user_1["nation"] != 0) {
            $sql .= " and m.nation = " . $nation;
        }

        // 是否想要孩子
		$wantchildren = $user_1["wantchildren"] ? $user_1["wantchildren"] : 0;
		if ($user_1["wantchildren"] != 0) {
			$sql .= " and m.wantchildren = " . $wantchildren;
		}
        // 喝酒
		$drinking = $user_1["drinking"] ? $user_1["drinking"] : 0;
        if ($user_1["drinking"] == 1) {
            $sql .= " and m.drinking = 1";
		}
		
		$sql0 = " and m.uid in($where_uid)";
		
		//echo $pre_sql.$sql.$sql0." limit 60";
		//符合条件的会员
		$userlist = array();
		$userlist = $_MooClass['MooMySQL']->getAll($pre_sql.$sql_table2.$sql.$sql0." limit 60"); 
        $total = count($userlist);
        if ($total > 0) {
			
			//符合条件的uid
			$sql_uid = '';
			$uid_arr = array();
			foreach($userlist as $user) {
				$uid_arr[] = $user['uid'];
			}
			$uid_str = implode(',', $uid_arr);
			$sql_uid = "and m.uid in($uid_str)";
			
            $is_data = true;

            $total_pages = ceil($total / $pagesize);
            $page = min($page, $total_pages);

            $offset = ($page - 1) * $pagesize;
			
			$sortway = isset($_GET['sortway']) ? $_GET['sortway'] : '1';
			$sortway = intval($sortway);
			//钻石会员和高级会员搜索，本站注册靠前
			$sort_str = '';
			if($user_arr['s_cid'] != 40) {
				$sort_str = ', `usertype` asc';
			}
			if($sortway == '1'){
				//note 默认排序
				$sort = "`city_star` DESC{$sort_str},";
			}elseif($sortway == '2'){
				//note 最新注册排序
				$sort = "`regdate` DESC, `city_star` desc{$sort_str},";
			
			}elseif($sortway == '3'){
				//note 诚信等级排序
				$sort = "`certification` DESC, `city_star` desc{$sort_str},";
			}else{
				//note 默认排序
				$sort = "`city_star` DESC{$sort_str},";
			}
			
			$sql = "select m.uid,m.gender,m.images_ischeck,m.province,m.city,m.birthyear,m.height,m.education,m.certification,mf.mainimg,i.introduce {$sql_table1} {$sql} {$sql_uid} order by {$sort}s_cid asc, images_ischeck desc, birthyear desc limit {$offset}, $pagesize";

			//note 用block缓存
            $param = ("type=query/name=bothbelong_{$userid}/sql=$sql/cachetime=86400");
            $_MooClass['MooCache']->getBlock($param);
            $user = $GLOBALS['_MooBlock']['bothbelong_' . $userid];
	
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
			include MooTemplate('public/'.$htm, 'module');

			} else {
				include MooTemplate('public/search_error', 'module');
				exit;
			}
		}
}

//note 标签搜索
function tag_search($tagname, $sex, $tagid) {
    global $_MooClass, $dbTablePre, $userid, $user_arr,$last_login_time;
    $returnurl = 'index.php?' . $_SERVER['QUERY_STRING']; //返回的url
	
	//$searchtable = $sex=='0' ? 'membersfastadvance_man' : 'membersfastadvance_women';
	$searchtable = 'members_search';
    
    /* 以下分页参数设置 */
    //note  每页显示个数
    if (isset($_GET['condition']) && $_GET['condition'] == '2') {
        $pagesize = 6;
    } else if (isset($_GET['condition']) && $_GET['condition'] == '3') {
        $pagesize = 16;
    } else {
        $pagesize = 6;
    }
    if (isset($_GET['m']) && $_GET['m'] == 'js') {
        $linktag = js_unescape(MooGetGPC('linktag', 'string', 'G'));
    } else {
        $linktag = str_replace(' ', '+', MooGetGPC('linktag', 'string', 'G'));
        $linktag = base64_decode($linktag);
    }

    
     //=============取 当前 登录 网站用户的IP地址  对应所在 地区begin====================================
     $work_city=getLocalArea();

    //exit($linktag);
    //note 获得第几页
    $page = 1;
    if(isset($_GET['page']))
    	$page = intval(max(1, $_GET['page']));
    $offset = ($page - 1)*$pagesize;
	
	/*******排序方式********/
	$sortway = isset($_GET['sortway']) ? $_GET['sortway'] : '1';
	//echo $sortway;
	//$sortway = intval($sortway);
	//note 普通会员搜索结果还是按照以前的排序，高级、钻石会员搜索结果按照本站注册的排序
//	if($user_arr["s_cid"]=="40"){
//		if($sortway == '1'){
//			//note 默认排序
//			$sort = " ORDER BY city<>'{$work_city}',city desc,s_cid asc,pic_num desc,`city_star` DESC,images_ischeck desc ";
//		}elseif($sortway == '2'){
//			//note 最新注册的排序
//			$sort = " ORDER BY city<>'{$work_city}',city desc,s_cid asc,pic_num desc,`regdate`  DESC,s_cid asc ,images_ischeck desc ";
//		}elseif($sortway == '3'){
//			//note 按照诚信等级排序
//			$sort = "ORDER BY city<>'{$work_city}',city desc,s_cid asc,pic_num desc,`certification` DESC,s_cid asc,images_ischeck desc ";
//		}else{
//			//note 默认排序
//			$sort = " ORDER BY city<>'{$work_city}',city desc,s_cid asc,pic_num desc,`city_star` DESC,s_cid asc ,images_ischeck desc";
//		}
//	}else{
//		if($sortway == '1'){
//			//note 默认排序
//			$sort = " ORDER BY `city_star` DESC,usertype asc,s_cid asc ";
//		}elseif($sortway == '2'){
//			//note 最新注册的排序
//			$sort = " ORDER BY `regdate`  DESC,usertype asc,s_cid asc ";
//		}elseif($sortway == '3'){
//			//note 按照诚信等级排序
//			$sort = "ORDER BY `certification` DESC,usertype asc,s_cid asc ";
//		}else{
//			//note 默认排序
//			$sort = " ORDER BY `city_star` DESC,usertype asc,s_cid asc ";
//		}		
//	}
	$cond = array();
	$user = array();
    //note 年龄标签搜索
    if ($tagname == 'age') {
        switch ($tagid) {
            case '1':
                $bsql = " AND `birthyear` > '1984' AND `birthyear` < '1991'";
                $birth_start = 1984;
                $birth_end = 1991;
                break;
            case '2':
                $bsql = " AND `birthyear` > '1974' AND `birthyear` < '1983'";
                $birth_start = 1974;
                $birth_end = 1983;
                break;
            case '3':
                $bsql = " AND `birthyear` > '1964' AND `birthyear` < '1973'";
                $birth_start = 1964;
                $birth_end = 1973;
                break;
            case '4':
                $bsql = " AND `birthyear` > '1954' AND `birthyear` < '1963'";
                $birth_start = 1954;
                $birth_end = 1963;
                break;
            case '5':
                $bsql = " AND `birthyear` > '1949' AND `birthyear` < '1953'";
                $birth_start = 1949;
                $birth_end = 1953;
                break;
            case '6':
                $bsql = " AND `birthyear` < '1949' ";
                $birth_start = 1900;
                $birth_end = 1948;
                break;
            default:
                $bsql = "";
                $birth_start = 1900;
                $birth_end = 1991;
                break;
        }
        $cond[] = array('birthyear',array($birth_start,$birth_end),false);
		$rs = SearchByTag($sex,$sortway,$cond,$offset,$pagesize);
		$total = 0;
		if(isset($rs['total_found'])) $total = $rs['total_found'];
		if($total > 600) $total = 600;
		$total_pages = ceil($total / $pagesize);
        $page = min($page, $total_pages);
        if($total>0){
        	$ids = array();
        	if(isset($rs['ids'])) $ids = $rs['ids'];
        	if(!empty($ids)){
				$ids_str = implode(',',$ids);
				$user = $_MooClass['MooMySQL']->getAll("select s.*,b.mainimg from {$dbTablePre}".$searchtable." s left join {$dbTablePre}members_base b on s.uid=b.uid where s.uid in ($ids_str)");
				$user = userbasicadd($user);
        	}
        	$recommend = array();
        	if(isset($rs['ids2']) && !empty($rs['ids2'])){//推荐会员信息
	            $ids2 = $rs['ids2'];
	            $ids_str2 = implode(',',$ids2);
	            $recommend_list = $_MooClass['MooMySQL']->getAll("select s.uid,s.gender,b.mainimg,s.city_star,s.images_ischeck,s.s_cid,s.nickname from `{$dbTablePre}members_search` s left join `{$dbTablePre}members_base` b on s.uid=b.uid where s.uid in ($ids_str2)");
	            shuffle($recommend_list);
	            $recommend = array_slice($recommend_list,0,6);
			}
        }
		
        //note 查询出来的总数
		/*
        $total_count = $_MooClass['MooMySQL']->getOne("select count(*) as count from `{$dbTablePre}members` where is_lock = 1 and `gender` = '$sex' $bsql");
        $total = $total_count["count"];
        if ($total > 600) {
            $total = 600;
        }*/
//		$total = 600;
//
        $total_pages = ceil($total / $pagesize);
        $page = min($page, $total_pages);
        $start = 0;
//
//        //note limit查询开始位置
//        $start = ($page - 1) * $pagesize;
//        if ($total - $start < $pagesize) {
//            $pagesize = $total - $start;
//        }
//        if ($total > 0) {
//            $user = $_MooClass['MooMySQL']->getAll("select * from {$dbTablePre}".$searchtable." where images_ischeck='1' and is_lock='1' and `gender` = '{$sex}' $bsql $sort LIMIT $start,$pagesize");
//			$user = userbasicadd($user);
//            //推荐会员信息
//            $recommend_list = $_MooClass['MooMySQL']->getAll("select s.uid,s.gender,b.mainimg,s.city_star,s.images_ischeck,s.s_cid,s.nickname from `{$dbTablePre}members_search` s,`{$dbTablePre}members_base` b where s.images_ischeck='1' and s.is_lock='1' and s.`gender` = '{$sex}' $bsql and b.is_vote='1' LIMIT 6");
//            shuffle($recommend_list);
//
//            $recommend = array_slice($recommend_list, 0,6);
//        }
    //note 收入标签搜索
    } else if ($tagname == 'salary') {

        switch ($tagid) {
            case '1':
                $ssql = " AND `salary` >= '7'";
                $sal_type = '7|8|9';
                break;
            case '2':
                $ssql = " AND `salary` >= '5' AND `salary` <= '6'";
                $sal_type = '5|6';
                break;
            case '3':
                $ssql = " AND `salary` = '4'";
                $sal_type = '4';
                break;
            case '4':
                $ssql = " AND `salary` <= '3'";
                $sal_type = '1|2|3';
                break;
        }
    	$cond[] = array('salary',$sal_type,false);
		$rs = SearchByTag($sex,$sortway,$cond,$offset,$pagesize);
		$total = 0;
		if(isset($rs['total_found'])) $total = $rs['total_found'];
		if($total > 600) $total = 600;
		$total_pages = ceil($total / $pagesize);
        $page = min($page, $total_pages);
        if($total>0){
        	$ids = array();
        	if(isset($rs['ids'])) $ids = $rs['ids'];
        	if(!empty($ids)){
				$ids_str = implode(',',$ids);
				$user = $_MooClass['MooMySQL']->getAll("select s.*,b.mainimg from {$dbTablePre}".$searchtable." s left join {$dbTablePre}members_base b on s.uid=b.uid where s.uid in ($ids_str)");
				$user = userbasicadd($user);
        	}
        	$recommend = array();
        	if(isset($rs['ids2']) && !empty($rs['ids2'])){//推荐会员信息
	            $ids2 = $rs['ids2'];
	            $ids_str2 = implode(',',$ids2);
	            $recommend_list = $_MooClass['MooMySQL']->getAll("select s.uid,s.gender,b.mainimg,s.city_star,s.images_ischeck,s.s_cid,s.nickname from `{$dbTablePre}members_search` s left join `{$dbTablePre}members_base` b on s.uid=b.uid where s.uid in ($ids_str2)");
	            shuffle($recommend_list);
	            $recommend = array_slice($recommend_list,0,6);
			}
        }
		/*
        //note 查询出来的总数
        $total_count = $_MooClass['MooMySQL']->getOne("select count(*) as count from `{$dbTablePre}members` where is_lock = 1 and `gender` = '$sex' $ssql");
        //$total = $_MooClass['MooMySQL']->numRows($query);
        $total = $total_count["count"];
        if ($total > 600) {
            $total = 600;
        }
		*/
//		$total = 600;
//
//        $total_pages = ceil($total / $pagesize);
//        $page = min($page, $total_pages);
//
//        //note limit查询开始位置
//        $start = ($page - 1) * $pagesize;
//        if ($total - $start < $pagesize) {
//            $pagesize = $total - $start;
//        }
//
//        if ($total > 0) {
//            $user = $_MooClass['MooMySQL']->getAll("select * from {$dbTablePre}".$searchtable." where images_ischeck='1' and is_lock='1' and `gender` = '{$sex}' $ssql  $sort LIMIT $start,$pagesize");
//			$user = userbasicadd($user);
//            //推荐会员信息
//            $recommend_list = $_MooClass['MooMySQL']->getAll("select s.uid,s.gender,b.mainimg,s.city_star,s.images_ischeck,s.s_cid,s.nickname  from `{$dbTablePre}members_search` s,`{$dbTablePre}members_base` b where s.images_ischeck='1' and s.is_lock='1' and s.`gender` = '{$sex}' $ssql  and b.is_vote='1' LIMIT 6");
//            shuffle($recommend_list);
//            $recommend = array_slice($recommend_list, 0, 6);
//        }
    //note 学历标签搜索
    } else if ($tagname == 'education') {

        switch ($tagid) {
            case '1':
                $ssql = " AND `education` = '7'";
                $edu = '7';
                break;
            case '2':
                $ssql = " AND `education` = '6'";
                $edu = '6';
                break;
            case '3':
                $ssql = " AND `education` = '5'";
                $edu = '5';
                break;
            case '4':
                $ssql = " AND `education` = '4'";
                $edu = '4';
                break;
            case '5':
                $ssql = " AND `education` = '3'";
                $edu = '3';
                break;
        }
    	$cond[] = array('education',$edu,false);
		$rs = SearchByTag($sex,$sortway,$cond,$offset,$pagesize);
		$total = 0;
		if(isset($rs['total_found'])) $total = $rs['total_found'];
		if($total > 600) $total = 600;
		$total_pages = ceil($total / $pagesize);
        $page = min($page, $total_pages);
        if($total>0){
        	$ids = array();
        	if(isset($rs['ids'])) $ids = $rs['ids'];
        	if(!empty($ids)){
				$ids_str = implode(',',$ids);
				$user = $_MooClass['MooMySQL']->getAll("select s.*,b.mainimg from {$dbTablePre}".$searchtable." s left join {$dbTablePre}members_base b on s.uid=b.uid where s.uid in ($ids_str)");
				$user = userbasicadd($user);
        	}
        	$recommend = array();
        	if(isset($rs['ids2']) && !empty($rs['ids2'])){//推荐会员信息
	            $ids2 = $rs['ids2'];
	            $ids_str2 = implode(',',$ids2);
	            $recommend_list = $_MooClass['MooMySQL']->getAll("select s.uid,s.gender,b.mainimg,s.city_star,s.images_ischeck,s.s_cid,s.nickname from `{$dbTablePre}members_search` s left join `{$dbTablePre}members_base` b on s.uid=b.uid where s.uid in ($ids_str2)");
	            shuffle($recommend_list);
	            $recommend = array_slice($recommend_list,0,6);
			}
        }
		/*
        //note 查询出来的总数
        $total_count = $_MooClass['MooMySQL']->getOne("select count(*) as count from `{$dbTablePre}members` where is_lock = 1 and `gender` = '$sex' $ssql");
        $total = $total_count["count"];
        if ($total > 600) {
            $total = 600;
        }
		*/
//		$total = 600;
//
//        $total_pages = ceil($total / $pagesize);
//        $page = min($page, $total_pages);
//
//        //note limit查询开始位置
//        $start = ($page - 1) * $pagesize;
//        if ($total - $start < $pagesize) {
//            $pagesize = $total - $start;
//        }
//        if ($total > 0) {
//            $user = $_MooClass['MooMySQL']->getAll("select * from {$dbTablePre}".$searchtable." where images_ischeck='1' and is_lock='1' and `gender` = '{$sex}' $ssql  $sort LIMIT $start,$pagesize");
//			$user = userbasicadd($user);
//            //推荐会员信息
//            $recommend_list = $_MooClass['MooMySQL']->getAll("select s.uid,s.gender,b.mainimg,s.city_star,s.images_ischeck,s.s_cid,s.nickname  from `{$dbTablePre}members_search` s,`{$dbTablePre}members_base` b where s.images_ischeck='1' and s.is_lock='1' and s.`gender` = '{$sex}' $ssql LIMIT 6");
//            shuffle($recommend_list);
//            $recommend = array_slice($recommend_list, 0, 6);
//        }
    //note 车房标签搜索
    } else if ($tagname == 'housvehicle') {

        switch ($tagid) {
            case '1':
                $ssql = " from {$dbTablePre}".$searchtable." where images_ischeck='1' and is_lock='1' and `gender` = '{$sex}' AND vehicle = '1'";
                $cond[] = array('vehicle','1',false);
                break;
            case '2':
                $ssql = " from {$dbTablePre}".$searchtable." where images_ischeck='1' and is_lock='1' and `gender` = '{$sex}' AND (`house` = '2' OR `house` = '4')";
                $cond[] = array('house','2|4',false);
                break;
        }
    	$rs = SearchByTag($sex,$sortway,$cond,$offset,$pagesize);
		$total = 0;
		if(isset($rs['total_found'])) $total = $rs['total_found'];
		if($total > 600) $total = 600;
		$total_pages = ceil($total / $pagesize);
        $page = min($page, $total_pages);
        if($total>0){
        	$ids = array();
        	if(isset($rs['ids'])) $ids = $rs['ids'];
        	if(!empty($ids)){
				$ids_str = implode(',',$ids);
				$user = $_MooClass['MooMySQL']->getAll("select s.*,b.mainimg from {$dbTablePre}".$searchtable." s left join {$dbTablePre}members_base b on s.uid=b.uid where s.uid in ($ids_str)");
				$user = userbasicadd($user);
        	}
        	$recommend = array();
        	if(isset($rs['ids2']) && !empty($rs['ids2'])){//推荐会员信息
	            $ids2 = $rs['ids2'];
	            $ids_str2 = implode(',',$ids2);
	            $recommend_list = $_MooClass['MooMySQL']->getAll("select s.uid,s.gender,b.mainimg,s.city_star,s.images_ischeck,s.s_cid,s.nickname from `{$dbTablePre}members_search` s left join `{$dbTablePre}members_base` b on s.uid=b.uid where s.uid in ($ids_str2)");
	            shuffle($recommend_list);
	            $recommend = array_slice($recommend_list,0,6);
			}
        }
		/*
        //note 查询出来的总数
        $total_count = $_MooClass['MooMySQL']->getOne("select count(*) as count" . $ssql);
        $total = $total_count["count"];
        if ($total > 600) {
            $total = 600;
        }
		*/
//		$total = 600;
//
//        $total_pages = ceil($total / $pagesize);
//        $page = min($page, $total_pages);
//
//        //note limit查询开始位置
//        $start = ($page - 1) * $pagesize;
//        if ($total - $start < $pagesize) {
//            $pagesize = $total - $start;
//        }
////		echo $ssql;die;
//        if ($total > 0) {
//            $user = $_MooClass['MooMySQL']->getAll("select * " . $ssql . $sort . " LIMIT " . $start . "," . $pagesize);
//			$user = userbasicadd($user);
//            //推荐会员信息
//			switch ($tagid) {
//				case '1':
//					$ssql = " from {$dbTablePre}members_search s, {$dbTablePre}members_base sf where images_ischeck='1' and is_lock='1' and s.uid=sf.uid AND `gender` = '{$sex}' AND s.vehicle = '1'";
//					break;
//				case '2':
//					$ssql = " from {$dbTablePre}members_search where images_ischeck='1' and is_lock='1' and `gender` = '{$sex}' AND (`house` = '2' OR `house` = '4')";
//					break;
//			}
//            $recommend_list = $_MooClass['MooMySQL']->getAll("select * " . $ssql . " and is_vote='1' LIMIT 6");
//
//            shuffle($recommend_list);
//            $recommend = array_slice($recommend_list, 0, 6);
//        }
    //note 城市标签搜索
    } else if ($tagname == 'city') {  	
//        if ($tagid == '10102000' || $tagid == '10103000' || $tagid == '10105000' || $tagid == '10132000' || $tagid == '10133000' || $tagid == '10134000') {
//        	
//*
//        //note 查询出来的总数
//            $query = $_MooClass['MooMySQL']->getOne("select count(1) as num from `{$dbTablePre}members` where is_lock = 1 and `gender` = '$sex' AND `province` = '$tagid'");
//            $total = $query['num'];
//            if ($total > 600) {
//                $total = 600;
//            }
//			*/
//			$total = 600;
//
//            $total_pages = ceil($total / $pagesize);
//            $page = min($page, $total_pages);
//
//            //note limit查询开始位置
//            $start = ($page - 1) * $pagesize;
//            if ($total - $start < $pagesize) {
//                $pagesize = $total - $start;
//            }
//            if ($start < 0) {
//                $start = 0;
//            }
//            $csql = "select * from {$dbTablePre}".$searchtable." where images_ischeck='1' and is_lock='1' and `gender` = '$sex' AND `province` = '{$tagid}' $sort LIMIT $start,$pagesize";
//            $csql2 = "select * from {$dbTablePre}".$searchtable." where images_ischeck='1' and is_lock='1' and `gender` = '$sex' AND `province` = '{$tagid}' LIMIT 20";
//        } else {
//			/*
//            //note 查询出来的总数
//            $total_count = $_MooClass['MooMySQL']->getOne("select count(1) as num from `{$dbTablePre}members` where is_lock = 1 and `gender` = '$sex' AND `city` = '$tagid'");
//            $total = $total_count["num"];
//			*/
//			$total = 600;
//
//            $is_data = true;
//            if ($total == 0) {
//                $is_data = false;
//                $province = substr($tagid, 0, 5) . "000";
//                $total_count = $_MooClass['MooMySQL']->getOne("select count(*) as count from `{$dbTablePre}members_search` where images_ischeck='1' and is_lock='1' and `gender` = '{$sex}' AND `province` = '{$province}'");
//                $total = $total_count["count"];
//            }
//			/*
//            if ($total > 600) {
//                $total = 600;
//            }
//			*/
//            $total_pages = ceil($total / $pagesize);
//            $page = min($page, $total_pages);
//
//            //note limit查询开始位置
//            $start = ($page - 1) * $pagesize;
//            if ($total - $start < $pagesize) {
//                $pagesize = $total - $start;
//            }
//            if ($start < 0) {
//                $start = 0;
//            }
//
			$cond[] = array('province',$tagid,false);
			$rs = SearchByTag($sex,$sortway,$cond,$offset,$pagesize);
			if(!isset($rs['ids']) || empty($rs['ids'])){
				$cond = array();
				$cond[] = array('city',$tagid,false);
				$rs = SearchByTag($sex,$sortway,$cond,$offset,$pagesize);
			}
	    	$total = 0;
			if(isset($rs['total_found'])) $total = $rs['total_found'];
			if($total > 600) $total = 600;
			$total_pages = ceil($total / $pagesize);
	        $page = min($page, $total_pages);
	        if($total>0){
	        	$ids = array();
	        	if(isset($rs['ids'])) $ids = $rs['ids'];
	        	if(!empty($ids)){
					$ids_str = implode(',',$ids);
					$user = $_MooClass['MooMySQL']->getAll("select s.*,b.mainimg from {$dbTablePre}".$searchtable." s left join {$dbTablePre}members_base b on s.uid=b.uid where s.uid in ($ids_str)");
					$user = userbasicadd($user);
	        	}
	        	$recommend = array();
	        	if(isset($rs['ids2']) && !empty($rs['ids2'])){//推荐会员信息
		            $ids2 = $rs['ids2'];
		            $ids_str2 = implode(',',$ids2);
		            $recommend_list = $_MooClass['MooMySQL']->getAll("select s.uid,s.gender,b.mainimg,s.city_star,s.images_ischeck,s.s_cid,s.nickname from `{$dbTablePre}members_search` s left join `{$dbTablePre}members_base` b on s.uid=b.uid where s.uid in ($ids_str2)");
		            shuffle($recommend_list);
		            $recommend = array_slice($recommend_list,0,6);
				}
	        }
//            if ($is_data) {
//                $csql = "select * from {$dbTablePre}".$searchtable." where images_ischeck='1' and is_lock='1' and `gender` = '{$sex}' AND `city` = '{$tagid}' $sort LIMIT $start,$pagesize";
//                $csql2 = "select s.uid,s.gender,b.mainimg,s.city_star,s.images_ischeck,s.s_cid,s.nickname  from `{$dbTablePre}members_search` s,`{$dbTablePre}members_base` b where s.images_ischeck='1' and s.is_lock='1' and s.`gender` = '{$sex}' AND s.`city` = '{$tagid}' and b.is_vote='1' LIMIT 6";
//            } else {
//                $province = substr($tagid, 0, 5) . "000";
//                $csql = "select * from {$dbTablePre}".$searchtable." where images_ischeck='1' and is_lock='1' and `gender` = '{$sex}' AND `province` = '$province' $sort LIMIT $start,$pagesize";
//                $csql2 = "select uid,gender,mainimg,city_star,images_ischeck,s_cid,nickname  from `{$dbTablePre}members_search` where images_ischeck='1' and is_lock='1' and `gender` = '{$sex}' AND `province` = '$province' and is_vote='1'  LIMIT 6";
//            }
//        }

//        if ($total > 0) {
//            $user = $_MooClass['MooMySQL']->getAll($csql);
//			$user = userbasicadd($user);
//            //推荐会员信息
//            $recommend_list = $_MooClass['MooMySQL']->getAll($csql2);
//            shuffle($recommend_list);
//            $recommend = array_slice($recommend_list, 0, 6);
//        }
    //note 职业标签搜索
    } else if ($tagname == 'occupation') {
        switch ($tagid) {
            case '1':
                $osql = " AND s.`occupation` = '7'";
                $o_cond = '7';
                break;
            case '2':
                $osql = " AND s.`occupation` = '26'";
                $o_cond = '26';
                break;
            case '3':
            	$o_cond = '5|16|17|40';
                $osql = " AND (s.`occupation` = '5' OR s.`occupation` = '16' OR s.`occupation` = '17' OR s.`occupation` = '40')";
                break;
            case '4':
                $osql = "  AND (s.`occupation` = '21' OR s.`occupation` = '14')";
                $o_cond = '14|21';
                break;
            case '5':
                $osql = "  AND (s.`occupation` = '34' OR s.`occupation` = '33')";
                $o_cond = '33|34';
                break;
            case '6':
                $osql = "  AND (s.`occupation` = '22' OR s.`occupation` = '11')";
                $o_cond = '11|22';
                break;
            case '7':
                $osql = "  AND (s.`occupation` = '2' OR s.`occupation` = '41')";
                $o_cond = '2|41';
                break;
            case '8':
                $osql = "  AND s.`occupation` = '1'";
                $o_cond = '1';
                break;
            case '9':
                $osql = " AND (s.`occupation` = '3' OR s.`occupation` = '4' OR s.`occupation` = '9' OR s.`occupation` = '24' OR s.`occupation` = '99')";
                $o_cond = '3|4|9|24|99';
                break;
            case '10':
                $osql = "  AND s.`occupation` = '10'";
                $o_cond = '10';
                break;
            case '11':
                $osql = "  AND s.`occupation` = '27'";
                $o_cond = '27';
                break;
            case '12':
                $osql = " AND (s.`occupation` = '28' OR s.`occupation` = '29' OR s.`occupation` = '39' OR s.`occupation` = '42')";
                $o_cond = '28|29|39|42';
                break;
            case '13':
                $osql = " AND (s.`occupation` = '6' OR s.`occupation` = '18' OR s.`occupation` = '19')";
                $o_cond = '6|18|19';
                break;
            case '14':
                $osql = " AND s.`occupation` = '23'";
                $o_cond = '23';
                break;
        }
    	$cond[] = array('occupation',$o_cond,false);
		$rs = SearchByTag($sex,$sortway,$cond,$offset,$pagesize);
		$total = 0;
		if(isset($rs['total_found'])) $total = $rs['total_found'];
		if($total > 600) $total = 600;
		$total_pages = ceil($total / $pagesize);
        $page = min($page, $total_pages);
        if($total>0){
        	$ids = array();
        	if(isset($rs['ids'])) $ids = $rs['ids'];
        	if(!empty($ids)){
				$ids_str = implode(',',$ids);
				$user = $_MooClass['MooMySQL']->getAll("select s.*,b.mainimg from {$dbTablePre}".$searchtable." s left join {$dbTablePre}members_base b on s.uid=b.uid where s.uid in ($ids_str)");
				$user = userbasicadd($user);
        	}
        	$recommend = array();
        	if(isset($rs['ids2']) && !empty($rs['ids2'])){//推荐会员信息
	            $ids2 = $rs['ids2'];
	            $ids_str2 = implode(',',$ids2);
	            $recommend_list = $_MooClass['MooMySQL']->getAll("select s.uid,s.gender,b.mainimg,s.city_star,s.images_ischeck,s.s_cid,s.nickname from `{$dbTablePre}members_search` s left join `{$dbTablePre}members_base` b on s.uid=b.uid where s.uid in ($ids_str2)");
	            shuffle($recommend_list);
	            $recommend = array_slice($recommend_list,0,6);
			}
        }
//		echo $osql;die;
//		echo "select count(*) as count from `{$dbTablePre}members` s,`{$dbTablePre}memberfield` sf where is_lock = 1 and `uid` = sf.`uid` AND `gender` = '$sex' $osql";die;
		/*
        //note 查询出来的总数
        $total_count = $_MooClass['MooMySQL']->getOne("select count(*) as count from `{$dbTablePre}members` s,`{$dbTablePre}memberfield` sf where is_lock = 1 and s.`uid` = sf.`uid` AND `gender` = '$sex' $osql");
        $total = $total_count["count"];
        if ($total > 600) {
            $total = 600;
        }
		*/
//		$total = 600;
//
//        $total_pages = ceil($total / $pagesize);
//        $page = min($page, $total_pages);
//
//        //note limit查询开始位置
//        $start = ($page - 1) * $pagesize;
//        if ($total - $start < $pagesize) {
//            $pagesize = $total - $start;
//        }
//        if ($total > 0) {
//            $csql = "select * from {$dbTablePre}".$searchtable." s,`{$dbTablePre}members_base` sf where s.images_ischeck='1' and s.is_lock='1' and s.`uid` = sf.`uid` AND s.`gender` = '{$sex}' $osql  $sort LIMIT $start,$pagesize";
//            $csql2 = "select s.uid,gender,mainimg,city_star,images_ischeck,s_cid,nickname  from `{$dbTablePre}members_search` s,`{$dbTablePre}members_base` sf where s.images_ischeck='1' and s.is_lock='1' and s.`uid` = sf.`uid` AND `gender` = '{$sex}' $osql and  is_vote='1'  LIMIT 6";
//            $user = $_MooClass['MooMySQL']->getAll($csql);
//			$user = userbasicadd($user);
//            //推荐会员信息
//            $recommend_list = $_MooClass['MooMySQL']->getAll($csql2);
//
//            shuffle($recommend_list);
//            $recommend = array_slice($recommend_list, 0, 6);
//        }
    //note 血型标签搜索
    } else if ($tagname == 'bloodtype') {
    	$cond[] = array('bloodtype',$tagid,false);
		$rs = SearchByTag($sex,$sortway,$cond,$offset,$pagesize);
		$total = 0;
		if(isset($rs['total_found'])) $total = $rs['total_found'];
		if($total > 600) $total = 600;
		$total_pages = ceil($total / $pagesize);
        $page = min($page, $total_pages);
        if($total>0){
        	$ids = array();
        	if(isset($rs['ids'])) $ids = $rs['ids'];
        	if(!empty($ids)){
				$ids_str = implode(',',$ids);
				$user = $_MooClass['MooMySQL']->getAll("select s.*,b.mainimg from {$dbTablePre}".$searchtable." s left join {$dbTablePre}members_base b on s.uid=b.uid where s.uid in ($ids_str)");
				$user = userbasicadd($user);
        	}
        	$recommend = array();
        	if(isset($rs['ids2']) && !empty($rs['ids2'])){//推荐会员信息
	            $ids2 = $rs['ids2'];
	            $ids_str2 = implode(',',$ids2);
	            $recommend_list = $_MooClass['MooMySQL']->getAll("select s.uid,s.gender,b.mainimg,s.city_star,s.images_ischeck,s.s_cid,s.nickname from `{$dbTablePre}members_search` s left join `{$dbTablePre}members_base` b on s.uid=b.uid where s.uid in ($ids_str2)");
	            shuffle($recommend_list);
	            $recommend = array_slice($recommend_list,0,6);
			}
        }
		/*
        //note 查询出来的总数
        $total_count = $_MooClass['MooMySQL']->getOne("select count(*) as count from `{$dbTablePre}members` s,`{$dbTablePre}memberfield` sf where is_lock = 1 and `gender` = '$sex' AND s.`uid` = sf.`uid` AND sf.`bloodtype` = '$tagid'");
        $total = $total_count["count"];
        if ($total > 600) {
            $total = 600;
        }
		*/
    	
//		$total = 600;
//
//        $total_pages = ceil($total / $pagesize);
//        $page = min($page, $total_pages);
//
//        //note limit查询开始位置
//        $start = ($page - 1) * $pagesize;
//        if ($total - $start < $pagesize) {
//            $pagesize = $total - $start;
//        }
//        if ($total > 0) {
//            $user = $_MooClass['MooMySQL']->getAll("select * from {$dbTablePre}".$searchtable." where images_ischeck='1' and is_lock='1' and `gender` = '{$sex}' AND bloodtype = '{$tagid}'  $sort LIMIT $start,$pagesize");
//			$user = userbasicadd($user);
//            //推荐会员信息
//            $recommend_list = $_MooClass['MooMySQL']->getAll("select s.uid,gender,mainimg,city_star,images_ischeck,s_cid,nickname  from `{$dbTablePre}members_search` s,`{$dbTablePre}members_base` sf where s.images_ischeck='1' and s.is_lock='1' and `gender` = '$sex' AND s.`uid` = sf.`uid` AND s.`bloodtype` = '{$tagid}' and is_vote='1'  LIMIT 6");
//            shuffle($recommend_list);
//            $recommend = array_slice($recommend_list, 0, 6);
//        }
    //note 星座标签搜索
    } else if ($tagname == 'constellation') {
    	$cond[] = array('constellation',$tagid,false);
		$rs = SearchByTag($sex,$sortway,$cond,$offset,$pagesize);
		$total = 0;
		if(isset($rs['total_found'])) $total = $rs['total_found'];
		if($total > 600) $total = 600;
		$total_pages = ceil($total / $pagesize);
        $page = min($page, $total_pages);
        if($total>0){
        	$ids = array();
        	if(isset($rs['ids'])) $ids = $rs['ids'];
        	if(!empty($ids)){
				$ids_str = implode(',',$ids);
				$user = $_MooClass['MooMySQL']->getAll("select s.*,b.mainimg from {$dbTablePre}".$searchtable." s left join {$dbTablePre}members_base b on s.uid=b.uid where s.uid in ($ids_str)");
				$user = userbasicadd($user);
        	}
        	$recommend = array();
        	if(isset($rs['ids2']) && !empty($rs['ids2'])){//推荐会员信息
	            $ids2 = $rs['ids2'];
	            $ids_str2 = implode(',',$ids2);
	            $recommend_list = $_MooClass['MooMySQL']->getAll("select s.uid,s.gender,b.mainimg,s.city_star,s.images_ischeck,s.s_cid,s.nickname from `{$dbTablePre}members_search` s left join `{$dbTablePre}members_base` b on s.uid=b.uid where s.uid in ($ids_str2)");
	            shuffle($recommend_list);
	            $recommend = array_slice($recommend_list,0,6);
			}
        }
		/*
        //note 查询出来的总数
        $total_count = $_MooClass['MooMySQL']->getOne("select count(*) as count from `{$dbTablePre}members` s,`{$dbTablePre}memberfield` sf where is_lock = 1 and `gender` = '$sex' AND s.`uid` = sf.`uid` AND sf.`constellation` = '$tagid'");
        $total = $total_count["count"];
        if ($total > 600) {
            $total = 600;
        }
		*/
    	
//		$total = 600;
//
//        $total_pages = ceil($total / $pagesize);
//        $page = min($page, $total_pages);
//
//        //note limit查询开始位置
//        $start = ($page - 1) * $pagesize;
//        if ($total - $start < $pagesize) {
//            $pagesize = $total - $start;
//        }
//        if ($total > 0) {
//            $user = $_MooClass['MooMySQL']->getAll("select * from {$dbTablePre}".$searchtable." where images_ischeck='1' and is_lock='1' and `gender` = '{$sex}' AND `constellation` = '{$tagid}'  $sort LIMIT $start,$pagesize");
//			$user = userbasicadd($user);
//            //推荐会员信息
//            $recommend_list = $_MooClass['MooMySQL']->getAll("select s.uid,gender,mainimg,city_star,images_ischeck,s_cid,nickname  from `{$dbTablePre}members_search` s,`{$dbTablePre}members_base` sf where s.images_ischeck='1' and s.is_lock='1' and `gender` = '{$sex}' AND s.`uid` = sf.`uid` AND s.`constellation` = '{$tagid}' and is_vote='1' LIMIT 6");
//            shuffle($recommend_list);
//            $recommend = array_slice($recommend_list, 0, 6);
//        }
    //note 生肖标签搜索
    } else if ($tagname == 'animalyear') {
    	$cond[] = array('animalyear',$tagid,false);
		$rs = SearchByTag($sex,$sortway,$cond,$offset,$pagesize);
		$total = 0;
		if(isset($rs['total_found'])) $total = $rs['total_found'];
		if($total > 600) $total = 600;
		$total_pages = ceil($total / $pagesize);
        $page = min($page, $total_pages);
        if($total>0){
        	$ids = array();
        	if(isset($rs['ids'])) $ids = $rs['ids'];
        	if(!empty($ids)){
				$ids_str = implode(',',$ids);
				$user = $_MooClass['MooMySQL']->getAll("select s.*,b.mainimg from {$dbTablePre}".$searchtable." s left join {$dbTablePre}members_base b on s.uid=b.uid where s.uid in ($ids_str)");
				$user = userbasicadd($user);
        	}
        	$recommend = array();
        	if(isset($rs['ids2']) && !empty($rs['ids2'])){//推荐会员信息
	            $ids2 = $rs['ids2'];
	            $ids_str2 = implode(',',$ids2);
	            $recommend_list = $_MooClass['MooMySQL']->getAll("select s.uid,s.gender,b.mainimg,s.city_star,s.images_ischeck,s.s_cid,s.nickname from `{$dbTablePre}members_search` s left join `{$dbTablePre}members_base` b on s.uid=b.uid where s.uid in ($ids_str2)");
	            shuffle($recommend_list);
	            $recommend = array_slice($recommend_list,0,6);
			}
        }
		/*
        //note 查询出来的总数
        $total_count = $_MooClass['MooMySQL']->getOne("select count(*) as count from `{$dbTablePre}members` s,`{$dbTablePre}memberfield` sf where is_lock = 1 and `gender` = '$sex' AND s.`uid` = sf.`uid` AND sf.`animalyear` = '$tagid'");
        $total = $total_count["count"];
        if ($total > 600) {
            $total = 600;
        }
		*/
//		$total = 600;
//
//        $total_pages = ceil($total / $pagesize);
//        $page = min($page, $total_pages);
//
//        //note limit查询开始位置
//        $start = ($page - 1) * $pagesize;
//        if ($total - $start < $pagesize) {
//            $pagesize = $total - $start;
//        }
//
//        if ($total > 0) {
//            $user = $_MooClass['MooMySQL']->getAll("select * from {$dbTablePre}".$searchtable." where images_ischeck='1' and is_lock='1' and `gender` = '{$sex}' AND `animalyear` = '{$tagid}'  $sort LIMIT $start,$pagesize");
//			$user = userbasicadd($user);
//            //推荐会员信息
//            $recommend_list = $_MooClass['MooMySQL']->getAll("select s.uid,gender,sf.mainimg,city_star,images_ischeck,s_cid,nickname  from `{$dbTablePre}members_search` s,`{$dbTablePre}members_base` sf where s.images_ischeck='1' and s.is_lock='1' and `gender` = '{$sex}' AND s.`uid` = sf.`uid` AND s.`animalyear` = '{$tagid}' and sf.is_vote='1' LIMIT 6");
//            shuffle($recommend_list);
//            $recommend = array_slice($recommend_list, 0, 6);
//        }
    //note 体型标签搜索
    } else if ($tagname == 'body') {
    	$cond[] = array('body',$tagid,false);
		$rs = SearchByTag($sex,$sortway,$cond,$offset,$pagesize);
		$total = 0;
		if(isset($rs['total_found'])) $total = $rs['total_found'];
		if($total > 600) $total = 600;
		$total_pages = ceil($total / $pagesize);
        $page = min($page, $total_pages);
        if($total>0){
        	$ids = array();
        	if(isset($rs['ids'])) $ids = $rs['ids'];
        	if(!empty($ids)){
				$ids_str = implode(',',$ids);
				$user = $_MooClass['MooMySQL']->getAll("select s.*,b.mainimg from {$dbTablePre}".$searchtable." s left join {$dbTablePre}members_base b on s.uid=b.uid where s.uid in ($ids_str)");
				$user = userbasicadd($user);
        	}
        	$recommend = array();
        	if(isset($rs['ids2']) && !empty($rs['ids2'])){//推荐会员信息
	            $ids2 = $rs['ids2'];
	            $ids_str2 = implode(',',$ids2);
	            $recommend_list = $_MooClass['MooMySQL']->getAll("select s.uid,s.gender,b.mainimg,s.city_star,s.images_ischeck,s.s_cid,s.nickname from `{$dbTablePre}members_search` s left join `{$dbTablePre}members_base` b on s.uid=b.uid where s.uid in ($ids_str2)");
	            shuffle($recommend_list);
	            $recommend = array_slice($recommend_list,0,6);
			}
        }
		/*
        //note 查询出来的总数
        $total_count = $_MooClass['MooMySQL']->getOne("select count(*) as count from `{$dbTablePre}members` s,`{$dbTablePre}memberfield` sf where is_lock = 1 and `gender` = '$sex' AND s.`uid` = sf.`uid` AND sf.`body` = '$tagid'");
        $total = $total_count["count"];
        if ($total > 600) {
            $total = 600;
        }
		*/
//		$total = 600;
//
//        $total_pages = ceil($total / $pagesize);
//        $page = min($page, $total_pages);
//
//        //note limit查询开始位置
//        $start = ($page - 1) * $pagesize;
//        if ($total - $start < $pagesize) {
//            $pagesize = $total - $start;
//        }
//        if ($total > 0) {
//            $user = $_MooClass['MooMySQL']->getAll("select * from {$dbTablePre}".$searchtable." where images_ischeck='1' and is_lock='1' and `gender` = '{$sex}' AND `body` = '{$tagid}'  $sort LIMIT $start,$pagesize");
//			$user = userbasicadd($user);
//            //推荐会员信息
//            $recommend_list = $_MooClass['MooMySQL']->getAll("select s.uid,gender,sf.mainimg,city_star,images_ischeck,s_cid,nickname  from `{$dbTablePre}members_search` s,`{$dbTablePre}members_base` sf where s.images_ischeck='1' and s.is_lock='1' and `gender` = '{$sex}' AND s.`uid` = sf.`uid` AND s.`body` = '{$tagid}'  and is_vote='1'  LIMIT 6");
//            shuffle($recommend_list);
//            $recommend = array_slice($recommend_list, 0, 6);
//        }
    //note 性格标签搜索
    } else if ($tagname == 'nature') {
		/*
        //note 查询出来的总数
        $total_count = $_MooClass['MooMySQL']->getOne("select count(*) as count from `{$dbTablePre}members` s,`{$dbTablePre}memberfield` sf where is_lock = 1 and `gender` = '$sex' AND s.`uid` = sf.`uid` AND sf.`nature` = '$tagid'");
        $total = $total_count["count"];
        if ($total > 600) {
            $total = 600;
        }
		*/
    	if($sortway == '1'){
			//note 默认排序
			$sort = " ORDER BY city<>'{$work_city}',city desc,s_cid asc,pic_num desc,`city_star` DESC,images_ischeck desc ";
		}elseif($sortway == '2'){
			//note 最新注册的排序
			$sort = " ORDER BY city<>'{$work_city}',city desc,s_cid asc,pic_num desc,`regdate`  DESC,s_cid asc ,images_ischeck desc ";
		}elseif($sortway == '3'){
			//note 按照诚信等级排序
			$sort = "ORDER BY city<>'{$work_city}',city desc,s_cid asc,pic_num desc,`certification` DESC,s_cid asc,images_ischeck desc ";
		}else{
			//note 默认排序
			$sort = " ORDER BY city<>'{$work_city}',city desc,s_cid asc,pic_num desc,`city_star` DESC,s_cid asc ,images_ischeck desc";
		}
		$total = 600;

        $total_pages = ceil($total / $pagesize);
        $page = min($page, $total_pages);

        //note limit查询开始位置
        $start = ($page - 1) * $pagesize;
        if ($total - $start < $pagesize) {
            $pagesize = $total - $start;
        }
        if ($total > 0) {
            $user = $_MooClass['MooMySQL']->getAll("select s.*,sf.mainimg from `{$dbTablePre}members_search` s left join `{$dbTablePre}members_base` sf on s.uid=sf.uid where s.images_ischeck='1' and s.is_lock='1' and s.`gender` = '{$sex}' AND s.`uid` = sf.`uid` AND sf.`nature` = '{$tagid}' $sort LIMIT $start,$pagesize");
			$user = userbasicadd($user);
            //推荐会员信息
            $recommend_list = $_MooClass['MooMySQL']->getAll("select s.uid,s.gender,sf.mainimg,s.city_star,s.images_ischeck,s.s_cid,s.nickname  from `{$dbTablePre}members_search` s,`{$dbTablePre}members_base` sf where s.images_ischeck='1' and s.is_lock='1' and s.`gender` = '{$sex}' AND s.`uid` = sf.`uid` AND sf.`nature` = '{$tagid}'  and s.is_vote='1' LIMIT 6");
            shuffle($recommend_list);
            $recommend = array_slice($recommend_list, 0, 6);
        }
    }
    if (count($user) == '0') {
        require MooTemplate('public/search_error', 'module');
        exit;
    }

    if (isset($_GET['condition']) && $_GET['condition'] == '2') {
        $pagesize = 6;
        $condition = '2';
		$htm = 'search_tag_page';
    } else if (isset($_GET['condition']) && $_GET['condition'] == '3') {
        $pagesize = 16;
    } else {
        $pagesize = 6;
    }
//	print_r($recommend);die;

    //note 选择不同的显示模式
		if (isset($_GET['condition']) && $_GET['condition'] == '2') {
			
		} else if (isset($_GET['condition']) && $_GET['condition'] == '3') {
			$condition = '3';
			$htm = 'search_tag_photo';
		} else {
			$condition = '2';
			$htm = 'search_tag_page';
		}
		include MooTemplate('public/'.$htm, 'module');
}

//note 快速查询显示页面
//function quick_search_page($gender, $age_start, $age_end, $work_province, $work_city, $photo) {
//    global $_MooClass, $dbTablePre;
//    global $user_arr, $userid,$last_login_time;
//
//    $timestamp = time();
//    $expiretime2 = $timestamp - 3600; //过期时间
//    $returnurl = 'index.php?' . $_SERVER['QUERY_STRING']; //返回的url 
//    //note 获得当前的url 去除多余的参数page=
//    $currenturl = 'http://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
//    //echo $currenturl;
//    $currenturl2 = preg_replace("/(&page=\d+)/", "", $currenturl);
//
//    $url3 = preg_replace("/(&orderby=\d+)/", "", $currenturl2);
//
//    $is_online_url = preg_replace("/(&is_online=\d+)/", "", $currenturl2) . "&is_online=1";
//    //echo $currenturl2;
//
//    /* 以下分页参数设置 */
//    //note  每页显示个数
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
//    //note limit查询年龄开始位置
//    $start = ($page - 1) * $pagesize;
//    $age_sql = '';
//    if ($age_end > 0 && $age_start > 0) {
//        $tmp_start = date('Y', time()) - intval($age_start);
//        $tmp_end = date('Y', time()) - intval($age_end);
//        if ($age_start > $age_end) {
//            $age_sql = " and m.birthyear >= '" . $tmp_start . "' and m.birthyear <= '" . $tmp_end . "'";
//        } else {
//            $age_sql = " and m.birthyear >= '" . $tmp_end . "' and m.birthyear <= '" . $tmp_start . "'";
//        }
//    } else if ($age_start > 0) {
//        $tmp_start = date('Y', time()) - intval($age_start);
//        $age_sql = " and m.birthyear <= '" . $tmp_start . "'";
//    } else if ($age_end > 0) {
//        $tmp_end = date('Y', time()) - intval($age_end);
//        $age_sql = " and m.birthyear >= '" . $tmp_end . "'";
//    }
//
//    //note 转换成地区查询sql语句
//    
//    
//  
//	        
//    $area_sql = '';
//    //=====================选择了省 和 市 =======================================
//    
//    if (!($work_city==-1 || $work_province==-1 || empty($work_city) || empty($work_province))){
//        
//    	$area_sql = ' and m.province = ' . $work_province . ' AND m.city = ' . $work_city;
//    	
//    	$order_province=$work_province;
//        $order_city=$work_city;
//    }
//    
//    
//    //=====================选择了 省 ===========================================
//    if ($work_city == '-1' && $work_province != '-1') {
//        //note 修正广东省深圳和广州的区域查询
//	    include_once("./module/crontab/crontab_config.php");
//	    $cur_ip = GetIP();
//	    MooPlugins('ipdata');
//	    $ip_arr = convertIp($cur_ip);
//	
//	    //得到省份对应的数值，查库 
//	    foreach($provice_list as $key => $val){
//	       if(strstr($ip_arr,$val)){
//	          $province = $key;
//	          break;
//	       }
//	    }
//	            
//	    //得到市对应的城市代号
//	    foreach($city_list as $key =>$val){
//	       if(strstr($ip_arr,$val)){
//	         $city = $key;
//	         break;
//	       }
//	    }
//	    
//	    if($work_province==$province){
//	       $order_city=$city;
//	    }
//        
//        if(in_array($work_province, array(10101201,10101002))) {
//            $work_city = -1;
//            $work_province = 10101000;
//        }
//        
//        
//        //修正直辖市查询
//        if(in_array($work_province, array('10102000', '10103000', '10104000', '10105000'))) {
//            $work_city = '-1';
//        }
//        $area_sql = ' and m.province = ' . $work_province ;
//        
//        $order_province=$work_province;
//        
//       
//        
//        //=============取 当前 登录 网站用户的IP地址  对应所在 地区begin====================================
//        
//        
//    }
//    
//    //=====================search area no limit  选择不限=============================
//    if ($work_province == '-1' && $work_city == '-1') {
//     //note 修正广东省深圳和广州的区域查询
//        include_once("./module/crontab/crontab_config.php");
//        $cur_ip = GetIP();
//        //$cur_ip='219.151.125.20';西藏测试IP
//        MooPlugins('ipdata');
//        $ip_arr = convertIp($cur_ip);
//        
//    
//        //得到省份对应的数值，查库
//                
//        foreach($provice_list as $key => $val){
//           if(strstr($ip_arr,$val)){
//              $province = $key;
//              break;
//           }
//        }
//                
//        //得到市对应的城市代号
//        foreach($city_list as $key =>$val){
//           if(strstr($ip_arr,$val)){
//             $city = $key;
//             break;
//           }
//        }
//        
//        if(empty($city)){ $city='-1';}
//        
//        $order_province=$province;
//        $order_city=$city;
//
//        $area_sql = "";
//    }   
//    
//    
//    /*if(!empty($user_arr)){
//    	if ($work_provinc0 '-1' && $work_city == '-1') {
//    		$area_sql = " and (m.province =".$user_arr['province'];
//    		$work_city=$user_arr['city'];
//        	//$area_sql = "and ((m.province =".$user_arr['province']." and m.city=".$user_arr['city'].") or (m.province =".$user_arr['province']."))";
//    	}	
//    }elseif(empty($user_arr)){
//    	if ($work_province == '-1' && $work_city == '-1') {
//    		$work_city=getLocalArea();
//            $area_sql = "and (m.province =".$work_province." )";
//        }   
//    }*/
//	
//    //note 查询是否显示照片
//	$photo_sql='';
//    if ($photo == '1') {
//        $photo_sql = " AND m.images_ischeck = '1'";
//    }/*else{
//		$photo_sql = " AND images_ischeck in(1,0,-1)";
//	}*/
//    
//    if (!in_array($gender, array(0, 1))) {
//        $gender = 1;
//    }
//
//    //note 组合SQL语句
//    $sql = " where m.is_lock = '1'  " . $age_sql . $area_sql . $photo_sql; //未封锁用户且对外公开信息
//
//    if ($_GET['is_online']) {
//        $lasttime = time() - 15 * 60;
//        $sql .= "  and  m.lastvisit>'$lasttime' ";
//    }
//    
//    $sql_md5 = md5($gender.$sql); //md5保存sql
//    $sql_search = "select id from `{$dbTablePre}member_tmp` where `condition_md5`='{$sql_md5}' and expiretime > '{$expiretime2}'";
//    $ret_search = $_MooClass['MooMySQL']->getOne($sql_search);
//    
//    //有保存的搜索条件时
//   /* if ($ret_search) {
//    	//echo $sql_md5;
//        $search_id = $ret_search['id'];
//        search_index($search_id, $gender, $age_start, $age_end, $work_province, $work_city, $photo, $marriage = '-1', $salary = '-1', $education = '-1', $height1 = '-1', $height2 = '-1', $home_townprovince = '-1', $home_towncity = '-1', $house = '-1', $vehicle = '-1',$user_list='1',$order_province,$order_city);
//    } else {*/
//    	
//    	
//    	//note 页面访问限制未测试方便暂时注销
//       /* if ($user_arr['last_search_time'] + 30 >= $tiemstamp) {
//            MooMessage('对不起，您30秒内只能搜索一次，请返回！', $_SERVER['HTTP_REFERER'], '03');
//        } else {
//            $_MooClass['MooMySQL']->query("update {$dbTablePre}members set last_search_time='$tiemstamp' where uid='$userid'");
//        }*/
//
//        //note 查询出来的总数
//        $total_count = $_MooClass['MooMySQL']->getOne("SELECT count(uid) as count FROM web_members m " . $sql);
//        $total_condition = $total = $total_count["count"];
//        if ($total>=600){
//		    $total_condition = $total = 600;
//        }
//        
//        $is_data = true;
//     
//        $total_pages = ceil($total / $pagesize);
//        $page = min($page, $total_pages);
//
//        //note limit查询开始位置
//        $start = ($page - 1) * $pagesize;
//
//		//note 普通会员搜索结果还是按照以前的排序，高级、钻石会员搜索结果按照本站注册的排序
//		/*if($user_arr["s_cid"]=="2"){
//			if(empty($area_sql)){
//				$sort = " order by m.s_cid asc, m.bgtime desc, m.birthyear desc, m.pic_num desc,m.images_ischeck desc";
//			}else{
//				$sort = " order by m.city_star desc, m.s_cid asc, m.bgtime desc, m.birthyear desc, m.pic_num desc,m.images_ischeck desc"; //城市之星靠前	
//			}
//		}else{*/
//		if(empty($area_sql)){
//			$sort = " order by  province<>'{$order_province}',city<>'{$order_city}',m.city desc,m.province desc,m.s_cid asc, m.images_ischeck desc,m.pic_num desc "; //,m.bgtime desc, m.birthyear desc
//		}else{
//			$sort = " order by  province<>'{$order_province}',city<>'{$order_city}',m.city desc,m.province desc,m.s_cid asc, m.images_ischeck desc,m.pic_num desc "; //城市之星靠前	,m.bgtime desc, m.birthyear desc
//		}
//		//}
//       
//	   
//       //note 分开搜索男性表和女性表
//       $table_searchfast = 'membersfastsort_women';
//       if($gender == 0){
//       		$table_searchfast = 'membersfastsort_man';
//       }
//	   //sql 语句      
//       $fast_sql = $sql;
// 	   MooSetCookie('fast_sql',$fast_sql);
//        if ($total_condition >= $pagesize) {
//            $user = $_MooClass['MooMySQL']->getAll("SELECT m.uid  as uid FROM {$dbTablePre}$table_searchfast m " . $fast_sql . " and m.usertype=1 $sort LIMIT 0,600");
//        } else {
//            if ($total_condition != 0) {
//                //找到只有一页的数据，代码去除
//				/*$user = $_MooClass['MooMySQL']->getAll("SELECT m.uid FROM {$dbTablePre}$table_searchfast m  " . $fast_sql . "  $sort");
//                $no_member_id = "";
//                foreach ($user as $tmp) {
//                    $no_member_id .= $tmp["uid"] . ",";
//                }
//                $no_member_id .= 0;
//                if ($work_province != -1 && $work_city != -1) {
//                    $area_sql = ' AND m.province = ' . $work_province;
//                    $sql1 = " where m.uid not in (" . $no_member_id . ") and m.is_lock = 1 AND m.gender='$gender' " . $age_sql . $area_sql . $photo_sql;
//
//                    if ($page == 1) {
//                        $limit = $pagesize - $total_condition;
//                        $user1 = $_MooClass['MooMySQL']->getAll("SELECT m.uid FROM {$dbTablePre}members m " . $sql1 . " limit 0, " . $limit);
//                    } else {
//                        $offset = ($page - 1) * $pagesize - $total_condition;
//                        if ($offset >= 0) {
//                            $user = $_MooClass['MooMySQL']->getAll("SELECT m.uid FROM {$dbTablePre}members m " . $sql1 . " limit " . $offset . ", " . $pagesize);
//                        }
//                    }
//                }*/
//            } else {
//            	
//            	//==以前代码没有用 没有找到（省市不为空的情况下，按照省来查）
//                /*if ($work_province != -1 && $work_city != -1) {
//                    $area_sql = ' AND m.province = ' . $work_province;
//                    $sql1 = " where m.is_lock = 1 AND m.gender='$gender' " . $age_sql . $area_sql . $photo_sql;
//
//                    $offset = ($page - 1) * $pagesize - $total_condition;
//                    if ($offset >= 0) {
//                        $is_data = true;
//                        if ($page == 1) {
//                            $is_data = false;
//                        }
//                        $user1 = $_MooClass['MooMySQL']->getAll("SELECT m.uid FROM {$dbTablePre}members m " . $sql1 . " limit " . 0 . ", " . 600);
//                    }
//                }*/
//                
//            }
//        }
//        
//        //echo "SELECT m.uid  as uid FROM {$dbTablePre}$table_searchfast m " . $fast_sql . "  $sort LIMIT 0,600";
//        
//		$user_origination=array();
//
//		foreach($user as $k=>$u){
//		  $user_origination[$k]=$u['uid'];
//		}
//		
//		//print_r($user_origination);
//        //20110925 采集的300个会员
//        $sql_acquisition="select m.uid as uid FROM {$dbTablePre}$table_searchfast m " . $fast_sql . " and m.usertype=3   {$sort}   limit 300";
//        //echo "<br/>".$sql_acquisition;
//        //echo $sql_acquisition.'ddd';
//        $result_acquisition=$_MooClass['MooMySQL']->getAll($sql_acquisition);
//        
//        
//        
//		$user_acquisition=array();
//		foreach($result_acquisition as $k=>$u){
//		  if($gender == 1){//女的奇数
//		    $k=2*$k+1;
//		  }else{//男的偶数
//		  	$k=2*$k; 
//		  }
//		  $user_acquisition[$k]=$u['uid'];
//		}
//		
//		$result=array();
//		$result=array_replace($user_origination,$user_acquisition);
//		
//		$user_list=implode(",",$result);
//        
//		
//	
//         
//        /*$user_list = "";
//        if (!empty($user)) {
//            foreach ($user as $v) {
//                $user_uid .= $v['uid'] . ',';
//            }
//            if (!empty($user_uid))
//                $user_uid = substr($user_uid, 0, -1);
//            $user_list .= $user_uid;
//           
//        }*/
//
//        /*if (!empty($user1)) {
//            foreach ($user1 as $v) {
//                $user_uid2 .= $v['uid'] . ',';
//            }
//            if (!empty($user_uid2))
//                $user_uid2 = substr($user_uid2, 0, -1);
//            $user_list .= '|' . $user_uid2;
//        }*/
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
//            $search_id = $_MooClass['MooMySQL']->insertId();
//        }
//        
//       print_r("search_id:".$search_id);
//        search_index($search_id, $gender, $age_start, $age_end, $work_province, $work_city, $photo, $marriage = '-1', $salary = '-1', $education = '-1', $height1 = '-1', $height2 = '-1', $home_townprovince = '-1', $home_towncity = '-1', $house = '-1', $vehicle = '-1');
//        exit;
//		
//   // }
//}
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
	$cond[] = array('usertype','1|3',false);
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
		}
	    //note 找不到匹配的结果返回单独提示页面
	    if (empty($user) && empty($user1)) {
	        include MooTemplate('public/search_error', 'module');
	        exit;
	    }
	
	    //note 记录婚姻状况
	    $marriageArr = array();
	    if (isset($marriage) && $marriage != '0') {
	        if (strlen($marriage) == 1) {
	            $marriageArr[] = $marriage;
	        } else {
	            $marriageArr = explode(',', $marriage);
	        }
	    }
	    //note 记录月收入
	    $salaryArr = array();
	    if (isset($salary) && $salary != '0') {
	        if (strlen($salary) == 1) {
	            $salaryArr[] = $salary;
	        } else {
	            $salaryArr = explode(',', $salary);
	        }
	    }
	    //note 记录教育情况
	    $educationArr = array();
	    if (isset($education) && $education != '0') {
	        if (strlen($education) == 1) {
	            $educationArr[] = $education;
	        } else {
	            $educationArr = explode(',', $education);
	        }
	    }
	
	    //note 记录住房情况
	    $houseArr = array();
	    if (isset($house) && $house != '0') {
	        if (strlen($house) == 1) {
	            $houseArr[] = $house;
	        } else {
	            $houseArr = explode(',', $house);
	        }
	    }
	
	    //note 记录是否购车
	    $vehicleArr = array();
	    if (isset($vehicle) && $vehicle != '0') {
	        if (strlen($vehicle) == 1) {
	            $vehicleArr[] = $vehicle;
	        } else {
	            $vehicleArr = explode(',', $vehicle);
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

//note 加载以前的搜索条件
function condition_in_update($gender, $age_start, $age_end, $work_province, $work_city, $marriage, $salary, $education, $height1, $height2, $weight1, $weight2, $body, $smoking, $drinking, $occupation, $house, $vehicle, $corptype, $children, $wantchildren, $home_townprovince, $home_towncity, $nation, $animalyear, $constellation, $bloodtype, $religion, $family, $language,$photo, $sid,$is_commend,$issavesearchname,$searchname,$currenturl){
    global $_MooClass, $dbTablePre, $userid,$last_login_time;
    global $user_arr;
//	echo "搜索条件名称=".$searchname;
//	echo "<br>以前的搜索id=".$sid;
//	echo "<br>是否确定保存搜索条件=".$issavesearchname;
//	echo "<br>是否定期存到邮箱=".$is_commend;die;

    //note 搜索名称
    $searchsign = safeFilter($searchname);

    //note 性别
    if ($gender && is_array($gender)) {
        $searchcondition = '<gender>' . implode(',', $gender) . '</gender>';
    } else {
        $searchcondition = '<gender>' . $gender . '</gender>';
    }

    //note 年龄范围
    if ($age_start && is_array($age_start)) {
        $searchcondition .= '<age_start>' . implode(',', $age_start) . '</age_start>';
    } else {
        $searchcondition .= '<age_start>' . $age_start . '</age_start>';
    }
    if ($age_end && is_array($age_end)) {
        $searchcondition .= '<age_end>' . implode(',', $age_end) . '</age_end>';
    } else {
        $searchcondition .= '<age_end>' . $age_end . '</age_end>';
    }

    //note 工作省、市
    if ($work_province && is_array($work_province)) {
        $searchcondition .= '<work_province>' . implode(',', $work_province) . '</work_province>';
    } else {
        $searchcondition .= '<work_province>' . $work_province . '</work_province>';
    }
    if ($work_city && is_array($work_city)) {
        $searchcondition .= '<work_city>' . implode(',', $work_city) . '</work_city>';
    } else {
        $searchcondition .= '<work_city>' . $work_city . '</work_city>';
    }

    //note 婚姻
    if ($marriage && is_array($marriage)) {
        $searchcondition .= '<marriage>' . implode(',', $marriage) . '</marriage>';
    } else {
        $searchcondition .= '<marriage>' . $marriage . '</marriage>';
    }

    //note 收入
    if ($salary && is_array($salary)) {
        $searchcondition .= '<salary>' . implode(',', $salary) . '</salary>';
    } else {
        $searchcondition .= '<salary>' . $salary . '</salary>';
    }

    //note 教育程度
    if ($education && is_array($education)) {
        $searchcondition .= '<education>' . implode(',', $education) . '</education>';
    } else {
        $searchcondition .= '<education>' . $education . '</education>';
    }

    //note 身高
    if ($height1 && is_array($height1)) {
        $searchcondition .= '<height1>' . implode(',', $height1) . '</height1>';
    } else {
        $searchcondition .= '<height1>' . $height1 . '</height1>';
    }
    if ($height2 && is_array($height2)) {
        $searchcondition .= '<height2>' . implode(',', $height2) . '</height2>';
    } else {
        $searchcondition .= '<height2>' . $height2 . '</height2>';
    }

    //note 体重范围
    if ($weight1 && is_array($weight1)) {
        $searchcondition .= '<weight1>' . implode(',', $weight1) . '</weight1>';
    } else {
        $searchcondition .= '<weight1>' . $weight1 . '</weight1>';
    }
    if ($weight2 && is_array($weight2)) {
        $searchcondition .= '<weight2>' . implode(',', $weight2) . '</weight2>';
    } else {
        $searchcondition .= '<weight2>' . $weight2 . '</weight2>';
    }

    //note 体型
    if ($body && is_array($body)) {
        $searchcondition .= '<body1>' . implode(',', $body) . '</body1>';
    } else {
        $searchcondition .= '<body1>' . $body . '</body1>';
    }

    //note 是否吸烟
    if ($smoking && is_array($smoking)) {
        $searchcondition .= '<smoking>' . implode(',', $smoking) . '</smoking>';
    } else {
        $searchcondition .= '<smoking>' . $smoking . '</smoking>';
    }

    //note 是否饮酒
    if ($drinking && is_array($drinking)) {
        $searchcondition .= '<drinking>' . implode(',', $drinking) . '</drinking>';
    } else {
        $searchcondition .= '<drinking>' . $drinking . '</drinking>';
    }

    //note 从事职业
    if ($occupation && is_array($occupation)) {
        $searchcondition .= '<occupation>' . implode(',', $occupation) . '</occupation>';
    } else {
        $searchcondition .= '<occupation>' . $occupation . '</occupation>';
    }

    //note 住房条件
    if ($house && is_array($house)) {
        $searchcondition .= '<house>' . implode(',', $house) . '</house>';
    } else {
        $searchcondition .= '<house>' . $house . '</house>';
    }

    //note 是否购车
    if ($vehicle && is_array($vehicle)) {
        $searchcondition .= '<vehicle>' . implode(',', $vehicle) . '</vehicle>';
    } else {
        $searchcondition .= '<vehicle>' . $vehicle . '</vehicle>';
    }

    //note 公司类别
    if ($corptype && is_array($corptype)) {
        $searchcondition .= '<corptype>' . implode(',', $corptype) . '</corptype>';
    } else {
        $searchcondition .= '<corptype>' . $corptype . '</corptype>';
    }

    //note 是否有孩子
    if ($children && is_array($children)) {
        $searchcondition .= '<children>' . implode(',', $children) . '</children>';
    } else {
        $searchcondition .= '<children>' . $children . '</children>';
    }

    //note 是否想要孩子
    if ($wantchildren && is_array($wantchildren)) {
        $searchcondition .= '<wantchildren>' . implode(',', $wantchildren) . '</wantchildren>';
    } else {
        $searchcondition .= '<wantchildren>' . $wantchildren . '</wantchildren>';
    }

    //note 籍贯省、市
    if ($home_townprovince && is_array($home_townprovince)) {
        $searchcondition .= '<home_townprovince>' . implode(',', $home_townprovince) . '</home_townprovince>';
    } else {
        $searchcondition .= '<home_townprovince>' . $home_townprovince . '</home_townprovince>';
    }
    if ($home_towncity && is_array($home_towncity)) {
        $searchcondition .= '<home_towncity>' . implode(',', $home_towncity) . '</home_towncity>';
    } else {
        $searchcondition .= '<home_towncity>' . $home_towncity . '</home_towncity>';
    }

    //note 名族
    if ($nation && is_array($nation)) {
        $searchcondition .= '<nation>' . implode(',', $nation) . '</nation>';
    } else {
        $searchcondition .= '<nation>' . $nation . '</nation>';
    }

    //note 生肖
    if ($animalyear && is_array($animalyear)) {
        $searchcondition .= '<animalyear>' . implode(',', $animalyear) . '</animalyear>';
    } else {
        $searchcondition .= '<animalyear>' . $animalyear . '</animalyear>';
    }

    //note 星座
    if ($constellation && is_array($constellation)) {
        $searchcondition .= '<constellation>' . implode(',', $constellation) . '</constellation>';
    } else {
        $searchcondition .= '<constellation>' . $constellation . '</constellation>';
    }

    //note 血型
    if ($bloodtype && is_array($bloodtype)) {
        $searchcondition .= '<bloodtype>' . implode(',', $bloodtype) . '</bloodtype>';
    } else {
        $searchcondition .= '<bloodtype>' . $bloodtype . '</bloodtype>';
    }

    //note 信仰
    if ($religion && is_array($religion)) {
        $searchcondition .= '<religion>' . implode(',', $religion) . '</religion>';
    } else {
        $searchcondition .= '<religion>' . $religion . '</religion>';
    }

    //note 兄弟姐妹
    if ($family && is_array($family)) {
        $searchcondition .= '<family>' . implode(',', $family) . '</family>';
    } else {
        $searchcondition .= '<family>' . $family . '</family>';
    }

    //note 几种语言
    if ($language && is_array($language)) {
        $searchcondition .= '<language1>' . implode(',', $language) . '</language1>';
    } else {
        $searchcondition .= '<language1>' . $language . '</language1>';
    }

    //note 显示有照片的会员
    if ($photo && is_array($photo)) {
        $searchcondition .= '<photo>' . implode(',', $photo) . '</photo>';
    } else {
        $searchcondition .= '<photo>' . $photo . '</photo>';
    }

    //note 搜索条件标签
    if ($searchname && is_array($searchname)) {
        $searchcondition .= '<searchname>' . implode(',', $searchname) . '</searchname>';
    } else {
        $searchcondition .= '<searchname>' . $searchname . '</searchname>';
    }

	//note 是否保存
    if ($issavesearchname && is_array($issavesearchname)) {
        $searchcondition .= '<issavesearchname>' . implode(',', $issavesearchname) . '</issavesearchname>';
    } else {
        $searchcondition .= '<issavesearchname>' . $issavesearchname . '</issavesearchname>';
    }
//	echo $searchcondition;die;
    //note 编辑搜索条件保存，update表
    if ($sid) {
        $_MooClass['MooMySQL']->query("update `{$dbTablePre}searchbak` set `searchsign`='$searchsign',`searchcondition`='$searchcondition',`is_commend`='".$is_commend."' where `scid` = '$sid'");
        //note 高级搜索时候，保存一次搜索条件
    } else {
        $_MooClass['MooMySQL']->query("insert  into `{$dbTablePre}searchbak` set `uid`='$userid',`searchsign`='$searchsign',`searchcondition`='$searchcondition',`is_commend`='".$is_commend."'");
    }
}


/**
 * 显示从缓存中搜索出的结果////////////////////////////////////////
 * 
 */
/*
function search_index($search_id, $gender, $age_start, $age_end, $work_province, $work_city, $photo, $marriage='0', $salary='0', $education='0', $height1='0', $height2='0', $home_townprovince='0', $home_towncity='0', $house='0', $vehicle='0',$user_list='1',$order_province='',$order_city='') {
    global $_MooClass, $dbTablePre;
    global $user_arr, $userid,$last_login_time;
    
    //echo $order_province.'and'.$order_city;
    $weight1 = 0;
    $weight2 = 0;
    $body = 0;
    $smoking = 0;
    $drinking = 0;
    $occupation = 0;
    $corptype = 0;
    $children = 0;
    $wantchildren = 0;
    $nation = 0;
    $animalyear = 0;
    $constellation = 0;
    $bloodtype = 0;
    $religion = 0;
    $family = 0;
    $language = 0;

    $returnurl = 'index.php?' . $_SERVER['QUERY_STRING']; //返回的url

    //以下分页参数设置 
    //note  每页显示个数
    if ($_GET['condition'] == '2') {
        $pagesize = 6;
    } else if ($_GET['condition'] == '3') {
        $pagesize = 16;
    } else {
        $pagesize = 6;
    }
    //note 获得第几页
    $page = max(1, intval($_GET['page']));
    //note limit查询开始位置
    $start = ($page - 1) * $pagesize;
    
    $search_id = intval($search_id);
    
	if($user_list == '1'){
		
		//$sql = "select uid_list from `{$dbTablePre}member_tmp` where `id`='$search_id'";
		$ret_search = $_MooClass['MooMySQL']->getOne($sql);
	
		if (!$ret_search) {
			MooMessage("您指定的搜索不存在或已过期", "index.php?n=search", '03');
		}
	
		$user_list = $ret_search['uid_list'];
	}
	

    $user_list2 = '';
    if (strpos($ret_search['uid_list'], '|') !== false) {
        $user_list_arr = explode('|', $ret_search['uid_list']);
        $user_list = $user_list_arr[0];
        $user_list2 = $user_list_arr[1];
    }

    //城市之星靠前
//    $order_by = ' order by city_star desc, (s_cid=20) * bgtime desc , birthyear desc';
//	$order_by = ' order by city_star desc';

	//这里是排序方式
    
    
	$sortway = '1';
	if($_GET['sortway'] == '1'){
		//note 默认排序
		$sortway = '1';
		//note sql语句
		
		$order_by = " order by province<>'{$order_province}',city<>'{$order_city}',city desc,province desc,s_cid asc,pic_num desc,images_ischeck desc ";
	}elseif($_GET['sortway'] == '2'){
		//note 最新注册
		$sortway = '2';
		$order_by = " order by regdate desc ,province<>'{$order_province}',city<>'{$order_city}',city desc,province desc,s_cid asc,pic_num desc,city_star desc,images_ischeck desc";
	}elseif($_GET['sortway'] == '3'){
		//note 诚信等级
		$sortway = '3';
		$order_by = " order by  certification desc ,province<>'{$order_province}',city<>'{$order_city}',city desc,province desc,s_cid asc,pic_num desc,city_star desc,images_ischeck desc";
	}else{
		//note 默认排序
		$sortway = '1';
		
		$order_by = " order by province<>'{$order_province}',city<>'{$order_city}',city desc,province desc,s_cid asc,pic_num desc,images_ischeck desc";
	}
	
	
    
	//note 普通会员搜索结果还是按照以前的排序，高级、钻石会员搜索结果按照本站注册的排序
//	if($user_arr["s_cid"]=="2"){
//		$order_by .= ", s_cid asc, birthyear desc, pic_num desc";
//	}else{
//		$order_by .= ", s_cid asc, birthyear desc, pic_num desc"; //城市之星靠前	
//	}

    $is_data = true;
    $total2 = 0;
    
    
    if (!empty($user_list)) {
    	
    	//过滤黑名单BEGIN
    	$uid_arr=explode(',',$user_list);
       
        $uid=array();
        foreach ($uid_arr as $v){
            $sql = "select id from web_members_blacklist where uid={$v}";
            $result=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql); 
            if(empty($result['id'])){
                $uid[]= $v;
            }
        }
        $user_list=implode(',',$uid);
        //===END
    	
        //$arr = explode(",", $user_list);
        $total = $total2 = count($uid);

        $total_pages = ceil($total / $pagesize);
        $page = min($page, $total_pages);
        //note limit查询开始位置
		if($gender == 0) { 
			$dbsuf = "_man";
		} else {
			$dbsuf = "_women";
		}
        $start = ($page - 1) * $pagesize;
		$user = array();
		
		
		//$substr_user=substr($user_list,)
		$userArr=explode(',',$user_list);
		$userListArr=array_slice($userArr,$start,$pagesize);
		$userListChild=implode(',',$userListArr);
		
        //$sql="select * from `{$dbTablePre}membersfastsort{$dbsuf}` where uid in ({$user_list})   LIMIT $start,$pagesize";//$order_by
        if($sortway==1){
           $sql="select * from `{$dbTablePre}members_search` where uid in ({$userListChild}) order  by  find_in_set(uid, '$userListChild')";//   LIMIT $start,$pagesize";//$order_by
        }elseif($sortway==2 || $sortway==3){
           $sql="select * from `{$dbTablePre}members_search` where uid in ({$user_list})  $order_by  LIMIT $start,$pagesize";
        }
        
        //echo $sql;
		$user = $_MooClass['MooMySQL']->getAll($sql);

        if ($total != 0) {
            if ($page == 1 && !empty($user_list2)) {
                $limit = $pagesize - $total2;
				$user1 = array();
                $user1 = $_MooClass['MooMySQL']->getAll("select * from `{$dbTablePre}members_search` where uid in ({$user_list2})    limit 0 , $limit");//$order_by
            }
        }
    } elseif (!empty($user_list2)) {
        $no_data = true;
        //$arr = explode(",", $user_list2);
        
        //过滤黑名单BEGIN
        $uid_arr=explode(',',$user_list2);
       
        $uid=array();
        foreach ($uid_arr as $v){
            $sql = "select id from web_members_blacklist where uid={$v}";
            $result=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql); 
            if(empty($result['id'])){
                $uid[]= $v;
            }
        }
        $user_list2=implode(',',$uid);
        //===END
        
        //$total = count($arr);
        $total=count($uid);

        $total_pages = ceil($total / $pagesize);
        $page = min($page, $total_pages);
        //note limit查询开始位置
        $start = ($page - 1) * $pagesize;
		$user1 = array();
		
        if($gender == 0) { 
            $dbsuf = "_man";
        } else {
            $dbsuf = "_women";
        }
        
        $userArr=explode(',',$user_list2);
		$userListArr=array_slice($userArr,$start,$pagesize);
		$userListChild=implode(',',$userListArr);
		
		//$sql="select * from `{$dbTablePre}membersfastsort{$dbsuf}` where uid in ({$user_list2})   limit $start,$pagesize";//$order_by
		if($sortway==1){
		   $sql="select * from `{$dbTablePre}members_search` where uid in ({$userListChild}) order  by  find_in_set(uid, '$userListChild')";
		}elseif($sortway==2 || $sortway==3){
		   $sql="select * from `{$dbTablePre}members_search` where uid in ({$user_list2}) $order_by  limit $start,$pagesize";//
		}
		
        $user1 = $_MooClass['MooMySQL']->getAll($sql);
    }
    
    //print_r($user);
	//会员基本信息补充
	if($user) {
		$user = userbasicadd($user);
	}
	if($user1) {
		$user1 = userbasicadd($user1);
	}
    //note 找不到匹配的结果返回单独提示页面
    if (empty($user) && empty($user1)) {
        include MooTemplate('public/search_error', 'module');
        exit;
    }


    //note 记录婚姻状况
    $marriageArr = array();
    if ($marriage != 0) {
        if (strlen($marriage) == 1) {
            $marriageArr[] = $marriage;
        } else {
            $marriageArr = explode(',', $marriage);
        }
    }
//	$marriageCount = count($marriageArr);
//	print_r($marriageArr);die;
    //note 记录月收入
    $salaryArr = array();
    if ($salary != 0) {
        if (strlen($salary) == 1) {
            $salaryArr[] = $salary;
        } else {
            $salaryArr = explode(',', $salary);
        }
    }
//	echo $salaryArr;die;
    //note 记录教育情况
    $educationArr = array();
    if ($education != 0) {
        if (strlen($education) == 1) {
            $educationArr[] = $education;
        } else {
            $educationArr = explode(',', $education);
        }
    }

    //note 记录住房情况
    $houseArr = array();
    if ($house != 0) {
        if (strlen($house) == 1) {
            $houseArr[] = $house;
        } else {
            $houseArr = explode(',', $house);
        }
    }

    //note 记录是否购车
    $vehicleArr = array();
    if ($vehicle != 0) {
        if (strlen($vehicle) == 1) {
            $vehicleArr[] = $vehicle;
        } else {
            $vehicleArr = explode(',', $vehicle);
        }
    }

    //note 选择不同的显示模式
	if ($_GET['condition'] == '2') {
		$condition = '2';
		$htm = 'search_page';
	} else if ($_GET['condition'] == '3') {
		$condition = '3';
		$htm = 'search_photo';
	} else {
		$condition = '2';
		$htm = 'search_page';
	}
    if($_GET['debug']){
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
    	//$sql_acquisition="select m.uid as uid FROM {$dbTablePre}membersfastsort_women m" . $fast_sql . " and m.usertype=3   {$sort}   limit 300";
    	//$sql_acquisition=strtr($sql_acquisition,"/\/\/\","");
        //echo '<hr/>';
    	var_dump($sql_acquisition);
        //str_replace($sql_acquisition,"/\/\/\",'');
      	//str_res
    	//$sql_acquisition
    	//echo $sql_acquisition;
    	//$result_acquisition=$_MooClass['MooMySQL']->getAll($sql_acquisition);
    	//print_r($result_acquisition);
    }
        
//        $user_acquisition=array();
//        foreach($result_acquisition as $k=>$u){
//          $k=2*$k+1;
//          $user_acquisition[$k]=$u['uid'];
//        }   
	include MooTemplate('public/'.$htm, 'module');
}*/

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

/*
 * 显示从缓存中搜索出的结果////////////////////////////////////////
 * 高级增加、删除的搜索条件
 */
//function high_search_index($search_id, $gender, $age_start, $age_end, $work_province, $work_city, $marriage=0, $salary=0, $education=0, $height1, $height2, $weight1, $weight2, $body=0, $smoking=0, $drinking=0, $occupation=0, $house=0, $vehicle=0, $corptype=0, $children=0, $wantchildren=0, $home_townprovince, $home_towncity, $nation=0, $animalyear=0, $constellation=0, $bloodtype=0, $religion=0, $family=0, $language=0, $photo='1', $page='1',$user_list='1') {
//    global $_MooClass, $dbTablePre;
//    global $user_arr, $userid,$last_login_time;
//    global $_MooCookie;
////	print_r($_MooCookie);die;
//    $returnurl = 'index.php?' . $_SERVER['QUERY_STRING']; //返回的url
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
//    //	note 获得第几页
//    $page = max(1, intval($_GET['page']));
//
//    //note limit查询开始位置
//    $start = ($page - 1) * $pagesize;
//
//    $search_id = intval($search_id);
//	if($user_list == '1'){
//		$sql = "select uid_list from `{$dbTablePre}member_tmp` where `id`='$search_id'";
//		$ret_search = $_MooClass['MooMySQL']->getOne($sql);
//	
//		if (!$ret_search) {
//			MooMessage("您指定的搜索不存在或已过期", "index.php?n=search", '03');
//		}
//	
//		$user_list = $ret_search['uid_list'];
//	}
//
//    $user_list2 = '';
//    if (strpos($ret_search['uid_list'], '|') !== false) {
//        $user_list_arr = explode('|', $ret_search['uid_list']);
//        $user_list = $user_list_arr[0];
//        $user_list2 = $user_list_arr[1];
//    }
//
//    //note 城市之星靠前
//// $order_by = ' order by city_star desc, (s_cid=20) * bgtime desc , birthyear desc';
//	$order_by = ' order by city_star desc ';
//
//	/*这里是排序方式*/
//	$sortway = '1';
//	if($_GET['sortway'] == '1'){
//		//note 默认排序
//		$sortway = '1';
//		//note sql语句
//		$order_by = " order by city<>'{$work_city}',city desc,s_cid asc,pic_num desc,city_star desc,images_ischeck desc ";
//	}elseif($_GET['sortway'] == '2'){
//		//note 最新注册
//		$sortway = '2';
//		$order_by = " order by city<>'{$work_city}',city desc,s_cid asc,pic_num desc,regdate desc ,images_ischeck desc";
//	}elseif($_GET['sortway'] == '3'){
//		//note 诚信等级
//		$sortway = '3';
//		$order_by = " order by city<>'{$work_city}',city desc,s_cid asc,pic_num desc,certification desc ,images_ischeck desc";
//	}else{
//		//note 默认排序
//		$sortway = '1';
//		$order_by = " order by city<>'{$work_city}',city desc,s_cid asc,pic_num desc,city_star desc ,images_ischeck desc";
//	}
//
//	//note 普通会员搜索结果还是按照以前的排序，高级、钻石会员搜索结果按照本站注册的排序
//	/*if($user_arr["s_cid"]=="2"){
//		$order_by .= ", s_cid asc, birthyear desc, pic_num desc";
//	}else{
//		$order_by .= ", s_cid asc,usertype asc,birthyear desc,pic_num desc"; //城市之星靠前	
//	}*/
//
//    $is_data = true;
//    $total2 = 0;
//    if (!empty($user_list)) {
//        //$arr = explode(",", $user_list);
//        
//    	//过滤黑名单BEGIN
//        $uid_arr = explode(",", $user_list);
//        $uid=array();
//        foreach ($uid_arr as $v){
//            $sql = "select id from web_members_blacklist where uid={$v}";
//            $result=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql); 
//            if(empty($result['id'])){
//                $uid[]= $v;
//            }
//        }
//
//        $user_list=implode(',',$uid);
//        //===END
//        
//        $total = $total2 = count($uid);
//        
//        $total_pages = ceil($total / $pagesize);
//        $page = min($page, $total_pages);
//        //note limit查询开始位置
//		if($gender == 0) { 
//			$dbsuf = "_man";
//		} else {
//			$dbsuf = "_women";
//		}
//        $start = ($page - 1) * $pagesize;
//        $user = $_MooClass['MooMySQL']->getAll("select * from `{$dbTablePre}members_search` where uid in ({$user_list}) $order_by  LIMIT $start,$pagesize");
////		die("select * from `{$dbTablePre}members` where uid in ({$user_list}) $order_by  LIMIT $start,$pagesize");
//        if ($total != 0) {
//            if ($page == 1 && !empty($user_list2)) {
//                $limit = $pagesize - $total2;
//                $user1 = $_MooClass['MooMySQL']->getAll("select * from `{$dbTablePre}members_search` where uid in ({$user_list2}) $order_by limit 0 , $limit");
//            }
//        }
//    } elseif (!empty($user_list2)) {
//        $no_data = true;
//        //$arr = explode(",", $user_list2);
//        
//        //过滤黑名单BEGIN
//        $uid_arr = explode(",", $user_list2);
//        $uid=array();
//        foreach ($uid_arr as $v){
//            $sql = "select id from web_members_blacklist where uid={$v}";
//            $result=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql); 
//            if(empty($result['id'])){
//                $uid[]= $v;
//            }
//        }
//
//        $user_list2=implode(',',$uid);
//        //===END
//        
//        $total = count($uid);
//          
//        $total_pages = ceil($total / $pagesize);
//        $page = min($page, $total_pages);
//        
//        if($gender == 0) { 
//            $dbsuf = "_man";
//        } else {
//            $dbsuf = "_women";
//        }
//        
//        //note limit查询开始位置
//        $start = ($page - 1) * $pagesize;
//        $user1 = $_MooClass['MooMySQL']->getAll("select * from `{$dbTablePre}members_search` where uid in ({$user_list2}) $order_by limit $start,$pagesize");
//    };
//	//用户基本资料补充
//	if($user) {
//		$user = userbasicadd($user);
//	}
//	if($user1) {
//		$user1 = userbasicadd($user1);
//	}
//    //note 找不到匹配的结果返回单独提示页面
//    if (empty($user) && empty($user1)) {
//        include MooTemplate('public/search_error', 'module');
//        exit;
//    }
//
//    //note 民族1
//    $nationArr = array();
//    if ($nation != 0) {
//        if (strlen($nation) == 1) {
//            $nationArr[] = $nation;
//        } else {
//            $nationArr = explode(',', $nation);
//        }
//    }
//
//    //note 记录婚姻状况
//    $marriageArr = array();
//    if ($marriage != 0) {
//        if (strlen($marriage) == 1) {
//            $marriageArr[] = $marriage;
//        } else {
//            $marriageArr = explode(',', $marriage);
//        }
//    }
//
//    //note 记录月收入
//    $salaryArr = array();
//    if ($salary != 0) {
//        if (strlen($salary) == 1) {
//            $salaryArr[] = $salary;
//        } else {
//            $salaryArr = explode(',', $salary);
//        }
//    }
//
//    //note 记录教育情况
//    $educationArr = array();
//    if ($education != 0) {
//        if (strlen($education) == 1) {
//            $educationArr[] = $_MooCookie['education'];
//        } else {
//            $educationArr = explode(',', $education);
//        }
//    }
//
//    //note 记录是否吸烟
//    $smokingArr = array();
//    if ($smoking != 0) {
//        if (strlen($smoking) == 1) {
//            $smokingArr[] = $smoking;
//        } else {
//            $smokingArr = explode(',', $smoking);
//        }
//    }
//
//    //note 记录是否喝酒
//    $drinkingArr = array();
//    if ($drinking != 0) {
//        if (strlen($drinking) == 1) {
//            $drinkingArr[] = $drinking;
//        } else {
//            $drinkingArr = explode(',', $drinking);
//        }
//    }
//
//    //note 记录从事职业
//    $occupationArr = array();
//    if ($occupation != 0) {
//        if (strlen($occupation) == 1 || strlen($occupation) == 2) {
//            $occupationArr[] = $occupation;
//        } else {
//            $occupationArr = explode(',', $occupation);
//        }
//    }
//
//    //note 记录住房情况
//    $houseArr = array();
//    if ($house != 0) {
//        if (strlen($house) == 1) {
//            $houseArr[] = $house;
//        } else {
//            $houseArr = explode(',', $house);
//        }
//    }
//
//    //note 记录是否购车
//    $vehicleArr = array();
//    if ($vehicle != 0) {
//        if (strlen($vehicle) == 1) {
//            $vehicleArr[] = $vehicle;
//        } else {
//            $vehicleArr = explode(',', $vehicle);
//        }
//    }
//
//    //note 记录公司类型
//    $corptypeArr = array();
//    if ($corptype != 0) {
//        if (strlen($corptype) == 1) {
//            $corptypeArr[] = $corptype;
//        } else {
//            $corptypeArr = explode(',', $corptype);
//        }
//    }
//
//    //note 记录是否有孩子
//    $childrenArr = array();
//    if ($children != 0) {
//        if (strlen($children) == 1) {
//            $childrenArr[] = $children;
//        } else {
//            $childrenArr = explode(',', $children);
//        }
//    }
//
//    //note 记录是否想要孩子
//    $wantchildrenArr = array();
//    if ($wantchildren != 0) {
//        if (strlen($wantchildren) == 1) {
//            $wantchildrenArr[] = $wantchildren;
//        } else {
//            $wantchildrenArr = explode(',', $wantchildren);
//        }
//    }
//
//    //note 记录体型
//    $bodyArr = array();
//    if ($body != 0) {
//        if (strlen($body) == 1) {
//            $bodyArr[] = $body;
//        } else {
//            $bodyArr = explode(',', $body);
//        }
//    }
//
//    //note 记录生肖
//    $animalyearArr = array();
//    if ($animalyear != 0) {
//        if (strlen($animalyear) == 1 || strlen($animalyear) == 2) {
//            $animalyearArr[] = $animalyear;
//        } else {
//            $animalyearArr = explode(',', $animalyear);
//        }
//    }
//
//    //note 记录星座
//    $constellationArr = array();
//    if ($constellation != 0) {
//        if (strlen($constellation) == 1 || strlen($constellation) == 2) {
//            $constellationArr[] = $constellation;
//        } else {
//            $constellationArr = explode(',', $constellation);
//        }
//    }
//
//    //note 记录血型
//    $bloodtypeArr = array();
//    if ($bloodtype != 0) {
//        if (strlen($bloodtype) == 1) {
//            $bloodtypeArr[] = $bloodtype;
//        } else {
//            $bloodtypeArr = explode(',', $bloodtype);
//        }
//    }
//
//    //note 记录信仰
//    $religionArr = array();
//    if ($religion != 0) {
//        if (strlen($religion) == 1) {
//            $religionArr[] = $religion;
//        } else {
//            $religionArr = explode(',', $religion);
//        }
//    }
//
//    //note 兄弟姐妹
//    $familyArr = array();
//    if ($family != 0) {
//        if (strlen($family) == 1) {
//            $familyArr[] = $family;
//        } else {
//            $familyArr = explode(',', $family);
//        }
//    }
//
//    //note 记录语言
//    $languageArr = array();
//    if ($language != 0) {
//        if (strlen($language) == 1) {
//            $languageArr[] = $language;
//        } else {
//            $languageArr = explode(',', $language);
//        }
//    }
//
//    //note 选择不同的显示模式
//	if ($_GET['condition'] == '2') {
//		$condition = '2';
//		$htm = 'search_page';
//	} else if ($_GET['condition'] == '3') {
//		$condition = '3';
//		$htm = 'search_photo';
//	} else {
//		$condition = '2';
//		$htm = 'search_page';
//	}
//	include MooTemplate('public/'.$htm, 'module');
//}

function search_cityinline(){
	global $_MooClass, $dbTablePre, $user_arr, $userid,$last_login_time;
	if(isset($user_arr['province'])) $province = $user_arr['province'];
	if(isset($user_arr['city'])) $city = $user_arr['city'];
	//页码
	$page = 1;
	if(isset($_GET['page']))
		$page = intval(max(1, $_GET['page']));
	$pagesize = 4;
	$offset = ($page-1)*$pagesize;
	
	//note sql语句
	$sql_gender = '';
	if(isset($user_arr['gender']) && $user_arr['gender'] == '0'){
		//$gender = '1';
		//$sql_gender = " and m.gender='1'";
		$index = 'members_women';
	}else{
		//$gender = '0';
		//$sql_gender = " and m.gender='0'";
		$index = 'members_man';
	}
	$cl = searchApi($index);
	//note 地区sql语句
//	$sql_area = '';
//	if($province == -1) $province = 0;
//	if($city == -1) $city = 0;
//	if($province != 0 && $city != 0){
//		$sql_area = " and m.province='$province' and m.city='$city' ";
//	}elseif($province == 0 && $city != 0){
//		$sql_area = " and m.province='$province'";
//	}elseif($province != 0 && $city == 0){
//		$sql_area = " and m.city='$city'";
//	}elseif($province == 0 && $city == 0){
//		$sql_area = '';
//	}else{
//		$sql_area = '';
//	}
	$cond = array();
	$cond[] = array('usertype','1',false);
	//获得在线用户数据
	if(is_array($province) || is_array($city)) 
		$lastvisit = array();
	else
		$lastvisit = MooUserOnlineArea($province,$city);
	//$lastvisit = array($province => array('1098212','1215687','1743145','1744119','1744358','1096623','1097401','1097732','1216122','1216529'));
	if(!is_array($lastvisit) || empty($lastvisit)) 
		MooMessage("对不起，当前没有在线的会员", "index.php?n=search");
	else{
		$lastvisit_str = '';
		if(isset($lastvisit[$province]) && !empty($lastvisit[$province]))
			$lastvisit_str = implode('|',$lastvisit[$province]);
		if(isset($lastvisit[$city]) && !empty($lastvisit[$city])){
			if($lastvisit_str)
				$lastvisit_str .= '|'.implode('|',$lastvisit[$city]);
			else 
				$lastvisit_str = implode('|',$lastvisit[$city]);
		}
		$cond[] = array('@id',$lastvisit_str,false);
	}
	if($province) $cond[] = array('province',$province,false);
	if($city) $cond[] = array('city',$city,false);
	//note 照片显示
	//$sql_photo = " and m.images_ischeck='1' and is_lock='1'";
	$cond[] = array('is_lock','1',false);
	$cond[] = array('images_ischeck','1',false);
	$sort_str = '@weight desc,s_cid desc,city_star desc,images_ischeck desc';
	
//	$timestamp = time();
//    $expiretime2 = $timestamp - 300;//过期时间

	//note 当前在线时间
//	$time = time();
//	$timemark = $time - 300;
//	$sql_time = '';

//	$sql_time = "m.lastvisit>'$timemark' ";

//	$sql = " and usertype='1'".$sql_area.$sql_photo.$sql_gender;
//	echo $sql;die;
//	$sql_md5 = md5($sql); //md5保存sql
//	$sql_search = "select id from `{$dbTablePre}member_tmp` where `condition_md5`='{$sql_md5}' and expiretime > '$expiretime2'";
//    $ret_search = $_MooClass['MooMySQL']->getOne($sql_search);
//	if($ret_search){
//		$search_id = $ret_search['id'];
//		cityinline_index($search_id);
//	}else{
	$limit = array($offset,$pagesize);
	$rs = $cl -> getResultOfReset($cond,$limit,$sort_str);
	if(isset($rs['total_found']) && $rs['total_found']){//有数据
		$total = $rs['total_found'];
		$total_pages = ceil($total/$pagesize);
		$ids = $cl -> getIds();
	}else{//没有数据降低条件
		$cond = array();
		$cond[] = array('usertype','1',false);
		$cond[] = array('is_lock','1');
		$cond[] = array('images_ischeck','1');
		$cond[] = array('@id',$lastvisit_str,false);
		$rs = $cl -> getResult($cond,$limit,$sort_str);
		if(isset($rs['total_found']) && $rs['total_found']){
			$total = $rs['total_found'];
			$total_pages = ceil($total/$pagesize);
			$ids = $cl -> getIds();
		}
	}
	
	//note 找不到匹配的结果返回单独提示页面
    if (!isset($ids) || empty($ids)) {
        include MooTemplate('public/search_error', 'module');
        exit;
    }
    
	$user_list = (is_array($ids) && !empty($ids)) ? implode(",",$ids) : '';
	if ($user_list) {
        $page = min($page, $total_pages);
        $order_by = "order by s_cid desc ,city_star desc, pic_num desc,images_ischeck desc ";
        $user = $_MooClass['MooMySQL']->getAll("select s.*,b.mainimg from `{$dbTablePre}members_search` s left join `{$dbTablePre}members_base` b on s.uid=b.uid where s.uid in ({$user_list}) $order_by");
    }
     
	if(!isset($html)) $html = '';
	if($total>4){
		$url = 'index.php?n=search&h=cityinline';
		$pagebar = pagebar($page,$total_pages,$url,$html);
	}
	include MooTemplate('public/search_cityinline', 'module');
	exit;
//		$total_count = $_MooClass['MooMySQL']->getOne("select count(*) as count from `{$dbTablePre}members` m where "."m.lastvisit>'$timemark'".$sql);
//		$total = $total_count["count"];
//		//echo $total;die;
//		if($total>=1){
//			if($total>=4){
//				$user = $_MooClass['MooMySQL']->getAll("select m.uid from `{$dbTablePre}members` m where "."m.lastvisit>'$timemark'".$sql);
//			}else{
//				$sql_province = " and usertype='1'"." and m.province='{$province}'".$sql_photo.$sql_gender;
//				$user = $_MooClass['MooMySQL']->getAll("select m.uid from `{$dbTablePre}members` m where "."m.lastvisit>'$timemark'".$sql);
//			}
//		}else{
//			$sql1 = " and usertype='1'".$sql_photo.$sql_gender;
//			$total1 = $_MooClass['MooMySQL']->getOne("select count(*) as count from `{$dbTablePre}members` m where "."m.lastvisit>'$timemark'".$sql1);
//			if($total1<1){
//				MooMessage("对不起，当前没有在线的会员", "index.php?n=search");
//			}
//			$user1 = $_MooClass['MooMySQL']->getAll("select m.uid from `{$dbTablePre}members` m where "."m.lastvisit>'$timemark'".$sql1);
//		}
//		$user_list = "";
//		if(!empty($user)){
//			foreach ($user as $v) {
//				$user_uid .= $v['uid'].',';
//			}
//			if(!empty($user_uid))
//				$user_uid = substr($user_uid,0,-1);
//			$user_list .= $user_uid;
//		}
//
//		if(!empty($user1)){
//			foreach ($user1 as $v){
//				$user_uid2 .= $v['uid'].',';
//			}
//			if(!empty($user_uid2))
//				$user_uid2 = substr($user_uid2,0,-1);
//			$user_list .= '|'.$user_uid2;
//		}
		
		//echo $user_list;die;
//		$addtime = time();
//        $expiretime = $addtime;
//        $sql = "select id from `{$dbTablePre}member_tmp` where condition_md5 = '$sql_md5'";
//        $ret = $_MooClass['MooMySQL']->getOne($sql);
//        if (isset($ret['id']) && $ret['id']) {  //有则更新
//            $search_id = $ret['id'];
//            $_MooClass['MooMySQL']->query("update `{$dbTablePre}member_tmp` set search_uid='$userid' , uid_list='$user_list', addtime='$addtime',expiretime='$expiretime' where id='$search_id'");
//        } else {    //无则插入
//            $sql = "insert into `{$dbTablePre}member_tmp` set search_uid='$userid',condition_md5='$sql_md5',uid_list='$user_list',addtime='$addtime',expiretime='$expiretime'";
//            $_MooClass['MooMySQL']->query($sql);
//            $search_id = $_MooClass['MooMySQL']->insertId();
//        }
//		cityinline_index($search_id);
//	}
}

/*
 *同城在线读取缓存
 */
//function cityinline_index($search_id){
////	echo $search_id;die;
//	global $_MooClass, $dbTablePre;
//    global $user_arr, $userid,$last_login_time;
//	$search_arr = $_MooClass['MooMySQL']->getAll("SELECT * FROM {$dbTablePre}searchbak WHERE uid = '$userid'");
//
//	$returnurl = 'index.php?' . $_SERVER['QUERY_STRING']; //返回的url
//	
//	$pagesize = 4;
//	//note 获得第几页
////	$page = empty($_GET['page']) ?  '1' : $_GET['page'];
////	$page = intval($page);
//	$page=1;
//	if(isset($_GET['page']))
//    	$page = max(1, intval($_GET['page']));
//    //note limit查询开始位置
//    $start = ($page - 1) * $pagesize;
//
//	$sql = "select uid_list from `{$dbTablePre}member_tmp` where `id`='$search_id'";
//    $ret_search = $_MooClass['MooMySQL']->getOne($sql);
////	$ret_search = array('uid_list'=>"2156376,1748956,1097433,1097570,2149007,8415090,1098631,1097887,1099282,1097563,1097534,1097185,1097340,1098183,1099323,1098411,1098986,1098300,1098299,1098519,1098192,1097741,1097914,1098935,1099238,1099024,1099170,1098701,1098662,1098256,1099041,1098109,1098163,1098177,1098406,1098394,1097253,1099306,1216581,1098090,1099145,1097187,1097249");
//
//	if(empty($ret_search)){
//		search_cityinline();
//	}else{
//		$user_list = $ret_search['uid_list'];
//		$user_list2 = '';
//		if (strpos($ret_search['uid_list'], '|') !== false) {
//			$user_list_arr = explode('|', $ret_search['uid_list']);
//			$user_list = $user_list_arr[0];
//			$user_list2 = $user_list_arr[1];
//		}
//	}
//
//	//note 照片多的和城市之星靠前
//	$order_by = "order by s_cid desc ,city_star desc, pic_num desc,images_ischeck desc ";
//	
//	$is_data = true;
//    $total2 = 0;
//	$total_pages1 = '';
//	$total_pages2 = '';
//    if (!empty($user_list)) {
//        $arr = explode(",", $user_list);
//        $total = $total2 = count($arr);
//        $total_pages1 = ceil($total / $pagesize);
//		$total_pages = $total_pages1+$total_pages2;
//        $page = min($page, $total_pages);
//        //note limit查询开始位置
//        $start = ($page - 1) * $pagesize;
//        $user = $_MooClass['MooMySQL']->getAll("select * from `{$dbTablePre}members_search` where uid in ({$user_list}) and usertype=1 $order_by  LIMIT $start,$pagesize");
//        if ($total != 0) {
//            if ($page == 1 && !empty($user_list2)) {
//                $limit = $pagesize - $total2;
//                $user1 = $_MooClass['MooMySQL']->getAll("select * from `{$dbTablePre}members_search` where uid in ({$user_list2}) and usertype=1 $order_by limit 0 , $limit");
//            }
//        }
//    } 
//	if (!empty($user_list2)) {
//        $no_data = true;
//        $arr = explode(",", $user_list2);
//        $total = count($arr);
//        $total_pages2 = ceil($total / $pagesize);
//		$total_pages = $total_pages1+$total_pages2;
//        $page = min($page, $total_pages);
//        //note limit查询开始位置
//        $start = ($page - 1) * $pagesize;
//        $user1 = $_MooClass['MooMySQL']->getAll("select * from `{$dbTablePre}members_search` where uid in ({$user_list2}) and usertype=1 $order_by limit $start,$pagesize");
//    }
//
//    //note 找不到匹配的结果返回单独提示页面
//    if (empty($user) && empty($user1)) {
//        include MooTemplate('public/search_error', 'module');
//        exit;
//    }
//
//	if(!isset($html)) $html = '';
//	if($total>4){
//		$url = 'index.php?n=search&h=cityinline';
//		$pagebar = pagebar($page,$total_pages,$url,$html);
//	}
//	include MooTemplate('public/search_cityinline', 'module');
//
//}

/* * *************************************   控制层(C)   *************************************** */
// 当定期发送邮件时debug

if (!in_array($_GET['h'], array('basic','super','tag','list','del','tagmore','quick','add_del_search','nickid','look','cityinline')) && $_GET['h'] != '') {
    if (!$userid) {
        header("location:login.html");
    }
}


if(isset($_GET['workprovince']) && isset($_GET['workcityprovince1'])){
	$temp_work_province = (int) ($_GET['workprovince'] + $_GET['workcityprovince1']);
	if (in_array($temp_work_province, array(10101201, 10101002))) {
	    //note 修正广东省深圳和广州的区域查询 2010-09-04
	    $_GET['workprovince'] = $_GET['workcityprovince1'] = 10101000;
	    $_GET['workcity2'] = $_GET['workcity'] = $_GET['workcitycity1'] = $temp_work_province;
	    //echo $temp_work_province;exit;
	}
	unset($temp_work_province);
}

//note 快速查询表单处理
$h=$_GET['h'];

switch ($h){
	case 'quick':
		
	    if (isset($_GET['quick_search'])) {
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
	    } else {
	        //note 基本搜索表单页面
	        basic_search();
	    }
	    break;
	case 'basic':
   //note	基本查询表单处理
	    if (isset($_GET['basic_search'])) {
	        //note 性别
	        $gender = isset($_GET['gender']) ? trim(MooGetGPC('gender', 'integer', 'G')) : 0;
	
	        //note 不超过年龄和不低于年龄
			$age_start = isset($_GET['age_start']) ? trim(MooGetGPC('age_start', 'integer', 'G')) : 0;
	        $age_end = isset($_GET['age_end']) ? trim(MooGetGPC('age_end', 'integer', 'G')) : 0;
	
	        //note 工作省、市
			$work_province = isset($_GET['workprovince']) ? trim(MooGetGPC('workprovince', 'integer', 'G')) : 0;
	        $work_city = isset($_GET['workcity']) ? trim(MooGetGPC('workcity', 'integer', 'G')) : 0;
	
	        //note 籍贯省市
			$home_townprovince = isset($_GET['workcityprovince1']) ? trim(MooGetGPC('workcityprovince1', 'integer', 'G')) : 0;
	        $home_towncity = isset($_GET['workcitycity1']) ? trim(MooGetGPC('workcitycity1', 'integer', 'G')) : 0;
	
	        //note 身高
	        $height1 = isset($_GET['height1']) ? trim(MooGetGPC('height1', 'integer','G')) : 0;
			$height2 = isset($_GET['height2']) ? trim(MooGetGPC('height2', 'integer','G')) : 0;
	        //note 是否搜索有照片的会员
			$photo = isset($_GET['photo']) ? trim(MooGetGPC('photo', 'integer', 'G')) : 0;
			
	        //note 结婚，学历，教育，体型 中多选 有不限特殊处理
	        $marriage = 0;
	        $salary = 0;
	        $education = 0;
	        if(isset($_GET['marriage'])) $marriage = $_GET['marriage'] == '' ? 0 : $_GET['marriage'];
	        if(isset($_GET['salary'])) $salary = $_GET['salary'] == '' ? 0 : $_GET['salary'];
	        if(isset($_GET['education'])) $education = $_GET['education'] == '' ? 0 : $_GET['education'];
			
	        if($work_province == -1) $work_province = 0;
	        elseif($work_province == -2) $work_province = 2;
	        if($work_city == -1) $work_city = 0;
	        if($marriage == -1) $marriage = 0;
	        if($salary == -1) $salary = 0;
	        if($education == -1) $education = 0;
	        if($height1 == -1) $height1 = 0;
	        if($height2 == -1) $height2 = 0;
	        if($photo == -1) $photo = 0;
			//echo "性别".$gender, "年龄".$age_start, $age_end,"地区".$work_province, $work_city, "婚姻".$marriage, "薪水".$salary, $education, $height1, $height2, $photo;die;
			/**if(isset($_GET['searchid'])){
				$search_id = trim(MooGetGPC('searchid', 'integer', 'G'));
				search_index($search_id, $gender, $age_start, $age_end, $work_province, $work_city, $photo, $marriage, $salary, $education, $height1, $height2);
				exit;
			}*/
	        //note 调用基本处理功能函数
	        basic_search_page($gender, $age_start, $age_end, $work_province, $work_city, $marriage, $salary, $education, $height1, $height2, $photo);
	    } else {
	        //note 显示基本搜索页面
	        basic_search();
	    }
	    break;
	case 'super':
   //note	高级查询表单处理

	    if (isset($_GET['advance_search'])) {
	        //note get过来的值  匹配筛选是checkbox的
	        $str = '';
	        $str = $_SERVER['QUERY_STRING'];
	
	        $smoking = '';
	        if (isset($_GET['ismok'])) {
	            preg_match_all('/ismok=(\d*)/i', $str, $ismok);
	            $smoking = $ismok[1][0] == '' ? 0 : $ismok[1];
	        }
	        if (empty($smoking))
	            $smoking = 0;
	
	        $drinking = '';
	        if (isset($_GET['idrink'])) {
	            preg_match_all('/idrink=(\d*)/i', $str, $idrink);
	            $drinking = $idrink[1][0] == '' ? 0 : $idrink[1];
	        }
	        if (empty($drinking))
	            $drinking = 0;
	
	        $children = '';
	        if (isset($_GET['child'])) {
	            preg_match_all('/child=(\d*)/i', $str, $child);
	            $children = $child[1][0] == '' ? 0 : $child[1];
	        }
	        if (empty($children))
	            $children = 0;
	
	        $wantchildren = '';
	        if (isset($_GET['wtchildren'])) {
	            preg_match_all('/wtchildren=(\d*)/i', $str, $wtchild);
	            $wantchildren = $wtchild[1][0] == '' ? 0 : $wtchild[1];
	        }
	        if (empty($wantchildren))
	            $wantchildren = 0;
	
	        $body = '';
	        if (isset($_GET['body'])) {
	            preg_match_all('/body=(\d*)/i', $str, $bo);
	            $body = $bo[1][0] == '' ? 0 : $bo[1];
	        }
	        if (empty($body))
	            $body = 0;
	
	        $animalyear = '';
	        if (isset($_GET['animals'])) {
	            preg_match_all('/animals=(\d*)/i', $str, $ani);
	            $animalyear = $ani[1][0] == '' ? 0 : $ani[1];
	        }
	        if (empty($animalyear))
	            $animalyear = 0;
	            
	        //note 星座
	        $constellation = '';
	        if (isset($_GET['constellation'])) {
	            preg_match_all('/constellation=(\d*)/i', $str, $const);
	            $constellation = $const[1][0] == '' ? 0 : $const[1];
	        }
	        if (empty($constellation))
	            $constellation = 0;
	
	        $bloodtype = '';
	        if (isset($_GET['bty'])) {
	            preg_match_all('/bty=(\d*)/i', $str, $bty);
	            $bloodtype = $bty[1][0] == '' ? 0 : $bty[1];
	        }
	        if (empty($bloodtype))
	            $bloodtype = 0;

	        //note 婚姻
	        $marriage = '';
	        if (isset($_GET['marriage'])) {
	            preg_match_all('/marriage=(\d*)/i', $str, $marr);
	            $marriage = $marr[1][0] == '' ? 0 : $marr[1];
	        }
	        if (empty($marriage))
	            $marriage = 0;

	        //note 教育程度
	        $education = '';
	        if (isset($_GET['education'])) {
	            preg_match_all('/education=(\d*)/i', $str, $edu);
	            $education = $edu[1][0] == '' ? 0 : $edu[1];
	        }
	        if (empty($education))
	            $education = 0;

	        //note 月收入
	        $salary = '';
	        if (isset($_GET['salary'])) {
	            preg_match_all('/salary=(\d*)/i', $str, $sal);
	            $salary = $sal[1][0] == '' ? 0 : $sal[1];
	        }
	        if (empty($salary))
	            $salary = 0;

	        //note 从事职业
	        $occupation = '';
	        if (isset($_GET['occupation'])) {
	            preg_match_all('/occupation=(\d*)/i', $str, $occu);
	            $occupation = $occu[1][0] == '' ? 0 : $occu[1];
	        }
	        if (empty($occupation))
	            $occupation = 0;

	        //note 住房情况
	        $house = '';
	        if (isset($_GET['house'])) {
	            preg_match_all('/house=(\d*)/i', $str, $h);
	            $house = $h[1][0] == '' ? 0 : $h[1];
	        }
	        if (empty($house))
	            $house = 0;
	//		print_r($house);
	//		echo "住房情况<br />";
	        //note 是否购车
	        $vehicle = '';
	        if (isset($_GET['vehicle'])) {
	            preg_match_all('/vehicle=(\d*)/i', $str, $v);
	            $vehicle = $v[1][0] == '' ? 0 : $v[1];
	        }
	        if (empty($vehicle))
	            $vehicle = 0;
	//		print_r($vehicle);
	//		echo "是否购车<br />";
	        //note 公司类别
	        $corptype = '';
	        if (isset($_GET['corptp'])) {
	            preg_match_all('/corptp=(\d*)/i', $str, $corptp);
	            $corptype = $corptp[1][0] == '' ? 0 : $corptp[1];
	        }
	        if (empty($corptype))
	            $corptype = 0;
	//		print_r($corptype);
	//		echo "公司类别<br />";
	        //note 信仰
	        $religion = '';
	        if (isset($_GET['belief'])) {
	            preg_match_all('/belief=(\d*)/i', $str, $belief);
	            $religion = $belief[1][0] == '' ? 0 : $belief[1];
	        }
	        if (empty($religion))
	            $religion = 0;
	//		print_r($religion);
	//		echo "信仰<br />";
	        //note 语言能力
	        $language = '';
	        if (isset($_GET['tonguegift'])) {
	            preg_match_all('/tonguegift=(\d*)/i', $str, $tongue);
	            $language = $tongue[1][0] == '' ? 0 : $tongue[1];
	        }
	        if (empty($language))
	            $language = 0;
	//		print_r($language);
	//		echo "语言能力<br />";
	        //note 兄弟姐妹
	        $family = '';
	        if (isset($_GET['family'])) {
	            preg_match_all('/family=(\d*)/i', $str, $f);
	            $family = $f[1][0] == '' ? 0 : $f[1];
	        }
	        if (empty($family))
	            $family = 0;
	//		print_r($family);
	//		echo "兄弟姐妹<br />";
	
			//note 性别
	        $gender = isset($_GET['gender']) ? trim(MooGetGPC('gender', 'integer', 'G')) : 0;
	
	        //note 不超过年龄和不低于年龄
			$age_start = isset($_GET['age_start']) ? trim(MooGetGPC('age_start', 'integer', 'G')) : 0;
	        $age_end = isset($_GET['age_end']) ? trim(MooGetGPC('age_end', 'integer', 'G')) : 0;
	
	        //note 工作省、市
			$work_province = isset($_GET['workprovince']) ? trim(MooGetGPC('workprovince', 'integer', 'G')) : 0;
	        $work_city = isset($_GET['workcity']) ? trim(MooGetGPC('workcity', 'integer', 'G')) : 0;
			
			//note 修正广东省深圳和广州的区域查询
			if(in_array($work_province, array(10101201,10101002))) {
				$work_city = $work_province;
				$work_province = 10101000;
			}
	        //note 籍贯省市
			$home_townprovince = isset($_GET['workcityprovince1']) ? trim(MooGetGPC('workcityprovince1', 'integer', 'G')) : 0;
	        $home_towncity = isset($_GET['workcitycity1']) ? trim(MooGetGPC('workcitycity1', 'integer', 'G')) : 0;
			
			//note 修正广东省深圳和广州的区域查询
			if(in_array($home_townprovince, array(10101201,10101002))) {
				$home_towncity = $home_townprovince;
				$home_townprovince = 10101000;
			}
			
	        //note 身高
	        $height1 = isset($_GET['height1']) ? trim(MooGetGPC('height1', 'integer','G')) : 0;
			$height2 = isset($_GET['height2']) ? trim(MooGetGPC('height2', 'integer','G')) : 0;
			//note 不超过和不低于体重
	        $weight1 = isset($_GET['weight1']) ? trim(MooGetGPC('weight1', 'integer','G')) : 0;
	        $weight2 = isset($_GET['weight2']) ? trim(MooGetGPC('weight2', 'integer','G')) : 0;
	
	        //note 是否搜索有照片的会员
			$photo = isset($_GET['photo']) ? trim(MooGetGPC('photo', 'integer', 'G')) : 0;
	
	//		echo "性别$gender<br />年龄$age_start--$age_end<br />工作省市$work_province--$work_city<br />是否显示相片$photo<br />身高$height1--$height2<br />体重$weight1--$weight2<br />";
	
	        //note 民族
	        $nation = isset($_GET['stock']) ? trim(MooGetGPC('stock', 'integer', 'G')) : 0;
	//		echo "籍贯省市$home_townprovince--$home_towncity<br />民族$nation";die;
	        //note 保存搜索条件名称
	        $searchname = isset($_GET['searchname']) ? trim(MooGetGPC('searchname', 'string', 'G')) : '';
	
	        //note 以前的搜索id
	        $sid = isset($_GET['sid']) ? trim(MooGetGPC('sid', 'integer', 'G')) : '';
	//        $condition = isset($_GET['condition']) ? trim(MooGetGPC('condition', 'integer', 'G')) : '2';
	
	        //note 是否确定保存搜索条件
	        $issavesearchname = isset($_GET['issavesearchname']) ? MooGetGPC('issavesearchname', 'integer','G') : '';
			//note 是否定期存到邮箱
	        $is_commend = MooGetGPC('is_commend', 'integer','G');
	
	        if ($searchname && $issavesearchname) {
	            $currenturl = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	            $currenturl2 = preg_replace("/(&issavesearchname=\d+)/","", $currenturl);
				$countname = count_string_len($searchname);
				if($countname>25){
					MooMessage('对不起！您输入的搜索条件名过长！',"index.php?n=search&h=super");	
				}
	            //note 保存的搜索条件新增加一条记录到web_searchbk表或者根据搜索条件id更新表某一条记录
	            condition_in_update($gender, $age_start, $age_end, $work_province, $work_city, $marriage, $salary, $education, $height1, $height2, $weight1, $weight2, $body, $smoking, $drinking, $occupation, $house, $vehicle, $corptype, $children, $wantchildren, $home_townprovince, $home_towncity, $nation, $animalyear, $constellation, $bloodtype, $religion, $family, $language,$photo, $sid,$is_commend,$issavesearchname,$searchname,$currenturl);
	//			exit;
	        }
	        
	        if($age_start == -1) $age_start = 0;
	        if($age_end == -1) $age_end = 0;
	        if($work_province == -1) $work_province = 0;
	        elseif($work_province == -2) $work_province = 2;
	        if($work_city == -1) $work_city = 0;
	        if($marriage == -1) $marriage = 0;
	        if($salary == -1) $salary = 0;
	        if($education == -1) $education = 0;
	        if($height1 == -1) $height1 = 0;
	        if($height2 == -1) $height2 = 0;
	        if($weight1 == -1) $weight1 = 0;
	        if($weight2 == -1) $weight2 = 0;
	        if($body == -1) $body = 0;
	        if($smoking == -1) $smoking = 0;
	        if($drinking == -1) $drinking = 0;
	        if($occupation == -1) $occupation = 0;
	        if($house == -1) $house = 0;
	        if($vehicle == -1) $vehicle = 0;
	        if($corptype == -1) $corptype = 0;
	        if($children == -1) $children = 0;
	        if($wantchildren == -1) $wantchildren = 0;
	        if($home_townprovince == -1) $home_townprovince = 0;
	        elseif($home_townprovince == -2) $home_townprovince = 2;
	        if($home_towncity == -1) $home_towncity = 0;
	        if($nation == -1) $nation = 0;
	        if($animalyear == -1) $animalyear = 0;
	        if($constellation == -1) $constellation = 0;
	        if($bloodtype == -1) $bloodtype = 0;
	        if($religion == -1) $religion = 0;
	        if($family == -1) $family = 0;
	        if($language == -1) $language = 0;
	        if($photo == -1) $photo = 0;

	        //note 条件查询					
	        advance_search_page($gender, $age_start, $age_end, $work_province, $work_city, $marriage, $salary, $education, $height1, $height2, $weight1, $weight2, $body, $smoking, $drinking, $occupation, $house, $vehicle, $corptype, $children, $wantchildren, $home_townprovince, $home_towncity, $nation, $animalyear, $constellation, $bloodtype, $religion, $family, $language, $photo);
	    } else {
	        advance_search();
	    }
	    break;
	case 'add_del_search':
    //note 增加、删除的搜索条件
	
		//note 性别
		$gender = isset($_GET['gender']) ? trim(MooGetGPC('gender', 'integer', 'G')) : 0;
	
		//note 不超过年龄和不低于年龄
		$age_start = isset($_GET['age_start']) ? trim(MooGetGPC('age_start', 'integer', 'G')) : 0;
		$age_end = isset($_GET['age_end']) ? trim(MooGetGPC('age_end', 'integer', 'G')) : 0;
	
		//note 工作省、市
		$work_province = isset($_GET['workprovince']) ? trim(MooGetGPC('workprovince', 'integer', 'G')) : 0;
		$work_city = isset($_GET['workcity']) ? trim(MooGetGPC('workcity', 'integer', 'G')) : 0;
		//note 修正广东省深圳和广州的区域查询
		if(in_array($work_province, array(10101201,10101002))) {
			$work_city = $work_province;
			$work_province = 10101000;
		}
	
		//note 籍贯省市
		$home_townprovince = isset($_GET['workcityprovince1']) ? trim(MooGetGPC('workcityprovince1', 'integer', 'G')) : 0;
		$home_towncity = isset($_GET['workcitycity1']) ? trim(MooGetGPC('workcitycity1', 'integer', 'G')) : 0;
		//note 修正广东省深圳和广州的区域查询
		if(in_array($home_townprovince, array(10101201,10101002))) {
			$home_towncity = $home_townprovince;
			$home_townprovince = 10101000;
		}
	
		//note 身高
		$height1 = isset($_GET['height1']) ? trim(MooGetGPC('height1', 'integer','G')) : 0;
		$height2 = isset($_GET['height2']) ? trim(MooGetGPC('height2', 'integer','G')) : 0;
		//note 不超过和不低于体重
		$weight1 = isset($_GET['weight1']) ? trim(MooGetGPC('weight1', 'integer','G')) : 0;
		$weight2 = isset($_GET['weight2']) ? trim(MooGetGPC('weight2', 'integer','G')) : 0;
	
		//note 是否搜索有照片的会员
		$photo = isset($_GET['photo']) ? trim(MooGetGPC('photo', 'integer', 'G')) : 0;
	
	    //note 婚姻
	    $marriage = array();
	    $marri = isset($_GET['Marriage']) ? trim(MooGetGPC('Marriage', 'string', 'G')) : 0;
	    if ($marri == 0){
	        $marriage = 0;
		}else if(strlen($marri) == '1'){
			$marriage[] = $marri;
		}else{
	        $marriage = explode(',', $marri);
		}
	//	print_r($marriage);
	//	echo "婚姻<br />";
	
	    //note 月收入
	    $salary = array();
	    $sal = isset($_GET['Salary']) ? trim(MooGetGPC('Salary', 'string', 'G')) : 0;
	    if ($sal == 0){
	        $salary = 0;
		}else if(strlen($sal) == '1'){
			$salary[] = $sal;
		}else{
	        $salary = explode(',', $sal);
		}
	//	print_r($salary);
	//	echo "月收入<br />";
	
	    //note 教育
	    $education = array();
	    $edu = isset($_GET['Education']) ? trim(MooGetGPC('Education', 'string', 'G')) : 0;
	    if ($edu == 0){
	        $education = 0;
		}else if(strlen($edu) == '1'){
			$education[] = $edu;
		}else{
	        $education = explode(',', $edu);
		}
	//	print_r($education);
	//	echo "教育<br />";
	
	    //note 体型
	    $body = array();
	    $bo = isset($_GET['Body']) ? trim(MooGetGPC('Body', 'string', 'G')) : 0;
	    if ($bo == 0){
	        $body = 0;
		}else if(strlen($bo) == '1'){
			$body[] = $bo;	
		}else{
	        $body = explode(',', $bo);
		}
	//	print_r($body);
	//	echo "体型<br />";
	
	    //note 吸烟
	    $smoking = array();
	    $smo = isset($_GET['ismok']) ? trim(MooGetGPC('ismok', 'string', 'G')) : 0;
	    if ($smo == 0){
	        $smoking = 0;
		}else if(strlen($smo) == '1'){
			$smoking[] = $smo;
		}else{
	        $smoking = explode(',', $smo);
		}
	//	print_r($smoking);
	//	echo "吸烟<br />";
	
	    //note 喝酒
	    $drinking = array();
	    $drink = isset($_GET['idrink']) ? trim(MooGetGPC('idrink', 'string', 'G')) : 0;
	    if ($drink == 0){
	        $drinking = 0;
		}else if(strlen($drink) == '1'){
			$drinking[] = $drink;
		}else{
	        $drinking = explode(',', $drink);
		}
	//	print_r($drinking);
	//	echo "喝酒<br />";
	
	    //note 从事职业
	    $occupation = array();
	    $occu = isset($_GET['Occupation']) ? trim(MooGetGPC('Occupation', 'string', 'G')) : 0;
	    if ($occu == 0){
	        $occupation = 0;
		}else if(strlen($occu) =='1' || strlen($occu) == '2'){
			$occupation[] = $occu;
		}else{
	        $occupation = explode(',', $occu);
		}
	//	print_r($occupation);
	//	echo "从事职业<br />";
	
	    //note 租房情况
	    $house = array();
	    $hh = isset($_GET['House']) ? trim(MooGetGPC('House', 'string', 'G')) : 0;
	    if ($hh == 0){
	        $house = 0;
		}else if(strlen($hh) == '1'){
			$house[] = $hh;
		}else{
	        $house = explode(',', $hh);
		}
	//	print_r($house);
	//	echo "租房情况<br />";
	
	    //note 购车情况
	    $vehicle = array();
	    $vv = isset($_GET['Vehicle']) ? trim(MooGetGPC('Vehicle', 'string', 'G')) : 0;
	    if ($vv == 0){
	        $vehicle = 0;
		}else if(strlen($vv) == '1'){
			$vehicle[] = $vv;
		}else{
	        $vehicle = explode(',', $vv);
		}
	//	print_r($vehicle);
	//	echo "购车情况<br />";
	
	    //note 公司类型
	    $corptype = array();
	    $corp = isset($_GET['Corptp']) ? trim(MooGetGPC('Corptp', 'string', 'G')) : 0;
	    if ($corp == 0){
	        $corptype = 0;
		}else if(strlen($corp) == '1'){
			$corptype[] = $corp;
		}else{
	        $corptype = explode(',', $corp);
		}
	//	print_r($corptype);
	//	echo "公司类型<br />";
	
	    //note 是否有孩子
	    $children = array();
	    $child = isset($_GET['Child']) ? trim(MooGetGPC('Child', 'string', 'G')) : 0;
	    if ($child == 0){
	        $children = 0;
		}else if(strlen($child) == '1'){
			$children[] = $child;
		}else{
	        $children = explode(',', $child);
		}
	//	print_r($children);
	//	echo "是否有孩子<br />";
	
	    //note 是否想要孩子
	    $wantchildren = array();
	    $wc = isset($_GET['Wtchildren']) ? trim(MooGetGPC('Wtchildren', 'string', 'G')) : 0;
	    if ($wc == 0){
	        $wantchildren = 0;
		}else if(strlen($wc) == '1'){
			$wantchildren[] = $wc;
		}else{
	        $wantchildren = explode(',', $wc);
		}
	//	print_r($wantchildren);
	//	echo "是否想要孩子<br />";
	
	    //note 民族
	    $nation = isset($_GET['Stock']) ? trim(MooGetGPC('Stock', 'string', 'G')) : 0;
	
	    //note 生肖
	    $animalyear = array();
	    $ani = isset($_GET['Animals']) ? trim(MooGetGPC('Animals', 'string', 'G')) : 0;
	    if ($ani == 0){
	        $animalyear = 0;
		}else if(strlen($ani) == '1' || strlen($ani) == '2'){
			$animalyear[] = $ani;
		}else{
	        $animalyear = explode(',', $ani);
		}
	//	print_r($animalyear);
	//	echo "生肖<br />";
	
	    //note 星座
	    $constellation = array();
	    $cons = isset($_GET['Constellation']) ? trim(MooGetGPC('Constellation', 'string', 'G')) : 0;
	    if ($cons == 0){
	        $constellation = 0;
		}else if(strlen($cons) == '1' || strlen($cons) == '2'){
			$constellation[] = $cons;
		}else{
	        $constellation = explode(',', $cons);
		}
	//	print_r($constellation);
	//	echo "星座<br />";
	
	    //note 血型
	    $bloodtype = array();
	    $bty = isset($_GET['Bty']) ? trim(MooGetGPC('Bty', 'string', 'G')) : 0;
	    if ($bty == 0){
	        $bloodtype = 0;
		}else if(strlen($bty) == '1'){
			$bloodtype[] = $bty;
		}else{
	        $bloodtype = explode(',', $bty);
		}
	//	print_r($bloodtype);
	//	echo "血型<br />";
	
	    //note 信仰
	    $religion = array();
	    $belief = isset($_GET['Belief']) ? trim(MooGetGPC('Belief', 'string', 'G')) : 0;
	    if ($belief == 0){
	        $religion = 0;
		}else if(strlen($belief) == '1'){
			$religion[] = $belief;
		}else{
	        $religion = explode(',', $belief);
		}
	//	print_r($religion);
	//	echo "信仰<br />";
	
	    //note 兄弟姐妹
	    $family = array();
	    $fa = isset($_GET['Family']) ? trim(MooGetGPC('Family', 'string', 'G')) : 0;
	    if ($fa == 0){
	        $family = 0;
		}else if(strlen($fa) == '1'){
			$family[] = $fa;
		}else{
	        $family = explode(',', $fa);
		}
	//	print_r($family);
	//	echo "兄弟姐妹<br />";
	
	    //note 语言能力
	    $language = array();
	    $lang = isset($_GET['Tonguegift']) ? trim(MooGetGPC('Tonguegift', 'string', 'G')) : 0;
	    if ($lang == 0){
	        $language = 0;
		}else if(strlen($lang) == '1'){
			$language[] = $lang;
		}else{
	        $language = explode(',', $lang);
		}
	//	print_r($language);
	//	echo "语言能力<br />";
	
	    //note 条件查询
//		if(isset($_GET['searchid'])){
//			$search_id = trim(MooGetGPC('searchid', 'integer', 'G'));
//			high_search_index($search_id, $gender, $age_start, $age_end, $work_province, $work_city, $marri, $sal, $edu, $height1, $height2, $weight1, $weight2, $bo, $smo, $drink, $occu, $hh, $vv, $corp, $child, $wc, $home_townprovince, $home_towncity, $nation=0, $ani, $cons, $bty, $belief, $fa, $lang, $photo='1', $page='1');
//			exit;
//		}
	
		if($age_start == -1) $age_start = 0;
        if($age_end == -1) $age_end = 0;
        if($work_province == -1) $work_province = 0;
        elseif($work_province == -2) $work_province = 2;
        if($work_city == -1) $work_city = 0;
        if($marriage == -1) $marriage = 0;
        if($salary == -1) $salary = 0;
        if($education == -1) $education = 0;
        if($height1 == -1) $height1 = 0;
        if($height2 == -1) $height2 = 0;
        if($weight1 == -1) $weight1 = 0;
        if($weight2 == -1) $weight2 = 0;
        if($body == -1) $body = 0;
        if($smoking == -1) $smoking = 0;
        if($drinking == -1) $drinking = 0;
        if($occupation == -1) $occupation = 0;
        if($house == -1) $house = 0;
        if($vehicle == -1) $vehicle = 0;
        if($corptype == -1) $corptype = 0;
        if($children == -1) $children = 0;
        if($wantchildren == -1) $wantchildren = 0;
        if($home_townprovince == -1) $home_townprovince = 0;
        elseif($home_townprovince == -2) $home_townprovince = 2;
        if($home_towncity == -1) $home_towncity = 0;
        if($nation == -1) $nation = 0;
        if($animalyear == -1) $animalyear = 0;
        if($constellation == -1) $constellation = 0;
        if($bloodtype == -1) $bloodtype = 0;
        if($religion == -1) $religion = 0;
        if($family == -1) $family = 0;
        if($language == -1) $language = 0;
        if($photo == -1) $photo = 0;
	        
	    advance_search_page($gender, $age_start, $age_end, $work_province, $work_city, $marriage, $salary, $education, $height1, $height2, $weight1, $weight2, $body, $smoking, $drinking, $occupation, $house, $vehicle, $corptype, $children, $wantchildren, $home_townprovince, $home_towncity, $nation, $animalyear, $constellation, $bloodtype, $religion, $family, $language, $photo);
        break;
	case 'edit':
//note 编辑以前搜索的条件

		if(!($GLOBALS['MooUid'])){
			header("Location:login.html");
			exit;
		}
	    if ($_GET['s'] && is_numeric($_GET['s'])) {
	        $s = $_GET['s'];
	        edit_search($s);
	    } else {
	        include MooTemplate('public/search_error', 'module');
	        exit;
	    }
   		break;
	case 'list':
//note 显示以前搜索的条件

		if(!($GLOBALS['MooUid'])){
			header("Location:login.html");
			exit;
		}
	    list_search();
	    break;
    
	case 'show':
    //note 直接读取以前的搜索条件搜索
		if(!($GLOBALS['MooUid'])){
			header("Location:login.html");
			exit;
		}
	    if (isset($_GET['oldSearchCondition']) && $_GET['oldSearchCondition']) {
	        $scid = $_GET['oldSearchCondition'];
			if($scid==0){
				MooMessage("对不起，请选择保存的搜索条件", "index.php?n=search");
				exit;
			}
	        show_search($scid);
	    } else {
	        include MooTemplate('public/search_error', 'module');
	        exit;
	    }
        break;
	case 'del':
       //note 删除以前的搜索条件

		if(!($GLOBALS['MooUid'])){
			header("Location:login.html");
			exit;
		}
	    if (isset($_GET['s']) && $_GET['s'] && is_numeric($_GET['s'])) {
	        $s = $_GET['s'];
	        del_search($s);
	    } else {
	        include MooTemplate('public/search_error', 'module');
	        exit;
	    }
        break;
	case 'nickid':
        //note 昵称或者用户id查询
        nickid_search(MooGetGPC('info', 'interger', 'G'));
        break;
	case 'look':
	    //note 谁在找我搜索
		if(!($GLOBALS['MooUid'])){
			header("Location:login.html");
			exit;
		}
	    //note 做多用户session的时候，更改此处，例如$memberid = $_SESSION[memberid]
	    $memberid = $userid;
	   //	echo $userid;die;
	    look_search($memberid);
	    break;
	case 'bothbelong':
    //note 天生一对搜索

		if(!($GLOBALS['MooUid'])){
			header("Location:login.html");
			exit;
		}
	    //note 做多用户session的时候，更改此处，例如$memberid = $_SESSION[memberid]
	    $memberid = $userid;
	    bothbelong_search($memberid);
        break;
	case 'automatch': //自动匹配
		if(!($GLOBALS['MooUid'])){
            header("Location:login.html");
            exit;
        }

        automatch_search();
        break;
	case 'tag':
       //note 标签搜索
		if(isset($user_arr['gender']) && $user_arr['gender'] == '1'){
			$gender = '0';
		}else{
		    $gender = '1';
		}
//		if ($user_arr['gender'] == '1') {
//	        $gender = '0';
//	    }
//	    if ($user_arr['gender'] == '0') {
//	        $gender = '1';
//	    }
	    if ($gender !== '0' && $gender !== '1') {
	        $gender = '1';
	        $user_arr['gender'] = '0';
	    }
	    //note 标签搜索
	    if (isset($_GET['tagname']) && isset($_GET['tagid']) && $_GET['tagname'] && $_GET['tagid']) {
	        $tagname = $_GET['tagname'];
	        $sex = $_GET['s'];
	        $tagid = $_GET['tagid'];
	        tag_search($tagname, $sex, $tagid);
	    } else {
	        require MooTemplate('public/search_tag', 'module');
	    }
	    break;
   //note 更多标签搜索	
	case 'tagmore':
	    if (isset($user_arr['gender']) && $user_arr['gender'] == '1') {
	        $gender = '0';
	    }else{
	        $gender = '1';
	    }
	    //note 标签搜索
	    if (isset($_GET['tagname']) && isset($_GET['tagid']) && $_GET['tagname'] && $_GET['tagid']) {
	        $tagname = $_GET['tagname'];
	        $sex = $_GET['s'];
	        $tagid = $_GET['tagid'];
	        tag_search($tagname, $sex, $tagid);
	    } else {
	        require MooTemplate('public/search_tag_more', 'module');
	    }
        break;
	case 'cityinline':
        //note 
		if(!($GLOBALS['MooUid'])){
			header("Location:login.html");
			exit;
		}
		if($user_arr['s_cid'] == '30' || $user_arr['s_cid'] == '20' || $user_arr['s_cid'] == '10'){
			search_cityinline();
		}else{
			MooMessage("对不起，您不是高级会员，请升级为高级会员！", "index.php?n=payment",'02');
		}
        break;
	case 'test':
	    //note 测试页面	
	
	    $image = MooAutoLoad('MooImage');
	    //各个参数的意义可以看下面对应的说明
	    $image->config(array('waterMarkMinWidth' => '400', 'waterMarkMinHeight' => '300', 'waterMarkStatus' => 9));
	    $image->thumb(200, 100, '1.gif');
	    $image->waterMark();
  		break;
    default:
        basic_search();
        break;
    
}
?>
<!--comment-->
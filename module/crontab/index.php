<?php 
/**
 * 1.定时执行，发送会员邮件
 * 2.定时执行，清空搜索缓存表
 * h参数为recommd_mail，发送定期邮件会员
 * h参数为其它时，发送队列邮件
 * param参数为验证，格式为MooAuthCode(time() . "|" . MOOPHP_AUTHKEY, "ENCODE")
 */
header('Content-Type:text/html;charset=UTF-8');

function crontabRecommdMail() {
	global $_MooClass,$dbTablePre, $user_arr;
	
	$date = date("Y-m-d", strtotime("-2 day"));
	
	// 表名
	$search_table_name = $dbTablePre . "searchbak";
	$members_table_name = $dbTablePre . "members_search";
	$certification_table_name = $dbTablePre . "certification";
	$sql = "select s.scid,s.uid,s.searchsign from " . $search_table_name . " as s where s.is_commend = 1 and (s.last_send_date <= '" . $date . "' or s.last_send_date is null) limit 10";
	$list = $_MooClass['MooMySQL']->getAll($sql);
	
	if(empty($list)) return;
	//取得所有UID
	$uids = array();
	foreach($list as $k=>$tmp)
		$uids[$tmp['uid']][] = $k;

	$uids_two = $uids;
	$sql = "select uid from {$certification_table_name} where uid in (".implode(',', array_keys($uids)).") and email = 'yes'";
	if($cert = $_MooClass['MooMySQL']->getAll($sql)){
		foreach($cert as $c){
			$k = array_shift($uids_two[$c['uid']]);
			$sql = "update " . $search_table_name . " set last_send_date = '" . date("Y-m-d") . "' where scid = '" . $list[$k]["scid"] . "'";
			$_MooClass['MooMySQL']->query($sql);
			unset($list[$k]);
			if(empty($uids_two[$c['uid']])) unset($uids[$c['uid']]);		
		}
	}

	if(empty($uids)) return;
	
	$sql = "select uid,username,nickname from {$members_table_name} where uid in (".implode(',', array_keys($uids)).")";
	$members = $_MooClass['MooMySQL']->getAll($sql);
	foreach($members as $m){
		$k = $uids[$m['uid']];
		foreach($k as $k_v){
			if(empty($user_arr)) $user_arr = $m;
			getSearchCondition($list[$k_v]["scid"], $m["username"], $list[$k_v]["searchsign"]);
		}
	}
	
	
	/*
	foreach ($list as $tmp) {

			
		/*$sql = "select m.uid,m.username from " . $members_table_name . " as m inner join " . $certification_table_name . " as c on c.uid = m.uid where m.uid = '" . $tmp["uid"] . "' and c.email = 'yes'";
		*
		$sql = "select uid from {$certification_table_name} where uid = {$tmp["uid"]} and email = 'yes'";  
		$member = $_MooClass['MooMySQL']->getOne($sql);
		
		if (!empty($member)) {
			$sql = "update " . $search_table_name . " set last_send_date = '" . date("Y-m-d") . "' where scid = '" . $tmp["scid"] . "'";
			$_MooClass['MooMySQL']->query($sql);
			
			continue;
		}
		
		getSearchCondition($tmp["scid"], $member["username"], $tmp["searchsign"]);
	}
	*/
}

//
function MooSendMail_SYS($ToAddress,$ToSubject,$ToBody,$type='',$is_template = true){
	global $mailTemplateFile;
	
	//note ***********加载模板************
	if ($is_template == true) {
		if($type==''){
			$body = MooReadFileadmin($mailTemplateFile);
		}else{
			$body = MooReadFileadmin($type, false);
		}
		if($body==''){
		      $body = MooReadFileadmin($mailTemplateFile);
		}
		$body = eregi_replace("[\]",'',$body);
		//邮件时间替换
		//date_default_timezone_set ('Asia/Shanghai');
		$Time = date('Y-m-d H:i:s');
		$body = str_replace("#DATETIME#",$Time,$body);
		//note 邮件正文替换
		$body = str_replace("#BODY#",$ToBody,$body);
		//note 模板几个内部图片地址
		$body = str_replace("#siteurl#",'http://'.$_SERVER['HTTP_HOST'].'/',$body);
	} else {
		$body = $ToBody;
	}

	
	$ToAddress=explode(',',$ToAddress);
	foreach($ToAddress as $email){
		$param = array();
		$param["registration_date"] = date("Y-m-d H:i:s");
		//$param["uid"] = $uid;
		$param["mail"] = $email;
		$param["subject"] = addslashes($ToSubject);
		$param["content"] = addslashes($body);
		
		inserttable("mail_queue_sys", $param);
	}
	
	return true;
}

//定时更新采集会员的邮件已读状态
function update_mail_status(){
	global $_MooClass,$dbTablePre;
	$time=time()-3600*24*3;
	$start = 0;
	isset($_GET['s']) && $start = intval($_GET['s']);
	echo $start;
	if($start<500000){
		$sql="SELECT * FROM {$dbTablePre}services where s_time<'$time' and s_status=0 limit {$start},500";
		$email_arr=$_MooClass['MooMySQL']->getAll($sql);
		if($email_arr){
			foreach($email_arr as $email_inf){
				/*if(MOOPHP_ALLOW_FASTDB){
					$get_email_user=MooFastdbGet('members','uid',$email_inf['s_uid']);
				}else{
					$get_email_user=$_MooClass['MooMySQL']->getOne("SELECT usertype FROM {$dbTablePre}members_search where uid={$email_inf['s_uid']}");
				}*/
				if(MooMembersData($email_inf['s_uid'], 'usertype') == 3){
					$_MooClass['MooMySQL']->query("update {$dbTablePre}services set s_status=1,read_time=now() where s_id={$email_inf['s_id']}");
				}
				$start += 500;
				exit('<html><head><meta http-equiv="refresh" content="2;url=?n=crontab&param=123456&h=update_mail_status&s='.$start.'"> </head><body></body></html>');
			}
		}else{
			exit("ok");
		}
	}else{
		exit("ok");
	}
}

//note 直接查询以前的搜索条件
function getSearchCondition($s, $email, $searchsign){
	global $_MooClass,$dbTablePre,$user_arr;
	//note 获取搜索条件
	$sr = $_MooClass['MooMySQL']->getOne("select * from `{$dbTablePre}searchbak` where `scid` = '$s'");
	
	//note 以下是不同的会员信息正则转换
	$body = $sr['searchcondition'];
 	preg_match("/<gender>(.*)<\/gender>/isU",$body,$gender_arr);
 	$gender = isset($gender_arr[1]) ? $gender_arr[1] : 0;
 	
 	preg_match("/<age_start>(.*)<\/age_start>/isU",$body,$age_start_arr);
 	$age_start = isset($age_start_arr[1]) ? $age_start_arr[1] : 0;
 	
 	preg_match("/<age_end>(.*)<\/age_end>/isU",$body,$age_end_arr);
 	$age_end = isset($age_end_arr[1]) ? $age_end_arr[1] : 0;
 	
 	preg_match("/<work_province>(.*)<\/work_province>/isU",$body,$work_province_arr);
 	$work_province = isset($work_province_arr[1]) ? $work_province_arr[1] : 0;
 	
 	preg_match("/<work_city>(.*)<\/work_city>/isU",$body,$work_city_arr);
 	$work_city = isset($work_city_arr[1]) ? $work_city_arr[1] : 0;
 	
 	preg_match("/<marriage>(.*)<\/marriage>/isU",$body,$marriage_arr);
 	$marriage = 0;
 	if(isset($marriage_arr[1])){
	 	if($marriage_arr[1] == '-1' || $marriage_arr[1] == '-2')
	 	$marriage = $marriage_arr[1];
	 	else
	 	$marriage = explode(",",$marriage_arr[1]);
 	}
 
	preg_match("/<salary>(.*)<\/salary>/isU",$body,$salary_arr);
	$salary = 0;
	if(isset($salary_arr[1])){
	 	if($salary_arr[1] == '-1' || $salary_arr[1] == '-2')
	 	$salary = $salary_arr[1];
	 	else
	 	$salary = explode(",",$salary_arr[1]);
	}
 	preg_match("/<education>(.*)<\/education>/isU",$body,$education_arr);
 	$education = 0;
 	if(isset($education_arr[1])){
	 	if($education_arr[1] == '-1' || $education_arr[1] == '-2')
	 	$education = $education_arr[1];
	 	else
	 	$education = explode(",",$education_arr[1]);
 	}
 	
 	preg_match("/<height1>(.*)<\/height1>/isU",$body,$height1_arr);
 	$height1 = isset($height1_arr[1]) ? $height1_arr[1] : 0;
 	
	preg_match("/<height2>(.*)<\/height2>/isU",$body,$height2_arr);
	$height2 = isset($height2_arr[1]) ?  $height2_arr[1] : 0;
	
	preg_match("/<weight1>(.*)<\/weight1>/isU",$body,$weight1_arr);
	$weight1 = isset($weight1_arr[1]) ? $weight1_arr[1] : 0;
	
 	preg_match("/<weight2>(.*)<\/weight2>/isU",$body,$weight2_arr);
 	$weight2 = isset($weight2_arr[1]) ? $weight2_arr[1] : 0;
 	
 	preg_match("/<body1>(.*)<\/body1>/isU",$body,$body_arr);
 	$body1 = 0;
 	if(isset($body_arr[1])){
	 	if($body_arr[1] == '-1' || $body_arr[1] == '-2')
	 	$body1 = $body_arr[1];
	 	else
	 	$body1 = explode(",",$body_arr[1]);
 	}
 	
 	preg_match("/<smoking>(.*)<\/smoking>/isU",$body,$smoking_arr);
 	$smoking = 0;
 	if(isset($smoking_arr[1])){
	 	if($smoking_arr[1] == '-1' || $smoking_arr[1] == '-2')
	 	$smoking = $smoking_arr[1];
	 	else
	 	$smoking = explode(",",$smoking_arr[1]);
 	}
 	
 	preg_match("/<drinking>(.*)<\/drinking>/isU",$body,$drinking_arr);
 	$drinking = 0;
 	if(isset($drinking_ar[1])){
	 	if($drinking_arr[1] == '-1' || $drinking_arr[1] == '-2')
	 	$drinking = $drinking_arr[1];
	 	else
	 	$drinking = explode(",",$drinking_arr[1]);
 	}
 	
 	preg_match("/<occupation>(.*)<\/occupation>/isU",$body,$occupation_arr);
 	$occupation = 0;
 	if(isset($occupation_arr[1])){
	 	if($occupation_arr[1] == '-1' || $occupation_arr[1] == '-2')
	 	$occupation = $occupation_arr[1];
	 	else
	 	$occupation = explode(",",$occupation_arr[1]);
 	}
 	
 	preg_match("/<house>(.*)<\/house>/isU",$body,$house_arr);
 	$house = 0;
 	if(isset($house_arr[1])){
 	if($house_arr[1] == '-1' || $house_arr[1] == '-2')
 	$house = $house_arr[1];
 	else
 	$house = explode(",",$house_arr[1]);
 	}
 	
 	preg_match("/<vehicle>(.*)<\/vehicle>/isU",$body,$vehicle_arr);
 	$vehicle = 0;
 	if(isset($vehicle_arr[1])){
 	if($vehicle_arr[1] == '-1' || $vehicle_arr[1] == '-2')
 	$vehicle = $vehicle_arr[1];
 	else
 	$vehicle = explode(",",$vehicle_arr[1]);
 	}
 	
 	preg_match("/<corptype>(.*)<\/corptype>/isU",$body,$corptype_arr);
 	$corptype =0;
 	if(isset($corptype_ar[1])){
 	if($corptype_arr[1] == '-1' || $corptype_arr[1] == '-2')
 	$corptype = $corptype_arr[1];
 	else
 	$corptype = explode(",",$corptype_arr[1]);
 	}
 	
 	preg_match("/<children>(.*)<\/children>/isU",$body,$children_arr);
 	$children = 0;
 	if(isset($children_arr[1])){
 	if($children_arr[1] == '-1' || $children_arr[1] == '-2')
 	$children = $children_arr[1];
 	else
 	$children = explode(",",$children_arr[1]);
 	}
 	
 	preg_match("/<wantchildren>(.*)<\/wantchildren>/isU",$body,$wantchildren_arr);
 	$wantchildren = 0;
 	if(isset($wantchildren_arr[1])){
 	if($wantchildren_arr[1] == '-1' || $wantchildren_arr[1] == '-2')
 	$wantchildren = $wantchildren_arr[1];
 	else
 	$wantchildren = explode(",",$wantchildren_arr[1]);
 	}
 	preg_match("/<home_townprovince>(.*)<\/home_townprovince>/isU",$body,$home_townprovince_arr);
 	$home_townprovince = isset($home_townprovince_arr[1]) ? $home_townprovince_arr[1] : 0;
 	
 	preg_match("/<home_towncity>(.*)<\/home_towncity>/isU",$body,$home_towncity_arr);
 	$home_towncity = isset($home_towncity_arr[1]) ? $home_towncity_arr[1] : 0;
 	
 	preg_match("/<nation>(.*)<\/nation>/isU",$body,$nation_arr);
 	$nation = isset($nation_arr[1]) ? $nation_arr[1] : 0;

 	
 	preg_match("/<animalyear>(.*)<\/animalyear>/isU",$body,$animalyear_arr);
 	$animalyear = 0;
 	if(isset($animalyear_arr[1])){
 	if($animalyear_arr[1] == '-1' || $animalyear_arr[1] == '-2')
 	$animalyear = $animalyear_arr[1];
 	else
 	$animalyear = explode(",",$animalyear_arr[1]);
 	}
 	
 	preg_match("/<constellation>(.*)<\/constellation>/isU",$body,$constellation_arr);
 	$constellation = 1;
 	if(isset($constellation_arr[1])){
 	if($constellation_arr[1] == '-1' || $constellation_arr[1] == '-2')
 	$constellation = $constellation_arr[1];
 	else
 	$constellation = explode(",",$constellation_arr[1]);
 	}
 	
 	preg_match("/<bloodtype>(.*)<\/bloodtype>/isU",$body,$bloodtype_arr);
 	$bloodtype = 0;
 	if(isset($bloodtype_arr[1])){
 	if($bloodtype_arr[1] == '-1' || $bloodtype_arr[1] == '-2')
 	$bloodtype = $bloodtype_arr[1];
 	else
 	$bloodtype = explode(",",$bloodtype_arr[1]);
 	}
 	
 	preg_match("/<religion>(.*)<\/religion>/isU",$body,$religion_arr);
 	$religion = 0;
 	if(isset($religion_arr[1])){
 	if($religion_arr[1] == '-1' || $religion_arr[1] == '-2')
 	$religion = $religion_arr[1];
 	else
 	$religion = explode(",",$religion_arr[1]);
	}
 	preg_match("/<family>(.*)<\/family>/isU",$body,$family_arr);
 	$family = 0;
 	if(isset($family_arr[1])){
 	if($family_arr[1] == '-1' || $family_arr[1] == '-2')
 	$family = $family_arr[1];
 	else
 	$family = explode(",",$family_arr[1]);
 	}
 	
 	preg_match("/<language1>(.*)<\/language1>/isU",$body,$language_arr);
 	$language = 0;
 	if(isset($language_arr[1])){
	 	if($language_arr[1] == '-1' || $language_arr[1] == '-2')
	 	$language = $language_arr[1];
	 	else
	 	$language = explode(",",$language_arr[1]);
 	}
 	
 	preg_match("/<photo>(.*)<\/photo>/isU",$body,$photo_arr);
 	$photo = 0;
 	if(isset($photo_arr[1])){
 	if($photo_arr[1] == '-1' || $photo_arr[1] == '-2')
 	$photo = $photo_arr[1];
 	else
 	$photo = explode(",",$photo_arr[1]);
 	}
 		if($gender == -1) $gender = 1;
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
        elseif($home_townprovince) $home_townprovince = 2;
        if($home_towncity == -1) $home_towncity = 0;
        if($nation == -1) $nation = 0;
        if($animalyear == -1) $animalyear = 0;
        if($constellation == -1) $constellation = 0;
        if($bloodtype == -1) $bloodtype = 0;
        if($religion == -1) $religion = 0;
        if($family == -1) $family = 0;
        if($language == -1) $language = 0;
        if($photo == -1) $photo = 0;
	advance_search($gender,$age_start,$age_end,$work_province,$work_city,$marriage,$salary,$education,$height1,$height2,$weight1,$weight2,$body,$smoking,$drinking,$occupation,$house,$vehicle,$corptype,$children,$wantchildren,$home_townprovince,$home_towncity,$nation,$body1,$animalyear,$constellation,$bloodtype,$religion,$family,$language,$photo,$email, $searchsign);
}

function advance_search($gender,$age_begin,$age_end,$work_province,$work_city,$marriage,$salary,$education,$height1,$height2,$weight1,$weight2,$body,$smoking,$drinking,$occupation,$house,$vehicle,$corptype,$children,$wantchildren,$home_townprovince,$home_towncity,$nation,$body,$animalyear,$constellation,$bloodtype,$religion,$family,$language,$photo, $email, $searchsign) {
	global $_MooClass,$dbTablePre;
	global $user_arr;
	
	// 包含配置文件
	require_once("crontab_config.php");
	
	//note 获得当前的url 去除多余的参数page=
	$currenturl = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"]; 
	$currenturl2 = preg_replace("/(&page=\d+)/","",$currenturl);
	
	$condition_str = "";
	/* 以下分页参数设置 */
	$pagesize = 16;

	//note limit查询开始位置
	$start = 0;
	
	$index = 'members_man';//索引 sphinx
	$filter = array();//过滤条件 sphinx
	if ($gender == 1) {
		$condition_str .= "女性";
		$index = 'members_women';
	} elseif ($gender == 0) {
		$condition_str .= "男性";
	}
	
	//note 查询是否显示照片
	if($photo == '1') {
		$condition_str .= "　必须要照片";
		//$photo_sql = " AND `pic_name` != ''";
		$filter[] = array('images_ischeck', 1);
	}
	
	//note 转换成实际的年份
	if($age_end) {
		$age_begin1 = gmdate('Y', time()) - intval($age_end);  
	}
	if($age_begin) {
		$age_end1 = gmdate('Y', time()) - intval($age_begin); 	
	}
	
	//note 年龄条件查询sql语句
	//$age_sql = '';
        if($age_begin1 && $age_end1) {
        	//$age_sql = ' AND (s.birthyear >= '.$age_begin1.' AND s.birthyear <= '.$age_end1.')';
        	$filter[] = array('birthyear', array($age_begin1, $age_end1));
        	$condition_str .= "　年龄" . $age_begin . "到" . $age_end . "岁";
        }else if($age_begin1 && empty($age_end1)) {
            //$age_sql = ' AND s.birthyear >= '.$age_begin;
            $filter[] = array('birthyear', array($age_begin, $age_begin+99));
            $condition_str .= "　年龄大于等于" . $age_begin . "岁";
        }else if($age_end1 && empty($age_begin1)) {
        	//$age_sql = ' AND s.birthyear <= '.$age_end;
        	$filter[] = array('birthyear', array($age_end1-99, $age_end1));
        	$condition_str .= "　年龄小于等于" . $age_end . "岁";
        }
        
        //note 地区条件查询sql语句
        /*
        $area_sql = '';
        $area_sql = ' AND s.province = '.$work_province.' AND s.city = '.$work_city;
        if($work_city == '-1' && $work_province != '-1')
        $area_sql = ' AND province = '.$work_province;
        
        if($work_province == '-1' && $work_city == '-1')
         $area_sql = '';
		if($work_province=="-1"){
		 $area_sql = '';
		}
		*/
		
		if ($work_province != '-1' &&  $work_province != 0) {
			$condition_str .= "　所在地区：" . $provice_list[$work_province];
			if($work_province == '-2') $work_province = 2;
			$filter[] = array('province', $work_province);
			
			if ($work_city != '-1' &&  $work_city != 0) {
				$filter[] = array('city', $work_city);
				$condition_str .= " " . $city_list[$work_city];
			}
		}
        
        //note 婚姻状况条件查询sql语句
       
        if($marriage != 0 && $marriage != '-1' && is_array($marriage)) {
        	$marr_str = "　婚烟状况：";
		 //$marriage_sql =" and  s.marriage in(";
		 	$marr_sql = array();
        	foreach($marriage as $v) {
        		$marr_str .= $marriage_list[$v] . " ";
        		$marr_sql[]= $v;
        	}
        	/*
			if($marr_sql){
			  $marr_sql=join(",",$marr_sql);
			}*/
		 	$filter[] = array('marriage', implode(' | ', $marr_sql));
	
		  //$marriage_sql.=$marr_sql.")";	
		  
		   	$condition_str .= $marr_str;     
		  
        }
        
           
			   
        //note 月收入条件查询sql语句
        
        if($salary != 0 && $salary != '-1' && is_array($salary)) {
        	$salary_str = "　月收入:";
			//$salary_sql = " and s.salary in(";
			$sal_sql = array();
        	foreach($salary as $v){
        		$salary_str .= $salary_list[$v];
        		$sal_sql[] =$v;
        	}
			/*if($sal_sql){
			  $sal_sql=join(",",$sal_sql);
			}	
			$salary_sql.=$sal_sql.")";*/
        	$filter[] = array('salary', implode(' | ', $sal_sql));
        	$condition_str .= $salary_str;
        }
		
        //if($salary == '-1') $salary_sql = '';
        //$condition_str .= $salary_str;
        
        //note 教育程度条件查询sql语句
      
        if($education != '0' && $education != '-1' && is_array($education)) {
        	$education_str = "　教育程度:";
			//$education_sql = " and  s.education in(";
			$e_sql = array();
        	foreach($education as $v){
        		$education_str .= $education_list[$v];
        		$e_sql[]= $v;
        	}
			/*if($e_sql){
			  $e_sql=join(",",$e_sql);
			}
			$education_sql.=$e_sql.")";*/
        	$filter[] = array('education', implode(' | ', $e_sql));
			
			 $condition_str .= $education_str;
        }
       
		
        //if($education == '-1') $education_sql = '';
        
        //note 身高条件查询sql语句
        //$height_sql = '';
        $height1 = ($height1!=0 && $height1!='-1') ? $height1 : 0;
        $height2 = ($height2!=0 && $height2!='-1') ? $height2 : 0;
        if($height1 || $height1){
	        if($height1  && $height2 && ($height2 > $height1)) {
	        	//$height_sql = ' AND (s.height >= '.$height1.' AND s.height <= '.$height2.')';
	        	$filter[] = array('height', array($height1, $height2));
	        	$condition_str .= "　身高" . $height1 . "cm到" . $height2 . "cm";
	        }else if($height1 && $height2 && ($height2 < $height1)) {
	        	//$height_sql = ' AND (s.height <= '.$height1.' AND s.height >= '.$height2.')';
	        	$filter[] = array('height', array($height2, $height1));
	        	$condition_str .= "　身高" . $height2 . "cm到" . $height1 . "cm";
	        }else if($height1 && !$height2) {
	        	//$height_sql = ' AND s.height >= '.$height1;
	        	$filter[] = array('height', array($height1, $height1+200));
	        	$condition_str .= "　身高大于等于" . $height1 . "cm";
	        }else if($height2  && !$height1) {
	        	//$height_sql = ' AND s.height <='.$height2;
	        	$filter[] = array('height', array(0, $height2));
	        	$condition_str .= "　身高小于等于" . $height2 . "cm";
	        }else if($height1 && $height2  && $height1 == $height2) {
	        	//$height_sql = ' AND s.height ='.$height1;
	        	$filter[] = array('height', $height1);
	        	$condition_str .= "　身高等于" . $height1 . "cm";
	        }
        }
        /*else if($height2 =='-1' && $height1 == '-1') {
        	$height_sql = '';
        }
        */
        //note 体重条件查询sql语句
        //$weight_sql = '';
        $weight1 = ($weight1!=0 && $weight1!='-1') ? $weight1 : 0;
        $weight2 = ($weight2!=0 && $weight2!='-1') ? $weight2 : 0;
        if($weight1 && $weight2){
	        if($weight1 && $weight2  && ($weight2 > $weight1)) {
	        	//$weight_sql = ' AND (sf.weight >= '.$weight1.' AND sf.weight <= '.$weight2.')';
	        	$filter[] = array('weight', array($weight1, $weight2));
	        	$condition_str .= "　体重" . $weight1 . "kg到" . $weight2 . "kg";
	        }else if($weight1 && $weight2 && ($weight2 < $weight1)) {
	        	//$weight_sql = ' AND (sf.weight <= '.$weight1.' AND sf.weight >= '.$weight2.')';
	        	$filter[] = array('weight', array($weight2, $weight1));
	        	$condition_str .= "　体重" . $weight2 . "kg到" . $weight1 . "kg";
	        }else if($weight1 && !$weight2) {
	        	//$weight_sql = ' AND sf.weight >= '.$weight1;
	        	$filter[] = array('weight', array($weight1, $weight1+80));
	        	$condition_str .= "　体重大于等于" . $weight1 . "kg";
	        }else if($weight2  && !$weight1) {
	        	//$weight_sql = ' AND sf.weight <='.$weight2;
	        	$filter[] = array('weight', array(0, $weight2));
	        	$condition_str .= "　体重小于等于" . $weight2 . "kg";
	        }else if($weight1  && $weight2 && $weight1 == $weight2) {
	        	//$weight_sql = ' AND sf.weight ='.$weight1;
	        	$filter[] = array('weight', $weight1);
	        	$condition_str .= "　体重等于" . $weight1 . "kg";
	        }
        }
        /*else if($weight2 =='-1' && $weight1 == '-1') {
        	$weight_sql = '';
        }*/
        
        //note 是否抽烟查询sql语句
        
		
        if($smoking != 0 && $smoking != '-1' && $smoking != '-2' && is_array($smoking)) {
        	$smoking_str = "　抽烟要求:";
			//$smoking_sql = " and sf.smoking in(";
			$smok_sql = array();
        	foreach($smoking as $v){
        		$smoking_str .= $smoking_list[$v];
        		$smok_sql[]=$v;
        	}
			/*if($smok_sql){
			    $smok_sql=join(",",$smok_sql);
			}
			$smoking_sql.= $smok_sql.")";*/
        	$filter[] = array('smoking', implode(' | ', $smok_sql));
			$condition_str .= $smoking_str;
        }
		
        //if($smoking == '-1' || $smoking == '-2') $smoking_sql = '';
        
        //note 是否喝酒查询sql语句
      
        if($drinking != 0 && $drinking != '-1' && $drinking != '-2' && is_array($drinking)) {
        	$drinking_str = "　喝酒要求:";
			//$drinking_sql = " and sf.drinking in(";
			$drink_sql = array();
        	foreach($drinking as $v){
        		$drinking_str .= $drinking_list[$v] . " ";
        		$drink_sql[]= $v;
        	}
        	$filter[] = array('drinking', implode(' | ', $drink_sql));
		/*	if($drink_sql){
			  $drink_sql=join(",",$drink_sql);
			 
			}
			$drinking_sql.=$drink_sql.")";*/
			$condition_str .= $drinking_str;
        }
		
       // 
        //if($drinking == '-1' || $drinking == '-2') $drinking_sql = '';
        
        //note 从事职业查询sql语句
        
        if($occupation != 0 && $occupation != '-1' && $occupation != '-2' && is_array($occupation)) {
        	$occupation_str = "　从事行业要求:";
	        //$occupation_sql = " and sf.occupation in( ";
	        $oc_sql = array();	
			foreach($occupation as $v){
				$occupation_str .= $drinking_list[$v] . " ";
        		$oc_sql[]= $v;
        	}
			/*if($oc_sql){
			    $oc_sql=join(",",$oc_sql);
			} 
			$occupation_sql.=$oc_sql.")";
			*/
        	$filter[] = array('occupation', implode(' | ', $oc_sql));
			 $condition_str .= $occupation_str;
        }
		
       
        //if($occupation == '-1' || $occupation == '-2')  $occupation_sql= '';
        
        //note 住房情况查询sql语句
      
        if($house != 0 && $house != '-1' && $house != '-2' && is_array($house)) {
        	$house_str = "　住房情况:";
		 	//$house_sql = "  and s.house in( ";
		 	$ho_sql = array();
        	foreach($house as $v){
        		$house_str .= $house_list[$v] . " ";
        		$ho_sql[]= $v;
        	}
			/*if($ho_sql){
			  $ho_sql=join(",",$ho_sql);
			}
			$house_sql.=$ho_sql.")";*/
        	$filter[] = array('house', implode(' | ', $ho_sql));
        	$condition_str .= $house_str;
        }
		
       
       // if($house == '-1' || $house == '-2') $house_sql = '';
        
        //note 购车查询sql语句
   
        if($vehicle != 0 && $vehicle != '-1' && $vehicle != '-2' && is_array($vehicle)) {
        	$vehicle_str = "　购车情况:";
			//$vehicle_sql =" and sf.vehicle in(";
			$ve_sql = array();
        	foreach($vehicle as $v){
        		$vehicle_str .= $vehicle_list[$v] . " ";
        		$ve_sql[]= $v;
        	}
			/*if($ve_sql){
			  $ve_sql=join(",",$ve_sql);
			}
			$vehicle_sql.=$ve_sql.")";*/
			$filter[] = array('vehicle', implode(' | ', $ve_sql));
			$condition_str .= $vehicle_str;
         }
		
		
        //if($vehicle == '-1' || $vehicle == '-2') $vehicle_sql = '';
        
        //note 公司类别sql语句
       
        if($corptype != 0 && $corptype != '-1' && $corptype != '-2' && is_array($corptype)) {
        	$corptype_str = "　公司类别:";
		    //$corptype_sql = "  and sf.corptype in(";
        	$co_sql = array();
        	foreach($corptype as $v){
        		$corptype_str .= $corptype_list[$v] . " ";
        		$co_sql[]=$v;
        	}
			/*if($co_sql){
			    $co_sql=join(",",$co_sql);
			}
			$corptype_sql.=$co_sql." )";*/
        	$filter[] = array('corptype', implode(' | ', $co_sql));
        	$condition_str .= $corptype_str;
	
        }
		
        //if($corptype == '-1' || $corptype == '-2') $corptype_sql = '';
        
        //note 是否有孩子sql语句
        if($children != 0 && $children != '-1' && $children != '-2' && is_array($children)) {
        	$children_str = "　有无孩子:";
			//$children_sql = "  and  s.children in(";
        	$chil_sql = array();
        	foreach($children as $v){
        		$children_str .= $children_list[$v] . " ";
        		$chil_sql[]=$v;
        	}
			/*if($chil_sql){
			  $chil_sql=join(",",$chil_sql);
			}
			$children_sql.=$chil_sql.")";*/
        	$filter[] = array('children', implode(' | ', $chil_sql));
        	$condition_str .= $children_str;
        }
		
        //if($children == '-1' || $children == '-2') $children_sql = '';
        
        //note 是否要孩子sql语句
     
        if($wantchildren != 0 && $wantchildren != '-1' && $wantchildren != '-2' && is_array($wantchildren)) {
        	$wantchildren_str = "　可要孩子:";
			//$wantchildren_sql = "  and sf.wantchildren in(";
        	$wan_sql = array();
        	foreach($wantchildren as $v){
        		$wantchildren_str .= $wantchildren_list[$v] . " ";
        		$wan_sql[]=$v;
        	}
			/*if($wan_sql){
			  $wan_sql=join(",",$wan_sql);
			}
			$wantchildren_sql.=$wan_sql.")";*/
        	$filter[] = array('wantchildren', implode(' | ', $wan_sql));
        	$condition_str .= $wantchildren_str;
        }
       // if($wantchildren == '-1' || $wantchildren == '-2') $wantchildren_sql = '';
        
        //note 籍贯查询sql语句
       /* $hometown_sql = '';
        $hometown_sql = ' AND sf.hometownprovince = '.$home_townprovince.' AND sf.hometowncity = '.$home_towncity;
        if($home_towncity == '-1' && $home_townprovince !='-1') {
        	$hometown_sql = ' AND sf.hometownprovince = '.$home_townprovince;
        }
        if($home_townprovince == '-1' && $home_townprovince == '-1') $hometown_sql = '';*/
        
        if ($home_townprovince !=0 && $home_townprovince != -1) {
        	if($home_townprovince == '-2') $home_townprovince = 2;
        	$filter[] = array('home_townprovince', $home_townprovince);
        	$condition_str .= "　籍贯:" . $city_list[$home_townprovince];
        	if ($home_towncity != 0 && $home_towncity != -1) {
        		$filter[] = array('home_towncity', $home_towncity);
        		$condtion_str .= " " . $city_list[$home_towncity];
        	}
        }
        
        //note 名族查询sql语句
        
        /*$nation_sql = '';
        $nation_sql = ' AND sf.nation = '.$nation;
        if($nation == '-1') $nation_sql = '';
        */
        if ($nation != -1) {
        	$filter[] = array('nation', $nation);
        	$condition_str .= "　民族:" . $stock_list[$nation];
        }
        
        //note 体型查询sql语句
        if($body != 0 && $body != '-1' && $body != '-2' && is_array($body)) {
        	$body_str = "　体型:";
			//$body_sql = " and sf.body in(";
			$body_list = $user_arr["gender"] == 0 ? $body1_list : $body0_list;
        	$bo_sql = array();
			foreach($body as $v) {
        		/*if ($user_arr["gender"] == 0) {
        			$body_str .= $body1_list[$v] . " ";
        		} else {
        			$body_str .= $body0_list[$v] . " ";
        		}*/
        		$body_str .=  $body_list[$v];
        		$bo_sql[]= $v;
        	}
			/*if($bo_sql){
			    $bo_sql=join(",",$bo_sql);
			}
			$body_sql.=$bo_sql.")";*/
        	$filter[] = array('body', implode(' | ', $bo_sql));
        	$condition_str .= $body_str;
        }
		
        //if($body == '-1' || $body == '-2') $body_sql = '';
        
        //note 生肖查询sql语句
       
        if($animalyear != 0 && $animalyear != '-1' && $animalyear != '-2' && is_array($animalyear)) {
        	$animalyear_str = "　生肖:";
			//$animalyear_sql = "  and sf.animalyear in(";
			$ani_sql = array();
        	foreach($animalyear as $v) {
        		$animalyear_str .= $animals_list[$v] . " ";
        		$ani_sql[]=$v;
        	}
			/*if($ani_sql){
			  $ani_sql=join(",",$ani_sql); 
			}
			  $animalyear_sql.=$ani_sql.")";*/
			$filter[] = array('animalyear', implode(' | ', $ani_sql));
			$condition_str .= $animalyear_str;
        }
		
        //if($animalyear == '-1' || $animalyear == '-2') $animalyear_sql = '';
        
        //note 星座查询sql语句
        if($constellation != 0 && $constellation != '-1' && $constellation != '-2' && is_array($constellation)) {
        	$constellation_str = "　星座:";
			//$constellation_sql = " and  sf.constellation in( ";
			$cons_sql = array();
        	foreach($constellation as $v) {
        		$constellation_str .= $constellation_list[$v] . " ";
        		$cons_sql[]=$v;
        	}
			/*if($cons_sql){
			  $cons_sql=join(",",$cons_sql);
			}
			$constellation_sql.=$cons_sql.")";*/
        	$filter[] = array('constellation', implode(' | ', $cons_sql));
        	$condition_str .= $constellation_str;
        }
		
        //if($constellation == '-1' || $constellation == '-2') $constellation_sql = '';
        
        //note 血型查询sql语句
        if($bloodtype != 0 && $bloodtype != '-1' && $bloodtype != '-2' && is_array($bloodtype)) {
			$bloodtype_str = "　血型:";
        	//$bloodtype_sql =" and sf.bloodtype in(";
        	$bloo_sql = array();
        	foreach($bloodtype as $v) {
        		$bloodtype_str .= $bloodtype_list[$v] . " ";
        		$bloo_sql[]=$v;
        	}
			/*if($bloo_sql){
			  $bloo_sql=join(",",$bloo_sql); 
			}
			$bloodtype_sql.=$bloo_sql.")";*/
        	$filter[] = array('bloodtype', implode(' | ', $bloo_sql));
        	$condition_str .= $bloodtype_str;
        }
		
        //if($bloodtype == '-1' || $bloodtype == '-2') $bloodtype_sql = '';
       
	    
        //note 信仰查询sql语句
        if($religion != 0 && $religion != '-1' && $religion != '-2' && is_array($religion)) {
        	$religion_str = "　信仰:";
			//$religion_sql = "  and  sf.religion in(";
			$reli_sql = array();
        	foreach($religion as $v) {
        		$religion_str .= $belief_list[$v] . " ";
        		$reli_sql[]=$v;
        	}
		/*	if($reli_sql){
			  $reli_sql=join(",",$reli_sql);
			}
			$religion_sql.=$reli_sql.")";*/
			$filter[] = array('religion', implode(' | ', $reli_sql));
			$condition_str .= $religion_str;
        }
		
       // if($religion == '-1' || $religion == '-2') $religion_sql = '';
        
        //note 兄弟姐妹sql语句
       
        if($family != '0' && $family != '-1' && $family != '-2' && is_array($family)) {
        	$family_str = "　兄弟姐妹:"; 
			//$family_sql = " and  sf.family in(";
			$fami_sql = array();
         	foreach($family as $v) {
         		$family_str .= $family_list[$v];
        		$fami_sql[]= $v;
        	}
			/*if($fami_sql){
			    $fami_sql=join(",",$fami_sql);
			}
			$family_sql.=$fami_sql.")";*/
        	$filter[] = array('family', implode(' | ', $fami_sql));
        	$condition_str .= $family_str;
        }
		
        //if($family == '-1' || $family == '-2')  $family_sql = '';
        
        //note 语言能力sql语句，采用like查询
     
 		$language_sql = "";
        if(is_array($language)) {
        	$language_str = "　语言能力:";
			//$language_sql = " and(";
			//$lan_sql = "";
			$lan_sql = array();
        	foreach($language as $v) {
        		$language_str .= $language_list[$v] . " ";
        		$lan_sql[] = $v; 
        		/*if (empty($lan_sql)) {
        			$lan_sql .= " FIND_IN_SET('" . $v . "'," . "sf.language)";
        		} else {
        			$lan_sql .= " or FIND_IN_SET('" . $v . "'," . "sf.language)";
        		}*/
        	}
        	$filter[] = array('language', implode(' | ', $lan_sql));
			//$language_sql .= $lan_sql . ")";
			$condition_str .= $language_str;
        }
               	
        //if($language == '-1' || $language == '-2') $language_sql = '';
              
	/*$sql = " on sf.uid=s.uid  where s.is_lock = 1 and s.gender='$gender' ".$age_sql.$area_sql.$marriage_sql.$salary_sql.$education_sql.$height_sql.$weight_sql.
		$smoking_sql.$drinking_sql.$occupation_sql.$house_sql.$vehicle_sql.$corptype_sql.$children_sql.$wantchildren_sql.$hometown_sql.
		$nation_sql.$body_sql.$animalyear_sql.$constellation_sql.$bloodtype_sql.$religion_sql.$family_sql.$language_sql.$photo_sql;*/
        
     $filter[] = array('is_lock', 1);
     $limit = array(16);
	 $sp = searchApi($index);
	 $sp->getResultOfReset($filter, $limit);
	 $user_ids = $sp->getIds();
	//$user = $_MooClass['MooMySQL']->getAll("select s.*,sf.body, sf.hometownprovince, sf.hometowncity from `{$dbTablePre}members` s left join `{$dbTablePre}memberfield` sf $sql LIMIT 0, 16");
	
	//note 找不到匹配的结果返回单独提示页面
	if(count($user_ids) == '0') {
		return;
	}
	
	$sql = "select s.uid,s.nickname,s.birthyear,s.height,s.hometownprovince,s.hometowncity,b.pic_date,b.pic_name from `{$dbTablePre}members_search` s left join `{$dbTablePre}members_base` b on s.uid=b.uid where s.uid in (".implode(',', $user_ids).")";
	$user = $_MooClass['MooMySQL']->getAll($sql);
	
	$recommend_member = $user[0];
	$recommend_member_href = '<a href="http://' . MOOPHP_HOST . '/index.php?n=space&h=viewpro&uid=' . $recommend_member["uid"] . '" style="color:#bd0000; text-decoration:underline" target="_blank">';
	if (!empty($recommend_member["nickname"])) {
		$recommend_member_href .= htmlspecialchars(MooCutstr($recommend_member["nickname"], 10));
	} else {
		$recommend_member_href .= htmlspecialchars($recommend_member["uid"]);
	}
	$recommend_member_href .= "</a>";
	
	$sql = "select introduce from " . $dbTablePre . "members_introduce where uid = '" . $recommend_member["uid"] . "'";
	$choice = $_MooClass['MooMySQL']->getOne($sql,true);
	
	ob_start();
	include MooTemplate('public/crontab_index', 'module');
	$content = ob_get_clean();
	//error_log($content, 0);
	//echo $email;
	$return = MooSendMail($email, "真爱一生网推荐会员", $content, "", false,$user_arr['uid']);
}


// 删除过时的邮件
function crontab_del_mail() {
	global $_MooClass,$dbTablePre;
	
	$date = date("Y-m-d H:i:s", strtotime("-5 day"));
	
	$sql = "delete from " . $dbTablePre . "mail_queue where registration_date < '" . $date . "'";
	$_MooClass['MooMySQL']->query($sql);
}

//会员注册统计
function reg_count(){
	global $_MooClass,$dbTablePre;
	set_time_limit(0);
	$input_date = MooGetGPC('date','string','G');
	if(!empty($input_date)){
		$ymd = explode('-',$input_date);
		$yesterday_start = mktime(0, 0, 0, $ymd['1'],$ymd['2'],$ymd['0']);
		$yesterday_end = mktime(23, 59, 59, $ymd['1'],$ymd['2'],$ymd['0']);
	}else{
		$yesterday_start = mktime(0, 0, 0, date('m'),date('d')-1,date('Y'));
		$yesterday_end = mktime(23, 59, 59, date('m'),date('d')-1,date('Y'));
	}
	
	//sphinx 类
	$sp = searchApi('members_women');
	$filters = array();	
	$filters[] = array('usertype', 1); 
	$limits = array(1);
	//$where = ' and usertype=1 and gender=1';
	//注册总数
	$filter = $filters;
	$filter[] = array('regdate', array(0, $yesterday_end));
	$sp->getResultOfReset($filter, $limits);
	$reg_members_num_all = $sp->getRs('total_found');
	/*
	$sql = "select count(*) c from {$dbTablePre}members where regdate<=$yesterday_end $where";
	$reg_members_num_all = $_MooClass['MooMySQL']->getOne($sql);
	$reg_members_num_all = $reg_members_num_all['c'];
	echo $sql."<br />";
	*/
	

	
	//昨天的会员有照片的总数
	$filter = $filters;
	$filter[] = array('regdate', array($yesterday_start, $yesterday_end));
	$filter[] = array('images_ischeck', 1);
	$sp->getResultOfReset($filter, $limits);
	$members_num = $sp->getRs('total_found');
	/*$sql = "select count(*) c from {$dbTablePre}members where regdate>=$yesterday_start and regdate<=$yesterday_end and images_ischeck=1 $where";
	$today_members_num = $_MooClass['MooMySQL']->getOne($sql);
	$members_num = $today_members_num['c'];
	*/
	//昨天的会员总数
	$filter = $filters;
	$filter[] = array('regdate', array($yesterday_start, $yesterday_end));
	$sp->getResultOfReset($filter, $limits);
	$today_members_num = $sp->getRs('total_found');
	/*$sql = "select count(*) c from {$dbTablePre}members where regdate>=$yesterday_start and regdate<=$yesterday_end $where";
	$today_members_num_all = $_MooClass['MooMySQL']->getOne($sql);
	$today_members_num = $today_members_num_all['c'];*/
	
	//注意总会员数,有相册的
	$filter = $filters;
	$filter[] = array('regdate', array(0, $yesterday_end));
	$filter[] = array('images_ischeck', 1);
	$sp->getResultOfReset($filter, $limits);
	$tmembers_num = $sp->getRs('total_found');
	/*$sql = "select count(*) c from {$dbTablePre}members where regdate<=$yesterday_end and images_ischeck=1 $where";
	$reg_members_num = $_MooClass['MooMySQL']->getOne($sql);
	$tmembers_num = $reg_members_num['c'];
	echo $sql."<br />";*/
	
	//echo $sql."<br />";
	//昨天只有一张相册的会员总数
	$filter = $filters;
	$filter[] = array('regdate', array($yesterday_start, $yesterday_end));
	$filter[] = array('images_ischeck', 1);
	$filter[] = array('pic_num', 0);
	$sp->getResultOfReset($filter, $limits);
	$pic_num1 = $sp->getRs('total_found');
	/*$sql = "select count(*) c from {$dbTablePre}members where regdate>=$yesterday_start and regdate<=$yesterday_end and images_ischeck=1 and pic_num<=0 $where";
	$today_members_num1 = $_MooClass['MooMySQL']->getOne($sql);
	$pic_num1 = $today_members_num1['c'];
	*/
	
	$filter = $filters;
	$filter[] = array('regdate', array(0, $yesterday_end));
	$filter[] = array('images_ischeck', 1);
	$filter[] = array('pic_num', 0);
	$sp->getResultOfReset($filter, $limits);
	$total_pic_num1 = $sp->getRs('total_found');
	/*$sql = "select count(*) c from {$dbTablePre}members where regdate<=$yesterday_end and images_ischeck=1 and pic_num<=0 $where";
	$reg_members_num1 = $_MooClass['MooMySQL']->getOne($sql);
	$total_pic_num1 = $reg_members_num1['c'];
	echo $sql."<br />";*/
	
	//昨天只有二张相册的会员总数
	$filter = $filters;
	$filter[] = array('regdate', array($yesterday_start, $yesterday_end));
	$filter[] = array('images_ischeck', 1);
	$filter[] = array('pic_num', 1);
	$sp->getResultOfReset($filter, $limits);
	$pic_num2 = $sp->getRs('total_found');
	/*$sql = "select count(*) c from {$dbTablePre}members where regdate>=$yesterday_start and regdate<=$yesterday_end and images_ischeck=1 and pic_num=1 $where";
	$today_members_num2 = $_MooClass['MooMySQL']->getOne($sql);
	$pic_num2 = $today_members_num2['c'];*/
	
	$filter = $filters;
	$filter[] = array('regdate', array(0, $yesterday_end));
	$filter[] = array('images_ischeck', 1);
	$filter[] = array('pic_num', 1);
	$sp->getResultOfReset($filter, $limits);
	$total_pic_num2 = $sp->getRs('total_found');
	/*$sql = "select count(*) c from {$dbTablePre}members where regdate<=$yesterday_end and images_ischeck=1 and pic_num=1 $where";
	$reg_members_num2 = $_MooClass['MooMySQL']->getOne($sql);
	$total_pic_num2 = $reg_members_num2['c'];
	echo $sql."<br />";*/
	
	//昨天只有三张相册的会员总数
	$filter = $filters;
	$filter[] = array('regdate', array($yesterday_start, $yesterday_end));
	$filter[] = array('images_ischeck', 1);
	$filter[] = array('pic_num', 2);
	$sp->getResultOfReset($filter, $limits);
	$pic_num3 = $sp->getRs('total_found');
	/*$sql = "select count(*) c from {$dbTablePre}members where regdate>=$yesterday_start and regdate<=$yesterday_end and images_ischeck=1 and pic_num=2 $where";
	$today_members_num3 = $_MooClass['MooMySQL']->getOne($sql);
	$pic_num3 = $today_members_num3['c'];*/
	
	$filter = $filters;
	$filter[] = array('regdate', array(0, $yesterday_end));
	$filter[] = array('images_ischeck', 1);
	$filter[] = array('pic_num', 2);
	$sp->getResultOfReset($filter, $limits);
	$total_pic_num3 = $sp->getRs('total_found');
	/*$sql = "select count(*) c from {$dbTablePre}members where regdate<=$yesterday_end and images_ischeck=1 and pic_num=2 $where";
	$reg_members_num3 = $_MooClass['MooMySQL']->getOne($sql);
	$total_pic_num3 = $reg_members_num3['c'];
	echo $sql."<br />";*/
	
	//昨天大于三张相册的会员总数
	$filter = $filters;
	$filter[] = array('regdate', array($yesterday_start, $yesterday_end));
	$filter[] = array('images_ischeck', 1);
	$filter[] = array('pic_num', array(0, 3), EXCLUDE_TRUE);
	$sp->getResultOfReset($filter, $limits);
	$pic_num4 = $sp->getRs('total_found');
	/*$sql = "select count(*) c from {$dbTablePre}members where regdate>=$yesterday_start and regdate<=$yesterday_end and images_ischeck=1 and pic_num>=3 $where";
	$today_members_num4 = $_MooClass['MooMySQL']->getOne($sql);
	$pic_num4 = $today_members_num4['c'];*/
	
	$filter = $filters;
	$filter[] = array('regdate', array(0, $yesterday_end));
	$filter[] = array('images_ischeck', 1);
	$filter[] = array('pic_num', array(0, 3), EXCLUDE_TRUE);
	$sp->getResultOfReset($filter, $limits);
	$total_pic_num4 = $sp->getRs('total_found');
	/*$sql = "select count(*) c from {$dbTablePre}members where regdate<=$yesterday_end and images_ischeck=1 and pic_num>=3 $where";
	$reg_members_num4 = $_MooClass['MooMySQL']->getOne($sql);
	$total_pic_num4 = $reg_members_num4['c'];
	echo $sql."<br />";*/
	
	$sql = "replace into {$dbTablePre}reg_count set date='{$yesterday_start}',pic_num1='{$pic_num1}',pic_num2='{$pic_num2}',pic_num3='{$pic_num3}',pic_num4='{$pic_num4}',members_num='{$members_num}',total_pic_num1='{$total_pic_num1}',total_pic_num2='{$total_pic_num2}',total_pic_num3='{$total_pic_num3}',total_pic_num4='{$total_pic_num4}',tmembers_num='{$tmembers_num}',reg_members_num='{$reg_members_num_all}',today_members_num='{$today_members_num}'";
	$_MooClass['MooMySQL']->query($sql);
	
	//echo $sql;
}

//自动短信生日提醒
function birthday_remark(){
	global $_MooClass,$dbTablePre;
	$time = time();
	$birthmonth = date('n');
	$birthday = date('j');

	/*$sql = "SELECT sid,telphone,uid,nickname,birthmonth,birthday FROM {$dbTablePre}members WHERE birthmonth='{$birthmonth}' AND birthday='{$birthday}' AND  usertype=1";
	*/
	$sql = "SELECT s.uid,s.sid,s.telphone,s.nickname FROM {$dbTablePre}members_search s left join {$dbTablePre}members_base b on s.uid=b.uid where s.usertype=1 and date_format(b.birth, '%c')={$birthmonth} and date_format(b.birth, '%e')={$birthday}";
	$members = $_MooClass['MooMySQL']->getAll($sql);
	//fanglin暂时屏蔽
	foreach($members as $member){
		$message = "尊敬的会员ID:{$member['uid']}您好！今天是您的生日，真爱一生网(www.zhenaiyisheng.cc)祝您生日快乐，早日找到自己的另一半。";
		Push_message_intab($member['uid'],$member['telphone'],"生日祝福",$message,"system");
		
	}
	
}

//快到期高级会员提前7天提提醒，已到期高级会员则更改为普通会员
function will_expire_vip(){
	global $_MooClass,$dbTablePre;
	$sql_value2 = '';
	$time = strtotime('-7 day');
	$sql = "SELECT uid,endtime,sid FROM {$dbTablePre}members_search where endtime<'{$time}' and s_cid IN(20,30) LIMIT 5";
	$expire_members = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);	
	if (empty($expire_members)) exit('没有快到期的会员');
	$sql2 = "INSERT INTO {$GLOBALS['dbTablePre']}admin_remark(sid,groupid,title,content,awoketime,dateline) VALUES";
	foreach($expire_members as $member){
		$dateline = time();
		$awoketime = $dateline+600;
		$endtime = date('Y-m-d H:i:s',$member['endtime']);
		$sql_value2 .= "('{$member['sid']}','0','ID:{$member['uid']}vip会员快到期','{$member['uid']}会员到期时间为{$endtime}','{$awoketime}','{$dateline}'),";
	}
	$sql_value2 = substr($sql_value2,0,-1);
	$sql2 .= $sql_value2;

	$GLOBALS['_MooClass']['MooMySQL']->query($sql2);
	
	//将过期vip会员自动转为普通会员
	$current_time = time();
	$sql = "select group_concat(uid) as uid_list from {$dbTablePre}members_search WHERE endtime<'{$current_time}' AND s_cid IN(20,30)";
	$expire_user = 	$GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	
	//写入日志
	$handle = "系统自动将{$expire_user['uid_list']}过期vip会员转为普通会员";
	$sql = "INSERT INTO {$dbTablePre}server_log SET type='4',tablename='web_members_search',handle='$handle',sid='0',time='{$current_time}'";
	$GLOBALS['_MooClass']['MooMySQL']->query($sql);
//	var_dump($expire_user);
	
	//转为普通会员
	$sql = "UPDATE {$dbTablePre}members_search SET s_cid=40 WHERE endtime<'{$current_time}' AND s_cid IN(10,20,30) and (usertype='1' or usertype='3')";
	$GLOBALS['_MooClass']['MooMySQL']->query($sql);
	$sql2 = "select uid from {$dbTablePre}members_search where endtime<'{$current_time}' AND s_cid IN(10,20,30) and (usertype='1' or usertype='3')";
	$rs = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql2);
	if(is_array($rs) && !empty($rs)){
		foreach($rs as $k=>$v){
			$str_arr[$v['uid']] = array(40);
		}
		searchApi('members_man members_women')->updateAttr(array('s_cid'),$str_arr);
		
		//删除背景音乐
		$upDir='data'.DIRECTORY_SEPARATOR.'music'.DIRECTORY_SEPARATOR.$v['uid'] ;
		rmdir($upDir);
	}
	
	//$sql = "SELECT uid FROM {$dbTablePre}members bgtime<$time WHERE s_cid IN(0,1)";
	
}

//高级会员聊天回复信息
function get_noanswer_message(){
    $currenttimestamp=time();
    $time=$currenttimestamp-28800;
    $sql="select cm.fromid,cm.toid,cm.id,ms.sid from web_chat_message cm left join web_members_search ms on cm.fromid=ms.uid where cm.serverid=0 and cm.status=0 and cm.time>{$time} group by cm.fromid";
	
    
	//****************提醒所属客服*****************
	$Messages=array();
	$Messages=$GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	$title="聊天信息未回复";
	foreach($Messages as $v){
		$sid = $v['sid'];
		$content = '会员 '.$v['fromid'].' 发送到 '.$v['toid'].'信息未回';
		$awoketime = $currenttimestamp+600;
		$sql_remark = "insert into web_admin_remark set sid='{$sid}',title='{$title}',content='{$content}',awoketime='{$awoketime}',dateline='{$currenttimestamp}'";
		$GLOBALS['_MooClass']['MooMySQL']->query($sql_remark);
	}
}

//定时发送短信
function sendmsg_crontab(){
	global $_MooClass,$dbTablePre;
	$lim_count=300;
	$smslog_array = array();
	$time=time();
	$two_month_befor=$time-3600*24*60;	//两个月未登录的会员不发短信
	$sql="SELECT * FROM web_sendmsg_tmp ORDER BY id ASC limit $lim_count ";
	$msg=$GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	foreach($msg as $key=>$v){
		
		
		if(empty($v['sid'])) { /*修正可能会员ID=0 发送的信息*/
			$sql="DELETE  FROM web_sendmsg_tmp where id = '".$v['id']."'";                         
			$GLOBALS['_MooClass']['MooMySQL']->query($sql);
			continue; 
		}
		
		//if($v['dateline']>$time) continue;
		
		if($v['telphone'] && preg_match("/^1[3458][0-9]{9}$/",$v['telphone'])){				
		    //之前的$sql1="SELECT uid from {$dbTablePre}member_admininfo WHERE real_lastvisit>'$two_month_befor' and uid='".$v['uid']."'";
			//$sql="SELECT uid from {$dbTablePre}members_login WHERE lastvisit>'$two_month_befor' and uid='".$v['uid']."'";
			
			//$exp_id=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
			//if($exp_id['uid']){
				if(SendMsg($v['telphone'],$v['message'])==100){
					echo "发送成功会员：";
					echo $v['uid'];
					
					//发送成功删除记录
					$sql="DELETE  FROM web_sendmsg_tmp where id = '".$v['id']."'";							
					$GLOBALS['_MooClass']['MooMySQL']->query($sql);
					
					$smslog_array[$key]['sid']=$v['sid'];
					$smslog_array[$key]['uid']=$v['uid'];
					$smslog_array[$key]['content']=$v['message'];
					$smslog_array[$key]['sendtime']=time();
					$smslog_array[$key]['type']=$v['type'];
					
					
					
				}else{
					if($v['count']>2){
						$sql="DELETE  FROM web_sendmsg_tmp where id = '".$v['id']."'";							
						$GLOBALS['_MooClass']['MooMySQL']->query($sql);
						
						echo "该会员:".$v['uid'];
						echo "发送失败2次，数据已经删除".$v['count'];
						
					}else{
						$count=$v['count']+1;
						return update_msg_count($v['id'],$count);
						echo "发送失败!".$count;
					}
				}
				
			/* }else{
				//$need_del[]="'$v[uid]'";
				$sql="DELETE  FROM web_sendmsg_tmp where id = '".$v['id']."'";							
				$GLOBALS['_MooClass']['MooMySQL']->query($sql);
				echo "长时间未登录会员";
				echo $v['uid'];
				echo "删除";
		   } */
		}else{
			//$need_del[]="'$v[uid]'";	
			$sql="DELETE  FROM web_sendmsg_tmp where id = '".$v['id']."'";							
			$GLOBALS['_MooClass']['MooMySQL']->query($sql);
			echo "没有电话号码的会员";
			echo $v['uid'];
			echo "删除";
		}
	}
	
	write_log($smslog_array);
	
}
/*
//删除短信发送临时表中的指定数据
function delete_msg($need_del){
	if($need_del){
		$id=join(',',$need_del);
		$sql="DELETE  FROM web_sendmsg_tmp where id IN ($id)";
		if($GLOBALS['_MooClass']['MooMySQL']->query($sql)) return true;
	}
}
*/

//执行表内的URL
function do_count_url(){
$sql="select url,id from web_do_url where count<5 and stats=0 limit 60";
$url_array=$GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
//print_r($url_array);
foreach($url_array as $url){
	if(file_get_contents($url['url'])=="ok"){
		$GLOBALS['_MooClass']['MooMySQL']->query("update web_do_url set stats=1 where id='{$url['id']}'");
		echo "ok";
		}else{
			$GLOBALS['_MooClass']['MooMySQL']->query("update web_do_url set count=count+1 where id='{$url['id']}'");
			echo $url['id'];
		}
	}
}

//note 售后未完成任务发送消息提醒
function send_unfinishedsell(){
	$time = time();
	$marktime3 = 72*3600;
	$marktime7 = 7*24*3600;
	$marktime15 = 15*24*3600;
	$marktime30 = 30*24*3600;
	$sql = "select m.uid,m.bgtime,m.sid from web_members_search m left join web_member_admininfo a on m.uid=a.uid where m.s_cid in(20,30) and m.usertype='1' and a.finished='0' and m.bgtime>0 and m.endtime>='{$time}'";
	$res = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	//note 插入remark表、提示未完成步骤的
	$title = " 的三大步骤未完成。";
	$content3 = "  三天内的三大步骤未完成。";
	$content7 = "  一个星期内的三大步骤未完成。";
	$content15 = "  十五天内的三大步骤未完成。";
	$content30 = "  三十天内的三大步骤未完成。";
	$sql_rem = "INSERT INTO web_admin_remark SET ";
	foreach($res as $k=>$v){
		if($v['bgtime']>'1288886400'){
		$timeinterval = $time-$v['bgtime'];
		if($timeinterval>=$marktime3 && $timeinterval<$marktime7 && $timeinterval<$marktime15 && $timeinterval<$marktime30){
			$remindtime = $v['bgtime']+$marktime3+12*3600;
			$sql = "SELECT manage_list FROM web_admin_manage WHERE type=1 AND find_in_set({$v['sid']},manage_list) LIMIT 1";
			$manage_list = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
			if(!empty($manage_list)){
				//查此组成员中的组长
				$group_leader = implode(',',$GLOBALS['admin_aftersales_service']);
				$sql = "SELECT uid,groupid FROM web_admin_user WHERE uid IN({$manage_list['manage_list']}) AND groupid IN({$group_leader})";
				$admin_user = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
			}	
			if($remindtime>$time){
				$sql_remark=$sql_rem."sid='".$admin_user['uid']."',groupid='".$admin_user['groupid']."',title='{$v['uid']}".$title."',content='{$v['uid']}".$content3."',awoketime='{$remindtime}',send_id={$v['sid']}";
//				echo $sql_remark;
				$res_remark = $GLOBALS['_MooClass']['MooMySQL']->query($sql_remark);
			}
		}
		if($timeinterval>=$marktime7 && $timeinterval<$marktime15 && $timeinterval<$marktime30){
			$remindtime = $v['bgtime']+$marktime7+12*3600;
			//查售后主管
			$group_leader = implode(',',$GLOBALS['admin_service_after']);
			$sql = "SELECT uid,groupid FROM web_admin_user WHERE groupid IN({$group_leader})";
			$admin_user = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
			if($remindtime>$time){
				$sql_remark=$sql_rem."sid='".$admin_user['uid']."',groupid='".$admin_user['groupid']."',title='{$v['uid']}".$title."',content='{$v['uid']}".$content7."',awoketime='{$remindtime}',send_id={$v['sid']}";
//				echo $sql_remark."<br>";
				$res_remark = $GLOBALS['_MooClass']['MooMySQL']->query($sql_remark);
			}
		}
		if($timeinterval>=$marktime15 && $timeinterval<$marktime30){
			$remindtime = $v['bgtime']+$marktime15+12*3600;
			//查客服主管
			$group_leader = implode(',',$GLOBALS['admin_the_service_arr']);
			$sql = "SELECT uid,groupid FROM web_admin_user WHERE groupid IN({$group_leader})";
			$admin_user = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
			if($remindtime>$time){
				$sql_remark=$sql_rem."sid='".$admin_user['uid']."',groupid='".$admin_user['groupid']."',title='{$v['uid']}".$title."',content='{$v['uid']}".$content15."',awoketime='{$remindtime}',send_id={$v['sid']}";
//				echo $sql_remark."<br>";
				$res_remark = $GLOBALS['_MooClass']['MooMySQL']->query($sql_remark);
			}
		}
		if($timeinterval>=$marktime30){
			$remindtime = $v['bgtime']+$marktime30+12*3600;
			//查系统管理员
			$group_leader = implode(',',$GLOBALS['system_admin']);
			$sql = "SELECT uid,groupid FROM web_admin_user WHERE groupid IN({$group_leader})";
			$admin_user = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
			if($remindtime>$time){
				$sql_remark=$sql_rem."sid='".$admin_user['uid']."',groupid='".$admin_user['groupid']."',title='{$v['uid']}".$title."',content='{$v['uid']}".$content30."',awoketime='{$remindtime}',send_id={$v['sid']}";
//				echo $sql_remark;
				$res_remark = $GLOBALS['_MooClass']['MooMySQL']->query($sql_remark);
			}
		}
	}
	}
}
//统计客服联系会员的情况
function count_tele_info(){
	if(isset($_GET["T"]) && $_GET["T"]){
		$diftime=3600*24*$_GET["T"];
		$end_time=mktime(0,0,0,date('m',time()),date('d',time()),date('Y',time()))-$diftime;
		$begin_time=$end_time-3600*24-$diftime;
		$three_day_time=$end_time-3600*24*2;
		$seven_day_time=$end_time-3600*24*6;
	}else{
		$end_time=mktime(0,0,0,date('m',time()),date('d',time()),date('Y',time()));
		$begin_time=$end_time-3600*24;
		$three_day_time=$end_time-3600*24*2;
		$seven_day_time=$end_time-3600*24*6;
	}
	$time_between="b.dateline>".$begin_time." and b.dateline<".$end_time;
	//$time_array=array(1=>array($end_time-3600*24,$end_time),2=>array($end_time-3600*48,$end_time-3600*24),3=>array($end_time-3600*72,$end_time-3600*48),4=>array($end_time-3600*96,$end_time-3600*72),5=>array($end_time-3600*120,$end_time-3600*96),6=>array($end_time-3600*144,$end_time-3600*120),7=>array($end_time-3600*166));
	$time=$end_time-3600;

	$sql="SELECT * FROM {$GLOBALS['dbTablePre']}admin_manage WHERE type=1 and manage_list!=''";
	$datax = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
 
	foreach($datax as $key=> $datax_user){//获得组
		$group_user_name[]=$datax_user['manage_name'];
		//echo $datax_user['manage_list'];
		$admin_user_array=$datax_user['manage_list'];
		$admin_user_num_arr=explode(",",$datax_user['manage_list']);
		$admin_user_num=count($admin_user_num_arr);
		$group_user_count=0;
		$user_num=0;

		$sql="SELECT sum(member_count) sum FROM {$GLOBALS['dbTablePre']}admin_user where uid in ($admin_user_array)";
		$member_count=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
		$group_user_count=$member_count['sum'];//获得每个组总的会员数量人数

		//获得每个组今天分配会员数量
		$sql="SELECT sum(allot_member) sum FROM {$GLOBALS['dbTablePre']}admin_user where uid in ($admin_user_array) and allot_time>$begin_time";
		$allot_member_arr=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
		$allot_member=$allot_member_arr['sum'];

		$sql="SELECT count(1) num FROM {$GLOBALS['dbTablePre']}admin_deluser b where sid in (".$datax_user['manage_list'].") and ".$time_between;
		$del_user_num_arr=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
		$del_user_num=$del_user_num_arr['num'];// 获得各组删除会员数量

		$sql="SELECT  count(distinct b.uid) as tnum FROM {$GLOBALS['dbTablePre']}members_search m  left join {$GLOBALS['dbTablePre']}member_backinfo b on b.uid=m.uid  where m.sid in (".$datax_user['manage_list'].") and ".$time_between;
		$num=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
		$user_num= $num['tnum'];//获得每个组总的联系会员数

		$sql="SELECT  count(distinct b.uid) as tnum FROM {$GLOBALS['dbTablePre']}members_search m  left join {$GLOBALS['dbTablePre']}member_backinfo b on b.uid=m.uid  where effect_contact = 1 and phonecall=1 and m.sid in (".$datax_user['manage_list'].") and ".$time_between;
		$num_two=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
		$effect_num= $num_two['tnum'];//获得每个组总的通话的有效联系会员数
		 
		$sql="INSERT INTO {$GLOBALS['dbTablePre']}admin_telcount set type=1,uid='{$datax_user['id']}',username='{$datax_user['manage_name']}',member_count='{$group_user_count}',allot_member='{$allot_member}',kefu_num='{$admin_user_num}',tele_num='{$user_num}',del_user_num='{$del_user_num}',effect_num='{$effect_num}',dateline='{$time}'";
		$GLOBALS['_MooClass']['MooMySQL']->query($sql);
	}

	$sql="SELECT * FROM {$GLOBALS['dbTablePre']}admin_user WHERE groupid=67";
	$all_kefu = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	foreach($all_kefu as $kefu_inf){
		$del_user_num=0;
		$group_user_name[]= $kefu_inf['username'];
		//$all_user_count[$key]['all']=$kefu_inf['member_count'];//获得每个客服总的会员数量人数

		$sql="SELECT  count(b.uid) as tnum FROM {$GLOBALS['dbTablePre']}members_search m left join {$GLOBALS['dbTablePre']}member_backinfo b on b.uid=m.uid  where m.sid='{$kefu_inf['uid']}' and ".$time_between;
		//echo $sql;
		$num=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
		$all_user_count['havetel']= $num['tnum'];//获得每个组总的联系会员数

		$sql="SELECT  count(b.uid) as tnum FROM {$GLOBALS['dbTablePre']}members_search m left join {$GLOBALS['dbTablePre']}member_backinfo b on b.uid=m.uid  where b.effect_contact = 1 and b.phonecall=1 and m.sid='{$kefu_inf['uid']}' and ".$time_between;
		//echo $sql;
		$num_eff=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
		$all_user_count['effect_tel']= $num_eff['tnum'];//获得每个组总的有效联系会员数
		
		$v=$kefu_inf['uid'];
		$sql="SELECT count(1) num FROM {$GLOBALS['dbTablePre']}admin_deluser b where sid ='$v' and ".$time_between;
		$del_user_num_arr=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
		$del_user_num=$del_user_num_arr['num'];
		if($kefu_inf['allot_time']>$begin_time){
			$allot_member=$kefu_inf['allot_member'];
		}else{
			$allot_member=0;
		}
		$sql="INSERT INTO {$GLOBALS['dbTablePre']}admin_telcount set type=2,uid='{$kefu_inf['uid']}',username='{$kefu_inf['username']}',member_count='{$kefu_inf['member_count']}',allot_member='$allot_member',tele_num='{$all_user_count['havetel']}',effect_num='{$all_user_count['effect_tel']}',del_user_num='{$del_user_num}',dateline='{$time}'";
		$GLOBALS['_MooClass']['MooMySQL']->query($sql);
		//三天内联系会员数
		$sql="SELECT sum(tele_num) tele FROM {$GLOBALS['dbTablePre']}admin_telcount where uid='{$kefu_inf['uid']}' and dateline>'{$three_day_time}' and type=2";
		
		$three_day_num=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
		$sql="update  {$GLOBALS['dbTablePre']}admin_user set three_day='{$three_day_num['tele']}' where uid='{$kefu_inf['uid']}'";
		$GLOBALS['_MooClass']['MooMySQL']->query($sql);
		//七天内联系会员数
		$sql="SELECT sum(tele_num) tele FROM {$GLOBALS['dbTablePre']}admin_telcount where uid='{$kefu_inf['uid']}' and dateline>'{$seven_day_time}' and type=2";
		$seven_day_num=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
		$sql="update  {$GLOBALS['dbTablePre']}admin_user set seven_day='{$seven_day_num['tele']}' where uid='{$kefu_inf['uid']}'";
		$GLOBALS['_MooClass']['MooMySQL']->query($sql);
	}
}

//写发送短信日志
function write_log($sys_array){
	//print_r($sys_array);
	if($sys_array){
		foreach($sys_array as $v){
			inserttable("smslog_sys",$v);
		
		}
		
	}
}

//note 定时清除编辑图片临时文件
function clear_temp_pic(){
	$t = time() - 60*30;//30分钟
	$path = './data/upload/images/tmp/';
	if( $handle = opendir($path) ){
		while (false !== ($file = readdir($handle)))
		{
			if($file != "." && $file != ".." 
			   && file_exists($path.$file) 
			   && filemtime($path.$file) < $t){
				unlink($path.$file);
				echo $path.$file,'<br>';
			}
		}
	}
}

//count+1
function update_msg_count($id,$count){
$sql="UPDATE web_sendmsg_tmp SET count='$count' where id = '{$id}'";
if($GLOBALS['_MooClass']['MooMySQL']->query($sql)) return true;
}


//note 接收上行数据评分  5分钟
function  get_recvsms(){
	require dirname(__FILE__)."/nusoap.php";
	$client=new nusoap_client('http://61.145.229.29:9002/MWGate/wmgw.asmx?wsdl');
	$userid='J30087';
	$password='070003';

	//将对应参数值赋到下面数组以供调用
	$parameters = array(
		'userId'=>$userid,
		'password'=>$password
	);
	$sms=$client->call('MongateCsGetSmsExEx',$parameters);
//	$arr = array(1,2,3,4,5);

//	$sms = Array('string' => '2011-01-15,21:01:21,15055185301,1065712038070003,*,2');
	
	/*$sms = Array('string' => Array(
            '0' => '2011-01-21,16:14:11,15055185301,1065712038070003,*,6',
            '1' => '2011-01-21,16:14:11,13695519841,1065712038070003,*,非常不满意',
            '2' => '2011-01-21,16:14:11,15955952580,1065712038070003,*,不好说'
        ));*/

	echo "<pre>";
	print_r($sms);
	echo "</pre>";

	if($sms){
		$strarr = array();
		if(count($sms['string']) == '1'){
			$strarr[] = $sms['string'];
		}else{
			$strarr = $sms['string'];
		}
		foreach($strarr as $key=>$val){
			$uplink = explode(',',$val);
			//$uplink[0]——日期、$uplink[1]——时间、$uplink[2]——手机号、$uplink[5]——回复内容
//			if(in_array($uplink['5'],$arr)){
				$reptime = strtotime($uplink['0'].$uplink['1']);
				$tel = $uplink['2'];
				$rep = $uplink['5'];
				$repnum = $uplink['3'];
				$sqlinfo = "select uid,sid from web_members_search where telphone='{$tel}' and is_lock=1 ";//同一个手机号多个会员注册的情况，封锁ID
				$resinfo = $GLOBALS['_MooClass']['MooMySQL']->getOne($sqlinfo);
				if($resinfo){
					$sid = $resinfo['sid'];
					$uid = $resinfo['uid'];
					if($rep=='5'){
						$sql = "SELECT manage_list FROM web_admin_manage WHERE type=1 AND find_in_set({$sid},manage_list) LIMIT 1";
						$manage_list = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
						if(!empty($manage_list)){
							//查此组成员中的组长
							$group_leader = implode(',',$GLOBALS['admin_leader']);
							$sql = "SELECT uid,groupid FROM web_admin_user WHERE uid IN({$manage_list['manage_list']}) AND groupid IN({$group_leader})";
							$admin_user = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
						}
						$leaderid = $admin_user['uid'];

						$title = '会员 '.$uid.' 对客服 '.$sid.' 的服务评价：非常不满意';
						$awoketime = time()+3600;
						$sql_remark = "insert into web_admin_remark set sid='{$leaderid}',title='{$title}',content='{$title}',awoketime='{$awoketime}',dateline='{$reptime}'";
						$res = $GLOBALS['_MooClass']['MooMySQL']->query($sql_remark);
					}
					//echo $rep."22<br />";
					if(!ereg("^[1-5]{1}$",$rep)){
						$recomment_arr=array(1=>"非常满意",3=>"一般",5=>"非常不满意",4=>"不满意",2=>"满意");						
						foreach($recomment_arr as $key=>$recomment){							
							if(strpos($rep,$recomment)!==false){
								$rep=$key;								
							}							
						}						
						if(ereg("^[1-5]{1}$",$rep)){
							//改变会员危险等级
							//echo $rep;
							$sql_level = "update web_member_admininfo set danger_leval='{$rep}' where uid='{$uid}'";
							$res = $GLOBALS['_MooClass']['MooMySQL']->query($sql_level);

							$sql="SELECT id FROM web_uplinkdata where sid='{$sid}' and uid='{$uid}'";
							$is_have=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
							if($is_have['id']){
								$sql = "update web_uplinkdata set telphone='{$tel}',rep='{$rep}',reptime='{$reptime}',repnum='{$repnum}' where id={$is_have['id']}";
								$res = $GLOBALS['_MooClass']['MooMySQL']->query($sql);
							}else{
								$sql = "insert into web_uplinkdata set sid='{$sid}',uid='{$uid}',telphone='{$tel}',rep='{$rep}',reptime='{$reptime}',repnum='{$repnum}'";
								$res = $GLOBALS['_MooClass']['MooMySQL']->query($sql);								
							}
		//					echo $sql."<br>";
						}else{
							$sql = "insert into web_uplinkcontent set sid='{$sid}',uid='{$uid}',telphone='{$tel}',rep='{$rep}',reptime='{$reptime}',repnum='{$repnum}'";
							$res = $GLOBALS['_MooClass']['MooMySQL']->query($sql);
							
							//*********真爱一生备注*********
					        $title = '您的会员'.$uid.'给您发送了短信';//.$order['subject'];
					        $awoketime = time()+3600;
					        $sql_remark = "insert into {$dbTablePre}admin_remark set sid='{$sid}',title='{$title}',content='{$title}',awoketime='{$awoketime}',dateline='{$reptime}'";
					        $res = $GLOBALS['_MooClass']['MooMySQL']->query($sql_remark);
					        //**********end**********
						}

					}else{
						//改变会员危险等级
						$sql_level = "update web_member_admininfo set danger_leval='{$rep}' where uid='{$uid}'";
						$res = $GLOBALS['_MooClass']['MooMySQL']->query($sql_level);

						$sql="SELECT id FROM web_uplinkdata where sid='{$sid}' and uid='{$uid}'";
						$is_have=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
						if($is_have['id']){
							$sql = "update web_uplinkdata set telphone='{$tel}',rep='{$rep}',reptime='{$reptime}',repnum='{$repnum}' where id={$is_have['id']}";
							$res = $GLOBALS['_MooClass']['MooMySQL']->query($sql);
						}else{
							$sql = "insert into web_uplinkdata set sid='{$sid}',uid='{$uid}',telphone='{$tel}',rep='{$rep}',reptime='{$reptime}',repnum='{$repnum}'";
							$res = $GLOBALS['_MooClass']['MooMySQL']->query($sql);								
						}
	//					echo $sql."<br>";
					}
				}
//			}
			unset($uplink);
		}
		echo "<br>ok";
		unset($strarr);
	}
	unset($sms);
}

//三类未跟进的
function contactNo(){
    global $_MooClass,$dbTablePre;
	
	$H=date('H');
	if($H<=16 || $H>=21) exit;
	
	$time=time();
     //今天
	$tEnd=strtotime ("+1 day");
	$tStart=strtotime(date('Y-m-d')); //相对今天的昨天

    
	$where_y='';
	if(!empty($tStart)){
		$where_y = " and ma.dateline<'{$tStart}'";//截止昨日
    }
	
	$where_byt ="";
    if(!empty($tEnd) && !empty($tStart)){
		$where_byt = " and ma.dateline<'{$tEnd}' and ma.dateline>='{$tStart}'";//昨天今日之间
    }
	

	$res=array();
	$sql="select uid from web_admin_user where groupid=67";
	$res=$_MooClass['MooMySQL']->getAll($sql,0,0,0,true);
	foreach($res as $k=>$v){
	    if($v['uid']==1) continue;
	    $res_y=$res_t=$r_y=$v_y=array();
		$sql="select m.uid,ma.dateline,ma.effect_grade from {$dbTablePre}member_backinfo ma left join {$dbTablePre}members_search m on ma.uid=m.uid where   m.sid={$v['uid']}   {$where_y} order by m.uid,ma.dateline ";
		$res_y = $_MooClass['MooMySQL']->getAll($sql,0,0,0,true);
		
		foreach($res_y as $v_y){
			$r_y[$v_y['uid']]=$v_y['effect_grade'];
		}
		
		
		$sql="select effect_grade,m.uid as uid from {$dbTablePre}member_admininfo ma left join {$dbTablePre}members_search m on ma.uid=m.uid where   m.sid={$v['uid']}   {$where_byt}";
		$res_t_ = $_MooClass['MooMySQL']->getAll($sql,0,0,0,true);
		
		foreach($res_t_ as $v_t_){
			if(empty($v_t_['effect_grade'])) continue;
			$r_t_[$v_t_['uid']]=$v_t_['effect_grade'];
		}
		
		$r=array();
		$r=array_diff_key($r_y,$r_t_);
        		
	    unset($res_t_);
		unset($v_t_);
		
		$v_=array();
		foreach($r as $k_=>$v_){
		    if($v_!=4) continue;
			$title="未跟进的3类会员";
			$sid = $v['uid'];
			$content = "<a href='#' onclick='parent.addTab(\'{$k_}资料\',\'index.php?action=allmember&h=view_info&uid={$k_}\',\'icon\')'>{$k_}</a>";
			$content=addslashes($content);
			$awoketime = $time+600;
			$sql_remark = "insert into web_admin_remark set sid='{$sid}',title='{$title}',content='{$content}',awoketime='{$awoketime}',dateline='{$time}'";
		    $_MooClass['MooMySQL']->query($sql_remark);
		}
	}
}

//清空客服右下角提醒
function clearRemark(){
    global $_MooClass,$dbTablePre;
	$sql="truncate table web_admin_remark where sid=1 or status=1";
	$_MooClass['MooMySQL']->query($sql);
}



//404错误页面
if(MooGetGPC('h','string') == 'none'){
	include MooTemplate('public/wrong400', 'module');
	exit;
}

// 参数 字符串加密类型为:time() . "|" . MOOPHP_AUTHKEY
//echo MooAuthCode(time() . "|" . MOOPHP_AUTHKEY, "ENCODE");exit;

$param=MooGetGPC('param','string');
if (!isset($param) && $param != '_ikol_') {
	echo "AUTH ERROR";
	exit;
}

$h = MooGetGPC('h','string');

switch ($h) {
	case "recommd_mail":
		set_time_limit(0);
		crontabRecommdMail();
		break;
	case "truncate_search_tbl";//定时清空搜索结果缓存表  没用了
//		if(!is_object($_MooClass['MooMySQL'])){
//			// 包含配置文件
//			require_once('./../../config.php');
//			//note 加载框架
//			require './../../framwork/MooPHP.php';
//		}
//		$_MooClass['MooMySQL']->query("TRUNCATE TABLE `web_member_tmp`");
		
		break;
	//删除表mail_queue中5天前的
	case "delmail":
		crontab_del_mail();
		break;
	//注册统计
	case 'reg_count':
		reg_count();
		break;	
	//生日提醒
	case 'birthday_remark':
		birthday_remark();
	    break;
	//到期高级会员提醒
	case 'will_expire_vip':
		will_expire_vip();
	    break;
	case 'update_mail_status':
		update_mail_status();
		break;
	case 'count_url':
		do_count_url();
		break;
	//统计客服联系会员的情况
	case 'count_tele_info':
		count_tele_info();
		break;
	case 'sendmsg_crontab':
		sendmsg_crontab();
	    break;
	//note  定时排序搜索表
	case 'chat':
		get_noanswer_message();
		break;
	case 'clear_temp_pic':
		clear_temp_pic();
		break;
	//note 售后未完成任务的发送消息提醒
	case 'send_unfinishedsell':
		// 包含配置文件
		require_once (dirname(__FILE__)."./../../backend/include/config.php");
		send_unfinishedsell();
		break;
	//note 接收上行数据评分
	case 'recvsms':
		// 包含配置文件
		require_once (dirname(__FILE__)."./../../backend/include/config.php");
		get_recvsms();
		break;
	case 'contactNo':
	    contactNo();
		break;
    case 'clearRemark':
	    clearRemark();
		break;
	default:
		set_time_limit(0);
		sendMailQueue();
		break;
}


?>
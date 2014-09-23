<?php
include "module/andriod/function.php";
include "module/search/function.php";

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


function basic_search_page($gender, $age_start, $age_end, $work_province, $work_city, $marriage, $salary, $education, $height1, $height2) {
	global $_MooClass, $dbTablePre;
    global $user_arr, $userid,$last_login_time,$isdelcond,$isselectarea,$isdispmore,$isresult;
    $currenturl = 'http://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
    $currenturl2 = preg_replace("/(&page=\d+)/", "", $currenturl);
//	echo $gender.'aaa'.$age_start.'1111q'.$age_end;exit;
    //分页相关参数
    $pagesize = 16;
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
	
    $error = array();
	$cond = array();
	$cond[] = array('is_lock','1',false);
	$cond[] = array('usertype','1',false);
//	if($photo) $cond[] = array('images_ischeck','1',false);
	if(is_array($blacklist) && !empty($blacklist))  $cond[] = array('@id',implode('|',$blacklist),true);
	if($age_start && $age_end){
		$year = date('Y',time());
		$cond[] = array('birthyear',array(($year-$age_end),($year-$age_start)),false);
	}
	if($work_province) $cond[] = array('province',$work_province,false);
	elseif(isset($user_arr['province']) && $user_arr['province'] && !$work_city) $cond[] = array('province',$user_arr['province'],false);
	if($work_city) $cond[] = array('city',$work_city,false);
	elseif(isset($user_arr['city']) && $user_arr['city'] && !$work_province) $cond[] = array('city',$user_arr['city'],false);
	
	if($marriage){
		$cond[] = array('marriage',$marriage,false);
	}
	if($salary){
		$cond[] = array('salary',$salary,false);
	}
	if($education){
		$cond[] = array('education',$education,false);
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
	$rs = sphinx_search($index,$cond,$sort_str,$page,$pagesize);
	$total = 0;
	if(!$rs || is_array($rs) && empty($rs)){//没有结果
        $error = "您指定的搜索不存在";
        echo return_data($error);exit;
	}else{
		$data = $rs['user'];
		$total = $rs['total'];
		if($total > 600) $total = 600;
		$user_list = implode(',',$data);
		if(empty($data)) {
			
			$error = "您指定的搜索不存";
			
			echo return_data($error,false);exit;
			//MooMessage('您指定的搜索不存在或已过期', 'index.php?n=search', '03');
//			include MooTemplate('public/search_error', 'module');
	        
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
			if (empty($user)) {
		    	$error = "找不到匹配的结果";
				echo return_data($error,false);exit;
		    }
		//if($user1) $user1 = userbasicadd($user1);
	
	    //note 找不到匹配的结果返回单独提示页面
//	    if(empty($error) && $userid){

        foreach ($user as $k=>$u){
        	$mainimg = MooGetphoto($u['uid'],$style = "com");
        	$users[] = array('uid'=>$u['uid'],'nickname'=>$u['nickname'],'birthyear'=>$u['birthyear'],
        	'height'=>$u['height'],'salary'=>$u['salary'],'province'=>$u['province'],'city'=>$u['city'],'mainimg'=>$mainimg,'gender'=>$u['gender']);

        }		 
		if(empty($error)){	 
			$return = $user;   	
	    	echo return_data($return);exit;
	    }else{	   
		    $error = json_encode($error);
		    echo return_data($error);exit;
	    }
	}
}

//可能喜欢的人
function ableLike($num = '12') {
    global $_MooClass,$dbTablePre,$user_arr,$memcached,$userid;
    //note 你喜欢的人，以同地区条件查询,如果城市是0,就查询省，如果省为0就查询国家
    //note 在后面注册功能完善后，有了国家，就要判断国家
    //$user1 = UserInfo();
	$uid =  $_GET['uid'] = isset($_GET['uid'])?$_GET['uid']:'';
	if($uid){
		$userid = $mem_uid = $memcached->get('uid_'.$uid);
	}
	$and_uuid = isset($_GET['uuid'])? $_GET['uuid'] : '';
	$checkuuid = check_uuid($and_uuid,$userid);
    if(!$checkuuid){
    	$error = "uuid_error";
    	echo return_data($error,false);exit;	
    }
    $limit = 12;
    $page = $_GET['page'] = empty($_GET['page'])? 0 : $_GET['page'];
    $user_arr = MooMembersData($userid);
    $user1 = $user_arr;
    $gender = $user1['gender'] == '1' ? '0' : '1';
    if(18<=(date("Y")-$user1['birthyear'])&&(date("Y")-$user1['birthyear'])<=30&&$user1['birthyear']!=''&&$user1['birthyear']!=0){$age=1;}
    if(31<=(date("Y")-$user1['birthyear'])&&(date("Y")-$user1['birthyear'])<=35&&$user1['birthyear']!=''&&$user1['birthyear']!=0){$age=2;}
    if(36<=(date("Y")-$user1['birthyear'])&&(date("Y")-$user1['birthyear'])<=40&&$user1['birthyear']!=''&&$user1['birthyear']!=0){$age=3;}
    if(41<=(date("Y")-$user1['birthyear'])&&(date("Y")-$user1['birthyear'])<=50&&$user1['birthyear']!=''&&$user1['birthyear']!=0){$age=4;}
    if(51<=(date("Y")-$user1['birthyear'])&&(date("Y")-$user1['birthyear'])<=60&&$user1['birthyear']!=''&&$user1['birthyear']!=0){$age=5;}
    if(61<=(date("Y")-$user1['birthyear'])&&$user1['birthyear']!=''&&$user1['birthyear']!=0){$age=6;}
    if($user1['birthyear']==''||$user1['birthyear']==0){$age=1;}
    if($user1["province"]==''||$user1["province"]==0||$user1["province"]==-1){
        $user1["province"]=10103000;
    }
    $file = $age . "_" . $gender . "_" . $user1["province"] . "_" . $user1["city"];
//     $cache_file = MOOPHP_DATA_DIR.'/cache/cache_' . $file . '.php';
    $data  = $memcached->get($file);
    if (!$data) {
        updateVoteFeel("","",$user1['province'],$user1['city'],$age,$gender);
        $data = $memcached->get($file);
    }
    
    if(empty($data)) {
    	
    	$error = "empty";
    	echo return_data($error,false);exit;
    }
    $offset = ($page-1)*12;
    
    $count = count($data);
    if($limit > $count){
    	$limit = $count;
    }
//		shuffle($data);
	        $data=array_slice($data, $offset , $limit);    
	        for($j=0;$j<12;$j++){
	            //if(MOOPHP_ALLOW_FASTDB){ 
	              if(isset($data[$j])) $able_like[$j] = MooMembersData($data[$j]);//MooFastdbGet('members_search','uid',$data[$j]);
	            //}
	        }  
     foreach ($able_like as $k=>$u){
     	$mainimg = MooGetphoto($u['uid'],$style = "com");
        $users[] = array('uid'=>$u['uid'],'nickname'=>$u['nickname'],'birthyear'=>$u['birthyear'],
        'height'=>$u['height'],'salary'=>$u['salary'],'province'=>$u['province'],'city'=>$u['city'],'mainimg'=>$mainimg,'gender'=>$u['gender']);

     }
    echo return_data($users);exit;
}




function advance_search_page($gender, $age_start, $age_end, $work_province, $work_city, $marriage, $salary, $education, $height1, $height2, $weight1, $weight2, $body, $smoking, $drinking, $occupation, $house, $vehicle, $corptype, $children, $wantchildren, $home_townprovince, $home_towncity, $nation, $animalyear, $constellation, $bloodtype, $religion, $family, $language, $photo) {
    global $_MooClass, $dbTablePre;
    global $user_arr, $userid,$isdelcond,$isselectarea,$isdispmore,$isresult;
    
    //kaishi
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
        
        //jieshu
    
    
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
//			$tmp_user = array();
//			foreach($data as $k=>$v){
//				foreach($user as $key=>$val){
//					if($v == $val['uid']){
//						$tmp_user[] = $user[$key];
//						break;
//					}
//				}
//			}
//			$user = $tmp_user;
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





//new
function newse(){

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
	        
	      	//note 身高
	        $height1 = isset($_GET['height1']) ? trim(MooGetGPC('height1', 'integer','G')) : 0;
			$height2 = isset($_GET['height2']) ? trim(MooGetGPC('height2', 'integer','G')) : 0;
			
			
			$marriage = 0;
	        $salary = 0;
	        $education = 0;
	        if(isset($_GET['marriage'])) $marriage = $_GET['marriage'] == '' ? 0 : $_GET['marriage'];
	        if(isset($_GET['salary'])) $salary = $_GET['salary'] == '' ? 0 : $_GET['salary'];
	        if(isset($_GET['education'])) $education = $_GET['education'] == '' ? 0 : $_GET['education'];
			
	        //note 是否显示相片
	       // $photo = isset($_GET['photo']) ? trim(MooGetGPC('photo', 'integer', 'G')) : '0';
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
	        
	        
	        if($marriage == -1) $marriage = 0;
	        if($salary == -1) $salary = 0;
	        if($education == -1) $education = 0;
	        if($height1 == -1) $height1 = 0;
	        if($height2 == -1) $height2 = 0;
	       // if($photo == -1) $photo = 0;
	        
	        quick_search_page($gender, $age_start, $age_end, $work_province, $work_city, $photo);

}

function quick_search_page($gender, $age_start, $age_end, $work_province, $work_city, $marriage, $salary, $education, $height1, $height2,$photo) {
	global $_MooClass, $dbTablePre;
    global $user_arr, $userid,$last_login_time,$isdelcond,$isselectarea,$isdispmore,$isresult;
    
    $currenturl = 'http://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
    $currenturl2 = preg_replace("/(&page=\d+)/", "", $currenturl);
	$pagesize = 16;
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
	//
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
	
	
	if($marriage){
		$cond[] = array('marriage',$marriage,false);
	}
	if($salary){
		$cond[] = array('salary',$salary,false);
	}
	if($education){
		$cond[] = array('education',$education,false);
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
	
	$rs = sphinx_search($index,$cond,$sort_str,$page,$pagesize);
	
	   
	
	
	if(!$rs || is_array($rs) && empty($rs)){//没有结果
		//MooMessage("您指定的搜索不存在或已过期", "index.php?n=search", '03');
			$error = "找不到匹配的结果";
    		echo return_data($error,false);exit;
	}else{
		$data = $rs['user'];

		$total = $rs['total'];
		if($total > 600) $total = 600;
		$user_list = implode(',',$data);
		if(empty($data)) {
			//MooMessage("您指定的搜索不存在或已过期", "index.php?n=search", '03');
			$error = "找不到匹配的结果";
    		echo return_data($error,false);exit;
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
			
		
     	foreach ($user as $k=>$u){
     	$mainimg = MooGetphoto($u['uid'],$style = "com");
        $users[] = array('uid'=>$u['uid'],'nickname'=>$u['nickname'],'birthyear'=>$u['birthyear'],
        'height'=>$u['height'],'salary'=>$u['salary'],'province'=>$u['province'],'city'=>$u['city'],'mainimg'=>$mainimg,'gender'=>$u['gender']);

     }
    	echo return_data($users);exit;
			/*
			foreach($user as $va){
				echo $va['nickname'].'<br />';
			}
			exit;*/
			//$error = "找不到匹配的结果";
		/*	
     	$str = 'gender:'.$gender.'age_start:'.$age_start.'age_end:'.$age_end.'work_province:'.$work_province.'work_city:'.$work_city.'marriage:'.$marriage.'salary'
     	.$salary.'education:'.$education.'height1:'.$height1.'height2:'.$height2;
     	
     	$return[] = $str;
     	$return[] = $user;
     	*/
    	//echo return_data($user,true);exit;
		}
	    //note 找不到匹配的结果返回单独提示页面
	    if (empty($user) && empty($user1)) {
	    	
	    	$error = "找不到匹配的结果";
    		echo return_data($error,false);exit;
	    }
	 
	}
	
}







	        /**
 * 新快速查询
 * 采用sphinx查询
 * @author likefei
 * @date 2011-11-14
 */
function quick_search_page1($gender, $age_start, $age_end, $work_province, $work_city, $marriage, $salary, $education, $height1, $height2) {
	global $_MooClass, $dbTablePre;
    global $user_arr, $userid,$last_login_time,$isdelcond,$isselectarea,$isdispmore,$isresult;
    
    $currenturl = 'http://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
    $currenturl2 = preg_replace("/(&page=\d+)/", "", $currenturl);
	$pagesize = 12;
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
	//
	$cond[] = array('is_lock','1',false);
	//if($photo) $cond[] = array('images_ischeck','1',false);
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

//
/*
 * ******************************************************控制层****************************************/
global $memcached;
/*
	$and_uuid = $_GET['uuid'];
	$uid =  $_GET['uid'] = isset($_GET['uid'])?$_GET['uid']:'';
	if($uid){
		$userid = $mem_uid = $memcached->get('uid_'.$uid);
	}
	$checkuuid = check_uuid($and_uuid,$userid);
    if(!$checkuuid){
    	$error = "uuid_error";
    	echo return_data($error,false);exit;	
    }
if (!in_array($_GET['h'], array('basicsearch', 'ablelike')) && $_GET['h'] != '') {
    if (!$userid) {
    	$error = "未登录";
        echo return_data($error,false);exit;
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
*/
//note 快速查询表单处理
$h = $_GET['h'] = isset($_GET['h'])?$_GET['h']:'';
switch ($h){
	case 'basicsearch':
		
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
	        /*
	        if($age_start == -1) $age_start = 0;
	        if($age_end == -1) $age_end = 0;
	        if($work_province == -1) $work_province = 0;
	        elseif($work_province == -2) $work_province = 2;
	        if($work_city == -1) $work_city = 0;
	        */
	        //note 身高
	        $height1 = isset($_GET['height1']) ? trim(MooGetGPC('height1', 'integer','G')) : 0;
			$height2 = isset($_GET['height2']) ? trim(MooGetGPC('height2', 'integer','G')) : 0;
			
			
	        $marriage = 0;
	        $salary = 0;
	        $education = 0;
			//月薪
			if(isset($_GET['salary'])) $salary = $_GET['salary'] == '' ? 0 : $_GET['salary'];
			//婚史
			if(isset($_GET['marriage'])) $marriage = $_GET['marriage'] == '' ? 0 : $_GET['marriage'];
			//学历
			if(isset($_GET['education'])) $education = $_GET['education'] == '' ? 0 : $_GET['education'];
			
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
/*
			//性别
			$gender = isset($_GET['gender']) ? trim(MooGetGPC('gender', 'integer', 'G')) : 0;
			
			//年龄
			$age_start = isset($_GET['age_start']) ? trim(MooGetGPC('age_start'),'integer','G'): 0;
//			$age_start = 20;
//			$age_end=60; 
			$age_end = isset($_GET['age_end']) ? trim(MooGetGPC('age_end','integer','G')): 0;
			//工作地区
			$work_province = isset($_GET['workprovince']) ? trim(MooGetGPC('workprovince', 'integer', 'G')) : 0;
	        $work_city = isset($_GET['workcity']) ? trim(MooGetGPC('workcity', 'integer', 'G')) : 0;
	         //note 身高
	        $height1 = isset($_GET['height1']) ? trim(MooGetGPC('height1', 'integer','G')) : 0;
			$height2 = isset($_GET['height2']) ? trim(MooGetGPC('height2', 'integer','G')) : 0;
			$marriage = 0;
	        $salary = 0;
	        $education = 0;
			//月薪
			if(isset($_GET['salary'])) $salary = $_GET['salary'] == '' ? 0 : $_GET['salary'];
			//婚史
			if(isset($_GET['marriage'])) $marriage = $_GET['marriage'] == '' ? 0 : $_GET['marriage'];
			//学历
			if(isset($_GET['education'])) $education = $_GET['education'] == '' ? 0 : $_GET['education'];
			
				        if($work_province == -1) $work_province = 0;
	        elseif($work_province == -2) $work_province = 2;
	        if($work_city == -1) $work_city = 0;
	        if($marriage == -1) $marriage = 0;
	        if($salary == -1) $salary = 0;
	        if($education == -1) $education = 0;
	        if($height1 == -1) $height1 = 0;
	        if($height2 == -1) $height2 = 0;
	        
	        */
			quick_search_page($gender, $age_start, $age_end, $work_province, $work_city, $marriage, $salary, $education, $height1, $height2,$photo);
	        
	       // basic_search_page($gender, $age_start, $age_end, $work_province, $work_city, $marriage, $salary, $education, $height1, $height2);

		break;
	//默认搜索
	case 'ablelike' :
		ableLike($num=12);
		break;
}
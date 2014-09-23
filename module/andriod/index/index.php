<?php

include "module/index/function.php";
include "module/andriod/function.php";




/**
 * 登录首页
 * 描述：
 * 
 */
function index_index() {
	
	
	global $_MooClass,$dbTablePre,$userid,$user_arr,$memcached,$uuid;
	$womenList  =  array('30407189','21186208','21199933','20686096','20223069','20069562','21156987','21023025','21206132','21201459','21161703','20619161','20301718','20719091','21740236','20681292','21424242','20554506','20111310','20660269','30632759','30631960','30630519','30608962','30387074','30378615','30360483','30300309','30252809','30214641');
	$manList    =  array('30391348','21793878','30129941','21514524','30087732','30621435','30071838','30610492','30619608','30080561','30403838','30211025','30069490','30359171','30368726','30405061','30192000','30069742','30141451','30052221','30253012','30016533','30286293','30247063','30403530','30365362','21767568','30341955','21359517','21708383','30041513');
	
	$uid =  $_GET['uid'] = isset($_GET['uid'])?$_GET['uid']:'';
	if($uid){
		$userid = $mem_uid = $memcached->get('uid_'.$uid);
		$user_arr = MooMembersData($userid);
	}
	
	
	if($userid){
		$and_uuid = isset($_GET['uuid'])? $_GET['uuid'] : '';
		$checkuuid = check_uuid($and_uuid,$userid);
	    if(!$checkuuid){
	    	$error = "uuid_error";
	    	echo return_data($error,false);exit;	
	    }
	}
	
	
	if($userid){
		$gender = $user_arr['gender'] == 1 ? 0 : 1;
	}
	else{
		
		$gender = $_GET['gender'];
		
	}
	
	
	
	
	
	
	
	
	
	
	$sql_condition_province=$sql_condition_city=$province='';
	$currentdistrict = '0';$arr_diamond_female=$arr_diamond_male=null;
	$provice_list=$city_list=$province_othercachefile=null;
	$s_cid = 40;
	
	

	//note 先初始化用户信息
	//MooUserInfo();
	//-------会员推荐------//
	$sex = !MooGetGPC('h','integer','G') ? 0 : 1;//note 性别
	$workprovince = MooGetGPC('workprovince','integer','G');//工作省份
	
	$workcity =  MooGetGPC('workcity','integer','G');//工作城市

	if(in_array( $workprovince, array(10101201,10101002)))
	{
		//note 修正广东省深圳和广州的区域查询 2010-09-04
		$workcity = $workprovince;
		$workprovince = 10101000;
	}

	$age1 = MooGetGPC('age1','integer','G');//最小年龄
	$age2 = MooGetGPC('age2','integer','G');//最大年龄
	$gendersha = MooGetGPC('gender','integer','G');
	
	$userList = $userList2 = array();
	$index_total_user = 24;//首页总用户数
	//note 当前年份
	$year = date('Y');
	
	//登录用户查找性别
	$sql_gender = '';
	
	//$between = ' birthyear between ' . ($year-28).' and '.($year-22);
	
	//性别取反
	if($GLOBALS['MooUid']){
		$gender1 = $user_arr['gender'] == 1 ? 0 : 1;
		$sql_gender = " and s.gender='$gender1'";
		$sql_condition_province .= $sql_gender;
		$sql_condition_city .= " and s.gender='$gender1'";
	}
	
	$date_time=date('d');
	$month_time=date('m');
	$reguser_num=30000+$month_time*1100+$date_time*191;
	//新加滚动文字
	/*$time_list = time();
	$sql = "select content from {$dbTablePre}text_show where start_time<'$time_list' and end_time>'$time_list' order by reg_time desc limit 0,15";
	$text_list = $_MooClass['MooMySQL']->getAll($sql);*/
	//显示男姓or显示女生搜索
	
	if(isset($workprovince) && isset($workcity) && isset($age1) && $age2){
		//$sql = "select * from {$dbTablePre}members where gender=$sex and images_ischeck=1 ";
		include_once("./module/crontab/crontab_config.php");
		//note 从快速搜索表中查询
		if($sex == '0'){
			$sql = "select uid from {$dbTablePre}members_search where  images_ischeck=1 and gender='0'";				
		}
		if($sex == '1'){
			$sql = "select uid from {$dbTablePre}members_search where images_ischeck=1 and gender='1'";
		}
		
		if($workprovince != 0 && $workcity == 0){//仅选择了省
			$sql .= " and province=$workprovince";
		}elseif($workprovince != 0 && $workcity != 0){ //选择了省市
			$sql .= " and province=$workprovince and city=$workcity";
		}
		if($age1 != '0' && $age2 != '0'){
			if($age1 < $age2){
				//查找数据的年龄条件
				$between = ($year-$age2).' and '.($year-$age1);
				$sql .= " and birthyear between $between";
			}else{
				//查找数据的年龄条件
				$between = ($year-$age1).' and '.($year-$age2);
				$sql .= " and birthyear between $between";
			}
		}
		
		$sql .= " and is_lock = 1";
		//$sql .= " and is_lock = 1 limit 12";
		$sql .= " order by city_star desc,s_cid asc,bgtime desc ,pic_num desc limit 20";
         //var_dump($sql);exit;
		//note 查询出来的用户id写入缓存
		$md5 = md5($sql);
		$index_search = ("type=query/name=userid_{$md5}/sql=$sql/cachetime=86400");
	
		$_MooClass['MooCache']->getBlock($index_search);
		$userid = $GLOBALS['_MooBlock']["userid_{$md5}"];
		
		//查询为空则跳转到首页
		if(empty($userid)) {
			MooMessage("非常抱歉!没有您所找的会员！", 'index.php','01');
			exit;
		}
		//note uid数组转换成字符串	
		$index_img_num = 6;
		$index_img = index_img($currentdistrict,$index_img_num);
		$small_img_num = 3;
		$small_img = small_img($currentdistrict,$small_img_num,$workprovince);
		$user_arr1 = array();
		foreach ($userid as $v1){
			$user_arr1[] = $v1['uid'];
		}
		//note 获得用户id
		$userid_list =  implode(",",$user_arr1);
		$sql = "select s.uid,s.gender,s.images_ischeck,s.nickname,s.province,s.city,s.birthyear,s.s_cid,s.city_star,b.mainimg from {$dbTablePre}members_search s left join {$dbTablePre}members_base b on s.uid=b.uid where s.uid in ($userid_list) order by s.city_star desc, s.s_cid asc";
		
		//note 查询出来的用户信息写入缓存
		$md5 = md5($sql);
		$index_search = ("type=query/name=userList_{$md5}/sql=$sql/cachetime=86400");
		
		$_MooClass['MooCache']->getBlock($index_search);
		$userList = $GLOBALS['_MooBlock']["userList_{$md5}"];
		
	}else{
		//默认进入
		//包含配置文件
		include_once("./module/crontab/crontab_config.php");
		$cur_ip = GetIP();

//		$cur_ip = "61.190.44.98";
//		$cur_ip = "202.120.2.200";//上海ip
//		$cur_ip = "218.202.206.102";//新疆ip
		//$cur_ip = "218.70.178.197"; //重庆
//		$cur_ip="221.130.166.242";
        //$cur_ip="113.89.70.73"; //S深圳
		
		MooPlugins('ipdata');
		$ip_arr = convertIp($cur_ip);
	
		//得到省份对应的数值，查库
		$province = $city = "";
		foreach($provice_list as $key => $val){
			if(strstr($ip_arr,$val)){
				$province = $key;
				$currentdistrict=$val;
				break;
			}
		}


		if(isset($_GET['province'])){
			foreach($provice_list as $key => $val){
				if($_GET['province']==$key){
					$currentdistrict=$val;
					$province = $key;
					break;
				}
            }
			
	    }
	    $test = array();
	    if(isset($currentdistrict)){
			$index_img_num = 6;
			$index_img = index_img($currentdistrict,$index_img_num);
			$small_img_num = 3;
			$small_img = small_img($currentdistrict,$small_img_num,$province);
			$test = test($currentdistrict);
	    }
		
		//得到市对应的城市代号
		
		foreach($city_list as $city_key => $city_val){
		
			if(strstr($ip_arr,$city_val)){
				$city = $city_key;
				break;
			}
		}
		
		if(isset($_GET['province'])){
			//默认快速查询没有进入
			if(isset($_GET['city2'])?$_GET['city2']:''){
				$city = MooGetGPC('city2','integer','G');
			}else{
				$city = '0';
			}
			$province = MooGetGPC('province','integer','G');
			
			//note 修正广东省深圳和广州的区域查询
			if(in_array($province, array(10101201,10101002))) {
				$city = $province;
				$province = 10101000;
			}
			
			
			//修正直辖市查询
			if(in_array($province, array('10102000', '10103000', '10104000', '10105000'))) {
				$city = '0';
			}
    	$sql = "SELECT * FROM {$dbTablePre}index_cachefile WHERE province='{$province}' AND city='{$city}'";
		
			$cachefile_list = $_MooClass['MooMySQL']->getAll($sql);
			
		 	foreach($cachefile_list as $cachefile){
				 $cachefile['provincestarfile'] = rtrim($cachefile['provincestarfile'],'.data');
				if($memcached->get($cachefile['provincestarfile'])){
					$memcached->delete($cachefile['provincestarfile']);
				}
				$cachefile['citystarfile'] = rtrim($cachefile['citystarfile'],'.data');
				if($memcached->get($cachefile['citystarfile'])){
					$memcached->delete($cachefile['citystarfile']);
				}
				$cachefile['provinceotherfile'] = rtrim($cachefile['provinceotherfile'],'.data');
				if($memcached->get($cachefile['provinceotherfile'])){
					$memcached->delete($cachefile['provinceotherfile']);
				}
			} 
		}

		$userList = array();
		//查市城市之星
		//note 修正广东省深圳和广州的区域查询
		if(in_array($province, array(10101201,10101002))) {
			$city = $province;
			$province = 10101000;
		}
		$sql_city = "s.city='$city'";	
		
		//修正直辖市查询
		if(in_array($province, array('10102000', '10103000', '10104000', '10105000'))) {
			$city = '0';
		}
		
		if($city == 0) {
			$sql_city = "s.province='$province'";
		}
		
		
		if(!empty($city) || !empty($province)){
			//默认没有进入  选择省后进入
       
			 $sql = "SELECT s.uid,s.nickname,s.gender,s.images_ischeck,s.province,s.city,s.birthyear,s.s_cid,s.city_star,b.mainimg FROM {$dbTablePre}members_search as s left join {$dbTablePre}members_base b on s.uid=b.uid WHERE s.images_ischeck=1 and s.s_cid='20' or ({$sql_city} AND s.is_lock=1 AND s.images_ischeck=1 and s.nickname!='' AND s.showinformation=1 AND s.city_star>=1 AND s.usertype=1) AND s.uid!=20752315 order by s.s_cid limit 32";
			//$sql = "SELECT s.uid,s.nickname,s.gender,s.s_cid,b.mainimg,s.images_ischeck,s.province,s.city,s.birthyear,s.city_star FROM {$dbTablePre}members_search as s left join {$dbTablePre}members_base b on s.uid=b.uid WHERE  s.uid=21335105 or ({$sql_city} and s.is_lock=1 AND s.images_ischeck=1 and s.nickname!='' AND s.showinformation=1 AND s.city_star>=1 AND s.usertype=1)   order by (case when  s.uid=21335105 then 1 ELSE 4 END) limit 20";
       
			//$userList = $_MooClass['MooMySQL']->getAll($sql);
			$param = ("type=query/name=userlist_{$city}_citystar/sql=$sql/cachetime=86400");
			
			$_MooClass['MooCache']->getBlock($param);//
			$userList = $GLOBALS['_MooBlock']["userlist_{$city}_citystar"];
			//市缓存文件
			$city_cachefile = "userlist_{$city}_citystar".'_'.md5($param).'.data';
			
		}
       
		//市无城市之星，从省取城市之星
		if(empty($userList)){
		
			//默认进入
			//enky note
			/*$sql="select * from {$dbTablePre}members_base as b left join {$dbTablePre}members_search as s on b.uid=s.uid WHERE s.province='$province'
			          AND s.is_lock=1  
			          AND s.images_ischeck = 1 AND s.showinformation=1 
			          and s.city_star>=1 and s.usertype=1 AND s.uid!=20752315 LIMIT 20";
			*/
			$sql="select s.uid,s.nickname,s.gender,s.images_ischeck,s.province,s.city,s.birthyear,s.s_cid,s.city_star,b.mainimg from  {$dbTablePre}members_search as s left join {$dbTablePre}members_base b on s.uid=b.uid WHERE s.province='$province'
			          AND s.is_lock=1  
			          AND s.images_ischeck = 1 AND s.showinformation=1 
			          and s.city_star>=1 and s.usertype=1 AND s.uid!=20752315 LIMIT 32";
		
//			$userList = $_MooClass['MooMySQL']->getAll($sql);
			$param = ("type=query/name=userlist_{$province}_provincestar/sql=$sql/cachetime=86400");
			$_MooClass['MooCache']->getBlock($param);
			$userList = $GLOBALS['_MooBlock']["userlist_{$province}_provincestar"];
			
			//省缓存文件
			$province_cachefile = "userlist_{$province}_provincestar".'_'.md5($param).'.data';
		}
		
		//城市之星不够取钻石会员（市->省->全国）
		$count = count($userList);
		if($count < 32) {
			//默认进入
			 $addarr = array('cityadd', 'provinceadd', 'countryadd');
			$user_in = '1747188|2154375|1600591|2159633|20782701|20785837|20561126|20305660|20910184';
			//城市之星uid
			$user_list = array();
			if(!is_array($userList)){$userList = array();}//enky add
			foreach($userList as $user){
				$user_list[]=$user['uid'];
			}
			
			if(!empty($user_list)){
				$user_in .= ','.implode('|',$user_list);
			}
			
			//从（市->省->全国）取钻石会员
			$cityadd = array();
			$provinceadd = array();
			$countryadd = array();
			foreach($addarr as $add) {
				if($count < 32) {
					$id = '';
					//从市取
					if($add == 'cityadd') {
						if(!$city || $city == 0) {
							continue;
						}
						$sql_add = "s.city='$city' and";
						$id .= $city;
						
					}
					//从省取
					if($add == 'provinceadd') {
						if(!$province || $province == 0) {
							continue;
						}
						$sql_add = "s.province='$province' and";
						$id .= $province;
					}
					//全国取
					if($add == 'countryadd') {
						$sql_add = '';
						$id .= 'country';
					}
					
					//取几个钻石会员
					$add_query_sum = 32 - $count;
					
				    //enky need change
					
					$cl = searchApi('members_women members_man');
					$cond[] = array('is_lock','1',false);
					$cond[] = array('images_ischeck','1',false);
					$cond[] = array('usertype','1',false);
					$cond[] = array('@id','20752315|'.$user_in,true);//uid
					$cond[] = array('s_cid','20',false);
					$cond[] = array('city_star','0',false);
					$cond[] = array('showinformation','1',false); 
					$cond[] = array('pic_num',array(1,20),false);
					if($province) array('province',$province,false);
					
					if($city) array('city',$city,false);
					$limit = array($add_query_sum);
					$res_matches = $cl -> getResult($cond,$limit);
					
				if($res_matches){
					$count_res = count($res_matches);
					$array_merge = array();
					
					for($i=0;$i<$count_res;$i++){
						if(!empty($res_matches['matches'][$i])){
							$ids[] = $res_matches['matches'][$i]['id'];
					
						}
					}
					$sql_str = implode(',', $ids);
			
					$nickname_res = $_MooClass['MooMySQL']->getAll("select nickname,uid from web_members_search where uid in "."($sql_str)");
					$mainimg_res = $_MooClass['MooMySQL']->getAll("select mainimg from web_members_base where uid in "."($sql_str)");
					//合并到sphinx查询出的整理后的数组
				    for($i=0;$i<$count_res;$i++){
				    	if(!empty($res_matches['matches'][$i]['attrs'])){
						$res_matches['matches'][$i]['attrs']['nickname']= $nickname_res[$i]['nickname'];
						$res_matches['matches'][$i]['attrs']['uid']= $nickname_res[$i]['uid'];
						$res_matches['matches'][$i]['attrs']['mainimg']=$mainimg_res[$i]['mainimg'];
				    	
						$bigarr[] = $res_matches['matches'][$i]['attrs'];
				    	}
					}
					$$add = $bigarr;
				
				}else{
				    $sql = "select s.uid,s.nickname,s.gender,s.images_ischeck,s.province,s.city,s.birthyear,s.s_cid,s.city_star,b.mainimg from {$dbTablePre}members_search as s left join {$dbTablePre}members_base as b on s.uid=b.uid  where s.uid not in({$user_in})
					          AND {$sql_add} s.city_star=0 AND s.s_cid = 20 
					          AND s.images_ischeck  = 1 AND s.is_lock = 1 AND s.showinformation = 1
					          AND s.pic_num >=1 AND s.usertype=1 AND s.uid!=20752315 
					          LIMIT $add_query_sum";
					
					$param = ("type=query/name=userlist_{$id}_other/sql=$sql/cachetime=86400");
					$_MooClass['MooCache']->getBlock($param);
					$$add = $GLOBALS['_MooBlock']["userlist_{$id}_other"];
				}
					
					//已获取的首页会员uid
					$user_list = array();
				    if(!is_array($$add)){$$add = array();}//enky add
					foreach($$add as $user){
						$user_list[]=$user['uid'];
					}
					
					if(!empty($user_list)){
						$user_in .= ','.implode(',',$user_list);
					}
					//echo $user_in;exit;
					//已有总数
					$count += count($$add);
					
				}
			}
			//$countryadd  have data
			$userList = array_merge($userList, $cityadd, $provinceadd, $countryadd);
			
			
			//省其它类型会员缓存文件
			$province_othercachefile = "userlist_{$id}_other".'_'.md5($param).'.data';
		}
		
		//生成的block缓存文件存库
		$city = $city ? $city : 0;
		$sql = "SELECT * FROM {$dbTablePre}index_cachefile WHERE province='{$province}' AND city='{$city}'";
		$cache_arr = $_MooClass['MooMySQL']->getAll($sql);
		if(empty($cache_arr)){
			$province_cachefile = isset($province_cachefile)?$province_cachefile:'';
			$sql = "INSERT INTO {$dbTablePre}index_cachefile SET province='{$province}',city='{$city}',provincestarfile='{$province_cachefile}',citystarfile='{$city_cachefile}',provinceotherfile='{$province_othercachefile}'";
			$_MooClass['MooMySQL']->query($sql);
		}
	
		//推荐列表中如果存在，则以推荐列表中的指定的sort替换
		$sql = "SELECT * FROM {$dbTablePre}members_recommend WHERE province='{$province}' AND city='$city' order by sort asc";
		$recommend_list = $_MooClass['MooMySQL']->getAll($sql);
		
		if(!empty($recommend_list)){
			foreach($recommend_list as $list){
				$sort = $list['sort'] < 1 ? 0 : $list['sort']-1;
				if(MOOPHP_ALLOW_FASTDB){
					$u = MooFastdbGet('members_search','uid',$list['uid']);
					$u2 = MooFastdbGet('members_base','uid',$list['uid']);
					if (is_array($u) && is_array($u2)){
					   $u = array_merge($u,$u2);
					}
				}else{
					$sql = "SELECT s.uid,s.nickname,s.gender,s.images_ischeck,s.province,s.city,s.birthyear,s.s_cid,s.city_star,b.mainimg FROM {$dbTablePre}members_search s left join {$dbTablePre}members_base b on s.uid=b.uid WHERE s.uid='{$list['uid']}'";
					$u = $_MooClass['MooMySQL']->getOne($sql,true);
				}
				if($u) {
					foreach($userList as $key => $val) {
						if($list['uid'] == $val['uid']) {
							$user_key = $key;
							break;
						}
					}
					if(isset($user_key)) {
						if(isset($userList[$sort])) {
							$userList[$key] = $userList[$sort];
						}
						$userList[$sort] = $u;
					} else {
						$userList[$sort] = $u;
					}
				}
			}
			
		}
			
	}
	
	//删除数组中多余的部分
	//array_splice($userList, 24);
	foreach($userList as $va){
		if($va['gender'] == $gender){
			$newuser[] = $va['uid'] ;
		}
	}
	if(count($newuser) >= 12){
		array_splice($newuser, 12);
	}
	else{
		$needn = 12 - count($newuser);
		if($needn == 1){
			$needn == 2;
		}
		
		if($gender == 0){
				$rand_keys = array_rand($manList, $needn);
	    		for($i=0;$i<$needn;$i++){
					$newuser[] = $manList[$rand_keys[$i]];
	    		}
	    			
		}
		else{
				$rand_keys = array_rand($womenList, $needn);
	    		for($i=0;$i<$needn;$i++){
					$newuser[] = $womenList[$rand_keys[$i]];
	    		}
		}
	}
	
	foreach ($newuser as $value){
		$mainimg = MooGetphoto($value,$style = "com");
        $users[] = array('uid'=>$value,'mainimg'=>$mainimg);      
	}
    echo return_data($users);
	exit; 
	/*
	foreach($users as $val){
    	$sha[] = $val['uid'];
    }
    $sha_in = implode(',', $sha);
    $sha_ar = $_MooClass['MooMySQL']->getAll("select nickname from web_members_search where uid in "."($sha_in)");
    foreach($sha_ar as $va){
    	echo $va['nickname'].'<br />';
    }
	exit; 
	*/
	/*
	//最新注册会员，按照片数排序

	$param = ("type=query/name=new_members/sql=SELECT s.uid,s.nickname,s.gender,s.images_ischeck,s.province,s.city,s.birthyear,s.s_cid,s.city_star,b.mainimg FROM {$dbTablePre}members_search as s left join {$dbTablePre}members_base as b on s.uid=b.uid   where  s.pic_num>4 and s.images_ischeck=1 and s.is_lock=1 order by s.uid desc limit 4/cachetime=10800");
	$_MooClass['MooCache']->getBlock($param);
	$new_reguser = $GLOBALS['_MooBlock']['new_members'];
	
	//note 钻石会员介绍    地区切换时真爱一生推荐会员涉及的
	$sql="select count(d.uid) as num from web_diamond_recommend d left join web_members_search s on d.uid=s.uid  where  d.isindex=1 and d.gender='1' and s.images_ischeck=1 and d.province='{$province}'";
	$result_female=$_MooClass['MooMySQL']->getOne($sql,true);
	$num_female=$result_female['num'];
	
	$sql="select count(d.uid) as num from web_diamond_recommend  d left join web_members_search s on d.uid=s.uid  where  d.isindex=1 and d.gender='0' and s.images_ischeck=1 and d.province='{$province}'";
    $result_male=$_MooClass['MooMySQL']->getOne($sql,true);
    $num_male=$result_male['num'];
    if ($num_female>=5 && $num_male>=5){
    	
	   //女钻石会员
       $sql_female = "select d.uid,d.birthyear,d.province,d.city,d.nickname from {$dbTablePre}diamond_recommend  d left join web_members_search s on d.uid=s.uid  where d.isindex=1 and d.gender='1' and s.images_ischeck=1 and d.province='{$province}' order by d.city<>'{$city}' ,d.city desc,d.sort desc  limit 10"; //FEMAIL
       $param = ("type=query/name=web_star_recommend/sql=$sql_female/cachetime=86400");
       $_MooClass['MooCache']->getBlock($param);
       $arr_diamond_female = $GLOBALS['_MooBlock']['web_star_recommend'];
	   
	  
       
       //男钻石会员
       $sql_male = "select d.uid,d.birthyear,d.province,d.city,d.nickname from {$dbTablePre}diamond_recommend  d left join web_members_search s on d.uid=s.uid  where d.isindex=1 and d.gender='0' and s.images_ischeck=1 and d.province='{$province}' order by d.city<>'{$city}' ,d.city desc,d.sort desc limit 10"; //MAIL
       $param = ("type=query/name=web_star_recommend/sql=$sql_male/cachetime=86400");
       $_MooClass['MooCache']->getBlock($param);
       $arr_diamond_male = $GLOBALS['_MooBlock']['web_star_recommend'];
	  
	}else{
		$diamond_filename = "data/cache/diamond_intro.php";
	    if(!file_exists($diamond_filename)){
	        //女钻石会员
	        $sql_female = "select m.uid,m.birthyear,m.province,m.city,m.nickname,b.mainimg,m.images_ischeck,i.introduce from {$dbTablePre}members_search m left join {$dbTablePre}members_base as b on m.uid=b.uid left join {$dbTablePre}members_introduce as i on m.uid=i.uid left join {$dbTablePre}members_choice c on m.uid=c.uid where m.s_cid='20' and m.usertype='1' and i.introduce_pass='1' and m.is_lock='1' and m.images_ischeck='1' and m.gender='1'";
	        $sql = $sql_female . " order by m.pic_num desc,m.bgtime desc limit 10";
	        $arr_diamond_female = $_MooClass['MooMySQL']->getAll($sql);
	        foreach($arr_diamond_female as $k=>$v){
	            $diamond_str_female .= $v['uid'].','; 
	        }
	        
	        //男钻石会员
	        $sql_male = "select m.uid,m.birthyear,m.province,m.city,m.nickname,b.mainimg,m.images_ischeck,i.introduce from {$dbTablePre}members_search m left join {$dbTablePre}members_base as b on m.uid=b.uid left join  {$dbTablePre}members_introduce as i on m.uid=i.uid left join {$dbTablePre}members_choice c on m.uid=c.uid where m.s_cid='20' and m.usertype='1' and i.introduce_pass='1' and m.is_lock='1' and m.images_ischeck='1' and m.gender='0'";
	        $sql = $sql_male . " order by m.pic_num desc,m.bgtime desc limit 10";
	        $arr_diamond_male = $_MooClass['MooMySQL']->getAll($sql);
	        foreach($arr_diamond_male as $k=>$v){
	            $diamond_str_male .= $v['uid'].','; 
	        }
	        
	        $diamond_str=$diamond_str_female.$diamond_str_male;
	       
	        file_put_contents($diamond_filename,$diamond_str);
	    }else{
	    
	        $arr_diamond = file($diamond_filename);
	        
	        if($arr_diamond){
	        	
	            $star_user = trim($arr_diamond[0],',');
	            
	            $star_user=explode(',',$star_user);
	        
	            if(count($star_user)==20){
	                $star_female=$star_user[0].','.$star_user[1].','.$star_user[2].','.$star_user[3].','.$star_user[4].','.$star_user[5].','.$star_user[6].','.$star_user[7].','.$star_user[8].','.$star_user[9];
	                //女钻石会员 enky need change
	                $sql_female = "select m.uid,m.birthyear,m.province,m.city,m.nickname,b.mainimg,m.images_ischeck,i.introduce from {$dbTablePre}members_search m left join {$dbTablePre}members_base as b on m.uid=b.uid left join {$dbTablePre}members_introduce as i on m.uid=i.uid  left join {$dbTablePre}members_choice c on m.uid=c.uid where m.uid in($star_female)  limit 10"; //FEMAIL
	                $param = ("type=query/name=web_star_recommend/sql=$sql_female/cachetime=86400");
	                $_MooClass['MooCache']->getBlock($param);
	                
	                $arr_diamond_female = $GLOBALS['_MooBlock']['web_star_recommend'];
	                
	                $star_male=$star_user[10].','.$star_user[11].','.$star_user[12].','.$star_user[13].','.$star_user[14].','.$star_user[15].','.$star_user[16].','.$star_user[17].','.$star_user[18].','.$star_user[19];
	                //男钻石会员 enky need change
	                $sql_male = "select m.uid,m.birthyear,m.province,m.city,m.nickname,b.mainimg,m.images_ischeck,i.introduce from {$dbTablePre}members_search m left join {$dbTablePre}members_base as b on m.uid=b.uid left join {$dbTablePre}members_introduce as i on m.uid=i.uid  left join {$dbTablePre}members_choice c on m.uid=c.uid where m.uid in($star_male) limit 10"; //MAIL
	                $param = ("type=query/name=web_star_recommend/sql=$sql_male/cachetime=86400");
	                $_MooClass['MooCache']->getBlock($param);
	                $arr_diamond_male = $GLOBALS['_MooBlock']['web_star_recommend'];
	                
	            }
	        }
	    }

	}
    

	//note 优秀真爱一生介绍
//	include_once 'module/index/excellent.php';
//	echo "<pre>";
//	print_r($arrexcellent);
//	echo "</pre>";
	//成功案例
	$param = ("type=query/name=story/sql=select s.sid,s.uid,s.title,s.content,s.name1,s.name2,s.story_date,sp.img from `{$dbTablePre}story` as s left join  `{$dbTablePre}story_pic` as sp on s.is_index=sp.mid where sp.syscheck=1 and s.syscheck=1 and s.recommand= '1' order by s.story_date desc,sp.img desc limit 0, 4/cachetime=600");
	$_MooClass['MooCache']->getBlock($param);
	$story = $GLOBALS['_MooBlock']['story'];
	MooPlugins('ipdata');	
	$ip = GetIP();
	//$ip = '119.145.41.181';
	$finally_ip = convertIp($ip);
	//$news_ip =iconv('gbk','utf-8',file_get_contents('http://fw.qq.com/ipaddress'));
	//$finally_ip = $news_ip;
	//echo $finally_ip; 
	if(preg_match('/(广东|广州|深圳|佛山|珠海|东莞|汕头|韶关|江门|湛江|茂名|肇庆|惠州|梅州|汕尾|河源|清远|阳江|潮州|揭阳|云浮)/',$finally_ip)){
		$finally_address = 1;
	}
	$time = time();
	//活动时间
	$activitytime1 = "2011-02-01 00:30:00";
	$activitytime2 = "2011-02-08";

	if($time>=strtotime($activitytime1)&&$time<strtotime($activitytime2)) $appear = "1";
	// note 与相亲网首页错开会员
    $api = MooGetGPC('api','string', "G");  
    if($api == '0019'){ 
        $d = '';
        foreach($userList as $v){
            if($v['uid'] < 30000000){ 
                $apistr .= $d.$v['uid'];
                $d = ',';
            }
        }
        exit($apistr);
    }
	
	
	require MooTemplate('public/index', 'module');
	*/
	
	
}


function index_index1(){
	global $_MooClass,$dbTablePre,$userid,$user_arr,$memcached,$uuid;
	$sql_condition_province=$sql_condition_city='gender';
	$province='';
	$currentdistrict = '0';$arr_diamond_female=$arr_diamond_male=null;
	$provice_list=$city_list=$province_othercachefile=null;
	$s_cid = 40;
	
	$uid =  $_GET['uid'] = isset($_GET['uid'])?$_GET['uid']:'';
	if($uid){
		$userid = $mem_uid = $memcached->get('uid_'.$uid);
		$user_arr = MooMembersData($userid);
	}
	
	
	if($userid){
		$and_uuid = isset($_GET['uuid'])? $_GET['uuid'] : '';
		$checkuuid = check_uuid($and_uuid,$userid);
	    if(!$checkuuid){
	    	//$error = "uuid_error";
	    	//echo return_data($error,false);exit;	
	    }
	}

    $sex = !MooGetGPC('h','integer','G') ? 0 : 1;//note 性别
	$workprovince = MooGetGPC('workprovince','integer','G');//工作省份
	$workcity =  MooGetGPC('workcity','integer','G');//工作城市
	
	//$workprovince = $user_arr['province']?$user_arr['province']:0;
	//$workcity = $user_arr['city']?$user_arr['city']:0;
	if(in_array( $workprovince, array(10101201,10101002)))
	{
		//note 修正广东省深圳和广州的区域查询 2010-09-04
		$workcity = $workprovince;
		$workprovince = 10101000;
	}

	$age1 = MooGetGPC('age1','integer','G');//最小年龄
	$age2 = MooGetGPC('age2','integer','G');//最大年龄
	
	$userList = $userList2 = array();
	$index_total_user = 24;//首页总用户数
	//note 当前年份
	$year = date('Y');
	
	//登录用户查找性别
	$sql_gender = '';
	
	
	
	//性别取反
	if($userid){
		$gender = $user_arr['gender'] == 1 ? 0 : 1;
		$sql_gender = " and s.gender='$gender'";
		$sql_condition_province .= $sql_gender;
		$sql_condition_city .= " and s.gender='$gender'";
	}
	else{
		if($_GET['gender'] == 0 || $_GET['gender'] == 1){
			$gender = $_GET['gender'];
			$sql_gender = " and s.gender='$gender'";
			$sql_condition_province .= $sql_gender;
			$sql_condition_city .= " and s.gender='$gender'";
		}
	}
	
//	get_integrity();
	$date_time=date('d');
	$month_time=date('m');
	$reguser_num=30000+$month_time*1100+$date_time*191;
	
	/*
		//默认进入
		//包含配置文件
		include_once("./module/crontab/crontab_config.php");
		$cur_ip = GetIP();
		MooPlugins('ipdata');
		$ip_arr = convertIp($cur_ip);
		//得到省份对应的数值，查库
		$province = $city = "";
		foreach($provice_list as $key => $val){
			if(strstr($ip_arr,$val)){
				$province = $key;
				$currentdistrict=$val;
				break;
			}
		}
		if(isset($_GET['province'])){
			foreach($provice_list as $key => $val){
				if($_GET['province']==$key){
					$currentdistrict=$val;
					$province = $key;
					break;
				}
            }
	    }
	    $test = array();
	    if(isset($currentdistrict)){
			$index_img_num = 5;
			$index_img = index_img($currentdistrict,$index_img_num);
			$small_img_num = 3;
			$small_img = small_img($currentdistrict,$small_img_num,$province);
			$test = test($currentdistrict);
	    }
		//得到市对应的城市代号
		foreach($city_list as $city_key => $city_val){
		
			if(strstr($ip_arr,$city_val)){
				$city = $city_key;
				break;
			}
		}
*/		
		$userList = array();
		//查市城市之星
		//note 修正广东省深圳和广州的区域查询
		if(in_array($province, array(10101201,10101002))) {
			$city = $province;
			$province = 10101000;
		}
		$sql_city = "s.city='$city'";	
		
		//修正直辖市查询
		if(in_array($province, array('10102000', '10103000', '10104000', '10105000'))) {
			$city = '0';
		}
		if($city == 0) {
			$sql_city = "s.province='$province'";
		}
		if(!empty($city) || !empty($province)){
			//默认没有进入  选择省后进入
			$sql = "SELECT s.uid,b.mainimg,s.gender FROM {$dbTablePre}members_search as s left join {$dbTablePre}members_base b on s.uid=b.uid WHERE s.images_ischeck=1 $sql_gender and s.s_cid='20' or ({$sql_city} AND s.is_lock=1 AND s.images_ischeck=1 and s.nickname!='' AND s.showinformation=1 AND s.city_star>=1 AND s.usertype=1) AND s.uid!=20752315 order by s.s_cid limit 32";
			$param = ("type=query/name=userlist_{$city}9{$gender}_citystar/sql=$sql/cachetime=86400");
			
			$_MooClass['MooCache']->getBlock($param);//
			$userList = $GLOBALS['_MooBlock']["userlist_{$city}9{$gender}_citystar"];
			//市缓存文件
			$city_cachefile = "userlist_{$city}9{$gender}_citystar".'_'.md5($param).'.data';
		}
       
		//市无城市之星，从省取城市之星
		if(empty($userList)){
			$sql="select s.uid,b.mainimg,s.gender from  {$dbTablePre}members_search as s left join {$dbTablePre}members_base b on s.uid=b.uid WHERE s.province='$province'
			          AND s.is_lock=1  
			          $sql_gender 
			          AND s.images_ischeck = 1 AND s.showinformation=1 
			          and s.city_star>=1 and s.usertype=1 AND s.uid!=20752315 LIMIT 32";
			$param = ("type=query/name=userlist_{$province}9{$gender}_provincestar/sql=$sql/cachetime=86400");
			$_MooClass['MooCache']->getBlock($param);
			$userList = $GLOBALS['_MooBlock']["userlist_{$province}9{$gender}_provincestar"];
			//省缓存文件
			$province_cachefile = "userlist_{$province}9{$gender}_provincestar".'_'.md5($param).'.data';
		}
		//城市之星不够取钻石会员（市->省->全国）
		$count = count($userList);
		if($count < 32) {
			//默认进入
			 $addarr = array('cityadd', 'provinceadd', 'countryadd');
			$user_in = '1747188|2154375|1600591|2159633|20782701|20785837|20561126|20305660|20910184';
			//城市之星uid
			$user_list = array();
			if(!is_array($userList)){$userList = array();}//enky add
			foreach($userList as $user){
				$user_list[]=$user['uid'];
			}
			if(!empty($user_list)){
				$user_in .= ','.implode('|',$user_list);
			}
			//从（市->省->全国）取钻石会员
			$cityadd = array();
			$provinceadd = array();
			$countryadd = array();
			foreach($addarr as $add) {
				if($count < 32) {
					$id = '';
					//从市取
					if($add == 'cityadd') {
						if(!$city || $city == 0) {
							continue;
						}
						$sql_add = "s.city='$city' and";
						$id .= $city;
					}
					//从省取
					if($add == 'provinceadd') {
						if(!$province || $province == 0) {
							continue;
						}
						$sql_add = "s.province='$province' and";
						$id .= $province;
					}
					//全国取
					if($add == 'countryadd') {
						$sql_add = '';
						$id .= 'country';
					}
					//取几个钻石会员
					$add_query_sum = 32 - $count;
					$cl = searchApi('members_women members_man');
					$cond[] = array('is_lock','1',false);
					$cond[] = array('images_ischeck','1',false);
					$cond[] = array('usertype','1',false);
					$cond[] = array('@id','20752315|'.$user_in,true);//uid
					$cond[] = array('s_cid','20',false);
					$cond[] = array('city_star','0',false);
					$cond[] = array('showinformation','1',false); 
					$cond[] = array('pic_num',array(1,20),false);
					if($province) array('province',$province,false);
					if($city) array('city',$city,false);
					$limit = array($add_query_sum);
					$res_matches = $cl -> getResult($cond,$limit);
				if($res_matches){
					$count_res = count($res_matches);
					$array_merge = array();
					for($i=0;$i<$count_res;$i++){
						if(!empty($res_matches['matches'][$i])){
							$ids[] = $res_matches['matches'][$i]['id'];
						}
					}
					$sql_str = implode(',', $ids);
					$nickname_res = $_MooClass['MooMySQL']->getAll("select nickname,uid from web_members_search where uid in "."($sql_str)");
					$mainimg_res = $_MooClass['MooMySQL']->getAll("select mainimg from web_members_base where uid in "."($sql_str)");
					//合并到sphinx查询出的整理后的数组
				    for($i=0;$i<$count_res;$i++){
				    	if(!empty($res_matches['matches'][$i]['attrs'])){
						$res_matches['matches'][$i]['attrs']['nickname']= $nickname_res[$i]['nickname'];
						$res_matches['matches'][$i]['attrs']['uid']= $nickname_res[$i]['uid'];
						$res_matches['matches'][$i]['attrs']['mainimg']=$mainimg_res[$i]['mainimg'];
						$bigarr[] = $res_matches['matches'][$i]['attrs'];
				    	}
					}
					$$add = $bigarr;
				}else{
				    $sql = "select s.uid,b.mainimg,s.gender from {$dbTablePre}members_search as s left join {$dbTablePre}members_base as b on s.uid=b.uid  where s.uid not in({$user_in})
					          AND {$sql_add} s.city_star=0 AND s.s_cid = 20 
					          AND s.images_ischeck  = 1 $sql_gender AND s.is_lock = 1 AND s.showinformation = 1
					          AND s.pic_num >=1 AND s.usertype=1 AND s.uid!=20752315 
					          LIMIT $add_query_sum";
					$param = ("type=query/name=userlist_{$id}9{$gender}_other/sql=$sql/cachetime=86400");
					$_MooClass['MooCache']->getBlock($param);
					$$add = $GLOBALS['_MooBlock']["userlist_{$id}9{$gender}_other"];
				}
					//已获取的首页会员uid
					$user_list = array();
				    if(!is_array($$add)){$$add = array();}//enky add
					foreach($$add as $user){
						$user_list[]=$user['uid'];
					}
					if(!empty($user_list)){
						$user_in .= ','.implode(',',$user_list);
					}
					//echo $user_in;exit;
					//已有总数
					$count += count($$add);
				}
			}
			//$countryadd  have data
			$userList = array_merge($userList, $cityadd, $provinceadd, $countryadd);
			//省其它类型会员缓存文件
			$province_othercachefile = "userlist_{$id}9{$gender}_other".'_'.md5($param).'.data';
		}
		//生成的block缓存文件存库
		$city = $city ? $city : 0;
		$sql = "SELECT * FROM {$dbTablePre}index_cachefile WHERE province='{$province}9{$gender}' AND city='{$city}{$gender}'";
		$cache_arr = $_MooClass['MooMySQL']->getAll($sql);
		if(empty($cache_arr)){
			$province_cachefile = isset($province_cachefile)?$province_cachefile:'';
			$sql = "INSERT INTO {$dbTablePre}index_cachefile SET province='{$province}9{$gender}',city='{$city}{$gender}',provincestarfile='{$province_cachefile}',citystarfile='{$city_cachefile}',provinceotherfile='{$province_othercachefile}'";
			$_MooClass['MooMySQL']->query($sql);
		}
		
		/*
		//new
		//推荐列表中如果存在，则以推荐列表中的指定的sort替换
		$sql = "SELECT * FROM {$dbTablePre}members_recommend WHERE province='{$province}' AND city='$city' order by sort asc";
		$recommend_list = $_MooClass['MooMySQL']->getAll($sql);
		
		if(!empty($recommend_list)){
			foreach($recommend_list as $list){
				$sort = $list['sort'] < 1 ? 0 : $list['sort']-1;
				if(MOOPHP_ALLOW_FASTDB){
					$u = MooFastdbGet('members_search','uid',$list['uid']);
					$u2 = MooFastdbGet('members_base','uid',$list['uid']);
					if (is_array($u) && is_array($u2)){
					   $u = array_merge($u,$u2);
					}
				}else{
					$sql = "SELECT s.uid,s.nickname,s.gender,s.images_ischeck,s.province,s.city,s.birthyear,s.s_cid,s.city_star,b.mainimg FROM {$dbTablePre}members_search s left join {$dbTablePre}members_base b on s.uid=b.uid WHERE s.uid='{$list['uid']}'";
					$u = $_MooClass['MooMySQL']->getOne($sql,true);
				}
				if($u) {
					foreach($userList as $key => $val) {
						if($list['uid'] == $val['uid']) {
							$user_key = $key;
							break;
						}
					}
					if(isset($user_key)) {
						if(isset($userList[$sort])) {
							$userList[$key] = $userList[$sort];
						}
						$userList[$sort] = $u;
					} else {
						$userList[$sort] = $u;
					}
				}
			}
			
		}
			
	
	//new over
	 * */
	 
	//删除数组中多余的部分
	array_splice($userList, 12);
	foreach ($userList as $key=>$value){
		$mainimg = MooGetphoto($value['uid'],$style = "com");
        $users[] = array('uid'=>$value['uid'],'mainimg'=>$mainimg,'gender'=>$value['gender']);      
	}
    //echo return_data($users);
    foreach($users as $val){
    	$sha[] = $val['uid'];
    }
    $sha_in = implode(',', $sha);
    $sha_ar = $_MooClass['MooMySQL']->getAll("select nickname from web_members_search where uid in "."($sha_in)");
    foreach($sha_ar as $va){
    	echo $va['nickname'].'<br />';
    }
	exit; 
}

function shadow_master(){
	global $_MooClass,$dbTablePre,$userid,$user_arr,$memcached,$uuid;
	$womenList  =  array('30407189','21186208','21199933','20686096','20223069','20520991','20069562','21156987','21023025','21206132','21201459','21161703','20619161','20301718','20719091','21740236','20681292','21424242','20554506','20111310','20660269','30632759','30631960','30630519','30608962','30387074','30378615','30360483','30300309','30252809','30214641');
	$manList    =  array('30391348','21793878','30129941','21514524','30087732','30621435','30071838','30610492','30619608','30080561','30403838','30211025','30069490','30359171','30368726','30405061','30192000','30069742','30141451','30052221','30253012','30016533','30286293','30247063','30403530','30365362','21767568','30341955','21359517','21708383','30041513');
	
	$uid =  $_GET['uid'] = isset($_GET['uid'])?$_GET['uid']:'';
	if($uid){
		$userid = $mem_uid = $memcached->get('uid_'.$uid);
		$user_arr = MooMembersData($userid);
	}
	
	
	if($userid){
		$and_uuid = isset($_GET['uuid'])? $_GET['uuid'] : '';
		$checkuuid = check_uuid($and_uuid,$userid);
	    if(!$checkuuid){
	    	$error = "uuid_error";
	    	echo return_data($error,false);exit;	
	    }
	}
	
	
	if($userid){
		$gender = $user_arr['gender'] == 1 ? 0 : 1;
	}
	else{
		
		$gender = $_GET['gender'];
		
	}
	
	if($gender == 0){
			$rand_keys = array_rand($manList, 12);
    		for($i=0;$i<12;$i++){
				$returnarr[] = $manList[$rand_keys[$i]];
    		}
    			
	}
	elseif($gender == 1){
			$rand_keys = array_rand($womenList, 12);
    		for($i=0;$i<12;$i++){
				$returnarr[] = $womenList[$rand_keys[$i]];
    		}
	}
	else{
			$rand_keys = array_rand($womenList, 6);
    		for($i=0;$i<6;$i++){
				$returnarr[] = $womenList[$rand_keys[$i]];
    		}
			$rand_keys = array_rand($manList, 6);
    		for($i=0;$i<6;$i++){
				$returnarr[] = $manList[$rand_keys[$i]];
    		}
    		//shuffle($returnarr);
	}
	
	
	
	foreach ($returnarr as $value){
		$mainimg = MooGetphoto($value,$style = "com");
        $users[] = array('uid'=>$value,'mainimg'=>$mainimg);      
	}
    echo return_data($users);
	exit; 
	
}

//note 故事列表页
function story_list() {
	global $_MooClass,$dbTablePre,$userid,$memcached;
	
	$pagesize = 12;
	//note 获得第几页
	$page = empty($_GET['page']) ?  '1' : $_GET['page'];
	//note limit查询开始位置
	$start = ($page - 1) * $pagesize;
	
	//note 获得当前的url
	$currenturl = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	$currenturl = preg_replace("/(&page=\d+)/","",$currenturl); 
	
	//note 分页查询
	$love_story = $_MooClass['MooMySQL']->getAll("SELECT sid,title,name1,name2,story_date,submit_date,state FROM {$dbTablePre}story where syscheck = 1 ORDER BY story_date DESC LIMIT $start,$pagesize",false,true,3600*3);
	
	foreach($love_story as $key => $va){
		$love_story[$key]['story_date'] = date("Y-n-j",$va['story_date']);
	}
	if(count($love_story)>0){
		echo return_data($love_story,true);  
    	exit;
	}
	else{
		$error = '此页无数据';
		echo return_data($error,false);  
    	exit;
	}
}


//note 故事页
function story_one() {
	global $_MooClass,$dbTablePre,$userid,$memcached;
	$sid = MooGetGPC('sid','integer','G');
	$love_story = $_MooClass['MooMySQL']->getOne("SELECT * FROM {$dbTablePre}story where sid = $sid ");
	
	if($love_story){
		echo return_data($love_story,true);  
    	exit;
	}
	else{
		$error = '找不到该成功故事';
		echo return_data($error,false);  
    	exit;
	}
}


/*
 * *********************************************控制层******************************************/

 $h = $_GET['h'] = isset($_GET['h'])?$_GET['h']:'';
switch ($h){
	case 'index':
	     shadow_master();
	     break;
	case 'story_list' :
		story_list();
		break;
	case 'story_one':
		story_one();
		break;
		
	case 'newindex':
		index_index();
	default :
		 shadow_master();
         break;
}
?>



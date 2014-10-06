<?php
include "module/index/function.php";
/**
 * 登录表单处理
 * 描述：
 */
function login_submit() {
	global $_MooClass,$dbTablePre,$_MooCookie;
	//noet 对提交的数据过滤
	if(MooGetGPC('btnLogin','string','P') || MooGetGPC('loginsubmit','string','P')) {
	    $username=MooGetGPC('username','string','P');
	    $password=MooGetGPC('password','string','P');
		$username = trim($username);
		$md5_password = md5($password);
		$cookietime = intval(MooGetGPC('cookietime','string','P')?MooGetGPC('cookietime','string','P'):'');
		$remember_username = MooGetGPC('remember','integer','P');
	}
/*****设置回转的页面*****/
	$returnurl = MooGetGPC('returnurl','string', "P");


	$userid = 0;
	$sp = searchApi('members_man members_women');
	$limit = array(0, 1);
	//note 验证用户名，密码     enky


	if(is_numeric($username)){
		if(strlen($username)==11){
			//判断手机号是否存在
			$filter = array();
			$filter[] = array('telphone', $username);
			if($sp->getResultOfReset($filter, $limit)){
				$ids = $sp->getIds();
				if(isset($ids[0])) $userid = $ids[0];
			}
		}

		if(!$userid){
			//判断uid是否存在
			$filter = array();
			$filter[] = array('@id', $username);
			if($sp->getResultOfReset($filter, $limit)){
				$ids = $sp->getIds();
				if(isset($ids[0])) $userid = $ids[0];
			}
		}

		if(!$userid){
			if($user_one = $_MooClass['MooMySQL']->getOne("SELECT uid from `{$dbTablePre}members_base` where qq='{$username}'",true))
				$userid = $user_one['uid'];
		}
		/*if(strlen($username)==11){
		   $sql_where = " s.telphone='{$username}'";
           $user = $_MooClass['MooMySQL']->getOne("SELECT s.uid,s.username,s.password,s.birthyear,s.gender,s.province,s.city,b.automatic,s.is_lock,b.is_awoke,s.sid,l.last_login_time,l.lastip FROM `{$dbTablePre}members_search` as s left join `{$dbTablePre}members_base` as b on s.uid=b.uid left join `{$dbTablePre}members_login` as l on s.uid=l.uid   WHERE $sql_where");
		}else{
			$sql_where = " s.uid = '{$username}'";
			$user = $_MooClass['MooMySQL']->getOne("SELECT s.uid,s.username,s.password,s.birthyear,s.gender,s.province,s.city,b.automatic,s.is_lock,b.is_awoke,s.sid,l.last_login_time,l.lastip FROM `{$dbTablePre}members_search` as s left join `{$dbTablePre}members_base` as b on s.uid=b.uid left join `{$dbTablePre}members_login` as l on s.uid=l.uid   WHERE $sql_where");
			if(empty($user)){
			   $sql_where = "b.qq='{$username}'";
			   //$user = $_MooClass['MooMySQL']->getOne("SELECT a.uid,username,password,birthyear,gender,province,city,automatic,is_lock,is_awoke,sid,last_login_time,lastip FROM `{$dbTablePre}members` a ,`{$dbTablePre}memberfield` b WHERE a.uid=b.uid and $sql_where");
				$user = $_MooClass['MooMySQL']->getOne("SELECT s.uid,s.username,s.password,s.birthyear,s.gender,s.province,s.city,b.automatic,s.is_lock,b.is_awoke,s.sid,l.last_login_time,l.lastip FROM `{$dbTablePre}members_search` as s left join `{$dbTablePre}members_base` as b on s.uid=b.uid left join `{$dbTablePre}members_login` as l on s.uid=l.uid   WHERE $sql_where");
			}
		}*/
	}else{

		$filter = array();
		$filter[] = array('username', $username);
		if($sp->getResultOfReset($filter, $limit)){
			$ids = $sp->getIds();
			if(isset($ids[0])) $userid = $ids[0];
		}
		/*$sql_where = "s.username = '{$username}'";
		//$user = $_MooClass['MooMySQL']->getOne("SELECT uid,username,password,birthyear,gender,province,city,automatic,is_lock,is_awoke,sid,last_login_time,lastip FROM `{$dbTablePre}members` WHERE $sql_where");
		$user = $_MooClass['MooMySQL']->getOne("SELECT s.uid,s.username,s.password,s.birthyear,s.gender,s.province,s.city,b.automatic,s.is_lock,b.is_awoke,s.sid,l.last_login_time,l.lastip FROM `{$dbTablePre}members_search` as s left join `{$dbTablePre}members_base` as b on s.uid=b.uid left join `{$dbTablePre}members_login` as l on s.uid=l.uid   WHERE $sql_where");*/
	}
	//note 用户名找不到
	if(!$userid) {
		$login_where = is_numeric($username) ? "uid='{$username}' or telphone='{$username}'" : "username='{$username}'";
		$user_one = $_MooClass['MooMySQL']->getOne("SELECT uid from `{$dbTablePre}members_search` where $login_where",true);
		if($user_one){
			$userid = $user_one['uid'];
		}else{
			//note 转至邮箱验证页
			if($returnurl){
				MooMessage("用户名不存在","{$returnurl}",'01');
			}else{
				MooMessage("用户名不存在","login.html",'01');
			}
		}
	}
	//获取
	$user = array_merge(MooGetData('members_login', 'uid', $userid), MooMembersData($userid));

	if($user['is_lock']!='1'){
		MooMessage("很抱歉您的用户名已经被锁定！<br>请联系真爱一生网客服：<b>400-8787-920</b>","{$returnurl}",'01',6);
	}
	//note 用户密码错误
	if($user['uid'] && $user['password'] != $md5_password) {
		//note 转至邮箱验证页
		if($returnurl){
			MooMessage("用户密码错误","{$returnurl}",'01');
		}else{
			MooMessage("用户密码错误","login.html",'01');
		}
		exit();
	}

	//note 验证通过
	if($user['uid'] && $user['password'] == $md5_password) {


		if($user['automatic']==1){
	        MooSetCookie('auth',MooAuthCode("$user[uid]\t$user[password]",'ENCODE'),86400*7);
		}else{
		    MooSetCookie('auth',MooAuthCode("$user[uid]\t$user[password]",'ENCODE'),86400);
		}
		// MooSetCookie('auth','SDFSFGAFGD\AHFGHGHJ',86400);
		$time = time();
		$_MooClass['MooMySQL']->query("update {$dbTablePre}members_login set last_login_time = '{$time}',login_meb = login_meb+1,real_lastvisit='{$time}' where uid = '{$user['uid']}'");//更新最后登录时间
		//会员最后登录时间

        MooSetCookie('last_login_time', $time,86400);

		//note 客服提醒
		if($user['is_awoke'] == '1'){
			$awoketime = time()+120;
			$sql = "INSERT INTO `{$dbTablePre}admin_remark` SET sid='{$user['sid']}',title='会员上线',content='ID:{$user['uid']}会员刚刚通过pc机上线,请联系',awoketime='{$awoketime}',dateline='{$GLOBALS['timestamp']}'";
			$_MooClass['MooMySQL']->query($sql);
		}
		//note 记住用户名
		if($remember_username == 1){
			MooSetCookie('username',$username,time()+3600);
		}else{
			MooSetCookie('username',$username,-1200);
		}
//		echo print_r($_MooCookie);die;

		MooPlugins('ipdata');
		$online_ip = GetIP();




		if($online_ip != $user['lastip']){

			$user_address = convertIp($online_ip);

		    include("./module/crontab/crontab_config.php");

		    foreach($provice_list as $key=>$provice_arr){
		        if(strstr($user_address,$provice_arr)!==false){
		       	//if(){
		            $province=$key;
		            break;
		        }
		    }

		    if(empty($province)){
		        $province=$current_user['province'];
		    }


		    //得到市对应的城市代号
		    foreach($city_list as $city_key => $city_val){
		      if(strstr($user_address,$city_val)!==false){
		         $city = $city_key;
		         break;
		      }
		    }


		}


		MooSetCookie('province', $user['province'],86400);
		MooSetCookie('city', $user['city'],86400);


		$lastactive = time();
		$uid = $user['uid'];


		//note 更新用户的最近登录ip和最近登录时间
		$updatesqlarr = array(
			'lastip' => $online_ip,
			'lastvisit'=> $lastactive
		);
		$wheresqlarr = array(
			'uid' => $uid
		);
		updatetable("members_login",$updatesqlarr,$wheresqlarr);
		if(MOOPHP_ALLOW_FASTDB){
			$val = array();
            $val['lastip'] = $online_ip;
            //$val['client']=0;
            $val['last_login_time']= $time;
            $val['lastvisit']=$time;
            //$val['isOnline']=1;
			MooFastdbUpdate('members_login','uid',$uid, $val);//!!
		}


		//记录本次登录ip及上次的ip,及真实的最后访问时间
		$sql_ip = "SELECT last_ip,finally_ip FROM {$GLOBALS['dbTablePre']}member_admininfo WHERE uid='{$uid}'";
		$member_admin_info = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql_ip);
		if(!empty($member_admin_info)){
			$sql_ip = "UPDATE {$GLOBALS['dbTablePre']}member_admininfo SET last_ip='{$member_admin_info['finally_ip']}',finally_ip='{$online_ip}',real_lastvisit='{$lastactive}' WHERE uid='{$uid}'";
		}else{
			$sql_ip = "INSERT INTO {$GLOBALS['dbTablePre']}member_admininfo SET finally_ip='{$online_ip}',uid='{$uid}',real_lastvisit='{$lastactive}'";
		}
		$GLOBALS['_MooClass']['MooMySQL']->query($sql_ip);


		//note 先删除表里面已存在对应用户的session
		//$_MooClass['MooMySQL']->query("DELETE FROM `{$dbTablePre}membersession` WHERE `uid` = '$uid'");
		//删除过期SESSION
		//$date=time()-1200;
		//$_MooClass['MooMySQL']->query("DELETE FROM `{$dbTablePre}membersession` WHERE lastactive<'$date'");

		//$_MooClass['MooMySQL']->query("REPLACE INTO `{$dbTablePre}membersession` SET `username`= '$user[username]',`password`='$user[password]',`ip` = '$online_ip',`lastactive` = '$lastactive',`uid` = '$uid'");
		//发送短信提醒关注的会员上线了
		//fanglin暂时屏蔽
		/*if($_MooCookie['iscontact'] != "yes"){
		   MooSend_Contact($uid,$user['gender'],"system");
		}


		//note 伪造用户访问
		$selectuser=selectuser($user['province'],$user['city'],$user['birthyear'],$user['gender']);
		$time=strtotime(date('Y-m-d'))-24*60*60;
		for($i=0;$i<count($selectuser);$i++){
		      $result=$_MooClass['MooMySQL']->getOne("SELECT `uid` FROM `".$dbTablePre."service_visitor` WHERE `uid`='".$selectuser[$i]['uid']."' AND `visitorid`='".$user['uid']."' AND `who_del`!=2");
		      if($result['uid']==''){
		              $_MooClass['MooMySQL']->query("INSERT INTO `".$dbTablePre."service_visitor` SET `uid`='".$selectuser[$i]['uid']."',`visitorid`='".$user['uid']."',`visitortime`='".$time."',`who_del`=1");
		      }else $_MooClass['MooMySQL']->query("UPDATE `".$dbTablePre."service_visitor` SET `visitortime`='".$time."' WHERE `uid`='".$selectuser[$i]['uid']."' AND `visitorid`='".$user['uid']."'");

		}*/
		//note 转至邮箱验证页


		if($returnurl){
			header("Location:".$returnurl);
		}else{
			header("Location:index.php");
		}
		exit();
	}
}

/**
 * 登录首页
 * 描述：
 *
 */
function index_index() {
	global $_MooClass,$dbTablePre,$userid,$user_arr,$memcached;
	$sql_condition_province=$sql_condition_city=$province='';
	$currentdistrict = '0';$arr_diamond_female=$arr_diamond_male=null;
	$provice_list=$city_list=$province_othercachefile=null;
	$s_cid = 40;
    
	$url=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    MooSetCookie('where_from',$url,300);

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

	$userList = $userList2 = array();
	$index_total_user = 24;//首页总用户数
	//note 当前年份
	$year = date('Y');

	//登录用户查找性别
	$sql_gender = '';

	//$between = ' birthyear between ' . ($year-28).' and '.($year-22);

	//性别取反
	if($GLOBALS['MooUid']){
		$gender = $user_arr['gender'] == 1 ? 0 : 1;
		$sql_gender = " and gender='$gender'";
		$sql_condition_province .= $sql_gender;
		$sql_condition_city .= " and gender='$gender'";
	}

	$date_time=date('d');
	$month_time=date('m');
	$reguser_num=30000+$month_time*1100+$date_time*191;
   
    /*
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
			MooMessage("非常抱歉!此会员资料已关闭！", 'index.php','01');
			exit;
		}
		//note uid数组转换成字符串
		$index_img_num = 8;
		$index_img = index_img($currentdistrict,$index_img_num);
		$small_img_num = 3;
		$small_img = small_img($currentdistrict,$small_img_num,$workprovince);
		$user_arr1 = array();
		foreach ($userid as $v1){
			$user_arr1[] = $v1['uid'];
		}

		array_unshift($user_arr1,'30372253');

		//note 获得用户id
		$userid_list =  implode(",",$user_arr1);

		$sql = "select s.uid,s.gender,s.images_ischeck,s.nickname,s.province,s.city,s.birthyear,s.city_star,s.s_cid,b.mainimg from {$dbTablePre}members_search s left join {$dbTablePre}members_base b on s.uid=b.uid where s.uid in ($userid_list) order by s.city_star desc, s.s_cid asc";

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
				if(($_GET['province']==$key)){
					$currentdistrict=$val;
					$province = $key;
					break;
				}
            }

	    }
	  



		//得到市对应的城市代号

		foreach($city_list as $city_key => $city_val){

			if(strstr($ip_arr,$city_val)){
				$city = $city_key;
				break;
			}
		}


		if (isset($_GET['province'])) $province=MooGetGPC('province','integer','G');

		if($province){
			//默认快速查询没有进入
			if(isset($_GET['city2'])?$_GET['city2']:''){
				$city = MooGetGPC('city2','integer','G');
			}else{
				$city = '0';
			}
			//$province = MooGetGPC('province','integer','G');

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
				 if (empty($cachefile['provincestarfile'])) continue;
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




		//市无城市之星，从省取城市之星
		if(empty($userList)){

			
			$sql="select s.uid from  {$dbTablePre}members_search as s left join {$dbTablePre}members_base b on s.uid=b.uid WHERE (s.province='{$province}'
			          AND s.is_lock=1
			          AND s.showinformation=1
					   AND s.images_ischeck = 1
			          and s.city_star>=1 and s.usertype=1) LIMIT 32";
			$getuserid= $_MooClass['MooMySQL']->getAll($sql);

			//$getuserid=array_merge($getuserid,array(array("uid"=>"30831426"),array("uid"=>"30659888"),array("uid"=>"30394569 ")));




			$getuserlistid="";
			$comma="";
            foreach($getuserid as $v){
			   $getuserlistid .=$comma.$v['uid'];
			   $comma=",";
            }
			if (!isset($getuserid[0])) {
				$getuserlistid = '0';
			}


			$sql="select s.uid,s.nickname,s.gender,s.images_ischeck,s.province,s.city,s.birthyear,s.s_cid,s.city_star,b.mainimg,s.is_lock from  {$dbTablePre}members_search as s left join {$dbTablePre}members_base b on s.uid=b.uid WHERE s.uid in ({$getuserlistid}) order  by  find_in_set(s.uid, '$getuserlistid')";

			$userList = $_MooClass['MooMySQL']->getAll($sql);
			 $param = ("type=query/name=userlist_{$province}_provincestar/sql=$sql/cachetime=600");
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
				    $sql = "select s.uid,s.nickname,s.gender,s.images_ischeck,s.province,s.city,s.birthyear,s.s_cid,s.city_star,b.mainimg from {$dbTablePre}members_search as s left join {$dbTablePre}members_base as b on s.uid=b.uid  where (s.uid not in({$user_in})
					          AND {$sql_add} s.city_star=0 AND s.s_cid = 20
					          AND s.images_ischeck  = 1 AND s.is_lock = 1 AND s.showinformation = 1
					          AND s.pic_num >=1 AND s.usertype=1)
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
					$sql = "SELECT s.uid,s.nickname,s.gender,s.images_ischeck,s.province,s.city,s.birthyear,s.s_cid,s.city_star,b.mainimg,s.pic_num FROM {$dbTablePre}members_search s left join {$dbTablePre}members_base b on s.uid=b.uid WHERE s.uid='{$list['uid']}'";
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

	}*/
	
	/* $platinum = $_MooClass['MooMySQL']->getAll("SELECT s.uid,s.nickname,s.gender,s.images_ischeck,s.province,s.city,s.birthyear,s.s_cid,s.city_star,b.mainimg,s.pic_num FROM {$dbTablePre}members_search s left join {$dbTablePre}members_base b on s.uid=b.uid WHERE s.uid in (30831426,30659888,30394569,30665802,34981195)");
	$userList = array_merge($platinum, $userList); 
	
	$userListUp=$userListDown=array();
	//删除数组中多余的部分
	array_unique($userList);
	array_splice($userList, 24);

	$userListUp=array_slice($userList,0,11);

	$userListDown=array_slice($userList,12,12); 

	//var_dump($userListDown);
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
    $num_male=$result_male['num']; */
	
    /*if ($num_female>=5 && $num_male>=5){

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

	} */
    
	include("./module/crontab/crontab_config.php");
	$uidList='30189630,30130525,30115979 ,30062353,30174298 ,30189647,30151696 ,30094405,30074391,30118484,30075888,30195034,30220706,30057803';
	//$sql="SELECT s.uid,s.nickname,s.gender,s.images_ischeck,s.province,s.city,s.birthyear,s.s_cid,s.city_star,b.mainimg,s.pic_num FROM {$dbTablePre}members_search s left join {$dbTablePre}members_base b on s.uid=b.uid WHERE s.is_lock=1 and s.showinformation=1 and s.gender=0 and s.images_ischeck=1  and s.usertype=1 and s.s_cid<=30 order by s.s_cid asc limit 7";
	$sql="SELECT s.uid,s.nickname,s.gender,s.images_ischeck,s.province,s.city,s.birthyear,s.s_cid,s.city_star,b.mainimg,s.pic_num FROM {$dbTablePre}members_search s left join {$dbTablePre}members_base b on s.uid=b.uid WHERE s.uid in ({$uidList})";
	$userList = $_MooClass['MooMySQL']->getAll($sql);
 
	

	//成功案例
	$sql = "select s.sid,s.uid,s.title,s.content,s.name1,s.name2,sp.img,s.story_date from `web_story` as s left join  `web_story_pic` as sp  on s.sid=sp.sid where s.syscheck=1 and s.recommand= '1' and sp.sid in (69,341,342,343,340,330,333,318,319,320,321,322,323,324,325) GROUP BY sp.sid order by s.story_date desc,sp.img desc limit 0, 4";
	$story = $_MooClass['MooMySQL']->getAll($sql);
	
	
	//滚动文字
	$time=time();$textInfo=array();
	$sql="SELECT  content from `{$dbTablePre}text_show` where start_time<{$time} and end_time>={$time} and `show`=1 order by `order` asc";
	$textInfo=$_MooClass['MooMySQL']->getAll($sql);
	//if(MooGetGPC('t','integer','G')==1){
	    require MooTemplate('public/indexTest', 'module');
	//}else{
	//    require MooTemplate('public/index', 'module');
	//}


}



/**
 * 退出登录
 * 描述：
 *
 */
function login_logout(){
	global $_MooClass,$user_arr;
	//note 清空cookie，同时清空session表记录
	//clearcookie();
	header("Expires: Mon 26 Jul 1997 05:00:00 GMT");
	header("Last-Modified: " . gmdate("D d M Y H:i:s") . " GMT");
	header("Cache-Control: no-store no-cache must-revalidate");
	header("Cache-Control: post-check=0 pre-check=0",false);
	header("Pragma: no-cache");
	MooClearCookie();
	//$_MooClass['MooMySQL']->query("update web_members set isOnline=0 where uid = '{$user_arr['uid']}'");//更新在线状态
	header("Location:index.php?n=index");
}
//note 通过ID查找该ID号会员的性别
function getSex($id){
	global $_MooClass,$dbTablePre;
	if(MOOPHP_ALLOW_FASTDB){
		$sex = MooFastdbGet('members_search','uid',$id);
	}else{
		$sex = $_MooClass['MooMySQL']->getOne("select gender from {$dbTablePre}members_search where uid='$id'");
	}
	return $sex['gender'];
}


function index_govip(){
	global $_MooClass,$user_arr,$uid;
	if(empty($uid)) header("location:login.html");
	require MooTemplate('public/vip', 'module');
}
function index_add_vip(){
	global $_MooClass,$user_arr;
	if(isset($user_arr['s_cid'])){
		$back_url='index.php?n=index&h=add_vip&s_cid='.$user_arr['s_cid'];
	}else{
		$back_url='index.php?n=index&h=add_vip';
	}
	if(!$user_arr['s_cid']){
		header("location:login.html"); //&back_url=".urlencode($back_url)
	}else{
		$s_cid =MooGetGPC('s_cid','int','R');
	}
	require MooTemplate('public/add_vip', 'module');
}

/***************************************   控制层(C)   ****************************************/

switch ($_GET['h']) {
	case "submit" :
		login_submit();
		break;
	case "logout" :
		login_logout();
		break;
	case "govip" :
		index_govip();
		break;
	case "add_vip" :
		index_add_vip();
		break;
	default :
		index_index();
	}
?>
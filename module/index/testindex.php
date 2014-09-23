<?php
include "module/index/function.php";
/**
 * 登录表单处理
 * 描述：
 */
function login_submit() {
	global $_MooClass,$dbTablePre,$_MooCookie;  
	//noet 对提交的数据过滤
	if($_POST) {
		$username = trim($_POST['username']);
		$md5_password = md5($_POST['password']);
		$cookietime = intval(isset($_POST['cookietime'])?$_POST['cookietime']:'');
		$remember_username = MooGetGPC('remember','integer','P');
	}
/*****设置回转的页面*****/
	$returnurl = MooGetGPC('returnurl','string', "P");	
	//echo $returnurl;
	//exit;
	//note 要填写用户和密码
	if(empty($_POST['username']) && empty($_POST['password'])) {
		//note 转至邮箱验证页
		if($returnurl){
			$returnurl = $returnurl;
			MooMessage("请填写用户名和密码","{$returnurl}",'03');
		}else{
			MooMessage("请填写用户名和密码","index.php?n=login",'03');
		}
		exit();
	}
	
	//note 用户名不能为空
	if(empty($_POST['username'])) {
		//note 转至邮箱验证页
		if($returnurl){
			$returnurl = $returnurl;
			MooMessage("用户名不能为空","{$returnurl}",'03');
		}else{
			MooMessage("用户名不能为空","index.php?n=login",'03');
		}
		exit();
	}
	
	//note 密码不能为空
	if(empty($_POST['password'])) {
		//note 转至邮箱验证页
		if($returnurl){
			$returnurl = $returnurl;
			MooMessage("密码不能为空","{$returnurl}",'03');
		}else{
			MooMessage("密码不能为空","index.php?n=login",'03');
		}
		exit();
	}
	
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
			if($user_one = $_MooClass['MooMySQL']->getOne("SELECT uid from `{$dbTablePre}members_base` where qq='{$username}'"))
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
		$user_one = $_MooClass['MooMySQL']->getOne("SELECT uid from `{$dbTablePre}members_search` where $login_where");
		if($user_one){
			$userid = $user_one['uid'];
		}else{
			//note 转至邮箱验证页
			if($returnurl){
				MooMessage("用户名不存在","{$returnurl}",'01');
			}else{
				MooMessage("用户名不存在","index.php?n=login",'01');
			}
		}
	}
	//获取
	$user = array_merge(MooGetData('members_login', 'uid', $userid), MooMembersData($userid));
	echo $user['is_lock'];exit;
	if($user['is_lock']!='1'){
		MooMessage("很抱歉您的用户名已经被锁定！<br>请联系真爱一生网客服：<b>400-678-0405</b>","{$returnurl}",'01',6);
	}
	//note 用户密码错误
	if($user['uid'] && $user['password'] != $md5_password) {
		//note 转至邮箱验证页
		if($returnurl){
			$returnurl =$returnurl;
			MooMessage("用户密码错误","{$returnurl}",'01');
		}else{
			MooMessage("用户密码错误","index.php?n=login",'01');
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
		$time = time();
		$_MooClass['MooMySQL']->query("update {$dbTablePre}members_login set last_login_time = '{$time}',login_meb = login_meb+1,lastvisit='{$time}' where uid = '{$user['uid']}'");//更新最后登录时间
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
			$returnurl = $returnurl;
			header("Location:".$returnurl);
		}else{
			header("Location:index.php?n=service");
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
	
	

	//note 先初始化用户信息
	//MooUserInfo();
	//-------会员推荐------//
	
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
	$index_total_user = 12;//首页总用户数
	//note 当前年份
	$year = date('Y');
	
	//登录用户查找性别
	$sql_gender = '';

	
	
	//性别取反
	if($GLOBALS['MooUid']){
		$gender = $user_arr['gender'] == 1 ? 0 : 1;
		/* $sql_gender = " and gender='$gender'";
		$sql_condition_province .= $sql_gender;
		$sql_condition_city .= " and gender='$gender'"; */
	}else{
	    $gender = !MooGetGPC('gender','integer','G') ? 0 : 1;//note 性别
	}
	
	
	
	$date_time=date('d');
	$month_time=date('m');
	$reguser_num=30000+$month_time*1100+$date_time*191;
	//新加滚动文字
	/*$time_list = time();
	$sql = "select content from {$dbTablePre}text_show where start_time<'$time_list' and end_time>'$time_list' order by reg_time desc limit 0,15";
	$text_list = $_MooClass['MooMySQL']->getAll($sql);*/
	//显示男姓or显示女生搜索
	
	/* if(isset($workprovince) && isset($workcity) && isset($age1) && $age2){
		//$sql = "select * from {$dbTablePre}members where gender=$sex and images_ischeck=1 ";
		include_once("./module/crontab/crontab_config.php");
		//note 从快速搜索表中查询
		if($gender == '0'){
			$sql = "select uid from {$dbTablePre}members_search where  images_ischeck=1 and gender='0'";				
		}
		
		if($gender == '1'){
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
		$index_img_num = 5;
		$index_img = index_img($currentdistrict,$index_img_num);
		$small_img_num = 3;
		$small_img = small_img($currentdistrict,$small_img_num);
		$user_arr1 = array();
		foreach ($userid as $v1){
			$user_arr1[] = $v1['uid'];
		}
		//note 获得用户id
		$userid_list =  implode(",",$user_arr1);
		$sql = "select s.uid,s.gender,s.images_ischeck,s.nickname,s.province,s.city,s.birthyear,s.s_cid,s.city_star,b.mainimg,i.introduce from {$dbTablePre}members_search s left join {$dbTablePre}members_base b on s.uid=b.uid left join {$dbTablePre}members_introduce i on s.uid=i.uid where s.uid in ($userid_list) order by s.city_star desc, s.s_cid asc";
		
		//note 查询出来的用户信息写入缓存
		$md5 = md5($sql);
		$index_search = ("type=query/name=userList_{$md5}/sql=$sql/cachetime=86400");
		
		$_MooClass['MooCache']->getBlock($index_search);
		$userList = $GLOBALS['_MooBlock']["userList_{$md5}"];
		
	}
	
	else{ */
	
	
		//默认进入
		//包含配置文件
		include_once("./module/crontab/crontab_config.php");
		$cur_ip = GetIP();
		

//		$cur_ip = "61.190.44.98";
//		$cur_ip = "202.120.2.200";//上海ip
//		$cur_ip = "218.202.206.102";//新疆ip
		//$cur_ip = "218.70.178.197"; //重庆
//		$cur_ip="221.130.166.242";

		
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
					
					break;
				}
            }
			
	    }
	    $test = array();
	    if(isset($currentdistrict)){
			$index_img_num = 5;
			$index_img = index_img($currentdistrict,$index_img_num);
			$small_img_num = 3;
			$small_img = small_img($currentdistrict,$small_img_num);
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
			
			
		    /* $sql = "SELECT * FROM {$dbTablePre}index_cachefile WHERE province='{$province}' AND city='{$city}'";
			$cachefile_list = $_MooClass['MooMySQL']->getAll($sql);
			foreach($cachefile_list as $cachefile){
				if(file_exists("data/block/{$cachefile['provincestarfile']}")){
					@unlink("data/block/{$cachefile['provincestarfile']}");
				}
				if(file_exists("data/block/{$cachefile['citystarfile']}")){
					@unlink("data/block/{$cachefile['citystarfile']}");
				}
				if(file_exists("data/block/{$cachefile['provinceotherfile']}")){
					@unlink("data/block/{$cachefile['provinceotherfile']}");
				}
			}  */ 
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

			 $sql = "SELECT s.uid,s.nickname,s.gender,s.images_ischeck,s.province,s.city,s.birthyear,s.s_cid,s.city_star,b.mainimg,i.introduce FROM {$dbTablePre}members_search as s left join {$dbTablePre}members_base b on s.uid=b.uid  left join {$dbTablePre}members_introduce as i on s.uid=i.uid WHERE  s.s_cid='20' and s.gender='{$gender}' and ({$sql_city} AND s.is_lock=1 AND s.images_ischeck=1 and s.nickname!='' AND s.showinformation=1 AND s.city_star>=1 AND s.usertype=1) AND i.introduce!='' order by s.s_cid limit 6";
			
			//$userList = $_MooClass['MooMySQL']->getAll($sql);
			$param = ("type=query/name=userlist_{$city}_citystar/sql=$sql/cachetime=86400");
			
			$_MooClass['MooCache']->getBlock($param);//
			$userList = $GLOBALS['_MooBlock']["userlist_{$city}_citystar"];
			//市缓存文件
			//$city_cachefile = "userlist_{$city}_citystar".'_'.md5($param).'.data';
			
		}
	   
       
		//市无城市之星，从省取城市之星
		if(empty($userList)){
			//默认进入
			//enky note
			//$sql="select * from {$dbTablePre}members_base as b left join {$dbTablePre}members_search as s on b.uid=s.uid WHERE s.province='$province'  AND s.is_lock=1        AND s.images_ischeck = 1 AND s.showinformation=1      and s.city_star>=1 and s.usertype=1 AND s.uid!=20752315 LIMIT 20";
			
			$sql="select s.uid,s.nickname,s.gender,s.images_ischeck,s.province,s.city,s.birthyear,s.s_cid,s.city_star,b.mainimg,i.introduce from  {$dbTablePre}members_search as s left join {$dbTablePre}members_base as b on s.uid=b.uid left join {$dbTablePre}members_introduce as i on s.uid=i.uid WHERE s.province='$province'
			          AND s.is_lock=1   and  s.gender={$gender}
			          AND s.images_ischeck = 1 AND s.showinformation=1 
			          and s.city_star>=1 and s.usertype=1 AND i.introduce!='' and s.nickname!='' LIMIT 6";
	
//			$userList = $_MooClass['MooMySQL']->getAll($sql);
			$param = ("type=query/name=userlist_{$province}_provincestar/sql=$sql/cachetime=86400");
			$_MooClass['MooCache']->getBlock($param);
			$userList = $GLOBALS['_MooBlock']["userlist_{$province}_provincestar"];
			
			//省缓存文件
			//$province_cachefile = "userlist_{$province}_provincestar".'_'.md5($param).'.data';
		}
		
	
		
		//城市之星不够取钻石会员（市->省->全国）
		$count = count($userList);
		if($count < 6) {
		    
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
				if($count < 6) {
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
					$add_query_sum = 6 - $count;
					
				    //enky need change
					
					$cl = searchApi('members_women members_man');
					$cond[] = array('is_lock','1',false);
					$cond[] = array('images_ischeck','1',false);
					$cond[] = array('gender',$gender,false);
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
						$introduce_res = $_MooClass['MooMySQL']->getAll("select introduce from web_members_introduce where uid in "."($sql_str)");
						//合并到sphinx查询出的整理后的数组
						for($i=0;$i<$count_res;$i++){
							if(!empty($res_matches['matches'][$i]['attrs'])){
							$res_matches['matches'][$i]['attrs']['nickname']= $nickname_res[$i]['nickname'];
							$res_matches['matches'][$i]['attrs']['uid']= $nickname_res[$i]['uid'];
							$res_matches['matches'][$i]['attrs']['mainimg']=$mainimg_res[$i]['mainimg'];
							$res_matches['matches'][$i]['attrs']['introduce']=$introduce_res[$i]['introduce'];
							
							$bigarr[] = $res_matches['matches'][$i]['attrs'];
							}
						}
						$$add = $bigarr;
		
					
					}else{
						$sql = "select s.uid,s.nickname,s.gender,s.images_ischeck,s.province,s.city,s.birthyear,s.s_cid,s.city_star,b.mainimg,i.introduce from {$dbTablePre}members_search as s left join {$dbTablePre}members_base as b on s.uid=b.uid left join {$dbTablePre}members_introduce as i on s.uid=i.uid  where s.uid not in({$user_in})
								  AND {$sql_add} s.city_star=0 AND s.s_cid = 20  and s.gender={$gender}
								  AND s.images_ischeck  = 1 AND s.is_lock = 1 AND s.showinformation = 1
								  AND s.pic_num >=1 AND s.usertype=1 AND i.introduce!='' and s.nickname!=''  LIMIT $add_query_sum";
					
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
			//$province_othercachefile = "userlist_{$id}_other".'_'.md5($param).'.data';
		}
		
		//生成的block缓存文件存库
	    /* $city = $city ? $city : 0;
		$sql = "SELECT * FROM {$dbTablePre}index_cachefile WHERE  province='{$province}' AND city='{$city}'";
		$cache_arr = $_MooClass['MooMySQL']->getAll($sql);
		
		if(empty($cache_arr)){
			$province_cachefile = isset($province_cachefile)?$province_cachefile:'';
			$sql = "INSERT INTO {$dbTablePre}index_cachefile SET province='{$province}',city='{$city}',provincestarfile='{$province_cachefile}',citystarfile='{$city_cachefile}',provinceotherfile='{$province_othercachefile}'";
			$_MooClass['MooMySQL']->query($sql);
		}  */
	
		//推荐列表中如果存在，则以推荐列表中的指定的sort替换
		$sql = "SELECT * FROM {$dbTablePre}members_recommend WHERE  province='{$province}' AND city='$city' order by sort asc";
		$recommend_list = $_MooClass['MooMySQL']->getAll($sql);
		
		if(!empty($recommend_list)){
			foreach($recommend_list as $list){
				$sort = $list['sort'] < 1 ? 0 : $list['sort']-1;
				if(MOOPHP_ALLOW_FASTDB){
					$u = MooFastdbGet('members_search','uid',$list['uid']);
					$u2 = MooFastdbGet('members_base','uid',$list['uid']);
					$u = array_merge($u,$u2);
				}else{
					$sql = "SELECT s.uid,s.nickname,s.gender,s.images_ischeck,s.province,s.city,s.birthyear,s.s_cid,s.city_star,b.mainimg,i.introduce FROM {$dbTablePre}members_search s left join {$dbTablePre}members_base b on s.uid=b.uid left join {$dbTablePre}members_introduce as i on s.uid=i.uid WHERE s.uid='{$list['uid']}'";
					$u = $_MooClass['MooMySQL']->getOne($sql);
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
			
	// }
	
	
	//删除数组中多余的部分
	array_splice($userList, 6);
	
    $userArr=array();
	$i=0;
	$imgSrcSmall=$imgSrcMid=$imgSrcUrl='';
	foreach($userList as  $v){
	  
	  $introduce='';
	  if(isset($v['introduce'])){
	     $introduce=MooCutstr($v['introduce'],50,'...');
	  }
	  $imgSrcSmall=MooGetphoto($v['uid'],'small');
	  $imgSrcMid=MooGetphoto($v['uid'],'mid');
	  
	  if($imgSrcSmall){
	     $imgSrc=$imgSrcMid;
	  }elseif($v['gender'] == '1'){
 	     $imgSrc="module/index/templates/default/images/se_woman.gif";
	  }else{
 	     $imgSrc="module/index/templates/default/images/se_man.gif";
	  }			                    
	  
	  if(isset($v['mainimg']) && !empty($v['mainimg']) && isset($v['images_ischeck']) && $v['images_ischeck']==1){
          $imgUrl="<a href=\"index.php?n=space&uid={$v['uid']}\" target=\"_blank\"><img src={$imgSrc} width=\"120\"/> </a>";
	  }else{
          if(isset($v['gender']) && $v['gender']){
   		    $imgUrl="<a href=\"index.php?n=space&uid={$v['uid']}\" target=\"_blank\"><img src=\"module/index/templates/default/images/se_woman.gif\" /> </a> ";
		  }else{
 		    $imgUrl="<a href=\"index.php?n=space&uid={$v['uid']}\" target=\"_blank\"><img src=\"module/index/templates/default/images/se_man.gif\" /></a> ";
		  }
	  }
	  
	  if($v['birthyear']){
	     $age=date('Y')-$v['birthyear'].'岁';
	  }else{
	     $age='年龄保密';
	  }
	  
      
	  
	  $userArr[]=array("i"=>$i,"uid"=>$v['uid'],"mainimg"=>$v['mainimg'],"images_ischeck"=>$v['images_ischeck'],"gender"=>$v['gender'],"introduce"=>$introduce,"imgUrl"=>$imgUrl,"nickname"=>$v['nickname'],"age"=>$age,"province"=>$v['province'],"city"=>$v['city']);
	  $i++;
	}
	
	
	//===============最新注册会员，按照片数排序 ，男会员=========================
	$newMale=array();
	$param = ("type=query/name=new_members_male/sql=SELECT s.uid,s.nickname,s.gender,s.images_ischeck,s.province,s.city,s.birthyear,s.s_cid,s.city_star,b.mainimg,i.introduce FROM {$dbTablePre}members_search as s left join {$dbTablePre}members_base as b on s.uid=b.uid  left join {$dbTablePre}members_introduce as i on s.uid=i.uid where  s.gender=0 and s.pic_num>4 and s.images_ischeck=1 and s.is_lock=1 and i.introduce!='' and s.nickname!='' order by s.uid desc limit 3/cachetime=10800");
	$_MooClass['MooCache']->getBlock($param);
	$new_reguser_male = isset($GLOBALS['_MooBlock']['new_members_male'])?$GLOBALS['_MooBlock']['new_members_male']:'';
	
	foreach($new_reguser_male as  $v){
	  
	  $introduce=MooCutstr($v['introduce'],14,'...');
	  $imgSrcSmall=MooGetphoto($v['uid'],'small');
	  $imgSrcMid=MooGetphoto($v['uid'],'mid');
	  
	  if($imgSrcSmall){
	     $imgSrc=$imgSrcMid;
	  }elseif($v['gender'] == '1'){
 	     $imgSrc="module/index/templates/default/images/se_woman.gif";
	  }else{
 	     $imgSrc="module/index/templates/default/images/se_man.gif";
	  }			                    
	  
	  if(isset($v['mainimg']) && !empty($v['mainimg']) && isset($v['images_ischeck']) && $v['images_ischeck']==1){
          $imgUrl="<a href=\"index.php?n=space&uid={$v['uid']}\" target=\"_blank\"><img src={$imgSrc} width=\"120\"/> </a>";
	  }else{
          if(isset($v['gender']) && $v['gender']){
   		    $imgUrl="<a href=\"index.php?n=space&uid={$v['uid']}\" target=\"_blank\"><img src=\"module/index/templates/default/images/se_woman.gif\" /> </a> ";
		  }else{
 		    $imgUrl="<a href=\"index.php?n=space&uid={$v['uid']}\" target=\"_blank\"><img src=\"module/index/templates/default/images/se_man.gif\" /></a> ";
		  }
	  }
	  
	  if($v['birthyear']){
	     $birthyear=date('Y')-$v['birthyear'].'岁';
	  }else{
	     $birthyear='年龄保密';
	  }
	  
	  if($v['nickname']){
	     $nickname=MooCutstr($v['nickname'],15,'');
	  }else{
	     $nickname='ID:'.$v['uid'];
	  }
	  
	  $newMale[]=array("uid"=>$v['uid'],"mainimg"=>$v['mainimg'],"images_ischeck"=>$v['images_ischeck'],"gender"=>$v['gender'],"introduce"=>$introduce,"imgUrl"=>$imgUrl,"nickname"=>$nickname,"birthyear"=>$birthyear,"province"=>$v['province'],"city"=>$v['city']);
	
	}
	
	//==================最新注册会员，按照片数排序 ，女会员==========================
	$param = ("type=query/name=new_members_female/sql=SELECT s.uid,s.nickname,s.gender,s.images_ischeck,s.province,s.city,s.birthyear,s.s_cid,s.city_star,b.mainimg,i.introduce FROM {$dbTablePre}members_search as s left join {$dbTablePre}members_base as b on s.uid=b.uid  left join {$dbTablePre}members_introduce as i on s.uid=i.uid where  s.gender=1 and s.pic_num>4 and s.images_ischeck=1 and s.is_lock=1 order by s.uid desc limit 3/cachetime=10800");
	$_MooClass['MooCache']->getBlock($param);
	$new_reguser_female = isset($GLOBALS['_MooBlock']['new_members_female'])?$GLOBALS['_MooBlock']['new_members_female']:'';
	
	foreach($new_reguser_female as  $v){
	  
	  $introduce=MooCutstr($v['introduce'],14,'...');
	  $imgSrcSmall=MooGetphoto($v['uid'],'small');
	  $imgSrcMid=MooGetphoto($v['uid'],'mid');
	  
	  if($imgSrcSmall){
	     $imgSrc=$imgSrcMid;
	  }elseif($v['gender'] == '1'){
 	     $imgSrc="module/index/templates/default/images/se_woman.gif";
	  }else{
 	     $imgSrc="module/index/templates/default/images/se_man.gif";
	  }			                    
	  
	  if(isset($v['mainimg']) && !empty($v['mainimg']) && isset($v['images_ischeck']) && $v['images_ischeck']==1){
          $imgUrl="<a href=\"index.php?n=space&uid={$v['uid']}\" target=\"_blank\"><img src={$imgSrc} width=\"120\"/> </a>";
	  }else{
          if(isset($v['gender']) && $v['gender']){
   		    $imgUrl="<a href=\"index.php?n=space&uid={$v['uid']}\" target=\"_blank\"><img src=\"module/index/templates/default/images/se_woman.gif\" /> </a> ";
		  }else{
 		    $imgUrl="<a href=\"index.php?n=space&uid={$v['uid']}\" target=\"_blank\"><img src=\"module/index/templates/default/images/se_man.gif\" /></a> ";
		  }
	  }
	  
	  if($v['birthyear']){
	     $birthyear=date('Y')-$v['birthyear'].'岁';
	  }else{
	     $birthyear='年龄保密';
	  }
	  
	  if($v['nickname']){
	     $nickname=MooCutstr($v['nickname'],15,'');
	  }else{
	     $nickname='ID:'.$v['uid'];
	  }
	  
	  $newFemale[]=array("uid"=>$v['uid'],"mainimg"=>$v['mainimg'],"images_ischeck"=>$v['images_ischeck'],"gender"=>$v['gender'],"introduce"=>$introduce,"imgUrl"=>$imgUrl,"nickname"=>$nickname,"birthyear"=>$birthyear,"province"=>$v['province'],"city"=>$v['city']);
	
	}
	
	
	
	//=================钻石会员   地区切换时真爱一生推荐会员涉及的==================================
	$sql="select count(uid) as num from web_diamond_recommend where  isindex=1  and province='{$province}'";
	$result=$_MooClass['MooMySQL']->getOne($sql);
	$num=$result['num'];
	
	/* $sql="select count(uid) as num from web_diamond_recommend where  isindex=1 and gender='0' and province='{$province}'";
    $result_male=$_MooClass['MooMySQL']->getOne($sql);
    $num_male=$result_male['num']; */
    if ($num>=5){
    	
	   //女钻石会员
       $sql = "select uid,birthyear,province,city,nickname from {$dbTablePre}diamond_recommend where isindex=1  and province='{$province}' order by city<>'{$city}' ,city desc,sort desc  limit 10"; //FEMAIL
       $param = ("type=query/name=web_diamond_recommend/sql=$sql/cachetime=86400");
       $_MooClass['MooCache']->getBlock($param);
       $arr_diamond = $GLOBALS['_MooBlock']['web_diamond_recommend'];
       
       //男钻石会员
       /* $sql_male = "select uid,birthyear,province,city,nickname from {$dbTablePre}diamond_recommend where isindex=1 and gender='0' and province='{$province}' order by city<>'{$city}' ,city desc,sort desc limit 10"; //MAIL
       $param = ("type=query/name=web_diamond_recommend/sql=$sql_male/cachetime=86400");
       $_MooClass['MooCache']->getBlock($param);
       $arr_diamond_male = $GLOBALS['_MooBlock']['web_diamond_recommend']; */
	}else{
		$diamond_filename = "data/cache/diamond_intro.php";
	    if(!file_exists($diamond_filename)){
	        //女钻石会员
	        $sql = "select m.uid,m.birthyear,m.province,m.city,m.nickname,b.mainimg,m.images_ischeck,i.introduce from {$dbTablePre}members_search m left join {$dbTablePre}members_base as b on m.uid=b.uid left join {$dbTablePre}members_introduce as i on m.uid=i.uid left join {$dbTablePre}members_choice c on m.uid=c.uid where m.s_cid='20' and m.usertype='1' and i.introduce_pass='1' and m.is_lock='1' and m.images_ischeck='1'";
	        $sql = $sql . " order by m.pic_num desc,m.bgtime desc limit 10";
	        $arr_diamond = $_MooClass['MooMySQL']->getAll($sql);
	        foreach($arr_diamond as $k=>$v){
	            $diamond_str .= $v['uid'].','; 
	        }
	        
	        //男钻石会员
	        /* $sql_male = "select m.uid,m.birthyear,m.province,m.city,m.nickname,b.mainimg,m.images_ischeck,i.introduce from {$dbTablePre}members_search m left join {$dbTablePre}members_base as b on m.uid=b.uid left join  {$dbTablePre}members_introduce as i on m.uid=i.uid left join {$dbTablePre}members_choice c on m.uid=c.uid where m.s_cid='20' and m.usertype='1' and i.introduce_pass='1' and m.is_lock='1' and m.images_ischeck='1' and m.gender='0'";
	        $sql = $sql_male . " order by m.pic_num desc,m.bgtime desc limit 10";
	        $arr_diamond_male = $_MooClass['MooMySQL']->getAll($sql);
	        foreach($arr_diamond_male as $k=>$v){
	            $diamond_str_male .= $v['uid'].','; 
	        } */
	        
	        //$diamond_str=$diamond_str_female.$diamond_str_male;
	       
	        file_put_contents($diamond_filename,$diamond_str);
	    }else{
	        $arr_diamond = file($diamond_filename);
	        
	        if($arr_diamond){
	        	
	            $star_user = trim($arr_diamond[0],',');
	            
	            $star_user=explode(',',$star_user);
	        
	            if(count($star_user)==10){
	                $star=$star_user[0].','.$star_user[1].','.$star_user[2].','.$star_user[3].','.$star_user[4].','.$star_user[5].','.$star_user[6].','.$star_user[7].','.$star_user[8].','.$star_user[9];
	                //女钻石会员 enky need change
	                $sql = "select m.uid,m.birthyear,m.province,m.city,m.nickname,b.mainimg,m.images_ischeck,i.introduce from {$dbTablePre}members_search m left join {$dbTablePre}members_base as b on m.uid=b.uid left join {$dbTablePre}members_introduce as i on m.uid=i.uid  left join {$dbTablePre}members_choice c on m.uid=c.uid where m.uid in($star)  limit 10"; //FEMAIL
	                $param = ("type=query/name=web_star_recommend/sql=$sql/cachetime=86400");
	                $_MooClass['MooCache']->getBlock($param);
	                $arr_diamond = $GLOBALS['_MooBlock']['web_star_recommend'];
	                
	               /*  $star_male=$star_user[10].','.$star_user[11].','.$star_user[12].','.$star_user[13].','.$star_user[14].','.$star_user[15].','.$star_user[16].','.$star_user[17].','.$star_user[18].','.$star_user[19];
	                //男钻石会员 enky need change
	                $sql_male = "select m.uid,m.birthyear,m.province,m.city,m.nickname,b.mainimg,m.images_ischeck,i.introduce from {$dbTablePre}members_search m left join {$dbTablePre}members_base as b on m.uid=b.uid left join {$dbTablePre}members_introduce as i on m.uid=i.uid  left join {$dbTablePre}members_choice c on m.uid=c.uid where m.uid in($star_male) limit 10"; //MAIL
	                $param = ("type=query/name=web_star_recommend/sql=$sql_male/cachetime=86400");
	                $_MooClass['MooCache']->getBlock($param);
	                $arr_diamond_male = $GLOBALS['_MooBlock']['web_star_recommend']; */
	                
	            }
	        }
	    }

	}
	
	$diamond=array();
	foreach($arr_diamond as $v){
	    if(MooGetphoto($v['uid'],'middle')){
		    $imgSrc=MooGetphoto($v['uid'],'mid');
	    }else{
  		    $imgSrc="module/index/templates/default/images/se_woman.gif";
        }
		
		if($v['nickname']){
		   $nickname=MooCutstr($v['nickname'],6,'');
		}else{
		   $nickname="ID:".$v['uid'];
		}
		
		if ($v['birthyear'] ==''){
		   $age=iconv('gbk','utf-8',"年龄：保密");
		}else{
		   $age=date('Y')-$v['birthyear']."岁";
		}
		
		foreach($provice_list as  $key =>$val){
			if ($v['province']==$key){
				$PROVINCE=$val;
                break;
			} 
		}
		
		foreach($city_list as $key =>$val){
			if($v['city']==$key){
				$CITY=$val;
				break;
			}
		}	
		$diamond[]=array("uid"=>$v['uid'],"imgSrc"=>$imgSrc,"nickname"=>$nickname,"age"=>$age,"province"=>$PROVINCE,"city"=>$CITY);
	
	}
	
    
	
	
    //========================高级会员=============================
	$param = ("type=query/name=members_advance/sql=SELECT s.uid,s.nickname,s.gender,s.images_ischeck,s.province,s.city,s.birthyear,s.s_cid,s.city_star,b.mainimg FROM {$dbTablePre}members_search as s left join {$dbTablePre}members_base as b on s.uid=b.uid  where s.s_cid='30' and  s.images_ischeck=1 and s.is_lock=1 and s.showinformation=1 order by s.uid desc limit 8/cachetime=86400");
	$_MooClass['MooCache']->getBlock($param);
	$members_advance = isset($GLOBALS['_MooBlock']['members_advance'])?$GLOBALS['_MooBlock']['members_advance']:'';
	
	
	$advance=array();
	foreach($members_advance as $v){
	    if(MooGetphoto($v['uid'],'middle')){
     	   $imgSrc=MooGetphoto($v['uid'],'mid');
		}else {
		   $imgSrc="module/index/templates/default/images/se_woman.gif";
		}
		
		if($v['nickname']){
     	   $nickname=MooCutstr($v['nickname'],6,'');
		}else{
		   $nickname="ID:".$v['uid'];
		}
		
		if ($v['birthyear'] ==''){
		   $age=iconv('gbk','utf-8',"年龄：保密");
	    }else{
		   $age=date('Y')-$v['birthyear']."岁";
		}
		
		foreach($provice_list as  $key =>$val){
			if ($v['province']==$key){
				$PROVINCE=$val;
                break;
			} 
		}
		
		foreach($city_list as $key =>$val){
			if($v['city']==$key){
				$CITY=$val;
				break;
			}
		}	
		
		$advance[]=array("uid"=>$v['uid'],"imgSrc"=>$imgSrc,"nickname"=>$nickname,"age"=>$age,"province"=>$PROVINCE,"city"=>$CITY);
	
		
	}
	

	//========================成功案例=============================
	$storyList=array();
	$param = ("type=query/name=story/sql=select s.sid,s.uid,s.title,s.content,s.name1,s.name2,s.story_date,sp.img from `{$dbTablePre}story` as s left join  `{$dbTablePre}story_pic` as sp on s.is_index=sp.mid where sp.syscheck=1 and s.syscheck=1 and s.recommand= '1' order by s.story_date desc,sp.img desc limit 0, 4/cachetime=86400");
	$_MooClass['MooCache']->getBlock($param);
	$story = $GLOBALS['_MooBlock']['story'];
	
	foreach($story as $v){
	    $picsmall = MooGetstoryphoto($v['sid'],$v['uid'],'medium');
        if($picsmall){
		   $imgSrc="<img src=\"".$picsmall."\" width=\"211\" height=\"143\" /> ";
		}else{
  		   $imgSrc="<img src=\"module/index/templates/default/images/story_sample.jpg\" width=\"211\" height=\"143\" />";
		}
		
		$title=MooCutstr($v['title'], 18, $dot = ' ');
		
		$name1=MooCutstr($v['name1'],8,'');
		
		$name1=getSex($v['uid'])?"她".$name1:"他".$name1;
		
		$name2=MooCutstr($v['name2'],8,'');
		$name2=getSex($v['uid'])?'她'.$name2:'他'.$name2;
		
		$date=Date('Y-m-d',$v['story_date']);
		
		$content=MooCutstr($v['content'], 50, $dot = ' ...');
	
	    $storyList[]=array("sid"=>$v['sid'],"imgSrc"=>$imgSrc,"title"=>$title,"name1"=>$name1,"name2"=>$name2,"date"=>$date,"content"=>$content);
	}
	// print_r($storyList);exit;
	
	
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
	
	
	$text_list = text_show();
	
    //echo $province;

	require MooTemplate('public/index_test', 'module');
	
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

//优惠活动
function index_holiday(){
	global $_MooClass,$user_arr;
	require MooTemplate('public/holiday', 'module');
}

function index_govip(){
	global $_MooClass,$user_arr;
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
		header("location:index.php?n=login&back_url=".urlencode($back_url));
	}else{
		$s_cid =MooGetGPC('s_cid','int','R');
	}
	require MooTemplate('public/add_vip', 'module');
}

/***************************************   控制层(C)   ****************************************/

//print_r($_COOKIE);
switch ($_GET['h']) {
	case "submit" :
		login_submit();
		break;
	case "logout" :
		login_logout();
		break;
	case "holiday" :
		index_holiday();
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
<?php
/*******************************************逻辑层(M)/表现层(V)*****************************************/
function site_index(){
		
	require_once(adminTemplate('site_member_recommend'));
}

//搜索地点查看站点首页
function site_search_index(){
	global $_MooClass,$dbTablePre;
	$province = MooGetGPC('workprovince','integer','P');
	$city = MooGetGPC('workcity','integer','P');

	if(in_array( $province, array(10101201,10101002))){
		//note 修正广东省深圳和广州的区域查询 
		$city = $province;
		$province = 10101000;
	}

	if(!isset($province) || !isset($city)){ 
		require_once(adminTemplate('site_member_recommend'));exit;
	}
	if(!isset($province)){
		salert('请选择省市查看');
	}
	//获取该城市的首页展示

	$user_arr = array();
//	$sql = "SELECT * FROM {$dbTablePre}members_recommend WHERE city='$city' and province = '$province' order by uid desc"; // original file
	$sql = "SELECT uid, sort,dateline FROM {$dbTablePre}members_recommend WHERE city='$city' and province = '$province' order by uid desc"; // updated file
	//echo $sql;exit;
	$user_arr = $_MooClass['MooMySQL']->getAll($sql);

	$uid_list = array();
	$user_arr1 = array();
	if($user_arr) {
		foreach($user_arr as $user) {
			$uid_list[] = $user['uid'];
		}
		$allow_uid = implode(',', $uid_list);
		$user_arr1 = $_MooClass['MooMySQL']->getAll("select nickname, birthyear, marriage, gender, city_star, s_cid from {$dbTablePre}members_search where uid in({$allow_uid}) order by uid desc");
		$count = count($uid_list);
		
		for($i = 0; $i < $count; $i++) {
			if(!empty($user_arr1[$i])){
				$user_arr[$i] = array_merge($user_arr[$i], $user_arr1[$i]);
			}
		}
	}
	require_once(adminTemplate('site_member_recommend'));
}


//note 添加首页推荐会员
function site_add_recommend_member(){
	global $memcached;
	$uid = MooGetGPC('uid','integer','P');
	$province = MooGetGPC('province','integer','P');
	$city = MooGetGPC('city','integer','P');
	if(in_array( $province, array(10101201,10101002))){
		//note 修正广东省深圳和广州的区域查询 
		$city = $province;
		$province = 10101000;
	}

	$dateline = time();
	
	$sql = "SELECT COUNT(*) num FROM {$GLOBALS['dbTablePre']}members_recommend WHERE uid = {$uid}";
	$ret = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	if( $ret['num'] ){
		salert("该会员已被推荐过");
		exit;
	}
	
	$sql = "INSERT INTO {$GLOBALS['dbTablePre']}members_recommend(uid, province, city, dateline) values({$uid}, {$province}, {$city}, {$dateline})";
	$GLOBALS['_MooClass']['MooMySQL']->query($sql);
	
	//note 删除缓存文件
//	$sql = "SELECT * FROM {$GLOBALS['dbTablePre']}index_cachefile WHERE province='{$province}' OR province=''"; // original file
	$sql = "SELECT provincestarfile, citystarfile, provinceotherfile  FROM {$GLOBALS['dbTablePre']}index_cachefile WHERE province='{$province}' OR province=''"; // updated file
	$cache_file = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	
	$cache_file['provincestarfile'] = rtrim($cache_file['provincestarfile'],'.data');
	if($memcached->get($cache_file['provincestarfile'])){
		$memcached->delete($cache_file['provincestarfile']);
	}
	$cache_file['citystarfile'] = rtrim($cache_file['citystarfile'],'.data');
	if($memcached->get($cache_file['citystarfile'])){
		$memcached->delete($cache_file['citystarfile']);
	}
	$cache_file['provinceotherfile'] = rtrim($cache_file['provinceotherfile'],'.data');
	if($memcached->get($cache_file['provinceotherfile'])){
		$memcached->delete($cache_file['provinceotherfile']);
	}	
	
	$sql = "SELECT COUNT(*) num FROM {$GLOBALS['dbTablePre']}members_recommend";
	$ret = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	if($ret['num']<=24){
		$sql = "SELECT * FROM {$GLOBALS['dbTablePre']}index_cachefile";
		$cache_file = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
		foreach($cache_file as $file){
			$file['provinceotherfile'] = rtrim($file['provinceotherfile'],'.data');
			if($memcached->get($file['provinceotherfile'])){
				$memcached->delete($file['provinceotherfile']);
			}	
		}
	}
	
	//note 插入日志
	serverlog(3,$GLOBALS['dbTablePre'].'members_recommend','添加首页推荐会员uid='.$uid, $GLOBALS['adminid'],$uid);
        
	salert('添加成功','index.php?action=site&h=index');
}
//note 删除首页推荐会员
function site_del_recommend_member(){
	global $memcached;
	$uid = MooGetGPC('uid','integer','G');
	
	$sql = "SELECT * FROM {$GLOBALS['dbTablePre']}members_recommend WHERE uid = {$uid}";
	$ret = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	
	//note 删除缓存文件
//	$sql = "SELECT * FROM {$GLOBALS['dbTablePre']}index_cachefile WHERE province='{$ret['province']}' OR province=''"; // original file
	$sql = "SELECT provincestarfile, citystarfile, provinceotherfile FROM {$GLOBALS['dbTablePre']}index_cachefile WHERE province='{$ret['province']}' OR province=''"; // updated file
	$cache_file = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	$cache_file['provincestarfile'] = rtrim($cache_file['provincestarfile'],'.data');
	if($memcached->get($cache_file['provincestarfile'])){
		$memcached->delete($cache_file['provincestarfile']);
	}
	$cache_file['citystarfile'] = rtrim($cache_file['citystarfile'],'.data');
	if($memcached->get($cache_file['citystarfile'])){
		$memcached->delete($cache_file['citystarfile']);
	}
	$cache_file['provinceotherfile'] = rtrim($cache_file['provinceotherfile'],'.data');
	if($memcached->get($cache_file['provinceotherfile'])){
		$memcached->delete($cache_file['provinceotherfile']);
	}
	
	$sql = "DELETE FROM {$GLOBALS['dbTablePre']}members_recommend WHERE uid = {$uid}";
	$GLOBALS['_MooClass']['MooMySQL']->query($sql);
	
	//note 插入日志
	serverlog(4,$GLOBALS['dbTablePre'].'members_recommend','删除首页推荐会员uid='.$uid, $GLOBALS['adminid'],$uid);
	
	salert('删除成功','index.php?action=site&h=index');
}

/***********************************************控制层(C)*****************************************/
$h=MooGetGPC('h','string')=='' ? 'index' : MooGetGPC('h','string');
//note 动作列表
$hlist = array('index','add_recommend_member','del_recommend_member','search_index');
//note 判断页面是否存在
if(!in_array($h,$hlist)){
    MooMessageAdmin('您要打开的页面不存在','index.php?action=system_admingroup&h=list');
}
//note 判断是否有权限
if(!checkGroup('site',$h)){
  salert('您没有修改此操作的权限');
}

switch($h){
    //note 欢迎界面
    case 'index':
    	site_index();
   	break;
   	//note 添加首页推荐会员
    case 'add_recommend_member':
    	site_add_recommend_member();
    break;
    //note 删除首页推荐会员
    case 'del_recommend_member':
    	site_del_recommend_member();
    break;
    case 'search_index':
    	site_search_index();
    break;
    
}
?>
<?php
/*
 * Created on 2007-10-13
 *
 * 用户资料
 * 
 */
/*************************************** 逻辑层(M)/表现层(V) ****************************************/
ob_start ();

include "./module/{$name}/function.php";
/**
 * 功能列表
 */
//note 查看用户资料

function space_viewpro() {
	global $_MooClass, $dbTablePre, $userid, $timestamp, $user_arr, $val_arr, $style_user_arr, $diamond, $_MooCookie, $memcached; //$val_arr统计资料完善度
	$uid = MooGetGPC('uid','integer','G')?MooGetGPC('uid','integer','G'):$userid;
	$skinName=MooGetGPC('skiname','string','G');
	$is_only_show = true;
	$status = array ();
	//note 判断被浏览的会员是否存在
	$status = array_merge ( MooMembersData ( $uid ), MooGetData ( 'members_login', 'uid', $uid ) );

	//*****************白名单*******************
	if ($uid != $userid) {
		$is_only_show = false;
	}
	if (empty ( $is_only_show )) {
		//if(MOOPHP_ALLOW_FASTDB && $status['is_lock'] != 1){//is_lock默认1不封锁用户，0则封锁
		if ($status ['is_lock'] != 1) {
			MooMessage ( '此会员已经找到真爱，关闭了个人资料，转向我的真爱一生，寻找自己的真爱吧！', 'index.php?n=service' );
		}
		
		if (! $status) {
			MooMessage ( '此会员已经找到真爱，关闭了个人资料，转向我的真爱一生，寻找自己的真爱吧！', 'index.php?n=service' );
		
		//note 判断被浏览的用户是否允许其他会员查看他的资料
		} elseif (! $status ['showinformation'] && $status ['uid'] != $userid) { //showinformation默认1允许其他会员查看，0则不允许
			switch ($status ['showinformation_val']) { //showinformation_val用户关闭资料的理由
				case 1 :
					MooMessage ( '此会员已找到正在交往的对象，故资料未公开，转向我的真爱一生', 'index.php?n=service' );
					break;
				case 2 :
					MooMessage ( '此会员已找到真爱，即将踏上红地毯，故资料未公开，转向我的真爱一生', 'index.php?n=service' );
					break;
				case 3 :
					MooMessage ( '此会员最近很忙，无法及时回复邮件，故资料未公开，转向我的真爱一生', 'index.php?n=service' );
					break;
				case 4 :
					MooMessage ( '此会员资料未公开，原因可能是TA已经找到真爱了，转向我的真爱一生', 'index.php?n=service' );
					break;
			}
		}
	}
	
	$serverid = Moo_is_kefu (); //后台管理员
	if ($serverid) {
		$result = $_MooClass ['MooMySQL']->getOne ( "select groupid from web_admin_user where uid='{$serverid}'" );
		$groupid = $result ['groupid'];
		//系统管理员权限
		$GLOBALS ['system_admin'] = array (60 );
		if (in_array ( $groupid, $GLOBALS ['system_admin'] )) {
			$serverid = null;
		}
	}
	//note 浏览资料页面时候，写入谁浏览谁表，自己浏览自己的除外
	if (($userid && $uid) && ($userid != $uid)) {
		//note 不让屏蔽的会员查看
		if (MooGetScreen ( $userid, $uid )) {
			MooMessage ( '由于特殊原因对方资料未公开，转向我的真爱一生', 'index.php?n=service' );
		}
		
		if (empty ( $serverid )) {
			//service_visitor 某某会员浏览某某会员记录表
			$visitor = $_MooClass ['MooMySQL']->getOne ( "SELECT vid FROM {$dbTablePre}service_visitor WHERE uid = '$userid' AND visitorid = '$uid'" );
			//note 再次浏览的，更新浏览时间
			if ($visitor ['vid']) {
				$_MooClass ['MooMySQL']->query ( "UPDATE {$dbTablePre}service_visitor SET visitortime = '$timestamp' WHERE uid='$userid' AND visitorid = '$uid' limit 1" );
			} else {
				$_MooClass ['MooMySQL']->query ( "INSERT INTO {$dbTablePre}service_visitor SET uid = '$userid',visitorid='$uid',visitortime = '$timestamp'" );
			}
			//MemoryCache('visitor', $userid.'行78, space/index.php');
			
			/*
			//===simulatereal
			if(MOOPHP_ALLOW_FASTDB){
				$browsered = MooFastdbGet('members','uid',$uid);
			}else{
				$sql="SELECT isOnline FROM {$dbTablePre}members where uid='$uid'";
                $browsered=$_MooClass['MooMySQL']->getOne($sql);
			}
			*/
			
			if (MooUserIsOnline ( $uid )) {
				$browser = $_MooClass ['MooMySQL']->getOne ( "SELECT id FROM {$dbTablePre}service_browser WHERE uid = '$userid' AND browserid = '$uid'" );
				//note 再次浏览的，更新浏览时间
				if ($browser ['id']) {
					$_MooClass ['MooMySQL']->query ( "UPDATE {$dbTablePre}service_browser SET browsertime = '$timestamp' WHERE uid='$userid' AND browserid = '$uid' limit 1" );
				} else {
					$_MooClass ['MooMySQL']->query ( "INSERT INTO {$dbTablePre}service_browser SET uid = '$userid',browserid='$uid',browsertime = '$timestamp'" );
				}
			}
		
		}
	}
	
	//note 查看会员资料信息	
	$c = MooGetData ( 'members_choice', 'uid', $uid ) + MooGetData ( 'members_introduce', 'uid', $uid );
	$user = &$status;
	
	/***创建浏览队列 ***/
	if (empty ( $serverid ) && ! empty ( $userid )) {
		if ($user ['gender'] != $user_arr ['gender'] && $user ['uid'] != $userid ) {
			if ($user ['usertype'] == 3 && $user_arr ['usertype'] != 3 && $user['showinformation']==1 && $user_arr['s_cid']>=40) {
				$iscan = $GLOBALS ['fastdb']->get ( $userid . '_scan_space' ); //浏览的全权会员列表
				$iscan = empty ( $iscan ) ? array () : json_decode ( $iscan, true );
				if(!empty($iscan)) $iscan = in_array ( $uid, $iscan ) ? $iscan : array_push ( $iscan, $uid );
				$GLOBALS ['fastdb']->set ( $userid . '_scan_space', json_encode ( $iscan ) );
			}
			if ($user ['usertype'] != 3) {
				$scan_i = $GLOBALS ['fastdb']->get ( 'scan_space_' . $uid ); //访问的记录列表
				$scan_i = empty ( $scan_i ) ? array () : json_decode ( $scan_i, true );
				if (! empty ( $scan_i )) {
					$scan_s = array ();
					foreach ( $scan_i as $k => $scan ) {
						$scan_s [$k] = $scan [0];
					}
					if (in_array ( $userid, $scan_s )) {
						$scan_i [array_search ( $userid, $scan_s )] = array ($userid, time () );
					} else {
						array_push ( $scan_i, array ($userid, time () ) );
					}
				} else {
					array_push ( $scan_i, array ($userid, time () ) );
				}
				$GLOBALS ['fastdb']->set ( 'scan_space_' . $uid, json_encode ( $scan_i ) );
			}
		}
	}
	
	//note_显示相册中的普通照片
	$user_pic = $_MooClass ['MooMySQL']->getAll ( "SELECT imgurl,pic_date,pic_name FROM {$dbTablePre}pic WHERE syscheck=1 and isimage='0' and uid='$uid'" );
	
	$gender = $user_arr ['gender'] == '0' ? '1' : '0'; //0为男,1为女
	$agebegin = (date ( "Y" ) - $user_arr ['birthyear']) - 3;
	$ageend = (date ( "Y" ) - $user_arr ['birthyear']) + 3;
	$workprovince = $user_arr ['province'];
	$workcity = $user_arr ['city'];
	$search_url = MOOPHP_URL . "/index.php?n=search&h=quick&gender=" . $gender . "&age_start=" . $agebegin . "&age_end=" . $ageend . "&workprovince=" . $workprovince . "&workcity=" . $workcity . "&isphoto=1&imageField=&quick_search=搜索";
	
	//note 您可能喜欢的人，匹配相同地区
	$able_like = $userid ? youAbleLike ($l,0 ) : array ();
	
	//note 获取会员认证证件
	$usercer = certification ( $uid );
	
	//获取视频
	/* $sql = "select toshoot_video_time,toshoot_video_url from {$dbTablePre}certification where uid='{$uid}' and toshoot_video_check=2";
	$get_video = $_MooClass ['MooMySQL']->getOne ( $sql );
	$cs_path = videoPathEncrypt ( MOOPHP_URL . '/' . $get_video ['toshoot_video_url'] . '/mov_' . $uid . '.flv' ); */
	
	//获取录音
	/* $sql_voi = "select toshoot_voice_time,toshoot_voice_url from {$dbTablePre}certification where uid='{$uid}' and toshoot_voice_check=2";
	$get_voice = $_MooClass ['MooMySQL']->getOne ( $sql_voi );
	$voice_path = MOOPHP_URL . '/' . $get_voice ['toshoot_voice_url'] . '/voi_' . $uid . '.flv'; */
	
	//note 当查看其他个人主页时，当前浏览的主页条件是否匹配
	$user2 = false;
	if ($uid != "" && $uid != $user_arr ['uid'] && $user ['gender'] != $user_arr ['gender']) {
		$c2 = MooGetData ( 'members_choice', 'uid', $userid );
		$user2 = MooMembersData ( $userid );
	}
	
	$skin_style = $_MooClass['MooMySQL']->getAll("select * from {$dbTablePre}members_skin");
	
	
	$returnurl = 'index.php?' . $_SERVER ['QUERY_STRING']; //返回的url
	//note 检查绑定是否过期
	if ($user ['isbind'] == 1) {
		$user ['isbind'] = check_bind ( $user ['bind_id'] );
	}
    
	
	if ($diamond) {
		$Template=MooTemplate ( 'space_viewpro', 'data' );
	} else {
		if (! empty ($skinName)) {
			$Template= MooTemplate ( "public/space_{$skinName}", 'module' );
		} elseif ($user ['skin']) {
			$Template= MooTemplate ( "public/space_" . $user ['skin'], 'module' );
		} else {
			$Template= MooTemplate ( 'public/space_viewpro', 'module' );
		}
	}
	
	//模拟动作
	if(!empty($userid) && !empty($serverid) && $user['usertype']==1){
		$urlArr= array("ajax.php?n=service&h=sendleer","ajax.php?n=service&h=addLiker");
		$key = array_rand($urlArr);
		$url=$urlArr[$key];
	}
	
	//是否存在音乐
	if(in_array($user_arr['s_cid'],array(10,20,30))){
		$isMusic=false;
		$sql="select musicName from web_vipmusic where uid='{$uid}'";
		$music=$_MooClass['MooMySQL']->getOne($sql);
		if($music['musicName']){
			$music_url="data/music/{$uid}/{$music['musicName']}";
			if(file_exists($music_url)){
				$isMusic=true;
			}
		}
	}
	
    require $Template;
}

  

//note 会员的已认证证件
function certification($uid) {
	global $_MooClass, $dbTablePre, $userid, $timestamp, $user_arr, $diamond, $last_login_time;
	//note 查询会员诚信认证表
	

	if (MOOPHP_ALLOW_FASTDB) {
		$certificationMsg = MooFastdbGet ( 'certification', 'uid', $uid );
	} else {
		$certificationMsg = $_MooClass ['MooMySQL']->getOne ( "select * from {$dbTablePre}certification where uid='$uid'" );
	}
	return $certificationMsg;
}

//note 钻石视频
function space_video() {
	global $_MooClass, $dbTablePre, $userid, $user_arr, $style_user_arr, $diamond, $last_login_time;
	
	$uid = $GLOBALS ['style_uid'];
	
	$c = MooGetData ( 'members_choice', 'uid', $userid );
	
	$vid = MooGetGPC ( 'vid', 'integer', 'G' );
	
	$sql = "select * from {$dbTablePre}video where uid='$uid' and status='2' order by sort desc";
	$video_list = $_MooClass ['MooMySQL']->getAll ( $sql );
	
	if (! empty ( $video_list )) {
		if ($vid) {
			foreach ( $video_list as $video ) {
				if ($video ['vid'] == $vid) {
					$default_video_name = md5 ( $video ['vid'] . MOOPHP_AUTHKEY );
					$default_video_pic = MOOPHP_URL . substr ( $video ['filepath'], 2 ) . '/pic/' . $default_video_name . '_' . $video ['pic'] . '.jpg';
					$video_subject = $video ['subject'];
					$video_dateline = date ( "Y-m-d", $video ['dateline'] );
					$video_intro = $video ['intro'];
					$video_expert_comment = $video ['expert_comment'];
					break;
				}
			
			}
		} else {
			$default_video_name = md5 ( $video_list [0] ['vid'] . MOOPHP_AUTHKEY );
			$default_video_pic = MOOPHP_URL . substr ( $video_list [0] ['filepath'], 2 ) . '/pic/' . $default_video_name . '_' . $video_list [0] ['pic'] . '.jpg';
			$video_subject = $video_list [0] ['subject'];
			$video_dateline = date ( "Y-m-d", $video_list [0] ['dateline'] );
			$video_intro = $video_list [0] ['intro'];
			$video_expert_comment = $video_list [0] ['expert_comment'];
			$vid = $video_list [0] ['vid'];
		}
	
	}
	//视频path
	$path = isset ( $video_list [0] ['filepath'] ) ? videoPathEncrypt ( MOOPHP_URL . substr ( $video_list [0] ['filepath'], 2 ) . '/' . $default_video_name . '.flv' ) : '';
	
	//视频评论
	$pagesize = 5;
	$page = isset ( $_GET ['page'] ) ? max ( 1, intval ( $_GET ['page'] ) ) : 1;
	$start = ($page - 1) * $pagesize;
	$currenturl = 'http://' . $_SERVER ['SERVER_NAME'] . ':' . $_SERVER ["SERVER_PORT"] . $_SERVER ["REQUEST_URI"];
	$currenturl2 = preg_replace ( "/(&page=\d+)/", "", $currenturl );
	if (! empty ( $vid )) {
		$sql = "select count(*) c from {$dbTablePre}diamond_comment where vid='$vid'";
		$video_count = $_MooClass ['MooMySQL']->getOne ( $sql );
		$count = $video_count ['c'];
		
		if ($count > 0) {
			$sql = "select comment,uid,nickname,dateline from {$dbTablePre}diamond_comment where vid='$vid' order by id desc LIMIT $start,$pagesize";
			$video_comment = $_MooClass ['MooMySQL']->getAll ( $sql );
		}
	}
	
	//note_显示相册中的普通照片
	$user_pic = $_MooClass ['MooMySQL']->getAll ( "SELECT imgurl,pic_date,pic_name FROM {$dbTablePre}pic WHERE syscheck=1 and isimage='0' and uid='$uid'" );
	//note 您可能喜欢的人，匹配相同地区
	$able_like = youAbleLike ( $l,5 );
	//判断性别类型
	$gender_value = check_diff_gender ();
	//根据来访性虽显示固定评语
	$comment_list = comment_list ( $gender_value, $type = 1 );
	
	//音乐
	$sql = "select * from {$dbTablePre}art_music where uid='{$GLOBALS['style_uid']}'";
	$arr = $_MooClass ['MooMySQL']->getAll ( $sql );
	$mp3_list = array ();
	foreach ( $arr as $v ) {
		$mp3_list [] = array ('path' => 'data/mp3/' . $v ['path'], 'title' => $v ['title'], 'note' => $v ['note'] );
	}
	$mp3_list = json_encode ( $mp3_list );
	include MooTemplate ( 'space_video', 'data' );
}

//note 才艺展示
function space_art() {
	global $_MooClass, $dbTablePre, $userid, $user_arr, $diamond, $last_login_time;
	$sql = "select * from {$dbTablePre}art_music where uid='{$GLOBALS['style_uid']}'";
	$arr = $_MooClass ['MooMySQL']->getAll ( $sql );
	$mp3_list = array ();
	foreach ( $arr as $v ) {
		$mp3_list [] = array ('path' => 'data/mp3/' . $v ['path'], 'title' => $v ['title'], 'note' => $v ['note'] );
	}
	$mp3_list = json_encode ( $mp3_list );
	
	//note 您可能喜欢的人，匹配相同地区
	$able_like = youAbleLike ( $l,5 );
	include MooTemplate ( 'space_art', 'data' );
}

//note 亲友印象
function space_impression() {
	global $_MooClass, $dbTablePre, $userid, $user_arr, $diamond, $last_login_time;
	//note 您可能喜欢的人，匹配相同地区
	$able_like = youAbleLike ( $l,5 );
	include MooTemplate ( 'space_impression', 'data' );
}

//note 星座生肖
function space_horoscope() {
	global $_MooClass, $dbTablePre, $userid, $user_arr, $diamond, $last_login_time;
	//note 您可能喜欢的人，匹配相同地区
	$able_like = youAbleLike ( $l,5 );
	include MooTemplate ( 'space_horoscope', 'data' );
}

//note 人生经历
function space_lifetimes() {
	global $_MooClass, $dbTablePre, $userid, $user_arr, $diamond, $last_login_time;
	//判断性别类型
	$gender_value = check_diff_gender ();
	//根据来访性别显示固定评语
	$comment_list = comment_list ( $gender_value, $type = 2 );
	
	//人生经历评论
	$pagesize = 5;
	$page = max ( 1, intval ( $_GET ['page'] ) );
	$start = ($page - 1) * $pagesize;
	$currenturl = 'http://' . $_SERVER ['SERVER_NAME'] . ':' . $_SERVER ["SERVER_PORT"] . $_SERVER ["REQUEST_URI"];
	$currenturl2 = preg_replace ( "/(&page=\d+)/", "", $currenturl );
	$sql = "select count(*) c from {$dbTablePre}diamond_comment where comment_type='2' and cid='{$GLOBALS['style_uid']}'";
	$life_count = $_MooClass ['MooMySQL']->getOne ( $sql );
	$count = $life_count ['c'];
	
	if ($count > 0) {
		$sql = "select comment,uid,nickname,dateline from {$dbTablePre}diamond_comment where comment_type=2 and cid='{$GLOBALS['style_uid']}' order by id desc LIMIT $start,$pagesize";
		$life_comment = $_MooClass ['MooMySQL']->getAll ( $sql );
	}
	//note 您可能喜欢的人，匹配相同地区
	$able_like = youAbleLike ( $l,5 );
	include MooTemplate ( 'space_lifetimes', 'data' );
}

//note 约会
function space_dating() {
	global $_MooClass, $dbTablePre, $userid, $user_arr, $style_user_arr, $diamond, $last_login_time;
	//包含配置文件
	

	$did = MooGetGPC ( 'did', 'integer', 'G' );
	$uid = MooGetGPC ( 'uid', 'integer', 'G' );
	if ($_POST) {
		$param ['did'] = $did;
		$param ['uid'] = $userid;
		$param ['nickname'] = $user_arr ['nickname'];
		$param ['message'] = MooGetGPC ( 'message', 'string', 'P' );
		$param ['dateline'] = time ();
		inserttable ( 'dating_respond', $param );
		$sql = "update {$dbTablePre}dating set dating_total=dating_total+1 where did='$did'";
		$_MooClass ['MooMySQL']->query ( $sql );
		MooMessage ( '应约成功，对方将会查看您的资料，请尽快完善您的资料。', 'index.php?n=space&uid=' . $uid );
	}
	$sql = "select * from {$dbTablePre}dating where did='$did' and flag='2'";
	$dating = $_MooClass ['MooMySQL']->getOne ( $sql );
	if (! $dating) {
		MooMessage ( '您查看的用户暂时还没有发布约会。', 'index.php?n=space&uid=' . $uid );
	}
	$sql = "select count(*) as c from {$dbTablePre}dating_respond where did='$did' and uid='$userid'";
	$dating_respond = $_MooClass ['MooMySQL']->getOne ( $sql );
	$dating_respond ['count'] = $dating_respond ['c'];
	
	include_once ("./module/crontab/crontab_config.php");
	$province_key = $dating ['dating_province'];
	$city_key = $dating ['dating_city'];
	
	if (! empty ( $uid ) && ($uid != $userid)) {
		//$sql = "select * from {$dbTablePre}members where uid='$uid'";
		$members = MooMembersData ( $uid ); //= $_MooClass['MooMySQL']->getOne($sql);
	} else {
		$members = $user_arr;
		$uid = $userid;
	}
	$choice = MooGetData ( 'members_choice', 'uid', $uid );
	
	$dating_list = userDating ( $uid, 'limit 3' );
	//note 您可能喜欢的人，匹配相同地区
	$able_like = youAbleLike ( $l,5 );
	include MooTemplate ( 'space_dating', 'data' );
}

//note 播放器
function space_player() {
	include MooTemplate ( 'space_player', 'data' );
}

//个性化定制-其它未知模块
function space_other() {
	//note 您可能喜欢的人，匹配相同地区
	$able_like = youAbleLike ( $l,5 );
	include MooTemplate ( 'space_other', 'data' );
}
//匹配列表
function space_match() {
	global $_MooClass, $dbTablePre, $userid, $user_arr, $style_user_arr, $diamond, $last_login_time;
	$uid = MooGetGPC ( 'uid', 'integer', 'G' );
	//print_r($user_arr);
	//note 查看会员资料信息	
	$c = MooGetData ( 'members_choice', 'uid', $uid );
	$user = MooMembersData ( $uid );
	
	//note_显示相册中的普通照片
	$user_pic = $_MooClass ['MooMySQL']->getAll ( "SELECT imgurl,pic_date,pic_name FROM {$dbTablePre}pic WHERE syscheck=1 and isimage='0' and uid='$uid'" );
	
	$gender = $user_arr ['gender'] == '0' ? '1' : '0';
	$agebegin = (date ( "Y" ) - $user_arr ['birthyear']) - 3;
	$ageend = (date ( "Y" ) - $user_arr ['birthyear']) + 3;
	$workprovince = $user_arr ['province'];
	$workcity = $user_arr ['city'];
	$search_url = MOOPHP_URL . "/index.php?n=search&h=quick&gender=" . $gender . "&age_start=" . $agebegin . "&age_end=" . $ageend . "&workprovince=" . $workprovince . "&workcity=" . $workcity . "&isphoto=1&imageField=&quick_search=搜索";
	
	//note 匹配指数分数得出
	if ($uid != "" && $uid != $user_arr ['uid'] && $user ['gender'] != $user_arr ['gender']) {
		$mark = 0;
		$cho = MooGetData ( 'members_choice', 'uid', $user_arr ['uid'] );
		$year = isset ( $cho ['birthyear'] ) ? $cho ['birthyear'] : $user_arr ['birthyear'];
		if (($year - 5) <= $user ['birthyear'] && $user ['birthyear'] <= ($year + 5)) {
			$mark_age = 9;
			$mark += 9;
		} else {
			$mark_age = 6;
			$mark += 6;
		}
		if ($cho ['height1'] <= $user ['height'] && $user ['height'] <= ($cho ['height2'])) {
			$mark_height = 7;
			$mark += 7;
		} else {
			$mark_height = 5;
			$mark += 5;
		}
		if ($cho ['weight1'] <= $user ['weight'] && $user ['weight'] <= ($cho ['weight2'])) {
			$mark_weight = 5;
			$mark += 5;
		} else {
			$mark_weight = 3;
			$mark += 3;
		}
		if ($cho ['workprovince'] == $user ['province']) {
			$mark_workprovince = 8;
			$mark += 8;
		} else {
			$mark_workprovince = 6;
			$mark += 6;
		}
		if ($cho ['workcity'] == $user ['city']) {
			$mark_workcity = 16;
			$mark += 16;
		} else {
			$mark_workcity = 5;
			$mark += 10;
		}
		if ($cho ['education'] == $user ['education']) {
			$mark_education = 8;
			$mark += 8;
		} else {
			$mark_education = 5;
			$mark += 5;
		}
		if ($cho ['salary'] == $user ['salary']) {
			$mark_salary = 9;
			$mark += 9;
		} else {
			$mark_salary = 7;
			$mark += 7;
		}
		if ($cho ['marriage'] == $user ['marriage']) {
			$mark_marriage = 8;
			$mark += 8;
		} else {
			$mark_marriage = 5;
			$mark += 5;
		}
		if ($cho ['children'] == $user ['children']) {
			$mark_children = 8;
			$mark += 8;
		} else {
			$mark_children = 6;
			$mark += 6;
		}
		if ($cho ['drinking'] == $user ['drinking']) {
			$mark_drinking = 5;
			$mark += 5;
		} else {
			$mark_drinking = 3;
			$mark += 3;
		}
		if ($cho ['smoking'] == $user ['smoking']) {
			$mark_smoking = 5;
			$mark += 5;
		} else {
			$mark_smoking = 2;
			$mark += 2;
		}
		if ($cho ['body'] == $user ['body']) {
			$mark_body = 6;
			$mark += 12;
		} else {
			$mark_body = 4;
			$mark += 8;
		}
	}
	$able_like = youAbleLike ( $l,5 );
	require MooTemplate ( 'public/space_match', 'module' );
}

//查看绑定申请
function space_mebind() {
	global $_MooClass, $dbTablePre, $userid, $user_arr, $diamond, $last_login_time;
	$bind_id = MooGetGPC ( 'bind_id', 'integer', 'G' );
	$statue = 1;
	if ($bind_id == 0) {
		//MooMessage('赶快去找到您心仪的TA去绑定吧！');
		$statue = 0;
	} else {
		$sql = "SELECT a_uid,b_uid,length_time FROM {$dbTablePre}members_bind WHERE id=" . $bind_id;
		$m_bind = $_MooClass ['MooMySQL']->getOne ( $sql );
		
		$f_uid = $m_bind ['a_uid'] == $user_arr ['uid'] ? $m_bind ['b_uid'] : $m_bind ['a_uid'];
		$user_bind = MooMembersData ( $f_uid );
	}
	$user_s = $user_arr;
	require MooTemplate ( 'public/space_contact_binding', 'module' );
}

function sendinsitemail() {
	global $user_arr, $_MooCookie, $_MooClass;
	$suid = MooGetGPC ( 'suid', 'integer', 'G' );
	$uid = MooGetGPC ( 'uid', 'integer', 'G' );
	
	if(!$uid) return;
	
	if ($user_arr ['usertype'] != 3) { //不是全权会员
		$s_title = "真爱一生温馨提示：会员" . $user_arr ['nickname'] . "(id:" . $uid . ")关注了您！";
		
		$sql = "SELECT count(s_id) as count from web_services where s_uid='{$suid}' and s_fromid='{$uid}' and s_title='{$s_title}'";
		
		$hznResult = $_MooClass ['MooMySQL']->getOne ( $sql ); //对方如果删除过您的邮件，下次不会发送 此类邮件
		$hznCount = $hznResult ['count'];
		
		if ($hznCount < 1 || empty ( $hznResult )) {
			if ($user_arr ['s_cid'] < 40) {
				$s_cid = 1;
			} elseif ($user_arr ['s_cid'] == 40) {
				$s_cid = 2;
			}elseif($user_arr ['s_cid'] == 50){
				$s_cid = 3;
			}
			$s_content = "会员号：" . $uid . "  的会员【" . $user_arr ['nickname'] . "】关注了您，您可以通过点击下面的链接回访Ta：<br/>" . "<a href=\"index.php?n=space&uid=" . $uid . "\">我要访问TA</a>";
			$_MooClass ['MooMySQL']->query ( "insert into web_services (s_cid,s_uid,s_fromid,s_title,s_content,s_time,sid,flag) values ('{$s_cid}','{$suid}','0','{$s_title}','{$s_content}'," . time () . ",'{$user_arr['sid']}','1')" );
		
		}
	}
}
/***************************************   控制层(C)   ****************************************/

// $back_url = 'index.php?' . $_SERVER ['QUERY_STRING'];
$diamond_dir = 'data/diamond/' . substr ( $style_uid, - 1 ) . '/' . $style_uid;
// global $style_user_arr;
$h = MooGetGPC ( 'h', 'string' );
// $h_b = $h;

if (! $userid && ! $style_uid)
	MooMessage ( '页面参数错误！', 'index.php?n=service' );
// $_GET ['uid'] = isset ( $_GET ['uid'] ) ? $_GET ['uid'] : 0;

$diamond = 0;
if (isset ( $style_user_arr ['s_cid'] ) && $style_user_arr ['s_cid'] == 20) {
	$diamond_info = $_MooClass ['MooMySQL']->getOne ( "SELECT * FROM {$dbTablePre}diamond WHERE uid = {$style_user_arr['uid']}" );
	if ($diamond_info ['status'] == 3 || ($diamond_info ['status'] == 2)) {
		$diamond = 1;
		$style_name = $diamond_info ['skin'];
	} else {
		$diamond = 0;
	}
}


// if (! $userid) header ( "location:login.html");//&back_url=" . urlencode ( $back_url ) 


//钻石会员页面导航显示约会
/* if ($style_name != 'default') {
	$dating_list = userDating ( $GLOBALS ['style_uid'], 'limit 1' );
} */

//查看更多可能喜欢的人
/* $gender = $user_arr ['gender'] == '0' ? '1' : '0';
$agebegin = (date ( "Y" ) - $user_arr ['birthyear']) - 3;
$ageend = (date ( "Y" ) - $user_arr ['birthyear']) + 3;
$workprovince = $user_arr ['province'];
$workcity = $user_arr ['city'];
$search_url = MOOPHP_URL . "/index.php?n=search&h=quick&gender=" . $gender . "&age_start=" . $agebegin . "&age_end=" . $ageend . "&workprovince=" . $workprovince . "&workcity=" . $workcity . "&isphoto=1&imageField=&quick_search=搜索";
 */


// if ($h_b == 'match') $h = 'match';

// $stopflag = MooGetGPC ( 'stopflag', 'string', 'P' );

switch ($h) {
	case 'match' : //匹配列表
		space_match ();
		break;
	case 'video' : //视频
		space_video ();
		break;
	case 'art' : //才艺展示
		space_art ();
		break;
	case 'impression' : //亲友印象
		space_impression ();
		break;
	case 'horoscope' : //星座生肖
		space_horoscope ();
		break;
	case 'lifetimes' : //人生经历
		space_lifetimes ();
		break;
	case 'dating' : //约会
		space_dating ();
		break;
	case 'player' : //音乐播放器
		space_player ();
		break;
	case 'other' :
		space_other ();
		break;
	case 'comment' : //对TA评价
		space_comment ();
		break;
	case 'mebind' : //查看绑定申请
		space_mebind ();
		break;
	case 'viewpro' : //资料页
		space_viewpro ();
		break;
	case 'sendinsitemail' :
		sendinsitemail ();
		break;
	
	default :
		space_viewpro ();
		break;
}
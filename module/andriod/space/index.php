<?php
ob_start ();
include "module/andriod/function.php";

function space_viewpro() {
	global $_MooClass, $dbTablePre, $userid, $timestamp, $user_arr, $val_arr, $style_user_arr, $diamond, $_MooCookie, $memcached; //$val_arr统计资料完善度
//	$uid = $GLOBALS ['style_uid'];
    $uid = $_GET['other_uid'];
	$is_only_show = true;
	$status = array ();
	//验证是否合法成功登陆
	$and_uuid = isset($_GET['uuid'])? $_GET['uuid'] : '';
	$userid =  $_GET['uid'] = isset($_GET['uid'])?$_GET['uid']:'';
	if($userid){
		$userid = $mem_uid = $memcached->get('uid_'.$userid);
	}
	$checkuuid = check_uuid($and_uuid,$userid);
    if(!$checkuuid){
    	$error = "uuid_error";
    	echo return_data($error,false);exit;	
    }
    $user_arr = MooMembersData ( $userid );
    $user_oth = MooMembersData ( $uid );
	//note 判断被浏览的会员是否存在
	$status = array_merge ( MooMembersData ( $uid ), MooGetData ( 'members_login', 'uid', $uid ) );
    $user_per_num= getUserinfo_per($user_arr);
	
	$error = array();
	
	//*****************白名单*******************
	if ($uid != $userid) {
		$is_only_show = false;
	}
	//*****************白名单*******************
	
	if (empty ( $is_only_show )) {
		//if(MOOPHP_ALLOW_FASTDB && $status['is_lock'] != 1){//is_lock默认1不封锁用户，0则封锁
		if ($status ['is_lock'] != 1) {
			$error = "此会员已经找到真爱，关闭了个人资料";
			echo return_data($error,false);exit;
		}
		
		if (! $status) {
			$error = "此会员已经找到真爱，关闭了个人资料";
			echo return_data($error,false);exit;
		
		//note 判断被浏览的用户是否允许其他会员查看他的资料
		} elseif (! $status ['showinformation'] && $status ['uid'] != $userid) { //showinformation默认1允许其他会员查看，0则不允许
			switch ($status ['showinformation_val']) { //showinformation_val用户关闭资料的理由
				case 1 :
					$error = "此会员已找到正在交往的对象，故资料未公开";
					echo return_data($error,FALSE);exit;
					break;
				case 2 :
					$error = "此会员已找到真爱，即将踏上红地毯，故资料未公开,转向我的真爱一生";
					echo return_data($error,false);exit;
					break;
				case 3 :
					$error = "此会员最近很忙，无法及时回复邮件，故资料未公开，转向我的真爱一生";
					break;
				case 4 :
					$error = "此会员资料未公开，原因可能是TA已经找到真爱了，转向我的真爱一生";
					echo return_data($error,false);exit;
					break;
			}
		}
	}

		//note 浏览资料页面时候，写入谁浏览谁表，自己浏览自己的除外
		if (($userid && $uid) && ($userid != $uid)) {
			//note 不让屏蔽的会员查看
			if (MooGetScreen ( $userid, $uid )) {
				$error = "由于特殊原因对方资料未公开，转向我的真爱一生";
				echo return_data($error,false);exit;
			}
			
			if ($user_oth['gender'] != $user_arr['gender']) {
				//service_visitor 某某会员浏览某某会员记录表
				$visitor = $_MooClass ['MooMySQL']->getOne ( "SELECT vid FROM {$dbTablePre}service_visitor WHERE uid = '$userid' AND visitorid = '$uid'" );
				//note 再次浏览的，更新浏览时间
				if ($visitor ['vid']) {
					$_MooClass ['MooMySQL']->query ( "UPDATE {$dbTablePre}service_visitor SET visitortime = '$timestamp' WHERE uid='$userid' AND visitorid = '$uid' limit 1" );
				} else {
					$_MooClass ['MooMySQL']->query ( "INSERT INTO {$dbTablePre}service_visitor SET uid = '$userid',visitorid='$uid',visitortime = '$timestamp'" );
				}
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
		if (($userid && $uid) && ($userid != $uid)) {
			if ($user_oth['gender'] != $user_arr['gender'] && $user ['uid'] != $userid ) {
				if ($user ['usertype'] == 3 && $user_arr ['usertype'] != 3 && $user['showinformation']==1 && $user_arr['s_cid']>=40) {
					$iscan = $GLOBALS ['fastdb']->get ( $userid . '_scan_space' ); //浏览的全权会员列表
					$iscan = empty ( $iscan ) ? array () : json_decode ( $iscan, true );
					$iscan = in_array ( $uid, $iscan ) ? $iscan : array_push ( $iscan, $uid );
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
		$user['pic'] = $user_pic;
		$gender = $user_arr ['gender'] == '0' ? '1' : '0'; //0为男,1为女
		$agebegin = (date ( "Y" ) - $user_arr ['birthyear']) - 3;
		$ageend = (date ( "Y" ) - $user_arr ['birthyear']) + 3;
		$workprovince = $user_arr ['province'];
		$workcity = $user_arr ['city'];
		$search_url = MOOPHP_URL . "/index.php?n=search&h=quick&gender=" . $gender . "&age_start=" . $agebegin . "&age_end=" . $ageend . "&workprovince=" . $workprovince . "&workcity=" . $workcity . "&isphoto=1&imageField=&quick_search=搜索";
		
		//note 您可能喜欢的人，匹配相同地区
		$able_like = $userid ? youAbleLike ( 5 ) : array ();
		//note 获取会员认证证件
		//note 当查看其他个人主页时，当前浏览的主页条件是否匹配
		$user2 = false;
		if ($uid != "" && $uid != $user_arr ['uid'] && $user ['gender'] != $user_arr ['gender']) {
			$c2 = MooGetData ( 'members_choice', 'uid', $userid );
			$user2 = MooMembersData ( $userid );
		}
		//note 获得用户资料的完整度，以百分比显示makui
		$all_len = 0;
		if ($uid == $user_arr ['uid']) {
			$all_len = ( int ) (getUserinfo_per ( $user_arr ) * 100);
		}
		$user['all_len'] = $all_len;
		//note 匹配指数分数得出
		$mark = 0;
		if ($uid && $uid != $user_arr ['uid'] && $user ['gender'] != $user_arr ['gender']) {
			
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
		$returnurl = 'index.php?' . $_SERVER ['QUERY_STRING']; //返回的url
		//note 检查绑定是否过期
		if ($user ['isbind'] == 1) {
			$user ['isbind'] = check_bind ( $user ['bind_id'] );
		}
		$activity = $_MooClass ['MooMySQL']->getOne ( "SELECT uid,username,regtime,channel FROM {$dbTablePre}ahtv_reguser where  uid='{$uid}' and  isattend=1" );
		
		//鲜花发送语
		if ($user_arr ['gender'] == 0) { //boy
			$sql = "SELECT id,content  FROM {$dbTablePre}members_sendinfo where type=2 and isShow=1";
		} else { //girl
			$sql = "SELECT id,content  FROM {$dbTablePre}members_sendinfo where type=1 and isShow=1";
		}
		$sendinfo = $_MooClass ['MooMySQL']->getAll ( $sql );
	
	$users = array();
    $mainimg = MooGetphoto($status['uid'],$style = "com"); 
    $status['mainimg'] = $mainimg ;
    $users['material'] = $status; 
    $users['mating'] = $c;
    //资料完整度
    $users['inte'] = $user_per_num *100;
   	$users['inte'] = round($users['inte']);
   	$users['material']['password'] = '';
	$users['material']['regip'] = '';
	$users['material']['qq'] = '';
	$users['material']['msn'] = '';
	$users['material']['telphone'] = '';
	$users['material']['username'] = '';
	if(empty($error)){
     	echo return_data($users);exit;
	}else{	
		echo return_data($error,false);exit;	
		
	}
}


/*
 * ***********************************************控制层****************************/
$h = MooGetGPC ( 'h', 'string' );

switch ($h){
	case 'view':
		space_viewpro();
		break;
	default:
        space_viewpro();	
        break;
}
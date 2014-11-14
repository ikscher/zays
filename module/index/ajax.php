<?php
/*
 * Created on 10/14/2009 yujun module/service/ajax.php 测试推送
 * 调试窗口http://test.cy0551.com/20120801/ajax.php?n=index&h=showmsg&receiveuid=31000106
 */
/**
 * *************************************功能层*****************************
 */

// 浮动DIV显示内容——消息、被谁访问空间、在线聊天
function public_showmsg($uid) {
	header ( "Expires: Mon, 26 Jul 1970 05:00:00  GMT" );
	header ( "Cache-Control:no-cache, must-revalidate" );
	header ( "Pragma:no-cache" );
	
	$serverid = Moo_is_kefu();
	global $_MooClass, $dbTablePre, $timestamp, $memcached, $user_arr;
	// 会员ID
	// $uid = MooGetGPC('uid','integer');
	// 初始化显示数据
	$str = '';
	$n = 0;
	
	// 未读消息条数
	// $msg = $_MooClass['MooMySQL']->getOne("select count(*) from
	// {$dbTablePre}services where s_uid={$uid} and s_status='0' and flag = '1'
	// and s_uid_del='0'");
	// $msg_count = $msg['count(*)'];
	$msg_count = header_show_total ( $uid );

	// 前台显示
	
	if ($msg_count) {
		// 3个发件人的ID
		$user_msg_id = $_MooClass ['MooMySQL']->getAll ( "select distinct s_fromid from {$dbTablePre}services where s_uid={$uid} and s_status='0' and s_uid_del='0' order by s_time desc limit 3" );
		$inidArr = array ();
		foreach ( $user_msg_id as $v ) {
			if ($v ['s_fromid'] == 0) {
				$from_kefu = 1;
			} else {
				$inidArr [] = $v ['s_fromid'];
			}
		}
		$inid = implode ( ',', $inidArr );
		// 提示信息
		$user_msg = array ();
		if (MOOPHP_ALLOW_FASTDB) {
			foreach ( $inidArr as $arruid ) {
				$user_msg [] = MooFastdbGet ( 'members_search', 'uid', $arruid );
			}
		} else {
			$user_msg = $_MooClass ['MooMySQL']->getAll ( "select uid,nickname,s_cid from {$dbTablePre}members_search where uid in ($inid)" );
		}
		$str .= '<p>您有<a href="index.php?n=service&h=message">' . $msg_count . '条新消息</a></p>';
		foreach ( $user_msg as $vv ) {
			// 设置不同链接
			if ($vv) {
				// $nickname=$vv['nickname2']?$vv['nickname2']:$vv['nickname'];
				$nickname = $vv ['nickname'];
				if ($nickname) {
					
					$str .= '<p>查看<a href="index.php?n=service&h=message&t=membermessage">' . $nickname . '给您发的新邮件</a></p>';
				} else {
					$str .= '<p>查看<a href="index.php?n=service&h=message&t=membermessage">会员ID ' . $vv ['uid'] . '给您发的新邮件</a></p>';
				}
			}
		}
		if ($from_kefu) {
			$str .= '<p>查看<a href="index.php?n=service&h=message&t=hlmessage">真爱一生网发新邮件</a></p>';
		}
		$n ++;
	}
	// 提示被哪些用户访问主页
	if ($user_arr ['regdate'] < (time () - 7200)) { // 注册时间超过2个小时的会员
		if ($user_arr ['showinformation']) { // 信息公开的会员
			$visitor_msg = public_showmsg_two ( $uid );
			// 删除对应的浏览会员
			if ($uid && $visitor_msg ['uid']) {
				$sql = "DELETE FROM web_service_browser where browserid='{$uid}' and uid={$visitor_msg['uid']}";
				$_MooClass ['MooMySQL']->query ( $sql );
			}
		}
		
		// $nickname=$visitor_msg['nickname2']?$visitor_msg['nickname2']:$visitor_msg['nickname'];
		
		if (! empty ( $visitor_msg )) {
			$nickname = $visitor_msg ['nickname'];
			$nc = $nickname ? $nickname : 'ID:' . $visitor_msg ['uid'];
			
			// 伪造查看共多少秒,存memcached
			$makevisit_sec = $memcached->get ( 'makevisit' . $visitor_msg ['uid'] . '_' . $uid );
			if ($makevisit_sec) {
				$sec = $makevisit_sec + 1;
				$memcached->set ( 'makevisit' . $visitor_msg ['uid'] . '_' . $uid, $sec );
			} else {
				$sec = rand ( 30, 300 );
				$memcached->set ( 'makevisit' . $visitor_msg ['uid'] . '_' . $uid, $sec );
			}
			
			//$img = MooGetphoto ( $visitor_msg ['uid'], 'page' ); // 显示访问者的头像
			$img = MooGetphoto ( $visitor_msg ['uid'], 'mid' ); // 显示访问者的头像
			$mid_img = MooGetphoto ( $visitor_msg ['uid'], 'mid' );
			
			
			if (MOOPHP_ALLOW_FASTDB && $visitor_msg ['uid']) {
				$msg2 = MooFastdbGet ( 'members_search', 'uid', $visitor_msg ['uid'] );
				$msg_b = MooFastdbGet ( 'members_base', 'uid', $visitor_msg ['uid'] );
				$msg2 = array_merge ( $msg2, $msg_b );
			} elseif ($visitor_msg ['uid']) {
				$sql = "SELECT * FROM {$dbTablePre}members_search s left join {$dbTablePre}members_base b on s.uid=b.uid  where s.uid='{$visitor_msg['uid']}'";
				$msg2 = $_MooClass ['MooMySQL']->query ( $sql );
			}
			
			if ($sec) {
				if ($sec < 60)
					$showsec = $sec . "秒";
				if ($sec == 60)
					$showsec = "一分钟";
				if ($sec > 60) {
					$secOne = intval ( $sec / 60 );
					$secTwo = $sec - $secOne * 60;
					$mod = $sec % 60;
					if (empty ( $mod )) {
						$showsec = $secOne . '分钟';
					} else {
						$showsec = $secOne . '分钟' . $secTwo . '秒';
					}
				}
			}
			$photo = null;
			if ($msg2 ['mainimg'] && $visitor_msg ['uid']) {
				$photo = "<a target='_blank' href='index.php?n=space&h=viewpro&uid=" . $visitor_msg ['uid'] . "'><img src=" . $img . " class='u-photo'></a>";
				if ($user_arr ['s_cid'] < 40) {
					$str .= '<div class="c-line"></div>' . $photo . '<p><a target="_blank" href="index.php?n=space&h=viewpro&uid=' . $visitor_msg ['uid'] . '">' . $nc . '</a>也许适合您哦，</p><p>为了您的幸福，请把握缘分。</p><p><a target="_blank" href="index.php?n=space&h=viewpro&uid=' . $visitor_msg ['uid'] . '">关注Ta的资料</a></p><div style="clear:both"></div>';
				} else {
					$str .= '<div class="c-line"></div>' . $photo . '<p><a target="_blank" href="index.php?n=space&h=viewpro&uid=' . $visitor_msg ['uid'] . '">' . $nc . '</a>关注了您,</p><p>查看了' . $showsec . ',</p><p><a target="_blank" href="index.php?n=space&h=viewpro&uid=' . $visitor_msg ['uid'] . '">关注Ta的资料</a></p><div style="clear:both"></div>';
				}
			} elseif ($visitor_msg ['uid']) {
				if ($user_arr ['s_cid'] < 40) {
					$str .= '<p>' . $photo . '<a target="_blank" href="index.php?n=space&h=viewpro&uid=' . $visitor_msg ['uid'] . '">' . $nc . '</a>关注了您,查看了' . $showsec . ',<a target="_blank" href="index.php?n=space&h=viewpro&uid=' . $visitor_msg ['uid'] . '">查看Ta的资料</a></p>';
				} else {
					$str .= '<p>' . $photo . '<a target="_blank" href="index.php?n=space&h=viewpro&uid=' . $visitor_msg ['uid'] . '">' . $nc . '</a>也许适合您哦，为了您的幸福，请把握缘分。<a target="_blank" href="index.php?n=space&h=viewpro&uid=' . $visitor_msg ['uid'] . '">关注Ta的资料</a></p>';
				}
			}
			$n ++;
		}
	}
	// 提示当前会员有未读在线聊天消息
	$chat_msg = public_showmsg_three ( $uid );
	//print_r($chat_msg);
	$new_message = '';
	if ($chat_msg) {
		//$num = count($chat_msg);
		//$t_arr = array_keys($chat_msg);
        
		//普通会员可以接受来自高级会员的聊天信息
		$str .= '<p>您有<a style="cursor:pointer" onclick="javascript:window.open(\'index.php?n=chat&h=inline_chat&c=1&fid='.$user_arr['uid'].'&tid='.$chat_msg['fromid'].'&sid='.$serverid.'\',\''.$user_arr['uid'].'_'.$chat_msg['fromid'].'\',\'scrollbars=no,resizable=no,status=no,width=497, height=440\');setTimeout(function(){public_showmsg(1);},1000);void(0);">新的在线消息</a></p>';
		
		$new_message = '|new_message';
		$n ++;
	}
	
	// 委托真爱一生联系TA消息提醒,十五天后自动消失
	$expires_time = time () - 3600 * 24 * 15;
	$sql_contact = "SELECT count(*) as yc_count,other_contact_you,you_contact_other FROM {$dbTablePre}service_contact WHERE is_read=0 and you_contact_other = '$uid' AND syscheck = '1' AND stat = '1' AND sendtime > " . $expires_time;
	$ret = $_MooClass ['MooMySQL']->getOne ( $sql_contact ,true);
	if ($uid == $ret ['you_contact_other']) {
		$you_contact_other_msg_count = $ret ['yc_count'];
		if ($you_contact_other_msg_count > 0) {
			$str .= '<p><a style="cursor:pointer" onclick="javascript:window.open(\'index.php?n=space&h=viewpro&uid=' . $ret ['other_contact_you'] . '\', \'newwindow\', \'height=480, width=680, toolbar =no, menubar=no, scrollbars=yes, resizable=yes, location=no, status=no\'),update_read(type=2,uid=' . $uid . ')">ID为' . $ret ['other_contact_you'] . '</a>委托真爱一生联系您</p>';
			$n ++;
		}
	}
	
	// 秋波发送提醒,十五天后自动消失
	$sql_leer = "SELECT receiveuid,senduid FROM web_service_leer WHERE is_read=0 and receiveuid = '" . $uid . "' AND  receivetime > " . $expires_time;
	$ret_leer = $_MooClass ['MooMySQL']->getOne ( $sql_leer );
	
	if (!empty($ret_leer) && $uid == $ret_leer ['receiveuid']) {
		$str .= '<p><a style="cursor:pointer" onclick="javascript:window.open(\'index.php?n=space&h=viewpro&uid=' . $ret_leer ['senduid'] . '\', \'newwindow\', \'height=480, width=680, toolbar =no, menubar=no, scrollbars=yes, resizable=yes, location=no, status=no\'),update_read(type=0,uid=' . $uid . ')">ID为' . $ret_leer ['senduid'] . '</a>向您送出了一个秋波</p>';
		$n ++;
	}
	// 鲜花发送提醒
	$sql_rose = "SELECT receiveuid,senduid,num FROM web_service_rose WHERE is_read=0 and receiveuid = '" . $uid . "' AND receivetime > " . $expires_time;
	$ret_rose = $_MooClass ['MooMySQL']->getOne ( $sql_rose);
	if (!empty($ret_rose) && $uid == $ret_rose ['receiveuid']) {
		$str .= '<p><a style="cursor:pointer" onclick="javascript:window.open(\'index.php?n=space&h=viewpro&uid=' . $ret_rose ['senduid'] . '\', \'newwindow\', \'height=480, width=680, toolbar =no, menubar=no, scrollbars=yes, resizable=yes, location=no, status=no\'),update_read(type=1,uid=' . $uid . ')">ID为' . $ret_rose ['senduid'] . '</a>向您送出了鲜花</p>';
		$n ++;
	}
	
	// 意中人提醒
	$sql_liker = "SELECT * FROM web_service_friend WHERE is_read=0 and friendid = '" . $uid . "' AND sendtime > " . $expires_time;
	
	
	$ret_liker = $_MooClass ['MooMySQL']->getAll ( $sql_liker );
	$count_liker = count ( $ret_liker );
	if($count_liker>=1){
		if ($count_liker > 1 && $_MooCookie ['uid_liker'] == $ret_liker [0] ['uid']) {
			MooSetCookie ( 'uid_liker', $ret_liker [0] ['uid'], 86400 );
			shuffle ( $ret_liker );
			$rand = rand ( 1, $count_liker );
			$ret_liker ['friendid'] = $ret_liker [$rand] ['friendid'];
			$ret_liker ['uid'] = $ret_liker [$rand] ['uid'];
		
		} elseif (isset ( $ret_liker [0] )) {
			
			MooSetCookie ( 'uid_liker', $ret_liker [0] ['uid'], 86400 );
			$ret_liker ['friendid'] = $ret_liker [0] ['friendid'];
			$ret_liker ['uid'] = $ret_liker [0] ['uid'];
		
		}
		if (empty ( $ret_liker ['friendid'] ))
			$ret_liker ['friendid'] = 0;
		if ($uid == $ret_liker ['friendid']) {
			$str .= '<p><a style="cursor:pointer" onclick="javascript:window.open(\'index.php?n=space&h=viewpro&uid=' . $ret_liker ['uid'] . '\', \'newwindow\', \'height=480, width=680, toolbar =no, menubar=no, scrollbars=yes, resizable=yes, location=no, status=no\'),update_read(type=3,uid=' . $uid . ')">ID为' . $ret_liker ['uid'] . '</a>将您添加为意中人了</p>';
			$n ++;
		}
	}
	
	// 输出
	if ($str) {
		$str .= "|最新提醒($n){$new_message}";
		echo $str;
	} else {
		// $n = 0;
		$n = '';
		echo $n;
	}
}

// 更新该消息为已读
function update_read() {
	global $_MooClass;
	$message_type = $_GET ['type'];
	$uid = $_GET ['uid'];
	$array_table = array ('leer', 'rose', 'contact', 'friend' );
	$id_limit_array = array ('receiveuid', 'receiveuid', 'you_contact_other', 'friendid' );
	echo $array [$message_type];
	$sql = "update web_service_" . $array_table [$message_type] . " set is_read=1 where " . $id_limit_array [$message_type] . "=" . $uid;
	$_MooClass ['MooMySQL']->query ( $sql );
}

// 浮动DIV显示内容——提示被哪些用户访问主页
function public_showmsg_two($uid) {
	global $_MooClass, $dbTablePre, $timestamp, $memcached, $user_arr, $_MooCookie;
	$time = time ();
	$msg = array ();
	// $lastmake = $memcached->get('lastmake'.$GLOBALS['MooUid']);//如果先前伪造了用户
	
	// $visitortime = $time - 600;
	
	/*
	 * $real_offest = $_MooCookie['real_offest']; if(empty($real_offest)){
	 * $real_offest=0; }
	 */
	$scan_space = $i_scan_space = $uid_scan_lock = false;
	$uid_scan_lock = $GLOBALS ['memcached']->get ( $uid . '_scan' );
	$i_scan_space = empty ( $uid_scan_lock ) ? ($GLOBALS ['fastdb']->get ( $uid . '_scan_space' )) : false; // 我浏览的全权会员
	$i_scan_space = empty ( $i_scan_space ) ? array () : json_decode ( $i_scan_space, true );
	$i_scan_space = is_array ( $i_scan_space ) ? $i_scan_space : array ();
	if (! empty ( $i_scan_space )) {
		$scan_space = $i_scan_space;
		$scan_space_key = $uid . '_scan_space';
	} else {
		$GLOBALS ['fastdb']->set ( $uid . '_scan_space', json_encode ( $i_scan_space ) );
		$scan_space = $GLOBALS ['fastdb']->get ( 'scan_space_' . $uid ); // 浏览我的人员
		$scan_space = empty ( $scan_space ) ? array () : json_decode ( $scan_space, true );
		if (empty ( $scan_space ) || ! is_array ( $scan_space )) {
			$scan_space = array ();
			$GLOBALS ['fastdb']->set ( 'scan_space_' . $uid, json_encode ( $scan_space ) );
		}
			$scan_space_key = 'scan_space_' . $uid;
	}
	if (empty ( $scan_space )) {
		$visitor_uid = isset ( $_MooCookie ['visitor_uid'] )?$_MooCookie ['visitor_uid']:null;
		// 搜索访问您的会员
		$get_visitor_uid = empty ( $visitor_uid ) ? array () : $_MooClass ['MooMySQL']->getOne ( "select uid from {$dbTablePre}service_browser where  browserid=" . $uid . "   order by browsertime desc limit 1" );
		
		/*
		 * $real_offest++; MooSetCookie('real_offest',$real_offest,86400);
		 */
		
		// if($get_visitor_uid && $get_visitor_uid['uid'] != $visitor_uid){
		if ($get_visitor_uid && $get_visitor_uid ['uid']) {
			$other_uid = $get_visitor_uid ['uid'];
			// MooSetCookie('visitor_uid',$msg['uid'],86400);
		} else {
			// note 伪造
			// 高级会员 不伪造 （模拟）
			//if ($user_arr ['s_cid'] >= 40) {
				$other_uid = make ( $uid );
				// MooSetCookie('visitor_uid',$msg['uid'],86400);
		}
	} else {
		$other = array_shift ( $scan_space );
		$GLOBALS ['fastdb']->set ( $scan_space_key, json_encode ( $scan_space ) );
		$other_uid = is_array ( $other ) ? $other [0] : $other;
		if ($scan_space_key == $uid . '_scan_space' && ! empty ( $other_uid )) {
			$result = $_MooClass ['MooMySQL']->getOne ( "SELECT `vid` FROM `{$dbTablePre}service_visitor` WHERE uid='{$other_uid}' AND visitorid='{$uid}' AND `who_del`!=2" );
			$visitortime = (rand ( 1, 100 ) > 70) ? $timestamp : ($timestamp - rand ( 60, 120) * 60);
			if ($result ['vid']) {
				$_MooClass ['MooMySQL']->query ( "UPDATE `{$dbTablePre}service_visitor` SET `visitortime`='{$visitortime}' WHERE `vid`={$result['vid']}" );
			} else {
				$_MooClass ['MooMySQL']->query ( "INSERT INTO `{$dbTablePre}service_visitor` SET `uid`='{$other_uid}',`visitorid`='{$uid}',`visitortime`='{$visitortime}',`who_del`=1" );
			}
			$_MooClass ['MooMySQL']->query ( "UPDATE `{$dbTablePre}members_login` SET `lastvisit`=" . $visitortime . " WHERE `uid`=" . $other_uid );
			MooFastdbUpdate ( 'members_login', 'uid', $other_uid, array ('lastvisit' => $visitortime ) );
		}
	}
	if (empty ( $other_uid )) {
		return 0;
	}
	if (MOOPHP_ALLOW_FASTDB) {
		$msg = MooFastdbGet ( 'members_search', 'uid', $other_uid );
		$msg_b = MooFastdbGet ( 'members_base', 'uid', $other_uid );
		if (is_array ( $msg ) && is_array ( $msg_b ))
			$msg = array_merge ( $msg, $msg_b );
	} else {
		$sql = "SELECT * FROM {$dbTablePre}members_search as s left join {$dbTablePre}members_base as b on s.uid=b.uid where s.uid='{$other_uid}'";
		$msg = $_MooClass ['MooMySQL']->getOne ( $sql );
	}
	// }
	// }
	if (! empty ( $msg )) {
		if (! empty ( $other ) && is_array ( $other )) {
			$msg ['user_show_time'] = $other [1];
		}
		if ($scan_space_key == $uid . '_scan_space') {
			$msg ['user_show_time'] = $visitortime;
		}
		$msg ['fastdb'] = empty ( $other ) ? 0 : 1; // 访问信息来源1队列
		return $msg;
	} else {
		return 0;
	}
}
// 浮动DIV显示内容——提示当前会员有未读在线聊天消息
function public_showmsg_three($uid) {
	/* if(!in_array($uid,array('30002761','30017759'))){
		global $_MooClass, $dbTablePre;
		$msg = $_MooClass ['MooMySQL']->getOne ( "select s_fromid from {$dbTablePre}service_chat where s_uid='$uid' and s_status=0 and s_time>" . mktime ( 0, 0, 0, date ( 'm', time () ), date ( 'd', time () ), date ( 'Y', time () ) ) ,true);
		if ($msg) {
			return $msg;
		} else {
			return 0;
		}
	}else{ */
		include_once 'module/chat/ChatAction.class.php';
		$chat = new ChatAction();
		//$chat -> hasInlineChat($uid);
		$data = $chat -> hasInlineChat($uid);

		if(is_array($data) && !empty($data))
			return $data;
		else
			return 0; 
	//}
}

// note 仿造XX访问当前会员主页
function make($uid) {
	global $_MooClass, $dbTablePre, $timestamp, $memcached, $_MooCookie,$user_arr;
	$province = isset($_MooCookie ['province'])?$_MooCookie ['province']:'';
	$city = isset($_MooCookie ['city'])?$_MooCookie ['city']:'';
    // 增加一处判断 cookie 没有记录的时候直接从 user_arr 获取城市信息
    if(!empty($user_arr) && (empty ( $province ) || empty ( $city ))){
        $province=empty($province)?(empty($user_arr['province'])?(empty($user_arr['hometownprovince'])?'':$user_arr['hometownprovince']):$user_arr['province']):$province;
        $city=empty($city)?(empty($user_arr['city'])?(empty($user_arr['hometowncity'])?'':$user_arr['hometowncity']):$user_arr['city']):$city;
    }
	if (empty ( $province ) && empty ( $city ))
		return 0;//exit ();
	$time = time ();
	
	$randdo = rand ( 1, 15 );
	
	if ($randdo > 8) {
		return 0;
	}
	
	// note 当前会员工作地
	if (MOOPHP_ALLOW_FASTDB) {
		$current_user = MooFastdbGet ( 'members_search', 'uid', $uid );
	} else {
		$current_user = $_MooClass ['MooMySQL']->getOne ( "select province,city,gender,birthyear,s_cid from {$dbTablePre}members_search where uid='$uid'" );
	}
	
	// note 与当前会员在同一工作地或同一省的会员
	$fgender = $current_user ['gender'] == '0' ? '1' : '0'; // 取当前用户相反性别
	
	if ($fgender == 1) { // 2.2.1男找女：年龄小于自己的优先，向下无限制（限制在12岁），向上在5岁以内；
	                 // $age1 = $current_user['birthyear']-1;
		$age1 = $current_user ['birthyear'] - 5;
		$age2 = $current_user ['birthyear'] + 12;
	} else { // 2.2.2女找男：年龄大于自己的优先，向上20岁以内，向下3岁以内；
		$age1 = $current_user ['birthyear'] - 20;
		$age2 = $current_user ['birthyear'] + 3;
	}
	
	// note 修正广东省深圳和广州的区域查询
	if (in_array ( $province, array (10101201, 10101002 ) )) {
		$city = $province;
		$province = 10101000;
	}
	
	// 修正直辖市查询
	if (in_array ( $province, array ('10102000', '10103000', '10104000', '10105000' ) )) {
		$city = '0';
	}
	
	if ($fgender == '0' || $fgender == '1') { // girl search boy
		$sql = "SELECT count(1) AS num FROM {$dbTablePre}members_search   WHERE gender='{$fgender}' and  province='{$province}' and is_lock=1  AND birthyear>='$age1' AND birthyear<='$age2' and images_ischeck=1  " . ((! empty ( $current_user ) && $current_user ['s_cid'] < 40) ? ' and usertype=1' : '  and usertype=3'); // and
		                                                                                                                                                                                                                                                                                  // usertype=1
	
	}
	
	if (MOOPHP_ALLOW_FASTDB) {
		global $fastdb;
		$md5_sql1 = md5 ( $sql );
		$user = unserialize ( $fastdb->get ( $md5_sql1 ) );
		if (! $user || $user ['last_time'] < time ()) {
			$user = $_MooClass ['MooMySQL']->getOne ( $sql );
			$user ['last_time'] = time () + (3600 * 24 * 7);
			$fastdb->set ( $md5_sql1, serialize ( $user ) );
		}
	} else {
		$user = $_MooClass ['MooMySQL']->getOne ( $sql );
	}
	
	$rand = $user ['num'];
	$rand = mt_rand ( 0, $rand );
	
	$arrUser = array ();
	/*if ($user ['num'] > 60) { // exceed 60
		if ($fgender == '0' || $fgender == '1') { // girls find boys
			$sql = "SELECT mes1.uid as uid FROM {$dbTablePre}members_search AS mes1  left join {$dbTablePre}members_base m on mes1.uid=m.uid  WHERE mes1.gender='{$fgender}' and  mes1.province='{$province}' and mes1.is_lock=1  and mes1.birthyear>='$age1' AND mes1.birthyear<='$age2' and mes1.images_ischeck=1  and mes1.showinformation=1 " . ((! empty ( $current_user ) && $current_user ['s_cid'] < 40) ? ' and mes1.usertype=1' : '  and mes1.usertype=3') . " order by  mes1.city<>'{$city}',mes1.city desc LIMIT {$rand},1"; // and
			                                                                                                                                                                                                                                                                                                                                                                                                                                                                               // mes1.usertype=1
		}
		
		 $arrUser = $_MooClass['MooMySQL']->getOne($sql,true);
	} else { //
		if ($fgender == '0' || $fgender == '1') { // girls find boys
			$sql = "SELECT mes1.uid as uid FROM {$dbTablePre}members_search AS mes1 left join {$dbTablePre}members_base m on mes1.uid=m.uid  WHERE mes1.gender='{$fgender}' and  mes1.is_lock=1 and  mes1.birthyear>='$age1' AND mes1.birthyear<='$age2'  and mes1.images_ischeck=1  and mes1.showinformation=1 " . ((! empty ( $current_user ) && $current_user ['s_cid'] < 40) ? ' and mes1.usertype=1' : '  and mes1.usertype=3') . " order by mes1.province<>'{$province}',mes1.city<>'{$city}',mes1.province desc,mes1.city desc LIMIT {$rand},1"; // and
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              // mes1.usertype=1
		}
	
	}*/
	//echo 'aaa';exit;
	$cl = searchApi('members_man members_women');
	//print_r($cl);
	if($user['num'] > 60){
		if($fgender == '0' || $fgender == '1'){
			$cond[] = array('gender',$fgender,false);
			$cond[] = array('is_lock','1',false);
			$cond[] = array('province',$province,false);
			$cond[] = array('city',$city,false);
			$cond[] = array('birthyear',array($age1,$age2),false);
			$cond[] = array('images_ischeck','1',false);
			$cond[] = array('showinformation','1',false);
			if(!empty($current_user) && $current_user ['s_cid'] < 40){
				$cond[] = array('usertype','1',false);
			}else{
				$cond[] = array('usertype','3',false);
			}
			
			$cl->SetMatchMode ( SPH_MATCH_EXTENDED );//设置模式
			$cl->SetRankingMode ( SPH_RANK_PROXIMITY );//设置评分模式
			$cl->SetFieldWeights (array('province'=>1,'city'=>2));//设置字段的权重，如果city命中，那么权重算2
			//$cl->SetSortMode ('SPH_SORT_EXPR','@weight');//按照权重排序
			$sort='@weight';
			
			//$sort = 'city desc';
			$rs_total = $cl -> getResultOfReset($cond,array(),$sort);
			if($rs_total['total_found'] >= 1) $rand = mt_rand(1,$rs_total['total_found']);
			$limit = array($rand,1);
			$rs = $cl -> getResultOfReset($cond,$limit,$sort);
		}
	}else{
		if($fgender == '0' || $fgender == '1'){
			$cond[] = array('gender',$fgender,false);
			$cond[] = array('is_lock','1',false);
			//$cond[] = array('province',$province,false);
			$cond[] = array('birthyear',array($age1,$age2),false);
			$cond[] = array('images_ischeck','1',false);
			$cond[] = array('showinformation','1',false);
			if(!empty($current_user) && $current_user ['s_cid'] < 40){
				$cond[] = array('usertype','1',false);
			}else{
				$cond[] = array('usertype','3',false);
			}
			
			$cl->SetMatchMode ( SPH_MATCH_EXTENDED );//设置模式
			$cl->SetRankingMode ( SPH_RANK_PROXIMITY );//设置评分模式
			$cl->SetFieldWeights (array('province'=>1,'city'=>2));//设置字段的权重，如果city命中，那么权重算2
			//$cl->SetSortMode ('SPH_SORT_EXPR','@weight');//按照权重排序
			$sort='@weight';
			$limit = array($rand,1);
			//$sort = 'province desc,city desc';
			$rs_total = $cl -> getResultOfReset($cond,$limit,$sort);
			if($rs_total['total_found'] >= 1) $rand = mt_rand(1,$rs_total['total_found']);
			
			$rs = $cl -> getResultOfReset($cond,$limit,$sort);
		}
	}
	
	if(is_array($rs) && isset($rs['matches']) && !empty($rs['matches'])){
		$ids = $cl -> getIds();
		//print_r($ids);
		if(!empty($ids)) $arrUser['uid'] = $ids[0];
		//$arrUser = $_MooClass ['MooMySQL']->getOne($sql,true);
	}
	
	if ($arrUser && ! MooGetScreen ( $arrUser ['uid'], $uid )) {
		if (MOOPHP_ALLOW_FASTDB) {
			$user = MooFastdbGet ( 'members_login', 'uid', $arrUser ['uid'] );
		} else {
			$user = $_MooClass ['MooMySQL']->getOne ( 'SELECT `lastvisit` FROM `' . $dbTablePre . 'members_login` WHERE uid=' . $arrUser ['uid'] );
		}
		if ($user ['lastvisit'] < ($GLOBALS ['timestamp'] - 600)) {
			$nowtime = $GLOBALS ['timestamp'];
		} else {
			$nowtime = (rand ( 1, 100 ) > 70) ? $GLOBALS ['timestamp'] : ($GLOBALS ['timestamp'] - rand ( 60, 120 ) * 60);
		}
		$_MooClass ['MooMySQL']->query ( "UPDATE `{$dbTablePre}members_login` SET `lastvisit`=" . $nowtime . " WHERE `uid`=" . $arrUser ['uid'] );
		if (MOOPHP_ALLOW_FASTDB) {
			MooFastdbUpdate ( 'members_login', 'uid', $arrUser ['uid'], array ('lastvisit' => $nowtime ) );
		}
		$result = $_MooClass ['MooMySQL']->getOne ( "SELECT `vid` FROM `{$dbTablePre}service_visitor` WHERE uid='{$arrUser['uid']}' AND visitorid='{$uid}' AND `who_del`!=2" ,true);
		if ($result ['vid']) {
			$_MooClass ['MooMySQL']->query ( "UPDATE `{$dbTablePre}service_visitor` SET `visitortime`='{$nowtime}' WHERE `vid`={$result['vid']}" );
		} else {
			$_MooClass ['MooMySQL']->query ( "INSERT INTO `{$dbTablePre}service_visitor` SET `uid`='{$arrUser['uid']}',`visitorid`='{$GLOBALS['MooUid']}',`visitortime`='{$nowtime}',`who_del`=1" );
		}
		return $arrUser ['uid'];
	} else {
		return 0;
	}

}

//显示首页推荐会员
function recommendMember(){
    global $_MooClass, $dbTablePre, $timestamp, $memcached, $_MooCookie;
	    $gender = !MooGetGPC('gender','integer','G') ? 0 : 1;//note 性别
	
    include_once("./module/crontab/crontab_config.php");
		$cur_ip = GetIP();
		


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
	  
		
		//得到市对应的城市代号
		
		foreach($city_list as $city_key => $city_val){
		
			if(strstr($ip_arr,$city_val)){
				$city = $city_key;
				break;
			}
		}
		
		if(isset($_GET['province'])){
		
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
       
			$sql = "SELECT s.uid,s.nickname,s.gender,s.images_ischeck,s.province,s.city,s.birthyear,s.s_cid,s.city_star,b.mainimg,i.introduce FROM {$dbTablePre}members_search as s left join {$dbTablePre}members_base b on s.uid=b.uid  left join {$dbTablePre}members_introduce i on s.uid=i.uid WHERE s.images_ischeck=1 and s.s_cid='20' and s.gender='{$gender}' and ({$sql_city} AND s.is_lock=1 AND  s.nickname!='' AND i.introduce!='' AND s.showinformation=1 AND s.city_star>=1 AND s.usertype=1)  order by s.s_cid limit 6"; 
			//$userList = $_MooClass['MooMySQL']->getAll($sql);
			$param = ("type=query/name=userlist_{$city}_citystar/sql=$sql/cachetime=86400");
			
			$_MooClass['MooCache']->getBlock($param);//
			$userList = $GLOBALS['_MooBlock']["userlist_{$city}_citystar"];
			//市缓存文件
			//$city_cachefile = "userlist_{$city}_citystar".'_'.md5($param).'.data';
			
		}
		
       
		//市无城市之星，从省取城市之星
		if(empty($userList)){
			$sql="select s.uid,s.nickname,s.gender,s.images_ischeck,s.province,s.city,s.birthyear,s.s_cid,s.city_star,b.mainimg,i.introduce from  {$dbTablePre}members_search as s left join {$dbTablePre}members_base b on s.uid=b.uid left join {$dbTablePre}members_introduce as i on s.uid=i.uid WHERE s.province='$province'
			          AND s.is_lock=1   and  s.gender='{$gender}' 
			          AND s.images_ischeck = 1 AND s.showinformation=1 
			          and s.city_star>=1 and s.usertype=1 and i.introduce!='' ans s.nickname!='' LIMIT 6";
		    
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
					//$cond[] = array('@id','20752315|'.$user_in,true);//uid
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
						$sql = "select s.uid,s.nickname,s.gender,s.images_ischeck,s.province,s.city,s.birthyear,s.s_cid,s.city_star,b.mainimg,i.introduce from {$dbTablePre}members_search as s left join {$dbTablePre}members_base as b on s.uid=b.uid  left join {$dbTablePre}members_introduce as i on s.uid=i.uid where s.uid not in({$user_in})
								  AND {$sql_add} s.city_star=0 AND s.s_cid = 20  and s.gender=".$gender." 
								  AND s.images_ischeck  = 1 AND s.is_lock = 1 AND s.showinformation = 1
								  AND s.pic_num >=1 AND s.usertype=1 AND i.introduce!='' AND s.nickname!=''  LIMIT $add_query_sum";
						
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
					if (is_array($u) && is_array($u2)){
					    $u = array_merge($u,$u2);
					}
				}else{
					$sql = "SELECT s.uid,s.nickname,s.gender,s.images_ischeck,s.province,s.city,s.birthyear,s.s_cid,s.city_star,b.mainimg,i.introduce FROM {$dbTablePre}members_search s left join {$dbTablePre}members_base b on s.uid=b.uid left join {$dbTablePre}members_introduce  as i on s.uid=i.uid WHERE s.uid='{$list['uid']}'";
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
			
	
	
	//删除数组中多余的部分
	array_splice($userList, 6);
	
	$result=array();
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
	     $birthyear=date('Y')-$v['birthyear'].'岁';
	  }else{
	     $birthyear='年龄保密';
	  }
					
	  $result[]=array("uid"=>$v['uid'],"mainimg"=>$v['mainimg'],"images_ischeck"=>$v['images_ischeck'],"gender"=>$v['gender'],"introduce"=>$introduce,"imgUrl"=>$imgUrl,"nickname"=>$v['nickname'],"birthyear"=>$birthyear,"province"=>$v['province'],"city"=>$v['city']);
	
	}
	
	echo  json_encode($result);
}
	

/**
 * *************************************控制层***************************************
 */
include "./module/{$name}/function.php";

header ( "Cache-Control: no-cache, must-revalidate" ); // HTTP/1.1
header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" ); // 过去的时间
                                                  // note 初始化用户id
MooUserInfo ();
$userid = $GLOBALS ['MooUid'];

$h=MooGetGPC('h','string','G');

if($h=='recommendMember'){
   recommendMember();
   exit;
}

if(!$userid) {
   echo 'no_login';
   exit ();
} 

switch ($h) {
	// note 显示
	case "showmsg" :
		public_showmsg ( $userid );
		break;
	
	// note 仿造XX访问当前会员主页
	case "makevisitor" :
		make_visitor ();
		break;
	case "update_read" :
		update_read ();
		break;
	
	// note
	default :
		public_showmsg ( $userid );
		break;
}

?>
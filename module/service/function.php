<?php

/**
 * 显示收到的玫瑰花，秋波，真爱一生委托，别人浏览您的统计总数,消息统计还没有做？
 * @param integer $stat 1表示真爱一生委托,2表示收到邮件数,3表示收到的秋波,4表示收到的玫瑰,5表示多少会员浏览过，默认是真爱一生委托
 * @return integer $total 返回统计总数
 */
function show_total($stat = '1', $userid) {
	global $_MooClass, $dbTablePre, $user_arr, $timestamp;
	$total = '';
	// note 委托请求统计
	if ($stat == '1') {
		$query = $_MooClass ['MooMySQL']->getOne ( "select count(1) as num FROM {$dbTablePre}service_contact WHERE you_contact_other = '$userid' AND stat = '1' AND `receive_del`=0" );
		$total = $query ['num'];
	}
	// note 收到邮件数
	if ($stat == '2') {
		if($userid==0){
			return $total = 0;
		}
		$query = $_MooClass ['MooMySQL']->getOne ( "select count(1) as num from {$dbTablePre}services where s_status = '0' and s_uid = '$userid' and s_uid_del='0'" );
		$total = $query ['num'];
	}
	// note 收到的玫瑰统计
	if ($stat == '3') {
		$rose_num1 = $_MooClass ['MooMySQL']->getOne ( "SELECT sum(receivenum) total FROM {$dbTablePre}service_rose WHERE receiveuid = '$userid' AND `receive_del`=0" );
		$total = $rose_num1 ['total'];
	}
	// note 收到的秋波统计
	if ($stat == '4') {
		$query = $_MooClass ['MooMySQL']->getOne ( "select sum(case when receivenum>=1 then 1 else 0 end) as num FROM {$dbTablePre}service_leer WHERE receiveuid = '$userid' AND `receive_del`=0" );
		$total = $query ['num'];
	}
	// note 访问过我的详细页面人数统计
	if ($stat == '5') {
		$query = $_MooClass ['MooMySQL']->getOne ( "select count(1) as num FROM {$dbTablePre}service_visitor WHERE visitorid = '$userid' AND `who_del`!=2" );
		$total = $query ['num'];
	}
	// note 意中人个数统计
	if ($stat == '6') {
		$query = $_MooClass ['MooMySQL']->getOne ( "select count(1) as num FROM {$dbTablePre}service_friend WHERE uid = '$userid'" );
		$total = $query ['num'];
	}
	// 对您评价的人数
	if ($stat == '7') {
		$query = $_MooClass ['MooMySQL']->getOne ( "select count(1) as num FROM {$dbTablePre}members_comment WHERE uid = '$userid' and is_pass=1 and receive_del=0" );
		$total = $query ['num'];
	}
	// 真实身份资料请求
	if ($stat == '8') {
		$query = $_MooClass ['MooMySQL']->getOne ( "select count(1) as c FROM {$dbTablePre}members_requestsms WHERE uid = '$userid'" );
		$total = $query ['c'];
	}
	// 绑定
	if ($stat == '9') {
	}
	// 关注会员动态
	if ($stat == '10') {
		$conact_uidlist = $_MooClass ['MooMySQL']->getAll ( "select you_contact_other FROM {$dbTablePre}service_contact WHERE other_contact_you = '$userid' AND stat = '1' AND `receive_del`=0" );
		foreach ( $conact_uidlist as $v ) {
			$uid_arr [] = $v ['you_contact_other'];
		}
		if (isset ( $uid_arr ) && $uid_arr) {
			$uidlist = join ( ",", $uid_arr );
			$query = $_MooClass ['MooMySQL']->getOne ( "SELECT count(distinct uid) as c FROM {$dbTablePre}members_sns where uid in ($uidlist) and dateline>($timestamp-86400*7)" );
			$total = $query ['c'];
		} else {
			$total = 0;
		}
	}
	// 黑名单
	if ($stat == '11') {
		$query = $_MooClass ['MooMySQL']->getOne ( "select count(1) as c FROM {$dbTablePre}screen WHERE uid = '$userid'" ,true);
		$total = $query ['c'];
	}
	return $total;
}

/**
 * 显示不同的性别词汇,以后随着需要增加词汇
 * 
 * @param $gender integer
 *       	 显示性别,如男，女
 * @param $stat integer
 *       	 显示哪一种性别表示，如女，女士 ...
 * @return string 页面显示的性别
 */
function show_gender($gender, $stat = '1') {
	switch ($stat) {
		case '1' :
			$str = $gender == 0 ? '男' : '女';
			break;
		case '2' :
			$str = $gender == 0 ? '男士' : '女士';
			break;
		case '3' :
			$str = $gender == 0 ? '男性' : '女性';
			break;
		default :
			$str = $gender == 0 ? '男' : '女';
	}
	return $str;
}

/**
 * 获得附加用户表信息
 * 
 * @param $id 查询该用户的id       	
 * @return $array 该用户附件表中的信息
 */
function service_user3($id) {
	global $_MooClass, $dbTablePre, $user_arr;
	return MooMembersData ( $id );
}

/**
 * 获得来信会员昵称
 * 
 * @param $id 发送秋波用户id       	
 * @return array 该用户基本信息
 */
function leer_send_user1($id) {
	global $_MooClass, $dbTablePre, $user_arr;
	$user = array ();
	$user1 = MooMembersData ( $id );
	$user2 = MooGetData ( 'members_login', 'uid', $id );
	if ($user1 && $user2){
		$user = array_merge ( $user1, $user2 );
    }else{
        $user=$user1;
    }
	return $user;
}

/**
 * 获得来信会员昵称
 * 
 * @param $id 发送秋波用户id       	
 * @return array 该用户择偶基本信息
 */
function leer_send_user2($id) {
	global $_MooClass, $dbTablePre, $user_arr;
	$user = array ();
	$user1 = MooGetData ( 'members_choice', 'uid', $id );
	$user2 = MooGetData ( 'members_introduce', 'uid', $id );
	if ($user1 && $user2)
		$user = array_merge ( $user1, $user2 );
	return $user;
}

/**
 * 删除数据提醒页面
 *
 * @param $content string
 *       	 提示内容
 * @param $button integer
 *       	 按钮上的文本
 * @param $url integer
 *       	 点击按钮转向的URL
 */
function delmessage($content, $button, $url) {
	// 提示消息
	$prompt ['content'] = $content;
	$prompt ['button'] = $button;
	$prompt ['url'] = $url;
	require MooTemplate ( 'public/service_email_remove', 'module' );
	exit ();
}

/**
 * *委托他人使用的分页函数
 */
function multicontact($num, $perpage, $curpage, $mpurl, $ajaxdiv = '', $todiv = '') {
	global $_SCONFIG, $_SGLOBAL;
	
	if (empty ( $ajaxdiv ) && $_SGLOBAL ['inajax']) {
		$ajaxdiv = $_GET ['ajaxdiv'];
	}
	
	$page = 5;
	if ($_SGLOBAL ['showpage'])
		$page = $_SGLOBAL ['showpage'];
	
	$multipage = '';
	$mpurl .= strpos ( $mpurl, '?' ) ? '&' : '?';
	$realpages = 1;
	if ($num > $perpage) {
		$offset = 2;
		$realpages = @ceil ( $num / $perpage );
		$pages = $_SCONFIG ['maxpage'] && $_SCONFIG ['maxpage'] < $realpages ? $_SCONFIG ['maxpage'] : $realpages;
		if ($page > $pages) {
			$from = 1;
			$to = $pages;
		} else {
			$from = $curpage - $offset;
			$to = $from + $page - 1;
			if ($from < 1) {
				$to = $curpage + 1 - $from;
				$from = 1;
				if ($to - $from < $page) {
					$to = $page;
				}
			} elseif ($to > $pages) {
				$from = $pages - $page + 1;
				$to = $pages;
			}
		}
		$urlplus = $todiv ? "#$todiv" : '';
		if ($curpage - $offset > 1 && $pages > $page) {
			$multipage .= "<li><a ";
			if ($_SGLOBAL ['inajax']) {
				$multipage .= "href=\"javascript:;\" onclick=\"ajaxget('{$mpurl}page=1&ajaxdiv=$ajaxdiv', '$ajaxdiv')\"";
			} else {
				$multipage .= "href=\"{$mpurl}page=1{$urlplus}\"";
			}
			$multipage .= " ></a></li>";
		}
		$multipage .= "<li><a ";
		if ($_SGLOBAL ['inajax']) {
			$multipage .= "href=\"javascript:;\" onclick=\"ajaxget('{$mpurl}page=" . ($curpage - 1) . "&ajaxdiv=$ajaxdiv', '$ajaxdiv')\"";
		} else {
			// note 判断是否已是第一页，并做相应显示
			if ($curpage == 1) {
				$multipage .= "href=\"{$mpurl}page=1" . "$urlplus\"";
			} else {
				$multipage .= "href=\"{$mpurl}page=" . ($curpage - 1) . "$urlplus\"";
			}
			// note 判断是否已是第一页，并做相应显示
		}
		$multipage .= ">上一页</a></li>";
		$multipage .= '<li style="border:0px; padding-top:3px;">|</li>';
		$multipage .= "";
		$multipage .= "<li><a ";
		if ($_SGLOBAL ['inajax']) {
			$multipage .= "href=\"javascript:;\" onclick=\"ajaxget('{$mpurl}page=" . ($curpage + 1) . "&ajaxdiv=$ajaxdiv', '$ajaxdiv')\"";
		} else {
			// note 判断是否已是第一页，并做相应显示
			if ($curpage == $pages) {
				$multipage .= "href=\"{$mpurl}page=" . $pages . "{$urlplus}\"";
			} else {
				$multipage .= "href=\"{$mpurl}page=" . ($curpage + 1) . "{$urlplus}\"";
			}
			// note 判断是否已是第一页，并做相应显示
		}
		$multipage .= " >下一页</a></li>";
		if ($to < $pages) {
			$multipage .= "<a ";
			if ($_SGLOBAL ['inajax']) {
				$multipage .= "href=\"javascript:;\" onclick=\"ajaxget('{$mpurl}page=$pages&ajaxdiv=$ajaxdiv', '$ajaxdiv')\"";
			} else {
				$multipage .= "href=\"{$mpurl}page=$pages{$urlplus}\"";
			}
			$multipage .= " ></a>";
		}
	
	}
	return $multipage;
}

/**
 * *邮件模块使用的分页函数
 * @功能 分页函数
 * @$num 为总共的条数 比如说这个分类下共有15篇文章
 * @$perpage 为每页要显示的条数
 * @$curpage 为当前的页数
 * @$mpurl 为url的除去表示页数变量的一部分
 */
function multimail($num, $perpage, $curpage, $mpurl, $ajaxdiv = '', $todiv = '') {
	global $_SCONFIG, $_SGLOBAL;
	
	if (empty ( $ajaxdiv ) && $_SGLOBAL ['inajax']) {
		$ajaxdiv = $_GET ['ajaxdiv'];
	}
	
	$page = 5;
	if ($_SGLOBAL ['showpage'])
		$page = $_SGLOBAL ['showpage'];
	
	$multipage = '';
	$mpurl .= strpos ( $mpurl, '?' ) ? '&' : '?';
	$realpages = 1;
	if ($num > $perpage) {
		$offset = 2;
		$realpages = @ceil ( $num / $perpage );
		$pages = $_SCONFIG ['maxpage'] && $_SCONFIG ['maxpage'] < $realpages ? $_SCONFIG ['maxpage'] : $realpages;
		if ($page > $pages) {
			$from = 1;
			$to = $pages;
		} else {
			$from = $curpage - $offset;
			$to = $from + $page - 1;
			if ($from < 1) {
				$to = $curpage + 1 - $from;
				$from = 1;
				if ($to - $from < $page) {
					$to = $page;
				}
			} elseif ($to > $pages) {
				$from = $pages - $page + 1;
				$to = $pages;
			}
		}
		$urlplus = $todiv ? "#$todiv" : '';
		/*
		 * if($curpage - $offset > 1 && $pages > $page) { $multipage .= "<a ";
		 * if($_SGLOBAL['inajax']) { $multipage .= "href=\"javascript:;\"
		 * onclick=\"ajaxget('{$mpurl}page=1&ajaxdiv=$ajaxdiv', '$ajaxdiv')\"";
		 * } else { $multipage .= "href=\"{$mpurl}page=1{$urlplus}\""; }
		 * $multipage .= " ></a>"; }
		 */
		$multipage .= "<a ";
		
		if ($_SGLOBAL ['inajax']) {
			$multipage .= "href=\"javascript:;\" onclick=\"ajaxget('{$mpurl}page=" . ($curpage - 1) . "&ajaxdiv=$ajaxdiv', '$ajaxdiv')\"";
		} else {
			// note 判断是否已是第一页，并做相应显示
			if ($curpage == 1) {
				$multipage .= "href=\"{$mpurl}page=1" . "$urlplus\"";
			} else {
				$multipage .= "href=\"{$mpurl}page=" . ($curpage - 1) . "$urlplus\"";
			}
			// note 判断是否已是第一页，并做相应显示
		}
		
		$multipage .= " class='up'>上一页</a>";
		$multipage .= '';
		for($i = $from; $i <= $to; $i ++) {
			// //////////////////
			// //////////////////if($i == $curpage) {
			// $multipage .= '<strong>'.$i.'</strong>';
			// //////////////////
			// //////////////////} else {
			$multipage .= "<a ";
			if ($_SGLOBAL ['inajax']) {
				$multipage .= "href=\"javascript:;\" onclick=\"ajaxget('{$mpurl}page=$i&ajaxdiv=$ajaxdiv', '$ajaxdiv')\"";
			} else {
				$multipage .= "href=\"{$mpurl}page=$i{$urlplus}\"";
			}
			if ($i == $curpage) {
				$multipage .= " class=\"current\">$i</a>";
			} else {
				$multipage .= ">$i</a>";
			}
			// //////////////////
			// //////////////////}
		}
		$multipage .= "";
		/*
		 * if($multipage) { $multipage .= '&nbsp;&nbsp; 共'.$realpages.'页
		 * &nbsp;&nbsp;'; }
		 */
		$multipage .= "<a ";
		if ($_SGLOBAL ['inajax']) {
			$multipage .= "href=\"javascript:;\" onclick=\"ajaxget('{$mpurl}page=" . ($curpage + 1) . "&ajaxdiv=$ajaxdiv', '$ajaxdiv')\"";
		} else {
			// note 判断是否已是第一页，并做相应显示
			if ($curpage == $pages) {
				$multipage .= "href=\"{$mpurl}page=" . $pages . "{$urlplus}\"";
			} else {
				$multipage .= "href=\"{$mpurl}page=" . ($curpage + 1) . "{$urlplus}\"";
			}
			// note 判断是否已是第一页，并做相应显示
		}
		$multipage .= " class='up'>下一页</a>";
		/*
		 * if($to < $pages) { $multipage .= "<a "; if($_SGLOBAL['inajax']) {
		 * $multipage .= "href=\"javascript:;\"
		 * onclick=\"ajaxget('{$mpurl}page=$pages&ajaxdiv=$ajaxdiv',
		 * '$ajaxdiv')\""; } else { $multipage .=
		 * "href=\"{$mpurl}page=$pages{$urlplus}\""; } $multipage .= " ></a>"; }
		 */
	}
	return $multipage;
}

/**
 * 获得来信会员昵称
 * 
 * @param $id 来信会员id
 *       	 return 用户昵称
 */
function getfromname($id) {
	global $_MooClass, $dbTablePre;
	$user = MooMembersData ( $id, 'nickname' );
	return $user;
}

/*
 * 根据uid得到用户信息 parm userid
 */
function getUserInfo($userid) {
	global $_MooClass, $dbTablePre, $user_arr;
	$userInfo = MooMembersData ( $userid );
	return $userInfo;
}

// <dd>ID号：100094 手机号码：150****9139</dd>
function services_get_cache() {
	$telArr = array ();
	// 手机前3位数组
	$telHead = array ();
	// 以13开头的手机前3位
	for($ih = 130; $ih < 139; $ih ++) {
		$telHead [] = $ih;
	}
	// 以15开头的手机前3位
	for($ih = 150; $ih < 159; $ih ++) {
		// if(in_array($ih,array('185','187'))){
		// continue;
		// }
		$telHead [] = $ih;
	}
	// 以18开头的手机前3位
	// for($ih = 184;$ih < 189;$ih ++){
	// if(in_array($ih,array('185','187'))){
	// continue;
	// }
	// $telHead[] = $ih;
	// }
	
	// 随机结果
	for($i = 0; $i < 14; $i ++) {
		$rand = rand ( 0, (count ( $telHead ) - 1) );
		$telArr [] = '<dd>ID号：1***' . rand ( 100, 999 ) . '   手机号码：' . $telHead [$rand] . '****' . rand ( 1000, 9999 ) . '</dd>';
	}
	
	ob_start ();
	var_export ( $telArr );
	$out = ob_get_clean ();
	
	$cache = new MooCache ();
	$cache->writeCache ( "service_gettel", "return " . $out );
}

// 当前约会状态
function dating_status($dating) {
	$expire_time = $dating ['expire_time'];
	$flag = $dating ['flag'];
	if ($flag == 1 && $expire_time >= date ( "Y-m-d" )) {
		return '您的约会正在审核中';
	} elseif ($flag == 2 && $expire_time >= date ( "Y-m-d" )) {
		return '约会进行中...';
	} elseif ($flag == 3 && $expire_time >= date ( "Y-m-d" )) {
		return '审核不通过';
	} else {
		return '约会失败';
	}
}

/*
 * function get_pic( $userCode, $filename ){ $url =
 * "http://219.141.223.105/unismsHC/photos/"."{$userCode}"."_000.jpg"; $url2 =
 * "http://219.141.223.105/ydsmsHC/photos/"."{$userCode}"."_000.jpg"; if (
 * fopen( $url, "r" ) ) { ob_start( ); readfile( $url ); $img = ob_get_contents(
 * ); ob_end_clean( ); $size = strlen( $img ); $fp2 = @fopen( $filename, "a" );
 * fwrite( $fp2, $img ); fclose( $fp2 ); return true; } else if ( fopen( $url2,
 * "r" ) ) { ob_start( ); readfile( $url2 ); $img = ob_get_contents( );
 * ob_end_clean( ); $size = strlen( $img ); $fp2 = @fopen( $filename, "a" );
 * fwrite( $fp2, $img ); fclose( $fp2 ); return true; } else { return false; } }
 */

// 得到身份证认证图片
function get_pic2($userCode) {
	$url = "http://219.141.223.105/unismsHC/photos/" . "{$userCode}" . "_000.jpg";
	$url2 = "http://219.141.223.105/ydsmsHC/photos/" . "{$userCode}" . "_000.jpg";
	
	$exits = remote_file_exists ( $url );
	if ($exits) {
		return $url;
	} elseif (remote_file_exists ( $url2 )) {
		return $url2;
	} else {
		return '图片暂时不存在';
	}
}

// 判断远程文件是否存在
function remote_file_exists($url_file) {
	// 检测输入
	$url_file = trim ( $url_file );
	if (empty ( $url_file )) {
		return false;
	}
	$url_arr = parse_url ( $url_file );
	if (! is_array ( $url_arr ) || empty ( $url_arr )) {
		return false;
	}
	
	// 获取请求数据
	$host = $url_arr ['host'];
	$path = $url_arr ['path'] . "?" . $url_arr ['query'];
	$port = isset ( $url_arr ['port'] ) ? $url_arr ['port'] : "80";
	
	// 连接服务器
	$fp = fsockopen ( $host, $port, $err_no, $err_str, 30 );
	if (! $fp) {
		return false;
	}
	
	// 构造请求协议
	$request_str = "GET " . $path . " HTTP/1.1\r\n";
	$request_str .= "Host: " . $host . "\r\n";
	$request_str .= "Connection: Close\r\n\r\n";
	
	// 发送请求
	fwrite ( $fp, $request_str );
	$first_header = fgets ( $fp, 1024 );
	fclose ( $fp );
	
	// 判断文件是否存在
	if (trim ( $first_header ) == "") {
		return false;
	}
	if (! preg_match ( "/200/", $first_header )) {
		return false;
	}
	return true;
}

// 为鲜花，委托发送彩信
function send_mms_commission($tel, $type, $uid) {
	
	global $_MooClass, $dbTablePre, $timestamp, $user_arr;
	include ("ework/include/crontab_config.php");
	$pic_path = "./data/upload/userimg/";
	$file_path = "data/mmstmp/";
	if ($type == 'rose') {
		$mes = "真爱一生网 ID为" . $uid . "的会员给您发送了鲜花，赶快访问www.zhenaiyisheng.cc 登录您的账户了解ta的详细信息吧，同时可以搜索您的意中人，给TA送出自己的鲜花，希望您在茫茫人海中早日找寻到属于自己的幸福。";
		$title = "真爱一生网鲜花提醒";
	} elseif ($type == 'leer') {
		$mes = "真爱一生网 ID为" . $uid . "的会员给您发送了秋波，赶快访问www.zhenaiyisheng.cc 登录您的账户了解ta的详细信息吧，同时可以搜索您的意中人，给TA送出自己的秋波，希望您在茫茫人海中早日找寻到属于自己的幸福。";
		$title = "真爱一生网秋波提醒";
	} else {
		$mes = "真爱一生网 ID为" . $uid . "的会员给您发送了委托，赶快访问www.zhenaiyisheng.cc 登录您的账户了解ta的详细信息吧，同时可以搜索您的意中人，给TA送出自己的委托，希望您在茫茫人海中早日找寻到属于自己的幸福。";
		$title = "真爱一生网委托提醒";
	}
	$mes_gb = iconv ( "UTF-8", "gb2312", "$mes" );
	// $tel=$sendto_user_info['telphone'];
	$mkdirs = MooAutoLoad ( 'MooFiles' );
	$image = MooAutoLoad ( 'MooImage' );
	$sql = "SELECT pic_date,pic_name FROM {$GLOBALS['dbTablePre']}pic where uid='{$uid}' and isimage=0 and syscheck=1 limit 5";
	$user_image_arr = $_MooClass ['MooMySQL']->getAll ( $sql );
	
	$userinfo = array_merge ( MooGetData ( 'members_choice', 'uid', $uid ), MooMembersData ( $uid ) );
	$file_name_smil = 'temp' . time () . rand ( 10000, 99999 );
	$file_array = $file_path . $file_name_smil . ".smil," . $file_path . $file_name_smil . ".txt";
	$file_name = 'temp' . time () . rand ( 10000, 99999 );
	
	$nickname1 = "昵称:" . $userinfo ['nickname'];
	$nickname = iconv ( "UTF-8", "gb2312", "$nickname1" );
	$txt = " ID:" . $userinfo ['uid'] . "(" . (gmdate ( 'Y', time () ) - $userinfo ['birthyear']) . "岁)，居住在" . (($provice_list [$userinfo ['province']] || $city_list [$userinfo ['city']]) ? $provice_list [$userinfo ['province']] . $city_list [$userinfo ['city']] : "未填") . "的" . ($userinfo ['gender'] ? '女士' : '男士') . "寻找一位年龄在" . $userinfo ['age1'] . "-" . $userinfo ['age2'] . "岁，居住" . (($provice_list [$userinfo ['workprovince']] || $city_list [$userinfo ['workcity']]) ? $provice_list [$userinfo ['workprovince']] . $city_list [$userinfo ['workcity']] : "未填") . "的" . ($userinfo ['gender'] ? '男士' : '女士');
	$txt1 = iconv ( "UTF-8", "gb2312", "$txt" );
	$txt = $nickname . $txt1;
	$mkdirs->fileWrite ( $file_path . $file_name . ".txt", $txt );
	$pic_name_old = MooGetphoto ( $userinfo ['uid'], 'medium' );
	
	$pic_only_name = ($userinfo ['uid'] * 3) . "_medium.gif";
	$pic_name_file = $file_path . '/' . $pic_only_name;
	
	$pic = 'http://www.zhenaiyisheng.cc/' . $pic_name_old;
	$pic = file_get_contents ( $pic );
	file_put_contents ( $pic_name_file, $pic );
	
	$image->config ( array ('thumbDir' => $file_path . '/', 'thumbStatus' => '1', 'saveType' => '0', 'thumbName' => $pic_only_name, 'waterMarkMinWidth' => '100', 'waterMarkMinHeight' => '125', 'waterMarkStatus' => 9 ) );
	$image->thumb ( '100', '125', $pic_name_old );
	// $pic_name_file=$file_path.'/'.$pic_only_name;
	
	// $pic_name="5226417_mid.jpg";
	$par = '<par dur="50000ms"><img src="' . $pic_only_name . '" region="Image" /><text src="' . $file_name . '.txt" region="Text" /></par>';
	$file_array = $file_array . "," . $file_path . $file_name . ".txt," . $pic_name_file;
	foreach ( $user_image_arr as $key => $user_image_name ) {
		$user_image = thumbImgPath ( "2", $user_image_name ['pic_date'], $user_image_name ['pic_name'] );
		$image_name_arr = explode ( '.', $user_image_name ['pic_name'] );
		if (file_exists ( "." . $user_image ) && ($image_name_arr [1] != 'bmp')) {
			$image_only_name = $file_name . $key . ".gif";
			list ( $width, $height ) = getimagesize ( '.' . $user_image );
			$d = $width / $height;
			$c = 85 / 100;
			if ($d < $c) {
				$thumb1_width = 85;
				$b = $width / $thumb1_width;
				$thumb1_height = $height / $b;
			} else {
				$thumb1_height = 100;
				$b = $height / $thumb1_height;
				$thumb1_width = $width / $b;
			}
			
			$image->config ( array ('thumbDir' => $file_path, 'thumbStatus' => '1', 'saveType' => '0', 'thumbName' => $image_only_name, 'waterMarkMinWidth' => '82', 'waterMarkMinHeight' => '114', 'waterMarkStatus' => 9 ) );
			$image->thumb ( $thumb1_height, $thumb1_width, '.' . $user_image );
			$need_product_img [] = $file_path . $image_only_name;
			
			$file_array = $file_array . "," . $file_path . $image_only_name;
			$par = $par . '<par dur="50000ms"><img src="' . $image_only_name . '" region="Image" /></par>';
		}
	}
	$mkdirs->fileWrite ( $file_path . $file_name_smil . ".txt", $mes_gb );
	
	$smil = '<smil xmlns="http://www.w3.org/2000/SMIL20/CR/Language"><head><layout><root-layout width="208" height="176" /><region id="Image" left="0" top="0" width="128" height="128" fit="hidden" /><region id="Text" left="0" top="50" width="128" height="128" fit="hidden" /></layout></head><body><par dur="50000ms"><text src="' . $file_name_smil . '.txt" region="Text" /></par>' . $par . '</body></smil>';
	$mkdirs->fileWrite ( $file_path . $file_name_smil . ".smil", $smil );
	
	require 'ework/include/pclzip_new.lib.php';
	$archive = new PclZip ( $file_path . $file_name_smil . '.zip' );
	$v_list = $archive->create ( $file_array, PCLZIP_OPT_REMOVE_ALL_PATH );
	if ($v_list == 0) {
		die ( "Error : " . $archive->errorInfo ( true ) );
	}
	$ret = send_mms_yimei_up ( $title, $tel, $file_name_smil . '.zip' );
	
	$ret_ok = substr ( $ret, 0, 2 );
	if ($ret_ok == "OK") {
		$dateline = time ();
		$sql = "INSERT INTO {$GLOBALS['dbTablePre']}mmslog(sid,uid,title,content,sendtime,id_list) VALUES('000','{$uid}','{$title}','system','{$dateline}','{$uid}')";
		$sid = $GLOBALS ['_MooClass'] ['MooMySQL']->query ( $sql );
		$result = "OK";
	} else {
		$result = false;
	}
	$mkdirs->fileDelete ( $file_path . $file_name_smil . '.zip' );
	$mkdirs->fileDelete ( $file_path . $file_name_smil . '.txt' );
	$mkdirs->fileDelete ( $file_path . $file_name_smil . '.smil' );
	$mkdirs->fileDelete ( $file_path . $file_name . '.txt' );
	$mkdirs->fileDelete ( $pic_name_file );
	
	if ($need_product_img) {
		foreach ( $need_product_img as $del_img ) {
			$mkdirs->fileDelete ( $del_img );
		}
	}
	return $result;
}

function send_mms_yimei_up($title, $phone, $zip_name) {
	// 上海亿美彩信技术有限公司设置
	$name = 'SH-gcwl'; // 用户名
	$pass = 'admin168'; // 密码
	$zipfile = "data/mmstmp/" . $zip_name;
	
	$fp = fopen ( $zipfile, 'rb' );
	$content = fread ( $fp, filesize ( $zipfile ) );
	fclose ( $fp );
	
	function sdmms($uname, $psw, $title, $unum, $cont, $type) {
		$ws = 'http://mmsplat.eucp.b2m.cn/MMSCenterInterface/MMS.asmx?wsdl';
		$soapclient = new SoapClient ( $ws );
		// $soapclient->setOutgoingEncoding("UTF-8");
		$param ['userName'] = $uname;
		$param ['password'] = $psw;
		$param ['title'] = $title;
		$param ['userNumbers'] = $unum;
		$param ['MMSContent'] = $cont;
		$param ['sendType'] = $type;
		$return = $soapclient->__soapCall ( 'SendMMS', array ('paramters' => $param ) );
		$result = $return->SendMMSResult;
		return $result;
	}
	
	return sdmms ( $name, $pass, $title, $phone, $content, 1 );

}

function update_iswell_user($uid) {
	global $_MooClass, $dbTablePre;
	$sql = "update {$dbTablePre}members_search set is_well_user=1 where uid={$uid}";
	searchApi ( 'members_man members_women' )->updateAttr ( array ('is_well_user' ), array ($uid => array (1 ) ) );
	$_MooClass ['MooMySQL']->query ( $sql );
	if (MOOPHP_ALLOW_FASTDB) {
		$value ['is_well_user'] = 1;
		MooFastdbUpdate ( 'members_search', 'uid', $uid, $value );
	}
}

/**
 * 客服模拟全权
 */
function fulllog($uid, $serverid, $action, $send_user) {
	global $_MooClass, $dbTablePre;
	if (empty($send_user['uid'])) return;
	$t = time ();
	$sql = 'select uid,username from web_admin_user where uid =' . $serverid;
	$server = $_MooClass ['MooMySQL']->getOne ( $sql );
	$comment = "模拟全权会员对ID：" . $send_user ['uid'] . "昵称：" . $send_user ['nickname'] . ",发送" . $action . '。';
	
	$_MooClass ['MooMySQL']->query ( "INSERT INTO web_member_backinfo (mid,manager,uid,effect_grade,effect_contact,master_member,next_contact_time,interest,different,service_intro,next_contact_desc,comment,dateline,phonecall) values('{$server['uid']}','{$server['username']}','{$uid}','{9}','{0}','{0}','{0}','','','','','{$comment}','{$t}','{0}')" );
	$sql = 'select uid from web_full_log where uid =' . $uid;
	if ($_MooClass ['MooMySQL']->getOne ( $sql ,true)) {
		white_list($uid, $send_user ['uid']);
		return false;
	} else {
		$_MooClass ['MooMySQL']->query ( "INSERT INTO web_full_log (uid,action_time) values('{$uid}','{$t}')" );
		white_list($uid, $send_user ['uid']);	
	}
}

/**
 * 全权会员操作过的对象包括在首次使用一个月之内的会员也写入到白名单中
 */
function white_list($uid, $anotheruid) {
	global $_MooClass, $dbTablePre;
    $u=MooMembersData($uid);
    $another==MooMembersData($anotheruid);
    if(($u['usertype']==3 && $another['usertype']==3) || ($u['usertype']!=3 && $another['usertype']!=3)){
        return false;
    }
    if($u['usertype']==3 && $another['usertype']!=3){
        $isexit_sql="`uid` = '" . $uid . "' and `anotheruid` = '" . $anotheruid."'";
        $insert_sql="('" . $uid . "','" . $anotheruid . "')";
    }else{
        $isexit_sql="`uid` = '" . $anotheruid . "' and `anotheruid` = '" . $uid."'";
        $insert_sql="('" . $anotheruid . "','" . $uid . "')";
    }
	$isexit = $_MooClass ['MooMySQL']->getOne ( "select `id` from `web_white_list` where ".$isexit_sql ,true);
	if (!empty ( $isexit )) {
		return false;
	} else {
		$sql = "insert  INTO `web_white_list` (`uid`,`anotheruid`)  VALUES ".$insert_sql;
		$_MooClass ['MooMySQL']->query ( $sql );
		return;
	}
}

/*********************************************************************************************************************/
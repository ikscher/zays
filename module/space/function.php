<?php
//约会列表
function userDating($uid, $limit = '') {
	global $_MooClass, $dbTablePre;
	$sql = "select * from {$dbTablePre}dating where uid='$uid' and flag=2 and expire_time>='" . date ( "Y-m-d" ) . "' order by did desc $limit";
	$dating = $_MooClass ['MooMySQL']->getAll ( $sql );
	return $dating;
}

//由出生日期得到生肖
//得到生肖和星座
function get_signs($year) {
	
	$start = 1901;
	$birthyear = $year;
	$x = ($start - $birthyear) % 12;
	if ($x == 1 || $x == - 11) {
		$value = "老鼠 ";
	}
	if ($x == 0) {
		$value = "牛 ";
	}
	if ($x == 11 || $x == - 1) {
		$value = "老虎 ";
	}
	if ($x == 10 || $x == - 2) {
		$value = "兔子 ";
	}
	if ($x == 9 || $x == - 3) {
		$value = "龙 ";
	}
	if ($x == 8 || $x == - 4) {
		$value = "蛇 ";
	}
	if ($x == 7 || $x == - 5) {
		$value = "马 ";
	}
	if ($x == 6 || $x == - 6) {
		$value = "羊 ";
	}
	if ($x == 5 || $x == - 7) {
		$value = "猴子 ";
	}
	if ($x == 4 || $x == - 8) {
		$value = "鸡 ";
	}
	if ($x == 3 || $x == - 9) {
		$value = "狗 ";
	}
	if ($x == 2 || $x == - 10) {
		$value = "猪 ";
	}
	
	return $value; //返回属相
}

/*
 * 根据时间戳格式化时间
 * 语法同date(),但一天内的时间会以其它方式显示
 *
 * */
//时间格式化
function sdate($dateformat, $timestamp = 0) {
	static $h = 0;
	$result = '';
	
	if ($h == 0) {
		$h = gmdate ( "G", $GLOBALS ['timestamp'] ) * 3600;
	}
	
	if ($timestamp == 0)
		$timestamp = time ();
	$time = $GLOBALS ['timestamp'] - $timestamp;
	if ($time > $h + 48 * 3600) {
		$result = gmdate ( $dateformat, $timestamp );
	} elseif ($time > $h + 24 * 3600) {
		$result = '前天';
	} elseif ($time > $h) {
		$result = '昨天';
	} elseif ($time > 3600) {
		$result = intval ( $time / 3600 ) . '小时前';
	} elseif ($time > 60) {
		$result = intval ( $time / 60 ) . '分钟前';
	} elseif ($time > 0) {
		$result = $time . '秒前';
	} else {
		$result = '现在';
	}
	return $result;
}

/*
 * 检查自己性别与访问者性别类型：
 * 1:男对女，2:女对男,3:男对男,4:女对女
 * */
function check_diff_gender() {
	if ($GLOBALS ['user_arr'] ['gender'] == 1) { //女性
		return woman_comment ();
	} else {
		return man_comment ();
	}
}

function woman_comment() {
	$self_gender = $GLOBALS ['user_arr'] ['gender'];
	$other_gender = $GLOBALS ['style_user_arr'] ['gender'];
	if ($self_gender == $other_gender) {
		return 4; //女对女
	} elseif ($self_gender != $other_gender) {
		return 2; //女对男
	}
}

function man_comment() {
	$self_gender = $GLOBALS ['user_arr'] ['gender'];
	$other_gender = $GLOBALS ['style_user_arr'] ['gender'];
	if ($self_gender == $other_gender) {
		return 3; //男对男
	} elseif ($self_gender != $other_gender) {
		return 1; //男对女
	}
}

//与性别对应的固定评语
function comment_list($gender_value, $type = 1) {
	global $_MooClass, $dbTablePre;
	$cache_file = getCacheFile ( $gender_value . $type );
	
	if (file_exists ( $cache_file ) && (filemtime ( $cache_file ) + 3600) > time ()) {
		include $cache_file;
		return $data;
	} else {
		$sql = "SELECT id,comment FROM {$dbTablePre}manage_comment where type=1 and comment_type='$type' and (comment_sort='$gender_value' or comment_sort='0') ORDER BY id DESC";
		$comment_list = $_MooClass ['MooMySQL']->getAll ( $sql );
		foreach ( $comment_list as $key => $value ) {
			$comment_list_tmp [$value ['id']] = $value ['comment'];
		}
		setArrayCache ( $gender_value . $type, $comment_list_tmp );
		return $comment_list_tmp;
	}
}

/** 
 * 创建数组缓存
 * int setArrayCache( string $name, array $arr )
 * @param	string $name 缓存名称,尽量长点避免重名,用于读取缓存
 * @param	array  $arr 要缓存的数组
 * @return	integer 0不成功,非0成功
 * @author 	xiaowu
 * */
function setArrayCache($name, $arr) {
	$cache_dir = getCacheFile ();
	$cache_file = getCacheFile ( $name );
	MooMakeDir ( $cache_dir );
	
	$str = "<?php\n//Array Cache File, Do Not Modify Me!\n//Created: " . gmdate ( "Y-m-d H:i:s" ) . "\n
		if(!defined('IN_MOOPHP')) exit('Access Denied');\n\$data=" . var_export ( $arr, true ) . ";\n?>";
	return file_put_contents ( $cache_file, $str );
}

//得到缓存文件名
function getCacheFile($name = "") {
	$cache_dir = MOOPHP_DATA_DIR . '/cache';
	if (empty ( $name )) {
		return $cache_dir;
	}
	$cache_file = $cache_dir . '/array_gender_value_' . $name . '.php';
	return $cache_file;
}

/**
 *邮件模块使用的分页函数
 *@功能 分页函数
 *@$num 为总共的条数   比如说这个分类下共有15篇文章    
 *@$perpage 为每页要显示的条数    
 *@$curpage 为当前的页数    
 *@$mpurl 为url的除去表示页数变量的一部分 
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
		if ($curpage - $offset > 1 && $pages > $page) {
			$multipage .= "<a ";
			if ($_SGLOBAL ['inajax']) {
				$multipage .= "href=\"javascript:;\" onclick=\"ajaxget('{$mpurl}page=1&ajaxdiv=$ajaxdiv', '$ajaxdiv')\"";
			} else {
				$multipage .= "href=\"{$mpurl}page=1{$urlplus}\"";
			}
			$multipage .= " ></a>";
		}
		$multipage .= "<a ";
		if ($_SGLOBAL ['inajax']) {
			$multipage .= "href=\"javascript:;\" onclick=\"ajaxget('{$mpurl}page=" . ($curpage - 1) . "&ajaxdiv=$ajaxdiv', '$ajaxdiv')\"";
		} else {
			//note 判断是否已是第一页，并做相应显示
			if ($curpage == 1) {
				$multipage .= "href=\"{$mpurl}page=1" . "$urlplus\"";
			} else {
				$multipage .= "href=\"{$mpurl}page=" . ($curpage - 1) . "$urlplus\"";
			}
		
		//note 判断是否已是第一页，并做相应显示
		}
		$multipage .= " class='up'>上一页</a>";
		$multipage .= '';
		for($i = $from; $i <= $to; $i ++) {
			////////////////////
			////////////////////if($i == $curpage) {
			//$multipage .= '<strong>'.$i.'</strong>';
			////////////////////
			////////////////////} else {
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
		
		////////////////////
		////////////////////}
		}
		$multipage .= "";
		/*
		if($multipage) {
			$multipage .= '&nbsp;&nbsp;
				共'.$realpages.'页
				&nbsp;&nbsp;';
		}
		*/
		$multipage .= "<a ";
		if ($_SGLOBAL ['inajax']) {
			$multipage .= "href=\"javascript:;\" onclick=\"ajaxget('{$mpurl}page=" . ($curpage + 1) . "&ajaxdiv=$ajaxdiv', '$ajaxdiv')\"";
		} else {
			//note 判断是否已是第一页，并做相应显示
			if ($curpage == $pages) {
				$multipage .= "href=\"{$mpurl}page=" . $pages . "{$urlplus}\"";
			} else {
				$multipage .= "href=\"{$mpurl}page=" . ($curpage + 1) . "{$urlplus}\"";
			}
		
		//note 判断是否已是第一页，并做相应显示
		}
		$multipage .= " class='up'>下一页</a>";
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

//　简单的ubb处理
function ubbCode($message) {
	//return $message;
	$message = htmlspecialchars ( $message );
	
	$replace ['search_exp'] = array ("/\s*\[quote\][\n\r]*(.+?)[\n\r]*\[\/quote\]\s*/is", "/\[url\]\s*(http:\/\/|https?:\/\/|ftp:\/\/|gopher:\/\/|news:\/\/|telnet:\/\/|rtsp:\/\/|mms:\/\/|callto:\/\/|ed2k:\/\/){1}([^\[\"']+?)\s*\[\/url\]/i", "/\[em:(.+?):\]/i" );
	$replace ['replace_exp'] = array ("<div class=\"quote\"><span class=\"q\">\\1</span></div>", "<a href=\"\\1\\2\" target=\"_blank\">\\1\\2</a>", "<img src=\"data/face/\\1.gif\">" );
	$replace ['search_str'] = array ('[b]', '[/b]', '[i]', '[/i]', '[u]', '[/u]' );
	$replace ['replace_str'] = array ('<b>', '</b>', '<i>', '</i>', '<u>', '</u>' );
	
	@$message = str_replace ( $replace ['search_str'], $replace ['replace_str'], preg_replace ( $replace ['search_exp'], $replace ['replace_exp'], $message, 20 ) );
	//return nl2br(str_replace(array("\t", '   ', '  '), array('&nbsp; &nbsp; &nbsp; &nbsp; ', '&nbsp; &nbsp;', '&nbsp;&nbsp;'), $message));
	return nl2br ( $message );
}

//视频路径加密，先base64加密后打乱顺序
function videoPathEncrypt($videopath = '') {
	if (empty ( $videopath ))
		return;
	$str = base64_encode ( $videopath );
	$step = 4;
	$len = strlen ( $str );
	if ($len <= $step)
		return strrev ( $str );
	$n_block = $len % $step + 3; //小块的字符数
	$y = $len % $n_block; //不足一小块余下字符数
	

	$str_y = substr ( $str, $y, $y );
	
	$arr_block = str_split ( substr ( $str, 0, $y ) . substr ( $str, $y + $y ), $n_block );
	foreach ( $arr_block as &$v ) {
		$v = strrev ( $v );
	}
	$out = implode ( '', $arr_block );
	return substr ( $out, 0, $y ) . strrev ( $str_y ) . substr ( $out, $y );
}

/**
 * 检测是否在白名单中
 * 可考虑加入缓存
 * @param int $style_uid	被访问者的uid
 * @param int $uid	访问者的uid
 */
function check_white_list($style_uid, $uid = 0) {
	$white_lists = $GLOBALS ['_MooClass'] ['MooMySQL']->getAll ( 'SELECT `anotheruid` FROM `' . $GLOBALS ['dbTablePre'] . 'white_list` WHERE `uid`=' . $style_uid );
	if (empty ( $white_lists )) {
		return true;
	} else {
		$white_list = array ();
		foreach ( $white_lists as $value ) {
			$white_list [] = $value ['anotheruid'];
		}
		return empty($white_list)?true:in_array ( $uid, $white_list );
	}
}

//note 资料的完善度
$val_arr = array ('height' => - 1, 'marriage' => - 1, 'salary' => - 1, 'education' => - 1, 'children' => - 1, 'house' => - 1, 'oldsex' => - 1, 'introduce_check' => 'null', 'smoking' => - 1, 'drinking' => - 1, 'occupation' => - 1, 'vehicle' => - 1, 'corptype' => - 1, 'wantchildren' => - 1, 'fondfood' => '', 'fondplace' => '', 'fondactivity' => '', 'fondsport' => '', 'fondmusic' => '', 'fondprogram' => '' );
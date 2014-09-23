<?php
/*
	More & Original PHP Framwork
	Copyright (c) 2007 - 2008 IsMole Inc.
	常用函数
	$Id: Global.function.php 366 2008-07-11 08:23:25Z guorc $
*/


/**
 * 文本转HTML
 *
 * @param string $txt;
 * return string;
 */
function Text2Html($txt){
	$txt = str_replace("  ","　",$txt);
	$txt = str_replace("<","&lt;",$txt);
	$txt = str_replace(">","&gt;",$txt);
	$txt = preg_replace("/[\r\n]{1,}/isU","<br/>\r\n",$txt);
	return $txt;
}

/**
 * 获得IP
 * return string;
 */
function GetIP(){
	if(empty($_SERVER["HTTP_CDN_SRC_IP"])){
		if(!empty($_SERVER["HTTP_CLIENT_IP"])) { $cip = $_SERVER["HTTP_CLIENT_IP"]; }
		else if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) { $cip = $_SERVER["HTTP_X_FORWARDED_FOR"]; }
		else if(!empty($_SERVER["REMOTE_ADDR"])) { $cip = $_SERVER["REMOTE_ADDR"]; }
		else $cip = "";
	}else{
		$cip = $_SERVER["HTTP_CDN_SRC_IP"];
	}
	preg_match("/[\d\.]{7,15}/", $cip, $cips);
	$cip = $cips[0] ? $cips[0] : 'unknown';
	unset($cips);
	return $cip;
}

/**
 *@功能 分页函数
 *@$num 为总共的条数   比如说这个分类下共有15篇文章    
 *@$perpage 为每页要显示的条数    
 *@$curpage 为当前的页数    
 *@$mpurl 为url的除去表示页数变量的一部分 
 */
function multipage($num, $perpage, $curpage, $mpurl, $ajaxdiv='', $todiv='') {
	global $_SCONFIG, $_SGLOBAL;

	if(empty($ajaxdiv) && $_SGLOBAL['inajax']) {
		$ajaxdiv = $_GET['ajaxdiv'];
	}

	$page = 5;
	if($_SGLOBAL['showpage']) $page = $_SGLOBAL['showpage'];

	$multipage = '';
	$mpurl .= strpos($mpurl, '?') ? '&' : '?';
	$realpages = 1;
	if($num > $perpage) {
		$offset = 2;
		$realpages = ceil($num / $perpage);
		$pages = $_SCONFIG['maxpage'] && $_SCONFIG['maxpage'] < $realpages ? $_SCONFIG['maxpage'] : $realpages;
		if($page > $pages) {
			$from = 1;
			$to = $pages;
		} else {
			$from = $curpage - $offset;
			$to = $from + $page - 1;
			if($from < 1) {
				$to = $curpage + 1 - $from;
				$from = 1;
				if($to - $from < $page) {
					$to = $page;
				}
			} elseif($to > $pages) {
				$from = $pages - $page + 1;
				$to = $pages;
			}
		}
		$urlplus = $todiv?"#$todiv":'';
//		if($curpage - $offset > 1 && $pages > $page) {
//			$multipage .= "<a ";
//			if($_SGLOBAL['inajax']) {
//				$multipage .= "href=\"javascript:;\" onclick=\"ajaxget('{$mpurl}page=1&ajaxdiv=$ajaxdiv', '$ajaxdiv')\"";
//			} else {
//				$multipage .= "href=\"{$mpurl}page=1{$urlplus}\"";
//			}
//			$multipage .= " ></a>";
//		}
		
		if($curpage > 1) {
			$multipage .= "<a ";
			if($_SGLOBAL['inajax']) {
				$multipage .= "href=\"javascript:;\" onclick=\"ajaxget('{$mpurl}page=".($curpage-1)."&ajaxdiv=$ajaxdiv', '$ajaxdiv')\"";
			} else {
				$multipage .= "href=\"{$mpurl}page=" . ($curpage-1) ."$urlplus\"";
			}
			$multipage .= ">上一页</a>&nbsp;&nbsp;";
		}else{
			$multipage .= "<a ";
			if($_SGLOBAL['inajax']) {
				$multipage .= "href=\"javascript:;\" onclick=\"ajaxget('{$mpurl}page=".($curpage-1)."&ajaxdiv=$ajaxdiv', '$ajaxdiv')\"";
			} else {
				$multipage .= "href=\"{$mpurl}page=1"."$urlplus\"";
			}
			$multipage .= ">上一页</a>&nbsp;&nbsp;";
		}
		//$multipage .= '第&nbsp;';
		for($i = $from; $i <= $to; $i++) {
			if($i == $curpage) {
				$multipage .= '&nbsp;<strong><span class=\'pc\'>'.$i.'</span></strong>&nbsp;';
			} else {
				$multipage .= "&nbsp;<a ";
				if($_SGLOBAL['inajax']) {
					$multipage .= "href=\"javascript:;\" onclick=\"ajaxget('{$mpurl}page=$i&ajaxdiv=$ajaxdiv', '$ajaxdiv')\"";
				} else {
					$multipage .= "href=\"{$mpurl}page=$i{$urlplus}\"";
				}
				$multipage .= "><span class='pc'>$i</span></a>&nbsp;";
			}
		}
		//$multipage .= "&nbsp;页";
		
		if($curpage < $pages) {
			$multipage .= "<a ";
			if($_SGLOBAL['inajax']) {
				$multipage .= "href=\"javascript:;\" onclick=\"ajaxget('{$mpurl}page=".($curpage+1)."&ajaxdiv=$ajaxdiv', '$ajaxdiv')\"";
			} else {
				$multipage .= "href=\"{$mpurl}page=".($curpage+1)."{$urlplus}\"";
			}
			$multipage .= " >&nbsp;下一页</a>";
		}else{
			$multipage .= "<a ";
			if($_SGLOBAL['inajax']) {
				$multipage .= "href=\"javascript:;\" onclick=\"ajaxget('{$mpurl}page=".$pages."&ajaxdiv=$ajaxdiv', '$ajaxdiv')\"";
			} else {
				$multipage .= "href=\"{$mpurl}page=".$pages."{$urlplus}\"";
			}
			$multipage .= " >&nbsp;下一页</a>";
		}
		
		if($multipage) {
			$multipage .= '<a class=\'tv\'>共'.$realpages.'页</a>';
		}
//		if($to < $pages) {
//			$multipage .= "<a ";
//			if($_SGLOBAL['inajax']) {
//				$multipage .= "href=\"javascript:;\" onclick=\"ajaxget('{$mpurl}page=$pages&ajaxdiv=$ajaxdiv', '$ajaxdiv')\"";
//			} else {
//				$multipage .= "href=\"{$mpurl}page=$pages{$urlplus}\"";
//			}
//			$multipage .= " ></a>";
//		}

		
	}
	return $multipage;
}

/**
 * 显示图片列表分页
 *
 */
function multi_showphoto($num, $perpage, $curpage, $mpurl, $ajaxdiv='', $todiv='') {
	global $_SCONFIG, $_SGLOBAL;

	if(empty($ajaxdiv) && $_SGLOBAL['inajax']) {
		$ajaxdiv = $_GET['ajaxdiv'];
	}

	$page = 5;
	if($_SGLOBAL['showpage']) $page = $_SGLOBAL['showpage'];

	$multipage = '';
	$mpurl .= strpos($mpurl, '?') ? '&' : '?';
	$realpages = 1;
	if($num > $perpage) {
		$offset = 2;
		$realpages = ceil($num / $perpage);
		$pages = $_SCONFIG['maxpage'] && $_SCONFIG['maxpage'] < $realpages ? $_SCONFIG['maxpage'] : $realpages;
		if($page > $pages) {
			$from = 1;
			$to = $pages;
		} else {
			$from = $curpage - $offset;
			$to = $from + $page - 1;
			if($from < 1) {
				$to = $curpage + 1 - $from;
				$from = 1;
				if($to - $from < $page) {
					$to = $page;
				}
			} elseif($to > $pages) {
				$from = $pages - $page + 1;
				$to = $pages;
			}
		}
		$urlplus = $todiv?"#$todiv":'';
		if($curpage - $offset > 1 && $pages > $page) {
			$multipage .= "<a ";
			if($_SGLOBAL['inajax']) {
				$multipage .= "href=\"javascript:;\" onclick=\"ajaxget('{$mpurl}page=1&ajaxdiv=$ajaxdiv', '$ajaxdiv')\"";
			} else {
				$multipage .= "href=\"{$mpurl}page=1{$urlplus}\"";
			}
			$multipage .= " ></a>";
		}
		
		if($curpage > 1) {
			$multipage .= "<a ";
			if($_SGLOBAL['inajax']) {
				$multipage .= "href=\"javascript:;\" onclick=\"ajaxget('{$mpurl}page=".($curpage-1)."&ajaxdiv=$ajaxdiv', '$ajaxdiv')\"";
			} else {
				$multipage .= "href=\"{$mpurl}page=".($curpage-1)."$urlplus\"";
			}
			$multipage .= " class='uppage'></a>";
		}else{
			$multipage .= "<a ";
			if($_SGLOBAL['inajax']) {
				$multipage .= "href=\"javascript:;\" onclick=\"ajaxget('{$mpurl}page=1"."&ajaxdiv=$ajaxdiv', '$ajaxdiv')\"";
			} else {
				$multipage .= "href=\"{$mpurl}page=1"."$urlplus\"";
			}
			$multipage .= " class='uppage'></a>";
		}
		$multipage .= '';
		for($i = $from; $i <= $to; $i++) {
			if($i == $curpage) {
				$multipage .= '<span>'.$i.'</span>';
			} else {
				$multipage .= "<a ";
				if($_SGLOBAL['inajax']) {
					$multipage .= "href=\"javascript:;\" onclick=\"ajaxget('{$mpurl}page=$i&ajaxdiv=$ajaxdiv', '$ajaxdiv')\"";
				} else {
					$multipage .= "href=\"{$mpurl}page=$i{$urlplus}\"";
				}
				$multipage .= ">$i</a>";
			}
		}
		
		if($curpage < $pages) {
			$multipage .= "<a ";
			if($_SGLOBAL['inajax']) {
				$multipage .= "href=\"javascript:;\" onclick=\"ajaxget('{$mpurl}page=".($curpage+1)."&ajaxdiv=$ajaxdiv', '$ajaxdiv')\"";
			} else {
				$multipage .= "href=\"{$mpurl}page=".($curpage+1)."{$urlplus}\"";
			}
			$multipage .= " class='downpage'></a>";
		}else{
			$multipage .= "<a ";
			if($_SGLOBAL['inajax']) {
				$multipage .= "href=\"javascript:;\" onclick=\"ajaxget('{$mpurl}page=".$pages."&ajaxdiv=$ajaxdiv', '$ajaxdiv')\"";
			} else {
				$multipage .= "href=\"{$mpurl}page=".$pages."{$urlplus}\"";
			}
			$multipage .= " class='downpage'></a>";
		}
		if($to < $pages) {
			$multipage .= "<a ";
			if($_SGLOBAL['inajax']) {
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
 * 屏蔽会员列表分页
 *
 */
function multi_screen($num, $perpage, $curpage, $mpurl, $ajaxdiv='', $todiv='') {
	global $_SCONFIG, $_SGLOBAL;

	if(empty($ajaxdiv) && $_SGLOBAL['inajax']) {
		$ajaxdiv = $_GET['ajaxdiv'];
	}

	$page = 5;
	if($_SGLOBAL['showpage']) $page = $_SGLOBAL['showpage'];

	$multipage = '';
	$mpurl .= strpos($mpurl, '?') ? '&' : '?';
	$realpages = 1;
	if($num > $perpage) {
		$offset = 2;
		$realpages = ceil($num / $perpage);
		$pages = $_SCONFIG['maxpage'] && $_SCONFIG['maxpage'] < $realpages ? $_SCONFIG['maxpage'] : $realpages;
		if($page > $pages) {
			$from = 1;
			$to = $pages;
		} else {
			$from = $curpage - $offset;
			$to = $from + $page - 1;
			if($from < 1) {
				$to = $curpage + 1 - $from;
				$from = 1;
				if($to - $from < $page) {
					$to = $page;
				}
			} elseif($to > $pages) {
				$from = $pages - $page + 1;
				$to = $pages;
			}
		}
		$urlplus = $todiv?"#$todiv":'';
		if($curpage - $offset > 1 && $pages > $page) {
			$multipage .= "<a ";
			if($_SGLOBAL['inajax']) {
				$multipage .= "href=\"javascript:;\" onclick=\"ajaxget('{$mpurl}page=1&ajaxdiv=$ajaxdiv', '$ajaxdiv')\"";
			} else {
				$multipage .= "href=\"{$mpurl}page=1{$urlplus}\"";
			}
			$multipage .= " ></a>";
		}
		
		if($curpage > 1) {
			$multipage .= "<a ";
			if($_SGLOBAL['inajax']) {
				$multipage .= "href=\"javascript:;\" onclick=\"ajaxget('{$mpurl}page=".($curpage-1)."&ajaxdiv=$ajaxdiv', '$ajaxdiv')\"";
			} else {
				$multipage .= "href=\"{$mpurl}page=".($curpage-1)."$urlplus\"";
			}
			$multipage .= " class='stepup'>上一页</a>&nbsp;&nbsp;&nbsp;&nbsp;";
		}else{
			$multipage .= "<a ";
			if($_SGLOBAL['inajax']) {
				$multipage .= "href=\"javascript:;\" onclick=\"ajaxget('{$mpurl}page=1"."&ajaxdiv=$ajaxdiv', '$ajaxdiv')\"";
			} else {
				$multipage .= "href=\"{$mpurl}page=1"."$urlplus\"";
			}
			$multipage .= " class='stepup'>上一页</a>&nbsp;&nbsp;&nbsp;&nbsp;";
		}
		$multipage .= '&nbsp;';
		for($i = $from; $i <= $to; $i++) {
			if($i == $curpage) {
				$multipage .= '&nbsp;<font color="#ec008f">'.$i.'</font>&nbsp;';
			} else {
				$multipage .= "&nbsp;<a ";
				if($_SGLOBAL['inajax']) {
					$multipage .= "href=\"javascript:;\" onclick=\"ajaxget('{$mpurl}page=$i&ajaxdiv=$ajaxdiv', '$ajaxdiv')\"";
				} else {
					$multipage .= "href=\"{$mpurl}page=$i{$urlplus}\"";
				}
				$multipage .= ">$i</a>&nbsp;";
			}
		}
		$multipage .= "&nbsp;";
		/*
		if($multipage) {
			$multipage .= '&nbsp;&nbsp;
				共'.$realpages.'页
				&nbsp;&nbsp;';
		}
		*/
		if($curpage < $pages) {
			$multipage .= "<a ";
			if($_SGLOBAL['inajax']) {
				$multipage .= "href=\"javascript:;\" onclick=\"ajaxget('{$mpurl}page=".($curpage+1)."&ajaxdiv=$ajaxdiv', '$ajaxdiv')\"";
			} else {
				$multipage .= "href=\"{$mpurl}page=".($curpage+1)."{$urlplus}\"";
			}
			$multipage .= " class='netx'>下一页</a>";
		}else{
			$multipage .= "<a ";
			if($_SGLOBAL['inajax']) {
				$multipage .= "href=\"javascript:;\" onclick=\"ajaxget('{$mpurl}page=".$pages."&ajaxdiv=$ajaxdiv', '$ajaxdiv')\"";
			} else {
				$multipage .= "href=\"{$mpurl}page=".$pages."{$urlplus}\"";
			}
			$multipage .= " class='netx'>下一页</a>";
		}
		if($to < $pages) {
			$multipage .= "<a ";
			if($_SGLOBAL['inajax']) {
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
*分页函数
*
*
*/
function multi($total, $perPage, $curPage, $pageUrl, $maxPages = 0, $page = 10, $autoGoTo = TRUE, $simple = FALSE) {
	$multiPage = '';
	$pageUrl .= strpos($pageUrl, '?') ? '&amp;' : '?';
	$realPages = 1;
	if($total > $perPage) {
		$offset = 2;

		$realPages = ceil($total / $perPage);
		$pages = $maxPages && $maxPages < $realPages ? $maxPages : $realPages;

		if($page > $pages) {
			$from = 1;
			$to = $pages;
		} else {
			$from = $curPage - $offset;
			$to = $from + $page - 1;
			if($from < 1) {
				$to = $curPage + 1 - $from;
				$from = 1;
				if($to - $from < $page) {
					$to = $page;
				}
			} elseif ($to > $pages) {
				$from = $pages - $page + 1;
				$to = $pages;
			}
		}

		$multiPage = ($curPage - $offset > 1 && $pages > $page ? '<a href="'.$pageUrl.'page=1" class="first"'.$ajaxtarget.'>1 ...</a>' : '').
			($curPage > 1 && !$simple ? '<a href="'.$pageUrl.'page='.($curPage - 1).'" class="prev"'.$ajaxtarget.'>&lsaquo;&lsaquo;</a>' : '');
		for($i = $from; $i <= $to; $i++) {
			$multiPage .= $i == $curPage ? '<strong>'.$i.'</strong>' :
				'<a href="'.$pageUrl.'page='.$i.($ajaxtarget && $i == $pages && $autoGoTo ? '#' : '').'"'.$ajaxtarget.'>'.$i.'</a>';
		}

		$multiPage .= ($curPage < $pages && !$simple ? '<a href="'.$pageUrl.'page='.($curPage + 1).'" class="next"'.$ajaxtarget.'>&rsaquo;&rsaquo;</a>' : '').
			($to < $pages ? '<a href="'.$pageUrl.'page='.$pages.'" class="last"'.$ajaxtarget.'>... '.$realPages.'</a>' : '').
			(!$simple && $pages > $page && !$ajaxtarget ? '<kbd><input type="text" name="custompage" size="3" onkeydown="if(event.keyCode==13) {window.location=\''.$pageUrl.'page=\'+this.value; return false;}" /></kbd>' : '');

		$multiPage = $multiPage ? '<div class="pages">'.(!$simple ? '<em>&nbsp;'.$total.'&nbsp;</em>' : '').$multiPage.'</div>' : '';
	}
	$maxpage = $realPages;
	return $multiPage;
}

/**
 *
 * 生成GUID
 *
 * @return string 
 *
 **/
function getGUID(){
		global $_MooClass;
		$result = $_MooClass['MooMySQL']->getOne("select uuid() as GUID");
		return $result['GUID'];
}

/**
 *
 * 获得用户的GUID
 * 描述：获得第三方用户的GUID
 *
 * @param string $app_uid;
 * @param string $app_username;
 * @param integer $type;
 * @return string 
 * 
 **/
function systemGetGUID($app_uid,$app_username,$app_type=0){
		global $_MooClass;
		$user = $_MooClass['MooMySQL']->getOne("select sys_uid from system_user where app_type={$app_type} and app_uid='{$app_uid}'");
		if(!$user){
				$GUID = getGUID();
				$date = time();
				$_MooClass['MooMySQL']->query("insert into system_user (`sys_uid`,`app_type`,`app_uid`,`app_username`,`sys_date`) values ('{$GUID}',{$app_type},'{$app_uid}','{$app_username}','{$date}')");
				return $GUID;
		}else{
				return $user['sys_uid'];
		}
}




/**
 *
 * 添加数据
 * 描述：向表里插入数据
 *
 * @param string $tablename;
 * @param array() $insertsqlarr;
 * @param integer $returnid;
 * @param true/flase $replace;
 * @param integer $silent;
 * @return integer 
 * 
 **/
function inserttable($tablename, $insertsqlarr, $returnid=0, $replace = false, $silent=0) {
	global $_MooClass;
	global $dbTablePre;

	$insertkeysql = $insertvaluesql = $comma = '';
	foreach ($insertsqlarr as $insert_key => $insert_value) {
		$insertkeysql .= $comma.'`'.$insert_key.'`';
		$insertvaluesql .= $comma.'\''.$insert_value.'\'';
		$comma = ', ';
	}

	$method = $replace?'REPLACE':'INSERT';
	$_MooClass['MooMySQL']->query($method.' INTO '.$dbTablePre.$tablename.' ('.$insertkeysql.') VALUES ('.$insertvaluesql.')', $silent?'SILENT':'');
	if($returnid && !$replace) {
		return $_MooClass['MooMySQL']->insertId();
	}
}

/**
 *
 * 更新数据
 * 描述：向表里更新数据
 *
 * @param string $tablename;
 * @param array() $setsqlarr;
 * @param array() $wheresqlarr;
 * @param integer $silent;
 * @return integer 
 * 
 **/
function updatetable($tablename, $setsqlarr, $wheresqlarr, $silent=0) {
	global $_MooClass;
	global $dbTablePre;

	if(empty($setsqlarr)) return false;
	
	$setsql = $comma = '';
	foreach ($setsqlarr as $set_key => $set_value) {
		$setsql .= $comma.'`'.$set_key.'`'.'=\''.$set_value.'\'';
		$comma = ', ';
	}
	$where = $comma = '';
	if(empty($wheresqlarr)) {
		$where = '1';
	} elseif(is_array($wheresqlarr)) {
		foreach ($wheresqlarr as $key => $value) {
			$where .= $comma.'`'.$key.'`'.'=\''.$value.'\'';
			$comma = ' AND ';
		}
	} else {
		$where = $wheresqlarr;
	}
	$_MooClass['MooMySQL']->query('UPDATE '.$dbTablePre.$tablename.' SET '.$setsql.' WHERE '.$where, $silent?'SILENT':'');
}

/**
 * 拼接where的sql文
 * param array $w_param
 * return string
 **/
function buildWhereSQL($w_param = null) {
	if (empty($w_param)) {
		return null;
	}
	
	if (!is_array($w_param)) {
		return null;
	}
	
	$where_sql = "";
	
	foreach ($w_param as $key => $val) {
		$flag = substr($key, -4);
		
		$field = substr($key, 0, strlen($key) - 4);
		
		if ($flag == "_MIN") {
			$where_sql .= " and " . $field . " <= '" . $val . "'";
		} else if ($flag == "_MAX") {
			$where_sql .= " and " . $field . " >= '" . $val . "'";
		} else if ($flg == "_LIK") {
			$where_sql .= " and " . $field . " like '" . $val . "'";
		} else {
			$where_sql .= " and " . $key . " = '" . $val . "'";
		}
	}
	
	return $where_sql;
}

/**
 * 根据日期判断是否是周末
 * param $date date
 * return $str string
 */

function getDatByWeekend($date = null) {
	if ($date == null) {
		return null;
	}
	
	list($year, $month, $day) = explode("-", $date);
	$w = date("w", mktime(0, 0, 1, $month, $day, $year));
	if ($w == 0) {
		return "星期天";
	} else if ($w == 6) {
		return "星期六";
	}
	
	return null;
}

/**
 * 返回用户访问深度
 * param $st date
 * param $et date
 * param $site_id int
 * param $deep int
 * return $data array
 */
function getUserViewByDeep($st, $et, $site_id = 1, $deep = 1) {
	global $_MooClass, $dbTablePre;
	
	// 表名
	$visit_table = $dbTablePre . "visit";
	
//	$sql = "select visit_server_date, (select count(*) from (select count(visitor_idcookie) as count  from " . $visit_table . " as b where b.visit_server_date = a.visit_server_date  group by b.visitor_idcookie having count = '" . $deep . "') as c )" .
//			" from " . $visit_table . " as a where a.site_id = '" . $site_id . "' and a.visit_server_date >= '" . $st . "' and a.visit_server_date <= '" . $et . "' group by visit_server_date";
//	
//	$list = $_MooClass['MooMySQL']->getAll($sql);
	
	$between_day = getBetweenByDay($st, $et);
	
	list($year, $month, $day) = explode("-", $st);
	$data = array();
	for ($i = 0; $i <= $between_day; $i++) {
		$tmp_date = date("Y-m-d", mktime(0, 0, 0, $month, $day + $i, $year));
		$sql = "select count(*) as count from " .
				"(select count(visitor_idcookie) as count from " . $visit_table . " as b where b.visit_server_date = '" . $tmp_date . "' group by b.visitor_idcookie having count = '" . $deep . "') as a";
		$tmp_data = $_MooClass['MooMySQL']->getOne($sql);
		
		$data[$tmp_date] = $tmp_data["count"] + 0;
	}
	
	return $data;
}

/**
 * 根据年龄反回不同年龄段
 * param $age int
 * return $return int
 */
function getAge($year) {
	if (empty($year)) {
		return false;
	}
	
	$d = date("Y") - $year;
	if ($d >= 18 && $d <= 25) {
		return 1;
	} elseif ($d >= 26 && $d <= 32) {
		return 2;
	} elseif ($d >= 33 && $d <= 40) {
		return 3;
	} elseif ($d > 40) {
		return 4;
	} else {
		return 5;
	}
}

/**
 * 更新E见钟情缓存
 * param $uid int
 * param $type string
 * param $city_id int
 * param $year int  年龄段
 * param $gender int
 */
//mark 己改  by chuzx sphinx
function updateVoteFeel($uid = null, $type = "add", $province_id = null, $city_id = null, $d = 1, $gender = 1) {
	global $_MooClass, $dbTablePre,$memcached;
	// 没有参数时跳出
	if (!empty($uid)) {
		/*
		$sql = "select * from " . $dbTablePre . "members_search where uid = '" . $uid . "' and is_lock = 1 and images_ischeck = 1";
		$member = $_MooClass['MooMySQL']->getOne($sql);
		if (empty($member)) {
			return false;
		}
		*/
		$member = MooMembersData($uid);
		if($member['is_lock'] != 1 || $member['images_ischeck'] != 1)
			return FALSE;
		
		
		
		// 获取年龄段
		$d = getAge($member["birthyear"]);
		$file = $d . "_" . $member["gender"] . "_" . $member["province"] . "_" . $member["city"];
// 		if (empty($member["city"]) || $member["city"] == 1) {
// 			// $file = $d . "_" . $member["gender"] . "_" . $member["province"];
// 		}
		
// 		$cache_file = MOOPHP_DATA_DIR.'/cache/cache_' . $file . '.php';		
		
		
		$data = array();
		if (($data = $memcached->get($file))) {
			if ($type == "add") {
				// 添加
				$data[] = $uid;
				$memcached->set($file,$data,0,86400);
			} elseif ($type == "del") {
				if (in_array($uid, $data)) {
					$key = array_search($uid, $data);
					if ($key === false) {
						
					} else {
						unset($data[$key]);
					}
					
					$memcached->set($file,$data,0,86400);
				} else {
					return false;
				}
			}
		} else {
			
			$sp = searchApi("members_man members_women");
			
    		$limit = array("offset"=>0,"limit"=>100);
    		$sort = '@random';
    		$filters = array(
        		array("is_lock",1),
				array("images_ischeck",1),
				array("usertype",1)
        	);
   
			// 不存在时，从数据库中读
			if (empty($member["city"])) {
				$filters[] = array("province",$member["province"]);
			} else {
				$filters[] = array("city",$member["city"]);
			}
			
			$sp->getResultOfReset($filters, $limit, $sort );
			$data = $sp->getIds();
			
			$memcached->set($file,$data,0,86400);
		}
	} else{
		// 分为不同年龄段与性别		
		
		//分男女
		if($gender==1){
			// 年龄段分析
			switch ($d){
				case 1:$start_year = date("Y") - 25;$end_year = date("Y") - 18;break;
				case 2:$start_year = date("Y") - 32;$end_year = date("Y") - 26;break;
				case 3:$start_year = date("Y") - 40;$end_year = date("Y") - 33;break;
				case 4:$start_year = date("Y") - 50;$end_year = date("Y") - 40;break;
				case 5:$start_year = date("Y") - 60;$end_year = date("Y") - 50;break;
				case 6:$start_year = 1900;$end_year = date("Y") - 60;          break;
				default:$start_year = date("Y") - 25;$end_year = date("Y") - 18;break;
			}
			$index = 'members_women';
		}else{
			// 年龄段分析
			switch ($d){
				case 1:$start_year = date("Y") - 35;$end_year = date("Y") - 20; break;
				case 2:$start_year = date("Y") - 40;$end_year = date("Y") - 33;break;
				case 3:$start_year = date("Y") - 45;$end_year = date("Y") - 38;break;
				case 4:$start_year = date("Y")-55;$end_year = date("Y") - 45;break;
				case 5:$start_year = date("Y")-65;$end_year = date("Y") - 55;break;
				case 6:$start_year = 1900;$end_year = date("Y") - 55;break;
				default:$start_year = date("Y") - 25;$end_year = date("Y") - 18;break;
			}
            $index = 'members_man';
		}
		//在sphinx中搜索
		$sp = searchApi($index);
		//满足省市
		$sp->getResultOfReset(
				array(
					array("is_lock",1),
					array("images_ischeck",1),
					array("province",$province_id),
					array("city",$city_id),
					array("usertype",1),
					array("birthyear", array($start_year,$end_year))
				),
				array("offset"=>0,"limit"=>100)
			
				);
		$member_list = $sp->getIds();
		//print_r($member_list);
		
		//满足省
		$other_member_list = array();
		if (count($member_list) < 100) {
			$sp->getResultOfReset(
				array(
					array("is_lock",1),
					array("images_ischeck",1),
					array("province",$province_id),
					array("city",$city_id,EXCLUDE_TRUE),
					array("usertype",1),
					array("birthyear",array($start_year,$end_year))
				),
				array("offset"=>0,"limit"=>100-count($member_list))
	
				);			
			
			$other_member_list = $sp->getIds();
			// 防止foreach时出错
			if (count($other_member_list) == 0) {
				$other_member_list = array();
			}
		}
		
		$data = array();
       $data = array_merge($member_list,$other_member_list);		
		
       //满足省市之外的条件
		$all_member_list = array();
		if (count($data) < 100) {
			$sp->getResultOfReset(
				array(
					array("is_lock",1),
					array("images_ischeck",1),
					array("province",$province_id,EXCLUDE_TRUE),
					array("city",$city_id,EXCLUDE_TRUE),
					array("usertype",1),
					array("birthyear",array($start_year,$end_year))
				),
				array("offset"=>0,"limit"=>100-count($data))
				);		
				//select uid from web_members_search where is_lock=1 and images_ischeck=1 and usertype=1 and birthyear >=1986 and birthyear <1998
			
			$all_member_list = $sp->getIds();
			
			if (count($all_member_list) == 0) {
				$all_member_list = array();
			}
		}
		
		$data = array_merge($data,$all_member_list);	
		$file = $d . "_" . $gender . "_" . $province_id . "_" . $city_id;
		
		$memcached->set($file,$data,0,86400);
	}
}


function runPHP($url){
	//数据准备
	$port = 80;
	$urls = parse_url($url);
	$host = $urls['host'];
	if( !empty($urls["port"]) ) $port = $urls['port'];
	$path = $urls['path'];
	if( !empty($urls["query"]) ) $path .= '?'.$urls['query'];
	
	//开始访问
	$fp = fsockopen( $host, $port, $errno, $errstr, 30);
	if ($fp) {
		$out = "GET $path HTTP/1.1\r\n";
		$out .= "Host: $host\r\n";
		$out .= "Connection: Close\r\n\r\n";
		$write_n = fwrite($fp, $out);
		fclose($fp);
	}
	return '';
}

//>>>ts24:伪造送鲜花或秋波 2009-2-4
function fakeSend( $user, $last_alive ){
	global $_MooClass,$dbTablePre,$timestamp;
		
	$min_len = 2*24*3600; //基准间隔时间3天
	$base_max_len = 5*24*3600;
	
	//第一次登陆不发
	if( $user['last_login_time'] <= 0 ){
		return 0;
	}
	//检查收到鲜花的最迟时间
	$sql = "SELECT MAX(receivetime) starttime FROM `{$dbTablePre}service_rose` WHERE `receiveuid` = '{$user['uid']}' LIMIT 1 ";
	$temp = $_MooClass['MooMySQL']->getOne( $sql );
	$start_rose = $temp ? $temp['starttime'] : 0;
	
	if( $start_rose > 0 && $timestamp - $start_rose < $min_len ){ //收到鲜花不超过3天,不发
		return 0;
	}
	
	//检查收到秋波的最迟时间
	$sql = "SELECT MAX(receivetime) startime FROM `{$dbTablePre}service_leer` WHERE `receiveuid` = '{$user['uid']}' LIMIT 1 ";
	$temp = $_MooClass['MooMySQL']->getOne( $sql );
	$start_leer = $temp ? $temp['starttime'] : 0;
	
	if( $start_leer > 0 && $timestamp - $start_leer < $min_len ){ //收到秋波不超过3天,不发
		return 0;
	}
	
	//已通过时间检查准备好时间数据
	$start = max( $start_leer, $start_rose );
	if( $start < $last_alive ) $start = $last_alive;
	$max_len = ( ( $timestamp - $start ) / 18 );
	if( $max_len < $base_max_len ) $max_len = $base_max_len;
	
	//读取送花人列表
	$person_list = personList( $user['uid'], 1-$user['gender'] ); //k => array( 0/1, uid, time ) {0:我访问的人,1:来访问的人}
	if( empty($person_list) ){
		return 0;
	}
	
	//发秋波或鲜花
	$person_num = count( $person_list );
	$start += rand( 0, $min_len );
	$rose_list = $leer_list = $visit_list = array();
	$fake_n = 0;
	do{
		$p_n = rand( 0, $person_num-1 ); //第几人
		if( rand( 0, 1 ) != 0 ){//鲜花
			$temp_list = &$rose_list;
		}else{//秋波
			$temp_list = &$leer_list;
		}
		
		$temp_list[$person_list[$p_n][1]] = $start;
		$visit_list[$person_list[$p_n][1]] = $start;
		
		++ $fake_n;
		$start += rand( $min_len, $max_len );
	}while( $start < $timestamp );

	if( !empty($visit_list) ) fakeVisitOne( $user['uid'], $visit_list );
	if( !empty($rose_list) ) fakeSendRoseToOne( $user['uid'], $rose_list );
	if( !empty($leer_list) ) fakeSendLeerToOne( $user['uid'], $leer_list );
	
	return $fake_n;
}

//>>>ts24:根据uid读取指定性别的来访者和我访问过的人列表
function personList( $uid, $gender ){
	global $_MooClass,$dbTablePre,$timestamp;
	//取uid列表和时间
	$sql = "SELECT * 
		FROM {$dbTablePre}service_visitor 
		WHERE uid = '$uid' OR visitorid = '$uid'
		ORDER BY visitortime DESC LIMIT 100";
	$temp = $_MooClass['MooMySQL']->getAll( $sql );
	if( empty($temp) ) return array();
	
	$temp1 = array();
	foreach( $temp as $v ){
		if( $v['uid'] == $uid ){
			$temp1[$v['visitorid']] = array( 0, $v['visitorid'], $v['visitortime'] );
		}else{
			$temp1[$v['uid']] = array( 1, $v['uid'], $v['visitortime'] );
		}
	}
	unset( $temp );
	
	//筛选性别
	$ids = array_keys( $temp1 ); 
	$sql = "SELECT uid FROM {$dbTablePre}members_search WHERE uid IN(".implode(',',$ids).") AND gender = '$gender' ORDER BY usertype";
	$user_list = $_MooClass['MooMySQL']->getAll( $sql );
	if( empty($user_list) ) return array();
	
	$out_list = array(); 
	foreach( $user_list as $v ){
		$out_list[] = $temp1[$v['uid']];
	}
	return $out_list;	//k => array( 0/1, uid, time ) {0:我访问的人,1:来访问的人}
}

//>>>ts24:伪造访问某用户
/*
 * $uid 被访问者uid
 * $list 来访者uid,和 时间列表   uid => time
 * */
function fakeVisitOne( $uid, $list ){
	global $_MooClass,$dbTablePre,$timestamp;
	//历史来访者信息
	$sql = "SELECT `uid`, `vid` FROM `".$dbTablePre."service_visitor` WHERE `visitorid`='$uid' AND `who_del`!=2"; 
	$temp = $_MooClass['MooMySQL']->getAll( $sql );
	$visitor = array();
	foreach( $temp as $v ){
		$visitor[$v['uid']] = $v['vid'];
	}
	//伪造数据
	foreach( $list as $v_uid => $v_time ){
		if( isset($visitor[$v_uid]) ){
			$sql = "UPDATE `".$dbTablePre."service_visitor` SET `visitortime`='$v_time' WHERE `vid`='{$visitor[$v_uid]}'";
		}else{
			$sql = "INSERT INTO `".$dbTablePre."service_visitor` SET `uid`='$v_uid',`visitorid`='$uid',`visitortime`='$v_time',`who_del`=1";
		}
		//MemoryCache('visitor', $sql . '行1033 global.function.php');
		$_MooClass['MooMySQL']->query( $sql );
	}
}

//>>>ts24:伪造送鲜花给某人
function fakeSendRoseToOne( $uid, $list ){
	global $_MooClass,$dbTablePre,$timestamp;
	$ids = "'".implode( "','", array_keys($list) )."'";
	$sql = "SELECT rid,senduid FROM {$dbTablePre}service_rose WHERE receiveuid = '$uid' AND senduid IN ( $ids ) ";
	$rows = $_MooClass['MooMySQL']->getAll( $sql );
	//以前送过,修改
	$update_list = array();
	foreach( $rows as $v ){
		$sql = "UPDATE {$dbTablePre}service_rose SET fakenum = fakenum + 1,receivenum = receivenum + 1,receivetime = '{$list[$v['senduid']]}',receive_del=0 WHERE rid = '{$v['rid']}'";
		$rows = $_MooClass['MooMySQL']->query( $sql );
		$update_list[$v['senduid']] = 1;
	}
	//以前未送过,插入
	$list = array_diff_key( $list, $update_list );
	foreach( $list as $senduid => $time ){
		$sql = "INSERT INTO {$dbTablePre}service_rose SET fakenum = '1',receivenum = 1,sendtime='{$timestamp}', receivetime = '{$time}',senduid  = '{$senduid}',receiveuid = '{$uid}', send_del = '1' ";
		$rows = $_MooClass['MooMySQL']->query( $sql );
	}
}

//>>>ts24:伪造发秋波
function fakeSendLeerToOne( $uid, $list ){
	global $_MooClass,$dbTablePre,$timestamp;
	$ids = "'".implode( "','", array_keys($list) )."'";
	$sql = "SELECT lid,senduid FROM {$dbTablePre}service_leer WHERE receiveuid = '$uid' AND senduid IN ( $ids ) ";
	$rows = $_MooClass['MooMySQL']->getAll( $sql );
	//以前送过,修改
	$update_list = array();
	foreach( $rows as $v ){
		$sql = "UPDATE {$dbTablePre}service_leer SET fakenum = fakenum + 1,receivenum = receivenum + 1,receivetime = '{$list[$v['senduid']]}',receive_del=0 WHERE lid = '{$v['rid']}'";
		$rows = $_MooClass['MooMySQL']->query( $sql );
		$update_list[$v['senduid']] = 1;
	}
	//以前未送过,插入
	$list = array_diff_key( $list, $update_list );
	foreach( $list as $senduid => $time ){
		$sql = "INSERT INTO {$dbTablePre}service_leer SET fakenum = '1', receivenum = 1 ,sendtime='{$timestamp}', receivetime = '{$time}',senduid  = '{$senduid}',receiveuid = '{$uid}', send_del = '1' ";
		$rows = $_MooClass['MooMySQL']->query( $sql );
	}	
}
//note 检查是否绑定过期
function check_bind($bind_id){
	global $_MooClass,$dbTablePre;
	$time = time();
	
	$sql = "SELECT * FROM {$dbTablePre}members_bind WHERE id=".$bind_id;
	$bindrs = $_MooClass['MooMySQL']->getOne($sql);
	if($time - $bindrs['length_time'] > $bindrs['start_time']){
		if(!empty($bindrs['a_uid']) && !empty($bindrs['b_uid'])) {
			$sql = "UPDATE {$dbTablePre}members_base SET bind_id=0,isbind=0 
					WHERE uid IN({$bindrs['a_uid']},{$bindrs['b_uid']})";
			$_MooClass['MooMySQL']->query($sql);
			$value['bind_id']=0;
			$value['isbind']=0;
		}
		
		if (MOOPHP_ALLOW_FASTDB){			
			MooFastdbUpdate("members_base",'uid',$bindrs['a_uid'],$value);
			MooFastdbUpdate("members_base",'uid',$bindrs['b_uid'],$value);
		}
		
		$sql = "UPDATE {$dbTablePre}members_bind SET bind=2 WHERE id=".$bind_id;
		$_MooClass['MooMySQL']->query($sql);
		return 0;
	}else{
		return 1;
	}
}

/**
 * 更新诚信值
 * @param array $user_arr
 * @param array $user_cert = array()
 */
function reset_integrity($user_arr, $user_cert = array()){
	global $_MooClass, $dbTablePre;
	$start     = 0;//诚信值初始化0
	if(!is_array($user_arr)){//这里可以传一个members信息数组或者一个$uid
		
		$user_arr = MooMembersData($user_arr);
		/*if(MOOPHP_ALLOW_FASTDB){
			$user_arr = MooFastdbGet('members','uid',$user_arr);
		}else{
			$user_arr = $_MooClass['MooMySQL']->getOne("select * from {$dbTablePre}members where uid='$user_arr' ");
		}*/
	}
	$s_cid     = $user_arr['s_cid'];
	$uid       = $user_arr['uid'];

	$start += $s_cid == 30 ? 4 : ($s_cid  == 20 ? 6 : 0) ;//升级会员
	$start += $user_arr['images_ischeck'] == 1 ? 1 : 0 ;//形象照
	$lovetest = $_MooClass['MooMySQL']->getOne("select `lovetest` from {$dbTablePre}members_action where uid='$uid'");
	$start += $lovetest['lovetest'] ? 2 : 0 ;//爱情测试
	unset($lovetest);
	
	

	
    
	if(sizeof($user_cert) === 0){
		if(MOOPHP_ALLOW_FASTDB){
			$user_cert = MooFastdbGet('certification','uid',$uid);
		}else{
			$user_cert = $_MooClass['MooMySQL']->getOne("select * from {$dbTablePre}certification where uid='$uid'");
		}
	}
	$start += $user_cert['identity_check'] == 3 ? 2 : 0 ;//身份认证
	$start += $user_cert['video_check'] == 3 ? 2 : 0 ;//视频
	$start += $user_cert['education_check'] == 3 ? 1 : 0 ;//学历证明
	$start += $user_cert['occupation_check'] == 3 ? 1 : 0 ;//工作
	$start += $user_cert['marriage_check'] == 3 ? 2 : 0 ;//婚育
	$start += $user_cert['house_check'] == 3 ? 1 : 0 ;//身份认证
	$start += $user_cert['sms'] ? 1 : 0 ;//身份证明
	$start += $user_cert['telphone'] ? 2 : 0 ;//手机
	$start += $user_cert['email'] == 'yes' ? 2 : 0 ;//邮箱
	unset($user_cert,$user_arr);

	$sql = "UPDATE {$dbTablePre}members_search SET `certification`='{$start}' WHERE uid='{$uid}' ";
	$_MooClass['MooMySQL']->query($sql);
	$value['certification']=$start;
	MOOPHP_ALLOW_FASTDB && MooFastdbUpdate("members_search",'uid',$uid,$value);
	searchApi('members_man members_women') -> updateAttr(array('certification'),array($uid=>array($start)));
}
/**
 * 返回诚信值星级标识
 * @param array $user_arr
 * @param array $user_cert = array()
 * @param bool $small = false
 * return string
 */
function get_integrity($start, $small = false){
	$max_start = 6;//6星级
	//$base      = 0.25;
	$ext = '.gif';

	$imgurl    = 'public/system/images/start/default/';
	$imgurl    .= $small ? 's/' : '';
	$start_mod = $start % 4;
	$start = (int)($start / 4);//转换成完整星

	$return = '';
	$j = $i = 0;// $j有色星数
	for($i = 0;$i < $start;$i ++){//有色public/system/images/start/
		$return .= '<img src="'.$imgurl.'start01'.$ext.'">';
		++ $j;
	}
	if($start_mod){
		$return .= '<img src="'.$imgurl .'start'. ($start_mod * 25) . $ext.'">';
		++ $j;
	}
	for($j; $j < $max_start; ++ $j){//无色
		$return .= '<img src="'.$imgurl .'start02'.$ext.'">';
	}
	return $return;
}

/**
 * 快速搜索同步 
 * @param integer $uid 用户id
 * @param integer $type 属于那种类型的更新，1为写入快速搜索表，2为写入高级搜索表
 * no return 
 */
/*
function fastsearch_insert($uid,$type = 1){
	global $_MooClass, $dbTablePre;
	
	if($type == 1){		
		//note 查询判断会员性别，等其他字段信息
		$sql = "select uid,gender,birthyear,province,city,s_cid,bgtime,images_ischeck,is_lock,pic_num,city_star,usertype,regdate,certification from {$GLOBALS['dbTablePre']}members where uid = '{$uid}'";
		$row = $_MooClass['MooMySQL']->getOne($sql);
		
		$membersfastsort_arr = array();
		$membersfastsort_arr['uid'] = $row['uid'];
		$membersfastsort_arr['birthyear'] = $row['birthyear'];
		$membersfastsort_arr['province'] = $row['province'];
		$membersfastsort_arr['city'] = $row['city'];
		$membersfastsort_arr['s_cid'] = $row['s_cid'];
		$membersfastsort_arr['bgtime'] = $row['bgtime'];
		$membersfastsort_arr['images_ischeck'] = $row['images_ischeck'];
		$membersfastsort_arr['is_lock'] = $row['is_lock'];
		$membersfastsort_arr['pic_num'] = $row['pic_num'];
		$membersfastsort_arr['city_star'] = $row['city_star'];
		$membersfastsort_arr['usertype'] = $row['usertype'];
		$membersfastsort_arr['regdate'] = $row['regdate'];
		$membersfastsort_arr['certification'] = $row['certification'];
			
		//note 如果是男性会员,写入男姓快速搜索表
		if($row['gender'] == '0'){
			$sql = "select uid from {$GLOBALS['dbTablePre']}membersfastsort_man where uid = '{$uid}'";
			$row1 = $_MooClass['MooMySQL']->getOne($sql);
			//note 防止写入重复的数据
			if(!$row1['uid']){
				inserttable('membersfastsort_man',$membersfastsort_arr);
			}
		}
		//note 如果是女性会员,写入女姓快速搜索表
		if($row['gender'] == '1'){
			$sql = "select uid from {$GLOBALS['dbTablePre']}membersfastsort_women where uid = '{$uid}'";
			$row1 = $_MooClass['MooMySQL']->getOne($sql);
			//note 防止写入重复的数据
			if(!$row1['uid']){
				inserttable('membersfastsort_women',$membersfastsort_arr);
			}	
		}
	}	
	
	if($type == 2){
		//note 查询判断会员性别，等其他字段信息
		$sql = "select a.uid,a.gender,a.birthyear,a.marriage,a.education,a.salary,a.house,a.height,a.province,
				a.city,a.children,a.s_cid,a.bgtime,a.images_ischeck,a.is_lock,a.pic_num,a.city_star,a.usertype,a.regdate,a.certification,
		        b.weight,b.body,b.animalyear,b.constellation,b.bloodtype,b.hometownprovince,b.hometowncity,b.nation,
		        b.religion,b.family,b.language,b.smoking,b.drinking,b.occupation,b.vehicle,b.corptype,b.wantchildren 
		        from {$GLOBALS['dbTablePre']}members as a left join {$GLOBALS['dbTablePre']}memberfield as b on a.uid = b.uid  where a.uid = '{$uid}'";
		$row = $_MooClass['MooMySQL']->getOne($sql);
		
		//note 组装写入表字段的对应数组
		$membersfastadvance_arr = array();
		$membersfastadvance_arr['uid'] = $row['uid'];
		$membersfastadvance_arr['birthyear'] = $row['birthyear'];
		$membersfastadvance_arr['marriage'] = $row['marriage'];
		$membersfastadvance_arr['education'] = $row['education'];
		$membersfastadvance_arr['salary'] = $row['salary'];
		$membersfastadvance_arr['house'] = $row['house'];
		$membersfastadvance_arr['height'] = $row['height'];
		$membersfastadvance_arr['province'] = $row['province'];
		$membersfastadvance_arr['city'] = $row['city'];
		$membersfastadvance_arr['children'] = $row['children'];
		$membersfastadvance_arr['weight'] = $row['weight'];
		$membersfastadvance_arr['body'] = $row['body'];
		$membersfastadvance_arr['animalyear'] = $row['animalyear'];
		$membersfastadvance_arr['constellation'] = $row['constellation'];
		$membersfastadvance_arr['bloodtype'] = $row['bloodtype'];
		$membersfastadvance_arr['hometownprovince'] = $row['hometownprovince'];
		$membersfastadvance_arr['hometowncity'] = $row['hometowncity'];
		$membersfastadvance_arr['nation'] = $row['nation'];
		$membersfastadvance_arr['religion'] = $row['religion'];
		$membersfastadvance_arr['family'] = $row['family'];
		$membersfastadvance_arr['language'] = $row['language'];
		$membersfastadvance_arr['smoking'] = $row['smoking'];
		$membersfastadvance_arr['drinking'] = $row['drinking'];
		$membersfastadvance_arr['occupation'] = $row['occupation'];
		$membersfastadvance_arr['vehicle'] = $row['vehicle'];
		$membersfastadvance_arr['corptype'] = $row['corptype'];
		$membersfastadvance_arr['wantchildren'] = $row['wantchildren'];
		$membersfastadvance_arr['regdate'] = $row['regdate'];
		$membersfastadvance_arr['certification'] = $row['certification'];
		
		$membersfastadvance_arr['s_cid'] = $row['s_cid'];
		$membersfastadvance_arr['bgtime'] = $row['bgtime'];
		$membersfastadvance_arr['images_ischeck'] = $row['images_ischeck'];
		$membersfastadvance_arr['is_lock'] = $row['is_lock'];
		$membersfastadvance_arr['pic_num'] = $row['pic_num'];
		$membersfastadvance_arr['city_star'] = $row['city_star'];	
	
		//note 如果是男性会员,写入男姓快速搜索表
		if($row['gender'] == '0'){
			$sql = "select uid from {$GLOBALS['dbTablePre']}membersfastadvance_man where uid = '{$uid}'";
			$row1 = $_MooClass['MooMySQL']->getOne($sql);
			//note 防止写入重复的数据
			if(!$row1['uid']){
				inserttable('membersfastadvance_man',$membersfastadvance_arr);
			}	
		}
		//note 如果是女性会员,写入女姓快速搜索表
		if($row['gender'] == '1'){
			$sql = "select uid from {$GLOBALS['dbTablePre']}membersfastadvance_women where uid = '{$uid}'";
			$row1 = $_MooClass['MooMySQL']->getOne($sql);
			//note 防止写入重复的数据
			if(!$row1['uid']){
				inserttable('membersfastadvance_women',$membersfastadvance_arr);
			}
		}
	}
	
	
}
*/
/**
 * 快速搜索更新 
 * @param integer $uid 用户id
 * @param integer $type 属于那种类型的更新，1为写入快速搜索表，2为写入高级搜索表
 * no return 
 */
/*
function fastsearch_update($uid,$type = 1){
	global $_MooClass, $dbTablePre;
	
	//note 更新表的判断条件 
	$where_arr = array('uid'=>$uid);
	
	if($type == 1){		
		//note 查询判断会员性别，等其他字段信息
		$sql = "select uid,gender,birthyear,province,city,s_cid,bgtime,images_ischeck,is_lock,pic_num,city_star,usertype,regdate,certification from {$GLOBALS['dbTablePre']}members where uid = '{$uid}'";
		$row = $_MooClass['MooMySQL']->getOne($sql);
		
		$membersfastsort_arr = array();
		$membersfastsort_arr['uid'] = $row['uid'];
		$membersfastsort_arr['birthyear'] = $row['birthyear'];
		$membersfastsort_arr['province'] = $row['province'];
		$membersfastsort_arr['city'] = $row['city'];
		$membersfastsort_arr['s_cid'] = $row['s_cid'];
		$membersfastsort_arr['bgtime'] = $row['bgtime'];
		$membersfastsort_arr['images_ischeck'] = $row['images_ischeck'];
		$membersfastsort_arr['is_lock'] = $row['is_lock'];
		$membersfastsort_arr['pic_num'] = $row['pic_num'];
		$membersfastsort_arr['city_star'] = $row['city_star'];
		$membersfastsort_arr['usertype'] = $row['usertype'];
		$membersfastsort_arr['regdate'] = $row['regdate'];
		$membersfastsort_arr['certification'] = $row['certification'];
		
		//note 如果是男性会员,写入男姓快速搜索表
		if($row['gender'] == '0'){
			$sql = "select uid from {$GLOBALS['dbTablePre']}membersfastsort_man where uid = '{$uid}'";
			$row1 = $_MooClass['MooMySQL']->getOne($sql);
			//note 如果搜索表该会员的记录才更新
			if($row1['uid']){
				updatetable('membersfastsort_man',$membersfastsort_arr,$where_arr);
			}else{
				fastsearch_insert($uid,1);
			}
		}
		//note 如果是女性会员,写入女姓快速搜索表
		if($row['gender'] == '1'){
			$sql = "select uid from {$GLOBALS['dbTablePre']}membersfastsort_women where uid = '{$uid}'";
			$row1 = $_MooClass['MooMySQL']->getOne($sql);
			//note 如果搜索表该会员的记录才更新
			if($row1['uid']){
				updatetable('membersfastsort_women',$membersfastsort_arr,$where_arr);
			}else{
				fastsearch_insert($uid,1);
			}	
		}
	}	
	
	if($type == 2){
		//note 查询判断会员性别，等其他字段信息
		$sql = "select a.uid,a.gender,a.birthyear,a.marriage,a.education,a.salary,a.house,a.height,a.province,
			    a.city,a.children,a.s_cid,a.bgtime,a.images_ischeck,a.is_lock,a.pic_num,a.city_star,a.usertype,a.regdate,a.certification,
		        b.weight,b.body,b.animalyear,b.constellation,b.bloodtype,b.hometownprovince,b.hometowncity,b.nation,
		        b.religion,b.family,b.language,b.smoking,b.drinking,b.occupation,b.vehicle,b.corptype,b.wantchildren 
		        from {$GLOBALS['dbTablePre']}members as a left join {$GLOBALS['dbTablePre']}memberfield as b on a.uid = b.uid  where a.uid = '{$uid}'";
		$row = $_MooClass['MooMySQL']->getOne($sql);
		
		//note 组装写入表字段的对应数组
		$membersfastadvance_arr = array();
		$membersfastadvance_arr['uid'] = $row['uid'];
		$membersfastadvance_arr['birthyear'] = $row['birthyear'];
		$membersfastadvance_arr['marriage'] = $row['marriage'];
		$membersfastadvance_arr['education'] = $row['education'];
		$membersfastadvance_arr['salary'] = $row['salary'];
		$membersfastadvance_arr['house'] = $row['house'];
		$membersfastadvance_arr['height'] = $row['height'];
		$membersfastadvance_arr['province'] = $row['province'];
		$membersfastadvance_arr['city'] = $row['city'];
		$membersfastadvance_arr['children'] = $row['children'];
		$membersfastadvance_arr['weight'] = $row['weight'];
		$membersfastadvance_arr['body'] = $row['body'];
		$membersfastadvance_arr['animalyear'] = $row['animalyear'];
		$membersfastadvance_arr['constellation'] = $row['constellation'];
		$membersfastadvance_arr['bloodtype'] = $row['bloodtype'];
		$membersfastadvance_arr['hometownprovince'] = $row['hometownprovince'];
		$membersfastadvance_arr['hometowncity'] = $row['hometowncity'];
		$membersfastadvance_arr['nation'] = $row['nation'];
		$membersfastadvance_arr['religion'] = $row['religion'];
		$membersfastadvance_arr['family'] = $row['family'];
		$membersfastadvance_arr['language'] = $row['language'];
		$membersfastadvance_arr['smoking'] = $row['smoking'];
		$membersfastadvance_arr['drinking'] = $row['drinking'];
		$membersfastadvance_arr['occupation'] = $row['occupation'];
		$membersfastadvance_arr['vehicle'] = $row['vehicle'];
		$membersfastadvance_arr['corptype'] = $row['corptype'];
		$membersfastadvance_arr['wantchildren'] = $row['wantchildren'];
		$membersfastadvance_arr['usertype'] = $row['usertype'];
		$membersfastadvance_arr['regdate'] = $row['regdate'];
		$membersfastadvance_arr['certification'] = $row['certification'];
		
		$membersfastadvance_arr['s_cid'] = $row['s_cid'];
		$membersfastadvance_arr['bgtime'] = $row['bgtime'];
		$membersfastadvance_arr['images_ischeck'] = $row['images_ischeck'];
		$membersfastadvance_arr['is_lock'] = $row['is_lock'];
		$membersfastadvance_arr['pic_num'] = $row['pic_num'];
		$membersfastadvance_arr['city_star'] = $row['city_star'];	
	
		//note 如果是男性会员,写入男姓快速搜索表
		if($row['gender'] == '0'){
			$sql = "select uid from {$GLOBALS['dbTablePre']}membersfastadvance_man where uid = '{$uid}'";
			$row1 = $_MooClass['MooMySQL']->getOne($sql);
			//note 如果搜索表该会员的记录才更新
			if($row1['uid']){
				updatetable('membersfastadvance_man',$membersfastadvance_arr,$where_arr);
			}else{
				fastsearch_insert($uid,2);
			}	
		}
		//note 如果是女性会员,写入女姓快速搜索表
		if($row['gender'] == '1'){
			$sql = "select uid from {$GLOBALS['dbTablePre']}membersfastadvance_women where uid = '{$uid}'";
			$row1 = $_MooClass['MooMySQL']->getOne($sql);
			//note 如果搜索表该会员的记录才更新
			if($row1['uid']){
				updatetable('membersfastadvance_women',$membersfastadvance_arr,$where_arr);
			}else{
				fastsearch_insert($uid,2);
			}
		}
	}
	
	
}


*/

/**
 * 如果是锁定用户就删除快速搜索表用户行记录 
 * @param integer $uid 用户id
 * @param integer $type 属于那种类型的更新，1为写入快速搜索表，2为写入高级搜索表
 * no return 
 */
/*
function fastsearch_delrow($uid,$type = 1){
	global $_MooClass, $dbTablePre;
	
	if($type == 1){		
		//note 查询判断会员性别
		$sql = "select uid,gender,is_lock from {$GLOBALS['dbTablePre']}members where uid = '{$uid}'";
		$row = $_MooClass['MooMySQL']->getOne($sql);
		
		//note 如果是男性会员,
		if($row['gender'] == '0'){
			$sql = "select uid from {$GLOBALS['dbTablePre']}membersfastsort_man where uid = '{$uid}'";
			$row1 = $_MooClass['MooMySQL']->getOne($sql);
			
			//note 如果搜索表该会员的记录存在，锁定用户就删除
			if($row1['uid'] && $row['is_lock'] == '0'){
				$sql = "delete from {$GLOBALS['dbTablePre']}membersfastsort_man where uid = '{$uid}'";
				$_MooClass['MooMySQL']->query($sql);
			}

		}
		
		//note 如果是女性会员,写入女性快速搜索表
		if($row['gender'] == '1'){
			$sql = "select uid from {$GLOBALS['dbTablePre']}membersfastsort_women where uid = '{$uid}'";
			$row1 = $_MooClass['MooMySQL']->getOne($sql);
			
			//note 如果搜索表该会员的记录存在，锁定用户就删除
			if($row1['uid'] && $row['is_lock'] == '0'){
				$sql = "delete from {$GLOBALS['dbTablePre']}membersfastsort_women where uid = '{$uid}'";
				$_MooClass['MooMySQL']->query($sql);
			}
		}
	}	
	
	if($type == 2){
		//note 查询判断会员性别，等其他字段信息
		$sql = "select uid,gender,is_lock from {$GLOBALS['dbTablePre']}members where uid = '{$uid}'";
		$row = $_MooClass['MooMySQL']->getOne($sql);
	
		//note 如果是男性会员,写入男性快速搜索表
		if($row['gender'] == '0'){
			$sql = "select uid from {$GLOBALS['dbTablePre']}membersfastadvance_man where uid = '{$uid}'";
			$row1 = $_MooClass['MooMySQL']->getOne($sql);
			
			//note 如果搜索表该会员的记录才更新
			if($row1['uid'] && $row['is_lock']){
				$sql = "delete  from  {$GLOBALS['dbTablePre']}membersfastadvance_man where uid = '{$uid}'";
				$_MooClass['MooMySQL']->query($sql);
			}	
		}
		//note 如果是女性会员,写入女性快速搜索表
		if($row['gender'] == '1'){
			$sql = "select uid from {$GLOBALS['dbTablePre']}membersfastadvance_women where uid = '{$uid}'";
			$row1 = $_MooClass['MooMySQL']->getOne($sql);
			
			//note 如果搜索表该会员的记录才更新
			if($row1['uid'] && $row['is_lock']){
				$sql = "delete  from  {$GLOBALS['dbTablePre']}membersfastadvance_women where uid = '{$uid}'";
				$_MooClass['MooMySQL']->query($sql);
			}	
		}
	}
	
	
}
*/



//获得会员的资料完整度 makui
function getUserinfo_per($member_inf){
	global $dbTablePre,$_MooClass;
	//print_r($member_inf);
	/*
	if(MOOPHP_ALLOW_FASTDB){
		$mem_field = MooFastdbGet('memberfield','uid',$member_inf['uid']);	
	}else{
		$mem_field = $_MooClass['MooMySQL']->getOne("SELECT * FROM {$dbTablePre}member
		field as mf WHERE uid='{$member_inf['uid']}'");
	}
	$member_info_all=array_merge($member_inf,$mem_field);
	//$user_info_num=getUserinfo($member_info);
	*/
	$member_info_all = MooMembersData($member_inf['uid']);
	
	$member_info_all['birthyear']=$member_info_all['birthmonth']=$member_info_all['birthday']='';
	if(!empty($member_info_all['birth']) && strlen($member_info_all['birth'])==10){
	   list($member_info_all['birthyear'],$member_info_all['birthmonth'],$member_info_all['birthday']) = explode('-',$member_info_all['birth']);
	}
	$info_array=array('nickname','telphone','gender','birthyear','birthmonth','birthday','province','city','marriage','education','salary','house','children','height','oldsex','mainimg','truename','nature','weight','body','animalyear','constellation','bloodtype','hometownprovince','hometowncity','nation','religion','finishschool','family','language','smoking','drinking','occupation','vehicle','corptype','wantchildren','fondfood','fondplace','fondactivity','fondsport','fondmusic','fondprogram','blacklist','qq','msn','currentprovince','currentcity','friendprovince');
	$i = 0;
	foreach($info_array as $v){
		if($member_info_all[$v]){
			$i++;
		}
		
	}
	return ($i/48);
	
	//echo count($info_array);
}
/**
 * 真实文件类型(图片) dsk
 *
 */
function file_type($filename){
	$file = fopen($filename, "rb");
	$bin = fread($file, 2); //只读2字节
	fclose($file);
	$strInfo = @unpack("C2chars", $bin);
	$typeCode = intval($strInfo['chars1'].$strInfo['chars2']);
	$type_arr = array(7790=>'exe', 7784=>'midi', 8297=>'rar', 255216=>'jpeg', 7173=>'gif', 6677=>'bmp', 13780=>'png');
	return $type_arr[$typeCode] ? $type_arr[$typeCode] : 'unknown';
}
/**
 * 文字轮播
 *
 */
function text_show(){
	global $_MooClass;
	$time_list = time();
	$sql = "select content,uid from web_text_show where start_time<'$time_list' and end_time>'$time_list' and `show` = '1'  order by `order` asc limit 0,15";
	$text_list = $_MooClass['MooMySQL']->getAll($sql);
	foreach($text_list as $key => $value){
		$text_list[$key]['list'] = "<a href = 'index.php?n=space&uid={$value['uid']}'>".$value['uid']."</a>".$value['content'];	
	}
	return $text_list;
}

/**
 * search类调用接口
 * @param $index
 */
function searchApi($index){
	static $sp = array();
	
	if(empty($index)) return null;
	
	if(!isset($sp[$index]) || !is_object($sp[$index])){
		$sp[$index] = MooAutoLoad("MooSearch");
		$sp[$index] -> setIndex($index);
	}
	
	return $sp[$index];
}

/**
 * 输入要查询的语言选项的和,返回符合该选项的所有language字段的值
 * @param int $language_value 要查询的语言选项的和 
 * return array
 */
function getSearchLanguage($language_value){
	$return_language = array();
	for ($i=1;$i<=63;$i++){
		if (($i&$language_value)== $language_value){
			$return_language[] = $i;
		}
	}
	return $return_language;
}

/**
 * 后台添加黑名单
 */
function BlackList(){
	$sql = "select uid from web_members_blacklist";
    $result=$GLOBALS['_MooClass']['MooMySQL']->getAll($sql); 
    if(is_array($result) && !empty($result)){
        foreach($result as $k=>$v){
    		$blacklist[] = (int)$v["uid"];
    	}
    	return $blacklist;
    }
    return array();
}

/**
 * 从表web_members_search和web_members_base里的表里获取内容
 * @Author:
 * param $uid int 字段 用户id值
 * param $getfield string 你要获取的字段值 单一字段 
 * return $arr  array()/string 如果设置了getfield字段,将返回字段值，不设置将返回所有数据
 */
function MooMembersData($uid, $getfield=False){
	global $_MooClass, $dbTablePre;
	
	if(!$uid) return array();
	static $member_all = array();
	if(isset($member_all[$uid])){
		if($getfield){
			return isset($member_all[$uid][$getfield]) ? $member_all[$uid][$getfield] : NULL; 
		}else{
			return $member_all[$uid];
		}
	}	
	
	if(MOOPHP_ALLOW_FASTDB){

		$member_all[$uid] = MooFastdbGet('members_search','uid',$uid);		
		if($member_base = MooFastdbGet('members_base','uid',$uid)){
			$member_all[$uid] = array_merge($member_all[$uid], $member_base);
		}
             
		$s_cid = $_MooClass['MooMySQL']->getOne("select s_cid from " . $dbTablePre . "members_search where uid=" . $uid,true);
		if(isset($s_cid['s_cid']) && $s_cid['s_cid']) $member_all[$uid]['s_cid'] = $s_cid['s_cid'];
	}else{
		$member_all[$uid] = $_MooClass['MooMySQL']->getOne("select * from " . $dbTablePre . "members_search where uid=" . $uid,true);
		if($member_base = $_MooClass['MooMySQL']->getOne("select * from " . $dbTablePre . "members_base where uid=".$uid,true)){
			$member_all[$uid] = array_merge($member_all[$uid], $member_base);
		}
		
	}
	
	if($getfield) return isset($member_all[$uid][$getfield]) ? $member_all[$uid][$getfield] : NULL;
	
	
	return $member_all[$uid];
	
}

/**
 * 从表的表里获取内容
 * @Author:
 * param $table string 表名 不带前缀
 * param $fieldname string 字段名
 * param $fieldvalue string 字段值
 * param $getfield string 你要获取的字段值 字段间用半角逗号分开， 仅对未开启memcahe缓存时可用
 * return $arr  array()
 */
function MooGetData($table, $fieldname, $fieldvalue, $getfield=''){
	global $_MooClass, $dbTablePre;
	
	$data = array();
	
	if(MOOPHP_ALLOW_FASTDB){
		$data = MooFastdbGet($table, $fieldname, $fieldvalue);	
	}else{
		$getfield = $getfield ? $getfield : '*';		
		$data = $_MooClass['MooMySQL']->getOne("SELECT {$getfield} FROM {$dbTablePre}{$table} WHERE {$fieldname}='{$fieldvalue}'");
	}
	
	return $data;
	
}

/**
 * 设置用户在线状态
 * @param int $uid 用户ID值
 */
function MooUserOnline($uid){
	global $memcached;
	
	if(!MOOPHP_ALLOW_MEMCACHED) return;
	
	$count_key = MEMBERS_ONLINE_COUNT;
	$key = MEMBERS_ONLINE_SINGLE.$uid;
	$area = MooMembersData($uid, 'province').'.'.MooMembersData($uid, 'city');
	if($memcached->get($key)){
		$memcached->replace($key, $area, 0,300);
	}else{
		$memcached->set($key, $area, 0,300);
		$count_str = '';
		if($count_uids = $memcached->get($count_key)){
			if(strpos($count_uids, $uid)!==false) return; 
			$count_uids .= ','.$uid;
			$memcached->replace($count_key, $count_uids);
		}else{
			$memcached->set($count_key, $uid);
		}
		
	}
	
}

/*
 * 总在线人数
 */
function MooUserIsOnlineNum(){
	global $memcached, $userid;

	$count_key = MEMBERS_ONLINE_COUNT;
	$uids_str = $memcached->get($count_key);
	
	return substr_count($uids_str, ',')+1;
}

/**
 * 用户是否在线
 * @param int $uid 用户ID值
 */
function MooUserIsOnline($uid){
	global $memcached;
	return $memcached->get(MEMBERS_ONLINE_SINGLE.$uid);
}

/*
 * 写到缓存
 */
/*
 * function MemoryCache($name, $content=''){
	global $memcached;
	
	$name = 'cache_visitor_'.$name;
	
	if($content == '') return $memcached->get($name);
	
	if($c = $memcached->get($name)){
		$memcached->replace($name, $c.'<br/>'.$content);
	}else{
		$memcached->set($name, $content);
	}
	
}*/
<?php

/**
 * 文本转HTML
 *
 * @param string $txt; 传入文本
 * return string; 段落自动首字空两格
 */
function TextHtml($txt){
	$txt = str_replace("  ","　",$txt);
	$txt = str_replace("<","&lt;",$txt);
	$txt = str_replace(">","&gt;",$txt);
	$txt = preg_replace("/[\r\n]{1,}/isU","</p><p>",$txt);
	return $txt;
}

/**
 * 显示相册中的图片
 * @param integer $uid 传递过来的故事id参数
 */
function showPic($sid) {
	global $_MooClass, $dbTablePre;
	$show_img = $_MooClass['MooMySQL']->getAll("SELECT img FROM {$dbTablePre}story_pic WHERE sid='$sid' and syscheck=1",false,true,360);
	return $show_img;
}
/**
 * 相册中多个相片，其中一张作为首页相片显示
 * @param $sid 传递爱情故事id
 * @return string 返回图片名，如果没有图片返回0
 */
function marriagePic($sid) {
	global $_MooClass, $dbTablePre,$userid;
	
	//note 如果选择其中一张作为首页相片，就返回这一张图片路径
	$pic2 = $_MooClass['MooMySQL']->getOne("SELECT is_index,uid FROM {$dbTablePre}story WHERE sid = '$sid'");
	
	if($pic2['is_index']) {
		$mid = $pic2['is_index'];
		$img_arr = $_MooClass['MooMySQL']->getOne("SELECT img FROM {$dbTablePre}story_pic WHERE mid='$mid'");
		$img = $img_arr['img'];
	}else {
		//note 如果没有选择首页相片的话，默认第一个作为首页相片
		$pic1 = $_MooClass['MooMySQL']->getOne("SELECT img FROM {$dbTablePre}story_pic WHERE uid = '$pic2[uid]' limit 0,1");
		
		if($pic1['img']) {
			$img = $pic1['img'];
		}else {
			$img = 0;
		}
	}
	return $img;
}
/**
 * 判断目前的婚姻状态
 *
 * @param integer $state 1为恋爱,2为订婚,3为结婚
 * @param return integer 返回代表婚姻状态数字,默认返回恋爱 
 */
function marriageState($state) {
	switch($state) {
		case '1' :
			$renum = '2';
			break;	
		case '2' :
			$renum = '3';
			break;
		case '3' :
			$renum = '1';
			break;
		default :			
		        $renum = '2';
	}
	return $renum;
}

/**
 *@功能 分页函数
 *@$num 为总共的条数   比如说这个分类下共有15篇文章    
 *@$perpage 为每页要显示的条数    
 *@$curpage 为当前的页数    
 *@$mpurl 为url的除去表示页数变量的一部分 
 */
function storyPage($num, $perpage, $curpage, $mpurl, $ajaxdiv='', $todiv='') {
	global $_SCONFIG, $_SGLOBAL;

	if(empty($ajaxdiv) && $_SGLOBAL['inajax']) {
		$ajaxdiv = $_GET['ajaxdiv'];
	}
	//note 每页显示的页码数
	$page = 5;
	if($_SGLOBAL['showpage']) $page = $_SGLOBAL['showpage'];

	$multipage = '';
	$mpurl .= strpos($mpurl, '?') ? '&' : '?';
	$realpages = 1;
	if($num > $perpage) {
		$offset = 2;
		$realpages = @ceil($num / $perpage);
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
		//note 上一页按钮
		if($curpage > 1){
			$multipage .= '<input name="" type="button" class="page_btn" value="上一页" ';
			$multipage .= 'onclick="location.href=\'index.php?n=story&h=list&page='.($curpage-1).'\'"';
			$multipage .= '/>';
		}else{
			$multipage .= '<input name="" type="button" class="page_btn" value="上一页" />';
		}
		//note 页码
		$multipage .= '<div class="page_ye">第';
		for($i = $from; $i <= $to; $i++) {
		//for($i = $from; $i <= 5; $i++) {
			if($i == $curpage) {
				$multipage .= '<font class="page_link">'.$i.'</font>';
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
		$multipage .= "页";
		if($multipage) {
			// $multipage .= '共'.$realpages.'页</div>';
			$multipage .= '</div>';
		}
		//note 下一页按钮
		if($curpage < $pages) {
			$multipage .= '<input name="" type="button" class="page_btn" value="下一页" ';
			$multipage .= 'onclick="location.href=\'index.php?n=story&h=list&page='.($curpage+1).'\'"';
			$multipage .= '/>';
		}else{
			$multipage .= '<input name="" type="button" class="page_btn" value="下一页" />';
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


/*
 * 从post接受内容写入评论
 * 
 * */

function addComment( $type, $id ){
	global $_MooClass, $dbTablePre, $user_arr;
	$content = Text2Html( MooGetGPC('content', 'string', 'P') );
	
	$temp = mb_convert_encoding( $content, 'gbk', 'utf-8' );
	if( $content == '' || isset($temp{220}) ){
		return false;
	}
	
	$arr_in = array(
		'type' 		=> $type,
		'id' 		=> $id,
		'uid' 		=> $user_arr['uid'],
		'username' 	=> $user_arr['nickname'],
		'dateline' 	=> time(),
		'ischeck' 	=> 0,
		'comment' 	=> $content,	
	);
	
	return inserttable( 'comment', $arr_in, 1 );
}

/*
 * 按用户读取评论
 * 
 * */
function getComment( $type, $id ){
	global $dbTablePre, $user_arr;
	$page_per = 15;
	$page_now = MooGetGPC('page', 'integer', 'G');
	$page_url = preg_replace( '/&page=[^&]*/', '', $_SERVER['REQUEST_URI'] );
	$uid = empty( $user_arr['uid'] ) ? 0 : $user_arr['uid'];
	$from_where = "FROM {prefix}comment WHERE type = '$type' AND id= '$id' AND ( ischeck = 1 OR uid = '$uid' ) ";
	
	return readTable( $page_now, $page_per, $page_url, $from_where,  $order_by = 'ORDER BY cid DESC', $fd_list = '*' );
}

/**
 * 按指定参数以分页的模式的读取数据 
 * */
function readTable( $page_now, $page_per, $page_url, $from_where,  $order_by = '', $fd_list = '*' ){
	function pageLinks( $page_num, $page_now, $page_url, $link_num = 5 ){
		if( $page_num <= 1 ) return '';
		$page_url .= strpos($page_url, '?') ? '&page=' : '?page=';
		$page_prev = $page_now>1 ? $page_now-1 : 1;
		$page_next = $page_now<$page_num ? $page_now + 1 : $page_num;
		
		$a = $page_now - ceil($link_num/2);if( $a < 1 ) $a = 1;
		$b = $a + $link_num > $page_num ? $page_num : $a + $link_num;
		$a = $b - $link_num;if( $a < 1 ) $a = 1;
		
		//数据显示
		$str = '<li class="pages"><a href="'.$page_url.$page_prev.'">上一页</a>&nbsp;&nbsp;&nbsp;&nbsp;第&nbsp;';
		for( $i=$a; $i<=$b; $i++ ){
			if( $i==$page_now ){
				$str .= '&nbsp;<strong style="color: rgb(189, 0, 0);">'.$i.'</strong>&nbsp;';
			}else{
				$str .= '&nbsp;<a href="'.$page_url.$i.'">'.$i.'</a>&nbsp;';
			}
		}
		$str .= '&nbsp;页&nbsp;&nbsp;共1'.$page_num.'页&nbsp;&nbsp;<a href="'.$page_url.$page_next.'">下一页</a>';
		
		return $str;	
	}
	
	$from_where = str_replace( '{prefix}', $GLOBALS['dbTablePre'], $from_where );
	//分页
	$tmp = $GLOBALS['_MooClass']['MooMySQL']->getOne( 'SELECT COUNT(*) N '.$from_where );
	$item_num = $tmp['N'];
	$page_num = ceil( $item_num / $page_per );
	if( $page_now > $page_num ) $page_now = $page_num;
	if( $page_now < 1 ) $page_now = 1;
	$page_links = pageLinks( $page_num, $page_now, $page_url );
	
	//读数据
	$start = ( $page_now - 1 ) * $page_per;
	$sql = "SELECT $fd_list $from_where $order_by LIMIT $start, $page_per";
	$question_list = $GLOBALS['_MooClass']['MooMySQL']->getAll( $sql );
		
	return array(
		'count'		 => $item_num,
		'page_links' => $page_links,
		'list'		 => $question_list 
	);
	
}




?>
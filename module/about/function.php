<?php

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
<?php
//note 钻石会员音乐列表
function music_list(){
	$where = '';
	$uid = MooGetGPC('uid','integer');
	if($uid > 0){
		$where = "WHERE uid=".$uid;
	}
	$currenturl = "index.php?action=diamondmusic&h=list";
	$page = max(1,MooGetGPC('page','integer'));
    $limit = 18;
    $offset = ($page-1)*$limit;
	$sql = "SELECT COUNT(1) AS c FROM {$GLOBALS['dbTablePre']}art_music {$where}";
    $total = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	$total = $total['c'];
    
	$sql = "SELECT mid,uid,title,note FROM {$GLOBALS['dbTablePre']}art_music 
			{$where}
			ORDER BY mid DESC 
			LIMIT {$offset},{$limit}";
	$music = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	$pages = multipage( $total, $limit, $page, $currenturl ); 
	require adminTemplate('diamond_music_list');
}
//删除
function music_del(){
	$uid = MooGetGPC('uid','integer','G');
	$mid = MooGetGPC('mid','integer','G');
	if($uid > 0 && $mid > 0){
		$sql = "DELETE FROM {$GLOBALS['dbTablePre']}art_music WHERE uid={$uid} AND mid={$mid}";
		$del = $GLOBALS['_MooClass']['MooMySQL']->query($sql);
	}
	if($del){
		salert('成功删除会员'.$uid.'ID为'.$mid.'的音乐');
	}else{
		saler('操作失败...');
	}
	echo "<script>window.history.go(-1);</script>";
	
}
//上传音乐
function music_add(){
	//note 指定会员POST
	$post_old_uid = !empty($_POST['old_uid'])?$_POST['old_uid']:'';
	$post_uid = !empty($_POST['uid'])?$_POST['uid']:'';
	$old_uid = $post_old_uid ? MooGetGPC('old_uid','integer','P') : MooGetGPC('uid','integer','G');
	$uid = $post_uid ? MooGetGPC('uid','integer','P') : $old_uid ;
	if( isset( $_POST['ispost'] ) && !empty($uid) ){
		$ps = '信息不全';
		//是否MP3
		if( preg_match( '/\.mp3$/i', $_FILES['mp3']['name'] ) ){
			//限制大小6兆
			if( $_FILES['mp3']['size'] < 6*1024*1024 ){
				MooMakeDir(MP3_DIR.'/'.substr($uid,-2));
				
				$file = str_replace('\\\\', '\\', $_FILES['mp3']['tmp_name']);
				$path = substr($uid,-2).'/'.$uid.'_'.time().'.mp3';
				if( move_uploaded_file( $file, MP3_DIR.$path ) ){
					$title 	= MooGetGPC('title', 'string','P');
					$path 		= $path;
					$note 		= MooGetGPC('note', 'string','P');
					$dateline 	= time();
					
					$sql = "INSERT INTO {$GLOBALS['dbTablePre']}art_music (uid,path,title,note,dateline) VALUES ('{$uid}','{$path}','{$title}','{$note}','{$dateline}')";
					$insert = $GLOBALS['_MooClass']['MooMySQL']->query($sql);
					$ps = '添加音频成功...';
				}else{$ps = '文件保存失败...';}
				
			}else{$ps = 'MP3大小不能超过6KB...';}
			
		}else{$ps = '请上传MP3格式...';}
		
		salert($ps,'index.php?action=diamond_music&h=list');
		//echo "<script>window.history.go(-1);</script>";
	}
	require adminTemplate( 'diamond_music_add' );
}
/***********************************************控制层(C)*****************************************/
$h=MooGetGPC('h','string')=='' ? 'list' : MooGetGPC('h','string');
//note 动作列表
$hlist = array('list','del','add');
//note 判断页面是否存在
if(!in_array($h,$hlist)){
    salert('您要打开的页面不存在');
	echo "<script>window.history.go(-1);</script>";
	exit;
}
define( 'MP3_DIR', '../data/mp3/');
//note 判断是否有权限
if(!checkGroup('site_diamond',$h)){
  //salert('您没有修改此操作的权限');
}
switch($h){
	case 'list':
		music_list();
	break;
	case 'del':
		music_del();
	break;
	case 'add':
		music_add();
	break;
}
?>
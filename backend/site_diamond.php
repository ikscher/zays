<?php
/*
解压定制模板
$uid 		钻石会员uid
$from_zip 	根据一定目录结构和命名格式打包的定制模板
return 		失败返回false,成功返回解压列表
*/
function unZip( $uid, $from_zip ){
	$afety_tag = 'Safety first -- ts24'; //
	require 'include/pclzip.lib.php';
	$to_dir = '../data/diamond/'.substr( $uid, -1 ).'/';
	
	//允许的非模板文件类型
	$file_type = array( 'jpg','gif','jpeg','js','css', 'mp3', 'wma'	);

	//解压文件条件判断
	$reg_type = '/^'.$uid.'\/.+\.('.implode('|',$file_type).')$/i';//
	$reg_htm = '/^'.$uid.'\/'.intval($uid).'_.+\.htm$/i';
	
	$zip = new PclZip( $from_zip );
	
	//包内所有文件列表
	$all_list = $zip->listContent();
	
	//获取解压列表
	$out_index = array();
	$arr_ret = array();
	if( empty($all_list) ) $all_list = array();
	foreach( $all_list as $k => $v ){
		if( substr( $v['filename'], -1 ) != '/' ){
			if( 'htm' == substr($v['filename'],-3) ){
				$tmp = preg_match( $reg_htm, $v['filename'] );
			}else{
				$tmp = preg_match( $reg_type, $v['filename'] );
			}
			if( $tmp ){ //合格文件,可解压
				$arr_ret[$v['filename']] = 1;
				$out_index[] = $k;
			}else{
				$arr_ret[$v['filename']] = 0;
			}
		}
	}
	//解压
	if( empty($out_index) ) return $arr_ret;
	$ret = $zip->extract( PCLZIP_OPT_PATH, $to_dir, PCLZIP_OPT_BY_INDEX, $out_index	);
	if( $ret == 0 ){
		return array();
	}
	return $arr_ret;
}

function getFileList($path){
	if(!is_dir($path)) return;
	$path = rtrim($path,'/').'/';
	$arr_file = array();

	if ($handle = opendir($path)) {
		while (false !== ($file = readdir($handle))) {
			if($file!="." and $file!=".."){
				if(is_file($path.$file))$arr_file[]= $file;
			}
		}
		closedir($handle);
	}
	asort($arr_file);
	return $arr_file;
}
 

/*******************************************逻辑层(M)/表现层(V)*****************************************/
//note 钻石会员列表
function site_diamond_list(){
	$page = max(1,MooGetGPC('page','integer'));
    $limit = 15;
    $offset = ($page-1)*$limit;
    
    $type = MooGetGPC('type','string');
	$keyword = MooGetGPC('keyword','string');
	$c_uid 	  = MooGetGPC('uid', 'integer', 'G');	//需修改状态的uid
	$change	  = MooGetGPC('c', 'string','G');		//修改类型,增或减
	
	if( $c_uid && in_array( $change, array('left','right') ) ){
		$ret = ($change=='left' ? '-(status > 1)' : '+(status < 3 )');
		$sql = "UPDATE {$GLOBALS['dbTablePre']}diamond SET status = status $ret WHERE uid='{$c_uid}'";
		$GLOBALS['_MooClass']['MooMySQL']->query($sql);
	}
	
	$where = "";
    $total = getcount('diamond',$where);
    $currenturl = "index.php?action=site_diamond&h=list";   
    if(!empty($type) && !empty($keyword)){
    	$where = "WHERE {$type} = '{$keyword}'";
    	$total = getcount('diamond',$where);
    	$currenturl = "index.php?action=site_diamond&h=list&type={$type}&keyword={$keyword}";
    }
    
    $sql = "SELECT * FROM {$GLOBALS['dbTablePre']}diamond {$where} LIMIT {$offset},{$limit}";
    $members = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
    
   	$pages = multipage( $total, $limit, $page, $currenturl ); 	
	$arr_status = array( 1=> '定制中', 2=> '确认中', 3 => '已完成' );
	require_once(adminTemplate('site_diamond_list'));
}
//note 视频列表
function site_diamond_video_list(){
	$page = max(1,MooGetGPC('page','integer'));
    $limit = 10;
    $offset = ($page-1)*$limit;
    
    $type = MooGetGPC('type','string');
	$keyword = MooGetGPC('keyword','string');
	//note UID
	$uid = MooGetGPC('uid','integer','G');
	$where = "";
    $total = getcount('video',$where);
    $currenturl = "index.php?action=site_diamond&h=video_list";   
    if(!empty($type) && !empty($keyword)){
    	$where = "WHERE {$type} = '{$keyword}'";
    	$total = getcount('video',$where);
    	$currenturl = "index.php?action=site_diamond&h=video_list&type={$type}&keyword={$keyword}";
    } 
    $sql = "SELECT * FROM {$GLOBALS['dbTablePre']}video {$where} LIMIT {$offset},{$limit}";
	if($uid > 0){
		$sql = "SELECT * FROM {$GLOBALS['dbTablePre']}video WHERE uid=".$uid;
	}else{
		$pages = multipage( $total, $limit, $page, $currenturl ); 	
	}
    $videos = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
    
   	
	require_once(adminTemplate('site_diamond_video_list'));
}
//note 上传视频
function site_diamond_video_add(){
	$uid = MooGetGPC('uid','integer','G');
	if($_POST){
		$vid = MooGetGPC('vid','integer','P');
		$param['uid'] = MooGetGPC('uid','integer','P');
		//$param['filename'] = MooGetGPC('video_name','string','P');
		$param['subject'] = MooGetGPC('subject','string','P');
		$param['intro'] = MooGetGPC('intro','string','P');
		$param['expert_comment'] = MooGetGPC('expert_comment','string','P');
		$param['dateline'] = time();
		if($vid){
			updatetable('video',$param,array('vid'=>$vid));
		}
	}
	require_once(adminTemplate('site_diamond_video_add'));
}
//视频删除
function diamond_video_del(){
	$id = MooGetGPC('id','integer','G');
	if(!empty($id)){
		$sql = "select vid,filepath,filename from {$GLOBALS['dbTablePre']}video where vid='$id'";
		$video = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql,0,0,0,true);
		if($video['filepath']){
			$ext = strrchr($video['filename'],'.');
			$r_filename = $video['filepath'].'/'.(md5($video['vid'] . MOOPHP_AUTHKEY)).$ext;
			unlink($r_filename);
		}
		$sql = "delete from {$GLOBALS['dbTablePre']}video where vid='$id'";
		$GLOBALS['_MooClass']['MooMySQL']->query($sql);
		$sql = "delete from {$GLOBALS['dbTablePre']}video_comment where vid='$id'";
		$GLOBALS['_MooClass']['MooMySQL']->query($sql);
	}
	echo "<script>alert('删除成功');history.go(-1)</script>";
	//header("Location:index.php?n=diamond&h=video_list");
}
//note 编辑视频
function site_diamond_video_edit(){
	$isedit = 1;
	$vid = MooGetGPC('vid','integer');
	$uid = MooGetGPC('uid','integer','P');
	$ispost = MooGetGPC('ispost','integer','P');
	
	if($ispost){
		$subject = MooGetGPC('subject','string','P');
		$intro = MooGetGPC('intro','string','P');
		$comment = MooGetGPC('comment','string','P');
		$sql = "UPDATE {$GLOBALS['dbTablePre']}video SET subject = '{$subject}', intro = '{$intro}', expert_comment = '{$comment}' WHERE vid = {$vid}";
		$GLOBALS['_MooClass']['MooMySQL']->query($sql);
		//写日志
		serverlog(2,$GLOBALS['dbTablePre'].'video',"客服{$GLOBALS['username']}编辑视频ID{$vid}",$GLOBALS['adminid'],$uid);
		salert("编辑成功");
	}
	
	$sql = "SELECT * FROM {$GLOBALS['dbTablePre']}video WHERE vid = {$vid}";
	$video = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
	
	require adminTemplate('site_diamond_video_add');
}
//修改封面截图
function diamond_video_pic(){
	$vid = MooGetGPC('vid','integer','G');
	if(isset($_GET['d'])){
		$d = MooGetGPC('d','integer','G');
		$sql = "update {$GLOBALS['dbTablePre']}video set pic='$d' where vid='$vid'";
		$GLOBALS['_MooClass']['MooMySQL']->query($sql);
		echo "<script>alert('设置成功');location.href='index.php?action=site_diamond&h=video_pic&vid={$vid}'</script>";exit;
	}
	$sql = "select filepath,filename,pic from {$GLOBALS['dbTablePre']}video where vid='$vid'";
	$video = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
	$pic_path_arr = explode('.',$video['filename']);
	$pic_path = $pic_path_arr[0];
	
	$pic_path_9 = $video['filepath'].'/'.$pic_path.'_9.jpg';
	require adminTemplate( "diamond_video_pic" );
}
//媒体报道和成功故事留言处理
function diamond_words(){
	//处理
	if ( isset($_POST['ispost']) ){
		if( !empty($_POST['checkbox']) ){
			$sql = "UPDATE {$GLOBALS['dbTablePre']}comment SET ischeck = '{$_POST['set_state']}' WHERE cid IN (".implode(',',$_POST['checkbox']).") ";
			$GLOBALS['_MooClass']['MooMySQL']->query($sql);
		}
	}
	
	//显示
	$t = MooGetGPC('t', 'integer','G'); //留言类型{0:所有,1:媒体报道,2:成功故事}
	$c = MooGetGPC('c', 'integer','G');	//审核状态{0:所有,1:未审核,2:已通过,3:不通过}
	if( !in_array( $t, array(0,1,2) ) ) 	$t = 0;
	if( !in_array( $c, array(0,1,2) ) ) 	$c = 0;
	$arr_w = array();
	if( $t ) $arr_w[] = " type=$t ";
	$arr_w[] = " ischeck = $c ";
	$str_w = empty($arr_w) ? '' : ' WHERE '.implode( 'AND', $arr_w );
	
	$page_now = MooGetGPC('page', 'integer');
	$page_per = 15;
	$page_url = "index.php?n=diamond_t&h=words&t=$t&c=$c";
	$from_where ="FROM {prefix}comment ".$str_w;
	$data = readTable( $page_now, $page_per, $page_url, $from_where, 'ORDER BY cid', '*' );
	
	require adminTemplate( 'diamond_words' );
}
//删除评论
function diamond_comment_del(){
	global $_MooClass,$dbTablePre;
	$id = MooGetGPC('id','integer','G');
	$sql = "delete from {$GLOBALS['dbTablePre']}manage_comment where id='$id'";
	$GLOBALS['_MooClass']['MooMySQL']->query($sql);
	salert('删除成功。');
	header("Location:index.php?action=site_diamond&h=comment");
}
//编辑&添加评论
function diamond_comment_edit(){
	$id = MooGetGPC('id','integer');
	$flag = false;
	if($_POST){
		$comment_type = MooGetGPC('comment_type','integer','P');
		$comment = MooGetGPC('comment','string','P');
		$comment_sort = MooGetGPC('comment_sort','integer','P');
		$sql = "update {$GLOBALS['dbTablePre']}manage_comment set comment='$comment',comment_type='{$comment_type}', comment_sort='{$comment_sort}' where id='$id'";
		$ps = '修改成功。';
		if($id == 0){
			$sql = "INSERT INTO {$GLOBALS['dbTablePre']}manage_comment (comment,comment_type,comment_sort) VALUES ('{$comment}','{$comment_type}','{$comment_sort}')";
			$ps = '添加成功。';
			$flag = true;
		}
		$GLOBALS['_MooClass']['MooMySQL']->query($sql);
		if($falg){
			//salert($ps,'index.php?action=site_diamond&h=comment');
			header("Location:index.php?action=site_diamond&h=comment");
			exit;
		}else{
			echo "<script>alert('{$ps}');history.go(-1);</script>";
		}
	}
	if($id > 0){
		$sql = "select * from {$GLOBALS['dbTablePre']}manage_comment where id='$id'";
		$comment = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
	}
	require adminTemplate( "diamond_comment_edit" );
}
//添加表情
function diamond_face_add(){
	if($_POST){
		$upload = MooAutoLoad('MooUpload');
		$upload->config(array(
			'targetDir' => '../data/face/',
			'saveType' => 0,
			'thumbStatus' => 0,
			'waterMarkStatus' => 0,
		));
		$files = $upload->saveFiles('pic_file');
		
		if($files){
			$param['comment'] = $files[0]['path'].$files[0]['name'].'.'.$files[0]['extension'];
			$param['type'] = 2;
			$param['dateline'] = time();
			inserttable('manage_comment',$param);
			echo "<script>alert('添加成功');history.go(-1);</script>";
		}
	}
	require adminTemplate( "diamond_face_edit" );
}

//编辑表情
function diamond_face_edit(){
	$id = MooGetGPC('id','integer','G');
	if(empty($id)){
		$id = MooGetGPC('id','integer','P');
	}
	$sql = "select * from {$GLOBALS['dbTablePre']}manage_comment where id='$id'";
	$face = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
	if($_POST){
		$upload = MooAutoLoad('MooUpload');
		$upload->config(array(
			'targetDir' => '../public/face/',
			'saveType' => 0,
			'thumbStatus' => 0,
			'waterMarkStatus' => 0,
		));
		$files = $upload->saveFiles('pic_file');
		
		if($files){
			$param['comment'] = $files[0]['path'].$files[0]['name'].'.'.$files[0]['extension'];
			$sql = "update {$GLOBALS['dbTablePre']}manage_comment set comment='{$param['comment']}' where id='$id'";
			$GLOBALS['_MooClass']['MooMySQL']->query($sql);
			unlink("{$face['comment']}");
			echo "<script>alert('修改成功');history.go(-1);</script>";
		}
	}
	require adminTemplate( "diamond_face_edit" );
}
//删除表情图片
function diamond_face_del(){
	$id = MooGetGPC('id','integer','G');
	if($id){
		$sql = "select * from {$GLOBALS['dbTablePre']}manage_comment where id='$id'";
		$face = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
		$sql = "delete from {$GLOBALS['dbTablePre']}manage_comment where id='$id'";
		$GLOBALS['_MooClass']['MooMySQL']->query($sql);
		unlink("{$face['comment']}");
	}
	header("Location:".$_SERVER['HTTP_REFERER']);
}
//用户留言和表情
function diamond_comment(){
	$stype = $link = '';
	$type = MooGetGPC('type','integer','G');//{1:品论语 2:表情}
	if($type == 0){$type = 1;}
	$stype = 'WHERE type='.$type;
	$link = '&type='.$type;
	$type_arr = array(0=>array(1=>'品论语',2=>'表情'),1=>array(1=>'comment',2=>'face'));
	$comment_type = array(1=>'视频介绍',2=>'人生经历');
	$comment_sort = array(0=>'通用',1=>'男对女',2=>'女对男',3=>'男对男',4=>'女对女');
	
	$page_now = MooGetGPC('page', 'integer');
	$page_per = 15;
	$page_url = "index.php?action=site_diamond&h=comment".$link;
	$from_where =" FROM {prefix}manage_comment {$stype}";
	$data = readTable( $page_now, $page_per, $page_url, $from_where, 'ORDER BY id DESC', '*' );
	require adminTemplate( "diamond_comment_list" );
}


//皮肤管理
function diamond_skin(){
	$uid = MooGetGPC('uid','integer');
	$d = MooGetGPC('d', 'string','G');
	$diamondInfo = $GLOBALS['_MooClass']['MooMySQL']->getOne("SELECT `uid`,`nickname`,`skin`,`bg_img` FROM {$GLOBALS['dbTablePre']}diamond WHERE `uid`=".$uid,true);

	if( $d == 'bg' ){
		$arr_bg = getFileList('../public/bg_img/');
		if( isset($_POST['bg']) ){
			$bg = $_POST['bg'];
			if( in_array($bg,$arr_bg) ){
				$sql = "UPDATE {$GLOBALS['dbTablePre']}diamond SET bg_img='{$bg}' WHERE uid='{$uid}'";
				if( $GLOBALS['_MooClass']['MooMySQL']->query($sql) ){
					$GLOBALS['arr_user']['bg_img'] = $bg;
					$ps = '保存背景成功!';
				}
			}
		}
	}else{
		$arr_skin = array( 'cyan', 'red' );
		if( isset($_POST['skin'])){
			$skin = $_POST['skin'];
			if( in_array( $skin, $arr_skin ) && $skin != $GLOBALS['arr_user']['skin'] ){
				$sql = "UPDATE {$GLOBALS['dbTablePre']}diamond SET skin='{$skin}', bg_img='{$skin}.jpg' WHERE uid='{$uid}'";
				if( $GLOBALS['_MooClass']['MooMySQL']->query($sql) ){
					$GLOBALS['arr_user']['skin'] = $skin;
					$ps = '保存皮肤成功';
				}
			}
		}
	}
	
	require adminTemplate( 'diamond_skin' );
}
//上传模板
function diamond_template(){
	$uid = MooGetGPC('uid','integer','G');
	$list = array();
	if( isset( $_POST['ispost'] ) ){
		if( preg_match( '/\.zip$/i', $_FILES['zip_file']['name'] ) ){
			if( $_FILES['zip_file']['size'] < 3*1024*1024 ){
				$zip = get_magic_quotes_gpc() ? stripslashes( $_FILES['zip_file']['tmp_name'] ) : $_FILES['zip_file']['tmp_name'];
				$list = unZip( $uid, $zip );
			}
		}
	}
	require adminTemplate( 'diamond_template' );
}

//刷新缓存
function refresh_comment(){
	include "include/function_comment.php";
	$status = comment_list();
	if($status == 'ok'){
		salert("缓存文件生成成功,请返回继续操作！",'index.php?action=site_diamond&h=comment');
	}
}


//更新钻石会员表的,和members_search表同步:昵称,性别,出生年
function diamond_update(){
	$sql = "UPDATE web_diamond d 
	        LEFT JOIN web_members_search m ON d.uid = m.uid
	        left join web_members_grade g on d.uid = g.uid 
			SET d.nickname = m.nickname,d.gender = m.gender,
			    d.birthyear = m.birthyear,d.bgtime=m.bgtime,
			    d.endtime=g.endtime";

	$GLOBALS['_MooClass']['MooMySQL']->query($sql);
	header("location:".$_SERVER['HTTP_REFERER']);
	exit();
}
//首页钻石会员介绍
function diamond_intro(){
	$flag = "";
	$file = '../data/cache/diamond_intro.php';
	if(!is_file($file)){
		touch($file,0666);
	}
	//删除指定的首页钻石会员介绍
	if((isset($_GET['d'])?$_GET['d']:'')=='del'){
		$uid = MooGetGPC('uid','integer','G');
		$userlist = file($file);
		$userlist_temp = str_replace($uid.',','',$userlist[0]);
		$fp = fopen($file,'w');
		fwrite($fp,$userlist_temp);
		fclose($fp);
		echo "<script>alert('修改成功');location.href='index.php?action=site_diamond&h=intro';</script>";exit;
	}
	
	$fp = fopen($file,'r');
	//$userlist = @fread($fp,filesize($file));
	$userlist = file($file);
	$userlist = $userlist[0];
	fclose($fp);
	if($userlist){
		$userlist = trim($userlist,',');
		$user_arr = array_unique(explode(',',$userlist));
		$userlist = implode(',',$user_arr);
	}
	
	if($_POST || !empty($userlist)){
		$uid = MooGetGPC('search_uid','integer','P');
		if($uid) $userlist = $uid;
		$sql = "select * from {$GLOBALS['dbTablePre']}members_search where uid in($userlist) and s_cid=20";
		$userarr = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
	}
	if($_POST){
		$flag = 'hidden';
	}
	require adminTemplate( "diamond_intro" );
}
//设置推荐到钻石会员介绍
function diamond_recommend(){
	$key = 'web_star';
	$uid = MooGetGPC('uid','integer','G');
	$fileClass = MooAutoLoad('MooFiles');
	$file = '../data/cache/diamond_intro.php';

	$fileClass->fileOpen($file,'a+');
	$fileClass->fileWrite($file,$uid.',','a+');
	echo "<script>alert('设置成功');location.href='index.php?action=site_diamond&h=intro';</script>";
}
//编辑发布的约会Ext diamond_*
function dating_edit(){
	$did = MooGetGPC('did','integer','G');
	if($_POST){
		$did = MooGetGPC('did','integer','P');
		$subject = MooGetGPC('subject','string','P');
		$content = MooGetGPC('content','string','P');
		$sql = "UPDATE {$GLOBALS['dbTablePre']}dating SET subject='$subject',content='$content' WHERE did='$did'";
		$GLOBALS['_MooClass']['MooMySQL']->query($sql);
		echo "<script>alert('修改成功');history.go(-1)</script>";
		exit;
	}
	$sql = "SELECT * FROM {$GLOBALS['dbTablePre']}dating WHERE did='$did'";
	$dating = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
	require adminTemplate( "diamond_dating_edit" );
}

//删除发布的约会Ext diamond_*
function dating_del(){
	$did = MooGetGPC('did','integer','G');
	$sql = "delete from {$GLOBALS['dbTablePre']}dating where did='$did'";
	$GLOBALS['_MooClass']['MooMySQL']->query($sql);
	$sql = "delete from {$GLOBALS['dbTablePre']}dating_respond where did='$did'";
	$GLOBALS['_MooClass']['MooMySQL']->query($sql);
	echo "<script>alert('删除成功');history.go(-1)</script>";
}
//约会管理
function diamond_dating(){
	include_once('include/function_dating.php');
	$uid = MooGetGPC('uid','integer','G');
	$did = MooGetGPC('did','integer','G');
	
	$page_now = MooGetGPC('page', 'integer');
	$page_per = 10;
	
	$link = $where = '';
	if(!empty($uid)){
		$where .= " and uid='{$uid}'";
		$link .= "&uid=".$uid;
	}
	if(!empty($did)){
		$where .= " and did='{$did}'";
		$link .= "&did=".$did;
	}
	
	if(isset($_GET['subject'])){
		$subject = trim(MooGetGPC('subject','string','G'));
		$where .= " and subject like '%{$subject}%'";
		$link .= "&subject=".$subject;
	}
	if(isset($_GET['dating_total'])){
		$dating_total = trim(MooGetGPC('dating_total','integer','G'));
		$where .= " and dating_total='{$dating_total}'";
		$link .= "&dating_total=".$dating_total;
	}
	if(isset($_GET['start_time'])){
		$start_time = $_GET['start_time'];
		$where .= " and dateline>='".strtotime($start_time)."'";
		$link .= "&start_time=".$start_time;
	}
	if(isset($_GET['end_time'])){
		$end_time = $_GET['end_time'];
		$where .= " and dateline<='".strtotime($end_time)."'";
		$link .= "&end_time=".$end_time;
	}
	
	if(isset($_GET['flag'])){
		$flag = $_GET['flag'];
		$where .= " and flag='{$flag}'";
		$link .= "&flag=$flag";
	}
	$currenturl = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"]; 
	$page_url = preg_replace("/(&page=\d+)/","",$currenturl);
	//$page_url = 'index.php?n=diamond&h=dating&uid='.$uid;
	
	if(!empty($where)){
		$where = preg_replace("/^\sand\s/",'',$where);
		$where = 'WHERE '.$where;
		//echo $where;exit;
	}
	$from_where = "FROM {prefix}dating {$where} ";
	$data = readTable( $page_now, $page_per, $page_url, $from_where, 'ORDER BY did DESC', '*' );
	
	$comm = $user_list = '';
	foreach($data['list'] as $user){
		$user_list .= $comm.$user['uid'];
		$comm = ',';
	}
	if(!empty($user_list)){
		$sql = "select uid,sid from {$GLOBALS['dbTablePre']}members_search where uid in($user_list)";
		$user_sid = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
		
		foreach($user_sid as $user){
			$user_arr[$user['uid']] = $user['sid']; 
		}
	}
	require adminTemplate( "diamond_dating" );
}

//编辑响应的约会Ext diamond_*
function redating_edit(){
	$rid = MooGetGPC('rid','integer','G');
	if($_POST){
		$rid = MooGetGPC('rid','integer','P');
		$message = MooGetGPC('message','string','P');
		$sql = "update {$GLOBALS['dbTablePre']}dating_respond set message='$message' where rid='$rid'";
		$GLOBALS['_MooClass']['MooMySQL']->query($sql);
		echo "<script>alert('修改成功');history.go(-1)</script>";
		exit;
	}
	$sql = "select * from {$GLOBALS['dbTablePre']}dating_respond where rid='$rid'";
	$dating = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
	require adminTemplate("diamond_redating_edit" );
}

//删除响应的约会
function redating_del(){
	$rid = MooGetGPC('rid','integer','G');
	$sql = "delete from {$GLOBALS['dbTablePre']}dating_respond where rid='$rid'";
	$GLOBALS['_MooClass']['MooMySQL']->query($sql);
	echo "<script>alert('删除成功');history.go(-1)</script>";
}
//约会响应--响应的约会
function diamond_redating(){
	$link='';
	include_once('include/function_dating.php');
	$uid = MooGetGPC('uid','integer','G');
	
	$page_now = max(1,MooGetGPC('page', 'integer'));
	$page_per = 10;
	
	$where = '';
	if(!empty($uid)){
		$where .= " and uid='$uid'";	
		$link .= "&uid=".$uid;
	}
	
	if(isset($_GET['agree'])){
		$agree = MooGetGPC('agree','integer','G');
		$where .= " and agree='$agree'";
		$link .= "&agree=$agree";
	}
	if(isset($_GET['flag'])){
		$flag = MooGetGPC('flag','integer','G');
		$where .= " and flag='$flag'";
		$link .= "&flag=$flag";
	}
	if(!empty($where)){
		$where = preg_replace("/^\sand\s/",'',$where);
		$where = 'WHERE '.$where;
	}
	$page_url = 'index.php?action=site_diamond&h=redating'.$link;
	$from_where = "FROM {prefix}dating_respond {$where}";
	$data = readTable( $page_now, $page_per, $page_url, $from_where, 'ORDER BY rid DESC', '*' );
	
	$comm = $did_list = $user_list = '';
	foreach($data['list'] as $repond){
		$did_list .= $comm.$repond['did'];
		$user_list .= $comm.$repond['uid'];
		$comm = ',';
	}
	if(!empty($did_list) && !empty($user_list)){
		$sql = "select did,subject from {$dbTablePre}dating where did in ($did_list)";
		$dating_list = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
		foreach($dating_list as $subject){
			$subject_arr[$subject['did']] = $subject['subject'];
		}
		$sql = "select uid,sid from {$GLOBALS['dbTablePre']}members_search where uid in($user_list)";
		$user_sid = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
		
		foreach($user_sid as $user){
			$user_arr[$user['uid']] = $user['sid']; 
		}
		
	}
	
	require adminTemplate( "diamond_redating" );
}


/***********************************************控制层(C)*****************************************/
$h=MooGetGPC('h','string')=='' ? 'list' : MooGetGPC('h','string');
//note 动作列表
$hlist = array('list','video_list','video_add','video_edit','video_pic','video_del','words','template','skin','intro','diamond_recommend','dating','dating_edit','dating_del','redating','redating_edit','redating_del',
			   'comment','comment_del','comment_edit','face_list','face_add','face_edit','refresh_comment');
//note 判断页面是否存在
if(!in_array($h,$hlist)){
    salert('您要打开的页面不存在','index.php?action=site_diamond&h=list');
}
//note 判断是否有权限
if(!checkGroup('site_diamond',$h)){
  //salert('您没有修改此操作的权限');
}

switch( $h ){
	//note 钻石会员列表
	case 'list':
		site_diamond_list();
	break;
	case 'diamond_recommend':
		diamond_recommend();
	break;
	//note 视频列表
	case 'video_list':
		site_diamond_video_list();	
	break;
	//note 上传视频
	case 'video_upload':
		site_diamond_video_upload();	
	break;
	//note 上传视频
	case 'video_add':
		site_diamond_video_add();	
	break;
	//note 编辑视频
	case 'video_edit':
		site_diamond_video_edit();	
	break;
	case 'video_del':
		diamond_video_del();
	break;
	case 'video_pic':
		diamond_video_pic();
	break;
	case 'words':
		diamond_words();
	break;
	case 'skin':
		diamond_skin();
	break;
	case 'comment':
		diamond_comment();
	break;
	case 'comment_edit':
		diamond_comment_edit();
	break;
	case 'comment_del':
		diamond_comment_del();
	break;
	case 'refresh_comment':
		refresh_comment();
	break;
	case 'face_edit':
		diamond_face_edit();
	break;
	case 'face_add':
		diamond_face_add();
	break;
	case 'intro':
		diamond_intro();
	break;
	case 'dating':
		diamond_dating();
	break;
	case 'dating_edit':
		dating_edit();
	break;
	case 'dating_del':
		dating_del();
	break;
	case 'redating':
		diamond_redating();
	break;
	case 'redating_edit':
		redating_edit();
	break;
	case 'redating_del':
		redating_del();
	break;
	case 'update':
		diamond_update();
	break;
	case 'template':
	default : 
		diamond_template();
	break;
	
}
?>
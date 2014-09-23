<?php
//*********************************************逻辑层(M)/表现层(V)**********************************************//

//皮肤列表页
function site_skin_list(){
	global $_MooClass,$dbTablePre;
	//分页
	$count = $_MooClass['MooMySQL']->getOne("select count(*) as c from {$dbTablePre}members_skin");
	$page_per = 20;
	$limit = 20;
	$page = max(1,MooGetGPC('page','integer','G'));
	$total = $count['c'];
	$offset = ($page-1)*$limit;
	$myurl = explode('|',getUrl());
	$page_links = multipage($total,$page_per,$page,$myurl[1]);
	$skiname = MooGetGPC('skiname','string','P');
	if($skiname){
		$sql_where = "where skiname='{$skiname}'";
	}else{
		$sql_where = '';
	}
	$sql = "select * from {$dbTablePre}members_skin {$sql_where} limit {$offset},20";
	$site_skin_list = $_MooClass['MooMySQL']->getAll($sql);
	require adminTemplate('site_skin_list');
}


//添加皮肤
function site_skin_add(){
	global $dbTablePre,$_MooClass;
	
	$skiname = MooGetGPC('skiname','string','P');
	$skin_style = MooGetGPC('skin_style','string','P');
	$imgs = !empty($_FILES['imgs'])?$_FILES['imgs']:'';
	$maxfilesize = 1024*1024;
	$time = time();
	$type = array('jpg','jpeg','gif','png','bmp');
	if($_POST){
		if(!$skiname){
			MooMessageAdmin('皮肤名称必填！','index.php?action=site_skin&h=add',0);
			exit;
		}
		//echo $skiname;exit;
		if(!skin_style){
			MooMessageAdmin('皮肤模板名称必填！','index.php?action=site_skin&h=add',0);
			exit;
		}
		
		if(!empty($imgs['size'])){
			foreach($type as $v){
				if(eregi($v,$imgs['type'])){
					$flag = 1;
				}
			}
			
			if($flag != 1){
				$notice = "照片必须为BMP，JPG，PNG或GIF格式";
				MooMessageAdmin($notice, 'index.php?action=site_skin&h=add', 0);
				exit;
			}
				
			if($imgs['size'] >= $maxfilesize){
				$notice = "上传图片大小不得超过1M";
				MooMessageAdmin($notice, 'index.php?action=site_skin&h=add', 0);
				exit;
			}
			
			//note 上传到指定目录，并且获得上传后的文件描述
			$new_path = 'data/upload/images_skin/';
			$file_url = $new_path.$imgs['name'];
			if(file_exists('../'.$file_url)){
				unlink('../'.$file_url);
			}
			if(!move_uploaded_file(str_replace('\\\\','\\',$imgs['tmp_name']),'../data/upload/images_skin/'.$imgs['name'])){
				echo "<script>alert('没有上传成功！');window.history.go(-1);</script>";exit;	
			}
			$sql = "insert into {$dbTablePre}members_skin set skiname='{$skiname}',skin_style='{$skin_style}',image='{$file_url}',date='{$time}'";
			if($_MooClass['MooMySQL']->query($sql)){
				//note 插入日志
				serverlog(1,$GLOBALS['dbTablePre'].'members_skin','添加皮肤成功', $GLOBALS['adminid']);
				salert("添加皮肤成功","index.php?action=site_skin&h=add");	
			}
		}
	}
	require adminTemplate('site_skin_add');	
}


//编辑皮肤
function site_skin_check(){
	global $dbTablePre,$_MooClass;
	$uid = MooGetGPC('uid','string');
	$sql = "select * from {$dbTablePre}members_skin where uid = '{$uid}'";
	$uid_skin = $_MooClass['MooMySQL']->getOne($sql);
	if($_POST){
		$image = MooGetGPC('image','string','P');
		$skiname = MooGetGPC('skiname','string','P');
		$skin_style = MooGetGPC('skin_style','string','P');
		$imgs = $_FILES['imgs'];
		$maxfilesize = 1024*1024;
		$time = time();
		$type = array('jpg','jpeg','gif','png','bmp');
		if(!empty($imgs['size'])){
			foreach($type as $v){
				if(eregi($v,$imgs['type'])){
					$flag = 1;
				}
			}
			
			if($flag != 1){
				$notice = "照片必须为BMP，JPG，PNG或GIF格式";
				MooMessageAdmin($notice, 'index.php?action=site_story&h=check&uid={$uid}', 0);
				exit;
			}
				
			if($imgs['size'] >= $maxfilesize){
				$notice = "上传图片大小不得超过1M";
				MooMessageAdmin($notice, 'index.php?action=site_skin&h=check&uid={$uid}', 0);
				exit;
			}
			//删除之前上传的图片
			unlink('../'.$image);
			//note 上传到指定目录，并且获得上传后的文件描述
			$new_path = 'data/upload/images_skin/';
			$file_url = $new_path.$imgs['name'];
			if(file_exists('../'.$file_url)){
				unlink('../'.$file_url);
			}
			if(!move_uploaded_file(str_replace('\\\\','\\',$imgs['tmp_name']),'../'.$file_url)){
				echo '<script>没有上传成功！;window.history.go(-1);</script>';exit;	
			}
			$sql = "update {$dbTablePre}members_skin set skiname='{$skiname}',skin_style='{$skin_style}',image='{$file_url}',date='{$time}' where uid='{$uid}'";
			if($_MooClass['MooMySQL']->query($sql)){
				//note 插入日志
				serverlog(1,$GLOBALS['dbTablePre'].'members_skin','修改皮肤成功', $GLOBALS['adminid']);
				salert("修改皮肤成功","index.php?action=site_skin&h=check&uid={$uid}");
			}
		}else{
			$sql = "update {$dbTablePre}members_skin set skiname='{$skiname}',skin_style='{$skin_style}',image='{$image}',date='{$time}' where uid='{$uid}'";	
			if($_MooClass['MooMySQL']->query($sql)){
				serverlog(1,$GLOBALS['dbTablePre'].'members_skin','修改皮肤成功', $GLOBALS['adminid']);
				salert("修改皮肤成功","index.php?action=site_skin&h=check&uid={$uid}");	
			}
		}
	}
	require adminTemplate('site_skin_check');	
}


//删除皮肤
function site_skin_del(){
	global $dbTablePre,$_MooClass;
	$uid = MooGetGPC('uid','string','G');
	$del_img = $_MooClass['MooMySQL']->getOne("select * from {$dbTablePre}members_skin where uid='{$uid}'");
	$sql = "delete from {$dbTablePre}members_skin where uid={$uid}";
	if($_MooClass['MooMySQL']->query($sql) && unlink("../".$del_img['image'])){
		//note 插入日志
		serverlog(1,$GLOBALS['dbTablePre'].'members_skin','删除皮肤成功', $GLOBALS['adminid']);
		salert("删除皮肤成功","index.php?action=site_skin&h=list");
	}
}


















































//获取路径
function getUrl(){
	$currenturl = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"]; 
	$currenturl2 = preg_replace("/(&page=\d+)/","",$currenturl);
	$currenturl = preg_replace("/(&orderlogintime=(desc|asc))/","",$currenturl2);
	$currenturl = preg_replace("/(&orderreal_lastvisit=(desc|asc))/","",$currenturl);
	return $currenturl.'|'.$currenturl2;
}


//********************************************控制层(C)*************************************************//
$h = MooGetGPC('h','string','G');
switch($h){
	//皮肤列表
	case 'list':
		site_skin_list();
		break;
	//添加皮肤
	case 'add':
		site_skin_add();
		break;
	//编辑皮肤
	case 'check':
		site_skin_check();
		break;
	//删除皮肤
	case  'del':
		site_skin_del();
		break;
}
?>
<?php
/*  
	Copyright (c) 2009 

	$Id: login.php 399 2009-06-15
	@author:fanglin
*/


/***************************************   逻辑层(M)/表现层(V)   ****************************************/
//note 成功故事列表
function site_story_list(){
	global $story_process;
	$page = max(1,MooGetGPC('page','integer'));
    $limit = 15;
    $offset = ($page-1)*$limit;
    
   	$state = MooGetGPC('state','integer','G') ?  MooGetGPC('state','integer','G') : 1;
	$where = " WHERE `state` = " .$state;
   	
    $sql = "SELECT COUNT(*) num FROM {$GLOBALS['dbTablePre']}story {$where}";
	$total = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
    
	$sql = "SELECT * FROM {$GLOBALS['dbTablePre']}story {$where} ORDER BY `sid` DESC LIMIT {$offset},{$limit}";
	$ret_list = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
   	
   	//最上面一条 可上移
   	if($page != 1){
   		$upable = 1;
   		$preoffset = $offset-1;
   		$sql = "SELECT `sid` FROM {$GLOBALS['dbTablePre']}story {$where} ORDER BY `sid` DESC LIMIT {$preoffset},1";
		$pre_ret = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
   	}
   	//最下面一条 可下移
   	if($page != ceil($total['num']/$limit)){
   		$downable = 1;
   		$nextoffset = $offset+$limit;
   		$sql = "SELECT `sid` FROM {$GLOBALS['dbTablePre']}story {$where} ORDER BY `sid` DESC LIMIT {$nextoffset},1";
		$next_ret = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
   	}
   	$currenturl = "index.php?action=site_story&h=list&state=".$state;
   	$pages = multipage( $total['num'], $limit, $page, $currenturl );
   	
   	//note 插入日志
	serverlog(1,$GLOBALS['dbTablePre'].'story','查看成功故事列表', $GLOBALS['adminid']);

	require(adminTemplate('site_story_list'));
}

//note 添加成功故事
function site_story_add(){
	global $story_sweet_process;
	$ispost = MooGetGPC('ispost','integer','P');
	if($ispost=='1'){
		$title = htmlspecialchars(MooGetGPC('title','string','P'));
		$content = htmlspecialchars(MooGetGPC('content','string','P'));
		$name1 = htmlspecialchars(MooGetGPC('name1','string','P'));
		$name2 = htmlspecialchars(MooGetGPC('name2','string','P'));
		if(empty($title) || empty($content) || empty($name1) || empty($name2)){
			$notice = "标题、内容、恋爱一方、恋爱另一方不能为空！";
			salert($notice,"index.php?action=site_story&h=add");
			exit;
		}
		$state = MooGetGPC('state','integer','P');
		$story_date1 = isset($_POST['confirmtime']) ? MooGetGPC('confirmtime','string','P') : time();
		$story_date = strtotime($story_date1);
		$imgtitle = htmlspecialchars(MooGetGPC('imgtitle','string','P'));
		$recommand = MooGetGPC('recommand','integer','P');
		date_default_timezone_set ('Asia/Shanghai');
		$submit_date = time();
		
		//note 上传图片
		//note 判断上传的文件类型
		$flag = '';
		$images = array('jpg','jpeg','gif','png','bmp');
		$maxfilesize = 1024*1024;
		$filesize = $_FILES['img']['size'];
		if(!empty($filesize)){
			foreach($images as $v) {
				if(strpos($_FILES['img']['type'],$v) == true) {
					$flag = 1;
				}
			}
			if($flag != 1){
				$notice = "照片必须为BMP，JPG，PNG或GIF格式";
				MooMessageAdmin($notice, 'index.php?action=site_story&h=add', 0);
				exit;
			}
			if($filesize>$maxfilesize){
				$notice = "上传图片大小不得超过1M";
				MooMessageAdmin($notice, 'index.php?action=site_story&h=add', 0);
				exit;
			}

			//note 上传到指定目录，并且获得上传后的文件描述	
			global $upload;
			$upload = MooAutoLoad('MooUpload');
			$upload->config(array(
				'targetDir' => IMG_ROOT,
				'saveType' => '0'
			));

			$files = $upload->saveFiles('img');
			$story_picname = $files[0]['name'].".".$files[0]['extension'];
			$filetype = explode('.',$story_picname);
			$filetype = array_pop($filetype);
		}
		
		//note 插入story表
		$sql_insert = '';
		$sql_insert = "INSERT INTO {$GLOBALS['dbTablePre']}story SET title='{$title}',content='{$content}',story_date='{$story_date}',state='{$state}',name1='{$name1}',name2='{$name2}',submit_date='{$submit_date}',recommand='{$recommand}'";
		$res_insert = $GLOBALS['_MooClass']['MooMySQL']->query($sql_insert);
		$sid = $GLOBALS['_MooClass']['MooMySQL']->insertId();

		//$MooImage = MooAutoLoad('MooImage');
		if($story_picname){
			//生成缩略图
			$src = IMG_ROOT.$story_picname;
			$sidmd5=$sid*3;
			$size = getimagesize($src,$info);
			$width = $size['0'];
			$height = $size['1'];
			$sizearray=array(0=>array('width'=>280,'height'=>168),1=>array('width'=>252,'height'=>151),2=>array('width'=>150,'height'=>90));
			$namearray=array(0=>'big',1=>'medium',2=>'small');
			$newimages=changesize($src,'../data/upload/story',0,0,$width,$height,$sid,$sizearray,$namearray);

			$res_in_pic = '';
			//note 插入story_pic表
			$sql_in_spic = "INSERT INTO {$GLOBALS['dbTablePre']}story_pic SET sid='{$sid}',img='{$story_picname}',title='{$imgtitle}',submit_date='{$submit_date}',syscheck='0'";
			$res_in_pic = $GLOBALS['_MooClass']['MooMySQL']->query($sql_in_spic);
			$mid = $GLOBALS['_MooClass']['MooMySQL']->insertId();
			$sql_s = "UPDATE {$GLOBALS['dbTablePre']}story SET is_index='{$mid}' WHERE sid='{$sid}'";
			$res= $GLOBALS['_MooClass']['MooMySQL']->query($sql_s);
		}
		//note 插入日志
		serverlog(1,$GLOBALS['dbTablePre'].'story','添加成功故事成功', $GLOBALS['adminid']);
		salert("添加成功故事成功","index.php?action=site_story&h=add");
	}
	require(adminTemplate('site_story_add'));

}

//note 编辑成功故事
function site_story_edit(){
	global $story_sweet_process, $_MooClass, $dbTablePre,$memcached;
	$ispost = MooGetGPC('ispost','integer','P');
	$imgpost = MooGetGPC('imgpost','integer','P');
	//$MooImage = MooAutoLoad('MooImage');
	if($imgpost == '1'){
		$story_picname = MooGetGPC('imgUrl','string','P');
		$sid = MooGetGPC('sid','integer','P');
		$mid = MooGetGPC('mid','integer','P');
		//重新生成小图 覆盖原来的首页照
		$src = IMG_ROOT.$story_picname;
		$sidmd5=$sid*3;
		$size = getimagesize($src,$info);
		$width = $size['0'];
		$height = $size['1'];
		$sizearray=array(0=>array('width'=>280,'height'=>168),1=>array('width'=>252,'height'=>151),2=>array('width'=>150,'height'=>90));
		$namearray=array(0=>'big',1=>'medium',2=>'small');
		$newimages=changesize($src,'../data/upload/story',0,0,$width,$height,$sid,$sizearray,$namearray);

		$sql = "UPDATE {$GLOBALS['dbTablePre']}story SET is_index='{$mid}' WHERE sid='{$sid}'";
		$res= $GLOBALS['_MooClass']['MooMySQL']->query($sql);
		if($res){
			echo 1;
		}
		exit;
	}
	if($ispost=='1'){
		$sid = trim(MooGetGPC('sid','integer','P'));
		$title = htmlspecialchars(trim(MooGetGPC('title','string','P')));
		$content = htmlspecialchars(trim(MooGetGPC('content','string','P')));
		$confirmtime = htmlspecialchars(MooGetGPC('confirmtime','string','P'));
		$story_date = '';
		if($confirmtime){
			$story_date1 = htmlspecialchars(MooGetGPC('confirmtime','string','P'));
			$story_date = strtotime($story_date1);
		}else{
			$story_date1 = trim(MooGetGPC('date','string','P'));
			$story_date = strtotime($story_date1);
		}
		$name1 = trim(MooGetGPC('name1','string','P'));
		$name1 = htmlspecialchars($name1);
		$name2 = trim(MooGetGPC('name2','string','P'));
		$name2 = htmlspecialchars($name2);
		$state = trim(MooGetGPC('state','integer','P'));
		$syscheck = trim(MooGetGPC('pass','integer','P'));
		$recommand = trim(MooGetGPC('recommand','integer','P'));
		$hot = trim(MooGetGPC('clicknum','integer','P'));
		date_default_timezone_set ('Asia/Shanghai');
		$submit_date = time();
		$imgtitle = trim(MooGetGPC('imgtitle','string','P'));
		$imgtitle = htmlspecialchars($imgtitle);
		//note 上传新图片
		//note 判断上传的文件类型
		$flag = '';
		$images = array('jpg','jpeg','gif','png','bmp');
		$maxfilesize = 1024*1024;
		$filesize = $_FILES['img']['size'];
		if(!empty($filesize)){
			foreach($images as $v) {
				if(strpos($_FILES['img']['type'],$v) == true) {
					$flag = 1;
				}
			}
			if($flag != 1){
				$notice = "照片必须为BMP，JPG，PNG或GIF格式";
				MooMessageAdmin($notice, 'index.php?action=site_story&h=edit&id='.$sid, 0);
				exit;
			}
			if($filesize>$maxfilesize){
				$notice = "上传图片大小不得超过1M";
				MooMessageAdmin($notice, 'index.php?action=site_story&h=edit&id='.$sid, 0);
				exit;

			}

			//note 上传到指定目录，并且获得上传后的文件描述	
			global $upload;
			$upload = MooAutoLoad('MooUpload');
			$upload->config(array(
				'targetDir' => IMG_ROOT,
				'saveType' => '0'
			));
			$files = $upload->saveFiles('img');
			$story_picname = $files[0]['name'].".".$files[0]['extension'];
			$filetype = explode('.',$story_picname);
			$filetype = array_pop($filetype);

			//生成小图
			/*
			$src = IMG_ROOT.$story_picname;
			$sidmd5=$sid*3;
			$size = getimagesize($src,$info);
			$width = $size['0'];
			$height = $size['1'];
			$sizearray=array(0=>array('width'=>280,'height'=>168),1=>array('width'=>252,'height'=>151),2=>array('width'=>150,'height'=>90));
			$namearray=array(0=>'big',1=>'medium',2=>'small');
			$newimages=changesize($src,'../data/upload/story',0,0,$width,$height,$sid,$sizearray,$namearray);
			*/
			$res_in_pic = '';
			if($story_picname){
				//note 插入story_pic表
				$sql_in_spic = "INSERT INTO {$GLOBALS['dbTablePre']}story_pic SET sid='{$sid}',img='{$story_picname}',title='{$imgtitle}',submit_date='{$submit_date}',syscheck='{$syscheck}'";
				$res_in_pic = $GLOBALS['_MooClass']['MooMySQL']->query($sql_in_spic);
			}
		}

		$sql = "UPDATE {$GLOBALS['dbTablePre']}story SET title='{$title}',content='{$content}',story_date='{$story_date}',name1='{$name1}',name2='{$name2}',state='{$state}',syscheck='{$syscheck}',recommand='{$recommand}',hot='{$hot}' WHERE sid='{$sid}' ";
		$sql_up_pic = "UPDATE {$GLOBALS['dbTablePre']}story_pic SET syscheck='{$syscheck}' WHERE sid='{$sid}'";
		$res = $GLOBALS['_MooClass']['MooMySQL']->query($sql);
		$res_up_pic = $GLOBALS['_MooClass']['MooMySQL']->query($sql_up_pic);
		//note 更新缓存
		$param = ("type=query/name=story/sql=select s.sid,s.uid,s.title,s.content,s.name1,s.name2,s.story_date,sp.img from `{$dbTablePre}story` as s left join  `{$dbTablePre}story_pic` as sp on s.is_index=sp.mid where sp.syscheck=1 and s.syscheck=1 and s.recommand= '1' order by s.story_date desc,sp.img desc limit 6/cachetime=86400");
		$cachekey = md5($param);
// 		$file = MOOPHP_DATA_DIR.'/block/story_'.$cachekey.'.data';
		if ($memcached->get('story_'.$cachekey)){
			$memcached->delete('story_'.$cachekey);
		}
		$_MooClass['MooCache']->getBlock($param);
		$story = $GLOBALS['_MooBlock']['story'];

		//note 插入日志
		serverlog(1,$GLOBALS['dbTablePre'].'story','编辑成功故事',$GLOBALS['adminid']);
		salert("编辑成功","index.php?action=site_story&h=edit&id=".$sid);
		exit;
	}

	//note 编辑图片页面
	$sid = MooGetGPC('id','integer','G');
	$sid = intval($sid);
	$sql = "SELECT * FROM {$GLOBALS['dbTablePre']}story WHERE `sid` = '{$sid}'";
	$sql_pic = "SELECT * FROM {$GLOBALS['dbTablePre']}story_pic WHERE `sid` = '{$sid}'";
	$editStory = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	$editPic = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql_pic);
	require(adminTemplate('site_story_edit'));
	
}

//note 删除成功故事
function site_story_del(){
	global $_MooClass, $dbTablePre,$memcached;
	$delpost = MooGetGPC('delpost','integer','P');
	if($delpost == '1'){
		$imgUrl = MooGetGPC('imgUrl','string','P');
		$sid = MooGetGPC('sid','integer','P');
		$sql = "DELETE FROM {$GLOBALS['dbTablePre']}story_pic WHERE img='{$imgUrl}'";
		$res = $GLOBALS['_MooClass']['MooMySQL']->query($sql);
		chmod(IMG_ROOT.$imgUrl, 0777);  
		if(unlink(IMG_ROOT.$imgUrl)){
			echo $sid;
		}
		die;
	}
		$sid = MooGetGPC('id','integer','G');
		$sid = intval($sid);
		$state = MooGetGPC('state','integer','G');
		$state = intval($state);
		$sql1 = "DELETE FROM {$GLOBALS['dbTablePre']}story WHERE `sid` = '{$sid}'";
		$sql2 = "DELETE FROM {$GLOBALS['dbTablePre']}story_pic WHERE `sid` = '{$sid}'";
		//note 获得大图图片信息
		$sql_get = "SELECT * FROM {$GLOBALS['dbTablePre']}story_pic WHERE `sid` = '{$sid}'";
		$res_get = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql_get);
		//note 获得小图图片名
		$story_picname = $res_get[0]['img'];
		$filetype = explode('.',$story_picname);
		$filetype = array_pop($filetype);
		$sidmd5=$sid*3;
		$bigName = $sidmd5.'_'.'big'.'.'.$filetype;			//393_big.jpg
		$mediumName = $sidmd5.'_'.'medium'.'.'.$filetype;	//393_medium.jpg
		$smallName = $sidmd5.'_'.'small'.'.'.$filetype;		//393_small.jpg

		$res1 = $GLOBALS['_MooClass']['MooMySQL']->query($sql1);
		$res2 = $GLOBALS['_MooClass']['MooMySQL']->query($sql2);


		//note 删除大图和小图
		$c = 0;
		foreach($res_get as $k=>$v){
			chmod(IMG_ROOT.$v['img'], 0777);  
			unlink(IMG_ROOT.$v['img']);
			$c++;
		}
		if($c == count($res_get)){
			if(file_exists(TO_IMG_CHANGE_ROOT.$bigName) && file_exists(TO_IMG_CHANGE_ROOT.$mediumName) && file_exists(TO_IMG_CHANGE_ROOT.$smallName)) {
				chmod(TO_IMG_CHANGE_ROOT.$bigName,0777);
				chmod(TO_IMG_CHANGE_ROOT.$mediumName,0777);
				chmod(TO_IMG_CHANGE_ROOT.$smallName,0777);
				unlink(TO_IMG_CHANGE_ROOT.$bigName);
				unlink(TO_IMG_CHANGE_ROOT.$mediumName);
				unlink(TO_IMG_CHANGE_ROOT.$smallName);
			}
			//note 更新缓存
			$param = ("type=query/name=story/sql=select s.sid,s.uid,s.title,s.content,s.name1,s.name2,s.story_date,sp.img from `{$dbTablePre}story` as s left join  `{$dbTablePre}story_pic` as sp on s.is_index=sp.mid where sp.syscheck=1 and s.syscheck=1 and s.recommand= '1' order by s.story_date desc,sp.img desc limit 6/cachetime=86400");
			$cachekey = md5($param);
// 			$file = MOOPHP_DATA_DIR.'/block/story_'.$cachekey.'.data';
			if ($memcached->get('story_'.$cachekey)){
				$memcached->delete('story_'.$cachekey);
			}
			$_MooClass['MooCache']->getBlock($param);
			$story = $GLOBALS['_MooBlock']['story'];
			//note 插入日志
			serverlog(1,$GLOBALS['dbTablePre'].'story','删除成功故事',$GLOBALS['adminid']);
			salert("删除故事成功","index.php?action=site_story&h=list&state=$state");
		}
	
}

//更新成功故事缓存
function site_story_update() {
	global $_MooClass, $dbTablePre, $memcached;
	//更新首页缓存
	$param = ("type=query/name=story/sql=select s.sid,s.uid,s.title,s.content,s.name1,s.name2,s.story_date,sp.img from `{$dbTablePre}story` as s left join  `{$dbTablePre}story_pic` as sp on s.is_index=sp.mid where sp.syscheck=1 and s.syscheck=1 and s.recommand= '1' order by s.story_date desc,sp.img desc limit 0, 6/cachetime=86400");
	$cachekey = md5($param);
// 	$file = MOOPHP_DATA_DIR.'/block/story_'.$cachekey.'.data';
	if ($memcached->get('story_'.$cachekey)){
			$memcached->delete('story_'.$cachekey);
		}
	$_MooClass['MooCache']->getBlock($param);
	//更新推荐列表缓存
	$hot_story = $_MooClass['MooMySQL']->getOne("SELECT * FROM {$dbTablePre}story where syscheck = '1' order by hot desc LIMIT 0,1");
	$sql = "SELECT * FROM {$dbTablePre}story WHERE recommand= '1' and syscheck = '1' and `sid`!='".$hot_story['sid']."' ORDER BY story_date DESC LIMIT 6";
	$key = md5($sql);
	$memcached->delete($key);
	$_MooClass['MooMySQL']->getAll($sql,false,true,3600);
	echo "ok";die;
}

/***************************************   控制层(C)   ****************************************/

$name = MooGetGPC('h','string','G') == '' ? 'index' : MooGetGPC('h','string','G');

//允许的方法
$names = array('list','add','edit','del','sort', 'update');

if(!in_array($name, $names)) {
	MooMessageAdmin('没有这个页面', 'index.php', 0);
}

switch ($h) {
	//note 成功故事列表
	case 'list' :
		site_story_list();
	break;

	//note 添加成功故事
	case 'add' :
		site_story_add();
	break;

	//note 编辑成功故事
	case 'edit' :
		site_story_edit();
	break;

	//note 删除成功故事
	case 'del' :
		site_story_del();
	break;
	
	//note 清除首页成功故事缓存
	case 'update' :
		site_story_update();
	break;
}
?>
<?php

/*************************************** 逻辑层(M)/表现层(V) ****************************************/ 



//note 故事首页
function story_index() {
	global $_MooClass, $dbTablePre;
	  global $user_arr;
	$hot_story = $_MooClass['MooMySQL']->getOne("SELECT * FROM {$dbTablePre}story where syscheck = '1' order by hot desc LIMIT 0,1");
	$index_story = $_MooClass['MooMySQL']->getAll("SELECT * FROM {$dbTablePre}story WHERE recommand= '1' and syscheck = '1' and `sid`!='".$hot_story['sid']."' ORDER BY story_date DESC LIMIT 6",false,true,3600);
	include MooTemplate('public/story_index', 'module');
}

//note 故事列表页
function story_list() {
	global $_MooClass, $dbTablePre,$pagesize;
	  global $user_arr;
	//note -----------------------分页
	//note 每页显示个数
	!$pagesize && $pagesize = 4;
	//note 获得第几页
	$page = empty($_GET['page']) ?  '1' : $_GET['page'];
	//note limit查询开始位置
	$start = ($page - 1) * $pagesize;
	//note 统计相册中的图片的总数
	$query = $_MooClass['MooMySQL']->getOne("select count(1) as num FROM {$dbTablePre}story where syscheck = 1 ");
	$total = $query['num'];
	
	//note 获得当前的url
	$currenturl = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	$currenturl = preg_replace("/(&page=\d+)/","",$currenturl); 
	
	//note 分页查询
	$love_story = $_MooClass['MooMySQL']->getAll("SELECT sid,uid,title,content,story_date,state,name1,name2,submit_date,recommand,hot,is_index FROM {$dbTablePre}story where syscheck = 1 ORDER BY submit_date DESC LIMIT $start,$pagesize",false,false,1000);
//	print_r($love_story);die;
	include MooTemplate('public/story_list', 'module');
}

//note 故事内容页
function story_content() {
	global $_MooClass, $dbTablePre,$userid;
	global $user_arr;
	$sid = MooGetGPC('sid','integer');
	if($sid){
		//增加点击次数
		$_MooClass['MooMySQL']->query("update {$dbTablePre}story set hot=hot+1 where sid=$sid and syscheck = 1");
	}else{
		$sid = 1;
	}
	$love_story = $_MooClass['MooMySQL']->getOne("SELECT * FROM {$dbTablePre}story WHERE sid = '$sid' and syscheck = 1");
	if(!$love_story){
		MooMessage('没有对应故事','index.php?n=story');
		exit;
	}
	$comment = getComment( 2, $sid );
	
	$show_pic = showPic($sid);
	include MooTemplate('public/story_content', 'module');
}

//上传成功故事第一步
function step1(){
	global $_MooClass, $dbTablePre,$userid,$timestamp,$user_arr;
	if($_POST) {
		$name1 = safeFilter(MooGetGPC('name1','string'));
		$name2 = safeFilter(MooGetGPC('name2','string'));
		$state = MooGetGPC('state','integer');
		$year = MooGetGPC('year','integer');
		$month = MooGetGPC('month','integer');
		$day = MooGetGPC('day','integer');
		$storybasic = $_COOKIE['storybasic'];
		if(empty($name1) || empty($name2) || $state=="-1" ){
			MooMessage("请填写完整","index.php?n=story&h=upload");exit;
		}
		
		//note 如果已经提交故事就显示标题和内容，爱情进程，时间
		$love_story = $_MooClass['MooMySQL']->getOne("SELECT * FROM {$dbTablePre}story WHERE uid = '$userid'");
		//note 如果没有选择甜蜜进程就退出
		$story_date = mktime(0,0,0,$month,$day,$year);
		//note 已经写故事了，就更新已有的故事
		//判断是否更新
		$str = $name1.$name2.$state.$story_date;
		$md5str = md5($str);
		if($love_story['sid']) {
			if($md5str != $storybasic) {
				$_MooClass['MooMySQL']->query("UPDATE {$dbTablePre}story SET name1 = '$name1',name2 = '$name2',state = '$state',story_date = '$story_date',submit_date = '$timestamp', syscheck = '0' WHERE sid = '$love_story[sid]'");
			}
			$insert_id = $love_story['sid'];
		}else {				
			$_MooClass['MooMySQL']->query("INSERT INTO {$dbTablePre}story (uid,name1,name2,state,story_date,submit_date) values ('$userid','$name1','$name2','$state','$story_date','$timestamp')");
			$insert_id = $_MooClass['MooMySQL']->insertId();
		}
		
	}
	if(!isset($love_story)){
		$love_story = $_MooClass['MooMySQL']->getOne("SELECT * FROM {$dbTablePre}story WHERE uid = '$userid'");
		$insert_id = $love_story['sid'];
		$md5story = $love_story['title'].$love_story['content'];
		$md5content = md5($md5content);
		setcookie("storycontent", $md5content);
	}
	//note 查询出相册
	$album = $_MooClass['MooMySQL']->getAll("SELECT * FROM {$dbTablePre}story_pic WHERE uid = '$userid'");
	include MooTemplate('public/story_upload2', 'module');
}

//上传成功故事第二步
function step2(){
	global $_MooClass, $dbTablePre,$userid,$timestamp;
	global $user_arr;
	$story2 = MooGetGPC('story2','string');	
	$pics = MooGetGPC('pics','string');
	$insert_id = MooGetGPC('tid','integer');
	
	if($story2 || $pics) {
		$title = safeFilter(MooGetGPC('subject','string'));
		$content = safeFilter(MooGetGPC('content','string'));
		$insert_id = MooGetGPC('tid','integer');
		$img_title = safeFilter(MooGetGPC('imgTitle','string'));
	}
	if($story2 && $insert_id){
		$storycontent = isset( $_COOKIE['storycontent']) ? $_COOKIE['storycontent'] : '';
		$md5content = md5($title.$content);
		if($md5content != $storycontent) {
			$_MooClass['MooMySQL']->query("UPDATE {$dbTablePre}story SET title = '$title',content = '$content',submit_date = '$timestamp', syscheck = '0' WHERE sid = '$insert_id'");
			$lsql = "UPDATE {$dbTablePre}story SET title = '$title',content = '$content', syscheck = '0' WHERE sid = '$insert_id'";
		}
		//提示消息
		
      	MooMessage("您的爱情故事已上传成功，感谢您和我们分享您的幸福。","index.php?n=story",'05');
	
	//note 上传相册中的照片的时候	
	}elseif($pics && $insert_id) {	
		$album = $_MooClass['MooMySQL']->getAll("SELECT count(1) as count FROM {$dbTablePre}story_pic WHERE uid = '$userid'",0,0,0,true);
		//note 如果上传照片超过30个图片，不允许上传
		if(isset($album['count'])&&$album['count'] > '30') {
	     	MooMessage("您上传的图片已满30张！不可以在上传新的图片了","index.php?n=story",'03');
		}
		//note 判断上传的文件类型
		$flag = '';
		$images = array('jpg', 'jpeg', 'gif', 'png', 'bmp');
		$maxfilesize = 1024*1024;
		$filesize = $_FILES['pic']['size'];
		$true_type = file_type($_FILES['pic']['tmp_name']);
		$extname = strtolower(substr($_FILES['pic']['name'],(strrpos($_FILES['pic']['name'],'.')+1)));
		$_FILES['pic']['type'] = strtolower($_FILES['pic']['type']);

		
		if(in_array($true_type, $images) && in_array($extname, $images)){
			foreach($images as $v) {
				if('image/'.$true_type == $_FILES['pic']['type'] 
					|| 'image/p'.$true_type == $_FILES['pic']['type'] 
					|| 'image/x-'.$true_type == $_FILES['pic']['type']) {
						
					//过滤图片信息
		           $file_content = file_get_contents($_FILES['pic']['tmp_name']);
	   			   $low_file_content = strtolower($file_content);
	               $pos = strpos($low_file_content, '<?php');
	               if($pos){
	               	    $notice = "照片中含有不安全信息请重新上传";
				        MooMessage($notice,'index.php?n=story&h=upload');
				        exit();
						
	               }else{
	                 	$flag = 1;
	               }
				}
			}
		}		
		
                
		if($flag != 1) {
			$notice = "照片必须为BMP，JPG，PNG或GIF格式";
			MooMessage($notice,'index.php?n=story&h=step1');
			exit();
		}
		if($filesize>$maxfilesize){
		    $notice = "上传图片大小不得超过1M";
			MooMessage($notice,'index.php?n=story&h=step1');
			exit();
		}
		
		//note 上传到指定目录，并且获得上传后的文件描述	
		$upload = MooAutoLoad('MooUpload');
		$upload->config(array(
			'targetDir' => STORY_IMG_PATH,
			'saveType' => '0'
		));
		$files = $upload->saveFiles('pic');
		$story_picname = $files[0]['name'].".".$files[0]['extension'];
		$_MooClass['MooMySQL']->query("UPDATE {$dbTablePre}story SET title = '$title',content = '$content',submit_date = '$timestamp' WHERE sid = '$insert_id'");
		$album = $_MooClass['MooMySQL']->getAll("SELECT * FROM {$dbTablePre}story_pic WHERE uid = '$userid' order by submit_date desc");
		if($story_picname) {
			$_MooClass['MooMySQL']->query("INSERT INTO {$dbTablePre}story_pic (img,sid,uid,submit_date,title) values ('$story_picname','$insert_id','$userid','$timestamp','$img_title')");
			$mid = $_MooClass['MooMySQL']->insertId();
			$album[] = array(
				'img' => $story_picname,
				'sid' => $insert_id,
				'uid' => $userid,
				'submit_date' => $timestamp,
				'title' => $img_title,
				'mid' => $mid
			);
		}
		
		$prompt = '<script>alert("上传成功，您可以继续上传");location.href="index.php?n=story&h=step2#1"</script>';
        }
        //note 查询出相册
		
		$love_story = $_MooClass['MooMySQL']->getOne("SELECT * FROM {$dbTablePre}story WHERE uid = '$userid'");
		$insert_id = $love_story['sid'];
        include MooTemplate('public/story_upload2', 'module');
		
}
//note 故事上传页
function story_upload() {
	global $_MooClass, $dbTablePre,$userid,$timestamp;
	  global $user_arr;
	//note 获得那几个步骤
	$story1 = MooGetGPC('story1','string');
	$story2 = MooGetGPC('story2','string');	
	$pics = MooGetGPC('pics','string');
	//note 提交第一步的变量赋值
	if($story1) {
		$name1 = safeFilter(MooGetGPC('name1','string'));
		$name2 = safeFilter(MooGetGPC('name2','string'));
		$state = MooGetGPC('state','integer');
		$year = MooGetGPC('year','integer');
		$month = MooGetGPC('month','integer');
		$day = MooGetGPC('day','integer');
	}
	//note 提交第二步变量赋值
	if($story2 || $pics) {
		$title = safeFilter(MooGetGPC('subject','string'));
		$content = safeFilter(MooGetGPC('content','string'));
		$insert_id = MooGetGPC('insertId','integer');
		$img_title = safeFilter(MooGetGPC('imgTitle','string'));
	}
	//note 如果已经提交故事就显示标题和内容，爱情进程，时间
	$love_story = $_MooClass['MooMySQL']->getOne("SELECT * FROM {$dbTablePre}story WHERE uid = '$userid'");
	
	//note 提交故事第一步
	if($story1 && $name1 && $name2 && $state && $year && $month && $day) {
		//note 如果没有选择甜蜜进程就退出
		$storybasic = $_COOKIE['storybasic'];
		$state == '-1' && exit;
		$story_date = mktime(0,0,0,$month,$day,$year);
		//note 已经写故事了，就更新已有的故事
		//判断是否更新
		$str = $name1.$name2.$state.$story_date;
		$md5str = md5($str);
		if($love_story['sid']) {
			if($md5str != $storybasic) {
				$_MooClass['MooMySQL']->query("UPDATE {$dbTablePre}story SET name1 = '$name1',name2 = '$name2',state = '$state',story_date = '$story_date',submit_date = '$timestamp', syscheck = '0' WHERE sid = '$love_story[sid]'");
			}
			$insert_id = $love_story['sid'];
		}else {				
			$_MooClass['MooMySQL']->query("INSERT INTO {$dbTablePre}story (uid,name1,name2,state,story_date,submit_date) values ('$userid','$name1','$name2','$state','$story_date','$timestamp')");
			$insert_id = $_MooClass['MooMySQL']->insertId();
		}
		//note 查询出相册
		$album = $_MooClass['MooMySQL']->getAll("SELECT * FROM {$dbTablePre}story_pic WHERE uid = '$userid'");
		require MooTemplate('public/story_upload2', 'module');
	//note 更新提交故事第二步	
	}else if($story2 && $insert_id){
		$storycontent = isset($_COOKIE['storycontent']) ? $_COOKIE['storycontent'] : '';
		$md5content = md5($title.$content);
		if($md5content != $storycontent) {
			$_MooClass['MooMySQL']->query("UPDATE {$dbTablePre}story SET title = '$title',content = '$content',submit_date = '$timestamp', syscheck = '0' WHERE sid = '$insert_id'");
			$lsql = "UPDATE {$dbTablePre}story SET title = '$title',content = '$content', syscheck = '0' WHERE sid = '$insert_id'";
		}
		//提示消息
		
      	MooMessage("您的爱情故事已上传成功，感谢您和我们分享您的幸福。","index.php?n=story",'05');
	
	//note 上传相册中的照片的时候	
	}else if($pics && $insert_id) {	
		$album = $_MooClass['MooMySQL']->getAll("SELECT count(1) as count FROM {$dbTablePre}story_pic WHERE uid = '$userid'");
		//note 如果上传照片超过30个图片，不允许上传
		if($album['count'] > '30') {
	     	MooMessage("您上传的图片已满30张！不可以在上传新的图片了","index.php?n=story",'03');
		
		}
		//note 判断上传的文件类型
		$flag = '';
		$images = array('jpg', 'jpeg', 'gif', 'png', 'bmp','JPG','JPEG','GIF','PNG','BMP');
		$maxfilesize = 1024*1024;
		$filesize = $_FILES['pic']['size'];
		foreach($images as $v) {
			if(eregi($v,$_FILES['pic']['type'])) {
				  $file_content = file_get_contents($_FILES['pic']['tmp_name']);
   				  $low_file_content = strtolower($file_content);
                  $pos = strpos($low_file_content, '<?php');
                 if($pos){
                 	$notice = "照片中含有不安全信息请重新上传";
			        MooMessage($notice,'index.php?n=story&h=upload');
			        exit();
					
                 }else{
                 	$flag = 1;
                 }
			}
		}
		if($flag != 1) {
			$notice = "照片必须为BMP，JPG，PNG或GIF格式";
			MooMessage($notice,'index.php?n=story&h=upload');
			exit();
		}
		if($filesize > $maxfilesize) {
			$notice = "上传图片大小不得大于1M";
			MooMessage($notice,'index.php?n=story&h=upload');
			exit();
		}
		//note 上传到指定目录，并且获得上传后的文件描述	
		$upload = MooAutoLoad('MooUpload');
		$upload->config(array(
			'targetDir' => STORY_IMG_PATH,
			'saveType' => '0'
		));
		$files = $upload->saveFiles('pic');
		$story_picname = $files[0]['name'].".".$files[0]['extension'];
		$_MooClass['MooMySQL']->query("UPDATE {$dbTablePre}story SET title = '$title',content = '$content',submit_date = '$timestamp' WHERE sid = '$insert_id'");
		if($story_picname) {
			$_MooClass['MooMySQL']->query("INSERT INTO {$dbTablePre}story_pic (img,sid,uid,submit_date,title) values ('$story_picname','$insert_id','$userid','$timestamp','$img_title')");
		}
		//note 查询出相册
		$album = $_MooClass['MooMySQL']->getAll("SELECT * FROM {$dbTablePre}story_pic WHERE uid = '$userid'");
		$love_story = $_MooClass['MooMySQL']->getOne("SELECT * FROM {$dbTablePre}story WHERE uid = '$userid'");
		$prompt = '<script>alert("上传成功，您可以继续上传")</script>';
		include MooTemplate('public/story_upload2', 'module');
    }else {
		//note 故事日期
		$story_date = $love_story['story_date'];
		$show_year = date("Y",$story_date);
		$show_month = date("n",$story_date);
		$show_date = date("j",$story_date);
        
		$md5str = $love_story['name1'].$love_story['name2'].$love_story['state'].$love_story['story_date'];
		$md5basic = md5($md5str);
		setcookie("storybasic", $md5basic);	
		include MooTemplate('public/story_upload1', 'module');
	}
}
//note 删除相册中的相片
function story_img_del() {
	global $_MooClass, $dbTablePre,$userid,$user_arr;
	header("Expires: Mon, 26 Jul 1970 05:00:00  GMT");  
	header("Cache-Control:no-cache, must-revalidate");  
	header("Pragma:no-cache");
	//note 如果已经提交故事就显示标题和内容，爱情进程，时间
	$love_story = $_MooClass['MooMySQL']->getOne("SELECT * FROM {$dbTablePre}story WHERE uid = '$userid'");
	$pid = MooGetGPC('pid','integer');
	
//	$pid && $_MooClass['MooMySQL']->query("DELETE FROM {$dbTablePre}story_pic WHERE mid = '$pid' AND uid='$userid'");
	if(!empty($pid)){
		$_MooClass['MooMySQL']->query("DELETE FROM {$dbTablePre}story_pic WHERE mid = '$pid' AND uid='$userid'");
		echo "1";//成功
	}else{
		echo "0";//失败
	}
//	//note 查询出相册
//	$album = $_MooClass['MooMySQL']->getAll("SELECT * FROM {$dbTablePre}story_pic WHERE uid = '$userid'");
//	$insert_id = $love_story['sid'];
//	$prompt = '<script>alert("删除成功，您可以继续上传");< /script>';
//	include MooTemplate('public/story_upload2', 'module');
}

//note 设置图片为相册中默认首页图片
function story_img_index() {
	global $_MooClass, $dbTablePre,$userid;
	  global $user_arr;
	$pid = MooGetGPC('pid','integer');
	//note 更新被选中的图片id
	$_MooClass['MooMySQL']->query("UPDATE {$dbTablePre}story set is_index = '$pid' WHERE uid = '$userid'");
	$_MooClass['MooMySQL']->query("UPDATE {$dbTablePre}story_pic set syscheck= '0' WHERE mid = '$pid' ");
	//note 如果已经提交故事就显示标题和内容，爱情进程，时间
	$love_story = $_MooClass['MooMySQL']->getOne("SELECT * FROM {$dbTablePre}story WHERE uid = '$userid'");
	$album = $_MooClass['MooMySQL']->getAll("SELECT * FROM {$dbTablePre}story_pic WHERE uid = '$userid' order by submit_date desc");
	$insert_id = $love_story['sid'];
	include MooTemplate('public/story_upload2', 'module');
}

//note 肖像许可说明
function story_announce() {
  global $user_arr;
	include MooTemplate('public/story_announce', 'module');
}

//添加评论
function story_addcomment(){
	if( !empty($_POST['content']) ){
		$id = $_GET['id'];
		addComment( 2, $id);
	}
	header("location:".$_SERVER['HTTP_REFERER']);
}


/***************************************   控制层(C)   ****************************************/

include "./module/{$name}/function.php";

//note 初始化用户信息
$back_url='index.php?'.$_SERVER['QUERY_STRING'];

//note 获得URL参数处理
$h = MooGetGPC('h', 'string');
if(empty($h)) $h = 'index';

if(!$userid){
	if(!in_array($h,array('index','content','list'))){
		header("location:login.html");//&back_url=".urlencode($back_url)
    }
}

switch ($h) {
	//note 故事首页
	case "index":
		story_index();
		break;
	//note 故事列表页	
	case "list":
		story_list();
		break;	
	//note 故事内容页	
	case "content":
		story_content();
		break;
	//note 故事上传页	
	case "upload":
		story_upload();
		break;
	case "step1":
		step1();
		break;
	case "step2":
		step2();
		break;
	//note 删除相册中的相片
	case "del":
		story_img_del();
		break;	
	//note 设置为默认的首页
	case "setindex":
		story_img_index();
		break;
	//note 	肖像许可说明
	case "announce":
		story_announce();
		break;
	//note 添加评论
	case 'addcomment':
		story_addcomment();
		break;
	//note 默认显示首页	
	default:
		story_index();	
}
?>
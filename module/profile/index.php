
<?php
/*
 * Created on 2007-10-13
 *
 * To change the template for this generated file go to*
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
/*************************************** 逻辑层(M)/表现层(V) ****************************************/ 
//include "./module/{$name}/function.php";

/**
 * 功能列表
 */
//note 投诉
function report($uid){
	global $_MooClass,$dbTablePre,$userid,$user_arr,$timestamp;
	
    $serverid = Moo_is_kefu();
    if($serverid && $user_arr['usertype']!=3){
        MooMessage('对不起您不能模拟操作','javascript:history.go(-1);','04');
        exit;
    }
    
	//处理会员提交了数据
	if(MooGetGPC('reportbutton','string','P')){
		$u_id =safeFilter(MooGetGPC('uid','string','P'));
		$ruid = safeFilter(MooGetGPC('ruid','string','P'));
		$forwhy = MooGetGPC('forwhy','integer','P');
		$umail = safeFilter(MooGetGPC('umail','string','P'));
		$content = safeFilter(MooGetGPC('content','string','P'));
		if($forwhy == '0'){
			MooMessage('请选择您的举报原因','javascript:history.go(-1);');
			exit;
		}
		if(rtrim($content) == ''){
			MooMessage('请选择您的举报内容','javascript:history.go(-1);');
			exit;
		}
		//note 上传图片
		if($_FILES['pic']['size'] <= 5242880 && !empty($_FILES['pic']['size'])){
			$extname = strtolower(substr($_FILES['pic']['name'],(strrpos($_FILES['pic']['name'],'.')+1)));
			
			//note 判断上传的文件类型
			$flag = '';
			$images = array('jpg', 'jpeg', 'gif', 'png', 'bmp','JPG','JPEG','GIF','PNG','BMP');
			//foreach($images as $v) {
				//if(preg_match("/$v/",$_FILES['pic']['type'])) {
				$img_type = explode("/",$_FILES['pic']['type']);
				if(in_array($img_type[1], $images) && in_array($extname,$images)){
					$flag = 1;
					
				}
			//}
			if($flag != 1) {
				$notice = "照片必须为BMP，JPG，PNG或GIF格式";
				MooMessage($notice,'javascript:history.go(-1);');
				exit();
			}
			//note 上传到指定目录，并且获得上传后的文件描述	
			$upload = MooAutoLoad('MooUpload');
			$upload->config(array(
				'targetDir' => REPORT_IMG_PATH,
				'saveType' => '0'
			));
			$files = $upload->saveFiles('pic');
			$report_picname = $files[0]['name'].".".$files[0]['extension'];
			//note 提交举报数据
			$_MooClass['MooMySQL']->query("insert into {$dbTablePre}report (uid,ruid,forwhy,umail,content,pic,addtime) values ('$u_id','$ruid','$forwhy','$umail','$content','$report_picname','".time()."')");
		//note 没有上传图片
		}else{
			if($_FILES['pic']['size'] > 5242880){
				MooMessage('上传图片大小不能超过5M','javascript:history.go(-1);');
			}else{
				MooMessage('必须上传图片作为举报证据','javascript:history.go(-1);');	
			}
			//note 提交举报数据
			//$_MooClass['MooMySQL']->query("insert into {$dbTablePre}report (uid,ruid,forwhy,umail,content,addtime) values ('$u_id','$ruid','$forwhy','$umail','$content','".time()."')");
		}
		
		
		//************提醒所属客服**************
        $sid = $user_arr['sid'];
        $title = '您的会员 '.$user_arr['uid'].' 举报了 '.$ruid;
        $awoketime = $timestamp+3600;
        $sql_remark = "insert into {$dbTablePre}admin_remark set sid='{$sid}',title='{$title}',content='{$title}',awoketime='{$awoketime}',dateline='{$timestamp}'";
        $res = $_MooClass['MooMySQL']->query($sql_remark);
        
        
		MooMessage('举报成功','javascript:history.go(-2);');
		exit;
	}
	//note 两天内对同一位会员投诉只限一次
	$reportcount1 = $_MooClass['MooMySQL']->getOne("select id from {$dbTablePre}report where uid='$userid' and ruid='$uid' and addtime>".mktime(0,0,0,date('m',time()-86400),date('d',time()-86400),date('Y',time()-86400)));
	if($reportcount1){
		MooMessage('对不起，您两天内对同一位会员投诉只限一次','javascript:history.go(-1);','02');
		exit;
	}else{
		//note 第天只能投诉5位会员
		$reportcount = $_MooClass['MooMySQL']->getOne("select count(*) from {$dbTablePre}report where uid='$userid' and addtime>".mktime(0,0,0,date('m',time()),date('d',time()),date('Y',time())));
		if($reportcount['count(*)'] >= 5){
			MooMessage('对不起，您每天只能对5位不同的会员进行投诉','javascript:history.go(-1);','02');
			exit;
		}
	}
	//被投诉人不可能是自己
	if($userid == $uid){
		MooMessage('不可以举报自己','javascript:history.go(-1);','03');
	}
	//被投诉人信息
	if(MOOPHP_ALLOW_FASTDB){
		$userinfo = MooFastdbGet('members_search','uid',$uid);
	}else{
		$userinfo = $_MooClass['MooMySQL']->getOne("select * from {$dbTablePre}members_search as s left join {$dbTablePre}members_base as b on s.uid=b.uid  where uid='$uid'");
	}
	$sql = "select count(*) as c from {$dbTablePre}pic where uid='{$uid}' and isimage=0";
	$pic_num = $_MooClass['MooMySQL']->getOne($sql);
	if(!$userinfo){
		MooMessage('无此用户','javascript:history.go(-1);','03');
		exit;
	}
	include MooTemplate('public/profile_index', 'module');
}
/***************************************   控制层(C)   ****************************************/

$back_url='index.php?'.$_SERVER['QUERY_STRING'];
if(!$userid) {header("location:login.html");} //&back_url=".urlencode($back_url)

$h = MooGetGPC('h', 'string');
$h = in_array($h, array('report')) ? $h : 'report';

switch ($h) {
	case "report" :
		$uids = MooGetGPC('uid', 'integer');
		report($uids);
		break;
		
	default :
		$uids = MooGetGPC('uid', 'integer');
		report($uids);
		break;
}
?>
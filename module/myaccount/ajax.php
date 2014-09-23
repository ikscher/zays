<?php
/*************************************** 逻辑层(M)/表现层(V) ****************************************/ 
//note 手机验证码
function telphone_code(){
	global $_MooCookie,$userid,$_MooClass,$dbTablePre;
	if($_MooCookie['sendtimes']) return;
	MooSetCookie( "sendtimes",1 );
	
	$rand=rand(3621,9654);
	MooSetCookie("rand",md5(md5($rand)));
	MooSetCookie("counts",$_MooCookie['counts']+1,3600);
	$phone=MooGetGPC('phone','string','G');
	$content = "会员ID{$userid},您的手机验码：".$rand;
	if(SendMsg($phone,$content,1)){
		$time=time();
		$_MooClass['MooMySQL']->query("INSERT INTO {$dbTablePre}smslog_sys (id,sid,uid,content,sendtime,type) values('','','$userid','$content','$time','验证码')");
	}
}

//note  视频
function video_show(){
	global $_MooClass,$_MooCookie,$dbTablePre,$userid;
	$val = MooGetGPC('val','integer','G');
	$tmp_order = isset($_MooCookie['tmp_order'])?$_MooCookie['tmp_order']:null; 
	if(!empty($tmp_order)){
		$tmporder_sql ="and tmp_order=$tmp_order";
	}else{
		$tmporder_sql=null;
	}
	$user_pic = $_MooClass['MooMySQL']->getOne("SELECT count(*) as c FROM {$dbTablePre}pic WHERE isimage='0' and uid='$userid'");
	$sql="SELECT * FROM {$dbTablePre}tmp WHERE uid='$userid' and staus='0' ".$tmporder_sql." order by tmp_id desc ";
	$rs=$_MooClass['MooMySQL']->getAll($sql);
	$flash_pic=$rs?$rs:"";
    $acc_pic=$_MooClass['MooMySQL']->getOne("select  mainimg from {$dbTablePre}members_base where uid='{$userid}'",true);//取出形象照 改变形象照的操作状态
	$acc_pic = $acc_pic['mainimg'];
	include MooTemplate('public/myaccount_video_ajax', 'module');
}
//生成视频文件名
function video_name(){
	global $userid;
	if($userid){
		echo $userid;
	}else{
		echo 'noaccess';	
	}
}

//录制-->上传视频
function video_upload(){
	global $dbTablePre,$_MooClass,$userid;
	$vals = MooGetGPC('vals','integer','G');
	//echo $vals;exit;
	$url_1 = substr($userid,-1,1);
	$url_2 = substr($userid,-2,1);
	$url_3 = substr($userid,-3,1);
	//note 调用文件操作类库，建立无限级目录
	$mkdirs = MooAutoLoad('MooFiles');
	//录制时候存储视频路径
	$url_s = 'data/userecord_tmp/mov_'.$userid.'.flv';
	if($vals){
		if(file_exists($url_s)){
			$filesize = filesize($url_s);
			if($filesize < 512){
				//unlink($url_s);
				echo 'short';
				exit;
			}
			//最终存储视频路径
			$url = 'data/usermov/'.$url_1.'/'.$url_2.'/'.$url_3;
			if(!file_exists($url)){
				$mkdirs->fileMake_news($url);
			}
			if(copy($url_s,$url.'/mov_'.$userid.'.flv')){
				$toshoot_video_time = strtotime(date('Y-m-d')); //录制视频的时间
				$own_uid = $_MooClass['MooMySQL']->getOne("select uid from {$dbTablePre}certification where uid='{$userid}'");	
				if($own_uid){
					$sql = "update {$dbTablePre}certification set toshoot_video_time='{$toshoot_video_time}',toshoot_video_url='{$url}',toshoot_video_check=1 where uid='{$userid}'";
				}else{
					$sql = "insert into {$dbTablePre}certification(uid,toshoot_video_check,toshoot_video_time,toshoot_video_url) values('{$userid}',1,'{$toshoot_video_time}','{$url}')";	
				}
				$_MooClass['MooMySQL']->query($sql);
				unlink($url_s);
				echo 'ok';
			}else{
				echo 'error';
			}	
		}else{
			echo 'error2';
		}
	}
}

//录制-->上传录音
function voice_upload(){
	global $dbTablePre,$_MooClass,$userid;
	$vals = MooGetGPC('vals','integer','G');
	//echo $vals;exit;
	$url_1 = substr($userid,-1,1);
	$url_2 = substr($userid,-2,1);
	$url_3 = substr($userid,-3,1);
	//note 调用文件操作类库，建立无限级目录
	$mkdirs = MooAutoLoad('MooFiles');
	//录制时候存储路径
	$url_s = 'data/userecord_tmp/vol_'.$userid.'.flv';
	if($vals){
		if(file_exists($url_s)){
			//最终存储路径
			$url = 'data/uservoi/'.$url_1.'/'.$url_2.'/'.$url_3;
			if(!file_exists($url)){
				$mkdirs->fileMake_news($url);
			}
			if(copy($url_s,$url.'/voi_'.$userid.'.flv')){
				$toshoot_voice_time = strtotime(date('Y-m-d')); //录制视频的时间
				$own_uid = $_MooClass['MooMySQL']->getOne("select uid from {$dbTablePre}certification where uid='{$userid}'");	
				if($own_uid){
					$sql = "update {$dbTablePre}certification set toshoot_voice_time='{$toshoot_voice_time}',toshoot_voice_url='{$url}',toshoot_voice_check=1 where uid='{$userid}'";
				}else{
					$sql = "insert into {$dbTablePre}certification(uid,toshoot_voice_check,toshoot_voice_time,toshoot_voice_url) values('{$userid}',1,'{$toshoot_video_time}','{$url}')";	
				}
				$_MooClass['MooMySQL']->query($sql);
				unlink($url_s);
				echo 'ok';
			}else{
				echo 'error';
			}	
		}else{
			echo 'error2';
		}
	}
}

//***************************************控制层(C)*******************************************//
$h = MooGetGPC('h','string','G');
switch($h){
	case 'video_show':
		video_show();
		break;
	case 'video_name':
		video_name();
		break;
	case 'video'://上传视频
		video_upload();
		break;
	case 'voice'://上传录音
		voice_upload();
		break;
	default:
		telphone_code();
	break;
	
}
?>
<?PHP
require_once "function.php";
function reg_checkmack(){
	global $_MooClass,$dbTablePre,$_MooCookie;;
	$mack = MooGetGPC('mack','string');
	$telphonemack = md5($mack);
	$web_rand = $_MooCookie['rand2'];
	$telphonemack == $web_rand?exit('ok'):exit('errors');
	/*	if($telphonemack == $web_rand){
			exit('ok');
		}else{
			exit('errors');
		}*/
}


//检测手机号码是否存在
function reg_telphone(){
	global $userid,$dbTablePre;
	$tel = MooGetGPC('tel','string','G');
	//手机正则表达
	$phone = '/^1[3-58][0-9]{9}$/';
	if(!preg_match($phone,$tel)){
		//exit('3');
		echo '3';
	}
//		$sql = "select * from {$dbTablePre}members where telphone='{$tel}'";
//		$row = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	$filters=array("0"=>array("field"=>"telphone","val"=>array($tel),"exclude" => false));
	$cl = searchApi("members_man members_women");
	$var = $cl->getResult("","","",$filters,"");
	$row = $cl -> getIds();
	//echo $row[uid].'  123  '.$userid;
	if(($row[uid] <> $userid) && $row[uid]){
		echo '1';
	}elseif($row[uid] == $userid){
		echo '2';
	}elseif(!$row[uid]){
		echo '4';
	}
	//exit;
}


//保存皮肤
function reg_save_skin(){
	global $userid,$dbTablePre,$_MooClass;
	$skiname = MooGetGPC('skiname','string','G');
	$sql ="update {$dbTablePre}members_base set skin='{$skiname}' where uid='{$userid}'";
	if($_MooClass['MooMySQL']->query($sql)){
		$skin_arr['skin'] = $skiname;
		if(MooFastdbUpdate('members_base','uid',$userid,$skin_arr)){
			echo 'ok';
		}
	}
}

//设置取消形象照
function setImage(){
    global $_MooClass,$dbTablePre,$userid,$user,$user_arr;
	$action=MoogetGPC('action','string','P');
	if($action == 'u'){//设为形象照
		$purl= MoogetGPC('purl','string','P');
		$pdate = MoogetGPC('pdate','string','P');
		$pname = MoogetGPC('pname','string','P');
		$updatetime = time(); 
		if(file_exists($purl)){
		    $_MooClass['MooMySQL']->query("update {$dbTablePre}members_base mb left join {$dbTablePre}members_search ms ON mb.uid=ms.uid set mb.mainimg='$purl',mb.pic_name='$pname',mb.pic_date='$pdate',ms.images_ischeck='2'  where mb.uid='$userid'");
			if(MOOPHP_ALLOW_FASTDB){
				$photo_arr = array();
				$photo_arr['mainimg'] = $purl;					
				$photo_arr['pic_date'] = $pdate;
				$photo_arr['pic_name'] = $pname;
				$members_search['images_ischeck'] = 2;
				$members_search['updatetime'] = time();
				MooFastdbUpdate('members_base','uid',$userid,$photo_arr);
				MooFastdbUpdate('members_search','uid',$userid,$members_search);
			}
			searchApi("members_man members_women")->updateAttr(array('images_ischeck'),array($userid => array(2)));
		}
	}elseif($action == 'd'){ //删除形象照
	    unlink_mainimg($userid); //形象照缩略图删除(big,small,medium,mid...)
		$updatetime=time();
		if(MOOPHP_ALLOW_FASTDB){
			$photo_arr = array();
			$photo_arr['mainimg'] = '';
			$photo_arr['pic_date'] = '';
			$photo_arr['pic_name'] = '';
			$members_search['images_ischeck'] = 0;
			$members_search['time'] = $updatetime;
			MooFastdbUpdate('members_base','uid',$userid,$photo_arr);
			MooFastdbUpdate('members_search','uid',$userid,$members_search);
		}
		
		$_MooClass['MooMySQL']->query("update {$dbTablePre}members_base mb left join {$dbTablePre}members_search ms ON mb.uid=ms.uid set mb.mainimg='',mb.pic_name='',mb.pic_date='',ms.images_ischeck='0',ms.updatetime = '$updatetime' where mb.uid='$userid'");
		
		searchApi("members_man members_women")->updateAttr(array('images_ischeck'),array($userid => array(0)));
	}  
    //$thumb= ltrim(thumbImgPath(2,$user_arr['pic_date'],$user_arr['pic_name']),'/');
	//exit($thumb);
	/* if(!file_exists($thumb_2)){
		$thumb_2 = MooGetphoto($userid,'mid');
	} */
}

//删除相册
function deletePhoto(){
    global $_MooClass,$dbTablePre,$userid,$user,$user_arr;
    $imgid=MooGetGPC('imgid','integer','P');
	$image = $_MooClass['MooMySQL']->getOne("select * from {$dbTablePre}pic where imgid='$imgid' and uid='$userid'",true);
	//note 相册三张缩略图
	$thumb_1 = ltrim(thumbImgPath('1',$image['pic_date'],$image['pic_name']),'/');
	$thumb_2 = ltrim(thumbImgPath('2',$image['pic_date'],$image['pic_name']),'/');
	$thumb_3 = ltrim(thumbImgPath('3',$image['pic_date'],$image['pic_name']),'/');
	file_exists($thumb_1) && unlink($thumb_1);
	file_exists($thumb_2) && unlink($thumb_2);
	file_exists($thumb_3) && unlink($thumb_3);
	file_exists($image['imgurl']) && unlink($image['imgurl']);
	$image_woter = str_replace('.','_nowater.',$image['imgurl']);
	if(file_exists($image_woter)){
		unlink($image_woter);
	}
	if($user_arr['mainimg'] == $image['imgurl'] 
		&& $user_arr['images_ischeck'] == '2' ){
		$_MooClass['MooMySQL']->query("update `{$dbTablePre}members_base` set `mainimg` = '',`pic_date` = '',`pic_name` = '' where `uid` = '$userid'");
		$_MooClass['MooMySQL']->query("update `{$dbTablePre}members_search` set `images_ischeck` = '0',updatetime = '$updatetime' where `uid` = '$userid'");
		
		if(MOOPHP_ALLOW_FASTDB){
			$photo_arr = array();
			$photo_arr['mainimg'] = '';
			$members_search['images_ischeck'] = '0';
			$photo_arr['pic_date'] = '';
			$photo_arr['pic_name'] = '';
			MooFastdbUpdate('members_base','uid',$userid,$photo_arr);
			MooFastdbUpdate('members_search','uid',$userid,$members_search);
		}
	}

	$_MooClass['MooMySQL']->query("delete from `{$dbTablePre}pic` where `imgid` = '$imgid' and  uid='$userid'");
	$user_pic = $_MooClass['MooMySQL']->getOne("SELECT COUNT(*) AS num FROM {$dbTablePre}pic WHERE syscheck=1 and isimage='0' and uid='$userid'",true);
	$user_pic = (int)$user_pic['num'];
	if($user_pic == 0) $user_pic = 0;
	$sql = "update {$dbTablePre}members_search set pic_num='{$user_pic}'  where uid='$userid'";
	$_MooClass['MooMySQL']->query($sql);
	if(MOOPHP_ALLOW_FASTDB){
		$pic_arrs = array();
		$pic_arrs['pic_num'] = $user_pic;
		MooFastdbUpdate('members_search','uid',$userid,$pic_arrs);	
	}
	searchApi("members_man members_women")->updateAttr(array('images_ischeck','pic_num'),array($memberid=>array(0,$user_pic)));
	//searchApi("members_man members_women")->UpdateAttributes(array($memberid=>array('images_ischeck'=>'0','pic_num'=>$user_pic)));
}

//***************************************控制层(C)************************************************//
//header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
//header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // 过去的时间


$h = MooGetGPC('h','string');

switch ($h){
	case 'checkmack': reg_checkmack();break;
	case 'telphone': reg_telphone();break;
	case 'save_skin': reg_save_skin();break;
	case 'setImage': setImage();break;
	case 'delPhoto': deletePhoto();break;
}
?>
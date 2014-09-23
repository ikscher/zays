<?php
include "module/material/function.php";
include "module/andriod/function.php";


//note 图片上传处理
function material_photo_upload() {
	global $_MooClass,$dbTablePre,$userid,$user,$pic_size_arr,$user_arr,$memcached;
	$and_uuid = isset($_GET['uuid'])? $_GET['uuid'] : '';
	$uid =  $_GET['uid'] = isset($_GET['uid'])?$_GET['uid']:'';
	if($uid){
		$userid = $mem_uid = $memcached->get('uid_'.$uid);
	}
	$checkuuid = check_uuid($and_uuid,$userid);
	if(!$checkuuid){
	    $error = "uuid_error";
	    echo return_data($error,false);exit;	
	}
    
	$user_arr = $user = MooMembersData($userid);
	$user_rank_id=get_userrank($userid);
	if(MOOPHP_ALLOW_FASTDB){
		$usercer = MooFastdbGet('certification','uid',$userid);
	}else{
		$usercer = $_MooClass['MooMySQL']->getOne("SELECT * FROM {$dbTablePre}certification WHERE uid='$userid' LIMIT 1 ",true);
	}
	if($_POST['isupload'] == '1') {
		//note 设定用户id
		$memberid = $userid;
		
	//note 设定用户上传图片大小限制 最小10240字节 = 10k 最大419430字节 = 400K
		$minfilesize = 20480;
		$maxfilesize = 1024000;
		$filesize = $_FILES['userfile']['size'];
		if($filesize > $maxfilesize ) {
			$notice = "请上传小于1000KB的照片。";
            echo return_data($notice);exit;
		}
		if($filesize < $minfilesize) {
			$notice = "请上传大于20KB的照片。";
			echo return_data($notice);exit;
		}
		
		//note 判断文件类型
		$flag = '';
		$true_type = file_type($_FILES['userfile']['tmp_name']);
		$extname = strtolower(substr($_FILES['userfile']['name'],(strrpos($_FILES['userfile']['name'],'.')+1)));
		$images = array('/jpg/', '/jpeg/', '/gif/', '/png/', '/JPG/', '/JPEG/', '/GIF/', '/PNG/');
		if(in_array('/'.$extname.'/',$images)){
			foreach($images as $v) {
				//note http://ask.wangmeng.cn/question/76
				if(preg_match($v,$_FILES['userfile']['type'])
				&& ('image/'.$true_type == $_FILES['userfile']['type'] 
					|| 'image/p'.$true_type == $_FILES['userfile']['type'] 
					|| 'image/x-'.$true_type == $_FILES['userfile']['type']) ) {
						
				  $file_content = file_get_contents($_FILES['userfile']['tmp_name']);
   				  $low_file_content = strtolower($file_content);
                  $pos = strpos($low_file_content, '<?php');
                 if($pos){
                   $notice = "照片中含有不安全信息请重新上传";
                   echo return_data($notice);exit;
                 }else{
                 	$flag = 1;
                 }	
				}
			}
		}
		//echo $true_type,'_',$flag,'_',$_FILES['userfile']['type'];exit;
		if($flag != 1) {
			$notice = "请上传JPEG，JPG，PNG或GIF格式";
			echo return_data($notice);exit;
		}
		
		

		//note 设定该用户最多上传20张图片（形象照除外）
		$maxuploadnum = 20;
		
		//note 查询出来的总数
		$query = $_MooClass['MooMySQL']->getOne("select count(1) as num from `{$dbTablePre}pic` where `uid` = '$memberid' AND `isimage` = '0'",true);
		$total = $query['num'];
		
		//note 还可以上传多少张
		$leave_num = $maxuploadnum - $total;
		if($leave_num <= 0) {
			$notice = '您已经有'.$maxuploadnum.'张照片了，';
			if($user_arr['mainimg']){
				$notice .= '不可以再上传了。';
			}else{
				$notice .= '请在相册中选一张作为形象照。';
			}
			echo return_data($notice);exit;
		}

		//note 设定相册名
		$album = '';
		
		//note 设定全局路径
		$orgin = "orgin"; //note 原图文件夹名
		$thumb_path = PIC_PATH;//图片路径
		$timestat = time();
		$thumb_datedir = date("Y",$timestat)."/".date("m",$timestat)."/".date("d",$timestat);
		//原图路径
		$main_img_path = $thumb_path."/".$thumb_datedir."/".$orgin."/";
		
		//note 调用文件操作类库，建立无限级目录
		$mkdirs = MooAutoLoad('MooFiles');
		!is_dir($main_img_path) && $mkdirs->fileMake($main_img_path,0777);
		
		//note 上传到指定目录(原图)，并且获得上传后的文件描述	
		$upload = MooAutoLoad('MooUpload');
		$upload->config(array(
			'targetDir' => $main_img_path,
			'saveType' => '0'
		));
		$files = $upload->saveFiles('userfile');

		
		
		
		//note 获得图片路径和缩略图的路径
		$imgurl = $files[0]['path'].$files[0]['name'].".".$files[0]['extension'];
		

		if($files[0]['extension'] == 'gif'){
			$output = imagecreatefromgif($imgurl);
			imagegif($output, $imgurl, 100);
			imagedestroy($output);
		}
		$imgurl2 = $files[0]['path'].$files[0]['name']."_nowater.".$files[0]['extension'];
		@copy($imgurl,$imgurl2);//拷贝一张无水印图片（用于形象照）
		$pic_name = $files[0]['name'].".".$files[0]['extension'];
		$pic_name2 = $files[0]['name']."_nowater.".$files[0]['extension'];
		
		$first = new Imagick($imgurl);//写入水印
		$second = new Imagick('public/system/images/logo_original.png');

		 //$second->setImageOpacity (0.4);//设置透明度
		$dw = new ImagickDraw();
		$dw->setGravity(Imagick::GRAVITY_SOUTHEAST);//设置位置
										//$dw->setGravity(Imagick::GRAVITY_SOUTH);//设置位置
		$dw->composite($second->getImageCompose(),0,0,50,0,$second);
		$first->drawImage($dw);
		$first->writeImage($imgurl);
		
		//将第一张图片的大小控制成我们需要的大小
		list($width,$height)=getimagesize($imgurl);
		$d = $width / $height;
		$off_wh = off_WH($width, $height);
		$new_width = $off_wh['width'];
		$new_height = $off_wh['height'];
		unset($off_wh);
		$big_path=$files[0]['path'];
		$big_name=$files[0]['name'].'.'.$files[0]['extension'];

		$image = MooAutoLoad('MooImage');
		$image->config(array('thumbDir'=>$big_path,'thumbStatus'=>'1','saveType'=>'0','thumbName' =>$pic_name,'waterMarkMinWidth' => '41', 'waterMarkMinHeight' => '57', 'waterMarkStatus' => 9));
		$image->thumb($new_width,$new_height, $imgurl);
		$image->waterMark();

		//note 缩略无水印图片
		$image->config(array('thumbDir'=>$big_path,'thumbStatus'=>'1','saveType'=>'0','thumbName' =>$pic_name2,'waterMarkMinWidth' => '41', 'waterMarkMinHeight' => '57', 'waterMarkStatus' => 9));
		$image->thumb($new_width,$new_height, $imgurl2);
		$image->waterMark();

		$thumb1_width  = $pic_size_arr["1"]["width"];
		$thumb1_height = $pic_size_arr["1"]["height"];
		$thumb2_width  = $pic_size_arr["2"]["width"];
		$thumb2_height = $pic_size_arr["2"]["height"];
		$thumb3_width  = $pic_size_arr["3"]["width"];
		$thumb3_height = $pic_size_arr["3"]["height"];
		
		//note 生成日期目录
		$thumb1_path = $thumb_path."/".$thumb_datedir."/".$thumb1_width."_".$thumb1_height."/";
		$thumb2_path = $thumb_path."/".$thumb_datedir."/".$thumb2_width."_".$thumb2_height."/";
		$thumb3_path = $thumb_path."/".$thumb_datedir."/".$thumb3_width."_".$thumb3_height."/";
	
		!is_dir($thumb1_path) && $mkdirs->fileMake($thumb1_path,0777);
		!is_dir($thumb2_path) && $mkdirs->fileMake($thumb2_path,0777);
		!is_dir($thumb3_path) && $mkdirs->fileMake($thumb3_path,0777);
		
		//note 缩略图文件名
		$thumb_filename = md5($files[0]['name']).".".$files[0]['extension'];
		$c=41/57;
		if($d>$c){
		$thumb1_width=41;
		$b=$width/$thumb1_width;
		  $thumb1_height=$height/$b;
		}
		else{
		 $thumb1_height=57;
		 $b=$height/$thumb1_height;
		 $thumb1_width=$width/$b;
		}
		$image->config(array('thumbDir'=>$thumb1_path,'thumbStatus'=>'1','saveType'=>'0','thumbName' => $thumb_filename,'waterMarkMinWidth' => '41', 'waterMarkMinHeight' => '57', 'waterMarkStatus' => 9));
		$image->thumb($thumb1_width,$thumb1_height, $imgurl);
		$image->waterMark();

		$c=139/189;
		if($d>$c){
		$thumb2_width = 139;
		$b=$width/$thumb2_width;
		  $thumb2_height=$height/$b;
		}
		else{
		 $thumb2_height = 189;
		 $b=$height/$thumb2_height;
		 $thumb2_width=$width/$b;
		}
		$image->config(array('thumbDir'=>$thumb2_path,'thumbStatus'=>'1','saveType'=>'0','thumbName' => $thumb_filename,'waterMarkMinWidth' => '139', 'waterMarkMinHeight' => '189', 'waterMarkStatus' => 9));
		$image->thumb($thumb2_width,$thumb2_height, $imgurl);
		$image->waterMark();

		$c=171/244;
		if($d>$c){
		$thumb3_width = 171;
		$b=$width/$thumb3_width;
		  $thumb3_height=$height/$b;
		}
		else{
		$thumb3_height = 244;
		 $b=$height/$thumb3_height;
		 $thumb3_width=$width/$b;
		}
		$image->config(array('thumbDir'=>$thumb3_path,'thumbStatus'=>'1','saveType'=>'0','thumbName' => $thumb_filename,'waterMarkMinWidth' => '171', 'waterMarkMinHeight' => '244', 'waterMarkStatus' => 9));
		$image->thumb($thumb3_width,$thumb3_height, $imgurl);
		$image->waterMark();

		//note 设定是否是形象照 1为形象照,0为普通照
		$isimage = (int)$_POST['isimage'];
        $updatetime = time();
		if($isimage === 1) {
			$_MooClass['MooMySQL']->query("update `{$dbTablePre}members_base` set `mainimg`='$imgurl',`pic_date` = '$thumb_datedir',`pic_name` = '$pic_name' where `uid` = '$memberid'");
			$_MooClass['MooMySQL']->query("update `{$dbTablePre}members_search` set images_ischeck='2'  where `uid` = '$memberid'");
			if(MOOPHP_ALLOW_FASTDB){
				$image_arr = array();
				$image_arr['mainimg'] = $imgurl;
				$image_arr['pic_date'] = $thumb_datedir;
				$image_arr['pic_name'] = $pic_name;
				$members_search['images_ischeck'] = '2';
				MooFastdbUpdate('members_base','uid',$memberid,$image_arr);
				MooFastdbUpdate('members_search','uid',$memberid,$members_search);
			}
			searchApi("members_man members_women")->updateAttr(array('images_ischeck'),array($memberid=>array(0)));
			UpdateMembersSNS($userid,'修改了形象照');
		}	
		//note 写入相册表
		$isimage = 0;//web_members.mainimg == web_pic.imgurl 即形象照
		$_MooClass['MooMySQL']->query("insert into `{$dbTablePre}pic` set `uid` = '$memberid',`isimage` = '$isimage',`album` = '$album',`imgurl` = '$imgurl',`pic_date` = '$thumb_datedir',`pic_name` = '$pic_name'");
		//提交会员动态makui
		UpdateMembersSNS($userid,'修改了相册');
		
	        $notice = "成功";
	        echo return_data($notice);exit;
	}else {
		$user1 = $user_arr;
	}
}


/*
 * ****************************************************控制层****************************/

$m = $_GET['m'];

switch ($m){
	case 'upload':
		material_photo_upload();
		break;
			
}

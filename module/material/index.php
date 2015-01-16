<?php
/*
 * Created on 2009-8-14
 *
 * To change the template for this generated file go to*
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
/*************************************** 逻辑层(M)/表现层(V) ****************************************/ 
include "module/{$name}/function.php";

/**
 * 功能列表
 */
//note 我的资料引导首页
function material_index() {
	global $_MooClass,$dbTablePre,$user,$userid,$user_arr;
	$actio=MoogetGPC('actio','string','G');
	 if($actio == 'u'){
	   $staus = MoogetGPC('staus','integer','G'); 
	   $index_mend = $user_arr;
	   if($staus == 1){
	   		$_MooClass['MooMySQL']->query("update {$dbTablePre}members_base set is_phone='1' where uid='$userid'");
			
			if(MOOPHP_ALLOW_FASTDB){
				$value['is_phone'] = 1;
				MooFastdbUpdate('members_base','uid',$userid,$value);
			}
			MooMessage('您开启短信提醒成功','index.php?n=material');
	   }elseif($staus == 0){
	   		$_MooClass['MooMySQL']->query("update {$dbTablePre}members_base set is_phone='0' where uid='$userid'");
			
			if(MOOPHP_ALLOW_FASTDB){
				$value['is_phone'] = 0;
				MooFastdbUpdate('members_base','uid',$userid,$value);
			}
			MooMessage('您关闭短信提醒成功','index.php?n=material');
	   }
	}
	
	
	//note 验证信息本次改版暂且关闭
	//if(MOOPHP_ALLOW_FASTDB){
//		$usercer = MooFastdbGet('certification','uid',$userid);
//	}else{
//		$usercer = $_MooClass['MooMySQL']->getOne("SELECT * FROM {$dbTablePre}certification WHERE uid='$userid' LIMIT 1 ");
//	}
	//note 本次改版暂且关闭
	//$user_rank_id=get_userrank($userid);
	include MooTemplate('public/material_index', 'module');	
}


//note 修改个人资料--显示
function material_upinfo_view() {
	global $_MooClass,$dbTablePre,$user,$userid,$user_arr;
   
	checkAuthMod('index.php?n=material');//客服模拟登录操作没有修改权限
	
	$user_rank_id=get_userrank($userid);
	if(MOOPHP_ALLOW_FASTDB){
		$usercer = MooFastdbGet('certification','uid',$userid);
	}else{
		$usercer = $_MooClass['MooMySQL']->getOne("SELECT * FROM {$dbTablePre}certification WHERE uid='$userid' LIMIT 1 ",true);
	}
	$uid = $userid;
	if(MOOPHP_ALLOW_FASTDB){
		$userinfo = MooFastdbGet('members_search','uid',$uid);
		$u = MooFastdbGet('members_base','uid',$uid);
		if(empty($u)){$u=array();}
		$userinfo = array_merge($userinfo,$u);
	}else{
		$userinfo = $_MooClass['MooMySQL']->getOne("SELECT * FROM {$dbTablePre}members_search m,{$dbTablePre}members_base mf WHERE m.uid=mf.uid AND m.uid = '$uid'",true);
	}
	$date=$userinfo['birth'];
	$date = explode('-', $date);
	if(sizeof($date)==3){
	  $userinfo['birthmonth']=$date['1'];
	  $userinfo['birthday']=$date['2'];
	}
    
	//期望交友地区
	$friendprovince = unserialize($userinfo['friendprovince']);
	
	if(MOOPHP_ALLOW_FASTDB){
		$userchoice = MooFastdbGet('members_choice','uid',$uid);
		$introduce = MooFastdbGet('members_introduce', 'uid', $uid);
		
		$userchoice = array_merge($userchoice,$introduce);
		
	}else{
		$userchoice = $_MooClass['MooMySQL']->getOne("SELECT * FROM {$dbTablePre}members_choice mc left join {$dbTablePre}members_introduce mi WHERE mc.uid=mi.uid AND uid = '$uid'",true);
	}
   $actio=MoogetGPC('actio','integer','R');
  
   switch($actio){
	 case 1: 
	    include MooTemplate('public/material_updateinfo_view1', 'module');
	 break;  
	 case 2:
	    include MooTemplate('public/material_updateinfo_view2', 'module');
	 break;
	 case 3:
	    include MooTemplate('public/material_updateinfo_view3', 'module');
	 break;
	 default:
	   include MooTemplate('public/material_updateinfo_view', 'module');
     break;
   }

}

/*检查昵称长度*/
function str_length($str){
	//echo $str;exit('111');
	if(!empty($str)){
			$number = preg_match("/\d{6,}/","$str");
			$str1 = iconv('utf-8','gbk',$str); 
			//$num = is_chinese($str);
	
			$str_num = strlen($str1);
			if($str_num > 12){
					MooMessage ( "昵称不能超过12个字符,中文昵称不能超过六个汉字", "index.php?n=material&h=upinfo" );
					
				}
		    if($number > 0){//包含六个数字的昵称
				MooMessage ( "昵称中不能有连续的六个数字", "index.php?n=material&h=upinfo" );
			}
	}
		
			
}



//note 修改个人资料--提交
function material_upinfo_submit() {
	global $_MooClass,$dbTablePre,$userid,$user_arr;
	
    checkAuthMod('index.php?n=material');//客服模拟登录操作没有修改权限
	
	$validation = MooAutoLoad('MooValidation');

	$uid = $userid;
	$user_rank_id=get_userrank($userid);
	//var_dump($user_rank_id);
	//$update1_arr = $update2_arr = $update3_arr = array();
	//note members表字段
	$members_search=array();
	$members_base=array();
	$members_choice=array();
	$members_introduce=array();
	$birthyear = MooGetGPC('year','string','P');
	$members_search['birthyear'] = $birthyear;
	$birthmonth = MooGetGPC('month','string','P');
	$birthday = MooGetGPC('days','string','P');
	$members_search['updatetime'] = time();
	$members_search['nickname'] = safeFilter(MoogetGPC('nickname','string','P'));
	$members_search['telphone'] = MoogetGPC('telphone','string','P');
	$members_search['marriage'] = MoogetGPC('marriage1','integer','P');
	$members_search['height'] = MoogetGPC('height','integer','P');
	$members_search['salary'] = MoogetGPC('salary','integer','P');
	$members_search['education'] = MoogetGPC('education1','integer','P');
	$members_search['children'] = MoogetGPC('children1','integer','P');
	$members_search['house'] = MoogetGPC('house','integer','P');
	$members_base['oldsex'] = MoogetGPC('oldsex','integer','P');
	$members_search['province'] = MoogetGPC('province','integer','P');
	$members_search['city'] = MoogetGPC('city','integer','P');
	if( in_array( $members_search['province'],array( 10101201, 10101002)) )
	{
		//note 修正广东省深圳和广州的区域查询 2010-09-04
		$members_search['city'] = $members_search['province'];
		$members_search['province'] = 10101000;
	}
	
	//note choice表字段
	$gender = $_MooClass['MooMySQL']->getOne("select gender from {$dbTablePre}members_search WHERE uid='$uid'",true);
    if($gender['gender']==0){
    	$members_choice['gender']=1;
    }else{
    	$members_choice['gender']=0;
    }
	//$update2_arr['sex'] = MoogetGpc('sex','integer','p');
	$members_choice['age1'] = MoogetGPC('age1','integer','P');
	$members_choice['age2'] = MoogetGPC('age2','integer','P');
	$members_choice['workprovince'] = MoogetGPC('workProvince','integer','P');
	$members_choice['workcity'] = MoogetGPC('workCity','integer','P');

	if( in_array( $members_choice['workprovince'],array( 10101201, 10101002)) )
	{
		//note 修正广东省深圳和广州的区域查询 2010-09-04
		$members_choice['workcity'] = $members_choice['workprovince'];
		$members_choice['workprovince'] = 10101000;
	}

	$members_choice['marriage'] = MoogetGPC('marriage2','integer','P');
	$members_choice['education'] = MoogetGPC('education2','integer','P');
	$members_choice['children'] = MoogetGPC('children2','integer','P');
	
	$members_choice['salary'] = MoogetGPC('salary1','integer','P');
	$members_choice['height1'] = MoogetGPC('height1','integer','P');
	$members_choice['height2'] = MoogetGPC('height2','integer','P');
	$members_choice['hasphoto'] = MoogetGPC('hasphoto','integer','P');
	$members_choice['nature'] = MoogetGPC('nature2','integer','P');
	$members_choice['body'] = MoogetGPC('body2','integer','P');
	$members_choice['weight1'] = MoogetGPC('weight1','integer','P');
	$members_choice['weight2'] = MoogetGPC('weight2','integer','P');
	$members_choice['occupation'] = MoogetGPC('occupation2','integer','P');
	$members_choice['nation'] = MoogetGPC('stock2','integer','P');
	$members_choice['hometownprovince'] = MoogetGPC('hometownProvince2','integer','P');
	$members_choice['hometowncity'] = MoogetGPC('hometownCity2','integer','P');

	if( in_array( $members_choice['hometownprovince'],array( 10101201, 10101002)) )
	{
		//note 修正广东省深圳和广州的区域查询 2010-09-04
		$members_choice['hometowncity'] =$members_choice['hometownProvince'];
		$members_choice['hometownprovince'] = 10101000;
	}

	$members_choice['wantchildren'] = MoogetGPC('wantchildren2','integer','P');
	$members_choice['smoking'] = MoogetGPC('issmoking','integer','P');
	$members_choice['drinking'] = MoogetGPC('isdrinking','integer','P');
	$members_introduce['introduce_check'] =safeFilter(trim(MoogetGPC('introduce','string','P')));
	
	
	$rs = $user_arr;
	
	//note 验证状态
	if(MOOPHP_ALLOW_FASTDB){
		$sta = MooFastdbGet('certification','uid',$uid);
	}else{
		$sta = $_MooClass['MooMySQL']->getOne("select telphone from {$dbTablePre}certification WHERE uid='$uid'",true);
	}
	$where_arr = array('uid'=>$uid);
	foreach($members_search as $key=>$val){
		//无手机号吗
		if($key == 'telphone' && $val == ''){		
			continue;
		}
		$memberssearch[$key]=$val;		
	}
	
//	foreach ($members_base as $key=>$val){
//		$membersbase[$key]=$val;
//	}

	if(count($members_base)>=1 || count($memberssearch)>=1){
		$members_search['updatetime'] = time();
		if(!rtrim($members_search['nickname'])){
			MooMessage('昵称必填','javascript:history.go(-1)');
		}
		if(preg_match('/^(1[345]\d{9})|(18[024-9]\d{8})|(010-?\d{8})|(02)[012345789]-?\d{8}|(0[3-9]\d{2,2}-?\d{7,8})|(.*@.*)$/',$members_search['nickname'])){
			MooMessage("昵称不符合规范！", "javascript:history.go(-1)");
		}
		//echo 'sss';exit;
		str_length($members_search['nickname']);
		//note 昵称截取
		$members_search['nickname'] = MooCutstr($members_search['nickname'], 18, $dot = '');
		
		if($members_search['telphone'] && !preg_match('/^((1[345]\d{9})|(18[0-9]\d{8}))$/',$members_search['telphone'])){
			MooMessage('请输入正确的手机号码','javascript:history.go(-1)');
		}
		//$birth=strtotime("$birthyear/$birthmonth/$birthday");
		$birth = "$birthyear-$birthmonth-$birthday";
		$members_base['birth']=$birth;
		updatetable('members_base',$members_base,$where_arr); 
		updatetable('members_search',$memberssearch,$where_arr);
		
		if(MOOPHP_ALLOW_FASTDB){			
			MooFastdbUpdate('members_base','uid',$uid,$members_base);
			MooFastdbUpdate('members_search','uid',$uid,$memberssearch);
		}
		
		//searchApi("members_man members_women")->UpdateAttributes(array($uid=>$members_search));
	
	}
		//提交会员动态makui
		UpdateMembersSNS($uid,'修改了资料');
		//内心独白必填
		//if(rtrim($update2_arr['introduce_check'] != '')){
			$members_introduce['introduce'] = '';
			$members_introduce['introduce_pass'] = '2';
			//if(isset($members_choice)){
				$members_choice['updatetime'] = time();
			    updatetable('members_choice',$members_choice,$where_arr);
			//}
			updatetable('members_introduce',$members_introduce,$where_arr);
			
			if(MOOPHP_ALLOW_FASTDB){
				$members_choice['uid'] = $uid;
				$members_introduce['uid'] = $uid;
				//print_r($update2_arr);exit;
				if(isset($members_choice)){
					$members_choice['updatetime']=time();
				    MooFastdbUpdate('members_choice','uid',$uid,$members_choice);
				}
				MooFastdbUpdate('members_introduce','uid',$uid,$members_introduce);
			}
			//searchApi("members_man members_women")->UpdateAttributes(array($uid=>$members_choice));
			if(MOOPHP_ALLOW_FASTDB){
				$userchoice = MooFastdbGet('members_choice','uid',$uid);
				$introduce = MooFastdbGet('members_introduce', 'uid', $uid);
				$userchoice = array_merge($userchoice,$introduce);
			}else{
				$userchoice = $_MooClass['MooMySQL']->getOne("SELECT * FROM {$dbTablePre}members_choice mc left join {$dbTablePre}members_introduce mi WHERE mc.uid=mi.uid AND uid = '$uid'",true);
			}
		    
		//}else{
		//	MooMessage("内心独白必填！", "index.php?n=material&h=upinfo");
		//}
		/*
		//低质量会员自动分配
		if($user_rank_id == 0){
			//以下信息都没选，都规为垃圾会员,自动分配给普通客服
			if($update1_arr['height']=='-1' || $update1_arr['salary']=='-1' || $update1_arr['children']=='-1' || $update1_arr['oldsex']=='-1' || $update2_arr['age1']=='-1' || $update2_arr['age2']=='-1' || $update2_arr['marriage'] == '-1' || $update2_arr['children'] == '-1' || $update2_arr['body'] == '-1'){
				invalid_user_allotserver($uid);
			}
		}
		*/
        $actio=MoogetGPC('actio','integer','R');
        switch($actio){
		    case 1:
					if($rs['telphone'] == $members_search['telphone'] || $sta['telphone'] == '' || $members_search['telphone'] == ''){
		      			header("location:index.php?n=material&h=upinfo&actio=1");
			 		}
					else {//重新手机认证
						$sql="update {$dbTablePre}certification  set telphone='' where uid='$uid'";
						$_MooClass['MooMySQL']->query($sql);
						$certif_arr['telphone'] = '';
						MooFastdbUpdate('certification','uid',$uid,$certif_arr);

						if(MOOPHP_ALLOW_FASTDB){
							if(MOOPHP_ALLOW_FASTDB){
								$certification_1 = MooFastdbGet('certification','uid',$userid);
							}else{
								$certification_1 = $_MooClass['MooMySQL']->getOne("SELECT * FROM {$dbTablePre}certification  WHERE uid = '$userid'",true);
							}
							
						}
						MooMessage("您的手机信息有变动请再次通过我们的验证",'index.php?n=myaccount&h=telphone');     
					}
				break;
            default:
          	   header("location:index.php?n=material&h=upinfo");
            break; 
	 } 
	
	 //note 快速常用搜索表更新
	//fastsearch_update($userid,'1');
		
	//note 快速高级搜索表更新
	//fastsearch_update($userid,'2');
	
}


//note 修改个人详细资料--显示
function material_upmoinfo_view() {
	global $_MooClass,$dbTablePre,$userid,$user_arr;
	//MooUserInfo();
	$user_rank_id=get_userrank($userid);
	/*$userinfo = $_MooClass['MooMySQL']->getOne("SELECT * FROM {$dbTablePre}members m {$dbTablePre} mf WHERE m.uid = mf.uid AND 
	m.uid = '$user[uid]'   ");*/
	
	//require MooTemplate("material_updatemoinfo_view");
}
//note 修改个人详细资料--提交


//note 图片上传处理
function material_photo_upload() {
    //checkAuthMod('index.php?n=material');//客服模拟登录操作没有修改权限
	
	global $_MooClass,$dbTablePre,$userid,$user,$pic_size_arr,$user_arr;
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
			MooMessage($notice,'index.php?n=material&h=show');
			exit();
		}
		if($filesize < $minfilesize) {
			$notice = "请上传大于20KB的照片。";
			MooMessage($notice,'index.php?n=material&h=show');
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
			       MooMessage($notice,'index.php?n=material&h=show');
			        exit();
					
                 }else{
                 	$flag = 1;
                 }	
				}
			}
		}
		//echo $true_type,'_',$flag,'_',$_FILES['userfile']['type'];exit;
		if($flag != 1) {
			$notice = "请上传JPEG，JPG，PNG或GIF格式";
			MooMessage($notice,'index.php?n=material&h=show');
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
			MooMessage($notice,'index.php?n=material&h=show');
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
			imagegif($output, $imgurl);
			imagedestroy($output);
		}
		$imgurl2 = $files[0]['path'].$files[0]['name']."_nowater.".$files[0]['extension'];
		@copy($imgurl,$imgurl2);//拷贝一张无水印图片（用于形象照）
		$pic_name = $files[0]['name'].".".$files[0]['extension'];
		$pic_name2 = $files[0]['name']."_nowater.".$files[0]['extension'];
		
		//$imgurl=$_SERVER['DOCUMENT_ROOT'] .'/'.$imgurl;
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
			//searchApi("members_man members_women")->UpdateAttributes(array($memberid=>array('images_ischeck'=>'0')));
			UpdateMembersSNS($userid,'修改了形象照');
		}
       
		
		
		//note 写入相册表
		$isimage = 0;//web_members.mainimg == web_pic.imgurl 即形象照
		$_MooClass['MooMySQL']->query("insert into `{$dbTablePre}pic` set `uid` = '$memberid',`isimage` = '$isimage',`album` = '$album',`imgurl` = '$imgurl',`pic_date` = '$thumb_datedir',`pic_name` = '$pic_name'");
		//提交会员动态makui
		UpdateMembersSNS($userid,'修改了相册');

		
	}
	header("Location:index.php?n=material&h=show");
}

//note 上传音乐
function material_music(){
    global $_MooClass,$dbTablePre,$userid,$user,$user_arr,$timestamp;    
    
	$user_rank_id=get_userrank($userid);
	if(MOOPHP_ALLOW_FASTDB){
		$usercer = MooFastdbGet('certification','uid',$userid);
	}else{
		$usercer = $_MooClass['MooMySQL']->getOne("SELECT * FROM {$dbTablePre}certification WHERE uid='$userid' LIMIT 1 ",true);
	}
	$uid = $userid;

	$isupload=MooGetGPC('isupload','int','P');
	if($isupload == '1') {
	    if(!in_array($user_arr['s_cid'],array(10,20,30))){
			MooMessage('必须是VIP会员才能上传背景音乐！','upgrade.html');
		}

		$minfilesize = 1024000;
		$maxfilesize = 8192000;
		$filesize = $_FILES['userfile']['size'];
		if($filesize > $maxfilesize ) {
			$notice = "请上传小于8MB的音乐。";
			MooMessage($notice,'index.php?n=material&h=music');
		}
		if($filesize < $minfilesize) {
			$notice = "请上传大于1MB的音乐。";
			MooMessage($notice,'index.php?n=material&h=music');
		}
		
		//note 判断文件类型
		$flag = '';
		
		$extname = strtolower(substr($_FILES['userfile']['name'],(strrpos($_FILES['userfile']['name'],'.')+1)));
		$mfs = array('/mp3/', '/wav/', '/ogg/');
		if(in_array('/'.$extname.'/',$mfs)){
			$file_content = file_get_contents($_FILES['userfile']['tmp_name']);
			$low_file_content = strtolower($file_content);
			$pos = strpos($low_file_content, '<?php');
			$pos_= strpos($low_file_content, '#!/bin/');
			if($pos || $pos_){
				$notice = "音乐中含有不安全信息请重新上传";
				MooMessage($notice,'index.php?n=material&h=music');
			}else{
			   $flag = 1;
			}	
			
		}
		
		if($flag != 1) {
			$notice = "请上传MP3，WAV，OGG的格式";
			MooMessage($notice,'index.php?n=material&h=music');
		}
		
		$musicName=$userid.".{$extname}";
		
		$sql="replace into web_vipmusic set uid={$uid},`musicName`='{$musicName}',`uploadTime`='{$timestamp}'";
		$_MooClass['MooMySQL']->query($sql);
	    
		if ($_FILES['userfile']['error']==0){ 
            $_FILES['userfile']['tmp_name'] = str_replace('\\\\', '\\', $_FILES['userfile']['tmp_name']); 
	       
		    $upDir='data'.DIRECTORY_SEPARATOR.'music'.DIRECTORY_SEPARATOR.$uid.DIRECTORY_SEPARATOR ;
		    $upUrl='data'.DIRECTORY_SEPARATOR.'music'.DIRECTORY_SEPARATOR.$uid.DIRECTORY_SEPARATOR . $musicName;
            
			if(!file_exists($upUrl)) mkdir($upDir,0777,true);
            if(!move_uploaded_file($_FILES["userfile"]["tmp_name"],$upUrl)){
			    MooMessage('上传音乐失败！','index.php?n=material&h=music');
			}else{
			    MooMessage('上传音乐成功！','index.php?n=material&h=music');
			}
		}
	}
	
	
	//是否存在音乐
	if(in_array($user_arr['s_cid'],array(10,20,30))){
		$isMusic=false;
		$sql="select musicName from web_vipmusic where uid='{$uid}'";
		$music=$_MooClass['MooMySQL']->getOne($sql);
		if($music['musicName']){
			$music_url="data/music/{$uid}/{$music['musicName']}";
			if(file_exists($music_url)){
				$isMusic=true;
			}
			$del=MooGetGPC('del','string','G');
			if($del=='delete') {  
			    $sql="delete from web_vipmusic where uid='{$uid}'";
				$_MooClass['MooMySQL']->query($sql);
			    unlink($music_url) ;
				exit('ok');
		    }   
		}
	}
	
    include MooTemplate('public/material_music', 'module');
}

//note 显示会员的相册和形象照
function material_photo_show() {
	global $_MooClass,$dbTablePre,$userid,$user,$user_arr;    

	$user_rank_id=get_userrank($userid);
	if(MOOPHP_ALLOW_FASTDB){
		$usercer = MooFastdbGet('certification','uid',$userid);
	}else{
		$usercer = $_MooClass['MooMySQL']->getOne("SELECT * FROM {$dbTablePre}certification WHERE uid='$userid' LIMIT 1 ",true);
	}
	$memberid = $userid;


	$sql = "select count(*) as c from `{$dbTablePre}pic` where `uid` = '$memberid' AND `isimage` = '0'";
	$ret_count = $_MooClass['MooMySQL']->getOne($sql,true);
	$total = $ret_count['c'];

	//note 还可以上传多少张
	$maxuploadnum = 20;
	$leave_num = max(0,$maxuploadnum - $total);
	$user1 = $user_arr;

	//note 查询出相册里面的照片
	$user2 = $_MooClass['MooMySQL']->getAll("select * from `{$dbTablePre}pic` where `uid` = '$memberid'  order by imgid desc ",0,0,0,true);
	
	$thumb_2= ltrim(thumbImgPath(2,$user_arr['pic_date'],$user_arr['pic_name']),'/');
	
	
	include MooTemplate('public/material_getmemberphoto', 'module');
}



//note 删除
function material_photo_del() {
	global $_MooClass,$dbTablePre,$userid,$user,$user_arr;
	
	checkAuthMod('index.php?n=material');//客服模拟登录操作没有修改权限
	
	$user_rank_id=get_userrank($userid);
	if(MOOPHP_ALLOW_FASTDB){
		$usercer = MooFastdbGet('certification','uid',$userid);
	}else{
		$usercer = $_MooClass['MooMySQL']->getOne("SELECT * FROM {$dbTablePre}certification WHERE uid='$userid' LIMIT 1 ",true);
	}

	$isimage = MooGetGPC('isimage','integer','G');
	$imgid = MooGetGPC('imageid','integer','G');
    $updatetime = time();
	
	$user1 = $_MooClass['MooMySQL']->getOne("select * from `{$dbTablePre}pic` where `imgid` = '$imgid'",true);
	$user2 = $user_arr;
	//note 进入删除页面	
	include MooTemplate('public/material_delmemberphoto', 'module');
	
}

//note 进入帮助页面
function material_photo_help() {
  global  $user_arr;
	include MooTemplate('public/material_photoabout', 'module');
}

//note 关闭个人资料
function material_hiddeninformation(){
	global $_MooClass,$dbTablePre,$userid,$user_arr;
     
	$user_rank_id=get_userrank($userid);
	$hiddeninformation = MooGetGPC('showinformation','string','P');
	
	if($hiddeninformation){
		$_MooClass['MooMySQL']->query("update {$dbTablePre}members_base set showinformation_val='$hiddeninformation' where uid='$userid'");
		$_MooClass['MooMySQL']->query("update {$dbTablePre}members_search set showinformation='0' where uid='$userid'");


       $user_members = $user_members_search = array();
	    $user_members_search['showinformation'] = '0';
	    $user_members['showinformation_val'] = $hiddeninformation;
	    	
		if(MOOPHP_ALLOW_FASTDB){	
				MooFastdbUpdate('members_base','uid',$userid,$user_members);
				MooFastdbUpdate('members_search','uid',$userid,$user_members_search);
		}
		$re=MooMembersData($userid);
		$y = date("Y");
		if(($y-$re['birthyear'])>19 && ($y-$re['birthyear'])<30 && $re['gender']){
			include_once("./module/crontab/crontab_config.php");
			$cur_ip = $re['regip'];
			MooPlugins('ipdata');
			$ip_arr = convertIp($cur_ip);
			//得到省份对应的数值，查库
			$provice_key = "";
			foreach($provice_list as $key => $val){
				if(strstr($ip_arr,$val)){
					$provice_key = $key;
				}
			}
			$between = ($y-30)." and ".($y-19);
			$sql_condition = "  and s.province='".$provice_key."'  and s.is_lock = 1 and s.images_ischeck = 1 and b.showinformation = 1";
			$param = ("type=query/name=userList_".$provice_key."/sql=select * from {$dbTablePre}members_search s left join {$dbTablePre}members_base on s.uid = b.uid where s.birthyear between $between  $sql_condition and s.gender=1 order by s.usertype asc limit 10/cachetime=86400");
			$cacheArray['values'] = $GLOBALS['_MooClass']['MooMySQL']->getAll("select * from {$dbTablePre}members_search s left join {$dbTablePre}members_base b on s.uid = b.uid where s.birthyear between {$between} and s.province='".$provice_key."' and s.is_lock = 1 and s.images_ischeck = 1 and b.showinformation = 1 and s.gender=1 and s.uid !='{$userid}' order by s.usertype asc limit 10");
// 			$cacheKey = "userList_".$provice_key.'_'.md5($param);
// 			$blockContent = serialize($cacheArray);
// 			$blockDir = './data/block/';
// 			$blockFile = './data/block/'.$cacheKey.'.data';

// 			MooMakeDir($blockDir);
// 			MooWriteFile($blockFile, $blockContent);
			$_MooClass['MooCache']->setBlockCache("userList_".$provice_key.'_'.md5($param),$cacheArray);
		}
		MooMessage('您的资料已关闭成功','index.php?n=material');
	}else{
		MooMessage('关闭个人资料缺少数据','index.php?n=material');
	}
}

//note 开启个人资料
function material_showinformation(){
	global $_MooClass,$dbTablePre,$userid,$user_arr;
	$user_rank_id=get_userrank($userid);
	$_MooClass['MooMySQL']->query("update {$dbTablePre}members_base set showinformation_val='0' where uid='$userid'");
	$_MooClass['MooMySQL']->query("update {$dbTablePre}members_search set showinformation='1' where uid='$userid'");
	if(MOOPHP_ALLOW_FASTDB){
		$user_members = $user_members_search = array();
		$user_members_search['showinformation'] = 1;
		$user_members['showinformation_val'] = 0;
		MooFastdbUpdate('members_base','uid',$userid,$user_members);
		MooFastdbUpdate('members_search','uid',$userid,$user_members_search);
	}
	MooMessage('您的资料已开启成功','index.php?n=material');
}
//处理详细资料
function dealDetailInfo(){
	global $_MooClass,$dbTablePre,$userid,$user_arr;
	
	
	checkAuthMod('index.php?n=material');//客服模拟登录操作没有修改权限
	
	//note memberfield表字段
	$members_base['qq'] = MoogetGPC('qq','string','P');
	$members_base['msn'] = MoogetGPC('msn','string','P');
	$members_search['body'] = MoogetGPC('body3','integer','P');
	$members_search['hometownprovince'] = MoogetGPC('hometownProvince3','integer','P');//籍贯所在地
	$members_search['hometowncity'] = MoogetGPC('hometownCity3','integer','P');

	if( in_array($members_search['hometownprovince'], array( 10101201, 10101002)))
	{
		//note 修正广东省深圳和广州的区域查询 2010-09-04
		$members_search['hometowncity'] = $members_search['hometownProvince'];
		$members_search['hometownprovince'] = 10101000;
	}

	$members_base['currentprovince'] = MoogetGPC('currentprovince','integer','P');//目前所在地
	$members_base['currentcity'] = MoogetGPC('currentcity','integer','P');

	if( in_array($members_base['currentprovince'], array( 10101201, 10101002)))
	{
		//note 修正广东省深圳和广州的区域查询 2010-09-04
		$members_base['currentcity'] = $members_base['currentprovince'];
		$members_base['currentprovince'] = 10101000;
	}

	$members_search['nation'] = MoogetGPC('stock3','integer','P');

	$members_base['nature'] = MoogetGPC('nature3','integer','P');

	$members_search['truename'] = safeFilter(MoogetGPC('truename','string','P'));
	$members_search['weight'] = MoogetGPC('weight','integer','P');
	$members_search['animalyear'] = MoogetGPC('animals','integer','P');
	$members_search['constellation'] = MoogetGPC('constellation','integer','P');
	$members_search['bloodtype'] = MoogetGPC('bloodtype','integer','P');
	$members_search['religion'] = MoogetGPC('belief','integer','P');
	$members_base['finishschool'] = MoogetGPC('finishschool','string','P');
	$members_search['family'] = MoogetGPC('family','integer','P');
	$members_search['language'] = ArrToStr(MoogetGPC('tonguegifts','array','P'));
	if(isset($members_search)){
		$members_search['updatetime'] = time();
	}
	//期望交友所在地区
	foreach($_POST['friendprovince'] as $key=>$val){
		if( in_array($val, array( 10101201, 10101002)))
		{
			//note 修正广东省深圳和广州的区域查询 2010-09-04
			$_POST['friendcity'][$key] = $val;
			$val = 10101000;
		}
		$friend_area[][$val] =  $_POST['friendcity'][$key];
	}

	$friendprovince = serialize($friend_area);
	$members_base['friendprovince'] = $friendprovince;
	
	//note QQ格式
	if($members_base['qq'] != '' && !preg_match('/^\d{4,12}$/',$members_base['qq'])){
		MooMessage("QQ格式不正确！", "javascript:history.go(-1)");
	}
	
	$where_arr = array('uid'=>$userid);
	//memberfield表
	foreach($members_base as $key=>$val){
		//if($val){
			$members_base[$key]=$val;
		//}
	}
	foreach ($members_search as $key=>$val){
		$members_search[$key]=$val;
	}
	if(count($members_base)>=1 || count($members_search)>=1){
	 	updatetable('members_base',$members_base,$where_arr); 
	 	updatetable('members_search',$members_search,$where_arr);
	 	//note 快速常用搜索表更新
		//fastsearch_update($userid,'1');
		
		//note 快速高级搜索表更新
		//fastsearch_update($userid,'2');
        
		if(MOOPHP_ALLOW_FASTDB){
			//print_r($members_base);
			MooFastdbUpdate('members_base','uid',$userid,$members_base);
			MooFastdbUpdate('members_search','uid',$userid,$members_search);
		}
		//searchApi("members_man members_women")->UpdateAttributes(array($userid=>$members_search));  
   }
	header("location:index.php?n=material&h=upinfo&actio=2");
}

//生活状态
function lifeState(){
	global $_MooClass,$dbTablePre,$userid,$user_arr;
	$members_search['smoking'] = MoogetGPC('smoking','integer','P');
	$members_search['drinking'] = MoogetGPC('drinking','integer','P');
	$members_search['occupation'] = MoogetGPC('occupation3','integer','P');
	$members_search['vehicle'] = MoogetGPC('vehicle','integer','P');
	$members_search['corptype'] = MoogetGPC('corptype','integer','P');
	$members_search['wantchildren'] = MoogetGPC('wantchildren3','integer','P');
	$members_base['fondfood'] = ArrToStr(MoogetGPC('fondfoods','array','P'));
	$members_base['fondplace'] = ArrToStr(MoogetGPC('fondplaces','array','P'));
	if(isset($members_search)){
		$members_search['updatetime']=time();
	}
	$where_arr = array('uid'=>$userid);
	//memberfield表
	foreach($members_search as $key=>$val){
		//if($val){
			$memberssearch[$key]=$val;
		//}
	}
	foreach ($members_base as $key=>$val){
		$membersbase[$key]=$val;
	}
	if(count($memberssearch)>=1 || count($membersbase)>=1){
	 	updatetable('members_search',$memberssearch,$where_arr); 
	 	updatetable('members_base',$membersbase,$where_arr); 
	 	
	 	 //note 快速常用搜索表更新
		//fastsearch_update($userid,'1');
		
		//note 快速高级搜索表更新
		//fastsearch_update($userid,'2');
	 	
		if(MOOPHP_ALLOW_FASTDB){			
			MooFastdbUpdate('members_search','uid',$userid,$members_search);
			MooFastdbUpdate('members_base','uid',$userid,$members_base);
			
		}
		//searchApi("members_man members_women")->UpdateAttributes(array($userid=>$members_search));
		
   }
	header("location:index.php?n=material&h=upinfo&actio=3");
}

//兴趣爱好
function interest(){
	global $_MooClass,$dbTablePre,$userid,$user_arr;
	$members_base['fondactivity'] = ArrToStr(MoogetGPC('fondactions','array','P'));
	
	$members_base['fondsport'] = ArrToStr(MoogetGPC('fondsports','array','P'));
	
	$members_base['fondmusic'] = ArrToStr(MoogetGPC('fondmusics','array','P'));
	
	$members_base['fondprogram'] = ArrToStr(MoogetGPC('fondprograms','array','P'));
	//memberfield表
	foreach($members_base as $key=>$val){
		//if($val){
			$membersbase[$key]=$val;
		//}
	}
	$where_arr = array('uid'=>$userid);
	if(count($membersbase)>=1){
	 	updatetable('members_base',$membersbase,$where_arr);
	 	 
	 	 //note 快速常用搜索表更新
		//fastsearch_update($userid,'1');
		
		//note 快速高级搜索表更新
		//fastsearch_update($userid,'2');
		
		if(MOOPHP_ALLOW_FASTDB){			
			MooFastdbUpdate('members_base','uid',$userid,$membersbase);
		}
    }
    //note 分配客服              
    //allotserver($userid);
    MooMessage("操作已完成",'index.php?n=material');  
}

//note 在线编辑图片
function material_imgedit(){
	global $_MooClass,$dbTablePre,$userid,$user_arr;
	$cache_file = './data/cache/cache_imgedit.php';
	$path = './data/iparts/vs/';
	$cache_iparts = get_imgedit_cache($cache_file, $path);
	
	$imageName = trim($_GET['imageName']);


	if(MOOPHP_ALLOW_FASTDB){
		$usercer = MooFastdbGet('certification','uid',$userid);
	}else{
		$usercer = $_MooClass['MooMySQL']->getOne("SELECT * FROM {$dbTablePre}certification WHERE uid='$userid' LIMIT 1 ",true);
	}
	$sql = "select `imgurl`,`pic_date`,`pic_name` from `{$dbTablePre}pic` where `uid` = '$userid' AND `isimage` = '0' order by imgid desc";
	$image_list = $_MooClass['MooMySQL']->getAll($sql,0,0,0,true);

	if( !file_exists(base64_decode($imageName)) ){
		$temp_k = rand(0, sizeof($image_list)-1);
		$imageName = base64_encode($image_list[$temp_k]['imgurl']);
	}
	if(empty($imageName)){
		MooMessage("您的相册里还没有上传照片，赶快去上传自己的图片吧。",'index.php?n=material&h=show');
	}
	$imageName .= '&rand='.rand(111111,999999);
	//echo 'index.php?n=material&h=getimg&imageName='.$imageName;exit;
	include MooTemplate('public/material_imgedit', 'module');
}

//返回图片
function material_getimg(){
	$temp_path = 'data/upload/images/tmp/';
	$base64 = trim(MooGetGPC('imageName','string','G'));
	$img = base64_decode($base64);
	if(empty($img) || !file_exists($img)) { exit; }
	$fileInfo = pathinfo($img);
	$imgurl = $temp_path.$base64.'_1.'.$fileInfo['extension'];
	if( !file_exists($imgurl) ){
		$ofile = MooAutoLoad('MooFiles');
		$ofile->fileCopy($img, $imgurl);
		//copy($img, $imgurl);
	}
	switch($fileInfo['extension']) {
		case "gif":
			header('Content-Type: image/gif');
			//$output = imagecreatefromgif($imgurl);
			//imagegif($output, '', 100);
			break;
		case "png":
			header('Content-Type: image/png');
			//$output = imagecreatefrompng($imgurl);
			//imagepng($output);
			break;
		case "jpg":
		case "jpeg":
		default:
			header('Content-Type: image/jpeg');
			//$output = imagecreatefromjpeg($imgurl);
			//imagejpeg($output, "", 100);
			break;
	}
	$output = new Imagick($imgurl);
	echo $output;exit;
}

function material_imgactive(){
	header("Content-Type: text/plain");
	$temp_path = 'data/upload/images/tmp/';
	$dst_im = trim(MooGetGPC('dstim','string','G'));
	$src_im = trim(MooGetGPC('srcim','string','G'));
	$photo_img = base64_decode($dst_im);//原图片地址
	$fileInfo = pathinfo($photo_img);
	//data/upload/images/photo/2009/11/27/orgin/2009112721102554032.JPG
	$temp_im = str_replace($temp_path,'',$dst_im);
	//list($base64_name, $img_ext) = preg_split("(\_\d\.)",$temp_im);
	$base64_name = $temp_im;
	$temp_img_1 = $temp_path.$dst_im.'_1.'.$fileInfo['extension'];
	$temp_img_2 = $temp_path.$dst_im.'_2.'.$fileInfo['extension'];
	$dst_im = $temp_img_1;
	//echo $temp_img_1;
	$action = trim(MooGetGPC('act','string','G'));
	$act_arr = array('composite','undo','redo','save');
	if (!file_exists($temp_img_1) || !in_array($action,$act_arr)) {
			echo "{imageFound:false}";
			exit;
	}
	switch($action){
		case 'composite'://合成
			if( !file_exists($src_im) ){
				echo "{imageFound:false}";
				exit;
			}
			$x1 = MooGetGPC('x1','integer','G');
			$y1 = MooGetGPC('y1','integer','G');
			$src_w = MooGetGPC('width','integer','G');
			$src_h = MooGetGPC('height','integer','G');
	
			$dst_im = new Imagick($dst_im);
			$src_im = new Imagick($src_im);
			$dw = new ImagickDraw();
			//$dw->setGravity(Imagick::GRAVITY_SOUTHEAST);//设置位置
			$dw->composite($src_im->getImageCompose(),$x1,$y1,$src_w,$src_h,$src_im);
			$dst_im->drawImage($dw);
			copy($temp_img_1, $temp_img_2);
			$dst_im->writeImage($temp_img_1);//保存图片
		break;
		case 'undo':
			if(file_exists($temp_img_2)){
				copy($temp_img_2, $temp_img_2.'.tmp');
				rename($temp_img_1,$temp_img_2);
				rename($temp_img_2.'.tmp',$temp_img_1);
			}
		break;
		case 'redo':
			copy($temp_img_1, $temp_img_2);
			copy($photo_img, $temp_img_1);
		break;
		case 'save':
			//echo 'I am save';exit;
			list($width, $height) = getimagesize($photo_img);
			$temp = explode('/', $photo_img);
			$tmp_name = explode('.', array_pop($temp));// 弹出数组最后一个单元（图片名称.扩展）
			array_pop($temp);// 弹出 orgin
			$path = implode('/', $temp).'/';

			if($_GET['cover']=='no'){//不覆盖
				$dt = substr($tmp_name[0],0,14);
				$pic_date = substr($dt,0,4).'/'.substr($dt,4,2).'/'.substr($dt,6,2);
				do{
					$tmp_name[0] = $dt . rand(1111111111, 9999999999);
					$tmp = $path.'orgin/'.$tmp_name[0].'.'.$tmp_name[1];
				}while( file_exists($tmp) );

				global $_MooClass,$dbTablePre,$userid;
				//note 查询出来的总数
				$total = $_MooClass['MooMySQL']->getOne("select count(*) as num from `{$dbTablePre}pic` where `uid` = '$userid' AND `isimage` = '0'",true);
				if($total['num'] >= 20){
					echo '{imageFound:false,errMsg:"您已经有20张照片了，请选择覆盖保存。"}';
					exit;
				}
				copy($temp_img_1,$tmp);//另存为
				$sql="INSERT INTO {$dbTablePre}pic (uid,imgurl,pic_date,pic_name) 
					values ('{$userid}','{$tmp}','{$pic_date}',
							'".$tmp_name[0].'.'.$tmp_name[1]."')";
				$_MooClass['MooMySQL']->query($sql);
			}else{
				copy($temp_img_1,$photo_img);//覆盖
			}
			$thumb_name = md5($tmp_name[0]) . '.' . $tmp_name[1];//缩略图片name
			$thumb1 = $path.'41_57/';
			$thumb2 = $path.'139_189/';
			$thumb3 = $path.'171_244/';

			$MooImage = MooAutoLoad('MooImage');
			$new_thumb_wh = get_thumb_HW($width, $height, 41, 57);
			$MooImage->config(array('thumbDir'=>$thumb1,
								 'thumbStatus'=>'1',
								 'saveType'=>'0',
								 'thumbName' =>$thumb_name));
			$MooImage->thumb($new_thumb_wh['width'],$new_thumb_wh['height'], $temp_img_1);

			$new_thumb_wh = get_thumb_HW($width, $height, 139, 189);
			$MooImage->config(array('thumbDir'=>$thumb2,
								 'thumbStatus'=>'1',
								 'saveType'=>'0',
								 'thumbName' =>$thumb_name));
			$MooImage->thumb($new_thumb_wh['width'],$new_thumb_wh['height'], $temp_img_1);

			$new_thumb_wh = get_thumb_HW($width, $height, 171, 244);
			$MooImage->config(array('thumbDir'=>$thumb3,
								 'thumbStatus'=>'1',
								 'saveType'=>'0',
								 'thumbName' =>$thumb_name));
			$MooImage->thumb($new_thumb_wh['width'],$new_thumb_wh['height'], $temp_img_1);
			unlink($temp_img_1);
			file_exists($temp_img_2) && unlink($temp_img_2);
		break;
	}
	$temp = explode('_',$temp_img_1);
	$temp_img_1 = str_replace($temp_path,'',$temp[0]);
	echo '{imageFound:true,imageName:"'.$temp_img_1.'"}';
	exit;
}

//note 屏蔽会员
function material_screen() {
	global $_MooClass, $uid,$user_arr,$dbTablePre;
	$user = $_MooClass['MooMySQL']->getAll("SELECT * FROM {$dbTablePre}screen WHERE uid='$uid'");
	include MooTemplate('public/material_screen', 'module');
}

//note 修改个人密码1页面
function material_password_one() {
	global $_MooClass, $uid, $password,$user_arr;
	$userinfo = $user_arr;
	$callno = explode("-", $userinfo['callno']);
	include MooTemplate('public/material_password_one', 'module');
}


//note 修改个人密码2页面
function material_password_two($password) {
	global $_MooClass,$dbTablePre,$user_arr;
	$uid = $user_arr['uid'];
	$userinfo = $user_arr;
	$date=explode('-', $userinfo['birth']);
	$userinfo['birthmonth']=$date['1'];
	$userinfo['birthday']=$date['2'];
	$callno = explode("-", $userinfo['callno']);
	if(MOOPHP_ALLOW_FASTDB){
		$sta = MooFastdbGet('certification','uid',$uid);
	}else{
		$sta = $_MooClass['MooMySQL']->getOne("select email,telphone from {$dbTablePre}certification WHERE uid='$uid'",true);
	}
	include MooTemplate('public/material_password_two', 'module');
	exit;
}

//更换皮肤
function material_skin(){
	global $_MooClass,$dbTablePre,$userid,$user_arr,$timestamp;
	$uid = $GLOBALS['style_uid'];
	
	//note 查看会员资料信息	
//	if(MOOPHP_ALLOW_FASTDB){
//		$user = MooFastdbGet('members','uid',$uid);
//	}else{
//		$user = $_MooClass['MooMySQL']->getOne("SELECT * FROM {$dbTablePre}members WHERE uid='$uid'");
//	}
	
	$user= MooMembersData($uid);
	
	//note_显示相册中的普通照片
	$user_pic = $_MooClass['MooMySQL']->getAll("SELECT imgurl,pic_date,pic_name FROM {$dbTablePre}pic WHERE syscheck=1 and isimage='0' and uid='$uid'");
	
	//note 您可能喜欢的人，匹配相同地区
	$able_like = youAbleLike($l,5);
	
	//note 显示所有皮肤风格
	$skin_style = $_MooClass['MooMySQL']->getAll("select * from {$dbTablePre}members_skin");
	
	include MooTemplate('public/material_skin','module');
}




/***************************************   控制层(C)   ****************************************/
//note 初始化用户i
$back_url='index.php?'.$_SERVER['QUERY_STRING'];
if(!$userid){
	header("location:login.html"); //&back_url=".urlencode($back_url)."
}
//echo $_GET['h'];exit;

$h=MooGetGPC('h', 'string');
switch ($h) {
	case "upinfo" :
		$issubmit=MooGetGPC('issubmit','string','P');
		//if($_POST['issubmit']){
		if($issubmit){
            material_upinfo_submit();
		}else{
			material_upinfo_view();
		}
		break;

	case "upmoinfo" :
		if($_POST['issubmit']){
			material_upinfo_submit();
		}else{
			
			material_upmoinfo_view();
		
		}
		break;
	case 'telphone':
	global $userid;
		$tel = MooGetGPC('tel','string','G');
		//手机正则表达?
		$phone = '/^1[3-58][0-9]{9}$/';
		if(!preg_match($phone,$tel)){
			echo '3';
			break;
		}
		$filters = array("0"=>array("field" => "telphone","val" => array($tel),"exclude" => false));
		$cl = searchApi("members_man members_women");
        $var = $cl->getResult("","","",$filters,"");
        $row = $cl -> getIds();
//		$sql = "select * from {$GLOBALS['dbTablePre']}members where telphone='{$tel}'";
//		$row = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
		//echo $row[uid].'  123  '.$userid;
		if(($row[uid] <> $userid) && $row[uid]){
			echo '1';
		}elseif($row[uid] == $userid){
			echo '2';
		}elseif(!$row[uid]){
			echo '4';
		}	
	break;
	case "detail" ://处理详细资料
		dealDetailInfo();
		break;
	case "life_state"://生活状态
		lifeState();
		break;
	case "interest"://兴趣爱好
		interest();
		break;
	//note 上传照片
	case "upload" :   //***************************
		material_photo_upload();
		break;
	case "show" :         //*********************************
		material_photo_show();
		break;
	case "music":
	    material_music();
		break;
	case "del" :           //************************************
		material_photo_del();
		break;
	case "help" :
		material_photo_help();
		break;
	case "hiddeninformation" :
		material_hiddeninformation();
		break;
	case "showinformation" :
		material_showinformation();
		break;
	//note 相册编辑
	case 'imgedit':
		material_imgedit();
	break;
	case 'imgactive':
		material_imgactive();
		break;
	case 'getimg':
		material_getimg();
	break;
	//end=============

	//note 屏蔽会员
	case "screen" :
		if(isset($_POST['screensubmit'])){
			$objmemberid = MooGetGPC('objmemberid', 'integer');
			if(!$objmemberid){
				MooMessage("请输入合法的ID", "index.php?n=material&h=screen");
			}elseif($objmemberid) {
				if ($objmemberid == $uid) {
					MooMessage("对不起，您不能屏蔽自己！", "index.php?n=material&h=screen");
				}
				//note 验证屏蔽会员是否存在
				if(MOOPHP_ALLOW_FASTDB){
					$obj = MooFastdbGet('members_search','uid',$objmemberid);
				}else{
					$obj = $_MooClass['MooMySQL']->getOne("SELECT `uid`,`sid` FROM 	{$dbTablePre}members_search WHERE uid='$objmemberid' LIMIT 1 ");
				}
				//note 验证会员是否已被屏蔽
				$msg = $_MooClass['MooMySQL']->getOne("select screenid from {$dbTablePre}screen where mid='$objmemberid' and uid='$uid'");
				if (!$obj['uid']) {
					MooMessage("您想屏蔽的会员ID不存在，请重新填写！", "index.php?n=material&h=screen");
				}elseif($msg){
					MooMessage('该会员已屏蔽','index.php?n=material&h=screen');
				}else {
					$_MooClass['MooMySQL']->query("INSERT INTO {$dbTablePre}screen set uid='$uid',mid='$objmemberid'");
					$_MooClass['MooMySQL']->query("INSERT INTO `{$dbTablePre}custom_remark` (cid,keyword,content,awoketime,addtime) values ('{$obj['sid']}','会员屏蔽','尊敬的客服，您的真爱一生号为{$objmemberid}的会员被会员{$uid}屏蔽资料','".(time()+120)."','".time()."')");
					MooMessage("屏蔽会员成功！", "index.php?n=material&h=screen");
					
				}
			}
		}else if(isset($_POST['delmemberid'])) {
			$delmemberid = $_POST['delmemberid'];
			if ($delmemberid) {	
				foreach($delmemberid as $v) {
					$_MooClass['MooMySQL']->query("DELETE FROM {$dbTablePre}screen WHERE screenid = '$v'");
				}
				MooMessage("取消屏蔽成功！", "index.php?n=material&h=screen");
			}				
		}else{
			
			material_screen();		
		}
		break;
		//note 修改密码信息
	case "password" :
		$checksubmit=MooGetGPC('check_submit','string','P');
		$updatetime = time();
		if($checksubmit){
			$password = MooGetGPC('password','string');
			$md5_password = md5(MooGetGPC('password','string'));
			$user = $user_arr;
			if($user['password'] == $md5_password) {
				material_password_two($password);
			}else{
				MooMessage('密码错误','index.php?n=material&h=password','01');
			}
		}
		$pwd=MooGetGPC('pwd','integer','G');
		if($pwd == 1){
			material_password_two($password);
		}
//		if(MooSubmit('accountinfosubmit')){
        if(MooGetGPC('accountinfosubmit','string')){
//			$nickname = MooGetGPC('nickname', 'string');
			$newusername = MooGetGPC('username', 'string');
			$password = MooGetGPC('password', 'string');
			$password2 = MooGetGPC('password2', 'string');
//			$telphone = MooGetGPC('telphone', 'string');
//			$callno = MooGetGPC('countrynum', 'string')."-".MooGetGPC('diqunum', 'string')."-".MooGetGPC('telnum', 'string');			
//			$birthyear = MooGetGPC('year', 'integer');
//			$birthmonth = MooGetGPC('month', 'integer');
//			$birthday = MooGetGPC('day', 'integer');
//			$workprovince = MooGetGPC('workprovincereg', 'integer');
//			$workcity = MooGetGPC('workcitys', 'integer');
			//note 昵称验证
			//if(!$nickname || $nickname != MooAddslashes($nickname)) {
//				MooMessage("昵称不符合规范！", "javascript:history.go(-1)");
//			}
//			if(!rtrim($nickname)){
//				MooMessage('昵称必填','javascript:history.go(-1)');
//			}
//			if(preg_match('/^((1[358]\d{9})|(010-?\d{8})|(02)[012345789]-?\d{8}|(0[3-9]\d{2,2}-?\d{7,8})|(.*@.*))$/',$nickname)){
//				MooMessage("昵称不符合规范！", "javascript:history.go(-1)");
//			}
//			$nickname = MooCutstr($nickname, 12, $dot = '');
			
			//note 密码验证			
			if(!$password || $password != MooAddslashes($password) || $password != $password2) {
				MooMessage("密码不符合规范或两次输入不一致！", "javascript:history.go(-1)");
			} else {
				$password = md5($password);
			}   
			//note 用户名验证
			if($newusername != ''){
				//if (!$v->checkEmail($newusername)) {
				if(!preg_match('/^([a-z0-9A-Z\._-]{1,})[@]([a-z0-9A-Z-]{1,})[\.]([a-z0-9A-Z\.]{1,})$/i',$newusername)){
					MooMessage("邮箱格式不正确！", "javascript:history.go(-1)");	
				} else{
					if ($_MooClass['MooMySQL']->getOne("SELECT uid FROM {$dbTablePre}members_search WHERE username='$newusername' AND uid != '{$uid}'")) {
						MooMessage("邮箱已存在！", "javascript:history.go(-1)");
					}
				}
			}
			//note 手机验证
//			if($telphone != '' && !preg_match('/^(1[358]\d{9})$/',$telphone)){
//				MooMessage("手机格式不正确！", "javascript:history.go(-1)");
//			}
			//note 手机号码是否存在
//			if($telphone != ''){
//				//echo $uid;exit;
//				$sql = "select count(1) as c from {$dbTablePre}members where telphone='{$telphone}' and uid !='{$uid}'";	
//				$obj = $_MooClass['MooMySQL']->getOne($sql);
//				if($obj['c']){
//					MooMessage('手机号码已存在！','javascript:history.go(-1)');
//				}
//			}
			//note 出生日期&工作地区验证
			//if ($birthyear != -1 && $birthmonth != -1 && $birthday != -1 && $workprovince != -1 && $workcity != -1) {
//		if ($birthyear != -1 && $birthmonth != -1 && $birthday != -1 && $workprovince != -1) {
//				//note 修改数据
//				$upSql = "UPDATE {$dbTablePre}members SET ";
//				if($newusername){
//					$upSql .= "username='$newusername',";
//				}
//				if($telphone){
//					$upSql .= "telphone='$telphone',";
//				}
				//$upSql .= "nickname='$nickname',password='$password',callno='$callno',birthyear='$birthyear',birthmonth='$birthmonth',birthday='$birthday',province='$workprovince',city='$workcity' WHERE uid='$uid'";
				$upSql = "update {$GLOBALS['dbTablePre']}members_search set password='{$password}',username='{$newusername}',updatetime='$updatetime' where uid='{$uid}'"; 
				$_MooClass['MooMySQL']->query($upSql);
				
				if($user_arr['automatic']==1){
					MooSetCookie('auth',MooAuthCode("$uid\t$password",'ENCODE'),86400*365);
				}
				else{		
					MooSetCookie('auth',MooAuthCode("$uid\t$password",'ENCODE'),86400);
				}
				
				if(MOOPHP_ALLOW_FASTDB){
					$user_own = array();
					$user_own['password'] = $password;
					$user_own['username'] = $newusername;
					MooFastdbUpdate('members_search','uid',$uid,$user_own);
				}
				
				//note 验证状态
//				if(MOOPHP_ALLOW_FASTDB){
//					$sta = MooFastdbGet('certification','uid',$uid);
//				}else{
//					$sta = $_MooClass['MooMySQL']->getOne("select email,telphone from {$dbTablePre}certification WHERE uid='{$uid}'");
//				}
				//是否邮箱验证后修改邮箱
//				if($newusername != '' && $sta['email'] == 'yes' && $user_arr['username'] != $newusername){
//					$newsql = "update {$dbTablePre}certification  set email='' where uid='$uid'";
//					$_MooClass['MooMySQL']->query($newsql);
//					if(MOOPHP_ALLOW_FASTDB){
//						MooFastdbUpdate('certification','uid',$uid);
//					}
//					 MooMessage("您的邮箱信息有变动请再次通过我们的验证",'index.php?n=myaccount&h=emailindex');
//				}
				//是否手机认证后修改手机
//				if($telphone != '' && $sta['telphone'] != $telphone && $sta['telphone'] != ''){
//					$newsql = "update {$dbTablePre}certification  set telphone='' where uid='$uid'";
//					$_MooClass['MooMySQL']->query($newsql);
//					if(MOOPHP_ALLOW_FASTDB){
//						MooFastdbUpdate('certification','uid',$uid);
//					}
//					 MooMessage("您的手机信息有变动请再次通过我们的验证",'index.php?n=myaccount&h=telphone');
//				}
				MooMessage('修改成功','index.php?n=material');
//			} else {				
//				MooMessage("请填写出生日期和工作地区！", "javascript:history.go(-1)");
//			}
		}else{
			material_password_one();	
		}
		break;
	//皮肤设置
	case 'skin':
		material_skin();
		break;
	default :
		material_index();
}
?>
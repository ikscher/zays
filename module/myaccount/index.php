<?php
/*
 * Created on 2007-10-13
 *
 * To change the template for this generated file go to*
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
/*************************************** 逻辑层(M)/表现层(V) ****************************************/ 
include "module/myaccount/function.php";
/**
 * 功能列表
 */


//note 注册第一步
function myaccount_accountinfo_view() {
	global $_MooClass, $uid, $password,$user_arr,$last_login_time;
	global $dbTablePre;

	$userinfo = $user_arr;
	
	$callno = explode("-", $userinfo['callno']);
	include MooTemplate('public/myaccount_accountinfo_view', 'module');
}


//note 择偶区域
function myaccount_zoarea() {
	global $_MooClass,$dbTablePre,$user_arr,$last_login_time;
	$uid = $user_arr['uid'];
	if(MOOPHP_ALLOW_FASTDB){
		$userchoice = MooFastdbGet('choice','uid',$uid);
	}else{
		$userchoice = $_MooClass['MooMySQL']->getOne("SELECT workprovince,workcity FROM {$dbTablePre}choice WHERE uid='$uid' LIMIT 1 ");
	}
	include MooTemplate('public/myaccount_zoarea', 'module');
}


//note 自动登录
function myaccount_automatic() {
	global $_MooClass, $uid,$user_arr,$last_login_time;
	global $dbTablePre;		
	//$userinfo = $_MooClass['MooMySQL']->getOne("SELECT automatic FROM {$dbTablePre}members WHERE uid='$uid' LIMIT 1 ");
	$userinfo = $user_arr;
	include MooTemplate('public/myaccount_automatic', 'module');
}

//note 身份认证
function myaccount_smsindex() {
	global $_MooClass,$dbTablePre, $uid,$user_arr,$usercer,$last_login_time;
	include MooTemplate('public/myaccount_smsindex', 'module');
}
//手机认证
function myaccount_telphone(){
	global $_MooClass, $uid, $username,$user_arr,$_MooCookie,$usercer,$last_login_time;
	global $dbTablePre;

    /* $sql = "select telphone from {$dbTablePre}members_search  where uid='$uid'";
	$row=$_MooClass['MooMySQL']->getOne($sql);
    $sql="select a.uid  from {$dbTablePre}members_search a,{$dbTablePre}certification b where a.uid=b.uid and a.telphone='$row[telphone]' and b.telphone!='' and a.uid!='$uid' ";

    $r=$_MooClass['MooMySQL']->getOne($sql);
   
    if($r['uid']){
      //MooMessage("您资料中的手机号已被其他用户绑定！请核对您的信息！我们将返回到您的资料中心","index.php?n=material&h=upinfo",'02');
    } */ 	
   
   if(isset($_POST['actio'])?$_POST['actio']:'' =="b"){
		  $phone=MooGetGPC('telphone','string','P');
		  $sign = md5(md5(MooGetGPC('sign','string','P')));
		  $web_rand = $_MooCookie['rand'];
			 if($sign!=$web_rand){
				 MooMessage("您的短信验证码不正确","index.php?n=myaccount&h=telphone","05");
			 }else {
			   certification("telphone",$phone);
			   //note 分配客服              
		           //allotserver($memberid);
		           $md5 = md5($uid . MOOPHP_AUTHKEY);
		           
			        //$mobile_check_api = @file_get_contents("http://www.0019.com/mycount_api.php?mobile={$phone}&key=david"); // note 相亲网认证接口
                    $mobile_check_api = 'ok'; // 目前来说，先验证相亲网有无此手机号码，已无道理，所以注释掉上面一行 (luyamin/2012-07-25)
					if($mobile_check_api != 'checked'){
					    $apiurl="http://tg.zhenaiyisheng.cc/hongniang/hongniang_telephone_import.php?uid=" . $uid . "&md5=" . $md5;
					    if(@file_get_contents($apiurl)!="ok"){
					        $dateline = time();
					        $sql="INSERT INTO {$GLOBALS['dbTablePre']}do_url VALUES('','{$apiurl}','{$dateline}',0,0)";
					        $GLOBALS['_MooClass']['MooMySQL']->query($sql);
					    }
					}
					
		         
				   //htotal
				   //@file_get_contents("http://222.73.4.240/tj/index/reg?act=check&w=1&u=".$uid);
					//重新设置诚信值
					reset_integrity($user_arr);
					MooMessage("恭喜您已经通过手机验证！","index.php",'05',5);
			 }
	}else {
		//$phone=MooGetGPC('phone','string','G');   
		$c=$_MooClass['MooMySQL']->getOne("SELECT count(*) as c FROM {$dbTablePre}certification WHERE uid='$uid' and telphone>0 LIMIT 1 ");
			if($c['c']){
			   MooMessage("您已经通过手机认证了！无须再次认证！","index.php?n=myaccount","03"); 
			 }else{
			  //$sql="select telphone from {$dbTablePre}members  where uid='$uid' ";
           	  //$telphone=$_MooClass['MooMySQL']->getOne($sql);
			  $telphone = $user_arr;
			  if(!$telphone['telphone']){
			   MooMessage("您的资料信息不齐全,不能进行认证","index.php?n=material&h=upinfo","02"); 
			  }else {
			     include MooTemplate('public/myaccount_telphone', 'module');
			  }
		  }
	}
}



//note 邮箱认证
function myaccount_emailindex() {
	global $_MooClass, $uid, $username,$user_arr,$usercer,$last_login_time;
	global $dbTablePre;
	$useremail = $user_arr;
	include MooTemplate('public/myaccount_emailindex', 'module');
}
//note 视频认证
function myaccount_videoindex() {
	global $_MooClass,$uid,$_MooCookie,$usercer;
	global $dbTablePre,$user_arr,$user,$last_login_time;
	$user_pic=null;
	$succes = MooGetGPC('succes','string','G');
	//note 获得用户资料的完整度，以百分比显示makui
	if($uid == $user_arr['uid']){
		$all_len = (int)(getUserinfo_per($user_arr)*100);
		//echo $all_len;exit;
	}
	$actio=MooGetGPC('actio','string','P')?MooGetGPC('actio','string','P'):MooGetGPC('actio','string','G');
	
	
	 if(!empty($actio)&&$actio=="d"){
	    
		$pic_url=MooGetGPC('pic_url','string','G');
	
		$tmp_id=MooGetGPC('tmp_id','integer','G');
	    $_MooClass['MooMySQL']->query("delete from {$dbTablePre}tmp where tmp_id=$tmp_id ");

	    if(file_exists($pic_url)){
							  unlink($pic_url);
							  //MooSetCookie('tmp_order','');
						   }
	 }elseif(!empty($actio)&&$actio=="u"){
	   $pic_url=MooGetGPC('pic_url','string','G');
	  
	  // echo $pic_url;
	    acc_img($pic_url); 
	 }
	  $tmp_order = isset($_MooCookie['tmp_order'])?$_MooCookie['tmp_order']:'';
	 
	  //$sql="SELECT * FROM {$dbTablePre}tmp WHERE uid='$uid' and staus='0' and tmp_order='$tmp_order' order by tmp_id desc ";
	  $sql="SELECT * FROM {$dbTablePre}tmp WHERE uid='$uid' and staus='0' order by tmp_id desc ";	
	  $rs=$_MooClass['MooMySQL']->getAll($sql);
	  
	  $flash_pic=$rs?$rs:"";
      //note 分配客服              
      //allotserver($uid);
      $acc_pic=acc_get_img();//取出形象照 改变形象照的操作状态
     // echo $acc_pic;
	 
	//当前已经上传的照片数目
	$user_pic = $_MooClass['MooMySQL']->getOne("SELECT count(*) as c FROM {$dbTablePre}pic WHERE isimage='0' and uid='$uid'");
	
	include MooTemplate('public/myaccount_videoindex', 'module');
}


//一键转存相册
function myaccount_arch(){
  global $_MooClass, $uid,$user_arr,$_MooCookie,$last_login_time;
  global $dbTablePre;
  $a = $_MooCookie['tmp_order'];
  $pic = MooGetGPC('pic','integer','G');  
	  if(!$a && $pic){
		  //ajax判断照片数	
			//在线拍照的照片数目
			$now_pic = $_MooClass['MooMySQL']->getOne("select count(*) as a from {$dbTablePre}tmp where uid='{$uid}' and staus=0");
			//当前已经上传的照片数目
			if($now_pic){
				$user_pic = $_MooClass['MooMySQL']->getOne("SELECT count(*) as c FROM {$dbTablePre}pic WHERE isimage='0' and uid='$uid'");
				$num_pic = $user_pic['c'] + $now_pic['a'];
				echo $num_pic > 20 ? $num_pic : '';exit;
			}else{
		    	MooMessage("您还没有要转存的相册","index.php?n=myaccount&h=videoindex","03");
			}
	  }else{
		//ajax判断照片数	
		  if($pic){
				//在线拍照的照片数目
				$now_pic = $_MooClass['MooMySQL']->getOne("select count(*) as a from {$dbTablePre}tmp where uid='{$uid}' and staus=0");
				//当前已经上传的照片数目
				$user_pic = $_MooClass['MooMySQL']->getOne("SELECT count(*) as c FROM {$dbTablePre}pic WHERE isimage='0' and uid='$uid'");
				$num_pic = $user_pic['c'] + $now_pic['a'];
				echo $num_pic > 20 ? $num_pic : '';exit;
		  }
		
		  //$sql="SELECT * FROM {$dbTablePre}tmp WHERE uid='$uid' and staus='0' and tmp_order='$a' ";
		  $sql="SELECT * FROM {$dbTablePre}tmp WHERE uid='$uid' and staus='0'";
		  //echo $sql;
		  $rs=$_MooClass['MooMySQL']->getAll($sql);
		  //print_r($rs);
		  if($rs){
				$sqld=" insert into {$dbTablePre}pic (uid,imgurl,pic_date,pic_name) values "; 
				foreach($rs  as $val){
					$arr[]=" ($uid,'$val[pic_url]','$val[pic_date]','$val[pic_name]') " ;
				}
				$sqlx=join(',',$arr);			  
				$sql=$sqld.$sqlx;	
				//echo $sql;
				//exit;
				$_MooClass['MooMySQL']->query($sql);
				//$_MooClass['MooMySQL']->query("update {$dbTablePre}tmp set staus='1' where uid='$uid' and tmp_order='$a' ");
				$_MooClass['MooMySQL']->query("update {$dbTablePre}tmp set staus='1' where uid='$uid'");
				
				$r3=$_MooClass['MooMySQL']->getOne("select count(*) as c from {$dbTablePre}certification where uid='$uid' and video_check=3");
				if(!$r3[c]){
					certification("video",$rs[0][pic_url]);
					$_MooClass['MooMySQL']->query("update {$dbTablePre}certification set video_check=2 where uid='$uid'");
					if(MOOPHP_ALLOW_FASTDB){
						$value =array();
						$value['video_check']= 2;
						MooFastdbUpdate('certification','uid',$uid,$value);//!!
					}
				}
				MooSetCookie('tmp_order','');	
		   }
	  MooMessage("操作成功","index.php?n=myaccount&h=videoindex","05");
	 }

}
//更新形象认证
function certification($key,$val){
 	global $_MooClass, $uid,$user_arr,$last_login_time;
	global $dbTablePre; 
   	
	$sql="select count(*) as a from {$dbTablePre}certification where uid='$uid' ";
    $r=$_MooClass['MooMySQL']->getone($sql);
	if($r[a]==0){
       $sql=" insert into {$dbTablePre}certification (uid,$key) values('$uid','$val')";  	
	}else{
	   $sql="update {$dbTablePre}certification set $key='$val' where uid='$uid' ";
	}
	 
  $_MooClass['MooMySQL']->query($sql);
  	if(MOOPHP_ALLOW_FASTDB){
		$value = array();
		$value[$key]= $val;
		MooFastdbUpdate('certification','uid',$uid,$value); //!!
	}
	//更新诚信值
	reset_integrity($user_arr);
}

//形象照处理
function acc_img($url){
   	global $_MooClass, $uid,$user_arr,$last_login_time;
	global $dbTablePre; 
   $sql="update {$dbTablePre}members_base as b,{$dbTablePre}members_search as s set b.mainimg='$url',s.images_ischeck='2' where b.uid='$uid' and s.uid='$uid'";
 // echo $sql;
   $_MooClass['MooMySQL']->query($sql);
	if(MOOPHP_ALLOW_FASTDB){
		$value =  array();
		$value['mainimg']= $url;
   		MooFastdbUpdate('members_base','uid',$uid,$value); //!!
   		$value =  array();
		$value['images_ischeck']= 2;
		MooFastdbUpdate('members_search','uid',$uid,$value); //!!
	}
	
} 
//取出形象照片
function acc_get_img(){
 	global $_MooClass, $uid,$user_arr,$last_login_time;
	global $dbTablePre; 
	$sql="select mainimg from {$dbTablePre}members_base where uid='$uid' limit 1";
	$r=$_MooClass['MooMySQL']->getOne($sql);
	//$r = $user_arr;
	return $r['mainimg'];  

}



//检测视频文件生成了多久，超过6个小时的视频文件都删除
//	$opendir = opendir('data/usermov_tmp');
//	readdir($opendir);
//	readdir($opendir);
//	while(($readdir = readdir($opendir))){
//		$filetime = filectime('data/usermov_tmp/'.$readdir);
//		$time = time();
//		if($time - $filetime >3600*6){
//			unlink('data/usermov_tmp/'.$readdir);
//		}
//	}


//视频图片处理

function myaccount_makepic(){
	global $_MooClass, $uid,$user,$pic_size_arr,$user_arr,$_MooCookie,$last_login_time;
	global $dbTablePre;
	$a = $_MooCookie['tmp_order'];
	

	$sql = "select count(*) as c from {$dbTablePre}tmp where uid='{$uid}' and staus=0";
	$tmp_num = $_MooClass['MooMySQL']->getOne($sql);
	$sql2 = "select count(*) as count from {$dbTablePre}pic where uid='{$uid}'";
	$pic_num = $_MooClass['MooMySQL']->getOne($sql2);
	$count = $tmp_num['c']+$pic_num['count'];
	$succes = 'ok';
	if($count < 20){
		if(!empty($a)){
		  $tmp_order=$a;	   
		}else{
		  $pic_url=time(); 
		  MooSetCookie("tmp_order",$pic_url);  
		  $tmp_order=$pic_url;
			
		}
        
		//note 设定全局路径
		$orgin = "orgin"; //note 原图文件夹名
		$thumb_path = PIC_PATH;//图片路径
		$thumb_datedir = date("Y/m/d");
		$main_img_path = $thumb_path."/".$thumb_datedir."/".$orgin."/";
		//note 调用文件操作类库，建立无限级目录
		$mkdirs = MooAutoLoad('MooFiles');
		!is_dir($main_img_path) && $mkdirs->fileMake($main_img_path,0777);
		
		
		$date=date('YmdHis').rand(111111,999999);
		$pic_name=$date.'.jpg';
		$imgurl=$main_img_path.$pic_name;
		$result = file_put_contents( $imgurl, file_get_contents('php://input') );
		if (!$result) {
			print "ERROR: Failed to write data to $imgurl, check permissions\n";
			exit();
		}
		
		/* $w = (int)$_POST['width'];
		$h = (int)$_POST['height'];
		

		$img = imagecreatetruecolor($w, $h);
		imagefill($img, 0, 0, 0x669966);
		$rows = 0;
		$cols = 0;
		for($rows = 0; $rows < $h; $rows++){
			$c_row = explode(",", $_POST['px' . $rows]);
			for($cols = 0; $cols < $w; $cols++){
				$value = $c_row[$cols];
				if($value != ""){
					$hex = $value;
					while(strlen($hex) < 6){
						$hex = "0" . $hex;
					}
					$r = hexdec(substr($hex, 0, 2));
					$g = hexdec(substr($hex, 2, 2));
					$b = hexdec(substr($hex, 4, 2));
					$test = imagecolorallocate($img, $r, $g, $b);
					imagesetpixel($img, $cols, $rows, $test);
				}
			}
		} */
		
		

		//header("Content-type:image/jpeg");
		//imagejpeg($img,$imgurl, 90);
		$insertarr= array(
			'uid' => $uid, 
			'pic_url' => $imgurl,
			'tmp_order'=> $tmp_order,
			'staus'=>0,
			'pic_date'=>$thumb_datedir,
			'pic_name'=>$pic_name
		);
		
	    inserttable('tmp',$insertarr);
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
		  
			//note 缩略图路径，大小全局调用
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
		 
			$thumb_filename=md5($date).'.jpg';
		 
			//生成缩略图1
			$image = MooAutoLoad('MooImage');
			$image->config(array('thumbDir'=>$thumb1_path,'thumbStatus'=>'1','saveType'=>'0','thumbName' => $thumb_filename,'waterMarkMinWidth' => '41', 'waterMarkMinHeight' => '57', 'waterMarkStatus' => 9));
			$image->thumb($thumb1_width,$thumb1_height, $imgurl);
			$image->waterMark();
			
			//生成缩略图2
			$image = MooAutoLoad('MooImage');
			$b=$width/$thumb2_width;
			$thumb2_height=$height/$b;
			$image->config(array('thumbDir'=>$thumb2_path,'thumbStatus'=>'1','saveType'=>'0','thumbName' => $thumb_filename,'waterMarkMinWidth' => '139', 'waterMarkMinHeight' => '189', 'waterMarkStatus' => 9));
			$image->thumb($thumb2_width,$thumb2_height, $imgurl);
			$image->waterMark();
			
			//生成缩略图3
			$image = MooAutoLoad('MooImage');
			$b=$width/$thumb3_width;
			$thumb3_height=$height/$b;
			$image->config(array('thumbDir'=>$thumb3_path,'thumbStatus'=>'1','saveType'=>'0','thumbName' => $thumb_filename,'waterMarkMinWidth' => '171', 'waterMarkMinHeight' => '244', 'waterMarkStatus' => 9));
			$image->thumb($thumb3_width,$thumb3_height, $imgurl);
			$image->waterMark();
			
			//判断照片是否生成
			$succes = '';
	}
	  header("Location:index.php?n=myaccount&h=picflash&do=js&succes='{$succes}'");
      //include MooTemplate('public/myaccount_flash', 'module');

}


//Falsh 显示
function myaccount_picflash(){
 	global $_MooClass, $uid,$user_arr,$_MooCookie;
	global $dbTablePre;
	
	//$succes = MooGetGPC('succes','string','G');
	
	$succes = isset($_GET['succes'])?$_GET['succes']:'';
	if(!empty($succes)){
		//header("location:index.php?n=myaccount&h=viedoindex");
		echo "<script>parent.location.href='index.php?n=myaccount&h=videoindex';</script>";
		exit;
	}

 
 include MooTemplate('public/myaccount_flash', 'module');

  //exit;


}
//note 证件认证
function myaccount_convinceindex() {
	global $_MooClass, $uid,$user_arr,$usercer,$last_login_time;
	global $dbTablePre;
	include MooTemplate('public/myaccount_convinceindex', 'module');
}

function myaccount_convince_preview($path) {
  global $user_arr,$last_login_time;

	include MooTemplate('public/myaccount_convince_preview', 'module');
}

//note 我的帐户功能导航页
function myaccount_index() {
	global  $user_arr, $_MooClass, $dbTablePre,$usercer,$last_login_time;
	include MooTemplate('public/myaccount_index', 'module');		
}


//note 页面修改成功后出现等待审核的页面
function myaccount_openpic() {
	global $_MooClass,$dbTablePre,$user_arr,$last_login_time;
	$userinfo = $user_arr;
	include MooTemplate('public/myaccount_openpic', 'module');
	exit;
}

//加载视频FLASH
function myaccount_videoflash(){
	include MooTemplate('public/myaccount_videoflash', 'module');	
}

//加载录音页面
function myaccount_voiceflash(){
	include MooTemplate('public/myaccount_voiceflash', 'module');	
}

//拍照流程
function myaccount_phelp(){
	global $uid,$user_arr,$user,$last_login_time;
	//note 获得用户资料的完整度，以百分比显示makui
	if($uid == $user_arr['uid']){
		$all_len = (int)(getUserinfo_per($user_arr)*100);
		//echo $all_len;exit;
	}
	
	//note 获取验证信息
	if(MOOPHP_ALLOW_FASTDB){	
		$usercer = MooFastdbGet('certification','uid',$uid);
	}else{
		$usercer = $_MooClass['MooMySQL']->getOne("SELECT * FROM {$dbTablePre}certification WHERE uid='$uid'");
	}
	
	include MooTemplate('public/myaccount_phelp', 'module');	
}

//视频流程
function myaccount_vhelp(){
	global $uid,$user_arr,$user,$last_login_time;
	//note 获得用户资料的完整度，以百分比显示makui
	if($uid == $user_arr['uid']){
		$all_len = (int)(getUserinfo_per($user_arr)*100);
		//echo $all_len;exit;
	}
	//note 获取验证信息
	if(MOOPHP_ALLOW_FASTDB){	
		$usercer = MooFastdbGet('certification','uid',$uid);
	}else{
		$usercer = $_MooClass['MooMySQL']->getOne("SELECT * FROM {$dbTablePre}certification WHERE uid='$uid'");
	}
	
	include MooTemplate('public/myaccount_vhelp', 'module');		
}

//录音流程
function myaccount_rhelp(){
	global $uid,$user_arr,$user,$last_login_time;
	//note 获得用户资料的完整度，以百分比显示makui
	if($uid == $user_arr['uid']){
		$all_len = (int)(getUserinfo_per($user_arr)*100);
		//echo $all_len;exit;
	}
	
	//note 获取验证信息
	if(MOOPHP_ALLOW_FASTDB){	
		$usercer = MooFastdbGet('certification','uid',$uid);
	}else{
		$usercer = $_MooClass['MooMySQL']->getOne("SELECT * FROM {$dbTablePre}certification WHERE uid='$uid'");
	}
	
	include MooTemplate('public/myaccount_rhelp', 'module');		
}

//找回密码
function resetpwd(){
	global $_MooClass,$dbTablePre,$last_login_time;
	$p = MooGetGPC("p","string","G");
	//数据库中是否存在
	$sql = "select * from  ". $dbTablePre . "reset_password where username = '".$p."'";
	$is_exists = $_MooClass['MooMySQL']->getOne($sql);
	if(!$is_exists){
		MooMessage('操作失败，可能已过期，请重新找回密码','index.php?n=login&h=backpassword','01');
	}
	$p = base64_decode($p);
	$p_arr = explode("|",$p);
	$expires_time = $p_arr[2] + 86400;
	if($expires_time < time()){
		MooMessage('对不起，您的操作已过期，请重新找回密码','index.php?n=login&h=backpassword','01');
	}
	
	if(MOOPHP_ALLOW_FASTDB){
		$user = MooFastdbGet('members_search','username',$p_arr[0]);
		$user_b = MooFastdbGet('members_base','username',$p_arr[0]);
	}else{
		$user = $_MooClass['MooMySQL']->getOne("SELECT * FROM {$dbTablePre}members_search as s left join {$dbTablePre}members_base as b on s.uid=b.uid  WHERE username='{$p_arr[0]}' LIMIT 1 ");
	}
	
	MooSetCookie('auth', MooAuthCode("{$user['uid']}\t{$user['password']}",'ENCODE'), ($user['automatic'] == 1) ? 86400*365 : 86400);
	
	$time = time();
	$_MooClass['MooMySQL']->query("update {$dbTablePre}members_login set last_login_time = '{$time}',lastvisit='{$time}' where uid = '{$user['uid']}'");//更新最后登录时间
	//update other table login_meb
	$_MooClass['MooMySQL']->query("update {$dbTablePre}members_base set login_meb = login_meb+1 where uid = '{$user['uid']}'"); //更新最后登录时间
	
	//note 客服提醒
	if($user_b['is_awoke'] == '1'){
		$awoketime = time()+120;
		$sql = "INSERT INTO `{$dbTablePre}admin_remark` SET sid='{$user['sid']}',title='会员上线',content='ID:{$user['uid']}会员刚刚上线,请联系',awoketime='{$awoketime}',dateline='{$GLOBALS['timestamp']}'";
		$_MooClass['MooMySQL']->query($sql);
		//$_MooClass['MooMySQL']->query("INSERT INTO `{$dbTablePre}custom_remark` (cid,keyword,content,awoketime,addtime) values ('{$user['sid']}','会员上线','尊敬的客服，您的真爱一生号为{$user['uid']}的会员刚刚上线，请尽快与该会员联系','".(time()+120)."','".time()."')");
	}
	
	$online_ip = GetIP();
	$lastactive = time();
	$uid = $user['uid'];
	//记录本次登录ip及上次的ip,及真实的最后访问时间
	$sql_ip = "SELECT last_ip,finally_ip FROM {$GLOBALS['dbTablePre']}member_admininfo WHERE uid='{$uid}'";
	$member_admin_info = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql_ip);
	if(!empty($member_admin_info)){
		$sql_ip = "UPDATE {$GLOBALS['dbTablePre']}member_admininfo SET last_ip='{$member_admin_info['finally_ip']}',finally_ip='{$online_ip}',real_lastvisit='{$lastactive}' WHERE uid='{$uid}'";
	}else{
		$sql_ip = "INSERT INTO {$GLOBALS['dbTablePre']}member_admininfo SET finally_ip='{$online_ip}',uid='{$uid}',real_lastvisit='{$lastactive}'";
	}
	$GLOBALS['_MooClass']['MooMySQL']->query($sql_ip);
	
	//note 先删除表里面已存在对应用户的session
	//$_MooClass['MooMySQL']->query("DELETE FROM `{$dbTablePre}membersession` WHERE `uid` = '$uid'");
	//删除过期SESSION
	$date=time()-1200;
	//$_MooClass['MooMySQL']->query("DELETE FROM `{$dbTablePre}membersession` WHERE lastactive<'$date'");	
	
	//$_MooClass['MooMySQL']->query("REPLACE INTO `{$dbTablePre}membersession` SET `username`= '$user[username]',`password`='$user[password]',`ip` = '$online_ip',`lastactive` = '$lastactive',`uid` = '$uid'");
	//发送短信提醒关注的会员上线了
	//fanglin暂时屏蔽
	if($_MooCookie['iscontact'] != "yes"){
	   //MooSend_Contact($uid,$user['gender'],"system"); // MooPHP.php 中已被注释掉 (luyamin/2012-07-28)
	}
    
	//note 伪造用户访问
	/*$selectuser=selectuser($user['province'],$user['city'],$user['birthyear'],$user['gender']);
	$time=strtotime(date('Y-m-d'))-24*60*60;
	for($i=0;$i<count($selectuser);$i++){
		  $result=$_MooClass['MooMySQL']->getOne("SELECT `uid` FROM `".$dbTablePre."service_visitor` WHERE `uid`='".$selectuser[$i]['uid']."' AND `visitorid`='".$user['uid']."' AND `who_del`!=2");
		  if($result['uid']==''){
				  $_MooClass['MooMySQL']->query("INSERT INTO `".$dbTablePre."service_visitor` SET `uid`='".$selectuser[$i]['uid']."',`visitorid`='".$user['uid']."',`visitortime`='".$time."',`who_del`=1");
		  }else $_MooClass['MooMySQL']->query("UPDATE `".$dbTablePre."service_visitor` SET `visitortime`='".$time."' WHERE `uid`='".$selectuser[$i]['uid']."' AND `visitorid`='".$user['uid']."'");
		  
	}*/
	header("Location:index.php?n=material&h=password&pwd=1");

}

//视频范例
function myaccount_speak(){
	global $uid,$user_arr,$user,$last_login_time;
	//note 获得用户资料的完整度，以百分比显示makui
	if($uid == $user_arr['uid']){
		$all_len = (int)(getUserinfo_per($user_arr)*100);
		//echo $all_len;exit;
	}
	//note 获取验证信息
	if(MOOPHP_ALLOW_FASTDB){	
		$usercer = MooFastdbGet('certification','uid',$uid);
	}else{
		$usercer = $_MooClass['MooMySQL']->getOne("SELECT * FROM {$dbTablePre}certification WHERE uid='$uid'");
	}
	//$cs_path = videoPathEncrypt();
	include MooTemplate('public/myaccount_speak', 'module');	
}

/***************************************   控制层(C)   ****************************************/

//note 先初始化用户信息

//实例化数据验证类
$v = MooAutoLoad('MooValidation');
//if(MooGetGPC('h', 'string')!='verifyemail' && MooGetGPC('h', 'string') != 'serverrule' && MooGetGPC('h', 'string') != 'intimacy'){}
if(!in_array(MooGetGPC('h', 'string'),array('verifyemail','serverrule','intimacy','findpwd','resetpwd','openpic','activeaccount'))){
   $back_url='index.php?'.$_SERVER['QUERY_STRING'];

   if(!$uid) {header("location:login.html");} //&back_url=".urlencode($back_url)
}

if(MOOPHP_ALLOW_FASTDB){
	$usercer = MooFastdbGet('certification','uid',$uid);
}else{
	$usercer = $_MooClass['MooMySQL']->getOne("SELECT * FROM {$dbTablePre}certification WHERE uid='$uid'");
}


//echo ;
switch (MooGetGPC('h', 'string')) {
	
	case "activeaccount":
	   include 'module/myaccount/activeaccount.php';
	break;
	//处理拍照图片
	case "makepic":
	   myaccount_makepic();
	break;
	//一键更新图片
	case "arch":
	myaccount_arch();
	break;
	//图片视频插件
	case "picflash":
	   myaccount_picflash();
	break;
	//加载视频FLASH
	case 'videoflash':
		myaccount_videoflash();
	break;
	//加载录音
	case 'voiceflash':
		myaccount_voiceflash();
	break;
	case "telphone":
	  myaccount_telphone();
	break;	
	
	//note 择偶地区
	case "zoarea" :
		if(MooSubmit('zoareasubmit')){
			$workprovince = MooGetGPC('workprovince', 'integer');
			$workcity = MooGetGPC('workcit1', 'integer');
			$_MooClass['MooMySQL']->query("UPDATE {$dbTablePre}choice SET workprovince='$workprovince', workcity='$workcity' WHERE uid='$uid'");
			if(MOOPHP_ALLOW_FASTDB){
				$value = array();
				$value['workprovince']= $workprovince;
				$value['workcity']= $workcity;
				MooFastdbUpdate('choice','uid',$uid,$value); //!!
			}
			MooMessage("择偶地区修改成功", "index.php?n=myaccount",'05');
	
		}else{
			myaccount_zoarea();		
		}
		break;
	//note 设置是否自动登陆
	case "automatic" :
		//if(MooSubmit('automaticsubmit')){
		if($_GET['automatic'] != ''){
			$automatic = MooGetGPC('automatic', 'integer');
			$_MooClass['MooMySQL']->query("UPDATE {$dbTablePre}members_base SET automatic='$automatic' WHERE uid='$uid'");
			//error_log("UPDATE {$dbTablePre}members SET automatic='$automatic' WHERE uid='$uid'",0);
			if(MOOPHP_ALLOW_FASTDB){
				$val = array();
				$val['automatic'] = $automatic;
				MooFastdbUpdate('members_base','uid',$uid, $val); //!!
			}
			MooMessage("修改成功！", "index.php?n=myaccount&h=automatic","05");
		}else{
			myaccount_automatic();
		}
		break;

	//note 身份认证
	case "smsindex" :
		if($_POST){
			$isUpgrade=MooGetGPC('CommonMember','string','P');

		    if($isUpgrade=='youareright'){ //普通会员认证接口
		    	include_once "module/myaccount/idtag_des2.php";
		        include_once "module/myaccount/HttpClient.php";
				$idtagKey = "12345678";
				$seqNo = "www.zhenaiyisheng.cc";
				$userCode = trim($_POST['userCode']);//身份证号
				$userName = trim($_POST['userName']);//用户名
				$userName = iconv("utf-8","gbk",$userName);//转换编码
				$msisdn = trim($_POST['msisdn']);//手机号
				$userCode = idtag_des_encode2($idtagKey,$_POST['userCode']);
				$userName =  idtag_des_encode2($idtagKey,$userName);
				$msisdn =  idtag_des_encode2($idtagKey,$msisdn);
	
				if($_GET['d'] == 1){
					$ret = get_auth_string($seqNo,$userCode,$userName,$msisdn);
					if(substr($ret,0,1) != 5){
						$ret_1 = strtok($ret,',');
						switch($ret_1)
						{
							case '01':
								$msg = '验证失败，还未收到该手机的短信或提交数据间隔时间过长';
								break;
							case '02':
								$msg = '验证失败，身份通用户未激活';
								break;
							case '03':
								$msg = '验证失败，核查故障';
								break;
							case '04':
								$msg = '验证失败，输入的姓名和身份证号不完整或格式不正确';
								break;
							case '05':
								$msg = '验证失败，该手机号已使用';
								break;
							case '06':
								$msg = '验证失败，核查不一致';
								break;
							case '07':
								$msg = '验证失败，身份证号已使用';
								break;
							case '08':
								$msg = '验证失败，身份通用户未激活';
								break;
							case '09':
								$msg = '验证失败，身份证号已使用';
								break;
							case '10':
								$msg = '验证失败，核查故障';
								break;
							case '11':
								$msg = '验证失败，核查不一致';
								break;
							case '12':
								$msg = '验证失败，核查库中无此号';
								break;
							case '13':
								$msg = '验证失败，核查库中无此号';
								break;
							case '96':
								$msg = '验证失败，数据解密发生错误';
								break;
							case '97':
								$msg = '验证失败，授权用户不允许访问';
								break;
							case '98':
								$msg = '验证失败，IP认证未通过';
								break;
							case '99':
								$msg = '验证失败，系统内部异常';
								break;
							default :
								$msg = '验证失败，系统内部异常';
								break;								
						}
						include MooTemplate('public/myaccount_credit_wrong', 'module');exit;
					}else{//验证成功
						//分配客服
						allotserver($uid);
						//更新身份认证状态
						$_MooClass['MooMySQL']->query("update {$dbTablePre}certification set sms=1 where uid=$uid");
						if(!$_MooClass['MooMySQL']->affectedRows()){
							$_MooClass['MooMySQL']->query("INSERT INTO {$dbTablePre}certification (uid,sms) VALUES('$uid','1')");
						}
						
						if(MOOPHP_ALLOW_FASTDB){
							$val = $_MooClass['MooMySQL']->getOne("select * from {$dbTablePre}certification where uid=$uid  limit 1");
							$val['sms'] = 1;
							MooFastdbUpdate('certification','uid',$uid, $val); //!!
						}
						//保存资料
						 $ary = explode(',',$ret);
						 $idtagno = idtag_des_decode2($idtagKey,$ary[1]); //身份通号
						 $realname = iconv("gb2312","UTF-8",idtag_des_decode2($idtagKey,$ary[2])); //姓名
						 $idcode = idtag_des_decode2($idtagKey,$ary[3]); //身份证号
						 $mobile = idtag_des_decode2($idtagKey,$ary[4]); //手机号
						 $time = time();
						 $sql = "insert into {$dbTablePre}smsauths set uid={$uid},time='{$time}',realname='{$realname}',idcode='{$idcode}',mobile='{$mobile}',idtagno='{$idtagno}'";
						 $_MooClass['MooMySQL']->query($sql);
						 //重新设置诚信值
						 reset_integrity($user_arr);
						 MooMessage("恭喜您，验证成功！", "index.php?n=myaccount&h=smsindex",'05');	
					}
				}
				$ret = get_auth_string($seqNo,$userCode,$userName,$msisdn);
				include MooTemplate('public/myaccount_sfzsms', 'module');
				
		    }else{//高级会员认证免费接口
			    $tagValue="";
		    	$result=$_MooClass['MooMySQL']->getOne("select sms from {$dbTablePre}certification where uid=$uid");
		    	if($result['sms']==1){
		    		MooMessage("您已经通过了身份通的验证，无需再次验证！", "index.php?n=myaccount",'05');	
		    	}
	
		        include_once "module/myaccount/SynPlatAPI.php";
		        $SynPlatAPI=new SynPlatAPI;
		        $userCode = trim($_POST['userCode']);//身份证号
				$userName = trim($_POST['userName']);//用户名
				//$userName = iconv("utf-8","gbk",$userName);//转换编码
				$param=$userName.",".$userCode;
		        $returnXml=$SynPlatAPI->getData($param);
		        //echo $userName.'and'.$userCode;
                // echo $returnXml;exit;
		        $realname=$SynPlatAPI->getXmlValueByTag($returnXml,'name');
		        $idcode=$SynPlatAPI->getXmlValueByTag($returnXml,'identitycard');
		        
		        $tagValue=$SynPlatAPI->getXmlValueByTag($returnXml,'compResult');
                
		        if ($tagValue=="一致"){
		        	//allotserver($uid);
					//更新身份认证状态
		            $_MooClass['MooMySQL']->query("update {$dbTablePre}certification set sms=1 where uid=$uid");
		            
					if(!$_MooClass['MooMySQL']->affectedRows()){
						$_MooClass['MooMySQL']->query("INSERT INTO {$dbTablePre}certification (uid,sms) VALUES('$uid','1')");
					}
					
					if(MOOPHP_ALLOW_FASTDB){
						$val = $_MooClass['MooMySQL']->getOne("select * from {$dbTablePre}certification where uid=$uid  limit 1");
						$val['sms'] = 1;
						MooFastdbUpdate('certification','uid',$uid, $val); //!!
					}
					//保存资料
					 
					 $mobile = $user_arr['mobile']; //手机号
					 $time = time();
					 $sql = "insert into {$dbTablePre}smsauths set uid={$uid},time='{$time}',realname='{$realname}',idcode='{$idcode}',mobile='{$mobile}'";
					 $_MooClass['MooMySQL']->query($sql);
					 //重新设置诚信值
					 reset_integrity($user_arr);
					 MooMessage("恭喜您，验证成功！", "index.php?n=myaccount",'05');	
		        }elseif($tagValue=="不一致"){
		        	MooMessage("卡号不一致，验证失败！", "index.php?n=myaccount&h=smsindex",'03');	
		        	include MooTemplate('public/myaccount_credit_wrong', 'module');exit;
		        	
		        }elseif(preg_match('/库中无此号/',$tagValue)){
		        	MooMessage("库中无此卡号，验证失败！", "index.php?n=myaccount&h=smsindex",'03');	
		        	include MooTemplate('public/myaccount_credit_wrong', 'module');exit;
		        	
		        }else{
		        	MooMessage("验证失败，请重新验证！！！", "index.php?n=myaccount&h=smsindex",'01');	
		        	
		        }
		        
		        
		    }
		   
		    
		}else{
			myaccount_smsindex();		
		}
		break;
	//note 邮箱认证
	case "emailindex" :
		if(MooSubmit('emailindexsubmit')){
			if (!$usercer) {
				$insertsqlarr = array(
					'uid' => $uid
				);
				inserttable('certification', $insertsqlarr);	
			} else {
				if ($usercer['email'] == 'yes'){
					MooMessage("您已经验证过邮箱了！", "index.php?n=myaccount&h=emailindex","03");
				}
			}
			$verifycode = md5($timestamp);
			//note 用户密码
			//$userMsg = $_MooClass['MooMySQL']->getOne("SELECT password FROM {$dbTablePre}members WHERE uid = '$uid'");
			$userMsg = $user_arr;
			$email_message = "点击验证：<a href='".MOOPHP_HOST."/index.php?n=myaccount&h=verifyemail&t=".$userMsg['password']."&uid=".$uid."&rand=".time()."&verifycode=".$verifycode."'>".MOOPHP_HOST."/index.php?n=myaccount&h=verifyemail&t=".$userMsg['password']."&uid=".$uid."&rand=".time()."&verifycode=".$verifycode."</a>";		
			//note 会员邮箱
			//$userMsg = $_MooClass['MooMySQL']->getOne("select username from {$dbTablePre}members where uid='$uid'");
			
			$ToAddress = $userMsg['username'];
			$ToSubject = "请完成真爱一生网邮箱验证";
			$ToBody = $email_message;
			//发送邮件
			$end = MooSendMail($ToAddress,$ToSubject,$ToBody,'',true,$uid);
			if(!$end) {
				MooMessage("发送失败！返回至邮箱验证", "index.php?n=myaccount&h=emailindex","01");
			} else {
				$updatesqlarr = array(
					'email' => $verifycode
				);
				$wheresqlarr = array(
					'uid' => $uid
				);
				updatetable('certification', $updatesqlarr, $wheresqlarr);
				if(MOOPHP_ALLOW_FASTDB){
					$val = array();
					$val['email'] = $verifycode;
					MooFastdbUpdate('certification','uid',$uid, $val); //!!
				}
				MooMessage("发送成功！请前往您的邮箱完成邮箱验证", "index.php?n=myaccount&h=emailindex","05");
			}
		}else{
			myaccount_emailindex();	
		}
		break;
	//note 邮箱认结果证提示信息
	case "verifyemail" :
		$vuid = MooGetGPC('uid', 'integer');
		$verifycode = MooGetGPC('verifycode', 'string');
		$rand = MooGetGPC('rand', 'integer');
	    $pwd = MooGetGPC('t','string',G);
		
		if((time() - 3600) > $rand){
			MooMessage("验证超时，请重新验证！", "index.php?n=myaccount&h=emailindex",'01');
		}
		$sql="SELECT uid FROM {$dbTablePre}certification WHERE `uid`='$vuid' AND `email`='$verifycode'";

		$userver = $_MooClass['MooMySQL']->getOne($sql);
		if (!empty($userver)) {
			$updatesqlarr = array(
				'email' => 'yes'
			);
			$wheresqlarr = array(
				'uid' => $vuid
			);
			updatetable('certification', $updatesqlarr, $wheresqlarr);
			
			if(MOOPHP_ALLOW_FASTDB){
				$val = array();
				$val['email'] = 'yes';
				MooFastdbUpdate('certification','uid',$vuid, $val);
			}
			//verifyemail_login($uid,$pwd);
			//note 分配客服              
            //allotserver($vuid);
			//更新诚信值
			reset_integrity($vuid);
			MooMessage("验证成功！", "index.php?n=myaccount","05");
			exit;
		} else {
			MooMessage("验证失败！", "index.php?n=myaccount&h=emailindex",'01');
		}
		break;	
	//note 视频认证
	case "videoindex" :
		myaccount_videoindex();
		break;
	//note 证件认证
	case "convinceindex" :
		if(MooSubmit('convinceindexsubmit')){
		    if(!$_FILES['fileData1']['tmp_name']){
			 
			   MooMessage("请上传证件！", "index.php?n=myaccount&h=convinceindex","03");
			 }
		
			$field_arr = array(
				1 => 'identity',
				2 => 'marriage',
				3 => 'education',
				4 => 'occupation',
				5 => 'salary',
				6 => 'house'
			);
			$phototype = MooGetGPC('phototype', 'integer');
			if (!$usercer) {
				$insertsqlarr = array(
					'uid' => $uid
				);
				inserttable('certification', $insertsqlarr);	
			} else {
				if ($usercer[$field_arr[$phototype]] != ''&&$usercer[$field_arr[$phototype].'_check']==3){
					MooMessage("您已经上传过此类证件！", "index.php?n=myaccount&h=convinceindex","03");
				}
			}
			
			
			//note 判断上传的文件类型
			$flag = '';
			$true_type = file_type($_FILES['fileData1']['tmp_name']);
			$extname = strtolower(substr($_FILES['fileData1']['name'],(strrpos($_FILES['fileData1']['name'],'.')+1)));
			
			$images = array('jpg', 'jpeg', 'gif', 'png', 'bmp','JPG','JPEG','GIF','PNG','BMP');
			$img_type = explode('/',$_FILES['fileData1']['type']);
			//foreach($images as $v) {
				//if(eregi($v,$_FILES['fileData1']['type']) 
				if(in_array($img_type[1], $images) && in_array($extname,$images)
				&& ('image/'.$true_type == $_FILES['fileData1']['type'] 
					|| 'image/p'.$true_type == $_FILES['fileData1']['type'] 
					|| 'image/x-'.$true_type == $_FILES['fileData1']['type'] ) ) {
					$flag = 1;
					//break;
				}
			//}
			if($flag != 1) {
				$notice = "照片必须为BMP，JPG，PNG或GIF格式";
				MooMessage($notice,'index.php?n=myaccount&h=convinceindex',"02");
				exit();
			}
			//note 设定用户上传图片大小限制 最小20480字节 = 20k 最大419430字节 = 400K
			$minfilesize = 20480;
			$maxfilesize = 1024000;
			$filesize = $_FILES['fileData1']['size'];
			if($filesize > $maxfilesize ) {
				$notice = "上传证件照片大小不得超过1000KB";
				MooMessage($notice,'index.php?n=myaccount&h=convinceindex',"02");
				exit();
			}
			if($filesize < $minfilesize) {
				$notice = "上传证件照片大小不得小于20KB";
				MooMessage($notice,'index.php?n=myaccount&h=convinceindex',"02");
				exit();
			}
			
			//note 上传到指定目录，并且获得上传后的文件描述	
			$upload = MooAutoLoad('MooUpload');
			$upload->config(array(
				'targetDir' => 'attachments/',
				'saveType' =>'1'
			));
			$file = $upload->saveFiles('fileData1');			
			$updatesqlarr = array(
				$field_arr[$phototype] => $file[0]['path'].$file[0]['name'].".".$file[0]['extension'],$field_arr[$phototype].'_check'=>2
			);
			$wheresqlarr = array(
				'uid' => $uid
			);
			//echo $phototype;
			//print_r($updatesqlarr);
			//exit;
			updatetable('certification', $updatesqlarr, $wheresqlarr);
			if(MOOPHP_ALLOW_FASTDB){
				$val = array();
				$val[$field_arr[$phototype]] = $file[0]['path'].$file[0]['name'].".".$file[0]['extension'];
				$val[$field_arr[$phototype].'_check'] = 2;
				MooFastdbUpdate('certification','uid',$uid, $val); //!!
			}
			//note 分配客服              
            //allotserver($uid);
			MooMessage("上传成功！", "index.php?n=myaccount&h=convinceindex","05");
		}else{
			myaccount_convinceindex();		
		}
		break;
	//note 证件预览
	case "convince_preview" :
		$path = MooGetGPC('path', 'string');
		myaccount_convince_preview($path);
		break;
	//note 真爱一生网使用条款协议
	case "serverrule" :
		include MooTemplate('public/myaccount_serverrule', 'module');
		break;
	//note 隐私政策条款
	case "intimacy"	:
		include MooTemplate('public/myaccount_intimacy', 'module');
		break;	
    case "openpic":
    	myaccount_openpic();
    	break;
	//拍照流程
	case "phelp":
		myaccount_phelp();
		break;
	//视频流程
	case "vhelp":
		myaccount_vhelp();
		break;
	//录音流程
	case "rhelp":
		myaccount_rhelp();
		break;
	//找回密码
	case "resetpwd":
    	resetpwd();
    	break;
	//视频范例
	case "speak":
		myaccount_speak();
		break;
	default :
		myaccount_index();
		//myaccount_accountinfo_view();
		break;
}
?>

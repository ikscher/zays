<?php
include "module/andriod/function.php";


//note 发送新的秋波
function sendnewleer() {

	global $_MooClass,$dbTablePre,$timestamp,$user_arr,$serverid,$memcached,$_MooCookie,$GLOBALS;
	//是否合法登录
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
	$sendtoid = MooGetGPC('sendtoid','integer','P');
	$user_arr = MooMembersData($userid);
    $uid = $userid;
    $return = array();
	$is_first_send = true;
	$query = $_MooClass['MooMySQL']->getOne("SELECT count(1) as c FROM {$dbTablePre}pic WHERE uid = '$sendtoid'");
	$pic_total = $query['c'];
	//note 自己不能给自己发送秋波，直接转到秋波列表页面
	if($sendtoid == $userid){
		$error = "自己不可以给自己发过送秋波";
		echo return_data($error,false);exit;
	}
    $result=$_MooClass['MooMySQL']->getOne("select groupid from web_admin_user where uid='{$serverid}'");
    $groupid=$result['groupid'];
    //系统管理员权限
    $GLOBALS['system_admin'] = array(60);
    if(in_array($groupid,$GLOBALS['system_admin'])){
        $serverid=null;
    }
    if($serverid && $user_arr['usertype']!=3){//只能模拟全权会员
    	$error = "对不起您不能模拟操作";
    	echo return_data($error,false);exit;
    }
    //note 双方屏蔽不给操作
    if(MooGetScreen($userid,$sendtoid)){
    	$error = "因特殊原因，送秋波失败";
    	echo return_data($error,false);exit;

    }
	//note 要做性别过滤，异性的发送秋波，直接转到秋波列表页面
	$send_user1 = leer_send_user1($sendtoid);
	$user = leer_send_user1($userid);
	if($send_user1['gender'] == $user['gender']) {
		$error = "同性之间不可互发秋波";
		echo return_data($error,false);exit;
		exit;
	}
	if(isset($_POST['sendleer'])&&$_POST['sendleer']){
	    $leer = $_MooClass['MooMySQL']->getOne("SELECT * FROM {$dbTablePre}service_leer WHERE receiveuid = '$sendtoid' AND senduid = '$userid' ",true);
	    if($leer['lid']) {
	    	    $is_first_send = false;
	           $lid = $leer['lid'];
	           //note 如果已经发送过秋波，就增加发送秋波的次数
	           $_MooClass['MooMySQL']->query("UPDATE {$dbTablePre}service_leer SET num = num + 1,receivenum = receivenum + 1,sendtime = '$timestamp',receivetime = '$timestamp',receive_del = '0' WHERE lid = '$lid'");     
	           //note 如果已经收到这个人的秋波，已经拒绝，现在改变注意，又发送秋波给这个人,拒绝状态2更改为0
	           $_MooClass['MooMySQL']->query("UPDATE {$dbTablePre}service_leer SET stat = '0' WHERE senduid = '$sendtoid' AND receiveuid = '$uid' AND stat = '2'"); 
	    }else {
	        $_MooClass['MooMySQL']->query("INSERT INTO {$dbTablePre}service_leer SET sendtime = '$timestamp',receivetime = '$timestamp',receivenum = '1', num = '1', senduid  = '{$uid}',receiveuid = '$sendtoid'");
	    }
	    //将新注册的会员更新为优质会员
	    if(in_array($user_arr['sid'],array(0,52,123))&&$user_arr['is_well_user']!=1){       
	        update_iswell_user($user_arr['uid']);
	    }
	    //发送短信和邮件
	   include_once("./module/crontab/crontab_config.php");
	   $res            = MooMembersData($sendtoid);
	   $send_user_info = array_merge(MooGetData('members_choice','uid',$uid),  MooMembersData($uid));
	   /*普通会员对全权会员反馈白名单*/
	   if($res['usertype'] == '3' && $user_arr['usertype'] != '3' && !$serverid){
	   	white_list($res['uid'],$user_arr['uid']);
	   }
	   /*客服模拟全权记录*/
	   if($user_arr['usertype'] == '3' && $serverid && $res['usertype']!=3){
	   	$action = '秋波';
	   	fulllog($user_arr['uid'],$serverid,$action,$res);
	   }
	   //头像路径
	   $path = thumbImgPath(2,$send_user_info['pic_date'],$send_user_info['pic_name']);
	   if(file_exists($path)){
	     $img_path = $path;
	   }else{
	        if($send_user_info['gender'] == 1){
	            $img_path = "/public/images/service_nopic_woman.gif";
	        }else{
	            $img_path = "/public/images/service_nopic_man.gif";
	        }
	   }
	   $send_username = $send_user_info['nickname'] ?  $send_user_info['nickname'] : $send_user_info['uid'];//发送者用户名
	   $send_user_grade = $send_user_info['gender'] == 1 ? "女" : "男"; //发送者性别
	   $province = $send_user_info['province'] ? $provice_list[$send_user_info['province']] : '';//省
	   $city = $send_user_info['city'] ? $city_list[$send_user_info['city']] : ''; //市
	   $height = $send_user_info['height'] ? $height_list[$send_user_info['height']] : "未知"; //身高
	   ob_start();
	   $body = ob_get_clean();
	   if($res['usertype']!=3){
	      MooSendMail($res['username'],"真爱一生网系统温馨提示",$body,"",false,$sendtoid);
	   }
	  //每天向同一用户发送多次，短信记录数表只记一次
	   $send_leer_date = isset($leer['sendtime']) ? date("Y-m-d",$leer['sendtime']) : date("Y-m-d");//
	   $today_leer_count =  isset($leer['lid']) ? $leer['num'] + 1 : 1;
	   if(date("Y-m-d") > $send_leer_date){
	        $today_leer_count = 1;
	        $return = "您今天已向该会员发送一次秋波";
	        echo return_data($return);exit;
	   }
	   $sendinfo=MooGetGPC('sendleerinfo','string','P');
	   if(!empty($sendinfo)){
	   	  $sendinfo="对您说：".$sendinfo;
	   }
	   if($res['usertype']!=3){//全权会员不发送短信和彩信
		   if($serverid){ //如果是客服模拟登录
			   	$SMStype=MooGetGPC('selectSMSorCMS','string','P');
			   	if ($SMStype=='SMS' && $res['is_phone']){
			   	 	// SendMsg($res['telphone'],"真爱一生网 用户ID：".$userid.",".$send_user_grade.",已给您发送秋波,".$sendinfo."   请及时把握您的缘分！4006780405");
					 Push_message_intab($sendtoid,$res['telphone'],"秋波","真爱一生网 用户ID：".$userid.",".$send_user_grade.",已给您发送秋波,".$sendinfo."  请及时把握您的缘分！4006780405",$userid);
			   	}elseif($SMStype=='CMS' && $res['is_phone']){
			   	 	 //====发送彩信   begin  ====
			   	 	 //发送人有照片
		              $sql="SELECT uid,telphone FROM {$dbTablePre}members_search where uid='{$userid}' and images_ischeck=1 and pic_num>0";
		              $sendfrom_user_info=$_MooClass['MooMySQL']->getOne($sql,true);
	
		              if($sendfrom_user_info['uid']){
		                  $sendedMMSCount=$memcached->get('hznsimulate'.$serverid);
	                     if(empty($sendedMMSCount)) $sendedMMSCount=0;
	                     if($sendedMMSCount > 10){
	                        MooMessage('您今天累计已经发过10条彩信','index.php?n=service');
	                     }
	                     send_mms_commission($res['telphone'],'leer',$userid);
	                     
	                     $memcached->set('hznsimulate'.$serverid,++$sendedMMSCount,0,28800);
		              }
		                    
		             //====发送彩信 end =====
			   	}
		   }else{//会员真实登录
			   if($res['is_phone']&&$is_first_send&&$res['s_cid']!=40){//第一次发送秋波才发短信提示
				  Push_message_intab($sendtoid,$res['telphone'],"秋波","真爱一生网 用户ID：".$userid.",".$send_user_grade.",已给您发送秋波,".$sendinfo."  请及时把握您的缘分！4006780405",$userid);
			   }
		   }
	   }
	    //提醒所属客服
	    $sid = $user_arr['sid'];
	    $title = '您的会员 '.$user_arr['uid'].' 向 '.$sendtoid.' 发送了秋波';
	    $awoketime = $timestamp+3600;
	    $sql_remark = "insert into {$dbTablePre}admin_remark set sid='{$sid}',title='{$title}',content='{$title}',awoketime='{$awoketime}',dateline='{$timestamp}'";
	    $res = $_MooClass['MooMySQL']->query($sql_remark);
	        
	    $users = & $res;
	    $return = "发送秋波成功";
	    
	    echo return_data($return);exit;
	     MooMessage('发送秋波成功',"index.php?n=service&h=leer&t=sendnewleer&sendtoid={$sendtoid}");
	}else{

	    if($user_arr['gender']==0){//boy
	       $sql = "SELECT id,content  FROM {$dbTablePre}members_sendinfo where type=2 and isShow=1";
	    }else{//girl
	       $sql = "SELECT id,content  FROM {$dbTablePre}members_sendinfo where type=1 and isShow=1";
	    }
	    $sendinfo =  $_MooClass['MooMySQL']->getAll($sql);
	    echo return_data($sendinfo);exit;
	}
	
}


//note 发送新的鲜花
function sendnewfl() {
	global $_MooClass,$dbTablePre,$timestamp,$user_arr,$serverid,$memcached,$_MooCookie,$GLOBALS;
	//是否合法登录
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
	$sendtoid = MooGetGPC('sendtoid','integer','P');
//
	$user_arr = MooMembersData($userid);
    $uid = $userid;
    $return = array();
	$is_first_send = true;
	$query = $_MooClass['MooMySQL']->getOne("SELECT count(1) as c FROM {$dbTablePre}pic WHERE uid = '$sendtoid'");
	$pic_total = $query['c'];
	//note 自己不能给自己发送秋波，直接转到秋波列表页面
	if($sendtoid == $userid){
		$error = "自己不可以给自己发过送鲜花";
		echo return_data($error,false);exit;
	}
    $result=$_MooClass['MooMySQL']->getOne("select groupid from web_admin_user where uid='{$serverid}'");
    $groupid=$result['groupid'];
    //系统管理员权限
    $GLOBALS['system_admin'] = array(60);
    if(in_array($groupid,$GLOBALS['system_admin'])){
        $serverid=null;
    }
    if($serverid && $user_arr['usertype']!=3){//只能模拟全权会员
    	$error = "对不起您不能模拟操作";
    	echo return_data($error,false);exit;
    }
    //note 双方屏蔽不给操作
    if(MooGetScreen($userid,$sendtoid)){
    	$error = "因特殊原因，送鲜花失败";
    	echo return_data($error,false);exit;

    }
	//note 要做性别过滤，异性的发送秋波，直接转到秋波列表页面
	$send_user1 = leer_send_user1($sendtoid);
	$user = leer_send_user1($userid);
	if($send_user1['gender'] == $user['gender']) {
		$error = "同性之间不可互发鲜花";
		echo return_data($error,false);exit;
		exit;
	}
	if($user['rosenumber'] <= 0) {
		$error = '您没有鲜花了，获取更多鲜花';
		echo return_data($error,false);exit;
	}
//	
	
	
	
	//发送短信和邮件
	include_once("./module/crontab/crontab_config.php");
	$res            = MooMembersData($sendtoid);
	$send_user_info = array_merge(MooGetData('members_choice','uid',$userid),MooMembersData($userid));
	
	//头像路径
	$path = thumbImgPath(2,$send_user_info['pic_date'],$send_user_info['pic_name'],$send_user_info['gender']);
	if(file_exists($path)){
	 $img_path = $path;
	}else{
		if($send_user_info['gender'] == 1){
			$img_path = "/public/images/service_nopic_woman.gif";
		}else{
			$img_path = "/public/images/service_nopic_man.gif";
		}
	}
	
	$send_username = $send_user_info['nickname'] ?  $send_user_info['nickname'] : $send_user_info['uid'];//发送者用户名
	$send_user_grade = $send_user_info['gender'] == 1 ? "女" : "男"; //发送者性别
	$province = $send_user_info['province'] ? $provice_list[$send_user_info['province']] : '';//省
	$city = $send_user_info['city'] ? $city_list[$send_user_info['city']] : ''; //市
	$height = $send_user_info['height'] ? $height_list[$send_user_info['height']] : "未知"; //身高
	ob_start();
	//require_once MooTemplate('public/mail_space_rosetpl', 'module'); //模板
	$body = ob_get_clean();
    
	if($res['usertype']!=3){
	  MooSendMail($res['username'],"真爱一生网系统温馨提示",$body,"",false,$sendtoid);
	}
	if(empty($GLOBALS['MooUid'])){
	list($uid, $password) = explode("\t", MooAuthCode($_MooCookie['auth'], 'DECODE'));
		$uid = intval($uid);
	}else{
		$uid = $GLOBALS['MooUid'];
	}
	//if(empty($uid)){
	//	MooMessage('您还没有登录','index.php?n=login');
	//}

	$leer = $_MooClass['MooMySQL']->getOne("SELECT * FROM {$dbTablePre}service_rose WHERE receiveuid = '$sendtoid' AND senduid = '$userid' ORDER BY rid DESC LIMIT 1");
	//库中验证鲜花数
	//$rosenum_check =  $GLOBALS['_MooClass']['MooMySQL']->getOne("SELECT rosenumber FROM {$GLOBALS['dbTablePre']}members_base  WHERE `uid`='{$uid}'  LIMIT 1",true);
	//if($rosenum_check['rosenumber'] > 0) {	
	    if($leer['rid']) {
			$is_first_send = false;
			$rid = $leer['rid'];
			//note 如果已经发送过玫瑰，就增加发送玫瑰的次数
			$_MooClass['MooMySQL']->query("UPDATE {$dbTablePre}service_rose SET num = num + 1, receivenum = receivenum + 1,sendtime = '$timestamp',receivetime='$timestamp',receive_del=0,send_del=0 WHERE rid = '$rid'");
		}else {
			//note 发送新的玫瑰，写入数据库 发送者，接受者，发送时间
			$_MooClass['MooMySQL']->query("INSERT INTO {$dbTablePre}service_rose SET num = 1, receivenum = 1, sendtime = '$timestamp',receivetime='$timestamp',senduid  = '{$uid}',receiveuid = '$sendtoid' ");
		}
			
		//note 发送一朵玫瑰，自己就要减少一朵玫瑰
		$_MooClass['MooMySQL']->query("UPDATE {$dbTablePre}members_base SET rosenumber = rosenumber - 1 WHERE uid = '{$uid}'");
		if(MOOPHP_ALLOW_FASTDB){
			$user_rosenum =  $GLOBALS['_MooClass']['MooMySQL']->getOne("SELECT rosenumber FROM {$GLOBALS['dbTablePre']}members_base  WHERE `uid`='{$uid}'  LIMIT 1",true);
			$value['rosenumber']= $user_rosenum['rosenumber'];
			MooFastdbUpdate('members_base','uid',$userid,$value);
		}
	//}
	
	
	//将新注册的会员更新为优质会员
	if(in_array($user_arr['sid'],array(0,52,123))&&$user_arr['is_well_user']!=1){		
		update_iswell_user($user_arr['uid']);
	}

	//每天向同一用户发送多次，短信记录数表只记一次
	$send_rose_date = isset($leer['sendtime']) ? date("Y-m-d",$leer['sendtime']) : date("Y-m-d");//
	$today_rose_count =  isset($leer['rid']) ? $leer['num'] + 1 : 1;
	if(date("Y-m-d") > $send_rose_date){
		$today_rose_count = 1;
	}
    
	$sendinfo=MooGetGPC('sendinfo','string','G');
	if (!empty($sendinfo)){
		$sendinfo=",对您说：".$sendinfo;
	}
	
	if($res['usertype']!=3){
		if($serverid){//客服模拟会员登录可以选择发送短信或彩信
		        $SMStype=MooGetGPC('selectSMSorCMS','string','G');
	            if ($SMStype=='SMS' && $res['is_phone']){
	                 Push_message_intab($sendtoid,$res['telphone'],"鲜花","真爱一生网 用户ID：".$userid.",".$send_user_grade.",已给您发送鲜花".$sendinfo."     请及时把握您的缘分！4006780405",$userid);
	            }elseif($SMStype=='CMS' && $res['is_phone']){
	                 //====发送彩信   begin  ====
	                 //发送人有照片
	                  $sql="SELECT uid,telphone FROM {$dbTablePre}members_search where uid='{$userid}' and images_ischeck=1 and pic_num>0";
	                  $sendfrom_user_info=$_MooClass['MooMySQL']->getOne($sql,true);
	                  
	                  //发送条件：本站注册会员，有电话号码的，开启短信通知的会员发送
	                 /* $sql="SELECT telphone FROM {$dbTablePre}members where uid='{$sendtoid}'";// and is_phone=1 and telphone!='' and usertype=1";
	                  $sendto_user_info=$_MooClass['MooMySQL']->getOne($sql);*/
	
	                  if($sendfrom_user_info['uid']){
	                  	 $sendedMMSCount=$memcached->get('hznsimulate'.$serverid);
	                     if(empty($sendedMMSCount)) $sendedMMSCount=0;
	                     //if($sendedMMSCount > 10){
	                     //	MooMessage('您今天累计已经发过10条彩信','index.php?n=service&h=rose');
	                     //}
	                     send_mms_commission($res['telphone'],'rose',$userid);
	                     
	                     
	                     $memcached->set('hznsimulate'.$serverid,++$sendedMMSCount,0,28800);
	                  }
	                        
	                 //====发送彩信 end =====
	            }
		}else{//真实会员登录
			if($res['is_phone']&&$is_first_send&&$res['s_cid']!=40){//第一次发才短信提示
			  // SendMsg($res['telphone'],"真爱一生网 用户ID：".$userid.",".$send_user_grade.",已给您发送鲜花".$sendinfo."     请及时把握您的缘分！4006780405");
			  Push_message_intab($sendtoid,$res['telphone'],"鲜花","真爱一生网 用户ID：".$userid.",".$send_user_grade.",已给您发送鲜花".$sendinfo."     请及时把握您的缘分！4006780405",$userid);
			}
		}
	}
	/*
	$week_time = 24*3600*7;//一周时间秒数
	$interval_time = $timestamp - $user_arr['last_login_time'];//当前时间-最后登录时间
	$date1 = date("Y-m-d",strtotime("last Monday"));
	$date2 = date("Y-m-d",strtotime("Sunday"));
	
	//echo "interval_time:".$interval_time . ' and '.'week:'.$week_time;exit;
    
	if($interval_time > $week_time){//不活跃用户每周发一条短信
		 $_MooClass['MooMySQL']->query("INSERT INTO {$dbTablePre}today_send SET uid = '".$sendtoid."', sid = '".$userid."',phone = '".$res['telphone']."',sendtime = '".date("Y-m-d")."'" );
		 $cos = $_MooClass['MooMySQL']->getOne("select count(*) as c from {$dbTablePre}today_send where uid='$sendtoid'  and sendtime>='$date1' and sendtime<='$date2'");
		 if($cos[c] <= 1){
			//fanglin暂时屏蔽
			Push_message_intab($sendtoid,$res['telphone'],"鲜花","真爱一生网 用户ID：".$userid.",".$send_user_grade.",已给您发送鲜花,请及时把握您的缘分！4006780405",$userid);
		 }
	}else{
		  //echo $today_rose_count;exit;
		  if($send_rose_date==date("Y-m-d") && $today_rose_count == 1){	//每天同一个用户发送多次秋波，短信只记一次
			   $cos = $_MooClass['MooMySQL']->getOne("select count(*) as c from {$dbTablePre}today_send where uid='$sendtoid' and sendtime='".date("Y-m-d")."'");
			   
               //print_r($cos);exit;
			   if($cos[c]<5){
				  $_MooClass['MooMySQL']->query("INSERT INTO {$dbTablePre}today_send SET uid = '".$sendtoid."', sid = '".$userid."',phone = '".$res['telphone']."',sendtime = '".date("Y-m-d")."'" );
				  //fanglin暂时屏蔽
				  Push_message_intab($sendtoid,$res['telphone'],"鲜花","真爱一生网 用户ID：".$userid.",".$send_user_grade.",已给您发送鲜花,请及时把握您的缘分！4006780405",$userid);
			   }
		}
	}
    */
	
	
	/*
	//发送彩信
	$sql="SELECT uid FROM {$dbTablePre}members where uid='{$uid}' and mainimg!='' and images_ischeck=1 and pic_num>0";
	//$sql="SELECT uid FROM {$dbTablePre}members where uid='{$uid}'";
	$sendout_user_info=$_MooClass['MooMySQL']->getOne($sql);
	//发送人有照片
	$sql="SELECT telphone FROM {$dbTablePre}members where uid='{$sendtoid}' and is_phone=1 and telphone!='' and usertype=1";
	$sendto_user_info=$_MooClass['MooMySQL']->getOne($sql);
	//发送条件：本站注册会员，有电话号码的，开启短信通知的会员发送
    
	//echo $sendto_user_info['telphone'].' and '.$sendout_user_info['uid'];exit;
	if($sendto_user_info['telphone']&&$sendout_user_info['uid']){
		//echo 'ffff';exit;
		if(send_mms_commission($sendto_user_info['telphone'],'rose',$sendout_user_info['uid'])){
			$note="。";
		}else{
			$note="！";
		}
	}
	
	*/
	/*普通会员对全权会员反馈白名单*/
	if($res['usertype'] == '3' && $user_arr['usertype'] != '3' && !$serverid){
		white_list($res['uid'],$user_arr['uid']);
	}
	/*客服模拟全权记录*/
	if($user_arr['usertype'] == '3' && $serverid && $res['usertype'] != '3'){
		$action = '鲜花';
		fulllog($user_arr['uid'],$serverid,$action,$res);
	}
	//提醒所属客服
	$sid = $user_arr['sid'];
	$title = '您的会员 '.$user_arr['uid'].' 向 '.$sendtoid.' 发送了鲜花';
	$awoketime = $timestamp+3600;
	$sql_remark = "insert into {$dbTablePre}admin_remark set sid='{$sid}',title='{$title}',content='{$title}',awoketime='{$awoketime}',dateline='{$timestamp}'";
	$res = $_MooClass['MooMySQL']->query($sql_remark);
	$return = "发送鲜花成功";
	    
	echo return_data($return);exit;
	//MooMessage('发送鲜花成功',"index.php?n=service&h=rose&t=isendrose","05");
	//require MooTemplate('public/service_rose_sendrose', 'module');
}


/*
 * *****************************************控制层**********************************/



$l = $_GET['l'] = empty($_GET['l'])? '' : $_GET['l'];

switch ($l){
	case 'sendnewleer':
		sendnewleer();
		break;
	case 'sendnewfl':
		sendnewfl();
		break;
	default:
		sendnewleer();
		break;	
}
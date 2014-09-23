<?php
include "module/andriod/function.php";

/**
 * 
 * 返回邮件的上一封，下一封邮件ID
 * @param unknown_type $s_id 本邮件id
 * @param unknown_type $where 部分查询条件
 * @param unknown_type $s_time 邮件发送时间
 * return array($upid,$nextid)
 */
function getUpNextId($s_id,$where,$s_time){
	global $dbTablePre,$_MooClass;
	$return = array();
	$upid   = $_MooClass['MooMySQL']->getOne("SELECT s_id FROM {$dbTablePre}services WHERE $where and s_time >$s_time order by s_time asc limit 1");
	$nextid = $_MooClass['MooMySQL']->getOne("SELECT s_id FROM {$dbTablePre}services WHERE $where and s_time <$s_time order by s_time desc limit 1");
	$return[] = $upid ? $upid['s_id'] : $s_id;
	$return[] = $nextid ? $nextid['s_id'] : $s_id;
	return $return;
}


//note 所有消息（详细）
function allmessage_detail($contentid){
	global $_MooClass,$dbTablePre,$userid,$user_arr,$memcached;
	$and_uuid = isset($_GET['uuid'])? $_GET['uuid'] : '';
	$uid =  isset($_GET['uid'])?$_GET['uid']:'';
	if($uid){
		$userid = $mem_uid = $memcached->get('uid_'.$uid);
	}
	$uuid = $memcached->get('uuid_'.$userid);
	//$error[] = array("getand_uuid"=>$and_uuid,"getuid"=>$uid,"userid"=>$userid,"mem_uuid"=>$uuid);
	$checkuuid = check_uuid($and_uuid,$userid);
    if(!$checkuuid){
    	$error = "uuid_error";
    	echo return_data($error,false);exit;	
    }
	$user_arr = MooMembersData($userid);
	//note 显示查询的内容	
	$memail = $_MooClass['MooMySQL']->getOne("SELECT * FROM {$dbTablePre}services WHERE s_id = '$contentid' and flag = '1' and s_uid_del='0'");
	//如果是本站注册会员
	if($user_arr['usertype'] == 1 && $memail['s_cid'] != 3){
			$m_level = get_userrank($userid);//会员等级
			if($m_level != 1){ //不是高级会员
				if($user_arr['gender'] == 0){//男性
						if(!checkIsOver($userid)){ //过了试用期
							$sql = "select count(*) as c from " . $dbTablePre . "services where s_status = '1'  and s_uid = " . $userid . " and read_time = '" . date("Y-m-d") . "'";
							$arr = $_MooClass['MooMySQL']->getOne($sql,true);
							$today_read_count = $arr['c'];
							if($today_read_count > 5){
								$error = "对不起，您不是钻石或高级会员，每天只可以看5条电子邮件";
								echo return_data($error,false);exit;
							}
						}
				}
			}
	}
	//note 更新已经阅读状态
	if(!$memail['s_status']){
		$_MooClass['MooMySQL']->query("UPDATE {$dbTablePre}services SET s_status = '1',read_time='".date('Y-m-d')."' WHERE s_id = '$contentid'");
	}
	//note 发件人的昵称或ID
	if($memail['s_fromid'] == 0){
		$fromname = '真爱一生网';
		//note 当前会员昵称		
		$uname = $user_arr['nickname']?$user_arr['nickname']:'ID:'.$user_arr['uid'];
	}else{
		$from_name = $_MooClass['MooMySQL']->getOne("select uid,nickname from {$dbTablePre}members_search where uid='".$memail['s_fromid']."'");
		$fromname = $from_name['nickname']?$from_name['nickname']:'ID '.$from_name['uid'];
		$uname = $user_arr['nickname']?$user_arr['nickname']:'ID:'.$user_arr['uid'];
	}
	$memail['s_content'] = str_replace("<br>", "\r\n", $memail['s_content']);
	$memail['fromname'] = $fromname;
	$memail['username'] = $uname;
	$h = stripos($memail['s_content'], "会员号");
	if($h !== false){
		$from_uid = substr($memail['s_content'], $h+12,7);
		$memail['from_uid'] = $from_uid;
	}
	$memail["s_time"] = date("Y-m-d H:i:s",$memail["s_time"]);
	echo return_data($memail);exit;
	
}


//note 所有消息
function allmessage_list(){
	global $_MooClass,$dbTablePre,$userid,$pagesize,$user_arr,$memcached;
	$uid =  $_GET['uid'] = isset($_GET['uid'])?$_GET['uid']:'';
	if($uid){
		$userid = $mem_uid = $memcached->get('uid_'.$uid);
	}
	$and_uuid = isset($_GET['uuid'])? $_GET['uuid'] : '';
	$checkuuid = check_uuid($and_uuid,$userid);
    if(!$checkuuid){
    	$error = "uuid_error";
    	echo return_data($error,false);exit;	
    }
    $user_arr = MooMembersData($userid);
	$allmail = array();
	//note 获得当前url
	$currenturl = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"]; 
	$currenturl2 = preg_replace("/(&page=\d+)/","",$currenturl);
	//note 获得第几页
	$pagesize = 10;
	$page = empty($_GET['page']) ? 1 : intval($_GET['page']);
	//note limit查询开始位置
	$start = ($page - 1) * $pagesize;
	//note 显示数据
	$total1 = 0;
	$ret_count = $_MooClass['MooMySQL']->getOne("SELECT count(s_uid) as c FROM {$dbTablePre}services WHERE s_uid = '$userid' and flag = '1' and s_uid_del='0'",true);
	$total = $ret_count['c'];//2009-11-22日修改(得到总数)
	$ret_count1 = $_MooClass['MooMySQL']->getOne("SELECT count(s_uid) as c FROM {$dbTablePre}services WHERE s_uid = '$userid' and flag = '1' and s_uid_del='0' and s_status = '0' ",true);
	$total1 = $ret_count1['c'];//2009-11-22日修改(得到总数)
	if($total){
		$allmail = $_MooClass['MooMySQL']->getAll("SELECT * FROM {$dbTablePre}services WHERE s_uid = '$userid' and flag = '1' and s_uid_del='0' order by s_time desc LIMIT $start,$pagesize");
	}
    if(empty($allmail)){
    	$empty = "empty";
    	echo return_data($empty);exit;
    }
	$al = array();
	foreach ($allmail as $key=>$value){
		$al = $value;
		$al['s_time'] = date("Y-m-d",$value["s_time"]);
		$al['s_content'] = '';
		if ($value['s_fromid'] != '0' && $value['s_cid'] != 50){
			$fromname = getfromname($value['s_fromid']);
			if(empty($fromname)){
			$fromname = 'ID '.$value['s_fromid'];
		    }
		}else{
			$fromname = '真爱一生网';
		}
	
		$al['nickname'] = $fromname;
		
		$mails[] = $al;
	}
	$shadow_see = array();
	$shadow_see['content'] = $mails;
	$shadow_see['count'] = $total1;
	echo return_data($shadow_see);exit;


}

//note 邮件记录，最多10条
function shadow_talks(){
	global $_MooClass,$dbTablePre,$userid,$pagesize,$user_arr,$memcached;
	$uid =  $_GET['uid'] = isset($_GET['uid'])?$_GET['uid']:'';
	if($uid){
		$userid = $mem_uid = $memcached->get('uid_'.$uid);
	}
	$and_uuid = isset($_GET['uuid'])? $_GET['uuid'] : '';
	$checkuuid = check_uuid($and_uuid,$userid);
    if(!$checkuuid){
    	$error = "uuid_error";
    	echo return_data($error,false);exit;	
    }
    //$user_arr = MooMembersData($userid);
    $otheruid = isset($_GET['otheruid'])? $_GET['otheruid'] : '0';
    $in = '('.$userid.','.$otheruid.')';
    
    
    
	$allmail = array();
	
	$ret_count = $_MooClass['MooMySQL']->getOne("SELECT count(s_uid) as c FROM {$dbTablePre}services WHERE s_uid in $in and s_fromid in $in and flag = '1' and s_uid_del='0' LIMIT 10",true);
	$total = $ret_count['c'];//2009-11-22日修改(得到总数)
	
	if($total){
		$allmail = $_MooClass['MooMySQL']->getAll("SELECT * FROM {$dbTablePre}services WHERE s_uid in $in and s_fromid in $in and flag = '1' and s_uid_del='0' order by s_time desc LIMIT 10");
	}
    if(empty($allmail)){
    	$empty = "empty";
    	echo return_data($empty);exit;
    }
	$al = array();
	foreach ($allmail as $key=>$value){
		$al = $value;
		$al['s_time'] = date("Y-m-d",$value["s_time"]);
		if ($value['s_fromid'] != '0' && $value['s_cid'] != 50){
			$fromname = getfromname($value['s_fromid']);
			if(empty($fromname)){
			$fromname = 'ID '.$value['s_fromid'];
		    }
		}else{
			$fromname = '真爱一生网';
		}
	
		$al['fromnickname'] = $fromname;
		
		if ($value['s_uid'] != '0' && $value['s_cid'] != 50){
			$toname = getfromname($value['s_uid']);
			if(empty($fromname)){
			$toname = 'ID '.$value['s_uid'];
		    }
		}else{
			$toname = '真爱一生网';
		}
	
		$al['tonickname'] = $toname;
		
		$mails[] = $al;
	}
	
	echo return_data($mails);exit;


}

//note 所有已发消息
function allmessage_list_send(){
	global $_MooClass,$dbTablePre,$userid,$pagesize,$user_arr,$memcached;
	$uid =  $_GET['uid'] = isset($_GET['uid'])?$_GET['uid']:'';
	if($uid){
		$userid = $mem_uid = $memcached->get('uid_'.$uid);
	}
	$and_uuid = isset($_GET['uuid'])? $_GET['uuid'] : '';
	$checkuuid = check_uuid($and_uuid,$userid);
    if(!$checkuuid){
    	$error = "uuid_error";
    	echo return_data($error,false);exit;	
    }
    $user_arr = MooMembersData($userid);
	$allmail = array();
	//note 获得当前url
	$currenturl = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"]; 
	$currenturl2 = preg_replace("/(&page=\d+)/","",$currenturl);
	//note 获得第几页
	$pagesize = 10;
	$page = empty($_GET['page']) ? 1 : intval($_GET['page']);
	//note limit查询开始位置
	$start = ($page - 1) * $pagesize;
	//note 显示数据
	$ret_count = $_MooClass['MooMySQL']->getOne("SELECT count(s_uid) as c FROM {$dbTablePre}services WHERE s_fromid = '$userid' and flag = '1' and s_fromid_del='0'",true);
	$total = $ret_count['c'];//2009-11-22日修改(得到总数)
	if($total){
		$allmail = $_MooClass['MooMySQL']->getAll("SELECT * FROM {$dbTablePre}services WHERE s_fromid = '$userid' and flag = '1' and s_fromid_del='0' order by s_time desc LIMIT $start,$pagesize");
	}
    if(empty($allmail)){
    	$empty = "empty";
    	echo return_data($empty);exit;
    }
	$al = array();
	foreach ($allmail as $key=>$value){
		$al = $value;
		$al['s_time'] = date("Y-m-d",$value["s_time"]);
		$al['s_content'] = '';
		if ($value['s_uid'] != '0' && $value['s_cid'] != 50){
			$fromname = getfromname($value['s_uid']);
			if(empty($fromname)){
			$fromname = 'ID '.$value['s_uid'];
		    }
		}else{
			$fromname = '真爱一生网';
		}
	
		$al['nickname'] = $fromname;
		
		$mails[] = $al;
	}

	echo return_data($mails);exit;


}

//note 已发消息（详细）
function allmessage_detail_send($contentid){
	global $_MooClass,$dbTablePre,$userid,$user_arr,$memcached;
	$and_uuid = isset($_GET['uuid'])? $_GET['uuid'] : '';
	$uid =  isset($_GET['uid'])?$_GET['uid']:'';
	if($uid){
		$userid = $mem_uid = $memcached->get('uid_'.$uid);
	}
	$uuid = $memcached->get('uuid_'.$userid);
	//$error[] = array("getand_uuid"=>$and_uuid,"getuid"=>$uid,"userid"=>$userid,"mem_uuid"=>$uuid);
	$checkuuid = check_uuid($and_uuid,$userid);
    if(!$checkuuid){
    	$error = "uuid_error";
    	echo return_data($error,false);exit;	
    }
	$user_arr = MooMembersData($userid);
	//note 显示查询的内容	
	$memail = $_MooClass['MooMySQL']->getOne("SELECT * FROM {$dbTablePre}services WHERE s_id = '$contentid' and flag = '1' and s_fromid_del='0'");
	
	//note 发件人的昵称或ID
	if($memail['s_uid'] == 0){
		$fromname = '真爱一生网';
		//note 当前会员昵称		
		$uname = $user_arr['nickname']?$user_arr['nickname']:'ID:'.$user_arr['uid'];
	}else{
		$from_name = $_MooClass['MooMySQL']->getOne("select uid,nickname from {$dbTablePre}members_search where uid='".$memail['s_uid']."'");
		$fromname = $from_name['nickname']?$from_name['nickname']:'ID '.$from_name['uid'];
		$uname = $user_arr['nickname']?$user_arr['nickname']:'ID:'.$user_arr['uid'];
	}
	$memail['s_content'] = str_replace("<br>", "\r\n", $memail['s_content']);
	$memail['fromname'] = $uname;
	$memail['username'] = $fromname;
	$h = stripos($memail['s_content'], "会员号");
	if($h !== false){
		$from_uid = substr($memail['s_content'], $h+12,7);
		$memail['from_uid'] = $from_uid;
	}
	$memail["s_time"] = date("Y-m-d H:i:s",$memail["s_time"]);
	echo return_data($memail);exit;
	
}



//note 发送/回复 消息
function to_send_message(){
	global $_MooClass,$dbTablePre,$userid,$user_arr,$memcached,$timestamp,$serverid;
	$and_uuid = isset($_GET['uuid'])? $_GET['uuid'] : '';
	$uid =  isset($_GET['uid'])?$_GET['uid']:'';
	if($uid){
		$userid = $mem_uid = $memcached->get('uid_'.$uid);
	}
	$uuid = $memcached->get('uuid_'.$userid);
	//$error[] = array("getand_uuid"=>$and_uuid,"getuid"=>$uid,"userid"=>$userid,"mem_uuid"=>$uuid);
	$checkuuid = check_uuid($and_uuid,$userid);
    if(!$checkuuid){
    	$error = "uuid_error";
    	echo return_data($error,false);exit;	
    }
	$user_arr = MooMembersData($userid);
	
	$contentid = MooGetGPC('s_id','integer','P');

		//不可给同性别发送消息
		
		$receive_info = MooMembersData($contentid);
		
 		$receive_gender = $receive_info['gender'];
 		if($user_arr['gender'] == $receive_gender){			
 			$err = "不可给同性别发送消息";
    		echo return_data($err,false);exit;	
 		}
        
 		//系统管理员权限
	    /*$result=$_MooClass['MooMySQL']->getOne("select groupid from web_admin_user where uid='{$serverid}'");
	    $groupid=$result['groupid'];
	    
	    //$GLOBALS['system_admin'] = array(60);
	    if(in_array($groupid,$GLOBALS['system_admin'])){
	        $serverid=null;
	    }*/
	    
 		$result=$_MooClass['MooMySQL']->getOne("select groupid from web_admin_user where uid='{$serverid}'");
	    $groupid=$result['groupid'];
 		
        if($serverid && $user_arr['usertype']!=3){
		   
		   	$err = "对不起您不能模拟操作";
    		echo return_data($err,false);exit;	
		   
	    }
        
	    if($serverid && $user_arr['usertype']==3 && !in_array($groupid,$GLOBALS['admin_aftersales'])){
	       	$err = "对不起您不能模拟操作";
    		echo return_data($err,false);exit;	
	    }
	    
		//如果是本站注册会员
		if($user_arr['usertype'] == 1){
				$m_level = get_userrank($userid);//会员等级
				if($m_level != 1){ //不是高级会员
							if(!checkIsMobileCertical($userid)){ //没有通过了手机验证
								$err = "您还没有进行手机验证,请先认证";
    							echo return_data($err,false);exit;	
							}else{
								//if(!checkIsOver($userid)){ //过了试用期
									
									$err = "只有钻石或高级会员才可以发送电子邮件给对方";
    								echo return_data($err,false);exit;	
								//}
							}					
				}
		}
	//print_r($_POST);
	//note s_cid 发送者的权限（回复时才有）
	$s_cid = MooGetGPC('s_cid','integer','P');
	//echo $s_cid;exit;
	
	//收件人id
	$sid = $contentid;
	//发件人id
	$mid = $userid;
	
	///echo $sid;exit;
	
	$s_title = MooGetGPC('s_title','string','P');
	$s_title = rtrim($s_title);
	//对主题字数限制
	$s_title = MooStrReplace(MooCutstr($s_title, 30, $dot = ''));
	$s_content = MooGetGPC('s_content','string','P');
	//note 特殊字符    
	$s_content = rtrim(MooStrReplace(safeFilter($s_content)));
	$send_mymessage = MooGetGPC('send_mymessage','integer','P');
	$message_back = MooGetGPC('message_back','integer','P');
	//note 双方屏蔽不给操作
	if(MooGetScreen($mid,$sid)){
		
		$err = "因特殊原因，消息发送失败";
    	echo return_data($err,false);exit;
		
	}
	if($mid && $sid && $s_title && $s_content){
		$m_level = get_userrank($userid);//会员等级
		if($m_level == 2 && $user_arr['usertype']!=3 ){ //普通会员需要审核（除采集会员之外）
			//if($user_arr['gender'] == 0){	//如果是男方发的，需要审核,0表示
				$send_status = $_MooClass['MooMySQL']->query("insert into {$dbTablePre}services (s_cid,s_uid,s_fromid,s_title,s_content,s_time,sid,flag) values ('{$s_cid}','{$sid}','{$mid}','{$s_title}','{$s_content}',".time().",'{$user_arr['sid']}','0')");			
		}else{ 
			$send_status = $_MooClass['MooMySQL']->query("insert into {$dbTablePre}services (s_cid,s_uid,s_fromid,s_title,s_content,s_time,sid,flag) values ('{$s_cid}','{$sid}','{$mid}','{$s_title}','{$s_content}',".time().",'{$user_arr['sid']}','1')");
		}
		
			
	   //发送短信和邮件
	   include_once("./module/crontab/crontab_config.php");
	   $res= MooMembersData($sid);
//	   $send_user_info = $_MooClass['MooMySQL']->getAll("select * from `{$dbTablePre}members_search` a  left join  {$dbTablePre}members_choice b  on a.uid=b.uid  where a.uid = '$userid'");
		$send_user_info = array_merge(MooGetData("members_choice", 'uid',$userid),MooMembersData($userid));
//	   $send_user_info = $send_user_info[0];
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
	   require_once MooTemplate('public/mail_space_messagetpl', 'module'); //模板
	   $body = ob_get_clean();
	  
	   MooSendMail($res['username'],"真爱一生网系统温馨提示",$body,"",false,$sid);
	  
	  
	   $week_time = 24*3600*7;//一周时间秒数
	   $interval_time = $timestamp - $user_arr['last_login_time'];//当前时间-最后登录时间
	   $date1 = date("Y-m-d",strtotime("last Monday"));
	   $date2 = date("Y-m-d",strtotime("Sunday"));
	   
	   if($interval_time > $week_time){//不活跃用户每周发一条短信
	   		$_MooClass['MooMySQL']->query("INSERT INTO {$dbTablePre}today_send SET uid = '".$sid."', sid = '".$userid."',phone = '".$res['telphone']."',sendtime = '".date("Y-m-d")."'" );
	   		$cos = $_MooClass['MooMySQL']->getOne("select count(*) as c from {$dbTablePre}today_send where uid='$sid' and sendtime>='$date1' and sendtime<='$date2'",true);
	    	if($cos['c']<=1){
			  //fanglin暂时屏蔽
			  Push_message_intab($sid,$res['telphone'],"邮件","真爱一生网 用户ID：".$userid.",".$send_user_grade.",已给您发送电子邮件,请及时到真爱一生网查看！4006780405",$userid);
		   }
	   }else{ //活跃用户每天一条
		     //每天该用户超过5条信息不发送短信
		   $cos = $_MooClass['MooMySQL']->getOne("select count(*) as c from {$dbTablePre}today_send where uid='$sid' and sendtime='".date("Y-m-d")."'",true);
		   if($cos['c']<5){
		   	  $_MooClass['MooMySQL']->query("INSERT INTO {$dbTablePre}today_send SET uid = '".$sid."', sid = '".$userid."',phone = '".$res['telphone']."',sendtime = '".date("Y-m-d")."'" );
			  //fanglin暂时屏蔽
			  Push_message_intab($sid,$res['telphone'],'邮件',"真爱一生网 用户ID：".$userid.",".$send_user_grade.",已给您发送电子邮件,请及时到真爱一生网查看！4006780405",$userid);
		   }
	   }
	

		//note 发送资料给接收方
		if($send_mymessage){
			
		}
		//note 备份邮件操作
		if($message_back){
			//note 发送MAIL时所需信息
			$user = $_MooClass['MooMySQL']->getOne("select username,nickname from {$dbTablePre}members_search where uid='$mid'");
			//note 发送MAIL时收信人的邮箱
			$ToAddress = $user['username'];
			//note 发送MAIL时主题
			$ToSubject = '提示：您在真爱一生网所发的消息备份';
			//note 发送MAIL时Body内容所需信息
			$username = $user['nickname'];
			//note 发送MAIL时Body内容所需信息
			$toname = $_MooClass['MooMySQL']->getOne("select nickname from {$dbTablePre}members_search where uid='$sid'");
			if($username){
				$ToBody = $username;
			}else{
				$ToBody = 'ID号为'.$mid.'的会员<br>';
			}
			if($toname['nickname']){
				$ToBody .= '：您好，您于'.date('Y-m-d H:i:s',time()).'，在真爱一生网发送电子邮件给'.$toname['nickname'].'，内容如下：<br>';
			}else{			
				$ToBody .= '：您好，您于'.date('Y-m-d H:i:s',time()).'，在真爱一生网发送电子邮件给ID号为'.$sid.'的会员，内容如下：<br>';
			}
			if($username){
				$ToBody .= '&nbsp;&nbsp;&nbsp;&nbsp;发件人：'.$username.'<br>';
			}else{
				$ToBody .= '&nbsp;&nbsp;&nbsp;&nbsp;发件人：ID号为'.$mid.'<br>';
			}
			if($toname['nickname']){
				$ToBody .= '&nbsp;&nbsp;&nbsp;&nbsp;收件人：'.$toname['nickname'].'<br>';
			}else{
				$ToBody .= '&nbsp;&nbsp;&nbsp;&nbsp;收件人：ID号为'.$sid.'<br>';
			}
			$ToBody .= '&nbsp;&nbsp;&nbsp;&nbsp;主题：'.$s_title.'<br>';
			$ToBody .= '&nbsp;&nbsp;&nbsp;&nbsp;正文：<br>&nbsp;&nbsp;&nbsp;&nbsp;'.$s_content;
			MooSendMail($ToAddress,$ToSubject,$ToBody,$is_template = true,$sid);
		}
		//添加成功提示信息
		if($send_status){
			/*普通会员对全权会员反馈白名单*/
			if($res['usertype'] == '3' && $user_arr['usertype'] != '3' && !$serverid){
				white_list($res['uid'],$user_arr['uid']);
			}
			/*客服模拟全权记录*/
			if($user_arr['usertype'] == '3' && $serverid && $res['usertype'] != '3'){
				$action = '站内信';
				fulllog($user_arr['uid'],$serverid,$action,$res);
			}
			//提醒所属客服
			$usid = $user_arr['sid'];
			$title = '您的会员 '.$mid.' 向 '.$sid.' 发送了邮件';
			$awoketime = $timestamp+3600;
			$sql_remark = "insert into {$dbTablePre}admin_remark set sid='{$usid}',title='{$title}',content='{$title}',awoketime='{$awoketime}',dateline='{$timestamp}'";
			$res = $_MooClass['MooMySQL']->query($sql_remark);

			if($user_arr['gender'] == 0){ //男方发的
				
				$suc = "发送成功,真爱一生审核后对方即可以收到。";
    			echo return_data($suc,true);exit;
			}else{
				$suc = "发送成功";
    			echo return_data($suc,true);exit;
				
			}

		}else{
			$err = "发送失败";
    		echo return_data($err,false);exit;
		}
	}else{
		$err = "数据填写不完整";
    	echo return_data($err,false);exit;
	}
}

/**************************************************控制层*******************************/

$m = $_GET['m'] = empty($_GET['m'])? '': $_GET['m'];

switch ($m){
	case 'allmessage' :
		allmessage_list();
		break;
		//note 所有消息（详细）
	case 'detailallmessage':
		$contentid = MooGetGPC('contentid','integer');
		allmessage_detail($contentid);
		break;
		
	case 'allmessagesend' :
		allmessage_list_send();
		break;
		
	case 'talk' :
		shadow_talks();
		break;
		//note 所有消息（详细）
	case 'detailallmessagesend':
		$contentid = MooGetGPC('contentid','integer');
		allmessage_detail_send($contentid);
		break;
		
	//note 发信息****************************
	case "send" :
		to_send_message();
		break;		
		
	default:
		allmessage_list();
		break;	
	

}
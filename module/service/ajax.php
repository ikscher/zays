<?php
/*
 * Created on 10/12/2009
 *
 * yujun
 *
 * module/service/ajax.php
 */
/***************************************功能层******************************/

//note 委托填号码
function addphone(){
	global $_MooClass,$dbTablePre,$user_arr,$userid,$serverid;
	$phone = $_GET['phone'];
	$value = array();
	if($phone && preg_match('/^((1[35][\d]{9})|(18[4689][\d]{8}))$/',$phone)){
		 if($_MooClass['MooMySQL']->query("update {$dbTablePre}members_search set telphone='$phone' where uid='$userid'")){
		 	searchApi('members_man members_women')->updateAttr(array('telphone'),array($userid=>array($phone)));
		 	//更新索引数据的属性值
			if(MOOPHP_ALLOW_FASTDB){
				$value['phone'] = $phone;
				MooFastdbUpdate('members_search','uid',$userid,$value);
			}
		 	echo $phone;
		 }
	}else{
		echo 0;
	}
}

//读取身份证信息
function view_request_sms(){
	global $_MooClass,$dbTablePre,$user_arr,$userid,$serverid;
	
	$member_level = get_userrank($userid);
	if($member_level==0){
		echo 'no_vip';exit;
	}
	$uid = MooGetGPC('uid','integer','G');
	
	//判断是否有权限查看
	$sql = "SELECT id FROM {$dbTablePre}members_requestsms WHERE uid='{$uid}'  AND ruid='{$userid}' AND status=1";
	$requestsms = $_MooClass['MooMySQL']->getOne($sql,true);
	if(empty($requestsms)){
		return false;
	}
	//$uid = 2146973;
	$members = MooMembersData($uid);
	if (isset($members['birth'])&& !empty($members['birth'])){//birth modify
		$barr = explode('-',$members['birth']);
		$members['birthyear']   = $barr[0];
		$members['birthmonth']  = $barr[1];
		$members['birthday']    = $barr[2];
	}
	
	if($members['gender'] == 1) $gender='女';
	else $gender = '男';
	
	$sql = "SELECT realname,idcode FROM {$dbTablePre}smsauths WHERE uid='{$uid}'";
	$sms = $_MooClass['MooMySQL']->getOne($sql,true);
	if(empty($sms)){
		echo 'no';exit;
	}
	//$filename = "data/sms/{$uid}.jpg";
	//if(!file_exists($filename)){
		//$get_pic = get_pic($sms['idcode'],$filename);
		$get_pic = get_pic2($sms['idcode'],$filename);

	//}
	
	echo "<div>
			<table>
				<tr><td>姓名:</td><td>{$sms['realname']}</td></tr>
				<tr><td>性别:</td><td>{$gender}</td></tr>
				<tr><td>出生年月:</td><td>{$members['birthyear']}年{$members['birthmonth']}月{$members['birthday']}日</td></tr>
				<tr><td>照片:</td><td><img src='$get_pic' /></td></tr>
			</table>
		</div>";
}


//读取验证码是否填写正确
function remark_seccode(){
	global $memcached;
	$seccode1 = strtolower(MooGetGPC('seccode','string','G'));
	$seccode2 = MooGetGPC('seccode','string','C');
	$session_seccode = $memcached->get($seccode2);

	if($session_seccode != $seccode1){
		echo '2';
	}else if($session_seccode != '' && $seccode1 && $session_seccode == $seccode1){
		echo '1';	
	}
}


//秋波发送表白语句
function getLeerInfo(){
    global $_MooClass,$dbTablePre,$timestamp,$memcached,$user_arr;
	//header('Content-Type text/html; charset=utf8');
	$sendinfo=array();
	$page=MooGetGPC('page','integer','G');
	if($page<=0 || !isset($page)) $page=1;
	$limit=5;
	$offset=($page-1)*$limit;
    if($user_arr['gender']==0){//boy
	    $sql = "SELECT id,content  FROM {$dbTablePre}members_sendinfo where type=2 and isShow=1 limit  $offset,$limit";
		$sql_="select count(id) as total from web_members_sendinfo where type=2 and isShow=1";
    }else{//girl
	    $sql = "SELECT id,content  FROM {$dbTablePre}members_sendinfo where type=1 and isShow=1  limit $offset,$limit";
		$sql_="select count(id) as total from web_members_sendinfo where type=1 and isShow=1";
	}
	$t = $_MooClass['MooMySQL']->getOne($sql_);
    $sendinfo =  $_MooClass['MooMySQL']->getAll($sql);
	
	$sendinfo = array_merge($sendinfo,$t);
	echo json_encode($sendinfo);

}

//发送秋波
function sendLeer(){
    global $_MooClass,$dbTablePre,$timestamp,$user_arr,$memcached;	
    $userid=$user_arr['uid'];
	$sendtouid=MooGetGPC('uid','string','P');
	$info = MooGetGPC('info','string','P');
	if($userid==$sendtouid) exit('sameone');

	
	$serverid = Moo_is_kefu();
	if($serverid && $user_arr['usertype']!=3){//只能模拟全权会员
        exit('simulate');
    }
	
	
	if(MooGetScreen($userid,$sendtouid)){
        exit('shield');
    }
	
	$sendToUser=MooMembersData($sendtouid);
	if(isset($sendToUser['gender']) && $sendToUser['gender']==$user_arr['gender']){ exit('gender');}
	
	$as=$memcached->get('sendLeer'.$userid.$sendtouid);
	if($as) {exit('alreadySend');}
	
	$i=$memcached->get('sendLeer'.$userid)?$memcached->get('sendLeer'.$userid):0;
	
	if($i>=5) exit('limited');
	
	//是否有手机号码
    if(MOOPHP_ALLOW_FASTDB){
        $_R_ = MooFastdbGet('certification','uid',$userid);
    }else{
	    $sql="SELECT telphone FROM {$dbTablePre}certification WHERE uid='$userid'";
        $_R_ = $_MooClass['MooMySQL']->getOne($sql,true);
    }

    if(empty($_R_['telphone']) && empty($serverid) ){ exit('telNo');}
	
	
	$gender=$user_arr['gender']==1?"美女":"帅哥";
	$leer = $_MooClass['MooMySQL']->getOne("SELECT * FROM {$dbTablePre}service_leer WHERE receiveuid = '$sendtouid' AND senduid = '$userid' ",true);
	if(isset($leer['lid'])) { //已经发送过秋波
		$sendtime=date('Y-m-d',$leer['sendtime']);
		$lid = $leer['lid'];
		//增加发送秋波的次数
		$_MooClass['MooMySQL']->query("UPDATE {$dbTablePre}service_leer SET num = num + 1,receivenum = receivenum + 1,sendtime = '$timestamp',receivetime = '$timestamp',receive_del =0,is_read=0 WHERE lid = '$lid'");     
		//note 如果已经收到这个人的秋波，已经拒绝，现在改变注意，又发送秋波给这个人,拒绝状态2更改为0
		$_MooClass['MooMySQL']->query("UPDATE {$dbTablePre}service_leer SET stat = '0' WHERE senduid = '$sendtouid' AND receiveuid = '$userid' AND stat = '2'"); 
	}else {
		//note 发送新的秋波，写入数据库 发送者，接受者，发送时间
		$_MooClass['MooMySQL']->query("INSERT INTO {$dbTablePre}service_leer SET sendtime = '$timestamp',receivetime = '$timestamp',receivenum = '1', num = '1', senduid  = '{$userid}',receiveuid = '$sendtouid'");
	    //发送短信
		if(isset($sendToUser['usertype']) && $sendToUser['usertype']==1) Push_message_intab($sendtouid,$sendToUser['telphone'],"秋波","尊敬的会员您好！ID：{$userid}的{$gender}给您发送了秋波，{$info} 登录www.zhenaiyisheng.cc收获缘分！幸福热线：4008787920。",$userid);
	}
	
	 //将新注册的会员更新为优质会员
	if(in_array($user_arr['sid'],array(1,52,123))&&$user_arr['is_well_user']!=1){       
		update_iswell_user($user_arr['uid']);
	}
	
	$memcached->set('sendLeer'.$userid.$sendtouid,1,0,86400);
	$memcached->set('sendLeer'.$userid,$i+1,0,86400);
	
	//提醒所属客服
	$sid = $user_arr['sid'];
	$title = '您的会员 '.$userid.' 向 '.$sendtouid.' 发送了秋波';
	$awoketime = $timestamp+3600;
	$sql_remark = "insert into {$dbTablePre}admin_remark set sid='{$sid}',title='秋波',content='{$title}',awoketime='{$awoketime}',dateline='{$timestamp}'";
	$res = $_MooClass['MooMySQL']->query($sql_remark);
	
	
}

//发送委托
function sendCommission(){
    global $_MooClass,$dbTablePre,$timestamp,$user_arr,$memcached;	
	$userid=$user_arr['uid'];
	$sendtouid=MooGetGPC('uid','string','P');
	$content = MooGetGPC('content','string','P');
	if($userid==$sendtouid) exit('sameone');
	
	$serverid = Moo_is_kefu();
	if($serverid && $user_arr['usertype']!=3){
        exit('simulate');
    }
	
	if(MooGetScreen($userid,$sendtouid)){
        exit('shield');
    }
	
	$sendToUser=MooMembersData($sendtouid);
	if(isset($sendToUser['gender']) && $sendToUser['gender']==$user_arr['gender']){ exit('gender');}
	
	if(isset($sendToUser['showinformation']) && $sendToUser['showinformation']!=1) exit('closeInfo');
	
	//是否有手机号码
    if(MOOPHP_ALLOW_FASTDB){
        $_R_ = MooFastdbGet('certification','uid',$userid);
    }else{
	    $sql="SELECT telphone FROM {$dbTablePre}certification WHERE uid='$userid'";
        $_R_ = $_MooClass['MooMySQL']->getOne($sql,true);
    }

    if(empty($_R_['telphone']) && empty($serverid) ) { exit('telNo');}
	
	//已经发送过多次
	$as=$memcached->get('sendCommission'.$userid.$sendtouid);
	if($as) {exit('alreadySend');}
	
	$i=$memcached->get('sendCommission'.$userid)?$memcached->get('sendCommission'.$userid):0;
	
	if($i>=3) exit('limited');
	
	$gender=$user_arr['gender']==1?"美女":"帅哥";
	$update_sql = '';
	if(empty($user_arr['sid']) && $user_arr['usertype'] == 1){
		$update_sql = ',is_well_user=1';
	}
	$_MooClass['MooMySQL']->query("UPDATE {$dbTablePre}members_base SET contact_num = contact_num + 1,contact_time = '$timestamp' $update_sql  WHERE uid = '$userid'");
	if(MOOPHP_ALLOW_FASTDB){
		$MB_ = MooFastdbGet('members_base','uid',$userid);
		$value = array();
		$value['contact_num'] = $MB_['contact_num']+1;
		$value['contact_time'] = $timestamp;
		if($update_sql!='') $value['is_well_user'] = 1;
		MooFastdbUpdate('members_base','uid',$userid,$value);
	}
	//note 记录委托红娘的内容       
	$_MooClass['MooMySQL']->query("INSERT INTO {$dbTablePre}service_contact SET content = '$content',other_contact_you = '$userid',you_contact_other='$sendtouid',stat = '1',syscheck='0',sendtime = '$timestamp'");

	//将新注册的会员更新为优质会员
	if(in_array($user_arr['sid'],array(1,52,123))&&$user_arr['is_well_user']!=1){       
		update_iswell_user($user_arr['uid']);
	}
	
	$memcached->set('sendCommission'.$userid.$sendtouid,1,0,86400);
	$memcached->set('sendCommission'.$userid,$i+1,0,86400);
	
	//*********提醒所属客服**********
	$sid = $user_arr['sid'];
	$title = '您的会员 '.$userid.' 向 '.$sendtouid.' 发送了委托';
	$awoketime = $timestamp+3600;
	$sql_remark = "insert into {$dbTablePre}admin_remark set sid='{$sid}',title='委托',content='{$title}',awoketime='{$awoketime}',dateline='{$timestamp}'";
	$res = $_MooClass['MooMySQL']->query($sql_remark);
	
	
	if(isset($sendToUser['usertype']) && $sendToUser['usertype']==1) Push_message_intab($sendtouid,$sendToUser['telphone'],"委托","尊敬的会员您好！ID：{$userid}的{$gender}已委托红娘联系您，登录www.zhenaiyisheng.cc收获缘分！幸福热线：4008787920",$userid);

	
}

//加为意中人
function addLiker(){
    global $_MooClass,$dbTablePre,$pagesize,$user_arr,$timestamp,$memcached;
	$sendtouid = MooGetGPC('uid','integer','P');

	$userid=$user_arr['uid'];
    
	$serverid = Moo_is_kefu();
	if($serverid && $user_arr['usertype']!=3){
        exit('simulate');
    }
	
	if($sendtouid == $userid){	
		exit('sameone');
	}
	
	if(MooGetScreen($userid,$sendtouid)){
        exit('shield');
	}

	$sendFriendCount=$memcached->get('sendFriend'.$userid);
	if(empty($sendFriendCount)) $sendFriendCount=0;
	if($sendFriendCount>5){
	    exit('limited');
	}
	
	$friend = $_MooClass['MooMySQL']->getOne("SELECT * FROM {$dbTablePre}service_friend WHERE friendid = '$sendtouid' AND uid = '$userid'");
	if($friend['fid']) {
	    exit('alreadySend');
	}
	
	$sendToUser=MooMembersData($sendtouid);

	if(isset($sendToUser['gender']) && $sendToUser['gender']==$user_arr['gender']){ exit('gender');}
	
	if(isset($sendToUser['showinformation']) && $sendToUser['showinformation']!=1) exit('closeInfo');
	
	//是否有手机号码
    if(MOOPHP_ALLOW_FASTDB){
        $_R_ = MooFastdbGet('certification','uid',$userid);
    }else{
	    $sql="SELECT telphone FROM {$dbTablePre}certification WHERE uid='$userid'";
        $_R_ = $_MooClass['MooMySQL']->getOne($sql,true);
    }

    if(empty($_R_['telphone']) && empty($serverid) ) { exit('telNo');}
	
	$gender=$user_arr['gender']==1?"美女":"帅哥";
   //note 加为意中人，新插入一行记录
   $_MooClass['MooMySQL']->query("INSERT INTO {$dbTablePre}service_friend SET friendid  = '{$sendtouid}',uid = '{$userid}',sendtime='{$timestamp}'");
   
   $memcached->set('sendFriend'.$userid,++$sendFriendCount,0,86400);
   
    //将新注册的会员更新为优质会员
	if(in_array($user_arr['sid'],array(1,52,123))&&$user_arr['is_well_user']!=1){		
		update_iswell_user($userid);
	}
	
	
	//****************提醒所属客服*****************8
	$sid = $user_arr['sid'];
	$title = '您的会员 '.$userid.' 把 '.$sendtouid.' 加为意中人';
	$awoketime = $timestamp+3600;
	$sql_remark = "insert into {$dbTablePre}admin_remark set sid='{$sid}',title='意中人',content='{$title}',awoketime='{$awoketime}',dateline='{$timestamp}'";
	$_MooClass['MooMySQL']->query($sql_remark);

	if(isset($sendToUser['usertype']) && $sendToUser['usertype']==1) Push_message_intab($sendtouid,$sendToUser['telphone'],"意中人","尊敬的会员您好！ID：{$userid}的{$gender}加您为意中人,请及时登录www.zhenaiyisheng.cc收获缘分！幸福热线：4008787920",$userid);
	
}

//送礼物
function sendGift(){
    global $_MooClass,$dbTablePre,$pagesize,$user_arr,$timestamp,$memcached;
	$sendtouid = MooGetGPC('uid','integer','P');

	$userid=$user_arr['uid'];
    
	$serverid = Moo_is_kefu();
	if($serverid && $user_arr['usertype']!=3){
        exit('simulate');
    }
	
	if($sendtouid == $userid){	
		exit('sameone');
	}
	
	if(MooGetScreen($userid,$sendtouid)){
        exit('shield');
	}

	$sendGiftCount=$memcached->get('sendGift'.$userid);
	if(empty($sendGiftCount)) $sendGiftCount=0;
	if($sendGiftCount>3){
	    exit('limited');
	}
	
	$gifts = $_MooClass['MooMySQL']->getOne("SELECT rid FROM {$dbTablePre}service_friend WHERE receiveuid = '$sendtouid' AND senduid = '$userid'");
	if(isset($gifts['rid'])) {
	    exit('alreadySend');
	}
	
	$sendToUser=MooMembersData($sendtouid);
	if(isset($sendToUser['gender']) && $sendToUser['gender']==$user_arr['gender']){ exit('gender');}
	
	if(isset($sendToUser['showinformation']) && $sendToUser['showinformation']!=1) exit('closeInfo');

	if($user_arr['rosenumber'] <= 0) {
		exit('nomoregifts');
	}
	
	//是否有手机号码
    if(MOOPHP_ALLOW_FASTDB){
        $_R_ = MooFastdbGet('certification','uid',$userid);
    }else{
	    $sql="SELECT telphone FROM {$dbTablePre}certification WHERE uid='$userid'";
        $_R_ = $_MooClass['MooMySQL']->getOne($sql,true);
    }

    if(empty($_R_['telphone']) && empty($serverid) ) { exit('telNo');}
    
	$gender=$user_arr['gender']==1?"美女":"帅哥";
   //note 赠送礼物
	$rosenum_check =  $_MooClass['MooMySQL']->getOne("SELECT rosenumber FROM {$GLOBALS['dbTablePre']}members_base  WHERE `uid`='{$userid}'  LIMIT 1",true);

	if($rosenum_check['rosenumber'] > 0) {	
	    $rose = $_MooClass['MooMySQL']->getOne("SELECT * FROM {$dbTablePre}service_rose WHERE receiveuid = '$sendtouid' AND senduid = '$userid' ORDER BY rid DESC LIMIT 1");
	    if(isset($rose['rid'])) {
			$rid = $rose['rid'];
			//note 如果已经发送过玫瑰，就增加发送玫瑰的次数
			$_MooClass['MooMySQL']->query("UPDATE {$dbTablePre}service_rose SET num = num + 1, receivenum = receivenum + 1,sendtime = '$timestamp',receivetime='$timestamp',receive_del=0,send_del=0 WHERE rid = '$rid'");
		}else {
			//note 发送新的玫瑰，写入数据库 发送者，接受者，发送时间
			$_MooClass['MooMySQL']->query("INSERT INTO {$dbTablePre}service_rose SET num = 1, receivenum = 1, sendtime = '$timestamp',receivetime='$timestamp',senduid  = '{$userid}',receiveuid = '$sendtouid' ");
		}
			
		//note 发送一朵玫瑰，自己就要减少一朵玫瑰
		$_MooClass['MooMySQL']->query("UPDATE {$dbTablePre}members_base SET rosenumber = rosenumber - 1 WHERE uid = '{$userid}'");
		if(MOOPHP_ALLOW_FASTDB){
			$user_rosenum =  $_MooClass['MooMySQL']->getOne("SELECT rosenumber FROM {$GLOBALS['dbTablePre']}members_base  WHERE `uid`='{$userid}'  LIMIT 1",true);
			$value['rosenumber']= $user_rosenum['rosenumber'];
			MooFastdbUpdate('members_base','uid',$userid,$value);
		}
	}
   
   
   $memcached->set('sendGift'.$userid,++$sendGiftCount,0,86400);
   
    //将新注册的会员更新为优质会员
	if(in_array($user_arr['sid'],array(1,52,123))&&$user_arr['is_well_user']!=1){		
		update_iswell_user($userid);
	}
	
	
	//****************提醒所属客服*****************8
	$sid = $user_arr['sid'];
	$title = '您的会员 '.$userid.' 向 '.$sendtouid.' 赠送了礼物';
	$awoketime = $timestamp+3600;
	$sql_remark = "insert into {$dbTablePre}admin_remark set sid='{$sid}',title='赠送礼物',content='{$title}',awoketime='{$awoketime}',dateline='{$timestamp}'";
	$_MooClass['MooMySQL']->query($sql_remark);
    
	if(isset($sendToUser['usertype']) && $sendToUser['usertype']==1) Push_message_intab($sendtouid,$sendToUser['telphone'],"鲜花","尊敬的会员您好！ID：{$userid}的{$gender}向您赠送了礼物,登录www.zhenaiyisheng.cc收获缘分！幸福热线：4008787920",$userid);
}


//对TA评价
function sendEstimate(){
    global $_MooClass,$dbTablePre,$timestamp,$user_arr,$timestamp,$memcached; 
    $sendtouid = MooGetGPC('uid','integer','P');
    $userid=$user_arr['uid'];
	
	$serverid = Moo_is_kefu();
	if($serverid && $user_arr['usertype']!=3){
        exit('simulate');
    }
	
	
	if(empty($sendCommentCount)) $sendCommentCount=0;
	$sendCommentCount=$memcached->get('sendComment'.$userid);
	if($sendCommentCount>5){
	    exit('limited');
	}
	
    if($sendtouid == $userid){	
		exit('sameone');
	}
	
	if(MooGetScreen($userid,$sendtouid)){
        exit('shield');
	}
	
	
	$sendToUser=MooMembersData($sendtouid);

	if(isset($sendToUser['gender']) && $sendToUser['gender']==$user_arr['gender']){ exit('gender');}
	
	if(isset($sendToUser['showinformation']) && $sendToUser['showinformation']!=1) exit('closeInfo');
	
	$comment = $_MooClass['MooMySQL']->getOne("SELECT * FROM {$dbTablePre}members_comment WHERE cuid = '$userid' AND uid = '$sendtouid'");
	if(isset($comment['id'])) {
	    exit('alreadySend');
	}
	
	
	//是否有手机号码
    if(MOOPHP_ALLOW_FASTDB){
        $_R_ = MooFastdbGet('certification','uid',$userid);
    }else{
	    $sql="SELECT telphone FROM {$dbTablePre}certification WHERE uid='$userid'";
        $_R_ = $_MooClass['MooMySQL']->getOne($sql,true);
    }

    if(empty($_R_['telphone']) && empty($serverid) ) { exit('telNo');}
    
	$gender=$user_arr['gender']==1?"美女":"帅哥";
    $data['content'] = trim(safeFilter(MooGetGPC('content','string','P')));
	$data['cuid'] = $userid;
    $data['uid'] = $sendtouid;
	$data['dateline'] = time();
	
	inserttable('members_comment',$data);
    
	$memcached->set('sendComment'.$userid,++$sendCommentCount,0,86400);
	
	//将新注册的会员更新为优质会员
	if(in_array($user_arr['sid'],array(1,52,123))&&$user_arr['is_well_user']!=1){		
		update_iswell_user($userid);
	}
	
	//************提醒所属客服**************
	$sid = $user_arr['sid'];
	$title = '您的会员 '.$userid.' 评价了 '.$sendtouid;
	$awoketime = $timestamp+3600;
	$sql_remark = "insert into {$dbTablePre}admin_remark set sid='{$sid}',title='评价',content='{$title}',awoketime='{$awoketime}',dateline='{$timestamp}'";
	$res = $_MooClass['MooMySQL']->query($sql_remark);
	
	
	if(isset($sendToUser['usertype']) && $sendToUser['usertype']==1) Push_message_intab($sendtouid,$sendToUser['telphone'],"评价人","尊敬的会员您好！ID：{$userid}的{$gender}评价了您,登录www.zhenaiyisheng.cc收获缘分！幸福热线：4008787920",$userid);  

}

//获取对方的信息
function getSMS(){
    global $_MooClass,$dbTablePre,$timestamp,$user_arr,$serverid,$memcached; 
	$sendtouid = MooGetGPC('uid','integer','P');
    $userid=$user_arr['uid'];
	
	$serverid = Moo_is_kefu();
	if($serverid && $user_arr['usertype']!=3){
        exit('simulate');
    }
	
    $isHigher= get_userrank($userid);
    if($isHigher != 1){
        exit('noHigher');
    }
	
	if(empty($sendSMSCount)) $sendSMSCount=0;
	$sendSMSCount=$memcached->get('sendSMS'.$userid);
	if($sendSMSCount>3){
	    exit('limited');
	}
	
    if($sendtouid == $userid){	
		exit('sameone');
	}
	
	if(MooGetScreen($userid,$sendtouid)){
        exit('shield');
	}
	
	//是否有手机号码
    if(MOOPHP_ALLOW_FASTDB){
        $_R_ = MooFastdbGet('certification','uid',$userid);
    }else{
	    $sql="SELECT telphone FROM {$dbTablePre}certification WHERE uid='$userid'";
        $_R_ = $_MooClass['MooMySQL']->getOne($sql,true);
    }

    if(empty($_R_['telphone']) && empty($serverid) ) { exit('telNo');}
	
	$gender=$user_arr['gender']==1?"美女":"帅哥";
	$sendToUser=MooMembersData($sendtouid);

	if(isset($sendToUser['gender']) && $sendToUser['gender']==$user_arr['gender']){ exit('gender');}
	
	if(isset($sendToUser['showinformation']) && $sendToUser['showinformation']!=1) exit('closeInfo');
	
	
    $sql = "SELECT sms FROM {$dbTablePre}certification WHERE uid='$userid'";
    $certification = $_MooClass['MooMySQL']->getOne($sql,true);
    if($certification['sms'] != 1){
        exit('nosms');
    }

    $sql = "SELECT id FROM {$dbTablePre}members_requestsms WHERE uid='{$sendtouid}' AND ruid='{$userid}'";
    if($_MooClass['MooMySQL']->getOne($sql,true)){
        $sql = "UPDATE {$dbTablePre}members_requestsms SET request_total=request_total+1 WHERE uid='{$sendtouid}' AND ruid='{$userid}'";
        $_MooClass['MooMySQL']->query($sql);
    }else{
        $sql = "INSERT INTO {$dbTablePre}members_requestsms SET uid='{$sendtouid}',ruid='{$userid}',dateline='{$timestamp}'";
        $_MooClass['MooMySQL']->query($sql);
    }
	
	$memcached->set('sendSMS'.$userid,++$sendSMSCount,0,86400);
	
	//将新注册的会员更新为优质会员
	if(in_array($user_arr['sid'],array(1,52,123))&&$user_arr['is_well_user']!=1){		
		update_iswell_user($userid);
	}
    
    //***********提醒所属客服************
    $sid = $user_arr['sid'];
    $title = '您的会员 '.$userid.' 向 '.$sendtouid.' 获取身份信息';
    $awoketime = $timestamp+3600;
    $sql_remark = "insert into {$dbTablePre}admin_remark set sid='{$sid}',title='获取身份信息',content='{$title}',awoketime='{$awoketime}',dateline='{$timestamp}'";
    $res = $_MooClass['MooMySQL']->query($sql_remark);
    

      //fanglin暂时屏蔽  
    if(isset($sendToUser['usertype']) && $sendToUser['usertype']==1) Push_message_intab($sendtouid,$sendToUser['telphone'],"索取身份","尊敬的会员您好！ID：{$userid}的{$gender}请求查看您的身份信息，登录www.zhenaiyisheng.cc收获缘分！幸福热线：4008787920",$userid);

}

//绑定申请
function applyBind(){
    global $_MooClass,$dbTablePre,$userid,$user_arr,$timestamp;
	
	$sendtouid = MooGetGPC('uid','integer','P');
    $userid=$user_arr['uid'];
	
	$serverid = Moo_is_kefu();
	if($serverid && $user_arr['usertype']!=3){
        exit('simulate');
    }

    if($sendtouid == $userid){	
		exit('sameone');
	}
	
	if(MooGetScreen($userid,$sendtouid)){
        exit('shield');
	}
	
	$sendToUser=MooMembersData($sendtouid);

	if(isset($sendToUser['gender']) && $sendToUser['gender']==$user_arr['gender']){ exit('gender');}
	
	if(isset($sendToUser['showinformation']) && $sendToUser['showinformation']!=1) exit('closeInfo');
	
	if($user_arr['isbind']>0) exit('you');
	if($sendToUser['isbind']>0) exit('other');
	
	
	//是否有手机号码
    if(MOOPHP_ALLOW_FASTDB){
        $_R_ = MooFastdbGet('certification','uid',$userid);
    }else{
	    $sql="SELECT telphone FROM {$dbTablePre}certification WHERE uid='$userid'";
        $_R_ = $_MooClass['MooMySQL']->getOne($sql,true);
    }

    if(empty($_R_['telphone']) && empty($serverid) ) { exit('telNo');}
    
	$content =MooGetGPC('content','string','P');
	$time   = MooGetGPC('time','integer','P');
	$sql = "INSERT INTO {$dbTablePre}members_bind SET a_uid='{$userid}',b_uid='{$sendtouid}',apply_con='{$content}',dateline='{$timestamp}',apply_ltime='{$time}'";
	
	$query = $_MooClass['MooMySQL']->query($sql);
	$bind_id = $_MooClass['MooMySQL']->insertId();
	$_MooClass['MooMySQL']->query("UPDATE {$dbTablePre}members_base SET bind_id=".$bind_id." WHERE uid=".$userid);//预先更新申请人的bind_id,
	if (MOOPHP_ALLOW_FASTDB){
		$value = array();
		$value['bind_id'] = $bind_id;
		MooFastdbUpdate("members_base",'uid',$userid,$value);
	}
	
	$gender=$user_arr['gender']==1?"美女":"帅哥";
	//*************提醒所属客服*************
	$sid = $user_arr['sid'];
	$title = '您的会员 '.$userid.' 委托红娘绑定 '.$sendtouid;
	$awoketime = $timestamp+3600;
	$sql_remark = "insert into {$dbTablePre}admin_remark set sid='{$sid}',title='绑定',content='{$title}',awoketime='{$awoketime}',dateline='{$timestamp}'";
	$res = $_MooClass['MooMySQL']->query($sql_remark);
   
    if(isset($sendToUser['usertype']) && $sendToUser['usertype']==1) Push_message_intab($sendtouid,$sendToUser['telphone'],"绑定","尊敬的会员您好！ID：{$userid}的{$gender}已给委托红娘绑定您,登录www.zhenaiyisheng.cc收获缘分！幸福热线：4008787920",$userid);

}

/***************************************控制层****************************************/ 

include "function.php";

//note 初始化用户id
MooUserInfo();
$userid = $GLOBALS['MooUid'];
//$user = $_MooClass['MooMySQL']->getOne("SELECT mthumbimg FROM {$dbTablePre}members WHERE uid = '$userid'");

$h=MooGetGPC('h','string');
if(!$userid && $h!='page') {
   echo $h=='show'?json_encode(array('content'=>'no_login','s_id'=>'0')):'no_login';
   exit;
}

$serverid = Moo_is_kefu();

switch ($h) {
	//note 委托填号码
	case "addphone" :
		addphone();
		break;
	case 'view_request_sms':
		view_request_sms();
		break;
    case 'seccode':
		remark_seccode();
	    break;
    case 'page':
        activity_regist_page();
        break;
	case 'getleerinfo':
	    getLeerInfo();
		break;
	case 'sendleer':
	    sendLeer();
		break;
	case 'sendCommission':
	   sendCommission();
	   break;
	case 'addLiker':
	   addLiker();
	   break;
	case 'sendGift':
	   sendGift();
	   break;
	case 'sendEstimate':
	   sendEstimate();
	   break;
	case 'getSMS':
	   getSMS();
	   break;
	case 'applyBind':
	   applyBind();
	   break;
}
?>

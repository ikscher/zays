<?php 
/*
 * Created on 8/14/2009
 *
 * Tianyong yujun
 * @Modify:2010-02-10
 * @Modifier:fang
 * module/service/index.php
 */
/***************************************一些模板用的函数******************************/


//note 聊天记录会员信息
function service_story($uid){
    global $_MooClass,$dbTablePre,$user_arr,$userid,$serverid;
    //会员相关信息
    $usermsg = $user_arr;

    //if(!$serverid){
    
    //所有发件人ID
    $service_msg_send = $_MooClass['MooMySQL']->getAll("SELECT distinct s_fromid FROM {$dbTablePre}service_chat WHERE is_server=0 and s_uid = '$uid'");
    //所有收件人ID
    $service_msg_get = $_MooClass['MooMySQL']->getAll("SELECT distinct s_uid FROM {$dbTablePre}service_chat WHERE is_server=0 and s_fromid = '$uid'");
    
    /*}else{
    
    //所有发件人ID
    $service_msg_send = $_MooClass['MooMySQL']->getAll("SELECT distinct s_fromid FROM {$dbTablePre}service_chat WHERE s_uid = '$uid'");
    //所有收件人ID
    $service_msg_get = $_MooClass['MooMySQL']->getAll("SELECT distinct s_uid FROM {$dbTablePre}service_chat WHERE s_fromid = '$uid'");
    
    }*/
    
    
    if($service_msg_send){
        //所有发件人的相关信息
        foreach($service_msg_send as $v){
            $form_id_Arr[] = $v['s_fromid'];
        }
    }
    if($service_msg_get){
        //所有发件人的相关信息
        foreach($service_msg_get as $v){
            $form_id_Arr[] = $v['s_uid'];
        }
    }
    //判断有没有记录
    if($form_id_Arr){
        //去重复ID
        $form_id_Arr = array_keys(array_count_values($form_id_Arr));
        $fromid = implode(',',$form_id_Arr);
        $from_user = $_MooClass['MooMySQL']->getAll("SELECT uid,nickname FROM {$dbTablePre}members_search WHERE uid in ($fromid)");
    }else{
        //note 是否高级会员
        if(!get_userrank($uid)){
            require MooTemplate('public/nochat_story', 'module');
        }else{
            MooMessage('无聊天记录，搜索会员','index.php?n=search');
        }
        exit;
    }
    require MooTemplate('public/service_story', 'module');
}
//note 在线聊天对方信息
function service_chat_msg($uid){
    global $_MooCookie,$_MooClass,$dbTablePre,$user_arr,$serverid,$hzn;
    $chatorid = MooGetGPC('chatorid','integer','G');
    $screen = MooGetGPC('s','string','G');
    
    $chatfromid = MooGetGPC('chatfromid','integer');
    
    
	   
    header("location:index.php?n=chat&h=inline_chat&fid={$uid}&tid={$chatorid}&sid={$serverid}&isjump=1");   
		
    
    //note 屏蔽会员
    if($screen){
        $_MooClass['MooMySQL']->query("INSERT INTO {$dbTablePre}screen set uid='$uid',mid='$chatorid'");
        //MooMessage("屏蔽会员成功！", "index.php?n=myaccount&h=screen");
    }
    
    
    $chatorMsg = MooMembersData($chatorid);
    //约束会员在址输入参数
    if($chatorid == $uid){
        //exit('自己不可与自己聊天<a href="index.php?n=service&h=history">查看聊天记录</a>');
        MooMessage('自己不可与自己聊天','index.php');
    }elseif(!$chatorMsg){
        //exit('无此会员<a href="index.php?n=service&h=history">查看聊天记录</a>');
        if(!$_MooClass['MooMySQL']->getOne("select * from {$dbTablePre}server where sid='$chatorid'",true)){
            MooMessage('无此会员','index.php');
        }
    }elseif($chatorMsg['gender'] == $user_arr['gender']){
        MooMessage('不可与同性在线聊天','index.php');
    }
    //查找屏蔽会员表，查看是否允许对方邮件
    if($sta = $_MooClass['MooMySQL']->getOne("select screenid from {$dbTablePre}screen where (uid='$uid' and mid='$chatorid') or (uid='$chatorid' and mid='$uid')",true)){
        //exit('不可与此会员在线聊天，可能对方是被您屏蔽的会员，<a href="index.php?n=myaccount&h=screen">查看</a>');
        MooMessage('不可与此会员在线聊天，可能对方是被您屏蔽的会员','index.php?n=myaccount&h=screen');
    }
    
	
    
    $result=$_MooClass['MooMySQL']->getOne("select groupid from web_admin_user where uid='{$serverid}'",true);
    $groupid=$result['groupid'];
    //系统管理员权限
    /* $GLOBALS['system_admin'] = array(60);
    if(in_array($groupid,$GLOBALS['system_admin'])){
        $serverid=null;
    } */
    
    //客服不能模拟聊天
    
    if($serverid && $user_arr['usertype']!=3 ){
        MooMessage('对不起您不能模拟操作','javascript:history.go(-1);','04');
        exit;
    }
    
    $result=$_MooClass['MooMySQL']->getOne("select groupid from web_admin_user where uid='{$serverid}'",true);
	$groupid=$result['groupid'];
    if($serverid && $user_arr['usertype']==3 && !in_array($groupid,$GLOBALS['admin_aftersales'])){
        MooMessage('对不起您不能模拟操作','javascript:history.go(-1);','04');
        exit;
    }
    

		 
    /*if($serverid){
    $chatlog = $_MooClass['MooMySQL']->getAll("select s_id from {$dbTablePre}service_chat where ((s_uid='$uid' and s_fromid='$chatorid') or (s_uid='$chatorid' and s_fromid='$uid')) and s_status=0");
    }else{*/
        $chatlog = $_MooClass['MooMySQL']->getAll("select s_id from {$dbTablePre}service_chat where s_uid='$uid' and s_fromid='$chatorid' and is_server!='$uid' and s_status=0",0,0,0,true);
        //$chatlog = $_MooClass['MooMySQL']->getAll("select s_id from {$dbTablePre}service_chat where ((s_uid='$uid' and s_fromid='$chatorid') or (s_uid='$chatorid' and s_fromid='$uid')) and is_server!='$uid' and s_status=0");
    //}
    //当双方消息为空时判断打开聊天窗口的会员的级别
    if(!$chatlog){
        if(!$serverid){////判断是不是客服伪造登录
		
        //note 是否高级会员
        if(!get_userrank($uid)){
		      
                require MooTemplate('public/nochat_story', 'module');
                exit;   
        }
        }else{
            Mooserverlog(4,$dbTablePre."service_chat","模拟用户在线聊天",$serverid);
        }
    }

    $usernickname = $user_arr;

    require MooTemplate('public/service_chat', 'module');
}

//发布约会
function service_dating(){
    global $_MooClass,$dbTablePre,$user,$userid,$user_arr;
    if(MOOPHP_ALLOW_FASTDB){
        $userchoice = MooFastdbGet('members_choice','uid',$userid);
    }else{
        $userchoice = $_MooClass['MooMySQL']->getOne("SELECT * FROM {$dbTablePre}members_choice WHERE uid = '$userid'");
    }

    if($_POST){
        $choice['age1'] = MooGetGPC('dating_age1','integer','P');
        $choice['age2'] = MooGetGPC('dating_age2','integer','P');
        $choice['workprovince'] = MooGetGPC('workProvince','integer','P');
        $choice['workcity'] = MooGetGPC('workCity','integer','P');
        $choice['salary'] = MooGetGPC('salary','integer','P');
        $choice['marriage'] = MooGetGPC('marriage2','integer','P');
        updatetable('members_choice',$choice,array('uid'=>$userid));
        $param['uid'] = $userid;
        $param['nickname'] = $user_arr['nickname'];
        $param['subject'] = MooGetGPC('subject','string','P');
        $param['content'] = MooGetGPC('content','string','P');
        $param['dating_province'] = MooGetGPC('dating_province','string','P');
        $param['dating_city'] = MooGetGPC('dating_city','integer','P');
        $param['how_to_go'] = MooGetGPC('how_to_go','integer','P');
        $param['how_to_return'] = MooGetGPC('how_to_return','integer','P');
        $param['fee_assign'] = MooGetGPC('fee_assign','integer','P');
        $param['expire_time'] = MooGetGPC('expire_time','string','P');
        $param['dateline'] = $GLOBALS['timestamp'];
        inserttable('dating',$param);
        MooMessage('您的约会申请已提交！您的资料页面正在被其他会员浏览，请尽快完善资料，获得更多的约会机会。 ','index.php?n=service&h=mydating');
    }
    require MooTemplate('public/service_dating','module');
}

//我发起的约会
function service_mydating(){
    global $_MooClass,$dbTablePre,$userid,$user_arr,$pagesize;
    
    $page = max(1,intval($_GET['page']));
    $start = ($page - 1) * $pagesize;
    $currenturl = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"]; 
    $currenturl2 = preg_replace("/(&page=\d+)/","",$currenturl);
    
    $where = '';
    $d = MooGetGPC('d','string','G');
    if($d == 'respond'){
        $where = " and dating_total>0";
    }
    $sql = "select count(*) as count from {$dbTablePre}dating where uid='$userid' $where";
    $mydating = $_MooClass['MooMySQL']->getOne($sql,true);
    $count = $mydating['count'];
    if($count > 0){
        $sql = "select * from {$dbTablePre}dating where uid='$userid' $where order by did desc LIMIT $start,$pagesize";
        $dating_list =  $_MooClass['MooMySQL']->getAll($sql);
    }
    
    
    require MooTemplate('public/service_mydating','module');
}

//查看我发起的约会
function service_msg_managedate(){
    global $_MooClass,$dbTablePre,$userid,$user_arr;
    //分页
    $page_per = 6;
    $page_url = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"]; 
    $page_url = preg_replace( "/(&page=\d+)/","",$page_url );
    $page_now = MooGetGPC( 'page', 'integer', 'G');
    
    $did = MooGetGPC('id','integer','G');

    $sql = "select * from {$dbTablePre}dating where did='$did' and uid='$userid'";
    $dating = $_MooClass['MooMySQL']->getOne($sql);
    if(!$dating){
        MooMessage('此约会不存在，请确认。 ','index.php?n=space&h=viewpro&uid={$userid}');
    }
    //约会状态
    $dating_status = dating_status($dating);
    
    $sql = "select age1,age2,salary,marriage,workprovince,workcity from {$dbTablePre}members_choice where uid='$userid'";
    $choice = $_MooClass['MooMySQL']->getOne($sql);
    //包含配置文件
    include_once("./module/crontab/crontab_config.php");
    $province_key = $dating['dating_province'];
    $city_key = $dating['dating_city'];

    //响应过约会
    $dating_count = $dating['dating_total'];
    $page_num = ceil( $dating_count / $page_per );
    if( $page_now > $page_num ) $page_now = $page_num;
    if( $page_now < 1 ) $page_now = 1;
    $start = ($page_now - 1) * $page_per;
    if($dating_count > 0){
        $sql = "select * from {$dbTablePre}dating_respond where did='$did' order by rid desc LIMIT $start,$page_per";
        $dating_respond = $_MooClass['MooMySQL']->getAll($sql);
        foreach($dating_respond as $value){
            $uid_list .= $comma.$value['uid'];
            $comma = ', ';
        }
        $sql = "select uid,lastvisit from {$dbTablePre}members_login where uid in($uid_list)";
        $user_list = $_MooClass['MooMySQL']->getAll($sql);
        foreach($user_list as $user){
            $user_lastvisit[$user['uid'].'_lastvisit'] = $user['lastvisit'];
        }
    }
    require MooTemplate('public/service_msg_managedate','module');
}

//确认约会
function service_dating_confirm(){
    global $_MooClass,$dbTablePre;
    $agree = MooGetGPC('d','integer','G');
    $did = MooGetGPC('did','integer','G');
    $rid = MooGetGPC('rid','integer','G');
    $uid = MooGetGPC('uid','integer','G');
    if(!in_array($agree,array(1,2,3))){
        $agree = 1;
    }
    $sql = "update {$dbTablePre}dating_respond set agree='$agree' where rid='$rid' and uid='$uid'";
    $_MooClass['MooMySQL']->query($sql);
    if($agree == 2) $message = '您已同意此约会';
    elseif($agree == 3) $message = '您已拒绝此约会'; 
    MooMessage($message,'index.php?n=service&h=msg_managedate&id='.$did);
    
}

//确定应用风格
function service_confirm_style(){
    global $_MooClass,$dbTablePre,$user_arr;
    $sql = "update {$dbTablePre}diamond set status=3 where uid='{$user_arr['uid']}'";
    $_MooClass['MooMySQL']->query($sql);
    MooMessage('恭喜您已成功应用新的皮肤风格。','index.php?n=service');
}

//对TA评价
function service_comment(){
    global $_MooClass,$dbTablePre,$timestamp,$user_arr,$serverid,$timestamp,$memcached; 
    $sendtoid = MooGetGPC('sendtoid','integer');
    $userid=$user_arr['uid'];
	
	
	
	if(empty($sendCommentCount)) $sendCommentCount=0;
	$sendCommentCount=$memcached->get('sendComment'.$userid);
	if($sendCommentCount>10){
	   MooMessage('当天累计评价超过10条','javascript:history.go(-1);','04');
	}
	
	 if($serverid && $user_arr['usertype']!=3){
        MooMessage('对不起您不能模拟操作','javascript:history.go(-1);','04');
        exit;
    }
	
	  
    $query = $_MooClass['MooMySQL']->getOne("SELECT count(1) as c FROM {$dbTablePre}pic WHERE uid = '$sendtoid'");
    $pic_total = $query['c'];
	
	
	$send_user = leer_send_user1($sendtoid);
    $send_user_gender = $send_user['gender'] == 1 ? "女" : "男"; //发送者性别
	
    if($sendtoid == $userid){
        MooMessage('自己不可以给自己评价','javascript:history.go(-1);');
        exit;
    }
    if($send_user['gender'] == $user_arr['gender']) {
        MooMessage('同性之间不可以评价','javascript:history.go(-1);');
        exit;
    }
	
  
    
    //提交评价
    if($_POST){
        $data['cuid'] = $user_arr['uid'];
        $data['uid'] = MooGetGPC('uid','integer','P');
        $data['dateline'] = time();
        $data['content'] = trim(safeFilter(MooGetGPC('content','string','P')));
        if(empty($data['content'])){
            MooMessage('评价内容不能为空','javascript:history.go(-1);');
        }
        $members = MooMembersData($data['uid']);
        if(empty($members)){
            MooMessage('您评价的用户不存在，请确认','javascript:history.go(-1);');  
        }
        inserttable('members_comment',$data);
        
        $send_user_gender = $user_arr['gender'] == 1 ? "女" : "男"; //发送者性别
        
        $memcached->set('sendComment'.$userid,++$sendCommentCount,0,28800);
		
		
        //************提醒所属客服**************
        $sid = $user_arr['sid'];
        $title = '您的会员 '.$user_arr['uid'].' 评价了 '.$sendtoid;
        $awoketime = $timestamp+3600;
        $sql_remark = "insert into {$dbTablePre}admin_remark set sid='{$sid}',title='{$title}',content='{$title}',awoketime='{$awoketime}',dateline='{$timestamp}'";
        $res = $_MooClass['MooMySQL']->query($sql_remark);
        
        
        if($members['usertype']==1){
            //fanglin暂时屏蔽
            Push_message_intab($sendtoid,$members['telphone'],"评价人","用户ID：".$userid.",".$send_user_gender.",已关注了您，请速查看！4008787920【真爱一生网】",$userid);
        }
        MooMessage('评价成功','index.php?n=space&h=viewpro&uid='.$data['uid'],'05');exit;
    }
    
  
    
    
    
    $result=$_MooClass['MooMySQL']->getOne("select groupid from web_admin_user where uid='{$serverid}'");
    $groupid=$result['groupid'];
    //系统管理员权限
    $GLOBALS['system_admin'] = array(60);
    if(in_array($groupid,$GLOBALS['system_admin'])){
        $serverid=null;
    }
    
   
    
    
    require MooTemplate('public/service_comment', 'module');
}

//谁评价过我
function service_mycomment(){
    global $_MooClass,$dbTablePre,$timestamp,$user_arr; 
    $userid = $user_arr['uid'];
    $member_level = get_userrank($userid);
    if($member_level != 1){
        MooMessage('您不是钻石或高级会员，不能查看其TA会员对您的评价，快升级为钻石或高级会员吧','index.php?n=payment&h=diamond');
    }
    
    $pagesize = 4;
    
    //note 获得当前url
    $currenturl = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"]; 
    $currenturl2 = preg_replace("/(&page=\d+)/","",$currenturl);
    
    //note 获得第几页
    $page = empty($_GET['page']) ? 1:$_GET['page'];
        
    //note limit查询开始位置
    $start = ($page - 1) * $pagesize;
    
    $query = $_MooClass['MooMySQL']->getOne("select count(1) as c FROM {$dbTablePre}members_comment WHERE uid = '$userid' AND receive_del=0 AND is_pass=1");
    $total = $query['c'];
    
    $query = $_MooClass['MooMySQL']->getOne("select count(1) as c FROM {$dbTablePre}members_comment WHERE cuid = '$userid' AND send_del=0 ");
    $total2 = $query['c'];
    
    if($total>0){
        $sql = "SELECT * FROM {$dbTablePre}members_comment WHERE uid = '$userid' AND receive_del=0 AND is_pass=1 order by id desc LIMIT $start,$pagesize";
        $comment_list  = $_MooClass['MooMySQL']->getAll($sql);
    }
    
    require MooTemplate('public/service_mycomment', 'module');
}

//删除对我的评价
function service_delmycomment(){
    global $_MooClass,$dbTablePre,$timestamp,$user_arr; 
    $del_id_arr = MooGetGPC('id','string','P');
    if(!empty($del_id_arr)){
        $del_id_list = implode(',',$del_id_arr);
        $sql = "UPDATE {$dbTablePre}members_comment SET receive_del=1 WHERE id IN($del_id_list) AND uid='{$user_arr['uid']}'";
        $_MooClass['MooMySQL']->query($sql);
    }
    MooMessage("删除成功",'index.php?n=service&h=mycomment');
}

//我评价过谁
function service_mycommentwho(){
    global $_MooClass,$dbTablePre,$timestamp,$user_arr; 
    $userid = $user_arr['uid'];
    $member_level = get_userrank($userid);
    if($member_level != 1){
        MooMessage('您不是钻石或高级会员，不能查看评价，快升级为钻石或高级会员吧','index.php?n=payment&h=diamond');
    }
    $pagesize = 4;
    
    //note 获得当前url
    $currenturl = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"]; 
    $currenturl2 = preg_replace("/(&page=\d+)/","",$currenturl);
    
    //note 获得第几页
    $page = empty($_GET['page']) ? 1:$_GET['page'];
        
    //note limit查询开始位置
    $start = ($page - 1) * $pagesize;
    
    $query = $_MooClass['MooMySQL']->getOne("select count(1) as c FROM {$dbTablePre}members_comment WHERE uid = '$userid' AND receive_del=0 AND is_pass=1");
    $total = $query['c'];
    
    $query = $_MooClass['MooMySQL']->getOne("select count(1) as c FROM {$dbTablePre}members_comment WHERE cuid = '$userid' AND send_del=0");
    $total2 = $query['c'];
    
    if($total2>0){
        $sql = "SELECT * FROM {$dbTablePre}members_comment WHERE cuid = '$userid' AND send_del=0 order by id desc LIMIT $start,$pagesize";
        $comment_list  = $_MooClass['MooMySQL']->getAll($sql);
        //print_r($comment_list);
    }
    
    require MooTemplate('public/service_mycommentwho', 'module');
}

//删除我评论过谁
function service_delmycommentwho(){
    global $_MooClass,$dbTablePre,$timestamp,$user_arr; 
    $del_id_arr = MooGetGPC('id','string','P');
    if(!empty($del_id_arr)){
        $del_id_list = implode(',',$del_id_arr);
        $sql = "UPDATE {$dbTablePre}members_comment SET send_del=1 WHERE id IN($del_id_list) AND cuid='{$user_arr['uid']}'";
        $_MooClass['MooMySQL']->query($sql);
    }
    MooMessage("删除成功",'index.php?n=service&h=mycommentwho');
}

//请求查看他的身份证信息
function service_request_sms(){
    global $_MooClass,$dbTablePre,$timestamp,$user_arr,$hzn,$serverid,$timestamp; 
    $userid=$user_arr['uid'];
    if(empty($userid)) {  header("Location:login.html"); }
    $member_level = get_userrank($userid);
    if($member_level != 1){
        MooMessage('您不是钻石或高级会员，不能请求查看身份证信息，快升级为钻石或高级会员吧','index.php?n=payment&h=diamond');
    }
    $sql = "SELECT sms FROM {$dbTablePre}certification WHERE uid='$userid'";
    $certification = $_MooClass['MooMySQL']->getOne($sql,true);
    if($certification['sms'] != 1){
        MooMessage('您未进行身份通认证，请先进行身份通认证','index.php?n=myaccount&h=smsindex');
    }
    $sendtoid = MooGetGPC('sendtoid','integer');
    
    $send_user = leer_send_user1($sendtoid);
    //$send_user_gender = $send_user['gender'] == 1 ? "女" : "男"; //发送者性别
    $send_user_gender = $user_arr['gender'] == 1 ? "女" : "男"; //发送者性别
    
    $query = $_MooClass['MooMySQL']->getOne("SELECT count(1) as c FROM {$dbTablePre}pic WHERE uid = '$sendtoid'");
    $pic_total = $query['c'];
    
    if($sendtoid == $userid){
        MooMessage('自己不可以请求查看自己的信息','javascript:history.go(-1);');
        exit;
    }
    if($send_user['gender'] == $user_arr['gender']) {
        MooMessage('同性之间不可以请求查看','javascript:history.go(-1);');
        exit;
    }
    
    if($serverid && $user_arr['usertype']!=3){
        MooMessage('对不起您不能模拟操作','javascript:history.go(-1);','04');
        exit;
    }
    
    
    
    //$sql = "SELECT sms FROM {$dbTablePre}certification WHERE uid='$userid'";
    
    $time = time();
    $sql = "SELECT id FROM {$dbTablePre}members_requestsms WHERE uid='{$sendtoid}' AND ruid='{$userid}'";
    if($_MooClass['MooMySQL']->getOne($sql,true)){
        $sql = "UPDATE {$dbTablePre}members_requestsms SET request_total=request_total+1 WHERE uid='{$sendtoid}' AND ruid='{$userid}'";
        $_MooClass['MooMySQL']->query($sql);
    }else{
        $sql = "INSERT INTO {$dbTablePre}members_requestsms SET uid='{$sendtoid}',ruid='{$userid}',dateline='{$time}'";
        $_MooClass['MooMySQL']->query($sql);
    }
    
    //***********提醒所属客服************
    $sid = $user_arr['sid'];
    $title = '您的会员 '.$user_arr['uid'].' 向 '.$sendtoid.' 索取身份';
    $awoketime = $timestamp+3600;
    $sql_remark = "insert into {$dbTablePre}admin_remark set sid='{$sid}',title='{$title}',content='{$title}',awoketime='{$awoketime}',dateline='{$timestamp}'";
    $res = $_MooClass['MooMySQL']->query($sql_remark);
    
    
    $members = MooMembersData($sendtoid);
      //fanglin暂时屏蔽  
    Push_message_intab($sendtoid,$members['telphone'],"索取身份","真爱一生网www.zhenaiyisheng.cc用户ID：".$userid.",".$send_user_gender.",请求查看您的身份证信息，请速回复哦！4006780405",$userid);
    require MooTemplate('public/service_requestsms', 'module');
}

//已收到的查看身份证认证请求
function service_request_sms_list(){
    global $_MooClass,$dbTablePre,$timestamp,$user_arr; 
    $userid = $user_arr['uid'];
    //$member_level = get_userrank($userid);
    
    if(isset($_GET['status']) && in_array($_GET['status'],array(1,2,3)) && isset($_GET['id'])){
        $status = MooGetGPC('status','integer','G');
        $id = MooGetGPC('id','integer','G');
        $sql = "UPDATE {$dbTablePre}members_requestsms SET  status='{$status}' WHERE id='{$id}' AND uid='{$userid}'";
        $_MooClass['MooMySQL']->query($sql);
        if($status == 1){
            MooMessage("操作成功，您已同意对方查看",'index.php?n=service&h=request_sms_list');
        }elseif($status==2){
            MooMessage("操作成功，您已拒绝对方查看",'index.php?n=service&h=request_sms_list');
        }else{
            MooMessage("操作成功",'index.php?n=service&h=request_sms_list');
        }
    }
    $pagesize = 4;
    
    //note 获得当前url
    $currenturl = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"]; 
    $currenturl2 = preg_replace("/(&page=\d+)/","",$currenturl);
    
    //note 获得第几页
    $page = empty($_GET['page']) ? 1:$_GET['page'];
        
    //note limit查询开始位置
    $start = ($page - 1) * $pagesize;
    
    $query = $_MooClass['MooMySQL']->getOne("select count(1) as c FROM {$dbTablePre}members_requestsms WHERE uid = '$userid'");
    $total = $query['c'];
    
    $query = $_MooClass['MooMySQL']->getOne("select count(1) as c FROM {$dbTablePre}members_requestsms WHERE ruid = '$userid'");
    $total2 = $query['c'];
    
    if($total>0){
        $sql = "SELECT * FROM {$dbTablePre}members_requestsms WHERE uid = '$userid' order by id desc LIMIT $start,$pagesize";
        $request_list  = $_MooClass['MooMySQL']->getAll($sql);
    }
    require MooTemplate('public/service_request_sms_list', 'module');
}

//已发送的身份证查看请求
function service_send_request_sms_list(){
    global $_MooClass,$dbTablePre,$timestamp,$user_arr; 
    $userid = $user_arr['uid'];
    //$member_level = get_userrank($userid);
    $pagesize = 4;
    
    //note 获得当前url
    $currenturl = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"]; 
    $currenturl2 = preg_replace("/(&page=\d+)/","",$currenturl);
    
    //note 获得第几页
    $page = empty($_GET['page']) ? 1:$_GET['page'];
        
    //note limit查询开始位置
    $start = ($page - 1) * $pagesize;
    
    $query = $_MooClass['MooMySQL']->getOne("select count(1) as c FROM {$dbTablePre}members_requestsms WHERE uid = '$userid'");
    $total = $query['c'];
    
    $query = $_MooClass['MooMySQL']->getOne("select count(1) as c FROM {$dbTablePre}members_requestsms WHERE ruid = '$userid'");
    $total2 = $query['c'];
    
    if($total2>0){
        $sql = "SELECT * FROM {$dbTablePre}members_requestsms WHERE ruid = '$userid' order by id desc LIMIT $start,$pagesize";
        $request_list  = $_MooClass['MooMySQL']->getAll($sql);
    }
    $time = time();
    require MooTemplate('public/service_send_request_sms_list', 'module');
}


//删除身份证请求列表
function service_delsms(){
    global $_MooClass,$dbTablePre,$timestamp,$user_arr; 
    $del_id_arr = MooGetGPC('delsms','string','P');
    if(empty($del_id_arr)){
        MooMessage('请选择要删除的内容','javascript:history.go(-1);');
    }
    if(!empty($del_id_arr)){
        $del_id_list = implode(',',$del_id_arr);
        //$sql = "UPDATE {$dbTablePre}members_comment SET receive_del=1 WHERE id IN($del_id_list) AND uid='{$user_arr['uid']}'";
        $sql = "DELETE FROM {$dbTablePre}members_requestsms WHERE id IN($del_id_list)";
        $_MooClass['MooMySQL']->query($sql);
    }
    MooMessage("删除成功",'index.php?n=service&h=request_sms_list');
}

//关注会员动态
function attention_member(){
    global $_MooClass,$dbTablePre,$timestamp,$user_arr;
    $pagesize = 4;
    
    //note 获得当前url
    $currenturl = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"]; 
    $currenturl2 = preg_replace("/(&page=\d+)/","",$currenturl);
    
    //note 获得第几页
    $page = empty($_GET['page']) ? 1:$_GET['page'];
        
    //note limit查询开始位置
    $start = ($page - 1) * $pagesize;

    $sql="SELECT you_contact_other  FROM {$dbTablePre}service_contact where other_contact_you='{$user_arr['uid']}' and send_del=0 and is_server=0 ";
    $contact_uid=$_MooClass['MooMySQL']->getAll($sql);
    if($contact_uid){
	    $t = $timestamp-86400*7;
        foreach($contact_uid as $v_uid){
            $uid_arr[]=$v_uid['you_contact_other'];
        }
        $uid_list=join(",",$uid_arr);
    
        $sql="SELECT * FROM {$dbTablePre}members_sns where uid in ($uid_list) and dateline>{$t} LIMIT $start,$pagesize";
        $update_info=$_MooClass['MooMySQL']->getAll($sql);
      
        foreach($update_info as $update){
            $update_arr[$update['uid']][]=$update;
        }
        //print_r($update_arr);
       
        $query = $_MooClass['MooMySQL']->getOne("SELECT count(distinct uid) as c FROM {$dbTablePre}members_sns where uid in ($uid_list) and dateline>{$t}");
        $total = $query['c'];
    }
    require MooTemplate('public/service_attention_list','module');
}



//黑名单
function black_member(){
    global $_MooClass,$dbTablePre,$timestamp,$user_arr;
    $is_action=MooGetGPC("is_post","string",'G');
    if($is_action=="add"){
        $black_uid=MooGetGPC("black_uid",'integer','P');
        $uid = 0;
        if($user_arr['uid']!=$black_uid){       
            if($black_uid){
                $uid = $_MooClass['MooMySQL']->getOne("SELECT uid FROM {$dbTablePre}members_search WHERE uid='{$black_uid}' LIMIT 1 ",true);
                $uid = $uid['uid'];
                if (empty($uid))$msg = '没有此会员，请确保您输入的会员ID存在。';
            }else{
                $msg = '请输入您要加入黑名单的会员ID';
            }
            if($uid){
                $_MooClass['MooMySQL']->query("INSERT INTO {$dbTablePre}screen set uid='{$user_arr['uid']}',mid='$black_uid'");
                $msg = '成功将会员（ID：'.$uid.'）加入黑名单。';
            }
        }else{
        	$msg = '您不能将自己加到黑名单！';
        }
        MooMessage($msg,'index.php?n=service&h=black');
    }
    if($is_action=="delblack"){
        $del_id_arr = MooGetGPC('id','string','P');
        if(empty($del_id_arr)){
        MooMessage('请选择要删除的内容','javascript:history.go(-1);');
        }
        if(!empty($del_id_arr)){
            $del_id_list = implode(',',$del_id_arr);
            
            $sql = "DELETE FROM {$dbTablePre}screen WHERE mid IN($del_id_list)";
            $_MooClass['MooMySQL']->query($sql);
        }
        MooMessage("删除成功",'index.php?n=service&h=black','05');
    }
    $pagesize = 4;
    
    //note 获得当前url
    $currenturl = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"]; 
    $currenturl2 = preg_replace("/(&page=\d+)/","",$currenturl);
    
    //note 获得第几页
    $page = empty($_GET['page']) ? 1:$_GET['page'];
        
    //note limit查询开始位置
    $start = ($page - 1) * $pagesize;
    $query = $_MooClass['MooMySQL']->getOne("SELECT count(1) as c FROM {$dbTablePre}screen where uid='{$user_arr['uid']}'");
    $total = $query['c'];
    $sql="SELECT * FROM {$dbTablePre}screen where uid='{$user_arr['uid']}' LIMIT $start,$pagesize";
    $black_uid=$_MooClass['MooMySQL']->getAll($sql);
    $page_list = multimail($total,$pagesize,$page,$currenturl2);
    require MooTemplate('public/service_black_list','module');
}


//发帖验证码
function post_code(){
	//验证码
	$img = MooAutoLoad('MooSeccode');
	$img -> outCodeImage(125,30,4);
}


/***************************************控制层****************************************/ 
$pagesize = 10;         //服务中心信息每页显示的条目数

include "function.php";
include "./backend/include/config.php";

$back_url='index.php?'.$_SERVER['QUERY_STRING'];




//$serverid = Moo_is_kefu();

if(!$userid) {header("location:login.html");} //&back_url=".urlencode($back_url)

if (isset($_MooCookie['kefu'])){ 
	$kefu = MooAuthCode($_MooCookie['kefu'], 'DECODE');
	if($kefu) list($hzn,$serverid) = explode("\t", $kefu);
}

$_GET['t'] = empty($_GET['t']) ? '' : $_GET['t'];
switch ($_GET['h']) {
    //note 短消息功能
    case "message" :
        require 'message.php';
        break;
    
    //note 委托真爱一生功能
    case "commission" :
        require 'commission.php';
        break;
        
    //note 您的意中人
    case "liker" :
        require 'liker.php';
        break;
    
    //note 您的秋波 
    case "leer" :
        require 'leer.php';
        break;
    //note test您的秋波 
    case "leertest" :
        require 'leertest.php';
        break;
        
    //note 您的玫瑰
    case "gift" :
        require 'gift.php';
        break;
        
    //note 您留意过谁
    case "mindme" :
        require 'advert.php';
        break;
        
    //note 真爱一生心理测试
    case "lovetest" :
        require 'lovetest.php';
        break;
            
    //note 用户聊天记录
    case 'history':
        service_story($userid);
        break;
        
    //note 用户在线聊天
    case 'chat':

        service_chat_msg($userid);
        break;
    
    //note 索要身份证件
    case 'idcart':
        require 'idcart.php';
        break;
   
  
    case 'dating':
        service_dating();
        break;
    case 'mydating':
        service_mydating();
        break;
    case 'msg_managedate':
        service_msg_managedate();
        break;
    case 'dating_confirm':
        service_dating_confirm();
        break;
    case 'confirm_style':
        service_confirm_style();
        break;
    case 'comment':
        service_comment();
        break;
    case 'attention';
        attention_member();
        break;
    case 'black':
        black_member();
    break;
    case 'mycomment':
        service_mycomment();
        break;
    case 'delmycomment':
        service_delmycomment();
        break;
    case 'mycommentwho':
        service_mycommentwho();
        break;
    case 'delmycommentwho':
        service_delmycommentwho();
        break;
    case 'request_sms':
        service_request_sms();
        break;
    case 'request_sms_list':
        service_request_sms_list();
        break;
    case 'send_request_sms_list':
        service_send_request_sms_list();
        break;
    case 'delsms':
        service_delsms();
        break;
    //note 默认访问服务中心的首页
    default :
        require 'main.php';
    break;
}
?>

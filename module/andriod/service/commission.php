<?php
include "module/andriod/function.php";
//note 我委托真爱一生联系他们
function getmycontact() {
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
    
    $pagesize = 10;
    //note 获得当前url
    $currenturl = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"]; 
    $currenturl2 = preg_replace("/(&page=\d+)/","",$currenturl);
    $currenturl2 = preg_replace("/(&page2=\d+)/","",$currenturl2);
    $currenturl2 = preg_replace("/(&page3=\d+)/","",$currenturl2);

    //note ---------------------等待回应的请求
    //note 获得第几页
    //$page = empty($_GET['page']) ?  '1' : $_GET['page'];
    $page = empty($_GET['page']) ? 1 : $_GET['page'];
    $stat = empty($_GET['stat']) ? 1 : $_GET['stat'];
    //note limit查询开始位置
    $start = ($page - 1) * $pagesize;
    //note 查询等待回应的请求统计总数
    //$ret_count = $_MooClass['MooMySQL']->getOne("SELECT count(*) as c FROM {$dbTablePre}service_contact WHERE other_contact_you = '$userid'  AND stat = '1' and send_del=0 and is_server=0");
    //$total = $ret_count['c'];
    
    $ret = $_MooClass['MooMySQL']->getAll("SELECT stat,count(you_contact_other) as c FROM {$dbTablePre}service_contact WHERE other_contact_you = '$userid'  and send_del=0  group by stat");
    $total = array();
    $total[1] = 0;
    $total[2] = 0;
    $total[3] = 0;
    $total[4] = 0;
    foreach($ret as $v){
        switch($v['stat']){
            case 1: $total[1] = $v['c'];break;
            case 2: $total[2] = $v['c'];break;
            case 3: $total[3] = $v['c'];break;
            case 4: $total[4] = $v['c'];break;
        }
    }
    //note 查询等待回应的请求
    
        $contact = $_MooClass['MooMySQL']->getAll("SELECT mid,stat,you_contact_other as uid FROM {$dbTablePre}service_contact WHERE other_contact_you = '$userid' and send_del=0 and is_server=0 order by sendtime desc LIMIT $start,$pagesize");
    	if(empty($contact)){
			$errors = "empty";
			echo return_data($errors);exit;
		}
        $con_arr = array();
        foreach($contact as $v){
        	$con_arr[] = $v['uid'];
        }
        $con_shadow = implode(',', $con_arr);
        $con_shadow_val = $_MooClass['MooMySQL']->getAll("SELECT uid,nickname,birthyear,height,education,salary,province,city FROM {$dbTablePre}members_search WHERE uid in ({$con_shadow})");
        $con_sha_val = $_MooClass['MooMySQL']->getAll("SELECT uid,mainimg FROM {$dbTablePre}members_base WHERE uid in ({$con_shadow})");
        $contact_all = $con_shadow_val;
        /*
        foreach($contact_all as $key =>$val){
        	foreach($contact as $value){
        		if($value['uid'] == $val['uid']){
        			$contact_all[$key]['mid'] = $value['mid'];
        			$contact_all[$key]['stat'] = $value['stat'];      		
        		}
        	}
        	foreach($con_sha_val as $va){
        		if($va['uid'] == $val['uid']){
        			$contact_all[$key]['mainimg'] = $va['mainimg'];      		
        		}
        	}
        }
        */
		foreach($contact as $key =>$val){
        	foreach($contact_all as $value){
        		if($value['uid'] == $val['uid']){
        			
        			$contact[$key]['nickname'] = $value['nickname'];
        			$contact[$key]['birthyear'] = $value['birthyear']; 
        			$contact[$key]['height'] = $value['height'];
        			$contact[$key]['education'] = $value['education'];
        			$contact[$key]['salary'] = $value['salary'];
        			$contact[$key]['province'] = $value['province'];   
        			$contact[$key]['city'] = $value['city'];    		
        		}
        	}
        	foreach($con_sha_val as $va){
        		if($va['uid'] == $val['uid']){
        			$contact[$key]['mainimg'] = $va['mainimg'];      		
        		}
        	}
        }
        
        
        
        $returnarr = array();
        $returnarr['ret'] = $ret;
        $returnarr['contact'] = $contact_all;
        echo return_data($contact);
        
    
    
}


//note 他们委托真爱一生联系我
function getothercontact() {
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
    
    $pagesize = 10;
    //note 获得当前url
    $currenturl = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"]; 
    $currenturl2 = preg_replace("/(&page=\d+)/","",$currenturl);
    //$currenturl2 = preg_replace("/(&page2=\d+)/","",$currenturl2);
    //$currenturl2 = preg_replace("/(&page3=\d+)/","",$currenturl2);

    //note ---------------------等待回应的请求
    //note 获得第几页
    //$page = empty($_GET['page']) ?  '1' : $_GET['page'];
    $page = empty($_GET['page']) ? 1 : $_GET['page'];
    $stat = empty($_GET['stat']) ? 1 : $_GET['stat'];
    //note limit查询开始位置
    $start = ($page - 1) * $pagesize;
    //note 查询等待回应的请求统计总数
    //$ret_count = $_MooClass['MooMySQL']->getOne("SELECT count(*) as c FROM {$dbTablePre}service_contact WHERE other_contact_you = '$userid'  AND stat = '1' and send_del=0 and is_server=0");
    //$total = $ret_count['c'];
    
    $ret = $_MooClass['MooMySQL']->getAll("SELECT stat,count(other_contact_you) as c FROM {$dbTablePre}service_contact WHERE you_contact_other = '$userid'  and send_del=0  group by stat");
    $total = array();
    $total[1] = 0;
    $total[2] = 0;
    $total[3] = 0;
    $total[4] = 0;
    foreach($ret as $v){
        switch($v['stat']){
            case 1: $total[1] = $v['c'];break;
            case 2: $total[2] = $v['c'];break;
            case 3: $total[3] = $v['c'];break;
            case 4: $total[4] = $v['c'];break;
        }
    }
    //note 查询等待回应的请求
   
        $contact = $_MooClass['MooMySQL']->getAll("SELECT mid,stat,other_contact_you as uid FROM {$dbTablePre}service_contact WHERE you_contact_other = '$userid' AND stat < 4 and send_del=0 and is_server=0 order by sendtime desc LIMIT $start,$pagesize");
    	if(empty($contact)){
			$errors = "empty";
			echo return_data($errors);exit;
		}
        $con_arr = array();
        foreach($contact as $v){
        	$con_arr[] = $v['uid'];
        }
        $con_shadow = implode(',', $con_arr);
        $con_shadow_val = $_MooClass['MooMySQL']->getAll("SELECT uid,nickname,birthyear,height,education,salary,province,city FROM {$dbTablePre}members_search WHERE uid in ({$con_shadow})");
        $con_sha_val = $_MooClass['MooMySQL']->getAll("SELECT uid,mainimg FROM {$dbTablePre}members_base WHERE uid in ({$con_shadow})");
    	$contact_all = $con_shadow_val;
		foreach($contact as $key =>$val){
        	foreach($contact_all as $value){
        		if($value['uid'] == $val['uid']){
        			
        			$contact[$key]['nickname'] = $value['nickname'];
        			$contact[$key]['birthyear'] = $value['birthyear']; 
        			$contact[$key]['height'] = $value['height'];
        			$contact[$key]['education'] = $value['education'];
        			$contact[$key]['salary'] = $value['salary'];
        			$contact[$key]['province'] = $value['province'];   
        			$contact[$key]['city'] = $value['city'];    		
        		}
        	}
        	foreach($con_sha_val as $va){
        		if($va['uid'] == $val['uid']){
        			$contact[$key]['mainimg'] = $va['mainimg'];      		
        		}
        	}
        }
        
        
        $returnarr = array();
        $returnarr['ret'] = $ret;
        $returnarr['contact'] = $contact_all;
        echo return_data($contact);
        
    
    
}



function oneajax() {
    global $_MooClass,$dbTablePre,$userid,$timestamp,$user_arr,$memcached;
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
    
    //$text1 =  MooCutstr(rtrim(MooGetGPC('text1','string','G')),180);
    //$text2 =  MooCutstr(rtrim(MooGetGPC('text2','string','G')),180);
    $mid = MooGetGPC('mid','integer','G');
    $stat = MooGetGPC('stat','integer','G');
    if($stat){
	    if($stat != '4') {
	        $_MooClass['MooMySQL']->query("UPDATE `{$dbTablePre}service_contact` SET `stat` = '$stat' WHERE `other_contact_you` = '$mid' and `you_contact_other` = '$uid' LIMIT 1 ");
	    }else if($stat == '4') {
	        $_MooClass['MooMySQL']->query("UPDATE `{$dbTablePre}service_contact` SET `stat` = '$stat',`receive_del` = '1' WHERE `other_contact_you` = '$mid' AND `you_contact_other` = '$uid' LIMIT 1 ");
	        
	    }
    	
    	$suc = "成功";
    	echo return_data($suc,true);exit;
    }
    else{
    	$suc = "失败";
    	echo return_data($suc,false);exit;
    }
    /*
    //note 接受请求
    if(!$stat) {
        //note 更新委托表中的字段
        $_MooClass['MooMySQL']->query("UPDATE `{$dbTablePre}service_contact` SET `accept_message` = '$text1',`accept_other` = '$text2',`stat` = '2' WHERE `other_contact_you` = '$acceptid' AND `you_contact_other` = '$userid' LIMIT 1 ");
        echo '1';
        exit;
        
    //note 如果$stat 是3就是考虑，4就是拒绝
    }else if($stat == '3') {
        $_MooClass['MooMySQL']->query("UPDATE `{$dbTablePre}service_contact` SET `stat` = '$stat' WHERE `other_contact_you` = '$acceptid' AND `you_contact_other` = '$userid' LIMIT 1 ");
        echo '1';
        exit;
    }else if($stat == '4') {
        $_MooClass['MooMySQL']->query("UPDATE `{$dbTablePre}service_contact` SET `stat` = '$stat',`receive_del` = '1' WHERE `other_contact_you` = '$acceptid' AND `you_contact_other` = '$userid' LIMIT 1 ");
        echo '1';
        exit;
    }else {
        echo '0';
        exit;       
    }
    */

}


//接受拒绝秋波
function leerajax() {
    global $_MooClass,$dbTablePre,$userid,$timestamp,$user_arr,$memcached;
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
    
    //$text1 =  MooCutstr(rtrim(MooGetGPC('text1','string','G')),180);
    //$text2 =  MooCutstr(rtrim(MooGetGPC('text2','string','G')),180);
    $mid = MooGetGPC('mid','integer','G');
    $stat = MooGetGPC('stat','integer','G');
    if($stat){
    	$_MooClass['MooMySQL']->query("UPDATE `{$dbTablePre}service_leer` SET `stat` = '$stat' WHERE `senduid` = '$mid' and `receiveuid` = '$uid' LIMIT 1 ");
    	$suc = "成功";
    	echo return_data($suc,true);exit;
    }
    else{
    	$suc = "失败";
    	echo return_data($suc,false);exit;
    }
    /*
    //note 接受请求
    if(!$stat) {
        //note 更新委托表中的字段
        $_MooClass['MooMySQL']->query("UPDATE `{$dbTablePre}service_contact` SET `accept_message` = '$text1',`accept_other` = '$text2',`stat` = '2' WHERE `other_contact_you` = '$acceptid' AND `you_contact_other` = '$userid' LIMIT 1 ");
        echo '1';
        exit;
        
    //note 如果$stat 是3就是考虑，4就是拒绝
    }else if($stat == '3') {
        $_MooClass['MooMySQL']->query("UPDATE `{$dbTablePre}service_contact` SET `stat` = '$stat' WHERE `other_contact_you` = '$acceptid' AND `you_contact_other` = '$userid' LIMIT 1 ");
        echo '1';
        exit;
    }else if($stat == '4') {
        $_MooClass['MooMySQL']->query("UPDATE `{$dbTablePre}service_contact` SET `stat` = '$stat',`receive_del` = '1' WHERE `other_contact_you` = '$acceptid' AND `you_contact_other` = '$userid' LIMIT 1 ");
        echo '1';
        exit;
    }else {
        echo '0';
        exit;       
    }
    */

}

//note 发出联系真爱一生
function sendcontact() {
    global $_MooClass,$dbTablePre,$timestamp,$user,$user_arr,$hzn,$serverid,$memcached;   
    
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
	$sendid = MooGetGPC('sendid','integer','P');
	$user_arr = MooMembersData($userid);
    $uid = $userid;
    
    
    
    $returnurl = 'index.php?'.$_SERVER['QUERY_STRING'];//返回的url
    
    //note 目前默认系统不审核
    $contact_sys_check= '0';
    //note 目前系统默认是委托真爱一生等待对方的回应
    $contact_stat = '1';
    $content =  MooCutstr(safeFilter(MooGetGPC('content','string','P')),200,'');
    
    
    //客服不可模拟操作
    /*if($hzn == "hongniangwang"){
        MooMessage('对不起您不能模拟操作','javascript:history.go(-1);','04');
        exit;
    }*/
    
    if($serverid && $user_arr['usertype']!=3){
       // MooMessage('对不起您不能模拟操作','javascript:history.go(-1);','04');
        $error = '对不起您不能模拟操作';
        echo return_data($error,false);exit;
    }
    
    
    //自己不可委托真爱一生联系自己
    if($sendid == $userid){
        //MooMessage('自己不可委托真爱一生联系自己','javascript:history.go(-1);','02');
        $error = '自己不可委托真爱一生联系自己';
        echo return_data($error,false);exit;
    }
    //note 双方屏蔽不给操作
    if(MooGetScreen($userid,$sendid)){
        //MooMessage('因特殊原因，委托失败',"index.php?n=service&h=rose&t=getmorerose",'03');
        $error = '因特殊原因，委托失败';
        echo return_data($error,false);exit;
    }
    //$formsubmit1 = MooGetGPC('formsubmit1','string');
    
    //委托联系人表
    $checkuser = $_MooClass['MooMySQL']->getOne("SELECT mid,sendtime FROM {$dbTablePre}service_contact WHERE you_contact_other='$sendid' AND other_contact_you = '$userid' and receive_del=0 and send_del=0 and is_server=0");
    //查今天委托次数    
    $checkuser2 = $user_arr;
    //note 委托也要做性别限制
    
    $user_s = array_merge(MooMembersData($sendid),MooGetData('members_login','uid',$sendid));
    //note 获得照片总数
    $ret_count = $_MooClass['MooMySQL']->getOne("SELECT count(imgid) as c FROM {$dbTablePre}pic WHERE uid = '{$user_s['uid']}'");
    $pic_total = $ret_count['c'];
    if($checkuser2['gender'] == $user_s['gender']) {
        //MooMessage('同性之间不可委托真爱一生','javascript:history.go(-1);','02');
        $error = '同性之间不可委托真爱一生';
        echo return_data($error,false);exit;
    }
    
    //note 检查绑定是否过期
    if($user_s['isbind'] == 1){
        //note 如果被绑定，检测绑定是否到期
        $user_s['isbind'] = check_bind($user_s['bind_id']);
        if($user_s['isbind'] == 1){
           //MooMessage('不能向绑定中的会员发委托','javascript:history.go(-1);','02');
            $error = '不能向绑定中的会员发委托';
        	echo return_data($error,false);exit;
        }
    }
    if($user_s['showinformation'] != 1){
        //MooMessage('发起委托失败，该会员已经关闭资料！','javascript:history.go(-1);','02');
        $error = '发起委托失败，该会员已经关闭资料！';
        echo return_data($error,false);exit;
        }
    
    //note 如果已经联系他就直接提示
    
    if($checkuser['mid']) {
        //MooMessage("对不起，您已经委托真爱一生联系TA了",'javascript:history.go(-1);','02');
        $error = '对不起，您已经委托真爱一生联系TA了';
        echo return_data($error,false);exit;
    }
    //note 如果是今天超过3次就直接提示
    $mtime = date("Ymd");
    $contact_time_Ymd=date("Ymd",$checkuser2['contact_time']);
    if($contact_time_Ymd==$mtime && $checkuser2['contact_num'] >= 3) {
        //MooMessage("今天委托真爱一生已经超过3次",'javascript:history.go(-1);','02');
        $error = '今天委托真爱一生已经超过3次';
        echo return_data($error,false);exit;
    }
    
    //提交时
    if($content && $sendid) {
        if(!$checkuser['mid']) {
             //发送短信和邮件
               include_once("./module/crontab/crontab_config.php");
               $res = MooMembersData($sendid);
                if(MOOPHP_ALLOW_FASTDB){
                    $send_user_info = MooMembersData($userid);
                    $send_user_info = array_merge($send_user_info,MooFastdbGet('members_choice','uid',$userid));
                }else{
                   $send_user_info = $_MooClass['MooMySQL']->getAll("select b.*,a.* from `{$dbTablePre}members_search` a  left join  {$dbTablePre}members_choice b  on a.uid=b.uid  where a.uid = '$userid'");
                   $send_user_info = $send_user_info['0'];
                }
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
               $province = $provice_list[$send_user_info['province']];//省
               $city = $city_list[$send_user_info['city']]; //市
               $height = $send_user_info['height'] ? $height_list[$send_user_info['height']] : "未知"; //身高
               ob_start();
               //require_once MooTemplate('public/mail_space_commissiontpl', 'module'); //模板
               $body = ob_get_clean();             
               MooSendMail($res['username'],"真爱一生网系统温馨提示",$body,"",false,$sendid);
              
               /*发送短信提醒   begin
                $week_time = 24*3600*7;//一周时间秒数
                $interval_time = $timestamp - $user_arr['last_login_time'];//当前时间-最后登录时间
                $date1 = date("Y-m-d",strtotime("last Monday"));
                $date2 = date("Y-m-d",strtotime("Sunday"));
                
                if($interval_time > $week_time){//不活跃用户每周发一条短信
                     $_MooClass['MooMySQL']->query("INSERT INTO {$dbTablePre}today_send SET uid = '".$sendid."', sid = '".$userid."',phone = '".$res['telphone']."',sendtime = '".date("Y-m-d")."'" );
                     $cos = $_MooClass['MooMySQL']->getOne("select count(*) as c from {$dbTablePre}today_send where uid='$sendtoid'  and sendtime>='$date1' and sendtime<='$date2'");
                    if($cos['c'] <= 1){
                        //fanglin暂时屏蔽
                        Push_message_intab($sendid,$res['telphone'],"委托","真爱一生网 用户ID：".$send_user_info['uid'].",".$send_user_grade.",已委托真爱一生联系您,请及时把握您的缘分！4006780405",$send_user_info['uid']);
                    }
                }else{
                     //每天该用户超过5条信息不发送短信
                   $cos = $_MooClass['MooMySQL']->getOne("select count(*) as c from {$dbTablePre}today_send where uid='$sendid' and sendtime='".date("Y-m-d")."'");
                   if($cos['c']<5){
                      $_MooClass['MooMySQL']->query("INSERT INTO {$dbTablePre}today_send SET uid = '".$sendid."', sid = '".$userid."',phone = '".$res['telphone']."',sendtime = '".date("Y-m-d")."'" );
                      //fanglin暂时屏蔽
                      Push_message_intab($sendid,$res['telphone'],"委托","真爱一生网 用户ID：".$send_user_info['uid'].",".$send_user_grade.",已委托真爱一生联系您,请及时把握您的缘分！4006780405",$send_user_info['uid']);
                   }
                }
				end */
               
            //note 今天提交委托真爱一生，表中的委托计数还是昨天的就 update为0
            if($contact_time_Ymd != $mtime){
                $_MooClass['MooMySQL']->query("UPDATE {$dbTablePre}members_base SET contact_num = 0,contact_time = '$timestamp' WHERE uid = '$userid'");
                if(MOOPHP_ALLOW_FASTDB){
                    $value = array();
                    $value['contact_num'] = 0;
                    $value['contact_time'] = $timestamp;
                    MooFastdbUpdate('members_base','uid',$userid,$value);
                }
            }
            //note 如果今天提交委托真爱一生次数超过3次不能再发起委托，否则更新次数
            $today_contact_num = $_MooClass['MooMySQL']->getOne("SELECT contact_num FROM {$dbTablePre}members_base WHERE uid = '$userid'",true);
            //$today_contact_num = $checkuser2;
            $today_contact_num = $today_contact_num['contact_num'];//今天委托的次数
            //if($user_arr['uid']=='20796965') $today_contact_num=0;
           // if($today_contact_num < 3) {
               //优质会员列表
               $update_sql = '';
                if(empty($user_arr['sid']) && $user_arr['usertype'] == 1){
                    $update_sql = ',is_well_user=1';
                }
                $_MooClass['MooMySQL']->query("UPDATE {$dbTablePre}members_base SET contact_num = contact_num + 1,contact_time = '$timestamp' $update_sql  WHERE uid = '$userid'");
                if(MOOPHP_ALLOW_FASTDB){
                    $oldarr = MooFastdbGet('members_base','uid',$userid);
                    $value = array();
                    $value['contact_num'] = $oldarr['contact_num']+1;
                    $value['contact_time'] = $timestamp;
                    if($update_sql!='') $value['is_well_user'] = 1;
                    MooFastdbUpdate('members_base','uid',$userid,$value);
                }
                //note 记录委托真爱一生的内容       
                $_MooClass['MooMySQL']->query("INSERT INTO {$dbTablePre}service_contact SET content = '$content',other_contact_you = '$userid',you_contact_other='$sendid',stat = '$contact_stat',syscheck='$contact_sys_check',sendtime = '$timestamp'");

                //将新注册的会员更新为优质会员
                if(in_array($user_arr['sid'],array(0,52,123))&&$user_arr['is_well_user']!=1){       
                    update_iswell_user($user_arr['uid']);
                }
				
                //if($user_arr['uid']=='20796965'){
	                //====发送委托彩信   begin  ====
	                
                   //发送人有照片
	                $sendout_user_info = MooMembersData($userid);
	                if (!($sendout_user_info['mainimg'] !='' && $sendout_user_info['images_ischeck'] ==1 && $sendout_user_info['pic_num'] > 0))
	                      $sendout_user_info['uid'] = false;
	                 
	                
	                //发送条件：本站注册会员，有电话号码的，开启短信通知的会员发送
	                //echo $sendid."<br />".$userid;
	                $sendto_user_info = MooMembersData($sendid);
	                if (!($sendto_user_info['is_phone']==1 && $sendto_user_info['telphone']!='' && $sendto_user_info['usertype']==1)){
	                	$sendto_user_info['telphone'] = false;
	                }
					//print_r($sendout_user_info);
					//exit;$sendout_user_info['telphone']
	                if($sendto_user_info['telphone']&&$sendout_user_info['uid']){
	                	//SendMsg('18911883821',"真爱一生网 用户ID：".$user_arr['uid'].",".$gender.",已给委托真爱一生 委托您,请及时把握您的缘分！4006780405");
	                    send_mms_commission($sendto_user_info['telphone'],'contact',$sendout_user_info['uid']);
	                }
	                
	                //====发送委托彩信 end =====
                //}
                
	                
				//*********提醒所属客服**********
		        $sid = $user_arr['sid'];
		        $title = '您的会员 '.$user_arr['uid'].' 向 '.$sendid.' 发送了委托';
		        $awoketime = $timestamp+3600;
		        $sql_remark = "insert into {$dbTablePre}admin_remark set sid='{$sid}',title='{$title}',content='{$title}',awoketime='{$awoketime}',dateline='{$timestamp}'";
		        $res = $_MooClass['MooMySQL']->query($sql_remark);
		        /*普通会员对全权会员反馈白名单*/
		        if($user_s['usertype'] == '3' && $user_arr['usertype'] != '3' && !$serverid){
		        	white_list($user_s['uid'],$user_arr['uid']);
		        }
		        /*客服模拟全权记录*/
		        if($user_arr['usertype'] == '3' && $serverid && $user_s['usertype']!=3){
		        	$action = '站内信';
		        	fulllog($user_arr['uid'],$serverid,$action,$user_s);
		        }
        
                //MooMessage("委托成功",'index.php?n=service','05');
                $error = '委托成功';
        		echo return_data($error,true);exit;
            //} else {
                //MooMessage("今天委托真爱一生已经 超过3次",'javascript:history.go(-1);','02');
           //     $error = '今天委托真爱一生已经 超过3次';
        	//	echo return_data($error,false);exit;
           // }
            
      }else{
                //MooMessage("对不起，您已经委托真爱一生联系TA了",'javascript:history.go(-1);','02');
                $error = '对不起，您已经委托真爱一生联系TA了';
        		echo return_data($error,false);exit;
      }
  }
    //是否有手机号码
    if(MOOPHP_ALLOW_FASTDB){
        $status = MooFastdbGet('certification','uid',$userid);
    }else{
        $status = $_MooClass['MooMySQL']->getOne("SELECT telphone FROM {$dbTablePre}certification WHERE uid='$userid'",true);
    }
    if(!$status['telphone']){
           //MooMessage("对不起，您没有通过手机验证，请先通过验证再委托",'index.php?n=myaccount&h=telphone','02');
           $error = '对不起，您没有通过手机验证';
        	echo return_data($error,false);exit;
    }else{$tel=$status['telphone'];}
    
    
    //require MooTemplate('public/service_contact_sendcontact', 'module');
}


/**************************************************控制层*******************************/

$c = $_GET['c'] = empty($_GET['c'])? '': $_GET['c'];

switch ($c){
	case 'getmycontact' :
		getmycontact();
		break;
		
	case 'getothercontact' :
		getothercontact();
		break;
	case 'oneajax':
		oneajax();
		break;
		
		//秋波接受拒绝接口
	case 'leerajax':
		leerajax();
		break;
		
	case 'sendcontact':
		sendcontact();
		break;
		
	default:
		getmycontact();
		break;	
	

}
<?php
/*
 * Created on 8/14/2009
 *
 * Tianyong
 *
 * module/service/commission.php
 */
//note 删除我的委托
function delmycontact () {
    global $_MooClass,$dbTablePre,$userid,$user_arr;
    $mid = MooGetGPC('mid','integer');
    $dif_type=MooGetGPC('dif_type','integer');
    if($mid) {
        //$_MooClass['MooMySQL']->query("DELETE FROM {$dbTablePre}service_contact WHERE mid = '$mid'");
        if($dif_type){
            $_MooClass['MooMySQL']->query("UPDATE {$dbTablePre}service_contact SET receive_del=1 WHERE mid = '$mid'");
            MooMessage("委托删除成功",'index.php?n=service&h=commission','05');
        }
        $_MooClass['MooMySQL']->query("UPDATE {$dbTablePre}service_contact SET send_del=1 WHERE mid = '$mid' and other_contact_you='$userid'");
        MooMessage("委托删除成功",'index.php?n=service&h=commission&t=getmycontact','05');
    }
}

//note 测试ajax
function oneajax() {
    global $_MooClass,$dbTablePre,$userid,$timestamp,$user_arr;
    
    $text1 =  MooCutstr(rtrim(MooGetGPC('text1','string','G')),180);
    $text2 =  MooCutstr(rtrim(MooGetGPC('text2','string','G')),180);
    $acceptid = MooGetGPC('acceptid','integer','G');
    $stat = MooGetGPC('stat','integer','G');
    
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

}

//note 发出联系真爱一生
function sendcontact() {
    global $_MooClass,$dbTablePre,$timestamp,$user,$user_arr,$hzn,$serverid;   
    $returnurl = 'index.php?'.$_SERVER['QUERY_STRING'];//返回的url
    $userid=$GLOBALS['MooUid']; 
    //note 目前默认系统不审核
    $contact_sys_check= '0';
    //note 目前系统默认是委托真爱一生等待对方的回应
    $contact_stat = '1';
    $content =  MooCutstr(safeFilter(MooGetGPC('content1','string')),200,'');
    $sendid = MooGetGPC('sendid','integer');
    
    //客服不可模拟操作
    /*if($hzn == "hongniangwang"){
        MooMessage('对不起您不能模拟操作','javascript:history.go(-1);','04');
        exit;
    }*/
    
    if($serverid){
        MooMessage('对不起您不能模拟操作','javascript:history.go(-1);','04');
        exit;
    }
    
    
    //自己不可委托真爱一生联系自己
    if($sendid == $userid){
        MooMessage('自己不可委托真爱一生联系自己','javascript:history.go(-1);','02');
        exit;
    }
    //note 双方屏蔽不给操作
    if(MooGetScreen($userid,$sendid)){
        MooMessage('因特殊原因，委托失败',"index.php?n=service&h=rose&t=getmorerose",'03');
        exit;
    }
    $formsubmit1 = MooGetGPC('formsubmit1','string');
    
    //委托联系人表
    $checkuser = $_MooClass['MooMySQL']->getOne("SELECT mid,sendtime FROM {$dbTablePre}service_contact WHERE you_contact_other='$sendid' AND other_contact_you = '$userid' and receive_del=0 and send_del=0 and is_server=0");
    //查今天委托次数    
    $checkuser2 = $user_arr;
    //note 委托也要做性别限制
    
    if(MOOPHP_ALLOW_FASTDB){
        $user_s = MooFastdbGet('members_search','uid',$sendid);
    }else{
        $user_s =  $_MooClass['MooMySQL']->getOne("SELECT * FROM {$dbTablePre}members_search WHERE uid = '$sendid'",true);
    }
    //note 获得照片总数
    $ret_count = $_MooClass['MooMySQL']->getOne("SELECT count(imgid) as c FROM {$dbTablePre}pic WHERE uid = '{$user_s['uid']}'");
    $pic_total = $ret_count['c'];
    if($checkuser2['gender'] == $user_s['gender']) {
        MooMessage('同性之间不可委托真爱一生','javascript:history.go(-1);','02');
        exit;
    }
    
    //note 检查绑定是否过期
    if($user_s['isbind'] == 1){
        //note 如果被绑定，检测绑定是否到期
        $user_s['isbind'] = check_bind($user_s['bind_id']);
        if($user_s['isbind'] == 1){
            MooMessage('不能向绑定中的会员发委托','javascript:history.go(-1);','02');
            exit;   
        }
    }
    if($user_s['showinformation'] != 1){
        MooMessage('发起委托失败，该会员已经关闭资料！','javascript:history.go(-1);','02');
        exit;
        }
    
    //note 如果已经联系他就直接提示
    
    if($checkuser['mid']) {
        MooMessage("对不起，您已经委托真爱一生联系TA了",'javascript:history.go(-1);','02');
    }
    //note 如果是今天超过3次就直接提示
    $mtime = date("Ymd");
    $contact_time_Ymd=date("Ymd",$checkuser2['contact_time']);
    if($contact_time_Ymd==$mtime && $checkuser2['contact_num'] >= 3) {
        MooMessage("今天委托真爱一生已经超过3次",'javascript:history.go(-1);','02');
    }
    
    //提交时
    if($formsubmit1 && $content && $sendid) {
        if(!$checkuser['mid']) {
             //发送短信和邮件
               include_once("./module/crontab/crontab_config.php");
                if(MOOPHP_ALLOW_FASTDB){
                    $res = MooFastdbGet('members_search','uid',$sendid);
                }else{
                    $res= $_MooClass['MooMySQL']->getOne("select telphone,is_phone,username from {$dbTablePre}members_search where uid='$sendid'");
                }
                if(MOOPHP_ALLOW_FASTDB){
                    $send_user_info = MooFastdbGet('members_search','uid',$userid);
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
               require_once MooTemplate('public/mail_space_commissiontpl', 'module'); //模板
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
            if($today_contact_num < 3) {
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
                if(in_array($user_arr['sid'],array(1,52,123))&&$user_arr['is_well_user']!=1){       
                    update_iswell_user($user_arr['uid']);
                }
				
                //if($user_arr['uid']=='20796965'){
	                //====发送委托彩信   begin  ====
	                
	                $sql="SELECT uid,telphone FROM {$dbTablePre}members_search where uid='{$userid}' and mainimg!='' and images_ischeck=1 and pic_num>0";
	                $sendout_user_info=$_MooClass['MooMySQL']->getOne($sql);
	                //发送人有照片
	                //echo $sendid."<br />".$userid;
	                $sql="SELECT s.telphone FROM {$dbTablePre}members_search as s
	                       left join {$dbTablePre}members_base as b on s.uid=b.uid
	                       where s.uid='{$sendid}' and b.is_phone=1 and s.telphone!='' and s.usertype=1";
	                $sendto_user_info=$_MooClass['MooMySQL']->getOne($sql,true);
	                //发送条件：本站注册会员，有电话号码的，开启短信通知的会员发送
					//print_r($sendout_user_info);
					//exit;$sendout_user_info['telphone']
	                if($sendto_user_info['telphone']&&$sendout_user_info['uid']){
	                	//SendMsg('18911883821',"真爱一生网 用户ID：".$user_arr['uid'].",".$gender.",已给委托真爱一生 委托您,请及时把握您的缘分！4006780405");
	                    send_mms_commission($sendto_user_info['telphone'],'contact',$sendout_user_info['uid']);
	                }
	                
	                //====发送委托彩信 end =====
                //}
				
                MooMessage("委托成功",'index.php?n=service','05');
            } else {
                MooMessage("今天委托真爱一生已经 超过3次",'javascript:history.go(-1);','02');
            }
            
      }else{
                MooMessage("对不起，您已经委托真爱一生联系TA了",'javascript:history.go(-1);','02');
      }
  }
    //是否有手机号码
    if(MOOPHP_ALLOW_FASTDB){
        $status = MooFastdbGet('certification','uid',$userid);
    }else{
        $status = $_MooClass['MooMySQL']->getOne("SELECT telphone FROM {$dbTablePre}certification WHERE uid='$userid'",true);
    }
    if(!$status['telphone']){
           MooMessage("对不起，您没有通过手机验证，请先通过验证再委托",'index.php?n=myaccount&h=telphone','02');
    }else{$tel=$status['telphone'];}
    
    
    require MooTemplate('public/service_contact_sendcontact', 'module');
}

//绑定申请
function service_bind(){
    global $_MooClass,$dbTablePre,$userid,$user_arr,$user_pic,$serverid;
    $bindMsg = '';
    $time = time();
    $b_uid = MooGetGPC('buid','integer','P');
    $sendid = MooGetGPC('sendid','integer','G');
    if($sendid == 0){
        $sendid = MooGetGPC('sendtoid','integer','G');
    }
    $sendid = $sendid ? $sendid : $b_uid;
    if(MOOPHP_ALLOW_FASTDB){
        $user_s = MooFastdbGet('members_search','uid',$sendid);
        $user_b = MooFastdbGet('members_base','uid',$sendid);
        $user_s = array_merge($user_s,$user_b);
    }else{
        $user_s =  $_MooClass['MooMySQL']->getOne("SELECT * FROM {$dbTablePre}members_search,{$dbTablePre}members_base WHERE uid = '$sendid' and {$dbTablePre}members_search.uid={$dbTablePre}members_base.uid");
    }
    
    /*if($hzn == "hongniangwang"){
        MooMessage('对不起您不能模拟操作','javascript:history.go(-1);','04');
        exit;
    }*/
    
   
    $result=$_MooClass['MooMySQL']->getOne("select groupid from web_admin_user where uid='{$serverid}'");
    $groupid=$result['groupid'];
    //系统管理员权限
    $GLOBALS['system_admin'] = array(60);
    if(in_array($groupid,$GLOBALS['system_admin'])){
        $serverid=null;
    }
    
    if($serverid){
        MooMessage('对不起您不能模拟操作','javascript:history.go(-1);','04');
        exit;
    }
    
    
    if($user_s['gender'] == $user_arr['gender']){
        MooMessage("同性之间不能绑定。",'javascript:history.go(-1);','02');
    }
    if($user_arr['bind_id'] > 0){
        if($user_arr['isbind'] != 0){
            $bindMsg = '您已经在绑定状态了。';
        }else{
            $bindMsg = '您的绑定正在申请中。';
        }
    }else{
        $tmp_bind = $_MooClass['MooMySQL']->getOne("SELECT start_time,length_time FROM {$dbTablePre}members_bind WHERE (a_uid={$user_arr['uid']} OR b_uid={$user_arr['uid']}) AND bind=2 ORDER BY id DESC LIMIT 1",true);
        //echo $sql;
        if($time - $tmp_bind['start_time'] + $tmp_bind['length_time'] < 86400){
            $bindMsg = "绑定解除后的24小时内不能再次申请绑定。";
        }
    }
    if($bindMsg == ''){
    if($b_uid > 0 && $user_s['isbind'] == 0){
        
        $apply_con =safeFilter(MooGetGPC('content1','string','P'));
        $ltime   = MooGetGPC('ltime','integer','P');
        $sql = "INSERT INTO {$dbTablePre}members_bind SET a_uid={$user_arr['uid']},b_uid={$b_uid},apply_con='{$apply_con}',dateline='".$time."',apply_ltime=".$ltime;
        
        $query = $_MooClass['MooMySQL']->query($sql);
        $bind_id = $_MooClass['MooMySQL']->insertId();
        @ $_MooClass['MooMySQL']->query("UPDATE {$dbTablePre}members_base SET bind_id=".$bind_id." WHERE uid=".$user_arr['uid']);//预先更新申请人的bind_id,
        if (MOOPHP_ALLOW_FASTDB){
            $value = array();
            $value['bind_id'] = $bind_id;
            MooFastdbUpdate("members_base",'uid',$user_arr['uid'],$value);
        }
        
        if($user_s['is_phone']){
	        $gender=$user_arr['gender']?'女':'男';
	        //发送短信
	        SendMsg($user_s['telphone'],"真爱一生网 用户ID：".$user_arr['uid'].",".$gender.",已给委托真爱一生绑定您,请及时把握您的缘分！4006780405");
		    //Push_message_intab($sendid,$user_s['telphone'],"绑定","真爱一生网 用户ID：".$user_arr['uid'].",".$gender.",已给委托真爱一生绑定您,请及时把握您的缘分！4006780405",$user_arr['uid']);
	  
        }
        
        MooMessage("申请成功！请等待真爱一生的联系与确定。",'javascript:history.go(-2);','05');
    }elseif($user_s['isbind'] > 0 || $user_s['bind_id'] > 0 ){
        $statue = 1;
        //得到与TA绑定的user信息
        $sql = "SELECT a_uid,b_uid,length_time FROM {$dbTablePre}members_bind WHERE id=".$user_s['bind_id'];
        $m_bind = $_MooClass['MooMySQL']->getOne($sql);
        if($user_s['bind_id'] > 0 && $m_bind['isbind'] == 0){
            //MooMessage("会员{$user_s['uid']}正在申请与其他会员绑定中...",'javascript:history.go(-2);');
        }
        $f_uid  = $m_bind['a_uid'] == $sendid ? $m_bind['b_uid'] : $m_bind['a_uid'];
        //echo $f_uid,'|',$m_bind['a_uid'],'|',$m_bind['b_uid'];
        if(MOOPHP_ALLOW_FASTDB){
            $user_bind = MooFastdbGet('members_base','uid',$f_uid);
        }else{
            $user_bind =  $_MooClass['MooMySQL']->getOne("SELECT * FROM {$dbTablePre}members_base WHERE uid = '$f_uid'");
        }
        require MooTemplate('public/service_contact_binding', 'module');exit;
    }
    }
    require MooTemplate('public/service_contact_bind', 'module');
}
function service_mebind(){
    global $_MooClass,$dbTablePre,$userid,$user_arr;
    $bind_id = MooGetGPC('bind_id','integer','G');
    $statue = 1;
    if($bind_id == 0){
        //MooMessage('赶快去找到您心仪的TA去绑定吧！');
        $statue = 0;
    }else{
        $sql = "SELECT a_uid,b_uid,length_time FROM {$dbTablePre}members_bind WHERE id=".$bind_id;
        $m_bind = $_MooClass['MooMySQL']->getOne($sql,true);
        
        $f_uid  = $m_bind['a_uid'] == $user_arr['uid'] ? $m_bind['b_uid'] : $m_bind['a_uid'];
        if(MOOPHP_ALLOW_FASTDB){
            $user_bind = MooFastdbGet('members_base','uid',$f_uid);
        }else{
            $user_bind =  $_MooClass['MooMySQL']->getOne("SELECT * FROM {$dbTablePre}members_base WHERE uid = '$f_uid'");
        }
       
    }
    //print_r($user_bind);
    $user_s = $user_arr;
    require MooTemplate('public/service_contact_binding', 'module');
}
//note 查看别人联系我的详细资料页面
function getcontactdata1() {
    global $_MooClass,$dbTablePre,$userid,$user_arr;
    $uid = MooGetGPC('uid','integer');
    
    //note 查询目前委托真爱一生联系对方表
    $user4 = $_MooClass['MooMySQL']->getOne("SELECT * FROM `{$dbTablePre}service_contact` WHERE `other_contact_you` = '$uid' AND `you_contact_other` = '$userid' and receive_del=0",true);
    if(!$user4){
        MooMessage('对不起，您查看的不属于您的委托','javascript:history.go(-1)','04');
    }
    
    //note 查询用户主表
    $user1 = leer_send_user1($uid);
    
    //note 查询用户择偶表
    //$user2 = leer_send_user2($uid);
    
    //note 查询用户附加表
    $user3 = service_user3($uid);
    
    //note 择偶条件

    if(MOOPHP_ALLOW_FASTDB){
        $c = MooFastdbGet('members_choice','uid',$uid);
    }else{
        $c = $_MooClass['MooMySQL']->getOne("SELECT * FROM `{$dbTablePre}members_choice` WHERE uid = $uid");
    }
    //note 显示相册中的普通照片
    $user_pic = $_MooClass['MooMySQL']->getAll("SELECT * FROM {$dbTablePre}pic WHERE  syscheck=1 and uid='{$user1['uid']}'");
    //会员已认证证件
    $MSG = certification($user1['uid']);
    
    
    //note 实现查看下一个会员功能
    $user5 = $_MooClass['MooMySQL']->getAll("SELECT other_contact_you FROM `{$dbTablePre}service_contact` WHERE `you_contact_other` = '$userid' and receive_del=0");
    $total = count($user5);
    foreach($user5 as $k => $v) {
        if($user5[$k]['other_contact_you'] == $uid) {
            $up = ($k - 1) <= 0 ? '0' : ($k - 1);
            $next = ($k + 1) >= $total ? ($total- 1) : ($k+1);
            $upid = $user5[$up]['other_contact_you'];
            $nextid = $user5[$next]['other_contact_you'];
        }
    }
    
        
    require MooTemplate('public/service_contact_getcontactdata1', 'module');
}


//note 查看我联系别人的详细资料页面
function getcontactdata2() {
    global $_MooClass,$dbTablePre,$userid,$user_arr;
    $uid = MooGetGPC('uid','integer');
    
    //note 查询目前委托真爱一生联系对方表
    $user4 = $_MooClass['MooMySQL']->getOne("SELECT * FROM `{$dbTablePre}service_contact` WHERE `other_contact_you` = '$userid' AND `you_contact_other` = '$uid' and send_del=0 and is_server=0",true);
    if(!$user4){
        MooMessage('对不起，您查看的不属于您的委托','javascript:history.go(-1)','04');
    }
    
    //note 查询用户主表
    $user1 = leer_send_user1($uid);
    
    //note 查询用户择偶表
    //$user2 = leer_send_user2($uid);
    
    //note 查询用户附加表
    $user3 = service_user3($uid);
    
    //note 择偶条件

    if(MOOPHP_ALLOW_FASTDB){
        $c = MooFastdbGet('members_choice','uid',$uid);
    }else{
        $c = $_MooClass['MooMySQL']->getOne("SELECT * FROM `{$dbTablePre}members_choice` WHERE uid = $uid");
    }
    //note 实现查看下一个会员功能
    $user5 = $_MooClass['MooMySQL']->getAll("SELECT you_contact_other FROM `{$dbTablePre}service_contact` WHERE `other_contact_you` = '$userid' and send_del=0 and is_server=0");
    
    //note 显示相册中的普通照片
    $user_pic = $_MooClass['MooMySQL']->getAll("SELECT * FROM {$dbTablePre}pic WHERE syscheck=1 and  uid='{$user1['uid']}'");
    //会员已认证证件
    $MSG = certification($user1['uid']);
    
    $total = count($user5);
    foreach($user5 as $k => $v) {
        if($user5[$k]['you_contact_other'] == $uid) {
            $up = ($k - 1) <= 0 ? '0' : ($k - 1);
            $next = ($k + 1) >= $total ? ($total- 1) : ($k+1);
            $upid = $user5[$up]['you_contact_other'];
            $nextid = $user5[$next]['you_contact_other'];
        }
    }
    //note 查询普通图片
    //$user6 = $_MooClass['MooMySQL']->getAll("SELECT * FROM {$dbTablePre}pic WHERE uid='$uid' AND syscheck = 1");
    
    
    require MooTemplate('public/service_contact_getcontactdata2', 'module');
}

//note 我委托真爱一生联系他们
function getmycontact() {
    global $_MooClass,$dbTablePre,$userid,$pagesize,$user_arr;
    $pagesize = 4;
    //note 获得当前url
    $currenturl = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"]; 
    $currenturl2 = preg_replace("/(&page=\d+)/","",$currenturl);
    $currenturl2 = preg_replace("/(&page2=\d+)/","",$currenturl2);
    $currenturl2 = preg_replace("/(&page3=\d+)/","",$currenturl2);

    //note ---------------------等待回应的请求
    //note 获得第几页
    //$page = empty($_GET['page']) ?  '1' : $_GET['page'];
    $page = max(1,intval($_GET['page']));
    //note limit查询开始位置
    $start = ($page - 1) * $pagesize;
    //note 查询等待回应的请求统计总数
    //$ret_count = $_MooClass['MooMySQL']->getOne("SELECT count(*) as c FROM {$dbTablePre}service_contact WHERE other_contact_you = '$userid'  AND stat = '1' and send_del=0 and is_server=0");
    //$total = $ret_count['c'];
    
    $ret = $_MooClass['MooMySQL']->getAll("SELECT stat,count(*) as c FROM {$dbTablePre}service_contact WHERE other_contact_you = '$userid'  and send_del=0  group by stat");
    
    $total = 0;
    $total2 = 0;
    $total3 = 0;
    $total4 = 0;
    foreach($ret as $v){
        switch($v['stat']){
            case 1: $total = $v['c'];break;
            case 2: $total2 = $v['c'];break;
            case 3: $total3 = $v['c'];break;
            case 4: $total4 = $v['c'];break;
        }
    }
    //note 查询等待回应的请求
    if($total){
        $contact = $_MooClass['MooMySQL']->getAll("SELECT * FROM {$dbTablePre}service_contact WHERE other_contact_you = '$userid' AND stat = '1' and send_del=0 and is_server=0 order by sendtime desc LIMIT $start,$pagesize");
    }
    
    
    //note ---------------------已接受的请求
    //note 获得第几页
    //$page2 = empty($_GET['page2']) ?  '1' : $_GET['page2'];
    $page2 = max(1,intval($_GET['page2']));
    //note limit查询开始位置
    $start2 = ($page2 - 1) * $pagesize;
    //note 查询已接受的请求统计总数
    //$ret_count2 = $_MooClass['MooMySQL']->getOne("SELECT count(*) as c FROM {$dbTablePre}service_contact WHERE other_contact_you = '$userid' AND stat = '2' and send_del=0 and is_server=0");
    //$total2 = $ret_count2['c'];
    
    
    //note 查询已接受的请求
    if($total2){
        $contact2 = $_MooClass['MooMySQL']->getAll("SELECT * FROM {$dbTablePre}service_contact WHERE other_contact_you = '$userid' AND stat = '2' and send_del=0 and is_server=0 order by sendtime desc LIMIT $start2,$pagesize");
    }
    //note --------------------考虑中的请求
    //note 获得第几页
    //$page3 = empty($_GET['page3']) ?  '1' : $_GET['page3'];
    $page3 = max(1,intval($_GET['page3']));
    //note limit查询开始位置
    $start3 = ($page3 - 1) * $pagesize;
    //note 查询已接受的请求统计总数
    //note 考虑中的请求的统计总数
    //$ret_count3 = $_MooClass['MooMySQL']->getOne("SELECT count(*) as c FROM {$dbTablePre}service_contact WHERE other_contact_you = '$userid' AND stat = '3' and send_del=0 and is_server=0");
    //$total3 = $ret_count3['c'];
    
    
    //note 考虑中的请求
    if($total3){
        $contact3 = $_MooClass['MooMySQL']->getAll("SELECT * FROM {$dbTablePre}service_contact WHERE other_contact_you = '$userid'  AND stat = '3' and send_del=0 and is_server=0 order by sendtime desc LIMIT $start3,$pagesize");
    }
    //note --------------------对方不愿接受
    //note 获得第几页
    $page4 = max(1,intval($_GET['page4']));
    //note limit查询开始位置
    $start4 = ($page4 - 1) * $pagesize;
    //note 请求统计总数
    //note 请求的统计总数
    //$ret_count4 = $_MooClass['MooMySQL']->getOne("SELECT count(*) as c FROM {$dbTablePre}service_contact WHERE other_contact_you = '$userid' AND stat = '4' and send_del=0 and is_server=0");
    //$total4 = $ret_count4['c'];
    //note 请求
    if($total4){
        $contact4 = $_MooClass['MooMySQL']->getAll("SELECT * FROM {$dbTablePre}service_contact WHERE other_contact_you = '$userid'  AND stat = '4' and send_del=0 and is_server=0 order by sendtime desc  LIMIT $start4,$pagesize");
    }
    require MooTemplate('public/service_contact_getmycontact', 'module');
    
}

//note 他们委托真爱一生联系我
function getcontactme() {
    global $_MooClass,$dbTablePre,$userid,$pagesize,$user_arr;
    $pagesize = 4;
    //note 获得当前url
    $currenturl = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"]; 
    $currenturl2 = preg_replace("/(&page=\d+)/","",$currenturl);
    $currenturl2 = preg_replace("/(&page2=\d+)/","",$currenturl2);
    $currenturl2 = preg_replace("/(&page3=\d+)/","",$currenturl2);

    //note ---------------------等待回应的请求
    //note 获得第几页
    $page = max(1,intval($_GET['page']));
    //note limit查询开始位置
    $start = ($page - 1) * $pagesize;
    //note 查询等待回应的请求统计总数

    //$ret_count = $_MooClass['MooMySQL']->getOne("SELECT count(*) as c FROM {$dbTablePre}service_contact WHERE you_contact_other = '$userid'  AND stat = 1 and receive_del=0");
    //$total = $ret_count['c'];
    
    
    $ret = $_MooClass['MooMySQL']->getAll("SELECT stat,count(*) as c FROM {$dbTablePre}service_contact WHERE you_contact_other = '$userid'  and receive_del=0 and send_del=0 group by stat");
    $total = 0;
    $total2 = 0;
    $total3 = 0;
    foreach($ret as $v){
        switch($v['stat']){
            case 1: $total = $v['c'];break;
            case 2: $total2 = $v['c'];break;
            case 3: $total3 = $v['c'];break;
        }
    }
    
    //note 查询等待回应的请求
    if($total){
        $contact = $_MooClass['MooMySQL']->getAll("SELECT * FROM {$dbTablePre}service_contact WHERE you_contact_other = '$userid' AND stat = 1 and receive_del=0 and send_del=0  order by sendtime desc LIMIT $start,$pagesize");
    }
    
    
    //note ---------------------已接受的请求
    //note 获得第几页
    $page2 = max(1,intval($_GET['page2']));
    //note limit查询开始位置
    $start2 = ($page2 - 1) * $pagesize;
    //note 查询已接受的请求统计总数

    //$ret_count2 = $_MooClass['MooMySQL']->getOne("SELECT count(*) as c FROM {$dbTablePre}service_contact WHERE you_contact_other = '$userid' AND stat = 2 and receive_del=0");
    //$total2 = $ret_count2['c'];
    //note 查询已接受的请求

    if($total2){
        $contact2 = $_MooClass['MooMySQL']->getAll("SELECT * FROM {$dbTablePre}service_contact WHERE you_contact_other = '$userid'  AND stat = 2 and receive_del=0 and send_del=0 order by sendtime desc  LIMIT $start2,$pagesize");
    }
    //note --------------------考虑中的请求
    //note 获得第几页
    $page3  = max(1,intval($_GET['page3']));
    //note limit查询开始位置
    $start3 = ($page3 - 1) * $pagesize;
    //note 查询已接受的请求统计总数
    //note 考虑中的请求的统计总数

    //$ret_count3 = $_MooClass['MooMySQL']->getOne("SELECT count(*) as c FROM {$dbTablePre}service_contact WHERE you_contact_other = '$userid' AND stat = 3 and receive_del=0");
    //$total3 = $ret_count3['c'];
    //note 考虑中的请求

    if($total3){
        $contact3 = $_MooClass['MooMySQL']->getAll("SELECT * FROM {$dbTablePre}service_contact WHERE you_contact_other = '$userid' AND stat = 3 and receive_del=0 and send_del=0 order by sendtime desc LIMIT $start3,$pagesize");
    }

    require MooTemplate('public/service_contact_getcontactme', 'module');
}
//note 会员的已认证证件
function certification($uid){
    global $_MooClass,$dbTablePre,$userid,$timestamp,$user_arr;
    //note 查询会员诚信认证表
    
    if(MOOPHP_ALLOW_FASTDB){
        $certificationMsg = MooFastdbGet('certification','uid',$uid);
    }else{
        $certificationMsg = $_MooClass['MooMySQL']->getOne("select * from {$dbTablePre}certification where uid='$uid'");
    }
    return $certificationMsg;
}

//note 会员绑定
function binding(){
    global $_MooClass,$dbTablePre,$userid,$timestamp,$user_arr;
    $b_uid = MooGetGPC('buid','integer','G');
    //echo $b_uid;
    if($user_arr['bind_id'] > 0){
        //如果申请过了就不能再次申请
        echo 'havebind';exit();
    }
    //note 检查是否同性
    $sql = "SELECT s.gender,b.bind_id,b.isbind FROM {$dbTablePre}members_search s left join {$dbTablePre}members_base b on s.uid=b.uid  WHERE s.uid = ".$b_uid;
    $b_gender = $_MooClass['MooMySQL']->getOne($sql);
    
    if($b_gender['gender'] == $user_arr['gender']){
        echo 'onesex';exit();   
    }
    if( $b_uid > 0 && $user_arr['isbind'] == 0 ){
        $sql = "INSERT INTO {$dbTablePre}members_bind SET a_uid={$user_arr['uid']},b_uid={$b_uid},dateline=".time();
        $query = $_MooClass['MooMySQL']->query($sql);
        $bind_id = $_MooClass['MooMySQL']->insertId();
        @ $_MooClass['MooMySQL']->query("UPDATE {$dbTablePre}members_base SET bind_id=".$bind_id." WHERE uid=".$user_arr['uid']);//预先更新申请人的bind_id,
        if (MOOPHP_ALLOW_FASTDB){
            $value = array();
            $value['bind_id'] = $bind_id;
            MooFastdbUpdate("members_base",'uid',$user_arr['uid'],$value);
        }
        if($query){
            echo 'binding';//申请成功
        }
    }else{
        echo 'binded';//对方已绑定过
    }
    //require MooTemplate('public/service_members_bind', 'module');
}
/*******************************************************************************/
//控制部分
switch ($_GET['t']) {
    //note 他们委托真爱一生联系您
    case "getcontactme" :
        getcontactme();
        break;
        
    //note 您委托真爱一生联系他们 
    case "getmycontact" :
        getmycontact();
        break;  
        
    //note 删除联系人   
    case "delmycontact" :
        delmycontact();
        break;
        
    //note 查看联系人详细资料页面   
    case "getcontactdata1" :
        getcontactdata1();
        break;

    //note 查看联系人详细资料页面   
    case "getcontactdata2" :
        getcontactdata2();
        break;
            
    //note 委托真爱一生
    case "sendcontact" :
        sendcontact();
        break;  
        
    case "oneajax"  :
        oneajax();
        break;
    
    //note 绑定
    case 'bind' :
        binding();
        break;
    case 'sbind':
        service_bind();
        break;
    case 'mebind':
        service_mebind();
        break;
    //note 默认进入他们委托真爱一生联系您的列表页面 
    default:
        getcontactme();
}
?>

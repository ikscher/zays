<?php
/*
 * Created on 8/14/2009
 *
 * Tianyong
 *
 * module/service/commission.php
 */
//mark 己改  by chuzx
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
/* function oneajax() {
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

} */


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
        $user_bind = MooMembersData($f_uid)+MooGetData('members_login','uid',$f_uid);
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
    $c = MooGetData('members_choice','uid',$uid)+MooGetData('members_introduce','uid',$uid);
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
    $page = empty($_GET['page']) ? 1 : $_GET['page'];
    //note limit查询开始位置
    $start = ($page - 1) * $pagesize;

    
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
        $results1 = $_MooClass['MooMySQL']->getAll("SELECT * FROM {$dbTablePre}service_contact WHERE other_contact_you = '$userid' AND stat = '1' and send_del=0 order by sendtime desc LIMIT $start,$pagesize");

		foreach($results1 as $k=>$v){
			$send_user1=array();
			$send_user2=array();
			$contact=array();

			$send_user1 = leer_send_user1($v['you_contact_other']);
			$send_user2 = leer_send_user2($v['you_contact_other']);
			
			$contact['l']=$v;  
			$contact['s']=$send_user1;
			$contact['t']=$send_user2;

			$contact['t']['introduce']=trim($contact['t']['introduce'])?MooCutstr($contact['t']['introduce'], 148, $dot = ' ...'):'无内心独白内容';
			$contacts[$k]=$contact;
		}
    }

    $page2 = empty($_GET['page2']) ? 1 : $_GET['page2'];
    //note limit查询开始位置
    $start2 = ($page2 - 1) * $pagesize;

    
    //note 查询已接受的请求
    if($total2){
        $results2 = $_MooClass['MooMySQL']->getAll("SELECT * FROM {$dbTablePre}service_contact WHERE other_contact_you = '$userid' AND stat = '2' and send_del=0  order by sendtime desc LIMIT $start2,$pagesize");
        foreach($results2 as $k=>$v){
			$send_user1=array();
			$send_user2=array();
			$contact=array();

			$send_user1 = leer_send_user1($v['you_contact_other']);
			$send_user2 = leer_send_user2($v['you_contact_other']);
			
			$contact['l']=$v;  
			$contact['s']=$send_user1;
			$contact['t']=$send_user2;
			$contact['t']['introduce']=trim($contact['t']['introduce'])?MooCutstr($contact['t']['introduce'], 148, $dot = ' ...'):'无内心独白内容';
			$contacts2[$k]=$contact;
		}
	}
	
	
   
    $page3 = empty($_GET['page3']) ? 1 : $_GET['page3'];
    //note limit查询开始位置
    $start3 = ($page3 - 1) * $pagesize;

    
    //note 考虑中的请求
    if($total3){
        $results3 = $_MooClass['MooMySQL']->getAll("SELECT * FROM {$dbTablePre}service_contact WHERE other_contact_you = '$userid'  AND stat = '3' and send_del=0  order by sendtime desc LIMIT $start3,$pagesize");
        
		foreach($results3 as $k=>$v){
			$send_user1=array();
			$send_user2=array();
			$contact=array();

			$send_user1 = leer_send_user1($v['you_contact_other']);
			$send_user2 = leer_send_user2($v['you_contact_other']);
			
			$contact['l']=$v;  
			$contact['s']=$send_user1;
			$contact['t']=$send_user2;
			$contact['t']['introduce']=trim($contact['t']['introduce'])?MooCutstr($contact['t']['introduce'], 148, $dot = ' ...'):'无内心独白内容';
			$contacts3[$k]=$contact;
		}
    }
	
    //note --------------------对方不愿接受
    $page4 = empty($_GET['page4']) ? 1 : $_GET['page4'];
    //note limit查询开始位置
    $start4 = ($page4 - 1) * $pagesize;

    //note 请求
    if($total4){
        $results4 = $_MooClass['MooMySQL']->getAll("SELECT * FROM {$dbTablePre}service_contact WHERE other_contact_you = '$userid'  AND stat = '4' and send_del=0 order by sendtime desc  LIMIT $start4,$pagesize");
        
		foreach($results4 as $k=>$v){
			$send_user1=array();
			$send_user2=array();
			$contact=array();

			$send_user1 = leer_send_user1($v['you_contact_other']);
			$send_user2 = leer_send_user2($v['you_contact_other']);
			
			$contact['l']=$v;  
			$contact['s']=$send_user1;
			$contact['t']=$send_user2;
			$contact['t']['introduce']=trim($contact['t']['introduce'])?MooCutstr($contact['t']['introduce'], 148, $dot = ' ...'):'无内心独白内容';
			$contacts4[$k]=$contact;
		}
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
    $page = empty($_GET['page']) ? 1 : $_GET['page'];
    //note limit查询开始位置
    $start = ($page - 1) * $pagesize;

    
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
        $results = $_MooClass['MooMySQL']->getAll("SELECT * FROM {$dbTablePre}service_contact WHERE you_contact_other = '$userid' AND stat = 1 and receive_del=0 and send_del=0  order by sendtime desc LIMIT $start,$pagesize");
        foreach($results as $k=>$v){
			$send_user1=array();
			$send_user2=array();
			$contact=array();

			$send_user1 = leer_send_user1($v['other_contact_you']);
			$send_user2 = leer_send_user2($v['other_contact_you']);
			
			$contact['l']=$v;  
			$contact['s']=$send_user1;
			$contact['t']=$send_user2;
			$contact['t']['introduce']=trim($contact['t']['introduce'])?MooCutstr($contact['t']['introduce'], 148, $dot = ' ...'):'无内心独白内容';
			$contacts[$k]=$contact;
		}
	}

    $page2 = empty($_GET['page2']) ? 1 : $_GET['page2'];
    $start2 = ($page2 - 1) * $pagesize;
    if($total2){
        $results2 = $_MooClass['MooMySQL']->getAll("SELECT * FROM {$dbTablePre}service_contact WHERE you_contact_other = '$userid'  AND stat = 2 and receive_del=0 and send_del=0 order by sendtime desc  LIMIT $start2,$pagesize");
        
		foreach($results2 as $k=>$v){
			$send_user1=array();
			$send_user2=array();
			$contact=array();

			$send_user1 = leer_send_user1($v['other_contact_you']);
			$send_user2 = leer_send_user2($v['other_contact_you']);
			
			$contact['l']=$v;  
			$contact['s']=$send_user1;
			$contact['t']=$send_user2;
			$contact['t']['introduce']=trim($contact['t']['introduce'])?MooCutstr($contact['t']['introduce'], 148, $dot = ' ...'):'无内心独白内容';
			$contacts2[$k]=$contact;
		}
	}

    $page3 = empty($_GET['page3']) ? 1 : $_GET['page3'];
    $start3 = ($page3 - 1) * $pagesize;
    if($total3){
        $results3 = $_MooClass['MooMySQL']->getAll("SELECT * FROM {$dbTablePre}service_contact WHERE you_contact_other = '$userid' AND stat = 3 and receive_del=0 and send_del=0 order by sendtime desc LIMIT $start3,$pagesize");
        
		foreach($results3 as $k=>$v){
			$send_user1=array();
			$send_user2=array();
			$contact=array();

			$send_user1 = leer_send_user1($v['other_contact_you']);
			$send_user2 = leer_send_user2($v['other_contact_you']);
			
			$contact['l']=$v;  
			$contact['s']=$send_user1;
			$contact['t']=$send_user2;
			$contact['t']['introduce']=trim($contact['t']['introduce'])?MooCutstr($contact['t']['introduce'], 148, $dot = ' ...'):'无内心独白内容';
			$contacts3[$k]=$contact;
		}
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
        $certificationMsg = $_MooClass['MooMySQL']->getOne("select * from {$dbTablePre}certification where uid='$uid'",true);
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
    $b_gender = MooMembersData($b_uid);
    
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
        
    case "oneajax"  :
        oneajax();
        break;

    case 'mebind':
        service_mebind();
        break;
    //note 默认进入他们委托真爱一生联系您的列表页面 
    default:
        getcontactme();
}
?>

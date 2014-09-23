<?php
header("Content-type: text/html; charset=utf-8");
//ini_set('error_log','./error_log.log');
//error_log('myuser_ajax.php==========');

//note 加载框架配置参数
require './include/config.php';
//note 加载MooPHP框架
require '../framwork/MooPHP.php';
//note 包含共公方法
include './include/function.php';
include './include/ajax_function.php';


function ajax_change_awoke(){
	$uid = MooGetGPC('uid','integer','G');
	if($uid==0){ 
		echo "error";
		exit();
	}
	$user = $GLOBALS['_MooClass']['MooMySQL']->getOne("SELECT uid,is_awoke FROM {$GLOBALS['dbTablePre']}members_base  where uid='$uid'",true);
	if($user['uid']){
		$is_awoke = $user['is_awoke'] ? 0 : 1;
		$GLOBALS['_MooClass']['MooMySQL']->query("UPDATE {$GLOBALS['dbTablePre']}members_base SET `is_awoke`='$is_awoke' where uid='$uid'");
		if(MOOPHP_ALLOW_FASTDB){
			$value['is_awoke']= $is_awoke;
			MooFastdbUpdate('members_base','uid',$uid,$value);	
		}
		echo 'ok';exit;
    }else{
  		echo 'error';
  		exit;
    }	
}

//删除会员
function ajax_delmyuser(){
	//所管理的客服id列表
   $myservice_idlist = get_myservice_idlist(); 
//   error_log($myservice_idlist);
	$uid = MooGetGPC('uid','integer','G');
	$deldesc = trim(MooGetGPC('deldesc','string','G'));
	$deldesc = iconv("GBK","UTF-8",$deldesc);
	
	if(empty($uid) || empty($deldesc)){
		echo 'no uid or desc';exit;
	}
	if($myservice_idlist != 'all'){
		$myservice_idlist = explode(',',$myservice_idlist);
	}
	
//	error_log(print_r($_GET,true));
	$sql = "SELECT * FROM {$GLOBALS['dbTablePre']}members_search WHERE uid='{$uid}'";
	$sid = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
	if(($sid['sid'] == $GLOBALS['adminid']) || $myservice_idlist=='all' || in_array($sid['sid'],$myservice_idlist)){
		//删除会员转到某个客服名下
		if(file_exists("data/del_to_user.txt")){
			$file_kefuid=fopen("data/del_to_user.txt",'r');
			$del_to_sid=fread($file_kefuid,11);
			fclose($file_kefuid);
		}
		$sql = "UPDATE {$GLOBALS['dbTablePre']}members_search  SET sid='{$del_to_sid}',is_well_user=0  WHERE uid='{$uid}' ";
		$GLOBALS['_MooClass']['MooMySQL']->query($sql);
		searchApi('members_man members_women') -> updateAttr(array('sid','is_well_user'),array($uid=>array((int)$del_to_sid,0)));
		
		//if(MOOPHP_ALLOW_FASTDB) {MooFastdbUpdate('members','uid',$uid);}
		
		//对应的维护会员总数减1
		$sql = "UPDATE {$GLOBALS['dbTablePre']}admin_user SET member_count=member_count-1 WHERE uid='{$sid['sid']}'";
		$GLOBALS['_MooClass']['MooMySQL']->query($sql);

		$sql = "UPDATE {$GLOBALS['dbTablePre']}admin_user SET member_count=member_count+1 WHERE uid='{$del_to_sid}'";
		$GLOBALS['_MooClass']['MooMySQL']->query($sql);
		
		//后台扩展表标记为删除状态
		$sql = "UPDATE {$GLOBALS['dbTablePre']}member_admininfo SET is_delete=1,old_sid='{$sid['sid']}' WHERE uid='{$uid}'";
		$GLOBALS['_MooClass']['MooMySQL']->query($sql);
		
		//写入到后台扩展表小记中
		$time = time();
		$deldesc = '[删除原因]:'.$deldesc;
		$sql = "INSERT INTO {$GLOBALS['dbTablePre']}member_backinfo SET mid='{$GLOBALS['adminid']}',manager='{$GLOBALS['username']}',uid='{$uid}',effect_grade='10',comment='{$deldesc}',dateline='{$time}'";
		$GLOBALS['_MooClass']['MooMySQL']->query($sql);
		
		//删除会员写表记录
		$sql = "INSERT INTO {$GLOBALS['dbTablePre']}admin_deluser SET sid='{$GLOBALS['adminid']}',username='{$GLOBALS['username']}',uid='{$uid}',dateline='{$time}'";
		$GLOBALS['_MooClass']['MooMySQL']->query($sql);		
		if(MOOPHP_ALLOW_FASTDB) {
			$value['sid'] = 52;
			$value['is_well_user']=0;
			MooFastdbUpdate('members_search','uid',$uid,$value);	
		}
		//写日志
		serverlog(3,$GLOBALS['dbTablePre'].'members_search',"{$GLOBALS['username']}客服将自己的会员{$uid}删除放弃",$GLOBALS['adminid'], $uid);
		echo 'ok';
	}
}
function ajax_transfer(){
	$str = '';
	$uid = MooGetGPC('uid','integer','G');
	$sql = "SELECT * FROM {$GLOBALS['dbTablePre']}members_transfer WHERE `uid` = ".$uid." ORDER BY `id` DESC LIMIT 1";
	$transfer = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
	if($transfer){
		$str = json_encode($transfer);
	}else{
		$str = 0;
	}
	echo $str;
}

//会员跟进步骤修改
function ajax_change_grade() {
	global $dbTablePre, $_MooClass, $username, $grade, $adminid, $kefu_arr, $admin_service_arr, $groupid;

	$uid = MooGetGPC('uid', 'integer', 'P');
	$grade_type = MooGetGPC('change_grade', 'integer', 'P');
	$usertype = MooGetGPC('usertype', 'integer', 'P');
//	error_log(print_r($_GET,true));
	$sid = MooGetGPC('sid', 'integer', 'P');
	if(($sid != $adminid) && !in_array($groupid, $admin_service_arr)) {
		echo 'error';die;
	}
	
	if($grade_type) {
		if($grade_type < 10) {
			//跟进步骤修改，写表记录
			$time = time();
			$sql = "INSERT INTO {$dbTablePre}change_grade(uid, sid, states, dateline) values('$uid', '{$adminid}', '$grade_type', '$time')";
			$GLOBALS['_MooClass']['MooMySQL']->query($sql);
			//写日志
			serverlog(4,$dbTablePre.'members_search',"{$username}客服修改会员{$uid}跟进步骤：".$grade[$grade_type], $adminid, $uid);
			echo 'ok';die;
		} elseif($grade_type == 10) {
			global $del_cause_arr;
			$other_cause = '';
			$del_cause_type = MooGetGPC('del_cause', 'integer', 'P');
			$other_cause = MooGetGPC('del_cause', 'string', 'P');

			if($del_cause_arr[$del_cause_type]) {
				$del_cause = $del_cause_arr[$del_cause_type];
			} else {
				$del_cause = $other_cause;
			}

			$sql = "UPDATE {$dbTablePre}members_search  SET sid='123',is_well_user=0  WHERE uid='{$uid}' ";
			
			$GLOBALS['_MooClass']['MooMySQL']->query($sql);
			searchApi('members_man members_women')->updateAttr(array('sid','is_well_user'),array($uid=>array(123,0)));
			//if(MOOPHP_ALLOW_FASTDB) {MooFastdbUpdate('members','uid',$uid);}
			
			//对应的维护会员总数减1
			$sql = "UPDATE {$dbTablePre}admin_user SET member_count=member_count-1 WHERE uid='{$sid}'";
			$GLOBALS['_MooClass']['MooMySQL']->query($sql);
	
			$sql = "UPDATE {$dbTablePre}admin_user SET member_count=member_count+1 WHERE uid='123'";
			$GLOBALS['_MooClass']['MooMySQL']->query($sql);
			
			//后台扩展表标记为删除状态
			$sql = "UPDATE {$GLOBALS['dbTablePre']}member_admininfo SET is_delete=2,old_sid='{$sid}', effect_grade='10' WHERE uid='{$uid}'";
			$GLOBALS['_MooClass']['MooMySQL']->query($sql);
			
			//写入到后台扩展表小记中
			$time = time();
			$deldesc = $adminid.'号客服 '.$kefu_arr[$adminid].' 在'.date("Y-m-d H:i:s").' 将该会员删除, [删除原因]:'.$del_cause;
			$sql = "INSERT INTO {$dbTablePre}member_backinfo SET mid='{$adminid}',manager='{$username}',uid='{$uid}',effect_grade='10', effect_contact='2',comment='{$deldesc}',dateline='{$time}'";
			$GLOBALS['_MooClass']['MooMySQL']->query($sql);
			
			//删除会员写表记录
			$sql = "INSERT INTO {$dbTablePre}change_grade(uid, sid, states, dateline) values('$uid', '$adminid', '$grade_type', '$time')";
			$GLOBALS['_MooClass']['MooMySQL']->query($sql);		
			
			//写到不可回收会员来源统计表中
			$regdate = MooGetGPC('regdate', 'integer', 'P');
			$str = MooGetGPC('source', 'string', 'P');
			$wf = $st = $source = '';
			if(preg_match_all("/(wf=\w+)&?|(st=\w+)&?/i", $str, $matches)) {
				$wf = $matches[1][0];
				$st = $matches[2][1];
				if($wf||$st){
					$source = $wf.'&'.$st;
				}
			}
			$reason = is_numeric($other_cause) ? $other_cause : 7;

			$sqld = "replace {$dbTablePre}delstatistics SET uid='{$uid}',regdate='{$regdate}',deltime='{$time}',reason='{$reason}',mid='{$adminid}',sid=".$sid.",source='{$source}'";
			$GLOBALS['_MooClass']['MooMySQL']->query($sqld);
			if(MOOPHP_ALLOW_FASTDB) {
				$value['sid']=123;
				$value['is_well_user']=0;
				MooFastdbUpdate('members_search','uid',$uid,$value);	
			}
			//写日志
			serverlog(4,$dbTablePre.'members_search',"{$username}客服将自己的会员{$uid}删除放弃",$GLOBALS['adminid'], $uid);
			echo 'ok';die;
		}elseif($grade_type == 11) {
            $sql="select id from web_member_backinfo where effect_grade=11 and uid='{$uid}'" ;
            $result=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
            
            if(empty($result['id'])){
            
	            $sql = "UPDATE {$dbTablePre}members_search  SET sid='389',is_well_user=0 WHERE uid='{$uid}' ";
	           
	            $GLOBALS['_MooClass']['MooMySQL']->query($sql);
	            searchApi('members_man members_women') -> updateAttr(array('sid','is_well_user'),array($uid=>array(389,0)));
	            
	            //对应的维护会员总数减1
	            $sql = "UPDATE {$dbTablePre}admin_user SET member_count=member_count-1 WHERE uid='{$sid}'";
	            $GLOBALS['_MooClass']['MooMySQL']->query($sql);
	    
	            $sql = "UPDATE {$dbTablePre}admin_user SET member_count=member_count+1 WHERE uid='389'";
	            $GLOBALS['_MooClass']['MooMySQL']->query($sql);
	            
	            //后台扩展表标记为删除状态
	            $sql = "UPDATE {$GLOBALS['dbTablePre']}member_admininfo SET is_delete=2,old_sid='{$sid}', effect_grade='11' WHERE uid='{$uid}'";
	            $GLOBALS['_MooClass']['MooMySQL']->query($sql);
	            
	            //写入到后台扩展表小记中
	            $time = time();
	            $desc = $adminid.'号客服 '.$kefu_arr[$adminid].' 将该会员转移到客诉部 389（客诉员）名下';
	            $sql = "INSERT INTO {$dbTablePre}member_backinfo SET mid='{$adminid}',manager='{$username}',uid='{$uid}',effect_grade='11', effect_contact='2',comment='{$desc}',dateline='{$time}'";
	            $GLOBALS['_MooClass']['MooMySQL']->query($sql);
	            
	            //删除会员写表记录
	            $sql = "INSERT INTO {$dbTablePre}change_grade(uid, sid, states, dateline) values('$uid', '$adminid', '$grade_type', '$time')";
	            $GLOBALS['_MooClass']['MooMySQL']->query($sql);     
	            
	           
	            //写日志
	            serverlog(4,$dbTablePre.'members_search',"{$username}客服将会员{$uid}移到客诉部",$GLOBALS['adminid'], $uid);
	            echo 'ok';die;
            }
            
        }
	}
}



//设置重点跟进会员
function ajax_important(){
    global $dbTablePre;
    $userid=MooGetGPC('uid','integer','P');
    $effect_grade=MooGetGPC('effect_grade','integer','P');
    $next_contact_time=MooGetGPC('next_contact_time','integer','P');
    //$sql1="update web_member_backinfo set master_member='{$key}' where uid='{$userid}'";
    
    $sql="select count(id) as num from web_member_goon where  uid='{$userid}'";
    $result=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
    
    //判断是否存在于跟进会员表中
    if($result['num']>0){
      $sql2="delete from web_member_goon where uid='{$userid}'";
    }else{
      $sid=$GLOBALS['adminid'];
      $sql2="insert into web_member_goon set uid='{$userid}' , sid='{$sid}',effect_grade='{$effect_grade}',next_contact_time='{$next_contact_time}'";
    }
    
    if($GLOBALS['_MooClass']['MooMySQL']->query($sql2)){
	    serverlog(4,$dbTablePre.'member_backinfo',$GLOBALS['adminid']."将ID：{$userid}设置为重点会员",$GLOBALS['adminid'],$userid);
        echo "ok";
        
    }
}


//预测可控会员
function ajax_control(){
    global $dbTablePre,$timestamp;
    $userid=MooGetGPC('uid','integer','P');
	$isControl=MooGetGPC('isControl','integer','P');
	$isForcast=MooGetGPC('isForcast','integer','P');


    $sql="select flag,isforcast from web_member_isControl where  uid='{$userid}'";
    $result=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
    
    //判断是否存在于跟进会员表中
    
    $sid=$GLOBALS['adminid'];
	if (!empty($result)){
       $sql2="update web_member_isControl set flag='{$isControl}' , isforcast='{$isForcast}',dateline='{$timestamp}' where uid={$userid}";
    }else{
	   $sql2="insert into web_member_isControl set uid='{$userid}' , sid='{$sid}',dateline='{$timestamp}',flag={$isControl},isforcast={$isForcast}";
	}
    
    if($GLOBALS['_MooClass']['MooMySQL']->query($sql2)){
	    serverlog(4,$dbTablePre.'member_backinfo',$GLOBALS['adminid']."将ID：{$userid}设置为可控（预测）会员",$GLOBALS['adminid'],$userid);
        echo "ok";
        
    }
}





/***************************************控制层(V)***********************************/
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // 过去的时间

$n=MooGetGPC('n','string');
//判断是否登录
adminUserInfo();
if(empty($GLOBALS['adminid'])){
	echo '未登录';exit;
}

switch($n){
	//note 重点跟进会员
    case 'important':
        ajax_important();
    break;
	//note 预测可控会员
    case 'control':
        ajax_control();
    break;
    
	case 'change_awoke':
		ajax_change_awoke();
	break;
	case 'delmyuser':
		ajax_delmyuser();
	break;
	case 'transfer'://会员交接信息
		ajax_transfer();
	break;
	case 'change_grade':
		ajax_change_grade();
	default:
		echo 'no method';
	break;
}
?>
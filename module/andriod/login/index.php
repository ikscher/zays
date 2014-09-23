<?php
//登录页

header("Content-Type: application/json; charset=UTF-8");

include "module/index/function.php";
include "module/andriod/function.php";
/**
 * 登录表单处理
 * 描述：
 */
function login_submit() {
	global $_MooClass,$dbTablePre,$_MooCookie,$uuid,$memcached;
	
	$error = array();
	//noet 对提交的数据过滤
	if($_POST) {
		$username = trim($_POST['username']);
//		$username = "1096621";
		$md5_password = md5($_POST['password']);
//		$md5_password = md5(123456);
		$cookietime = intval(isset($_POST['cookietime'])?$_POST['cookietime']:'');
		$remember_username = MooGetGPC('remember','integer','P');
	}
/*****设置回转的页面*****/
	if(empty($_POST['username']) && empty($_POST['password'])) {
		//note 转至邮箱验证页
		$error="用户名和密码为空";
		echo return_data($error,false);exit;
	}
	
	//note 用户名不能为空
	if(empty($_POST['username'])) {
		//note 转至邮箱验证页
		$error = "用户名为空";
        echo return_data($error,false);exit;
	}
	//note 密码不能为空
	if(empty($_POST['password'])) {
		//note 转至邮箱验证页
		$error = "密码为空";
        echo return_data($error,false);exit;
	}
	if(empty($error)){
		$userid = 0;
		$sp = searchApi('members_man members_women');
		$limit = array(0, 1);
		//note 验证用户名，密码     enky
		if(is_numeric($username)){
			if(strlen($username)==11){
				//判断手机号是否存在
				$filter = array();
				$filter[] = array('telphone', $username);
				if($sp->getResultOfReset($filter, $limit)){
					$ids = $sp->getIds();
					if(isset($ids[0])) $userid = $ids[0];
				}
			}
			if(!$userid){
				//判断uid是否存在
				$filter = array();
				$filter[] = array('@id', $username);
				if($sp->getResultOfReset($filter, $limit)){
					$ids = $sp->getIds();
					if(isset($ids[0])) $userid = $ids[0];
				}
			}
			if(!$userid){
				if($user_one = $_MooClass['MooMySQL']->getOne("SELECT uid from `{$dbTablePre}members_base` where qq='{$username}'",true))
					$userid = $user_one['uid'];				
			}
		}else{
			$filter = array();
			$filter[] = array('username', $username);
			if($sp->getResultOfReset($filter, $limit)){
				$ids = $sp->getIds();
				if(isset($ids[0])) $userid = $ids[0];
			}
		}
		//note 用户名找不到
		if(!$userid) {
			$login_where = is_numeric($username) ? "uid='{$username}' or telphone='{$username}'" : "username='{$username}'";
			$user_one = $_MooClass['MooMySQL']->getOne("SELECT uid from `{$dbTablePre}members_search` where $login_where",true);
			if($user_one){
				$userid = $user_one['uid'];
			}else{

				$error = "用户名不存在";
                echo return_data($error,false);exit;
			}
		}
		//获取
		$user = array_merge(MooGetData('members_login', 'uid', $userid), MooMembersData($userid));
		
		if($user['is_lock']!='1'){
			$error = "用户已经被锁定";
            echo return_data($error,false);exit;
		}
		//note 用户密码错误
		if($user['uid'] && $user['password'] != $md5_password) {
			//note 转至邮箱验证页
			$error = "密码错误";
            echo return_data($error,FALSE);exit;
		}
		if(empty($error)){
		//note 验证通过
			if($user['uid'] && $user['password'] == $md5_password) {
		        //生成随机唯一id,andriod用
		        $userid = $user['uid'];
				$uuid = user_md5_id($user['uid']);
				$memcached->set ( 'uuid_'.$userid, $uuid, 0, 0 );
				$memcached->set('uid_'.$userid, $userid, 0,0);
				$user['uuid'] = $uuid;
				if($user['automatic']==1){
			        MooSetCookie('auth',MooAuthCode("$user[uid]\t$user[password]",'ENCODE'),86400*7);
				}else{		
				    MooSetCookie('auth',MooAuthCode("$user[uid]\t$user[password]",'ENCODE'),86400);
				}
				
				$user_sha = $_MooClass['MooMySQL']->getOne("SELECT lastvisit from `{$dbTablePre}members_login` where uid='{$userid}'");
				$s_time = $user_sha['lastvisit'];
				$ret_count = $_MooClass['MooMySQL']->getOne("SELECT count(uid) as c FROM {$dbTablePre}service_visitor WHERE uid >0 and visitorid = '$userid' AND who_del !=2 and visitortime > '$s_time' ");
				$shadow_numb = $ret_count['c']?$ret_count['c']:0;
				
				// MooSetCookie('auth','SDFSFGAFGD\AHFGHGHJ',86400);
				$time = time();
				$_MooClass['MooMySQL']->query("update {$dbTablePre}members_login set last_login_time = '{$time}',login_meb = login_meb+1,lastvisit='{$time}' where uid = '{$user['uid']}'");//更新最后登录时间
				//会员最后登录时间
		        MooSetCookie('last_login_time', $time,86400);
				//note 客服提醒
				if($user['is_awoke'] == '1'){
					$awoketime = time()+120;
					$sql = "INSERT INTO `{$dbTablePre}admin_remark` SET sid='{$user['sid']}',title='会员上线',content='ID:{$user['uid']}会员刚刚通过pc机上线,请联系',awoketime='{$awoketime}',dateline='{$GLOBALS['timestamp']}'";
					$_MooClass['MooMySQL']->query($sql);			
				}		
				MooPlugins('ipdata');
				$online_ip = GetIP();						
				if($online_ip != $user['lastip']){
					$user_address = convertIp($online_ip);
				    include("./module/crontab/crontab_config.php");    
				    foreach($provice_list as $key=>$provice_arr){
				        if(strstr($user_address,$provice_arr)!==false){
				       	//if(){
				            $province=$key;
				            break;
				        }
				    }
				    if(empty($province)){
				        $province=$current_user['province'];
				    }
				    //得到市对应的城市代号
				    foreach($city_list as $city_key => $city_val){
				      if(strstr($user_address,$city_val)!==false){
				         $city = $city_key;
				         break;
				      }
				    }
				}
				MooSetCookie('province', $user['province'],86400);
				MooSetCookie('city', $user['city'],86400);
				$lastactive = time();
				$uid = $user['uid'];
				//note 更新用户的最近登录ip和最近登录时间
				$updatesqlarr = array(
					'lastip' => $online_ip,
				);
				$wheresqlarr = array(
					'uid' => $uid
				);
				updatetable("members_login",$updatesqlarr,$wheresqlarr);
				if(MOOPHP_ALLOW_FASTDB){
					$val = array();
		            $val['lastip'] = $online_ip;
		            //$val['client']=0;
		            $val['last_login_time']= $time;
		            $val['lastvisit']=$time;
		            //$val['isOnline']=1;
					MooFastdbUpdate('members_login','uid',$uid, $val);//!!
				}
				
			
				//记录本次登录ip及上次的ip,及真实的最后访问时间
				$sql_ip = "SELECT last_ip,finally_ip FROM {$GLOBALS['dbTablePre']}member_admininfo WHERE uid='{$uid}'";
				$member_admin_info = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql_ip);
				if(!empty($member_admin_info)){
					$sql_ip = "UPDATE {$GLOBALS['dbTablePre']}member_admininfo SET last_ip='{$member_admin_info['finally_ip']}',finally_ip='{$online_ip}',real_lastvisit='{$lastactive}' WHERE uid='{$uid}'";
				}else{
					$sql_ip = "INSERT INTO {$GLOBALS['dbTablePre']}member_admininfo SET finally_ip='{$online_ip}',uid='{$uid}',real_lastvisit='{$lastactive}'";
				}
				$GLOBALS['_MooClass']['MooMySQL']->query($sql_ip);
			}
		}

					
		}
			
		
		//资料完善度
		$all_len = 0;
		$user_arr = MooMembersData($uid);
		if ($uid) {
			$all_len = ( int ) (getUserinfo_per ( $user_arr ) * 100);
		}
		$user['all_len'] = $all_len;
		
		$user['choice'] = MooGetData ( 'members_choice', 'uid', $uid );     
		if(MooGetphoto($user['uid'],'com')){
			$mainimg = MooGetphoto($user['uid'],'com');
		} 
		$user_sha = $_MooClass['MooMySQL']->getOne("SELECT telphone from `{$dbTablePre}certification` where uid='{$userid}'");
		if(empty($error)){
			$user['telphonesha'] = isset($user_sha['telphone'])?$user_sha['telphone']:0;	
			$user['mainimg'] = $mainimg;
			$user['password'] = '';
			$user['regip'] = '';
			$user['qq'] = '';
			$user['msn'] = '';
			$user['telphone'] = '';
			$user['username'] = '';
			$user['numofvis'] = $shadow_numb;
			
			$ret_count1 = $_MooClass['MooMySQL']->getOne("SELECT count(s_uid) as c FROM {$dbTablePre}services WHERE s_uid = '$userid' and flag = '1' and s_uid_del='0' and s_status = '0' ",true);
			$total1 = $ret_count1['c'];//2009-11-22日修改(得到总数)
			$user['nummail'] = $total1;
			
		    echo return_data($user,true);  
            exit;
//            echo "<pre>$return</pre>";exit;		
		}else{
			$error = "用户名或密码错误";
			echo return_data($error,false);
	        exit;
		}
		
}
   
//退出
function login_logout(){
	global $_MooClass,$user_arr,$uuid,$memcached;
	//note 清空cookie，同时清空session表记录
	//clearcookie();
//	MooClearCookie();
    $return=array();
	$and_uuid = $_GET['uuid'];
	$uid =  $_GET['uid'] = isset($_GET['uid'])?$_GET['uid']:'';
	if($uid){
		$mem_uid = $memcached->get('uid_'.$uid);
	}
	$checkuuid = check_uuid($and_uuid,$mem_uid);
	$uuid = $memcached->get('uuid_'.$mem_uid);
	if($checkuuid){
		$logout_uuid = $memcached->delete('uuid_'.$mem_uid);
		$logout_uid = $memcached->delete('uid_'.$mem_uid);
	}
	if($logout_uuid && $logout_uid){
		$return ="成功退出";
		echo return_data($return,true);exit;
	}else{
		
		$return = "uuid_error";
		echo return_data($return,false);exit;
	}
	
}



//接受手机号码，发送手机验证码
function sendmes(){
	global $_MooClass,$dbTablePre,$memcached;
	
	
	//$telphone = MoogetGPC ( 'tel', 'string', 'G' );
	if($_GET['tel']){
		$telphone = $_GET['tel'];
		$tele = $telphone % 1000000000;
	}
	else{
		$return = "未填写手机号码";
		echo return_data($return,false);exit;
	}
	
	$sql = "select sendtime from {$dbTablePre}smslog_sys where uid = '$tele' ";
	$r = $_MooClass ['MooMySQL']->getOne($sql,true);
	$time = time();
	if($r['sendtime']){
		if($time < $r['sendtime'] + 60){
			$return = "1分钟内只能发送1次验证码，请稍后再试！";
			exit(return_data($return,false));
		}
	}
	
	
	$rand = rand(362144,965421);
	
	if(SendMsg($telphone,"欢迎您注册真爱一生网！您本次注册的手机认证码为：".$rand.",本认证码1小时内有效。")){
		
		
		
		$sql = "select count(*) as a from {$dbTablePre}smslog_sys where uid = '$tele' ";
		$r1 = $_MooClass ['MooMySQL']->getOne ( $sql,true);
		if ($r1[a] == 0) {
			$sql = "INSERT INTO {$dbTablePre}smslog_sys (id,sid,uid,content,sendtime,type) values('','','$tele','$rand','$time','注册验证码')";
	
		} else {
		
			$sql = "UPDATE {$dbTablePre}smslog_sys set content='$rand' , sendtime = '$time' where uid='$tele' ";
		}
		
		//$sql = "REPLACE INTO {$dbTablePre}smslog_sys set content='$rand' where uid='$telphone' ";
		
		//$sql = "REPLACE INTO {$dbTablePre}smslog_sys (id,sid,uid,content,sendtime,type) values('','','$tele','$rand','$time','注册验证码')";
		$_MooClass ['MooMySQL']->query($sql);
		$sql = "select content,sendtime from {$dbTablePre}smslog_sys where uid = '$tele' ";
		$r2 = $_MooClass ['MooMySQL']->getOne($sql,true);
		
		$return = "验证码已发送";
		//echo $r2['content'].' '.$r2['sendtime'];
		echo return_data($return,true);exit;		
		
	}
	else{
		$return = "发送短信验证码失败";
		echo return_data($return,false);exit;
	}
	
}
/*
 ******************************控制层******************************* */
switch ($_GET['h']) {
	case "submit" :
		login_submit();   
		break;
	case "logout" :
		login_logout();
		break;
	case"sendmes":
		sendmes();
		break;
    default:
    	login_submit();
    	break;
}


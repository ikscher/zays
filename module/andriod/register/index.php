<?php
/*
 * Created on 2007-10-13 To change the template for this generated file go to*
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
/**
 * ************************************* 逻辑层(M)/表现层(V)
 * ***************************************
 */
//header ( "Expires: Mon 26 Jul 1997 05:00:00 GMT" );
//header ( "Last-Modified: " . gmdate ( "D d M Y H:i:s" ) . " GMT" );
//header ( "Cache-Control: no-store no-cache must-revalidate" );
//header ( "Cache-Control: post-check=0 pre-check=0", false );
//header ( "Pragma: no-cache" );
include "./module/{$name}/function.php";
include "module/andriod/function.php";

/**
 * 功能列表
 */

// note 以“,”号连接数组
function ArrToStr($arr) {
	$string = implode ( ",", $arr );
	return $string;
}


// note 注册第一步
function register_stepone() {
	global $_MooClass, $user_arr, $memcached, $_MooCookie;

	MooPlugins ( 'ipdata' );
	$address = convertIp ( GetIP () );

}



/**
 * ************************************* 控制层(C)
 * ***************************************
 */

error_reporting(0);

$h = MooGetGPC ( 'h', 'string' );
//$h = in_array ( $h, array ('stepone', 'stepsix', 'validateemail', 'seccode', 'incenter', 'serverrule' ) ) ? $h : 'stepone';
$h = in_array($h, array('stepone'));
// note 解密Cookies
/*
 * if ($_MooCookie['auth']){ list($username, $password, $uid) =
 * MooAddslashes(explode("\t", MooAuthCode($_MooCookie['auth'], 'DECODE'))); }
 * //$uid = 14; $user = $_MooClass['MooMySQL']->getOne("SELECT * FROM
 * {$dbTablePre}members as m,web_memberfield as mf WHERE m.uid='$uid' AND
 * m.uid=mf.uid"); $c = $_MooClass['MooMySQL']->getOne("SELECT * FROM
 * {$dbTablePre}choice WHERE uid='$uid'"); //实例化数据验证类
 */

$_MooCookie ['auth'] = '';

$v = MooAutoLoad ( 'MooValidation' );

switch ($h) {
	case "stepone" :
		
		global $_MooClass, $user_arr, $memcached, $_MooCookie;
		if ($_SERVER ['REQUEST_METHOD'] == 'POST') {
			$error = array();
			$istueiguang = MooGetGPC ( 'istueiguang', 'string' );
			$friendprovince = '';
			if (empty ( $istueiguang )) {

			}
			$username = MooGetGPC ( 'username', 'string' );
			$password = MooGetGPC ( 'password', 'string' );
			$password2 = MooGetGPC ( 'password2', 'string' );
			$gender = MooGetGPC ( 'gender', 'integer' );
			$telphone = MooGetGPC ( 'telphone', 'string' );
			$birthyear = MooGetGPC ( 'year', 'integer' );
			$birthmonth = MooGetGPC ( 'month', 'integer' );
			$birthday = MooGetGPC ( 'day', 'integer' );
			$nickname = safeFilter ( rtrim ( MoogetGPC ( 'nickname', 'string', 'P' ) ) );
			$marriage = MoogetGPC ( 'marriage1', 'integer', 'P' );
			$workprovince = MooGetGPC ( 'workprovincereg', 'integer' );
			$workcity = MooGetGPC ( 'workcity', 'integer' );
			
			
			if($_POST['yanzheng']){
				$shadow_yan = $_POST['yanzheng'];
			}
			else{
				$return = "未填写验证码";
				echo return_data($return,false);exit;
			}
			$tele = $telphone % 1000000000;
			
			$sql = "select content from {$dbTablePre}smslog_sys where uid = '$tele' ";
			$r = $_MooClass ['MooMySQL']->getOne($sql,true);
			if($r['content'] != $shadow_yan){
				$return = "验证码错误";
				echo return_data($return,false);exit;
			}
			
			
			
							
			
			if (in_array ( $workprovince, array (10101201, 10101002 ) )) {
				// note 修正广东省深圳和广州的区域查询 2010-09-04
				$workcity = $workprovince;
				$workprovince = 10101000;
			}
			
//			$agree = MooGetGPC ( 'agree', 'integer' );
			$ip = GetIP ();
			
			
			if (in_array ( $hometownprovince, array (10101201, 10101002 ) )) {
				// note 修正广东省深圳和广州的区域查询 2010-09-04
				$hometowncity = $hometownprovince;
				$hometownprovince = 10101000;
			}

		     
			
			
			
			
			// note 用户名验证
			if ((empty($username)) || (preg_match ( '/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/', $username)==false)) {
				$error = "用户名格式不正确";
				
				echo return_data($error,false);exit;
			} else {
				if ($_MooClass ['MooMySQL']->getOne ( "SELECT uid FROM {$dbTablePre}members_search WHERE username='$username'" ,true)) {
					
					$error = "用户名已存在";
					echo return_data($error,false);exit;
					
				}
				// 验证密码
				if (! $v->checkMax ( $password, 20 )) {
			 		$error = "密码长度不得大于20位";		
			 		echo return_data($error,false);exit;
				}
				if (! $v->checkMin ( $password, 6 )) {
					$error = "密码长度不得小于6位";
					echo return_data($error,false);exit;
				}
				if (! $v->checkSame ( $password, $password2 )) {
					$error = "两次输入的密码不相同,请确认";		
					echo return_data($error,false);exit;
				}  
				
				// note 手机必填
				$tel_arr = array ('15212412915', '13866164340', '13866770139', '15209865789', '15855160903', '13965098471', '13659140698', '15867121348', '15930210816', '18601183459', '15001266073' ,'13856900659');
				if (! empty ( $telphone ) && ! in_array ( $telphone, $tel_arr )) {
					if (! preg_match ( '/^((1[345]\d{9})|(18[0-9]\d{8}))$/', $telphone )) {
						$error = "手机不符合规范"; 
						echo return_data($error,false);exit;
					} else {
						$tel = $_MooClass ['MooMySQL']->getOne ( "SELECT count(1) as a FROM {$dbTablePre}members_search WHERE telphone = '$telphone'" ,true);
						if ($tel ['a'] >= 1) {
							$error = "该手机号码已经存在";
							echo return_data($error,false);exit; 
						}
					}
				} elseif (empty ( $telphone )) {
					$error = "手机号必填";
					echo return_data($error,false);exit;
				}
				/*
				//昵称必填
				if (rtrim ( $nickname ) != '') {
						if (preg_match ( '/^((1[345]\d{9})|(18[024-9]\d{8})|(010-?\d{8})|(02)[012345789]-?\d{8}|(0[3-9]\d{2,2}-?\d{7,8})|(.*@.*))$/', $nickname )) {
							$error = "昵称不符合规范";
							echo return_data($error,false);exit;
						}
						// note 昵称截取
						$nickname = MooCutstr ( $nickname, 12, $dot = '' );
						
				} else {
					$error = "昵称必填";
					echo return_data($error,false);exit;
				}
				*/
				// 来源，发送到统计用
				$where_from = isset ( $_MooCookie ['where_from'] ) ? $_MooCookie ['where_from'] : '';
				//修正推广程序
				if(!empty($istueiguang)){
					$hometownprovince=empty($hometownprovince)?$workprovince:$hometownprovince;
				}
//				var_dump($error);exit;
				if(empty($error)){
				// note 出生日期和工作地区验证
				
					if ($birthyear != 0 && $birthmonth != 0 && $birthday != 0 && $workprovince != 0 ) {
						$website = isset($_MooCookie ['website'])?$_MooCookie ['website']:'';
						$puid = isset($_MooCookie ['puid'])?$_MooCookie ['puid']:'';
						$wherefrom = isset($_MooCookie ['w_from'])?$_MooCookie ['w_from']:'';
						$password = md5 ( $password );

						$birth = "$birthyear-$birthmonth-$birthday";
						
						$hometownprovince = $hometownprovince == '0' ? '0' : $hometownprovince;
						$hometowncity = $hometowncity == '0' ? '0' : $hometowncity;

						$updatetime = time ();
						
						//事务开始
						$_MooClass ['MooMySQL']->query ( "begin;");
						$_MooClass ['MooMySQL']->showError = false;
						$sql_res = array();//事务数组

						
						$sql = "INSERT INTO {$dbTablePre}members_search SET username='" . $username . "', password='" . $password . "', regdate='" . $timestamp . "', gender='" . $gender . "',   telphone='" . $telphone . "', birthyear='" . $birthyear . "',  province='" . $workprovince . "', city='" . $workcity . "',hometownprovince='" . $hometownprovince . "',nickname='".$nickname."',marriage='".$marriage."',updatetime='" . $updatetime . "',hometowncity='" . $hometowncity . "'";
						$sql_res[] =  $_MooClass ['MooMySQL']->query ( $sql );
						$uid = $_MooClass ['MooMySQL']->insertId ();
						if($sql_res[0] && $uid){
							$sql2 = "REPLACE INTO {$dbTablePre}members_base SET uid='".$uid."', regip='" . $ip . "',birth='" . $birth ."', puid='" . $puid . "',website='" . $website . "', source='{$where_from}'";
							
							$sql3 = "REPLACE INTO {$dbTablePre}members_login SET uid='".$uid."',lastip='".$ip."', lastvisit='" . $GLOBALS ['timestamp'] . "',last_login_time='{$timestamp}'";
							if ($callno)
								$sql2 .= ", callno='{$callno}'";
							$sql_res[] =  $_MooClass ['MooMySQL']->query ( $sql2 );
							//$_MooClass ['MooMySQL']->query ( $sql );
							$sql_res[] =  $_MooClass ['MooMySQL']->query ( $sql3 );
							
							$sql_res[] =  $_MooClass ['MooMySQL']->query ( "REPLACE INTO {$dbTablePre}members_choice SET uid='$uid'" );
							$sql_res[] = $_MooClass ['MooMySQL']->query ( "REPLACE INTO {$dbTablePre}members_introduce SET uid='$uid'" );
						}
						//事务
						$sql_res_isrollback = false;
						foreach ($sql_res as $v){
							if (!$v){
								$sql_res_isrollback = true;
								$_MooClass ['MooMySQL']->query("rollback");
								break;
							}						
						}
						
						if($sql_res_isrollback){
							$error = "注册失败";
							echo return_data($error,false);exit; 
							
						}else{
							$_MooClass ['MooMySQL']->query("commit");
							$_MooClass ['MooMySQL']->showError = true;
						}
						
						$ToAddress = $username;
						$ToSubject = '［真爱一生网］感谢您的注册';
						
						// 插入后台扩展表，记录最后访问时间，最后登录ip
						$sql = "INSERT INTO {$GLOBALS['dbTablePre']}member_admininfo SET finally_ip='{$ip}',uid='{$uid}',real_lastvisit='{$GLOBALS['timestamp']}'";
						$sql_res[] = $_MooClass ['MooMySQL']->query ( $sql );
						
						sendMailByNow ( $ToAddress, $ToSubject, $ToBody );
						// 注册站内信提醒
						$s_title = "［真爱一生网］感谢您的注册";
						$s_content = '尊敬的' . $ToAddress . '：<br>您好！您已经正式注册成为真爱一生网的会员用户，以下是您的注册信息：<br>用户名称：' . $ToAddress . '<br>用户密码:' . $password2 . "，建议您完善您的资料并尽快进行诚信认证，以便获得更高关注度，真爱一生网祝您早日找到自己的另一半";
						$sql = "insert into {$dbTablePre}services set s_cid = 3,s_uid = '" . $uid . "',s_title = '" . $s_title . "',s_content = '" . $s_content . "',s_time = '" . time () . "',is_server = '1'";
						$sql_res[] = $_MooClass ['MooMySQL']->query ( $sql );
						
						MooSetCookie ( "auth", MooAuthCode ( "$username\t$password\t$uid", "ENCODE" ), 86400 * 7 );
						
						// 加鲜花
						if ($puid) {
							MooAddRose ( $puid, 3 );
						}
						
						// 发送统计请求
						
						// 如果从推广过来修改此值
						$rf_url = parse_url ( $_SERVER ["HTTP_REFERER"] );
						if ($rf_url ["host"] == $rf_url ["host"]) {
							if (strstr ( $_SERVER ["HTTP_REFERER"], "/pop/" ) !== false || strstr ( $_SERVER ["HTTP_REFERER"], "/reg/" ) !== false) {
								$where_from = urlencode ( $_SERVER ["HTTP_REFERER"] );
							} else {
								$where_from = urlencode ( $where_from );
							}
						}
						$is_phone = ! empty ( $telphone ) ? 1 : 0;
						$md5 = md5 ( $uid . $puid . 2 . $website . $ip . $where_from . MOOPHP_AUTHKEY );
						$apiurl = "http://mycount.7652.com/hongniang/hongniang_import.php?ip=" . $ip . "&uid=" . $uid . "&puid=" . $puid . "&s_cid=2&website=" . $website . "&keyurl=" . $where_from . "&md5=" . $md5 . "&gender=" . $gender . "&phone=" . $telphone . "&is_phone=" . $is_phone . "&birthyear=" . $birthyear;
						if (file_get_contents ( $apiurl ) != "ok") {
							$dateline = time ();
							$sql = "INSERT INTO {$GLOBALS['dbTablePre']}do_url VALUES('','{$apiurl}','{$dateline}',0,0)";
							$GLOBALS ['_MooClass'] ['MooMySQL']->query ( $sql );
						}
						// htotal
						$h_time = getdate ( time () );
						$apiurl2 = "http://222.73.4.240/tj/index/reg?act=register&i=" . $ip . "&u=" . $uid . "&pu=" . $puid . "&w=1&site=" . $website . "&urr=" . $where_from . "&sex=" . $gender . "&p=" . $is_phone . "&t=" . strtotime ( date ( "Y-m-d" ) ) . "&h=" . $h_time ["hours"] . "&n=" . $username;
						@file_get_contents ( $apiurl2 );
						
						$rand = rand ( 362144, 965421 );
						MooSetCookie ( "rand", md5 ( md5 ( $rand ) ) );
				
						
						// 希希奥信息发送手机短信接口
						$re = SendMsg ( $telphone, "注册真爱一生网成功 ID:" . $uid );
						
						if ($re) {
							$time = time ();
							$content = "注册成功提醒";
							$sql_res[] =  $_MooClass ['MooMySQL']->query ( "INSERT INTO {$dbTablePre}smslog_sys (id,sid,uid,content,sendtime,type) values('','','$uid','$content','$time','注册成功提醒')" );
							//$sql = "DELETE FROM {$dbTablePre}smslog_sys where uid = '$tele'";
							//$_MooClass ['MooMySQL']->query($sql);
						}
						MooSetCookie ( 'auth', MooAuthCode ( "$uid\t$password", 'ENCODE' ), 86400 * 7 );
						MooSetCookie ( "where_from", "" );
						MooSetCookie ( "puid", "" );
						MooSetCookie ( "website", "" );

						MooSetCookie ( 'password2', $password2, 3600 );

						// }
					
					} else {
						$error = "填写资料不全";
						echo return_data($error,false);exit;
					}
				}
			}
		} else {
			
			if ($userid) {
				$error = "您现在是登陆状态";
				echo return_data($error,false);exit;
			}
			register_stepone ();
		}
		if(empty($error) && $uid){
			global $uuid;
			certification( "telphone", $telphone, $uid );
			$uuid = user_md5_id($uid);
			$memcached->set ( 'uuid_'.$uid, $uuid, 0, 0 );
			$memcached->set('uid_'.$uid,$uid,0,0);
			$user['uuid'] = $uuid;
			$return = array();
			$return = array('uuid'=>$uuid,'uid'=>$uid);
			
		    echo return_data($return);
			exit;
		}else{
			
			$error = "注册失败，请重新注册";
			echo return_data($error,FALSE);
			exit;
		}
		break;
//	     default :
//		register_stepone ();
//		break;
}

function certification($key, $val, $uid) {
	global $_MooClass, $dbTablePre;
	
	$sql = "select count(*) as a from {$dbTablePre}certification where uid='$uid' ";
	$r = $_MooClass ['MooMySQL']->getone ( $sql,true);
	if ($r [a] == 0) {
		$sql = " insert into {$dbTablePre}certification (uid,$key) values('$uid','$val')";
	
	} else {
		
		$sql = "update {$dbTablePre}certification set $key='$val' where uid='$uid' ";
	}
	
	$_MooClass ['MooMySQL']->query ( $sql);
	
	if (MOOPHP_ALLOW_FASTDB) {
		$cert_arr = array ('uid' => $uid, $key => $val );
		MooFastdbUpdate ( 'certification', 'uid', $uid, $cert_arr );
	}
	// 更新诚信值
	reset_integrity ( $uid );
}

?>
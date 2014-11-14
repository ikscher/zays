<?php
/*
 * Created on 2007-10-13 To change the template for this generated file go to*
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
/**
 * ************************************* 逻辑层(M)/表现层(V)
 * ***************************************
 */
header ( "Expires: Mon 26 Jul 1997 05:00:00 GMT" );
header ( "Last-Modified: " . gmdate ( "D d M Y H:i:s" ) . " GMT" );
header ( "Cache-Control: no-store no-cache must-revalidate" );
header ( "Cache-Control: post-check=0 pre-check=0", false );
header ( "Pragma: no-cache" );
include "./module/{$name}/function.php";

/**
 * 功能列表
 */

// note 以“,”号连接数组
function ArrToStr($arr) {
	$string = implode ( ",", $arr );
	return $string;
}
// note 获取members_search、members_base表字段并改值
function getMemberfield() {
	// note memberfield表字段
	$members_base ['qq'] = MoogetGPC ( 'qq', 'string', 'P' );
	$members_base ['msn'] = MoogetGPC ( 'msn', 'string', 'P' );
	// note QQ格式
	if ($members_base ['qq'] != '' && ! preg_match ( '/^\d{5,11}$/', $members_base ['qq'] )) {
		MooMessage ( "QQ格式不正确！", "index.php?n=register&h=stepthree" );
	}
	// note MSN格式
	if ($members_base ['msn'] != '' && ! preg_match ( '/^\w+([-_]\w+)*@(\w{2,}\.)+[a-zA-Z]{2,}$/', $members_base ['msn'] )) {
		MooMessage ( "MSN格式不正确！", "index.php?n=register&h=stepthree" );
	}
	$members_search ['updatetime'] = time ();
	$members_search ['body'] = MoogetGPC ( 'body3', 'integer', 'P' );
	$members_search ['hometownProvince'] = MoogetGPC ( 'hometownProvince3', 'integer', 'P' );
	$members_search ['hometownCity'] = MoogetGPC ( 'hometownCity3', 'integer', 'P' );
	$members_base ['currentprovince'] = MooGetGPC ( 'currentprovince', 'integer', 'P' );
	$members_base ['currentcity'] = MooGetGPC ( 'currentcity', 'integer', 'P' );
	// 期望交友所在地区
	$friendprovince = MooGetGPC ( 'currentprovince', 'integer', 'P' );
	if ($friendprovince) {
		foreach ( $_POST ['friendprovince'] as $key => $val ) {
			if (in_array ( $val, array (10101201, 10101002 ) )) {
				$_POST ['friendcity'] [$key] = $val;
				$val = 10101000;
			}
			$friend_area [] [$val] = $_POST ['friendcity'] [$key];
		}
		$members_base ['friendprovince'] = serialize ( $friend_area );
	}
	
	if (in_array ( $members_search ['hometownProvince'], array (10101201, 10101002 ) )) {
		// note 修正广东省深圳和广州的区域查询 2010-09-04
		$members_search ['hometownCity'] = $members_search ['hometownProvince'];
		$members_search ['hometownProvince'] = 10101000;
	}
	
	$members_search ['wantchildren'] = MoogetGPC ( 'wantchildren3', 'integer', 'P' );
	$members_search ['nation'] = MoogetGPC ( 'stock3', 'integer', 'P' );
	$members_search ['occupation'] = MoogetGPC ( 'occupation3', 'integer', 'P' );
	$members_base ['nature'] = MoogetGPC ( 'nature3', 'integer', 'P' );
	
	$members_search ['truename'] = safeFilter ( MoogetGPC ( 'truename', 'string', 'P' ) );
	$members_search ['weight'] = MoogetGPC ( 'weight', 'integer', 'P' );
	$members_search ['animalyear'] = MoogetGPC ( 'animals', 'integer', 'P' );
	$members_search ['constellation'] = MoogetGPC ( 'constellation', 'integer', 'P' );
	$members_search ['bloodtype'] = MoogetGPC ( 'bloodtype', 'integer', 'P' );
	$members_search ['religion'] = MoogetGPC ( 'belief', 'integer', 'P' );
	$members_base ['finishschool'] = safeFilter ( MoogetGPC ( 'finishschool', 'string', 'P' ) );
	$members_search ['family'] = MoogetGPC ( 'family', 'integer', 'P' );
	$members_search ['language'] = ArrToStr ( MoogetGPC ( 'tonguegifts', 'array', 'P' ) );
	
	$members_search ['smoking'] = MoogetGPC ( 'smoking', 'integer', 'P' );
	$members_search ['drinking'] = MoogetGPC ( 'drinking', 'integer', 'P' );
	$members_search ['vehicle'] = MoogetGPC ( 'vehicle', 'integer', 'P' );
	$members_search ['corptype'] = MoogetGPC ( 'corptype', 'integer', 'P' );
	$members_base ['fondfood'] = ArrToStr ( MoogetGPC ( 'fondfoods', 'array', 'P' ) );
	$members_base ['fondplace'] = ArrToStr ( MoogetGPC ( 'fondplaces', 'array', 'P' ) );
	
	$members_base ['fondactivity'] = ArrToStr ( MoogetGPC ( 'fondactions', 'array', 'P' ) );
	$members_base ['fondsport'] = ArrToStr ( MoogetGPC ( 'fondsports', 'array', 'P' ) );
	$members_base ['fondmusic'] = ArrToStr ( MoogetGPC ( 'fondmusics', 'array', 'P' ) );
	$members_base ['fondprogram'] = ArrToStr ( MoogetGPC ( 'fondprograms', 'array', 'P' ) );
	
	$memberssearch = array ();
	foreach ( $members_search as $k => $v ) {
		if ($v) {
			$memberssearch [$k] = $v;
		}
	}
	$membersbase = array ();
	foreach ( $members_base as $k => $v ) {
		if ($v) {
			$membersbase [$k] = $v;
		}
	}
	$result ['search'] = $memberssearch;

	$result ['base'] = $membersbase;

	
	return $result;
}

// note 注册第一步
function register_stepone() {
	global $_MooClass, $user_arr, $userid,$memcached, $_MooCookie;

	MooPlugins ( 'ipdata' );
	$address = convertIp ( GetIP () );
	
	include MooTemplate ( 'public/register_stepone', 'module' );
}

//note 注册的第一步之后，第二步之前
function register_steptel() {
	global $_MooClass, $dbTablePre,$_MooCookie;
    
	$uid=$_MooCookie['uid'];
	$password=$_MooCookie['password'];
	$telphone=$_MooCookie['telphone'];
	
	$telphonecheck = MooGetGPC ( 'telphonecheck', 'string' );
	if (MooSubmit ( 'register' )) {

		$telcheck = MoogetGPC ( 'telcheck', 'string' );
		$truetelphone = MoogetGPC ( 'truetelphone', 'string' );
		$telphonemack = md5 ( md5 ( MoogetGPC ( 'telphonemack', 'string', 'P' ) ) );
		$web_rand = $_MooCookie ['rand'];

		if ($telcheck != '' && ($web_rand == $telphonemack)) {

			certification ( "telphone", $truetelphone, $uid );
			$md5 = md5 ( $uid . MOOPHP_AUTHKEY );
			
			
			$apiurl = "http://tg.zhenaiyisheng.cc/hongniang/hongniang_telephone_import.php?uid=" . $uid . "&md5=" . $md5;
			if (@file_get_contents ( $apiurl ) != "ok") {
				$dateline = time ();
				$sql = "INSERT INTO {$GLOBALS['dbTablePre']}do_url VALUES('','{$apiurl}','{$dateline}',0,0)";
				$GLOBALS ['_MooClass'] ['MooMySQL']->query ( $sql );
			}
			
			
			
			if (MOOPHP_ALLOW_FASTDB) {
				MooFastdbUpdate ( 'members_search', 'uid', $uid );
			}
			
			
			MooSetCookie ( 'auth', MooAuthCode ( "$uid\t$password", 'ENCODE' ), 86400 * 7 );

			$message = "恭喜您，您已经通过手机验证！进入下一步！";
			MooMessage ( $message, 'index.php?n=register&h=steptwo' );

		} elseif (MoogetGPC ( 'telphonemack', 'string', 'P' ) == '' && $telcheck != '') {

			MooMessage ( "请填写您收到的手机验证码！", "index.php?n=register&h=steptel" );
		} else if ($telcheck != '' && ($web_rand != $telphonemack)) {
	
			$message = "您输入的短信验证码不正确";

			MooMessage ( $message, 'index.php?n=register&h=steptel' );
		}
		
	
	}

	include MooTemplate ( 'public/register_steptel', 'module' );
}

// note 注册第二步
function register_steptwo() {
	global $_MooClass, $dbTablePre, $uid, $user_arr, $_MooCookie;
	$userinfo = $user_arr;
	$password = isset ( $_MooCookie ['password2'] ) ? $_MooCookie ['password2'] : '';
	MooSetCookie ( 'password2', '' );
	
	
	
			
	$userchoice = MooGetData ( 'members_choice', 'uid', $uid );
	
	$telphonecheck = MooGetGPC ( 'telphonecheck', 'string' );
	if (MooSubmit ( 'register_submittwo' )) {
		// note members表字段
		$members_search ['nickname'] = safeFilter ( rtrim ( MoogetGPC ( 'nickname', 'string', 'P' ) ) );
		
		$members_search ['marriage'] = MoogetGPC ( 'marriage1', 'integer', 'P' ); // update1_arr
		$members_search ['height'] = MoogetGPC ( 'height', 'integer', 'P' );
		$members_search ['salary'] = MoogetGPC ( 'salary', 'integer', 'P' );
		$members_search ['education'] = MoogetGPC ( 'education1', 'integer', 'P' );
		$members_search ['children'] = MoogetGPC ( 'children1', 'integer', 'P' );
		$members_search ['house'] = MoogetGPC ( 'house', 'integer', 'P' );
		$members_search ['updatetime'] = time ();
		$members_base ['oldsex'] = MoogetGPC ( 'oldsex', 'integer', 'P' );
		
		$gender = $_MooClass ['MooMySQL']->getOne ( "select gender from {$dbTablePre}members_search WHERE uid='$uid'",true );
		if ($gender ['gender'] == 0) {
			$members_choice ['gender'] = 1;
		} else {
			$members_choice ['gender'] = 0;
		}
		$members_choice ['age1'] = MoogetGPC ( 'age1', 'integer', 'P' ); // $update2_arr
		$members_choice ['age2'] = MoogetGPC ( 'age2', 'integer', 'P' );
		$members_choice ['workprovince'] = MoogetGPC ( 'workProvince', 'integer', 'P' );
		$members_choice ['workCity'] = MoogetGPC ( 'workCity', 'integer', 'P' );
		
		if (in_array ( $members_choice ['workprovince'], array (10101201, 10101002 ) )) {
			$members_choice ['workcity'] = $members_choice ['workprovince'];
			$members_choice ['workprovince'] = 10101000;
		}
		
		$members_choice ['marriage'] = MoogetGPC ( 'marriage2', 'integer', 'P' );
		$members_choice ['education'] = MoogetGPC ( 'education2', 'integer', 'P' );
		$members_choice ['children'] = MoogetGPC ( 'children2', 'integer', 'P' );
		$members_choice ['salary'] = MoogetGPC ( 'salary1', 'integer', 'P' );
		$members_choice ['height1'] = MoogetGPC ( 'height1', 'integer', 'P' );
		$members_choice ['height2'] = MoogetGPC ( 'height2', 'integer', 'P' );
		$members_choice ['hasphoto'] = MoogetGPC ( 'hasphoto', 'integer', 'P' );
		$members_choice ['nature'] = MoogetGPC ( 'nature2', 'integer', 'P' );
		$members_choice ['body'] = MoogetGPC ( 'body2', 'integer', 'P' );
		$members_choice ['weight1'] = MoogetGPC ( 'weight1', 'integer', 'P' );
		$members_choice ['weight2'] = MoogetGPC ( 'weight2', 'integer', 'P' );
		$members_choice ['occupation'] = MoogetGPC ( 'occupation2', 'integer', 'P' );
		$members_choice ['nation'] = MoogetGPC ( 'stock2', 'integer', 'P' );
		$members_choice ['hometownprovince'] = MoogetGPC ( 'hometownProvince2', 'integer', 'P' );
		$members_choice ['hometowncity'] = MoogetGPC ( 'hometownCity2', 'integer', 'P' );
		$members_choice ['updatetime'] = time ();
		if (in_array ( $members_choice ['hometownprovince'], array (10101201, 10101002 ) )) {
			$members_choice ['hometowncity'] = $members_choice ['hometownprovince'];
			$members_choice ['hometownprovince'] = 10101000;
		}
		
		$members_choice ['wantchildren'] = MoogetGPC ( 'wantchildren2', 'integer', 'P' );
		$members_choice ['smoking'] = MoogetGPC ( 'issmoking', 'integer', 'P' );
		$members_choice ['drinking'] = MoogetGPC ( 'isdrinking', 'integer', 'P' );
		
		// *********内心独白处理**********//
		$members_introduce ['introduce'] = MoogetGPC ( 'introduce', 'string', 'P' ); // $update2_arr
		$members_introduce ['introduce_check'] = safeFilter ( rtrim ( MoogetGPC ( 'introduce', 'string', 'P' ) ) );
	
		$telcheck = MoogetGPC ( 'telcheck', 'string' );
		$truetelphone = MoogetGPC ( 'truetelphone', 'string' );
		$telphonemack = md5 ( md5 ( MoogetGPC ( 'telphonemack', 'string', 'P' ) ) );
		$web_rand = $_MooCookie ['rand'];
		
		
		// wxmtest********************************************
		// SQL条件
		$where_arr = array ('uid' => $uid );
		if ($members_search || $members_choice) {
			// 会员基本信息
			//if ($members_search) {
				// 判断昵称
				if (rtrim ( $members_search ['nickname'] ) != '') {
					if (preg_match ( '/^((1[345]\d{9})|(18[024-9]\d{8})|(010-?\d{8})|(02)[012345789]-?\d{8}|(0[3-9]\d{2,2}-?\d{7,8})|(.*@.*))$/', $members_search ['nickname'] )) {
						MooMessage ( "昵称不符合规范！", "index.php?n=register&h=steptwo" );
					}
					// note 昵称截取
					$members_search ['nickname'] = MooCutstr ( $members_search ['nickname'], 12, $dot = '' );
					
					// 更新数据
					updatetable ( 'members_search', $members_search, $where_arr );
					updatetable ( 'members_base', $members_base, $where_arr );
					if (MOOPHP_ALLOW_FASTDB) {
						MooFastdbUpdate ( 'members_search', 'uid', $uid, $members_search );
						MooFastdbUpdate ( 'members_base', 'uid', $uid, $members_base );
					}
					// searchApi("members_man
				    // members_women")->updateAttr(array('nickname','marriage','height','salary','education','children','house'),array(9888888888=>$search));
				
				} else {
					MooMessage ( "昵称必填！", "index.php?n=register&h=steptwo" );
				}
			//}
			
			if ($members_choice) {
				updatetable ( 'members_choice', $members_choice, $where_arr );
				if (MOOPHP_ALLOW_FASTDB) {
					MooFastdbUpdate ( 'members_choice', 'uid', $uid, $members_choice );
				}
			
			}
			
			if ($members_introduce) {
				// 内心独白必填
				// if($update2_arr['introduce_check'] != ''){
				
				$members_introduce ['introduce'] = '';
				$members_introduce ['introduce_pass'] = '0';
				updatetable ( 'members_introduce', $members_introduce, $where_arr );
				if (MOOPHP_ALLOW_FASTDB) {
					MooFastdbUpdate ( 'members_introduce', 'uid', $uid, $members_introduce );
				}
				// }else{
				// MooMessage("内心独白必填！", "index.php?n=register&h=steptwo");
				// }
			}

			MooMessage ( "进入下一步！", "index.php?n=register&h=stepthree" );
		}
	}

	include MooTemplate ( 'public/register_steptwo', 'module' );
}

// note 注册第三步
function register_stepthree() {
	global $_MooClass, $dbTablePre, $uid, $user_arr;
	/* if (! certification_ok ( $uid )) {
		MooMessage ( "请验证手机号码！", "index.php?n=register&h=steptwo" );
	} */

	$userinfo = MooMembersData ( $uid );
	if (MooSubmit ( 'register_submitthree' )) {
		$search_base3 = getMemberfield ();
		
		$members_search3 = $search_base3 ["search"];
		$members_base3 = $search_base3 ["base"];
		
		$where_arr = array ('uid' => $uid );
		if (isset ( $members_search3 )) {
			updatetable ( 'members_search', $members_search3, $where_arr );
			if (MOOPHP_ALLOW_FASTDB) {
				MooFastdbUpdate ( 'members_search', 'uid', $uid, $members_search3 );
			}
		
		}
		if (isset ( $members_base3 )) {
			updatetable ( 'members_base', $members_base3, $where_arr );
			if (MOOPHP_ALLOW_FASTDB) {
				MooFastdbUpdate ( 'members_base', 'uid', $uid, $members_base3 );
			}
		}
		MooMessage ( "进入下一步！", "index.php?n=register&h=stepfour" );
	}
	include MooTemplate ( 'public/register_stepthree', 'module' );
}

// note 注册第四步
function register_stepfour() {
	global $_MooClass, $dbTablePre, $uid, $user_arr;
	
	
	$userinfo = MooMembersData ( $uid );
	if (MooSubmit ( 'register_submitfour' )) {
		$search_base4 = getMemberfield ();
		
		$members_search4 = $search_base4 ["search"];
		$members_base4 = $search_base4 ["base"];
		
		$where_arr = array ('uid' => $uid );
		if ($members_search4) {
			updatetable ( 'members_search', $members_search4, $where_arr );
			if (MOOPHP_ALLOW_FASTDB) {
				MooFastdbUpdate ( 'members_search', 'uid', $uid, $members_search4 );
			}
		
		}
		if ($members_base4) {
			updatetable ( 'members_base', $members_base4, $where_arr );
			if (MOOPHP_ALLOW_FASTDB) {
				MooFastdbUpdate ( 'members_base', 'uid', $uid, $members_base4 );
			}
		}
		MooMessage ( "进入下一步！", "index.php?n=register&h=stepfive" );
	}
	include MooTemplate ( 'public/register_stepfour', 'module' );
}

// note 注册第五步
function register_stepfive() {
	global $_MooClass, $dbTablePre, $uid, $user_arr;
	
	
	$userinfo = MooMembersData ( $uid );
	if (MooSubmit ( 'register_submitfive' )) {
		$search_base5 = getMemberfield ();
		$members_search5 = isset ( $search_base5 ["search"] ) ? $search_base5 ["search"] : array ();
		$members_base5 = isset ( $search_base5 ["base"] ) ? $search_base5 ["base"] : array ();
	
		
		$where_arr = array ('uid' => $uid );
		
		if (! empty ( $members_search5 )) {
			updatetable ( 'members_search', $members_search5, $where_arr );
			if (MOOPHP_ALLOW_FASTDB) {
				MooFastdbUpdate ( 'members_search', 'uid', $uid, $members_search5 );
			}
		}
		if (! empty ( $members_base5 )) {
			updatetable ( 'members_base', $members_base5, $where_arr );
			if (MOOPHP_ALLOW_FASTDB) {
				MooFastdbUpdate ( 'members_base', 'uid', $uid, $members_base5 );
			}
		}
		
		MooMessage ( "您已经完成了您的资料填写，请尽快进入我的帐户完成您的认证！", "index.php?n=service" );
	}
	include MooTemplate ( 'public/register_stepfive', 'module' );
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

/**
 * 该用户手机号码是否验证过
 * 
 * @param $uid integer       	
 * @return true 或者fase
 */
function certification_ok($uid) {
	global $_MooClass, $dbTablePre;
	$sql = "select telphone from {$dbTablePre}certification where uid='$uid' ";
	$list = $_MooClass ['MooMySQL']->getAll ( $sql,0,0,0,true);
	if (! empty ( $list )) {
		return true;
	} else {
		return false;
	}

}

// 注册服务条款
function register_serverrule() {
	include MooTemplate ( 'public/register_serverrule', 'module' );
}

// 看别人怎么写内心独白
function register_incenter() {
	include MooTemplate ( 'public/register_incenter', 'module' );
}

// 注册验证码
function reg_code() {
	// 验证码
	$img = MooAutoLoad ( 'MooSeccode' );
	$img->outCodeImage ( 125, 30, 4 );
}
/**
 * ************************************* 控制层(C)
 * ***************************************
 */

$h = MooGetGPC ( 'h', 'string' );
$h = in_array ( $h, array ('stepone','step_one','steptel', 'steptwo', 'stepthree', 'stepfour', 'stepfive', 'stepsix', 'validateemail', 'seccode', 'incenter', 'serverrule' ) ) ? $h : 'stepone';



$_MooCookie ['auth'] = '';

$v = MooAutoLoad ( 'MooValidation' );

switch ($h) {
	case "stepone" :
	    
		if ($_SERVER ['REQUEST_METHOD'] == 'POST') {
			$istuiguang = MooGetGPC ( 'istuiguang', 'string' );
			$friendprovince = '';
			
			$username = MooGetGPC ( 'username', 'string');
			$password = MooGetGPC ( 'password', 'string');
			if(empty($password) || !isset($password)){
			    $str = '1234567890abcdefghijklmnopqrstuvwxyz';
                $password=$str[rand(0,35)].$str[rand(0,35)].$str[rand(0,35)].$str[rand(0,35)].$str[rand(0,35)].$str[rand(0,35)];
			}
			$password_= $password;
			//$password2 = MooGetGPC ( 'password2', 'string' );
			$gender = MooGetGPC ( 'gender', 'integer' );
			$telphone = MooGetGPC ( 'telphone', 'string' );

			$birthyear = MooGetGPC ( 'year', 'integer' );
			$birthmonth = MooGetGPC ( 'month', 'integer' );
			$birthday = MooGetGPC ( 'day', 'integer' );
			$workprovince = MooGetGPC ( 'workprovincereg', 'integer' );
			$workcity = MooGetGPC ( 'workcity', 'integer' );
			
			$marriage = MooGetGPC('marriage','integer');
			$marriage =isset($marriage)?$marriage:1;
			$education = MooGetGPC('education','integer');
			$salary = MooGetGPC('salary','integer');
    
			
			if (empty ( $istuiguang )) {
				/* $seccode1 = strtolower ( MooGetGPC ( 'seccode', 'string', 'P' ) );
				$seccode2 = MooGetGPC ( 'seccode', 'string', 'C' );
				$session_seccode = $memcached->get ( $seccode2 );

				if ($seccode1 != $session_seccode) {
					MooMessage ( "验证码填写不正确，请确认。", "index.php?n=register&h=stepone", '', '1', 1 );
				} 
				
				$memcached->delete($seccode2);
				*/
				
				// 期望交友所在地区
				if(MooGetGPC('friendprovince','string','P')){
					foreach ( $_POST ['friendprovince'] as $key => $val ) {
						if (in_array ( $val, array (10101201, 10101002 ) )) {
							$_POST ['friendcity'] [$key] = $val;
							$val = 10101000;
						}
						$friend_area [] [$val] = $_POST ['friendcity'] [$key];
					}
					$friendprovince = serialize ( $friend_area );
				}
			}
			
			if (in_array ( $workprovince, array (10101201, 10101002 ) )) {
				// note 修正广东省深圳和广州的区域查询
				$workcity = $workprovince;
				$workprovince = 10101000;
			}
			        
			$agree = MooGetGPC ( 'agree', 'integer' );
			$ip = GetIP ();
			

			$mustphone = MooGetGPC ( 'mustphone', 'integer' );
			$telphonecheck = MooGetGPC ( 'telphonecheck', 'integer' );
			$currentprovince = MooGetGPC ( 'currentprovince', 'integer' ); // 目前所在地省
			$currentcity = MooGetGPC ( 'currentcity', 'integer' ); // 目前所在地市
			
			if (in_array ( $currentprovince, array (10101201, 10101002 ) )) {
				// note 修正广东省深圳和广州的区域查询
				$currentcity = $currentprovince;
				$currentprovince = 10101000;
			}
			
			$hometownprovince = MooGetGPC ( 'hometownprovince', 'integer' ); // 籍贯所在地省
			$hometowncity = MooGetGPC ( 'hometowncity', 'integer' ); // 籍贯所在地市
			
			if (in_array ( $hometownprovince, array (10101201, 10101002 ) )) {
				// note 修正广东省深圳和广州的区域查询 
				$hometowncity = $hometownprovince;
				$hometownprovince = 10101000;
			}
			// note 同意服务条款和隐私政策 验证
			if ($agree != 1) {
				MooMessage ( "未同意服务条款和隐私政策", "index.php?n=register&h=stepone", '', '1', 1 );
			}
			
			//if (empty($istuiguang)) {
			
				// 来源，发送到统计用
				$where_from = isset ( $_MooCookie ['where_from'] ) ? urlencode($_MooCookie ['where_from']) : '';
			
	
				$currentprovince=empty($currentprovince)?$workprovince:$currentprovince;
				$hometownprovince=empty($hometownprovince)?$workprovince:$hometownprovince;
				
				// note 出生日期和工作地区验证
				//if ($birthyear != 0 && $birthmonth != 0 && $birthday != 0 && $workprovince != 0 && $currentprovince != 0 && $hometownprovince != 0) {
				$website = isset($_MooCookie ['website'])?$_MooCookie ['website']:'';
				$puid = isset($_MooCookie ['puid'])?$_MooCookie ['puid']:'';
				$wherefrom = isset($_MooCookie ['w_from'])?$_MooCookie ['w_from']:'';
				$password = md5( $password );
				$birth = mktime ( 0, 0, 0, $birthmonth, $birthday, $birthyear );
				//$birth = strtotime("$birthyear/$birthmonth/$birthday");
				//$birth = "$birthyear-$birthmonth-$birthday";
				
				$hometownprovince = $hometownprovince == '0' ? '0' : $hometownprovince;
				$hometowncity = $hometowncity == '0' ? '0' : $hometowncity;
				$currentprovince = $currentprovince == '0' ? '0' : $currentprovince;
				$currentcity = $currentcity == '0' ? '0' : $currentcity;
				$friendprovince = $friendprovince == '' ? 'a:3:{i:0;a:1:{i:0;s:2:"0";}i:1;a:1:{i:0;s:2:"0";}i:2;a:1:{i:0;s:2:"0";}}' : $friendprovince;
				$updatetime = time ();
				
				
				// 如果从推广过来修改此值
				$rf_url = parse_url ( $_SERVER ["HTTP_REFERER"] );
				
				
				
				if (strstr ( $_SERVER ["HTTP_REFERER"], "pop" ) !== false || strstr ( $_SERVER ["HTTP_REFERER"], "reg" ) !== false) {
					$where_from = urlencode ( $_SERVER ["HTTP_REFERER"] );
				} else {
					$where_from = urlencode ( $where_from );
				}
				
				
				$rs_=array();
			    $sql="select uid from web_members_search where telphone='{$telphone}'";
				$rs_=$_MooClass ['MooMySQL']->getOne($sql);
				if(!empty($rs_['uid'])){
				     MooMessage ( '此手机号已经被注册过！', 'index.php?n=register', '01' );
					 return;
				}
				
				//事务开始
				$_MooClass ['MooMySQL']->query ( "begin;");
				$_MooClass ['MooMySQL']->showError = false;
				$sql_res = array();//事务数组
				
				//$sql = "INSERT INTO {$dbTablePre}members_search SET username='" . $username . "', password='" . $password . "', regdate='" . $timestamp . "', gender='" . $gender . "',   telphone='" . $telphone . "', birthyear='" . $birthyear . "',  province='" . $workprovince . "', city='" . $workcity . "',hometownprovince='" . $hometownprovince . "',updatetime='" . $updatetime . "',hometowncity='" . $hometowncity . "'";
				$sql = "INSERT INTO {$dbTablePre}members_search SET username='{$username}', password='{$password}',marriage='{$marriage}',education='{$education}',salary='{$salary}', regdate='{$timestamp}', gender='{$gender}',  province='{$workprovince}', city='{$workcity}',  telphone='{$telphone}', birthyear='{$birthyear}',updatetime='{$updatetime}'";
				
				$sql_res[]=$_MooClass ['MooMySQL']->query ($sql);
				$uid = $_MooClass ['MooMySQL']->insertId ();
			
				
				
				if($sql_res[0] && $uid){
					//$sql2 = "REPLACE INTO {$dbTablePre}members_base SET uid='".$uid."', regip='" . $ip . "',birth='" . $birth ."', puid='" . $puid . "',website='" . $website . "', source='{$where_from}',currentprovince='" . $currentprovince . "',currentcity='" . $currentcity . "',friendprovince='" . $friendprovince . "'";
					$sql2 = "REPLACE INTO {$dbTablePre}members_base SET uid='".$uid."', regip='" . $ip ."',birth='" . $birth . "', puid='" . $puid . "',website='" . $website . "', source='{$where_from}'";
					
					$sql3 = "REPLACE INTO {$dbTablePre}members_login SET uid='".$uid."',lastip='".$ip."', lastvisit='" . $GLOBALS ['timestamp'] . "',last_login_time='{$timestamp}'";
					if ($callno)
						$sql2 .= ", callno='{$callno}'";
					$sql_res[] =  $_MooClass ['MooMySQL']->query ( $sql2 );
				
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
					MooMessage ( "您好！不好意思，注册失败，可能是网络故障，请尝试重新注册。", "index.php?n=register&h=stepone", '', '1', 1 );
				}else{
					$_MooClass ['MooMySQL']->query("commit");
					$_MooClass ['MooMySQL']->showError = true;
				}
				
				$ToAddress = $username;
				$ToSubject = '［真爱一生网］感谢您的注册';
				$ToBody = '尊敬的' . $ToAddress . '：<br>您好！您已经正式注册成为真爱一生网的会员用户，以下是您的注册信息：<br>用户名称：' . $ToAddress . '<br>用户密码:' . $password_ . ",建议您完善您的资料并尽快进行诚信认证，以便获得更高关注度，真爱一生网祝您早日找到自己的另一半。";
				
				// 插入后台扩展表，记录最后访问时间，最后登录ip
				$sql = "INSERT INTO {$GLOBALS['dbTablePre']}member_admininfo SET finally_ip='{$ip}',uid='{$uid}',real_lastvisit='{$GLOBALS['timestamp']}'";
				$sql_res[] = $_MooClass ['MooMySQL']->query ( $sql );
				
				sendMailByNow ( $ToAddress, $ToSubject, $ToBody );
				
				
				
				//MooSetCookie ( "auth", MooAuthCode ( "$username\t$password\t$uid", "ENCODE" ), 86400 * 7 );
				
				// 加鲜花
				if ($puid) {
					MooAddRose ( $puid, 3 );
				}
				
	
				
				//暂时测试屏蔽
				
				$is_phone = ! empty ( $telphone ) ? 1 : 0;
				$md5 = md5 ( $uid . $puid . 2 . $website . $ip . $where_from . MOOPHP_AUTHKEY );
				$apiurl = "http://tg.zhenaiyisheng.cc/hongniang/hongniang_import.php?ip=" . $ip . "&uid=" . $uid . "&puid=" . $puid . "&s_cid=2&website=" . $website . "&keyurl=" . $where_from . "&md5=" . $md5 . "&gender=" . $gender . "&phone=" . $telphone . "&is_phone=" . $is_phone . "&birthyear=" . $birthyear;
				if (file_get_contents ( $apiurl ) != "ok") {
				   
					$dateline = time ();
					$sql = "INSERT INTO {$GLOBALS['dbTablePre']}do_url VALUES('','{$apiurl}','{$dateline}',0,0)";
					$GLOBALS ['_MooClass'] ['MooMySQL']->query ( $sql );
				}
				
				
				
				
				$rand = rand ( 1621, 9654);
				MooSetCookie ( "rand_",  $rand , 3600 );
				MooSetCookie ( "rand", md5 ( md5 ( $rand ) ), 3600 );
				
				// 希希奥信息发送手机短信接口
				$re = SendMsg ( $telphone, "注册成功,您的ID" . $uid .  ",密码：".$password_.",验证码：" . $rand ,1);
				
				if($re){
				//if( trim($re) == '100' ){
					$time = time ();
					$content = "注册成功提醒";
					$sql_res[] =  $_MooClass ['MooMySQL']->query ( "INSERT INTO {$dbTablePre}smslog_sys (id,sid,uid,content,sendtime,type) values('','','$uid','$content','$time','注册成功提醒')" );
				}

				
				MooSetCookie ( 'uid', $uid,3600 );
				MooSetCookie ( 'password', $password,3600);
				MooSetCookie ( 'telphone', $telphone,3600);
				//MooSetCookie ( 'auth', MooAuthCode ( "$uid\t$password", 'ENCODE' ), 86400 * 7 );
				MooSetCookie ( "where_from", "" );
				MooSetCookie ( "puid", "" );
				MooSetCookie ( "website", "" );
				
			
				MooSetCookie ( 'password2', $password_, 3600 );
				echo "<script>location.href='index.php?n=register&h=steptel'</script>";
			//}	
			
		} else {
			
			if ($userid) {
				MooMessage ( '您现在是登陆状态！我们将返回用户中心', 'index.php?n=service', '03' );
			}
			
			/* $seccode = md5 ( uniqid ( rand (), true ) );
			MooSetCookie ( 'seccode', $seccode, 3600, '' );
			$session_seccode = $memcached->set ( $seccode, '', 0, 600 ); */
			
			register_stepone();
		}
		break;
	case "step_one" :
	    
		if ($_SERVER ['REQUEST_METHOD'] == 'POST') {
			$istuiguang = MooGetGPC ( 'istuiguang', 'string' ,'P');
			
			$username = MooGetGPC ( 'username', 'string','P');
			$password = MooGetGPC ( 'password', 'string','P');
		
			$password2 = MooGetGPC ( 'password2', 'string','P' );
			$gender = MooGetGPC ( 'gender', 'integer','P' );
			$telphone = MooGetGPC ( 'telphone', 'string','P' );
            $birthyear = MooGetGPC ( 'birthyear', 'integer','P' );
			        
			$agree = MooGetGPC ( 'agree', 'integer','P' );
			$ip = GetIP ();

			
			// 来源，发送到统计用
			$where_from = isset ( $_MooCookie ['where_from'] ) ? urlencode($_MooCookie ['where_from']) : '';


			$website = isset($_MooCookie ['website'])?$_MooCookie ['website']:'';
			$puid = isset($_MooCookie ['puid'])?$_MooCookie ['puid']:'';
			$password = md5( $password );

			$updatetime = time ();
			
			
			// 如果从推广过来修改此值
			$rf_url = parse_url ( $_SERVER ["HTTP_REFERER"] );

			if (strstr ( $_SERVER ["HTTP_REFERER"], "pop" ) !== false || strstr ( $_SERVER ["HTTP_REFERER"], "reg" ) !== false) {
				$where_from = urlencode ( $_SERVER ["HTTP_REFERER"] );
			} else {
				$where_from = urlencode ( $where_from );
			}
			
			
			$rs_=array();
			$telArr=array('13856900659');
			if(!in_array($telphone,$telArr)){
				$sql="select uid from web_members_search where telphone='{$telphone}'";
				$rs_=$_MooClass ['MooMySQL']->getOne($sql);
				if(!empty($rs_['uid'])){
					 MooMessage ( '此手机号已经被注册过！', 'index.php?n=register', '01' );
					 return;
				}
			}
			
			//事务开始
			$_MooClass ['MooMySQL']->query ( "begin;");
			$_MooClass ['MooMySQL']->showError = false;
			$sql_res = array();//事务数组
			
			$sql = "INSERT INTO {$dbTablePre}members_search SET  password='{$password}',gender='{$gender}',  telphone='{$telphone}', regdate='{$timestamp}', updatetime='{$updatetime}',birthyear='{$birthyear}'";
			
			$sql_res[]=$_MooClass ['MooMySQL']->query ($sql);
			$uid = $_MooClass ['MooMySQL']->insertId ();
		
			
			
			if($sql_res[0] && $uid){
				$sql2 = "REPLACE INTO {$dbTablePre}members_base SET uid='".$uid."', regip='" . $ip ."', puid='" . $puid . "',website='" . $website . "', source='{$where_from}'";
				$sql_res[] =  $_MooClass ['MooMySQL']->query ( $sql2 );
				
				$sql3 = "REPLACE INTO {$dbTablePre}members_login SET uid='".$uid."',lastip='".$ip."', lastvisit='{$timestamp}',last_login_time='{$timestamp}'";
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
				MooMessage ( "您好！不好意思，注册失败，可能是网络故障，请尝试重新注册。", "index.php?n=register&h=stepone", '', '1', 1 );
			}else{
				$_MooClass ['MooMySQL']->query("commit");
				$_MooClass ['MooMySQL']->showError = true;
			}
			
			$sql = " replace into web_certification set `uid`='{$uid}',`telphone`='{$telphone}'";
			$_MooClass ['MooMySQL']->query($sql);
			reset_integrity ( $uid );

			

			if (MOOPHP_ALLOW_FASTDB) {
				MooFastdbUpdate ( 'members_search', 'uid', $uid );
			}
			
			
			$sql = "INSERT INTO {$GLOBALS['dbTablePre']}member_admininfo SET finally_ip='{$ip}',uid='{$uid}',real_lastvisit='{$GLOBALS['timestamp']}'";
		    $_MooClass ['MooMySQL']->query ( $sql );
			
	
			// 朋友推荐过来的，加鲜花
			if($puid) MooAddRose ( $puid, 3 );
			
			

			
			//暂时测试屏蔽
			
			$is_phone = ! empty ( $telphone ) ? 1 : 0;
			$md5 = md5 ( $uid . $puid . 2 . $website . $ip . $where_from . MOOPHP_AUTHKEY );
			$apiurl = "http://tg.zhenaiyisheng.cc/hongniang/hongniang_import.php?ip=" . $ip . "&uid=" . $uid . "&puid=" . $puid . "&s_cid=2&website=" . $website . "&keyurl=" . $where_from . "&md5=" . $md5 . "&gender=" . $gender . "&phone=" . $telphone . "&is_phone=" . $is_phone . "&birthyear=" . $birthyear;
			if (file_get_contents ( $apiurl ) != "ok") {
			   
				$dateline = time ();
				$sql = "INSERT INTO {$GLOBALS['dbTablePre']}do_url VALUES('','{$apiurl}','{$dateline}',0,0)";
				$GLOBALS ['_MooClass'] ['MooMySQL']->query ( $sql );
			}
			
			
			$md5 = md5 ( $uid . MOOPHP_AUTHKEY );	
			$apiurl = "http://tg.zhenaiyisheng.cc/hongniang/hongniang_telephone_import.php?uid=" . $uid . "&md5=" . $md5;
			if (@file_get_contents ( $apiurl ) != "ok") {
				$dateline = time ();
				$sql = "INSERT INTO web_do_url VALUES('','{$apiurl}','{$dateline}',0,0)";
				$GLOBALS ['_MooClass'] ['MooMySQL']->query ( $sql );
			}
			
			
		
			
			$time = time ();
			$content = "注册成功提醒";
			$sql_res[] =  $_MooClass ['MooMySQL']->query ( "INSERT INTO {$dbTablePre}smslog_sys (id,sid,uid,content,sendtime,type) values('','','$uid','$content','$time','注册成功提醒')" );
			

			
			MooSetCookie ( 'uid', $uid,3600 );
			MooSetCookie ( 'password', $password,3600);
			MooSetCookie ( 'telphone', $telphone,3600);
			MooSetCookie ( 'auth', MooAuthCode ( "$uid\t$password", 'ENCODE' ), 86400*7);
			MooSetCookie ( "where_from", "" );
			MooSetCookie ( "puid", "" );
			MooSetCookie ( "website", "" );

			//echo "<script>location.href='index.php?n=register&h=steptwo'</script>";
		    
			$message = "恭喜您，您已经注册成功！祝您在真爱一生网找到您的一生真爱！";
			MooMessage ( $message, 'index.php?n=service' );
			
		} else {
			if ($userid) {
				MooMessage ( '您现在是登陆状态！我们将返回用户中心', 'index.php?n=service', '03' );
			}
			register_stepone();
		}
		break;
	case "steptel":
		register_steptel ();
	    break;
	case "steptwo" :
		if (! $uid) exit( "Not login!" );
		register_steptwo ();
		break;
	
	case "stepthree" :
		if (! $uid) {exit ( "Not login!" );}
		register_stepthree ();
		break;
	
	case "stepfour" :
		if (! $uid) { exit ( "Not login!" );}
		register_stepfour ();
		break;
	
	case "stepfive" :
		if (! $uid) {exit ( "Not login!" );}
		register_stepfive ();
		break;
	
	case "validateemail" :
		require_once "validateemail.php";
		break;
	case "seccode" :
		reg_code ();
		break;
	case "incenter" : // 看别人怎么写内心独白
		register_incenter ();
		break;
	case "serverrule" : // 注册服务条款
		register_serverrule ();
		break;
	default:
		register_stepone();
		break;
}

?>
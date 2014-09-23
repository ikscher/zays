<?PHP
//验证手机号码
function reg_checkmack(){
	global $_MooCookie;;
	$mack = MooGetGPC('mack','integer','G');
	$telphonemack = md5(md5($mack));
	$web_rand = $_MooCookie['rand'];
	$telphonemack == $web_rand?exit('ok'):exit('errors');

}

//查询该手机号码 是否被他人验证过
function reg_checktel(){
	global $_MooClass,$dbTablePre;
	$tel_val = MooGetGPC('tel_val','string');
    $tel_arr = array('13856900659');
	if(in_array($tel_val,$tel_arr)){
		echo '';
	}else{
		$tel = $_MooClass['MooMySQL']->getOne("SELECT count(1) as a FROM {$dbTablePre}members_search WHERE telphone = '{$tel_val}'",true);
		if($tel && $tel['a'] >= 1){echo '该号码已被其他用户绑定';exit;}else {echo '';exit;}
	}
}


//获取手机验证码
function reg_truetelphone(){
	global $_MooCookie,$_MooClass,$dbTablePre;
	//if($_MooCookie['sendtimes']) return;
	//MooSetCookie( "sendtimes",1 );
	
	$rand = rand(3621,9654);
	MooSetCookie("rand",md5(md5($rand)));
	$truetelphone_val = MooGetGPC('truetelphone_val','string','G');
	$all_val = explode(',',$truetelphone_val);
	$uid = $all_val[0];
	$telphone = $all_val[1];
	$password2 = $all_val[2];
	$time = time();
	$time_cookie = isset($_MooCookie['time']) ? $_MooCookie['time'] : 0;
	if($time - $time_cookie >= 60){
		MooSetCookie('time',$time,time()+3600*24*8);
		//希希奥信息发送手机短信接口
		$re=SendMsg($telphone,"会员ID".$uid .",您的短信验证码：".$rand,1);
		if($re){
		//if( trim($re) == '100' ){
			
			$_MooClass['MooMySQL']->query("INSERT INTO {$dbTablePre}smslog_sys (id,sid,uid,content,sendtime,type) values('','','$uid','注册验证码','$time','注册验证码')");
		}
	}
}


/*
    获取手机验证码
    2014-06-08
*/
function getVcode(){
	global $_MooCookie,$_MooClass,$dbTablePre;
	$rand = rand(1621,9699);
	//MooSetCookie( "mobilevcode_",$rand );
	MooSetCookie("mobilevcode",md5(md5($rand)));
	$telphone = MooGetGPC('telphone','string','G');

	$time = time();
	$time_cookie = isset($_MooCookie['vtime']) ? $_MooCookie['vtime'] : 0;
	if($time - $time_cookie >= 60){
		MooSetCookie('vtime',$time,time()+3600*24*8);
		//手机短信接口
		SendMsg($telphone,"您的短信验证码：".$rand,1);
	}
}

/*
    验证手机号码
    2014-06-08
*/
function setVcode(){
	global $_MooCookie;;
	$mack = MooGetGPC('mack','integer','G');
	$telphonemack = md5(md5($mack));
	$mobilevcode = $_MooCookie['mobilevcode'];
	$telphonemack == $mobilevcode?exit('ok'):exit('errors');
}


//读取验证码是否填写正确
function reg_seccode(){
	global $memcached;
	$seccode1 = strtolower(MooGetGPC('seccode','string','G'));
	$seccode2 = MooGetGPC('seccode','string','C');
	$session_seccode = $memcached->get($seccode2);
	//echo $session_seccode;
	//if($seccode == ''){
	//	echo '1';
	//}elseif($seccode == ''){
	//	echo '0';
	//	exit;
	//}elseif($session_seccode != $seccode){
		
	if($session_seccode != $seccode1){
		echo '2';
	}else if($session_seccode != '' && $seccode1 && $session_seccode == $seccode1){
		echo '1';	
	}
}

//教您怎么写内心独白
function reg_text(){
	$vals = MooGetGPC('vals','integer','G');
	include MooTemplate('public/reg_text', 'module');
}

//修改手机号码
function reg_modifytel(){
	global $userid,$dbTablePre,$_MooClass;
	$tel_val = MooGetGPC('tel_val','string','G');
	$sql ="update {$dbTablePre}members_search set telphone='{$tel_val}' where uid='{$userid}'";
	if($_MooClass['MooMySQL']->query($sql)){
		$tel['telphone'] = $tel_val;
		if(MooFastdbUpdate('members_search','uid',$userid,$tel)){
			echo 'ok';
		}
	}
}

//***************************************控制层(C)************************************************//
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // 过去的时间

$h = MooGetGPC('h','string');
switch ($h){
	case 'checkmack': reg_checkmack();break;
	case 'checktel': reg_checktel();break;
	case 'truetelphone':reg_truetelphone();break;
	case 'seccode':reg_seccode();break;
	case 'text':reg_text();break;
	case 'modifytel':reg_modifytel();break;
	case 'getVcode':getVcode();break;
	case 'setVcode':setVcode();break;
}
?>
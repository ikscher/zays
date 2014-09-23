<?PHP
//include "mondle/index/function.php";
//验证手机号码
function reg_checkmack(){
global $_MooCookie;;
$mack = MooGetGPC('mack','integer','G');
$telphonemack = md5(md5($mack));
$web_rand = $_MooCookie['rand'];
$telphonemack == $web_rand?exit('ok'):exit('errors');
/*	if($telphonemack == $web_rand){
		exit('ok');
	}else{
		exit('errors');
	}*/
}

//查询该手机号码 是否被他人验证过
function reg_checktel(){
global $_MooClass,$dbTablePre;
$tel_val = MooGetGPC('tel_val','string');
$tel_arr = array('15212412915','13866164340','13866770139','15209865789','15855160903','13965098471','13659140698','15867121348','15930210816','15001266073','18601183459','13856900659');
	if(in_array($tel_val,$tel_arr)){
		echo '';
	}else{
		$tel = $_MooClass['MooMySQL']->getOne("SELECT count(1) as a FROM {$dbTablePre}members_search WHERE telphone = '{$tel_val}'",true);
		if($tel['a'] >= 1){echo '该号码已被其他用户绑定';exit;}else {echo '';exit;}
	}
}


//获取手机验证码

function reg_truetelphone(){
	global $_MooCookie,$_MooClass,$dbTablePre;
	$rand = rand(362144,965421);
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
		if(SendMsg($telphone,"您已成功注册为真爱一生网会员ID:".$uid.",密码".$password2.",手机认证码".$rand.",本认证码两小时内有效。")){
			
			$_MooClass['MooMySQL']->query("INSERT INTO {$dbTablePre}smslog_sys (id,sid,uid,content,sendtime,type) values('','','$uid','注册验证码','$time','注册验证码')");
			}
		
	}else{
		echo 'Not login!';	
	}
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
}
?>
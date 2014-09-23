<?php
/*************************************** 逻辑层(M)/表现层(V) ****************************************/ 
//note 登录首页
function login_index() {
	global $_MooCookie;
	/*****设置回转的页面*****/
	$returnurl = MooGetGPC('returnurl','string','G')?MooGetGPC('returnurl','string','G'):MooGetGPC('back_url','string','G');
	/*****设置回转的页面*****/
	//记住的用户名
	$u_name = isset($_MooCookie['username'])?$_MooCookie['username']:'';
	
	include MooTemplate('public/login_index', 'module');

}
//note 找回密码首页
function login_password() {
	include MooTemplate('public/login_password', 'module');
}
//note 找回密码成功页面
function login_passwordok($name,$mail) {
	$nickname = $name;
	$usermail = $mail;
	include MooTemplate('public/login_passwordok', 'module');
}
//note 改密码获取数据
function findPwd($method){
	global $_MooClass,$dbTablePre,$_MooCookie,$userid;
	//note 只给用户3次机会
	if($method){
		$_MooCookie['backpwdnum'] = $_MooCookie['backpwdnum']?$_MooCookie['backpwdnum']:'';
		MooSetCookie('backpwdnum',($_MooCookie['backpwdnum'] + 1),85400);
		if($_MooCookie['backpwdnum'] >= 3){
			MooMessage('您今天操作次数过多，请明天再试','index.php','02');
		}
	}
	switch($method){
		case 1:
			$ToAddress = trim(MooGetGPC('email','string','P'));
			//查找用户表,enky修改表名
			$userMsg = $_MooClass['MooMySQL']->getOne("select uid,username,password from {$dbTablePre}members_search where username='$ToAddress'",true);
			if($userMsg){//有此用户
				//是否邮箱认证
				//$ifmail = $_MooClass['MooMySQL']->$_MooClass['MooMySQL']->getOne("select telphone from {$dbTablePre}certification where uid='{$userMsg['uid']}'");
				//没认证
				//if(!$ifmail['telphone']){
					$email = $userMsg['username'];
					$password = $userMsg['password'];
					$ToAddressMd5 = md5($ToAddress.'+'.$password);//email和密码的md5
					$QueryString = base64_encode($ToAddress . '|' . $ToAddressMd5 . '|' .time());//url后的查询字符串
					//$sql = "insert into ". $dbTablePre ."reset_password set username = '". $ToAddress ."'";
					$sql = "insert into ". $dbTablePre ."reset_password set username = '". $QueryString ."'";
					$_MooClass['MooMySQL']->query($sql);//记录数据库
				
					$ToSubject = '真爱一生网提示：修改您的密码';
					//note 发送邮件
					if($userMsg['nickname']){
						$ToBody = $userMsg['nickname'].'：您好！<br>';
					}else{
						$ToBody = 'ID为'.$userMsg['uid'].'会员：您好！<br>';
					}
					$ToBody .= "&nbsp;&nbsp;&nbsp;&nbsp;因您在真爱一生网使用了找回密码功能，如果您忘记密码，请点击以下链接到真爱一生网，修改您的密码。";
					$ToBody .= '<br>&nbsp;&nbsp;&nbsp;&nbsp;提示：请在24小时内登陆真爱一生网，并在登陆后将密码修改为您容易记住的密码,如果您没有操作，无需理会此邮件。';
					$ToBody .= "点击此链接修改密码：<a href='http://".MOOPHP_HOST."/index.php?n=myaccount&h=resetpwd&p=".$QueryString."'>http://".MOOPHP_HOST."/index.php?n=myaccount&h=resetpwd&p=".$QueryString."</a>";	
					if(sendMailByNow($ToAddress,$ToSubject,$ToBody)){
						MooMessage('修改密码地址已发送至邮箱，请尽快登录邮箱操作。','index.php');
					}else{
						MooMessage('数据操作失败，请重新找回密码','index.php?n=login&h=backpassword','01');
					}
				//}
			}else{
				MooMessage('无此邮箱的会员','index.php?n=login&h=backpassword','01');
			}
			break;
		case 2: 
			//$umail = MooGetGPC('umail','string','P');
			$phone = MooGetGPC('phone','string','P');
			//判断手机号码是否符合规范
			if(!preg_match('/^((1[35][\d]{9})|(18[4689][\d]{8}))$/',$phone)){
				MooMessage('您的手机号码不正确','index.php?n=login&h=backpassword','01');
			}else{
				//查找用户表
				$userMsg = $_MooClass['MooMySQL']->getOne("select m.telphone,m.uid,m.nickname from {$dbTablePre}members_search as m left join {$dbTablePre}certification as c on m.uid=c.uid where m.telphone='$phone' and m.is_lock = 1 limit 1",true);
				if(!$userMsg){
					MooMessage('无使用此手机号码或绑定不正确','index.php?n=login&h=backpassword','01');
				}elseif($userMsg['telphone'] == $phone){
					//改为新密码
					$newpwd = changePWD($userMsg['uid']);
					//发手机消息
					if($newpwd){
						$content = "您的新密码是：".$newpwd.",请妥善保管好您的帐号和密码!";
						//$re = siooSendMsg($phone,$content);//希希奥信息发送手机短信接口
						if(SendMsg($phone,$content,1)){
							$time=time();
							$_MooClass['MooMySQL']->query("INSERT INTO {$dbTablePre}smslog_sys (id,sid,uid,content,sendtime,type) values('','','$userid','重置密码','$time','重置密码')");
							}
						MooMessage('您好！新密码已发送至您的手机，转到登陆页面','index.php?n=login');
					}else{
						MooMessage('找回密码失败','index.php?n=login&h=backpassword','01');
					}
				}else{
					MooMessage('您的手机号码未通过验证，请用邮件方式取回密码','index.php?n=login&h=backpassword','01');
				}
			}
			break;
	}
}
//note 改密码操作
function changePWD($uid){
	global $_MooClass,$dbTablePre,$user_arr;
	//note 将作为新密码
	$newpwd = rand(100000,999999);
	
	
	
	//修改密码至表中
	if($_MooClass['MooMySQL']->query("update web_members_search set password='".md5($newpwd)."' where uid='$uid'")){
		if(MOOPHP_ALLOW_FASTDB){
			$new_user_arr = array();
			$new_user_arr['password'] = md5($newpwd);
			MooFastdbUpdate('members_search','uid',$uid,$new_user_arr);
		}
		return $newpwd;
	}else{
		return 0;
	}
}

//qq登录
function login_qq(){
	include MooTemplate('public/login_qq', 'module');
}
function qc_back(){
	include MooTemplate('public/qc_back', 'module');
}
/***************************************   控制层(C)   ****************************************/
$h=MooGetGPC('h','string','G');
if($h=='qc_back'){
	qc_back();
    exit;
}

if($userid){
   header("location:service.html");exit;
}

switch ($h) {
	case "login" :
		login_index();
		break;

	case "backpassword" :
		$backmehtod = MooGetGPC('backmethod','integer');
		//note 处理接收的邮箱
		if($backmehtod == 1 || $backmehtod == 2){
			findPwd($backmehtod);
		}
		login_password();
		break;
	case 'login_qq':
	    login_qq();
		break;

/*	case "backpasswordok" :
		login_passwordok();
		break;	
*/
	default : // print_r($_SERVER);
		login_index();
		break;
}
?>
<?php
/*
 * Created on 2009-10-26
 *
 * To change the template for this generated file go to*
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
//首页显示 推荐好友 链接

include "./module/{$name}/msn.class.php";
 
function index_index(){
 global $_MooClass,$dbTablePre,$uid,$user_arr;
 require MooTemplate('public/invite_link', 'module');
}
//导入邮箱联系人
function into(){
 global $_MooClass,$dbTablePre,$uid,$user_arr;
 if(isset($_POST['actio']) && $_POST['actio']=='1'){
    
	$username=MooGetGPC('username','string','P');
    $password=MooGetGPC('password','string','P');
	
    if($username&&$password){
		$msn2 = new MSN;
		$returned_emails = $msn2->getAddressList($username, $password);
		if( !$returned_emails ){
		    MooMessage("您的帐户里面没有要导入的联系人，您可以给朋友们发送邮件",'index.php?n=invite&h=into');
		}
        else{
			$date=date('YmdHis').rand(1111,9999);
			$sql="insert into {$dbTablePre}email values";
			foreach($returned_emails as $val){
				if($val[6] && $username != $val[6])
					$vals[]=" ('','".$val[6]."','".$date."')";
			}
			$into_count = count($vals);
			if($into_count){
				$vals=join(',',$vals);
				$sql.=$vals;
				$_MooClass['MooMySQL']->query($sql);
				MooMessage("成功导入".$into_count."个MSN好友。",'index.php?n=invite&h=into');
			}else{
				MooMessage("没有找到您的MSN好友，可能您的账号里面没有联系人。",'index.php?n=invite&h=into');
			}
		  //header("Location:index.php?n=invite&h=mail&sendid=".$date);
		}
    }
 }
 require MooTemplate('public/invite_into', 'module');


}
//发送邮件
function  sendto_m(){
	global $_MooClass,$dbTablePre,$uid,$user_arr;
	if(isset($_POST['actio']) && $_POST['actio']=='1'){
		$mailadd = safeFilter(MooGetGPC('mailadd','string','P'));
		$content = safeFilter(MooGetGPC('content','string','P'));
		$title   = safeFilter(MooGetGPC('title','string','P'));
		$sendid  = MooGetGPC('sendid','string','P');
		if($mailadd && $content && $title){
			$mailarr=explode(',',$mailadd);
			$date = '';
			if(!$sendid){
				$date=date('YmdHis').rand(1111,9999);
				$reg = "/^([a-z0-9\+_\-\.]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/";
				$vals = array();
				foreach($mailarr as $val){
					$val = trim($val);
					if( preg_match($reg,$val) ){
						$vals[]=" ('','".$val."','".$date."')";
					}
				}
				$vals=join(',',$vals);
				if(!empty($vals)){
					$sql="insert into {$dbTablePre}email values";
					$sql.= $vals;
					$_MooClass['MooMySQL']->query($sql);
				}
			} 
			$content.="您找到心中的TA了吗？如果没有，常来真爱一生网看看（http://www.zhenaiyisheng.cc/index.php?puid={$user_arr['uid']}），付出您的真心与真情，真爱一生必定为您牵线连理，让您轻松、快捷地找到知己好友 ，祝愿天下有情人终成眷属！
	您恋爱了吗?您结婚了吗?如果没有，就来真爱一生网看看（ http://www.zhenaiyisheng.cc/index.php?puid={$user_arr['uid']}）吧！";	
			MooSendMail($mailadd,$title,$content,$is_template = true,$sendid);
			MooMessage("感谢您对真爱一生网的支持",'index.php?n=invite&h=mail&sendid='.$date);
		}
		else{
			MooMessage("信息不完整！",'index.php?n=invite&h=mail');
		}
	}
	else{ 
		$sendid = trim(MooGetGPC('sendid','string','G'));
		$t = $mail_str = '';
		if( preg_match("/\d{18}/",$sendid)){
			$sql="select `email` from {$dbTablePre}email where sendid='$sendid' limit 10";
			$st=$_MooClass['MooMySQL']->getAll($sql); 
			if($st){				
				foreach($st as $val){
					 $mail_str .= $t.$val['email'];
					 $t = ',';
				}
			}
		}
		require MooTemplate('public/invite_mail', 'module');
	}
}



$h = MooGetGPC('h', 'string');
$back_url='index.php?'.$_SERVER['QUERY_STRING'];
if(!$uid) {header("location:login.html");} //&back_url=".urlencode($back_url)
switch ($h) {
	case "mail":
	  sendto_m();
		break;
	case "into":
	 	into();
		break;
	default:
	 	index_index();
		break;
}
?>

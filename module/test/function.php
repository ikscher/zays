<?php 
/*

支付方法文件

作者尤莹煌

修改 ts24 (2010-3-22)

*/


//改变状态及其他服务的操作
function order_paid($order_id){
  
	$pay=get_pay_one($order_id);
	 //购买服务,0:钻石会员,1:高级会员,2:城市之星
	$serviceArr = array(0=>'钻石会员',1=>'高级会员',2=>'城市之星');
	 
	if($pay['status']=="1"){
		return false;
	}else {
		update_pay($order_id);//更新状态防止快速刷新
		update_user_rank($pay['uid'],$pay['pay_service']);//更新用户相应的等级
		
		$type_name = $serviceArr[$pay['pay_service']];
		$content="恭喜您，您已成功升级为真爱一生网{$type_name}！您的真爱一生单号".$order_id."请等待审核。[真爱一生网]";
		if(!empty($pay['telphone'])){
			if(SendMsg($pay['telphone'],$content)){
				$time=time();
				$_MooClass['MooMySQL']->query("INSERT INTO {$dbTablePre}smslog_sys (id,sid,uid,content,sendtime,type) values('','',".$pay['uid'].",'$content','$time','升级')");
				}
		}

		MooSendMail($pay['username'],"真爱一生网会员升级",$content.'您可以咨询客服您的会员办理进度！',$is_template = true,$pay['uid']);
		 
		return true;  		 
	   
	 } 
}

//返回帐号参数信息
function get_payment($code){
   global $payment_code;

  return $payment_code[$code];
}


//取得异步和同步返回地址
function get_payurl($k,$code){
 // global $code;
  
	 switch($k){
	  
		   case "r":
		       $action="reurl&at=1";   
		   break;
		  
		   case "n":
		       $action="reurl&at=2";
		   break;
	   
	 }
	 
	//$site="http://www.zhenaiyisheng.cc/index.php?n=return&h=".$action."&code=".$code;
	 $site="http://www.zhenaiyisheng.cc/index.php?n=return&h=".$action."&code=".$code."&trade_status=TRADE_FINISHED";
  return $site;	  
}
//改变用户的等级权限
function update_user_rank($uid,$service){
	global $_MooClass,$dbTablePre;
	$bgtime=time();
	//$endtime=$bgtime+180*24*3600;
	$daymeb = date("t")<30?30:date("t");
	$endtime=$bgtime+$daymeb*24*3600;
	$sql=" update {$dbTablePre}members set s_cid=".$service.",bgtime='$bgtime',endtime='$endtime' where uid=$uid";
  
	$_MooClass['MooMySQL']->query($sql);
	if(MOOPHP_ALLOW_FASTDB){
		$value = array();
		$value['uid'] = $uid;
		MooFastdbUpdate('members','uid',$uid,$value);
	}
	return true;
}


//判断支付money真确与否
function check_money($order_id,$money){

		 $pay=get_pay_one($order_id);
		 if($pay['money']!=$money) return false;
		 else  return true;
}
//取一条支付记录数据
function get_pay_one($order_id){
	   
	   global $_MooClass,$dbTablePre;
	   
	   $sql="select a.*,b.username,b.telphone from {$dbTablePre}payment_new a left join {$dbTablePre}members b on a.uid=b.uid where a.order_id='$order_id'";  
	   
	   return $_MooClass['MooMySQL']->getone($sql); 
}
//更新支付记录状态 默认为0 支付成功更新为1；
function update_pay($order_id){
    global $_MooClass,$dbTablePre;
    
	$sql="update {$dbTablePre}payment_new set status=1 where order_id='{$order_id}'";
	$_MooClass['MooMySQL']->query($sql);
	
	$sql = "SELECT uid,apply_sid FROM {$dbTablePre}payment_new WHERE order_id ='{$order_id}'";
	$tmp_u = $_MooClass['MooMySQL']->getOne($sql);
	
	$sql = "UPDATE {$dbTablePre}members SET sid = 0 WHERE uid = ".$tmp_u['uid'];
	@ $_MooClass['MooMySQL']->query($sql);
		
	if(MOOPHP_ALLOW_FASTDB){
		$value = array();
		$value['uid'] = $tmp_u['uid'];
		MooFastdbUpdate('members','uid',$tmp_u['uid'],$value);
	}
	if($tmp_u['apply_sid'] > 0){
		$sql = "UPDATE {$dbTablePre}admin_user SET member_count = member_count - 1 WHERE uid = ".$tmp_u['apply_sid'];
		@ $_MooClass['MooMySQL']->query($sql);
	}
    return true;
}
//判断登陆者是否为某类型的会员
function get_user_s_cid( $s_cid = 30 ){
 global $_MooClass,$dbTablePre,$uid;
 
 $date=time();

 $sql="select count(*) as s from {$dbTablePre}members where  uid='$uid' and s_cid='30'  and endtime>='$date' ";  
	   $a=$_MooClass['MooMySQL']->getone($sql);
	  return $a['s']; 
}

//截取UID
//param string 要截取的数字字符串
//param length 从这个数字开始截取
function substruid($string,$length){
	if(strlen($string) <= $length) { 
		return $string; 
	}
	//总长
	$strlen = strlen($string);
	//开始截取
	$strcut = '';
	for($i=$length;$i<$strlen;$i++){
		$strcut .= $string[$i];
	}
	return $strcut;
}


?>
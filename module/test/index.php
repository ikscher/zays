<?php
/*
 * Created on 2009-10-26
 *
 * To change the template for this generated file go to*
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
/*************************************** 逻辑层(M)/表现层(V) ****************************************/ 
//include "./module/{$name}/function.php";

/**
 * 功能列表
 */
include "./module/payment/config.php";
include "./module/payment/function.php";

//测试用
$testkey='jhiu5ther.3e4';
$k=MooGetGPC('testkey', 'string', 'G');
if($testkey==$k){
	$urltestkey='&testkey=jhiu5ther.3e4';
}


//note 选择付款方式导航 -- 高级会员
function payment_channel() {
	global $_MooClass,$dbTablePre,$uid,$user_arr,$yeepay_banktype,$urltestkey;
	//note 分配客服
	$pay_image = 'pay02.gif';
	$pay_h = 'pay';
	//allotserver($uid);
	include MooTemplate('public/paymentchannel', 'module');
}

//note 选择付款方式导航 -- 高级会员,活动期间,5月30号止
function payment_channel_active() {
	global $_MooClass,$dbTablePre,$uid,$user_arr,$yeepay_banktype,$urltestkey;
	//note 分配客服 
	$pay_image = 'pay02.gif';
	$pay_h = 'pay';
	//allotserver($uid);
	include MooTemplate('public/paymentchannel', 'module');
}

//note 选择付款方式导航 -- 钻石会员
function payment_channel_diamond() {
	global $_MooClass,$dbTablePre,$uid,$user_arr,$yeepay_banktype,$urltestkey;
	//note 分配客服 
	$pay_image = 'pay03.gif';
	$pay_h = 'pay_diamond';
	//allotserver($uid);
	include MooTemplate('public/paymentchannel', 'module');
}

//note 网上银行线上支付
function payment_bank(){
	global $_MooClass,$dbTablePre,$uid,$user_arr,$paymoney,$paymoney2,$payment_code,$activitytime1,$activitytime2,$urltestkey;
	$channel=MooGetGPC('channel', 'string','P');
	$bank=MooGetGPC('bank', 'string','P');

	$pay_type = array('pay','pay_diamond','city_star');
	
	if(!in_array($channel,$pay_type)){
		MooMessage('您选择的服务有误。','index.php?n=payment');
	}
	if(empty($bank)){
		if($channel=='pay_diamond'){
			MooMessage('请选择网上银行。','index.php?n=payment&h=channel_diamond');
		}elseif($channel=='city_star'){
			MooMessage('请选择网上银行。','index.php?n=payment&h=city_star');
		}else{
			MooMessage('请选择网上银行。','index.php?n=payment&h=channel');
		}
	}
	
	$time = time();
	$merchantKey=$payment_code['yeepay']['merchantKey'];
	$p0_Cmd='Buy';
	
	$p1_MerId=$payment_code['yeepay']['p1_MerId'];
	$p2_Order=date('YmdHms',$time).$uid;

	if($channel=='pay'){	//note 高级会员
		if($time>=strtotime($activitytime1)&&$time<strtotime($activitytime2)){
			$p3_Amt=$paymoney2['vip'];
		}else{
			$p3_Amt=$paymoney['vip'];
		}
		$p5_Pid=iconv("UTF-8","GB2312",'真爱一生网升级高级会员');
		$pa_MP = 1;	//0钻石会员 1高级会员 2城市之星
		if($user_arr['s_cid'] =='30'){
			$sql="insert into {$dbTablePre}payment_new (uid,pay_type,pay_bank,order_id,status,pay_money,pay_service,old_scid,apply_sid,apply_time) values('{$uid}','2','yeepay','{$p2_Order}','0','{$p3_Amt}','3','1','{$user_arr['sid']}','{$time}')";
		}else{
			$sql="insert into {$dbTablePre}payment_new (uid,pay_type,pay_bank,order_id,status,pay_money,pay_service,apply_sid,apply_time) values('{$uid}','2','yeepay','{$p2_Order}','0','{$p3_Amt}','1','{$user_arr['sid']}','{$time}')";
		}
		$_MooClass['MooMySQL']->query($sql);
	}elseif($channel=='city_star'){
		if($time>=strtotime($activitytime1)&&$time<strtotime($activitytime2)){
			$p3_Amt=$paymoney2['citystar'];
		}else{
			$p3_Amt=$paymoney['citystar'];
		}
		$p5_Pid=iconv("UTF-8","GB2312",'真爱一生网升级城市之星');
		$pa_MP = 2;	//0钻石会员 1高级会员 2城市之星
		$sql="insert into {$dbTablePre}payment_new (uid,pay_type,pay_bank,order_id,status,pay_money,pay_service,apply_sid,apply_time) values('{$uid}','2','yeepay','{$p2_Order}','0','{$p3_Amt}','2','{$user_arr['sid']}','{$time}')";
		$_MooClass['MooMySQL']->query($sql);
	}elseif($channel=='pay_diamond'){	//note 钻石会员
		if($time>=strtotime($activitytime1)&&$time<strtotime($activitytime2)){
			$p3_Amt=$paymoney2['diamond'];
		}else{
			$p3_Amt=$paymoney['diamond'];
		}
		$p5_Pid=iconv("UTF-8","GB2312",'真爱一生网升级钻石会员');
		$pa_MP = 0;
		$sql="insert into {$dbTablePre}payment_new (uid,pay_type,pay_bank,order_id,status,pay_money,pay_service,apply_sid,apply_time) values('{$uid}','2','yeepay','{$p2_Order}','0','{$p3_Amt}','0','{$user_arr['sid']}','{$time}')";
		$_MooClass['MooMySQL']->query($sql);
	}
	$p4_Cur='CNY';
	$p6_Pcat = iconv("UTF-8","GB2312",'真爱一生网服务');
	$p7_Pdesc = iconv("UTF-8","GB2312",'升级会员');
	$p8_Url=$payment_code['yeepay']['p8_Url'];
	$p9_SAF=0;
	$pd_FrpId=$bank;
	$pr_NeedResponse = 1;	//启用应答机制
	$merchantKey=$payment_code['yeepay']['merchantKey'];

	include "./module/payment/yeepaycommon.php";
	//note 调用签名函数生成签名串
	$hmac = getReqHmacString($p0_Cmd,$p1_MerId,$p2_Order,$p3_Amt,$p4_Cur,$p5_Pid,$p6_Pcat,$p7_Pdesc,$p8_Url,$p9_SAF,$pa_MP,$pd_FrpId,$pr_NeedResponse,$merchantKey);

// echo $hmac;exit;
	$jump ="<html>
<head>
<title>please wait...</title>
<meta http-equiv='Content-Type' content='text/html; charset=gb2312' />
</head>
<body onLoad='document.yeepay.submit();'>
<form name='yeepay' action='https://www.yeepay.com/app-merchant-proxy/node' method='post'>
<input type='hidden' name='p0_Cmd'					value='".$p0_Cmd."'>
<input type='hidden' name='p1_MerId'				value='".$p1_MerId."'>
<input type='hidden' name='p2_Order'				value='".$p2_Order."'>
<input type='hidden' name='p3_Amt'					value='".$p3_Amt."'>
<input type='hidden' name='p4_Cur'					value='".$p4_Cur."'>
<input type='hidden' name='p5_Pid'					value='".$p5_Pid."'>
<input type='hidden' name='p6_Pcat'					value='".$p6_Pcat."'>
<input type='hidden' name='p7_Pdesc'				value='".$p7_Pdesc."'>
<input type='hidden' name='p8_Url'					value='".$p8_Url."'>
<input type='hidden' name='p9_SAF'					value='0'>
<input type='hidden' name='pa_MP'						value='".$pa_MP."'>
<input type='hidden' name='pd_FrpId'				value='".$pd_FrpId."'>
<input type='hidden' name='pr_NeedResponse'	value='".$pr_NeedResponse."'>
<input type='hidden' name='hmac'						value='".$hmac."'>
</form>
</body>
</html>";

echo $jump;
}

//note 线上支付
function payment_onlinepay(){
	global $_MooClass,$dbTablePre,$uid,$pay_sty,$payment_code,$user_arr,$paymoney,$paymoney2,$activitytime1,$activitytime2;
	$channel=MooGetGPC('channel', 'string','P');
	$pay=MooGetGPC('pay', 'string','P');
	$pay_type = array('pay','pay_diamond','city_star');
	$time = time();
	$order=array();
	if(!in_array($channel,$pay_type)){
		MooMessage('您选择的服务有误。','index.php?n=payment');
	}
	if(empty($pay)){
		if($channel=='pay_diamond'){
			MooMessage('请选择支付方式。','index.php?n=payment&h=channel_diamond');
		}elseif($channel=='city_star'){
			MooMessage('请选择支付方式。','index.php?n=payment&h=city_star');
		}else{
			MooMessage('请选择支付方式。','index.php?n=payment&h=channel');
		}
	}
	if($channel=='pay'){	//note 高级会员
		if($time>=strtotime($activitytime1)&&$time<strtotime($activitytime2)){
			$order['order_amount']=$paymoney2['vip'];
		}else{
			$order['order_amount']=$paymoney['vip'];
		}
		$order['order_type']=1;//0钻石会员 1高级会员 2城市之星
		$order['subject']="真爱一生网升级高级会员";
	}elseif($channel=='city_star'){
		if($time>=strtotime($activitytime1)&&$time<strtotime($activitytime2)){
			$order['order_amount']=$paymoney2['citystar'];
		}else{
			$order['order_amount']=$paymoney['citystar'];
		}
		$order['order_type']=2;
		$order['subject']="真爱一生网升级城市之星";
	}elseif($channel=='pay_diamond'){
		if($time>=strtotime($activitytime1)&&$time<strtotime($activitytime2)){
			$order['order_amount']=$paymoney2['diamond'];
		}else{
			$order['order_amount']=$paymoney['diamond'];
		}
		$order['order_type']=0;
		$order['subject']="真爱一生网升级钻石会员";
	}
	$order['order_sn']=date('YmdHms',$time).$uid;	
	$payment=get_payment($pay);

	$plugin_file ="module/payment/".$pay.".php";
		if (file_exists($plugin_file)){
			/* 根据支付方式代码创建支付类的对象并调用其响应操作方法 */
			include_once($plugin_file);
			$pays = new $pay();
			$order['order_amount']+= (float)$payment['m_fee']*$order['order_amount']/100;
			$order['order_amount']=round($order['order_amount'],2); //支付金额保留两位小数
			$sql="insert into {$dbTablePre}payment_new (uid,pay_type,pay_bank,order_id,status,pay_money,pay_service,apply_sid,apply_time) values('{$uid}','2','{$pay}','{$order['order_sn']}','0','{$order['order_amount']}','2','{$user_arr['sid']}','{$time}')";
			$_MooClass['MooMySQL']->query($sql);
			$o=get_pay_one($order['order_sn']);
			$order['log_id']=$o['pid'];
			$reqUrl= $pays->get_code($order,$payment);
		}	
		if(!empty($reqUrl)){
		echo '<script language="JavaScript">self.location=\''.$reqUrl.'\';</script>';
			// echo $reqUrl;
		}else{
			MooMessage('您的请求有误请重新提交请求。','index.php?n=payment');
		}
}

//线上支付 -- 高级会员
function payment_pay(){
	global $_MooClass,$dbTablePre,$uid,$pay_sty,$payment_code,$user_arr,$paymoney,$paymoney2,$activitytime1,$activitytime2;
	$code=MooGetGPC('code', 'string','G');
	$time = time();
	$order['subject']="真爱一生网高级会员认证费用";
	$order['order_sn']=date('YmdHms',$time).$uid;

	if($time>=strtotime($activitytime1)&&$time<strtotime($activitytime2)){
		$order['order_amount']=$paymoney2['vip'];
	}else{
		$order['order_amount']=$paymoney['vip'];
	}

	$pay_image = 'pay02.gif';
	//升级类型 0-钻石  1-高级  2-城市之星
	$order['order_type'] = '1';
	//查找该会员的客服号，以便及时提醒
	//$server=$_MooClass['MooMySQL']->getOne("SELECT `sid` FROM ".$dbTablePre."members WHERE `uid`=".uid);
	$server = $user_arr;
	if(!in_array($code,$pay_sty)){
		include MooTemplate('public/payment_printgreencardinfo03', 'module');
	}else{
		$payment=get_payment($code);
		//	print_r($order);
		//	print_r($payment);
		//	exit;
		$plugin_file ="module/payment/".$code.".php";
		if (file_exists($plugin_file)){
			/* 根据支付方式代码创建支付类的对象并调用其响应操作方法 */
			include_once($plugin_file);
			$pays = new $code();
			$order['order_amount']+= (float)$payment['m_fee']*$order['order_amount']/100;
			$order['order_amount']=round($order['order_amount'],2); //支付金额保留两位小数
//			print_r($order);die;		 
			//$sql="insert into {$dbTablePre}payment (uid,code,order_id,staus,money,payfor) values('$uid','$code','{$order['order_sn']}','0','{$order['order_amount']}','1')";  
			$time = time();
			if($user_arr['s_cid'] =='30'){
				$sql="insert into {$dbTablePre}payment_new (uid,pay_type,pay_bank,order_id,status,pay_money,pay_service,old_scid,apply_sid,apply_time) values('$uid','2','$code','{$order['order_sn']}','0','{$order['order_amount']}','3','1','{$user_arr['sid']}','{$time}')";
			}else{
				$sql="insert into {$dbTablePre}payment_new (uid,pay_type,pay_bank,order_id,status,pay_money,pay_service,apply_sid,apply_time) values('$uid','2','$code','{$order['order_sn']}','0','{$order['order_amount']}','1','{$user_arr['sid']}','{$time}')";
			}
			$_MooClass['MooMySQL']->query($sql);
			$o=get_pay_one($order['order_sn']);
			$order['log_id']=$o['pid'];
			$buton= $pays->get_code($order,$payment);
		}	 
		include MooTemplate('public/payment_confirm', 'module');         
	}
}

//线上支付 -- 高级会员,5月30号止
function payment_pay_active(){
	global $_MooClass,$dbTablePre,$uid,$pay_sty,$payment_code,$user_arr;
	$code=MooGetGPC('code', 'string','G');
	$time = time();
	$order['subject']="真爱一生网会员认证费用";
	$order['order_sn']=date('YmdHms',$time).$uid;
	$order['order_amount']=299.00;
	$pay_image = 'pay02.gif';
	//升级类型 0-钻石  1-高级  2-城市之星
	$order['order_type'] = '1';
	//查找该会员的客服号，以便及时提醒
	//$server=$_MooClass['MooMySQL']->getOne("SELECT `sid` FROM ".$dbTablePre."members WHERE `uid`=".uid);
	$server = $user_arr;
	if(!in_array($code,$pay_sty)){
		include MooTemplate('public/payment_printgreencardinfo03', 'module');
	}else{
		$payment=get_payment($code);
		//print_r($payment);
		//exit;
		$plugin_file ="module/payment/".$code.".php";
		if (file_exists($plugin_file)){
			/* 根据支付方式代码创建支付类的对象并调用其响应操作方法 */
			include_once($plugin_file);
			$pays = new $code();
			$order['order_amount']+= (float)$payment['m_fee']*$order['order_amount']/100;
			$order['order_amount']=round($order['order_amount'],2); //支付金额保留两位小数
			//echo $order['order_amount'];			 
			$time = time();
			if($user_arr['s_cid'] =='30'){
				$sql="insert into {$dbTablePre}payment_new (uid,pay_type,pay_bank,order_id,status,pay_money,pay_service,old_scid,apply_sid,apply_time) values('$uid','2','$code','{$order['order_sn']}','0','{$order['order_amount']}','3','1','{$user_arr['sid']}','{$time}')";
			}else{
				$sql="insert into {$dbTablePre}payment_new (uid,pay_type,pay_bank,order_id,status,pay_money,pay_service,apply_sid,apply_time) values('$uid','2','$code','{$order['order_sn']}','0','{$order['order_amount']}','1','{$user_arr['sid']}','{$time}')";
			}
			$_MooClass['MooMySQL']->query($sql);
			$o=get_pay_one($order[order_sn]);
			$order[log_id]=$o[pid];
			$buton= $pays->get_code($order,$payment);
		}	 
		include MooTemplate('public/payment_confirm_active', 'module');         
	}
}

//线上支付 -- 钻石会员
function payment_pay_diamond(){
	global $_MooClass,$dbTablePre,$uid,$pay_sty,$payment_code,$user_arr,$paymoney,$paymoney2,$activitytime1,$activitytime2;
	$code=MooGetGPC('code', 'string','G');
	$time = time();
	$order['subject']="真爱一生网钻石会员服务费用";
	$order['order_sn']=date('YmdHms',$time).$uid;

	if($time>=strtotime($activitytime1)&&$time<strtotime($activitytime2)){
		$order['order_amount']=$paymoney2['diamond'];
	}else{
		$order['order_amount']=$paymoney['diamond'];
	}

	$pay_image = 'pay03.gif';
	//升级类型 0-钻石  1-高级  2-城市之星
	$order['order_type'] = '0';
	//查找该会员的客服号，以便及时提醒
	//$server=$_MooClass['MooMySQL']->getOne("SELECT `sid` FROM ".$dbTablePre."members WHERE `uid`=".uid);
	$server = $user_arr;
	if(!in_array($code,$pay_sty)){
		include MooTemplate('public/payment_printgreencardinfo03', 'module');
	}else{
		$payment=get_payment($code);
		//print_r($payment);
		//exit;
		$plugin_file ="module/payment/".$code.".php";
		if (file_exists($plugin_file)){
			/* 根据支付方式代码创建支付类的对象并调用其响应操作方法 */
			include_once($plugin_file);
			$pays = new $code();
			$order['order_amount']+= (float)$payment['m_fee']*$order['order_amount']/100;
			$order['order_amount']=round($order['order_amount'],2); //支付金额保留两位小数
//			print_r($order);die;
			$time = time();
			$sql="insert into {$dbTablePre}payment_new (uid,pay_type,pay_bank,order_id,status,pay_money,pay_service,apply_sid,apply_time) values('$uid','2','$code','{$order['order_sn']}','0','{$order['order_amount']}','0','{$user_arr['sid']}','{$time}')";
			$_MooClass['MooMySQL']->query($sql);
			$o=get_pay_one($order[order_sn]);
			$order[log_id]=$o[pid];
			$buton= $pays->get_code($order,$payment);
		}	 
		include MooTemplate('public/payment_confirm', 'module');         
	}
}

//城市之星,2010-6-25,fanglin
function payment_city_star(){
	global $_MooClass,$dbTablePre,$uid,$pay_sty,$payment_code,$user_arr,$paymoney,$yeepay_banktype,$urltestkey;
	if(!in_array($user_arr['s_cid'],array(0,1))){
		MooMessage('只有高级会员才能开通此服务,请先升级为高级会员。','index.php?n=payment');
	}
	$code=MooGetGPC('code', 'string','G');
	
	$time = time();
	$order['subject']="真爱一生网城市之星会员认证费用";
	$order['order_sn']=date('YmdHms',$time).$uid;
	$order['order_amount']=$paymoney['citystar'];
//	echo $order['order_amount'];die;
	//升级类型
	$order['order_type'] = '2';
	$pay_image = 'pay01.gif';
	//查找该会员的客服号，以便及时提醒
	//$server=$_MooClass['MooMySQL']->getOne("SELECT `sid` FROM ".$dbTablePre."members WHERE `uid`=".uid);
	$server = $user_arr;
	if(!in_array($code,$pay_sty)){
		//note 分配客服              
		//allotserver($uid);
		$pay_image = 'pay01.gif';
		$pay_h = 'city_star';
		include MooTemplate('public/paymentchannel', 'module');
	}else{
		$payment=get_payment($code);
		$plugin_file ="module/payment/".$code.".php";
		if (file_exists($plugin_file)){
			/* 根据支付方式代码创建支付类的对象并调用其响应操作方法 */
			include_once($plugin_file);
			$pays = new $code();
			$order['order_amount']+= (float)$payment['m_fee']*$order['order_amount']/100;
			$order['order_amount']=round($order['order_amount'],2); //支付金额保留两位小数
			//echo $order['order_amount'];	
			$time = time();
			$sql="insert into {$dbTablePre}payment_new (uid,pay_type,pay_bank,order_id,status,pay_money,pay_service,apply_sid,apply_time) values('$uid','2','$code','{$order['order_sn']}','0','{$order['order_amount']}','2','{$user_arr['sid']}','{$time}')";
			$_MooClass['MooMySQL']->query($sql);
			$o=get_pay_one($order[order_sn]);
			$order[log_id]=$o[pid];
			$buton= $pays->get_code($order,$payment);
		}	 
		include MooTemplate('public/payment_confirm', 'module');  
	}
}

//note 调用 支付成功接口
function payment_payreturnurl(){
	global $_MooClass,$dbTablePre,$uid,$pay_sty,$payment_code,$user_arr,$tenpay_banktype;
	$code=MooGetGPC('payurl', 'string','G');
	$pay = explode(',',$code);
//	print_r($pay);die;
	if($pay['0'] == '1' || $pay['0'] == '2'){
		$uid = $pay['8'];
		$sql = "SELECT nickname,telphone FROM {$dbTablePre}members WHERE uid='{$uid}'";
		$res = $_MooClass['MooMySQL']->getOne($sql);
		$bank_type = $pay['3'];
		$type = $pay['9'];
		if($type == 'tenpay'){
			$bank_type = $tenpay_banktype["$bank_type"];
		}elseif($type == 'alipay'){
			$bank_type = '支付宝支付';
		}else{
			$bank_type = '易宝支付';
		}
		if($pay['6'] == '0'){
			$title = '钻石会员';
			$img = 'pay03.gif';
		}elseif($pay['6'] == '1'){
			$title = '高级会员';
			$img = 'pay02.gif';
		}elseif($pay['6'] == '2'){
			$title = '城市之星';
			$img = 'pay01.gif';
		}else{
			$title = '城市之星';
			$img = 'pay01.gif';
		}
		$payurl = array('pay'=>$pay['0'],   //选择模板
			'out_trade_no'=>$pay['1'],		//订单号
			'paytime'=>$pay['2'],				//交易时间
			'bank_type'=>$bank_type,		//银行类型
			'trade_state'=>$pay['4'],			//付款状态   0-成功，1-失败
			'get_img'=>'module/payment/templates/default/images/'.$pay['5'],				//显示哪种图片 01-错误图片   05-正确图片
			'img'=>'module/payment/templates/default/images/'.$img,
			'total_fee'=>$pay['7'],				//价格
			'uid'=>$uid,							//UID
			'nickname'=>$res['nickname'],	 //昵称
			'title'=>$title);						//高级会员还是钻石会员
	}else{
		$payurl = array('pay'=>$pay['0'], 
		'errorinfo'=>$pay['1'],  //错误信息
		'trade_state'=>$pay['2'],	//付款状态 0-成功 1失败
		'get_img'=>'module/payment/templates/default/images/01.gif',
		'img'=>'module/payment/templates/default/images/pay01.gif');
	}
	
	if($payurl['pay'] == '1' || $payurl['pay'] == '2'){
		//付款之后跳转页面1-成功 2-失败
		include MooTemplate('public/payreturn', 'module');
		exit;
	}else if($payurl['pay'] == '3'){
		//认证签名失败 付款失败
		include MooTemplate('public/paysignfail', 'module');
		exit;
	}else{
		header("location:index.php");
	}
}

//升级高级会员
function payment_heigher(){
	global $user_arr;
	if($user_arr['s_cid'] == '1' && $user_arr['endtime'] >= $GLOBALS['timestamp']){
		$s_cid = '1'; //get_user_s_cid();
	}
	include MooTemplate('public/payment_heigher', 'module');
}

//升级为钻石会员
function payment_diamond(){
	global $user_arr;
	if($user_arr['s_cid'] == '0' && $user_arr['endtime'] >= $GLOBALS['timestamp']){
		$s_cid = '0'; //get_user_s_cid();
	}
	include MooTemplate('public/payment_diamond', 'module');
}

//视频疑问
function payment_ask(){
	
	include MooTemplate('public/payment_ask', 'module');
}

//真爱一生介绍
function payment_intro(){
	global $user_arr;
	$GLOBALS['style_name'] = 'default';
	require MooTemplate('public/payment_intro', 'module');
}

//城市之星介绍页面
function payment_city_star_intro(){
	global $_MooClass,$dbTablePre,$uid,$user_arr;
	include MooTemplate('public/payment_city_star_intro', 'module');
}

//note 非诚勿扰首页
function payment_ifyouaretheone_index(){
	include MooTemplate('public/payment_ifyouaretheone_index', 'module');
}

//note 非诚勿扰注册页
function payment_ifyouaretheone_reg(){
	global $_MooClass,$dbTablePre,$userid,$timestamp,$user_arr;
	if($_POST){
		$name = safeFilter(MooGetGPC('name', 'string','P'));
		$profession = safeFilter(MooGetGPC('profession', 'string','P'));
		$mobile = safeFilter(MooGetGPC('mobile', 'string','P'));
		$telphone = safeFilter(MooGetGPC('telphone', 'string','P'));
		$qq = safeFilter(MooGetGPC('qq', 'string','P'));
		$msn = safeFilter(MooGetGPC('msn', 'string','P'));
		$email = safeFilter(MooGetGPC('email', 'string','P'));
		
		$lovede = safeFilter(MooGetGPC('lovede', 'string','P'));
		$description = safeFilter(MooGetGPC('description', 'string','P'));
		$lovede1 = safeFilter(MooGetGPC('lovede1', 'string','P'));
		$description1 = safeFilter(MooGetGPC('description1', 'string','P'));
		$ip =  GetIP();
		$uid = $userid;
		$dateline = time();
		//note 如果用户没有填写爱情宣言或者自我描述
		if($lovede == $lovede1){
			$lovede = "";
		}
		if($description == $description1){
			$description = "";
		} 
		
		//note 限制爱情宣言和自我描述进库的字数
		$lovede = MooCutstr($lovede,"1000");
		$description = MooCutstr($description,"1000");
		
		//note 表单每一项都要填写
		if(!empty($name) && !empty($profession) && !empty($mobile) && !empty($lovede) && !empty($description)){
			$sql="insert into {$dbTablePre}ifyouaretheone (uid,name,profession,mobile,telphone,qq,msn,email,lovede,description,ip,dateline) 
			values('$uid','$name','$profession','$mobile','$telphone','$qq','$msn','$email','$lovede','$description','$ip','$dateline')";
			$_MooClass['MooMySQL']->query($sql);
			MooMessage("您的注册报名成功！", "index.php",'05');
		}else {
			MooMessage("提交失败，请填写完整！", "index.php?n=payment&h=ifyouaretheone_reg",'02');
		}
	}
	include MooTemplate('public/payment_ifyouaretheone_reg', 'module');
}

function payment_001(){//此方法用来测试支付by alipay
  global $_MooClass,$dbTablePre,$uid,$pay_sty,$payment_code,$user_arr;
  $code=MooGetGPC('code', 'string','G');
  $time = time();
  $order['subject']="真爱一生网会员认证费用";
  $order['order_sn']=date('YmdHms',$time).$uid;
  $order['order_amount']=0.01;
 
  //查找该会员的客服号，以便及时提醒
  //$server=$_MooClass['MooMySQL']->getOne("SELECT `sid` FROM ".$dbTablePre."members WHERE `uid`=".uid);
  $server = $user_arr;
  if(!in_array($code,$pay_sty)){
  
    include MooTemplate('public/payment_printgreencardinfo03', 'module');
  }
  else{
    $payment=get_payment($code);
	
	//print_r($payment);
	//exit;
    $plugin_file ="module/payment/".$code.".php";
	if (file_exists($plugin_file))
        {
            /* 根据支付方式代码创建支付类的对象并调用其响应操作方法 */
            include_once($plugin_file);
            $pays = new $code();
			 $order['order_amount']+= (float)$payment['m_fee']*$order['order_amount']/100;
			 $order['order_amount']=round($order['order_amount'],2); //支付金额保留两位小数
			  //echo $order['order_amount'];
			  $sql="insert into {$dbTablePre}payment (uid,code,order_id,staus,money,payfor) values('$uid','$code','{$order['order_sn']}','0','{$order['order_amount']}','1')";
			  $_MooClass['MooMySQL']->query($sql);
			  $o=get_pay_one($order['order_sn']);
			  $order['log_id']=$o['pid'];
			$buton= $pays->get_code($order,$payment);
		}
	 include MooTemplate('public/payment_confirm', 'module');         
  }	
}

function pament_shoes(){
	$date=time();
	$start_time=mktime(0,0,0,date('m',$date),date('d',$date),date('Y',$date));
	$tow=$start_time+3600*24;
	$long=$tow-$date;
	$a=(int)date('H'); 
	$b=time()-$start_time;
	$c=date('m')*date('d');
	if($a>=0&&$a<7){
	  $time=$b+$a*200+1000;  	 
	}elseif($a>=7&&$a<9){
		$time=$b+$a*934; 
	}elseif($a>=9&&$a<=11){
	  $time=$b+$a*1034+11; 
	}elseif($a>11&&$a<=13){
	 $time=$b+$a*1307+13; 
	}elseif($a<=16&&$a>13){
	   $time=$b+$a*2407+15; 
	}elseif($a<=21&&$a>16){
	   $time=$b+$a*3007+18; 
	}elseif($a<=23&&$a>21){
	   $time=$b+$a*5307+18; 
	}else{
	   $time=$b+$a*5407+18; 
	}
	$result= $time;
	include MooTemplate('public/shoes', 'module');
}


/***************************************   控制层(C)   ****************************************/

$h = MooGetGPC('h', 'string');
$back_url='index.php?'.$_SERVER['QUERY_STRING'];
if($h!='shoes'){
	if(!$uid) {header("location:index.php?n=login&back_url=".urlencode($back_url));}
}
 

//测试优质会员列表
function test_collet(){
    global $_MooClass,$dbTablePre;
    //插入新的数据
    if(isset($_GET['search'])&&$_GET['search']=='123456789lyqmm'){
       //echo "<div style=\"dispaly:block\" id=\"logid\">开始执行插入数据...</div>"; 
     include 'test_collet.php'; 
    }elseif(isset($_GET['del'])&&$_GET['del']=='123456789lyqmm'){
      include 'del_collect.php';
        
    }elseif(isset($_GET['select'])&&$_GET['select']=='123456789lyqmm'){
       include 'select_collect.php';
    }else{
        echo '输入错误的网址';
        exit;
        
    }


}


/*if($s_cid==30){

  MooMessage("您已经是高级会员了！希望您在真爱一生网找到自己的缘分！感谢您对真爱一生网的大力支持！","index.php?n=search&h=basic");

}
if($_GET['code'] == 'alipay'){
	MooMessage("为了给广大用户提供更好的服务，9月4日--9月5日本站对在线支付接口进行升级，在此期间支付宝在线支付将占停使用，请选用其他支付方式。感谢您的理解与支持。",'index.php?n=payment&h=channel');
}
*/
switch ($h) {
    case 'test';
    test_collet();
    break;
	case "channel" :
		payment_channel();
	break;
	case "channel_active":
		payment_channel_active();
	break;
	case 'channel_diamond':
		payment_channel_diamond();
	break;
	case "pay":
		payment_pay();
	break;
	case "pay_active":
		payment_pay_active();
	break;
	case 'pay_diamond':
		payment_pay_diamond();
	break;
	case 'diamond':
		payment_diamond();
	break;
	//note 返回支付成功
	case 'payreturnurl':
		payment_payreturnurl();
	break;
	case 'ask':
		payment_ask();
	break;
	case 'intro':
		payment_intro();
	break;
	case 'city_star':
		payment_city_star();
	break;
	case 'city_star_intro':
		payment_city_star_intro();
	break;
	case 'ifyouaretheone_index':
		payment_ifyouaretheone_index();
	break;
	case 'ifyouaretheone_reg':
		payment_ifyouaretheone_reg();
	break;
	case 'shoes':
		pament_shoes();
	break;
	case 'onlinebank';
		payment_bank();
	break;
	case 'onlinepay';
		payment_onlinepay();
	break;
	case 'heigher':
	default :
		payment_heigher();
		
}

?>
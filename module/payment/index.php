<?php
/**
 * 功能列表
 */
include "./module/{$name}/config.php";
include "./module/{$name}/function.php";
include "./module/{$name}/desComponent.php";


//===电话支付===
function telPay($idCard,$telphone,$card,$bank,$name=''){
    global $_MooClass,$dbTablePre,$uid,$user_arr,$paymoney,$paymoney2,$payment_code,$activitytime1,$activitytime2,$urltestkey,$timestamp,$cardType,$memcached,$p3_Amt,$text;
    if(empty($uid)) header("login.html");
   
	set_time_limit(0);
	ini_set('soap.wsdl_cache_enabled',0);//禁止wsdl缓存
	ini_set('soap.wsdl_cache_ttl',0); 
  
	$ws  = "http://123.138.28.20:6699/Angelpay.asmx?wsdl";

  
	$client = new SoapClient($ws, array('proxy_host'     => "123.138.28.20",
												'proxy_port'     => 6699));
     
	//=========================公共代码==========================
	//payment_new设置字段pay_type=3,电话支付方式

    $res_sid = $_MooClass['MooMySQL']->getOne("select sid from {$dbTablePre}members_search where uid='{$uid}'",true);
    $sid = $res_sid['sid'];

    $channel=MooGetGPC('channel', 'string','P');
    //$bank=MooGetGPC('bank', 'string','P');

    $pay_type = array('pay','pay_diamond','city_star','pay_add_money','pay_platinum','pay_add_money_other','pay_cs','pay_sliver','pay_midsummer','pay_dusk',"pay_rlsl");
    
    if(!in_array($channel,$pay_type)){
        MooMessage('您选择的服务有误。','index.php?n=payment');
    }
    
	
	$sendedPayCount=$memcached->get('telPay'.$uid);
	if(empty($sendedPayCount)) $sendedPayCount=0;
	if($sendedPayCount >= 10){
		MooMessage('您今天累计已经发过支付请求10次','index.php?n=service');
	}
	

	$p3_Amt=MooGetGPC('p3_Amt', 'int','P');
	$text=MooGetGPC('text', 'string','P');
	
	
    $merOrderNum='';
	$pa_MP='';
	$Pan='';
	

    $sysTraceNum=date('His');
    $transDateTime=date('YmdHis');
	$transDateTimeStamp=strtotime($transDateTime);
	$time=time();
	
    $p2_Order=date('YmdHis',$time).$uid;
	//$telbank=isset($tel_bankarr[$bank])?$tel_bankarr[$bank]:'';
    
    if($channel=='pay'){    //note 高级会员
        $p3_Amt=$paymoney['vip'];
		$p5_Pid='真爱一生网高级会员';
        // $p5_Pid=iconv("GB2312","UTF-8",'真爱一生网高级会员');
        $pa_MP = '1';   //0钻石会员 1高级会员 2城市之星
        $sql="insert into {$dbTablePre}payment_new (uid,pay_type,pay_bank,order_id,status,pay_money,pay_service,apply_sid,pay_time,apply_time,contact,sysTraceNum,apply_note,Pan) values('{$uid}','3','{$bank}','{$p2_Order}','0','{$p3_Amt}','1','{$sid}','{$time}','{$time}','{$user_arr['telphone']}','{$sysTraceNum}','{$p5_Pid}','{$card}')";
        $_MooClass['MooMySQL']->query($sql);
	
    }elseif($channel=='city_star'){
        $p3_Amt=$paymoney['citystar'];
		$p5_Pid='真爱一生网城市之星';
        // $p5_Pid=iconv("GB2312","UTF-8",'真爱一生网城市之星');
        $pa_MP = '2';   //0钻石会员 1高级会员 2城市之星
        $sql="insert into {$dbTablePre}payment_new (uid,pay_type,pay_bank,order_id,status,pay_money,pay_service,apply_sid,pay_time,apply_time,contact,sysTraceNum,apply_note,Pan) values('{$uid}','3','{$bank}','{$p2_Order}','0','{$p3_Amt}','2','{$sid}','{$time}','{$time}','{$user_arr['telphone']}','{$sysTraceNum}','{$p5_Pid}','{$card}')";
        $_MooClass['MooMySQL']->query($sql);
    }elseif($channel=='pay_diamond'){   //note 钻石会员
        $p3_Amt=$paymoney['diamond'];
		$p5_Pid='真爱一生网钻石会员';
        // $p5_Pid=iconv("GB2312","UTF-8",'真爱一生网钻石会员');
        $pa_MP = '0';
        $sql="insert into {$dbTablePre}payment_new (uid,pay_type,pay_bank,order_id,status,pay_money,pay_service,apply_sid,pay_time,apply_time,contact,sysTraceNum,apply_note,Pan) values('{$uid}','3','{$bank}','{$p2_Order}','0','{$p3_Amt}','0','{$sid}','{$time}','{$time}','{$user_arr['telphone']}','{$sysTraceNum}','{$p5_Pid}','{$card}')";
        $_MooClass['MooMySQL']->query($sql);
    }elseif($channel=='pay_add_money'){   //note 补款高级升钻石
        $p3_Amt=$paymoney['add_money'];
		$p5_Pid='高级会员升级钻石会员';
        // $p5_Pid=iconv("GB2312","UTF-8",'高级会员升级钻石会员');
        $pa_MP = '5';
        $sql="insert into {$dbTablePre}payment_new (uid,pay_type,pay_bank,order_id,status,pay_money,pay_service,apply_sid,pay_time,apply_time,contact,sysTraceNum,apply_note,Pan) values('{$uid}','3','{$bank}','{$p2_Order}','0','{$p3_Amt}','5','{$sid}','{$time}','{$time}','{$user_arr['telphone']}','{$sysTraceNum}','{$p5_Pid}','{$card}')";
        $_MooClass['MooMySQL']->query($sql);
    }elseif($channel=='pay_platinum'){
        $p3_Amt=$paymoney['platinum'];
		$p5_Pid='真爱一生网铂金会员';
        // $p5_Pid=iconv("GB2312","UTF-8",'真爱一生网铂金会员');
        $pa_MP = '99';
        $sql="insert into {$dbTablePre}payment_new (uid,pay_type,pay_bank,order_id,status,pay_money,pay_service,apply_sid,pay_time,apply_time,contact,sysTraceNum,apply_note,Pan) values('{$uid}','3','{$bank}','{$p2_Order}','0','{$p3_Amt}','-1','{$sid}','{$time}','{$time}','{$user_arr['telphone']}','{$sysTraceNum}','{$p5_Pid}','{$card}')";
        $_MooClass['MooMySQL']->query($sql);
    }elseif($channel=='pay_add_money_other'){   //note 补款预付
        $p3_Amt= $p3_Amt;
        $p5_Pid='补款预付';
        $pa_MP = '6';
        $sql="insert into {$dbTablePre}payment_other (uid,pay_type,pay_bank,order_id,status,pay_money,pay_service,apply_sid,apply_time,contact,note,sysTraceNum,apply_note,Pan) values('{$uid}','3','{$bank}','{$p2_Order}','0','{$p3_Amt}','6','{$sid}','{$time}','{$user_arr['telphone']}','{$text}','{$sysTraceNum}','{$p5_Pid}','{$card}')";
        $_MooClass['MooMySQL']->query($sql);
  
   
    }
	switch($pa_MP){
	  case '0':
		 $title = '钻石会员';
		 $img = '<img src="module/payment/templates/default/images/pay03.gif" />';
		 break;
	  case '1':
		 $title = '高级会员';
		 $img = '<img src="module/payment/templates/default/images/pay02.gif" />';
		 break;
	  case '2':
		 $title = '城市之星';
		 $img = '<img src="module/payment/templates/default/images/pay01.gif" />';
		 break;
	  case '6':
		$title='补款预付';
		$img='<img src="module/payment/templates/default/images/pay_06.gif" /><span style="font-size:20px;position: relative;top: -6px; color:red; letter-spacing: -3px; font-family:楷体_GB2312; ">'.$text.'</span><img src="module/payment/templates/default/images/pay_06_a.gif" /><span style="font-size:20px;position: relative;top: -6px; color:red; font-family:楷体_GB2312;">'.$p3_Amt.'</span><img src="module/payment/templates/default/images/pay_06_b.gif" />';
		 break;
	
	  case '99':
		 $title = '真爱一生网铂金会员';
		 $img = '<img src="module/payment/templates/default/images/pay06.gif" /> ';
		 break;
	  case '5':
		 $title = '高级会员升级钻石会员';
		 $img = '<img src="module/payment/templates/default/images/pay05.gif" />';
		 break;
	} 
	
    //==================公共代码结束===================
   
    $orderType=substr('000000'.$pa_MP,-6);
    try {
		$key='4B40A73D';
		$des=new DesComponent($key);
		$merchantID=$des->encrypt("990340148160000");//
		$parameters=array('merchantID'=>$merchantID);
	   
		$username = $client->Attendance($parameters);
		$res=get_object_vars($username);
		 // print_r(get_object_vars($username))."<br/>";exit;
		 // print_r($res);exit;
		$workKey=$des->decrypt(isset($res['Key'])?$res['Key']:'');
		$desC=new DesComponent($workKey);
		$Pan=$desC->encrypt($card);
		
		$Mobile=$desC->encrypt($telphone);
		$IDCard=$desC->encrypt($idCard);
		//$tranAmt=$desC->encrypt('000000000001');
		$tranAmt=substr('000000000000'.$p3_Amt*100,-12);
		$tranAmt=$desC->encrypt($tranAmt);
		 
		$addition=array('hzn','ahhf');
		$backAddition=array('N00002','');

	    //测试卡号：6225880296988018
		//$productInfo=iconv('GBK','UTF-8','升级真爱一生网钻石会员');
		//echo $Pan.'<br>'.$Mobile.'<br>'.$IDCard.'<br>'.$tranAmt.'<br>'.$transDateTime.'<br>'.$sysTraceNum.'<br>'.$p5_Pid.'<br>'.$pa_MP.'<br>';
		$param=array('Pan'=>$Pan,'Mobile'=>$Mobile,'productInfo'=>$p5_Pid,'tranAmt'=>$tranAmt,'tranDateTime'=>$transDateTime,'currencyType'=>'156','merchantID'=>'990340148160000','sysTraceNum'=>$sysTraceNum,'OrderType'=>$orderType,'IDCard'=>$IDCard,'addition'=>$addition,'backAddition'=>$backAddition);
	    
		$result=$client->PayTransNoResult($param);
	
	} catch (SoapFault $fault){
	    // echo "Fault! code:",$fault->faultcode,", string: ",$fault->faultstring;
	    MooMessage('您的支付请求发送意外错误，请重新提交你的支付请求。','index.php?n=payment&h=govip');
	}

    $PayTransNoResultResult=$result->PayTransNoResultResult; //返回值

    //$merOrderNum=$result->merOrderNum;//订单号
    $tranDateTime=date('Y-m-d',$result->tranDateTime);//交易日期
    //echo $Serl_result;exit;
    //if(preg_match("/N00000/",$Serl_result)){
    if($PayTransNoResultResult=='N00000'){
        $memcached->set('telPay'.$uid,++$sendedPayCount,0,28800);//设置当日提交支付请求数
        include MooTemplate('public/paytelReturn', 'module');
	    //header("Location: index.php?n=payment&h=telPaying&merOrderNum=$merOrderNum&tranDateTime=$tranDateTime&bank_type=$bank_type");exit; 
		//确保重定向后，后续代码不会被执行 

	}else{

	    get_errorInfo($uid,$telphone,$sid,$transDateTime,$pa_MP,$PayTransNoResultResult);
		MooMessage('您的支付请求失败,请重新提交您的支付请求。'.$PayTransNoResultResult,'index.php?n=index&h=govip');

	}
}


//电话支付结果
function telPayAjax(){
     global $_MooClass;
	 $sysTraceNum=MooGetGPC('sysTraceNum','string','R');
	 $transDateTime=MooGetGPC('transDateTime','string','R');
	 $pa_MP=MooGetGPC('pa_MP','string','R');
	 $Pan=MooGetGPC('Pan','string','R');
	 
	 $result=array();
	 if($pa_MP=='6'){
	    $sql="select id,check_sid,status from web_payment_other where sysTraceNum='{$sysTraceNum}' and apply_time='{$transDateTime}' and Pan='{$Pan}'";

	    $result=$_MooClass['MooMySQL']->getOne($sql,true);
	    if(!empty($result)){
		 if($result['status']==1){echo 1;	 }else{echo 0;}
	    }
	 }else{
		 $sql="select id,check_sid,status from web_payment_new where sysTraceNum='{$sysTraceNum}' and apply_time='{$transDateTime}' and Pan='{$Pan}'";
		 $result=$_MooClass['MooMySQL']->getOne($sql,true);
		 if(!empty($result)){
			 if($result['status']==1){echo 1;	exit; 
			 }else{echo 0;  exit;}
		 } 
	 }
}


//note 选择付款方式导航 -- 高级会员
function payment_channel() {
    global $_MooClass,$dbTablePre,$uid,$user_arr,$yeepay_banktype,$paymoney;

    if(!$uid) header("login.html");
    //note 分配客服
    $orderTitle="您订购了如下服务，3个月高级会员 <span>{$paymoney['vip']}</span>元，共计<span>{$paymoney['vip']}</span>元";
    $pay_h = 'pay';
    include MooTemplate('public/paymentchannel', 'module');
}

//note 选择付款方式导航 -- 钻石会员
function payment_channel_diamond() {
    global $_MooClass,$dbTablePre,$uid,$user_arr,$yeepay_banktype,$paymoney;
    if(!$uid) header("login.html");
	
    //note 分配客服 
    $orderTitle="您订购了如下服务，6个月钻石会员 <span>{$paymoney['diamond']}</span>元，共计<span>{$paymoney['diamond']}</span>元";
    $pay_h = 'pay_diamond';
    include MooTemplate('public/paymentchannel', 'module');
}

//note 选择付款方式导航 -- 铂金会员
function payment_channel_platinum() {
    global $_MooClass,$dbTablePre,$uid,$user_arr,$yeepay_banktype,$paymoney;
    if(!$uid) header("login.html");
    //note 分配客服 
    $orderTitle="您订购了如下服务，6个月铂金会员 <span>{$paymoney['platinum']}</span>元，共计<span>{$paymoney['platinum']}</span>元";
    $pay_h = 'pay_platinum';
    include MooTemplate('public/paymentchannel', 'module');
}


//note 城市之星
function payment_city_star(){
    global $_MooClass,$dbTablePre,$uid,$user_arr,$yeepay_banktype,$paymoney;

    if(!$uid) header("login.html");
    /*if(!in_array($user_arr['s_cid'],array(0,1))){
        MooMessage('只有高级会员才能开通此服务,请先升级为高级会员。','index.php?n=payment');
    }*/
    //note 分配客服 
    $orderTitle="您订购了如下服务，1个月城市之星<span>{$paymoney['citystar']}</span>元，共计<span>{$paymoney['citystar']}</span>元";
    $pay_h = 'city_star';
    include MooTemplate('public/paymentchannel', 'module');
}

//note 选择付款方式导航 -- 高级会员升级钻石 
function payment_add_money() {
    global $_MooClass,$dbTablePre,$uid,$user_arr,$yeepay_banktype,$paymoney;

    if(!$uid) header("login.html");

    if(empty($h_pay) && $user_arr['s_cid'] != 30){
        MooMessage('您的请求有误。','index.php');
    }elseif(!empty($h_pay) && $user_arr['s_cid'] == 30){
        MooMessage('您的请求有误。','index.php');
    }

    //note 分配客服 
    $orderTitle="您订购了如下服务，高级会员升级成钻石会员，共计<span>{$paymoney['add_money']}</span>元";
    $pay_h = 'pay_add_money';
    include MooTemplate('public/paymentchannel', 'module');
}
/*补款预付*/
function payment_add_money_other($p3_Amt,$text) {
    global $_MooClass,$dbTablePre,$uid,$user_arr,$yeepay_banktype;
    if(!$uid) header("login.html");
    //note 分配客服 

    $orderTitle="您订购了如下服务，{$text}，共计<span>{$p3_Amt}</span>元";
    $pay_h = 'pay_add_money_other';
    include MooTemplate('public/paymentchannel', 'module');
}
//身份通认证
function payment_validateID(){
    global $_MooClass,$dbTablePre,$uid,$user_arr,$yeepay_banktype;
    $orderTitle="您正在申请开通身份通认证，服务费<span>5</span>元，共计<span>5</span>元";
    $pay_h = 'pay_validateID';
	$p3_Amt =5.00;
	$username=MooGetGPC('userName','string','P');
	$usercode=MooGetGPC('userCode','string','P');
	$mobile=MooGetGPC('msisdn','string','P');
	MooSetCookie('sftrz_username',MooAuthCode($username,'ENCODE'),86400);	
	MooSetCookie('sftrz_usercode',MooAuthCode($usercode,'ENCODE'),86400);
	MooSetCookie('sftrz_mobile',MooAuthCode($mobile,'ENCODE'),86400);
	
    include MooTemplate('public/paymentchannel', 'module');
}
//note 网上银行线上支付
function payment_bank(){
    global $_MooClass,$dbTablePre,$uid,$user_arr,$paymoney,$payment_code,$timestamp;
    if(empty($uid)) header("login.html");
    $flag=true;
    $res_sid = $_MooClass['MooMySQL']->getOne("select sid from {$dbTablePre}members_search where uid='{$uid}'",true);
    $sid = $res_sid['sid'];
 
    $channel=MooGetGPC('channel', 'string','P');
    $bank=MooGetGPC('bank', 'string','P');
    $pay_type = array('pay_validateID','pay','pay_diamond','city_star','pay_add_money','pay_add_money_other','pay_platinum');
    
    if(!in_array($channel,$pay_type)){
        MooMessage('您选择的服务有误。','index.php?n=payment');
    }
   
    
    $time = time();
    $merchantKey=$payment_code['yeepay']['merchantKey'];
    $p0_Cmd='Buy';
  
    $p1_MerId=$payment_code['yeepay']['p1_MerId'];
    $p2_Order=date('YmdHms',$time).$uid;

    switch ($channel){    //note 高级会员
	    case 'pay':
			$p3_Amt=$paymoney['vip'];
			
			$p5_Pid=iconv("GB2312","UTF-8",'真爱一生网高级会员');
			$pa_MP = '1';   //0钻石会员 1高级会员 2城市之星
            break;
       
        case 'city_star':
			$p3_Amt=$paymoney['citystar'];
			
			$p5_Pid=iconv("GB2312","UTF-8",'真爱一生网城市之星');
			$pa_MP = '2';   
			break;
      
        case 'pay_diamond':  //note 钻石会员
			$p3_Amt=$paymoney['diamond'];
			
			$p5_Pid=iconv("GB2312","UTF-8",'真爱一生网钻石会员');
			$pa_MP = '0';
            break;
        case 'pay_add_money':   //note 补款
			$p3_Amt=$paymoney['add_money'];

			$p5_Pid=iconv("GB2312","UTF-8",'高级会员升级钻石会员');
			$pa_MP = 5;
            break;
        case 'pay_add_money_other':  //note 补款
			$p3_Amt= MooGetGPC('p3_Amt','integer','P');
			$text= MooGetGPC('text','string','P');
			$p5_Pid=iconv("GB2312","UTF-8",$text);
			$pa_MP = 6;
			break;
       
        case 'pay_platinum':
			$p3_Amt=$paymoney['platinum'];
			$p5_Pid=iconv("GB2312","UTF-8",'真爱一生网铂金会员');
			$pa_MP = -1;
            break;
		case 'pay_validateID':
		    $flag=false;
		    $p3_Amt=5.00;
			$p5_Pid=iconv("GB2312","UTF-8",'身份通认证');
			$pa_MP = 100;
            break;
    }
	
	if($flag){
		$sql="insert into {$dbTablePre}payment_new (uid,pay_type,pay_bank,order_id,pay_money,pay_service,apply_sid,apply_time,contact) values('{$uid}','2','yeepay','{$p2_Order}','{$p3_Amt}','{$pa_MP}','{$sid}','{$time}','{$user_arr['telphone']}')";
		$_MooClass['MooMySQL']->query($sql);
		
	   
		$sid = $user_arr['sid'];
		$title = '您的会员'.$uid.'正在支付 ';//.$p5_Pid;
		$awoketime = $timestamp+3600;
		$sql_remark = "insert into {$dbTablePre}admin_remark set sid='{$sid}',title='{$title}',content='{$title}',awoketime='{$awoketime}',dateline='{$timestamp}'";
		$res = $_MooClass['MooMySQL']->query($sql_remark); 
    }
    
    
    $p4_Cur='CNY';
    $p6_Pcat = iconv("GB2312","UTF-8",'真爱一生网服务');
    $p7_Pdesc = iconv("GB2312","UTF-8",'会员升级认证');
	
    $p8_Url=$payment_code['yeepay']['p8_Url'];
    $p9_SAF=0;
    $pd_FrpId=$bank;
    $pr_NeedResponse = 1;   //启用应答机制
    $merchantKey=$payment_code['yeepay']['merchantKey'];


    include "./module/payment/yeepaycommon.php";
	
    //note 调用签名函数生成签名串
    $hmac = getReqHmacString($p0_Cmd,$p1_MerId,$p2_Order,$p3_Amt,$p4_Cur,$p5_Pid,$p6_Pcat,$p7_Pdesc,$p8_Url,$p9_SAF,$pa_MP,$pd_FrpId,$pr_NeedResponse,$merchantKey);

    $jump ="<html>
<head>
<title>please wait...</title>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
</head>
<body onLoad='document.yeepay.submit();'>
<form name='yeepay' action='https://www.yeepay.com/app-merchant-proxy/node' method='post'>
<input type='hidden' name='p0_Cmd'                  value='".$p0_Cmd."'>
<input type='hidden' name='p1_MerId'                value='".$p1_MerId."'>
<input type='hidden' name='p2_Order'                value='".$p2_Order."'>
<input type='hidden' name='p3_Amt'                  value='".$p3_Amt."'>
<input type='hidden' name='p4_Cur'                  value='".$p4_Cur."'>
<input type='hidden' name='p5_Pid'                  value='".$p5_Pid."'>
<input type='hidden' name='p6_Pcat'                 value='".$p6_Pcat."'>
<input type='hidden' name='p7_Pdesc'                value='".$p7_Pdesc."'>
<input type='hidden' name='p8_Url'                  value='".$p8_Url."'>
<input type='hidden' name='p9_SAF'                  value='0'>
<input type='hidden' name='pa_MP'                       value='".$pa_MP."'>
<input type='hidden' name='pd_FrpId'                value='".$pd_FrpId."'>
<input type='hidden' name='pr_NeedResponse' value='".$pr_NeedResponse."'>
<input type='hidden' name='hmac'                        value='".$hmac."'>
</form>
</body>
</html>";

echo $jump;

}

//note 线上支付(支付宝，财付通，易宝）
function payment_onlinepay(){
    global $_MooClass,$dbTablePre,$uid,$payment_code,$user_arr,$paymoney,$timestamp;
    
    if(empty($uid)) header("login.html");
    $flag=true;
    $res_sid = $_MooClass['MooMySQL']->getOne("select sid from {$dbTablePre}members_search where uid='{$uid}'",true);
    $sid = $res_sid['sid'];
    
    $channel=MooGetGPC('channel', 'string','P');
    
    $pay_type = array('pay_validateID','pay','pay_diamond','city_star','pay_add_money','pay_add_money_other','pay_platinum');
    $time = time();
    $order=array();
    if(!in_array($channel,$pay_type)){
        MooMessage('您选择的服务有误。','index.php?n=payment');
    }
    
	switch ($channel){
        case 'pay':    //note 高级会员
			$order['order_amount']=$paymoney['vip'];
			$order['order_type']='1';//0钻石会员 1高级会员 2城市之星
			$order['subject']="真爱一生网高级会员";
			$order['memo']="您即将升级成为{$order['subject']}，3个月服务费： {$order['order_amount']}";
			break;
        case 'city_star':
			$order['order_amount']=$paymoney['citystar'];
			$order['order_type']='2';
			$order['subject']="真爱一生网城市之星";
			$order['memo']="您即将升级成为{$order['subject']}，1个月服务费： {$order['order_amount']}";
			break;
        case 'pay_diamond':
			$order['order_amount']=$paymoney['diamond'];
			$order['order_type']='0';
			$order['subject']="真爱一生网钻石会员";
			$order['memo']="您即将升级成为{$order['subject']}，6个月服务费： {$order['order_amount']}";
			break;
        case 'pay_add_money':
			$order['order_amount']=$paymoney['add_money'];
			$order['order_type']=5;
			$order['subject']="高级会员升级为钻石会员";
			$order['memo']="您即将从真爱一生网的{$order['subject']}，3个月服务费： {$order['order_amount']}";
			break;
        case 'pay_add_money_other':
	    	$p3_Amt= MooGetGPC('p3_Amt','integer','P');
			$order['subject']= MooGetGPC('text','string','P');
			$order['order_amount']=$p3_Amt;
			$order['order_type']=6;
			break;
        case 'pay_platinum':
			$order['order_amount']=$paymoney['platinum'];
			$order['order_type']='-1';
			$order['subject']="真爱一生网铂金会员";
			$order['memo']="您即将升级成为{$order['subject']}，6个月服务费： {$order['order_amount']}";
			break;
		case 'pay_validateID':
			$order['order_amount']=5.00;
			$order['order_type']='100';
			$order['subject']="身份通认证";
			$flag=false;
			break;
		default:    //note 高级会员
			$order['order_amount']=$paymoney['vip'];
			$order['order_type']='1';//0钻石会员 1高级会员 2城市之星
			$order['subject']="真爱一生网高级会员";
			$order['memo']="您即将升级成为{$order['subject']}，3个月服务费： {$order['order_amount']}";
			break;
    }
    
	//$title=iconv('utf-8', 'gbk',$order['subject']);
    $title=$order['subject'];
	$memo=$order['memo'];
	$pay=MooGetGPC('pay', 'string','P');

	if($pay=='zhifubao'){
	    //$url="https://shenghuo.alipay.com/send/payment/fill.htm?optEmail=1557334568@qq.com&payAmount={$order['order_amount']}&title={$order['subject']}&smsNo=15156018341&isSend=true";
	    echo "<script> 
		document.write('<meta  charset=\"gbk\" />');
		document.write('<form action=\"https://shenghuo.alipay.com/send/payment/fill.htm\" method=\"post\" name=\"formzfb\" style=\"display:none\" accept-charset=\"gbk\" onsubmit=\"document.charset=gbk\" >');
		document.write('<input type=\"hidden\" name=\"optEmail\" value=\"1557334568@qq.com\" />');
		document.write('<input type=\"hidden\" name=\"payAmount\" value=\"{$order['order_amount']}\" />');
		document.write('<input type=\"hidden\" name=\"title\" value=\"{$title}\" />');
		document.write('<input type=\"hidden\" name=\"memo\" value=\"{$memo}\" />');
		document.write('<input type=\"hidden\" name=\"smsNo\" value=\"15156018341\" />');
		document.write('<input type=\"hidden\" name=\"isSend\" value=\"true\" />');
		
		document.write('</form>');
		document.formzfb.submit();
		</script>";

	}elseif($pay=='tenpay'){
	    echo "<script> 
		document.write('<form action=\"https://www.tenpay.com/v2/account/pay/index.shtml\" method=\"post\" name=\"formtenpay\" style=\"display:none\">');
		document.write('</form>');
		document.formtenpay.submit();
	    </script>";
	}elseif($pay=='yeepay'){
	
		$order['order_sn']=date('YmdHms',$time).$uid;   
		$payment=get_payment($pay);
		$plugin_file ="module/payment/".$pay.".php";
		// echo $plugin_file;exit;
        if (file_exists($plugin_file)){
        	
            /* 根据支付方式代码创建支付类的对象并调用其响应操作方法 */
            include_once($plugin_file);
            
            $pays = new $pay();
            
            $order['order_amount']+= (float)$payment['m_fee']*$order['order_amount']/100;
            $order['order_amount']=round($order['order_amount'],2); //支付金额保留两位小数
           
            if($order['order_type']=='6' || $order['order_type']='100'){
				 $sql="insert into {$dbTablePre}payment_other(uid,pay_type,pay_bank,order_id,pay_money,pay_service,apply_sid,apply_time,contact,note) values('{$uid}','2','{$pay}','{$order['order_sn']}','{$order['order_amount']}','{$order['order_type']}','{$sid}','{$time}','{$user_arr['telphone']}','{$order['subject']}')";
			}else{
                $sql="insert into {$dbTablePre}payment_new (uid,pay_type,pay_bank,order_id,pay_money,pay_service,apply_sid,apply_time,contact,note) values('{$uid}','2','{$pay}','{$order['order_sn']}','{$order['order_amount']}','{$order['order_type']}','{$sid}','{$time}','{$user_arr['telphone']}','{$order['subject']}')";
            }
            
            $_MooClass['MooMySQL']->query($sql);
			
			
            $o=get_pay_one($order['order_sn']);
            
            $order['log_id']=$o['pid'];
            $reqUrl= $pays->get_code($order,$payment);
            
            
        }
        //echo $reqUrl.'ddd';exit;
        if(!empty($reqUrl)){
            if($pay!='yeepay'){
                echo '<script language="JavaScript">self.location=\''.$reqUrl.'\';</script>';
            }else{
                echo $reqUrl;
            }
            
            //*********真爱一生备注*********
		    $sid = $user_arr['sid'];
		    $title = '您的会员'.$uid.'正在支付 ';//$order['subject']
		    $awoketime = $timestamp+3600;
		    $sql_remark = "insert into {$dbTablePre}admin_remark set sid='{$sid}',title='{$title}',content='{$title}',awoketime='{$awoketime}',dateline='{$timestamp}'";
		    $res = $_MooClass['MooMySQL']->query($sql_remark);
		    //**********end**********
    
        }else{
            MooMessage('您的请求有误请重新提交请求。','index.php?n=payment');
        }
	}
}

//note 网上刷卡支付
function payment_creditcardinline(){
    global $_MooClass,$dbTablePre,$uid,$pay_sty,$paymoney,$payment_code,$user_arr,$timestamp;
    
    if(empty($uid)) header("login.html");
    
    
    $res_sid = $_MooClass['MooMySQL']->getOne("select sid from {$dbTablePre}members_search where uid='{$uid}'",true);
    $sid = $res_sid['sid'];

    $ispost=MooGetGPC('ispost', 'integer','P');
    $channel=MooGetGPC('channel', 'string','P');
	
	$p3_Amt=MooGetGPC('p3_Amt', 'int','P');//其他支付金额
	$text=MooGetGPC('text', 'string','P'); //其他支付明细
	
    $pay=MooGetGPC('pay', 'string','P');
    $pay_type = array('pay','pay_diamond','city_star','pay_add_money','pay_add_money_other','pay_platinum','pay_sliver','pay_xuanyan','pay_eyes','pay_perfume','pay_azlt','pay_jiaren','pay_wink','pay_sing','pay_fakeface','pay_gourd','pay_hand','pay_spa','pay_yz','pay_ruby','pay_cs','pay_midsummer','pay_dusk','pay_rlsl');
    $time = time();
    $year = date("Y");
    $order=array();
    if(!in_array($channel,$pay_type)){
        MooMessage('您选择的服务有误。','index.php?n=payment');
        exit;
    }
    if(empty($pay)){
        if($channel=='pay_diamond'){
            MooMessage('请选择支付方式。','index.php?n=payment&h=channel_diamond');
            exit;
        }elseif($channel=='city_star'){
            MooMessage('请选择支付方式。','index.php?n=payment&h=city_star');
            exit;
        }elseif($channel=='pay_add_money'){
            MooMessage('请选择支付方式。','index.php?n=payment&h=add_money');
            exit;
        }elseif($channel=='pay_add_money_other'){
            MooMessage('请选择支付方式。','index.php?n=payment&h=add_money_2');
            exit;
        }elseif($channel=='pay_platinum'){
            MooMessage('请选择支付方式。','index.php?n=payment&h=channel_platinum');
            exit;
        }else{
            MooMessage('请选择支付方式。','index.php?n=payment&h=channel');
            exit;
        }
    }

    if($channel=='pay'){    //note 高级会员
        $order['order_amount']=$paymoney['vip'];
        $order['order_type']='1';//0钻石会员 1高级会员 2城市之星
        $order['subject']="真爱一生网高级会员";
    }elseif($channel=='city_star'){
        $order['order_amount']=$paymoney['citystar'];
        $order['order_type']='2';
        $order['subject']="真爱一生网城市之星";
    }elseif($channel=='pay_diamond'){
        $order['order_amount']=$paymoney['diamond'];
        $order['order_type']='0';
        $order['subject']="真爱一生网钻石会员";
    }elseif($channel=='pay_add_money'){
        $order['order_amount']=$paymoney['add_money'];
        $order['order_type']=5;
        $order['subject']="高级会员升级钻石会员";
    }elseif($channel=='pay_add_money_other'){
        $order['order_amount']=$p3_Amt;
        $order['order_type']=6;
        $order['subject']="补款或预付";
    }elseif($channel=='pay_platinum'){
        $order['order_amount']=$paymoney['platinum'];
        $order['order_type']='-1';
        $order['subject']="真爱一生网铂金会员";
    }
   
    
    if($ispost=='1'){
        require_once 'yeepayeposcommon.php';
        $payment=get_payment($pay);
        $haystack=array('ECITICCREDIT','ICBCCREDIT','BOCOCREDIT','BOSHCREDIT','BOCCREDIT','CMBCCREDIT','GDBCREDIT','CIBCREDIT','CCBCREDIT','CMBCHINACREDIT','ABCCREDIT','PINGANREDIT','BCCBCREDIT');
        // 业务类型
        $p0_Cmd=$payment['epossale'];
        // 交易币种
        $p4_Cur=$payment['cny'];
        // 是否需要应答
        $pr_NeedResponse=$payment['needresponse'];
        // 商户编号
        $p1_MerId=$payment['p1_MerId'];
        // 商户密钥,用于生成hmac(hmac的说明详见文档)key为测试
        $merchantKey=$payment['merchantKey'];
        // 正式地址
        $actionURL=$payment['actionURL'];
        // 获取订单号
        $p2_Order=date('YmdHms',$time).$uid;
        // 获取本地提交消费金额
        $p3_Amt=$order['order_amount'];
        // 获取本地提交商品名称
        $p5_Pid=$order['subject'];
        $p5_Pid=iconv("GB2312","UTF-8",$p5_Pid);
        // 获取本地提交接收支付结果地址
        $p8_Url=$payment['p8_Url'];
        // 获取本地提交证件类型
        $pa_CredType=MooGetGPC('pa_CredType', 'string','P');
        // 获取本地提交证件号码
        $pb_CredCode=MooGetGPC('pb_CredCode', 'string','P');
        $pb_CredCode=iconv("GB2312","UTF-8",$pb_CredCode);
        // 获取本地提交银行编码
        $pd_FrpId=MooGetGPC('pd_FrpId', 'string','P');
        $pd_FrpId=trim($pd_FrpId);
        // 获取本地提交消费者手机号
        $pe_BuyerTel=MooGetGPC('pe_BuyerTel', 'string','P');
        // 获取本地提交消费者姓名
        $pf_BuyerName=MooGetGPC('pf_BuyerName', 'string','P');
        $pf_BuyerName=iconv("GB2312","UTF-8",$pf_BuyerName);
        // 获取本地提交信用卡卡号
        $pt_ActId=MooGetGPC('pt_ActId', 'string','P');
        // 获取本地提交有效期（年）
        $pa2_ExpireYear=MooGetGPC('pa2_ExpireYear', 'string','P');
        // 获取本地提交有效期（月）
        $pa3_ExpireMonth=MooGetGPC('pa3_ExpireMonth', 'string','P');
        // 获取本地提交信用卡CVV码
        $pa4_CVV=MooGetGPC('pa4_CVV', 'string','P');
		
		
		/* $p3_Amt=MooGetGPC('p3_Amt', 'int','P');
		$text=MooGetGPC('text', 'string','P'); */
		
		
        if($pa_CredType==''||$pb_CredCode==''||$pd_FrpId==''||$pe_BuyerTel==''||$pf_BuyerName==''||$pt_ActId==''||$pa2_ExpireYear==''||$pa3_ExpireMonth==''||$pa4_CVV==''){
            if($channel=='pay_diamond'){
                MooMessage('您有个选项没有填写！','index.php?n=payment&h=channel_diamond');
            }elseif($channel=='city_star'){
                MooMessage('您有个选项没有填写！','index.php?n=payment&h=city_star');
            }elseif($channel=='add_money'){
                MooMessage('您有个选项没有填写！','index.php?n=payment&h=add_money');
            }elseif($channel=='add_money_2'){
                MooMessage('您有个选项没有填写！','index.php?n=payment&h=add_money_2');
            }elseif($channel=='pay_platinum'){
                MooMessage('您有个选项没有填写！','index.php?n=payment&h=channel_platinum');
            }else{
                MooMessage('您有个选项没有填写！','index.php?n=payment&h=channel');
            }
            exit;
        }
        
        if(!in_array($pd_FrpId,$haystack)){
            if($channel=='pay_diamond'){
                MooMessage('请输入对应的发卡行英文代号！','index.php?n=payment&h=channel_diamond');
            }elseif($channel=='city_star'){
                MooMessage('请输入对应的发卡行英文代号！','index.php?n=payment&h=city_star');
            }elseif($channel=='add_money'){
                MooMessage('请输入对应的发卡行英文代号！','index.php?n=payment&h=add_money');
            }elseif($channel=='add_money_2'){
                MooMessage('请输入对应的发卡行英文代号！','index.php?n=payment&h=add_money_2');
            }elseif($channel=='pay_platinum'){
                MooMessage('请输入对应的发卡行英文代号！','index.php?n=payment&h=channel_platinum');
            }else{
                MooMessage('请输入对应的发卡行英文代号！','index.php?n=payment&h=channel');
            }
            exit;
        }

        //校验身份证号码
        if($pa_CredType=='IDCARD'){
            if(!validation_filter_id_card($pb_CredCode)){
                if($channel=='pay_diamond'){
                    MooMessage('您填写的身份证号码不正确！','index.php?n=payment&h=channel_diamond');
                }elseif($channel=='city_star'){
                    MooMessage('您填写的身份证号码不正确！','index.php?n=payment&h=city_star');
                }elseif($channel=='add_money'){
                    MooMessage('您填写的身份证号码不正确！','index.php?n=payment&h=add_money');
                }elseif($channel=='add_money_2'){
                    MooMessage('您填写的身份证号码不正确！','index.php?n=payment&h=add_money_2');
                }elseif($channel=='pay_platinum'){
                    MooMessage('您填写的身份证号码不正确！','index.php?n=payment&h=channel_platinum');
                }else{
                    MooMessage('您填写的身份证号码不正确！','index.php?n=payment&h=channel');
                }
                exit;
            }
        }
        //校验手机号码
        if(!preg_match("/^(((13[0-9]{1})|14[0-9]{1}|15[0-9]{1}|18[0-9]{1}|)+\d{8})$/",$pe_BuyerTel)){
            if($channel=='pay_diamond'){
                MooMessage('您填写的手机号码不正确！','index.php?n=payment&h=channel_diamond');
            }elseif($channel=='city_star'){
                MooMessage('您填写的手机号码不正确！','index.php?n=payment&h=city_star');
            }elseif($channel=='add_money'){
                MooMessage('您填写的手机号码不正确！','index.php?n=payment&h=add_money');
            }elseif($channel=='add_money_2'){
                MooMessage('您填写的手机号码不正确！','index.php?n=payment&h=add_money_2');
            }elseif($channel=='pay_platinum'){
                MooMessage('您填写的手机号码不正确！','index.php?n=payment&h=channel_platinum');
            }else{
                MooMessage('您填写的手机号码不正确！','index.php?n=payment&h=channel');
            }
            exit;
        }
        if(!($pa2_ExpireYear>=$year&&$pa2_ExpireYear<=2099)){
            if($channel=='pay_diamond'){
                MooMessage('您填写的信用卡有效期（年）不正确！','index.php?n=payment&h=channel_diamond');
            }elseif($channel=='city_star'){
                MooMessage('您填写的信用卡有效期（年）不正确！','index.php?n=payment&h=city_star');
            }elseif($channel=='add_money'){
                MooMessage('您填写的信用卡有效期（年）不正确！','index.php?n=payment&h=add_money');
            }elseif($channel=='add_money_2'){
                MooMessage('您填写的信用卡有效期（年）不正确！','index.php?n=payment&h=add_money_2');
            }elseif($channel=='pay_platinum'){
                MooMessage('您填写的信用卡有效期（年）不正确！','index.php?n=payment&h=channel_platinum');
            }else{
                MooMessage('您填写的信用卡有效期（年）不正确！','index.php?n=payment&h=channel');
            }
            exit;
        }
        if(!($pa3_ExpireMonth>=01&&$pa3_ExpireMonth<=12)){
            if($channel=='pay_diamond'){
                MooMessage('您填写的信用卡有效期（月）不正确！','index.php?n=payment&h=channel_diamond');
            }elseif($channel=='city_star'){
                MooMessage('您填写的信用卡有效期（月）不正确！','index.php?n=payment&h=city_star');
            }elseif($channel=='add_money'){
                MooMessage('您填写的信用卡有效期（月）不正确！','index.php?n=payment&h=add_money');
            }elseif($channel=='add_money_2'){
                MooMessage('您填写的信用卡有效期（月）不正确！','index.php?n=payment&h=add_money_2');
            }elseif($channel=='pay_platinum'){
                MooMessage('您填写的信用卡有效期（月）不正确！','index.php?n=payment&h=channel_platinum');
            }else{
                MooMessage('您填写的信用卡有效期（月）不正确！','index.php?n=payment&h=channel');
            }
            exit;
        }
        if(!(strlen($pa4_CVV)==3||strlen($pa4_CVV)==4)){
            if($channel=='pay_diamond'){
                MooMessage('请输入信用卡背面的3或4位cvv码！','index.php?n=payment&h=channel_diamond');
            }elseif($channel=='city_star'){
                MooMessage('请输入信用卡背面的3或4位cvv码！','index.php?n=payment&h=city_star');
            }elseif($channel=='add_money'){
                MooMessage('请输入信用卡背面的3或4位cvv码！','index.php?n=payment&h=add_money');
            }elseif($channel=='add_money_2'){
                MooMessage('请输入信用卡背面的3或4位cvv码！','index.php?n=payment&h=add_money_2');
            }elseif($channel=='pay_platinum'){
                MooMessage('请输入信用卡背面的3或4位cvv码！','index.php?n=payment&h=channel_platinum');
            }else{
                MooMessage('请输入信用卡背面的3或4位cvv码！','index.php?n=payment&h=channel');
            }
            exit;
        }
        // 日志文件路径
        $logName=$payment['logyeepayepos'];
        // 构造请求业务处理所需参数数组
        $bizArray=Array('p0_Cmd'=>$p0_Cmd,'p1_MerId'=>$p1_MerId,'p2_Order'=>$p2_Order,'p3_Amt'=>$p3_Amt,'p4_Cur'=>$p4_Cur,'p5_Pid'=>$p5_Pid,'p8_Url'=>$p8_Url,'pa_CredType'=>$pa_CredType,'pb_CredCode'=>$pb_CredCode,'pd_FrpId'=>$pd_FrpId,'pe_BuyerTel'=>$pe_BuyerTel,'pf_BuyerName'=>$pf_BuyerName,'pt_ActId'=>$pt_ActId,'pa2_ExpireYear'=>$pa2_ExpireYear,'pa3_ExpireMonth'=>$pa3_ExpireMonth,'pa4_CVV'=>$pa4_CVV);
        
        /*-------------------------------------------------------*/
        // 接入程序员关注部分（此函数可选择使用）   
        // 调用您的业务逻辑处理函数，比如：将商品状态改为“下单”
        if($user_arr['s_cid'] =='30' && $order['order_type']=='1'){
            $sql="insert into {$dbTablePre}payment_new (uid,pay_type,pay_bank,order_id,status,pay_money,pay_service,apply_sid,apply_time,contact) values('{$uid}','2','{$pay}','{$p2_Order}','0','{$order['order_amount']}','1','{$sid}','{$time}','{$user_arr['telphone']}')";
        }else if($order['order_type']=='6'){
			$sql="insert into {$dbTablePre}payment_other (uid,pay_type,pay_bank,order_id,status,pay_money,pay_service,apply_sid,apply_time,contact,note) values('{$uid}','2','{$pay}','{$p2_Order}','0','{$order['order_amount']}','6','{$sid}','{$time}','{$user_arr['telphone']}','{$text}')";
		}else{
            $sql="insert into {$dbTablePre}payment_new (uid,pay_type,pay_bank,order_id,status,pay_money,pay_service,apply_sid,apply_time,contact) values('{$uid}','2','{$pay}','{$p2_Order}','0','{$order['order_amount']}','{$order['order_type']}','{$sid}','{$time}','{$user_arr['telphone']}')";
        }
        $_MooClass['MooMySQL']->query($sql);
        /*-------------------------------------------------------*/
        
        
        //*********真爱一生备注*********
         $sid = $user_arr['sid'];
        $title = '您的会员'.$uid.'正在支付';//.$order['subject'];
        $awoketime = $timestamp+3600;
        $sql_remark = "insert into {$dbTablePre}admin_remark set sid='{$sid}',title='{$title}',content='{$title}',awoketime='{$awoketime}',dateline='{$timestamp}'";
        $res = $_MooClass['MooMySQL']->query($sql_remark); 
        //**********end**********
        // 调用支付业务函数，接入程序员无需关注
        eposSale($bizArray,$actionURL,$merchantKey,$logName);
        exit;
    }else{
        include MooTemplate('public/fillintheinformation', 'module');
    }
}

/*
测试参数
$paytime = date("Y.m.d H:i:s");
	$bank_type=urlencode('易宝支付');
	$pay = array('pay' => '1', //选择模板
		'out_trade_no' => 'w34234', //订单号
		'paytime' => $paytime, //支付时间
		'bank_type' => $bank_type, //支付银行类型
		'trade_state' => 0, //支付状态
		'get_img' => '05.gif', //01-错误图片   05-正确图片
		'img' => 1, //图片类型 0钻石会员 1高级会员 2城市之星
		'total_fee' => 1899, //支付价格
		'uid' => $uid,
		'yeepay'=>'yeepay');         //会员ID

	$pay=array_values($pay);
*/
//note 调用 支付成功接口
function payment_payreturnurl(){
    global $_MooClass,$dbTablePre,$uid,$pay_sty,$payment_code,$user_arr,$tenpay_banktype,$timestamp;
    $code=MooGetGPC('payurl', 'string','G');
    $pay = explode(',',$code);
    if($pay['0'] == '1' || $pay['0'] == '2'){
        $uid = $pay['8'];
        $sql = "SELECT nickname,telphone FROM {$dbTablePre}members_search WHERE uid='{$uid}'";
        $res = $_MooClass['MooMySQL']->getOne($sql,true);
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
            $title = '您已成功升级为钻石会员';
        }elseif($pay['6'] == '1'){
            $title = '您已成功升级为高级会员';
        }elseif($pay['6'] == '2'){
            $title = '您已成功升级为城市之星';
        }elseif($pay['6'] == '-1'){
            $title = '您已成功升级为铂金会员';
        }elseif($pay['6']=='100'){
            $title = '您已经成功通过身份通认证';
        }elseif($pay['6']=='6'){
		    $title = '您已经成功预付或补款';
		}
		
        $payurl = array('pay'=>$pay['0'],   //选择模板
            'out_trade_no'=>$pay['1'],      //订单号
            'paytime'=>$pay['2'],               //交易时间
            'bank_type'=>$bank_type,        //银行类型
            'trade_state'=>$pay['4'],           //付款状态   0-成功，1-失败
            'get_img'=>'module/payment/templates/default/images/'.$pay['5'],                //显示哪种图片 01-错误图片   05-正确图片
            'total_fee'=>$pay['7'],             //价格
            'uid'=>$uid,                            //UID
            'nickname'=>$res['nickname'],    //昵称
            'title'=>$title);                       //高级会员还是钻石会员
    }else{
        $payurl = array('pay'=>$pay['0'], 
        'errorinfo'=>$pay['1'],  //错误信息
        'trade_state'=>$pay['2'],   //付款状态 0-成功 1失败
        'get_img'=>'module/payment/templates/default/images/01.gif');
    }
    
    if($payurl['pay'] == '1' || $payurl['pay'] == '2'){
        //付款之后跳转页面1-成功 2-失败
        if($payurl['pay']=='1'){
        	//*********真爱一生备注*********
	        $sid = $user_arr['sid'];
	        $title = '您的会员'.$user_arr['uid'].'支付成功！';//.$order['subject'];
	        $awoketime = $timestamp+3600;
	        $sql_remark = "insert into {$dbTablePre}admin_remark set sid='{$sid}',title='{$title}',content='{$title}',awoketime='{$awoketime}',dateline='{$timestamp}'";
	        $res = $_MooClass['MooMySQL']->query($sql_remark);
	        //**********end**********
        }
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
    if(isset($user_arr['s_cid']) && $user_arr['s_cid'] == '30' && $user_arr['endtime'] >= $GLOBALS['timestamp']){
        $s_cid = '30'; //get_user_s_cid();
    }
    include MooTemplate('public/payment_heigher', 'module');
}

//升级为钻石会员
function payment_diamond(){
    global $user_arr;
    $s_cid = 40;
    if(isset($user_arr['s_cid']) && $user_arr['s_cid'] == '20' && $user_arr['endtime'] >= $GLOBALS['timestamp']){
        $s_cid = '20'; //get_user_s_cid();
    }
    include MooTemplate('public/payment_diamond', 'module');
}

//升级为铂金会员
function payment_platinum(){
    global $user_arr;
    $s_cid = 40;
    if(isset($user_arr['s_cid']) && $user_arr['s_cid'] == '10' && $user_arr['endtime'] >= $GLOBALS['timestamp']){
        $s_cid = '10'; //get_user_s_cid();
    }
    include MooTemplate('public/payment_platinum', 'module');
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
    include MooTemplate('public/payment_cityStarIntro', 'module');
}

function payIntroduce(){
  include MooTemplate('public/payIntroduce', 'module');

}


function payDetail(){
  global $uid;
  if(!$uid) header("login.html");
  include MooTemplate('public/payDetail', 'module');

}


/***************************************   控制层(C)   ****************************************/
$p3_Amt='';
$text='';
$h = MooGetGPC('h', 'string');
$back_url='index.php?'.$_SERVER['QUERY_STRING'];
switch ($h) {
    case 'channel':
        payment_channel();
        break;
    case 'channel_diamond':
        payment_channel_diamond();
        break;
    case 'add_money':
        payment_add_money();
        break;
	case 'add_money_other':
		$p3_Amt = MooGetGPC ('money', 'int', 'P');
		$text = MooGetGPC ('text', 'string', 'P');
		payment_add_money_other($p3_Amt,$text);
	    break;
    case 'diamond':
        payment_diamond();
        break;
    case 'platinum':
        payment_platinum();
        break;
    case 'channel_platinum':
        payment_channel_platinum();
        break;
	case 'channel_cs':
        payment_channel_cs();
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
    case 'onlinebank':
        payment_bank();
        break;
    case 'onlinepay':
        payment_onlinepay();
        break;
    case 'creditcardinline':
        payment_creditcardinline();
        break;
	case 'validateID':
	    payment_validateID();
		break;
	case 'telpay': //电话支付
	   $cardType=MooGetGPC('cardType','string','P');//卡类型，借记卡或信用卡

	   if($cardType==1){//信用卡提交
	   	  $creditName=MooGetGPC('creditName','string','P');
	   	  $creditIDCard='01'.MooGetGPC('creditIDCard','string','P');
	   	  $creditCard=MooGetGPC('creditCard','string','P');
	   	  $creditTelphone=MooGetGPC('creditTelphone','string','P');
		  $creditBank=MooGetGPC('creditBank','string','P');
	   	  
	   	  telPay($creditIDCard,$creditTelphone,$creditCard,$creditBank,$creditName);
	   }
	   
       if($cardType==2){//借记卡提交
	   	  $depositIDCard='01'.MooGetGPC('depositIDCard','string','P');
	   	  $depositCard=MooGetGPC('depositCard','string','P');
	   	  $depositTelphone=MooGetGPC('depositTelphone','string','P');
		  $depositBank=MooGetGPC('depositBank','string','P');
	   	  
	   	  telPay($depositIDCard,$depositTelphone,$depositCard,$depositBank);
	   }
       break;
	   
	 case 'telPayAjax': //
	    telPayAjax();
		break;
	 case 'introduce':
	    payIntroduce();
		break;
	 case 'payDetail':
	    payDetail();
		break;

	 default:
        payment_heigher();
        break;
 }
	


?>

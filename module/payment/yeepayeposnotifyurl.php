<?php
/*
 * @Title 易宝支付EPOS范例
 * @Description 用户支付后易宝"点对点"访问此页面，商户在本文件中加入自身业务
 * @Author  wenhua.cheng
 */
require_once dirname(__FILE__).'/./../../framwork/MooPHP.php';
require_once dirname(__FILE__).'/./config.php';
require_once dirname(__FILE__).'/./function.php';
require_once dirname(__FILE__).'/./yeepayeposcommon.php';	
//define("INFO",dirname(__FILE__)."/./info.txt");
//define("INFOW",dirname(__FILE__)."/./infow.txt");
//define("INFOMATION",dirname(__FILE__)."/./infomation.txt");


global $payment_code,$paymoney,$paymoney2,$activitytime1,$activitytime2;
$logName = $payment_code['yeepayepos']['logyeepayepos'];
$merchantKey = $payment_code['yeepayepos']['merchantKey'];

$allow_ip = array('220.178.112.174','61.190.44.98','127.0.0.1','220.178.123.74','221.130.166.242','120.193.108.166','61.190.22.14','61.190.10.254','124.73.152.192');
$cur_ip = GetIP();
if(in_array($cur_ip,$allow_ip)){
	$paymoney = array(
	    'platinum'=>'0.10',  //六个月铂金会员
		'diamond'=>'0.10',   //六个月钻石会员费用
		'vip'=>'0.10',			  //三个月高级会员费用
		'citystar'=>'0.10',     //一个月城市之星费用
	);
}else{
	if(strpos($cur_ip,'192.168') !== FALSE){
		$paymoney = array(
		    'platinum'=>'0.10',   //六个月铂金会员
			'diamond'=>'0.10',   //六个月钻石会员费用
			'vip'=>'0.10',			  //三个月高级会员费用
			'citystar'=>'0.10',     //一个月城市之星费用
		);
	}
}
// print_r($paymoney);die;

// 支付成功时返回的参数
$p1_MerId=$_GET['p1_MerId'];
$r0_Cmd=$_GET['r0_Cmd'];
$r1_Code=$_GET['r1_Code'];
$r2_TrxId=$_GET['r2_TrxId'];
$r3_Amt=$_GET['r3_Amt'];
$r4_Cur=$_GET['r4_Cur'];
$r5_Pid=$_GET['r5_Pid'];
$r6_Order=$_GET['r6_Order'];
$r7_Uid=$_GET['r7_Uid'];
$r8_MP=$_GET['r8_MP'];
$r9_BType=$_GET['r9_BType'];
$rb_BankId=$_GET['rb_BankId'];
$ro_BankOrderId=$_GET['ro_BankOrderId'];
$rp_PayDate=$_GET['rp_PayDate'];
$ru_Trxtime=$_GET['ru_Trxtime'];
$hmac=$_REQUEST['hmac'];
// 支付失败时返回的参数
$rp_TrxDate=$_GET['rp_TrxDate'];
$rp_Msg=$_GET['rp_Msg'];
// 构造支付结果验证参数数组
$successCallBack=Array('p1_MerId'=>$p1_MerId,'r0_Cmd'=>$r0_Cmd,'r1_Code'=>$r1_Code,'r2_TrxId'=>$r2_TrxId,'r3_Amt'=>$r3_Amt,'r4_Cur'=>$r4_Cur,'r5_Pid'=>$r5_Pid,'r6_Order'=>$r6_Order,'r7_Uid'=>$r7_Uid,'r8_MP'=>$r8_MP,'r9_BType'=>$r9_BType);

$failCallBack=Array('p1_MerId'=>$p1_MerId,'r0_Cmd'=>$r0_Cmd,'r1_Code'=>$r1_Code,'r2_TrxId'=>$r2_TrxId,'r3_Amt'=>$r3_Amt,'r6_Order'=>$r6_Order,'rp_TrxDate'=>$rp_TrxDate);

// 回写success，通知易宝支付商户已收到点对点响应
//echo "success";
//在接收到支付结果通知后，判断是否进行过业务逻辑处理，不要重复进行业务逻辑处理
// 支付成功.可以调用您的业务逻辑，您无需关心易宝接口如何工作
if ($r0_Cmd=="Buy" && $r1_Code=="1") {
	if (verifyCallback($successCallBack,$hmac,$logName,$merchantKey)){
	/*--------------------------------------------------------------------------------------*/
	// 接入程序员关注部分
	// 调用您的业务逻辑处理函数，比如：更新商户数据库的商品是否发货的状态，进行商品价格校验等

	//处理数据库逻辑
	//注意交易单不要重复处理
	//注意判断返回金额
	$uid = substruid($r6_Order, 14);

	//更新支付状态
	$sql_getpayid = "SELECT max(id) id FROM {$dbTablePre}payment_new WHERE order_id='{$r6_Order}'";
	$getpayid = $_MooClass['MooMySQL']->getOne($sql_getpayid,true);
	$id = $getpayid['id'];
	
	$sql_p = "SELECT pay_service FROM {$dbTablePre}payment_new WHERE id='{$id}'";
	$payservice = $_MooClass['MooMySQL']->getOne($sql_p,true);
	
	$bgtime = time();
	$apply_note = '';
	$s_cid = '';
	$total_money = '';
	$total_fee_res = $r3_Amt;
	$confirm=array();
	
	$attach='';
    
	if($payservice['pay_service']=='-1'){
        $attach='-1';
    }elseif($payservice['pay_service']=='0'){
		$attach='0';
	}elseif($payservice['pay_service']=='1'||$payservice['pay_service']=='3'){
		$attach='1';
	}elseif($payservice['pay_service']=='2'){
		$attach='2';
	}elseif($payservice['pay_serivce']=='5'){
	    $attach='5';
	}

	if ($attach == '0') {
		if ($bgtime >= strtotime($activitytime1) && $bgtime < strtotime($activitytime2)) {
			$total_money = $paymoney2['diamond'];
		} else {
			$total_money = $paymoney['diamond'];
		}
		$apply_note = '六个月钻石会员';
		$s_cid = '20';
	} elseif ($attach == '-1') {
        if ($bgtime >= strtotime($activitytime1) && $bgtime < strtotime($activitytime2)) {
            $total_money = $paymoney2['platinum'];
        } else {
            $total_money = $paymoney['platinum'];
        }
        $apply_note = '六个月铂金会员';
        $s_cid = '10';
    }elseif ($attach == '1') {
		if ($bgtime >= strtotime($activitytime1) && $bgtime < strtotime($activitytime2)) {
			$total_money = $paymoney2['vip'];
		} else {
			$total_money = $paymoney['vip'];
		}
		$apply_note = '三个月高级会员';
		$s_cid = '30';
	} elseif ($attach == '2') {
		$total_money = $paymoney['citystar'];
		$apply_note = '一个月城市之星';
	}elseif ($attach == '7') {
		$total_money = $paymoney['qixi'];
		$apply_note = '七夕报名费用';
	} elseif ($attach == '8') {
		$total_money = $paymoney['xuanyan'];
		$apply_note = '爱的宣言报名费用';
	} elseif($attach=='2') {
		$total_money = $paymoney['citystar'];
		$apply_note = '一个月城市之星';
	} elseif($attach=='5') {
		$total_money = $paymoney['add_money'];
		$apply_note = '高级会员升级钻石会员';
	}else{
	    $total_money = $r3_Amt;
	}
		//如果支付金额不正确 ——不改变会员状态
	if ($total_fee_res < $total_money) { //这里应该是不等于$total_fee_res != $total_money ，
		echo "success";
		exit;
	} 

	$bank_type = "易宝支付";
	$sql_pay = "UPDATE {$dbTablePre}payment_new SET pay_time='{$bgtime}',pay_info='{$bank_type}',apply_note='{$apply_note}',status='1',check_time='{$bgtime}' WHERE id='{$id}'";
	$_MooClass['MooMySQL']->query($sql_pay);
    
	$confirm=array();
	//确认更改状态成功
	$sql_com = "SELECT status FROM {$dbTablePre}payment_new WHERE id='{$id}'";
	$confirm = $_MooClass['MooMySQL']->getOne($sql_com,true);
	
	
	if(!empty($confirm)){
		//------------------------------
		//处理业务完毕
		//------------------------------
		if (($confirm['status'] == '1' || $confirm['status'] == '3')) {
			echo "success";exit;
		} else {
			echo "fail";exit;
		}
	}
	
	$confirm_pre=array();
	//========by hwt 2011-12-31 BEGIN==========
	$sql_prepay="select max(id) id from web_payment_other where order_id='{$r6_Order}'";
	$getPrePayId=$_MooClass['MooMySQL']->getOne($sql_prepay,true);
	if(!empty($getPrePayId)){
	   $PreId=$getPrePayId['id'];
	   $sql_prepay="update web_payment_other set status=1,pay_time='{$bgtime}' where id={$PreId}";
	   $_MooClass['MooMySQL']->query($sql_prepay);
	   
	   $sql_precom = "SELECT status FROM {$dbTablePre}payment_other WHERE id='{$PreId}'";
	   $confirm_pre = $_MooClass['MooMySQL']->getOne($sql_precom,true);
	   //------------------------------
		//处理业务完毕
		//------------------------------
		if ($confirm_pre['status'] == '1' ) {
			echo "success";
		} else {
			echo "fail";
		}
	}
	//============END=============================
	
	
	/*--------------------------------------------------------------------------------------*/
	$success = "支付成功！";
	$order = "订单号：".$r6_Order;
	$success = iconv("UTF-8","GB2312",$success);
	$order = iconv("UTF-8","GB2312",$order);
	logurl($success,$order,$logName);
		
	}
}elseif ($r0_Cmd=="EposFailed" && $r1_Code=="-100"){  // 支付失败
	$fail = "支付失败!";
	$order = "订单号：".$r6_Order;
	$fail = iconv("UTF-8","GB2312",$fail);
	$order = iconv("UTF-8","GB2312",$order);
	logurl($fail,$order,$logName);
	verifyCallback($failCallBack,$hmac,$logName,$merchantKey);
	echo "fail";

}else {
	$fail = "支付失败!";
	$fail = iconv("UTF-8","GB2312",$fail);
	$failinfo = "未知错误。请联系技术支持!";
	$failinfo = iconv("UTF-8","GB2312",$failinfo);
//	logurl($fail,$failinfo,$logName);
	echo "fail";	
}
?> 

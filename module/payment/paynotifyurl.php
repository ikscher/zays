<?php
/*
 *2010-11-20
 */
//---------------------------------------------------------
//财付通即时到帐支付后台回调示例，商户按照此文档进行开发即可
//---------------------------------------------------------
require_once dirname(__FILE__).'/./requesthandler.class.php';
require_once dirname(__FILE__).'/./responsehandler.class.php';
require_once dirname(__FILE__).'/./clientresponsehandler.class.php';
require_once dirname(__FILE__).'/./tenpayhttpclient.class.php';
require_once dirname(__FILE__).'/./../../framwork/MooPHP.php';

require_once dirname(__FILE__).'/./config.php';
require_once dirname(__FILE__).'/./function.php';
date_default_timezone_set("Asia/Shanghai");
global $payment_code,$paymoney;

$payment = $payment_code['tenpay'];

/* 商户号 */
$partner = $payment['tenpay_account'];
/* 密钥 */
$key = $payment['tenpay_key'];
/* 创建支付应答对象 */
$resHandler = new ResponseHandler();
$resHandler->setKey($key);

//判断签名
if($resHandler->isTenpaySign()) {
	global $_MooClass,$dbTablePre,$banktype;
	//通知id
	$notify_id = $resHandler->getParameter("notify_id");
	//通过通知ID查询，确保通知来至财付通
	//创建查询请求
	$queryReq = new RequestHandler();
	$queryReq->init();
	$queryReq->setKey($key);
	$queryReq->setGateUrl($payment['gateurlnotify']);
	$queryReq->setParameter("partner", $partner);
	$queryReq->setParameter("notify_id", $notify_id);
	//通信对象
	$httpClient = new TenpayHttpClient();
	$httpClient->setTimeOut(5);
	//设置请求内容
	$httpClient->setReqContent($queryReq->getRequestURL());
	//后台调用
	if($httpClient->call()) {
		//设置结果参数
		$queryRes = new ClientResponseHandler();
		$queryRes->setContent($httpClient->getResContent());
		$queryRes->setKey($key);
		//判断签名及结果
		//只有签名正确,retcode为0，trade_state为0才是支付成功
		if($queryRes->isTenpaySign() && $queryRes->getParameter("retcode") == "0" && $queryRes->getParameter("trade_state") == "0" && $queryRes->getParameter("trade_mode") == "1"){
			//取结果参数做业务处理
			$out_trade_no = $queryRes->getParameter("out_trade_no");
			//财付通订单号
			$transaction_id = $queryRes->getParameter("transaction_id");
			//金额,以分为单位
			$total_fee = '';
			$total_fee_res = '';
			$total_fee = $queryRes->getParameter("total_fee");
			$total_fee_res = $total_fee/100;
			//如果有使用折扣券，discount有值，total_fee+discount=原请求的total_fee
			$discount = $queryRes->getParameter("discount");
			//支付结果 0-成功 1-失败
			$trade_state = $queryRes->getParameter('trade_state');
			//交易模式，1即时到帐
			$trade_mode = $queryRes->getParameter('trade_mode');
			//支付银行
			$bank_type1 = $queryRes->getParameter('bank_type');
			$bank_type = $banktype["$bank_type1"];//银行名
			//币种 1-人民币
			$fee_type = $queryRes->getParameter('fee_type');
			//商品价格
			$product_fee = $queryRes->getParameter("product_fee");
			$product_fee_res = $product_fee/100;

			//attach 0-钻石会员  1-高级会员  2-城市之星
			$attach = $queryRes->getParameter("attach");

			//用户ID
			$uid = substruid($out_trade_no,14);
			//------------------------------
			//处理业务开始
			//------------------------------
			//处理数据库逻辑
			//注意交易单不要重复处理
			//注意判断返回金额
			$bgtime = time();
			$apply_note = '';
			$s_cid = '';
			$total_money = '';
		    if($attach == '-1'){
                $total_money = $paymoney['platinum'];
                $apply_note = '六个月铂金会员';
                $s_cid = '10';
            }elseif($attach == '0'){
				$total_money = $paymoney['diamond'];
				$apply_note = '六个月钻石会员';
				$s_cid = '20';
			}elseif($attach == '1'){
				$total_money = $paymoney['vip'];
				$apply_note = '三个月高级会员';
				$s_cid = '30';
			}elseif($attach == '2'){
				$total_money = $paymoney['citystar'];
				$apply_note = '一个月城市之星';
			}else{
				$total_money = $paymoney['citystar'];
				$apply_note = '一个月城市之星';
			}
			//如果支付金额不正确 ——不改变会员状态
			if($total_fee_res < $total_money){
				echo "success";
				exit;
			}
			
			//会员信息
			$sql = "SELECT nickname,telphone FROM {$dbTablePre}members_search WHERE uid='{$uid}'";
			$res = $_MooClass['MooMySQL']->getOne($sql,true);

			//联系方式
			$telephone = !empty($res['telphone']) ? $res['telphone'] : '';

			//更新会员等级 城市之星不用更改等级
			if($attach != '2'){
				$sql_uid = "UPDATE {$dbTablePre}members_search SET s_cid='{$s_cid}'  WHERE uid='{$uid}'";
				$_MooClass['MooMySQL']->query($sql_uid);
				searchApi('members_man members_women')->updateAttr(array('s_cid'),array($uid=>array($s_cid)));
			}

			//更新支付状态
			$sql_getpayid = "SELECT max(id) id FROM {$dbTablePre}payment_new WHERE order_id='{$out_trade_no}'";
			$getpayid = $_MooClass['MooMySQL']->getOne($sql_getpayid,true);
			$id = $getpayid['id'];

			$sql_pay = "UPDATE {$dbTablePre}payment_new SET pay_time='{$bgtime}',pay_info='{$bank_type}',apply_note='{$apply_note}',status='1',check_time='{$bgtime}',contact='{$telephone}' WHERE id='{$id}'";
			$_MooClass['MooMySQL']->query($sql_pay);
			
			//确认更改状态成功
			$sql_com_uid = "SELECT s_cid FROM {$dbTablePre}members_search WHERE uid='{$uid}'";
			$com_uid = $_MooClass['MooMySQL']->getOne($sql_com_uid,true);
			$sql_com = "SELECT status FROM {$dbTablePre}payment_new WHERE id='{$id}'";
			$confirm = $_MooClass['MooMySQL']->getOne($sql_com,true);

			//------------------------------
			//处理业务完毕
			//------------------------------
			if(($confirm['status'] == '1'  || $confirm['status'] == '3') && $com_uid['s_cid'] != '40'){
				echo "success";
			}else{
				echo "fail";
			}
			
		} else {
			//错误时，返回结果可能没有签名，写日志trade_state、retcode、retmsg看失败详情。
			//echo "验证签名失败 或 业务错误信息:trade_state=" . $queryRes->getParameter("trade_state") . ",retcode=" . $queryRes->getParameter("retcode"). ",retmsg=" . $queryRes->getParameter("retmsg") . "<br/>" ;
			echo "fail";
		}
		
		//获取查询的debug信息,建议把请求、应答内容、debug信息，通信返回码写入日志，方便定位问题
		/*
		echo "<br>------------------------------------------------------<br>";
		echo "http res:" . $httpClient->getResponseCode() . "," . $httpClient->getErrInfo() . "<br>";
		echo "query req:" . htmlentities($queryReq->getRequestURL(), ENT_NOQUOTES, "GB2312") . "<br><br>";
		echo "query res:" . htmlentities($queryRes->getContent(), ENT_NOQUOTES, "GB2312") . "<br><br>";
		echo "query reqdebug:" . $queryReq->getDebugInfo() . "<br><br>" ;
		echo "query resdebug:" . $queryRes->getDebugInfo() . "<br><br>";
		*/
	}else {
		//通信失败
		echo "fail";
		//后台调用通信失败,写日志，方便定位问题
		//echo "<br>call err:" . $httpClient->getResponseCode() ."," . $httpClient->getErrInfo() . "<br>";
	} 
	
	
} else {
	//回调签名错误
	echo "fail";
	//echo "<br>签名失败<br>";
}

//获取debug信息,建议把debug信息写入日志，方便定位问题
//echo $resHandler->getDebugInfo() . "<br>";

?>
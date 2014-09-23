<?php
/*
 *2010-11-19
 */
class tenpay{
	/**
	 * 生成支付代码
	 * @param   array    $order       订单信息
	 * @param   array    $payment     支付方式信息
	 */
	function get_code($order,$payment){
		require_once 'requesthandler.class.php';
		/* 商户号 */
		$partner = $payment['tenpay_account'];

		/* 密钥 */
		$key = $payment['tenpay_key'];

		//订单号，此处用时间加随机数生成，商户根据自己情况调整，只要保持全局唯一就行
		$out_trade_no = $order['order_sn'];
		/* 财付通交易单号，规则为：10位商户号+8位时间（YYYYmmdd)+10位流水号 */

		//认证费用
		$money = intval(intval($order['order_amount']).'00');

		//附加数据 0-钻石会员,1-高级会员
		$attach = $order['order_type'];

		/* 创建支付请求对象 */
		$reqHandler = new RequestHandler();
		$reqHandler->init();
		$reqHandler->setKey($key);
		$reqHandler->setGateUrl($payment['gateurl']);

		//----------------------------------------
		//设置支付参数 
		//----------------------------------------
		$reqHandler->setParameter("total_fee",$money);  //总金额
		//用户ip
		$reqHandler->setParameter("spbill_create_ip",$_SERVER['REMOTE_ADDR']);//客户端IP
		$reqHandler->setParameter("return_url",$payment['returnurl']);//支付成功后返回
		$reqHandler->setParameter("partner",$partner);
		$reqHandler->setParameter("out_trade_no",$out_trade_no);			//用户订单号
//		$reqHandler->setParameter("transaction_id",$transaction_id);		//财付通交易单号
		$reqHandler->setParameter("notify_url",$payment['notifyurl']);//通知商户后台
		$reqHandler->setParameter("body",$order['subject']);
		$reqHandler->setParameter("bank_type", "DEFAULT");  	  //银行类型，默认为财付通
		$reqHandler->setParameter("fee_type", "1");               //币种
		//系统可选参数
		$reqHandler->setParameter("sign_type", "MD5");  	 	  //签名方式，默认为MD5，可选RSA
		$reqHandler->setParameter("service_version", "1.0"); 	  //接口版本号
		$reqHandler->setParameter("input_charset", "UTF-8");   	  //字符集
		$reqHandler->setParameter("sign_key_index", "1");    	  //密钥序号

		//业务可选参数
		$reqHandler->setParameter("attach", $attach);             	  //附件数据，原样返回就可以了
		$reqHandler->setParameter("product_fee", "");        	  //商品费用
		$reqHandler->setParameter("transport_fee", "");      	  //物流费用
		$reqHandler->setParameter("time_start", date("YmdHis"));  //订单生成时间
		$reqHandler->setParameter("time_expire", "");             //订单失效时间

		$reqHandler->setParameter("buyer_id", "");                //买方财付通帐号
		$reqHandler->setParameter("goods_tag", "");               //商品标记
		$reqHandler->setParameter("agentid", "");                 //===========
		$reqHandler->setParameter("agent_type", "");			  //===========
		//请求的URL
		$reqUrl = $reqHandler->getRequestURL();
//		return $transaction_id;
		return $reqUrl;
	}
}
?>
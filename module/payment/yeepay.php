<?php
/*
 *2011-2-18
 */
class yeepay{
	/**
	 * 生成支付代码
	 * @param   array    $order       订单信息
	 * @param   array    $payment     支付方式信息
	 */
	function get_code($order,$payment){
		include 'yeepaycommon.php';
		$merchantKey = $payment['merchantKey'];	// 密钥
		$p0_Cmd='Buy';
		$p1_MerId = $payment['p1_MerId'];	// 商户号
		$p2_Order = $order['order_sn'];	//订单号
		$p3_Amt=$order['order_amount'];
		$p4_Cur='CNY';
		$p5_Pid=iconv("GB2312","UTF-8",$order['subject']);
		$p6_Pcat = iconv("GB2312","UTF-8",'真爱一生网服务');
		$p7_Pdesc = iconv("GB2312","UTF-8",'升级会员');
		$p8_Url =$payment['p8_Url'];
		$p9_SAF=0;
		$pa_MP = $order['order_type'];
		$pd_FrpId = '1000000-NET';
		$pr_NeedResponse=1;
		$hmac = getReqHmacString($p0_Cmd,$p1_MerId,$p2_Order,$p3_Amt,$p4_Cur,$p5_Pid,$p6_Pcat,$p7_Pdesc,$p8_Url,$p9_SAF,$pa_MP,$pd_FrpId,$pr_NeedResponse,$merchantKey);
		$reqUrl ="<html>
<head>
<title>please wait...</title>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
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
		return $reqUrl;
	}
}
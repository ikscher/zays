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
		require_once 'yeepaycommon.php';
		$merchantKey = $payment['merchantKey'];	// 密钥
		$p0_Cmd='Buy';
		$p1_MerId = $payment['p1_MerId'];	// 商户号
		$p2_Order = $order['order_sn'];	//订单号
		$p3_Amt=$order['order_amount'];
		$p4_Cur='CNY';
		$p5_Pid=iconv("UTF-8","GB2312",$order['subject']);
		$p6_Pcat = iconv("UTF-8","GB2312",'真爱一生网服务');
		$p7_Pdesc = iconv("UTF-8","GB2312",'升级会员');
		$p8_Url =$payment['p8_Url'];
		$p9_SAF=0;
		$pa_MP = $order['order_type'];
		$pd_FrpId = '';
		$pr_NeedResponse=1;
		$hmac = getReqHmacString($p0_Cmd,$p1_MerId,$p2_Order,$p3_Amt,$p4_Cur,$p5_Pid,$p6_Pcat,$p7_Pdesc,$p8_Url,$p9_SAF,$pa_MP,$pd_FrpId,$pr_NeedResponse,$merchantKey);
		$p5_Pid=urlencode($p5_Pid);
		$p6_Pcat=urlencode($p6_Pcat);
		$p7_Pdesc=urlencode($p7_Pdesc);
		$reqUrl='https://www.yeepay.com/app-merchant-proxy/node?p0_Cmd='.$p0_Cmd.'&p1_MerId='.$p1_MerId.'&p2_Order='.$p2_Order.'&p3_Amt='.$p3_Amt.'&p4_Cur='.$p4_Cur.'&p5_Pid='.$p5_Pid.'&p6_Pcat='.$p6_Pcat.'&p7_Pdesc='.$p7_Pdesc.'&p8_Url='.$p8_Url.'&p9_SAF='.$p9_SAF.'&pa_MP='.$pa_MP.'&pd_FrpId='.$pd_FrpId.'&pr_NeedResponse='.$pr_NeedResponse.'&hmac='.$hmac;
		return $reqUrl;
	}
}
<?php
/*
 *2010-11-20
 */
//---------------------------------------------------------
//财付通即时到帐支付页面回调示例，商户按照此文档进行开发即可
//---------------------------------------------------------
require_once dirname(__FILE__).'/./responsehandler.class.php';
//require_once dirname(__FILE__).'./../../framwork/MooPHP.php';

require_once dirname(__FILE__).'/./config.php';
require_once dirname(__FILE__).'/./function.php';
date_default_timezone_set("Asia/Shanghai");
global $payment_code,$paymoney;

$payment = $payment_code['tenpay'];
/* 密钥 */
$key = $payment['tenpay_key'];
/* 创建支付应答对象 */
$resHandler = new ResponseHandler();
$resHandler->setKey($key);
//attach 0-钻石会员  1-高级会员 2-城市之星
$attach = $resHandler->getParameter("attach");
//判断签名
if($resHandler->isTenpaySign()){
	//状态码 0-成功
	$retcode = $resHandler->getParameter("retcode");
	//通知id
	$notify_id = $resHandler->getParameter("notify_id");
	//商户订单号
	$out_trade_no = $resHandler->getParameter("out_trade_no");
	//财付通订单号
	$transaction_id = $resHandler->getParameter("transaction_id");
	//金额,以分为单位
	$total_fee = $resHandler->getParameter("total_fee");
	$total_fee_res = $total_fee/100;
	//如果有使用折扣券，discount有值，total_fee+discount=原请求的total_fee
	$discount = $resHandler->getParameter("discount");
	//支付结果 0-成功  1-失败
	$trade_state = $resHandler->getParameter("trade_state");
	//交易模式,1即时到账
	$trade_mode = $resHandler->getParameter("trade_mode");
	//支付银行
	$bank_type = $resHandler->getParameter('bank_type');

	//币种 1-人民币
	$fee_type = $resHandler->getParameter("fee_type");
	//商品价格
	$product_fee = $resHandler->getParameter("product_fee");
	$product_fee_res = $product_fee/100;

	//用户ID
	$uid = substruid($out_trade_no,14);
	
	if($trade_state == '0' && $trade_mode == '1'){
		//------------------------------
		//处理业务开始
		//------------------------------
		//注意交易单不要重复处理
		//注意判断返回金额
				
		$total_money = '';
		if($attach == '0'){
			$total_money = $paymoney['diamond'];
		}elseif($attach == '1'){
			$total_money = $paymoney['vip'];
		}elseif($attach == '2'){
			$total_money = $paymoney['citystar'];
		}else{
			$total_money = $paymoney['vip'];
		}

		//如果支付金额不正确  ——返回失败页面
//		$total_fee_res = $total_money;
		if($total_fee_res < $total_money){
			//返回信息 数组表示
			//当做不成功处理
			$paytime = date("Y.m.d H:i:s",time());
			$payurl = array('pay'=>'2',					//选择模板  
				'out_trade_no'=>$out_trade_no,		//订单号
				'paytime'=>$paytime,						//支付时间
				'bank_type'=>$bank_type,				//支付银行类型
				'trade_state'=>$trade_state,			//支付状态
				'get_img'=>'01.gif',							//01-错误图片   05-正确图片
				'img'=>$attach,								//图片类型
				'total_fee'=>$total_fee_res,				//支付价格
				'uid'=>$uid);								//会员ID
			$payurl = implode(',',$payurl);
			$payurl = $payurl.',tenpay';
			header("Location:./../../index.php?n=payment&h=payreturnurl&payurl=".$payurl);exit;
		}
		
		//------------------------------
		//处理业务完毕
		//------------------------------	

		//返回信息 数组表示
		$paytime = date("Y.m.d H:i:s");
		$payurl = array('pay'=>'1',					//选择模板 
			'out_trade_no'=>$out_trade_no,		//订单号
			'paytime'=>$paytime,						//支付时间
			'bank_type'=>$bank_type,				//支付银行类型
			'trade_state'=>$trade_state,			//支付状态
			'get_img'=>'05.gif',							//01-错误图片   05-正确图片
			'img'=>$attach,								//图片类型
			'total_fee'=>$total_fee_res,				//支付价格
			'uid'=>$uid);									//会员ID
		$payurl = implode(',',$payurl);
		$payurl = $payurl.',tenpay';
		header("Location:./../../index.php?n=payment&h=payreturnurl&payurl=".$payurl);exit;
	
	}else{
		//返回信息 数组表示
		//当做不成功处理
		$paytime = date("Y.m.d H:i:s",time());
		$payurl = array('pay'=>'2',					//选择模板 
			'out_trade_no'=>$out_trade_no,		//订单号
			'paytime'=>$paytime,						//支付时间
			'bank_type'=>$bank_type,				//支付银行类型
			'trade_state'=>$trade_state,			//支付状态
			'get_img'=>'01.gif',							//01-错误图片   05-正确图片
			'img'=>$attach,								//图片类型
			'total_fee'=>$total_fee_res,				//支付价格
			'uid'=>$uid);									//会员ID
		$payurl = implode(',',$payurl);
		$payurl = $payurl.',tenpay';
		header("Location:./../../index.php?n=payment&h=payreturnurl&payurl=".$payurl);exit;
		
	}
}else{
	//认证签名失败
	//返回信息 数组表示
	//付款状态 0-成功 1失败
	$trade_state = '1';
	$payurl = array('pay'=>'3',				//选择模板 
		'errorinfo'=>'请<a href="https://www.tenpay.com/" target="_blank">登录财付通</a>查看订单信息',	 //错误信息
		'trade_state'=>$trade_state	);
	$payurl = implode(',',$payurl);
	$payurl = $payurl.',tenpay';
	header("Location:./../../index.php?n=payment&h=payreturnurl&payurl=".$payurl);exit;

}

?>
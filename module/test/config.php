<?php 

  /*
   支付方式统一参数配置文件
  */

$pay_sty=array("alipay","kuaiqian","tenpay","yeepay");


$payment_code=array(
		 "alipay"=>
		    array(
		        "alipay_partner"  =>"2088402274086579",//商家ID
		        //"alipay_account"  =>"138180051@qq.com",//卖家邮箱
				"alipay_account"  =>"268007651@qq.com",//卖家邮箱
                "alipay_key"      =>"m6s2jvw37r8vwss4q571lss7y9fjyq6x", 	//支付密钥	  
				"m_fee"      =>"0",//手续费率  
				"notify_url"  =>"http://www.zhenaiyisheng.cc/module/paymenttest/alipaynotifyurl.php",	//服务器异步通知
				"return_url" =>"http://www.zhenaiyisheng.cc/module/paymenttest/alipayreturnurl.php",	//页面跳转
		        ), 
		 "kuaiqian"=>
		     array(
		        "kq_account"      =>"",//快钱帐号  
		        "kq_key"          =>"",  //快钱KEY 
		        "m_fee"           =>"1",   
		     ), 
         "tenpay"=>
		       array(
		        "tenpay_account"  =>"1208485801",//商户号
	            "tenpay_key"      =>"3b5b473c327d3febc72d5d359d4a427c",//密钥
		        "m_fee"           =>"0",//手续费率  不要
				"gateurl"   =>"https://gw.tenpay.com/gateway/pay.htm",
				"gateurlnotify"  => "https://gw.tenpay.com/gateway/verifynotifyid.xml",      
				"returnurl"  => "http://www.zhenaiyisheng.cc/module/paymenttest/tenpayreturnurl.php",
				"notifyurl" => "http://www.zhenaiyisheng.cc/module/paymenttest/tenpaynotifyurl.php",
				"body" => "真爱一生网会员认证费用",
		     ),  
		"yeepay"=>
				array(
				 "p1_MerId" =>"10011168861",//商户号
				 "merchantKey" =>"B57623ASq0D0IeQco66YWw0100c662UAF9l7310r3f2511n67sgPz8o6lc62",//密钥
				 "p8_Url" =>"http://www.zhenaiyisheng.cc/module/paymenttest/yeepaynotifyurl.php",
				),
);
//财付通支付银行代号
$tenpay_banktype = array(
	'DEFAULT'=>'财付通',
	'BL'=>'财付通余额支付',
	'ICBC'=>'中国工商银行',
	'CMB'=>'招商银行',
	'CCB'=>'中国建设银行',
	'ABC'=>'中国农业银行',
	'SPDB'=>'上海浦东发展银行',
	'SDB'=>'深圳发展银行',
	'CIB'=>'兴业银行',
	'BOB'=>'北京银行',
	'CEB'=>'中国光大银行',
	'CMBC'=>'中国民生银行',
	'CITIC'=>'中信银行',
	'GDB'=>'广东发展银行',
	'PAB'=>'平安银行',
	'BOC'=>'中国银行',
	'COMM'=>'交通银行',
	'ICBCB2B'=>'中国工商银行（企业）',
	'CMBB2B'=>'招商银行（企业）',
	'POSTGC'=>'中国邮政储蓄银行（银联）',
	'EPOS'=>'信用卡EPOS',
);
//易宝支付银行代号
$yeepay_banktype = array(
	//'1000000-NET'=>'yeepay.jpg',//易宝支付
	'ICBC-NET-B2C'=>'gongshang.gif',//中国工商银行
	'CMBCHINA-NET-B2C'=>'zhaohang.gif',//招商银行
	'ABC-NET-B2C'=>'nongye.gif',//中国农业银行
	'CCB-NET-B2C'=>'jianshe.gif',//建设银行
	'BCCB-NET-B2C'=>'beijing.gif',//北京银行
	'BOCO-NET-B2C'=>'jiaotong.gif',//交通银行
	'CIB-NET-B2C'=>'xingye.gif',//兴业银行
	'NJCB-NET-B2C'=>'nanjing.gif',//南京银行
	'CMBC-NET-B2C'=>'minsheng.gif',//中国民生银行
	'CEB-NET-B2C'=>'guangda.gif',//光大银行
	'BOC-NET-B2C'=>'zhongguo.gif',//中国银行
	'PINGANBANK-NET'=>'pingan.gif',//平安银行
	'CBHB-NET-B2C'=>'buohai.gif',//渤海银行
	'HKBEA-NET-B2C'=>'dongya.gif',//东亚银行
	'NBCB-NET-B2C'=>'ningbo.gif',//宁波银行
	'ECITIC-NET-B2C'=>'zhongxin.gif',//中信银行
	'SDB-NET-B2C'=>'shenfa.gif',//深圳发展银行
	'GDB-NET-B2C'=>'guangfa.gif',//广东发展银行
	'SHB-NET-B2C'=>'shanghaibank.gif',//上海银行
	'SPDB-NET-B2C'=>'shangpufa.gif',//上海浦东发展银行
	'POST-NET-B2C'=>'youzheng.gif',//中国邮政
	'BJRCB-NET-B2C'=>'nongcunshangye.gif',//北京农村商业银行
	'HXB-NET-B2C'=>'huaxia.gif',//华夏银行
	'CZ-NET-B2C'=>'zheshang.gif',//浙商银行
);

//测试用
$testkey='jhiu5ther.3e4';
$k=MooGetGPC('testkey', 'string', 'G');

if($testkey==$k){
	$paymoney = array(
		'diamond'=>'0.01',   //六个月钻石会员费用
		'vip'=>'0.01',			  //三个月高级会员费用
		'citystar'=>'0.01',     //一个月城市之星费用
	);
	$urltestkey='&testkey=jhiu5ther.3e4';
}else{
	$paymoney = array(
		'diamond'=>'1899',   //六个月钻石会员费用
		'vip'=>'1399',			  //三个月高级会员费用
		'citystar'=>'1399',     //一个月城市之星费用
	);
}

$paymoney2 = array(		//活动期间
	'diamond'=>'1499',   //六个月钻石会员费用
	'vip'=>'999',			  //三个月高级会员费用
	'citystar'=>'1399',     //一个月城市之星费用
);
//活动时间
$activitytime1 = "2011-02-01 00:10:00";
$activitytime2 = "2011-02-08";

?>
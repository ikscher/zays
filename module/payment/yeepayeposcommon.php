<?php
/*
 * @Title 易宝支付EPOS范例
 * @Description 通用文件，包含签名机制，数据发送和日志写入
 * @V3.0
 * @Author  Prencense
 */
 //---------------------------------------------------------------------------------------------
require_once 'yeepayeposhttpclient.class.php';


// 支付请求函数
function getReqHmacString(Array $bizArray,$logName,$merchantKey){
	$str="";
	foreach($bizArray as $key=>$value){
		 $str=$str.$value;
	}
	return HmacMd5($str,$merchantKey,$logName);	
}


function eposSale(Array $bizArray,$actionURL,$merchantKey,$logName) {
	// 调用签名函数生成签名串
	$ReqHmacString=getReqHmacString($bizArray,$logName,$merchantKey);
	// 组成请求串
	$actionHttpString=HttpClient::buildQueryString($bizArray)."&pr_NeedResponse=1"."&hmac=".$ReqHmacString;
	 // echo $actionURL."?".$actionHttpString;exit;

	// 记录发起支付请求的参数
	logurl("发起请求",$actionURL."?".$actionHttpString,$logName);
	// 发起支付请求
	$pageContents = HttpClient::quickPost($actionURL,$actionHttpString);
	
	if ($GLOBALS['uid']=='20310462'){ 
	  echo $pageContents."<br>";
	   
	}
	// 记录收到的提交结果
	logurl("请求回写",$pageContents,$logName);
    $result=explode("\n",$pageContents);
	
	for($index=0;$index<count($result);$index++){		
		$result[$index] = trim($result[$index]);
		if (strlen($result[$index]) == 0) {
			continue;
		}
		
		$aryReturn= explode("=",$result[$index]);
		$sKey= $aryReturn[0];
		$sValue	= $aryReturn[1];
		if($sKey=="r0_Cmd"){				
			$r0_Cmd	= $sValue;
		}elseif($sKey=="r1_Code"){			        
			$r1_Code= $sValue;
		}elseif($sKey=="r2_TrxId"){			       
			$r2_TrxId=$sValue;
		}elseif($sKey=="r6_Order"){			       
			$r6_Order=$sValue;
		}elseif($sKey =="errorMsg"){				
			$errorMsg=$sValue;
		}elseif($sKey == "hmac"){						
			$hmac = $sValue;	 
			
		}   /* else{
		   return $result[$index];
		}   */
		
		
	}
    
    $hmac=iconv("UTF-8","GB2312",$hmac);
	 
    // 进行校验码检查 取得加密前的字符串
	$sbOld="";
	// 加入业务类型
	$sbOld = $sbOld.$r0_Cmd;                
	// 加入支付结果
	$sbOld = $sbOld.$r1_Code;
	// 加入易宝支付交易流水号
	$sbOld = $sbOld.$r2_TrxId;                
	// 加入商户订单号
	$sbOld = $sbOld.$r6_Order;                
	    
	$sNewString = HmacMd5($sbOld,$merchantKey,$logName);  
	    
    logurl("订单号:".$r6_Order,"本地生成HMAC:".$sNewString."返回HMAC:".$hmac,$logName);
	
	$uid = substruid($r6_Order,14);
	
	

	$money = $bizArray['p3_Amt'];
	global $_MooClass,$dbTablePre;
	$sql_getpayid = "SELECT max(id) id FROM {$dbTablePre}payment_new WHERE order_id='{$r6_Order}'";
	$getpayid = $_MooClass['MooMySQL']->getOne($sql_getpayid,true);
	$id = $getpayid['id'];
	
	$sql_p = "SELECT pay_service FROM {$dbTablePre}payment_new WHERE id='{$id}'";
	$payservice = $_MooClass['MooMySQL']->getOne($sql_p,true);
//	if($money=='1899'){
//		$attach='0';
//	}elseif($money=='1399'){
//		$attach='1';
//	}elseif($money=='1399.00'){
//		$attach='2';
//	}else{
//		$attach='1';
//	}
//
	if($payservice['pay_service']=='0'){
		$attach='0';
	}elseif($payservice['pay_service']=='1'||$payservice['pay_service']=='3'){
		$attach='1';
	}elseif($payservice['pay_service']=='2'){
		$attach='2';
	}else{
		$attach='1';
	}
	
	if ($GLOBALS['uid']=='20310462'){ 
	  echo $sNewString;exit;
	   
	}
	
	// 校验码正确
	if($sNewString==$hmac) {
		if($r1_Code=="1"){
		      logurl("请求成功","本地生成HMAC:".$sNewString."返回HMAC:".$hmac,$logName);
			  //返回信息 数组表示
			  $paytime = date("Y.m.d H:i:s");
			  $payurl = array('pay'=>'1',					//选择模板 
				'out_trade_no'=>$r6_Order,		//订单号
				'paytime'=>$paytime,						//支付时间
				'bank_type'=>'易宝支付',				//支付银行类型
				'trade_state'=>'0',			//支付状态
				'get_img'=>'05.gif',							//01-错误图片   05-正确图片
				'img'=>$attach,								//图片类型
				'total_fee'=>$bizArray['p3_Amt'],				//支付价格
				'uid'=>$uid);									//会员ID
			  $payurl = implode(',',$payurl);
			  $payurl = $payurl.',yeepay';
			  header("Location:./../../index.php?n=payment&h=payreturnurl&payurl=".$payurl);
		      return; 
		} elseif($r1_Code=="66"){
			$errorinfo = iconv("GB2312","UTF-8","订单金额过小!");

		} elseif($r1_Code=="30001"){
			$errorinfo = iconv("GB2312","UTF-8","填写信息格式有误!");

		} elseif($r1_Code=="3002"){
			$errorinfo = iconv("GB2312","UTF-8","创建订单异常!");

		} elseif($r1_Code=="3003"){
			$errorinfo = iconv("GB2312","UTF-8","创建交易异常!");

		} elseif($r1_Code=="3006"){
			$errorinfo = iconv("GB2312","UTF-8","提交失败!银行返回失败信息：$errorMsg!");

		} elseif($r1_Code=="3008"){
			$errorinfo = iconv("GB2312","UTF-8","卡号规则不符合!");

		}elseif($r1_Code=="3009"){
			$errorinfo = iconv("GB2312","UTF-8","卡号有误或不支持的银行!");

		}elseif($r1_Code=="3010"){
			$errorinfo = iconv("GB2312","UTF-8","查询信用卡类型出错!");

		}elseif($r1_Code=="3011"){
			$errorinfo = iconv("GB2312","UTF-8","日期格式错误!");
		
		} elseif($r1_Code=="-100"){
			$errorinfo = iconv("GB2312","UTF-8","未知错误!");

		} else{
			$errorinfo = iconv("GB2312","UTF-8","提交失败，请检查后重新测试支付!");    

		}
		$trade_state = '1';
		$payurl = array('pay'=>'3',				//选择模板 
			'errorinfo'=>$errorinfo,	 //错误信息
			'trade_state'=>$trade_state	);
		$payurl = implode(',',$payurl);
		$payurl = $payurl.',yeepay';
		header("Location:./../../index.php?n=payment&h=payreturnurl&payurl=".$payurl);
		exit;
	} else{
		$trade_state = '1';
		$errorinfo = iconv("GB2312","UTF-8","服务器出错!");
		$payurl = array('pay'=>'3',				//选择模板 
			'errorinfo'=>$errorinfo,	 //错误信息
			'trade_state'=>$trade_state	);
		$payurl = implode(',',$payurl);
		$payurl = $payurl.',yeepay';
		header("Location:./../../index.php?n=payment&h=payreturnurl&payurl=".$payurl);
		exit; 
	}
}


// 校验支付结果

function verifyCallback(Array $bizArray,$callBackHmac,$logName,$merchantKey){
    $callBackString="";
	$callBackStringLog="";
	foreach($bizArray as $key=>$value){
			$callBackString.=$value;
			$callBackStringLog.=$key."=".$value."&";
	
	}
	$newLocalHmac=HmacMd5( $callBackString,$merchantKey,$logName);	
	if ($newLocalHmac==$callBackHmac){
		logurl("callBack页面回调成功，交易信息正常!","回调参数串:".$callBackStringLog."LocalHmac(".$newLocalHmac.") == ResponseHmac(".$callBackHmac.")!",$logName);
	 	return true;
	
	}
    else{
		echo "交易信息被篡改！</br>newLocalHmac=".$newLocalHmac."</br>callBackHmac=".$callBackHmac;
		logurl("callBack页面回调成功，但交易信息被篡改!","回调参数串:".$callBackStringLog."LocalHmac(".$newLocalHmac.") != ResponseHmac(".$callBackHmac.")!",$logName);
		return false; 
	}

}


// 生成hmac的函数
function HmacMd5($data,$key,$logName)
{
	//logurl("iconv Q logdata",$data);
	$logdata=$data;
	$logkey=$key;

	// RFC 2104 HMAC implementation for php.
	// Creates an md5 HMAC.
	// Eliminates the need to install mhash to compute a HMAC
	// Hacked by Lance Rushing(NOTE: Hacked means written)

	// 需要配置环境支持iconv，否则中文参数不能正常处理
	$key=iconv("GB2312","UTF-8",$key);
	$data=iconv("GB2312","UTF-8",$data);
	$b=64; // byte length for md5
	if (strlen($key) > $b) {
			$key = pack("H*",md5($key));
								}
	$key=str_pad($key, $b, chr(0x00));
	$ipad=str_pad('', $b, chr(0x36));
	$opad=str_pad('', $b, chr(0x5c));
	$k_ipad=$key ^ $ipad ;
	$k_opad=$key ^ $opad;

	$log_hmac=md5($k_opad . pack("H*",md5($k_ipad . $data)));
	loghmac($logdata,$logkey,$log_hmac,$logName);
	return md5($k_opad . pack("H*",md5($k_ipad . $data)));
}

// 记录请求URL到日志
function logurl($title,$content,$logName)
{
	$james=fopen($logName,"a+");
	fwrite($james,"\r\n".date("Y-m-d H:i:s,A")." [".$title."]   ".$content."\n");
	fclose($james);
}

// 记录生成hmac时的日志信息
function loghmac($str,$merchantKey,$hmac,$logName)
{
	$james=fopen($logName,"a+");
	fwrite($james,"\r\n".date("Y-m-d H:i:s,A")."  [构成签名的参数:]".$str."  [商户密钥:]".$merchantKey."   [本地HMAC:]".$hmac);
	fclose($james);
}
?>
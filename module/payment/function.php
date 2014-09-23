<?php 
/*

支付方法文件

作者尤莹煌

修改 ts24 (2010-3-22)

*/


//改变状态及其他服务的操作
function order_paid($order_id){
  
	$pay=get_pay_one($order_id);
	 //购买服务,0:钻石会员,1:高级会员,2:城市之星
	$serviceArr = array(0=>'钻石会员',1=>'高级会员',2=>'城市之星',6=>'铂金会员');
	 
	if($pay['status']=="1"){
		return false;
	}else {
		update_pay($order_id);//更新状态防止快速刷新
		update_user_rank($pay['uid'],$pay['pay_service']);//更新用户相应的等级
		
		$type_name = $serviceArr[$pay['pay_service']];
		$content="恭喜您，您已成功升级为真爱一生网{$type_name}！您的真爱一生单号".$order_id."请等待审核。";
		if(!empty($pay['telphone'])){
			if(SendMsg($pay['telphone'],$content)){
				$time=time();
				$_MooClass['MooMySQL']->query("INSERT INTO {$dbTablePre}smslog_sys (id,sid,uid,content,sendtime,type) values('','',".$pay['uid'].",'$content','$time','升级')");
				}
		}

		MooSendMail($pay['username'],"真爱一生网会员升级",$content.'您可以咨询客服您的会员办理进度！',$is_template = true,$pay['uid']);
		 
		return true;  		 
	   
	 } 
}

//返回帐号参数信息
function get_payment($code){
   global $payment_code;

  return $payment_code[$code];
}


//取得异步和同步返回地址
function get_payurl($k,$code){
 // global $code;
  
	 switch($k){
	  
		   case "r":
		       $action="reurl&at=1";   
		   break;
		  
		   case "n":
		       $action="reurl&at=2";
		   break;
	   
	 }
	 
	//$site="http://www.7652.com/index.php?n=return&h=".$action."&code=".$code;
	 $site="http://www.zhenaiyisheng.cc/index.php?n=return&h=".$action."&code=".$code."&trade_status=TRADE_FINISHED";
  return $site;	  
}
//改变用户的等级权限
function update_user_rank($uid,$service){
	global $_MooClass,$dbTablePre;
	$bgtime=time();
	//$endtime=$bgtime+180*24*3600;
	$daymeb = date("t")<30?30:date("t");
	$endtime=$bgtime+$daymeb*24*3600;
	$sql=" update {$dbTablePre}members_search set s_cid=".$service.",bgtime='$bgtime',endtime='$endtime' where uid=$uid";
  
	$_MooClass['MooMySQL']->query($sql);
	searchApi('members_man members_women')->updateAttr(array('s_cid','bgtime','endtime'),array($uid=>array($service,$bgtime,$endtime)));
	if(MOOPHP_ALLOW_FASTDB){
		$value = array();
		$value['s_cid'] = $service;
		$value['bgtime'] = $bgtime;
		$value['endtime'] = $endtime;		
		MooFastdbUpdate('members_search','uid',$uid,$value);
	}
	return true;
}


//判断支付money真确与否
function check_money($order_id,$money){

		 $pay=get_pay_one($order_id);
		 if($pay['money']!=$money) return false;
		 else  return true;
}
//取一条支付记录数据
function get_pay_one($order_id){
	   
	   global $_MooClass,$dbTablePre;
	   
	   $sql="select a.*,b.username,b.telphone from {$dbTablePre}payment_new a left join {$dbTablePre}members_search b on a.uid=b.uid where a.order_id='$order_id'";  
	   
	   return $_MooClass['MooMySQL']->getone($sql,true); 
}
//更新支付记录状态 默认为0 支付成功更新为1；
function update_pay($order_id){
    global $_MooClass,$dbTablePre;
    
	$sql="update {$dbTablePre}payment_new set status=1 where order_id='{$order_id}'";
	$_MooClass['MooMySQL']->query($sql);
	
	$sql = "SELECT uid,apply_sid FROM {$dbTablePre}payment_new WHERE order_id ='{$order_id}'";
	$tmp_u = $_MooClass['MooMySQL']->getOne($sql,true);
	
	$sql = "UPDATE {$dbTablePre}members_search SET sid = 0  WHERE uid = ".$tmp_u['uid'];
	@ $_MooClass['MooMySQL']->query($sql);
	searchApi('members_man members_women')->updateAttr(array('sid'),array($tmp_u['uid']=>array(0)));
		
	if(MOOPHP_ALLOW_FASTDB){
		$value = array();
		$value['sid'] = 0;
		MooFastdbUpdate('members_search','uid',$tmp_u['uid'],$value);
	}
	if($tmp_u['apply_sid'] > 0){
		$sql = "UPDATE {$dbTablePre}admin_user SET member_count = member_count - 1 WHERE uid = ".$tmp_u['apply_sid'];
		@ $_MooClass['MooMySQL']->query($sql);
	}
    return true;
}
//判断登陆者是否为某类型的会员
function get_user_s_cid( $s_cid = 30 ){
 global $_MooClass,$dbTablePre,$uid;
 
 $date=time();

 $sql="select count(*) as s from {$dbTablePre}members_search where  uid='$uid' and s_cid='30'  and endtime>='$date' ";  
	   $a=$_MooClass['MooMySQL']->getone($sql,true);
	  return $a['s']; 
}

//截取UID
//param string 要截取的数字字符串
//param length 从这个数字开始截取
function substruid($string,$length){
	if(strlen($string) <= $length) { 
		return $string; 
	}
	//总长
	$strlen = strlen($string);
	//开始截取
	$strcut = '';
	for($i=$length;$i<$strlen;$i++){
		$strcut .= $string[$i];
	}
	return $strcut;
}

function validation_filter_id_card($id_card){ 
	if(strlen($id_card) == 18){ 
		return idcard_checksum18($id_card); 
	}elseif((strlen($id_card) == 15)){ 
		$id_card = idcard_15to18($id_card); 
		return idcard_checksum18($id_card); 
	}else{ 
		return false; 
	} 
} 
// 计算身份证校验码，根据国家标准GB 11643-1999 
function idcard_verify_number($idcard_base){ 
	if(strlen($idcard_base) != 17){ 
		return false; 
	} 
	//加权因子 
	$factor = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2); 
	//校验码对应值 
	$verify_number_list = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2'); 
	$checksum = 0; 
	for ($i = 0; $i < strlen($idcard_base); $i++){ 
		$checksum += substr($idcard_base, $i, 1) * $factor[$i]; 
	} 
	$mod = $checksum % 11; 
	$verify_number = $verify_number_list[$mod]; 
	return $verify_number; 
} 
// 将15位身份证升级到18位 
function idcard_15to18($idcard){ 
	if (strlen($idcard) != 15){ 
		return false; 
	}else{ 
		// 如果身份证顺序码是996 997 998 999，这些是为百岁以上老人的特殊编码 
		if (array_search(substr($idcard, 12, 3), array('996', '997', '998', '999')) !== false){ 
			$idcard = substr($idcard, 0, 6) . '18'. substr($idcard, 6, 9); 
		}else{ 
			$idcard = substr($idcard, 0, 6) . '19'. substr($idcard, 6, 9); 
		} 
	} 
	$idcard = $idcard . idcard_verify_number($idcard); 
	return $idcard; 
} 
// 18位身份证校验码有效性检查 
function idcard_checksum18($idcard){ 
	if (strlen($idcard) != 18){ return false; } 
	$idcard_base = substr($idcard, 0, 17); 
	if (idcard_verify_number($idcard_base) != strtoupper(substr($idcard, 17, 1))){ 
		return false; 
	}else{ 
		return true; 
	} 
}

/**
 *  function :   xml文档解析函数
 *  argument1:   $inXmlset  xml文档
 *  $argument2 : $needle  xml文档节点
 * 
 *  $return   :返回xml节点值
 */
function getXmlValueByTag($inXmlset,$needle){
	$resource    =    xml_parser_create();//Create an XML parser
	xml_parse_into_struct($resource, $inXmlset, $outArray);// Parse XML data into an array structure
	xml_parser_free($resource);//Free an XML parser
   
	for($i=0;$i<count($outArray);$i++){
		if($outArray[$i]['tag']==strtoupper($needle)){
			$tagValue    =    $outArray[$i]['value'];
		}
	}
	return $tagValue;
} 

function get_errorInfo($uid,$telphone,$sid,$transDateTime,$pa_MP,$PayTransNoResultResult){
  switch ($PayTransNoResultResult) {
    case "N00001":
	   error_log("uid:".$uid.",sid:".$sid.",telphone:".$telphone.",date:".$transDateTime.",type:".$pa_MP.",查发卡方",3,"/var/www/www.zhenaiyisheng.cc/error/payError.log");
	   break;
    case "N00002":
	   error_log("uid:".$uid.",sid:".$sid.",telphone:".$telphone.",date:".$transDateTime.",type:".$pa_MP.",查发卡方的特殊条件",3,"/var/www/www.zhenaiyisheng.cc/error/payError.log");
	   break;
    case "N00003":
	   error_log("uid:".$uid.",sid:".$sid.",telphone:".$telphone.",date:".$transDateTime.",type:".$pa_MP.",无效商户",3,"/var/www/www.zhenaiyisheng.cc/error/payError.log");
	   break;
    case "N00004":
	   error_log("uid:".$uid.",sid:".$sid.",telphone:".$telphone.",date:".$transDateTime.",type:".$pa_MP.",没收卡",3,"/var/www/www.zhenaiyisheng.cc/error/payError.log");
	   break;
    case "N00005":
	   error_log("uid:".$uid.",sid:".$sid.",telphone:".$telphone.",date:".$transDateTime.",type:".$pa_MP.",不予承兑",3,"/var/www/www.zhenaiyisheng.cc/error/payError.log");
	   break;
    case "N00006":
	   error_log("uid:".$uid.",sid:".$sid.",telphone:".$telphone.",date:".$transDateTime.",type:".$pa_MP.",出错",3,"/var/www/www.zhenaiyisheng.cc/error/payError.log");
	   break;
    case "N00007":
	   error_log("uid:".$uid.",sid:".$sid.",telphone:".$telphone.",date:".$transDateTime.",type:".$pa_MP.",特殊条件下没收卡",3,"/var/www/www.zhenaiyisheng.cc/error/payError.log");
	   break;
    case "N00009":
	   error_log("uid:".$uid.",sid:".$sid.",telphone:".$telphone.",date:".$transDateTime.",type:".$pa_MP.",请求正在处理中",3,"/var/www/www.zhenaiyisheng.cc/error/payError.log");
	   break;
    case "N00012":
	   error_log("uid:".$uid.",sid:".$sid.",telphone:".$telphone.",date:".$transDateTime.",type:".$pa_MP.",无效交易",3,"/var/www/www.zhenaiyisheng.cc/error/payError.log");
	   break;
    case "N00013":
	   error_log("uid:".$uid.",sid:".$sid.",telphone:".$telphone.",date:".$transDateTime.",type:".$pa_MP.",无效金额",3,"/var/www/www.zhenaiyisheng.cc/error/payError.log");
	   break;
    case "N00014":
	   error_log("uid:".$uid.",sid:".$sid.",telphone:".$telphone.",date:".$transDateTime.",type:".$pa_MP.",无效卡号",3,"/var/www/www.zhenaiyisheng.cc/error/payError.log");
	   break;
    case "N00015":
	   error_log("uid:".$uid.",sid:".$sid.",telphone:".$telphone.",date:".$transDateTime.",type:".$pa_MP.",无此发卡方",3,"/var/www/www.zhenaiyisheng.cc/error/payError.log");
	   break;
    case "N00020":
	   error_log("uid:".$uid.",sid:".$sid.",telphone:".$telphone.",date:".$transDateTime.",type:".$pa_MP.",无效应答",3,"/var/www/www.zhenaiyisheng.cc/error/payError.log");
	   break;
    case "N00022":
	   error_log("uid:".$uid.",sid:".$sid.",telphone:".$telphone.",date:".$transDateTime.",type:".$pa_MP.",操作有误",3,"/var/www/www.zhenaiyisheng.cc/error/payError.log");
	   break;
    case "N00023":
	   error_log("uid:".$uid.",sid:".$sid.",telphone:".$telphone.",date:".$transDateTime.",type:".$pa_MP.",不可接收的交易费",3,"/var/www/www.zhenaiyisheng.cc/error/payError.log");
	   break;
    case "N00030":
	   error_log("uid:".$uid.",sid:".$sid.",telphone:".$telphone.",date:".$transDateTime.",type:".$pa_MP.",格式错误",3,"/var/www/www.zhenaiyisheng.cc/error/payError.log");
	   break;
    case "N00031":
	   error_log("uid:".$uid.",sid:".$sid.",telphone:".$telphone.",date:".$transDateTime.",type:".$pa_MP.",交换中心不支持的银行",3,"/var/www/www.zhenaiyisheng.cc/error/payError.log");
	   break;
    case "N00033":
	   error_log("uid:".$uid.",sid:".$sid.",telphone:".$telphone.",date:".$transDateTime.",type:".$pa_MP.",过期的卡",3,"/var/www/www.zhenaiyisheng.cc/error/payError.log");
	   break;
    case "N00034":
	   error_log("uid:".$uid.",sid:".$sid.",telphone:".$telphone.",date:".$transDateTime.",type:".$pa_MP.",作弊",3,"/var/www/www.zhenaiyisheng.cc/error/payError.log");
	   break;
    case "N00035":
	   error_log("uid:".$uid.",sid:".$sid.",telphone:".$telphone.",date:".$transDateTime.",type:".$pa_MP.",受卡方与安全保密",3,"/var/www/www.zhenaiyisheng.cc/error/payError.log");
	   break;
    case "N00036":
	   error_log("uid:".$uid.",sid:".$sid.",telphone:".$telphone.",date:".$transDateTime.",type:".$pa_MP.",受限制的卡",3,"/var/www/www.zhenaiyisheng.cc/error/payError.log");
	   break;
    case "N00037":
	   error_log("uid:".$uid.",sid:".$sid.",telphone:".$telphone.",date:".$transDateTime.",type:".$pa_MP.",受卡方呼受理方",3,"/var/www/www.zhenaiyisheng.cc/error/payError.log");
	   break;
    case "N00038":
	   error_log("uid:".$uid.",sid:".$sid.",telphone:".$telphone.",date:".$transDateTime.",type:".$pa_MP.",超过允许的密码输入次数",3,"/var/www/www.zhenaiyisheng.cc/error/payError.log");
	   break;
    case "N00039":
	   error_log("uid:".$uid.",sid:".$sid.",telphone:".$telphone.",date:".$transDateTime.",type:".$pa_MP.",无此信用卡账户",3,"/var/www/www.zhenaiyisheng.cc/error/payError.log");
	   break;
    case "N00040":
	   error_log("uid:".$uid.",sid:".$sid.",telphone:".$telphone.",date:".$transDateTime.",type:".$pa_MP.",请求的功能不支持",3,"/var/www/www.zhenaiyisheng.cc/error/payError.log");
	   break;
    case "N00041":
	   error_log("uid:".$uid.",sid:".$sid.",telphone:".$telphone.",date:".$transDateTime.",type:".$pa_MP.",挂失卡",3,"/var/www/www.zhenaiyisheng.cc/error/payError.log");
	   break;
    case "N00042":
	   error_log("uid:".$uid.",sid:".$sid.",telphone:".$telphone.",date:".$transDateTime.",type:".$pa_MP.",无此账户",3,"/var/www/www.zhenaiyisheng.cc/error/payError.log");
	   break;
    case "N00043":
	   error_log("uid:".$uid.",sid:".$sid.",telphone:".$telphone.",date:".$transDateTime.",type:".$pa_MP.",被窃卡",3,"/var/www/www.zhenaiyisheng.cc/error/payError.log");
	   break;
    case "N00051":
	   error_log("uid:".$uid.",sid:".$sid.",telphone:".$telphone.",date:".$transDateTime.",type:".$pa_MP.",无足够的存款",3,"/var/www/www.zhenaiyisheng.cc/error/payError.log");
	   break;
    case "N00052":
	   error_log("uid:".$uid.",sid:".$sid.",telphone:".$telphone.",date:".$transDateTime.",type:".$pa_MP.",无此支票账户",3,"/var/www/www.zhenaiyisheng.cc/error/payError.log");
	   break;
    case "N00053":
	   error_log("uid:".$uid.",sid:".$sid.",telphone:".$telphone.",date:".$transDateTime.",type:".$pa_MP.",无此储蓄卡账户",3,"/var/www/www.zhenaiyisheng.cc/error/payError.log");
	   break;
    case "N00054":
	   error_log("uid:".$uid.",sid:".$sid.",telphone:".$telphone.",date:".$transDateTime.",type:".$pa_MP.",过期的卡",3,"/var/www/www.zhenaiyisheng.cc/error/payError.log");
	   break;
    case "N00055":
	   error_log("uid:".$uid.",sid:".$sid.",telphone:".$telphone.",date:".$transDateTime.",type:".$pa_MP.",不正确的密码",3,"/var/www/www.zhenaiyisheng.cc/error/payError.log");
	   break;
    case "N00056":
	   error_log("uid:".$uid.",sid:".$sid.",telphone:".$telphone.",date:".$transDateTime.",type:".$pa_MP.",无此卡记录",3,"/var/www/www.zhenaiyisheng.cc/error/payError.log");
	   break;
    case "N00057":
	   error_log("uid:".$uid.",sid:".$sid.",telphone:".$telphone.",date:".$transDateTime.",type:".$pa_MP.",不允许持卡人进行交易",3,"/var/www/www.zhenaiyisheng.cc/error/payError.log");
	   break;
    case "N00058":
	   error_log("uid:".$uid.",sid:".$sid.",telphone:".$telphone.",date:".$transDateTime.",type:".$pa_MP.",不允许的终端进行的交易",3,"/var/www/www.zhenaiyisheng.cc/error/payError.log");
	   break;
    case "N00059":
	   error_log("uid:".$uid.",sid:".$sid.",sid:".$sid.",telphone:".$telphone.",date:".$transDateTime.",type:".$pa_MP.",有作弊的嫌疑",3,"/var/www/www.zhenaiyisheng.cc/error/payError.log");
	   break;
    case "N00062":
	   error_log("uid:".$uid.",sid:".$sid.",telphone:".$telphone.",date:".$transDateTime.",type:".$pa_MP.",受限制的卡",3,"/var/www/www.zhenaiyisheng.cc/error/payError.log");
	   break;
    case "N00080":
	   error_log("uid:".$uid.",sid:".$sid.",telphone:".$telphone.",date:".$transDateTime.",type:".$pa_MP.",银行系统内部错误",3,"/var/www/www.zhenaiyisheng.cc/error/payError.log");
	   break;
    case "N00092":
	   error_log("uid:".$uid.",sid:".$sid.",telphone:".$telphone.",date:".$transDateTime.",type:".$pa_MP.",金融机构或网络无法到达",3,"/var/www/www.zhenaiyisheng.cc/error/payError.log");
	   break;
    case "N00093":
	   error_log("uid:".$uid.",sid:".$sid.",telphone:".$telphone.",date:".$transDateTime.",type:".$pa_MP.",交易违法",3,"/var/www/www.zhenaiyisheng.cc/error/payError.log");
	   break;
    case "N00096":
	   error_log("uid:".$uid.",sid:".$sid.",telphone:".$telphone.",date:".$transDateTime.",type:".$pa_MP.",系统异常，失效",3,"/var/www/www.zhenaiyisheng.cc/error/payError.log");
	   break;
    case "N00099":
	   error_log("uid:".$uid.",sid:".$sid.",telphone:".$telphone.",date:".$transDateTime.",type:".$pa_MP.",密码格式错误",3,"/var/www/www.zhenaiyisheng.cc/error/payError.log");
	   break;
    case "N30301":
	   error_log("uid:".$uid.",sid:".$sid.",telphone:".$telphone.",date:".$transDateTime.",type:".$pa_MP.",持卡人身份不合法",3,"/var/www/www.zhenaiyisheng.cc/error/payError.log");
	   break;
    case "N32065":
	   error_log("uid:".$uid.",sid:".$sid.",telphone:".$telphone.",date:".$transDateTime.",type:".$pa_MP.",支付失败，持卡人未接听",3,"/var/www/www.zhenaiyisheng.cc/error/payError.log");
	   break;
    case "N43018":
	   error_log("uid:".$uid.",sid:".$sid.",telphone:".$telphone.",date:".$transDateTime.",type:".$pa_MP.",金额格式错误",3,"/var/www/www.zhenaiyisheng.cc/error/payError.log");
	   break;
    case "N43020":
	   error_log("uid:".$uid.",sid:".$sid .",telphone:".$telphone.",date:".$transDateTime.",type:".$pa_MP.",流水号错误",3,"/var/www/www.zhenaiyisheng.cc/error/payError.log");
	   break;
    case "N90021":
	   error_log("uid:".$uid .",sid:".$sid.",telphone:".$telphone.",date:".$transDateTime.",type:".$pa_MP.",身份证号不合法",3,"/var/www/www.zhenaiyisheng.cc/error/payError.log");
	   break;
    case "N90050":
	   error_log("uid:".$uid.",sid:".$sid.",telphone:".$telphone.",date:".$transDateTime.",type:".$pa_MP.",姓名长度有误",3,"/var/www/www.zhenaiyisheng.cc/error/payError.log");
	   break;
    default:
	   error_log("编号：".$PayTransNoResultResult,'3',"/var/www/www.zhenaiyisheng.cc/error/payError.log");
  }

}



?>



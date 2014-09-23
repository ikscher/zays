<?php

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
	
	// var_dump($result);exit;
    //$Serl_result=serialize($result);
    $PayTransNoResultResult=$result->PayTransNoResultResult; //返回值

    //$merOrderNum=$result->merOrderNum;//订单号
    $tranDateTime=date('Y-m-d',$result->tranDateTime);//交易日期
    //echo $Serl_result;exit;
    //if(preg_match("/N00000/",$Serl_result)){
    if($PayTransNoResultResult=='N00000'){
        
       
        $memcached->set('telPay'.$uid,++$sendedPayCount,0,28800);//设置当日提交支付请求数
        
	    //*********真爱一生备注*********
	    /* $sid = $user_arr['sid'];
	    $title = '您的会员'.$uid.'正在支付 ';//.$p5_Pid;
	    $awoketime = $timestamp+3600;
	    $sql_remark = "insert into {$dbTablePre}admin_remark set sid='{$sid}',title='{$title}',content='{$title}',awoketime='{$awoketime}',dateline='{$timestamp}'";
	    $res = $_MooClass['MooMySQL']->query($sql_remark); */
	    //**********end**********
		
		
        
	    //header("Location: index.php?n=payment&h=telPaying&merOrderNum=$merOrderNum&tranDateTime=$tranDateTime&bank_type=$bank_type");exit; 
		//确保重定向后，后续代码不会被执行 

	}else{
	  /* $key=md5('telpay');
	  $memcached->set($Key,'ddd',600); */
		MooMessage('您的支付请求失败,请重新提交您的支付请求。','index.php?n=index&h=govip');

	}
?>
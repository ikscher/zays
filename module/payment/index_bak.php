<?php
/*
 * Created on 2009-10-26
 *
 * To change the template for this generated file go to*
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
/*************************************** 逻辑层(M)/表现层(V) ****************************************/ 
//include "./module/{$name}/function.php";

/**
 * 功能列表
 */
include "./module/{$name}/config.php";
include "./module/{$name}/function.php";

//note 选择付款方式导航 -- 高级会员
function payment_channel() {
    global $_MooClass,$dbTablePre,$uid,$user_arr,$yeepay_banktype,$urltestkey;
//  $allow_ip = array('220.178.112.174','61.190.44.98','127.0.0.1','220.178.123.74','221.130.166.242','120.193.108.166','61.190.22.14','61.190.10.254','124.73.152.192');
//  $cur_ip = GetIP();
//  $passyou = '';
//  if(in_array($cur_ip,$allow_ip)){
//      $passyou = '1'; 
//  }else{
//      if(strpos($cur_ip,'192.168') !== FALSE){
//          $passyou = '1'; 
//      }
//  }
    if(!$uid) header("location:index.php?n=login");
    //note 分配客服
    $pay_image = 'pay02.gif';
    $pay_h = 'pay';
    //allotserver($uid);
    include MooTemplate('public/paymentchannel02', 'module');
}

//note 选择付款方式导航 -- 钻石会员
function payment_channel_diamond() {
    global $_MooClass,$dbTablePre,$uid,$user_arr,$yeepay_banktype,$urltestkey;
//  $allow_ip = array('220.178.112.174','61.190.44.98','127.0.0.1','220.178.123.74','221.130.166.242','120.193.108.166','61.190.22.14','61.190.10.254','124.73.152.192');
//  $cur_ip = GetIP();
//  $passyou = '';
//  if(in_array($cur_ip,$allow_ip)){
//      $passyou = '1'; 
//  }else{
//      if(strpos($cur_ip,'192.168') !== FALSE){
//          $passyou = '1'; 
//      }
//  }
    if(!$uid) header("location:index.php?n=login");
    //note 分配客服 
    $pay_image = 'pay03.gif';
    $pay_h = 'pay_diamond';
    //allotserver($uid);
    include MooTemplate('public/paymentchannel02', 'module');
}

//note 选择付款方式导航 -- 铂金会员
function payment_channel_platinum() {
    global $_MooClass,$dbTablePre,$uid,$user_arr,$yeepay_banktype,$urltestkey;
    if(!$uid) header("location:index.php?n=login");
    //note 分配客服 
    $pay_image = 'pay04.gif';
    $pay_h = 'pay_platinum';
    //allotserver($uid);
    include MooTemplate('public/paymentchannel02', 'module');
}

//note 城市之星
function payment_city_star(){
    global $_MooClass,$dbTablePre,$uid,$user_arr,$yeepay_banktype,$urltestkey;
//  $allow_ip = array('220.178.112.174','61.190.44.98','127.0.0.1','220.178.123.74','221.130.166.242','120.193.108.166','61.190.22.14','61.190.10.254','124.73.152.192');
//  $cur_ip = GetIP();
//  $passyou = '';
//  if(in_array($cur_ip,$allow_ip)){
//      $passyou = '1'; 
//  }else{
//      if(strpos($cur_ip,'192.168') !== FALSE){
//          $passyou = '1'; 
//      }
//  }
    if(!$uid) header("location:index.php?n=login");
    if(!in_array($user_arr['s_cid'],array(0,1))){
        MooMessage('只有高级会员才能开通此服务,请先升级为高级会员。','index.php?n=payment');
    }
    //note 分配客服 
    $pay_image = 'pay01.gif';
    $pay_h = 'city_star';
    //allotserver($uid);
    include MooTemplate('public/paymentchannel02', 'module');
}

//note 选择付款方式导航 -- 高级会员升级钻石 
function payment_add_money() {
    global $_MooClass,$dbTablePre,$uid,$user_arr,$yeepay_banktype,$urltestkey;
//  $allow_ip = array('220.178.112.174','61.190.44.98','127.0.0.1','220.178.123.74','221.130.166.242','120.193.108.166','61.190.22.14','61.190.10.254','124.73.152.192');
//  $cur_ip = GetIP();
//  $passyou = '';
//  if(in_array($cur_ip,$allow_ip)){
//      $passyou = '1'; 
//  }else{
//      if(strpos($cur_ip,'192.168') !== FALSE){
//          $passyou = '1'; 
//      }
//  }
    if(!$uid) header("location:index.php?n=login");

    if(empty($h_pay) && $user_arr['s_cid'] != 1){
        MooMessage('你的请求有误。','index.php');
    }elseif(!empty($h_pay) && $user_arr['s_cid'] == 1){
        MooMessage('你的请求有误。','index.php');
    }

    //note 分配客服 
    $pay_image = 'pay04.gif';
    $pay_h = 'pay_add_money';
    //allotserver($uid);
    include MooTemplate('public/paymentchannel02', 'module');
}
//note 网上银行线上支付
function payment_bank(){
    global $_MooClass,$dbTablePre,$uid,$user_arr,$paymoney,$paymoney2,$payment_code,$activitytime1,$activitytime2,$urltestkey;
    
    if(empty($uid)) header("location:index.php?n=login");
    
    $res_sid = $_MooClass['MooMySQL']->getOne("select sid from {$dbTablePre}members where uid='{$uid}'");
    $sid = $res_sid['sid'];

    $channel=MooGetGPC('channel', 'string','P');
    $bank=MooGetGPC('bank', 'string','P');

    $pay_type = array('pay','pay_diamond','city_star','pay_add_money');
    
    if(!in_array($channel,$pay_type)){
        MooMessage('您选择的服务有误。','index.php?n=payment');
    }
    if(empty($bank)){
        if($channel=='pay_diamond'){
            MooMessage('请选择网上银行。','index.php?n=payment&h=channel_diamond');
        }elseif($channel=='city_star'){
            MooMessage('请选择网上银行。','index.php?n=payment&h=city_star');
        }elseif($channel=='pay_add_money'){
            MooMessage('请选择网上银行。','index.php?n=payment&h=add_money');
        }else{
            MooMessage('请选择网上银行。','index.php?n=payment&h=channel');
        }
    }
    
    $time = time();
    $merchantKey=$payment_code['yeepay']['merchantKey'];
    $p0_Cmd='Buy';
    
    $p1_MerId=$payment_code['yeepay']['p1_MerId'];
    $p2_Order=date('YmdHms',$time).$uid;

    if($channel=='pay'){    //note 高级会员
        if($time>=strtotime($activitytime1)&&$time<strtotime($activitytime2)){
            $p3_Amt=$paymoney2['vip'];
        }else{
            $p3_Amt=$paymoney['vip'];
        }
        $p5_Pid=iconv("UTF-8","GB2312",'真爱一生网高级会员');
        $pa_MP = '1';   //0钻石会员 1高级会员 2城市之星
        if($user_arr['s_cid']=='1'){
            $sql="insert into {$dbTablePre}payment_new (uid,pay_type,pay_bank,order_id,status,pay_money,pay_service,apply_sid,apply_time,contact) values('{$uid}','2','yeepay','{$p2_Order}','0','{$p3_Amt}','3','{$sid}','{$time}','{$user_arr['telphone']}')";
        }else{
            $sql="insert into {$dbTablePre}payment_new (uid,pay_type,pay_bank,order_id,status,pay_money,pay_service,apply_sid,apply_time,contact) values('{$uid}','2','yeepay','{$p2_Order}','0','{$p3_Amt}','1','{$sid}','{$time}','{$user_arr['telphone']}')";
        }
        $_MooClass['MooMySQL']->query($sql);
    }elseif($channel=='city_star'){
        if($time>=strtotime($activitytime1)&&$time<strtotime($activitytime2)){
            $p3_Amt=$paymoney2['citystar'];
        }else{
            $p3_Amt=$paymoney['citystar'];
        }
        $p5_Pid=iconv("UTF-8","GB2312",'真爱一生网城市之星');
        $pa_MP = '2';   //0钻石会员 1高级会员 2城市之星
        $sql="insert into {$dbTablePre}payment_new (uid,pay_type,pay_bank,order_id,status,pay_money,pay_service,apply_sid,apply_time,contact) values('{$uid}','2','yeepay','{$p2_Order}','0','{$p3_Amt}','2','{$sid}','{$time}','{$user_arr['telphone']}')";
        $_MooClass['MooMySQL']->query($sql);
    }elseif($channel=='pay_diamond'){   //note 钻石会员
        if($time>=strtotime($activitytime1)&&$time<strtotime($activitytime2)){
            $p3_Amt=$paymoney2['diamond'];
        }else{
            $p3_Amt=$paymoney['diamond'];
        }
        $p5_Pid=iconv("UTF-8","GB2312",'真爱一生网钻石会员');
        $pa_MP = '0';
        $sql="insert into {$dbTablePre}payment_new (uid,pay_type,pay_bank,order_id,status,pay_money,pay_service,apply_sid,apply_time,contact) values('{$uid}','2','yeepay','{$p2_Order}','0','{$p3_Amt}','0','{$sid}','{$time}','{$user_arr['telphone']}')";
        $_MooClass['MooMySQL']->query($sql);
    }elseif($channel=='pay_add_money'){   //note 补款
        if($time>=strtotime($activitytime1)&&$time<strtotime($activitytime2)){
            $p3_Amt=$paymoney2['add_money'];
        }else{
            $p3_Amt=$paymoney['add_money'];
        }
        $p5_Pid=iconv("UTF-8","GB2312",'高级会员升级钻石会员');
        $pa_MP = 5;
        $sql="insert into {$dbTablePre}payment_new (uid,pay_type,pay_bank,order_id,status,pay_money,pay_service,apply_sid,apply_time,contact) values('{$uid}','2','yeepay','{$p2_Order}','0','{$p3_Amt}','5','{$sid}','{$time}','{$user_arr['telphone']}')";
        $_MooClass['MooMySQL']->query($sql);
    }
    $p4_Cur='CNY';
    $p6_Pcat = iconv("UTF-8","GB2312",'真爱一生网服务');
    $p7_Pdesc = iconv("UTF-8","GB2312",'升级会员');
    $p8_Url=$payment_code['yeepay']['p8_Url'];
    $p9_SAF=0;
    $pd_FrpId=$bank;
    $pr_NeedResponse = 1;   //启用应答机制
    $merchantKey=$payment_code['yeepay']['merchantKey'];

    include "./module/payment/yeepaycommon.php";
    //note 调用签名函数生成签名串
    $hmac = getReqHmacString($p0_Cmd,$p1_MerId,$p2_Order,$p3_Amt,$p4_Cur,$p5_Pid,$p6_Pcat,$p7_Pdesc,$p8_Url,$p9_SAF,$pa_MP,$pd_FrpId,$pr_NeedResponse,$merchantKey);

// echo $hmac;exit;
    $jump ="<html>
<head>
<title>please wait...</title>
<meta http-equiv='Content-Type' content='text/html; charset=gb2312' />
</head>
<body onLoad='document.yeepay.submit();'>
<form name='yeepay' action='https://www.yeepay.com/app-merchant-proxy/node' method='post'>
<input type='hidden' name='p0_Cmd'                  value='".$p0_Cmd."'>
<input type='hidden' name='p1_MerId'                value='".$p1_MerId."'>
<input type='hidden' name='p2_Order'                value='".$p2_Order."'>
<input type='hidden' name='p3_Amt'                  value='".$p3_Amt."'>
<input type='hidden' name='p4_Cur'                  value='".$p4_Cur."'>
<input type='hidden' name='p5_Pid'                  value='".$p5_Pid."'>
<input type='hidden' name='p6_Pcat'                 value='".$p6_Pcat."'>
<input type='hidden' name='p7_Pdesc'                value='".$p7_Pdesc."'>
<input type='hidden' name='p8_Url'                  value='".$p8_Url."'>
<input type='hidden' name='p9_SAF'                  value='0'>
<input type='hidden' name='pa_MP'                       value='".$pa_MP."'>
<input type='hidden' name='pd_FrpId'                value='".$pd_FrpId."'>
<input type='hidden' name='pr_NeedResponse' value='".$pr_NeedResponse."'>
<input type='hidden' name='hmac'                        value='".$hmac."'>
</form>
</body>
</html>";

echo $jump;
}

//note 线上支付
function payment_onlinepay(){
    global $_MooClass,$dbTablePre,$uid,$pay_sty,$payment_code,$user_arr,$paymoney,$paymoney2,$activitytime1,$activitytime2;
    
    if(empty($uid)) header("location:index.php?n=login");
    
    $res_sid = $_MooClass['MooMySQL']->getOne("select sid from {$dbTablePre}members where uid='{$uid}'");
    $sid = $res_sid['sid'];
    
    $channel=MooGetGPC('channel', 'string','P');
    $pay=MooGetGPC('pay', 'string','P');
    $pay_type = array('pay','pay_diamond','city_star','pay_add_money');
    $time = time();
    $order=array();
    if(!in_array($channel,$pay_type)){
        MooMessage('您选择的服务有误。','index.php?n=payment');
    }
    if(empty($pay)){
        if($channel=='pay_diamond'){
            MooMessage('请选择支付方式。','index.php?n=payment&h=channel_diamond');
        }elseif($channel=='city_star'){
            MooMessage('请选择支付方式。','index.php?n=payment&h=city_star');
        }elseif($channel=='pay_add_money'){
            MooMessage('请选择支付方式。','index.php?n=payment&h=add_money');
        }else{
            MooMessage('请选择支付方式。','index.php?n=payment&h=channel');
        }
    }
    if($channel=='pay'){    //note 高级会员
        if($time>=strtotime($activitytime1)&&$time<strtotime($activitytime2)){
            $order['order_amount']=$paymoney2['vip'];
        }else{
            $order['order_amount']=$paymoney['vip'];
        }
        $order['order_type']='1';//0钻石会员 1高级会员 2城市之星
        $order['subject']="真爱一生网高级会员";
    }elseif($channel=='city_star'){
        if($time>=strtotime($activitytime1)&&$time<strtotime($activitytime2)){
            $order['order_amount']=$paymoney2['citystar'];
        }else{
            $order['order_amount']=$paymoney['citystar'];
        }
        $order['order_type']='2';
        $order['subject']="真爱一生网城市之星";
    }elseif($channel=='pay_diamond'){
        if($time>=strtotime($activitytime1)&&$time<strtotime($activitytime2)){
            $order['order_amount']=$paymoney2['diamond'];
        }else{
            $order['order_amount']=$paymoney['diamond'];
        }
        $order['order_type']='0';
        $order['subject']="真爱一生网钻石会员";
    }elseif($channel=='pay_add_money'){
        if($time>=strtotime($activitytime1)&&$time<strtotime($activitytime2)){
            $order['order_amount']=$paymoney2['add_money'];
        }else{
            $order['order_amount']=$paymoney['add_money'];
        }
        $order['order_type']=5;
        $order['subject']="高级会员升级钻石会员";
    }

    $order['order_sn']=date('YmdHms',$time).$uid;   
    $payment=get_payment($pay);

    $plugin_file ="module/payment/".$pay.".php";
        if (file_exists($plugin_file)){
            /* 根据支付方式代码创建支付类的对象并调用其响应操作方法 */
            include_once($plugin_file);
            $pays = new $pay();
            $order['order_amount']+= (float)$payment['m_fee']*$order['order_amount']/100;
            $order['order_amount']=round($order['order_amount'],2); //支付金额保留两位小数
            if($user_arr['s_cid']=='1' && $order['order_type']=='1'){
                $sql="insert into {$dbTablePre}payment_new (uid,pay_type,pay_bank,order_id,status,pay_money,pay_service,apply_sid,apply_time,contact) values('{$uid}','2','{$pay}','{$order['order_sn']}','0','{$order['order_amount']}','3','{$sid}','{$time}','{$user_arr['telphone']}')";
            }else{
                $sql="insert into {$dbTablePre}payment_new (uid,pay_type,pay_bank,order_id,status,pay_money,pay_service,apply_sid,apply_time,contact) values('{$uid}','2','{$pay}','{$order['order_sn']}','0','{$order['order_amount']}','{$order['order_type']}','{$sid}','{$time}','{$user_arr['telphone']}')";
            }
            $_MooClass['MooMySQL']->query($sql);
            $o=get_pay_one($order['order_sn']);
            $order['log_id']=$o['pid'];
            $reqUrl= $pays->get_code($order,$payment);
        }
//      echo $reqUrl;exit;
        if(!empty($reqUrl)){
            if($pay!='yeepay'){
                echo '<script language="JavaScript">self.location=\''.$reqUrl.'\';</script>';
            }else{
                echo $reqUrl;
            }
        }else{
            MooMessage('您的请求有误请重新提交请求。','index.php?n=payment');
        }
}

//note 网上刷卡支付
function payment_creditcardinline(){
    global $_MooClass,$dbTablePre,$uid,$pay_sty,$paymoney,$payment_code,$user_arr,$paymoney2,$activitytime1,$activitytime2;
    /*
    $allow_ip = array('220.178.112.174','61.190.44.98','127.0.0.1','220.178.123.74','221.130.166.242','120.193.108.166','61.190.22.14','61.190.10.254','124.73.152.192');
    $cur_ip = GetIP();
    if(in_array($cur_ip,$allow_ip)){
      $paymoney = array(
          'diamond'=>'0.10',   //六个月钻石会员费用
          'vip'=>'0.10',            //三个月高级会员费用
          'citystar'=>'0.10',     //一个月城市之星费用
      );
    }else{
      if(strpos($cur_ip,'192.168') !== FALSE){
          $paymoney = array(
              'diamond'=>'0.10',   //六个月钻石会员费用
              'vip'=>'0.10',            //三个月高级会员费用
              'citystar'=>'0.10',     //一个月城市之星费用
          );
      }
    }
     */
    
    if(empty($uid)) header("location:index.php?n=login");
    
    
    $res_sid = $_MooClass['MooMySQL']->getOne("select sid from {$dbTablePre}members where uid='{$uid}'");
    $sid = $res_sid['sid'];

    $ispost=MooGetGPC('ispost', 'integer','P');
    $channel=MooGetGPC('channel', 'string','P');
    $pay=MooGetGPC('pay', 'string','P');
    $pay_type = array('pay','pay_diamond','city_star','pay_add_money');
    $time = time();
    $year = date("Y");
    $order=array();
    if(!in_array($channel,$pay_type)){
        MooMessage('您选择的服务有误。','index.php?n=payment');
        exit;
    }
    if(empty($pay)){
        if($channel=='pay_diamond'){
            MooMessage('请选择支付方式。','index.php?n=payment&h=channel_diamond');
            exit;
        }elseif($channel=='city_star'){
            MooMessage('请选择支付方式。','index.php?n=payment&h=city_star');
            exit;
        }elseif($channel=='pay_add_money'){
            MooMessage('请选择支付方式。','index.php?n=payment&h=add_money');
        }else{
            MooMessage('请选择支付方式。','index.php?n=payment&h=channel');
            exit;
        }
    }

    if($channel=='pay'){    //note 高级会员
        if($time>=strtotime($activitytime1)&&$time<strtotime($activitytime2)){
            $order['order_amount']=$paymoney2['vip'];
        }else{
            $order['order_amount']=$paymoney['vip'];
        }
        $order['order_type']='1';//0钻石会员 1高级会员 2城市之星
        $order['subject']="真爱一生网高级会员";
    }elseif($channel=='city_star'){
        if($time>=strtotime($activitytime1)&&$time<strtotime($activitytime2)){
            $order['order_amount']=$paymoney2['citystar'];
        }else{
            $order['order_amount']=$paymoney['citystar'];
        }
        $order['order_type']='2';
        $order['subject']="真爱一生网城市之星";
    }elseif($channel=='pay_diamond'){
        if($time>=strtotime($activitytime1)&&$time<strtotime($activitytime2)){
            $order['order_amount']=$paymoney2['diamond'];
        }else{
            $order['order_amount']=$paymoney['diamond'];
        }
        $order['order_type']='0';
        $order['subject']="真爱一生网钻石会员";
    }elseif($channel=='pay_add_money'){
        if($time>=strtotime($activitytime1)&&$time<strtotime($activitytime2)){
            $order['order_amount']=$paymoney2['add_money'];
        }else{
            $order['order_amount']=$paymoney['add_money'];
        }
        $order['order_type']=5;
        $order['subject']="高级会员升级钻石会员";
    }
    
    //内部测试号 0.01消费
    if($uid=='20746717' && $user_arr['username']=='ikscher@163.com') { $order['order_amount']=0.01;}
    
    if($ispost=='1'){
        require_once 'yeepayeposcommon.php';
        $payment=get_payment($pay);
        $haystack=array('ECITICCREDIT','ICBCCREDIT','BOSHCREDIT','BOCCREDIT','CMBCCREDIT','GDBCREDIT','CIBCREDIT','CCBCREDIT','HXBCREDIT','CMBCHINACREDIT','ABCCREDIT','PINGANREDIT');
        // 业务类型
        $p0_Cmd=$payment['epossale'];
        // 交易币种
        $p4_Cur=$payment['cny'];
        // 是否需要应答
        $pr_NeedResponse=$payment['needresponse'];
        // 商户编号
        $p1_MerId=$payment['p1_MerId'];
        // 商户密钥,用于生成hmac(hmac的说明详见文档)key为测试
        $merchantKey=$payment['merchantKey'];
        // 正式地址
        $actionURL=$payment['actionURL'];
        // 获取订单号
        $p2_Order=date('YmdHms',$time).$uid;
        // 获取本地提交消费金额
        $p3_Amt=$order['order_amount'];
        // 获取本地提交商品名称
        $p5_Pid=$order['subject'];
        $p5_Pid=iconv("UTF-8","GB2312",$p5_Pid);
        // 获取本地提交接收支付结果地址
        $p8_Url=$payment['p8_Url'];
        // 获取本地提交证件类型
        $pa_CredType=MooGetGPC('pa_CredType', 'string','P');
        // 获取本地提交证件号码
        $pb_CredCode=MooGetGPC('pb_CredCode', 'string','P');
        $pb_CredCode=iconv("UTF-8","GB2312",$pb_CredCode);
        // 获取本地提交银行编码
        $pd_FrpId=MooGetGPC('pd_FrpId', 'string','P');
        $pd_FrpId=trim($pd_FrpId);
        // 获取本地提交消费者手机号
        $pe_BuyerTel=MooGetGPC('pe_BuyerTel', 'string','P');
        // 获取本地提交消费者姓名
        $pf_BuyerName=MooGetGPC('pf_BuyerName', 'string','P');
        $pf_BuyerName=iconv("UTF-8","GB2312",$pf_BuyerName);
        // 获取本地提交信用卡卡号
        $pt_ActId=MooGetGPC('pt_ActId', 'string','P');
        // 获取本地提交有效期（年）
        $pa2_ExpireYear=MooGetGPC('pa2_ExpireYear', 'string','P');
        // 获取本地提交有效期（月）
        $pa3_ExpireMonth=MooGetGPC('pa3_ExpireMonth', 'string','P');
        // 获取本地提交信用卡CVV码
        $pa4_CVV=MooGetGPC('pa4_CVV', 'string','P');
        if($pa_CredType==''||$pb_CredCode==''||$pd_FrpId==''||$pe_BuyerTel==''||$pf_BuyerName==''||$pt_ActId==''||$pa2_ExpireYear==''||$pa3_ExpireMonth==''||$pa4_CVV==''){
            if($channel=='pay_diamond'){
                MooMessage('您有个选项没有填写！','index.php?n=payment&h=channel_diamond');
            }elseif($channel=='city_star'){
                MooMessage('您有个选项没有填写！','index.php?n=payment&h=city_star');
            }elseif($channel=='add_money'){
                MooMessage('您有个选项没有填写！','index.php?n=payment&h=add_money');
            }else{
                MooMessage('您有个选项没有填写！','index.php?n=payment&h=channel');
            }
            exit;
        }
        
        if(!in_array($pd_FrpId,$haystack)){
            if($channel=='pay_diamond'){
                MooMessage('请输入对应的发卡行英文代号！','index.php?n=payment&h=channel_diamond');
            }elseif($channel=='city_star'){
                MooMessage('请输入对应的发卡行英文代号！','index.php?n=payment&h=city_star');
            }elseif($channel=='add_money'){
                MooMessage('请输入对应的发卡行英文代号！','index.php?n=payment&h=add_money');
            }else{
                MooMessage('请输入对应的发卡行英文代号！','index.php?n=payment&h=channel');
            }
            exit;
        }

        //校验身份证号码
        if($pa_CredType=='IDCARD'){
            if(!validation_filter_id_card($pb_CredCode)){
                if($channel=='pay_diamond'){
                    MooMessage('您填写的身份证号码不正确！','index.php?n=payment&h=channel_diamond');
                }elseif($channel=='city_star'){
                    MooMessage('您填写的身份证号码不正确！','index.php?n=payment&h=city_star');
                }elseif($channel=='add_money'){
                    MooMessage('您填写的身份证号码不正确！','index.php?n=payment&h=add_money');
                }else{
                    MooMessage('您填写的身份证号码不正确！','index.php?n=payment&h=channel');
                }
                exit;
            }
        }
        //校验手机号码
        if(!preg_match("/^(((13[0-9]{1})|14[0-9]{1}|15[0-9]{1}|18[0-9]{1}|)+\d{8})$/",$pe_BuyerTel)){
            if($channel=='pay_diamond'){
                MooMessage('您填写的手机号码不正确！','index.php?n=payment&h=channel_diamond');
            }elseif($channel=='city_star'){
                MooMessage('您填写的手机号码不正确！','index.php?n=payment&h=city_star');
            }elseif($channel=='add_money'){
                MooMessage('您填写的手机号码不正确！','index.php?n=payment&h=add_money');
            }else{
                MooMessage('您填写的手机号码不正确！','index.php?n=payment&h=channel');
            }
            exit;
        }
        if(!($pa2_ExpireYear>=$year&&$pa2_ExpireYear<=2099)){
            if($channel=='pay_diamond'){
                MooMessage('您填写的信用卡有效期（年）不正确！','index.php?n=payment&h=channel_diamond');
            }elseif($channel=='city_star'){
                MooMessage('您填写的信用卡有效期（年）不正确！','index.php?n=payment&h=city_star');
            }elseif($channel=='add_money'){
                MooMessage('您填写的信用卡有效期（年）不正确！','index.php?n=payment&h=add_money');
            }else{
                MooMessage('您填写的信用卡有效期（年）不正确！','index.php?n=payment&h=channel');
            }
            exit;
        }
        if(!($pa3_ExpireMonth>=01&&$pa3_ExpireMonth<=12)){
            if($channel=='pay_diamond'){
                MooMessage('您填写的信用卡有效期（月）不正确！','index.php?n=payment&h=channel_diamond');
            }elseif($channel=='city_star'){
                MooMessage('您填写的信用卡有效期（月）不正确！','index.php?n=payment&h=city_star');
            }elseif($channel=='add_money'){
                MooMessage('您填写的信用卡有效期（月）不正确！','index.php?n=payment&h=add_money');
            }else{
                MooMessage('您填写的信用卡有效期（月）不正确！','index.php?n=payment&h=channel');
            }
            exit;
        }
        if(!(strlen($pa4_CVV)==3||strlen($pa4_CVV)==4)){
            if($channel=='pay_diamond'){
                MooMessage('请输入信用卡背面的3或4位cvv码！','index.php?n=payment&h=channel_diamond');
            }elseif($channel=='city_star'){
                MooMessage('请输入信用卡背面的3或4位cvv码！','index.php?n=payment&h=city_star');
            }elseif($channel=='add_money'){
                MooMessage('请输入信用卡背面的3或4位cvv码！','index.php?n=payment&h=add_money');
            }else{
                MooMessage('请输入信用卡背面的3或4位cvv码！','index.php?n=payment&h=channel');
            }
            exit;
        }
        // 日志文件路径
        $logName=$payment['logyeepayepos'];
        // 构造请求业务处理所需参数数组
        $bizArray=Array('p0_Cmd'=>$p0_Cmd,'p1_MerId'=>$p1_MerId,'p2_Order'=>$p2_Order,'p3_Amt'=>$p3_Amt,'p4_Cur'=>$p4_Cur,'p5_Pid'=>$p5_Pid,'p8_Url'=>$p8_Url,'pa_CredType'=>$pa_CredType,'pb_CredCode'=>$pb_CredCode,'pd_FrpId'=>$pd_FrpId,'pe_BuyerTel'=>$pe_BuyerTel,'pf_BuyerName'=>$pf_BuyerName,'pt_ActId'=>$pt_ActId,'pa2_ExpireYear'=>$pa2_ExpireYear,'pa3_ExpireMonth'=>$pa3_ExpireMonth,'pa4_CVV'=>$pa4_CVV);
        
        /*-------------------------------------------------------*/
        // 接入程序员关注部分（此函数可选择使用）   
        // 调用您的业务逻辑处理函数，比如：将商品状态改为“下单”
        if($user_arr['s_cid']=='1' && $order['order_type']=='1'){
            $sql="insert into {$dbTablePre}payment_new (uid,pay_type,pay_bank,order_id,status,pay_money,pay_service,apply_sid,apply_time,contact) values('{$uid}','2','{$pay}','{$p2_Order}','0','{$order['order_amount']}','3','{$sid}','{$time}','{$user_arr['telphone']}')";
        }else{
            $sql="insert into {$dbTablePre}payment_new (uid,pay_type,pay_bank,order_id,status,pay_money,pay_service,apply_sid,apply_time,contact) values('{$uid}','2','{$pay}','{$p2_Order}','0','{$order['order_amount']}','{$order['order_type']}','{$sid}','{$time}','{$user_arr['telphone']}')";
        }
        $_MooClass['MooMySQL']->query($sql);
        /*-------------------------------------------------------*/
        // 调用支付业务函数，接入程序员无需关注
        eposSale($bizArray,$actionURL,$merchantKey,$logName);
        exit;
    }else{
        include MooTemplate('public/fillintheinformation', 'module');
    }
}


//note 调用 支付成功接口
function payment_payreturnurl(){
    global $_MooClass,$dbTablePre,$uid,$pay_sty,$payment_code,$user_arr,$tenpay_banktype;
    $code=MooGetGPC('payurl', 'string','G');
    $pay = explode(',',$code);
    if($pay['0'] == '1' || $pay['0'] == '2'){
        $uid = $pay['8'];
        $sql = "SELECT nickname,telphone FROM {$dbTablePre}members WHERE uid='{$uid}'";
        $res = $_MooClass['MooMySQL']->getOne($sql);
        $bank_type = $pay['3'];
        $type = $pay['9'];
        if($type == 'tenpay'){
            $bank_type = $tenpay_banktype["$bank_type"];
        }elseif($type == 'alipay'){
            $bank_type = '支付宝支付';
        }else{
            $bank_type = '易宝支付';
        }
        if($pay['6'] == '0'){
            $title = '钻石会员';
            $img = 'pay03.gif';
        }elseif($pay['6'] == '1'){
            $title = '高级会员';
            $img = 'pay02.gif';
        }elseif($pay['6'] == '2'){
            $title = '城市之星';
            $img = 'pay01.gif';
        }else{
            $title = '城市之星';
            $img = 'pay01.gif';
        }
        $payurl = array('pay'=>$pay['0'],   //选择模板
            'out_trade_no'=>$pay['1'],      //订单号
            'paytime'=>$pay['2'],               //交易时间
            'bank_type'=>$bank_type,        //银行类型
            'trade_state'=>$pay['4'],           //付款状态   0-成功，1-失败
            'get_img'=>'module/payment/templates/default/images/'.$pay['5'],                //显示哪种图片 01-错误图片   05-正确图片
            'img'=>'module/payment/templates/default/images/'.$img,
            'total_fee'=>$pay['7'],             //价格
            'uid'=>$uid,                            //UID
            'nickname'=>$res['nickname'],    //昵称
            'title'=>$title);                       //高级会员还是钻石会员
    }else{
        $payurl = array('pay'=>$pay['0'], 
        'errorinfo'=>$pay['1'],  //错误信息
        'trade_state'=>$pay['2'],   //付款状态 0-成功 1失败
        'get_img'=>'module/payment/templates/default/images/01.gif',
        'img'=>'module/payment/templates/default/images/pay02.gif');
    }
    
    if($payurl['pay'] == '1' || $payurl['pay'] == '2'){
        //付款之后跳转页面1-成功 2-失败
        include MooTemplate('public/payreturn', 'module');
        exit;
    }else if($payurl['pay'] == '3'){
        //认证签名失败 付款失败
        include MooTemplate('public/paysignfail', 'module');
        exit;
    }else{
        header("location:index.php");
    }
}

//升级高级会员
function payment_heigher(){
    global $user_arr;
    if($user_arr['s_cid'] == '1' && $user_arr['endtime'] >= $GLOBALS['timestamp']){
        $s_cid = '1'; //get_user_s_cid();
    }
    include MooTemplate('public/payment_heigher', 'module');
}

//升级为钻石会员
function payment_diamond(){
    global $user_arr;
    if($user_arr['s_cid'] == '0' && $user_arr['endtime'] >= $GLOBALS['timestamp']){
        $s_cid = '0'; //get_user_s_cid();
    }
    include MooTemplate('public/payment_diamond', 'module');
}

//升级为铂金会员
function payment_platinum(){
    global $user_arr;
    if($user_arr['s_cid'] == '0' && $user_arr['endtime'] >= $GLOBALS['timestamp']){
        $s_cid = '0'; //get_user_s_cid();
    }
    include MooTemplate('public/payment_platinum', 'module');
}

//视频疑问
function payment_ask(){
    include MooTemplate('public/payment_ask', 'module');
}

//真爱一生介绍
function payment_intro(){
    global $user_arr;
    $GLOBALS['style_name'] = 'default';
    require MooTemplate('public/payment_intro', 'module');
}

//城市之星介绍页面
function payment_city_star_intro(){
    global $_MooClass,$dbTablePre,$uid,$user_arr;
    include MooTemplate('public/payment_city_star_intro', 'module');
}

//note 非诚勿扰首页
function payment_ifyouaretheone_index(){
    include MooTemplate('public/payment_ifyouaretheone_index', 'module');
}

//note 非诚勿扰注册页
function payment_ifyouaretheone_reg(){
    global $_MooClass,$dbTablePre,$userid,$timestamp,$user_arr;
    if($_POST){
        $name = safeFilter(MooGetGPC('name', 'string','P'));
        $profession = safeFilter(MooGetGPC('profession', 'string','P'));
        $mobile = safeFilter(MooGetGPC('mobile', 'string','P'));
        $telphone = safeFilter(MooGetGPC('telphone', 'string','P'));
        $qq = safeFilter(MooGetGPC('qq', 'string','P'));
        $msn = safeFilter(MooGetGPC('msn', 'string','P'));
        $email = safeFilter(MooGetGPC('email', 'string','P'));
        
        $lovede = safeFilter(MooGetGPC('lovede', 'string','P'));
        $description = safeFilter(MooGetGPC('description', 'string','P'));
        $lovede1 = safeFilter(MooGetGPC('lovede1', 'string','P'));
        $description1 = safeFilter(MooGetGPC('description1', 'string','P'));
        $ip =  GetIP();
        $uid = $userid;
        $dateline = time();
        //note 如果用户没有填写爱情宣言或者自我描述
        if($lovede == $lovede1){
            $lovede = "";
        }
        if($description == $description1){
            $description = "";
        } 
        
        //note 限制爱情宣言和自我描述进库的字数
        $lovede = MooCutstr($lovede,"1000");
        $description = MooCutstr($description,"1000");
        
        //note 表单每一项都要填写
        if(!empty($name) && !empty($profession) && !empty($mobile) && !empty($lovede) && !empty($description)){
            $sql="insert into {$dbTablePre}ifyouaretheone (uid,name,profession,mobile,telphone,qq,msn,email,lovede,description,ip,dateline) 
            values('$uid','$name','$profession','$mobile','$telphone','$qq','$msn','$email','$lovede','$description','$ip','$dateline')";
            $_MooClass['MooMySQL']->query($sql);
            MooMessage("您的注册报名成功！", "index.php",'05');
        }else {
            MooMessage("提交失败，请填写完整！", "index.php?n=payment&h=ifyouaretheone_reg",'02');
        }
    }
    include MooTemplate('public/payment_ifyouaretheone_reg', 'module');
}


/***************************************   控制层(C)   ****************************************/

$h = MooGetGPC('h', 'string');
$back_url='index.php?'.$_SERVER['QUERY_STRING'];
switch ($h) {
    case 'channel':
        payment_channel();
    break;
    case 'channel_diamond':
        payment_channel_diamond();
    break;
    case 'add_money':
        payment_add_money();
    break;
    case 'diamond':
        payment_diamond();
    break;
    case 'platinum':
        payment_platinum();
    break;
    case 'channel_platinum':
        payment_channel_platinum();
    break;
    //note 返回支付成功
    case 'payreturnurl':
        payment_payreturnurl();
    break;
    case 'ask':
        payment_ask();
    break;
    case 'intro':
        payment_intro();
    break;
    case 'city_star':
        payment_city_star();
    break;
    case 'city_star_intro':
        payment_city_star_intro();
    break;
    case 'ifyouaretheone_index':
        payment_ifyouaretheone_index();
    break;
    case 'ifyouaretheone_reg':
        payment_ifyouaretheone_reg();
    break;
    case 'onlinebank':
        payment_bank();
    break;
    case 'onlinepay':
        payment_onlinepay();
    break;
    case 'creditcardinline':
        payment_creditcardinline();
    break;
    default:
        payment_heigher();
    break;
}

?>

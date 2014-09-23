<?php
/*
  2011-2-18
 */
require_once dirname(__FILE__).'/./../../framwork/MooPHP.php';
require_once dirname(__FILE__).'/./config.php';
require_once dirname(__FILE__).'/./function.php';
require_once dirname(__FILE__).'/./yeepaycommon.php';

#   解析返回参数.
$return = getCallBackValue($r0_Cmd, $r1_Code, $r2_TrxId, $r3_Amt, $r4_Cur, $r5_Pid, $r6_Order, $r7_Uid, $r8_MP, $r9_BType, $hmac);
$uid = substruid($r6_Order, 14);
if(empty($uid)){
    MooMessage('您的请求有误。','../../index.php?n=payment');
}
$payment = $payment_code['yeepay'];
#   判断返回签名是否正确（True/False）
$bRet = CheckHmac($payment['p1_MerId'], $r0_Cmd, $r1_Code, $r2_TrxId, $r3_Amt, $r4_Cur, $r5_Pid, $r6_Order, $r7_Uid, $r8_MP, $r9_BType, $hmac, $payment['merchantKey']);
  //$bRet = CheckHmac($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType,$hmac);
if ($bRet) {
    if ($r1_Code == "1") {
        #   需要比较返回的金额与商家数据库中订单的金额是否相等，只有相等的情况下才认为是交易成功.
        #   并且需要对返回的处理进行事务控制，进行记录的排它性处理，在接收到支付结果通知后，判断是否进行过业务逻辑处理，不要重复进行业务逻辑处理，防止对同一条交易重复发货的情况发生.
        if ($r9_BType == "1") {
            //用户成功提示
            $paytime = date("Y.m.d H:i:s");
            $bank_type=urlencode('易宝支付');
            $payurl = array('pay' => '1', //选择模板
                'out_trade_no' => $r6_Order, //订单号
                'paytime' => $paytime, //支付时间
                'bank_type' => $bank_type, //支付银行类型
                'trade_state' => 0, //支付状态
                'get_img' => '05.gif', //01-错误图片   05-正确图片
                'img' => $r8_MP, //图片类型 0钻石会员 1高级会员 2城市之星
                'total_fee' => $r3_Amt, //支付价格
                'uid' => $uid);         //会员ID
            $payurl = implode(',', $payurl);
            $payurl = $payurl . ',yeepay';
            $jump = '../../index.php?n=payment&h=payreturnurl&payurl=' . $payurl;
            echo '<script language="JavaScript">self.location=\'' . $jump . '\';</script>';
            exit;
        } elseif ($r9_BType == "2") {
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
            $attach = $r8_MP;
            $total_fee_res = $r3_Amt;
            switch ($attach){
			    case '0':
					if ($bgtime >= strtotime($activitytime1) && $bgtime < strtotime($activitytime2)) {
						$total_money = $paymoney2['diamond'];
					} else {
						$total_money = $paymoney['diamond'];
					}

					$apply_note = '六个月钻石会员';
					$s_cid = '20';
					break;
                case '-1':
					if ($bgtime >= strtotime($activitytime1) && $bgtime < strtotime($activitytime2)) {
						$total_money = $paymoney2['platinum'];
					} else {
						$total_money = $paymoney['platinum'];
					}

					$apply_note = '六个月铂金会员';
					$s_cid = '10';
					break;
                case  '1':
					if ($bgtime >= strtotime($activitytime1) && $bgtime < strtotime($activitytime2)) {
						$total_money = $paymoney2['vip'];
					} else {
						$total_money = $paymoney['vip'];
					}
					$apply_note = '三个月高级会员';
					$s_cid = '30';
					break;
                case  '2':
					if ($bgtime >= strtotime($activitytime1) && $bgtime < strtotime($activitytime2)) {
						$total_money = $paymoney2['citystar'];
					} else {
						$total_money = $paymoney['citystar'];
					}
					$apply_note = '一个月城市之星';
					break;
               
          
                case '5':
					if ($bgtime >= strtotime($activitytime1) && $bgtime < strtotime($activitytime2)) {
						$total_money = $paymoney2['add_money'];
					} else {
						$total_money = $paymoney['add_money'];
					}
					$apply_note = '高级会员升级钻石会员';
					break;
				case '100':
				    $time = time();
					$_MooClass['MooMySQL']->query("update {$dbTablePre}certification set sms=1 where uid=$uid");
					if(!$_MooClass['MooMySQL']->affectedRows()){
						$_MooClass['MooMySQL']->query("INSERT INTO {$dbTablePre}certification (uid,sms) VALUES('$uid','1')");
					}
					
					if(MOOPHP_ALLOW_FASTDB){
						$val = $_MooClass['MooMySQL']->getOne("select * from {$dbTablePre}certification where uid=$uid  limit 1");
						$val['sms'] = 1;
						MooFastdbUpdate('certification','uid',$uid, $val); 
					}

					//$username=iconv('utf-8','gbk',MooAuthCode($_MooCookie['sftrz_username'], 'DECODE'));
					$username=MooAuthCode($_MooCookie['sftrz_username'], 'DECODE');
					$usercode=MooAuthCode($_MooCookie['sftrz_usercode'], 'DECODE');
					$mobile=MooAuthCode($_MooCookie['sftrz_mobile'], 'DECODE');
                    $sql="select id from {$dbTablePre}smsauths where uid='{$uid}' ";
                    $row=$_MooClass['MooMySQL']->getOne($sql);
                    if(!empty($row['id'])){
					     $sql = "update {$dbTablePre}smsauths set time='{$time}',realname='{$username}',idcode='{$usercode}',mobile='{$mobile}' where uid='{$uid}'";
                    }else{
                         $sql = "insert into {$dbTablePre}smsauths set uid='{$uid}',time='{$time}',realname='{$username}',idcode='{$usercode}',mobile='{$mobile}'";
                    }
					$_MooClass['MooMySQL']->query($sql);
					//重新设置诚信值
					reset_integrity($user_arr);
					echo "success";exit;
                default :
			        $total_money = $r3_Amt;
			        break;
			}

            //如果支付金额不正确 ——不改变会员状态
            if ($total_fee_res < $total_money) {
                echo "success";
                exit;
            } 
			
			

            //会员信息
            $sql = "SELECT nickname,telphone FROM {$dbTablePre}members_search WHERE uid='{$uid}'";
            $res = $_MooClass['MooMySQL']->getOne($sql,true);

            //联系方式
            $telephone = !empty($res['telphone']) ? $res['telphone'] : '';


            //更新支付状态
            $sql_getpayid = "SELECT max(id) id FROM {$dbTablePre}payment_new WHERE order_id='{$r6_Order}'";
            $getpayid = $_MooClass['MooMySQL']->getOne($sql_getpayid,true);
            $id = $getpayid['id'];
            $bank_type = "易宝支付";
            $sql_pay = "UPDATE {$dbTablePre}payment_new SET pay_time='{$bgtime}',pay_info='{$bank_type}',apply_note='{$apply_note}',status='1',check_time='{$bgtime}',contact='{$telephone}' WHERE id='{$id}'";
            $_MooClass['MooMySQL']->query($sql_pay);
			
			
			//确认更改状态成功
            $sql_com = "SELECT status FROM {$dbTablePre}payment_new WHERE id='{$id}'";
            $confirm = $_MooClass['MooMySQL']->getOne($sql_com,true);
			if(!empty($confirm)){
				if ($confirm['status'] == '1' || $confirm['status'] == '3') {
					echo "success";exit;
				} else {
					echo "fail";exit;
				}
			}
			//========by hwt 2011-12-31 BEGIN==========
			$confirm_pre=array();
			$sql_prepay="select max(id) id from web_payment_other where order_id='{$r6_Order}'";
			$getPrePayId=$_MooClass['MooMySQL']->getOne($sql_prepay,true);
			if(!empty($getPrePayId)){
			   $PreId=$getPrePayId['id'];
			   $sql_prepay="update web_payment_other set status=1,pay_time='{$bgtime}' where id={$PreId}";
			   $_MooClass['MooMySQL']->query($sql_prepay);
			   
			   $sql_com = "SELECT status FROM {$dbTablePre}payment_other WHERE id='{$PreId}'";
               $confirm_pre = $_MooClass['MooMySQL']->getOne($sql_com,true);
			    if ($confirm_pre['status'] == '1' ) {
                   echo "success";
				} else {
					echo "fail";
				}
			}
            //------------------------------
            //处理业务完毕
            //------------------------------
           
        }
    }
} else {
    $paytime = date("Y.m.d H:i:s");
    $bank_type=urlencode('易宝支付');
    $payurl = array('pay' => '2', //选择模板
        'out_trade_no' => $r6_Order, //订单号
        'paytime' => $paytime, //支付时间
        'bank_type' => $bank_type, //支付银行类型
        'trade_state' => 1, //支付状态
        'get_img' => '01.gif', //01-错误图片   05-正确图片
        'img' => $r8_MP, //图片类型 0钻石会员 1高级会员 2城市之星
        'total_fee' => $r3_Amt, //支付价格
        'uid' => $uid);         //会员ID
    $payurl = implode(',', $payurl);
    $payurl = $payurl . ',yeepay';
    $jump = '../../index.php?n=payment&h=payreturnurl&payurl=' . $payurl;
    echo '<script language="JavaScript">self.location=\'' . $jump . '\';</script>';
    exit;
}

?>

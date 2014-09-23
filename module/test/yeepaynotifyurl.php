<?php

/*
  2011-2-18
 */
require_once dirname(__FILE__) . '/./../../framwork/MooPHP.php';
require_once dirname(__FILE__) . '/./config.php';
require_once dirname(__FILE__) . '/./function.php';
require_once dirname(__FILE__) . '/./yeepayCommon.php';

#	解析返回参数.
$return = getCallBackValue($r0_Cmd, $r1_Code, $r2_TrxId, $r3_Amt, $r4_Cur, $r5_Pid, $r6_Order, $r7_Uid, $r8_MP, $r9_BType, $hmac);
#	判断返回签名是否正确（True/False）
$payment = $payment_code['yeepay'];

$bRet = CheckHmac($payment['p1_MerId'], $r0_Cmd, $r1_Code, $r2_TrxId, $r3_Amt, $r4_Cur, $r5_Pid, $r6_Order, $r7_Uid, $r8_MP, $r9_BType, $hmac, $payment['merchantKey']);

if ($bRet) {
    if ($r1_Code == "1") {

        #	需要比较返回的金额与商家数据库中订单的金额是否相等，只有相等的情况下才认为是交易成功.
        #	并且需要对返回的处理进行事务控制，进行记录的排它性处理，在接收到支付结果通知后，判断是否进行过业务逻辑处理，不要重复进行业务逻辑处理，防止对同一条交易重复发货的情况发生.
        $uid = substruid($r6_Order, 14);
        if ($r9_BType == "1") {
            //用户成功提示
            $paytime = date("Y.m.d H:i:s");
            $payurl = array('pay' => '1', //选择模板
                'out_trade_no' => $r6_Order, //订单号
                'paytime' => $paytime, //支付时间
                'bank_type' => '易宝支付', //支付银行类型
                'trade_state' => 0, //支付状态
                'get_img' => '05.gif', //01-错误图片   05-正确图片
                'img' => $r8_MP, //图片类型	0钻石会员 1高级会员 2城市之星
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
            $attach = MooGetGPC('r8_MP', 'string', 'G');
            $total_fee_res = MooGetGPC('r3_Amt', 'string', 'G');
            if ($attach == '0') {
                if ($bgtime >= strtotime($activitytime1) && $bgtime < strtotime($activitytime2)) {
                    $total_money = $paymoney2['diamond'];
                } else {
                    $total_money = $paymoney['diamond'];
                }

                $apply_note = '六个月钻石会员';
                $s_cid = '0';
            } elseif ($attach == '1') {
                if ($bgtime >= strtotime($activitytime1) && $bgtime < strtotime($activitytime2)) {
                    $total_money = $paymoney2['vip'];
                } else {
                    $total_money = $paymoney['vip'];
                }
                $apply_note = '三个月高级会员';
                $s_cid = '1';
            } elseif ($attach == '2') {
                $total_money = $paymoney['citystar'];
                $apply_note = '一个月城市之星';
            } else {
                $total_money = $paymoney['citystar'];
                $apply_note = '一个月城市之星';
            }

            //如果支付金额不正确 ——不改变会员状态
            // if ($total_fee_res < $total_money) {
                // echo "success";
                // exit;
            // }

            //会员信息
            $sql = "SELECT nickname,telphone FROM {$dbTablePre}members WHERE uid='{$uid}'";
            $res = $_MooClass['MooMySQL']->getOne($sql);

            //联系方式
            $telephone = !empty($res['telphone']) ? $res['telphone'] : '';

            //更新会员等级 城市之星不用更改等级
            if ($attach != '2') {
                $sql_uid = "UPDATE {$dbTablePre}members SET s_cid='{$s_cid}' WHERE uid='{$uid}'";
                $_MooClass['MooMySQL']->query($sql_uid);
            }

            //更新支付状态
            $sql_getpayid = "SELECT max(id) id FROM {$dbTablePre}payment_new WHERE order_id='{$out_trade_no}'";
            $getpayid = $_MooClass['MooMySQL']->getOne($sql_getpayid);
            $id = $getpayid['id'];

            $sql_pay = "UPDATE {$dbTablePre}payment_new SET pay_time='{$bgtime}',pay_info='{$bank_type}',apply_note='{$apply_note}',status='1',check_time='{$bgtime}',contact='{$telephone}' WHERE id='{$id}'";
            $_MooClass['MooMySQL']->query($sql_pay);

            //确认更改状态成功
            $sql_com_uid = "SELECT s_cid FROM {$dbTablePre}members WHERE uid='{$uid}'";
            $com_uid = $_MooClass['MooMySQL']->getOne($sql_com_uid);
            $sql_com = "SELECT status FROM {$dbTablePre}payment_new WHERE id='{$id}'";
            $confirm = $_MooClass['MooMySQL']->getOne($sql_com);
            //------------------------------
            //处理业务完毕
            //------------------------------
            if (($confirm['status'] == '1' || $confirm['status'] == '3') && $com_uid['s_cid'] != '2') {
                echo "success";
            } else {
                echo "fail";
            }
        }
    }
} else {
    $uid = substruid($r6_Order, 14);
    $paytime = date("Y.m.d H:i:s");
    $payurl = array('pay' => '1', //选择模板
        'out_trade_no' => $r6_Order, //订单号
        'paytime' => $paytime, //支付时间
        'bank_type' => '易宝支付', //支付银行类型
        'trade_state' => 1, //支付状态
        'get_img' => '01.gif', //01-错误图片   05-正确图片
        'img' => $r8_MP, //图片类型	0钻石会员 1高级会员 2城市之星
        'total_fee' => $r3_Amt, //支付价格
        'uid' => $uid);         //会员ID
    $payurl = implode(',', $payurl);
    $payurl = $payurl . ',yeepay';
    $jump = '../../index.php?n=payment&h=payreturnurl&payurl=' . $payurl;
    echo '<script language="JavaScript">self.location=\'' . $jump . '\';</script>';
    exit;
}
?>
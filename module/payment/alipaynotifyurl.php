<?php

/*
 * 功能：支付宝主动通知调用的页面（服务器异步通知页面）
 * 版本：3.1
 */
require_once dirname(__FILE__).'/./../../framwork/MooPHP.php';
require_once dirname(__FILE__).'/./alipay_notify.php';
require_once dirname(__FILE__).'/./config.php';
require_once dirname(__FILE__).'/./function.php';

$payment = $payment_code['alipay'];
$charset = 'utf-8';
$alipay = new alipay_notify($payment['alipay_partner'], $payment['alipay_key'], $charset);    //构造通知函数信息
$verify_result = $alipay->notify_verify();  //计算得出通知验证结果

if ($verify_result) {//验证成功
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
	$out_trade_no = MooGetGPC('out_trade_no', 'string', 'P');
	$uid = substruid($out_trade_no, 14);
    $attach = MooGetGPC('extra_common_param', 'string', 'P');
    $total_fee_res = MooGetGPC('total_fee', 'string', 'P');
    if ($attach == '0') {
        if ($bgtime >= strtotime($activitytime1) && $bgtime < strtotime($activitytime2)) {
            $total_money = $paymoney2['diamond'];
        } else {
            $total_money = $paymoney['diamond'];
        }

        $apply_note = '六个月钻石会员';
        $s_cid = '20';
    } elseif($attach == '-1') {
        if ($bgtime >= strtotime($activitytime1) && $bgtime < strtotime($activitytime2)) {
            $total_money = $paymoney2['platinum'];
        } else {
            $total_money = $paymoney['platinum'];
        }

        $apply_note = '六个月铂金会员';
        $s_cid = '10';
    } elseif ($attach == '1') {
        if ($bgtime >= strtotime($activitytime1) && $bgtime < strtotime($activitytime2)) {
            $total_money = $paymoney2['vip'];
        } else {
            $total_money = $paymoney['vip'];
        }
        $apply_note = '三个月高级会员';
        $s_cid = '30';
    } elseif ($attach == '2') {
        $total_money = $paymoney['citystar'];
        $apply_note = '一个月城市之星';
    } else {
        $total_money = $paymoney['citystar'];
        $apply_note = '一个月城市之星';
    }

    //如果支付金额不正确 ——不改变会员状态
    if ($total_fee_res < $total_money) {
        echo "success";
        exit;
    }

    //会员信息
    /**
    $sql = "SELECT nickname,telphone FROM {$dbTablePre}members_search WHERE uid='{$uid}'";
    $res = $_MooClass['MooMySQL']->getOne($sql);
    */
    $res = MooMembersData($uid);

    //联系方式
    $telephone = !empty($res['telphone']) ? $res['telphone'] : '';

    //更新会员等级 城市之星不用更改等级
//    if ($attach != '2') {
//        $sql_uid = "UPDATE {$dbTablePre}members SET s_cid='{$s_cid}' WHERE uid='{$uid}'";
//        $_MooClass['MooMySQL']->query($sql_uid);
//    }

    //更新支付状态
    $sql_getpayid = "SELECT max(id) id FROM {$dbTablePre}payment_new WHERE order_id='{$out_trade_no}'";
    $getpayid = $_MooClass['MooMySQL']->getOne($sql_getpayid,true);
    $id = $getpayid['id'];

    $sql_pay = "UPDATE {$dbTablePre}payment_new SET pay_time='{$bgtime}',pay_info='{$bank_type}',apply_note='{$apply_note}',status='1',check_time='{$bgtime}',contact='{$telephone}' WHERE id='{$id}'";
    $_MooClass['MooMySQL']->query($sql_pay);

    //确认更改状态成功
//    $sql_com_uid = "SELECT s_cid FROM {$dbTablePre}members WHERE uid='{$uid}'";
//    $com_uid = $_MooClass['MooMySQL']->getOne($sql_com_uid);
    $sql_com = "SELECT status FROM {$dbTablePre}payment_new WHERE id='{$id}'";
    $confirm = $_MooClass['MooMySQL']->getOne($sql_com,true);
    //------------------------------
    //处理业务完毕
    //------------------------------
    if (($confirm['status'] == '1' || $confirm['status'] == '3')) {
        echo "success";
    } else {
        echo "fail";
    }
} else {
    //验证失败
    echo "fail";
}
?>
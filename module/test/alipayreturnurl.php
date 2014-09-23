<?php

/*
 * 功能：付完款后跳转的页面（页面跳转同步通知页面）
 * 版本：3.1
 */

require_once dirname(__FILE__) . '/./alipay_notify.php';
require_once dirname(__FILE__) . '/./../../framwork/MooPHP.php';
require_once dirname(__FILE__) . '/./config.php';

//构造通知函数信息
$alipay = new alipay_notify($partner, $key, $sign_type, $_input_charset, $transport);
//计算得出通知验证结果
$verify_result = $alipay->return_verify();

if ($verify_result) {//验证成功
    //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表
    $uid = substruid($_GET['out_trade_no'], 14);
    //用户成功提示
    $paytime = date("Y.m.d H:i:s");
    $payurl = array('pay' => '1', //选择模板
        'out_trade_no' => $_GET['out_trade_no'], //订单号
        'paytime' => $paytime, //支付时间
        'bank_type' => '支付宝支付', //支付银行类型
        'trade_state' => 0, //支付状态
        'get_img' => '05.gif', //01-错误图片   05-正确图片
        'img' => $_GET['extra_common_param'], //图片类型	0钻石会员 1高级会员 2城市之星
        'total_fee' => $_GET['total_fee'], //支付价格
        'uid' => $uid);         //会员ID
    $payurl = implode(',', $payurl);
    $payurl = $payurl . ',alipay';
    $jump = '../../index.php?n=payment&h=payreturnurl&payurl=' . $payurl;
    echo '<script language="JavaScript">self.location=\'' . $jump . '\';</script>';
    exit;
} else {
    //验证失败
    $uid = substruid($_GET['out_trade_no'], 14);
    $paytime = date("Y.m.d H:i:s");
    $payurl = array('pay' => '1', //选择模板
        'out_trade_no' => $_GET['out_trade_no'], //订单号
        'paytime' => $paytime, //支付时间
        'bank_type' => '易宝支付', //支付银行类型
        'trade_state' => 1, //支付状态
        'get_img' => '01.gif', //01-错误图片   05-正确图片
        'img' => $_GET['extra_common_param'], //图片类型	0钻石会员 1高级会员 2城市之星
        'total_fee' => $_GET['total_fee'], //支付价格
        'uid' => $uid);         //会员ID
    $payurl = implode(',', $payurl);
    $payurl = $payurl . ',alipay';
    $jump = '../../index.php?n=payment&h=payreturnurl&payurl=' . $payurl;
    echo '<script language="JavaScript">self.location=\'' . $jump . '\';</script>';
    exit;
}
?>
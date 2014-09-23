<?php

/*

支付宝接口

作者：尤莹煌
*/



class alipay 
{   //返回支付按钮
    function get_code($order,$payment)
    {
     
        $charset = 'utf-8';
    

            $parameter = array(
            'service'           => 'create_direct_pay_by_user',//即时到帐
            'partner'           => $payment['alipay_partner'],//支付宝ID
            '_input_charset'    => $charset,//字符编码
            'notify_url'        => $payment['notify_url'],//异步返回地址
            'return_url'        => $payment['return_url'],//同步返回的地址
            /* 业务参数 */
            'subject'           => $order['subject'],//商品名称
            'out_trade_no'      => $order['order_sn'],//订单(商品名称和订单的组合)
            'price'             => $order['order_amount'],//订单金额
            'quantity'          => 1,
            'payment_type'      => 1,
            /* 物流参数 */
            'logistics_type'    => 'EXPRESS',//
            'logistics_fee'     => 0,
            'logistics_payment' => 'BUYER_PAY_AFTER_RECEIVE',
            /* 买卖双方信息 */
            'seller_email'      => $payment['alipay_account'],//卖家邮箱
			'extra_common_param' => $order['order_type']
        );

        ksort($parameter);
        reset($parameter);

        $param = '';
        $sign  = '';

        foreach ($parameter AS $key => $val)
        {
            $param .= "$key=" .urlencode($val). "&";
            $sign  .= "$key=$val&";
        }

        $param = substr($param, 0, -1);
        $sign  = substr($sign, 0, -1). $payment['alipay_key'];
       $reqUrl = 'https://www.alipay.com/cooperate/gateway.do?'.$param. '&sign='.md5($sign);
        return $reqUrl;
    }

    /**
     * 响应操作
     */
    function respond()
    {
        if (!empty($_POST))//异步返回时候不为空的时候
        {
            foreach($_POST as $key => $data)
            {
                $_GET[$key] = $data;
            }
        }
        $payment  = get_payment($_GET['code']);
        $seller_email = rawurldecode($_GET['seller_email']);
        $order_sn = str_replace($_GET['subject'], '', $_GET['out_trade_no']);
        $order_sn = trim($order_sn);

        /* 检查支付的金额是否相符 */
        if (!check_money($order_sn, $_GET['total_fee']))
        {
            return false;
        }

        /* 检查数字签名是否正确 */
        ksort($_GET);
        reset($_GET);

        $sign = '';
        foreach ($_GET AS $key=>$val)
        {
            if ($key != 'sign' &&$key!="n"&&$key!=="h"&&$key!="at"&&$key != 'sign_type' && $key != 'code')
            {
                $sign .= "$key=$val&";
            }
        }

        $sign = substr($sign, 0, -1) . $payment['alipay_key'];
        //$sign = substr($sign, 0, -1) . ALIPAY_AUTH;
        if (md5($sign) != $_GET['sign'])
        {
            return false;
        }

      
       if ($_GET['trade_status'] == 'TRADE_FINISHED')
        {
		     // echo $order_sn;
			 // exit;
		
            /* 改变订单状态 */
         return   order_paid($order_sn);

            // true;
        }
        else
        {
            return false;
        }
    }
}



?>
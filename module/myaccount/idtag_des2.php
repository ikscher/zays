<?php
/*
 ***************************
 * 用途：IDTAG Corp.接口的加密解密函数包
 * 创建者：Mandel Wu 
 * 创建时间：2007-09-12 22:39
 *
 * 维护历史：
 *     when who why
 ***************************
 */

function idtag_des_decode2($key,$encrypted)
{
    $encrypted = base64_decode($encrypted);

    $td = mcrypt_module_open(MCRYPT_DES,'',MCRYPT_MODE_CBC,''); //使用MCRYPT_DES算法,cbc模式
    $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
    $ks = mcrypt_enc_get_key_size($td);
    mcrypt_generic_init($td, $key, $key);       //初始处理

    $decrypted = mdecrypt_generic($td, $encrypted);       //解密

    mcrypt_generic_deinit($td);       //结束
    mcrypt_module_close($td);

    $y=pkcs5_unpad($decrypted);
    return $y;
}

function idtag_des_encode2($key,$text)
{
    $y=pkcs5_pad($text);

    $td = mcrypt_module_open(MCRYPT_DES,'',MCRYPT_MODE_CBC,''); //使用MCRYPT_DES算法,cbc模式
    $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
    $ks = mcrypt_enc_get_key_size($td);
    mcrypt_generic_init($td, $key, $key);       //初始处理

    $encrypted = mcrypt_generic($td, $y);       //解密

    mcrypt_generic_deinit($td);       //结束
    mcrypt_module_close($td);

    return base64_encode($encrypted);
}

function pkcs5_pad($text,$block=8)
{
	$pad = $block - (strlen($text) % $block);
	return $text . str_repeat(chr($pad), $pad);
}


function pkcs5_unpad($text)
{
   $pad = ord($text{strlen($text)-1});
   if ($pad > strlen($text)) return $text;
   if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) return $text;
   return substr($text, 0, -1 * $pad);
}

function get_auth_string( $seqNo, $userCode, $userName, $msisdn )
{
	$Client = new httpclient( );
	$url = "http://219.141.223.110:8080/idtag_tong_bottom/order.service";
	$method = "POST";
	$data = array(
		"icpCode" => "www.zhenaiyisheng.cc",
		"seqNo" => $seqNo,
		"userCode" => $userCode,
		"userName" => $userName,
		"msisdn" => $msisdn
	);
	$ret = $Client->httprequest( $url, $method, $data );
	$ret = $ret['body'];
	return $ret;
}
?>
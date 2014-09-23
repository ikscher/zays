<?php
header('Content-Type:text/html;charset=utf-8');

if (!isset($_GET['p']) || $_GET['p'] != 'i7651') {
    echo 'auth failure';
    exit;
}

$api_xml = @file_get_contents("http://ws.montnets.com:9002/MWGate/wmgw.asmx/MongateQueryBalance?userId=J30087&password=070003");
if ($api_xml) {
    
    // 短信剩余条数
    preg_match('/\>(\-?\d+)\</', $api_xml, $count);
    $sms_left_count = intval($count[1]);

    // 短信提醒内容
    $content = "提醒：短信剩余{$sms_left_count}条。".date('Y-m-d H:i:s');
    
    // 发送提醒
    SendMsg(array('18756916443', '18656509722'), $content, 'array');
}

function SendMsg($tel,$content,$type=''){
    $uid = 'J30087';
    $pwd = '070003';
    $content = urlencode($content);
    if($type){
        $iMobiCount=count($tel);
        $phone=implode(',',$tel);
    }else{
        $iMobiCount = 1;
        $phone=$tel;
    }

    $httpstr="http://ws.montnets.com:9002/MWGate/wmgw.asmx/MongateCsSpSendSmsNew?userId=$uid&password=$pwd&pszMobis=$phone&pszMsg=$content&iMobiCount=$iMobiCount&pszSubPort=*";
    return @file_get_contents($httpstr);
}

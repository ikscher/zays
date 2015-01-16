<?php
$visitor  = $_MooClass['MooMySQL']->getAll("SELECT * FROM {$dbTablePre}service_visitor WHERE uid = '$userid' ORDER BY vid  DESC LIMIT 0,5",false,false,true,3600*3);
$certification =$_MooClass['MooMySQL']->getOne("SELECT sms,telphone FROM {$dbTablePre}certification where uid = '$userid'",true);
//查看更多喜欢的人sql条件
$gender = $user_arr['gender'] == '0' ? '1' : '0';
$agebegin = (date("Y") - $user_arr['birthyear']) - 3;
$ageend = (date("Y") - $user_arr['birthyear']) + 3;
$workprovince = $user_arr['province'];
$workcity = $user_arr['city'];
$search_url = MOOPHP_URL."/index.php?n=search&h=quick&gender=".$gender."&age_start=".$agebegin."&age_end=".$ageend."&workprovince=".$workprovince."&workcity=".$workcity."&&imageField=&quick_search=搜索";

//note 您可能喜欢的人，匹配相同地区
$able_like = youAbleLike($l,5);

$user_per_num= getUserinfo_per($user_arr);
$user1 = $user_arr;
	MooPlugins('ipdata');
//	$member_admininfo = $_MooClass['MooMySQL']->getOne("select finally_ip from {$dbTablePre}member_admininfo where uid='{$userid}'");
//	$finally_ip = convertIp($member_admininfo['finally_ip']);
	$ip = GetIP();
	$finally_ip = convertIp($ip);
	//echo $finally_ip;
	//$news_ip = iconv('gbk','utf-8',file_get_contents('http://fw.qq.com/ipaddress'));
	//$finally_ip = $news_ip;
	if(preg_match('/(广东|广州|深圳|佛山|珠海|东莞|汕头|韶关|江门|湛江|茂名|肇庆|惠州|梅州|汕尾|河源|清远|阳江|潮州|揭阳|云浮)/',$finally_ip)){
		$finally_address = 1;
	}
	$diamond = isset($diamond) ? $diamond : '';
require MooTemplate('public/service_index','module');

?>

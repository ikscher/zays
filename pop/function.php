<?php
define('MOOPHP_ROOT', substr(__FILE__, 0, -10));
include_once '../framwork/plugins/ipdata.php';
/**
 * 获得IP
 * return string;
 */
function GetIP(){
	if(empty($_SERVER["HTTP_CDN_SRC_IP"])){
		if(!empty($_SERVER["HTTP_CLIENT_IP"])) { $cip = $_SERVER["HTTP_CLIENT_IP"]; }
		else if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) { $cip = $_SERVER["HTTP_X_FORWARDED_FOR"]; }
		else if(!empty($_SERVER["REMOTE_ADDR"])) { $cip = $_SERVER["REMOTE_ADDR"]; }
		else $cip = "";
	}else{
		$cip = $_SERVER["HTTP_CDN_SRC_IP"];
	}
	preg_match("/[\d\.]{7,15}/", $cip, $cips);
	$cip = $cips[0] ? $cips[0] : 'unknown';
	unset($cips);
	return $cip;
}

/**
*来源获取（网站来源访问和推荐用户ID）
*@返回COOKIE值在注册的时候写入
*PHPYOU
*/
function MooGetFromwhere(){
    global $_MooCookie;
	$puid=$_MooCookie['puid'] ?$_MooCookie['puid'] :MooGetGPC('puid','integer','G');
	if($puid){
		MooSetCookie('puid', $puid, 24*3600);
	}
	$website=$_MooCookie['website']?$_MooCookie['website']:MooGetGPC('website','string','G');
	if($website){
		MooSetCookie('website', $puid, 24*3600);
	}
	if( isset($_GET['wf']) && isset($_GET['st']) ){
		MooSetCookie('where_from', "http://www.7651.com/virtual/?".$_SERVER['QUERY_STRING'], 24*3600);
		return;
	}
	if( preg_match("/\/virtual\//",$_MooCookie['where_from']) ) return;
	$rf_url = $_SERVER['HTTP_REFERER'];
	// 判断是否有外来页
	if (empty($rf_url)) {
		//error_log("rf error", 0);
		return false;
	}
	
	$rf_arr = parse_url($rf_url);
	$rf_hostname = $rf_arr["host"];
	$rf_path = $rf_ar["path"];
	
	//print_r($rf_arr);
	// 判断外来是否是本站
	if ($rf_hostname == $_SERVER["HTTP_HOST"] && strstr($rf_url, "/pop/") === false && strstr($rf_url, "/reg/") === false) {
		//error_log("rf error1", 0);
		$where_from = $_MooCookie['where_from'];
		$where_from_arr = parse_url($where_from);
		if ($where_from_arr["host"] == $_SERVER["HTTP_HOST"]) {
			MooSetCookie('where_from', "", -24*3600);
		}
		
		return false;
	}

	$where_from = $_MooCookie['where_from'];
	// 判断外来与cookie中记录是否一致
	if ($where_from == $rf_url) {
		return false;
	}

	MooSetCookie('where_from', $_SERVER['HTTP_REFERER'], 24*3600);
	return true;
}
?>
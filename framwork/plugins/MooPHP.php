<?php
/*
    More & Original PHP Framwork
    Copyright (c) 2007 - 2008 IsMole Inc.

    $Id: MooPHP.php 401 2008-10-14 07:37:07Z kimi $
*/


//note 定义MooPHP框架基本常量
define('IN_MOOPHP', TRUE);
//note MooPHP的核心版本，例如：0.21 alpha
define('MOOPHP_VERSION', '0.95.399 beta');
//note 正在被访问的文件路径，例如：D:\web\MooPHP\
define('MOOPHP_ROOT', substr(__FILE__, 0, -10));
//note 正在被访问的文件URL，例如：http://www.ccvita.com/MooPHP
define('MOOPHP_URL', strtolower(substr($_SERVER['SERVER_PROTOCOL'], 0, strpos($_SERVER['SERVER_PROTOCOL'], '/'))).'://'.$_SERVER['HTTP_HOST'].substr($_SERVER['PHP_SELF'], 0, strrpos($_SERVER['PHP_SELF'], '/')));
//note REQUEST_URI
define('REQUEST_URI', isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : (isset($_SERVER['argv']) ? $_SERVER['PHP_SELF'].'?'.$_SERVER['argv'][0] : $_SERVER['PHP_SELF'] .'?'. $_SERVER['QUERY_STRING']));
define('MOOPHP_SELFURL', MOOPHP_URL.'/'.basename($_SERVER['PHP_SELF']));
define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());

//note 禁止对全局变量注入
if (isset($_REQUEST['GLOBALS']) OR isset($_FILES['GLOBALS'])) {
    exit('Request tainting attempted.');
}



$_GET['debug'] = isset($_GET['debug']) ? $_GET['debug']: 0;

//note MooPHP基础数组
$_MooPHP = $_MooBlock = $_MooCacheConfig = $_MooCookie = $_MooClass = array();
//note 数据库信息初始化
$dbHost = $dbName = $dbUser = $dbPasswd = $dbPconnect = '';
//note 初始化常用变量
$timestamp = time();
$mtime = explode(' ', microtime());
$_MooPHP['startTime'] = $mtime[1] + $mtime[0];


//图片服务器域名
define ('IMG_SITE','http://'.$_SERVER['HTTP_HOST'].'/');
//图片相对路径
define('PIC_PATH',"data/upload/images/photo");

//note 缩略图尺寸数组存储
$pic_size_arr = array (
    "1"  => array("width" => "41", "height" => "57"),
    "2" => array("width" => "139", "height" => "189"),
    "3"   => array("width" => "171", "height" => "244")
);

$pic_size_1 = $pic_size_arr['1']['width'].'_'.$pic_size_arr['1']['height'];
$pic_size_2 = $pic_size_arr['2']['width'].'_'.$pic_size_arr['2']['height'];
$pic_size_3 = $pic_size_arr['3']['width'].'_'.$pic_size_arr['3']['height'];

//note 预定义缩略图尺寸
define('PIC_SIZE_1',$pic_size_1);
define('PIC_SIZE_2',$pic_size_2);
define('PIC_SIZE_3',$pic_size_3);

//note 定义爱情故事图片存放路径
$story_img_path = "data/upload/images/story/";
define('STORY_IMG_PATH',$story_img_path);

//图片服务器路径主域 
$images_server_path = "http://img.7651.com/";
define('IMG_SERVER_PATH',$images_server_path);

//note 定义举报会员存放路径
$report_img_path = "data/upload/images/report/";
define('REPORT_IMG_PATH',$report_img_path);


//note 加载MooPHP配置文件
if(!defined('MOOPHP_USER_CONFIG')) {
    require_once MOOPHP_ROOT.'/MooConfig.php';
}

//note 定义MooPHP配置常量
!defined('MOOPHP_ALLOW_BLOCK') && define('MOOPHP_ALLOW_BLOCK', TRUE);
!defined('MOOPHP_ALLOW_CACHE') && define('MOOPHP_ALLOW_CACHE', FALSE);
!defined('MOOPHP_ALLOW_MYSQL') && define('MOOPHP_ALLOW_MYSQL', FALSE);
!defined('MOOPHP_DATA_DIR') && define('MOOPHP_DATA_DIR', MOOPHP_ROOT.'./../data');
!defined('MOOPHP_TEMPLATE_DIR') && define('MOOPHP_TEMPLATE_DIR', '../Moo-templates');
!defined('MOOPHP_TEMPLATE_URL') && define('MOOPHP_TEMPLATE_URL', 'Moo-templates');
!defined('MOOPHP_ADMIN_DIR') && define('MOOPHP_ADMIN_DIR', 'Moo-admin');
!defined('MOOPHP_COOKIE_PRE') && define('MOOPHP_COOKIE_PRE', 'Moo');
!defined('MOOPHP_COOKIE_PATH') && define('MOOPHP_COOKIE_PATH', '/');
!defined('MOOPHP_COOKIE_DOMAIN') && define('MOOPHP_COOKIE_DOMAIN', '');
!defined('MOOPHP_AUTHKEY') && define('MOOPHP_AUTHKEY', 'kimi');
!defined('MOOPHP_DEBUG') && define('MOOPHP_DEBUG', true);

//note 加载MooPHPCache配置文件
if(MOOPHP_ALLOW_BLOCK) {
    MOOPHP_ALLOW_CACHE && require_once MOOPHP_ROOT.'/MooCacheConfig.php';
    $cache = MooAutoLoad('MooCache');
}

require_once MOOPHP_ROOT.'/Global.function.php';



//note 如果系统需要使用MYSQL则，初始化
/*if(MOOPHP_ALLOW_MYSQL) {
    $db = MooAutoLoad('MooMySQL');
    $db->connect($dbHost, $dbUser, $dbPasswd, $dbName, $dbPconnect, $dbCharset);
}*/
if(MOOPHP_ALLOW_MYSQL) {
    $db = MooAutoLoad('MooMySQL');
    if(defined('FROMEWORK') && FROMEWORK==true){
    	$db->init($masterConf_ework,$slaveConf_ework);
    }else{
	    $db->init($masterConf,$slaveConf);
    }
}


//note 如果系统需要使用memcached则,初始化
if(MOOPHP_ALLOW_MEMCACHED) {
    $memcached = new Memcache;
    $memcached->connect($memcachehost, $memcacheport);
}

//note 如果系统需要使用fastdb则,初始化
if(MOOPHP_ALLOW_FASTDB) {
    $fastdb = new Memcache;
    $fastdb->connect($fastdbhost, $fastdbport);
}
//note 对GPC变量进行安全处理
if (!MAGIC_QUOTES_GPC) {
    $_GET = MooAddslashes($_GET);
    $_POST = MooAddslashes($_POST);
    $_COOKIE = MooAddslashes($_COOKIE);
    $_REQUEST = MooAddslashes($_REQUEST);
}

//note 由于php.ini中的magic_quotes_gpc设置不会对$_SERVER、$_FILES产生影响，MooPHP从安全角度出发，对$_SERVER、$_FILES均添加转义。
$_SERVER = MooAddslashes($_SERVER);
$_FILES = MooAddslashes($_FILES);

$CookiePreLength = strlen(MOOPHP_COOKIE_PRE);
foreach($_COOKIE as $key => $val) {
    if(substr($key, 0, $CookiePreLength) == MOOPHP_COOKIE_PRE) {
        $_MooCookie[(substr($key, $CookiePreLength))] = MAGIC_QUOTES_GPC ? $val : MooAddslashes($val);
    }
}
unset($CookiePreLength);

!MOOPHP_AUTHKEY && exit('MOOPHP_AUTHKEY is not defined!');


/*
*时时更新cookie操作
*/
function MooUpateCookie($uid){
    $time = time();
    global $_MooClass,$dbTablePre,$user_arr,$_MooCookie,$memcached; 
    if(MOOPHP_ALLOW_MEMCACHED){
        $updatetime = $memcached->get('updatetime'.$uid);       
    }
    if( !$updatetime && $uid){
        $memcached->set('updatetime'.$uid,$time,0,700);
        $_MooClass['MooMySQL']->query("update `{$dbTablePre}member_admininfo` set real_lastvisit='$time' where uid='$uid'");
        $_MooClass['MooMySQL']->query("update `{$dbTablePre}members_login` set lastvisit='$time',last_login_time='$time' where uid='$uid'");
        //如果开启的缓存 
        if(MOOPHP_ALLOW_FASTDB){
                $value['lastvisit']=$time;
                //$value['client']=0;
                MooFastdbUpdate('members_login','uid',$uid,$value);
        }
        
    }
    
    $auth=isset($_MooCookie['auth']) ? $_MooCookie['auth'] : 0 ;
    if($auth){
        if(isset($user_arr['automatic']) && $user_arr['automatic']==1){
            MooSetCookie('auth',$auth,86400*365);
        }
        else{
            MooSetCookie('auth',$auth,86400*7);
        }
        if($uid && $updatetime && $updatetime<($time-600)){
            $_MooClass['MooMySQL']->query("update `{$dbTablePre}member_admininfo` set real_lastvisit='$time' where uid='$uid'");
            $_MooClass['MooMySQL']->query("update `{$dbTablePre}members_login` set last_login_time='$time' where uid='$uid'");
            
            $memcached->set('updatetime'.$uid,$time,0,700);
            
            if(MOOPHP_ALLOW_FASTDB){
                $value['lastvisit']=$time;
                //$value['client']=0;
                MooFastdbUpdate('members_login','uid',$uid,$value);
            }
           
            
        }
    }
    
}
/**
*关注我委托的人   
*@param $uid 登陆ID  
**/
/*function MooSend_Contact($uid,$gender,$sid){
     global $_MooClass,$dbTablePre;
     //note 委托会员标识初始化
     $state = 0;
            
     $sql = "select other_contact_you from {$dbTablePre}service_contact  where you_contact_other='$uid' and stat!=4";
     $row = $_MooClass['MooMySQL']->getAll($sql);
     if($row){
        foreach($row as $v){
                $arr_contact[] = $v['other_contact_you'];
        }
        $other_contact_you = implode(",",$arr_contact);
     }
     
     if(!empty($other_contact_you)){
        $sql1 = " and a.uid in($other_contact_you)";
     }else{
        $sql1 = " and a.uid in('')";
        //note 没有委托的会员标识
        $state = "1";
     }

     //note 没有委托就不执行上线提醒
     if($state != '1'){
         $sql="select a.telphone,a.uid,a.nickname,a.gender from {$dbTablePre}members a,{$dbTablePre}certification b where  a.uid=b.uid and b.telphone!='' $sql1 and is_lock = 1";
         $result=$_MooClass['MooMySQL']->getAll($sql);
         MooSetCookie('iscontact',"yes",21600);//6个小时内不重复发送    
         if($result){
              $nickname="";
              if($result[0]['nickname']) $nickname = "，用户名" . $result[0]['nickname'];
              $my_gender =  $gender == 1 ? "女" : "男";
              $content="尊敬的红娘网会员您好！您委托的会员 ID:".$uid .",".$my_gender."现在正在线，请及时与TA联系。";
              foreach($result as $val){
                  $sql_s = "select count(*) as c from ". $dbTablePre . "today_send where phone = '" . $val['telphone'] . "' and sendtime = '" . date("Y-m-d") . "'";
                  $ret = $_MooClass['MooMySQL']->getOne($sql_s);
                  if($ret['c'] < 5){
                     $sql = "insert into " . $dbTablePre . "today_send values ('" . $val['uid'] . "', '" . $uid . "','" . $val['telphone'] . "', '" . date("Y-m-d") . "')";
                     $result=$_MooClass['MooMySQL']->query($sql);
                     //Push_message_intab($uid,$val['telphone'],"上线提醒",$content,$sid);  
                                        
    
                  }
              } 
         } 
     }
}*/
/*
过滤一些HTML 代码
*/
function Moorestr($str){
$str=str_replace("<br/>","",$str);
$str=str_replace("<br />","",$str);
$str=str_replace("&nbsp;","",$str);
$str=str_replace("&lt;br/&gt;","",$str);
$str=str_replace("&lt;br /&gt;","",$str);
$str=str_replace("&amp;nbsp;","",$str);
$str=str_replace("'","",$str);
$str=str_replace("珍爱","红娘",$str);

return $str;
}

function ImagickResizeImage($srcFile, $destFile, $new_w, $new_h,$zhenshu=false) {
    if ($new_w <= 0 || $new_h <= 0 || ! file_exists ( $srcFile ))
        return false;
    $src = new Imagick ( $srcFile );
    $image_format = strtolower ( $src->getImageFormat () );
    if ($image_format != 'jpeg' && $image_format != 'gif' && $image_format != 'png' && $image_format != 'jpg' && $image_format != 'bmp')
        return false;

    //如果是 jpg jpeg
    if ($image_format != 'gif') {
        $dest = $src;
        $dest->thumbnailImage ( $new_w, $new_h, true );
        $dest->writeImage ( $destFile );
        $dest->clear ();

    //gif需要以帧一帧的处理
    } else {
        $dest = new Imagick ( );
        $color_transparent = new ImagickPixel ( "transparent" ); //透明色

        $gif_i=1;
        foreach ( $src as $img ) {
            $page = $img->getImagePage ();
            $tmp = new Imagick ( );
            $tmp->newImage ( $page ['width'], $page ['height'], $color_transparent, 'gif' );
            $tmp->compositeImage ( $img, Imagick::COMPOSITE_OVER, $page ['x'], $page ['y'] );
            $tmp->thumbnailImage ( $new_w, $new_h, true );
            $dest->addImage ( $tmp );
            $dest->setImagePage ( $tmp->getImageWidth (), $tmp->getImageHeight (), 0, 0 );
            $dest->setImageDelay ( $img->getImageDelay () );
            $dest->setImageDispose ( $img->getImageDispose () );

            if($zhenshu>0){
                if($zhenshu<=$gif_i)break;
                $gif_i++;
            }
        }
        $dest->coalesceImages ();
        $dest->writeImages ( $destFile, true );

        $dest->clear ();
    }
}

//云网
/*--------------------------------
功能:HTTP接口 发送短信
修改日期:	2009-04-08
说明:		http://http.yunsms.cn/tx/?uid=用户账号&pwd=MD5位32密码&mobile=号码&content=内容
状态:
	100 发送成功
	101 验证失败
	102 短信不足
	103 操作失败
	104 非法字符
	105 内容过多
	106 号码过多
	107 频率过快
	108 号码内容空
	109 账号冻结
	110 禁止频繁单条发送
	111 系统暂定发送
	112	有错误号码
	113	定时时间不对
	114	账号被锁，10分钟后登录
	115	连接失败
	116 禁止接口发送
	117	绑定IP不正确
	120 系统升级*/

function sendSMS($uid,$pwd,$mobile,$content,$time='',$mid='')
{   
	//验证短信
	//账号55220，密码8p6hri
	//账号55215  密码rfkda9
	//提醒短信

	//$content = urlencode($content);
	$http = 'http://http.yunsms.cn/tx/';
	$data = array
		(
		'uid'=>$uid,					//用户账号
		'pwd'=>strtolower(md5($pwd)),	//MD5位32密码
		'mobile'=>$mobile,				//号码
		'content'=>$content,			//内容 如果对方是utf-8编码，则需转码iconv('gbk','utf-8',$content); 如果是gbk则无需转码
		'time'=>$time,		//定时发送
		'mid'=>$mid						//子扩展号
		);
	$re=postSMS($http,$data); 
	
	return $re;
	/* $re= postSMS($http,$data);			//POST方式提交
	if( trim($re) == '100' )
	{
		return "发送成功!";
	}
	else 
	{
		return "发送失败! 状态：".$re;
	} */ 
}

function SendMsg($mobile,$content,$type=0){
    if($type==1){//只发验证码
	    $uid='55215';
		$pwd='rfkda9';

	}else{
	    //$uid = '55220';		//用户账号
	    //$pwd = '8p6hri';		//密码
		SendMsg_($mobile,$content);
	}
    if(is_array($mobile)) $mobile=implode(',',$mobile);
    
	return sendSMS($uid,$pwd,$mobile,$content);
}

function postSMS($url,$data='')
{
	$row = parse_url($url);
	$host = $row['host'];
	$port = isset($row['port']) ? $row['port']:80;
	$file = $row['path'];
	$post='';
	while (list($k,$v) = each($data)) 
	{
		$post .= rawurlencode($k)."=".rawurlencode($v)."&";	//转URL标准码
	}
	$post = substr( $post , 0 , -1 );
	$post .='&encode=utf8';
	$len = strlen($post);
	$fp = @fsockopen( $host ,$port, $errno, $errstr, 10);
	if (!$fp) {
		return "$errstr ($errno)\n";
	} else {
		$receive = '';
		$out = "POST $file HTTP/1.1\r\n";
		$out .= "Host: $host\r\n";
		$out .= "Content-type: application/x-www-form-urlencoded\r\n";
		$out .= "Connection: Close\r\n";
		$out .= "Content-Length: $len\r\n\r\n";
		$out .= $post;		
		fwrite($fp, $out);
		while (!feof($fp)) {
			$receive .= fgets($fp, 128);
		}
		fclose($fp);
		$receive = explode("\r\n\r\n",$receive);
		unset($receive[0]);
		return implode("",$receive);
	}
}


/*
 * 高经理短信发送接口
 **/
function SendMsg_($tel,$content,$type=''){
    $uid = '1371';
    $account='zays';
    $pwd = '888666zaysw';

	$content = urlencode($content);
    if($type){
        $iMobiCount=count($tel);
        $phone=implode(',',$tel);
    }else{
        $iMobiCount = 1;
        $phone=$tel;
    }

    $httpstr="http://222.73.219.223:8888/sms.aspx?action=send&userid=$uid&account=$account&password=$pwd&mobile=$phone&content=$content&sendTime=&extno=";
	
	
    return @file_get_contents($httpstr);
    //@file_get_contents($httpstr);       before

	//关闭短信发送接口。方便测试 by chuzhaoxing
	//return true;
}



function siooSendMsg($phone,$content){
    SendMsg($phone,$content);
}

/**
@param MooGetphoto 取用户形象照路径
@param $uid 用户的 UID 
@param $style  图片样式(big,small,medium,mid,index,page,com)//BIG 原图没有缩放的 SAMLL是41*57的 
medium 是139*189  mid  是 171*244 index 是 100*125 的 page是 50*63  的  com 是 110*138
@param $jpg    图片的后缀(jpg,bmp,png,gif)
@param return  $IMGURL 返回图片URL 
*/
function MooGetphoto($uid, $style = "mid", $jpg="") {
    global $_MooClass,$dbTablePre;  
    $first_dir=substr($uid,-1,1);
    $secend_dir=substr($uid,-2,1);
    $third_dir=substr($uid,-3,1);
    $forth_dir=substr($uid,-4,1);
    $new_filedir="data/upload/userimg/".$first_dir."/".$secend_dir."/".$third_dir."/".$forth_dir."/";   
    
    $uidmd5 = $uid * 3;
        $imgurl ='sfsdf';   
    if($jpg != ""){
        $imgurl = $new_filedir . $uidmd5 . '_' . $style . '.' . $jpg;
    }else{
        $imgurl = $new_filedir . $uidmd5 . '_' . $style . '.jpg';
    }
    return trim($imgurl); 
}

/**
@param MooGetphotoAdmin 后台取用户形象照路径
@param $uid 用户的 UID 
@param $style  图片样式(big,small,medium,mid)//大 小 中
@param $jpg    图片的后缀(jpg,bmp,png,gif)
@param return  $IMGURL 返回图片URL 
*/


function MooGetphotoAdmin($uid,$style="medium",$jpg=""){
 global $_MooClass,$dbTablePre; 
    $first_dir=substr($uid,-1,1);
    $secend_dir=substr($uid,-2,1);
    $third_dir=substr($uid,-3,1);
    $forth_dir=substr($uid,-4,1);
    $new_filedir="../data/upload/userimg/".$first_dir."/".$secend_dir."/".$third_dir."/".$forth_dir."/";
    //$new_filedir=IMG_SITE."data/upload/userimg/".$first_dir."/".$secend_dir."/".$third_dir."/".$forth_dir."/"; 
    
    $uidmd5 = $uid * 3;

    if($jpg != ""){
        $imgurl = $new_filedir . $uidmd5 . '_' . $style . '.' . $jpg;
        return $imgurl;
    }else{
        $imgurl = $new_filedir . $uidmd5 . '_' . $style . '.jpg';
        return $imgurl; 
    }

}
/**
@param MooGetphoto 获取成功故事封面图径路
@param $sid 故事id 
@param $uid 用户的 UID 
@param $style  图片样式(big,small,medium)//大 小 中
@param $jpg    图片的后缀(jpg,bmp,png,gif)
@param return  $IMGURL 返回图片URL 
*/

function MooGetstoryphoto($sid,$uid,$style,$jpg=""){
    global $_MooClass,$dbTablePre;
    $filedir="data/upload/story/";
    $uid=(int)(trim($uid));
    $sidmd5=$sid*3;
    $imgurl=$filedir.$sidmd5.'_'.$style.'.';

    if($jpg != ""){
        $imgurl = $filedir . $sidmd5 . '_' . $style . '.' . $jpg;
        return $imgurl;
    }else{
        $imgurl = $filedir . $sidmd5 . '_' . $style . '.jpg';
        return $imgurl; 
    }
}

/**
*@param 屏蔽会员发送消息中的特殊字符
*@param $str 需要替换的字符串
*/
function MooStrReplace($str){
    //特殊字符列表
    $newStr = preg_replace('/\d|二|三|四|五|六|七|八|九|十|壹|弌|贰|弍|叁|弎|肆|柒|捌|玖|⒈|⒉|⒊|⒋|⒌|⒍|⒎|⒏|⒐|⒑|⒒|⒓|⒔|⒕|⒖|⒗|⒘|⒙|⒚|⒛|⑴|⑵|⑶|⑷|⑸|⑹|⑺|⑻|⑼|⑽|⑾|⑿|⒀|⒁|⒂|⒃|⒄|⒅|⒆|⒇|①|②|③|④|⑤|⑥|⑦|⑧|⑼|⑩|㈠|㈡|㈢|㈣|㈤|㈥|㈦|㈧|㈨|㈩|Ⅰ|Ⅱ|Ⅲ|Ⅳ|Ⅴ|Ⅵ|Ⅶ|Ⅷ|Ⅸ|Ⅹ|Ⅺ|Ⅻ|０|１|２|３|４|５|６|７|８|９|Ａ|Ｂ|Ｃ|Ｄ|Ｅ|Ｆ|Ｇ|Ｈ|Ｉ|Ｊ|Ｋ|Ｌ|Ｍ|Ｎ|Ｏ|Ｐ|Ｑ|Ｒ|Ｓ|Ｔ|Ｕ|Ｖ|Ｗ|Ｘ|Ｙ|Ｚ|ａ|ｂ|ｃ|ｄ|ｅ|ｆ|ｇ|ｈ|ｉ|ｊ|ｋ|ｌ|ｍ|ｎ|ｏ|ｐ|ｑ|ｒ|ｓ|ｔ|ｕ|ｖ|ｗ|ｘ|ｙ|ｚ|yao|yi|er|san|si|wu|liu|qi|ba|jiu|shi|one|two|three|four|five|six|seven|eight|nine|ten/','*',$str);
    $newStr = preg_replace('/[a-zA-Z]\w+([-_]\w+)*@(\w{2,}\.)+[a-zA-Z]{2,}/','***',$newStr);
    return $newStr;
}

/**
@param MooGetScreen 根据两会员ID查看有没有被对方屏蔽
@param uid 当前会员ID
@param mid 对方ID
@param retrun 0为双方都没屏蔽对方 ｜ 1为其中一方屏蔽了另一方
*/
function MooGetScreen($uid,$mid){
    global $_MooClass,$dbTablePre;
    if($_MooClass['MooMySQL']->getOne("select screenid from {$dbTablePre}screen where (uid='$uid' and mid='$mid') or (uid='$mid' and mid='$uid')")){
        return 1;
    }else{
        return 0;
    }
}

/**
@param MooSendMail 发送邮件函数
@param ToAddress 收件人的地址
@param ToSubject 发送邮件的主题
@param ToBody 发送的邮件的正文
@param uid 会员ID
@param return bollean
* gmail发送邮件
*/
function MooSendMail($ToAddress,$ToSubject,$ToBody,$type='',$is_template = true,$uid=0){
    global $mailTemplateFile;
    
    //note ***********加载模板************
    if ($is_template == true) {
        if($type==''){
            $body = MooReadFileadmin($mailTemplateFile);
        }else{
            $body = MooReadFileadmin($type, false);
        }
        if($body==''){
              $body = MooReadFileadmin($mailTemplateFile);
        }
        $body = str_replace('\\','',$body);
        //邮件时间替换
        //date_default_timezone_set ('Asia/Shanghai');
        $Time = date('Y-m-d H:i:s');
        $body = str_replace("#DATETIME#",$Time,$body);
        //note 邮件正文替换
        $body = str_replace("#BODY#",$ToBody,$body);
        //note 模板几个内部图片地址
        $body = str_replace("#siteurl#",'http://'.$_SERVER['HTTP_HOST'].'/',$body);
    } else {
        $body = $ToBody;
    }

    
    $ToAddress=explode(',',$ToAddress);
    foreach($ToAddress as $email){
        $param = array();
        $param["registration_date"] = date("Y-m-d H:i:s");
        $param["uid"] = $uid;
        $param["mail"] = $email;
        $param["subject"] = addslashes($ToSubject);
        $param["content"] = addslashes($body);
        
        inserttable("mail_queue", $param);
    }
    
    return true;
}

function sendMailByNow($ToAddress,$ToSubject,$ToBody,$type='',$is_template = true) {
    global $_MooClass,$dbTablePre;
    global $mailHost;
    global $mailUser;
    global $mailPasswd;
    global $mailSenderMail;
    global $mailSenderName;
    global $mailTemplateFile;
    global $g_mail_list;
    

    // 查询是否来自采集的 
    $filter = array();
    $filter[] = array('username', $ToAddress);
    $filter[] = array('usertype', '3');
    
    $limit = array(0, 1);
     
    $members_id = searchApi('members_man members_women')->getResultOfReset(
		$filter,
		$limit
    	);
    	  	
    if(!empty($members_id)) return true;

    
	    /**
    	$sql = "select usertype from " . $dbTablePre . "members_search where uid = '" . $members_id[0] . "' limit 1";
	    $member = $_MooClass['MooMySQL']->getOne($sql);
	    
	    if (count($member) > 0) {
	        if ($member["usertype"] == 3) {
	            return true;
	        }
	    } else {
	        return true;
	    }
	    **/
    //}

    $key = array_rand($g_mail_list, 1);
    $mailSenderMail = $g_mail_list[$key];
    
    if ($is_template == true) {
        if($type==''){
            $body = MooReadFileadmin($mailTemplateFile);
        }else{
            $body = MooReadFileadmin($type, false);
        }
        $body = str_replace('\\','',$body);
        //邮件时间替换
        //date_default_timezone_set ('Asia/Shanghai');
        $Time = date('Y-m-d H:i:s');
        $body = str_replace("#DATETIME#",$Time,$body);
        //note 邮件正文替换
        $body = str_replace("#BODY#",$ToBody,$body);
        //note 模板几个内部图片地址
        $body = str_replace("#siteurl#",'http://'.$_SERVER['HTTP_HOST'].'/',$body);
    } else {
        $body = $ToBody;
    }
    try {
        ob_start();
        require("framwork/libraries/class.phpmailer.php");
        $mail = new PHPMailer(); //建立邮件发送类
        $mail->IsSMTP(); // 使用SMTP方式发送
        $mail->Host = $mailHost; // 您的企业邮局域名
        $mail->Port = 25;  
        $mail->SMTPAuth = true; // 启用SMTP验证功能
        //$mail->SMTPSecure = "ssl";
        $mail->Username = $mailSenderMail; // 邮局用户名(请填写完整的email地址)
        $mail->Password = $mailPasswd; // 邮局密码
        $mail->From = $mailSenderMail; //邮件发送者email地址
        $mail->CharSet = "utf-8"; 
        $mail->Encoding = "base64"; 
        $mail->FromName = $ToSubject;
        
        //$mail->AddReplyTo("", "");
        //$mail->AddAttachment("/var/tmp/file.tar.gz"); // 添加附件
        $mail->IsHTML(true); // set email format to HTML //是否使用HTML格式
        $mail->AltBody = ""; //附加信息，可以省略
        
        // 自动换行
        $mail->WordWrap   = 70;
        // 这里指定字符集！如果是utf-8则将gb2312修改为utf-8
        $mail->CharSet = 'utf-8';
        $mail->Subject = $ToSubject;
        $mail->Body = $body;
        $mail->AddAddress($ToAddress, "");
        if($mail->Send()){
            return true;
        } else {
            return MooSendMail($ToAddress,$ToSubject,$ToBody,$type);
        }
    } catch (Exception  $e) {
        return MooSendMail($ToAddress,$ToSubject,$ToBody,$type);
    }
}

/**
 * 
 */
function sendMailQueue () {
    global $_MooClass,$dbTablePre;
    global $mailHost;
    global $mailUser;
    global $mailPasswd;
    global $mailSenderMail;
    global $mailSenderName;
    global $mailTemplateFile;
    global $_MooClass,$dbTablePre;
    global $g_mail_list;
    
    require("framwork/libraries/class.phpmailer.php");

    // 邮件队列
    $sql = "select * from " . $dbTablePre . "mail_queue where flg = 0 and count < 3 order by id desc limit 30";
    $mail_queue_list = $_MooClass['MooMySQL']->getAll($sql);

    if(empty($mail_queue_list)) return;
    
    $username_arr = $username_crc = array();
    foreach($mail_queue_list as $key=>$mail_queue){
    	$username_arr[$mail_queue['id']] = $mail_queue['mail'];
    	$username_crc[sprintf("%u", crc32($mail_queue['mail']))] = $mail_queue['id'];
    	$mail_queue_list[$mail_queue['id']] = $mail_queue;
    	unset($mail_queue_list[$key]);
    }
    
    if(empty($username_arr)) return;
    
    $filter = array();
    $filter[] = array('usertype', '3');
    $filter[] = array('username', implode(' | ', $username_arr));
    // 查询是否来自采集的
	$sp = searchApi('members_man members_women');
    $sp->getResultOfReset($filter);

    if($rs_matchs = $sp->getRs('matches')){
    	$queue_ids = array(); 
    	foreach($rs_matchs as $attr){
    		if(isset($attr['attrs']['username'])){
    			$queue_id = $username_crc[$attr['attrs']['username']];
    			$queue_ids[] = $queue_id;
    			unset($mail_queue_list[$queue_id]);
    		}
    	}	
    	if(!empty($queue_ids)){
    		$sql = "update " . $dbTablePre . "mail_queue set flg = 2 where id in (".implode(',',$queue_ids).")";
    		$_MooClass['MooMySQL']->query($sql);
    	}   
    }
    
    // 发送邮件
    foreach ($mail_queue_list as $mail_queue) {
        
    	  	
    	//if(!empty($members_id)) continue;
        /*$sql = "select * from " . $dbTablePre . "members where username = '" . $mail_queue["mail"] . "' limit 1";
        $member = $_MooClass['MooMySQL']->getOne($sql);
        if (count($member) > 0) {
            if ($member["usertype"] == 3) {
                $sql = "update " . $dbTablePre . "mail_queue set flg = 2 where id = '" . $mail_queue["id"] . "'";
                $_MooClass['MooMySQL']->query($sql);
                continue;
            }
        } else {
            continue;
        }
        */
        
        //unset($mail);
        
        $key = array_rand($g_mail_list);
        $mailSenderMail = $g_mail_list[$key];
        echo '<br />'.$mailSenderMail . "-----" . $mailPasswd . "----<br />{$mail_queue["mail"]}<br />";
        echo $mailHost . "<br />";
		
		
        $mail = new PHPMailer(); //建立邮件发送类
        $mail->IsSMTP(); // 使用SMTP方式发送
        $mail->Host = $mailHost; // 您的企业邮局域名
        $mail->Port = 25;  
		$mail->SMTPKeepAlive = true;
        $mail->SMTPAuth = true; // 启用SMTP验证功能
        // $mail->SMTPSecure = "ssl";
        $mail->Username = $mailSenderMail; // 邮局用户名(请填写完整的email地址)
        $mail->Password = $mailPasswd; // 邮局密码
        $mail->From = $mailSenderMail; //邮件发送者email地址
        $mail->CharSet = "utf-8"; 
        $mail->Encoding = "base64"; 
        $mail->FromName = $mail_queue["subject"];
        //$mail->AddReplyTo("", "");
        //$mail->AddAttachment("/var/tmp/file.tar.gz"); // 添加附件
        $mail->IsHTML(true); // set email format to HTML //是否使用HTML格式
        $mail->AltBody = ""; //附加信息，可以省略
    
        // 自动换行
        $mail->WordWrap   = 70;
        // 这里指定字符集！如果是utf-8则将gb2312修改为utf-8
        $mail->CharSet = 'utf-8';
        $mail->Subject = $mail_queue["subject"];
        $mail->Body = $mail_queue["content"];
        $mail->AddAddress($mail_queue["mail"], "");
        if($mail->Send()){
            echo "Message has been sent";
            $sql = "update " . $dbTablePre . "mail_queue set flg = 1 where id = '" . $mail_queue["id"] . "'";
            $_MooClass['MooMySQL']->query($sql);
        } else {
            echo "Mailer Error: " . $mail->ErrorInfo;
            $sql = "update " . $dbTablePre . "mail_queue set count = count + 1 where id = '" . $mail_queue["id"] . "'";
            $_MooClass['MooMySQL']->query($sql);
        }
    }
}


//邮件发送鲜花，选为意中人时发送邮件函数及模板
function MooSendMailSpace2($ToAddress,$ToSubject,$ToBody,$type="1",$body){
    global $mailHost;
    global $mailUser;
    global $mailPasswd;
    global $mailSenderMail;
    global $mailSenderName;
    global $mailTemplateFile;
    if($type != ''){require("framwork/libraries/class.phpmailer.php");}else{require("../framwork/libraries/class.phpmailer.php");}//下载的文件必须放在该文件所在目录
    //建立邮件发送类
    $mail = new PHPMailer();
    //note 使用SMTP方式发送
    $mail->IsSMTP();
    // 您的企业邮局域名
    $mail->Host = $mailHost;
    $mail->Port = 25;  
    //$mail->SMTPAuth = true; // 启用SMTP验证功能
    //$mail->SMTPSecure = "ssl";
    // 是否使支持 HTML 邮件的发送，默认为 false ，为了方便后面使用“邮件模版”，我们把它改为 true
    $mail->IsHTML(true);
    // 邮局用户名(请填写完整的email地址)
    $mail->Username = $mailUser;
    // 邮局密码
    $mail->Password = $mailPasswd;
    //邮件发送者email地址
    $mail->From = $mailSenderMail; 
    $mail->FromName = $mailSenderName;
    $mail->CharSet = "utf-8"; 
    $mail->Encoding = "base64"; 
    //收件人地址，可以替换成任何想要接收邮件的email信箱,格式是AddAddress("收件人email","收件人姓名")
    
    //邮件标题
    $mail->Subject = $ToSubject;
    //邮件内容
    //$mail->Body = $ToBody;
    //note ***********加载模板************
    if($type==''){
//      $body = $mail->getFile($mailTemplateFile);
    }else{
//      $body = $mail->getFile('./public/mailtamp/mail_space_rosetpl.html');
    }
//  $body = eregi_replace("[\]",'',$body);
    //邮件时间替换
    //date_default_timezone_set ('Asia/Shanghai');
//  $Time = date('Y-m-d H:i:s');
//  $body = str_replace("#DATETIME#",$Time,$body);
    //note 邮件正文替换
//  $body = str_replace("#BODY#",$ToBody,$body);
    //note 模板几个内部图片地址
//  $body = str_replace("#siteurl#",'http://'.$_SERVER['HTTP_HOST'].'/',$body);
    // 自动换行
    $mail->WordWrap   = 70; 
    //note ***********HTML正文**************
    $mail->MsgHTML($body);
    // 这里指定字符集！如果是utf-8则将gb2312修改为utf-8
    $mail->CharSet = 'utf-8';
    
    $ToAddress=explode(',',$ToAddress);
    
    foreach($ToAddress as $email){
      $mail->AddAddress($email, "");
    
    }
    
    if(!$mail->Send())
    {
        return false;
    }else{
        return true;
    }
}

/**
* 采用RC4为核心算法，通过加密或者解密用户信息
* @param $string - 加密或解密的串
* @param $operation - DECODE 解密；ENCODE 加密
* @param $key - 密钥 默认为MOOPHP_AUTHKEY常量
* @return 返回字符串
*/
function MooAuthCode($string, $operation = 'DECODE', $key = '', $expiry = 0) {

    /**
    * $ckey_length 随机密钥长度 取值 0-32;
    * 加入随机密钥，可以令密文无任何规律，即便是原文和密钥完全相同，加密结果也会每次不同，增大破解难度。
    * 取值越大，密文变动规律越大，密文变化 = 16 的 $ckey_length 次方
    * 当此值为 0 时，则不产生随机密钥
    */
    $ckey_length = 0;
    $key = md5($key ? $key : md5(MOOPHP_AUTHKEY.$_SERVER['HTTP_USER_AGENT']));
    $keya = md5(substr($key, 0, 16));
    $keyb = md5(substr($key, 16, 16));
    $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

    $cryptkey = $keya.md5($keya.$keyc);
    $key_length = strlen($cryptkey);

    $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
    $string_length = strlen($string);

    $result = '';
    $box = range(0, 255);

    $rndkey = array();
    for($i = 0; $i <= 255; $i++) {
        $rndkey[$i] = ord($cryptkey[$i % $key_length]);
    }

    for($j = $i = 0; $i < 256; $i++) {
        $j = ($j + $box[$i] + $rndkey[$i]) % 256;
        $tmp = $box[$i];
        $box[$i] = $box[$j];
        $box[$j] = $tmp;
    }

    for($a = $j = $i = 0; $i < $string_length; $i++) {
        $a = ($a + 1) % 256;
        $j = ($j + $box[$a]) % 256;
        $tmp = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;
        $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
    }
    if($operation == 'DECODE') {
        if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
            return substr($result, 26);
        } else {
            return '';
        }
    } else {
        return $keyc.str_replace('=', '', base64_encode($result));
    }

}

/**
* 自动加载默认类文件函数，并将其初始化
* @param string $classname - 类名
* @param string $type - libraries 默认类文件夹路径；plugins 插件文件路径
* @return class
*/

function MooAutoLoad($classname, $type = 'libraries') {
    global $_MooClass;

    $type = in_array($type, array('libraries', 'plugins')) ? $type : 'plugins';

    if(empty($_MooClass[$classname])) {
        require_once MOOPHP_ROOT.'./'.$type.'/'.$classname.'.class.php';
        $_MooClass[$classname]=  new $classname;
        return $_MooClass[$classname];
    } else {
        return $_MooClass[$classname];
    }

}


/**
* 自动加载插件文件，并将其初始化
* @param string $name - 插件名
* @return none
*/
function MooPlugins($name) {
    include_once MOOPHP_ROOT.'./plugins/'.$name.'.php';
}

/**
* 为变量或者数组添加转义
* @param string $value - 字符串或者数组变量
* @return array
*/
function MooAddslashes($value) {
    return $value = is_array($value) ? array_map('MooAddslashes', $value) : addslashes($value);
}

/**
* 模块函数
* @param string $type - 类型
* @param string $name - 结果集的数组名称
* @param string $param - 参数
* @return array
*/
function MooBlock($param) {
    global $_MooClass;

    $_MooClass['MooCache']->getBlock($param);

}

/**
* 根据中文裁减字符串
* @param string $string - 待截取的字符串
* @param integer $length - 截取字符串的长度
* @param string $dot - 缩略后缀
* @return string 返回带省略号被裁减好的字符串
*/
function MooCutstr($string, $length, $dot = ' ...') {
    global $charset;

    if(strlen($string) <= $length) {
        return $string;
    }
    $string = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;','。'), array('&', '"', '<', '>',''), $string);
    $strcut = '';
    if(strtolower($charset) == 'utf-8') {
        $n = $tn = $noc = 0;
        while($n < strlen($string)) {
            $t = ord($string[$n]);
            if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
                $tn = 1; $n++; $noc++;
            } elseif (194 <= $t && $t <= 223) {
                $tn = 2; $n += 2; $noc += 2;
            } elseif (224 <= $t && $t < 239) {
                $tn = 3; $n += 3; $noc += 2;
            } elseif (240 <= $t && $t <= 247) {
                $tn = 4; $n += 4; $noc += 2;
            } elseif (248 <= $t && $t <= 251) {
                $tn = 5; $n += 5; $noc += 2;
            } elseif ($t == 252 || $t == 253) {
                $tn = 6; $n += 6; $noc += 2;
            } else {
                $n++;
            }
            if($noc >= $length) {
                break;
            }
        }
        if($noc > $length) {
            $n -= $tn;
        }
        $strcut = substr($string, 0, $n);
    } else {
        for($i = 0; $i < $length; $i++) {
            $strcut .= ord($string[$i]) > 127 ? $string[$i].$string[++$i] : $string[$i];
        }
    }
    $strcut = str_replace(array('&', '"', '<', '>','。'), array('&amp;', '&quot;', '&lt;', '&gt;',''), $strcut);

    return $strcut.$dot;
}

/**
* 获取缓存文件路径。
* @param string $cacheFile - 缓存文件名
* @return string 返回缓存文件绝对路径
*/
function MooCacheFile($cacheFileName) {
    global $_MooClass;

    $cacheFile = MOOPHP_DATA_DIR.'/cache/cache_'.$cacheFileName.'.php';

    if(!file_exists($cacheFile)) {
        $_MooClass['MooCache']->setCache($cacheFileName);
    }
    return $cacheFile;

}

/*
*去除不安全HTML代码
*@ $str 传入内容
*/

function safeFilter($str,$type=""){
       
       if(!$type){
              $str = preg_replace( "@<(.*?)>@is", "", $str );
          }
        else{
          $str = preg_replace( "@<script(.*?)</script>@is", "", $str );
          $str = preg_replace( "@<iframe(.*?)</iframe>@is", "", $str );
          $str = preg_replace( "@<style(.*?)</style>@is", "", $str );
        }  
          return $str;
}


/**
* 获取GPC变量。对于type为integer的变量强制转化为数字型
* @param string $key - 权限表达式
* @param string $type - integer 数字类型；string 字符串类型；array 数组类型
* @param string $var - R $REQUEST变量；G $GET变量；P $POST变量；C $COOKIE变量
* @return string 返回经过过滤或者初始化的GPC变量
*/
function MooGetGPC($key, $type = 'integer', $var = 'R') {

    switch($var) {
        case 'G': $var = &$_GET; break;
        case 'P': $var = &$_POST; break;
        case 'C': $var = &$_COOKIE; break;
        case 'R': $var = &$_REQUEST; break;
    }
    switch($type) {
        case 'integer':
            $return = isset($var[$key]) ? intval($var[$key]) : 0;
            break;
        case 'string':
            $return = isset($var[$key]) ? $var[$key] : NULL;
            break;
        case 'array':
            $return = isset($var[$key]) ? $var[$key] : array();
            break;
        default:
            $return = isset($var[$key]) ? intval($var[$key]) : 0;
    }

    return $return;
}

/**
* 文本缓存函数
* @param string $filename - 需要写入缓存的文件名称。
* @param string $type - get 读取缓存；make 生成缓存
* @param string $cacheLife - 缓存文件有效期，默认为3600秒
* @param string $cacheDir - 缓存文件路径，默认为MOOPHP_DATA_DIR.'/html/'
* @return array
*/
function MooHtmlCache($fileName, $type = 'get', $cacheLife = 3600, $cacheDir = '') {
    global $timestamp;

    $cacheDir = $cacheDir ? $cacheDir : MOOPHP_DATA_DIR.'/html/';
    $cacheFile = $cacheDir.$fileName.'.tpl';

    if ($type == 'get') {
        ob_start();
        if($timestamp - filemtime($cacheFile) < $cacheLife) {
            readfile($cacheFile);
            exit();
        }
    }

    if ($type == 'make') {

        $cacheContent = ob_get_contents();
        ob_end_clean();
        ob_start();
        MooMakeDir(dirname($cacheFile));
        MooWriteFile($cacheFile, $cacheContent);
        readfile($cacheFile);
        exit();

    }
}

/**
* MooPHP调试输出函数。在模板尾部添加{php MooDebug();}同时设置MOOPHP_DEBUG常量为真
* @return string
*/
function MooDebug() {
    global $timestamp, $_MooPHP, $_COOKIE, $_SERVER;
    include MOOPHP_ROOT.'/libraries/MooMySQLDebug.inc.php';
}

/**
* 将特殊字符转成 HTML 格式。比如<a href='test'>Test</a>转换为&lt;a href=&#039;test&#039;&gt;Test&lt;/a&gt;
* @param string $value - 字符串或者数组变量
* @return array
*/
function MooHtmlspecialchars($value) {
    return is_array($value) ? array_map('MooHtmlspecialchars', $value) : preg_replace('/&amp;((#(\d{3,5}|x[a-fA-F0-9]{4})|[a-zA-Z][a-z0-9]{2,5});)/', '&\\1', str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $value));
}


/**
*生成伪配对数
*@return $counts 多少对幸福恋人
**/
function MooLoves(){
    $date=time();
    $a=(int)date('H'); 
    $start_time=mktime(0,0,0,date('m',$date),date('d',$date),date('Y',$date));  
    
    $b=time()-$start_time+1000;
    //echo $b;
    if($a>=7&&$a<24){
    
     
      $counts=(int)($b/80);
    
    }
   return $counts;
}
/**
*生成伪在线状态
*@return $counts 多少人在线
**/
function MooLogin(){
    global $_MooCookie;
    $num=isset($_MooCookie['login_num']) ? $_MooCookie['login_num'] : 0;
    if($num){
        
    }else{      
        $date=time();
        $start_time=mktime(0,0,0,date('m',$date),date('d',$date),date('Y',$date));
        $tow=$start_time+3600*24;
        $long=$tow-$date;
        $a=(int)date('H'); 
        $b=time()-$start_time;
        $c=date('m')*date('d');
        if($a>=0&&$a<7){
          $time=$b+$a*2000+1000;     
        }elseif($a>=7&&$a<9){
            $time=$b+$a*9348; 
        }elseif($a>=9&&$a<=11){
          $time=$b+$a*10348+11; 
        }elseif($a>11&&$a<=13){
         $time=$b+$a*13078+13; 
        }elseif($a<=16&&$a>13){
           $time=$b+$a*24078+15; 
        }elseif($a<=21&&$a>16){
           $time=$b+$a*30078+18; 
        }elseif($a<=23&&$a>21){
           $time=$b+$a*53078+18; 
        }else{
           $time=$b+$a*54078+18; 
        }
        $result= $time;
        MooSetCookie('login_num',$result,600);//$long
        $num=$result;
    }
    return $num;
}


/**
* 设置cookie
* @param $var - 变量名
* @param $value - 变量值
* @param $life - 生命周期期
* @param $prefix - 前缀
*/
function MooSetCookie($var, $value, $life=0, $prefix = 1) {
    global  $_SERVER;
   	setcookie(($prefix ? MOOPHP_COOKIE_PRE : '').$var, $value, $life?time()+$life:0, MOOPHP_COOKIE_PATH, MOOPHP_COOKIE_DOMAIN, $_SERVER['SERVER_PORT'] == 443 ? 1 : 0);
}


function MooClearCookie() {
    global $timestamp, $_SERVER;
    session_start();
    //$sql = "DELETE FROM {$GLOBALS['dbTablePre']}membersession WHERE uid='{$GLOBALS['MooUid']}'";
   // $GLOBALS['_MooClass']['MooMySQL']->query($sql);
    MooSetCookie('auth', '', -86400 * 365);
    $GLOBALS['MooUid'] = 0;
    $GLOBALS['MooUserName'] = '';
    $GLOBALS['USER_MSG'] = $GLOBALS['member'] = array();
    unset($_SESSION['s_userid']);
}


 
/**
* 检查是否正确提交了表单 //debug 此函数还处于调试阶段
* @param string $var 需要检查的变量
* @param string $allowget 是否允许GET方式
* @param string $seccodecheck 验证码检测是否开启
* @return 返回是否正确提交了表单
*/
function MooSubmit($var, $allowget = 0, $seccodecheck = 0) {

    if(empty($GLOBALS['_REQUEST'][$var])) {
        return FALSE;
    } else {
        global $_SERVER;
        if($allowget || ($_SERVER['REQUEST_METHOD'] == 'POST' && (empty($_SERVER['HTTP_REFERER']) ||
            preg_replace("/https?:\/\/([^\:\/]+).*/i", "\\1", $_SERVER['HTTP_REFERER']) == preg_replace("/([^\:]+).*/", "\\1", $_SERVER['HTTP_HOST'])))) {
            return TRUE;
        } else {
            MooMessage('submit_invalid');//debug 此处还缺少
        }
    }
}

/**
* PHP下递归创建目录的函数，使用示例MooMakeDir('D:\web\web/a/b/c/d/f');
* @param string $dir - 需要创建的目录路径，可以是绝对路径或者相对路径
* @return boolean 返回是否写入成功
*/
function MooMakeDir($dir) {
    return is_dir($dir) or (MooMakeDir(dirname($dir)) and mkdir($dir, 0777)); 
}

/**
* 终止程序执行，显示提示信息
* @param string $message 显示的信息文本
* @param string $urlForward 跳转地址，默认为空
* @param string $time 链接跳转时间，默认为3秒
* @param strint $stype (01是错误信息,02警告信息,03提示信息,04一般信息,05正确信息,为空就是提示信息)
* @return 无返回值
*/
function MooMessage($message, $urlForward = '',$stype='',$time = 3,$js='') {
     global $user_arr;
     global $memcached, $fastdb,$_MooClass;
    if(!$stype)$stype='04';
    
    //$message = $message;
    $title = $message;
    //$urlForward = $urlForward;
    
    
    $time = $time * 1000;

    if($urlForward && empty($js)) {
        $message .= "<p align='center'><input type=\"button\" class=\"btn btn-default\" onclick=\"javascript:location.href='".$urlForward."'\" value=\"确 定\" /></p>";
    }elseif(!empty($js)){
        $message .= "<p align='center'><input type=\"button\" class=\"btn btn-default\" onclick=\"history.back()\" value=\"确 定\" /></p>";
    }

    /* if($time) {
        $message .= "<script>".
            //"function redirect() {window.location.replace('$urlForward');}\n".
            "function redirect() {window.location.href='{$urlForward}';}\n".
            "setTimeout('redirect();', $time);\n".
            "</script>";
    } */

    require MooTemplate('system/msg','public');
    $_MooClass['MooMySQL']->close();
    $memcached->close();
    $fastdb->close();
    exit;
}

/**
* 加载模板
* @param string $path - 模板文件路径 (包含皮肤目录和文件名) 如: default/na 
* @param string $type - 模板类型 {module:module里模块模板;public:public下的公共模板;data:data下的用户定制模板}
* @return string 返回编译后模板的系统绝对路径
*/
function MooTemplate( $path, $type ){
    switch ($type){
        case 'module':
            $tpl_path = MOOPHP_TEMPLATE_DIR.'/'.$path.'.htm';//aaa.htm
            break;
        case 'public':
            $tpl_path = 'public/'.$path.'.htm';
            break;
        case 'data':
            $path = substr($GLOBALS['style_uid'],-1).'/'.$GLOBALS['style_uid'].'/'.$GLOBALS['style_uid'].'_'.$path;
            $tpl_path = 'data/diamond/'.$path.'.htm';
           
            if( !file_exists($tpl_path) ){
                MooMessage('您访问的页面不存在');
                exit();
            }
            break;
        default:
            exit('$type error');
    }

    $php_path = MOOPHP_DATA_DIR.'/templates/'.$type.'/'.$path.'.tpl.php';
    if(!file_exists($php_path) || filemtime($tpl_path) > filemtime($php_path)) {
        //note 加载模板类文件
        $T = MooAutoLoad('MooTemplate');
        $T->complie($tpl_path, $php_path);
    }
    return $php_path;
}


/**
*来源获取（网站来源访问和推荐用户ID）
*@返回COOKIE值在注册的时候写入
*PHPYOU
*/
function MooGetFromwhere(){
    global $_MooCookie;
    $puid=isset($_MooCookie['puid']) ?$_MooCookie['puid'] :MooGetGPC('puid','integer','G');
    if($puid){
        MooSetCookie('puid', $puid, 24*3600);
    }
    $website=isset($_MooCookie['website'])?$_MooCookie['website']:MooGetGPC('website','string','G');
    if($website){
        MooSetCookie('website', $puid, 24*3600);
    }
    if( isset($_GET['wf']) && isset($_GET['st']) ){
        MooSetCookie('where_from', "http://www.7651.com/virtual/?".$_SERVER['QUERY_STRING'], 24*3600);
        return;
    }
    if(isset($_MooCookie['where_from']) && preg_match("/\/virtual\//",$_MooCookie['where_from']) ) return;
    $rf_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
    // 判断是否有外来页
    if (empty($rf_url)) {
        //error_log("rf error", 0);
        return false;
    }
    
    $rf_arr = parse_url($rf_url);
    $rf_hostname = $rf_arr["host"];
    $rf_path = $rf_arr["path"];
    
    //print_r($rf_arr);
     
    $where_from  = isset($_MooCookie['where_from']) ? $_MooCookie['where_from'] : '';
    
    // 判断外来是否是本站
    if ($rf_hostname == $_SERVER["HTTP_HOST"] && strstr($rf_url, "/pop/") === false && strstr($rf_url, "/reg/") === false) {
        //error_log("rf error1", 0);
        if(!empty($where_from)){
	        $where_from_arr = parse_url($where_from);
	        if ($where_from_arr["host"] == $_SERVER["HTTP_HOST"]) {
	            MooSetCookie('where_from', "", -24*3600);
	        }
        }
        
        return false;
    }

    // 判断外来与cookie中记录是否一致
    if ($where_from == $rf_url) {
        return false;
    }

    MooSetCookie('where_from', $_SERVER['HTTP_REFERER'], 24*3600);
    return true;
}



/**
* 获取当前用户信息
* @return 返回全局变量$MooUid, $MooUserName
*/
function MooUserInfo() {
    global $_MooCookie;
//    var_dump($_MooCookie);
//     MooSetCookie('auth','SDFSFGAFGD\AHFGHGHJ',86400);

    if (!session_id()) session_start();
    if(!empty($_SESSION['s_userid'])){
    	$GLOBALS['MooUid'] = $_SESSION['s_userid'];
    }else if(MOOPHP_ALLOW_MYSQL && isset($_MooCookie['auth'])) {  	
    	 $arr = explode("\t", MooAuthCode($_MooCookie['auth'], 'DECODE'));
    	 if (isset($arr[1])){
    	 	list($uid, $password) = $arr;    	 	
    	 	if($uid && $password){
	    	 	$member = MooMembersData($uid);
	            if($member && $member['password']==$password){
	            	$member += MooGetData('members_login', 'uid', $uid);
	                $GLOBALS['USER_MSG'] = $member;
	                $GLOBALS['MooUserName'] = addslashes($member['username']);
	                //记录在线状态 更新访问
	                MooUserOnline($uid);
	                
	                $GLOBALS['MooUid'] = intval($uid);
	                $_SESSION['s_userid'] = $uid;
	            }	
    	 	}        
        } 
    }
}

/**
 * 获得用户信息
 * @return array
 *
 */
function UserInfo() {
    global $GLOBALS,$_MooClass,$dbTablePre;
    $user = array();
    if(isset($GLOBALS['USER_MSG']) && $GLOBALS['USER_MSG']){
        $user = $GLOBALS['USER_MSG'];
    }elseif(isset($GLOBALS['MooUid']) && $GLOBALS['MooUid']){  	
            $uid = $GLOBALS['MooUid'];
            $user = MooMembersData($uid);
		
            $user += MooGetData('members_login', 'uid', $uid);
			$GLOBALS['USER_MSG'] = $user;
            $GLOBALS['MooUserName'] = addslashes($user['username']);
    }
    return $user;   
        
}


/**
* 获取当前用户信息
* @param array $setarr 传入的数组$setarr = array('uid' => $GLOBALS['MooUid'], 'username' => $GLOBALS['MooUserName'], 'password' => $password);
* @return 无返回值
*/
function MooUserSession($setarr) {
    $GLOBALS['_MooClass']['MooMySQL']->query("REPLACE INTO {$GLOBALS['dbTablePre']}membersession SET uid='{$setarr['uid']}', username='{$setarr['username']}', password='{$setarr['password']}', lastactive='{$GLOBALS['timestamp']}'");
}

/**
* 读文件
* @param string $file - 需要读取的文件，系统的绝对路径加文件名
* @param boolean $exit - 不能读入是否中断程序，默认为中断
* @return boolean 返回文件的具体数据
*/
function MooReadFile($file, $exit = TRUE) {
    if(!$fp = fopen($file, 'rb')) {
        if($exit) {
            exit('MooPHP File :<br>'.$file.'<br>Have no access to write!');
        } else {
            return false;
        }
    } else {
        $data = fread($fp,filesize($cachefile));
        fclose($fp);
        return $data;
    }
}

/**
* 读文件
* @param string $file - 需要读取的文件，系统的绝对路径加文件名
* @param boolean $exit - 不能读入是否中断程序，默认为中断
* @return boolean 返回文件的具体数据
*/
function MooReadFileadmin($file, $exit = TRUE) {
    if(!$fp = fopen($file, 'r')) {
        if($exit) {
            exit('MooPHP File :<br>'.$file.'<br>Have no access to write!');
        } else {
            return false;
        }
    } else {
        $data = fread($fp,filesize($file));
        fclose($fp);
        return $data;
    }
}

/**
* 写文件
* @param string $file - 需要写入的文件，系统的绝对路径加文件名
* @param string $content - 需要写入的内容
* @param string $mod - 写入模式，默认为w
* @param boolean $exit - 不能写入是否中断程序，默认为中断
* @return boolean 返回是否写入成功
*/
function MooWriteFile($file, $content, $mod = 'w', $exit = TRUE) {
    global $memcached;
    
	if(!$fp = fopen($file, $mod)) {
        if($exit) {
            exit('MooPHP File :<br>'.$file.'<br>Have no access to write!');
        } else {
            return false;
        }
    }else{
    	$key = 'moo_wirte_file_'.md5($file);
    	if ($memcached->get($key)){
    		$i =0;
	    	while($i<60 && $memcached->get($key)){
	    		usleep(100);
	    		$i++;
	    	}
    	}else{
    		$memcached->set($key, true,0,5);
    		flock($fp, 2);
	        fwrite($fp, $content);
	        fclose($fp);
	        $memcached->delete($key);
    	}
        return true;
    }
}

/**
* 输出缩略图路径
* @param $size - 传递缩略图大小 1，2，3...
* @param $path_date - 路径日期
* @param $pic_name - 图片名称
* @return 返回缩略图路径
*/
function thumbImgPath ($size,$path_date,$pic_name) {
    $pic_name_arr = explode(".",$pic_name);
    $path = '';
    if(isset($pic_name_arr[1])){
	    $name1 = md5($pic_name_arr[0]);
	    $pic_name = $name1.".".$pic_name_arr[1];
	    if($size == '1') {
	        $path = IMG_SITE.PIC_PATH."/".$path_date."/".PIC_SIZE_1."/".$pic_name;
	    }
	    else if($size == '2') {
	        $path = IMG_SITE.PIC_PATH."/".$path_date."/".PIC_SIZE_2."/".$pic_name;
	    }
	    else if($size == '3') {
	        $path = IMG_SITE.PIC_PATH."/".$path_date."/".PIC_SIZE_3."/".$pic_name;
	    }
    }
    return $path;
}

/**
 * 你可能喜欢的人
 * 目前只是匹配相同地区的人，功能有待完善
 * @param $num 一次显示多少个图片,默认显示8张
 * @return array 你可能喜欢的人信息存储数组
 *
 */
//mark 己改  by chuzx
function youAbleLike(&$l,$num = 0) {
    global $_MooClass,$dbTablePre,$user_arr,$memcached;
    //note 你喜欢的人，以同地区条件查询,如果城市是0,就查询省，如果省为0就查询国家
    //note 在后面注册功能完善后，有了国家，就要判断国家
    //$user1 = UserInfo();
    
    $user1 = $user_arr;
    $gender = $user1['gender'] == '1' ? '0' : '1';
    if(18<=(date("Y")-$user1['birthyear'])&&(date("Y")-$user1['birthyear'])<=30&&$user1['birthyear']!=''&&$user1['birthyear']!=0){$age=1;}
    if(31<=(date("Y")-$user1['birthyear'])&&(date("Y")-$user1['birthyear'])<=35&&$user1['birthyear']!=''&&$user1['birthyear']!=0){$age=2;}
    if(36<=(date("Y")-$user1['birthyear'])&&(date("Y")-$user1['birthyear'])<=40&&$user1['birthyear']!=''&&$user1['birthyear']!=0){$age=3;}
    if(41<=(date("Y")-$user1['birthyear'])&&(date("Y")-$user1['birthyear'])<=50&&$user1['birthyear']!=''&&$user1['birthyear']!=0){$age=4;}
    if(51<=(date("Y")-$user1['birthyear'])&&(date("Y")-$user1['birthyear'])<=60&&$user1['birthyear']!=''&&$user1['birthyear']!=0){$age=5;}
    if(61<=(date("Y")-$user1['birthyear'])&&$user1['birthyear']!=''&&$user1['birthyear']!=0){$age=6;}
    if($user1['birthyear']==''||$user1['birthyear']==0){$age=1;}
    if($user1["province"]==''||$user1["province"]==0||$user1["province"]==-1){
        $user1["province"]=10103000;
    }
    $file = $age . "_" . $gender . "_" . $user1["province"] . "_" . $user1["city"];
//     $cache_file = MOOPHP_DATA_DIR.'/cache/cache_' . $file . '.php';
    $data  = $memcached->get($file);
    if (!$data) {
        updateVoteFeel("","",$user1['province'],$user1['city'],$age,$gender);
        $data = $memcached->get($file);
    }
    if(empty($data)) return $data;
	
	$l=count($data);
	//shuffle($data);
        $data=array_slice($data, $num , 5);
        for($j=0;$j<5;$j++){
            //if(MOOPHP_ALLOW_FASTDB){ 
              if(isset($data[$j])) $able_like[$j] = MooMembersData($data[$j]);//MooFastdbGet('members_search','uid',$data[$j]);
            //}
        }
    //echo $cache_file.'<br>';
    //print_r($able_like);
    return $able_like;
}
/**
 * 产生匿名会员
 * $province 会员工作省份
 * $city     会员工作城市
 * $province 会员年龄
 * $gender   会员性别
 */
function selectuser($province,$city,$birthyear,$gender) {
    global $_MooClass,$dbTablePre;
    //note 你喜欢的人，以同地区条件查询,如果城市是-1,就查询省，如果省为-1就查询国家
    //note 在后面注册功能完善后，有了国家，就要判断国家
    $gender = $gender == '0' ? '1' : '0';
    $index = $gender ? 'members_women' : 'members_man';
    //$between = 'between '.($birthyear-3).' and '.($birthyear+3);
	$num=rand(1,5);
	$area_filter = array();
	
	//note 省下面的城市存在就查询以城市优先    
	if($city != '0') {
        $area_filter =	array('city', $city);
	//note 如果是直接市的查询
	}elseif($city == '0' && $province != '0') {
    	$area_filter = array('province', $province);
	}
	
	$query = '';
    $limit = array("offset"=>0,"limit"=>$num);
    $sort = '@random';
    $filters = array(
        		array('is_lock', '1'),
        		array('birthyear', array(($birthyear-3), ($birthyear+3)))
        	);
    if(!empty($area_filter)) $filters[] = $area_filter;
    
    $sp = searchApi($index); 
    $sp->getResultOfReset($filters, $limit, $sort);
   	$able_like = $sp->getIds();
    
    return $able_like;
}
    
    /**
    //note 要在首页随机显示8位推荐同地区的人，需要足够的测试数据/*
    $total = count($able_like);
    $num=rand(1,5);
    if($total > 0) {
        $page = ceil($total/$num);
        $rand_page = rand(0,$page-1);
        $start = $rand_page * $num;
        $limit_sql = " LIMIT $start,$num";
        
        //note 如果是直接市的查询
        if($city == '-1' && $province != '-1') {
            if($gender){
            $able_like = $_MooClass['MooMySQL']->getAll("SELECT `uid` FROM {$dbTablePre}membersfastadvance_women WHERE province = '$province'  and is_lock = 1 AND birthyear $between $limit_sql");
            }else{
                $able_like = $_MooClass['MooMySQL']->getAll("SELECT `uid` FROM {$dbTablePre}membersfastadvance_man WHERE province = '$province' AND  is_lock = 1 AND birthyear $between $limit_sql");
            }
            //$able_like = $_MooClass['MooMySQL']->getAll("SELECT `uid` FROM {$dbTablePre}members WHERE province = '$province' AND gender = '$gender' and is_vote='1' and is_lock = 1 AND birthyear $between $limit_sql");
        }
        //note 省下面的城市存在就查询以城市优先
        if($city != '-1') {
            if($gender){
            $able_like = $_MooClass['MooMySQL']->getAll("SELECT `uid` FROM {$dbTablePre}membersfastadvance_women WHERE province = '$province'  and is_lock = 1 AND birthyear $between $limit_sql");
            }else{
                $able_like = $_MooClass['MooMySQL']->getAll("SELECT `uid` FROM {$dbTablePre}membersfastadvance_man WHERE city = '$city' AND  is_lock = 1 AND birthyear $between $limit_sql");
            }
            //$able_like = $_MooClass['MooMySQL']->getAll("SELECT `uid` FROM {$dbTablePre}members WHERE city = '$city' AND gender = '$gender' and is_vote='1' and is_lock = 1 AND birthyear $between $limit_sql");
        }
    }
    return $able_like;
}
*/
/**
 * 获得对方他或者她
 *@return string 返回他或者她
 */
function ta() {
    global $user_arr;
    $user = $user_arr;
    $gender = $user['gender'] == '0' ? "他" : "她";
    return $gender;
}
/*
*返回会员等级
*@return 0 or return 1

*/
function get_userrank($uid){
   global $_MooClass,$dbTablePre;
   $date=time();
   $sql="select count(*) as c from {$dbTablePre}members_search where uid='$uid' and s_cid in (10,20,30) and endtime >='$date'"; 
   $b=$_MooClass['MooMySQL']->getOne($sql,true);
   return $b['c'];
}


/**
 * 资料相似的会员
 * 查询条件是相同地区，性别，学历，年龄在相差上下两岁
 * @param $suid integer 传递过来用户id
 * @param $num integer 传递过来查询显示多少个会员，默认显示5个
 * @return array 返回一组资料相似的会员
 */
function similarUser($suid,$num='5') {
    global $_MooClass,$dbTablePre;
    $users = array();
    /*
    if(MOOPHP_ALLOW_FASTDB){
        $userC = MooMembersData($suid);//MooFastdbGet('members','uid',$suid);
        if($userC['is_lock'] == 1){
            $user = $userC;
        }
    }else{
    	$user = MooMembersData($suid);
        if($user)
    	//$user = $_MooClass['MooMySQL']->getOne("SELECT * FROM {$dbTablePre}members WHERE uid = '$suid' and is_lock = 1");
    }*/
    $user = MooMembersData($suid);
    if($user['is_lock'] !=  1) return array();
    
    $index = $user['gender'] ? 'members_women' : 'members_man';
    $filters = array();
    
    //$sql = "SELECT * FROM {$dbTablePre}members where ";
    //note 性别
    //$gender = $user['gender'];
    //$sql .= "gender = '$gender'";
    //note 所在地
    $city = $user['city'];
    $province = $user['province'];
    if($city == 0 && $province != 0){
    	$filters[] = array('province', $province);
        //$sql .= " and province = '$province'";
    }elseif($city != 0 && $province != 0){
    	$filters[] = array('province', $province);
    	$filters[] = array('city', $city);
        //$sql .= " and province = '$province' and city = '$city'";
    }
    //note 学历
    $education  = $user['education'];
    if($education != 0){
        if($education != 3 || $education != 7){
        	$filters[] = array('education', ($education-1).' | '.($education+1));
            //$sql .= " and education in ('".($education-1)."','".($education+1)."')";
        }else{
        	$filters[] = array('education', $education);
            //$sql .= " and education = '$education'";
        }
    }
    //note 年龄的匹配
    $birthyear = $user['birthyear'];
    if($birthyear){
       // $birthyear_up = ;
        //$birthyear_down = $birthyear + 3;
       // $sql .= " and (birthyear between $birthyear_up and $birthyear_down)";
        
        $filters[] = array('birthyear', array($birthyear - 3, $birthyear + 3));
    }
    //note 结婚状态
    if($user['marriage'])
    	$filters[] = array('marriage',  $user['marriage'] );
        //$sql .= " and marriage = '{$user['marriage']}'";
    $filters[] = array('is_lock', '1');
    
    $limit = array("offset"=>0,"limit"=>$num);
    
    $query = '';
    $sort = null;
    $sp = searchApi($index);
    $sp->getResultOfReset($filters, $limit, $sort);
   	
    $users = array();
    if($user_ids = $sp->getIds()){
    	$sql = "SELECT s.uid,s.nickname,s.birthyear,s.images_ischeck,b.mainimg,s.gender,s.s_cid FROM {$dbTablePre}members_search s LEFT JOIN {$dbTablePre}members_base b on s.uid=b.uid  where s.uid in (".implode(',', $user_ids).")";
    //$sql .= " and is_lock = 1 ";
    //$sql .= " limit 0,$num";
    	$users = $_MooClass['MooMySQL']->getAll($sql);
    }
    return $users;
}

/**
 * 活跃时间
 * @param integer 传入最后一次登录时间
 * @param integer 用户类型
 * @return string 返回多长时间活跃
 *
 */
function activetime($lastvisit,$usertype=0) {
    global $timestamp;
    if(($timestamp - $lastvisit)  < 600){
        $str='当前在线';
    }elseif(($timestamp - $lastvisit)< 3600) {
        $str='一小时内登陆过 ';
    }elseif(($timestamp - $lastvisit) > 3600 && ($timestamp - $lastvisit) < (12*3600)){
        $str='3小时内活跃过';
    }elseif(($timestamp - $lastvisit) > 12*3600 && ($timestamp - $lastvisit) < (3600*24)){
        $str='今天活跃过';
    }elseif(($timestamp - $lastvisit) > 24*3600 && ($timestamp - $lastvisit) < (3600*24*30)){
        $str='3天内活跃过';
    }else{
        $str='3天内活跃过';
    }
    return $str;       
}
/**
@用途：提示用户所在模块及当前模块下的位置
@无参数
@return string
*/
function getMenu(){
    $menuArr = array('<a href="?n=service">我的红娘</a>',
                    '<a href="?n=search">红娘寻友</a>',
                    '<a href="?n=material">我的资料</a>',
                    '<a href="?n=myaccount">我的帐户</a>',
                    '<a href="?n=lovestyle">爱情测试</a>',
                    '<a href="?n=story">成功故事</a>',
                    '<a href="?n=about&h=us">关于我们</a>',
                    '<a href="?n=about&h=media">媒体关注</a>',
                    '<a href="?n=about&h=contact">联系我们</a>',
                    '<a href="?n=about&h=links">合作伙伴</a>',
                    '<a href="?n=safetyguide">安全征婚指南</a>',
                    '<a href="?n=payment">会员升级</a>',
                    '<a href="index.php?n=space">个人主页</a>',
                    '<a href="?n=invite">邀请好友</a>',
                    '<a href="#">投诉会员</a>',
                    '<a href="?n=vote">E见钟情</a>',
                    '<a href="?n=about&h=getsave">意见反馈</a>'
                );

    $linkStr = ' &gt;&gt; ';
    $MENU = '';
    $n = MooGetGPC('n','string');
    //判断N参数是否在参数数组中
    if(in_array($n,array('service','search','material','myaccount','lovestyle','story','about','safetyguide','payment','space','profile','invite','vote'))){
        switch($n){
            case 'vote':
                $MENU = $linkStr.$menuArr[15];
                break;
            case 'invite':
                $MENU = $linkStr.$menuArr[13];
                break;
            case 'profile':
                $MENU = $linkStr.$menuArr[14];
                break;
            case 'service':
                $MENU = $linkStr.$menuArr[0];
                $listArr = array('message'=>'<a href="?n=service&h=message">我的邮件</a>',
                                'commission'=>'<a href="?n=service&h=commission">我的委托</a>',
                                'leer'=>'<a href="?n=service&h=leer">我的秋波</a>',
                                'rose'=>'<a href="?n=service&h=rose">我的鲜花</a>',
                                'liker'=>'<a href="?n=service&h=liker">我的意中人</a>',
                                'mindme'=>'<a href="?n=service&h=mindme&t=iadvertwho">我留意过谁</a>',
                                'gettel'=>'<a href="index.php?n=service&h=gettel">送话费活动详情</a>',
                                'gettel2'=>'<a href="index.php?n=service&h=gettel2">送钻戒互动详情</a>',
                                'dating'=>'<a href="index.php?n=service&h=dating">发起约会</a>',
                                'mydating'=>'<a href="index.php?n=service&h=dating">发起约会</a>'
                            );
                //note 下级模块
                $h = MooGetGPC('h', 'string');
                if($h){
                    $MENU.=$linkStr.$listArr[$h];
                }
                break;
            case 'search':
                $MENU = $linkStr.$menuArr[1];
                break;
            case 'material':
                $MENU = $linkStr.$menuArr[2];
                $listArr = array('upinfo'=>'<a href="?n=material&h=upinfo">修改资料</a>',
                                'show'=>'<a href="?n=material&h=show">编辑相册</a>'
                            );
                //note 下级模块
                if(MooGetGPC('h', 'string')){
                    $MENU.= $linkStr.getMenuList($listArr);
                }
                break;
            case 'myaccount':
                if(!in_array($_GET['h'],array('index2','telphone','smsindex','emailindex','videoindex','convinceindex','openpic')) && $_GET['n'] == 'myaccount'){
                    $MENU = $linkStr.$menuArr[3];
                }else{
                    $MENU = $linkStr.'<a href="?n=myaccount&h=index2">诚信认证</a>';
                }
                $listArr = array('accountinfo'=>'<a href="?n=myaccount&h=accountinfo">注册信息</a>',
                                'zoarea'=>'<a href="?n=myaccount&h=zoarea">择偶地区</a>',
                                'screen'=>'<a href="?n=myaccount&h=screen">屏蔽会员</a>',
                                'automatic'=>'<a href="?n=myaccount&h=automatic">自动登陆</a>',
                                /*'index2'=>'<a href="?n=myaccount&h=index2">诚信认证</a>',*/
                                'payment'=>'<a href="?n=payment">升级</a>'
                            );
                //note 下级模块
                if(in_array(MooGetGPC('h', 'string'),array('accountinfo','zoarea','screen','automatic','payment'))){
                    $MENU.= $linkStr.getMenuList($listArr);
                }
                break;
            case 'lovestyle':
                $MENU = $linkStr.$menuArr[4];
                break;
            case 'story':
                $MENU = $linkStr.$menuArr[5];
                break;
            case 'safetyguide':
                $MENU = $linkStr.$menuArr[10];
                break;
            case 'payment':
                $MENU = $linkStr.$menuArr[11];
                break;
            case 'space':
                $MENU = $linkStr.$menuArr[12];
                $listArr = array('viewpro'=>'他/她的资料');
                //note 下级模块
                if(MooGetGPC('h', 'string')){
                    $MENU.= $linkStr.getMenuList($listArr);
                }
                break;
            case 'about':
                $h = MooGetGPC('h', 'string');
                if(in_array($h,array('contact','links','media','us','getsave'))){
                    switch($h){
                        case 'us':
                            $MENU = $linkStr.$menuArr[6];
                            break;
                        case 'media':
                            $MENU = $linkStr.$menuArr[7];
                            break;
                        case 'contact':
                            $MENU = $linkStr.$menuArr[8];
                            break;
                        case 'links':
                            $MENU = $linkStr.$menuArr[9];
                            break;
                        case 'getsave':
                            $MENU = $linkStr.$menuArr[16];
                            break;
                    }
                }
                break;
        }
    }
    return $MENU;
}
/**
@param 用途：提示用户所在模块下的位置
@param menuListArr array
@param return string
*/
function getMenuList($menuListArr){
    $nh = MooGetGPC('h', 'string');
    if(in_array($nh,array('accountinfo','zoarea','screen','automatic','index2','payment','upinfo','message','commission','leer','rose','liker','mindme','show','viewpro','gettel','gettel2'))){
        return $menuListArr[$nh];
    }
}
/**
@param 用途："我的红娘"模块 侧栏导航显示
@param return 无返回值，直接输出样式名
*/
function getStyle($str,$class){
    $h = MooGetGPC('h','string',G);
    //$hArr = array('message','commission','leer','rose','liker','mindme');
    if($str == $h){
        echo $class;
    }
}

/**
@param 给会员加鲜花
@param $uid 会员ID
@param $num 默认加一朵鲜花
@param return null
*/
function MooAddRose($uid,$num = 1){
    global $_MooClass,$dbTablePre;
    if($uid){
        $_MooClass['MooMySQL']->query("update {$dbTablePre}members_base set rosenumber = rosenumber+{$num} where uid = $uid and is_lock = 1");
        if(MOOPHP_ALLOW_FASTDB){
            $user_inf=MooFastdbGet('members_base','uid',$uid);
            $value['rosenumber']=$user_inf['rosenumber']+$num;
            MooFastdbUpdate('members_base','uid',$uid,$value);
        }
    }
}
/**
 * 显示未读消息数
 * @return integer $total 返回统计总数
 */
function header_show_total($userid) {
    global $_MooClass,$dbTablePre,$_MooCookie;
    $TOTAL = 0;
    //note 收到邮件数
    if(isset($_MooCookie['servicesemail'.$GLOBALS['MooUid']])){ 
    	$TOTAL = $_MooCookie['servicesemail'.$GLOBALS['MooUid']];
    }else{
		$query = $_MooClass['MooMySQL']->getOne("select count(1) as c from {$dbTablePre}services where s_status = '0' and s_uid = '$userid' and flag = '1' and s_uid_del='0'");
        $TOTAL  = $query['c'];
        MooSetCookie('servicesemail'.$GLOBALS['MooUid'],$TOTAL,600);
    }
    
    return $TOTAL;
}


/**
 * 生成省城市文件
 * return null
 */
function createCity() {
    // 定义省城市文件的位置
    $file_path = "data/cache/city_config.php";
    
    chmod($file_path, 0755);
    
    require_once("module/system/function.php");
    
    // 获取所有省
    $w_param = array();
    $w_param["parent_id"] = 0;
    $provice_full_list = getList($w_param);
    
    $provice_list = array();
    $city_list = array();
    foreach ($provice_full_list as $provice) {
        $provice_list[$provice["name"]] = $provice["title"];
        
        $w_param = array();
        $w_param["parent_id"] = $provice["id"];
        $city_full_list = getList($w_param);
        
        $tmp_city_list = array();
        foreach ($city_full_list as $city) {
            $tmp_city_list[$city["name"]] = $city["title"];
        }
        
        if (count($tmp_city_list) > 0) {
            $city_list[$provice["name"]] = $tmp_city_list;
        }
    }

    // 读出变量$provice_list中的值
    ob_start();
    var_export($provice_list);
    $provice_str = ob_get_clean();
    
    // 读出变量$city_list中的值
    ob_start();
    var_export($city_list);
    $city_str = ob_get_clean();
    
    $provice_str = '$provice_list = ' . $provice_str;
    //echo $provice_str;
    
    $city_str = '$city_list = ' . $city_str;
    //echo $city_str;
    
    // 产生内容
    $content = "<?php\n" . $provice_str . ";\n" . $city_str . ";\n?>";
    
    // 写入文件
    return MooWriteFile($file_path, $content);
}

/**
 * 检查管理员登录情况
 */
function adminCheck() {
    global $_MooClass,$dbTablePre;
    
    $sql = "select * from " . $dbTablePre . "account where name = '" . $_SESSION["ACCOUNT"]["name"] . "'";
    $account = $_MooClass['MooMySQL']->getOne($sql);
    
    if (empty($account)) {
        return false;
    }
    
    if ($account["password"] != $_SESSION["ACCOUNT"]["password"]) {
        return false;
    }
    
    return $account;
}

/**
 * 根据时间获取IP, UV, PV
 * param $date date
 * param $type string   ip, uv, pv
 * param $site_id int 站点ID
 * param $db_flg 后面完善　主要用于之间的统计查statistics_day表
 */
function getCountByHour($date = null, $type = "ip", $site_id = 1, $db_flg = false) {
    global $_MooClass,$dbTablePre;
    
    if ($date == null) {
        $date = date("Y-m-d H");
    }
    
    //2009-10-29 10
    $date_arr = explode (" ", $date);
    
    switch ($type) {
        case "ip":
            if ($date_arr[1] != 0) {
                // 获取某天到H时的所有IP
                $sql = "select count(*) as count from " . $dbTablePre . "visit where site_id = '" . $site_id . "' and substring(registration_date, 1, 10) = '" . $date_arr[0] . "' and substring(registration_date, 12, 2) <= '" . $date_arr[1] . "' group by location_ip";
                // $visit_all_count = $_MooClass['MooMySQL']->getOne($sql);
                
                $query = $_MooClass['MooMySQL']->query($sql);
                $visit_all_count = $_MooClass['MooMySQL']->numRows($query);
                
                // 获取某天到H-1时的所有IP
                $h = $date_arr[1] - 1;
                if (strlen($h) == 1) {
                    $h = "0" . $h;
                }
                $sql = "select count(*) as count from " . $dbTablePre . "visit where site_id = '" . $site_id . "' and substring(registration_date, 1, 10) = '" . $date_arr[0] . "' and substring(registration_date, 12, 2) <= '" . $h . "' group by location_ip";
                // $visit_pre_count = $_MooClass['MooMySQL']->getOne($sql);
                
                $query = $_MooClass['MooMySQL']->query($sql);
                $visit_pre_count = $_MooClass['MooMySQL']->numRows($query);

                return $visit_all_count - $visit_pre_count;
            } else {
                $sql = "select count(*) as count from " . $dbTablePre . "visit where site_id = '" . $site_id . "' and substring(registration_date, 1, 13) = '" . $date . "' group by location_ip";
                $visit_count = $_MooClass['MooMySQL']->getOne($sql);
                
                return $visit_count; 
            }
            
            break;
        case "uv":
            if ($date_arr[1] != 0) {
                // 获取某天到H时的所有UV
                $sql = "select count(*) as count from " . $dbTablePre . "visit where site_id = '" . $site_id . "' and substring(registration_date, 1, 10) = '" . $date_arr[0] . "' and substring(registration_date, 12, 2) <= '" . $date_arr[1] . "' group by visitor_idcookie";
                $query = $_MooClass['MooMySQL']->query($sql);
                $visit_all_count = $_MooClass['MooMySQL']->numRows($query);
                
                // 获取某天到H-1时的所有UV
                $h = $date_arr[1] - 1;
                if (strlen($h) == 1) {
                    $h = "0" . $h;
                }
                $sql = "select count(*) as count from " . $dbTablePre . "visit where site_id = '" . $site_id . "' and substring(registration_date, 1, 10) = '" . $date_arr[0] . "' and substring(registration_date, 12, 2) <= '" . $h . "' group by visitor_idcookie";
                // $visit_pre_count = $_MooClass['MooMySQL']->getOne($sql);
                $query = $_MooClass['MooMySQL']->query($sql);
                $visit_pre_count = $_MooClass['MooMySQL']->numRows($query);

                return $visit_all_count - $visit_pre_count;
            } else {
                $sql = "select count(*) as count from " . $dbTablePre . "visit where site_id = '" . $site_id . "' and substring(registration_date, 1, 13) = '" . $date . "' group by visitor_idcookie";
                //$visit_count = $_MooClass['MooMySQL']->getOne($sql);
                
                $query = $_MooClass['MooMySQL']->query($sql);
                $visit_count = $_MooClass['MooMySQL']->numRows($query);
                
                return $visit_count; 
            }
            
            break;
            
        default:
            $sql = "select count(*) as count from " . $dbTablePre . "visit where site_id = '" . $site_id . "' and substring(registration_date, 1, 13) = '" . $date . "'";
            $visit_count = $_MooClass['MooMySQL']->getOne($sql);
            
            return $visit_count["count"]; 

            break;
    }
}


/**
 * 更新每日统计,按小时更新IP, UV, PV
 * param $d datetime
 * return void
 */
function updateDayByHour($d) {
    global $_MooClass, $dbTablePre;

    // 时间处理 
    $date_h = substr($d, 0, 13);
    $date = substr($d, 0, 10);
    $h = substr($d, 11, 2) + 0;
    
    // 获取所有要统计的站点
    $sql = "select * from " . $dbTablePre . "site";
    $site_list = $_MooClass['MooMySQL']->getAll($sql);
    
    foreach ($site_list as $site) {
        $ip_count = getCountByHour($date_h, "ip", $site["id"]) + 0;
        $uv_count = getCountByHour($date_h, "uv", $site["id"]) + 0;
        $pv_count = getCountByHour($date_h, "pv", $site["id"]) + 0;
        
        $sql = "select * from " . $dbTablePre . "day where site_id ='" . $site["id"] . "' and registration_date = '" . $date . "'";
        $day = $_MooClass['MooMySQL']->getOne($sql);
        
        if (empty($day)) {
            $param = array();
            $param["registration_date"] = $date;
            $param["site_id"] = $site["id"];
            $param["ip" . $h] = $ip_count;
            $param["uv" . $h] = $uv_count;
            $param["pv" . $h] = $pv_count;
            
            inserttable("day", $param);
        } else {
            // 插入值
            $param = array();
            $param["ip" . $h] = $ip_count;
            $param["uv" . $h] = $uv_count;
            $param["pv" . $h] = $pv_count;
            
            // 条件
            $w_param = array();
            $w_param["registration_date"] = $date;
            $w_param["site_id"] = $site["id"];
            updatetable("day", $param, $w_param);
        }
    }
}

/**
 * 时间内的统计,以小时为单位
 * param $st date
 * param $et date
 * return $visit_day array
 */
function getSearchDayByHour($st = null, $et = null, $site_id = 1) {
    global $_MooClass,$dbTablePre;
    
    // 更新本小时的数据
    updateDayByHour(date("Y-m-d H"));
    
    if ($st == null) {
        $st = date("Y-m-d");
    }
    if ($et == null) {
        $et = date("Y-m-d");
    }
    
    $sql = "select" .
            " sum(ip0) as ip0, " .
            " sum(ip1) as ip1, " .
            " sum(ip2) as ip2, " .
            " sum(ip3) as ip3, " .
            " sum(ip4) as ip4, " .
            " sum(ip5) as ip5, " .
            " sum(ip6) as ip6, " .
            " sum(ip7) as ip7, " .
            " sum(ip8) as ip8, " .
            " sum(ip9) as ip9, " .
            " sum(ip10) as ip10, " .
            " sum(ip11) as ip11, " .
            " sum(ip12) as ip12, " .
            " sum(ip13) as ip13, " .
            " sum(ip14) as ip14, " .
            " sum(ip15) as ip15, " .
            " sum(ip16) as ip16, " .
            " sum(ip17) as ip17, " .
            " sum(ip18) as ip18, " .
            " sum(ip19) as ip19, " .
            " sum(ip20) as ip20, " .
            " sum(ip21) as ip21, " .
            " sum(ip22) as ip22, " .
            " sum(ip23) as ip23, " .
            " sum(pv0) as pv0, " .
            " sum(pv1) as pv1, " .
            " sum(pv2) as pv2, " .
            " sum(pv3) as pv3, " .
            " sum(pv4) as pv4, " .
            " sum(pv5) as pv5, " .
            " sum(pv6) as pv6, " .
            " sum(pv7) as pv7, " .
            " sum(pv8) as pv8, " .
            " sum(pv9) as pv9, " .
            " sum(pv10) as pv10, " .
            " sum(pv11) as pv11, " .
            " sum(pv12) as pv12, " .
            " sum(pv13) as pv13, " .
            " sum(pv14) as pv14, " .
            " sum(pv15) as pv15, " .
            " sum(pv16) as pv16, " .
            " sum(pv17) as pv17, " .
            " sum(pv18) as pv18, " .
            " sum(pv19) as pv19, " .
            " sum(pv20) as pv20, " .
            " sum(pv21) as pv21, " .
            " sum(pv22) as pv22, " .
            " sum(pv23) as pv23, " .
            " sum(uv0) as uv0, " .
            " sum(uv1) as uv1, " .
            " sum(uv2) as uv2, " .
            " sum(uv3) as uv3, " .
            " sum(uv4) as uv4, " .
            " sum(uv5) as uv5, " .
            " sum(uv6) as uv6, " .
            " sum(uv7) as uv7, " .
            " sum(uv8) as uv8, " .
            " sum(uv9) as uv9, " .
            " sum(uv10) as uv10, " .
            " sum(uv11) as uv11, " .
            " sum(uv12) as uv12, " .
            " sum(uv13) as uv13, " .
            " sum(uv14) as uv14, " .
            " sum(uv15) as uv15, " .
            " sum(uv16) as uv16, " .
            " sum(uv17) as uv17, " .
            " sum(uv18) as uv18, " .
            " sum(uv19) as uv19, " .
            " sum(uv20) as uv20, " .
            " sum(uv21) as uv21, " .
            " sum(uv22) as uv22, " .
            " sum(uv23) as uv23 " . 
            "from " . $dbTablePre . "day where registration_date >= '" . $st . "' and registration_date <= '" . $et . "' and site_id = '" . $site_id . "'";
    $visit_day = $_MooClass['MooMySQL']->getOne($sql);
    
    return $visit_day;
}

/**
 * 统计按天为单位统计PV, UV, IP
 * param $st date
 * param $et date
 * param $site_id int
 * return $visit_list array()
 */
function  getSearchDayByDay($st, $et, $site_id = 1) {
    global $_MooClass,$dbTablePre;
    
    // 更新本小时的数据
    updateDayByHour(date("Y-m-d H"));
    
    if ($st == null) {
        $st = date("Y-m-d");
    }
    
    if ($et == null) {
        $et = date("Y-m-d");
    }
    
    $sql = "select registration_date, " .
            " (ip0 + ip1 + ip2 + ip3 + ip4 + ip5 + ip6 + ip7 + ip8 + ip9 + ip10 + ip11 +ip12 + ip13 + ip14 + ip15 + ip16 + ip17 + ip18 + ip19 + ip20 + ip21 + ip22 + ip23) as ip, " .
            " (pv0 + pv1 + pv2 + pv3 + pv4 + pv5 + pv6 + pv7 + pv8 + pv9 + pv10 + pv11 +pv12 + pv13 + pv14 + pv15 + pv16 + pv17 + pv18 + pv19 + pv20 + pv21 + pv22 + pv23) as pv, " .
            " (uv0 + uv1 + uv2 + uv3 + uv4 + uv5 + uv6 + uv7 + uv8 + uv9 + uv10 + uv11 +uv12 + uv13 + uv14 + uv15 + uv16 + uv17 + uv18 + uv19 + uv20 + uv21 + uv22 + uv23) as uv " .
            "from " . $dbTablePre . "day where registration_date >= '" . $st . "' and registration_date <= '" . $et . "' and site_id = '" . $site_id . "'";
    $visit_list = $_MooClass['MooMySQL']->getAll($sql);
    
    // 处理中间没有值的
    list($year,$month,$day) = explode("-",$et);
    $et_time = mktime(0,0,0,$month,$day,$year);
    list($year,$month,$day) = explode("-",$st);
    $st_time = mktime(0,0,0,$month,$day,$year);
    
    $between_day = ($et_time - $st_time) / (3600*24);
    
    $return = array();
    for ($i = 0; $i <= $between_day; $i++) {
        $day_tmp = date("Y-m-d", mktime(0, 0, 0, $month, $day + $i, $year));
        
        $return[$day_tmp]["registration_date"] = $day_tmp;
        $return[$day_tmp]["ip"] = 0;
        $return[$day_tmp]["pv"] = 0;
        $return[$day_tmp]["uv"] = 0;
    }
    
    foreach ($visit_list as $visit) {
        $return[$visit["registration_date"]]["ip"] = $visit["ip"];
        $return[$visit["registration_date"]]["pv"] = $visit["pv"];
        $return[$visit["registration_date"]]["uv"] = $visit["uv"];
    }
    
    return $return;
}


/**
 * 获取来路分类
 * param $st date
 * param $ed date
 * param $site_id int
 * param $type string
 * return $domain_list
 */
function getRefererByFL($st, $et, $site_id = 1, $type = null) {
    global $_MooClass, $dbTablePre;

    if ($type == "day") {
        // 按天进行分类
        $sql = "select count(referer_flg) as count, referer_flg, visit_server_date from " . $dbTablePre . "visit where site_id = '" . $site_id . "' and visit_server_date >= '" . $st . "' and visit_server_date <= '" . $et . "' group by visit_server_date, referer_flg";
        $domain_list = $_MooClass["MooMySQL"]->getAll($sql);
        
        // 默认情况下来源页分为三个部分：搜索引擎，本站，其它
        // debug
        
        // 处理相差的天数
        list($year, $month, $day) = explode("-", $et);
        $et_s = mktime(0, 0, 0, $month, $day, $year);
        list($year, $month, $day) = explode("-", $st);
        $st_s = mktime(0, 0, 0, $month, $day, $year);
        $between = ($et_s - $st_s) / 86400;
        
        $return = array();
        for ($i = 0; $i <= $between; $i++) {
            $tmp_date = date("Y-m-d", mktime(0, 0, 0, $month, $day + $i, $year));
            
            $return[$tmp_date]["S"] = 0;
            $return[$tmp_date]["O"] = 0;
            $return[$tmp_date]["R"] = 0;
        }
        
        foreach ($domain_list as $tmp) {
            $return[$tmp["visit_server_date"]][$tmp["referer_flg"]] = $tmp["count"];
        }
    } else if ($type == "hour") {
        // 按小时进行分类
        $sql = "select  count(referer_flg) as count, referer_flg, substring(registration_date, 12, 2) as h from " . $dbTablePre . "visit where site_id = '" . $site_id . "' and visit_server_date = '" . $st . "' group by referer_flg, substring(registration_date, 12, 2)";
        $domain_list = $_MooClass["MooMySQL"]->getAll($sql);
        
        // 默认情况下来源页分为三个部分：搜索引擎，本站，其它
        // debug
        $return = array();
        for ($i = 0; $i < 24; $i++) {
            $tmp = "";
            if ($i < 10) {
                $tmp = 0 . $i;
            }
            
            $return[$i]["S"] = 0;
            $return[$i]["O"] = 0;
            $return[$i]["R"] = 0;
        }
        
        foreach ($domain_list as $tmp) {
            $tmp_h = $tmp["h"] + 0;
            $return[$tmp_h][$tmp["referer_flg"]] = $tmp["count"];
        }
    } else {
        // 按来源类型分类
        $sql = "select count(referer_flg) as count, referer_flg from " . $dbTablePre . "visit where site_id = '" . $site_id . "' and visit_server_date >= '" . $st . "' and visit_server_date <= '" . $et . "' group by referer_flg";
        $domain_list = $_MooClass["MooMySQL"]->getAll($sql);
        
        // 默认情况下来源页分为三个部分：搜索引擎，本站，其它
        // debug
        $return = array();
        $return["S"] = 0;
        $return["O"] = 0;
        $return["R"] = 0;
        
        foreach ($domain_list as $tmp) {
            $return[$tmp["referer_flg"]] = $tmp["count"];
        }
    }
    
    return $return;
}

/**
 * 获取两天相差的天数
 * param $st date 日期格式:2009-01-01
 * param $et date
 * return $between_day int
 */
function getBetweenByDay($st, $et) {
    list($year, $month, $day) = explode("-", $et);
    $et_t = mktime(0, 0, 0, $month, $day, $year);
    list($year, $month, $day) = explode("-", $st);
    $st_t = mktime(0, 0, 0, $month, $day, $year);
    
    return abs($st_t - $et_t) / 86400;
}


function allotserver($uid){
    /*
        global $_MooClass,$dbTablePre;
        $user=$_MooClass['MooMySQL']->getOne("SELECT `sid` FROM `".$dbTablePre."members` WHERE `uid`='".$uid."' and usertype=1 and is_lock = 1");
        if($user['sid']==null||$user['sid']==0){
                $_MooClass['MooMySQL']->query("UPDATE `{$dbTablePre}members` SET is_well_user=1 WHERE uid='".$uid."'");
                if(MOOPHP_ALLOW_FASTDB){
                    MooFastdbUpdate('members','uid',$uid);
                }
        }
     */
}

//会员填写的信息不完整，视为低质量会员自动分配给普通客服
function invalid_user_allotserver($uid){
    global $_MooClass,$dbTablePre;
    
    if(MooMembersData($uid, 'is_lock') != 1 || MooMembersData($uid, 'usertype') != 1)
    	return FALSE;
    //查询此会员是否分配过
    $user = $_MooClass['MooMySQL']->getOne("SELECT lastvisit FROM `".$dbTablePre."members_login` WHERE `uid`='".$uid."'");
    $user['sid'] = MooMembersData($uid, 'sid');

    /*
    $user=$_MooClass['MooMySQL']->getOne("SELECT `sid`,lastvisit FROM `".$dbTablePre."members_login` WHERE `uid`='".$uid."' AND is_lock = 1 AND usertype=1");
    */
    if($user['sid']==0){
        $time = strtotime(date('Y-m-d'));
        //查询可以分配给的客服
        $sql = "SELECT uid,member_count FROM {$dbTablePre}admin_user 
                WHERE
                member_count<400 AND is_allot=1 AND
                ( 
                ( allot_member<20 AND allot_time>'$time')
                OR
                ( allot_time<'{$time}')
                )
                ORDER BY member_count ASC,allot_member ASC LIMIT 1";
        $service_list = $_MooClass['MooMySQL']->getOne($sql);
        
        if(!empty($service_list)){
            $timestamp = time();
            //更新members表中sid及分配时间
            $sql = "UPDATE {$dbTablePre}members_search SET sid='{$service_list['uid']}' WHERE uid='{$uid}'";
            $_MooClass['MooMySQL']->query($sql);
            searchApi('members_man members_women')->updateAttr(array('sid'),array($uid=>array($service_list['uid'])));
			$sql = "UPDATE {$dbTablePre}members_base SET allotdate='{$timestamp}' WHERE uid='{$uid}'";
            $_MooClass['MooMySQL']->query($sql);
            
            //判断member_admininfo表中是否有此会员,没有时插入到后台扩展表
            $sql = "SELECT uid FROM {$GLOBALS['dbTablePre']}member_admininfo WHERE uid='{$uid}'";
            $member_admin_info = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
            if(empty($member_admin_info)){
                $sql = "INSERT INTO {$GLOBALS['dbTablePre']}member_admininfo SET uid='{$uid}',effect_grade=1";
                $GLOBALS['_MooClass']['MooMySQL']->query($sql);
            }

            //更新后台客服表中对应客服的分配会员总数及当日分配总数
            $sql = "UPDATE {$dbTablePre}admin_user SET member_count=member_count+1,allot_member=allot_member+1,allot_time='{$timestamp}' WHERE uid='{$service_list['uid']}'";
            $_MooClass['MooMySQL']->query($sql);

        }
    }
}

/**
 * 根据日期，得出前一天，前七天，本月等信息
 * param $st date
 * param $et date
 * 
 * return $return string
 */
function getSearchNavByDay($st, $et, $url, $type = "day", $target = null) {
    $str = "";
    
    $today = date("Y-m-d");
    $yesterday = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 1));
    $week_day = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 6));
    $last_30_day = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 29));
    $month_day = date("Y-m-01");
    
    $sep_str = strstr($url, "?") ? "&" : "?"; 
    $str = "<a href='" . $url . $sep_str . "st=" . $today . "&et=" . $today . "' " . $target . ">今天</a> \n";
    $str .= "<a href='" . $url . $sep_str . "st=" . $yesterday . "&et=" . $yesterday . "' " . $target .">昨天</a> \n";
    $str .= "<a href='" . $url . $sep_str . "st=" . $week_day . "&et=" . $today . "&d=week' " . $target ."&type=7>最近七天</a> \n";
    $str .= "<a href='" . $url . $sep_str . "st=" . $last_30_day . "&et=" . $today . "&d=30' " . $target ."&type=30>最近30天</a> \n";
    $str .= "<a href='" . $url . $sep_str . "st=" . $month_day . "&et=" . $today . "&d=month' " . $target ."&type=month>本月</a> \n";
    
    if ($type == "week") {
        list($year, $month, $day) = explode("-", $st);
        $pre_day = date("Y-m-d", mktime(0, 0, 0, $month, $day - 7, $year));
        $next_day = date("Y-m-d", mktime(0, 0, 0, $month, $day - 1, $year));
        
        $str .= "<input type='button' value='前七天' onclick=\"window.location.href='" . $url . "&st=" . $pre_day . "&et=" . $next_day . "&d=week'\" /> ";
        
        list($year, $month, $day) = explode("-", $et);
        $pre_day = date("Y-m-d", mktime(0, 0, 0, $month, $day + 1, $year));
        $next_day = date("Y-m-d", mktime(0, 0, 0, $month, $day + 7, $year));
        
        $disabled = '';
        if ($pre_day > $today) {
            $disabled = "disabled=true";
        }
        
        $str .= "<input type='button' value='后七天' onclick=\"window.location.href='" . $url . "&st=" . $pre_day . "&et=" . $next_day . "&d=week'\" " . $disabled . " /> ";
    } else if ($type == "30") {
        list($year, $month, $day) = explode("-", $st);
        $pre_day = date("Y-m-d", mktime(0, 0, 0, $month, $day - 30, $year));
        $next_day = date("Y-m-d", mktime(0, 0, 0, $month, $day - 1, $year));
        
        $str .= "<input type='button' value='前30天' onclick=\"window.location.href='" . $url . "&st=" . $pre_day . "&et=" . $next_day . "&d=30'\" /> ";
        
        list($year, $month, $day) = explode("-", $et);
        $pre_day = date("Y-m-d", mktime(0, 0, 0, $month, $day + 1, $year));
        $next_day = date("Y-m-d", mktime(0, 0, 0, $month, $day + 30, $year));
        
        $disabled = '';
        if ($pre_day > $today) {
            $disabled = "disabled=true";
        }
        
        $str .= "<input type='button' value='后30天' onclick=\"window.location.href='" . $url . "&st=" . $pre_day . "&et=" . $next_day . "&d=30'\" " . $disabled . " /> ";
    } else if ($type == "month") {
        list($year, $month, $day) = explode("-", $st);
        $pre_day = date("Y-m-d", mktime(0, 0, 0, $month - 1, $day, $year));
        $next_day = date("Y-m-d", mktime(0, 0, 0, $month, $day - 1, $year));
        
        $str .= "<input type='button' value='上月' onclick=\"window.location.href='" . $url . "&st=" . $pre_day . "&et=" . $next_day . "&d=month'\" /> ";
        
        list($year, $month, $day) = explode("-", $et);
        $pre_day = date("Y-m-d", mktime(0, 0, 0, $month, $day + 1, $year));
        $next_day = date("Y-m-d", mktime(0, 0, 0, $month + 1, date("t", mktime(0, 0, 0, $month + 1, 1, $year)), $year));
        
        $disabled = '';
        if ($pre_day > $today) {
            $disabled = "disabled=true";
        }
        
        $str .= "<input type='button' value='下月' onclick=\"window.location.href='" . $url . "&st=" . $pre_day . "&et=" . $next_day . "&d=month'\" " . $disabled . " /> ";
    } else {
        list($year, $month, $day) = explode("-", $st);
        $pre_day = date("Y-m-d", mktime(0, 0, 0, $month, $day - 1, $year));
        $str .= "<input type='button' value='前一天' onclick=\"window.location.href='" . $url . "&st=" . $pre_day . "&et=" . $pre_day . "'\" /> ";
        
        $disabled = '';
        $next_day = date("Y-m-d", mktime(0, 0, 0, $month, $day + 1, $year));
        if ($next_day > $today) {
            $disabled = "disabled=true";
        }
        $str .= "<input type='button' value='后一天' onclick=\"window.location.href='" . $url . "&st=" . $next_day . "&et=" . $next_day . "'\" " . $disabled . " /> ";
    } 
    
    $str .= "从<input name=\"start_time\" type=\"text\" value=\"" . $st . "\" style=\"width:75px;\" />";
    $str .= "　到<input name=\"end_time\" type=\"text\" value=\"" . $et . "\" style=\"width:75px;\" />";
    $str .= " <input name='' type=\"button\" value=\"查 询\" onclick=\"search();\" />";
    return $str;
}

/**
 * 根据搜索引擎url所回中文名
 * param $hostname string
 * return $return string
 */
function getSearchEngineName($hostname = null) {
    if ($hostname == null) {
        return null;
    }

    switch ($hostname) {
        case "www.baidu.com":
            return "百度";
            break;
        case "www.google.com":
            return "谷歌";
            break;
        case "www.bing.com":
            return "必应";
            break;
        case "www.soso.com":
            return "搜搜";
            break;
        case "www.yahoo.com":
            return "雅虎";
            break;
        case "www.youdao.com":
            return "有道";
            break;
        case "www.118114.com":
            return "114";
            break;
        case "www.sogou.com":
            return "搜 狗";
            break;
        case "www.zhongsou.com":
            return "中搜";
            break;
        default:
            return "未知";
            break;
    }
} 

/**
 * 是否通过手机验证和身份验证
 * @Author:
 * param $userid
 * return 是true,否false
 */
 function checkIsMobileCertical($userid){
    global $_MooClass,$dbTablePre;
    //$sql  = "select count(*) as c from " . $dbTablePre . "certification where uid = '" . $userid . "' and sms = '1' and telphone != ''";
    $sql  = "select count(*) as c from " . $dbTablePre . "certification where uid = '" . $userid . "' and telphone != ''";
    $vertical_arr = $_MooClass["MooMySQL"]->getOne($sql,true);
    if($vertical_arr['c']){
      return true;
    }else{
      return false;
    }
 }
 
 /**
 * 注册是否过了试用期或是否客服延长了试用期
 * @Author:
 * param $userid
 * return 没过返回true,过了返回false
 */
 function checkIsOver($userid){
    global $_MooClass,$dbTablePre;
    $cur_time = time();
    /*
    $sql  = "select regdate,tastetime,gender from " . $dbTablePre . "members where uid = '" . $userid . "' and is_lock = 1";
    $date_arr = $_MooClass["MooMySQL"]->getOne($sql);
    */
    $user_arr = MooMembersData($userid);
    if($user_arr['is_lock'] != 1) return FALSE;
        
    if($user_arr['gender'] == 1) $regdate =  $user_arr['regdate'] + (24*3600*3); //女性会员注册时间 + 加三天试用期
    //$regdate =  $user_arr['regdate'] + (24*3600*3); //会员注册时间 + 加三天试用期
    //$user_arr['gender'] == 1 && $regdate =  $user_arr['regdate'] + (24*3600*3); //女性会员注册时间 + 加三天试用期
    $tastetime = intval($user_arr['tastetime']); //客服延长时间
    if($user_arr['gender'] == 0){
        if($cur_time < $tastetime){
            return true;
        }
        return false;
    }else{
        if($cur_time < $regdate){
            return true;
        }elseif($cur_time < $tastetime){
            return true;
        }
        return false;
        
    }
 }
 
/**
 * 判断是不是客服伪造登录
 * @Author:
 * return 是返回true,不是返回false
*/
 function Moo_is_kefu(){
    global $_MooCookie;
    //$kefu = $_COOKIE['web_kefu'];
    if (!isset($_MooCookie['kefu']))
        return 0;
    $kefu = MooAuthCode(urldecode($_MooCookie['kefu']), 'DECODE');
    if($kefu){
	    list($check, $sid) = explode("\t", $kefu);
	    if(($check=="hongniangwang"&&$sid)){
	        return $sid;
	    }
    }
    
    return 0;
 }
 

 /**
 * 将部分需要发送的短信入库已备定时发送
 * @Author:
 * return 是返回true,不是返回false
*/
function Push_message_intab($uid,$tel,$type,$comment,$sid,$t=0){
    global $_MooClass,$dbTablePre;
    $t = $t>0?$t:time();
    /*
    $uid=$uid;
    $tel=$tel;
    $type=$type;
    $comment=$comment;
    */
    $type_num = '';
    $is_phone=$_MooClass['MooMySQL']->getOne("select is_phone from {$dbTablePre}members_base where uid='$uid'");
    if($tel && $is_phone['is_phone']){
        switch($type){
            case "委托":
                $type_num = 1;
                break;              
            case "秋波":
                $type_num = 2;
                break;
            case "站内信":
                $type_num = 3;
                break;
            case "邮件":
                $type_num = 4;
                break;
            case "鲜花":
                $type_num = 5;
                break;
            case "关注会员上线提醒":
                $type_num = 6;
                break;
            case "密码修改成功":
                $type_num = 7;
                break;
            case "索取身份":
                $type_num = 8;
                break;
            case "意中人":
                $type_num = 9;
                break;
            case "评价人":
                $type_num = 10;
                break;    
            case "绑定":
            	$type_num=11;
            	break;                                          
        }
        
        $md5 = md5($uid.$sid.$tel.$type_num);   
        $sql = "select md5 from {$dbTablePre}sendmsg_tmp where md5 = '$md5'";
        $row = $_MooClass['MooMySQL']->getOne($sql);

        if(!isset($row['md5'])){
             $query = $_MooClass['MooMySQL']->query("INSERT INTO {$dbTablePre}sendmsg_tmp (id,type,uid,telphone,message,count,dateline,sid,md5) values('','$type','$uid','$tel','$comment','0','$t','$sid','$md5')");
            if($query){
                return true;
            }else{
                return false;
            }
        }
        
    
    }
}

/**
 * 判断是不是客服伪造登录
 * @Author:
 * param $type integer 操作类型
 * param $table string 操作的表名
 * param $hangle string 操作内容
 * param $sid integer 操作的客服id
 * return 是返回true,不是返回false
 */
function Mooserverlog($type,$table,$handle,$sid) {
    global $_MooClass,$dbTablePre;
    $t = time();
    $type=$type;
    $handle=$handle;
    $sid=$sid;
    $table=$table;
    if($_MooClass['MooMySQL']->query("INSERT INTO {$dbTablePre}server_log (type,tablename,handle,sid,time) values('$type','$table','$handle','$sid','$t')")){
        return true;
    }else{
        return false;
    }
}

/**
 * 从fastdb获取内容
 * @Author:
 * param $table string 表
 * param $key string 条件
 * param $field string 字段
 * return $arr  array()
 */
function MooFastdbGet($table,$field,$key) {
    global $fastdb,$_MooClass,$dbTablePre;
    if($_GET['debug']){
        $time = microtime();
        echo $iscache = '<br /><font style="color:#669900">';
    }
    $value = $fastdb->get($table.$field.$key);
    if(empty($value)){
       $arr = $_MooClass['MooMySQL']->getOne("SELECT * FROM {$dbTablePre}{$table} WHERE {$field}='{$key}'  LIMIT 1",true);
       $fastdb->set($table.$field.$key,serialize($arr));
    }else{
       $arr = unserialize($value);
	
       if(!$arr){
			$arr = $_MooClass['MooMySQL']->getOne("SELECT * FROM {$dbTablePre}{$table} WHERE {$field}='{$key}'  LIMIT 1",true);
            $fastdb->set($table.$field.$key,serialize($arr));
            $value = $fastdb->get($table.$field.$key);
            $arr = unserialize($value);     	
       }
    }
    
    if($_GET['debug']){
        $time = (microtime()-$time)*1000;
        echo $iscache = 'fastdb'.$time.$table.$field.$key.'</font><br />';
        //print_r($arr);
        //echo "<br />";
    }
    return $arr;
}

/**
 * 向fastdb写入内容
 * @Author:
 * param $table string 表
 * param $key string 条件
 * param $field string 字段
 * param $upvalue array() 字段
 * return $return int  1:set 2:replace
 */
function MooFastdbUpdate($table,$field,$key,$upvalue="") {
    global $fastdb,$_MooClass,$dbTablePre;
    
    if(empty($upvalue)){
            $fbvalue = $_MooClass['MooMySQL']->getOne("SELECT * FROM {$dbTablePre}{$table} WHERE {$field}='{$key}'  LIMIT 1",true);
            if( $fastdb->get($table.$field.$key) ){
			  
                $fastdb->replace($table.$field.$key,serialize($fbvalue));
                $return = 2;
            }else{
			    
                $fastdb->set($table.$field.$key,serialize($fbvalue));
                $return = 1;
            }
    }else{
           
            if( !( $fbvalue = unserialize($fastdb->get($table.$field.$key)) ) ){
                $fbvalue = $_MooClass['MooMySQL']->getOne("SELECT * FROM {$dbTablePre}{$table} WHERE {$field}='{$key}'  LIMIT 1",true);
                $return = 1;
            }else{
                $return = 2;
            } 
		
            foreach($upvalue as $upkey => $val){
                $fbvalue[$upkey] = $val;
            }
			
			
            if($return == 2){
                $fastdb->replace($table.$field.$key,serialize($fbvalue));  
                				
            }
            if($return == 1){
                $fastdb->set($table.$field.$key,serialize($fbvalue));               
            }           
    }
    
    return $return;
}

function UpdateMembersSNS($uid,$content){
    global $_MooClass,$dbTablePre;
    $temptime=time();
    $sql="insert into '{$dbTablePre}'members_sns set content='$content',uid='$uid',dateline='$temptime'";
    $_MooClass['MooMySQL']->query("INSERT INTO {$dbTablePre}members_sns (id,uid,content,dateline) values('','$uid','$content','$temptime')");   
}

/**
 * 爱情状态
 * @status 返回的结果集
 */
function loveStatus($status) {
   global $timestamp;
   $str="甜蜜恋爱中";//initilization
   
   if(($timestamp - $status['lastvisit']) < 600){//判断在线
     $color="color:red";//当前在线红色;
   }else{
     $color="color:black";//不在线灰色;
   }

        
   if(!empty($status)){
       if($status['showinformation_val']==0){//判断状态
          $str="寻觅爱情中";
       }else{
          $str="甜蜜恋爱中";
       }
            
       if ($status['is_lock'] == 0) { //值为1是开启资料，未封锁状态
          $str="甜蜜恋爱中";
       }
    }
    
    $str="<span style='{$color}'>".$str."</span>";
    
    return $str;
    
}


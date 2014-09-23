<?PHP
/*
	More & Original PHP Framwork
	Copyright (c) 2007 - 2008 IsMole Inc.
	$Id: MooConfig5.php 395 2008-10-13 03:43:32Z kimi $
*/

//note 定义PHP报错级别
//error_reporting(E_ERROR | E_WARNING | E_PARSE);


//note 开启Block缓存功能
define('MOOPHP_ALLOW_BLOCK', TRUE);
//note 开启系统缓存功能
define('MOOPHP_ALLOW_CACHE', TRUE);
//note 系统是否使用MYSQL数据库，当其值为TRUE时，将根据MySQL的相关配置参数自动初始化MYSQL链接
define('MOOPHP_ALLOW_MYSQL', TRUE);

//note 系统是否使用memcache,TRUE时使用 并自动初始化
define('MOOPHP_ALLOW_MEMCACHED',TRUE);
//note 系统是否使用fastdb,TRUE时使用 并自动初始化
define('MOOPHP_ALLOW_FASTDB',TRUE);

//note 定义cookie前缀
define('MOOPHP_COOKIE_PRE', 'Moo_');
//note 定义cookie作用域
define('MOOPHP_COOKIE_PATH', '/');
//note 定义cookie作用路径
define('MOOPHP_COOKIE_DOMAIN', 'zays.com');
//取主域

/* $sithost_str =  $_SERVER['HTTP_HOST'];
$host_arr = explode('.',$sithost_str);
$num_arr_int = count($host_arr);
$hostname_str = $host_arr[$num_arr_int-2].'.'.$host_arr[$num_arr_int-1];

define('MOOPHP_COOKIE_DOMAIN', $hostname_str); */

//note 定义加密所需Key，请修改此处为唯一
// !defined('MOOPHP_AUTHKEY') && define('MOOPHP_AUTHKEY', '_z_a_y_s_');

//note 定义是否开启MooPHP的Debug功能
define('MOOPHP_DEBUG', false);

//note MySQL的主机地址，通常为localhost
//$dbHost = '192.168.10.249:8066';

$dbHost = '127.0.0.1:3306';


//note 系统使用的MySQL的数据库名，比如project_moophp
$dbName = 'caiji';
//$dbName ='0052';
//note MySQL的用户名
$dbUser = 'root';
//$dbUser='admin7651';
//note MySQL的用户名对应的密码
$dbPasswd = '666666';
//$dbPasswd ='net12349sb';
//note MySQL表前缀
$dbTablePre = 'web_';
//note MySQL数据库字符集，推荐为UTF-8
$dbCharset = 'UTF-8';
//note 是否为持续链接
$dbPconnect = 0;

//slave mysql --------------------------
$slave_dbHost = '127.0.0.1:3306';
//$dbHost ='222.73.19.184:3306';

//note 系统使用的MySQL的数据库名，比如project_moophp
$slave_dbName = 'caiji';
//$dbName ='0052';
//note MySQL的用户名
$slave_dbUser = 'root';
//$dbUser='admin7651';
//note MySQL的用户名对应的密码
$slave_dbPasswd = '666666';
//$dbPasswd ='net12349sb';
//note MySQL表前缀
$slave_dbTablePre = 'web_';
//note MySQL数据库字符集，推荐为UTF-8
$slave_dbCharset = 'UTF-8';
$slave_dbPconnect = 0;

//note 定义 memcached主机地址
$memcachehost = '127.0.0.1';
//note 定义 memcached服务端口
$memcacheport = '11211';
//note 定义 memcached生命长度
$memcachelife = '60';

//note 定义 fastdb主机地址
$fastdbhost = '127.0.0.1';
//note 定义 fastdb服务端口
$fastdbport = '11211';


 

//note mail服务器地址
$mailHost = 'smtp.163.com';
//note 邮件账号
$mailUser = 'kefu@zhenaiyisheng.cc';
//note 邮件密码
$mailPasswd = 'zays5920';
//note 发送者邮箱
$mailSenderMail = 'zayswww@163.com';
//note 邮件发送者
$mailSenderName = '真爱一生网';
//note 模板页URL
$mailTemplateFile = 'public/system/mailtamp/template.html';
$charset = 'UTF-8';


//定义memcached记录用户在线的键值常量
//在线统计记录
define('MEMBERS_ONLINE_COUNT', 'members_online_count');
//单一用户在线状态记录键值
define('MEMBERS_ONLINE_SINGLE', 'members_online_');

//sphinx索引地址
define("SPH_HOST","localhost");

$masterConf = array(
     "host"    => "127.0.0.1:3306",
     "user"    => "root",
     "pwd"    => "666666",
     "db"    => "caiji"
     );
$slaveConf = array(
     "host"    => "127.0.0.1",
     "user"    => "root",
     "pwd"    => "666666",
     "db"    => "caiji"
     );

$slaveConf_ework = array(
     "host"    => "127.0.0.1",
     "user"    => "root",
    "pwd"    => "666666",
     "db"    => "caiji"
     );

$masterConf_ework = array(
     "host"    => "127.0.0.1:3306",
     "user"    => "root",
     "pwd"    => "666666",
     "db"    => "caiji"
     );

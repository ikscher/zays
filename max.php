<?php



// echo PHP_INT_MAX;


require 'config.php';
require 'framwork/MooConfig.php';
require 'framwork/MooPHP.php';


 $randStr = '%hongniang%';//一个任意的字符串
 $key_pre = 'chat_';//memcache键值前缀
 $touid='30017759';
$key = $key_pre.$touid.$randStr;
$touid_='30002761';

$key_=$key_pre.$touid_.$randStr;

$msg=$memcached->get($key);

var_dump($msg);

echo "<br/>";


$msg=$memcached->get($key_);

var_dump($msg);


<?php
error_reporting(0);
require 'config.php';

//note 加载框架
require 'framwork/MooPHP.php';
list($s,$m) = explode(' ', microtime());
$start = $s+$m;
//------------------------------------------------
$index = "choice";
$sp = searchApi($index);

$arr = array(array('marriage','4'));
$res = $sp->getResult();

var_dump($res['total']);
echo '<br/>';
//----------------------------------------------------
list($s,$m) = explode(' ', microtime());
$end = $s+$m;
echo $end-$start;
?>
<?php
/*  
	Copyright (c) 2009 

	$Id: index.php 399 2009-10-14 07:11:04Z techice $
*/


$name = empty($_GET['n'])?'index':$_GET['n'];

//note 加载框架配置参数
require 'config.php';

//note 加载框架
require 'framwork/MooPHP.php';

//允许的方法
$names = array('login', 'index', 'register', 'lostpasswd', 'inputpwd','sns', 'viewspace', 'relatekw', 'ajax', 'seccode', 'sendmail');

if(!in_array($name, $names)) {
	showmessage('enter_the_space', 'index.php', 0);
}



include  "module/".@ strtolower($name)."/api.php"; 


?>
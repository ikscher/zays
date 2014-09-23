<?php
require 'db_import.class.php';
session_start();
set_time_limit(0);
header("content-type:text/html;charset=utf-8");

//if (isset($_REQUEST['submit'])){
	//记录时间
	$start = isset($argv[1]) ? $argv[1] : exit('start is no define');
	$curr_file = isset($argv[2]) ? $argv[2] : exit('curr_file is no define'); 
	$i = isset($argv[3]) ? $argv[3] : exit('i is no define');
	if (!fopen($curr_file,'r')) exit($curr_file . '文件不可读');
		
	$import = new db_import($curr_file,$start,$i);
	$import->insertMain();
	
//}





		
//

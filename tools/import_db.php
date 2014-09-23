<?php
require 'db_import.class.php';
session_start();
set_time_limit(0);
header("content-type:text/html;charset=utf-8");

if (isset($_REQUEST['submit'])){
	//记录时间
	if (!isset($_SESSION['exec_time']))$_SESSION['exec_time'] = time();
	$start = empty($_REQUEST['start']) ? 0 : $_REQUEST['start']; 
	if (isset($_REQUEST['xml_file'])) $_SESSION['xmlfile'] = serialize($_REQUEST['xml_file']);
	$xml = isset($_REQUEST['xml_file']) ? $_REQUEST['xml_file'] : unserialize($_SESSION['xmlfile']);
	foreach ($xml as $x){
		if (isset($_REQUEST['curr_file']) && ($x != $_REQUEST['curr_file'])) continue;
		//$x = $_REQUEST['curr_file'];
		$i = isset($_REQUEST['i']) ? $_REQUEST['i'] : 1;
		if (!fopen($x,'r'))exit('文件不可读');
		
		$import = new db_import($x,$start,$i);
		$import->shellMain();
	}
	
}





		
//

<?php
/*************************************** 逻辑层(M)/表现层(V) ****************************************/ 

include "./module/{$name}/function.php";

function hnintro_index(){
	if(empty($_GET['hnid'])){
		$hnid= '';
	}else{
		$hnid = (int)$_GET['hnid'];
	}
	if($hnid < 1 || $hnid > 14) $hnid = 1;
	include MooTemplate('public/hnintroduce_index', 'module');
}

/***************************************   控制层(C)   ****************************************/

$h = MooGetGPC('h', 'string');

//$back_url='index.php?'.$_SERVER['QUERY_STRING'];
//if(!$userid) {header("location:index.php?n=login&back_url=".urlencode($back_url));}

switch ($h) {
	case 'index':
	default :
		hnintro_index();
	break;	
}


?>
<?php

/**
 * 功能列表
 */

/*************************************** 逻辑层(M)/表现层(V) ****************************************/ 
function safetyguide() {
  global $user_arr;
	include MooTemplate('public/safetyguide_index', 'module');
}

function safetyguide1() {
  global $user_arr;
	include MooTemplate('public/safetyguide_guide1', 'module');
}

function safetyguide2() {
  global $user_arr;
	include MooTemplate('public/safetyguide_guide2', 'module');
}

function safetyguide3() {
  global $user_arr;
	include MooTemplate('public/safetyguide_guide3', 'module');
}

function safetyguide4() {
  global $user_arr;
	include MooTemplate('public/safetyguide_guide4', 'module');
}

function safetyguide5() {
  global $user_arr;
	include MooTemplate('public/safetyguide_guide5', 'module');
}

function safetyguide6() {
  global $user_arr;
	include MooTemplate('public/safetyguide_guide6', 'module');
}



/***************************************   控制层(C)   ****************************************/

$h = MooGetGPC('h', 'string');
$h = in_array($h, array('safetyguide','safetyguide1','safetyguide2','safetyguide3','safetyguide4','safetyguide5','safetyguide6')) ? $h : 'safetyguide';
switch ($h) {
	case "safetyguide":
		safetyguide();
		break;
	case "safetyguide1":
		safetyguide1();
		break;
	case "safetyguide2":
		safetyguide2();		
		break;
	case "safetyguide3":
		safetyguide3();	
		break;
	case "safetyguide4":
		safetyguide4();
		break;
	case "safetyguide5":
		safetyguide5();		
		break;
	case "safetyguide6":
		safetyguide6();		
		break;
	default:
		safetyguide();
		
}
?>
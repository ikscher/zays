<?php
/*
 * Created on 9/25/2009
 *
 * yujun
 *
 * module/video/index.php
 */
/*************************************** 逻辑层(M)/表现层(V) ****************************************/




function video_index(){
	global $user_arr;
	include MooTemplate("public/video", 'module');
}
function video_inner(){
	global $user_arr;
	include MooTemplate("public/video_hx", 'module');
}



/***************************************   控制层(C)   ****************************************/
$h = MooGetGPC('h', 'string');


switch($h){
	case'index':			
		video_index();
		break;
	case'inner':			
		video_inner();
		break;
	default :
		video_index();
}
?>
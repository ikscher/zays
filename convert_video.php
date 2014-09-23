<?php
/*
 *视频转换程序 
 */

set_time_limit(0);
require 'config.php';

require 'framwork/MooPHP.php';

$sql = "select vid,filepath,filename from web_video where status='1'";

$video_list = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);

if($video_list){
	foreach($video_list as $video){
		$sql = "update web_video set status='2' where vid='{$video['vid']}'";
		$GLOBALS['_MooClass']['MooMySQL']->query($sql);
	}
}
echo 'ok';
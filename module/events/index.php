<?php 
/*
 * Created on 8/14/2009
 *
 * Tianyong yujun
 * @Modify:2010-02-10
 * @Modifier:fang
 * module/service/index.php
 */
/***************************************一些模板用的函数******************************/

date_default_timezone_set('Asia/shanghai');


/***************************************控制层****************************************/ 
$pagesize = 10;         //服务中心信息每页显示的条目数

//include "./module/{$name}/function.php";

$back_url='index.php?'.$_SERVER['QUERY_STRING'];


$type=MooGetGPC('h','string','G');

switch ($type) {
    case "valentine":
	    require MooTemplate('public/valentine','module');
		break;

   

} 
?>

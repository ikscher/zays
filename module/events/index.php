<?php 
/*
 * Created on 8/14/2009
 *
 * Tianyong yujun
 * @Modify:2010-02-10
 * @Modifier:fang
 * module/service/index.php
 */


/***************************************控制层****************************************/ 

$type=MooGetGPC('h','string','G');

switch ($type) {
    case "nationalday":
	    require MooTemplate('public/nationalday','module');
		break;

   

} 
?>

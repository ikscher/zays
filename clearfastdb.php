<?php
 
set_time_limit(0);
$start=time();
 //note 加载框架
require 'framwork/MooPHP.php';

$userid='21683328';

$iscan = $GLOBALS ['fastdb']->get ( $userid . '_scan_space' ); //浏览的全权会员列表
$iscan = empty ( $iscan ) ? array () : json_decode ( $iscan, true );
echo '<h1>'.$userid.'浏览的全权会员列表</h1><div>';
echo $userid . '_scan_space=>'.var_export($iscan,true);
echo "</div><br/>";
echo '<h1>访问'.$userid.'的记录列表</h1><div>';
$scan_i = $GLOBALS ['fastdb']->get ( 'scan_space_' . $userid); //访问的记录列表
$scan_i = empty ( $scan_i ) ? array () : json_decode ( $scan_i, true );
echo 'scan_space_'.$userid . '=>'.var_export($scan_i,true);
echo "</div><br/>";
echo '<h1>'.$userid.'的锁</h1><div>';
$uid_scan_lock = $GLOBALS ['memcached']->get ( $userid . '_scan' );
echo $userid . '_scan=>'.$uid_scan_lock.'</div>';
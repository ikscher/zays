<?php
 
set_time_limit(0);
$start=time();
 //note ���ؿ��
require 'framwork/MooPHP.php';

$userid='21683328';

$iscan = $GLOBALS ['fastdb']->get ( $userid . '_scan_space' ); //�����ȫȨ��Ա�б�
$iscan = empty ( $iscan ) ? array () : json_decode ( $iscan, true );
echo '<h1>'.$userid.'�����ȫȨ��Ա�б�</h1><div>';
echo $userid . '_scan_space=>'.var_export($iscan,true);
echo "</div><br/>";
echo '<h1>����'.$userid.'�ļ�¼�б�</h1><div>';
$scan_i = $GLOBALS ['fastdb']->get ( 'scan_space_' . $userid); //���ʵļ�¼�б�
$scan_i = empty ( $scan_i ) ? array () : json_decode ( $scan_i, true );
echo 'scan_space_'.$userid . '=>'.var_export($scan_i,true);
echo "</div><br/>";
echo '<h1>'.$userid.'����</h1><div>';
$uid_scan_lock = $GLOBALS ['memcached']->get ( $userid . '_scan' );
echo $userid . '_scan=>'.$uid_scan_lock.'</div>';
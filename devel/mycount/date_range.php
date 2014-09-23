<?php
/**
 * 在开始导入前先设置日期范围(fromDT---toDT)
 */

//$fromDT = strtotime('2012-02-01 00:00:00');
//$toDT = strtotime('2012-02-02 00:00:00'); // 不包括2012-02-02日
$fromDT = null;
$toDT = null;
$fromDT = strtotime('2012-06-14 00:00:00');
$toDT = strtotime('2012-06-15 00:00:00'); 
if (empty($fromDT) || empty($toDT) || $fromDT > $toDT) {
    echo '未正确设置要恢复数据的日期范围';
    exit;
}

?>
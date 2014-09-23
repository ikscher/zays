<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>修补统计数据(每日汇总)</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type='text/javascript' src='http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.7.1.min.js'></script>
</head>
<body>
<?php
/**
 * 修补统计数据(每日汇总)
 * @author MIEN
 * @date 2012-02-02 16:54
 */

set_time_limit(0);

require '../../framwork/MooPHP.php';
require('date_range.php');

// 根据日期范围生成每日的dateline
$days = array();
for ($dateline = $fromDT; $dateline < $toDT; $dateline += 86400) {
    $days[] = $dateline;
}

// 查询总数
$total = count($days);

// 输出当前和总数
echo '要统计的天数'.$total;
echo '<br/>----------------------------<br/>', "\r\n";
foreach($days as $day) {
    $dt = date('Y-m-d', $day);
    echo "<a href='http://mycount.7651.com/index.php?n=main&h=reflesh&date={$dt}' target='_blank'>{$dt}</a><br/>\r\n";
}
?>

</body>
</html>
<?php
/**
 * 查看信息的脚本
 */

// 包含
require('./../config.php');
require('./../framwork/MooPHP.php');

$sql = '';
$md = date('md');

if (!empty($_POST['sql'])) {
    $sql = stripslashes($_POST['sql']);
    if (strncasecmp($sql, 'select ', 7) == 0 
        && strpos($sql, ';') === false
        && $_POST['p'] == 'r7651'.$md
    ) {
        $rows = $_MooClass['MooMySQL']->getAll($sql);
        print_r($rows);
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>查询</title>
</head>
<body>
<form name='form1' id='form1' method='post' action=''>
<textarea name='sql' id='sql' rows='15' cols='55'><?php echo $sql; ?></textarea><br/>
密码:<input type='text' name='p' value=''/><br/>
<input type='submit' name='s' value='提交'/>
</form>
<?php
if (!empty($rows)) {
    echo sprintf("<br/><b>总数:%d, 查询结果:</b><br/><br/>\n", count($rows));
    $i = 0;
    foreach ($rows as $row) {
        echo '<b>序号:' . $i . '</b><br/>', "\n";
        foreach($row as $k => $v) {
            echo $k, '-->', $v, '<br/>', "\n";
        }
        echo "<br/>\n";
        $i++;
    }
}

?>
</body>
</html>
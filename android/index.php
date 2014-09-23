
<?php

$fp=fopen("../module/andriod/shadow/download/ver.txt", "r");
$str=fread($fp, filesize("../module/andriod/shadow/download/ver.txt"));
$sha = 'hzn7651_'.$str.'.apk';
//echo $sha;exit;


$file = '../module/andriod/shadow/download/'.$sha;
$file = iconv('UTF-8', 'gbk', $file);
if (file_exists($file)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.basename($file));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    ob_clean();
    flush();
    readfile($file);
    exit;
}
else{
	$err = '下载失败';
	echo $err;
}
?>
	    
<?php
include "../function.php";

$fp=fopen("download/ver.txt", "r");
$str=fread($fp, filesize("download/ver.txt"));
fclose($fp);
echo return_data($str,true);exit;	
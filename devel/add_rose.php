<?php
/*
 * 添加鲜花
 */
require '../config.php';
require '../framwork/MooPHP.php';

$key = MooGetGPC('k','string','G');
$uid = MooGetGPC('u','string','G');
$num = MooGetGPC('n','string','G');

if($key != 'r7651'){
    exit;
}

if(empty($uid) || empty($num)){
    exit('uid or num empty!');
}

 $sql = "update web_members set rosenumber = rosenumber + {$num} where uid = {$uid}";
 $rs = $_MooClass['MooMySQL']->query($sql);

 if($rs){
    echo 'ok';    
 }

?>

<?php
/*
 * 补钻石会员记录
 */
require '../config.php';
require '../framwork/MooPHP.php';

$key = MooGetGPC('k','string','G');
$uid = MooGetGPC('u','string','G');

if($key != 'd7651'){
    exit;
}

if(empty($uid)){
    exit('uid is empty!');
}

$sql = "select username,gender,nickname,birthyear,bgtime,endtime from web_members where uid = {$uid}";
$rs = $_MooClass['MooMySQL']->getOne($sql);

if(empty($rs)){
    exit('no this uid.');
}

$sql = "REPLACE INTO web_diamond (uid ,username ,skin ,status, nickname, gender, birthyear,bgtime,endtime) VALUES ( '{$uid}', '{$rs['username']}', '".($rs['gender']==0?'cyan':'red')."', '1', '{$rs['nickname']}','{$rs['gender']}','{$rs['birthyear']}','{$rs['bgtime']}','{$rs['endtime']}')";
 $rs = $_MooClass['MooMySQL']->query($sql);

 if($rs){
    echo 'ok';    
 }

?>

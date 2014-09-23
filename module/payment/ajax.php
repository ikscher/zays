<?php
global $_MooClass,$dbTablePre;
$uid=MooGetGPC('uid','integer');
/*
if(MOOPHP_ALLOW_FASTDB){
	$server = MooFastdbGet('members','uid',$uid);
}else{
	$server=$_MooClass['MooMySQL']->getOne("SELECT `sid` FROM `{$dbTablePre}members` WHERE `uid`=".$uid);
}
*/
if($uid){
        if(MooMembersData($uid, 'sid')){
                $_MooClass['MooMySQL']->query("INSERT INTO `{$dbTablePre}custom_remark` (cid,keyword,content,awoketime,addtime) values ('{$server['sid']}','会员付款','尊敬的客服，ID为{$uid}的会员正在付款,请及时与他联系','".(time()+120)."','".time()."')");
        }else $_MooClass['MooMySQL']->query("INSERT INTO `{$dbTablePre}custom_remark` (cid,keyword,content,awoketime,addtime) values ('5','会员付款','尊敬的客服，ID为{$uid}的会员正在付款,请及时与他联系','".(time()+120)."','".time()."')");
}
?>
<?php 
/**
* 向会员发送站内信
* @param array $arr - 操作类型
* @return string 
*/
function sendusermessage($uid,$content,$s_title){
        global $_MooClass,$dbTablePre;
        $addtime=time();
        $_MooClass['MooMySQL']->query("INSERT INTO {$dbTablePre}services SET `s_uid`='$uid',`s_cid`='3',`s_title`='$s_title',`s_content`='$content',`s_time`='$addtime'");
}

?>
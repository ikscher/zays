<?php 
header('content-Type:text/html; charset=utf-8');
if(isset($_POST['uid'])){
    $filepath='uid_collect.txt';
    $str=file_get_contents($filepath)or die('uid写入文件错误');
    $arr=explode("\r\n",$str);
    foreach ($arr as $k=>$v){
        $vi=array();
        //$v = rtrim($v,"\r");
        $vi=explode(":",$v);

        if(isset($vi[1]) && $_POST['uid']==$vi[1]){

            echo  "新会员uid:".$vi[0]."    旧会员uid:  ".$vi[1];
            echo "<hr>";
            break;

        }
    }


}

?>
<form action="" method="post">
会员旧uid:<input type="text" name='uid'>
<input type="submit" value="查询">
</form>






?>

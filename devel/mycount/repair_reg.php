<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>修补统计数据(会员注册)</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type='text/javascript' src='http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.7.1.min.js'></script>
</head>
<body>
<?php
/**
 * 修补统计数据(会员注册)
 * @author MIEN
 * @date 2012-02-01 20:28
 */

set_time_limit(0);
$n = isset($_GET['n']) ? intval($_GET['n']) : 0;
$limit = 50;
$offset = $n * $limit;
require '../../framwork/MooPHP.php';
require('date_range.php');
// $fromDT = strtotime('2012-06-14 00:00:00');
// $toDT = strtotime('2012-06-15 00:00:00'); 
// if (empty($fromDT) || empty($toDT) || $fromDT > $toDT) {
    // echo '未正确设置要恢复数据的日期范围';
    // exit;
// }

//在这里使用sphinx 做改进 由于红娘的数据表结构和相亲的不一样特再次进行修改
/* $sp = searchApi("members_man members_women");
$sp->setQueryType(true);
$filters=array(0=>array('field'=>'usertype','val'=>array('1'),'exclude'=>false),1=>array('field'=>'regdate','val'=>$fromDT,'val2'=>$toDT,'exclude'=>false));
$result=$sp->getResultOfReset($filters, array('offset'=>$offset,'limit'=>$limit), array("mode" => "attr_asc","field" => "regdate"));

var_dump($total = $sp->getRs('total_found'));
var_dump($total = $sp->getIds());
var_dump($sp->GetLastError()); */
//exit;
// 查询总数
$sql = 'select count(`uid`) as cnt from web_members_search where usertype=1 and regdate>=' . $fromDT . ' and regdate<'. $toDT;
$total = $_MooClass['MooMySQL']->getOne($sql);
$total = $total['cnt'];

// 分页查询


$sql = sprintf('select s.`uid`,`gender`,`telphone`,`birthyear`,`source`,`regip`,`regdate` from web_members_search s left join web_members_base b on s.uid=b.uid where s.usertype=1 and s.regdate>=%s and s.regdate<%s limit %s,%s', $fromDT, $toDT, $offset, $limit);

$uids = $_MooClass['MooMySQL']->getAll($sql);

// print_r($uids);exit;

$count = count($uids);

// 输出当前和总数
echo ($offset + $count).'/'.$total;
echo '<br/>----------------------------<br/>', "\r\n";

// 输出数据为 javascript数组
echo '<script type="text/javascript">', "\r\n";
echo 'var m = [];', "\r\n";
foreach($uids as $m) {
    $is_phone = empty($m['telphone']) ? 0 : 1;
    echo 'm.push({';
    echo sprintf(
        "uid:%d, gender:%s, phone:'%s', birthyear:%d, md5:'', keyurl:'%s', ip:'%s', dateline:%s, is_phone:%d",
        $m['uid'], $m['gender'], $m['telphone'], $m['birthyear'], urlencode($m['source']), $m['regip'], $m['regdate'], $is_phone
    );
    echo '});', "\r\n";
}
echo '</script>', "\r\n";

?>

<div id='secs'></div>
<div id='mlist'></div>
<script type='text/javascript'>
function showMessage(msg) {
    $('#mlist').html('导入结果:' + msg + '<br/>');
}

var index = 0;
var length = m.length;
//var baseurl = 'http://www.tt.com/mycount/repair/hongniang_import.php';
var baseurl = 'http://mycount.7651.com/repair/hongniang_import.php';
var timeStamp = null;
var actionType = 0; // 1, 导入会员; 2, 查询下一页数据

var timerObservor = null;

// 返回当前的时间戳
function getTime() {
    return (new Date()).getTime(); 
}

// 导入会员
function nextImport() {
    if (index < length) {
        var user = m[index];
        var url = baseurl + '?uid=' + user.uid + '&puid=&ip=' + user.ip + '&s_cid=2&website=&keyurl=' + user.keyurl + '&dateline=' + user.dateline + '&gender=' + user.gender + '&phone=' + user.phone + '&birthyear=' + user.birthyear + '&is_phone=' + user.is_phone;
        $('#mlist').html($('#mlist').html() + '导入本页第' + (index + 1) + '/' + length + '个会员……<br/>' + url);
        setTimeout(
            function() {
                actionType = 1;
                timeStamp = getTime();
                $.getScript(url);
                index++;
            },
            100
       );
    }
    else if (length >= <?php echo $limit;?>) {
        $('#mlist').html('查询下一页会员数据……');
        setTimeout(
            function() {
                actionType = 2;
                timeStamp = getTime();
                document.location.href = '?n=<?php echo ($n + 1);?>';
            },
            1000
        );
    }
    else {
        $('#mlist').html('全部完成……');
        if (timerObservor) {
            clearInterval(timerObservor);
        }
    }
}

// 重新导入会员
function retryImport() {
    var user = m[index - 1];
    var url = baseurl + '?uid=' + user.uid + '&puid=&ip=' + user.ip + '&s_cid=2&website=&keyurl=' + user.keyurl + '&dateline=' + user.dateline + '&gender=' + user.gender + '&phone=' + user.phone + '&birthyear=' + user.birthyear + '&is_phone=' + user.is_phone + '&retry=1';
    $('#mlist').html($('#mlist').html() + '导入本页第' + index + '/' + length + '个会员(重试)……<br/>' + url);
    setTimeout(
        function() {
            actionType = 1;
            timeStamp = getTime();
            $.getScript(url);
        },
        100
    );
}

// 监视导入的方法(由于网络问题导致中断，30秒后重试)
function observe() {
    if (timeStamp == null) return;
    
    var now = getTime();
    var diff = now - timeStamp;
    
    var seconds = parseInt(diff / 1000);
    var million_seconds = diff % 1000;
    if (million_seconds < 10) {
        million_seconds = '00' + million_seconds;
    }
    else if (million_seconds < 100) {
        million_seconds = '0' + million_seconds;
    }
    
    $('#secs').html(
        seconds + '.' + million_seconds + '秒'
    );
    
    if (diff > 30000) {
        if (actionType == 1) {
            retryImport();
        }
        else {
            document.location.href = document.location.href; // 重新刷新本页
        }
    }
}

// 导入
nextImport();

// 监视导入，确保不中断
timerObservor = setInterval(observe, 200);

</script>
</body>
</html>
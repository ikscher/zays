<?php 
require '../../../framwork/MooPHP.php';
//允许的单ip列
$allow_ip = array('221.123.138.202', '218.59.80.90','60.208.184.120','124.128.18.234','221.173.219.34','127.0.0.1','60.208.187.174','60.12.204.123');
//允许访问ip地址段
$allow_ip_segment = array('10.20.20.100-10.20.20.254');  //例array('218.59.80.90-218.59.80.100','60.12.1.1-60.12.1.100')
$cur_ip = GetIP();
if(!in_array($cur_ip,$allow_ip)){
	$allow_ip_bool = false;
	foreach($allow_ip_segment as $value){
		$ip_segment_arr = explode('-', $value);
		if(count($ip_segment_arr)==2 && !empty($ip_segment_arr[0]) && !empty($ip_segment_arr[1])){
			$cur_ip_long = ip2long($cur_ip);
			if(ip2long($ip_segment_arr[0])<=$cur_ip_long && $cur_ip_long<=ip2long($ip_segment_arr[1])){
				$allow_ip_bool = true;				
			}
		}
		if($allow_ip_bool) break;
	}
	if(!$allow_ip_bool && strpos($cur_ip,'192.168') === FALSE){
	    echo 'GET OUT -- '.$cur_ip;exit;
	}
}

?>


<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>版本上传</title>

<script type='text/javascript' src='../../../public/system/js/jquery-1.6.4.min.js'></script>
</head>
<body>
    <h1>版本上传</h1>
<?php
if (!isset($_POST['pass']) || $_POST['pass'] != 'canbe#491&黑夜：mon') {
?>
    <form name='frmPass' method='post' action=''>
        请输入密码:<input name='pass' id='pass' type='text' value=''/>
        <input name='hid' type='hidden' value='you'/>
        <input name='submit' type='submit' value='提交'/>
    </form>
    <script type='text/javascript'>
    $(function() {
        $('#pass').focus();
    });
    </script>
<?php
    exit;
}
?>

<?php
	
	if(is_uploaded_file($_FILES[upfile][tmp_name])){
		$saveup = "download/";
		if(!file_exists($saveup))
    	{
        	mkdir($saveup);
       
    	}
		$upfile = $_FILES[upfile];
		//$name = "HN.apk";
		//$name = iconv('UTF-8', 'gbk', $name);
		$nameup = "download/HN.apk";
		
		$type = $upfile[type];
		$size = $upfile[size];
		$tmp_name = $upfile[tmp_name];
		$error = $upfile[error];
		if($error==0){
			move_uploaded_file($tmp_name, $nameup);
			if($_POST['num']){
				$handle = fopen("download/vision.txt", "w");
				fwrite($handle, $_POST['num']);
				fclose($handle);
				echo "上传成功！目前服务器版本号已更新至".$_POST['num']."版本";
			}
			else{
				echo "上传成功！最新版本内容已更新，但版本号保持不变";
			}
			
			
		}
		
		else{
			echo "上传失败,请检查文件大小。";
		}
	}
	else{
		if($_POST['hid'] != 'you'){
			echo '没有文件上传！';
		}
	}
?>

<form action='' enctype="multipart/form-data" method = "post" name="upform">
上传新版本:<input name = "upfile" type="file"><br />
请输入版本号:<input name='num' type='text' value=''/>
请输入密码:<input name='pass' type='text' value=''/>
<input type = "submit" value = "上传该版本"><br/>
</form>
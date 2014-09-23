<!DOCTYPE html >
<html>
<head>
<title>请更新您的浏览器</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body>
  <div style="margin:0 auto;width:960px;">
      亲，您的浏览器版本Internet  Explore 6！！！
	  
	  我们不支持IE6，您不应该使用这么旧的浏览器，它已经完全被淘汰！
	  
	  点击下载IE8浏览器，您将会有更好的网上浏览体验！~~~
	    <?php 
		    if(!empty($_POST['download'])) {
		       
				$file = "/download/IE8-WindowsXP-x86-CHS.exe";
			 
				$filename = basename($file);
			 
				header("Content-type: application/octet-stream");
			 
				//处理中文文件名
				$ua = $_SERVER["HTTP_USER_AGENT"];
				$encoded_filename = rawurlencode($filename);
				if (preg_match("/MSIE/", $ua)) {
				 header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
				} else if (preg_match("/Firefox/", $ua)) {
				 header("Content-Disposition: attachment; filename*=\"utf8''" . $filename . '"');
				} else {
				 header('Content-Disposition: attachment; filename="' . $filename . '"');
				}
			 
				//让Xsendfile发送文件
				header("X-Accel-Redirect: $file");
			}
		?>
	  <form name="form" action="ie6.php" method="post">
	      <input type="submit" value="下载IE新版" />
	       <input type='hidden' name="download" value="yes" />
	  </form>
	  
	  
	
  </div>
</body>

</html>
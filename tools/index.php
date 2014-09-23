<?php
$config_file = glob("confile/*.xml");
$show_arr    = array();
if (!empty($config_file)){
	foreach ($config_file as $k=>$file){
		$xml = simplexml_load_file($file);
		$show_arr[$k]['config_name'] = (string)$xml->config_name;
		$show_arr[$k]['file']        = $file;
		$show_arr[$k]['content']     = file_get_contents($file); 
	}
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>数据迁移工具</title>
</head>
<body>
<?php 
if (!empty($show_arr)){
	foreach ($show_arr as $k=>$v){
?>
<div style="display:block">

<span>配置文件<?php echo $k+1,":";?></span><br/>
<div style="margin-left:80px;">
描述:<?php echo $v['config_name'];?><br/>
文件名:<?php echo $v['file'];?><br/>
<form action="import_db.php"  method="post">
从多少条开始插入:<input type='text' name='start' />
<input type="hidden" name="xml_file[]" value="<?php echo $v['file'];?>" />
<input type='submit' name="submit" value='导入'／>
</form>
</div>

</div>
<br/>
<?php
	}
}
?>
<div style="margin-left:80px;">
<form action="import_db.php" method="post">
<?php 
if (!empty($show_arr)){
	foreach ($show_arr as $k=>$v){
?>
<input type="hidden" name="xml_file[]" value="<?php echo $v['file'];?>" />
<?php
	}
}
?>
<input type='submit' name="submit" value='全部导入'／>
<input type='button' onclick="javascript:location.href='tool2.php'" name="button" value='生成配置文件'／>
</form>
</div>
</body>
</html>
<!doctype html>
<html>

<head></head>
<html>
<body>
<form action="md5.php" method="get">

<input type="text" name="time"  />
<input type="button" value="submit" />
</form>
<?php 
   $time = isset($_GET['time'])?$_GET['time']:0;
   echo strtotime($time);
   ?>
   </body>
   </html>
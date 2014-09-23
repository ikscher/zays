<?php
   var_dump(get_extension_funcs("iconv"));
   
   echo iconv('utf-8','gbk','高级会员');
?>
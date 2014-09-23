<?php 
/*
 * Created on 2009-10-26
 *
 * To change the template for this generated file go to*
 * Window - Preferences - PHPeclipse - PHP - Code Templates
  
 * 作者:尤莹煌
 */
/*************************************** 逻辑层(M)/表现层(V) ****************************************/ 
include "module/payment/config.php";
include "module/payment/function.php";





switch ($_GET['h']) {	

 case "reurl":
  /* 支付方式代码 */
  $code = !empty($_REQUEST['code']) ? trim($_REQUEST['code']) : '';
  
  /* 参数是否为空 */
  if (empty($code))
   {
     MooMessage("参数不正确","index.php");
   }
  else{
    if(!in_array($code,$pay_sty)){
	  
	   MooMessage("没有该支付方式","index.php");
	
	}
	else{
	   $plugin_file ="module/payment/".$code.".php";
	
	
	} 
      if (file_exists($plugin_file))
        {
            /* 根据支付方式代码创建支付类的对象并调用其响应操作方法 */
            include_once($plugin_file);
                
			//echo 	$plugin_file;
			
			//exit;
            $paym = new $code();
            
		
			
			if($paym->respond()) 
			  {
			  
			  
                  if($_GET[at]==1){			  
			           MooMessage("支付成功","index.php");
			      }
				  else {
				   
				    echo 'success';
				  }
			  
			  }
			else {
			    MooMessage("操作失败请联系客服","index.php");
			}
        
		
		}
     else {
	 
	   MooMessage("操作失败请联系客服","index.php");
	 
	 }
  
  
  
  }
 
 
 break;
 default:
       MooMessage("网络异常","index.php");
 break;
}
?>

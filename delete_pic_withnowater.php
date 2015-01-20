<?php
    //删除图片目录下 带nowater的文件名的脚本
	set_time_limit(0);
    //$dir = "data/upload/images/photo/2014";
	
	$dir='/wwwroot/zays/data/upload/images/photo/2014/12';
    
	// Open a known directory, and proceed to read its contents   
	

    
    /******
    *  $dh:由opendir()打开的句柄
	*  $dir:当前要操作的文件目录
	*/	
	
    function getRecursiveFile($dir){
		if (is_dir($dir)) {   
		   if ($dh = opendir($dir)) {   
				while (($file = readdir($dh)) !== false) {   
				    $dir_=$dir;
					if ($file!="." && $file!="..") {   
						$dir_.= '/'.$file;
						//echo $dir_.'<br/>';
						ob_clean();
						if(preg_match('/nowater/',$dir_,$matches)) {  
						    unlink($dir_);
						    echo "正在处理：".$dir_;
                            usleep(1000);							
						    
							//echo $dir_.'<br/>';
						}
                        getRecursiveFile($dir_);						
					}   
				}  
			    closedir($dh); 
            				
		    }
        }	
        
	}
	
	
	getRecursiveFile($dir);
	
	
	echo "处理结束!";
	
	
	
	
	
    //服务器不支持glob操作
	/* function scandir_through($dir)
	{
		$items = glob($dir . '/*');
        $picItems=array();
		for ($i = 0; $i < count($items); $i++) {
			if (is_dir($items[$i])) {
				$add = glob($items[$i] . '/*');
				$items = array_merge($items, $add);
		         foreach($add as $k=>$v){
					 //echo $v;echo '<br>';
					 if(!is_dir($v)) {
						 if (preg_match('/nowater/',$v,$matches)) array_push($picItems,$v);
					 }
				 }
				 
			}
		}
        return $picItems;
	}
	var_dump(scandir_through($dir)); */

?>
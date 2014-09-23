<?php
    set_time_limit(0);
	$start=time();
	$sumtime=0;
	 //note 加载框架
	require 'framwork/MooPHP.php';
	require("framwork/libraries/class.phpmailer.php");

	//note mail服务器地址 配置
	$mailHost = '127.0.0.1';
	//mail 端口号
	$mailPort='25';
	//note 邮件账号
	$mailUser = 'server@7651.com';
	//note 邮件密码
	$mailPasswd = 'my66vvcom';
	//note 发送者邮箱
	$mailSenderMail = 'server@7651.com';
	//note 邮件发送者
	$mailSenderName = '红娘网';
	
	
	$result=array();
	//note 模板页URL
	//2版
	$MailTemplate_boyMail = 'public/system/mailtamp/boyMail2.html'; 
	$MailTemplate_girlMail = 'public/system/mailtamp/girlMail2.html';
	$type=2;
	//1版
	//$MailTemplate_boyMail = 'public/system/mailtamp/boyMail1.html';
	//$MailTemplate_girlMail = 'public/system/mailtamp/girlMail1.html';
	//$type=1;
	
	$charset = 'UTF-8';
	
	$mail_list = array( "robot@7651.com", "robot4@7651.com", "robot5@7651.com", "robot6@7651.com", 
					"robot7@7651.com", "robot8@7651.com", "robot9@7651.com" ,"robot10@7651.com" ,"robot11@7651.com","robot12@7651.com", 
					"robot13@7651.com", "robot14@7651.com", "robot15@7651.com", "robot16@7651.com", "robot17@7651.com", "robot18@7651.com", 
					"robot19@7651.com", "robot20@7651.com", "robot210@7651.com", "robot22@7651.com", "robot23@7651.com", "robot24@7651.com",
					"robot25@7651.com", "robot26@7651.com", "robot27@7651.com", "robot28@7651.com", "robot29@7651.com", "robot30@7651.com",
					"robot31@7651.com", "robot32@7651.com", "robot33@7651.com", "robot34@7651.com", "robot35@7651.com", "robot36@7651.com",
					"robot37@7651.com", "robot38@7651.com", "robot39@7651.com", "robot40@7651.com"
    );
	
	$key = array_rand($mail_list, 1);
    $mailSenderMail = $mail_list[$key];
	
	
	$time = $timestamp-3*3600;
	
	//$page=MooGetGPC('page','integer','G');
	$page=$memcached->get("sendmailpage");
	$page=(empty($page))?1:$page;
    
	$offset = ($page -1 ) * 10;
	
	($offset>0 && $offset%5000==0) && exit("exceed the current day max sending");
	
	$sql="select s.nickname,s.uid,s.username,s.gender from web_members_search s left join web_members_login l on s.uid=l.uid where s.usertype=1 and s.s_cid = 40 and l.lastvisit <= '1347752434' and s.uid>=(select s.uid from web_members_search s left join web_members_login l on s.uid=l.uid where s.usertype=1 and s.s_cid = 40 and l.lastvisit <= '1347752434' order by s.uid limit {$offset},1 ) limit 10";
	//$sql = "select s.nickname,s.uid,s.username,s.gender from web_members_search  s left join web_members_login l on s.uid=l.uid  where s.usertype=1 and s.s_cid = 40   and l.lastvisit <= '$time' and s.uid>(select uid from web_members_search order by uid  limit {$offset},1 ) limit 10";
  
	$result=$_MooClass['MooMySQL']->getAll($sql);


	/*
	$result=array( array("uid"=>"25698742","username"=>"huangwentao@7651.com","gender"=>1),
	                array("uid"=>"45397312","username"=>"45397312@qq.com","gender"=>0),
	                array("uid"=>"23023452","username"=>"ikscher@163.com","gender"=>0)
	                
					
			);
	
	*/
	
	
	if(empty($result)) exit('执行结束！');
 
	$ToSubject="红娘网";
	$ToAddress='';
	

	
	$mail = new PHPMailer(); //建立邮件发送类
	$mail->IsSMTP(); // 使用SMTP方式发送
	$mail->Host = $mailHost; // 您的企业邮局域名
	$mail->Port = $mailPort;  
	//$mail->SMTPAuth = true; // 启用SMTP验证功能
	//$mail->SMTPSecure = "ssl";
	$mail->Username = $mailSenderMail; // 邮局用户名(请填写完整的email地址)
	$mail->Password = $mailPasswd; // 邮局密码
	$mail->From = $mailSenderMail; //邮件发送者email地址
	$mail->CharSet = "utf-8"; 
	$mail->Encoding = "base64"; 
	$mail->FromName = $ToSubject;
	
	//$mail->AddReplyTo("", "");
	//$mail->AddAttachment("/var/tmp/file.tar.gz"); // 添加附件
	$mail->IsHTML(true); // set email format to HTML //是否使用HTML格式
	$mail->AltBody = ""; //附加信息，可以省略
	
	// 自动换行
	$mail->WordWrap   = 70;
	// 这里指定字符集！如果是utf-8则将gb2312修改为utf-8
	$mail->CharSet = 'utf-8';
	$mail->Subject = $ToSubject;
	
			
	$LogFileName=dirname(__FILE__);
    $LogFileName=$LogFileName."/error/tmp/";
	
	foreach($result as $v){
        
		//载入模板
		if($v['gender']==1){
		  $body = ReadFileTemplate($MailTemplate_boyMail);
		}else{
		  $body = ReadFileTemplate($MailTemplate_girlMail);
		}
		
		$body = str_replace('\\','',$body);
		
		
		//邮件时间替换
		//date_default_timezone_set ('Asia/Shanghai');
		//$Time = date('Y-m-d H:i:s');
		
		//$body = str_replace("#DATETIME#",$Time,$body);
		//note 邮件正文替换
		//$body = str_replace("#BODY#",$ToBody,$body);
		//note 模板几个内部图片地址
		$body = str_replace("#siteurl#",'http://'.$_SERVER['HTTP_HOST'].'/',$body);
		$body = preg_replace("/\\\\/",'',$body);
		
	    $mail->Body = $body;
	
	
	    $ToAddress=$v['username'];

		
	    if (!$mail->ValidateAddress($ToAddress)) {
		   continue;
	     }
		try {
		
			$mail->ClearAddresses();
			$mail->AddAddress($ToAddress, "");
    
			if($mail->Send()){
			    
				writelog($LogFileName,$v['uid']);
			    //continue;
			} else {
			    SendMail($ToAddress,$ToSubject);
				writelog($LogFileName,$v['uid']);
			}
		} catch (Exception  $e) {
		    writelog($LogFileName,$v['uid'],false);
		}
	
	}
	
	/**
	   function:邮件发送
	   argument:  $ToAddress,邮件地址 ,array
	              $ToSubject,邮件标题 ，string
	   
	*/
	function SendMail($ToAddress,$ToSubject,$uid=0){
		global $body;
		
		//$body="";
		//note ***********加载模板************
		
		
	    //$body = ReadFileTemplate($MailTemplate);
		
		//$body = preg_replace("/\\\\/",'',$body);
		//邮件时间替换
		//date_default_timezone_set ('Asia/Shanghai');
		//$Time = date('Y-m-d H:i:s');
		//$body = str_replace("#DATETIME#",$Time,$body);
		//note 邮件正文替换
		//$body = str_replace("#BODY#",$ToBody,$body);
		//note 模板几个内部图片地址
		//$body = str_replace("#siteurl#",'http://'.$_SERVER['HTTP_HOST'].'/',$body);
		
       
		//$ToAddress=explode(',',$ToAddress);
		//foreach($ToAddress as $email){
			$param = array();
			$param["registration_date"] = date("Y-m-d H:i:s");
			$param["uid"] = $uid;
			$param["mail"] = $ToAddress;
			$param["subject"] = addslashes($ToSubject);
			$param["content"] = addslashes($body);
			
			inserttable("mail_queue", $param);
		//}
		
		//return true;
	}
	
	
	/**
	* 读文件
	* @param string $file - 需要读取的文件，系统的绝对路径加文件名
	* @param boolean $exit - 不能读入是否中断程序，默认为中断
	* @return boolean 返回文件的具体数据
	*/
	function ReadFileTemplate($file, $exit = TRUE) {
		if(!$fp = fopen($file, 'rb')) {
			if($exit) {
				exit('MooPHP File :<br>'.$file.'<br>Have no access to write!');
			} else {
				return false;
			}
		} else {
			$data = fread($fp,filesize($file));
			fclose($fp);
			return $data;
		}
	}
	
	
	/**
	*写记录日志文件
	*/
	
	function writelog($path,$uid,$flag=true){
	    global $timestamp,$type;
		$date=date('Y-m-d',$timestamp);

		
		$TxtFileName=str_replace("\\","/",$path);

		if (!file_exists($TxtFileName)){
			if (mkdir($TxtFileName,0777,true)) {

			}else{
			   exit('fail!');
			}
		}

		$TxtFileName .="sendMail".$date.".txt";

		//以读写方式打写指定文件，如果文件不存则创建

		if( ($TxtRes=fopen($TxtFileName,"a+")) == FALSE){

			echo("创建可写文件：".$TxtFileName."失败");  

			exit();
	    }

		if($flag){
		   $StrContents ="\n会员ID:".$uid.",发送时间：".$timestamp.",模板类型:".$type;//要 写进文件的内容
		}else{
		   $StrContents ="\n会员ID:".$uid.",发送失败！,模板类型:".$type;//要 写进文件的内容
		}
		
		fwrite($TxtRes,$StrContents);

	    fclose ($TxtRes); //关闭指针
	
	}
	
	


	++$page;
	
	$memcached->set("sendmailpage",$page);
	
	$end=time();
    
	$sumtime=MooGetGPC('sumtime','integer','G');
    $executetime=$end - $start;
	$sumtime +=$executetime;
	
	echo '执行成功，共花费'.$sumtime.'秒，本次执行'.$executetime.'秒';
	
	echo "<script>window.location.href='SendMail2012.php?page={$page}&sumtime={$sumtime}';</script>";
	

	

	

?>
	
	
<?PHP
/**
 * 清空session
 *
 */
function clearsession(){
	global $_MooClass,$dbTablePre;
	$user = userinfo();
	$uid = $user['uid'];
	//$_MooClass['MooMySQL']->query("DELETE FROM `{$dbTablePre}membersession` WHERE `uid` = '$uid'");	
}
/**
 * 清空cookie
 */
function clearcookie() {
	// 清空session
	clearsession();
	
	ob_end_clean();
	setcookie('hlw_auth', '', -86400 * 365);
	setcookie('hlw_uid', '', -86400 * 365);
}
/**
 * 提示信息
 * 描述：例如用户在登录的用户名为空就进入一个提示页面
 * @param string $str 提示页面显示的内容
 * @param string $url 提示信息返回网址
 */
function show_message($str,$url) {
	if(!empty($str)) {
		$notice = $str;
	}else {
		$notice = "没有提示信息";
	}
	//notice 如果url为空返回首页
	if(empty($url)) {
		$notice = "返回地址为空";
		$url = "index.php";	
	}
	require MooTemplate("public/index_showmessage", 'module');
	exit;
}

function index_img ($province,$num){
	$dates = date('Ymd');
	$arr = array(
		1=>array('0'=>'全部','1'=>'module/index/templates/default/images/platinum.jpg','2'=>'index.php?n=payment&h=platinum','3'=>' 升级铂金会员，享受至尊服务','4'=>'20990909'),
		
		9=>array('0'=>'全部','1'=>'module/index/templates/default/images/miai.jpg','2'=>'index.php?n=activity&h=activity','3'=>' 真爱一生网电视 线下 相亲活动','4'=>'20990909'),
		
		11=>array('0'=>'全部','1'=>'module/index/templates/default/images/lovestory.jpg','2'=>'index.php?n=story&h=list','3'=>' 真爱一生网成功故事','4'=>'20990909'),
		2=>array('0'=>'全部','1'=>'module/index/templates/default/images/diamond.jpg','2'=>'index.php?n=payment&h=diamond','3'=>' 开通钻石会员，八大专享服务','4'=>'20990909'),
		10=>array('0'=>'全部','1'=>'module/index/templates/default/images/lovetest.jpg','2'=>'index.php?n=lovestyle','3'=>' 真爱一生网爱情测试','4'=>'20990909'),
	   12=>array('0'=>'全部','1'=>'module/index/templates/default/images/sevenAnniversary.jpg','2'=>'index.php?n=activity&h=sevenanniversary','3'=>'真爱一生网七周年    真情大回馈','4'=>'20110909'),
     15=>array('0'=>'江苏','1'=>'module/index/templates/default/images/spa_1.jpg','2'=>'index.php?n=activity&h=spa','3'=>'恋上温泉，恋上爱','4'=>'20111120')
		
		
		);
	$array = array();
	$other = array();
	$number = 0; 
	
	foreach($arr as $key => $val){
		if(strstr($val['0'],$province)){
		
			if($num>=$number){
				if($dates<=$val['4']){
						$number++;
					$array[$number] = $val;
				}
			}
		}else{
			if($dates<=$val['4']){
				$other[] = $val;
			}
		}	
	}
	
	$n = $num-$number;
	for($i=0;$i<$n;$i++){
		$j=$number+$i+1;
		$array[$j] = $other[$i];
	}

	return $array;
}

//首页小图片

function small_img($province,$num,$province_key){
	$dates = date('Ymd');
	
	$url1=$url2=$url3="";
	switch ($province_key) {
		case "10106000" :
			$provinc="anhui/";
			break;
		case "10127000" :
			$provinc="sichuan/";
			break;
		case "10115000" :
			$provinc="hubei/";
			break;
		case "10118000" :
			$provinc="jiangsu/";
			break;
		case "10124000" :
			$provinc="shandong/";
			break;
		case "10103000" :
		   $provinc="shanghai/";
		   break;
		case "10125000" :
			$provinc="shanxi/";
		    break;
		default :	
			$provinc="";
     }
	
	$url="index.php?n=activity&h=activity";
      $arr = array(
		1=>array('0'=>'全部','1'=>'module/index/templates/default/images/'.$provinc.'xt_2.jpg','2'=>$url,'3'=>'20990909'),	
		2=>array('0'=>'全部','1'=>'module/index/templates/default/images/'.$provinc.'xt_1.jpg','2'=>$url,'3'=>'20990909'),
		3=>array('0'=>'全部','1'=>'module/index/templates/default/images/'.$provinc.'xt_3.jpg','2'=>$url,'3'=>'20990909'),
		
		
		15=>array('0'=>'江苏','1'=>'module/index/templates/default/images/spa.jpg','2'=>'index.php?n=activity&h=spa','3'=>'20111120')
		
		);
	$array = array();
	$other = array();
	$number = 0; 
	
	foreach($arr as $key => $val){
		if(strstr($val['0'],$province)){
			
			if($num>=$number){
				if($dates<=$val['3']){
					$number++;
					$array[$number] = $val;
				}
			}
		}else{
			if($dates<=$val['3']){
				$other[] = $val;
			}
			
		}	
	}
	
	$n = $num-$number;
	for($i=0;$i<$n;$i++){
		$j=$number+$i+1;
			$array[$j] = $other[$i];
	}
	
	return $array;
}

function test($province){
	
	$dates = date('Ymd');
	$arr = array(
		//4=>array('0'=>'安徽','1'=>'尊敬的会员：','2'=>'您好！','3'=>'20111030','4'=>"2011年10月15日14：00，真爱一生网再度携手湖北卫视在合肥望湖美家居（宿松路与南二环东流路交口），举办一场以“弱水三千，我只取一瓢”为主题的大型线下相亲活动及湖北卫视《相亲齐上阵》电视节目海选活动，活动费用为100元/位。您可以登录<a href = 'http://www.zhenaiyisheng.cc'>www.zhenaiyisheng.cc</a>活动专区，或致电您的专线真爱一生，或拨打400-678-0405客服热线咨询活动组了解详情。",'5'=>"秋天，一段情感在光影的色彩变幻中疯长。此时，如果窗外有风，就有了思念的理由。因为思念会随风，飘到您的身边。真爱一生网希望通过此平台，让单身的您邂逅一段佳缘，真爱一生网活动组全体工作人员期待您能参与并收获自己的爱情。",'6'=>'2011年10月11日','7'=>'真爱一生网活动组','8'=>'合肥最新活动','9'=>'360'),
		//5=>array('0'=>'湖北','1'=>'尊敬的会员：','2'=>'您好！','3'=>'20111020','4'=>"2011年10月23日，真爱一生网将在武汉举办一场以“牵手的那一刻，爱已永恒”为主题的大型线下相亲活动，活动费用为100元/位。您可以登录<a href = 'http://www.zhenaiyisheng.cc'>www.zhenaiyisheng.cc</a>活动专区，或致电您的专线真爱一生，或拨打400-678-0405客服热线咨询活动组了解详情。",'5'=>"",'6'=>'2011年10月16日','7'=>'真爱一生网活动组','8'=>'武汉最新活动','9'=>'216'),
		6=>array('0'=>'全部','1'=>'尊敬的会员：','2'=>'您好！','3'=>'20111026','4'=>"2011年10月26日，真爱一生网再度携手四川电视台在成都举办一场“缘分，只有今生没有来世”为主题的大型线下相亲活动及《闻香识女人》电视节目海选活动，活动费用为100元/位。您可以登录<a href = 'http://www.zhenaiyisheng.cc'>www.zhenaiyisheng.cc</a>活动专区，或致电您的专线真爱一生，或拨打400-678-0405客服热线咨询活动组了解详情。",'5'=>"",'6'=>'2011年10月17日','7'=>'真爱一生网活动组','8'=>'成都最新活动','9'=>'235'),
		7=>array('0'=>'全部','1'=>'尊敬的会员：','2'=>'您好！','3'=>'20120121','4'=>"真爱一生网7岁了！回馈会员大升级！免费相亲大派对，iphone4s，ipad2，七重豪礼等您来拿！         <a href='index.php?n=activity&h=sevenanniversary'> 详情请点击</a> ",'5'=>"",'6'=>'2011年12月24日','7'=>'真爱一生网','8'=>'免费大派对，豪礼等您拿','9'=>'229'),
		
		);
	$array = array();
	$other = array();
	$number = 0; 
	$num = 1;
	foreach($arr as $key => $val){
		if(strstr($val['0'],$province)){
			if($num>=$number){
				if($dates<=$val['3']){
					$number++;
					$array[$number] = $val;
				}
			}
		}else{
			if($dates<=$val['3']){
				$other[] = $val;
			}
			
		}	
	}
	$n = $num-$number;
	for($i=0;$i<$n;$i++){
		$j=$number+$i+1;
			$array[$j] = isset($other[$i]) ? $other[$i] : null;
	}
	
	
	return $array[1];
}

?>
<?PHP
global $isdelcond,$isselectarea,$isdispmore,$isresult; //查询是否选择了条件 ,是否选择了地区,是否显示更多查询结果,未降条件查询是否有结果
$isdelcond=false;$isselectarea=false;$isdispmore=true;$isresult=true;

//会员择偶条件
function choice_user($id) {
	global $_MooClass,$dbTablePre;
	$choice  = MooGetData('members_choice', 'uid', $id);
	$introduce = MooGetData('members_introduce', 'uid', $id);
	$user = array_merge($choice, $introduce);
	return $user;
}


function user_login_isRight($click_login, $loginname, $loginpwd) {

	if ($click_login == 1) {
		$_SESSION['session_login'] = 0;
		$_SESSION['session_salesid'] = 0;
		$_SESSION['session_salesname'] ='';
		$_SESSION['session_accountno'] ='';
		$_SESSION['session_loginname'] ='';
		$_SESSION['session_salesarea'] ='';
		//个人状态信息		
		//没有填写完整		
		if (empty ($loginname) or empty ($loginpwd)) {
			return -3;
		}
		
		if( ($loginname!="1")||($loginpwd!="1")  ){
		return -2;
		}
		/*$sql = "select loginPwd,isStop,isDeleted from AuthSales  where loginName='".$loginname."'";
		$result = site_get_array($sql);
		
		//没有这个会员
		if ($result == 0) {
			return -2;
		}
		//密码不对
		if ($result[0]['loginPwd'] != md5($loginpwd)) {
			return -1;
		}
		//被冻结
		if($result[0]['isStop']==1){
			return -6;
		}
		//被删除
		if($result[0]['isDeleted']==1){
			return -7;
		}*/
		return 1;

	}
}



function user_login_readSession($loginname_input) {
	
	/*if ($loginname_input != '')
		$loginname = $loginname_input;
	$sql = "select * from AuthSales where loginName='".$loginname."' limit 1";
	$result = site_get_array($sql);
	if ($result != 0) { //生成session
		$_SESSION['session_login'] = 1;
		$_SESSION['session_salesid'] = $result[0]['salesId'];
		$_SESSION['session_salesname'] = $result[0]['salesName'];
		$_SESSION['session_accountno'] = $result[0]['accountNo'];
		$_SESSION['session_loginname'] = $result[0]['loginName'];
		$_SESSION['session_salesarea'] = $result[0]['salesArea'];
		return 1;
	} else {
		return -4;
	}*/
	$_SESSION['session_login'] = 1;
	$_SESSION['session_loginname'] =$loginname_input;
	return 1;
}

//note 解密JS加密的内容	
function js_unescape($str){
         $ret = '';
         $len = strlen($str);
         for ($i = 0; $i < $len; $i++){
                 if ($str[$i] == '%' && $str[$i+1] == 'u'){
                         $val = hexdec(substr($str, $i+2, 4));
                         if ($val < 0x7f) $ret .= chr($val);
                         else if($val < 0x800) $ret .= chr(0xc0|($val>>6)).chr(0x80|($val&0x3f));
                         else $ret .= chr(0xe0|($val>>12)).chr(0x80|(($val>>6)&0x3f)).chr(0x80|($val&0x3f));
                         $i += 5;
                 }
                 else if ($str[$i] == '%')
                 {
                         $ret .= urldecode(substr($str, $i, 3));
                         $i += 2;
                 }
                 else $ret .= $str[$i];
         }
         return $ret;
}

/*
 *分页
 *@page 当前页
 *@allpage 总页
 *@url 跳转地址
 *@html return
 */
function pagebar($page,$allpage,$url,$html=''){
	if($page!=1){
		$html .= "<a href='".$url."&page=1' class='same-city-button'>首页</a>";
		$html .= "<a href='".$url."&page=".($page-1)."' class='same-city-button'>上一页</a>";
	}
	
	if($page!=$allpage){
		$html .= "<a href='".$url."&page=".($page+1)."' class='same-city-button'>下一页</a>";
		$html .= "<a href='".$url."&page=".$allpage."' class='same-city-button'>尾页</a>";
	}
	return $html;
}

/* 计算字符串的长度(包括中英数字混合情况)        */
function count_string_len($str) {
    //return (strlen($str)+mb_strlen($str,'utf-8'))/2; //开启了php_mbstring.dll扩展
    $name_len = strlen ( $str );
    $temp_len = 0;
    for($i = 0; $i < $name_len;) {
        if (strpos ( 'abcdefghijklmnopqrstvuwxyz0123456789', $str [$i] ) === false) {
            $i = $i + 3;
            $temp_len += 2;
        } else {
            $i = $i + 1;
            $temp_len += 1;
        }
    }
    return $temp_len;
}

function getLocalArea(){
    include_once("./module/crontab/crontab_config.php");
    $cur_ip = GetIP();
    MooPlugins('ipdata');
    $ip_arr = convertIp($cur_ip);

    //得到省份对应的数值，查库
            
    /*foreach($provice_list as $key => $val){
       if(strstr($ip_arr,$val)){
          $province = $key;
          break;
       }
    }*/
            
    //得到市对应的城市代号
    $city = '';
    foreach($city_list as $key =>$val){
    	if($val){
	       if(strstr($ip_arr,$val)){
	         $city = $key;
	         break;
	       }
    	}
    }

    return $city;
}

/**
 * 
 * 把传过来的搜索语言参数转换成搜索想要的值
 * @param array $arr array(1,2,3,4,5,6);
 */
function languageParse($arr){
	$value = 0;
	if (empty($arr)) return 0;
	foreach ($arr as $v){
		$v     = pow(2,$v-1);
		$value = $value|$v;
	}
	return $value;
}

/**
 * 获取在线用户的id
 */
function MooUserOnlineArea($province=0, $city=0){
	global $memcached;

	$count_key = MEMBERS_ONLINE_COUNT;
	$key_pre = MEMBERS_ONLINE_SINGLE;

	$uids = array();
	if($count_uids = $memcached->get($count_key)){
		$count_uids_arr = explode(',', $count_uids);
		$count_uids = '';
		foreach($count_uids_arr as $uid){
			if($area = $memcached->get($key_pre.$uid)){
				if($city && strpos($area, $city)!==FALSE)
					$uids[$city][] = $uid;
				elseif ($province && strpbrk($area, $province)!==FALSE)
					$uids[$province][] = $uid;
					
				$count_uids .= ','.$uids;
			}			
		}
		
		$count_uids = ltrim($count_uids, ',');
		$memcached->replace($count_key, $count_uids);
	}
	return $uids;	
}

/**
 * 按照标签查询
 * @param $sex 
 * @param $sortway
 * @param $cond
 */
function SearchByTag($sex,$sortway,$cond,$offset,$pagesize){
	if(!is_array($cond) || empty($cond)) $cond = array();
	
	$cond[] = array('is_lock','1',false);
	$cond[] = array('images_ischeck','1',false);
	
	$sortway = intval($sortway);
	if($sortway == '1'){
		//note 默认排序
		$sort = 'city desc,s_cid asc,pic_num desc,city_star desc,images_ischeck desc';
	}elseif($sortway == '2'){
		//note 最新注册的排序
		$sort = 'city desc,s_cid asc,pic_num desc,regdate desc,images_ischeck desc';
	}elseif($sortway == '3'){
		//note 按照诚信等级排序
		$sort = 'city desc,s_cid asc,pic_num desc,certification desc,images_ischeck desc';
	}else{
		//note 默认排序
		$sort = 'city desc,s_cid asc,pic_num desc,city_star desc,images_ischeck desc';
	}
	$index = 'members_man';
	if($sex == '1') $index = 'members_women';
	$cl = searchApi($index);
	$rs = $cl -> getResultOfReset($cond,array($offset,$pagesize),$sort);
	$data = array();
	if(isset($rs['total_found'])) $data['total_found'] = $rs['total_found'];
	if(isset($rs['matches']) && !empty($rs['matches'])) $data['ids'] = $cl -> getIds();
	
	
	//推荐会员信息
	$cond[] = array('is_vote','1',false);
	$rs2 = $cl -> getResultOfReset($cond,array(6),$sort);
	if(isset($rs2['total']) && $rs2['total']>0 ) $data['ids2'] = $cl -> getIds();
	
	return $data;
}



/**
   funciton :两个数组元素分隔插入
   argument $a array;
   argument $b array;
   
   return : array;

*/


function insertArray($a,$b){
      if ((!is_array($a)) &&　(!is_array($b))) {

	     return array();
	  }
      
      if(empty($a) && !empty($b)){
	     if(is_array($b)){
		    return $b;
		  }else{
		     return array();
		  }
	  }elseif (!empty($a) && empty($b)){
	       if(is_array($a)){
		    return $a;
		  }else{
		     return array();
		  }
	  }elseif(empty($a) && empty($b)){
     	  return array();
	  }
	  
	  

	  $alen=count($a);
	  $blen=count($b);
	  
	 
	  $a=array_values($a);
	  $b=array_values($b);
	  $arr=$arrM=array();
	  if ($alen>$blen){
	      for($i=0;$i<$blen;$i++){
		     $arr[]=$a[$i];
			 $arr[]=$b[$i];
		  }
	      $arrM=array_merge($arr,array_slice($a,$blen,$alen - $blen));
	   }else{
	       for($i=0;$i<$alen;$i++){
		     $arr[]=$a[$i];
			 $arr[]=$b[$i];
		  }
	      $arrM=array_merge($arr,array_slice($b,$alen,$blen - $alen));
	   
	   }
	   
	   return $arrM;
  
}

/**
*      功能：从一个大的数组中 删除 在小的数组 中 出现过的 元素
*     argument:$a ，$b  类型： array     
*      return array;	  
*
*/

function substractArr($a,$b){

  if(!is_array($a) || !is_array($b)) return array();
  
  if(count($a)<count($b)) {
     $tmp=array();
	 $tmp=$b;
	 $b=$a;
	 $a=$tmp;
  }

  if (empty($a) || !is_array($a)) return array();
  
  foreach($a as $k=>$v){
	 
	 foreach($b as $val){

		if ($v==$val){
		  unset($a[$k]);
		}
	 }
  
  }
  return $a;

}


/**
 * sphinx search base function
 * sphinx查询基础函数
 * 基础、快速、高级查询用
 * @author likefei
 * 
 * @param $index
 * @param $cond
 * @param $sort
 * @param $page
 * @param $pagesize
 * @param $code
 * @param $offset
 * @param $isSelectArea 是否选择了地区
 * @param $isDiapMore 是否显示更多信息     当用户选择了地区之后才有效
 * @return array or false
 */
 

 function sphinx_search($index,$cond,$sort,$page,$pagesize,$code=0,$offset=null){
	global $memcached,$user_arr,$isselectarea,$isdispmore;
	
	//普通用户||真爱一生||未登录
	/* if(!isset($user_arr['s_cid']) || $user_arr['s_cid'] >= 40 || $user_arr['uid']=='21785244'){

		foreach($cond as $k=>$v){
			if('usertype' == $v[0]){
				unset($cond[$k]);
				break;
			}
		}
		$cond[] = array('usertype','1|3',false);
	} */
	
	$cond2 = $cond;
	$search_attrs = array('city','province');
	$choice_attrs = array('workcity','workprovince');
	$cl = searchApi($index); 
	$cl -> setQueryType(false);
	if($isselectarea && !$isdispmore){
		$rs = $cl -> getResultOfReset($cond,array($offset,$pagesize),$sort);
		if(isset($rs['total_found']) && $rs['total_found']) $total_found = $rs['total_found'];
		else{
			$currenturl = 'http://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"].'&dm=1';			
			header('Location:'.$currenturl);exit;
		}
	}
	
	if(!isset($total_found) || !$total_found){
		$attrs = $search_attrs;
		if($index == 'choice') $attrs = $choice_attrs;
	
		foreach($attrs as $k=>$v){
			foreach($cond2 as $key=>$val){
				if($v == $val[0]){
					unset($cond2[$key]);
					break;
				}
			}
		}
		$cond_str = md5(serialize($cond2));
		$cond_key = $cond_str.'_total';
		$total_found = $memcached -> get($cond_key);
		//total
		if(!$total_found){
			$rs = $cl -> getResultOfReset($cond2,array($offset,$pagesize),$sort);
			
			if(isset($rs['total_found']) && $rs['total_found']) $total_found = $rs['total_found'];
			$memcached -> set($cond_key,$total_found,false,60);
		}
	}

	if($total_found){  
		$pagenum = ceil($total_found/$pagesize);//分页数
		if($page > $pagenum){
			$currenturl = 'http://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
			$currenturl2 = preg_replace("/(&page=\d+)/", '&page='.$pagenum, $currenturl);
			header('Location:'.$currenturl2);exit;
		}
	}else $total_found = 0;
	if(!isset($offset) || $offset === null) $offset = ($page-1)*$pagesize;

	$user = sphinx_search2($cl,$index,$cond,$sort,$page,$pagesize,0,$offset);
	
	if(!is_array($user) || empty($user)) $user = array();
	$result = array('total' => $total_found,'user' => $user);
	return $result;
}  

/* 
function sphinx_search($index,$cond,$sort,$page,$pagesize,$code=0,$offset=null){
	global $memcached,$user_arr,$isselectarea,$isdispmore;
	$maxTotal=600;
	//普通用户||真爱一生||未登录

    $userArr_collect2=array();
	$usersArr_unused=array();
	$usersArr_used=array();
	$user2=array();

	$cond2 = $cond;
	$search_attrs = array('city','province');
	$choice_attrs = array('workcity','workprovince');
	$cl = searchApi($index); 
	
	$cl -> setQueryType(true);
	if($isselectarea && !$isdispmore){
		$rs = $cl -> getResultOfReset($cond,array($offset,$pagesize),$sort);
		if(isset($rs['total_found']) && $rs['total_found']) $total_found = $rs['total_found'];
		else{
			$currenturl = 'http://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"].'&dm=1';			
			header('Location:'.$currenturl);exit;
		}
	}
	
	if(!isset($total_found) || !$total_found){
		$attrs = $search_attrs;
		if($index == 'choice') $attrs = $choice_attrs;

		foreach($attrs as $k=>$v){
			foreach($cond2 as $key=>$val){
				if($v == $val[0]){
					unset($cond2[$key]);
					break;
				}
			}
		}
		
		
		$cond_str = md5(serialize($cond2));
		$cond_key = $cond_str.'_total';
		$total_found = $memcached -> get($cond_key);
	    
		if(!$total_found){
			$rs = $cl -> getResultOfReset($cond2,array($offset,$pagesize),$sort);
			if(isset($rs['total_found']) && $rs['total_found']) $total_found = $rs['total_found'];
			$memcached -> set($cond_key,$total_found,false,60);

		}
	}

    
	
	
	//高级，钻石，城市之星靠前，普通和全权会员交叉显示，全权会员按照使用日期降序排序（1）*****begin******************
	
	//最大600查询
	if ($total_found>$maxTotal) $total_found=$maxTotal;
	
	$rs=$cl -> getResultOfReset($cond2,array(0,$total_found),$sort);
	
    $users=$cl -> getIdSidUType();
	
    
	$user=$userGeneral=$userAdvance=array();
	foreach($users as $key=>$val){
	   $user[]=$val['id'];//全部会员UID
	   if($val['s_cid']<40){
	     $userAdvance[]=$val['id']; //高级以上会员UID
	   }else{
	     $userGeneral[]=$val['id'];//普通会员UID
	   }
	}
	
	//按照同样的数量搜索出全权会员
	foreach($cond2 as $k=>$v){
		if('usertype' == $v[0]){
			unset($cond2[$k]);
			break;
		}
	}
	 // print_r($cond);
	$cond2[] = array('usertype','3',false);
	
     
	$rs2 = $cl -> getResultOfReset($cond2,array(0,$total_found),$sort);
	 // $total_found2=$rs2['total_found'];

	$usersArr_collect=$cl->getIds();
	
	// print_r($usersArr_collect);
	if (!empty($usersArr_collect) && count($usersArr_collect>0)){
	  $userstr_collect=implode(',',$usersArr_collect);
	}else{
	  $userstr_collect=0;
	}
	
	
	//使用未过期的全权显示
	$usetime=time() - 3888000;
    $sql="select uid,action_time from web_full_log where uid in ({$userstr_collect})   order by action_time desc";//全权会员首次使用时间降序排列

	$result=$GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	
	// print_r($result);
	$usersArr_used=array();
	if(!empty($result)){
	   foreach($result as $val){
	     if (empty($val['action_time'])) continue;
		 
		 if($val['action_time']>$usetime){
	        $usersArr_used[]=$val['uid'];
		 }else{
		     $ka=array_search($val['uid'],$usersArr_collect);
			 if ($ka)  unset($usersArr_collect[$ka]);
		 }
	   }
	} 
    
	
	
	
    
	$usersArr_unused=substractArr($usersArr_collect,$usersArr_used);
	
	
	$usersArr_collect2=array_merge($usersArr_unused,$usersArr_used);
	$total_found2=sizeof($usersArr_collect2);
	
	if ($total_found + $total_found2>$maxTotal) {
	    $total_found=$maxTotal;
	}else{
	    $total_found=$total_found+$total_found2;
	}
	
	

	$userGeneral=insertArray($userGeneral,$usersArr_collect2);//普通会员中插入全权会员
	
	$user2=array_merge($userAdvance,$userGeneral);
	
	
	
	if(count($user2>$maxTotal)){
	   $user2=array_slice($user2,0,$maxTotal);
	}
	
	
	if($total_found){
		$pagenum = ceil($total_found/$pagesize);//分页数
		if($page > $pagenum){
			$currenturl = 'http://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
			$currenturl2 = preg_replace("/(&page=\d+)/", '&page='.$pagenum, $currenturl);
			header('Location:'.$currenturl2);exit;
		}
	}else $total_found = 0;
	if(!isset($offset) || $offset === null) $offset = ($page-1)*$pagesize;
	
	
	$user2=array_slice($user2,$offset,$pagesize);
	

	// end ************调用方式（1）*************end*************************************

	 //$user = sphinx_search3($cl,$index,$cond,$sort,0,$offset);//print_r($user);
	 //$user = sphinx_search2($cl,$index,$cond,$sort,$page,$pagesize,0,$offset); //原先调用方式（2）

	if(!is_array($user2) || empty($user2)) $user2 = array();
	$result = array('total' => $total_found,'user' => $user2);
	
	
	return $result;
} 

 */

/**
 * 查询user列表
 */
function sphinx_search2($cl,$index,$cond,$sort,$page,$pagesize,$code=0,$offset){
	global $memcached,$user_arr,$isdispmore,$isresult;
	$max_total = 600;
	$user = null;
	$total_found=0;
	$pagenum = ceil($max_total/$pagesize);//分页数
	
	
	$usersArr_unused=array();
	$usersArr_used=array();
	$user2=array();
	
	if($page == $pagenum)//如果是最后一页，显示指定的条数
		$limit = $max_total%$pagesize;
	else //其他情况显示规定条数
		$limit = $pagesize;
	//$code != 0;降低查询条件
	if($code > 0){//删除一个条件,如果属性条件都去掉则return false;
		$cond = DelAttr($cond,$index);//如果没有可删减的条件则返回false
		if(!is_array($cond) || empty($cond) || $cond == false) return array();
	}
	
	
	//查询条件加缓存
	$cond_str = md5(serialize($cond));
	$cond_key = $user_arr['uid'].'_'.$cond_str;
	$old_search = $memcached -> get($cond_key);
	if(isset($old_search['total']) && $old_search['total'] < $offset){
		if(!$old_search['total']) $isresult = false;
		$user = sphinx_search2($cl,$index,$cond,$sort,$page,$pagesize,($code+1),($offset-$old_search['total']));
	}else{
		$rs = $cl -> getResultOfReset($cond,array($offset,$pagesize),$sort);
        
		if(isset($rs['total_found']) && $rs['total_found']){//有查询的结果
			$total_found = $rs['total_found'];
			$rs_matches = $rs['matches'];
			$cond_arr = array('cond' => $cond,'total' => $total_found);
			$memcached -> set($cond_key,$cond_arr,false,3600);
			
			if(!empty($rs_matches) && count($rs_matches) == 0){//查询出0个
				$user = sphinx_search2($cl,$index,$cond,$sort,$page,$pagesize,($code+1),$offset);
			}elseif(!empty($rs_matches) && count($rs_matches) == $pagesize){//查询的数目正好
				//$user = $cl -> getIds();
				
				
				//高级，钻石，城市之星靠前，普通和全权会员交叉显示，全权会员按照使用日期降序排序（1）*****begin******************
				$cond2=$cond;
				
				
				
				//最大600查询
				if ($total_found>$max_total) $total_found=$max_total;
				
				//$rs=$cl -> getResultOfReset($cond2,array(0,$total_found),$sort);
				
				$users=$cl -> getIdSidUType();
				
				
				$user=$userGeneral=$userAdvance=array();
				foreach($users as $key=>$val){
				   $user[]=$val['id'];//全部会员UID
				   if($val['s_cid']<40){
					 $userAdvance[]=$val['id']; //高级以上会员UID
				   }else{
					 if($val['usertype']!=3) $userGeneral[]=$val['id'];//普通会员UID
				   }
				}
				
				//按照同样的数量搜索出全权会员
				foreach($cond2 as $k=>$v){
					if('usertype' == $v[0]){
						unset($cond2[$k]);
						break;
					}
				}
				 // print_r($cond);
				$cond2[] = array('usertype','3',false);
				
				
				//$rs2 = $cl -> getResultOfReset($cond2,array(0,$total_found),$sort);
				 // $total_found2=$rs2['total_found'];
				$rs2 = $cl -> getResultOfReset($cond2,array($offset,$pagesize),$sort);
                $total_found2=$rs2['total_found'];
				
				$usersArr_collect=$cl->getIds();
				
				
				// print_r($usersArr_collect);
				if (!empty($usersArr_collect) && count($usersArr_collect>0)){
				  $userstr_collect=implode(',',$usersArr_collect);
				}else{
				  $userstr_collect=0;
				}
				
				
				//使用未过期的全权显示
				$usetime=time() - 3888000;
				$sql="select uid,action_time from web_full_log where uid in ({$userstr_collect})   order by action_time desc";//全权会员首次使用时间降序排列

				$result=$GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
				
				// print_r($result);
				$usersArr_used=array();
				if(!empty($result)){
				   foreach($result as $val){
					 if (empty($val['action_time'])) continue;
					 
					 if($val['action_time']>$usetime){
						$usersArr_used[]=$val['uid'];
					 }else{
						 $ka=array_search($val['uid'],$usersArr_collect);
						 if ($ka)  unset($usersArr_collect[$ka]);
					 }
				   }
				} 
				
				
				
				
				
				$usersArr_unused=substractArr($usersArr_collect,$usersArr_used);
				
				
				$usersArr_collect2=array_merge($usersArr_unused,$usersArr_used);
				$total_found2=sizeof($usersArr_collect2);
				
				if ($total_found + $total_found2>$max_total) {
					$total_found=$max_total;
				}else{
					$total_found=$total_found+$total_found2;
				}
				
				

				$userGeneral=insertArray($userGeneral,$usersArr_collect2);//普通会员中插入全权会员
				
				
				$user2=array_merge($userAdvance,$userGeneral);
				
				
				
				
				if(count($user2>$max_total)){
				   //$user2=array_slice($user2,0,$max_total);
				   $user2=array_slice($user2,0,$pagesize);
				}
				
				
				
				
				//$user2=array_slice($user2,$offset,$pagesize);
				
				$user=  array_unique($user2);
				
				//高级，钻石，城市之星靠前，普通和全权会员交叉显示，全权会员按照使用日期降序排序（1）*****end******************
				
				
				
			}else{//查询数据少了
				$user = $cl -> getIds();
				if($isdispmore){
					$user_other = sphinx_search2($cl,$index,$cond,$sort,$page,($pagesize-count($user)),($code+1),0);
					if(is_array($user_other) && !empty($user_other)) 
						$user = array_merge($user,$user_other);
				}
			}
		}elseif(!$rs){//无查询结果
			if($code == 0) $isresult = false;
			//无结果的话，需要缓存这次按照这个条件查询能查询出的总数
			$old_search = $memcached -> get($cond_key);
			if(isset($old_search['total']) && $old_search['total']){
				$rs['total_found'] = $old_search['total'];
			}else{
				$rs = $cl -> getResultOfReset($cond,null,$sort);
				if(!isset($rs['total_found']) || !$rs['total_found']) $rs['total_found'] = 0;
				$total_found = $rs['total_found'];
				if(isset($rs_matches)) $rs_matches = $rs['matches'];
				$cond_arr = array('cond' => $cond,'total' => $total_found);
				$memcached -> set($cond_key,$cond_arr,false,3600);
			}
			if($offset-$rs['total_found'] > 0) $pro_offset = $offset-$rs['total_found'];
			else $pro_offset = 0;
			$user = sphinx_search2($cl,$index,$cond,$sort,$page,$pagesize,($code+1),$pro_offset);
		}
	}
	return $user;
}

/**
 * Sphinx数组删除一个条件
 */
function DelAttr($cond,$index='members_women'){
	global $isdelcond;

	if(!is_array($cond)){
		exit('The argv is error.');
	}
	$search_attrs = array('city','province');
	$choice_attrs = array('workcity','workprovince');
	
	$attrs = $search_attrs;
	if($index == 'choice') $attrs = $choice_attrs;
	
	foreach($attrs as $k=>$v){
		if(is_array($v) && !empty($v)){//choice--对应两个字段的
			$n=0;
			foreach($v as $ke=>$va){
				foreach($cond as $key=>$val){
					if(isset($val[0]) && $val[0] == $va && $val[2] == false){
						$cond[$key][2] = true;
						$n++;
						break;
					}
				}
			}
			if($n == 2){
				$isdelcond = true;
				return $cond;
			}
		}else{
			foreach($cond as $key=>$val){
				if(isset($val[0]) && $val[0] == $v && !$val[2]){
					$cond[$key][2] = true;
					$isdelcond = true;
					return $cond;
				}
			}
		}
	}
	return false;
}

/**
 * 普通会员搜索加入全权会员
 */
function GeneralUserSearch($index,$cond,$sort,$page,$pagesize,$code=0,$offset=null){
	global $user_arr,$isselectarea,$isdispmore,$isresult;
	$search_attrs = array('city','province');
	$choice_attrs = array('workcity','workprovince');
	$cl = searchApi($index); 
	$cl -> setQueryType(false);
	$pagesize2 = (int)$pagesize/2;
	foreach($cond as $k=>$v){
		if('usertype' == $v[0]){
			unset($cond[$k]);
		}
	}
	
	$cond3 = $cond2 = $cond;
	$cond3[] = array('usertype','1|3',false);
	
	if($isselectarea && !$isdispmore){
		$rs = $cl -> getResultOfReset($cond3,array($offset,$pagesize),$sort);
		if(isset($rs['total_found']) && $rs['total_found']) $total_found = $rs['total_found'];
		else{
			$currenturl = 'http://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"].'&dm=1';			
			header('Location:'.$currenturl);exit;
		}
	}
	
	if(!isset($total_found) || !$total_found){
		$attrs = $search_attrs;
		if($index == 'choice') $attrs = $choice_attrs;
		foreach($attrs as $k=>$v){
			foreach($cond3 as $key=>$val){
				if($v == $val[0]){
					unset($cond3[$key]);
					unset($cond2[$key]);
					unset($cond[$key]);
					break;
				}
			}
		}
		$rs = $cl -> getResultOfReset($cond3,array($offset,$pagesize),$sort);
		if(isset($rs['total_found']) && $rs['total_found']) $total_found = $rs['total_found'];
		if($total_found > 600) $total_found=600;
	}
	
	if($total_found){
		//注册会员总数
		$cond[] = array('usertype','1',false);
		$rs_reg = $cl -> getResultOfReset($cond,array($offset,$pagesize),$sort);
		if(isset($rs_reg['total_found']) && $rs_reg['total_found']) $total_reg = $rs_reg['total_found'];
		else $total_reg = 0;
		$cond2[] = array('usertype','3',false);
		$rs_col = $cl -> getResultOfReset($cond2,array($offset,$pagesize),$sort);
		if(isset($rs_col['total_found']) && $rs_col['total_found']) $total_col = $rs_col['total_found'];
		else $total_col = 0;
		
		//$total_col = $total_found - $total_reg;
			
		$pagenum = ceil($total_found/$pagesize);//分页数
		if($page > $pagenum){
			$currenturl = 'http://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
			$currenturl2 = preg_replace("/(&page=\d+)/", '&page='.$pagenum, $currenturl);
			header('Location:'.$currenturl2);exit;
		}
	}else $total_found = 0;
	
//	var_dump($total_reg);
//	var_dump($total_col);
	
	//计算分别取多少条
	$reg_page_num = ceil($total_reg/$pagesize2);
	$collection_page_num = ceil($total_col/$pagesize2);
//	var_dump($reg_page_num);var_dump($collection_page_num);
	if($reg_page_num > $collection_page_num){
		if($collection_page_num == 0){
			$offset_col = 0;
			$pagesize_col = 0;
			$offset_reg = ($page-1)*$pagesize;
			$pagesize_reg = $pagesize;
		}else{
			if($page < $collection_page_num){
				$offset_reg = ($page-1)*$pagesize2;
				$pagesize_reg = $pagesize2;
				$offset_col = ($page-1)*$pagesize2;
				$pagesize_col = $pagesize2;
			}elseif($page == $collection_page_num){
				$offset_reg = ($page-1)*$pagesize2;
				$pagesize_reg = $pagesize-$total_col%$pagesize2;
				$offset_col = ($page-1)*$pagesize2;
				$pagesize_col = $total_col%$pagesize2;
			}else{
				$offset_col = 0;
				$pagesize_col = 0;
				$offset_reg = ($collection_page_num-1)*$pagesize2+($pagesize-$total_col%$pagesize2)+($page-$collection_page_num-1)*$pagesize;//前面页面显示3个的+中间显示不定项的+后面显示6个的
				$pagesize_reg = $pagesize;
			}
		}
	}elseif($reg_page_num < $collection_page_num){
		if($reg_page_num == 0){
			$offset_col = ($page-1)*$pagesize;
			$pagesize_col = $pagesize;
			$offset_reg = 0;
			$pagesize_reg = 0;
		}else{
			if($page < $reg_page_num){
				$offset_reg = ($page-1)*$pagesize2;
				$pagesize_reg = $pagesize2;
				$offset_col = ($page-1)*$pagesize2;
				$pagesize_col = $pagesize2;
			}elseif($page == $reg_page_num){
				$offset_reg = ($page-1)*$pagesize2;
				$pagesize_reg = $total_reg%$pagesize2;
				$offset_col = ($page-1)*$pagesize2;
				$pagesize_col = $pagesize-$pagesize_reg;
			}else{
				$offset_col = ($reg_page_num-1)*$pagesize2+($pagesize-$total_reg%$pagesize2)+($page-$reg_page_num-1)*$pagesize;//前面页面显示3个的+中间显示不定项的+后面显示6个的
				$pagesize_col = $pagesize;
				$offset_reg = 0;
				$pagesize_reg = 0;
			}
		}
	}elseif($reg_page_num == $collection_page_num){
		$offset_reg = ($page-1)*$pagesize2;
		$pagesize_reg = $total_reg%$pagesize2;
		$offset_col = ($page-1)*$pagesize2;
		$pagesize_col = $total_col%$pagesize2;
	}
	
//	var_dump((int)$offset_reg);var_dump($pagesize_reg);var_dump($offset_col);var_dump($pagesize_col);
	if($pagesize_reg){
		$user_reg = sphinx_search2($cl,$index,$cond,$sort,$page,$pagesize_reg,$code,$offset_reg);//注册会员
		if(!is_array($user_reg) || empty($user_reg)) $user_reg=array();
	}
	
	if($pagesize_col){
		$user_collect = sphinx_search2($cl,$index,$cond2,$sort,$page,$pagesize_col,$code,$offset_col);//采集会员
	}
	
	$gender = 1;
	if(isset($user_arr['gender']) && isset($user_arr['s_cid'])) $gender = $user_arr['gender'];
	
	if(!empty($user_reg) && !empty($user_collect)){
		if($gender){
			$arr1=$user_reg;
			$arr2=$user_collect;
		}else{
			$arr2=$user_reg;
			$arr1=$user_collect;
		}
		isset($arr1[0]) && $user_list[] = $arr1[0];
		isset($arr2[0]) && $user_list[] = $arr2[0];
		isset($arr1[1]) && $user_list[] = $arr1[1];
		isset($arr2[1]) && $user_list[] = $arr2[1];
		isset($arr1[2]) && $user_list[] = $arr1[2];
		isset($arr2[2]) && $user_list[] = $arr2[2];
		
		isset($arr1[3]) && $user_list[] = $arr1[3];
		isset($arr2[3]) && $user_list[] = $arr2[3];
		isset($arr1[4]) && $user_list[] = $arr1[4];
		isset($arr2[4]) && $user_list[] = $arr2[4];
		isset($arr1[5]) && $user_list[] = $arr1[5];
		isset($arr2[5]) && $user_list[] = $arr2[5];
		
	}elseif(!empty($user_reg) && empty($user_collect)){
		$user_list = $user_reg;
	}elseif(empty($user_reg) && !empty($user_collect)){
		$user_list = $user_collect;
	}else $user_list=array();
	
	if(!empty($user_reg) || !empty($user_collect)) $isresult=true;
	
	//var_dump($user_list);
	return array('total' => $total_found,'user' => $user_list);
}

/**
 * 是否用到了模糊字段
 */
function isUseFuzzySearch($cond){
	$fuzzy_field = array(
		'0' => 'nickname',
		'1' => 'nickname2',
		'2' => 'truename',
		'3' => 'marriage',
		'4' => 'education',
		'5' => 'salary',
		'6' => 'house',
		'7' => 'children',
		'8' => 'body',
		'9' => 'animalyear',
		'10' => 'constellation',
		'11' => 'bloodtype',
		'12' => 'hometownprovince',
		'13' => 'hometowncity',
		'14' => 'nation',
		'15' => 'religion',
		'16' => 'family',
		'17' => 'language',
		'18' => 'smoking',
		'19' => 'drinking',
		'20' => 'occupation',
		'21' => 'vehicle',
		'22' => 'corptype',
		'23' => 'wantchildren'
	);
	if(is_array($cond) && !empty($cond)){
		foreach($cond as $k=>$v){
			if(in_array($v[0],$fuzzy_field)) return true;
		}
	}
	return false;
}

/**
 * 获得排序字符串
 * @param $sortway
 */
function getSortsStr($sortway,$cond){
	global $user_arr,$isselectarea;
	$isfuzzyfield = isUseFuzzySearch($cond);
	if($isfuzzyfield || isset($user_arr['s_cid']) && $user_arr['s_cid'] < 40){
		$sort_other = '@weight desc';
	}else{
		$sort_other = 'usertype asc';
	}
	
	if(!$sortway) $sortway = 1;
	$sort = array();
	
	//note 普通会员搜索结果还是按照以前的排序，高级、钻石会员搜索结果按照本站注册的排序
	if(!isset($user_arr['s_cid']) || isset($user_arr['s_cid']) && $user_arr['s_cid'] > '40'){
		if($isselectarea){
			$sort = array('s_cid asc',$sort_other,'bgtime desc','birthyear desc','pic_num desc');
		}else{
			$sort = array('city_star desc','s_cid asc',$sort_other,'bgtime desc','birthyear desc','pic_num desc'); //城市之星靠前	
		}
	}else{
		if($isselectarea){
			$sort = array('s_cid asc',$sort_other,'bgtime desc','birthyear desc','pic_num desc');
		}else{
			$sort = array('city_star desc','s_cid asc',$sort_other,'bgtime desc','birthyear desc','pic_num desc'); //城市之星靠前		
		}
	}
	$sort_str = array();
	if($sortway == '2'){
		//note 最新注册
		$sort_str = array('regdate desc');
		$sort_str[] = $sort[0];
		$sort_str[] = $sort[1];
		$sort_str[] = $sort[2];
		$sort_str[] = $sort[3];
	}elseif($sortway == '3'){
		//note 诚信等级
		$sort_str = array('certification desc');
		$sort_str[] = $sort[0];
		$sort_str[] = $sort[1];
		$sort_str[] = $sort[2];
		$sort_str[] = $sort[3];
	}else{
		$sort_str[] = $sort[0];
		$sort_str[] = $sort[1];
		$sort_str[] = $sort[2];
		$sort_str[] = $sort[3];
		$sort_str[] = $sort[4];
	}
	
	$sort_str = implode(',',$sort_str);
	
	if(!$sort_str) $sort_str= 'city_star desc,s_cid asc,@weight desc';
	
	return $sort_str;
}
?>
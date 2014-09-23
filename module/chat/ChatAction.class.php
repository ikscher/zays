<?php

class ChatAction{
	/**
	 * I.检测用户是否具有打开在线聊天的权限
	 * II.告诉网站有在线聊天信息
	 */
	
	private $randStr = '%hongniang%';//一个任意的字符串
	private $key_pre = 'chat_';//memcache键值前缀
	
	private $mem;//memcache
	
	/**
	 * 析构函数
	 */
	public function __construct(){
		global $memcached;
		$this -> mem = $memcached;
	}
	/**
	 * 高级以上会员才可以发起在线聊天
	 */
	private function checkChatPerm($scid=null){
		if($scid == '10' || $scid == '20' || $scid == '30') return true;
		else return false;
		return true;
	}
	
	/**
	 * 得到用户信息
	 * @param $fromuid 发起会话用户
	 * @param $touid 想要联系的用户
	 * @param $serverid 客服ID
	 */
	public function getUserInfo($fromuid,$touid,$serverid=null){
		global $_MooClass,$dbTablePre;
		if($fromuid == $touid) return 1;//不可以和自己在线聊天
		if($fromuid && $touid){
			$sql = "select m.uid,m.s_cid,m.gender,m.usertype,mf.mainimg,m.nickname,m.images_ischeck from ".$dbTablePre."members_search m left join ".$dbTablePre."members_base mf on m.uid=mf.uid where m.uid in($fromuid,$touid)";
			$rs = $_MooClass['MooMySQL'] -> getAll($sql);
			if(is_array($rs) && !empty($rs)){
				foreach($rs as $k=>$v){
					if(!$v['images_ischeck']) $v['mainimg'] = '';
					//if($v['mainimg'] && !$this->imgExist(MOOPHP_IMAGE_HOST.'/'.$v['mainimg'])) $v['mainimg'] = '';
					if($fromuid == $v['uid']) $data['from'] = $v;
					if($touid == $v['uid']) $data['to'] = $v;
				}
			}
			//check
			if(!isset($data['from']['uid']) || !$data['from']['uid']) return 8;
			
			//perm高级会员才能发起在线聊天
			$user_red = $this->hasInlineChat($data['from']['uid'],$data['to']['uid']);
			if(!$serverid && !$this -> checkChatPerm($data['from']['s_cid']) && !$user_red)	return 2;
			
			//sex不可同性在线聊天
			if($data['from']['gender'] == $data['to']['gender']) return 3;
			//没有该会员
			if(!isset($data['to']['uid']) || !$data['to']['uid']) return 4;
			//两个会员之间是否有屏蔽关系
			if($_MooClass['MooMySQL']->getOne("select screenid from ".$dbTablePre."screen where (uid='$fromuid' and mid='$touid') or (uid='$touid' and mid='$fromuid')"))
		        return 5;
		    //客服模拟登陆，如果不是全权会员，不可以进行这个操作
			//if($serverid && $data['from']['usertype'] != 3) return 6;
			
			$this -> isRead($touid,$fromuid);
			return $data;
		}
		return 7;
	}
	
	/**
	 * 告诉网站已经谁有在线聊天请求了
	 * @param $touid  谁出现了在线聊天信息
	 * @param $fromuid 来自谁的在线聊天
	 * $chat_msg = array(
	 * 	$formuid => 时间戳
	 * );
	 */
	public function InlineChatMsg($touid,$fromuid){
		$key = $this->key_pre.$touid.$this->randStr;
		$chat_msg = $this -> mem -> get($key);
		$now = time();
		$chat_msg[$fromuid] = $now;
		$this -> mem -> set($key,$chat_msg,0,30*86400);
		return true;
	}
	
	/**
	 * 是否这个用户有在线聊天请求
	 */
	public function hasInlineChat($touid,$formid=''){
		/*global $_MooClass,$dbTablePre;
		$sql = "select id,fromid,toid from ".$dbTablePre."chat_message where toid=$touid";
		if($formid) $sql .= " and fromid=$formid";
		$sql .= " and status=0 limit 1";
		$chat_arr = $_MooClass['MooMySQL'] -> getOne($sql);
		if(is_array($chat_arr) && !empty($chat_arr)) return $chat_arr;*/
		
		$key = $this->key_pre.$touid.$this->randStr;
		
		$chat_msg = $this -> mem -> get($key);
		
		if(!$formid){
			if(is_array($chat_msg) && !empty($chat_msg)){
				foreach($chat_msg as $k=>$v){
					if($v){
						$data['toid'] = $touid;
						$data['fromid'] = $k;
						return $data;
					}else continue;
				}
			}
		}else{
			if(isset($chat_msg[$formid]) && $chat_msg[$formid]){
				$data['toid'] = $touid;
				$data['fromid'] = $formid;
				return $data;
			}
		}
		return false;
	}
	
	/**
	 * 清空该会员的所有在线聊天提示信息
	 * @param $touid 标记为已读在线信息的的用户ID
	 */
	public function hasReadMsg($touid){
		if(!$touid) return false;
		$key = $this->key_pre.$touid.$this->randStr;
		$this->rds = new Rediska_Key($key);
		$this -> rds -> setAndExpire(array(),1);
		return true;
	}
	
	/**
	 * 标记用户在线聊天提示信息的一条为已读
	 * @param $fromuid
	 * @param $touid
	 */
	public function isRead($fromuid,$touid){
		$key = $this->key_pre.$touid.$this->randStr;
		$chat_msg = $this -> mem -> get($key);
		if(is_array($chat_msg)){
			if(isset($chat_msg[$fromuid]) && $chat_msg[$fromuid]) unset($chat_msg[$fromuid]);
		}
		$chat_msg = $this -> mem -> set($key,$chat_msg,0,30*86400);
	}
	
	/**
	 * 判断在服务器这个图片是否存在
	 * @param $url 图片链接
	 */
	public function imgExist($url){
        $ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_NOBODY, 1);
		curl_setopt($ch, CURLOPT_FAILONERROR, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        if(curl_exec($ch)!==false)
            return true;
        else
            return false;
    }
}
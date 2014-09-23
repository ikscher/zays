<?php
/*
	More & Original PHP Framwork
	Copyright (c) 2007 - 2008 IsMole Inc.

	$Id: MooCache.class.php 387 2008-09-09 07:22:03Z kimi $
*/

!defined('IN_MOOPHP') && exit('Access Denied');

class MooCache {

	function arrayEval($array, $level = 0) {
		$space = '';
		for($i = 0; $i <= $level; $i++) {
			$space .= "\t";
		}
		$evaluate = "Array\n$space(\n";
		$comma = $space;
		if(is_array($array)) {
			foreach($array as $key => $val) {
				$key = is_string($key) ? '\''.addcslashes($key, '\'\\').'\'' : $key;
				$val = !is_array($val) && (!preg_match("/^\-?[1-9]\d*$/", $val) || strlen($val) > 12) ? '\''.addcslashes($val, '\'\\').'\'' : $val;
				if(is_array($val)) {
					$evaluate .= "$comma$key => ".$this->arrayEval($val, $level + 1);
				} else {
					$evaluate .= "$comma$key => $val";
				}
				$comma = ",\n$space";
			}
		}
		$evaluate .= "\n$space)";
		return $evaluate;
	}

	function getBlock($param) {

		$cachekey = md5($param);
		$param = $this->parseParameter($param);
		$cachekey = $param['name'].'_'.$cachekey;//key

		//note �ж��Ƿ���Ҫ����
		if(MOOPHP_ALLOW_BLOCK && $param['cachetime']) {
			$cacheArray = $this->getBlockCache($cachekey);
		} else {
			$cacheArray = array();
		}

		//note �ж��Ƿ���ҪӦ�û������
		if(empty($cacheArray)) {
			switch($param['type']) {
				case 'list':
					$cacheArray['values'] = $toding;
					break;
				case 'query':
					$cacheArray['values'] = $GLOBALS['_MooClass']['MooMySQL']->getAll($param['sql'],0,0,0,true);
					break;
				default:
					$cacheArray['values'] = NULL;
			}

			//note �ж��Ƿ���Ҫ���»���
			if(MOOPHP_ALLOW_BLOCK && $param['cachetime'] && !empty($cacheArray['values'])) {
				//$cacheArray['multipage'] = $cacheArray['multi'];//�����ҳ debug
				$this->setBlockCache($cachekey, $cacheArray,$param['cachetime']);
			}
		}

		//note ��Block�Ļ�����ݴ���ȫ�ֱ���
		$GLOBALS['_MooBlock'][$param['name']] = $cacheArray['values'];
		//$GLOBALS['_MooBlock'][$param['name'].'_multipage'] = $cacheArray['multi'];//debug

	}

	function getBlockCache($cachekey) {
		global $memcached;
		$cacheArray = $memcached->get($cachekey);
		$caches = array();
		if(is_array($cacheArray) && $cacheArray) {
			if(isset($cacheArray['multipage'])) {
				$caches['multi'] = $cacheArray['multipage'];
				unset( $cacheArray['multipage']);
			} else {
				$caches['multi'] = '';
			}
			$caches['values'] = $cacheArray['values'];
		}else{
			return array();
		}
		return $caches;
	}

	function parseParameter($param) {
		$paramarr = array();
		$parr = explode('/', $param);
		if(empty($parr)) return $paramarr;

		foreach($parr as $value){
			$valuearr = explode('=', $value, 2);
			$paramarr[$valuearr[0]] = $valuearr[1];
		}
		return $paramarr;
	}

	function setBlockCache($cacheKey, $cacheArray,$exprie=0) {
		global $memcached;
		if (empty($cacheArray)) return;
		if ($memcached->get($cacheKey)){
			$memcached->replace($cacheKey,$cacheArray,0,$exprie);
		}else{
			$memcached->set($cacheKey,$cacheArray,0,$exprie);
		}
	}

	function setCache($cacheFile) {

		if(!MOOPHP_ALLOW_CACHE) {
			return FALSE;
		}

		$cacheContent = '';

		foreach($GLOBALS['_MooCacheConfig'][$cacheFile] as $cacheKey) {
			$cacheFuncName = 'MooGetCache_'.$cacheKey;
			$cacheContent .= "\$_MooCache['$cacheKey'] = ".$this->arrayEval($cacheFuncName()).";\n\n";
		}

		$this->writeCache($cacheFile, $cacheContent);
	}

	function setCacheByKey($cacheKey) {

		if(!MOOPHP_ALLOW_CACHE) {
			return FALSE;
		}

		foreach($GLOBALS['_MooCacheConfig'] as $cacheFile => $cacheKeyArray) {
			foreach($cacheKeyArray as $key) {
				if($cacheKey == $key) {
					$this->setCache($cacheFile);
				}
			}
		}

	}

	function writeCache($cacheFile, $cacheContent) {

		$cacheContent = "<?php\n//MooPHP Cache File, Do Not Modify Me!".
				"\n//Created: ".date("Y-m-d H:i:s").
				"\n$cacheContent?>";


		$cacheDir = MOOPHP_DATA_DIR.'/cache/';
		$cacheFile = MOOPHP_DATA_DIR.'/cache/cache_'.$cacheFile.'.php';

		MooMakeDir($cacheDir);
		MooWriteFile($cacheFile, $cacheContent);

	}
}
?>
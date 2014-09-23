<?php
/*
	more & original php framwork
	copyright (c) 2007 - 2008 ismole inc.

	$Id: MooFiles.class.php 273 2008-06-02 00:23:07Z aming $
*/

!defined('IN_MOOPHP') && exit('Access Denied');

class MooFiles {
	var $dirList = array();

	/**
	 * ��ȡ�ļ�
	 *
	 * @param boolean $exit
	 * @param string $file
	 * @return string
	 */
	function fileRead($file, $exit = TRUE) {
		return MooReadFile($file, $exit);
	}

	/**
	 * �洢���
	 * 
	 * @param string $file
	 * @param string $content
	 * @param string $mod
	 * @param boolean $exit
	 * @return boolean
	 */
	function fileWrite($file, $content, $mod = 'w', $exit = TRUE) {
		return MooWriteFile($file, $content, $mod, $exit);
	}

	/**
	 * ɾ�����
	 *
	 * @param string $file��
	 * @return boolean;
	 */
	 function fileDelete($folder) {
		if(is_file($folder) && file_exists($folder)) {
			unlink($folder);
		}
		return true;
	}

	/**
	 * �����ļ���Ŀ¼
	 *
	 * @param string $file��
	 * @param string $type��
	 * @return boolean;
	 */
	function fileMake($file, $type = 'dir') {
		$array = explode('/', $file);
		$count = count($array);
		$msg = '';
		if($type == 'dir') {
			for($i = 0; $i < $count; $i++) {
				$msg .= $array[$i];
				if(!file_exists($msg) && ($array[$i])) {
					mkdir($msg, 0777);
				}
				$msg .= '/';
			}
		} else {
			for($i = 0; $i < ($count-1); $i++) {
				$msg .= $array[$i];
				if(!file_exists($msg) && ($array[$i])) {
					mkdir($msg, 0777);
				}
				$msg .= '/';
			}
			global $systemTime;
			$theTime = $systemTime ? $systemTime : time();
			//note:�����ļ�
			@touch($file, $theTime);
			unset($theTime);
		}
		unset($msg, $file, $type, $count, $array);
		return true;
	}

	//这里只针对视频上传处理
	function fileMake_news($file, $type = 'dir') {
		$array = explode('/', $file);
		$count = count($array);
		$msg = '';
		if($type == 'dir') {
			for($i = 0; $i < $count; $i++) {
				$msg .= $array[$i];
				if(!file_exists($msg) && ($array[$i] || $array[$i] == 0)) {
					mkdir($msg, 0777);
				}
				$msg .= '/';
			}
		} else {
			for($i = 0; $i < ($count-1); $i++) {
				$msg .= $array[$i];
				if(!file_exists($msg) && ($array[$i])) {
					mkdir($msg, 0777);
				}
				$msg .= '/';
			}
			global $systemTime;
			$theTime = $systemTime ? $systemTime : time();
			//note:�����ļ�
			@touch($file, $theTime);
			unset($theTime);
		}
		unset($msg, $file, $type, $count, $array);
		return true;
	}

	/**
	 * ���Ʋ���
	 *
	 * @param string $old��
	 * @param string $new��
	 * @param boolean $recover��
	 * @return boolean;
	 */
	function fileCopy($old, $new, $recover = true) {
		if(substr($new, -1) == '/') {
			$this->fileMake($new, 'dir');
		} else {
			$this->fileMake($new, 'file');
		}
		if(is_file($new)) {
			if($recover) {
				unlink($new);
			} else {
				return false;
			}
		} else {
			$new = $new.basename($old);
		}
		copy($old, $new);
		unset($old, $new, $recover);
		return true;
	}


	/**
	 * �ļ��ƶ�����
	 *
	 * @param string $old��
	 * @param string $new��
	 * @param boolean $recover��
	 * @return boolean;
	 */
	function fileMove($old, $new, $recover = true) {
		if(substr($new, -1) == '/') {
			$this->fileMake($new, 'dir');
		} else {
			$this->fileMake($new, 'file');
		}
		if(is_file($new)) {
			if($recover) {
				unlink($new);
			} else {
				return false;
			}
		} else {
			$new = $new.basename($old);
		}
		rename($old, $new);
		unset($old, $new, $recover);
		return true;
	}

	/**
	 * ��ȡ�ļ����б�
	 *
	 * @param string $folder��
	 * @param boolean $isSubDir��
	 * @return array;
	 */
	function getDirList($folder, $isSubDir = false) {
		$this->dirList = array();
		if(is_dir($folder)) {
			$handle = opendir($folder);
			while(false !== ($myFile = readdir($handle))) {
				if($myFile != '.' && $myFile != '..') {
					$this->dirList[] = $myFile;
					if($isSubDir && is_dir($folder.'/'.$myFile)) {
						$this->getDirList($folder.'/'.$myFile, $isSubDir);
					}
				}
			}
			closedir($handle);
			unset($folder, $isSubDir);
			return $this->dirList;
		}
		return $this->dirList;
	}

	/**
	 * ���ļ�
	 *
	 * @param string $file��
	 * @param string $type��
	 * @return resource;
	 */
	function fileOpen($file, $type = 'wb') {
		$handle = fopen($file, $type);
		return $handle;
	}
	
	/**
	 * �ر�ָ��
	 *
	 * @param resource $handle
	 * @return boolean
	 */
	function fileClose($handle) {
		return fclose($handle);
	}
}
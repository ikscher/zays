<?php
/*
	More & Original PHP Framwork
	Copyright (c) 2007 - 2008 IsMole Inc.

	$Id: MooUpload.class.php 378 2008-08-01 05:19:08Z lulu $
*/


!defined('IN_MOOPHP') && exit('Access Denied');


/*
* ʹ�÷�����
$upload = MooAutoLoad('MooUpload');
$upload->config(array(
	'targetDir' => './../Moo-data/attachments/',
	'saveType' => 1,
	'thumbStatus' => 1,
	'waterMarkStatus' => 1,
	'imageConfig' => array('thumbDir'=>'./../Moo-data/attachments/thumb/')
));
$files = $upload->saveFiles('upfile');
* upfile �ϴ��ļ������ļ���������
* $files Ϊһ���ϴ��ɹ����һ���ļ���Ϣ����
**/

class MooUpload {

	//note �ϴ���Ŀ¼��ȷ����д
	var $targetDir = '';

	//note �ϴ�����ķ�ʽ��0Ϊ��������Ŀ¼����ȫ��������һ��Ŀ¼��  1 ���·�ʽΪһ����Ŀ¼�洢 2 ���췽ʽΪһ����Ŀ¼�洢
	var $saveType =1;

	//note ���ص��ϴ���Ϣ
	var $upFiles = array();

	//note ͼƬ������չ��׺
	var $images = array('jpg', 'jpeg', 'gif', 'png', 'bmp');

	//note �����ϴ����ļ�����,������
	var $allowExtensions = array();

	//note �Ƿ�������ͼ
	var $thumbStatus = 0;

	//note �Ƿ���ˮӡ, ��Ҫ�� $imageConfig ͬʱ����
	var $waterMarkStatus = 0;

	//note ����ͼ��ˮӡ�Ĳ��� ����, ��������˵���� MooImage�Ĳ���˵��
	var $imageConfig = array();

	//note �ж��Ƿ��Ѿ���������Ŀ¼����ֹ�ظ�����
	var $mkSubDirEd = false;

	//note ͼƬ������
	var $imageClass = '';

	//note �Ƿ��Ѿ�����ͼƬ���������Ӧ����
	var $imageConfiged = false;

	/**
	 * ���ú���
	 *
	 * @param array $config: ��������,��Ӧ��key�ͱ�����Ӧ
	 * @return void
	 */
	function config($config) {
		if(is_array($config)) {
			foreach ($config as $var=>$val) {
				if(isset($this->$var)) {
					$this->$var = $val;
				}
			}
		}
	}

	/**
	 * ���������ϴ��ļ�
	 * @param string $upFilename �ϴ��ļ����ļ���������
	 *
	 * @return array $this->upFiles �ļ���Ϣ����
	 */
	function saveFiles($upFilename) {

		$files = $this->getFiles($upFilename);
		foreach($files as $file) {
			//note ��������������tmp_name�������������ַ��������滻֮��
			$file['tmp_name'] = str_replace('\\\\', '\\', $file['tmp_name']);
			if(!is_uploaded_file($file['tmp_name']) || !($file['tmp_name'] != 'none' && $file['tmp_name'] && $file['name'])) {
				continue;
			}
			if(!empty($this->allowExtensions) && !in_array($this->getExtension($file['name']), $this->allowExtensions)) {
				continue;
				//uploadError('Not AllowExtensions Attachment!');
			}
			$this->upFiles[] = $this->saveFile($file);
		}
		return $this->upFiles;
	}

	/**
	 * ���洦�����ļ�
	 *
	 * @return string
	 */
	function saveFile(& $file) {
		$upFile = $imageFile = array();
		$this->getSubDir();
		$upFile['path'] = $this->targetDir;
		$upFile['filename'] = $file['name'];
		$upFile['name'] = date("YmdHis").$this->random(10, 1);
		$upFile['extension'] = $this->getExtension($file['name']);
		$upFile['size'] = $file['size'];
		$upFile['isimage'] = 0;
		$imageFile['size'] = $file['size'];
		$destination = $upFile['path'].$upFile['name'].'.'.$upFile['extension'];
		if(move_uploaded_file($file['tmp_name'], $destination)) {

			if(in_array($upFile['extension'], array('jpg', 'jpeg', 'gif', 'png', 'swf', 'bmp')) && function_exists('getimagesize') && !@getimagesize($destination)) {
				unlink($destination);
				//uploadError('Not Expected Attachment!');
			}elseif(in_array($upFile['extension'], array('jpg', 'jpeg', 'gif', 'png', 'bmp'))) {
				if($this->thumbStatus || $this->waterMarkStatus) {

					if(!$this->imageClass) {
						$this->imageClass = MooAutoLoad('MooImage');
					}
					$this->imageClass->image($destination, $imageFile);
					if(!$this->imageConfiged) {
						$this->imageClass->config($this->imageConfig);
						$this->imageConfiged = true;
					}
					$this->imageClass->thumbStatus && $this->imageClass->thumb();
					$this->imageClass->waterMarkStatus && $this->imageClass->Watermark();
					$upFile = array_merge($this->imageClass->upFile, $upFile);
				}
				$upFile['isimage'] = 1;
			}
			return $upFile;
		}
	}


	/**
	 * ����ָ���洢�ķ�ʽȡ���ϴ���Ŀ¼
	 *
	 * @return void
	 */
	function getSubDir() {

		if($this->mkSubDirEd){
			return ;
		}

		$this->mkSubDirEd = true;

		if(empty($this->targetDir)) {
			$this->targetDir = MOOPHP_DATA_DIR.'/attachments/';
		}

		if(!is_dir($this->targetDir)) {
			mkdir($this->targetDir, 0777);
			touch($this->targetDir.'index.htm');
		}

		if($this->saveType == 1) {
			$this->targetDir .= date('Ym'). '/';
		}else if($this->saveType == 2) {
			$this->targetDir .= date('Ymd').'/';
		}else {

		}

		if(!is_dir($this->targetDir)) {
			mkdir($this->targetDir, 0777);
			touch($this->targetDir.'index.htm');
		}
	}

	/**
	 * �����ϴ��ļ�����"."����չ��
	 *
	 * @return string
	 */
	function getExtension($fileName) {
		return  strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
	}


	/**
	 * �Ѷ���ļ��ϴ�����Ϣ��ֺ󷵻�
	 * @param string $upFilename  ���ϴ��ļ�����������
	 *
	 * @return array FilesInfo
	 */
	function getFiles($upFilename) {
		$upFiles = array();
		if(isset($_FILES[$upFilename]) && is_array($_FILES[$upFilename])) {
			foreach($_FILES[$upFilename] as $key => $var) {
				if(!is_array($var)) {
					$upFiles[0] = $_FILES[$upFilename];
					break;
				}
				foreach($var as $id => $val) {
					$upFiles[$id][$key] = $val;
				}
			}
		}
		return $upFiles;
	}

	/**
	 * ��������ַ�
	 * @param int $length  �ַ�����
	 * @param boolean $numeric  �Ƿ�������
	 *
	 * @return string random string
	 */
	function random($length, $numeric = 0) {
		PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);
		if($numeric) {
			$hash = sprintf('%0'.$length.'d', mt_rand(0, pow(10, $length) - 1));
		} else {
			$hash = '';
			$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
			$max = strlen($chars) - 1;
			for($i = 0; $i < $length; $i++) {
				$hash .= $chars[mt_rand(0, $max)];
			}
		}
		return $hash;
	}


}




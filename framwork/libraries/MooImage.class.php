<?php
/*
	More & Original PHP Framwork
	Copyright (c) 2007 - 2008 IsMole Inc.

	$Id: MooImage.class.php 405 2008-11-26 02:25:35Z kimi $
*/

!defined('IN_MOOPHP') && exit('Access Denied');

/**
 * ʹ�÷���
$image = MooAutoLoad('MooImage');
//����������������Կ������Ӧ��˵��
$image->config(array('waterMarkMinWidth' => '400', 'waterMarkMinHeight' => '300', 'waterMarkStatus' => 9));
$image->thumb(200, 260, './../Moo-data/attachments/2.jpg');
$image->waterMark();
 */


class MooImage {

	//note ��Ҫ�����Դ�ļ�
	var $targetFile = '';

	//note ��Ҫ�����Դ�ļ�����Ϣ
	var $targetInfo = '';

	//note ����ĺ���
	var $imageCreateFromFunc = '';
	var $imageFunc = '';

	//note ���صĴ�����Ϣ����
	var $upFile = array();

	//note ��̬��gifͼƬ������
	var $animatedGif = 0;

	//note ����ͼ���� 0Ϊ�ر� 1Ϊ����ָ����С������ͼ 2��ԭͼ����Ϊָ����С������ͼ
	var $thumbStatus = 1;

	//note ָ������ͼ�Ŀ�
	var $thumbWidth = 400;

	//note ָ������ͼ�ĸ�
	var $thumbHeight = 300;

	//note  jpeg �����ļ�����ͼ������������ΧΪ 0��100 ����������ֵԽ����ͼƬЧ��Խ��
	var $thumbQuality = 100;

	//note ����ͼ����·�� Ϊ�յ�ʱ���Դ�ļ�ͬĿ¼�� ָ����ʱ��ĩβ��Ҫ�� /
	var $thumbDir = '';

	//note ����ͼ�ķ�ʽ��0Ϊ��������Ŀ¼����ȫ��������һ��Ŀ¼��  1 ���·�ʽΪһ����Ŀ¼�洢 2 ���췽ʽΪһ����Ŀ¼�洢
	var $saveType = 1;

	//note ����ͼ����: �վ���Դ�ļ�������� .thumb.jpg,  random ����������ɣ� �����ľͰ���ָ������
	var $thumbName = '';

	//note 0 �ر�ˮӡ 1���� 2���� 3���� 4����  5����  6���� 7���� 8���� 9����
	var $waterMarkStatus = 0;

	//note 0 gif���͵��ļ���Ϊˮӡ 1 png���͵��ļ���Ϊˮӡ
	var $waterMarkType = 0;

	//note ˮӡ�ļ�·��
	var $waterImagePath = './../Moo-data/images/';

	//note ���ˮӡ�������� ԴͼƬ����С���
	var $waterMarkMinWidth = 0;

	//note ���ˮӡ��������ԴͼƬ����С�߶�
	var $waterMarkMinHeight = 0;

	//note ˮӡ�ں϶�, 1��100 ����������ֵԽ��ˮӡͼƬ͸����Խ��
	var $waterMarkTrans = 65;

	//note jpeg ���͵�ͼƬ���ˮӡ��������������ΧΪ 0��100 ����������ֵԽ����ͼƬЧ��Խ��
	var $waterMarkQuality = 100;

	//note �ж��Ƿ��Ѿ���������Ŀ¼����ֹ�ظ�����
	var $mkSubDirEd = false;

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
	 * ��ʼ������
	 *
	 * @param string $targetFile: ��Ҫ�����Ŀ��ͼƬ�ļ���ַ
	 * @param array $upFile: Ŀ��ͼƬ��һЩ�ļ���Ϣ
	 * @return void
	 */
	function image($targetFile, $upFile = array()) {
		$this->targetFile = $targetFile;
		$this->upFile = $upFile;
		$this->targetInfo = @getimagesize($targetFile);
		switch($this->targetInfo['mime']) {
			case 'image/jpeg':
				$this->imageCreateFromFunc = function_exists('imagecreatefromjpeg') ? 'imagecreatefromjpeg' : '';
				$this->imageFunc = function_exists('imagejpeg') ? 'imagejpeg' : '';
				break;
			case 'image/gif':
				$this->imageCreateFromFunc = function_exists('imagecreatefromgif') ? 'imagecreatefromgif' : '';
				$this->imageFunc = function_exists('imagegif') ? 'imagegif' : '';
				break;
			case 'image/png':
				$this->imageCreateFromFunc = function_exists('imagecreatefrompng') ? 'imagecreatefrompng' : '';
				$this->imageFunc = function_exists('imagepng') ? 'imagepng' : '';
				break;
		}

		$this->upFile['size'] = empty($this->upFile['size']) ? @filesize($targetFile) : $this->upFile['size'];
		if($this->targetInfo['mime'] == 'image/gif') {
			$fp = fopen($targetFile, 'rb');
			$targetFileContent = fread($fp, $this->upFile['size']);
			fclose($fp);
			$this->animatedGif = strpos($targetFileContent, 'NETSCAPE2.0') === FALSE ? 0 : 1;
		}
	}

	/**
	 * ����ָ����С��������ͼ��ʹ��GD�⣩
	 *
	 * @param int $thumbWidth: ָ������ͼ�Ŀ�
	 * @param int $thumbHeight: ָ������ͼ�ĸ�
	 * @return void
	 */
	function thumb($thumbWidth = '', $thumbHeight = '', $targetFile = '', $upFile = '') {

		if($targetFile) {
			$this->image($targetFile);
		}

		$thumbWidth = $thumbWidth ? $thumbWidth : $this->thumbWidth;
		$thumbHeight = $thumbHeight ? $thumbHeight : $this->thumbHeight;

		if($this->thumbStatus && function_exists('imagecreatetruecolor') && function_exists('imagecopyresampled') && function_exists('imagejpeg') && $this->imageCreateFromFunc && $this->imageFunc) {
			$imageCreateFromFunc = $this->imageCreateFromFunc;
			$imageFunc = $this->thumbStatus == 1 ? 'imagejpeg' : $this->imageFunc;
			list($imgWidth, $imgHeight) = $this->targetInfo;

			if( !$this->animatedGif ) {//ȡ������ͼ��С��ԭͼ��С��Χ�ڵ����� by dsk
			//if(!$this->animatedGif && ($imgWidth >= $thumbWidth || $imgHeight >= $thumbHeight)) {

				$upFilePhoto = $imageCreateFromFunc($this->targetFile);

				$XRation = $thumbWidth / $imgWidth;
				$YRation = $thumbHeight / $imgHeight;

				if(($XRation * $imgHeight) < $thumbHeight) {
					$thumb['height'] = ceil($XRation * $imgHeight);
					$thumb['width'] = $thumbWidth;
				} else {
					$thumb['width'] = ceil($YRation * $imgWidth);
					$thumb['height'] = $thumbHeight;
				}

				$this->thumbDir && $this->getSubDir();
				$thumbDir = $this->thumbDir ? $this->thumbDir : pathinfo($this->targetFile, PATHINFO_DIRNAME).'/';
				$thumbName = $this->thumbName ? ($this->thumbName == 'random' ? date("YmdHis").$this->random(10, 1).'.jpg' : $this->thumbName) : basename($this->targetFile).'.thumb.jpg';
				$targetFile = $this->thumbStatus == 1 ? $thumbDir.$thumbName : $this->targetFile;
				$thumbPhoto = imagecreatetruecolor($thumb['width'], $thumb['height']);
				imageCopyreSampled($thumbPhoto, $upFilePhoto ,0, 0, 0, 0, $thumb['width'], $thumb['height'], $imgWidth, $imgHeight);
				clearstatcache();
				if($this->targetInfo['mime'] == 'image/jpeg') {
					$imageFunc($thumbPhoto, $targetFile, $this->thumbQuality);
				} else {
					$imageFunc($thumbPhoto, $targetFile);
				}
				$this->upFile['thumbDir'] = str_replace(MOOPHP_ROOT.'/', '', $thumbDir);
				$this->upFile['thumbName'] = $thumbName;
				$this->upFile['thumbWidth'] = $thumb['width'];
				$this->upFile['thumbHeight'] = $thumb['height'];
				$this->upFile['thumb'] = $this->thumbStatus == 1 ? 1 : 0;
			}
		}else {
			return ;
		}

		if($this->thumbStatus == 2 && $this->waterMarkStatus) {
			$this->image($this->targetFile, $this->upFile);
			$this->upFile['thumb'] = 2;
			$this->upFile['thumbDir'] = pathinfo($this->targetFile, PATHINFO_DIRNAME).'/';
			$this->upFile['thumbName'] = basename($this->targetFile);
		}
		$this->upFile['size'] = filesize($this->targetFile);
	}

	/**
	 * ��ͼƬ����ˮӡ��ʹ��GD�⣩
	 * @return void
	 */
	function waterMark($targetFile = '' , $upFile = '') {

		if($targetFile) {
			$this->image($targetFile, $upFile);
		}

		if(($this->waterMarkMinWidth && $this->targetInfo[0] <= $this->waterMarkMinWidth && $this->waterMarkMinHeight && $this->targetInfo[1] <= $this->waterMarkMinHeight) || ($this->waterMarkType == 2 && (!file_exists($this->waterMarkText['fontpath']) || !is_file($this->waterMarkText['fontpath'])))) {
			return;
		}

		if($this->waterMarkStatus && function_exists('imagecopy') && function_exists('imagealphablending') && function_exists('imagecopymerge')) {
			$imageCreateFromFunc = $this->imageCreateFromFunc;
			$imageFunc = $this->imageFunc;
			list($imgWidth, $imgHeight) = $this->targetInfo;
			if($this->waterMarkType < 2) {
				$waterMarkFile = $this->waterMarkType == 1 ? MOOPHP_ROOT.'/'.$this->waterImagePath .'watermark.png' : MOOPHP_ROOT.'/'.$this->waterImagePath.'watermark.gif';

				$waterMarkInfo = @getimagesize($waterMarkFile);
				$waterMarkLogo = $this->waterMarkType == 1 ? @imageCreateFromPNG($waterMarkFile) : @imageCreateFromGIF($waterMarkFile);
				if(!$waterMarkLogo) {
					return;
				}
				list($logoWidth, $logoHeight) = $waterMarkInfo;
			}
			$wmwidth = $imgWidth - $logoWidth;
			$wmheight = $imgHeight - $logoHeight;

			if(($this->waterMarkType < 2 && is_readable($waterMarkFile) || $this->waterMarkType == 2) && $wmwidth > 10 && $wmheight > 10 && !$this->animatedGif) {
				switch($this->waterMarkStatus) {
					case 1:
						$x = +5;
						$y = +5;
						break;
					case 2:
						$x = ($imgWidth - $logoWidth) / 2;
						$y = +5;
						break;
					case 3:
						$x = $imgWidth - $logoWidth - 5;
						$y = +5;
						break;
					case 4:
						$x = +5;
						$y = ($imgHeight - $logoHeight) / 2;
						break;
					case 5:
						$x = ($imgWidth - $logoWidth) / 2;
						$y = ($imgHeight - $logoHeight) / 2;
						break;
					case 6:
						$x = $imgWidth - $logoWidth;
						$y = ($imgHeight - $logoHeight) / 2;
						break;
					case 7:
						$x = +5;
						$y = $imgHeight - $logoHeight - 5;
						break;
					case 8:
						$x = ($imgWidth - $logoWidth) / 2;
						$y = $imgHeight - $logoHeight - 5;
						break;
					case 9:
						$x = $imgWidth - $logoWidth - 5;
						$y = $imgHeight - $logoHeight - 5;
						break;
				}

				$destinationPhoto = imagecreatetruecolor($imgWidth, $imgHeight);
				$targetPhoto = @$imageCreateFromFunc($this->targetFile);
				imageCopy($destinationPhoto, $targetPhoto, 0, 0, 0, 0, $imgWidth, $imgHeight);

				if($this->waterMarkType == 1) {
					imageCopy($destinationPhoto, $waterMarkLogo, $x, $y, 0, 0, $logoWidth, $logoHeight);
				} else {
					imageAlphaBlending($waterMarkLogo, true);
					imageCopyMerge($destinationPhoto, $waterMarkLogo, $x, $y, 0, 0, $logoWidth, $logoHeight, $this->waterMarkTrans);
				}

				clearstatcache();
				if($this->targetInfo['mime'] == 'image/jpeg') {
					$imageFunc($destinationPhoto, $this->targetFile, $this->waterMarkQuality);
				} else {
					$imageFunc($destinationPhoto, $this->targetFile);
				}

				$this->upFile['size'] = filesize($this->targetFile);
			}
		}
	}

	/**
	 * ����ָ���洢�ķ�ʽȡ����������ͼ��Ŀ¼
	 *
	 * @return void
	 */
	function getSubDir() {

		if(empty($this->thumbDir)) {
			return ;
		}

		if($this->mkSubDirEd){
			return ;
		}

		$this->mkSubDirEd = true;

		if(!is_dir($this->thumbDir)) {
			mkdir($this->thumbDir, 0777);
			touch($this->thumbDir.'index.htm');
		}

		if($this->saveType == 1) {
			$this->thumbDir .= date('Ym').'/';
		}else if($this->saveType == 2) {
			$this->thumbDir .= date('Ymd').'/';
		}else {

		}

		if(!is_dir($this->thumbDir)) {
			mkdir($this->thumbDir, 0777);
			touch($this->thumbDir.'index.htm');
		}
	}

	/**
	 * ��������ַ�
	 * @param int $length  �ַ�����
	 * @param boolean $numeric  �Ƿ�������
	 *
	 * @return string
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

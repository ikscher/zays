<?php
/*
	More & Original PHP Framwork
	Copyright (c) 2007 - 2008 IsMole Inc.

	$Id: MooCategory.class.php 296 2008-06-03 03:22:26Z kimi $
*/


!defined('IN_MOOPHP') && exit('Access Denied');

class MooCategory {

	//note ���ݿ⴦����
	var $dbClass = '';

	//note ������
	var $cacheClass = '';

	//note ���ݿ�����
	var $dbName = 'moophp_categories';
	
	//note ���з����б�����
	var $cateArray = array();

	/**
	 * ȡ�����з�����Ϣ�б�
	 *
	 * @return array
	 */
	function getCateArray() {
		if(!$this->dbClass) {
			$this->dbClass = MooAutoLoad('MooMySQL');
		}

		$cateArray = array();
		$query = $this->dbClass->query("SELECT * FROM {$this->dbName}");
		while ($category = $this->dbClass->fetchArray($query)) {
			$cateArray[$category['cateid']] = $category;
		}

		return $cateArray;
	}

}

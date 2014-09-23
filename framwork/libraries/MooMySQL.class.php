<?php
/*
	More & Original PHP Framwork
	Copyright (c) 2007 - 2008 IsMole Inc.

	$Id: Mysql.Class.php 2008-3-19 aming$
*/


//!defined('IN_MOOPHP') && exit('Access Denied');

class MooMySQL {
	/**
     * 数据库配置信息
     */
    var $wdbConf = array();
    var $rdbConf = array();
    /**
     * Master数据库连接
     */
    var $wdbConn = null;
    /**
     * Slave数据库连接
     */
    var $rdbConn = array();
	/**
     * 初始化的时候是否要连接到数据库
     */
    var $isInitConn = false;
	/**
     * 是否查询出错的时候终止脚本执行
     */
    var $isExit = false;
	
	var $queryCount = 0;
	//var $conn;
	var $result;
	var $rsType = MYSQL_ASSOC;
	//note:锟斤拷询时锟斤拷
	var $queryTimes = 0;
	var $sql = null;
	/**
     * 初始化函数
     *
     * 传递配置信息，配置信息数组结构：
     * $masterConf = array(
     *        "host"    => Master数据库主机地址
     *        "user"    => 登录用户名
     *        "pwd"    => 登录密码
     *        "db"    => 默认连接的数据库
     *    );
     * $slaveConf = array(
     *        "host"    => Slave1数据库主机地址|Slave2数据库主机地址|...
     *        "user"    => 登录用户名
     *        "pwd"    => 登录密码
     *        "db"    => 默认连接的数据库
     *    );
     */
    function init($masterConf, $slaveConf=array()){
        //构造数据库配置信息
        if (is_array($masterConf) && !empty($masterConf)){
            $this->wdbConf = $masterConf;
        }
        if (!is_array($slaveConf) || empty($slaveConf)){
            $this->rdbConf = $masterConf;
        } else {
            $this->rdbConf = $slaveConf;
        }
        //初始化连接（一般不推荐）
        if ($this->isInitConn){
            $this->getDbWriteConn();
            $this->getDbReadConn();
        }
    }
	
	 /**
     * 获取Master的写数据连接
     */
    
    
   
    function getDbWriteConn(){
        //判断是否已经连接
        if ($this->wdbConn ) {
			if (!mysql_ping ($this->wdbConn)) {
				mysql_close($this->wdbConn);
			}else{
				return $this->wdbConn;
			}

		}
        //没有连接则自行处理
        $db = $this->connect($this->wdbConf['host'], $this->wdbConf['user'], $this->wdbConf['pwd'], $this->wdbConf['db']);
        if (!$db || !is_resource($db)) {
            return false;
        }
        $this->wdbConn = $db;
        return $this->wdbConn;
    }

    /**
     * 获取Slave的读数据连接
     */
    function getDbReadConn(){
        //如果有可用的Slave连接，随机挑选一台Slave
        if (is_array($this->rdbConn) && !empty($this->rdbConn)) {
            $key = array_rand($this->rdbConn);
            
            if (isset($this->rdbConn[$key]) && is_resource($this->rdbConn[$key])) {
            	
				if (!mysql_ping ($this->rdbConn[$key])) {
					mysql_close($this->rdbConn[$key]);
				}else{
					
					return $this->rdbConn[$key];
				}
            }
        }
        //连接到所有Slave数据库，如果没有可用的Slave机则调用Master
        $arrHost = explode("|", $this->rdbConf['host']);
        if (!is_array($arrHost) || empty($arrHost)){
            return $this->getDbWriteConn();
        }
        $this->rdbConn = array();
        foreach($arrHost as $tmpHost){
            $db = $this->connect($tmpHost, $this->rdbConf['user'], $this->rdbConf['pwd'], $this->rdbConf['db']);
            if ($db && is_resource($db)){
                $this->rdbConn[] = $db;
            }
        }
        
        //如果没有一台可用的Slave则调用Master
        if (!is_array($this->rdbConn) || empty($this->rdbConn)){
            //$this->errorLog("Not availability slave db connection, call master db connection");
            return $this->getDbWriteConn();
        }
        //随机在已连接的Slave机中选择一台
        $key = array_rand($this->rdbConn);
        if (isset($this->rdbConn[$key])  && is_resource($this->rdbConn[$key])){
        	//file_put_contents('sql_log.txt', $this->sql.'\n',FILE_APPEND);//note
            return $this->rdbConn[$key];
        }
        //如果选择的slave机器是无效的，并且可用的slave机器大于一台则循环遍历所有能用的slave机器
        if (count($this->rdbConn) > 1){
            foreach($this->rdbConn as $conn){
                if (is_resource($conn)){
                    return $conn;
                }
            }
        }
        //如果没有可用的Slave连接，则继续使用Master连接
        return $this->getDbWriteConn();
    }

	/**
	 * l锟斤拷锟斤拷菘锟?	 *
	 * @param string $dbHost
	 * @param string $dbName
	 * @param string $dbUser
	 * @param string $dbPass
	 * @param blooean $dbOpenType
	 * @param string $dbCharset
	 * @return void
	 */
	function connect($dbHost = '', $dbUser = '', $dbPass = '', $dbName = '', $dbOpenType = false ,$dbCharset = 'utf8') {
		if($dbOpenType) {
			if(!$conn = @mysql_pconnect($dbHost, $dbUser, $dbPass)) {
				$this->errorMsg('Can not connect to MySQL server');
			}
		} else {
			if(!$conn = @mysql_connect($dbHost, $dbUser, $dbPass)) {
				$this->errorMsg('Can not connect to MySQL server2');
			}
		}

		$mysqlVersion = $this->getMysqlVersion();

		if($mysqlVersion > '4.1') {
				global $charset, $dbCharset;
				$dbCharset = str_replace('-', '', !$dbCharset && in_array(strtolower($charset), array('gbk', 'big5', 'utf-8')) ? $charset : $dbCharset);
				$serverset = $dbCharset ? 'character_set_connection='.$dbCharset.', character_set_results='.$dbCharset.', character_set_client=binary' : '';
				$serverset .= $mysqlVersion > '5.0.1' ? ((empty($serverset) ? '' : ',').'sql_mode=\'\'') : '';
				$serverset && @mysql_query("SET $serverset", $conn);
		}

		@mysql_select_db($dbName, $conn);
		return $conn;
	}
	
	/**
     * 关闭数据库连接
     */
    function close($dbConn=null, $closeAll=true){
        //关闭指定数据库连接
        if ($dbConn && is_resource($dbConn)){
            mysql_close($dbConn);
            $dbConn = null;
        }
        //关闭所有数据库连接
        if (!$dbConn && $closeAll){
            if ($this->wdbConn && is_resource($this->wdbConn)){
                mysql_close($this->wdbConn);
                $this->wdbConn = null;
            }
            if (is_array($this->rdbConn) && !empty($this->rdbConn)){
                foreach($this->rdbConn as $conn){
                    if ($conn && is_resource($conn)){
                        mysql_close($conn);
                    }
                }
                $this->rdbConn = array();
            }
        }
        return true;
    }
	
	/**
	 * 锟斤拷锟酵诧拷询锟斤拷锟?	 *
	 * @param string $sql
	 * @param string $type
	 * @return blooean
	 */
	function query($sql, $isMaster=false, $type = "ASSOC") {
	    if(strpos($sql, 'sleep') !== false){ 
	    	return $this->errorMsg('SQL Isnnt Safty');
	    }
		if (trim($sql) == ""){
            $this->errorMsg("Sql query is empty.");
            return false;
        }
		if($_GET['debug']){
		$time = microtime();
		}
		global $debug, $timestamp, $sqldebug, $sqlspenttimes;

		if(MOOPHP_DEBUG) {
			global $_MooPHP;
			$sqlstarttime = $sqlendttime = 0;
			$mtime = explode(' ', microtime());
			$sqlstarttime = number_format(($mtime[1] + $mtime[0] - $_MooPHP['startTime']), 6) * 1000;
		}
		//$this->sql = $sql;//note
        //获取执行SQL的数据库连接
        if (!$isMaster){
        	$temp = explode(" ", ltrim($sql));	
            $optType = trim(strtolower(array_shift($temp)));
            unset($temp);
        }
		$queryDbType = 'Slave';
        if ($isMaster || $optType!="select"){
            $dbConn = $this->getDbWriteConn();
           
			$queryDbType = 'Master';
        } else {
        	
            $dbConn = $this->getDbReadConn();
           
        }
        if (!$dbConn || !is_resource($dbConn)){
            $this->errorMsg("Not availability db connection. Query SQL:".$sql);
            if ($this->isExit) {
                exit;
            }
            return false;
        }
		
		$this->rsType = $type != "ASSOC" ? ($type == "NUM" ? MYSQL_NUM : MYSQL_BOTH) : MYSQL_ASSOC;
		
		$this->result = @mysql_query($sql,$dbConn);
		$this->queryCount++;
		//error_log($sql, 0);
		if(MOOPHP_DEBUG) {
			$mtime = explode(' ', microtime());
			$sqlendttime = number_format(($mtime[1] + $mtime[0] - $_MooPHP['startTime']), 6) * 1000;
			$sqltime = round(($sqlendttime - $sqlstarttime), 3);

			$explain = array();
			$info = mysql_info();
			if(preg_match("/^(select )/i", $sql)) {
				$explain = mysql_fetch_assoc(mysql_query('EXPLAIN '.$sql, $dbConn));
			}
			$_MooPHP['debug_query'][] = array('sql'=>$sql, 'time'=>$sqltime, 'info'=>$info, 'explain'=>$explain);
		}
		
		if($_GET['debug']){
		$temp = (microtime()-$time)*1000;
		echo  "query|{$temp}"."|".$sql."<br/>";
		}
		if($this->result===false) {
			//return $this->errorMsg("Query sql failed in ".$queryDbType." DB. SQL:".$sql);
			return $this->result;
		} else {
			return $this->result;
		}
	}
	
	/**
	 * 锟斤拷锟?锟饺较达拷锟斤拷锟斤拷锟铰诧拷询
	 *
	 * @param string $sql
	 * @param string $type
	 * @return blooean
	 */
	function bigQuery($sql,$isMaster=false, $type = "ASSOC") {
		if (trim($sql) == ""){
            $this->errorMsg("Sql query is empty.");
            return false;
        }
		
		//获取执行SQL的数据库连接
        if (!$isMaster){
        	$temp = explode(" ", ltrim($sql));	
            $optType = trim(strtolower(array_shift($temp)));
            unset($temp);
        }
		$queryDbType = 'Slave';
        if ($isMaster || $optType!="select"){
            $dbConn = $this->getDbWriteConn();
			$queryDbType = 'Master';
        } else {
            $dbConn = $this->getDbReadConn();
        }
        if (!$dbConn || !is_resource($dbConn)){
            $this->errorMsg("Not availability db connection. Query SQL:".$sql);
            if ($this->isExit) {
                exit;
            }
            return false;
        }
		
		$this->rsType = $type != "ASSOC" ? ($type == "NUM" ? MYSQL_NUM : MYSQL_BOTH) : MYSQL_ASSOC;
		$this->result = @mysql_unbuffered_query($sql, $dbConn);
		$this->queryCount++;
		if($this->result===false) {
			return $this->errorMsg("Query sql failed in ".$queryDbType." DB. SQL:".$sql);
		}
		else {
			return $this->result;
		}
	}
	
	/**
	 * 锟斤拷取全锟斤拷锟斤拷锟?	 *
	 * @param string $sql
	 * @param blooean $nocache
	 * @return array
	 */
	function getAll($sql, $noCache = false, $domemcached = false, $memcachedtime = 0 ,$isMaster=false) {
		global $nomemcached;
		if($_GET['debug']){
		$time = microtime();
		$iscache = '<font style="color:#003300">mem</font>';
		}
		if(MOOPHP_ALLOW_MEMCACHED&!$nomemcached & $domemcached ) {
		    global $memcached,$memcachelife;
			$memcachelife = $memcachedtime?$memcachedtime:$memcachelife;
			$key = md5($sql);
			if(! ( $rows = $memcached->get($key) ) ) {
			    $noCache ? $this->bigQuery($sql) : $this->query($sql,$isMaster);
//				$rows = array();
				//如果this->result返回为null，赋值空数组
//				$this->result=$this->result?$this->result:array();
				if ($this->result) {
					while($row = mysql_fetch_array($this->result, $this->rsType)) {
						$rows[] = $row;
					}					
				}
			    $memcached->set($key, $rows , 0, $memcachelife);
					if($_GET['debug']){
				$iscache = "NO";
				}
			}
        }else{
			$noCache ? $this->bigQuery($sql) : $this->query($sql,$isMaster);
			$rows = array();
			//如果this->result返回为null，赋值空数组
//				$this->result=$this->result?$this->result:array();
			if ($this->result) {
				while($row = mysql_fetch_array($this->result, $this->rsType)) {
					if($_GET['debug']){
						$iscache = "NO";
					}
					$rows[] = $row;
				}
			}
		}
		if (!is_array($rows)) {
			$rows = array();
		}
		if($_GET['debug']){
		$time = (microtime()-$time)*1000;
		echo "<br />{$time}"."|{$iscache}|";
		}
		return $rows;
	}
	
	/**
	 * 锟斤拷取锟斤拷锟斤拷锟斤拷锟?	 *
	 * @param string $sql
	 * @return array
	 */
	function getOne($sql,$isMaster=false) {
	    if($_GET['debug']){
	       $time = microtime();
		}
		$this->query($sql,$isMaster);
		$rows = array();	
		//如果this->result返回为null，赋值空数组
//		$this->result=$this->result?$this->result:array();
		if ($this->result) {
			$rows = mysql_fetch_array($this->result, $this->rsType);
		}
		if (!is_array($rows)) {
			$rows = array();
		}
		if($_GET['debug']){
			$temp = (microtime()-$time)*1000;
			echo "<br />getOne|{$temp}|";
		}
		return $rows;
	}
	
	/**
	 * 锟接斤拷锟斤拷锟饺★拷锟揭伙拷锟斤拷锟轿拷锟絡锟斤拷锟介，锟斤拷锟斤拷锟斤拷锟斤拷锟斤拷
	 *
	 * @param resource $query
	 * @return array
	 */
	function fetchArray($query) {
		return mysql_fetch_array($query, $this->rsType);
	}

	/**
	 * 取锟矫斤拷锟斤拷锟斤拷
	 *
	 * @param resource $query
	 * @return string
	 */
	function result($query, $row) {
		$query = @mysql_result($query, $row);
		return $query;
	}

	/**
	 * 取锟斤拷锟斤拷一锟斤拷 INSERT 锟斤拷锟斤拷锟斤拷锟斤拷 ID 
	 *
	 * @return integer
	 */

	 function insertId() {
		return ($id = mysql_insert_id($this->wdbConn)) >= 0 ? $id : $this->result($this->query("SELECT last_insert_id()",1), 0);
	}
	
	/**
	 * 取锟斤拷锟叫碉拷锟斤拷目
	 *
	 * @param resource $query
	 * @return integer
	 */
	function numRows($query) {
	if($_GET['debug']){
	    echo '<font style="color:#FF0000">numRows</font><br />';
		}
		return mysql_num_rows($query);
	}
	
	/**
	 * 取锟矫斤拷锟斤拷锟斤拷侄蔚锟斤拷锟侥?	 *
	 * @param resource $query
	 * @return integer
	 */
	function numFields($query) {
		return mysql_num_fields($query);
	}
	
	/**
	 * 取锟斤拷前一锟斤拷 MySQL 锟斤拷锟斤拷锟斤拷影锟斤拷募锟铰硷拷锟斤拷锟?	 *
	 * 
	 * @return integer
	 */
	function affectedRows() {
		return mysql_affected_rows($this->wdbConn);
	}

	/**
	 * 取锟矫斤拷锟斤拷锟街革拷锟斤拷侄蔚锟斤拷侄锟斤拷锟?
	 *
	 * @param string $data
	 * @param string $table
	 * @return array
	 */
	function listFields($data, $table) {
		$row = mysql_list_fields($data, $table, $this->rdbConn);
		$count = mysql_num_fields($row);
		for($i = 0; $i < $count; $i++) {
			$rows[] = mysql_field_name($row, $i);
		}
		return $rows;
	}
	
	/**
	 * 锟叫筹拷锟斤拷菘锟斤拷械谋锟?	 *
	 * @param string $data
	 * @return array
	 */
	function listTables($data) {
		$query = mysql_list_tables($data);
		$rows = array();
		while($row = mysql_fetch_array($query)) {
			$rows[] = $row[0];
		}
		return $rows;
	}
	
	/**
	 * 取锟矫憋拷锟斤拷
	 *
	 * @param string $table_list
	 * @param integer $i
	 * @return string
	 */
	function tableName($table_list, $i) {
		return mysql_tablename($table_list, $i);
	}
	
	/**
	 * 转锟斤拷锟街凤拷锟斤拷锟节诧拷询
	 *
	 * @param string $char
	 * @return string
	 */
	function escapeString($char) {
		return mysql_escape_string($char);
	}
	
	/**
	 * 取锟斤拷锟斤拷菘锟芥本锟斤拷息
	 *
	 * @return string
	 */
	function getMysqlVersion() {
		return mysql_get_server_info();
	}

	/**
	 * 锟斤拷锟斤拷锟斤拷锟斤拷息
	 *
	 * @param string $msg
	 * @param string $sql
	 * @return void 
	 */
	function errorMsg($msg = '', $sql = '') {
		if($msg) {
			echo "<b>ErrorMsg</b>:".$msg."<br />";
		}
		if($sql) {
			echo "<b>SQL</b>:".htmlspecialchars($sql)."<br />";
		}
		echo "<b>Error</b>:  ".mysql_error()."<br />";
		echo "<b>Errno</b>: ".mysql_errno();
		
		echo '<br/>'.'writeConn-------'.'<br/>';
		var_dump($this->wdbConn);
		echo '<br/>'.'readConn--------'.'<br/>';
		var_dump($this->rdbConn);
		exit();
	}
	/** 刘艳强添加测试以后要删除*/
		function list_Fields($data, $table) {
	    $row = mysql_list_fields($data, $table, $this->wdbConn);
	    $count = mysql_num_fields($row);
	    for($i = 0; $i < $count; $i++) {
	        $rows[] = mysql_field_name($row, $i);
	    }
	    return $rows;
	}
	
	
}

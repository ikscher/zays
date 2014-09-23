<?php
require_once 'config.php';
class db_import{
	
	public $link;
	public $xml_file;
	public $insert_table_arr;
	public $curr_insert_table;//当前正在插入的表
	public $curr_src_table;//当前数据来源的表的数组
	public $curr_src_sql;//当前数据来源的sql
	public $count_src_sql;
	public $curr_count_arr;
	public $curr_insert_table_field;
	public $curr_insert_table_field_value;
	public $curr_fk;//当前来源表的外键关联信息
	public $start = 0;
	public $step;
	public $i;
	public $sql_file_path = 'sql/';
	public $dbhost,$dbuser,$dbpwd,$dbname;
	
	function __construct($xmlf,$s,$i=1){
		$this->setDbConfig();
		$this->link = mysql_connect($this->dbhost,$this->dbuser,$this->dbpwd) or die(mysql_error());
		mysql_select_db($this->dbname,$this->link) or die(mysql_error());
		mysql_query("set names utf8",$this->link);
		$this->xml_file = $xmlf;
		$this->start    = $s;
		$this->i        = $i;
	}
	
	//获取数据链接参数
	private function setDbConfig(){
		$this->dbhost 	= DBHOST;
		$this->dbuser	= DBUSER;
		$this->dbpwd	= DBPWD;
		$this->dbname	= DBNAME;
	}
	
	//解析xml
	public function myParseXml(){
		
		$this->insert_table_arr  = array();
		$xml  = simplexml_load_file($this->xml_file);
		$this->insert_table_arr = $xml->table;
	}
	
	//插入主函数
	public function insertMain(){
		
		$this->myParseXml();//第一步:解析xml
		foreach ($this->insert_table_arr as $table){//循环所有将要插入的表
			
			$this->curr_insert_table = $table;
			$this->step              = $this->curr_insert_table->step;
			$this->curr_src_table    = explode(',',$this->curr_insert_table->pretable);
			$this->curr_fk           = str_replace(',',' and ',$this->curr_insert_table->fk);
			$this->setCurrInsertTableField();
			$this->setCurrInsertTableFieldValue();
			
			echo "当前插入的表是：".$this->curr_insert_table->name,'<br/>';
			echo "当前表的数据将来自",implode(',',$this->curr_src_table),'<br/>';
			echo "正在获得从以上表得到数据的sql",'<br/>';
			$this->settSrcSql();
			//echo "获取数据的sql为",'<br/>',$this->curr_src_sql,'<br/>';
			echo '正在获取数目信息:<br/>';
			$this->getCount();
			echo '共',$this->curr_count_arr['total'],'条数据，所有数据将分为',$this->curr_count_arr['exe_total'],'次插入<br/>';
			echo '每次插入',$this->step,'条数据,最后一次插入',$this->curr_count_arr['last'],'条<br/>';
			$stmp = 0;
			while ($this->curr_count_arr['exe_total'] >= $this->i){
				        if ($this->i==$this->curr_count_arr['exe_total']){
				        	$this->step = $this->curr_count_arr['last'];
				        }
						echo '正在获取将要插入的数据的第',$this->start,'到第',$this->start+$this->step,'条','<br/>';
						$data = $this->getSrcData4Step();
						if ($data){
							echo '数据获取成功......','<br/>';
							echo '正在生成插入sql<br/>';
							$sql = $this->getInsertSql($data);
							echo 'sql生成成功....<br/>正在插入....<br/>';
							$this->writeSqlFile($sql, false);
							//$this->execQuery($sql);
						}else {
							exit('数据获取失败');
						}
						
						$this->i++;
						$stmp = $stmp+$this->step;
						/*if ($stmp >= $this->step*10){//每插入成功后中转一下
							
							echo "<script>","document.location.href='test.php?start=",$this->start,"&&submit=1&&curr_file=",$this->xml_file,"&&i=",$this->i,"'</script>";
							exit;
						}*/
			}
			$this->start = 0;
			echo $this->curr_insert_table->name,"表插入完成<br/><a href='index.php'>返回</a><br/>",$this->execTime($_SESSION['exec_time']);
		}
		
	}
	
	
	//调用shell 实现多线程
	public function shellMain(){
		
		$this->myParseXml();//第一步:解析xml
		foreach ($this->insert_table_arr as $table){//循环所有将要插入的表
			
			$this->curr_insert_table = $table;
			$this->step              = $this->curr_insert_table->step;
			$this->curr_src_table    = explode(',',$this->curr_insert_table->pretable);
			$this->curr_fk           = str_replace(',',' and ',$this->curr_insert_table->fk);
			$this->setCurrInsertTableField();
			$this->setCurrInsertTableFieldValue();
			
			echo "当前插入的表是：".$this->curr_insert_table->name,'<br/>';
			echo "当前表的数据将来自",implode(',',$this->curr_src_table),'<br/>';
			echo "正在获得从以上表得到数据的sql",'<br/>';
			$this->settSrcSql();
			//echo "获取数据的sql为",'<br/>',$this->curr_src_sql,'<br/>';
			echo '正在获取数目信息:<br/>';
			$this->getCount();
			echo '共',$this->curr_count_arr['total'],'条数据，所有数据将分为',$this->curr_count_arr['exe_total'],'次插入<br/>';
			echo '每次插入',$this->step,'条数据,最后一次插入',$this->curr_count_arr['last'],'条<br/>';
			//$stmp = 0;
			while ($this->curr_count_arr['exe_total'] >= $this->i){
				        if ($this->i==$this->curr_count_arr['exe_total']){
				        	$this->step = $this->curr_count_arr['last'];
				        }
						echo '正在获取将要插入的数据的第',$this->start,'到第',$this->start+$this->step,'条','<br/>';
						
						$output = array();
						$phpexec = $this->start .' '. $this->xml_file . ' '. $this->i;
						system('sh sh/callphp.sh '. $phpexec);
						//var_dump($return);
						//var_dump($output);
						echo $phpexec . '已经抛给后台执行。。。<br />';
						$this->start = $this->start+$this->step;
						
						//$data = $this->getSrcData4Step();
						//if ($data){
							///echo '数据获取成功......','<br/>';
							//echo '正在生成插入sql<br/>';
							//$sql = $this->getInsertSql($data);
							//echo 'sql生成成功....<br/>正在插入....<br/>';
							//$this->writeSqlFile($sql, $this->start);
							//$this->execQuery($sql);
						//}else {
							//exit('数据获取失败');
						//}
						
						$this->i++;
						//$stmp = $stmp+$this->step;
					/*	if ($stmp >= $this->step*10){//每插入成功后中转一下
							
							echo "<script>","document.location.href='test.php?start=",$this->start,"&&submit=1&&curr_file=",$this->xml_file,"&&i=",$this->i,"'</script>";
							exit;
						}*/
			}
			$this->start = 0;
			echo $this->curr_insert_table->name,"表插入完成<br/><a href='index.php'>返回</a><br/>",$this->execTime($_SESSION['exec_time']);
		}
		
	}
	
	//设置当前插入表的字段信息
	public function setCurrInsertTableField(){
		$this->curr_insert_table_field = '';
		
		foreach ($this->curr_insert_table->field as $field){
			$this->curr_insert_table_field .= $field->name.',';
		}
		$this->curr_insert_table_field = rtrim($this->curr_insert_table_field,',');
	}
	
	//执行时间
	public function execTime($time){
		$h = date('H',time()) > date('H',$time) ? date('H',time()) - date('H',$time) : 0;
		$i =  date('i',time()) > date('i',$time) ? date('i',time()) - date('i',$time) : 0;
		$s =  date('s',time()) > date('s',$time) ? date('s',time()) - date('s',$time) : 0;
		echo '程序执行消耗',$h,'小时',$i,'分钟',$s,'秒<br/>';
		unset($_SESSION['exec_time']);
	}
	
	//获取当前插入表的字段的来源和规则
	public function setCurrInsertTableFieldValue(){
		
		$this->curr_insert_table_field_value = array();
		foreach ($this->curr_insert_table->field as $field){
			$arr = array();
			$arr['from']  = str_replace('.','_',(string)$field->from);
			$arr['isint'] = (string)$field->isint;
			if (isset($field->rule)){
				$arr['rule'] = array('type'=>$field->rule->type,'value'=>$field->rule->value);
			}
			$this->curr_insert_table_field_value[] = $arr;
		}
		
	}
	
	//获取旧表的$step条数据的sql
	public function settSrcSql(){
		if (empty($this->curr_src_table))exit('没有数据来源表');
		if (count($this->curr_src_table)>1 && empty($this->curr_fk))exit('多表联合查询，没有关联信息');
		$this->curr_src_sql = "select ";
		foreach ($this->curr_src_table as $v){
			$field= array();
			$field= $this->getSrcField($v);
			$this->curr_src_sql .= implode(',',$field).",";
		}
		$this->curr_src_sql = rtrim($this->curr_src_sql,',');
		$this->curr_src_sql.= " from ".implode($this->curr_src_table,',')." where {$this->curr_fk}";
		$this->count_src_sql= "select count(1) "." from ".implode($this->curr_src_table,',')." where {$this->curr_fk}";;
		return $this->curr_src_sql; 
	}
	
	public function getCount(){
		$count = $this->getAll($this->count_src_sql);
		$this->curr_count_arr['total'] = $count[0]['count(1)'];
		$this->curr_count_arr['exe_total'] = ceil($this->curr_count_arr['total']/$this->step);
		$this->curr_count_arr['last']      = $this->curr_count_arr['total']%$this->step;
	}
	//获取旧表的$step条数据
	public function getSrcData4Step(){
		$sql  = $this->curr_src_sql." limit {$this->start},{$this->step}";
		$data = $this->getAll($sql);
		if ($data){
			return $data;
		}else {
			return false;
		}
		
	}
	
	//获取旧表的字段
	public function getSrcField($src_table){
		$sql  = "desc $src_table";
		$all  = $this->getAll($sql);
		$field= array();
		if ($all){
			foreach ($all as $v){
				$field[] = $src_table.".".$v['Field']." as ".$src_table."_".$v['Field'];
			}
		}else {
			return false;
		}
		return $field;
	}
	
	//获得插入的sql
	public function getInsertSql($src_data){
		//$src_data = $this->getSrcData4Step();
		$sql      = "insert into {$this->curr_insert_table->name} ({$this->curr_insert_table_field}) values";
		foreach ($src_data as $v){
			$sql .= "(";
			foreach ($this->curr_insert_table_field_value as $fv){
				if ($fv['isint'] == 1){
					$sql    .= isset($fv['rule']) ? "'".$this->filterRule($v,$fv['from'],$fv['rule'])."'," : "'".$v[$fv['from']]."',";//根据规则过滤或重新设置该字段的值
				}else {
					$sql    .= isset($fv['rule']) ? "'".mysql_escape_string($this->filterRule($v,$fv['from'],$fv['rule']))."'," : "'".mysql_escape_string($v[$fv['from']])."',";//根据规则过滤或重新设置该字段的值
				}
        	}
        	
        	$sql = rtrim($sql,',')."),\n";
		}
		$sql = rtrim($sql,",\n");
		//echo '插入sql为：<br/><pre>'.$sql.'</pre><br/>';
		return $sql.';';
	}
	
	
	//过滤字段值
	public function filterRule($data,$from,$rule){
//		if (empty($rule))return $value;
		switch ($rule['type']){
			case 1 : return $this->rule1($data[$from],$rule);break;
			case 2 : return $this->rule2($data,$from,$rule);break;
			case 3 : return $this->rule3($data,$from);break;
		}
	}
	
	//过滤
	public function rule1($value,$rule){
		$rule_value = explode(',',(string)$rule['value']);
		foreach ($rule_value as $v){
			list($k,$vv) = explode('->',$v);
			if ($k == $value) return $vv;
		}
		return $value;
	}
	
	//过滤
	public function rule2($data,$from,$rule){
		$rule_value = explode(',',$rule['value']);
		foreach ($rule_value as $v){
			list($k,$vv) = explode('->',$v);
			$vv = str_replace('.','_',$vv);
			if ($k == 'y') $year  = $data[$vv];
			if ($k == 'm') $month = $data[$vv];
			if ($k == 'd') $day   = $data[$vv];
		}
		return strtotime("$year/$month/$day");
	}
	
	public function rule3($data,$from){
		if (empty($data[$from])||$data[$from]=='-1'||$data[$from]=='0') return 0;
		$value = 0;
		$l_arr = explode(',',$data[$from]);
		foreach ($l_arr as $v){
			$v     = pow(2,$v-1);
			$value = $value|$v;
		}
		return $value;
	}
	
	//执行插入
	public function execQuery($insert_sql){
//		echo $insert_sql.'<br/>';
		$r = mysql_query($insert_sql,$this->link);
		$end = $this->start+$this->step;
		if ($r){
			echo "{$this->start}到{$end}条插入成功<br/><br/>";
			$this->start = $this->start + $this->step;
			
		}else {
			echo "{$this->start}到{$end}条插入失败<br/>";
			die(mysql_error($this->link));
		}
	}
	
	//把sql语句写入到文件
	public function writeSqlFile($sql, $append = true){
		$filename = $this->sql_file_path.$this->curr_insert_table->name.'/'.$this->curr_insert_table->name.'_'.$this->start.'_'.($this->start+$this->step).'.sql';
		$sql .= "\n"; 
		//判断文件是否存在 不存则要创建
		if($append){
			file_put_contents($filename, $sql, FILE_APPEND);
		}else{
			file_put_contents($filename, $sql);
		}
		if(!file_exists($filename)) die($filename.'此文件不可写');
		
		echo "{$this->start}到" . $this->start+$this->step ."条写入成功<br/><br/>";
		$this->start = $this->start + $this->step;
	}
	
	//获取sql数据
	public function getAll($sql){
		$result = mysql_query($sql,$this->link) or die(mysql_error());
		$all    = array();
		while ($row = mysql_fetch_assoc($result)){
			$all[] = $row;
		}
		if (empty($all)){
			return false;
		}else {
			return $all;
		}
	}
}
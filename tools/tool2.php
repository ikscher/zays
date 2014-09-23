<?php
class tool{
	
	public $link;
	
	function __construct($host,$user,$pwd,$dbname){
		$this->link = mysql_connect($host,$user,$pwd) or die(mysql_error());
		mysql_select_db($dbname,$this->link);
		mysql_query("set names utf8",$this->link);
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
	
	//获取旧表的字段
	public function getSrcField($src_table, $from_table, $fk, $step){
		$sql  = "desc $src_table";
		$all  = $this->getAll($sql);
		$from_arr = explode(',', $from_table);
		$desc_from = array();
		foreach($from_arr as $table){
			$sql  = "desc $table";
			$desc  = $this->getAll($sql);
			foreach($desc as $v){
				if(isset($desc_from[$v['Field']])) continue;
				$desc_from[$v['Field']] = $table;
			}
		}
		$field= '';
		$field_from_table = '';
		if ($all){
			foreach ($all as $v){
				$field .= "\t<field>\n";
				if (strpos($v['Type'],'int') !==false){
					$field .= "\t\t<isint>1</isint>\n";
				}
				$field_from_table = isset($desc_from[$v['Field']]) ? $desc_from[$v['Field']] : ''; 
				$field .= "\t\t<name>{$v['Field']}</name>\n\t\t<from>{$field_from_table}.{$v['Field']}</from>\n\t</field>\n";
			}
		}else {
			return false;
		}
		$table = <<<html
<root>
<config_name>从旧表{$from_table}中导入到新表{$src_table}</config_name>
<table>
	<name>{$src_table}</name><!-- 要插入数据的表 -->
	<pretable>{$from_table}</pretable><!-- 数据来源的表 -->
	<step>{$step}</step><!-- 一次插入多少条 -->
	<fk>{$fk}</fk><!-- 来源表关联 -->
	$field
</table>
</root>
html;
		return $table;
	}

}

$_GET['src_table'] = isset($_GET['src_table']) ? $_GET['src_table'] : '';
$_GET['from_table'] = isset($_GET['from_table']) ? $_GET['from_table'] : '';
$_GET['fk'] = isset($_GET['fk']) ? $_GET['fk'] : '';
$_GET['step'] = isset($_GET['step']) ? $_GET['step'] : '';


$rule = "rule
	\t<field>\n
			\t\t<isint>1</isint>\n
			\t\t<name>field1</name>\n
			\t\t<from>xxx</from>\n
			\t\t<rule>\n
			\t\t<type>2</type>\n
			\t\t<value>y->field1,m->field2,d->field3</value>\n
			\t\t</rule>\n
		\t</field>\n
";
$rule = htmlspecialchars($rule);

$rule2= '<field>
			<name>field2</name>
			<from>vote_content.cid</from>
			<rule>
			<type>1</type>
			<value>-1->1,0->1,1->2</value>
			</rule>
		</field>';

$rule2 = htmlspecialchars($rule2);

header("Content-type: text/html; charset=utf-8");
$html = <<<html
	<html>
	<body>
	本工具主要是用于生成xml格式的数据导入规则，用于红娘网数据表数据迁移<br>
	功能主要是：1，自成针对表字段的xml格式，<br>
	2,指定规则处理数据
	
	提示:<br />
		数据迁移中会有特殊数据的处理，所以我增加了以下规则，可以供你数据调整使用<br>
		规则1：<br>
		该规则实现目的：将旧表中的年，月，日三个字段合并成新表的一个字段，该字段存储年月日的时间戳<br>
			实现原理：y对应旧表中的存储年的时间戳，m对应旧表中的月存储字段,d代表旧表中的日存储字段<br>
		例：<br>
		$rule
		<br>
		规则2：<br >
		该规则实现目的：将旧表中的对应值转换成新表中的对应值，比如旧表中的-1在新表中存储为1<br>
			实现原理：将每一个值的对应用->分开，左边表示旧表中的值，右边表示新表中的值。比如-1->1 表示在旧表中的－1在新表中将作为1存储<br/>
		例：<br>
		$rule2
		<br>
	<form method="get">
		insert table: <input type="text" name="src_table" value="{$_GET['src_table']}"> 插入数据表 只能添写一个<br/>
		from table:<input type="text" name="from_table" value="{$_GET['from_table']}"> 插入数据来源表，多个以（半角）逗号分割开如：表1,表2<br/>
		Foreign key:<input type="text" name="fk" value="{$_GET['fk']}" >来源表的关联,单表使用默认值1，多个外键关联用（半角）逗号分开，如表1.id=表2.id,表2=表3<br/>
		step:<input type="text" name="step" value="{$_GET['step']}" >程序运行时每次插入条数
		<input type="submit">
		</form>
	</body>
	</html>
html;

echo $html;


if(!empty($_GET['src_table'])){
	$src_table = $_GET['src_table'];
	$from_table = $_GET['from_table'];
	$tool = new tool('localhost','root','root','hzn');
	$xml =  "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
	$xml .= $tool->getSrcField($src_table, $from_table, $_GET['fk'], $_GET['step']);
	echo '<textarea rows="50" cols="100">'.$xml.'</textarea>';
}
?>
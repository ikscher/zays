<?php
/**
 * sphinx search
 * sphinx查询封装类
 * 
 * @author likefei
 * @date 2011-11-09 10:16
 */
require_once('sphinxapi.php');
class search extends SphinxClient{
	var $offset=0;
	var $limit=1000;
	
	var $index;
	var $rs='';
	/**
	 * __construct()
	 * 构造函数
	 */
	public function __construct(){
		parent::__construct();
		parent::SetServer(SPH_HOST,9312);
		//parent::SetServer("localhost",9312);
		//parent::SetConnectTimeout(10);
		parent::SetMatchMode(SPH_MATCH_EXTENDED2);
		//parent::SetFieldWeights($this->weights);
		parent::SetRankingMode(SPH_RANK_WORDCOUNT);//
		parent::SetSortMode(SPH_SORT_EXTENDED,'@weight desc');
		parent::SetArrayResult(TRUE);
		$this->SetLimits($this->offset,$this->limit);
	}
	
	/**
	 * __destruct()
	 * 析构函数
	 */
	public function __destruct(){
	}
	
	/**
	 * set sphinx index
	 * 设置sphinx索引
	 */
	public function setIndex($index){
		
		if (strpos($index,'|')!==false){
			$index = str_replace('|',' ',$index);
		}else{
			$arr   = array('choice'=>'choice_delta','members_women'=>'members_women_delta','members_man'=>'members_man_delta');
			$temp  = explode(' ',$index);
			$index = '';
			foreach ($temp as $k=>&$v){
				if ($v == '')unset($temp[$k]);
				if (isset($arr[$v])) $v = $v.' '.$arr[$v];
			}
			$index = implode(' ',$temp);
		}
		if(!$index) $this->halt('The argv is null.',1001);
		$this->index = $index;
	}
	
	/**
	 * @param array $arr
	 * @return string $arr_str
	 * e.g. $arr = array(0=>456,1=>457,2=>458);
	 * return $arr_str = "(456,457,458)";
	 */
	public function ArrToStr($arr){
		if(!is_array($arr) || empty($arr)) $this -> halt('The argv must be an array and not null.',1002);
		$arr_str = "";
		foreach($arr as $v)
		{
			if($arr_str) $arr_str .= ",";
			$arr_str .= "'".$v."'";
		}
		return "(".$arr_str.")";
	}
	
	/**
	 * get id's
	 * 得到id数组
	 * @return $ids
	 * $ids = "(1,3,9)";
	 */
	public function getIds()
	{
		if(is_array($this->rs) && !empty($this->rs)){
			$arr = array();
			if(isset($this->rs['matches'])){
				foreach($this->rs['matches'] as $v){
					$arr[] = $v['id'];
				}
				return $arr;
			}else return array();
		}else if($this->rs == false){
			return array();
		}else $this->halt('Please search firstly!',1004);
	}
	
	
	/**
	 * get id's
	 * 得到id,s_cid,usertype数组
	 * @return $ids
	 * $ids = "(1,3,9)";
	 */
	public function getIdSidUType()
	{
		if(is_array($this->rs) && !empty($this->rs)){
			$arr = array();
			if(isset($this->rs['matches'])){
				foreach($this->rs['matches'] as $k=>$v){
					$arr[$k]['id'] = $v['id'];
					$arr[$k]['s_cid']=$v['attrs']['s_cid'];
					$arr[$k]['usertype']=$v['attrs']['usertype'];
				
				}
				return $arr;
			}else return array();
		}else if($this->rs == false){
			return array();
		}else $this->halt('Please search firstly!',1004);
	}
	
	
	/**
	 * set filter attr
	 * 设置过滤属性
	 * @param $filters
	 * $filters = array(
	 * 	0=>array("field" => "gender","val" => array(1),"exclude" => false),
	 *  1=>array("field" => "age","val" => 23,"val2" => 30,"exclude" => false),
	 *  2=>array("field" => "username","str" => array("123123@yahoo.cn","fl_dream@163.com"),"exclude" => false),
	 * );
	 */
	private function setFilters($filters){
		if(!is_array($filters) && empty($filters)) $this->halt('The argv must be an array and not null.',1002);
		foreach($filters as $k=>$v)
		{
			if(!isset($v['exclude']) || !$v['exclude']) $v['exclude'] = false;
			if(isset($v['val']) && is_array($v['val'])) parent::SetFilter($v['field'],$v['val'],$v['exclude']);
			else if(isset($v['val2']) && $v['val2']){
				if($v['val'] > $v['val2']){
					$t = $v['val2'];
					$v['val2'] = $v['val'];
					$v['val'] = $t;
				}
				parent::SetFilterRange($v['field'],$v['val'],$v['val2'],$v['exclude']);
			}else if(isset($v['str']) && $v['str']){
				if(isset($v['str']) && !empty($v['str'])){
					$str_arr = array();
					foreach($v['str'] as $val){
						$str_arr[] = sprintf("%u",crc32($val));
					}
					parent::SetFilter($v['field'],$str_arr,$v['exclude']);
				}
			}
		}
	}
	
	/**
	 * set group by attr
	 * 设置分组属性
	 * @param $group
	 * $group = array(
	 *   0=>array("attrname" => "gender","func" => "attr","sort" => "@group desc"),
	 * );
	 * @param $distinct
	 */
	private function setGroups($group,$distinct=''){
		if(!is_array($group) && empty($group)) $this->halt('The argv must be an array and not null.',1002);
		$func = array(
			'day' => SPH_GROUPBY_DAY,//按天分组
			'week' => SPH_GROUPBY_WEEK,//按周分组
			'month' => SPH_GROUPBY_MONTH,//按月分组
			'year' => SPH_GROUPBY_YEAR,//按年分组
			'attr' => SPH_GROUPBY_ATTR//按属性分组
		);
		foreach($group as $k=>$v)
		{
			parent::SetGroupBy($v['attrname'],$func[$v['func']],$v['sort']);
		}
		if($distinct) parent::SetGroupDistinct($distinct);
	}
	
	/**
	 * set sort by attr
	 * 设置排序属性
	 * @param $sorts
	 * $sorts = array(
	 *   "mode" => "attr_desc",
	 *   "field" => "s_cid"
	 * );
	 */
	private function setSorts($sorts){
		if(!is_array($sorts) && empty($sorts)) $this->halt('The argv must be an array and not null.',1002);
		$sortmode = array(
			'attr_desc' => SPH_SORT_ATTR_DESC,//按照属性倒叙
			'attr_asc' => SPH_SORT_ATTR_ASC,//按照属性升序
			'relevance' => SPH_SORT_RELEVANCE,//按照相关度排序
			'time_segments' => SPH_SORT_TIME_SEGMENTS,//先按时间段（最近一小时/天/周/月）降序，再按相关度降序
			'extended' => SPH_SORT_EXTENDED,//类似SQL的排序
			'expr' => SPH_SORT_EXPR,//按某个算术表达式排序
		);
		parent::SetSortMode($sortmode[$sorts['mode']],$sorts['field']);
	}
	
	/**
	 * set limit like mysql
	 * 设置类似mysql的limit
	 * @param $offset
	 * @param $limit
	 */
	private function setLimit($offset=0,$limit=20){
		$this -> SetLimits(intval($offset),intval($limit));
	}
	
	/**
	 * main function
	 * query and return result
	 * 查询并返回结果
	 * @param $query 查询规则,没有搜索内容默认空
	 * @param $limit 类似MySQL的limit
	 * $limit = array("offset"=>0,"limit"=>20);
	 * offset:偏移量
	 * limit:需要取多少条
	 * @param $sorts 排序条件
	 * $sorts = array(
	 *   "mode" => "attr_desc",
	 *   "field" => "s_cid"
	 * );
	 * mode：排序的模式,，参见本类setSorts()
	 * field：排序的字段
	 * @param $filter 过滤的条件
	 * $filters = array(
	 * 	0=>array("field" => "gender","val" => array(1),"exclude" => false),
	 *  1=>array("field" => "age","val" => 23,"val2" => 30,"exclude" => false),
	 *  2=>array("field" => "username","str" => array("123123@yahoo.cn","fl_dream@163.com"),"exclude" => false),
	 * );
	 * field:过滤的字段
	 * val：过滤单项或者过滤区间最低值
	 * val2:过滤区间时，最高值
	 * exclude:过滤还是排除，不设置默认为false
	 * @param $groups 分组条件
	 * $group = array(
	 *   0=>array("attrname" => "gender","func" => "attr","sort" => "@group desc"),
	 * );
	 * attrname：分组属性名
	 * func：分组的模式，参见本类setGroups()
	 * sort:分组后，排序方式
	 * 
	 * @return $rs 返回值
	 * 正确返回数据，如果没有匹配出值或者错误则返回false
	 */
	public function getResults($query='',$limit=null,$sorts=null,$filters=null,$groups=null){

		if(!$this->index) $this->halt('Please set sphinx index name!',1003);
		//limit
		if(is_array($limit) && !empty($limit)) $this->setLimit($limit['offset'],$limit['limit']);
		//sort
		if(is_array($sorts) && !empty($sorts)) $this->setSorts($sorts);
		//filter
		if(is_array($filters) && !empty($filters)) $this->setFilters($filters);
		//group
		if(is_array($groups) && !empty($groups)) $this->setGroups($groups);
	  
		$this->rs = parent::Query($query,$this->index);
		if(isset($this->rs['matches']) && !empty($this->rs['matches'])) 
			return $this->rs;
		else 
			return false;
	}
	
	/**
	 * get result or sub node of result
	 * 得到结果集或者结果集的子节点
	 * @param unknown_type $param
	 */
	public function getRs($param = ''){
		if($param) return  isset($this->rs[$param]) ? $this->rs[$param] : FALSE;
		return $this->rs;
	}
	
	/**
	 * get sphinx result,reset filter and group condition
	 * 得到查询结果，重置过滤和分组条件
	 * @param $query 查询字符串，关键词
	 * @param $limit 从什么地方开始取多少条
	 * @param $sorts 排序条件
	 * @param $filters 过滤条件
	 * @param $groups 分组条件
	 */
	public function getResultOfResets($query='',$limit=null,$sorts=null,$filters=null,$groups=null){
		$this -> resetAll();
		$rs = $this -> getResults($query,$limit,$sorts,$filters,$groups);
		$this -> resetAll();
		return $rs;
	}
	
	/**
	 * execute multiple queries
	 * 执行多次查询
	 * 没有这样的需求暂时先不写
	 */
	public function getMoreResult($index,$query='',$limit=null,$sorts=null,$filters=null,$groups=null){	
	}
	
	/**
	 * reset filter condition
	 * 重置条件
	 */
	public function resetFilter(){
		parent::ResetFilters();
	}
	
	/**
	 * reset group condition
	 * 重置分组条件
	 */
	public function resetGroup(){
		parent::ResetGroupBy();
	}
	
	/**
	 * reset all condition
	 * 重置所有条件
	 */
	public function resetAll(){
		$this->resetFilter();
		$this->resetGroup();
	}
	
	/**
	 * update attr value
	 * 更新属性值
	 * @param $fields 需要修改的字段
	 * $fields = array('age1','age2');
	 * @param $values 要修改的值
	 * $values = array(100004 => array(20,26));
	 * 意思是把uid为100004的数据的属性age1改成20，age2改成26
	 */
	public function updateAttr($fields,$values){
		$index = $this->index;
		return parent::UpdateAttributes($index,$fields,$values);
	}

	/**
	 * exit
	 * 出口
	 */
	private function halt($msg,$err_no = 0){
		if(is_int($err_no) && $err_no) $msg = "Code $err_no Error：".$msg;
		echo $msg;exit;
	}
}
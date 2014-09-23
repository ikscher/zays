<?php
require_once 'search.class.php';

define ('ISFIELD',1);//属性标识
define ('ISATTR',0);//字段标识
define('EXCLUDE_TRUE', true);//是排序搜索
define('EXCLUDE_FALSE',false);//不是排序搜索

class MooSearch extends search{
	
	public $queryStr    = '';
	public $filtersArr  = null;
	public $querytype   = false;//模糊搜索为true,精确搜索为false
	public $limit_arr   = null;
	public $sorts_arr   = null;
	public $groups_arr  = null;
	public $plus_arr    = array('occupation','religion','nation','constellation','animalyear','body','certification');
	
	function __construct(){
		parent::__construct();
	}
	/**
	 * 
	 * 设置查询类型:模糊搜索为true,精确搜索为false
	 * @param unknown_type $type
	 */
	public function setQueryType($type){
		$this->querytype = $type;
	}
	
	/**
	 * 	$arr = array(
		array('marriage','4,5,6'),//单值／多值－－－》且逻辑(查询同时满足所有条件的结果)
		array('city','1|2|3',EXCLUDE_FALSE),／／单值／多值－－》或逻辑(查询满足其中一个条件的结果)
		array('province',array(1,2),EXCLUDE_FALSE),／／区间属性
		);
	 *
	 * @param unknown_type $arr
	 */
	public function parsePramas($arr){
		if (empty($arr)) return;
		
		$config = $this->get_index_config();
      
		$this->queryStr   = '';
		$this->filtersArr = array();
  
		foreach ($arr as $v){
			$this->plus_operate($v);//加100操作
			if (isset($config[$v[0]])&&($config[$v[0]] == ISFIELD)){
    
				$this->getQueryStr("@{$v[0]} ({$v[1]})");
			}else {
				if (is_array($v[1])){
					$this->getFiltersArr($v,2);
				}else {
					if ($v[0] == 'username'){
						$this->getFiltersArr($v,3);
					}else {
                                               
						$this->getFiltersArr($v,1);
					}
				}
			}
		}
		
	}
	
	/**
	 * 
	 * 将字段值加100
	 * @param unknown_type $v
	 */
	public function plus_operate(&$v){
		if (empty($v))return;
		$temp = array();

		if (in_array($v[0],$this->plus_arr)){
			if (strstr($v[1],'|') !=false){
				$temp = explode('|',$v[1]);
				foreach ($temp as &$vv){
					$vv = 100+$vv;
				}
				$v[1] = implode('|',$temp);
			}elseif (strstr($v[1],',') !=false){
				$temp = explode(',',$v[1]);
				foreach ($temp as &$vv){
					$vv = 100+$vv;
				}
				$v[1] = implode(',',$temp);
			}elseif (is_array($v[1])){
				foreach ($v[1] as &$vv){
					$vv = 100+$vv;
				}
			}
		}
	}
	
	/**
	 * 
	 * 解析查询字符串
	 * @param string $str
	 */
	public function getQueryStr($str){
		if ($this->querytype){
			$this->queryStr .= $str.'|';
		}else{
			$this->queryStr .= $str.' ';
		}
	}


	public function getFiltersArr($attr,$type){
		$arr           = array();
		$arr['field']  = $attr[0];
		$arr['exclude']= isset( $attr[2]) ?  $attr[2] : false;
		switch ($type){
			case 1:
				$temp = explode('|',$attr[1]);
                               
				foreach ($temp as &$v){
                                      
					$v =intval($v);
                                    
				}
                              
				$arr['val']    = $temp;
				break;
			case 2:
				list($val1,$val2) = $attr[1];
				$arr['val']    = intval($val1);
				$arr['val2']   = intval($val2);
				break;
			case 3:
				$arr['str']    = explode('|',$attr[1]);
				break;
				
		}
       
		$this->filtersArr[] = $arr;
		
	}
	/**
	 * 
	 * 原来的：$limit = array("offset"=>0,"limit"=>20);
	 * 现在的：
	 * $limit = array(0,20);
	 * $limit = array(20);
	 * $limit = array("offset"=>0,"limit"=>20);
	 * @param unknown_type $arr
	 */
	public function parseLimit($limit){
		if (empty($limit)) return;
		$this->limit_arr = array();
		$arr = array_values($limit);
		if (count($arr)>1){
			$this->limit_arr = array('offset'=>$arr[0],'limit'=>$arr[1]);
		}else {
			$this->limit_arr = array('offset'=>0,'limit'=>$arr[0]);
		}
		
	}
	
	/**
	 * @param $sorts
	 * 原来的：$sorts = array( "mode" => "attr_desc", "field" => "s_cid");
	 * 现在的：$sorts = "s_cid";
	 */
	public function parseSort($sort){
		if (empty($sort)) return;
		$this->sorts_arr = array();
		$this->sorts_arr = array('mode'=>'extended','field'=>$sort);
	}
	
	/**
	 * set group by attr
	 * 设置分组属性
	 * @param $group
	 * 原来的：$group = array( 0=>array("attrname" => "gender","func" => "attr","sort" => "@group desc"));
	 * 现在的：$group = array(array("gender","@group desc"));
	 * @param $distinct
	 */
	public function parseGroupBy($group){
		if (empty($group)) return;
		$this->groups_arr = array();
		foreach ($group as $v){
			$this->groups_arr[] = array('attrname'=>$v[0],'func'=>'attr','sort'=>$v[1]);
		}
	}
	
	/**
	 * 重载父类getResult（）方法，把原先的query参数和filters参数合并成一个数组传入
	 * 数组格式为：
	 * 	$arr = array(
		array('marriage','4'),//字段搜索
		array('city','1,2,3',EXCLUDE_FALSE),／／单值或多值属性
		array('province',array(1,2),EXCLUDE_FALSE),／／区间属性
		);
		每个字段对应一个数组元素，如果是查询索引字段，则第一个元素为索引字段名，第二个元素为查询值 eg:array('marriage','4')
		如果字段是属性，则，元素一为字段名，元素二为字段值，元素三为是否为排序搜索，值为true或false，由EXCLUDE_开头的常量表示
		调用该方法前请先设置查询类型
		$this->setQueryType($type);
		使用实例：
		$arr1 = array(array('s_cid','0'));//单值
		$arr2 = array(array('s_cid','0,1,2'));//多值
		$arr3 = array(array('s_cid',array('0','2')));//区间
		$arr4 = array(array('username','fl_dream@163.com,139580051@qq.com'));//用户名搜索
		$arr5 = array(array('marriage','4'),array('s_cid',array('0','2')));//字段，属性 搜索
		//$cl->setQueryType(true);
		$rs  = $cl->getResult($arr5);
	 * @see search::getResult()
	 */
	public function getResult($arr,$limit=null,$sorts=null,$groups=null){
		
		$this->parsePramas($arr);

		$this->parseLimit($limit);
		$this->parseSort($sorts);
		$this->parseGroupBy($groups);
        echo $this->queryStr;
		return parent::getResults(rtrim($this->queryStr,'|'),$this->limit_arr,$this->sorts_arr,$this->filtersArr,$this->groups_arr);
	}
	
	public function getResultOfReset($arr,$limit=null,$sorts=null,$groups=null){
		$this->resetAll();
		$rs = $this->getResult($arr,$limit,$sorts,$groups);
		$this->resetAll();
		
		return $rs;
	}
	
	/*
	 * 更新索引属性值
	 * 
	 * 单条更新或批量更新
	 * ＠param array $index_data
	 * $index_data = array(主键=>数据数组)
	 * 例：
	 * ex1:$index_data = array('10010'=>array('username'=>'nihao@163.com', s_cid='10'));
	 * ex2:$index_data = array(
	 * 			'10010'=>array('username'=>'nihao@163.com', s_cid='10'),
	 * 			'10011'=>array('username'=>'nihao@gmail.com', s_cid='40', 'height'=>120)
	 * 			);
	 * @return 更新成功返回更新的条数，字段错误返回-1,更新数据不存在返回0
	 */
//	public function UpdateAttributes($index_data){
//		
//		if(empty($index_data)) return;
//		
//		$config = $this->get_index_config();
//		
//		$result = array();
//		foreach($index_data as $index_id=>$data){
//			
//			$fields = $values = array();
//			foreach($data as $key=>$value){
//				if($config[$key] === ISATTR){
//					//用户名做特殊处理
//					if($key == 'username') $value = sprintf("%u",crc32($value)); 
//					
//					$fields[] = $key;
//					$values[] = intval($value); //转换成整型
//				}
//			}
//			
//			if(empty($fields)) continue;
//						
//			$result[$index_id] = parent::updateAttr($fields, array($index_id=>$values));
//		}	
//		
//		return $result;
//	}
	
	private function get_index_config(){
						
		$config_key = $this->index == 'choice choice_delta' ? 'choice_config' : 'member_config';
		$config = $this->index_config();
		
		return $config[$config_key];
	}
	
	private function  index_config(){
	  	$index_config = array();
	  	
		$index_config['member_config'] = array(
		'marriage'=>ISFIELD,
		'education'=>ISFIELD,
		'salary'=>ISFIELD,
		'house'=>ISFIELD,
		'children'=>ISFIELD,
		'body'=>ISFIELD,
		'animalyear'=>ISFIELD,
		'constellation'=>ISFIELD,
		'bloodtype'=>ISFIELD,
		'hometownprovince'=>ISFIELD,
		'hometowncity'=>ISFIELD,
		'nation'=>ISFIELD,
		'religion'=>ISFIELD,
		'family'=>ISFIELD,
		'language'=>ISFIELD,
		'smoking'=>ISFIELD,
	    'nickname'=>ISFIELD,
	   'nickname2'=>ISFIELD,
	   'truename'=>ISFIELD,
		'drinking'=>ISFIELD,
		'occupation'=>ISFIELD,
		'vehicle'=>ISFIELD,
		'corptype'=>ISFIELD,
		'wantchildren'=>ISFIELD,
	    'username' =>ISATTR,
      'birthyear' =>ISATTR,
      'gender' =>ISATTR,
      'province' =>ISATTR,
      'city' =>ISATTR,
      'height' =>ISATTR,
      's_cid' =>ISATTR,
      'bgtime' =>ISATTR,
      'images_ischeck' =>ISATTR,
      'is_lock' =>ISATTR,
      'pic_num' =>ISATTR,
      'city_star' =>ISATTR,
      'certification' =>ISATTR,
      'weight' =>ISATTR,
      'usertype' =>ISATTR,
      'regdate' =>ISATTR,
      'sid' =>ISATTR,
      'is_well_user' =>ISATTR,
      'telphone' =>ISATTR,
		'is_vote'=>ISATTR,
		'showinformation'=>ISATTR,
		'endtime'=>ISATTR
	);
	
	$index_config['choice_config'] = array(
		'a_uid'=>ISFIELD,
		'marriage'=>ISFIELD,
		'education'=>ISFIELD,
		'salary'=>ISFIELD,
		'children'=>ISFIELD,
		'hasphoto'=>ISFIELD,
		'nature'=>ISFIELD,
		'body'=>ISFIELD,
		'occupation'=>ISFIELD,
		'hometownprovince'=>ISFIELD,
		'hometowncity'=>ISFIELD,
		'nation'=>ISFIELD,
		'wantchildren'=>ISFIELD,
		'drinking'=>ISFIELD,
		'smoking'=>ISFIELD,
      'gender' =>ISATTR,
      'age1' =>ISATTR,
      'age2' =>ISATTR,
      'workprovince' =>ISATTR,
      'workcity' =>ISATTR,
      'height1' =>ISATTR,
      'height2' =>ISATTR,
      'weight1' =>ISATTR,
      'weight2' =>ISATTR
	);
	
	return $index_config;
	
	}
	
}
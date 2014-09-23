<?php
/*******************************************逻辑层(M)/表现层(V)*****************************************/
//note 欢迎界面
function admin_index(){
	global $type_list,$chang_user;
	$str = '';
	foreach($type_list as $key=>$type){
		$sub_str = '';
		foreach($type['subname'] as $key2=>$subname){
			$i = $key2+1;
			$sub_str .= "{'menuid':'{$i}','menuname':'{$subname['title']}','icon':'icon-nav','url':'{$subname['url']}'},";
		}
		$sub_str = substr($sub_str,0,-1);
		$j = 'a_'.$key;
		$str .= "{'menuid':'{$j}','icon':'icon-sys','menuname':'{$type['typename']}','menus':[".$sub_str."]},";
		
	}
	$str = substr($str,0,-1);
//print_r($str);exit;
	$groupid = $GLOBALS['groupid'];
	if(in_array($groupid,$GLOBALS['admin_service_pre'])) $pass = "no";
	else $pass = "yes";
    require_once(adminTemplate('admin_index'));
}
/***********************************************控制层(C)*****************************************/
//左侧分类列表
$type_list = array();

//登录组应该显示的菜单,即大分类
$allow_nav = returnNav();

//返回允许的方法,即子分类
$allow_action_list = returnAction();

//权限拥有的左侧列表
foreach($allow_nav as $k=>$v1){
	if(isset($leftMenu[$v1])){
		foreach($leftMenu[$v1] as $k2 => $v2){
			foreach($allow_action_list as $k3=>$v3){
				if($k2 != $v3){
					unset($leftMenu[$k2]);
				}else{
					$type_list[$k]['typename'] = $menu_nav_arr[$v1];	//大分类
					$type_list[$k]['subname'][$k3]['title'] = $leftMenu[$v1][$v3]['title']; //子分类
					$type_list[$k]['subname'][$k3]['url'] = $leftMenu[$v1][$v3]['url']; //子分类链接
				}
				
			}
		}
	}
}

/*
foreach($allow_nav as $k=>$v1){
	foreach($allow_action_list as $k2=>$v2){
		if(strpos($v2,$v1) !== false){
			$type_list[$k]['typename'] = $menu_nav_arr[$v1];	//大分类
			if(isset($leftMenu[$v1][$v2])){
				$type_list[$k]['subname'][$k2]['title'] = $leftMenu[$v1][$v2]['title']; //子分类
				$type_list[$k]['subname'][$k2]['url'] = $leftMenu[$v1][$v2]['url']; //子分类链接
			}
		}
	}
}*/

//print_r($type_list);exit;

//后台客服缓存文件不存在时，这里再次判断创建
if(!file_exists($file_kefulist_cache)){
	create_kefuuser_cachefile();
}

$h=MooGetGPC('h','string')=='' ? 'index' : MooGetGPC('h','string');
//note 动作列表
$hlist = array('index');
//note 判断页面是否存在
if(!in_array($h,$hlist)){
    MooMessage('您要打开的页面不存在','index.php?action=admin&h=index');
}
switch($h){
    //note 欢迎界面
    case 'index':
    	admin_index();
    break;
    default:
    	admin_index();
    break;
}
?>
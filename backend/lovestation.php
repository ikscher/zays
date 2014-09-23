<?php
/**
 * 获得Ajax分页数据
 * @param string $sql 需要包含 count(*) as count
 * @param integer $page_per default:10
 * @return array array(0)分页数据 array(1)sql偏移数据
 */
function ajaxpaging($sql, $page_per=10) {
	$c = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	$total = $c['count'];
	$page_num = ceil($total / $page_per);	
	$page = max(1, min(MooGetGPC('page', 'integer', 'G'), $page_num));
	$offset = ($page - 1) * $page_per;
	$pages = multipage($total, $page_per, $page, '#currenturl#');
	$pages = preg_replace('/#currenturl#\?page=(\d*)/', 'javascript:gotoPage($1)', $pages);	
	return array($pages, "$offset, $page_per");
}

/**
 * 疯狂粉丝会 
 */
function crazyfan() {
	$tb_pre = $GLOBALS['dbTablePre'];
	$db = $GLOBALS['_MooClass']['MooMySQL'];
	// $scid_list = array(10 => '铂金', 20 => '钻石', 30 => '高级', 40 => '普通', 50 => '红娘');
	$index = MooGetGPC('index', 'integer', 'G'); // 标示 哪个标签被点击 需要加粗显示
	$n = MooGetGPC('n', 'string', 'G');
	$year = (int)date('Y');
	$month = (int)date('m');
	
	switch ($n) {
		case 'adduid': // 推荐
			$adduid = MooGetGPC('adduid', 'integer', 'G');
			$user = $db->getOne("select count(*) as count, sort, score from {$tb_pre}carcrazyfan where uid=$adduid limit 1");
			if ($user['count'] == 1) { // 已存在，更改标记
				if ($user['sort'] == 0) {
					$score = unserialize($user['score']);
					$cur_score = $score[$year][$month];
					if ($cur_score === null) {
						$cur_score = 0;
						$score[$year][$month] = 0;
						$score = addslashes(serialize($score));
						$db->query("update {$tb_pre}carcrazyfan set sort=1, score='$score' where uid=$adduid");
					} else {
						$db->query("update {$tb_pre}carcrazyfan set sort=1 where uid=$adduid");
					}
				} else {
					exit('isadd');
				}
			} else { // 不存在，添加新纪录
				$userinfo = $db->getOne("select gender, is_lock, images_ischeck, nickname from {$tb_pre}members_search where uid=$adduid limit 1");
				if (empty($userinfo) || $userinfo['is_lock'] == 0) {
					exit('nouser');
				} elseif ($userinfo['images_ischeck'] != 1) {
					exit('nopic');
				} else {
					$time = time();
					$cur_score = 0;
					$score = addslashes(serialize(array($year => array($month => 0))));
					$db->query("insert into {$tb_pre}carcrazyfan(uid, gender,nickname, sort, recommend_time, score) values($adduid, {$userinfo['gender']}, '{$userinfo['nickname']}', 1, $time, '$score')");
				}
			}
			break;
		case 'delete': // 取消推荐
			$uid = MooGetGPC('uid', 'integer', 'G');
			$db->query("update {$tb_pre}carcrazyfan set sort=0 where uid=$uid");
			break;
		case 'history': // 历史推荐会员列表
			$ajaxp = ajaxpaging("select count(*) as count from {$tb_pre}carcrazyfan where sort=0");
			$pages = $ajaxp[0];
			$currenturl = "index.php?action=lovestation&h=crazyfan&n=history&ajax=";

			$user_list = $db->getAll("select id,uid,nickname,gender, score, recommend_time from {$tb_pre}carcrazyfan where sort = 0 order by recommend_time desc limit {$ajaxp[1]}");
			foreach ($user_list as $k => $user) {
				$score = unserialize($user['score']);
				$score = $score[$year][$month];
				$user_list[$k]['score'] = (int)$score;
			}
			break;
			case 'getimg': // 获取用户头像
				$uid = MooGetGPC('uid', 'integer', 'G');
				if ($uid > 0) { echo 'http://www.7651.com/' . MooGetphoto($uid); }
				exit;
				break;
	}
	if ($n != 'history') {
		$user_list = $db->getAll("select id,uid,nickname,score, gender,recommend_time from {$tb_pre}carcrazyfan where sort > 0 order by gender");
		foreach ($user_list as $key => $user) {
			$score = unserialize($user['score']);
			$cur_score = $score[$year][$month];
			if ($cur_score === null) {
                $cur_score = 0;
                $score[$year][$month] = 0;
                $score = addslashes(serialize($score));
                $db->query("update {$tb_pre}carcrazyfan set score='$score' where uid={$user['uid']}");
            }
            $user_list[$key]['score'] = $cur_score;
		}
	}
	require adminTemplate('lovestation_crazyfan');
	exit;
}

/**
 * 用户推荐表
 */
function recommend_list() {
	$db = $GLOBALS['_MooClass']['MooMySQL'];
	$tb_pre = $GLOBALS['dbTablePre'];
	
	$n = MooGetGPC('n', 'string', 'G');
	$uid = MooGetGPC('uid', 'integer', 'G');
	$ruid = MooGetGPC('ruid', 'integer', 'G');
	
	if ($n == 'delete') {
		$id = MooGetGPC('id', 'integer', 'G');
		$db->query("delete from {$tb_pre}carrecommend where id=$id");
	}
	$condition = array();
	if (!empty($uid)) { $condition[] = "uid=$uid"; }
	if (!empty($ruid)) { $condition[] = "ruid=$ruid"; }

	$sql_condition = implode(' and ', $condition);
	if (!$sql_condition) {
		$sql_condition = 'true';
	}
	
	$ajaxp = ajaxpaging("select count(*) as count from {$tb_pre}carrecommend where {$sql_condition}");
	$pages = $ajaxp[0];
	$currenturl = "index.php?action=lovestation&h=recommend_list&ajax=&uid=$uid&ruid=$ruid";
		
	$sql = "select * from {$tb_pre}carrecommend where {$sql_condition} limit {$ajaxp[1]}";
	$recommend_list = $db->getAll($sql);
	
	require adminTemplate('lovestation_recommend_list');
	exit;
}

/**
 * 红娘爱车会 
 */
function hnlove() {
	$tb_pre = $GLOBALS['dbTablePre'];
	$db = $GLOBALS['_MooClass']['MooMySQL'];
	$dirname = '../module/lovestation/templates/default/images/carimg/'; // 车型图片存储路径
	
	$n = MooGetGPC('n', 'string', 'G');
	$id = MooGetGPC('id', 'integer', 'G');
	$carname = MooGetGPC('carname', 'string');
	$isnoimg = MooGetGPC('noimg', 'string', 'G');
	
	$condition = array();
	if (!empty($carname)) { $condition[] = "car='%$carname%'"; }
	if ($isnoimg == 'on') { $condition[] = "imgurl = ''"; }
	$sql_condition = implode(' and ', $condition);
	if (!$sql_condition) {
		$sql_condition = 'true';
	}
	switch ($n) {
		case 'changeshow': // 更改是否显示
			$isdisplay = MooGetGPC('isshow', 'integer', 'G');
			$db->query("update {$tb_pre}carhnlove set isdisplay=$isdisplay where id=$id");
			exit('ok');
			break;
		case 'delete': // 删除车型
			$car = $db->getOne("select imgurl from {$tb_pre}carhnlove where id=$id");
			if (!empty($car['imgurl'])) {
				$img_arr = explode('||', $car['imgurl']);
				foreach ($img_arr as $imgurl) { // 删除车型相关的图片
					//unlink($dirname . $imgurl);
				}
			}
			$db->query("delete from {$tb_pre}carhnlove where id=$id");
			exit('ok');
			break;
		case 'checkname': // 检查车型名是否已存在
			if (!empty($carname)) {
				$a = $db->getOne("select count(*) as count from {$tb_pre}carhnlove where car='$carname' and id != $id");
				if ($a['count'] == 0) {
					echo 'ok';
				}
			}
			exit;
			break;
		case 'delimg':
			$index = explode(',', trim(MooGetGPC('index', 'string', 'G'), ',')); // 需要删除的图片序号数组
			$car = $db->getOne("select imgurl from {$tb_pre}carhnlove where id=$id");
			if (!empty($car['imgurl'])) {
				$img_arr = explode('||', $car['imgurl']);
				foreach ($index as $v) {
					$v = (int)$v - 1;
					if (isset($img_arr[$v])) {
						//unlink($dirname . $img_arr[$v]);
						unset($img_arr[$v]);
					}
				}
				$imgurl = implode('||', $img_arr);
				$db->query("update {$tb_pre}carhnlove set imgurl='$imgurl' where id=$id");
			}
			break;
		case 'addcar': // 添加编辑车型
			if (!empty($_POST)) {
				$carinfo = MooGetGPC('carinfo', 'string', 'P');
				$permit_imgtype = array('gif', 'jpg', 'jpeg', 'png', 'bmp');
				
				$a = $db->getOne("select count(*) as count from {$tb_pre}carhnlove where car='$carname' and id != $id");
				if ($a['count'] > 0) {
					exit('重复的车型名');
				}
/*				if (!is_dir($dirname)) {
					MooMakeDir($dirname);
				}
*/				$imgurl_arr = array();
				$car_arr = $_FILES['carimg'];
				$num = count($car_arr['name']);
				$alert_msg = '保存成功！';
				
				for ($i = 0; $i < $num; ++$i) { // 上传图片	
					$ext = strtolower(pathinfo($car_arr['name'][$i], PATHINFO_EXTENSION));
					if (in_array($ext, $permit_imgtype)) {
						$imgurl_arr[] = substr($dirname, 3) . $car_arr['name'][$i]; // 存储到数据库的路径是相对于网站根目录
					}
/*					if (is_uploaded_file($car_arr['tmp_name'][$i]) && $car_arr['error'][$i] == 0) {
						$ext = strtolower(pathinfo($car_arr['name'][$i], PATHINFO_EXTENSION)); // 小写扩展名
						
						if (in_array($ext, $permit_imgtype)) {
							if ($car_arr['size'][$i] < 1024*1024) { // 文件最大1M
								$name = md5_file($car_arr['tmp_name'][$i]) . '.' .$ext;
								$imgurl_arr[] = substr($dirname, 3) . $name;
								if (!file_exists($dirname .$name)) {
									move_uploaded_file($car_arr['tmp_name'][$i], $dirname.$name);
								}
							} else { $alert_msg  = '有图片大小超过1M!'; }
						} else { $alert_msg = '有图片类型未识别!'; }
					}
*/				}
 

				if ($id > 0) { // 修改
					$car = $db->getOne("select id, car, info, imgurl from {$tb_pre}carhnlove where id=$id");
					if (!empty($car['imgurl'])) {
						$car['imgurl'] = explode('||', $car['imgurl']);
					} else $car['imgurl'] = array();
					$imgurl_arr = array_unique(array_merge($car['imgurl'], $imgurl_arr));
					$imgurl = implode('||', $imgurl_arr);
					
					$db->query("update {$tb_pre}carhnlove set car='$carname', info='$carinfo', imgurl='$imgurl' where id=$id");
				} else { // 新增
					$time = time();
					$imgurl = implode('||', $imgurl_arr);
					
					$db->query("insert into {$tb_pre}carhnlove(car,info,imgurl,dateline) values('$carname', '$carinfo', '$imgurl', $time)");
				}
			}
			if ($id > 0) {
				$cardetail = $db->getOne("select car, info from {$tb_pre}carhnlove where id=$id");
			}
			require adminTemplate('lovestation_lovecar');
			exit;
			break;
	}
	$ajaxp = ajaxpaging("select count(*) as count from {$tb_pre}carhnlove where {$sql_condition}", 5);
	$pages = $ajaxp[0];
	$currenturl = "index.php?action=lovestation&h=recommend_list&ajax=&uid=$uid&ruid=$ruid";

	$sql = "select * from {$tb_pre}carhnlove where {$sql_condition} order by isdisplay desc, dateline desc limit {$ajaxp[1]}";
	$car_list = $db->getAll($sql);
	foreach ($car_list as $key => $car) {
		if (!empty($car['imgurl'])) {
			$car_list[$key]['imgurl'] = explode('||', $car['imgurl']);
		} else $car_list[$key]['imgurl'] = array();
	}
	require adminTemplate('lovestation_hnlove');
	exit;

}

/**
 * 红娘来支招列表
 */

function  carfaq(){
   // global $_MooClass,$dbTablePre;
    $page = max(1,MooGetGPC('page','integer','G'));

    $limit = 4;
    $offset = ($page-1)*$limit;
    $sql  = 'select count(1) as total  from '.$GLOBALS['dbTablePre']."carfaq  where deleted =0  ";
    $total=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
    $total =$total['total'];
    $sql='SELECT *  FROM `'.$GLOBALS['dbTablePre']."carfaq` where  deleted = 0 order by dateline desc LIMIT ".$offset.','.$limit;
    $data=$GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
    foreach($data as $key=>$val){
        $val = preg_replace("/\n/","<br/>",$val);
        $val = preg_replace("/ /","&nbsp",$val);
        $data[$key]=$val;
    }
    $currenturl = "index.php?action=lovestation&h=carfaq";
    $pages = multipage( $total, $limit, $page, $currenturl );
    $page_num = ceil($total/$limit);
    require_once(adminTemplate('lovestation_carfaq'));
}
/*
 * 红娘支招内容编辑
 */
function carfaqedit(){
	global $_MooCookie;
	if($_POST){
        $data['dateline'] = time();
        $data['adminuid'] = $GLOBALS['adminid'];
        $data['question'] = MooGetGPC("question","string","P");
        if(empty($data['question'])){
            MooMessageAdmin("问题标题必须填写",'index.php?action=lovestation&h=carfaqedit');
            exit;
        }
        $data['answer'] = MooGetGPC("content","string","P");
        if(empty($data['question'])){
            MooMessageAdmin("问题支招必须填写",'index.php?action=lovestation&h=carfaqedit');
            exit;
        }
        $whearr['id'] = MooGetGPC("id","integer","P");
		updatetable("carfaq",$data,$whearr);
        MooMessageAdmin("更新成功","index.php?action=lovestation&h=carfaqedit&id=$whearr[id]");
        exit;
    }
	$id = MooGetGPC('id','integer','R');
	$sql='SELECT *  FROM `'.$GLOBALS['dbTablePre']."carfaq` where id= $id LIMIT 1";
	$datainfo=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	require_once (adminTemplate('lovestation_carfaq_edit'));
}

/*
 * 红娘支招内容添加
 */

function carfaqadd(){
    global $_MooCookie;
    if($_POST){
        $data['dateline'] = time();
        $data['adminuid'] = $GLOBALS['adminid'];
        $data['question'] = MooGetGPC("question","string","P");
        $data['answer'] = MooGetGPC("content","string","P");
        $data['uid'] = MooGetGPC("uid","string","P");
        $id = inserttable("carfaq",$data,0,true);

        if(!$id){
            MooMessageAdmin("添加失败请重新添加",'index.php?action=lovestation&h=carfaqadd');
            exit;
        }
        MooMessageAdmin("添加成功",'index.php?action=lovestation&h=carfaqadd');

    }
    require_once (adminTemplate('lovestation_carfaq_add'));
}
/*
 * 红娘支招内容删除
 */

function carfaqdelete(){
	$whearr["id"] = MooGetGPC("id","string","G");
    $data['deleted']=1;
    updatetable("carfaq",$data,$whearr);
    MooMessageAdmin("删除成功",'index.php?action=lovestation&h=carfaq');
    exit;
}
/*
 * 检查uid对应的用户是否存在
 * ajax请求
 */
function carfaqajax(){
    $uid = MooGetGPC("uid","integer","P");
    $sql ="select uid from ".$GLOBALS['dbTablePre']."members_search where uid= $uid limit 1";
    $data = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
    if($data['uid']){
        echo 1;exit;
    }else{
        echo 0;exit;
    }

}

/**
 *
 * 红娘休闲吧列表
 */

function carrelaxbarlist(){
    $page = max(1,MooGetGPC('page','integer','G'));
    $limit = 5;
    $offset = ($page-1)*$limit;
    $deleted = MooGetGPC('deleted','integer','G');
    $deleted = empty($deleted)?0:1;
    $sql='SELECT count(1) as total  FROM `'.$GLOBALS['dbTablePre']."carrelaxbar` where deleted = $deleted ";
    $data = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
    $total =$data['total'];
    $sql='SELECT *  FROM `'.$GLOBALS['dbTablePre']."carrelaxbar` where deleted = $deleted  order by dateline desc limit $offset ,$limit";
    $data = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
    foreach($data as $key=>$val){
        $val = preg_replace("/\n/","<br/>",$val);
        $val = preg_replace("/ /","&nbsp;",$val);
        $data[$key]=$val;
    }
    $currenturl = "index.php?action=lovestation&h=carrelaxbar&deleted=$deleted";
    $pages = multipage( $total, $limit, $page, $currenturl );
    $page_num = ceil($total/$limit);
    require_once (adminTemplate("lovestation_carrelaxbar_list"));

}
/*
 * 休闲吧笑话添加
 *
 */
function carrelaxbaradd(){
    if($_POST){
        $data['content'] = MooGetGPC("content",'string','P');
        //$data['content'] = preg_replace("/\n/","<br>",$data['content']);
        //$data['content'] = preg_replace("/ /","&nbsp",$data['content']);
        $data['dateline']=time();
        $data['deleted'] =0;
        $id = inserttable("carrelaxbar",$data,0,true);
        if(!$id){
            MooMessageAdmin("添加失败",'index.php?action=lovestation&h=carrelaxbar');

        }
    }
    MooMessageAdmin("添加成功",'index.php?action=lovestation&h=carrelaxbar');
    exit;
}
/*
 * 休闲吧笑话内容编辑
 *
 */
function carrelaxbaredit(){
    if($_POST){
        $data['content'] = MooGetGPC("content",'string','P');
        //$data['content'] = preg_replace("/\n/","<br>",$data['content']);
        //$data['content'] = preg_replace("/ /","&nbsp",$data['content']);
        $data['dateline']=time();
        $whearr['id'] = MooGetGPC("id","integer","P");
        updatetable("carrelaxbar",$data,$whearr);
        echo 1;exit;
    }else{
        echo "非法操作";exit;
    }
}
/*
 * 笑话删除
 *
 */
function carrelaxbardelete(){
    if($_POST){
        $deleted  =MooGetGPC("deleted","integer","P");
        $data['deleted'] = empty($deleted)?1:0;
        $whearr['id'] = MooGetGPC("id","integer","P");
        updatetable("carrelaxbar",$data,$whearr);
        echo 1;exit;
    }else{
        echo "非法操作";exit;
    }
}

/*
 * 趣味游戏列表
 */
function carrelaxbargame(){
    $page = max(1,MooGetGPC('page','integer','G'));
    $limit = 5;
    $offset = ($page-1)*$limit;
    $sql = "select count(1) as total from ".$GLOBALS['dbTablePre']."cargames ";
    $data = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
    $total = $data['total'];
    $sql = "select * from ".$GLOBALS['dbTablePre']."cargames limit $offset,$limit";
    $data = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
    $currenturl = "index.php?action=lovestation&h=carrelaxbargame";
    $pages = multipage( $total, $limit, $page, $currenturl );
    $page_num = ceil($total/$limit);
    require_once (adminTemplate("lovestation_carrelaxbargame_list"));
}
/*
*删除趣味游戏
*/
function carrelaxbargamedelete(){
    $id = MooGetGPC("id","integer","P");
    $sql = "delete from ".$GLOBALS['dbTablePre']."cargames where id = $id";
    $isdel = $GLOBALS['_MooClass']['MooMySQL']->query($sql);
    if($isdel){
        echo 1;exit;
    }else{
        echo 0;exit;
    }
}
/*
 *添加趣味游戏
*/
function carrelaxbargameadd(){
    if($_POST){
        $href = MooGetGPC("href","string","P");

        if(empty($href)){
            $notice = "游戏的链接地址不能为空";
            MooMessageAdmin($notice,'index.php?action=lovestation&h=carrelaxbargame');
            exit;
        }
        $introduce = MooGetGPC("introduce","string","P");
        if(empty($introduce)){
            $notice = "游戏介绍说明必须填写";
            MooMessageAdmin($notice,'index.php?action=lovestation&h=carrelaxbargame');
            exit;
        }
        //上传图片的处理
/*
        if($_FILES['imgfile']['error']==UPLOAD_ERR_NO_FILE){
            $notice = "请选择图片";
            MooMessageAdmin($notice,'index.php?action=lovestation&h=carrelaxbargame');
            exit();
        }
        $minfilesize = 100;
        $maxfilesize = 1024000;
        $filesize = $_FILES['imgfile']['size'];
        if($filesize > $maxfilesize ) {
            $notice = "请上传小于1000KB的照片。";
            MooMessageAdmin($notice,'index.php?action=lovestation&h=carrelaxbargame');
            exit();
        }
        if($filesize < $minfilesize) {
            $notice = "请上传大于100b的照片。";
            MooMessageAdmin($notice,'index.php?action=lovestation&h=carrelaxbargame');
            exit();
        }
        $true_type = file_type($_FILES['imgfile']['tmp_name']);
        $extname = strtolower(substr($_FILES['imgfile']['name'],(strrpos($_FILES['imgfile']['name'],'.')+1)));
        $images = array('/jpg/', '/jpeg/', '/gif/', '/png/', '/JPG/', '/JPEG/', '/GIF/', '/PNG/');
        if(in_array('/'.$extname.'/',$images)){
            foreach($images as $v) {
                //note http://ask.wangmeng.cn/question/76
                if(preg_match($v,$_FILES['imgfile']['type'])
                    && ('image/'.$true_type == $_FILES['imgfile']['type']
                        || 'image/p'.$true_type == $_FILES['imgfile']['type']
                        || 'image/x-'.$true_type == $_FILES['imgfile']['type']) ) {

                    $file_content = file_get_contents($_FILES['imgfile']['tmp_name']);
                    $low_file_content = strtolower($file_content);
                    $pos = strpos($low_file_content, '<?php');
                    if($pos){
                        $notice = "照片中含有不安全信息请重新上传";
                        MooMessageAdmin($notice,'index.php?n=lovestation&h=carrelaxbargame');
                        exit();
                    }else{
                        $flag = 1;
                    }
                }
            }
        }
        if($flag != 1) {
            $notice = "请上传JPEG，JPG，PNG或GIF格式";
            MooMessageAdmin($notice,'index.php?action=lovestation&h=carrelaxbargame');
            exit();
        }

        $filename = "module/lovestation/templates/default/images/".md5(time()."7651hong").".gif";
        move_uploaded_file(stripcslashes($_FILES['imgfile']['tmp_name']),"../".$filename);
        //上传图片处理结束
*/
        $filename = "module/lovestation/templates/default/images/carrelaxbargame/".$_FILES['imgfile']['name'];
        $data['href']=$href;
        $data['introduce'] =$introduce;
        $data['dateline'] = time();
        $data['imgfile'] =$filename;
        $id =inserttable("cargames",$data,true);
        if(!$id){
            $notice = "插入失败请重新操作";
            MooMessageAdmin($notice,'index.php?action=lovestation&h=carrelaxbargame');
            exit;
        }

        MooMessageAdmin("添加成功",'index.php?action=lovestation&h=carrelaxbargame');
        exit;
    }
}
/*
 *
 * 游戏编辑
 */
function carrelaxbargameedit(){
    $href = MooGetGPC("href","string","P");
    $introduce =MooGetGPC("introduce","string","P");
    $id = MooGetGPC("id","string","P");
    if(empty($href)){
        $notice = "游戏的链接地址不能为空";
        MooMessageAdmin($notice,'index.php?n=lovestation&h=carrelaxbargame');
        exit;
    }

    if(empty($introduce)){
        $notice = "游戏介绍说明必须填写";
        MooMessageAdmin($notice,'index.php?n=lovestation&h=carrelaxbargame');
        exit;
    }
/*
    if($_FILES['imgfile']['error']==UPLOAD_ERR_OK){
        $minfilesize = 100;
        $maxfilesize = 1024000;
        $filesize = $_FILES['imgfile']['size'];
        if($filesize > $maxfilesize ) {
            $notice = "请上传小于1000KB的照片。";
            MooMessageAdmin($notice,'index.php?action=lovestation&h=carrelaxbargame');
            exit();
        }
        if($filesize < $minfilesize) {
            $notice = "请上传大于100b的照片。";
            MooMessageAdmin($notice,'index.php?action=lovestation&h=carrelaxbargame');
            exit();
        }
        $true_type = file_type($_FILES['imgfile']['tmp_name']);
        $extname = strtolower(substr($_FILES['imgfile']['name'],(strrpos($_FILES['imgfile']['name'],'.')+1)));
        $images = array('/jpg/', '/jpeg/', '/gif/', '/png/', '/JPG/', '/JPEG/', '/GIF/', '/PNG/');
        if(in_array('/'.$extname.'/',$images)){
            foreach($images as $v) {
                //note http://ask.wangmeng.cn/question/76
                if(preg_match($v,$_FILES['imgfile']['type'])
                    && ('image/'.$true_type == $_FILES['imgfile']['type']
                        || 'image/p'.$true_type == $_FILES['imgfile']['type']
                        || 'image/x-'.$true_type == $_FILES['imgfile']['type']) ) {

                    $file_content = file_get_contents($_FILES['imgfile']['tmp_name']);
                    $low_file_content = strtolower($file_content);
                    $pos = strpos($low_file_content, '<?php');
                    if($pos){
                        $notice = "照片中含有不安全信息请重新上传";
                        MooMessageAdmin($notice,'index.php?n=lovestation&h=carrelaxbargame');
                        exit();
                    }else{
                        $flag = 1;
                    }
                }
            }
        }
        if($flag != 1) {
            $notice = "请上传JPEG，JPG，PNG或GIF格式";
            MooMessageAdmin($notice,'index.php?action=lovestation&h=carrelaxbargame');
            exit();
        }
*/
    if($_FILES['imgfile']['error']==UPLOAD_ERR_OK){
        $filename = "module/lovestation/templates/default/images/carrelaxbargame/".$_FILES['imgfile']['name'];        $data['imgfile']=$filename;
        //上传图片处理结束
    }
    $data['href']=$href;
    $data['introduce'] =$introduce;
    $data['dateline'] = time();
    $whearr['id'] =  $id;
    updatetable("cargames",$data,$whearr);
    MooMessageAdmin("更新成功",'index.php?action=lovestation&h=carrelaxbargame');
    exit;
}


if(!checkGroup('lovestation',$h)){
	MooMessageAdmin('您没有此操作的权限','index.php');
}
$h=MooGetGPC('h','string','G');
//日志变更类型
/*
$matchmaker_msg_array=array(0=>'处罚',1=>'奖励','3'=>'PK胜利','4'=>'PK失败','5'=>'PK资本支付','6'=>'PK资本返还');
if(!checkGroup('matchmaker',$h)){
    if(in_array($h,array('config_list','to_rewards','want_pk','my_pk','pk_me','pk_list','mylog','reward_log','isAuthRewards'))){
        exit('您没有此操作的权限');
    }else{
        exit(json_encode(array('flag'=>0,'msg'=>'您没有此操作的权限')));
    }
}
*/


switch ($h){
	case 'crazyfan':
		crazyfan();
		break;
	case 'recommend_list':
		recommend_list();
		break;
	case 'hnlove':
		hnlove();
		break;
    case "carfaq":
        carfaq();
        break;
    case "carfaqedit":
    	carfaqedit();
    	break;
    case "carfaqadd":
        carfaqadd();
        break;
    case "carfaqajax":
        carfaqajax();
        break;
    case "carfaqdelete":
        carfaqdelete();
        break;
    case "carrelaxbar":
        carrelaxbarlist();
        break;
    case "carrelaxbardelete":
        carrelaxbardelete();
        break;
    case "carrelaxbaredit":
        carrelaxbaredit();
        break;
    case "carrelaxbaradd":
        carrelaxbaradd();
        break;
    case "carrelaxbargame":
        carrelaxbargame();
        break;
    case "carrelaxbargameadd":
        carrelaxbargameadd();
        break;
    case "carrelaxbargameedit":
        carrelaxbargameedit();
        break;
    case "carrelaxbargamedelete":
        carrelaxbargamedelete();
        break;
    default:
        exit('找不到你要操作的内容');
        break;
}
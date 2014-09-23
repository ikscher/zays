<link href="templates/css/general.css" rel="stylesheet" type="text/css" />
<link href="templates/css/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="templates/js/onmousemove_minutes.js"></script>
<script type="text/javascript"><!--
//跳转
	function gotoPage(){
		var page=$("#pageGo").attr("value");
		window.location="index.php?action=check&h=<?php echo $_GET['h'];?>&type=list&page="+page;
	}
	function showreport(id){
		$("#report").css('display','none');
		$("#report_div").css('display','block');
		$("#report_iframe").attr('src',"index.php?action=check&h=<?php echo $_GET['h'];?>&type=show&id="+id);
	}
	function unshowreport(){
		$("#report").css('display','');
		$("#report_div").css('display','none');
	}
	function jump(id,uid){
	       window.location="index.php?action=check&h=<?php echo $_GET['h'];?>&type=show&id="+id+"&uid="+uid;
	}
	
	function monologCheck(uid,pass){
		var uid  =  uid;
		var pass = pass;
		var url  = "index.php?action=check&h=monolog&type=submit&uid="+uid;
		$.post(url,{uid:uid,pass:pass,ajax:1},function(str){
			if(str == 1){
				alert('审核通过');
				$("#monolog_"+uid).html("审核通过");
			}else{
				alert('审核不通过');
				$("#monolog_"+uid).html("审核不通过");
			}
		})
	}

    //鼠标移动行  高亮 显示
	$(function(){
	    $(".csstab tr").mouseover(function(){
	        $(this).addClass("over");
	    }).mouseout(function(){
	        $(this).removeClass("over");    
	    });
	});


    //排序
	function chang_order(field,order){
		location.href="<?php echo $currenturl;?>&order="+field+"&method="+order;
	}
        
</script>
<style>
tr.over td {
	background:#cfeefe;
} 

</style>
<h1>
<span class="action-span1"><a href="###">真爱一生网 管理中心</a> </span><span id="search_id" class="action-span1"> - <?php echo $lei;?></span>
<span class="action-span"><a href="<?php $u=explode('&',$_SERVER['QUERY_STRING']);?>index.php?<?php echo $u[0];?>&<?php echo $u[1];?>">刷新</a></span>
<div style="clear:both"></div>
</h1>
<div class="list-div" style="margin-bottom:10px;padding:5px;">
<form action="<?php echo $currenturl;?>" method="post">
	会员ID：&nbsp;&nbsp;<input type="text" name="uid" value="<?php if(!empty($uid)) { ?><?php echo $uid;?><?php } ?>" />&nbsp;&nbsp;
	<?php if($myservice_idlist && $myservice_idlist!==$GLOBALS['adminid']) { ?>
		客服：<select name="usersid" id="usersid" onchange="option_gourl(this.value,<?php echo $pass;?>)" style="height:21px;margin-top:3px;" >
		   <?php if($myservice_idlist=='all') { ?>
		     <option value="-1" <?php if($usersid==-1) { ?>selected="selected"<?php } ?>> 不限</option>
		     <option value="0" <?php if($usersid == 0) { ?> selected="selected"<?php } ?>>0：</option>
		   <?php } ?>
		   <?php foreach((array)$adminUser as $value) {?>
		   <option value="<?php echo $value['uid'];?>" <?php if($usersid == $value['uid']) { ?> selected="selected"<?php } ?>><?php echo $value['uid'];?>：<?php echo $value['username'];?></option>
		   <?php } ?>
		</select>
	<?php } ?>
	<input type="hidden" name="pass" value="<?php echo $pass;?>" />
	<input type="submit" name="submit" value=" 查 找 " />
	<span class="but_a"><a href="<?php echo $currenturl2;?>&pass=1" <?php if($pass==1) { ?>style="font-weight:bold;"<?php } ?>>已审核</a><a href="<?php echo $currenturl2;?>&pass=0" <?php if($pass==0) { ?>style="font-weight:bold;"<?php } ?>>未审核</a></span>
</form>
</div>
<div style=" margin-top:10px;">
</div>
<div class="list-div" id="listDiv">
<table width="100%" cellspacing='1' cellpadding='3' id='list-table' class="csstab" style="word-wrap: break-word; overflow: hidden;">
  <tr>
    <th width="%6"><a href="javascript:chang_order('uid','<?php echo $rsort_arr['uid'];?>')"  style="text-decoration:underline;">会员ID</a></th>
	<th width="%8">昵称</th>
	<th width="%5"><a href="javascript:chang_order('birthyear','<?php echo $rsort_arr['birthyear'];?>')"  style="text-decoration:underline;">年龄</a></th>
	<th width="%8"><a href="javascript:chang_order('lastvisit','<?php echo $rsort_arr['lastvisit'];?>')"  style="text-decoration:underline;">最后登录时间</a></th>
    <th width="%8"><a href="javascript:chang_order('allotdate','<?php echo $rsort_arr['allotdate'];?>')"  style="text-decoration:underline;">分配时间</a></th>
	<?php if(in_array($_GET['h'],array('photo','image'))) { ?><th width="%8" ><a href="javascript:chang_order('pic_date','<?php echo $rsort_arr['pic_date'];?>')"  style="text-decoration:underline;">上传时间</a></th><?php } ?>
	<th width="%40"><?php if($_GET['h'] == 'monolog') { ?>独白内容<?php } else { ?>类型<?php } ?></th>
	<th width="%8">审核状态</th>
	<th width="%8"><a href="javascript:chang_order('sid','<?php echo $rsort_arr['sid'];?>')"  style="text-decoration:underline;">所属客服ID</a></th>
	<th width="%12">操作</th>
  </tr>
  <?php foreach((array)$list as $v) {?>
  <tr onmousemove="this.bgColor='#cfeefe';" onmouseout="this.bgColor='#ffffff';" class="listclass">
    <td align="center"><a href="#" onclick="parent.addTab('查看<?php echo $v['uid'];?>资料','index.php?action=allmember&h=view_info&uid=<?php echo $v['uid'];?>','icon')"><?php echo $v['uid'];?></a></td>
	<td align="center">
		<?php if($v['gender']==1) { ?><img src="templates/images/w.gif" alt="女" title="女"/>
		<?php } else { ?><img src="templates/images/m.gif" alt="男" title="男"/>
		<?php } ?> 
		<a href="#" onclick="parent.addTab('查看<?php echo $v['uid'];?>资料','index.php?action=allmember&h=view_info&uid=<?php echo $v['uid'];?>','icon')"><?php echo $v['nickname'];?></a>
	</td>
	<td align="center"><?php if($v['birthyear'])echo date('Y')-$v['birthyear'];else echo "无";?></td>
	<td align="center"><?php echo date('Y-m-d',$v['lastvisit']);?></td>
    <td align="center"><?php echo $v['allotdate'] ? date('Y-m-d',$v['allotdate']) : '未分配';?></td>
	
	<?php if(in_array($_GET['h'] ,array('photo','image'))) { ?>
	<td align="center">
	<?php echo $v['pic_date']?>
	</td>
	<?php } ?>
	
    <?php if($_GET['h']=='monolog') { ?>
	<td id="<?php echo $v['uid'];?>monolog" width="400" align="left" style="overflow:auto;word-wrap:break-word;overflow: hidden;"
	><?php echo $v['introduce_check'],$v['syscheck']?></td>
    <?php } else { ?>
    <td align="center" style="word-wrap:break-word;word- break:break-all;"
    ><?php echo $lei;?></td>
    <?php } ?>
	
	<td align="center">
    	<?php if($_GET['h'] != 'monolog') { ?>
         <?php echo $checkArr[$v['syscheck']];?>
        <?php } else { ?>
         <?php echo $v['syscheck'] !='' ? '审核通过' : '未审核';?>
        <?php } ?>
    </td>
	<td align="center"><a href="<?php echo $currenturl2;?>&usersid=<?php echo $v['sid'];?>"><?php echo $v['sid'];?></a></td>
	
	<td align="center"><?php if($_GET['h']=='monolog')echo '<a onclick="monologCheck('.$v['uid'].',1);return false;" href="#">通过</a> | <a onclick="monologCheck('.$v['uid'].',2);return false;" href="#">不通过</a> | '; if($v['syscheck']==-1)echo "查看";else echo "<a href=\"index.php?action=check&h=" . $_GET['h']."&type=show&pass=$pass&id=".$v['id']."&uid=".$v['uid']."&usersid=$usersid&page=$page \">查看</a>";?><span style="color:#F00" id="monolog_<?php echo $v['uid'];?>"></span></td>
  </tr>
  <?php } ?>
  <tr>
  	<td colspan="8" align="center"><?php echo multipage($total,$prepage,$page,$currenturl2)?>
     	转到第   <input name="pageGo"  id="pageGo" type="text" style="width:35px" value=""/>  页
     	<input type="button"  class="ser_go" value="跳转" onclick="gotoPage()"/></td>
  	</tr>
  </table>
  
  
</div>


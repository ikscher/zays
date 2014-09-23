<link href="templates/css/general.css" rel="stylesheet" type="text/css" />
<link href="templates/css/main.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="templates/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="templates/js/onmousemove_minutes.js"></script>


<script>
$(function(){
	$("#list-table tr").mouseover(function(){
		$(this).addClass("over");
	}).mouseout(function(){
		$(this).removeClass("over");	
	})
	$(".s_content1").css("display","none");
	$(".s_content2").css("display","none");
})


//分页跳转
function gotoPage() {
	var page = $("#pageGo").val();
	var page = parseInt(page);
	
	if(page<1) page = 1;
	if(page><?php echo $page_num;?>)

	page = <?php echo $page_num;?>;
	window.location.href = "<?php echo $currenturl;?>&page="+page;
}

function dealok(id){
	var url = './active_ajax.php?n=websms&id='+id;
	$.get(url,function(data){
		$("#tr_"+id).hide();
	});	
}
function showUndeal(){
	window.location.href = "<?php echo $currenturl2;?>&type=undealed";
}

function showDealed(){
	window.location.href = "<?php echo $currenturl2;?>&type=dealed";
}

function showAll(){
	window.location.href = "<?php echo $currenturl2;?>&type=all";
}

function showMessage(id){
	$("#"+id).show();
}



</script>
<style>
tr.over td {
	background:#cfeefe;
} 

input[type="button"]  {
background-color:#cfeefe;
}

.coolbg {
background: #a4cafd;
border-bottom:2px solid #ACACAC;
border-right:2px solid #ACACAC;
cursor:pointer;
padding:2px 5px;
}

.np {
border:medium none;
}

</style>


<h1>
<span class="action-span1"><a href="###">真爱一生网 管理中心</a> </span><span id="search_id" class="action-span1"> - 站内短信</span>
<span class="action-span"><a href="<?php $u=explode('&',$_SERVER['QUERY_STRING']);?>index.php?<?php echo $u[0];?>&<?php echo $u[1];?>">刷新</a></span>
<div style="clear:both"></div>
</h1>
<div class="list-div" style="padding:5px;">
<form action="" method="get">
  <tr border="2">
  	<td>
     <select name="choose" id="choose">
	    <option value="">请选择搜索条件</option>
	    <option value="s_fromid" <?php if($choose=='s_fromid') { ?>selected="selected"<?php } ?>>发信人ID</option>
		<option value="s_uid" <?php if($choose=='s_uid') { ?>selected="selected"<?php } ?>>收信人ID</option>
		<option value="s_title" <?php if($choose=='s_title') { ?>selected="selected"<?php } ?>>邮件主题</option>
		<option value="s_content" <?php if($choose=='s_content') { ?>selected="selected"<?php } ?>>邮件内容</option>
	 </select>
	 </td>
	 <td>&nbsp;&nbsp;
	 </td>
	 <td>
		<input name="keyword" type="text" id="keyword" value="<?php echo $keyword;?>"/>
		<input type="hidden" name="action" value="active_websms" />
		<input type="hidden" name="h" value="<?php echo $GLOBALS['h'];?>" />
		<input name="" type="submit" value="搜 索"/>
	</td>
	<td align="center">
    <input type="button" value="显示未处理过的" onclick="showUndeal()" class="coolbg np">
    <input type="button" value="显示已处理过的" onclick="showDealed()" class="coolbg np">
    <input type="button" value="显示所有的" onclick="showAll()" class="coolbg np">
    
   </td>
	  </tr>
</form>
</div>
<p/>
<div class="list-div" id="listDiv">
<table cellspacing='1' cellpadding='3' id='list-table'>
  <tr>
    <th>发方ID</th>
    <th>发方昵称</th>
    <th>收方ID</th>
   
    <th>发送者身份</th>
    <th>主题</th>
    <th class="s_content1">邮件内容</th>
    <th>发布时间</th>
    <th>阅读时间</th>
    <th>发方是否删除</th>
    <th>发方删除时间</th>
    <th>收方是否删除</th>
    <th>收方删除时间</th>
    <th>是否审核</th>
    <th>操作</th>
  </tr>
  <?php foreach((array)$user_arr as $v) {?>
  <tr id="tr_<?php echo $v['s_id'];?>">
    <td align="center" >
    <?php if($v['s_cid'] == '3') { ?>
    	红娘ID
    <?php } else { ?> <a href="#" class="userinfo" onclick="parent.addTab('查看<?php echo $v['s_fromid'];?>资料','index.php?action=allmember&h=view_info&uid=<?php echo $v['s_fromid'];?>','icon')">
   <?php echo $v['s_fromid'];?>
    </a><?php } ?>→</td>
    <td  align="center" ><?php if(isset($v['send_gender']) && $v['send_gender']==1) { ?><img src="templates/images/w.gif" alt="女" title="女"/>
				<?php } else { ?><img src="templates/images/m.gif" alt="男" title="男"/>
				<?php } ?><?php echo $v['nickname'];?></td>
    <td align="center">→<a href="#" class="userinfo" onclick="parent.addTab('查看<?php echo $v['s_uid'];?>资料','index.php?action=allmember&h=view_info&uid=<?php echo $v['s_uid'];?>','icon')">
    	<?php echo $v['s_uid'];?>
    	</a></td>
 
    <td align="center" >
    	<?php if($v['s_cid'] == '1') { ?>
    	高级会员
    	<?php } ?>
    	
    	<?php if($v['s_cid'] == '2') { ?>
    	普通会员
    	<?php } ?>
    	
    	<?php if($v['s_cid'] == '3') { ?>
    	红娘来信
    	<?php } ?>   
    	</td>
     <td align="center" ><a onclick="showMessage('aboutorder<?php echo $v['s_id'];?>')"  href="#"  class="vtip" ><?php echo str_replace($keyword,"<span style='color: #33F;'>".$keyword."</span>",$v['s_title']);?></a>
        <div style="position: relative; width: 200px; height: auto;">
      <div id="aboutorder<?php echo $v['s_id'];?>"  style="display: none; position: absolute; width: 200px;color:#15428B; background-color: #E0ECFF; left: 150px; top: -20px; border: 1px solid #99BBE8;">
       <p style="margin: 0;border: 2px solid #99BBE8;text-align: right;"><a href="#"   onclick="$('#aboutorder<?php echo $v['s_id'];?>').hide()">关闭</a>&nbsp;&nbsp;&nbsp;<p>
		  <p><?php echo $v['s_content'];?>&nbsp;
		  	
		  </p>
		  <p></p>
	  </div>
	</div>
	
    </td>

    <td align="center"><?php echo date("Y-m-d H:i:s",$v['s_time']);?></td>
    <td align="center">
    <?php if($v['s_cid'] == '3') { ?>
    	
    <?php } else { ?> 
    	<?php if($v['read_time']=='0000-00-00' ) { ?>
    	  未阅读
    	<?php } else { ?>
    	<?php echo $v['read_time'];?>
    	<?php } ?>
    <?php } ?></td>
    <td align="center"><?php if($v['s_fromid_del'] == '1') { ?>已删除<?php } ?></td>
     <td align="center"><?php if(!empty($v['s_fromid_deltime'])) { ?><?php echo date("Y-m-d h:i:s",$v['s_fromid_deltime'])?><?php } ?></td>
    <td align="center"><?php if($v['s_uid_del'] == '1') { ?>已删除<?php } ?></td>
    <td align="center"><?php if(!empty($v['s_uid_deltime'])) { ?><?php echo date("Y-m-d h:i:s",$v['s_uid_deltime'])?><?php } ?></td>
    <td align="center">
    <?php if($v['flag'] == '0') { ?> 未审核<?php } ?>
    <?php if($v['flag'] == '1') { ?> 审核通过<?php } ?>
    <?php if($v['flag'] == '2') { ?> 拒绝<?php } ?>
    </td>
    <td align="center">
    <?php if($v['dealstate'] == '1') { ?>
    	已处理
    <?php } else { ?>
    <a href="#" onclick="dealok(<?php echo $v['s_id'];?>);">未处理</a>
    <?php } ?>
    </td>
  </tr>
  <?php } ?>
  </table>
  
  <div style="padding:5px;background:#fff">
	
</div>

<table cellpadding="4" cellspacing="0">
    <tr>
      <td align="center"><?php echo $pages;?>
      	&nbsp;&nbsp;&nbsp;
      	转到第   <input name="pageGo"  id="pageGo" type="text" style="width:20px;height:15px;" value="" /> 页 &nbsp;
      <input type="button"  class="ser_go" value="跳转" onclick="gotoPage()"/></td>
    </tr>
  </table>
  
</div>
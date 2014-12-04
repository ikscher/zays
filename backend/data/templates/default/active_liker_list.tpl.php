<link href="templates/css/general.css" rel="stylesheet" type="text/css" />
<link href="templates/css/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/city.js?v=1"></script>
<script type="text/javascript" src="templates/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="templates/js/onmousemove_minutes.js"></script>
<script>
$(function(){
	$("#list-table tr").mouseover(function(){
		$(this).addClass("over");
	}).mouseout(function(){
		$(this).removeClass("over");	
	})
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
	var url = './active_ajax.php?n=friend&id='+id;
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
<span class="action-span1"><a href="###">真爱一生网 管理中心</a> </span><span id="search_id" class="action-span1"> - 会员意中人</span>
<span class="action-span"><a href="index.php?action=active_liker&h=list">刷新</a></span>
<div style="clear:both"></div>
</h1>
<div class="list-div" style="padding:5px;">
<form action="" method="get">
  <tr border="2">
  	<td>
     <select name="choose" id="choose">
	    <option value="">请选择搜索条件</option>
	    <option value="uid" <?php if($choose=='uid') { ?>selected="selected"<?php } ?>>会员的ID</option>
		<option value="friendid" <?php if($choose=='friendid') { ?>selected="selected"<?php } ?>>会员意中人的ID</option>
	 </select>
	 </td>
	 <td>&nbsp;&nbsp;
	 </td>
	 <td>
		<input name="keyword" type="text" id="keyword" value="<?php echo $keyword;?>"/>
		<input type="hidden" name="action" value="active_liker" />
		<input type="hidden" name="h" value="<?php echo $GLOBALS['h'];?>" />
		<input name="" type="submit" value="搜 索"/>
		
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
    <th>会员的ID</th>
    <th>会员昵称</th>
    <th>会员意中人的ID</th>
    <th>意中人昵称</th>
    <th>发送时间</th>
     <th>操作</th>
  </tr>
  <?php foreach((array)$user_arr as $v) {?>
  <tr id="tr_<?php echo $v['fid'];?>">
    <td align="center"><a href="#" class="userinfo" onclick="parent.addTab('查看<?php echo $v['uid'];?>资料','index.php?action=allmember&h=view_info&uid=<?php echo $v['uid'];?>','icon')">
		<span style='color: #33F;'><?php echo $v['uid'];?></span>
    </a></td>
     <td  align="center" ><?php if(isset($v['send_gender']) && $v['send_gender']==1) { ?><img src="templates/images/w.gif" alt="女" title="女"/>
				<?php } else { ?><img src="templates/images/m.gif" alt="男" title="男"/>
				<?php } ?><?php if(isset($v['send_nickname'])) { ?><?php echo $v['send_nickname'];?><?php } ?></td>
    <td align="center"><a href="#" class="userinfo" onclick="parent.addTab('查看<?php echo $v['friendid'];?>资料','index.php?action=allmember&h=view_info&uid=<?php echo $v['friendid'];?>','icon')">
    	<span style='color: #33F;'><?php echo $v['friendid'];?></span>
    	</a></td>
    <td  align="center" ><?php if(isset($v['receive_gender']) && $v['receive_gender']==1) { ?><img src="templates/images/w.gif" alt="女" title="女"/>
				<?php } else { ?><img src="templates/images/m.gif" alt="男" title="男"/>
				<?php } ?><?php if(isset($v['receive_nickname'])) { ?><?php echo $v['receive_nickname'];?><?php } ?></td>
    <td align="center"><?php echo date("Y-m-d H:i:s",$v['sendtime']);?></td>
    <td align="center">
    <?php if(isset($v['dealstate']) && $v['dealstate'] == '1') { ?>
           已处理
    <?php } else { ?>
    <a href="#" onclick="dealok(<?php echo $v['fid'];?>);">未处理</a>
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
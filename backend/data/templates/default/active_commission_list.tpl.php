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
	var url = './active_ajax.php?n=contact&id='+id;
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
<span class="action-span1"><a href="###">真爱一生网 管理中心</a> </span><span id="search_id" class="action-span1"> - 用户委托</span>
<span class="action-span"><a href="index.php?action=active_commission&h=list">刷新</a></span>
<div style="clear:both"></div>
</h1>
<div class="list-div" style="padding:5px;" onmouseover="$('#aboutorder').hide()">
<form action="" method="get">
  <tr border="2">
  	<td>
     <select name="choose" id="choose">
	    <option value="">请选择搜索条件</option>
	    <option value="other_contact_you" <?php if($choose=='other_contact_you') { ?>selected="selected"<?php } ?>>发送委托会员ID</option>
	 </select>
	 </td>
	 <td>&nbsp;&nbsp;
	 </td>
	 <td>
		<input name="keyword" type="text" id="keyword" value="<?php echo $keyword;?>"/>
		<input type="hidden" name="action" value="active_commission" />
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
    <th>发送委托会员ID</th>
    <th>发送委托会员昵称</th>
    <th>发送时间</th>
    <th>发送委托会员年龄</th>
    <th>接收委托的会员</th>
    <th>操作</th>
  </tr>
  <?php foreach((array)$user_arr as $v) {?>
  <tr id="tr_<?php echo $v['mid'];?>">
    <td align="center"><a href="#" class="userinfo" onclick="parent.addTab('查看<?php echo $v['other_contact_you'];?>资料','index.php?action=allmember&h=view_info&uid=<?php echo $v['other_contact_you'];?>','icon')">
    <span style='color: #33F;'><?php echo $v['other_contact_you'];?></span>
    </a></td>
    <td align="center"><?php if($v['gender']==1) { ?><img src="templates/images/w.gif" alt="女" title="女"/>
				<?php } else { ?><img src="templates/images/m.gif" alt="男" title="男"/>
				<?php } ?><?php echo $v['nickname'];?></td>
	 <td align="center"><?php echo date('Y-m-d H:i:s',$v['sendtime'])?></td>
    <td align="center"><?php echo (gmdate('Y', time()) - $v['birthyear']);?></td>
    <td style="text-align: left;">
    	<?php foreach((array)$v['contact_list'] as $vv) {?>
    	<span>&nbsp;</span>
    	<label>
    		<a href="#" class="userinfo" onclick="parent.addTab('查看<?php echo $vv['uid'];?>资料','index.php?action=allmember&h=view_info&uid=<?php echo $vv['uid'];?>','icon')"><?php if(empty($vv['nickname'])) { ?><?php echo $vv['uid'];?><?php } else { ?><?php echo $vv['nickname'];?><?php } ?></a>																</label>
    	<span>&nbsp;&nbsp;&nbsp;&nbsp;接受会员年龄：<?php echo $vv['birthyear'];?>&nbsp;&nbsp;&nbsp;&nbsp;接受会员性别：<?php if($vv['gender'] == '0') { ?>男<?php } ?><?php if($vv['gender'] == '1') { ?>女<?php } ?>
    		&nbsp;&nbsp;&nbsp;&nbsp;发送委托时间：	<?php echo $vv['sendtime'];?> &nbsp;&nbsp;&nbsp;&nbsp;委托状态：	
    		<?php if($vv['state'] == '1') { ?>待对方回应<?php } ?>
    		<?php if($vv['state'] == '2') { ?>对方已接受<?php } ?>
    		<?php if($vv['state'] == '3') { ?>对方正在考虑<?php } ?>
    		<?php if($vv['state'] == '4') { ?>对方不愿接受<?php } ?> 
    		</span> 
    	<br>
    	<?php } ?>		
    	
    	</td>
	 <td align="center">
	 <?php if($v['dealstate'] == '1') { ?>
            已处理
     <?php } else { ?>
     <a href="#" onclick="dealok(<?php echo $v['mid'];?>);">未处理</a>
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
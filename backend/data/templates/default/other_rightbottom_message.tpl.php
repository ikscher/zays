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
	if(page><?php echo $page_num;?>) page = <?php echo $page_num;?>;

	
	window.location.href = "<?php echo $currenturl;?>&page="+page;
}

function dealok(id){
	var url = './active_ajax.php?n=remark&id='+id;
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
<span class="action-span1"><a href="###">真爱一生网 管理中心</a> </span><span id="search_id" class="action-span1"> - 我的右下角提醒历史列表</span>
<span class="action-span"><a href="<?php $u=explode('&',$_SERVER['QUERY_STRING']);?>index.php?<?php echo $u[0];?>&<?php echo $u[1];?>">刷新</a></span>
<div style="clear:both"></div>
</h1>
<div class="list-div">
<form action="" method="get">
	<table>
		<tr border="2">
			<td  style="background: #E6F0FD">    <input type="button" value="显示未处理过的" onclick="showUndeal()" class="coolbg np">
    <input type="button" value="显示已处理过的" onclick="showDealed()" class="coolbg np">
    <input type="button" value="显示所有的" onclick="showAll()" class="coolbg np"></td>
		</tr>
	</table>
</form>
</div>
<p/>
<div class="list-div" id="listDiv">
<table cellspacing='1' cellpadding='3' id='list-table'>
  <tr>
    <th>客服ID</th>
    <th>发送标题</th>
    <th>发送内容</th>
    <th>发送时间</th>
    <th>状态</th>
    <th>操作</th>
  </tr>
  <?php foreach((array)$remark_list as $list) {?>
	<tr  id="tr_<?php echo $list['id'];?>">
		<td align="center" ><?php echo $list['sid'];?></td>
		<td align="center" ><?php echo $list['title'];?></td>
		<td align="center" ><?php echo $list['content'];?></td>
		<td align="center" ><?php echo date('Y-m-d H:i:s',$list['dateline'])?></td>
		<td align="center" ><?php if($list['status']==1) { ?>已解决<?php } else { ?><a href="index.php?action=other_rightbottom&h=message&id=<?php echo $list['id'];?>">未解决</a><?php } ?></td>
		<td align="center">
		<?php if($list['dealstate'] == '1') { ?>
                      已处理
    	<?php } else { ?>
    	<a href="#" onclick="dealok(<?php echo $list['id'];?>);">未处理</a>
    	<?php } ?>
		</td>
	</tr>
  <?php } ?>
  </table>
  
  <div style="padding:5px;background:#fff">
	
</div>

<table cellpadding="4" cellspacing="0">
    <tr>
      <td align="center"><?php echo $page_links;?>
      	&nbsp;&nbsp;&nbsp;
      	转到第   <input name="pageGo"  id="pageGo" type="text" style="width:20px;height:15px;" value="" /> 页 &nbsp;
      <input type="button"  class="ser_go" value="跳转" onclick="gotoPage()"/></td>
    </tr>
  </table>
  
</div>

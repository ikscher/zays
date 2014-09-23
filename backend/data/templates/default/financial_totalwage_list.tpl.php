<link href="templates/css/general.css" rel="stylesheet" type="text/css" />
<link href="templates/css/main.css" rel="stylesheet" type="text/css" />
<link type="text/css" rel="stylesheet" href="templates/js/My97DatePicker/skin/WdatePicker.css">
<script type="text/javascript" src="templates/js/city.js?v=1"></script>
<script type="text/javascript" src="templates/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="templates/js/My97DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="templates/js/onmousemove_minutes.js"></script>
<script><!--
$(function(){
	$("#list-table tr").mouseover(function(){
		$(this).addClass("over");
	}).mouseout(function(){
		$(this).removeClass("over");	
	})
})

function listgroup(){
	if($("#choose").val() == 'group'){
		$("#group").css("display","");
		$("#keyword").css("display","none");
		$("#keyword").attr("value","");
	}

	if($("#choose").val() == 'uid'){
		$("#group").css("display","none");
		$("#keyword").css("display","");
		$("#group").attr("value","");
	}
}

//分页跳转
function gotoPage() {
	var page = $("#pageGo").val();
	var page = parseInt(page);
	
	if(page<1) page = 1;
	if(page><?php echo $page_num;?>)

	page = <?php echo $page_num;?>;
	window.location.href = "<?php echo $currenturl;?>&page="+page;
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
<span class="action-span1"><a href="###">真爱一生网 管理中心</a> </span><span id="search_id" class="action-span1"> - 财务报表</span>
<span class="action-span"><a href="index.php?action=financial_totalwage&h=list">刷新</a></span>
<div style="clear:both"></div>
</h1>
<div class="list-div" style="padding:5px;" onmouseover="$('#aboutorder').hide()">
<form action="" method="get">
  <tr border="2">
  	<td>
     <select name="choose" id="choose" onChange="listgroup()">
	    <option value="">请选择搜索条件</option>
	    <option value="uid" <?php if($choose=='uid') { ?>selected="selected"<?php } ?>>员工</option>
	    <option value="group" <?php if($choose=='group') { ?>selected="selected"<?php } ?>>小组</option>
	 </select>
	 <select name="group" id="group" <?php if($choose!='group') { ?> style="display:none" <?php } ?>>
	    <option value="">请选择小组</option>
	    <?php foreach((array)$group_arr as $group) {?>
	    <option <?php if($groups == $group['id']) { ?>selected="selected"<?php } ?> value="<?php echo $group['id'];?>"><?php echo $group['manage_name'];?></option>
		<?php } ?>
	 </select>
	 </td>
	 <td>&nbsp;&nbsp;
	 </td>
	 <td>
		<input name="keyword" type="text" id="keyword" <?php if($choose!='uid') { ?> style="display:none" <?php } ?> value="<?php if(!$groups) { ?><?php echo $keyword;?><?php } ?>"/>
		<input type="hidden" name="action" value="financial_totalwage" />
		<input type="hidden" name="h" value="<?php echo $GLOBALS['h'];?>" />
		
		 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;日期：
		<input realvalue="" name="bgtime" value="<?php if($bgtime) { ?><?php echo $bgtime;?><?php } else { ?><?php echo date('Y-m-d',(time()-60*60*24*30));?><?php } ?>" onfocus="WdatePicker({startDate:'%y-%M-%D 00:00:00',dateFmt:'yyyy-MM-dd',alwaysUseStartDate:true})" style="width: 100px;" type="text">
	 &nbsp;到
		<input realvalue="" name="endtime" value="<?php if($endtime) { ?><?php echo $endtime;?><?php } else { ?><?php echo date('Y-m-d',time()+60*60*24);?><?php } ?>" onfocus="WdatePicker({startDate:'%y-%M-%D 00:00:00',dateFmt:'yyyy-MM-dd',alwaysUseStartDate:true})" style="width: 100px;" type="text">
	 &nbsp;&nbsp;
	
		
		<input name="" type="submit" class="button" value="搜 索"/>
		
		<input type="submit" class="button"  name="export" value=" 导出 ">
	</td>
	  </tr>
</form>
</div>
<p/>
<div class="list-div" id="listDiv">
<table cellspacing='1' cellpadding='3' id='list-table'>
  <tr>
    <th>员工</th>  
    <th>成功订单金额</th>
    <th>电话时间金额</th>
    <th>月份</th>
    <th>提成百分比</th>
    <th>薪水提成总计</th>
     <th>备注</th>
  </tr>

  </table>

  <div style="padding:5px;background:#fff">
	
</div>

<!-- 分页 -->
<!--  <table cellpadding="4" cellspacing="0">
    <tr>
      <td align="center"><?php echo $pages;?>
      	&nbsp;&nbsp;&nbsp;
      	转到第   <input name="pageGo"  id="pageGo" type="text" style="width:20px;height:15px;" value="" /> 页 &nbsp;
      <input type="button"  value="跳转" class="button" onclick="gotoPage()"/></td>
    </tr>
  </table>-->
  <!-- 分页结束 -->
  
</div>
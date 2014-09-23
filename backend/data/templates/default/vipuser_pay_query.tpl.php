<link href="templates/css/general.css" rel="stylesheet" type="text/css" />
<link href="templates/css/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="templates/js/My97DatePicker/WdatePicker.js"></script>

<script type="text/javascript">


//隔行换色
$(function(){
	$(".csstab tr").mouseover(function(){
		$(this).addClass("over");
	}).mouseout(function(){
		$(this).removeClass("over");    
	});
	
	
	$(".csstab tr td a.uid").click(function(){
       var id=$.trim($(this).text());
	   
	   parent.addTab('会员'+id+'资料','index.php?action=allmember&h=view_info&uid='+id);
	});

});

</script>
<style type="text/css" >
    tr.over td {background:#cfeefe;}
    td{word-wrap:break-word;}
	.tb{table-layout:fixed}
</style>
<h1> 
	<span class="action-span1"><a href="###">真爱一生网 管理中心</a> </span><span id="search_id" class="action-span1"> - 支付会员来源</span>
	<span class="action-span"><a href="<?php $u=explode('&',$_SERVER['QUERY_STRING']);?>index.php?<?php echo $u[0];?>&<?php echo $u[1];?>">刷新</a></span>
	<div style="clear:both"></div>
</h1>
<div class="list-div" style="padding:5px;">
	<form action="index.php?action=vipuser&h=pay_query" method="POST">

		
		时间：
		<input type="text" name="apply_time1" value="<?php if($apply_time1) echo $apply_time1;?>" onFocus="WdatePicker({startDate:'%y-%M-%D 00:00:00',dateFmt:'yyyy-MM-dd',alwaysUseStartDate:true})" style="width:100px;"/>到
		<input type="text" name="apply_time2" value="<?php if($apply_time2) echo $apply_time2;?>" onFocus="WdatePicker({startDate:'%y-%M-%D 00:00:00',dateFmt:'yyyy-MM-dd',alwaysUseStartDate:true})" style="width:100px;"/>
		会员ID:
		<input type="text" name="uid" />

		
		<input name="提交" type="submit" value="搜 索" />
		<span class="but_a">共<?php echo $total;?>条</span>
	</form>
</div>
	
<p/>
<div class="list-div " id="listDiv">
	<table  id='list-table' class ="csstab tb">
		  <tr>
		    <th width="5%">序号</th>
			<th width="5%">所属客服</th>
			<th width="10%">申请时间</th>
			<th width="8%">会员ID</th>
			<th width="2%">性别</th>
			<th width="10%">购买信息</th>
			
			<th width="8%">支付金额</th>
			<th width="10%">支付时间</th>
            <th width="5%">支付类型</th>
            <th width="10%">支付银行</th>
			<th width="22%">来源</th>
            <th width="5%">渠道</th>
			
		  </tr>
		  <?php foreach((array)$payment_list as $k=>$pay) {?>
		   <tr id="tr_<?php echo $pay['id'];?>">
		    <td align="center"><?php echo $k+1;?></td>
			<td align="center"><a href="index.php?action=vipuser&h=pay_query&sid=<?php echo $pay['apply_sid'];?>"><?php echo $GLOBALS['kefu_arr'][$pay['apply_sid']];?></a></td>
			<td align="center"><?php if($pay['apply_time'])	echo date('Y-m-d H:i:s',$pay['apply_time'])?></td>
			<td align="center"><a class="uid" href="#"><?php echo $pay['uid'];?></a></td>
			<td align="center"><?php if($pay['gender']) { ?>女<?php } else { ?>男<?php } ?></td>
			<td align="center"><?php echo $pay['apply_note'];?></td>
			
			<td align="center"><?php echo $pay['pay_money'];?>元</td>
			<td align="center"><?php if($pay['pay_time']) echo date('Y-m-d H:i:s',$pay['pay_time'])?></td>
            <td align="center"><?php if($pay['pay_type'] == 1) { ?>线下支付<?php } elseif ($pay['pay_type'] == 2) { ?>网银支付<?php } elseif ($pay['pay_type'] == 3) { ?>电话支付<?php } ?></td>
            <td><?php if($pay['pay_type']==3) { ?><?php echo @$tel_bankarr[$pay['pay_bank']]?><?php } else { ?><?php echo $bankarr[$pay['pay_bank']]?><?php } ?></td>
			<?php $url=urldecode($pay['source']);preg_match('/st=\w+/', $url, $matches);?>
			<td align="center"><?php echo urldecode($pay['source'])?></td>
            <td align="center"><a href="index.php?action=vipuser&h=pay_query&source=<?php echo $matches[0];?>"><?php echo $matches[0];?></a></td>
			
           
            
		  </tr>
		  <?php }?>
			<tr>
			<td align="center" colspan="15"><?php echo $page_links;?>
				&nbsp;&nbsp;&nbsp;
				转到第
				<input name="pageGo"  id="page" type="text" style="width:20px;height:15px;" value="" /> 页 &nbsp;
				<input type="button"  class="ser_go" value="跳转" onclick="gotoPag()"/>
			</td>
		  </tr>
	  </table>
</div>
<script>
	//分页跳转
	function gotoPag() {
		var page = $("#page").val();
		var page = parseInt(page);
		if(page<1) page = 1;
		if(page><?php echo ceil($total/$page_per);?>)
	
		//page = <?php echo ceil($total/$page_per);?>;
		
		window.location.href = "<?php echo $currenturl;?>&page="+page;
	}
</script>

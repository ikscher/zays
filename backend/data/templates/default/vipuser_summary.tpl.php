<link href="templates/css/general.css" rel="stylesheet" type="text/css" />
<link href="templates/css/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="templates/js/My97DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="templates/js/onmousemove_minutes.js"></script>
<h1> 
	<span class="action-span1"><a href="###">真爱一生网 管理中心</a> </span><span id="search_id" class="action-span1"> - 会员升级支付汇总</span>
	<div style="clear:both"></div>
</h1>
<div class="list-div" style="padding:5px;">
	<form action="" method="get">
		时间：
		<input type="text" name="start_time" value="<?php if(isset($_GET['start_time'])) { ?><?php echo $_GET['start_time'];?><?php } ?>" onFocus="WdatePicker({startDate:'%y-%M-%D 00:00:00',dateFmt:'yyyy-MM-dd',alwaysUseStartDate:true})" style="width:100px;"/>到
		<input type="text" name="end_time" value="<?php if(isset($_GET['end_time'])) { ?><?php echo $_GET['end_time'];?><?php } ?>" onFocus="WdatePicker({startDate:'%y-%M-%D 00:00:00',dateFmt:'yyyy-MM-dd',alwaysUseStartDate:true})" style="width:100px;"/>
        <input type="hidden" name="action" value="vipuser" />
		<input type="hidden" name="h" value="vip_summary" />
		<?php if(isset($_GET['pay_service'])) { ?><input type="hidden" name="pay_service" value="<?php echo $pay_service;?>" /><?php } ?>
		<input name="提交" type="submit" value="查看汇总" />
	</form>
</div>
<?php if($note) { ?><span style="float:left;margin-left:10px;padding:5px;"><?php echo $note;?></span><?php } ?>
<br /><br />
<div class="list-div">
<p>总体成单数及金额可以汇总</p>
	<table cellspacing='1' cellpadding='3' id='list-table' class ="csstab">
		  <tr>
			<th>数量</th>
			<th>销售总金额</th>
			<th>购买类型</th>
			<th>查看</th>
		  </tr>
		  <?php foreach((array)$totalCount as $c) {?>
			<tr>
            	<td><?php echo $c['c'];?></td>
                <td><?php echo $c['qian'];?></td>
                <td><?php echo @$GLOBALS['apply_member_level'][$c['pay_service']]?></td>
				<td><a href="#" onclick="parent.addTab('<?php echo @$GLOBALS['apply_member_level'][$c['pay_service']];?> 升级详细','index.php?action=vipuser&h=vip_summary&pay_service=<?php echo isset($c['pay_service'])?$c['pay_service']:'';?>','icon')">查看详细</a></td>
			</tr>
		  <?php } ?>
	  </table>
</div>
<!-- <div class="list-div">
<p>每个客服的成单数及金额可以汇总</p>
      <table cellspacing='1' cellpadding='3' id='list-table' class ="csstab">
		  <tr>
			<th>客服</th>
			<th>成功订单数</th>
			<th>销售金额</th>
		  </tr>
		  <?php foreach((array)$everyCount as $c) {?>
			<tr>
            	<td><?php echo $GLOBALS['kefu_arr'][$c['apply_sid']]?></td>
                <td><?php echo $c['num'];?></td>
                <td><?php echo $c['total'];?></td>
			</tr>
		  <?php } ?>
	  </table>
</div> -->
<div class="list-div">
<p>每个小组的成单数及金额可以汇总</p>
      <table cellspacing='1' cellpadding='3' id='list-table' class ="csstab">
		  <tr>
			<th>组</th>
			<th>销售总单数</th>
			<th>销售总金额</th>
			<th>查看</th>
		  </tr>
		  <?php foreach((array)$groupCount as $k=>$c) {?>
			<tr>
            	<td><?php echo $k;?></td>
                <td><?php echo $c[0]['num'];?></td>
                <td><?php echo $c[0]['total'];?></td>
				<td><a href="#" onclick="parent.addTab('<?php echo $k;?> 升级详细','index.php?action=vipuser&h=getvip_group&groupid=<?php echo $c['id'];?>','icon')">查看详细</a></td>
			</tr>
		  <?php }?>
	  </table>
</div>

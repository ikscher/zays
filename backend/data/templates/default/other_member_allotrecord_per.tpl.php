<link href="templates/css/general.css" rel="stylesheet" type="text/css" />
<link href="templates/css/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/onmousemove_minutes.js"></script>
<h1><span class="action-span1"><a href="###">真爱一生网 管理中心</a> </span><span id="search_id" class="action-span1"> - 会员分配记录列表</span>
<span class="action-span"><a href="<?php $u=explode('&',$_SERVER['QUERY_STRING']);?>index.php?<?php echo $u[0];?>&<?php echo $u[1];?>&<?php echo $u[2];?>">刷新</a></span>
	<div style="clear:both"></div>
</h1>
<p/>
<div class="list-div" id="listDiv">
	<table cellspacing='1' cellpadding='3' id='list-table'>
		<tr>
			<th>用户ID</th>
			<th>分配客服ID</th>
			<th>接收分配客服ID</th>
			<th>分配情况</th>
			<th><a href="index.php?action=other&h=member_allotrecord_per&order=<?php echo $order;?>&type=<?php echo $type;?>&keyword=<?php echo $keyword;?>&page=<?php echo $page;?>">分配时间<img src="templates/images/<?php if($order =='ASC') { ?>desc<?php } else { ?>asc<?php } ?>.gif" width="12" height="12"  style="border:none"/></a></th>
		</tr>
		<?php foreach((array)$ret as $v) {?>
		<tr>
			<td align="center"><a href="#" onclick="parent.addTab('查看<?php echo $v['uid'];?>资料','index.php?action=allmember&h=view_info&uid=<?php echo $v['uid'];?>','icon')"><?php echo $v['uid'];?></a></td>
			<td align="center"><?php echo $v['sid'];?></td>
			<td align="center"><?php echo $v['allot_sid'];?></td>
			<td align="center"><?php echo $v['allot_con'];?></td>
			<td align="center"><?php echo date("Y-m-d H:i",$v['allot_time']);?></td>
		</tr>
		<?php } ?>
		<tr>
			<td colspan="5" align="center"><?php echo $pages;?></td>
		</tr>
	</table>
</div>

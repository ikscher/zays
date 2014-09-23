<style type="text/css">
#kfmanage_float{
	border:1px solid #1C3F80;
	background:#CEDAEA;
	position:absolute;
	left:400px;
	top:200px;
	width:600px;
}
</style>
<script type="text/javascript">
//note 注册拖动
$(function(){
	var myDrag=new Endrag("kfmanage_movetop","kfmanage_float",0,0); 
})

var iscontact = 1;
function kfmanage_hideDiv(){
	$("#kfmanage_float").remove();
	iscontact = 0;
}
function kfgopage(p){
	var url = './allmember_ajax.php?n=kfmanage&uid=<?php echo $uid;?>&page='+p;
	$.get(url,function(data){
		$("#kfmanage_float").remove();
		$("body").append(data);
	});	
}
</script>
<div id="kfmanage_float">
<div id="kfmanage_movetop" style="background-color:#3E679A; text-align:center; color:#FFF; font-weight:bold; height:20px; padding-top:10px; cursor:move">客服操作记录(<?php echo $total;?>)<div style="float:right; width:30px; position:absolute;top:2px; right:0px;"><a href="javascript:kfmanage_hideDiv();" style=" color:#CCC">关闭</a></div></div>

	<table width="100%" cellspacing="1" cellpadding="3" style=" background:#FFF">
		<tr style="background:#7F99BE">
			<th height="22">客服名称及ID</th>
			<th>操作内容</th>
			<th>操作日期</th>
		</tr>
		<?php foreach((array)$logs as $v) {?>
		<tr style="background-color:#CEDAEA; height:26px;">
			<td align="center"><?php echo $GLOBALS['kefu_arr'][$v['sid']]?>(<?php echo $v['sid'];?>)</td>
			<td align="left"><?php echo $v['handle'];?></td>
			<td align="center"><?php echo date('Y-m-d H:i:s',$v['time']);?></td>
		</tr>
		<?php } ?>
	</table>

	<?php if($pages>=2) { ?><br/><div style="text-align:center">第&nbsp;<?php for($i=1;$i<=$pages;$i++){?>
	<a href="javascript:kfgopage(<?php echo $i;?>);"style="text-decoration:none;<?php if($page==$i) { ?>border:1px solid #3E679A; background:#7F99BE;<?php } ?>">&nbsp;<?php echo $i;?>&nbsp;</a>&nbsp;
<?php } ?>&nbsp;页</div><?php } ?>
</div>
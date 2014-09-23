<link href="templates/css/general.css" rel="stylesheet" type="text/css" />
<link href="templates/css/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="templates/js/My97DatePicker/WdatePicker.js"></script>

<script type="text/javascript">
var upgrade_local = false;//升级过程中锁定，防止多次请求

function upgrade_order(pid,uid){
	
	var ch_url = "vipuser_ajax.php?n=upgrade_pay&pid="+pid+"&uid="+uid;
	if(confirm('你确定将此订单设为有效订单吗？')){
	       $.get(ch_url,function(str){
	            if(str=='1'){
					alert("此订单已设为有效！");
					$("#order_tda_"+pid).hide();
					$("#status_2_"+pid).hide();
					$("#order_td_"+pid).html('已确认汇款');
					
				}else{
					alert(str);
				}
	       });
	}	
}
function change_upgrade_status2(uid,pid,status){
	var ch_url = "vipuser_ajax.php?n=upgrade_nopay&uid="+uid+"&pid="+pid;
	if(confirm('您确定此操作吗？')){
	       $.get(ch_url,function(str){
	            if(str=='ok'){
					alert("操作成功！");
					$("#order_tda_"+pid).hide();
					$("#status_2_"+pid).hide();
					$("#order_td_"+pid).html('无效申请');
					//window.location=ch_url;
				}else{
					alert(str);
				}
	       });
	}	
}

$(function(){
	$("#group_change").change(function(){
		  var groupid = this.value;
		  $.get("vipuser_ajax.php?n=get_groupmember&id="+groupid,function(str){
	           if(str == 'no'){
				alert('此组无成员');
			   }else{
			   $("#kefu_sid").empty();
				$("#kefu_sid").append(str);
			   }
	       });
	});
});


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
    tr.over td {
    background:#cfeefe;
    } 
   
</style>
<h1> 
	<span class="action-span1"><a href="###">真爱一生网 管理中心</a> </span><span id="search_id" class="action-span1"> - 补款或支付</span>
	<span class="action-span"><a href="<?php $u=explode('&',$_SERVER['QUERY_STRING']);?>index.php?<?php echo $u[0];?>&<?php echo $u[1];?>">刷新</a></span>
	<div style="clear:both"></div>
</h1>
<div class="list-div" style="padding:5px;">
	<form action="index.php?action=vipuser&h=pay_other" method="post">
		组别:
		<select name="group" id="group_change">
			<option value="">请选择</option>
			<?php foreach((array)$group_list as $list) {?>
			<option value="<?php echo $list['id'];?>"><?php echo $list['manage_name'];?></option>
			<?php } ?>
		</select>
		人员:
		<select name="sid" id="kefu_sid">
			<option value="">请选择</option>
		</select>
		
		时间：
		<input type="text" name="apply_time1" value="<?php if($apply_time1) echo $apply_time1;?>" onFocus="WdatePicker({startDate:'%y-%M-%D 00:00:00',dateFmt:'yyyy-MM-dd',alwaysUseStartDate:true})" style="width:100px;"/>到
		<input type="text" name="apply_time2" value="<?php if($apply_time2) echo $apply_time2;?>" onFocus="WdatePicker({startDate:'%y-%M-%D 00:00:00',dateFmt:'yyyy-MM-dd',alwaysUseStartDate:true})" style="width:100px;"/>
		会员ID:
		<input type="text" name="uid" />
		
		<!-- 是否通过
		<select name="status">
			<option value="">全部</option>
			<option value="1">确认付款</option>
			<option value="2">无效申请</option>
		</select> -->
		
		<input name="提交" type="submit" value="搜 索" />
		<span class="but_a">
        
        <!-- <a href="index.php?action=vipuser&h=pay_other&check_order_sid=0">未处理</a><a href="index.php?action=vipuser&h=pay_other&check_order_sid=1">已处理</a> -->
        
		</span>
	</form>
</div>
	
<p/>
<div class="list-div" id="listDiv">
	<table cellspacing='1' cellpadding='3' id='list-table' class ="csstab">
		  <tr>
			<th>所属客服</th>
			<th>申请时间</th>
			<th>会员ID</th>
			<th>购买信息</th>
			
			<th>支付金额</th>
			<th>支付时间</th>
            <th>支付类型</th>
            <th>支付银行</th>
			
			<th>是否通过</th>
		  </tr>
		  <?php foreach((array)$payment_list as $pay) {?>
		   <tr id="tr_<?php echo $pay['id'];?>">
			<td align="center"><?php echo $pay['apply_sid'];?> : <?php echo $GLOBALS['kefu_arr'][$pay['apply_sid']];?></td>
			<td align="center"><?php if($pay['apply_time'])	echo date('Y-m-d H:i:s',$pay['apply_time'])?></td>
			<td align="center"><a class="uid" href="#"><?php echo $pay['uid'];?></a></td>
			<td align="center"><?php echo $pay['note'];?></td>
			
			<td align="center"><?php echo $pay['pay_money'];?>元</td>
			<td align="center"><?php if($pay['pay_time']) echo date('Y-m-d H:i:s',$pay['pay_time'])?></td>
            <td align="center"><?php if($pay['pay_type'] == 1) { ?>线下支付<?php } elseif ($pay['pay_type'] == 2) { ?>网银支付<?php } elseif ($pay['pay_type'] == 3) { ?>电话支付<?php } ?></td>
            <td><?php if($pay['pay_type']==3) { ?><?php echo @$tel_bankarr[$pay['pay_bank']]?><?php } else { ?><?php echo $bankarr[$pay['pay_bank']]?><?php } ?></td>
			
            
			<td align="center">
				<?php if($pay['status'] == '0') { ?>
				<a id="order_tda_<?php echo $pay['id'];?>" href="javascript:upgrade_order(<?php echo $pay['id'];?>,<?php echo $pay['uid'];?>);">确认汇款</a>
                <a id="status_2_<?php echo $pay['id'];?>" href="javascript:change_upgrade_status2(<?php echo $pay['uid'];?>,<?php echo $pay['id'];?>,2);">无效申请</a>
				<?php } elseif ($pay['status'] == '1') { ?>
				 <span style="color:#00BF00">已确认汇款</span>
                <?php } elseif ($pay['status'] == '2') { ?>
                <span style="color:#F00">无效申请</span>
				<?php } ?>
                <span style="color:#00BF00" id="order_td_<?php echo $pay['id'];?>"></span>
                
			</td>
           
            
		  </tr>
		  <?php } ?>
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

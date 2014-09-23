<link href="templates/css/general.css" rel="stylesheet" type="text/css" />
<link href="templates/css/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="templates/js/My97DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="templates/js/onmousemove_minutes.js"></script>
<script type="text/javascript">
var upgrade_local = false;//升级过程中锁定，防止多次请求
function change_upgrade_status(uid,pid,status){
	if(upgrade_local) return;
	upgrade_local = true;
	var ch_url = "vipuser_ajax.php?n=upgrade_new&uid="+uid+"&pid="+pid;
	if(confirm('你确定此会员已支付并让其升级吗？')){
	       $.get(ch_url,function(str){
	            if(str=='ok'){
					//成功升级祝贺提醒
					//parent.congratulate_remark();
					alert("升级会员成功！");
					$("#tr_"+pid).css('display','none');
				}else{
					alert(str);
				}
	       });
	}
	upgrade_local = false;
}
function upgrade_order(pid,uid){
	var ch_url = "vipuser_ajax.php?n=upgrade_order&pid="+pid+"&uid="+uid;
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
	var ch_url = "vipuser_ajax.php?n=upgrade_new2&uid="+uid+"&pid="+pid;
	if(confirm('您确定此操作吗？')){
	       $.get(ch_url,function(str){
	            if(str=='ok'){
					alert("操作成功！");
					$("#order_tda_"+pid).hide();
					$("#status_2_"+pid).hide();
					$("#order_td_"+pid).html('无效申请');
				}else{
					alert(str);
				}
	       });
	}	
}


function change_downgrade_status(pid){
	var url = "vipuser_ajax.php?n=downgrade_status&pid="+pid;
	if(confirm('您确定此操作吗？')){
	       $.get(url,function(str){
	            if(str=='ok'){
					alert("操作成功！");
					$("#tr_"+pid).hide();
				}else{
					alert("操作失败！");
				}
	       });
	}	
}


$(function(){
    
	$(".csstab tr").mouseover(function(){
        $(this).addClass("over");
    }).mouseout(function(){
        $(this).removeClass("over");    
    });
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
	})
	
	
	$("#listDiv tr td a").css("text-decoration","none");
    
	$("#listDiv tr td a").mouseover(function(){
	    $(this).css("text-decoration","underline");
	  
	}).mouseout(function(){
	    $(this).css("text-decoration","none");
	});
	
	$("#listDiv tr td a.uid").click(function(){
	   var id=$.trim($(this).text());
	   
	   parent.addTab('会员'+id+'资料','index.php?action=allmember&h=view_info&uid='+id);
	
	});
	
	
	
});



//分页跳转
function gotoPage() {
	var page = $("#pageGo").val();
	var page = parseInt(page);
	
	if(page<1) page = 1;
	if(page><?php echo ceil($total/$page_per);?>)

	page = <?php echo ceil($total/$page_per);?>;
	window.location.href = "<?php echo $currenturl;?>&page="+page;
}
</script>
<style type="text/css">
tr.over td {
	background:#cfeefe;
} 
</style>
<h1> 
	<span class="action-span1"><a href="###">真爱一生网 管理中心</a> </span><span id="search_id" class="action-span1"> - 会员升级支付列表</span>
	<span class="action-span"><a href="index.php?action=vipuser&h=apply_list">刷新</a></span>
	<div style="clear:both"></div>
</h1>
<div class="list-div" style="padding:5px;">

	<form action="" method="get">
		组别:
		<select name="group" id="group_change">
			<option value="">请选择</option>
			<?php foreach((array)$group_list as $list) {?>
			<option value="<?php echo $list['id'];?>" <?php if($list['id']==$id) { ?> selected="selected"<?php } ?> ><?php echo $list['manage_name'];?></option>
			<?php } ?>
		</select>
		人员:
		<select name="apply_sid" id="kefu_sid">
			<option value="" ><?php if($apply_sid) { ?> <?php echo $apply_sid;?> <?php } else { ?>请选择<?php } ?></option>
		</select>
		
		时间：
		<input type="text" name="apply_time1" value="" onFocus="WdatePicker({startDate:'%y-%M-%D 00:00:00',dateFmt:'yyyy-MM-dd',alwaysUseStartDate:true})" style="width:100px;"/>到
		<input type="text" name="apply_time2" value="" onFocus="WdatePicker({startDate:'%y-%M-%D 00:00:00',dateFmt:'yyyy-MM-dd',alwaysUseStartDate:true})" style="width:100px;"/>
		会员ID:
		<input type="text" name="uid" />
		
		是否通过
		<select name="status">
			<option value="">全部</option>
			<option value="1">确认付款</option>
			<option value="2">无效申请</option>
            <option value="3">组长升级</option>
			<option value="0">未处理</option>
		</select>
		<input type="hidden" name="action" value="vipuser" />
		<input type="hidden" name="h" value="apply_list" />
		<input name="提交" type="submit" value="搜 索" />
		<span class="but_a">
        <?php if(!$check_order) { ?>
		<!--<a href="index.php?action=vipuser&h=apply_list&status=0">未审核</a><a href="index.php?action=vipuser&h=apply_list&status=1">审核通过</a><a href="index.php?action=vipuser&h=apply_list&status=2">已删除的</a>-->
        <?php } else { ?>
        <a href="index.php?action=vipuser&h=apply_list&check_order_sid=0">未处理</a><a href="index.php?action=vipuser&h=apply_list&check_order_sid=1">已处理</a>
        <?php } ?>
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
			<th>购买服务</th>
			<th>是否赠送城市之星</th>
			<th>支付金额</th>
			<th>支付时间</th>
            <th>支付类型</th>
            <th>支付银行</th>
			<th>审核人</th>
			<th>审核时间</th>
			<th>联系方式</th>
			<th>支付信息</th>
			<th>申请备注</th>
			<th>是否通过</th>
		  </tr>
		  <?php foreach((array)$payment_list as $pay) {?>
		   <tr id="tr_<?php echo $pay['id'];?>">
			<td align="center"><?php echo $GLOBALS['kefu_arr'][$pay['apply_sid']];?></td>
			<td align="center"><?php if(!empty($pay['apply_time']))	echo date('Y-m-d H:i:s',$pay['apply_time'])?></td>
			<td align="center"><a  class="uid" href="#"><?php echo $pay['uid'];?><a></td>
			<td align="center"><?php if($pay['pay_service']==-1) { ?>（六个月）铂金会员<?php } else { ?><?php echo $GLOBALS['apply_member_level'][$pay['pay_service']];?><?php } ?> <?php if($pay['plus_time']>0) { ?><?php echo $pay['plus_time'];?> 个月<?php } ?></td>
			<td align="center"><?php if($pay['give_city_star']) { ?>是<?php } else { ?>否<?php } ?></td>
			<td align="center"><?php echo $pay['pay_money'];?>元</td>
			<td align="center"><?php if($pay['pay_time']) echo date('Y-m-d H:i:s',$pay['pay_time'])?></td>
            <td align="center"><?php if($pay['pay_type'] == 1) { ?>线下支付<?php } elseif ($pay['pay_type']==2) { ?>网银支付<?php } elseif ($pay['pay_type']==3) { ?>电话支付<?php } ?></td>
            <td><?php if($pay['pay_type']==3) { ?><?php echo @$tel_bankarr[$pay['pay_bank']]?><?php } else { ?><?php echo @$bankarr[$pay['pay_bank']]?><?php } ?></td>
			<td align="center">
            <?php echo @$GLOBALS['kefu_arr'][$pay['check_order_sid']],',',@$GLOBALS['kefu_arr'][$pay['check_sid']]?>
            
            </td>
			<td align="center"><?php if($pay['check_time']) echo date('Y-m-d H:i:s',$pay['check_time'])?></td>
			<td align="center"><?php echo $pay['contact'];?></td>
			<td align="center"><?php echo $pay['pay_info'];?></td>
			<td align="center"><?php echo $pay['apply_note'];?></td>
            <?php if($check_order) { ?>
			<td align="center">
				<?php if($pay['status'] == '0') { ?>
				<a id="order_tda_<?php echo $pay['id'];?>" href="javascript:upgrade_order(<?php echo $pay['id'];?>,<?php echo $pay['uid'];?>);">确认汇款</a>
                <a id="status_2_<?php echo $pay['id'];?>" href="javascript:change_upgrade_status2(<?php echo $pay['uid'];?>,<?php echo $pay['id'];?>,2);">无效申请</a>
				<?php } elseif ($pay['status'] == '1') { ?>
				 <span style="color:#00BF00">已确认汇款</span>
                <?php } elseif ($pay['status'] == '2') { ?>
                <span style="color:#F00">无效申请</span>
                <?php } else { ?>
                <span style="color:#0FF">组长已升级</span>
				<?php } ?>
                <span style="color:#00BF00" id="order_td_<?php echo $pay['id'];?>"></span>
                
			</td>
            <?php } else { ?>
            <td align="center">
				<?php if($pay['status'] == 1) { ?>
				<a href="javascript:change_upgrade_status(<?php echo $pay['uid'];?>,<?php echo $pay['id'];?>,1);">确定升级</a>
				<?php } ?>
			</td>
            <?php } ?>
			
			<?php if(in_array($GLOBALS['groupid'],$GLOBALS['system_admin'] ) && $pay['status']=='1') { ?>
			<td align="center">
			    <a id="downgrade_<?php echo $pay['id'];?>" href="javascript:change_downgrade_status(<?php echo $pay['id'];?>);">未付款</a>
			</td>
			<?php } ?>
			
		  </tr>
		  <?php } ?>
			<tr>
			<td align="center" colspan="15"><?php echo $page_links;?>
				&nbsp;&nbsp;&nbsp;
				转到第
				<input name="pageGo"  id="pageGo" type="text" style="width:20px;height:15px;" value="" /> 页 &nbsp;
				<input type="button"  class="ser_go" value="跳转" onclick="gotoPage()"/>
			</td>
		  </tr>
	  </table>
</div>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="templates/css/general.css" rel="stylesheet" type="text/css" />
<link href="templates/css/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../public/system/js/sys1.js?v=1"></script>
<script type="text/javascript" src="templates/js/My97DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="templates/js/onmousemove_minutes.js"></script>
<script>
	function check_from(){
		var uid = $("#uid").val();
		var pay_money = $("#pay_money").val();
		var pay_service = $("#pay_service option:selected").val();
		
		var re=/^[0-9]+.?[0-9]*$/;
		if(!re.test(pay_money)){
			alert('金额必须是正整数');
			return false;
		}
		if(pay_service == ''){
			alert('请选择购买服务');
			return false;
		}
		if(pay_service == 3){
			var temstr = '';
			var old_scid = $('#old_scid').val();
			if(old_scid == 30){
				temstr = "高级会员";
			}else if(old_scid == 20){
				temstr = "钻石会员";
			}
			if(!confirm('你确定申请会员：'+uid+'续费为'+temstr+'吗？')){
				return false;
			}
		}else{
			var temstr = $("#hide_reamrk").val();
			if(!confirm('你确定申请会员：'+uid+'升级为'+temstr+'吗？')){
				return false;
			}
		}
		
		if($.trim(uid) == ''){
			alert('会员ID必须填写');
			return false;
		}
		return true;
	}
function showplus(){
	var k = $("#pay_service option:selected").val();
	var remark_arr = Array('钻石会员','三个月高级会员','城市之星','续费','一个月高级会员','高级会员升钻石会员','六个月铂金会员','七夕报名费');
	if(k == 3){
		$("#plus_sel").show();
		$("#old_scid").show();
	}else{
		$("#plus_sel").hide();
		$("#old_scid").hide();
	}
	$("#hide_reamrk").val(remark_arr[k]);
}
</script>
<style>
tr.over td {
	background:#cfeefe;
} 

#listDiv .desc{
	color:#333;
	font-weight:bold;
}
.area_select{
	width:100px;
}

#new_add table tr td{
	line-height:30px;
	font-weight:bold;
}
</style>
</head>
<body>
<h1 style="margin-bottom:15px;">
	<span class="action-span"><a href="index.php?action=vipuser&h=<?php echo $_GET['h'];?>">刷新</a></span>
	<span class="action-span1"><a href="###">真爱一生网 管理中心</a> </span><span id="search_id" class="action-span1"> ------- 会员升级申请 </span>
	<div style="clear:both"></div>
</h1>


<div class="list-div" id="listDiv" style="background:#fff;">
  <div style="height:10px;border-bottom:1px solid #999;margin:10px auto;"></div>
  
  <div id="new_add" style="margin-left:50px;">
  	<?php if($t != 'remark') { ?>
	<form action="" method="post" onsubmit="return check_from();">
	<table>
	<tr>
		<td width="20%">　会员ID<input type="text" name="uid" id="uid" /></td>
		<td width="20%">申请时间<input type="text" name="apply_time" value="<?php echo date('Y-m-d H:i:s')?>" onFocus="WdatePicker({startDate:'%y-%M-%D 00:00:00',dateFmt:'yyyy-MM-dd HH:mm:ss',alwaysUseStartDate:true})" /></td>
		<!--
		<td>支付方式
			<select name="pay_type">
				<option value="">请选择</option>
				<option value="1">线下</option>
				<option value="2">支付宝</option>
			</select>
		</td>-->
	</tr>
	<tr>
		<td>支付金额<input type="text" name="pay_money" id="pay_money" value="" size="8" maxlength="8" />格式：<span style="color:#F00">699.99</span></td>
		<td>支付银行
			<select name="pay_bank">
				<option value="">请选择</option>
				<?php foreach((array)$GLOBALS['bankarr'] as $key=>$bank) {?>
				<option value="<?php echo $key;?>"><?php echo $bank;?></option>
				<?php }?>
				</select>
		</td>
		<td>
			购买服务
			<select onchange="showplus();" name="pay_service" id="pay_service">
				<option value="">请选择</option>
				<?php foreach((array)$GLOBALS['apply_member_level'] as $key=>$level) {?>
				<option value="<?php echo $key;?>"><?php echo $level;?></option>
				<?php }?>
			</select>
            <select id="plus_sel" style="display:none" name="plustime">
            	<option value="0">选择续费时长</option>
            	<option value="1">一个月</option>
				<option value="2">两个月</option>
                <option value="3">三个月</option>
				<option value="4">四个月</option>
				<option value="5">五个月</option>
                <option value="6">六个月</option>
                <option value="12">一年</option>
            </select>
			<select id="old_scid" style="display:none" name="old_scid">
            	<option value="20">钻石会员</option>
            	<option value="30" selected="selected">高级会员</option>
            </select>
			<input type="checkbox" name="give_city_star" id="give_city_star" value="1" />勾选赠送城市之星
		</td>
	</tr>
	<tr>
		<td>支付时间
			<input type="text" name="pay_time" value="<?php echo date('Y-m-d H:i:s')?>" onFocus="WdatePicker({startDate:'%y-%M-%D 00:00:00',dateFmt:'yyyy-MM-dd HH:mm:ss',alwaysUseStartDate:true})" />
		</td>
		<td>
			联系方式
			<input type="text" name="contact" value="" />
		</td>
		<td>
			支付信息
			<input type="text" name="pay_info" value="" />
		</td>
	</tr>
	<tr>
		<td cospan="3">
		申请备注
		<input type="text" name="apply_note" value="" />
        <input type="hidden" name="hide_reamrk" id="hide_reamrk" value="" />
		<td>
	</tr>
	</table>
	<div style="margin:30px;"><input type="submit" value="保 存" class="button" />&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" onclick="window.location.href = 'index.php?action=vipuser&h=upgrade_apply&t=remark';" value="补写交接资料" class="button" /></div>
	</form>
    <?php } else { ?>
    <form action="" method="post"  onsubmit="return check_from2();">
    <input type="hidden" name="t" value="remark" />
    <input type="hidden" name="ispost" value="1" />
    <table>
    	<tr>
        	<th colspan="2">会员交接信息填写</th>
        </tr>
    	<tr>
        	<td>会员ID
            	<input type="text" name="uid" value="<?php echo $uid;?>" />
            </td>
        </tr>
        <tr>
        	<td>服务期限
            	<select name="servicetime">
                	<option value="1">一个月</option>
                    <option value="3">三个月</option>
                    <option value="6">六个月</option>
                </select>
                付款金额<input type="text" name="payments" value="<?php echo $payments;?>" size="6" />元
            </td>
        </tr>
        <tr>
        	<td>委托联系的会员ID
            	<input type="text" name="otheruid" value="" />
                模拟聊天记录
                <input type="text" name="chatnotes" value="" />
            </td>
        </tr>
        <tr>
        	<td>升级会员的具体情况
                <textarea cols="60" rows="5" name="intro"></textarea>
           </td>
         </tr>
        <tr>
        	<td>委托会员的具体情况
                <textarea cols="60" rows="5" name="otherintro"></textarea>
            </td>
        </tr>
        <tr>
        	<td>最后一次沟通情况
                <textarea cols="60" rows="5" name="lastcom"></textarea>
            </td>
        </tr>
        <tr>
        	<td>备注信息
                 <textarea cols="60" rows="5" name="remark"></textarea>
            </td>
        </tr>
    </table>
    <div style="margin-left:30px"><input type="submit" value="提交" class="button" /></div>
    </form>
    <?php } ?>
  </div>
</div>
</body>
</html>
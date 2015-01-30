<link href="templates/css/general.css" rel="stylesheet" type="text/css" />
<link href="templates/css/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/city.js?v=1"></script>
<script type="text/javascript" src="templates/js/jquery-1.4.2.min.js"></script>
<!-- <script type="text/javascript" src="templates/js/onmousemove_minutes.js"></script> -->
<script type="text/javascript" src="templates/js/My97DatePicker/WdatePicker.js"></script>
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
	var url = './active_ajax.php?n=leer&id='+id;
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
<span class="action-span1"><a href="###">真爱一生网 管理中心</a> </span><span id="search_id" class="action-span1"> - 会员秋波</span>
<span class="action-span"><a href="index.php?action=active_leer&h=list">刷新</a></span>
<div style="clear:both"></div>
</h1>
<div class="list-div" style="padding:5px;">
<form action="" method="get">
  <tr border="2">
  	<td>
     <select name="choose" id="choose">
	    <option value="">请选择搜索条件</option>
	    <option value="senduid" <?php if($choose=='senduid') { ?>selected="selected"<?php } ?>>发送秋波用户ID</option>
		<option value="receiveuid" <?php if($choose=='receiveuid') { ?>selected="selected"<?php } ?>>接受秋波用户ID</option>
	 </select>
	 </td>
	 <td>&nbsp;&nbsp;
	 </td>
	 <td>
		<input name="keyword" type="text" id="keyword" value="<?php echo $keyword;?>"/>
		<input type="hidden" name="action" value="active_leer" />
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
    <th>发方ID</th>
    <th>发方昵称</th>
    <th>收方ID</th>
    <th>收方昵称</th>
    <th>收方是否删除</th>
    <th>收方删除时间</th>
    <th>发方是否删除</th>
    <th>发方删除时间</th>
    <th>发送时间</th>
	<th>操作</th>
  </tr>
  <?php foreach((array)$user_arr as $v) {?>
  <tr id="tr_<?php echo $v['lid'];?>">
    <td  align="center" ><a href="#" class="userinfo" onClick="parent.addTab('查看<?php echo $v['senduid'];?>资料','index.php?action=allmember&h=view_info&uid=<?php echo $v['senduid'];?>','icon')">
    <span style='color: #33F;'><?php echo $v['senduid'];?></span>
    </a>→</td>
    <td  align="center" ><?php if(isset($v['send_gender']) && $v['send_gender']==1) { ?><img src="templates/images/w.gif" alt="女" title="女"/>
				<?php } else { ?><img src="templates/images/m.gif" alt="男" title="男"/>
				<?php } ?><?php if(isset($v['send_nickname'])) { ?><?php echo $v['send_nickname'];?><?php } ?></td>
    <td align="center">→<a href="#" class="userinfo" onClick="parent.addTab('查看<?php echo $v['receiveuid'];?>资料','index.php?action=allmember&h=view_info&uid=<?php echo $v['receiveuid'];?>','icon')">
    	<span style='color: #33F;'><?php echo $v['receiveuid'];?></span>
    	</a></td>
    <td  align="center" ><?php if(isset($v['receive_gender']) && $v['receive_gender']==1) { ?><img src="templates/images/w.gif" alt="女" title="女"/>
				<?php } else { ?><img src="templates/images/m.gif" alt="男" title="男"/>
				<?php } ?><?php if(isset($v['receive_gender'])) { ?><?php echo $v['receive_nickname'];?><?php } ?></td>
    <td align="center"><?php if($v['receive_del'] == '1') { ?>是<?php } else { ?>否<?php } ?></td>
    <td align="center"><?php if(!empty($v['receive_deltime'])) { ?><?php echo date("Y-m-d h:i:s",$v['receive_deltime'])?><?php } ?></td>
    <td align="center"><?php if($v['send_del'] == '1') { ?>是<?php } else { ?>否<?php } ?></td>
    <td align="center"><?php if(!empty($v['send_deltime'])) { ?><?php echo date("Y-m-d h:i:s",$v['send_deltime'])?><?php } ?></td>
    <td align="center"><?php if(!empty($v['sendtime'])) { ?><?php echo date("Y-m-d h:i:s",$v['sendtime'])?><?php } ?></td>
    <td align="center">
    <?php if($v['dealstate'] == '1') { ?>
           已处理
    <?php } else { ?>
    <a href="#" onclick="dealok(<?php echo $v['lid'];?>);">未处理</a>
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
<div style="margin-top:20px"></div>
<div class="list-div" style="padding:5px;">
	发送者：<input type="text" id="sendfrom" name="sendfrom" maxlength="8" value="" />
	接受者：<input type="text" id="sendto" name="sendto" maxlength="8" value="" />
	<label>指定发送的时间：</label><input type="text" id="sendtime" name="sendtime" value="" onFocus="WdatePicker({sendtime:'%y-%M-%D 00:00:00',dateFmt:'yyyy-MM-dd HH:mm:ss',alwaysUseStartDate:true})" readOnly="true" style="width:150px;"/>
	<input type="submit" name="send" value="发送秋波" />
	<span style="color:red" id="tipMsg"><span>
</div>
<script type="text/javascript">
    //取得当前时间 (默认给违约的创建时间)
	function CurrentTime()
    { 
        var now = new Date();
       
        var year = now.getFullYear();       //年
        var month = now.getMonth() + 1;     //月
        var day = now.getDate();            //日
       
        var hh = now.getHours();            //时
        var mm = now.getMinutes();          //分
		var ss = now.getSeconds();          //秒
       
        var clock = year + "-";
       
        if(month < 10) clock += "0";
        clock += month + "-";
       
        if(day < 10) clock += "0";
        clock += day + " ";
       
        if(hh < 10)  clock += "0";   
        clock += hh + ":";
		
        if (mm < 10) clock += '0'; 
        clock += mm+ ":";
		
		if (ss < 10) clock += '0'; 
        clock += ss;
		
        return(clock); 
    }
	var curtime=CurrentTime();
    document.getElementById('sendtime').value=curtime;	
 
    $('input[name=send]').click(function(){
	    document.getElementById('tipMsg').innerText='';
	    var sendfrom=$('#sendfrom').val();
		var sendto=$('#sendto').val();
		var sendtime=$('#sendtime').val();
		
		var arr=new Array();
		if(!sendfrom ||  !(/\d{8,8}/.test(sendfrom))){
		    arr[0]='请输入发送者ID！';
		}
		
		if(!sendto ||  !(/\d{8,8}/.test(sendto))){
		    arr[1]='请输入接收者ID！';
		}

		if(!sendtime){
		    arr[2]='请输入要发送的时间！';
		}
		
		if(sendfrom && sendto && sendfrom==sendto){
		    arr[3]='发送者和接受者不能一样！'
		}
		
		var str='';
		
		if(arr.length>0){
		    
		    var j=1;
		    for(var i=0;i<arr.length;i++){
			    if(arr[i]) str += (j)+"："+arr[i];
				j++;
			}
			document.getElementById('tipMsg').innerText=str;
		    return false;
		}
		
	
		$.ajax({
			url:'./active_ajax.php?n=sendLeer',
			type:'get',
			data:{sendfrom:sendfrom,sendto:sendto,sendtime:sendtime},
			dataType:'text',
			success:function(str){
			    console.log(str);
			    if(str=='shield'){
				    document.getElementById('tipMsg').innerText='对方已屏蔽';
				}else if(str=='sender'){
				    document.getElementById('tipMsg').innerText='只能模拟全权发送（发送者只能是非本站注册会员）';
				}else if(str=='receiver'){
				    document.getElementById('tipMsg').innerText='不能发送秋波给全权会员';
				}else if(str=='gender'){
				    document.getElementById('tipMsg').innerText='同性别不能发送';
				}else{
				    alert('发送成功！');
					location.href='index.php?action=active_leer&h=list';
				}
			}
		});
	});
</script>
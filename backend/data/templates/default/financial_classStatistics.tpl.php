
<link href="templates/css/general.css" rel="stylesheet" type="text/css" />
<link href="templates/css/main.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="templates/css/jquery-ui.css" rel="stylesheet" type="text/css" type="text/css" media="all"  />
<script type="text/javascript" src="templates/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="templates/js/My97DatePicker/WdatePicker.js"></script>

<script type="text/javascript" src="templates/js/jquery-ui.min.js"></script>


<script type="text/javascript">

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


<!--此段js放这生效 -->
<script type="text/javascript">

function checkForm(){
  var startTime=$("#alloctimeStart").val();
  var endTime=$("#alloctimeEnd").val();
  
   if((!startTime && endTime) || (startTime && !endTime)){
     alert('请选择起始日期和结束日期！');
	 return false;
   }
   
  if(startTime>endTime){
     alert("起始日期不能大于结束日期！");
	 return false;
  }

   return true;
  
} 


$(function(){
	$(".csstab tr").mouseover(function(){
		$(this).addClass("over");
	}).mouseout(function(){
		$(this).removeClass("over");	
	});

});



$(function() {
	// a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
	//$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	$( "#dialog-form" ).dialog({
		bgiframe:true, //兼容IE6
		autoOpen: false,
		height: 600,
		width: 500,
		modal: true,
		position:['top','left'],
		buttons: {
			"确定": function() {
				var bValid = true;
				var sid = $("#sid").html();

				var content_val = $("#remark_content").val();
               
				$.post("ajax.php",{n:'write_remark',content:content_val,sid:sid},function(str){
					tabTar = false;
				    //$("#introduce_"+uid).html(cont_val);
					if(str=='ok'){
					  alert('评论成功！');
					  $("#effectGrade_"+sid).html("是");
					}
				});
				
				$( this ).dialog( "close" );

			},
			"取消": function() {
				$( this ).dialog( "close" );
			}
		}
        /*
        ,
		close: function() {
			allFields.val( "" ).removeClass( "ui-state-error" );
		}*/
	});	
});	


//组长填写评论
function writeRemark(sid){

	
	var content = $("#remark_content");

	$("#sid").html(sid);
	
	$("#writeRemark_"+sid)
	//.button()
	.click(function() {
		$( "#dialog-form" ).dialog( "open" );
		//title.val($("#ser_title_"+s_id).html());
        //content.val($("#introduce_"+uid).html());
		content.val();

	});
}




</script>


<style>
    tr.over td {
        background:#cfeefe;
    } 
	
	label{ display:block; }
	input.text { margin-bottom:12px; width:95%; padding: .4em; }
	fieldset { padding:0; border:0; margin-top:25px; }

	.ui-dialog .ui-state-error { padding: .3em; } 
	.validateTips { border: 1px solid transparent; padding: 0.3em; }
 
</style>
</head>
<body>
<h1 style="margin-bottom:15px;">
	<span class="action-span1"><a href="###">真爱一生网 管理中心</a> </span><span id="search_id" class="action-span1"> - 客服名下会员 类 统计报表 </span>
	<span class="action-span"><a href="index.php?action=financial&h=classStatistics">刷新</a></span>
	<div style="clear:both"></div>
</h1>


<!--填写评语弹出框-->
<div id="dialog-form" title="填写评语">
    <p class="validateTips">请输入您填写的评语。评论对象：<span  id="sid"></span>客服</p> 

		<label for="content">内容</label>
		<textarea cols="74" rows="13" id="remark_content" name="remark_content"  class="text ui-widget-content ui-corner-all" ></textarea>

</div>


<div>
<form action="#" method="get" >
	<div>
		
            <?php if(!empty($group)) { ?>
				 <span >所属组：</span><select name="groupid">
										<option value="" <?php if($groupid=='') { ?>selected="selected"<?php } ?> >请选择</option>
										<?php foreach((array)$group as $k=>$v) {?>
											<option value="<?php echo $v['id'];?>" <?php if($groupid==$v['id']) { ?>selected="selected"<?php } ?>><?php echo $k;?>:<?php echo $v['manage_name'];?></option>
										<?php }?>
									</select>
				 
		    <?php } ?>
			
				<span >时间：</span>
				<!-- <input type="text" id="alloctimeStart" name="alloctimeStart" value="<?php if($startTime) echo $startTime;?>" onFocus="WdatePicker({startDate:'%y-%M-%D 00:00:00',dateFmt:'yyyy-MM-dd',alwaysUseStartDate:true})" style="width:100px;"/> 到 -->
				<input type="text"id="alloctimeEnd" name="alloctimeEnd" value="<?php if($endTime) echo $endTime;?>" onFocus="WdatePicker({startDate:'%y-%M-%D 00:00:00',dateFmt:'yyyy-MM-dd',alwaysUseStartDate:true})"  style="width:100px;"/>
			
			
			<!--
			<td>
				<span >是否评估：</span>
				<select name="reason">
				<option value="">请选择</option>
				
                    <option value="0" <?php if($_GET['reason']==$k) { ?>selected="selected"<?php } ?>>是</option>
					<option value="1" <?php if($_GET['reason']==$k) { ?>selected="selected"<?php } ?>>否</option>
                
				</select>
			</td>
            -->
			
           
                <input type="submit" value="搜索"   />
                <input type="reset" value="重置"  />
            
		        <span class="but_a">
				<a href="<?php echo $currenturl;?>&day=today"<?php if($curdate == 'today') { ?> style="font-weight:bold"<?php } ?>>今天</a>
				<a href="<?php echo $currenturl;?>&day=yesterday"<?php if($curdate == 'yesterday') { ?> style="font-weight:bold"<?php } ?>>昨天</a>
				<a href="<?php echo $currenturl;?>&day=beforeyesterday"<?php if($curdate == 'beforeyesterday') { ?> style="font-weight:bold"<?php } ?>>前天</a>
				</span>

      </div>
	  
	 
      
      <input type="hidden" name="action" value="financial" />
      <input type="hidden" name="h" value="classStatistics"  />
	  
</form>
<div style="height:10px;border-bottom:1px solid #999;margin:10px auto;"></div>
<div class="list-div" id="listDiv">
	<table width="100%" cellspacing='1' cellpadding='1' id='list-table' class ="csstab" style="table-layout:fixed;">
	<!--表头开始-->
	 <tr>
		<th rowspan='2'>客服ID</th>
		<th rowspan='2'>姓名</th>
		<th rowspan='2'>会员<br>总数</th>
		<?php foreach((array)$grade as $k=>$v) {?>
			<th colspan='2' width="7.3%" style="word-break:break-all;overflow:hidden;" ><?php echo ($k-1)."类";?></th>
		<?php }?>
		<th rowspan='2'>是否<br>盘库</th>
		<th rowspan='2'>操作</th>
	 </tr>

	 <tr>
		<?php for($i=0;$i<count($grade);$i++ ) {?>
			<th>人数</th>
			<th>%</th>
		<?php } ?>
	 </tr>
	 <!--表头结束-->
	 
	 <!--总数统计 -->
	 
	 <tr >
	    <td align="center"></td>
		<td align="center"></td>
		<td align="center"><?php echo $sum;?></td>
		<td align="center"><?php echo $sum0;?></td>
		<td align="center"><?php echo $p0;?></td>
		<td align="center"><?php echo $sum1;?></td>
		<td align="center"><?php echo $p1;?></td>
		<td align="center"><?php echo $sum2;?></td>
		<td align="center"><?php echo $p2;?></td>
		<td align="center"><?php echo $sum3;?></td>
		<td align="center"><?php echo $p3;?></td>
		<td align="center"><?php echo $sum4;?></td>
		<td align="center"><?php echo $p4;?></td>
		<td align="center"><?php echo $sum5;?></td>
		<td align="center"><?php echo $p5;?></td>
		<td align="center"><?php echo $sum6;?></td>
		<td align="center"><?php echo $p6;?></td>
		<td align="center"><?php echo $sum7;?></td>
		<td align="center"><?php echo $p7;?></td>
		<td align="center"><?php echo $sum8;?></td>
		<td align="center"><?php echo $p8;?></td>
		<td align="center"><?php echo $sum9;?></td>
		<td align="center"><?php echo $p9;?></td>
		<td align="center"><?php echo $sum10;?></td>
		<td align="center"><?php echo $p10;?></td>

	 
	 <tr>
	 
	 <tr>
	   <td colspan="26" align="center"></td>
	 </tr>
	 
	 <?php foreach((array)$effectGradeArr as $key=>$value) {?>
	 <tr>
		<td align="center"><?php echo $value['sid'];?></td>
		<td align="center"><?php echo $value['username'];?></td>
		<td align="center" <?php if($value['sumGrade']>=130) { ?>style="background:red"<?php } ?>><?php echo $value['sumGrade'];?></td>
		
        <!--  红色标注的数字：1、总数超过130 变为红色 2、0类 不管多少 都是红色 3、1类：10%-25% 低于或者超过这个范围 标注红色 。4、2类10%-15%  5、3类10%-15%  6、4类 2%-7%  7、5类 2%-7% 8、6类 大于2% 标注红色 9、7 8类 大于20% 标注红色
         1 2 3 4 5 这些类别 给你的是正常比例范围 低于或者超过 这个范围比例的 标注红色
        -->
	
		<?php if(!empty($value['effectGrade'])) { ?>
		    
			<?php foreach((array)$value['effectGrade'] as $k=>$v) {?>
			  <?php if($value['sumGrade']==0) { ?> 0<?php } else { ?> <?php $percent=(round($v['cGrade']/$value['sumGrade'],3))*100;?><?php } ?>
			  <td align="center" <?php if(($v["effect_grade"]-1==0 && $v["cGrade"]>0) || ($v["effect_grade"]-1==1 && ($percent<10 || $percent>25)) || (($v["effect_grade"]-1==2 || $v["effect_grade"]-1==3)  && ($percent<10 || $percent>15)) || (($v["effect_grade"]-1==4 || $v["effect_grade"]-1==5)  && ($percent<2 || $percent>7)) || ($v["effect_grade"]-1==6 &&  $percent>2) || (($v["effect_grade"]-1==7 || $v["effect_grade"]-1==8) &&  $percent>20)) { ?> style="background:red"<?php } ?> ><a href="#" onclick="parent.addTab('查看客服<?php echo $value["sid"];?>的<?php echo $v["effect_grade"]-1;?>类会员信息','index.php?action=allmember&h=class&choose=sid&keyword=<?php echo $value["sid"];?>&effect_grade=<?php echo $v["effect_grade"];?>&end=<?php echo $alloctimeEnd;?>&clear=1')" ><?php echo $v["cGrade"];?></a></td>
			  <td align="center" ><?php echo $percent;?></td>
		
			
			<?php }?>
		<?php } ?>
	     <td align="center" id="effectGrade_<?php echo $value['sid'];?>"><?php if(!empty($value['effectgrade_id'])) { ?> 是<?php } else { ?>否<?php } ?></td>
		<td><a href="#"  onclick="parent.addTab('查看客服的评语','index.php?action=myuser&h=remark&endTime=<?php echo $endTime;?>&sid=<?php echo $value["sid"];?>&groupid=<?php echo $groupid;?>');">查看</a><?php if(in_array($GLOBALS['groupid'],$GLOBALS['admin_all_group'] ) ) { ?>，<a href="#"  id="writeRemark_<?php echo $value['sid'];?>" onclick="writeRemark(<?php echo $value['sid'];?>);">评论</a><?php } ?></td>

	 </tr>
	 <?php }?>
	 
	 <tr>
	 
	 
	 </tr>
	 <!-- <tr>
		<td colspan="26" align="center"><?php echo $pages;?>
			&nbsp;&nbsp;&nbsp;
			转到第
			<input name="pageGo"  id="pageGo" type="text" style="width:20px;height:15px;" value="" /> 页 &nbsp;
			<input type="button"  class="ser_go" value="跳转" onclick="gotoPage()"/></td>
		</td>
	 </tr> -->
	 </table>
	 
	 <!-- <p> 三类会员明细： </p>
	 <table width="100%" cellspacing='1' cellpadding='1' id='list-table' class ="csstab" style="table-layout:fixed;">
	   
	   <tr>
	      <th width="5%">客服</td>
	      <?php foreach((array)$effectGradeArr as $key=>$value) {?>
		     <th align="center" colspan="2" ><?php echo $value['sid'];?></td>
		  <?php }?>
	   </tr>
	   
	   <tr >
	      <th width="5%"  rowspan=2>人数</td>
		  
	      <?php foreach((array)$effectGradeArr as $key=>$value) {?>
		     <td align="center">以前</td><td align="center">当天</td>
		  <?php }?>
	   </tr>
	   <tr>
	      <?php foreach((array)$effectGradeArr as $key=>$value) {?>
		     <td align="center"><?php echo $value['effectGrade'][3]['cGrade'] - $value['effectGrade3'];?></td><td align="center"><?php echo $value['effectGrade3'];?></td>
		  <?php }?>
	   </tr>
	 </table> -->
	 
</div>
备注：客服是否盘库 按照当天的日期 统计！
</div>

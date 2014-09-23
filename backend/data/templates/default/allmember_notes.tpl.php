<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="templates/js/mouse_tz.js"></script>
<style type="text/css">
body{font-size:12px;}
#notes{
	border:1px solid #1C3F80;
	background:#CEDAEA;
	position:absolute;
	left:0px;
	top:0px;
	width:800px;
}

html {
    overflow: -moz-scrollbars-vertical;
}
li{list-style-type: none;padding-top:3px;padding-bottom:3px;}

ul{margin:0px;}
ul.t1 {background-color:#e6f0fd;}/* 第一行背景色 */
ul.t2{background-color:#cedaea;}/* 第二行背景色 */
ul.t3 {background-color:skyblue;}/* 鼠标经过时的背景色 */
</style>

</head>
<body>
<div id="notes">
    <?php if(count($notes)>0) { ?>
		<?php foreach((array)$notes as $k=>$v) {?>
			<ul>
				<li><strong>【<?php echo ($page-1)*$limit+$k+1;?>】</strong> <?php echo date("Y-m-d H:i:s",$v['dateline']);?>&nbsp;&nbsp;&nbsp;<?php echo $v['mid'];?>号客服<?php echo $v['manager'];?>&nbsp;&nbsp;&nbsp;<?php echo $v['effect_grade']-1;?>类</li>
				<li><?php if($v['effect_contact'] != 2) { ?><?php if($v['effect_contact']) { ?>有效联系<?php } else { ?>无效联系<?php } ?>&nbsp;&nbsp;&nbsp;<?php if($v['master_member']) { ?>重点会员<?php } ?><?php } ?></li>
				<li><?php if(isset($v['effect_grade']) && isset($grade[$v['effect_grade']])) echo $grade[$v['effect_grade']];?>&nbsp;&nbsp;&nbsp;
				<?php $time = time();?>
				<?php if($v['next_contact_time'] > $time ) { ?>
				-&gt; &nbsp;下次联系时间：<?php echo date("Y-m-d H:i:s",$v['next_contact_time']);?>
				<?php } ?>
				<?php if($v['interest']) { ?>&nbsp;&nbsp;&nbsp;-> &nbsp;兴趣点：<?php echo $v['interest'];?><?php } ?><?php if($v['different']) { ?>&nbsp;&nbsp;&nbsp;-> &nbsp;异议点：<?php echo $v['different'];?><?php } ?><?php if($v['service_intro']) { ?>&nbsp;&nbsp;&nbsp;-> &nbsp;服务介绍：<?php echo $v['service_intro'];?><?php } ?><?php if($v['next_contact_desc']) { ?>&nbsp;&nbsp;&nbsp;-> &nbsp;下次跟进要点：<?php echo $v['next_contact_desc'];?><?php } ?><?php if($v['comment']) { ?>&nbsp;&nbsp;&nbsp;-> &nbsp;备注：<?php echo $v['comment'];?><?php } ?></li>
			</ul> 
		<?php }?>	 
		<div  id="pages" style=" text-align:center;font-size:23px;"><?php echo $pages;?></div>
	<?php } else { ?>
	    无记录
	<?php } ?>
</div>	
    <script type="text/javascript">
        var Ptr=document.getElementById("notes").getElementsByTagName("ul");
        function $() {
              for (i=1;i<Ptr.length+1;i++) { 
              Ptr[i-1].className = (i%2>0)?"t1":"t2"; 
              }
        }
        window.onload=$;
        for(var i=0;i<Ptr.length;i++) {
              Ptr[i].onmouseover=function(){
              this.tmpClass=this.className;
              this.className = "t3";    
              };
              Ptr[i].onmouseout=function(){
              this.className=this.tmpClass;
              };
        }
    </script>
</body>
</html>
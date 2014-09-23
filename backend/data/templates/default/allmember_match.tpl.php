<style type="text/css">
.over{
	background-color:#C4F0FF;
}
</style>
<script type="text/javascript" src="templates/js/onmousemove_minutes.js"></script>
<script type="text/javascript">
//显示用户信息
function userdetail(number,arrayobj,a,b) {
	var arrname = arrayobj;
		for(i=0;i<arrayobj.length;i++) {
			var valueArray  = arrayobj[i].split(",");
			if(valueArray[0] == number) {
				if(valueArray[0] == '0' && valueArray[1] != '男士') {
					$("#"+a+"_"+b).html("不限");
				} else {
					$("#"+a+"_"+b).html(valueArray[1]);
				}	
			}
	}
}

function mgopage(p){

	var condition = '&age=<?php echo $check_age;?>&weight=<?php echo $check_weight;?>&height=<?php echo $check_height;?>&haphoto=<?php echo $check_haphoto;?>&workprovince=<?php echo $check_workprovince;?>&marriage=<?php echo $check_marriage;?>&education=<?php echo $check_education;?>&smoking=<?php echo $check_smoking;?>&salary=<?php echo $check_salary;?>&children=<?php echo $check_children;?>&bodys=<?php echo $check_bodys;?>&occupation=<?php echo $check_occupation;?>&hometownprovince=<?php echo $check_hometownprovince;?>&nation=<?php echo $check_nation;?>&drinking=<?php echo $check_drinking;?>';

	var url = './allmember_ajax.php?n=match&uid=<?php echo $memberid;?>&page='+p;
	<?php if(isset($mark)) { ?>
	var mark = '<?php echo $mark;?>';
	<?php } else { ?>
	var mark = '';
	<?php } ?>
	if(mark == 1){
		url = './allmember_ajax.php?n=match_search&uid=<?php echo $memberid;?>&page='+p+condition;
	}
	$.get(url,function(data){
		$("#match_showarea").html(data);
	});	
}

function loadImg(uid){
	var url = './allmember_ajax.php?n=getimg&uid='+uid;
	$.get(url,function(data){//alert(data)
		$("#imgshow").html("<img src='"+data+"' />");
		$("#imgshow").css("display","block");
	})
}

function hideImg(){
	$("#imgshow").css("display","none");
}


//按照条件搜索
function match_search(){
	var  url = './allmember_ajax.php?n=match_search&uid=<?php echo $memberid;?>';
	var age = $('#check_age:checked').val() ? $('#check_age:checked').val() : '';
	var weight = $('#check_weight:checked').val() ? $('#check_weight:checked').val() : '';
	var height = $('#check_height:checked').val() ? $('#check_height:checked').val() : '';
	var bodys = $('#check_body:checked').val() ? $('#check_body:checked').val() : '';
	var haphoto = $('#check_hasphoto:checked').val() ? $('#check_hasphoto:checked').val() : '';
	var workprovince = $('#check_workprovince:checked').val() ? $('#check_workprovince:checked').val() : '';
	var marriage = $('#check_marriage:checked').val() ? $('#check_marriage:checked').val() : '';
	var education = $('#check_education:checked').val() ? $('#check_education:checked').val() : '';
	var salary = $('#check_salary:checked').val() ? $('#check_salary:checked').val() : '';
	var children = $('#check_children:checked').val() ? $('#check_children:checked').val() : '';
	var occupation = $('#check_occupation:checked').val() ? $('#check_occupation:checked').val() : '';
	var nation = $('#check_nation:checked').val() ? $('#check_nation:checked').val() : '';
	var smoking = $('#check_smoking:checked').val() ? $('#check_smoking:checked').val() : '';
	var drinking = $('#check_drinking:checked').val() ? $('#check_drinking:checked').val() : '';
	var hometownprovince = $('#check_hometownprovince:checked').val() ? $('#check_hometownprovince:checked').val() : '';
	$("#match_showarea").html("<div style='text-align:center'><br/>页面加载中...</div>");
	$.get(url,{age:age,weight:weight,height:height,bodys:bodys,haphoto:haphoto,workprovince:workprovince,marriage:marriage,education:education,salary:salary,children:children,occupation:occupation,nation:nation,smoking:smoking,drinking:drinking,hometownprovince:hometownprovince},function(data){
		$("#match_showarea").html(data);			 
	})
}
</script>
<div id="match_showarea">
<br/>
<div style="border: 1px solid #1C3F80; margin:0 auto; width:100%;">
<table width="100%" cellpadding="3" cellspacing="1" class="color_table">
<tr>  
    <th>请选择：
    年龄<input type="checkbox" name="check_age" id="check_age" value="age" <?php if($check_age) { ?>checked<?php } ?>>
    体重<input type="checkbox" name="check_weight" id="check_weight" value="weight" <?php if($check_weight) { ?>checked<?php } ?>>
    身高<input type="checkbox" name="check_height" id="check_height" value="height" <?php if($check_height) { ?>checked<?php } ?>>
    体形<input type="checkbox" name="check_body" id="check_body" value="body" <?php if($check_bodys) { ?>checked<?php } ?>>
    是否有照片<input type="checkbox" name="check_hasphoto" id="check_hasphoto" value="hasphoto" <?php if($check_haphoto) { ?>checked<?php } ?>>
    工作地区<input type="checkbox" name="check_workprovince" id="check_workprovince" value="workprovince" <?php if($check_workprovince) { ?>checked<?php } ?>> 
    征友地区<input type="checkbox" name="check_hometownprovince" id="check_hometownprovince" value="hometownprovince" <?php if($check_hometownprovince) { ?>checked<?php } ?>>   
    婚姻状况<input type="checkbox" name="check_marriage" id="check_marriage" value="marriage" <?php if($check_marriage) { ?>checked<?php } ?>>
    教育程度<input type="checkbox" name="check_education" id="check_education" value="education" <?php if($check_education) { ?>checked<?php } ?>>   
    月收入<input type="checkbox" name="check_salary" id="check_salary" value="salary" <?php if($check_salary) { ?>checked<?php } ?>>
    有无孩子<input type="checkbox" name="check_children" id="check_children" value="children" <?php if($check_children) { ?>checked<?php } ?>>
    职业<input type="checkbox" name="check_occupation" id="check_occupation" value="occupation" <?php if($check_occupation) { ?>checked<?php } ?>>
    民族<input type="checkbox" name="check_nation" id="check_nation" value="nation" <?php if($check_nation) { ?>checked<?php } ?>>
    是否抽烟<input type="checkbox" name="check_smoking" id="check_smoking" value="smoking" <?php if($check_smoking) { ?>checked<?php } ?>>
    是否喝酒<input type="checkbox" name="check_drinking" id="check_drinking" value="drinking" <?php if($check_drinking) { ?>checked<?php } ?>>
    
    <a href="#" style="display:block;text-decoration:none;border:1px solid #3E679A;width:100px;height:20px;background:#FFF;float:right;cursor:pointer" onclick="javascript:match_search();return false;">搜索</a>
    </th>
</tr>
</table>
<table width="100%" cellpadding="3" cellspacing="1" class="color_table" style="background-color:#FFF">
	
    <tr>
		<th>ID</th>
		<th>形象照</th>
		<th>昵称</th>
		<th>年龄</th>
		<th>身高</th>
		<th>学历</th>
		<th>婚姻状况</th>
		<th>月收入</th>
		<th>工作地点</th>
	</tr>
	<?php foreach((array)$users as $v) {?>
	<tr>
		<td align="center"><a href="#" onclick="parent.addTab('<?php echo $v['uid'];?>资料','index.php?action=allmember&h=view_info&uid=<?php echo $v['uid'];?>','icon')"><?php echo $v['uid'];?></a></td>
		<td align="center"><?php $img=MooGetphotoAdmin($v['uid'],'small');?><?php if($img) { ?><img src="<?php echo $img;?>" onmouseover="loadImg(<?php echo $v['uid'];?>)" onmouseout="hideImg()"/><?php } else { ?>无<?php } ?></td>
		<td align="center"><?php if($v['gender']==1) { ?><img src="templates/images/w.gif" alt="女" title="女"/>
			<?php } else { ?><img src="templates/images/m.gif" alt="男" title="男"/>
			<?php } ?><?php echo $v['nickname'];?></td>
		<td align="center"><?php echo date("Y")-$v['birthyear'];?></td>
		<td align="center" id="height_<?php echo $v['uid'];?>"><script>userdetail("<?php echo $v['height'];?>",height,'height',"<?php echo $v['uid'];?>");</script></td>
		<td align="center" id="education_<?php echo $v['uid'];?>"><script>userdetail("<?php echo $v['education'];?>",education,'education',"<?php echo $v['uid'];?>");</script></td>
		<td align="center"><?php if($v['marriage']) { ?>未婚<?php } else { ?>离异<?php } ?></td>
		<td align="center" id="salary1_<?php echo $v['uid'];?>"><script>userdetail("<?php echo $v['salary'];?>",salary1,'salary1',"<?php echo $v['uid'];?>");</script></td>
		<td align="center"><span id="province_<?php echo $v['uid'];?>"><script>userdetail("<?php echo $v['province'];?>",provice,'province',"<?php echo $v['uid'];?>");</script></span>&nbsp;&nbsp;<span id="city_<?php echo $v['uid'];?>"><script>userdetail("<?php echo $v['city'];?>",city,'city',"<?php echo $v['uid'];?>");</script></span></td>
	</tr>
	<?php } ?>
</table>
</div>
<br/>
<?php if($pages>=2) { ?><div style="text-align:center">第&nbsp;

<?php for($i=1;$i<=$pages;$i++){?>
	<a href="javascript:mgopage(<?php echo $i;?>);"style="text-decoration:none;<?php if($page==$i) { ?>border:1px solid #3E679A; background:#7F99BE;<?php } ?>">&nbsp;<?php echo $i;?>&nbsp;</a>&nbsp;
<?php } ?>

&nbsp;页</div><?php } ?>
<div id="imgshow" style="position:relative;top:-400px; left:-150px;width:200px;height:250px; display:none;"></div>
</div>

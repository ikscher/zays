<?php if(!isset($gender)) { ?>
<?php $gender='';?>
<?php } ?>
<?php if(!isset($photo)) { ?>
<?php $photo='';?>
<?php } ?>
<?php if(!isset($age_start)) { ?>
<?php $age_start=0;?>
<?php } ?>
<?php if(!isset($age_end)) { ?>
<?php $age_end=0;?>
<?php } ?>
<?php if(isset($marriage) && is_array($marriage) && !empty($marriage)) { ?>
<?php $marriage=implode(',',$marriage);?>
<?php } else { ?>
<?php $marriage = 0;?>
<?php } ?>
<?php if(isset($salary) && is_array($salary) && !empty($salary)) { ?>
<?php $salary=implode(',',$salary);?>
<?php } else { ?>
<?php $salary = 0;?>
<?php } ?>
<?php if(isset($education) && is_array($education) && !empty($education)) { ?>
<?php $education=implode(',',$education);?>
<?php } else { ?>
<?php $education = 0;?>
<?php } ?>
<?php if(!isset($province)) { ?>
<?php $province=0;?>
<?php } ?>
<?php if(!isset($city)) { ?>
<?php $city=0;?>
<?php } ?>
<?php if(!isset($work_province)) { ?>
<?php $work_province=0;?>
<?php } ?>
<?php if(!isset($work_city)) { ?>
<?php $work_city=0;?>
<?php } ?>
<?php if(!isset($height1)) { ?>
<?php $height1=0;?>
<?php } ?>
<?php if(!isset($height2)) { ?>
<?php $height2=0;?>
<?php } ?>
<?php if(!isset($weight1)) { ?>
<?php $weight1=0;?>
<?php } ?>
<?php if(!isset($weight2)) { ?>
<?php $weight2=0;?>
<?php } ?>
<?php if(!isset($home_townprovince)) { ?>
<?php $home_townprovince=0;?>
<?php } ?>
<?php if(!isset($home_towncity)) { ?>
<?php $home_towncity=0;?>
<?php } ?>
<?php if(!isset($salary)) { ?>
<?php $salary=0;?>
<?php } ?>
<?php if(!isset($house)) { ?>
<?php $house=0;?>
<?php } ?>
<?php if(!isset($vehicle)) { ?>
<?php $vehicle=0;?>
<?php } ?>
<?php if(!isset($marriageArr)) { ?>
<?php $marriageArr=array();?>
<?php } ?>
<?php if(!isset($salaryArr)) { ?>
<?php $salaryArr = array();?>
<?php } ?>
<?php if(!isset($educationArr)) { ?>
<?php $educationArr=array();?>
<?php } ?>
<?php if(!isset($houseArr)) { ?>
<?php $houseArr = array();?>
<?php } ?>
<?php if(!isset($vehicleArr)) { ?>
<?php $vehicleArr=array();?>
<?php } ?>
<?php if(!isset($smoking)) { ?>
<?php $smoking=0;?>
<?php } ?>
<?php if(!isset($drinking)) { ?>
<?php $drinking=0;?>
<?php } ?>
<?php if(!isset($occupation)) { ?>
<?php $occupation=0;?>
<?php } ?>
<?php if(!isset($corptype)) { ?>
<?php $corptype=0;?>
<?php } ?>
<?php if(!isset($children)) { ?>
<?php $children=0;?>
<?php } ?>
<?php if(!isset($wantchildren)) { ?>
<?php $wantchildren=0;?>
<?php } ?>
<?php if(!isset($nation)) { ?>
<?php $nation=0;?>
<?php } ?>
<?php if(!isset($animalyear)) { ?>
<?php $animalyear=0;?>
<?php } ?>
<?php if(!isset($constellation)) { ?>
<?php $constellation=0;?>
<?php } ?>
<?php if(!isset($bloodtype)) { ?>
<?php $bloodtype=0;?>
<?php } ?>
<?php if(!isset($religion)) { ?>
<?php $religion=0;?>
<?php } ?>
<?php if(!isset($family)) { ?>
<?php $family=0;?>
<?php } ?>
<?php if(!isset($language)) { ?>
<?php $language=0;?>
<?php } ?>
<?php if(!isset($body)) { ?>
<?php $body=0;?>
<?php } ?>
<script type="text/javascript">
<!--
//检测ID是否能搜索
function checkid(){
	var id = $("#searchid").val();
	if(!id){
		alert("请输入用户昵称或ID号！");
		return false;
	}else{
		return true;
	}
}
//-->
</script>
<script type="text/javascript">
var changyong = function(syscodeapi,itemname) {
	var salaryId = new Array();
	var salaryViewStr = new Array();
	for(var i=0;i<syscodeapi.length;i++) {
	temp = syscodeapi[i].split(",");
	salaryId[i]=temp[0];
	salaryViewStr[i]=temp[1];
	}
	for(var j=0;j<salaryId.length;j++) {
		document.write('<a href="index.php?n=search&h=add_del_search&gender=<?php echo $gender;?>&age_start=<?php echo $age_start;?>&age_end=<?php echo $age_end;?>&workprovince=<?php echo $work_province;?>&workcity=<?php echo $work_city;?>&Marriage=<?php echo $marriage;?>&Salary=<?php echo $salary;?>&Education=<?php echo $education;?>&height1=<?php echo $height1;?>&height2=<?php echo $height2;?>&weight1=<?php echo $weight1;?>&weight2=<?php echo $weight2;?>&Body=<?php echo $body;?>&ismok=<?php echo $smoking;?>&idrink=<?php echo $drinking;?>&Occupation=<?php echo $occupation;?>&House=<?php echo $house;?>&Vehicle=<?php echo $vehicle;?>&Corptp=<?php echo $corptype;?>&Child=<?php echo $children;?>&Wtchildren=<?php echo $wantchildren;?>&workcityprovince1=<?php echo $home_townprovince;?>&workcitycity1=<?php echo $home_towncity;?>&Stock=<?php echo $nation;?>&Animals=<?php echo $animalyear;?>&Constellation=<?php echo $constellation;?>&Bty=<?php echo $bloodtype;?>&Belief=<?php echo $religion;?>&Family=<?php echo $family;?>&Tonguegift=<?php echo $language;?>&photo=<?php echo $photo;?>&condition=<?php echo $condition;?>&sortway=<?php echo $sortway;?>&'+itemname+'='+salaryId[j]+'">'+salaryViewStr[j]+'</a>');
	}
}
function clearConditions(){
$("#alldelete").css({"display":"none"});
}

//清空搜索条件
var delAllItem = function(){
var index =0;
var s = "gender=<?php echo $gender;?>&age_begin=<?php echo $age_start;?>&ageend=<?php echo $age_end;?>&workprovince=<?php echo $work_province;?>&work_city=<?php echo $work_city;?>&photo=<?php echo $photo;?>&condition=<?php echo $condition;?>&sortway=<?php echo $sortway;?>";
s=s.substring(0,s.length-1);
var t = new Array();
var itemName = new Array();
var itemValue = new Array();
var delItem = new Array();
var t = s.split("&");
var newsc = "";
for(var i=0;i<t.length;i++) {
temp = t[i].split("=");
itemName[i]=temp[0];
itemValue[i]=temp[1];
}
if($('#marriage_extends').size()>0){delItem[index++]="Marriage";}
if($('#salary_extends').size()>0){delItem[index++]="Salary";}
if($('#education_extends').size()>0){delItem[index++]="Education";}
if($('#height_extends').size()>0){delItem[index++]="h1";delItem[index++]="h2";	}
if($('#house_extends').size()>0){delItem[index++]="house";	}
if($('#vehicle_extends').size()>0){delItem[index++]="vehicle";}
if($('#hometown_province_Id').size()>0){delItem[index++]="HomeTown";}
if($('#ismoking_extends').size()>0){delItem[index++]="ismok";	}
if($('#isdrinking_extends').size()>0){delItem[index++]="idrink";}
if($('#children_extends').size()>0){delItem[index++]="child";}
if($('#wantchildren_extends').size()>0){delItem[index++]="wtchildren";}
if($('#weight_extends').size()>0){delItem[index++]="w1";delItem[index++]="w2";	}
if($('#body_extends').size()>0){delItem[index++]="Body";}
if($('#weight_extends').size()>0){delItem[index++]="w1";delItem[index++]="w2";	}
if($('#stock_extends').size()>0){delItem[index++]="Stock";}
if($('#animals_extends').size()>0){delItem[index++]="Animals";}
if($('#constellation_extends').size()>0){delItem[index++]="Constellation";	}
if($('#bloodtype_extends').size()>0){delItem[index++]="bty";}
if($('#occupation_extends').size()>0){delItem[index++]="Occupation";}
if($('#corptp_extends').size()>0){delItem[index++]="corptp";}
if($('#belief_extends').size()>0){delItem[index++]="Belief";}
if($('#tonguegift_extends').size()>0){delItem[index++]="TongueGift";}
if($('#family_extends').size()>0){delItem[index++]="family";}
for(var j=0;j<itemName.length;j++){
var m = -1;
for(var k=0;k<delItem.length;k++){
if(itemName[j]==delItem[k]){
m = j;
break;
}
}
if(m>=0) {
itemName[j] = '';
}
}
for(var x=0;x<itemName.length;x++){
if(itemName[x]!=''){
newsc+=itemName[x]+"="+itemValue[x]+"&";
}
}
newsc = newsc.substring(0,newsc.length-1);
//alert(newsc);
//	location.href='index.php?n=search&h=quick&'+newsc;
}
/******************************************************************************/
</script>
<script type="text/javascript">
<!--
var delCurrentItem = function(fieldName,value) {
var s = "gender=<?php echo $gender;?>&age_start=<?php echo $age_start;?>&age_end=<?php echo $age_end;?>&workprovince=<?php echo $work_province;?>&workcity=<?php echo $work_city;?>&Marriage=<?php echo $marriage;?>&Salary=<?php echo $salary;?>&Education=<?php echo $education;?>&height1=<?php echo $height1;?>&height2=<?php echo $height2;?>&weight1=<?php echo $weight1;?>&weight2=<?php echo $weight2;?>&Body=<?php echo $body;?>&ismok=<?php echo $smoking;?>&idrink=<?php echo $drinking;?>&Occupation=<?php echo $occupation;?>&House=<?php echo $house;?>&Vehicle=<?php echo $vehicle;?>&Corptp=<?php echo $corptype;?>&Child=<?php echo $children;?>&Wtchildren=<?php echo $wantchildren;?>&workcityprovince1=<?php echo $home_townprovince;?>&workcitycity1=<?php echo $home_towncity;?>&Stock=<?php echo $nation;?>&Animals=<?php echo $animalyear;?>&Constellation=<?php echo $constellation;?>&Bty=<?php echo $bloodtype;?>&Belief=<?php echo $religion;?>&Family=<?php echo $family;?>&Tonguegift=<?php echo $language;?>&photo=<?php echo $photo;?>&condition=<?php echo $condition;?>&sortway=<?php echo $sortway;?>";
var t = new Array();
var itemName = new Array();
var itemValue = new Array();
var t = s.split("&");
var newsc = "";
for(var i=0;i<t.length;i++) {
temp = t[i].split("=");
itemName[i]=temp[0];
itemValue[i]=temp[1];
}
for(var j=0;j<itemName.length;j++){
if(fieldName==itemName[j]) {
//去掉对应的value，要分为多选和单选的情况
if(itemValue[j].indexOf(",")>0) {
//多选 1,2,3,4
if(value.indexOf(",")>0) {
//一次全部去掉
for(var k=0;k<t.length;k++) {
if(k!=j) {
	newsc = newsc + t[k]+"&";
}
}
newsc = newsc.substring(0,newsc.length-1);
} else {
//一个个去掉
var temp = t[j];
var curItemValue = new Array();
var temp3 = new Array();
curItemValue = temp.split("=");
var temp2 = curItemValue[1];
temp3 = temp2.split(",");
var temp4 = "";
var temp5 = "";
for(var x=0;x<temp3.length;x++) {
if(temp3[x]!=value) {
	temp4=temp4+temp3[x]+",";
}
}
temp4 = temp4.substring(0,temp4.length-1);
for(var y=0;y<=j;y++){
temp5 = temp5+t[y]+"&";
}
temp5 = temp5 + curItemValue[0] + "=" + temp4 + "&";
for(var z=j+1;z<t.length;z++){
temp5 = temp5 + t[z] + "&";
}
newsc = temp5;
}
} else {
//单选
for(var k=0;k<t.length;k++) {
if(k!=j) {
newsc = newsc + t[k]+"&";
}
}
newsc = newsc.substring(0,newsc.length-1);
}
break;
}
}
//		  alert(newsc);
location.href = "index.php?n=search&h=add_del_search&" + newsc;
}
//-->
</script>
<div class="centent-right">
<div class="centent-right-side"></div>
<div class="centent-center">
<?php if(isset($searchme) && $searchme == 'yes') { ?>
<p class="h-seache-title" style="color: rgb(215, 60, 144);">哪些人在搜索我</p>
<?php } else { ?>
<p class="h-seache-title" style="color: rgb(215, 60, 144);">您选择的搜索条件：</p>
<?php } ?>
<!----------==============-->
<div class="search-criteria-m2">
<script type="text/javascript">
<!--
var delHomeTown = function(){
	var newsc = "gender=<?php echo $gender;?>&age_start=<?php echo $age_start;?>&age_end=<?php echo $age_end;?>&workprovince=<?php echo $work_province;?>&workcity=<?php echo $work_city;?>&Marriage=<?php echo $marriage;?>&Salary=<?php echo $salary;?>&Education=<?php echo $education;?>&height1=<?php echo $height1;?>&height2=<?php echo $height2;?>&weight1=<?php echo $weight1;?>&weight2=<?php echo $weight2;?>&Body=<?php echo $body;?>&ismok=<?php echo $smoking;?>&idrink=<?php echo $drinking;?>&Occupation=<?php echo $occupation;?>&House=<?php echo $house;?>&Vehicle=<?php echo $vehicle;?>&Corptp=<?php echo $corptype;?>&Child=<?php echo $children;?>&Wtchildren=<?php echo $wantchildren;?>&workcityprovince1=0&workcitycity1=0&Stock=<?php echo $nation;?>&Animals=<?php echo $animalyear;?>&Constellation=<?php echo $constellation;?>&Bty=<?php echo $bloodtype;?>&Belief=<?php echo $religion;?>&Family=<?php echo $family;?>&Tonguegift=<?php echo $language;?>&photo=<?php echo $photo;?>&condition=<?php echo $condition;?>&sortway=<?php echo $sortway;?>";
	location.href = "index.php?n=search&h=add_del_search&"+newsc;
}

var delHeight = function() {
	var newsc = "gender=<?php echo $gender;?>&age_start=<?php echo $age_start;?>&age_end=<?php echo $age_end;?>&workprovince=<?php echo $work_province;?>&workcity=<?php echo $work_city;?>&Marriage=<?php echo $marriage;?>&Salary=<?php echo $salary;?>&Education=<?php echo $education;?>&height1=0&height2=0&weight1=<?php echo $weight1;?>&weight2=<?php echo $weight2;?>&Body=<?php echo $body;?>&ismok=<?php echo $smoking;?>&idrink=<?php echo $drinking;?>&Occupation=<?php echo $occupation;?>&House=<?php echo $house;?>&Vehicle=<?php echo $vehicle;?>&Corptp=<?php echo $corptype;?>&Child=<?php echo $children;?>&Wtchildren=<?php echo $wantchildren;?>&workcityprovince1=<?php echo $home_townprovince;?>&workcitycity1=<?php echo $home_towncity;?>&Stock=<?php echo $nation;?>&Animals=<?php echo $animalyear;?>&Constellation=<?php echo $constellation;?>&Bty=<?php echo $bloodtype;?>&Belief=<?php echo $religion;?>&Family=<?php echo $family;?>&Tonguegift=<?php echo $language;?>&photo=<?php echo $photo;?>&condition=<?php echo $condition;?>&sortway=<?php echo $sortway;?>";
	location.href = "index.php?n=search&h=add_del_search&" + newsc;
}

var delWeight = function() {
	var newsc = "gender=<?php echo $gender;?>&age_start=<?php echo $age_start;?>&age_end=<?php echo $age_end;?>&workprovince=<?php echo $work_province;?>&workcity=<?php echo $work_city;?>&Marriage=<?php echo $marriage;?>&Salary=<?php echo $salary;?>&Education=<?php echo $education;?>&height1=<?php echo $height1;?>&height2=<?php echo $height2;?>&weight1=0&weight2=0&Body=<?php echo $body;?>&ismok=<?php echo $smoking;?>&idrink=<?php echo $drinking;?>&Occupation=<?php echo $occupation;?>&House=<?php echo $house;?>&Vehicle=<?php echo $vehicle;?>&Corptp=<?php echo $corptype;?>&Child=<?php echo $children;?>&Wtchildren=<?php echo $wantchildren;?>&workcityprovince1=<?php echo $home_townprovince;?>&workcitycity1=<?php echo $home_towncity;?>&Stock=<?php echo $nation;?>&Animals=<?php echo $animalyear;?>&Constellation=<?php echo $constellation;?>&Bty=<?php echo $bloodtype;?>&Belief=<?php echo $religion;?>&Family=<?php echo $family;?>&Tonguegift=<?php echo $language;?>&photo=<?php echo $photo;?>&condition=<?php echo $condition;?>&sortway=<?php echo $sortway;?>";
	location.href = "index.php?n=search&h=add_del_search&" + newsc;
}

var delTonguegift = function(value){
	var newsc = "gender=<?php echo $gender;?>&age_start=<?php echo $age_start;?>&age_end=<?php echo $age_end;?>&workprovince=<?php echo $work_province;?>&workcity=<?php echo $work_city;?>&Marriage=<?php echo $marriage;?>&Salary=<?php echo $salary;?>&Education=<?php echo $education;?>&height1=<?php echo $height1;?>&height2=<?php echo $height2;?>&weight1=<?php echo $weight1;?>&weight2=<?php echo $weight2;?>&Body=<?php echo $body;?>&ismok=<?php echo $smoking;?>&idrink=<?php echo $drinking;?>&Occupation=<?php echo $occupation;?>&House=<?php echo $house;?>&Vehicle=<?php echo $vehicle;?>&Corptp=<?php echo $corptype;?>&Child=<?php echo $children;?>&Wtchildren=<?php echo $wantchildren;?>&workcityprovince1=<?php echo $home_townprovince;?>&workcitycity1=<?php echo $home_towncity;?>&Stock=<?php echo $nation;?>&Animals=<?php echo $animalyear;?>&Constellation=<?php echo $constellation;?>&Bty=<?php echo $bloodtype;?>&Belief=<?php echo $religion;?>&Family=<?php echo $family;?>&Tonguegift=0&photo=<?php echo $photo;?>&condition=<?php echo $condition;?>&sortway=<?php echo $sortway;?>";
	location.href = "index.php?n=search&h=add_del_search&" + newsc;
}
//-->
</script>
<script type="text/javascript">
<!--
var singleSelectedEdit = function(o) {
	$('.darrow').removeClass('darrow current').addClass('darrow');
	$('.datalist').css('display','none');
	$('#personal_'+o).removeClass('darrow').addClass('darrow current');
	$('#personal_view_'+o).css('display','');
}	
//-->
</script>
<ul class="scbox">
<li class="title">
<span class="tred">&nbsp;基本条件</span>
<a href="#" class="lgray underlines Bl_3"  onClick="addConditionopen();baseSelectedEdit();" style="margin-right:-10px;">编辑</a>
</li>
<li class="lh24">
<?php if(isset($gender) && $gender == '0') { ?>
男士
<?php } else { ?>
女士
<?php } ?>

<?php if($age_start != '0' && $age_end != '0') { ?>
<?php echo $age_start;?>-<?php echo $age_end;?>岁
<?php } elseif ($age_start != '0' && $age_end == '0') { ?>
<?php echo $age_start;?>岁以上
<?php } elseif ($age_start != '0' && $age_end == '0') { ?>
<?php echo $age_end;?>岁以下
<?php } else { ?>
不限
<?php } ?>

</li>
<li class="lh24">
<?php if($work_province == 0 && $work_city != 0) { ?>
<script type="text/javascript">
userdetail('0',provice);
userdetail('<?php echo $work_city;?>',city);
</script>
<?php } elseif ($work_province != 0 && $work_city !=0) { ?>
<script type="text/javascript">
userdetail('<?php echo $work_province;?>',provice);
userdetail('<?php echo $work_city;?>',city);
</script>
<?php } elseif ($work_province != 0 && $work_city ==0) { ?>
<script type="text/javascript">
userdetail('<?php echo $work_province;?>',provice);
userdetail('0',city);
</script>
<?php } else { ?>
工作城市不限
<?php } ?>
<br />
<?php if(isset($photo) && $photo == '1') { ?>
有照片
<?php } else { ?>
没有照片
<?php } ?>
</li>
</ul>
<!-- 其他条件 -->
<?php if(is_array($marriageArr) && !empty($marriageArr) && !in_array(0,$marriageArr)) { ?>
<ul class="scbox2">
<li class="title">
<span class="tred" id="marriage_extends">婚姻状况</span>
<a href="#" class="lgray underlines Bl_3"  onClick="baseSelectedEdit();addConditionopen();singleSelectedEdit('marriage');">编辑</a>
</li>
<li class="lh24">
<script>selectedFiled(syscode.marriage,",<?php echo $marriage;?>,","marriage8_","Marriage");</script>
</li>
</ul>
<?php } ?>
<?php if(isset($salaryArr) && is_array($salaryArr) && !empty($salaryArr) && !in_array(0,$salaryArr)) { ?>
<ul class="scbox2">
<li class="title">
<span class="tred" id="salary_extends">月收入</span>
<a href="#" class="lgray underlines Bl_3"  onClick="baseSelectedEdit();addConditionopen();singleSelectedEdit('salary');">编辑</a>
</li>
<li class="lh24">
<script>selectedFiled(syscode.salary,",<?php echo $salary;?>,","salary8_","Salary");</script>
</li>
</ul>
<?php } ?>

<?php if(isset($educationArr) && is_array($educationArr) && !empty($educationArr) && !in_array(0,$educationArr)) { ?>
<ul class="scbox2">
<li class="title">
<span class="tred" id="education_extends">教育程度</span>
<a href="#" class="lgray underlines Bl_3"  onClick="baseSelectedEdit();addConditionopen();singleSelectedEdit('education');">编辑</a>
</li>
<li class="lh24">
<script>selectedFiled(syscode.education,",<?php echo $education;?>,","education8_","Education");</script>
</li>
</ul>
<?php } ?>

<?php if(isset($houseArr) && is_array($houseArr) && !empty($houseArr) && !in_array(0,$houseArr)) { ?>
<ul class="scbox2">
<li class="title">
<span class="tred" id="house_extends">住房情况</span>
<a href="#" class="lgray underlines Bl_3"  onClick="baseSelectedEdit();addConditionopen();singleSelectedEdit('house');">编辑</a>
</li>
<li class="lh24">
<script>selectedFiled(syscode.house,",<?php echo $house;?>,","house8_","House");</script>
</li>
</ul>
<?php } ?>
<?php if($height1 || $height2) { ?>
<?php if($height1 != '0' || $height2 != '0') { ?>
<ul class="scbox2">
<li class="title">
<span class="tred" id="height_extends">身高</span>
<a href="#" class="lgray underlines Bl_3"  onClick="baseSelectedEdit();addConditionopen();singleSelectedEdit('height');">编辑</a>
</li>
<li class="label">
<a href="##" onclick="delHeight();">
<?php if($height1 != '0' && $height2 == '0') { ?>
<?php echo $height1;?>cm以上
<?php } elseif ($height1 == '0' && $height2 != '0') { ?>
<?php echo $height2;?>cm以下
<?php } elseif ($height1 == '0' && $height2 == '0') { ?>
不限
<?php } else { ?>
<?php echo $height1;?>cm 到 <?php echo $height2;?>cm
<?php } ?>
</a>
</li>
</ul>
<?php } ?>
<?php } ?>

<?php if(isset($vehicleArr) && is_array($vehicleArr) && !empty($vehicleArr) && !in_array(0,$vehicleArr)) { ?>
<ul class="scbox2">
<li class="title">
<span class="tred" id="vehicle_extends">是否购车</span>
<a href="#" class="lgray underlines Bl_3"  onClick="baseSelectedEdit();addConditionopen();singleSelectedEdit('vehicle');">编辑</a>
</li>
<li class="lh24">
<script>selectedFiled(syscode.vehicle,",<?php echo $vehicle;?>,","vehicle8_","Vehicle");</script>
</li>
</ul>
<?php } ?>

<?php if(isset($home_townprovince) && $home_townprovince || isset($home_towncity) && $home_towncity) { ?>
<ul class="scbox2">
<li class="title">
<span class="tred" id="hometown_province_Id">籍贯</span>
<a href="#" class="lgray underlines Bl_3"  onClick="baseSelectedEdit();addConditionopen();singleSelectedEdit('hometown');">编辑</a>
</li>
<li class="label">
<a href="##" onclick="delHomeTown();">
<?php if($home_townprovince != '0' && $home_towncity != '0') { ?>
<script type="text/javascript">
	userdetail('<?php echo $home_townprovince;?>',provice)
	userdetail('<?php echo $home_towncity;?>',city)
</script>
<?php } elseif ($home_townprovince != '0' && $home_towncity == '0') { ?>
<script type="text/javascript">
	userdetail('<?php echo $home_townprovince;?>',provice)
	userdetail('<?php echo $home_towncity;?>',city)
</script>
<?php } else { ?>
	不限
<?php } ?>
</a>
</li>
</ul>
<?php } ?>

<?php if(isset($weight1) && $weight1 || isset($weight2) && $weight2) { ?>
<?php if($weight1 != '0' || $weight2 != '0') { ?>
<ul class="scbox2">
<li class="title">
<span class="tred" id="hometown_province_Id">体重</span>
<a href="#" class="lgray underlines Bl_3"  onClick="baseSelectedEdit();addConditionopen();singleSelectedEdit('weight');">编辑</a>
</li>
<li class="label">
<a href="##" onclick="delWeight();">
<?php if($weight1 != '0' && $weight1 != '0' && $weight2 != '0' && $weight2 != '0') { ?>
<?php echo $weight1;?> 到 <?php echo $weight2;?> 公斤
<?php } elseif ($weight1 != '0' && ($weight2 == '0' || $weight2 == '0')) { ?>
<?php echo $weight1;?> 公斤以上
<?php } elseif (($weight1 == '0' || $weight1 == '0') && $weight2 != '0') { ?>
<?php echo $weight2;?> 公斤以下
<?php } else { ?>
不限					
<?php } ?>
</a>
</ul>
<?php } ?>
<?php } ?>

<?php if(isset($smokingArr) && is_array($smokingArr) && !empty($smokingArr) && !in_array(0,$smokingArr)) { ?>
<ul class="scbox2">
<li class="title">
<span class="tred" id="ismoking_extends">是否吸烟</span>
<a href="#" class="lgray underlines Bl_3"  onClick="baseSelectedEdit();addConditionopen();singleSelectedEdit('ismok');">编辑</a>
</li>
<li class="lh24">
<script>selectedFiled(syscode.isSmoking,",<?php echo $smoking;?>,","smoking8_","ismok");</script>
</li>
</ul>
<?php } ?>

<?php if(isset($drinkingArr) && is_array($drinkingArr) && !empty($drinkingArr) && !in_array(0,$drinkingArr)) { ?>
<ul class="scbox2">
<li class="title">
<span class="tred" id="isdrinking_extends">是否喝酒</span>
<a href="#" class="lgray underlines Bl_3"  onClick="baseSelectedEdit();addConditionopen();singleSelectedEdit('idrink');">编辑</a>
</li>
<li class="lh24">
<script>selectedFiled(syscode.isDrinking,",<?php echo $drinking;?>,","isdrinking8_","idrink");</script>
</li>
</ul>
<?php } ?>

<?php if(isset($occupationArr) && is_array($occupationArr) && !empty($occupationArr) && !in_array(0,$occupationArr)) { ?>
<ul class="scbox2">
<li class="title">
<span class="tred" id="occupation_extends">从事职业</span>
<a href="#" class="lgray underlines Bl_3"  onClick="baseSelectedEdit();addConditionopen();singleSelectedEdit('occupation');">编辑</a>
</li>
<li class="lh24">
<script>selectedFiled(syscode.occupationbt,",<?php echo $occupation;?>,","occupation8_","Occupation");</script>
</li>
</ul>
<?php } ?>

<?php if(isset($corptypeArr) && is_array($corptypeArr) && !empty($corptypeArr) && !in_array(0,$corptypeArr)) { ?>
<ul class="scbox2">
<li class="title">
<span class="tred" id="corptype_extends">公司类别</span>
<a href="#" class="lgray underlines Bl_3"  onClick="baseSelectedEdit();addConditionopen();singleSelectedEdit('corptp');">编辑</a>
</li>
<li class="lh24">
<script>selectedFiled(syscode.corptype,",<?php echo $corptype;?>,","corptp8_","Corptp");</script>
</li>
</ul>
<?php } ?>

<?php if(isset($childrenArr) && is_array($childrenArr) && !empty($childrenArr) && !in_array(0,$childrenArr)) { ?>
<ul class="scbox2">
<li class="title">
<span class="tred" id="children_extends">是否有孩子</span>
<a href="#" class="lgray underlines Bl_3"  onClick="baseSelectedEdit();addConditionopen();singleSelectedEdit('child');">编辑</a>
</li>
<li class="lh24">
<script>selectedFiled(syscode.children,",<?php echo $children;?>,","children8_","Child");</script>
</li>
</ul>
<?php } ?>

<?php if(isset($wantchildrenArr) && is_array($wantchildrenArr) && !empty($wantchildrenArr) && !in_array(0,$wantchildrenArr)) { ?>
<ul class="scbox2">
<li class="title">
<span class="tred" id="wantchildren_extends">是否想要孩子</span>
<a href="#" class="lgray underlines Bl_3"  onClick="baseSelectedEdit();addConditionopen();singleSelectedEdit('wtchildren');">编辑</a>
</li>
<li class="lh24">
<script>selectedFiled(syscode.wantChildren,",<?php echo $wantchildren;?>,","wantchildren8_","Wtchildren");</script>
</li>
</ul>
<?php } ?>

<?php if(isset($bodyArr) && is_array($bodyArr) && !empty($bodyArr) && !in_array(0,$bodyArr)) { ?>
<ul class="scbox2">
<li class="title">
<span class="tred" id="body_extends">体型</span>
<a href="#" class="lgray underlines Bl_3"  onClick="baseSelectedEdit();addConditionopen();singleSelectedEdit('body');">编辑</a>
</li>
<li class="lh24">
<script>selectedFiled(syscode.body1,",<?php echo $body;?>,","body8_","Body");</script>
</li>
</ul>
<?php } ?>

<?php if(isset($animalyearArr) && is_array($animalyearArr) && !empty($animalyearArr) && !in_array(0,$animalyearArr)) { ?>
<ul class="scbox2">
<li class="title">
<span class="tred" id="animals_extends">生肖</span>
<a href="#" class="lgray underlines Bl_3"  onClick="baseSelectedEdit();addConditionopen();singleSelectedEdit('animals');">编辑</a>
</li>
<li class="lh24">
<script>selectedFiled(syscode.animals,",<?php echo $animalyear;?>,","animals8_","Animals");</script>
</li>
</ul>
<?php } ?>

<?php if(isset($constellationArr) && is_array($constellationArr) && !empty($constellationArr) && !in_array(0,$constellationArr)) { ?>
<ul class="scbox2">
<li class="title">
<span class="tred" id="constellation_extends">星座</span>
<a href="#" class="lgray underlines Bl_3"  onClick="baseSelectedEdit();addConditionopen();singleSelectedEdit('constellation');">编辑</a>
</li>
<li class="lh24">
<script>selectedFiled(syscode.constellation,",<?php echo $constellation;?>,","constellation8_","Constellation");</script>
</li>
</ul>
<?php } ?>

<?php if(isset($bloodtypeArr) && is_array($bloodtypeArr) && !empty($bloodtypeArr) && !in_array(0,$bloodtypeArr)) { ?>
<ul class="scbox2">
<li class="title">
<span class="tred" id="bloodtype_extends">血型</span>
<a href="#" class="lgray underlines Bl_3"  onClick="baseSelectedEdit();addConditionopen();singleSelectedEdit('bty');">编辑</a>
</li>
<li class="lh24">
<script>selectedFiled(syscode.bloodtype,",<?php echo $bloodtype;?>,","constellation8_","Bty");</script>
</li>
</ul>
<?php } ?>

<?php if(isset($religionArr) && is_array($religionArr) && !empty($religionArr) && !in_array(0,$religionArr)) { ?>
<ul class="scbox2">
<li class="title">
<span class="tred" id="belief_extends">信仰</span>
<a href="#" class="lgray underlines Bl_3"  onClick="baseSelectedEdit();addConditionopen();singleSelectedEdit('belief');">编辑</a>
</li>
<li class="lh24">
<script>selectedFiled(syscode.belief,",<?php echo $religion;?>,","belief8_","Belief");</script>
</li>
</ul>
<?php } ?>

<?php if(isset($familyArr) && is_array($familyArr) && !empty($familyArr) && !in_array(0,$familyArr)) { ?>
<ul class="scbox2">
<li class="title">
<span class="tred" id="family_extends">兄弟姐妹</span>
<a href="#" class="lgray underlines Bl_3"  onClick="baseSelectedEdit();addConditionopen();singleSelectedEdit('family');">编辑</a>
</li>
<li class="lh24">
<script>selectedFiled(syscode.family,",<?php echo $family;?>,","family8_","Family");</script>
</li>
</ul>
<?php } ?>

<?php if(isset($languageArr) && is_array($languageArr) && !empty($languageArr) && !in_array(0,$languageArr)) { ?>
<ul class="scbox2">
<li class="title">
<span class="tred" id="tonguegift_extends">语言能力</span>
<a href="#" class="lgray underlines Bl_3"  onClick="baseSelectedEdit();addConditionopen();singleSelectedEdit('tonguegift');">编辑</a>
</li>
<li class="lh24">
<script>selectedFiled(syscode.tonguegifts,",<?php echo $language;?>,","tonguegift8_","TongueGift");</script>
</li>
</ul>
<?php } ?>

<?php if(isset($nationArr) && is_array($nationArr) && !empty($nationArr) && !in_array(0,$nationArr)) { ?>
<ul class="scbox2">
<li class="title">
<span class="tred" id="stock_extends">民族</span>
<a href="#" class="lgray underlines Bl_3"  onClick="baseSelectedEdit();addConditionopen();singleSelectedEdit('stock');">编辑</a>
</li>
<li class="lh24">
<script>selectedFiled(syscode.stock,",<?php echo $nation;?>,","stock8_","Stock");</script>
</li>
</ul>
<?php } ?>

<p id="alldelete" style="display:none;margin-left:200px;" class="textRight">
<a href="index.php?n=search&h=quick&quick_search=1&gender=<?php echo $gender;?>&agebegin=<?php echo $age_start;?>&ageend=<?php echo $age_end;?>&workprovince=<?php echo $work_province;?>&workcity=<?php echo $work_city;?><?php if(isset($photo)) { ?>&photo=<?php echo $photo;?><?php } ?>&condition=<?php echo $condition;?>&sortway=<?php echo $sortway;?>" class="lgray underlines" onclick="clearConditions();delAllItem();">
全部清除
</a>
</p>
<script type="text/javascript">
if($('#marriage_extends').size()>0 || $('#salary_extends').size()>0  || $('#education_extends').size()>0 || $('#height_extends').size()>0|| $('#house_extends').size()>0  || $('#vehicle_extends').size()>0  || $('#hometown_province_Id').size()>0 || $('#ismoking_extends').size()>0   || $('#isdrinking_extends').size()>0  || $('#children_extends').size()>0  || $('#wantchildren_extends').size()>0 || $('#weight_extends').size()>0  || $('#body_extends').size()>0  || $('#weight_extends').size()>0  || $('#stock_extends').size()>0  || $('#animals_extends').size()>0 || $('#constellation_extends').size()>0 || $('#bloodtype_extends').size()>0 || $('#occupation_extends').size()>0 || $('#corptype_extends').size()>0 || $('#belief_extends').size()>0  || $('#tonguegift_extends').size()>0  || $('#family_extends').size()>0 ) { $('#alldelete').css("display","");}
</script>
<div class="addscd" style="display:block;">
<h3>增加搜索条件</h3>
<ul class="commonbox2">
<?php if(isset($marriage) && $marriage == '0') { ?>
<li class="carrow" onclick="tabtoggle(this,'#c1')">婚姻状况</li>
<li class="commonlist" id="c1" style="display:none">
<script type="text/javascript">changyong(syscode.marriage,'Marriage');</script>
</li>
<?php } ?>

<?php if($home_townprovince == '0' && $home_towncity == '0') { ?>
<li class="carrow" onclick="tabtoggle(this,'#c2')">籍贯</li>
<li class="commonlist" id="c2" style="display:none">
<script>
syscode.provinceSelect('select53','areaForm.workProvince3','workcityprovince','areaForm.workCity3','','10100000',syscode.buxian);
</script>
<script>
syscode.citySelect('select53','areaForm.workCity3','workcitycity','','0',syscode.buxian);
</script>
<input class="addbut" name="" type="button" value="增加" onclick="addHometown_changyong();" />
</li>
<?php } ?>
<script type="text/javascript">
var addHometown_changyong = function(){
var a = document.getElementById('areaForm.workProvince3').value;
var b = document.getElementById('areaForm.workCity3').value;
location.href="index.php?n=search&h=add_del_search&gender=<?php echo $gender;?>&age_start=<?php echo $age_start;?>&age_end=<?php echo $age_end;?>&workprovince=<?php echo $work_province;?>&workcity=<?php echo $work_city;?>&Marriage=<?php echo $marriage;?>&Salary=<?php echo $salary;?>&Education=<?php echo $education;?>&height1=<?php echo $height1;?>&height2=<?php echo $height2;?>&weight1=<?php echo $weight1;?>&weight2=<?php echo $weight2;?>&Body=<?php echo $body;?>&ismok=<?php echo $smoking;?>&idrink=<?php echo $drinking;?>&Occupation=<?php echo $occupation;?>&House=<?php echo $house;?>&Vehicle=<?php echo $vehicle;?>&Corptp=<?php echo $corptype;?>&Child=<?php echo $children;?>&Wtchildren=<?php echo $wantchildren;?>&Stock=<?php echo $nation;?>&Animals=<?php echo $animalyear;?>&Constellation=<?php echo $constellation;?>&Bty=<?php echo $bloodtype;?>&Belief=<?php echo $religion;?>&Family=<?php echo $family;?>&Tonguegift=<?php echo $language;?>&photo=<?php echo $photo;?>&condition=<?php echo $condition;?>&sortway=<?php echo $sortway;?>&"+"&workcityprovince1="+a+"&workcitycity1="+b;
}
</script>

<?php if(isset($salary) && $salary == '0') { ?>
<li class="carrow" onclick="tabtoggle(this,'#c3')">月收入</li>
<li class="commonlist" id="c3" style="display:none">
<script type="text/javascript">changyong(syscode.salary,'Salary'); </script>
</li>
<?php } ?>

<?php if(isset($education) && $education == '0') { ?>
<li class="carrow" onclick="tabtoggle(this,'#c4')">教育程度</li>
<li class="commonlist" id="c4" style="display:none">
<script type="text/javascript">changyong(syscode.education,'Education'); </script>
</li>
<?php } ?>
<script type="text/javascript">
var addHeight_changyong = function(){
var a = $('#height1').attr('value');
var b = $('#height2').attr('value');
if(a!=0 && b!=0 && a>b) {
	alert('您选择的“身高”不正确');
	$('#height1').focus();
	return false;
}
location.href="index.php?n=search&h=add_del_search&gender=<?php echo $gender;?>&age_start=<?php echo $age_start;?>&age_end=<?php echo $age_end;?>&workprovince=<?php echo $work_province;?>&workcity=<?php echo $work_city;?>&Marriage=<?php echo $marriage;?>&Salary=<?php echo $salary;?>&Education=<?php echo $education;?>&weight1=<?php echo $weight1;?>&weight2=<?php echo $weight2;?>&Body=<?php echo $body;?>&ismok=<?php echo $smoking;?>&idrink=<?php echo $drinking;?>&Occupation=<?php echo $occupation;?>&House=<?php echo $house;?>&Vehicle=<?php echo $vehicle;?>&Corptp=<?php echo $corptype;?>&Child=<?php echo $children;?>&Wtchildren=<?php echo $wantchildren;?>&workcityprovince1=<?php echo $home_townprovince;?>&workcitycity1=<?php echo $home_towncity;?>&Stock=<?php echo $nation;?>&Animals=<?php echo $animalyear;?>&Constellation=<?php echo $constellation;?>&Bty=<?php echo $bloodtype;?>&Belief=<?php echo $religion;?>&Family=<?php echo $family;?>&Tonguegift=<?php echo $language;?>&photo=<?php echo $photo;?>&condition=<?php echo $condition;?>&sortway=<?php echo $sortway;?>&"+"&height1="+a+"&height2="+b;
}
</script>
<?php if(isset($height1) && $height1 == '0' && isset($height2) && $height2 == '0') { ?>
<li class="carrow" onclick="tabtoggle(this,'#c5')">身高</li>
<li class="commonlist" id="c5" style="display:none">
<script>syscode.select('select51','height1','h1','0','0',syscode.height,syscode.buxian);</script>
到
<script>syscode.select('select51','height2','h2','0','0',syscode.height,syscode.buxian);</script>
<input class="addbut" name="" type="button" value="增加" onclick="addHeight_changyong();" />
</li>
<?php } ?>

<?php if(isset($house) && $house == '0') { ?>
<li class="carrow" onclick="tabtoggle(this,'#c6')">住房情况</li>
<li class="commonlist" id="c6" style="display:none">
<script type="text/javascript">changyong(syscode.house,'House');</script>
</li>
<?php } ?>

<?php if(isset($vehicle) && $vehicle == '0') { ?>
<li class="carrow" onclick="tabtoggle(this,'#c7')">是否购车</li>
<li class="commonlist" id="c7" style="display:none">
<script type="text/javascript">changyong(syscode.vehicle,'Vehicle');</script>
</li>
<?php } ?>
</ul>
</div>
<p class="mt"><a href="#" class="lgray underlines"  onClick="baseSelectedEdit();addConditionopen();">设置更多搜索条件</a></p>
</div>
<!----------==========-->
</div>
<div class="centent-right-side" style="background-
;"></div>


<div class="search-id">
<div class="search-id-title f-b-d73c90">
按ID搜索
</div>
<form action="index.php?n=search&h=nickid" method="get" name="accountform" onsubmit="return checkid()">
<div class="search-id-in">
请输入ID号：
<input type="hidden" name="n" value="search">
<input type="hidden" name="h" value="nickid">
<input name="info" type="text" class="search-id-text" id="searchid"/>
<input name="startSearch" type="submit"  style="margin-left:90px;" class="btn btn-default" value="开始搜索" />
</div>
</form>


<!-- <div> <a href="http://www.zhenaiyisheng.cc/index.php?n=activity&h=activity" target="_blank" ><img  src="module/search/templates/default/images/ad.gif" border="0" width="290" height="170" /></a></div> -->
<!-- pufang advertisment 
<div> <a href="http://www.pufung.com/huodong/sajiaozhuanti.html" target="_blank" ><img  src="module/search/templates/default/images/sajiao_search.jpg" border="0" width="290" height="170" /></a></div>-->

<div class="search-id-title" style="background-position:bottom; height:3px; font-size:0px;"></div>

</div>



</div><!--centent-right end-->
<div class="clear"></div>
<div id="fullbg" style="display: none;"></div>
<div id="group_id" style="display: none; left: 50%; top:180px; margin-left: -492px;">
<div id="group" class="B_group" style="left: 0px; display: none;">
<p class="h-seache-side-top"></p>
<div class="c-center2 clearfix">
<div class="h-seache-title"><span class="fleft">设置更多搜索条件</span><a href="#" onclick="closeThis()" class="close-this"></a>
<div class="clear"></div>
</div>
<script type="text/javascript">
function closeThis(){
$("#fullbg").css("display",'none');
$("#group_id").css("display","none");
$("#group").css("display","none");
$("#search_more").css("display","block");
}
</script>

<div class="search-criteria-tipm clearfix">
<div class="h-search-side2"></div>
<div class="tipboxl2">
<p style="color: rgb(215, 60, 144);" class="h-seache-title">您选择的搜索条件：</p>
<div class="search-criteria-m">
<div id="addSearchCondition">
<?php if(isset($marriageArr) && $marriageArr) { ?>
<ul class="scbox2" id="marriage_title">
<li class="title">
	<span class="tred" id="marriage_extends2">婚姻状况</span>
</li>
<li>
	<script>selectedFiled2(syscode.marriage,",<?php echo $marriage;?>,","marriage","marriage");</script>
</li>
</ul>
<?php } ?>

<?php if(isset($salaryArr) && $salaryArr) { ?>
<ul class="scbox2" id="salary_title">
<li class="title">
	<span class="tred" id="salary_extends2">月收入</span>
</li>
<li>
	<script>selectedFiled2(syscode.salary,",<?php echo $salary;?>,","salary","salary");</script>
</li>
</ul>
<?php } ?>

<?php if(isset($educationArr) && $educationArr) { ?>
<ul class="scbox2" id="education_title">
<li class="title">
	<span class="tred" id="education_extends2">教育程度</span>
</li>
<li>
	<script>selectedFiled2(syscode.education,",<?php echo $education;?>,","education","education");</script>
</li>
</ul>
<?php } ?> 

<?php if(isset($height1) && isset($height2) && $height1 && $height2) { ?>
<?php if($height1 != '0' || $height2 != '0') { ?>
<ul class="scbox2" id="height_title">
<li class="title">
	<span class="tred" id="height_extends2">身高</span>
</li>
<li class="label">
	<a onclick="removeNewSelected(this);removeNewAddedHeight();" href="##">
<?php if(($height1 != '0' && $height1 !='0') && ($height2 != '0' && $height2 != '0')) { ?>
<?php echo $height1;?>cm 到 <?php echo $height2;?>cm
<?php } elseif ($height1 != '0') { ?>
<?php echo $height1;?>cm - 不限
<?php } else { ?>
<?php echo $height2;?>cm
<?php } ?>
	</a>
</li>
</ul>
<?php } ?>
<?php } ?>

<?php if(isset($houseArr) && $houseArr) { ?>
<ul class="scbox2" id="house_title">
<li class="title">
	<span class="tred" id="house_extends2">住房情况</span>
</li>
<li class="label">
	<script>selectedFiled2(syscode.house,",<?php echo $house;?>,","house","house");</script>
</li>
</ul>
<?php } ?>

<?php if(isset($vehicleArr) && $vehicleArr) { ?>
<ul class="scbox2" id="vehicle_title">
<li class="title">
	<span class="tred" id="vehicle_extends2">是否购车</span>
</li>
<li class="label">
	<script>selectedFiled2(syscode.vehicle,",<?php echo $vehicle;?>,","vehicle","vehicle");</script>
</li>
</ul>
<?php } ?>

<?php if(isset($home_townprovince) && isset($home_towncity) && $home_townprovince && $home_towncity) { ?>
<?php if($home_townprovince != '0' || $home_towncity != '0') { ?>
<ul class="scbox2" id="hometown_title">
<li class="title">
	<span class="tred" id="hometown_province_Id2">籍贯</span>
</li>
<li class="label">
	<a onclick="removeNewSelected(this);removeNewAddedHometown();" href="##">
<?php if($home_townprovince != '0' && $home_townprovince != '0' && $home_towncity != '0' && $home_towncity != '0') { ?>
		<script type="text/javascript">
			userdetail('<?php echo $home_townprovince;?>',provice)
			userdetail('<?php echo $home_towncity;?>',city)
		</script>
<?php } elseif ($home_townprovince != '0') { ?>
	<script type="text/javascript">
			userdetail('<?php echo $home_townprovince;?>',provice)
			userdetail('0',city)
		</script>
<?php } else { ?>
		<script type="text/javascript">
			userdetail('0',provice)
			userdetail('0',city)
		</script>
<?php } ?>
	</a>
</li>
</ul>
<?php } ?>
<?php } ?>

<?php if(isset($smokingArr) && $smokingArr) { ?>
<ul class="scbox2" id="ismok_title">
<li class="title">
	<span class="tred" id="ismoking_extends2">是否吸烟</span>
</li>
<li class="label">
	<script>selectedFiled2(syscode.isSmoking,",<?php echo $smoking;?>,","ismok","ismok");</script>
</li>
</ul>
<?php } ?>

<?php if(isset($drinkingArr) && $drinkingArr) { ?>
<ul class="scbox2" id="isdrink_title">
<li class="title">
	<span class="tred" id="isdrinking_extends2">是否喝酒</span>
</li>
<li class="label">
	<script>selectedFiled2(syscode.isDrinking,",<?php echo $drinking;?>,","idrink","idrink");</script>
</li>
</ul>
<?php } ?>

<?php if(isset($childrenArr) && $childrenArr) { ?>
<ul class="scbox2" id="child_title">
<li class="title">
	<span class="tred" id="children_extends2">是否有孩子</span>
</li>
<li class="label">
	<script>selectedFiled2(syscode.children,",<?php echo $children;?>,","child","child");</script>
</li>
</ul>
<?php } ?>

<?php if(isset($wantchildrenArr) && $wantchildrenArr) { ?>
<ul class="scbox2" id="wtchildren_title">
<li class="title">
	<span class="tred" id="wantchildren_extends2">是否想要孩子</span>
</li>
<li class="label">
	<script>selectedFiled2(syscode.wantChildren,",<?php echo $wantchildren;?>,","wtchildren","wtchildren");</script>
</li>
</ul>
<?php } ?>

<?php if(isset($nationArr) && $nationArr) { ?>
<ul class="scbox2" id="stock_title">
<li class="title">
	<span class="tred" id="stock_extends2">民族</span>
</li>
<li class="label">
	<script>selectedFiled2(syscode.stock,",<?php echo $nation;?>,","stock","stock");</script>
</li>
</ul>
<?php } ?>

<?php if(isset($animalyearArr) && $animalyearArr) { ?>
<ul class="scbox2" id="animals_title">
<li class="title">
	<span class="tred" id="animals_extends2">生肖</span>
</li>
<li class="label">
	<script>selectedFiled2(syscode.animals,",<?php echo $animalyear;?>,","animals","animals");</script>
</li>
</ul>
<?php } ?>

<?php if(isset($constellationArr) && $constellationArr) { ?>
<ul class="scbox2" id="constellation_title">
<li class="title">
	<span class="tred" id="constellation_extends2">星座</span>
</li>
<li class="label">
	<script>selectedFiled2(syscode.constellation,",<?php echo $constellation;?>,","constellation","constellation");</script>
</li>
</ul>
<?php } ?>

<?php if(isset($bloodtypeArr) && $bloodtypeArr) { ?>
<ul class="scbox2" id="bty_title">
<li class="title">
	<span class="tred" id="bloodtype_extends2">血型</span>
</li>
<li class="label">
	<script>selectedFiled2(syscode.bloodtype,",<?php echo $bloodtype;?>,","bty","bty");</script>
</li>
</ul>
<?php } ?>

<?php if(isset($bodyArr) && $bodyArr) { ?>
<ul class="scbox2" id="body_title">
<li class="title">
	<span class="tred" id="body_extends2">体型</span>
</li>
<li class="label">
	<script>selectedFiled2(syscode.body1,",<?php echo $body;?>,","body","body");</script>
</li>
</ul>
<?php } ?>

<?php if(isset($weight1) && $weight1 || isset($weight2) && $weight2) { ?>
	<?php if($weight1 != '0' || $weight1 != '0' || $weight2 != '0' || $weight2 != '0') { ?>
	<ul class="scbox2" id="weight_title">
	<li class="title">
		<span class="tred" id="weight_extends2">体重</span>
	</li>
	<li class="label">
		<a href="##" onclick="delCruSearchResultWeight(this);">
	<?php if($weight1 != '0' && $weight2 != '0') { ?>
		<?php echo $weight1;?> 到 <?php echo $weight2;?> 公斤
		<?php } elseif ($weight1 != '0' && $weight2 == '0') { ?>
		<?php echo $weight1;?> 公斤以上
		<?php } elseif ($weight1 == '0' && $weight2 != '0') { ?>
		<?php echo $weight2;?> 公斤以下
		<?php } else { ?>
		不限					
	<?php } ?>
		</a>
	</li>
	</ul>
	<?php } ?>
<?php } ?>

<?php if(isset($occupationArr) && $occupationArr) { ?>
<ul class="scbox2" id="occupation_title">
<li class="title">
	<span class="tred" id="occupation_extends2">从事职业</span>
</li>
<li class="label">
	<script>selectedFiled2(syscode.occupationbt,",<?php echo $occupation;?>,","occupation","occupation");</script>
</li>
</ul>
<?php } ?>

<?php if(isset($corptypeArr) && $corptypeArr) { ?>
<ul class="scbox2" id="corptp_title">
<li class="title">
	<span class="tred" id="corptp_extends2">公司类型</span>
</li>
<li class="label">
	<script>selectedFiled2(syscode.corptype,",<?php echo $corptype;?>,","corptp","corptp");</script>
</li>
</ul>
<?php } ?>

<?php if(isset($religionArr) && $religionArr) { ?>
<ul class="scbox2" id="belief_title">
<li class="title">
	<span class="tred" id="belief_extends2">信仰</span>
</li>
<li class="label">
	<script>selectedFiled2(syscode.belief,",<?php echo $religion;?>,","belief","belief");</script>
</li>
</ul>
<?php } ?>

<?php if(isset($languageArr) && $languageArr) { ?>
<ul class="scbox2" id="tonguegift_title">
<li class="title">
	<span class="tred" id="tonguegift_extends2">语言能力</span>
</li>
<li class="label">
	<script>selectedFiled2(syscode.tonguegifts,",<?php echo $language;?>,","tonguegift","tonguegift");</script>
</li>
</ul>
<?php } ?>

<?php if(isset($familyArr) && $familyArr) { ?>
<ul class="scbox2" id="family_title">
<li class="title">
	<span class="tred" id="family_extends2">兄弟姐妹</span>
</li>
<li class="label">
	<script>selectedFiled2(syscode.family,",<?php echo $family;?>,","family","family");</script>
</li>
</ul>
<?php } ?>
</div>
<p id="alldelete2" style="display:none" class="textRight">
<a href="##" onclick="delAllItem2();" class="lgray underlines">全部清除</a>
</p>
<script type="text/javascript">
if($('#marriage_extends2').size()>0 || $('#salary_extends2').size()>0  || $('#education_extends2').size()>0 || $('#height_extends2').size()>0|| $('#house_extends2').size()>0  || $('#vehicle_extends2').size()>0  || $('#hometown_province_Id2').size()>0 || $('#ismoking_extends2').size()>0   || $('#isdrinking_extends2').size()>0  || $('#children_extends2').size()>0  || $('#wantchildren_extends2').size()>0 || $('#weight_extends2').size()>0  || $('#body_extends2').size()>0  || $('#weight_extends2').size()>0  || $('#stock_extends2').size()>0  || $('#animals_extends2').size()>0 || $('#constellation_extends2').size()>0 || $('#bloodtype_extends2').size()>0 || $('#occupation_extends2').size()>0 || $('#corptp_extends2').size()>0 || $('#belief_extends2').size()>0  || $('#tonguegift_extends2').size()>0  || $('#family_extends2').size()>0 ) { $('#alldelete2').css("display","");}
</script>
</div>
</div>
<script type="text/javascript">
<!--
//--------------------------------------------------------------------------
var checkForm = function() {
	var sex = document.getElementById("sex").value;
	var age1 = document.getElementById("a1").value;
	var age2 = document.getElementById("a2").value;
	var h1 = document.getElementById("h1").value;
	var h2 = document.getElementById("h2").value;
	var w1 = document.getElementById("w1").value;
	var w2 = document.getElementById("w2").value;

	if(sex == null) {
		alert("请选择性别");
		document.getElementById("sex").focus();
		return false;
	}
	if( age1 == 0 && age2 == 0) {
		alert("请选择年龄");
		document.getElementById("age1").focus();
		return false;
	}
	if(age1!=null && age1!='' && age1==0 && age2!=null && age2!='' && age2!=0) {
		alert("请选择年龄下限");
		document.getElementById("age1").focus();
		return false;
	}
	if(age1!=null && age1!='' && age1!=0 && age2!=null && age2!='' && age2==0) {
		alert("请选择年龄上限");
		document.getElementById("age2").focus();
		return false;
	}
	if(age1!=null && age1!='' && age1!=0 && age2!=null && age2!='' && age2!=0 && age1>age2) {
		alert("您选择的年龄范围不正确，请重新选择");
		document.getElementById("age1").focus();
		return false;
	}
	if(h1!=null && h1!='' && h1!=0 && h2!=null && h2!='' && h2!=0 && h1>h2) {
		alert("您选择的身高范围不正确，请重新选择");
		document.getElementById("h1").focus();
		return false;
	}
	if(w1!=null && w1!='' && w1!=0 && w2!=null && w2!='' && w2!=0 && w1>w2) {
		alert("您选择的体重范围不正确，请重新选择");
		$('#w1').focus();
		return false;
	}
	var ic = document.getElementById("is_commend");
	var issavesearchname = document.getElementById("issavesearchname");
	var searchname = document.getElementById("searchname");
	if(issavesearchname.checked==true) {
		var temp = Trim(searchname.value);
		if(temp == null || temp == ''){
			alert("请输入搜索条件名");
			searchname.focus();
			return false;
		}
		issavesearchname.value = 1;
		searchname.value = temp;
		checkWord(22,event,'对不起！您输入的搜索条件名过长！');
	} else if(issavesearchname.checked==false &&  null != temp && temp != '') {
		issavesearchname.value = 1;
		searchname.value = temp;
		issavesearchname.checked=true;
	}
	if(ic.checked==true) {
		ic.value = 1;
	}
	//$("#buttonsearch").attr("class","search-button-h2");
	//$("#buttonsearch").val("");
	$("#buttonsearch").val("搜索中...");
	return;
}



var changeBody = function(value){
	var bodybtbt = document.getElementById("bodybtbt");
	var htmlStr="";
	if(value=='20'){
		bodybtbt.innerHTML=syscode.checkboxHtml2('body','body','',syscode.body0,'p','0,不限','body');
		if($('#body_title').size()>0){
			$('#body_title').remove();
		}
	}else if(value=='1'){
		bodybtbt.innerHTML=syscode.checkboxHtml2('body','body','',syscode.body1,'p','0,不限','body');
		if($('#body_title').size()>0){
			$('#body_title').remove();
		}
	}else if(value=='0'){
		bodybtbt.innerHTML=syscode.checkboxHtml2('body','body','',syscode.body0,'p','0,不限','body');
		if($('#body_title').size()>0){
			$('#body_title').remove();
		}
	}
}
function subform(dest)
{
	document.orderbyform.action=dest;
	document.orderbyform.submit();
}

//--------------------------------------------------------------------
//-->
</script>


<!-- begin form -->

<form id="hs" name="hs" action="index.php" method="get" onsubmit="return checkForm()">
<input type="hidden" value="search" name="n">
<input type="hidden" value="super" name="h">
<input type="hidden" name="advance_search" value="1">
<div class="tipboxr clearfix">
	<ul class="databox clearfix">
		<li>
			<h5>基本条件</h5>
			<ul class="basic">
				<li>我要找：
					<select name="gender" id="sex" onchange="changeBody(this.value);" class="selectSize">
						<?php if($user_arr['gender'] == 1) { ?>
						<option value="1">女士</option>
						<option value="0" selected="selected">男士</option>
						<?php } else { ?>
						<option value="1" selected="selected">女士</option>
						<option value="0">男士</option>
						<?php } ?>
					</select>
					<input name="photo" type="checkbox" checked="checked" value="1" />有照片</li>
				<li>年&nbsp;&nbsp;龄：
					<script>syscode.select('select50','a1','age_start','<?php echo $age_start;?>','21',syscode.age,syscode.buxian);</script>
						到
					<script>syscode.select('select50','a2','age_end','<?php echo $age_end;?>','45',syscode.age,syscode.buxian);</script>
				</li>
				<li>所在地：
					<script>syscode.provinceSelect('selectSize','areaForm.workProvince','workprovince','areaForm.workCity','<?php echo $work_province;?>','10100000',syscode.buxian);</script>
					<script>syscode.citySelect('selectSize','areaForm.workCity','workcity','<?php echo $work_city;?>','0',syscode.buxian);</script>
				</li>
			</ul>
			<h5>生活细节</h5>
			<dl>
				<dt id="personal_ismok" class="darrow" onclick="tabtoggle2(this,'#personal_view_ismok')">是否抽烟</dt>
				<dd class="datalist" id="personal_view_ismok" style="display:none">
					<script>syscode.checkbox2('ismok','ismok',',<?php echo $smoking;?>,',syscode.isSmoking,'p',syscode.buxian,'ismok');checkBuXian(',<?php echo $smoking;?>,','ismok_0');</script>
				</dd>
				<dt id="personal_idrink" class="darrow" onclick="tabtoggle2(this,'#personal_view_idrink')">是否喝酒</dt>
				<dd class="datalist" id="personal_view_idrink" style="display:none">
					<script>syscode.checkbox2('idrink','idrink',',<?php echo $drinking;?>,',syscode.isDrinking,'p',syscode.buxian,'idrink');checkBuXian(',<?php echo $drinking;?>,','idrink_0');</script>
				</dd>
				<dt id="personal_child" class="darrow" onclick="tabtoggle2(this,'#personal_view_child')">是否有孩子</dt>
				<dd class="datalist" id="personal_view_child" style="display:none">
					<script>syscode.checkbox2('child','child','",<?php echo $children;?>,',syscode.taChildren,'p',syscode.buxian,'child');checkBuXian(',<?php echo $children;?>,','child_0');</script>
				</dd>
				<dt id="personal_wtchildren" class="darrow" onclick="tabtoggle2(this,'#personal_view_wtchildren')">是否想要孩子</dt>
				<dd class="datalist" id="personal_view_wtchildren" style="display:none">
					<script>syscode.checkbox2('wtchildren','wtchildren',',<?php echo $wantchildren;?>,',syscode.taWantChildren,'p',syscode.buxian,'wtchildren');checkBuXian(',<?php echo $wantchildren;?>,','wtchildren_0');</script>
				</dd>
			</dl>
		</li>
		<li>
			<h5>外形条件</h5>
			<dl style="margin-bottom:10px">
				<dt id="personal_height" class="darrow" onclick="tabtoggle2(this,'#personal_view_height')">身高</dt>
				<dd class="datalist" id="personal_view_height" style="display:none">
					<script>syscode.select('selectSize','h1','height1','<?php echo $height1;?>','0',syscode.height,syscode.buxian);</script> 到 <script>syscode.select('selectSize','h2','height2','<?php echo $height2;?>','0',syscode.height2,syscode.buxian);</script>
					<input class="addbut" name="" type="button" value="增加" onclick="addHeight();" /></dd>
				<dt id="personal_weight" class="darrow" onclick="tabtoggle2(this,'#personal_view_weight')">体重</dt>
				<dd class="datalist" id="personal_view_weight" style="display:none">
					<script>syscode.select('selectSize','w1','weight1','<?php echo $weight1;?>','0',syscode.weight,syscode.buxian);</script>
						到
					<script>syscode.select('selectSize','w2','weight2','<?php echo $weight2;?>','0',syscode.weight2,syscode.buxian);</script>
					<input class="addbut" name="" type="button" value="增加" onclick="addWeight();" />
				</dd>
				<dt id="personal_body" class="darrow" onclick="tabtoggle2(this,'#personal_view_body')">体型</dt>
				<dd class="datalist" id="personal_view_body" style="display:none">
					<ul class="clearfix">
						<li id="bodybtbt" class="clearfix">
							<?php if($user_arr['gender'] == '0') { ?>
								<script>syscode.checkbox2('body','body',',<?php echo $body;?>,',syscode.body1,'p',syscode.buxian,'body');checkBuXian(',,','body_0');</script>
							<?php } else { ?>
								<script>syscode.checkbox2('body','body',',<?php echo $body;?>,',syscode.body0,'p',syscode.buxian,'body');checkBuXian(',,','body_0');</script>
							<?php } ?>
						</li>
					</ul>
				</dd>
			</dl>
			<h5>背景条件</h5>
			<dl>
				<dt id="personal_hometown" class="darrow" onclick="tabtoggle2(this,'#personal_view_hometown')">籍贯</dt>
				<dd class="datalist" id="personal_view_hometown" style="display:none">
					<script>syscode.provinceSelect('selectSize','areaForm.workProvince1','workcityprovince1','areaForm.workCity1','<?php echo $home_townprovince;?>','0',syscode.buxian);</script>
					<script>syscode.citySelect('selectSize','areaForm.workCity1','workcitycity1','<?php echo $home_towncity;?>','0',syscode.buxian);</script>
					<input class="addbut" name="" type="button" value="增加" onclick="addHometown();" />
				</dd>
				<dt id="personal_stock" class="darrow" onclick="tabtoggle2(this,'#personal_view_stock')">民族</dt>
				<dd class="datalist" id="personal_view_stock" style="display:none">
					<script>syscode.select('','stock','stock','<?php echo $nation;?>','0',syscode.stock,syscode.buxian);</script>
					<input class="addbut" name="" type="button" value="增加" onclick="addStock();" />
				</dd>
				<dt id="personal_animals" class="darrow" onclick="tabtoggle2(this,'#personal_view_animals')">生肖</dt>
				<dd class="datalist" id="personal_view_animals" style="display:none">
					<ul class="clearfix">
						<li class="clearfix">
							<script>syscode.checkbox2('animals','animals',',<?php echo $animalyear;?>,',syscode.animals,'p',syscode.buxian,'animals');checkBuXian(',<?php echo $animalyear;?>,','animals_0');</script>
						</li>
					</ul>
				</dd>
				<dt id="personal_constellation" class="darrow" onclick="tabtoggle2(this,'#personal_view_constellation')">星座</dt>
				<dd class="datalist" id="personal_view_constellation" style="display:none">
					<ul class="clearfix">
						<li class="clearfix">
							<script>syscode.checkbox2('constellation','constellation',',<?php echo $constellation;?>,',syscode.constellation,'li',syscode.buxian,'constellation');checkBuXian(',<?php echo $constellation;?>,','constellation_0');</script>
					</ul>
				</dd>
				<dt id="personal_bty" class="darrow" onclick="tabtoggle2(this,'#personal_view_bty')">血型</dt>
				<dd class="datalist" id="personal_view_bty" style="display:none">
					<ul class="clearfix">
						<li class="clearfix">
							<script>syscode.checkbox2('bty','bty',',<?php echo $bloodtype;?>,',syscode.bloodtype,'p',syscode.buxian,'bty');checkBuXian(',<?php echo $bloodtype;?>,','bty_0');</script>
						</li>
					</ul>
				</dd>
			</dl>
		</li>
		<li>
			<h5>个人资料</h5>
			<dl>
				<dt id="personal_marriage" class="darrow" onclick="tabtoggle2(this,'#personal_view_marriage')">婚姻状况</dt>
				<dd class="datalist" id="personal_view_marriage" style="display:none">
					<ul class="clearfix">
						<li class="clearfix">
							<script>syscode.checkbox2('marriage','marriage',',<?php echo $marriage;?>,',syscode.marriage,'p',syscode.buxian,'marriage');checkBuXian(',<?php echo $marriage;?>,','marriage_0');</script>
						</li>
					</ul>
				</dd>
				<dt id="personal_education" class="darrow" onclick="tabtoggle2(this,'#personal_view_education')">教育程度</dt>
				<dd class="datalist" id="personal_view_education" style="display:none">
					<ul class="clearfix">
						<li class="clearfix">
							<script>syscode.checkbox2('education','education',',<?php echo $education;?>,',syscode.education,'p',syscode.buxian,'education');checkBuXian(',<?php echo $education;?>,','education_0');</script>
						</li>
					</ul>
				</dd>
				<dt id="personal_salary" class="darrow" onclick="tabtoggle2(this,'#personal_view_salary')">月收入</dt>
				<dd class="datalist" id="personal_view_salary" style="display:none">
					<script>syscode.checkbox2('salary','salary',',<?php echo $salary;?>,',syscode.salary,'p',syscode.buxian,'salary');checkBuXian(',<?php echo $salary;?>,','salary_0');</script>
				</dd>
				<dt id="personal_occupation" class="darrow" onclick="tabtoggle2(this,'#personal_view_occupation')">从事职业</dt>
				<dd class="datalist" id="personal_view_occupation" style="display:none">
					<ul class="clearfix">
						<li class="clearfix">
							<script>syscode.checkbox2('occupation','occupation',',<?php echo $occupation;?>,',syscode.occupationbt,'p',syscode.buxian,'occupation');checkBuXian(',<?php echo $occupation;?>,','occupation_0');</script>
						</li>
					</ul>
				</dd>
				<dt id="personal_house" class="darrow" onclick="tabtoggle2(this,'#personal_view_house')">住房情况</dt>
				<dd class="datalist" id="personal_view_house" style="display:none">
					<script>syscode.checkbox2('house','house',',<?php echo $house;?>,',syscode.house,'p',syscode.buxian,'house');checkBuXian(',<?php echo $house;?>,','house_0');</script>
				</dd>
				<dt id="personal_vehicle" class="darrow" onclick="tabtoggle2(this,'#personal_view_vehicle')">是否购车</dt>
				<dd class="datalist" id="personal_view_vehicle" style="display:none">
					<script>syscode.checkbox2('vehicle','vehicle',',<?php echo $vehicle;?>,',syscode.vehicle,'p',syscode.buxian,'vehicle');checkBuXian(',<?php echo $vehicle;?>,','vehicle_0');</script>
				</dd>
				<dt id="personal_corptp" class="darrow" onclick="tabtoggle2(this,'#personal_view_corptp')">公司类别</dt>
				<dd class="datalist" id="personal_view_corptp" style="display:none">
					<ul class="clearfix">
						<li class="clearfix">
							<script>syscode.checkbox2('corptp','corptp',',<?php echo $corptype;?>,',syscode.corptype,'p',syscode.buxian,'corptp');checkBuXian(',<?php echo $corptype;?>,','corptp_0');</script>
						</li>
					</ul>
				</dd>
				<dt id="personal_belief" class="darrow" onclick="tabtoggle2(this,'#personal_view_belief')">信仰</dt>
				<dd class="datalist" id="personal_view_belief" style="display:none">
					<ul class="clearfix">
						<li class="clearfix">
							<script>syscode.checkbox2('belief','belief',',<?php echo $religion;?>,',syscode.belief,'p',syscode.buxian,'belief');checkBuXian(',<?php echo $religion;?>,','belief_0');</script>
						</li>
					</ul>
				</dd>
				<dt id="personal_tonguegift" class="darrow" onclick="tabtoggle2(this,'#personal_view_tonguegift')">语言能力</dt>
				<dd class="datalist" id="personal_view_tonguegift" style="display:none">
					<script>syscode.checkbox2('tonguegift','tonguegift',',<?php echo $language;?>,',syscode.tonguegifts,'p',syscode.buxian,'tonguegift');checkBuXian(',<?php echo $language;?>,','tonguegift_0');</script>
				</dd>
				<dt id="personal_family" class="darrow" onclick="tabtoggle2(this,'#personal_view_family')">兄弟姐妹</dt>
				<dd class="datalist" id="personal_view_family" style="display:none">
					<ul class="clearfix">
						<li class="clearfix">
							<script>syscode.checkbox2('family','family',',<?php echo $family;?>,',syscode.family,'p',syscode.buxian,'family');checkBuXian(',<?php echo $family;?>,','family_0');</script>
						</li>
					</ul>
				</dd>
			</dl>
		</li>
		<div style="clear:both"></div>
	</ul>
	<!--<div class="butbox clearfix" style="text-align:center" id="beginsearch">
		<input name="" value="开始搜索"  type="submit" class="search-button-h" style="float:none">
		<div class="clear"></div>
	</div>-->
	<div class="butbox clearfix">
		<div class="clearfix-p">
			<p><input name="issavesearchname" type="checkbox" value="1" id="issavesearchname" />&nbsp;保存这次搜索条件命名为：<input name="searchname" type="text"  class="clearfix-p-text" id="searchname" onblur="javascript:checkWord(22,event,'对不起！您输入的搜索条件名过长！')"/></p>
			<p><input name="is_commend" type="checkbox" value="1" id="is_commend" />&nbsp;定期推荐符合此搜索条件的会员到我的注册邮箱</p>
		</div>
		<div id="beginsearch">
			<input name="" value="开始搜索"  type="submit"  class="btn btn-default" id="buttonsearch"> <!--search-button-h-->
		</div>
	</div>
</div>
<div class="h-search-side" style="background-position:center bottom;margin-top:0!important; margin-top:-5px;"></div>
</form>

<!-- end form -->

<div style="clear:both;"></div>

</div>
</div>
<p class="h-seache-side-bottom"></p>
</div>

<div class="clear"></div>
</div>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="templates/css/general.css" rel="stylesheet" type="text/css" />
<link href="templates/css/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../public/system/js/sys1.js?v=1"></script>
<script type="text/javascript" src="templates/js/onmousemove_minutes.js"></script>
<script type="text/javascript" src="templates/js/My97DatePicker/WdatePicker.js"></script>
   <script type="text/javascript">
   function textCounter(field,countfieldId,leavingsfieldId,maxlimit) {
	    var countfield = document.getElementById(countfieldId);
	    var leavingsfield = document.getElementById(leavingsfieldId);
		if (field.value.length > maxlimit) // if too long...trim it!
		{
		  field.value = field.value.substring(0, maxlimit);
		  alert(" 限" + maxlimit + "字内！");
		} else { 
		  leavingsfield.innerHTML=maxlimit - field.value.length;
		  countfield.innerHTML=field.value.length;
		}
	} 



   
   //更改为全权会员，取消为全权会员
   $(function(){
     $("#changeToAcq").click(function(){
        
        var value=$(this).val();
        var uid=<?php echo $userinfo['uid'];?>;
        var url="./allmember_ajax.php?n=changeAcq";
        if(confirm('你确定'+uid+'会员更改为全权会员吗？')){
	       	$.post(url,{uid:uid,value:value},function(data){
	               if(data=='update'){
	                 alert("成功 更改为全权会员！");
	                 $("#changeToAcq").attr("disabled","disabled");
	               }else if(data=="cancel"){
	                 alert("成功取消 此会员 为全权会员！ ");
	               }else{
	                 alert("您的操作失败!");
	               }
	         }); 
        }else{
            $("#changeToAcq").attr("checked",false);
        }
     });

   });

   
   
   function checkForm(){ 
	   var select_usertype=$("select[name=usertype] option:selected").val();
	   var usertype=<?php echo $userinfo['usertype'];?>;
	   if (select_usertype==3 &&　usertype!=3){
		   alert("不能这样修改全权会员,请点左边的更改复选框！");
		   return false;
	   }

   }
   

    
   </script>
</head>

<body>
<form action="" method="post" onsubmit="return checkForm();">
<div class="main-div">
	<table width="100%">
	<tr>
		<td style="vertical-align:top;">
			<table style="width:400px" >
			  <tr>
			    <td class="label">会员昵称:</td>
			    <td> <input name="nickname" type="text" value="<?php echo $userinfo['nickname'];?>" <?php if($is_admin && !in_array($GLOBALS['groupid'],$GLOBALS['admin_all_group'])) { ?> readonly<?php } ?>/> </td>
			  </tr>
			  <?php if(in_array($GLOBALS['groupid'],$GLOBALS['system_admin'])) { ?>
			   <tr>
			    <td class="label">会员姓名:</td>
			    <td> <input name="username" type="text" value="<?php echo $userinfo['username'];?>" /> </td>
			  </tr>
			  <?php } ?>
			  <tr>
			    <td class="label">姓名:</td>
			    <td> <input name="truename" type="text" value="<?php echo $userinfo['truename'];?>" /> </td>
			  </tr>
			  <tr>
			    <td class="label">重设密码:</td>
			    <td>				
					<input name="password" type="password"  />
				</td>
			  </tr>
			  <tr>
			    <td class="label">手机:</td>
			    <td><input name="telphone" <?php if($GLOBALS['adminid']==52) { ?><?php } else { ?>value="<?php echo $userinfo['telphone'];?>"<?php } ?> type="text" <?php if($is_admin ) { ?> readonly<?php } ?>/> </td>
			  </tr>
			 
			  <tr>
			  	<td class="label">婚姻状况:</td>
			  	<td><?php if($is_admin ) { ?>不可修改<?php } else { ?><script>getSelect('','marriage1','marriage1','<?php echo $userinfo['marriage'];?>','1',marriage);</script><?php } ?></td>
			  </tr>
			
			   <tr>
			    <td class="label">身高:</td>
			    <td><?php if($is_admin ) { ?>不可修改<?php } else { ?><script>getSelect('','height','height','<?php echo $userinfo['height'];?>','0',height);</script><?php } ?></td>
			  </tr>
			  <tr>
			    <td class="label">月收入:</td>
			    <td><?php if($is_admin ) { ?>不可修改<?php } else { ?> <script>getSelect('','salary','salary','<?php echo $userinfo['salary'];?>','0',salary1);</script><?php } ?></td>
			  </tr>
			   <tr>
			    <td class="label">QQ:</td>
			    <td> <input type="text" name="qq" value="<?php echo $userfield['qq'];?>" /></td>
			  </tr>
			   <tr>
			    <td class="label">MSN:</td>
			    <td><input type="text" name="msn" value="<?php echo $userfield['msn'];?>" /></td>
			  </tr>
			   <tr>
			    <td class="label">最高学历:</td>
			    <td><?php if($is_admin ) { ?>不可修改<?php } else { ?><script>getSelect('','education1','education1','<?php echo $userinfo['education'];?>','0',education);</script><?php } ?></td>
			  </tr>
			  <tr>
			    <td class="label">有无小孩:</td>
			    <td><script>getSelect('','children1','children1','<?php echo $userinfo['children'];?>','0',children);</script></td>
			  </tr>
			    <tr>
			    <td class="label">住房情况:</td>
			    <td><?php if($is_admin ) { ?>不可修改<?php } else { ?><script>getSelect('','house','house','<?php echo $userinfo['house'];?>','0',house);</script><?php } ?> </td>
			  </tr>
			  <tr>
				<td class="label">性别：</td>
				<td>
				
				<select name="gender" id="gender">
					<option value=0 <?php if($userinfo['gender']==0) { ?>selected="selected"<?php } ?>>男</option>
					<option value=1 <?php if($userinfo['gender']==1) { ?>selected="selected"<?php } ?>>女</option>
				</select>
				
				</td>
			  </tr>			  
			  <tr>
				<td class="label">会员等级</td>
				<td> <?php if($is_admin) { ?>不可修改<?php } else { ?>
							<select name="s_cid">
								<?php foreach((array)$GLOBALS['member_level'] as $key=>$member_level) {?>
								
									<option value="<?php echo $key;?>" <?php if($userinfo['s_cid'] == $key) { ?>selected="selected"<?php } ?>><?php echo $member_level;?></option>
								
								<?php }?>
							</select>
					<?php } ?>
				</td>
			  </tr>			 
			  <tr>
				<td class="label">
				年龄
				</td>
				<td><?php if($is_admin ) { ?>不可修改<?php } else { ?>
				<!--<?php if(!isset($userinfo['birthmonth']) || !$userinfo['birthmonth']) { ?>
					<?php $userinfo['birthmonth'] = 1;?>
				<?php } ?>
				<?php if(!isset($userinfo['birthday']) || !$userinfo['birthday']) { ?>
					<?php $userinfo['birthday'] = 1;?>
				<?php } ?> -->
				
					<script>getYearsSelect('selectSize','year','year','<?php echo date('Y',strtotime($userinfo['birth']));?>','0');</script>年
					<script>getMonthsSelect('selectSize01','month','month','<?php echo  date('n',strtotime($userinfo['birth']));?>','0');</script>月
					<script>getDaysSelect('selectSize011','day','day','<?php echo date('j',strtotime($userinfo['birth']));?>','0');</script>日
				
				<?php } ?></td>
			  </tr>
			  <tr><td class="label"> 是否城市之星</td><td> <?php if($is_admin) { ?>不可修改<?php } else { ?>是：<input type="radio" name="city_star" value="1" <?php if($userinfo['city_star']>time()) { ?>checked<?php } ?> />否：<input type="radio" name="city_star" value="0"  <?php if($userinfo['city_star']==0) { ?>checked<?php } ?>/><?php } ?></td></tr>
			</table>
		</td>
		<td style="vertical-align:top;">
			<table style="width:400px" >
			 <tr>
			    <td class="label">多久内找到对象:</td>
			    <td> <script>getSelect('','expectlovedateid','oldsex','<?php echo $userinfo['oldsex'];?>','0',expectlovedate);</script></td>
	 	 	</tr>
			<tr>
			    <td class="label">工作地区:</td>
			    <td> <?php if($is_admin ) { ?>不可修改<?php } else { ?>
			    <script>getProvinceSelect66('','','workprovince','workCity','<?php echo $userinfo['province'];?>','10100000');</script>
				省
				<script>getCitySelect66('','workCity','workCity','<?php echo $userinfo['city'];?>','0');</script>
				市<?php } ?>
			    </td>
			</tr>
		  	<tr>
			    <td class="label">性格:</td>
			    <td><script>getSelect('','nature3','nature3','<?php echo $userfield['nature'];?>','1',nature);</script></td>
		 	</tr>
	  	  <tr>
		    <td class="label">体重:</td>
		    <td><?php if($is_admin ) { ?>不可修改<?php } else { ?><script>getSelect('selectSize','weight','weight','<?php echo $userfield['weight'];?>','0',weight);</script><?php } ?></td>
	  	  </tr>
	  	  <tr>
	    	<td class="label">体型:</td>
	    	<td> 
		    <?php if($userinfo['gender'] == 0) { ?>
				<script>getSelect('','body','body','<?php echo $userfield['body'];?>','1',body0);</script>
			<?php } else { ?>
				<script>getSelect('','body','body','<?php echo $userfield['body'];?>','1',body1);</script>
			<?php } ?>
			
	   	 	</td>
	  	 </tr>
		 
		 <tr> 
		    <td class="label">工作单位:</td>
		    <td><?php if($is_admin ) { ?>不可修改<?php } else { ?><script>getSelect('','corptype','corptype','<?php echo $userfield['corptype'];?>','0',corptype);</script><?php } ?></td>
	 	 </tr>
		 
		 
	  	 <tr> 
		    <td class="label">职业:</td>
		    <td><?php if($is_admin ) { ?>不可修改<?php } else { ?><script>getSelect('','occupation2','occupation2','<?php echo $userfield['occupation'];?>','0',occupation);</script><?php } ?></td>
	 	 </tr>
		 
		  <tr> 
		    <td class="label">兄弟姐妹:</td>
		    <td><?php if($is_admin ) { ?>不可修改<?php } else { ?><script>getSelect('','family','family','<?php echo $userfield['family'];?>','0',family);</script><?php } ?></td>
	 	 </tr>
		 

		 
	 	 <tr>
		    <td class="label">民族:</td>
		    <td><script>getSelect('','stock2','stock2','<?php echo $userfield['nation'];?>','0',stock);</script></td>
	  	</tr>
	   <tr>
		    <td class="label">籍贯:</td>
		    <td> <?php if($is_admin ) { ?>不可修改<?php } else { ?>
		    <script>getProvinceSelect66('','hometownProvince','hometownProvince','hometownCity','<?php echo $userfield['hometownprovince'];?>','10100000');</script>
			<script>getCitySelect66('','hometownCity','hometownCity','<?php echo $userfield['hometowncity'];?>','0');</script><?php } ?>
		    </td>
	   </tr>
	   <tr>
	    <td class="label">是否抽烟:</td>
	    <td>
	    <script>getSelect('','issmoking','issmoking','<?php echo $userfield['smoking'];?>','0',isSmoking);</script>
	    </td>
	  </tr>
	   <tr>
	    <td class="label">是否喝酒:</td>
	    <td>
	    <script>getSelect('','isdrinking','isdrinking','<?php echo $userfield['drinking'];?>','0',isDrinking);</script>
	    </td>
	  </tr>
	  <tr>
	    <td class="label">是否封锁:</td>
	    <td> <?php if($is_admin) { ?>不可修改<?php } else { ?>
	    <select name='is_lock'>
		   	<option value='1' <?php if($userinfo['is_lock']==1) { ?>selected="selected"<?php } ?>>未封锁</option>
			<option value='0' <?php if($userinfo['is_lock']==0) { ?>selected="selected"<?php } ?>>封锁</option>
		</select><?php } ?>
	    </td>
	  </tr>
	  <tr>
	    <td class="label">会员来源:</td>
	    <td> <?php if($is_admin ) { ?>不可修改<?php } else { ?>
		   <select name="usertype">
	  			<option value="1" <?php if($userinfo['usertype'] == 1) { ?>selected<?php } ?>>本站注册</option>
	  			<option value="2" <?php if($userinfo['usertype'] == 2) { ?>selected<?php } ?>>外站加入</option>
	  			<option value="3" <?php if($userinfo['usertype'] == 3) { ?>selected<?php } ?>>全权会员</option>
	  			<option value="4" <?php if($userinfo['usertype'] == 4) { ?>selected<?php } ?>>联盟会员</option>
				<option value="5" <?php if($userinfo['usertype'] == 5) { ?>selected<?php } ?>>内部会员</option>
	  		</select><?php } ?>
	    </td>
	  </tr>
	  
	  <tr>
		<td class="label">vip起始时间:</td>
		<td> <?php if($is_admin ) { ?>不可修改<?php } else { ?>
			<input type="text" name="bgtime" value="<?php if($userinfo['bgtime']) echo date('Y-m-d H:i:s',$userinfo['bgtime']);?>" onFocus="WdatePicker({startDate:'%y-%M-%D 00:00:00',dateFmt:'yyyy-MM-dd HH:mm:ss',alwaysUseStartDate:true})" <?php if(isset($group_front) && $group_front) { ?> readonly<?php } ?> />  <?php } ?>
		</td>
	  </tr>
	
	  <tr>
		<td class="label">vip到期时间:</td>
		<td> <?php if($is_admin ) { ?>不可修改<?php } else { ?>
			<input type="text" name="endtime" value="<?php if($userinfo['endtime']) echo date('Y-m-d H:i:s',$userinfo['endtime']);?>" onFocus="WdatePicker({startDate:'%y-%M-%D 00:00:00',dateFmt:'yyyy-MM-dd HH:mm:ss',alwaysUseStartDate:true})" <?php if(isset($group_front) && $group_front) { ?> readonly<?php } ?> />  <?php } ?>
		</td>
	  </tr>
	
	  
	</table>
	</td>
    
    <td style="vertical-align:top;">
      <table style="width:400px" >
        <tr>
          <td class="label">择偶年龄:</td><td><script>getSelect("",'spouse_age1','spouse_age1',"<?php echo $spouseinfo['age1'];?>",'21',age);</script>&nbsp;到&nbsp;<script>getSelect("",'spouse_age2','spouse_age2',"<?php echo $spouseinfo['age2'];?>",'45',age) ;</script></td>
        </tr>
       
        <tr>
          <td class="label">择偶身高:</td><td><script>getSelect('','spouse_minheight','spouse_minheight','<?php echo $spouseinfo['height1'];?>','-1',height);</script>&nbsp;&nbsp;到&nbsp;&nbsp;<script>getSelect('','spouse_maxheight','spouse_maxheight','<?php echo $spouseinfo['height2'];?>','0',height);</script></td>
        </tr>
        <tr>
          <td class="label">择偶体重:</td> <td><script>getSelect('','spouse_minweight','spouse_minweight','<?php echo $spouseinfo['weight1'];?>','-1',weight);</script>&nbsp;&nbsp;到&nbsp;&nbsp;<script>getSelect('','spouse_maxweight','spouse_maxweight','<?php echo $spouseinfo['weight2'];?>','0',weight);</script></td>
        </tr>
        <!--<tr>
          <td class="label">择偶体形:</td>
        </tr>
        --><!--<tr>
          <td class="label">择偶民族:</td>
        </tr>
        -->
        <!--<tr>
          <td class="label">择偶职业:</td>
        </tr>
        --><tr>
          <td class="label">择偶月薪:</td> <td> <script>getSelect('','spouse_salary','spouse_salary','<?php echo $spouseinfo['salary'];?>','0',salary1);</script></td>
        </tr>
		<tr>
			<td class="label">择偶地区</td>
			<td>
				<script type="text/javascript">getProvinceSelect66('','','spouse_hometownprovince','spouse_hometowncity',"<?php echo $spouseinfo['hometownprovince'];?>",'10100000');</script>省
				<script type="text/javascript">getCitySelect66('','spouse_hometowncity','spouse_hometowncity',"<?php echo $spouseinfo['hometowncity'];?>",'0')</script>市
			</td>
		</tr>
        <tr>
          <td class="label">择偶工作地址:</td>
           <td> 
                <script>getProvinceSelect66('','','spouse_workprovince','spouse_workCity',"<?php echo $spouseinfo['workprovince'];?>",'10100000');</script>
                省
                <script>getCitySelect66('','spouse_workCity','spouse_workCity',"<?php echo $spouseinfo['workcity'];?>",'0');</script>
                市
                </td>
        </tr>
        <tr>
          <td class="label">择偶是否抽烟:</td>
           <td>
            <script>getSelect('','spouse_smoking','spouse_smoking',"<?php echo $spouseinfo['smoking'];?>",'0',isSmoking);</script>
            </td>
        </tr>
        <!--
         <tr>
          <td class="label">择偶婚姻状况:</td>
        </tr>
         -->
         <tr>
          <td class="label">择偶教育程度:</td>
          <td><script>getSelect('','spouse_education','spouse_education','<?php echo $spouseinfo['education'];?>','0',education);</script></td>
        </tr>
  
         <tr>
          <td class="label">择偶是否有孩子:</td>
          <td><script>getSelect('','spouse_children','spouse_children','<?php echo $spouseinfo['children'];?>','0',children);</script></td>
        </tr>
         <tr>
          <td class="label">择偶是否要孩子:</td>
          <td><script>getSelect('','spouse_wantchildren','spouse_wantchildren','<?php echo $spouseinfo['wantchildren'];?>','0',wantchildren);</script></td>
        </tr>
         <tr>
          <td class="label">择偶的性格:</td>
          <td><script>getSelect('','spouse_character','spouse_character','<?php echo $spouseinfo['nature'];?>','0',nature);</script></td>
        </tr>
      </table>
    </td>
   </tr>
</table>
<div style="margin:0 auto;margin-left:500px;">
	<input name="uid" type="hidden" value="<?php echo $uid;?>" />
  	<input name="presex" type="hidden" value="<?php echo $userinfo['gender'];?>" />
    <?php if($is_admin==0) { ?><?php if($userinfo['s_cid']==40  && $userinfo['usertype']!=3 ) { ?><label><input type="checkbox" id="changeToAcq" value="noquanquan"   />更改为全权会员</label><?php } ?><?php } ?>
    <input type="submit" value="确定修改 " class="button" />
</div>
</div>
</form>
</body>
</html>

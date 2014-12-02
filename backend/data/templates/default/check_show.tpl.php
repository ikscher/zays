<link href="templates/css/general.css" rel="stylesheet" type="text/css" />
<link href="templates/css/main.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="templates/css/imgareaselect-animated.css" />  
<script type="text/javascript" src="templates/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="templates/js/jquery.imgareaselect.pack.js"></script>
<script>
function check_replay(){
	var submit=$("#submit_1").attr("value");
	if(submit=="回复"){$("#reply").css('display','block');$("#submit_1").attr("value",'提交'); return false;}
	if(submit=="提交"){if($("#content").val()==''){alert("请填写回复内容"); return false;}}
}

function preview(img, selection) {
    if (!selection.width || !selection.height)
        return;
    var old_w = $('#photo').attr('width');
	var old_h = $('#photo').attr('height');
    var scaleX = 171 / selection.width;
    var scaleY = 212 / selection.height;

    $('#preview img').css({
        width: Math.round(scaleX * old_w),
        height: Math.round(scaleY * old_h),
        marginLeft: -Math.round(scaleX * selection.x1),
        marginTop: -Math.round(scaleY * selection.y1)
    });

    $('.x_1').val(selection.x1);
    $('.y1').val(selection.y1);
    $('.x2').val(selection.x2);
    $('.y2').val(selection.y2);
    $('.w1').val(selection.width);
    $('.h').val(selection.height);    
}

function choose(mid){
	var src=$(".check_"+mid).attr("src");
	$("#photo").attr("src",src);
	$("#imagessrc").attr("value",src);
}
function upload(){
	var src=$("#photo").attr("src");
	var x1=$("#x_1").attr("value");
	var x2=$("#x2").attr("value");
	var y1=$("#y1").attr("value");
	var y2=$("#y2").attr("value");
	var width=$("#w1").attr("value");
	var height=$("#h").attr("value");
	var choose=$("#userphoto").attr('checked');
	var introduce=$("#incoduce").attr('checked');
	<?php if($_GET['h']=='photo') { ?>
		if(!choose&&!introduce){alert("没有选择要审核的内容"); return false;}
	<?php } ?>
	if(choose&&(src==''||x1==''||x2=='')){alert("请选择区域");} else check=true;
}
function checkupload(){
	return check;
}

function checknopass(){
     if(confirm("您确定不通过此照片吗？")){
             return true;
     }else return false;
}

	
function del(){
	if(confirm('你确定要删除吗？删除后将不可恢复')){return true;}else{return false;}
}

//旋转形象照


function rotate2(o,p){
	var img = document.getElementById(o);
	if(!img || !p) return false;
	var n = img.getAttribute('step');
	if(n== null) n=0;
	if(p=='left'){
		(n==3)? n=0:n++;
	}else if(p=='right'){
		(n==0)? n=3:n--;
	}
	img.setAttribute('step',n);
	//MSIE
	if(document.all) {
		img.style.filter = 'progid:DXImageTransform.Microsoft.BasicImage(rotation='+ n +')';
		//HACK FOR MSIE 8
		switch(n){
			case 0:
				img.parentNode.style.height = img.height;
				break;
			case 1:
				img.parentNode.style.height = img.width;
				break;
			case 2:
				img.parentNode.style.height = img.height;
				break;
			case 3:
				img.parentNode.style.height = img.width;
				break;
		}
	//DOM
	}else{
		var c = document.getElementById('canvas_'+o);
		if(c== null){
			img.style.visibility = 'hidden';
			img.style.position = 'absolute';
			c = document.createElement('canvas');
			c.setAttribute("id",'canvas_'+o);
			img.parentNode.appendChild(c);
		}
		var canvasContext = c.getContext('2d');
		switch(n) {
			default :
			case 0 :
				c.setAttribute('width', img.width);
				c.setAttribute('height', img.height);
				canvasContext.rotate(0 * Math.PI / 180);
				canvasContext.drawImage(img, 0, 0);
				break;
			case 1 :
				c.setAttribute('width', img.height);
				c.setAttribute('height', img.width);
				canvasContext.rotate(90 * Math.PI / 180);
				canvasContext.drawImage(img, 0, -img.height);
				break;
			case 2 :
				c.setAttribute('width', img.width);
				c.setAttribute('height', img.height);
				canvasContext.rotate(180 * Math.PI / 180);
				canvasContext.drawImage(img, -img.width, -img.height);
				break;
			case 3 :
				c.setAttribute('width', img.height);
				c.setAttribute('height', img.width);
				canvasContext.rotate(270 * Math.PI / 180);
				canvasContext.drawImage(img, -img.width, 0);
				break;
		};
	}
}
</script>

<script type="text/javascript"><!--
//跳转
var check=false;
$(document).ready(function (){
	$('#photo').imgAreaSelect({ aspectRatio: '<?php echo $bili;?>', handles: true,fadeSpeed: 200, onSelectChange: preview })

});

function rotate(){
	//rotate2('photo','left');

	var changeUrl = "check_ajax.php?n=imagick_rotate&pic_path=<?php echo $photo['mainimg'];?>&id=<?php echo $id;?>&uid=<?php echo $uid;?>&rand="+Math.random();
	$.get(changeUrl,function(str){
		location.href = "<?php echo $url2;?>&num="+str;
	})
	
	//location.href="index.php?action=check&h=imagick_rotate&pic_path=<?php echo $photo['mainimg'];?>&id=<?php echo $uid;?>&uid=<?php echo $uid;?>";
}
--></script>
<h1>
<span class="action-span1"><a href="###">真爱一生网 管理中心</a> </span><span id="search_id" class="action-span1"> - <?php echo $lei;?></span><span class="action-span"><a href="<?php if(!empty($url)) { ?><?php echo $url;?><?php } else { ?> javascript:history.go(-1)<?php } ?>">返回</a></span>
<div style="clear:both"></div>
</h1>
<div class="list-div" id="listDiv">
<?php if($_GET['h'] == 'photo') { ?>
<div>
	<div>
	<table cellspacing='1' cellpadding='3' id='list-table'>
	  <tr>
	    <th><?php if((isset($photo['syscheck'])?$photo['syscheck']:'')==1)echo "查看";else echo "验证";?>会员<?php echo $uid;?>的印象照：&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;年龄：<?php if($photo['birthyear'])echo date('Y')-$photo['birthyear'];else echo "无";?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;性别：<?php echo $photo['gender']==0 ? '男' : '女' ;?></th>
	  </tr>
	</table>
	<form id="first" action="index.php?action=check&h=<?php echo $_GET['h'];?>&type=submit&id=<?php echo $id;?>&uid=<?php echo $uid;?>&usersid=<?php echo $usersid;?>&page=<?php echo isset($page) ? $page : 0?>" method="POST" onSubmit="return checkupload();">
		<div style="width:730px; background:#fff; margin:0 auto; text-align:center;">
			<div>
            	<table>
                	<tr>
                    <td>
					<img <?php if($photo['mainimg']!='') echo "id=\"photo\"";?> class="check_" src="<?php echo IMG_SITE.$photo['mainimg']?>"  style="cursor:crosshair;"/>
					</td>
					<td>
                		<div id="preview" style="width: 171px; height: 212px; overflow: hidden;">
                		预览图：
                    <img src="<?php echo IMG_SITE.$photo['mainimg']?>"  style="width: 171px; height: 212px;"/>
               		 </div>
					 </td>
					 </tr>
				</table>
			</div>            
		</div>       
		<table style="width:60%" align="center">
		   <!-- <tr>
				 <td style="width:10%;"><b>独白:</b></td>
				 <td colspan="3"><textarea id="contentincoduce" name="contentincoduce" style="width:97%; height:50px; float:left;word-break:break-all;"><?php if($user_introduce['introduce']=='') echo strip_tags($user_introduce['introduce_check']);else echo strip_tags($user_introduce['introduce']);?></textarea></td>
			</tr>-->
			<tr>
				 <td style="width: 10%;"><b>X<sub>1</sub>:</b></td>
				 <td style="width: 30%;"><input type="text" class="x_1" value="" id="x_1" name="x1"/></td>
				 <td style="width: 20%;"><b>宽度:</b></td>
				 <td><input type="text" id="w1" class="w1" value="" name="width"/></td>
			</tr>
			<tr>
				 <td><b>Y<sub>1</sub>:</b></td>
				 <td><input type="text" value="" class="y1" id="y1" name="y1"/></td>
				 <td><b>高度:</b></td>
				 <td><input type="text" value="" class="h" id="h" name="height"/></td>
			</tr>
			<tr>
				 <td><b>X<sub>2</sub>:</b></td>
				 <td><input type="text" value="" class="x2" id="x2" name="x2"/></td>
				 <td colspan="2">
					形象照：<input type="checkbox" name="userphoto" id="userphoto" checked="checked" value="1" />&nbsp;&nbsp;&nbsp;&nbsp;
					<!--内心独白：<input id="incoduce" type="checkbox" name="incoduce" value="1" /><?php if($user_introduce['introduce']=='')echo "<span style=\"color:#ff0000\">(未审核)</span>";else echo "<span style=\"color: rgb(28, 153, 0);\">(已审核)</span>";?>-->
				 </td>
			</tr>
			<tr>
				 <td><b>Y<sub>2</sub>:</b></td>
				 <td><input type="text" value="" class="y2" id="y2" name="y2"/></td>
				 <td></td>
				 <td>
				 <input type="hidden" value="<?php echo $uid;?>" name="uid" /><input type="hidden" id="imagessrc" name="photoimage" value="../<?php echo $photo['mainimg'];?>"/>
				 <input class="submit" type="submit" onClick="check=true;" name="nopass" value="不通过"/>&nbsp;&nbsp;&nbsp;&nbsp;<input class="submit" onClick="upload();" name="pass" type="submit" value="提交"/>
				 <input type="hidden" name="pic_path" id="pic_path" value="../<?php echo $photo['mainimg'];?>"  />
				 <input type="button" value="旋转图片" onClick="rotate();">
				 </td>
			</tr>
	  </table>
	</form>
	</div>
</div>
<?php } ?>

<?php if($_GET['h'] == 'monolog') { ?>
<table cellspacing='1' cellpadding='3' id='list-table'>
  <tr>
    <th><?php if($photo['syscheck']==1)echo "查看";else echo "验证";?>会员<?php echo $uid;?>的内心独白：&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;年龄：<?php if($photo['birthyear'])echo date('Y')-$photo['birthyear'];else echo "无";?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;性别：<?php echo $photo['gender']==0 ? '男' : '女' ;?></th>
  </tr>
</table>
<form action="index.php?action=check&h=monolog&type=submit" method="POST" style="margin:auto; width:510px;height:200px;border:#cccccc solid 1px;">
<table>
   <tbody>
    <tr>
    <td style="width:10%;"><b>内容:</b></td>
     <td colspan="3"><textarea id="introduce" name="introduce" style="width:97%; height:130px; float:left;word-break:break-all;"><?php if($monolog['introduce']=='') echo $monolog['introduce_check'];else echo $monolog['introduce'];?></textarea></td>
    </tr>
    <tr><input type="hidden" value="<?php echo $id;?>" name="id" /><input type="hidden" value="<?php echo $uid;?>" name="uid" />
     <td colspan="4">&nbsp;&nbsp;&nbsp;&nbsp;<input name="pass" class="submit" type="submit" value="审核"/></td>
    </tr>
   </tbody>
  </table>
  </form>
<?php } ?> 

<?php if($_GET['h'] == 'comment') { ?>
<table cellspacing='1' cellpadding='3' id='list-table'>
  <tr>
    <th>审核会员<?php echo $comment['cuid'];?>对<?php echo $uid;?>的评价：&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;年龄：<?php if($comment['birthyear'])echo date('Y')-$comment['birthyear'];else echo "无";?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;性别：<?php echo $comment['gender']==0 ? '男' : '女' ;?></th>
  </tr>
</table>
<form action="index.php?action=check&h=comment&type=submit" method="POST" style="margin:auto; width:510px;height:200px;border:#cccccc solid 1px;">
<table>
   <tbody>
    <tr>
    <td style="width:10%;"><b>内容:</b></td>
     <td colspan="3"><textarea id="content" name="content" style="width:97%; height:130px; float:left;word-break:break-all;"><?php if($comment['content']!='') echo $comment['content'];?></textarea></td>
    </tr>
    <tr><input type="hidden" value="<?php echo $id;?>" name="id" /><input type="hidden" value="<?php echo $uid;?>" name="uid" />
     <td colspan="4">&nbsp;&nbsp;&nbsp;&nbsp;<input name="pass" class="submit" type="submit" value="通过"/></td>
    </tr>
   </tbody>
  </table>
  </form>
<?php } ?> 

<?php if($_GET['h'] == 'image') { ?>
<table cellspacing='1' cellpadding='3' id='list-table'>
  <tr>
    <th><?php if($image['syscheck']==1)echo "查看";else echo "验证";?>会员<?php echo $uid;?>的相册图片：&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;年龄：<?php if($image['birthyear'])echo date('Y')-$image['birthyear'];else echo "无";?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;性别：<?php echo $image['gender']==0 ? '男' : '女' ;?></th>
  </tr>
</table>
<div style="width:171px;height:212px;position:absolute;right:30px;top:110px;text-align:left">
<!--  <img src="../<?php echo $image['mainimg'];?>" width="171"/>-->
<img src="<?php echo IMG_SITE.$image['mainimg']?>" width="171"/>
</div>
<form action="index.php?action=check&h=image&type=submit&usersid=<?php echo $usersid;?>&page=<?php echo $page;?>" method="POST">
<table>
   <tbody>
   <tr <?php if($image['syscheck']==1)echo "style=\"display:none\"";?>><input type="hidden" value="<?php echo $id;?>" name="id" /><input type="hidden" value="<?php echo $uid;?>" name="uid" />
     <td align="center"><input class="submit" type="submit" name="nopass" value="不通过" onClick="return checknopass();"/>&nbsp;&nbsp;&nbsp;&nbsp;<input name="pass" class="submit" type="submit" value="通过"/></td>
    </tr>
    <tr>
    <!--  <td align="center"><img src="../<?php echo $image['imgurl'];?>" width="500"/></td>-->
    <td align="center"><img src="<?php echo IMG_SITE.$image['imgurl']?>" width="500"/></td>
    </tr>
   </tbody>
  </table><input type="hidden" name="imagesname" value="<?php echo $image['pic_name'];?>"/><input type="hidden" name="pic_date" value="<?php echo $image['pic_date'];?>"/>
  </form>
<?php } ?>

<?php if($_GET['h'] == 'story') { ?>
<table cellspacing='1' cellpadding='3' id='list-table'>
  <tr>
    <th><?php if($story['syscheck']==1)echo "查看";else echo "验证";?>会员<?php echo $story['uid'];?>的成功故事：&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;年龄：<?php if($story['birthyear'])echo date('Y')-$story['birthyear'];else echo "无";?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;性别：<?php echo $story['gender']==0 ? '男' : '女' ;?></th>
  </tr>
</table>
<form action="index.php?action=check&h=story&type=submit" method="POST" style="margin:auto; width:510px;border:#cccccc solid 1px;">
<table>
   <tbody>
    <tr>
     <td style="width: 20%;"><b>恋爱:</b></td>
     <td style="width: 25%;"><input type="text" value="<?php echo $story['name1'];?>" id="x1" name="name1" /></td>
     <td style="width: 20%;"><b>恋爱另一方:</b></td>
     <td><input type="text" value="<?php echo $story['name2'];?>" name="name2" /></td>
    </tr>
    <tr>
     <td style="width: 20%;"><b>故事标题:</b></td>
     <td style="width: 25%;"><input type="text" value="<?php echo $story['title'];?>" id="x1" name="title" /></td>
     <td style="width: 20%;"><b>甜蜜进程:</b></td>
     <td><select name="state"><option value="0">请选择</option><option value="1" <?php if($story['state']=='1') { ?>selected="selected"<?php } ?>>恋爱</option><option value="2" <?php if($story['state']=='2') { ?>selected="selected"<?php } ?>>订婚</option><option value="3" <?php if($story['state']=='3') { ?> selected="selected"<?php } ?>>结婚</option> </select></td>
    </tr>
    <tr>
    <td style="width:20%;"><b>故事内容:</b></td>
     <td colspan="3"><textarea  name="content" style="width:400px; height:100px; float:left;overflow-y:scroll; border:#cccccc solid 1px;word-break:break-all;"><?php echo $story['content'];?></textarea></td>
    </tr>
    <tr <?php if($story['recommand']==1)echo "style=\"display:none\"";?>>
		<td colspan="4">
		<a href="#" id="recommand" onClick="recommand(<?php echo $story['sid'];?>)">推荐到首页</a>
    </tr>
	
    <tr ><input type="hidden" value="<?php echo $id;?>" name="id" /><input type="hidden" value="<?php echo $uid;?>" name="uid" />
	<?php if($story['syscheck']==1) { ?>
	    <input type="hidden" name="Save" value="1" />
	    <td colspan="4"><input class="submit" type="submit"  value="保存"/>
	<?php } else { ?>
        <td colspan="4"><input class="submit" type="submit" name="Pass" value="通过"/>&nbsp;&nbsp;&nbsp;&nbsp;<input name="NoPass" class="submit" type="submit" value="不通过"/></td>
	 <?php } ?>
    </tr>
   </tbody>
  </table>
  </form>
  <script type="text/javascript">
		function recommand(sid_val){
			$.get("ajax.php",{n:'storyrecommand',sid:sid_val},function(str){
				$("#recommand").html(str);
			})
		}
	</script>
<?php } ?>

<?php if($_GET['h'] == 'storyfirst') { ?>
<div id="main">
	<img <?php if($storyfirst['img']!='') echo "id=\"photo\"";?> class="check_<?php echo $images[0]['mid'];?>" src="<?php echo IMG_SITE?>data/upload/images/story/<?php echo $storyfirst['img'];?>"  style="cursor:crosshair;"/>
</div>
<table cellspacing='1' cellpadding='3' id='list-table'>
  <tr>
    <th><?php if($storyfirst['syscheck']==1)echo "查看";else echo "验证";?>会员<?php echo $uid;?>的成功故事封面照：&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;年龄：<?php if($storyfirst['birthyear'])echo date('Y')-$storyfirst['birthyear'];else echo "无";?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;性别：<?php echo $storyfirst['gender']==0 ? '男' : '女' ;?></th>
  </tr>
</table>
<form id="first" action="index.php?action=check&h=storyfirst&type=submit&id=<?php echo $id;?>" method="POST" onSubmit="return checkupload();" style="margin:auto; width:510px;height:150px;border:#cccccc solid 1px;<?php if($storyfirst['syscheck']==1)echo "display:none";?>">
<table>
   <tbody>
    <tr>
     <td style="width: 10%;"><b>X<sub>1</sub>:</b></td>
     <td style="width: 30%;"><input type="text" class="x_1" value="" id="x_1" name="x1"/></td>
     <td style="width: 20%;"><b style="color:#a6d0e7">宽度:</b></td>
     <td><input type="text" id="w1" class="w1" value="" name="width"/></td>
    </tr>
    <tr>
     <td><b>Y<sub>1</sub>:</b></td>
     <td><input type="text" value="" class="y1" id="y1" name="y1"/></td>
     <td><b>高度:</b></td>
     <td><input type="text" value="" class="h" id="h" name="height"/></td>
    </tr>
    <tr>
     <td><b>X<sub>2</sub>:</b></td>
     <td><input type="text" value="" class="x2" id="x2" name="x2"/></td>
     <td/>
     <td/>
    </tr>
    <tr>
     <td><b>Y<sub>2</sub>:</b></td>
     <td><input type="text" value="" class="y2" id="y2" name="y2"/></td>
     <td/>
     <td><input type="hidden" value="<?php echo $uid;?>" name="uid" /><input type="hidden" id="imagessrc" name="storyfirstsrc" value="../data/upload/images/story/<?php echo $storyfirst['img'];?>"/><input type="hidden" value="<?php echo $storyfirst['sid'];?>" name="storyid" />
  <input class="submit" type="submit" onClick="check=true;" name="nopass" value="不通过"/>&nbsp;&nbsp;&nbsp;&nbsp;<input class="submit" onClick="upload();" name="pass" type="submit" value="提交"/></td>
    </tr>
   </tbody>
  </table>
  </form>
<?php } ?>

<?php if($_GET['h'] == 'storyimage') { ?>
<table cellspacing='1' cellpadding='3' id='list-table'>
  <tr>
    <th><?php if($storyimage['syscheck']==1)echo "查看";else echo "验证";?>会员<?php echo $storyimage['uid'];?>的成功故事图片：&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;年龄：<?php if($storyimage['birthyear'])echo date('Y')-$storyimage['birthyear'];else echo "无";?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;性别：<?php echo $storyimage['gender']==0 ? '男' : '女' ;?></th>
  </tr>
</table>
<form action="index.php?action=check&h=storyimage&type=submit" method="POST" style="margin:auto; width:510px;border:#cccccc solid 1px;">
<table>
   <tbody>
   <tr <?php if($storyimage['syscheck']==1)echo "style=\"display:none\"";?>><input type="hidden" value="<?php echo $id;?>" name="id" /><input type="hidden" value="<?php echo $uid;?>" name="uid" />
     <td><input class="submit" type="submit" name="nopass" value="不通过"/>&nbsp;&nbsp;&nbsp;&nbsp;<input name="pass" class="submit" type="submit" value="通过"/></td>
    </tr>
    <tr>
    <td><img src="<?php echo IMG_SITE?>data/upload/images/story/<?php echo $storyimage['img'];?>" width="500"/></td>
    </tr>
   </tbody>
  </table><input type="hidden" name="storyimagessrc" value="../data/upload/images/story/<?php echo $storyimage['img'];?>"/>
  </form>
<?php } ?>

<?php if($_GET['h'] == 'paper') { ?>
<table cellspacing='1' cellpadding='3' id='list-table'>
  <tr>
    <th><?php if($check==0)echo "查看";else echo "验证";?>会员<?php echo $uid;?>的<?php if($mintype=="identity") echo "身份证明"; if($mintype=="marriage") echo "婚育证明"; if($mintype=="education") echo "学历证明"; if($mintype=="occupation") echo "工作证明"; if($mintype=="salary") echo "工资证明"; if($mintype=="house") echo "房产证明"; if($mintype=="video") echo "视频认证";?>：&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;年龄：<?php if($paper['birthyear'])echo date('Y')-$paper['birthyear'];else echo "无";?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;性别：<?php echo $paper['gender']==0 ? '男' : '女' ;?></th>
  </tr>
</table>
<form action="index.php?action=check&h=paper&type=submit&mintype=<?php echo $mintype;?>&uid=<?php echo $uid;?>" method="POST" style="margin:auto; width:510px;border:#cccccc solid 1px;">
<table>
   <tbody>
   <tr><input type="hidden" value="<?php echo $id;?>" name="id" /><input type="hidden" value="<?php echo $uid;?>" name="uid" />
     <td align="center"><input class="submit" type="submit" name="nopass" value="不通过"/>&nbsp;&nbsp;&nbsp;&nbsp;<input name="pass" class="submit" type="submit" value="通过" <?php if($check==0)echo "style=\"display:none;\"";?>/></td>
    </tr>
    <tr>
    <td align="center"><img src="../<?php echo $paper['mintype'];?>" width="500"/></td>
    </tr>
   </tbody>
  </table><input type="hidden" name="paperimagessrc" value="../<?php echo $paper['mintype'];?>"/>
  </form>

<?php } ?>

<?php if($_GET['h'] == 'feedback') { ?>
<table cellspacing='1' cellpadding='3' id='list-table'>
  <tr>
    <th>查看<?php echo $serArr[$showadvice['stat2']]?>意见反馈：<?php echo date("Y-m-d H:i:s",$showadvice['submitdate'])?></th>
  </tr>
</table>
<form action="index.php?action=check&h=feedback&type=submit&gid=<?php echo $gid;?>&uid=<?php echo $uid;?>" method="post" style="margin:auto; width:510px;border:#cccccc solid 1px;">
<table>
   <tbody>
    <tr>
     <td style="width: 20%;"><b>会员id:</b></td>
     <td style="width: 25%;"><input type="text" value="<?php echo $showadvice['uid'];?>" id="x1" name="x1" disabled="disabled"/></td>
     <td style="width: 20%;"><b>反馈态度:</b></td>
     <td><input type="text" value="<?php echo $attArr[$showadvice['stat1']]?>" name="width" readonly="readonly" /></td>
    </tr>
    <?php if($showadvice['stat2'] == 2) { ?>
    <tr>
     <td style="width: 20%;"><b>客服:</b></td>
     <td style="width: 25%;"><input type="text" value="<?php echo $kefu_arr[$showadvice['sid']]?>" id="x1" name="x1" disabled="disabled"/></td>
     <td style="width: 20%;"><b>评分:</b></td>
     <td><?php echo $fraction_arr[$showadvice['fraction']];?></td>
    </tr>
    <?php } ?>
    <tr>
    <td style="width:20%;"><b>反馈内容:</b></td>
     <td colspan="3"><textarea style="width:97%; height:100px; float:left;*+word-break:break-all;overflow-y:scroll;"><?php echo $showadvice['content'];?></textarea></td>
    </tr>
  </tbody>
</table>
<?php if(in_array($groupid,$GLOBALS['admin_service_arr']) || in_array($groupid, $GLOBALS['admin_all_group']) || in_array($groupid,$GLOBALS['admin_service_after']) ) { ?>
<table width="510px" id="reply" style="display:none;">
	<tr>
    <td width="100"><b>回复内容:</b></td>
     <td width="400"><textarea style="width:97%; height:100px; float:left;" name="content" id="content"></textarea></td>
    </tr>
</table>
<table>
    <tr><input type="hidden" value="<?php echo $showadvice['uid'];?>" name="uid" />
    <?php if($adminid != $showadvice['sid'] || in_array($groupid, $GLOBALS['admin_service_arr']) || in_array($groupid, $GLOBALS['admin_all_group']) || in_array($groupid,$GLOBALS['admin_service_after'])) { ?>
    <td><input id="nopass" name="nopass" class="submit" type="submit" onClick="return del();" value="删除"/>&nbsp;&nbsp;&nbsp;&nbsp;<input id="submit_1" name="pass" class="submit" type="submit" onClick="return check_replay();" value="回复"/></td>
     <?php } ?>
    </tr>
</table>
<?php } ?>
  </form>
<?php } ?>

<?php if($_GET['h'] == 'action') { ?>
<table cellspacing='1' cellpadding='3' id='list-table'>
  <tr>
    <td><?php if($photo['syscheck']==1)echo "查看";else echo "验证";?>会员<?php echo $uid;?>的印象照：&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;年龄：<?php if($photo['birthyear'])echo date('Y')-$photo['birthyear'];else echo "无";?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;性别：<?php echo $photo['gender']==0 ? '男' : '女' ;?></td>
  </tr>
</table>
<?php } ?>  

<?php if($_GET['h'] == 'complaint_box') { ?>
<script type="text/javascript">
	var forground = new Array('其他', '首页', '我的红娘', '红娘寻友', '资料设置', '爱情测试', '诚信认证', 'E见钟情', '视频认证', '会员升级');
	var background = new Array('其他', '网站管理', '测试管理', '我的用户', '全部会员', '会员升级', '互动管理', '报表', '信息审核', '系统管理', '其他管理');
</script>
<script type="text/javascript">
	function modulechange(module) {
		if(module == 1) {
			var mod = forground;
		} else if (module == 2) {
			var mod = background;
		}
		html = '';
		if(mod) {
			for(var i = 0; i < mod.length; i++) {
				html += "<option value="+ i +">"+ mod[i] +"</option>";
			}
			$("select[name='module']").html(html);
		} else {
			$("select[name='module']").html("<option value='-1'>-----</option>");
		}
		
	}
</script>
<table cellspacing='1' cellpadding='3' id='list-table'>
  <tr>
    <th>
    	<?php if($_GET['cid']) { ?>
        查看意见反馈：<?php echo date("Y-m-d H:i:s",$complaint['submittime'])?>
        <?php } else { ?>
        提交意见反馈
        <?php } ?>
    </th>
  </tr>
</table>
<form action="index.php?action=other&h=complaint_box&type=submit" method="POST" style="margin:auto; width:510px;border:#cccccc solid 1px;">
<table>
   <tbody>
    <tr>
         <td style="width: 20%;"><b>客服:</b>
        <?php if($_GET['cid']) { ?>
        <?php $user = $kefu_arr[$complaint['uid']] ? $kefu_arr[$complaint['uid']] : $complaint['uid'];
        echo $user;?>
        <?php } else { ?>
        <?php $user = $kefu_arr[$adminid] ? $kefu_arr[$adminid] : $adminid;
        echo $user;?>
        <?php } ?>
         </td></td>
         <td><b>模块:</b>
        <?php if($_GET['cid']) { ?>
            <?php if($complaint['areaid'] != 0) { ?>
                <?php echo $areaid_arr[$complaint['areaid']];?>&nbsp;&nbsp;
                <?php $mod = $complaint['areaid'] == 1 ? $foreground : $background;?>
                <?php echo $mod[$complaint['module']];?>
            <?php } else { ?>
                <?php echo $areaid_arr[$complaint['areaid']];?>
            <?php } ?>
        <?php } else { ?>
         &nbsp;反馈区域：
        <select name="areaid" onchange="modulechange(this.value)">
            <?php foreach((array)$areaid_arr as $key=>$val) {?>
                <option value="<?php echo $key;?>"><?php echo $val;?></option>
            <?php }?>
        </select>
        &nbsp;反馈模块：<select name="module">
            <option value="-1">-----</option>
        </select>
        <?php } ?>
        </td>
    </tr>
    <tr>
    	<td style="width:20%;"><b>反馈内容:</b></td>
     	<td><textarea name="content" style="width:97%; height:100px; float:left;*+word-break:break-all;overflow-y:scroll;" <?php if($adminid != $complaint['uid'] && $_GET['cid']) { ?> readonly="readonly"<?php } ?>><?php echo $complaint['content'];?></textarea></td>
    </tr>
    <?php if($_GET['cid']) { ?>
    <tr id="reply">
		<td style="width:20%;"><b>回复内容:</b></td>
		<td>
        <?php if($groupname == '系统管理员权限') { ?>
        <textarea style="width:97%; height:100px; float:left;" name="replay" id="replay"><?php echo $complaint['replay'];?></textarea>
        <?php } else { ?>
        	<?php echo $complaint['replay'];?>
        <?php } ?>
        </td>
    </tr>
    <tr id="reply">
		<td style="width:20%;"><b>是否采纳:</b></td>
		<td>
        	<?php if($groupname == '系统管理员权限') { ?>
            <select name="accept">
                <?php foreach((array)$accept_arr as $key=>$val) {?>
                    <option value="<?php echo $key;?>" <?php if($key == $complaint['accept']) { ?>selected='selected'<?php } ?>><?php echo $val;?></option>
                <?php }?>
            </select>
            <?php } else { ?>
                <?php echo $accept_arr[$complaint['accept']]?>
            <?php } ?>
        </td>
    </tr>
    <tr id="reply">
		<td style="width:20%;"><b>处理状态:</b></td>
		<td>
        	<?php if($groupname == '系统管理员权限') { ?>
            <select name="status">
                <?php foreach((array)$status_arr as $key=>$val) {?>
                    <option value="<?php echo $key;?>" <?php if($key == $complaint['status']) { ?>selected='selected'<?php } ?>><?php echo $val;?></option>
                <?php }?>
            </select>
            <?php } else { ?>
                <?php echo $status_arr[$complaint['status']]?>
            <?php } ?>
        </td>
    </tr>
    <?php } ?>
    <tr>
     	<td colspan="2">
        	<?php if($adminid == $complaint['uid'] && $_GET['cid']) { ?>
            	<input type="button" id="cdel" value="删除"/>
            <?php } ?>
        	&nbsp;&nbsp;&nbsp;&nbsp;
            <?php if($groupname == '系统管理员权限' && $_GET['cid']) { ?>
            <input type="hidden" name="cid" value="<?php echo $complaint['cid'];?>" />
            <input class="complaint" name="complaint" type="submit" value="回复"/>
            <?php } elseif ($adminid == $complaint['uid'] || !$_GET['cid']) { ?>
            	<?php if($_GET['cid']) { ?>
                	 <input type="hidden" name="cid" value="<?php echo $complaint['cid'];?>" />
                <?php } ?>
            <input class="complaint" name="complaint" type="submit" value="提交" />
            <?php } ?>
            <?php if($_GET['cid']) { ?>
            <input type="hidden" name="uid" value="<?php echo $complaint['uid'];?>" />
            <?php } ?>
        </td>
        <script type="text/javascript">
			$(".complaint").click(function() {
				if($("[name='content']").val() == '') {
					alert("内容不能为空");
					return false;
				}
				<?php if($_GET['cid'] && $groupname == '系统管理员权限') { ?>
					if($("[name='replay']").val() == '') {
						alert("回复内容不能为空");
						return false;
					}
					if($("[name='accept']").val() == 0) {
						alert("请选择是否采纳");
						return false;
					}
				<?php } ?>
			});
			$("#cdel").click(function() {
				if(confirm('确定要删除吗？')) {
					location.href = "index.php?action=other&h=complaint_box&type=del&cid=<?php echo $_GET['cid'];?>";
				}
			});
		</script>
    </tr>
   </tbody>
  </table>
</form>
<?php } ?>
</div>


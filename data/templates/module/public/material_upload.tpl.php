<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>上传照片——编辑我的相册——我的资料——真爱一生网</title>
<?php include MooTemplate('system/js_css','public'); ?>
<link rel="stylesheet" type="text/css" href="public/<?php echo $GLOBALS['style_name'];?>/css/font.css" />
<link rel="stylesheet" type="text/css" href="module/material/templates/<?php echo $GLOBALS['style_name'];?>/material.css" />
</head>

<body>
<?php include MooTemplate('system/header','public'); ?>

<!--头部结束-->
 
 <div class="clear"></div>
 
 <div class="main">
   <div class="m_left">
       <h3>我的联系情况</h3>
    <div class="m_box">
         <div class="mpic">
         <div class="mpic1">
		   <p>
         		<?php if($user['images_ischeck']=='1'&& $user['mainimg']) { ?>
         			  <img src="
                      <?php if(MooGetphoto($user['uid'],'com')) { ?>
                      	<?php echo MooGetphoto($user['uid'],'com')?>
                      <?php } elseif (MooGetphoto($user['uid'],'medium')) { ?>
                          <?php echo MooGetphoto($user['uid'],'medium')?>
                      <?php } else { ?>
                        <?php if($user['gender'] == '1') { ?>
                            public/system/images/woman.gif
                        <?php } else { ?>
                            public/system/images/man.gif
                        <?php } ?>
                      <?php } ?>
                      " onload="javascript:DrawImage(this,110,138)"  />
       			<?php } elseif ($user['mainimg']) { ?>
            		<?php if($user['gender'] == '1') { ?>
         				<img src="public/system/images/woman.gif"  />
         			<?php } else { ?>
          				<img src="public/system/images/man.gif"/>
          			<?php } ?>
            	
                <?php } else { ?>
         			<?php if($user['gender'] == '1') { ?>
         				<img src="public/system/images/service_nopic_woman.gif"  />
         			<?php } else { ?>
          				<img src="public/system/images/service_nopic_man.gif" />
          			<?php } ?>
         		<?php } ?>
                </p></div>
                    </div>
         <p class="d22224" align="center"><?php echo $user_arr['nickname']?$user_arr['nickname']:'ID '.$user_arr['uid']?></p>
         <p class="d22224" align="center"><?php echo $user_arr['username'];?></p>
         <p class="f68" align="center"><?php if($user_rank_id!=1) { ?>[普通会员]<?php } else { ?>[VIP会员]<?php } ?></p>
         <ul>
            <li><?php if($usercer['telphone']) { ?><a href="index.php?n=myaccount&h=telphone" class="ren1">已通过号码验证</a><?php } else { ?><a href="index.php?n=myaccount&h=telphone" class="ren1over">未通过号码验证</a><?php } ?></li>
            <li><?php if($usercer['sms']) { ?><a href="index.php?n=myaccount&h=smsindex" class="ren2">已通过身份认证</a> <?php } else { ?><a href="index.php?n=myaccount&h=smsindex" class="ren2over">未通过身份认证</a><?php } ?></li>
            <li><a href="index.php?n=myaccount&amp;h=emailindex" class=" <?php if($usercer['email'] == 'yes') { ?>ren3<?php } else { ?> ren3over<?php } ?>"><?php if($usercer['email'] == 'yes') { ?>已<?php } else { ?> 未<?php } ?>通过邮箱认证</a></li>
            <li><a href="index.php?n=myaccount&amp;h=videoindex" class="	<?php if($usercer['video_check']==3) { ?>ren4 <?php } else { ?>ren4over<?php } ?>"><?php if($usercer['video_check']==3) { ?>已<?php } else { ?>未<?php } ?>通过视频认证 </a></li>

         </ul>
         <div class="faith">
        <?php $n = 0?>
        <?php if($usercer['identity_check']==3) { ?>
        <?php $n = $n + 2?>
        <?php } ?>
        <?php if($usercer['marriage_check']==3) { ?>
        <?php $n = $n + 2?>
        <?php } ?>
        <?php if($usercer['education_check']==3) { ?>
        <?php $n ++?>
        <?php } ?>
        <?php if($usercer['occupation_check']==3) { ?>
        <?php $n ++?>
        <?php } ?>
        <?php if($usercer['salary_check']==3) { ?>
        <?php $n ++?>
        <?php } ?>
        <?php if($usercer['house_check']==3) { ?>
        <?php $n ++?>
        <?php } ?>
        <?php if($usercer['video_check'] == 3) { ?>
        <?php $n ++?>
        <?php } ?>
        <?php if($usercer['email'] == 'yes') { ?>
        <?php $n ++?>
        <?php } ?>
        <?php if($usercer['sms']) { ?>
        <?php $n = $n + 2?>
        <?php } ?>
        <?php if($usercer['telphone'] != '') { ?>
        <?php $n = $n + 2?>
        <?php } ?>
		<?php $n1 = (int)($n / 2)?>
		<?php $n2 = $n % 2?>
        <div style="float:left"> 诚信度：</div>
         <?php for($a=0;$a < $n1;$a++) {?>
         <img src="module/material/templates/default/images/xing1.gif"  />
  	     <?php } ?>
         <?php if($n2) { ?>
             <img src="module/material/templates/default/images/halfxing.gif"  />
             <?php for($b=6-$a;$b>0;$b--) {?>
             <img src="module/material/templates/default/images/xing2.gif"  />
             <?php } ?>
         <?php } else { ?>
             <?php for($b=7-$a;$b>0;$b--) {?>
             <img src="module/material/templates/default/images/xing2.gif"  />
             <?php } ?>
         <?php } ?>
         <div class="clear"></div>
         </div>
    </div>
   </div>
   <!--左边结束-->
   <div class="ma_right">
   <h3><span><a href="index.php?n=material">我的资料设置>></a></span>上传照片</h3>
      <div class="ma_rightbox">
        <div class="uptext">
          现在可以上传照片了，最多可上传14张，您已经上传了2张，还可上传12张
         </div>    
    <h4>上传照片 </h4>
	    <div class="uphoto">	
					<p class="d22224"><b>温馨提示</b>：点击“浏览”从您的计算机中选择您想要上传的照片。</p>
                    <p class="d22224">（照片最大不超过400K,最小不低于10K）提交按钮 </p>
                    <p class="input">
                     <form action="index.php?n=material&h=upload" method="post" enctype="multipart/form-data" name="uppic" id="uppic" onsubmit="return checkPicForm();">
           
                     <input type="hidden" name="isupload" value="1" />
    		      <input type="hidden" name="isimage" value="0" />
                     <input  name="userfile" type="file" />
                
                      <input type="submit" class="save_btn" value="上传照片" />
             
                      </form></p>
                     <p>  ·照片必须为BMP，JPG，PNG或GIF格式，并且不可小于10K和超过4M。</p>
                     <p>·上传的照片，客服将在24小时内进行审核，通过后照片才可以显示。请耐心等待！</p> 
        </div>
    <h4>上传照片注意事项 </h4>
          <ul class="upnote">
             <li>
             <img src="module/material/templates/default/images/note01.gif"/> 
             <p class="d22224"><b>高质量的照片</b></p>
             <p>真爱一生网要求会员从数码相机或其他硬盘中，直接上传清晰、无变形的高质量照片。</p>
             </li>
             <li>
             <img src="module/material/templates/default/images/note02.gif"/> 
             <p class="d22224"><b>照片数量</b></p>
             <p>照片的数量会提高您征婚的成功率，升级会员最多可以上传更多的照片</p>
             </li>
             <li>
             <img src="module/material/templates/default/images/note03.gif"/> 
             <p class="d22224"><b>照片规格</b></p>
             <p>建议您上传的照片要大于100KB，理想的照片尺寸为300×400像素。</p>
             </li>
          
          </ul>
          
          
              
          <div class="clear"></div>  
      </div>
   </div>
 </div>
 <div class="clear"></div>



<?php include MooTemplate('system/footer','public'); ?>

</body>
</html>
<script language="javascript">
function checkPicForm(){
	pic = document.uppic.userfile.value;
	if(pic == ''){
		alert('请选择要上传的图片');
		return false;
	}
	return true;
}
</script>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>我的真爱一生--我的相册——真爱一生网</title>
        <?php include MooTemplate('system/js_css','public'); ?>
        <link href="module/material/templates/default/left.css" rel="stylesheet" type="text/css" />
        <link href="module/material/templates/default/material.css" rel="stylesheet" type="text/css" />
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
    </head>
    <script src="module/search/templates/default/js/syscode.js?v=1" type="text/javascript"></script>
    <body>
        <?php include MooTemplate('system/header','public'); ?>
<div class="main">
            <div class="content">
                <p class="c-title"><span class="f-000">真爱一生首页&nbsp;&gt;&gt;&nbsp;我的真爱一生&nbsp;&gt;&gt;&nbsp;我的相册</span><a href="#"></a></p>
                <!--content-lift end-->
                <?php include MooTemplate('system/simp_left','public'); ?>
                <!--=====左边结束===-->
                <div class="content-right">
                    <div class="c-right-content">
                        <div class="r-center-tilte">
                            <span class="right-title f-ed0a91">我的相册</span>
                        </div>
                        <div class="r-center-ccontent">
                            <p class="photo-up-title">
                                <?php if($user1['mainimg']) { ?>
                                <?php if($total < $maxuploadnum) { ?>
                                您好，您已有 <span class="f-b-d73c90"><?php echo $total;?></span> 张照片，您还可以上传 <span class="f-b-d73c90"><?php echo $leave_num;?></span> 张照片！
                                <?php } else { ?>
                                您好，您已有<span class="f-b-d73c90"> <?php echo $maxuploadnum;?> </span>张照片了，最多可上传<span class="f-b-d73c90"> <?php echo $maxuploadnum;?> </span>张，现在不可以再上传了！
                                <?php } ?>
                                <?php } else { ?>
                                您好，您还没有形象照，请上传形像照！
                                <?php } ?>
                            </p>
                            <h4>我的形象照</h4>
                            <div class="up-photo">
                                <div class="upimg">
                                    <?php if($user1['images_ischeck'] == 2) { ?>
                                    <div class="post_s_div">照片正在审核中</div>
                                    <?php } ?>
                                    <div class="pic10">
                                        <p><?php if($user1['mainimg']) { ?>
                                            <a href="./index.php?n=material&h=del&mid=<?php echo $user1['uid'];?>&isimage=1">
                                                <img src="<?php echo $thumb_2;?>" /></a>
                                            <?php } else { ?>
                                            <?php if($user1['gender'] == '1') { ?>
                                            <img src="public/system/images/service_nopic_woman.gif" />
                                            <?php } else { ?>
                                            <img src="public/system/images/service_nopic_man.gif" />
                                            <?php } ?>
                                            <?php } ?></p>
                                    </div>     
                                    <p style="padding-top:15px;">
                                        <a class="delImage" style="cursor:pointer;" data-gender="<?php echo $user_arr['gender'];?>"><?php if($thumb_2) { ?>取消形象照<?php } ?></a>
                                    </p>
                                </div>
                                <div class="up-photo-right">
                                    <p><strong>温馨提示：</strong>点击“浏览”从您的计算机中选择您想要上传的照片。</p>
                                    <p class="f-ed0a91">（照片最大不超过1000KB,最小不低于20KB）提交按钮 </p>
                                    <p style="padding:25px 0;">
                                        <?php if(!$user1['mainimg']) { ?>
                                        <form action="index.php?n=material&h=upload" method="POST" enctype="multipart/form-data" name="uppic" id="uppic" onsubmit="return checkPicForm();">
                                            <input type="hidden" name="isupload" value="1" />
                                            <input type="hidden" name="isimage" value="1" />
                                            <input  name="userfile" type="file" style="padding:3px;"/>
                                            <input  type="submit" class="save_btn" value="上传形象照" style="margin-left:40px;" />
                                        </form>
                                        <?php } else { ?>
                                        <form action="index.php?n=material&h=upload" method="POST" enctype="multipart/form-data" name="uppic" id="uppic" onsubmit="return checkPicForm();">
                                            <input type="hidden" name="isupload" value="1" />
                                            <input type="hidden" name="isimage" value="0" />
                                            <input  name="userfile" type="file" style="padding:3px;" />
                                            <input type="submit" class="save_btn" value="上传照片" style="margin-left:40px;" />
                                        </form>
                                        <?php } ?>
                                    </p>
                                    <p class="f-aeaeae">照片必须为JPG，PNG或GIF格式，并且不可小于20KB和超过1000KB。</p>
                                    <p class="f-aeaeae">·上传的照片，客服将在24小时内进行审核，通过后照片才可以显示。请耐心等待！</p>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <h4>我的影集</h4>
                            <div class="up-photo">
                                <?php foreach((array)$user2 as $users2) {?>
                                <div class="show-img">
                                    <?php if(!$users2['syscheck']) { ?>
                                    <div class="post_s_div">照片正在审核中</div>
                                    <?php } ?>
                                    <div class="pic10">
                                        <p>
                                            <a href="./index.php?n=material&h=del&isimage=0&imageid=<?php echo $users2['imgid'];?>">
                                                <img src="<?php echo thumbImgPath(2,$users2['pic_date'],$users2['pic_name']).'?sid='.rand(111111,999999); ?>" />
                                            </a>
                                        </p>
                                    </div>     
                                    <p style="padding-top:5px;">
                                        <a class="setImage" style="cursor:pointer" data-gender="<?php echo $user_arr['gender'];?>" data-url="<?php echo $users2['imgurl'];?>" data-date="<?php echo $users2['pic_date'];?>" data-name="<?php echo $users2['pic_name'];?>"><?php if($users2['imgurl']==$user['mainimg'] ) { ?><span class="cancel">取消形象照</span><?php } else { ?><span class="ok">设为形象照</span><?php } ?> </a>
                                        &nbsp;&nbsp;&nbsp;&nbsp;<a class="deletePhoto" style="cursor:pointer;" data-imageid="<?php echo $users2['imgid'];?>">删除&times;</a>
                                    </p>
                                    <!-- <p><a href="index.php?n=material&h=imgedit&imageName=<?php echo base64_encode($users2['imgurl'])?>">在线编辑图片<i class="new"><img src="module/material/templates/default/images/new.gif" /></i></a></p> -->
                                </div>
                                <?php } ?>
                                <div class="clear"></div>
                                <!-- <div class="pager"></div> -->
                            </div>
                            <h4>上传照片注意事项</h4>
                            <div class="up-photo">
                                <p><a href="index.php?n=material&h=help" class="f-ed0a91" target="_blank">怎样的照片可以帮助您提高在真爱一生网中的征婚成功率呢？</a></p>
                                <dl class="up-photo-note">
                                    <dt><img src="module/material/templates/default/images/note01.gif" /></dt>
                                    <dd>
                                        <p class="f-b-d73c90">高质量的照片</p>
                                    </dd>
                                    <dd>真爱一生网要求会员从数码相机或其他硬盘中，直接上传清晰、无变形的高质量照片。</dd>
                                </dl>
                                <dl class="up-photo-note">
                                    <dt><img src="module/material/templates/default/images/note02.gif" /></dt>
                                    <dd>
                                        <p class="f-b-d73c90">照片数量</p>
                                    </dd>
                                    <dd>照片的数量会提高您征婚的成功率，升级会员可以上传更多的照片。</dd>
                                </dl>
                                <dl class="up-photo-note">
                                    <dt><img src="module/material/templates/default/images/note03.gif" /></dt>
                                    <dd>
                                        <p class="f-b-d73c90">照片规格</p>
                                    </dd>
                                    <dd>建议您上传的照片要大于100KB，理想的照片尺寸为300×400像素。</dd>
                                </dl>
                                <div class="clear"></div>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="r-center-bottom">
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
                <div class="clear"></div>
            </div>

            <!--content end-->
            <?php include MooTemplate('system/footer','public'); ?><!--foot end-->
        </div><!--main end-->
        <script type="text/javascript">
            $('.setImage').on('click',function(){ 
			    if($(this).children('span').hasClass('ok')){
				    var that=$(this);
					if(confirm('您设置此张照片为形象照吗？')){
						var purl=that.attr('data-url');
						var pdate=that.attr('data-date');
						var pname=that.attr('data-name');
						var gender=that.attr('data-gender');
						$.ajax({
							url:'ajax.php?n=material&h=setImage',
							data:{action:'u',purl:purl,pdate:pdate,pname:pname},
							type:'POST',
							dataType:'html',
							success:function(str){
								$('.upimg p:eq(0) ').html('<img src="'+purl+'" />');
								$('.upimg').after('<div class="post_s_div">照片正在审核中</div>');
								if(gender==1){
									$('.mat-limg p').html('<img src="public/system/images/woman.gif">');
								}else{
									$('.mat-limg p').html('<img src="public/system/images/man.gif">');
								}
	
								if(that.has('span')) that.children('span').remove();
								that.append('<span class="cancel">取消形象照</span>');
								$('.delImage').html('取消形象照');
							}
						});
					}
				}else if($(this).children('span').hasClass('cancel')){
				    var gender=$(this).attr('data-gender');
					var that=$(this);
					if(confirm('您取消此张照片为形象照吗？')){
						$.ajax({
							url:"ajax.php?n=material&h=setImage",
							data:{action:'d'},
							type:'POST',
							dataType:'text',
							success:function(str){
								$('.post_s_div').remove();
								if(gender==1){
									$('.mat-limg p').html('<img src="public/system/images/service_nopic_woman.gif">');
									$('.upimg p:eq(0) ').html('<img src="public/system/images/service_nopic_woman.gif">');
								}else{
									$('.mat-limg p').html('<img src="public/system/images/service_nopic_man.gif">');
									$('.upimg p:eq(0) ').html('<img src="public/system/images/service_nopic_man.gif">');
								}
								that.html('<span class="ok">设为形象照</span>');
								$('.delImage').html('');
							} 
						});
				    }
				}
            });
			
			
			$('.delImage').on('click',function(){
			    var gender=$(this).attr('data-gender');
				var that=$(this);
			    if(confirm('您取消此张照片为形象照吗？')){
					$.ajax({
						url:"ajax.php?n=material&h=setImage",
						data:{action:'d'},
						type:'POST',
						dataType:'text',
						success:function(str){
							$('.post_s_div').remove();
							if(gender==1){
								$('.mat-limg p').html('<img src="public/system/images/service_nopic_woman.gif">');
								$('.upimg p:eq(0) ').html('<img src="public/system/images/service_nopic_woman.gif">');
							}else{
								$('.mat-limg p').html('<img src="public/system/images/service_nopic_man.gif">');
								$('.upimg p:eq(0) ').html('<img src="public/system/images/service_nopic_man.gif">');
							}
							that.html('');
							$('.setImage').each(function(i,w){
							    if($(w).children('span').hasClass('cancel')) $(w).html('<span class="ok">设为形象照</span>');
							});
						} 
					});
				}
			});
			
			$('.deletePhoto').on('click',function(){
			    var that=$(this);
				var imgid=$(this).attr('data-imageid');
			    if(confirm('您确定删除此张照片吗？')){
					$.ajax({
					    url:'ajax.php?n=material&h=delPhoto',
						type:'POST',
						data:{imgid:imgid},
						dataType:'text',
						success:function(str){
						    that.parents('.show-img').remove();
						}
					});
				}
			    //javascript:window.location.href='index.php?n=material&h=del&isimage=0&imageid=<?php echo $users2['imgid'];?>&s=1'" onclick="return confirm('确定删除吗？')
			});
			
            //index.php?n=material&h=show&actio=u&url=<?php echo $users2['imgurl'];?>&pdate=<?php echo $users2['pic_date'];?>&pname=<?php echo $users2['pic_name'];?>" onclick="return 
        </script>
    </body>
</html>

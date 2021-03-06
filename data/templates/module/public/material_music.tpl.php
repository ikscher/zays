<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>上传音乐——真爱一生网</title>
        <?php include MooTemplate('system/js_css','public'); ?>
        <link href="module/material/templates/default/left.css" rel="stylesheet" type="text/css" />
        <link href="module/material/templates/default/material.css" rel="stylesheet" type="text/css" />
        <script language="javascript">
            function checkPicForm(){
                pic = document.uppic.userfile.value;
                if(!pic){
                    alert('请选择要上传的音乐文件');
                    return false;
                }
                return true;
            }
        </script>
    </head>
    <!-- <script src="module/search/templates/default/js/syscode.js?v=1" type="text/javascript"></script> -->
    <body>
        <?php include MooTemplate('system/header','public'); ?>
        <div class="main">
            <div class="content">
              
                <!--content-lift end-->
                <?php include MooTemplate('system/simp_left','public'); ?>
                <!--=====左边结束===-->
                <div class="content-right">
                    <div class="c-right-content">
                        <div class="r-center-tilte">
                            <span class="right-title f-ed0a91">上传音乐</span>
                        </div>
                        <div class="r-center-ccontent">
                          
                            <!-- <h4>上传音乐</h4> -->
                            <div class="up-photo">
                                
                                <div class="up-photo-right">
                                    <p><strong>温馨提示：</strong>点击“浏览”从您的计算机中选择您想要上传的音乐。</p>
                                   
                                    <p style="padding:25px 0;">
                                    
                                        <form action="index.php?n=material&h=music" method="POST" enctype="multipart/form-data" name="uppic" id="uppic" onsubmit="return checkPicForm();">
                                            <input type="hidden" name="isupload" value="1" />
                                            <input type="hidden" name="ismusic" value="1" />
                                            <input  name="userfile" type="file" style="padding:3px;"/>
                                            <input  type="submit" class="save_btn" value="上传音乐" style="margin-left:40px;" />
                                        </form>
                                       
                                    </p>
                                    <p class="f-aeaeae">音乐必须为mp3,wav,ogg的格式，并且不可小于1MB和超过8MB。</p>
                                    <!-- <p class="f-aeaeae">·上传的照片，客服将在24小时内进行审核，通过后照片才可以显示。请耐心等待！</p> -->
                                </div>
                                <div class="clear"></div>
                            </div>
                          
                            <h4>上传音乐注意事项</h4>
                            <div class="up-photo">
							    <ul>
								<li>1：上传后的音乐在个人主页显示如下：
                                <img src="module/material/templates/default/images/mp3.png" /></li>
								<br/>
								<li>
                                2：必须为VIP会员，上传音乐后您的个人主页将会以此音乐作为背景音乐。</li></ul>
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
    </body>
</html>

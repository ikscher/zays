<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>我的委托--我的真爱一生——真爱一生网</title>
        <?php include MooTemplate('system/js_css','public'); ?>
        <link href="module/service/templates/default/service.css" rel="stylesheet" type="text/css" />
        <script src="module/service/templates/public/js/service_contact_getcontactme.js"></script>
		<style type="text/css">
            .fixphoto_{max-width:171px;max-height:123px;margin-left:-35px;}
		</style>
    </head>

    <body>
        <div class="main">
            <?php include MooTemplate('system/header','public'); ?><!--top end--><!--head-search end-->
            <div class="content">
                <p class="c-title"><span class="f-000"><a href="index.php">真爱一生首页</a>&nbsp;&gt;&gt;&nbsp;<a href="index.php?n=service">我的真爱一生</a>&nbsp;&gt;&gt;&nbsp;我的委托</span><a href="index.php?n=invite" target="_blank" class="loaction_right">邀请好友</a></p>
                <div class="content-lift">
                    <?php include MooTemplate('public/service_left','module'); ?>
                </div>
                <!--=====左边结束===-->
                <div class="content-right">
                    <div class="c-right-content">
                        <div class="r-center-tilte">
                            <a href="index.php?n=service" class="r-title-black">&lt;&lt;返回我的真爱一生</a>
                            <span class="right-title f-ed0a91">我的委托</span>			
                        </div>
                        <div class="r-center-ccontent" style="padding:20px 0 60px; width:753px;">
                            <!--=======公共头部=====-->
                            <div class="service-title">
                                <ul>
                                    <li><a href="#" id="two1" class="onthis"><span>TA委托真爱一生联系我</span></a></li>
                                    <li><a id="two2" href="#" onclick="javascript:location.href='index.php?n=service&h=commission&t=getmycontact'"><span>我委托真爱一生联系TA</span></a></li>
                                </ul>
                                <div class="clear"></div>
                            </div>
                            <!--====公共头部结束==-->
                            <div class="s-righr-list">
                                <div class="s-righr-list-nav">
                                    <ul>
                                        <li ><a href="#" class="f-b-d73c90" id="one1" onclick="setTab('one',1,3)">等待回应的请求 (<?php echo $total;?>)</a></li>
                                        <li><a href="#" id="one2" onclick="setTab('one',2,3)" class="f-000">已接受的请求 (<?php echo $total2;?>)</a></li>
                                        <li><a href="#" class="f-000" id="one3" onclick="setTab('one',3,3)">考虑中的请求 (<?php echo $total3;?>)</a></li>
                                    </ul>
                                </div>
                                <!--列表开始-->
                                <div id="con_one_1" style="display:block">
                                    <?php if($total == 0) { ?> 
                                    <div class="norequest">
                                        目前您没有要回应的委托<br />立即 <a href="index.php?n=search" class="f-ed0a91-a">搜索意中人</a>，委托真爱一生帮您联系对方 </div>
                                    <?php } else { ?>
                                    <?php foreach((array)$contacts as $contact) {?>

                                    <div class="r-conter-box">
                                        <div class="r-conter-box-side"></div>
                                        <div class="r-conter-box-center">
                                            <a onclick="event.cancelBubble = true;return confirm('确定删除此委托吗？');" href="index.php?n=service&h=commission&t=delmycontact&dif_type=1&mid=<?php echo $contact['l'][mid];?>" class="r-close"></a>
                                            <div class="r-service-img">
                                                <?php if(!empty($contact['s']['city_star'])) { ?><a class="citystar2"><img src="module/service/templates/default/images/citystar.gif" /></a><?php } ?>
                                                <div class="r-s-img-in">
                                                    <p style="width:105px;">
                                                        <a style="display:block;" href="index.php?n=service&h=commission&t=getcontactdata1&uid=<?php echo $contact['l']['other_contact_you'];?>">
                                                            <?php if($contact['s']['images_ischeck']=='1'&& $contact['s']['mainimg']) { ?>
                                                            <img src="
                                                                 <?php if(MooGetphoto($contact['s'][uid],'mid')) echo MooGetphoto($contact['s'][uid],'mid');
                                                                 elseif(MooGetphoto($contact['s'][uid],'medium')) echo MooGetphoto($contact['s'][uid],'medium');
                                                                 elseif($contact['s']['gender'] == '1') echo 'public/system/images/woman_100.gif';
                                                                 else echo 'public/system/images/man_100.gif';?>" />
                                                            <?php } elseif ($contact['s']['mainimg']) { ?>
                                                            <?php if($contact['s']['gender'] == '1') { ?>
                                                            <img src="public/system/images/woman_100.gif"/>
                                                            <?php } else { ?>
                                                            <img src="public/system/images/man_100.gif"  />
                                                            <?php } ?>

                                                            <?php } else { ?>
                                                            <?php if($contact['s']['gender'] == '1') { ?>
                                                            <img src="public/system/images/nopic_woman_100.gif" />
                                                            <?php } else { ?>
                                                            <img src="public/system/images/nopic_man_100.gif"  />
                                                            <?php } ?>
                                                            <?php } ?>
                                                        </a>
                                                    </p>
                                                </div>
                                            </div>
                                            <ul class="r-service-data">
                                                <li><strong><?php if($contact['s']['s_cid'] ==20) { ?>
                                                        <a href="index.php?n=payment&h=diamond" target="_blank"><img src="module/index/templates/default/images/zuan0.gif" title="钻石会员" alt="钻石会员"/></a>
                                                        <?php } elseif ($contact['s']['s_cid'] == 30) { ?>
                                                        <a href="index.php?n=payment" target="_blank"><img src="module/index/templates/default/images/zuan1.gif" title="高级会员" alt="高级会员"/></a>
                                                        <?php } else { ?>
                                                        <?php } ?>
                                                        <a href="index.php?n=service&h=commission&t=getcontactdata1&uid=<?php echo $contact['l']['other_contact_you'];?>">
                                                            <?php echo $contact['s']['nickname']?$contact['s']['nickname']:'ID:'.$contact['s']['uid']?></a></strong></li>
                                                <li><a href="index.php?n=service&h=commission&t=getcontactdata1&uid=<?php echo $contact['l']['other_contact_you'];?>" class="f-ed0a91">等待我的回应</a></li>
                                                <li>年龄：<?php if($contact['s']['birthyear']) { ?><?php echo (gmdate('Y', time()) - $contact['s']['birthyear']);?>岁<?php } else { ?>保密<?php } ?></li>
                                                <li>身高：<script>userdetail("<?php echo $contact['s'][height];?>",height);</script></li>
                                                <li>学历：<script>userdetail("<?php echo $contact['s'][education];?>",education);</script></li>
                                                <li>收入：<script>userdetail("<?php echo $contact['s'][salary];?>",salary1);</script></li>
                                            </ul>
                                            <dl class="r-service-heart">
                                                <dt><strong>内心独白</strong></dt>
                                                <dd class="r-service-heart-text"> <?php echo $contact['t']['introduce'];?></dd>
                                                <dt></dt>
                                            </dl>						
                                            <div class="clear"></div>
                                        </div>
                                        <div class="r-conter-box-side" style="background-position:bottom"></div>
                                    </div>
                                    <?php } ?>
                                    <?php } ?><div class="clear"></div>			
                                    <div class="pages" style="padding-right:180"><?php echo multipage($total,$pagesize,$page,$currenturl2)?>
                                    </div></div><!--等待回应的请求列表  -->
                                <div id="con_one_2" style="display:none">
                                    <?php if($total2 == 0) { ?>
                                    <div class="norequest">
                                        目前您没有接受的联系请求<br />立即 <a href="index.php?n=search" class="f-ed0a91-a">搜索意中人</a>，委托真爱一生帮您联系对方 </div>
                                    <?php } else { ?>
                                    <?php foreach((array)$contacts2 as $contact) {?>				
                                    <div class="r-conter-box">
                                        <div class="r-conter-box-side"></div>
                                        <div class="r-conter-box-center">
                                            <a onclick="event.cancelBubble = true;return confirm('确定删除此委托吗？');" href="index.php?n=service&h=commission&t=delmycontact&dif_type=1&mid=<?php echo $contact['l'][mid];?>" class="r-close"></a>
                                            <div class="r-service-img">
                                                <?php if(!empty($contact['s']['city_star'])) { ?><a class="citystar2"><img src="module/service/templates/default/images/citystar.gif" /></a><?php } ?>
                                                <div class="r-s-img-in">
                                                    <p style="width:105px;">
                                                        <a style="display:block;" href="index.php?n=service&h=commission&t=getcontactdata1&uid=<?php echo $contact['l']['other_contact_you'];?>">
                                                            <?php if($contact['s']['images_ischeck']=='1'&& $contact['s']['mainimg']) { ?>
                                                            <img src="
                                                                 <?php if(MooGetphoto($contact['s'][uid],'mid')) echo MooGetphoto($contact['s'][uid],'mid');
                                                                 elseif(MooGetphoto($contact['s'][uid],'medium')) echo MooGetphoto($contact['s'][uid],'medium');
                                                                 elseif($contact['s']['gender'] == '1') echo 'public/system/images/woman_100.gif';
                                                                 else echo 'public/system/images/man_100.gif';
                                                                 ?>" />
                                                            <?php } elseif ($contact['s']['mainimg']) { ?>
                                                            <?php if($contact['s']['gender'] == '1') { ?>
                                                            <img src="public/system/images/woman_100.gif"/>
                                                            <?php } else { ?>
                                                            <img src="public/system/images/man_100.gif"  />
                                                            <?php } ?>

                                                            <?php } else { ?>
                                                            <?php if($contact['s']['gender'] == '1') { ?>
                                                            <img src="public/system/images/nopic_woman_100.gif" />
                                                            <?php } else { ?>
                                                            <img src="public/system/images/nopic_man_100.gif"  />
                                                            <?php } ?>
                                                            <?php } ?>
                                                        </a>
                                                    </p>
                                                </div>
                                            </div>
                                            <ul class="r-service-data">
                                                <li><strong><?php if($contact['s']['s_cid'] ==20) { ?>
                                                        <a href="index.php?n=payment&h=diamond" target="_blank"><img src="module/index/templates/default/images/zuan0.gif" title="钻石会员" alt="钻石会员"/></a>
                                                        <?php } elseif ($contact['s']['s_cid'] == 30) { ?>
                                                        <a href="index.php?n=payment" target="_blank"><img src="module/index/templates/default/images/zuan1.gif" title="高级会员" alt="高级会员"/></a>
                                                        <?php } else { ?>
                                                        <?php } ?>
                                                        <a href="index.php?n=service&h=commission&t=getcontactdata1&uid=<?php echo $contact['l']['other_contact_you'];?>">
                                                            <?php echo $contact['s']['nickname']?$contact['s']['nickname']:'ID:'.$contact['s']['uid']?></a></strong></li>
                                                <li><a href="index.php?n=service&h=commission&t=getcontactdata1&uid=<?php echo $contact['l']['other_contact_you'];?>" class="f-ed0a91">等待我的回应</a></li>
                                                <li>年龄：<?php if($contact['s']['birthyear']) { ?><?php echo (gmdate('Y', time()) - $contact['s']['birthyear']);?>岁<?php } else { ?>保密<?php } ?></li>
                                                <li>身高：<script>userdetail("<?php echo $contact['s'][height];?>",height);</script></li>
                                                <li>学历：<script>userdetail("<?php echo $contact['s'][education];?>",education);</script></li>
                                                <li>收入：<script>userdetail("<?php echo $contact['s'][salary];?>",salary1);</script></li>
                                            </ul>
                                            <dl class="r-service-heart">
                                                <dt><strong>内心独白</strong></dt>
                                                <dd class="r-service-heart-text"> 
                                                    <?php echo $contact['t']['introduce'];?>
                                                </dd>
                                                <dt>

                                                </dt>
                                            </dl>						
                                            <div class="clear"></div>
                                        </div>
                                        <div class="r-conter-box-side" style="background-position:bottom"></div>
                                    </div>
                                    <?php } ?>
                                    <?php } ?><div class="clear"></div>			
                                    <div class="pages" style="padding-right:180"><?php echo multipage($total2,$pagesize,$page,$currenturl2)?>
                                    </div></div><!--已接受的请求列表  -->
                                <div id="con_one_3" style="display:none">
                                    <?php if($total3 == 0) { ?> 
                                    <div class="norequest">
                                        目前您没有考虑中的联系请求<br />立即 <a href="index.php?n=search" class="f-ed0a91-a">搜索意中人</a>，委托真爱一生帮您联系对方 </div>
                                    <?php } else { ?>
                                    <?php foreach((array)$contacts3 as $contact) {?>

                                    <div class="r-conter-box">
                                        <div class="r-conter-box-side"></div>
                                        <div class="r-conter-box-center">
                                            <a onclick="event.cancelBubble = true;return confirm('确定删除此委托吗？');" href="index.php?n=service&h=commission&t=delmycontact&dif_type=1&mid=<?php echo $contact['l'][mid];?>" class="r-close"></a>
                                            <div class="r-service-img">
                                                <?php if(!empty($contact['s']['city_star'])) { ?><a class="citystar2"><img src="module/service/templates/default/images/citystar.gif" /></a><?php } ?>
                                                <div class="r-s-img-in">
                                                    <p style="width:105px;">
                                                        <a style="display:block;" href="index.php?n=service&h=commission&t=getcontactdata1&uid=<?php echo $contact['l']['other_contact_you'];?>">
                                                            <?php if($contact['s']['images_ischeck']=='1'&& $contact['s']['mainimg']) { ?>
                                                            <img src="<?php if(MooGetphoto($contact['s'][uid],'mid')) echo MooGetphoto($contact['s'][uid],'mid');
                                                                 elseif(MooGetphoto($contact['s'][uid],'medium')) echo MooGetphoto($contact['s'][uid],'medium');
                                                                 elseif($contact['s']['gender'] == '1') echo 'public/system/images/woman_100.gif';
                                                                 else echo 'public/system/images/man_100.gif';?>" />
                                                            <?php } elseif ($contact['s']['mainimg']) { ?>
                                                            <?php if($contact['s']['gender'] == '1') { ?>
                                                            <img src="public/system/images/woman_100.gif"/>
                                                            <?php } else { ?>
                                                            <img src="public/system/images/man_100.gif"  />
                                                            <?php } ?>

                                                            <?php } else { ?>
                                                            <?php if($contact['s']['gender'] == '1') { ?>
                                                            <img src="public/system/images/nopic_woman_100.gif" />
                                                            <?php } else { ?>
                                                            <img src="public/system/images/nopic_man_100.gif"  />
                                                            <?php } ?>
                                                            <?php } ?>
                                                        </a>
                                                    </p>
                                                </div>
                                            </div>
                                            <ul class="r-service-data">
                                                <li><strong><?php if($contact['s']['s_cid'] ==20) { ?>
                                                        <a href="index.php?n=payment&h=diamond" target="_blank"><img src="module/index/templates/default/images/zuan0.gif" title="钻石会员" alt="钻石会员"/></a>
                                                        <?php } elseif ($contact['s']['s_cid'] == 30) { ?>
                                                        <a href="index.php?n=payment" target="_blank"><img src="module/index/templates/default/images/zuan1.gif" title="高级会员" alt="高级会员"/></a>
                                                        <?php } else { ?>
                                                        <?php } ?>
                                                        <a href="index.php?n=service&h=commission&t=getcontactdata1&uid=<?php echo $contact['l']['other_contact_you'];?>">
                                                            <?php echo $contact['s']['nickname']?$contact['s']['nickname']:'ID:'.$contact['s']['uid']?></a></strong></li>
                                                <li><a href="index.php?n=service&h=commission&t=getcontactdata1&uid=<?php echo $contact['l']['other_contact_you'];?>" class="f-ed0a91">等待我的回应</a></li>
                                                <li>年龄：<?php if($contact['s']['birthyear']) { ?><?php echo (gmdate('Y', time()) - $contact['s']['birthyear']);?>岁<?php } else { ?>保密<?php } ?></li>
                                                <li>身高：<script>userdetail("<?php echo $contact['s'][height];?>",height);</script></li>
                                                <li>学历：<script>userdetail("<?php echo $contact['s'][education];?>",education);</script></li>
                                                <li>收入：<script>userdetail("<?php echo $contact['s'][salary];?>",salary1);</script></li>
                                            </ul>
                                            <dl class="r-service-heart">
                                                <dt><strong>内心独白</strong></dt>
                                                <dd class="r-service-heart-text"> <?php echo $contact['t']['introduce'];?></dd>
                                                <dt> </dt>
                                            </dl>						
                                            <div class="clear"></div>
                                        </div>
                                        <div class="r-conter-box-side" style="background-position:bottom"></div>
                                    </div>
                                    <?php } ?>
                                    <?php } ?><div class="clear"></div>			
                                    <div class="pages" style="padding-right:180"><?php echo multipage($total3,$pagesize,$page,$currenturl2)?>
                                    </div></div><!--考虑中的请求列表  -->
                                <!--列表结束-->				
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
            $(document).ready(function(){
                var theImage = new Image();
		
                $(".r-s-img-in a img").each(function(i,w){
                    theImage.src = $(w).attr("src");
                    var imageWidth = theImage.width;
                    var imageHeight = theImage.height;
                    if(imageHeight<200){
                        //$(w).removeClass('fixphoto');
                        $(w).addClass('fixphoto_');
                    }else{
                        //DrawImage($(w),100,125);
                    }
                })
            });
        </script>
    </body>
</html>

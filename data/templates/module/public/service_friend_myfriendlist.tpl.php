<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>我的意中人——我的真爱一生——真爱一生网</title>
        <link href="module/service/templates/default/service.css" rel="stylesheet" type="text/css" />
        <?php include MooTemplate('system/js_css','public'); ?>
        <script src="module/service/templates/default/js/service_submit_page.js"></script>
		<style type="text/css">
            .fixphoto_{max-width:171px;max-height:123px;margin-left:-35px;}
		</style>
    </head>

    <body>
        <?php include MooTemplate('system/header','public'); ?>
<div class="main">
            <div class="content">
                <p class="c-title"><span class="f-000"><a href="index.php">真爱一生首页</a>&nbsp;&gt;&gt;&nbsp;<a href="index.php?n=service">我的真爱一生</a>&nbsp;&gt;&gt;&nbsp;我的意中人</span><!-- <a href="index.php?n=invite" target="_blank" class="loaction_right">邀请好友</a> --></p>
                <div class="content-lift">
                    <?php include MooTemplate('public/service_left','module'); ?>

                </div><!--content-lift end-->
                <!--=====左边结束===-->
                <div class="content-right">
                    <div class="c-right-content">
                        <div class="r-center-tilte">
                            <a href="#" class="r-title-black">&lt;&lt;返回我的真爱一生</a>
                            <span class="right-title f-ed0a91">我的意中人</span>
                        </div>
                        <div class="r-center-ccontent" style="padding:20px 0 60px; width:753px;">
                            <!--=======公共头部=====-->
                            <div class="service-title">
                                <ul>
                                    <li><a href="#" class="onthis"><span>我的意中人（<?php echo $total;?>）</span></a></li>
                                    <li><a href="#" onclick="location.href='index.php?n=service&h=liker&t=whoaddme'"><span>谁加我为意中人（<?php echo $total2;?>）</span></a></li>
                                </ul>
                                <div class="clear"></div>
                            </div>
                            <!--====公共头部结束==-->
                            <form name="form2" action="index.php?n=service&h=liker" method="post" onsubmit="return checkPageGo('delfriendid[]')"> 
                                <div class="service-liker">
                                    <?php if($total == 0) { ?>
                                    <div class="norequest">
                                        您现在还没有加任何人为您的意中人
                                        <p> 马上去 <a href="index.php?n=search" class="f-ed0a91-a">搜索意中人</a>，即刻拥有自己的意中人</p>
                                    </div>
                                    <?php } else { ?>
                                    <?php foreach((array)$friends as $friend) {?>
                                    <ul class="service-liker-list">
                                        <li><input type="checkbox" name="delfriendid[]" value="<?php echo $friend['l']['fid'];?>"/></li>
                                        <li>
                                            <div class="r-service-img">
                                                <?php if(!empty($friend['s']['city_star'])) { ?><a class="citystar2"><img src="module/service/templates/default/images/citystar.gif" /></a><?php } ?>
                                                <div class="r-s-img-in">
                                                    <p style="width:105px;">  <a style="display:block;" href="index.php?n=space&h=viewpro&uid=<?php echo $friend['l']['friendid'];?>" target="_blank">
                                                            <?php if($friend['s']['images_ischeck']=='1'&& $friend['s']['mainimg']) { ?>
                                                            <img src="<?php if(MooGetphoto($friend['s'][uid],'mid')) echo MooGetphoto($friend['s'][uid],'mid');
                                                                 elseif(MooGetphoto($friend['s'][uid],'medium')) echo MooGetphoto($friend['s'][uid],'medium');
                                                                 elseif($friend['s']['gender'] == '1') echo 'public/system/images/woman_100.gif';
                                                                 else echo 'public/system/images/man_100.gif';?>" />
                                                            <?php } elseif ($friend['s']['mainimg']) { ?>
                                                            <?php if($friend['s']['gender'] == '1') { ?>
                                                            <img src="public/system/images/woman_100.gif"/>
                                                            <?php } else { ?>
                                                            <img src="public/system/images/man_100.gif"  />
                                                            <?php } ?>

                                                            <?php } else { ?>
                                                            <?php if($friend['s']['gender'] == '1') { ?>
                                                            <img src="public/system/images/nopic_woman_100.gif" />
                                                            <?php } else { ?>
                                                            <img src="public/system/images/nopic_man_100.gif"  />
                                                            <?php } ?>
                                                            <?php } ?>
                                                        </a> </p>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <dl class="liker-heart">
                                                <dt><?php if($friend['s']['s_cid'] ==20) { ?>
                                                    <a href="index.php?n=payment&h=diamond" target="_blank"><img src="module/index/templates/default/images/zuan0.gif" title="钻石会员" alt="钻石会员"/></a>
                                                    <?php } elseif ($friend['s']['s_cid'] == 30) { ?>
                                                    <a href="index.php?n=payment" target="_blank"><img src="module/index/templates/default/images/zuan1.gif" title="高级会员" alt="高级会员"/></a>
                                                    <?php } else { ?>
                                                    <?php } ?>
                                                    <a id="fzs" href="index.php?n=space&h=viewpro&uid=<?php echo $friends['friendid'];?>"><?php $nickname=$friend['s']['nickname2']?$friend['s']['nickname2']:$friend['s']['nickname']?><?php echo $nickname?$nickname:'ID:'.$friend['s']['uid']?></a>
                                                </dt>
                                                <dd>年龄：<?php if($friend['s']['birthyear']) { ?><?php echo (gmdate('Y', time()) - $friend['s']['birthyear']);?>岁<?php } else { ?>保密<?php } ?></dd>
                                                <dd>身高：<script>userdetail("<?php echo $friend['s'][height];?>",height);</script></dd>
                                                <dd>收入：<script>userdetail("<?php echo $friend['s'][salary];?>",salary1);</script></dd>
                                                <dd>学历：<script>userdetail("<?php echo $friend['s'][education];?>",education);</script></dd>
                                                <dd>地址：<?php if($friend['s'][province] == '0' && $friend['s'][city] == '0') { ?>保密<?php } else { ?><script>userdetail('<?php echo $friend['s'][province];?>',provice);userdetail('<?php echo $friend['s'][city];?>',city)</script><?php } ?></dd>
                                                <!-- <dd class="f-ed0a91">&lt; <?php echo activetime($friend['s']['lastvisit'],$friend['s']['usertype']);?> &gt;</dd> -->
                                            </dl>
                                        </li>
                                        <li class="fright">
                                            <dl class="r-service-heart">
                                                <dt>内心独白</dt>
                                                <dd class="r-service-heart-text"><?php echo $friend['t']['introduce']?MooCutstr($friend['t']['introduce'], 148, $dot = ' ...'):'无内心独白内容'?>
                                                </dd>
                                                <!-- <dd> <p><input name="" onclick="location.href='index.php?n=service&h=commission&t=sendcontact&sendid=<?php echo $friend['s'][uid];?>'" type="button" class="btn btn-default" value="委托真爱一生联系TA" /></p></dd> -->
                                            </dl>	
                                        </li>
                                    </ul>
                                    <?php } ?>

                                    <div class="clear"></div>
                                    <div class="pages"><span class="fleft" style="padding-left:40px;"><input name="" type="checkbox" onclick="checkAll(this)"  value="" />&nbsp;全选&nbsp;<input name="" type="submit" class="btn btn-default" value="删除信息" /><input type="hidden"  name="delfriend"  value="删除秋波"></span><?php echo multimail($total,$pagesize,$page,$currenturl2)?><input name="" id="pageGo" type="text" size="3" />&nbsp;<input name=""  type="button" class="go-page" value="GO" onclick="gotoPage()" style="float:none"/>
                                        <div class="clear"></div>
                                    </div><?php } ?>
                                </div>
                                <div class="clear"></div>
                        </div>

                        <div class="r-center-bottom">
                        </div>
                        <div class="clear"></div>
                    </div>
                    <form>
                </div>
                <div class="clear"></div>
            </div>

            <!--content end-->
            <?php include MooTemplate('system/footer','public'); ?><!--foot end-->
        </div><!--main end-->
    </body>
</html>
<script type="text/javascript">

    // 删除全选功能
    function checkAll(chk){  
        var checkboxes = document.getElementsByName("delfriendid[]");
        for(j=0; j < checkboxes.length; j++) {
            checkboxes[j].checked = chk.checked;
        }
    }

    // 分页跳转
    function gotoPage() {
        value = document.getElementById("pageGo").value;
        value = parseInt(value);
	
        if(isNaN(value))
            value = 0;
	
        if(value<1)
            value = 1;
	
        if(value> <?php echo ceil($total/$pagesize);?>)
            value = <?php echo ceil($total/$pagesize);?>;
		
        window.location.href = "<?php echo $currenturl2;?>&page="+value;
    }
	
	
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
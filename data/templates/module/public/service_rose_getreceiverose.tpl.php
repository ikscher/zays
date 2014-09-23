<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>我的鲜花——我的真爱一生——真爱一生网</title>
        <link href="module/service/templates/default/service.css" rel="stylesheet" type="text/css" />
        <?php include MooTemplate('system/js_css','public'); ?>
        <script src="module/service/templates/default/js/service_submit_page.js"></script>
		<style type="text/css">
            .fixphoto_{max-width:171px;max-height:123px;margin-left:-35px;}
		</style>
    </head>

    <body>
        <div class="main">
            <?php include MooTemplate('system/header','public'); ?>
            <div class="content">
                <p class="c-title"><span class="f-000"><a href="index.php">真爱一生首页</a>&nbsp;&gt;&gt;&nbsp;<a href="index.php?n=service">我的真爱一生</a>&nbsp;&gt;&gt;&nbsp;我的鲜花</span><a href="index.php?n=invite" target="_blank" class="loaction_right">邀请好友</a></p>
                <div class="content-lift">
                    <?php include MooTemplate('public/service_left','module'); ?>

                </div><!--content-lift end-->
                <!--=====左边结束===-->
                <div class="content-right">
                    <div class="c-right-content">
                        <div class="r-center-tilte">
                            <a href="index.php?n=service" class="r-title-black">&lt;&lt;返回我的真爱一生</a>
                            <span class="right-title f-ed0a91">我的鲜花</span>			
                        </div>
                        <div class="r-center-ccontent" style="padding:20px 0 60px; width:753px;">
                            <!--=======公共头部=====-->
                            <div class="service-title">
                                <ul>
                                    <li><a id="two1" onclick="setTab('two',1,4)" href="#" class="onthis"><span>收到的鲜花(<?php echo $receive_num;?>)</span></a></li>
                                    <li><a id="two2" onclick="setTab('two',2,4)" href="#"><span>发送的鲜花(<?php echo $send_num;?>)</span></a></li>
                                    <li><a id="two3" onclick="setTab('two',3,4)" href="#"><span>获得更多鲜花</span></a></li>
                                </ul>
                                <div class="clear"></div>
                            </div>
                            <!--====公共头部结束==-->
                            <div class="service-liker">
                                <form name="form1" action="index.php?n=service&h=gift&t=igetrose" method="post" onsubmit="return checkPageGo('delroseid[]')">
                                    <?php if(count($roses) == 0) { ?>
                                    <div class="norequest">
                                        <p>您还没有收到任何鲜花</p>
                                        <p>立即 <a href="?n=search" class="f-ed0a91-a">搜索意中人</a>，给意中人送出自己的鲜花</p>
                                    </div>          
                                    <?php } else { ?>
                                    <?php foreach((array)$roses as $rose) {?>
                                   
                                    <ul class="service-liker-list">
                                        <li><input type="checkbox" name="delroseid[]" value="<?php echo $roses['rid'];?>"/></li>
                                        <li>
                                            <div class="r-service-img">
                                                <?php if(!empty($rose['s']['city_star'])) { ?><a class="citystar2"><img src="module/service/templates/default/images/citystar.gif" /></a><?php } ?>
                                                <div class="r-s-img-in">
                                                    <p style="width:110px;"> <a style="display:block;" href="index.php?n=space&h=viewpro&uid=<?php echo $rose['s']['uid'];?>" target="_blank">
                                                            <?php if($rose['s']['images_ischeck']=='1'&& $rose['s']['mainimg']) { ?>
                                                            <img src="<?php if(MooGetphoto($rose['s'][uid],'mid')) echo MooGetphoto($rose['s'][uid],'mid');
                                                                 elseif(MooGetphoto($rose['s'][uid],'medium')) echo MooGetphoto($rose['s'][uid],'medium');
                                                                 elseif($rose['s']['gender'] == '1') echo 'public/system/images/woman_100.gif';
                                                                 else echo 'public/system/images/man_100.gif';
                                                                 ?>" />
                                                            <?php } elseif ($rose['s']['mainimg']) { ?>
                                                            <?php if($rose['s']['gender'] == '1') { ?>
                                                            <img src="public/system/images/woman_100.gif"/>
                                                            <?php } else { ?>
                                                            <img src="public/system/images/man_100.gif"  />
                                                            <?php } ?>

                                                            <?php } else { ?>
                                                            <?php if($rose['s']['gender'] == '1') { ?>
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
                                                <dt> <?php if($rose['s']['s_cid']==20) { ?>
                                                <a href="index.php?n=payment&h=diamond" target="_blank"><img src="module/index/templates/default/images/zuan0.gif" title="钻石会员" alt="钻石会员"/></a>
                                                <?php } elseif ($rose['s']['s_cid'] == 30) { ?>
                                                <a href="index.php?n=payment" target="_blank"><img src="module/index/templates/default/images/zuan1.gif" title="高级会员" alt="高级会员"/></a>
                                                <?php } else { ?>
                                                <?php } ?>
                                                <?php $nickname=$rose['s']['nickname2']?$rose['s']['nickname2']:$rose['s']['nickname']?><?php echo $nickname?$nickname:'ID:'.$rose['s']['senduid']?>
                                                </dt>
                                                <dd><p><?php if($rose['s']['birthyear']) { ?><?php echo (gmdate('Y', time()) - $rose['s']['birthyear']);?>岁<?php } else { ?>年龄保密<?php } ?>，居住<?php if($rose['s'][province] == '0' && $rose['s'][city] == '0') { ?>保密<?php } else { ?>在<script>userdetail('<?php echo $rose['s'][province];?>',provice)</script><script>userdetail('<?php echo $rose['s'][city];?>',city)</script><?php } ?>的<?php echo $rose['s']['gender']?'女士':'男士'?>，寻找一位年龄<?php if($rose['t'][age1] == '0') { ?>不限<?php } else { ?>在<?php echo $rose['t'][age1];?>-<?php echo $rose['t'][age2];?>岁<?php } ?>，居住<?php if($rose['t'][workprovince] == '0' && $rose['t'][workcity] == '0') { ?>不限<?php } else { ?>在<script>userdetail('<?php echo $rose['t'][workprovince];?>',provice);userdetail('<?php echo $rose['t'][workcity];?>',city)</script><?php } ?>的<?php echo $rose['s']['gender']?'男士':'女士'?></p>
                                                    <p><a href="index.php?n=space&h=viewpro&uid=<?php echo $rose['s']['uid'];?>" class="f-024890">点击查看>></a></p></dd>
                                                <dd class="f-ed0a91">&lt;<?php echo activetime($rose['s']['lastvisit'],$rose['s']['usertype']);?>
                                                    &gt;</dd>
                                            </dl>
                                        </li>
                                        <li style="float:right">
                                            <dl class="new-move">
                                                <dd><?php echo $rose['s']['gender']?'她':'他';?><?php echo date("Y年m月d日",$rose['l']['sendtime']);?>第<?php echo $rose['l']['fakenum'] + $rose['l']['num']?>次向您送出鲜花</dd>
                                                <dd><?php echo $rose['s']['gender']?'她':'他';?>如此喜欢您，强烈建议您：</dd>							
                                                <dt><input name="" onclick="location.href='index.php?n=service&h=commission&t=sendcontact&sendid=<?php echo $rose['s'][uid];?>'" type="button" class="btn btn-default" value="委托真爱一生联系TA" /></dt>
                                            </dl>
                                        </li>
                                    </ul>
                                    <?php } ?>


                                    <div class="clear"></div>
                                    <div class="pages"><span class="fleft" style="padding-left:40px;"><input  type="checkbox" onclick="checkAll(this)" value="" />&nbsp;全选&nbsp;<input name="" type="submit" class="btn btn-default" value="删除信息" /> <input type="hidden"  name="delrose"  value="删除玫瑰"></span><?php echo multimail($item_num,$page_per,$page_now,$page_url)?> <input name="pageGo"  id="pageGo" type="text" size="3" />&nbsp;<input name="" type="button" class="go-page" value="GO" style="float:none" onclick="gotoPage()"/>
                                        <div class="clear"></div>
                                    </div>
                                    <?php } ?>
                                </form>
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
            <?php include MooTemplate('system/footer','public'); ?>
        </div><!--main end-->
    </body>
</html>
<script>
    // 切换tab
    function setTab(name,cursel,n) {
        // 切换到收到鲜花列表
        if(cursel == 1) {
            window.location.href="index.php?n=service&h=gift&t=igetrose";
        }
  
        // 切换到发送出去的鲜花列表
        if(cursel == 2) {
            window.location.href="index.php?n=service&h=gift&t=isendrose";		
        }
  
        // 切换到获取更多鲜花
        if(cursel == 3) {
            window.location.href="index.php?n=service&h=gift&t=getmorerose";		
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
	
        if(value> <?php echo $page_num;?>)
            value = <?php echo $page_num;?>;
		
        window.location.href = "<?php echo $page_url;?>&page="+value;
    }

    // 删除全选功能
    function checkAll(chk){  
        var checkboxes = document.getElementsByName("delroseid[]");
        for(j=0; j < checkboxes.length; j++) {
            checkboxes[j].checked = chk.checked;
        }
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
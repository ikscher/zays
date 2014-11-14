<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>我的秋波——我的真爱一生——真爱一生网</title>

        <link href="module/service/templates/default/service.css" rel="stylesheet" type="text/css" />
        <?php include MooTemplate('system/js_css','public'); ?>
        <script src="module/service/templates/public/js/service_submit_page.js"></script>
        <script type="text/javascript">
            // 读取数组显示用户信息
            function userdetail(number,arrayobj) {
                var arrname = arrayobj;
                for(i=0;i<arrayobj.length;i++) {
                    var valueArray  = arrayobj[i].split(",");
                    if(valueArray[0] == number) {
                        if(valueArray[0] == '0' && valueArray[1] != '男士') {
                            document.write("保密");
                        } else {
                            document.write(valueArray[1]);
                        }	
                    }
                }
            }

        </script>
		<style type="text/css">
            .fixphoto_{max-width:171px;max-height:123px;margin-left:-35px;}
		</style>
    </head>

    <body>
        <?php include MooTemplate('system/header','public'); ?>
<div class="main"><!--top end-->	
            <div class="content">
                <p class="c-title"><span class="f-000"><a href="index.php">真爱一生首页</a>&nbsp;&gt;&gt;&nbsp;<a href="index.php?n=service">我的真爱一生</a>&nbsp;&gt;&gt;&nbsp;我的秋波</span>
                    <a href="index.php?n=invite" target="_blank" class="loaction_right">邀请好友</a>
                </p>
                <div class="content-lift">
                    <?php include MooTemplate('public/service_left','module'); ?>

                </div><!--content-lift end-->
                <!--=====左边结束===-->
                <div class="content-right">
                    <div class="c-right-content">
                        <div class="r-center-tilte">
                            <a href="index.php?n=service" class="r-title-black">&lt;&lt;返回我的真爱一生</a>
                            <span class="right-title f-ed0a91">我的秋波</span>			
                        </div>
                        <div class="r-center-ccontent" style="padding:20px 0 60px; width:753px;">
                            <!--=======公共头部=====-->
                            <div class="service-title">
                                <ul>					
                                    <li><a href="#" id="two1"  onclick="javascript:location.href='index.php?n=service&h=leer'" class="onthis"><span>已收到的秋波（<?php echo $receive_num;?>）</span></a></li>
                                    <li><a href="#" id="two2" onclick="javascript:location.href='index.php?n=service&h=leer&t=isendleer'"><span>已发送的秋波（<?php echo $send_num;?>）</span></a></li>
                                </ul>
                                <div class="clear"></div>
                            </div>
                            <!--====公共头部结束==-->
                            <div class="service-liker">
                                <?php if(count($leers) == 0) { ?>
                                <div class="norequest">
                                    <p>您现在还没有收到任何秋波</p>
                                    <p>立即 <a href="?n=search" class="f-ed0a91-a">搜索意中人</a>，发送自己的秋波</p>
                                </div>
                                <?php } else { ?>
                                <form name="delmemberid" action="index.php?n=service&h=leer" method="post" onsubmit="return checkPageGo('delleerid[]')">
                                    <?php foreach((array)$leers as $leer) {?>
                                   
                                    <ul class="service-liker-list">
                                        <li><input type="checkbox" name="delleerid[]" value="<?php echo $leer['l']['lid'];?>"/></li>
                                        <li>
                                            <div class="r-service-img">
                                                <?php if(!empty($leer['s']['city_star'])) { ?><a class="citystar2"><img src="module/service/templates/default/images/citystar.gif" /></a><?php } ?>
                                                <div class="r-s-img-in">
                                                    <p style="width:110px;"> <a style="display:block;" href="index.php?n=space&h=viewpro&uid=<?php echo $leer['l']['senduid'];?>" target="_blank">
                                                            <?php if($leer['s']['images_ischeck']=='1'&& $leer['s']['mainimg']) { ?>
                                                            <img src="<?php if(MooGetphoto($leer['s'][uid],'mid')) echo MooGetphoto($leer['s'][uid],'mid');
                                                                 elseif(MooGetphoto($leer['s'][uid],'medium')) echo MooGetphoto($leer['s'][uid],'medium');
                                                                 elseif($leer['s']['gender'] == '1') echo 'public/system/images/woman_100.gif';
                                                                 else echo 'public/system/images/man_100.gif';?>"/>
                                                            <?php } elseif ($leer['s']['mainimg']) { ?>
                                                            <?php if($leer['s']['gender'] == '1') { ?>
                                                            <img src="public/system/images/woman_100.gif"/>
                                                            <?php } else { ?>
                                                            <img src="public/system/images/man_100.gif"  />
                                                            <?php } ?>

                                                            <?php } else { ?>
                                                            <?php if($leer['s']['gender'] == '1') { ?>
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
                                                <dt><?php if($leer['s']['s_cid'] ==20) { ?>
                                                    <a href="index.php?n=payment&h=diamond" target="_blank"><img src="module/index/templates/default/images/zuan0.gif" title="钻石会员" alt="钻石会员"/></a>
                                                    <?php } elseif ($leer['s']['s_cid'] == 30) { ?>
                                                    <a href="index.php?n=payment" target="_blank"><img src="module/index/templates/default/images/zuan1.gif" title="高级会员" alt="高级会员"/></a>
                                                    <?php } else { ?>
                                                    <?php } ?>
                                                    <a id="fzs" href="index.php?n=space&h=viewpro&uid=<?php echo $leer['l']['senduid'];?>"><?php $nickname=$leer['s']['nickname2']?$leer['s']['nickname2']:$leer['s']['nickname']?><?php echo $nickname?$nickname:'ID:'.$leer['s']['uid']?></a>
                                                </dt>
                                                <dd><?php if($leer['s']['birthyear']) { ?><?php echo (gmdate('Y', time()) - $leer['s']['birthyear']);?>岁<?php } else { ?>年龄保密<?php } ?>，居住<?php if($leer['s'][province] == '0' && $leer['s'][city] == '0') { ?>保密<?php } else { ?>在<script>userdetail('<?php echo $leer['s'][province];?>',provice)</script><script>userdetail('<?php echo $leer['s'][city];?>',city)</script><?php } ?>的<?php echo $leer['s']['gender']?'女士':'男士'?>，寻找一位年龄<?php if($leer['t'][age1] == '0') { ?>不限<?php } else { ?>在<?php echo $leer['t'][age1];?>-<?php echo $leer['t'][age2];?>岁<?php } ?>，居住<?php if($leer['t'][workprovince] == '0' && $leer['t'][workcity] == '0') { ?>不限<?php } else { ?>在<script>userdetail("<?php echo $leer[t][workprovince];?>",provice);userdetail("<?php echo $leer['t'][workcity];?>",city)</script><?php } ?>的<?php echo $leer['s']['gender']?'男士':'女士'?>
                                                </dd>
                                                <dd class="f-ed0a91">&lt;<?php echo loveStatus($leer['s']);?>&gt;</dd>
                                            </dl>
                                        </li>
                                        <li style="float:right">
                                            <dl class="new-move">
                                                <?php if($leer['l']['stat'] == 0) { ?>
                                                <dd><?php echo date("Y年m月d日",$leer['l']['receivetime']);?>向您送出了第<?php echo $leer['l']['fakenum'] + $leer['l']['num']?>次秋波。</dd>

                                                <dd><strong>您可以选择</strong></dd>
                                                <dd>
                                                    <a href="index.php?n=service&h=leer&repeatleer=1&repeatleerid=<?php echo $leer['l']['senduid'];?>" class="f-ed0a91-a">接收</a>&nbsp;
                                                    <a href="index.php?n=service&h=leer&repeatleer=3&repeatleerid=<?php echo $leer['l']['senduid'];?>" class="f-ed0a91-a">考虑</a>&nbsp;
                                                    <a href="index.php?n=service&h=leer&refuseleer=1&refuseleerid=<?php echo $leer['l']['senduid'];?>" class="f-ed0a91-a">拒绝</a>&nbsp;
                                                </dd>
                                                <dt><input name="" type="button" class="btn btn-default" onclick="location.href='index.php?n=service&h=commission&t=sendcontact&sendid=<?php echo $leer['s'][uid];?>'" value="委托红娘联系TA" /></dt>
                                                <?php } ?>
                                                <?php if($leer['l']['stat'] == 1) { ?>
                                                <dd>您接收<?php echo $leer['s']['gender']?'她':'他';?>的秋波已成功</dd>
                                                <dd>您如此喜欢<?php echo $leer['s']['gender']?'她':'他';?>,强烈建议您：</dd>
                                                <dt><input name="" type="button" class="btn btn-default" onclick="location.href='index.php?n=service&h=commission&t=sendcontact&sendid=<?php echo $leer['s'][uid];?>'" value="委托红娘联系TA" /></dt>
                                                <?php } ?>
                                                <?php if($leer['l']['stat'] == 2) { ?>
                                                <dd><?php echo date("Y年m月d日",$leer['l']['receivetime']);?>向您送出了第<?php echo $leer['l']['num'] + $leer['l']['fakenum']?>次秋波。</dd>
                                                <dd>您已经委婉拒绝了对方的好意，如果您改变了主意，就主动给<?php echo $leer['s']['gender']?'她':'他';?><a href="index.php?n=service&h=leer&t=sendnewleer&sendtoid=<?php echo $leer['l']['senduid'];?>" target="_blank">发个秋波</a>吧</dd>
                                                <?php } ?>
                                                <?php if($leer['l']['stat'] == 3) { ?>
                                                <dd><?php echo date("Y年m月d日",$leer['l']['receivetime']);?>向您送出了第<?php echo $leer['l']['num'] + $leer['l']['fakenum']?>次秋波。</dd>
                                                <dd>您正在考虑对方的好意，如果您决定了，就主动给<?php echo $leer['s']['gender']?'她':'他';?><a href="index.php?n=service&h=leer&t=sendnewleer&sendtoid=<?php echo $leer['l']['senduid'];?>" target="_blank">发个秋波</a>吧</dd>
                                                <?php } ?>
                                            </dl>
                                        </li>
                                    </ul>
                                    <?php } ?>

                                    <div class="clear"></div>
                                    <div class="pages"><span class="fleft" style="padding-left:40px;"><input name="" type="checkbox" onclick="checkAll(this)" value="" />&nbsp;全选&nbsp;<input name="" type="submit" class="btn btn-default" value="删除信息" /></span><?php echo multimail($item_num,$page_per,$page_now,$page_url)?>转到第<input name="pageGo" id="pageGo" type="text" size="3" />页&nbsp;<input name="" type="button" class="go-page" value="GO" onclick="gotoPage()" style="float:none;"/>
                                        <div class="clear"></div>
                                        <input type="hidden"  name="delleer"  value="删除秋波">
                                    </div>
                                </form>
                                <?php } ?>
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
<script>
    // 切换tab
    function setTab(name,cursel,n) {

        if(cursel == 1) {
            window.location.href="index.php?n=service&h=leer";
        }
  
        if(cursel == 2) {
            window.location.href="index.php?n=service&h=leer&t=isendleer";		
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
        var checkboxes = document.getElementsByName("delleerid[]");
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

	//onload="javascript:DrawImage(this,100,125)" 
</script>
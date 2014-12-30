<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php echo $user['nickname']?$user['nickname']:'ID:'.$user['uid']?>的征婚交友信息——成就美好姻缘——真爱一生网</title>
        <?php include MooTemplate('system/js_css','public'); ?>
        <link type='text/css' rel='stylesheet' href='module/space/templates/default/liquidcarousel.css' />
        <link rel="stylesheet" type="text/css" href="module/space/templates/default/jquery.fancybox.css?v=2.1.5" media="screen" />
        <link rel="stylesheet" type="text/css" href="module/space/templates/default/jquery.fancybox-buttons.css?v=1.0.5" />
        <link href="module/space/templates/default/space.css" rel="stylesheet" type="text/css" />
        <link href="module/space/templates/default/common.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="module/space/templates/public/js/jquery.mousewheel-3.0.6.pack.js"></script>
        <script type="text/javascript" src="module/space/templates/public/js/jquery.fancybox.pack.js?v=2.1.5"></script>
        <script type="text/javascript" src="module/space/templates/public/js/jquery.liquidcarousel.pack.js"></script>
        <script type="text/javascript" src="module/space/templates/public/js/jquery.fancybox-buttons.js?v=1.0.5"></script>
    </head>


    <script language="javascript"> 

        window.onload=function(){
            var uid=<?php echo $userid;?>;
            if(typeof(uid)== "undefined" || uid == 0){
                $('#myModal .modal-header button').remove();
                openLogin();
            }
        };


        $(document).ready(function() {
            $('#liquid').liquidcarousel({
                height: 100,		//the height of the list
                duration: 100,		//the duration of the animation
                hidearrows: true	//hide arrows if all of the list items are visible
            });

            $('.fancybox-buttons').fancybox({
                openEffect  : 'none',
                closeEffect : 'none',

                prevEffect : 'none',
                nextEffect : 'none',

                closeBtn  : false,

                helpers : {
                    title : {
                        type : 'inside'
                    },
                    buttons	: {}
                },

                afterLoad : function() {
                    //this.title = 'Image ' + (this.index + 1) + ' of ' + this.group.length + (this.title ? ' - ' + this.title : '');
                    this.title = '第 ' + (this.index + 1) + ' 张， 共' + this.group.length +'张'+ (this.title ? ' - ' + this.title : '');
                }
            });
        });
    </script>


    <body>
        <div class="main">
            <div class="top">
                <!-- <span style="position:absolute;left:180px;top:10px;background:url('public/default/images/mediumfestival.png') no-repeat;width:50px;height:50px;"></span> -->

                <a href="index.php" class="logo" title="真爱一生网www.zhenaiyisheng.cc"></a>
                <?php if(!empty($userid)) { ?>
                <?php foreach((array)$skin_style as $n=>$v) {?>
                <?php $left=320+$n*25;?>
                <a href="javascript:void(0);" class="preview" title="<?php echo $v['skiname'];?>"     data-skin="<?php echo $v['skin_style'];?>"><span style="position:absolute;left:<?php echo $left;?>px;top:50px;background-color:<?php echo $v['color'];?>;width:15px;height:15px;border:1px solid #000;"></span></a>
                <?php }?>

                <?php } ?>
                <span class="top-tel"></span>
                <?php if($GLOBALS['MooUid']) { ?>
                <div class="top-title">欢迎您 <span class="f-ed0a91"><?php echo MooCutstr($GLOBALS["user_arr"]['nickname'],8,'')?> </span>
                    您的ID是 <span class="f-ed0a91"><?php echo $GLOBALS["user_arr"]['uid'];?></span> <a href="index.php?n=service&h=message" class="f-000">最新邮件</a>（<span class="f-ed0a91"><?php echo $message_total = header_show_total($GLOBALS["user_arr"]['uid']);?></span>） <a href="index.php?n=material&h=password" class="f-000">修改密码</a> <a href="./index.php?n=index&h=logout" class="f-000">安全退出</a></div>
                <?php } else { ?>
                <div class="top-title">您好，欢迎来到真爱一生网！[<a href="login.html" class="f-024890">请登录</a>] [<a href="register.html" class="f-024890">免费注册</a>]</div>

                <?php } ?>


                <div class="spaceHeader">
                    <ul>
                        <li><a href="index.html">首&nbsp;&nbsp;页</a><b></b></li>
                        <li><a href="service.html">我的真爱</a><b></b></li>
                        <li><a href="search.html">搜索</a><b></b></li>
                        <li><a href="material.html">我要征婚</a><b></b></li>
                        <li><a href="lovetest.html">爱情测评</a><b></b></li>
                        <li><a href="myaccount.html">信用认证</a><b></b></li>
                        <!-- <li><a href="vote.html">E见钟情</a><b></b></li> -->
                        <li><a href="introduce.html">服务介绍</a></li>
                    </ul>
                </div>
                <div class="iwanttovip"><a href="upgrade.html" title="升级成高级、钻石、铂金会员"></a></div>
            </div>

            <div class="content-right">

                <div class="rightbox">
                    <div class="rightdata">

                        <div class="c-rightbox">
                            <div class="right-tilte"><span class="r-title"><?php if($uid != $userid) { ?><?php echo $user['nickname']?MooCutstr($user['nickname'], 12, ''):'ID:'.$user['uid']?><?php } else { ?>我<?php } ?>的征婚资料</span></div>
                            <!--公共部分-->
                            <div class="pInfo">
                                <div class="myself">
                                    <p style="width:110px;height:138px;overflow:hidden;padding-top:5px;margin-left:8px;">
                                        <?php if($user['images_ischeck']=='1'&& $user['mainimg']) { ?>
                                        <img id="show_pic_1"  class="fixphoto_" src="<?php   
                                             if(MooGetphoto($user['uid'],'mid')) echo IMG_SITE.MooGetphoto($user['uid'],'mid');
                                             elseif($user['gender'] == '1')echo 'public/system/images/woman_1.gif';
                                             else  echo 'public/system/images/man_1.gif';
                                             ?>" flag=''/></a>
                                        <?php } elseif ($user['mainimg']) { ?>
                                        <?php if($user['gender'] == '1') { ?>
                                        <img id="show_pic_1" src="public/system/images/woman.gif"/>
                                        <?php } else { ?>
                                        <img id="show_pic_1" src="public/system/images/man.gif" />
                                        <?php } ?>
                                        </a>
                                        <?php } else { ?>                                        
                                        <?php if($user['gender'] == '1') { ?>
                                        <img id="show_pic_1" src="public/system/images/service_nopic_woman.gif"/>
                                        <?php } else { ?>
                                        <img  id="show_pic_1" src="public/system/images/service_nopic_man.gif"/>
                                        <?php } ?>
                                        </a>
                                        <?php } ?> 
                                    </p>
                                </div>

                                <div class="vmcd">
                                    <div>						
                                        <div>
                                            <span class="f-ed0a91">
                                                <?php if($user['s_cid'] == 20) { ?>
                                                <a href="#"><img src="module/space/templates/default/images/zuan0.gif" /></a>
                                                <?php } elseif ($user['s_cid'] == 30) { ?>
                                                <a href="#"><img src="module/space/templates/default/images/zuan1.gif" /></a></span>
                                            <?php } ?>
                                            <span class="myname"><?php echo $user['nickname']?$user['nickname']:'ID:'.$user['uid']?></span>&nbsp;<span class="memberid">（ID：<?php echo $user['uid'];?>）</span>	
                                            <span>诚信度：</span>
                                            <?php echo get_integrity($user['certification']);?>
                                        </div>

                                    </div>

                                    <div><?php echo activetime($user['lastvisit'],$user['usertype']);?>，<?php if($user['birthyear']) { ?><?php echo (date('Y', time()) - $user['birthyear']);?>岁<?php } else { ?>年龄保密<?php } ?>，<?php echo loveStatus($status);?>，居住在<span class="address"><?php if($user['province'] == '0' && $user['city'] == '0') { ?>保密<?php } else { ?><script>userdetail('<?php echo $user['province'];?>',provice)</script><script>userdetail('<?php echo $user['city'];?>',city)</script><?php } ?></span>的<script>userdetail('<?php echo $user['gender'];?>',sex)</script>，寻找一位年龄<?php if($c['age1'] == '0') { ?>不限的<?php } else { ?>在<script>userdetail('<?php echo $c['age1'];?>',age)</script>-<script>userdetail('<?php echo $c['age2'];?>',age)</script>岁<?php } ?><?php if($user['gender'] == 0) { ?>女士<?php } else { ?>男士<?php } ?>。</div>
                                    <div>
                                        <ul class="right-cer">
                                            <?php if(!$usercer['telphone']) { ?>
                                            <li class="cer-phone-no"><a href="index.php?n=myaccount&h=telphone" title="现在就去验证号码">未通过号码验证</a></li>
                                            <?php } else { ?>
                                            <li class="cer-phone-yes">已通过号码验证</li>
                                            <?php } ?>
                                            <?php if(!$usercer['sms']) { ?>
                                            <li class="cer-card-no"><a href="index.php?n=myaccount&h=smsindex" title="现在就去身份通认证">未通过身份通验证</a></li>
                                            <?php } else { ?>
                                            <li class="cer-card-yes">已通过身份通认证</li>
                                            <?php } ?>
                                            <?php if($usercer['email'] != 'yes') { ?>
                                            <li class="cer-email-no"><a href="index.php?n=myaccount&h=emailindex" title="现在就去邮箱认证">未通过邮箱验证</a></li>
                                            <?php } else { ?>
                                            <li class="cer-email-yes">已通过邮箱认证</li>
                                            <?php } ?>
                                            <?php if($usercer['video_check']!=3) { ?>
                                            <li class="cer-video-no"><a href="index.php?n=myaccount&h=videoindex" title="现在就去视频认证">未通过视频验证</a></li>
                                            <?php } else { ?>
                                            <li class="cer-video-yes">已进行视频认证</li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                    <div class="clear"></div>
                                    <div>
                                        <?php if($usercer['identity_check'] == 3 || $usercer['education_check'] == 3 || $usercer['occupation_check'] == 3 || $usercer['salary_check'] == 3 || $usercer['house_check'] == 3 || $usercer['marriage_check'] == 3) { ?>
                                        <span class="f-ed0a91">已认证证件：</span><?php if($usercer['identity_check'] == 3) { ?>身份证   <?php } ?><?php if($usercer['education_check'] == 3) { ?>学历证   <?php } ?><?php if($usercer['occupation_check'] == 3) { ?>工作证   <?php } ?><?php if($usercer['salary_check'] == 3) { ?>工资证   <?php } ?><?php if($usercer['house_check'] == 3) { ?>房产证 <?php } ?><?php if($usercer['marriage_check'] == 3) { ?>婚育证<?php } ?> 
                                        <?php } else { ?>
                                        <span class="f-ed0a91">已认证证件：</span>暂未验证任何证件 
                                        <?php } ?>
                                    </div>

                                </div>
                            </div>
                            <!--公共部分-->
                        </div>

                        <div class="right-base-data">
                            <h4><?php if($userid == $uid) { ?><a href="index.php?n=material&h=show" class="f-ed0a91-a">修改</a><?php } ?>相册</h4>
							<?php if($user_pic) { ?>
                            <div id="liquid" class="liquid">
                                <span class="previous"></span>
                                <div class="wrapper">
                                    <ul>
                                        <?php foreach((array)$user_pic as $k=>$v) {?> 
                                        <li><a  class="fancybox-buttons" data-fancybox-group="button" href="<?php echo IMG_SITE.$v['imgurl']?>" ><img src="<?php echo thumbImgPath('2',$v['pic_date'],$v['pic_name']);?>"   /></a></li>
                                        <?php }?>
                                    </ul>
                                </div>
                                <span class="next"></span>
                            </div>
							<?php } else { ?>
							     <a href='index.php?n=material&h=show'>您还没有形象照，请上传！！！</a>
							<?php } ?>
                        </div>

                        <div class="right-base-data"> 
                            <h4><?php if($userid == $uid) { ?><a href="index.php?n=material&h=upinfo&#person_introduce" class="f-ed0a91-a">修改</a><?php } ?>内心独白</h4>				
                            <p class="introduce">
                                <?php $u = $_GET['uid']?>
                                <?php if(!$u) { ?>1
                                <?php if($c['introduce'] != '') { ?>
                                <?php echo Moorestr($c['introduce'])?>
                                <?php } elseif ($c['introduce_check'] != '') { ?>
                                <?php echo Moorestr($c['introduce_check'])?>
                                （<font class="f-ed0a91-a" style="text-decoration:none;">正在审核中</font>）
                                <?php } else { ?>
                                您还没有内心独白<font style="cursor:pointer;" color="#c10202" onclick="javascript:location.href='index.php?n=material&h=upinfo'"> 马上填写</font>
                                <?php } ?>           
                                <?php } else { ?>
                                <?php echo $c['introduce']?MooStrReplace(Moorestr($c['introduce'])):'还没有填写内心独白内容'?>
                                <?php } ?>
                            </p>
                        </div>

                        <?php if($user['gender'] == '1') { ?>
                        <?php $TA = '她'?>
                        <?php } else { ?>
                        <?php $TA = '他'?>
                        <?php } ?>

                        <div class="right-base-data">

                            <h4 ><?php if($userid == $uid) { ?><a href="index.php?n=material&h=upinfo#person_message" class="f-ed0a91-a">修改</a><?php } ?>
                                <span id="vf_01" class="t01">基本资料</span>

                                <span id="vf_02" class="t02">择偶要求</span>

                                <span id="vf_03" class="t02">生活方式</span>

                                <span id="vf_04" class="t02">兴趣爱好</span>
                            </h4>	

                            <div class="baseinfo" id="vf_01_">
                                <ul>
                                    <li>性&nbsp;&nbsp;&nbsp;&nbsp;别：<script>userdetail('<?php echo  $user['gender']?1:0?>',sex);</script></li>
                                    <li>年&nbsp;&nbsp;&nbsp;&nbsp;龄：<?php if($user['birthyear']) { ?><?php echo (date('Y', time()) - $user['birthyear']);?>岁<?php } else { ?>保密<?php } ?>
                                        <?php if($user2) { ?><?php if(($c2['age1'] == '0' and $c2['age2'] =='0') || ($c2['age1'] == '0' and  (date('Y', time()) - $user['birthyear'])< $c2['age2']) || ($c2['age1'] < (date('Y', time()) - $user['birthyear']) and  (date('Y', time()) - $user['birthyear'])< $c2['age2']) || ($c2['age1'] < (date('Y', time()) - $user['birthyear']) and  $c2['age2'] == '0')) { ?><font title="<?php echo $TA;?>的年龄符合您择偶要求"><img src="public/system/images/123.gif" alt="<?php echo $TA;?>的年龄符合您择偶要求"></font><?php } ?><?php } ?>
                                    </li>
                                    <li>宗教信仰：<?php if($user['religion'] == '0') { ?>保密<?php } else { ?><script>userdetail('<?php echo $user['religion'];?>',belief)</script><?php } ?></li>
                                    <li>身&nbsp;&nbsp;&nbsp;&nbsp;高：<?php if($user['height'] == '0') { ?>保密<?php } else { ?><script>userdetail('<?php echo $user['height'];?>',height)</script>厘米<?php } ?>
                                        <?php if($user2) { ?><?php if(($c2['height1'] == '0' and $c2['height2'] =='0') || ($c2['height1'] == '0' and  ($user['height'])< $c2['height2']) || ($c2['height1'] < ($user['height']) and  ($user['height'])< $c2['height2']) || ($c2['height1'] < ($user['height']) and  $c2['height2'] == '0')) { ?><font title="<?php echo $TA;?>的身高符合您择偶要求"><img src="public/system/images/123.gif" alt="<?php echo $TA;?>的身高符合您择偶要求"></font><?php } ?><?php } ?>
                                    </li>
                                    <li>体&nbsp;&nbsp;&nbsp;&nbsp;重：<?php if($user['weight'] == '0') { ?>保密<?php } else { ?><script>userdetail('<?php echo $user['weight'];?>',weight)</script>公斤<?php } ?>
                                        <?php if($user2) { ?><?php if(($c2['weight1'] == '0' and $c2['weight2'] =='0') || ($c2['weight1'] == '0' and  ($user['weight'])< $c2['weight2']) || ($c2['weight1'] < ($user['weight']) and  ($user['weight'])< $c2['weight2']) || ($c2['weight1'] < ($user['weight']) and  $c2['weight2'] == '0')) { ?><font title="<?php echo $TA;?>的体重符合您择偶要求"><img src="public/system/images/123.gif" alt="<?php echo $TA;?>的体重符合您择偶要求"></font><?php } ?><?php } ?>
                                    </li>
                                    <li>学&nbsp;&nbsp;&nbsp;&nbsp;历：<?php if($user['education'] == '0') { ?>保密<?php } else { ?><script>userdetail('<?php echo $user['education'];?>',education)</script><?php } ?>
                                        <?php if($user2) { ?><?php if($c2['education'] == '0' || ($c2['education'] ==  $user['education'])) { ?><font title="<?php echo $TA;?>的学历符合您择偶要求"><img src="public/system/images/123.gif" alt="<?php echo $TA;?>的学历符合您择偶要求"></font><?php } ?><?php } ?>
                                    </li>
                                    <li>民&nbsp;&nbsp;&nbsp;&nbsp;族：<?php if($user['nation'] == '0') { ?>保密<?php } else { ?><script>userdetail('<?php echo $user['nation'];?>',stock)</script><?php } ?></li>
                                    <li>所在地区：<?php if($user['province'] == '0' && $user['city'] == '0') { ?>保密<?php } else { ?><script>userdetail('<?php echo $user['province'];?>',provice)</script><script>userdetail('<?php echo $user['city'];?>',city)</script><?php } ?>
                                        <?php if($user2) { ?><?php if(($c2['workprovince']<>'0' && $c2['workcity']<>'0')) { ?><?php if(($c2['workprovince'] ==  $user['province']) && $c2['workcity'] == $user['city']) { ?><font title="<?php echo $TA;?>的所在地区符合您择偶要求"><img src="public/system/images/123.gif" alt="<?php echo $TA;?>的所在地区符合您择偶要求"></font><?php } ?><?php } elseif ($c2['workprovince']<>'0') { ?><?php if($c2['workprovince']==$user['province']) { ?><font title="<?php echo $TA;?>的所在地区符合您择偶要求"><img src="public/system/images/123.gif" alt="<?php echo $TA;?>的所在地区符合您择偶要求"></font><?php } ?><?php } elseif ($c2['workprovince'] =='0') { ?><font title="<?php echo $TA;?>的所在地区符合您择偶要求"><img src="public/system/images/123.gif" alt="<?php echo $TA;?>的所在地区符合您择偶要求"></font><?php } ?><?php } ?>
                                    </li>
                                    <li>籍&nbsp;&nbsp;&nbsp;&nbsp;贯：<?php if($user['hometownprovince'] == '0' && $user['hometowncity'] == '0') { ?>保密<?php } else { ?><script>userdetail('<?php echo $user['hometownprovince'];?>',provice)</script><script>userdetail('<?php echo $user['hometowncity'];?>',city)</script><?php } ?>
                                        <?php if($user2) { ?><?php if(($c2['hometownprovince']<>'0' && $c2['hometowncity']<>'0')) { ?><?php if(($c2['hometownprovince'] ==  $user['hometownprovince']) && $c2['hometowncity'] == $user['hometowncity']) { ?><font title="<?php echo $TA;?>的户口所在地符合您择偶要求"><img src="public/system/images/123.gif" alt="<?php echo $TA;?>的户口所在地符合您择偶要求"></font><?php } ?><?php } elseif ($c2['hometownprovince']<>'0') { ?><?php if($c2['hometownprovince']==$user['hometownprovince']) { ?><font title="<?php echo $TA;?>的户口所在地符合您择偶要求"><img src="public/system/images/123.gif" alt="<?php echo $TA;?>的户口所在地符合您择偶要求"></font><?php } ?><?php } elseif ($c2['hometownprovince'] =='0') { ?><font title="<?php echo $TA;?>的户口所在地符合您择偶要求"><img src="public/system/images/123.gif" alt="<?php echo $TA;?>的户口所在地符合您择偶要求"></font><?php } ?><?php } ?>
                                    </li>
                                    <li>职&nbsp;&nbsp;&nbsp;&nbsp;业：<?php if($user['occupation'] == '0') { ?>保密<?php } else { ?><script>userdetail('<?php echo $user['occupation'];?>',occupation)</script><?php } ?>
                                        <?php if($user2) { ?><?php if($c2['occupation']<>'0') { ?><?php if($c2['occupation'] ==  $user['occupation']) { ?><font title="<?php echo $TA;?>的职业符合您择偶要求"><img src="public/system/images/123.gif" alt="<?php echo $TA;?>的职业符合您择偶要求"></font><?php } ?><?php } elseif ($c2['occupation']=='0') { ?><font title="<?php echo $TA;?>的职业符合您择偶要求"><img src="public/system/images/123.gif" alt="<?php echo $TA;?>的职业符合您择偶要求"></font><?php } ?><?php } ?>
                                    </li>
                                    <li>婚姻状况：<?php if($user['marriage'] == '0') { ?>保密<?php } else { ?><script>userdetail('<?php echo $user['marriage'];?>',marriage)</script><?php } ?>
                                        <?php if($user2) { ?><?php if($c2['marriage']<>'0') { ?><?php if($c2['marriage'] ==  $user['marriage']) { ?><font title="<?php echo $TA;?>的婚姻状况符合您择偶要求"><img src="public/system/images/123.gif" alt="<?php echo $TA;?>的婚姻状况符合您择偶要求"></font><?php } ?><?php } elseif ($c2['marriage']=='0') { ?><font title="<?php echo $TA;?>的婚姻状况符合您择偶要求"><img src="public/system/images/123.gif" alt="<?php echo $TA;?>的婚姻状况符合您择偶要求"></font><?php } ?><?php } ?>
                                    </li>
                                    <li>生&nbsp;&nbsp;&nbsp;&nbsp;肖：<?php if($user['birthyear'] == '0') { ?>保密<?php } else { ?><?php echo get_signs($user['birthyear'])?><?php } ?></li>
                                    <li>公司类型：<?php if($user['corptype'] == '0') { ?>保密<?php } else { ?><script>userdetail('<?php echo $user['corptype'];?>',corptype)</script><?php } ?></li>
                                    <li>是否有孩子：<?php if($user['children'] == '0') { ?>保密<?php } else { ?><script>userdetail('<?php echo $user['children'];?>',children)</script><?php } ?>
                                        <?php if($user2) { ?><?php if($c2['children']<>'0') { ?><?php if($c2['children'] ==  $user['children']) { ?><font title="<?php echo $TA;?>的有无孩子符合您择偶要求"><img src="public/system/images/123.gif" alt="<?php echo $TA;?>的有无孩子符合您择偶要求"></font><?php } ?><?php } elseif ($c2['children']=='0') { ?><font title="<?php echo $TA;?>的有无孩子符合您择偶要求"><img src="public/system/images/123.gif" alt="<?php echo $TA;?>的有无孩子符合您择偶要求"></font><?php } ?><?php } ?>
                                    </li>
                                    <li>星&nbsp;&nbsp;&nbsp;&nbsp;座：<?php if($user['constellation'] == '0') { ?>保密<?php } else { ?><script>userdetail('<?php echo $user['constellation'];?>',constellation)</script><?php } ?></li>
                                    <li>居住情况：<?php if($user['house'] == '0') { ?>保密<?php } else { ?><script>userdetail('<?php echo $user['house'];?>',house)</script><?php } ?></li>
                                    <li>月&nbsp;&nbsp;&nbsp;&nbsp;薪：<?php if($user['salary'] == '0') { ?>保密<?php } else { ?><script>userdetail('<?php echo $user['salary'];?>',salary1)</script><?php } ?>
                                        <?php if($user2) { ?><?php if($c2['salary']<>'0') { ?><?php if($c2['salary'] ==  $user['salary']) { ?><font title="<?php echo $TA;?>的月薪符合您择偶要求"><img src="public/system/images/123.gif" alt="<?php echo $TA;?>的月薪符合您择偶要求"></font><?php } ?><?php } elseif ($c2['salary']=='0') { ?><font title="<?php echo $TA;?>的月薪符合您择偶要求"><img src="public/system/images/123.gif" alt="<?php echo $TA;?>的月薪符合您择偶要求"></font><?php } ?><?php } ?>
                                    </li>
                                    <li>血&nbsp;&nbsp;&nbsp;&nbsp;型：<?php if($user['bloodtype'] == '0') { ?>保密<?php } else { ?><script>userdetail('<?php echo $user['bloodtype'];?>',bloodtype)</script><?php } ?></li>
                                    <li>购车情况：<?php if($user['vehicle'] == '0') { ?>保密<?php } else { ?><script>userdetail('<?php echo $user['vehicle'];?>',vehicle)</script><?php } ?></li>
                                    <li>兄弟姐妹：<?php if($user['family'] == '0') { ?>保密<?php } else { ?><script>userdetail('<?php echo $user['family'];?>',family)</script><?php } ?></li>
                                    <li>绑定情况：<?php if($user['isbind'] == '1') { ?><span style="color:#F00">绑定中...</span><?php } else { ?>未绑定<?php } ?></li>
                                    <li style="width:auto">语言能力：<?php $lg = explode(",",$user['language']);?>
                                        <?php foreach((array)$lg as $v) {?>
                                        <?php if($v== '') { ?>
                                        保密
                                        <?php } else { ?>
                                        <script>userdetail('<?php echo $v;?>',tonguegifts)</script>
                                        <?php } ?>
                                        <?php } ?>
                                    </li>      
                                </ul>
                            </div>

                            <div class="spouse h" id="vf_02_">
                                <ul>
                                    <li>年&nbsp;&nbsp;&nbsp;&nbsp;龄：<?php if($c['age1'] == '0') { ?>不限<?php } else { ?><script>userdetail('<?php echo $c['age1'];?>',age)</script>~<script>userdetail('<?php echo $c['age2'];?>',age)</script>岁 <?php } ?></li>
                                    <li>身&nbsp;&nbsp;&nbsp;&nbsp;高：<?php if($c['height1'] == '0') { ?>不限<?php } else { ?><script>userdetail('<?php echo $c['height1'];?>',height)</script>~<script>userdetail('<?php echo $c['height2'];?>',height)</script>厘米<?php } ?> </li>
                                    <li>体&nbsp;&nbsp;&nbsp;&nbsp;重：<?php if($c['weight1'] == '0') { ?>不限<?php } else { ?><script>userdetail('<?php echo $c['weight1'];?>',weight)</script>~<script>userdetail('<?php echo $c['weight2'];?>',weight)</script>公斤<?php } ?></li>
                                    <li>体&nbsp;&nbsp;&nbsp;&nbsp;型：<?php if($c['body'] == '0') { ?>不限<?php } else { ?><script>userdetail('<?php echo $c['body'];?>',body<?php echo $user['gender']?0:1;?>)</script><?php } ?></li>
                                    <li>是否有照片：<?php if($c['hasphoto'] == '1') { ?>有<?php } else { ?>无<?php } ?></li>
                                    <li>征友地区：<?php if($c['hometownprovince'] == '0' && $c['hometowncity'] == '0') { ?>不限<?php } else { ?><script>userdetail(<?php echo $c['hometownprovince'];?>,provice)</script><script>userdetail(<?php echo $c['hometowncity'];?>,city)</script><?php } ?></li>
                                    <li>教育程度：<?php if($c['education'] == '0') { ?>不限<?php } else { ?><script>userdetail('<?php echo $c['education'];?>',education)</script><?php } ?></li>
                                    <li>职&nbsp;&nbsp;&nbsp;&nbsp;业：<?php if($c['occupation'] == '0') { ?>不限<?php } else { ?><script>userdetail('<?php echo $c['occupation'];?>',occupation)</script><?php } ?></li>
                                    <li>月&nbsp;&nbsp;&nbsp;&nbsp;薪：<?php if($c['salary'] == '0') { ?>不限<?php } else { ?><script>userdetail('<?php echo $c['salary'];?>',salary1)</script><?php } ?></li>
                                    <li>工&nbsp;作&nbsp;地：<?php if($c['workprovince'] == '0' && $c['workcity'] == '0') { ?>不限<?php } else { ?><script>userdetail('<?php echo $c['workprovince'];?>',provice)</script><script>userdetail('<?php echo $c['workcity'];?>',city)</script><?php } ?></li>
                                    <li>是否抽烟：<?php if($c['smoking'] == '0') { ?>不限<?php } else { ?><script>userdetail('<?php echo $c['smoking'];?>',isSmoking)</script><?php } ?></li>
                                    <li>婚姻状况：<?php if($c['marriage'] == '0') { ?>不限<?php } else { ?><script>userdetail('<?php echo $c['marriage'];?>',marriage)</script><?php } ?></li>
                                    <li>是否有孩子：<?php if($c['children'] == '0') { ?>不限<?php } else { ?><script>userdetail('<?php echo $c['children'];?>',children)</script><?php } ?></li>
                                    <li>是否要孩子：<?php if($c['wantchildren'] == '0') { ?>不限<?php } else { ?><script>userdetail('<?php echo $c['wantchildren'];?>',wantchildren)</script><?php } ?></li>
                                </ul>
                            </div>


                            <div class="livings h" id="vf_03_">
                                <ul>
                                    <li>
                                        是否吸烟：<?php if($user['smoking'] == '0') { ?>保密<?php } else { ?><script>userdetail('<?php echo $user['smoking'];?>',isSmoking)</script><?php } ?>
                                        <?php if($user2) { ?><?php if($c2['smoking']<>'0') { ?><?php if($c2['smoking'] ==  $user['smoking']) { ?><font title="<?php echo $TA;?>的是否吸烟符合您择偶要求"><img src="public/system/images/123.gif" alt="<?php echo $TA;?>的是否吸烟符合您择偶要求"></font><?php } ?><?php } elseif ($c2['smoking']=='0') { ?><font title="<?php echo $TA;?>的是否吸烟符合您择偶要求"><img src="public/system/images/123.gif" alt="<?php echo $TA;?>的是否吸烟符合您择偶要求"></font><?php } ?><?php } ?>
                                    </li>
                                    <li>
                                        是否饮酒：<?php if($user['drinking'] == '0') { ?>保密<?php } else { ?>
                                        <script>userdetail('<?php echo $user['drinking'];?>',isDrinking)</script><?php } ?>
                                    </li>
                                </ul>	
                                <p>喜欢的美食：<?php $fd = explode(",",$user['fondfood']);?>
                                    <?php foreach((array)$fd as $v) {?>
                                    <?php if($v == '0') { ?>
                                    保密
                                    <?php } else { ?>
                                    <script>userdetail('<?php echo $v;?>',fondfoods)</script>
                                    <?php } ?>
                                    <?php } ?>

                                </p>
                                <p>喜欢的地方：<?php $fd = explode(",",$user['fondplace']);?>
                                    <?php foreach((array)$fd as $v) {?>
                                    <?php if($v == '0') { ?><span class="w350">保密</span><?php } else { ?>
                                    <script>userdetail('<?php echo $v;?>',fondplaces)</script>
                                    <?php } ?>
                                    <?php } ?>
                                </p>				
                            </div>


                            <div class="favoriate h" id="vf_04_">				
                                <p >喜欢的运动：
                                    <?php $fda = explode(",",$user['fondsport']);?>
                                    <?php foreach((array)$fda as $v) {?>
                                    <?php if($v == '0') { ?><span class="w350">保密</span><?php } else { ?>
                                    <script>userdetail('<?php echo $v;?>',fondsports)</script>
                                    <?php } ?>
                                    <?php } ?>
                                </p>
                                <p>喜欢的活动：
                                    <?php $fda = explode(",",$user['fondactivity']);?>
                                    <?php foreach((array)$fda as $v) {?>
                                    <?php if($v == '0') { ?><span class="w350">保密</span><?php } else { ?>
                                    <script>userdetail('<?php echo $v;?>',fondactions)</script>
                                    <?php } ?>
                                    <?php } ?> 
                                </p>				
                                <p>喜欢的音乐：
                                    <?php $fda = explode(",",$user['fondmusic']);?>
                                    <?php foreach((array)$fda as $v) {?>
                                    <?php if($v == '0') { ?><span class="w350">保密</span><?php } else { ?>
                                    <script>userdetail('<?php echo $v;?>',fondmusics)</script>
                                    <?php } ?>
                                    <?php } ?> 
                                </p>
                                <p>喜欢的影视：
                                    <?php $fda = explode(",",$user['fondprogram']);?>
                                    <?php foreach((array)$fda as $v) {?>
                                    <?php if($v == '0') { ?><span class="w350">保密</span><?php } else { ?>
                                    <script>userdetail('<?php echo $v;?>',fondprograms)</script>
                                    <?php } ?>
                                    <?php } ?>
                                </p>
                            </div>

                        </div>
                        <!-- <div style="margin-top:10px;"><img src="module/space/templates/default/images/mediumfestival.jpg" /></div> -->
                        
						<?php if(in_array($user_arr['s_cid'],array(10,20,30)) ) { ?>
                        <div class="right-base-data"> 
                            <h4><?php if($userid == $uid) { ?><a href="index.php?n=material&h=music" class="f-ed0a91-a">修改</a><?php } ?>音乐</h4>	
							<?php if($isMusic) { ?>
                                 <div style="margin-left:20px;"><audio autoplay controls loop src="<?php echo MOOPHP_HOST.'/'.$music_url;?>">您的浏览器不支持播放，请升级高版本浏览器（如IE9、Firefox3.5、Chrome4以上）</audio>	</div>	
                            <?php } else { ?>
                                 <div style="margin:0px 0px 10px 20px;"><a class="bgmusic" href="index.php?n=material&h=music">设置背景音乐</a></div>	
                            <?php } ?>							
						</div>
						<?php } else { ?>
						<div class="right-base-data"> 
                            <h4>音乐</h4>	
                            <div style="margin:0px 0px 10px 20px;"><a href="upgrade.html" class="bgmusic" title="请升级为高级会员">音乐背景只对VIP会员支持，如果您想设置征婚页有音乐背景，请先升级为VIP会员或充值！</a></div>						
						</div>
						<?php } ?>
                    </div>

                </div>



            </div>
            <?php include MooTemplate('public/space_leftmenu_new','module'); ?>
            <div class="clear"></div>
            <!--content end-->
            <?php include MooTemplate('system/footer_profile','public'); ?>
        </div><!--main end-->

        <script type="text/javascript" src="module/payment/templates/default/js/scrollDoor.js?v=1.0"></script>
        <script type="text/javascript">
            window.onload = function(){var SDmodel = new scrollDoor();SDmodel.sd(["vf_01","vf_02","vf_03","vf_04"],["vf_01_","vf_02_","vf_03_","vf_04_"],"t01","t02");}
            $('.wrapper li a').on('click',function(){var uid=<?php echo $GLOBALS['MooUid'];?>;if(!uid){openLogin();return false;}});
            $('.preview').on('click',function(){var skiname=$(this).attr('data-skin');window.location.href='space_'+skiname+'.html';})
            var url="<?php echo $url;?>";
            if(url){$.ajax({url:url,type:'post',data:{uid:<?php echo $uid;?>},dataType:'text',success:function(){}});}
        </script>
    </body>
</html>
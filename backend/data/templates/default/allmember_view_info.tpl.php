<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="templates/css/general.css" rel="stylesheet" type="text/css" />
        <link href="templates/css/main.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="templates/js/jquery-1.8.3.min.js"></script>
        <script type="text/javascript" src="../public/system/js/sys1.js?v=1"></script>
        <script type="text/javascript" src="templates/js/My97DatePicker/WdatePicker.js"></script>
        <link href="templates/css/allmember_viewinfo.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript">
            //显示用户信息
            function userdetail(number,arrayobj) {
                var arrname = arrayobj;
				
				if(!arrayobj) return;
                for(i=0;i<arrayobj.length;i++) {
                    var valueArray  = arrayobj[i].split(",");
                    if(valueArray[0] == number) {
                        if(valueArray[0] == '0') {
                            document.write("未选择");
                        } else {
                            document.write(valueArray[1]);
                        }	
                    }
                }
            }
			
			
			function userdetail_(number,arrayobj) {
                var arrname = arrayobj;
				
				if(!arrayobj) return;
                for(i=0;i<arrayobj.length;i++) {
                    var valueArray  = arrayobj[i].split(",");
                    if(valueArray[0] == number) {
                        if(valueArray[0] == '0') {
                            return '';
                        } else {
                            return valueArray[1];
                        }	
                    }
                }
            }

            function get(n,a){
                //	if( $("#item"+a).html() != "" ) return;
                $("#item"+a).html("<div style='text-align:center'><br/>页面加载中...</div>");
                var sid=<?php echo $member['sid'];?>;	
                var url = './allmember_ajax.php?n='+n+'&uid=<?php echo $uid;?>'+'&sid='+sid;
                $.get(url,function(data){  
                    $("#item"+a).html(data);
                });
            }

            <?php if($adminright == 'sellpass') { ?>
            <?php if($member['s_cid'] < 40 && $member['usertype'] < 3) { ?>
            function get_sell(n,a){
                if( $("#item"+a).html() != "" ) return;
                $("#item"+a).html("<div style='text-align:center'><br/>页面加载中...</div>");	
                var url = './allmember_ajax.php?n='+n+'&uid=<?php echo $uid;?>'+'&rand='+Math.random();
                $.get(url,function(data){  
                    $("#item"+a).html(data);
                });
            }
            <?php } ?>
            <?php } ?>


            //模拟用户登录
            function thisLogin(){
                var uid = prompt("请输入查看用户ID","<?php echo $member['uid'];?>");
                var adminid=<?php echo $GLOBALS['adminid'];?>;
                if  (adminid=='52') return false;
                if(isNaN(uid) || uid == null || uid.replace(/\s/g,"") == ""){
                    return;	
                }
                var url = "./active_ajax.php?n=this_login&luid="+uid;
                $.get(url,function(str){
                    if(str == "ok"){
                        window.open("../index.php?n=space&h=viewpro&uid=<?php echo $member['uid'];?>");
                        $("#kefulogin").show();
                        $("#kefulogin a").bind('click',function(){$("#kefulogin a").hide()})
                        $("#kefulogin a").attr('href',"../index.php?n=space&h=viewpro&uid=<?php echo $member['uid'];?>");
                    }else{
                        alert(str);
                    }   
                })
            }


            //重点会员
            function updateMaster(id){
                var url = "./myuser_ajax.php?n=important";
                var effect_grade="<?php echo isset($member_admininfo['effect_grade'])?$member_admininfo['effect_grade']:'';?>";
                var next_contact_time="<?php echo isset($member_admininfo['next_contact_time'])?$member_admininfo['next_contact_time']:''?>";
                $.post(url,{uid:id,effect_grade:effect_grade,next_contact_time:next_contact_time},function(data){
                    if(data=='ok'){
                        alert("更新成功: " + data);
                    }else{
                        alert("更新失败: " + data);
                    }
                });    
            }


            function enneagramEmpty(){
                var url = "./allmember_ajax.php?n=enneagram&h=update&uid=<?php echo $member['uid'];?>&type=0";
                $.get(url,function(data){
                    if(data == 1) {
                        $("#display_enneagram").css("display","none");
                    }
                })
            }

            //note 九型列表
            function enneagram(){	
                if($("#enneagram_list").css("display") == "none"){
                    $("#enneagram_list").css("display","");
                }else{
                    $("#enneagram_list").css("display","none");
                }
            }

            //note 九型ajax单个展示对策
            function enneagram_list_ajax(num){
                var url = "./allmember_ajax.php?n=enneagram&h=list&type="+num;
                $.get(url,function(data){
                    $("body").append(data);
                });
            }



            //note 初始化
            $(function(){
                $("a.zoom").fancybox();
            })
            $(function(){
                $(".table_list ul:odd").css("background","#e6f0fd");
                $(".table_list ul:even").css("background","#CEDAEA");	
                $("div[id^=item]").css("display","none");
                $("div[id^=item1]").css("display","block");
                //三分钟提醒
                $("body").mousemove(function(){
                    parent.mousemove_3minutes_remark();
                })
            })

            //note 九型ajax更改用户类型
            function enneagram_update_ajax(num){
                var url = "./allmember_ajax.php?n=enneagram&h=update&uid=<?php echo $member['uid'];?>&type="+num;
                $.get(url,function(data){

                    if(data == 1) {
                        $("#display_enneagram").css("display","");
                        switch(num) {
                            case 1:
                                $("#display_enneagram").html("一号完美型[<a href=\"javascript:enneagramEmpty();\">清空</a>]");
                                break;
                            case 2:
                                $("#display_enneagram").html("二号助人型[<a href=\"javascript:enneagramEmpty();\">清空</a>]");
                                break;
                            case 3:
                                $("#display_enneagram").html("三号成就型[<a href=\"javascript:enneagramEmpty();\">清空</a>]");
                                break;
                            case 4:
                                $("#display_enneagram").html("四号自我型[<a href=\"javascript:enneagramEmpty();\">清空</a>]");
                                break;
                            case 5:
                                $("#display_enneagram").html("五号理智型[<a href=\"javascript:enneagramEmpty();\">清空</a>]");
                                break;
                            case 6:
                                $("#display_enneagram").html("六号忠诚型[<a href=\"javascript:enneagramEmpty();\">清空</a>]");
                                break;
                            case 7:
                                $("#display_enneagram").html("七号活跃型[<a href=\"javascript:enneagramEmpty();\">清空</a>]");
                                break;
                            case 8:
                                $("#display_enneagram").html("八号领袖型[<a href=\"javascript:enneagramEmpty();\">清空</a>]");
                                break;
                            case 9:
                                $("#display_enneagram").html("九号和平型[<a href=\"javascript:enneagramEmpty();\">清空</a>]");
                                break;
                        }
                    }else{
                        alert("update failed！");
                    }
                });
            }

            //note 显示9型列表	
            $(function() {
                $("#enneagram_list").find("img").each(function(i){
                    $(this).click(function(){
                        enneagram_list_ajax(i+1);
                    })
                });
            });


            //note 选择属于那一种性格
            $(function() {
                $("#enneagram_list").find("input").each(function(i,w){
                    $(w).click(function(){
                        if($(this).attr("checked")){
                            enneagram_update_ajax(i+1);
                        }
                    });
                });
            });

            function kfManage(){
                var url = "./allmember_ajax.php?n=kfmanage&uid=<?php echo $member['uid'];?>";
                $.get(url,function(data){
                    $("body").append(data);
                });
            }

            var iscontact = 0;
            function contact(type,from,to){
                if( iscontact != 0) return;
                var tel2=$("#callno").html();
                if(!tel2) tel2="";
                var url = "./allmember_ajax.php?n=contact&uid=<?php echo $uid;?>&tel=<?php echo $member['telphone'];?>&tel2="+tel2+"&type="+type+"&from="+from+"&to="+to;
    
                $.get(url,function(data){
                    $("body").append(data);
                });
            }


        </script>

    </head>
    <body>
        <h1>
            <span class="action-span1"><a href="###">真爱一生网 管理中心</a> </span><span id="search_id" class="action-span1"> - 查看会员<?php echo $uid;?>资料 </span>
            <!-- <span class="action-span"><a href="javascript:parent.delmyuser(<?php echo $member['uid'];?>)" onclick="return confirm('确定删除吗？删除后此会员将不在你的名下！')">删除</a></span> -->
            <span class="action-span"><a href="index.php?action=allmember&h=view_info&uid=<?php echo $member['uid'];?>">刷新</a></span>
            <div style="clear:both"></div>
        </h1>
        <div class="list-div thispageTable" id="view_info">
            <div class="userpic">
                <div style="width:220px;height:220px;text-align:center;overflow:hidden;">


                    <?php if($member['images_ischeck']=='1'&& $member['mainimg']) { ?>
                    <img id="show_pic_1" src="<?php   
                         if(MooGetphotoAdmin($member['uid'],'mid')) echo MooGetphotoAdmin($member['uid'],'mid');
                         elseif($member['gender'] == '1')echo '../public/system/images/woman_1.gif';
                         else  echo '../public/system/images/man_1.gif';
                         ?>" /></a>
                    <?php } elseif ($member['mainimg']) { ?>
                    <?php if($member['gender'] == '1') { ?>
                    <img id="show_pic_1" src="../public/system/images/woman.gif"/>
                    <?php } else { ?>
                    <img id="show_pic_1" src="../public/system/images/man.gif" />
                    <?php } ?>
                    </a>
                    <?php } else { ?>                                        
                    <?php if($member['gender'] == '1') { ?>
                    <img id="show_pic_1" src="../public/system/images/service_nopic_woman.gif"/>
                    <?php } else { ?>
                    <img  id="show_pic_1" src="../public/system/images/service_nopic_man.gif"/>
                    <?php } ?>
                    </a>
                    <?php } ?> 


                </div>
                <table width="150" border="0" cellspacing="0" cellpadding="0" class="allmember_funbtn">
                    <tr>
                        <td colspan="3" align="center" id="mAlbum">
                            <a onclick="thisLogin();return false;" href="#" target="_blank" style="background:none;border:none;color:#333; display:inline;">进入网站查看</a>

                            <a href="#" class="modifyinfo" <?php if($GLOBALS['adminid']==52 || (in_array($GLOBALS['groupid'],$GLOBALS['general_service_pre']) && $member['usertype']==3) ) { ?> onclick="alert('无权修改！');return false;"<?php } else { ?>onclick="parent.addTab('修改会员<?php echo $member['uid'];?>资料','index.php?action=allmember&h=edit_info&uid=<?php echo $member['uid'];?>')"<?php } ?> style="background:none;border:none;color:#333;display:inline;">修改资料</a>				
                            <!-- 相册开始 -->
                           
                            <!-- 结束 -->
                        </td>
                    </tr>
                    <tr>
                        <!-- <td width="50" height="35"><a href="javascript:<?php if($GLOBALS['ccid']) { ?>contact(1,0,<?php echo $uid;?>)<?php } else { ?>alert('你无权拨打电话')<?php } ?>;">拨打电话</a></td> -->
                        <td width="50"><a href="javascript:contact(2,0,<?php echo $uid;?>);"  >发送短信</a></td>
                        <td width="50"><a href="javascript:contact(3,0,<?php echo $uid;?>);"   >发站内信</a></td>
                        <td width="50"><a href="javascript:contact(4,0,<?php echo $uid;?>);" >会员认证</a></td>
                    </tr>
                    <tr>
                        <td width="50"><a href="javascript:enneagram()">九型人格</a></td>
                        <td width="50">
                            <?php if($member['usertype']==3) { ?><a href="javascript:kfManage()"  >客服操作</a><?php } ?>
                            <?php if(in_array($GLOBALS['groupid'],$GLOBALS['admin_aftersales'])) { ?>
                            <a href="#" onclick="view_transfer(event,<?php echo $member['uid'];?>);return false;">交接信息</a>
                            <?php } ?>
                        </td>
                        <td width="50" height="35"><a href="javascript:contact(5,0,<?php echo $uid;?>);" onclick="alert('无权操作');return false;" >发送彩信</a></td>

                    </tr>
                </table>
            </div>

            <div class="info1" style="width:18%;">
                <div class="title"><span>个人资料</span></div>
                <table border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td class="desc">用户ID:</td>
                        <td class="desc2" colspan=2><?php echo $member['uid'];?></td>
                    </tr>
                    <tr>
                        <td class="desc">用户名:</td>
                        <td class="desc2"><span <?php if($member['usertype']==3 && $GLOBALS['adminid']!=1 ) { ?>title="您无权查看此条信息"<?php } else { ?>title="<?php echo $member['username'];?>"<?php } ?>><?php if($member['usertype']!=3) { ?><?php echo MooCutstr($member['username'],16)?><?php } else { ?><?php if($GLOBALS['adminid']!=1) { ?>******<?php } ?><?php } ?></span></td>
                        <td>
                            <a href="#" onclick="show_search(event,'<?php echo $member['username'];?>');"><img src="templates/images/sou-btn.gif" ></a>
                        </td>
                    </tr>
                    <tr>
                        <td class="desc">姓　名:</td>
                        <td class="desc2" <?php if($member['usertype']==3 && $GLOBALS['adminid']!=1 ) { ?>title="您无权查看此条信息"<?php } ?>><?php if($member['usertype']!=3) { ?><?php echo $member2['truename'];?><?php } else { ?><?php if($GLOBALS['adminid']!=1) { ?>******<?php } ?><?php } ?></td>
                        <td>
                            <a href="#" onclick="show_search(event,'<?php echo $member2['truename'];?>');"><img src="templates/images/sou-btn.gif" ></a>
                        </td>
                    </tr>
                    <tr>
                        <td class="desc">昵　称:</td>
                        <td class="desc2"><?php echo $member['nickname'];?></td>
                        <td>
                            <a href="#" onclick="show_search(event,'<?php echo $member['nickname'];?>');"><img src="templates/images/sou-btn.gif" ></a>
                        </td>
                    </tr>
                    <tr>
                        <td class="desc">性　别:</td>
                        <td class="desc2" colspan=2><?php if($member['gender']) { ?>女<?php } else { ?>男<?php } ?><input type="hidden" id="gender" value="<?php echo $member['gender'];?>" /></td>
                    </tr>
                    <tr>
                        <td class="desc">出生年月:</td>
                        <td class="desc2" colspan=2><?php if(isset($member['birthyear'])) { ?><?php echo $member['birthyear'];?><?php } ?>年<?php if(isset($member['birthmonth'])) { ?><?php echo $member['birthmonth'];?><?php } ?>月<?php if(isset($member['birthday'])) { ?><?php echo $member['birthday'];?><?php } ?>日</td>
                    </tr>
                    <tr>
                        <td class="desc">年　龄:</td>
                        <td class="desc2" colspan=2><?php echo date("Y")-$member['birthyear'];?>岁</td>
                    </tr>
                    <tr>
                        <td class="desc">生肖:</td>
                        <td id="zodiac" class="desc2" colspan=2></td>
                    </tr>
                    <tr>
                        <td class="desc">星座:</td>
                        <td id="zodiac_" class="desc2" colspan=2></td> 

                    </tr>
                    <tr>
                        <td class="desc">身　高:</td>
                        <td class="desc2" colspan=2><script>userdetail("<?php echo $member['height'];?>",height);</script></td>
                    </tr>
                    <tr>
                        <td class="desc">学　历:</td>
                        <td class="desc2" colspan=2><script>userdetail("<?php echo $member['education'];?>",education);</script></td>
                    </tr>
                    <tr>
                        <td class="desc">籍　贯:</td>
                        <td class="desc2" colspan=2>
                            <script>userdetail("<?php echo $member2['hometownprovince'];?>",provice);userdetail("<?php echo $member2['hometowncity'];?>",city);</script>
                        </td>
                    </tr>
                    <tr>
                        <td class="desc">婚姻状况:</td>
                        <td class="desc2" colspan=2><script>userdetail("<?php echo $member['marriage'];?>",marriage);</script></td>
                    </tr>
                    <tr>
                        <td class="desc">Q　Q:</td> 
                        <td class="desc2">
                            <?php if($member['usertype']==3 ) { ?>
                            ******
                            <?php } else { ?>
                            <?php if($is_look=='1') { ?> 
                            此会员为 4类/5类 快成单会员，如需要此会员联系方式，请与(<?php echo $member['sid'];?>号)<?php echo $GLOBALS['kefu_arr'][$member['sid']];?>联系
                            <?php } else { ?>
                            <?php echo $member2['qq'];?>
                        <td><a href="#" onclick="show_search(event,'<?php echo $member2['qq'];?>');"><img src="templates/images/sou-btn.gif" ></a></td>
                        <?php } ?>

                        <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="desc">手　机:</td>
                        <td class="desc2" >
                            <?php if($member['usertype']==3 ) { ?>
                            ******
                            <?php } else { ?>
                            <?php if($is_look=='1') { ?>  
                            4类/5类 会员
                            <?php } else { ?>
                            <?php echo $member['telphone'];?>
                        <td><a href="#" onclick="show_search(event,'<?php echo $member['telphone'];?>');"><img src="templates/images/sou-btn.gif" ></a></td>
                        <?php } ?>
                        <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="desc">号码所在地:</td>
                        <td class="desc2" colspan='2' id="teladdress" >
                            <?php if($member['usertype']==3 ) { ?>
                            ******
                            <?php } else { ?>

                            <?php echo $teladdr;?>
                            <?php } ?>
                        </td>
                    </tr>

                    <tr>
                        <td class="desc">备用号码:</td>
                        <td class="desc2" colspan=2>
                            <span id="byhm">
                                <?php if($member['callno']) { ?>
                                <?php if($member['usertype']==3 ) { ?>
                                ******
                                <?php } else { ?>
                                <?php if($is_look) { ?>  
                                4类/5类 会员
                                <?php } else { ?>
                                <a id="callno" href="javascript:showInputHm();"><?php echo $member['callno'];?></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onclick="show_search(event,'<?php echo $member['callno'];?>');"><img src="templates/images/sou-btn.gif" ></a>
                                <?php } ?>
                                <?php } ?>
                                <?php } else { ?>
                                暂无[<a href="javascript:showInputHm();">新增</a>]

                                <?php } ?>
                            </span>
                            <input id="inputhm" type="text" style="width:80px; display:none" onblur="submitcallno(this.value,'<?php echo $member['callno'];?>',<?php echo $member['uid'];?>)" value="<?php echo $member['callno'];?>" />
                        </td>
                    </tr>
                    <tr id="byhmszd" >
                        <td class="desc">号码所在地:</td>
                        <td id="byhmszd_td" class="desc2" colspan=2 id='teladdress_'> 
                            <?php if($member['callno'] ) { ?>
                            <?php if($member['usertype']==3) { ?>
                            ******
                            <?php } else { ?>
                            <?php echo $teladdr2;?>
                            <?php } ?>

                            <?php } ?>
                        </td>
                    </tr>
                   
                </table>

            </div>

            <div class="info1" style="width:18%;">
                <table border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td class="desc">有无小孩:</td>
                        <td class="desc2" colspan=2><script>userdetail("<?php echo $member['children'];?>",children);</script></td>
                    </tr>
                    <tr>
                        <td class="desc">兄弟姐妹:</td>
                        <td class="desc2" colspan=2><script>userdetail("<?php echo $member2['family'];?>",family);</script><?php if($member2['family']>1) { ?>个<?php } ?></td>
                    </tr>
                    <tr>
                        <td class="desc">月　薪:</td>
                        <td class="desc2" colspan=2><script>userdetail("<?php echo $member['salary'];?>",salary1);</script></td>
                    </tr>
                    <tr>
                        <td class="desc">职　业:</td>
                        <td class="desc2" colspan=2><script>userdetail("<?php echo $member2['occupation'];?>",occupation);</script></td>
                    </tr>
                    <tr>
                        <td class="desc">工作地区:</td>
                        <td class="desc2" colspan=2>
                            <script>document.write("<span id='work_province'>");userdetail("<?php echo $member['province'];?>",provice);document.write("</span>");document.write("<span id='work_city'>");userdetail("<?php echo $member['city'];?>",city);document.write("</span>");</script>&nbsp;&nbsp;&nbsp;&nbsp;
                            <a href="#" onclick="show_search(event,'work_address');"><img src="templates/images/sou-btn.gif" ></a></td>
                    </tr>


                   
                    <!--  <tr>
                         <td class="desc">工作地天气</td>
                         <td class="desc2" colspan=2><div id='workcity_weather_today'></div><div id='workcity_weather_tomorrow'></div><div id='workcity_weather_after'></div></td></tr>
                     <tr> --> 
                    <tr>
                        <td class="desc">工作单位:</td>
                        <td class="desc2" colspan=2><script>userdetail("<?php echo $member2['corptype'];?>",corptype);</script></td>
                    </tr>
                    <tr>
                        <td class="desc">住房状况:</td>
                        <td class="desc2" colspan=2><script>userdetail("<?php echo $member['house'];?>",house);</script></td>
                    </tr>
                    <tr>
                        <td class="desc">是否有车:</td>
                        <td class="desc2" colspan=2><script>userdetail("<?php echo $member2['vehicle'];?>",vehicle);</script></td>
                    </tr>
                    <tr>
                        <td class="desc">是否饮酒:</td>
                        <td class="desc2" colspan=2><script>userdetail("<?php echo $member2['drinking'];?>",isDrinking);</script></td>
                    </tr>
                    <tr>
                        <td class="desc">是否吸烟:</td>
                        <td class="desc2" colspan=2><script>userdetail("<?php echo $member2['smoking'];?>",isSmoking);</script></td>
                    </tr>
                    <tr>
                        <td class="desc">多久找到对象:</td>
                        <td class="desc2" colspan=2><script>userdetail("<?php echo $member['oldsex'];?>",expectlovedate);</script></td>
                    </tr>
                    <tr>
                        <td class="desc">会员性格:</td>
                        <td class="desc2" colspan=2><script>userdetail("<?php echo $member2['nature'];?>",nature);</script></td>
                    </tr>
                    <tr>
                        <td class="desc">毕业院校:</td>
                        <td class="desc2" colspan=2><?php echo $member2['finishschool'];?></td>
                    </tr>
                    <tr>
                        <td class="desc">资料公开:</td>
                        <td class="desc2" colspan=2>
                            <?php 
                            echo $member['showinformation']?"是":"否";
                            $show=array(1=>"正在交往",2=>"找到真爱",3=>"最近忙",4=>"对红娘不满意");
                            echo !$member['showinformation'] ? $show[$member['showinformation_val']]:"";
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="desc">资料是否封锁:</td>
                        <td class="desc2" colspan=2><?php if($member['is_lock']) { ?>未封锁<?php } else { ?>封锁<?php } ?></td>
                    </tr>
                    <tr>
                        <td class="desc">会员等级:</td>
                        <td class="desc2" colspan=2><?php echo $GLOBALS['member_level'][$member['s_cid']];?></td>
                    </tr>
                    <?php if(!empty($member['bgtime']) && !empty($member['endtime'])) { ?>
                    <tr>
                        <td class="desc">服务期:</td>
                        <td class="desc2" colspan=2><?php echo date('Y-m-d',$member['bgtime']).'至'.date('Y-m-d',$member['endtime']);?></td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <td class="desc">会员来源:</td>
                        <td class="desc2 userfrom" colspan=2 data-usertype="<?php echo $member['usertype'];?>"><?php if($member['usertype']==1)echo "本站注册";if($member['usertype']==2)echo "外站加入";if($member['usertype']==3)echo "诚信会员";if($member['usertype']==4) echo '联盟会员';?></td>
                    </tr>
                    <tr>
                        <td class="desc">所属客服:</td>
                        <td class="desc2 customerservice" colspan=2><?php if(!empty($member['sid'])) { ?> <?php echo $member['sid'];?>号(<?php echo $GLOBALS['kefu_arr'][$member['sid']];?>)客服<?php } else { ?>无<?php } ?></td>
                    </tr>
                    <tr>
                        <td class="desc">九型性格:</td>
                        <td class="desc2" id="display_enneagram" colspan=2>

                            <?php if($enneagram_row['type'] == '1') { ?>
                            一号完美型[<a href="javascript:enneagramEmpty();">清空</a>]
                            <?php } ?>
                            <?php if($enneagram_row['type'] == '2') { ?>
                            二号助人型[<a href="javascript:enneagramEmpty();">清空</a>]
                            <?php } ?>
                            <?php if($enneagram_row['type'] == '3') { ?>
                            三号成就型[<a href="javascript:enneagramEmpty();">清空</a>]
                            <?php } ?>
                            <?php if($enneagram_row['type'] == '4') { ?>
                            四号自我型[<a href="javascript:enneagramEmpty();">清空</a>]
                            <?php } ?>
                            <?php if($enneagram_row['type'] == '5') { ?>
                            五号理智型[<a href="javascript:enneagramEmpty();">清空</a>]
                            <?php } ?>
                            <?php if($enneagram_row['type'] == '6') { ?>
                            六号忠诚型[<a href="javascript:enneagramEmpty();">清空</a>]
                            <?php } ?>
                            <?php if($enneagram_row['type'] == '7') { ?>
                            七号活跃型[<a href="javascript:enneagramEmpty();">清空</a>]
                            <?php } ?>
                            <?php if($enneagram_row['type'] == '8') { ?>
                            八号领袖型[<a href="javascript:enneagramEmpty();">清空</a>]
                            <?php } ?>
                            <?php if($enneagram_row['type'] == '9') { ?>
                            九号和平型[<a href="javascript:enneagramEmpty();">清空</a>]
                            <?php } ?>


                        </td>
                    </tr>
                     <tr>
                        <td class="desc">短信提醒状态</td>
                        <td colspan=2><?php if($member['is_phone']) { ?>提醒---[<a href="javascript:subitnotice('<?php echo $member['uid'];?>',0);">关闭</a>]<?php } else { ?>不提醒--- [<a href="javascript:subitnotice('<?php echo $member['uid'];?>',1);">开启</a>]<?php } ?><span id='open_isphone'></span></td>
                    </tr>
                </table>	
            </div>
            <!--择偶资料开始-->
            <div class="info1" style="width:18%;">
                <div class="title"><span>择偶资料</span></div>
                <table border="0" cellspacing="0" cellpadding="0">		
                    <tr>
                        <td class="desc">年　龄:</td>
                        <td id="spouseage" class="desc2">不限</td>
                    </tr>
                    <tr>
                        <td class="desc">身　高:</td>
                        <td id="spouseheight" class="desc2">不限</td>
                    </tr>
                    <tr>
                        <td class="desc">体　重:</td>
                        <td id="spouseweight" class="desc2">不限</td>
                    </tr>
                    <tr>
                        <td class="desc">体　型:</td>
                        <td id="spousebodytype" class="desc2">不限</td>
                    </tr>	
                    <tr>
                        <td class="desc">民　族:</td>
                        <td id="spousenational" class="desc2">不限</td>
                    </tr>
                    <tr>
                        <td class="desc">职　业:</td>
                        <td id="spouseoccupation" class="desc2">不限</td>
                    </tr>
                    <tr>
                        <td class="desc">月　薪:</td>
                        <td id="spousesalary" class="desc2">不限</td>
                    </tr>
                    <tr>
                        <td class="desc">工作地址:</td>
                        <td id="spouseaddress" class="desc2">不限</td>
                    </tr>

                    <tr>
                        <td class="desc">是否抽烟:</td>
                        <td id="spousesmoke" class="desc2">不限</td>
                    </tr>
                    <tr>
                        <td class="desc">婚姻状况:</td>
                        <td id="spousemarriage" class="desc2">不限</td>
                    </tr>
                    <tr>
                        <td class="desc">教育程度:</td>
                        <td id="spouseeducation" class="desc2">不限</td>
                    </tr>
                    <tr>
                        <td class="desc">征友地区:</td>
                        <td id="spousefriendaddress" class="desc2">不限</td>
                    </tr>
                    <tr>
                        <td class="desc">是否有孩子:</td>
                        <td id="spousehavechildren" class="desc2">不限</td>
                    </tr>
                    <tr>
                        <td class="desc">是否想要孩子:</td>
                        <td id="spousewantchildren" class="desc2">不限</td>
                    </tr>
                    <tr>
                        <td class="desc">喜欢的性格:</td>
                        <td id="spousenature" class="desc2">不限</td>
                    </tr>
                </table>
            </div>
			<!--择偶资料结束-->
            <div class="info1" style="width:18%">
                <div class="title"><span>网站行为:</span></div>
                <table border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td class="desc">真实在线状态:</td>
                        <td class="desc2">
                            <?php if($member['usertype']==3) { ?>
                            <span style="color:#B4A23B;">今天活跃过</span>
                            <?php } else { ?>
                            <?php if(isset($member_admininfo['real_lastvisit']) && (time() - $member_admininfo['real_lastvisit'])  < 600) { ?>
                            <span style="color:#CF6454;">在线&nbsp;&nbsp;&nbsp;<?php echo empty($member['client'])?'':'<img src="templates/images/wap_phone.gif" title="手机wap在线">';?></span>
                            <?php } elseif (isset($member_admininfo['real_lastvisit']) && (time()-$member_admininfo['real_lastvisit'])< 3600 ) { ?>
                            <span style="color:#B4A23B;">一小时内登陆过</span>
                            <?php } elseif (isset($member_admininfo['real_lastvisit']) && (time() - $member_admininfo['real_lastvisit']) > 3600 && (time() - $member_admininfo['real_lastvisit']) < (12*3600)) { ?>
                            <span style="color:#B4A23B;">3小时前活跃过</span>
                            <?php } elseif (isset($member_admininfo['real_lastvisit']) && (time() - $member_admininfo['real_lastvisit']) > 12*3600 && (time() - $member_admininfo['real_lastvisit']) < (3600*24)) { ?>
                            <span style="color:#B4A23B;">今天活跃过</span>
                            <?php } elseif (isset($member_admininfo['real_lastvisit']) && (time() - $member_admininfo['real_lastvisit']) > 24*3600 && (time() - $member_admininfo['real_lastvisit']) < (3600*24*30)) { ?>
                            <span style="color:#B4A23B;">3天前活跃过</span>
                            <?php } else { ?>
                            <span style="color:#B4A23B;">一周前活跃过</span>
                            <?php } ?>
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="desc">红娘来信:</td>
                        <td class="desc2"><?php echo $hncount;?></td>
                    </tr>
                    <tr>
                        <td class="desc">会员来信:</td>
                        <td class="desc2"><?php echo $hycount;?></td>
                    </tr>
                    <tr>
                        <td class="desc">发件箱数:</td>
                        <td class="desc2"><?php echo $facount;?></td>
                    </tr>
                    <tr>
                        <td class="desc">被分配次数:</td>
                        <td class="desc2"><a href="#" id="fpcount" onclick="parent.addTab('查看<?php echo $member['uid'];?>分配情况','index.php?action=other&h=member_allotrecord_per&uid=<?php echo $member['uid'];?>','icon')"></a></td>
                    </tr>
                    <tr>
                        <td class="desc">被放弃次数:</td>
                        <td class="desc2"><a href="#" id="fqcount" onclick="parent.addTab('查看<?php echo $member['uid'];?>放弃情况','index.php?action=other&h=giveup_member&uid=<?php echo $member['uid'];?>&type=uid&keyword=<?php echo $member['uid'];?>','icon')"></a></td>
                    </tr>
                    <tr>
                        <td class="desc">登录次数:</td>
                        <td class="desc2" <?php if($member['usertype']==3 && $GLOBALS['adminid']!=1 ) { ?>title="您无权查看此条信息"<?php } ?>><?php if($member['usertype']!=3 || $GLOBALS['adminid']==1) { ?><?php echo $member['login_meb'];?><?php } else { ?>******<?php } ?></td>
                    </tr>
                    <tr>
                        <td class="desc">上次登录IP:</td>
                        <td class="desc2" <?php if($member['usertype']==3 && $GLOBALS['adminid']!=1 ) { ?>title="您无权查看此条信息"<?php } ?>><?php if($member['usertype']!=3 || $GLOBALS['adminid']==1) { ?><?php echo $member_admininfo['last_ip'];?>&nbsp;&nbsp;<a href="#" onclick="show_search(event,'<?php echo $member_admininfo['last_ip'];?>');"><img src="templates/images/sou-btn.gif" ></a><?php } else { ?>******<?php } ?></td>
                    </tr>

                    <td class="desc">最后登录IP:</td>
                    <td class="desc2" <?php if($member['usertype']==3 && $GLOBALS['adminid']!=1 ) { ?>title="您无权查看此条信息"<?php } ?>><?php if($member['usertype']!=3 || $GLOBALS['adminid']==1) { ?><?php echo $member_admininfo['finally_ip'];?>&nbsp;&nbsp;<a href="#" onclick="show_search(event,'<?php echo $member_admininfo['finally_ip'];?>');"><img src="templates/images/sou-btn.gif" ></a><?php } else { ?>******<?php } ?></td>
                    </tr>
                    <tr>
                        <td class="desc">注册日期:</td>
                        <td class="desc2" <?php if($member['usertype']==3 && $GLOBALS['adminid']!=1 ) { ?>title="您无权查看此条信息"<?php } ?>><br /><?php if($member['usertype']!=3 || $GLOBALS['adminid']==1) { ?><?php echo date('Y-m-d H:i',$member['regdate']);?><?php } else { ?>******<?php } ?></td>
                    </tr>
                    <tr>
                        <td class="desc">模拟最后访问时间:</td>
                        <td class="desc2"><br /><?php if($member['lastvisit']) echo date('Y-m-d H:i',$member['lastvisit']);?></td>
                    </tr>
                    <tr>

                        <td class="desc">真实最后访问时间:</td>
                        <td class="desc2" <?php if($member['usertype']==3 && $GLOBALS['adminid']!=1 ) { ?>title="您无权查看此条信息"<?php } ?>><br />
                            <?php if($member['usertype']!=3 || $GLOBALS['adminid']==1) { ?><?php if($member_admininfo['real_lastvisit']) echo date('Y-m-d H:i',$member_admininfo['real_lastvisit']);?><?php } else { ?>******<?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="desc">此手机号注册的会员数：</td>
                        <td class="desc2" <?php if($member['usertype']==3 && $GLOBALS['adminid']!=1 ) { ?>title="您无权查看此条信息"<?php } ?>><?php if($member['usertype']!=3 || $GLOBALS['adminid']==1) { ?><span id="mobileregcount"></span><?php } else { ?>******<?php } ?></td>
                    </tr>
                </table>
            </div>
            <div style="clear:both"></div>
			<div style="margin-left:280px;margin-bottom:5px;" >
			    <?php if(!empty($member['fondsport'])) { ?><span style="font-weight:bold;">喜欢的运动：</span>
					<?php $fda = explode(",",$member['fondsport']);?>
					<?php foreach((array)$fda as $v) {?>
					<?php if($v == '0') { ?><span >保密</span><?php } else { ?>
					<script>userdetail('<?php echo $v;?>',fondsports)</script>
					<?php } ?>
					<?php } ?>
				<?php } ?>
				<?php if(!empty($member['fondactivity'])) { ?><span style="font-weight:bold;">喜欢的活动：</span>  
					<?php $fda = explode(",",$member['fondactivity']);?>
					<?php foreach((array)$fda as $v) {?>
					<?php if($v == '0') { ?><span >保密</span><?php } else { ?>
					<script>userdetail('<?php echo $v;?>',fondactions)</script>
					<?php } ?>
					<?php } ?>
				<?php } ?>
				<?php if($member['fondmusic']) { ?>
                    <span style="font-weight:bold;">喜欢的音乐：</span>
				            
					<?php $fda = explode(",",$member['fondmusic']);?>
					<?php foreach((array)$fda as $v) {?>
					<?php if($v == '0') { ?><span >保密</span><?php } else { ?>
					<script>userdetail('<?php echo $v;?>',fondmusics)</script>
					<?php } ?>
					<?php } ?>
				<?php } ?>
				
				<?php if($member['fondprogram']) { ?>
				    <span style="font-weight:bold">喜欢的影视:</span>    
					<?php $fda = explode(",",$member['fondprogram']);?>
					<?php foreach((array)$fda as $v) {?>
					<?php if($v == '0') { ?><span >保密</span><?php } else { ?>
					<script>userdetail('<?php echo $v;?>',fondprograms)</script>
					<?php } ?>
					<?php } ?>
				<?php } ?>
			</div>
            <div style="margin-left: 280px;">
                <span style="font-weight:bold;">内心独白：</span>
                <span id="introduce"></span>
            </div>
            <div class="userOtherFun" style="display:none;border:1px solid #7F99BE;" id="enneagram_list">
                <table> 
                    <tr align="center">
                        <td >
                            <p><a href="#"><img width="74" border= "0" height="75" src="./templates/images/enneagramimages/1.jpg"></a>
                            <p>一号完美型  <span><input type="radio" name="name1" value="1" style="display:none"></span></p>
                        </td>
                        <td >    
                            <p><a href="#"><img width="74" border= "0" height="75"  src="./templates/images/enneagramimages/2.jpg"></a></p>
                            <p> 二号助人型<span><input type="radio" name="name1" value="1" style="display:none"></span></p>
                        </td>
                        <td >
                            <p><a href="#"><img width="74" border= "0" height="75"  src="./templates/images/enneagramimages/3.jpg"></a></p>
                            <p>三号成就型<span><input type="radio" name="name1" value="1" style="display:none"></span></p>
                        </td>
                        <td >
                            <p><a href="#"><img width="74" border= "0" height="75"  src="./templates/images/enneagramimages/4.jpg"></a></p>
                            <p>四号自我型<span><input type="radio" name="name1" value="1" style="display:none"></span></p>
                        </td>
                        <td>
                            <p><a href="#"><img width="74" border= "0" height="75"  src="./templates/images/enneagramimages/5.jpg"></a></p>
                            <p>五号理智型<span><input type="radio" name="name1" value="1" style="display:none"></span></p>
                        </td>
                        <td >
                            <p><a href="#"><img width="74" border= "0" height="75"  src="./templates/images/enneagramimages/6.jpg"></a></p>
                            <p>六号忠诚型<span><input type="radio" name="name1" value="1" style="display:none"></span></p>
                        </td>
                        <td >
                            <p><a href="#"><img width="74" border= "0" height="75"  src="./templates/images/enneagramimages/7.jpg"></a></p>
                            <p>七号活跃型<span><input type="radio" name="name1" value="1" style="display:none"></span></p>
                        </td>
                        <td >
                            <p><a href="#"><img width="74"  border= "0"  height="75"  src="./templates/images/enneagramimages/8.jpg"></a></p>
                            <p>八号领袖型<span><input type="radio" name="name1" value="1" style="display:none"></span></p>
                        </td>
                        <td>
                            <p><a href="#"><img width="74"   border= "0" height="75"  src="./templates/images/enneagramimages/9.jpg"></a></p>
                            <p>九号和平型<span><input type="radio" name="name1" value="1" style="display:none"></span></p>
                        </td>
                    </tr>
                </table>
            </div>
            <div><br></div>
            <div style="clear:both"></div>

            <div class="userOtherFun">
                <ul>
                    <li id="menu1" class="currentLink"><a href="javascript:menu(1)"></a></li>
                    <li id="menu3"><a href="javascript:menu(3)"></a></li>
                    <li id="menu4"><a href="javascript:menu(4)"></a></li>
                    <li id="menu2"><a href="javascript:menu(2)"></a></li>
                    <li id="menu5"><a href="javascript:menu(5)"></a></li>
                    <li id="menu7"><a href="javascript:menu(7)"></a></li>
                    <li id="menu9"><a href="javascript:menu(9)"></a></li>
                    <li id="menu_c"><a href="javascript:menu('_c')"></a></li>
                    <li id="menu8"><a href="javascript:menu(8)"></a></li>
                    <li id="menu_a"><a href="javascript:menu('_a')"></a></li>
                    <li id="menu6"><a href="javascript:menu(6)">匹配搜索</a></li>
                    <li id="menu_b"><a href="javascript:menu('_b')">电话流程</a></li>
                    <?php if($adminright == 'sellpass') { ?>
                    <?php if($member['s_cid'] < 40 && $member['usertype'] < 3) { ?>
                    <li id="menu_d"><a href="javascript:menu('_d')">售后</a></li>
                    <?php } ?>
                    <?php } ?>
                    <!-- <li id="menu_10"><a href="javascript:menu('_10')"></a></li> -->
                    <li id="menu_j"><a href="javascript:menu('_j')"></a></li>
                </ul>
                <div class="userOtherFun_content">
                    <!---小记 begin -->
                    <div id="item1">
                        <form id="noteForm"  name="noteForm"  >
                            <table width="95%" border="0" align="center" cellpadding="3" cellspacing="3">
                                <tr height="20">
                                    <td colspan="5">跟进步骤：<select name="grade" id="grade" onchange="grade_change(this.value);">
                                            <option value="">--请选择跟进步骤--</option>
                                            <?php foreach((array)$grade as $k=>$v) {?>
                                            <option value="<?php echo $k;?>" <?php if(($salesAfter==1 && $k==12)) { ?> selected="selected" <?php } elseif (isset($member_admininfo['effect_grade']) && $k == $member_admininfo['effect_grade']) { ?> selected="selected"<?php } ?>><?php echo $v;?></option>
                                            <?php }?>
                                        </select>&nbsp;&nbsp;
                                        <script type="text/javascript">
                                            function grade_change(grade) {
                                                if(grade < 10 || grade==12) {
                                                    $.post('myuser_ajax.php', {n:'change_grade', uid:<?php echo $member['uid'];?>, sid:<?php echo $member['sid'];?>, usertype:<?php echo $member['usertype'];?>, change_grade:grade});
                                                    $("#del_cause")[0].style.display = 'none';
                                                    $("#other").css("display", 'block');
                                                    $("#unable_contact").css("display", 'none');
                                                } else if (grade == 10) {
                                                    $("#other").css("display", 'none');	
                                                    $("#del_cause")[0].style.display = 'block';
                                                    $("#unable_contact").css("display", 'none');
                                                }else if (grade == 11) {
                                                    $("#other").css("display", 'none'); 
                                                    $("#del_cause")[0].style.display = 'none';
                                                    $("#unable_contact").css("display", 'block');
                                                }
                                            }
                                            function del_myuser() {
                                                var cause = $(":radio[name='del_cause']:checked").val();
                                                var other_cause = $("#other_cause").val();
                                                if(!cause) {
                                                    alert("请选择删除理由");
                                                    return false;
                                                }
                                                if(cause != 7) {
                                                    $.post('myuser_ajax.php', {n:'change_grade', uid:<?php echo $member['uid'];?>, regdate:<?php echo $member['regdate'];?>, sid:<?php echo $member['sid'];?>,source:"<?php echo $member['source'];?>", usertype:<?php echo $member['usertype'];?>, change_grade:10, del_cause:cause}, function(data) {
                                                        if(data == 'ok') {
                                                            alert("删除成功");
                                                            location.href = "index.php?action=allmember&h=view_info&uid=<?php echo $member['uid'];?>";
                                                        } else if (data == 'error') {
                                                            alert("无权限");
                                                        } else {
                                                            alert("异常错误");
                                                        }
                                                    });
                                                } else {
                                                    if(other_cause) {
                                                        $.post('myuser_ajax.php', {n:'change_grade', uid:<?php echo $member['uid'];?>, regdate:<?php echo $member['regdate'];?>, sid:<?php echo $member['sid'];?>,source:"<?php echo $member['source'];?>", usertype:<?php echo $member['usertype'];?>, change_grade:10, del_cause:other_cause}, function(data) {
                                                            if(data == 'ok') {
                                                                alert("会员删除成功");
                                                                location.href = "index.php?action=allmember&h=view_info&uid=<?php echo $member['uid'];?>";
                                                            } else if (data == 'error') {
                                                                alert("无权限");
                                                            } else {
                                                                alert("异常错误");
                                                            }
                                                        });
                                                    } else {
                                                        alert("请输入理由");
                                                        return false;
                                                    }
                                                }
                                            }


                                            function unablecontact_myuser(){
                                                $.post('myuser_ajax.php', {n:'change_grade', uid:<?php echo $member['uid'];?>, regdate:<?php echo $member['regdate'];?>, sid:<?php echo $member['sid'];?>,source:"<?php echo $member['source'];?>", usertype:<?php echo $member['usertype'];?>, change_grade:11}, function(data) {
                                                    if(data == 'ok') {
                                                        alert("提交成功");
                                                        location.href = "index.php?action=allmember&h=view_info&uid=<?php echo $member['uid'];?>";
                                                    }
                                                });
                                            }
                                        </script>
                                        <span id="del_cause" style="display:none">
                                            请选择放弃理由：<br />
                                            <input name='del_cause' type='radio' value="1" />1、空号<br />
                                            <input name='del_cause' type='radio' value="2" />2、停机<br />
                                            <input name='del_cause' type='radio' value="3" />3、非本人注册<br />
                                            <input name='del_cause' type='radio' value="4" />4、3天内一直关机<br />
                                            <input name='del_cause' type='radio' value="5" />5、已婚<br />
                                            <input name='del_cause' type='radio' value="6" />6、注册玩的<br />
                                            <input name='del_cause' type='radio' value="7" />7、其他
                                            <input type="text" name="other_cause" id="other_cause"/><br />
                                            <input type="button" onclick="del_myuser();" value="提交" />
                                        </span>

                                        <span id='unable_contact' style="display:none">
                                            <input type="button" onclick="unablecontact_myuser();" value="提交" />
                                        </span>

                                    </td>
                                </tr>
                            </table>
                            <table width="95%" border="0" align="center" cellpadding="3" cellspacing="3" id="other">
                                <tr height="20">
                                    <td colspan="2">
                                        是否打了电话<input type="radio" name="phonecall" value="1" id="phonecall">是
                                        &nbsp;&nbsp;	<input type="radio" name="phonecall" value="0" id="phonecall">否 
                                    </td>


                                    <td  colspan="2" >是否有效联系：<input name="contact" type="radio" id="radio" value="1"<?php if($member_admininfo['effect_contact'] == 1) { ?> checked="checked"<?php } ?> />                    是                    <input type="radio" name="contact" id="radio2" value="0" />                   否(不计入联系量)&nbsp;                    <input name="master" type="checkbox" id="<?php echo $uid;?>"  <?php if($member_goon['uid']) { ?> checked="checked"   <?php } ?> onclick="javascript:updateMaster(this.id);"/>
                                                                                                                                                                                                        <input type="hidden"  id="isControl_<?php echo $uid;?>" value=<?php echo $member_Control['flag'];?> />
                                        <input type="hidden"  id="isForcast_<?php echo $uid;?>" value=<?php echo $member_Control['isforcast'];?> />

                                    </td>

                                    <td width="25%" >下次联系时间：<input name="time" type="text" id="time" <?php if (!empty($member_admininfo['next_contact_time'])) echo 'value="'.date('Y-m-d H:i:s',$member_admininfo['next_contact_time']).'"';else echo 'value=""';?> onfocus="WdatePicker({startDate:'%y-%M-%D 00:00:00',dateFmt:'yyyy-MM-dd HH:mm:ss',alwaysUseStartDate:true})"/></td>
                                </tr>

                                <?php if($member['telphone'] !='0' || $member['telphone'] !='') { ?>
                                <tr>
                                    <td colspan="2">电话验证：
                                        <select name="checkreason" id="checkreason">
                                            <?php if(($tel_ifcheck == '' || $tel_ifcheck == '0')) { ?>
                                            <option value="" selected="selected">--未验证原因--</option>
                                            <option value="1" <?php if($member_admininfo['checkreason'] == '1') { ?>selected="selected"<?php } ?>>1.关机</option>
                                            <option value="2" <?php if($member_admininfo['checkreason'] == '2') { ?>selected="selected"<?php } ?>>2.打通无人接听</option>
                                            <option value="3" <?php if($member_admininfo['checkreason'] == '3') { ?>selected="selected"<?php } ?>>3.接听无意验证</option>
                                            <option value="4" <?php if($member_admininfo['checkreason'] == '4') { ?>selected="selected"<?php } ?>>4.空号</option>
                                            <?php } else { ?>
                                            <option value="" selected="selected">--已验证原因--</option>
                                            <option value="4" <?php if($member_admininfo['checkreason'] == '5') { ?>selected="selected"<?php } ?>>1.喜欢网站</option>
                                            <option value="5" <?php if($member_admininfo['checkreason'] == '6') { ?>selected="selected"<?php } ?>>2.试试看</option>
                                            <?php } ?>
                                        </select>（收集电话号码是否验证原因）</td>
                                </tr>
                                <?php } ?>

                                <tr>
                                    <td colspan='5'>
                                        危险等级：
                                        <?php 
                                        if($danger_leval == '1') echo "<font color='#33CC00'>非常满意</font>";
                                        elseif($danger_leval == '2') echo "<font color='#339900'>满意</font>";
                                        elseif($danger_leval == '3') echo "<font color='#336600'>一般</font>";
                                        elseif($danger_leval == '4') echo "<font color='#FF00FF'>不满意</font>";
                                        elseif($danger_leval == '5') echo "<font color='#FF0000'>非常不满意</font>";
                                        else echo "<font color='#339900'>未选择</font>";?>
                                        &nbsp;&nbsp;
                                        <?php foreach((array)$reprating as $k=>$v) {?>
                                        <input type="radio" name="isdanger" value="<?php echo $k;?>" <?php if($danger_leval['danger_leval'] == $k) echo "checked";?>><?php echo $v;?> 
                                               <?php }?>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center">兴趣点</td>
                                    <td width="19%" align="center">异议点</td>
                                    <td width="21%" align="center">服务介绍</td>
                                    <td width="21%" align="center">下次跟进要点</td>
                                    <td width="21%" align="center">备注</td>
                                </tr>
                                <tr  class="textword">
                                    <td height="80" align="center"><textarea name="interest" id="interest" cols="27" rows="5"></textarea></td>
                                    <td height="80" align="center"><textarea name="different" id="different" cols="27" rows="5"></textarea></td>
                                    <td height="80" align="center"><textarea name="service" id="service" cols="27" rows="5"></textarea></td>
                                    <td height="80" align="center"><textarea name="desc" id="desc" cols="27" rows="5"></textarea></td>
                                    <td height="80" align="center"><textarea name="comment" id="comment" cols="27" rows="5"></textarea></td>
                                </tr>
                                <tr>
                                <input type="hidden" name="telphone" value="<?php echo $member['telphone'];?>">
                                <td ><input type="button" name="saveNotes" id="saveNotes" value="保存小记"  class="sysbtn"/>&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" name="queryNotes" id="queryNotes" value="查看所有小记"  class="sysbtn"/></td>
                                <td align="center"><input type="hidden" name="uid" id="uid" value="<?php echo $member['uid'];?>"/><input type="hidden" name="ispost" id="ispost" value="1"/><input type="hidden" id="usertype" name="usertype" value="<?php echo $member['usertype'];?>"></td>
                                <td align="center">&nbsp;</td>
                                <td align="center">&nbsp;</td>
                                <td align="center">&nbsp;</td>
                                </tr>
                            </table>
                        </form>
                    </div>
                    <!--小记end-->
                    <!--委托begin--><div id="item3"></div><!--委托end-->
                    <!--秋波begin--><div id="item4"></div><!--秋波end-->
                    <!--鲜花begin--><div id="item2"></div><!--鲜花end-->
                    <!--意中人begin--><div id="item5"></div><!--意中人end-->
                    <!--匹配搜索begin--><div id="item6"></div><!--匹配搜索end-->
                    <!--心理测试begin--><div id="item7"></div><!--心理测试end-->
                    <!--短信记录begin--><div id="item8"></div><!--短信记录end-->
                    <!--信箱记录begin--><div id="item9"></div><!--信箱记录end-->
                    <!--聊天记录begin--><div id="item_a"></div><!--聊天记录end-->
                    <!--电话流程begin--><div id="item_b"></div><!--电话流程end-->
                    <div id="item_c"></div>
                    <!-- 售后begin --><div id="item_d"></div><!-- 售后end -->
                    <!-- 参加的活动begin -->
                    <!-- <div id="item_10"></div> -->
                    <!-- 参加的活动end -->
                    <!-- 会员交接begin --><div id="item_j"></div><!-- 会员交接end -->
                </div>
            </div>
            <div class="table_list" id="item_d_d">

            </div>
            <div id="action_msg">
                <?php if(!empty($action_msg)) { ?>
                <div style="border:solid <?php echo $color;?> 5px; padding:10px; margin:5px auto; width:650px;"><?php echo $action_msg;?></div>
                <?php } ?>
                <?php if($action_button && $GLOBALS['groupid']==60) { ?>
                <input type="button" value="过期会员标注" onclick="overdue(<?php echo $member['uid'];?>)">
                <?php } ?>
            </div>
            <!--小记begin-->

            <div class="table_list" id="kefuxiaoji">
                <div id="notes">
                    
                </div>	   
                <!-- <div id="pages" style=" text-align:center"></div>  -->
            </div>
            <!--小记end-->
        </div>
    </body>
    <div id="kefulogin" style=" display:none;position:absolute; top:260px;left:100px;"><a href="" target="_blank">打不开就点这里打开</a>&nbsp;&nbsp;&nbsp;<span style="cursor:pointer;" onclick="javascript:$('#kefulogin').hide(100)">关闭</span></div>
    <div id="transfer_box" style="display:none">
        <span style="cursor:pointer;" onclick="javascript:$('#transfer_box').hide(100)">关闭</span>
        <p>内容正在加载...</p>
    </div>

    <!-- 关键词搜索 -->
    <div class="sou-box" id="keyword_search" style="display:none;">
        <div class="s-box-t"><a href="#" onclick="close_serrch();"><img src="templates/images/close-btn.gif" /></a></div>
        <div class="s-box-side">
            <ul class="s-box-list">
                <li><a href="#" onclick="open_search(1);"><img src="templates/images/bd-bg.gif" /></a></li>
                <li><a href="#" onclick="open_search(2);"><img src="templates/images/gg-bg.gif" /></a></li>
                <li><a href="#" onclick="open_search(3);"><img src="templates/images/bg-bg.gif" /></a></li>
                <li><a href="#" onclick="open_search(4);"><img src="templates/images/rl-bg.gif" /></a></li>
                <li><a href="#" onclick="open_search(5);"><img src="templates/images/ss-bg.gif" width="52" height="33" /></a></li>
                <li><a href="#" onclick="open_search(6);"><img src="templates/images/sg-bg.gif" /></a></li>
            </ul>
            <div style="clear:both"><span id="up_value" style="display:none;"></span></div>
        </div>
    </div>
    <script type="text/javascript" src="templates/js/allmember_view_info.js?v=20140831"></script>
	<link rel="stylesheet" type="text/css" href="templates/css/jquery.fancybox.css?v=2.1.5" media="screen" />
	<link rel="stylesheet" type="text/css" href="templates/css/jquery.fancybox-buttons.css?v=1.0.5" />

	<script type="text/javascript" src="templates/js/fancybox/jquery.mousewheel-3.0.6.pack.js"></script>
	<script type="text/javascript" src="templates/js/fancybox/jquery.fancybox.pack.js?v=2.1.5"></script>
	<script type="text/javascript" src="templates/js/fancybox/jquery.fancybox-buttons.js?v=1.0.5"></script>
    <script type="text/javascript">
	    var uid="<?php echo $member['uid'];?>";
		
		var birth="<?php echo $member['birth'];?>";
		var birthyear="<?php echo $member['birthyear'];?>";
		$.ajax({
		    url:'./allmember_ajax.php?n=czodiac',
			data:{birthyear:birthyear,birth:birth},
			type:'post',
			datatype:'json',
			success:function(json){
			    var zodiac=json.toString().replace(/[\"\[\]]+/g,'').split(',');
			    $('#zodiac_').html(zodiac[0]);
				$('#zodiac').html(zodiac[1]);
			}
		});
		
		var telphone="<?php echo $member['telphone'];?>";
		var callno = "<?php echo $member['callno'];?>";
		$.ajax({
		    url:'./allmember_ajax.php?n=addbytel',
			data:{telphone:telphone,callno:callno},
			type:'post',
			datatype:'json',
			beforeSend:function(){
			    if(telphone) $('#teladdress').html('正在加载...');
				if(callno) $('#teladdress_').html('正在加载...');
			},
			success:function(json){
			    var tela=json.toString().replace(/[\"\[\]]+/g,'').split(',');
			    $('#teladdress').html(tela[0]);
				$('#teladdress_').html(tela[1]);
			}
		});
		
		var mobilereg="<?php echo $member['telphone'];?>";
		$.ajax({
		    url:'./allmember_ajax.php?n=mobileregcount',
			data:{mobile:mobilereg},
			type:'post',
			datatype:'text',
			success:function(regcount){
			    var str="<a href=\"#\" onclick=\"parent.addTab('"+mobilereg+"手机号注册会员','index.php?action=allmember&h=same_telphone&telphone="+mobilereg+"','icon')\" >"+regcount+"个查看</a>";
				$('#mobileregcount').html(str);
			}
		});
		
		//择偶资料异步加载
        $.ajax({
            url:'./allmember_ajax.php?n=spouse',
            data:{uid:uid},
            type:'POST',
            dataType:'json',
            success:function(json){
			    //console.log(json);
			    if (!json) return;
				var age1= userdetail_(json['age1'],agebuxian);
				var age2= userdetail_(json['age2'],agebuxian);
				if(age1 && age2) $('#spouseage').html(age1+'至'+age2+'岁');
			    
				var height1= userdetail_(json['height1'],height);
				var height2= userdetail_(json['height2'],height);
				if(height1 && height2) $('#spouseheight').html(height1+'至'+height2+'厘米');
				
				var weight1= userdetail_(json['weight1'],weight);
				var weight2= userdetail_(json['weight2'],weight);
				if(weight1 && weight2) $('#spouseweight').html(weight1+'至'+weight2+'公斤');
				
				var body= userdetail_(json['body'],body1);
				if(body) $('#spousebodytype').html(body);
				
				var nation=userdetail_(json['nation'],stockbuxian);
				if(nation) $('#spousenational').html(nation);
				
				var occupation=userdetail_(json['occupation'],occupation);
				if(occupation) $('#spouseoccupation').html(occupation);
				
				var salary=userdetail_(json['salary'],salary1);
				if(salary) $('#spousesalary').html(salary);
				
				var workprovince=json['workprovince']>0?userdetail_(json['workprovince'],provice):'';
				var workcity = json['workcity']>0?userdetail_(json['workcity'],city):'';
				if(workcity) $('#spouseaddress').html(workprovince+workcity);
				
				var smoke=userdetail_(json['smoking'],smoking);
				if(smoke) $('#spousesmoke').html(smoke);
				
				var marriage_=userdetail_(json['marriage'],marriage);
				if(marriage_) $('#spousemarriage').html(marriage_);
				
				var education_=userdetail_(json['education'],education);
				if(education_) $('#spouseeducation').html(education_);
				
				var hometownprovince=json['hometownprovince']>0?userdetail_(json['hometownprovince'],provice):'';
				var hometowncity=json['hometowncity']>0?userdetail_(json['hometowncity'],city):'';				
				if(hometownprovince && hometowncity) $('#spousefriendaddress').html(hometownprovince+hometowncity);
				
				var havechildren=userdetail_(json['children'],children);
				if(havechildren) $('#spousehavechildren').html(havechildren);
				
				var wantchildren_=userdetail_(json['wantchildren'],wantchildren);
				if(wantchildren_) $('#spousewantchildren').html(wantchildren_);
				
				
				var nature=userdetail_(json['nature'],naturebuxian);
				if(nature) $('#spousenature').html(nature);
				
				var introduce=json['introduce'];
				$('#introduce').html(introduce);
				
			}
		});
	
        //加载动作数
        function getLocalTime(nS) {   
            return new Date(parseInt(nS) * 1000).toLocaleString().replace(/年|月/g, "-").replace(/日/g, " ");    
        }     
			  
        //var uid="<?php echo $member['uid'];?>";
        $.ajax({
            url:'./allmember_ajax.php?n=total',
            data:{uid:uid},
            type:'POST',
            dataType:'json',
            success:function(json){
                //console.log(json);
                $('#menu1 a').html('小记'+json.notestotal);
                $('#menu3 a').html('委托'+json.cocount1+'/'+json.cocount2);
                $('#menu4 a').html('秋波'+json.leercount1+'/'+json.leercount2);
                $('#menu2 a').html('鲜花'+json.rcount1+'/'+json.rcount2);
                $('#menu5 a').html('意中人'+json.fcount1+'/'+json.fcount2);
                $('#menu7 a').html('心里测试'+json.testcount);
                $('#menu9 a').html('站内信箱'+json.lettercount1+'/'+json.lettercount2);
                $('#menu_c a').html('会员评价'+json.pingjia1+'/'+json.pingjia2);
                $('#menu8 a').html('短信记录'+json.mescount);
                $('#menu_a a').html('聊天记录'+json.chatcount);
                //$('#menu_10 a').html('活动'+json.activitycount);
                $('#menu_j a').html('交接'+json.joincount);
                $('#fpcount').html(json.fpcount);
                $('#fqcount').html(json.fqcount);
            }
        });
		
		var _elements=<?php echo $json_grade;?>;
		var grade=[];
		for (var i in _elements) {
			grade.push(_elements[i]);
		}     

		//初始小记
		$.ajax({
		    url:'./allmember_ajax.php?n=initnotes',
			data:{uid:uid},
			type:'POST',
			dataType:'json',
			beforeSend:function(){
			    $('#notes').html('<span style="font-size:20px;">小记正在加载...</span>');
			},
			success:function(json){
			    //console.log(json);
				var html_notes='';
				if(!json) {  $('#notes').html('');return ;}
				if (json.length>0){
				    
                    for(var i=0;i<json.length;i++){
                        html_notes+='<ul>';
						var dateline= new Date(json[i]['dateline']*1000);
                        html_notes+='<li><strong>【'+(i+1)+'】</strong> '+dateline.toLocaleString()+'&nbsp;&nbsp;'+json[i]['mid']+'号客服'+json[i]['manager']+'&nbsp;&nbsp;&nbsp;'+(json[i]['effect_grade']-1)+'类</li>';
                        html_notes+='<li>';
						if (json[i]['effect_contact'] != 2){
						    if (json[i]['effect_contact']){
							    html_notes+='有效联系';
							}else{
							    html_notes+='无效联系';
							}
							
							html_notes+='&nbsp;&nbsp;&nbsp;';
							if (json[i]['master_member']==1) html_notes+='重点会员';
						}
						html_notes+='</li>';
                        html_notes+='<li>';
						if(json[i]['effect_grade'] &&  grade[json[i]['effect_grade']]) {
						    var _x_=grade[json[i]['effect_grade']-1];
						    html_notes+= _x_+'&nbsp;&nbsp;&nbsp;';
						}
						var myDate = new Date();
						var curtime=Date.parse(myDate)/1000;
						
                        var next_contact_time=new Date(json[i]['next_contact_time']*1000);
                        if (json[i]['next_contact_time'] > curtime){
                            html_notes+='-&gt; &nbsp;下次联系时间：'+ next_contact_time.toLocaleString();
                        }
						
                        if (json[i]['interest']) html_notes+='&nbsp;&nbsp;&nbsp;-> &nbsp;兴趣点：'+json[i]['interest'];
						if (json[i]['different']) html_notes+='&nbsp;&nbsp;&nbsp;-> &nbsp;异议点：'+json[i]['different'];
						if (json[i]['service_intro']) html_notes+='&nbsp;&nbsp;&nbsp;-> &nbsp;服务介绍：'+json[i]['service_intro'];
						if (json[i]['next_contact_desc']) html_notes+='&nbsp;&nbsp;&nbsp;-> &nbsp;下次跟进要点：'+ json[i]['next_contact_desc'];
						if (json[i]['comment']) html_notes+='&nbsp;&nbsp;&nbsp;-> &nbsp;备注：'+ json[i]['comment'];
						html_notes+='</li>';
                        html_notes+='</ul>'; 
                    }
					
                }
				$('#notes').html(html_notes);
			}
		});
	    
        //查看小记
        var newWin=null;
        $('#queryNotes').click(function(){
            newWin=window.open('./allmember_ajax.php?n=notes&uid='+uid,'newWin','height=550,width=810,top=300,left=200,menubar=no,status=no,scrollbars=1,resizable=yes,fullscreen=no','replace=true');
            newWin.focus();
        });
	
        var dclick=false;
        //小记保存
        $('#saveNotes').click(function(){
            if (dclick) return false;
			
		   
            var myDate = new Date();
            var strtime=$('#time').val();
            var nexttime=new Date(strtime.replace(/-/g, '/'));nexttime=nexttime.getTime()/1000;
            var curtime=Date.parse(myDate)/1000;
			
            var cookietime=getCookie('sn'+uid);
            cookietime=cookietime?cookietime:0;
            if((curtime - cookietime)<600) {alert('你已保存过！请 10分钟后再保存！');return false;}
			
			
			
			
            var userfrom=$('.userfrom').attr('data-usertype');
            var usersource=$('.userfrom').html().search(/全权/g);
            var grade = $("#grade").val();
            if(userfrom!=3 && usersource==-1){
                if(!grade) { alert("请选择跟进步骤！");return false;}
                var contact = $("input[name=contact]:checked").val();
                if(!contact){alert("请确定是否有效联系! ");return false;}
                var phonecall = $("input[id=phonecall]:checked").val();
                if(!phonecall){alert("请确认是否打过电话! ");return false;}
            }
			
            dclick=true;
			
            $.ajax({
                url:'./allmember_ajax.php?n=saveNotes',
                type:'POST',
                data:$('#noteForm').serialize(),// 要提交的表单
                dataType:'text',
                error: function(jqXHR, exception) {
                    if (jqXHR.status === 0) {
                        alert('网络中断！！！请不要刷新个人资料页面，以免丢失小记！');
                    } else if (jqXHR.status == 404) {
                        alert('Requested page not found. [404]');
                    } else if (jqXHR.status == 500) {
                        alert('Internal Server Error [500].');
                    } else if (exception === 'parsererror') {
                        alert('Requested JSON parse failed.'); //dataType设置为json的时候
                    } else if (exception === 'timeout') {
                        alert('Time out error.');
                    } else if (exception === 'abort') {
                        alert('Ajax request aborted.');
                    } else {
                        alert('Uncaught Error.\n' + jqXHR.responseText);
                    }
                },
                success:function(data, textStatus, jqXHR) {
                    var str='';
                    //console.log(cookietime+'and'+nexttime+'and'+curtime);
                    var grade=$('select[name=grade]').val() ;
                    str='<ul><li><strong>【新增】</strong>'+myDate.toLocaleString()+'&nbsp;&nbsp;&nbsp;'+$('.customerservice').html()+'&nbsp;&nbsp;&nbsp;'+(grade-1)+'类</li>';
                    str+='<li>';
                    if ($("input[name=contact]:checked").val()==1){
                        str+='有效联系';
                    }else{
                        str+='无效联系';
                    }
                    str+='&nbsp;&nbsp;&nbsp;';
                    if($("input[name=master]").attr("checked")==true){
                        str+='重点会员';
                    }
					
                    str+='</li><li>'+$('select[name=grade] option:selected').text()+'&nbsp;&nbsp;&nbsp;';

                    if( !isNaN(nexttime) && nexttime>curtime){
                        str+='-&gt; &nbsp;下次联系时间:'+$('#time').val();
                    }
					
                    var interest,different,service,desc,comment;
                    interest=$.trim($('#interest').val());
                    different=$.trim($('#different').val());
                    service=$.trim($('#service').val());
                    desc=$.trim($('#desc').val());
                    comment=$.trim($('#comment').val());
					
                    if (interest.length>0) str+='&nbsp;&nbsp;&nbsp;-> &nbsp;兴趣点：'+interest;
                    if (different.length>0) str+='&nbsp;&nbsp;&nbsp;-> &nbsp;异议点：'+different;
                    if (service.length>0)  str+='&nbsp;&nbsp;&nbsp;-> &nbsp;服务介绍：'+ service;
                    if (desc.length>0)     str+='&nbsp;&nbsp;&nbsp;-> &nbsp;下次跟进要点：'+desc;
                    if (comment.length>0)  str+='&nbsp;&nbsp;&nbsp;-> &nbsp;备注：'+comment;
                    str+='</li></ul>';

                    if (str.length>0) $('#notes').prepend(str);
					
                    setCookie('sn'+uid,curtime);
                    alert('保存成功！');
                    dclick=false;
					
                }
            });
        });
		
		
		$.ajax({
		    url:'./allmember_ajax.php?n=mAlbum',
			data:{uid:uid},
			type:'POST',
			dataType:'json',
			beforeSend:function(){
			    //$('#notes').html('<span style="font-size:20px;">正在加载...</span>');
			},
			success:function(json){
			    //console.log(json);
				//console.log(json.length);
                if(!json) return;
				var html_album='';
				var imgSite="<?php echo IMG_SITE?>";
				for(var i=0;i<json.length;i++){  
					if (i==0){
						html_album+='<a class="fancybox-buttons" data-fancybox-group="button" href="'+imgSite+json[i]['imgurl']+'" style="background:none;border:none;color:#333; display:inline;">查看相册('+json.length+')</a>';
					}else{
						html_album+='<a class="fancybox-buttons" data-fancybox-group="button" href="'+imgSite+json[i]['imgurl']+'" style="display:none" /></a>';
					}
				}		
			    $('#mAlbum').append(html_album);
			}
		});
		
		
		$(document).ready(function() {
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
     
    <!-- <script type="text/javascript" src="templates/js/fancybox/jquery.fancybox-1.2.5.pack.js"></script>
    <link rel="stylesheet" type="text/css" href="templates/css/jquery.fancybox-1.2.5.css"/> -->
	
</body>
</html>
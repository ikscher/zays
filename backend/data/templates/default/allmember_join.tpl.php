<script type="text/javascript" src="templates/js/jquery-1.4.2.min.js"></script>
<?php if(empty($_POST) && empty($action)) { ?>

<?php if(isset($_GET['from'])) { ?><script type="text/javascript" src="templates/js/My97DatePicker/WdatePicker.js"></script><?php } ?>
<style type="text/css">
    #joinoption table { text-align: center; }
    #joinoption td { border: 1px solid #4B76AC; }
    .margin20 { margin-left: 20px; }
    .margin10 { margin-left: 5px; }
    #kefuid { width: 130px; }
    #localid { width: 100px; }
    #joinbutton { margin-top: 5px; }
    #joinstatus { color: #F69; font-size: 13px; margin-left: 15px;}
    #joincontent > div { margin: 5px 0; padding: 5px 0; border-bottom: 3px #CC9933 double; }
    #joincontent p { margin: 3px 0 0 0;	border-bottom: 1px #4B76AC dotted;}
    #joincontent div.info p { text-indent: 2em; }
    #joincontent div.info span { display: block; margin-top: 5px; font-weight: bold; }
    #joincontent p > span { margin-right: 30px;	}
    #joincontent p > span > span { color: #0000FF; font-weight: bold; }
</style>
<div id="joinoption">
    <?php if(isset($_GET['from'])) { ?><h3 style="text-align: center; padding-top: 10px; margin: 0 0 3px 0;">添加或修改交接表</h3><?php } ?>
    <form action="">
        <div>
            <a name="joinpage"></a><a href="#joinpage" id="tojoinpage"></a>
            <span>组名: </span><select id="manageid">
                <option value="0">未选择</option>
                <?php foreach((array)$manage_list as $v) {?>
                <option value="<?php echo $v['id'];?>" <?php if(($manageid == $v['id'])) { ?> selected="selected" <?php } ?>><?php echo $v['manage_name'];?></option>
                <?php } ?>
            </select>
            <span class="margin10">客服: </span><select id="kefuid">
                <option value="0">未选择</option>
                <?php foreach((array)$current_group_kefu_list as $v) {?>
                <option value="<?php echo $v;?>" <?php if(($sid == $v)) { ?> selected="selected"<?php } ?> ><?php echo $v;?>号  <?php echo $server_arr[$v];?></option>
                <?php } ?>
            </select>
            <span class="margin10">委托本站ID: </span><input type="text" id="delegateid" maxlength="255"  size="35"  />&nbsp;&nbsp;
            <span class="margin10">委托全权ID: </span><input type="text" name="" id="joinid"  maxlength="255" size="35" />
            ID可以填写多个，用逗号隔开
            <!-- <span class="margin10">手机号: </span><input type="text" id="telephone" maxlength="11" />	 -->		
        </div>
        <div>
            <span style="display: none; margin-right: 5px;"><span>会员ID: </span><input type="text" id="localid" maxlength="15" value="<?php echo $uid;?>" /></span>
            <span>金额: </span><input type="text" id="money" maxlength="10" />元&nbsp;&nbsp;&nbsp;
            <span class="margin20">服务期限: </span><select id="period">
                <option value="0">未选择</option>
                <?php foreach((array)$periods as $k=>$v) {?>
                <option value="<?php echo $k;?>"><?php echo $v;?></option>
                <?php }?>
            </select>
            <span class="margin20">提交升级时间: </span><input type="text" id="uptime" readonly="true" value="<?php echo $bgtime;?>" class="WdateFmtErr" maxlength="30" />
            <span class="margin20">分配时间: </span><input type="text" id="allottime" value="<?php echo $allotdate;?>" onfocus="WdatePicker({startDate:'%y-%M-%D 00:00:00',dateFmt:'yyyy-MM-dd HH:mm:ss',alwaysUseStartDate:true})" class="WdateFmtErr" maxlength="30" />
        </div>
        <div>
            <span>牵线模式: </span><select id="mode">
                <option value="0">未选择</option>
                <?php foreach((array)$modes as $k=>$v) {?>
                <option value="<?php echo $k;?>"><?php echo $v;?></option>
                <?php }?>
            </select>
            <span class="margin20">是否有模拟: </span><input type="checkbox" id="hasfake" />
            <span class="margin20">是否与VIP交流过: </span><input type="checkbox" id="hastalk" />
            <span class="margin20">是否见过负面: </span><input type="checkbox" id="hasnegative" />
        </div>
        <table>
            <tr>
                <th>升级会员的具体集重点情况</th>
                <th title="和对方会员介绍时的具体情况">模拟的会员详细情况</th>
                <th>最后一次沟通情况</th>
                <th>备注</th>
            </tr>
            <tr>
                <td><textarea id="maininfo" cols="35" rows="7"></textarea></td>
                <td><textarea id="simulateinfo" cols="35" rows="7"></textarea></td>
                <td><textarea id="lastinfo" cols="35" rows="7"></textarea></td>
                <td><textarea id="remark" cols="35" rows="7"></textarea></td>
            </tr>
        </table>
        <div id="joinbutton">
            <input type="button" id="saveinfo" class="sysbtn" value="保存" />
            <input type="button" id="resetinfo" class="sysbtn" value="重置" />
            <span id="joinstatus"></span>
        </div>
    </form>
</div>
<?php } ?> 
<div id="joincontent">

    <?php foreach((array)$join_list as $k=>$v) {?>
    <div data-id="<?php echo $v['id'];?>">
        <p>
            <input type="button" value="修改" class="update" />
            <input type="button" value="删除" class="delete" />
            <input type="button" value="导出" class="excel" />
            <span>第<?php echo $k+1;?>条</span>
            <span class="time"><?php echo date('Y-m-d H:i:s', $v['dateline']);?></span>
            <span><span class="needconvert" data-role="manageid"><?php echo $v['gid'];?></span></span>
            <span><span class="needconvert" data-role="kefuid"><?php echo $v['sid'];?></span></span>
            <span>成单金额: <span><?php echo $v['money'];?></span>元</span>
            <span>服务期限: <span class="needconvert" data-role="period"><?php echo $v['period'];?></span></span>
            <span>牵线模式:<span class="needconvert" data-role="mode"><?php echo $v['mode'];?></span></span>
        </p>
        <p>
            <span>委托本站ID: <span><?php echo $v['mid'];?></span></span>
            <span>全权会员ID: <span><?php echo $v['fid'];?></span></span>
            <span>手机号: <span><?php echo MooMembersData($uid,'telphone');?></span></span>
            <span>升级时间: <span><?php echo date('Y-m-d H:i:s', $v['bgtime']);?></span></span>
            <span>分配时间: <span><?php echo date('Y-m-d H:i:s', MooMembersData($uid,'allotdate'));?></span></span>	
        </p>
        <p>
            <span><span><?php echo $v['isfake'] == 1 ? '有' : '没有';?></span> 网站模拟行为</span>
            <span><span><?php echo $v['istalk'] == 1 ? '已经' : '没有';?></span> 和VIP(全权会员)交流过</span>
            <span><span><?php echo $v['isnegative'] == 1 ? '有' : '没有';?></span> 见过负面</span>		
        </p>
        <div style="margin: 0;" class="info">
            <span>升级会员的具体集重点情况: </span>
            <p><?php echo $v['maininfo'];?></p>
            <span>模拟的会员详细情况: </span>
            <p><?php echo $v['simulateinfo'];?></p>
            <span>最后一次沟通情况: </span>
            <p><?php echo $v['lastinfo'];?></p>
            <span>备注: </span>
            <p style="border-bottom: none;"><?php echo $v['remark'];?></p>
        </div>
    </div>
    <?php }?>
    
</div>

<script type="text/javascript">
    function view_transfer(evt,uid) {
        var evt = evt;
        evt=evt || window.event;
        if(evt.pageX) {
            xPos1=evt.pageX;
            yPos1=evt.pageY;
        } else {  
            xPos1=evt.clientX+document.body.scrollLeft-document.body.clientLeft;
            yPos1=evt.clientY+document.body.scrollTop-document.body.clientTop;    
        }
        $("#transfer_box").css("left",xPos1+25);
        $("#transfer_box").css("top",yPos1-10);
        $("#transfer_box").show(); 
        url = "myuser_ajax.php?n=transfer&uid="+uid;
        $.getJSON(url,function(data) {
            var str = '';
            if(data != 0) {
                str = "原客服ID："+data.sid+"<br>服务期限："+data.servicetime+"个月<br>付款金额："+data.payments+"元<br>委托会员ID："+data.otheruid+"<br>模拟聊天记录："+data.chatnotes+"<br>升级会员情况："+data.intro+"<br>委托会员情况："+data.otherintro+"<br>最后一次沟通情况："+data.lastcom+"<br>备注："+data.remark;
            } else {
                str = '没有交接内容'; 
            }
            $("#transfer_box p").html(str);
        });
    }
	
    var member_join = {
        finished: true,
        updateid: 0,
        sid: <?php echo $sid;?>,
        manageids: "<?php echo $manageids;?>",
        saveinfo: function() {
            member_join.finished = true;
            var info = {};
            info['n'] = 'join';
            if (member_join.updateid) {
                info['id'] = member_join.updateid;
            }
            var selector = {select: 'select', text: 'input:text', textarea: 'textarea'};
            for (var x in selector) {
                if (!member_join.finished) { return; }
				
                $('#joinoption ' + selector[x]).map(function(i, w){
	                
                    if (!member_join.finished) { return; }
                    if ((x == 'select' && w.value != '0') || (x == 'text' && member_join.check(w.id, w.value)) || x == 'textarea') {
                        info[w.id] = $.trim(w.value);
                    } else {
                        member_join.finished = false;
                        member_join.alertneed(w, x);
                    }
                });
            }
            if (!member_join.finished) { return; }
            $('#joinoption input:checkbox').map(function(i, w){
                if (!member_join.finished) { return; }
                info[w.id] = Number(w.checked);
            });
            if (member_join.finished || confirm('还有信息未填或格式错误，确定保存么？')) {
                $.post('./allmember_ajax.php', info, function(data) {
                    alert(member_join.updateid == 0 ? '添加成功！' : '修改成功！');
                    member_join.reset();
                    $('#joincontent').html(data);
                    member_join.convert();
                });
            }
        },
        alertneed: function(n, type) {
            var warning = '还有信息未填写';
            switch (type) {
                case 'select':
                    warning = $(n).prev().text().substr(0, $(n).prev().text().indexOf(':')) + ' 未选择';
                    break;
                case 'text':
                    warning = $(n).prev().text().substr(0, $(n).prev().text().indexOf(':')) + ' 未填写或格式错误';
                    break;
                default :
                    break;
            }
            alert(warning);
            n.focus();
        },
        check: function(id, value) {
            var value = $.trim(value);
            var pattern;
            var result = true;
            if (id == 'localid') {
                result = /^\d{5,}$/.test(value);
            } else if (id == 'delegateid' || (id == 'joinid' && value)) {
               // result = /\d{5,}([,，]\d{5,})*$/.test(value);	
            } else if (id == 'telephone') {
                result = /^1\d{10,10}$/.test(value);
            } else if (id == 'money') {
                result = /^\d+\.?\d*$/.test(value);
            } else if (/uptime|allottime/.test(id)) {
                result = /\d{4,4}-\d{2,2}-\d{2,2} \d{2,2}:\d{2,2}:\d{2,2}/.test(value);
            }
            return result;
        },
        convert: function() {
            $('#joincontent > div').map(function(){
                var needconvert = $(this).find('span.needconvert');
                for (var i = 0; i < needconvert.length; ++i) {
                    var onespan = needconvert.eq(i);
                    var id = onespan.attr('data-role');
                    if (id == 'manageid') { var manageid =  onespan.text(); }
                    if (id == 'kefuid') {
                        var kefuid =  onespan.text();
                        var kefuspan = onespan;
                    }
                    onespan.text($('#' + id + ' option[value=' + onespan.text() + ']').text());
                }
                kefuspan.text(kefuid + '号 ' + member_join.manageids[manageid][kefuid]);
            });
        },
        update: function() {
            member_join.reset();
            var id = $(this).parent().parent().attr('data-id');
			
            var index = $(this).parent().parent().index() + 1;
            $.getJSON('./allmember_ajax.php?n=join&act=query&id=' + id, function(data) {
			   
                if (data == 'fail') {
                    alert('fail'); return;
                }
                member_join.updateid = id;
                $('#joinstatus').text('正在修改条目 ' + index).css('display', 'inline');
                var selector = {val: 'input:text,textarea,select', checkbox: 'input:checkbox'};
                for (var x in selector) {
                    $('#joinoption').find(selector[x]).map(function(i, n){
					    
                        if (x == 'val') {
                            $(n).val(data[n.id]);
                        } else if (x == 'checkbox') {
                            if (data[n.id] == 1) {
                                $(n).click();
                            }
                        }
                    });
                }
                $('#manageid').change();  // 列表联动
                $('#kefuid').val(data['kefuid']);
                $('#uptime, #allottime').removeClass('WdateFmtErr');
                //$('#tojoinpage')[0].click(); // 锚
            });
        },
        deleteid: function() {
            var id = $(this).parent().parent().attr('data-id');
            var uid = $('#localid').val();
            if (confirm('确定要删除么？')) {
                $.get('./allmember_ajax.php?n=join&act=delete&id=' + id + '&uid=' + uid, function(data) {
                    alert('删除成功！');
                    $('#joincontent').html(data);
                    member_join.convert();
                });
            }
        },
        excel:function(){
            var id = $(this).parent().parent().attr('data-id');
            $.post('./allmember_ajax.php?n=excel',{id:id},function(data){
                if(data==1){
                    alert("导出成功");
                }else{
                    alert("导出失败！");
                }
            });
        },
        reset: function() {
            member_join.updateid = 0;
            $('#joinoption form')[0].reset();
            $('#manageid').change();
            $('#joinstatus').css('display', 'none');
        },
        change: function() {
            var manageid = this.value;
            var group_users = member_join.manageids[manageid];
            var option_values = '<option value="0">未选择</option>';
            for (var id in group_users) {
                option_values += '<option value="' + id + '">' + id + '号 ' + group_users[id] + '</option>';
            }
            $('#kefuid').html(option_values).val(member_join.sid);
        }
    };
	
    $('#saveinfo').click(member_join.saveinfo);
    $('#resetinfo').click(member_join.reset);
    $('#manageid').change(member_join.change);
    $('#joincontent').delegate('input.update', 'click', member_join.update);
    $('#joincontent').delegate('input.delete', 'click', member_join.deleteid);
    $('#joincontent').delegate('input.excel', 'click', member_join.excel);
    member_join.convert();
    <?php if(isset($_GET['from'])) { ?>
	<?php if($_GET['from'] != 'up') { ?> $('#localid').parent().css('display', 'inline');<?php } ?>
    $('#joinoption, #joincontent').wrapAll('<div class="userOtherFun"><div class="userOtherFun_content"></div></div>');
    <?php if($uid && $id) { ?> $('#joincontent > div[data-id=<?php echo $id;?>] input.update').click(); <?php } ?>
    <?php } ?>
</script>

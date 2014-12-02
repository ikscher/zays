<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>汇款信息——真爱一生网</title>
<?php include MooTemplate('system/js_css','public'); ?>
<link type="text/css" href="module/payment/templates/default/pay_tab2.css" rel="stylesheet" />
<link href="module/payment/templates/default/payment.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="module/payment/templates/default/js/pay_tab.js?v=1.0"></script>
<!-- <script type="text/javascript" src="public/system/js/iepng.js"></script> -->

</head>
<body>
<?php include MooTemplate('system/header','public'); ?>
<div class="main">
	
	<div class="content">
		<div class="clear"></div>
		<div class="payment">
			<div class="pm-bank-banner">
			    
				<div class="payment_ad_paltinum" style="width: 580px;background: url(http://img.7651.com/static/front/images/servicegj_icon.png) no-repeat;"><?php echo $orderTitle;?></div>
				
			</div><!--pm-bank-banner end-->
			
			
			<div class="pm-content">
				<p class="f-b-d73c90 f-14x" style="padding-top:20px;">请选择您最熟悉的支付方式进行付款升级</p>
				<p class="f-14x" style="line-height:28px; padding-top:10px;">在线支付即时到账，银行转账通常2个工作日内到帐，请在汇款成功后，保留汇款凭证以便我们客服人员与您电话核实。</p><!--steps end-->
				
				
				<div class="payLeft">
					<ul>
					   <li class="tit01" id="vf_01" value="0">网银支付</li>
					   <li class="tit02" id="vf_02" value="1">第三方支付</li>
					   <li class="tit02" id="vf_03" style="border-bottom:1px solid #F2B775;" value="2" >银行汇款</li>
					</ul>
				</div>
			
				
				<div class="bank-online" id="vf_01_">
					<form method="post" name="onlinebank" action="index.php?n=payment&h=onlinebank" target="_blank">
					<div class="bank-online-r">
						<ul>
							<?php foreach((array)$yeepay_banktype as $k=>$banktype) {?>
							<li><input type="radio" id="<?php echo $k;?>" name="bank" value="<?php echo $k;?>"/><label for="<?php echo $k;?>"><img src="module/payment/templates/default/images/banklogo/<?php echo $banktype;?>"></label></li>
							<?php }?>
						</ul>
					<input type="hidden" name="channel" value="<?php echo $pay_h;?>">
					<?php if($pay_h=='pay_add_money_other') { ?>
					<input type="hidden" name="p3_Amt" value="<?php echo $p3_Amt;?>">
					<input type="hidden" name="text" value="<?php echo $text;?>">
					<?php } ?>
					
					<input type="button"  id="onlinebank"  class="btn btn-primary" value="确认支付"/>
					</div>
					</form>
					<div class="clear"></div>
				</div>
			
				
				<!-- <p class="f-14x" style="padding:20px 0;"><img src="module/payment/templates/default/images/light.gif" />&nbsp;&nbsp;<span class="f-b-d73c90">信用卡支付：</span> 操作简单，安全支付，快速升级。</p>
				<div class="bank-online">
					<div class="bank-online-l">请选择支付方式:</div>
					<form name="creditcard" method="post" action="index.php?n=payment&h=creditcardinline" target="_blank">
					<div class="bank-online-r">
						<ul>
							<li><input type="radio" name="pay" id="yeepayepos" value="yeepayepos" checked /><label for="yeepayepos"><img src="module/payment/templates/default/images/banklogo/yibao.gif"></label></li>
                        </ul>
					<input type="hidden" name="channel" value="<?php echo $pay_h;?>">
					<input type="hidden" name="p3_Amt" value="<?php echo $p3_Amt;?>">
					<input type="hidden" name="text" value="<?php echo $text;?>">
					<input type="submit" class="btn btn-primary" value="确认支付"/>
					</div>
					</form>
					<div class="clear"></div>
				</div> -->	
                
                
                <div class="bank-online h" id="vf_02_" >

                    <form name="onlinepay" method="post" action="index.php?n=payment&h=onlinepay" target="_blank">
                        <div class="bank-online-r">
                        <ul>
							<li>
                            <input type="radio" name="pay" id="zhifubao" value="zhifubao" checked /><label for="zhifubao"><img src="module/payment/templates/default/images/zhifubao.gif"></label>
							</li>
							<li>
                            <input type="radio" name="pay" id="tenpay" value="tenpay"  /><label for="tenpay"><img src="module/payment/templates/default/images/tenpay.gif"></label>
                            </li>
							 <li>
                            <input type="radio" name="pay" id="yeepay" value="yeepay" /><label for="yeepay"><img src="module/payment/templates/default/images/yeepay.gif"></label>
                            </li>
                         </ul>
						    <div style="margin-top:60px;clear:both;border-top:1px solid #eee;padding-top:10px;">
                            <input type="hidden" name="channel" value="<?php echo $pay_h;?>">
							<input type="hidden" name="p3_Amt" value="<?php echo $p3_Amt;?>">
							<input type="hidden" name="text" value="<?php echo $text;?>">
                            <input type="submit"  class="btn btn-primary" value="确认支付"/>
                            </div>
                        </div>
                    </form>
					
					
					
                    <div class="clear"></div>
                   
                    <div class="thirdparty"><span class="f-14x-d73e90">支付宝账号 ：1557334568@qq.com </span>&nbsp;&nbsp;&nbsp;&nbsp; <span class="f-14x-d73e90">财付通帐号 ：1557334568 &nbsp;&nbsp;&nbsp;&nbsp; 账户名 ：李东敏</span></div>
                      <div class="clear"></div>
           
                </div>    

				<!-- 电话支付开始    -->
				
                <!-- <p class="f-14x" style="padding:20px 0;"><img src="module/payment/templates/default/images/light.gif" />&nbsp;&nbsp;<span class="f-b-d73c90">电话支付：</span>您只要用您的手机就可以完成支付。</p>
			   
			    <div id="pay_page_card">
					<div class="pay_header">
					    
				    	<a class="pay_two">
				        	<em>借记卡 </em>
				            <span><img src="module/payment/templates/default/images/telphone/wx_ico.png" /></span>
				        </a>
				        <div class="clear"></div>
				    </div>
				    <div class="pay_main">
					
						 
				        <div id="debit_card">
				        	<div class="explain">支持借记卡(即储蓄卡)，无需开通网银，请选择银行：</div>
				            <div><img src="module/payment/templates/default/images/telphone/titletop.gif" /></div>
							<form name="formDeposit"  action="index.php?n=payment&h=telpay" target="_blank" method="post" onsubmit="return checkDeposit()">
				            
				            <ol>
				            
				            	<li><input name="depositBank" onclick="selectRadio(this.value)" type="radio" id="dc_CMBC" value="dc_CMBC" />&nbsp;<label for="dc_CMBC"><img src="module/payment/templates/default/images/telphone/zhaohang.gif" title="招商银行" disabled /></label></li>
				            	<li><input name="depositBank" onclick="selectRadio(this.value)" type="radio" id="dc_ABC" value="dc_ABC" />&nbsp;<label for="dc_ABC"><img src="module/payment/templates/default/images/telphone/nongye.gif" title="农业银行" disabled /></label></li>
				            
				            	<li><input name="depositBank" onclick="selectRadio(this.value)" type="radio" id="dc_EMS" value="dc_EMS" />&nbsp;<label for="dc_EMS"><img src="module/payment/templates/default/images/telphone/youzheng.gif" title="中国邮政储蓄" disabled /></label></li>
				            	<li><input name="depositBank" onclick="selectRadio(this.value)" type="radio" id="dc_BCM" value="dc_BCM" />&nbsp;<label for="dc_BCM"><img src="module/payment/templates/default/images/telphone/jiaotong.gif" title="中国交通银行" disabled /></label></li>
				            	
				            	<li><input name="depositBank" onclick="selectRadio(this.value)" type="radio" id="dc_CITIC" value="dc_CITIC" />&nbsp;<label for="dc_CITIC"><img src="module/payment/templates/default/images/telphone/zhongxin.gif" title="中信银行" disabled /></label></li>
								<li><input name="depositBank" onclick="selectRadio(this.value)" type="radio" id="dc_GDB" value="dc_GDB" />&nbsp;<label for="dc_GDB"><img src="module/payment/templates/default/images/banklogo/guangfa.gif" title="广东发展银行" disabled /></label></li>
				            	
				            	<li><input name="depositBank" onclick="selectRadio(this.value)" type="radio" id="dc_HXB" value="dc_HXB" />&nbsp;<label for="dc_HXB"><img src="module/payment/templates/default/images/banklogo/huaxia.gif" title="华夏银行" disabled  /></label></li>
				            	
				                <div class="clear"></div>
				            </ol>
							<ul id = 'formDeposit' style="display:none">
				            	<li>
				                    <p class="p_yellow">
				                    	<span id = 'depositCard_err'>银行卡号不能为空</span>
				                    </p>
									银行卡号：<input name="depositCard" id = 'depositCard' type="text"   />
				                </li>
				            	<li>
				                    <p class="p_yellow">
				                    	<span id = 'depositTelphone_err'>请输入手机号码</span>
				                    </p>
				                	手机号码：<input name="depositTelphone" id = 'depositTelphone' type="text"  />
				                </li>
				            	<li>
				                    <p class="p_yellow">
				                    	<span id = 'depositIDCard_err'>请输入身份证号</span>
				                    </p>
				                	身份证号：<input name="depositIDCard" id = 'depositIDCard' type="text"  />
				                </li>
								<input type="hidden" name="channel" value="<?php echo $pay_h;?>">
								<input type="hidden" name="p3_Amt" value="<?php echo $p3_Amt;?>">
					            <input type="hidden" name="text" value="<?php echo $text;?>">
				                <li class="begin_pay"><input type="hidden" name="cardType" value="2" /><input name="" style="cursor:pointer;" id = 'payDeposit' type="submit" value="开始支付" />&nbsp;提交后您将接到号码为 <span class="org">02195156</span>的来电！</li>
				            </ul>
							</form>
				            <div class="pay_show"> 
				            	<h3>借记卡-手机支付流程演示：</h3>
				                <ol>
				                	<li class="f-14x">1.提交卡号，身份证号和手机号</li>
				                	<li class="f-14x">2.手机接听银联自动语音电话</li>
				                	<li class="f-14x" style="font-size:14px; overflow:hidden; width:84px;">3.支付成功</li>
				                </ol>
				            </div>
				            <div class="clear"></div>
				        </div>
				    </div>
				
				
				</div> -->
             
               <!--  电话支付结束 -->
			   
				
				<div class="bank-online h" id="vf_03_" >
					<div class="bankAccount">
					<table width="100%" border="0" cellspacing="0" cellpadding="0" class="bank-table">
						<tr>
							<td height="110" align="right"><img src="module/payment/templates/default/images/bk01.jpg" /></td>
							<td>
								<dl>
									<dt>中国工商银行</dt>
									<dd>开户行：中国工商银行合肥科大支行</dd>
									<dd>卡号：<span class="f-14x-d73e90">6222 0213 0202 3223 612</span></dd>
								</dl>
							</td>
							<td>账户名：李东敏</td>
						</tr>
						<tr>
							<td height="110" align="right"><img src="module/payment/templates/default/images/bk02.jpg" /></td>
							<td>
								<dl>
									<dt>招商银行</dt>
									<dd>开户行：招商银行合肥金屯支行</dd>
									<dd>卡号：<span class="f-14x-d73e90">6214 8355 1115 6042</span></dd>
								</dl>
							</td>
							<td>账户名：李东敏</td>
						</tr> 
						<tr>
							<td height="110" align="right"><img src="module/payment/templates/default/images/bk03.jpg" /></td>
							<td>
								<dl>
									<dt>中国建设银行</dt>
									<dd>开户行：建设银行合肥城南支行</dd>
									<dd>卡号：<span class="f-14x-d73e90">6217 0016 3000 6474 909</span></dd>
								</dl>
							</td>
							<td>账户名：李东敏</td>
						</tr>
						<tr>
							<td height="110" align="right"><img src="module/payment/templates/default/images/bk04.jpg" /></td>
							<td>
								<dl>
									<dt>交通银行</dt>
									<dd>开户行：交通银行安徽省分行新天地广场支行</dd>
									<dd>卡号：<span class="f-14x-d73e90">6222 6202 5000 2357 205</span></dd>
								</dl>
							</td>
							<td>账户名：李东敏</td>
						</tr>
						<tr>
							<td height="110" align="right"><img src="module/payment/templates/default/images/bk05.jpg" /></td>
							<td>
								<dl>
									<dt>中国银行</dt>
									<dd>开户行：中国银行合肥南城支行</dd>
									<dd>卡号：<span class="f-14x-d73e90">6217 9063 0000 3565 978</span></dd>
								</dl>
							</td>
							<td>账户名：李东敏</td>
						</tr>
						<tr>
							<td height="110" align="right"><img src="module/payment/templates/default/images/bk06.jpg" /></td>
							<td>
								<dl>
									<dt>中国农业银行</dt>
									<dd>开户行：中国农业银行合肥安居苑分理处</dd>
									<dd>卡号：<span class="f-14x-d73e90">6228 4806 6842 0290 771</span></dd>
								</dl>
							</td>
							<td >账户名：李东敏</td>
						</tr>
						<!-- <tr>
							<td height="110" align="right"><img src="module/payment/templates/default/images/bk07.gif" /></td>
							<td>
								<dl>
									<dt>中国光大银行</dt>
									<dd>开户行：光大银行合肥濉溪路支行</dd>
									<dd>卡号：<span class="f-14x-d73e90">6226 6230 0130 4173</span></dd>
								</dl>
							</td>
							<td>账户名：</td>
						</tr> -->
						<tr>
							<td height="110" align="right"><img src="module/payment/templates/default/images/bk08.gif" /></td>
							<td>
								<dl>
									<dt>中国邮政邮政储蓄银行</dt>
									<dd>开户行：中国邮政储蓄银行安徽省分行直属支行</dd>
									<dd>卡号：<span class="f-14x-d73e90">6232 1836 1000 0290 674</span></dd>
								</dl>
							</td>
							<td>账户名：李东敏</td>
						</tr>
						<tr>
							<td height="110" align="right"><img src="module/payment/templates/default/images/bk09.jpg" /></td>
							<td>
								<dl>
									<dt>兴业银行</dt>
									<dd>开户行：兴业银行合肥黄山路支行</dd>
									<dd>卡号：<span class="f-14x-d73e90">6229 0949 3396 3107 16</span></dd>
								</dl>
							</td>
							<td>账户名：李东敏</td>
						</tr> 
						<tr>
							<td height="110" align="right"><img src="module/payment/templates/default/images/bk10.gif" /></td>
							<td>
								<dl>
									<dt>中信银行</dt>
									<dd>开户行：中信银行马鞍山路支行</dd>
									<dd>卡号：<span class="f-14x-d73e90">6217 7323 0004 2060</span></dd>
								</dl>
							</td>
							<td>账户名：李东敏</td>
						</tr> 
						<tr>
							<td height="110" align="right"><img src="module/payment/templates/default/images/bank_hftech.jpg" /></td>
							<td>
								<dl>
									<dt>合肥科技农村商业银行</dt>
									<dd>开户行：合肥科技银行五里墩分理处</dd>
									<dd>卡号：<span class="f-14x-d73e90">6229 5381 0010 5942 500</span></dd>
								</dl>
							</td>
							<td>账户名：李东敏</td>
						</tr>
						<!-- <tr>
							<td height="110" align="right"><img src="module/payment/templates/default/images/bk12.gif" /></td>
							<td>
								<dl>
									<dt>中国民生银行</dt>
									<dd>开户行：民生银行合肥营业部</dd>
									<dd>卡号：<span class="f-14x-d73e90">6226 2234 8008 2554</span></dd>
								</dl>
							</td>
							<td>账户名：</td>
						</tr> -->
					</table>
					</div>
			    </div>
				
			</div><!--pm-content end-->
		</div>
		<div class="clear"></div>
	</div><!--content end-->
	
	<div class="footer">
	  <div class="g">品牌：专业婚恋服务&nbsp; 专业：庞大的资深红娘队伍&nbsp; 真实：诚信会员验证体系&nbsp; </div>
	  <div class="g">Copyright@<?php echo date('Y');?> 真爱一生网.All Right Reserved.<a  href="http://www.miitbeian.gov.cn/" target="_blank">皖ICP备14002819号</a> </div>
	  <div class="g">客服热线：400-8787-920 （周一至周日 9：00-21：00）客服信箱：kefu@zhenaiyisheng.cc</div>
	</div>
</div><!--main end-->
<script type="text/javascript" src="module/payment/templates/default/js/check.js?v=3.0"></script>
<script type="text/javascript" src="module/payment/templates/default/js/scrollDoor.js?v=1.0"></script>
<script type="text/javascript">
window.onload = function(){var SDmodel = new scrollDoor();SDmodel.sd(["vf_01","vf_02","vf_03"],["vf_01_","vf_02_","vf_03_"],"tit01","tit02");}
</script>
</body>
</html>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>升级高级会员——真爱一生网</title>
<?php include MooTemplate('system/js_css','public'); ?>
<link href="module/payment/templates/default/payment.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="d">
<?php if($GLOBALS['MooUid']) { ?>
	<div class="logined">
		您的用户名是：<?php echo $GLOBALS['MooUserName']?MooCutstr($GLOBALS['MooUserName'],16):$user_arr['nickname'];?>
	   &nbsp;&nbsp; 您的ID是：<?php echo $GLOBALS['MooUid'];?>&nbsp;&nbsp;
		最新邮件(<a href="index.php?n=service&h=message"><?php echo $GLOBALS['new_email_num']?>) <a href="service.html">进入我的红娘&gt;&gt;</a> <a href="index.php?n=index&h=logout">安全退出</a>
	</div><?php } ?>
</div>
<div class="main">
    <div class="content">
		<div class="payment">
			<div class="vip-banner">
				<div class="banner-box">
				<p style="margin-top:20px;"><img src="module/payment/templates/default/images/go-see01.gif" /></p>
				<p><img  src="module/payment/templates/default/images/pay-num01.gif" /><a  href="index.php?n=payment&h=channel"><img src="module/payment/templates/default/images/<?php echo (isset($s_cid) && $s_cid=='30') ? 'go-on' : 'open';?>.gif" /></a>
                </p>
				<p><img src="module/payment/templates/default/images/top-tel.gif" /></p>
				</div>
				<b class="vipicon"><img src="module/payment/templates/default/images/vip-icon.gif" /></b>
				<div class="clear"></div>
			</div><!--pm-banner end-->
			<div class="pm-content">
			<p style="padding:0 25px 10px;"><img src="module/payment/templates/default/images/con-title01.gif" /></p>
			<p class="pm-text">真爱一生网通过大量研究发现，网络征婚领域中，几乎所有成功的恋爱关系都是在诚信的网络征婚平台上经过相识、相见、相恋这3个阶段，因此高级会员所提供的所有服务正是基于这三个必经阶段而开发的。</p>
			<div class="steps">
				<div class="step-box">
					<div class="s-box-title">第一阶段：相识</div>
					<ul class="s-box-center">
						<li><a href="javascript:;" onclick="openShutManager(this,'pbox1');">查看</a>1.精准介绍合适人选
							<dl id="pbox1" class="pm-text-box">
								<dt>平时工作忙，会员资料又浩如烟海，不知道怎样在最短时间里找到心仪的人？</dt>
								<dd>真爱一生不仅会帮您在3000多万会员中精心筛选你心目中的白雪公主（白马王子），而且还有“一对一”个性约会，使你和你心仪的人迅速相连，开始缘分。</dd>
							</dl>
						</li>
						<li><a href="javascript:;" onclick="openShutManager(this,'pbox2');">查看</a>2.帮助双方有效联系 
							<dl id="pbox2" class="pm-text-box">
								<dt>我能用什么方式联系上对方本人？如何确保我能得到回复，并了解对方的交往意向呢？</dt>
								<dd>真爱一生不仅会为您开通站内邮件和在线聊天等方便的沟通工具，还会响应您的需求，代您主动与对方联系，帮您牵线搭桥，相识成功率更高。</dd>
							</dl>
						</li>
						<li><a href="javascript:;" onclick="openShutManager(this,'pbox3');">查看</a>3.提供个性化征婚指导 
							<dl id="pbox3" class="pm-text-box">
								<dt>我怎样开始和对方接触？我的形象是不是让对方满意？什么人比较对我感兴趣？</dt>
								<dd>真爱一生网拥有全球最大的婚恋人群资料库，人群定位和配对技术属于世界领先。并拥有多项专有知识产权的真爱一生分析和指导系统，助你确定自身定位，树立良好形象。让爱情路上初起步的你，面对新相识，信心更足。</dd>
							</dl>
						</li>
						<li><a href="javascript:;" onclick="openShutManager(this,'pbox4');">查看</a>4.相识之后，帮忙撮合 
							<dl id="pbox4" class="pm-text-box">
								<dt>有些问题，我想深入了解一下对方，但又不好意思开口，怎么办？</dt>
								<dd>当遇到细节问题，比如对方的感情史？对方的父母的情况？对方对您的印象如何……为避免尴尬，你可以通过真爱一生代为询问。并能协助您沟通心仪对象的交往意向，促进双方交往。</dd>
							</dl>
						</li>
					</ul>
					<div class="s-box-bottom"></div>
				</div><!--step-box end-->
				<div class="step-box" style="margin:0 27px;">
					<div class="s-box-title02">第二阶段：相见</div>
					<ul class="s-box-center">
						<li><a href="javascript:;" onclick="openShutManager(this,'pbox5');">查看</a>1.见面之前，加深了解
							<dl id="pbox5" class="pm-text-box">
								<dt>由于网络的虚拟性，见面前我想全面了解对方资料，从哪里才能获得对方的详细资料？</dt>
								<dd>与对方见面前，真爱一生为您提供全面和完整的信息，帮助您全面掌握资料，降低误解，了解对方兴趣爱好。</dd>
							</dl>
						</li>
						<li><a href="javascript:;" onclick="openShutManager(this,'pbox6');">查看</a>2.安排双方安全见面 
							<dl id="pbox6" class="pm-text-box">
								<dt>从网上到见面，如何确保这个过程是安全和有效的呢？</dt>
								<dd>真爱一生在约会前将向您详细交代约会中所有的安全注意事项，并且为双方的实际约会方案提供建议。真爱一生网为你搭建开放式自助交友平台，做“一对一”引见、约会。会员的安全始终是真爱一生网工作的第一要项，对于任何不真诚的行为，真爱一生网将坚决予以预防和制止，我们矢志不渝地不断改进各种会员安全措施。</dd>
							</dl>
						</li>
						<li><a href="javascript:;" onclick="openShutManager(this,'pbox7');">查看</a>3.提供全程约会指导 
							<dl id="pbox7" class="pm-text-box">
								<dt>初次见面的印象很重要，我怎样才能展现最佳形象呢？我该如何应对聊天时出现的尴尬？</dt>
								<dd>真爱一生网长期进行各种专业婚恋行为研究,总结出行之有效的多种解决方案。 在见面前，真爱一生将给予双方详细的资料（兴趣爱好、家庭状况等），并设计心理、行为、话题、穿着打扮等方面的最优化约会指导与建议。让您以最佳姿态出现在心仪对象面前，让初次见面互相留下好印象，从而为下一步相恋的展开打下基础。</dd>
							</dl>
						</li>
						<li><a href="javascript:;" onclick="openShutManager(this,'pbox8');">查看</a>4.相见之后，帮忙撮合 
							<dl id="pbox8" class="pm-text-box">
								<dt>见面之后，我如何了解对方意向？并能让对方更深入地了解我？</dt>
								<dd>见面之后，真爱一生将尽快深入了解并转达双方的意向。当对方令您满意时，真爱一生会根据您的决定做出更进一步的推进计划，帮助撮合双方。</dd>
							</dl>
						</li>
					</ul>
					<div class="s-box-bottom"></div>
				</div><!--step-box end-->
				<div class="step-box">
					<div class="s-box-title03">第三阶段：恋爱</div>
					<ul class="s-box-center">
						<li><a href="javascript:;" onclick="openShutManager(this,'pbox9');">查看</a>1.解决矛盾，增进感情
							<dl id="pbox9" class="pm-text-box">
								<dt>相识相处时发生矛盾怎么办？能提供帮助和指导吗？</dt>
								<dd>情到浓时更需要处理好恋人之间的关系，努力栽培，让爱情开花结果。此时，真爱一生网4年的经验协助至关重要，真爱一生专业的恋爱指导，确保相恋顺利。</dd>
							</dl>
						</li>
						<li><a href="javascript:;" onclick="openShutManager(this,'pbox10');">查看</a>2.提供恋爱婚姻指导 
							<dl id="pbox10" class="pm-text-box">
								<dt>恋爱中出现的问题我不知道该怎样去处理？</dt>
								<dd>爱情本身就意味着一种生活，也只有在琐碎的，具体的，日常生活中才能体现的淋漓尽致。真爱一生网充分了解中国消费者的社会环境和他们的思想行为模式，并有多年指导婚恋的经验。真爱一生会协助你考虑的更全面、仔细，作出更合理的决定。</dd>
							</dl>
						</li>
						<li><a href="javascript:;" onclick="openShutManager(this,'pbox11');">查看</a>3.把握时机，促成婚姻 
							<dl id="pbox11" class="pm-text-box">
								<dt>对方有结婚意愿吗？我怎样向对方提出结婚意愿？他的家人是如何看待的？……</dt>
								<dd>真爱一生通过信息反馈机制，将对方相恋的抉择更清晰地掌握和告知，协助促成婚姻。祝您早日步入结婚殿堂！</dd>
							</dl>
						</li>
					</ul>
					<div class="s-box-bottom"></div>
				</div><!--step-box end-->
				<div class="clear"></div>
			</div><!--steps end-->
			<h6 style="margin-bottom:30px;"><img src="module/payment/templates/default/images/con-title02.gif" /></h6>
			<table width="905" border="0" cellspacing="0" cellpadding="0" class="pm-table">
				<tr>
					<th width="208" height="68" align="center" style=" background:url(module/payment/templates/default/images/tab_bg.gif) no-repeat; line-height:35px;"><span style="margin-left:45px;">会员类型</span><br />
						<span style="margin-right:45px;">服务内容</span></th>
					<th><span><img alt="钻石会员" title="钻石会员" src="module/index/templates/default/images/zuan0.gif">&nbsp;钻石会员</span></th>
					<th><span><img alt="高级会员" title="高级会员" src="module/index/templates/default/images/zuan1.gif">&nbsp;高级会员</span></th>
					<th><span>普通会员</span></th>
				</tr>
				<tr>
					<td height="35" align="center" class="f-ed0a91">邮件沟通</td>
					<td class="f-ed0a91">有</td>
					<td class="f-ed0a91">有</td>
					<td>无</td>
				</tr>
				<tr bgcolor="#f4f4f4">
					<td height="35" align="center" class="f-ed0a91">IM在线聊天</td>
					<td class="f-ed0a91">有</td>
					<td class="f-ed0a91">有</td>
					<td class="f-ed0a91">可回复高级会员</td>
				</tr>
				<tr>
					<td height="35" align="center" class="f-ed0a91">红娘贴心回访</td>
					<td class="f-ed0a91">有</td>
					<td class="f-ed0a91">有</td>
					<td>无</td>
				</tr>
				<tr bgcolor="#f4f4f4">
					<td height="35" align="center" class="f-ed0a91">红娘协助牵线</td>
					<td class="f-ed0a91">有</td>
					<td class="f-ed0a91">有</td>
					<td>无</td>
				</tr>
				<tr>
					<td height="35" align="center" class="f-ed0a91">红娘推动见面</td>
					<td class="f-ed0a91">有</td>
					<td class="f-ed0a91">有</td>
					<td>无</td>
				</tr>
				<tr bgcolor="#f4f4f4">
					<td height="35" align="center" class="f-ed0a91">个人信息展示</td>
					<td class="f-ed0a91">显要位置显示</td>
					<td class="f-ed0a91">有</td>
					<td class="f-ed0a91">仅查看标题</td>
				</tr>
				<tr>
					<td height="35" align="center" class="f-ed0a91">主动搜索会员</td>
					<td class="f-ed0a91">有</td>
					<td class="f-ed0a91">有</td>
					<td>无</td>
				</tr>
				<tr bgcolor="#f4f4f4">
					<td height="35" align="center" class="f-ed0a91">诚信认证</td>
					<td class="f-ed0a91">有</td>
					<td class="f-ed0a91">有</td>
					<td>无</td>
				</tr>
				<tr>
					<td height="35" align="center" class="f-ed0a91">上传背景音乐</td>
					<td class="f-ed0a91">有</td>
					<td>无</td>
					<td>无</td>
				</tr>
				<tr bgcolor="#f4f4f4">
					<td height="35" align="center" class="f-ed0a91">红色显示</td>
					<td class="f-ed0a91">有</td>
					<td class="f-ed0a91">有</td>
					<td>无</td>
				</tr>
				<tr>
					<td height="35" align="center" class="f-ed0a91">视频介绍</td>
					<td class="f-ed0a91">有</td>
					<td>无</td>
					<td>无</td>
				</tr>
				<tr bgcolor="#f4f4f4">
					<td height="35" align="center" class="f-ed0a91">才艺展示</td>
					<td class="f-ed0a91">有</td>
					<td>无</td>
					<td>无</td>
				</tr>
				<tr>
					<td height="35" align="center" class="f-ed0a91">婚恋专家星座生肖点评</td>
					<td class="f-ed0a91">有</td>
					<td>无</td>
					<td>无</td>
				</tr>
				<tr bgcolor="#f4f4f4">
					<td height="35" align="center" class="f-ed0a91">亲友团助威</td>
					<td class="f-ed0a91">有</td>
					<td>无</td>
					<td>无</td>
				</tr>
				<tr>
					<td height="35" align="center" class="f-ed0a91">人生经历</td>
					<td class="f-ed0a91">有</td>
					<td>无</td>
					<td>无</td>
				</tr>
				<tr bgcolor="#f4f4f4">
					<td height="35" align="center" class="f-ed0a91">发起约会</td>
					<td class="f-ed0a91">有</td>
					<td>无</td>
					<td>无</td>
				</tr>
				<tr>
					<td height="35" align="center" class="f-ed0a91">特殊标识</td>
					<td class="f-ed0a91">有</td>
					<td class="f-ed0a91">有</td>
					<td>无</td>
				</tr>
				<tr bgcolor="#f4f4f4">
					<td height="35" align="center" class="f-ed0a91">专属高级红娘高质服务</td>
					<td class="f-ed0a91">有</td>
					<td class="f-ed0a91">有</td>
					<td>无</td>
				</tr>
				<tr>
					<td height="35" align="center" class="f-ed0a91">专业编辑内容优化</td>
					<td class="f-ed0a91">有</td>
					<td>无</td>
					<td>无</td>
				</tr>
				<!-- <tr bgcolor="#f4f4f4">
					<td height="35" align="center" class="f-ed0a91">设计师定制个人主页</td>
					<td class="f-ed0a91">有</td>
					<td>无</td>
					<td>无</td>
				</tr> -->
				<tr>
					<td height="35" align="center" class="f-ed0a91">优惠价格</td>
					<td class="f-ed0a91">3599元/6个月</td>
					<td class="f-ed0a91">2199元/3个月</td>
					<td class="f-ed0a91">免费</td>
				</tr>
			</table>
			<div class="cs-bottom"><a href="index.php?n=payment&h=channel"><img src="module/payment/templates/default/images/<?php echo (isset($s_cid) && $s_cid=='30') ? 'go-on' : 'open';?>.gif" /></a>
            </div>
			</div><!--pm-content end-->
		</div>
	</div><!--content end-->
	 <div style="height: 100px;text-align: center;padding-top: 20px;border-top:1px solid #999;">
		<div class="g">品牌：8年专业婚恋服务&nbsp; 专业：庞大的资深红娘队伍&nbsp; 真实：诚信会员验证体系&nbsp; </div>
		<div class="g">Copyright 2006-2014 真爱一生网.All Right Reserved.<a  href="http://www.miitbeian.gov.cn/" target="_blank">皖ICP备14002819号</a> </div>
		<div class="g">客服热线：400-8787-920 （周一至周日：9：00-21：00）客服信箱：kefu@zhenaiyisheng.cc</div>
	</div>
</div><!--main end-->
</body>
</html>
<script type="text/javascript">
//===========================点击展开关闭效果====================================
function openShutManager(oSourceObj,oTargetObj,shutAble,oOpenTip,oShutTip){
var sourceObj = typeof oSourceObj == "string" ? document.getElementById(oSourceObj) : oSourceObj;
var targetObj = typeof oTargetObj == "string" ? document.getElementById(oTargetObj) : oTargetObj;
var openTip = oOpenTip || "";
var shutTip = oShutTip || "";
if(targetObj.style.display!="block"){
   if(shutAble) return;
   targetObj.style.display="block";
   if(openTip && shutTip){
    sourceObj.innerHTML = shutTip; 
   }
} else {
   targetObj.style.display="none";
   if(openTip && shutTip){
    sourceObj.innerHTML = openTip; 
   }
}
}
</script>

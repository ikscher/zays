<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VIP会员通道——真爱一生网</title>
<?php include MooTemplate('system/js_css','public'); ?>
<link href="module/payment/templates/default/payment.css" rel="stylesheet" type="text/css" />
<link href="public/default/css/gototop.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="d">
<?php if($GLOBALS['MooUid']) { ?>
	<div class="logined">
		您的用户名是：<?php echo $GLOBALS['MooUserName']?MooCutstr($GLOBALS['MooUserName'],16):$user_arr['nickname'];?>
	   &nbsp;&nbsp; 您的ID是：<?php echo $GLOBALS['MooUid'];?>&nbsp;&nbsp;
		最新邮件(<a href="service.html"><?php echo $GLOBALS['new_email_num']?>) <a href="service.html">我的征婚信息中心&gt;&gt;</a> <a href="logout.html">安全退出</a>
	</div>
<?php } else { ?>
	<div class="logined">
	    <a class="_login_" href="javascript:void(0);">登录</a>&nbsp;&nbsp;<a href="register.html">注册</a>
	</div>
<?php } ?>
</div>
<!--升级高级会员开始-->
<div class="main vkl" id="vf_01_">
    <div class="content">
		<div class="payment">
			<div class="vip-banner">
				<div class="banner-box">
				<p style="margin-top:20px;"><img src="module/payment/templates/default/images/go-see01.gif" /></p>
				<p><img  src="module/payment/templates/default/images/pay-num01.gif" /><a   class="upgradeHeigherMember" href="javascript:void(0);"><img src="module/payment/templates/default/images/<?php echo (isset($s_cid) && $s_cid=='30') ? 'go-on' : 'open';?>.gif" /></a>
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
			<div class="cs-bottom"><a class="upgradeHeigherMember" href="javascript:void(0);"><img src="module/payment/templates/default/images/<?php echo (isset($s_cid) && $s_cid=='30') ? 'go-on' : 'open';?>.gif" /></a>
            </div>
			</div><!--pm-content end-->
		</div>
	</div><!--content end-->
	 
</div>
<!--升级高级会员结束-->

<div class="main h vkl" id="vf_02_">

	<!--升级钻石会员-->
	<div id="vip_content">


		<div id="vip_ad"><img src="module/payment/templates/default/images/vip_ad.jpg" /><div class="into"><a  class="upgradeDiamondMember" href="javascript:void(0);"><img src="module/payment/templates/default/images/<?php echo ($s_cid=='20') ? 'goon' : 'vip_button';?>.jpg" /></a></div></div>
		<div id="category">
			<div class="post">
				<h2><img src="module/payment/templates/default/images/vip_recommend.jpg" /></h2>
				<div class="entry recommend">
					您是否仍在苦苦寻觅那个他/她？不要再被动的等待了！想要有缘人主动来到您的身边吗？
					立即升级为钻石会员，让您从3000多万用户中脱颖而出！
				</div>
			</div>
			<div class="post">
				<h2><img src="module/payment/templates/default/images/vip_service.jpg" /></h2>
				<div class="entry service">
					<dl>
						<dt>一、个人介绍和才艺展示</dt>
						<dd>
							你是否还停留在以貌取人、单纯依赖文字性资料的误区？想要让那个他（她）了解更加真实生动的你吗？马上加入视频介绍和才艺展示吧！
							让你一步到位的找到心心相印、心灵默契的人。视频介绍和才艺展示的三大好处。
							<ul>
								<li>第一，展示真实自我风采！</li>
								<li>第二，吸引更多关注，提升交友几率！</li>
								<li>第三，多层面立体化的沟通方式，快速增进了解！即开通此服务</li>
							</ul>
						</dd>
						<dt>二、从星座生肖看爱情运势</dt>
						<dd>每个人的星座生肖中都隐藏着爱情玄机！这里有专业的婚恋专家针对您的星座生肖进行解说，分析什么样的异性与你相匹配，同时让异性更
							深入的了解你的真性情。令你散发诱惑性哦！你也可以说出你的恋爱史，让异性从你曾经的爱情故事中，感受你的真诚，用这颗清澈透明的求爱
							之心感动对方!</dd>
						<dt>三、亲友团助阵</dt>
						<dd>想让你的亲朋好友为你加油助阵吗？通过亲友团对你的评价，异性可以对你的生活圈子有充分的了解，让你更加形象生动！从而激起靠近你
							的欲望喔!</dd>
						<dt>四、打造最佳约会</dt>
						<dd>工作很忙？不喜欢单一的约会项目？让真爱一生来帮您分忧吧！高级专属真爱一生会根据你的时间和喜欢的约会项目，为你打造属于你的最佳约会，
							高效的网上寻爱服务，让你轻轻松松找到心仪之人！</dd>
						<dt>五、显要的位置排名</dt>
						<dd>想要异性第一眼看到你吗？钻石会员不仅搜索排名靠前,并且个人资料可以排在所在省市的首页，在异性会员的搜索结果中优先显示。</dd>
						<dt>六、独享尊贵标识</dt>
						<dd>个人资料带有“钻石”闪光标识，突出尊贵身份，从众多普通会员中脱颖而出！</dd>
					</dl>
				</div>
			</div>
			<div class="post">
				<h2><img src="module/payment/templates/default/images/vip_function.jpg" /></h2>
				<div class="entry function">
					<img src="module/payment/templates/default/images/vip_function_img.jpg" />
				</div>
			</div>
			<div class="post">
				<h2><img src="module/payment/templates/default/images/vip_contrast.jpg" /></h2>
				<div class="entry contrast">
					<div class="title"><img src="module/payment/templates/default/images/vip_contrast_title.jpg" /></div>
					<table width="828" cellspacing="0">
						<!-- <tr>
							<td class="style1">
								<p><span>特色视频功能--展现真实生动的个人和才艺</span></p>
								<p>可以通过视频介绍自己，展现你的多才多艺。</p>
								<a target="_blank" href="index.php?n=myaccount&h=vhelp"><img src="module/payment/templates/default/images/vip_contrast_ask.jpg" /></a>
							</td>
							<td class="style2"><p>无视频功能</p></td>
							<td class="style3"><p>普通会员基本寻爱功能</p></td>
						</tr> -->
						<tr>
							<td class="style1">
								<p><span>婚恋专家点评星座生肖--更好的分析适合你的异性特征</span></p>
								<p>专家通过对星座生肖的点评，了解什么样的异性与己匹配，让符合特
									征的异性主动来到你身边。</p>
							</td>
							<td class="style2"><p>无此功能</p></td>
							<td class="style3"><p>无此功能</p></td>
						</tr>
						<tr>
							<td class="style1">
								<p><span>随心沟通--联络更自由</span></p>
								<p>3000多万会员想与谁联络就与谁联络。主动把握自己的爱情！</p>
							</td>
							<td class="style2"><p>可以与部分普通会员、VIP会员沟通</p></td>
							<td class="style3"><p>只能与部分高级会员和VIP沟通，被动等待爱情</p></td>
						</tr>
						<tr>
							<td class="style1">
								<p><span>显眼位置排名--脱颖而出</span></p>
								<p>钻石会员不仅搜索排名靠前,并且个人资料可以排在所在省市的首页，
									在异性会员的搜索结果中优先显示。</p>
							</td>
							<td class="style2"><p>无显要位置排名</p></td>
							<td class="style3"><p>无显要位置排名</p></td>
						</tr>
						<tr>
							<td class="style1">
								<p><span>VIP钻石特殊标识--身份更尊贵</span></p>
								<p>VIP闪光标识受到异性会员更诚意关注，关注率与收信率大大提升，
									爱情的脚步快速临近！</p>
							</td>
							<td class="style2"><p>有特殊标识</p></td>
							<td class="style3"><p>有特殊标识</p></td>
						</tr>
						<tr>
							<td class="style4" colspan="3">
								<p><span>基础功能--身份更尊贵</span></p>
								<p>创建个人档案，心理测试与心灵匹配，搜索意中人，浏览用户资料，和意中人打招呼，发送接收秋波和鲜花，向真爱一生发起委托。</p>
							</td>
						</tr>
					</table>
				</div>
			</div>
			<a  class="upgradeDiamondMember into" href="javascript:void(0);"><img src="module/payment/templates/default/images/<?php echo ($s_cid=='20') ? 'goon' : 'vip_button';?>.jpg" /></a>
		</div>
	</div>
</div>
<!--升级钻石会员结束-->

<!--升级铂金会员开始-->
<div class="main h vkl" id="vf_03_">
	<div class="content">
		<div class="pt_banner">
			<div class="pt_logo"></div>
			<div><a  class="upgradePlatinumMember manu_one" href="javascript:void(0);" ></a></div>
		</div>
		<div class="pt_main">
			<div class="main_header">
				<div class="main_header_left"></div>
				<div class="main_header_right"></div>
			</div>
			<div class="main_con">
				<ol class="pt_mem">
					<li class="rings"></li>
					<li class="mem_title"></li>
				</ol>   
				<div class="clear"></div> 
				<div class="mem_font">
					<p>您是否仍在苦苦寻觅那个他/她？</p>
					<p>不要再被动的等待了！想要有缘人主动来到您的身边吗？铂金会员是真爱一生网与众多电视媒体合力打造，具有众多优先推荐权，包括优先</p>
					<p>参与录制电视相亲节目，享受三对一至尊真爱一生服务的高端会员。无需被动等待，独特的身份标识，让您从3000多万用户中脱颖而出！</p>
			  </div>
				<ol class="pt_func">
					<li class="rings"></li>
					<li class="func_title"></li>
				</ol>
				<div class="clear"></div> 
				<div class="func_font">铂金会员尊享四大专利（四大专利十分用心、为您的爱情加10分）</div>
				<div class="clear"></div>
				<ol class="ol">
					<li><img src="module/payment/templates/default/images/platinum/a_03.gif" /></li>
					<li><img src="module/payment/templates/default/images/platinum/a_05.gif" /></li>
					<li><img src="module/payment/templates/default/images/platinum/a_06.gif" /></li>
					<li><img src="module/payment/templates/default/images/platinum/a_07.gif" /></li>
					<li><img src="module/payment/templates/default/images/platinum/a_08.gif" /></li>
				</ol>
				<div><a  class="upgradePlatinumMember manu_two" href="javascript:void(0);" ></a></div>
			</div>
		</div>
	</div>
</div>
<!--升级铂金会员结束-->

<!--升级城市之星开始-->
<div class="main h vkl" id="vf_04_" >
	<img src="module/payment/templates/default/images/citystar.jpg" border="0" usemap="#Map"  />
		<map name="Map" id="Map">
	    <area shape="rect" coords="703,348,924,390" class="upgradeCityStarMember" href="javascript:void(0);" target="_blank" />
        <area shape="rect" coords="381,1285,601,1327" class="upgradeCityStarMember" href="javascript:void(0);" target="_blank" />
	</map>
    
</div>
<!--升级城市之星结束-->

<div class="navR">
	<span id="vf_01" class="t01">高级会员</span>			
	<span id="vf_02" class="t02">钻石会员</span>
	<span id="vf_03" class="t02">铂金会员</span>
	<span id="vf_04" class="t02">城市之星</span>
</div>

<!--登录模态框-->
<div class="modal fade" id="myModal" tabindex="0" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false" >
  <div class="modal-dialog">
	<div class="modal-content">
	  <div class="modal-header">
		<!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> -->
		<h5 class="modal-title" id="myModalLabel">登录后，您将了解更多会员的征婚信息和吸引更多的异性会员关注 </h5>
	  </div>
	  <div class="modal-body">
	        <div class="login-box">
				<form id="loginform" name="loginform" action="index.php?n=index&h=submit" method="post" onsubmit="return checkLoginForm();">
					<p><input type="text" name="username" id="username"   class="form-control" value=""  class="login-text" /></p>
					<p><input type="password" name="password" id="password" class="form-control"   class="login-text"/></p>
					<p><span class="userTips h"></span><span class="pwdTips h"></span></p>
					<p><input style="width:20px;" name="remember" type="checkbox" id="remember" value="1"  /><label for="remember">记住账号</label>
						<a href="#"  onclick="javascript:location.href='index.php?n=login&h=backpassword'" class="f-ed0a91-a">忘记密码？</a>
						<a href='register.html' class="f-ed0a91-a">我要成为会员</a></p> 
					<p><input type="submit"  name="btnLogin" class="btn btn-success ft" value="登 录" />
					<span class="fakeuname" >ID，手机号，邮箱 都 可以作为账号</span> 
			        <span class="fakepwd" >密码</span> 
					<input type="hidden" name="returnurl" value="upgrade.html" />
				</form>
			</div>
			<div class="login-box-right">
			     <span>告诉我一个地址，让我到你心里；给我一个密码，让我打开你的心门。我们衷心祝福所有在真爱一生网认识的会员都能终成眷属！</span>
			</div>
	  </div>
	  <!-- <div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
	  </div> -->
	</div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>
<!--登录模态框-->

<div style="height: 100px;text-align: center;padding-top: 20px;border-top:1px solid #999;margin-top:3px;">
	<div class="g">品牌：专业婚恋服务&nbsp; 专业：庞大的资深红娘队伍&nbsp; 真实：诚信会员验证体系&nbsp; </div>
	<div class="g">Copyright@<?php echo date('Y');?> 真爱一生网.All Right Reserved.<a  href="http://www.miitbeian.gov.cn/" target="_blank">皖ICP备14002819号</a> </div>
	<div class="g">客服热线：400-8787-920 （周一至周日：9：00-21：00）客服信箱：kefu@zhenaiyisheng.cc</div>
</div>
</body>
</html>
<script src="public/system/js/jquery.modal.min.js" type="text/javascript"></script>
<script type="text/javascript" src="module/payment/templates/default/js/scrollDoor.js?v=1.0"></script>
<script type="text/javascript">
window.onload = function(){ var uid=<?php echo $uid;?>;if(!uid || typeof uid=='undefined'){ openLogin();}var SDmodel = new scrollDoor();SDmodel.sd(["vf_01","vf_02","vf_03","vf_04"],["vf_01_","vf_02_","vf_03_","vf_04_"],"t01","t02");}
 function openLogin(){
		$('#myModal').modal({
			backdrop: 'static',
			keyboard: true,
			modal:true
		})
	}
$('.upgradeHeigherMember').on('click',function(){
    <?php if($GLOBALS['MooUid']) { ?>
		location.href='index.php?n=payment&h=channel';
	<?php } else { ?>
		openLogin();
	<?php } ?>
});

$('.upgradeDiamondMember').on('click',function(){
    <?php if($GLOBALS['MooUid']) { ?>
		location.href='index.php?n=payment&h=channel_diamond';
	<?php } else { ?>
		openLogin();
	<?php } ?>
   
});

$('.upgradePlatinumMember').on('click',function(){
    <?php if($GLOBALS['MooUid']) { ?>
		location.href='index.php?n=payment&h=channel_platinum';
		return false;
	<?php } else { ?>
		openLogin();
	<?php } ?>
   
});

$('.upgradeCityStarMember').on('click',function(){
    <?php if($GLOBALS['MooUid']) { ?>
		location.href='index.php?n=payment&h=city_star';
		return false;
	<?php } else { ?>
		openLogin();
	<?php } ?>
   
});

$('._login_').on('click',function(){
    openLogin();
});



//检测表单
function checkLoginForm(){
	$('.userTips').html('');
	$('.pwdTips').html('');
	var username = $('#username').val().replace(/\s/g,'');
	var password = $('#password').val().replace(/\s/g,'');
	if(!username || username==''){
		$('.userTips').html('请输入账号');
		$('.userTips').removeClass('h');
		$('.userTips').addClass('s');
		return false;
	}else if (!CheckEmail(username) && !chk_phone(username) && !/^\d{8,8}$/.test(username)){
		$('.userTips').html('输入的用户名格式不正确');
		$('.userTips').removeClass('h');
		$('.userTips').addClass('s');
		return false;
	}
	if(!password){
		$('.pwdTips').html('请输入密码');
		$('.pwdTips').removeClass('h');
		$('.pwdTips').addClass('s');
		return false;
	}
	return true;
}
	
	
//表单输入框的提示信息
$('input[name=username]').focus(function(){
   $('.userTips').addClass('h');
   $('.fakeuname').addClass('h');
	$('.userTips').removeClass('s');
   $('.fakeuname').removeClass('s');
}).blur(function(){
   var username = $.trim($(this).val());
   if(!username) {$('.fakeuname').removeClass('h');$('.fakeuname').addClass('s');}
});

$('input[name=password]').focus(function(){
   $('.fakepwd').addClass('h');
   $('.pwdTips').addClass('h');
	$('.fakepwd').removeClass('s');
   $('.pwdTips').removeClass('s');
}).blur(function(){
   var pwd = $.trim($(this).val());
   if(!pwd) { $('.fakepwd').addClass('s');$('.fakepwd').removeClass('h');}
});

$('.fakeuname').on('click',function(){
   $('input[name=username]').trigger('focus');
});

$('.fakepwd').on('click',function(){
   $('input[name=password]').trigger('focus');
});


</script>
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

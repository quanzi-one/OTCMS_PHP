<?php
require(dirname(__FILE__) .'/check.php');
$skin->CheckIframe();

/* **** 版权所有 ****

 网钛CMS(OTCMS)
 程序开发：网钛科技
 官方网站：http://otcms.com
 联系ＱＱ：877873666
 联系邮箱：877873666@qq.com

 **** 版权所有 **** */


//打开用户表，并检测用户是否登录
$MB->Open('','login');

$skin->WebTop();


echo('
<script language="javascript" type="text/javascript" src="js/system.js?v='. OT_VERSION .'"></script>
');



switch($mudi){
	case 'companyInfo':
		$MB->IsSecMenuRight('alertBack',40,$dataType);
		companyInfo();
		break;

	default:
		die('err');
}

$skin->WebBottom();

$MB->Close();
$DB->Close();





// 新增、修改
function companyInfo(){
	global $DB,$skin,$mudi,$dataType,$dataTypeCN,$sysAdminArr;

	$revexe=$DB->query('select * from '. OT_dbPref .'system');
		if ($row = $revexe->fetch()){
			$SYS_title			= $row['SYS_title'];
			$SYS_titleSign		= $row['SYS_titleSign'];
			$SYS_titleAddi		= $row['SYS_titleAddi'];
			$SYS_URL			= $row['SYS_URL'];
				if (! Is::HttpUrl($SYS_URL)){ $SYS_URL = GetUrl::HttpHead() . $SYS_URL; }
				if (substr($SYS_URL,-1) != '/'){ $SYS_URL .= '/'; }
			$SYS_isUrl301		= $row['SYS_isUrl301'];
			$SYS_webKey			= $row['SYS_webKey'];
			$SYS_webDesc		= $row['SYS_webDesc'];
			$SYS_isFloatAd		= $row['SYS_isFloatAd'];
			$SYS_isClose		= $row['SYS_isClose'];
			$SYS_closeNote		= $row['SYS_closeNote'];
			$SYS_dbCharset		= $row['SYS_dbCharset'];
			$SYS_isLogErr		= $row['SYS_isLogErr'];
			$SYS_isTplErr		= $row['SYS_isTplErr'];
			$SYS_isAjaxErr		= $row['SYS_isAjaxErr'];
			$SYS_isTimeInfo		= $row['SYS_isTimeInfo'];
			$SYS_isBaiduSitemap	= $row['SYS_isBaiduSitemap'];
			$SYS_verCodeMode	= $row['SYS_verCodeMode'];
			$SYS_verCodeStr		= $row['SYS_verCodeStr'];
			$SYS_geetestID		= $row['SYS_geetestID'];
			$SYS_geetestKey		= $row['SYS_geetestKey'];
			$SYS_templateDir	= $row['SYS_templateDir'];	//y
			$SYS_htmlCacheMin	= $row['SYS_htmlCacheMin'];
			$SYS_htmlCacheTime	= $row['SYS_htmlCacheTime'];
			$SYS_eventStr		= $row['SYS_eventStr'];

			$SYS_titleHome		= $row['SYS_titleHome'];
			$SYS_titleList		= $row['SYS_titleList'];
			$SYS_titleShow		= $row['SYS_titleShow'];
			$SYS_titleWeb		= $row['SYS_titleWeb'];
			$SYS_titleSearch	= $row['SYS_titleSearch'];
			$SYS_titleMark		= $row['SYS_titleMark'];
			$SYS_titleTopic		= $row['SYS_titleTopic'];
			if (AppBbs::Jud()){
				$SYS_titleBbsList	= $row['SYS_titleBbsList'];
				$SYS_titleBbsShow	= $row['SYS_titleBbsShow'];
				$SYS_titleBbsSearch	= $row['SYS_titleBbsSearch'];
			}else{
				$SYS_titleBbsList	= '';
				$SYS_titleBbsShow	= '';
				$SYS_titleBbsSearch	= '';
			}

			$SYS_announName		= $row['SYS_announName'];

			$SYS_isHtmlHome			= $row['SYS_isHtmlHome'];

			$SYS_htmlUrlDel			= $row['SYS_htmlUrlDel'];
			$SYS_htmlUrlJump		= $row['SYS_htmlUrlJump'];
			$SYS_htmlUrlDir			= $row['SYS_htmlUrlDir'];
			$SYS_diyInfoTypeDir		= $row['SYS_diyInfoTypeDir'];
			$SYS_htmlInfoTypeDir	= $row['SYS_htmlInfoTypeDir'];
			$SYS_htmlDatetimeDir	= $row['SYS_htmlDatetimeDir'];
			$SYS_newsListUrlMode	= $row['SYS_newsListUrlMode'];
			$SYS_newsListFileName	= $row['SYS_newsListFileName'];
			$SYS_newsShowUrlMode	= $row['SYS_newsShowUrlMode'];
			$SYS_newsShowFileName	= $row['SYS_newsShowFileName'];
			$SYS_dynWebUrlMode		= $row['SYS_dynWebUrlMode'];
			$SYS_dynWebFileName		= $row['SYS_dynWebFileName'];

			$SYS_proxyIpPoint		= $row['SYS_proxyIpPoint'];
			$SYS_proxyIpList		= $row['SYS_proxyIpList'];
		}
	unset($revexe);

	echo('
	<form id="dealForm" name="dealForm" method="post" action="system_deal.php?mudi='. $mudi .'" onsubmit="return CheckForm()">
	<input type="hidden" id="dataType" name="dataType" value="'. $dataType .'" />
	<input type="hidden" id="dataTypeCN" name="dataTypeCN" value="'. $dataTypeCN .'" />
	<script language="javascript" type="text/javascript">document.write(\'<input type="hidden" name="backURL" value="\'+ document.location.href +\'" />\')</script>

	<div class="tabMenu">
	<ul>
	   <li rel="tabBase" class="selected">基本信息</li>
	   <li rel="tabWeb" style="display:none;">页面设置</li>
	   <li id="seoBox" rel="tabSeo" style="display:none;">SEO设置</li>
	   <li id="staicBox" rel="tabStaic">首页静态</li>
	   <li id="newsBox" rel="tabNews" style="display:none;">文章路径</li>
	   <li id="buyBox" rel="tabBuy" style="display:none;">商业版专属</li>
	</ul>
	</div>

	<div class="tabMenuArea">
		<table id="tabBase" cellpadding="0" cellspacing="0" summary="" class="padd3">
		<tr><td style="width:150px;"></td><td></td></tr>
		<tr>
			<td align="right">网站图标：</td>
			<td style="color:red;">
				<a href="../favicon.ico" style="font-weight:bold;" class="font3_2" target="_blank"><img src="../favicon.ico" style="max-width:64px;max-height:64px;" />
				根目录/favicon.ico</a> 是网站ico图标，常见于显示在浏览器标题前，该文件可以替换为自己的。
			</td>
		</tr>
		<tr>
			<td align="right">网站名称：</td>
			<td><input type="text" id="title" name="title" size="50" style="width:500px;" value="'. $SYS_title .'" /></td>
		</tr>
		<tr>
			<td align="right">网站标题连接符：</td>
			<td>
				<input type="text" id="titleSign" name="titleSign" size="50" style="width:40px;" value="'. $SYS_titleSign .'" />
				<span class="font3_2">&ensp;如：连接符“_”，列表页：栏目名称<span style="color:red;">_</span>网站名称，内容页：文章标题<span style="color:red;">_</span>栏目名称<span style="color:red;">_</span>网站名称</span>
			</td>
		</tr>
		<tr>
			<td align="right" valign="top" style="padding-top:8px;">网站标题附加内容：</td>
			<td>
				<input type="text" id="titleAddi" name="titleAddi" size="50" style="width:500px;" value="'. $SYS_titleAddi .'" />
				<div class="font3_2">为了灵活性，该附加内容与网站标题之间系统不会自动添加连接符。</div>
			</td>
		</tr>
		<!-- <tr>
			<td align="right">网站网址模式：</td>
			<td>
				<label><input type="radio" id="isUrl3012" name="isUrl301" value="2" '. Is::Checked($SYS_isUrl301,2) .' onclick="CheckIsUrl301()" />默认</label>&ensp;&ensp;
				<label><input type="radio" id="isUrl3010" name="isUrl301" value="0" '. Is::Checked($SYS_isUrl301,0) .' onclick="CheckIsUrl301()" />用base标签网址锁定</label><span class="font2_2">(不推荐)</span>&ensp;&ensp;
			</td>
		</tr>
		<tr id="webUrlBox">
			<td align="right" valign="top" style="padding-top:8px;">网站网址：</td>
			<td>
				<input type="text" id="webURL" name="webURL" size="50" style="width:500px;" value="'. $SYS_URL .'" />
				<div class="font3_2" style="color:red;padding-bottom:5px;">如该网站有多个域名，请填写主打域名，不然<b>请</b>留空</div>
			</td>
		</tr> --><input type="hidden" name="isUrl301" value="2" />
		<tr>
			<td align="right" valign="top" style="padding-top:6px;">
				网站关键字(Keywords)：<br />
				<span class="font3_2">（便于搜索引擎搜到）<br />多个用英文逗号“,”隔开</span>
			</td>
			<td style="padding-bottom:4px;"><textarea id="webKey" name="webKey" rows="5" cols="40" style="width:500px; height:60px;">'. $SYS_webKey .'</textarea></td>
		</tr>
		<tr>
			<td align="right" valign="top" style="padding-top:6px;">
				网站描述(Description)：<br />
				<span class="font3_2">（便于搜索引擎搜到）</span>
			</td>
			<td style="padding-bottom:4px;"><textarea id="webDesc" name="webDesc" rows="5" cols="40" style="width:500px; height:60px;">'. $SYS_webDesc .'</textarea></td>
		</tr>
		<tr>
			<td align="right">网站开关：</td>
			<td>
				<select id="isClose" name="isClose" onchange="CheckCloseK();">
					<option value="20" '. Is::Selected($SYS_isClose,20) .'>网站开启</option>
					<option value="10" '. Is::Selected($SYS_isClose,10) .'>网站关闭</option>
				</select>
			</td>
		</tr>
		<tr id="closeNoteK" style="display:none;">
			<td align="right" valign="top">网站关闭说明：</td>
			<td>
				<textarea id="closeNote" name="closeNote" cols="40" rows="4" style="width:510px;height:120px;" class="text" onclick=\'LoadEditor("closeNote",510,120,"|miniMenu|");\' title="点击开启编辑器模式">'. Str::MoreReplace($SYS_closeNote,'textarea') .'</textarea>
			</td>
		</tr>
		<tr>
			<td align="right">数据库编码：</td>
			<td>
				<select id="dbCharset" name="dbCharset">
					<option value="utf8" '. Is::Selected($SYS_dbCharset,'utf8') .'>utf8（默认）</option>
					<option value="utf8mb4" '. Is::Selected($SYS_dbCharset,'utf8mb4') .'>utf8mb4</option>
				</select>&ensp;
				<span style="color:red;">（千万不要乱设置，错误编码会导致数据库乱码）</span>
			</td>
		</tr>
		<tr class="verCodeBox">
			<td align="right">验证码模式：</td>
			<td>
				<select id="verCodeMode" name="verCodeMode" onchange="CheckVerCodeMode()">
					<option value="1" '. Is::Selected($SYS_verCodeMode,1) .'>数字验证码1</option>
					<option value="2" '. Is::Selected($SYS_verCodeMode,2) .'>数字验证码2</option>
					<option value="3" '. Is::Selected($SYS_verCodeMode,3) .'>数字验证码3</option>
					<option value="4" '. Is::Selected($SYS_verCodeMode,4) .'>数字验证码4</option>
					<!-- <option value="5" '. Is::Selected($SYS_verCodeMode,5) .'>中文验证码</option> -->
					<option value="20" '. Is::Selected($SYS_verCodeMode,20) .'>滑动验证码（极验）</option>
				</select>
				<span style="position:relative;"><img src="temp/verCode.png" style="display:none;position:absolute;border:1px #000 solid;" /></span>
			</td>
		</tr>
		<tr class="geetestClass">
			<td align="right" class="font1_2d">极验ID：</td>
			<td align="left">
				<input type="text" id="geetestID" name="geetestID" size="50" style="width:250px;" value="'. $SYS_geetestID .'" />&ensp;&ensp;
				<span style="color:red;">极验申请网址：<a href="http://www.geetest.com/" target="_blank">http://www.geetest.com/</a></span>
			</td>
		</tr>
		<tr class="geetestClass">
			<td align="right" class="font1_2d">极验KEY：</td>
			<td align="left">
				<input type="text" id="geetestKey" name="geetestKey" size="50" style="width:250px;" value="'. $SYS_geetestKey .'" />&ensp;&ensp;
				<span style="color:red;">极验申请教程：<a href="http://otcms.com/news/8004.html" target="_blank">http://otcms.com/news/8004.html</a></span>
			</td>
		</tr>
		<tr>
			<td align="right" class="font1_2d">验证码开启范围：</td>
			<td>
				<label><input type="checkbox" name="verCodeStr[]" value="|admin|" '. Is::InstrChecked($SYS_verCodeStr,'|admin|') .' />后台登录</label>&ensp;&ensp;
				<label><input type="checkbox" name="verCodeStr[]" value="|login|" '. Is::InstrChecked($SYS_verCodeStr,'|login|') .' />会员登录</label>&ensp;&ensp;
				<!-- <label><input type="checkbox" name="verCodeStr[]" value="|news|" '. Is::InstrChecked($SYS_verCodeStr,'|news|') .' />前台投稿</label>&ensp;&ensp; -->
				<label><input type="checkbox" name="verCodeStr[]" value="|newsReply|" '. Is::InstrChecked($SYS_verCodeStr,'|newsReply|') .' />内容页评论区</label>&ensp;&ensp;
				<label><input type="checkbox" name="verCodeStr[]" value="|message|" '. Is::InstrChecked($SYS_verCodeStr,'|message|') .' />留言板</label>&ensp;&ensp;
				'. AppBbs::SystemBox1($SYS_verCodeStr) .'
			</td>
		</tr>
		<tr>
			<td align="right">调试信息写入错误日志：</td>
			<td>
				<label><input type="checkbox" name="eventStr[]" value="|alipayErr|" '. Is::InstrChecked($SYS_eventStr,'|alipayErr|') .'/>支付宝支付出错</label>&ensp;&ensp;
				<label><input type="checkbox" name="eventStr[]" value="|weixinErr|" '. Is::InstrChecked($SYS_eventStr,'|weixinErr|') .'/>微信支付出错</label>&ensp;&ensp;
			</td>
		</tr>
		<tr>
			<td align="right">程序错误写入日志：</td>
			<td>
				<select id="isLogErr" name="isLogErr">
					<option value="1" '. Is::Selected($SYS_isLogErr,1) .'>开启</option>
					<option value="0" '. Is::Selected($SYS_isLogErr,0) .'>关闭</option>
				</select>
				&ensp;&ensp;'. $skin->TishiBox('开启后，程序错误信息自动保存到 数据库目录/softErr.log') .'
			</td>
		</tr>
		<tr>
			<td align="right">网站模板错误提示：</td>
			<td>
				<select id="isTplErr" name="isTplErr">
					<option value="1" '. Is::Selected($SYS_isTplErr,1) .'>开启</option>
					<option value="0" '. Is::Selected($SYS_isTplErr,0) .'>关闭</option>
				</select>
				<!-- &ensp;&ensp;'. $skin->TishiBox('当前台出现class_template.php错误时，开启该项可以看到详细错误信息') .' -->
			</td>
		</tr>
		<tr style="display:none;">
			<td align="right">前台AJAX错误提示：</td>
			<td>
				<select id="isAjaxErr" name="isAjaxErr">
					<option value="1" '. Is::Selected($SYS_isAjaxErr,1) .'>开启</option>
					<option value="0" '. Is::Selected($SYS_isAjaxErr,0) .'>关闭</option>
				</select>
			</td>
		</tr>
		<tr>
			<td align="right">网站公告名称：</td>
			<td>
				<input type="text" id="announName" name="announName" value="'. $SYS_announName .'" style="width:100px;" />
				&ensp;&ensp;<span class="font2_2">（更多公告设置在【模板管理】-【模板参数设置】[列表页/内容页]）</span>
			</td>
		</tr>
		</table>


		<table id="tabSeo" style="display:none;" cellpadding="0" cellspacing="0" summary="" class="padd3">
		<tr><td style="width:150px;"></td><td></td></tr>
		<tr>
			<td align="right" valign="top" style="padding-top:8px;"></td>
			<td style="color:red;font-size:14px;padding:5px 0;">没特殊要求，这块SEO设置请不要动，设置不对会严重影响SEO，默认的就是官方觉得最适合的。</td>
		</tr>
		<tr>
			<td align="right" valign="top" style="padding-top:8px;">首页网页title：</td>
			<td>
				<input type="text" id="titleHome" name="titleHome" size="50" style="width:500px;" value="'. $SYS_titleHome .'" />
				<div class="font3_2" style="padding-bottom:5px;">
					<span class="font1_2d pointer" onclick=\'$id("titleHome").value="{%网站标题%}{%网站标题附加%}";\'>[默认]</span>&ensp;
					<span class="pointer" onclick=\'InputAddText("titleHome","{%网站标题%}")\'>{%网站标题%}</span>&ensp;
					<span class="pointer" onclick=\'InputAddText("titleHome","{%网站标题附加%}")\'>{%网站标题附加%}</span>
				</div>
			</td>
		</tr>
		<tr>
			<td align="right" valign="top" style="padding-top:8px;">列表页网页title：</td>
			<td>
				<input type="text" id="titleList" name="titleList" size="50" style="width:500px;" value="'. $SYS_titleList .'" />
				<div class="font3_2" style="padding-bottom:5px;">
					<span class="font1_2d pointer" onclick=\'$id("titleList").value="{%栏目名称%}{%父栏目名称%}{%栏目名称附加%}{%页码%}_{%网站标题%}";\'>[默认]</span>&ensp;
					<span class="pointer" onclick=\'InputAddText("titleList","{%网站标题%}")\'>{%网站标题%}</span>&ensp;
					<span class="pointer" onclick=\'InputAddText("titleList","{%网站标题附加%}")\'>{%网站标题附加%}</span>&ensp;
					<span class="pointer" onclick=\'InputAddText("titleList","{%栏目名称%}")\'>{%栏目名称%}</span>&ensp;
					<span class="pointer" onclick=\'InputAddText("titleList","{%父栏目名称%}")\'>{%父栏目名称%}</span>&ensp;
					<span class="pointer" onclick=\'InputAddText("titleList","{%栏目名称附加%}")\'>{%栏目名称附加%}</span>&ensp;
					<span class="pointer" onclick=\'InputAddText("titleList","{%页码%}")\'>{%页码%}</span>
				</div>
			</td>
		</tr>
		<tr>
			<td align="right" valign="top" style="padding-top:8px;">内容页网页title：</td>
			<td>
				<input type="text" id="titleShow" name="titleShow" size="50" style="width:500px;" value="'. $SYS_titleShow .'" />
				<div class="font3_2" style="padding-bottom:5px;">
					<span class="font1_2d pointer" onclick=\'$id("titleShow").value="{%文章标题%}_{%栏目名称%}{%父栏目名称%}{%文章标题附加%}{%页码%}_{%网站标题%}";\'>[默认]</span>&ensp;
					<span class="pointer" onclick=\'InputAddText("titleShow","{%网站标题%}")\'>{%网站标题%}</span>&ensp;
					<span class="pointer" onclick=\'InputAddText("titleShow","{%网站标题附加%}")\'>{%网站标题附加%}</span>&ensp;
					<span class="pointer" onclick=\'InputAddText("titleShow","{%栏目名称%}")\'>{%栏目名称%}</span>&ensp;
					<span class="pointer" onclick=\'InputAddText("titleShow","{%父栏目名称%}")\'>{%父栏目名称%}</span>&ensp;
					<span class="pointer" onclick=\'InputAddText("titleShow","{%文章标题%}")\'>{%文章标题%}</span>&ensp;
					<span class="pointer" onclick=\'InputAddText("titleShow","{%文章标题附加%}")\'>{%文章标题附加%}</span>&ensp;
					<span class="pointer" onclick=\'InputAddText("titleShow","{%页码%}")\'>{%页码%}</span>
				</div>
			</td>
		</tr>
		<tr>
			<td align="right" valign="top" style="padding-top:8px;">单篇页网页title：</td>
			<td>
				<input type="text" id="titleWeb" name="titleWeb" size="50" style="width:500px;" value="'. $SYS_titleWeb .'" />
				<div class="font3_2" style="padding-bottom:5px;">
					<span class="font1_2d pointer" onclick=\'$id("titleWeb").value="{%标题%}_{%网站标题%}{%标题附加%}";\'>[默认]</span>&ensp;
					<span class="pointer" onclick=\'InputAddText("titleWeb","{%网站标题%}")\'>{%网站标题%}</span>&ensp;
					<span class="pointer" onclick=\'InputAddText("titleWeb","{%网站标题附加%}")\'>{%网站标题附加%}</span>&ensp;
					<span class="pointer" onclick=\'InputAddText("titleWeb","{%标题%}")\'>{%标题%}</span>&ensp;
					<span class="pointer" onclick=\'InputAddText("titleWeb","{%标题附加%}")\'>{%标题附加%}</span>
				</div>
			</td>
		</tr>
		<tr>
			<td align="right" valign="top" style="padding-top:8px;">搜索页网页title：</td>
			<td>
				<input type="text" id="titleSearch" name="titleSearch" size="50" style="width:500px;" value="'. $SYS_titleSearch .'" />
				<div class="font3_2" style="padding-bottom:5px;">
					<span class="font1_2d pointer" onclick=\'$id("titleSearch").value="搜索({%搜索类型%})：{%搜索词%}{%页码%}_{%网站标题%}";\'>[默认]</span>&ensp;
					<span class="pointer" onclick=\'InputAddText("titleSearch","{%网站标题%}")\'>{%网站标题%}</span>&ensp;
					<span class="pointer" onclick=\'InputAddText("titleSearch","{%网站标题附加%}")\'>{%网站标题附加%}</span>&ensp;
					<span class="pointer" onclick=\'InputAddText("titleSearch","{%搜索词%}")\'>{%搜索词%}</span>&ensp;
					<span class="pointer" onclick=\'InputAddText("titleSearch","{%搜索类型%}")\'>{%搜索类型%}</span>&ensp;
					<span class="pointer" onclick=\'InputAddText("titleSearch","{%页码%}")\'>{%页码%}</span>
				</div>
			</td>
		</tr>
		<tr>
			<td align="right" valign="top" style="padding-top:8px;">标签页网页title：</td>
			<td>
				<input type="text" id="titleMark" name="titleMark" size="50" style="width:500px;" value="'. $SYS_titleMark .'" />
				<div class="font3_2" style="padding-bottom:5px;">
					<span class="font1_2d pointer" onclick=\'$id("titleMark").value="标签：{%标签%}{%页码%}_{%网站标题%}";\'>[默认]</span>&ensp;
					<span class="pointer" onclick=\'InputAddText("titleMark","{%网站标题%}")\'>{%网站标题%}</span>&ensp;
					<span class="pointer" onclick=\'InputAddText("titleMark","{%网站标题附加%}")\'>{%网站标题附加%}</span>&ensp;
					<span class="pointer" onclick=\'InputAddText("titleMark","{%标签%}")\'>{%标签%}</span>&ensp;
					<span class="pointer" onclick=\'InputAddText("titleMark","{%页码%}")\'>{%页码%}</span>
				</div>
			</td>
		</tr>
		<tr>
			<td align="right" valign="top" style="padding-top:8px;">专题页网页title：</td>
			<td>
				<input type="text" id="titleTopic" name="titleTopic" size="50" style="width:500px;" value="'. $SYS_titleTopic .'" />
				<div class="font3_2" style="padding-bottom:5px;">
					<span class="font1_2d pointer" onclick=\'$id("titleTopic").value="专题：{%专题%}{%专题附加%}{%页码%}_{%网站标题%}";\'>[默认]</span>&ensp;
					<span class="pointer" onclick=\'InputAddText("titleTopic","{%网站标题%}")\'>{%网站标题%}</span>&ensp;
					<span class="pointer" onclick=\'InputAddText("titleTopic","{%网站标题附加%}")\'>{%网站标题附加%}</span>&ensp;
					<span class="pointer" onclick=\'InputAddText("titleTopic","{%专题%}")\'>{%专题%}</span>&ensp;
					<span class="pointer" onclick=\'InputAddText("titleTopic","{%专题附加%}")\'>{%专题附加%}</span>&ensp;
					<span class="pointer" onclick=\'InputAddText("titleTopic","{%页码%}")\'>{%页码%}</span>
				</div>
			</td>
		</tr>
		'. AppBbs::SystemBox2($SYS_titleBbsList,$SYS_titleBbsShow,$SYS_titleBbsSearch) .'
		<!-- <tr>
			<td align="right" valign="top" style="padding-top:6px;">文章路径<span style="color:red;">屏蔽</span>：</td>
			<td>
				<input type="hidden" id="htmlUrlDelOld" name="htmlUrlDelOld" value="'. $SYS_htmlUrlDel .'" />
				<label><input type="checkbox" id="htmlUrlDel12" name="htmlUrlDel[]" value="|otcms_dyn-2.x|" '. Is::InstrChecked($SYS_htmlUrlDel,'|otcms_dyn-2.x|') .' onclick=\'CheckHtmlUrlDel(1,"dyn-2.x");\' />网钛-动态 </label>&ensp;&ensp;
				<label><input type="checkbox" id="htmlUrlDel13" name="htmlUrlDel[]" value="|otcms_html-2.x|" '. Is::InstrChecked($SYS_htmlUrlDel,'|otcms_html-2.x|') .' onclick=\'CheckHtmlUrlDel(1,"otcms_html-2.x");\' />网钛-纯静态</label>&ensp;&ensp;
				<div style="color:red;">打钩，即在robots.txt文件里屏蔽搜索蜘蛛收录此路径，建议都不要打钩，以防止收录受影响。</div>
			</td>
		</tr> -->
		</table>


		<table id="tabWeb" style="display:none;" cellpadding="0" cellspacing="0" summary="" class="padd3">
		<tr><td style="width:150px;"></td><td></td></tr>
		</table>


		<table id="tabStaic" style="display:none;" cellpadding="0" cellspacing="0" summary="" class="padd3">
		<tr><td style="width:150px;"></td><td></td></tr>
		<tr>
			<td align="right">首页静态页：</td>
			<td>
				<input type="hidden" id="isHtmlHomeOld" name="isHtmlHomeOld" value="'. $SYS_isHtmlHome .'" />
				<label><input type="radio" id="isHtmlHome1" name="isHtmlHome" value="1" '. Is::Checked($SYS_isHtmlHome,1) .' />启用</label>&ensp;&ensp;
				<label><input type="radio" id="isHtmlHome0" name="isHtmlHome" value="0" '. Is::Checked($SYS_isHtmlHome,0) .' />禁用</label>&ensp;&ensp;
				&ensp;&ensp;'. $skin->TishiBox('当禁用时，系统会自动删除根目录下首页静态页index.html') .'
			</td>
		</tr>
		</table>
		');

		if (! AppBase::Jud()){
			$skin->PaySoftBox('tabBuy','您尚未购买商业版基础包插件，无法使用该功能。');
			$skin->PaySoftBox('tabNews','您尚未购买商业版基础包插件，无法使用该功能。');
			echo('<input type="hidden" id="authState" name="authState" value="false" />');

		}elseif ($sysAdminArr['SA_isLan'] == 1 && $sysAdminArr['SA_sendUrlMode'] == 0){
			$skin->PaySoftBox('tabBuy',$skin->LanPaySoft());
			$skin->PaySoftBox('tabNews',$skin->LanPaySoft());
			echo('<input type="hidden" id="authState" name="authState" value="false" />');

		}else{
			if ($sysAdminArr['SA_sendUrlMode'] == 1){
				$isRewrite = 1;
			}else{
				$retArr = @ReqUrl::UseAuto($sysAdminArr['SA_getUrlMode'], 'GET',GetUrl::CurrDir(1) .'readSoft.html', 'UTF-8');
				if ($retArr['res'] && $retArr['note'] == '[该访问地址存在]'){
					$isRewrite = 1;
				}else{
					$isRewrite = 0;
				}
			}

			$paraArr = array(
				'SYS_isTimeInfo'		=> $SYS_isTimeInfo ,
				'SYS_isBaiduSitemap'	=> $SYS_isBaiduSitemap ,
				'SYS_htmlCacheMin'		=> $SYS_htmlCacheMin ,
				'SYS_htmlCacheTime'		=> $SYS_htmlCacheTime ,
				'SYS_isFloatAd'			=> $SYS_isFloatAd ,
				'SYS_eventStr'			=> $SYS_eventStr ,
				'SYS_proxyIpPoint'		=> $SYS_proxyIpPoint ,
				'SYS_proxyIpList'		=> $SYS_proxyIpList ,

				'SYS_htmlUrlJump'		=> $SYS_htmlUrlJump ,
				'SYS_htmlUrlDir'		=> $SYS_htmlUrlDir ,
				'SYS_htmlInfoTypeDir'	=> $SYS_htmlInfoTypeDir ,
				'SYS_htmlDatetimeDir'	=> $SYS_htmlDatetimeDir ,
				'SYS_diyInfoTypeDir'	=> $SYS_diyInfoTypeDir ,
				'SYS_newsListUrlMode'	=> $SYS_newsListUrlMode ,
				'SYS_newsListFileName'	=> $SYS_newsListFileName ,
				'SYS_newsShowUrlMode'	=> $SYS_newsShowUrlMode ,
				'SYS_newsShowFileName'	=> $SYS_newsShowFileName ,
				'SYS_dynWebUrlMode'		=> $SYS_dynWebUrlMode ,
				'SYS_dynWebFileName'	=> $SYS_dynWebFileName ,
				'isRewrite'				=> $isRewrite
				);

			$getWebHtml = OTauthWeb('system', 'system_V5.00.php', $paraArr);
			if (strpos($getWebHtml,'(OTCMS)') === false){
				$authAlertStr='未知原因（'. $getWebHtml .'）';
				if (strpos($getWebHtml,'<!-- noRemote -->') !== false){
					$authAlertStr='无法访问外网';
				}elseif (strpos($getWebHtml,'<!-- noUse -->') !== false){
					$authAlertStr='授权禁用';
				}else{
				
				}
				$getWebHtml = ''.
					$skin->PaySoftBox('tabBuy','因'. $authAlertStr .'而无法使用',true) .
					$skin->PaySoftBox('tabNews','因'. $authAlertStr .'而无法使用',true) .
					'<input type="hidden" id="authState" name="authState" value="false" />';
			}else{
				echo('
				<script language="javascript" type="text/javascript">
				$id("seoBox").style.display = "";
				$id("buyBox").style.display = "";
				$id("newsBox").style.display = "";
				</script>
				');
			}
			echo($getWebHtml);
		}

		echo('
		<div class="tabMenuSubmit"><input type="submit" class="btnBg" value="保 存" /></div>
	</div>

	</form>
	');
}

?>
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
<script language="javascript" type="text/javascript" src="js/inc/list.js?v='. OT_VERSION .'"></script>
<script language="javascript" type="text/javascript" src="js/siteMap.js?v='. OT_VERSION .'"></script>
');


switch($mudi){
	case 'manage':
		$MB->IsSecMenuRight('alertBack',145,$dataType);
		manage();
		break;

	default:
		die('err');
}

$skin->WebBottom();

$MB->Close();
$DB->Close();





function manage(){
	global $DB,$skin,$mudi,$systemArr;

	$scoreMode			= 0;
	$newScore			= '0.1';
	$homeThumbScore		= '0.1';
	$thumbScore			= '0.1';
	$flashScore			= '0.1';
	$imgScore			= '0.1';
	$marInfoScore		= '0.1';
	$recomScore			= '0.2';
	$topScore			= '0.2';

	$isNew				= 0;
	$isHomeThumb		= 0;
	$isThumb			= 0;
	$isFlash			= 0;
	$isImg				= 0;
	$isMarquee			= 0;
	$isRecom			= 0;
	$isTop				= 0;

	$recordMaxNum		= 0;
	$pageMaxNum			= 5000;
	$hourDiff			= 8;
	$updateFreq			= 'weekly';
	$updateTime			= 0;
	$updateTimeStr		= '';

	AppMapBaidu::SiteMapBox1();

	$currUrlDir			= GetUrl::CurrDir(1);
	$urlSitemapUrl		= $currUrlDir .'sitemap.xml';
	$urlSitemapWapUrl	= $systemArr['SYS_wapUrl'] .'sitemap.xml';
	$urlSitemapUrlEn	= urlencode($urlSitemapUrl);
	$urlBaiduWapUrl		= $currUrlDir .'cache/web/site_baiduWapUrl.txt';
	$urlSoWapPat		= $currUrlDir .'cache/web/site_360WapPat.txt';
	$urlSoWapUrl		= $currUrlDir .'cache/web/site_360WapUrl.txt';
	$urlSogouWapPat		= $currUrlDir .'cache/web/site_sogouWapPat.xml';
	$urlSogouWapUrl		= $currUrlDir .'cache/web/site_sogouWapUrl.txt';
	$urlShenmaWapPat	= $currUrlDir .'cache/web/site_shenmaWapPat.txt';
	$urlShenmaWapUrl	= $currUrlDir .'cache/web/site_shenmaWapUrl.txt';

	$skin->TableTop('share_rev.gif','','在线提交sitemap.xml&ensp;（百度和谷歌通用）');
		echo('
		<div style="padding:5px;">
			<table width="100%" cellpadding="2" cellspacing="0">
			<tr>
				<td>
					电脑版sitemap文件：<a href="'. $urlSitemapUrl .'" target="_blank" style="color:red;">'. $urlSitemapUrl .'<a>
					'. AppWap::SiteMapBox2($urlSitemapWapUrl) .'
				</td>
				<td>
					<input type="button" value="向Google提交" onclick=\'var a=window.open("http://www.google.com/webmasters/tools/ping?sitemap='. $urlSitemapUrlEn .'");\' />&ensp;&ensp;
					<input type="button" value="向百度提交" onclick=\'var a=window.open("http://zhanzhang.baidu.com/sitemap/index");\' />
				</td>
			</tr>
			</table>
		</div>
		');
	$skin->TableBottom();

	echo('
	<br />
	');

	AppWap::SiteMapBox($systemArr,$currUrlDir,array(
			'baiduUrl'	=> $urlBaiduWapUrl,
			'soPat'		=> $urlSoWapPat,
			'soUrl'		=> $urlSoWapUrl,
			'sogouPat'	=> $urlSogouWapPat,
			'sogouUrl'	=> $urlSogouWapUrl,
			'shenmaPat'	=> $urlShenmaWapPat,
			'shenmaUrl'	=> $urlShenmaWapUrl,
		));

	$skin->TableTop('share_rev.gif','','生成sitemap.html');
		echo('
		<div style="padding:5px;">
			<div>
				<input type="button" value="生成电脑版网站地图" onclick=\'DataDeal.location.href="makeHtml_run.php?mudi=sitemapHtml";return false;\' />
				&ensp;&ensp;<a href="../sitemap.html" target="_blank"><u>预览电脑版sitemap.html</u></a>
				'. AppWap::SiteMapBox3($systemArr['SYS_wapUrl']) .'
			</div>
		</div>
		');
	$skin->TableBottom();

	echo('
	<br />
	');

	$skin->TableTop('share_rev.gif','','生成sitemap.xml');
		echo('
		<form id="mapForm" name="mapForm" method="get" action="siteMap_deal.php?mudi=deal" onsubmit="return CheckMapForm()" target="resultBox">
		<input type="hidden" id="mudi" name="mudi" value="deal" />
		<input type="hidden" id="dealMode" name="dealMode" value="" />
		<input type="hidden" id="oldFileNum" name="oldFileNum" value="" />
		<table width="99%" align="center" cellpadding="0" cellspacing="0" summary="" class="padd3">
		<tr>
			<td style="width:15%;"></td>
			<td style="width:85%;"></td>
		</tr>
		<tr>
			<td align="right" style="font-weight:bold;">最后更新时间：</td>
			<td align="left" id="lastUpdatTime"></td>
		</tr>
		<tr>
			<td align="right">权重模式：</td>
			<td align="left">
				<label><input id="scoreMode0" name="scoreMode" type="radio" value="0" '. Is::Checked($scoreMode,0) .' onclick="CheckScoreMode()" />权重随机</label>&ensp;&ensp;&ensp;&ensp;
				<label><input id="scoreMode1" name="scoreMode" type="radio" value="1" '. Is::Checked($scoreMode,1) .' onclick="CheckScoreMode()" />文章属性权重累加</label>&ensp;&ensp;&ensp;&ensp;
			</td>
		</tr>
		<tr id="infoScoreBox">
			<td align="right">文章权重累加：</td>
			<td align="left">
				最新消息<input type="text" id="newScore" name="newScore" style="width:25px;" value="'. $newScore .'" />&ensp;&ensp;
				首页图<input type="text" id="homeThumbScore" name="homeThumbScore" style="width:25px;" value="'. $homeThumbScore .'" />&ensp;&ensp;
				缩略图<input type="text" id="thumbScore" name="thumbScore" style="width:25px;" value="'. $thumbScore .'" />&ensp;&ensp;
				幻灯片<input type="text" id="flashScore" name="flashScore" style="width:25px;" value="'. $flashScore .'" />&ensp;&ensp;
				滚动图片<input type="text" id="imgScore" name="imgScore" style="width:25px;" value="'. $imgScore .'" />&ensp;&ensp;
				滚动信息<input type="text" id="marInfoScore" name="marInfoScore" style="width:25px;" value="'. $marInfoScore .'" />&ensp;&ensp;
				推荐<input type="text" id="recomScore" name="recomScore" style="width:25px;" value="'. $recomScore .'" />&ensp;&ensp;
				置顶<input type="text" id="topScore" name="topScore" style="width:25px;" value="'. $topScore .'" />
				<div class="font2_2 padd5">（权重范围：0.0~1.0，权重累加最大值1.0，以此来决定在sitemap中各个文章的权重程度。）</div>
			</td>
		</tr>
		<tr>
			<td align="right">文章筛选范围：</td>
			<td align="left">
				<label><input id="isNew" name="isNew" type="checkbox" value="1" '. Is::Checked($isNew,1) .' />最新消息</label>&ensp;&ensp;
				<label><input id="isHomeThumb" name="isHomeThumb" type="checkbox" value="1" '. Is::Checked($isHomeThumb,1) .' />首页图</label>&ensp;&ensp;
				<label><input id="isThumb" name="isThumb" type="checkbox" value="1" '. Is::Checked($isThumb,1) .' />缩略图</label>&ensp;&ensp;
				<label><input id="isFlash" name="isFlash" type="checkbox" value="1" '. Is::Checked($isFlash,1) .' />幻灯片</label>&ensp;&ensp;
				<label><input id="isImg" name="isImg" type="checkbox" value="1" '. Is::Checked($isImg,1) .' />滚动图片</label>&ensp;&ensp;
				<label><input id="isMarquee" name="isMarquee" type="checkbox" value="1" '. Is::Checked($isMarquee,1) .' />滚动信息</label>&ensp;&ensp;
				<label><input id="isRecom" name="isRecom" type="checkbox" value="1" '. Is::Checked($isRecom,1) .' />推荐</label>&ensp;&ensp;
				<label><input id="isTop" name="isTop" type="checkbox" value="1" '. Is::Checked($isTop,1) .' />置顶</label>
				<div class="font2_2 padd5">（全没选，即所有文章。）</div>
			</td>
		</tr>
		<tr>
			<td align="right">限制最大条数：</td>
			<td align="left">
				<input type="text" id="recordMaxNum" name="recordMaxNum" value="'. $recordMaxNum .'" style="width:40px;margin-right:2px;" onkeyup="if (this.value!=FiltDecimal(this.value)){this.value=FiltDecimal(this.value)}" />条
				<span class="font2_2 padd5">&ensp;&ensp;&ensp;&ensp;（如为0，表示不限制条数）</span>
				&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;<input type="button" value="检查符合条件的记录数" onclick=\'CheckMapForm("count")\' />
			</td>
		</tr>
		<tr>
			<td align="right">每个文件最大条数：</td>
			<td align="left">
				<input type="text" id="pageMaxNum" name="pageMaxNum" value="'. $pageMaxNum .'" style="width:40px;margin-right:2px;" onkeyup="if (this.value!=FiltDecimal(this.value)){this.value=FiltDecimal(this.value)}" />条
				<span class="font2_2 padd5">&ensp;&ensp;&ensp;&ensp;（范围100~5000，当最大条数大于该数值时，自动分成多个地图文件存放）</span>
			</td>
		</tr>
		<tr>
			<td align="right">时区偏移：</td>
			<td align="left">
				<input type="text" id="hourDiff" name="hourDiff" value="'. $hourDiff .'" style="width:40px;margin-right:2px;" onkeyup="if (this.value!=FiltDecimal(this.value)){this.value=FiltDecimal(this.value)}" />小时
				<span class="font2_2">&ensp;&ensp;&ensp;（北京时区+8:00）</span>
			</td>
		</tr>
		<tr>
			<td align="right">更新频率：</td>
			<td align="left">
				<select id="updateFreq" name="updateFreq">
					<option value="always" '. Is::Selected($updateFreq,'always') .'>经常</option>
					<option value="hourly" '. Is::Selected($updateFreq,'hourly') .'>每时</option>
					<option value="daily" '. Is::Selected($updateFreq,'daily') .'>每天</option>
					<option value="weekly" '. Is::Selected($updateFreq,'weekly') .'>每周</option>
					<option value="monthly" '. Is::Selected($updateFreq,'monthly') .'>每月</option>
					<option value="yearly" '. Is::Selected($updateFreq,'yearly') .'>每年</option>
					<option value="never" '. Is::Selected($updateFreq,'never') .'>从不</option>
				</select>
			</td>
		</tr>
		<tr>
			<td align="right">更新时间：</td>
			<td align="left">
				<label><input id="updateTime0" name="updateTime" type="radio" value="0" '. Is::Checked($updateTime,0) .' />文章发布时间</label>&ensp;&ensp;&ensp;&ensp;
				<label><input id="updateTime1" name="updateTime" type="radio" value="1" '. Is::Checked($updateTime,1) .' />服务器当前时间</label>&ensp;&ensp;&ensp;&ensp;
				<label><input id="updateTime2" name="updateTime" type="radio" value="2" '. Is::Checked($updateTime,2) .' />指定时间</label><input type="text" id="updateTimeStr" name="updateTimeStr" size="22" style="width:170px;" value="'. $updateTimeStr .'" onclick=\'$id("updateTime2").checked=true;\' onfocus=\'WdatePicker({dateFmt:"yyyy-MM-dd HH:mm:ss"})\' class="Wdate" />
			</td>
		</tr>
		</table>
		<center style="margin-top:15px;"><iframe id="resultBox" name="resultBox" frameborder="0" allowTransparency="true" scrolling="no" style="width:710px;height:30px;" src="about:blank"></iframe></center>
		<center style="margin:20px 0 10px 0;">
			<input type="button" value="确定生成sitemap" onclick=\'CheckMapForm("submit")\' />&ensp;&ensp;&ensp;&ensp;
			<input type="button" value="恢复默认设置" onclick="ResetMapForm();" />
		</center>
		</form>
		<script language="javascript" type="text/javascript" src="../cache/js/siteMap.js"></script>
		');
	$skin->TableBottom();

	echo('<div style="padding:6px;color:red;">提醒：sitemap.xml 和 各种移动适配文件的数据更新，请点击底部【确定生成sitemap】按钮进行更新</div>');
}

?>
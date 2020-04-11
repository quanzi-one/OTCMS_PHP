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
<script language="javascript" type="text/javascript" src="js/tplSys.js?v='. OT_VERSION .'"></script>
');


switch($mudi){
	case 'setInfo':
		$MB->IsSecMenuRight('alertBack',229,$dataType);
		SetInfo();
		break;

	default:
		die('err');
}

$skin->WebBottom();

$MB->Close();
$DB->Close();





// 设置
function SetInfo(){
	global $DB,$skin,$mudi,$dataType,$dataTypeCN,$sysAdminArr,$systemArr;

	$revexe=$DB->query('select * from '. OT_dbPref .'tplSys');
		if ($row = $revexe->fetch()){
			$TS_skinName		= $row['TS_skinName'];
			$TS_skinColor		= $row['TS_skinColor'];
			$TS_skinPopup		= $row['TS_skinPopup'];

			$TS_topLogoMode		= $row['TS_topLogoMode'];
			$TS_logo			= $row['TS_logo'];
			$TS_fullLogo		= $row['TS_fullLogo'];
			$TS_logoExt			= $row['TS_logoExt'];
			$TS_logoW			= $row['TS_logoW'];
				if ($TS_logoW<=0){ $TS_logoW=256; }
			$TS_logoH			= $row['TS_logoH'];
				if ($TS_logoH<=0){ $TS_logoH=60; }
			$TS_isRss			= $row['TS_isRss'];
			$TS_isTopAd			= $row['TS_isTopAd'];
			$TS_topAdCode		= $row['TS_topAdCode'];
			$TS_searchArea		= $row['TS_searchArea'];

			$TS_isLogoAdd		= $row['TS_isLogoAdd'];
			$TS_logoAreaStr		= $row['TS_logoAreaStr'];
			$TS_logoListMode	= $row['TS_logoListMode'];
			$TS_TCP				= $row['TS_TCP'];
			$TS_beianName		= $row['TS_beianName'];
			$TS_beianUrl		= $row['TS_beianUrl'];
			$TS_copyright		= $row['TS_copyright'];
			$TS_isStati			= $row['TS_isStati'];
			$TS_statiCode		= $row['TS_statiCode'];

			$TS_homeAboutID			= $row['TS_homeAboutID'];
			$TS_homeAboutMoreID		= $row['TS_homeAboutMoreID'];
			$TS_homeContactID		= $row['TS_homeContactID'];
			$TS_homeContactMoreID	= $row['TS_homeContactMoreID'];
			$TS_homeNewsID			= $row['TS_homeNewsID'];
			$TS_homeNewsNum			= $row['TS_homeNewsNum'];
			$TS_homeProID			= $row['TS_homeProID'];
			$TS_homeProNum			= $row['TS_homeProNum'];
			$TS_homeInfoTypeID		= $row['TS_homeInfoTypeID'];

			$TS_navWidthMode	= $row['TS_navWidthMode'];
			$TS_jieriImg		= $row['TS_jieriImg'];
			$TS_jieriHeight		= $row['TS_jieriHeight'];
			$TS_navMode			= $row['TS_navMode'];
			$TS_navCode			= $row['TS_navCode'];
			$TS_navNum			= $row['TS_navNum'];
			$TS_navPadd			= $row['TS_navPadd'];
			$TS_navSubWidth		= $row['TS_navSubWidth'];

			$TS_homeItemMode		= $row['TS_homeItemMode'];
			$TS_redTimeDay			= $row['TS_redTimeDay'];
			$TS_rankUserMode		= $row['TS_rankUserMode'];
			$TS_marInfoNum			= $row['TS_marInfoNum'];
			$TS_isHomeFlash			= $row['TS_isHomeFlash'];
			$TS_homeFlashMode		= $row['TS_homeFlashMode'];
			$TS_isHomeFlashTheme	= $row['TS_isHomeFlashTheme'];
			$TS_homeFlashNum		= $row['TS_homeFlashNum'];
			$TS_isHomeAnnoun		= $row['TS_isHomeAnnoun'];
			$TS_homeAnnounName		= $row['TS_homeAnnounName'];
			$TS_homeAnnounNum		= $row['TS_homeAnnounNum'];
			$TS_homeAnnounListNum	= $row['TS_homeAnnounListNum'];
			$TS_isHomeNew			= $row['TS_isHomeNew'];
			$TS_homeNewIsType		= $row['TS_homeNewIsType'];
			$TS_homeNewIsDate		= $row['TS_homeNewIsDate'];
			$TS_homeNewTopMode		= $row['TS_homeNewTopMode'];
			$TS_homeNewTopNum		= $row['TS_homeNewTopNum'];
			$TS_homeNewNum			= $row['TS_homeNewNum'];
			$TS_homeNewListNum		= $row['TS_homeNewListNum'];
			$TS_homeNewMoreNum		= $row['TS_homeNewMoreNum'];
			$TS_homeNewBoxH			= $row['TS_homeNewBoxH'];
			$TS_isHomeRecom			= $row['TS_isHomeRecom'];
			$TS_homeRecomImgNum		= $row['TS_homeRecomImgNum'];
			$TS_homeRecomNum		= $row['TS_homeRecomNum'];
			$TS_homeRecomBoxH		= $row['TS_homeRecomBoxH'];
			$TS_isHomeHot			= $row['TS_isHomeHot'];
			$TS_homeHotName			= $row['TS_homeHotName'];
			$TS_homeHotSort			= $row['TS_homeHotSort'];
			$TS_homeHotIsDate		= $row['TS_homeHotIsDate'];
			$TS_homeHotImgNum		= $row['TS_homeHotImgNum'];
			$TS_homeHotNum			= $row['TS_homeHotNum'];
			$TS_isHomeMarImg		= $row['TS_isHomeMarImg'];
			$TS_homeMarImgMode		= $row['TS_homeMarImgMode'];
			$TS_homeMarImgW			= $row['TS_homeMarImgW'];
			$TS_homeMarImgH			= $row['TS_homeMarImgH'];
			$TS_homeMarImgNum		= $row['TS_homeMarImgNum'];
			$TS_isHomeMessage		= $row['TS_isHomeMessage'];
			$TS_homeMessageName		= $row['TS_homeMessageName'];
			$TS_homeMessageNum		= $row['TS_homeMessageNum'];
			$TS_homeMessageLen		= $row['TS_homeMessageLen'];
			$TS_homeMessageHmode	= $row['TS_homeMessageHmode'];
			$TS_homeMessageH		= $row['TS_homeMessageH'];
			$TS_isHomeReply			= $row['TS_isHomeReply'];
			$TS_homeReplyName		= $row['TS_homeReplyName'];
			$TS_homeReplyNum		= $row['TS_homeReplyNum'];
			$TS_homeReplyLen		= $row['TS_homeReplyLen'];
			$TS_homeReplyHmode		= $row['TS_homeReplyHmode'];
			$TS_homeReplyH			= $row['TS_homeReplyH'];
			$TS_isHomeBbs			= $row['TS_isHomeBbs'];
			$TS_homeBbsName			= $row['TS_homeBbsName'];
			$TS_homeBbsNum			= $row['TS_homeBbsNum'];
			$TS_homeBbsLen			= $row['TS_homeBbsLen'];
			$TS_homeBbsHmode		= $row['TS_homeBbsHmode'];
			$TS_homeBbsH			= $row['TS_homeBbsH'];

			$TS_isHomeNewUsers		= $row['TS_isHomeNewUsers'];
			$TS_homeNewUsersName	= $row['TS_homeNewUsersName'];
			$TS_homeNewUsersNum		= $row['TS_homeNewUsersNum'];
			$TS_isHomeRankUsers		= $row['TS_isHomeRankUsers'];
			$TS_homeRankUsersName	= $row['TS_homeRankUsersName'];
			$TS_homeRankUsersType	= $row['TS_homeRankUsersType'];
			$TS_homeRankUsersNum	= $row['TS_homeRankUsersNum'];
			$TS_isHomeQiandao		= $row['TS_isHomeQiandao'];
			$TS_homeQiandaoName		= $row['TS_homeQiandaoName'];
			$TS_isQiandaoRank		= $row['TS_isQiandaoRank'];
			$TS_qiandaoRankName		= $row['TS_qiandaoRankName'];
			$TS_qiandaoRankType		= $row['TS_qiandaoRankType'];
			$TS_qiandaoRankNum		= $row['TS_qiandaoRankNum'];

			$TS_subWebLR			= $row['TS_subWebLR'];
			$TS_messageMode			= $row['TS_messageMode'];

			$TS_isImgBanner			= $row['TS_isImgBanner'];
			$TS_imgBannerH			= $row['TS_imgBannerH'];

			$TS_replyMode			= $row['TS_replyMode'];
			$TS_userListNum			= $row['TS_userListNum'];
			$TS_userListMode		= $row['TS_userListMode'];
			$TS_searchListMode		= $row['TS_searchListMode'];
			$TS_markListMode		= $row['TS_markListMode'];
			$TS_announListMode		= $row['TS_announListMode'];
			$TS_newListMode			= $row['TS_newListMode'];

			$TS_subWeb404			= $row['TS_subWeb404'];
			$TS_searchAllowStr		= $row['TS_searchAllowStr'];
			$TS_searchListNum		= $row['TS_searchListNum'];
			$TS_searchBadStr		= $row['TS_searchBadStr'];
			$TS_isMark				= $row['TS_isMark'];
			$TS_markListNum			= $row['TS_markListNum'];
			$TS_markBadStr			= $row['TS_markBadStr'];

			$TS_isMessage			= $row['TS_isMessage'];
			$TS_messageName			= $row['TS_messageName'];
			$TS_messageWebKey		= $row['TS_messageWebKey'];
			$TS_messageWebDesc		= $row['TS_messageWebDesc'];
			$TS_messageCloseNote	= $row['TS_messageCloseNote'];
			$TS_messageNum			= $row['TS_messageNum'];
			$TS_messageSecond		= $row['TS_messageSecond'];
			$TS_messageAudit		= $row['TS_messageAudit'];
			$TS_messageOnly			= $row['TS_messageOnly'];
			$TS_messageMaxLen		= $row['TS_messageMaxLen'];
			$TS_messageAdminName	= $row['TS_messageAdminName'];
			$TS_messageBadWord		= $row['TS_messageBadWord'];
			$TS_messageRndMd5		= $row['TS_messageRndMd5'];
			$TS_messageVoteNum		= $row['TS_messageVoteNum'];
			$TS_messageHotNum		= $row['TS_messageHotNum'];
			$TS_messageEvent		= $row['TS_messageEvent'];
			$TS_event				= $row['TS_event'];
			$TS_logoNote			= $row['TS_logoNote'];
		}
	unset($revexe);

	$beforeURL=GetUrl::CurrDir(1);

	$searchArea1		= '';
	$searchArea1_title	= '站内搜索(标题)';
	$searchArea1_rank	= '';
	$searchArea2		= '';
	$searchArea2_title	= '站内搜索(正文)';
	$searchArea2_rank	= '';
	$searchArea3		= '';
	$searchArea3_title	= '站内搜索(来源)';
	$searchArea3_rank	= '';
	$searchArea4		= '';
	$searchArea4_title	= '站内搜索(作者)';
	$searchArea4_rank	= '';
	$searchArea5		= '';
	$searchArea5_title	= '百度站内搜索';
	$searchArea5_rank	= '';
	$searchArea5_addi1	= '';
	$searchArea5_addi2	= '';
	$searchArea6		= '';
	$searchArea6_title	= '360站内搜索';
	$searchArea6_rank	= '';
	$searchArea6_addi1	= '';
	$searchArea6_addi2	= '';
	$searchAreaArr = explode('<arr>',$TS_searchArea);
	if (count($searchAreaArr) >= 5){
		$searchOneArr		= explode('|',$searchAreaArr[0]);
		$searchArea1		= $searchOneArr[0];
		$searchArea1_rank	= $searchOneArr[1];
		$searchArea1_title	= $searchOneArr[3];
		$searchOneArr		= explode('|',$searchAreaArr[1]);
		$searchArea2		= $searchOneArr[0];
		$searchArea2_rank	= $searchOneArr[1];
		$searchArea2_title	= $searchOneArr[3];
		$searchOneArr		= explode('|',$searchAreaArr[2]);
		$searchArea3		= $searchOneArr[0];
		$searchArea3_rank	= $searchOneArr[1];
		$searchArea3_title	= $searchOneArr[3];
		$searchOneArr		= explode('|',$searchAreaArr[3]);
		$searchArea4		= $searchOneArr[0];
		$searchArea4_rank	= $searchOneArr[1];
		$searchArea4_title	= $searchOneArr[3];
		$searchOneArr		= explode('|',$searchAreaArr[4]);
		$searchArea5		= $searchOneArr[0];
		$searchArea5_rank	= $searchOneArr[1];
		$searchArea5_title	= $searchOneArr[3];
		$searchArea5_addi1	= $searchOneArr[4];
		$searchArea5_addi2	= $searchOneArr[5];
	}
	if (count($searchAreaArr) >= 6){
		$searchOneArr		= explode('|',$searchAreaArr[5]);
		$searchArea6		= $searchOneArr[0];
		$searchArea6_rank	= $searchOneArr[1];
		$searchArea6_title	= $searchOneArr[3];
		$searchArea6_addi1	= $searchOneArr[4];
	}

	$eventStr = '';
	$itemArr = array(
		'homeItemModeIsImg'	=> true,
		'homeItemMode_2'	=> '左两栏右热门文章',
		'homeItemMode_3'	=> '全三栏',
		);

	switch ($systemArr['SYS_templateDir']){
		case 'def_black/':		// 使用 清爽黑白调模板
			$itemArr['homeItemModeIsImg']	= false;
			$itemArr['homeItemMode_2']		= '选项卡切换';
			break;
	
		case 'def_white/':		// 使用 清爽白色风
			$eventStr = '
				<label><input type="checkbox" name="event[]" value="|noTop|" '. Is::InstrChecked($TS_event,'|noTop|') .'/>不显示自带回顶部火箭图标</label>&ensp;&ensp;
				';
			break;
	
		case 'def_media/':		// 使用 新媒体模板
			$itemArr['homeItemModeIsImg']	= false;
			$itemArr['homeItemMode_2']		= '显示';
			$itemArr['homeItemMode_3']		= '关闭';
			$eventStr = '
				<label><input type="checkbox" name="event[]" value="|noTop|" '. Is::InstrChecked($TS_event,'|noTop|') .'/>不显示自带回顶部箭头图标</label>&ensp;&ensp;
				';
			break;
	
	}
	
	echo('
	<form id="dealForm" name="dealForm" method="post" action="tplSys_deal.php?mudi=deal" onsubmit="return CheckForm()">
	<input type="hidden" id="dataType" name="dataType" value="'. $dataType .'" />
	<input type="hidden" id="dataTypeCN" name="dataTypeCN" value="'. $dataTypeCN .'" />
	<script language="javascript" type="text/javascript">document.write(\'<input type="hidden" name="backURL" value="\'+ document.location.href +\'" />\')</script>

	
	<div class="tabMenu">
	<ul>
	   <li rel="tabBase" class="selected">基本信息</li>
	   <li rel="tabTop">页头</li>
	   <li rel="tabBottm">页尾</li>
	   <li rel="tabHome">首页</li>
	   <li rel="tabSub">列表页/内容页</li>
	   <li rel="tabQiye">企业站专属</li>
	   <li id="buyBox" rel="tabBuy" style="display:none;">商业版专属</li>
	</ul>
	</div>

	<div class="tabMenuArea">
		<table id="tabBase" cellpadding="0" cellspacing="0" summary="" class="padd3">
		<tr><td style="width:150px;"></td><td></td></tr>
		<tr><td colspan="2" style="color:red;padding-bottom:10px;">提醒：所有参数设置项仅对默认模板有效（除企业站专属菜单），其他模板由于结构不同，部分选项无效或位置不同。</td></tr>
		<tr>
			<td align="right">模板皮肤：</td>
			<td>
				<select id="skinName" name="skinName">
					<option value="default" '. Is::Selected($TS_skinName,'default') .'>红色(默认)</option>
					<option value="def_blue" '. Is::Selected($TS_skinName,'def_blue') .'>蓝色</option>
					<option value="def_pink" '. Is::Selected($TS_skinName,'def_pink') .'>粉色</option>
					<option value="def_purple" '. Is::Selected($TS_skinName,'def_purple') .'>紫色</option>
					<option value="def_green" '. Is::Selected($TS_skinName,'def_green') .'>绿色</option>
					<option value="def_yellow" '. Is::Selected($TS_skinName,'def_yellow') .'>黄色</option>
					<option value="def_black" '. Is::Selected($TS_skinName,'def_black') .'>黑色</option>
					<option value="user_inkWash" '. Is::Selected($TS_skinName,'user_inkWash') .'>水墨风</option>
				</select>
			</td>
		</tr>
		<tr>
			<td align="right">蒙层皮肤：</td>
			<td>
				<select id="skinPopup" name="skinPopup">
					<option value="red" '. Is::Selected($TS_skinPopup,'red') .'>红色</option>
					<option value="blue" '. Is::Selected($TS_skinPopup,'blue') .'>蓝色</option>
					<option value="pink" '. Is::Selected($TS_skinPopup,'pink') .'>粉色</option>
					<option value="purple" '. Is::Selected($TS_skinPopup,'purple') .'>紫色</option>
					<option value="green" '. Is::Selected($TS_skinPopup,'green') .'>绿色</option>
					<option value="yellow" '. Is::Selected($TS_skinPopup,'yellow') .'>黄色</option>
					<option value="black" '. Is::Selected($TS_skinPopup,'black') .'>黑色</option>
				</select>
			</td>
		</tr>
		<tr>
			<td align="right" class="font1_2">几天内日期加红：</td>
			<td><input type="text" id="redTimeDay" name="redTimeDay" size="50" style="width:30px;" value="'. $TS_redTimeDay .'" /> 天</td>
		</tr>
		<tr>
			<td align="right">排行版显示：</td>
			<td>
				<label><input type="radio" name="rankUserMode" value="0" '. Is::Checked($TS_rankUserMode,0) .' />用户名</label>&ensp;&ensp;
				<label><input type="radio" name="rankUserMode" value="1" '. Is::Checked($TS_rankUserMode,1) .' />用户名部分带*</label>&ensp;&ensp;
				<label><input type="radio" name="rankUserMode" value="2" '. Is::Checked($TS_rankUserMode,2) .' />昵称（请确保所有用户都有昵称）</label>&ensp;&ensp;
			</td>
		</tr>
		<tr>
			<td align="right">其他附加项：</td>
			<td>
				'. $eventStr .'
			</td>
		</tr>
		</table>

		<table id="tabTop" style="display:none;" cellpadding="0" cellspacing="0" summary="" class="padd3">
		<tr><td style="width:150px;"></td><td></td></tr>
		<tr>
			<td align="right">页头logo模式：</td>
			<td>
				<label><input type="radio" id="topLogoMode1" name="topLogoMode" value="1" '. Is::Checked($TS_topLogoMode,1) .' onclick=\'$id("logoW").value="960";CheckTopLogoMode();\' />全栏banner</label>&ensp;&ensp;
				<label><input type="radio" id="topLogoMode2" name="topLogoMode" value="2" '. Is::Checked($TS_topLogoMode,2) .' onclick=\'$id("logoW").value="256";CheckTopLogoMode();\' />logo+1广告位(编号21)</label>&ensp;&ensp;
				<label><input type="radio" id="topLogoMode4" name="topLogoMode" value="4" '. Is::Checked($TS_topLogoMode,4) .' onclick="CheckTopLogoMode();" />全栏广告位(编号23)</label>
			</td>
		</tr>
		<tr id="logoExtBox" style="display:none;">
			<td align="right" class="font1_2d">flash型logo：</td>
			<td>
				<label for="logoExt"><input type="checkbox" id="logoExt" name="logoExt" value="1" '. Is::Checked($TS_logoExt,1) .' onclick="CheckFlashLogo()" />开启</label>&ensp;&ensp;&ensp;&ensp;
				<span id="flashLogoBox" style="display:none;">
					宽：<input type="text" id="logoW" name="logoW" size="50" style="width:50px;" value="'. $TS_logoW .'" />

					&ensp;&ensp;&ensp;&ensp;
					高：<input type="text" id="logoH" name="logoH" size="50" style="width:50px;" value="'. $TS_logoH .'" />
				</span>
			</td>
		</tr>
		<tr id="logoBox" style="display:none;">
			<td align="right" class="font1_2d"><span id="logoFontSpan1">图片</span>型logo(≤宽308px)：</td>
			<td>
				<input type="text" id="logo" name="logo" size="50" style="width:260px;" value="'. $TS_logo .'" />
				<input type="button" id="logoFontBtn1" onclick=\'OT_OpenUpImg("input","logo","images","&isWatermark=false")\' value="上传图片" />
			</td>
		</tr>
		<tr id="fullLogoBox" style="display:none;">
			<td align="right" class="font1_2d"><span id="logoFontSpan2">图片</span>型logo(宽1036px)：</td>
			<td>
				<input type="text" id="fullLogo" name="fullLogo" size="50" style="width:260px;" value="'. $TS_fullLogo .'" />
				<input type="button" id="logoFontBtn2" onclick=\'OT_OpenUpImg("input","fullLogo","images","&isWatermark=false")\' value="上传图片" />
			</td>
		</tr>
		'. AppRss::TplSys($TS_isRss) .'
		<tr>
			<td align="right">导航条类型：</td>
			<td>
				<label><input type="radio" id="navWidthMode0" name="navWidthMode" value="0" '. Is::Checked($TS_navWidthMode,0) .' onclick="CheckNavWidthMode()" />宽屏</label>&ensp;&ensp;
				<label><input type="radio" id="navWidthMode1" name="navWidthMode" value="1" '. Is::Checked($TS_navWidthMode,1) .' onclick="CheckNavWidthMode()" />窄屏</label>&ensp;&ensp;
			</td>
		</tr>
		<tbody id="jieriBgBox" style="display:none;">
		<tr>
			<td align="right">头背景：</td>
			<td>
				<select id="jieriImg" name="jieriImg">
					<option value="">无</option>
					<option value="yuandan.jpg" '. Is::Selected($TS_jieriImg,'yuandan.jpg') .'>元旦 （高度建议：177px）</option>
					<option value="chunjie.jpg" '. Is::Selected($TS_jieriImg,'chunjie.jpg') .'>春节 （高度建议：177px）</option>
					<option value="christmas.jpg" '. Is::Selected($TS_jieriImg,'christmas.jpg') .'>圣诞节 （高度建议：177px）</option>
					<option value="christmas2.jpg" '. Is::Selected($TS_jieriImg,'christmas2.jpg') .'>圣诞节2 （高度建议：177px）</option>
				</select>
				&ensp;&ensp;'. $skin->TishiBox('仅限默认模板，头背景文件存放在 inc_img/jieriBg/ 目录下') .'
			</td>
		</tr>
		<tr>
			<td align="right" class="font1_2d">头背景高度：</td>
			<td>
				<input type="text" id="jieriHeight" name="jieriHeight" size="50" style="width:50px;" value="'. $TS_jieriHeight .'" /> px
			</td>
		</tr>
		</tbody>
		<tr>
			<td align="right">导航模式：</td>
			<td>
				<select id="navMode" name="navMode" onchange="CheckNavMode();">
					<option value="11" '. Is::Selected($TS_navMode,11) .'>一级导航(不显示子菜单)</option>
					<option value="21" '. Is::Selected($TS_navMode,21) .'>二级导航[下拉二级菜单](默认)</option>
					<option value="26" '. Is::Selected($TS_navMode,26) .'>二级导航[无动画下拉二级菜单]</option>
					'. AppBase::TplSysOptionBox1($TS_navMode) .'
				</select>
			</td>
		</tr>
		<tr id="navMode0" style="display:none;">
			<td align="right" valign="top" style="padding-top:6px;">自定义导航代码：</td>
			<td>
				<textarea id="navCode" name="navCode" style="width:510px;height:120px;" onclick=\'LoadEditor("navCode",510,120,"|miniMenu|source|");\' title="点击开启编辑器模式">'. Str::MoreReplace($TS_navCode,'textarea') .'</textarea>
			</td>
		</tr>
		<tr id="navMode1">
			<td align="right">导航菜单数量：</td>
			<td>
				<select id="navNum" name="navNum" onchange="CheckNavNum();">
					<option value="3" '. Is::Selected($TS_navNum,3) .'>3</option>
					<option value="4" '. Is::Selected($TS_navNum,4) .'>4</option>
					<option value="5" '. Is::Selected($TS_navNum,5) .'>5</option>
					<option value="6" '. Is::Selected($TS_navNum,6) .'>6</option>
					<option value="7" '. Is::Selected($TS_navNum,7) .'>7</option>
					<option value="8" '. Is::Selected($TS_navNum,8) .'>8</option>
					<option value="9" '. Is::Selected($TS_navNum,9) .'>9</option>
					<option value="10" '. Is::Selected($TS_navNum,10) .'>10</option>
					<option value="11" '. Is::Selected($TS_navNum,11) .'>11</option>
					<option value="12" '. Is::Selected($TS_navNum,12) .'>12</option>
					<option value="13" '. Is::Selected($TS_navNum,13) .'>13</option>
					<option value="14" '. Is::Selected($TS_navNum,14) .'>14</option>
					<option value="0" '. Is::Selected($TS_navNum,0) .'>自适应宽</option>
				</select>
				<span id="navPaddBox" style="display:none;">&ensp;&ensp;主导航宽间距：<input type="text" id="navPadd" name="navPadd" size="50" style="width:30px;" value="'. $TS_navPadd .'" /> px&ensp;&ensp;下拉二级菜单宽度：<input type="text" id="navSubWidth" name="navSubWidth" size="50" style="width:30px;" value="'. $TS_navSubWidth .'" /> px</span>
			</td>
		</tr>
		</table>


		<table id="tabBottm" style="display:none;" cellpadding="0" cellspacing="0" summary="" class="padd3">
		<tr><td style="width:150px;"></td><td></td></tr>
		<tr>
			<td align="right">TCP/IP备案号：</td>
			<td><input type="text" id="TCP" name="TCP" size="50" style="width:300px;" value="'. $TS_TCP .'" />&ensp;<span class="font2_2">如：闽ICP备12010380号</span></td>
		</tr>
		<tr>
			<td align="right">公安备案号：</td>
			<td><input type="text" id="beianName" name="beianName" size="50" style="width:300px;" value="'. $TS_beianName .'" />&ensp;<span class="font2_2">如：闽公网安备35010302000189号</span></td>
		</tr>
		<tr>
			<td align="right" class="font1_2d">公安备案号数字：</td>
			<td><input type="text" id="beianUrl" name="beianUrl" size="50" style="width:300px;" value="'. $TS_beianUrl .'" />&ensp;<span class="font2_2">如：35010302000189</span></td>
		</tr>
		<tr>
			<td align="right" valign="top">网站版权：</td>
			<td>
				<textarea id="copyright" name="copyright" cols="40" rows="4" style="width:510px;height:120px;" class="text" onclick=\'LoadEditor("copyright",510,120,"|miniMenu|");\' title="点击开启编辑器模式">'. Str::MoreReplace($TS_copyright,'textarea') .'</textarea>
				<input type="hidden" id="upImgStr" name="upImgStr" value="" />
				<div><input type="button" onclick=\'OT_OpenUpImg("editor","copyright","","")\' value="上传图片载入编辑器" /></div>
			</td>
		</tr>
		<tr>
			<td align="right">统计代码：</td>
			<td>
				<label><input type="radio" id="isStati1" name="isStati" value="1" '. Is::Checked($TS_isStati,1) .' onclick="CheckStati();" />启用</label>&ensp;&ensp;
				<label><input type="radio" id="isStati0" name="isStati" value="0" '. Is::Checked($TS_isStati,0) .' onclick="CheckStati();" />禁用</label>&ensp;&ensp;
			</td>
		</tr>
		<tr id="statiBox" style="display:none;">
			<td align="right" valign="top" style="padding-top:6px;" class="font1_2d">统计代码内容：</td>
			<td><textarea id="statiCode" name="statiCode" style="width:500px; height:60px;">'. $TS_statiCode .'</textarea></td>
		</tr>
		</table>


		<table id="tabHome" style="display:none;" cellpadding="0" cellspacing="0" summary="" class="padd3">
		<tr><td style="width:150px;"></td><td></td></tr>
		<tr>
			<td align="right">首页栏目布局：</td>
			<td class="itemMode">
				<label><input type="radio" name="homeItemMode" value="2" '. Is::Checked($TS_homeItemMode,2) .'/>'. $itemArr['homeItemMode_2'] .'<span>'. ($itemArr['homeItemModeIsImg'] ? '<img src="temp/itemMode2.gif" style="display:none;" />' : '') .'</span></label>&ensp;&ensp;
				<label><input type="radio" name="homeItemMode" value="3" '. Is::Checked($TS_homeItemMode,3) .'/>'. $itemArr['homeItemMode_3'] .'<span>'. ($itemArr['homeItemModeIsImg'] ? '<img src="temp/itemMode3.gif" style="display:none;" />' : '') .'</span></label>&ensp;&ensp;
			</td>
		</tr>
		<tr>
			<td align="right">首页滚动信息显示数量：</td>
			<td align="left"><input type="text" id="marInfoNum" name="marInfoNum" size="50" style="width:30px;" value="'. $TS_marInfoNum .'" /></td>
		</tr>
		<tr>
			<td align="right">首页幻灯片款式：</td>
			<td class="flashTrun">
				<label><input type="radio" id="homeFlashMode5" name="homeFlashMode" value="5" '. Is::Checked($TS_homeFlashMode,5) .' onclick="CheckHomeFlashMode();" />开启<span><img src="temp/flashTrun5.gif" style="display:none;" /></span></label>&ensp;&ensp;
				<label><input type="radio" id="homeFlashMode0" name="homeFlashMode" value="0" '. Is::Checked($TS_homeFlashMode,0) .' onclick="CheckHomeFlashMode();" />关闭</label>&ensp;&ensp;
			</td>
		</tr>
		<tbody id="homeFlashBox" style="display:none;">
		<tr id="flashThemeBox">
			<td align="right" class="font1_2d">首页幻灯片标题：</td>
			<td class="flashTrun">
				<label><input type="radio" name="isHomeFlashTheme" value="1" '. Is::Checked($TS_isHomeFlashTheme,1) .'/>显示</label>&ensp;&ensp;
				<label><input type="radio" name="isHomeFlashTheme" value="0" '. Is::Checked($TS_isHomeFlashTheme,0) .'/>隐藏</label>&ensp;&ensp;
				&ensp;&ensp;'. $skin->TishiBox('仅对款式1、款式2、款式3有效') .'
			</td>
		</tr>
		<tr>
			<td align="right" class="font1_2d">首页幻灯片最大显示数：</td>
			<td align="left">
				<input type="text" id="homeFlashNum" name="homeFlashNum" size="50" style="width:30px;" value="'. $TS_homeFlashNum .'" />
				&ensp;&ensp;'. $skin->TishiBox('最大显示数为15；0表示最大显示数') .'
			</td>
		</tr>
		</tbody>
		<tr>
			<td align="right">首页网站公告：</td>
			<td>
				<label><input type="radio" id="isHomeAnnoun1" name="isHomeAnnoun" value="1" '. Is::Checked($TS_isHomeAnnoun,1) .' onclick="CheckHomeAnnoun();" />显示默认位置</label>&ensp;&ensp;
				<label><input type="radio" id="isHomeAnnoun2" name="isHomeAnnoun" value="2" '. Is::Checked($TS_isHomeAnnoun,2) .' onclick="CheckHomeAnnoun();" />显示右侧</label>&ensp;&ensp;
				<label><input type="radio" id="isHomeAnnoun0" name="isHomeAnnoun" value="0" '. Is::Checked($TS_isHomeAnnoun,0) .' onclick="CheckHomeAnnoun();" />隐藏</label>&ensp;&ensp;
			</td>
		</tr>
		<tbody id="homeAnnounBox" style="display:none;">
		<tr>
			<td align="right" class="font1_2d">首页网站公告名称：</td>
			<td>
				<input type="text" id="homeAnnounName" name="homeAnnounName" size="50" style="width:100px;" value="'. $TS_homeAnnounName .'" />
				&ensp;&ensp;<span class="font2_2">（通用公告名称设置在【常规设置】-【网站参数设置】[基本信息]）</span>
			</td>
		</tr>
		<tr>
			<td align="right" class="font1_2d">首页网站公告显示数量：</td>
			<td align="left"><input type="text" id="homeAnnounNum" name="homeAnnounNum" size="50" style="width:30px;" value="'. $TS_homeAnnounNum .'" /></td>
		</tr>
		</tbody>

		<tr>
			<td align="right">首页最新消息：</td>
			<td>
				<label><input type="radio" id="isHomeNew1" name="isHomeNew" value="1" '. Is::Checked($TS_isHomeNew,1) .' onclick="CheckHomeNew();" />显示</label>&ensp;&ensp;
				<label><input type="radio" id="isHomeNew0" name="isHomeNew" value="0" '. Is::Checked($TS_isHomeNew,0) .' onclick="CheckHomeNew();" />隐藏</label>&ensp;&ensp;
			</td>
		</tr>
		<tbody id="homeNewBox" style="display:none;">
		<tr>
			<td align="right" class="font1_2d">首页最新消息栏目分类：</td>
			<td>
				<label><input type="radio" name="homeNewIsType" value="1" '. Is::Checked($TS_homeNewIsType,1) .'/>显示</label>&ensp;&ensp;
				<label><input type="radio" name="homeNewIsType" value="0" '. Is::Checked($TS_homeNewIsType,0) .'/>隐藏</label>&ensp;&ensp;
			</td>
		</tr>
		<tr>
			<td align="right" class="font1_2d">首页最新消息时间：</td>
			<td>
				<label><input type="radio" name="homeNewIsDate" value="1" '. Is::Checked($TS_homeNewIsDate,1) .'/>显示</label>&ensp;&ensp;
				<label><input type="radio" name="homeNewIsDate" value="0" '. Is::Checked($TS_homeNewIsDate,0) .'/>隐藏</label>&ensp;&ensp;
			</td>
		</tr>
		<tr style="display:none;">
			<td align="right" class="font1_2d">首页最新消息置顶：</td>
			<td>
				<label><input type="radio" name="homeNewTopMode" value="1" '. Is::Checked($TS_homeNewTopMode,1) .'/>开启</label>&ensp;&ensp;
				<!-- <label><input type="radio" name="homeNewTopMode" value="2" '. Is::Checked($TS_homeNewTopMode,2) .'/>缩略图+标题+摘要</label>&ensp;&ensp; -->
				<label><input type="radio" name="homeNewTopMode" value="0" '. Is::Checked($TS_homeNewTopMode,0) .'/>关闭</label>&ensp;&ensp;
			</td>
		</tr>
		<tr>
			<td align="right" class="font1_2d">首页最新消息置顶文章数：</td>
			<td align="left"><input type="text" id="homeNewTopNum" name="homeNewTopNum" size="50" style="width:30px;" value="'. $TS_homeNewTopNum .'" /></td>
		</tr>
		<tr>
			<td align="right" class="font1_2d">首页最新消息显示数量：</td>
			<td align="left"><input type="text" id="homeNewNum" name="homeNewNum" size="50" style="width:30px;" value="'. $TS_homeNewNum .'" /></td>
		</tr>
		<tr>
			<td align="right" class="font1_2d">首页最新消息框高度：</td>
			<td align="left"><input type="text" id="homeNewBoxH" name="homeNewBoxH" size="50" style="width:30px;" value="'. $TS_homeNewBoxH .'" />&ensp;px&ensp;&ensp;'. $skin->TishiBox('为0则表示系统自适应高度') .'</td>
		</tr>
		<tr>
			<td align="right" class="font1_2d">最新消息最大条数：</td>
			<td align="left">
				<input type="text" id="homeNewMoreNum" name="homeNewMoreNum" size="50" style="width:30px;" value="'. $TS_homeNewMoreNum .'" />
				&ensp;'. $skin->TishiBox('为0则表示显示全部') .'
			</td>
		</tr>
		</tbody>

		<tr>
			<td align="right">首页精彩推荐：</td>
			<td>
				<label><input type="radio" id="isHomeRecom1" name="isHomeRecom" value="1" '. Is::Checked($TS_isHomeRecom,1) .' onclick="CheckHomeRecom();" />显示默认位置</label>&ensp;&ensp;
				<label><input type="radio" id="isHomeRecom2" name="isHomeRecom" value="2" '. Is::Checked($TS_isHomeRecom,2) .' onclick="CheckHomeRecom();" />显示左侧</label>&ensp;&ensp;
				<label><input type="radio" id="isHomeRecom0" name="isHomeRecom" value="0" '. Is::Checked($TS_isHomeRecom,0) .' onclick="CheckHomeRecom();" />隐藏</label>&ensp;&ensp;
			</td>
		</tr>
		<tbody id="homeRecomBox" style="display:none;">
		<tr>
			<td align="right" class="font1_2d">首页精彩推荐图文文章数：</td>
			<td align="left"><input type="text" id="homeRecomImgNum" name="homeRecomImgNum" size="50" style="width:30px;" value="'. $TS_homeRecomImgNum .'" /></td>
		</tr>
		<tr>
			<td align="right" class="font1_2d">首页精彩推荐文字文章数：</td>
			<td align="left"><input type="text" id="homeRecomNum" name="homeRecomNum" size="50" style="width:30px;" value="'. $TS_homeRecomNum .'" /></td>
		</tr>
		<tr>
			<td align="right" class="font1_2d">首页精彩推荐框高度：</td>
			<td align="left"><input type="text" id="homeRecomBoxH" name="homeRecomBoxH" size="50" style="width:30px;" value="'. $TS_homeRecomBoxH .'" />&ensp;px&ensp;'. $skin->TishiBox('为0则表示系统自适应高度') .'</td>
		</tr>
		</tbody>
		<tr>
			<td align="right">首页滚动图片：</td>
			<td>
				<label><input type="radio" id="isHomeMarImg1" name="isHomeMarImg" value="1" '. Is::Checked($TS_isHomeMarImg,1) .' onclick="CheckHomeMarImg();" />显示</label>&ensp;&ensp;
				<label><input type="radio" id="isHomeMarImg0" name="isHomeMarImg" value="0" '. Is::Checked($TS_isHomeMarImg,0) .' onclick="CheckHomeMarImg();" />隐藏</label>&ensp;&ensp;
			</td>
		</tr>
		<tbody id="homeMarImgBox" style="display:none;">
		<!-- <tr>
			<td align="right" class="font1_2d">首页滚动图片模式：</td>
			<td>
				<label><input type="radio" name="homeMarImgMode" value="1" '. Is::Checked($TS_homeMarImgMode,1) .'/>自动左滚</label>&ensp;&ensp;
			</td>
		</tr>
		<tr>
			<td align="right" class="font1_2d">首页滚动图片宽：</td>
			<td><input type="text" id="homeMarImgW" name="homeMarImgW" size="50" style="width:30px;" value="'. $TS_homeMarImgW .'" /> px</td>
		</tr>
		<tr>
			<td align="right" class="font1_2d">首页滚动图片高：</td>
			<td><input type="text" id="homeMarImgH" name="homeMarImgH" size="50" style="width:30px;" value="'. $TS_homeMarImgH .'" /> px</td>
		</tr> -->
		<tr>
			<td align="right" class="font1_2d">滚动图片最多显示数量：</td>
			<td align="left"><input type="text" id="homeMarImgNum" name="homeMarImgNum" size="50" style="width:30px;" value="'. $TS_homeMarImgNum .'" /></td>
		</tr>
		</tbody>
		<tr>
			<td align="right">首页热门文章：</td>
			<td>
				<label><input type="radio" id="isHomeHot1" name="isHomeHot" value="1" '. Is::Checked($TS_isHomeHot,1) .' onclick="CheckHomeHot();" />显示默认位置</label>&ensp;&ensp;
				<label><input type="radio" id="isHomeHot2" name="isHomeHot" value="2" '. Is::Checked($TS_isHomeHot,2) .' onclick="CheckHomeHot();" />显示左侧</label>&ensp;&ensp;
				<label><input type="radio" id="isHomeHot3" name="isHomeHot" value="3" '. Is::Checked($TS_isHomeHot,3) .' onclick="CheckHomeHot();" />显示右侧</label>&ensp;&ensp;
				<label><input type="radio" id="isHomeHot0" name="isHomeHot" value="0" '. Is::Checked($TS_isHomeHot,0) .' onclick="CheckHomeHot();" />隐藏</label>&ensp;&ensp;
			</td>
		</tr>
		<tbody id="homeHotBox" style="display:none;">
		<tr>
			<td align="right" class="font1_2d">首页热门文章名称：</td>
			<td><input type="text" id="homeHotName" name="homeHotName" size="50" style="width:100px;" value="'. $TS_homeHotName .'" /></td>
		</tr>
		<tr>
			<td align="right" class="font1_2d">首页热门文章排序模式：</td>
			<td>
				<select id="homeHotSort" name="homeHotSort">
					<option value="readNum" '. Is::Selected($TS_homeHotSort,'readNum') .'>阅读量降序</option>
					<option value="replyNum" '. Is::Selected($TS_homeHotSort,'replyNum') .'>评论数降序</option>
					<option value="time" '. Is::Selected($TS_homeHotSort,'time') .'>发布时间降序</option>
					<option value="revTime" '. Is::Selected($TS_homeHotSort,'revTime') .'>修改时间降序</option>
					<option value="isRecom DESC,IF_time" '. Is::Selected($TS_homeHotSort,'isRecom DESC,IF_time') .'>推荐降序</option>
					<option value="isTop DESC,IF_time" '. Is::Selected($TS_homeHotSort,'isTop DESC,IF_time') .'>置顶降序</option>
				</select>
			</td>
		</tr>
		<tr>
			<td align="right" class="font1_2d">首页热门文章日期：</td>
			<td align="left">
				<label><input type="radio" name="homeHotIsDate" value="true" '. Is::Checked($TS_homeHotIsDate,'true') .' />显示</label>&ensp;&ensp;
				<label><input type="radio" name="homeHotIsDate" value="false" '. Is::Checked($TS_homeHotIsDate,'false') .' />隐藏</label>&ensp;&ensp;
			</td>
		</tr>
		<tr>
			<td align="right" class="font1_2d">首页热门文章图文文章数：</td>
			<td align="left"><input type="text" id="homeHotImgNum" name="homeHotImgNum" size="50" style="width:30px;" value="'. $TS_homeHotImgNum .'" /></td>
		</tr>
		<tr>
			<td align="right" class="font1_2d">首页热门文章文字文章数：</td>
			<td align="left"><input type="text" id="homeHotNum" name="homeHotNum" size="50" style="width:30px;" value="'. $TS_homeHotNum .'" /></td>
		</tr>
		</tbody>
		<tr>
			<td align="right">首页最新留言：</td>
			<td>
				<label><input type="radio" id="isHomeMessage1" name="isHomeMessage" value="1" '. Is::Checked($TS_isHomeMessage,1) .' onclick="CheckHomeMessage();" />显示默认位置</label>&ensp;&ensp;
				<label><input type="radio" id="isHomeMessage2" name="isHomeMessage" value="2" '. Is::Checked($TS_isHomeMessage,2) .' onclick="CheckHomeMessage();" />显示左侧</label>&ensp;&ensp;
				<label><input type="radio" id="isHomeMessage3" name="isHomeMessage" value="3" '. Is::Checked($TS_isHomeMessage,3) .' onclick="CheckHomeMessage();" />显示右侧</label>&ensp;&ensp;
				<label><input type="radio" id="isHomeMessage0" name="isHomeMessage" value="0" '. Is::Checked($TS_isHomeMessage,0) .' onclick="CheckHomeMessage();" />隐藏</label>&ensp;&ensp;
			</td>
		</tr>
		<tbody id="homeMessageBox" style="display:none;">
		<tr>
			<td align="right" class="font1_2d">首页最新留言名称：</td>
			<td><input type="text" id="homeMessageName" name="homeMessageName" size="50" style="width:100px;" value="'. $TS_homeMessageName .'" /></td>
		</tr>
		<tr>
			<td align="right" class="font1_2d">首页最新留言高度：</td>
			<td>
				<input type="text" id="homeMessageH" name="homeMessageH" size="50" style="width:30px;" value="'. $TS_homeMessageH .'" />px
				&ensp;<label><input type="checkbox" name="homeMessageHmode" value="auto" '. Is::Checked($TS_homeMessageHmode,'auto') .' />超过出现滚动条</label>&ensp;&ensp;
				&ensp;<span class="font3_2">(0表示高度自适应)</span>
			</td>
		</tr>
		<tr>
			<td align="right" class="font1_2d">首页最新留言显示数量：</td>
			<td align="left"><input type="text" id="homeMessageNum" name="homeMessageNum" size="50" style="width:30px;" value="'. $TS_homeMessageNum .'" /></td>
		</tr>
		<tr>
			<td align="right" class="font1_2d">首页最新留言显示长度：</td>
			<td align="left"><input type="text" id="homeMessageLen" name="homeMessageLen" size="50" style="width:30px;" value="'. $TS_homeMessageLen .'" /> 字符</td>
		</tr>
		</tbody>
		<tr>
			<td align="right">首页最新评论：</td>
			<td>
				<label><input type="radio" id="isHomeReply1" name="isHomeReply" value="1" '. Is::Checked($TS_isHomeReply,1) .' onclick="CheckHomeReply();" />显示默认位置</label>&ensp;&ensp;
				<label><input type="radio" id="isHomeReply2" name="isHomeReply" value="2" '. Is::Checked($TS_isHomeReply,2) .' onclick="CheckHomeReply();" />显示左侧</label>&ensp;&ensp;
				<label><input type="radio" id="isHomeReply3" name="isHomeReply" value="3" '. Is::Checked($TS_isHomeReply,3) .' onclick="CheckHomeReply();" />显示右侧</label>&ensp;&ensp;
				<label><input type="radio" id="isHomeReply0" name="isHomeReply" value="0" '. Is::Checked($TS_isHomeReply,0) .' onclick="CheckHomeReply();" />隐藏</label>&ensp;&ensp;
			</td>
		</tr>
		<tbody id="homeReplyBox" style="display:none;">
		<tr>
			<td align="right" class="font1_2d">首页最新评论名称：</td>
			<td><input type="text" id="homeReplyName" name="homeReplyName" size="50" style="width:100px;" value="'. $TS_homeReplyName .'" /></td>
		</tr>
		<tr>
			<td align="right" class="font1_2d">首页最新评论高度：</td>
			<td>
				<input type="text" id="homeReplyH" name="homeReplyH" size="50" style="width:30px;" value="'. $TS_homeReplyH .'" />px
				&ensp;<label><input type="checkbox" name="homeReplyHmode" value="auto" '. Is::Checked($TS_homeReplyHmode,'auto') .' />超过出现滚动条</label>&ensp;&ensp;
				&ensp;<span class="font3_2">(0表示高度自适应)</span>
			</td>
		</tr>
		<tr>
			<td align="right" class="font1_2d">首页最新评论显示数量：</td>
			<td align="left"><input type="text" id="homeReplyNum" name="homeReplyNum" size="50" style="width:30px;" value="'. $TS_homeReplyNum .'" /></td>
		</tr>
		<tr>
			<td align="right" class="font1_2d">首页最新评论显示长度：</td>
			<td align="left"><input type="text" id="homeReplyLen" name="homeReplyLen" size="50" style="width:30px;" value="'. $TS_homeReplyLen .'" /> 字符</td>
		</tr>
		</tbody>
		<tr>
			<td align="right">最新会员排名：</td>
			<td>
				<label><input type="radio" id="isHomeNewUsers1" name="isHomeNewUsers" value="1" '. Is::Checked($TS_isHomeNewUsers,1) .' onclick="CheckHomeNewUsers();" />显示默认位置</label>&ensp;&ensp;
				<label><input type="radio" id="isHomeNewUsers2" name="isHomeNewUsers" value="2" '. Is::Checked($TS_isHomeNewUsers,2) .' onclick="CheckHomeNewUsers();" />显示左侧</label>&ensp;&ensp;
				<label><input type="radio" id="isHomeNewUsers3" name="isHomeNewUsers" value="3" '. Is::Checked($TS_isHomeNewUsers,3) .' onclick="CheckHomeNewUsers();" />显示右侧</label>&ensp;&ensp;
				<label><input type="radio" id="isHomeNewUsers0" name="isHomeNewUsers" value="0" '. Is::Checked($TS_isHomeNewUsers,0) .' onclick="CheckHomeNewUsers();" />隐藏</label>&ensp;&ensp;
			</td>
		</tr>
		<tbody id="homeNewUsersBox" style="display:none;">
		<tr>
			<td align="right" class="font1_2d">最新会员排名名称：</td>
			<td><input type="text" id="homeNewUsersName" name="homeNewUsersName" size="50" style="width:100px;" value="'. $TS_homeNewUsersName .'" /></td>
		</tr>
		<tr>
			<td align="right" class="font1_2d">最新会员排名数量：</td>
			<td align="left"><input type="text" id="homeNewUsersNum" name="homeNewUsersNum" size="50" style="width:30px;" value="'. $TS_homeNewUsersNum .'" /></td>
		</tr>
		</tbody>
		<tr>
			<td align="right">会员积分排名：</td>
			<td>
				<label><input type="radio" id="isHomeRankUsers1" name="isHomeRankUsers" value="1" '. Is::Checked($TS_isHomeRankUsers,1) .' onclick="CheckHomeRankUsers();" />显示默认位置</label>&ensp;&ensp;
				<label><input type="radio" id="isHomeRankUsers2" name="isHomeRankUsers" value="2" '. Is::Checked($TS_isHomeRankUsers,2) .' onclick="CheckHomeRankUsers();" />显示左侧</label>&ensp;&ensp;
				<label><input type="radio" id="isHomeRankUsers3" name="isHomeRankUsers" value="3" '. Is::Checked($TS_isHomeRankUsers,3) .' onclick="CheckHomeRankUsers();" />显示右侧</label>&ensp;&ensp;
				<label><input type="radio" id="isHomeRankUsers0" name="isHomeRankUsers" value="0" '. Is::Checked($TS_isHomeRankUsers,0) .' onclick="CheckHomeRankUsers();" />隐藏</label>&ensp;&ensp;
			</td>
		</tr>
		<tbody id="homeRankUsersBox" style="display:none;">
		<tr>
			<td align="right" class="font1_2d">会员积分排名名称：</td>
			<td><input type="text" id="homeRankUsersName" name="homeRankUsersName" size="50" style="width:100px;" value="'. $TS_homeRankUsersName .'" /></td>
		</tr>
		<tr>
			<td align="right" class="font1_2d">会员积分排名依据：</td>
			<td align="left">
				<label><input type="radio" name="homeRankUsersType" value="score1" '. Is::Checked($TS_homeRankUsersType,'score1') .' />积分1</label>&ensp;&ensp;
				<label><input type="radio" name="homeRankUsersType" value="score2" '. Is::Checked($TS_homeRankUsersType,'score2') .' />积分2</label>&ensp;&ensp;
				<label><input type="radio" name="homeRankUsersType" value="score3" '. Is::Checked($TS_homeRankUsersType,'score3') .' />积分3</label>&ensp;&ensp;
			</td>
		</tr>
		<tr>
			<td align="right" class="font1_2d">会员积分排名数量：</td>
			<td align="left"><input type="text" id="homeRankUsersNum" name="homeRankUsersNum" size="50" style="width:30px;" value="'. $TS_homeRankUsersNum .'" /></td>
		</tr>
		</tbody>
		'. AppQiandao::TplSys($TS_isHomeQiandao, $TS_homeQiandaoName, $TS_isQiandaoRank, $TS_qiandaoRankName, $TS_qiandaoRankType, $TS_qiandaoRankNum) .'
		'. AppBbs::TplSys($TS_isHomeBbs, $TS_homeBbsName, $TS_homeBbsH, $TS_homeBbsHmode, $TS_homeBbsNum, $TS_homeBbsLen) .'
		</table>
		');

		$aboutStr = $aboutMoreStr = $contactStr = $contactMoreStr = '<optgroup label="单篇页" style="font-weight:normal;"></optgroup>';
		$webexe=$DB->query("select IW_ID,IW_theme from ". OT_dbPref ."infoWeb where IW_type='newsWeb' and IW_mode='web' order by IW_rank ASC");
		if (! $row = $webexe->fetch()){
			$optionStr = '<option value="" style="color:#9d9d9d;">无记录</option>';
			$aboutStr .= $optionStr;
			$aboutMoreStr .= $optionStr;
			$contactStr .= $optionStr;
			$contactMoreStr .= $optionStr;
		}else{
			do {
				$aboutStr .= '<option value="'. $row['IW_ID'] .'" '. Is::Selected($row['IW_ID'],$TS_homeAboutID) .'>&ensp;&ensp;'. $row['IW_theme'] .'</option>';
				$aboutMoreStr .= '<option value="'. $row['IW_ID'] .'" '. Is::Selected($row['IW_ID'],$TS_homeAboutMoreID) .'>&ensp;&ensp;'. $row['IW_theme'] .'</option>';
				$contactStr .= '<option value="'. $row['IW_ID'] .'" '. Is::Selected($row['IW_ID'],$TS_homeContactID) .'>&ensp;&ensp;'. $row['IW_theme'] .'</option>';
				$contactMoreStr .= '<option value="'. $row['IW_ID'] .'" '. Is::Selected($row['IW_ID'],$TS_homeContactMoreID) .'>&ensp;&ensp;'. $row['IW_theme'] .'</option>';
			}while ($row = $webexe->fetch());
		}
		unset($webexe);

		$optionStr = '<optgroup label="底部栏目" style="font-weight:normal;"></optgroup>';
		$aboutStr .= $optionStr;
		$aboutMoreStr .= $optionStr;
		$contactStr .= $optionStr;
		$contactMoreStr .= $optionStr;

		$webexe=$DB->query("select IW_ID,IW_theme from ". OT_dbPref ."infoWeb where IW_type='bottom' and IW_mode='web' order by IW_rank ASC");
		if (! $row = $webexe->fetch()){
			$optionStr = '<option value="" style="color:#9d9d9d;">无记录</option>';
			$aboutStr .= $optionStr;
			$aboutMoreStr .= $optionStr;
			$contactStr .= $optionStr;
			$contactMoreStr .= $optionStr;
		}else{
			do {
				$aboutStr .= '<option value="'. $row['IW_ID'] .'" '. Is::Selected($row['IW_ID'],$TS_homeAboutID) .'>&ensp;&ensp;'. $row['IW_theme'] .'</option>';
				$aboutMoreStr .= '<option value="'. $row['IW_ID'] .'" '. Is::Selected($row['IW_ID'],$TS_homeAboutMoreID) .'>&ensp;&ensp;'. $row['IW_theme'] .'</option>';
				$contactStr .= '<option value="'. $row['IW_ID'] .'" '. Is::Selected($row['IW_ID'],$TS_homeContactID) .'>&ensp;&ensp;'. $row['IW_theme'] .'</option>';
				$contactMoreStr .= '<option value="'. $row['IW_ID'] .'" '. Is::Selected($row['IW_ID'],$TS_homeContactMoreID) .'>&ensp;&ensp;'. $row['IW_theme'] .'</option>';
			}while ($row = $webexe->fetch());
		}
		unset($webexe);

		$newsStr = '<option value="-1" '. Is::Selected($TS_homeNewsID,'-1') .'>0、'. $systemArr['SYS_announName'] .'</option>';
		$proStr = '<option value="-1" '. Is::Selected($TS_homeProID,'-1') .'>0、'. $systemArr['SYS_announName'] .'</option>';
		$typeNum = 0;
		$typeexe=$DB->query('select IT_ID,IT_theme from '. OT_dbPref .'infoType where IT_state=1 and IT_level=1 order by IT_rank ASC');
		while ($row = $typeexe->fetch()){
			$typeNum ++;
			$newsStr .= '<option value="'. $row['IT_ID'] .'" '. Is::Selected($TS_homeNewsID,$row['IT_ID']) .'>'. $typeNum .'、'. $row['IT_theme'] .'</option>';
			$proStr .= '<option value="'. $row['IT_ID'] .'" '. Is::Selected($TS_homeProID,$row['IT_ID']) .'>'. $typeNum .'、'. $row['IT_theme'] .'</option>';
			$type2exe=$DB->query('select IT_ID,IT_theme from '. OT_dbPref .'infoType where IT_state=1 and IT_level=2 and IT_fatID='. $row['IT_ID'] .' order by IT_rank ASC');
				while ($row2 = $type2exe->fetch()){
					$newsStr .= '<option value="'. $row2['IT_ID'] .'" '. Is::Selected($TS_homeNewsID,$row2['IT_ID']) .'>&ensp;&ensp;&ensp;┣&ensp;'. $row2['IT_theme'] .'</option>';
					$proStr .= '<option value="'. $row2['IT_ID'] .'" '. Is::Selected($TS_homeProID,$row2['IT_ID']) .'>&ensp;&ensp;&ensp;┣&ensp;'. $row2['IT_theme'] .'</option>';
				}
		}
		echo('
		<table id="tabQiye" style="display:none;" cellpadding="0" cellspacing="0" summary="" class="padd3">
		<tr><td style="width:150px;"></td><td></td></tr>
		<tr><td colspan="2" style="color:red;padding-bottom:10px;">提醒：企业站专属菜单针对企业类模板首页信息模块读取选择，非企业站模板一般不会用到。</td></tr>
		<tr>
			<td align="right">首页公司简介：</td>
			<td>
				<select id="homeAboutID" name="homeAboutID">
					<option value=""></option>
					'. $aboutStr .'
				</select>
			</td>
		</tr>
		<tr>
			<td align="right">首页公司简介[更多]：</td>
			<td>
				<select id="homeAboutMoreID" name="homeAboutMoreID">
					<option value=""></option>
					'. $aboutMoreStr .'
				</select>
			</td>
		</tr>
		<tr>
			<td align="right">首页联系我们：</td>
			<td>
				<select id="homeContactID" name="homeContactID">
					<option value=""></option>
					'. $contactStr .'
				</select>
			</td>
		</tr>
		<tr>
			<td align="right">首页联系我们[更多]：</td>
			<td>
				<select id="homeContactMoreID" name="homeContactMoreID">
					<option value=""></option>
					'. $contactMoreStr .'
				</select>
			</td>
		</tr>
		<tr>
			<td align="right">首页新闻：</td>
			<td>
				<select id="homeNewsID" name="homeNewsID" style="width:180px;">
					<option value=""></option>
					'. $newsStr .'
				</select>
			</td>
		</tr>
		<tr>
			<td align="right" class="font1_2d">首页新闻数量：</td>
			<td><input type="text" id="homeNewsNum" name="homeNewsNum" size="50" style="width:50px;" value="'. $TS_homeNewsNum .'" /></td>
		</tr>
		<tr>
			<td align="right">首页产品：</td>
			<td>
				<select id="homeProID" name="homeProID" style="width:180px;">
					<option value=""></option>
					'. $proStr .'
				</select>
			</td>
		</tr>
		<tr>
			<td align="right" class="font1_2d">首页产品数量：</td>
			<td><input type="text" id="homeProNum" name="homeProNum" size="50" style="width:50px;" value="'. $TS_homeProNum .'" /></td>
		</tr>
		<!-- <tr>
			<td align="right">首页栏目：</td>
			<td>
			</td>
		</tr> -->
		</table>


		<table id="tabSub" style="display:none;" cellpadding="0" cellspacing="0" summary="" class="padd3">
		<tr><td style="width:150px;"></td><td></td></tr>
		<tr>
			<td align="right">错误页面跳转：</td>
			<td align="left">
				<label><input type="radio" name="subWeb404" onclick="CheckSubWeb404()" value="" '. Is::Checked($TS_subWeb404,'') .' />首页</label>&ensp;&ensp;
				<label><input type="radio" name="subWeb404" onclick="CheckSubWeb404()" value="404.html" '. Is::Checked($TS_subWeb404,'404.html') .' />404.html</label>&ensp;&ensp;
				<label><input type="radio" name="subWeb404" onclick="CheckSubWeb404()" value="404.php" '. Is::Checked($TS_subWeb404,'404.php') .' />404.php<span style="color:red;">(推荐)</span></label>&ensp;&ensp;
				<label><input type="radio" name="subWeb404" onclick="CheckSubWeb404()" value="404gy.php" '. Is::Checked($TS_subWeb404,'404gy.php') .' />404gy.php(公益)</label>&ensp;&ensp;
				<label><input type="radio" id="subWeb404Other" name="subWeb404" onclick="CheckSubWeb404()" value="-1" />其他页面</label><span id="subWeb404NewBox" style="display:none;"><input type="text" style="width:80px;" id="subWeb404New" name="subWeb404New" value="'. $TS_subWeb404 .'" /><span class="font2_2">(请确保前台目录下有该文件)</span></span>&ensp;&ensp;
				<span style="color:red;">（仅对动态路径有效，其他地方需要空间里或服务器设置）</span>
			</td>
		</tr>
		'. AdmArea::ListMode('最新消息列表显示模式','newListMode',$TS_newListMode) .'
		<tr>
			<td align="right" class="font1_2d">最新消息每页条数：</td>
			<td align="left"><input type="text" id="homeNewListNum" name="homeNewListNum" size="50" style="width:30px;" value="'. $TS_homeNewListNum .'" /></td>
		</tr>
		'. AdmArea::ListMode('网站公告列表显示模式','announListMode',$TS_announListMode) .'
		<tr>
			<td align="right" class="font1_2d">网站公告每页条数：</td>
			<td align="left"><input type="text" id="homeAnnounListNum" name="homeAnnounListNum" size="50" style="width:30px;" value="'. $TS_homeAnnounListNum .'" /></td>
		</tr>
		<tbody style="display:none;">
		'. AdmArea::ListMode('会员文章显示模式','userListMode',$TS_userListMode) .'
		<tr>
			<td align="right" class="font1_2d">会员文章每页条数：</td>
			<td align="left"><input type="text" id="userListNum" name="userListNum" size="50" style="width:30px;" value="'. $TS_userListNum .'" /></td>
		</tr>
		</tbody>
		'. AdmArea::ListMode('搜索列表显示模式','searchListMode',$TS_searchListMode) .'
		<tr>
			<td align="right" class="font1_2d">搜索列表每页条数：</td>
			<td align="left"><input type="text" id="searchListNum" name="searchListNum" size="50" style="width:30px;" value="'. $TS_searchListNum .'" /></td>
		</tr>
		<!-- <tr>
			<td align="right" class="font1_2d">搜索词允许特殊字符：</td>
			<td align="left" colspan="4">
				<label><input type="checkbox" name="searchAllowStr[]" value="[(]" '. Is::InstrChecked($TS_searchAllowStr,'[(]') .'>(</label>&ensp;&ensp;
				<label><input type="checkbox" name="searchAllowStr[]" value="[)]" '. Is::InstrChecked($TS_searchAllowStr,'[)]') .'>)</label>&ensp;&ensp;
				<label><input type="checkbox" name="searchAllowStr[]" value="[-]" '. Is::InstrChecked($TS_searchAllowStr,'[-]') .'>-</label>&ensp;&ensp;
				<label><input type="checkbox" name="searchAllowStr[]" value="[/]" '. Is::InstrChecked($TS_searchAllowStr,'[/]') .'>/</label>&ensp;&ensp;
				<span style="color:red;">(建议都不要打钩，特殊字符会降低网站安全性)</span>
			</td>
		</tr> -->
		<tr>
			<td align="right" class="font1_2d" valign="top" style="padding-top:6px;">
				搜索页禁止词：<br />
				<span class="font2_2">（多个用竖杆“|”隔开）</span>
			</td>
			<td style="padding-bottom:4px;"><textarea id="searchBadStr" name="searchBadStr" rows="5" cols="40" style="width:500px; height:40px;">'. $TS_searchBadStr .'</textarea></td>
		</tr>
		<tr>
			<td align="right">标签页：</td>
			<td>
				<label><input type="radio" id="isMark1" name="isMark" value="1" '. Is::Checked($TS_isMark,1) .' '. Is::Checked($TS_isMark,2) .' onclick="CheckMark();" />开启</label>&ensp;&ensp;
				<label title="标签页开启，但标签的超链接不被搜索引擎追踪，采用nofollow属性"><input type="radio" id="isMark2" name="isMark" value="2" '. Is::Checked($TS_isMark,2) .' onclick="CheckMark();" />开启（但不被搜索引擎追踪）</label>&ensp;&ensp;
				<label><input type="radio" id="isMark0" name="isMark" value="0" '. Is::Checked($TS_isMark,0) .' onclick="CheckMark();" />关闭</label>&ensp;&ensp;
			</td>
		</tr>
		<tbody id="markBox">
		'. AdmArea::ListMode('标签列表显示模式','markListMode',$TS_markListMode,'font1_2d') .'
		<tr>
			<td align="right" class="font1_2d">标签列表每页条数：</td>
			<td align="left"><input type="text" id="markListNum" name="markListNum" size="50" style="width:30px;" value="'. $TS_markListNum .'" /></td>
		</tr>
		<tr>
			<td align="right" class="font1_2d" valign="top" style="padding-top:6px;">
				标签页禁止词：<br />
				<span class="font2_2">（多个用竖杆“|”隔开）</span>
			</td>
			<td style="padding-bottom:4px;"><textarea id="markBadStr" name="markBadStr" rows="5" cols="40" style="width:500px; height:40px;">'. $TS_markBadStr .'</textarea></td>
		</tr>
		</tbody>
		<tr>
			<td align="right">启用留言板：</td>
			<td>
				<label><input type="radio" id="isMessage1" name="isMessage" value="1" '. Is::Checked($TS_isMessage,1) .' onclick="CheckMessage();" />启用</label>&ensp;&ensp;
				<label><input type="radio" id="isMessage10" name="isMessage" value="10" '. Is::Checked($TS_isMessage,10) .' onclick="CheckMessage();" />仅限会员</label>&ensp;&ensp;
				<label><input type="radio" id="isMessage0" name="isMessage" value="0" '. Is::Checked($TS_isMessage,0) .' onclick="CheckMessage();" />禁用</label>&ensp;&ensp;
			</td>
		</tr>
		<tr>
			<td align="right" class="font1_2d">留言板名称：</td>
			<td>
				<input type="text" id="messageName" name="messageName" value="'. $TS_messageName .'" style="width:120px;" />
			</td>
		</tr>
		<tr>
			<td align="right" class="font1_2d" valign="top" style="padding-top:6px;">
				留言板页面关键字：<br />
				<span class="font3_2">多个用英文逗号“,”隔开</span>
			</td>
			<td style="padding-bottom:4px;"><textarea id="messageWebKey" name="messageWebKey" rows="5" cols="40" style="width:510px; height:30px;">'. $TS_messageWebKey .'</textarea></td>
		</tr>
		<tr>
			<td align="right" class="font1_2d" valign="top" style="padding-top:6px;">
				留言板页面描述：<br />
				<span class="font3_2">（便于搜索引擎搜到）</span>
			</td>
			<td style="padding-bottom:4px;"><textarea id="messageWebDesc" name="messageWebDesc" rows="5" cols="40" style="width:510px; height:45px;">'. $TS_messageWebDesc .'</textarea></td>
		</tr>
		<tr id="messageCloseBox">
			<td align="right" class="font1_2d" valign="top">留言板关闭信息：</td>
			<td>
				<textarea id="messageCloseNote" name="messageCloseNote" cols="40" rows="4" style="width:510px;height:120px;" class="text" onclick=\'LoadEditor("messageCloseNote",510,120,"|miniMenu|");\' title="点击开启编辑器模式">'. Str::MoreReplace($TS_messageCloseNote,'textarea') .'</textarea>
			</td>
		</tr>
		<tbody id="messageBox" style="display:none;">
		<tr>
			<td align="right" class="font1_2d">留言本每页显示数量：</td>
			<td align="left"><input type="text" id="messageNum" name="messageNum" size="50" style="width:30px;" value="'. $TS_messageNum .'" /></td>
		</tr>
		<tr>
			<td align="right" class="font1_2d">留言连续发言间隔秒数：</td>
			<td align="left"><input type="text" id="messageSecond" name="messageSecond" size="50" style="width:30px;" value="'. $TS_messageSecond .'" /> 秒</td>
		</tr>
		<tr>
			<td align="right" class="font1_2d">留言审核：</td>
			<td>
				<label><input type="radio" name="messageAudit" value="0" '. Is::Checked($TS_messageAudit,0) .' />需审核</label>&ensp;&ensp;
				<label><input type="radio" name="messageAudit" value="1" '. Is::Checked($TS_messageAudit,1) .' />无需审核</label>&ensp;&ensp;
			</td>
		</tr>
		<tr>
			<td align="right" class="font1_2d">留言最大字数：</td>
			<td align="left"><input type="text" id="messageMaxLen" name="messageMaxLen" size="50" style="width:30px;" value="'. $TS_messageMaxLen .'" /></td>
		</tr>
		<tr>
			<td align="right" class="font1_2d">留言回复称呼：</td>
			<td><input type="text" id="messageAdminName" name="messageAdminName" size="50" style="width:100px;" value="'. $TS_messageAdminName .'" /></td>
		</tr>
		</tbody>
		<tr style="display:none;" class="font1_2d">
			<td align="right">留言/评论热门评论：</td>
			<td align="left">
				显示数量：<input type="text" id="messageHotNum" name="messageHotNum" size="50" style="width:30px;" value="'. $TS_messageHotNum .'" />，
				要求“顶”投票数至少<input type="text" id="messageVoteNum" name="messageVoteNum" size="50" style="width:30px;" value="'. $TS_messageVoteNum .'" />个
			</td>
		</tr>
		<tr>
			<td align="right" valign="top" style="padding-top:6px;" class="font1_2d">
				留言/评论/回帖禁止词：<br />
				<span class="font2_2">（多个用竖杆“|”隔开）</span>
			</td>
			<td style="padding-bottom:4px;"><textarea id="messageBadWord" name="messageBadWord" rows="5" cols="40" style="width:500px; height:60px;">'. $TS_messageBadWord .'</textarea></td>
		</tr>
		<tr>
			<td align="right" class="font1_2d">留言/评论/回帖跟随验证：</td>
			<td>
				<label><input type="radio" name="messageRndMd5" value="1" '. Is::Checked($TS_messageRndMd5,1) .' />开启</label>&ensp;&ensp;
				<label><input type="radio" name="messageRndMd5" value="2" '. Is::Checked($TS_messageRndMd5,2) .' />开启(过期自动更新)</label>&ensp;&ensp;
				<label><input type="radio" name="messageRndMd5" value="0" '. Is::Checked($TS_messageRndMd5,0) .' />关闭</label>&ensp;&ensp;
				&ensp;&ensp;<span class="font2_2">（建议开启，可以一定程度防止机器灌水）</span>
			</td>
		</tr>
		</table>
		');

		if (! AppBase::Jud()){
			$skin->PaySoftBox('tabBuy','您尚未购买商业版基础包插件，无法使用该功能。');
			echo('<input type="hidden" id="authState" name="authState" value="false" />');
			
		}elseif ($sysAdminArr['SA_isLan'] == 1 && $sysAdminArr['SA_sendUrlMode'] == 0){
			$skin->PaySoftBox('tabBuy',$skin->LanPaySoft());
			echo('<input type="hidden" id="authState" name="authState" value="false" />');
			
		}else{

			$paraArr = array(
				'TS_navWidthMode'		=> $TS_navWidthMode ,
				'TS_isTopAd'			=> $TS_isTopAd ,
				'TS_topAdCode'			=> $TS_topAdCode ,
				'searchArea1'			=> $searchArea1 ,
				'searchArea1_title'		=> $searchArea1_title ,
				'searchArea1_rank'		=> $searchArea1_rank ,
				'searchArea2'			=> $searchArea2 ,
				'searchArea2_title'		=> $searchArea2_title ,
				'searchArea2_rank'		=> $searchArea2_rank ,
				'searchArea3'			=> $searchArea3 ,
				'searchArea3_title'		=> $searchArea3_title ,
				'searchArea3_rank'		=> $searchArea3_rank ,
				'searchArea4'			=> $searchArea4 ,
				'searchArea4_title'		=> $searchArea4_title ,
				'searchArea4_rank'		=> $searchArea4_rank ,
				'searchArea5'			=> $searchArea5 ,
				'searchArea5_title'		=> $searchArea5_title ,
				'searchArea5_rank'		=> $searchArea5_rank ,
				'searchArea5_addi1'		=> $searchArea5_addi1 ,
				'searchArea5_addi2'		=> $searchArea5_addi2 ,
				'searchArea6'			=> $searchArea6 ,
				'searchArea6_title'		=> $searchArea6_title ,
				'searchArea6_rank'		=> $searchArea6_rank ,
				'searchArea6_addi1'		=> $searchArea6_addi1 ,
				'searchArea6_addi2'		=> $searchArea6_addi2 ,
				'TS_subWebLR'			=> $TS_subWebLR ,
				'TS_messageMode'		=> $TS_messageMode ,
				'TS_messageEvent'		=> $TS_messageEvent ,
				'TS_replyMode'			=> $TS_replyMode ,
				'TS_logoAreaStr'		=> $TS_logoAreaStr ,
				'TS_isLogoAdd'			=> $TS_isLogoAdd ,
				'TS_logoListMode'		=> $TS_logoListMode ,
				'TS_logoNote'			=> $TS_logoNote ,
				'TS_isImgBanner'		=> $TS_isImgBanner ,
				'TS_imgBannerH'			=> $TS_imgBannerH ,
				'judBbs'				=> AppBbs::Jud() ? 1 : 0 ,
				'judLogo'				=> AppLogoAdd::Jud() ? 1 : 0 ,
				);

			$getWebHtml = OTauthWeb('tplSys', 'tplSys_V5.00.php', $paraArr);
			if (strpos($getWebHtml,'(OTCMS)') === false){
				$authAlertStr='未知原因';
				if (strpos($getWebHtml,'<!-- noRemote -->') !== false){
					$authAlertStr='无法访问外网';
				}elseif (strpos($getWebHtml,'<!-- noUse -->') !== false){
					$authAlertStr='授权禁用';
				}else{
				
				}
				$getWebHtml = ''.
					$skin->PaySoftBox('tabBuy','因'. $authAlertStr .'而无法使用',true) .
					'<input type="hidden" id="authState" name="authState" value="false" />';
			}else{
				echo('
				<script language="javascript" type="text/javascript">
				$id("buyBox").style.display = "";
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
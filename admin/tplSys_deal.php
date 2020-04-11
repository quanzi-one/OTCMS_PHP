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


//用户检测
$MB->Open('','login');


switch($mudi){
	case 'deal':
		$menuFileID = 229;
		$MB->IsSecMenuRight('alertBack',$menuFileID,$dataType);
		deal();
		break;

	case 'area':
		$menuFileID = 229;
		$MB->IsSecMenuRight('alertBack',$menuFileID,$dataType);
		area();
		break;

	default:
		die('err');
}

$MB->Close();
$DB->Close();





function deal(){
	global $DB,$skin,$mudi,$menuFileID,$menuTreeID;

	$backURL		= OT::PostStr('backURL');
	$dataType		= OT::PostRegExpStr('dataType','sql');
	$dataTypeCN		= OT::PostStr('dataTypeCN');
	
	$subWebLROld	= OT::PostInt('subWebLROld');

	$skinName		= OT::PostStr('skinName');
	$skinPopup		= OT::PostStr('skinPopup');
		switch ($skinName){
			case 'def_blue':		$skinColor = '#0078bf';	break;
			case 'def_pink':		$skinColor = '#b40287';	break;
			case 'def_purple':		$skinColor = '#8001b2';	break;
			case 'def_green':		$skinColor = '#02b31a';	break;
			case 'def_yellow':		$skinColor = '#a96f04';	break;
			case 'def_black':		$skinColor = '#3f3f3f';	break;
			case 'user_inkWash':	$skinColor = '#000000';	break;
			case 'user_goumiji':	$skinColor = '#ff6301';	break;
			default :				$skinColor = '#bf3131';	break;
		}
	$redTimeDay			= OT::PostInt('redTimeDay');
	$rankUserMode		= OT::PostInt('rankUserMode');

	$topLogoMode		= OT::PostInt('topLogoMode');
	$logo				= OT::PostStr('logo');
	$fullLogo			= OT::PostStr('fullLogo');
	$logoExt			= OT::PostInt('logoExt');
	$logoW				= OT::PostInt('logoW');
	$logoH				= OT::PostInt('logoH');
	$isRss				= OT::PostInt('isRss');
	$isTopAd			= OT::PostInt('isTopAd');
	$topAdCode			= Adm::FilterEditor(OT::PostStr('topAdCode'));

	$searchArea1_title	= OT::PostStr('searchArea1_title');
		if (strlen($searchArea1_title) == 0){ $searchArea1_title='站内搜索(标题)'; }
	$searchArea2_title	= OT::PostStr('searchArea2_title');
		if (strlen($searchArea2_title) == 0){ $searchArea2_title='站内搜索(正文)'; }
	$searchArea3_title	= OT::PostStr('searchArea3_title');
		if (strlen($searchArea3_title) == 0){ $searchArea3_title='站内搜索(来源)'; }
	$searchArea4_title	= OT::PostStr('searchArea4_title');
		if (strlen($searchArea4_title) == 0){ $searchArea4_title='站内搜索(作者)'; }
	$searchArea5_title	= OT::PostStr('searchArea5_title');
		if (strlen($searchArea5_title) == 0){ $searchArea5_title='百度站内搜索'; }
	$searchArea6_title	= OT::PostStr('searchArea6_title');
		if (strlen($searchArea6_title) == 0){ $searchArea6_title='360站内搜索'; }
	$searchArea		= OT::PostInt('searchArea1') .'|'. OT::PostInt('searchArea1_rank') .'|theme|'. $searchArea1_title .'||<arr>'.
					  OT::PostInt('searchArea2') .'|'. OT::PostInt('searchArea2_rank') .'|content|'. $searchArea2_title .'||<arr>'.
					  OT::PostInt('searchArea3') .'|'. OT::PostInt('searchArea3_rank') .'|source|'. $searchArea3_title .'||<arr>'.
					  OT::PostInt('searchArea4') .'|'. OT::PostInt('searchArea4_rank') .'|writer|'. $searchArea4_title .'||<arr>'.
					  OT::PostInt('searchArea5') .'|'. OT::PostInt('searchArea5_rank') .'|baidu|'. $searchArea5_title .'|'. OT::PostStr('searchArea5_addi1') .'|'. OT::PostStr('searchArea5_addi2') .'<arr>'.
					  OT::PostInt('searchArea6') .'|'. OT::PostInt('searchArea6_rank') .'|360|'. $searchArea6_title .'|'. OT::PostStr('searchArea6_addi1') .'|'.
					  '';

	$isLogoAdd			= OT::PostInt('isLogoAdd');
	$logoAreaStr		= OT::Post('logoAreaStr');
		if (is_array($logoAreaStr)){ $logoAreaStr = implode(',',$logoAreaStr); }
	$logoListMode		= OT::PostInt('logoListMode');
	$logoNote			= OT::PostStr('logoNote');
	$TCP				= OT::PostStr('TCP');
	$beianName			= OT::PostStr('beianName');
	$beianUrl			= OT::PostStr('beianUrl');
	$copyright			= Adm::FilterEditor(OT::PostStr('copyright'));
	$isStati			= OT::PostInt('isStati');
	$statiCode			= OT::PostStr('statiCode');

	$homeAboutID		= OT::PostInt('homeAboutID');
	$homeAboutMoreID	= OT::PostInt('homeAboutMoreID');
	$homeContactID		= OT::PostInt('homeContactID');
	$homeContactMoreID	= OT::PostInt('homeContactMoreID');
	$homeNewsID			= OT::PostInt('homeNewsID');
	$homeNewsNum		= OT::PostInt('homeNewsNum');
		if ($homeNewsNum <= 0){ $homeNewsNum = 8; }
	$homeProID			= OT::PostInt('homeProID');
	$homeProNum			= OT::PostInt('homeProNum');
		if ($homeProNum <= 0){ $homeProNum = 15; }
	$homeInfoTypeID		= OT::PostInt('homeInfoTypeID');

	$navWidthMode		= OT::PostInt('navWidthMode');
	$jieriImg			= OT::PostStr('jieriImg');
	$jieriHeight		= OT::PostInt('jieriHeight');
	$navMode			= OT::PostInt('navMode');
	$navCode			= Adm::FilterEditor(OT::PostStr('navCode'));
	$navNum				= OT::PostInt('navNum');
	$navPadd			= OT::PostInt('navPadd');
	$navSubWidth		= OT::PostInt('navSubWidth');

	$homeItemMode		= OT::PostInt('homeItemMode');
	$marInfoNum			= OT::PostInt('marInfoNum');
		if ($marInfoNum <= 0){ $marInfoNum = 10; }
		elseif ($marInfoNum > 99){ $marInfoNum = 99; }
	$isHomeFlash		= OT::PostInt('isHomeFlash');
	$homeFlashMode		= OT::PostInt('homeFlashMode');
	$isHomeFlashTheme	= OT::PostInt('isHomeFlashTheme');
	$homeFlashNum		= OT::PostInt('homeFlashNum');
		if ($homeFlashNum <= 0){ $homeFlashNum = 10; }
		elseif ($homeFlashNum > 15){ $homeFlashNum = 15; }

	$isHomeAnnoun		= OT::PostInt('isHomeAnnoun');
	$homeAnnounName		= OT::PostStr('homeAnnounName');
	$homeAnnounNum		= OT::PostInt('homeAnnounNum');
		if ($homeAnnounNum <= 0){ $homeAnnounNum=6; }
	$homeAnnounListNum	= OT::PostInt('homeAnnounListNum');
		if ($homeAnnounListNum <= 0){ $homeAnnounListNum=10; }
	$isHomeNew			= OT::PostInt('isHomeNew');
	$homeNewIsType		= OT::PostInt('homeNewIsType');
	$homeNewIsDate		= OT::PostInt('homeNewIsDate');
	$homeNewTopMode		= OT::PostInt('homeNewTopMode');
	$homeNewTopNum		= OT::PostInt('homeNewTopNum');
	$homeNewNum			= OT::PostInt('homeNewNum');
		if ($homeNewNum <= 0){ $homeNewNum=12; }
	$homeNewListNum		= OT::PostInt('homeNewListNum');
		if ($homeNewListNum <= 0){ $homeNewListNum=10; }
	$homeNewMoreNum		= OT::PostInt('homeNewMoreNum');
	$homeNewBoxH		= OT::PostInt('homeNewBoxH');
	$isHomeRecom		= OT::PostInt('isHomeRecom');
	$homeRecomImgNum	= OT::PostInt('homeRecomImgNum');
	$homeRecomNum		= OT::PostInt('homeRecomNum');
	$homeRecomBoxH		= OT::PostInt('homeRecomBoxH');
	$isHomeHot			= OT::PostInt('isHomeHot');
	$homeHotName		= OT::PostStr('homeHotName');
	$homeHotSort		= OT::PostStr('homeHotSort');
	$homeHotIsDate		= OT::PostStr('homeHotIsDate');
		if (! in_array($homeHotIsDate,array('true','false'))){ $homeHotIsDate='false'; }
	$homeHotImgNum		= OT::PostInt('homeHotImgNum');
	$homeHotNum			= OT::PostInt('homeHotNum');
	$isHomeMarImg		= OT::PostInt('isHomeMarImg');
	$homeMarImgMode		= OT::PostInt('homeMarImgMode');
	$homeMarImgW		= OT::PostInt('homeMarImgW');
	$homeMarImgH		= OT::PostInt('homeMarImgH');
	$homeMarImgNum		= OT::PostInt('homeMarImgNum');
		if ($homeMarImgNum <= 0){ $homeMarImgNum = 15; }
		elseif ($homeMarImgNum > 99){ $homeMarImgNum = 99; }
	$isHomeMessage		= OT::PostInt('isHomeMessage');
	$homeMessageName	= OT::PostStr('homeMessageName');
	$homeMessageNum		= OT::PostInt('homeMessageNum');
		if ($homeMessageNum <= 0){ $homeMessageNum=8; }
	$homeMessageLen		= OT::PostInt('homeMessageLen');
	$homeMessageHmode	= OT::PostStr('homeMessageHmode');
	$homeMessageH		= OT::PostInt('homeMessageH');
		if ($homeMessageH > 0){
			if ($homeMessageHmode != 'auto'){
				$homeMessageHmode='hidden';
			}
		}else{
			$homeMessageHmode='';
		}
	$isHomeReply		= OT::PostInt('isHomeReply');
	$homeReplyName		= OT::PostStr('homeReplyName');
	$homeReplyNum		= OT::PostInt('homeReplyNum');
		if ($homeReplyNum <= 0){ $homeReplyNum=8; }
	$homeReplyLen		= OT::PostInt('homeReplyLen');
	$homeReplyHmode		= OT::PostStr('homeReplyHmode');
	$homeReplyH			= OT::PostInt('homeReplyH');
		if ($homeReplyH > 0){
			if ($homeReplyHmode != 'auto'){
				$homeReplyHmode = 'hidden';
			}
		}else{
			$homeReplyHmode = '';
		}
	$isHomeNewUsers		= OT::PostInt('isHomeNewUsers');
	$homeNewUsersName	= OT::PostStr('homeNewUsersName');
	$homeNewUsersNum	= OT::PostInt('homeNewUsersNum');
	$isHomeRankUsers	= OT::PostInt('isHomeRankUsers');
	$homeRankUsersName	= OT::PostStr('homeRankUsersName');
	$homeRankUsersType	= OT::PostStr('homeRankUsersType');
	$homeRankUsersNum	= OT::PostInt('homeRankUsersNum');

	$isHomeQiandao		= OT::PostInt('isHomeQiandao');
	$homeQiandaoName	= OT::PostStr('homeQiandaoName');
		if ($homeQiandaoName == ''){ $homeQiandaoName='每日签到'; }
	$isQiandaoRank		= OT::PostInt('isQiandaoRank');
	$qiandaoRankName	= OT::PostStr('qiandaoRankName');
		if ($qiandaoRankName == ''){ $qiandaoRankName='会员签到排行'; }
	$qiandaoRankType	= OT::PostStr('qiandaoRankType');
		if ($qiandaoRankType == ''){ $qiandaoRankType='qiandaoNum'; }
	$qiandaoRankNum		= OT::PostInt('qiandaoRankNum');
		if ($qiandaoRankNum <= 0){ $qiandaoRankNum=8; }

	$isHomeBbs			= OT::PostInt('isHomeBbs');
	$homeBbsName		= OT::PostStr('homeBbsName');
	$homeBbsNum			= OT::PostInt('homeBbsNum');
		if ($homeBbsNum <= 0){ $homeBbsNum=8; }
	$homeBbsLen			= OT::PostInt('homeBbsLen');
	$homeBbsHmode		= OT::PostStr('homeBbsHmode');
	$homeBbsH			= OT::PostInt('homeBbsH');
		if ($homeBbsH > 0){
			if ($homeBbsHmode != 'auto'){
				$homeBbsHmode='hidden';
			}
		}else{
			$homeBbsHmode='';
		}

	$messageMode		= OT::PostInt('messageMode');
	$isImgBanner		= OT::PostInt('isImgBanner');
	$imgBannerH			= OT::PostInt('imgBannerH');
	$subWebLR			= OT::PostInt('subWebLR');
	$replyMode			= OT::PostInt('replyMode');

	$userListNum		= OT::PostInt('userListNum');
		if ($userListNum < 1){ $userListNum = 10; }
	$userListMode		= OT::PostInt('userListMode');
		if ($userListMode < 1){ $userListMode = 1; }
	$searchListMode		= OT::PostInt('searchListMode');
		if ($searchListMode < 1){ $searchListMode = 1; }
	$markListMode		= OT::PostInt('markListMode');
		if ($markListMode < 1){ $markListMode = 1; }
	$announListMode		= OT::PostInt('announListMode');
		if ($announListMode < 1){ $announListMode = 1; }
	$newListMode		= OT::PostInt('newListMode');
		if ($newListMode < 1){ $newListMode = 1; }

	$subWeb404			= OT::PostStr('subWeb404');
		if ($subWeb404 == '-1'){ $subWeb404=OT::PostStr('subWeb404New'); }
	$searchAllowStr		= OT::Post('searchAllowStr');
		if (is_array($searchAllowStr)){ $searchAllowStr = implode(',',$searchAllowStr); }
	$searchListNum		= OT::PostInt('searchListNum');
		if ($searchListNum <= 0){ $searchListNum=10; }
	$searchBadStr		= OT::PostStr('searchBadStr');
	$isMark				= OT::PostInt('isMark');
	$markListNum		= OT::PostInt('markListNum');
		if ($markListNum <= 0){ $markListNum=10; }
	$markBadStr			= OT::PostStr('markBadStr');

	$isMessage			= OT::PostInt('isMessage');
	$messageName		= OT::PostStr('messageName');
	$messageWebKey		= OT::PostStr('messageWebKey');
	$messageWebDesc		= OT::PostStr('messageWebDesc');
	$messageCloseNote	= Adm::FilterEditor(OT::PostStr('messageCloseNote'));
		if (Str::RegExp($messageCloseNote,'html') == ''){ $messageCloseNote='留言板暂时关闭'; }
	$messageNum			= OT::PostInt('messageNum');
		if ($messageNum <= 0){ $messageNum=15; }
	$messageSecond		= OT::PostInt('messageSecond');
	$messageAudit		= OT::PostInt('messageAudit');
	$messageOnly		= OT::PostInt('messageOnly');
	$messageMaxLen		= OT::PostInt('messageMaxLen');
	$messageAdminName	= OT::PostStr('messageAdminName');
	$messageBadWord		= OT::PostStr('messageBadWord');
	$messageRndMd5		= OT::PostStr('messageRndMd5');
	$messageVoteNum		= OT::PostInt('messageVoteNum');
	$messageHotNum		= OT::PostInt('messageHotNum');
	$messageEvent		= OT::Post('messageEvent');
		if (is_array($messageEvent)){ $messageEvent = implode(',',$messageEvent); }
	$event				= OT::Post('event');
		if (is_array($event)){ $event = implode(',',$event); }

	$sign				= OT::PostStr('sign');
	$authState			= OT::PostStr('authState');

	if ($backURL=='' || ($sign=='' && $authState!='false') ){
		JS::AlertBackEnd('表单内容接收不全');
	}

	$record=array();
	$record['TS_skinName']			= $skinName;
	$record['TS_skinColor']			= $skinColor;
	$record['TS_skinPopup']			= $skinPopup;
	$record['TS_redTimeDay']		= $redTimeDay;
	$record['TS_rankUserMode']		= $rankUserMode;

	$record['TS_topLogoMode']		= $topLogoMode;
	$record['TS_navWidthMode']		= $navWidthMode;
	$record['TS_jieriImg']			= $jieriImg;
	$record['TS_jieriHeight']		= $jieriHeight;
	$record['TS_logo']				= $logo;
	$record['TS_fullLogo']			= $fullLogo;
	$record['TS_logoExt']			= $logoExt;
	$record['TS_logoW']				= $logoW;
	$record['TS_logoH']				= $logoH;
	$record['TS_isRss']				= $isRss;

	$record['TS_TCP']				= $TCP;
	$record['TS_beianName']			= $beianName;
	$record['TS_beianUrl']			= $beianUrl;
	$record['TS_copyright']			= $copyright;
	$record['TS_isStati']			= $isStati;
	$record['TS_statiCode']			= $statiCode;

	$record['TS_homeAboutID']		= $homeAboutID;
	$record['TS_homeAboutMoreID']	= $homeAboutMoreID;
	$record['TS_homeContactID']		= $homeContactID;
	$record['TS_homeContactMoreID']	= $homeContactMoreID;
	$record['TS_homeNewsID']		= $homeNewsID;
	$record['TS_homeNewsNum']		= $homeNewsNum;
	$record['TS_homeProID']			= $homeProID;
	$record['TS_homeProNum']		= $homeProNum;
	$record['TS_homeInfoTypeID']	= $homeInfoTypeID;

	$record['TS_navMode']			= $navMode;
	$record['TS_navCode']			= $navCode;
	$record['TS_navNum']			= $navNum;
	$record['TS_navPadd']			= $navPadd;
	$record['TS_navSubWidth']		= $navSubWidth;

	$record['TS_homeItemMode']		= $homeItemMode;
	$record['TS_marInfoNum']		= $marInfoNum;
	$record['TS_isHomeFlash']		= $isHomeFlash;
	$record['TS_homeFlashMode']		= $homeFlashMode;
	$record['TS_isHomeFlashTheme']	= $isHomeFlashTheme;
	$record['TS_homeFlashNum']		= $homeFlashNum;

	$record['TS_isHomeAnnoun']		= $isHomeAnnoun;
	$record['TS_homeAnnounName']	= $homeAnnounName;
	$record['TS_homeAnnounNum']		= $homeAnnounNum;
	$record['TS_homeAnnounListNum']	= $homeAnnounListNum;
	$record['TS_isHomeNew']			= $isHomeNew;
	$record['TS_homeNewIsType']		= $homeNewIsType;
	$record['TS_homeNewIsDate']		= $homeNewIsDate;
	$record['TS_homeNewTopMode']	= $homeNewTopMode;
	$record['TS_homeNewTopNum']		= $homeNewTopNum;
	$record['TS_homeNewNum']		= $homeNewNum;
	$record['TS_homeNewListNum']	= $homeNewListNum;
	$record['TS_homeNewMoreNum']	= $homeNewMoreNum;
	$record['TS_homeNewBoxH']		= $homeNewBoxH;
	$record['TS_isHomeRecom']		= $isHomeRecom;
	$record['TS_homeRecomImgNum']	= $homeRecomImgNum;
	$record['TS_homeRecomNum']		= $homeRecomNum;
	$record['TS_homeRecomBoxH']		= $homeRecomBoxH;
	$record['TS_isHomeHot']			= $isHomeHot;
	$record['TS_homeHotName']		= $homeHotName;
	$record['TS_homeHotSort']		= $homeHotSort;
	$record['TS_homeHotIsDate']		= $homeHotIsDate;
	$record['TS_homeHotImgNum']		= $homeHotImgNum;
	$record['TS_homeHotNum']		= $homeHotNum;
	$record['TS_isHomeMarImg']		= $isHomeMarImg;
	$record['TS_homeMarImgMode']	= $homeMarImgMode;
	$record['TS_homeMarImgW']		= $homeMarImgW;
	$record['TS_homeMarImgH']		= $homeMarImgH;
	$record['TS_homeMarImgNum']		= $homeMarImgNum;
	$record['TS_isHomeMessage']		= $isHomeMessage;
	$record['TS_homeMessageName']	= $homeMessageName;
	$record['TS_homeMessageNum']	= $homeMessageNum;
	$record['TS_homeMessageLen']	= $homeMessageLen;
	$record['TS_homeMessageHmode']	= $homeMessageHmode;
	$record['TS_homeMessageH']		= $homeMessageH;
	$record['TS_isHomeReply']		= $isHomeReply;
	$record['TS_homeReplyName']		= $homeReplyName;
	$record['TS_homeReplyNum']		= $homeReplyNum;
	$record['TS_homeReplyLen']		= $homeReplyLen;
	$record['TS_homeReplyHmode']	= $homeReplyHmode;
	$record['TS_homeReplyH']		= $homeReplyH;

	$record['TS_isHomeNewUsers']		= $isHomeNewUsers;
	$record['TS_homeNewUsersName']		= $homeNewUsersName;
	$record['TS_homeNewUsersNum']		= $homeNewUsersNum;
	$record['TS_isHomeRankUsers']		= $isHomeRankUsers;
	$record['TS_homeRankUsersName']		= $homeRankUsersName;
	$record['TS_homeRankUsersType']		= $homeRankUsersType;
	$record['TS_homeRankUsersNum']		= $homeRankUsersNum;

	$record['TS_isHomeQiandao']		= $isHomeQiandao;
	$record['TS_homeQiandaoName']	= $homeQiandaoName;
	$record['TS_isQiandaoRank']		= $isQiandaoRank;
	$record['TS_qiandaoRankName']	= $qiandaoRankName;
	$record['TS_qiandaoRankType']	= $qiandaoRankType;
	$record['TS_qiandaoRankNum']	= $qiandaoRankNum;

	$record['TS_isHomeBbs']			= $isHomeBbs;
	$record['TS_homeBbsName']		= $homeBbsName;
	$record['TS_homeBbsNum']		= $homeBbsNum;
	$record['TS_homeBbsLen']		= $homeBbsLen;
	$record['TS_homeBbsHmode']		= $homeBbsHmode;
	$record['TS_homeBbsH']			= $homeBbsH;

	$record['TS_userListNum']		= $userListNum;
	$record['TS_userListMode']		= $userListMode;
	$record['TS_searchListMode']	= $searchListMode;
	$record['TS_markListMode']		= $markListMode;
	$record['TS_announListMode']	= $announListMode;
	$record['TS_newListMode']		= $newListMode;

	$record['TS_subWeb404']			= $subWeb404;
	$record['TS_searchAllowStr']	= $searchAllowStr;
	$record['TS_searchListNum']		= $searchListNum;
	$record['TS_searchBadStr']		= $searchBadStr;
	$record['TS_isMark']			= $isMark;
	$record['TS_markListNum']		= $markListNum;
	$record['TS_markBadStr']		= $markBadStr;

	$record['TS_isMessage']			= $isMessage;
	$record['TS_messageCloseNote']	= $messageCloseNote;
	$record['TS_messageName']		= $messageName;
	$record['TS_messageWebKey']		= $messageWebKey;
	$record['TS_messageWebDesc']	= $messageWebDesc;
	$record['TS_messageAdminName']	= $messageAdminName;
	$record['TS_messageNum']		= $messageNum;
	$record['TS_messageSecond']		= $messageSecond;
	$record['TS_messageAudit']		= $messageAudit;
	$record['TS_messageOnly']		= $messageOnly;
	$record['TS_messageMaxLen']		= $messageMaxLen;
	$record['TS_messageBadWord']	= $messageBadWord;
	$record['TS_messageRndMd5']		= $messageRndMd5;
	$record['TS_messageVoteNum']	= $messageVoteNum;
	$record['TS_messageHotNum']		= $messageHotNum;
	$record['TS_event']				= $event;

	if ($authState != 'false'){
		if (! AppLogoAdd::Jud()){ $isLogoAdd=0; }
		$record['TS_isTopAd']			= $isTopAd;
		$record['TS_topAdCode']			= $topAdCode;
		$record['TS_searchArea']		= $searchArea;
		$record['TS_subWebLR']			= $subWebLR;
		$record['TS_replyMode']			= $replyMode;
		$record['TS_messageMode']		= $messageMode;
		$record['TS_messageEvent']		= $messageEvent;
		$record['TS_isLogoAdd']			= $isLogoAdd;
		$record['TS_logoListMode']		= $logoListMode;
		$record['TS_logoNote']			= $logoNote;
		$record['TS_logoAreaStr']		= $logoAreaStr;
		$record['TS_isImgBanner']		= $isImgBanner;
		// $record['TS_imgBannerH']		= $imgBannerH;
	}

	$judResult = $DB->UpdateParam('tplSys',$record,'TS_ID=1');
		if (! $judResult){
			JS::AlertBackEnd('保存失败.');
		}

	$fileResultStr = '';
	$Cache = new Cache();
	$isJsResult = $Cache->Js('tplSys');
		if ($isJsResult){
			$fileResultStr .= '\n../cache/js/tplSys.js 生成成功！';
		}else{
			$fileResultStr .= '\n../cache/js/tplSys.js 生成失败，请检查该目录或者文件是否有写入/修改权限！';
		}
	$isCacheResult = $Cache->Php('tplSys');
		if ($isCacheResult){
			$fileResultStr .= '\n../cache/php/tplSys.php 生成成功！';
		}else{
			$fileResultStr .= '\n../cache/php/tplSys.php 生成失败，请检查该目录或者文件是否有写入/修改权限！';
		}
	//if ($subWebLROld != $subWebLR){
	$isSiteCss = AdmArea::MakeSiteCss();
		if ($isSiteCss){
			$fileResultStr .= '\n../cache/web/site.css 生成成功！';
		}else{
			$fileResultStr .= '\n../cache/web/site.css 生成失败，请检查该目录或者文件是否有写入/修改权限！';
		}
	//}

	$DB->query('update '. OT_dbPref .'autoRunSys set ARS_dayDate='. $DB->ForTime('2018-01-01'));
	$Cache->Php('autoRunSys');
	$Cache->Js('autoRunSys');

	Adm::AddLog(array(
		'note'		=> '【'. $dataTypeCN .'】修改成功！',
		));

	JS::AlertHrefEnd('修改成功\n'. $fileResultStr, $backURL);
}



function area(){
	global $DB,$skin,$mudi,$menuFileID,$menuTreeID;

	$backURL		= OT::PostStr('backURL');
	$dataType		= OT::PostRegExpStr('dataType','sql');
	$dataTypeCN		= OT::PostStr('dataTypeCN');
	
	$area		= OT::PostStr('area');
	$record = array();
	switch ($area){
		case 'imgBanner':
			$isImgBanner	= OT::PostInt('isImgBanner');
			$imgBannerH		= OT::PostInt('imgBannerH');
				if ($imgBannerH <= 0){ $imgBannerH = 360; }
			$record['TS_isImgBanner']	= $isImgBanner;
			$record['TS_imgBannerH']	= $imgBannerH;
			break;

		default :
			JS::AlertEnd('area参数不对');
			break;
	}

	$judResult = $DB->UpdateParam('tplSys',$record,'TS_ID=1');
		if (! $judResult){
			JS::AlertEnd('保存失败.');
		}

	$fileResultStr = '';
	$Cache = new Cache();
	/* $isJsResult = $Cache->Js('tplSys');
		if ($isJsResult){
			$fileResultStr .= '\n../cache/js/tplSys.js 生成成功！';
		}else{
			$fileResultStr .= '\n../cache/js/tplSys.js 生成失败，请检查该目录或者文件是否有写入/修改权限！';
		} */
	$isCacheResult = $Cache->Php('tplSys');
		if ($isCacheResult){
			$fileResultStr .= '\n../cache/php/tplSys.php 生成成功！';
		}else{
			$fileResultStr .= '\n../cache/php/tplSys.php 生成失败，请检查该目录或者文件是否有写入/修改权限！';
		}

	Adm::AddLog(array(
		'note'		=> '【区域模板参数设置】修改成功！',
		));

	JS::AlertEnd('修改成功\n'. $fileResultStr);
}

?>
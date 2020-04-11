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



switch ($mudi){
	case 'deal':
		$menuFileID = 194;
		$MB->IsSecMenuRight('alertBack',$menuFileID,$dataType);
		deal();
		break;

	case 'createTab':
		CreateTab();
		break;

	case 'clear':
		ClearDeal();
		break;

	default:
		die('err');
}

$MB->Close();
$DB->Close();





// 添加、修改
function deal(){
	global $DB,$MB,$mudi,$menuFileID,$menuTreeID;

	$backURL			= OT::PostStr('backURL');
	$dataType			= OT::PostRegExpStr('dataType','sql');
	$dataTypeCN			= OT::PostRegExpStr('dataTypeCN','sql');

	$addition			= OT::Post('addition');
		if (is_array($addition)){ $addition = implode(',',$addition); }
	$defIsAudit			= OT::PostInt('defIsAudit');
	$defIsNew			= OT::PostInt('defIsNew');
	$defIsHomeThumb		= OT::PostInt('defIsHomeThumb');
	$defIsThumb			= OT::PostInt('defIsThumb');
	$defIsImg			= OT::PostInt('defIsImg');
	$defIsFlash			= OT::PostInt('defIsFlash');
	$defIsMarquee		= OT::PostInt('defIsMarquee');
	$defIsRecom			= OT::PostInt('defIsRecom');
	$defIsTop			= OT::PostInt('defIsTop');
	$defMarkNews		= OT::PostInt('defMarkNews');
	$defVoteMode		= OT::PostInt('defVoteMode');
	$defVoteStr			= OT::PostStr('defVoteStr');
	$defIsReply			= OT::PostInt('defIsReply');
	$defTopicID			= OT::PostInt('defTopicID');
	$defTopAddiID		= OT::PostInt('defTopAddiID');
	$defAddiID			= OT::PostInt('defAddiID');
	$defIsCheckUser		= OT::PostInt('defIsCheckUser');
	$defUserGroupList	= OT::PostStr('defUserGroupList');
	$defUserLevel		= OT::PostInt('defUserLevel');
	$defScore1			= OT::PostInt('defScore1');
	$defScore2			= OT::PostInt('defScore2');
	$defScore3			= OT::PostInt('defScore3');
	$defCutScore1		= OT::PostInt('defCutScore1');
	$defCutScore2		= OT::PostInt('defCutScore2');
	$defCutScore3		= OT::PostInt('defCutScore3');
	$defReadNum1		= OT::PostInt('defReadNum1');
	$defReadNum2		= OT::PostInt('defReadNum2');
	$defTemplate		= OT::PostStr('defTemplate');
	$defTemplateWap		= OT::PostStr('defTemplateWap');
	$defIsSitemap		= OT::PostInt('defIsSitemap');
	$defIsXiongzhang	= OT::PostInt('defIsXiongzhang');
	$defBdPing			= OT::PostInt('defBdPing');
	$tabID				= OT::PostInt('tabID');
	$tabMaxNum			= OT::PostInt('tabMaxNum');
		if ($tabMaxNum < 1000 || $tabMaxNum > 50000){ $tabMaxNum = 10000; }
	$tabCheckMin			= OT::PostInt('tabCheckMin');
	$moreArea			= OT::Post('moreArea');
		if (is_array($moreArea)){ $moreArea = implode(',',$moreArea); }
	$prevNextArrow		= OT::PostInt('prevNextArrow');
	$prevAndNext		= OT::PostInt('prevAndNext');
	$isSavePrevNextId	= OT::PostInt('isSavePrevNextId');
	$is360meta			= OT::PostInt('is360meta');
	$isXiongzhang		= OT::PostInt('isXiongzhang');
	$xiongzhangId		= OT::PostStr('xiongzhangId');
	$isContentKey		= OT::PostInt('isContentKey');
	$isTime				= OT::PostInt('isTime');
	$isWriter			= OT::PostInt('isWriter');
	$isSource			= OT::PostInt('isSource');
	$isReadNum			= OT::PostInt('isReadNum');
	$isReplyNum			= OT::PostInt('isReplyNum');
	$isWapQrcode		= OT::PostInt('isWapQrcode');
	$newsVerCodeAnswer	= OT::PostStr('newsVerCodeAnswer');
	$newsVerCodeImg		= OT::PostStr('newsVerCodeImg');
	$newsVerCodeNote	= OT::PostStr('newsVerCodeNote');
	$keyWordNum			= OT::PostInt('keyWordNum');
	$readNum1			= OT::PostInt('readNum1');
		if ($readNum1 < 1){ $readNum1 = 1; }
	$readNum2			= OT::PostInt('readNum2');
		if ($readNum2 < 1){ $readNum2 = 1; }
	$oneReadNum			= OT::PostInt('oneReadNum');
	$isNewsVote			= OT::PostInt('isNewsVote');
	$isMarkNews			= OT::PostInt('isMarkNews');
	$isSaveMarkNewsId	= OT::PostInt('isSaveMarkNewsId');
	$newsVoteSecond		= OT::PostInt('newsVoteSecond');
	$isNewsReply		= OT::PostInt('isNewsReply');
	$newsReplyMode		= OT::PostInt('newsReplyMode');
	$changyanId1		= OT::PostStr('changyanId1');
	$changyanId2		= OT::PostStr('changyanId2');
	$changyanIsDashang	= OT::PostInt('changyanIsDashang');
	$changyanIsFace		= OT::PostInt('changyanIsFace');
	$newsReplyNum		= OT::PostStr('newsReplyNum');
		if ($newsReplyNum<1){ $newsReplyNum=20; }
	$newsReplySecond	= OT::PostInt('newsReplySecond');
	$newsReplyAudit		= OT::PostInt('newsReplyAudit');
	$newsReplyMaxLen	= OT::PostInt('newsReplyMaxLen');
	$newsReplyName		= OT::PostStr('newsReplyName');
	$isShareNews		= OT::PostInt('isShareNews');
	$shareNewsCode		= OT::PostStr('shareNewsCode');
	$newsVoteCode		= OT::PostStr('newsVoteCode');
	$isWumii			= OT::PostInt('isWumii');
	$isNoCollPage		= OT::PostInt('isNoCollPage');
	$isGoPage			= OT::PostInt('isGoPage');
	$isInte				= OT::PostInt('isInte');
	$inteContent		= OT::PostStr('inteContent');
	$inteMode			= OT::PostInt('inteMode');
	$eventStr			= OT::PostStr('eventStr');
	$copyAddiStr		= OT::PostRStr('copyAddiStr');
	$fileStyle			= OT::PostStr('fileStyle');

	$sign				= OT::PostStr('sign');
	$authState			= OT::PostStr('authState');

	if ($backURL=='' || ($sign=='' && $authState!='false') ){
		JS::AlertBackEnd('表单内容接收不全');
	}

	$record=array();
	$record['IS_addition']			= $addition;
	$record['IS_defIsAudit']		= $defIsAudit;
	$record['IS_defIsNew']			= $defIsNew;
	$record['IS_defIsHomeThumb']	= $defIsHomeThumb;
	$record['IS_defIsThumb']		= $defIsThumb;
	$record['IS_defIsImg']			= $defIsImg;
	$record['IS_defIsFlash']		= $defIsFlash;
	$record['IS_defIsMarquee']		= $defIsMarquee;
	$record['IS_defIsRecom']		= $defIsRecom;
	$record['IS_defIsTop']			= $defIsTop;
	$record['IS_defMarkNews']		= $defMarkNews;
	$record['IS_defVoteMode']		= $defVoteMode;
	$record['IS_defVoteStr']		= $defVoteStr;
	$record['IS_defIsReply']		= $defIsReply;
	$record['IS_defTopAddiID']		= $defTopAddiID;
	$record['IS_defAddiID']			= $defAddiID;
	$record['IS_defIsCheckUser']	= $defIsCheckUser;
	$record['IS_defUserGroupList']	= $defUserGroupList;
	$record['IS_defUserLevel']		= $defUserLevel;
	$record['IS_defScore1']			= $defScore1;
	$record['IS_defScore2']			= $defScore2;
	$record['IS_defScore3']			= $defScore3;
	$record['IS_defCutScore1']		= $defCutScore1;
	$record['IS_defCutScore2']		= $defCutScore2;
	$record['IS_defCutScore3']		= $defCutScore3;
	$record['IS_defReadNum1']		= $defReadNum1;
	$record['IS_defReadNum2']		= $defReadNum2;
	$record['IS_defTopicID']		= $defTopicID;
	$record['IS_defIsSitemap']		= $defIsSitemap;
	$record['IS_defIsXiongzhang']	= $defIsXiongzhang;
	$record['IS_defBdPing']			= $defBdPing;
	$record['IS_tabID']				= $tabID;
	$record['IS_tabMaxNum']			= $tabMaxNum;
	$record['IS_tabCheckMin']		= $tabCheckMin;
	$record['IS_moreArea']			= $moreArea;
	$record['IS_prevAndNext']		= $prevAndNext;
	$record['IS_is360meta']			= $is360meta;
	$record['IS_isXiongzhang']		= $isXiongzhang;
	$record['IS_xiongzhangId']		= $xiongzhangId;
	$record['IS_isContentKey']		= $isContentKey;
	$record['IS_isTime']			= $isTime;
	$record['IS_isWriter']			= $isWriter;
	$record['IS_isSource']			= $isSource;
	$record['IS_isReadNum']			= $isReadNum;
	$record['IS_isReplyNum']		= $isReplyNum;
	$record['IS_isWapQrcode']		= $isWapQrcode;
	$record['IS_isMarkNews']		= $isMarkNews;
	$record['IS_isSaveMarkNewsId']	= $isSaveMarkNewsId;
	$record['IS_readNum1']			= $readNum1;
	$record['IS_readNum2']			= $readNum2;
	$record['IS_oneReadNum']		= $oneReadNum;
	$record['IS_isNewsVote']		= $isNewsVote;
	$record['IS_newsVoteSecond']	= $newsVoteSecond;
	$record['IS_isNewsReply']		= $isNewsReply;
	$record['IS_newsReplyName']		= $newsReplyName;
	$record['IS_newsReplyNum']		= $newsReplyNum;
	$record['IS_newsReplySecond']	= $newsReplySecond;
	$record['IS_newsReplyAudit']	= $newsReplyAudit;
	$record['IS_newsReplyMaxLen']	= $newsReplyMaxLen;
	$record['IS_isShareNews']		= $isShareNews;
	$record['IS_shareNewsCode']		= $shareNewsCode;
	$record['IS_newsVoteCode']		= $newsVoteCode;
	$record['IS_fileStyle']			= $fileStyle;
	if (AppNewsVerCode::Jud()){
		$record['IS_newsVerCodeAnswer']	= $newsVerCodeAnswer;
		$record['IS_newsVerCodeImg']	= $newsVerCodeImg;
		$record['IS_newsVerCodeNote']	= $newsVerCodeNote;
	}
	if (AppBase::Jud()){
		$record['IS_defTemplate']		= $defTemplate;
	}
	if (AppWap::Jud()){
		$record['IS_defTemplateWap']	= $defTemplateWap;
	}

	if ($authState != 'false'){
		$record['IS_keyWordNum']		= $keyWordNum;
		$record['IS_prevNextArrow']		= $prevNextArrow;
		$record['IS_isSavePrevNextId']	= $isSavePrevNextId;
		$record['IS_newsReplyMode']		= $newsReplyMode;
		$record['IS_changyanId1']		= $changyanId1;
		$record['IS_changyanId2']		= $changyanId2;
		$record['IS_changyanIsDashang']	= $changyanIsDashang;
		$record['IS_changyanIsFace']	= $changyanIsFace;
		$record['IS_eventStr']			= $eventStr;
		$record['IS_copyAddiStr']		= $copyAddiStr;
		$record['IS_isNoCollPage']		= $isNoCollPage;
		$record['IS_isGoPage']			= $isGoPage;
	}

	$judResult = $DB->UpdateParam('infoSys',$record,'IS_ID=1');


	$fileResultStr = '';
	$Cache = new Cache();
	$isJsResult =$Cache->Js('infoSys');
		if ($isJsResult){
			$fileResultStr .= '\n../cache/infoSys.js 生成成功！';
		}else{
			$fileResultStr .= '\n../cache/infoSys.js 生成失败，请检查该目录或者文件是否有写入/修改权限！';
		}
	$isCacheResult = $Cache->Php('infoSys');
		if ($isCacheResult){
			$fileResultStr .= '\n../cache/infoSys.php 生成成功！';
		}else{
			$fileResultStr .= '\n../cache/infoSys.php 生成失败，请检查该目录或者文件是否有写入/修改权限！';
		}

	$DB->query('update '. OT_dbPref .'autoRunSys set ARS_dayDate='. $DB->ForTime('2018-01-01'));
	$Cache->Php('autoRunSys');
	$Cache->Js('autoRunSys');

	Adm::AddLog(array(
		'note'		=> '【'. $dataTypeCN .'】修改成功！',
		));

	JS::AlertHrefEnd('修改成功\n'. $fileResultStr, $backURL);
}



function CreateTab(){
	global $DB;

	$backURL		= OT::GetStr('backURL');
	$tabID			= OT::GetInt('tabID');

	$tabNum = $DB->GetOne('select IS_tabNum from '. OT_dbPref .'infoSys');
	$tabName = 'infoContent'. $tabID;
	if ($DB->IsTab($tabName)){
		if ($tabID > $tabNum){
			$DB->UpdateParam('infoSys', array('IS_tabNum'=>$tabID), 'IS_ID=1');
		}
		JS::AlertBackEnd('该表（'. $tabName .'）已存在不需要再创建。');
	}

	if (OT_Database == 'sqlite'){
		$DB->query('CREATE TABLE "OT_'. $tabName .'" (
					"IC_ID"  INTEGER(11) NOT NULL,
					"IC_content"  TEXT,
					PRIMARY KEY ("IC_ID")
					);');
	}else{
		$DB->query('CREATE TABLE '. OT_dbPref . $tabName .' (
				  IC_ID int(11) NOT NULL DEFAULT "0",
				  IC_content longtext,
				  PRIMARY KEY (IC_ID)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8;');
	}

	if ($tabID > $tabNum){
		$DB->UpdateParam('infoSys', array('IS_tabNum'=>$tabID), 'IS_ID=1');
	}

	JS::AlertBackEnd('创建表 '. $tabName .' 完成。', $backURL);
}



function ClearDeal(){
	global $DB;

	$mudi2 = OT::GetStr('mudi2');

	switch ($mudi2){
		case 'prevNextId':
			$DB->query('update '. OT_dbPref .'info set IF_prevNewsId=0,IF_nextNewsId=0');
			JS::Alert('清空自动保存上/下一篇的信息完成');
			break;
	
		case 'markNewsId':
			$DB->query("update ". OT_dbPref ."info set IF_themeKeyIdStr=''");
			JS::Alert('清空自动保存相关文章信息完成');
			break;
	}

}
?>
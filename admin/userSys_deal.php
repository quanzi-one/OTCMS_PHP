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
$MB->Open('','login',10);


switch($mudi){
	case 'infoSet':
		$menuFileID = 178;
		$MB->IsSecMenuRight('alertBack',$menuFileID,$dataType);
		InfoSet();
		break;

	default:
		die('err');
}

$MB->Close();
$DB->Close();





// 基本设置
function InfoSet(){
	global $DB,$mudi,$menuFileID,$menuTreeID;

	$backURL		= OT::PostStr('backURL');
	$dataType		= OT::PostRegExpStr('dataType','sql');
	$dataTypeCN		= OT::PostRegExpStr('dataTypeCN','sql');

	$isUserSys		= OT::PostInt('isUserSys');
	$isAuthMail		= OT::PostInt('isAuthMail');
	$isAuthPhone	= OT::PostInt('isAuthPhone');
	$isLockMail		= OT::PostInt('isLockMail');
	$isLockPhone	= OT::PostInt('isLockPhone');
	$isMustMail		= OT::PostInt('isMustMail');
	$isMustPhone	= OT::PostInt('isMustPhone');
	$mustMailStr	= str_replace(array('"',"'"),array('“','’'),OT::PostStr('mustMailStr'));
	$mustPhoneStr	= str_replace(array('"',"'"),array('“','’'),OT::PostStr('mustPhoneStr'));
	$isOnlyMail		= OT::PostInt('isOnlyMail');
	$isOnlyPhone	= OT::PostInt('isOnlyPhone');
	$isShiming		= OT::PostInt('isShiming');
	$isPwdEn		= OT::PostInt('isPwdEn');
	$exitMinute		= OT::PostInt('exitMinute');
	$loginKey		= OT::PostStr('loginKey');
	$userFieldStr	= OT::Post('userFieldStr');
		if (is_array($userFieldStr)){ $userFieldStr = implode(',',$userFieldStr); }
	$announ			= Adm::FilterEditor(OT::PostStr('announ'));
	$isReg			= OT::PostInt('isReg');
	$isRegInvite	= OT::PostInt('isRegInvite');
	$isRegApi		= OT::PostInt('isRegApi');
	$apiRecMode		= OT::PostInt('apiRecMode');
	$isRegAudit		= OT::PostInt('isRegAudit');
	$againRegMinute	= OT::PostInt('againRegMinute');
	$regGroupID		= OT::PostInt('regGroupID');
	$regFieldStr	= OT::Post('regFieldStr');
		if (is_array($regFieldStr)){ $regFieldStr = implode(',',$regFieldStr); }
	$regNote		= Adm::FilterEditor(OT::PostStr('regNote'));
	$regBadWord		= OT::PostStr('regBadWord');
		if (substr($regBadWord,-1) == '|'){ $regBadWord = substr($regBadWord,0,-1); }
	$regAnnoun		= Adm::FilterEditor(OT::PostStr('regAnnoun'));
	$regAuthMail	= OT::PostInt('regAuthMail');
	$regAuthPhone	= OT::PostInt('regAuthPhone');
	$isLogin		= OT::PostInt('isLogin');
	$isLoginExp		= OT::PostInt('isLoginExp');
	$isLoginUser	= OT::PostInt('isLoginUser');
	$isLoginMail	= OT::PostInt('isLoginMail');
	$isLoginPhone	= OT::PostInt('isLoginPhone');
		if ($isLoginUser + $isLoginMail + $isLoginPhone == 0){
			$isLoginUser = 1;
		}
	$loginAnnoun	= Adm::FilterEditor(OT::PostStr('loginAnnoun'));
	$isMissPwd		= OT::PostInt('isMissPwd');
	$isMissPwdUser	= OT::PostInt('isMissPwdUser');
	$isMissPwdMail	= OT::PostInt('isMissPwdMail');
	$isMissPwdPhone	= OT::PostInt('isMissPwdPhone');
	$isNews			= OT::PostInt('isNews');
	$isNewsAdd		= OT::PostInt('isNewsAdd');
	$isNewsAudit	= OT::PostInt('isNewsAudit');
	$isNewsRev		= OT::PostInt('isNewsRev');
	$isNewsRevAudit	= OT::PostInt('isNewsRevAudit');
	$isNewsDel		= OT::PostInt('isNewsDel');
	$isRevSource	= OT::PostInt('isRevSource');
	$isRevWriter	= OT::PostInt('isRevWriter');
	$newsSource		= OT::PostStr('newsSource');
	$newsWriter		= OT::PostStr('newsWriter');
	$isNewsUpImg	= OT::PostInt('isNewsUpImg');
	$newsUpImgSize	= OT::PostInt('newsUpImgSize');
	$newsUpImgOss	= OT::PostStr('newsUpImgOss');
		if (strlen($newsUpImgOss) == 0){ $newsUpImgOss = 'web'; }
	$newsUpImgOri	= OT::PostInt('newsUpImgOri');
	$isNewsUpFile	= OT::PostInt('isNewsUpFile');
	$newsUpFileSize	= OT::PostInt('newsUpFileSize');
	$newsUpFileOss	= OT::PostStr('newsUpFileOss');
		if (strlen($newsUpFileOss) == 0){ $newsUpFileOss = 'web'; }
	$isNewsReadRight= OT::PostInt('isNewsReadRight');
	$newsAddiStr	= OT::Post('newsAddiStr');
		if (is_array($newsAddiStr)){ $newsAddiStr = implode(',',$newsAddiStr); }
	$isMarkNews		= OT::PostInt('isMarkNews');
	$isNewsVote		= OT::PostInt('isNewsVote');
	$isReply		= OT::PostInt('isReply');
	$topicID		= OT::PostInt('topicID');
	$topAddiID		= OT::PostInt('topAddiID');
	$addiID			= OT::PostInt('addiID');
	$isCheckUser	= OT::PostInt('isCheckUser');
	$addNewsAnnoun	= Adm::FilterEditor(OT::PostStr('addNewsAnnoun'));
	$isGainScore	= OT::PostInt('isGainScore');
	$gainScore1Rate	= OT::PostInt('gainScore1Rate');
		if ($gainScore1Rate > 100){ $gainScore1Rate = 100; }
	$gainScore2Rate	= OT::PostInt('gainScore2Rate');
		if ($gainScore2Rate > 100){ $gainScore2Rate = 100; }
	$gainScore3Rate	= OT::PostInt('gainScore3Rate');
		if ($gainScore3Rate > 100){ $gainScore3Rate = 100; }

	$isScore1		= OT::PostInt('isScore1');
	$isScore2		= OT::PostInt('isScore2');
	$isScore3		= OT::PostInt('isScore3');
	$score1Name		= OT::PostStr('score1Name');
	$score2Name		= OT::PostStr('score2Name');
	$score3Name		= OT::PostStr('score3Name');
	$score1Unit		= OT::PostStr('score1Unit');
	$score2Unit		= OT::PostStr('score2Unit');
	$score3Unit		= OT::PostStr('score3Unit');
	$event			= OT::Post('event');
		if (is_array($event)){ $event = implode(',',$event); }
	$newsBadMode	= OT::PostInt('newsBadMode');
	$newsBadWord	= OT::PostStr('newsBadWord');
	$tempSaveMin	= OT::PostInt('tempSaveMin');
		if ($tempSaveMin < 10){ $tempSaveMin = 10; }
	$newsEvent			= OT::Post('newsEvent');
		if (is_array($newsEvent)){ $newsEvent = implode(',',$newsEvent); }
	$loginUserRank	= OT::PostInt('loginUserRank');
	$loginMailRank	= OT::PostInt('loginMailRank');
	$loginPhoneRank	= OT::PostInt('loginPhoneRank');
	$loginMailMode	= OT::PostInt('loginMailMode');
	$loginPhoneMode	= OT::PostInt('loginPhoneMode');

	$sign			= OT::PostStr('sign');
	$authState		= OT::PostStr('authState');

	if ($backURL == '' || ($sign=='' && $authState!='false') ){
		JS::AlertBackEnd('表单内容接收不全');
	}

	$record = array();
	$record['US_isUserSys']			= $isUserSys;
	$record['US_isOnlyMail']		= $isOnlyMail;
	$record['US_isOnlyPhone']		= $isOnlyPhone;
	$record['US_isShiming']			= $isShiming;
	$record['US_isPwdEn']			= $isPwdEn;
	$record['US_exitMinute']		= $exitMinute;
	$record['US_loginKey']			= $loginKey;
	$record['US_announ']			= $announ;
	$record['US_userFieldStr']		= $userFieldStr;
	$record['US_isReg']				= $isReg;
	$record['US_isRegAudit']		= $isRegAudit;
	$record['US_againRegMinute']	= $againRegMinute;
	$record['US_regGroupID']		= $regGroupID;
	$record['US_regFieldStr']		= $regFieldStr;
	$record['US_regNote']			= $regNote;
	$record['US_regBadWord']		= $regBadWord;
	$record['US_regAnnoun']			= $regAnnoun;
	$record['US_isLogin']			= $isLogin;
	$record['US_isLoginExp']		= $isLoginExp;
	$record['US_isLoginUser']		= $isLoginUser;
	$record['US_loginAnnoun']		= $loginAnnoun;
	$record['US_isMissPwd']			= $isMissPwd;
	$record['US_isMissPwdUser']		= $isMissPwdUser;
	$record['US_isMissPwdMail']		= $isMissPwdMail;
	$record['US_isMissPwdPhone']	= $isMissPwdPhone;
	$record['US_isNews']			= $isNews;
	$record['US_isNewsAdd']			= $isNewsAdd;
	$record['US_isNewsAudit']		= $isNewsAudit;
	$record['US_isNewsRev']			= $isNewsRev;
	$record['US_isNewsRevAudit']	= $isNewsRevAudit;
	$record['US_isNewsDel']			= $isNewsDel;
	$record['US_isRevSource']		= $isRevSource;
	$record['US_isRevWriter']		= $isRevWriter;
	$record['US_newsSource']		= $newsSource;
	$record['US_newsWriter']		= $newsWriter;
	$record['US_isNewsReadRight']	= $isNewsReadRight;
	$record['US_newsAddiStr']		= $newsAddiStr;
	$record['US_isMarkNews']		= $isMarkNews;
	$record['US_isNewsVote']		= $isNewsVote;
	$record['US_isReply']			= $isReply;
	$record['US_topAddiID']			= $topAddiID;
	$record['US_addiID']			= $addiID;
	$record['US_isCheckUser']		= $isCheckUser;
	$record['US_addNewsAnnoun']		= $addNewsAnnoun;
//	$record['US_isScore1']			= $isScore1;
	$record['US_isScore1']			= 1;
	$record['US_isScore2']			= $isScore2;
	$record['US_isScore3']			= $isScore3;
	$record['US_score1Name']		= $score1Name;
	$record['US_score2Name']		= $score2Name;
	$record['US_score3Name']		= $score3Name;
	$record['US_score1Unit']		= $score1Unit;
	$record['US_score2Unit']		= $score2Unit;
	$record['US_score3Unit']		= $score3Unit;
	$record['US_event']				= $event;
	$record['US_newsBadMode']		= $newsBadMode;
	$record['US_newsBadWord']		= $newsBadWord;
	$record['US_tempSaveMin']		= $tempSaveMin;
	$record['US_newsEvent']			= $newsEvent;
	$record['US_loginUserRank']		= $loginUserRank;
	if (AppMail::Jud()){
		$record['US_isAuthMail']	= $isAuthMail;
		$record['US_regAuthMail']	= $regAuthMail;
		$record['US_isLockMail']	= $isLockMail;
		$record['US_isMustMail']	= $isMustMail;
		$record['US_mustMailStr']	= $mustMailStr;
	}
	if (AppPhone::Jud()){
		$record['US_isAuthPhone']	= $isAuthPhone;
		$record['US_regAuthPhone']	= $regAuthPhone;
		$record['US_isLockPhone']	= $isLockPhone;
		$record['US_isMustPhone']	= $isMustPhone;
		$record['US_mustPhoneStr']	= $mustPhoneStr;
	}
	if (AppLoginMail::Jud()){
		$record['US_isLoginMail']	= $isLoginMail;
		$record['US_loginMailRank']	= $loginMailRank;
		$record['US_loginMailMode']	= $loginMailMode;
	}
	if (AppLoginPhone::Jud()){
		$record['US_isLoginPhone']	= $isLoginPhone;
		$record['US_loginPhoneRank']= $loginPhoneRank;
		$record['US_loginPhoneMode']= $loginPhoneMode;
	}
	if (AppNewsGain::Jud()){
		$record['US_isGainScore']		= $isGainScore;
		$record['US_gainScore1Rate']	= $gainScore1Rate;
		$record['US_gainScore2Rate']	= $gainScore2Rate;
		$record['US_gainScore3Rate']	= $gainScore3Rate;
	}
	if (AppTopic::Jud()){
		$record['US_topicID']			= $topicID;
	}

	if ($authState != 'false'){
		$record['US_isRegInvite']		= $isRegInvite;
		$record['US_isRegApi']			= $isRegApi;
		$record['US_apiRecMode']		= $apiRecMode;
		$record['US_isNewsUpImg']		= $isNewsUpImg;
		$record['US_newsUpImgSize']		= $newsUpImgSize;
		$record['US_newsUpImgOss']		= $newsUpImgOss;
		$record['US_newsUpImgOri']		= $newsUpImgOri;
		$record['US_isNewsUpFile']		= $isNewsUpFile;
		$record['US_newsUpFileSize']	= $newsUpFileSize;
		$record['US_newsUpFileOss']		= $newsUpFileOss;
	}

	$fileResultStr = '';
	$judResult = $DB->UpdateParam('userSys',$record,'US_ID=1');
		if ($judResult){
			$alertResult = '成功';

			$Cache = new Cache();
			$isJsResult = $Cache->Js('userSys');
				if ($isJsResult){
					$fileResultStr .= '\n../cache/userSys.js 生成成功！';
				}else{
					$fileResultStr .= '\n../cache/userSys.js 生成失败，请检查该目录或者文件是否有写入/修改权限！';
				}
			$isCacheResult = $Cache->Php('userSys');
				if ($isCacheResult){
					$fileResultStr .= '\n../cache/userSys.php 生成成功！';
				}else{
					$fileResultStr .= '\n../cache/userSys.php 生成失败，请检查该目录或者文件是否有写入/修改权限！';
				}

			$DB->query('update '. OT_dbPref .'autoRunSys set ARS_dayDate='. $DB->ForTime('2018-01-01'));
			$Cache->Php('autoRunSys');
			$Cache->Js('autoRunSys');
		}else{
			$alertResult = '失败';
		}

	// 操作日志记录
	Adm::AddLog(array(
		'note'		=> '【'. $dataTypeCN .'】修改'. $alertResult .'！',
		));

	JS::AlertHref('修改'. $alertResult .'\n'. $fileResultStr, $backURL);
}

?>
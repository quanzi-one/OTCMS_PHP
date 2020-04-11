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
		$menuFileID = 525;
		$MB->IsSecMenuRight('alertBack',$menuFileID,$dataType);
		InfoSet();
		break;

	case 'checkFtpConn':
		$menuFileID = 525;
		$MB->IsSecMenuRight('alertBack',$menuFileID,$dataType);
		CheckFtpConn();
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

	$isQiniu		= OT::PostInt('isQiniu');
	$qiniuKey1		= OT::PostStr('qiniuKey1');
	$qiniuKey2		= OT::PostStr('qiniuKey2');
	$qiniuName		= OT::PostStr('qiniuName');
	$qiniuUrl		= strtolower(OT::PostStr('qiniuUrl'));
		if ($qiniuUrl != ''){
			if (! Is::HttpUrl($qiniuUrl)){ $qiniuUrl = GetUrl::HttpHead() . $qiniuUrl; }
			if (substr($qiniuUrl,-1) != '/'){ $qiniuUrl .= '/'; }
		}
	$isUpyun		= OT::PostInt('isUpyun');
	$upyunKey1		= OT::PostStr('upyunKey1');
	$upyunKey2		= OT::PostStr('upyunKey2');
	$upyunName		= OT::PostStr('upyunName');
	$upyunUrl		= strtolower(OT::PostStr('upyunUrl'));
		if ($upyunUrl != ''){
			if (! Is::HttpUrl($upyunUrl)){ $upyunUrl = GetUrl::HttpHead() . $upyunUrl; }
			if (substr($upyunUrl,-1) != '/'){ $upyunUrl .= '/'; }
		}
	$isAliyun		= OT::PostInt('isAliyun');
	$aliyunKey1		= OT::PostStr('aliyunKey1');
	$aliyunKey2		= OT::PostStr('aliyunKey2');
	$aliyunName		= OT::PostStr('aliyunName');
	$aliyunEndPoint	= OT::PostStr('aliyunEndPoint');
	$aliyunUrl		= strtolower(OT::PostStr('aliyunUrl'));
		if ($aliyunUrl != ''){
			if (! Is::HttpUrl($aliyunUrl)){ $aliyunUrl = GetUrl::HttpHead() . $aliyunUrl; }
			if (substr($aliyunUrl,-1) != '/'){ $aliyunUrl .= '/'; }
		}
	$isJingan		= OT::PostInt('isJingan');
	$jinganKey1		= OT::PostStr('jinganKey1');
	$jinganKey2		= OT::PostStr('jinganKey2');
	$jinganKey3		= OT::PostStr('jinganKey3');
	$jinganName		= OT::PostStr('jinganName');
	$jinganUrl		= OT::PostStr('jinganUrl');
		if ($jinganUrl != ''){
			if (! Is::HttpUrl($jinganUrl)){ $jinganUrl = GetUrl::HttpHead() . $jinganUrl; }
			if (substr($jinganUrl,-1) != '/'){ $jinganUrl .= '/'; }
		}
	$videoPcWidth	= OT::PostStr('videoPcWidth');
		if (strlen($videoPcWidth) == 0){ $videoPcWidth = '100%'; }
	$videoPcHeight	= OT::PostStr('videoPcHeight');
		if (strlen($videoPcHeight) == 0){ $videoPcHeight = '400'; }
	$videoWapWidth	= OT::PostStr('videoWapWidth');
		if (strlen($videoWapWidth) == 0){ $videoWapWidth = '100%'; }
	$videoWapHeight	= OT::PostStr('videoWapHeight');
		if (strlen($videoWapHeight) == 0){ $videoWapHeight = '250'; }
	$audioPcWidth	= OT::PostStr('audioPcWidth');
		if (strlen($audioPcWidth) == 0){ $audioPcWidth = '500'; }
	$audioPcHeight	= OT::PostStr('audioPcHeight');
		if (strlen($audioPcHeight) == 0){ $audioPcHeight = '32'; }
	$audioWapWidth	= OT::PostStr('audioWapWidth');
		if (strlen($audioWapWidth) == 0){ $audioWapWidth = '100%'; }
	$audioWapHeight	= OT::PostStr('audioWapHeight');
		if (strlen($audioWapHeight) == 0){ $audioWapHeight = '32'; }
	$recomAnnoun	= Adm::FilterEditor(OT::PostStr('recomAnnoun'));
	$recomLinkStr	= OT::PostStr('recomLinkStr');
	$recomImgStr	= OT::PostStr('recomImgStr');
	$recomFontStr	= OT::PostStr('recomFontStr');
	$recomNoteStr	= Adm::FilterEditor(OT::PostStr('recomNoteStr'));
	$recomArea		= OT::Post('recomArea');
		if (is_array($recomArea)){ $recomArea = implode(',',$recomArea); }
	$isGain			= OT::PostInt('isGain');
	$gainRecomMoney	= OT::PostFloat('gainRecomMoney');
	$gainNewsMoney	= OT::PostFloat('gainNewsMoney');
	$gainUserMoney	= OT::PostFloat('gainUserMoney');
	$gainMoney		= OT::PostFloat('gainMoney');
	$gainDay		= OT::PostInt('gainDay');
	$isGainScore		= OT::PostInt('isGainScore');
	$gainScore1Rate		= OT::PostInt('gainScore1Rate');
	$gainScore2Rate		= OT::PostInt('gainScore2Rate');
	$gainScore3Rate		= OT::PostInt('gainScore3Rate');
	$userState1Money	= OT::PostFloat('userState1Money');
	$userState1Score1	= OT::PostInt('userState1Score1');
	$userState1Score2	= OT::PostInt('userState1Score2');
	$userState1Score3	= OT::PostInt('userState1Score3');
	$isFtp				= OT::PostInt('isFtp');
	$ftpIp				= OT::PostStr('ftpIp');
	$ftpPort			= OT::PostInt('ftpPort');
		if ($ftpPort == 0){ $ftpPort = 21; }
	$ftpUser			= OT::PostStr('ftpUser');
	$ftpPwd				= OT::PostStr('ftpPwd');
	$ftpDefDir			= OT::PostStr('ftpDefDir');
	$ftpUrl				= OT::PostStr('ftpUrl');
		if ($ftpUrl != ''){
			if (! Is::HttpUrl($ftpUrl)){ $ftpUrl = GetUrl::HttpHead() . $ftpUrl; }
			if (substr($ftpUrl,-1) != '/'){ $ftpUrl .= '/'; }
		}

	if ($backURL == ''){
		JS::AlertBackEnd('表单内容接收不全');
	}

	$record = array();
	if (AppOssQiniu::Jud()){
		$record['AS_isQiniu']			= $isQiniu;
		$record['AS_qiniuKey1']			= $qiniuKey1;
		$record['AS_qiniuKey2']			= $qiniuKey2;
		$record['AS_qiniuName']			= $qiniuName;
		$record['AS_qiniuUrl']			= $qiniuUrl;
	}
	if (AppOssUpyun::Jud()){
		$record['AS_isUpyun']			= $isUpyun;
		$record['AS_upyunKey1']			= $upyunKey1;
		$record['AS_upyunKey2']			= $upyunKey2;
		$record['AS_upyunName']			= $upyunName;
		$record['AS_upyunUrl']			= $upyunUrl;
	}
	if (AppOssAliyun::Jud()){
		$record['AS_isAliyun']			= $isAliyun;
		$record['AS_aliyunKey1']		= $aliyunKey1;
		$record['AS_aliyunKey2']		= $aliyunKey2;
		$record['AS_aliyunName']		= $aliyunName;
		$record['AS_aliyunEndPoint']	= $aliyunEndPoint;
		$record['AS_aliyunUrl']			= $aliyunUrl;
	}
	if (AppOssJingan::Jud()){
		$record['AS_isJingan']			= $isJingan;
		$record['AS_jinganKey1']		= $jinganKey1;
		$record['AS_jinganKey2']		= $jinganKey2;
		$record['AS_jinganKey3']		= $jinganKey3;
		$record['AS_jinganName']		= $jinganName;
		$record['AS_jinganUrl']			= $jinganUrl;
	}
	if (AppVideo::Jud()){
		$record['AS_videoPcWidth']		= $videoPcWidth;
		$record['AS_videoPcHeight']		= $videoPcHeight;
		$record['AS_videoWapWidth']		= $videoWapWidth;
		$record['AS_videoWapHeight']	= $videoWapHeight;
		$record['AS_audioPcWidth']		= $audioPcWidth;
		$record['AS_audioPcHeight']		= $audioPcHeight;
		$record['AS_audioWapWidth']		= $audioWapWidth;
		$record['AS_audioWapHeight']	= $audioWapHeight;
	}
	if (AppRecom::Jud()){
		$record['AS_recomAnnoun']	= $recomAnnoun;
		$record['AS_recomLinkStr']	= $recomLinkStr;
		$record['AS_recomImgStr']	= $recomImgStr;
		$record['AS_recomFontStr']	= $recomFontStr;
		$record['AS_recomNoteStr']	= $recomNoteStr;
		$record['AS_recomArea']		= $recomArea;
	}
	if (AppGain::Jud()){
		$record['AS_isGain']		= $isGain;
		$record['AS_gainRecomMoney']= $gainRecomMoney;
		$record['AS_gainNewsMoney']	= $gainNewsMoney;
		$record['AS_gainUserMoney']	= $gainUserMoney;
		$record['AS_gainMoney']		= $gainMoney;
		$record['AS_gainDay']		= $gainDay;
	}
	if (AppNewsGain::Jud()){
		$record['AS_isGainScore']		= $isGainScore;
		$record['AS_gainScore1Rate']	= $gainScore1Rate;
		$record['AS_gainScore2Rate']	= $gainScore2Rate;
		$record['AS_gainScore3Rate']	= $gainScore3Rate;
	}
	if (AppUserState1::Jud()){
		$record['AS_userState1Money']	= $userState1Money;
		$record['AS_userState1Score1']	= $userState1Score1;
		$record['AS_userState1Score2']	= $userState1Score2;
		$record['AS_userState1Score3']	= $userState1Score3;
	}
	if (AppOssFtp::Jud()){
		$record['AS_isFtp']				= $isFtp;
		$record['AS_ftpIp']				= $ftpIp;
		$record['AS_ftpPort']			= $ftpPort;
		$record['AS_ftpUser']			= $ftpUser;
		$record['AS_ftpPwd']			= $ftpPwd;
		$record['AS_ftpDefDir']			= $ftpDefDir;
		$record['AS_ftpUrl']			= $ftpUrl;
	}

	$fileResultStr = '';
	$judResult = $DB->UpdateParam('appSys',$record,'AS_ID=1');
		if ($judResult){
			$alertResult = '成功';

			$Cache = new Cache();
			$isJsResult = $Cache->Js('appSys');
				if ($isJsResult){
					$fileResultStr .= '\n../cache/appSys.js 生成成功！';
				}else{
					$fileResultStr .= '\n../cache/appSys.js 生成失败，请检查该目录或者文件是否有写入/修改权限！';
				}
			$isCacheResult = $Cache->Php('appSys');
				if ($isCacheResult){
					$fileResultStr .= '\n../cache/appSys.php 生成成功！';
				}else{
					$fileResultStr .= '\n../cache/appSys.php 生成失败，请检查该目录或者文件是否有写入/修改权限！';
				}

			/* $DB->query('update '. OT_dbPref .'autoRunSys set ARS_dayDate='. $DB->ForTime('2018-01-01'));
			$Cache->Php('autoRunSys');
			$Cache->Js('autoRunSys'); */
		}else{
			$alertResult = '失败';
		}

	// 操作日志记录
	Adm::AddLog(array(
		'note'		=> '【'. $dataTypeCN .'】修改'. $alertResult .'！',
		));

	JS::AlertHref('修改'. $alertResult .'\n'. $fileResultStr, $backURL);
}



// 检测FTP连接
function CheckFtpConn(){
	global $DB;

	$ftpIp		= OT::GetStr('ip');
	$ftpPort	= OT::GetInt('port');
		if ($ftpPort == 0){ $ftpPort = 21; }
	$ftpUser	= OT::GetStr('user');
	$ftpPwd		= OT::GetStr('pwd');

	$ftpArr = array(
		'host'	=> $ftpIp,
		'port'	=> $ftpPort,
		'user'	=> $ftpUser,
		'pwd'	=> $ftpPwd,
		);
	$ftp = new Ftp($ftpArr);
	if ($ftp->Connect()){
		echo('FTP连接成功（'. $ftpIp .'）');
	}else{
		echo('FTP连接失败（'. $ftp->GetErr() .'）');
	}
}
?>
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
<script language="javascript" type="text/javascript" src="js/appSys.js?v='. OT_VERSION .'"></script>
');


switch($mudi){
	case 'infoSet':
		$MB->IsSecMenuRight('alertBack',525,$dataType);
		InfoSet();
		break;

	default:
		die('err');
}

$skin->WebBottom();

$MB->Close();
$DB->Close();





// 新增、修改
function InfoSet(){
	global $DB,$skin,$mudi,$dataType,$dataTypeCN,$sysAdminArr;

	$revexe=$DB->query('select * from '. OT_dbPref .'appSys');
		if ($row = $revexe->fetch()){
			$AS_isQiniu				= $row['AS_isQiniu'];
			$AS_qiniuKey1			= $row['AS_qiniuKey1'];
			$AS_qiniuKey2			= $row['AS_qiniuKey2'];
			$AS_qiniuName			= $row['AS_qiniuName'];
			$AS_qiniuUrl			= $row['AS_qiniuUrl'];
				if (strlen($AS_qiniuUrl) == 0){ $AS_qiniuUrl = 'http://'; }
			$AS_isUpyun				= $row['AS_isUpyun'];
			$AS_upyunKey1			= $row['AS_upyunKey1'];
			$AS_upyunKey2			= $row['AS_upyunKey2'];
			$AS_upyunName			= $row['AS_upyunName'];
			$AS_upyunUrl			= $row['AS_upyunUrl'];
				if (strlen($AS_upyunUrl) == 0){ $AS_upyunUrl = 'http://'; }
			$AS_isAliyun			= $row['AS_isAliyun'];
			$AS_aliyunKey1			= $row['AS_aliyunKey1'];
			$AS_aliyunKey2			= $row['AS_aliyunKey2'];
			$AS_aliyunName			= $row['AS_aliyunName'];
			$AS_aliyunEndPoint		= $row['AS_aliyunEndPoint'];
			$AS_aliyunUrl			= $row['AS_aliyunUrl'];
				if (strlen($AS_aliyunUrl) == 0){ $AS_aliyunUrl = 'http://'; }
			$AS_isJingan			= $row['AS_isJingan'];
			$AS_jinganKey1			= $row['AS_jinganKey1'];
			$AS_jinganKey2			= $row['AS_jinganKey2'];
			$AS_jinganKey3			= $row['AS_jinganKey3'];
			$AS_jinganName			= $row['AS_jinganName'];
			$AS_jinganUrl			= $row['AS_jinganUrl'];
			$AS_videoPcWidth		= $row['AS_videoPcWidth'];
			$AS_videoPcHeight		= $row['AS_videoPcHeight'];
			$AS_videoWapWidth		= $row['AS_videoWapWidth'];
			$AS_videoWapHeight		= $row['AS_videoWapHeight'];
			$AS_audioPcWidth		= $row['AS_audioPcWidth'];
			$AS_audioPcHeight		= $row['AS_audioPcHeight'];
			$AS_audioWapWidth		= $row['AS_audioWapWidth'];
			$AS_audioWapHeight		= $row['AS_audioWapHeight'];
			$AS_recomAnnoun			= $row['AS_recomAnnoun'];
			$AS_recomLinkStr		= $row['AS_recomLinkStr'];
			$AS_recomImgStr			= $row['AS_recomImgStr'];
			$AS_recomFontStr		= $row['AS_recomFontStr'];
			$AS_recomNoteStr		= $row['AS_recomNoteStr'];
			$AS_recomArea			= $row['AS_recomArea'];
			$AS_isGain				= $row['AS_isGain'];
			$AS_gainRecomMoney		= $row['AS_gainRecomMoney'];
			$AS_gainNewsMoney		= $row['AS_gainNewsMoney'];
			$AS_gainUserMoney		= $row['AS_gainUserMoney'];
			$AS_gainMoney			= $row['AS_gainMoney'];
			$AS_gainDay				= $row['AS_gainDay'];
			$AS_isGainScore			= $row['AS_isGainScore'];
			$AS_gainScore1Rate		= $row['AS_gainScore1Rate'];
			$AS_gainScore2Rate		= $row['AS_gainScore2Rate'];
			$AS_gainScore3Rate		= $row['AS_gainScore3Rate'];
			$AS_userState1Money		= $row['AS_userState1Money'];
			$AS_userState1Score1	= $row['AS_userState1Score1'];
			$AS_userState1Score2	= $row['AS_userState1Score2'];
			$AS_userState1Score3	= $row['AS_userState1Score3'];
			$AS_isFtp				= $row['AS_isFtp'];
			$AS_ftpIp				= $row['AS_ftpIp'];
			$AS_ftpPort				= $row['AS_ftpPort'];
			$AS_ftpUser				= $row['AS_ftpUser'];
			$AS_ftpPwd				= $row['AS_ftpPwd'];
			$AS_ftpDefDir			= $row['AS_ftpDefDir'];
			$AS_ftpUrl				= $row['AS_ftpUrl'];
				if (strlen($AS_ftpUrl) == 0){ $AS_ftpUrl = 'http://'; }
		}
	unset($revexe);

	$userSysArr = Cache::PhpFile('userSys');

	echo('
	<form id="dealForm" name="dealForm" method="post" action="appSys_deal.php?mudi='. $mudi .'" onsubmit="return CheckForm()">
	<input type="hidden" id="dataType" name="dataType" value="'. $dataType .'" />
	<input type="hidden" id="dataTypeCN" name="dataTypeCN" value="'. $dataTypeCN .'" />
	<script language="javascript" type="text/javascript">document.write(\'<input type="hidden" name="backURL" value="\'+ document.location.href +\'" />\')</script>

	
	<div class="tabMenu">
	<ul>
	   <li rel="tabOss" class="selected">云存储</li>
	   <li rel="tabNews">文章类</li>
	   <li rel="tabUser">会员类</li>
	</ul>
	</div>

	<div class="tabMenuArea">
		<table id="tabOss" cellpadding="0" cellspacing="0" summary="" class="padd3">
		<tr><td style="width:150px;"></td><td></td></tr>'. 
		AppOssAliyun::AppSysBox($AS_isAliyun,$AS_aliyunKey1,$AS_aliyunKey2,$AS_aliyunName,$AS_aliyunEndPoint,$AS_aliyunUrl) .
		AppOssQiniu::AppSysBox($AS_isQiniu,$AS_qiniuKey1,$AS_qiniuKey2,$AS_qiniuName,$AS_qiniuUrl) .
		AppOssUpyun::AppSysBox($AS_isUpyun,$AS_upyunKey1,$AS_upyunKey2,$AS_upyunName,$AS_upyunUrl) .
		AppOssFtp::AppSysBox($AS_isFtp,$AS_ftpIp,$AS_ftpPort,$AS_ftpUser,$AS_ftpPwd,$AS_ftpDefDir,$AS_ftpUrl) .
		'</table>


		<table id="tabNews" style="display:none;" cellpadding="0" cellspacing="0" summary="" class="padd3">
		<tr><td style="width:150px;"></td><td></td></tr>
		'. AppVideo::AppSysTrBox1($AS_videoPcWidth, $AS_videoPcHeight, $AS_videoWapWidth, $AS_videoWapHeight, $AS_audioPcWidth, $AS_audioPcHeight, $AS_audioWapWidth, $AS_audioWapHeight) .'
		</table>


		<table id="tabUser" style="display:none;" cellpadding="0" cellspacing="0" summary="" class="padd3">
		<tr><td style="width:150px;"></td><td></td></tr>
		'. AppGain::AppSysTrBox1($AS_isGain, $AS_gainRecomMoney, $AS_gainNewsMoney, $AS_gainUserMoney, $AS_gainMoney, $AS_gainDay) .'
		'. AppRecom::AppSysTrBox1($AS_recomAnnoun, $AS_recomLinkStr, $AS_recomImgStr, $AS_recomFontStr, $AS_recomNoteStr) .'
		'. AppUserState1::AppSysTrBox1($AS_userState1Money, $AS_userState1Score1, $AS_userState1Score2, $AS_userState1Score3, AppMoneyPay::Jud(), $userSysArr) .'
		</table>

		<div class="tabMenuSubmit"><input type="submit" class="btnBg" value="保 存" /></div>
	</div>

	</form>
	');

}

?>
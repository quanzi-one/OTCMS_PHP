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

if ($dataType==''){$dataType=OT::PostRegExpStr('dataType','sql');}


switch($mudi){
	case 'infoSet':
		$menuFileID = 122;
		$MB->IsSecMenuRight('alertBack',$menuFileID,$dataType);
		InfoSet();
		break;

	default:
		die('err');
}

$MB->Close();
$DB->Close();





function InfoSet(){
	global $DB,$mudi,$menuFileID,$menuTreeID;

	$backURL	= OT::PostStr('backURL');
	$dataType	= OT::PostStr('dataType');
	$dataTypeCN	= OT::PostStr('dataTypeCN');

	$isDir		= OT::PostInt('isDir');
	$isThumb	= OT::PostStr('isThumb');
	$thumbW		= OT::PostInt('thumbW');
	$thumbH		= OT::PostInt('thumbH');
	$isWatermark			= OT::PostStr('isWatermark');
	$watermarkTrans			= OT::PostStr('watermarkTrans');
	$watermarkPath			= OT::PostStr('watermarkPath');
	$watermarkPos			= OT::PostStr('watermarkPos');
	$watermarkPadding		= OT::PostInt('watermarkPadding');
	$watermarkFontContent	= OT::PostStr('watermarkFontContent');
	$watermarkFontSize		= OT::PostInt('watermarkFontSize');
	$watermarkFontColor		= OT::PostStr('watermarkFontColor');

	$record=array();
	$record['SI_isDir']	= $isDir;
	if ($isThumb!=''){
		$record['SI_isThumb']	= $isThumb;
		$record['SI_thumbW']	= $thumbW;
		$record['SI_thumbH']	= $thumbH;
	}
	if (AppBase::Jud()){
		if ($isWatermark!=''){
			$record['SI_isWatermark']			= $isWatermark;
			$record['SI_watermarkTrans']		= $watermarkTrans;
			$record['SI_watermarkPath']			= $watermarkPath;
			$record['SI_watermarkPos']			= $watermarkPos;
			$record['SI_watermarkPadding']		= $watermarkPadding;
			$record['SI_watermarkFontContent']	= $watermarkFontContent;
			$record['SI_watermarkFontSize']		= $watermarkFontSize;
			$record['SI_watermarkFontColor']	= $watermarkFontColor;
		}
	}

	$judResult = $DB->UpdateParam('sysImages',$record,'SI_ID=1');
		if ($judResult){
			$alertResult = '成功';

			$Cache = new Cache();
			$Cache->Php('sysImages');	// 更新缓存文件
		}else{
			$alertResult = '失败';
		}

	Adm::AddLog(array(
		'note'		=> '【'. $dataTypeCN .'】修改'. $alertResult .'！',
		));

	JS::AlertHrefEnd('修改'. $alertResult .'.',$backURL);

}

?>
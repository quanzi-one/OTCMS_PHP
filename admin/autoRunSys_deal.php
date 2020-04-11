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
		$menuFileID = 274;
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
	global $DB,$mudi,$menuFileID,$menuTreeID,$dataType,$dataTypeCN;

	$backURL		= OT::PostStr('backURL');
	$dataType		= OT::PostStr('dataType');
	$dataTypeCN		= OT::PostStr('dataTypeCN');

	$runMode			= OT::PostInt('runMode');
	$runArea			= OT::Post('runArea');
		if (is_array($runArea)){ $runArea = implode(',',$runArea); }
	$isTimeRun			= OT::PostInt('isTimeRun');
	$timeRunMin			= OT::PostInt('timeRunMin');
//		if ($timeRunMin < 10){ $timeRunMin = 10; }
	$timeRunItem		= OT::Post('timeRunItem');
		if (is_array($timeRunItem)){ $timeRunItem = implode(',',$timeRunItem); }
	$isHtmlHome			= OT::PostInt('isHtmlHome');
	$htmlHomeMin		= OT::PostInt('htmlHomeMin');
		if ($htmlHomeMin < 10){ $htmlHomeMin = 10; }
	$isHtmlList			= OT::PostInt('isHtmlList');
	$htmlListMin		= OT::PostInt('htmlListMin');
		if ($htmlListMin < 1){ $htmlListMin = 1; }
	$htmlListNum		= OT::PostInt('htmlListNum');
		if ($htmlListNum < 1){ $htmlListNum = 1; }
		elseif ($htmlListNum > 20){ $htmlListNum = 20; }
	$htmlListMaxNum		= OT::PostInt('htmlListMaxNum');
		if ($htmlListMaxNum < 1){ $htmlListMaxNum = 1; }
	$isHtmlShow			= OT::PostInt('isHtmlShow');
	$htmlShowMin		= OT::PostInt('htmlShowMin');
		if ($htmlShowMin < 1){ $htmlShowMin = 1; }
	$htmlShowNum		= OT::PostInt('htmlShowNum');
		if ($htmlShowNum < 1){ $htmlShowNum = 1; }
		elseif ($htmlShowNum > 20){ $htmlShowNum = 20; }
	$htmlShowMaxNum		= OT::PostInt('htmlShowMaxNum');
		if ($htmlShowMaxNum < 1){ $htmlShowMaxNum = 1; }
	$htmlShowStartTime	= OT::PostStr('htmlShowStartTime');
		if (! strtotime($htmlShowStartTime)){ $htmlShowStartTime = '2018-1-1 00:00:01'; }
	$isColl				= OT::PostInt('isColl');
	$collMin			= OT::PostInt('collMin');
		if ($collMin < 10){ $collMin = 10; }
	$collNum			= OT::PostInt('collNum');
		if ($collNum < 1){ $collNum = 1; }
		elseif ($collNum > 10){ $collNum = 10; }
	$collFailNum		= OT::PostInt('collFailNum');
		if ($collFailNum < 1){ $collFailNum = 1; }
	$isSoftBak			= OT::PostInt('isSoftBak');
	$softBakMin			= OT::PostInt('softBakMin');
	$softBakArea		= OT::PostStr('softBakArea');
	$isDbBak			= OT::PostInt('isDbBak');
	$dbBakMin			= OT::PostInt('dbBakMin');
	$dbBakMode			= OT::PostStr('dbBakMode');

	$sign				= OT::PostStr('sign');
	$authState			= OT::PostStr('authState');

	$record=array();
	$record['ARS_runMode']			= $runMode;
	$record['ARS_runArea']			= $runArea;
	$record['ARS_isTimeRun']		= $isTimeRun;
	$record['ARS_timeRunMin']		= $timeRunMin;
	$record['ARS_timeRunItem']		= $timeRunItem;
	$record['ARS_isSoftBak']		= $isSoftBak;
	$record['ARS_softBakMin']		= $softBakMin;
	$record['ARS_softBakArea']		= $softBakArea;
	$record['ARS_isDbBak']			= $isDbBak;
	$record['ARS_dbBakMin']			= $dbBakMin;
	$record['ARS_dbBakMode']		= $dbBakMode;
	$record['ARS_isHtmlHome']		= $isHtmlHome;
	$record['ARS_htmlHomeMin']		= $htmlHomeMin;
	$record['ARS_isHtmlList']		= $isHtmlList;
	$record['ARS_isHtmlShow']		= $isHtmlShow;
	$record['ARS_isColl']			= $isColl;
	if ($authState != 'false'){
		if (AppAutoHtml::Jud()){
			$record['ARS_htmlListMin']		= $htmlListMin;
			$record['ARS_htmlListNum']		= $htmlListNum;
			$record['ARS_htmlListMaxNum']	= $htmlListMaxNum;
			$record['ARS_htmlShowMin']		= $htmlShowMin;
			$record['ARS_htmlShowNum']		= $htmlShowNum;
			$record['ARS_htmlShowMaxNum']	= $htmlShowMaxNum;
			$record['ARS_htmlShowStartTime']= $htmlShowStartTime;
		}
		if (AppAutoColl::Jud()){
			$record['ARS_collMin']			= $collMin;
			$record['ARS_collNum']			= $collNum;
			$record['ARS_collFailNum']		= $collFailNum;
		}
	}

	$cacheStr = '';
	$judResult = $DB->UpdateParam('autoRunSys',$record,'ARS_ID=1');
		if ($judResult){
			$alertResult = '成功';

			$Cache = new Cache();
			$result = $Cache->Php('autoRunSys');	// 更新缓存文件
				if ($result){
					$cacheStr .= '\n../cache/autoRunSys.php 生成成功！';
				}else{
					$cacheStr .= '\n../cache/autoRunSys.php 生成失败，请检查该目录或者文件是否有写入/修改权限！';
				}
			$isJsResult = $Cache->Js('autoRunSys');
				if ($isJsResult){
					$cacheStr .= '\n../cache/js/autoRunSys.js 生成成功！';
				}else{
					$cacheStr .= '\n../cache/js/autoRunSys.js 生成失败，请检查该目录或者文件是否有写入/修改权限！';
				}
			if (Cache::UpdateConfigJs()){
				$cacheStr .= '\n../cache/js/configJs.js 生成成功！';
			}else{
				$cacheStr .= '\n../cache/js/configJs.js 生成失败，请检查该目录或者文件是否有写入/修改权限！';
			}
		}else{
			$alertResult = '失败';
		}

	Adm::AddLog(array(
		'note'		=> '【自动操作设置】保存'. $alertResult .'！',
		));

	JS::AlertHrefEnd('保存'. $alertResult .'.'. $cacheStr, $backURL);

}

?>
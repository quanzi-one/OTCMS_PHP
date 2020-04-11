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

$MB->IsSecMenuRight('alertBack',122,$dataType);

switch($mudi){
	case 'deal':
		Deal();
		break;

	case 'saveDef':
		SaveDef();
		break;

	default:
		die('err');
}

$MB->Close();
$DB->Close();





// 修改后台参数设置
function Deal(){
	global $DB,$mudi,$menuFileID,$menuTreeID;

	$backURL			= OT::PostStr('backURL');
	$dataType			= OT::PostStr('dataType');
	$dataTypeCN			= OT::PostStr('dataTypeCN');

	$adminLoginKey		= OT::PostStr('adminLoginKey');
	$isAutoLogin		= OT::PostInt('isAutoLogin');
	$skinWidth			= OT::PostInt('skinWidth');
	$userSaveMode		= OT::PostInt('userSaveMode');
	$loginMode			= OT::PostInt('loginMode');
	$isLan				= OT::PostInt('isLan');
	$sendUrlMode		= OT::PostInt('sendUrlMode');
	$checkUrlMode		= OT::PostInt('checkUrlMode');
	$updateUrlMode		= OT::PostInt('updateUrlMode');
	$exitMinute			= OT::PostInt('exitMinute');
	$leftMenuNote		= OT::PostStr('leftMenuNote');
	$editorMode			= OT::PostStr('editorMode');
	$memberLogRank		= OT::PostInt('memberLogRank');
	$updateUrl			= OT::PostStr('updateUrl');
	$copyrightName		= OT::PostStr('copyrightName');
	$copyrightUrl		= OT::PostStr('copyrightUrl');
		if (strlen($copyrightUrl) == 0){ $copyrightUrl='http://'; }
	$getUrlMode			= OT::PostInt('getUrlMode');
	$collUrlMode		= OT::PostInt('collUrlMode');
	$softUpdateDay		= OT::PostInt('softUpdateDay');
	$softVerUpdateDay	= OT::PostInt('softVerUpdateDay');
	$isConnInternet		= OT::PostInt('isConnInternet');
	$isAnnounShow		= OT::PostInt('isAnnounShow');
	$backupCallDay		= OT::PostInt('backupCallDay');
	$isSubMenu			= OT::PostInt('isSubMenu');
	$upFileArea			= OT::PostStr('upFileArea');
	$upFileOss			= OT::PostStr('upFileOss');
		if ($upFileOss == ''){ $upFileOss = 'web'; }

	if ($backURL=='' || $adminLoginKey==''){
		JS::AlertBackEnd('表单内容接收不全');
	}

	$record=array();
	$record['SA_adminLoginKey']		= $adminLoginKey;
	$record['SA_isAutoLogin']		= $isAutoLogin;
	$record['SA_skinWidth']			= $skinWidth;
	$record['SA_userSaveMode']		= $userSaveMode;
	$record['SA_loginMode']			= $loginMode;
	$record['SA_isLan']				= $isLan;
	$record['SA_sendUrlMode']		= $sendUrlMode;
	$record['SA_checkUrlMode']		= $checkUrlMode;
	$record['SA_updateUrlMode']		= $updateUrlMode;
	$record['SA_exitMinute']		= $exitMinute;
	$record['SA_leftMenuNote']		= $leftMenuNote;
	$record['SA_memberLogRank']		= $memberLogRank;
	$record['SA_softUpdateDay']		= $softUpdateDay;
	$record['SA_softVerUpdateDay']	= $softVerUpdateDay;
	$record['SA_isConnInternet']	= $isConnInternet;
	$record['SA_isAnnounShow']		= $isAnnounShow;
	$record['SA_backupCallDay']		= $backupCallDay;
	$record['SA_getUrlMode']		= $getUrlMode;
	$record['SA_collUrlMode']		= $collUrlMode;
	$record['SA_copyrightName']		= $copyrightName;
	$record['SA_copyrightUrl']		= $copyrightUrl;
	$record['SA_editorMode']		= $editorMode;
	$record['SA_updateUrl']			= $updateUrl;
	$record['SA_isSubMenu']			= $isSubMenu;
	// $record['SA_upFileArea']		= $upFileArea;
	$record['SA_upFileOss']			= $upFileOss;

	$fileResultStr = '';
	$judResult = $DB->UpdateParam('sysAdmin',$record,'SA_ID=1');
		if ($judResult){
			$alertResult = '成功';

			$Cache = new Cache();
			$result = $Cache->Php('sysAdmin');	// 更新缓存文件
			if ($result){
				$fileResultStr .= '\n../cache/php/sysAdmin.php 生成成功！';
			}else{
				$fileResultStr .= '\n../cache/php/sysAdmin.php 生成失败，请检查该目录或者文件是否有写入/修改权限！';
			}
		}else{
			$alertResult = '失败';
		}

	Adm::AddLog(array(
		'note'		=> '【后台参数设置】修改'. $alertResult .'！',
		));

	JS::AlertHrefEnd('修改'. $alertResult .'.'. $fileResultStr,$backURL);
}



// 模式、路线恢复默认值
function SaveDef(){
	global $DB,$skin,$mudi,$menuFileID,$menuTreeID;

	$backURL = OT::GetStr('backURL');
		if ($backURL == ''){ $backURL='sysAdmin.php?mudi='; }

	$record=array();
	$record['SA_sendUrlMode']		= 0;
	$record['SA_checkUrlMode']		= 0;
	$record['SA_updateUrlMode']		= 0;
	$record['SA_getUrlMode']		= 0;
	$record['SA_collUrlMode']		= 0;

	$fileResultStr = '';
	$judResult = $DB->UpdateParam('sysAdmin',$record,'SA_ID=1');
		if ($judResult){
			$alertResult = '成功';

			$Cache = new Cache();
			$result = $Cache->Php('sysAdmin');	// 更新缓存文件
			if ($result){
				$fileResultStr .= '\n../cache/php/sysAdmin.php 生成成功！';
			}else{
				$fileResultStr .= '\n../cache/php/sysAdmin.php 生成失败，请检查该目录或者文件是否有写入/修改权限！';
			}
		}else{
			$alertResult = '失败';
		}

	Adm::AddLog(array(
		'note'		=> '【后台参数设置】模式、路线恢复默认值'. $alertResult .'！',
		));

	JS::AlertHrefEnd('模式、路线恢复默认值'. $alertResult .'.'. $fileResultStr,$backURL);
}

?>
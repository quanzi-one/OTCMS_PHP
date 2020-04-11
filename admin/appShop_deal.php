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



if ($mudi != 'auth'){
	//用户检测
	$MB->Open('','login');
}


switch ($mudi){
	case 'getInfo':
		$menuFileID = 171;
//		$MB->IsSecMenuRight('alert',$menuFileID,$dataType);
		GetInfo();
		break;

	case 'getAuth':
		GetAuth();
		break;

	case 'auth':
		AuthDeal();
		break;

	default:
		die('err');
}

$MB->Close();
$DB->Close();





// 获取已订购插件信息
function GetInfo(){
	global $DB,$sysAdminArr;

	$mode		= OT::GetStr('mode');
	$backURL	= OT::GetStr('backURL');
//		if (empty($backURL)){
			$backURL = 'appShop.php?dataMode=buy';
//		}
	$beforeURL	= GetUrl::CurrDir(1);


	$updateUrl = ReqUrl::SelUpdateUrl($sysAdminArr['SA_updateUrlMode']);
	$retStr = ReqUrl::UseAuto($sysAdminArr['SA_getUrlMode'], 'GET', $updateUrl .'otcmsAppInfo.php?mudi=getInfo&OT_UPDATETIME='. OT_UPDATETIME .'&OT_VERSION='. OT_VERSION .'&dataVer='. OT_UPDATEVER .'&OT_URL='. urlencode($beforeURL) .'&username='. $sysAdminArr['SA_username'] .'&softID='. $sysAdminArr['SA_softID'] .'&softCode='. $sysAdminArr['SA_softCode'] .'&domainID='. $sysAdminArr['SA_domainID'] .'&domainCode='. $sysAdminArr['SA_domainCode'], 'UTF-8', array(), 'note');
	if (strpos($retStr,'[true]') === false){
		$checkAuditNum = $DB->GetOne('select SA_checkAuditNum from '. OT_dbPref .'sysAdmin');
		if ($checkAuditNum >= 10){
			$DB->Delete('paySoft');
		}else{
			$DB->query('update '. OT_dbPref .'sysAdmin set SA_checkAuditNum=SA_checkAuditNum+1 where SA_ID=1');
		}
		if ($mode != 'no'){ JS::AlertHrefEnd(str_replace('"','\"',$retStr), $backURL); }
	}else{
		$DB->query('update '. OT_dbPref .'sysAdmin set SA_checkAuditNum=0 where SA_ID=1');

		$arrStr = Str::GetMark($retStr,'<appInfoList>','</appInfoList>');
		$infoArr = unserialize(@$arrStr);	// print_r($infoArr);die();
		if (! is_array($infoArr)){
			if ($mode != 'no'){ JS::AlertHrefEnd('获取的信息有误，不是数组信息。', $backURL); }
		}else{
			//print_r($infoArr);die();
			$stateArr = array();
			$chkexe = $DB->query('select PS_appID from '. OT_dbPref .'paySoft where PS_state=0');
			while ($rs = $chkexe->fetch()){
				$stateArr[] = $rs['PS_appID'];
			}
			unset($chkexe);

			$DB->UpdateParam('paySoft',array('PS_state'=>0),'1=1');
			foreach ($infoArr as $oneArr){
				$phpVer = OT::ToFloat($oneArr['phpVer'],2);
				$appID = intval($oneArr['appID']);
				$dataArr = array(
					'PS_lastTime'	=> TimeDate::Get(),
					'PS_appID'		=> $appID,
					'PS_appType'	=> $oneArr['appType'],
					'PS_appCode'	=> $oneArr['authCode'],
					'PS_theme'		=> $oneArr['theme'],
					'PS_buyCost'	=> $oneArr['buyCost'],
					'PS_cost'		=> $oneArr['cost'],
					'PS_payMode'	=> $oneArr['payMode'],
					'PS_buyDate'	=> $oneArr['buyDate'],
					'PS_useDate'	=> $oneArr['useDate'],
					'PS_updateDate'	=> $oneArr['updateDate'],
					'PS_currVer'	=> $oneArr['currVer'],
					'PS_currTime'	=> $oneArr['currTime'],
					'PS_softVer'	=> $oneArr['softVer'],
					'PS_softTime'	=> $oneArr['softTime'],
					'PS_phpVer'		=> $phpVer,
					'PS_newVer'		=> $oneArr['newVer'],
					'PS_newTime'	=> $oneArr['newTime'],
					'PS_url'		=> $oneArr['url'],
					'PS_event'		=> $oneArr['event'],
					'PS_state'		=> 1
					);
					if (strtotime($oneArr['time'])){
						$dataArr['PS_time'] = $oneArr['time'];
					}
					if (PHP_VERSION < $phpVer && in_array($appID,$stateArr)==false){
						$stateArr[] = $appID;
					}
				$readexe = $DB->query('select PS_ID from '. OT_dbPref .'paySoft where PS_appID='. $appID);
				if ($row = $readexe->fetch()){
					$DB->UpdateParam('paySoft',$dataArr,'PS_ID='. $row['PS_ID']);
				}else{
					$DB->InsertParam('paySoft',array_merge($dataArr,array('PS_lastTime'	=> TimeDate::Get())));
				}
				$readexe = null;
			}
			$DB->Delete('paySoft','PS_state=0');

			if (count($stateArr) > 0){
				$DB->UpdateParam('paySoft',array('PS_state'=>0),'PS_appID in ('. implode(',',$stateArr) .')');
			}

			// 遍历所有插件状态判断处理
			AdmArea::PaySoftStateDeal();
		}
	}

	$cacheStr = '';
	$Cache = new Cache();
	$isCacheResult = $Cache->PhpTypeArr('paySoft');
		if ($isCacheResult){
			$cacheStr = '\n../cache/php/paySoft.php 生成成功！';
		}else{
			$cacheStr = '\n../cache/php/paySoft.php 生成失败，请检查该目录或者文件是否有写入/修改权限！';
		}

	if ($mode != 'no'){
		if ($mode == 'back'){
			$appUpdateCount = $DB->GetOne('select count(PS_ID) from '. OT_dbPref .'paySoft where PS_currTime<PS_newTime');
			if ($appUpdateCount > 0){
				JS::AlertHrefEnd('更新完毕\n'. $cacheStr, 'appShop.php?dataMode=buy');
			}else{
				JS::AlertEnd('更新完毕\n'. $cacheStr);
			}
		}else{
			JS::AlertHrefEnd('更新完毕\n'. $cacheStr, $backURL);
		}
	}

}



// 快捷授权登录获取
function GetAuth(){
	global $DB,$sysAdminArr,$otcmsUrl1;

	$softID		= OT::PostInt('softID');
	$softCode	= OT::PostStr('softCode');
	$domainID	= OT::PostInt('domainID');
	$domainCode	= OT::PostStr('domainCode');
	$username	= OT::PostStr('username');
	$userpwd	= OT::PostStr('userpwd');

	if ($username == '' || $userpwd == ''){
		JS::AlertEnd('表单填写不全');
	}

	$beforeURL	= GetUrl::CurrDir(1);
	$updateUrl = ReqUrl::SelUpdateUrl($sysAdminArr['SA_updateUrlMode']);
	$retStr = ReqUrl::UseAuto($sysAdminArr['SA_getUrlMode'], 'GET', $updateUrl .'otcmsUserInfo.php?mudi=getInfo&OT_UPDATETIME='. OT_UPDATETIME .'&OT_VERSION='. OT_VERSION .'&dataVer='. OT_UPDATEVER .'&phpVer='. PHP_VERSION .'&OT_URL='. urlencode($beforeURL) .'&username='. $username .'&userpwd='. $userpwd .'&softID='. $sysAdminArr['SA_softID'] .'&softCode='. $sysAdminArr['SA_softCode'] .'&domainID='. $sysAdminArr['SA_domainID'] .'&domainCode='. $sysAdminArr['SA_domainCode'], 'UTF-8', array(), 'note');
	if (strpos($retStr,'[true]') === false){
		JS::AlertEnd('授权获取出错：'. $retStr);
	}
	$softID			= intval(Str::GetMark($retStr,'[field:softID]','[/field:softID]'));
	$softCode		= Str::GetMark($retStr,'[field:softCode]','[/field:softCode]');
	$domain			= Str::GetMark($retStr,'[field:domain]','[/field:domain]');
	$domainID		= intval(Str::GetMark($retStr,'[field:domainID]','[/field:domainID]'));
	$domainCode		= Str::GetMark($retStr,'[field:domainCode]','[/field:domainCode]');

	if ($softID == 0 || $softCode == '' || $domain == '' || $domainID == 0 || $domainCode == ''){
		JS::AlertEnd('授权反馈信息不全（'. $softID .'|'. strlen($softCode) .'|'. $domain .'|'. $domainID .'|'. strlen($domainCode) .'）');
	}

	$record=array();
	$record['SA_softID']		= $softID;
	$record['SA_softCode']		= $softCode;
	$record['SA_username']		= $username;
	$record['SA_domain']		= $domain;
	$record['SA_domainID']		= $domainID;
	$record['SA_domainCode']	= $domainCode;

	$judResult = $DB->UpdateParam('sysAdmin',$record,'SA_ID=1');
		if ($judResult){
			$Cache = new Cache();
			$result = $Cache->Php('sysAdmin');	// 更新缓存文件
			if ($result){
				$cacheStr = '';
			}else{
				$cacheStr = '\n../cache/php/sysAdmin.php 生成失败，请检查该目录或者文件是否有写入/修改权限！';
			}
			JS::DiyEnd('alert("授权获取成功'. $cacheStr .'");$id("loginBox").innerHTML="登录用户名：'. $username .'";');
		}else{
			JS::AlertEnd('授权获取失败');
		}
}



function AuthDeal(){
	global $DB,$sysAdminArr,$otcmsUrl1;

	$mudi2		= OT::GetStr('mudi2');
	$softID		= OT::GetInt('softID');
	$softCode	= OT::GetStr('softCode');
	$username	= OT::GetStr('username');
	$domainID	= OT::GetInt('domainID');
	$domainCode	= OT::GetStr('domainCode');
	$domain		= OT::GetStr('domain');

	$record=array();
	if ($mudi2 == 'user'){

		if ($softID==0 || $username=='' || $softCode==''){
			die('[false]empty');
		}

		$record['SA_softID']		= $softID;
		$record['SA_username']		= $username;
		$record['SA_softCode']		= $softCode;

	}elseif ($mudi2 == 'domain'){

		if ($domainID==0 || $domainCode==''){ // $softID==0 || $username=='' || $softCode=='' || 
			die('[false]empty');
		}

		$record['SA_domainID']		= $domainID;
		$record['SA_domainCode']	= $domainCode;

	}elseif ($mudi2 == 'all'){

		if ($softID > 0){
			$record['SA_softID']		= $softID;
			if (strlen($softCode) > 0){
				$record['SA_softCode']		= $softCode;
			}
		}
		if (strlen($username) > 0){
			$record['SA_username']		= $username;
		}
		if ($domainID > 0){
			$record['SA_domainID']		= $domainID;
			if (strlen($domainCode) > 0){
				$record['SA_domainCode']	= $domainCode;
			}
		}
		if (strlen($domain) > 0){
			$record['SA_domain']		= $domain;
		}

	}else{
		die('[false]mudi2不明确');

	}

	$judResult = $DB->UpdateParam('sysAdmin',$record,'SA_ID=1');
		if ($judResult){
			$alertResult = '[true]';

			$Cache = new Cache();
			$result = $Cache->Php('sysAdmin');	// 更新缓存文件
		}else{
			$alertResult = '[false]';
		}

	die($alertResult);
}

?>
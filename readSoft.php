<?php
header('Content-Type: text/html; charset=UTF-8');

define('OT_ROOT', dirname(__FILE__) .'/');

require(OT_ROOT .'config.php');
require(OT_ROOT .'configVer.php');
require(OT_ROOT .'inc/classReqUrl.php');


$infoSysArr = unserialize(@include(OT_ROOT .'cache/php/infoSys.php'));
$systemArr = unserialize(@include(OT_ROOT .'cache/php/system.php'));
$sysAdminArr = unserialize(@include(OT_ROOT .'cache/php/sysAdmin.php'));


$mudi = trim(@$_GET['mudi']);

switch ($mudi){
	case 'softInfo':
		SoftInfo();
		break;

	default:
		die('[该访问地址存在]');
		break;
}



function SoftInfo(){
	global $systemArr,$sysAdminArr;

	$rndStr = trim(@$_GET['rndStr']);
	if (strlen($rndStr) != 32){
		die('校验码应为32位.');
	}

	$retArr = ReqUrl::UseAuto($sysAdminArr['SA_getUrlMode'],'GET',ReqUrl::SelUpdateUrl($sysAdminArr['SA_updateUrlMode']) .'otcmsUserIp.php?OT_VERSION='. OT_VERSION .'&OT_UPDATETIME='. OT_UPDATETIME .'&dataVer='. OT_UPDATEVER .'&softID='. $sysAdminArr['SA_softID'] .'&softCode='. $sysAdminArr['SA_softCode'] .'&dbVersion='. $sysAdminArr['SA_dbVersion'] .'&dbTimeStr='. $sysAdminArr['SA_dbTimeStr'] .'&softUrl='. urlencode($_SERVER['HTTP_HOST']) .'&rndStr='. $rndStr .'&rnd='. time());
	if (! $retArr['res']){
		die($retArr['note']);
	}else{
		if (strpos($retArr['note'],$rndStr) === false){
			die('校验码错误（'. $rndStr .'）【'. $retArr['note'] .'】.');
		}
	}

	echo('[网站名称]'. $systemArr['SYS_title'] .''. $retArr['note']);

}

?>
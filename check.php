<?php
require(dirname(__FILE__) .'/conobj.php');


// 获取模板参数数组
if ($tplSysFile = @include(OT_ROOT .'cache/php/tplSys.php')){
	$tplSysArr = unserialize($tplSysFile);
}else{
	$Cache = new Cache();
	$Cache->Php('tplSys');
	die('
	<br /><br />
	<center>
		加载tplSys配置文件失败，<a href="#" onclick="document.location.reload();">[点击重新刷新]</a>
	</center>
	');
}


if ($systemArr['SYS_isClose']==10){
	// 网站关闭
	die('
	<!DOCTYPE html>
	<html>
	<head>
		<title>网站暂时关闭中...</title>
	</head>
	<body>
		<table align="center" cellpadding="0" cellspacing="0"><tr><td align="left" style="font-size:14px;">'. $systemArr['SYS_closeNote'] .'</td></tr></table>
	</body>
	</html>
	');
	
}

define('OT_MODE', 'pc');

$dataID	= OT::GetInt('dataID');

?>
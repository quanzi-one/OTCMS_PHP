<?php
// 初始化变量及引入初始文件
$dbPathPart		= '';
$webPathPart	= '';
$jsPathPart		= '';

require(dirname(__FILE__) .'/check.php');
require(OT_ROOT .'inc/classTemplate.php');
//require(OT_ROOT .'inc/classTplIndex.php');

$judCache = false;
$cacheName='tpl_message';
if ($retStr = Cache::CheckWebCache($cacheName,true)){ $judCache = true; }
if ($judCache == false){

	$tpl = new Template;

	// 初始化公共变量
	$tpl->webTypeName	= 'message';
	$tpl->areaName		= $tplSysArr['TS_messageName'];
	$tpl->webTitle		= str_replace(array('{%标题附加%}','{%标题%}'),array('',$tplSysArr['TS_messageName']),$systemArr['SYS_titleWeb']);
	$tpl->webTitleAddi	= '';
	$tpl->webKey		= $tplSysArr['TS_messageWebKey'];
	$tpl->webDesc		= $tplSysArr['TS_messageWebDesc'];

	// 解析页面
	$tpl->WebTop();
	$tpl->WebBottom();
	$tpl->WebMessage();
	$tpl->WebSubRight();

	$retStr = $tpl->GetShow('message.html');

	Cache::WriteWebCache($cacheName,$retStr);
}
echo($retStr);

?>
<?php
// 初始化变量及引入初始文件
$dbPathPart		= '';
$webPathPart	= '';
$jsPathPart		= '';

require(dirname(__FILE__) .'/check.php');
require(OT_ROOT .'inc/classTemplate.php');
//require(OT_ROOT .'inc/classTplIndex.php');
11
$judCache = false;
$cacheName='tpl_index';
if ($retStr = Cache::CheckWebCache($cacheName,true)){ $judCache = true; }
if ($judCache == false){

	$tpl = new Template;
	// 初始化公共变量
	$tpl->webTypeName	= 'home';
	$tpl->webTitleAddi	= '*';
	$tpl->webKey		= '*';
	$tpl->webDesc		= '*';

	// 解析页面
	$tpl->WebTop();
	$tpl->WebBottom();
	$tpl->WebIndex();

	$retStr = $tpl->GetShow('index.html');

	Cache::WriteWebCache($cacheName,$retStr);
}
echo($retStr);

$DB->Close();

?>
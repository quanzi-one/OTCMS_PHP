<?php
// 初始化变量及引入初始文件
$dbPathPart		= '../';
$webPathPart	= '../';
$jsPathPart		= '../';

require(dirname(dirname(__FILE__)) .'/check.php');
require(OT_ROOT .'inc/classTemplate.php');

$infoSysArr = Cache::PhpFile('infoSys');


$tpl = new Template;
// 初始化公共变量
$tpl->dbPathPart	= $dbPathPart;
$tpl->webPathPart	= $webPathPart;
$tpl->jsPathPart	= $jsPathPart;


if ($systemArr['SYS_isUrl301'] == 2){ $webPathPart = $jsPathPart; }
if ($systemArr['SYS_newsShowUrlMode'] == 'static-3.x'){ $tpl->urlMode = $systemArr['SYS_newsShowUrlMode']; }else{ $tpl->urlMode = 'dyn-2.x'; }


$tpl->queryStr = trim($_SERVER['QUERY_STRING']);
$endPos	= strpos($tpl->queryStr,'.html');
$queryUserStr = '';
if ($endPos>=1){
	$queryUserStr=substr($tpl->queryStr,$endPos);
	$tpl->queryStr=substr($tpl->queryStr,0,$endPos);
}
//die($tpl->queryStr .'|'. $endPos .'|'. $queryUserStr);

$judCache = false;
$cacheName='tpl_'. md5($tpl->queryStr);
if ($retStr = Cache::CheckWebCache($cacheName,true)){ $judCache = true; }
if ($judCache == false){

	if (substr($tpl->queryStr,0,5) == 'list_'){
		$tpl->webTypeName = 'list';	// 文章列表页
		$tpl->fileName = '/'. $systemArr['SYS_newsListFileName'] .'/';

		if ($systemArr['SYS_diyInfoTypeDir'] == 1){
			$jsPathPart	= '../';
		}

		$tpl->WebList();
		if (! $tpl->CheckTplFile($tpl->tplFileName)){ $tpl->tplFileName = 'list.html'; }

	}elseif (substr($tpl->queryStr,0,4) == 'web_'){
		$tpl->webTypeName = 'web';		// 单篇页
		$tpl->fileName = '/'. $systemArr['SYS_dynWebFileName'] .'/';

		$tpl->WebWeb();
		if (! $tpl->CheckTplFile($tpl->tplFileName)){ $tpl->tplFileName = 'web.html'; }

	}elseif (is_numeric(substr($tpl->queryStr,0,1))){
		$tpl->webTypeName = 'show';	// 文章内容页
		$tpl->fileName = '/'. $systemArr['SYS_newsShowFileName'] .'/';

		if (strpos($queryUserStr,'rnd=user') === false){
			if ($systemArr['SYS_htmlInfoTypeDir'] == 1 && $systemArr['SYS_htmlDatetimeDir']>0){
				if ($systemArr['SYS_diyInfoTypeDir'] == 1){
					$jsPathPart	= '../../';
				}else{
					$jsPathPart	= '../../../';
				}
			}elseif ($systemArr['SYS_htmlInfoTypeDir'] == 1 || $systemArr['SYS_htmlDatetimeDir']>0){
				if ($systemArr['SYS_diyInfoTypeDir'] == 1){
					$jsPathPart	= '../';
				}else{
					$jsPathPart	= '../../';
				}
			}
		}else{
			$judMakeCache='false';
		}
		if ($systemArr['SYS_isUrl301'] == 2){ $webPathPart=$jsPathPart; }

		$tpl->WebNews();
		if (! $tpl->CheckTplFile($tpl->tplFileName)){ $tpl->tplFileName = 'news.html'; }

	}else{
		JS::HrefEnd($dbPathPart);

	}

	$tpl->WebTop();
	$tpl->WebBottom();
	$tpl->WebSubRight();

	$retStr = $tpl->GetShow($tpl->tplFileName);

	Cache::WriteWebCache($cacheName,$retStr);
}
echo($retStr);
?>
<?php
require(dirname(__FILE__) .'/check.php');


/* **** 版权所有 ****

 网钛CMS(OTCMS)
 程序开发：网钛科技
 官方网站：http://otcms.com
 联系ＱＱ：877873666
 联系邮箱：877873666@qq.com

 **** 版权所有 **** */


@ini_set('max_execution_time', 0);
@set_time_limit(0); 

$mudi	= OT::GetStr('mudi');
$dataID	= OT::GetInt('dataID');

$userSysArr = Cache::PhpFile('userSys');
$infoSysArr = Cache::PhpFile('infoSys');
$tplSysArr = Cache::PhpFile('tplSys');

$tpl = new Template;


switch ($mudi){
	case 'homeHtml':
		HomeHtml();
		break;

	case 'subHtml':
		if (! AppBase::Jud()){ exit; }
		SubHtml();
		break;

	default:
		die('err');
}

$DB->Close();





function HomeHtml(){
	global $DB,$tpl,$systemArr,$userSysArr,$infoSysArr,$tplSysArr;

	if ($systemArr['SYS_isHtmlHome'] == 0){
		die('/* 首页静态页尚未开启.\n\n请到【常规设置】->【网站参数设置】-[文章路径]里开启. */');
	}

	$todayTime = TimeDate::Get();

	if ($systemArr['SYS_isClose'] == 10){	// 网站关闭
		$tempContent = $tpl->closeWebContent;
	}else{
		$tpl->webTypeName	= 'home';
		$tpl->webTitleAddi	= '*';
		$tpl->webKey		= '*';
		$tpl->webDesc		= '*';

		// 解析页面
		$tpl->WebTop();
		$tpl->WebBottom();
		$tpl->WebIndex();

		$tempContent = $tpl->GetShow('index.html');
	}

	if (! File::Write(OT_ROOT .'index.html', $tempContent . PHP_EOL .'<!-- Html For '. $todayTime .' -->')){
		die('/* 写入电脑版index.html文件失败，请检查根目录是否有写入权限 */');
	}
	
	$DB->query('update '. OT_dbPref .'autoRunSys set ARS_htmlHomeTime='. $DB->ForTime($todayTime));

	$logArr = array();
	$logArr['ARL_time']		= $todayTime;
	$logArr['ARL_type']		= 'home';
	$logArr['ARL_dataID']	= 0;
	$logArr['ARL_content']	= '【电脑版】生成首页静态页';
	$DB->InsertParam('autoRunLog',$logArr);

	$Cache = new Cache();
	$Cache->Php('autoRunSys');
	$Cache->Js('autoRunSys');

	die('/* 生成电脑版首页静态页成功. */');

}



function SubHtml(){
	global $DB,$tpl,$GB_WebHost,$GB_JsHost,$systemArr,$userSysArr,$infoSysArr,$tplSysArr;

	$htmlName		= OT::GetStr('htmlName');
	$htmlMode		= OT::GetStr('htmlMode');
	$htmlEachSec	= OT::GetFloat('htmlEachSec');
	$htmlEachNum	= OT::GetInt('htmlEachNum');
		if ($htmlEachNum <= 0){ $htmlEachNum = 1; }
	$htmlPref		= OT::GetStr('htmlPref');
	$htmlCount		= OT::GetInt('htmlCount');
	$htmlNum		= OT::GetInt('htmlNum');
	$htmlSucc		= OT::GetInt('htmlSucc');
	$htmlFail		= OT::GetInt('htmlFail');
	$htmlRecordNum	= OT::GetInt('htmlRecordNum');
	$htmlPageNum	= OT::GetInt('htmlPageNum');
	$typeID			= OT::GetInt('typeID',-99);
	$rateID			= OT::GetInt('rateID');
		if ($htmlNum >= $htmlCount){ $htmlNum = 0; }

	$todayTime		= TimeDate::Get();
	$infoIdArr		= explode(',',GetSession($htmlName .'htmlIdStr'));
	$infoPageArr	= explode(',',GetSession($htmlName .'htmlPageStr'));
	$infoDirArr		= explode(',',GetSession($htmlName .'htmlDirStr'));
	$infoCount		= count($infoIdArr) - 1;
	
	if ($infoCount < 1){
		die('
		<style>body{ text-align:left; margin:4 auto; font-size:12px; color:#000000; line-height:1.2; font-family:宋体; }</style>
		<center>无记录</center>
		');
	}

	$dbPathPart		= '../';
	$webPathPart	= '';
	$jsPathPart		= '../';

	if ($systemArr['SYS_isUrl301'] == 2){ $webPathPart = $jsPathPart; }

	if ($htmlMode == 'list'){
		$tpl->webTypeName = 'list';	// 文章列表页
		$tpl->fileName = $systemArr['SYS_newsListFileName'];
		if ($systemArr['SYS_diyInfoTypeDir'] == 1){
			$jsPathPart	= '../';
		}

	}elseif ($htmlMode == 'web'){
		$tpl->webTypeName = 'web';	// 单篇页
		$tpl->fileName = $systemArr['SYS_dynWebFileName'];

	}elseif (strpos('|show|newsMini|newsOne|diy|','|'. $htmlMode .'|') !== false){
		$tpl->webTypeName = 'show';	// 文章内容页
		$tpl->fileName = $systemArr['SYS_newsShowFileName'];

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
		if ($systemArr['SYS_isUrl301'] == 2){ $webPathPart=$jsPathPart; }
	}

	if (empty($systemArr['SYS_URL'])){
		$GB_WebHost = $webPathPart;
		$GB_JsHost = $jsPathPart;
	}else{
		$GB_WebHost = $systemArr['SYS_URL'];
		$GB_JsHost = $systemArr['SYS_URL'];
	}

	$tpl->dbPathPart	= $dbPathPart;
	$tpl->webPathPart	= $webPathPart;
	$tpl->jsPathPart	= $jsPathPart;
	// die($htmlNum .','. $htmlEachNum .','. $htmlCount);
	// echo('<pre>'. $htmlRecordNum . print_r($infoIdArr,true) . print_r($_GET,true) . print_r($_SESSION,true) .'</pre>');

	$startPage = $htmlPageNum + 1;
	$showHtmlPath = '';
	$currIdArr = array();
	$pageArr = array();
	for ($i=1; $i<=$htmlEachNum; $i++){
		if ($htmlNum < $htmlCount){
			$htmlNum ++;
			$htmlPageNum ++;
			if ($htmlPageNum > 1 && $htmlPageNum <= intval($infoPageArr[$htmlRecordNum])){
				$diyFilePart	= 'index_'. $htmlPageNum;
				$htmlFilePart	= $infoIdArr[$htmlRecordNum] .'_'. $htmlPageNum;
			}else{
				$htmlRecordNum ++;
				$htmlPageNum = 1;
				$diyFilePart = 'index';
				$htmlFilePart = $infoIdArr[$htmlRecordNum];
			}
			$htmlDir = $infoDirArr[$htmlRecordNum];

			$currIdArr[] = $infoIdArr[$htmlRecordNum];
			$pageArr[$infoIdArr[$htmlRecordNum]] = array(
				'id'	=> $infoIdArr[$htmlRecordNum],
				'num'	=> $htmlPageNum,
				'total'	=> $infoPageArr[$htmlRecordNum]
				);

			$tpl->queryStr = $htmlPref . $htmlFilePart;
			// echo($tpl->webTypeName .'|'. $tpl->queryStr .'|'. GetSession($htmlName .'htmlIdStr') .'|'. $htmlPageNum .'|'. $infoIdArr[$htmlRecordNum] .'|'. GetSession($htmlName .'htmlPageStr'));
			if ($tpl->webTypeName == 'list'){
				$tpl->WebList();
				if (! $tpl->CheckTplFile($tpl->tplFileName)){ $tpl->tplFileName = 'list.html'; }

			}elseif ($tpl->webTypeName == 'web'){
				$tpl->WebWeb();
				if (! $tpl->CheckTplFile($tpl->tplFileName)){ $tpl->tplFileName = 'web.html'; }

			}elseif ($tpl->webTypeName == 'show'){
				$tpl->WebNews();
				if (! $tpl->CheckTplFile($tpl->tplFileName)){ $tpl->tplFileName = 'news.html'; }

			}

			$tpl->WebTop();
			$tpl->WebBottom();
			$tpl->WebSubRight();

			$tempContent = $tpl->GetShow($tpl->tplFileName);

			if ($systemArr['SYS_diyInfoTypeDir']==1 && $htmlPref=='list_'){
				$makeHtmlPath = OT_ROOT . $tpl->fileName .'/'. $htmlDir . $diyFilePart .'.html';
				$showHtmlPath = $htmlDir . $diyFilePart .'.html';
			}else{
				$makeHtmlPath = OT_ROOT . $tpl->fileName .'/'. $htmlDir . $htmlPref . $htmlFilePart .'.html';
				$showHtmlPath = $htmlDir . $htmlPref . $htmlFilePart .'.html';
			}
			if ($systemArr['SYS_diyInfoTypeDir']==1 && $tpl->webTypeName!='web'){ $makeHtmlPath=str_replace(OT_ROOT . $tpl->fileName .'/', OT_ROOT, $makeHtmlPath); }
			
			if (! File::Write($makeHtmlPath, $tempContent . PHP_EOL .'<!-- Html For '. $todayTime .' -->',true,$createErr)){
				$htmlFail ++;
				$makeResult = '<span style="color:;">生成失败('. $createErr .')</span>';
				SetSession($htmlName .'htmlFailStr', GetSession($htmlName .'htmlFailStr') . $showHtmlPath .' 生成电脑版失败('. $createErr .')\n');
			}else{
				$htmlSucc ++;
				$makeResult = '<span style="color:;">生成成功</span>';
			}
		}else{
			break;
		}
	}

	if (count($currIdArr) > 0){
		$itemName = '';
		$currIdStr = implode(',',array_unique(array_filter($currIdArr)));
		$logStr = '';
		if ($tpl->webTypeName == 'list'){
			foreach ($pageArr as $val){
				if ($val['id'] == 'announ'){
					$itemName = '【网站公告】';
					if ($val['num'] >= $val['total']){
						$sqlStr = 'update '. OT_dbPref .'autoRunSys set ARS_announTime='. $DB->ForTime($todayTime) .',ARS_announCurrNum=0,ARS_announMaxNum=0';
						// $logStr .= '【'. $sqlStr .'】';
						$DB->query($sqlStr);
					}else{
						$sqlStr = 'update '. OT_dbPref .'autoRunSys set ARS_announCurrNum='. $val['num'] .',ARS_announMaxNum='. $val['total'] .'';
						// $logStr .= '【'. $sqlStr .'】';
						$DB->query($sqlStr);
					}
				}elseif ($val['id'] == 'new'){
					$itemName = '【最新消息】';
					if ($val['num'] >= $val['total']){
						$sqlStr = 'update '. OT_dbPref .'autoRunSys set ARS_newTime='. $DB->ForTime($todayTime) .',ARS_newCurrNum=0,ARS_newMaxNum=0';
						// $logStr .= '【'. $sqlStr .'】';
						$DB->query($sqlStr);
					}else{
						$sqlStr = 'update '. OT_dbPref .'autoRunSys set ARS_newCurrNum='. $val['num'] .',ARS_newMaxNum='. $val['total'] .'';
						// $logStr .= '【'. $sqlStr .'】';
						$DB->query($sqlStr);
					}
				}elseif (intval($val['id']) > 0){
					$itemName = '【'. $DB->GetOne('select IT_theme from '. OT_dbPref .'infoType where IT_ID='. $val['id']) .'】';
					if ($val['num'] >= $val['total']){
						$sqlStr = 'update '. OT_dbPref .'infoType set IT_htmlTime='. $DB->ForTime($todayTime) .',IT_htmlCurrNum=0,IT_htmlMaxNum=0 where IT_ID='. $val['id'] .'';
						// $logStr .= '【'. $sqlStr .'】';
						$DB->query($sqlStr);
					}else{
						$sqlStr = 'update '. OT_dbPref .'infoType set IT_htmlCurrNum='. $val['num'] .',IT_htmlMaxNum='. $val['total'] .' where IT_ID='. $val['id'] .'';
						// $logStr .= '【'. $sqlStr .'】';
						$DB->query($sqlStr);
					}
				}
			}
			// $DB->query('update '. OT_dbPref .'infoType set IT_htmlTime='. $DB->ForTime($todayTime) .' where IT_ID in ('. $currIdStr .')');
			$DB->query('update '. OT_dbPref .'autoRunSys set ARS_htmlListTime='. $DB->ForTime($todayTime));

		}elseif ($tpl->webTypeName == 'web'){
			$sqlStr = 'update '. OT_dbPref .'infoWeb set IW_htmlTime='. $DB->ForTime($todayTime) .' where IW_ID in ('. $currIdStr .')';
			// $logStr .= '【'. $sqlStr .'】';
			$DB->query($sqlStr);

		}elseif ($tpl->webTypeName == 'show'){
			$itemName = '';
			$itemNum = 0;
			$readexe = $DB->query('select IF_ID,IF_theme from '. OT_dbPref .'info where IF_ID in ('. $currIdStr .')');
			while ($row = $readexe->fetch()){
				$itemNum ++;
				$itemName .= '<br />【ID：'. $row['IF_ID'] .'，'. $row['IF_theme'] .'】';
			}
			unset($readexe);
			$itemName = '共'. $itemNum .'篇'. $itemName;

			$sqlStr = 'update '. OT_dbPref .'info set IF_htmlTime='. $DB->ForTime($todayTime) .' where IF_ID in ('. $currIdStr .')';
			// $logStr .= '【'. $sqlStr .'】';
			$DB->query($sqlStr);
			$DB->query('update '. OT_dbPref .'autoRunSys set ARS_htmlShowTime='. $DB->ForTime($todayTime));

		}
		
		// echo($sqlStr);
		$logArr = array();
		$logArr['ARL_time']		= $todayTime;
		$logArr['ARL_type']		= $tpl->webTypeName;
		$logArr['ARL_dataID']	= 0;
		$logArr['ARL_content']	= '【电脑版】'. $showHtmlPath .' '.  $startPage .'-'. $htmlNum .'/'. $htmlCount .'['. $htmlFail .']&ensp;<span class="fontLog">'. $itemName .''. $logStr .'</span>';
		$DB->InsertParam('autoRunLog',$logArr);

		$Cache = new Cache();
		$Cache->Php('autoRunSys');
		$Cache->Js('autoRunSys');
	}

	$failStr = '';
	if ($htmlNum < $htmlCount){

	}else{
		if (strpos('|diy|newsMini|newsOne|','|'. $htmlMode .'|') === false){

		}
		$failStr = ''.
			'记录：'. $infoCount .'\n'.
			'成功：'. $htmlSucc .'\n'.
			'失败：'. $htmlFail .'\n'.
			'电脑版失败页面有：\n'. GetSession($htmlName .'htmlFailStr');
	}

	if ($htmlCount == 0){
		$infoScore = 1;
	}else{
		$infoScore = OT::NumFormat($htmlNum/$htmlCount,2);
	}
	echo('
	<style>body{ text-align:left; margin:0 auto; font-size:12px; color:#000000; line-height:1.2; font-family:宋体; }</style>
	');
	if ($htmlMode == 'newsMini'){
		echo('
		<div style="border:#15a4d0 1px solid; padding:0px; width:40px; height:18px; position:relative; cursor:pointer;" onclick=\'alert("生成电脑版静态页结果\n'. $failStr .'");\' title="点击查看详情">
			<div style="z-index:8; width:'. ($infoScore*38) .'px; position:absolute; height:18px; background:url(inc_img/smallLineBg.jpg); font-size:1px;">&ensp;</div>
			<div style="z-index:88; width:37px; position:absolute; color:#000000; line-height:18px; text-align:center; font-size:12px; padding-left:0px;">'. $htmlNum .'/'. $htmlCount .'
			');
			if ($htmlFail > 0){
				echo('<span style="color:red;" title="失败数">['. $htmlFail .']</span></div>');
			}
		echo('
		</div>
		');

	}else{
		echo('
		<div style="border:#15a4d0 1px solid; padding:0px; width:198px; height:18px; position:relative; cursor:pointer;" onclick=\'alert("'. $failStr .'");\' title="点击查看详情">
			<div style="z-index:8; width:'. ($infoScore*198) .'px; position:absolute; height:18px; background:url(inc_img/smallLineBg.jpg); font-size:1px;">&ensp;</div>
			<div style="z-index:88; width:197px; position:absolute; color:#000000; line-height:18px; text-align:right; font-size:12px; padding-left:0px;">'. $htmlNum .'/'. $htmlCount .'<span style="color:red;" title="失败数">['. $htmlFail .']</span>('. ($infoScore*100) .'%)</div>
			<div style="z-index:88; position:absolute; padding-left:2px; color:#000000; line-height:18px; text-align:left; font-size:12px;">PC：'. $showHtmlPath .'</div>
		</div>
		<script language="javascript" type="text/javascript">
		try { parent.num --; }catch (e) {}
		</script>
		');
		
	}
}


function GetSession($str){
	global $sessID;
	if (empty($sessID)){ $sessID = OT_SiteID; }

	$retStr = @$_SESSION[$sessID .'make_'. $str];
	if (strlen($retStr)==0){
		$retStr = @$_COOKIE['make_html'];
	}
	return $retStr;
}


function SetSession($str,$value){
	global $sessID;
	if (empty($sessID)){ $sessID = OT_SiteID; }

	$_SESSION[$sessID .'make_'. $str] = $value;
	// setcookie('make_html', $value);
}

?>
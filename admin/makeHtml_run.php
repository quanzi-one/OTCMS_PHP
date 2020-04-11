<?php
require(dirname(__FILE__) .'/check.php');


/* **** 版权所有 ****

 网钛CMS(OTCMS)
 程序开发：网钛科技
 官方网站：http://otcms.com
 联系ＱＱ：877873666
 联系邮箱：877873666@qq.com

 **** 版权所有 **** */


//用户检测
$MB->Open('','login');



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

	case 'sitemapHtml':
		SitemapHtml();
		break;

	case 'subHtml':
		if (! AppBase::Jud()){ exit; }
		SubHtml();
		break;

	default:
		die('err');
}

$MB->Close();
$DB->Close();





function HomeHtml(){
	global $DB,$tpl,$systemArr,$userSysArr,$infoSysArr,$tplSysArr;

	if ($systemArr['SYS_isHtmlHome'] == 0){
		JS::AlertEnd('首页静态页尚未开启.\n\n请到【常规设置】->【网站参数设置】-[文章路径]里开启.');
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
		JS::AlertEnd('写入电脑版index.html文件失败。1.请检查根目录是否有写入修改权限；2.空间是否满了；3.服务器C盘是否满了.');
	}
	
	$DB->query('update '. OT_dbPref .'autoRunSys set ARS_htmlHomeTime='. $DB->ForTime($todayTime));

	$Cache = new Cache();
	$Cache->Php('autoRunSys');
	$Cache->Js('autoRunSys');

	echo('
	<script language="javascript" type="text/javascript">
	try {
		parent.$id("homeHtmlTime").innerHTML="'. $todayTime .'";
	}catch (e) {}
	alert("生成电脑版首页静态页成功.");
	</script>
	');

}



function SitemapHtml(){
	global $DB,$tpl,$systemArr,$userSysArr,$infoSysArr,$tplSysArr;

	$todayTime = TimeDate::Get();

	$tpl->webTypeName	= 'sitemap';
	$tpl->webTitleAddi	= '*';
	$tpl->webKey		= '*';
	$tpl->webDesc		= '*';

	$tpl->WebTop();

	$retStr = '';
	$menuexe = $DB->query('select IT_ID,IT_theme,IT_mode,IT_URL,IT_isEncUrl,IT_webID,IT_openMode,IT_level,IT_htmlName from '. OT_dbPref .'infoType where IT_state=1 and IT_isMenu=1 order by IT_rank ASC');
		while ($row = $menuexe->fetch()){
			$hrefStr = Area::InfoTypeUrl(array(
				'IT_mode'		=> $row['IT_mode'],
				'IT_ID'			=> $row['IT_ID'],
				'IT_webID'		=> $row['IT_webID'],
				'IT_URL'		=> $row['IT_URL'],
				'IT_isEncUrl'	=> $row['IT_isEncUrl'],
				'IT_htmlName'	=> $row['IT_htmlName'],
				'mainUrl'		=> '',
				'webPathPart'	=> $tpl->webPathPart,
				));

			$retStr .= '
			<div class="linkbox">
				<h3><a href="'. $hrefStr .'" target="'. $row['IT_openMode'] .'">'. $row['IT_theme'] .'</a></h3>
				';

			if ($row['IT_level'] == 1){
				$menu2exe = $DB->query('select IT_ID,IT_theme,IT_mode,IT_URL,IT_isEncUrl,IT_webID,IT_openMode,IT_htmlName from '. OT_dbPref .'infoType where IT_state=1 and IT_fatID='. $row['IT_ID'] .' and IT_isSubMenu=1 order by IT_rank ASC');
					if ($row2 =  $menu2exe->fetch()){
						$retStr .= '<ul>';

						do {
							$hrefStr = Area::InfoTypeUrl(array(
								'IT_mode'		=> $row2['IT_mode'],
								'IT_ID'			=> $row2['IT_ID'],
								'IT_webID'		=> $row2['IT_webID'],
								'IT_URL'		=> $row2['IT_URL'],
								'IT_isEncUrl'	=> $row2['IT_isEncUrl'],
								'IT_htmlName'	=> $row2['IT_htmlName'],
								'mainUrl'		=> '',
								'webPathPart'	=> $tpl->webPathPart,
								));
							$retStr .= '<li><a href="'. $hrefStr .'" target="'. $row2['IT_openMode'] .'">'. $row2['IT_theme'] .'</a></li>';
						}while ($row2 =  $menu2exe->fetch());

						$retStr .= '</ul><div class="clr"></div>';
					}
				$menu2exe=null;
			}

			$retStr .= '
			</div>
			<div class="clr"></div>
			';
		}
	unset($menuexe);

	$tpl->Add('sitemapContent',		$retStr);

	$tempContent = $tpl->GetShow('sitemap.html');

	if (! File::Write(OT_ROOT .'sitemap.html', $tempContent . PHP_EOL .'<!-- Html For '. $todayTime .' -->')){
		JS::AlertEnd('写入电脑版sitemap.html文件失败，请检查根目录或者该文件是否有写入权限');
	}
	
	echo('
	<script language="javascript" type="text/javascript">
	alert("生成电脑版sitemap.html成功.");
	</script>
	');

}



function SubHtml(){
	global $DB,$tpl,$GB_WebHost,$GB_JsHost,$systemArr,$userSysArr,$infoSysArr,$tplSysArr;

	$htmlName		= OT::GetStr('htmlName');
	$htmlMode		= OT::GetStr('htmlMode');
	$htmlEachSec	= OT::GetFloat('htmlEachSec');
	$htmlEachNum	= OT::GetInt('htmlEachNum');
		if ($htmlEachNum <= 0){ $htmlEachNum = 1; }
	$htmlPref		= OT::GetStr('htmlPref');
	$htmlMore		= OT::GetStr('htmlMore');
	$htmlCount		= OT::GetInt('htmlCount');
	$htmlNum		= OT::GetInt('htmlNum');
	$htmlSucc		= OT::GetInt('htmlSucc');
	$htmlFail		= OT::GetInt('htmlFail');
	$htmlRecordNum	= OT::GetInt('htmlRecordNum');
	$htmlPageNum	= OT::GetInt('htmlPageNum');
	$typeID			= OT::GetInt('typeID',-99);
	$rateID			= OT::GetInt('rateID');

	$todayTime		= TimeDate::Get();
	$infoIdArr		= explode(',',GetSession($htmlName .'htmlIdStr'));
	$infoPageArr	= explode(',',GetSession($htmlName .'htmlPageStr'));
	$infoDirArr		= explode(',',GetSession($htmlName .'htmlDirStr'));
	$infoCount		= count($infoIdArr) - 1;
	
	if ($infoCount < 1){
		if ($htmlMore == 'true'){
			echo('
			<script language="javascript" type="text/javascript">
			parent.RateDealNext();
			</script>
			');
		}
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
			// die($tpl->webTypeName .'|'. $tpl->queryStr .'|'. GetSession($htmlName .'htmlIdStr') .'|'. $htmlPageNum .'|'. $infoIdArr[$htmlRecordNum] .'|'. GetSession($htmlName .'htmlPageStr'));
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
		$judArs = false;
		$currIdStr = implode(',',array_unique(array_filter($currIdArr)));
		if ($tpl->webTypeName == 'list'){
			foreach ($pageArr as $val){
				if ($val['id'] == 'announ'){
					if ($val['num'] >= $val['total']){
						$DB->query('update '. OT_dbPref .'autoRunSys set ARS_announTime='. $DB->ForTime($todayTime) .',ARS_announCurrNum=0,ARS_announMaxNum=0');
					}else{
						$DB->query('update '. OT_dbPref .'autoRunSys set ARS_announCurrNum='. $val['num'] .',ARS_announMaxNum='. $val['total'] .'');
					}
					$judArs = true;
				}elseif ($val['id'] == 'new'){
					if ($val['num'] >= $val['total']){
						$DB->query('update '. OT_dbPref .'autoRunSys set ARS_newTime='. $DB->ForTime($todayTime) .',ARS_newCurrNum=0,ARS_newMaxNum=0');
					}else{
						$DB->query('update '. OT_dbPref .'autoRunSys set ARS_newCurrNum='. $val['num'] .',ARS_newMaxNum='. $val['total'] .'');
					}
					$judArs = true;
				}elseif (intval($val['id']) > 0){
					if ($val['num'] >= $val['total']){
						$DB->query('update '. OT_dbPref .'infoType set IT_htmlTime='. $DB->ForTime($todayTime) .',IT_htmlCurrNum=0,IT_htmlMaxNum=0 where IT_ID='. $val['id'] .'');
					}else{
						$DB->query('update '. OT_dbPref .'infoType set IT_htmlCurrNum='. $val['num'] .',IT_htmlMaxNum='. $val['total'] .' where IT_ID='. $val['id'] .'');
					}
				}
			}
			// $DB->query('update '. OT_dbPref .'infoType set IT_htmlTime='. $DB->ForTime($todayTime) .' where IT_ID in ('. $currIdStr .')');

		}elseif ($tpl->webTypeName == 'web'){
			$DB->query('update '. OT_dbPref .'infoWeb set IW_htmlTime='. $DB->ForTime($todayTime) .' where IW_ID in ('. $currIdStr .')');

		}elseif ($tpl->webTypeName == 'show'){
			$DB->query('update '. OT_dbPref .'info set IF_htmlTime='. $DB->ForTime($todayTime) .' where IF_ID in ('. $currIdStr .')');

		}

		if ($judArs){
			$Cache = new Cache();
			$Cache->Php('autoRunSys');
			$Cache->Js('autoRunSys');
		}
	}

	$failStr = '';
	if ($htmlNum < $htmlCount){
		echo('
		<script language="javascript" type="text/javascript">
		setTimeout(\'document.location.href="makeHtml_run.php?mudi=subHtml&htmlName='. $htmlName .'&htmlMode='. $htmlMode .'&htmlEachSec='. $htmlEachSec .'&htmlEachNum='. $htmlEachNum .'&htmlPref='. $htmlPref .'&htmlMore='. $htmlMore .'&htmlCount='. $htmlCount .'&htmlNum='. $htmlNum .'&htmlSucc='. $htmlSucc .'&htmlFail='. $htmlFail .'&htmlRecordNum='. $htmlRecordNum .'&htmlPageNum='. $htmlPageNum .'&typeID='. $typeID .'&rateID='. $rateID .'&rnd='. time() .'";\','. ($htmlEachSec*1000) .');
		</script>
		');
	}else{
		if (strpos('|diy|newsMini|newsOne|','|'. $htmlMode .'|') === false){
			if ($htmlMore == 'true'){
				echo('
				<script language="javascript" type="text/javascript">
				parent.RateDealNext();
				</script>
				');
			}
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
	if ($htmlMode == 'diy'){
		echo('
		<div style="border:#15a4d0 1px solid; padding:0px; width:698px; height:28px; position:relative; cursor:pointer;" onclick=\'alert("'. $failStr .'");\' title="点击查看详情">
			<div style="z-index:8; width:'. ($infoScore*698) .'px; position:absolute; height:28px; background:url(images/lineBg.jpg); font-size:1px;">&ensp;</div>
			<div style="z-index:88; position:absolute; color:#000000; line-height:30px; text-align:left; font-size:14px; padding-left:370px;">'. $htmlNum .'/'. $htmlCount .'('. ($infoScore*100) .'%)&ensp;&ensp;<span style="font-size:12px;">（记录：'. $infoCount .'，<span style="color:green;">成功：'. $htmlSucc .'</span>，<span style="color:red;">失败：'. $htmlFail .'</span>）</span></div>
			<div style="z-index:88; position:absolute; padding-left:8px; color:#000000; line-height:30px; text-align:left; font-size:12px;">PC电脑版：'. str_replace(OT_ROOT,'',$makeHtmlPath) .'...'. $makeResult .'</div>
		</div>
		');

	}elseif ($htmlMode == 'newsMini'){
		echo('
		<div style="border:#15a4d0 1px solid; padding:0px; width:58px; height:18px; position:relative; cursor:pointer;" onclick=\'alert("生成电脑版静态页结果\n'. $failStr .'");\' title="点击查看详情">
			<div style="z-index:8; width:'. ($infoScore*58) .'px; position:absolute; height:18px; background:url(images/smallLineBg.jpg); font-size:1px;">&ensp;</div>
			<div style="z-index:88; width:55px; position:absolute; color:#000000; line-height:18px; text-align:center; font-size:12px; padding-left:0px;">PC '. $htmlNum .'/'. $htmlCount .'
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
			<div style="z-index:8; width:'. ($infoScore*198) .'px; position:absolute; height:18px; background:url(images/smallLineBg.jpg); font-size:1px;">&ensp;</div>
			<div style="z-index:88; width:197px; position:absolute; color:#000000; line-height:18px; text-align:right; font-size:12px; padding-left:0px;">'. $htmlNum .'/'. $htmlCount .'<span style="color:red;" title="失败数">['. $htmlFail .']</span>('. ($infoScore*100) .'%)</div>
			<div style="z-index:88; position:absolute; padding-left:2px; color:#000000; line-height:18px; text-align:left; font-size:12px;">PC：'. $showHtmlPath .'</div>
		</div>
		<script language="javascript" type="text/javascript">
		try { parent.num --; }catch (e) {}
		</script>
		');
		
	}
	if ($htmlMode == 'list'){ $htmlModeCN='列'; }else{ $htmlModeCN='内'; }
	if ($rateID >= -1){
		echo('
		<script language="javascript" type="text/javascript">
		try {
			parent.document.getElementById("RateProcess'. $rateID .'").innerHTML=\'<span style="color:red;">'. $htmlModeCN .'</span>&ensp;'. $htmlNum .'\';
		}catch (e) {}
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
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


	$menuFileID = 40;
	$MB->IsSecMenuRight('alertBack',$menuFileID,$dataType);

switch($mudi){
	case 'companyInfo':
		companyInfo();
		break;

	case 'updateCacheTime':
		UpdateCacheTime();
		break;

	case 'calcCacheNum':
		CalcCacheNum();
		break;

	case 'clearCache':
		ClearCache();
		break;

	default:
		die('err');
}

$MB->Close();
$DB->Close();





function companyInfo(){
	global $DB,$skin,$mudi,$menuFileID,$menuTreeID;

	$backURL		= OT::PostStr('backURL');
	$dataType		= OT::PostRegExpStr('dataType','sql');
	$dataTypeCN		= OT::PostStr('dataTypeCN');
	
	$eventStrOld		= OT::PostStr('eventStrOld');
	$isHtmlHomeOld		= OT::PostInt('isHtmlHomeOld');
	$htmlUrlDelOld		= OT::PostStr('htmlUrlDelOld');
	$htmlInfoTypeDirOld	= OT::PostInt('htmlInfoTypeDirOld');
	$htmlDatetimeDirOld	= OT::PostInt('htmlDatetimeDirOld');

	$title				= OT::PostStr('title');
	$titleSign			= OT::Post('titleSign');
	$titleAddi			= OT::PostRStr('titleAddi');
	$isUrl301			= OT::PostInt('isUrl301');
	$webURL				= OT::PostStr('webURL');
		if (in_array($webURL,array('http://','https://'))){
			$webURL = '';
		}elseif ($webURL != ''){
			if (! Is::HttpUrl($webURL)){ $webURL = GetUrl::HttpHead() . $webURL; }
			if (substr($webURL,-1) != '/'){ $webURL .= '/'; }
		}
		if ($isUrl301 == 2){ $webURL=''; }
	$webKey				= OT::PostStr('webKey');
	$webDesc			= OT::PostStr('webDesc');
	$isFloatAd			= OT::PostInt('isFloatAd');
	$isClose			= OT::PostInt('isClose');
	$closeNote			= Adm::FilterEditor(OT::PostStr('closeNote'));
	$dbCharset			= OT::PostStr('dbCharset');
	$isLogErr			= OT::PostInt('isLogErr');
	$isTplErr			= OT::PostInt('isTplErr');
	$isAjaxErr			= OT::PostInt('isAjaxErr');
	$isTimeInfo			= OT::PostInt('isTimeInfo');
	$isBaiduSitemap		= OT::PostInt('isBaiduSitemap');
	$verCodeMode		= OT::PostInt('verCodeMode');
	$verCodeStr			= OT::Post('verCodeStr');
		if (is_array($verCodeStr)){ $verCodeStr = implode(',',$verCodeStr); }
	$geetestID			= OT::PostStr('geetestID');
	$geetestKey			= OT::PostStr('geetestKey');
	$templateDir		= OT::PostStr('templateDir');
	$htmlCacheMin		= OT::PostInt('htmlCacheMin');
	$eventStr			= OT::Post('eventStr');
		if (is_array($eventStr)){ $eventStr = implode(',',$eventStr); }

	$titleHome			= OT::PostStr('titleHome');
	$titleList			= OT::PostStr('titleList');
	$titleShow			= OT::PostStr('titleShow');
	$titleWeb			= OT::PostStr('titleWeb');
	$titleSearch		= OT::PostStr('titleSearch');
	$titleMark			= OT::PostStr('titleMark');
	$titleTopic			= OT::PostStr('titleTopic');
	$titleBbsHome		= OT::PostStr('titleBbsHome');
	$titleBbsList		= OT::PostStr('titleBbsList');
	$titleBbsShow		= OT::PostStr('titleBbsShow');
	$titleBbsSearch		= OT::PostStr('titleBbsSearch');

	$announName			= OT::PostStr('announName');
	$proxyIpList		= OT::PostStr('proxyIpList');

	$isHtmlHome			= OT::PostInt('isHtmlHome');

	$htmlUrlDel			= OT::Post('htmlUrlDel');
		if (is_array($htmlUrlDel)){ $htmlUrlDel = implode(',',$htmlUrlDel); }
	$htmlUrlSel			= OT::PostInt('htmlUrlSel');
	$htmlUrlJump		= OT::PostInt('htmlUrlJump');
	$htmlUrlDir			= OT::PostRegExpStr('htmlUrlDir','abcnum_');
	$diyInfoTypeDir		= OT::PostInt('diyInfoTypeDir');
	$htmlInfoTypeDir	= OT::PostInt('htmlInfoTypeDir');
	$htmlDatetimeDir	= OT::PostInt('htmlDatetimeDir');
	$newsListUrlMode	= OT::PostStr('newsListUrlMode');
	$newsListFileName	= OT::PostStr('newsListFileName');
	$newsShowUrlMode	= OT::PostStr('newsShowUrlMode');
	$newsShowFileName	= OT::PostStr('newsShowFileName');
	$dynWebUrlMode		= OT::PostStr('dynWebUrlMode');
	$dynWebFileName		= OT::PostStr('dynWebFileName');
		if (strpos('|admin|cache|inc|inc_img|inc_temp|install|js|temp|template|tools|upFiles|announ|new||','|'. $htmlUrlDir .'|') !== false){
			$htmlUrlDir		= 'news';
			if ($newsListUrlMode == 'html-2.x'){ $newsListFileName = 'news'; }
			if ($newsShowUrlMode == 'html-2.x'){ $newsShowFileName = 'news'; }
			if ($dynWebUrlMode == 'html-2.x'){ $dynWebFileName = 'news'; }
		}

	$sign				= OT::PostStr('sign');
	$authState			= OT::PostStr('authState');

	if ($backURL=='' || $title=='' || ($sign=='' && $authState!='false') ){
		JS::AlertBackEnd('表单内容接收不全');
	}

	$record=array();
	$record['SYS_title']			= $title;
	$record['SYS_titleSign']		= $titleSign;
	$record['SYS_titleAddi']		= $titleAddi;
	$record['SYS_isUrl301']			= $isUrl301;
	$record['SYS_URL']				= $webURL;
	$record['SYS_webKey']			= $webKey;
	$record['SYS_webDesc']			= $webDesc;
	$record['SYS_isFloatAd']		= $isFloatAd;
	$record['SYS_isClose']			= $isClose;
	$record['SYS_closeNote']		= $closeNote;
	$record['SYS_dbCharset']		= $dbCharset;
	$record['SYS_isLogErr']			= $isLogErr;
	$record['SYS_isTplErr']			= $isTplErr;
	$record['SYS_isAjaxErr']		= $isAjaxErr;
	$record['SYS_isTimeInfo']		= $isTimeInfo;
	$record['SYS_isBaiduSitemap']	= $isBaiduSitemap;
	$record['SYS_verCodeMode']		= $verCodeMode;
	$record['SYS_verCodeStr']		= $verCodeStr;
	$record['SYS_geetestID']		= $geetestID;
	$record['SYS_geetestKey']		= $geetestKey;
	$record['SYS_htmlCacheMin']		= $htmlCacheMin;
	$record['SYS_eventStr']			= $eventStr;

	$record['SYS_titleHome']		= $titleHome;
	$record['SYS_titleList']		= $titleList;
	$record['SYS_titleShow']		= $titleShow;
	$record['SYS_titleWeb']			= $titleWeb;
	$record['SYS_titleSearch']		= $titleSearch;
	$record['SYS_titleMark']		= $titleMark;
	$record['SYS_titleTopic']		= $titleTopic;
	if (AppBbs::Jud()){
		$record['SYS_titleBbsHome']		= $titleBbsHome;
		$record['SYS_titleBbsList']		= $titleBbsList;
		$record['SYS_titleBbsShow']		= $titleBbsShow;
		$record['SYS_titleBbsSearch']	= $titleBbsSearch;
	}
	if (! AppWap::Jud()){
		$record['SYS_isWap']			= 0;
	}

	$record['SYS_announName']		= $announName;

	$record['SYS_isHtmlHome']		= $isHtmlHome;
	$record['SYS_jsTimeStr']		= TimeDate::Get('YmdHis');

	if ($authState != 'false'){
		$record['SYS_proxyIpList']			= $proxyIpList;

		$record['SYS_htmlUrlDel']			= $htmlUrlDel;
//		$record['SYS_htmlUrlSel']			= $htmlUrlSel;
		$record['SYS_htmlUrlJump']			= $htmlUrlJump;
		$record['SYS_htmlUrlDir']			= $htmlUrlDir;
		$record['SYS_diyInfoTypeDir']		= $diyInfoTypeDir;
		$record['SYS_htmlInfoTypeDir']		= $htmlInfoTypeDir;
		$record['SYS_htmlDatetimeDir']		= $htmlDatetimeDir;
		$record['SYS_newsListUrlMode']		= $newsListUrlMode;
		$record['SYS_newsListFileName']		= $newsListFileName;
		$record['SYS_newsShowUrlMode']		= $newsShowUrlMode;
		$record['SYS_newsShowFileName']		= $newsShowFileName;
		$record['SYS_dynWebUrlMode']		= $dynWebUrlMode;
		$record['SYS_dynWebFileName']		= $dynWebFileName;
	}

	$judResult = $DB->UpdateParam('system',$record,'SYS_ID=1');
	if (! $judResult){
		JS::AlertBackEnd('保存失败.'. $judResult);
	}

	$fileResultStr = '';
	if ($htmlUrlDelOld != $htmlUrlDel){
		$robotsSysStr = '';
		if (strpos($htmlUrlDel,'|otcms_dyn-2.x|') !== false){
			$robotsSysStr .= ''.
				'# 屏蔽网钛动态路径'. PHP_EOL .
				'Disallow: /news/?'. PHP_EOL .
				'';
		}
		if (strpos($htmlUrlDel,'|otcms_html-2.x|') !== false){
			$robotsSysStr .= ''.
				'# 屏蔽网钛纯静态路径'. PHP_EOL .
				'Disallow: /news/'. PHP_EOL .
				'';
		}
		if (strlen($robotsSysStr) == 0){ $robotsSysStr = '# '. PHP_EOL; }

		$robotsStart	= '# *** 程序自动生成区 START (请勿删除该行) ***'. PHP_EOL;
		$robotsEnd		= '# *** 程序自动生成区 END (请勿删除该行) ***';
		$robotsStr = File::Read(OT_ROOT .'robots.txt');
			if (strlen($robotsStr) == 0){
				$robotsStr = 'User-agent: *'. PHP_EOL . $robotsStart . $robotsSysStr . $robotsEnd;
			}else{
				$robotsReStr = Str::GetMarkEnd($robotsStr, $robotsStart, $robotsEnd,true,true);
				if (strlen($robotsReStr) > 0){
					$robotsStr = str_replace($robotsReStr, $robotsStart . $robotsSysStr . $robotsEnd, $robotsStr);
				}else{
					$robotsStr .= PHP_EOL . $robotsStart . $robotsSysStr . $robotsEnd;
				}
			}
		$isRobots = File::Write(OT_ROOT .'robots.txt', $robotsStr);
		if (! $isRobots){
			$fileResultStr .= '\n../robots.txt 修改失败，请检查该目录或者文件是否有写入/修改权限！';
		}
	}

	$httpdStr = $htaccessStr = $htaccessWapStr = $nginxConfStr = $webConfigStr = $webConfigWapStr = '';
	$webConfigStr .= '<!-- 专门用来检测是否支持伪静态 -->'. PHP_EOL .
					'<rule name="chk">'. PHP_EOL .
					'	<match url="readSoft.html" ignoreCase="true"/>'. PHP_EOL .
					'	<action type="Rewrite" url="/readSoft.php" />'. PHP_EOL .
					'</rule>'. PHP_EOL;
	if (AppWap::Jud()){
		$wapRow = $DB->GetRow('select WAP_domainMode,WAP_domainUrl from '. OT_dbPref .'wap');
		if ($wapRow['WAP_domainMode'] == 1 && strlen($wapRow['WAP_domainUrl']) > 9 && strpos($eventStr,'|wapDomain|') !== false){
			$wapDomain = str_replace(array('http://','https://','/'), '', strtolower($wapRow['WAP_domainUrl']));	// (www.)?
			$htaccessStr .= '# 手机版用独立域名绑定'. PHP_EOL .
							'RewriteCond %{HTTP_HOST} ^'. $wapDomain .'$'. PHP_EOL .
							'RewriteCond %{REQUEST_URI} !^/wap/'. PHP_EOL .
							'# RewriteCond %{REQUEST_FILENAME} !-f'. PHP_EOL .
							'RewriteCond %{REQUEST_FILENAME} !-d'. PHP_EOL .
							'RewriteRule ^(.*)$ /wap/$1'. PHP_EOL .
							'RewriteCond %{HTTP_HOST} ^'. $wapDomain .'$'. PHP_EOL .
							'RewriteRule ^(/)?$ wap/ [L]'. PHP_EOL;
			$webConfigStr .= '<!-- 手机版用独立域名绑定 -->'. PHP_EOL .
							'<rule name="wap">'. PHP_EOL .
							'	<match url="^.*$" ignoreCase="false"/>'. PHP_EOL .
							'	<conditions><add input="{HTTP_HOST}" pattern="^'. $wapDomain .'$"/></conditions>'. PHP_EOL .
							'	<action type="Rewrite" url="wap/{R:0}" appendQueryString="true"/>'. PHP_EOL .
							'</rule>'. PHP_EOL;
		}
	}else{
		$wapRow = array('WAP_domainMode'=>0,'WAP_domainUrl'=>'');
	}

	$currDomain = GetUrl::Domain(GetUrl::Main());
	$domain3w = $domainNo3w = '';
	if (substr($currDomain,0,4) == 'www.'){
		$domain3w = $currDomain;
		$domainNo3w = substr($currDomain,4);
	}else{
		$domain3w = 'www.'. $currDomain;
		$domainNo3w = $currDomain;
	}
	if (strpos($eventStr,'|no3wTo3w|') !== false){
		$htaccessStr .= '# 不带www跳转到带www'. PHP_EOL .
						'RewriteCond %{HTTP_HOST} ^'. str_replace(array('.','-'),array('\.','\-'),$domainNo3w) .'$ [NC]'. PHP_EOL .
						'RewriteRule ^(.*)$ http://'. $domain3w .'/$1 [L,R=301]'. PHP_EOL;
		$webConfigStr .= '<!-- 不带3W域名301重定向到带3W -->'. PHP_EOL .
						'<rule name="no3wTo3w" stopProcessing="true">'. PHP_EOL .
						'	<match url=".*" />'. PHP_EOL .
						'	<conditions>'. PHP_EOL .
						'		<add input="{HTTP_HOST}" pattern="^'. $domainNo3w .'$" />'. PHP_EOL .
						'	</conditions>'. PHP_EOL .
						'	<action type="Redirect" url="http://'. $domain3w .'/{R:0}" redirectType="Permanent" />'. PHP_EOL .
						'</rule>'. PHP_EOL;
	}elseif (strpos($eventStr,'|3wToNo3w|') !== false){
		$htaccessStr .= '# 带www跳转到不带www'. PHP_EOL .
						'RewriteCond %{HTTP_HOST} ^'. str_replace(array('.','-'),array('\.','\-'),$domain3w) .'$ [NC]'. PHP_EOL .
						'RewriteRule ^(.*)$ http://'. $domainNo3w .'/$1 [L,R=301]'. PHP_EOL;
		$webConfigStr .= '<!-- 不带3W域名301重定向到带3W -->'. PHP_EOL .
						'<rule name="toNo3w" stopProcessing="true">'. PHP_EOL .
						'	<match url=".*" />'. PHP_EOL .
						'	<conditions>'. PHP_EOL .
						'		<add input="{HTTP_HOST}" pattern="^'. $domain3w .'$" />'. PHP_EOL .
						'	</conditions>'. PHP_EOL .
						'	<action type="Redirect" url="http://'. $domainNo3w .'/{R:0}" redirectType="Permanent" />'. PHP_EOL .
						'</rule>'. PHP_EOL;
	}
	if (strpos($eventStr,'|httpToHttps|') !== false){
		$htaccessStr .= '# http 跳转到 https'. PHP_EOL .
						'RewriteCond %{SERVER_PORT} !^443$'. PHP_EOL .
						'RewriteRule ^.*$ https://%{SERVER_NAME}%{REQUEST_URI} [L,R=301]'. PHP_EOL;
		$webConfigStr .= '<!-- http 跳转到 https -->'. PHP_EOL .
						'<rule name="httpToHttps" stopProcessing="true">'. PHP_EOL .
						'	<match url="(.*)" />'. PHP_EOL .
						'	<conditions>'. PHP_EOL .
						'		<add input="{HTTPS}" pattern="off" ignoreCase="true" />'. PHP_EOL .
						'	</conditions>'. PHP_EOL .
						'	<action type="Redirect" redirectType="Permanent" url="https://{HTTP_HOST}/{R:1}" />'. PHP_EOL .
						'</rule>'. PHP_EOL;
	}

	if ($newsListUrlMode == 'static-3.x'){
		$httpdStr .= '# PC端列表页和搜索页'. PHP_EOL .'RewriteRule /news/list_([a-zA-Z0-9_\-\%]*).html /news/\?list_$1.html&static'. PHP_EOL;
		$htaccessStr .= '# PC端列表页和搜索页'. PHP_EOL .'RewriteRule ^news/list_([a-zA-Z0-9_\-\%]*).html$ news/\?list_$1.html&static'. PHP_EOL;
		$htaccessWapStr .= '# PC端列表页和搜索页'. PHP_EOL .'RewriteRule ^news/list_([a-zA-Z0-9_\-\%]*).html$ news/\?list_$1.html&static'. PHP_EOL;
		$nginxConfStr .= '# PC端列表页和搜索页'. PHP_EOL .'rewrite ^/news/list_([a-zA-Z0-9_\-\%]*).html$ /news/?list_$1.html&static;'. PHP_EOL;
		$webConfigStr .= '<!-- PC端列表页和搜索页 -->'. PHP_EOL .
						'<rule name="list">'. PHP_EOL .
						'	<match url="^news/list_([a-zA-Z0-9_\-\%]*).html" ignoreCase="true"/>'. PHP_EOL .
						'	<action type="Rewrite" url="/news/\?list_{R:1}.html&amp;static" />'. PHP_EOL .
						'</rule>'. PHP_EOL;
		$webConfigWapStr .= '<!-- PC端列表页和搜索页 -->'. PHP_EOL .
						'<rule name="list">'. PHP_EOL .
						'	<match url="^news/list_([a-zA-Z0-9_\-\%]*).html" ignoreCase="true"/>'. PHP_EOL .
						'	<action type="Rewrite" url="/news/\?list_{R:1}.html&amp;static" />'. PHP_EOL .
						'</rule>'. PHP_EOL;
		if (AppWap::Jud()){
			$httpdStr .= '# WAP端列表页和搜索页'. PHP_EOL .'RewriteRule /wap/news/list_([a-zA-Z0-9_\-\%]*).html /wap/news/\?list_$1.html&static'. PHP_EOL;
			$htaccessStr .= '# WAP端列表页和搜索页'. PHP_EOL .'RewriteRule ^wap/news/list_([a-zA-Z0-9_\-\%]*).html$ wap/news/\?list_$1.html&static'. PHP_EOL;
			$nginxConfStr .= '# WAP端列表页和搜索页'. PHP_EOL .'rewrite ^/wap/news/list_([a-zA-Z0-9_\-\%]*).html$ /wap/news/?list_$1.html&static;'. PHP_EOL;
			$webConfigStr .= '<!-- WAP端列表页和搜索页 -->'. PHP_EOL .
							'<rule name="listWap">'. PHP_EOL .
							'	<match url="^wap/news/list_([a-zA-Z0-9_\-\%]*).html" ignoreCase="true"/>'. PHP_EOL .
							'	<action type="Rewrite" url="/wap/news/\?list_{R:1}.html&amp;static" />'. PHP_EOL .
							'</rule>'. PHP_EOL;
		}
	}
	if ($newsShowUrlMode == 'static-3.x'){
		$httpdStr .= '# PC端内容页'. PHP_EOL .'RewriteRule /news/([0-9_]*).html /news/\?$1.html&static'. PHP_EOL;
		$htaccessStr .= '# PC端内容页'. PHP_EOL .'RewriteRule ^news/([0-9_]*).html$ news/\?$1.html&static'. PHP_EOL;
		$htaccessWapStr .= '# PC端内容页'. PHP_EOL .'RewriteRule ^news/([0-9_]*).html$ news/\?$1.html&static'. PHP_EOL;
		$nginxConfStr .= '# PC端内容页'. PHP_EOL .'rewrite ^/news/([0-9_]*).html$ /news/?$1.html&static;'. PHP_EOL;
		$webConfigStr .= '<!-- PC端内容页 -->'. PHP_EOL .
						'<rule name="show">'. PHP_EOL .
						'	<match url="^news/([0-9_]*).html" ignoreCase="true"/>'. PHP_EOL .
						'	<action type="Rewrite" url="/news/\?{R:1}.html&amp;static" />'. PHP_EOL .
						'</rule>'. PHP_EOL;
		$webConfigWapStr .= '<!-- PC端内容页 -->'. PHP_EOL .
						'<rule name="show">'. PHP_EOL .
						'	<match url="^news/([0-9_]*).html" ignoreCase="true"/>'. PHP_EOL .
						'	<action type="Rewrite" url="/news/\?{R:1}.html&amp;static" />'. PHP_EOL .
						'</rule>'. PHP_EOL;
		if (AppWap::Jud()){
			$httpdStr .= '# WAP端内容页'. PHP_EOL .'RewriteRule /wap/news/([0-9_]*).html /wap/news/\?$1.html&static'. PHP_EOL;
			$htaccessStr .= '# WAP端内容页'. PHP_EOL .'RewriteRule ^wap/news/([0-9_]*).html$ wap/news/\?$1.html&static'. PHP_EOL;
			$nginxConfStr .= '# WAP端内容页'. PHP_EOL .'rewrite ^/wap/news/([0-9_]*).html$ /wap/news/?$1.html&static;'. PHP_EOL;
			$webConfigStr .= '<!-- WAP端内容页 -->'. PHP_EOL .
							'<rule name="showWap">'. PHP_EOL .
							'	<match url="^wap/news/([0-9_]*).html" ignoreCase="true"/>'. PHP_EOL .
							'	<action type="Rewrite" url="/wap/news/\?{R:1}.html&amp;static" />'. PHP_EOL .
							'</rule>'. PHP_EOL;
		}
	}
	if ($dynWebUrlMode == 'static-3.x'){
		$httpdStr .= '# PC端单篇页'. PHP_EOL .'RewriteRule /news/web_([0-9_]*).html /news/\?web_$1.html&static'. PHP_EOL;
		$htaccessStr .= '# PC端单篇页'. PHP_EOL .'RewriteRule ^news/web_([0-9_]*).html$ news/\?web_$1.html&static'. PHP_EOL;
		$htaccessWapStr .= '# PC端单篇页'. PHP_EOL .'RewriteRule ^news/web_([0-9_]*).html$ news/\?web_$1.html&static'. PHP_EOL;
		$nginxConfStr .= '# PC端单篇页'. PHP_EOL .'rewrite ^/news/web_([0-9_]*).html$ /news/?web_$1.html&static;'. PHP_EOL;
		$webConfigStr .= '<!-- PC端单篇页 -->'. PHP_EOL .
						'<rule name="dynWeb">'. PHP_EOL .
						'	<match url="^news/web_([0-9_]*).html" ignoreCase="true"/>'. PHP_EOL .
						'	<action type="Rewrite" url="/news/\?web_{R:1}.html&amp;static" />'. PHP_EOL .
						'</rule>'. PHP_EOL;
		$webConfigWapStr .= '<!-- PC端单篇页 -->'. PHP_EOL .
						'<rule name="dynWeb">'. PHP_EOL .
						'	<match url="^news/web_([0-9_]*).html" ignoreCase="true"/>'. PHP_EOL .
						'	<action type="Rewrite" url="/news/\?web_{R:1}.html&amp;static" />'. PHP_EOL .
						'</rule>'. PHP_EOL;
		if (AppWap::Jud()){
			$httpdStr .= '# WAP端单篇页'. PHP_EOL .'RewriteRule /wap/news/web_([0-9_]*).html /wap/news/\?web_$1.html&static'. PHP_EOL;
			$htaccessStr .= '# WAP端单篇页'. PHP_EOL .'RewriteRule ^wap/news/web_([0-9_]*).html$ wap/news/\?web_$1.html&static'. PHP_EOL;
			$nginxConfStr .= '# WAP端单篇页'. PHP_EOL .'rewrite ^/wap/news/web_([0-9_]*).html$ /wap/news/?web_$1.html&static;'. PHP_EOL;
			$webConfigStr .= '<!-- WAP端单篇页 -->'. PHP_EOL .
							'<rule name="dynWebWap">'. PHP_EOL .
							'	<match url="^wap/news/web_([0-9_]*).html" ignoreCase="true"/>'. PHP_EOL .
							'	<action type="Rewrite" url="/wap/news/\?web_{R:1}.html&amp;static" />'. PHP_EOL .
							'</rule>'. PHP_EOL;
		}
	}
	if (strlen($httpdStr) == 0){ $httpdStr = '# '. PHP_EOL; }

	$httpdStart	= '# *** 程序自动生成区 START (请勿删除该行) ***';
	$httpdEnd	= '# *** 程序自动生成区 END (请勿删除该行) ***';

	$httpdFile = File::Read(OT_ROOT .'httpd.ini');
		if (strlen($httpdFile) == 0){
			$httpdFile = ''.
				'[ISAPI_Rewrite]'. PHP_EOL .
				PHP_EOL .
				'# 3600 = 1 hour'. PHP_EOL .
				'CacheClockRate 3600'. PHP_EOL .
				PHP_EOL .
				'RepeatLimit 32'. PHP_EOL .
				PHP_EOL .
				'# Protect httpd.ini and httpd.parse.errors files'. PHP_EOL .
				'# from accessing through HTTP'. PHP_EOL .
				PHP_EOL . 
				$httpdStart . PHP_EOL . $httpdStr . $httpdEnd . PHP_EOL;
		}else{
			$httpdReStr = Str::GetMarkEnd($httpdFile, $httpdStart, $httpdEnd, true, true);
			if (strlen($httpdReStr) > 0){
				$httpdFile = str_replace($httpdReStr, $httpdStart . PHP_EOL . $httpdStr . $httpdEnd, $httpdFile);
			}else{
				$httpdFile .= PHP_EOL . $httpdStart . PHP_EOL . $httpdStr . $httpdEnd;
			}
		}
	$isHttpd = File::Write(OT_ROOT .'httpd.ini', $httpdFile);
	if ($isHttpd){
		$fileResultStr .= '\n../httpd.ini 修改成功！';
	}else{
		$fileResultStr .= '\n../httpd.ini 修改失败，请检查该目录或者文件是否有写入/修改权限！';
	}

	$htaccessFile = File::Read(OT_ROOT .'.htaccess');
		if (strlen($htaccessFile) == 0){
			$htaccessFile = ''.
				'<IfModule mod_rewrite.c>'. PHP_EOL .
				PHP_EOL .
				'# 开启URL重写'. PHP_EOL .
				'RewriteEngine On'. PHP_EOL .
				PHP_EOL .
				'# URL重写的作用域'. PHP_EOL .
				'# RewriteBase / '. PHP_EOL .
				PHP_EOL .
				'# http 跳转到 https'. PHP_EOL .
				'# RewriteCond %{SERVER_PORT} !^443$'. PHP_EOL .
				'# RewriteRule ^.*$ https://%{SERVER_NAME}%{REQUEST_URI} [L,R=301]'. PHP_EOL .
				PHP_EOL .
				'# 不带www跳转到带www（yourdomain 改为自己的域名即可）'. PHP_EOL .
				'# RewriteCond %{HTTP_HOST} ^yourdomain\.com$ [NC]'. PHP_EOL .
				'# RewriteRule ^(.*)$ http://www.yourdomain.com/$1 [L,R=301]'. PHP_EOL .
				PHP_EOL .
				'# 屏蔽掉一些目录执行权限'. PHP_EOL .
				'RewriteCond % !^$ '. PHP_EOL .
				'RewriteRule cache/(.*).(php)$ – [F]'. PHP_EOL .
				'RewriteRule html/(.*).(php)$ – [F]'. PHP_EOL .
				'RewriteRule inc_img/(.*).(php)$ – [F]'. PHP_EOL .
				'RewriteRule js/(.*).(php)$ – [F]'. PHP_EOL .
				'RewriteRule pay/(.*).(php)$ – [F]'. PHP_EOL .
				'RewriteRule pluDef/(.*).(php)$ – [F]'. PHP_EOL .
				'RewriteRule plugin/(.*).(php)$ – [F]'. PHP_EOL .
				'RewriteRule smarty/(.*).(php)$ – [F]'. PHP_EOL .
				'RewriteRule temp/(.*).(php)$ – [F]'. PHP_EOL .
				'RewriteRule template/(.*).(php)$ – [F]'. PHP_EOL .
				'RewriteRule tools/(.*).(php)$ – [F]'. PHP_EOL .
				'RewriteRule upFiles/(.*).(php)$ – [F]'. PHP_EOL .
				'RewriteRule web_config/(.*).(php)$ – [F]'. PHP_EOL .
				'RewriteRule wap/cache/(.*).(php)$ – [F]'. PHP_EOL .
				'RewriteRule wap/html/(.*).(php)$ – [F]'. PHP_EOL .
				'RewriteRule wap/images/(.*).(php)$ – [F]'. PHP_EOL .
				'RewriteRule wap/js/(.*).(php)$ – [F]'. PHP_EOL .
				'RewriteRule wap/skin/(.*).(php)$ – [F]'. PHP_EOL .
				'RewriteRule wap/template/(.*).(php)$ – [F]'. PHP_EOL .
				'RewriteRule wap/tools/(.*).(php)$ – [F]'. PHP_EOL .
				'RewriteRule wap/web_config/(.*).(php)$ – [F]'. PHP_EOL .
				PHP_EOL .
				'# 专门用来检测是否支持伪静态（不要删除）'. PHP_EOL .
				'RewriteRule ^readSoft.html$ readSoft.php'. PHP_EOL .
				PHP_EOL .
				$httpdStart . PHP_EOL . $htaccessStr . $httpdEnd . PHP_EOL .
				PHP_EOL .
				'</IfModule>'. PHP_EOL;
		}else{
			$htaccessReStr = Str::GetMarkEnd($htaccessFile, $httpdStart, $httpdEnd, true, true);
			if (strlen($htaccessReStr) > 0){
				$htaccessFile = str_replace($htaccessReStr, $httpdStart . PHP_EOL . $htaccessStr . $httpdEnd, $htaccessFile);
			}elseif (strpos($htaccessFile,$httpdStart) === false && strpos($htaccessFile,$httpdEnd) === false){
				$htaccessFile .= PHP_EOL . $httpdStart . PHP_EOL . $htaccessStr . $httpdEnd;
			}
		}
	$isHtaccess = File::Write(OT_ROOT .'.htaccess', $htaccessFile);
	if ($isHtaccess){
		$fileResultStr .= '\n../.htaccess 修改成功！';
	}else{
		$fileResultStr .= '\n../.htaccess 修改失败，请检查该目录或者文件是否有写入/修改权限！';
	}

	$nginxConfFile = File::Read(OT_ROOT .'nginx.conf');
		if (strlen($nginxConfFile) == 0){
			$nginxConfFile = ''.
				'# 屏蔽掉一些目录执行权限'. PHP_EOL .
				'location ~* ^/(cache|tools|upFiles)/.*\.(php|php5)$'. PHP_EOL .
				'{'. PHP_EOL .
				'	deny all;'. PHP_EOL .
				'}'. PHP_EOL .
				PHP_EOL .
				'# 专门用来检测是否支持伪静态'. PHP_EOL .
				'rewrite ^/readSoft.html$ /readSoft.php;'. PHP_EOL .
				PHP_EOL .
				$httpdStart . PHP_EOL . $nginxConfStr . $httpdEnd . PHP_EOL .
				PHP_EOL;
		}else{
			$nginxConfReStr = Str::GetMarkEnd($nginxConfFile, $httpdStart, $httpdEnd, true, true);
			if (strlen($nginxConfReStr) > 0){
				$nginxConfFile = str_replace($nginxConfReStr, $httpdStart . PHP_EOL . $nginxConfStr . $httpdEnd, $nginxConfFile);
			}elseif (strpos($nginxConfFile,$httpdStart) === false && strpos($nginxConfFile,$httpdEnd) === false){
				$nginxConfFile .= PHP_EOL . $httpdStart . PHP_EOL . $nginxConfStr . $httpdEnd;
			}
		}
	$isNginxConf = File::Write(OT_ROOT .'nginx.conf', $nginxConfFile);
	if ($isNginxConf){
		$fileResultStr .= '\n../nginx.conf 修改成功！';
	}else{
		$fileResultStr .= '\n../nginx.conf 修改失败，请检查该目录或者文件是否有写入/修改权限！';
	}

	$isWebConfig2 = File::Write(OT_ROOT .'web_config/rewrite.config', '<rules>'. PHP_EOL . $webConfigStr .'</rules>');
	if ($isWebConfig2){
		$fileResultStr .= '\n../web_config/rewrite.config 修改成功！';
	}else{
		$fileResultStr .= '\n../web_config/rewrite.config 修改失败，请检查该目录或者文件是否有写入/修改权限！';
	}
	if (strpos($eventStr,'|webConfig|') !== false){
		$webConfigFile = File::Read(OT_ROOT .'web.config');
		if (strpos($webConfigFile,'<rules configSource="web_config\rewrite.config"') !== false){
			$fileResultStr .= '\n../web.config 检测到里面有外部文件 rewrite.config 引用，无需修改！';
		}else{
			if (strlen($webConfigFile) == 0 || strpos($webConfigFile,'<system.webServer>') === false){
				$webConfigFile = ''.
					'<?xml version="1.0" encoding="UTF-8"?>'. PHP_EOL .
					'<configuration>'. PHP_EOL .
					'	<system.webServer>'. PHP_EOL .
					'		<rewrite>'. PHP_EOL .
					'			<rules>'. PHP_EOL .
								$webConfigStr . PHP_EOL .
					'			</rules>'. PHP_EOL .
					'		</rewrite>'. PHP_EOL .
					'	</system.webServer>'. PHP_EOL .
					'</configuration>'. PHP_EOL;
			}else{
				$wcStart = '<rules>';
				$wcEnd = '</rules>';
				$webConfigReStr = Str::GetMarkEnd($webConfigFile, $wcStart, $wcEnd, true, true);
				if (strlen($webConfigReStr) > 0){
					$webConfigFile = str_replace($webConfigReStr, $wcStart . PHP_EOL . $webConfigStr . $wcEnd, $webConfigFile);
				}else{
					if (strpos($webConfigFile,'<rewrite>') !== false){
						$webConfigFile = str_replace('<rewrite>', '<rewrite>'. PHP_EOL . $wcStart . PHP_EOL . $webConfigStr . $wcEnd, $webConfigFile);
					}elseif (strpos($webConfigFile,'<system.webServer>') !== false){
						$webConfigFile = str_replace('<system.webServer>', '<system.webServer>'. PHP_EOL .'<rewrite>'. PHP_EOL . $wcStart . PHP_EOL . $webConfigStr . $wcEnd . PHP_EOL .'</rewrite>', $webConfigFile);
					}
				}
			}
			$isWebConfig = File::Write(OT_ROOT .'web.config', $webConfigFile);
			if ($isWebConfig){
				$fileResultStr .= '\n../web.config 修改成功！';
			}else{
				$fileResultStr .= '\n../web.config 修改失败，请检查该目录或者文件是否有写入/修改权限！';
			}
		}
	}

	if (AppWap::Jud()){
		if ($wapRow['WAP_domainMode'] == 1){
			$htaccessWapFile = File::Read(OT_ROOT .'wap/.htaccess');
				if (strlen($htaccessWapFile) == 0){
					$htaccessWapFile = ''.
						'<IfModule mod_rewrite.c>'. PHP_EOL .
						PHP_EOL .
						'# 开启URL重写'. PHP_EOL .
						'RewriteEngine On'. PHP_EOL .
						PHP_EOL .
						'# URL重写的作用域'. PHP_EOL .
						'# RewriteBase / '. PHP_EOL .
						PHP_EOL .
						'# 屏蔽掉一些目录执行权限'. PHP_EOL .
						'RewriteCond % !^$ '. PHP_EOL .
						'RewriteRule cache/(.*).(php)$ – [F]'. PHP_EOL .
						'RewriteRule html/(.*).(php)$ – [F]'. PHP_EOL .
						'RewriteRule images/(.*).(php)$ – [F]'. PHP_EOL .
						'RewriteRule js/(.*).(php)$ – [F]'. PHP_EOL .
						'RewriteRule skin/(.*).(php)$ – [F]'. PHP_EOL .
						'RewriteRule template/(.*).(php)$ – [F]'. PHP_EOL .
						'RewriteRule tools/(.*).(php)$ – [F]'. PHP_EOL .
						'RewriteRule web_config/(.*).(php)$ – [F]'. PHP_EOL .
						PHP_EOL .
						$httpdStart . PHP_EOL . $htaccessWapStr . $httpdEnd . PHP_EOL .
						PHP_EOL .
						'</IfModule>'. PHP_EOL;
				}else{
					$htaccessReStr = Str::GetMarkEnd($htaccessWapFile, $httpdStart, $httpdEnd, true, true);
					if (strlen($htaccessReStr) > 0){
						$htaccessWapFile = str_replace($htaccessReStr, $httpdStart . PHP_EOL . $htaccessWapStr . $httpdEnd, $htaccessWapFile);
					}elseif (strpos($htaccessWapFile,$httpdStart) === false && strpos($htaccessWapFile,$httpdEnd) === false){
						$htaccessWapFile .= PHP_EOL . $httpdStart . PHP_EOL . $htaccessWapStr . $httpdEnd;
					}
				}
			$isHtaccessWap = File::Write(OT_ROOT .'wap/.htaccess', $htaccessWapFile);
			if ($isHtaccessWap){
				$fileResultStr .= '\n../wap/.htaccess 修改成功！';
			}else{
				$fileResultStr .= '\n../wap/.htaccess 修改失败，请检查该目录或者文件是否有写入/修改权限！';
			}

			if (strpos($eventStr,'|webConfig|') !== false){
				$webConfigWapFile = File::Read(OT_ROOT .'wap/web.config');
					if (strlen($webConfigWapFile) == 0 || strpos($webConfigWapFile,'<system.webServer>') === false){
						$webConfigWapFile = ''.
							'<?xml version="1.0" encoding="UTF-8"?>'. PHP_EOL .
							'<configuration>'. PHP_EOL .
							'	<system.webServer>'. PHP_EOL .
							'		<rewrite>'. PHP_EOL .
							'			<rules>'. PHP_EOL .
										$webConfigWapStr . PHP_EOL .
							'			</rules>'. PHP_EOL .
							'		</rewrite>'. PHP_EOL .
							'	</system.webServer>'. PHP_EOL .
							'</configuration>'. PHP_EOL;
					}else{
						$wcStart = '<rules>';
						$wcEnd = '</rules>';
						$webConfigReStr = Str::GetMarkEnd($webConfigWapFile, $wcStart, $wcEnd, true, true);
						if (strlen($webConfigReStr) > 0){
							$webConfigWapFile = str_replace($webConfigReStr, $wcStart . PHP_EOL . $webConfigWapStr . $wcEnd, $webConfigWapFile);
						}else{
							if (strpos($webConfigWapFile,'<rewrite>') !== false){
								$webConfigWapFile = str_replace('<rewrite>', '<rewrite>'. PHP_EOL . $wcStart . PHP_EOL . $webConfigWapStr . $wcEnd, $webConfigWapFile);
							}elseif (strpos($webConfigWapFile,'<system.webServer>') !== false){
								$webConfigWapFile = str_replace('<system.webServer>', '<system.webServer>'. PHP_EOL .'<rewrite>'. PHP_EOL . $wcStart . PHP_EOL . $webConfigWapStr . $wcEnd . PHP_EOL .'</rewrite>', $webConfigWapFile);
							}
						}
					}
				$isWebConfigWap = File::Write(OT_ROOT .'wap/web.config', $webConfigWapFile);
				if ($isWebConfigWap){
					$fileResultStr .= '\n../wap/web.config 修改成功！';
				}else{
					$fileResultStr .= '\n../wap/web.config 修改失败，请检查该目录或者文件是否有写入/修改权限！';
				}
			}
		}
	}

	if ($isHtmlHome==0 && $isHtmlHomeOld==1){
		if (file_exists(OT_ROOT .'index.html')){
			$isHomeFile = File::Del(OT_ROOT .'index.html');
			if (! $isHomeFile){
				$fileResultStr .= '\n根目录下的index.html删除失败，您可以手动删除！';
			}
		}
		if (AppWap::Jud()){
			if (file_exists(OT_ROOT .'wap/index.html')){
				$isWapHomeFile = File::Del(OT_ROOT .'wap/index.html');
				if (! $isWapHomeFile){
					$fileResultStr .= '\n根目录下的wap/index.html删除失败，您可以手动删除！';
				}
			}
		}
	}
	if ($htmlInfoTypeDirOld != $htmlInfoTypeDir && $htmlInfoTypeDir>0){
		$inforec=$DB->query('select IF_ID,IF_infoTypeDir from '. OT_dbPref .'info');
		while ($row = $inforec->fetch()){
			if (strlen($row['IF_infoTypeDir'])<=1){ $DB->query("update ". OT_dbPref ."info set IF_infoTypeDir='announ/' where IF_ID=". $row['IF_ID']); }
		}
		unset($inforec);
	}
	if ($htmlDatetimeDirOld != $htmlDatetimeDir && $htmlDatetimeDir>0){
		$inforec=$DB->query('select IF_ID,IF_time,IF_datetimeDir from '. OT_dbPref .'info');
		while ($row = $inforec->fetch()){
			$DB->query("update ". OT_dbPref ."info set IF_datetimeDir='". Area::DatetimeDirName($row['IF_time'],$htmlDatetimeDir) ."/' where IF_ID=". $row['IF_ID']);
		}
		unset($inforec);
	}

	$Cache = new Cache();
	$isJsResult = $Cache->Js('system');
		if ($isJsResult){
			$fileResultStr .= '\n../cache/js/system.js 生成成功！';
		}else{
			$fileResultStr .= '\n../cache/js/system.js 生成失败，请检查该目录或者文件是否有写入/修改权限！';
		}
	$isCacheResult = $Cache->Php('system');
		if ($isCacheResult){
			$fileResultStr .= '\n../cache/php/system.php 生成成功！';
		}else{
			$fileResultStr .= '\n../cache/php/system.php 生成失败，请检查该目录或者文件是否有写入/修改权限！';
		}
	$isSiteCss = AdmArea::MakeSiteCss();
		if ($isSiteCss){
			$fileResultStr .= '\n../cache/web/site.css 生成成功！';
		}else{
			$fileResultStr .= '\n../cache/web/site.css 生成失败，请检查该目录或者文件是否有写入/修改权限！';
		}

	$DB->query('update '. OT_dbPref .'autoRunSys set ARS_dayDate='. $DB->ForTime('2018-01-01'));
	$Cache->Php('autoRunSys');
	$Cache->Js('autoRunSys');

	Adm::AddLog(array(
		'note'		=> '【'. $dataTypeCN .'】修改成功！',
		));

	JS::AlertHrefEnd('修改成功\n'. $fileResultStr, $backURL);
}



function UpdateCacheTime(){
	global $DB;

	$todayTime = TimeDate::Get();
	$DB->query('update '. OT_dbPref .'system set SYS_htmlCacheTime='. $DB->ForTime($todayTime));

	$Cache = new Cache();
	$Cache->Php('system');

	echo('<span style="color:red;">（已更新:'. $todayTime .'）</span>');
}



function CalcCacheNum(){
	global $DB;

	$retNum = File::Count(OT_ROOT .'cache/html/','html');

	echo('共'. $retNum .'个缓存文件');
}



function ClearCache(){
	global $DB;

	$webCacheNum = File::Count(OT_ROOT .'cache/html/',array('html','png'));
	if ($webCacheNum>0){
		$retNum = File::MoreDel(OT_ROOT .'cache/html/',array('html','png'));

		echo('清理完毕，共清理'. $retNum .'个文件.');
	}else{
		echo('暂无页面缓存，无需清理。');
	}
}

?>
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
$MB->Open('','login',10);


	$menuFileID = 145;
	$MB->IsSecMenuRight('alert',$menuFileID,$dataType);


switch($mudi){
	case 'deal':
		deal();
		break;

	case 'dbMap':
		dbMap();
		break;

	case 'down':
		down();
		break;

	default:
		die('err');
}

$MB->Close();
$DB->Close();





function deal(){
	global $DB,$MB,$mudi,$menuFileID,$menuTreeID,$systemArr,$timeInfoWhereStr;

	$dealMode			= OT::GetStr('dealMode');

	$oldFileNum			= OT::GetInt('oldFileNum');
	$scoreMode			= OT::GetInt('scoreMode');
	$newScore			= OT::GetFloat('newScore');
	$homeThumbScore		= OT::GetFloat('homeThumbScore');
	$thumbScore			= OT::GetFloat('thumbScore');
	$flashScore			= OT::GetFloat('flashScore');
	$imgScore			= OT::GetFloat('imgScore');
	$marInfoScore		= OT::GetFloat('marInfoScore');
	$recomScore			= OT::GetFloat('recomScore');
	$topScore			= OT::GetFloat('topScore');

	$isNew				= OT::GetInt('isNew');
	$isHomeThumb		= OT::GetInt('isHomeThumb');
	$isThumb			= OT::GetInt('isThumb');
	$isFlash			= OT::GetInt('isFlash');
	$isImg				= OT::GetInt('isImg');
	$isMarquee			= OT::GetInt('isMarquee');
	$isRecom			= OT::GetInt('isRecom');
	$isTop				= OT::GetInt('isTop');

	$recordMaxNum		= OT::GetInt('recordMaxNum');
	$pageMaxNum			= OT::GetInt('pageMaxNum');
	$hourDiff			= OT::GetInt('hourDiff');
	$updateFreq			= OT::GetStr('updateFreq');
	$updateTime			= OT::GetInt('updateTime');
	$updateTimeStr		= OT::GetStr('updateTimeStr');

	$xmlFilePage		= OT::GetInt('xmlFilePage');

	$SQLstr = '';
	if ($isNew == 1){ $SQLstr .= ' and IF_isNew=1'; }
	if ($isHomeThumb == 1){ $SQLstr .= ' and IF_isHomeThumb=1'; }
	if ($isThumb == 1){ $SQLstr .= ' and IF_isThumb=1'; }
	if ($isFlash == 1){ $SQLstr .= ' and IF_isFlash=1'; }
	if ($isImg == 1){ $SQLstr .= ' and IF_isImg=1'; }
	if ($isMarquee == 1){ $SQLstr .= ' and IF_isMarquee=1'; }
	if ($isRecom == 1){ $SQLstr .= ' and IF_isRecom=1'; }
	if ($isTop == 1){ $SQLstr .= ' and IF_isTop=1'; }

	$infoTypeCount = $DB->GetOne("select count(IT_ID) from ". OT_dbPref ."infoType where IT_state=1 and IT_mode='item'");
	$infoCount = $DB->GetOne("select count(IF_ID) from ". OT_dbPref ."info where IF_state=1 and IF_isAudit=1". $SQLstr . $timeInfoWhereStr);
		if ($dealMode == 'count'){
			JS::AlertEnd('首页1条，列表页'. $infoTypeCount .'条，符合条件的文章数'. $infoCount .' 条，共计：'. (1+$infoTypeCount+$infoCount) .'条。');
		}

	if ($recordMaxNum <= 0 || $recordMaxNum>$infoCount){ $recordMaxNum=$infoCount; }
	if ($recordMaxNum <= 0){
		JS::AlertEnd('没有符合条件的记录。');
	}elseif ($recordMaxNum>1000000){
		JS::AlertEnd('符合条件的记录超过 1000000 条（现有 '. $recordMaxNum .' 条），请设置【限制最大条数】小于等于100000。');
	}
	if ($pageMaxNum < 100 || $pageMaxNum > 5000){
		JS::AlertEnd('每个文件最大条数取值范围内：100 至 5000。');
	}

	$xmlDataNum = 0;
	$recordPage = ceil($recordMaxNum/$pageMaxNum);
	$GB_WebHost = GetUrl::CurrDir(1);
	$oneWapUrl = $systemArr['SYS_wapUrl'];
	$addHourStr = substr('0'. $hourDiff,-2) .':00';
	if ($updateTime == 2){
		// $timeStr = TimeDate::Get('Y-m-dTH:i:s',$updateTimeStr) .'+'. $addHourStr;
		$timeStr = TimeDate::Get('Y-m-d',$updateTimeStr);
	}else{
		// $timeStr = TimeDate::Get('Y-m-dTH:i:s') .'+'. $addHourStr;
		$timeStr = TimeDate::Get('Y-m-d');
	}
	$xmlFilePage ++;

	$xmlMapStr = $xmlMapWapStr = $baiduUrlStr = $soPatStr = $soUrlStr = $sogouPatStr = $sogouUrlStr = $shenmaPatStr = $shenmaUrlStr = '';

	if ($recordPage > 1 && $xmlFilePage == 1){
		$xmlWebStr = '<?xml version="1.0" encoding="'. OT_Charset .'"?>
		<sitemapindex xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
			xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/siteindex.xsd"
			xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
			';
		$xmlWebWapStr = '';
		if (AppWap::Jud()){
			$xmlWebWapStr = '<?xml version="1.0" encoding="'. OT_Charset .'"?>
			<sitemapindex xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
				xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/siteindex.xsd"
				xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
				';
		}
			for ($i=1; $i<=$recordPage; $i++){
				$xmlWebStr .= '
				<sitemap>
					<loc>'. $GB_WebHost .'sitemap'. $i .'.xml</loc>
					<lastmod>'. $timeStr .'</lastmod>
				</sitemap>
				';
				if (AppWap::Jud()){
					$xmlWebWapStr .= '
					<sitemap>
						<loc>'. $oneWapUrl .'sitemap'. $i .'.xml</loc>
						<lastmod>'. $timeStr .'</lastmod>
					</sitemap>
					';
				}
			}
		$xmlWebStr .= '
		</sitemapindex>
		';
		if (AppWap::Jud()){
			$xmlWebWapStr .= '
			</sitemapindex>
			';
		}

		$judResult = File::Write(OT_ROOT .'sitemap.xml',$xmlWebStr);
		if (! $judResult){
			JS::AlertEnd('生成 ../sitemap.xml 失败.');
		}
		if (AppWap::Jud()){
			$judResult = File::Write(OT_ROOT .'wap/sitemap.xml',$xmlWebWapStr);
			if (! $judResult){
				JS::AlertEnd('生成 ../wap/sitemap.xml 失败.');
			}
		}
	}

	$xmlMapStr .= '<?xml version="1.0" encoding="'. OT_Charset .'"?>
		<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
			xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd"
			xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
			';
	if (AppWap::Jud()){
		$xmlMapWapStr .= '<?xml version="1.0" encoding="'. OT_Charset .'"?>
			<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
				xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd"
				xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
				';
	}
		if ($xmlFilePage == 1){
			if ($oldFileNum > 1){
				for ($i=1; $i<=$oldFileNum; $i++){
					File::Del(OT_ROOT .'sitemap'. $i .'.xml');
					File::Del(OT_ROOT .'cache/web/site_baiduWapUrl'. $i .'.txt');
				}
			}
			$xmlMapStr .= '
				<url>
					<loc>'. $GB_WebHost .'</loc>
					<lastmod>'. $timeStr .'</lastmod>
					<changefreq>'. $updateFreq .'</changefreq>
					<priority>1.0</priority>
				</url>
				<url>
					<loc>'. $GB_WebHost . Url::ListStr('announ',0) .'</loc>
					<lastmod>'. $timeStr .'</lastmod>
					<changefreq>'. $updateFreq .'</changefreq>
					<priority>1.0</priority>
				</url>
				';
			if (AppWap::Jud()){
				$xmlMapWapStr .= '
					<url>
						<loc>'. $oneWapUrl .'</loc>
						<lastmod>'. $timeStr .'</lastmod>
						<changefreq>'. $updateFreq .'</changefreq>
						<priority>1.0</priority>
					</url>
					<url>
						<loc>'. $oneWapUrl . Url::ListStr('announ',0) .'</loc>
						<lastmod>'. $timeStr .'</lastmod>
						<changefreq>'. $updateFreq .'</changefreq>
						<priority>1.0</priority>
					</url>
					';
			}

			$baiduUrlStr .=  $GB_WebHost .' '. $oneWapUrl;
			$soUrlStr .= $GB_WebHost .'	'. $oneWapUrl;
			$sogouUrlStr .= ''.
				'<url>'. PHP_EOL .
				'<loc>'. $GB_WebHost .'</loc>'. PHP_EOL .
				'<data>'. PHP_EOL .
				'<display>'. PHP_EOL .
				'<url_pattern>'. $oneWapUrl .'</url_pattern>'. PHP_EOL .
				'<version>7</version>'. PHP_EOL .
				'</display>'. PHP_EOL .
				'</data>'. PHP_EOL .
				'</url>'. PHP_EOL .
				'';

			$baiduUrlStr .= PHP_EOL . $GB_WebHost .'message.php '. $systemArr['SYS_wapUrl'] .'message.php';
			$soUrlStr .= PHP_EOL . $GB_WebHost .'message.php	'. $systemArr['SYS_wapUrl'] .'message.php';
			$sogouUrlStr .= ''.
				'<url>'. PHP_EOL .
				'<loc>'. $GB_WebHost .'message.php</loc>'. PHP_EOL .
				'<data>'. PHP_EOL .
				'<display>'. PHP_EOL .
				'<url_pattern>'. $systemArr['SYS_wapUrl'] .'message.php</url_pattern>'. PHP_EOL .
				'<version>7</version>'. PHP_EOL .
				'</display>'. PHP_EOL .
				'</data>'. PHP_EOL .
				'</url>'. PHP_EOL .
				'';

			$infoTypeexe=$DB->query("select IT_ID,IT_revTime,IT_lookScore,IT_htmlName from ". OT_dbPref ."infoType where IT_state=1 and IT_mode='item'");
			while ($row = $infoTypeexe->fetch()){
				if ($updateTime == 2){
					// $timeStr = TimeDate::Get('Y-m-dTH:i:s',$updateTimeStr) .'+'. $addHourStr;
					$timeStr = TimeDate::Get('Y-m-d',$updateTimeStr);
				}elseif ($updateTime == 1){
					// $timeStr = TimeDate::Get('Y-m-dTH:i:s') .'+'. $addHourStr;
					$timeStr = TimeDate::Get('Y-m-d');
				}else{
					$todayTime = $row['IT_revTime'];
					// $timeStr = TimeDate::Get('Y-m-dTH:i:s',$todayTime) .'+'. $addHourStr;
					$timeStr = TimeDate::Get('Y-m-d',$todayTime);
				}
				$currNewsListUrl = $GB_WebHost . Url::ListID('',$row['IT_htmlName'],$row['IT_ID']);
				$currNewsListWapUrl = $systemArr['SYS_wapUrl'] . Url::ListID('',$row['IT_htmlName'],$row['IT_ID']);

				$xmlMapStr .= '
				<url>
					<loc>'. $currNewsListUrl .'</loc>
					<lastmod>'. $timeStr .'</lastmod>
					<changefreq>'. $updateFreq .'</changefreq>
					<priority>'. OT::NumFormat($row['IT_lookScore']/10,1) .'</priority>
				</url>
				';
				if (AppWap::Jud()){
					$xmlMapWapStr .= '
					<url>
						<loc>'. $currNewsListWapUrl .'</loc>
						<lastmod>'. $timeStr .'</lastmod>
						<changefreq>'. $updateFreq .'</changefreq>
						<priority>'. OT::NumFormat($row['IT_lookScore']/10,1) .'</priority>
					</url>
					';
				}

				$oneWapUrl = Url::ListID('',$row['IT_htmlName'],$row['IT_ID'],0,$systemArr['SYS_wapUrl']);
				$baiduUrlStr .= PHP_EOL . $currNewsListUrl .' '. $oneWapUrl;
				$soUrlStr .= PHP_EOL . $currNewsListUrl .'	'. $oneWapUrl;
				$sogouUrlStr .= ''.
					'<url>'. PHP_EOL .
					'<loc>'. $currNewsListUrl .'</loc>'. PHP_EOL .
					'<data>'. PHP_EOL .
					'<display>'. PHP_EOL .
					'<url_pattern>'. $oneWapUrl .'</url_pattern>'. PHP_EOL .
					'<version>7</version>'. PHP_EOL .
					'</display>'. PHP_EOL .
					'</data>'. PHP_EOL .
					'</url>'. PHP_EOL .
					'';

			}
			unset($infoTypeexe);

			$xmlDataNum += $infoTypeCount + 2;

			$jsStr = '
				$id("scoreMode'. $scoreMode .'").checked=true;
				$id("newScore").value="'. OT::NumFormat($newScore,1) .'";
				$id("homeThumbScore").value="'. OT::NumFormat($homeThumbScore,1) .'";
				$id("thumbScore").value="'. OT::NumFormat($thumbScore,1) .'";
				$id("flashScore").value="'. OT::NumFormat($flashScore,1) .'";
				$id("imgScore").value="'. OT::NumFormat($imgScore,1) .'";
				$id("marInfoScore").value="'. OT::NumFormat($marInfoScore,1) .'";
				$id("recomScore").value="'. OT::NumFormat($recomScore,1) .'";
				$id("topScore").value="'. OT::NumFormat($topScore,1) .'";

				$id("isNew").checked='. $isNew .';
				$id("isHomeThumb").checked='. $isHomeThumb .';
				$id("isThumb").checked='. $isThumb .';
				$id("isFlash").checked='. $isFlash .';
				$id("isImg").checked='. $isImg .';
				$id("isMarquee").checked='. $isMarquee .';
				$id("isRecom").checked='. $isRecom .';
				$id("isTop").checked='. $isTop .';

				$id("recordMaxNum").value="'. OT::GetInt('recordMaxNum') .'";
				$id("pageMaxNum").value="'. $pageMaxNum .'";
				$id("hourDiff").value="'. $hourDiff .'";
				$id("updateFreq").value="'. $updateFreq .'";
				$id("updateTime'. $updateTime .'").checked=true;

				$id("oldFileNum").value="'. $recordPage .'";
				$id("lastUpdatTime").innerHTML="'. TimeDate::Get() .'";
				CheckScoreMode();
				';
			$Cache = new Cache();
			if (! $Cache->WriteJs('siteMap',$jsStr) ){
				JS::AlertEnd('生成 cache/js/siteMap.js 失败.');
			}
		}

	$pageSize	= $pageMaxNum;		//每页条数
	$page		= $xmlFilePage;
	$showRow = $DB->GetLimit('select IF_ID,IF_time,IF_isNew,IF_isHomeThumb,IF_isThumb,IF_isFlash,IF_isImg,IF_isMarquee,IF_isRecom,IF_isTop,IF_infoTypeDir,IF_datetimeDir from '. OT_dbPref .'info where IF_state=1 and IF_isAudit=1'. $SQLstr . $timeInfoWhereStr .' order by IF_time DESC',$pageSize,$page);
	//var_dump($inforec);die();
	if ($showRow){
		$recordCount=$DB->GetRowCount();
		$pageCount=ceil($recordCount/$pageSize);
		if ($page < 1 || $page > $pageCount){ $page=1; }

		$rowCount = count($showRow);
		for ($i=0; $i<$rowCount; $i++){
			$lookScore = 0;
			if ($scoreMode == 0){
				$lookScore = '0.'. OT::RndNumTo(1,9);
			}else{
				if ($showRow[$i]['IF_isNew'] == 1){ $lookScore += $newScore; }
				if ($showRow[$i]['IF_isHomeThumb'] == 1){ $lookScore += $homeThumbScore; }
				if ($showRow[$i]['IF_isThumb'] == 1){ $lookScore += $thumbScore; }
				if ($showRow[$i]['IF_isFlash'] == 1){ $lookScore += $flashScore; }
				if ($showRow[$i]['IF_isImg'] == 1){ $lookScore += $imgScore; }
				if ($showRow[$i]['IF_isMarquee'] == 1){ $lookScore += $marInfoScore; }
				if ($showRow[$i]['IF_isRecom'] == 1){ $lookScore += $recomScore; }
				if ($showRow[$i]['IF_isTop'] == 1){ $lookScore += $topScore; }
				if ($lookScore>1){
					$lookScore=1;
				}elseif ($lookScore == 0){
					$lookScore=0.1;
				}
				$lookScore = OT::NumFormat($lookScore,1);
			}
			if ($updateTime == 2){
				// $timeStr = TimeDate::Get('Y-m-dTH:i:s',$updateTimeStr) .'+'. $addHourStr;
				$timeStr = TimeDate::Get('Y-m-d',$updateTimeStr);
			}elseif ($updateTime == 1){
				// $timeStr = TimeDate::Get('Y-m-dTH:i:s') .'+'. $addHourStr;
				$timeStr = TimeDate::Get('Y-m-d');
			}else{
				$todayTime = $showRow[$i]['IF_time'];
				// $timeStr = TimeDate::Get('Y-m-dTH:i:s',$todayTime) .'+'. $addHourStr;
				$timeStr = TimeDate::Get('Y-m-d',$todayTime);
			}
			$currNewsShowUrl = $GB_WebHost . Url::NewsID($showRow[$i]['IF_infoTypeDir'], $showRow[$i]['IF_datetimeDir'], $showRow[$i]['IF_ID']);
			$currNewsShowWapUrl = $systemArr['SYS_wapUrl'] . Url::NewsID($showRow[$i]['IF_infoTypeDir'], $showRow[$i]['IF_datetimeDir'], $showRow[$i]['IF_ID']);

			$xmlDataNum ++;

			$xmlMapStr .= '
			<url>
				<loc>'. $currNewsShowUrl .'</loc>
				<lastmod>' . $timeStr . '</lastmod>
				<changefreq>'. $updateFreq .'</changefreq>
				<priority>'. $lookScore .'</priority>
			</url>
			';
			if (AppWap::Jud()){
				$xmlMapWapStr .= '
				<url>
					<loc>'. $currNewsShowWapUrl .'</loc>
					<lastmod>' . $timeStr . '</lastmod>
					<changefreq>'. $updateFreq .'</changefreq>
					<priority>'. $lookScore .'</priority>
				</url>
				';
			}

			$oneWapUrl = Url::NewsID($showRow[$i]['IF_infoTypeDir'], $showRow[$i]['IF_datetimeDir'], $showRow[$i]['IF_ID'], 0, $systemArr['SYS_wapUrl']);
			if ($xmlDataNum == 1 && $xmlFilePage > 1){
				$baiduUrlStr .= $currNewsShowUrl .' '. $oneWapUrl;
			}else{
				$baiduUrlStr .= PHP_EOL . $currNewsShowUrl .' '. $oneWapUrl;
			}
			if ($xmlDataNum<=5000){
				$soUrlStr .= PHP_EOL . $currNewsShowUrl .'	'. $oneWapUrl;
				$sogouUrlStr .= ''.
					'<url>'. PHP_EOL .
					'<loc>'. $currNewsShowUrl .'</loc>'. PHP_EOL .
					'<data>'. PHP_EOL .
					'<display>'. PHP_EOL .
					'<url_pattern>'. $oneWapUrl .'</url_pattern>'. PHP_EOL .
					'<version>7</version>'. PHP_EOL .
					'</display>'. PHP_EOL .
					'</data>'. PHP_EOL .
					'</url>'. PHP_EOL .
					'';
			}
		}
	}
	unset($inforec);

	$xmlMapStr .= '</urlset>';
	if (AppWap::Jud()){
		$xmlMapWapStr .= '</urlset>';
	}

	$alertStr = '';
	if ($recordPage > 1){
		$sitemapPath = OT_ROOT .'sitemap'. $xmlFilePage .'.xml';
		$judResult = File::Write($sitemapPath,$xmlMapStr);
			if ($judResult){
				$alertStr .= '生成 ../sitemap'. $xmlFilePage .'.xml 成功.\n';
			}else{
				$alertStr .= '生成 ../sitemap'. $xmlFilePage .'.xml 失败.\n';
			}
		if (AppWap::Jud()){
			$sitemapWapPath = OT_ROOT .'wap/sitemap'. $xmlFilePage .'.xml';
			$judResult = File::Write($sitemapWapPath,$xmlMapWapStr);
				if ($judResult){
					$alertStr .= '生成 ../wap/sitemap'. $xmlFilePage .'.xml 成功.\n';
				}else{
					$alertStr .= '生成 ../wap/sitemap'. $xmlFilePage .'.xml 失败.\n';
				}
		}
	}else{
		$sitemapPath = OT_ROOT .'sitemap.xml';
		$judResult = File::Write($sitemapPath,$xmlMapStr);
			if ($judResult){
				$alertStr .= '生成 ../sitemap.xml 成功.\n';
			}else{
				$alertStr .= '生成 ../sitemap.xml 失败.\n';
			}
		if (AppWap::Jud()){
			$sitemapWapPath = OT_ROOT .'wap/sitemap.xml';
			$judResult = File::Write($sitemapWapPath,$xmlMapWapStr);
				if ($judResult){
					$alertStr .= '生成 ../wap/sitemap.xml 成功.\n';
				}else{
					$alertStr .= '生成 ../wap/sitemap.xml 失败.\n';
				}
		}
	}

	if (AppWap::Jud()){
		if ($xmlFilePage>1){
			$judResult = File::Write(OT_ROOT .'cache/web/site_baiduWapUrl'. $xmlFilePage .'.txt',$baiduUrlStr);
				if ($judResult){
					$alertStr .= '生成 ../cache/web/site_baiduWapUrl'. $xmlFilePage .'.txt 成功.\n';
				}else{
					$alertStr .= '生成 ../cache/web/site_baiduWapUrl'. $xmlFilePage .'.txt 失败.\n';
				}
			$judResult = File::Write(OT_ROOT .'cache/web/site_360WapUrl'. $xmlFilePage .'.txt',$soUrlStr);
				if ($judResult){
					$alertStr .= '生成 ../cache/web/site_360WapUrl'. $xmlFilePage .'.txt 成功.\n';
				}else{
					$alertStr .= '生成 ../cache/web/site_360WapUrl'. $xmlFilePage .'.txt 失败.\n';
				}
			$judResult = File::Write(OT_ROOT .'cache/web/site_sogouWapUrl'. $xmlFilePage .'.txt','<urlset>'. PHP_EOL . $sogouUrlStr .'</urlset>');
				if ($judResult){
					$alertStr .= '生成 ../cache/web/site_sogouWapUrl'. $xmlFilePage .'.txt 成功.\n';
				}else{
					$alertStr .= '生成 ../cache/web/site_sogouWapUrl'. $xmlFilePage .'.txt 失败.\n';
				}
			$judResult = File::Write(OT_ROOT .'cache/web/site_shenmaWapUrl'. $xmlFilePage .'.txt',$soUrlStr);
				if ($judResult){
					$alertStr .= '生成 ../cache/web/site_shenmaWapUrl'. $xmlFilePage .'.txt 成功.\n';
				}else{
					$alertStr .= '生成 ../cache/web/site_shenmaWapUrl'. $xmlFilePage .'.txt 失败.\n';
				}
		}else{
			$judResult = File::Write(OT_ROOT .'cache/web/site_baiduWapUrl.txt',$baiduUrlStr);
				if ($judResult){
					$alertStr .= '生成 ../cache/web/site_baiduWapUrl.txt 成功.\n';
				}else{
					$alertStr .= '生成 ../cache/web/site_baiduWapUrl.txt 失败.\n';
				}
			$judResult = File::Write(OT_ROOT .'cache/web/site_360WapUrl.txt',$soUrlStr);
				if ($judResult){
					$alertStr .= '生成 ../cache/web/site_360WapUrl.txt 成功.\n';
				}else{
					$alertStr .= '生成 ../cache/web/site_360WapUrl.txt 失败.\n';
				}
			$judResult = File::Write(OT_ROOT .'cache/web/site_sogouWapUrl.txt','<urlset>'. PHP_EOL . $sogouUrlStr .'</urlset>');
				if ($judResult){
					$alertStr .= '生成 ../cache/web/site_sogouWapUrl.txt 成功.\n';
				}else{
					$alertStr .= '生成 ../cache/web/site_sogouWapUrl.txt 失败.\n';
				}
			$judResult = File::Write(OT_ROOT .'cache/web/site_shenmaWapUrl.txt',$soUrlStr);
				if ($judResult){
					$alertStr .= '生成 ../cache/web/site_shenmaWapUrl.txt 成功.\n';
				}else{
					$alertStr .= '生成 ../cache/web/site_shenmaWapUrl.txt 失败.\n';
				}
		}

		$soPatStr = ''.
		$GB_WebHost .' '. $systemArr['SYS_wapUrl'] .
		PHP_EOL . $GB_WebHost .'news/?list_topic-(\d+).html '. $systemArr['SYS_wapUrl'] .'news/?list_topic-\1.html'.
		PHP_EOL . $GB_WebHost .'news/message.php '. $systemArr['SYS_wapUrl'] .'message.php'.

		PHP_EOL . $GB_WebHost .'news/?list_(\d+).html '. $systemArr['SYS_wapUrl'] .'news/?list_\1.html'.
		PHP_EOL . $GB_WebHost .'news/?list_(\d+)_(\d+).html '. $systemArr['SYS_wapUrl'] .'news/?list_\1_\2.html'.
		PHP_EOL . $GB_WebHost .'news/?(\d+).html '. $systemArr['SYS_wapUrl'] .'news/?\1.html'.
		PHP_EOL . $GB_WebHost .'news/?(\d+)_(\d+).html '. $systemArr['SYS_wapUrl'] .'news/?\1_\2.html'.
		PHP_EOL . $GB_WebHost .'news/?web_(\d+).html '. $systemArr['SYS_wapUrl'] .'news/?web_\1.html'.
		PHP_EOL . $GB_WebHost .'news/?web_(\d+)_(\d+).html '. $systemArr['SYS_wapUrl'] .'news/?web_\1_\2.html'.

		PHP_EOL . $GB_WebHost .'(\w+)/list_(\d+).html '. $systemArr['SYS_wapUrl'] .'\1/list_\2.html'.
		PHP_EOL . $GB_WebHost .'(\w+)/list_(\d+)_(\d+).html '. $systemArr['SYS_wapUrl'] .'\1/list_\2_\3.html'.

		PHP_EOL . $GB_WebHost .'(\w+)/(\d+).html '. $systemArr['SYS_wapUrl'] .'\1/\2.html'.
		PHP_EOL . $GB_WebHost .'(\w+)/(\d+)_(\d+).html '. $systemArr['SYS_wapUrl'] .'\1/\2_\3.html'.
		PHP_EOL . $GB_WebHost .'(\w+)/(\w+)/(\d+).html '. $systemArr['SYS_wapUrl'] .'\1/\2/\3.html'.
		PHP_EOL . $GB_WebHost .'(\w+)/(\w+)/(\d+)_(\d+).html '. $systemArr['SYS_wapUrl'] .'\1/\2/\3_\4.html'.
		PHP_EOL . $GB_WebHost .'(\w+)/(\w+)/(\d+)/(\d+).html '. $systemArr['SYS_wapUrl'] .'\1/\2/\3/\4.html'.
		PHP_EOL . $GB_WebHost .'(\w+)/(\w+)/(\d+)/(\d+)_(\d+).html '. $systemArr['SYS_wapUrl'] .'\1/\2/\3/\4_\5.html'.
		PHP_EOL . $GB_WebHost .'(\w+)/(\d+)/(\d+).html '. $systemArr['SYS_wapUrl'] .'\1/\2/\3.html'.
		PHP_EOL . $GB_WebHost .'(\w+)/(\d+)/(\d+)_(\d+).html '. $systemArr['SYS_wapUrl'] .'\1/\2/\3_\4.html'.

		PHP_EOL . $GB_WebHost .'(\w+)/web_(\d+).html '. $systemArr['SYS_wapUrl'] .'\1/web_\2.html'.
		PHP_EOL . $GB_WebHost .'(\w+)/web_(\d+)_(\d+).html '. $systemArr['SYS_wapUrl'] .'\1/web_\2_\3.html'.
		'';

		$shenmaPatStr = ''.
		$GB_WebHost .'	'. $systemArr['SYS_wapUrl'] .
		PHP_EOL . $GB_WebHost .'news/?list_topic-(\d+).html	'. $systemArr['SYS_wapUrl'] .'news/?list_topic-${1}.html'.
		PHP_EOL . $GB_WebHost .'news/message.php	'. $systemArr['SYS_wapUrl'] .'message.php'.

		PHP_EOL . $GB_WebHost .'news/?list_(\d+).html	'. $systemArr['SYS_wapUrl'] .'news/?list_${1}.html'.
		PHP_EOL . $GB_WebHost .'news/?list_(\d+)_(\d+).html	'. $systemArr['SYS_wapUrl'] .'news/?list_${1}_${2}.html'.
		PHP_EOL . $GB_WebHost .'news/?(\d+).html	'. $systemArr['SYS_wapUrl'] .'news/?${1}.html'.
		PHP_EOL . $GB_WebHost .'news/?(\d+)_(\d+).html	'. $systemArr['SYS_wapUrl'] .'news/?${1}_${2}.html'.
		PHP_EOL . $GB_WebHost .'news/?web_(\d+).html	'. $systemArr['SYS_wapUrl'] .'news/?web_${1}.html'.
		PHP_EOL . $GB_WebHost .'news/?web_(\d+)_(\d+).html	'. $systemArr['SYS_wapUrl'] .'news/?web_${1}_${2}.html'.

		PHP_EOL . $GB_WebHost .'(\w+)/list_(\d+).html	'. $systemArr['SYS_wapUrl'] .'${1}/list_${2}.html'.
		PHP_EOL . $GB_WebHost .'(\w+)/list_(\d+)_(\d+).html	'. $systemArr['SYS_wapUrl'] .'${1}/list_${2}_${3}.html'.

		PHP_EOL . $GB_WebHost .'(\w+)/(\d+).html	'. $systemArr['SYS_wapUrl'] .'${1}/${2}.html'.
		PHP_EOL . $GB_WebHost .'(\w+)/(\d+)_(\d+).html	'. $systemArr['SYS_wapUrl'] .'${1}/${2}_${3}.html'.
		PHP_EOL . $GB_WebHost .'(\w+)/(\w+)/(\d+).html	'. $systemArr['SYS_wapUrl'] .'${1}/${2}/${3}.html'.
		PHP_EOL . $GB_WebHost .'(\w+)/(\w+)/(\d+)_(\d+).html	'. $systemArr['SYS_wapUrl'] .'${1}/${2}/${3}_${4}.html'.
		PHP_EOL . $GB_WebHost .'(\w+)/(\w+)/(\d+)/(\d+).html	'. $systemArr['SYS_wapUrl'] .'${1}/${2}/${3}/${4}.html'.
		PHP_EOL . $GB_WebHost .'(\w+)/(\w+)/(\d+)/(\d+)_(\d+).html	'. $systemArr['SYS_wapUrl'] .'${1}/${2}/${3}/${4}_${5}.html'.
		PHP_EOL . $GB_WebHost .'(\w+)/(\d+)/(\d+).html	'. $systemArr['SYS_wapUrl'] .'${1}/${2}/${3}.html'.
		PHP_EOL . $GB_WebHost .'(\w+)/(\d+)/(\d+)_(\d+).html	'. $systemArr['SYS_wapUrl'] .'${1}/${2}/${3}_${4}.html'.

		PHP_EOL . $GB_WebHost .'(\w+)/web_(\d+).html	'. $systemArr['SYS_wapUrl'] .'${1}/web_${2}.html'.
		PHP_EOL . $GB_WebHost .'(\w+)/web_(\d+)_(\d+).html	'. $systemArr['SYS_wapUrl'] .'${1}/web_${2}_${3}.html'.
		'';

		$sogouPatStr = ''.
			'<url>'. PHP_EOL .
			'	<loc>'. $GB_WebHost .'</loc>'. PHP_EOL .
			'	<data>'. PHP_EOL .
			'		<display>'. PHP_EOL .
			'			<pc_url_pattern>'. $GB_WebHost .'$</pc_url_pattern>'. PHP_EOL .
			'			<url_pattern>'. $systemArr['SYS_wapUrl'] .'</url_pattern>'. PHP_EOL .
			'			<pc_sample>'. $GB_WebHost .'</pc_sample>'. PHP_EOL .
			'			<wap_sample>'. $systemArr['SYS_wapUrl'] .'</wap_sample>'. PHP_EOL .
			'			<version>7</version>'. PHP_EOL .
			'		</display>'. PHP_EOL .
			'	</data>'. PHP_EOL .
			'</url>'. PHP_EOL .
			'<url>'. PHP_EOL .
			'	<loc>'. $GB_WebHost .'</loc>'. PHP_EOL .
			'	<data>'. PHP_EOL .
			'		<display>'. PHP_EOL .
			'			<pc_url_pattern>'. $GB_WebHost .'message.php</pc_url_pattern>'. PHP_EOL .
			'			<url_pattern>'. $systemArr['SYS_wapUrl'] .'message.php</url_pattern>'. PHP_EOL .
			'			<pc_sample>'. $GB_WebHost .'message.php</pc_sample>'. PHP_EOL .
			'			<wap_sample>'. $systemArr['SYS_wapUrl'] .'message.php</wap_sample>'. PHP_EOL .
			'			<version>7</version>'. PHP_EOL .
			'		</display>'. PHP_EOL .
			'	</data>'. PHP_EOL .
			'</url>'. PHP_EOL .
			'<url>'. PHP_EOL .
			'	<loc>'. $GB_WebHost .'</loc>'. PHP_EOL .
			'	<data>'. PHP_EOL .
			'		<display>'. PHP_EOL .
			'			<pc_url_pattern>'. $GB_WebHost .'news/\?list_topic-(\d+)\.html</pc_url_pattern>'. PHP_EOL .
			'			<url_pattern>'. $systemArr['SYS_wapUrl'] .'news/?list_topic-${1}.html</url_pattern>'. PHP_EOL .
			'			<pc_sample>'. $GB_WebHost .'news/?list_topic-88.html</pc_sample>'. PHP_EOL .
			'			<wap_sample>'. $systemArr['SYS_wapUrl'] .'news/?list_topic-88.html</wap_sample>'. PHP_EOL .
			'			<version>7</version>'. PHP_EOL .
			'		</display>'. PHP_EOL .
			'	</data>'. PHP_EOL .
			'</url>'. PHP_EOL .
			'<url>'. PHP_EOL .
			'	<loc>'. $GB_WebHost .'</loc>'. PHP_EOL .
			'	<data>'. PHP_EOL .
			'		<display>'. PHP_EOL .
			'			<pc_url_pattern>'. $GB_WebHost .'news/\?list_(\d+)\.html</pc_url_pattern>'. PHP_EOL .
			'			<url_pattern>'. $systemArr['SYS_wapUrl'] .'news/?list_${1}.html</url_pattern>'. PHP_EOL .
			'			<pc_sample>'. $GB_WebHost .'news/?list_88.html</pc_sample>'. PHP_EOL .
			'			<wap_sample>'. $systemArr['SYS_wapUrl'] .'news/?list_88.html</wap_sample>'. PHP_EOL .
			'			<version>7</version>'. PHP_EOL .
			'		</display>'. PHP_EOL .
			'	</data>'. PHP_EOL .
			'</url>'. PHP_EOL .
			'<url>'. PHP_EOL .
			'	<loc>'. $GB_WebHost .'</loc>'. PHP_EOL .
			'	<data>'. PHP_EOL .
			'		<display>'. PHP_EOL .
			'			<pc_url_pattern>'. $GB_WebHost .'news/\?(\d+)\.html</pc_url_pattern>'. PHP_EOL .
			'			<url_pattern>'. $systemArr['SYS_wapUrl'] .'news/?${1}.html</url_pattern>'. PHP_EOL .
			'			<pc_sample>'. $GB_WebHost .'news/?88.html</pc_sample>'. PHP_EOL .
			'			<wap_sample>'. $systemArr['SYS_wapUrl'] .'news/?88.html</wap_sample>'. PHP_EOL .
			'			<version>7</version>'. PHP_EOL .
			'		</display>'. PHP_EOL .
			'	</data>'. PHP_EOL .
			'</url>'. PHP_EOL .
			'<url>'. PHP_EOL .
			'	<loc>'. $GB_WebHost .'</loc>'. PHP_EOL .
			'	<data>'. PHP_EOL .
			'		<display>'. PHP_EOL .
			'			<pc_url_pattern>'. $GB_WebHost .'news/\?web_(\d+)\.html</pc_url_pattern>'. PHP_EOL .
			'			<url_pattern>'. $systemArr['SYS_wapUrl'] .'news/?web_${1}.html</url_pattern>'. PHP_EOL .
			'			<pc_sample>'. $GB_WebHost .'news/?web_88.html</pc_sample>'. PHP_EOL .
			'			<wap_sample>'. $systemArr['SYS_wapUrl'] .'news/?web_88.html</wap_sample>'. PHP_EOL .
			'			<version>7</version>'. PHP_EOL .
			'		</display>'. PHP_EOL .
			'	</data>'. PHP_EOL .
			'</url>'. PHP_EOL .
			'<url>'. PHP_EOL .
			'	<loc>'. $GB_WebHost .'</loc>'. PHP_EOL .
			'	<data>'. PHP_EOL .
			'		<display>'. PHP_EOL .
			'			<pc_url_pattern>'. $GB_WebHost .'(\w+)/list_(\d+)\.html</pc_url_pattern>'. PHP_EOL .
			'			<url_pattern>'. $systemArr['SYS_wapUrl'] .'${1}/list_${2}.html</url_pattern>'. PHP_EOL .
			'			<pc_sample>'. $GB_WebHost .'soft/list_88.html</pc_sample>'. PHP_EOL .
			'			<wap_sample>'. $systemArr['SYS_wapUrl'] .'soft/list_88.html</wap_sample>'. PHP_EOL .
			'			<version>7</version>'. PHP_EOL .
			'		</display>'. PHP_EOL .
			'	</data>'. PHP_EOL .
			'</url>'. PHP_EOL .
			'<url>'. PHP_EOL .
			'	<loc>'. $GB_WebHost .'</loc>'. PHP_EOL .
			'	<data>'. PHP_EOL .
			'		<display>'. PHP_EOL .
			'			<pc_url_pattern>'. $GB_WebHost .'(\w+)/(\d+)\.html</pc_url_pattern>'. PHP_EOL .
			'			<url_pattern>'. $systemArr['SYS_wapUrl'] .'${1}/${2}.html</url_pattern>'. PHP_EOL .
			'			<pc_sample>'. $GB_WebHost .'soft/88.html</pc_sample>'. PHP_EOL .
			'			<wap_sample>'. $systemArr['SYS_wapUrl'] .'soft/88.html</wap_sample>'. PHP_EOL .
			'			<version>7</version>'. PHP_EOL .
			'		</display>'. PHP_EOL .
			'	</data>'. PHP_EOL .
			'</url>'. PHP_EOL .
			'<url>'. PHP_EOL .
			'	<loc>'. $GB_WebHost .'</loc>'. PHP_EOL .
			'	<data>'. PHP_EOL .
			'		<display>'. PHP_EOL .
			'			<pc_url_pattern>'. $GB_WebHost .'(\w+)/(\w+)/(\d+)\.html</pc_url_pattern>'. PHP_EOL .
			'			<url_pattern>'. $systemArr['SYS_wapUrl'] .'${1}/${2}/${3}.html</url_pattern>'. PHP_EOL .
			'			<pc_sample>'. $GB_WebHost .'html/seo/88.html</pc_sample>'. PHP_EOL .
			'			<wap_sample>'. $systemArr['SYS_wapUrl'] .'html/seo/88.html</wap_sample>'. PHP_EOL .
			'			<version>7</version>'. PHP_EOL .
			'		</display>'. PHP_EOL .
			'	</data>'. PHP_EOL .
			'</url>'. PHP_EOL .
			'<url>'. PHP_EOL .
			'	<loc>'. $GB_WebHost .'</loc>'. PHP_EOL .
			'	<data>'. PHP_EOL .
			'		<display>'. PHP_EOL .
			'			<pc_url_pattern>'. $GB_WebHost .'(\w+)/(\w+)/(\d+)/(\d+)\.html</pc_url_pattern>'. PHP_EOL .
			'			<url_pattern>'. $systemArr['SYS_wapUrl'] .'${1}/${2}/${3}/${4}.html</url_pattern>'. PHP_EOL .
			'			<pc_sample>'. $GB_WebHost .'html/seo/201609/88.html</pc_sample>'. PHP_EOL .
			'			<wap_sample>'. $systemArr['SYS_wapUrl'] .'html/seo/201609/88.html</wap_sample>'. PHP_EOL .
			'			<version>7</version>'. PHP_EOL .
			'		</display>'. PHP_EOL .
			'	</data>'. PHP_EOL .
			'</url>'. PHP_EOL .
			'<url>'. PHP_EOL .
			'	<loc>'. $GB_WebHost .'</loc>'. PHP_EOL .
			'	<data>'. PHP_EOL .
			'		<display>'. PHP_EOL .
			'			<pc_url_pattern>'. $GB_WebHost .'(\w+)/(\d+)/(\d+)\.html</pc_url_pattern>'. PHP_EOL .
			'			<url_pattern>'. $systemArr['SYS_wapUrl'] .'${1}/${2}/${3}.html</url_pattern>'. PHP_EOL .
			'			<pc_sample>'. $GB_WebHost .'news/2016/88.html</pc_sample>'. PHP_EOL .
			'			<wap_sample>'. $systemArr['SYS_wapUrl'] .'news/2016/88.html</wap_sample>'. PHP_EOL .
			'			<version>7</version>'. PHP_EOL .
			'		</display>'. PHP_EOL .
			'	</data>'. PHP_EOL .
			'</url>'. PHP_EOL .
			'<url>'. PHP_EOL .
			'	<loc>'. $GB_WebHost .'</loc>'. PHP_EOL .
			'	<data>'. PHP_EOL .
			'		<display>'. PHP_EOL .
			'			<pc_url_pattern>'. $GB_WebHost .'(\w+)/web_(\d+)\.html</pc_url_pattern>'. PHP_EOL .
			'			<url_pattern>'. $systemArr['SYS_wapUrl'] .'${1}/web_${2}.html</url_pattern>'. PHP_EOL .
			'			<pc_sample>'. $GB_WebHost .'news/web_88.html</pc_sample>'. PHP_EOL .
			'			<wap_sample>'. $systemArr['SYS_wapUrl'] .'news/web_88.html</wap_sample>'. PHP_EOL .
			'			<version>7</version>'. PHP_EOL .
			'		</display>'. PHP_EOL .
			'	</data>'. PHP_EOL .
			'</url>'. PHP_EOL .
			'';

		if ($systemArr['SYS_diyInfoTypeDir'] == 1 && $systemArr['SYS_htmlInfoTypeDir'] == 1){
			$typeexe = $DB->query('select IT_ID,IT_htmlName from '. OT_dbPref .'infoType');
			while ($row = $typeexe->fetch()){
				if (strlen($row['IT_htmlName'])>0){
					$soPatStr .= ''.
					PHP_EOL . $GB_WebHost . $row['IT_htmlName'] .'/ '. $systemArr['SYS_wapUrl'] . $row['IT_htmlName'] .'/'.
					PHP_EOL . $GB_WebHost . $row['IT_htmlName'] .'/index_(\d+).html '. $systemArr['SYS_wapUrl'] . $row['IT_htmlName'] .'/index_\1.html'.
					'';
					$shenmaPatStr .= ''.
					PHP_EOL . $GB_WebHost . $row['IT_htmlName'] .'/	'. $systemArr['SYS_wapUrl'] . $row['IT_htmlName'] .'/'.
					PHP_EOL . $GB_WebHost . $row['IT_htmlName'] .'/index_(\d+).html	'. $systemArr['SYS_wapUrl'] . $row['IT_htmlName'] .'/index_${1}.html'.
					'';
					$sogouPatStr .= ''.
						'<url>'. PHP_EOL .
						'	<loc>'. $GB_WebHost .'</loc>'. PHP_EOL .
						'	<data>'. PHP_EOL .
						'		<display>'. PHP_EOL .
						'			<pc_url_pattern>'. $GB_WebHost . $row['IT_htmlName'] .'/$</pc_url_pattern>'. PHP_EOL .
						'			<url_pattern>'. $systemArr['SYS_wapUrl'] . $row['IT_htmlName'] .'/</url_pattern>'. PHP_EOL .
						'			<pc_sample>'. $GB_WebHost . $row['IT_htmlName'] .'/</pc_sample>'. PHP_EOL .
						'			<wap_sample>'. $systemArr['SYS_wapUrl'] . $row['IT_htmlName'] .'/</wap_sample>'. PHP_EOL .
						'			<version>7</version>'. PHP_EOL .
						'		</display>'. PHP_EOL .
						'	</data>'. PHP_EOL .
						'</url>'. PHP_EOL .
						'<url>'. PHP_EOL .
						'	<loc>'. $GB_WebHost .'</loc>'. PHP_EOL .
						'	<data>'. PHP_EOL .
						'		<display>'. PHP_EOL .
						'			<pc_url_pattern>'. $GB_WebHost . $row['IT_htmlName'] .'/index_(\d+)\.html</pc_url_pattern>'. PHP_EOL .
						'			<url_pattern>'. $systemArr['SYS_wapUrl'] . $row['IT_htmlName'] .'/index_${1}.html</url_pattern>'. PHP_EOL .
						'			<pc_sample>'. $GB_WebHost . $row['IT_htmlName'] .'/index_88.html</pc_sample>'. PHP_EOL .
						'			<wap_sample>'. $systemArr['SYS_wapUrl'] . $row['IT_htmlName'] .'/index_88.html</wap_sample>'. PHP_EOL .
						'			<version>7</version>'. PHP_EOL .
						'		</display>'. PHP_EOL .
						'	</data>'. PHP_EOL .
						'</url>'. PHP_EOL .
						'';
				}
			
			}
			unset($typeexe);
		}

		$judResult = File::Write(OT_ROOT .'cache/web/site_360WapPat.txt',$soPatStr);
			if ($judResult){
				$alertStr .= '生成 ../cache/web/site_360WapPat.txt 成功.\n';
			}else{
				$alertStr .= '生成 ../cache/web/site_360WapPat.txt 失败.\n';
			}
		$judResult = File::Write(OT_ROOT .'cache/web/site_sogouWapPat.xml','<?xml version="1.0" encoding="UTF-8" ?><urlset>'. $sogouPatStr .'</urlset>');
			if ($judResult){
				$alertStr .= '生成 ../cache/web/site_sogouWapPat.xml 成功.\n';
			}else{
				$alertStr .= '生成 ../cache/web/site_sogouWapPat.xml 失败.\n';
			}
		$judResult = File::Write(OT_ROOT .'cache/web/site_shenmaWapPat.txt','<urlset>'. $shenmaPatStr .'</urlset>');
			if ($judResult){
				$alertStr .= '生成 ../cache/web/site_shenmaWapPat.txt 成功.\n';
			}else{
				$alertStr .= '生成 ../cache/web/site_shenmaWapPat.txt 失败.\n';
			}
	}

	if ($recordPage == 0){ $recordPage = 1; }
	$pageRale = OT::NumFormat($xmlFilePage/$recordPage);
	echo('
	<style>body{ text-align:left; margin:0 auto; font-size:12px; color:#000000; line-height:1.2; font-family:宋体; }</style>
	<div style="border:#15a4d0 1px solid; padding:0px; width:698px; height:28px; position:relative;">
		<div style="z-index:8; width:'. ($pageRale*698) .'px; position:absolute; height:28px; background:url(images/lineBg.jpg); font-size:1px;">&ensp;</div>
		<div style="z-index:88; position:absolute; color:#000000; line-height:28px; text-align:left; font-size:14px; padding-left:320px;">'. $xmlFilePage .'/'. $recordPage .'('. ($pageRale*100) .'%)</div>
		<div style="z-index:88; position:absolute; padding-left:8px; color:#000000; line-height:26px; text-align:left; font-size:12px;">'. str_replace(OT_ROOT,'../',$sitemapPath) .'(内含'. $xmlDataNum .'条记录)...生成成功</div>
	</div>
	');
	if ($xmlFilePage < $recordPage){
		JS::HrefEnd('siteMap_deal.php?mudi='. $mudi .'&dealMode='. $dealMode .'&oldFileNum='. $oldFileNum .'&scoreMode='. $scoreMode .'&newScore='. $newScore .'&homeThumbScore='. $homeThumbScore .'&thumbScore='. $thumbScore .'&flashScore='. $flashScore .'&imgScore='. $imgScore .'&marInfoScore='. $marInfoScore .'&recomScore='. $recomScore .'&topScore='. $topScore .'&isNew='. $isNew .'&isHomeThumb='. $isHomeThumb .'&isThumb='. $isThumb .'&isFlash='. $isFlash .'&isImg='. $isImg .'&isMarquee='. $isMarquee .'&isRecom='. $isRecom .'&isTop='. $isTop .'&recordMaxNum='. $recordMaxNum .'&pageMaxNum='. $pageMaxNum .'&hourDiff='. $hourDiff .'&updateFreq='. $updateFreq .'&updateTime='. $updateTime .'&updateTimeStr='. $updateTimeStr .'&xmlFilePage='. $xmlFilePage .'');
	}else{
		echo('
		<script language="javascript" type="text/javascript">parent.$id("oldFileNum").value="'. $recordPage .'";</script>
		');
		if (strlen($alertStr) > 0){ JS::AlertEnd($alertStr); }
	}
}



function dbMap(){
	global $DB,$mudi,$systemArr;

	$num		= OT::GetInt('num');
	$type		= OT::GetStr('type');
	$maxNum		= OT::PostInt('maxNum');
	$xiongMode	= OT::PostStr('xiongMode');
	$xiongNum	= OT::PostInt('xiongNum');
	$refDate1	= OT::PostStr('refDate1');
		if (! strtotime($refDate1)){ $refDate1=''; }
	$refDate2	= OT::PostStr('refDate2');
		if (! strtotime($refDate2)){ $refDate2=''; }

	if ($num == 0){
		$SQLstr = 'select count(IF_ID) from '. OT_dbPref .'info where IF_state=1 and IF_isAudit=1';
		$SQLstr10 = 'select count(IF_ID) from '. OT_dbPref .'info where IF_state=1 and IF_isAudit=1 and IF_isSitemap=0';
		$SQLstr11 = 'select count(IF_ID) from '. OT_dbPref .'info where IF_state=1 and IF_isAudit=1 and IF_isSitemap=1';
		$SQLstr12 = 'select count(IF_ID) from '. OT_dbPref .'info where IF_state=1 and IF_isAudit=1 and IF_isSitemap=2';
		$SQLstr20 = 'select count(IF_ID) from '. OT_dbPref .'info where IF_state=1 and IF_isAudit=1 and IF_isXiongzhang=0';
		$SQLstr21 = 'select count(IF_ID) from '. OT_dbPref .'info where IF_state=1 and IF_isAudit=1 and IF_isXiongzhang=1';
		$SQLstr22 = 'select count(IF_ID) from '. OT_dbPref .'info where IF_state=1 and IF_isAudit=1 and IF_isXiongzhang=2';
		if ($refDate1 != ''){
			$whereOne = ' and IF_time>='. $DB->ForTime($refDate1);
			$SQLstr		.= $whereOne;
			$SQLstr10	.= $whereOne;
			$SQLstr11	.= $whereOne;
			$SQLstr12	.= $whereOne;
			$SQLstr20	.= $whereOne;
			$SQLstr21	.= $whereOne;
			$SQLstr22	.= $whereOne;
		}
		if ($refDate2 != ''){
			$whereOne = ' and IF_time<='. $DB->ForTime(TimeDate::Add('d',1,$refDate2));
			$SQLstr		.= $whereOne;
			$SQLstr10	.= $whereOne;
			$SQLstr11	.= $whereOne;
			$SQLstr12	.= $whereOne;
			$SQLstr20	.= $whereOne;
			$SQLstr21	.= $whereOne;
			$SQLstr22	.= $whereOne;
		}

		echo('符合条件的文章'. $DB->GetOne($SQLstr) .'篇；其中 <span style="color:#000;font-weight:bold;">主动推送</span> 要推送 <span style="color:#000;">'. $DB->GetOne($SQLstr10) .'</span> 篇，不推送 '. $DB->GetOne($SQLstr12) .' 篇，已推送 '. $DB->GetOne($SQLstr11) .' 篇；');
		if (AppWap::Jud()){
			echo('<span style="color:#000;font-weight:bold;">熊掌号推送</span> 要推送 <span style="color:#000;">'. $DB->GetOne($SQLstr20) .'</span> 篇，不推送 '. $DB->GetOne($SQLstr22) .' 篇，已推送 '. $DB->GetOne($SQLstr21) .' 篇；');
		}

		
	}elseif ($num == 9){
		if ($type == 'xiongzhang'){
			$xiongzhangApiUrl	= OT::PostStr('xiongzhangApiUrl');
			if ($xiongzhangApiUrl == ''){
				echo('<span style="color:red;">熊掌号推送接口地址不能为空</span>');
			}
			$judResult = $DB->UpdateParam('sysAdmin',array('SA_bdXiongzhangUrl'=>$xiongzhangApiUrl),'1=1');
		}else{
			$sitemapApiUrl	= OT::PostStr('sitemapApiUrl');
			if ($sitemapApiUrl == ''){
				echo('<span style="color:red;">主动推送接口地址不能为空</span>');
			}
			$judResult = $DB->UpdateParam('sysAdmin',array('SA_bdSitemapUrl'=>$sitemapApiUrl),'1=1');
		}
		if ($judResult){
			$alertResult='成功';
		}else{
			$alertResult='失败';
		}

		echo('<span style="color:green;">新接口调用地址保存'. $alertResult .'！</span>');

	}else{
		$urlPcArr	= array();
		$urlWapArr	= array();
		$pcIdArr	= array();
		$wapIdArr	= array();

		$srow = $DB->GetRow('select SA_bdSitemapUrl,SA_bdXiongzhangUrl from '. OT_dbPref .'sysAdmin');
		if (strlen($srow['SA_bdXiongzhangUrl']) > 7){
			$whereStr = '(IF_isSitemap=0 or IF_isXiongzhang=0)';
		}else{
			$whereStr = 'IF_isSitemap=0';
		}

		$SQLstr = 'select IF_ID,IF_infoTypeDir,IF_datetimeDir,IF_isSitemap,IF_isXiongzhang from '. OT_dbPref .'info where IF_state=1 and IF_isAudit=1 and '. $whereStr;
		if ($refDate1 != ''){ $SQLstr .= ' and IF_time>='. $DB->ForTime($refDate1); }
		if ($refDate2 != ''){ $SQLstr .= ' and IF_time<='. $DB->ForTime(TimeDate::Add('d',1,$refDate2)); }
		$SQLstr .= ' order by IF_time DESC';

		if ($maxNum <= 0){ $maxNum=500; }
		$showRow=$DB->GetLimit($SQLstr,$maxNum,1);
		if (! $showRow){
			die('没有符合条件的文章。');
		}else{
			$rowCount = count($showRow);
			for ($i=0; $i<$rowCount; $i++){
				if ($showRow[$i]['IF_isSitemap'] == 0){
					$pcIdArr[] = $showRow[$i]['IF_ID'];
					$urlPcArr[] = GetUrl::CurrDir(1) . Url::NewsID($showRow[$i]['IF_infoTypeDir'],$showRow[$i]['IF_datetimeDir'],$showRow[$i]['IF_ID']);
				}
				if ($showRow[$i]['IF_isXiongzhang'] == 0){
					if (count($wapIdArr) < $xiongNum){
						$wapIdArr[] = $showRow[$i]['IF_ID'];
						if ($xiongMode == 'pc'){
							$urlWapArr[] = GetUrl::CurrDir(1) . Url::NewsID($showRow[$i]['IF_infoTypeDir'],$showRow[$i]['IF_datetimeDir'],$showRow[$i]['IF_ID']);
						}else{
							$urlWapArr[] = $systemArr['SYS_wapUrl'] . Url::NewsID($showRow[$i]['IF_infoTypeDir'],$showRow[$i]['IF_datetimeDir'],$showRow[$i]['IF_ID']);
						}
					}
				}
			}
		}
		unset($listexe);

		$cnResStr = '<h3>【主动推送】</h3>';
		if (count($urlPcArr) > 0){
			$newsStr	= implode(PHP_EOL,$urlPcArr);
			$newsIdStr	= implode(',',$pcIdArr);

			$result = PostUrl($srow['SA_bdSitemapUrl'], $newsStr);
			if (strpos($result,'"error"') !== false){
				$cnResStr .= '错误码：'. Str::GetMark($result,'"error":',',') .'，描述：'. Str::GetMark($result,'"message":"','"') .'';
			}else{
				$successNum = OT::ToInt(Str::GetMark($result,'"success":','}'),-1);
				$remainNum = OT::ToInt(Str::GetMark($result,'"remain":',','),-1);
				if ($successNum == -1 && $remainNum == -1){
					$cnResStr .= '数据反馈异常，请重新推送。';
				}else{
					$url1 = str_replace(array('","','\\/'),array('<br />','/'),Str::GetMark($result,'"not_valid":["','"]'));
					$url2 = str_replace(array('","','\/'),array('<br />','/'),Str::GetMark($result,'"not_same_site":["','"]'));
					if (strlen($url1) > 5){ $url1Str = '不合法的url列表:<br />'. $url1 .'<br />'; }else{ $url1Str=''; }
					if (strlen($url2) > 5){ $url2Str = '由于不是本站url而未处理的url列表（提醒：带www和不带www会被视为不同域名，接口所属域名：<span style="color:red;">'. Str::GetMark($srow['SA_bdSitemapUrl'],'site=','&') .'</span>）:<br />'. $url2 .''; }else{ $url2Str=''; }
					$cnResStr .= '成功推送条数：'. $successNum .'，今日还能推送条数：'. $remainNum .'<br />'. $url1Str . $url2Str .'<div>'. implode('<br />',$urlPcArr) .'</div>';

					if (strlen($url1 . $url2) > 3){
						$newsIdStr	= ','. $newsIdStr .',';
						if (strlen($url1) > 3){
							$url1Arr = explode('<br />',$url1);
							foreach ($url1Arr as $urlOne){
								$urlOne = str_replace(array('?','.html'), array('',''), substr($urlOne,strrpos($urlOne,'/')));
								$newsIdStr = str_replace(','. $urlOne .',', ',', $newsIdStr);
								// echo('['. $urlOne .']');
							}
						}
						if (strlen($url2) > 3){
							$url2Arr = explode('<br />',$url2);
							foreach ($url2Arr as $urlOne){
								$urlOne = str_replace(array('?','.html'), array('',''), substr($urlOne,strrpos($urlOne,'/')));
								$newsIdStr = str_replace(','. $urlOne .',',',',$newsIdStr);
								// echo('['. $urlOne .']');
							}
						}
						if (strlen($newsIdStr) < 2){
							$newsIdStr = '';
						}else{
							$newsIdStr = substr(substr($newsIdStr,0,strlen($newsIdStr)-1), 0, strlen($newsIdStr)-2);
						}
					}
					if (strlen($newsIdStr) > 0){
						$DB->query('update '. OT_dbPref .'info set IF_isSitemap=1 where IF_ID in ('. $newsIdStr .')');
					}
				}
			}
			echo('<div style="line-height:1.6;color:blue;">'. $cnResStr .'<div style="margin-top:15px;color:red;">原始返回信息：<br />'. $result .'</div></div>');
		}else{
			echo('<div style="line-height:1.6;color:blue;">'. $cnResStr .'<div style="margin-top:15px;">无符合条件的文章</div></div>');
		}

		if ($xiongNum > 0 && strlen($srow['SA_bdXiongzhangUrl']) > 7){
			$cnResStr = '<h3 style="margin-top:18px;">【熊掌号推送】</h3>';
			if (count($urlWapArr) > 0){
				$newsStr	= implode(PHP_EOL, $urlWapArr);
				$newsIdStr	= implode(',',$wapIdArr);

				$result = PostUrl($srow['SA_bdXiongzhangUrl'], $newsStr);
				if (strpos($result,'"error"') !== false){
					$cnResStr .= '错误码：'. Str::GetMark($result,'"error":',',') .'，描述：'. Str::GetMark($result,'"message":"','"') .'';
				}else{
					$successNum = OT::ToInt(Str::GetMark($result,'"success_realtime":',','),-1);
					$remainNum = OT::ToInt(Str::GetMark($result,'"remain_realtime":','}'),-1);
						if ($successNum == -1){ $successNum = OT::ToInt(Str::GetMark($result,'"success":',','),-1); }
						if ($remainNum == -1){ $remainNum = OT::ToInt(Str::GetMark($result,'"remain":',','),-1); }
					if ($successNum == -1 && $remainNum == -1){
						$cnResStr .= '数据反馈异常，请重新推送。';
					}else{
						$url1 = str_replace(array('","','\\/'),array('<br />','/'),Str::GetMark($result,'"not_valid":["','"]'));
						$url2 = str_replace(array('","','\/'),array('<br />','/'),Str::GetMark($result,'"not_same_site":["','"]'));
						if (strlen($url1) > 5){ $url1Str = '不合法的url列表:<br />'. $url1 .'<br />'; }else{ $url1Str=''; }
						if (strlen($url2) > 5){ $url2Str = '由于不是本站url而未处理的url列表（提醒：带www和不带www会被视为不同域名）:<br />'. $url2 .''; }else{ $url2Str=''; }
						$cnResStr .= '成功推送条数：'. $successNum .'，今日还能推送条数：'. $remainNum .'<br />'. $url1Str . $url2Str .'<div>'. implode('<br />',$urlWapArr) .'</div>';

						if (strlen($url1 . $url2) > 3){
							$newsIdStr	= ','. $newsIdStr .',';
							if (strlen($url1) > 3){
								$url1Arr = explode('<br />',$url1);
								foreach ($url1Arr as $urlOne){
									$urlOne = str_replace(array('?','.html'), array('',''), substr($urlOne,strrpos($urlOne,'/')));
									$newsIdStr = str_replace(','. $urlOne .',', ',', $newsIdStr);
									// echo('['. $urlOne .']');
								}
							}
							if (strlen($url2) > 3){
								$url2Arr = explode('<br />',$url2);
								foreach ($url2Arr as $urlOne){
									$urlOne = str_replace(array('?','.html'), array('',''), substr($urlOne,strrpos($urlOne,'/')));
									$newsIdStr = str_replace(','. $urlOne .',',',',$newsIdStr);
									// echo('['. $urlOne .']');
								}
							}
							if (strlen($newsIdStr) < 2){
								$newsIdStr = '';
							}else{
								$newsIdStr = substr(substr($newsIdStr,0,strlen($newsIdStr)-1), 0, strlen($newsIdStr)-2);
							}
						}
						if (strlen($newsIdStr) > 0){
							$DB->query('update '. OT_dbPref .'info set IF_isXiongzhang=1 where IF_ID in ('. $newsIdStr .')');
						}
					}
				}
				echo('<div style="line-height:1.6;color:blue;">'. $cnResStr .'<div style="margin-top:15px;color:red;">原始返回信息：<br />'. $result .'</div></div>');
			}else{
				echo('<div style="line-height:1.6;color:blue;">'. $cnResStr .'<div style="margin-top:15px;">无符合条件的文章</div></div>');
			}
		}

		// <form method='post' action='". $srow['SA_bdSitemapUrl'] ."' target='_blank'><input type='hidden' name='1' value='". $newsStr ."' /><input type='submit' value='新窗口提交' /></form>
	}

}



function down(){
	$fileName = OT::GetStr('fileName');

	if (substr($fileName,0,13) == 'site_baiduWap' && substr($fileName,-4) == '.txt'){
		File::Download(OT_ROOT .'cache/web/'. $fileName,$fileName);
	}
}



function PostUrl($api,$urls){
	$ch = curl_init();
	$options =  array(
		CURLOPT_URL => $api,
		CURLOPT_POST => true,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_POSTFIELDS => $urls,
		CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),
	);
	curl_setopt_array($ch, $options);
	return curl_exec($ch);
}

?>
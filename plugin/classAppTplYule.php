<?php

if(! defined('OT_ROOT')) {
	exit('Access Denied');
}


class AppTplYule{

	// 导航菜单
	public static function NavMenu($paraStr,$judGet=true){
		global $DB,$tpl;

		$judCache = false;
		$cacheName='func_NavMenu_'. $paraStr .'_'. strlen($tpl->webPathPart);

		if ($retStr = Cache::CheckWebCache($cacheName)){ $judCache = true; }
		if ($judCache == false){
			$retStr = '';
			if ($tpl->webHost == ''){ $GB_NavHost=$tpl->webPathPart; }else{ $GB_NavHost=$tpl->webHost; }

			if ($tpl->tplSysArr['TS_navNum']>0){ $num=$tpl->tplSysArr['TS_navNum']; }else{ $num=100; }

			$menuRow = $DB->GetLimit('select IT_ID,IT_theme,IT_themeStyle,IT_mode,IT_webID,IT_URL,IT_isEncUrl,IT_openMode,IT_level,IT_htmlName from '. OT_dbPref .'infoType where IT_state=1 and IT_isMenu=1 order by IT_rank ASC',$num);
			if ($menuRow){
				$rowCount = count($menuRow);
				for ($i=0; $i<$rowCount; $i++){
					$hrefStr = Area::InfoTypeUrl(array(
						'IT_mode'		=> $menuRow[$i]['IT_mode'],
						'IT_ID'			=> $menuRow[$i]['IT_ID'],
						'IT_webID'		=> $menuRow[$i]['IT_webID'],
						'IT_URL'		=> $menuRow[$i]['IT_URL'],
						'IT_isEncUrl'	=> $menuRow[$i]['IT_isEncUrl'],
						'IT_htmlName'	=> $menuRow[$i]['IT_htmlName'],
						'mainUrl'		=> '',
						'webPathPart'	=> $tpl->webPathPart,
						));

					$retStr .= '<li id="tabmenu'. ($i+1) .'" class="b"><div class="itemMenu"><a href="'. $hrefStr .'" style="'. $menuRow[$i]['IT_themeStyle'] .'" target="'. $menuRow[$i]['IT_openMode'] .'"><span>'. $menuRow[$i]['IT_theme'] .'</span></a></div>'. PHP_EOL;

						if ($menuRow[$i]['IT_level'] == 1){
							if (in_array($tpl->tplSysArr['TS_navMode'],array(21,26))){
								$menu2exe = $DB->query('select IT_ID,IT_theme,IT_themeStyle,IT_mode,IT_webID,IT_URL,IT_isEncUrl,IT_openMode,IT_htmlName from '. OT_dbPref .'infoType where IT_state=1 and IT_fatID='. $menuRow[$i]['IT_ID'] .' and IT_isSubMenu=1 order by IT_rank ASC');
								if ($row2 = $menu2exe->fetch()){
									$retStr .= '<ul class="subnav">';
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
										$retStr .= '
										<li><a href="'. $hrefStr .'" target="'. $row2['IT_openMode'] .'" style="'. $row2['IT_themeStyle'] .'">'. $row2['IT_theme'] .'</a></li>';
									}while ($row2 = $menu2exe->fetch());
									$retStr .= '</ul>';
								}
								$menu2exe = null;

							}
						}
						$retStr .= '</li>';
				}
			}
			unset($menuexe);
					
			$retStr .= '<div class="clr"></div>';

			Cache::WriteWebCache($cacheName,$retStr);

		}

		if ($judGet){
			return $retStr;
		}else{
			echo($retStr);
		}
	}


	// 最近更新
	public static function NewList($paraStr,$judGet=true){
		global $DB,$tpl;

		$retStr = '';
		$topWhereStr = '';
		$topIdArr = array();

		$paraArr = explode(',',$paraStr);
		$pageNum	= $paraArr[0];
		$pageCount	= $paraArr[1];
		$num = intval($pageNum) * intval($pageCount);

		$todayDate = TimeDate::Get('date');
		$redDay = $tpl->tplSysArr['TS_redTimeDay']-1;

		$newsNum = 0;
		if ($tpl->tplSysArr['TS_homeNewTopNum'] > 0){
			$newRow = $DB->GetLimit('select IF_ID,IF_time,IF_theme,IF_themeStyle,IF_URL,IF_isEncUrl,IF_infoTypeDir,IF_datetimeDir from '. OT_dbPref .'info where IF_state=1 and IF_isAudit=1 and IF_isTop=1 and IF_isNew=1'. OT_TimeInfoWhereStr .' order by IF_time DESC',$tpl->tplSysArr['TS_homeNewTopNum']);
			if ($newRow){
				$rowCount = count($newRow);
				for ($i=0; $i<$rowCount; $i++){
					$newsNum ++;
					$topIdArr[] = $newRow[$i]['IF_ID'];
					if ($newRow[$i]['IF_URL']!=''){
						$hrefStr = Url::NewsUrl($newRow[$i]['IF_URL'],$newRow[$i]['IF_isEncUrl'],$newRow[$i]['IF_ID'],$tpl->webPathPart);
					}else{
						$hrefStr = Url::NewsID($newRow[$i]['IF_infoTypeDir'],$newRow[$i]['IF_datetimeDir'],$newRow[$i]['IF_ID']);
					}
					if ($tpl->tplSysArr['TS_homeNewIsDate'] == 1){
						if (TimeDate::Diff('d',$newRow[$i]['IF_time'],$todayDate) <= $redDay){ $newTimeClass = 'font2_1 redFontClass'; }else{ $newTimeClass = 'font1_1 defFontClass'; }
						$timeStr = '<span class="'. $newTimeClass .'">'. TimeDate::Get('m-d',$newRow[$i]['IF_time']) .'</span>';
					}else{
						$timeStr = '';
					}
					$retStr .= '
					<li class="column half font">
						'. $timeStr .'
						<img src="'. $tpl->webPathPart . $tpl->tplDir .'images/top.jpg" alt="置顶">
						<a href='. $hrefStr .' class="font1_1" style="'. $newRow[$i]['IF_themeStyle'] .'" title="'. Str::MoreReplace($newRow[$i]['IF_theme'],'input') .'" target="_blank">'. $newRow[$i]['IF_theme'] .'</a>
					</li>
					';
				}
			}
			unset($newRow);
		}

		if (count($topIdArr)>0){
			$topWhereStr .= ' and IF_ID not in ('. implode(',', $topIdArr) .')';
		}

		$num -= $newsNum;
		if ($num<=0){ $num=10; }

		$newexe = $DB->query('select IF1.IF_ID,IF2.IF_time,IF2.IF_theme,IF2.IF_themeStyle,IF2.IF_URL,IF2.IF_isEncUrl,IF2.IF_typeStr,IF2.IF_infoTypeDir,IF2.IF_datetimeDir from (select IF_ID from '. OT_dbPref .'info where IF_state=1 and IF_isAudit=1 and IF_isNew=1'. $topWhereStr . OT_TimeInfoWhereStr .' order by IF_time DESC limit 0,'. $num .') as IF1 inner join '. OT_dbPref .'info as IF2 on IF1.IF_ID=IF2.IF_ID order by IF2.IF_time DESC');
		while ($row = $newexe->fetch()){
			$newsNum ++;

			if ($newsNum % $pageNum == 1 && $newsNum > $pageNum){ $retStr .= '</ul><ul style="display: none;">';}

			if ($row['IF_URL']!=''){
				$hrefStr = Url::NewsUrl($row['IF_URL'],$row['IF_isEncUrl'],$row['IF_ID'],$tpl->webPathPart);
			}else{
				$hrefStr = Url::NewsID($row['IF_infoTypeDir'],$row['IF_datetimeDir'],$row['IF_ID']);
			}

			if ($tpl->tplSysArr['TS_homeNewIsDate'] == 1){
				if (TimeDate::Diff('d',$row['IF_time'],$todayDate) <= $redDay){ $newTimeClass = 'font2_1 redFontClass'; }else{ $newTimeClass = 'font1_1 defFontClass'; }
				$timeStr = '<span class="'. $newTimeClass .'">'. TimeDate::Get('m-d',$row['IF_time']) .'</span>';
			}else{
				$timeStr = '';
			}

			$retStr .= '
				<li class="column half font">
					'. $timeStr .'
					<img src="'. $tpl->webPathPart . $tpl->tplDir .'images/new.png" alt="新">
					<a href='. $hrefStr .' class="font1_1" style="'. $row['IF_themeStyle'] .'" title="'. Str::MoreReplace($row['IF_theme'],'input') .'" target="_blank">'. $row['IF_theme'] .'</a>
				</li>
				'. PHP_EOL;

		}
		unset($newexe);

		if ($judGet){
			return $retStr;
		}else{
			echo($retStr);
		}
	}



	// 信息栏目(首页三栏式)	$num = $paraStr
	public static function ItemList3($num,$judGet=true){
		global $DB,$tpl;

		/* $judCache = false;
		$cacheName='func_ItemList3_'. $num .'_'. strlen($tpl->webPathPart);

		if ($retStr = Cache::CheckWebCache($cacheName)){ $judCache = true; }
		if ($judCache == false){
		*/
			$retStr = '';
			$dataNum = 1;
			$adNum = 0;
			if ($num<=0){ $num=200; }

			$typeexe = $DB->query('select IT_ID,IT_level,IT_mode,IT_webID,IT_URL,IT_isEncUrl,IT_homeIco,IT_isHomeImg,IT_openMode,IT_theme,IT_homeNum,IT_isItemDate,IT_isItemType,IT_htmlName,IT_webID from '. OT_dbPref .'infoType where IT_state=1 and IT_isHome=1 order by IT_itemRank ASC,IT_rank ASC limit 0,'. $num .'');
			$typeRow = $typeexe->fetchAll();
			if ($typeRow){
				$rowCount = count($typeRow);
				for ($i=0; $i<$rowCount; $i++){
					if ($typeRow[$i]['IT_isHomeImg'] > 0){ $imgStr=TplIndex::ImgItem($typeRow[$i]['IT_ID'],$typeRow[$i]['IT_level'],$typeRow[$i]['IT_isHomeImg']); }else{ $imgStr = ''; }
					$moreHref = Area::InfoTypeUrl(array(
						'IT_mode'		=> $typeRow[$i]['IT_mode'],
						'IT_ID'			=> $typeRow[$i]['IT_ID'],
						'IT_webID'		=> $typeRow[$i]['IT_webID'],
						'IT_URL'		=> $typeRow[$i]['IT_URL'],
						'IT_isEncUrl'	=> $typeRow[$i]['IT_isEncUrl'],
						'IT_htmlName'	=> $typeRow[$i]['IT_htmlName'],
						'mainUrl'		=> '',
						'webPathPart'	=> $tpl->webPathPart,
						));
					if ($typeRow[$i]['IT_openMode'] == '_blank'){ $moreHref2 = 'var a=window.open("'. $moreHref .'");'; }else{ $moreHref2 = 'document.location.href="'. $moreHref .'";'; }
					$itemStyleStr='';
					if ($dataNum % 3 == 0){
						$itemStyleStr='margin-right:0px;';
					}else{
						$itemStyleStr='';
					}
					if ($dataNum<=3){ $itemStyleStr .= 'margin-top:0px;'; }
						$retStr .= '
						<div class="homeItemOne" style="'. $itemStyleStr .'">
						<div class="itemBox">
							<div class="itemTitle itemTitle2">
								<a href="'. $moreHref .'" target="'. $typeRow[$i]['IT_openMode'] .'"><span></span>'. $typeRow[$i]['IT_theme'] .'</a>
							</div>
							<div class="itemContent">
								<dl class="itemListDl">
								<dd class="listArrow3 listIco'. $typeRow[$i]['IT_homeIco'] .'">
									<ul>
										'. $imgStr .
										TplIndex::FontItem($typeRow[$i]['IT_ID'],$typeRow[$i]['IT_level'],$typeRow[$i]['IT_homeNum'],$typeRow[$i]['IT_isItemDate'],$typeRow[$i]['IT_isItemType']) .'
									</ul>
								</dd>
								</dl>
							</div>
						</div>
						</div>
						';
					if ($dataNum % 3 == 0){
						$retStr .= '<div class="clr"></div>';
						if ($adNum<10){
							$adNum ++;
							$retStr .= '
							<div class="caClass mainCa1 ca'. ($adNum+50) .'Style">
								<script type="text/javascript">OTca("ot0'. ($adNum+50) .'");</script>
							</div>
							<div class="clr"></div>
							';
						}
					}
					$dataNum ++;
				}
			}
			unset($typeexe);

			$retStr .= '<div class="clr"></div>'. PHP_EOL;

			/* Cache::WriteWebCache($cacheName,$retStr);

		}
		*/
		if ($judGet){
			return $retStr;
		}else{
			echo($retStr);
		}
	}



	// 友情链接
	public static function LinkBox($paraStr,$judGet=true){
		global $DB,$tpl;

		$retStr = $listStyleStr = '';
		if (strpos($tpl->tplSysArr['TS_logoAreaStr'],'|'. $tpl->webTypeName .'|') !== false){
			$logoAddStr = '';
			if (AppLogoAdd::Jud() && $tpl->tplSysArr['TS_isLogoAdd'] == 1){
				$logoAddStr = '<a style="float:right;padding:0 10px 0 0;font-size:14px;color:#000;" class="font1_1" href="'. $tpl->webPathPart .'logoAdd.php" target="_blank">[友情链接申请]</a>';
			}
			if ($tpl->tplSysArr['TS_logoListMode'] >= 5){
				$listStyleStr = ' linkList linkRow'. $tpl->tplSysArr['TS_logoListMode'];
			}

			$retStr .= '
			<div class="itemBox" style="text-align:left;">
				<div class="itemTitle linkTitle">'. $logoAddStr .'友情链接&ensp;<span style="font-size:14px;font-weight:normal;">'. $tpl->tplSysArr['TS_logoNote'] .'</span></div>
				<div class="itemContent linkMain'. $listStyleStr .'">
					'. TplBottom::LogoBox() .'
				</div>
				</div>
			</div>
			';
		}

		if ($judGet){
			return $retStr;
		}else{
			echo($retStr);
		}
	}


	// 列表页
	public static function NewsList($paraStr,$judGet=true){
		global $DB,$tpl;

		$retStr = '';
		$paraArr = explode(",",$paraStr);
		$areaName	= $paraArr[0];
		$showMode	= $paraArr[1];
		$showNum	= intval($paraArr[2]);
		$typeStr	= $paraArr[3];
		$typeLevel	= $paraArr[4];
		if ($showNum == 0){ $showNum=10; }
		$typeStrID = 0;
		$newRecordNum = 0;
		$listHtmlName = $listInfo = '';

		if ($typeStr == 'new' && $tpl->tplSysArr['TS_homeNewMoreNum']>0){
			$SQLstr = 'select IF_ID,IF_URL,IF_isEncUrl,IF_isTop,IF_isRecom,IF_writer,IF_readNum,IF_replyNum,IF_img,IF_time,IF_typeStr,IF_theme,IF_themeStyle,IF_themeKey,IF_contentKey,IF_infoTypeDir,IF_datetimeDir from '. OT_dbPref .'info where IF_state=1 and IF_isAudit=1 and IF_isNew=1'. OT_TimeInfoWhereStr;
			$newRecordNum = $tpl->tplSysArr['TS_homeNewMoreNum'];
		}else{
			$SQLstr = 'select IF_ID,IF_URL,IF_isEncUrl,IF_isTop,IF_isRecom,IF_writer,IF_readNum,IF_replyNum,IF_img,IF_time,IF_typeStr,IF_theme,IF_themeStyle,IF_themeKey,IF_contentKey,IF_infoTypeDir,IF_datetimeDir from '. OT_dbPref .'info where IF_state=1 and IF_isAudit=1'. OT_TimeInfoWhereStr;
		}
		switch ($typeStr){
			case 'announ':
				$SQLstr .= ' and IF_type1ID=-1 order by IF_isTop DESC,IF_time DESC';
				if ($tpl->tplSysArr['TS_homeAnnounListNum']>0){ $showNum=$tpl->tplSysArr['TS_homeAnnounListNum']; }
				break;

			case 'refer':
				if (strlen($tpl->refType) == 0){ $tpl->refType = OT::GetStr('refType'); }
				if (strlen($tpl->refContent) == 0){ $tpl->refContent = OT::GetRegExpStr('refContent','sql+ '); }
				if (strpos('|content|source|writer|','|'. $tpl->refType .'|') === false){ $tpl->refType='theme'; }
				$addiStr = '';
				if ($tpl->refType == 'content'){
					$addiArr = array();
					$infoSysArr = Cache::PhpFile('infoSys');
					for ($i=1; $i<=$infoSysArr['IS_tabNum']; $i++){
						$addiArr[] = "select IC_ID from ". OT_dbPref ."infoContent". $i ." where IC_content like '%". $DB->ForStr($tpl->refContent,false) ."%'";
					}
					if (count($addiArr) > 0){
						$addiStr = 'or IF_ID in ('. implode(' union ',$addiArr) .')';
					}
				}
				$SQLstr .= ' and (IF_'. $tpl->refType ." like '%". $DB->ForStr($tpl->refContent,false) ."%'". $addiStr .") order by IF_isTop DESC,IF_time DESC";
				if ($tpl->tplSysArr['TS_searchListNum']>0){ $showNum = $tpl->tplSysArr['TS_searchListNum']; }
				break;

			case 'mark':
				if ($tpl->tplSysArr['TS_isMark'] == 0){
					header('HTTP/1.0 404 Not Found');
					header('Status: 404 Not Found');
					JS::AlertHrefEnd('标签页已被关闭。',$tpl->dbPathPart . $tpl->tplSysArr['TS_subWeb404']);
				}
				if (strlen($tpl->markName) == 0){ $tpl->markName = OT::GetRegExpStr('markName','sql'); }
				if ($tpl->markName != ''){
					$SQLstr .= " and IF_themeKey like '%". $DB->ForStr($tpl->markName,false) ."%'";
				}
				$SQLstr .= ' order by IF_time DESC';
				if ($tpl->tplSysArr['TS_markListNum']>0){ $showNum=$tpl->tplSysArr['TS_markListNum']; }
				break;

			case 'user':
				if (strlen($tpl->markName) == 0){ $tpl->markName = OT::GetStr('markName'); }
				$tpl->markName = intval($tpl->markName);
				if ($tpl->markName == 0){
					$judUserNews = 1;
					$SQLstr .= ' and IF_userID>=1 order by IF_time DESC';
				}else{
					$SQLstr .= ' and IF_userID='. $tpl->markName .' order by IF_time DESC';
				}
				break;

			case 'topic':
				if (strlen($tpl->markName) == 0){ $tpl->markName = OT::GetStr('markName'); }
				$tpl->markName = intval($tpl->markName);
				$SQLstr .= ' and IF_topicID='. $tpl->markName .' order by IF_time DESC';
				$topicPageNum = intval($DB->GetOne('select IW_pageNum from '. OT_dbPref .'infoWeb where IW_ID='. $tpl->markName));
					if ($topicPageNum>0){ $showNum=$topicPageNum; }
				break;

			default :
				$typeStrID = intval($typeStr);
				if ($typeStrID>0){
					if ($typeLevel>=1 && $typeLevel<=3){
						$SQLstr .= ' and IF_type'. $typeLevel .'ID='. $typeStrID .' order by IF_isTop DESC,IF_time DESC';
					}else{
						$SQLstr .= " and IF_typeStr like '%,". $typeStrID .",%' order by IF_isTop DESC,IF_time DESC";
					}
					$itRow = $DB->GetRow('select IT_htmlName,IT_listInfo from '. OT_dbPref .'infoType where IT_ID='. $typeStrID);
						if ($itRow){
							$listHtmlName = $itRow['IT_htmlName'];
							$listInfo = $itRow['IT_listInfo'];
						}
				}else{
					if ($typeStr == 'new'){
						$SQLstr .= ' and IF_isNew=1 order by IF_time DESC';
					}else{
						$SQLstr .= ' order by IF_isTop DESC,IF_time DESC';
					}
					if ($tpl->tplSysArr['TS_homeNewListNum']>0){ $showNum=$tpl->tplSysArr['TS_homeNewListNum']; }
				}
				break;
		}

		$pageSize	= $showNum;		//每页条数
		if ($tpl->page ==0){ $tpl->page = OT::GetInt('page'); }
		$showRow = $DB->GetLimit($SQLstr,$pageSize,$tpl->page);
		if (! $showRow){
			if ($typeStr == 'mark'){
				header('HTTP/1.0 404 Not Found');
				header('Status: 404 Not Found');
				JS::AlertHrefEnd('没搜索到相关标签文章。',$tpl->dbPathPart . $tpl->tplSysArr['TS_subWeb404']);
			}elseif ($typeStr == 'refer'){
				$retStr .= '<meta name="robots" content="noindex"><br /><center class="font1_1">暂无内容</center><br />';
			}else{
				$retStr .= '<br /><center class="font1_1">暂无内容</center><br />';
			}
		}else{
			$recordCount=$DB->GetRowCount();
			if ($newRecordNum > 0 && $newRecordNum < $recordCount){ $recordCount = $newRecordNum; }
			$pageCount=ceil($recordCount/$pageSize);
			if ($tpl->page < 1 || $tpl->page > $pageCount){$tpl->page=1;}

			$number=1+($tpl->page-1)*$pageSize;
			$rowCount = count($showRow);

			$todayDate = TimeDate::Get('date');
			$redDay = $tpl->tplSysArr['TS_redTimeDay']-1;

			for ($i=0; $i<$rowCount; $i++){
				if ($showRow[$i]['IF_URL'] != ''){
					$hrefStr = Url::NewsUrl($showRow[$i]['IF_URL'],$showRow[$i]['IF_isEncUrl'],$showRow[$i]['IF_ID'],$tpl->webPathPart);
				}else{
					$hrefStr = Url::NewsID($showRow[$i]['IF_infoTypeDir'],$showRow[$i]['IF_datetimeDir'],$showRow[$i]['IF_ID']);
				}
				$themeImgStr = $writerStr = $timeStr = $infoTypeStr = '';
				if ($showRow[$i]['IF_isTop'] == 1){
					$themeImgStr = '[置顶]';
				}elseif ($showRow[$i]['IF_isRecom'] == 1){
					$themeImgStr = '[推荐]';
				}
				if (strpos($listInfo,'|noInfoType|') === false){
					$currTypeArr	= TplIndex::InfoTypeCN($showRow[$i]['IF_typeStr']);
					/* if ($typeStr == 'announ'){
						$homeTypeStr = '<a href="'. Url::ListStr($typeStr,0) .'" title="查看该分类下全部文章" rel="category tag">'. $currTypeArr['theme'] .'</a>';
					}elseif ($typeStrID > 0){
						$homeTypeStr = '<a href="'. Url::ListID('',$currTypeArr['htmlName'],$typeStrID,0) .'" title="查看该分类下全部文章" rel="category tag">'. $currTypeArr['theme'] .'</a>';
					}else{ */
						if ($showRow[$i]['IF_typeStr'] == 'announ'){
							$homeTypeStr = '<a href="'. Url::ListStr($showRow[$i]['IF_typeStr'],0) .'" title="查看该分类下全部文章" rel="category tag">'. $currTypeArr['theme'] .'</a>';
						}else{
							$homeTypeStr = '<a href="'. Url::ListID('',$currTypeArr['htmlName'],$currTypeArr['id'],0) .'" title="查看该分类下全部文章" rel="category tag">'. $currTypeArr['theme'] .'</a>';
						}
					// }
					$infoTypeStr = '<span class="newsType"><i class="icon-category"></i>'. $homeTypeStr .'</span>';
				}
				if (strpos($listInfo,'|noTime|') === false){
					if (TimeDate::Diff('d',$showRow[$i]['IF_time'],$todayDate) <= $redDay){ $newTimeClass = 'font2_1 redFontClass'; }else{ $newTimeClass = 'font1_1 defFontClass'; }
					$timeStr = '<span class="newsTime"><i class="icon-calendar"></i><span class="'. $newTimeClass .'">'. TimeDate::Get('Y-m-d H:i',$showRow[$i]['IF_time']) .'</span></span>';
				}
				if (strpos($listInfo,'|noWriter|') === false){
					$writerStr = '<span class="newsWriter"><i class="icon-user-add"></i><a>'. $showRow[$i]['IF_writer'] .' </a></span>';
				}
				$themeInputStr = Str::MoreReplace($showRow[$i]['IF_theme'],'input');
				
				$retStr .= '
				<div class="listArea">
					<div class="titleBox">
						<h2>'. $themeImgStr .' <a href="'. $hrefStr .'" style="'. $showRow[$i]['IF_themeStyle'] .'" target="_blank">'. $showRow[$i]['IF_theme'] .'</a></h2>
					</div>
					<div class="readBtn"><a href="'. $hrefStr .'" target="_blank">阅读全文&gt;&gt;</a></div>
					<div class="newsAddi">'. $writerStr . $timeStr . $infoTypeStr .'</div>
					<div class="clr"></div>
				</div>
				';
			}

			$retStr .= '<center>'. TplList::NewsListNav($typeStr,$listHtmlName,$typeStrID,$tpl->page,$pageCount,$pageSize,$recordCount) .'</center>';
		}
		unset($showRow);

		if ($judGet){
			return $retStr;
		}else{
			echo($retStr);
		}
	}
	
}


?>
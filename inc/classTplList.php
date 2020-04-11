<?php

if(! defined('OT_ROOT')) {
	exit('Access Denied');
}


class TplList{
	public static function NewsList($areaName,$showMode,$showNum,$typeStr,$typeLevel,$judGet=true){
		global $DB,$tpl;

		$retStr = '';

		if ($showMode == 0){ $showMode=2; }
		if ($showMode == 4){
			$showModeClass = 2;
		}elseif ($showMode == 7){
			$showModeClass = 5;
		}else{
			$showModeClass = $showMode;
		}
		if ($showNum == 0){ $showNum=10; }

		if (in_array($showMode,array(5,6))){
			if ($showMode == 5){
				$itemBoxClass	= 'pageBoxMore';
				$itemBoxCol		= 1;
			}else{
				$itemBoxClass	= 'itemBox';
				$itemBoxCol		= 2;
			}

			$typeStr = intval($typeStr);
			$itemBoxNum=0;

			$typeexe=$DB->query('select IT_ID,IT_level,IT_mode,IT_theme,IT_webID,IT_URL,IT_isEncUrl,IT_htmlName,IT_listInfo from '. OT_dbPref .'infoType where IT_state=1 and IT_fatID='. $typeStr .' order by IT_rank ASC');
			$typeRow = $typeexe->fetchAll();

			if (! $typeRow){
				$retStr .= self::NewsList($tpl->areaName,1,$showNum,$typeStr,$typeLevel);
			}else{
				$adNum = 0;
				$todayDate = TimeDate::Get('date');
				$redDay = $tpl->tplSysArr['TS_redTimeDay']-1;
				$rowCount = count($typeRow);
				for ($i=0; $i<$rowCount; $i++){
					$itemBoxNum ++;
					$newsMoreUrl = Area::InfoTypeUrl(array(
						'IT_mode'		=> $typeRow[$i]['IT_mode'],
						'IT_ID'			=> $typeRow[$i]['IT_ID'],
						'IT_webID'		=> $typeRow[$i]['IT_webID'],
						'IT_URL'		=> $typeRow[$i]['IT_URL'],
						'IT_isEncUrl'	=> $typeRow[$i]['IT_isEncUrl'],
						'IT_htmlName'	=> $typeRow[$i]['IT_htmlName'],
						'mainUrl'		=> '',
						'webPathPart'	=> $tpl->webPathPart,
						));
					$itemStyleStr = '';
					if ($itemBoxNum<=2){ $itemStyleStr .= 'margin-top:0px;'; }
					if ($itemBoxNum % $itemBoxCol == 0){ $itemStyleStr .= 'margin-right:0px;'; }
					$retStr .= '
					<div class="'. $itemBoxClass .'" style="'. $itemStyleStr .'">
					<dl>
						<dt>
							<div class="more"><a href="'. $newsMoreUrl .'"></a></div>
							<span class="pointer" onclick="document.location.href=\''. str_replace($tpl->webPathPart,$tpl->jsPathPart,$newsMoreUrl) .'\';">'. $typeRow[$i]['IT_theme'] .'</span>
						</dt>
						<dd class="listBox5">
						';
							$typeWhereStr = '';
							if ($typeRow[$i]['IT_level']>=1 && $typeRow[$i]['IT_level']<=3){
								$typeWhereStr = ' and IF_type'. $typeRow[$i]['IT_level'] .'ID='. $typeRow[$i]['IT_ID'] .'';
							}else{
								$typeWhereStr = " and IF_typeStr like '%,". $typeRow[$i]['IT_ID'] .",%'";
							}
							$showexe = $DB->query('select IF_ID,IF_time,IF_theme,IF_themeStyle,IF_URL,IF_isEncUrl,IF_infoTypeDir,IF_datetimeDir from '. OT_dbPref .'info where IF_state=1 and IF_isAudit=1'. $typeWhereStr .''. OT_TimeInfoWhereStr .' order by IF_time DESC limit 0,'. $showNum .'');
							$showRow = $showexe->fetchAll();
							if (! $showRow){
								$retStr .= '<br /><center class="font1_1">暂无内容</center><br />';
							}else{
								$retStr .= '<ul>';
								$row2Count = count($showRow);
								for ($i2=0; $i2<$row2Count; $i2++){
									$timeStr = '';
									if (strpos($typeRow[$i]['IT_listInfo'],'|noTime|') === false){
										if (TimeDate::Diff('d',$showRow[$i2]['IF_time'],$todayDate) <= $redDay){ $newTimeClass = 'font2_1'; }else{ $newTimeClass = 'font1_1'; }
										if ($itemBoxCol > 1){
											$itemTime = TimeDate::Get('m-d',$showRow[$i2]['IF_time']);
										}else{
											$itemTime = $showRow[$i2]['IF_time'];
										}
										$timeStr = '<div class="addi '. $newTimeClass .'">&ensp;'. $itemTime .'</div>';
									}
									if ($showRow[$i2]['IF_URL'] != ''){
										$hrefStr = Url::NewsUrl($showRow[$i2]['IF_URL'],$showRow[$i2]['IF_isEncUrl'],$showRow[$i2]['IF_ID'],$tpl->webPathPart);
									}else{
										$hrefStr = Url::NewsID($showRow[$i2]['IF_infoTypeDir'],$showRow[$i2]['IF_datetimeDir'],$showRow[$i2]['IF_ID']);
									}
									$retStr .= '
									<li>
										'. $timeStr .'
										<a href="'. $hrefStr .'" class="font1_1" style="'. $showRow[$i2]['IF_themeStyle'] .'" target="_blank">'. $showRow[$i2]['IF_theme'] .'</a>
									</li>
									';
								}

								$retStr .= '
								</ul><div class="clr"></div>';
							}
							unset($showexe);

						$retStr .= '
						</dd>
					</dl>
					</div>
					';
					if ($itemBoxNum % $itemBoxCol == 0){
						$retStr .= '
						<div class="clr"></div>
						';
						if ($adNum<10){
							$adNum ++;
							$retStr .= '
							<div class="caClass leftCa1 ca'. ($adNum+60) .'Style">
								<script type="text/javascript">OTca("ot0'. ($adNum+60) .'");</script>
							</div>
							<div class="clr"></div>
							';
						}
					}
				}
			}
			unset($typeexe);

		}else{
			$retStr .= '
			<div class="pageBox">
			<dl>
				<dt>'. $tpl->areaName .'</dt>
				<dd class="listBox'. $showModeClass .'">
				'. self::NewsListMode('',$showMode,$showNum,$typeStr,$typeLevel) .'
				</dd>
			</dl>
			</div>
			<div class="clr"></div>
			';
		}

		if ($judGet){
			return $retStr;
		}else{
			echo($retStr);
		}
	}

	
	// 多显示模式数值
	public static function NewsModeNum($showMode){
		if ($showMode == 0){ $showMode = 2; }
		if ($showMode == 4){
			$retNum = 2;
		}elseif ($showMode == 7){
			$retNum = 5;
		}else{
			$retNum = $showMode;
		}
		return $retNum;
	}


	// 多显示模式
	public static function NewsListMode($listInfo,$showMode,$showNum,$typeStr,$typeLevel,$judGet=true){
		global $DB,$tpl;

		$retStr = '';
		$listHtmlName = '';
		$newRecordNum = 0;
		$judUserNews = 0;
		$todayDate = TimeDate::Get('date');
		$redDay = $tpl->tplSysArr['TS_redTimeDay']-1;
		if ($showNum == 0){ $showNum=10; }
		if ($typeStr == 'new' && $tpl->tplSysArr['TS_homeNewMoreNum']>0){
			$SQLstr = 'select IF_ID,IF_URL,IF_isEncUrl,IF_isTop,IF_readNum,IF_replyNum,IF_img,IF_time,IF_theme,IF_themeStyle,IF_themeKey,IF_contentKey,IF_infoTypeDir,IF_datetimeDir from '. OT_dbPref .'info where IF_state=1 and IF_isAudit=1 and IF_isNew=1'. OT_TimeInfoWhereStr;
			$newRecordNum = $tpl->tplSysArr['TS_homeNewMoreNum'];
		}else{
			$SQLstr = 'select IF_ID,IF_URL,IF_isEncUrl,IF_isTop,IF_readNum,IF_replyNum,IF_img,IF_time,IF_theme,IF_themeStyle,IF_themeKey,IF_contentKey,IF_infoTypeDir,IF_datetimeDir,IF_userID from '. OT_dbPref .'info where IF_state=1 and IF_isAudit=1'. OT_TimeInfoWhereStr;
		}
		switch ($typeStr){
			case 'announ':
				$SQLstr .= ' and IF_type1ID=-1 order by IF_isTop DESC,IF_time DESC';
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
			
			$retStr .= '<ul>';
			switch ($showMode){
				case 2: case 4:		// 图+摘要
					for ($i=0; $i<$rowCount; $i++){
						$readAndReplyStr = $topImg = $userStr = $markStr = $timeStr = '';
						if ($showRow[$i]['IF_URL'] != ''){
							$hrefStr = Url::NewsUrl($showRow[$i]['IF_URL'],$showRow[$i]['IF_isEncUrl'],$showRow[$i]['IF_ID'],$tpl->webPathPart);
						}else{
							$hrefStr = Url::NewsID($showRow[$i]['IF_infoTypeDir'],$showRow[$i]['IF_datetimeDir'],$showRow[$i]['IF_ID']);
						}
						if ($showRow[$i]['IF_isTop'] == 1){
							$topImg='<img src="'. $tpl->webPathPart .'inc_img/share_top.gif" alt="置顶" title="置顶" style="margin-right:5px;" />';
						}
						if ($tpl->sysArr['SYS_newsListUrlMode'] != 'html-2.x'){
							if ($tpl->tplSysArr['TS_isReadNum'] == 1 && strpos($listInfo,'|noRead|') === false){
								$readAndReplyStr .= '&ensp;&ensp;阅读：'. $showRow[$i]['IF_readNum'];
							}
							if (strpos($listInfo,'|noReply|') === false){
								$readAndReplyStr .= '&ensp;&ensp;评论：'. $showRow[$i]['IF_replyNum'];
							}
						}
						if ($judUserNews == 1 && strpos($listInfo,'|noUser|') === false){
							$userStr = $DB->GetOne('select UE_username from '. OT_dbPref .'users where UE_ID='. $showRow[$i]['IF_userID']);
							if (strlen($userStr) > 0){ $userStr .= ' | '; }
						}
						if (strpos($listInfo,'|noMark|') === false){
							$markStr = '<div class="mark">'. TplIndex::MarkStr($showRow[$i]['IF_themeKey']) .'</div>';
						}
						if (strpos($listInfo,'|noTime|') === false){
							if (TimeDate::Diff('d',$showRow[$i]['IF_time'],$todayDate) <= $redDay){ $newTimeClass = 'font2_1'; }else{ $newTimeClass = 'font1_1'; }
							$timeStr = '<span class="'. $newTimeClass .'">'. $showRow[$i]['IF_time'] .'</span>';
						}
						$themeStr = Str::MoreReplace($showRow[$i]['IF_theme'],'input');

						if ($showRow[$i]['IF_img'] != '' || $showMode == 4){ 
							$retStr .= '
							<li>
								<div class="a">
									<div class="img"><a href="'. $hrefStr .'" class="font1_1" target="_blank"><img src="'. Area::InfoImgUrl($showRow[$i]['IF_img'],$tpl->webPathPart . InfoImgDir) .'" alt="'. $themeStr .'" title="'. $themeStr .'" onerror="if(this.value!=\'1\'){this.value=\'1\';this.src=\''. $tpl->webPathPart .'inc_img/noPic.gif\';}" /></a></div>
								</div>
								<div class="b">
									<div class="addi">&ensp;'. $timeStr . $readAndReplyStr .'</div>
									<h4><a href="'. $hrefStr .'" class="font1_1" style="'. $showRow[$i]['IF_themeStyle'] .'" target="_blank">'. $topImg . $userStr . self::RefFontColor($showRow[$i]['IF_theme']) .'</a></h4>
									<div class="clr"></div>
									<div class="note">'. self::RefFontColor($showRow[$i]['IF_contentKey']) .'&ensp;<a href="'. $hrefStr .'" class="font2_2" target="_blank">&ensp;阅读全文&gt;&gt;</a></div>
									<div class="clr"></div>
									'. $markStr .'
								</div><div class="clr"></div>
							</li><div class="clr"></div>
							';
						}else{
							$retStr .= '
							<li>
								<div class="b b2">
									<div class="addi">&ensp;'. $timeStr . $readAndReplyStr .'</div>
									<h4><a href="'. $hrefStr .'" class="font1_1" style="'. $showRow[$i]['IF_themeStyle'] .'" target="_blank">'. $topImg . $userStr . self::RefFontColor($showRow[$i]['IF_theme']) .'</a></h4>
									<div class="clr"></div>
									<div class="note">'. self::RefFontColor($showRow[$i]['IF_contentKey']) .'&ensp;<a href="'. $hrefStr .'" class="font2_2" target="_blank">&ensp;阅读全文&gt;&gt;</a></div>
									<div class="clr"></div>
									'. $markStr .'
								</div><div class="clr"></div>
							</li><div class="clr"></div>
							';
						}
					}
					break;

				case 3:		// 图+标题
					for ($i=0; $i<$rowCount; $i++){
						$topImg = $userStr = '';
						if ($showRow[$i]['IF_URL'] != ''){
							$hrefStr = Url::NewsUrl($showRow[$i]['IF_URL'],$showRow[$i]['IF_isEncUrl'],$showRow[$i]['IF_ID'],$tpl->webPathPart);
						}else{
							$hrefStr = Url::NewsID($showRow[$i]['IF_infoTypeDir'],$showRow[$i]['IF_datetimeDir'],$showRow[$i]['IF_ID']);
						}
						if ($showRow[$i]['IF_isTop'] == 1){
							$topImg='<img src="'. $tpl->webPathPart .'inc_img/share_top.gif" alt="置顶" title="置顶" style="margin-right:5px;" />';
						}
						if ($judUserNews == 1){
							$userStr = $DB->GetOne('select UE_username from '. OT_dbPref .'users where UE_ID='. $showRow[$i]['IF_userID']);
							if (strlen($userStr) > 0){ $userStr .= ' | '; }
						}
						$themeStr = Str::MoreReplace($showRow[$i]['IF_theme'],'input');

						$retStr .= '
						<li>
							<div class="a"><a href="'. $hrefStr .'" target="_blank"><img src="'. Area::InfoImgUrl($showRow[$i]['IF_img'],$tpl->webPathPart . InfoImgDir) .'" alt="'. $themeStr .'" title="'. $themeStr .'" onerror="if(this.value!=\'1\'){this.value=\'1\';this.src=\''. $tpl->webPathPart .'inc_img/noPic.gif\';}" /></a></div>
							<div class="b"><a href="'. $hrefStr .'" class="font1_1" style="'. $showRow[$i]['IF_themeStyle'] .'" target="_blank">'. $topImg . $userStr . self::RefFontColor($showRow[$i]['IF_theme']) .'</a></div>
						</li>
						';
					}
					break;

				case 7:	// 标题
					for ($i=0; $i<$rowCount; $i++){
						$topImg = $timeStr = '';
						if ($showRow[$i]['IF_URL'] != ''){
							$hrefStr = Url::NewsUrl($showRow[$i]['IF_URL'],$showRow[$i]['IF_isEncUrl'],$showRow[$i]['IF_ID'],$tpl->webPathPart);
						}else{
							$hrefStr = Url::NewsID($showRow[$i]['IF_infoTypeDir'],$showRow[$i]['IF_datetimeDir'],$showRow[$i]['IF_ID']);
						}
						if ($showRow[$i]['IF_isTop'] == 1){
							$topImg='<img src="'. $tpl->webPathPart .'inc_img/share_top.gif" alt="置顶" title="置顶" style="margin-right:5px;" />';
						}
						if (strpos($listInfo,'|noTime|') === false){
							if (TimeDate::Diff('d',$showRow[$i]['IF_time'],$todayDate) <= $redDay){ $newTimeClass = 'font2_1'; }else{ $newTimeClass = 'font1_1'; }
							$timeStr = '<div class="addi '. $newTimeClass .'">&ensp;'. $showRow[$i]['IF_time'] .'</div>';
						}
						$retStr .= '
						<li>
							'. $timeStr .'
							<a href="'. $hrefStr .'" class="font1_1" style="'. $showRow[$i]['IF_themeStyle'] .'" target="_blank">'. $topImg . $showRow[$i]['IF_theme'] .'</a>
						</li>
						';
					}
					break;

				default :	// 标题+摘要
					for ($i=0; $i<$rowCount; $i++){
						$readAndReplyStr = $topImg = $userStr = $markStr = $timeStr = '';
						if ($showRow[$i]['IF_URL'] != ''){
							$hrefStr = Url::NewsUrl($showRow[$i]['IF_URL'],$showRow[$i]['IF_isEncUrl'],$showRow[$i]['IF_ID'],$tpl->webPathPart);
						}else{
							$hrefStr = Url::NewsID($showRow[$i]['IF_infoTypeDir'],$showRow[$i]['IF_datetimeDir'],$showRow[$i]['IF_ID']);
						}
						if ($showRow[$i]['IF_isTop'] == 1){
							$topImg = '<img src="'. $tpl->webPathPart .'inc_img/share_top.gif" alt="置顶" title="置顶" style="margin-right:5px;" />';
						}
						if ($tpl->sysArr['SYS_newsListUrlMode'] != 'html-2.x'){
							if (strpos($listInfo,'|noRead|') === false && $tpl->tplSysArr['TS_isReadNum'] == 1){
								$readAndReplyStr .= '&ensp;&ensp;阅读：'. $showRow[$i]['IF_readNum'];
							}
							if (strpos($listInfo,'|noReply|') === false){
								$readAndReplyStr = '&ensp;&ensp;评论：'. $showRow[$i]['IF_replyNum'];
							}
						}
						if ($judUserNews == 1 && strpos($listInfo,'|noUser|') === false){
							$userStr = $DB->GetOne('select UE_username from '. OT_dbPref .'users where UE_ID='. $showRow[$i]['IF_userID']);
							if (strlen($userStr) > 0){ $userStr .= ' | '; }
						}
						if (strpos($listInfo,'|noMark|') === false){
							$markStr = '<div class="mark">'. TplIndex::MarkStr($showRow[$i]['IF_themeKey']) .'</div>';
						}
						if (strpos($listInfo,'|noTime|') === false){
							if (TimeDate::Diff('d',$showRow[$i]['IF_time'],$todayDate) <= $redDay){ $newTimeClass = 'font2_1'; }else{ $newTimeClass = 'font1_1'; }
							$timeStr = '<span class="'. $newTimeClass .'">'. $showRow[$i]['IF_time'] .'</span>';
						}
						$retStr .= '
						<li>
							<div class="addi">&ensp;'. $timeStr . $readAndReplyStr .'</div>
							<h4><a href="'. $hrefStr .'" class="font1_1" style="'. $showRow[$i]['IF_themeStyle'] .'" target="_blank">'. $topImg . $userStr . self::RefFontColor($showRow[$i]['IF_theme']) .'</a></h4>
							<div class="note">'. self::RefFontColor($showRow[$i]['IF_contentKey']) .'&ensp;<a href="'. $hrefStr .'" class="font2_2" target="_blank">&ensp;阅读全文&gt;&gt;</a></div><div class="clr"></div>
							'. $markStr .'
						</li>
						';
					}
					break;
			
			}

			$retStr .= '
			</ul><div class="clr"></div>

			<center>'. self::NewsListNav($typeStr,$listHtmlName,$tpl->typeID,$tpl->page,$pageCount,$pageSize,$recordCount) .'</center>
			';
		}
		unset($showRow);

		if ($judGet){
			return $retStr;
		}else{
			echo($retStr);
		}
	}

	// 搜索模式关键词标红
	public static function RefFontColor($str){
		global $tpl;

		if ($tpl->refContent == ''){
			return $str;
		}else{
			return str_replace($tpl->refContent,'<span style="color:red;">'. $tpl->refContent .'</span>', $str);
		}
	}


	// 列表导航
	public static function NewsListNav($newsTypeStr, $newsTypeDir, $newsTypeID, $newsPageID, $pageCount, $pageSize, $recordCount, $skin='img', $pageMode='pageNum', $judGet=true){
		global $tpl;

		if ($newsTypeStr == 'refer'){
			$newsTypeStr .= '-'. $tpl->refType .'-'. urlencode($tpl->refContent);
		}elseif (strpos('|mark|user|topic|','|'. $newsTypeStr .'|') !== false){
			$newsTypeStr .= '-'. urlencode($tpl->markName) .'';
		}

		$retStr = '<table align="center" cellpadding="0" cellspacing="0" class="listNavBox"><tr><td>';

		// 页码风格
		switch ($skin){
			case 'CN':
				$first_page		= '第一页';
				$prev_page		= '上一页';
				$next_page		= '下一页';
				$last_page		= '最后页';
				$first_page2	= '<span class="fontNav_2d">第一页</span>';
				$prev_page2		= '<span class="fontNav_2d">上一页</span>';
				$next_page2		= '<span class="fontNav_2d">下一页</span>';
				$last_page2		= '<span class="fontNav_2d">最后页</span>';
				break;

			case 'img':
				$first_page		= '<img src="'. $tpl->webPathPart .'inc_img/navigation/narStart.gif" border="0" alt="" style="margin-top:5px;" alt="第一页" class="navBtnD" />';
				$prev_page		= '<img src="'. $tpl->webPathPart .'inc_img/navigation/narLast.gif" border="0" alt="" style="margin-top:5px;" alt="上一页" />';
				$next_page		= '<img src="'. $tpl->webPathPart .'inc_img/navigation/narNext.gif" border="0" alt="" style="margin-top:5px;" alt="下一页" />';
				$last_page		= '<img src="'. $tpl->webPathPart .'inc_img/navigation/narEnd.gif" border="0" alt="" style="margin-top:5px;" alt="最后页" />';
				$first_page2	= '<img src="'. $tpl->webPathPart .'inc_img/navigation/narStart2.gif" border="0" alt="" style="margin-top:5px;" alt="第一页" class="navBtnD" />';
				$prev_page2		= '<img src="'. $tpl->webPathPart .'inc_img/navigation/narLast2.gif" border="0" alt="" style="margin-top:5px;" alt="上一页" />';
				$next_page2		= '<img src="'. $tpl->webPathPart .'inc_img/navigation/narNext2.gif" border="0" alt="" style="margin-top:5px;" alt="下一页" />';
				$last_page2		= '<img src="'. $tpl->webPathPart .'inc_img/navigation/narEnd2.gif" border="0" alt="" style="margin-top:5px;" alt="最后页" />';
				break;

			default :
				$first_page	='<span style="font-family:webdings;">9</span>';
				$prev_page	='<span style="font-family:webdings;">7</span>';
				$next_page	='<span style="font-family:webdings;">8</span>';
				$last_page	='<span style="font-family:webdings;">:</span>';
				$first_page2	='<span style="font-family:webdings;" class="fontNav_2d">9</span>';
				$prev_page2	='<span style="font-family:webdings;" class="fontNav_2d">7</span>';
				$next_page2	='<span style="font-family:webdings;" class="fontNav_2d">8</span>';
				$last_page2	='<span style="font-family:webdings;" class="fontNav_2d">:</span>';
				break;

		}

		// 往URL里填补GET参数

		$page = $newsPageID;

		if ($page<1 || $page>$pageCount){ $page=1; }
			if ($pageMode == 'pageNum'){
				$retStr .= '<div id="listNavInfo" class="navBtn font1_1">第'. $page .'/'. $pageCount .'页&ensp;&ensp;每页'. $pageSize .'条,共'. $recordCount .'条记录</div>';
			}elseif ($pageMode == 'bbsDet'){
				$retStr .= '<a href="./" class="navBtn font1_1"><img src="'. $tpl->webPathPart .'inc_img/navigation/narLast11.gif" border="0" alt="" style="margin:5px 5px 0 0;" />回到列表</a>';
			}

		if ($page<=1){
			$retStr .= '
			<div class="navBtn">'. $first_page2 .'</div>
			<div class="navBtn">'. $prev_page2 .'</div>
			';
		}else{
			$retStr .= '
			<a href="'. self::NewsListNavHref($newsTypeStr,$newsTypeDir,$newsTypeID,1) .'" class="navBtnPointer fontNav_2">'. $first_page .'</a>
			<a href="'. self::NewsListNavHref($newsTypeStr,$newsTypeDir,$newsTypeID,$page - 1) .'" class="navBtnPointer fontNav_2">'. $prev_page .'</a>
			';
		}

		$showPageNum = 7;	//显示页码个数
		$pageNumHalf=intval($showPageNum/2);

		if ($pageCount <= $showPageNum){
			$startpage = 1;
			$endpage = $pageCount;
		}elseif ($page-$pageNumHalf >= 1 && $page+$pageNumHalf <= $pageCount){
			$startpage = $page-$pageNumHalf;
			$endpage = $page+$pageNumHalf;
		}elseif ($page-$pageNumHalf < 1){
			$startpage = 1;
			$endpage = $showPageNum;
		}elseif ($page+$pageNumHalf > $pageCount){
			$startpage = $pageCount-($showPageNum-1);
			$endpage = $pageCount;
		}
		
		for ($i=$startpage; $i<=$endpage; $i++){
			if ($i == $page){
				$retStr .= '<div class="navBtn'. $i .' navBtn fontNav2_2">'. $i .'</div>';
			}else{
				$retStr .= '<a class="navBtn'. $i .' navBtnPointer fontNav_2" href="'. self::NewsListNavHref($newsTypeStr,$newsTypeDir,$newsTypeID,$i) .'">'. $i .'</a>';
			}
		}

		if ($page >= $pageCount){
			$retStr .= '
			<div class="navBtn">'. $next_page2 .'</div>
			<div class="navBtn">'. $last_page2 .'</div>
			';
		}else{
			$retStr .= '
			<a href="'. self::NewsListNavHref($newsTypeStr,$newsTypeDir,$newsTypeID,$page + 1) .'" class="navBtnPointer fontNav_2">'. $next_page .'</a>
			<a href="'. self::NewsListNavHref($newsTypeStr,$newsTypeDir,$newsTypeID,$pageCount) .'" class="navBtnPointer fontNav_2">'. $last_page .'</a>
			';
		}

		$retStr .= '
		<div id="listNavGoPage" class="navBtn">
			<select onchange="if(this.value!=\'\'){ListPageHref(this.value,\''. self::NewsListNavHref_pageSign($newsTypeStr,$newsTypeDir,$newsTypeID,2) .'\');}" class="caClass">
				<option value=""></option>
				';
				for ($i=1; $i<=$pageCount; $i++){
					$retStr .= '<option value="'. $i .'">'. $i .'</option>';
				}
			$retStr .= '
			</select>
		</div>
		</td></tr></table>
		';

		if ($judGet){
			return $retStr;
		}else{
			echo($retStr);
		}
	}


	public static function NewsListNavHref($newsTypeStr,$newsTypeDir,$newsTypeID,$newsPageID){
		if ($newsTypeStr == 'tkGoods'){
			return Url::GoodsList($newsTypeID,$newsPageID);
		}elseif ($newsTypeDir == 'tkGoods'){
			return Url::GoodsListStr($newsTypeStr,$newsPageID);
		}elseif ($newsTypeDir == 'bbs'){
			if ($newsTypeStr == 'bbsShow'){
				return Url::BbsID($newsTypeID,$newsPageID);
			}elseif ($newsTypeStr != '' && $newsTypeID == 0){
				return Url::BbsListStr($newsTypeStr,$newsPageID);
			}else{
				return Url::BbsList($newsTypeID,$newsPageID);
			}
		}elseif ($newsTypeStr != '' && $newsTypeID == 0){
			return Url::ListStr($newsTypeStr,$newsPageID);
		}else{
			return Url::ListID('',$newsTypeDir,$newsTypeID,$newsPageID);
		}
	}

	public static function NewsListNavHref_pageSign($newsTypeStr,$newsTypeDir,$newsTypeID,$newsPageID){
		if ($newsTypeStr == 'tkGoods'){
			return Url::GoodsList_pageSign($newsTypeID,$newsPageID);
		}elseif ($newsTypeDir == 'tkGoods'){
			return Url::GoodsListStr_pageSign($newsTypeStr,$newsPageID);
		}elseif ($newsTypeDir == 'bbs'){
			if ($newsTypeStr == 'bbsShow'){
				return Url::BbsID_pageSign($newsTypeID,$newsPageID);
			}elseif ($newsTypeStr != '' && $newsTypeID == 0){
				return Url::BbsListStr_pageSign($newsTypeStr,$newsPageID);
			}else{
				return Url::BbsList_pageSign($newsTypeID,$newsPageID);
			}
		}elseif ($newsTypeStr != '' && $newsTypeID == 0){
			return Url::ListStr_pageSign($newsTypeStr,$newsPageID);
		}else{
			return Url::ListID_pageSign('',$newsTypeDir,$newsTypeID,$newsPageID);
		}
	}

	/* 兼容V2.45以下版本 后期会删除该兼容代码 Start */
	public static function NewsListNavigation($newsTypeStr,$newsTypeDir,$newsTypeID,$newsPageID,$pageCount,$pageSize,$recordCount,$skin='img',$pageMode='pageNum',$judGet=true){
		return self::NewsListNav($newsTypeStr,$newsTypeDir,$newsTypeID,$newsPageID,$pageCount,$pageSize,$recordCount,$skin,$pageMode,$judGet);
	}

	public static function NewsListNaviHref($newsTypeStr,$newsTypeDir,$newsTypeID,$newsPageID){
		return self::NewsListNavHref($newsTypeStr,$newsTypeDir,$newsTypeID,$newsPageID);
	}

	public static function NewsListNaviHref_pageSign($newsTypeStr,$newsTypeDir,$newsTypeID,$newsPageID){
		return self::NewsListNavHref_pageSign($newsTypeStr,$newsTypeDir,$newsTypeID,$newsPageID);
	}
	/* 兼容V2.45以下版本 End */

}
?>
<?php

if(! defined('OT_ROOT')) {
	exit('Access Denied');
}


class TplIndex{
	public static $infoTypeArr = array();
	public static $homeItemImgIdStr;


	// 最近更新
	public static function NewList($num,$judGet=true){
		global $DB,$tpl;

		$retStr = '';
		$topWhereStr = '';
		$topIdArr = array();

		if ($tpl->tplSysArr['TS_homeNewTopNum'] > 0){
			$newRow = $DB->GetLimit('select IF_ID,IF_time,IF_theme,IF_themeStyle,IF_URL,IF_isEncUrl,IF_contentKey,IF_infoTypeDir,IF_datetimeDir from '. OT_dbPref .'info where IF_state=1 and IF_isAudit=1 and IF_isTop=1 and IF_isNew=1'. OT_TimeInfoWhereStr .' order by IF_time DESC',$tpl->tplSysArr['TS_homeNewTopNum']);
			if ($newRow){
				$rowCount = count($newRow);
				for ($i=0; $i<$rowCount; $i++){
					$topIdArr[] = $newRow[$i]['IF_ID'];
					if ($newRow[$i]['IF_URL']!=''){
						$hrefStr = Url::NewsUrl($newRow[$i]['IF_URL'],$newRow[$i]['IF_isEncUrl'],$newRow[$i]['IF_ID'],$tpl->webPathPart);
					}else{
						$hrefStr = Url::NewsID($newRow[$i]['IF_infoTypeDir'],$newRow[$i]['IF_datetimeDir'],$newRow[$i]['IF_ID']);
					}
					$retStr .= '
					<div class="headRow">
						<h1><a href="'. $hrefStr .'" title="'. Str::MoreReplace($newRow[$i]['IF_theme'],'input') .'" style="'. $newRow[$i]['IF_themeStyle'] .'" target="_blank">'. $newRow[$i]['IF_theme'] .'</a></h1>
						<span class="note">'. Str::LimitChar($newRow[$i]['IF_contentKey'],84) .'</span><a href="'. $hrefStr .'" class="font2_2" target="_blank">&ensp;阅读全文&gt;&gt;</a>
					</div>
					';
				}
			}
			unset($newRow);
		}

		if (count($topIdArr)>0){
			$topWhereStr .= ' and IF_ID not in ('. implode(',', $topIdArr) .')';
		}

		$retStr .= '<ul>';

		if ($num<=0){ $num=10; }
		$todayDate = TimeDate::Get('date');
		$redDay = $tpl->tplSysArr['TS_redTimeDay']-1;

		$newexe = $DB->query('select IF1.IF_ID,IF2.IF_time,IF2.IF_theme,IF2.IF_themeStyle,IF2.IF_URL,IF2.IF_isEncUrl,IF2.IF_typeStr,IF2.IF_infoTypeDir,IF2.IF_datetimeDir from (select IF_ID from '. OT_dbPref .'info where IF_state=1 and IF_isAudit=1 and IF_isNew=1'. $topWhereStr . OT_TimeInfoWhereStr .' order by IF_time DESC limit 0,'. $num .') as IF1 inner join '. OT_dbPref .'info as IF2 on IF1.IF_ID=IF2.IF_ID order by IF2.IF_time DESC');
		while ($row = $newexe->fetch()){
			if ($row['IF_URL']!=''){
				$hrefStr = Url::NewsUrl($row['IF_URL'],$row['IF_isEncUrl'],$row['IF_ID'],$tpl->webPathPart);
			}else{
				$hrefStr = Url::NewsID($row['IF_infoTypeDir'],$row['IF_datetimeDir'],$row['IF_ID']);
			}

			$dateStr = $typeStr = '';
			if ($tpl->tplSysArr['TS_homeNewIsDate'] == 1){
				if (TimeDate::Diff('d',$row['IF_time'],$todayDate) <= $redDay){ $newTimeClass = 'font2_1'; }else{ $newTimeClass = 'font1_1'; }
				$dateStr = '<div class="fr '. $newTimeClass .'">&ensp;'. TimeDate::Get('m-d',$row['IF_time']) .'</div>';
			}
			if ($tpl->tplSysArr['TS_homeNewIsType'] == 1){
				$currTypeArr = self::InfoTypeCN($row['IF_typeStr']);
				if ($row['IF_typeStr'] == 'announ'){
					$typeStr = '<a href="'. Url::ListStr($row['IF_typeStr'],0) .'" class="font2_1">['. $currTypeArr['theme'] .']</a>';
				}else{
					$typeArr = explode(',',$row['IF_typeStr']);
					if (count($typeArr)>=3){ $typeID = $typeArr[count($typeArr)-2]; }
					$typeStr = '<a href="'. Url::ListID('',$currTypeArr['htmlName'],$typeID,0) .'" class="font2_1">['. $currTypeArr['theme'] .']</a>';
				}
			}
			$retStr .= '<li>'. $dateStr . $typeStr .'<a href="'. $hrefStr .'" class="font1_1" style="'. $row['IF_themeStyle'] .'" target="_blank" title="'. Str::MoreReplace($row['IF_theme'],'input') .'">'. $row['IF_theme'] .'</a></li>'. PHP_EOL;
		}
		unset($newexe);

		$retStr .= '</ul>'. PHP_EOL;

		if ($judGet){
			return $retStr;
		}else{
			echo($retStr);
		}
	}

	// 获取栏目数组
	public static function InfoTypeCN($typeStr){
		global $DB,$tpl;

		if ($typeStr == 'announ'){
			return array('id'=>0, 'theme'=>$tpl->sysArr['SYS_announName'], 'htmlName'=>'announ');
		}else{
			if (empty(self::$infoTypeArr)){
				self::$infoTypeArr = array();
				$typecnexe=$DB->query('select IT_ID,IT_theme,IT_level,IT_htmlName from '. OT_dbPref .'infoType order by IT_ID DESC');
				while ($row = $typecnexe->fetch()){
					self::$infoTypeArr[$row['IT_ID']] = array('id'=>$row['IT_ID'], 'theme'=>$row['IT_theme'], 'htmlName'=>$row['IT_htmlName']);
				}
				unset($typecnexe);
			}


			$tsArr = explode(',',$typeStr);
			$tsCount = count($tsArr);
			for ($i=$tsCount-1; $i>=0; $i--){
				$val = intval($tsArr[$i]);
				if (isset(self::$infoTypeArr[$val])){
					return self::$infoTypeArr[$val];
				}
			}

			return array('id'=>-1, 'theme'=>'已不存在', 'htmlName'=>'');
		}
	}



	// 幻灯片
	public static function FlashBox($style,$w,$h,$num,$judGet=true){
		global $DB,$tpl;
		$retStr = '';

		if ($num==0){ $num = 16; }

		$flashexe = $DB->query('select IF1.IF_ID,IF2.IF_img,IF2.IF_theme,IF2.IF_URL,IF2.IF_isEncUrl,IF2.IF_infoTypeDir,IF2.IF_datetimeDir from (select IF_ID from '. OT_dbPref .'info where IF_state=1 and IF_isAudit=1 and IF_isFlash=1'. OT_TimeInfoWhereStr .' order by IF_time DESC limit 0,'. $num .') as IF1 inner join '. OT_dbPref .'info as IF2 on IF1.IF_ID=IF2.IF_ID order by IF2.IF_time DESC');
		$focusImgStr = '';
		$focusImg1 = '';
		if ($row = $flashexe->fetch()){
			if ($row['IF_URL']!=''){
				$hrefStr = Url::NewsUrl($row['IF_URL'],$row['IF_isEncUrl'],$row['IF_ID'],$tpl->webPathPart);
			}else{
				$hrefStr = Url::NewsID($row['IF_infoTypeDir'],$row['IF_datetimeDir'],$row['IF_ID']);
			}
			$themeStr = Str::MoreReplace($row['IF_theme'],'input');
			$focusImg1 = '<a href="'. $hrefStr .'" target="_blank"><img src="'. Area::InfoImgUrl($row['IF_img'],$tpl->webPathPart . InfoImgDir) .'" style="FILTER: revealTrans(duration=1,transition=23);" galleryimg="no" alt="'. $themeStr .'" title="'. $themeStr .'" /></a>';
			do {
				if ($row['IF_URL'] != ''){
					$hrefStr = Url::NewsUrl($row['IF_URL'],$row['IF_isEncUrl'],$row['IF_ID'],$tpl->webPathPart);
				}else{
					$hrefStr = Url::NewsID($row['IF_infoTypeDir'],$row['IF_datetimeDir'],$row['IF_ID']);
				}
				$focusImgStr .= '
				<dl>
					<dt><a href="'. $hrefStr .'">'. Str::MoreReplace($row['IF_theme'],'input') .'</a></dt>
					<dd>'. Area::InfoImgUrl($row['IF_img'],$tpl->webPathPart . InfoImgDir) .'</dd>
				</dl>
				';
			}while ($row = $flashexe->fetch());
		}
		unset($flashexe);

		if (strlen($focusImgStr) == 0){
			$focusImg1 = '<a></a>';
			$focusImgStr .= '
			<dl>
				<dt><a></a></dt>
				<dd></dd>
			</dl>
			';
		}

		$retStr .= '
		<script language="javascript" type="text/javascript" src="tools/imgTrun/imgTrun5.js?v='. OT_VERSION .'"></script>
		<div class="FocusImgFrame">
			<div class="FocusImg" id="FocusImg_2">
				<div class="BigPic" id="BigPic_2">'. $focusImg1 .'</div>
				<div class="Number" id="Number_2"><!-- 小图列表 --></div>
				<div class="TitleBox" id="TitleBox_2"></div>
			</div>
		</div>
		<div id="focusData_01" style="display:none">
			'. $focusImgStr .'
		</div>
		<script language="javascript" type="text/javascript">
		OT_FocusPic("'. ($w-2) .'","'. $h .'");
		//var pic_width	= '. ($w-2) .';	// 宽
		//var pic_height	= '. $h .';		// 高
		</script>
		';

		if ($judGet){
			return $retStr;
		}else{
			echo($retStr);
		}
	}


	// 相关文章(标签)
	public static function MarkNews($newsId,$markStr,$markIdStr,$num,$judGet=true){
		global $DB,$tpl,$infoSysArr;

		$retStr = '';

		if ($infoSysArr['IS_isSaveMarkNewsId'] == 1 && strlen($markIdStr) > 0){
			$markSqlStr = 'select IF_ID,IF_time,IF_theme,IF_themeStyle,IF_URL,IF_isEncUrl,IF_infoTypeDir,IF_datetimeDir from '. OT_dbPref .'info where IF_ID in ('. $markIdStr .') and IF_state=1 and IF_isAudit=1'. OT_TimeInfoWhereStr .' order by IF_time DESC limit '. $num .'';
		}else{
			$whereStr = '';
			if ($markStr != ''){
				$markArr = explode(',',$markStr);
				$whereStr .= ' and (1=2';
				$markNum = 0;
				foreach ($markArr as $mark){
					$markNum ++;
					if ($markNum>5){ break; }
					if ($mark != ''){
						$whereStr .= " or IF_themeKey like '%". $mark ."%'";
					}
				}
				$whereStr .= ')';
			}
			$markSqlStr = 'select IF1.IF_ID,IF2.IF_time,IF2.IF_theme,IF2.IF_themeStyle,IF2.IF_URL,IF2.IF_isEncUrl,IF2.IF_infoTypeDir,IF2.IF_datetimeDir from (select IF_ID from '. OT_dbPref .'info where IF_state=1 and IF_isAudit=1'. OT_TimeInfoWhereStr .''. $whereStr .' and IF_ID not in ('. $newsId .') order by IF_time DESC limit 0,'. $num .') as IF1 inner join '. OT_dbPref .'info as IF2 on IF1.IF_ID=IF2.IF_ID order by IF2.IF_time DESC';
		}

		if ($num > 0){
			$itemexe = $DB->query($markSqlStr);
			$itemRow = $itemexe->fetchAll();
			if (! $itemRow){
				$itemexe = $DB->query('select IF_ID,IF_time,IF_theme,IF_themeStyle,IF_URL,IF_isEncUrl,IF_infoTypeDir,IF_datetimeDir from '. OT_dbPref .'info where IF_state=1 and IF_isAudit=1 and IF_ID<='. ($newsId-1) .''. OT_TimeInfoWhereStr .' order by IF_time DESC limit 0,'. $num .'');
				$itemRow = $itemexe->fetchAll();
			}
			$markNewIdStr = '';
			if ($itemRow){
				$itemCount = count($itemRow);
				for ($i=0; $i<$itemCount; $i++){
					if ($itemRow[$i]['IF_URL']!=''){
						$hrefStr = Url::NewsUrl($itemRow[$i]['IF_URL'],$itemRow[$i]['IF_isEncUrl'],$itemRow[$i]['IF_ID'],$tpl->webPathPart);
					}else{
						$hrefStr = Url::NewsID($itemRow[$i]['IF_infoTypeDir'],$itemRow[$i]['IF_datetimeDir'],$itemRow[$i]['IF_ID']);
					}
					if (strlen($markNewIdStr)>0){ $markNewIdStr .= ','. $itemRow[$i]['IF_ID']; }else{ $markNewIdStr = $itemRow[$i]['IF_ID']; }
					$retStr .= '
					<li>
						<div class="fr">&ensp;'. TimeDate::Get('m-d',$itemRow[$i]['IF_time']) .'</div>
						<a href="'. $hrefStr .'" class="font1_1" style="'. $itemRow[$i]['IF_themeStyle'] .'" target="_blank" title="'. Str::MoreReplace($itemRow[$i]['IF_theme'],'input') .'">'. $itemRow[$i]['IF_theme'] .'</a>
					</li>
					';
				}
			}
			unset($itemexe);

			if ($infoSysArr['IS_isSaveMarkNewsId'] == 1 && $markNewIdStr != $markIdStr){
				$DB->query("update ". OT_dbPref ."info set IF_themeKeyIdStr='". $markNewIdStr ."' where IF_ID=". $newsId);
			}
		}

		if ($judGet){
			return $retStr;
		}else{
			echo($retStr);
		}
	}


	// 统计文章数量
	public static function StatiNewsCount($type,$judGet=true){
		global $DB,$tpl;

		$judCache = false;
		$cacheName='func_StatiNewsCount_'. $type;
		
		
		if ($retStr = Cache::CheckWebCache($cacheName)){ $judCache = true; }
		if ($judCache == false){
			if ($type == 'today'){
				$todayDate = TimeDate::Get('date');
				$whereStr = ' and IF_time>='. $DB->ForTime($todayDate .' 0:00:00') .' and IF_time<='. $DB->ForTime($todayDate .' 23:59:59') .'';
			}else{
				$whereStr = '';
			}
			$retStr = $DB->GetOne('select count(IF_ID) from '. OT_dbPref .'info where IF_isAudit=1 and IF_state=1'. $whereStr . OT_TimeInfoWhereStr);

			Cache::WriteWebCache($cacheName,$retStr);
		}

		if ($judGet){
			return $retStr;
		}else{
			echo($retStr);
		}
	}


	// 栏目项目
	public static function FontItem($typeStr,$typeLevel,$num,$judDate,$judType,$mode='pc',$judGet=true){
		global $DB,$tpl;

		$judCache = false;
		$cacheName='func_FontItem_'. $typeStr .'_'. $typeLevel .'_'. $num .'_'. $judDate .'_'. $judType .'_'. $mode .'_'. strlen($tpl->webPathPart);

		if ($retStr = Cache::CheckWebCache($cacheName)){ $judCache = true; }
		if ($judCache == false){

			$retStr = '';
			$whereStr = '';
			$orderName = 'time';
			if ($typeStr == 'announ'){
				$whereStr	= ' and IF_type1ID=-1';

			}elseif ($typeStr == 'recom'){
				$whereStr	= ' and IF_isRecom=1';

			}elseif (substr($typeStr,0,5) == 'recom'){
				if ($typeLevel>=1 && $typeLevel<=3){
					$whereStr	= ' and IF_isRecom=1 and IF_type'. $typeLevel .'ID='. substr($typeStr,5) .'';
				}else{
					$whereStr	= " and IF_isRecom=1 and IF_typeStr like '%,". substr($typeStr,5) .",%'";
				}

			}elseif ($typeStr == 'new'){
				$whereStr	= '';

			}elseif (substr($typeStr,0,3) == 'new'){
				if ($typeLevel>=1 && $typeLevel<=3){
					$whereStr	= ' and IF_type'. $typeLevel .'ID='. substr($typeStr,3) .'';
				}else{
					$whereStr	= " and IF_typeStr like '%,". substr($typeStr,3) .",%'";
				}

			}elseif ($typeStr == 'readNum'){
				$orderName	= 'readNum';
				$whereStr	= '';

			}elseif ($typeStr == 'homeHotSort'){
				$orderName	= $tpl->tplSysArr['TS_homeHotSort'];

			}elseif (substr($typeStr,0,7) == 'readNum'){
				$orderName	= 'readNum';
				if ($typeLevel>=1 && $typeLevel<=3){
					$whereStr	= ' and IF_type'. $typeLevel .'ID='. substr($typeStr,7) .'';
				}else{
					$whereStr	= " and IF_typeStr like '%,". substr($typeStr,7) .",%'";
				}

			}else{
				if ($typeLevel>=1 && $typeLevel<=3){
					$whereStr	= ' and IF_type'. $typeLevel .'ID='. $typeStr .'';
				}else{
					$whereStr	= " and IF_typeStr like '%,". $typeStr .",%'";
				}

			}

			if (! empty(self::$homeItemImgIdStr)){
				self::$homeItemImgIdStr = substr(self::$homeItemImgIdStr,0,-1);
				$whereStr .= ' and IF_ID not in ('. self::$homeItemImgIdStr .')';
			}

			$retStr .= '<ul>'. PHP_EOL;
			if ($num>0){
				self::$homeItemImgIdStr='';
				$todayDate = TimeDate::Get('date');
				$typeID = 0;
				$redDay = $tpl->tplSysArr['TS_redTimeDay']-1;

				if ($mode == 'wap'){
					$stateField = 'IF_wapState';
				}else{
					$stateField = 'IF_state';
				}

				$itemexe = $DB->query('select IF1.IF_ID,IF2.IF_time,IF2.IF_theme,IF2.IF_typeStr,IF2.IF_themeStyle,IF2.IF_URL,IF2.IF_isEncUrl,IF2.IF_infoTypeDir,IF2.IF_datetimeDir from (select IF_ID from '. OT_dbPref .'info where '. $stateField .'=1 and IF_isAudit=1'. $whereStr .''. OT_TimeInfoWhereStr .' order by IF_'. $orderName .' DESC limit 0,'. $num .') as IF1 inner join '. OT_dbPref .'info as IF2 on IF1.IF_ID=IF2.IF_ID order by IF2.IF_'. str_replace(',',',IF2.',$orderName) .' DESC');
				$itemRow = $itemexe->fetchAll();
				if ($itemRow){
					$rowCount = count($itemRow);
					for ($i=0; $i<$rowCount; $i++){
						if (! in_array($judDate,array(false,0,'false'))){
							if (TimeDate::Diff('d',$itemRow[$i]['IF_time'],$todayDate) <= $redDay){ $newTimeClass = 'font2_1 redFontClass'; }else{ $newTimeClass = 'font1_1 defFontClass'; }
							$dateStr='<div class="fr '. $newTimeClass .'">&ensp;'. TimeDate::Get('m-d',$itemRow[$i]['IF_time']) .'</div>';
						}else{
							$dateStr = '';
						}
						if (! in_array($judType,array(false,0,'false'))){
							$typeArr = explode(',',$itemRow[$i]['IF_typeStr']);
							if (count($typeArr)>=3){
								$typeID = $typeArr[count($typeArr)-2];
							}
							$currTypeArr = self::InfoTypeCN($itemRow[$i]['IF_typeStr']);
							$typeStr = '<a href="'. Url::ListID('',$currTypeArr['htmlName'],$typeID,0) .'" class="font2_1">['. $currTypeArr['theme'] .']</a>';
						}else{
							$typeStr = '';
						}
						if ($itemRow[$i]['IF_URL'] != ''){
							$hrefStr = Url::NewsUrl($itemRow[$i]['IF_URL'],$itemRow[$i]['IF_isEncUrl'],$itemRow[$i]['IF_ID'],$tpl->webPathPart);
						}else{
							$hrefStr = Url::NewsID($itemRow[$i]['IF_infoTypeDir'],$itemRow[$i]['IF_datetimeDir'],$itemRow[$i]['IF_ID']);
						}
						$retStr .= '
						<li>
							'. $dateStr .''. $typeStr .'<a href="'. $hrefStr .'" class="font1_1" style="'. $itemRow[$i]['IF_themeStyle'] .'" target="_blank" title="'. Str::MoreReplace($itemRow[$i]['IF_theme'],'input') .'">'. $itemRow[$i]['IF_theme'] .'</a>
						</li>
						';
					}
				}
				unset($itemexe);
			}
			$retStr .= '</ul>'. PHP_EOL;

			Cache::WriteWebCache($cacheName,$retStr);

		}

		if ($judGet){
			return $retStr;
		}else{
			echo($retStr);
		}
	}

	// 栏目项目（含图片）
	public static function ImgItem($typeStr,$typeLevel,$num,$mode='pc',$judGet=true){
		global $DB,$tpl;

		$judCache = false;
		$cacheName='func_ImgItem_'. $typeStr .'_'. $typeLevel .'_'. $num .'_'. $mode .'_'. strlen($tpl->webPathPart);

		if ($retStr = Cache::CheckWebCache($cacheName)){ $judCache = true; }
		if ($judCache == false){

			$retStr = '';
			$whereStr = '';
			$orderName = 'time';
			if ($typeStr == 'announ'){
				$whereStr	= ' and IF_type1ID=-1';

			}elseif ($typeStr == 'recom'){
				$whereStr	= ' and IF_isRecom=1';

			}elseif (substr($typeStr,0,5) == 'recom'){
				if ($typeLevel>=1 && $typeLevel<=3){
					$whereStr	= ' and IF_isRecom=1 and IF_type'. $typeLevel .'ID='. substr($typeStr,5) .'';
				}else{
					$whereStr	= " and IF_isRecom=1 and IF_typeStr like '%,". substr($typeStr,5) .",%'";
				}

			}elseif ($typeStr == 'new'){
				$whereStr	= '';

			}elseif (substr($typeStr,0,3) == 'new'){
				if ($typeLevel>=1 && $typeLevel<=3){
					$whereStr	= ' and IF_type'. $typeLevel .'ID='. substr($typeStr,3) .'';
				}else{
					$whereStr	= " and IF_typeStr like '%,". substr($typeStr,3) .",%'";
				}

			}elseif ($typeStr == 'readNum'){
				$orderName	= 'readNum';
				$whereStr	= '';

			}elseif ($typeStr == 'homeHotSort'){
				$orderName	= $tpl->tplSysArr['TS_homeHotSort'];

			}elseif (substr($typeStr,0,7) == 'readNum'){
				$orderName	= 'readNum';
				if ($typeLevel>=1 && $typeLevel<=3){
					$whereStr	= ' and IF_type'. $typeLevel .'ID='. substr($typeStr,7) .'';
				}else{
					$whereStr	= " and IF_typeStr like '%,". substr($typeStr,7) .",%'";
				}

			}else{
				if ($typeLevel>=1 && $typeLevel<=3){
					$whereStr	= ' and IF_type'. $typeLevel .'ID='. $typeStr .'';
				}else{
					$whereStr	= " and IF_typeStr like '%,". $typeStr .",%'";
				}

			}

			if ($num<=0){ $num=100; }

			if ($mode == 'wap'){
				$urlHead = $tpl->pcUrl;
				$noImgPath = 'images/noPic.png';
				$stateField = 'IF_wapState';
				$tr1Str = '<table cellpadding="0" cellspacing="0" width="100%"><tr><td class="a">';
				$tr2Str = '</td><td class="b">';
				$tr3Str = '</td></tr></table>';
			}else{
				$urlHead = $tpl->webPathPart;
				$noImgPath = 'inc_img/noPic.gif';
				$stateField = 'IF_state';
				$tr1Str = '<div class="a">';
				$tr2Str = '</div><div class="b">';
				$tr3Str = '</div>';
			}

			$itemexe = $DB->query('select IF1.IF_ID,IF2.IF_theme,IF2.IF_img,IF2.IF_contentKey,IF2.IF_URL,IF2.IF_isEncUrl,IF2.IF_infoTypeDir,IF2.IF_datetimeDir from (select IF_ID from '. OT_dbPref .'info where '. $stateField .'=1 and IF_isAudit=1 and IF_isHomeThumb=1'. $whereStr .''. OT_TimeInfoWhereStr .' order by IF_'. $orderName .' DESC limit 0,'. $num .') as IF1 inner join '. OT_dbPref .'info as IF2 on IF1.IF_ID=IF2.IF_ID order by IF2.IF_'. str_replace(',',',IF2.',$orderName) .' DESC');
			$itemRow = $itemexe->fetchAll();
			if ($itemRow){
				$rowCount = count($itemRow);
				switch ($tpl->sysArr['SYS_templateDir']){
					case 'def_blue/':	$contKeyNum = 49;	break;
					case 'def_white/':	$contKeyNum = 58;	break;
					default :			$contKeyNum = 62;	break;
				}

				for ($i=0; $i<$rowCount; $i++){
					self::$homeItemImgIdStr .= $itemRow[$i]['IF_ID'] .',';
					if ($itemRow[$i]['IF_URL'] != ''){
						$hrefStr = Url::NewsUrl($itemRow[$i]['IF_URL'],$itemRow[$i]['IF_isEncUrl'],$itemRow[$i]['IF_ID'],$urlHead);
					}else{
						$hrefStr = Url::NewsID($itemRow[$i]['IF_infoTypeDir'],$itemRow[$i]['IF_datetimeDir'],$itemRow[$i]['IF_ID']);
					}
					$themeStr = Str::MoreReplace($itemRow[$i]['IF_theme'],'input');
					$retStr .= '
					<div class="imgRow">
						'. $tr1Str .'
							<div class="img"><a href="'. $hrefStr .'" class="font1_1" target="_blank"><img src="'. Area::InfoImgUrl($itemRow[$i]['IF_img'],$urlHead . InfoImgDir) .'" onerror="if (this.value!=\'1\'){this.value=\'1\';this.src=\''. $noImgPath .'\';}" alt="'. $themeStr .'" title="'. $themeStr .'" /></a></div>
						'. $tr2Str .'
							<h2><a href="'. $hrefStr .'" class="font1_1" target="_blank" title="'. $themeStr .'">'. $itemRow[$i]['IF_theme'] .'</a></h2>
							<span>'. Str::LimitChar($itemRow[$i]['IF_contentKey'],$contKeyNum) .'&ensp;<a href="'. $hrefStr .'" class="font2_2" target="_blank">&ensp;阅读全文&gt;&gt;</a></span>
						'. $tr3Str .'
					</div><div class="clr"></div>
					';
				}
			}
			unset($itemexe);

			Cache::WriteWebCache($cacheName,$retStr);

		}

		if ($judGet){
			return $retStr;
		}else{
			echo($retStr);
		}
	}


	// 单篇页调用
	public static function DynWeb($webID,$mode='',$fontSize=0,$judGet=true){
		global $DB,$tpl;

		$judCache = false;
		$cacheName='func_DynWeb_'. $webID .'_'. $mode .'_'. $fontSize .'_'. strlen($tpl->webPathPart);

		if ($retStr = Cache::CheckWebCache($cacheName)){ $judCache = true; }
		if ($judCache == false){

			$webexe = $DB->query('select IW_content from '. OT_dbPref .'infoWeb where IW_ID='. $webID);
			if (! $row = $webexe->fetch()){
				$retStr = '无内容';
			}else{
				$retStr = $row['IW_content'];
				if ($mode=='noHtml'){
					$retStr = Str::RegExp($retStr,'html');
				}else{
					if ($tpl->webPathPart != '../'){ $retStr	= str_replace(InfoImgAdminDir,$tpl->webPathPart . InfoImgDir,$retStr); }
				}
				if ($fontSize>0){ $retStr = Str::LimitChar($retStr,$fontSize); }
			}
			unset($webexe);

			Cache::WriteWebCache($cacheName,$retStr);

		}

		if ($judGet){
			return $retStr;
		}else{
			echo($retStr);
		}
	}


	// 广告调用
	public static function Ads($adID,$judGet=true){
		global $DB,$tpl;

		$judCache = false;
		$cacheName='func_Ads_'. $adID .'_'. strlen($tpl->webPathPart);

		if ($retStr = Cache::CheckWebCache($cacheName)){ $judCache = true; }
		if ($judCache == false){

			$adexe = $DB->query('select AD_code from '. OT_dbPref .'ad where AD_num='. $adID .' and AD_state=1');
			if (! $row = $adexe->fetch()){
				$retStr = '';
			}else{
				$retStr = str_replace(InfoImgAdminDir, $tpl->webPathPart . InfoImgDir, $row['AD_code']);
			}
			unset($adexe);

			Cache::WriteWebCache($cacheName,$retStr);

		}

		if ($judGet){
			return $retStr;
		}else{
			echo($retStr);
		}
	}


	// 信息栏目
	public static function ItemList($num,$judGet=true){
		global $DB,$tpl;

/*		$judCache = false;
		$cacheName='func_ItemList_'. $num .'_'. strlen($tpl->webPathPart);

		if ($retStr = Cache::CheckWebCache($cacheName)){ $judCache = true; }
		if ($judCache == false){
*/
			$retStr = '';
			$dataNum = 1;
			$adNum = 0;
			if ($num<=0){ $num=200; }

			$typeexe = $DB->query('select IT_ID,IT_level,IT_homeIco,IT_isHomeImg,IT_openMode,IT_theme,IT_mode,IT_webID,IT_URL,IT_isEncUrl,IT_homeNum,IT_isItemDate,IT_isItemType,IT_htmlName from '. OT_dbPref .'infoType where IT_state=1 and IT_isHome=1 order by IT_itemRank ASC,IT_rank ASC');
			$typeRow = $typeexe->fetchAll();
			if ($typeRow){
				$rowCount = count($typeRow);
				for ($i=0; $i<$rowCount; $i++){
					$imgStr = $itemStyleStr = '';
					if ($typeRow[$i]['IT_isHomeImg'] > 0){ $imgStr=self::ImgItem($typeRow[$i]['IT_ID'],$typeRow[$i]['IT_level'],$typeRow[$i]['IT_isHomeImg']); }
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

					if ($dataNum <= 2){ $itemStyleStr .= ' margin-top:0px;'; }
					if ($dataNum % 2 == 0){ $itemStyleStr .= ' margin-right:0px;'; }
					$retStr .= '
					<div class="itemBox" style="'. $itemStyleStr .'">
					<dl>
						<dt><div class="more"><a href="'. $moreHref .'" target="'. $typeRow[$i]['IT_openMode'] .'"></a></div><span class="pointer" onclick=\''. $moreHref2 .'\'>'. $typeRow[$i]['IT_theme'] .'</span></dt>
						<dd class="listArrow3 listIco'. $typeRow[$i]['IT_homeIco'] .'">
							<ul>
								'. $imgStr .
								self::FontItem($typeRow[$i]['IT_ID'],$typeRow[$i]['IT_level'],$typeRow[$i]['IT_homeNum'],$typeRow[$i]['IT_isItemDate'],$typeRow[$i]['IT_isItemType']) .'
							</ul>
						</dd>
					</dl>
					</div>
					';
					if ($dataNum % 2 == 0){
						$retStr .= '<div class="clr"></div>';
						if ($adNum<10){
							$adNum ++;
							$retStr .= '
							<div class="caClass leftCa1 ca'. ($adNum+50) .'Style">
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

/*			Cache::WriteWebCache($cacheName,$retStr);
		}
*/
		if ($judGet){
			return $retStr;
		}else{
			echo($retStr);
		}
	}



	// 信息栏目(首页三栏式)
	public static function ItemList3($num,$judGet=true){
		global $DB,$tpl;

/*		$judCache = false;
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
					$imgStr = $itemStyleStr = '';
					if ($typeRow[$i]['IT_isHomeImg'] > 0){ $imgStr=self::ImgItem($typeRow[$i]['IT_ID'],$typeRow[$i]['IT_level'],$typeRow[$i]['IT_isHomeImg']); }
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
					if ($dataNum <= 3){ $itemStyleStr .= 'margin-top:0px;'; }
					if ($dataNum % 3 == 0){ $itemStyleStr = 'margin-right:0px;'; }else{ $itemStyleStr = 'margin-right:8px;'; }
					$retStr .= '
					<div class="itemBox3 itemCell'. ($dataNum % 3) .'" style="'. $itemStyleStr .'">
					<dl>
						<dt><div class="more"><a href="'. $moreHref .'" target="'. $typeRow[$i]['IT_openMode'] .'"></a></div><span class="pointer" onclick=\''. $moreHref2 .'\'>'. $typeRow[$i]['IT_theme'] .'</span></dt>
						<dd class="listArrow3 listIco'. $typeRow[$i]['IT_homeIco'] .'">
							<ul>
								'. $imgStr .
								self::FontItem($typeRow[$i]['IT_ID'],$typeRow[$i]['IT_level'],$typeRow[$i]['IT_homeNum'],$typeRow[$i]['IT_isItemDate'],$typeRow[$i]['IT_isItemType']) .'
							</ul>
						</dd>
					</dl>
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

/*			Cache::WriteWebCache($cacheName,$retStr);

		}
*/
		if ($judGet){
			return $retStr;
		}else{
			echo($retStr);
		}
	}



	// 推荐栏目
	public static function RecomList($judGet=true){
		global $DB,$tpl;

/*		$judCache = false;
		$cacheName='func_RecomList_'. $num .'_'. strlen($tpl->webPathPart);

		if ($retStr = Cache::CheckWebCache($cacheName)){ $judCache = true; }
		if ($judCache == false){
*/
		$retStr = '';
		if ($tpl->tplSysArr['TS_homeRecomImgNum'] > 0){
			$retStr .= self::ImgItem('recom',0,$tpl->tplSysArr['TS_homeRecomImgNum']);
		}
		if ($tpl->tplSysArr['TS_homeRecomNum'] > 0){
			$retStr .= self::FontItem('recom',0,$tpl->tplSysArr['TS_homeRecomNum'],0,0);
		}

/*			Cache::WriteWebCache($cacheName,$retStr);
		}
*/
		if ($judGet){
			return $retStr;
		}else{
			echo($retStr);
		}
	}



	// 热门栏目
	public static function HotList($judGet=true){
		global $DB,$tpl;

/*		$judCache = false;
		$cacheName='func_HotList_'. $num .'_'. strlen($tpl->webPathPart);

		if ($retStr = Cache::CheckWebCache($cacheName)){ $judCache = true; }
		if ($judCache == false){
*/
		$retStr = '';
		if ($tpl->tplSysArr['TS_homeHotImgNum'] > 0){
			$retStr .= self::ImgItem('homeHotSort',0,$tpl->tplSysArr['TS_homeHotImgNum']);
		}
		if ($tpl->tplSysArr['TS_homeHotNum'] > 0){
			$retStr .= self::FontItem('homeHotSort',0,$tpl->tplSysArr['TS_homeHotNum'],$tpl->tplSysArr['TS_homeHotIsDate'],0);
		}

/*			Cache::WriteWebCache($cacheName,$retStr);
		}
*/
		if ($judGet){
			return $retStr;
		}else{
			echo($retStr);
		}
	}



	// 最新会员排行
	public static function NewUsers($num,$judGet=true){
		global $DB,$tpl,$systemArr;

		if ($tpl->tplSysArr['TS_isHomeNewUsers'] == 0){ return ''; }

		$retStr = '';
		$rank = 0;
		$messexe=$DB->query('select UE1.UE_ID,UE2.UE_username,UE2.UE_realname,UE2.UE_time from (select UE_ID from '. OT_dbPref .'users where UE_state=1 order by UE_time DESC limit 0,'. $num .') as UE1 inner join '. OT_dbPref .'users as UE2 on UE1.UE_ID=UE2.UE_ID order by UE2.UE_time DESC');
		while ($row = $messexe->fetch()){
			$rank ++;
			if ($tpl->tplSysArr['TS_rankUserMode'] == 2){
				$username	= $row['UE_realname'] .'&ensp;';
			}elseif ($tpl->tplSysArr['TS_rankUserMode'] == 1){
				$username	= Str::PartHide($row['UE_username']);
			}else{
				$username	= $row['UE_username'];
			}
			$retStr .= '
			<div class="newMessItemNo">
				<div class="fr">'. TimeDate::Get('m-d H:i',$row['UE_time']) .'</div>
				<div class="left rankFont">'. Str::FixLen($rank,2) .'</div>
				'. $username .'
			</div>
			';
		}
		unset($messexe);

		if ($judGet){
			return $retStr;
		}else{
			echo($retStr);
		}
	}



	// 会员积分排行
	public static function RankUsers($num,$judGet=true){
		global $DB,$tpl,$systemArr;

		if ($tpl->tplSysArr['TS_isHomeRankUsers'] == 0){ return ''; }

		$retStr = '';
		$rank = 0;
		$messexe=$DB->query('select UE1.UE_ID,UE2.UE_username,UE2.UE_realname,UE2.UE_'. $tpl->tplSysArr['TS_homeRankUsersType'] .' from (select UE_ID from '. OT_dbPref .'users where UE_state=1 order by UE_'. $tpl->tplSysArr['TS_homeRankUsersType'] .' DESC limit 0,'. $num .') as UE1 inner join '. OT_dbPref .'users as UE2 on UE1.UE_ID=UE2.UE_ID order by UE2.UE_'. $tpl->tplSysArr['TS_homeRankUsersType'] .' DESC');
		while ($row = $messexe->fetch()){
			$rank ++;
			if ($tpl->tplSysArr['TS_rankUserMode'] == 2){
				$username	= $row['UE_realname'] .'&ensp;';
			}elseif ($tpl->tplSysArr['TS_rankUserMode'] == 1){
				$username	= Str::PartHide($row['UE_username']);
			}else{
				$username	= $row['UE_username'];
			}
			$retStr .= '
			<div class="newMessItemNo">
				<div class="fr">'. $row['UE_'. $tpl->tplSysArr['TS_homeRankUsersType']] .'</div>
				<div class="left rankFont">'. Str::FixLen($rank,2) .'</div>
				'. $username .'
			</div>
			';
		}
		unset($messexe);

		if ($judGet){
			return $retStr;
		}else{
			echo($retStr);
		}
	}



	// 会员签到排行
	public static function QiandaoRank($num,$judGet=true){
		global $DB,$tpl,$systemArr;

		if ($tpl->tplSysArr['TS_isQiandaoRank'] == 0){ return ''; }

		$retStr = '';
		$rank = 0;
		$messexe=$DB->query('select UE1.UE_ID,UE2.UE_username,UE2.UE_realname,UE2.UE_'. $tpl->tplSysArr['TS_qiandaoRankType'] .' from (select UE_ID from '. OT_dbPref .'users where UE_state=1 order by UE_'. $tpl->tplSysArr['TS_qiandaoRankType'] .' DESC limit 0,'. $num .') as UE1 inner join '. OT_dbPref .'users as UE2 on UE1.UE_ID=UE2.UE_ID order by UE2.UE_'. $tpl->tplSysArr['TS_qiandaoRankType'] .' DESC');
		while ($row = $messexe->fetch()){
			$rank ++;
			if ($tpl->tplSysArr['TS_rankUserMode'] == 2){
				$username	= $row['UE_realname'] .'&ensp;';
			}elseif ($tpl->tplSysArr['TS_rankUserMode'] == 1){
				$username	= Str::PartHide($row['UE_username']);
			}else{
				$username	= $row['UE_username'];
			}
			$retStr .= '
			<div class="newMessItemNo">
				<div class="fr">'. $row['UE_'. $tpl->tplSysArr['TS_qiandaoRankType']] .'</div>
				<div class="left rankFont">'. Str::FixLen($rank,2) .'</div>
				'. $username .'
			</div>
			';
		}
		unset($messexe);

		if ($judGet){
			return $retStr;
		}else{
			echo($retStr);
		}
	}



	// 最新留言
	public static function NewMessage($num,$judGet=true){
		global $DB,$tpl,$systemArr;

		if ($tpl->tplSysArr['TS_isHomeMessage'] == 0){ return ''; }

		if ($tpl->tplSysArr['TS_homeMessageH']>0){ $homeMessHeightStr='height:'. $tpl->tplSysArr['TS_homeMessageH'] .'px;'; }else{ $homeMessHeightStr=''; }
		$retStr = '<div style="'. $homeMessHeightStr .'overflow:'. $tpl->tplSysArr['TS_homeMessageHmode'] .';">';

		$messexe=$DB->query('select MA1.MA_ID,MA2.MA_username,MA2.MA_content,MA2.MA_isReply,MA2.MA_reply from (select MA_ID from '. OT_dbPref .'message where MA_state=1 order by MA_time DESC limit 0,'. $num .') as MA1 inner join '. OT_dbPref .'message as MA2 on MA1.MA_ID=MA2.MA_ID order by MA2.MA_time DESC');
		while ($row = $messexe->fetch()){
			if ($row['MA_isReply'] == 0){
				$repStr='';
			}else{
				$repStr='<div style="color:red;font-size:12px;"><b>'. $tpl->tplSysArr['TS_messageAdminName'] .'：</b>'. Area::FaceSignToImg($row['MA_reply'],'') .'</div>';	// '<span class="font2_2">[已回]</span>'
			}
			$retStr .= '
			<div class="newMessItemNo">
				<b class="user">'. $row['MA_username'] .'</b>:
				<div class="cont">'. Area::FaceSignToImg(Str::LimitChar(str_replace(array('&nbsp;','&ensp;'),'',Str::RegExp(Area::MessageEventDeal($row['MA_content'],$tpl->tplSysArr['TS_messageEvent']),'html')),$tpl->tplSysArr['TS_homeMessageLen'],'')) . $repStr .'</div>
			</div>
			';
		}
		unset($messexe);

		$retStr .= '</div>';

		if ($judGet){
			return $retStr;
		}else{
			echo($retStr);
		}
	}



	// 最新评论
	public static function NewReply($num,$judGet=true){
		global $DB,$tpl,$systemArr;

		if ($tpl->tplSysArr['TS_isHomeReply'] == 0){ return ''; }

		if ($tpl->tplSysArr['TS_homeReplyH']>0){ $homeReplyHeightStr='height:'. $tpl->tplSysArr['TS_homeReplyH'] .'px;'; }else{ $homeReplyHeightStr=''; }
		$retStr = '<div style="'. $homeReplyHeightStr .'overflow:'. $tpl->tplSysArr['TS_homeReplyHmode'] .';">';

		$replyexe=$DB->query('select IM.*,IF0.IF_infoTypeDir,IF0.IF_datetimeDir,IF0.IF_theme from (select IM_ID,IM_time,IM_infoID,IM_username,IM_content,IM_isReply,IM_reply from '. OT_dbPref .'infoMessage where IM_state=1 order by IM_time DESC limit 0,'. $num .') as IM inner join '. OT_dbPref .'info as IF0 on IM.IM_infoID=IF0.IF_ID order by IM.IM_time DESC');
		while ($row = $replyexe->fetch()){
			if ($row['IM_isReply'] == 0){
				$repStr='';
			}else{
				$repStr='<div style="color:red;font-size:12px;"><b>'. $tpl->tplSysArr['TS_messageAdminName'] .'：</b>'. Area::FaceSignToImg($row['IM_reply'],'') .'</div>';	// '<span class="font2_2">[已回]</span>';
			}
			$retStr .= '
			<div class="newMessItem">
				<b class="user">'. $row['IM_username'] .'</b>:
				<a href="'. Url::NewsID($row['IF_infoTypeDir'],$row['IF_datetimeDir'],$row['IM_infoID'],0) .'" class="font1_1" target="_blank" title="《'. Str::MoreReplace($row['IF_theme'],'input') .'》的评论">
					<div class="cont">'. Area::FaceSignToImg(Str::LimitChar(str_replace(array('&nbsp;','&ensp;'),'',Str::RegExp(Area::MessageEventDeal($row['IM_content'],$tpl->tplSysArr['TS_messageEvent']),'html')),$tpl->tplSysArr['TS_homeReplyLen']),'') . $repStr .'</div>
				</a>
			</div>
			';
		}
		unset($replyexe);

		$retStr .= '</div>';

		if ($judGet){
			return $retStr;
		}else{
			echo($retStr);
		}
	}



	// 最新论坛帖子
	public static function NewBbs($num,$judGet=true){
		global $DB,$tpl;

		if ($tpl->tplSysArr['TS_isHomeBbs'] == 0){ return ''; }

		$retStr = '';
		if ($tpl->tplSysArr['TS_homeBbsH']>0){ $homeBbsHeightStr='height:'. $tpl->tplSysArr['TS_homeBbsH'] .'px;'; }else{ $homeBbsHeightStr=''; }
		$retStr .= '
		<div class="height5"></div>
		<div class="clr"></div>
		<div class="typeBox">
		<dl>
			<dt>'. $tpl->tplSysArr['TS_homeBbsName'] .'</dt>
			<dd class="listArrow1" style="'. $homeBbsHeightStr .'overflow:'. $tpl->tplSysArr['TS_homeBbsHmode'] .';">
			<ul>
			';
			$bbsexe=$DB->query('select BD1.BD_ID,BD2.BD_username,BD2.BD_themeStyle,BD2.BD_theme from (select BD_ID from '. OT_dbPref .'bbsData where BD_state=1 order by BD_time DESC limit 0,'. $num .') as BD1 inner join '. OT_dbPref .'bbsData as BD2 on BD1.BD_ID=BD2.BD_ID order by BD2.BD_time DESC');
			while ($row = $bbsexe->fetch()){
				$retStr .= '
				<li>
					<a href="message/?'. $row['BD_ID'] .'.html" class="font1_1" style="'. $row['BD_themeStyle'] .'" target="_blank" title="'. Str::MoreReplace($row['BD_theme'],'input') .'">'. StrLen($row['BD_theme'],$tpl->tplSysArr['TS_homeBbsLen']) .'</a>
				</li>
				';
			}
			unset($bbsexe);

			$retStr .= '
			</ul>
			</dd>
		</dl>
		</div>
		<div class="clr"></div>
		';

		if ($judGet){
			return $retStr;
		}else{
			echo($retStr);
		}
	}



	// 滚动图片
	public static function MarImgBox($num,$judGet=true){
		global $DB,$tpl;

		if ($tpl->tplSysArr['TS_isHomeMarImg'] == 0){ return ''; }

		$retStr = '';
		if ($num<=0){ $num=20; }
		$marexe=$DB->query('select IF1.IF_ID,IF2.IF_theme,IF2.IF_URL,IF2.IF_isEncUrl,IF2.IF_img,IF2.IF_infoTypeDir,IF2.IF_datetimeDir from (select IF_ID from '. OT_dbPref .'info where IF_state=1 and IF_isAudit=1 and IF_isImg=1'. OT_TimeInfoWhereStr .' order by IF_time DESC limit 0,'. $num .') as IF1 inner join '. OT_dbPref .'info as IF2 on IF1.IF_ID=IF2.IF_ID order by IF2.IF_time DESC');

		if ($row = $marexe->fetch()){
			$retStr .= '
			<div class="imgBox list">
				<div id="caseMarX" style="overflow:hidden; width:100%; height:100%;">
					<div style="width:10000px;">
					<table cellpadding="0" cellspacing="0">
					<tr>
						<td id="caseMarX1">
							<table cellpadding="0" cellspacing="0"><tr>
							';
							do {
								$themeStr = Str::MoreReplace($row['IF_theme'],'input');
								if ($row['IF_URL'] != ''){
									$hrefStr = Url::NewsUrl($row['IF_URL'],$row['IF_isEncUrl'],$row['IF_ID'],$tpl->webPathPart);
								}else{
									$hrefStr = Url::NewsID($row['IF_infoTypeDir'],$row['IF_datetimeDir'],$row['IF_ID']);
								}
								$retStr .= '
								<td>
									<div class="a">
										<a href="'. $hrefStr .'" class="font1_1" target="_blank">
											<img src="'. Area::InfoImgUrl($row['IF_img'],$tpl->webPathPart . InfoImgDir) .'" alt="'. $themeStr .'" title="'. $themeStr .'" onerror="if (this.value!=\'1\'){this.value=\'1\';this.src=\'inc_img/noPic.gif\';}" /><br />
											<div>'. $row['IF_theme'] .'</div>
										</a>
									</div>
								</td>
								';
							}while ($row = $marexe->fetch());

							$retStr .= '
							</tr></table>
						</td>
						<td id="caseMarX2"></td>
						<td id="caseMarX3"></td>
					</tr>
					</table>
					</div>
				</div>
			</div>
			<div class="clr"></div>
			';
		}
		unset($marexe);

		if ($judGet){
			return $retStr;
		}else{
			echo($retStr);
		}
	}


	// 文章标签信息
	public static function MarkStr($themeKey,$judGet=true){
		global $tpl;

		$retStr = '';

		if ($tpl->tplSysArr['TS_isMark'] == 2){ $nofollStr=' rel="nofollow"'; }else{ $nofollStr=''; }
		if (strlen($themeKey)>0 && $tpl->tplSysArr['TS_isMark']>0){
			$retStr .= '<span class="font2_2">标签：</span>';
			$markArr = explode(',',$themeKey);
			foreach ($markArr as $mark){
				$retStr .= '<a href="'. Url::ListRefMark('mark',$mark,'',0) .'" class="font1_2d" target="_blank"'. $nofollStr .'>'. $mark .'</a>&ensp;&ensp;';
			}
		}

		if ($judGet){
			return $retStr;
		}else{
			echo($retStr);
		}
	}


	// 列表页二级导航菜单栏
	public static function SubRightNavMenu($type1ID,$judGet=true){
		global $DB,$tpl;

		$judCache = false;
		$cacheName='func_SubRightNavMenu_'. $type1ID .'_'. strlen($tpl->webPathPart);

		if ($retStr = Cache::CheckWebCache($cacheName)){ $judCache = true; }
		if ($judCache == false){

			$retStr = '';
			$menu2exe=$DB->query('select IT_ID,IT_theme,IT_mode,IT_webID,IT_URL,IT_isEncUrl,IT_openMode,IT_webID,IT_htmlName from '. OT_dbPref .'infoType where IT_state=1 and IT_fatID='. $type1ID .' order by IT_rank ASC');
				if (! $row = $menu2exe->fetch()){
					$retStr .= '该栏目下无二级栏目';
				}else{
					do {
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
						<li><a href="'. $hrefStr .'" target="'. $row['IT_openMode'] .'">'. $row['IT_theme'] .'</a></li>
						';
					}while ($row = $menu2exe->fetch());
				}
			unset($menu2exe);

			Cache::WriteWebCache($cacheName,$retStr);

		}

		if ($judGet){
			return $retStr;
		}else{
			echo($retStr);
		}
	}


	// banner广告图调用
	public static function BannerImg($id,$style='',$mode='',$judGet=true){
		global $DB,$tpl;

		$judCache = false;
		$cacheName='func_BannerImg_'. $id .'_'. md5($style . $mode) .'_'. strlen($tpl->webPathPart);

		$retStr = '';
		if ($retStr = Cache::CheckWebCache($cacheName)){ $judCache = true; }
		if ($judCache == false){
			$banexe = $DB->query('select * from '. OT_dbPref .'banner where BN_ID='. $id);
			if ($row = $banexe->fetch()){
				$retStr = '<img src="'. ImagesFileDir . $row['BN_img'] .'" style="'. $style .'" />';
				if (strlen($row['BN_url']) > 7){ $retStr ='<a href="'. $row['BN_url'] .'" target="_blank">'. $retStr .'</a>'; }
			}
			unset($marexe);

			Cache::WriteWebCache($cacheName,$retStr);

		}

		if ($judGet){
			return $retStr;
		}else{
			echo($retStr);
		}
	}

}

?>
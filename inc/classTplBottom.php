<?php

if(! defined('OT_ROOT')) {
	exit('Access Denied');
}


class TplBottom{

	// 友情链接
	public static function LogoBox($mode='pc',$judGet=true){
		global $DB,$tpl;

		$judCache = false;
		$cacheName='func_LogoBox_'. $mode .'_'. strlen($tpl->webPathPart);

		if ($retStr = Cache::CheckWebCache($cacheName)){ $judCache = true; }
		if ($judCache == false){
			if ($mode == 'wap'){
				$fieldName = 'IM_wapState';
				$urlHead = $tpl->pcUrl;
			}else{
				$fieldName = 'IM_state';
				$urlHead = $tpl->webPathPart;
			}

			$retStr = '';
			$todayDate = TimeDate::Get('date');
			$logoexe=$DB->query('select IM_ID,IM_theme,IM_URL,IM_alt from '. OT_dbPref .'infoMove where '. $fieldName .'=1 and IM_isImg=0 and IM_type='. $DB->ForStr('logo') .' and IM_startDate<='. $DB->ForTime($todayDate) .' and IM_endDate>='. $DB->ForTime($todayDate) .' order by IM_rank ASC');
			$logoRow = $logoexe->fetchAll();
			if ($logoRow){
				$rowCount = count($logoRow);
				for ($i=0; $i<$rowCount; $i++){
					if ($i>=1){ $retStr .= '<span class="font1_1d">&ensp;|&ensp;</span>'; }
					$retStr .= '<a href="'. $logoRow[$i]['IM_URL'] .'" class="font1_1" target="_blank" title="'. $logoRow[$i]['IM_alt'] .'">'. $logoRow[$i]['IM_theme'] .'</a>';
				}

				$retStr .= '<br />';
			}
			$logoexe = null;

			$logoexe=$DB->query('select IM_ID,IM_theme,IM_URL,IM_imgMode,IM_img,IM_alt from '. OT_dbPref .'infoMove where '. $fieldName .'=1 and IM_isImg>=1 and IM_type='. $DB->ForStr('logo') .' and IM_startDate<='. $DB->ForTime($todayDate) .' and IM_endDate>='. $DB->ForTime($todayDate) .' order by IM_rank ASC');
			while ($row = $logoexe->fetch()){
				if ($row['IM_imgMode'] == 'upImg'){ $imgUrl = $urlHead . ImagesFileDir . $row['IM_img']; }else{ $imgUrl = $row['IM_img']; }
				$imgAlt = Str::MoreReplace($row['IM_theme'],'input');
				$retStr .= '<a href="'. $row['IM_URL'] .'" class="font1_1" target="_blank" title="'. $row['IM_alt'] .'"><img src="'. $imgUrl .'" alt="'. $imgAlt .'" title="'. $imgAlt .'" class="img" /></a>'. PHP_EOL;
			
			}
			unset($logoexe);

			Cache::WriteWebCache($cacheName,$retStr);

		}

		if ($judGet){
			return $retStr;
		}else{
			echo($retStr);
		}
	}


	// 底部栏目
	public static function BottomMenu($mode='pc',$judGet=true){
		global $DB,$tpl;

		$judCache = false;
		$cacheName='func_BottomMenu_'. $mode .'_'. strlen($tpl->webPathPart);

		if ($retStr = Cache::CheckWebCache($cacheName)){ $judCache = true; }
		if ($judCache == false){
			if ($mode == 'wap'){
				$fieldName = 'IW_wapState';
				$urlHead = $tpl->webPathPart;
			}else{
				$fieldName = 'IW_state';
				$urlHead = $tpl->webHost;
			}

			$retStr = '';
			$bMenuNum=0;
			$webexe=$DB->query('select IW_ID,IW_theme,IW_themeStyle,IW_mode,IW_URL,IW_isEncUrl from '. OT_dbPref .'infoWeb where '. $fieldName .'=1 and IW_type='. $DB->ForStr('bottom') .' order by IW_rank ASC');
			while ($row = $webexe->fetch()){
				if ($row['IW_mode'] == 'sitemap'){
					$webURL		= $urlHead .'sitemap.html';
					$webTarget	= ' target="_blank"';
				}elseif ($row['IW_mode'] == 'message'){
					$webURL		= $urlHead .'message.php';
					$webTarget	= ' target="_blank"';
				}elseif ($row['IW_mode'] == 'url'){
					$webURL		= Url::WebUrl($row['IW_URL'],$row['IW_isEncUrl'],$row['IW_ID'],$tpl->webPathPart);
					$webTarget	= ' target="_blank"';
				}else{
					$webURL		= Url::WebID($row['IW_ID'],0,$urlHead);
					$webTarget	= '';
				}
				$bMenuNum ++;
				if ($bMenuNum>1){ $retStr .= '&ensp;-&ensp;'; }
				$retStr .= '<a href="'. $webURL .'" style="'. $row['IW_themeStyle'] .'" class="font1_1"'. $webTarget .'>'. $row['IW_theme'] .'</a>';
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
}

?>
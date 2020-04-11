<?php

if(! defined('OT_ROOT')) {
	exit('Access Denied');
}


class Url{

	public static function ListRefMark($urlTypeStr, $urlTypeStr2, $urlTypeStr3, $urlPageID=0, $urlDomain=''){
		if (strlen($urlTypeStr3)>0){
			$urlTypeStr .= '-'. $urlTypeStr2 .'-'. urlencode($urlTypeStr3);
		}else{
			$urlTypeStr .= '-'. urlencode($urlTypeStr2);
		}

		return self::ListStr($urlTypeStr, $urlPageID, $urlDomain);
	}

	public static function ListStr($urlTypeStr, $urlPageID=0, $urlDomain=''){
		global $GB_WebHost,$systemArr;

		$retStr = '';
		$pageStr= '';
		if (strlen($urlDomain) == 0){ $urlDomain = $GB_WebHost; }
		switch ($systemArr['SYS_newsListUrlMode']){
			case 'html-2.x':
				if ($systemArr['SYS_diyInfoTypeDir'] == 1 && strpos('|announ|new|','|'. $urlTypeStr .'|') !== false){
					if ($urlPageID>1){ $pageStr='index_'. $urlPageID .'.html'; }else{ $pageStr=''; }
					$retStr = $urlDomain . $urlTypeStr .'/'. $pageStr;
				}else{
					if ($urlPageID>1){ $pageStr='_'. $urlPageID; }else{ $pageStr=''; }
					if (strpos($urlTypeStr,'-') !== false){
						$retStr = $urlDomain .'news/?list_'. $urlTypeStr . $pageStr .'.html';
					}else{
						$retStr = $urlDomain . $systemArr['SYS_newsListFileName'] .'/list_'. $urlTypeStr . $pageStr .'.html';
					}
				}
				break;
		
			case 'static-3.x':
				if ($urlPageID>1){ $pageStr='_'. $urlPageID; }else{ $pageStr=''; }
				if (strpos($urlTypeStr,'-') !== false){
					$retStr = $urlDomain .'news/?list_'. $urlTypeStr . $pageStr .'.html';
				}else{
					$retStr = $urlDomain . $systemArr['SYS_newsListFileName'] .'/list_'. $urlTypeStr . $pageStr .'.html';
				}
				break;

			case 'dyn-2.x':
				if ($urlPageID>1){ $pageStr='_'. $urlPageID; }else{ $pageStr=''; }
				$retStr = $urlDomain .'news/?list_'. $urlTypeStr . $pageStr .'.html';
				break;

		}
		return $retStr;
	}

	public static function ListID($urlHtmlName, $urlInfoTypeDir, $urlDataID, $urlPageID=0, $urlDomain=''){
		global $GB_WebHost,$systemArr;

		$retStr = '';
		$pageStr= '';
		if (strlen($urlDomain) == 0){ $urlDomain = $GB_WebHost; }
		switch ($systemArr['SYS_newsListUrlMode']){
			case 'html-2.x':
				if ($systemArr['SYS_diyInfoTypeDir'] == 1 && strlen($urlInfoTypeDir) >= 1){
					if ($urlPageID > 1){ $pageStr = 'index_'. $urlPageID .'.html'; }else{ $pageStr=''; }
					if (substr($urlInfoTypeDir,-1) != '/'){ $urlInfoTypeDir .= '/'; }
					$retStr = $urlDomain . $urlInfoTypeDir . $pageStr;
				}else{
					if ($urlHtmlName==''){ $urlHtmlName=$urlDataID; }
					if ($urlPageID>1){ $pageStr='_'. $urlPageID; }else{ $pageStr=''; }
					$retStr = $urlDomain . $systemArr['SYS_newsListFileName'] .'/list_'. $urlHtmlName . $pageStr .'.html';
				}
				break;

			case 'static-3.x':
				if ($urlHtmlName==''){ $urlHtmlName=$urlDataID; }
				if ($urlPageID>1){ $pageStr='_'. $urlPageID; }else{ $pageStr=''; }
				$retStr = $urlDomain . $systemArr['SYS_newsListFileName'] .'/list_'. $urlHtmlName . $pageStr .'.html';
				break;

			case 'dyn-2.x':
				if ($urlPageID>1){ $pageStr='_'. $urlPageID; }else{ $pageStr=''; }
				$retStr = $urlDomain .'news/?list_'. $urlDataID . $pageStr .'.html';
				break;

		}
		return $retStr;
	}

	public static function ListTypeID($urlListType, $urlDataID, $urlPageID=0, $urlDomain=''){
		global $GB_WebHost;

		$retStr = '';
		$pageStr= '';
		if (strlen($urlDomain) == 0){ $urlDomain = $GB_WebHost; }
		if ($urlPageID>1){ $pageStr='_'. $urlPageID; }else{ $pageStr=''; }
		$retStr = $urlDomain .'news/?list_'. $urlListType .'-'. $urlDataID . $pageStr .'.html';

		return $retStr;
	}

	public static function ListUrl($urlMode, $urlStr, $urlIsEnc=0, $urlId=0, $urlWebPathPart='', $pcUrl=''){
		if ($pcUrl == ''){ $pcUrl = $urlWebPathPart; }
		if ($urlMode == 'url' && $urlIsEnc){
			return $pcUrl .'url.php?m=infoType&id='. $urlId;
		}else{
			return str_replace('{%网站根路径%}', $urlWebPathPart, $urlStr);
		}
	}

	public static function NewsID($urlInfoTypeDir, $urlDatetimeDir, $urlDataID, $urlPageID=0, $urlDomain=''){
		global $GB_WebHost,$systemArr;

		$retStr = '';
		$pageStr= '';
		if (strlen($urlDomain) == 0){ $urlDomain = $GB_WebHost; }
		switch ($systemArr['SYS_newsShowUrlMode']){
			case 'html-2.x': case 'static-3.x':
				if ($urlPageID>1){ $pageStr='_'. $urlPageID; }else{ $pageStr=''; }
				if ($systemArr['SYS_htmlInfoTypeDir']==1 && strlen($urlInfoTypeDir)>=1){ $infoTypeHtmlDir=$urlInfoTypeDir; }else{ $infoTypeHtmlDir=''; }
				if ($systemArr['SYS_htmlDatetimeDir']>0 && strlen($urlDatetimeDir)>=1){ $datetimeHtmlDir=$urlDatetimeDir; }else{ $datetimeHtmlDir=''; }
				if ($systemArr['SYS_diyInfoTypeDir']==1){
					$retStr = $urlDomain . $infoTypeHtmlDir . $datetimeHtmlDir . $urlDataID . $pageStr .'.html';
				}else{
					$retStr = $urlDomain . $systemArr['SYS_newsShowFileName'] .'/'. $infoTypeHtmlDir . $datetimeHtmlDir . $urlDataID . $pageStr .'.html';
				}
				break;

			case 'dyn-2.x':
				if ($urlPageID>1){ $pageStr='_'. $urlPageID; }else{ $pageStr=''; }
				$retStr = $urlDomain .'news/?'. $urlDataID . $pageStr .'.html';
				break;

		}
		return $retStr;
	}

	public static function NewsUrl($urlStr, $urlIsEnc=0, $urlId=0 ,$urlWebPathPart=''){
		if ($urlIsEnc){
			return $urlWebPathPart .'url.php?m=info&id='. $urlId;
		}else{
			return $urlStr;
		}
	}

	public static function WebID($urlDataID, $urlPageID=0, $urlDomain=''){
		global $GB_WebHost,$systemArr;

		$retStr = '';
		$pageStr= '';
		if (strlen($urlDomain) == 0){ $urlDomain = $GB_WebHost; }
		switch ($systemArr['SYS_dynWebUrlMode']){
			case 'html-2.x': case 'static-3.x':
				if ($urlPageID>1){ $pageStr='_'. $urlPageID; }else{ $pageStr=''; }
				$retStr = $urlDomain . $systemArr['SYS_dynWebFileName'] .'/web_'. $urlDataID . $pageStr .'.html';
				break;

			case 'dyn-2.x':
				if ($urlPageID>1){ $pageStr='_'. $urlPageID; }else{ $pageStr=''; }
				$retStr = $urlDomain .'news/?web_'. $urlDataID . $pageStr .'.html';
				break;

		}
		return $retStr;
	}

	public static function WebUrl($urlStr, $urlIsEnc=0, $urlId=0 ,$urlWebPathPart=''){
		if ($urlIsEnc){
			return $urlWebPathPart .'url.php?m=infoWeb&id='. $urlId;
		}else{
			return $urlStr;
		}
	}


	public static function GoodsList($urlDataID, $urlPageID=0, $urlDomain=''){
		global $GB_WebHost;

		$retStr = '';
		$pageStr= '';
		if (strlen($urlDomain) == 0){ $urlDomain = $GB_WebHost; }
		if ($urlPageID>1){ $pageStr='_'. $urlPageID; }else{ $pageStr=''; }
		$retStr = $urlDomain .'goods/?list_'. $urlDataID . $pageStr .'.html';

		return $retStr;
	}

	public static function GoodsListStr($urlTypeStr, $urlPageID=0, $urlDomain=''){
		global $GB_WebHost;

		$retStr = '';
		$pageStr= '';
		if (strlen($urlDomain) == 0){ $urlDomain = $GB_WebHost; }
		if ($urlPageID>1){ $pageStr='_'. $urlPageID; }else{ $pageStr=''; }
		$retStr = $urlDomain .'goods/?list_'. $urlTypeStr . $pageStr .'.html';

		return $retStr;
	}

	public static function BbsListStr($urlTypeStr, $urlPageID=0, $urlDomain=''){
		global $GB_WebHost,$systemArr;

		$retStr = '';
		$pageStr= '';
		if (strlen($urlDomain) == 0){ $urlDomain = $GB_WebHost; }
		if ($urlPageID>1){ $pageStr='_'. $urlPageID; }else{ $pageStr=''; }
		$retStr = $urlDomain .'message/?list_'. $urlTypeStr . $pageStr .'.html';

		return $retStr;
	}


	public static function BbsList($urlDataID, $urlPageID=0, $urlDomain=''){
		global $GB_WebHost;

		$retStr = '';
		$pageStr= '';
		if (strlen($urlDomain) == 0){ $urlDomain = $GB_WebHost; }
		if ($urlPageID>1){ $pageStr='_'. $urlPageID; }else{ $pageStr=''; }
		if ($urlDataID == 0 && $urlPageID <= 1){
			$retStr = $urlDomain .'message/';
		}else{
			$retStr = $urlDomain .'message/?list_'. $urlDataID . $pageStr .'.html';
		}

		return $retStr;
	}


	public static function BbsID($urlDataID, $urlPageID=0, $urlDomain=''){
		global $GB_WebHost;

		$retStr = '';
		$pageStr= '';
		if (strlen($urlDomain) == 0){ $urlDomain = $GB_WebHost; }
		if ($urlPageID>1){ $pageStr='_'. $urlPageID; }else{ $pageStr=''; }
		$retStr = $urlDomain .'message/?'. $urlDataID . $pageStr .'.html';

		return $retStr;
	}


	public static function IdcProList($urlDataID, $urlPageID=0, $urlDomain=''){
		global $GB_WebHost;

		$retStr = '';
		$pageStr= '';
		if (strlen($urlDomain) == 0){ $urlDomain = $GB_WebHost; }
		if ($urlPageID>1){ $pageStr='&page='. $urlPageID; }else{ $pageStr=''; }
		$retStr = $urlDomain .'idcPro.php?dataID='. $urlDataID . $pageStr .'';

		return $retStr;
	}


	public static function GoodsList_pageSign($urlDataID, $urlPageID=0, $urlDomain=''){
		global $GB_JsHost,$systemArr;

		$retStr = '';
		$pageStr= '';
		if (strlen($urlDomain) == 0){ $urlDomain = $GB_JsHost; }
		if ($urlPageID>1){ $pageStr='_[page]'; }else{ $pageStr=''; }
		$retStr = $urlDomain .'goods/?list_'. $urlDataID . $pageStr .'.html';

		return $retStr;
	}

	public static function GoodsListStr_pageSign($urlTypeStr, $urlPageID=0, $urlDomain=''){
		global $GB_JsHost;

		$retStr = '';
		$pageStr= '';
		if (strlen($urlDomain) == 0){ $urlDomain = $GB_JsHost; }
		if ($urlPageID>1){ $pageStr='_[page]'; }else{ $pageStr=''; }
		$retStr = $urlDomain .'goods/?list_'. $urlTypeStr . $pageStr .'.html';

		return $retStr;
	}



	public static function BbsListStr_pageSign($urlTypeStr, $urlPageID=0, $urlDomain=''){
		global $GB_JsHost,$systemArr;

		$retStr = '';
		$pageStr= '';
		if (strlen($urlDomain) == 0){ $urlDomain = $GB_JsHost; }
		if ($urlPageID>1){ $pageStr='_[page]'; }else{ $pageStr=''; }
		$retStr = $urlDomain .'message/?list_'. $urlTypeStr . $pageStr .'.html';

		return $retStr;
	}

	public static function BbsList_pageSign($urlDataID, $urlPageID=0, $urlDomain=''){
		global $GB_JsHost,$systemArr;

		$retStr = '';
		$pageStr= '';
		if (strlen($urlDomain) == 0){ $urlDomain = $GB_JsHost; }
		if ($urlPageID>1){ $pageStr='_[page]'; }else{ $pageStr=''; }
		$retStr = $urlDomain .'message/?list_'. $urlDataID . $pageStr .'.html';
		return $retStr;
	}

	public static function BbsID_pageSign($urlDataID, $urlPageID=0, $urlDomain=''){
		global $GB_JsHost,$systemArr;

		$retStr = '';
		$pageStr= '';
		if (strlen($urlDomain) == 0){ $urlDomain = $GB_JsHost; }
		if ($urlPageID>1){ $pageStr='_[page]'; }else{ $pageStr=''; }
		$retStr = $urlDomain .'message/?'. $urlDataID . $pageStr .'.html';

		return $retStr;
	}


	public static function ListStr_pageSign($urlTypeStr, $urlPageID=0, $urlDomain=''){
		global $GB_JsHost,$systemArr;

		$retStr = '';
		$pageStr= '';
		if (strlen($urlDomain) == 0){ $urlDomain = $GB_JsHost; }
		switch ($systemArr['SYS_newsListUrlMode']){
			case 'html-2.x':	// news/list_announ.html 、news/list_announ_2.html
				if ($systemArr['SYS_diyInfoTypeDir']==1 && strpos('|announ|new|','|'. $urlTypeStr .'|') !== false){
					if ($urlPageID>1){ $pageStr='index_[page].html'; }else{ $pageStr=''; }
					$retStr = $urlDomain . $urlTypeStr .'/'. $pageStr;
				}else{
					if ($urlPageID>1){ $pageStr='_[page]'; }else{ $pageStr=''; }
					if (strpos($urlTypeStr,'-') !== false){
						$retStr = $urlDomain .'news/?list_'. $urlTypeStr . $pageStr .'.html';
					}else{
						$retStr = $urlDomain . $systemArr['SYS_newsListFileName'] .'/list_'. $urlTypeStr . $pageStr .'.html';
					}
				}
				break;

			case 'static-3.x':	// news/list_announ.html 、news/list_announ_2.html
				if ($urlPageID>1){ $pageStr='_[page]'; }else{ $pageStr=''; }
				if (strpos($urlTypeStr,'-') !== false){
					$retStr = $urlDomain .'news/?list_'. $urlTypeStr . $pageStr .'.html';
				}else{
					$retStr = $urlDomain . $systemArr['SYS_newsListFileName'] .'/list_'. $urlTypeStr . $pageStr .'.html';
				}
				break;

			case 'dyn-2.x':	// news/?list_announ.html 、news/?list_announ_2.html
				if ($urlPageID>1){ $pageStr='_[page]'; }else{ $pageStr=''; }
				$retStr = $urlDomain .'news/?list_'. $urlTypeStr . $pageStr .'.html';
				break;

		}
		return $retStr;
	}

	public static function ListID_pageSign($urlHtmlName, $urlInfoTypeDir, $urlDataID, $urlPageID=0, $urlDomain=''){
		global $GB_JsHost,$systemArr;

		$retStr = '';
		$pageStr= '';
		if (strlen($urlDomain) == 0){ $urlDomain = $GB_JsHost; }
		switch ($systemArr['SYS_newsListUrlMode']){
			case 'html-2.x':
				if ($systemArr['SYS_diyInfoTypeDir'] == 1 && strlen($urlInfoTypeDir) >= 1){
					if ($urlPageID>1){ $pageStr='index_[page].html'; }else{ $pageStr=''; }
					if (substr($urlInfoTypeDir,-1) != '/'){ $urlInfoTypeDir .= '/'; }
					$retStr = $urlDomain . $urlInfoTypeDir . $pageStr;
				}else{
					if ($urlHtmlName==''){ $urlHtmlName=$urlDataID; }
					if ($urlPageID>1){ $pageStr='_[page]'; }else{ $pageStr=''; }
					$retStr = $urlDomain . $systemArr['SYS_newsListFileName'] .'/list_'. $urlHtmlName . $pageStr .'.html';
				}
				break;

			case 'static-3.x':
				if ($urlHtmlName==''){ $urlHtmlName=$urlDataID; }
				if ($urlPageID>1){ $pageStr='_[page]'; }else{ $pageStr=''; }
				$retStr = $urlDomain . $systemArr['SYS_newsListFileName'] .'/list_'. $urlHtmlName . $pageStr .'.html';
				break;

			case 'dyn-2.x':
				if ($urlPageID>1){ $pageStr='_[page]'; }else{ $pageStr=''; }
				$retStr = $urlDomain .'news/?list_'. $urlDataID . $pageStr .'.html';
				break;

		}
		return $retStr;
	}

	public static function NewsID_pageSign($urlInfoTypeDir, $urlDatetimeDir, $urlDataID, $urlPageID=0, $urlDomain=''){
		global $GB_JsHost,$systemArr;

		$retStr = '';
		$pageStr= '';
		if (strlen($urlDomain) == 0){ $urlDomain = $GB_JsHost; }
		switch ($systemArr['SYS_newsShowUrlMode']){
			case 'html-2.x': case 'static-3.x':
				if ($urlPageID>1){ $pageStr='_[page]'; }else{ $pageStr=''; }
				if ($systemArr['SYS_htmlInfoTypeDir']==1 && strlen($urlInfoTypeDir)>=1){ $infoTypeHtmlDir=$urlInfoTypeDir; }else{ $infoTypeHtmlDir=''; }
				if ($systemArr['SYS_htmlDatetimeDir']>0 && strlen($urlDatetimeDir)>=1){ $datetimeHtmlDir=$urlDatetimeDir; }else{ $datetimeHtmlDir=''; }
				if ($systemArr['SYS_diyInfoTypeDir']==1){
					$retStr = $urlDomain . $infoTypeHtmlDir . $datetimeHtmlDir . $urlDataID . $pageStr .'.html';
				}else{
					$retStr = $urlDomain . $systemArr['SYS_newsShowFileName'] .'/'. $infoTypeHtmlDir . $datetimeHtmlDir . $urlDataID . $pageStr .'.html';
				}
				break;

			case 'dyn-2.x':
				if ($urlPageID>1){ $pageStr='_[page]'; }else{ $pageStr=''; }
				$retStr = $urlDomain .'news/?'. $urlDataID . $pageStr .'.html';
				break;

		}
		return $retStr;
	}

}

?>
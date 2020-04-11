<?php

if(! defined('OT_ROOT')) {
	exit('Access Denied');
}


class Content{

	// 采用分页符分页显示文章内容
	// contentStr文章内容；infoTypeDir文章分栏目目录名；datetimeDir文章分时间目录名；infoId文章ID；
	public static function PageSign($contentStr, $infoTypeDir, $datetimeDir, $infoId, $page=0, $webPathPart=''){
		global $tpl,$infoSysArr;

		$retStr = '';
		if (strpos($contentStr,'[OT_page]') === false){
			$retStr = $contentStr;
		}else{
			if ($webPathPart == '' && isset($tpl)){ $webPathPart = $tpl->webPathPart; }
			$contentArr=explode('[OT_page]',$contentStr);

			$pages=count($contentArr);

			if ($page > 0){
				$currPage = $page;
			}else{
				if (isset($tpl)){
					$currPage = $tpl->page;
				}else{
					$currPage = 0;
				}
			}
			if ($currPage==0){ $currPage = OT::GetInt('page'); }
			if ($currPage<1){ $currPage=1; }
			if ($currPage>$pages){ $currPage=$pages; }

			$retStr = $contentArr[$currPage-1];

			$pStr = '';
			if ($currPage>1){
				$pStr .= '<div><a '. self::Href($infoId, $currPage-1, $infoTypeDir, $datetimeDir, $webPathPart) .'>上一页</a></div>';
			}else{
				$pStr .= '<div><span>上一页</span></div>';
			}

			if ($pages <= 9){
				$startpage = 1;
				$endpage = $pages;
			}elseif ($currPage-4 >= 1 && $currPage+4 <= $pages){
				$startpage = $currPage-4;
				$endpage = $currPage+4;
			}elseif ($currPage-4 < 1){
				$startpage = 1;
				$endpage = 9;
			}elseif ($currPage+4 > $pages){
				$startpage = $pages-8;
				$endpage = $pages;
			}

			if ($startpage != 1){
				$pStr .= '<div><a '. self::Href($infoId, 1, $infoTypeDir, $datetimeDir, $webPathPart) .'>1...</a></div>';
			}
			for ($i=$startpage; $i<=$endpage; $i++){
				if ($i == $currPage){
					$pStr .= '<div><span class="sel">'. $i .'</span></div>';
				}else{
					$pStr .= '<div><a '. self::Href($infoId, $i, $infoTypeDir, $datetimeDir, $webPathPart) .'>'. $i .'</a></div>';
				}
			}
			if ($endpage != $pages){
				$pStr .= '<div><a '. self::Href($infoId, $pages, $infoTypeDir, $datetimeDir, $webPathPart) .'>...'. $pages .'</a></div>';
			}

			if ($currPage < $pages){
				$pStr .= '<div><a '. self::Href($infoId, $currPage+1, $infoTypeDir, $datetimeDir, $webPathPart) .'>下一页</a></div>';
			}else{
				$pStr .= '<div><span>下一页</span></div>';
			}
			$retStr .= self::PageBox($pStr);
		}
		return $retStr;
	}



	// 采用限制每页字数方式显示文章内容
	// contentStr文章内容；infoTypeDir文章分栏目目录名；datetimeDir文章分时间目录名；infoId文章ID；pageWord每页字数
	public static function PageNum($contentStr, $infoTypeDir, $datetimeDir, $infoId, $pageWord, $page=0, $webPathPart=''){
		global $tpl;

		$contentLen=mb_strlen($contentStr,OT_Charset);

		$retStr = '';
		if ($contentLen <= $pageWord || $pageWord==0){
			$retStr = $contentStr;
		}else{
			if ($webPathPart == '' && isset($tpl)){ $webPathPart = $tpl->webPathPart; }
			if ($page > 0){
				$currPage = $page;
			}else{
				if (isset($tpl)){
					$currPage = $tpl->page;
				}else{
					$currPage = 0;
				}
			}
			if ($currPage==0){ $currPage = OT::GetInt('page'); }

			$pages=ceil($contentLen/$pageWord);
			$lngBound=$contentLen;          // 最大误差范围
			if ($currPage<1){ $currPage=1; }
			if ($currPage>$pages){ $currPage=$pages; }

			$contentStr = str_ireplace(array('</table>','</div>','</p>','<br>','<br />'), array('</table>[ptag]','</div>[ptag]','</p>[ptag]','<br>[ptag]','<br />[ptag]'), $contentStr);
			if (substr($contentStr,-6) == '[ptag]'){ $contentStr = substr($contentStr,0,-6); }
			$contArr = explode('[ptag]',$contentStr);
			$contCount = count($contArr);
			$calcContent = '';
			$calcNum = 0;
			$calcPage = 0;
			for ($i=0; $i<$contCount; $i++){
				$currNum = mb_strlen($contArr[$i],OT_Charset);	// Str::RegExp($contArr[$i],'html')
				$calcContent .= $contArr[$i];
				$calcNum += $currNum;
				if ($calcNum >= $pageWord || $i+1 == $contCount){
					$calcPage ++;
					if ($calcPage == $currPage){
						$retStr .= $calcContent;
						break;
					}else{
						$calcContent = '';
						$calcNum = 0;
					}
				}
			}

			$pStr = '';
			if ($currPage>1){
				$pStr .= '<div><a '. self::Href($infoId, $currPage-1, $infoTypeDir, $datetimeDir, $webPathPart) .'>上一页</a></div>';
			}else{
				$pStr .= '<div><span>上一页</span></div>';
			}
			
			if ($pages <= 9){
				$startpage = 1;
				$endpage = $pages;
			}elseif ($currPage-4 >= 1 && $currPage+4 <= $pages){
				$startpage = $currPage-4;
				$endpage = $currPage+4;
			}elseif ($currPage-4 < 1){
				$startpage = 1;
				$endpage = 9;
			}elseif ($currPage+4 > $pages){
				$startpage = $pages-8;
				$endpage = $pages;
			}
			
			if ($startpage != 1){
				$pStr .= '<div><a '. self::Href($infoId, 1, $infoTypeDir, $datetimeDir, $webPathPart) .'>1...</a></div>';
			}
			for ($i=$startpage; $i<=$endpage; $i++){
				if ($i == $currPage){
					$pStr .= '<div><span class="sel">'. $i .'</span></div>';
				}else{
					$pStr .= '<div><a '. self::Href($infoId, $i, $infoTypeDir, $datetimeDir, $webPathPart) .'>'. $i .'</a></div>';
				}
			}
			if ($endpage != $pages){
				$pStr .= '<div><a '. self::Href($infoId, $pages, $infoTypeDir, $datetimeDir, $webPathPart) .'>...'. $pages .'</a></div>';
			}
			
			if ($currPage < $pages){
				$pStr .= '<div><a '. self::Href($infoId, $currPage+1, $infoTypeDir, $datetimeDir, $webPathPart) .'>下一页</a></div>';
			}else{
				$pStr .= '<div><span>下一页</span></div>';
			}
			$retStr .= self::PageBox($pStr);
		}
		return $retStr;
	}

	// 分页链接
	// PH_mode：AJAX的ID值；PH_infoId：文章ID；PH_pageNum：页码；PH_infoTypeDir：分栏目目录名；PH_datetimeDir：分时间目录名
	public static function Href($PH_infoId, $PH_pageNum,$PH_infoTypeDir,$PH_datetimeDir,$PH_webPathPart=''){
		global $tpl,$infoSysArr;

		if ($infoSysArr['IS_isNoCollPage']==0){
			return 'href="'. Url::NewsID($PH_infoTypeDir, $PH_datetimeDir, $PH_infoId, $PH_pageNum, $PH_webPathPart) .'"';
		}else{
			return 'href="javascript:ContentPageHref(\'\','. $PH_infoId .','. ($PH_pageNum+$PH_infoId) .'-'. $PH_infoId .',\''. Url::NewsID_pageSign($PH_infoTypeDir, $PH_datetimeDir, $PH_infoId, $PH_pageNum, $PH_webPathPart) .'\');" rel="nofollow"';
		}
	}

	// 内容自动补齐闭合HTML标签
	public static function CloseTags($html) {
		$skipStartArr = array('br','input','img','hr','p','meta','link');	// 跳过开始标签
		$skinEndArr = array('p','del','i','u');	// 跳过结束标签
		$html = preg_replace('/<[^>]*$/','',$html);

		preg_match_all('#<([a-z0-9]+)(?: .*)?>#iU', $html, $result);	// #<([a-z0-9]+)(?: .*)?(?<![/|/ ])>#iU  [^/>]*?
		$startTagArr = $result[1];
		$startTagStr = implode('||',$startTagArr);
		$startTagTemp = '|'. strtolower($startTagStr) .'|';
		// echo($startTagTemp . $html);
		foreach ($skipStartArr as $val){
			if (strpos($startTagTemp,'|'. $val .'|') !== false){ $startTagTemp = str_replace('|'. $val .'|', '', $startTagTemp); }
		}
		$startTagArr = array_filter(explode('||',substr($startTagTemp,1,-1)));
		$startTagCount = count($startTagArr);
		// print_r($startTagArr);echo($startTagCount);

		preg_match_all('#</([a-z0-9]+)>#iU', $html, $result);
		$endTagArr = $result[1];
		$endTagStr = implode('||',$endTagArr);
		$endTagTemp = '|'. strtolower($endTagStr) .'|';
		foreach ($skinEndArr as $val){
			if (strpos($endTagTemp,'|'. $val .'|') !== false){ $endTagTemp = str_replace('|'. $val .'|', '', $endTagTemp); }
		}
		$endTagArr = array_filter(explode('||',substr($endTagTemp,1,-1)));
		$endTagCount = count($endTagArr);
		// print_r($endTagArr);echo($endTagCount);die($html);

		if ($startTagCount < $endTagCount){
			// 开始标签比结束标签少，补开始标签
			$headStr = "";
			$startTagArr = array_reverse($startTagArr);
			for ($i=0; $i<$endTagCount; $i++) {
				if (! in_array($endTagArr[$i], $startTagArr)){
					$headStr = '<'. $endTagArr[$i] .'>'. $headStr;
				}else{
					unset($startTagArr[array_search($endTagArr[$i], $startTagArr)]);
				}
			}
			$html = $headStr . $html;
		}elseif ($startTagCount > $endTagCount){
			// 开始标签比结束标签多，补结束标签
			$startTagArr = array_reverse($startTagArr);
			for ($i=0; $i<$startTagCount; $i++) {
				if (! in_array($startTagArr[$i], $endTagArr)){
					$html .= '</'. $startTagArr[$i] .'>';
				}else{
					unset($endTagArr[array_search($startTagArr[$i], $endTagArr)]);
				}
			}
		}
		return $html;
	}


	// 计算分页数
	// contentStr文章内容；pageWord每页字数
	public static function CalcPageNum($contentStr, $pageWord){
		$pageNum = 1;
		if (strpos($contentStr,'[OT_page]') !== false){
			$contentArr=explode('[OT_page]',$contentStr);
			$pageNum=count($contentArr);

		}elseif ($pageWord > 1){
			$contentLen=mb_strlen($contentStr,OT_Charset);

			if ($contentLen <= $pageWord){
				$pageNum = 1;
			}else{
				$pageNum = ceil($contentLen/$pageWord);
			}
		}
		return $pageNum;
	}

	public static function PageBox($str){
		return '
			<div class="clr"></div>
			<div style="margin:0 auto; width:700px; overflow:hidden; text-align:center;" class="caClass">
				<div class="ca22Style"><script type="text/javascript">OTca("ot022");</script></div>
			</div>
			<div class="clr"></div>
			<table align="center" cellpadding="0" cellspacing="0" class="pageNavBox list"><tr><td>'. $str .'</td></tr></table>
			<div class="clr"></div>
			';
	}

}

?>
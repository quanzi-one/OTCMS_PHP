<?php

if(! defined('OT_ROOT')) {
	exit('Access Denied');
}


class Str{

	// 按字符数截取字符串
	public static function LimitChar($str, $len, $sign='...'){
		if ($len > 0){
			if (function_exists('mb_substr')) {
				return mb_substr($str,0,$len,OT_Charset) . (mb_strlen($str,OT_Charset) <= $len ? '' : $sign);
			}else{
				return $str;
			}
		}else{
			return $str;
		}
	}

	// 按字节截取字符串（暂时没用到）
	public static function LimitB($str, $len, $sign='...'){
		if (OT_Charset!='utf-8'){$charB=2;}else{$charB=3;}

		$oldStr=$str;
		for($i=0;$i<$len;$i++){
			$temp_str=substr($str,0,1);
			if(ord($temp_str) > 127){
				$i++;
				if($i<$len){
					$new_str[]=substr($str,0,$charB);
					$str=substr($str,$charB);
				}
			}else{
				$new_str[]=substr($str,0,1);
				$str=substr($str,1);
			}
		}
		$newStr=join($new_str);

		if (strlen($newStr)<strlen($oldStr)){
			return $newStr . $sign;
		}else{
			return $oldStr;
		}
	}

	// 限定数字的长度，不足用$char填充
	public static function FixLen($str, $len, $char='0'){
		if (strlen($str) >= $len){
			return $str;
		}else{
			return str_repeat($char,$len-strlen($str)) . $str;
		}
	}


	// 将字符串进行处理，中间用星号表示
	public static function PartHide($user_name){
		// 获取字符串长度
		$strlen = mb_strlen($user_name, 'utf-8');
		// 如果字符串长度大于4
		if ($strlen > 15){
			$firstStr = mb_substr($user_name, 0, 5, 'utf-8');
			$lastStr = mb_substr($user_name, -5, 5, 'utf-8');
			return $firstStr . str_repeat("*", $strlen - 10) . $lastStr;
		}elseif ($strlen > 9){
			$firstStr = mb_substr($user_name, 0, 3, 'utf-8');
			$lastStr = mb_substr($user_name, -3, 3, 'utf-8');
			return $firstStr . str_repeat("*", $strlen - 6) . $lastStr;
		}elseif ($strlen > 4){
			$firstStr = mb_substr($user_name, 0, 2, 'utf-8');
			$lastStr = mb_substr($user_name, -2, 2, 'utf-8');
			return $firstStr . str_repeat("*", $strlen - 4) . $lastStr;
		}elseif ($strlen > 2){
			// mb_substr — 获取字符串的部分
			$firstStr = mb_substr($user_name, 0, 1, 'utf-8');
			$lastStr = mb_substr($user_name, -1, 1, 'utf-8');
			// str_repeat — 重复一个字符串
			return $firstStr . str_repeat("*", $strlen - 2) . $lastStr;
		}else{
			return $user_name;
		}
	}


	// 对字符串执行指定次数替换
	/*
	 * @param  Mixed $search   查找目标值 
	 * @param  Mixed $replace  替换值 
	 * @param  Mixed $subject  执行替换的字符串／数组 
	 * @param  Int   $limit    允许替换的次数，默认为-1，不限次数 
	 * @return Mixed 
	 */  
	public static function ReplaceLimit($search, $replace, $subject, $limit=-1){
		if(is_array($search)){
			foreach($search as $k=>$v){
				$search[$k] = '`'. preg_quote($search[$k], '`'). '`';
			}
		}else{
			$search = '`'. preg_quote($search, '`'). '`';
		}
		return preg_replace($search, $replace, $subject, $limit);
	}


	// 截取字符串
	// contentStr：要截取的字符串；startCode：开始字符串；endCode：结束字符串；incStart：是否包含startCode；incEnd：是否包含endCode
	public static function GetMark($contentStr,$startCode,$endCode,$incStart=false,$incEnd=false,$mode='',$isLower=false){
		if (strlen($contentStr)==0 || strlen($startCode)==0 || strlen($endCode)==0){
			return '';
		}
		$Start	= -1;
		$Over	= -1;
		if ($isLower){
			$contentTemp	= strtolower($contentStr);
			$startCode		= strtolower($startCode);
			$endCode		= strtolower($endCode);
		}else{
			$contentTemp	= $contentStr;
		}
		$Start	= strpos($contentTemp, $startCode);
		if ($Start === false){
			if ($mode == 'det'){
				return '开始标签没定位到内容';
			}else{
				return '';
			}
		}else{
			$Start += strlen($startCode);
		}
		$Over = strpos(substr($contentTemp,$Start),$endCode);
		if ($Over === false){
			if ($mode == 'det'){
				return '结尾标签没定位到内容('. substr($contentTemp,$Start) .')['. $endCode .']';
			}else{
				return '';
			}
		}
		$Over += $Start;
		if ($Over <= 0 || $Over <= $Start){
			if ($mode == 'det'){
				return '开始标签位置'. $Start .'，结尾标签位置'. $Over .'';
			}else{
				return '';
			}
		}

		$retStr = substr($contentStr, $Start, $Over-$Start);
		if ($incStart){ $retStr = $startCode . $retStr; }
		if ($incEnd){ $retStr .= $endCode; }
		return $retStr;
	}


	// 截取字符串
	// contentStr：要截取的字符串；startCode：开始字符串；endCode：结束字符串；incStart：是否包含startCode；incEnd：是否包含endCode
	public static function GetMarkEnd($contentStr,$startCode,$endCode,$incStart=false,$incEnd=false,$mode='',$isLower=false){
		if (strlen($contentStr)==0 || strlen($startCode)==0 || strlen($endCode)==0){
			return '';
		}
		$Start	= -1;
		$Over	= -1;
		if ($isLower){
			$contentTemp	= strtolower($contentStr);
			$startCode		= strtolower($startCode);
			$endCode		= strtolower($endCode);
		}else{
			$contentTemp	= $contentStr;
		}
		$Start	= strpos($contentTemp, $startCode);
		if ($Start === false){
			if ($mode == 'det'){
				return '开始标签没定位到内容';
			}else{
				return '';
			}
		}else{
			$Start += strlen($startCode);
		}
		$Over = strrpos(substr($contentTemp,$Start),$endCode);
		if ($Over === false){
			if ($mode == 'det'){
				return '结尾标签没定位到内容('. substr($contentTemp,$Start) .')['. $endCode .']';
			}else{
				return '';
			}
		}
		$Over += $Start;
		if ($Over <= 0 || $Over <= $Start){
			if ($mode == 'det'){
				return '开始标签位置'. $Start .'，结尾标签位置'. $Over .'';
			}else{
				return '';
			}
		}

		$retStr = substr($contentStr, $Start, $Over-$Start);
		if ($incStart){ $retStr = $startCode . $retStr; }
		if ($incEnd){ $retStr .= $endCode; }
		return $retStr;
	}


	// 过滤特殊符号
	public static function FilterSpecSign($str){
		return preg_replace("/(". chr(8) ."|". chr(9) ."|". chr(10) ."|". chr(13) .")/i","",$str);
	}


	// 过滤字符串
	public static function Filter($str,$Fnum){
		switch ($Fnum){
			Case 'xml':
				$newStr = strtr($str,array('<![CDATA['=>'', ']]>'=>''));
				break;

			case 'sql':
				$newStr = strtr($str,array(
				' '=>'', ','=>'', '.'=>'', ':'=>'', ';'=>'', "'"=>'', '"'=>'', '`'=>'', '~'=>'', '?'=>'', '!'=>'', '@'=>'', '#'=>'', "\$"=>'', '%'=>'', '^'=>'', '&'=>'', '*'=>'', '<'=>'', '>'=>'', '('=>'', ')'=>'', '+'=>'', '-'=>'', '/'=>'', '='=>'', "\\"=>'', '{'=>'', '}'=>'', '0xbf27'=>''
				));
				break;

			case 'sql2':
				$newStr = strtr($str,array(
				"'"=>'', '"'=>'', '`'=>'', '~'=>'', '?'=>'', '!'=>'', '@'=>'', '#'=>'', "\$"=>'', '%'=>'', '^'=>'', '&'=>'', '*'=>'', '('=>'', ')'=>'', "\\"=>'', '{'=>'', '}'=>'', '0xbf27'=>''
				));
				break;

			case 'typeStr':
				$newStr = strtr($str,array(
				' '=>'', '.'=>'', ':'=>'', ';'=>'', "'"=>'', '"'=>'', '`'=>'', '~'=>'', '?'=>'', '!'=>'', '@'=>'', '#'=>'', "\$"=>'', '%'=>'', '^'=>'', '&'=>'', '*'=>'', '<'=>'', '>'=>'', '('=>'', ')'=>'', '+'=>'', '-'=>'', "/"=>'', '='=>'', "\\"=>'', '{'=>'', '}'=>'', '0xbf27'=>''
				));
				break;

			case 'upImgStr':
				$newStr = strtr($str,array(
				','=>'', ':'=>'', ';'=>'', "'"=>'', '"'=>'', '`'=>'', '~'=>'', '?'=>'', '!'=>'', '@'=>'', '#'=>'', "\$"=>'', '%'=>'', '^'=>'', '&'=>'', '*'=>'', '<'=>'', '>'=>'', '('=>'', ')'=>'', '+'=>'', '-'=>'', '='=>'', "\\"=>'', '{'=>'', '}'=>'', '0xbf27'=>''
				));
				break;

			case 'openid':
				$newStr = strtr($str,array(
				' '=>'', ','=>'', '.'=>'', ':'=>'', ';'=>'', "'"=>'', '"'=>'', '`'=>'', '~'=>'', '?'=>'', '!'=>'', '@'=>'', '#'=>'', "\$"=>'', '%'=>'', '^'=>'', '&'=>'', '*'=>'', '<'=>'', '>'=>'', '('=>'', ')'=>'', '+'=>'', '/'=>'', '='=>'', "\\"=>'', '{'=>'', '}'=>'', '0xbf27'=>''
				));
				break;

			case 'url':
				$newStr = strtr($str,array(
				';'=>'', "'"=>'', '"'=>'', '`'=>'', '~'=>'', '!'=>'', '@'=>'', "\$"=>'', '^'=>'', '*'=>'', '<'=>'', '>'=>'', '('=>'', ')'=>'', '{'=>'', '}'=>'', '0xbf27'=>''
				));
				break;

			case 'eol':
				$newStr = str_replace(array("\r\n", "\r", "\n"), '', $str);
				break;

			case 'fileName':
				$newStr = strtr($str,array(
				','=>'', ':'=>'', ';'=>'', "'"=>'', '"'=>'', '`'=>'', '~'=>'', '?'=>'', '!'=>'', '@'=>'', '#'=>'', "\$"=>'', '%'=>'', '^'=>'', '&'=>'', '*'=>'', '<'=>'', '>'=>'', '('=>'', ')'=>'', '+'=>'', "/"=>'', '='=>'', "\\"=>'', '{'=>'', '}'=>'', '0xbf27'=>''
				));
				break;

			default:
				return 'no para';
				break;

		}
		
		return $newStr;
	}

	//过滤字符串（正则表达式）
	public static function RegExp($str,$Fnum){
		switch ($Fnum){
			case 'sql':
				/*
				$pattern = "/[^\w\x{4e00}-\x{9fa5}]/u";		// 过滤掉所有符号（保留数字、字母、下划线_、汉字）
				return preg_replace($pattern,"",$str);
				*/
				$str = self::Filter($str,'sql');
				return $str;
				break;

			case 'sql2':
				$str = self::Filter($str,'sql2');
				return $str;
				break;

			case 'abcnum':
				$pattern = "/[^a-zA-Z0-9]/i";
				return preg_replace($pattern,'',$str);
				break;

			case 'abcnum,':
				$pattern = "/[^a-zA-Z0-9,]/i";
				return preg_replace($pattern,'',$str);
				break;

			case 'abcnum_':
				$pattern = "/[^a-zA-Z0-9_]/i";
				return preg_replace($pattern,'',$str);
				break;

			case 'abcnum_.':
				$pattern = "/[^a-zA-Z0-9_\.]/i";
				return preg_replace($pattern,'',$str);
				break;

			case 'abcnum+url':
				$pattern = "/[^a-zA-Z0-9_\.\:\-\/]/i";
				return preg_replace($pattern,'',$str);
				break;

			case 'num':
				$pattern = "/[^0-9]/i";				// 只保留数字
				return preg_replace($pattern,'',$str);
				break;

			case 'sql+|':
				$pattern = "/[^\w\|\x{4e00}-\x{9fa5}]/u";
				return preg_replace($pattern,'',$str);
				break;

			case 'sql+,':
				$pattern = "/[^\w\,\x{4e00}-\x{9fa5}]/u";
				return preg_replace($pattern,'',$str);
				break;

			case 'sql+.':
				$pattern = "/[^\w\.\x{4e00}-\x{9fa5}]/u";
				return preg_replace($pattern,'',$str);
				break;

			case 'sql+ ':
				$pattern = "/[^\w \x{4e00}-\x{9fa5}]/u";
				return preg_replace($pattern,'',$str);
				break;

			case 'sql+[]':
				$pattern = "/[^\w\[\]\x{4e00}-\x{9fa5}]/u";
				return preg_replace($pattern,'',$str);
				break;

			case 'sql+-':
				$pattern = "/[^\w\-\x{4e00}-\x{9fa5}]/u";
				return preg_replace($pattern,'',$str);
				break;

			case 'sql+mail':
				$pattern = "/[^\w\.@-\x{4e00}-\x{9fa5}]/u";
				return preg_replace($pattern,'',$str);
				break;

			case 'html':
				$pattern = "/<[^>]*>/i";			// 过滤掉HTML标识
				return preg_replace($pattern,'',$str);
				break;

			case 'br':
				$pattern = "/(\t|\r|\n)/i";
				return preg_replace($pattern,'',$str);
				break;

			case 'contentKey':
				$pattern = "/(\t|\r|\n|\&nbsp;|\&ensp;)/i";
				return preg_replace($pattern,'',$str);
				break;

			case 'fileName':
				$pattern = "/(\\\\|\\/|\\:|\\*|\\?|\\\"|<|>|\|)/i";
				return preg_replace($pattern,'',$str);
				break;

			case 'filterUrl':
				$pattern = "#((https?|ftp|news)://)?([\w\-_]+(\.[\w\-_]+)*(\.(com|gov|net|org|edu|int|mil|cn|com\.cn|net\.cn|gov\.cn|org\.cn|biz|CC|TV|info|name|mobi|travel|museum|coop|aero))+)/?(?!/)([-a-z\d+&@\#%=~_|!:,.;]+/)*(?![-a-z\d+&@\#%=~_!,.;].*?\.(?:jpg|jpeg|gif|png|bmp))[-a-z\d+&@\#%=~_!,.;]*(?:\?[a-z\d+&@\#/%=~_|!:,.;]*)?#i";
				return preg_replace($pattern,'',$str);
				break;

			default:
				return 'no para';
				break;
		}
		
	}

	// 正则表达式 替换
	public static function RegExpFilter($str, $rePattern, $reStr){

		switch ($rePattern){
			case 'goodsId':
				$rePattern	= "#[^a-zA-Z0-9]#i";
				$reStr		= '';
				break;

			case 'goodsIdNum':
				$rePattern	= "#[^0-9]#i";
				$reStr		= '';
				break;

			case 'getGoodsId':
				$rePattern	= "#[\s\S]*?id=([0-9]*)([\s\S]*)#i";
				$reStr		= '$1';
				break;

			case 'replacePid':
				$rePattern	= "#([\s\S]*)(mm_[0-9]*_[0-9]*_[0-9]*)([\s\S]*)#i";
				$reStr		= '$1'. $reStr .'$3';
				break;

			case 'replaceUserId':
				$rePattern	= "#([\s\S]*)(etg\.[0-9]*_)[0-9]*(_[0-9]*_[0-9]*)([\s\S]*)#i";
				$reStr		= '$1$2'. $reStr .'$3$4';
				break;

			default:
				return 'no para';
				break;
		}

		return preg_replace($rePattern,$reStr,$str);
	}


	// 替换字符串
	public static function MoreReplace($str,$Fnum){
		$newStr = '';
		switch ($Fnum){		//fiStr:过滤字符串；reStr:顶替字符串，用“∥”间隔
			case '|':
				$newStr = strtr($str,array(
				'|'=>'｜', "'"=>'＇', '"'=>'＂'
				));
				break;

			case 'js':
				$newStr = strtr($str,array(
				"\\"=>"\\\\", "/"=>"\/", "'"=>"\'", "\""=>"\\\"", "\n"=>"\\n", "\r"=>""
				));
				break;

			case 'regexp':
				$newStr = strtr($str,array(
				'*'=>'\*', '.'=>'\.', '?'=>'\?', '+'=>'\+', '$'=>'\$', '^'=>'\^', '['=>'\[', ']'=>'\]', '('=>'\(', ')'=>'\)', '{'=>'\{', '}'=>'\}', '|'=>'\|', '\\'=>'\\\\', '/'=>'\/'
				));
				break;

			case 'html':
				$newStr = strtr($str,array(
				" "=>"&ensp;", "<"=>"&lt;", ">"=>"&gt;", "\""=>"&quot;", "\n"=>"<br />", "\r"=>""
				));
				break;

			case 'deHtml':
				$newStr = strtr($str,array(
				'&amp;'=>'&', '&nbsp;'=>' ', '&ensp;'=>' ', '&lt;'=>'<', '&gt;'=>'>', '&quot;'=>'"', '<br>'=>"\n", '<br />'=>"\n"
				));
				break;

			case 'html2':
				$newStr = strtr($str,array(
				' '=>'　', '<'=>'＜', '>'=>'＞', "\""=>'＂', "\n"=>'<br />', "\r"=>''
				));
				break;

			case 'filthtml':
				$newStr = strtr($str,array(
				'&nbsp;'=>' ', '&ensp;'=>' ', '<br>'=>"\n", '<br />'=>"\n", '<br/>'=>"\n", '<p>'=>"\n", '</p>'=>''
				));
				break;

			case 'defilthtml':
				$newStr = strtr($str,array(
				' '=>'&ensp;', "\n"=>'<br>', "\n"=>'<p>'
				));
				break;

			case 'input':
				$newStr = strtr($str,array(
				"'"=>'&#39;', '"'=>'&#34;', '<'=>'&lt;', '>'=>'&gt;', "\r"=>'', "\n"=>''
				));
				break;

			case 'textarea':
				$newStr = strtr($str,array(
				'&'=>'&#38;', "'"=>'&#39;', '"'=>'&#34;', '<'=>'&lt;', '>'=>'&gt;', ' '=>'&#32;', "\r"=>'', "\n"=>'&#10;'
				));	//  PHP_EOL=>'&#13;&#10;'
				break;

			case 'themeKey':
				$newStr = strtr($str,array(
				' '=>',', '，'=>',', '|'=>',', '、'=>',', '"'=>'', '\''=>'', '{'=>'', '}'=>''
				));
				break;

			case 'contentKey':
				$newStr = strtr($str,array(
				'<'=>'&lt;', '>'=>'&gt;', "\""=>'&quot;', "\r"=>'', "\n"=>''
				));
				break;

			case 'optionEncode':
				$newStr = strtr($str,array(
				'&lt;option '=>'<option ', "'&gt;"=>"'>", '&lt;/option&gt;'=>'</option>', '&lt;optgroup '=>'<optgroup ', '&lt;/optgroup&gt;'=>'</optgroup>'
				));
				break;

			default:
				$newStr = 'no para';
				break;

		}

		return $newStr;
	}


	// 替换字符串(自动过滤超链接和图片标签)
	public static function ReplaceSkipMark($strTemp, $sourceStr, $replaceStr, $replaceNum){
		preg_match_all("/(\<a[^<>]+\>.+?\<\/a\>)|(\<img[^<>]+\>)/is", $strTemp, $img_array);
		$img_array = array_unique($img_array[0]);	// 去掉重复

		$tempArr = array();
		$tempNum = 0;
		foreach ($img_array as $key => $value) {
			$tempArr[$tempNum] = $value;
			$strTemp=str_replace($value,'<OTre'. $tempNum .'>',$strTemp);
			$tempNum++;
		}

		$strTemp = preg_replace('/'. $sourceStr .'/', $replaceStr, $strTemp, $replaceNum);

		if ($tempNum > 0){
			for ($i=0; $i<$tempNum; $i++){
				$strTemp = str_replace('<OTre'. $i .'>', $tempArr[$i], $strTemp);
			}
		}
		
		return $strTemp;
	}

	// 过滤html标签
	public static function FilterMark($contentStr,$markName,$mode=0){
		if ($mode==0){
			switch ($markName){
				case 'img':
					$mode=1;
					break;
			
				case 'object': case 'script': case 'style': case 'select':
					$mode=2;
					break;
			
				case 'iframe': case 'div': case 'class': case 'table': case 'tr': case 'td': case 'html': case 'font': case 'a': case 'span':
					$mode=3;
					break;
			
			}
		}

		switch ($mode){
			case 1:
				$contentStr=preg_replace("~<". $markName ."([^>])*>~i",'',$contentStr);
				break;
			case 2:
				$contentStr=preg_replace("~<". $markName ."([^>])*>.*?</". $markName ."([^>])*>~i",'',$contentStr);
				break;
			case 3:
				$contentStr=preg_replace("~<". $markName ."([^>])*>~i",'',$contentStr);
				$contentStr=preg_replace("~</". $markName ."([^>])*>~i",'',$contentStr);
				break;
		}

		return $contentStr;
	}

	// UTF-8字符内容转为GB2312
	public static function UTF2GB($str, $judChk=true){
		if ($judChk && self::CheckCharset($str) == 'GBK'){
			return $str;
		}
		return iconv("UTF-8", "gb2312//IGNORE", $str);
	}

	// GB2312字符内容转为UTF-8
	public static function GB2UTF($str, $judChk=true){
		if ($judChk && self::CheckCharset($str) == 'UTF-8'){
			return $str;
		}
		return iconv("GBK", "UTF-8//IGNORE", $str);
	}

	// 检测字符串的编码
	public static function CheckCharset($text){
		if(strlen($text) < 3){ return false; }
		$lastch = 0;
		$begin = 0;
		$BOM = true;
		$BOMchs = array(0xEF, 0xBB, 0xBF);
		$good = 0;
		$bad = 0;
		$notAscii = 0;
		for($i=0; $i < strlen($text); $i++){
			$ch = ord($text[$i]);
			if($begin < 3){
				$BOM = ($BOMchs[$begin]==$ch);
				$begin += 1;
				continue;
			}
			
			if($begin==4 && $BOM) break;
			
			if($ch >= 0x80 ) $notAscii++;
			
			if( ($ch&0xC0) == 0x80){
				if( ($lastch&0xC0) == 0xC0){
					$good += 1;
				}
				else if( ($lastch&0x80) == 0 ){
					$bad += 1;
				}
			}
			else if( ($lastch&0xC0) == 0xC0 ){
				$bad += 1;
			}
			$lastch = $ch;
		} 
		if($begin == 4 && $BOM){
			return 'UTF-8';
		}else if($notAscii==0){
			return 'ASCII';
		}else if ($good >= $bad ){
			return 'UTF-8';
		}
		else {
			return 'GBK';
		}
	}

	// 改变字符串编码
	public static function ChangeCharset($str,$strCode=''){
		if ($strCode==''){
			$strCode = self::CheckCharset($str);
		}
		if (strtoupper(OT_Charset)==$strCode){
			return $str;
		}else{
			return iconv($strCode,OT_Charset .'//IGNORE',$str);
		}

	}

	// 过滤掉UTF-8字符范围外的字符
	public static function FilterUtf8($ostr){
		preg_match_all('/[\x{FF00}-\x{FFEF}|\x{0000}-\x{00ff}|\x{4e00}-\x{9fff}]+/u', $ostr, $matches);
		$str = join('', $matches[0]);
		if($str==''){   // 含有特殊字符需要逐个处理
			$returnstr = '';
			$i = 0;
			$str_length = strlen($ostr);
			while ($i<=$str_length){
				$temp_str = substr($ostr, $i, 1);
				$ascnum = Ord($temp_str);
				if ($ascnum>=224){
					$returnstr = $returnstr.substr($ostr, $i, 3);
					$i = $i + 3;
				}elseif ($ascnum>=192){
					$returnstr = $returnstr.substr($ostr, $i, 2);
					$i = $i + 2;
				}elseif ($ascnum>=65 && $ascnum<=90){
					$returnstr = $returnstr.substr($ostr, $i, 1);
					$i = $i + 1;
				}elseif ($ascnum>=128 && $ascnum<=191){ // 特殊字符
					$i = $i + 1;
				}else{
					$returnstr = $returnstr.substr($ostr, $i, 1);
					$i = $i + 1;
				}
			}
			$str = $returnstr;
			preg_match_all('/[\x{FF00}-\x{FFEF}|\x{0000}-\x{00ff}|\x{4e00}-\x{9fff}]+/u', $str, $matches);
			$str = join('', $matches[0]);
		}
		return $str;
	}
}

?>
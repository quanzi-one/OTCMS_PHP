<?php

if(! defined('OT_ROOT')) {
	exit('Access Denied');
}


class OT{

	public static function Get($str){
		return @$_GET[$str];
	}

	public static function GetNum($str,$defNum=0){
		return self::ToNum(trim(@$_GET[$str]),$defNum);
	}

	public static function GetInt($str,$defNum=0){
		return self::ToInt(trim(@$_GET[$str]),$defNum);
	}

	public static function GetFloat($str){
		return floatval(@$_GET[$str]);
	}

	public static function GetStr($str){
		return trim(@$_GET[$str]);
	}

	public static function GetRegExpStr($str,$repType){
		return Str::RegExp(@$_GET[$str],$repType);
	}


	public static function Post($str){
		return @$_POST[$str];
	}

	public static function PostNum($str,$defNum=0){
		return self::ToNum(trim(@$_POST[$str]),$defNum);
	}

	public static function PostInt($str,$defNum=0){
		return self::ToInt(trim(@$_POST[$str]),$defNum);
	}

	public static function PostFloat($str){
		return floatval(@$_POST[$str]);
	}

	public static function PostStr($str){
		return trim(@$_POST[$str]);
	}

	public static function PostRStr($str){
		return rtrim(@$_POST[$str]);
	}

	public static function PostReplaceStr($str,$repType){
		return Str::MoreReplace(@$_POST[$str],$repType);
	}

	public static function PostRegExpStr($str,$repType){
		return Str::RegExp(@$_POST[$str],$repType);
	}
	//***** 获取参数 END *****


	public static function ToNum($str,$deStr=0){
		if (is_numeric($str)==false){
			return $deStr;
		}else{
			return $str;
		}
	}

	public static function ToInt($str,$deStr=0){
		if (is_numeric($str)==false){
			return $deStr;
		}else{
			return intval($str);
		}
	}

	public static function ToFloat($num,$decNum=2){
		if (is_numeric($num)==false){
			return 0;
		}else{
			return floatval(number_format((double)$num,$decNum,'.',''));
		}
	}

	// float类型数值比较大小专用，浮点类型不能直接用==比较 $float1 > $float2 = 1
	public static function FloatCmp($float1,$float2,$decNum=2){
		$float1 = number_format((double)$float1,$decNum,'.','');
		$float2 = number_format((double)$float2,$decNum,'.','');
		if ($float1 == $float2){
			return 0;
		}elseif ($float1 > $float2){
			return 1;
		}else{
			return -1;
		}
	}

	// 数值格式化
	public static function NumFormat($num,$decNum=2){
		return number_format((double)$num,$decNum,'.','');
	}

	// 获取范围内的数字随机数
	public static function RndNumTo($minNum, $maxNum){
		if ($minNum > $maxNum){
			$temp = $minNum;
			$minNum = $maxNum;
			$maxNum = $temp;
		}
		@mt_srand();
		return mt_rand($minNum, $maxNum);
	}

	// 获取随机数
	// 33~47：!"#$%&'()*+,-./
	// 48~57：0123456789
	// 58~64：:;<=>?@
	// 65~90：ABCDEFGHIJKLOMNOPQRSTUVWXYZ
	// 91~96：[\]^_`
	// 97~122：abcdefghijklmnopqrstuvwxyz
	// 123~126：{|}~
	// 数值随机数
	public static function RndNum($length){
		$output='';
		for ($a=0; $a<$length; $a++){
			@mt_srand();
			$output .= chr(mt_rand(48, 57));    //生成php随机数
		}
		return $output;
	}

	// 字符随机数
	public static function RndChar($length){
		$output = '';
		$charArr = array(
			'1','2','3','4','5','6','7','8','9',
			'A','B','C','D','E','F','G','H','I','J','K','L','M','N','P','Q','R','S','T','U','V','W','X','Y','Z',
			'a','b','c','d','e','f','g','h','i','j','k','l','m','n','p','q','r','s','t','u','v','w','x','y','z'
			);
		$charCount = count($charArr)-1;
		for ($a=0; $a<$length; $a++){
			@mt_srand();
			$output .= $charArr[mt_rand(0, $charCount)];
		}
		return $output;
	}

	// 字母随机数
	public static function RndABC($length){
		$output = '';
		$charArr = array(
			'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
			'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z'
			);
		$charCount = count($charArr)-1;
		for ($a=0; $a<$length; $a++){
			@mt_srand();
			$output .= $charArr[mt_rand(0, $charCount)];
		}
		return $output;
	}

	// html转换成js
	public static function HtmlToJs($contentStr){
		$contentStr = str_replace(array('\\','/','\'','"'), array('\\\\','\\/','\\\'','\\"'), $contentStr);
		$contentStr = implode("\");". PHP_EOL ."document.writeln(\"",explode(PHP_EOL, $contentStr));
		$contentStr = "document.writeln(\"". str_replace(array("\r","\n"),array('',''),$contentStr) ."\");";
		return $contentStr;
	}

	// js转换成html
	public static function JsToHtml($contentStr){
		$contentStr = str_replace(array('document.writeln(\"','");','\\"','\\\'','\\/','\\\\'), array('','','"','\'','/','\\'), $contentStr);
		return $contentStr;
	}

	// 获取xml某个元素
	public static function GetXmlItem($contentStr,$mark){
		$retStr = Str::Filter(Str::GetMark($contentStr, '<'. $mark .'>', '</'. $mark .'>'), 'xml');
		return $retStr;
	}

	// 对象数组转为普通数组
	public static function ObjToArr($objStr) {
		if(is_object($objStr)){ $objStr = get_object_vars($objStr); }
		if(is_array($objStr)){ foreach($objStr as $k=>$v) $objStr[$k] = self::ObjToArr($v); }
		return $objStr;
	}

	// XML转为数组
	public static function XmlToArr($xml) {
		if(! $xml){ return false; }
		// 将XML转为array
		// 禁止引用外部xml实体
		$disableLibxmlEntityLoader = libxml_disable_entity_loader(true); //改为这句
		$retArr = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
		libxml_disable_entity_loader($disableLibxmlEntityLoader); //添加这句
		return $retArr;
	}

	// 数组转为XML
	public static function ArrToXml($arr){
		if(! is_array($arr) || count($arr) <= 0){ return false; }
    	
    	$xml = '<xml>';
    	foreach ($arr as $key=>$val)
    	{
    		if (is_numeric($val)){
    			$xml .= '<'. $key .'>'. $val .'</'. $key .'>';
    		}else{
    			$xml .= '<'. $key .'><![CDATA['. $val .']]></'. $key .'>';
    		}
        }
        $xml .= '</xml>';
        return $xml; 
	}

	// 数组转为网址参数
	public static function ArrToUrlParam($arr, $skipArr=array())
	{
		$retStr = '';
		foreach ($arr as $key => $val)
		{
			if( (! in_array($key,$skipArr)) && $val != '' && (! is_array($val)) ){
				$retStr .= $key .'='. $val .'&';
			}
		}
		
		$retStr = trim($retStr, '&');
		return $retStr;
	}

	// GET请求参数
	public static function GetParam($skipArr=array(), $addArr=array(), $headUrl=''){
		$retStr = $headUrl;
		if (count($addArr) > 0){
			foreach($addArr as $key => $value){
				$skipArr[] = $key;
			}
		}
		foreach($_GET as $key => $value){
			if (in_array($key,$skipArr)==false && is_string($key) && is_string($value)){
				$retStr .= self::ParamSign($retStr) . urlencode(Str::RegExp($key,'sql2')) .'='. urlencode(Str::RegExp($value,'sql2'));
			}
		}
		foreach($addArr as $key => $value){
			$retStr .= self::ParamSign($retStr) . urlencode(Str::RegExp($key,'sql2')) .'='. (in_array($value,array('[page]')) ? $value : urlencode(Str::RegExp($value,'sql2')));
		}
		if (strlen($retStr) == 0 && $headUrl != 'no'){
			$retStr = basename(GetUrl::HttpSelf());
		}
		return $retStr;
	}

	// 判断网址参数连接符
	public static function ParamSign($url=''){
		if (strpos($url,'?') !== false){
			return '&amp;';
		}else{
			return '?';
		}
	}

	// 密码解密
	public static function DePwdData($str, $mode, $key){
		switch ($mode){
			case 'dz':	return base64_decode( str_replace( array('-','_'), array('+','/'), Encrypt::AuthCode($str, 'DECODE', $key .'otcms.com') ) );
			default :	return $str;
		}
	}

	// IP地址隐藏化
	public static function IpHidden($str){
		return substr($str,0,strrpos($str,'.')) .'.*';
	}

	public static function GetIpInfoArr($refIP='',$defNull='缺少IP库'){
		if ($refIP==''){ $refIP = Users::GetIp(); }

		if (in_array($refIP,array('127.0.0.1','::1'))){
			$infoArr = array(
				'res'		=> true,
				'ip'		=> $refIP,
				'city'		=> '本地',
				'address'	=> '本地',
				);
			return $infoArr;
		}

		/* if (! OT_OpenIpDatabase){
			$infoArr = array(
				'ip'		=> $refIP,
				'city'		=> '缺少IP库',
				'address'	=> '缺少IP库',
				);
			return $infoArr;
		} */


		if (file_exists(OT_ROOT .'tools/ip.dat')){
			$ipobj = new IpInfo();
			// if ($refIP==''){ $refIP = $ipobj->getIP(); }


			$addrArr = $ipobj->getaddress($refIP);
			$infoArr = array(
				'res'		=> true,
				'ip'		=> $refIP,
				'city'		=> iconv('GB2312', 'UTF-8//IGNORE', $addrArr['area1']),
				'address'	=> iconv('GB2312', 'UTF-8//IGNORE', $addrArr['area1'] .' '. $addrArr['area2']),
				);
		
		}else{
			$infoArr = array(
				'res'		=> false,
				'ip'		=> $refIP,
				'city'		=> $defNull,
				'address'	=> $defNull,
				);

		}

		return $infoArr;
	}


	// 兼容each函数，php 7.2弃用each
	public static function NewEach(&$array){
		$res = array();
		$key = key($array);
		if($key !== null){
			next($array); 
			$res[1] = $res['value'] = $array[$key];
			$res[0] = $res['key'] = $key;
		}else{
			$res = false;
		}
		return $res;
	}


	// 兼容count函数，php 7.2苛刻化
	public static function NewCount($array, $mode = COUNT_NORMAL){
		$res = 0;
		if(is_array($array) || is_object($array)){
			$res = count($array, $mode);
		}
		return $res;
	}


	// 检查是否具目录可执行
	public static function TestPhpRun($pathDir, $siteUrl, $event = '') {
		$testStr = '<'. chr(0x3F) .'p'. chr(hexdec(68)) . chr(112) ."\r\n";
		$fileName = 'testOtcmsRun.php';	// md5($pathDir) .
		$rndStr = OT::RndChar(8);
		$testStr .= 'echo \'[\'. md5(\''. $rndStr .'\') .\']&ensp;&ensp;警告！该目录可以运行PHP文件，有执行权限\';'."\r\n";
		$testStr .= chr(0x3F).'>';

		$retArr = array('res'=>false, 'code'=>-1, 'note'=>'');
		if (is_writable($pathDir)) {
			if (! File::Write($pathDir . $fileName, $testStr, false, $errStr)){
				$retArr = array('res'=>false, 'code'=>2, 'note'=>'创建文件（'. $pathDir . $fileName .'）失败');
			}else{
				if (strpos($event,'|noCheck|') === false){
					$remoteUrl = $siteUrl . str_replace(OT_ROOT, '', str_replace("\\", '/',realpath($pathDir))) .'/'. $fileName;
					$tempStr = @ReqUrl::UseAuto(0,'get',$remoteUrl);
					if (strpos(trim(''. $tempStr), '['. md5($rndStr) .']') === false){
						$retArr = array('res'=>false, 'code'=>9, 'note'=>'内容不匹配，没有执行权限');
					}else{
						$retArr = array('res'=>true, 'code'=>0, 'note'=>'有执行权限');
					}
				}else{
					$retArr = array('res'=>true, 'code'=>0, 'note'=>'写入文件（'. $pathDir . $fileName .'）成功');
				}
				if (strpos($event,'|noDel|') === false){
					unlink($pathDir . $fileName);
				}
			}
		}else{
			$retArr = array('res'=>false, 'code'=>1, 'note'=>'该目录（'. $pathDir .'）没有写入权限');
		}
		return $retArr;
	}

	// 获取服务器外网IP
	public static function GetServIp(){
		/*
		$ch = curl_init('http://2019.ip138.com/ic.asp');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$a = curl_exec($ch);
		*/
		$a = file_get_contents('http://2019.ip138.com/ic.asp');
		preg_match('/\[(.*)\]/', $a, $ip);
		return $ip[1];
	}
}

?>
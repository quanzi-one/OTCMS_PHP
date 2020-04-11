<?php

if(! defined('OT_ROOT')) {
	exit('Access Denied');
}


class ReqUrl{

	public static function UseAuto($seMode, $method, $url, $charset='UTF-8', $dataArr=array(), $retMode=''){
		$retArr = array('res'=>false, 'note'=>'');

		switch ($seMode){
			case 1:	// Snoopy插件
				$retArr = self::UseSnoopy($method, $url, $charset, $dataArr);
				break;
		
			case 2:	// curl模式
				$retArr = self::UseCurl($method, $url, $charset, $dataArr);
				break;
		
			case 3:	// fsockopen模式
				$retArr = self::UseFsockopen($method, $url, $charset, $dataArr);
				break;

			case 4:	// fopen模式
				$retArr = self::UseFopen($method, $url, $charset, $dataArr);
				break;

			default :
				if (extension_loaded('curl')){
					$retArr = self::UseCurl($method, $url, $charset, $dataArr);
					//echo('curl['. $retArr['note'] .']<br />');
				}
				if ($retArr['res'] == false && function_exists('stream_socket_client')){
					$retArr = self::UseSnoopy($method, $url, $charset, $dataArr);
					//echo('Snoopy['. $retArr['note'] .']<br />');
				}
				if ($retArr['res'] == false && function_exists('fsockopen')){
					$retArr = self::UseFsockopen($method, $url, $charset, $dataArr);
					//echo('fsockopen['. $retArr['note'] .']<br />');
				}
				if ($retArr['res'] == false && (ini_get('allow_url_fopen') == 1 || strtolower(ini_get('allow_url_fopen')) == 'on')){
					$retArr = self::UseFopen($method, $url, $charset, $dataArr);
					//echo('fopen['. $retArr['note'] .']<br />');
				}
				break;
		}

		if ($retMode == 'res'){
			return $retArr['res'];
		}elseif ($retMode == 'note'){
			return $retArr['note'];
		}else{
			return $retArr;
		}
	}


	public static function UseAuto2($seMode, $method, $url, $charset='UTF-8', $dataArr=array(), $retMode=''){
		switch ($seMode){
			case 3:	// fsockopen模式
				$retArr = self::UseFsockopen($method, $url, $charset, $dataArr);
				break;
		
			default :	// curl模式
				$retArr = self::UseCurl($method, $url, $charset, $dataArr);
				break;
		}

		if ($retMode == 'res'){
			return $retArr['res'];
		}elseif ($retMode == 'note'){
			return $retArr['note'];
		}else{
			return $retArr;
		}
	}


	// 获取网页源码（限制读取时间）采用Snoopy插件
	// mode：模式（POST/GET）；URL：网页地址；charset：目标网址编码；dataArr：POST下的表单信息
	public static function UseSnoopy($method, $url, $charset='UTF-8', $dataArr = array()){
		if (empty($url)){
			return array('res'=>false, 'note'=>'UseSnoopy：网址为空');
		}

		class_exists('Snoopy',false) or include(OT_ROOT .'inc/Snoopy.class.php');
		$snoopy = new Snoopy();
		if (strtoupper($method) == 'POST'){
			if (! is_array($dataArr)){
				$dataStr = $dataArr;
				parse_str($dataStr,$dataArr);
			}
			// POST
			$snoopy->submit($url,$dataArr);
		}else{
			// GET
			$snoopy->fetch($url);
		}
		$data = $snoopy->results;
		if (strlen($data) == 0){ return array('res'=>false, 'note'=>'UseSnoopy：获取内容为空'); }

		$siteCharset = strtoupper(OT_Charset);
		if ($siteCharset=='GB2312'){ $siteCharset='GBK'; }
		if ($charset != $siteCharset){
			$data = iconv($charset,OT_Charset .'//IGNORE',$data);
		}
		return array('res'=>true, 'note'=>$data);
	}


	// 获取页面源代码2 curl模式
	public static function UseCurl($method, $url, $charset='UTF-8', $dataArr=array()){
		if (empty($url)){
			return array('res'=>false, 'note'=>'UseCurl：网址为空');
		}

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 2.0.50727;)'); 
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 45);	// 响应时间
		curl_setopt($ch ,CURLOPT_TIMEOUT, 120);			// 设置超时
		// 使用的HTTP协议，CURL_HTTP_VERSION_NONE（让curl自己判断），CURL_HTTP_VERSION_1_0（HTTP/1.0），CURL_HTTP_VERSION_1_1（HTTP/1.1）
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
		if (substr(strtolower($url),0,8) == 'https://'){
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);	// 跳过证书检查  
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);		// 从证书中检查SSL加密算法是否存在
		}
		if (strtoupper($method) == 'POST'){
			if (is_array($dataArr)){
				$newData = http_build_query($dataArr);	// 相反函数 parse_str()
			}else{
				$newData = $dataArr;
			}
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $newData);
		}
		$data = curl_exec($ch);

		// 检查是否有错误发生
		if(curl_errno($ch)){ return array('res'=>false, 'note'=>'UseCurl：发生错误（'. curl_error($ch) .'）'); }

		// 检查HTML返回状态
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		curl_close($ch);

		if($httpCode != 200){ return array('res'=>false, 'note'=>'UseCurl：返回状态'. $httpCode); }

		if (strlen($data) == 0){ return array('res'=>false, 'note'=>'UseCurl：获取内容为空'); }

		$siteCharset = strtoupper(OT_Charset);
		if ($siteCharset=='GB2312'){ $siteCharset='GBK'; }
		if ($charset != $siteCharset){
			$data = iconv($charset,OT_Charset .'//IGNORE',$data);
		}
		return array('res'=>true, 'note'=>$data);
	}


	// 获取页面源代码2 fsockopen模式
	public static function UseFsockopen($method, $url, $charset='UTF-8', $dataArr = array()){
		if (empty($url)){
			return array('res'=>false, 'note'=>'UseFsockopen：网址为空');
		}

		$urlArr = parse_url($url);
		$host = $urlArr['host'];
		$urlPath = $urlArr['path'];
		$port = 80;
		$errno = '';
		$errstr = '';
		$timeout = 30;
		if (strtolower(substr($url,0,8)) == 'https://'){ $hStart='ssl://';$port=443; }else{ $hStart=''; } // tcp://

		if (! empty($urlArr['query'])){
			$urlPath .= '?'. $urlArr['query'];
		}

		if ($method == 'POST'){

			if (is_array($dataArr)){
				$newData = http_build_query($dataArr);	// 相反函数 parse_str()
			}else{
				$newData = $dataArr;
			}

			// 创建连接
			$fp = fsockopen($hStart . $host, $port, $errno, $errstr, $timeout);

			if(!$fp){
				return array('res'=>false, 'note'=>'UseFsockopen：POST发生错误');
			}

			// 发送请求
			$out = 'POST '. $urlPath .' HTTP/1.1\r\n';
			$out .= 'Host:'. $host ."\r\n";
			$out .= "Content-type:application/x-www-form-urlencoded\r\n";
			$out .= "Content-length:". strlen($newData) ."\r\n";
			$out .= "Connection:close\r\n\r\n";
			$out .= $newData;
		}else{

			// 创建连接
			$fp = fsockopen($hStart . $host, $port, $errno, $errstr, $timeout);

			if(!$fp){
				return array('res'=>false, 'note'=>'UseFsockopen：GET发生错误');
			}

			// 发送请求
			$out = "GET ". $urlPath ." HTTP/1.1\r\n";
			$out .= "Host: ". $host ."\r\n";
			$out .= "Connection:close\r\n\r\n";
		}

		fputs($fp, $out);

		$data = '';
		while($row=fread($fp, 4096)){
			$data .= $row;
		}

		fclose($fp);

		$pos = strpos($data, "\r\n\r\n");
		$data = substr($data, $pos+4);
		if (strlen($data) == 0){ return array('res'=>false, 'note'=>'UseFsockopen：获取内容为空'); }

		$siteCharset = strtoupper(OT_Charset);
		if ($siteCharset=='GB2312'){ $siteCharset='GBK'; }
		if ($charset != $siteCharset){
			$data = iconv($charset,OT_Charset .'//IGNORE',$data);
		}

		return array('res'=>true, 'note'=>$data);
	}


	// 获取页面源代码4 fopen模式
	public static function UseFopen($method, $url, $charset='UTF-8', $dataArr = array()){
		if (empty($url)){
			return array('res'=>false, 'note'=>'UseFopen：网址为空');
		}

		if (is_array($dataArr)){
			$newData = http_build_query($dataArr);	// 相反函数 parse_str()
		}else{
			$newData = $dataArr;
		}

		ini_set('user_agent','Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 2.0.50727;)'); 
		if (strtoupper($method) == 'POST'){
			$context = array(
				'http' => array(
					'method'	=> (strtoupper($method)=='POST' ? 'POST' : 'GET'),
					'header'	=> 'User-Agent:Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 2.0.50727;)'. PHP_EOL .'Content-type: application/x-www-form-urlencoded'. PHP_EOL .'Content-Length: ' . strlen($newData) . PHP_EOL,
				//	'header'	=> 'User-Agent : '. $_SERVER['HTTP_USER_AGENT'],
				//	'header'	=> 'Content-type: application/x-www-form-urlencoded'. PHP_EOL . 'Content-Length: ' . strlen($newData) . PHP_EOL,
					'content'	=> $newData,  
					'timeout'	=> 60)
				);
			$stream_context = stream_context_create($context);
			$data = file_get_contents($url, false, $stream_context);
		}else{
			$data = file_get_contents($url);
		}

		if (strlen($data) == 0){ return array('res'=>false, 'note'=>'UseFopen：获取内容为空'); }

		$siteCharset = strtoupper(OT_Charset);
		if ($siteCharset=='GB2312'){ $siteCharset='GBK'; }
		if ($charset != $siteCharset){
			$data = iconv($charset,OT_Charset .'//IGNORE',$data);
		}
		return array('res'=>true, 'note'=>$data);
	}


	public static function UseGet($url, $timeout=30){
		$retStr = '';
		if ( function_exists('curl_init') ){
			$ch = curl_init();
			curl_setopt ($ch, CURLOPT_URL, $url);
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			$retStr = curl_exec($ch);
			curl_close($ch);
		}
		if (strlen($retStr)==0 && function_exists('fsockopen')){
			$urlArr = parse_url($url);
			$host = $urlArr['host'];
			$urlPath = $urlArr['path'];
			$port = 80;
			$errno = '';
			$errstr = '';
			$timeout = 30;
			if (strtolower(substr($url,0,8)) == 'https://'){ $hStart='ssl://';$port=443; }else{ $hStart=''; } // tcp://
			$fp = fsockopen($hStart . $host, $port, $errno, $errstr, $timeout);
			// 发送请求
			$out = 'GET '. $urlPath ." HTTP/1.1\r\n";
			$out .= "Host: ". $host ."\r\n";
			$out .= "Connection:close\r\n\r\n";
			fputs($fp, $out);

			$data = '';
			while($row=fread($fp, 4096)){
				$data .= $row;
			}

			fclose($fp);

			$pos = strpos($data, "\r\n\r\n");
			$data = substr($data, $pos+4);
			$retStr = $data;
		}
		if (strlen($retStr)==0 && (ini_get('allow_url_fopen') == 1 || strtolower(ini_get('allow_url_fopen')) == 'on')){
			$retStr = @file_get_contents($url);
		}
		if (strlen($retStr)==0 && function_exists('stream_socket_client')){
			$retStr = '';
			class_exists('Snoopy',false) or include(OT_ROOT .'inc/Snoopy.class.php');
			$snoopy = new Snoopy();
			$snoopy->fetch($url);
			$retStr = $snoopy->results;
		}
		return $retStr;
	}


	// 通过代理IP访问网址
	/*
	$proxyArr
		必填: 
		ip: 代理服务器地址
		port: 代理服务器端口

		可选: 
		charset: 编码
		header: 请求头
		userAgent: 浏览器用户信息
		timeout: 超时秒数
		cookie: 存储cookie

	$dataArr: 当POST请求时发送内容
	*/
	public static function ProxyCurl($method, $url, $proxyArr, $charset='UTF-8', $dataArr=array()){
		if (empty($proxyArr['ip'])){
			return array('res'=>false, 'note'=>'ProxyCurl：代理服务器地址为空');
		}
		if (empty($url)){
			return array('res'=>false, 'note'=>'ProxyCurl：网址为空');
		}

		if (empty($proxyArr['userAgent'])){ $proxyArr['userAgent'] = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 2.0.50727;)'; }
		if (empty($proxyArr['timeout'])){ $proxyArr['timeout'] = 120; }

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_PROXYAUTH, CURLAUTH_BASIC);	// 代理认证模式
		curl_setopt($ch, CURLOPT_PROXY, $proxyArr['ip']);		// 代理服务器地址
		curl_setopt($ch, CURLOPT_PROXYPORT, $proxyArr['port']);	// 代理服务器端口
		curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);	//使用http代理模式
		// curl_setopt($ch, CURLOPT_PROXYUSERPWD, ":");			// http代理认证帐号，username:password的格式
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);		// https请求 不验证证书和hosts
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_URL, $url);					// 设置请求网址
		curl_setopt($ch, CURLOPT_USERAGENT, $proxyArr['userAgent']);	// 浏览器用户信息
		curl_setopt($ch, CURLOPT_TIMEOUT, $proxyArr['timeout']);		// 设置超时秒数
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 45);	// 响应时间秒数
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);	// 设置是否返回信息
		// curl_setopt($ch, CURLOPT_MAXREDIRS, 3);	//设置跳转location 最多3次

		if (! empty($proxyArr['cookie'])){
			curl_setopt($ch, CURLOPT_COOKIEJAR,  $proxyArr['cookie']);		// 存储cookies
			curl_setopt($ch, CURLOPT_COOKIEFILE, $proxyArr['cookie']);
		}
		if(strtoupper($method) == 'POST'){	// 设置为POST方式
			if (is_array($dataArr)){
				$newData = http_build_query($dataArr);	// 相反函数 parse_str()
			}else{
				$newData = $dataArr;
			}
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $newData);
		}
		if (! empty($proxyArr['header'])){
			curl_setopt($ch, CURLOPT_HTTPHEADER, $proxyArr['header']);
		}

		$data = curl_exec($ch);	// 接收返回信息
		if (strlen($data) == 0){ return array('res'=>false, 'note'=>'ProxyCurl：获取内容为空'); }

		$siteCharset = strtoupper(OT_Charset);
		if ($siteCharset=='GB2312'){ $siteCharset='GBK'; }
		if ($charset != $siteCharset){
			$data = iconv($charset,OT_Charset .'//IGNORE',$data);
		}
		return array('res'=>true, 'note'=>$data);
	}


	public static function SelUpdateUrl($num){
		switch ($num){
			case 1:		return 'http://update2.oneti.cn/';		break;
			case 2:		return 'http://update2.bai35.com/';		break;
			default :	return 'http://php.otcms.com/';			break;
		}
	}
}

?>
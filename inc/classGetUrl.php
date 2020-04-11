<?php

if(! defined('OT_ROOT')) {
	exit('Access Denied');
}


class GetUrl{
	public static $isPort = false;

	public static function HttpHost(){
		return isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
	}

	public static function HttpSelf(){
		return $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
	}

	// 获取网址协议 http:// 或 https://
	public static function HttpHead(){
		// return ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
		if ( ! empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off'){
			return 'https://';
		}elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https'){
			return 'https://';
		}elseif ( ! empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off'){
			return 'https://';
		}else{
			return 'http://';
		}
	}

	// 获取网址目录URL
	public static function Dir($pathSH=true,$URLrank=0){
		$WebURL = self::HttpHead() . self::HttpHost() . dirname(self::HttpSelf());
		if ($pathSH==true){$WebURL .= '/';}
		for ($i=0; $i<$URLrank; $i++){
			$WebURL=self::UpDir($WebURL,$pathSH);
		}
		return $WebURL;
	}



	// 获取路径的上级目录
	// $pathSH:目录尾是否要斜杠，默认不要
	public static function UpDir($dirPath,$pathSH=false){
		$dirPath=substr($dirPath,0,-1);
		$dirPath=substr($dirPath,0,strrpos($dirPath,'/'));
		if ($pathSH==true){$dirPath .= '/';}
		return $dirPath;
	}


	// 获取网址的顶级域名
	public static function Domain($urlStr){
		$newUrl = str_replace(array('http://','https://'),'',strtolower($urlStr));
		$newUrlEndNum = strpos($newUrl,'/');
		if ($newUrlEndNum !== false){ $newUrl = substr($newUrl,0,$newUrlEndNum); }
		$newUrlEndNum = strpos($newUrl,':');
		if ($newUrlEndNum !== false){ $newUrl = substr($newUrl,0,$newUrlEndNum); }
		return $newUrl;
	}


	// 获取当前域名端口
	public static function Port($preStr=':'){
		$SERVER_PORT = '';
		if (self::$isPort){
			$SERVER_PORT = $_SERVER['SERVER_PORT'];
			if ($SERVER_PORT != 80){ $SERVER_PORT=$preStr . $SERVER_PORT; }else{ $SERVER_PORT=''; }
		}
		return $SERVER_PORT;
	}

	// 获取当前域名
	public static function Main(){
		// $SERVER_PORT = self::Port();
		// $SER_HOST = $_SERVER['SERVER_NAME'] . $SERVER_PORT;
		$SER_HOST = self::HttpHost();
		return self::HttpHead() . $SER_HOST .'/';
	}

	// 获取当前网址
	public static function Curr(){
		// $SERVER_PORT = self::Port();
		// $SER_HOST = $_SERVER['SERVER_NAME'] . $SERVER_PORT;
		$SER_HOST = self::HttpHost();
		return self::HttpHead() . $SER_HOST . self::HttpSelf();
	}

	// 获取当前网址含？后面参数
	public static function Query(){
		// $SERVER_PORT = self::Port();
		// $SER_HOST = $_SERVER['SERVER_NAME'] . $SERVER_PORT;
		$SER_HOST = self::HttpHost();
		$getCurrUrlStr = self::HttpHead() . $SER_HOST . self::HttpSelf();
		$queryPart=$_SERVER['QUERY_STRING'];
		if (strlen($queryPart)>0){ $getCurrUrlStr .= '?'. $queryPart; }
		return $getCurrUrlStr;
	}

	// 获取当前网址目录
	// dirRank=0:当前网址目录，1:上级网址目录（带/斜杆）
	public static function CurrDir($dirRank=0){
		$currUrl = self::Curr();
		for ($udi=0; $udi<=$dirRank; $udi++){
			$currUrl=substr($currUrl,0,strrpos($currUrl,'/'));
		}
		return $currUrl .'/';

	}

	// 获取当前网址目录2 参数采用./  ../  ../../ 表示退上级几步
	public static function CurrDir2($pathPart){
		return self::CurrDir(strlen(str_replace(array('../','./','/'),array('1','',''),$pathPart)));
	}

}

?>
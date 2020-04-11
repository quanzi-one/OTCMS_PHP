<?php
error_reporting(E_ALL ^ E_NOTICE);				// 显示除去 E_NOTICE 之外的所有错误信息
1111
header('Content-111111Type: text/html; charset=UTF-8');

/*
	使用 X-Frame-Options 有三个可选的值：
	DENY：浏览器拒绝当前页面加载任何Frame页面
	SAMEORIGIN：frame页面的地址只能为同源域名下的页面
	ALLOW-FROM：允许frame加载的页面地址
*/
// header('X-Frame-Options:SAMEORIGIN');
// header('X-Frame-Options:ALLOW-FROM');

define('OT_ROOT', dirname(__FILE__) .'/');

// 关闭透明化session id的功能
if (ini_get('session.use_trans_sid') != 0){ ini_set('session.use_trans_sid',0); }
// 只从cookie检查session id
if (ini_get('session.use_cookies') != 1){ ini_set('session.use_cookies',1); }
if (ini_get('session.use_only_cookies') != 1){ ini_set('session.use_only_cookies',1); }
if (ini_get('session.cookie_httponly') != 1){ ini_set('session.cookie_httponly', 1); }
// if (ini_get('session.cookie_secure') != 1){ ini_set('session.cookie_secure', 1); } // 开启则表明你的cookie只有通过HTTPS协议传输时才起作用。

// session_id();
// session_name(OT_SiteID .'sessionId');

@session_start();


if (! defined('OT_adminROOT')){
	// 代理IP直接退出
	// empty($_SERVER['HTTP_VIA']) or exit('Access Denied ...');
	$seconds = '1'; // 时间段[秒]
	$refresh = '15'; // 刷新次数
	// 设置监控变量
	$cur_time = time();
	if(isset($_SESSION['last_time'])){
		$_SESSION['refresh_times'] += 1;
	}else{
		$_SESSION['refresh_times'] = 1;
		$_SESSION['last_time'] = $cur_time;
	}
	// 处理监控结果
	if($cur_time - $_SESSION['last_time'] < $seconds){
		if($_SESSION['refresh_times'] >= $refresh){
			// 跳转至攻击者服务器地址
			header(sprintf('Location:%s', 'http://127.0.0.1'));
			exit('Access Denied ...');
		}
	}else{
		$_SESSION['refresh_times'] = 0;
		$_SESSION['last_time'] = $cur_time;
	}
}




require(OT_ROOT .'configVer.php');
require(OT_ROOT .'config.php');
require(OT_ROOT .'inc/classPdoDb.php');


// 自动加载类文件
if (version_compare(PHP_VERSION, '5.3.0', '>=')) {
	spl_autoload_register('OTautoload', true, true);
}else{
	spl_autoload_register('OTautoload');
}

$autoloadItem = '';
function OTautoload($className){
	global $autoloadItem;
	if ( stripos($className,'Smarty') === false && stripos($className,'TopClient') === false ){
		$judStrrev = false;
		if ( in_array($className,array('Ad', 'Adm', 'AdmArea', 'InfoType', 'Member', 'ServerFile', 'Skin', 'StrArr', 'IdcProType')) ){	// , 'SaveImg', 'WebHtml'
			$classPath = OT_adminROOT .'inc/class'. $className .'.php';

		}elseif ( in_array($className,array('WapArea','WapContent','WapIndex','WapJS','WapList','WapUrl')) ){
			$classPath = OT_wapROOT .'inc/class'. $className .'.php';

		}elseif ( in_array(substr($className,0,3),array('App','Api')) ){
			$judStrrev = true;
			$classPath = OT_ROOT .'plugin/class'. $className .'.php';

		}else{
			if ( ($autoloadItem == 'aliyun' || defined('OT_AppOssAliyun')) && strpos($className,"OSS\\") === 0 ){
				// 加载 阿里云OSS 云存储
				$path = str_replace('\\', DIRECTORY_SEPARATOR, $className);
				$classPath = OT_ROOT .'tools/ossAliyun/'. $path . '.php';
			}elseif ( ($autoloadItem == 'qiniu' || defined('OT_AppOssQiniu')) && strpos($className,"Qiniu\\") === 0 ){
				// 加载 七牛云 云存储
				$path = str_replace('\\', DIRECTORY_SEPARATOR, $className);
				$classPath = OT_ROOT .'tools/ossQiniu/src/'. $path . '.php'; 
			}else{
				$classPath = OT_ROOT .'inc/class'. $className .'.php';
			}
		}
		if (file_exists($classPath)){
			include($classPath);
		}else{
			if ($judStrrev){
				$classPath = OT_ROOT .'pluDef/class'. $className .'.php';
			}else{
				$classPath = OT_ROOT .'inc/class'. $className .'.php';
			}
			if (file_exists($classPath)){
				include($classPath);
			}else{
				if (defined('TOP_AUTOLOADER_PATH')){
					spl_autoload_register('Autoloader::autoload');
				}else{
					echo('类文件不存在或插件没购买/已禁用.'. $classPath .'或'. OT_ROOT .'inc/class'. $className .'.php');
				}
			}
		}
	}
}



// 获取网站基本参数数组
/*
if (! @include(OT_ROOT .'cache/php/system.php')){
	$Cache = new Cache();
	$Cache->Php('system','arr');

}
*/
if ($systemFile = @include(OT_ROOT .'cache/php/system.php')){
	$systemArr = unserialize($systemFile);
}else{
	$Cache = new Cache();
	$Cache->Php('system');
	die('
	<br /><br />
	<center>
		加载SYSTEM配置文件失败，<a href="#" onclick="document.location.reload();">[点击重新刷新]</a>
	</center>
	');
}


if ($systemArr['SYS_isLogErr'] == 1){
	ini_set('display_errors', 'off');			// 关闭错误报告的显示，一般在运行阶段使用
	ini_set('log_errors', 'on');				// 将错误报告写入日志中
	ini_set('log_errors_max_len', '10240');		// 每个日志项的最大长度
	ini_set('error_log', OT_ROOT . OT_dbDir .'softErr.log');	// 日志保存路径
}


if (OT_Database == 'mysql'){
	$dsn = 'mysql:host='. $dbServerName .';port='. $dbPort .';dbname='. $dbName;

}elseif (OT_Database == 'sqlite'){
	$dsn = 'sqlite:'. OT_ROOT . $dbName;

}

// 创建数据库链接
$DB = new PdoDb( array('type'=>OT_Database, 'dsn'=>$dsn, 'dbName'=>$dbName, 'user'=>$dbUserName, 'pwd'=>$dbUserPwd, 'charset'=>$systemArr['SYS_dbCharset']) );


// 允许程序在 register_globals = off 的环境下工作
/*
$onoff = function_exists('ini_get') ? ini_get('register_globals') : get_cfg_var('register_globals');
if ($onoff != 1) {
	@extract($_POST, EXTR_SKIP);
	@extract($_GET, EXTR_SKIP);
	@extract($_COOKIE, EXTR_SKIP);
}
*/

// 防止 PHP 5.1.x 使用时间函数报错
if (function_exists('date_default_timezone_set')){
	@date_default_timezone_set('PRC');	//PRC是中华人民共和国时区
}


// 判断 magic_quotes_gpc 状态 （给参数值加斜杠“/”转义）
if (ini_get('magic_quotes_runtime') != 0){ ini_set('magic_quotes_runtime',0); }
$magic_quotes_gpc = 0;
if (function_exists('get_magic_quotes_gpc')) {
	$magic_quotes_gpc = @get_magic_quotes_gpc();
}
if (! $magic_quotes_gpc){
	$_GET	= sec($_GET);
//	$_POST	= sec($_POST);		// 对存入ACCESS数据库，对内容这样转义不对，由ADODB的qstr函数进行处理
	$_COOKIE= sec($_COOKIE);
	$_FILES	= sec($_FILES);
}else{
    $_POST = desec($_POST);		// 对已转义内容进行反转义
}
$_SERVER = sec($_SERVER);


function sec(&$array) {
	if (is_array($array)){
		foreach ($array as $k => $v){
			$array[$k] = sec($v);
		}
	}elseif (is_string($array)){
		$array = addslashes($array);
	}elseif  (is_numeric($array)){
		$array = intval($array);
	}
	return $array;
}


function desec(&$array) {
	if (is_array($array)){
		foreach ($array as $k => $v){
			$array[$k] = desec($v);
		}
	}elseif (is_string($array)){
		$array = stripslashes($array);
	}elseif (is_numeric($array)){
		$array = intval($array);
	}
	return $array;
}



$timeInfoWhereStr = '';
if ($systemArr['SYS_isTimeInfo'] == 1){
	$timeInfoWhereStr = ' and IF_time<='. $DB->ForTime(TimeDate::Get());
}else{
	$timeInfoWhereStr = '';
}
define('OT_TimeInfoWhereStr', $timeInfoWhereStr);

// Url路径需要用到
$GB_WebHost = $GB_JsHost = '';
//if (empty($systemArr['SYS_URL'])){
	if (isset($webPathPart)){ $GB_WebHost = $webPathPart; }
	if (isset($jsPathPart)){ $GB_JsHost = $jsPathPart; }
/* }else{
	$GB_WebHost = $systemArr['SYS_URL'];
	$GB_JsHost = $systemArr['SYS_URL'];
} */

$mudi	= OT::GetStr('mudi');

?>
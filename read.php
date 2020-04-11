<?php
header('Content-Type: text/html; charset=UTF-8');

define('OT_ROOT', dirname(__FILE__) .'/');
define('OT_Charset',	'utf-8');


// 自动加载类文件
if (version_compare(PHP_VERSION, '5.3.0', '>=')) {
	spl_autoload_register('OTautoload', true, true);
}else{
	spl_autoload_register('OTautoload');
}

function OTautoload($className){
	$judStrrev = false;
	if ( in_array(substr($className,0,3),array('App','Api')) ){
		$judStrrev = true;
		$classPath = OT_ROOT .'plugin/class'. $className .'.php';

	}else{
		$classPath = OT_ROOT .'inc/class'. $className .'.php';
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
			echo('类路径错误或不存在.'. $classPath .'或'. OT_ROOT .'inc/class'. $className .'.php');
		}
	}
}


$mudi = OT::GetStr('mudi');

switch ($mudi){
	case 'getKeyWord':
		GetKeyWord();
		break;

	case 'getUrlencode':
		echo(urlencode(OT::GetStr('str')));
		break;

	case 'getGeetest':
		GetGeetest();
		break;

	case 'pinyin':
		PinYin();
		break;

	case 'encPwd':
		EncPwd();
		break;

	case 'getCityData':
		GetCityData();
		break;

	default :
		die('err');
}



// 获取关键字(标签)
function GetKeyWord(){
	$type		= trim(@$_GET['type']);
	$theme		= trim(@$_POST['theme']);
	$content	= trim(@$_POST['content']);
	
	switch ($type){
		case 'dz':
			echo(KeyWord::GetDz($theme, $content, 5));
			break;

		case 'fc':
			echo(KeyWord::GetFc($theme . PHP_EOL . $content, 5, false));
			break;

		default :
			echo(KeyWord::Get($theme));
			break;
	}
}


// 获取验证码结果
function GetGeetest(){
	$geetest = new Geetest();
	$geetest->ShowRes('web');
}


// 中文转为拼音
function PinYin(){
	$str	= OT::GetStr('str');
	$mode	= OT::GetStr('mode');
	echo(base64_encode(htmlspecialchars(PinYin::To($str, $mode, ' '))));
}


// 加密密码
function EncPwd(){
	$str	= OT::GetStr('str');
	$exp	= OT::GetInt('exp');
		if (strlen($str) == 0){ exit; }

	$key = time();

	if ($exp == 30){
		echo('dz|'. $key .'|'. Encrypt::AuthCode(str_replace(array('+','/'), array('-','_'), $str), 'ENCODE', $key .'otcms.com', $exp));
	}else{
		echo('|dz|'. $key .'|'. Encrypt::AuthCode(str_replace(array('+','/'), array('-','_'), $str), 'ENCODE', $key .'otcms.com', $exp) .'|');
	}
}


// 获取城市数据
function GetCityData(){
	$idName		= trim(@$_GET['idName']);
	$prov		= trim(@$_GET['prov']);

	echo(ProvCity::GetCityOptionJs($idName,$prov,''));
}

?>
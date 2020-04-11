<?php
header('Content-Type: text/html; charset=UTF-8');
define('OT_ROOT', dirname(__FILE__) .'/');

include OT_ROOT .'inc/phpqrcode.php';
include OT_ROOT .'inc/classImages.php';


$mode = intval(@$_GET['m']); // 模式1缓存图片，0不缓存
$text = trim(@$_GET['text']); // 二维码内容
$logo = trim(@$_GET['logo']); // 二维码logo图
if (! IsUrl($text)){
	if (strlen($text) > 9 && (substr($text,0,9) == 'weixin://' || substr($text,0,6) == 'wxp://')){
	
	}else{
		die('网址有误.');
	}
}

// 表示容错率，也就是有被覆盖的区域还能识别，分别是 L（QR_ECLEVEL_L，7%），M（QR_ECLEVEL_M，15%），Q（QR_ECLEVEL_Q，25%），H（QR_ECLEVEL_H，30%）； 
$errLevel = trim(@$_GET['level']);
if (strpos('|L|M|Q|H|','|'. $errLevel .'|') === false){ $errLevel = 'H'; }

// 表示生成图片大小，1~10，默认是3；
$imgSize = intval(@$_GET['size']);
if ($imgSize<1){ $imgSize = 10; }

// 表示二维码周围边框空白区域间距值；
$imgMargin = intval(@$_GET['margin']);
if ($imgMargin<1){ $imgMargin = 1; }

if ($mode == 1){
	$pngPath = OT_ROOT .'cache/html/qrcode_'. md5($text . $logo . $errLevel . $imgSize . $imgMargin) .'.png';
	if (file_exists($pngPath)){ echo(file_get_contents($pngPath)); }

	//生成二维码图片
	QRcode::png($text, $pngPath, $errLevel, $imgSize, $imgMargin);

	if (strlen($logo) > 3) {
		$imgWaterArr=array();
		$imgWaterArr['upLoadImg']	= $pngPath;
		$imgWaterArr['waterImg']	= $logo;
		$imgWaterArr['waterPos']	= 'centerMiddle';
		Images::Watermark($imgWaterArr);
	}

	echo(file_get_contents($pngPath));
}else{
	//生成二维码图片
	QRcode::png($text, false, $errLevel, $imgSize, $imgMargin);

}


function IsUrl($strUrl){
//	if (preg_match('#(https|http)://([\w\-]+\.)+[\w\-]+(/[\w\-\./\?%&=]*)?#i',$strUrl)){
	if (preg_match('/(http|ftp|https):\/\/([\w\d\-_]+[\.\w\d\-_]+)[:\d+]?([\/]?[\w\/\.\?=&;%@#\+,]+)/i',$strUrl)){
		return true;
	}else{
		return false;
	}
}

?>
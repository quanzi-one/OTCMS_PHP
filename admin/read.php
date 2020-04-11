<?php
define('OT_adminROOT', dirname(__FILE__) .'/');
define('OT_ROOT', dirname(OT_adminROOT) .'/');
define('OT_Charset',	'utf-8');
header('Content-Type: text/html; charset=UTF-8');


$mudi = trim(@$_GET['mudi']);

switch ($mudi){
	case 'exitTimeDiff':
		ExitTimeDiff();
		break;

	case 'getKeyWord':
		getKeyWord();
		break;

	case 'getSignal':
		GetSignal();
		break;

	case 'getSignalSec':
		GetSignalSec();
		break;

	case 'checkCollUrl':
		CheckCollUrl();
		break;

	case 'announBlank':
		AnnounBlank();
		break;

	case 'announNoUpdate':
		AnnounNoUpdate();
		break;

	case 'creatWeb':
		CreatWeb();
		break;

	case 'selUserBox':
		SelUserBox();
		break;

	case 'createUserBox':
		CreateUserBox();
		break;

	default:
		die('err');
}





// 获取用户超时退出时间差
function ExitTimeDiff(){
	@session_start();

	$sysAdminArr = unserialize(@include(OT_ROOT .'cache/php/sysAdmin.php'));

	if ($sysAdminArr['SA_exitMinute']==0){
		die('999');
	}

	$exitNewTime = time();
	$exitOldTime = intval(@$_SESSION['exitOldTime']);
	die(strval($exitOldTime+($sysAdminArr['SA_exitMinute']*60)-$exitNewTime));
}


function getKeyWord(){
	$type		= trim(@$_GET['type']);
	$theme		= trim(@$_POST['theme']);
	$content	= trim(@$_POST['content']);

	require(OT_ROOT .'inc/classKeyWord.php');
	
	switch ($type){
		case 'dz':
			echo(KeyWord::GetDz($theme, $content, 5));
			break;

		case 'fc':
			echo(KeyWord::GetFc($theme . PHP_EOL . $content, 5));
			break;

		default :
			echo(KeyWord::Get($theme));
			break;
	}
}


// 获取升级网址型号(没用到了，废弃)
function GetSignal(){
	require(OT_ROOT .'inc/classReqUrl.php');

	$signalUrl		= trim(@$_GET['signalUrl']);
	$signalNum		= intval(@$_GET['signalNum']);

	$startTime		= time();
	$retArr	= ReqUrl::UseAuto(0, 'GET', $signalUrl .'?mudi=signal&rnd='. time());
	if ($retArr['res']){
		$endTime = time();
		$diffMsec = intval(($endTime-$startTime)*1000);
		if ($diffMsec <= 300){
			echo('$id("signalImg'. $signalNum .'").innerHTML = \'<img src="images/signal3.gif" alt="信号强('. $diffMsec .'毫秒)" />\';');
		}elseif ($diffMsec <= 800){
			echo('$id("signalImg'. $signalNum .'").innerHTML = \'<img src="images/signal2.gif" alt="信号还行('. $diffMsec .'毫秒)" />\';');
		}elseif ($diffMsec <= 2000){
			echo('$id("signalImg'. $signalNum .'").innerHTML = \'<img src="images/signal1.gif" alt="信号低('. $diffMsec .'毫秒)" />\';');
		}
	}else{
		echo('$id("signalImg'. $signalNum .'").innerHTML = \'<img src="images/signal0.gif" alt="无信号" />\';');
	}
	echo('WindowHeight(0);');
}


// 获取升级网址型号秒数
function GetSignalSec(){
	require(OT_ROOT .'inc/classReqUrl.php');

	$diffMsec = 10000;
	$startTime = microtime();
	$retArr	= ReqUrl::UseAuto(0, 'GET', 'http://php.otcms.com/info.php?rnd='. time());
	if ($retArr['res']){
		$endTime = microtime();
		$diffMsec = intval((floatval($endTime)-floatval($startTime))*1000);
	}
	echo('readOTwebSec='. $diffMsec .';');
}


function CheckCollUrl(){
	require(OT_adminROOT .'inc/classWebHtml.php');

	$urlMode = trim(@$_GET['urlMode']);
	$urlCN = '网钛';
	$urlStr = 'http://check2.otcms.com/info.php';
	switch ($urlMode){
		case 'baidu':
			$urlStr	= 'http://www.baidu.com/';
			$urlCN	= '百度';
			break;
		case '163':
			$urlStr='http://www.163.com/';
			$urlCN = '网易';
			break;
		case 'otcms2':
			$urlStr='http://otcms2.oneti.cn/info.php';
			$urlCN = '网钛2';
			break;
		case 'otcms3':
			$urlStr='http://otcms2.bai35.com/info.php';
			$urlCN = '网钛3';
			break;
		case 'otcms4':
			$urlStr='http://otcms2.otcms.org/info.php';
			$urlCN = '网钛4';
			break;
	}

	$webHtml = new WebHtml();
	$softUserStr = $webHtml->GetCode($urlStr);
	if ($softUserStr != 'False'){
		echo('【'. $urlCN . $urlStr .'】连接正常.');
	}else{
		echo('【'. $urlCN . $urlStr .'】连接失败('. $webHtml->mErrStr .').');
	}
}


function AnnounBlank(){
	$url = trim(@$_GET['url']);

	echo('<center style="margin-top:85px;font-size:14px;">检测到该空间访问官网的网速偏慢，故不自动访问，您可以<a href="'. $url .'">手动刷新访问</a></center>');
}


function AnnounNoUpdate(){
	$url = trim(@$_GET['url']);

	echo('<center style="margin-top:85px;font-size:14px;">系统已关闭该区域自动访问，您可以<a href="'. $url .'">手动刷新访问</a></center>');
}


function CreatWeb(){
	$url = trim(@$_GET['url']);

	echo('<center style="margin-top:85px;font-size:14px;">建设中，敬请期待......</center>');
}


function SelUserBox(){
	$outId			= trim(@$_GET['outId']);
	$outField		= trim(@$_GET['outField']);
	$outMode		= trim(@$_GET['outMode']);

	echo('
	<div style="width:630px;height:50px;background:#e3e2fe;border:1px #b4b0fe solid;padding:5px;margin:5px 0;">
	<input type="hidden" id="selUserResText" name="selUserResText" value="" />
	<input type="hidden" id="outId" name="outId" value="'. $outId .'" />
	<input type="hidden" id="outField" name="outField" value="'. $outField .'" />
	<input type="hidden" id="outMode" name="outMode" value="'. $outMode .'" />
	用户名：<input type="text" id="selUsername" name="selUsername" value="" style="width:90px;" />&ensp;&ensp;
	昵称：<input type="text" id="selRealname" name="selRealname" value="" style="width:90px;" />&ensp;&ensp;
	QQ：<input type="text" id="selQq" name="selQq" value="" style="width:90px;" />&ensp;&ensp;
	旺旺：<input type="text" id="selWw" name="selWw" value="" style="width:90px;" />
	<input type="button" value="查询" onclick="CheckSelUserForm();" />
	<div id="selUserResBox" style="margin-top:5px;"></div>
	</div>
	');
}


function CreateUserBox(){
	require(OT_ROOT .'inc/classOT.php');

	$outId			= trim(@$_GET['outId']);
	$outField		= trim(@$_GET['outField']);
	$outMode		= trim(@$_GET['outMode']);

	echo('
	<div style="width:420px;height:170px;background:#e3e2fe;border:1px #b4b0fe solid;padding:5px;margin:5px 0;line-height:2;">
	<input type="hidden" id="newUserResText" name="newUserResText" value="" />
	<input type="hidden" id="outId" name="outId" value="'. $outId .'" />
	<input type="hidden" id="outField" name="outField" value="'. $outField .'" />
	<input type="hidden" id="outMode" name="outMode" value="'. $outMode .'" />
	<div>
		用户名：<input type="text" id="newUsername" name="newUsername" value="auto'. OT::RndNum(6) .'" style="width:200px;" onblur="CheckUserName(\'newUsername\',this.value)" />
		<span id="newUsernameIsOk"></span>
		<span id="newUsernameStr"></span>
	</div>
	<div>&ensp;&ensp;昵称：<input type="text" id="newRealname" name="newRealname" value="" style="width:200px;" /></div>
	<div>&ensp;&ensp;邮箱：<input type="text" id="newMail" name="newMail" value="" style="width:200px;" />&ensp;<input type="button" value="使用QQ邮箱" onclick="CreateUserQQmail()" /></div>
	<div>&ensp;&ensp;&ensp;&ensp;QQ：<input type="text" id="newQq" name="newQq" value="" style="width:200px;" /></div>
	<div>&ensp;&ensp;旺旺：<input type="text" id="newWw" name="newWw" value="" style="width:200px;" /></div>
	<div>&ensp;&ensp;手机：<input type="text" id="newPhone" name="newPhone" value="" style="width:200px;" /></div>
	<input type="button" value=" 创建临时用户 " onclick="CheckCreateUserForm();" style="margin-left:48px;" />
	<div id="newUserResBox" style="margin-top:5px;"></div>
	</div>
	');
}
?>
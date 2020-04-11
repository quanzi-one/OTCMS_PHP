<?php
require(dirname(__FILE__) .'/conobj.php');



switch ($mudi){
	case 'checkInfoRepeatTheme':
		CheckInfoRepeatTheme();
		break;

	case 'pinyin':
		PinYin();
		break;

	case 'encPwd':
		EncPwd();
		break;

	case 'sendPhoneForm':
		SendPhoneForm();
		break;

	case 'changyan':
		// 畅言登录和退出
		AppChangyan::Login();
		break;

	case 'chk':
		ChkWeb();
		break;

	default :
		die('err');
}

$DB->Close();



// 检测文章重复标题
function CheckInfoRepeatTheme(){
	global $DB;

	$dataID		= OT::GetInt('dataID');
	$theme		= OT::GetStr('theme');
	$themeMd5	= md5($theme);
		if ($dataID>0){ $whereIdStr = ' and IF_ID not in ('. $dataID .')'; }else{ $whereIdStr = ''; }

	$checkexe=$DB->query("select IF_ID from ". OT_dbPref ."info where IF_themeMd5='". $themeMd5 ."'". $whereIdStr);
		if ($checkexe->fetch()){
			echo('抱歉，该文章标题已存在。');
		}else{
			echo('恭喜，该文章标题未被占用。');
		}
	unset($checkexe);

}


// 中文转为拼音（转到 read.php，后期删除）
function PinYin(){
	$str	= OT::GetStr('str');
	$mode	= OT::GetStr('mode');
	echo(base64_encode(htmlspecialchars(PinYin::To($str, $mode, ' '))));
}


// 加密密码（转到 read.php，后期删除）
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


// 发送验证码表单
function SendPhoneForm(){
	$type		= OT::GetStr('type');
	$btnId		= OT::GetStr('btnId');
	$phone		= OT::GetStr('phone');
	$username	= OT::GetStr('username');

	echo('
	<div style="margin:0 auto;text-align:center;font-size:18px;font-weight:bold;padding-bottom:25px;">发送短信验证码</div>
	<form id="phoneForm" name="phoneForm" method="post" action="users_deal.php?mudi=phoneSend" onsubmit="return SendPhoneForm();">
	<input type="hidden" id="sendType" name="sendType" value="'. $type .'" />
	<input type="hidden" id="sendBtnId" name="sendBtnId" value="'. $btnId .'" />
	<input type="hidden" id="sendPhone" name="sendPhone" value="'. $phone .'" />
	<input type="hidden" id="sendUsername" name="sendUsername" value="'. $username .'" />
	<table cellpadding="3" >
	<tr><td align="right">手机号：</td><td>'. $phone .'</td></tr>
	<tr><td align="right">验证码：</td><td>'. Area::VerCodePop('phoneForm','','260px') .'</td></tr>
	<tr><td></td><td><input type="submit" value="确定发送" style="height:35px;font-size:14px;" /><input type="button" value="取消发送" onclick="HiddenMengceng();$id(\''. $btnId .'\').value = \'发送短信验证码\';" style="margin-left:35px;height:35px;font-size:14px;" /></td></tr>
	</table>
	</form>
	');
}


// 检测环境 |username|userpwd|usermail|usercall|scoreStr|recomId
function ChkWeb(){
	echo('
	会员session信息：'. (isset($_SESSION[OT_SiteID .'userID']) ? '存在' : '不存在') .'
	（id：'. strlen(@$_SESSION[OT_SiteID . 'userID']) .'；name：'. strlen(@$_SESSION[OT_SiteID . 'username']) .'；pwd：'. strlen(@$_SESSION[OT_SiteID . 'userpwd']) .'；mail：'. strlen(@$_SESSION[OT_SiteID . 'usermail']) .'；nick：'. strlen(@$_SESSION[OT_SiteID . 'usercall']) .'；scoreStr：'. strlen(@$_SESSION[OT_SiteID . 'scoreStr']) .'；recomId：'. strlen(@$_SESSION[OT_SiteID . 'recomId']) .'）
	<br />
	会员cookies信息：'. (isset($_COOKIE[OT_SiteID .'userID']) ? '存在' : '不存在') .'
	（id：'. strlen(@$_COOKIE[OT_SiteID . 'userID']) .'；name：'. strlen(@$_COOKIE[OT_SiteID . 'username']) .'；mail：'. strlen(@$_COOKIE[OT_SiteID . 'usermail']) .'；nick：'. strlen(@$_COOKIE[OT_SiteID . 'usercall']) .'；scoreStr：'. strlen(@$_COOKIE[OT_SiteID . 'scoreStr']) .'；recomId：'. strlen(@$_COOKIE[OT_SiteID . 'recomId']) .'；info：'. strlen(@$_COOKIE[OT_SiteID . 'userInfo']) .'）
	<br />
	其他cookies信息：wap_otcms='. @$_COOKIE['wap_otcms'] .'<br /><pre>'. print_r($_COOKIE,true) .'</pre>
	<script language="javascript" type="text/javascript">
	document.write("浏览器cookies信息：<br />"+ document.cookie.replace(/; /g,"; <br />"));
	</script>
	
	');
	
}
?>
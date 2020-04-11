<?php
define('OT_ROOT', dirname(dirname(__FILE__)) ."/");

header('Content-Type: text/html; charset=UTF-8');

require(OT_ROOT .'config.php');


/* **** 版权所有 ****

 网钛CMS(OTCMS)
 程序开发：网钛科技
 官方网站：http://otcms.com
 联系ＱＱ：877873666
 联系邮箱：877873666@qq.com

 **** 版权所有 **** */


$systemArr = unserialize(@include(OT_ROOT .'cache/php/system.php'));
if ($systemArr['SYS_verCodeMode'] > 10){ $systemArr['SYS_verCodeMode'] = 1; }

?>

<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo(OT_Charset); ?>" /><!-- big5  gb2312 gbk -->
	<title>后台登录</title>
	<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
	<meta name="author" content="网钛科技">
	<meta name="robots" content="none">

	<link href='style.css' type='text/css' rel='stylesheet' />

	<script language='javascript' type='text/javascript' src='js/inc/jquery.min.js'></script>
	<script language='javascript' type='text/javascript' src='js/inc/common.js'></script>
	<script language='javascript' type='text/javascript' src='js/index.js?v=3.80'></script>
	<script language='javascript' type='text/javascript' src='js/Particleground.js'></script>

	<script language='javascript' type='text/javascript' charset='gb2312' src='tools/keyBoard/vkboard.js'></script>
	<noscript><iframe src='*.htm'></iframe></noscript><!-- 防止另存为 -->

	<style>
	html{height:100%;}
	body{height:100%;background:#fff;overflow:hidden;}
	canvas{z-index:-1;position:absolute;}
	</style>

	<script language='javascript' type='text/javascript'>
	/* $(document).ready(function() {
		$('body').particleground({
			dotColor: '#b3c7e0',
			lineColor: '#b1c6e0'
		});
	}); */

	SYS_verCodeMode = <?php echo(intval($systemArr['SYS_verCodeMode'])); ?>;
	webPathPart = "../";
	geetWidth = "260px";
	</script>
</head>

<body leftmargin='0' topmargin='0' style='margin:0px; margin-top:0px; margin-left:0px;'>

<div style="padding-top:80px;"></div>
<form id="loginForm" name="loginForm" method="post" action="admin_cl.php?mudi=login" onsubmit="return CheckLoginForm()">
<input type="hidden" id="pwdMode" name="pwdMode" value="" />
<input type="hidden" id="pwdKey" name="pwdKey" value="" />
<input type="hidden" id="pwdEnc" name="pwdEnc" value="" />
<table style="width:489px; height:314px; background:url('images/login/login.jpg')" align="center" cellpadding="0" cellspacing="0">
<tr>
	<td valign="top" style="padding-top:95px;">
		<table align="center" class="padd5">
		<tr>
			<td style='width:110px' rowspan="3"></td>
			<td align="right" class="fontF_2">用户名：</td>
			<td align="left"><input type="text" style="width:210px" id="username" name="username" maxlength="20" onkeyup="if (this.value!=FiltVarStr(this.value)){this.value=FiltVarStr(this.value)}" /></td>
		</tr>
		<tr>
			<td align="right" class="fontF_2">密&ensp;&ensp;码：</td>
			<td align="left"><input type="password" style="width:210px" id="userpwd" name="userpwd" maxlength="50"/><img src="images/keyBoard.gif" alt="开启/关闭密码输入器" class="pointer" id="vkb_img"><br /><div id="vkeyboard"></div></td>
		</tr>		
		<tr style="height:30px;">
			<td align="right" class="fontF_2">验证码：</td>
			<td align="left">
			<?php
			if (OT_OpenVerCode && strpos($systemArr['SYS_verCodeStr'],'|admin|') !== false){
				echo(ShowVerCode('loginForm','verCode'));

			}else{
				echo('<span class="font2_2">已关闭</span>');
			}
			?>
			</td>
		</tr>
		</table>
		<br />
		<center>
			<input type="image" src="images/login/login_submit.gif" />
			&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;
			<img src="images/login/login_reset.gif" style="cursor:pointer" onclick="$id('loginForm').reset()" alt="" />
		</center>
	</td>
</tr>
<tr>
	<td align="center" style="height:27px;" valign="top" class="fontMenu">
		技术支持：<a class="fontMenu" href="http://otcms.com/" target="_blank" title="福州网钛软件科技有限公司">福州网钛软件科技有限公司</a>
	</td>
</tr>
</table>
</form>


<center>
<div style="margin-top:15px;">
	<a href="../" class="font1_1">★回到前台首页</a>
	&ensp;&ensp;&ensp;&ensp;
	<a href="http://otcms.com/news/7851.html" class="font1_1" target="_blank">★忘记后台密码</a>
	&ensp;&ensp;&ensp;&ensp;
	<a href="http://otcms.com/news/8101.html" class="font1_1" target="_blank">★搬家后数据库异常</a>
</div>
</center>

	<script language='javascript' type='text/javascript'>
	createKeyboard("vkeyboard","userpwd","vkb_img");
	</script>


<?php

function ShowVerCode($svcForm,$svcId=''){
	if (OT_OpenVerCode){
		global $systemArr;

		if ($systemArr['SYS_verCodeMode'] == 20){
			return '<div id="geetestDiv"></div>
					<p id="geetestWait" style="color:blue;">正在加载验证码......</p>
					<p id="geetestNote" style="display:none;">请先完成验证</p>
					<script language="javascript" type="text/javascript" src="../tools/geetest/gt.js"></script>
					';
		}else{
			if (empty($svcId)){ $svcId='verCode'; }
			return '<input type="text" id="'. $svcId .'" name="verCode" maxlength="16" class="text" style="width:60px;" onfocus=\'GetVerCode("input")\' title="如看不清验证码，可以点击验证码进行更换" />&ensp;&ensp;<span id="showVerCode" class="font2_2" onclick=\'GetVerCode("font")\' style="cursor:pointer;">点击获取验证码</span>';
		}
	}else{
		return '<span class="font2_2">已关闭</span>';
	}
}

?>

</body>
</html>

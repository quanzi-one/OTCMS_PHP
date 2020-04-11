<?php
// 初始化变量及引入初始文件
$dbPathPart		= '';
$webPathPart	= '';
$jsPathPart		= '';

require(dirname(__FILE__) .'/check.php');
require(OT_ROOT .'cache/php/userApi.php');

$userSysArr = Cache::PhpFile('userSys');

	if ($mudi == ''){
		UsersWeb::DefWeb();
		exit();
	}


if ($userSysArr['US_isUserSys'] == 0){
	die('<br /><br /><center>会员系统已关闭，有问题请联系管理员</center>');
}


require(OT_ROOT .'inc/classTemplate.php');
$tpl = new Template;

// 初始化公共变量
$tpl->webTypeName	= 'users';
$tpl->webTitle		= '';
$tpl->webKey		= '*';
$tpl->webDesc		= '*';


switch ($mudi){
	case 'reg':
		$webTitle	= '会员注册';
		$webContent = UsersWeb::RegWeb();
		break;

	case 'login':
		$webTitle	= '会员登录';
		$webContent = UsersWeb::LoginWeb();
		break;

	case 'missPwd':
		$webTitle	= '忘记密码';
		$webContent = UsersWeb::MissPwdWeb();
		break;

	default :
		die('no mudi');
		break;
}

$tpl->areaName		= $webTitle;
$tpl->webTitle		= str_replace(array('{%标题附加%}','{%标题%}'), array('',$webTitle), $systemArr['SYS_titleWeb']);


// 解析页面
$tpl->WebTop();
$tpl->WebBottom();


$webTop = '
	<ul>
	<li style="border:none;">欢迎您</li>
	<li><a href="users.php?mudi=reg&force=1">用户注册</a></li>
	<li><a href="users.php?mudi=login&force=1">用户登录</a></li>
	'. ($userSysArr['US_isMissPwd'] == 1 ? '<li><a href="users.php?mudi=missPwd">找回密码</a></li>' : '') .'
	<li><a href="./">网站首页</a></li>
	</ul>
	';

$webMenu = '
	<script language="javascript" type="text/javascript" src="js/users.js?v='. OT_VERSION .'"></script>

	<ul class="menu">
	<li>
		<div class="item"><i class="fa fa-dashboard fa-fw"></i> 菜单导航<i class="arrow"></i></div>
		<ul class="sub">
		<li><a href="users.php?mudi=reg&force=1">用户注册</a></li>
		<li><a href="users.php?mudi=login&force=1">用户登录</a></li>
		'. ($userSysArr['US_isMissPwd'] == 1 ? '<li><a href="users.php?mudi=missPwd">找回密码</a></li>' : '') .'
		<li><a href="./">官网首页</a></li>
		</ul>
	</li>
	</ul>
	</div>
	';

$tpl->Add('areaName',		$tpl->areaName);
$tpl->Add('webTop',			$webTop);
$tpl->Add('webMenu',		$webMenu);
$tpl->Add('webContent',		$webContent);


$tpl->Show('usersCenter.html');



class UsersWeb{
	// 默认页
	public static function DefWeb(){
		global $userSysArr,$userApiArr;

		if ($userSysArr['US_isLogin'] == 1){
			$username = Users::Username();
			$usercall = Users::Usercall();
			if ($username == ''){
				$missPwdStr = '';
				if ($userSysArr['US_isMissPwd'] == 1){
					$missPwdStr = '
						<div class="left" style="padding:0 6px;">/</div>
						<div id="uebox_miss" class="left" style="padding-right:12px;"><a href="./users.php?mudi=missPwd" class="font2_1" >忘记密码</a></div>
						';
				}
				echo('
				<div id="uebox_img" class="left">'. @$userApiArr['imgTop'] .'</div>
				<div id="uebox_login" class="left"><a href="./users.php?mudi=login" class="font2_1" >登录</a></div>
				<div class="left" style="padding:0 6px;">/</div>
				<div id="uebox_reg" class="left"><a href="./users.php?mudi=reg" class="font2_1" >注册</a></div>		
				'. $missPwdStr);
			}else{
				if (strlen($usercall)>0){ $showName = $usercall; }else{ $showName = $username; }
				echo('
				<div class="left font1_1">
					<div id="uebox_welcome" class="left">欢迎您，<b>'. $showName .'</b></div>
					<div id="uebox_center" class="left" style="padding-left:20px;"><a href="./usersCenter.php?mudi=userCenter" class="font2_1" >[会员中心]</a></div>
					'. AppQiandao::UminiMenu() .'
					'. UsersNews::UminiMenu() .'
					<div id="uebox_exit" class="left" style="padding:0 12px 0 8px;"><a href="javascript:void(0);" class="font2_1" onclick=\'UserExit();return false;\' >[退出]</a></div>
				</div>
				');
			}
		}
	}



	// 注册页
	public static function RegWeb(){
		global $userSysArr,$userApiArr;

		$announ = '';
		if (strlen($userSysArr['US_regAnnoun']) > 0){
			$announ = '<div class="loginBox" style="width:400px;padding:20px;line-height:1.4;">'. $userSysArr['US_regAnnoun'] .'</div>';
		}

		if ($userSysArr['US_isReg'] == 0){
			return '<div style="margin:0 auto; padding:35px 0 20px 0; text-align:center;">会员注册已关闭，有问题请联系管理员</div>'. $announ;
		}

		$backURL		= OT::GetStr('backURL');
		$isBack			= OT::GetInt('isBack');
		$recomId		= OT::GetInt('id');
			if ($isBack == 1){ $backURL = $_SERVER['HTTP_REFERER']; }
			if ($recomId > 0){
				Users::SetCookies('recomId', $recomId);
			}else{
				$recomId = OT::ToInt(Users::GetCookies('recomId'));
			}

		$username = Users::Username();
		if ($username != ''){
			$force		= OT::GetInt('force');
			if ($force == 1){
				$userRow = Users::Open('get','','',$judUserErr);
					if ((! $userRow) || $judUserErr != ''){
						
					}else{
						JS::HrefEnd('usersCenter.php');
					}
			}else{
				JS::HrefEnd('usersCenter.php');
			}
		}

		$addiFieldArr = array();
		if (strpos($userSysArr['US_regFieldStr'], '|邮箱|') !== false){
			if (strpos($userSysArr['US_regFieldStr'], '|邮箱必填|') !== false){ $btStr = ''; }else{ $btStr = '[可选] '; }
			$addiFieldArr[$btStr .'95'] = '
				<div class="input">
					<input type="text" id="mail" name="mail" class="text mail" onblur="CheckMail()" placeholder="'. $btStr .'请输入邮箱" title="'. $btStr .'请输入邮箱" />
					<span id="mailIsOk"></span>
					<span id="mailStr" class="font2_1"></span>
				</div>
				'. ($userSysArr['US_regAuthMail']==1 ? '
				<div class="input">
					<input type="text" id="mailCode" name="mailCode" class="text verCode2" placeholder="请输入邮箱验证码" title="请输入邮箱验证码" style="width:167px;display:inline;" /><input type="button" id="sendMail" value="发送邮件验证码" style="height:43px;margin-left:2px;" onclick=\'SendMailCode(this.id,"mail","check","username");\' />
				</div>
				' : '');
		}
		if (strpos($userSysArr['US_regFieldStr'], '|手机|') !== false){
			if (strpos($userSysArr['US_regFieldStr'], '|手机必填|') !== false){ $btStr = ''; }else{ $btStr = '[可选] '; }
			$addiFieldArr[$btStr .'90'] = '
				<div class="input">
					<input type="text" id="phone" name="phone" class="text phone" onblur="CheckPhone()" placeholder="'. $btStr .'请输入手机" title="'. $btStr .'请输入手机" />
					<span id="phoneIsOk"></span>
					<span id="phoneStr" class="font2_1"></span>
				</div>
				'. ($userSysArr['US_regAuthPhone']==1 ? '
				<div class="input">
					<input type="text" id="phoneCode" name="phoneCode" class="text verCode2" placeholder="请输入短信验证码" title="请输入短信验证码" style="width:167px;display:inline;" /><input type="button" id="sendPhone" value="发送短信验证码" style="height:43px;margin-left:2px;" onclick=\'SendPhoneCode(this.id,"phone","check","username");\' />
				</div>
				' : '');
		}
		if (strpos($userSysArr['US_regFieldStr'], '|昵称|') !== false){
			if (strpos($userSysArr['US_regFieldStr'], '|昵称必填|') !== false){ $btStr = ''; }else{ $btStr = '[可选] '; }
			$addiFieldArr[$btStr .'80'] = '
				<div class="input">
					<input type="text" id="realname" name="realname" class="text realname" placeholder="'. $btStr .'请输入昵称（QQ网名或姓名）" title="'. $btStr .'请输入昵称（QQ网名或姓名）" />
				</div>
				';
		}
		if (strpos($userSysArr['US_regFieldStr'], '|QQ|') !== false){
			if (strpos($userSysArr['US_regFieldStr'], '|QQ必填|') !== false){ $btStr = ''; }else{ $btStr = '[可选] '; }
			$addiFieldArr[$btStr .'70'] = '
				<div class="input">
					<input type="text" id="qq" name="qq" class="text qq" placeholder="'. $btStr .'请输入QQ" title="'. $btStr .'请输入QQ" />
				</div>
				';
		}
		if (strpos($userSysArr['US_regFieldStr'], '|微信|') !== false){
			if (strpos($userSysArr['US_regFieldStr'], '|微信必填|') !== false){ $btStr = ''; }else{ $btStr = '[可选] '; }
			$addiFieldArr[$btStr .'60'] = '
				<div class="input">
					<input type="text" id="weixin" name="weixin" class="text weixin" placeholder="'. $btStr .'请输入微信" title="'. $btStr .'请输入微信" />
				</div>
				';
		}
		if (strpos($userSysArr['US_regFieldStr'], '|旺旺|') !== false){
			if (strpos($userSysArr['US_regFieldStr'], '|旺旺必填|') !== false){ $btStr = ''; }else{ $btStr = '[可选] '; }
			$addiFieldArr[$btStr .'50'] = '
				<div class="input">
					<input type="text" id="ww" name="ww" class="text ww" placeholder="'. $btStr .'请输入旺旺" title="'. $btStr .'请输入旺旺" />
				</div>
				';
		}
		krsort($addiFieldArr);

		$rndMd5 = md5(md5(OT_SiteID . session_id()) . OT_SiteID . Users::GetIp());
		return '
		<div class="loginBox">
			<h2>用户注册</h2>
			<form id="regForm" name="regForm" method="post" action="users_deal.php?mudi=reg" onsubmit="return CheckRegForm();">
			<input type="hidden" id="rndMd5" name="rndMd5" value="'. $rndMd5 .'" />
			<input type="hidden" id="backURL" name="backURL" value="'. urlencode($backURL) .'" />
			<input type="hidden" id="pwdMode" name="pwdMode" value="" />
			<input type="hidden" id="pwdKey" name="pwdKey" value="" />
			<input type="hidden" id="pwdEnc" name="pwdEnc" value="" />
			<input type="hidden" id="recomId" name="recomId" value="'. $recomId .'" />
			<input type="hidden" id="judRegCode" name="judRegCode" value="'. (AppQuan::JudReg()?1:0) .'" />
			<input type="hidden" id="regFieldStr" name="regFieldStr" value="'. $userSysArr['US_regFieldStr'] .'" />

			'. AppQuan::RegItem() .'
			<div class="input">
				<input type="text" id="username" name="username" class="text username" placeholder="请输入用户名" onblur="CheckUserName(this.value)" title="请输入用户名" />
				<span id="usernameIsOk"></span>
				长度4~16字节，仅允许 数字、英文、汉字、_
				<div id="usernameStr"></div>
			</div>
			<div class="input">
				<input type="password" id="userpwd" name="userpwd" class="text userpwd" placeholder="请输入密码" onblur="CheckUserPwd()" title="请输入密码" />
				<span id="userpwdIsOk"></span>
				长度6-32位
				<div id="userpwdStr" class="font2_1"></div>
			</div>
			<div class="input">
				<input type="password" id="userpwd2" name="userpwd2" class="text userpwd" placeholder="再输入密码" onblur="CheckUserPwd2()" title="再输入密码" />
				<span id="userpwd2IsOk"></span>
				<div id="userpwd2Str" class="font2_1"></div>
			</div>
			'. implode('',$addiFieldArr) .'
			<!-- <div class="input">
				<input type="text" id="recomUser" name="recomUser" class="text username" placeholder="请输入推荐人" title="请输入推荐人" />
			</div> -->
			<!-- <div class="input">
				<input type="text" id="question" name="question" class="text realname" placeholder="请输入密保问题" onblur="CheckQuestion()" title="请输入密保问题" />
				<span id="questionIsOk"></span>
				长度2~50字节，仅允许 数字、英文、汉字、_
				<div id="questionStr" class="font2_1"></div>
			</div>
			<div class="input">
				<input type="text" id="answer" name="answer" class="text realname" placeholder="请输入密保答案" onblur="CheckAnswer()" title="请输入密保答案" />
				<span id="answerIsOk"></span>
				长度2~50字节
				<div id="answerStr" class="font2_1"></div>
			</div> -->
			<div class="input">
				<div style="float:left;">注册协议：</div>
				<div id="regNoteK" class="regNote font1_1" style="float:left;display:none;">
					'. $userSysArr["US_regNote"] .'
				</div>
				<label><input type="radio" name="isAgree" value="yes" style="background:none;" />同意</label>
				&ensp;&ensp;
				<label><input type="radio" name="isAgree" value="no" style="background:none;" />不同意</label>
				&ensp;&ensp;
				<a href="javascript:void(0);" class="font3_1" onclick="ClickShowHidden(\'regNoteK\');return false;">[查看内容]</a>
			</div>
			<div class="input">
				'. Area::VerCodeH5('regForm') .'
			</div>
			<div class="input">
				<button class="subBtn">立 即 注 册  <i class="fa fa-arrow-right"></i></button>
			</div>
			</form>
			<div class="bottLine"></div>
			<div class="otherLogin">
				<p>使用合作网站账号登录：</p>
				'. @$userApiArr['imgLogin'] .'
			</div>
		</div>
		'. $announ;
	}



	// 登录页
	public static function LoginWeb(){
		global $DB,$systemArr,$userSysArr,$userApiArr;

		$announ = '';
		if (strlen($userSysArr['US_loginAnnoun']) > 0){
			$announ = '<div class="loginBox" style="width:400px;padding:20px;line-height:1.4;">'. $userSysArr['US_loginAnnoun'] .'</div>';
		}

		if ($userSysArr['US_isLogin'] == 0){
			return '<div style="margin:0 auto; padding:35px 0 20px 0; text-align:center;">会员登录已关闭，有问题请联系管理员</div>'. $announ;
		}

		$backURL	= OT::GetStr('backURL');
		$isBack		= OT::GetInt('isBack');
			if ($isBack == 1){ $backURL = $_SERVER['HTTP_REFERER']; }
		$mode		= OT::GetStr('mode');
			if ($mode == 'wxmp' && AppWeixin::Jud()){
				return AppWeixin::Login($announ);
			}

		$username = Users::Username();
		if ($username != ''){
			$force		= OT::GetInt('force');
			if ($force == 1){
				$userRow = Users::Open('get','','',$judUserErr);
					if ((! $userRow) || $judUserErr != ''){
						
					}else{
						JS::HrefEnd('usersCenter.php');
					}
			}else{
				JS::HrefEnd('usersCenter.php');
			}
		}

		if (empty($_SESSION['VerCodeNum'])){ $_SESSION['VerCodeNum']=0; }
		if ($_SESSION['VerCodeNum'] >= 2 || strpos($systemArr['SYS_verCodeStr'],'|login|') !== false){ $verCodeStyle=''; }else{ $verCodeStyle='none'; }

		$expTimeStr = '';
		if ($userSysArr['US_isLoginExp'] > 0){
			$expTimeStr = '
				<div class="input">
					<label><input type="checkbox" name="expTime" value="'. $userSysArr['US_isLoginExp'] .'" />登录状态保存'. StrCN::LoginExp($userSysArr['US_isLoginExp']) .'</label>
				</div>';
		}

		$qrCodeStr = '';
		if (AppWeixin::Jud()){
			if ($DB->GetOne('select count(UA_ID) from '. OT_dbPref .'userApi where UA_state=1 and UA_appType="wxmp"') > 0){
				$qrCodeStr = '<div style="position:relative;"><a href="?mudi=login&mode=wxmp" title="微信扫码登录" style="position:absolute;left:318px;top:-18px;"><img src="inc_img/loginQrcode.png" /></a></div>';
			}
		}

		$loginModeCurr = '';
		$loginModeArr = array();
		if ($userSysArr['US_isLoginUser'] == 1){
			$loginModeArr[] = array('rank'=>$userSysArr['US_loginUserRank'], 'type'=>'username', 'name'=>'用户名登录');
		}
		if ($userSysArr['US_isLoginMail'] >= 1 && AppLoginMail::Jud()){
			$loginModeArr[] = array('rank'=>$userSysArr['US_loginMailRank'], 'type'=>'mail', 'name'=>'邮箱登录');
		}
		if ($userSysArr['US_isLoginPhone'] >= 1 && AppLoginPhone::Jud()){
			$loginModeArr[] = array('rank'=>$userSysArr['US_loginPhoneRank'], 'type'=>'phone', 'name'=>'手机登录');
		}
		$rankArr = array();
		foreach ($loginModeArr as $key => $val){
			$rankArr[$key]  = $val['rank'];
		}
		array_multisort($rankArr, SORT_ASC, SORT_NUMERIC, $loginModeArr);
		$loginModeStr = '';
		foreach ($loginModeArr as $item){
			if (strlen($loginModeStr) == 0){
				$loginModeCurr = $item['type'];
				$loginModeStr .= '
					<div id="login_'. $item['type'] .'" class="pointCurr" onclick="LoginModeTab(\''. $item['type'] .'\')">'. $item['name'] .'</div>
					';
			}else{
				$loginModeStr .= '
					<div class="pointSpace">&ensp;</div>
					<div id="login_'. $item['type'] .'" class="pointItem" onclick="LoginModeTab(\''. $item['type'] .'\')">'. $item['name'] .'</div>
					';
			}
		}

		return '
		<div class="loginBox">
			'. $qrCodeStr . $loginModeStr .'
			<div class="clr"></div>
			<form id="loginForm" name="loginForm" method="post" action="users_deal.php?mudi=login" onsubmit="return CheckLoginForm();">
			<input type="hidden" id="backURL" name="backURL" value="'. urlencode($backURL) .'" />
			<input type="hidden" id="loginMode" name="loginMode" value="" />
			<input type="hidden" id="loginPwd" name="loginPwd" value="pwd" />
			<input type="hidden" id="loginMailMode" name="loginMailMode" value="'. $userSysArr['US_loginMailMode'] .'" />
			<input type="hidden" id="loginPhoneMode" name="loginPhoneMode" value="'. $userSysArr['US_loginPhoneMode'] .'" />
			<input type="hidden" id="pwdMode" name="pwdMode" value="" />
			<input type="hidden" id="pwdKey" name="pwdKey" value="" />
			<input type="hidden" id="pwdEnc" name="pwdEnc" value="" />
			<div class="input" id="input_username">
				<input type="text" id="username" name="username" class="text username" placeholder="请输入用户名" autocomplete="off" title="请输入用户名" />
			</div>
			<div class="input" id="input_mail" style="display:none;">
				<input type="text" id="mail" name="mail" class="text mail" onblur="CheckMail()" placeholder="请输入邮箱" title="请输入邮箱" />
				<span id="mailIsOk"></span>
				<span id="mailStr" class="font2_1"></span>
			</div>
			<div class="input" id="input_mailCode" style="display:none;">
				<input type="text" id="mailCode" name="mailCode" class="text verCode2" placeholder="请输入邮箱验证码" title="请输入邮箱验证码" style="width:167px;display:inline;" /><input type="button" id="sendMail" value="发送邮件验证码" style="height:43px;margin-left:2px;" onclick=\'SendMailCode(this.id,"mail","login","");\' />
				'. ($userSysArr['US_loginMailMode']==2 ? '<div id="btn_mailPwd" class="inputBtn" onclick="LoginInputBtn(\'mailPwd\')">使用密码登录</div>' : '') .'
			</div>
			<div class="input" id="input_phone" style="display:none;">
				<input type="text" id="phone" name="phone" class="text phone" onblur="CheckPhone()" placeholder="请输入手机" title="请输入手机" />
				<span id="phoneIsOk"></span>
				<span id="phoneStr" class="font2_1"></span>
			</div>
			<div class="input" id="input_phoneCode" style="display:none;">
				<input type="text" id="phoneCode" name="phoneCode" class="text verCode2" placeholder="请输入短信验证码" title="请输入短信验证码" style="width:167px;display:inline;" /><input type="button" id="sendPhone" value="发送短信验证码" style="height:43px;margin-left:2px;" onclick=\'SendPhoneCode(this.id,"phone","login","");\' />
				'. ($userSysArr['US_loginPhoneMode']==2 ? '<div id="btn_phonePwd" class="inputBtn" onclick="LoginInputBtn(\'phonePwd\')">使用密码登录</div>' : '') .'
			</div>
			<div class="input" id="input_pwd">
				<input type="password" id="userpwd" name="userpwd" class="text userpwd" placeholder="请输入密码" autocomplete="off" title="请输入密码" />
				'. ($userSysArr['US_loginMailMode']==2 ? '<div id="btn_mailCode" class="inputBtn" style="display:none;" onclick="LoginInputBtn(\'mailCode\')">使用邮件验证码登录</div>' : '') .'
				'. ($userSysArr['US_loginPhoneMode']==2 ? '<div id="btn_phoneCode" class="inputBtn" style="display:none;" onclick="LoginInputBtn(\'phoneCode\')">使用短信验证码登录</div>' : '') .'
			</div>
			<div class="input" id="verCodeK" style="display:'. $verCodeStyle .'">
				'. Area::VerCodeH5('loginForm') .'
			</div>
			'. $expTimeStr .'
			<div class="input">
				<button class="subBtn">立 即 登 录  <i class="fa fa-arrow-right"></i></button>
				<div class="bottom">
					<label class="reg"><a href="users.php?mudi=reg&force=1">用户注册</a></label>
					'. ($userSysArr['US_isMissPwd'] == 1 ? '<label class="missPwd"><a href="users.php?mudi=missPwd">忘记密码?</a></label>' : '') .'
				</div>
			</div>
			</form>
			<div class="bottLine"></div>
			<div class="otherLogin">
				<p>使用合作网站账号登录：</p>
				'. @$userApiArr['imgLogin'] .'
			</div>
		</div>
		'. $announ .'

		<script language="javascript" type="text/javascript">
		LoginModeTab("'. $loginModeCurr .'");
		</script>
		';

	}



	// 忘记密码
	public static function MissPwdWeb(){
		global $userSysArr;

		if ($userSysArr['US_isMissPwd'] == 0){
			return '<div style="width:500px;text-align:center;margin:0 auto;padding:30px;font-size:16px;">忘记密码找回功能已关闭，如有问题联系管理员。</div>';
		}

		$itemStr = '';
		$itemNum = 0;
		if ($userSysArr['US_isMissPwdUser'] == 1){
			$itemNum ++;
			$itemStr .= '<label><input type="radio" id="refType_username" name="refType" value="用户名" onclick="MissPwdType()" checked="checked" />用户名</label>&ensp;&ensp;';
		}
		if ($userSysArr['US_isMissPwdMail'] == 1 && AppMail::Jud()){
			$itemNum ++;
			$itemStr .= '<label><input type="radio" id="refType_mail" name="refType" value="邮箱" onclick="MissPwdType()" '. ($itemNum==1?'checked="checked"':'') .' />邮箱</label>&ensp;&ensp;';
		}
		if ($userSysArr['US_isMissPwdPhone'] == 1 && AppPhone::Jud()){
			$itemNum ++;
			$itemStr .= '<label><input type="radio" id="refType_phone" name="refType" value="手机" onclick="MissPwdType()" '. ($itemNum==1?'checked="checked"':'') .' />手机</label>&ensp;&ensp;';
		}
		return '
		<div class="loginBox">
			<h2>找回密码</h2>
			<form id="missForm" name="missForm" method="post" action="users_deal.php?mudi=missPwd" onsubmit="return CheckMissPwdForm();">
			<div class="input">
				查找方式：'. $itemStr .'
			</div>
			<div class="input" id="usernameBox" style="display:none;">
				<input type="text" id="username" name="username" class="text username" placeholder="请输入用户名" title="请输入用户名" />
			</div>
			<div class="input" id="mailBox" style="display:none;">
				<input type="text" id="mail" name="mail" class="text mail" onblur="CheckMail()" placeholder="请输入邮箱" title="请输入邮箱" />
				<span id="mailIsOk"></span>
				<span id="mailStr" class="font2_1"></span>
			</div>
			<div class="input" id="phoneBox" style="display:none;">
				<input type="text" id="phone" name="phone" class="text phone" onblur="CheckPhone()" placeholder="请输入手机号" title="请输入手机号" />
				<span id="phoneIsOk"></span>
				<span id="phoneStr" class="font2_1" style="display:;"></span>
			</div>
			<div class="input">
				<input type="button" value=" 查 询 " class="subBtn" onclick="MissPwdSend()" />
			</div>
			<div id="questionStr">
			</div>
			</form>
			<script language="javascript" type="text/javascript">MissPwdType();</script>
		</div>
		';

	}
}

?>
<?php
require(dirname(__FILE__) .'/check.php');

if (! in_array($mudi,array('','reg','exit','onlineClear','mailSend','phoneSend'))){
	Area::CheckIsOutSubmit();	//检测是否外部提交
}

$userSysArr = Cache::PhpFile('userSys');



switch ($mudi){
	case 'reg':
		reg();
		break;

	case 'login':
		login();
		break;

	case 'missPwdSend':
		MissPwdSend();
		break;

	case 'missPwd':
		MissPwd();
		break;

	case 'mailSend':
		MailSend();
		break;

	case 'phoneSend':
		PhoneSend();
		break;

	case 'checkUserName':
		Users::CheckUserName('write',OT::GetStr('userName'));
		break;

	case 'onlineClear':
		OnlineClear();
		break;

	default :
		UserExit();
		break;
}

$DB->Close();





// 注册
function reg(){
	global $DB,$systemArr,$userSysArr;

	$pwdMode		= OT::PostStr('pwdMode');
	$pwdKey			= OT::PostStr('pwdKey');

	$regCode		= OT::PostStr('regCode');
	$username		= OT::PostStr('username');
	$userpwd		= OT::PostStr('userpwd');
	$mail			= OT::PostStr('mail');
	$mailCode		= OT::PostStr('mailCode');
	$question		= OT::PostReplaceStr('question','input');
	$othersQuestion	= OT::PostReplaceStr('othersQuestion','input');
		if ($question == 'others'){ $question = $othersQuestion; }
	$answer			= OT::PostStr('answer');
	$realname		= OT::PostRegExpStr('realname','sql');
	$sex			= OT::PostReplaceStr('sex','input');
	$phone			= OT::PostReplaceStr('phone','input');
	$phoneCode		= OT::PostStr('phoneCode');
	$qq				= OT::PostReplaceStr('qq','input');
	$weixin			= OT::PostReplaceStr('weixin','input');
	$ww				= OT::PostReplaceStr('ww','input');
	$web			= OT::PostReplaceStr('web','input');
	$note			= OT::PostReplaceStr('note','input');

	$verCode		= strtolower(OT::PostStr('verCode'));
	$rndMd5			= OT::PostStr('rndMd5');
	$backURL		= urldecode(OT::PostStr('backURL'));
	$isRegApi		= OT::PostInt('isRegApi');
	$apiType		= OT::PostStr('apiType');
	$apiId			= OT::PostReplaceStr('apiId','input');

	if ($userSysArr['US_isRegApi'] == 1 && $isRegApi == 1){
		$jueRegApi = true;
		$username	= $apiType .'_'. substr($apiId,-8) . OT::RndChar(1);
	}else{
		$jueRegApi = false;
	}

	if ($username == '' || ($userpwd=='' && $apiId=='')){
		JS::DiyEnd('alert("表单接收不全");regWaitTime=0;HiddenMengceng();');
	}

	if (! $jueRegApi){
		if (strpos($userSysArr['US_regFieldStr'], '|邮箱|') !== false){
			if (strpos($userSysArr['US_regFieldStr'], '|邮箱必填|') !== false){
				if (strlen($mail) == 0){ JS::DiyEnd('alert("请输入邮箱");$id("mail").focus();regWaitTime=0;HiddenMengceng();'); }
				if (! Is::Mail($mail)){ JS::DiyEnd('alert("邮箱格式错误");ResetVerCode();regWaitTime=0;HiddenMengceng();'); }
			}else{
				if (strlen($mail) > 0 && (! Is::Mail($mail))){ JS::DiyEnd('alert("邮箱格式错误");ResetVerCode();regWaitTime=0;HiddenMengceng();'); }
			}
		}else{
			$mail = '';
		}
		if (strpos($userSysArr['US_regFieldStr'], '|手机|') !== false){
			if (strpos($userSysArr['US_regFieldStr'], '|手机必填|') !== false){
				if (strlen($phone) == 0){ JS::DiyEnd('alert("请输入手机");$id("phone").focus();regWaitTime=0;HiddenMengceng();'); }
				if (! Is::Phone($phone)){ JS::DiyEnd('alert("手机号格式错误，应该1开头，长度11位");ResetVerCode();regWaitTime=0;HiddenMengceng();'); }
			}else{
				if (strlen($phone) > 0 && (! Is::Phone($phone))){ JS::DiyEnd('alert("手机号格式错误，应该1开头，长度11位");ResetVerCode();regWaitTime=0;HiddenMengceng();'); }
			}
		}else{
			$phone = '';
		}
		if (strpos($userSysArr['US_regFieldStr'], '|昵称|') !== false && strpos($userSysArr['US_regFieldStr'], '|昵称必填|') !== false){
			if ($realname==''){ JS::DiyEnd('alert("请输入昵称");$id("realname").focus();regWaitTime=0;HiddenMengceng();'); }
		}
		if (strpos($userSysArr['US_regFieldStr'], '|QQ|') !== false && strpos($userSysArr['US_regFieldStr'], '|QQ必填|') !== false){
			if ($qq==''){ JS::DiyEnd('alert("请输入QQ");$id("qq").focus();regWaitTime=0;HiddenMengceng();'); }
			if (strlen($qq) < 5){ JS::DiyEnd('alert("请输入QQ号不能低于5位数");regWaitTime=0;HiddenMengceng();'); }
		}
		if (strpos($userSysArr['US_regFieldStr'], '|微信|') !== false && strpos($userSysArr['US_regFieldStr'], '|微信必填|') !== false){
			if ($weixin==''){ JS::DiyEnd('alert("请输入微信");$id("weixin").focus();regWaitTime=0;HiddenMengceng();'); }
		}
		if (strpos($userSysArr['US_regFieldStr'], '|旺旺|') !== false && strpos($userSysArr['US_regFieldStr'], '|旺旺必填|') !== false){
			if ($ww==''){ JS::DiyEnd('alert("请输入旺旺");$id("ww").focus();regWaitTime=0;HiddenMengceng();'); }
		}

		if (! (OT_OpenVerCode == false || ($userSysArr['US_isRegApi'] == 1 && $isRegApi == 1))){
			if ($systemArr['SYS_verCodeMode'] == 20){
				$geetest = new Geetest();
				if (! $geetest->IsTrue('web')){
					JS::DiyEnd('alert("验证码错误，请重新点击验证.");ResetVerCode();regWaitTime=0;HiddenMengceng();');
				}
			}else{
				if ($verCode == '' || $verCode != strtolower($_SESSION['VerCode'. $systemArr['SYS_verCodeMode']])){
					JS::DiyEnd('alert("验证码错误.");ChangeCode();regWaitTime=0;HiddenMengceng();');
				}
				$_SESSION['VerCode'. $systemArr['SYS_verCodeMode']] = '';
			}
		}
	}

	if ($userSysArr['US_isUserSys'] == 0){ JS::DiyEnd('alert("会员系统已关闭");regWaitTime=0;HiddenMengceng();'); }
	if ($userSysArr['US_isReg'] == 0){ JS::DiyEnd('alert("会员注册已关闭");regWaitTime=0;HiddenMengceng();'); }

	if (AppQuan::JudReg()){
		if ($regCode==''){ JS::DiyEnd('alert("请输入注册邀请码");regWaitTime=0;HiddenMengceng();'); }
		$resArr = AppQuan::CheckUse($regCode,'reg');
		if (! $resArr['res']){ JS::DiyEnd('alert("该注册邀请码'. $resArr['note'] .'，请更换个。");regWaitTime=0;HiddenMengceng();'); }
	}

	$userpwd = OT::DePwdData($userpwd, $pwdMode, $pwdKey);

	if ($apiId != ''){ $userpwd=md5($apiId . OT::RndChar(5)); }
	$userpwdKey		= OT::RndChar(5);
	$userpwd		= md5(md5($userpwd) . $userpwdKey);
	$answerKey		= OT::RndChar(5);
	$answer			= md5(md5($answer) . $answerKey);


	Users::CheckUserName('ajax',$username);

	list($recomType,$recomId,$recomUser) = AppRecom::GetRegArr();


	if ($userSysArr['US_regBadWord'] != ''){
		$username = strtolower($username);
		$badWordArr = explode('|',strtolower($userSysArr['US_regBadWord']));
		foreach ($badWordArr as $str){
			if (strlen($str)>0 && strpos($username,$str)!==false){
				JS::DiyEnd('alert("用户名中含禁止注册关键词（'. $str .'）");regWaitTime=0;HiddenMengceng();');
			}
		}
	}

	if ($userSysArr['US_isOnlyMail'] == 1 && strlen($mail) > 0){
		$checkexe = $DB->QueryParam('select UE_ID from '. OT_dbPref .'users where UE_mail=? limit 1',array($mail));
		if ($checkexe->fetch()){
			JS::DiyEnd('alert("该邮箱已存在，请更换一个。");ResetVerCode();regWaitTime=0;HiddenMengceng();');
		}
	}

	if ($userSysArr['US_isOnlyPhone'] == 1 && strlen($phone) > 0){
		$checkexe = $DB->QueryParam('select UE_ID from '. OT_dbPref .'users where UE_phone=? limit 1',array($phone));
		if ($checkexe->fetch()){
			JS::DiyEnd('alert("该手机号已存在，请更换一个。");ResetVerCode();regWaitTime=0;HiddenMengceng();');
		}
	}

	$authStr = '';
	if ($userSysArr['US_regAuthMail'] == 1){
		$resArr = AppMail::CheckMailCode('check', $mail, $mailCode, 0);
		if (! $resArr['res']){
			JS::DiyEnd('alert("'. $resArr['note'] .'");ResetVerCode();regWaitTime=0;HiddenMengceng();');
		}
		$authStr .= '|邮箱|';
	}
	if ($userSysArr['US_regAuthPhone'] == 1){
		$resArr = AppPhone::CheckPhoneCode('check', $phone, $phoneCode, 0);
		if (! $resArr['res']){
			JS::DiyEnd('alert("'. $resArr['note'] .'");ResetVerCode();regWaitTime=0;HiddenMengceng();');
		}
		$authStr .= '|手机|';
	}

	$todayTime		= TimeDate::Get();
	$userIP			= Users::GetIp();
	$computerCode	= Users::GetSignCode();

	$resultMd5 = md5(md5(OT_SiteID . session_id()) . OT_SiteID . $userIP);
	if ($rndMd5 != $resultMd5){
		JS::DiyEnd('alert("用户跟随信息验证失败，已重新更新，请再点提交下.");$id("rndMd5").value = "'. $resultMd5 .'";ResetVerCode();regWaitTime=0;HiddenMengceng();');
	}

	// IP黑名单
	$checkIpexe = $DB->QueryParam("select UI_ID from ". OT_dbPref ."userIp where UI_type='bad' and UI_ip=?",array($userIP));
		if ($checkIpexe->fetch()){
			JS::DiyEnd('alert("该IP已被拉入黑名单，如有问题请与管理员联系。");regWaitTime=0;HiddenMengceng();');
		}
	unset($checkIpexe);

	$allowRegTime = TimeDate::Add('n',$userSysArr['US_againRegMinute']*(-1),$todayTime);
	if ($userSysArr['US_againRegMinute']>0){
		$checkIpexe = $DB->QueryParam("select UI_ID from ". OT_dbPref ."userIp where UI_type='reg' and UI_ip=? and UI_time>?",array($userIP,$allowRegTime));
			if ($checkIpexe->fetch()){
				JS::DiyEnd('alert("每'. $userSysArr['US_againRegMinute'] .'分钟内只能注册一次，请'. $userSysArr['US_againRegMinute'] .'分钟后再注册。");regWaitTime=0;HiddenMengceng();');
			}
		unset($checkIpexe);
	}
	
	if ($userSysArr['US_isRegAudit'] == 1){
		$alertStr = '\n您当前状态[待审核],需要管理员的审核。';
	}else{
		$alertStr = '';
	}

	if (strpos('|qq|weibo|weixin|wxmp|taobao|alipay|','|'. $apiType .'|') === false){ $apiType='web'; }
	if ($userSysArr['US_isRegAudit'] == 1){
		$userState = 0;
		$isRecomScore = 0;
	}else{
		$userState = 1;
		$isRecomScore = 1;
	}
	
	$regScoreArr = Area::UserScore('reg');

	$record = array();
	$record['UE_time']			= $todayTime;
	$record['UE_loginTime']		= $todayTime;
	$record['UE_regType']		= $apiType;
	$record['UE_regIP']			= $userIP;
	$record['UE_apiStr']		= '|'. $apiType .'::'. $apiId .':1|';
	$record['UE_authStr']		= $authStr;
	$record['UE_username']		= $username;
	$record['UE_userpwd']		= $userpwd;
	$record['UE_mail']			= $mail;
	$record['UE_groupID']		= $userSysArr['US_regGroupID'];
	$record['UE_userKey']		= $userpwdKey;
	$record['UE_question']		= $question;
	$record['UE_answer']		= $answer;
	$record['UE_answerKey']		= $answerKey;
	$record['UE_face']			= '';
	$record['UE_realname']		= $realname;
	$record['UE_sex']			= $sex;
	$record['UE_phone']			= $phone;
	//$record['UE_fax']			= '';
	$record['UE_qq']			= $qq;
	$record['UE_weixin']		= $weixin;
	$record['UE_ww']			= $ww;
	$record['UE_web']			= $web;
	$record['UE_note']			= $note;
	if (AppRecom::Jud()){
		$record['UE_recomType']		= $recomType;
		$record['UE_recomId']		= $recomId;
		$record['UE_recomUser']		= $recomUser;
	}
//	$record['UE_isRecomScore']	= $isRecomScore;
	$record['UE_score1']		= $regScoreArr['US_score1'];
	$record['UE_score2']		= $regScoreArr['US_score2'];
	$record['UE_score3']		= $regScoreArr['US_score3'];
	$record['UE_score1Day']		= $regScoreArr['US_score1'];
	$record['UE_score2Day']		= $regScoreArr['US_score2'];
	$record['UE_score3Day']		= $regScoreArr['US_score3'];
	$record['UE_score1Week']	= $regScoreArr['US_score1'];
	$record['UE_score2Week']	= $regScoreArr['US_score2'];
	$record['UE_score3Week']	= $regScoreArr['US_score3'];
	$record['UE_score1Month']	= $regScoreArr['US_score1'];
	$record['UE_score2Month']	= $regScoreArr['US_score2'];
	$record['UE_score3Month']	= $regScoreArr['US_score3'];
	$record['UE_score1Year']	= $regScoreArr['US_score1'];
	$record['UE_score2Year']	= $regScoreArr['US_score2'];
	$record['UE_score3Year']	= $regScoreArr['US_score3'];
	$record['UE_state']			= $userState;

	$judResult = $DB->InsertParam('users',$record);
	if ($judResult){
		$alertResult = '成功';
		$userID = $DB->GetOne('select max(UE_ID) from '. OT_dbPref .'users');

		if (AppRecom::Jud()){
			if ($userSysArr['US_isRegAudit'] == 0 && $recomId > 0){
				AppRecom::RegScore($userID, $username, $recomId, $recomUser);
			}
		}

		if (AppQuan::JudReg()){
			AppQuan::ToUse($regCode, $userID, $username, 'reg');
		}

		if (AppUserScore::IsAdd($regScoreArr['US_score1'], $regScoreArr['US_score2'], $regScoreArr['US_score3'])){
			$scoreArr = array();
			$scoreArr['UM_userID']		= $userID;
			$scoreArr['UM_username']	= $username;
			$scoreArr['UM_type']		= 'reg';
			$scoreArr['UM_score1']		= $regScoreArr['US_score1'];
			$scoreArr['UM_score2']		= $regScoreArr['US_score2'];
			$scoreArr['UM_score3']		= $regScoreArr['US_score3'];
			$scoreArr['UM_remScore1']	= $regScoreArr['US_score1'];
			$scoreArr['UM_remScore2']	= $regScoreArr['US_score2'];
			$scoreArr['UM_remScore3']	= $regScoreArr['US_score3'];
			$scoreArr['UM_note']		= '注册成功';
			AppUserScore::AddData($scoreArr);
		}

		if ($userSysArr['US_againRegMinute'] > 0){
			$DB->QueryParam("delete from ". OT_dbPref ."userIp where UI_type='reg' and UI_time<?",array($allowRegTime));
			$DB->QueryParam("insert into ". OT_dbPref ."userIp (UI_type,UI_time,UI_date,UI_dataID,UI_userID,UI_ip,UI_computerCode) values('reg',?,?,0,?,?,?)",array($todayTime, TimeDate::Get('date'), $userID, $userIP, $computerCode));
		}
		

		$_SESSION['userExitOldTime']= time();
		$_SESSION['userRegTime']	= time();

		// 会员信息加入/更新session和cookies
		Users::Update($userID,$username,$userpwd,$mail,$realname,$regScoreArr['US_score1'] .'|'. $regScoreArr['US_score2'] .'|'. $regScoreArr['US_score3']);

		// 登录状态记录在线表
		Users::Online($todayTime,$userID,$computerCode,$userIP);

		// 是否发送注册邮件
		if ($userSysArr['US_isAuthMail'] == 1 && AppMail::Jud() && strlen($mail) > 0){
			$resArr = AppMail::ContentSend('reg', $mail, $userID, $username);
			/* if (! $resArr['res']){
				$alertStr .= '\n邮件发送失败，原因：'. $resArr['note'];
			} */
		}
		// 是否发送注册短信
		if ($userSysArr['US_isAuthPhone'] == 1 && AppPhone::Jud() && strlen($phone) > 0){
			$resArr = AppPhone::ContentSend('reg', $phone, $userID, $username, array('user'=>$username));
			/* if (! $resArr['res']){
				$alertStr .= '\n短信发送失败，原因：'. $resArr['note'];
			} */
		}

		//document.location.href='". $backURL ."';
		if ($userSysArr['US_isRegApi'] == 1 && $isRegApi == 1){
			JS::Diy('alert("授权注册成功.");document.location.href="./";');
		}else{
			JS::Diy('alert("注册成功！'. $alertStr .'");');
			if (strlen($backURL) > 0 && GetUrl::Domain($backURL) == GetUrl::Domain(GetUrl::HttpHost())){
				JS::Diy('document.location.href=unescape("'. urlencode($backURL) .'");');
			}else{
				JS::Diy('document.location.href="usersCenter.php";');
			}
		}
	}else{
		$alertResult = '失败';
		JS::Diy('alert("注册失败！请检查是否有信息填写不规范。");');
	}

}



// 登录
function login(){
	global $DB,$systemArr,$userSysArr;

	$backURL	= urldecode(OT::PostStr('backURL'));
	$loginMode	= OT::PostStr('loginMode');
	$loginPwd	= OT::PostStr('loginPwd');
	$pwdMode	= OT::PostStr('pwdMode');
	$pwdKey		= OT::PostStr('pwdKey');
	$mail		= OT::PostStr('mail');
	$mailCode	= OT::PostStr('mailCode');
	$phone		= OT::PostNum('phone');
	$phoneCode	= OT::PostStr('phoneCode');
	$username	= OT::PostRegExpStr('username','sql');
	$userpwd	= OT::PostStr('userpwd');
	$verCode	= strtolower(OT::PostStr('verCode'));
	$expTime	= OT::PostInt('expTime');
		if ($expTime > 0){ $expTime = time() + StrInfo::LoginExpSec($expTime); }

	if ($loginMode == 'mail'){
		if ($mail == ''){
			JS::DiyEnd('alert("邮箱不能为空");ResetVerCode();HiddenMengceng();');
		}
		if (! Is::Mail($mail)){
			JS::DiyEnd('alert("邮箱格式错误，请认真检查下");ResetVerCode();HiddenMengceng();');
		}
		if (! ($userSysArr['US_isLoginMail'] >= 1 && AppLoginMail::Jud())){
			JS::DiyEnd('alert("已关闭邮箱登录，请用其他登录方式");window.location.reload();');
		}
		$loginVal = $mail;
		$loginName = '邮箱';
	
	}elseif ($loginMode == 'phone'){
		if (strlen($phone) != 11){
			JS::DiyEnd('alert("手机号长度必须为11位，当前'. strlen($phone) .'位");ResetVerCode();HiddenMengceng();');
		}
		if (! ($userSysArr['US_isLoginPhone'] >= 1 && AppLoginPhone::Jud())){
			JS::DiyEnd('alert("已关闭手机号登录，请用其他登录方式");window.location.reload();');
		}
		$loginVal = $phone;
		$loginName = '手机号';

	}else{
		$loginMode = 'username';
		if ($username == ''){
			JS::DiyEnd('alert("用户名不能为空");ResetVerCode();HiddenMengceng();');
		}
		$loginVal = $username;
		$loginName = '用户名';

	}

	if ($loginPwd == 'mail'){
		if ($mailCode == ''){
			JS::DiyEnd('alert("邮件验证码不能为空");ResetVerCode();HiddenMengceng();');
		}
	}elseif ($loginPwd == 'phone'){
		if ($phoneCode == ''){
			JS::DiyEnd('alert("短信验证码不能为空");ResetVerCode();HiddenMengceng();');
		}
	}else{
		$loginPwd = 'pwd';
		if ($userpwd == ''){
			JS::DiyEnd('alert("密码不能为空");ResetVerCode();HiddenMengceng();');
		}
	}

	$VerCodeJsStr = '';
	if (OT_OpenVerCode){
		if (empty($_SESSION['VerCodeNum'])){ $_SESSION['VerCodeNum']=0; }
		$_SESSION['VerCodeNum'] ++;
		if ($_SESSION['VerCodeNum'] >= 2){
			$VerCodeJsStr = 'if ($id("verCodeK").style.display=="none"){ $id("verCodeK").style.display=""; }';
	//		die();
		}
		if ($_SESSION['VerCodeNum'] > 2 || strpos($systemArr['SYS_verCodeStr'],'|login|') !== false){
			if ($systemArr['SYS_verCodeMode'] == 20){
				$geetest = new Geetest();
				if (! $geetest->IsTrue('web')){
					JS::DiyEnd($VerCodeJsStr .'alert("验证码错误，请重新点击验证.");ResetVerCode();HiddenMengceng();');
				}
			}else{
				if ($verCode=='' || $verCode!=strtolower($_SESSION['VerCode'. $systemArr['SYS_verCodeMode']])){
					JS::DiyEnd($VerCodeJsStr .'alert("验证码错误.");ResetVerCode();HiddenMengceng();');
				}
				$_SESSION['VerCode'. $systemArr['SYS_verCodeMode']] = '';
			}
		}
	}

	$todayTime = TimeDate::Get();

	// 检测用户 （会员登录逻辑代码相似的有3处，API快捷登录、微信授权登录会员系统、电脑版扫码公众号二维码登录会员系统）
	$checkrec = $DB->QueryParam('select UE_ID,UE_realname,UE_username,UE_userpwd,UE_userKey,UE_mail,UE_fax,UE_loginTime,UE_loginIP,UE_loginNum,UE_authStr,UE_score1,UE_score2,UE_score3,UE_weixinID from '. OT_dbPref .'users where UE_'. $loginMode .'=?',array($loginVal));
		if (! $urow = $checkrec->fetch()){
			if ($loginPwd == 'mail'){
				$alertStr = '邮箱不存在或邮件验证码错误';
			}elseif ($loginPwd == 'phone'){
				$alertStr = '手机号不存在或短信验证码错误';
			}else{
				$alertStr = $loginName .'不存在或密码错误';
			}
			JS::DiyEnd($VerCodeJsStr .'alert("'. $alertStr .'");ResetVerCode();HiddenMengceng();');
		}

		if ($loginMode == 'mail' && $userSysArr['US_isLoginMail'] == 2 && strpos($urow['UE_authStr'],'|邮箱|') === false){
			JS::DiyEnd('alert("您的邮箱还未验证，无法使用该登录方式，请联系管理员给你验证通过下\n如果管理员已验证过还是这样，让管理员查下是否多个账号都存在该邮箱，导致干扰了。");ResetVerCode();HiddenMengceng();');
		}elseif ($loginMode == 'phone' && $userSysArr['US_isLoginPhone'] == 2 && strpos($urow['UE_authStr'],'|手机|') === false){
			JS::DiyEnd('alert("您的手机号还未验证，无法使用该登录方式，请联系管理员给你验证通过下\n如果管理员已验证过还是这样，让管理员查下是否多个账号都存在该手机号，导致干扰了。");ResetVerCode();HiddenMengceng();');
		}
		
		if ($loginMode == 'mail' && $loginPwd == 'mail'){
			$resArr = AppMail::CheckMailCode('login', $mail, $mailCode, 0);
			if (! $resArr['res']){
				JS::DiyEnd('alert("'. $resArr['note'] .'");ResetVerCode();regWaitTime=0;HiddenMengceng();');
			}
		}elseif ($loginMode == 'phone' && $loginPwd == 'phone'){
			$resArr = AppPhone::CheckPhoneCode('login', $phone, $phoneCode, 0);
			if (! $resArr['res']){
				JS::DiyEnd('alert("'. $resArr['note'] .'");ResetVerCode();regWaitTime=0;HiddenMengceng();');
			}
		}else{
			$userpwd		= OT::DePwdData($userpwd, $pwdMode, $pwdKey);
			$userpwdMd5		= md5(md5($userpwd) . $urow['UE_userKey']);
				if ($urow['UE_userpwd'] != $userpwdMd5){
					JS::DiyEnd($VerCodeJsStr .'alert("'. $loginName .'或密码错误.");ResetVerCode();HiddenMengceng();');
				}
		}

		$username		= $urow['UE_username'];
		$UE_ID			= $urow['UE_ID'];
		$UE_loginTime	= $urow['UE_loginTime'];
		$UE_mail		= $urow['UE_mail'];
		$UE_realname	= $urow['UE_realname'];
		$UE_authStr		= $urow['UE_authStr'];
		$UE_weixinID	= $urow['UE_weixinID'];
		$scoreStr		= $urow['UE_score1'] .'|'. $urow['UE_score2'] .'|'. $urow['UE_score3'];
		$userIP			= Users::GetIp();
		$computerCode	= Users::GetSignCode();
		$userpwd		= $urow['UE_userpwd'];

		$record = array();
		$record['UE_loginTime']		= $todayTime;
		$record['UE_loginIP']		= $userIP;
		$record['UE_loginNum']		= 'UE_loginNum+1';
		if (strlen($UE_loginTime)<4){ $UE_loginTime='1988-08-08'; }
		if (TimeDate::Get('date') != TimeDate::Get('date',$UE_loginTime)){
			$loginScoreArr = Area::UserScore('login');
			$record['UE_score1']		= 'UE_score1+'. $loginScoreArr['US_score1'];
			$record['UE_score2']		= 'UE_score2+'. $loginScoreArr['US_score2'];
			$record['UE_score3']		= 'UE_score3+'. $loginScoreArr['US_score3'];
			$record['UE_score1Day']		= 'UE_score1Day+'. $loginScoreArr['US_score1'];
			$record['UE_score2Day']		= 'UE_score2Day+'. $loginScoreArr['US_score2'];
			$record['UE_score3Day']		= 'UE_score3Day+'. $loginScoreArr['US_score3'];
			$record['UE_score1Week']	= 'UE_score1Week+'. $loginScoreArr['US_score1'];
			$record['UE_score2Week']	= 'UE_score2Week+'. $loginScoreArr['US_score2'];
			$record['UE_score3Week']	= 'UE_score3Week+'. $loginScoreArr['US_score3'];
			$record['UE_score1Month']	= 'UE_score1Month+'. $loginScoreArr['US_score1'];
			$record['UE_score2Month']	= 'UE_score2Month+'. $loginScoreArr['US_score2'];
			$record['UE_score3Month']	= 'UE_score3Month+'. $loginScoreArr['US_score3'];
			$record['UE_score1Year']	= 'UE_score1Year+'. $loginScoreArr['US_score1'];
			$record['UE_score2Year']	= 'UE_score2Year+'. $loginScoreArr['US_score2'];
			$record['UE_score3Year']	= 'UE_score3Year+'. $loginScoreArr['US_score3'];
			$todayFirst = true;
		}else{
			$todayFirst = false;
		}

		$judResult = $DB->UpdateParam('users',$record,'UE_ID='. $UE_ID);
			if ($judResult && $todayFirst){
				if (AppUserScore::IsAdd($loginScoreArr['US_score1'], $loginScoreArr['US_score2'], $loginScoreArr['US_score3'])){
					$scoreArr = array();
					$scoreArr['UM_userID']		= $UE_ID;
					$scoreArr['UM_username']	= $username;
					$scoreArr['UM_type']		= 'login';
					$scoreArr['UM_score1']		= $loginScoreArr['US_score1'];
					$scoreArr['UM_score2']		= $loginScoreArr['US_score2'];
					$scoreArr['UM_score3']		= $loginScoreArr['US_score3'];
					$scoreArr['UM_remScore1']	= $urow['UE_score1'] + $loginScoreArr['US_score1'];
					$scoreArr['UM_remScore2']	= $urow['UE_score2'] + $loginScoreArr['US_score2'];
					$scoreArr['UM_remScore3']	= $urow['UE_score3'] + $loginScoreArr['US_score3'];
					$scoreArr['UM_note']		= '登录成功';
					AppUserScore::AddData($scoreArr);
				}
			}
	unset($checkrec);

	$_SESSION['userLastLoginTime']	= $UE_loginTime;
	$_SESSION['userExitOldTime']	= time();


	// 登录状态记录在线表
	Users::Online($todayTime,$UE_ID,$computerCode,$userIP);

	// 会员信息加入/更新session和cookies
	Users::Update($UE_ID,$username,$userpwd,$UE_mail,$UE_realname,$scoreStr,$expTime);

	// 加入会员日志
	Area::AddLog($UE_ID, $username, '【电脑版】会员'. $loginName .'登录');

	$_SESSION['VerCodeNum'] = 0;

	// 检测用户邮箱、手机号是否需要提醒填写
	AreaApp::UserTixing($UE_authStr, $userSysArr, 2);

	if (strlen($backURL) > 0 && GetUrl::Domain($backURL) == GetUrl::Domain(GetUrl::HttpHost())){
		echo('document.location.href=unescape("'. urlencode($backURL) .'");');
	}else{
		echo('document.location.href="usersCenter.php";');	// alert("'. $backURL .'|'. GetUrl::Domain($backURL) .'|'. GetUrl::Domain(GetUrl::HttpHost()) .'");
	}
}



// 忘记密码--发送数据
function MissPwdSend(){
	global $DB,$systemArr,$userSysArr;

	$refType	= OT::GetStr('refType');
	$username	= OT::GetStr('username');
	$mail		= OT::GetStr('mail');
	$phone		= OT::GetStr('phone');

	if ($refType == '用户名'){
		$username	= Str::RegExp($username,'sql');

		$checkexe = $DB->QueryParam('select UE_ID,UE_username,UE_question,UE_mail from '. OT_dbPref .'users where UE_username=?',array($username));
			if (! $row = $checkexe->fetch()){
				die('<br /><br /><center class="font2_2">不存在该用户。</center>');
			}
			if (strlen($row['UE_question'])<1){
				echo('
				<div class="alertBox" style="width:85%;margin-top:10px;padding:5px;">
				该用户未设置密保，如有问题，请与管理员联系。
				</div>
				<div class="clr"></div>
				');
		/*			if (Is::Mail($row['UE_mail'))){
						echo('
						<div class="alertBox" style="width:85%;margin-top:6px;padding:5px;margin-left:20px;text-align:center;">
							还未设置密保？重置密保发到你E-mail，<input type="button" value="发送" onclick=''SendMissMail('. $row['UE_ID'] .');'' /><span id="loadingStr"></span>
						</div>
						')
					}*/
				die();
			}
			echo('
			<input type="hidden" id="userID" name="userID" value="'. $row['UE_ID'] .'" />
			<div class="input">
				密保问题：'. $row['UE_question'] .'
			</div>
			<div class="input">
				<input type="text" id="answer" name="answer" class="text realname" placeholder="请输入密保答案" />
			</div>
			');
		unset($checkexe);

	}elseif ($refType == '邮箱'){
		if (! Is::Mail($mail)){
			die('<br /><br /><center class="font2_2">邮箱格式错误.</center>');
		}

		$checkexe = $DB->QueryParam('select UE_ID,UE_username,UE_authStr from '. OT_dbPref .'users where UE_mail=?',array($mail));
			if (! $row = $checkexe->fetch()){
				die('<br /><br /><center class="font2_2">搜索不到该邮箱的用户。</center>');
			}else{
				do {
					$userStr = Str::PartHide($row['UE_username']);
					if (strpos($userSysArr['US_event'],'|missPwdMailAuth|') !== false && strpos($row['UE_authStr'],'|邮箱|') === false){
						echo('
						<div class="input">
							<label><input type="radio" name="userID" value="0" title="'. $userStr .'" onclick="$id(\'username\').value=this.title" disabled="disabled" />'. $userStr .' <span style="color:red;">（邮箱未验证，请联系管理员）</span></label>
						</div>
						');
					}else{
						echo('
						<div class="input">
							<label><input type="radio" name="userID" value="'. $row['UE_ID'] .'" title="'. $userStr .'" onclick="$id(\'username\').value=this.title" />'. $userStr .'</label>
						</div>
						');
					}
				}while ($row = $checkexe->fetch());
				echo('
				<div class="input">
					使用该邮箱的有以上账号，请选择你要找回的账号，然后点击 <input type="button" id="sendMail" value="发送邮件验证码" style="height:29px;" onclick=\'SendMailCode(this.id,"mail","missPwd","username");\' /> ，进入邮箱查看验证码，填写邮件验证码。
				</div>
				');
			}
			echo('
			<div class="input">
				<input type="text" id="mailCode" name="mailCode" class="text mail" placeholder="请输入邮件验证码" title="请输入邮件验证码" />
			</div>
			');
		unset($checkexe);
	
	}elseif ($refType == '手机'){
		if (! Is::Phone($phone)){
			die('<br /><br /><center class="font2_2">手机号格式错误.</center>');
		}

		$checkexe = $DB->QueryParam('select UE_ID,UE_username,UE_authStr from '. OT_dbPref .'users where UE_phone=?',array($phone));
			if (! $row = $checkexe->fetch()){
				die('<br /><br /><center class="font2_2">搜索不到该手机号的用户。</center>');
			}else{
				do {
					$userStr = Str::PartHide($row['UE_username']);
					if (strpos($userSysArr['US_event'],'|missPwdPhoneAuth|') !== false && strpos($row['UE_authStr'],'|手机|') === false){
						echo('
						<div class="input">
							<label><input type="radio" name="userID" value="0" title="'. $userStr .'" onclick="$id(\'username\').value=this.title" disabled="disabled" />'. $userStr .' <span style="color:red;">（手机未验证，请联系管理员）</span></label>
						</div>
						');
					}else{
						echo('
						<div class="input">
							<label><input type="radio" name="userID" value="'. $row['UE_ID'] .'" title="'. $userStr .'" onclick="$id(\'username\').value=this.title" />'. $userStr .'</label>
						</div>
						');
					}
				}while ($row = $checkexe->fetch());
				echo('
				<div class="input">
					使用该手机号的有以上账号，请选择你要找回的账号，然后点击 <input type="button" id="sendPhone" value="发送短信验证码" style="height:29px;" onclick=\'SendPhoneCode(this.id,"phone","missPwd","username");\' /> ，打开手机查看短信验证码，填写短信验证码。
				</div>
				');
			}
			echo('
			<div class="input">
				<input type="text" id="phoneCode" name="phoneCode" class="text phone" placeholder="请输入短信验证码" title="请输入短信验证码" />
			</div>
			');
		unset($checkexe);
	
	}else{
		die('<br /><br /><center class="font2_2">操作目的不明确.</center>');
	}

	echo('
	<div class="input">
		<input type="password" id="userpwd" name="userpwd" class="text userpwd" placeholder="请输入新密码" />
	</div>
	<div class="input">
		<input type="password" id="userpwd2" name="userpwd2" class="text userpwd" placeholder="请输入确认密码" />
	</div>
	<div class="input">
		'. Area::VerCodeH5('missPwd') .'
	</div>
	<div class="input">
		<button class="subBtn">立 即 提 交  <i class="fa fa-arrow-right"></i></button>
	</div>
	');
}



// 忘记密码--处理表单
function MissPwd(){
	global $DB,$systemArr,$userSysArr;

	$refType	= OT::PostStr('refType');
	$userID		= OT::PostInt('userID');
	$verCode	= strtolower(OT::PostStr('verCode'));
	$userpwd	= OT::PostStr('userpwd');
		if ($userpwd == ''){
			JS::DiyEnd('alert("新密码不能为空.");');
		}

	switch ($refType){
		case '用户名':
			$answer		= OT::PostStr('answer');
			if ($answer == ''){
				JS::DiyEnd('alert("密保答案不能为空.");');
			}
			break;
	
		case '邮箱':
			$mailCode	= OT::PostStr('mailCode');
			if ($mailCode == ''){
				JS::DiyEnd('alert("邮件验证码不能为空.");');
			}
			break;
	
		case '手机':
			$phoneCode	= OT::PostStr('phoneCode');
			if ($phoneCode == ''){
				JS::DiyEnd('alert("手机短信验证码不能为空.");');
			}
			break;
	
		default :
			JS::DiyEnd('alert("refType类型不对（'. $refType .'）.");');
			break;
	}

	if (OT_OpenVerCode){
		if ($systemArr['SYS_verCodeMode'] == 20){
			$geetest = new Geetest();
			if (! $geetest->IsTrue('web')){
				JS::DiyEnd('alert("验证码错误，请重新点击验证.");ResetVerCode();');
			}
		}else{
			if ($verCode=='' || $verCode!=strtolower($_SESSION['VerCode'. $systemArr['SYS_verCodeMode']])){
				JS::DiyEnd('alert("验证码错误.");ChangeCode("change");');
			}
			$_SESSION['VerCode'. $systemArr['SYS_verCodeMode']]='';
		}
	}

	switch ($refType){
		case '用户名':
			// 检测用户
			$checkrec = $DB->query('select UE_ID,UE_answer,UE_answerKey from '. OT_dbPref .'users where UE_ID='. $userID);
				if (! $row = $checkrec->fetch()){
					JS::DiyEnd('alert("不存在该用户.");ResetVerCode();');
				}
				if ($row['UE_answer']<>md5(md5($answer) . $row['UE_answerKey'])){
					JS::DiyEnd('alert("密保答案错误.");ResetVerCode();');
				}
			unset($checkrec);
			break;
	
		case '邮箱':
			// 检测用户
			$checkrec = $DB->query('select UE_ID,UE_mail from '. OT_dbPref .'users where UE_ID='. $userID);
				if (! $row = $checkrec->fetch()){
					JS::DiyEnd('alert("不存在该用户.");ResetVerCode();');
				}

				$resArr = AppMail::CheckMailCode('missPwd', $row['UE_mail'], $mailCode);
				if (! $resArr['res']){
					JS::DiyEnd('alert("'. $resArr['note'] .'");ResetVerCode();');
				}
			unset($checkrec);
			break;
	
		case '手机':
			// 检测用户
			$checkrec = $DB->query('select UE_ID,UE_phone from '. OT_dbPref .'users where UE_ID='. $userID);
				if (! $row = $checkrec->fetch()){
					JS::DiyEnd('alert("不存在该用户.");ResetVerCode();');
				}

				$resArr = AppPhone::CheckPhoneCode('missPwd', $row['UE_phone'], $phoneCode);
				if (! $resArr['res']){
					JS::DiyEnd('alert("'. $resArr['note'] .'");ResetVerCode();');
				}
			unset($checkrec);
			break;
	}

	$UE_userKey	= OT::RndChar(5);
	$userpwd	= md5(md5($userpwd) . $UE_userKey);

	$record = array();
	$record['UE_userpwd']	= $userpwd;
	$record['UE_userKey']	= $UE_userKey;

	$judResult = $DB->UpdateParam('users',$record,'UE_ID='. $userID);


	JS::Diy('
	alert("重设密码成功！\n\n现在可以使用新密码进行登录.\n系统自动跳转到登录窗口.");
	document.location.href="users.php?mudi=login";
	');
}



// 邮件发送
function MailSend(){
	global $DB;

	$type		= OT::GetStr('type');
	$btnId		= OT::GetStr('btnId');
	$mail		= OT::GetStr('mail');
	$username	= OT::GetStr('username');
		if (! in_array($type,array('check','rev','missPwd','login'))){
			JS::AlertEnd('类型错误（'. $type .'）');
		}
		if (! Is::Mail($mail)){
			JS::AlertEnd('邮箱格式不对（'. $mail .'）');
		}

	// 获取用户信息
	$userID = 0;
	// $realname = '';
	$userInfoStr	= Users::Get();
	$userArr		= explode("\t",$userInfoStr);
		if (count($userArr)>=4){
			$userID = intval($userArr[0]);
			$username = $userArr[1];
			// $realname = $userArr[4];
		}

	$resArr = AppMail::RndCodeSend($type, $userID, $username, $mail);
	if ($resArr['res']){
		JS::DiyEnd('alert("邮件发送成功。请登录邮箱（'. $mail .'）查看验证码");MailBtnCalc("'. $btnId .'",60)');
	}else{
		JS::AlertEnd('邮件发送失败。原因：'. $resArr['note']);
	}
}



// 短信发送
function PhoneSend(){
	global $DB,$systemArr;

	/*
	$type		= OT::GetStr('type');
	$btnId		= OT::GetStr('btnId');
	$phone		= OT::GetStr('phone');
	$username	= OT::GetStr('username');
	*/
	$type		= OT::PostStr('sendType');
	$btnId		= OT::PostStr('sendBtnId');
	$phone		= OT::PostStr('sendPhone');
	$username	= OT::PostRegExpStr('sendUsername','sql');
	$verCode	= strtolower(OT::PostStr('verCode'));
		if (! in_array($type,array('check','rev','missPwd','login'))){
			JS::AlertEnd('类型错误（'. $type .'）');
		}
		if (! Is::Phone($phone)){
			JS::AlertEnd('手机号格式不对（'. $phone .'）');
		}
		if ($systemArr['SYS_verCodeMode'] == 20){
			$geetest = new Geetest();
			if (! $geetest->IsTrue('web')){
				JS::DiyEnd('alert("验证码错误，请重新点击验证.");ResetVerCode("pop");');
			}
		}else{
			if ($verCode == '' || $verCode != strtolower($_SESSION['VerCode'. $systemArr['SYS_verCodeMode']])){
				JS::DiyEnd('alert("验证码错误.");ChangeCode("pop");');
			}
			$_SESSION['VerCode'. $systemArr['SYS_verCodeMode']] = '';
		}

	// 获取用户信息
	$userID = 0;
	// $realname = '';
	$userInfoStr	= Users::Get();
	$userArr		= explode("\t",$userInfoStr);
		if (count($userArr)>=4){
			$userID = intval($userArr[0]);
			$username = Str::RegExp($userArr[1],'sql');
			// $realname = Str::RegExp($userArr[4],'sql');
		}

	$resArr = AppPhone::RndCodeSend($type, $userID, $username, $phone);
	if ($resArr['res']){
		JS::DiyEnd('alert("短信发送成功。请查看手机（'. $phone .'）短信验证码");HiddenMengceng();PhoneBtnCalc("'. $btnId .'",60)');
	}else{
		JS::DiyEnd('alert("短信发送失败。原因：'. $resArr['note'] .'");HiddenMengceng();');
	}
}



// 在线状态清理
function OnlineClear(){
	//$DB->Delete('userOnline','UO_ip='. $DB->ForStr(Users::GetIp()));

	UserExit();
}



// 用户退出
function UserExit(){
	$userID		= Users::UserID();
	$username	= Users::Username();

	Users::Delete();

	// 加入会员日志
	if ($userID > 0 || strlen($username) > 0){ Area::AddLog($userID, $username, '【电脑版】会员退出'); }

	$backURL = OT::GetStr('backURL');
		if (strpos($backURL,'usersCenter.php') !== false || strlen($backURL) < 5){ $backURL = './'; }
	
	$judWait = false;
	if (AppChangyan::Jud()){ $judWait = true; }

	if ($judWait){
		echo('<h2>正在退出中......</h2>');
		AppChangyan::ExitLogin();
		JS::HrefTimeoutEnd($backURL,1.6);
	}else{
		JS::HrefEnd($backURL);
	}
}

?>
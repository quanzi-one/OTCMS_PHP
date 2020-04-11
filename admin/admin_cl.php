<?php
require(dirname(__FILE__) .'/check.php');


/* **** 版权所有 ****

 网钛CMS(OTCMS)
 程序开发：网钛科技
 官方网站：http://otcms.com
 联系ＱＱ：877873666
 联系邮箱：877873666@qq.com

 **** 版权所有 **** */



switch ($mudi){
	case 'login':
		login();
		break;

	case 'revPWD':
		// 检测管理员是否登录
		$MB->Open(',MB_userKey','login');
		RevPWD();
		$MB->Close();
		break;

	case 'revOthers':
		// 检测管理员是否登录
		$MB->Open('','login');
		$MB->Close();
		RevOthers();
		break;

	case 'itemNum':
		// 检测管理员是否登录
		$MB->Open('','login');
		$MB->Close();
		ItemNum();
		break;

	default:
		ErrMudi();
		break;

}

$DB->Close();





// 登录
function login(){
	global $DB,$sysAdminArr,$systemArr;

	$pwdMode	= OT::PostStr('pwdMode');
	$pwdKey		= OT::PostStr('pwdKey');
	$username	= OT::PostRegExpStr('username','sql');
	$userpwd	= OT::PostStr('userpwd');
	$verCode	= strtolower(OT::PostStr('verCode'));

	if ($systemArr['SYS_verCodeMode'] > 10){ $systemArr['SYS_verCodeMode'] = 1; }

	if (OT_OpenVerCode && strpos($systemArr['SYS_verCodeStr'],'|admin|')!==false){
		if ($systemArr['SYS_verCodeMode'] == 20){
			$geetest = new Geetest();
			if (! $geetest->IsTrue('web')){
				die('alert("验证码错误，请重新点击验证.");ResetVerCode();');
			}
		}else{
			if ($verCode == '' || $verCode != strtolower($_SESSION['VerCode'. $systemArr['SYS_verCodeMode']])){
				JS::AlertBackEnd('验证码错误');
			}
			$_SESSION['VerCode'. $systemArr['SYS_verCodeMode']] = null;
		}
	}

	$loginNewTime = time();
	$loginOldTime = intval(@$_SESSION[OT_SiteID .'loginOldTime']);
	if ($loginNewTime - $loginOldTime < 6){
		JS::AlertBackEnd('由于验证码禁用，连续登录需相隔6秒.');
	}else{
		$_SESSION[OT_SiteID .'loginOldTime'] = $loginNewTime;
	}

	$checkexe=$DB->query('select MB_ID,MB_loginTime,MB_loginNum,MB_loginIP,MB_realname,MB_userpwd,MB_userKey,MB_state from '. OT_dbPref .'member where MB_username='. $DB->ForStr($username));
	if (! $row = $checkexe->fetch()){
		Adm::AddLog(array(
			'userID'	=> 0,
			'realname'	=> $username,
			'note'		=> '登录失败（用户名错误）',
			));
		JS::AlertBackEnd('错误！请确认用户名或密码是否正确！');
	}
	$nowStr		= TimeDate::Get();
	$userIP		= Users::GetIp();
	$userpwd	= OT::DePwdData($userpwd, $pwdMode, $pwdKey);
	$MB_ID			= $row['MB_ID'];
	$MB_realname	= $row['MB_realname'];
	$MB_loginNum	= intval($row['MB_loginNum']);
	$userpwd = md5(md5($userpwd) . $row['MB_userKey']);

		if ($row['MB_userpwd'] != $userpwd){
			Adm::AddLog(array(
				'userID'	=> $MB_ID,
				'realname'	=> $MB_realname,
				'note'		=> '登录失败（密码错误）',
				));
			JS::AlertBackEnd('密码错误！');
		}
		if ($row['MB_state']==10){
			Adm::AddLog(array(
				'userID'	=> $MB_ID,
				'realname'	=> $MB_realname,
				'note'		=> '登录失败（账号已被冻结）',
				));
			JS::AlertBackEnd('该账号已被冻结，如有疑问请联系管理员！');
		}

	$DB->UpdateParam('member',array(
		'MB_loginTime'	=> $nowStr,
		'MB_loginNum'	=> $MB_loginNum+1,
		'MB_loginIP'	=> $userIP
		),'MB_ID='. $row['MB_ID']);

	$user_info = Encrypt::PwdEncode($MB_ID ."\t". $username ."\t". $userpwd ."\t". $userIP ."\t". $nowStr ."\t". $MB_realname,$sysAdminArr['SA_adminLoginKey']);
	$_SESSION[OT_SiteID .'exitOldTime']		= time();
	$_SESSION[OT_SiteID .'memberID']		= $MB_ID;
	$_SESSION[OT_SiteID .'memberUsername']	= $username;
	$_SESSION[OT_SiteID .'memberUserpwd']	= $userpwd;
	$_SESSION[OT_SiteID .'memberUserIP']	= $userIP;
	$_SESSION[OT_SiteID .'memberTime']		= $nowStr;
	$_SESSION[OT_SiteID .'memberInfo']		= $user_info;
	$_SESSION[OT_SiteID .'memberRealName']	= $MB_realname;
	setcookie(OT_SiteID .'memberInfo', $user_info, null, null, null, null, true);
	setcookie(OT_SiteID .'memberRealName', $MB_realname, null, null, null, null, true);

	// 记录到在线表里
	$computerCode = Users::GetSignCode();
	$mgexe = $DB->query('select MO_ID from '. OT_dbPref .'memberOnline where MO_userID='. $MB_ID);
		if (! $mgexe->fetchColumn()){
			$DB->InsertParam('memberOnline',array(
				'MO_time'			=> $nowStr,
				'MO_userID'			=> $MB_ID,
				'MO_computerCode'	=> $computerCode
				));
		}else{
			$DB->UpdateParam('memberOnline',array(
				'MO_time'			=> $nowStr,
				'MO_computerCode'	=> $computerCode
				),'MO_userID='. $MB_ID);
		}
	unset($mgexe);

	$ipInfoArr = OT::GetIpInfoArr();

	// 操作日志记录
	Adm::AddLog(array(
		'userID'	=> $MB_ID,
		'realname'	=> $MB_realname,
		'note'		=> '登录成功！（用户IP：'. $userIP .'，用户地址：'. $ipInfoArr['address'] .'）',
		));

	JS::HrefEnd('ind_backstage.php');
}




// 修改密码
function RevPWD(){
	global $DB,$MB,$user_name,$user_pwd,$sysAdminArr;

	$judUsername	= OT::PostStr('judUsername');
	$judUserpwd		= OT::PostStr('judUserpwd');

	$MB_userKey = $MB->mMbRow['MB_userKey'];
	$revArr=array();

	// 判断用户名
	if ($judUsername == 'true'){
		$username=OT::PostStr('username');
			if ($username==''){
				JS::AlertBackEnd('用户名不能为空！');
			}
			if (Str::RegExp($username,'sql') != $username){
				JS::AlertBackEnd('用户名含非法字符！\n仅允许包含数字、字母、下划线、汉字，其他符号均不允许');
			}

		$username = Str::RegExp($username,'sql');

		$checkexe=$DB->query('select MB_ID from '. OT_dbPref .'member where MB_username='. $DB->ForStr($username));
			if ($checkexe->fetchColumn()){
				JS::AlertBackEnd('该用户名已存在，请换个！');
			}
		unset($checkexe);

		$revArr['MB_username'] = $username;
	}

	// 判断密码
	if ($judUserpwd == 'true'){
		$userpwd0	= OT::PostStr('userpwd0');
		$userpwd	= OT::PostStr('userpwd');
		if ($userpwd0 == '' || $userpwd == ''){
			JS::AlertBackEnd('修改密码相关信息接收不全！');
		}
		if (md5(md5($userpwd0) . $MB_userKey) != $user_pwd){
			JS::AlertBackEnd('原密码错误！');
		}
		$newUserKey	= OT::RndChar(5);
		$userpwd	= md5(md5($userpwd) . $newUserKey);
		$revArr['MB_userKey']	= $newUserKey;
		$revArr['MB_userpwd']	= $userpwd;
	}

	if (count($revArr)>0 && count($revArr)<=3){
		$DB->UpdateParam('member',$revArr,'MB_username='. $DB->ForStr($user_name) .' and MB_userpwd='. $DB->ForStr($user_pwd));
	}

	$alert = '';
	if ($judUsername == 'true'){
		$user_name = $username;
		$alert = '用户名（'. $username .'）修改成功\n';
	}
	if ($judUserpwd == 'true'){
		$user_pwd = $userpwd;
		$alert .= '密码修改成功';
	}

	$user_info=Encrypt::PwdEncode($MB->mUserID ."\t". $user_name ."\t". $user_pwd ."\t". $MB->mUserIp ."\t". $MB->mUserTime ."\t". $MB->mRealname,$sysAdminArr['SA_adminLoginKey']);
	@session_start();
	$_SESSION[OT_SiteID .'memberUsername']	= $user_name;
	$_SESSION[OT_SiteID .'memberUserpwd']	= $user_pwd;
	$_SESSION[OT_SiteID .'memberInfo']		= $user_info;
	setcookie(OT_SiteID .'memberInfo', $user_info, null, null, null, null, true);

	JS::AlertHrefEnd($alert,'admin.php?mudi=revPWD');

}



function RevOthers(){
	global $DB,$MB;

	$mudi2			= OT::PostStr('mudi2');
	$backURL		= OT::PostStr('backURL');
	$newItemNum		= OT::PostInt('itemNum');
		if ($newItemNum<1){$newItemNum=20;}
	$foreUsername	= OT::PostStr('foreUsername');

	$DB->UpdateParam('member', array('MB_itemNum'=>$newItemNum,'MB_foreUsername'=>$foreUsername), 'MB_username='. $DB->ForStr($MB->mUserName) .' and MB_userpwd='. $DB->ForStr($MB->mUserPwd));

	JS::AlertHrefEnd('修改成功！',$backURL);
}



function ItemNum(){
	global $DB,$MB;

	$mudi2		= OT::PostStr('mudi2');
	$itemNumURL	= OT::PostStr('itemNumURL');
	$newItemNum	= OT::PostInt('itemNum');
		if ($newItemNum<1){$newItemNum=20;}

	$DB->query('update '. OT_dbPref .'member set MB_itemNum='. $newItemNum .' where MB_username='. $DB->ForStr($MB->mUserName) .' and MB_userpwd='. $DB->ForStr($MB->mUserPwd));

	JS::HrefEnd($itemNumURL);
}



function ErrMudi(){
	global $DB,$MB;

	//清除session
	//session_unregister();	//注销一个
	//session_destroy();	//注销全部session
	@session_start();
	$_SESSION[OT_SiteID .'exitOldTime']		= null;
	$_SESSION[OT_SiteID .'memberID']		= null;
	$_SESSION[OT_SiteID .'memberUsername']	= null;
	$_SESSION[OT_SiteID .'memberUserpwd']	= null;
	$_SESSION[OT_SiteID .'memberUserIP']	= null;
	$_SESSION[OT_SiteID .'memberTime']		= null;
	$_SESSION[OT_SiteID .'memberInfo']		= null;
	$_SESSION[OT_SiteID .'memberRealName']	= null;

	//清除cookies
	setcookie(OT_SiteID .'memberInfo',null, null, null, null, null, true);
	setcookie(OT_SiteID .'memberRealName',null, null, null, null, null, true);

	$DB->Delete('memberOnline','MO_userID='. $MB->mUserID);

	$ipInfoArr = OT::GetIpInfoArr();

	Adm::AddLog(array(
		'note'	=> '退出成功！（用户IP：'. $ipInfoArr['ip'] .'，用户地址：'. $ipInfoArr['address'] .'）',
		));

	JS::HrefEnd('index.php');
}



// 检测用户名
function CheckUserName(){
	global $DB;

	$username = OT::GetStr('userName');
	if (strlen($username) != strlen(Str::RegExp($username,'sql'))){
		die('<font color="red">含符号</font>');
	}

	$checkexe=intval($DB->GetOne('select MB_ID from '. OT_dbPref .'member where MB_username='. $DB->ForStr($username) .''));
		if ($checkexe>0){
			die('<font color="red">已占用</font>');
		}else{
			die('<font color="green">未占用</font>');
		}
	unset($checkexe);

}
?>
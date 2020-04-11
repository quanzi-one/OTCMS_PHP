<?php

if(!defined('OT_ROOT')) {
	exit('Access Denied');
}



class Users{

	// 写入session和cookies
	public static function SetCookies($name, $value, $expTime=null){
		if (strpos('|userID|username|userpwd|usermail|usercall|scoreStr|recomId|','|'. $name .'|') !== false){
			$_SESSION[OT_SiteID . $name] = $value;
		}
		if (strpos('|userID|username|usercall|scoreStr|recomId|userInfo|','|'. $name .'|') !== false){
			setcookie(OT_SiteID . $name, $value, $expTime, null, null, null, true);
		}
	}

	// 获取session和cookies
	public static function GetCookies($name){
		$str = '';
		if ($name != 'userInfo'){
			$str = @$_SESSION[OT_SiteID . $name];
		}
		if (empty($str) && strpos('|userID|username|usercall|scoreStr|recomId|userInfo|','|'. $name .'|') !== false){
			$str = @$_COOKIE[OT_SiteID . $name];
		}
		return $str;
	}

	// 获取GetCookies中的userID，并格式化为数值
	public static function UserID(){
		return intval(self::GetCookies('userID'));
	}

	// 获取GetCookies中的username，并过滤掉非法字符
	public static function Username(){
		return self::FilterStr(self::GetCookies('username'));
	}

	// 获取GetCookies中的usercall，并过滤掉非法字符
	public static function Usercall(){
		return self::FilterStr(self::GetCookies('usercall'));
	}


	// 检测用户名
	public static function CheckUserName($method,$username){
		global $DB,$userSysArr;

		if (strlen($username) != strlen(Str::RegExp($username,'sql'))){
			$alertStr='用户名含特殊符号';
			if ($method == 'send'){
				die('<script language="javascript">parent.$id("usernameStr").innerHTML="<span class=font2_2>'. $alertStr .'</span>";</script>');
			}elseif ($method == 'write'){
				die('<font color="red">'. $alertStr .'</font>');
			}elseif ($method == 'check'){
				die('<script language="javascript">alert("'. $alertStr .'");history.back(-1);</script>');
			}elseif ($method == 'ajax'){
				die('alert("'. $alertStr .'");regWaitTime=0;HiddenMengceng();');
			}
		}

		$nameNum=strlen($username);
		if ($nameNum > 16 || $nameNum < 4){
			$alertStr='长度在4到16字节，当前长度'. $nameNum .'字节。一个汉字占3字节';
			if ($method == 'send'){
				die('<script language="javascript">parent.$id("usernameStr").innerHTML="<span class=font2_2>'. $alertStr .'</span>";</script>');
			}elseif ($method == 'write'){
				die('<font color="red">'. $alertStr .'</font>');
			}elseif ($method == 'check'){
				die('<script language="javascript">alert("'. $alertStr .'");history.back(-1);</script>');
			}elseif ($method == 'ajax'){
				die('alert("'. $alertStr .'");regWaitTime=0;HiddenMengceng();');
			}
		}

		if (empty($userSysArr)){ $userSysArr = Cache::PhpFile('userSys'); }
		if ($userSysArr['US_regBadWord']!=''){
			$username = strtolower($username);
			$badWordArr = explode('|',strtolower($userSysArr['US_regBadWord']));
			foreach ($badWordArr as $str){
				if ($str != ''){
					$alertStr='用户名中含禁止注册关键词（'. $str .'）';
					if (strpos($username,$str) !== false){
						if ($method == 'send'){
							die('<script language="javascript">parent.$id("usernameStr").innerHTML="<span class=font2_2>'. $alertStr .'</span>";</script>');
						}elseif ($method == 'write'){
							die('<font color="red">'. $alertStr .'</font>');
						}elseif ($method == 'check'){
							die('<script language="javascript">alert("'. $alertStr .'");history.back(-1);</script>');
						}elseif ($method == 'ajax'){
							die('alert("'. $alertStr .'");regWaitTime=0;HiddenMengceng();');
						}
					}
				}
			}
		}

		$checkexe = $DB->QueryParam('select UE_ID from '. OT_dbPref .'users where UE_username=?',array($username));
			if ($checkexe->fetch()){
				$alertStr='该用户名已存在，请更换';
				if ($method == 'send'){
					die('<script language="javascript">parent.$id("usernameStr").innerHTML="<span class=font2_2>'. $alertStr .'</span>";</script>');
				}elseif ($method == 'write'){
					die('<font color="red">'. $alertStr .'</font>');
				}elseif ($method == 'check'){
					die('<script language="javascript">alert("'. $alertStr .'");history.back(-1);</script>');
				}elseif ($method == 'ajax'){
					die('alert("'. $alertStr .'");regWaitTime=0;HiddenMengceng();');
				}
			}else{
				$alertStr='恭喜！该用户名未被占用';
				if ($method == 'send'){
					die('<script language="javascript">parent.$id("usernameStr").innerHTML="<span class=font4_2>'. $alertStr .'</span>"</script>');
				}elseif ($method == 'write'){
					die('<font color="green">'. $alertStr .'</font>');
				}
			}
		unset($checkexe);

	}


	// 过滤字符串（正则表达式）
	public static function FilterStr($str){
		$newStr = strtr($str,array(
		' '=>'', ','=>'', '.'=>'', ':'=>'', ';'=>'', '"'=>'', '"'=>'', '`'=>'', '~'=>'', '?'=>'', '!'=>'', '@'=>'', '#'=>'', "\$"=>'', '%'=>'', '^'=>'', '&'=>'', '*'=>'', '<'=>'', '>'=>'', '('=>'', ')'=>'', '+'=>'', '-'=>'', '/'=>'', '='=>'', "\\"=>'', '{'=>'', '}'=>'', '0xbf27'=>''
		));
		
		return $newStr;
	}


	// 获取会员信息
	public static function Get(){
		global $userSysArr;

		if (empty($userSysArr)){ $userSysArr = Cache::PhpFile('userSys'); }

		$userID		= intval(@$_SESSION[OT_SiteID .'userID']);
		$username	= self::FilterStr(@$_SESSION[OT_SiteID .'username']);
		$userpwd	= @$_SESSION[OT_SiteID .'userpwd'];
		$usermail	= @$_SESSION[OT_SiteID .'usermail'];
		$usercall	= self::FilterStr(@$_SESSION[OT_SiteID .'usercall']);
		$scoreStr	= @$_SESSION[OT_SiteID .'scoreStr'];
		if ($userID>0 && $username!='' && $userpwd!=''){
			$userInfoStr = $userID ."\t". $username ."\t". $userpwd ."\t". $usermail ."\t". $usercall ."\t". $scoreStr;
		}else{
			$userInfoStr = self::GetCookies('userInfo');
			$userInfoStr = Encrypt::PwdDecode($userInfoStr,$userSysArr['US_loginKey']);
		}
		return $userInfoStr;
	}


	// 更新会员信息
	public static function Update($user_ID,$user_name,$user_pwd,$user_mail,$user_call,$user_scoreStr,$user_expTime=0){
		global $userSysArr;

		if (empty($userSysArr)){ $userSysArr = Cache::PhpFile('userSys'); }

		$_SESSION[OT_SiteID .'userID']			= $user_ID;
		$_SESSION[OT_SiteID .'username']		= $user_name;
		$_SESSION[OT_SiteID .'userpwd']			= $user_pwd;
		$_SESSION[OT_SiteID .'usermail']		= $user_mail;
		$_SESSION[OT_SiteID .'usercall']		= $user_call;
		$_SESSION[OT_SiteID .'userScoreStr']	= $user_scoreStr;
		if ($user_expTime == 0){ $user_expTime = null; }
		self::SetCookies('userID', $user_ID, $user_expTime);
		self::SetCookies('username', $user_name, $user_expTime);
		self::SetCookies('usercall', $user_call, $user_expTime);
		self::SetCookies('userInfo', Encrypt::PwdEncode($user_ID ."\t". $user_name ."\t". $user_pwd ."\t". $user_mail ."\t". $user_call ."\t". $user_scoreStr,$userSysArr['US_loginKey']), $user_expTime);
	//	echo("alert('". GetCookies("username") ."|". GetCookies("userInfo") ."');");
	}


	// 清空会员信息
	public static function Delete(){
		$_SESSION[OT_SiteID .'userID']			= 0;
	//	$_SESSION[OT_SiteID .'username']		= null;
		$_SESSION[OT_SiteID .'userpwd']			= ' ';
		$_SESSION[OT_SiteID .'usermail']		= ' ';
		$_SESSION[OT_SiteID .'usercall']		= ' ';
		$_SESSION[OT_SiteID .'userScoreStr']	= ' ';
		self::SetCookies('username',' ');
		self::SetCookies('userInfo',' ');
	}



	// 打开用户表
	// modeStr：模式（jud:有存在返回true，否则返回false；alertBack:如不存在提示并返回）
	// sqlStr：附加打开的用户字段,要以","号开头（如：,UE_username,UE_realname）
	// whereStr：子条件句
	public static function Open($modeStr,$sqlStr,$whereStr,&$judErrStr){
		global $DB,$userSysArr;

		if (empty($userSysArr)){ $userSysArr = Cache::PhpFile('userSys'); }

		if ($modeStr == ''){ $modeStr='alertBack'; }

		$userID			= 0;
		$username		= '';
		$userpwd		= '';
		$userInfoStr	= self::Get();
		$userArr		= explode("\t",$userInfoStr);
		if (count($userArr)>=4){
			$userID		= intval($userArr[0]);
			$username	= self::FilterStr($userArr[1]);
			$userpwd	= self::FilterStr($userArr[2]);
		}

		$userexe = $DB->query('select UE_ID'. $sqlStr .' from '. OT_dbPref .'users where UE_ID='. $userID .' and UE_userpwd='. $DB->ForStr($userpwd) . $whereStr);
		if (! $row = $userexe->fetch()){
			$judErrStr = JS::ModeDeal($modeStr,false,'请先登录','users.php?mudi=login&force=1&isBack=1');
				if ($judErrStr != ''){
					return $row;
				}
		}else{
			if ($userSysArr['US_exitMinute']>0){
				$userExitNewTime = time();
				$userExitOldTime = intval(@$_SESSION['userExitOldTime']);
				if ($userExitOldTime + $userSysArr['US_exitMinute'] * 60 < $userExitNewTime){
					self::Delete();
					$judErrStr = JS::ModeDeal($modeStr,false,'您超过'. $userSysArr['US_exitMinute'] .'分钟没动静，请重新登录。','deal.php?mudi=exit');
						if ($judErrStr != ''){
							return $row;
						}
				}else{
					$_SESSION['userExitOldTime'] = $userExitNewTime;
				}
			}

			if ($modeStr == 'jud'){
				$judErrStr = true;
				return $row;
			}

			// 检查会员在线状态
			$OT_userIP	= self::GetIp();
			$OT_computerCode = self::GetSignCode();
			$onlinerec = $DB->query("select UO_ID,UO_time,UO_computerCode,UO_ip from ". OT_dbPref ."userOnline where UO_userID=". $row["UE_ID"] ." and UO_ip='". $OT_userIP ."'");
				if ($row2 = $onlinerec->fetch()){
					$record = array();
					$record['UO_time']			= TimeDate::Get();
					$record['UO_computerCode']	= $OT_computerCode;
					$record['UO_ip']			= $OT_userIP;
					$DB->UpdateParam('userOnline',$record,'UO_ID='. $row2['UO_ID']);
				}else{
					/*
					$judErrStr = JS::ModeDeal($modeStr,false,'登录超时，请重新登录。','users_deal.php?mudi=onlineClear');
						if ($judErrStr != ''){
							return $row;
						}
					*/
				}
			unset($onlinerec);

			return $row;
		}
	}


	// 注册
	public static function AutoReg($apiType,$apiId,$realname=''){
		global $DB,$systemArr,$userSysArr;

		if (strpos('|qq|weibo|weixin|wxmp|taobao|alipay|','|'. $apiType .'|') === false){ return array('res'=>false,'note'=>'类型错误（'. $apiType .'）'); }

		if (empty($systemArr)){ $systemArr = Cache::PhpFile('system'); }
		if (empty($userSysArr)){ $userSysArr = Cache::PhpFile('userSys'); }

		$username	= $apiType .'_'. substr($apiId,-8) . OT::RndChar(1);

		if ($userSysArr['US_isUserSys'] == 0){ return array('res'=>false,'note'=>'会员系统已关闭'); }
		if ($userSysArr['US_isReg'] == 0){ return array('res'=>false,'note'=>'会员注册已关闭'); }

		if (AppQuan::JudReg()){ return array('res'=>false,'note'=>'会员系统需要注册邀请码注册，请联系管理员处理'); }

		$userpwd = $answer = $mail = '';

		$userpwd = md5($apiId . OT::RndChar(5));
		$userpwdKey		= OT::RndChar(5);
		$userpwd		= md5(md5($userpwd) . $userpwdKey);
		$answerKey		= OT::RndChar(5);
		$answer			= md5(md5($answer) . $answerKey);

		$todayTime		= TimeDate::Get();
		$userIP			= Users::GetIp();
		$computerCode	= Users::GetSignCode();

		// IP黑名单
		$checkIpexe = $DB->QueryParam("select UI_ID from ". OT_dbPref ."userIp where UI_type='bad' and UI_ip=?",array($userIP));
			if ($checkIpexe->fetch()){ return array('res'=>false,'note'=>'该IP已被拉入黑名单，如有问题请与管理员联系。'); }
		unset($checkIpexe);

		$allowRegTime = TimeDate::Add('n',$userSysArr['US_againRegMinute']*(-1),$todayTime);
		if ($userSysArr['US_againRegMinute']>0){
			$checkIpexe = $DB->QueryParam("select UI_ID from ". OT_dbPref ."userIp where UI_type='reg' and UI_ip=? and UI_time>?",array($userIP,$allowRegTime));
				if ($checkIpexe->fetch()){ return array('res'=>false,'note'=>'每'. $userSysArr['US_againRegMinute'] .'分钟内只能注册一次，请'. $userSysArr['US_againRegMinute'] .'分钟后再注册。'); }
			unset($checkIpexe);
		}
		
		if ($userSysArr['US_isRegAudit'] == 1){
			$alertStr = '\n您当前状态[待审核],需要管理员的审核。';
		}else{
			$alertStr = '';
		}

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
		$record['UE_username']		= $username;
		$record['UE_userpwd']		= $userpwd;
		$record['UE_groupID']		= $userSysArr['US_regGroupID'];
		$record['UE_userKey']		= $userpwdKey;
		$record['UE_question']		= '';
		$record['UE_answer']		= $answer;
		$record['UE_answerKey']		= $answerKey;
		$record['UE_face']			= '';
		$record['UE_realname']		= $realname;
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
				$scoreArr['UM_note']		= '自动授权注册成功';
				AppUserScore::AddData($scoreArr);
			}

			if ($userSysArr['US_againRegMinute'] > 0){
				$DB->QueryParam("delete from ". OT_dbPref ."userIp where UI_type='reg' and UI_time<?",array($allowRegTime));
				$DB->QueryParam("insert into ". OT_dbPref ."userIp (UI_type,UI_time,UI_date,UI_dataID,UI_userID,UI_ip,UI_computerCode) values('reg',?,?,0,?,?,?)",array($todayTime, TimeDate::Get('date'), $userID, $userIP, $computerCode));
			}

			$_SESSION['userExitOldTime']= time();
			$_SESSION['userRegTime']	= time();

			// 把会员信息写入COOKIES
			Users::Update($userID,$username,$userpwd,$mail,$realname,$regScoreArr['US_score1'] .'|'. $regScoreArr['US_score2'] .'|'. $regScoreArr['US_score3']);

			Users::Online($todayTime,$userID,$computerCode,$userIP);

			return array('res'=>true,'note'=>'授权注册成功.','userID'=>$userID,'username'=>$username);
		}else{
			return array('res'=>false,'note'=>'注册失败！请检查是否有信息填写不规范。');
		}
	}


	public static function Online($userTime,$user_ID,$computerCode,$userIP){
		global $DB;

		$record = array();
		$record['UO_time']			= $userTime;
		$record['UO_userID']		= $user_ID;
		$record['UO_computerCode']	= $computerCode;
		$record['UO_ip']			= $userIP;

		$onlinerec = $DB->query("select UO_time,UO_userID,UO_computerCode,UO_ip from ". OT_dbPref ."userOnline where UO_userID=". $user_ID);
		if ($onlinerec->fetch()){
			$DB->UpdateParam('userOnline',$record,'UO_userID='. $user_ID);
		}else{
			$DB->InsertParam('userOnline',$record);
		}
		unset($onlinerec);
	}


	public static function UpdateScore($userID, $type, $score1=0, $score2=0, $score3=0, $sqlArr=array(), $mode=''){
		global $DB;

		switch ($type){
			case 'add': case '+':		$type = '+';	break;
			case 'cut': case '-':		$type = '-';	break;
			default :		die('UpdateScore 目的不明确.');
		}

		if ($score1 >= 0){
			$score1Str = $type . $score1;
		}else{
			if ($type == '+'){ $score1Str = $score1; }else{ $score1Str = '+'. abs($score1); }
		}
		if ($score2 >= 0){
			$score2Str = $type . $score2;
		}else{
			if ($type == '+'){ $score2Str = $score2; }else{ $score2Str = '+'. abs($score2); }
		}
		if ($score3 >= 0){
			$score3Str = $type . $score3;
		}else{
			if ($type == '+'){ $score3Str = $score3; }else{ $score3Str = '+'. abs($score3); }
		}

		$dataArr = array();
		$dataArr['UE_score1']		= 'UE_score1'. $score1Str;
		$dataArr['UE_score2']		= 'UE_score2'. $score2Str;
		$dataArr['UE_score3']		= 'UE_score3'. $score3Str;
		$dataArr['UE_score1Day']	= 'UE_score1Day'. $score1Str;
		$dataArr['UE_score2Day']	= 'UE_score2Day'. $score2Str;
		$dataArr['UE_score3Day']	= 'UE_score3Day'. $score3Str;
		$dataArr['UE_score1Week']	= 'UE_score1Week'. $score1Str;
		$dataArr['UE_score2Week']	= 'UE_score2Week'. $score2Str;
		$dataArr['UE_score3Week']	= 'UE_score3Week'. $score3Str;
		$dataArr['UE_score1Month']	= 'UE_score1Month'. $score1Str;
		$dataArr['UE_score2Month']	= 'UE_score2Month'. $score2Str;
		$dataArr['UE_score3Month']	= 'UE_score3Month'. $score3Str;
		$dataArr['UE_score1Year']	= 'UE_score1Year'. $score1Str;
		$dataArr['UE_score2Year']	= 'UE_score2Year'. $score2Str;
		$dataArr['UE_score3Year']	= 'UE_score3Year'. $score3Str;

		return $DB->Update('users', array_merge($dataArr,$sqlArr), 'UE_ID='. $userID, $mode);

	}


	public static function regTypeCN($num){
		switch ($num){
			case 'qq':		return 'QQ';
			case 'weibo':	return '新浪微博';
			case 'weixin':	return '微信';
			case 'wxmp':	return '微信公众号';
			case 'taobao':	return '淘宝';
			case 'alipay':	return '支付宝';
			case 'web':		return '网站';
			case 'back':	return '后台';
			default:		return '[未知'. $num .']';
		}
	}

	// 获得用户IP地址
	public static function GetIp(){
		if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')){
			$userIP = getenv('HTTP_CLIENT_IP');
		}elseif (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')){
			$userIP = getenv('HTTP_X_FORWARDED_FOR');
		}elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')){
			$userIP = $_SERVER['REMOTE_ADDR'];
		}
		/*
		}elseif (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')){
			$userIP = getenv('REMOTE_ADDR');
		*/
		$userIP = addslashes($userIP);
		if ($userIP == '::1'){
			$userIP = '127.0.0.1';
		}else{
			@preg_match("/[\d\.]{7,15}/", $userIP, $userIpArr);
			$userIP = $userIpArr[0] ? $userIpArr[0] : 'unknown';
			unset($userIpArr);
		}
		return $userIP;
	}

	// 获取电脑识别符
	public static function GetSignCode($userIP=null){
		if (empty($userIP)){ $userIP=self::GetIp(); }
		return md5(OT_SiteID . $userIP . session_id());
	}

}
?>
<?php
require(dirname(__FILE__) .'/check.php');

Area::CheckIsOutSubmit();	//检测是否外部提交

$userSysArr = Cache::PhpFile('userSys');



switch ($mudi){
	case 'rev':
		rev();
		break;

	case 'revPageNum':
		RevPageNum();
		break;

}

$DB->Close();





// 修改
function rev(){
	global $DB,$userSysArr;

	$backURL	= OT::PostStr('backURL');
	$revType	= OT::PostStr('revType');

	// 获取数据
	switch ($revType){
		case 'info':
			$realname_is	= OT::PostInt('realname_is');
			$sex_is			= OT::PostInt('sex_is');
			$address_is		= OT::PostInt('address_is');
			$postCode_is	= OT::PostInt('postCode_is');
			$weixin_is		= OT::PostInt('weixin_is');
			$alipay_is		= OT::PostInt('alipay_is');
			$qq_is			= OT::PostInt('qq_is');
			$ww_is			= OT::PostInt('ww_is');
			$web_is			= OT::PostInt('web_is');
			$note_is		= OT::PostInt('note_is');
			$realname	= OT::PostRegExpStr('realname','sql');
			$sex		= OT::PostReplaceStr('sex','input');
			$address	= OT::PostReplaceStr('address','input');
			$postCode	= OT::PostReplaceStr('postCode','input');
			$weixin		= OT::PostReplaceStr('weixin','input');
			$alipay		= OT::PostReplaceStr('alipay','input');
			$qq			= str_replace('，',',',OT::PostReplaceStr('qq','input'));
			$ww			= OT::PostReplaceStr('ww','input');
			$web		= OT::PostReplaceStr('web','input');
			$note		= OT::PostReplaceStr('note','textarea');
			$pageNum	= OT::PostInt('pageNum');
				if ($pageNum > 100){ $pageNum = 100; }
			$pageWapNum	= OT::PostInt('pageWapNum');
				if ($pageWapNum > 100){ $pageWapNum = 100; }

			$itemTotal = $itemSucc = 0;
			if (strpos($userSysArr['US_userFieldStr'],'|昵称|') !== false){		// $realname_is == 1
				$itemTotal ++;
				if (strlen($realname) > 0){ $itemSucc ++; }
				if (strpos($userSysArr['US_userFieldStr'],'|昵称必填|') !== false){
					if ($realname == ''){ JS::AlertBackEnd('昵称不能为空.'); }
				}
			}
			if (strpos($userSysArr['US_userFieldStr'],'|性别|') !== false){		// $sex_is == 1
				$itemTotal ++;
				if (strlen($sex) > 0){ $itemSucc ++; }
				if (strpos($userSysArr['US_userFieldStr'],'|性别必填|') !== false){
					if ($sex == ''){ JS::AlertBackEnd('请选择性别.'); }
				}
			}
			if (strpos($userSysArr['US_userFieldStr'],'|收货地址|') !== false){	// $address_is == 1
				$itemTotal ++;
				if (strlen($address) > 3){ $itemSucc ++; }
				if (strpos($userSysArr['US_userFieldStr'],'|收货地址必填|') !== false){
					if ($address == ''){ JS::AlertBackEnd('收货地址不能为空.'); }
				}
			}
			if (strpos($userSysArr['US_userFieldStr'],'|邮编|') !== false){		// $postCode_is == 1
				$itemTotal ++;
				if (strlen($postCode) > 3){ $itemSucc ++; }
				if (strpos($userSysArr['US_userFieldStr'],'|邮编必填|') !== false){
					if ($postCode == ''){ JS::AlertBackEnd('邮编不能为空.'); }
				}
			}
			if (strpos($userSysArr['US_userFieldStr'],'|微信|') !== false){		// $weixin_is == 1
				$itemTotal ++;
				if (strlen($weixin) > 3){ $itemSucc ++; }
				if (strpos($userSysArr['US_userFieldStr'],'|微信必填|') !== false){
					if ($weixin == ''){ JS::AlertBackEnd('微信不能为空.'); }
				}
			}
			if (strpos($userSysArr['US_userFieldStr'],'|支付宝|') !== false){	// $alipay_is == 1
				$itemTotal ++;
				if (strlen($alipay) > 3){ $itemSucc ++; }
				if (strpos($userSysArr['US_userFieldStr'],'|支付宝必填|') !== false){
					if ($alipay == ''){ JS::AlertBackEnd('支付宝不能为空.'); }
				}
			}
			if (strpos($userSysArr['US_userFieldStr'],'|QQ|') !== false){		// $qq_is == 1
				$itemTotal ++;
				if (strlen($qq) > 4){ $itemSucc ++; }
				if (strpos($userSysArr['US_userFieldStr'],'|QQ必填|') !== false){
					if ($qq == ''){ JS::AlertBackEnd('QQ不能为空.'); }
				}
			}
			if (strpos($userSysArr['US_userFieldStr'],'|旺旺|') !== false){		// $ww_is == 1
				$itemTotal ++;
				if (strlen($ww) > 3){ $itemSucc ++; }
				if (strpos($userSysArr['US_userFieldStr'],'|旺旺必填|') !== false){
					if ($ww == ''){ JS::AlertBackEnd('旺旺不能为空.'); }
				}
			}
			if (strpos($userSysArr['US_userFieldStr'],'|个人主页|') !== false){	// $web_is == 1
				$itemTotal ++;
				if (strlen($web) > 7){ $itemSucc ++; }
				if (strpos($userSysArr['US_userFieldStr'],'|个人主页必填|') !== false){
					if ($web == ''){ JS::AlertBackEnd('个人主页不能为空.'); }
				}
			}
			if (strpos($userSysArr['US_userFieldStr'],'|备注|') !== false){		// $note_is == 1
				$itemTotal ++;
				if (strlen($note) > 3){ $itemSucc ++; }
				if (strpos($userSysArr['US_userFieldStr'],'|备注必填|') !== false){
					if ($note == ''){ JS::AlertBackEnd('备注不能为空.'); }
				}
			}
			break;

		case 'username':
			$newUsername	= OT::PostStr('newUsername');
			break;

		case 'password':
			$userpwdOld		= OT::PostStr('userpwdOld');
			$userpwd		= OT::PostStr('userpwd');
			if ($userpwdOld == '' || $userpwd == ''){
				JS::AlertBackEnd('密码不能为空.');
			}
			break;

		case 'mail':
			$isAuthMail		= OT::PostInt('isAuthMail');
			$mailOldCode	= OT::PostStr('mailOldCode');
			$mail			= OT::PostStr('mail');
			$mailCode		= OT::PostStr('mailCode');
			$mailPwd		= OT::PostStr('mailPwd');
			if (! Is::Mail($mail)){
				JS::AlertBackEnd('邮箱格式错误（'. $mail .'）.');
			}
			break;

		case 'phone':
			$isAuthPhone	= OT::PostInt('isAuthPhone');
			$phoneOldCode	= OT::PostStr('phoneOldCode');
			$phone			= OT::PostStr('phone');
			$phoneCode		= OT::PostStr('phoneCode');
			$phonePwd		= OT::PostStr('phonePwd');
			if (! Is::Phone($phone)){
				JS::AlertBackEnd('手机号格式错误（'. $phone .'）.');
			}
			break;

		case 'question':
			$answerOld	= OT::PostStr('answerOld');
			$question	= OT::PostStr('question');
			$answer		= OT::PostStr('answer');
			if ($question != Str::Filter($question,'sql')){
				JS::AlertBackEnd('密保问题含特殊符号。');
			}
			if (strlen($question)<2 || strlen($question)>50){
				JS::AlertBackEnd('密保问题长度不在2~50字节范围内。');
			}
			break;

		case 'app':
			$dashangImg1Old	= OT::PostRegExpStr('dashangImg1Old','abcnum+url');
			$dashangImg1	= OT::PostRegExpStr('dashangImg1','abcnum+url');
			$dashangImg2Old	= OT::PostRegExpStr('dashangImg2Old','abcnum+url');
			$dashangImg2	= OT::PostRegExpStr('dashangImg2','abcnum+url');
			$dashangImg3Old	= OT::PostRegExpStr('dashangImg3Old','abcnum+url');
			$dashangImg3	= OT::PostRegExpStr('dashangImg3','abcnum+url');
			$face			= OT::PostInt('face');
			if (strlen($dashangImg1 . $dashangImg2 . $dashangImg3) < 5 && $face == 0){
				JS::AlertBackEnd('内容不能都为空，或者没有修改项。');
			}
			break;

		default :
			JS::AlertBackEnd('操作不明确.');
			break;

	}


	// 获取用户信息
	$userInfoStr	= Users::Get();
	$userArr		= explode("\t",$userInfoStr);
		if (count($userArr)>=4){ $userID = intval($userArr[0]); }

	$addiFieldStr='';
	if (AppDashang::Jud()){ $addiFieldStr=',UE_dashangImg1,UE_dashangImg2,UE_dashangImg3'; }

	$userrec = $DB->query('select UE_ID,UE_regType,UE_apiStr,UE_authStr,UE_username,UE_userpwd,UE_userKey,UE_mail,UE_question,UE_answer,UE_answerKey,UE_realname,UE_sex,UE_address,UE_postCode,UE_phone,UE_qq,UE_web,UE_note,UE_faceMode,UE_face,UE_score1,UE_score2,UE_score3'. $addiFieldStr .' from '. OT_dbPref .'users where UE_ID='. $userID);
	$row = $userrec->fetch();
		$UE_ID			= $row['UE_ID'];
		$UE_regType		= $row['UE_regType'];
		$UE_apiStr		= $row['UE_apiStr'];
		$UE_authStr		= $row['UE_authStr'];
		$UE_username	= $row['UE_username'];
		$UE_userpwd		= $row['UE_userpwd'];
		$UE_userKey		= $row['UE_userKey'];
		$UE_mail		= $row['UE_mail'];
		$UE_phone		= $row['UE_phone'];
		$UE_question	= $row['UE_question'];
		$UE_answer		= $row['UE_answer'];
		$UE_answerKey	= $row['UE_answerKey'];
		$UE_realname	= $row['UE_realname'];
		$scoreStr = $row['UE_score1'] .'|'. $row['UE_score2'] .'|'. $row['UE_score3'];


	// 数据处理
	$alertAddi = '';
	$record = array();
	switch ($revType){
		case 'info':
			if ($realname_is == 1 && strpos($userSysArr['US_userFieldStr'],'|昵称|') !== false){
				$record['UE_realname']	= $realname;
			}
			if ($sex_is == 1 && strpos($userSysArr['US_userFieldStr'],'|性别|') !== false){
				$record['UE_sex']		= $sex;
			}
			if ($address_is == 1 && strpos($userSysArr['US_userFieldStr'],'|收货地址|') !== false){
				$record['UE_address']	= $address;
			}
			if ($postCode_is == 1 && strpos($userSysArr['US_userFieldStr'],'|邮编|') !== false){
				$record['UE_postCode']	= $postCode;
			}
			if ($weixin_is == 1 && strpos($userSysArr['US_userFieldStr'],'|微信|') !== false){
				$record['UE_weixin']	= $weixin;
			}
			if ($alipay_is == 1 && strpos($userSysArr['US_userFieldStr'],'|支付宝|') !== false){
				$record['UE_alipay']	= $alipay;
			}
			if ($qq_is == 1 && strpos($userSysArr['US_userFieldStr'],'|QQ|') !== false){
				$record['UE_qq']		= $qq;
			}
			if ($ww_is == 1 && strpos($userSysArr['US_userFieldStr'],'|旺旺|') !== false){
				$record['UE_ww']		= $ww;
			}
			if ($web_is == 1 && strpos($userSysArr['US_userFieldStr'],'|个人主页|') !== false){
				$record['UE_web']		= $web;
			}
			if ($note_is == 1 && strpos($userSysArr['US_userFieldStr'],'|备注|') !== false){
				$record['UE_note']		= $note;
			}
			$record['UE_pageNum']		= $pageNum;
			// $record['UE_pageWapNum']	= $pageWapNum;
			if ($itemTotal == $itemSucc){
				if (strpos($UE_authStr,'|完善资料|') === false){
					$record['UE_authStr']	= $UE_authStr .'|完善资料|';
				}
			}else{
				if (strpos($UE_authStr,'|完善资料|') !== false){
					$record['UE_authStr']	= str_replace('|完善资料|','',$UE_authStr);
				}
			}
			$alertAddi = '，完善资料'. $itemSucc .'/'. $itemTotal;
			break;

		case 'username':
			Users::CheckUserName('check',$newUsername);

			if ($userSysArr['US_regBadWord'] != ''){
				$newUsername = strtolower($newUsername);
				$badWordArr = explode('|',strtolower($userSysArr['US_regBadWord']));
				foreach ($badWordArr as $str){
					if (strlen($str)>0 && strpos($newUsername,$str)!==false){
						JS::AlertBackEnd('用户名中含禁止注册关键词（'. $str .'）');
					}
				}
			}
			$record['UE_username']	= $newUsername;
			break;

		case 'password':
			if (strpos($UE_apiStr,'|web:::') !== false){
				if (md5(md5($userpwdOld) . $UE_userKey) != $UE_userpwd){
					JS::AlertBackEnd('原密码错误！');
				}
			}else{
				$record['UE_apiStr']	= '|web:::1|'. $UE_apiStr;
			}
			$newUserKey = OT::RndChar(5);
			$newUserpwd = md5(md5($userpwd) . $newUserKey);
			$record['UE_userpwd']	= $newUserpwd;
			$record['UE_userKey']	= $newUserKey;
			break;

		case 'mail':
			if ($userSysArr['US_isAuthMail'] == 1 && AppMail::Jud()){
				if (strpos($UE_authStr,'|邮箱|') === false && $isAuthMail == 1){
					$resArr = AppMail::CheckMailCode('check', $mail, $mailCode, $userID);
					if (! $resArr['res']){
						JS::AlertBackEnd($resArr['note']);
					}
				}elseif (strpos($UE_authStr,'|邮箱|') !== false && $userSysArr['US_isLockMail'] == 1){
					JS::AlertBackEnd('邮箱验证通过后禁止用户修改，如要修改请联系网站管理员处理。');
				}else{
					$resArr = AppMail::CheckMailCode('check', $UE_mail, $mailOldCode, $userID);
					if (! $resArr['res']){
						JS::AlertBackEnd($resArr['note']);
					}

					$resArr = AppMail::CheckMailCode('rev', $mail, $mailCode, $userID);
					if (! $resArr['res']){
						JS::AlertBackEnd($resArr['note']);
					}
				}
				if (strpos($UE_authStr,'|邮箱|') === false){
					$record['UE_authStr'] = $UE_authStr .'|邮箱|';
				}
			}else{
				if (strpos($UE_authStr,'|邮箱|') !== false){
					$record['UE_authStr'] = str_replace('|邮箱|', '', $UE_authStr);
				}
			}
			if (md5(md5($mailPwd) . $UE_userKey) != $UE_userpwd){
				JS::AlertBackEnd('登录密码错误！');
			}
			$record['UE_mail']	= $mail;
			break;

		case 'phone':
			if ($userSysArr['US_isAuthPhone'] == 1 && AppPhone::Jud()){
				if (strpos($UE_authStr,'|手机|') === false && $isAuthPhone == 1){
					$resArr = AppPhone::CheckPhoneCode('check', $phone, $phoneCode, $userID);
					if (! $resArr['res']){
						JS::AlertBackEnd($resArr['note']);
					}
				}elseif (strpos($UE_authStr,'|手机|') !== false && $userSysArr['US_isLockPhone'] == 1){
					JS::AlertBackEnd('手机号验证通过后禁止用户修改，如要修改请联系网站管理员处理。');
				}else{
					$resArr = AppPhone::CheckPhoneCode('check', $UE_phone, $phoneOldCode, $userID);
					if (! $resArr['res']){
						JS::AlertBackEnd($resArr['note']);
					}

					$resArr = AppPhone::CheckPhoneCode('rev', $phone, $phoneCode, $userID);
					if (! $resArr['res']){
						JS::AlertBackEnd($resArr['note']);
					}
				}
				if (strpos($UE_authStr,'|手机|') === false){
					$record['UE_authStr'] = $UE_authStr .'|手机|';
				}
			}else{
				if (strpos($UE_authStr,'|手机|') !== false){
					$record['UE_authStr'] = str_replace('|手机|', '', $UE_authStr);
				}
			}
			if (md5(md5($phonePwd) . $UE_userKey) != $UE_userpwd){
				JS::AlertBackEnd('登录密码错误！');
			}
			$record['UE_phone']	= $phone;
			break;

		case 'question':
			$answerOld	= md5(md5($answerOld) . $UE_answerKey);
			if (strlen($UE_question)>0 && $answerOld!=$UE_answer){
				JS::AlertBackEnd('旧答案错误！');
			}

			$newAnswerKey = OT::RndChar(5);
			$record['UE_question']		= $question;
			$record['UE_answer']		= md5(md5($answer) . $newAnswerKey);
			$record['UE_answerKey']		= $newAnswerKey;
			break;

		case 'app':
			if (strlen($dashangImg1 . $dashangImg2 . $dashangImg3) >= 5){
				$record['UE_dashangImg1']	= $dashangImg1;
				$record['UE_dashangImg2']	= $dashangImg2;
				$record['UE_dashangImg3']	= $dashangImg3;
			}
			if ($face > 0){
				$record['UE_faceMode']	= 3;
				$record['UE_face']		= $face;
			}
			break;

	}
	$UE_userpwd		= null;
	$UE_userKey		= null;
	$UE_answer		= null;
	$UE_answerKey	= null;

	$judResult = $DB->UpdateParam('users',$record,'UE_ID='. $userID);
		if (! $judResult){
			JS::AlertBackEnd('更新数据失败，请重试下.');
		}

	if ($revType == 'username'){
		Users::SetCookies('username',$newUsername);

	}elseif ($revType == 'info'){
		Users::SetCookies('usercall',$realname);

	}elseif ($revType == 'password'){
		Users::Update($UE_ID,$UE_username,$newUserpwd,$UE_mail,$UE_realname,$scoreStr);

	}elseif ($revType == 'app'){
		if (strlen($dashangImg1Old) > 5 && $dashangImg1Old != $dashangImg1){
			File::Del(OT_ROOT . UsersFileDir . $dashangImg1Old);
			File::Del(OT_ROOT . UsersFileDir .'thumb_'. $dashangImg1Old);
		}
		if (strlen($dashangImg2Old) > 5 && $dashangImg2Old != $dashangImg2){
			File::Del(OT_ROOT . UsersFileDir . $dashangImg2Old);
			File::Del(OT_ROOT . UsersFileDir .'thumb_'. $dashangImg2Old);
		}
		if (strlen($dashangImg3Old) > 5 && $dashangImg3Old != $dashangImg3){
			File::Del(OT_ROOT . UsersFileDir . $dashangImg3Old);
			File::Del(OT_ROOT . UsersFileDir .'thumb_'. $dashangImg3Old);
		}

	}

	if ($revType == 'question' && $UE_question != ''){
		$alertMode = '建立密保';
	}else{
		$alertMode = '修改';
	}

	JS::AlertHrefEnd($alertMode .'成功'. $alertAddi .'.','usersCenter.php?mudi=revInfo&revType='. $revType);
}


// 修改每页条数
function RevPageNum(){
	global $DB;

	$pageNum	= OT::GetInt('pageNum');
	$backUrl	= OT::GetStr('backUrl');
		if ($pageNum < 10){ $pageNum = 10; }
		elseif ($pageNum > 100){ $pageNum = 100; }
		if (strlen($backUrl) < 5){
			JS::AlertBackEnd('回调地址不能为空.');
		}

	$userRow = Users::Open('get','','',$judUserErr);
	if ((! $userRow) || $judUserErr != ''){
		JS::AlertBackEnd('会员未登录，请先登录再操作.');
	}

	$judResult = $DB->UpdateParam('users', array('UE_pageNum' => $pageNum), 'UE_ID='. $userRow['UE_ID']);
	JS::HrefEnd($backUrl);
}

?>
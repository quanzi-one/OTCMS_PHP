<?php
require(dirname(__FILE__) .'/check.php');
$skin->CheckIframe();


/* **** 版权所有 ****

 网钛CMS(OTCMS)
 程序开发：网钛科技
 官方网站：http://otcms.com
 联系ＱＱ：877873666
 联系邮箱：877873666@qq.com

 **** 版权所有 **** */

//用户检测
$MB->Open('','login',10);



switch ($mudi){
	case 'add':
		$menuFileID = 184;
		$MB->IsSecMenuRight('alertBack',$menuFileID,$dataType);
		AddOrRev();
		break;

	case 'rev':
		$menuFileID = 187;
		$MB->IsSecMenuRight('alertBack',$menuFileID,$dataType);
		AddOrRev();
		break;

	case 'del':
		$menuFileID = 188;
		$MB->IsSecMenuRight('alert',$menuFileID,$dataType);
		del();
		break;

	case 'moreDel':
		$menuFileID = 188;
		$MB->IsSecMenuRight('alert',$menuFileID,$dataType);
		MoreDel();
		break;

	case 'revInfo':
		$menuFileID = 187;
		$MB->IsSecMenuRight('alert',$menuFileID,$dataType);
		RevInfo();
		break;

	case 'groupMove':
		$menuFileID = 187;
		$MB->IsSecMenuRight('alertBack',$menuFileID,$dataType);
		GroupMoveDeal();
		break;

	case 'moreSet':
		$menuFileID = 187;
		$MB->IsSecMenuRight('alertBack',$menuFileID,$dataType);
		MoreSetDeal();
		break;

	default:
		die('err');
}

$MB->Close();
$DB->Close();





// 新增/修改会员信息
function AddOrRev(){
	global $DB,$mudi,$dataType,$dataTypeCN;

	$backURL	= OT::PostStr('backURL');
	$dataType	= OT::PostStr('dataType');
	$dataTypeCN	= OT::PostStr('dataTypeCN');
	$dataID		= OT::PostInt('dataID');
	$scoreOld1	= OT::PostInt('scoreOld1');
	$scoreOld2	= OT::PostInt('scoreOld2');
	$scoreOld3	= OT::PostInt('scoreOld3');

	$groupID	= OT::PostInt('groupID');
	$isGroupTime= OT::PostInt('isGroupTime');
	$groupTime	= OT::PostStr('groupTime');
	$authStr	= OT::Post('authStr');
		if (is_array($authStr)){ $authStr = implode('',$authStr); }
	$username	= OT::PostRegExpStr('username','sql');
	$userpwd	= OT::PostStr('userpwd');
	$mail		= OT::PostStr('mail');
	$question	= OT::PostStr('question');
	$answer		= OT::PostStr('answer');
	$realname	= OT::PostStr('realname');
	$sex		= OT::PostStr('sex');
	$city		= OT::PostStr('city');
	$address	= OT::PostStr('address');
	$phone		= OT::PostStr('phone');
	$fax		= OT::PostStr('fax');
	$qq			= str_replace('，',',',OT::PostStr('qq'));
	$ww			= OT::PostStr('ww');
	$weixin		= OT::PostStr('weixin');
	$alipay		= OT::PostStr('alipay');
	$web		= OT::PostStr('web');
	$note		= OT::PostStr('note');
	$event		= OT::Post('event');
		if (is_array($event)){ $event = implode(',',$event); }
	$state		= OT::PostInt('state');
	$isRevScore	= OT::PostInt('isRevScore');
	$score1		= OT::PostInt('score1');
	$score2		= OT::PostInt('score2');
	$score3		= OT::PostInt('score3');

	if ($username==''){
		JS::AlertBackEnd('表单数据接收不全');
	}


	$checkexe=$DB->query('select UE_ID from '. OT_dbPref .'users where UE_username='. $DB->ForStr($username) .' and UE_ID<>'. $dataID .' limit 1');
		if ($checkexe->fetch()){
			JS::AlertBackEnd('该用户名已被占用');
		}
	unset($checkexe);

	$todayTime	= TimeDate::Get();
	$userIP		= Users::GetIp();
	$record=array();
	$record['UE_groupID']		= $groupID;
	$record['UE_isGroupTime']	= $isGroupTime;
	if (strtotime($groupTime)){ $record['UE_groupTime'] = $groupTime; }
	$record['UE_authStr']		= $authStr;
	$record['UE_username']		= $username;
	if ($userpwd!=''){
		$userKey = OT::RndChar(5);
		$record['UE_userpwd']	= md5(md5($userpwd) . $userKey);
		$record['UE_userKey']	= $userKey;
	}
	$record['UE_question']		= $question;
	if ($answer!=''){
		$answerKey = OT::RndChar(5);
		$record['UE_answer']	= md5(md5($answer) . $answerKey);
		$record['UE_answerKey']	= $answerKey;
	}
	$record['UE_mail']			= $mail;
	$record['UE_realname']		= $realname;
	$record['UE_sex']			= $sex;
	$record['UE_city']			= $city;
	$record['UE_address']		= $address;
	$record['UE_phone']			= $phone;
	$record['UE_fax']			= $fax;
	$record['UE_qq']			= $qq;
	$record['UE_ww']			= $ww;
	$record['UE_weixin']		= $weixin;
	$record['UE_alipay']		= $alipay;
	$record['UE_web']			= $web;
	$record['UE_note']			= $note;
	$record['UE_event']			= $event;
	$record['UE_state']			= $state;
	if ($isRevScore == 1){
		$record['UE_score1']	= $score1;
		$record['UE_score2']	= $score2;
		$record['UE_score3']	= $score3;
	}
	
	if ($dataID==0){
		$record['UE_time']			= $todayTime;
		$record['UE_loginTime']		= $todayTime;
		$record['UE_regType']		= 'back';
		$record['UE_regIP']			= $userIP;

		$alertMode='新增';
		$judResult = $DB->InsertParam('users',$record);
			if ($judResult){
				if (AppUserScore::IsAdd($score1, $score2, $score3)){
					$dataID = $DB->GetOne('select max(UE_ID) from '. OT_dbPref .'users');
					$scoreArr = array();
					$scoreArr['UM_userID']		= $dataID;
					$scoreArr['UM_username']	= $username;
					$scoreArr['UM_type']		= 'reg';
					$scoreArr['UM_score1']		= $score1;
					$scoreArr['UM_score2']		= $score2;
					$scoreArr['UM_score3']		= $score3;
					$scoreArr['UM_remScore1']	= $score1;
					$scoreArr['UM_remScore2']	= $score2;
					$scoreArr['UM_remScore3']	= $score3;
					$scoreArr['UM_note']		= '后台注册成功';
					AppUserScore::AddData($scoreArr);
				}
			}
	}else{
		$alertMode='修改';
		$judResult = $DB->UpdateParam('users',$record,'UE_ID='. $dataID);
			if ($judResult){
				if (AppUserScore::IsAdd($scoreOld1-$score1, $scoreOld2-$score2, $scoreOld3-$score3)){
					$scoreArr = array();
					$scoreArr['UM_userID']		= $dataID;
					$scoreArr['UM_username']	= $username;
					$scoreArr['UM_type']		= 'other';
					$scoreArr['UM_score1']		= $score1-$scoreOld1;
					$scoreArr['UM_score2']		= $score2-$scoreOld2;
					$scoreArr['UM_score3']		= $score3-$scoreOld3;
					$scoreArr['UM_remScore1']	= $score1;
					$scoreArr['UM_remScore2']	= $score2;
					$scoreArr['UM_remScore3']	= $score3;
					$scoreArr['UM_note']		= '管理员后台对您进行积分调整';
					AppUserScore::AddData($scoreArr);
				}
			}
	}
		if ($judResult){
			$alertResult = '成功';

		}else{
			$alertResult = '失败';
		}

	Adm::AddLog(array(
		'theme'	=> $username,
		'note'	=> '【'. $dataTypeCN .'】'. $alertMode . $alertResult .'！',
		));

	JS::AlertHref(''. $alertMode . $alertResult .'.',$backURL);
}



// 删除
function del(){
	global $DB,$dataType,$dataTypeCN;

	$dataID		= OT::GetInt('dataID');
	$theme		= OT::GetStr('theme');

	if ($dataID<=0){
		JS::AlertEnd('指定ID错误');
	}

	$judResult = $DB->query('delete from '. OT_dbPref .'users where UE_ID='. $dataID);
		if ($judResult){
			$alertResult = '成功';
		}else{
			$alertResult = '失败';
		}

	Adm::AddLog(array(
		'title'	=> '用户名',
		'theme'	=> $theme,
		'note'	=> '【'. $dataTypeCN .'】删除'. $alertResult .'！',
		));

	echo('
	<script language="javascript" type="text/javascript">
	parent.$id("data'. $dataID .'").style.display="none";
	</script>
	');
}



// 批量删除
function MoreDel(){
	global $DB;

	$dataType	= OT::PostStr('dataType');
	$dataTypeCN	= OT::PostStr('dataTypeCN');
	$backURL	= OT::PostStr('backURL');
	$selDataID	= OT::Post('selDataID');

	if (count($selDataID)<=0){
		JS::AlertBackEnd('请先选择要删除的记录.');
	}

	$whereStr='';
	for ($i=0; $i<count($selDataID); $i++){
		$whereStr .= ','. intval($selDataID[$i]);
	}
	if ($whereStr == ''){
		JS::AlertBackEnd('请先选择要删除的记录.');
	}

	$judResult = $DB->query('delete from '. OT_dbPref .'users where UE_ID in (0'. $whereStr .')');
		if ($judResult){
			$alertResult = '成功';
		}else{
			$alertResult = '失败';
		}

	Adm::AddLog(array(
		'note'		=> '【'. $dataTypeCN .'】批量删除'. $alertResult .'！',
		));

	JS::AlertHrefEnd('批量删除'. $alertResult .'.',$backURL);

}



// 修改信息
function RevInfo(){
	global $DB,$dataType,$dataTypeCN;

	$theme		= OT::GetStr('theme');
	$userID		= OT::GetInt('userID');
	$mode		= OT::GetStr('mode');
	$type		= OT::GetStr('type');

	$record = array();
	switch ($mode){
		case 'state':
			$revName = '状态';
			$state	= OT::GetInt('state');
			$record['UE_state'] = $state;
			if ($state == 0){ $revStr = '待审核'; }else{ $revStr = '已审核'; }
			break;
	
		case 'qiandaoTime':
			$revName = '签到时间';
			$qiandaoTime	= OT::GetStr('qiandaoTime');
			$record['UE_qiandaoTime'] = $qiandaoTime;
			$revStr = $qiandaoTime;
			break;
	
		case 'apiStr':
			$revName = '快捷登录';
			switch ($type){
				case 'qq':		$revStr = '解除QQ绑定';		break;
				case 'weibo':	$revStr = '解除新浪微博绑定';	break;
				case 'weixin':	$revStr = '解除微信绑定';	break;
				default :	JS::AlertEnd('type参数不明（'. $type .'）.');	break;
			}

			$UE_apiStr = $DB->GetOne('select UE_apiStr from '. OT_dbPref .'users where UE_ID='. $userID);

			if (strpos($UE_apiStr,'|web:::') === false){
				JS::AlertEnd('检查到该账户登录密码尚未初始化，请初始化(修改)登录密码才能做解除绑定操作.');
			}

			$signStart = strpos($UE_apiStr,'|'. $type .'::');
			if ($signStart === false){
				JS::AlertEnd('该账号早已解除绑定或者还未绑定过，请刷新页面重新操作下.');
			}
			$apiStr = Str::GetMark($UE_apiStr,'|'. $type .'::','|',true,true);
			$UE_apiStr = str_replace($apiStr, '', $UE_apiStr);
			if ($type == 'weixin'){
				$apiStr = Str::GetMark($UE_apiStr,'|'. $type .'2::','|',true,true);
				if (strlen($apiStr) > 5){ $UE_apiStr = str_replace($apiStr, '', $UE_apiStr); }
			}

			$record['UE_apiStr'] = $UE_apiStr;
			break;
	
		default :
			JS::AlertEnd('类型错误');
			break;
	}

	$judResult = $DB->UpdateParam('users', $record, 'UE_ID='. $userID);
		if ($judResult){
			$alertResult = '成功';
		}else{
			$alertResult = '失败';
		}

	Adm::AddLog(array(
		'title'	=> '用户名',
		'theme'	=> $theme,
		'note'	=> '【'. $dataTypeCN .'】设置'. $revName .'{'. $revStr .'}'. $alertResult .'！',
		));

	echo('
	<script language="javascript" type="text/javascript">
	alert("设置'. $revName .'（'. $revStr .'）'. $alertResult .'");parent.document.location.reload();
	</script>
	');
}



// 会员组批量移动
function GroupMoveDeal(){
	global $DB,$mudi,$menuFileID,$menuTreeID,$dataType,$dataTypeCN,$systemArr;

	$backURL		= OT::PostStr('backURL');
	$dataType		= OT::PostStr('dataType');
	$dataTypeCN		= OT::PostStr('dataTypeCN');
	$groupMoveTo	= OT::PostInt('groupMoveTo');
	$groupMoveToCN	= OT::PostRegExpStr('groupMoveToCN','sql');
	$selDataID		= OT::Post('selDataID');

	if (count($selDataID)<=0){
		JS::AlertBackEnd('请先选择要移动的会员.');
	}
	if ($groupMoveTo <= 0){
		JS::AlertBackEnd('请选择批量移动的会员组.');
	}

	$whereStr='';
	for ($i=0; $i<count($selDataID); $i++){
		$whereStr .= ','. intval($selDataID[$i]);
	}
	if ($whereStr == ''){
		JS::AlertBackEnd('请先选择要移动的会员.');
	}

	$judResult = $DB->query('update '. OT_dbPref .'users set UE_groupID='. $groupMoveTo .' where UE_ID in (0'. $whereStr .')');
	if ($judResult){
		$alertResult = '成功';
	}else{
		$alertResult = '失败';
	}

	Adm::AddLog(array(
		'note'		=> '【'. $dataTypeCN .'】会员组批量移动到['. $groupMoveToCN .']'. $alertResult .'！',
		));

	JS::AlertHref('会员组批量移动'. $alertResult .'.',$backURL);
}



// 批量设置
function MoreSetDeal(){
	global $DB,$mudi,$menuFileID,$menuTreeID,$dataType,$dataTypeCN,$systemArr;

	$backURL		= OT::PostStr('backURL');
	$dataType		= OT::PostStr('dataType');
	$dataTypeCN		= OT::PostStr('dataTypeCN');
	$moreSetTo		= OT::PostRegExpStr('moreSetTo','sql');
	$moreSetToCN	= OT::PostRegExpStr('moreSetToCN','sql');
	$selDataID		= OT::Post('selDataID');

	if (count($selDataID)<=0){
		JS::AlertBackEnd('请先选择要设置的记录.');
	}
	if ($moreSetTo==''){
		JS::AlertBackEnd('请选择批量设置的操作.');
	}

	$whereStr = '';
	for ($i=0; $i<count($selDataID); $i++){
		$whereStr .= ','. intval($selDataID[$i]);
	}
	if ($whereStr == ''){
		JS::AlertBackEnd('请先选择要设置的记录.');
	}

	if (in_array($moreSetTo, array('groupTime1','groupTime0','state1','state0'))){
		switch ($moreSetTo){
			case 'groupTime1':		$dealStr = 'UE_isGroupTime=1';	break;
			case 'groupTime0':		$dealStr = 'UE_isGroupTime=0';	break;
			case 'state1':			$dealStr = 'UE_state=1';		break;
			case 'state0':			$dealStr = 'UE_state=0';		break;
		}

		$judResult = $DB->query('update '. OT_dbPref .'users set '. $dealStr .' where UE_ID in (0'. $whereStr .')');
		if ($judResult){
			$alertResult = '成功';
		}else{
			$alertResult = '失败';
		}

	}else{
		$loadexe = $DB->query('select UE_ID,UE_authStr from '. OT_dbPref .'users where UE_ID in (0'. $whereStr .')');
		while ($row = $loadexe->fetch()){
			$authStr = $row['UE_authStr'];
			switch ($moreSetTo){
				case 'shiming1':		$dealIs = 1; $dealStr = '|实名认证|';	break;
				case 'shiming0':		$dealIs = 0; $dealStr = '|实名认证|';	break;
				case 'mailAudit1':		$dealIs = 1; $dealStr = '|邮箱|';		break;
				case 'mailAudit0':		$dealIs = 0; $dealStr = '|邮箱|';		break;
				case 'phoneAudit1':		$dealIs = 1; $dealStr = '|手机|';		break;
				case 'phoneAudit0':		$dealIs = 0; $dealStr = '|手机|';		break;
				default :
					JS::AlertBackEnd('目的不明确（'. $moreSetTo .'）.');
					break;
			}
			if ($dealIs == 1){
				if (strpos($authStr,$dealStr) !== false){
					continue;
				}else{
					$authStr .= $dealStr;
				}
			}else{
				if (strpos($authStr,$dealStr) !== false){
					$authStr = str_replace($dealStr,'',$authStr);
				}else{
					continue;
				}
			}
			$DB->query('update '. OT_dbPref .'users set UE_authStr='. $DB->ForStr($authStr) .' where UE_ID='. $row['UE_ID']);
		}
		unset($loadexe);


		$alertResult = '完成';
	}


	Adm::AddLog(array(
		'note'		=> '【'. $dataTypeCN .'】批量设置成['. $moreSetToCN .']'. $alertResult .'！',
		));

	JS::AlertHref('批量设置'. $alertResult .'.',$backURL);
}

?>
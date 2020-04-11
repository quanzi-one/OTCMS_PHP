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


//打开用户表，并检测用户是否登录
$MB->Open('','login');

$skin->WebTop();


echo('
<script language="javascript" type="text/javascript" src="js/inc/list.js?v='. OT_VERSION .'"></script>
<script language="javascript" type="text/javascript" src="js/users.js?v='. OT_VERSION .'"></script>
');


$userSysArr = Cache::PhpFile('userSys');

//$dataTypeCN = '会员';


switch ($mudi){
	case 'add':
		$MB->IsSecMenuRight('alertBack',184,$dataType);
		AddOrRev();
		break;

	case 'rev':
		$MB->IsSecMenuRight('alertBack',187,$dataType);
		AddOrRev();
		break;

	case 'manage':
		$MB->IsSecMenuRight('alertBack',185,$dataType);
		manage();
		break;

	case 'recom':
		$MB->IsSecMenuRight('alertBack',185,$dataType);
		AppRecom::AdmManage();
		break;

	case 'online':
		$MB->IsSecMenuRight('alertBack',185,$dataType);
		online();
		break;

	case 'show':
		$MB->IsSecMenuRight('alertClose',186,$dataType);
		show();
		break;

	default:
		die('err');
}

$skin->WebBottom();

$MB->Close();
$DB->Close();





// 添加/修改会员
function AddOrRev(){
	global $DB,$skin,$mudi,$dataType,$dataTypeCN,$userSysArr;

	$regScoreArr = Area::UserScore('reg');

	$dataMode		= OT::GetStr('dataMode');
	$dataModeStr	= OT::GetStr('dataModeStr');
	$backURL		= OT::GetStr('backURL');
	$dataID			= OT::GetInt('dataID');

	if ($mudi=='rev'){
		$revexe=$DB->query('select * from '. OT_dbPref .'users where UE_ID='. $dataID);
		if (! $row = $revexe->fetch()){
			JS::AlertBackEnd('无该记录！');
		}
		$UE_ID			= $row['UE_ID'];
		$UE_regType		= $row['UE_regType'];
		$UE_username	= $row['UE_username'];
		$UE_mail		= $row['UE_mail'];
		$UE_question	= $row['UE_question'];
		$UE_realname	= $row['UE_realname'];
		$UE_groupID		= $row['UE_groupID'];
		$UE_isGroupTime	= $row['UE_isGroupTime'];
		$UE_groupTime	= $row['UE_groupTime'];
		$UE_authStr		= $row['UE_authStr'];
		$UE_sex			= $row['UE_sex'];
		$UE_city		= $row['UE_city'];
		$UE_address		= $row['UE_address'];
		$UE_phone		= $row['UE_phone'];
		$UE_fax			= $row['UE_fax'];
		$UE_qq			= $row['UE_qq'];
		$UE_ww			= $row['UE_ww'];
		$UE_weixin		= $row['UE_weixin'];
		$UE_alipay		= $row['UE_alipay'];
		$UE_web			= $row['UE_web'];
		$UE_note		= $row['UE_note'];
		$UE_event		= $row['UE_event'];
		$UE_state		= $row['UE_state'];
		$UE_score1		= $row['UE_score1'];
		$UE_score2		= $row['UE_score2'];
		$UE_score3		= $row['UE_score3'];
		$isRevScore		= 0;
		unset($revexe);

		$mudiCN='修改';
	}else{
		$UE_ID			= 0;
		$UE_regType		= '';
		$UE_username	= '';
		$UE_mail		= '';
		$UE_question	= '';
		$UE_realname	= '';
		$UE_groupID		= $userSysArr['US_regGroupID'];
		$UE_isGroupTime	= 0;
		$UE_groupTime	= '';
		$UE_authStr		= '';
		$UE_sex			= '';
		$UE_city		= '';
		$UE_address		= '';
		$UE_phone		= '';
		$UE_fax			= '';
		$UE_qq			= '';
		$UE_ww			= '';
		$UE_weixin		= '';
		$UE_alipay		= '';
		$UE_web			= '';
		$UE_note		= '';
		$UE_event		= '';
		$UE_state		= 1;
		$UE_score1		= $regScoreArr['US_score1'];
		$UE_score2		= $regScoreArr['US_score2'];
		$UE_score3		= $regScoreArr['US_score3'];
		$isRevScore		= 1;

		$mudiCN='添加';
	}

	$todayTime = TimeDate::Get();
	$todayWeek = TimeDate::Add('d',7,$todayTime);
	$todayMonth = TimeDate::Add('d',31,$todayTime);
	$today3Month = TimeDate::Add('d',93,$todayTime);
	$todayYear = TimeDate::Add('y',1,$todayTime);

	if ($mudi=='rev'){
		echo('<div onclick="history.back();" class="font2_1 padd8 pointer">&lt;&lt;&ensp;【返回上级】</div>');
	}

	echo('
	<form id="dealForm" name="dealForm" method="post" action="users_deal.php?mudi='. $mudi .'&nohrefStr=close" onsubmit="return CheckForm()">
	<input type="hidden" id="dataType" name="dataType" value="'. $dataType .'" />
	<input type="hidden" id="dataTypeCN" name="dataTypeCN" value="'. $dataTypeCN .'" />
	<input type="hidden" id="dataMode" name="dataMode" value="'. $dataMode .'" />
	<input type="hidden" id="dataModeStr" name="dataModeStr" value="'. $dataModeStr .'" />
	<input type="hidden" id="dataID" name="dataID" value="'. $dataID .'" />
	');
		if ($backURL!=''){
	echo('<input type="hidden" id="backURL" name="backURL" value="'. $backURL .'" />');
		}else{
	echo('<script language="javascript" type="text/javascript">document.write(\'<input type="hidden" id="backURL" name="backURL" value="\'+ document.location.href +\'" />\')</script>');
		}

	$skin->TableTop('share_'. $mudi .'.gif','',$mudiCN . $dataTypeCN .'资料');
		echo('
		<table style="width:98%;" align="center" cellpadding="0" cellspacing="0" summary="" class="padd3">
		<tr>
			<td align="right" style="width:120px;">类型：</td>
			<td>'. Users::regTypeCN($UE_regType) .'</td>
		</tr>
		<tr>
			<td align="right" style="width:120px;">'. Skin::RedSign() .'会员组：</td>
			<td>
				<select id="groupID" name="groupID">
				');

				$gexe=$DB->query('select UG_ID,UG_theme from '. OT_dbPref .'userGroup where UG_state=1 order by UG_rank ASC');
				while ($row = $gexe->fetch()){
					echo('<option value="'. $row['UG_ID'] .'" '. Is::Selected($UE_groupID,$row['UG_ID']) .'>'. $row['UG_theme'] .'</option>');
				}
				unset($gexe);

				echo('
				</select>&ensp;
				<label><input type="checkbox" id="isGroupTime" name="isGroupTime" value="1" '. Is::Checked($UE_isGroupTime,1) .' onclick="CheckGroupTime()" />开启到期时间</label>
			</td>
		</tr>
		<tr id="groupTimeBox" style="display:none;">
			<td align="right">'. Skin::RedSign() .'会员组到期时间：</td>
			<td align="left">
				<input type="text" id="groupTime" name="groupTime" size="22" style="width:160px;" value="'. $UE_groupTime .'" onfocus=\'WdatePicker({dateFmt:"yyyy-MM-dd HH:mm:ss"})\' class="Wdate" />&ensp;&ensp;
				<input type="button" value="7天" onclick=\'$id("groupTime").value = "'. $todayWeek .'"\' />&ensp;&ensp;
				<input type="button" value="31天" onclick=\'$id("groupTime").value = "'. $todayMonth .'"\' />&ensp;&ensp;
				<input type="button" value="93天" onclick=\'$id("groupTime").value = "'. $today3Month .'"\' />&ensp;&ensp;
				<input type="button" value="1年" onclick=\'$id("groupTime").value = "'. $todayYear .'"\' />&ensp;&ensp;
			</td>
		</tr>
		<tr>
			<td align="right">'. Skin::RedSign() .'用户名：</td>
			<td>
				<input type="text" id="username" name="username" size="25" style="width:300px;" value="'. $UE_username .'" onblur=\'CheckUserName("username",this.value)\' />
				<span id="usernameIsOk"></span>
				<span id="usernameStr"></span>
			</td>
		</tr>
		<tr>
			<td align="right">'. Skin::RedSign() .'登录密码：</td>
			<td><input type="text" id="userpwd" name="userpwd" size="25" style="width:300px;" /><span class="font2_2">&ensp;不改密码请留空</span></td>
		</tr>
		<tr>
			<td align="right">密保问题：</td>
			<td><input type="text" id="question" name="question" size="25" style="width:300px;" value="'. $UE_question .'" />&ensp;</td>
		</tr>
		<tr>
			<td align="right">密保答案：</td>
			<td><input type="text" id="answer" name="answer" size="25" style="width:300px;" /><span class="font2_2">&ensp;不改答案请留空</span></td>
		</tr>
		<tr>
			<td align="right">昵称：</td>
			<td style="padding:3px">
				<input type="text" id="realname" name="realname" size="25" style="width:300px;" value="'. $UE_realname .'" />&ensp;
				<label style="color:blue;font-weight:bold;"><input type="checkbox" name="authStr[]" value="|实名认证|" '. Is::InstrChecked($UE_authStr,'|实名认证|') .' />已实名认证</label>
			</td>
		</tr>
		<tr>
			<td align="right">邮箱：</td>
			<td>
				<input type="text" id="mail" name="mail" size="25" style="width:300px;" value="'. $UE_mail .'" />&ensp;
				<label><input type="checkbox" name="authStr[]" value="|邮箱|" '. Is::InstrChecked($UE_authStr,'|邮箱|') .' />邮箱已验证</label>
				&ensp;<input type="button" value="使用QQ邮箱" onclick="LoadQQmail()" />
			</td>
		</tr>
		<tr>
			<td align="right">手机：</td>
			<td>
				<input type="text" id="phone" name="phone" size="25" style="width:300px;" value="'. $UE_phone .'" />&ensp;
				<label><input type="checkbox" name="authStr[]" value="|手机|" '. Is::InstrChecked($UE_authStr,'|手机|') .' />手机已验证</label>
			</td>
		</tr>
		<tr>
			<td align="right">QQ：</td>
			<td>
				<input type="text" id="qq" name="qq" size="25" style="width:300px;" value="'. $UE_qq .'" /><!-- &ensp;
				<label><input type="checkbox" name="authStr[]" value="|QQ|" '. Is::InstrChecked($UE_authStr,'|QQ|') .' />QQ已验证</label> -->
				<span class="font2_2">&ensp;如多个QQ用逗号“,”隔开</span>
			</td>
		</tr>
		<tr>
			<td align="right">旺旺：</td>
			<td><input type="text" id="ww" name="ww" size="25" style="width:300px;" value="'. $UE_ww .'" />&ensp;</td>
		</tr>
		<tr>
			<td align="right">微信：</td>
			<td><input type="text" id="weixin" name="weixin" size="25" style="width:300px;" value="'. $UE_weixin .'" />&ensp;</td>
		</tr>
		<tr>
			<td align="right">支付宝：</td>
			<td><input type="text" id="alipay" name="alipay" size="25" style="width:300px;" value="'. $UE_alipay .'" />&ensp;</td>
		</tr>
		<tr>
			<td align="right">性别：</td>
			<td>
				<input type="radio" name="sex" value="男" '. Is::Checked($UE_sex,'男') .' />男&ensp;&ensp;
				<input type="radio" name="sex" value="女" '. Is::Checked($UE_sex,'女') .' />女
			</td>
		</tr>
		<tr>
			<td align="right">城市：</td>
			<td><input type="text" id="city" name="city" size="25" style="width:300px;" value="'. $UE_city .'" />&ensp;</td>
		</tr>
		<tr>
			<td align="right">收货地址：</td>
			<td><input type="text" id="address" name="address" size="25" style="width:300px;" value="'. $UE_address .'" />&ensp;</td>
		</tr>
		<tr>
			<td align="right">网址：</td>
			<td><input type="text" id="web" name="web" size="25" style="width:300px;" value="'. $UE_web .'" />&ensp;</td>
		</tr>
		<tr>
			<td align="right">备注：</td>
			<td><textarea id="note" name="note" style="width:300px;height:60px;" >'. $UE_note .'</textarea>&ensp;</td>
		</tr>
		<tr>
			<td align="right">状态：</td>
			<td>
				<label><input type="radio" name="state" value="1" '. Is::Checked($UE_state,1) .' />已审核</label>&ensp;&ensp;
				<label><input type="radio" name="state" value="0" '. Is::Checked($UE_state,0) .' />未审核</label>
			</td>
		</tr>
		<tr>
			<td align="right" style="color:red;">修改积分：</td>
			<td>
				<label><input type="radio" id="isRevScore1" name="isRevScore" value="1" '. Is::Checked($isRevScore,1) .' onclick="CheckScoreBox();" />是</label>&ensp;&ensp;
				<label><input type="radio" id="isRevScore0" name="isRevScore" value="0" '. Is::Checked($isRevScore,0) .' onclick="CheckScoreBox();" />否</label>
			</td>
		</tr>
		<tbody id="scoreBox">
		');
		if ($mudi == 'rev' && AppUserScore::Jud()){
			echo('
			<tr>
				<td align="right"></td>
				<td><div style="color:blue;cursor:pointer;" onclick=\'OT_OpenUserScore("&userID='. $UE_ID .'")\'>[积分管理]</div></td>
			</tr>
			');
		}
		if ($userSysArr['US_isScore1']==1){
			echo('
			<tr>
				<td align="right">'. $userSysArr['US_score1Name'] .'：</td>
				<td>
					<input type="hidden" id="scoreOld1" name="scoreOld1" value="'. $UE_score1 .'" />
					<input type="text" id="score1" name="score1" size="25" style="width:40px;" value="'. $UE_score1 .'" />
				</td>
			</tr>
			');
		}
		if ($userSysArr['US_isScore2']==1){
			echo('
			<tr>
				<td align="right">'. $userSysArr['US_score2Name'] .'：</td>
				<td>
					<input type="hidden" id="scoreOld2" name="scoreOld2" value="'. $UE_score2 .'" />
					<input type="text" id="score2" name="score2" size="25" style="width:40px;" value="'. $UE_score2 .'" />
				</td>
			</tr>
			');
		}
		if ($userSysArr['US_isScore3']==1){
			echo('
			<tr>
				<td align="right">'. $userSysArr['US_score3Name'] .'：</td>
				<td>
					<input type="hidden" id="scoreOld3" name="scoreOld3" value="'. $UE_score3 .'" />
					<input type="text" id="score3" name="score3" size="25" style="width:40px;" value="'. $UE_score3 .'" />
				</td>
			</tr>
			');
		}
		echo('
		</tbody>
		</table>
		');
	$skin->TableBottom();

	echo('
	<table style="height:16px;" cellpadding="0" cellspacing="0" summary=""><tr><td></td></tr></table>

	<center><input type="image" src="images/button_'. $mudi .'.gif" /></center>

	</form>
	');

}



// 查询
function manage(){
	global $DB,$MB,$skin,$mudi,$dataType,$dataTypeCN,$pageCount,$recordCount,$userSysArr;

	$dataMode		= OT::GetStr('dataMode');
	$dataModeStr	= OT::GetStr('dataModeStr');

	$refGroupID		= OT::GetInt('refGroupID');
	$refUserName	= OT::GetRegExpStr('refUserName','sql');
	$refRealName	= OT::GetRegExpStr('refRealName','sql');
	$refMail		= OT::GetRegExpStr('refMail','sql+mail');
	$refAuthMail	= OT::GetInt('refAuthMail');
	$refPhone		= OT::GetRegExpStr('refPhone','sql');
	$refAuthPhone	= OT::GetInt('refAuthPhone');
	$refqq			= OT::GetRegExpStr('refqq','sql');
	$refww			= OT::GetRegExpStr('refww','sql');
	$refRegType		= OT::GetRegExpStr('refRegType','sql');
	$refRegIP		= OT::GetRegExpStr('refRegIP','sql+.');
	$refState		= OT::GetInt('refState',-1);
	$refMailMode	= OT::GetRegExpStr('refMailMode','sql');
	$refPhoneMode	= OT::GetRegExpStr('refPhoneMode','sql');
	$refMark		= OT::GetInt('refMark');
	$refNoMail		= OT::GetInt('refNoMail');
	$refNoPhone		= OT::GetInt('refNoPhone');
	$refNoWeixin	= OT::GetInt('refNoWeixin');
	$refApiQq		= OT::GetInt('refApiQq');
	$refApiWeibo	= OT::GetInt('refApiWeibo');
	$refApiWeixin	= OT::GetInt('refApiWeixin');
	$refApiWxmp		= OT::GetInt('refApiWxmp');
	$refScore1_1	= OT::GetInt('refScore1_1');
	$refScore1_2	= OT::GetInt('refScore1_2');
	$refScore2_1	= OT::GetInt('refScore2_1');
	$refScore2_2	= OT::GetInt('refScore2_2');
	$refScore3_1	= OT::GetInt('refScore3_1');
	$refScore3_2	= OT::GetInt('refScore3_2');
	$refGroupDate1	= OT::GetStr('refGroupDate1');
		if (! strtotime($refGroupDate1)){ $refGroupDate1=''; }
	$refGroupDate2	= OT::GetStr('refGroupDate2');
		if (! strtotime($refGroupDate2)){ $refGroupDate2=''; }
	$refDate1		= OT::GetStr('refDate1');
		if (! strtotime($refDate1)){ $refDate1=''; }
	$refDate2		= OT::GetStr('refDate2');
		if (! strtotime($refDate2)){ $refDate2=''; }
	$refLoginDate1	= OT::GetStr('refLoginDate1');
		if (! strtotime($refLoginDate1)){ $refLoginDate1=''; }
	$refLoginDate2	= OT::GetStr('refLoginDate2');
		if (! strtotime($refLoginDate2)){ $refLoginDate2=''; }

	$orderName = OT::GetStr('orderName');
	$orderSort = OT::GetStr('orderSort');

	$SQLstr='select * from '. OT_dbPref .'users where 1=1';

	if ($refGroupID > 0){ $SQLstr .= ' and UE_groupID='. $refGroupID .''; }
	if ($refState >= 0){ $SQLstr .= ' and UE_state='. $refState .''; }
	if ($refUserName != ''){ $SQLstr .= " and UE_username like '%". $refUserName ."%'"; }
	if ($refRealName != ''){ $SQLstr .= " and UE_realName like '%". $refRealName ."%'"; }
	if ($refMail != ''){ $SQLstr .= " and UE_mail like '%". $refMail ."%'"; }
	if ($refAuthMail == 1){ $SQLstr .= " and UE_authStr like '%|邮箱|%'"; }
	if ($refPhone != ''){ $SQLstr .= " and UE_phone like '%". $refPhone ."%'"; }
	if ($refAuthPhone == 1){ $SQLstr .= " and UE_authStr like '%|手机|%'"; }
	if ($refqq != ''){ $SQLstr .= " and UE_qq like '%". $refqq ."%'"; }
	if ($refww != ''){ $SQLstr .= " and UE_ww like '%". $refww ."%'"; }
	if ($refRegType != ''){ $SQLstr .= " and UE_regType='". $refRegType ."'"; }
	if ($refRegIP != ''){ $SQLstr .= " and UE_regIP like '%". $refRegIP ."%'"; }
	if ($refGroupDate1 != ''){ $SQLstr .= ' and UE_groupTime>='. $DB->ForTime($refGroupDate1); }
	if ($refGroupDate2 != ''){ $SQLstr .= ' and UE_groupTime<='. $DB->ForTime(TimeDate::Add('d',1,$refGroupDate2)); }
	if ($refDate1 != ''){ $SQLstr .= ' and UE_time>='. $DB->ForTime($refDate1); }
	if ($refDate2 != ''){ $SQLstr .= ' and UE_time<='. $DB->ForTime(TimeDate::Add('d',1,$refDate2)); }
	if ($refMailMode == 'no'){
		$SQLstr .= ' and LENGTH(UE_mail)<=3';
	}elseif ($refMailMode == 'noToQq'){
		$SQLstr .= " and UE_mail not like '%@%' and LENGTH(UE_qq)>=5";
	}elseif ($refMailMode == 'yes'){
		$SQLstr .= ' and LENGTH(UE_mail)>=4';
	}elseif ($refMailMode == 'repeat'){
		$SQLstr .= ' and LENGTH(UE_mail)>=1 and UE_mail in (select UE_mail from '. OT_dbPref .'users group by UE_mail having count(UE_mail) >= 2)';
		$orderName = 'mail';
	}
	if ($refPhoneMode == 'no'){
		$SQLstr .= ' and LENGTH(UE_phone)!=11';
	}elseif ($refPhoneMode == 'yes'){
		$SQLstr .= ' and LENGTH(UE_phone)==11';
	}elseif ($refPhoneMode == 'repeat'){
		$SQLstr .= ' and LENGTH(UE_phone)>=1 and UE_phone in (select UE_phone from '. OT_dbPref .'users group by UE_phone having count(UE_phone) >= 2)';
		$orderName = 'phone';
	}
	if ($refMark == 1){ $SQLstr .= ' and UE_event like "%|mark|%"'; }
	if ($refNoMail == 1){ $SQLstr .= ' and UE_event like "%|noMail|%"'; }
	if ($refNoPhone == 1){ $SQLstr .= ' and UE_event like "%|noPhone|%"'; }
	if ($refNoWeixin == 1){ $SQLstr .= ' and UE_event like "%|noWeixin|%"'; }
	if ($refApiQq == 1){ $SQLstr .= ' and UE_apiStr like "%|qq::%"'; }
	if ($refApiWeibo == 1){ $SQLstr .= ' and UE_apiStr like "%|weibo::%"'; }
	if ($refApiWeixin == 1){ $SQLstr .= ' and UE_apiStr like "%|weixin::%"'; }
	if ($refApiWxmp == 1){ $SQLstr .= ' and UE_weixinID>=1'; }
	if ($refLoginDate1 != ''){ $SQLstr .= ' and UE_loginTime>='. $DB->ForTime($refLoginDate1); }
	if ($refLoginDate2 != ''){ $SQLstr .= ' and UE_loginTime<='. $DB->ForTime(TimeDate::Add('d',1,$refLoginDate2)); }
	if ($refScore1_1 > 0){ $SQLstr .= ' and UE_score1>='. $refScore1_1; }else{ $refScore1_1 = ''; }
	if ($refScore1_2 > 0){ $SQLstr .= ' and UE_score1<='. $refScore1_2; }else{ $refScore1_2 = ''; }
	if ($refScore2_1 > 0){ $SQLstr .= ' and UE_score2>='. $refScore2_1; }else{ $refScore2_1 = ''; }
	if ($refScore2_2 > 0){ $SQLstr .= ' and UE_score2<='. $refScore2_2; }else{ $refScore2_2 = ''; }
	if ($refScore3_1 > 0){ $SQLstr .= ' and UE_score3>='. $refScore3_1; }else{ $refScore3_1 = ''; }
	if ($refScore3_2 > 0){ $SQLstr .= ' and UE_score3<='. $refScore3_2; }else{ $refScore3_2 = ''; }

	if (strpos('|username|realname|groupID|mail|phone|weixin|qq|ww|state|regIP|loginTime|score1|score2|score3|','|'. $orderName .'|') === false){ $orderName='time'; }
	if ($orderSort != 'ASC'){ $orderSort='DESC'; }

	$groupOptionStr = '';

	$skin->TableTop('share_refer.gif','',$dataTypeCN .'查询');
		echo('
		<form id="refForm" name="refForm" method="get" action="">
		<input type="hidden" name="mudi" value="'. $mudi .'" />
		<input type="hidden" name="dataType" value="'. $dataType .'" />
		<input type="hidden" name="dataTypeCN" value="'. $dataTypeCN .'" />
		<input type="hidden" name="dataMode" value="'. $dataMode .'" />
		<input type="hidden" name="dataModeStr" value="'. $dataModeStr .'" />
		<table style="width:99%;" border="0" cellSpacing="0" cellPadding="0" summary="" class="padd3td">
		<tr>
			<td style="width:30%;">
				&ensp;&ensp;会员组：<select id="refGroupID" name="refGroupID" style="width:220px;">
				<option value="">&ensp;</option>
				');

				$gexe=$DB->query('select UG_ID,UG_theme from '. OT_dbPref .'userGroup where UG_state=1 order by UG_rank ASC');
				while ($row = $gexe->fetch()){
					echo('<option value="'. $row['UG_ID'] .'" '. Is::Selected($refGroupID,$row['UG_ID']) .'>'. $row['UG_theme'] .'</option>');
					$groupOptionStr .= '<option value="'. $row['UG_ID'] .'">'. $row['UG_theme'] .'</option>';
				}
				unset($gexe);

				echo('
				</select>&ensp;
			</td>
			<td style="width:17%;">
				用户名：<input type="text" name="refUserName" size="18" style="width:105px;" value="'. $refUserName .'" />
			</td>
			<td style="width:25%;">
				&ensp;&ensp;&ensp;&ensp;邮箱：<input type="text" name="refMail" size="18" style="width:105px;" value="'. $refMail .'" />
				<label><input type="checkbox" name="refAuthMail" value="1" '. Is::Checked($refAuthMail,1) .' />验证</label>
			</td>
			<td style="width:28%;">
				会员组到期：<input type="text" name="refGroupDate1" size="10" value="'. $refGroupDate1 .'" onfocus="WdatePicker()" />&ensp;
				至&ensp;<input type="text" name="refGroupDate2" size="10" value="'. $refGroupDate2 .'" onfocus="WdatePicker()" />
			</td>
		</tr>
		<tr>
			<td>
				&ensp;&ensp;注册IP：<input type="text" name="refRegIP" size="18" style="width:110px;" value="'. $refRegIP .'" />
				&ensp;&ensp;状态：<select id="refState" name="refState">
					<option value=""></option>
					<option value="1" '. Is::Selected($refState,1) .'>已审</option>
					<option value="0" '. Is::Selected($refState,0) .'>未审</option>
					</select>
			</td>
			<td>
				&ensp;&ensp;昵称：<input type="text" name="refRealName" size="18" style="width:105px;" value="'. $refRealName .'" />
			</td>
			<td>
				&ensp;&ensp;&ensp;&ensp;手机：<input type="text" name="refPhone" size="18" style="width:105px;" value="'. $refPhone .'" />
				<label><input type="checkbox" name="refAuthPhone" value="1" '. Is::Checked($refAuthPhone,1) .' />验证</label>
			</td>
			<td>
				&ensp;&ensp;注册日期：<input type="text" name="refDate1" size="10" value="'. $refDate1 .'" onfocus="WdatePicker()" />&ensp;
				至&ensp;<input type="text" name="refDate2" size="10" value="'. $refDate2 .'" onfocus="WdatePicker()" />
			</td>
		</tr>
		<tr>
			<td>
				&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;QQ：<input type="text" name="refqq" size="18" style="width:79px;" value="'. $refqq .'" />
				&ensp;&ensp;旺旺：<input type="text" name="refww" size="18" style="width:79px;" value="'. $refww .'" />
			</td>
			<td>
				&ensp;&ensp;类型：<select id="refRegType" name="refRegType" style="width:110px;">
					<option value=""></option>
					<option value="web" '. Is::Selected($refRegType,'web') .'>网站</option>
					<option value="back" '. Is::Selected($refRegType,'back') .'>后台</option>
					<option value="qq" '. Is::Selected($refRegType,'qq') .'>QQ</option>
					<option value="weibo" '. Is::Selected($refRegType,'weibo') .'>新浪微博</option>
					<option value="weixin" '. Is::Selected($refRegType,'weixin') .'>微信</option>
					<!-- <option value="wxmp" '. Is::Selected($refRegType,'wxmp') .'>微信公众号</option>
					<option value="taobao" '. Is::Selected($refRegType,'taobao') .'>淘宝</option>
					<option value="alipay" '. Is::Selected($refRegType,'alipay') .'>支付宝</option> -->
					</select>
			</td>
			<td>
				快捷登录：<label><input type="checkbox" name="refApiQq" value="1" '. Is::Checked($refApiQq,1) .' />QQ</label>&ensp;
				<label><input type="checkbox" name="refApiWeibo" value="1" '. Is::Checked($refApiWeibo,1) .' />微博</label>&ensp;
				<label><input type="checkbox" name="refApiWeixin" value="1" '. Is::Checked($refApiWeixin,1) .' />微信</label>&ensp;
			</td>
			<td>
				&ensp;&ensp;最后登录：<input type="text" name="refLoginDate1" size="10" value="'. $refLoginDate1 .'" onfocus="WdatePicker()" />&ensp;
				至&ensp;<input type="text" name="refLoginDate2" size="10" value="'. $refLoginDate2 .'" onfocus="WdatePicker()" />
			</td>
		</tr>
		<tr>
			<td align="left">
				&ensp;&ensp;&ensp;&ensp;邮箱：<select id="refMailMode" name="refMailMode" style="width:auto;">
					<option value=""></option>
					<option value="no" '. Is::Selected($refMailMode,'no') .'>没有</option>
					<option value="noToQq" '. Is::Selected($refMailMode,'noToQq') .'>没有但有QQ</option>
					<option value="yes" '. Is::Selected($refMailMode,'yes') .'>有</option>
					<option value="repeat" '. Is::Selected($refMailMode,'repeat') .'>重复</option>
					</select>
				&ensp;&ensp;手机：<select id="refPhoneMode" name="refPhoneMode" style="width:auto;">
					<option value=""></option>
					<option value="no" '. Is::Selected($refPhoneMode,'no') .'>没有</option>
					<option value="yes" '. Is::Selected($refPhoneMode,'yes') .'>有</option>
					<option value="repeat" '. Is::Selected($refPhoneMode,'repeat') .'>重复</option>
					</select>
			</td>
			<td align="left" colspan="3">
				&ensp;&ensp;'. $userSysArr['US_score1Name'] .'：<input type="text" name="refScore1_1" size="18" style="width:35px;" value="'. $refScore1_1 .'" />
				- <input type="text" name="refScore1_2" size="18" style="width:35px;" value="'. $refScore1_2 .'" />
				');
				if ($userSysArr['US_isScore2']==1){
					echo('&ensp;&ensp;&ensp;'. $userSysArr['US_score2Name'] .'：<input type="text" name="refScore2_1" size="18" style="width:35px;" value="'. $refScore2_1 .'" />
					- <input type="text" name="refScore2_2" size="18" style="width:35px;" value="'. $refScore2_2 .'" />');
				}
				if ($userSysArr['US_isScore3']==1){
					echo('&ensp;&ensp;&ensp;'. $userSysArr['US_score3Name'] .'：<input type="text" name="refScore3_1" size="18" style="width:35px;" value="'. $refScore3_1 .'" />
					- <input type="text" name="refScore3_2" size="18" style="width:35px;" value="'. $refScore3_2 .'" />');
				}
			echo('
			</td>
		</tr>
		<tr>
			<td align="center" style="padding-top:20px" colspan="4">
				<input type="image" src="images/button_refer.gif" />
				&ensp;&ensp;&ensp;&ensp;
				<img src="images/button_reset.gif" onclick=\'document.location.href="?mudi='. $mudi .'&amp;dataMode='. $dataMode .'&amp;dataModeStr='. $dataModeStr .'&amp;dataType='. $dataType .'&amp;dataTypeCN='. urlencode($dataTypeCN) .'"\' style="cursor:pointer" alt="" />
			</td>
		</tr>
		</table>
		</form>
		');
	$skin->TableBottom();

	echo('
	<br />

	<div style="padding:5px;float:left;">
		<input type="button" value="在线会员列表" onclick=\'document.location.href="?mudi=online&dataTypeCN='. urlencode('在线会员') .'";\' />&ensp;
		'. AppRecom::UsersBox1('&dataTypeCN='. urlencode('推荐注册会员')) .'
	</div>

	<form id="listForm" name="listForm" method="post" action="users_deal.php?mudi=moreDel" onsubmit="return CheckListForm()">
	<script language="javascript" type="text/javascript">document.write(\'<input type="hidden" name="backURL" value="\'+ document.location.href +\'">\')</script>
	<input type="hidden" name="dataType" value="'. $dataType .'" />
	<input type="hidden" name="dataTypeCN" value="'. $dataTypeCN .'" />
	<input type="hidden" name="dataMode" value="'. $dataMode .'" />
	<input type="hidden" name="dataModeStr" value="'. $dataModeStr .'" />
	');

	if (AppMoneyPay::Jud()){
		$otherFieldTitle = ''. $skin->ShowArrow('余额','money',$orderName,$orderSort) .'/'. $skin->ShowArrow('消费','payMoney',$orderName,$orderSort) .'';
		$moneyStyle = '';
		$scoreStyle = 'display:none;';
	}else{
		$otherFieldTitle = '其他';
		$moneyStyle = 'display:none;';
		$scoreStyle = '';
	}
	$skin->TableTop2('share_list.gif','',$dataTypeCN .'列表');
	$skin->TableItemTitle('3%,5%,6%,12%,13%,11%,11%,8%,8%,8%,5%,11%','<input type="checkbox" id="selAll" name="selAll" onclick="CheckBoxAll()" />,序号,'. $skin->ShowArrow('类型','regType',$orderName,$orderSort) .','. $skin->ShowArrow('用户名','username',$orderName,$orderSort) .'/'. $skin->ShowArrow('会员组','groupID',$orderName,$orderSort) .','. $skin->ShowArrow('昵称','realname',$orderName,$orderSort) .'/'. $skin->ShowArrow('邮箱','mail',$orderName,$orderSort) .'/'. $skin->ShowArrow('手机','phone',$orderName,$orderSort) .','. $skin->ShowArrow('QQ','qq',$orderName,$orderSort) .'/'. $skin->ShowArrow('旺旺','ww',$orderName,$orderSort) .'/'. $skin->ShowArrow('微信','weixin',$orderName,$orderSort) .','. $skin->ShowArrow('积分1','score1',$orderName,$orderSort) .'/'. $skin->ShowArrow('2','score2',$orderName,$orderSort) .'/'. $skin->ShowArrow('3','score3',$orderName,$orderSort) .','. $otherFieldTitle .','. $skin->ShowArrow('最后登录','loginTime',$orderName,$orderSort) .','. $skin->ShowArrow('注册日期','time',$orderName,$orderSort) .','. $skin->ShowArrow('状态','state',$orderName,$orderSort) .',详细&ensp;修改&ensp;删除');

	$pageSize	= $MB->mMbRow['MB_itemNum'];		//每页条数
	$page		= OT::GetInt('page');
	$showRow=$DB->GetLimit($SQLstr .' order by UE_'. $orderName .' '. $orderSort,$pageSize,$page);
	if (! $showRow){
		$skin->TableNoData();
	}else{
		$recordCount=$DB->GetRowCount();
		$pageCount=ceil($recordCount/$pageSize);
		if ($page < 1 || $page > $pageCount){$page=1;}

		$userGroupArr = Cache::PhpFile('userGroup');
		$todayTime = TimeDate::Get();

		echo('
		<tbody class="tabBody padd3td">
		');
		$number=1+($page-1)*$pageSize;
		$rowCount = count($showRow);
		for ($i=0; $i<$rowCount; $i++){
			if ($i % 2 == 1){ $bgcolor='class="tabColorTr"'; }else{ $bgcolor=''; }
			$recomStr = '';
			if (AppRecom::Jud()){
				$recomStr = AppRecom::UsersTabStr($showRow[$i]['UE_recomId'], $showRow[$i]['UE_isRecomScore']) . $showRow[$i]['UE_recomUser'];
			}

			$groupTimeStr = '';
			if ($showRow[$i]['UE_isGroupTime']==1 && strtotime($showRow[$i]['UE_groupTime'])){
				$groupTimeStr = '<span style="color:red;" title="到期时间：'. $showRow[$i]['UE_groupTime'] .'">['. TimeDate::DiffDayCN($showRow[$i]['UE_groupTime'],'') .']</span>';
			}

			$shimingStr = $mailStr = $phoneStr = '';
			if (strpos($showRow[$i]['UE_authStr'],'|实名认证|') !== false){
				$shimingStr = '<span title="已实名认证" style="color:blue;"><img src="images/img_shiming.png" style="margin-left:2px;" /></span>';
			}
			if (strpos($showRow[$i]['UE_authStr'],'|邮箱|') !== false){
				$mailStr = '<span title="邮箱已验证" style="color:blue;"><img src="images/img_yanzheng.png" style="margin-left:2px;" /></span>';
			}
			/* elseif ($userSysArr['US_isAuthMail'] == 1){
				$mailStr = '<span title="邮箱未验证" style="color:blue;">[未验]</span>';
			} */
			if (strpos($showRow[$i]['UE_authStr'],'|手机|') !== false){
				$phoneStr = '<span title="手机已验证" style="color:blue;"><img src="images/img_yanzheng.png" style="margin-left:2px;" /></span>';
			}
			/* elseif ($userSysArr['US_isAuthPhone'] == 1){
				$phoneStr = '<span title="手机未验证" style="color:blue;">[未验]</span>';
			} */
			$apiStr = '';
			if (strpos($showRow[$i]['UE_apiStr'],'|qq::') !== false){ $apiStr .= '<img src="images/api_qq.png" alt="已绑定QQ快捷登录" title="已绑定QQ快捷登录" />'; }
			if (strpos($showRow[$i]['UE_apiStr'],'|weibo::') !== false){ $apiStr .= '<img src="images/api_weibo.png" alt="已绑定新浪微博快捷登录" title="已绑定新浪微博快捷登录" />'; }
			if (strpos($showRow[$i]['UE_apiStr'],'|weixin::') !== false){ $apiStr .= '<img src="images/api_weixin.png" alt="已绑定微信快捷登录" title="已绑定微信快捷登录" />'; }
			if ($showRow[$i]['UE_weixinID'] >= 1){ $apiStr .= '<img src="images/api_wxmp.png" alt="已绑定微信公众号" title="已绑定微信公众号" />'; }

			echo('
			<tr '. $bgcolor .' id="data'. $showRow[$i]['UE_ID'] .'">
				<td align="center"><input type="checkbox" name="selDataID[]" value="'. $showRow[$i]['UE_ID'] .'" /></td>
				<td align="center">'. $number .'</td>
				<td align="center">'. Users::regTypeCN($showRow[$i]['UE_regType']) .'</td>
				<td align="center" style="word-break:break-all;">
					'. $showRow[$i]['UE_username'] . $shimingStr . $apiStr .'
					<div style="margin-top:5px;color:blue;">'. UserGroup::CurrName($showRow[$i]['UE_groupID'],$userGroupArr) . $groupTimeStr .'</div>
				</td>
				<td align="center" style="word-break:break-all;">
					'. $showRow[$i]['UE_realname'] .'
					<div>'. $showRow[$i]['UE_mail'] . $mailStr .'</div>
					<div>'. $showRow[$i]['UE_phone'] . $phoneStr .'</div>
				</td>
				<td align="center" style="word-break:break-all;">
					'. AdmArea::UserQQ($showRow[$i]['UE_qq']) .'
					'. AdmArea::UserWw($showRow[$i]['UE_ww']) .'
					<div>'. $showRow[$i]['UE_weixin'] .'</div>
					<div style="padding-top:5px;">'. $recomStr .'</div>
				</td>
				<td align="left">'. Area::UserScoreList($showRow[$i]['UE_score1'],$showRow[$i]['UE_score2'],$showRow[$i]['UE_score3']) .'</td>
				<td align="center">
					<div style="float:right;text-align:right;'. $moneyStyle .'">
						'. $showRow[$i]['UE_money'] .'
						<div style="color:blue;">'. $showRow[$i]['UE_payMoney'] .'</div>
					</div>
					<div class="clear"></div>
					<span style="'. $moneyStyle .'">'. AppMoneyPay::UsersItem($showRow[$i]["UE_ID"],'#c9c9c9') .'</span>
					<span style="'. $scoreStyle .'">'. AppUserScore::UsersItem($showRow[$i]["UE_ID"]) .'</span>
				</td>
				<td align="center" title="'. $showRow[$i]['UE_loginTime'] .'">
					'. TimeDate::Get('date',$showRow[$i]['UE_loginTime']) .'
					<div class="font2_2">('. TimeDate::Diff('d',$showRow[$i]['UE_loginTime'],$todayTime) .'天前)</div>
					<span style="'. $moneyStyle .'">'. AppUserScore::UsersItem($showRow[$i]["UE_ID"],'#c9c9c9') .'</span>
				</td>
				<td align="center" style="color:'. (TimeDate::Diff('d',$showRow[$i]['UE_time'],$todayTime)==0?'red':'') .';">
					'. $showRow[$i]['UE_time'] .'
				</td>
				<td align="center">'. Adm::SwitchBtn('users',$showRow[$i]['UE_ID'],$showRow[$i]['UE_state'],'state','stateAudit') .'</td>
				<td align="center">
					<img src="images/img_det.gif" style="cursor:pointer" onclick=\'var a=window.open("?mudi=show&nohrefStr=close&dataMode='. $dataMode .'&dataModeStr='. $dataModeStr .'&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&dataID='. $showRow[$i]['UE_ID'] .'")\' alt="" />&ensp;&ensp;
					<img src="images/img_rev.gif" style="cursor:pointer" onclick=\'document.location.href="?mudi=rev&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&dataMode='. $dataMode .'&dataID='. $showRow[$i]['UE_ID'] .'&backURL="+ encodeURIComponent(document.location.href)\' alt="" />&ensp;
					<img src="images/img_del.gif" style="cursor:pointer" onclick=\'if(confirm("你确定要删除？")==true){DataDeal.location.href="users_deal.php?mudi=del&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&theme='. urlencode($showRow[$i]['UE_username']) .'&dataID='. $showRow[$i]['UE_ID'] .'"}\' alt="" />
				</td>
			</tr>
			');
			$number ++;
		}
	echo('
	</tbody>
	<tr class="tabColorB padd5td">
		<td align="left" colspan="20">
			<input type="button" value="全选" onclick="AllSelBox()" class="form_button1" />
			<input type="button" value="反选" onclick="RevSelBox()" class="form_button1" />
			&ensp;
			<input type="submit" value="批量删除" /> <!-- class="form_button2" -->
			&ensp;&ensp;
			<select id="moreSetTo" name="moreSetTo" onchange="MoreSetTo()" style="width:150px;">
				<option value="">批量设置成...</option>
				<option value="shiming1" style="color:#0066CC;">已实名认证√</option>
				<option value="shiming0" style="color:#707070;">未实名认证ㄨ</option>
				<option value="mailAudit1" style="color:#0066CC;">邮箱已验证√</option>
				<option value="mailAudit0" style="color:#707070;">邮箱未验证ㄨ</option>
				<option value="phoneAudit1" style="color:#0066CC;">手机已验证√</option>
				<option value="phoneAudit0" style="color:#707070;">手机未验证ㄨ</option>
				<option value="groupTime1" style="color:#0066CC;">开启会员组到期时间√</option>
				<option value="groupTime0" style="color:#707070;">关闭会员组到期时间ㄨ</option>
				<option value="state1" style="color:#0066CC;">状态：已审核√</option>
				<option value="state0" style="color:red;">状态：未审核ㄨ</option>
			</select>
			<input type="hidden" id="moreSetToCN" name="moreSetToCN" value="" />
			&ensp;&ensp;&ensp;
			<select id="groupMoveTo" name="groupMoveTo" onchange="GroupMoveTo()" style="width:150px;">
				<option value="">会员组批量移动到...</option>
				'. $groupOptionStr .'
			</select>
			<input type="hidden" id="groupMoveToCN" name="groupMoveToCN" value="" />
		</td>
	</tr>
	');
	}
	unset($showRow);

	echo('</form>');

	$skin->TableBottom2($pageCount, $pageSize, $recordCount);
}



function online(){
	global $DB,$MB,$skin,$mudi,$dataType,$dataTypeCN,$pageCount,$recordCount,$userSysArr;

	$dataMode		= OT::GetStr('dataMode');
	$dataModeStr	= OT::GetStr('dataModeStr');

	$refGroupID		= OT::GetInt('refGroupID');
	$refUserName	= OT::GetRegExpStr('refUserName','sql');
	$refMail		= OT::GetRegExpStr('refMail','sql+mail');
	$refPhone		= OT::GetRegExpStr('refPhone','sql');
	$refRecomUser	= OT::GetRegExpStr('refRecomUser','sql');
	$refqq			= OT::GetRegExpStr('refqq','sql');
	$refRegIP		= OT::GetRegExpStr('refRegIP','sql+.');
	$refState		= OT::GetInt('refState',-1);
	$refDate1		= OT::GetStr('refDate1');
		if (! strtotime($refDate1)){ $refDate1=''; }
	$refDate2		= OT::GetStr('refDate2');
		if (! strtotime($refDate2)){ $refDate2=''; }

	$onTime = TimeDate::Add('min',-15);
	$SQLstr='select * from '. OT_dbPref .'users as UE RIGHT JOIN '. OT_dbPref .'userOnline as UO ON UE.UE_ID=UO.UO_userID where UO.UO_time>='. $DB->ForTime($onTime);

	if ($refGroupID > 0){ $SQLstr .= ' and UE_groupID='. $refGroupID .''; }
	if ($refState >= 0){ $SQLstr .= ' and UE_state='. $refState .''; }
	if ($refUserName != ''){ $SQLstr .= " and UE_userName like '%". $refUserName ."%'"; }
	if ($refMail != ''){ $SQLstr .= " and UE_mail like '%". $refMail ."%'"; }
	if ($refPhone != ''){ $SQLstr .= " and UE_phone like '%". $refPhone ."%'"; }
	if ($refqq != ''){ $SQLstr .= " and UE_qq like '%". $refqq ."%'"; }
	if ($refRegIP != ''){ $SQLstr .= " and UE_regIP like '%". $refRegIP ."%'"; }
	if ($refDate1 != ''){ $SQLstr .= ' and UE_time>='. $DB->ForTime($refDate1); }
	if ($refDate2 != ''){ $SQLstr .= ' and UE_time<='. $DB->ForTime(TimeDate::Add('d',1,$refDate2)); }

	$orderName = OT::GetStr('orderName');
		if (strpos('|username|groupID|mail|phone|weixin|qq|ww|state|regIP|loginTime|recomType|recomId|recomUser|recomScore1|','|'. $orderName .'|') === false){ $orderName='time'; }
	$orderSort = OT::GetStr('orderSort');
		if ($orderSort != 'ASC'){ $orderSort='DESC'; }


	$skin->TableTop('share_refer.gif','',$dataTypeCN .'查询');
		echo('
		<form id="refForm" name="refForm" method="get" action="">
		<input type="hidden" name="mudi" value="'. $mudi .'" />
		<input type="hidden" name="dataType" value="'. $dataType .'" />
		<input type="hidden" name="dataTypeCN" value="'. $dataTypeCN .'" />
		<input type="hidden" name="dataMode" value="'. $dataMode .'" />
		<input type="hidden" name="dataModeStr" value="'. $dataModeStr .'" />
		<table style="width:99%;" border="0" cellSpacing="0" cellPadding="0" summary="" class="padd5td">
		<tr>
			<td style="width:22%;">
				&ensp;&ensp;会员组：<select id="refGroupID" name="refGroupID" style="width:139px;">
				<option value="">&ensp;</option>
				');

				$gexe=$DB->query('select UG_ID,UG_theme from '. OT_dbPref .'userGroup where UG_state=1 order by UG_rank ASC');
				while ($row = $gexe->fetch()){
					echo('<option value="'. $row['UG_ID'] .'" '. Is::Selected($refGroupID,$row['UG_ID']) .'>'. $row['UG_theme'] .'</option>');
				}
				unset($gexe);

				echo('
				</select>&ensp;
			</td>
			<td style="width:22%;">
				用户名：<input type="text" name="refUserName" size="18" style="width:135px;" value="'. $refUserName .'" />
			</td>
			<td style="width:22%;">
				&ensp;&ensp;&ensp;&ensp;邮箱：<input type="text" name="refMail" size="18" style="width:135px;" value="'. $refMail .'" />
			</td>
			<td style="width:34%;">
				审核状态：<select name="refState">
					<option value="">&ensp;</option>
					<option value="1" '. Is::Selected($refState,1) .'>已审核</option>
					<option value="0" '. Is::Selected($refState,0) .'>未审核</option>
					</select>
				<!-- &ensp;&ensp;&ensp;&ensp;状态：<select id="refState" name="refState">
					<option value=""></option>
					<option value="1" '. Is::Selected($refState,1) .'>已审</option>
					<option value="0" '. Is::Selected($refState,0) .'>未审</option>
					</select> -->
			</td>
		</tr>
		<tr>
			<td>
				&ensp;&ensp;推荐人：<input type="text" name="refRecomUser" size="18" style="width:135px;" value="'. $refRecomUser .'" />
			</td>
			<td>
				&ensp;&ensp;&ensp;&ensp;QQ：<input type="text" name="refqq" size="18" style="width:135px;" value="'. $refqq .'" />
			</td>
			<td>
				&ensp;&ensp;&ensp;&ensp;手机：<input type="text" name="refPhone" size="18" style="width:135px;" value="'. $refPhone .'" />
			</td>
			<td>
				注册日期：<input type="text" name="refDate1" size="10" value="'. $refDate1 .'" onfocus="WdatePicker()" />&ensp;
				至&ensp;<input type="text" name="refDate2" size="10" value="'. $refDate2 .'" onfocus="WdatePicker()" />
			</td>
		</tr>
		<tr>
			<td align="center" style="padding-top:20px" colspan="3">
				<input type="image" src="images/button_refer.gif" />
				&ensp;&ensp;&ensp;&ensp;
				<img src="images/button_reset.gif" onclick=\'document.location.href="?mudi='. $mudi .'&amp;dataMode='. $dataMode .'&amp;dataModeStr='. $dataModeStr .'&amp;dataType='. $dataType .'&amp;dataTypeCN='. urlencode($dataTypeCN) .'"\' style="cursor:pointer" alt="" />
			</td>
		</tr>
		</table>
		</form>
		');
	$skin->TableBottom();

	echo('
	<br />

	<div style="padding:5px;"><input type="button" value="返回会员管理" onclick=\'document.location.href="?mudi=manage&amp;dataMode='. $dataMode .'&amp;dataModeStr='. $dataModeStr .'&amp;dataType='. $dataType .'&amp;dataTypeCN='. urlencode($dataTypeCN) .'";\' /></div>
	<form id="listForm" name="listForm" method="post" onsubmit="return CheckListForm()">
	<script language="javascript" type="text/javascript">document.write(\'<input type="hidden" name="backURL" value="\'+ document.location.href +\'">\')</script>
	<input type="hidden" name="dataType" value="'. $dataType .'" />
	<input type="hidden" name="dataTypeCN" value="'. $dataTypeCN .'" />
	<input type="hidden" name="dataMode" value="'. $dataMode .'" />
	<input type="hidden" name="dataModeStr" value="'. $dataModeStr .'" />
	');

	$skin->TableTop2('share_list.gif','',$dataTypeCN .'列表');
	$skin->TableItemTitle('3%,5%,6%,14%,14%,12%,13%,16%,8%,5%,5%','<input type="checkbox" id="selAll" name="selAll" onclick="CheckBoxAll()" />,序号,'. $skin->ShowArrow('注册类型','regType',$orderName,$orderSort) .','. $skin->ShowArrow('用户名','username',$orderName,$orderSort) .'/'. $skin->ShowArrow('会员组','groupID',$orderName,$orderSort) .','. $skin->ShowArrow('邮箱','mail',$orderName,$orderSort) .','. $skin->ShowArrow('QQ','qq',$orderName,$orderSort) .'/'. $skin->ShowArrow('旺旺','ww',$orderName,$orderSort) .','. $skin->ShowArrow('最后在线时间','UO_time',$orderName,$orderSort) .','. $skin->ShowArrow('在线IP','UO_ip',$orderName,$orderSort) .','. $skin->ShowArrow('注册日期','time',$orderName,$orderSort) .','. $skin->ShowArrow('状态','state',$orderName,$orderSort) .',详细');

	$pageSize	= $MB->mMbRow['MB_itemNum'];		//每页条数
	$page		= OT::GetInt('page');
	$showRow=$DB->GetLimit($SQLstr .' order by UE_'. $orderName .' '. $orderSort,$pageSize,$page);
	if (! $showRow){
		$skin->TableNoData();
	}else{
		$recordCount=$DB->GetRowCount();
		$pageCount=ceil($recordCount/$pageSize);
		if ($page < 1 || $page > $pageCount){$page=1;}

		$userGroupArr = Cache::PhpFile('userGroup');
		$todayTime = TimeDate::Get();

		echo('
		<tbody class="tabBody padd3td">
		');
		$number=1+($page-1)*$pageSize;
		$rowCount = count($showRow);
		for ($i=0; $i<$rowCount; $i++){
			if ($i % 2 == 1){ $bgcolor='class="tabColorTr"'; }else{ $bgcolor=''; }
			$recomStr = '';
			if (AppRecom::Jud()){
				$recomStr = AppRecom::UsersTabStr($showRow[$i]['UE_recomId'], $showRow[$i]['UE_isRecomScore']) . $showRow[$i]['UE_recomUser'];
			}

			$ipInfoArr = OT::GetIpInfoArr($showRow[$i]['UO_ip']);

			echo('
			<tr '. $bgcolor .' id="data'. $showRow[$i]['UE_ID'] .'">
				<td align="center"><input type="checkbox" name="selDataID[]" value="'. $showRow[$i]['UE_ID'] .'" /></td>
				<td align="center">'. $number .'</td>
				<td align="center">'. Users::regTypeCN($showRow[$i]['UE_regType']) .'</td>
				<td align="center" style="word-break:break-all;">'. $showRow[$i]['UE_username'] .'<div style="margin-top:5px;color:blue;">'. UserGroup::CurrName($showRow[$i]['UE_groupID'],$userGroupArr) .'</div></td>
				<td align="center" style="word-break:break-all;">'. $showRow[$i]['UE_mail'] .'</td>
				<td align="center" style="word-break:break-all;">
					'. AdmArea::UserQQ($showRow[$i]['UE_qq']) .'
					'. AdmArea::UserWw($showRow[$i]['UE_ww']) .'
				</td>
				<td align="center">'. $showRow[$i]['UO_time'] .'<div style="color:red;">（'. TimeDate::Diff('min',$showRow[$i]['UO_time']) .'分钟前）</div></td>
				<td align="center">'. $showRow[$i]['UO_ip'] .'<div style="color:blue;">（'. $ipInfoArr['address'] .'）</div></td>
				<td align="center">'. $showRow[$i]['UE_time'] .'</td>
				<td align="center">'. Adm::SwitchBtn('users',$showRow[$i]['UE_ID'],$showRow[$i]['UE_state'],'state','stateAudit') .'</td>
				<td align="center">
					<img src="images/img_det.gif" style="cursor:pointer" onclick=\'var a=window.open("?mudi=show&nohrefStr=close&dataMode='. $dataMode .'&dataModeStr='. $dataModeStr .'&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&dataID='. $showRow[$i]['UE_ID'] .'")\' alt="" />
				</td>
			</tr>
			');
			$number ++;
		}
	echo('
	</tbody>
	<tr class="tabColorB padd5td">
		<td align="left" colspan="20">
			<input type="button" value="全选" onclick="AllSelBox()" class="form_button1" />
			<input type="button" value="反选" onclick="RevSelBox()" class="form_button1" />
			&ensp;
			<input type="submit" value="批量删除" /> <!-- class="form_button2" -->
		</td>
	</tr>
	');
	}
	unset($showRow);

	echo('</form>');

	$skin->TableBottom2($pageCount, $pageSize, $recordCount);
}



function show(){
	global $DB,$skin,$dataType,$dataTypeCN,$userSysArr;

	$dataID		= OT::GetInt('dataID');
	$dataMode	= OT::GetStr('dataMode');
	$dataModeStr= OT::GetStr('dataModeStr');

	$showexe=$DB->query('select * from '. OT_dbPref .'users where UE_ID='. $dataID);
	if (! $row = $showexe->fetch()){
		JS::AlertCloseEnd('指定ID错误！');
	}else{
		echo('
		<script language="javascript" type="text/javascript">document.title="会员详细信息";</script>
		<table style="width:700px;" align="center" cellpadding="0" cellspacing="0" border="0" summary=""><tr><td>
		');
		$skin->TableTop('share_list.gif','','会员详细信息');

		$userGroupArr = Cache::PhpFile('userGroup');

		$groupTimeStr = $shimingStr = $authMailStr = $authPhoneStr = '';
		if ($row['UE_isGroupTime']==1 && strtotime($row['UE_groupTime'])){
			$groupTimeStr = '<span style="color:red;" title="到期时间：'. $row['UE_groupTime'] .'">['. TimeDate::DiffDayCN($row['UE_groupTime'],'') .']</span>';
		}

		if (strpos($row['UE_authStr'],'|实名认证|') !== false){
			$shimingStr = '&ensp;<span title="已实名认证" style="color:blue;"><img src="images/img_shiming.png" /></span>';
		}

		if ($userSysArr['US_isAuthMail'] == 1 && AppMail::Jud()){
			if (strpos($row['UE_authStr'],'|邮箱|') === false){
				$authMailStr = '&ensp;<span style="font-size:12px;color:red;">[未验证]</span>';
			}else{
				$authMailStr = '&ensp;<span style="font-size:12px;color:green;">[已验证]</span>';
			}
		}

		if ($userSysArr['US_isAuthPhone'] == 1 && AppPhone::Jud()){
			if (strpos($row['UE_authStr'],'|手机|') === false){
				$authPhoneStr = '&ensp;<span style="font-size:12px;color:red;">[未验证]</span>';
			}else{
				$authPhoneStr = '&ensp;<span style="font-size:12px;color:green;">[已验证]</span>';
			}
		}

		$ipInfoArr = OT::GetIpInfoArr($row['UE_regIP']);

		echo('
		<div style="float:left;width:340px;">
			<table style="width:100%;" align="center" cellpadding="0" cellspacing="0" border="0" summary="" class="padd3">
			<tr>
				<td align="right" width="100">注册时间：</td>
				<td>'. $row['UE_time'] .'</td>
			</tr>
			<tr>
				<td align="right">注册类型：</td>
				<td>'. Users::regTypeCN($row['UE_regType']) .'</td>
			</tr>
			<tr>
				<td align="right">注册IP：</td>
				<td>'. $row['UE_regIP'] .'&ensp;&ensp;（'. $ipInfoArr['address'] .'）</td>
			</tr>
			<tr>
				<td align="right">最后登录时间：</td>
				<td>'. $row['UE_loginTime'] .'</td>
			</tr>
			<tr>
				<td align="right">最后登录IP：</td>
				<td>'. $row['UE_loginIP'] .'</td>
			</tr>
			<tr>
				<td align="right">登录次数：</td>
				<td>'. $row['UE_loginNum'] .'</td>
			</tr>
			<tr>
				<td align="right">密码问题：</td>
				<td>'. $row['UE_question'] .'</td>
			</tr>
			<tr>
				<td align="right">用户组：</td>
				<td>'. UserGroup::CurrName($row['UE_groupID'],$userGroupArr) . $groupTimeStr .'</td>
			</tr>
			<tr>
				<td align="right">用户ID：</td>
				<td>'. $row['UE_ID'] .'</td>
			</tr>
			<tr>
				<td align="right">用户名：</td>
				<td>'. $row['UE_username'] . $shimingStr .'</td>
			</tr>
			<tr>
				<td align="right">昵称：</td>
				<td>'. $row['UE_realname'] .'</td>
			</tr>
			<tr>
				<td align="right">邮箱：</td>
				<td>'. $row['UE_mail'] . $authMailStr .'</td>
			</tr>
			<tr>
				<td align="right">手机：</td>
				<td>'. $row['UE_phone'] . $authPhoneStr .'</td>
			</tr>
			<!-- <tr>
				<td align="right">传真：</td>
				<td>'. $row['UE_fax'] .'</td>
			</tr> -->
			<tr>
				<td align="right">Q&ensp;Q：</td>
				<td>'. $row['UE_qq'] .'</td>
			</tr>
			<tr>
				<td align="right">旺旺：</td>
				<td>'. $row['UE_ww'] .'</td>
			</tr>
			<tr>
				<td align="right">微信：</td>
				<td>'. $row['UE_weixin'] .'</td>
			</tr>
			<tr>
				<td align="right">支付宝：</td>
				<td>'. $row['UE_alipay'] .'</td>
			</tr>
			<tr>
				<td align="right">状态：</td>
				<td>
					<select id="selectState" name="selectState">
					<option value="0" '. Is::Selected($row['UE_state'],0) .'>未审核</option>
					<option value="1" '. Is::Selected($row['UE_state'],1) .'>已审核</option>
					</select>
					<input type="submit" value="设置" onclick=\'DataDeal.location.href="users_deal.php?mudi=revInfo&mode=state&nohrefStr=close&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&theme='. urlencode($row['UE_username']) .'&userID='. $row['UE_ID'] .'&state="+ document.getElementById("selectState").value\' />
				</td>
			</tr>
			</table>
		</div>
		<div style="float:left;width:340px;">
			<table style="width:100%;" align="center" cellpadding="0" cellspacing="0" border="0" summary="" class="padd3">
			<tr><td width="100"></td><td></td></tr>
			');
			if (AppQiandao::Jud()){
				echo('
				<tr>
					<td align="right">最后签到时间：</td>
					<td>
						<input type="text" id="qiandaoTime" name="qiandaoTime" size="22" style="width:155px;" value="'. $row['UE_qiandaoTime'] .'" onfocus=\'WdatePicker({dateFmt:"yyyy-MM-dd HH:mm:ss"})\' class="Wdate" />
						<input type="submit" value="设置" onclick=\'DataDeal.location.href="users_deal.php?mudi=revInfo&mode=qiandaoTime&nohrefStr=close&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&theme='. urlencode($row['UE_username']) .'&userID='. $row['UE_ID'] .'&qiandaoTime="+ document.getElementById("qiandaoTime").value\' />
					</td>
				</tr>
				<tr>
					<td align="right">连续签到次数：</td>
					<td>'. $row['UE_qiandaoNum'] .'</td>
				</tr>
				<tr>
					<td align="right">签到总次数：</td>
					<td>'. $row['UE_qiandaoTotal'] .'</td>
				</tr>
				');
			}
			if (AppWeixin::Jud() && $row['UE_weixinID'] > 0){
				echo('
				<tr>
					<td align="right">微信绑定时间：</td>
					<td>'. $row['UE_weixinTime'] .'</td>
				</tr>
				<tr>
					<td align="right">微信昵称：</td>
					<td>'. $DB->GetOne('select WU_nickname from '. OT_dbPref .'weixinUsers where WU_ID='. $row['UE_weixinID']) .'</td>
				</tr>
				');
			}

			echo('
			<tr>
				<td align="right">性别：</td>
				<td>'. $row['UE_sex'] .'</td>
			</tr>
			<tr>
				<td align="right">城市：</td>
				<td>'. $row['UE_city'] .'</td>
			</tr>
			<tr>
				<td align="right">收货地址：</td>
				<td>'. $row['UE_address'] .'</td>
			</tr>
			<tr>
				<td align="right">邮编：</td>
				<td>'. $row['UE_postCode'] .'</td>
			</tr>
			<tr>
				<td align="right">网址：</td>
				<td>'. $row['UE_web'] .'</td>
			</tr>
			<tr>
				<td valign="top" align="right">备注：</td>
				<td>'. $row['UE_note'] .'</td>
			</tr>
			');
			$levelWhereStr = '';
	//		if ($userSysArr['US_isScore1']==1){
				$levelWhereStr .= ' and UL_score1<='. $row['UE_score1'];
				echo('
				<tr>
					<td align="right">'. $userSysArr['US_score1Name'] .'：</td>
					<td>'. $row['UE_score1'] .'</td>
				</tr>
				');
	//		}
			if ($userSysArr['US_isScore2']==1){
				$levelWhereStr .= ' and UL_score2<='. $row['UE_score2'];
				echo('
				<tr>
					<td align="right">'. $userSysArr['US_score2Name'] .'：</td>
					<td>'. $row['UE_score2'] .'</td>
				</tr>
				');
			}
			if ($userSysArr['US_isScore3']==1){
				$levelWhereStr .= ' and UL_score3<='. $row['UE_score3'];
				echo('
				<tr>
					<td align="right">'. $userSysArr['US_score3Name'] .'：</td>
					<td>'. $row['UE_score3'] .'</td>
				</tr>
				');
			}
			$levelRow = $DB->GetRow('select UL_num,UL_themeStyle,UL_theme,UL_img from '. OT_dbPref .'userLevel where (1=1)'. $levelWhereStr .' order by UL_ID DESC');
			echo('
			<tr>
				<td style="padding:3px;" align="right" valign="top">等级：</td>
				<td style="padding:3px;" align="left">
					['. $levelRow['UL_num'] .'级] <span style="'. $levelRow['UL_themeStyle'] .'">'. $levelRow['UL_theme'] .'</span><br />
					<img src="'. UsersFileAdminDir . $levelRow['UL_img'] .'" />
				</td>
			</tr>
			');
			unset($levelRow);

			$apiStr = '';
			if (strpos($row['UE_apiStr'],'|qq::') !== false){ $apiStr .= '<div>【QQ】<span style="color:red;cursor:pointer;" onclick=\'if (confirm("你确定要解除QQ绑定？")){ DataDeal.location.href="users_deal.php?mudi=revInfo&mode=apiStr&type=qq&nohrefStr=close&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&theme='. urlencode($row['UE_username'] .'解除QQ绑定') .'&userID='. $row['UE_ID'] .'"; }\'>[解除绑定]</span></div>'; }
			if (strpos($row['UE_apiStr'],'|weibo::') !== false){ $apiStr .= '<div>【新浪微博】<span style="color:red;cursor:pointer;" onclick=\'if (confirm("你确定要解除新浪微博绑定？")){ DataDeal.location.href="users_deal.php?mudi=revInfo&mode=apiStr&type=weibo&nohrefStr=close&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&theme='. urlencode($row['UE_username'] .'解除新浪微博绑定') .'&userID='. $row['UE_ID'] .'"; }\'>[解除绑定]</span></div>'; }
			if (strpos($row['UE_apiStr'],'|weixin::') !== false){ $apiStr .= '<div>【微信】<span style="color:red;cursor:pointer;" onclick=\'if (confirm("你确定要解除微信绑定？")){ DataDeal.location.href="users_deal.php?mudi=revInfo&mode=apiStr&type=weixin&nohrefStr=close&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&theme='. urlencode($row['UE_username'] .'解除微信绑定') .'&userID='. $row['UE_ID'] .'"; }\'>[解除绑定]</span></div>'; }
			if ($row['UE_weixinID'] >= 1){ $apiStr .= '<div>【微信公众号】</div>'; }
			echo('
			<tr>
				<td align="right">文章数量：</td>
				<td>'. $row['UE_newsCount'] .'</td>
			</tr>
			<tr>
				<td align="right" valign="top" style="padding-top:6px;">快捷登录：</td>
				<td style="line-height:1.4;">'. $apiStr .'</td>
			</tr>
			</table>
		</div>
		');

		$skin->TableBottom();

		$todayDate = TimeDate::Get();
		$pageSize = 5;
		$page = 1;

		if (AppMail::Jud()){
			echo('<div style="height:8px;"></div>');

			$skin->TableTop2('share_list.gif','','邮件提醒前5条');
			$skin->TableItemTitle('6%,9%,19%,46%,13%,7%','序号,类型,邮箱,模板,发送时间,状态');

			$showRow=$DB->GetLimit('select * from '. OT_dbPref .'mailUsers where MU_userID='. $dataID .' order by MU_time DESC',$pageSize,$page);
			if (! $showRow){
				$skin->TableNoData();
			}else{
				$recordCount=$DB->GetRowCount();
				$pageCount=ceil($recordCount/$pageSize);
				if ($page < 1 || $page > $pageCount){$page=1;}

				echo('
				<tbody class="tabBody padd3td">
				');
				$number=1+($page-1)*$pageSize;
				$rowCount = count($showRow);
				for ($i=0; $i<$rowCount; $i++){
					if ($i % 2 == 1){ $bgcolor='class="tabColorTr"'; }else{ $bgcolor=''; }

					echo('
					<tr id="data'. $showRow[$i]['MU_ID'] .'" '. $bgcolor .'>
						<td align="center">'. $number .'</td>
						<td align="center">'. TypeCN($showRow[$i]['MU_type']) .'</td>
						<td align="center">'. $showRow[$i]['MU_mail'] .'</td>
						<td align="left" title="模板ID：'. $showRow[$i]['MU_userID'] .'">'. $showRow[$i]['MU_tplTheme'] .'</td>
						<td align="center" style="color:'. (TimeDate::Diff('d',$showRow[$i]['MU_time'],$todayDate)==0?'red':'') .';">'. $showRow[$i]['MU_time'] .'</td>
						<td align="center">'. Adm::SwitchCN($showRow[$i]['MU_state'],'sign','') .'</td>
					</tr>
					');
					$number ++;
				}
				echo('
				</tbody>
				');
			}
			unset($showRow);

			echo('</table>');
		}

		if (AppPhone::Jud()){
			echo('<div style="height:8px;"></div>');

			$skin->TableTop2('share_list.gif','','短信提醒前5条');
			$skin->TableItemTitle('6%,9%,19%,46%,13%,7%','序号,类型,手机,模板,发送时间,状态');

			$showRow=$DB->GetLimit('select * from '. OT_dbPref .'phoneUsers where PU_userID='. $dataID .' order by PU_time DESC',$pageSize,$page);
			if (! $showRow){
				$skin->TableNoData();
			}else{
				$recordCount=$DB->GetRowCount();
				$pageCount=ceil($recordCount/$pageSize);
				if ($page < 1 || $page > $pageCount){$page=1;}

				echo('
				<tbody class="tabBody padd3td">
				');
				$number=1+($page-1)*$pageSize;
				$rowCount = count($showRow);
				for ($i=0; $i<$rowCount; $i++){
					if ($i % 2 == 1){ $bgcolor='class="tabColorTr"'; }else{ $bgcolor=''; }

					echo('
					<tr id="data'. $showRow[$i]['PU_ID'] .'" '. $bgcolor .'>
						<td align="center">'. $number .'</td>
						<td align="center">'. TypeCN($showRow[$i]['PU_type']) .'</td>
						<td align="center">'. $showRow[$i]['PU_phone'] .'</td>
						<td align="center" title="模板ID：'. $showRow[$i]['PU_userID'] .'">'. $showRow[$i]['PU_tplTheme'] .'</td>
						<td align="center" style="color:'. (TimeDate::Diff('d',$showRow[$i]['PU_time'],$todayDate)==0?'red':'') .';">'. $showRow[$i]['PU_time'] .'</td>
						<td align="center">'. Adm::SwitchCN($showRow[$i]['PU_state'],'sign','') .'</td>
					</tr>
					');
					$number ++;
				}
				echo('
				</tbody>
				');
			}
			unset($showRow);

			echo('</table>');
		}
		echo('
		</td></tr></table>
		');
	}

}

function TypeCN($str){
	switch ($str){
		case 'reg':		return '注册';
		case 'check':	return '验证';
		case 'rev':		return '更换';
		case 'missPwd':	return '找回密码';
		default :		return is_numeric($str)?'':$str;
	}
}

?>
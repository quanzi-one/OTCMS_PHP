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
<script language="javascript" type="text/javascript" src="js/userSys.js?v='. OT_VERSION .'"></script>
');


switch($mudi){
	case 'infoSet':
		$MB->IsSecMenuRight('alertBack',178,$dataType);
		InfoSet();
		break;

	default:
		die('err');
}

$skin->WebBottom();

$MB->Close();
$DB->Close();





// 新增、修改
function InfoSet(){
	global $DB,$skin,$mudi,$dataType,$dataTypeCN,$sysAdminArr;

	$revexe=$DB->query('select * from '. OT_dbPref .'userSys');
		if ($row = $revexe->fetch()){
			$US_isUserSys			= $row['US_isUserSys'];
			$US_isAuthMail			= $row['US_isAuthMail'];
			$US_isAuthPhone			= $row['US_isAuthPhone'];
			$US_isLockMail			= $row['US_isLockMail'];
			$US_isLockPhone			= $row['US_isLockPhone'];
			$US_isMustMail			= $row['US_isMustMail'];
			$US_isMustPhone			= $row['US_isMustPhone'];
			$US_mustMailStr			= $row['US_mustMailStr'];
			$US_mustPhoneStr		= $row['US_mustPhoneStr'];
			$US_isOnlyMail			= $row['US_isOnlyMail'];
			$US_isOnlyPhone			= $row['US_isOnlyPhone'];
			$US_isShiming			= $row['US_isShiming'];
			$US_isPwdEn				= $row['US_isPwdEn'];
			$US_exitMinute			= $row['US_exitMinute'];
			$US_loginKey			= $row['US_loginKey'];
			$US_announ				= $row['US_announ'];
			$US_userFieldStr		= $row['US_userFieldStr'];
			$US_isReg				= $row['US_isReg'];
			$US_isRegInvite			= $row['US_isRegInvite'];
			$US_isRegApi			= $row['US_isRegApi'];
			$US_apiRecMode			= $row['US_apiRecMode'];
			$US_isRegAudit			= $row['US_isRegAudit'];
			$US_againRegMinute		= $row['US_againRegMinute'];
			$US_regGroupID			= $row['US_regGroupID'];
			$US_regFieldStr			= $row['US_regFieldStr'];
			$US_regNote				= $row['US_regNote'];
			$US_regBadWord			= $row['US_regBadWord'];
			$US_regAnnoun			= $row['US_regAnnoun'];
			$US_regAuthMail			= $row['US_regAuthMail'];
			$US_regAuthPhone		= $row['US_regAuthPhone'];
			$US_isLogin				= $row['US_isLogin'];
			$US_isLoginExp			= $row['US_isLoginExp'];
			$US_isLoginUser			= $row['US_isLoginUser'];
			$US_isLoginMail			= $row['US_isLoginMail'];
			$US_isLoginPhone		= $row['US_isLoginPhone'];
			$US_loginAnnoun			= $row['US_loginAnnoun'];
			$US_isMissPwd			= $row['US_isMissPwd'];
			$US_isMissPwdUser		= $row['US_isMissPwdUser'];
			$US_isMissPwdMail		= $row['US_isMissPwdMail'];
			$US_isMissPwdPhone		= $row['US_isMissPwdPhone'];
			$US_isNews				= $row['US_isNews'];
			$US_isNewsAdd			= $row['US_isNewsAdd'];
			$US_isNewsAudit			= $row['US_isNewsAudit'];
			$US_isNewsRev			= $row['US_isNewsRev'];
			$US_isNewsRevAudit		= $row['US_isNewsRevAudit'];
			$US_isNewsDel			= $row['US_isNewsDel'];
			$US_isRevSource			= $row['US_isRevSource'];
			$US_isRevWriter			= $row['US_isRevWriter'];
			$US_newsSource			= $row['US_newsSource'];
			$US_newsWriter			= $row['US_newsWriter'];
			$US_isNewsUpImg			= $row['US_isNewsUpImg'];
			$US_newsUpImgSize		= $row['US_newsUpImgSize'];
			$US_newsUpImgOss		= $row['US_newsUpImgOss'];
			$US_newsUpImgOri		= $row['US_newsUpImgOri'];
			$US_isNewsUpFile		= $row['US_isNewsUpFile'];
			$US_newsUpFileSize		= $row['US_newsUpFileSize'];
			$US_newsUpFileOss		= $row['US_newsUpFileOss'];
			$US_isNewsReadRight		= $row['US_isNewsReadRight'];
			$US_newsAddiStr			= $row['US_newsAddiStr'];
			$US_isMarkNews			= $row['US_isMarkNews'];
			$US_isNewsVote			= $row['US_isNewsVote'];
			$US_isReply				= $row['US_isReply'];
			$US_topicID				= $row['US_topicID'];
			$US_topAddiID			= $row['US_topAddiID'];
			$US_addiID				= $row['US_addiID'];
			$US_isCheckUser			= $row['US_isCheckUser'];
			$US_addNewsAnnoun		= $row['US_addNewsAnnoun'];
			$US_isScore1			= $row['US_isScore1'];
			$US_isScore2			= $row['US_isScore2'];
			$US_isScore3			= $row['US_isScore3'];
			$US_score1Name			= $row['US_score1Name'];
			$US_score2Name			= $row['US_score2Name'];
			$US_score3Name			= $row['US_score3Name'];
			$US_score1Unit			= $row['US_score1Unit'];
			$US_score2Unit			= $row['US_score2Unit'];
			$US_score3Unit			= $row['US_score3Unit'];
			$US_event				= $row['US_event'];
			$US_tempSaveMin			= $row['US_tempSaveMin'];
			$US_newsBadMode			= $row['US_newsBadMode'];
			$US_newsBadWord			= $row['US_newsBadWord'];
			$US_newsEvent			= $row['US_newsEvent'];
			$US_loginUserRank		= $row['US_loginUserRank'];
			$US_loginMailRank		= $row['US_loginMailRank'];
			$US_loginPhoneRank		= $row['US_loginPhoneRank'];
			$US_loginMailMode		= $row['US_loginMailMode'];
			$US_loginPhoneMode		= $row['US_loginPhoneMode'];

			if (AppNewsGain::Jud()){
				$US_isGainScore			= $row['US_isGainScore'];
				$US_gainScore1Rate		= $row['US_gainScore1Rate'];
				$US_gainScore2Rate		= $row['US_gainScore2Rate'];
				$US_gainScore3Rate		= $row['US_gainScore3Rate'];
			}else{
				$US_isGainScore			= 0;
				$US_gainScore1Rate		= 0;
				$US_gainScore2Rate		= 0;
				$US_gainScore3Rate		= 0;
			}
		}
	unset($revexe);

	echo('
	<form id="dealForm" name="dealForm" method="post" action="userSys_deal.php?mudi='. $mudi .'" onsubmit="return CheckForm()">
	<input type="hidden" id="dataType" name="dataType" value="'. $dataType .'" />
	<input type="hidden" id="dataTypeCN" name="dataTypeCN" value="'. $dataTypeCN .'" />
	<script language="javascript" type="text/javascript">document.write(\'<input type="hidden" name="backURL" value="\'+ document.location.href +\'" />\')</script>

	
	<div class="tabMenu">
	<ul>
	   <li rel="tabBase" class="selected">基本设置</li>
	   <li rel="tabReg">会员登录/注册</li>
	   <li rel="tabCenter">会员中心</li>
	   <li rel="tabNews">会员文章</li>
	   <li rel="tabScore">会员积分</li>
	   <li id="buyBox" rel="tabBuy" style="display:none;">商业版专属</li>
	</ul>
	</div>

	<div class="tabMenuArea">
		<table id="tabBase" cellpadding="0" cellspacing="0" summary="" class="padd3">
		<tr><td style="width:150px;"></td><td></td></tr>
		<tr>
			<td align="right">会员系统：</td>
			<td>
				<label><input type="radio" id="isUserSys1" name="isUserSys" value="1" onclick="CheckUserBox();" '. Is::Checked($US_isUserSys,1) .' />启用<label>&ensp;&ensp;&ensp;
				<label><input type="radio" id="isUserSys0" name="isUserSys" value="0" onclick="CheckUserBox();" '. Is::Checked($US_isUserSys,0) .' />禁用<label>&ensp;&ensp;&ensp;
			</td>
		</tr>
		<tbody id="userBox">
		'. AppMail::UserSysTrBox1($US_isAuthMail, $US_isLockMail, $US_isMustMail, $US_mustMailStr) .'
		'. AppPhone::UserSysTrBox1($US_isAuthPhone, $US_isLockPhone, $US_isMustPhone, $US_mustPhoneStr) .'
		<tr>
			<td align="right">邮箱唯一性：</td>
			<td>
				<label><input type="radio" id="isOnlyMail1" name="isOnlyMail" value="1" '. Is::Checked($US_isOnlyMail,1) .' />启用<label>&ensp;&ensp;&ensp;
				<label><input type="radio" id="isOnlyMail0" name="isOnlyMail" value="0" '. Is::Checked($US_isOnlyMail,0) .' />禁用<label>&ensp;&ensp;&ensp;
			</td>
		</tr>
		<tr>
			<td align="right">手机唯一性：</td>
			<td>
				<label><input type="radio" id="isOnlyPhone1" name="isOnlyPhone" value="1" '. Is::Checked($US_isOnlyPhone,1) .' />启用<label>&ensp;&ensp;&ensp;
				<label><input type="radio" id="isOnlyPhone0" name="isOnlyPhone" value="0" '. Is::Checked($US_isOnlyPhone,0) .' />禁用<label>&ensp;&ensp;&ensp;
			</td>
		</tr>
		<tr style="display:none;">
			<td align="right">用户实名认证：</td>
			<td>
				<label><input type="radio" name="isShiming" value="1" '. Is::Checked($US_isShiming,1) .' />启用<label>&ensp;&ensp;&ensp;
				<label><input type="radio" name="isShiming" value="0" '. Is::Checked($US_isShiming,0) .' />禁用<label>&ensp;&ensp;&ensp;
			</td>
		</tr>
		<tr style="display:none;">
			<td align="right">表单密码加密提交：</td>
			<td>
				<label><input type="radio" name="isPwdEn" value="1" '. Is::Checked($US_isPwdEn,1) .' />启用<label>&ensp;&ensp;&ensp;
				<label><input type="radio" name="isPwdEn" value="0" '. Is::Checked($US_isPwdEn,0) .' />禁用<label>&ensp;&ensp;&ensp;
			</td>
		</tr>
		<tr>
			<td align="right">会员登录超时时间：</td>
			<td>
				<input type="text" name="exitMinute" value="'. $US_exitMinute .'" style="width:50px;" onkeyup="if (this.value!=FiltInt(this.value)){this.value=FiltInt(this.value)}" />分钟
				<span class="font2_2">&ensp;(值如为0，则表示无时间限制)</span>
			</td>
		</tr>
		<tr>
			<td align="right">通信密钥：</td>
			<td class="font2_2">
				<input type="text" id="loginKey" name="loginKey" style="width:260px;" maxlength="36" value="'. $US_loginKey .'" />
				&ensp;<input type="button" value="随机生成" onclick=\'$id("loginKey").value=RndNum(36);\' />
				<br />（会直接影响前台在登录状态中会员，致使他们要重新登录。）
			</td>
		</tr>
		</table>


		<table id="tabCenter" style="display:none;" cellpadding="0" cellspacing="0" summary="" class="padd3">
		<tr><td style="width:150px;"></td><td></td></tr>
		<tr>
			<td align="right" valign="top" style="padding-top:6px;">
				资料字段集：
			</td>
			<td style="padding-bottom:4px;" class="list">
				<ul>
				<li style="width:208px;">
					<label><input type="checkbox" id="regField_mail" name="userFieldStr[]" value="|昵称|" '. Is::InstrChecked($US_userFieldStr,'|昵称|') .' />昵称</label>
					<span style="color:#999;">(<label><input type="checkbox"  name="userFieldStr[]" value="|昵称必填|" '. Is::InstrChecked($US_userFieldStr,'|昵称必填|') .' />必填</label>、<label><input type="checkbox"  name="userFieldStr[]" value="|昵称不可改|" '. Is::InstrChecked($US_userFieldStr,'|昵称不可改|') .' />不可改</label>)</span>
				</li>
				<li style="width:208px;">
					<label><input type="checkbox" id="regField_mail" name="userFieldStr[]" value="|性别|" '. Is::InstrChecked($US_userFieldStr,'|性别|') .' />性别</label>
					<span style="color:#999;">(<label><input type="checkbox"  name="userFieldStr[]" value="|性别必填|" '. Is::InstrChecked($US_userFieldStr,'|性别必填|') .' />必填</label>、<label><input type="checkbox"  name="userFieldStr[]" value="|性别不可改|" '. Is::InstrChecked($US_userFieldStr,'|性别不可改|') .' />不可改</label>)</span>
				</li>
				<li style="width:208px;">
					<label><input type="checkbox" id="regField_mail" name="userFieldStr[]" value="|收货地址|" '. Is::InstrChecked($US_userFieldStr,'|收货地址|') .' />收货地址</label>
					<span style="color:#999;">(<label><input type="checkbox"  name="userFieldStr[]" value="|收货地址必填|" '. Is::InstrChecked($US_userFieldStr,'|收货地址必填|') .' />必填</label>、<label><input type="checkbox"  name="userFieldStr[]" value="|收货地址不可改|" '. Is::InstrChecked($US_userFieldStr,'|收货地址不可改|') .' />不可改</label>)</span>
				</li>
				<li style="width:208px;">
					<label><input type="checkbox" id="regField_mail" name="userFieldStr[]" value="|邮编|" '. Is::InstrChecked($US_userFieldStr,'|邮编|') .' />邮编</label>
					<span style="color:#999;">(<label><input type="checkbox"  name="userFieldStr[]" value="|邮编必填|" '. Is::InstrChecked($US_userFieldStr,'|邮编必填|') .' />必填</label>、<label><input type="checkbox"  name="userFieldStr[]" value="|邮编不可改|" '. Is::InstrChecked($US_userFieldStr,'|邮编不可改|') .' />不可改</label>)</span>
				</li>
				<li style="width:208px;">
					<label><input type="checkbox" id="regField_mail" name="userFieldStr[]" value="|QQ|" '. Is::InstrChecked($US_userFieldStr,'|QQ|') .' />QQ</label>
					<span style="color:#999;">(<label><input type="checkbox"  name="userFieldStr[]" value="|QQ必填|" '. Is::InstrChecked($US_userFieldStr,'|QQ必填|') .' />必填</label>、<label><input type="checkbox"  name="userFieldStr[]" value="|QQ不可改|" '. Is::InstrChecked($US_userFieldStr,'|QQ不可改|') .' />不可改</label>)</span>
				</li>
				<li style="width:208px;">
					<label><input type="checkbox" id="regField_mail" name="userFieldStr[]" value="|微信|" '. Is::InstrChecked($US_userFieldStr,'|微信|') .' />微信</label>
					<span style="color:#999;">(<label><input type="checkbox"  name="userFieldStr[]" value="|微信必填|" '. Is::InstrChecked($US_userFieldStr,'|微信必填|') .' />必填</label>、<label><input type="checkbox"  name="userFieldStr[]" value="|微信不可改|" '. Is::InstrChecked($US_userFieldStr,'|微信不可改|') .' />不可改</label>)</span>
				</li>
				<li style="width:208px;">
					<label><input type="checkbox" name="userFieldStr[]" value="|支付宝|" '. Is::InstrChecked($US_userFieldStr,'|支付宝|') .' />支付宝</label>
					<span style="color:#999;">(<label><input type="checkbox"  name="userFieldStr[]" value="|支付宝必填|" '. Is::InstrChecked($US_userFieldStr,'|支付宝必填|') .' />必填</label>、<label><input type="checkbox"  name="userFieldStr[]" value="|支付宝不可改|" '. Is::InstrChecked($US_userFieldStr,'|支付宝不可改|') .' />不可改</label>)</span>
				</li>
				<li style="width:208px;">
					<label><input type="checkbox" name="userFieldStr[]" value="|旺旺|" '. Is::InstrChecked($US_userFieldStr,'|旺旺|') .' />旺旺</label>
					<span style="color:#999;">(<label><input type="checkbox"  name="userFieldStr[]" value="|旺旺必填|" '. Is::InstrChecked($US_userFieldStr,'|旺旺必填|') .' />必填</label>、<label><input type="checkbox"  name="userFieldStr[]" value="|旺旺不可改|" '. Is::InstrChecked($US_userFieldStr,'|旺旺不可改|') .' />不可改</label>)</span>
				</li>
				<li style="width:208px;">
					<label><input type="checkbox" name="userFieldStr[]" value="|个人主页|" '. Is::InstrChecked($US_userFieldStr,'|个人主页|') .' />个人主页</label>
					<span style="color:#999;">(<label><input type="checkbox"  name="userFieldStr[]" value="|个人主页必填|" '. Is::InstrChecked($US_userFieldStr,'|个人主页必填|') .' />必填</label>、<label><input type="checkbox"  name="userFieldStr[]" value="|个人主页不可改|" '. Is::InstrChecked($US_userFieldStr,'|个人主页不可改|') .' />不可改</label>)</span>
				</li>
				<li style="width:208px;">
					<label><input type="checkbox" name="userFieldStr[]" value="|备注|" '. Is::InstrChecked($US_userFieldStr,'|备注|') .' />备注</label>
					<span style="color:#999;">(<label><input type="checkbox"  name="userFieldStr[]" value="|备注必填|" '. Is::InstrChecked($US_userFieldStr,'|备注必填|') .' />必填</label>、<label><input type="checkbox"  name="userFieldStr[]" value="|备注不可改|" '. Is::InstrChecked($US_userFieldStr,'|备注不可改|') .' />不可改</label>)</span>
				</li>
				</ul>
			</td>
		</tr>
		<tr>
			<td align="right" valign="top" style="padding-top:5px;">公告：</td>
			<td>
				<textarea id="announ" name="announ" cols="40" rows="4" style="width:680px;height:120px;" class="text" onclick=\'LoadEditor("announ",680,120,"|miniMenu|");\' title="点击开启编辑器模式">'. Str::MoreReplace($US_announ,'textarea') .'</textarea>
			</td>
		</tr>
		</table>


		<table id="tabReg" style="display:none;" cellpadding="0" cellspacing="0" summary="" class="padd3">
		<tr><td style="width:150px;"></td><td></td></tr>
		<tr>
			<td align="right">会员登录：</td>
			<td>
				<label><input type="radio" id="isLogin1" name="isLogin" value="1" onclick="CheckLoginBox();" '. Is::Checked($US_isLogin,1) .' />启用<label>&ensp;&ensp;&ensp;
				<label><input type="radio" id="isLogin0" name="isLogin" value="0" onclick="CheckLoginBox();" '. Is::Checked($US_isLogin,0) .' />禁用<label>&ensp;&ensp;&ensp;
			</td>
		</tr>
		<tr class="loginBoxClass" style="display:none;">
			<td align="right" class="font1_2d">登录状态保持：</td>
			<td>
				<select id="isLoginExp" name="isLoginExp">
				<option value="0" '. Is::Selected($US_isLoginExp,0) .'>关闭</option>
				<option value="1" '. Is::Selected($US_isLoginExp,1) .'>30天</option>
				<option value="2" '. Is::Selected($US_isLoginExp,2) .'>15天</option>
				<option value="3" '. Is::Selected($US_isLoginExp,3) .'>7天</option>
				<option value="4" '. Is::Selected($US_isLoginExp,4) .'>3天</option>
				<option value="5" '. Is::Selected($US_isLoginExp,5) .'>1天</option>
				<option value="21" '. Is::Selected($US_isLoginExp,21) .'>12小时</option>
				<option value="22" '. Is::Selected($US_isLoginExp,22) .'>6小时</option>
				<option value="23" '. Is::Selected($US_isLoginExp,23) .'>3小时</option>
				<option value="24" '. Is::Selected($US_isLoginExp,24) .'>2小时</option>
				<option value="25" '. Is::Selected($US_isLoginExp,25) .'>1小时</option>
				<option value="31" '. Is::Selected($US_isLoginExp,31) .'>30分钟</option>
				<option value="32" '. Is::Selected($US_isLoginExp,32) .'>15分钟</option>
				</select>&ensp;
			</td>
		</tr>
		<tr class="loginBoxClass" style="display:none;">
			<td align="right" class="font1_2d">会员<span style="color:blue;">用户名</span>登录：</td>
			<td>
				<label><input type="radio" name="isLoginUser" value="1" '. Is::Checked($US_isLoginUser,1) .' />启用<label>&ensp;&ensp;&ensp;
				<label><input type="radio" name="isLoginUser" value="0" '. Is::Checked($US_isLoginUser,0) .' />禁用<label>&ensp;&ensp;&ensp;
				排序<input type="text" name="loginUserRank" value="'. $US_loginUserRank .'" style="width:30px;" onkeyup="if (this.value!=FiltInt(this.value)){this.value=FiltInt(this.value)}" />
				<span class="font2_2">&ensp;(值越小排越前)</span>
			</td>
		</tr>
		'. AppLoginMail::UserSysTrBox1($US_isLoginMail, $US_loginMailRank, $US_loginMailMode) .'
		'. AppLoginPhone::UserSysTrBox1($US_isLoginPhone, $US_loginPhoneRank, $US_loginPhoneMode) .'
		<tr>
			<td align="right" valign="top" style="padding-top:6px;" class="font1_2d">
				登录页公告：<br />
				<span class="font2_2">（启用：在登录表单底部）<br />（禁用：在关闭提示下面）</span>
			</td>
			<td style="padding-bottom:4px;"><textarea id="loginAnnoun" name="loginAnnoun" rows="5" cols="40" style="width:500px; height:100px;" onclick=\'LoadEditor("loginAnnoun",500,100,"|miniMenu|");\' title="点击开启编辑器模式">'. $US_loginAnnoun .'</textarea></td>
		</tr>
		<tr>
			<td align="right">会员注册：</td>
			<td>
				<label><input type="radio" id="isReg1" name="isReg" value="1" onclick="CheckRegBox();" '. Is::Checked($US_isReg,1) .' />启用<label>&ensp;&ensp;&ensp;
				<label><input type="radio" id="isReg0" name="isReg" value="0" onclick="CheckRegBox();" '. Is::Checked($US_isReg,0) .' />禁用<label>&ensp;&ensp;&ensp;
			</td>
		</tr>
		'. AppMail::UserSysTrBox2($US_regAuthMail) .'
		'. AppPhone::UserSysTrBox2($US_regAuthPhone) .'
		<tr class="regBoxClass">
			<td align="right" class="font1_2d">会员审核：</td>
			<td>
				<label><input type="radio" name="isRegAudit" value="1" '. Is::Checked($US_isRegAudit,1) .' />启用<label>&ensp;&ensp;&ensp;
				<label><input type="radio" name="isRegAudit" value="0" '. Is::Checked($US_isRegAudit,0) .' />禁用<label>&ensp;&ensp;&ensp;
			</td>
		</tr>
		<tr class="regBoxClass">
			<td align="right" class="font1_2d">默认会员组：</td>
			<td>
				<select id="regGroupID" name="regGroupID">
				');

				$gexe=$DB->query('select UG_ID,UG_theme from '. OT_dbPref .'userGroup where UG_state=1 order by UG_rank ASC');
				while ($row = $gexe->fetch()){
					echo('<option value="'. $row['UG_ID'] .'" '. Is::Selected($US_regGroupID,$row['UG_ID']) .'>'. $row['UG_theme'] .'</option>');
				}
				unset($gexe);

				echo('
				</select>&ensp;
			</td>
		</tr>
		<tr class="regBoxClass">
			<td align="right" class="font1_2d">再次注册间隔时间：</td>
			<td>
				<input type="text" name="againRegMinute" value="'. $US_againRegMinute .'" style="width:50px;" onkeyup="if (this.value!=FiltInt(this.value)){this.value=FiltInt(this.value)}" />分钟
				<span class="font2_2">&ensp;(值如为0，则表示无时间限制)</span>
			</td>
		</tr>
		<tr class="regBoxClass">
			<td align="right" valign="top" style="padding-top:6px;" class="font1_2d">
				注册字段集：
			</td>
			<td style="padding-bottom:4px;">
				<label><input type="checkbox" id="regField_mail" name="regFieldStr[]" value="|邮箱|" '. Is::InstrChecked($US_regFieldStr,'|邮箱|') .' />邮箱</label>(<label><input type="checkbox" id="regField_mail2" name="regFieldStr[]" value="|邮箱必填|" '. Is::InstrChecked($US_regFieldStr,'|邮箱必填|') .' />必填</label>)&ensp;&ensp;
				<label><input type="checkbox" id="regField_phone" name="regFieldStr[]" value="|手机|" '. Is::InstrChecked($US_regFieldStr,'|手机|') .' />手机</label>(<label><input type="checkbox" id="regField_phone2" name="regFieldStr[]" value="|手机必填|" '. Is::InstrChecked($US_regFieldStr,'|手机必填|') .' />必填</label>)&ensp;&ensp;
				<label><input type="checkbox" name="regFieldStr[]" value="|昵称|" '. Is::InstrChecked($US_regFieldStr,'|昵称|') .' />昵称</label>(<label><input type="checkbox" name="regFieldStr[]" value="|昵称必填|" '. Is::InstrChecked($US_regFieldStr,'|昵称必填|') .' />必填</label>)&ensp;&ensp;
				<label><input type="checkbox" name="regFieldStr[]" value="|QQ|" '. Is::InstrChecked($US_regFieldStr,'|QQ|') .' />QQ</label>(<label><input type="checkbox" name="regFieldStr[]" value="|QQ必填|" '. Is::InstrChecked($US_regFieldStr,'|QQ必填|') .' />必填</label>)&ensp;&ensp;
				<label><input type="checkbox" name="regFieldStr[]" value="|微信|" '. Is::InstrChecked($US_regFieldStr,'|微信|') .' />微信</label>(<label><input type="checkbox" name="regFieldStr[]" value="|微信必填|" '. Is::InstrChecked($US_regFieldStr,'|微信必填|') .' />必填</label>)&ensp;&ensp;
				<label><input type="checkbox" name="regFieldStr[]" value="|旺旺|" '. Is::InstrChecked($US_regFieldStr,'|旺旺|') .' />旺旺</label>(<label><input type="checkbox" name="regFieldStr[]" value="|旺旺必填|" '. Is::InstrChecked($US_regFieldStr,'|旺旺必填|') .' />必填</label>)&ensp;&ensp;
			</td>
		</tr>
		<tr class="regBoxClass">
			<td align="right" valign="top" style="padding-top:6px;" class="font1_2d">
				注册时禁止的关键词：<br />
				<span class="font2_2">（多个用竖杆“|”隔开）</span>
			</td>
			<td style="padding-bottom:4px;"><textarea id="regBadWord" name="regBadWord" rows="5" cols="40" style="width:500px; height:60px;">'. $US_regBadWord .'</textarea></td>
		</tr>
		<tr class="regBoxClass">
			<td align="right" valign="top" class="font1_2d">注册协议：</td>
			<td>
				<textarea id="regNote" name="regNote" cols="40" rows="4" style="width:500px;height:180px;" class="text" onclick=\'LoadEditor("regNote",500,180,"|miniMenu|");\' title="点击开启编辑器模式">'. Str::MoreReplace($US_regNote,'textarea') .'</textarea>
			</td>
		</tr>
		<tr> <!-- class="regBoxClass" -->
			<td align="right" valign="top" style="padding-top:6px;" class="font1_2d">
				注册页公告：<br />
				<span class="font2_2">（启用：在注册表单底部）<br />（禁用：在关闭提示下面）</span>
			</td>
			<td style="padding-bottom:4px;"><textarea id="regAnnoun" name="regAnnoun" rows="5" cols="40" style="width:500px; height:100px;" onclick=\'LoadEditor("regAnnoun",500,100,"|miniMenu|");\' title="点击开启编辑器模式">'. $US_regAnnoun .'</textarea></td>
		</tr>
		</tbody>
		<tr>
			<td align="right">忘记密码找回：</td>
			<td>
				<label><input type="radio" id="isMissPwd1" name="isMissPwd" value="1" onclick="CheckMissPwdBox();" '. Is::Checked($US_isMissPwd,1) .' />启用<label>&ensp;&ensp;&ensp;
				<label><input type="radio" id="isMissPwd0" name="isMissPwd" value="0" onclick="CheckMissPwdBox();" '. Is::Checked($US_isMissPwd,0) .' />禁用<label>&ensp;&ensp;&ensp;
			</td>
		</tr>
		<tr class="missPwdBoxClass">
			<td align="right" class="font1_2d">用户名找回：</td>
			<td>
				<label><input type="radio" name="isMissPwdUser" value="1" '. Is::Checked($US_isMissPwdUser,1) .' />启用<label>&ensp;&ensp;&ensp;
				<label><input type="radio" name="isMissPwdUser" value="0" '. Is::Checked($US_isMissPwdUser,0) .' />禁用<label>&ensp;&ensp;&ensp;
			</td>
		</tr>
		'. AppMail::UserSysTrBox3($US_isMissPwdMail,$US_event) .'
		'. AppPhone::UserSysTrBox3($US_isMissPwdPhone,$US_event) .'
		</table>


		<table id="tabNews" style="display:none;" cellpadding="0" cellspacing="0" summary="" class="padd3">
		<tr><td style="width:150px;"></td><td></td></tr>
		<tr>
			<td align="right">会员文章功能：</td>
			<td>
				<label><input type="radio" name="isNews" value="1" '. Is::Checked($US_isNews,1) .' />开启<label>&ensp;&ensp;&ensp;
				<label><input type="radio" name="isNews" value="0" '. Is::Checked($US_isNews,0) .' />关闭<label>&ensp;&ensp;&ensp;
			</td>
		</tr>
		<tr>
			<td align="right">会员发表文章：</td>
			<td>
				<label><input type="radio" name="isNewsAdd" value="1" '. Is::Checked($US_isNewsAdd,1) .' />允许<label>&ensp;&ensp;&ensp;
				<label><input type="radio" name="isNewsAdd" value="0" '. Is::Checked($US_isNewsAdd,0) .' />禁止<label>&ensp;&ensp;&ensp;
			</td>
		</tr>
		<tr>
			<td align="right">会员文章审核：</td>
			<td>
				<label><input type="radio" name="isNewsAudit" value="1" '. Is::Checked($US_isNewsAudit,1) .' />启用<label>&ensp;&ensp;&ensp;
				<label><input type="radio" name="isNewsAudit" value="0" '. Is::Checked($US_isNewsAudit,0) .' />禁用<label>&ensp;&ensp;&ensp;
			</td>
		</tr>
		<tr>
			<td align="right">会员修改自己文章：</td>
			<td>
				<label><input type="radio" name="isNewsRev" value="1" '. Is::Checked($US_isNewsRev,1) .' />允许<label>&ensp;&ensp;&ensp;
				<label><input type="radio" name="isNewsRev" value="0" '. Is::Checked($US_isNewsRev,0) .' />禁止<label>&ensp;&ensp;&ensp;
			</td>
		</tr>
		<tr>
			<td align="right">会员修改后再审核：</td>
			<td>
				<label><input type="radio" name="isNewsRevAudit" value="1" '. Is::Checked($US_isNewsRevAudit,1) .' />启用<label>&ensp;&ensp;&ensp;
				<label><input type="radio" name="isNewsRevAudit" value="0" '. Is::Checked($US_isNewsRevAudit,0) .' />禁用<label>&ensp;&ensp;&ensp;
			</td>
		</tr>
		<tr>
			<td align="right">会员删除自己文章：</td>
			<td>
				<label><input type="radio" name="isNewsDel" value="1" '. Is::Checked($US_isNewsDel,1) .' />允许<label>&ensp;&ensp;&ensp;
				<label><input type="radio" name="isNewsDel" value="0" '. Is::Checked($US_isNewsDel,0) .' />禁止<label>&ensp;&ensp;&ensp;
			</td>
		</tr>
		<tr>
			<td align="right"><span class="font3_2">会员投稿</span>修改来源：</td>
			<td>
				<label><input type="radio" name="isRevSource" value="1" '. Is::Checked($US_isRevSource,1) .' />允许<label>&ensp;&ensp;&ensp;
				<label><input type="radio" name="isRevSource" value="0" '. Is::Checked($US_isRevSource,0) .' />禁止<label>&ensp;&ensp;&ensp;
			</td>
		</tr>
		<tr>
			<td align="right"><span class="font3_2">会员投稿</span>修改作者：</td>
			<td>
				<label><input type="radio" name="isRevWriter" value="1" '. Is::Checked($US_isRevWriter,1) .' />允许<label>&ensp;&ensp;&ensp;
				<label><input type="radio" name="isRevWriter" value="0" '. Is::Checked($US_isRevWriter,0) .' />禁止<label>&ensp;&ensp;&ensp;
			</td>
		</tr>
		<tr>
			<td align="right"><span class="font3_2">会员投稿</span>来源默认值：</td>
			<td class="font2_2">
				<input type="text" id="newsSource" name="newsSource" style="width:260px;" maxlength="36" value="'. $US_newsSource .'" />
			</td>
		</tr>
		<tr>
			<td align="right"><span class="font3_2">会员投稿</span>作者默认值：</td>
			<td class="font2_2">
				<input type="text" id="newsWriter" name="newsWriter" style="width:260px;" maxlength="36" value="'. $US_newsWriter .'" />
				<input type="button" value="会员用户名" onclick=\'$id("newsWriter").value="{会员用户名}";\' />
				<input type="button" value="会员用户名部分隐藏" onclick=\'$id("newsWriter").value="{会员用户名部分隐藏}";\' />
				<input type="button" value="会员昵称" onclick=\'$id("newsWriter").value="{会员昵称}";\' />
			</td>
		</tr>
		<tr style="display:none;">
			<td align="right"><span class="font3_2">会员投稿</span>阅读限制：</td>
			<td>
				<label><input type="radio" name="isNewsReadRight" value="1" '. Is::Checked($US_isNewsReadRight,1) .' />开启<label>&ensp;&ensp;&ensp;
				<label><input type="radio" name="isNewsReadRight" value="0" '. Is::Checked($US_isNewsReadRight,0) .' />关闭<label>&ensp;&ensp;&ensp;
			</td>
		</tr>
		<tr>
			<td align="right"><span class="font3_2">会员投稿</span>文章属性：</td>
			<td align="left">
				<label><input type="checkbox" name="newsAddiStr[]" value="|new|" '. Is::InstrChecked($US_newsAddiStr,'|new|') .' />最新消息</label>&ensp;
				<label><input type="checkbox" name="newsAddiStr[]" value="|homeThumb|" class="addiImg" '. Is::InstrChecked($US_newsAddiStr,'|homeThumb|') .' />首页缩略图</label>&ensp;
				<label><input type="checkbox" name="newsAddiStr[]" value="|thumb|" class="addiImg" '. Is::InstrChecked($US_newsAddiStr,'|thumb|') .' />缩略图</label>&ensp;
				<label><input type="checkbox" name="newsAddiStr[]" value="|flash|" class="addiImg" '. Is::InstrChecked($US_newsAddiStr,'|flash|') .' />幻灯片</label>&ensp;
				<label><input type="checkbox" name="newsAddiStr[]" value="|img|" class="addiImg" '. Is::InstrChecked($US_newsAddiStr,'|img|') .' />滚动图片</label>&ensp;
				<label><input type="checkbox" name="newsAddiStr[]" value="|marquee|" '. Is::InstrChecked($US_newsAddiStr,'|marquee|') .' />滚动信息</label>&ensp;
				<label><input type="checkbox" name="newsAddiStr[]" value="|recom|" '. Is::InstrChecked($US_newsAddiStr,'|recom|') .' />推荐</label>&ensp;
				<label><input type="checkbox" name="newsAddiStr[]" value="|top|" '. Is::InstrChecked($US_newsAddiStr,'|top|') .' />置顶</label>
			</td>
		</tr>
		<tr>
			<td align="right"><span class="font3_2">会员投稿</span>投票方式：</td>
			<td align="left">
				<label><input type="radio" name="isNewsVote" value="99" '. Is::Checked($US_isNewsVote,99) .' />用户自己选择</label>&ensp;&ensp;
				<label><input type="radio" name="isNewsVote" value="1" '. Is::Checked($US_isNewsVote,1) .' />默认心情</label>&ensp;&ensp;
				<label><input type="radio" name="isNewsVote" value="2" '. Is::Checked($US_isNewsVote,2) .' />默认顶踩</label>&ensp;&ensp;
				<label><input type="radio" name="isNewsVote" value="11" '. Is::Checked($US_isNewsVote,11) .' />默认百度喜欢按钮</label>&ensp;&ensp;
				<label><input type="radio" name="isNewsVote" value="0" '. Is::Checked($US_isNewsVote,0) .' />默认关闭</label>&ensp;&ensp;
			</td>
		</tr>
		<tr>
			<td align="right"><span class="font3_2">会员投稿</span>相关文章：</td>
			<td>
				<label><input type="radio" name="isMarkNews" value="99" '. Is::Checked($US_isMarkNews,99) .' />用户自己选择<label>&ensp;&ensp;
				<label><input type="radio" name="isMarkNews" value="1" '. Is::Checked($US_isMarkNews,1) .' />默认开启<label>&ensp;&ensp;
				<label><input type="radio" name="isMarkNews" value="0" '. Is::Checked($US_isMarkNews,0) .' />默认关闭<label>&ensp;&ensp;
			</td>
		</tr>
		<tr>
			<td align="right"><span class="font3_2">会员投稿</span>评论区：</td>
			<td>
				<label><input type="radio" name="isReply" value="99" '. Is::Checked($US_isReply,99) .' />用户自己选择</label>&ensp;&ensp;
				<label><input type="radio" name="isReply" value="1" '. Is::Checked($US_isReply,1) .' />默认开启</label>&ensp;&ensp;
				<label><input type="radio" name="isReply" value="10" '. Is::Checked($US_isReply,10) .' />默认仅限会员</label>&ensp;&ensp;
				<label><input type="radio" name="isReply" value="0" '. Is::Checked($US_isReply,0) .' />默认关闭</label>&ensp;&ensp;
			</td>
		</tr>
		'. AppTopic::UserSysTrBox1($US_topicID) .'
		');

		$topAddiOptionStr = $addiOptionStr = '';
		$addiexe=$DB->query("select IW_ID,IW_theme from ". OT_dbPref ."infoWeb where IW_type='news' order by IW_rank ASC");
		while ($row = $addiexe->fetch()){
			$topAddiOptionStr .= '<option value="'. $row['IW_ID'] .'" '. Is::Selected($US_topAddiID,$row['IW_ID']) .'>'. $row['IW_theme'] .'</option>';
			$addiOptionStr .= '<option value="'. $row['IW_ID'] .'" '. Is::Selected($US_addiID,$row['IW_ID']) .'>'. $row['IW_theme'] .'</option>';
		}
		unset($addiexe);

		echo('
		<tr>
			<td align="right"><span class="font3_2">会员投稿</span>正文头附加内容：</td>
			<td>
				<select id="topAddiID" name="topAddiID">
				<option value="0">无</option>
				<option value="-1" '. Is::Selected($US_topAddiID,-1) .'>用户自己选择</option>
				'. $topAddiOptionStr .'
				</select>&ensp;
			</td>
		</tr>
		<tr>
			<td align="right"><span class="font3_2">会员投稿</span>正文尾附加内容：</td>
			<td>
				<select id="addiID" name="addiID">
				<option value="0">无</option>
				<option value="-1" '. Is::Selected($US_addiID,-1) .'>用户自己选择</option>
				'. $addiOptionStr .'
				</select>&ensp;
			</td>
		</tr>
		<tr>
			<td align="right"><span class="font3_2">会员投稿</span>付费阅读：</td>
			<td>
				<label><input type="radio" name="isCheckUser" value="1" '. Is::Checked($US_isCheckUser,1) .' />开启</label>&ensp;&ensp;
				<label><input type="radio" name="isCheckUser" value="0" '. Is::Checked($US_isCheckUser,0) .' />关闭</label>&ensp;&ensp;
			</td>
		</tr>
		<tr>
			<td align="right" valign="top" style="padding-top:6px;"><span class="font3_2">会员投稿</span>公告：</td>
			<td style="padding-bottom:4px;"><textarea id="addNewsAnnoun" name="addNewsAnnoun" rows="5" cols="40" style="width:500px; height:100px;" onclick=\'LoadEditor("addNewsAnnoun",500,100,"|miniMenu|");\' title="点击开启编辑器模式">'. $US_addNewsAnnoun .'</textarea></td>
		</tr>
		<tr>
			<td align="right"><span class="font3_2">会员投稿</span>禁止词模式：</td>
			<td>
				<label><input type="radio" name="newsBadMode" value="0" '. Is::Checked($US_newsBadMode,0) .' />提醒并返回</label>&ensp;&ensp;
				<label><input type="radio" name="newsBadMode" value="1" '. Is::Checked($US_newsBadMode,1) .' />直接删除</label>&ensp;&ensp;
				<label><input type="radio" name="newsBadMode" value="2" '. Is::Checked($US_newsBadMode,2) .' />“[屏蔽词]”替换</label>&ensp;&ensp;
			</td>
		</tr>
		<tr>
			<td align="right" valign="top" style="padding-top:6px;">
				<span class="font3_2">会员投稿</span>禁止词：<br />
				<span class="font2_2">（多个用竖杆“|”隔开）</span>
			</td>
			<td style="padding-bottom:4px;"><textarea id="newsBadWord" name="newsBadWord" rows="5" cols="40" style="width:500px; height:40px;">'. $US_newsBadWord .'</textarea></td>
		</tr>
		<tr style="display:none;">
			<td align="right"><span class="font3_2">会员投稿</span>临时存稿间隔：</td>
			<td><input type="text" id="tempSaveMin" name="tempSaveMin" size="25" style="width:50px;" value="'. $US_tempSaveMin .'" /> 分钟</td>
		</tr>
		<tr>
			<td align="right"><span class="font3_2">会员投稿</span>编辑器：</td>
			<td align="left">
				<label><input type="checkbox" name="newsEvent[]" value="|noLink|" '. Is::InstrChecked($US_newsEvent,'|noLink|') .' />过滤超链接</label>&ensp;
				<label><input type="checkbox" name="newsEvent[]" value="|noUrl|" '. Is::InstrChecked($US_newsEvent,'|noUrl|') .' />过滤网址</label><span class="font2_2">(慎用，网址左右两侧如有英文字符可能会被过滤)</span>&ensp;
			</td>
		</tr>
		'. AppNewsGain::UserSysTrBox1($US_isGainScore, $US_gainScore1Rate, $US_gainScore2Rate, $US_gainScore3Rate, $US_isScore1, $US_isScore2, $US_isScore3, $US_score1Name, $US_score2Name, $US_score3Name) .'
		</table>


		<table id="tabScore" style="display:none;" cellpadding="0" cellspacing="0" summary="" class="padd3">
		<tr>
			<td style="width:150px;" align="right">积分类别：</td>
			<td style="width:130px;" align="center">积分1</td>
			<td style="width:130px;" align="center">积分2</td>
			<td style="width:130px;" align="center">积分3</td>
		</tr>
		<tr>
			<td align="right">是否启用：</td>
			<td align="center"><input type="checkbox" id="isScore1" name="isScore1" value="1" onclick="CheckScoreBox();" disabled="true" '. Is::Checked(1,1) .' /></td>
			<td align="center"><input type="checkbox" id="isScore2" name="isScore2" value="1" onclick="CheckScoreBox();" '. Is::Checked($US_isScore2,1) .' /></td>
			<td align="center"><input type="checkbox" id="isScore3" name="isScore3" value="1" onclick="CheckScoreBox();" '. Is::Checked($US_isScore3,1) .' /></td>
		</tr>
		<tr>
			<td align="right">积分名称：</td>
			<td align="center"><input type="text" id="score1Name" name="score1Name" style="width:60px;" class="score1Class" value="'. $US_score1Name .'" /></td>
			<td align="center"><input type="text" id="score2Name" name="score2Name" style="width:60px;" class="score2Class" value="'. $US_score2Name .'" /></td>
			<td align="center"><input type="text" id="score3Name" name="score3Name" style="width:60px;" class="score3Class" value="'. $US_score3Name .'" /></td>
		</tr>
		<tr style="display:none;">
			<td align="right">积分单位：</td>
			<td align="center"><input type="text" id="score1Unit" name="score1Unit" style="width:60px;" class="score1Class" value="'. $US_score1Unit .'" /></td>
			<td align="center"><input type="text" id="score2Unit" name="score2Unit" style="width:60px;" class="score2Class" value="'. $US_score2Unit .'" /></td>
			<td align="center"><input type="text" id="score3Unit" name="score3Unit" style="width:60px;" class="score3Class" value="'. $US_score3Unit .'" /></td>
		</tr>
		<tr>
			<td align="right"></td>
			<td align="center" colspan="3" style="padding:15px;" class="font2_2">具体项目积分设置，请到【会员管理】-【会员积分设置】设置.</td>
		</tr>
		</table>
		');

		if (! AppBase::Jud()){
			$skin->PaySoftBox('tabBuy','您尚未购买商业版基础包插件，无法使用该功能。');
			echo('<input type="hidden" id="authState" name="authState" value="false" />');
			
		}elseif ($sysAdminArr['SA_isLan'] == 1 && $sysAdminArr['SA_sendUrlMode'] == 0){
			$skin->PaySoftBox('tabBuy',$skin->LanPaySoft());
			echo('<input type="hidden" id="authState" name="authState" value="false" />');
			
		}else{

			$paraArr = array(
				'US_isRegInvite'		=> $US_isRegInvite ,
				'US_isRegApi'			=> $US_isRegApi ,
				'US_apiRecMode'			=> $US_apiRecMode ,
				'US_isNewsUpImg'		=> $US_isNewsUpImg ,
				'US_newsUpImgSize'		=> $US_newsUpImgSize ,
				'US_newsUpImgOss'		=> $US_newsUpImgOss ,
				'US_newsUpImgOri'		=> $US_newsUpImgOri ,
				'US_isNewsUpFile'		=> $US_isNewsUpFile ,
				'US_newsUpFileSize'		=> $US_newsUpFileSize ,
				'US_newsUpFileOss'		=> $US_newsUpFileOss ,
				'judOssAliyun'			=> AppOssAliyun::Jud() ? 1 : 0,
				'judOssQiniu'			=> AppOssQiniu::Jud() ? 1 : 0,
				'judOssUpyun'			=> AppOssUpyun::Jud() ? 1 : 0,
				'judOssFtp'				=> AppOssFtp::Jud() ? 1 : 0,
				);

			$getWebHtml = OTauthWeb('userSys', 'userSys_V5.00.php', $paraArr);
			if (strpos($getWebHtml,'(OTCMS)') === false){
				$authAlertStr='未知原因';
				if (strpos($getWebHtml,'<!-- noRemote -->') !== false){
					$authAlertStr='无法访问外网';
				}elseif (strpos($getWebHtml,'<!-- noUse -->') !== false){
					$authAlertStr='授权禁用';
				}else{
				
				}
				$getWebHtml = ''.
					$skin->PaySoftBox('tabBuy','因'. $authAlertStr .'而无法使用',true) .
					'<input type="hidden" id="authState" name="authState" value="false" />';
			}else{
				echo('
				<script language="javascript" type="text/javascript">
				$id("buyBox").style.display = "";
				</script>
				');
			}
			echo($getWebHtml);
		}

		echo('
		<div class="tabMenuSubmit"><input type="submit" class="btnBg" value="保 存" /></div>
	</div>

	</form>
	');

}

?>
<?php

if(!defined('OT_ROOT')) {
	exit('Access Denied');
}



class UsersCenter{
	public static $infoNum = 0;
	// 会员中心 首页
	public static function index($userID){
		global $DB,$userSysArr;

		if (AppMoneyPay::Jud()){
			$judMoney = true;
			$moneyWhere = ',UE_money,UE_payMoney';
		}else{
			$judMoney = false;
			$moneyWhere = '';
		}

		$urow = $DB->GetRow('select UE_time,UE_loginTime,UE_loginNum,UE_onlineMinute,UE_loginIP,UE_question,UE_authStr,UE_groupID,UE_isGroupTime,UE_groupTime,UE_username,UE_mail,UE_realname,UE_phone,UE_weixin,UE_qq,UE_note'. $moneyWhere .',UE_score1,UE_score2,UE_score3,UE_state,UE_face from '. OT_dbPref .'users where UE_ID='. $userID);
		$UE_time			= $urow['UE_time'];
		$UE_loginTime		= $urow['UE_loginTime'];
		$UE_loginNum		= $urow['UE_loginNum'];
		$UE_onlineMinute	= $urow['UE_onlineMinute'];
		$UE_loginIP			= $urow['UE_loginIP'];
		$UE_question		= $urow['UE_question'];
		$UE_authStr			= $urow['UE_authStr'];
		$UE_groupID			= $urow['UE_groupID'];
		$UE_isGroupTime		= $urow['UE_isGroupTime'];
		$UE_groupTime		= $urow['UE_groupTime'];
		$UE_username		= $urow['UE_username'];
		$UE_mail			= $urow['UE_mail'];
		$UE_realname		= $urow['UE_realname'];
		$UE_phone			= $urow['UE_phone'];
		$UE_weixin			= $urow['UE_weixin'];
		$UE_qq				= $urow['UE_qq'];
		$UE_note			= $urow['UE_note'];
		if ($judMoney){		
			$UE_money		= $urow['UE_money'];
			$UE_payMoney	= $urow['UE_payMoney'];
		}else{
			$UE_money		= 0;
			$UE_payMoney	= 0;
		}
		$UE_score1			= $urow['UE_score1'];
		$UE_score2			= $urow['UE_score2'];
		$UE_score3			= $urow['UE_score3'];
		$UE_state			= $urow['UE_state'];
		$UE_face			= $urow['UE_face'];
			if ($UE_state == 1){ $stateCN='<span>已审核</span>'; }else{ $stateCN='<span style="color:#ca001d;">未审核</span>'; }

		$retStr = '';
		if (strlen(Str::RegExp($userSysArr['US_announ'],'html')) >= 3){
			$retStr .= '<div class="fullBox">'. $userSysArr['US_announ'] .'</div>';
		}
		$newMessage = '';
	//	if ($UE_mail == ''){
	//		$newMessage .= '<br />你邮箱还未填写（1.增加找回密码方式；2.接收来至网站的最新资讯；）';
	//	}
		if ($UE_question = ''){
			$newMessage .= '<br />你密保还未填写（1.增加找回密码方式）';
		}
		if ($newMessage != ''){
			$retStr .= '<div class="alertBox"><b>提示：</b>'. $newMessage .'</div>';
		}

		$groupTimeStr = '';
		if ($urow['UE_isGroupTime']==1 && strtotime($urow['UE_groupTime'])){
			$groupTimeStr = '&ensp;<span style="font-size:12px;color:#d41b35;" title="到期时间：'. $urow['UE_groupTime'] .'">['. TimeDate::DiffDayCN($urow['UE_groupTime'],'') .']</span>';
		}

		$shimingStr = '';
		if (strpos($UE_authStr,'|实名认证|') !== false){
			// $shimingStr = '&ensp;<span style="font-size:12px;color:#9adbff;">[已实名认证]</span>';
			$shimingStr = '&ensp;<img src="inc_img/usersCenter/shiming1.png" />';
		}

		$authMailStr = '';
		if ($userSysArr['US_isAuthMail'] == 1 && AppMail::Jud()){
			if (strpos($UE_authStr,'|邮箱|') === false){
				// $authMailStr = '&ensp;<a href="?mudi=revInfo&revType=mail" style="font-size:12px;color:#d41b35;" title="点击进入验证">[未验证]</a>';
				$authMailStr = '&ensp;<a href="?mudi=revInfo&revType=mail" style="font-size:12px;color:#d41b35;" title="点击进入验证"><img src="inc_img/usersCenter/yanzheng0.png" /></a>';
			}else{
				// $authMailStr = '&ensp;<span style="font-size:12px;color:#9adbff;">[已验证]</span>';
				$authMailStr = '&ensp;<img src="inc_img/usersCenter/yanzheng1.png" />';
			}
		}

		$authPhoneStr = '';
		if ($userSysArr['US_isAuthPhone'] == 1 && AppPhone::Jud()){
			if (strpos($UE_authStr,'|手机|') === false){
				// $authPhoneStr = '&ensp;<a href="?mudi=revInfo&revType=phone" style="font-size:12px;color:#d41b35;" title="点击进入验证">[未验证]</a>';
				$authPhoneStr = '&ensp;<a href="?mudi=revInfo&revType=phone" style="font-size:12px;color:#d41b35;" title="点击进入验证"><img src="inc_img/usersCenter/yanzheng0.png" /></a>';
			}else{
				// $authPhoneStr = '&ensp;<span style="font-size:12px;color:#9adbff;">[已验证]</span>';
				$authPhoneStr = '&ensp;<img src="inc_img/usersCenter/yanzheng1.png" />';
			}
		}

		$score1Name = $score2Name = $score3Name = '';
		$score1Val = $score2Val = $score3Val = '';
		$levelWhereStr = '';
//		if ($userSysArr['US_isScore1'] == 1){
			$levelWhereStr .= ' and UL_score1<='. $UE_score1;
			$score1Name = $userSysArr['US_score1Name'];
			$score1Val = $UE_score1;
//		}
		if ($userSysArr['US_isScore2'] == 1){
			$levelWhereStr .= ' and UL_score2<='. $UE_score2;
			$score2Name = $userSysArr['US_score2Name'];
			$score2Val = $UE_score2;
		}
		if ($userSysArr['US_isScore3'] == 1){
			$levelWhereStr .= ' and UL_score3<='. $UE_score3;
			$score3Name = $userSysArr['US_score3Name'];
			$score3Val = $UE_score3;
		}

		$levelRow = $DB->GetRow('select UL_num,UL_themeStyle,UL_theme,UL_img from '. OT_dbPref .'userLevel where (1=1)'. $levelWhereStr .' order by UL_num DESC limit 1');
		$levelStr = '['. $levelRow['UL_num'] .'级] <span style="'. $levelRow['UL_themeStyle'] .'">'. $levelRow['UL_theme'] .'</span>';
		/* $retStr .= '
		<tr>
			<td style="padding:3px;" align="right" valign="top">等级：</td>
			<td style="padding:3px;" align="left">
				<br />
				<img src="'. UsersFileDir . $levelRow['UL_img'] .'" />
			</td>
		</tr>
		'. AppBbs::UcBox2('',$UE_face) .'
		</table>
		'; */

		if (AppMoneyPay::Jud()){
			$moneySysArr = Cache::PhpFile('moneySys');
			if ($moneySysArr['MS_userPayMode'] == 1){
				$rightArea = '
					<div class="row1">
						<div class="face"><i class="fa fa-bar-chart"></i></div>
						<div class="big"><small>余额&ensp;&ensp;&ensp;&ensp;（已消费：￥'. floatval($UE_payMoney) .'）</small><h4>￥<span class="counter">'. $UE_money .'</span></h4></div>
						<!-- '. AppUserGroupWork::UcBtn() .' -->
						'. AppMoneyPay::UcPayBtn() .'
					</div>
					<div class="clr"></div>
					';
			}else{
				$rightArea = '
					<div class="row1">
						<div class="face"><i class="fa fa-bar-chart"></i></div>
						<div class="big"><small>消费&ensp;&ensp;&ensp;&ensp;（余额：￥'. floatval($UE_money) .'）</small><h4>￥<span class="counter">'. $UE_payMoney .'</span></h4></div>
						<!-- '. AppUserGroupWork::UcBtn() .' -->
						'. AppMoneyPay::UcPayBtn() .'
					</div>
					<div class="clr"></div>
					';
			}

		}else{
			$rightArea = '
				<div class="row1">
					<div class="face"><i class="fa fa-diamond"></i></div>
					<div class="big"><small>等级</small><h4>'. $levelStr .'</h4></div>
					'. AppMoneyPay::UcPayBtn() .'
				</div>
				<div class="clr"></div>
				';
		}

		$retStr .= '
		<div class="area">
			<div class="areaBox">
				<div class="row1">
					<div class="face"><i class="fa fa-user"></i></div>
					<div class="big">
						<small>用户名</small>'. $shimingStr .'
						<h4>'. $UE_username .'</h4>
					</div>
					<div class="btn"><a href="usersCenter.php?mudi=revInfo">修改资料</a></div>
				</div>
				<div class="clr"></div>
				<div class="row2">
					<div class="one">
						<small>昵称</small>
						<h5>'. $UE_realname .'</h5>
					</div>
					<div class="one">
						<small>会员组</small>'. $groupTimeStr .'
						<h5 style="cursor:pointer;" onclick="document.location = \'?mudi=userGroupRightList\'" title="点击查看会员组权限列表">'. UserGroup::CurrName($UE_groupID) .'</h5>
					</div>
					<div class="one">
						<small>状态</small>
						<h5>'. $stateCN .'</h5>
					</div>
				</div>
				<div class="clr"></div>
				<div class="height20"></div>
				<div class="row2">
					<div class="one">
						<small>手机</small>'. $authPhoneStr .'
						<h6>'. $UE_phone .'</h6>
					</div>
					<div class="one">
						<small>QQ</small>
						<h6>'. $UE_qq .'</h6>
					</div>
					<div class="one">
						<small>邮箱</small>'. $authMailStr .'
						<h6>'. $UE_mail .'</h6>
					</div>
					<div class="clr"></div>
				</div>
				<div class="clr"></div>
			</div>

			<div class="areaBox areaColor">
				'. $rightArea .'
				<div class="row2">
					<div class="one">
						<small>'. $score1Name .'</small>
						<h5>'. $score1Val .'</h5>
					</div>
					<div class="one">
						<small>'. $score2Name .'</small>
						<h5>'. $score2Val .'</h5>
					</div>
					<div class="one">
						<small>'. $score3Name .'</small>
						<h5>'. $score3Val .'</h5>
					</div>
					<div class="clr"></div>
					<div class="height20"></div>
					<div class="one">
						<small>注册时间</small>
						<h6 title="'. $UE_time .'">'. TimeDate::Get('date',$UE_time) .'</h6>
					</div>
					<div class="one">
						<small>最后登录</small>
						<h6 title="'. $UE_loginTime .'">'. TimeDate::Get('date',$UE_loginTime) .'</h6>
					</div>
					<div class="one">
						<small>登录次数</small>
						<h6>'. $UE_loginNum .'</h6>
					</div>
				</div>
				<div class="clr"></div>
			</div>

		</div>
		<div class="clr"></div>

		<hr>

		<div class="area">
			<div class="infoBox">
				<div class="titleBox">
				<h4>
					<i class="fa fa-calculator"></i> 信息统计
					<!-- <small class="moneyBtn">
						<a href="http://otcms.com/"><b class="fa fa-dollar"></b> 查看财务</a>
					</small> -->
				</h4>
				</div>
				'. UsersNews::UcInfo($userID) .'
				'. AppUserGroupWork::UcInfo($userID) .'
				'. AppQiandao::UcInfo($userID) .'
				'. AppMoneyRecord::UcInfo($userID) .'
				'. AppWeixin::UcInfo($userID) .'
			</div>

			<div class="announBox">
				<div class="titleBox">
					<h4><i class="fa fa-bullhorn"></i> 网站公告</h4>
				</div>
				'. self::announBox(UsersCenter::$infoNum>3?UsersCenter::$infoNum:3) .'
			</div>
		</div>
		';

		return $retStr;
	}



	// 会员中心首页-公告
	public static function announBox($num=3,$judGet=true){
		global $DB,$tpl;

		$judCache = false;
		$cacheName = 'usersCenter_announBox';

		if ($retStr = Cache::CheckWebCache($cacheName)){ $judCache = true; }
		if ($judCache == false){
			$retStr = '';
			$todayDate = TimeDate::Get('date');
			$itemexe = $DB->query('select IF1.IF_ID,IF2.IF_time,IF2.IF_theme,IF2.IF_typeStr,IF2.IF_themeStyle,IF2.IF_contentKey,IF2.IF_URL,IF2.IF_isEncUrl,IF2.IF_infoTypeDir,IF2.IF_datetimeDir from (select IF_ID from '. OT_dbPref .'info where IF_state=1 and IF_isAudit=1 and IF_type1ID=-1'. OT_TimeInfoWhereStr .' order by IF_time DESC limit '. $num .') as IF1 inner join '. OT_dbPref .'info as IF2 on IF1.IF_ID=IF2.IF_ID order by IF2.IF_time DESC');
			while ($row = $itemexe->fetch()){
				if (TimeDate::Diff('d',$row['IF_time'],$todayDate) <= 3){ $newStyle = 'color:#ffc7cf;'; }else{ $newStyle = ''; }
				if ($row['IF_URL'] != ''){
					$hrefStr = Url::NewsUrl($row['IF_URL'],$row['IF_isEncUrl'],$row['IF_ID'],$tpl->webPathPart);
				}else{
					$hrefStr = Url::NewsID($row['IF_infoTypeDir'],$row['IF_datetimeDir'],$row['IF_ID']);
				}
				$retStr .= '
				<div class="item">
					<div class="date" style="'. $newStyle .'"><small>'. TimeDate::Get('n月j日 H:i',$row['IF_time']) .'</small></div>
					<div class="cont">
						<p style="word-break:break-all;"><a href="'. $hrefStr .'" style="'. $row['IF_themeStyle'] .'" target="_blank" title="'. Str::MoreReplace($row['IF_theme'],'input') .'">'. $row['IF_theme'] .'</a></p>
						<p class="number" style="word-break:break-all;">'. $row['IF_contentKey'] .'</p>
					</div>
				</div>
				';
			}

			Cache::WriteWebCache($cacheName,$retStr);
		}

		if ($judGet){
			return $retStr;
		}else{
			echo($retStr);
		}
	}



	// 修改信息
	public static function RevInfo($userID){
		global $DB,$userSysArr;

		$revType		= OT::GetStr('revType');
			if ($revType == ''){ $revType='info'; }

		$addiFieldStr='';
		if (AppDashang::Jud()){ $addiFieldStr=',UE_dashangImg1,UE_dashangImg2,UE_dashangImg3'; }

		$urow = $DB->GetRow('select UE_regType,UE_apiStr,UE_username,UE_question,UE_realname,UE_authStr,UE_mail,UE_sex,UE_address,UE_postCode,UE_phone,UE_weixin,UE_alipay,UE_qq,UE_ww,UE_web,UE_note,UE_face,UE_pageNum,UE_pageWapNum'. $addiFieldStr .' from '. OT_dbPref .'users where UE_ID='. $userID);
		$UE_regType		= $urow['UE_regType'];
		$UE_apiStr		= $urow['UE_apiStr'];
		$UE_username	= $urow['UE_username'];
		$UE_question	= $urow['UE_question'];
		$UE_realname	= $urow['UE_realname'];
		$UE_authStr		= $urow['UE_authStr'];
		$UE_mail		= $urow['UE_mail'];
		$UE_sex			= $urow['UE_sex'];
		$UE_address		= $urow['UE_address'];
		$UE_postCode	= $urow['UE_postCode'];
		$UE_phone		= $urow['UE_phone'];
		$UE_weixin		= $urow['UE_weixin'];
		$UE_alipay		= $urow['UE_alipay'];
		$UE_qq			= $urow['UE_qq'];
		$UE_ww			= $urow['UE_ww'];
		$UE_web			= $urow['UE_web'];
		$UE_note		= $urow['UE_note'];
		$UE_face		= $urow['UE_face'];
		$UE_pageNum		= $urow['UE_pageNum'];
		$UE_pageWapNum	= $urow['UE_pageWapNum'];
		if (AppDashang::Jud()){
			$UE_dashangImg1	= $urow['UE_dashangImg1'];
			$UE_dashangImg2	= $urow['UE_dashangImg2'];
			$UE_dashangImg3	= $urow['UE_dashangImg3'];
		}else{
			$UE_dashangImg1	= '';
			$UE_dashangImg2	= '';
			$UE_dashangImg3	= '';
		}

		$retStr = '
		<form id="revForm" name="revForm" method="post" action="usersCenter_deal.php?mudi=rev" onsubmit="return CheckRevForm();" class="form">
		<script language="javascript" type="text/javascript">document.write(\'<input type="hidden" name="backURL" value="\'+ document.location.href +\'" />\')</script>
		<table cellpadding="0" cellspacing="0" class="revInfoBox">
		<tr><td style="width:120px;"></td><td></td></tr>
		<tr>
			<td align="right">修改类型：</td>
			<td>
				<label><input type="radio" id="revType1" name="revType" value="info" onclick="CheckRevInfoType()" '. Is::Checked($revType,'info') .' />个人资料</label>
				&ensp;
				<label><input type="radio" id="revType2" name="revType" value="username" onclick="CheckRevInfoType()" '. Is::Checked($revType,'username') .' />用户名</label>
				&ensp;
				<label><input type="radio" id="revType3" name="revType" value="password" onclick="CheckRevInfoType()" '. Is::Checked($revType,'password') .' />密码</label>
				&ensp;
				<label><input type="radio" id="revType4" name="revType" value="mail" onclick="CheckRevInfoType()" '. Is::Checked($revType,'mail') .' />邮箱</label>
				&ensp;
				<label><input type="radio" id="revType5" name="revType" value="phone" onclick="CheckRevInfoType()" '. Is::Checked($revType,'phone') .' />手机</label>
				&ensp;
				<label><input type="radio" id="revType6" name="revType" value="question" onclick="CheckRevInfoType()" '. Is::Checked($revType,'question') .' />密保</label>
				&ensp;
				<label><input type="radio" id="revType7" name="revType" value="app" onclick="CheckRevInfoType()" '. Is::Checked($revType,'app') .' />插件</label>
			</td>
		</tr>
		</table>

		<div id="infoBox" style="display:none;">
		<input type="hidden" id="userFieldStr" name="userFieldStr" value="'. $userSysArr['US_userFieldStr'] .'" />
		<table cellpadding="0" cellspacing="0" class="revInfoBox">
		<tr><td style="width:120px;"></td><td></td></tr>
		';
		if (strpos($userSysArr['US_userFieldStr'],'|昵称|') !== false){
			if (strlen($UE_realname) > 0 && strpos($userSysArr['US_userFieldStr'],'|昵称不可改|') !== false){
				$retStr .= '
				<tr>
					<td align="right">姓名/昵称：</td>
					<td>'. $UE_realname .'</td>
				</tr>
				';
			}else{
				if (strpos($userSysArr['US_userFieldStr'],'|昵称必填|') !== false){
					$titleSign = '<span style="color:red;">*</span>&ensp;';
					$mustVal = 1;
				}else{
					$titleSign = '';
					$mustVal = 0;
				}
				$retStr .= '
				<tr>
					<td align="right">'. $titleSign .'姓名/昵称：</td>
					<td>
						<input type="hidden" id="realname_is" name="realname_is" value="1" />
						<input type="hidden" id="realname_must" name="realname_must" value="'. $mustVal .'" />
						<input type="text" id="realname" name="realname" class="text" value="'. $UE_realname .'" />
					</td>
				</tr>
				';
			}
		}
		if (strpos($userSysArr['US_userFieldStr'],'|性别|') !== false){
			if (strlen($UE_sex) > 0 && strpos($userSysArr['US_userFieldStr'],'|性别不可改|') !== false){
				$retStr .= '
				<tr>
					<td align="right">性别：</td>
					<td>'. $UE_sex .'</td>
				</tr>
				';
			}else{
				if (strpos($userSysArr['US_userFieldStr'],'|性别必填|') !== false){
					$titleSign = '<span style="color:red;">*</span>&ensp;';
					$mustVal = 1;
				}else{
					$titleSign = '';
					$mustVal = 0;
				}
				$retStr .= '
				<tr>
					<td align="right">'. $titleSign .'性别：</td>
					<td>
						<input type="hidden" id="sex_is" name="sex_is" value="1" />
						<input type="hidden" id="sex_must" name="sex_must" value="'. $mustVal .'" />
						<label><input type="radio" id="sex1" name="sex" value="男" '. Is::Checked($UE_sex,'男') .' />男</label>
						&ensp;&ensp;
						<label><input type="radio" id="sex0" name="sex" value="女" '. Is::Checked($UE_sex,'女') .' />女</label>
					</td>
				</tr>
				';
			}
		}
		if (strpos($userSysArr['US_userFieldStr'],'|收货地址|') !== false){
			if (strlen($UE_address) > 0 && strpos($userSysArr['US_userFieldStr'],'|收货地址不可改|') !== false){
				$retStr .= '
				<tr>
					<td align="right">收货地址：</td>
					<td>'. $UE_address .'</td>
				</tr>
				';
			}else{
				if (strpos($userSysArr['US_userFieldStr'],'|收货地址必填|') !== false){
					$titleSign = '<span style="color:red;">*</span>&ensp;';
					$mustVal = 1;
				}else{
					$titleSign = '';
					$mustVal = 0;
				}
				$retStr .= '
				<tr>
					<td align="right">'. $titleSign .'收货地址：</td>
					<td>
						<input type="hidden" id="address_is" name="address_is" value="1" />
						<input type="hidden" id="address_must" name="address_must" value="'. $mustVal .'" />
						<input type="text" id="address" name="address" class="text" value="'. $UE_address .'" style="width:380px;" />
					</td>
				</tr>
				';
			}
		}
		if (strpos($userSysArr['US_userFieldStr'],'|邮编|') !== false){
			if (strlen($UE_postCode) > 0 && strpos($userSysArr['US_userFieldStr'],'|邮编不可改|') !== false){
				$retStr .= '
				<tr>
					<td align="right">邮编：</td>
					<td>'. $UE_postCode .'</td>
				</tr>
				';
			}else{
				if (strpos($userSysArr['US_userFieldStr'],'|邮编必填|') !== false){
					$titleSign = '<span style="color:red;">*</span>&ensp;';
					$mustVal = 1;
				}else{
					$titleSign = '';
					$mustVal = 0;
				}
				$retStr .= '
				<tr>
					<td align="right">'. $titleSign .'邮编：</td>
					<td>
						<input type="hidden" id="postCode_is" name="postCode_is" value="1" />
						<input type="hidden" id="postCode_must" name="postCode_must" value="'. $mustVal .'" />
						<input type="text" id="postCode" name="postCode" class="text" value="'. $UE_postCode .'" style="width:380px;" />
					</td>
				</tr>
				';
			}
		}
		if (strpos($userSysArr['US_userFieldStr'],'|QQ|') !== false){
			if (strlen($UE_qq) > 0 && strpos($userSysArr['US_userFieldStr'],'|QQ不可改|') !== false){
				$retStr .= '
				<tr>
					<td align="right">QQ：</td>
					<td>'. $UE_qq .'</td>
				</tr>
				';
			}else{
				if (strpos($userSysArr['US_userFieldStr'],'|QQ必填|') !== false){
					$titleSign = '<span style="color:red;">*</span>&ensp;';
					$mustVal = 1;
				}else{
					$titleSign = '';
					$mustVal = 0;
				}
				$retStr .= '
				<tr>
					<td align="right">'. $titleSign .'QQ：</td>
					<td>
						<input type="hidden" id="qq_is" name="qq_is" value="1" />
						<input type="hidden" id="qq_must" name="qq_must" value="'. $mustVal .'" />
						<input type="text" id="qq" name="qq" class="text" value="'. $UE_qq .'" style="width:380px;" />
						<span class="font2_2">&ensp;如多个QQ用逗号“,”隔开</span>
					</td>
				</tr>
				';
			}
		}
		if (strpos($userSysArr['US_userFieldStr'],'|微信|') !== false){
			if (strlen($UE_weixin) > 0 && strpos($userSysArr['US_userFieldStr'],'|微信不可改|') !== false){
				$retStr .= '
				<tr>
					<td align="right">微信：</td>
					<td>'. $UE_weixin .'</td>
				</tr>
				';
			}else{
				if (strpos($userSysArr['US_userFieldStr'],'|微信必填|') !== false){
					$titleSign = '<span style="color:red;">*</span>&ensp;';
					$mustVal = 1;
				}else{
					$titleSign = '';
					$mustVal = 0;
				}
				$retStr .= '
				<tr>
					<td align="right">'. $titleSign .'微信：</td>
					<td>
						<input type="hidden" id="weixin_is" name="weixin_is" value="1" />
						<input type="hidden" id="weixin_must" name="weixin_must" value="'. $mustVal .'" />
						<input type="text" id="weixin" name="weixin" class="text" value="'. $UE_weixin .'" style="width:380px;" />
					</td>
				</tr>
				';
			}
		}
		if (strpos($userSysArr['US_userFieldStr'],'|支付宝|') !== false){
			if (strlen($UE_alipay) > 0 && strpos($userSysArr['US_userFieldStr'],'|支付宝不可改|') !== false){
				$retStr .= '
				<tr>
					<td align="right">支付宝：</td>
					<td>'. $UE_alipay .'</td>
				</tr>
				';
			}else{
				if (strpos($userSysArr['US_userFieldStr'],'|支付宝必填|') !== false){
					$titleSign = '<span style="color:red;">*</span>&ensp;';
					$mustVal = 1;
				}else{
					$titleSign = '';
					$mustVal = 0;
				}
				$retStr .= '
				<tr>
					<td align="right">'. $titleSign .'支付宝：</td>
					<td>
						<input type="hidden" id="alipay_is" name="alipay_is" value="1" />
						<input type="hidden" id="alipay_must" name="alipay_must" value="'. $mustVal .'" />
						<input type="text" id="alipay" name="alipay" class="text" value="'. $UE_alipay .'" style="width:380px;" />
					</td>
				</tr>
				';
			}
		}
		if (strpos($userSysArr['US_userFieldStr'],'|旺旺|') !== false){
			if (strlen($UE_ww) > 0 && strpos($userSysArr['US_userFieldStr'],'|旺旺不可改|') !== false){
				$retStr .= '
				<tr>
					<td align="right">旺旺：</td>
					<td>'. $UE_ww .'</td>
				</tr>
				';
			}else{
				if (strpos($userSysArr['US_userFieldStr'],'|旺旺必填|') !== false){
					$titleSign = '<span style="color:red;">*</span>&ensp;';
					$mustVal = 1;
				}else{
					$titleSign = '';
					$mustVal = 0;
				}
				$retStr .= '
				<tr>
					<td align="right">'. $titleSign .'旺旺：</td>
					<td>
						<input type="hidden" id="ww_is" name="ww_is" value="1" />
						<input type="hidden" id="ww_must" name="ww_must" value="'. $mustVal .'" />
						<input type="text" id="ww" name="ww" class="text" value="'. $UE_ww .'" style="width:380px;" />
					</td>
				</tr>
				';
			}
		}
		if (strpos($userSysArr['US_userFieldStr'],'|个人主页|') !== false){
			if (strlen($UE_web) > 0 && strpos($userSysArr['US_userFieldStr'],'|个人主页不可改|') !== false){
				$retStr .= '
				<tr>
					<td align="right">个人主页：</td>
					<td>'. $UE_web .'</td>
				</tr>
				';
			}else{
				if (strpos($userSysArr['US_userFieldStr'],'|个人主页必填|') !== false){
					$titleSign = '<span style="color:red;">*</span>&ensp;';
					$mustVal = 1;
				}else{
					$titleSign = '';
					$mustVal = 0;
				}
				$retStr .= '
				<tr>
					<td align="right">'. $titleSign .'个人主页：</td>
					<td>
						<input type="hidden" id="web_is" name="web_is" value="1" />
						<input type="hidden" id="web_must" name="web_must" value="'. $mustVal .'" />
						<input type="text" id="web" name="web" class="text" value="'. $UE_web .'" style="width:380px;" />
					</td>
				</tr>
				';
			}
		}
		if (strpos($userSysArr['US_userFieldStr'],'|备注|') !== false){
			if (strlen($UE_note) > 0 && strpos($userSysArr['US_userFieldStr'],'|备注不可改|') !== false){
				$retStr .= '
				<tr>
					<td align="right">备注：</td>
					<td>'. $UE_note .'</td>
				</tr>
				';
			}else{
				if (strpos($userSysArr['US_userFieldStr'],'|备注必填|') !== false){
					$titleSign = '<span style="color:red;">*</span>&ensp;';
					$mustVal = 1;
				}else{
					$titleSign = '';
					$mustVal = 0;
				}
				$retStr .= '
				<tr>
					<td align="right" valign="top" style="padding-top:6px;">'. $titleSign .'备注：</td>
					<td>
						<input type="hidden" id="note_is" name="note_is" value="1" />
						<input type="hidden" id="note_must" name="note_must" value="'. $mustVal .'" />
						<textarea id="note" name="note" cols="40" rows="4" style="width:380px;">'. $UE_note .'</textarea>
						<div style="padding-top:6px;color:red;">提醒：遇到不可修改信息，又需要修改的，请联系管理员。</div>
					</td>
				</tr>
				';
			}
		}
		$retStr .= '
		<tr>
			<td align="right">每页数量：</td>
			<td>
				<input type="text" id="pageNum" name="pageNum" class="text" value="'. $UE_pageNum .'" style="width:35px;" />
			</td>
		</tr>
		<!-- <tr>
			<td align="right">手机版每页数量：</td>
			<td>
				<input type="text" id="pageWapNum" name="pageWapNum" class="text" value="'. $UE_pageWapNum .'" style="width:35px;" />
			</td>
		</tr> -->
		</table>
		</div>

		<div id="usernameBox" style="display:none;">
		<table cellpadding="0" cellspacing="0" class="revInfoBox">
		<tr><td style="width:120px;"></td><td></td></tr>
		<tr>
			<td align="right">原用户名：</td>
			<td>'. $UE_username .'</td>
		</tr>
		';
		if (($UE_regType=='qq' && substr($UE_username,0,3)=='qq_') || ($UE_regType=='weibo' && substr($UE_username,0,6)=='weibo_') || ($UE_regType=='weixin' && substr($UE_username,0,7)=='weixin_') || ($UE_regType=='wxmp' && substr($UE_username,0,5)=='wxmp_') || ($UE_regType=='taobao' && substr($UE_username,0,7)=='taobao_') || ($UE_regType=='alipay' && substr($UE_username,0,7)=='alipay_')){
			$retStr .= '
			<tr>
				<td align="right">新用户名：</td>
				<td>
					<input type="text" id="newUsername" name="newUsername" class="text" maxlength="32" onblur="CheckUserName(this.value)" />
					<span id="usernameIsOk"></span>
					长度4~16字节，仅允许 数字、英文、汉字、下划线
					<div id="usernameStr"></div>
					<input type="hidden" id="isRevUsername" name="isRevUsername" value="1" />
				</td>
			</tr>
			';
		}else{
			if ($UE_regType != 'web'){
				$newUsernameAlert = '您已修改过用户名，不能在修改了。';
			}else{
				$newUsernameAlert = '您是非快捷登录注册的，不允许修改用户名。';
			}
			$retStr .= '
			<tr>
				<td align="right">新用户名：</td>
				<td>'. $newUsernameAlert .'<input type="hidden" id="isRevUsername" name="isRevUsername" value="0" /></td>
			</tr>
			';
		}
		$retStr .= '
		</table>
		</div>

		<div id="passwordBox" style="display:none;">
		<table cellpadding="0" cellspacing="0" class="revInfoBox">
		<tr><td style="width:120px;"></td><td></td></tr>
		<tr>
			<td align="right">原密码：</td>
			<td>
			';
			if (strpos($UE_apiStr,'|web:::') !== false){
				$retStr .= '<input type="password" id="userpwdOld" name="userpwdOld" class="text" maxlength="32" />';
			}else{
				$retStr .= '
				<div style=" padding-top:5px;">未设置过，直接设置新密码</div>
				<input type="hidden" id="userpwdOld" name="userpwdOld" class="text" maxlength="32" value="no" />
				';
			}
			$retStr .= '
			</td>
		</tr>
		<tr>
			<td align="right">新密码：</td>
			<td>
				<input type="password" id="userpwd" name="userpwd" class="text" maxlength="32" />
				<span id="userpwdIsOk"></span>
				长度6-32位
				<div id="userpwdStr" class="font2_2"></div>
			</td>
		</tr>
		<tr>
			<td align="right">确认密码：</td>
			<td>
				<input type="password" id="userpwd2" name="userpwd2" class="text" maxlength="32" />
				<span id="userpwd2IsOk"></span>
				<div id="userpwd2Str" class="font2_2"></div>
			</td>
		</tr>
		</table>
		</div>

		<div id="mailBox" style="display:none;">
		<table cellpadding="0" cellspacing="0" class="revInfoBox">
		<tr><td style="width:120px;"></td><td></td></tr>
		';
		$judAppMail = AppMail::Jud();
		if ($userSysArr['US_isLockMail'] == 1 && strpos($UE_authStr,'|邮箱|') !== false && $judAppMail){
			$retStr .= '
			<tr>
				<td align="right" valign="top" style="padding-top:6px;">当前邮箱：</td>
				<td>'. $UE_mail .'<div style="color:red;margin-top:8px;">邮箱验证通过后禁止用户修改，如要修改请联系网站管理员处理。</div></td>
			</tr>
			';
		}else{
			if ($userSysArr['US_isAuthMail'] == 1 && strpos($UE_authStr,'|邮箱|') === false && $judAppMail){
				$retStr .= '
				<tr>
					<td align="right">当前邮箱：</td>
					<td>
						<input type="hidden" id="isAuthMail" name="isAuthMail" value="1" />
						<input type="text" id="mail" name="mail" class="text" onchange="CheckMail()" value="'. $UE_mail .'" />
						<input type="button" id="sendMail" value="发送邮件验证码" style="height:29px;" onclick=\'SendMailCode(this.id,"mail","check","");\' />
						<span id="mailIsOk"></span>
						<div id="mailStr" class="font2_2"></div>
					</td>
				</tr>
				<tr>
					<td align="right">邮件验证码：</td>
					<td><input type="text" id="mailCode" name="mailCode" class="text" /></td>
				</tr>
				';
			}else{
				$currBtnStr = $currCodeStr = $newBtnStr = $newCodeStr = '';
				if ($userSysArr['US_isAuthMail'] == 1 && strpos($UE_authStr,'|邮箱|') !== false && $judAppMail){
					$currBtnStr = '<input type="hidden" id="oldMail" name="oldMail" value="'. $UE_mail .'" /><input type="button" id="sendOldMail" value="发送邮件验证码" onclick=\'SendMailCode(this.id,"oldMail","check","");\' />';
					$currCodeStr = '
						<tr>
							<td align="right">当前邮件验证码：</td>
							<td><input type="text" id="mailOldCode" name="mailOldCode" class="text" /></td>
						</tr>
						';
					$newBtnStr = '<input type="button" id="sendMail" value="发送邮件验证码" style="height:29px;" onclick=\'SendMailCode(this.id,"mail","rev","");\' />';
					$newCodeStr = '
						<tr>
							<td align="right">新邮件验证码：</td>
							<td><input type="text" id="mailCode" name="mailCode" class="text" /></td>
						</tr>
						';
				}

				$retStr .= '
				<tr>
					<td align="right">当前邮箱：</td>
					<td>
						'. $UE_mail .'&ensp;'. $currBtnStr .'
					</td>
				</tr>
				'. $currCodeStr .'
				<tr>
					<td align="right">新邮箱：</td>
					<td>
						<input type="hidden" id="isAuthMail" name="isAuthMail" value="0" />
						<input type="text" id="mail" name="mail" class="text" onchange="CheckMail()" />
						'. $newBtnStr .'
						<span id="mailIsOk"></span>
						<div id="mailStr" class="font2_2"></div>
					</td>
				</tr>
				'. $newCodeStr .'
				';
			}
			$retStr .= '
			<tr>
				<td align="right">会员登录密码：</td>
				<td>
					<input type="password" id="mailPwd" name="mailPwd" class="text" maxlength="32" />
					<span class="font2_2">&ensp;以确保修改邮箱的是该会员</span>
				</td>
			</tr>
			';
		}
		$retStr .= '
		</table>
		</div>

		<div id="phoneBox" style="display:none;">
		<table cellpadding="0" cellspacing="0" class="revInfoBox">
		<tr><td style="width:120px;"></td><td></td></tr>
		';
		$judAppPhone = AppPhone::Jud();
		if ($userSysArr['US_isLockPhone'] == 1 && strpos($UE_authStr,'|手机|') !== false && $judAppPhone){
			$retStr .= '
			<tr>
				<td align="right" valign="top" style="padding-top:6px;">当前手机：</td>
				<td>'. $UE_phone .'<div style="color:red;margin-top:8px;">手机验证通过后禁止用户修改，如要修改请联系网站管理员处理。</div></td>
			</tr>
			';
		}else{
			if ($userSysArr['US_isAuthPhone'] == 1 && strpos($UE_authStr,'|手机|') === false && $judAppPhone){
				$retStr .= '
				<tr>
					<td align="right">当前手机：</td>
					<td>
						<input type="hidden" id="isAuthPhone" name="isAuthPhone" value="1" />
						<input type="text" id="phone" name="phone" class="text" onchange="CheckPhone()" value="'. $UE_phone .'" />
						<input type="button" id="sendPhone" value="发送短信验证码" style="height:29px;" onclick=\'SendPhoneCode(this.id,"phone","check","");\' />
						<span id="phoneIsOk"></span>
						<div id="phoneStr" class="font2_2"></div>
					</td>
				</tr>
				<tr>
					<td align="right">短信验证码：</td>
					<td><input type="text" id="phoneCode" name="phoneCode" class="text" /></td>
				</tr>
				';
			}else{
				$currBtnStr = $currCodeStr = $newBtnStr = $newCodeStr = '';
				if ($userSysArr['US_isAuthPhone'] == 1 && strpos($UE_authStr,'|手机|') !== false && $judAppPhone){
					$currBtnStr = '<input type="hidden" id="oldPhone" name="oldPhone" value="'. $UE_phone .'" /><input type="button" id="sendOldPhone" value="发送短信验证码" onclick=\'SendPhoneCode(this.id,"oldPhone","check","");\' />';
					$currCodeStr = '
						<tr>
							<td align="right">当前短信验证码：</td>
							<td><input type="text" id="phoneOldCode" name="phoneOldCode" class="text" /></td>
						</tr>
						';
					$newBtnStr = '<input type="button" id="sendPhone" value="发送短信验证码" style="height:29px;" onclick=\'SendPhoneCode(this.id,"phone","rev","");\' />';
					$newCodeStr = '
						<tr>
							<td align="right">新短信验证码：</td>
							<td><input type="text" id="phoneCode" name="phoneCode" class="text" /></td>
						</tr>
						';
				}

				$retStr .= '
				<tr>
					<td align="right">当前手机：</td>
					<td>
						'. $UE_phone .'&ensp;'. $currBtnStr .'
					</td>
				</tr>
				'. $currCodeStr .'
				<tr>
					<td align="right">新手机：</td>
					<td>
						<input type="hidden" id="isAuthPhone" name="isAuthPhone" value="0" />
						<input type="text" id="phone" name="phone" class="text" onchange="CheckPhone()" />
						'. $newBtnStr .'
						<span id="phoneIsOk"></span>
						<div id="phoneStr" class="font2_2"></div>
					</td>
				</tr>
				'. $newCodeStr .'
				';
			}
			$retStr .= '
			<tr>
				<td align="right">会员登录密码：</td>
				<td>
					<input type="password" id="phonePwd" name="phonePwd" class="text" maxlength="32" />
					<span class="font2_2">&ensp;以确保修改手机的是该会员</span>
				</td>
			</tr>
			';
		}
		$retStr .= '
		</table>
		</div>

		<div id="questionBox" style="display:none;">
		<table cellpadding="0" cellspacing="0" class="revInfoBox">
		<tr><td style="width:120px;"></td><td></td></tr>
		';
		if ($UE_question != ''){
			$retStr .= '
			<tr>
				<td align="right">旧密保问题：</td>
				<td style="line-height:24px;">
					'. $UE_question .'
				</td>
			</tr>
			<tr>
				<td align="right">旧密保答案：</td>
				<td>
					<input type="text" id="answerOld" name="answerOld" class="text" />
				</td>
			</tr>
			';
		}
		$retStr .= '
		<tr>
			<td align="right">新密保问题：</td>
			<td>
				<input type="text" id="question" name="question" class="text" onchange="CheckQuestion()" />
				<span id="questionIsOk"></span>
				长度2~50字节，仅允许 数字、英文、汉字、下划线
				<div id="questionStr" class="font2_2"></div>
			</td>
		</tr>
		<tr>
			<td align="right">新密保答案：</td>
			<td>
				<input type="text" id="answer" name="answer" class="text" onchange="CheckAnswer()" />
				<span id="answerIsOk"></span>
				长度2~50字节
				<div id="answerStr" class="font2_2"></div>
			</td>
		</tr>
		</table>
		</div>

		<div id="appBox" style="display:none;">
		<table cellpadding="0" cellspacing="0" class="revInfoBox">
		<tr><td style="width:120px;"></td><td></td></tr>
		<input type="hidden" id="imgDir" name="imgDir" value="'. UsersFileDir .'" />
		'. AppDashang::UcRevInfoTrBox($UE_dashangImg1, $UE_dashangImg2, $UE_dashangImg3) .'
		'. AppBbs::UcBox1($UE_face) .'
		</table>
		</div>

		<table cellpadding="0" cellspacing="0" class="revInfoBox">
		<tr>
			<td style="width:120px;"></td>
			<td style="padding-top:10px;">
				<input type="submit" value=" 提 交 " class="btn subBtn" />
				&ensp;&ensp;&ensp;&ensp;
				<input type="reset" value=" 重 置 " class="btn defBtn" />
			</td>
		</tr>
		</table>
		</form>

		<script language="javascript" type="text/javascript">
		CheckRevInfoType();
		</script>
		';

		return $retStr;
	}


	// 会员和会员组
	public static function UserAndGroup($userID,$mode='pc'){
		global $DB;

		$uRow = $DB->GetRow('select UE_username,UE_groupID,UE_isGroupTime,UE_groupTime,UE_money,UE_score1,UE_score2,UE_score3,UE_state,UE_workTime from '. OT_dbPref .'users where UE_ID='. $userID);

		if ($uRow['UE_state'] == 1){ $stateCN='<span>已审核</span>'; }else{ $stateCN='<span style="color:#ca001d;">未审核</span>'; }

		$groupTimeStr = '';
		if ($uRow['UE_isGroupTime']==1 && strtotime($uRow['UE_groupTime'])){
			$groupTimeStr = '&ensp;<span style="font-size:12px;color:#d41b35;" title="到期时间：'. $uRow['UE_groupTime'] .'">['. TimeDate::DiffDayCN($uRow['UE_groupTime'],'') .']</span>';
		}

		$userSysArr = Cache::PhpFile('userSys');
		$appSysArr = Cache::PhpFile('appSys');

		$scoreList = '';
		if (AppMoneyPay::Jud()){
			$scoreList .= '金额：<span style="font-weight:bold;color:red;">'. floatval($uRow['UE_money']) .'</span> 元 ，';
		}
		// if ($userSysArr['US_isScore1'] == 1){
			$scoreList .= $userSysArr['US_score1Name'] .'：<span style="font-weight:bold;color:green;">'. $uRow['UE_score1'] .'</span> ，';
		// }
		if ($userSysArr['US_isScore2'] == 1){
			$scoreList .= $userSysArr['US_score2Name'] .'：<span style="font-weight:bold;color:green;">'. $uRow['UE_score2'] .'</span> ，';
		}
		if ($userSysArr['US_isScore3'] == 1){
			$scoreList .= $userSysArr['US_score3Name'] .'：<span style="font-weight:bold;color:green;">'. $uRow['UE_score3'] .'</span> ，';
		}

		$retStr = '	<script language="javascript" type="text/javascript" src="js/userAndGroup.js?v='. OT_VERSION .'"></script>';
		if ($mode == 'wap'){

		}else{
			$retStr .= '
			<div style="padding:10px 5px 20px 5px;">
				用户名：<span style="font-weight:bold;">'. $uRow['UE_username'] .'</span> ，
				会员组：<span style="font-weight:bold;color:blue;">'. UserGroup::CurrName($uRow['UE_groupID']) . $groupTimeStr .'</span> ，
				'. $scoreList .'
				状态：<span style="font-weight:bold;color:red;">'. $stateCN .'</span>
			</div>
			';

			if ($uRow['UE_state'] == 0){
				$retStr .= AppUserState1::UcArea($userID,$appSysArr,$userSysArr);	// 会员转正
			}

			$retStr .= AppQiandao::UcArea($userID,$userSysArr);				// 签到
			$retStr .= AppUserGroupWork::UcArea($userID,$uRow,$userSysArr);	// 领工资
			$retStr .= AppUserGroup::UcArea($userID,$uRow,$userSysArr);		// 会员组开通/更换/升级
			$retStr .= AppMoneyPay::UcArea($userID,$userSysArr);			// 金额兑换积分

		}

		return $retStr;
	}


	// 登录日志
	public static function LogWeb($userID){
		global $DB,$userRow;

		$retStr = '
		<table cellpadding="0" cellspacing="0" border="0" class="tabList1">
		<thead>
		<tr>
			<td width="4%" align="center">编号</td>
			<td width="14%" align="center">发生时间</td>
			<td width="16%" align="center">IP</td>
			<td width="41%" align="center">事件</td>
		</tr>
		</thead>
		';
		$pageSize	= $userRow['UE_pageNum'];	// 每页条数
		$page		= OT::GetInt('page');
		$showRow=$DB->GetLimit('select * from '. OT_dbPref .'userLog where UL_userID='. $userID .' order by UL_ID DESC',$pageSize,$page);
		if (! $showRow){
			$retStr .= '</table><center class="font1_1 padd8">暂无记录</center>';
			return $retStr;
		}else{
			$recordCount=$DB->GetRowCount();
			$pageCount=ceil($recordCount/$pageSize);
			if ($page < 1 || $page > $pageCount){$page=1;}

			$retStr .= '<tbody class="tabBody">';
			$number=1+($page-1)*$pageSize;
			$rowCount = count($showRow);
			for ($i=0; $i<$rowCount; $i++){
				if ($i % 2 == 1){ $bgcolor='class="tabColorTr"'; }else{ $bgcolor=''; }
				$retStr .= '
				<tr id="data'. $showRow[$i]['UL_ID'] .'" '. $bgcolor .'>
					<td align="center">'. $number .'</td>
					<td align="center">'. $showRow[$i]['UL_time'] .'</td>
					<td align="center">'. $showRow[$i]['UL_ip'] .'<div style="color:#c9c9c9;">'. $showRow[$i]['UL_ipCN'] .'</div></td>
					<td align="left">'. $showRow[$i]['UL_note'] .'</td>
				</tr>
				';
				$number ++;
			}
			$retStr .= '
			</tbody>
			</table>

			<table align="center" style="margin-top:2px;"><tr><td>
			'. Nav::Show('',$pageCount,$pageSize,$recordCount,'img','pageNum','get') .'
			</td></tr></table>
			';
		}
		unset($showRow);

		return $retStr;
	}

}
?>
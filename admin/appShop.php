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

$skin->WebTop('应用推送升级系统');


echo('
<script language="javascript" type="text/javascript" src="js/appShop.js?v='. OT_VERSION .'"></script>
');





switch($mudi){
	case 'update':
		updateWeb();
		break;

	default :
		$MB->IsAdminRight('alertBack');
		defWeb();
		break;

}

$skin->WebBottom();

$MB->Close();
$DB->Close();





function defWeb(){
	global $DB,$mudi,$skin,$sysAdminArr,$otcmsUrl1;

	$dataMode	= OT::GetStr('dataMode');
	$page		= OT::GetInt('page');
	$refContent	= OT::GetRegExpStr('refContent','sql');
		if (! in_array($dataMode,array('app','tpl','buy','user','user2'))){ $dataMode = 'buy'; }
		if ($refContent == '请输入插件关键字'){ $refContent = ''; }

	$paraArr = OTauthArr();
	$appUrl = 'http://app.otcms.com/';

	$userMarkStr = '';
	if ($dataMode == 'user' && $sysAdminArr['SA_checkUrlMode'] != 0){
		$userMarkStr = '<span style="color:red;">['. Adm::UrlWebMode($sysAdminArr['SA_checkUrlMode']) .']</span>';
	}
	echo('
	<div class="tabMenu" rel="no">
	<div style="float:right;padding:6px 5px 0 0;"><input type="button" style="color:blue;" value="更新已购插件信息" onclick=\'DataDeal.location.href="appShop_deal.php?mudi=getInfo&mode=back&backURL="+ encodeURIComponent(document.location.href);\' /></div>
	<ul>
	   <li onclick=\'document.location.href="?dataMode=app";return false;\' '. ($dataMode=='app' ? 'class="selected"' : '') .'>功能插件</li>
	   <li onclick=\'document.location.href="?dataMode=tpl";return false;\' '. ($dataMode=='tpl' ? 'class="selected"' : '') .'>模板插件</li>
	   <li onclick=\'document.location.href="?dataMode=buy";return false;\' '. ($dataMode=='buy' ? 'class="selected"' : '') .'>已购买的插件</li>
	   <li onclick=\'document.location.href="?dataMode=user";return false;\' '. ($dataMode=='user' ? 'class="selected"' : '') .'>个人中心'. $userMarkStr .'</li>
	   <li onclick=\'document.location.href="?dataMode=user2";return false;\' '. ($dataMode=='user2' ? 'class="selected"' : '') .'>个人中心(用https协议专用)</li>
	</ul>
	<div style="padding:10px 0 0 0;font-size:14px;color:red;">&ensp;&ensp;&ensp;&ensp;<a href="http://otcms.com/news/8193.html" target="_blank" style="color:red;font-size:14px;">《插件平台使用说明》</a></div>
	</div>
	');

	if (in_array($dataMode,array('app','tpl'))){
		$payArr = array(0);
		$payexe = $DB->query('select PS_appID from '. OT_dbPref .'paySoft where PS_state=1');
		while ($row = $payexe->fetch()){
			$payArr[] = $row['PS_appID'];
		}
		unset($payexe);

		$payStr = implode(',',$payArr);
	}

	// 功能插件
	if ($dataMode=='app'){
		$shopAppContent = ReqUrl::UseAuto($sysAdminArr['SA_getUrlMode'], 'POST', $appUrl .'api.php?mudi=app&dataMode='. $dataMode .'&phpVer='. PHP_VERSION .'&page='. $page .'&isRef=1&refContent='. urlencode($refContent), 'UTF-8', $paraArr, 'note');
			if (strpos($shopAppContent,'(OTCMS)') === false){
				$shopAppContent = '数据获取错误（'. $shopAppContent .'）';
			}

		echo('
		<div id="tabAppShop">
		   '. $shopAppContent .'
		</div>
		<script language="javascript" type="text/javascript">
		CheckPayBtn("'. $payStr .'");
		</script>
		');
	

	// 模板插件
	}elseif ($dataMode=='tpl'){
		$shopTplContent = ReqUrl::UseAuto($sysAdminArr['SA_getUrlMode'], 'POST', $appUrl .'api.php?mudi=tpl&dataMode='. $dataMode .'&phpVer='. PHP_VERSION .'&page='. $page .'&isRef=1&refContent='. urlencode($refContent), 'UTF-8', $paraArr, 'note');
			if (strpos($shopTplContent,'(OTCMS)') === false){
				$shopTplContent = '数据获取错误（'. $shopTplContent .'）';
			}

		echo('
		<div id="tabTplShop">
		   '. $shopTplContent .'
		</div>
		<script language="javascript" type="text/javascript">
		CheckPayBtn("'. $payStr .'");
		</script>
		');

	
	// 个人中心
	}elseif ($dataMode=='user'){
		echo('
		<div id="tabUser">
		   <iframe id="otcmsUser" name="otcmsUser" frameborder="0" allowTransparency="true" scrolling="auto" style="width:100%;height:780px;" src="'. $otcmsUrl1 .'shopUsers.php?url='. urlencode(GetUrl::CurrDir()) .'&domain='. urlencode(GetUrl::Domain(GetUrl::Main())) .'&OT_UPDATETIME='. OT_UPDATETIME .'&phpVer='. PHP_VERSION .'&username='. $sysAdminArr['SA_username'] .'&softID='. $sysAdminArr['SA_softID'] .'&softCode='. $sysAdminArr['SA_softCode'] .'&domainID='. $sysAdminArr['SA_domainID'] .'&domainCode='. $sysAdminArr['SA_domainCode'] .'"></iframe>
		</div>
		');

	// 个人中心（https协议）
	}elseif ($dataMode=='user2'){
		$otcmsUrl1 = 'https://check2.otcms.com/';
		echo('
		<div id="tabUser">
		   <iframe id="otcmsUser2" name="otcmsUser2" frameborder="0" allowTransparency="true" scrolling="auto" style="width:100%;height:780px;" src="'. $otcmsUrl1 .'shopUsers.php?url='. urlencode(GetUrl::CurrDir()) .'&domain='. urlencode(GetUrl::Domain(GetUrl::Main())) .'&OT_UPDATETIME='. OT_UPDATETIME .'&phpVer='. PHP_VERSION .'&username='. $sysAdminArr['SA_username'] .'&softID='. $sysAdminArr['SA_softID'] .'&softCode='. $sysAdminArr['SA_softCode'] .'&domainID='. $sysAdminArr['SA_domainID'] .'&domainCode='. $sysAdminArr['SA_domainCode'] .'&phpVer='. PHP_VERSION .'"></iframe>
		</div>
		');


	// 已购买的插件
	}else{
		echo('
		<div id="tabBuy">
			<div style="font-size:14px;font-weight:bold; background:#ffe5e5; border:1px solid #ff0000; color:#000000; padding:8px 12px; -moz-border-radius:3px; -webkit-border-radius:3px; border-radius:3px; margin:10px 10px 16px 10px; text-align:left; line-height:1.4;">
				<a href="http://www.yuntaiidc.com/vps.html" style="color:blue;font-size:14px;font-weight:bold;" target="_blank">网钛云服务器和独立服务器</a>季付以上，即赠送20%~35%的金额用于免费使用PHP插件，<a href="http://www.yuntaiidc.com/info/2017/9150.html" style="color:red;font-size:14px;font-weight:bold;" target="_blank">点击此处查看教程&gt;&gt;&gt;</a>
			</div>
			<div class="clr"></div>

			<div id="loginBox" style="font-size:14px;padding:0 0 12px 8px;">
				<form id="loginForm" name="loginForm" method="post" action="appShop_deal.php?mudi=getAuth" onsubmit="return CheckLoginForm()">
				<input type="hidden" id="pwdMode" name="pwdMode" value="" />
				<input type="hidden" id="pwdKey" name="pwdKey" value="" />
				<input type="hidden" id="pwdEnc" name="pwdEnc" value="" />
				<input type="hidden" id="softID" name="softID" value="'. $sysAdminArr['SA_softID'] .'" />
				<input type="hidden" id="softCode" name="softCode" value="'. $sysAdminArr['SA_softCode'] .'" />
				<input type="hidden" id="domainID" name="domainID" value="'. $sysAdminArr['SA_domainID'] .'" />
				<input type="hidden" id="domainCode" name="domainCode" value="'. $sysAdminArr['SA_domainCode'] .'" />
				
				登录用户名：<input type="text" id="username" name="username" value="'. $sysAdminArr['SA_username'] .'" style="width:125px;" />&ensp;&ensp;
				密码：<input type="password" id="userpwd" name="userpwd" style="width:125px;" />&ensp;&ensp;
				<input type="submit" value="授权获取" class="button1" />&ensp;&ensp;
				<span style="color:red;">（当个人中心已登录，更新信息却提示“获取不到用户注册信息”时使用）</span>
			</div>
			');


			$orderName = OT::GetStr('orderName');
				if (in_array($orderName,array('theme','buyDate','useDate','updateDate','buyCost','cost','currTime','softTime','newTime','state'))==false){ $orderName='state'; }
			$orderSort = OT::GetStr('orderSort');
				if ($orderSort!='ASC'){ $orderSort='DESC'; }

			$skin->TableTop2('share_show.gif','','已订购的插件');
			$payexe = $DB->query('select * from '. OT_dbPref .'paySoft order by PS_'. $orderName .' '. $orderSort);
			if (! $row = $payexe->fetch()){
				echo('
				<tr>
					<td align="center" style="padding:6px;">无订购任何插件.</td>
				</tr>
				');
			}else{
				echo('
				<tr>
					<td width="4%"></td>
					<td width="16%"></td>
					<td width="8%"></td>
					<td width="8%"></td>
					<td width="8%"></td>
					<td width="6%"></td>
					<td width="11%"></td>
					<td width="10%"></td>
					<td width="10%"></td>
					<td width="6%"></td>
					<td width="13%"></td>
				</tr>
				<tr class="tabColor1">
					<td align="center" style="padding:3px;font-weight:bold;">编号</td>
					<td align="center" style="padding:3px;font-weight:bold;">'. $skin->ShowArrow('名称','theme',$orderName,$orderSort) .'</td>
					<td align="center" style="padding:3px;font-weight:bold;">'. $skin->ShowArrow('购买日期','buyDate',$orderName,$orderSort) .'</td>
					<td align="center" style="padding:3px;font-weight:bold;">'. $skin->ShowArrow('使用期限','useDate',$orderName,$orderSort) .'</td>
					<td align="center" style="padding:3px;font-weight:bold;">'. $skin->ShowArrow('升级期限','updateDate',$orderName,$orderSort) .'</td>
					<td align="center" style="padding:3px;font-weight:bold;">'. $skin->ShowArrow('买','buyCost',$orderName,$orderSort) .'/'. $skin->ShowArrow('续费','cost',$orderName,$orderSort) .'</td>
					<td align="center" style="padding:3px;font-weight:bold;">'. $skin->ShowArrow('当前版本','currTime',$orderName,$orderSort) .'</td>
					<td align="center" style="padding:3px;font-weight:bold;">'. $skin->ShowArrow('程序版本要求','softTime',$orderName,$orderSort) .'</td>
					<td align="center" style="padding:3px;font-weight:bold;">'. $skin->ShowArrow('最新版本','newTime',$orderName,$orderSort) .'</td>
					<td align="center" style="padding:3px;font-weight:bold;">'. $skin->ShowArrow('状态','state',$orderName,$orderSort) .'</td>
					<td align="center" style="padding:3px;font-weight:bold;">操作</td>
				</tr>
				<tbody class="tabBody padd5td">
				');

				$appNum = 0;
				do {
					$payCurrVer		= '';
					$payCurrTime	= '';
					$appNum ++;
					if ($appNum % 2 == 0){ $bgcolor='class="tabColorTr"'; }else{ $bgcolor=''; }

					if (File::IsExists(OT_ROOT .'cache/web/appVer_'. $row['PS_appID'] .'.txt')){
						$verArr = explode(PHP_EOL,trim(File::Read(OT_ROOT .'cache/web/appVer_'. $row['PS_appID'] .'.txt')));
						if (count($verArr)>=6){ $payCurrVer=OT::NumFormat($verArr[2],2); $payCurrTime=intval($verArr[3]); }
					}

//					if ($payCurrTime == ''){ $payCurrVer=$row['PS_currVer']; $payCurrTime=$row['PS_currTime']; }
					
					$updateStyleStr = $updateImgStr = $themeAddi = '';
					if (intval($row['PS_newTime']) > $payCurrTime || strlen($row['PS_currTime'])==0){
						// $updateImgStr='<div style="position:relative;"><div style="position:absolute;left:25px; top:-22px;" title="点击进入在线升级"><img src="images/newVer.gif" onclick=\'CheckUpdateBox();$id("updateV2Box").src="updateV2.php?appID='. $row['PS_appID'] .'&appType='. $row['PS_appType'] .'";\' style="cursor:pointer;" /></div></div>';
						// $updateImgStr='<div style="color:red;cursor:pointer;" onclick=\'OT_OpenAppShopUpdate('. $row['PS_appID'] .',"&appType='. $row['PS_appType'] .'");\' title="点击进入升级">[有新版本]</div>';
						$updateStyleStr='color:red;cursor:pointer;';
						$updateImgStr='<div><img src="images/newVer.gif" /></div>';
					}
					if ($row['PS_payMode'] == 6){
						$themeAddi .= '<span style="color:blue;padding-top:2px;">【免费额度】</span>';
					}
					if ( floatval($row['PS_phpVer']) > 0 && PHP_VERSION < floatval($row['PS_phpVer']) ){
						$themeAddi .= '<div style="color:'. (PHP_VERSION >= floatval($row['PS_phpVer']) ? '#cccccc' : 'red') .';padding-top:2px;" title="该插件要求PHP版本不低于v'. $row['PS_phpVer'] .'才能使用">【PHP版本≥'. $row['PS_phpVer'] .'】</div>';
					}
					if ($row['PS_currTime'] == $payCurrTime){
						$currTimeStr = PayVerTimeStr($payCurrVer,$payCurrTime);
					}else{
						$currTimeStr = '<span style="text-decoration:line-through;">'. PayVerTimeStr($row['PS_currVer'],$row['PS_currTime']) .'</span>';
						if (strlen($payCurrTime) == 0){
							$currTimeStr .= '<div style="color:red;">异常！到[个人中心]-[域名管理]-[插件列表]-重新升级</div>';
						}
					}
					echo('
					<tr '. $bgcolor .'>
						<td align="center">'. $appNum .'</td>
						<td align="center"><span title="'. $row['PS_type'] .'">'. $row['PS_theme'] .'</span><div>'. $themeAddi .'</div></td>
						<td align="center">'. $row['PS_buyDate'] .'<div style="color:#c9c9c9;" title="升级次数">'. $row['PS_updateNum'] .'</div></td>
						<td align="center">'. DateDiffStr($row['PS_useDate']) .'</td>
						<td align="center">'. DateDiffStr($row['PS_updateDate']) .'</td>
						<td align="right" style="padding-right:8px;"><div style="color:;" title="购买价">'. $row['PS_buyCost'] .'</div><div style="color:blue;" title="续费价">'. $row['PS_cost'] .'</div></td>
						<td align="center" title="本地版本：V'. $payCurrVer .'_'. $payCurrTime .'&#10;记录版本：V'. $row['PS_currVer'] .'_'. $row['PS_currTime'] .'">'. $currTimeStr .'</td>
						<td align="center">V'. $row['PS_softVer'] .' '. $row['PS_softTime'] .'</td>
						<td align="center" style="'. $updateStyleStr .'" onclick=\'OT_OpenAppShopUpdate('. $row['PS_appID'] .',"&appType='. $row['PS_appType'] .'");\' title="点击进入升级系统">'. $updateImgStr . PayVerTimeStr($row['PS_newVer'],$row['PS_newTime']) .'</td>
						<td align="center">'. Adm::SwitchBtn('paySoft',$row['PS_ID'],$row['PS_state'],'state','isUse2') .'</td>
						<td align="center">
							<span style="color:red;cursor:pointer;" onclick=\'parent.OT_OpenAppShopUpdate('. $row['PS_appID'] .',"&appType='. $row['PS_appType'] .'");\'>[升级]</span>
							<span style="color:blue;cursor:pointer;" onclick=\'parent.OT_OpenAppShopUpdate('. $row['PS_appID'] .',"&appType='. $row['PS_appType'] .'&mode=xufei");\'>[续费]</span>
							<a href="'. $row['PS_url'] .'" target="_blank" style="color:#000;">[详情]</a>
						</td>
					</tr>
					');
				}while ($row = $payexe->fetch());
			}

			echo('
			</tbody>
			</table>

			<div style="font-size:14px;color:red;font-weight:bold;padding:5px 5px 5px 6px;line-height:1.4;">
				提醒：1、插件是绑定域名使用，用非购买插件的域名登录后台会造成插件菜单不显示或插件不能使用的问题。<br />
				　　　2、一个账号可以管理多个网站，请勿一个网站注册一个账号，不利于管理。<br />
				　　　3、插件购买后，要马上升级，不然可能会出现程序错误或插件功能不显示，如暂时不想升级，插件状态设为禁用。<br />
				　　　4、如果有插件到期后不想续费，但还想使用，请不要升级后续程序版本，最新版程序需要最新版插件配合才能用。
			</div>

			<div class="tabMenuSubmit" style="text-align:center;margin:0 auto;padding-left:0;"><input type="button" class="btnBg" value="更新信息" onclick=\'document.location.href="appShop_deal.php?mudi=getInfo&backURL="+ encodeURIComponent(document.location.href);\' /></div>
		</div>
		');

	}


}


function updateWeb(){
	global $DB,$skin,$sysAdminArr,$otcmsUrl1;

	$appID		= OT::GetInt('appID');
	$appType	= OT::GetStr('appType');
	$mode		= OT::GetStr('mode');
	$beforeURL	= GetUrl::CurrDir(1);
	$updateUrlGetStr = '&OT_Database='. OT_Database .'&OT_UPDATETIME='. OT_UPDATETIME .'&OT_VERSION='. OT_VERSION .'&dataVer='. OT_UPDATEVER .'&phpVer='. PHP_VERSION .'&OT_URL='. urlencode($beforeURL) .'&username='. $sysAdminArr['SA_username'] .'&softID='. $sysAdminArr['SA_softID'] .'&softCode='. $sysAdminArr['SA_softCode'] .'&domainID='. $sysAdminArr['SA_domainID'] .'&domainCode='. $sysAdminArr['SA_domainCode'] .'&appID='. $appID .'&appType='. $appType .'&mode='. $mode;

	$retRes = ReqUrl::UseAuto($sysAdminArr['SA_getUrlMode'], 'GET', $otcmsUrl1 .'otcmsAppBuy.php?mudi=getAppInfo&adminUrl='. urlencode(GetUrl::CurrDir(0)) . $updateUrlGetStr, 'UTF-8', array(), 'note');
	if (strpos($retRes,'[true]') === false){
		die('<div style="padding:20px;font-size:14px;line-height:2;">'. $retRes .'</div>');
	}else{
		echo('<div style="padding:3px;font-size:14px;line-height:2;text-align:center;color:blue;font-weight:bold;">'. $retRes .'</div>');
		$dbName = Str::GetMark($retRes,'[dbName:',']');
		if (strlen($dbName) > 0){ $appType = $dbName; }
	}

	echo('
	<form id="updateForm" name="updateForm" method="post" action="updateV2.php?mudi=checkRight">
	<input type="hidden" id="updateEventStr" name="updateEventStr" value="" />
	<input type="hidden" id="updateFileNum" name="updateFileNum" value="" />
	<input type="hidden" id="updateFileSize" name="updateFileSize" value="" />
	<input type="hidden" id="updateVerInfo" name="updateVerInfo" value="" />
	<input type="hidden" id="updateVerTheme" name="updateVerTheme" value="" />

	<input type="hidden" id="runVerIdList" name="runVerIdList" value="" />
	<input type="hidden" id="checkFileListStr" name="checkFileListStr" value="" />
	<input type="hidden" id="updateVerListStr" name="updateVerListStr" value="" />
	<input type="hidden" id="updateFileListStr" name="updateFileListStr" value="" />
	<input type="hidden" id="updateVerPoint" name="updateVerPoint" value="" />
	<input type="hidden" id="updateFilePoint" name="updateFilePoint" value="" />

	<input type="hidden" id="updateErrUrl" name="updateErrUrl" value="about:blank" />
	<input type="hidden" id="updateConfigWindow" name="updateConfigWindow" value="" />
	</form>

	<table cellpadding="0" cellspacing="0" style="width:650px;">
	<tr>
		<td width="40%" align="left" valign="top" class="font1_1" style="padding:8px;line-height:2;">
			<div>1.尝试连接网钛科技升级系统 &ensp;<span id="updateV2Step1"></span></div>
			<div>2.检测所需的目录文件权限 &ensp;<span id="updateV2Step2"></span></div>
			<div>3.获取升级包更新文件 &ensp;<span id="updateV2Step3"></span></div>
			<div>4.运行升级过程 &ensp;<span id="updateV2Step4"></span></div>
			<div>5.升级完毕 &ensp;<span id="updateV2Step5"></span></div>
		</td>
		<td width="60%" align="left" valign="top" style="padding:4px 8px 0 8px;">
			<input type="hidden" id="updateUrlGetStr" name="updateUrlGetStr" value="'. $updateUrlGetStr .'" />
			<span id="updateV2NoteStr"></span>
			<iframe id="updateV2Box" name="updateV2Box" width="98%" hei'.'ght="255" style="width:98%;hei'.'ght:255px;" frameborder="0" scrolling="no" allowtransparency="true" src="updateV2.php?appID='. $appID .'&appType='. $appType .'"></iframe>
		</td>
	</tr>
	</table>

	<div style="height:5px;overflow:hidden;"></div>
	</div>
	</div>
	');

}


function DateDiffStr($dateStr){
	$endDateStr = '';
	if (strtotime($dateStr)){
		if (TimeDate::Diff('d',$dateStr,'2029-12-31') == 0){
			$endDateStr = '<div class="font1_2">终身</div>';
		}elseif ($dateStr >= TimeDate::Get('date')){
			$endDateDiff = TimeDate::Diff('d',TimeDate::Get('date'),$dateStr)+1;
			if ($endDateDiff == 1){
				$endDateStr = $dateStr .'<div class="font2_2">(只剩今天)</div>';
			}else{
				$endDateStr = $dateStr .'<div class="font2_2">(剩'. $endDateDiff .'天)</div>';
			}
		}else{
			$endDateStr = $dateStr .'<div class="font2_2">(已过期)</div>';
		}
	}
	return $endDateStr;
}


function PayVerTimeStr($verStr, $verTimeStr){
	$retStr = '';
	if (strlen($verTimeStr)>3){
		$retStr = 'V'. $verStr .' '. $verTimeStr;
	}
	return $retStr;
}


?>
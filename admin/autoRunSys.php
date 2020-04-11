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
<script language="javascript" type="text/javascript" src="js/share.js?v='. OT_VERSION .'"></script>
<script language="javascript" type="text/javascript" src="js/autoRunSys.js?v='. OT_VERSION .'"></script>
');


switch($mudi){
	case 'infoSet':
		$MB->IsSecMenuRight('alertBack',274,$dataType);
		InfoSet();
		break;

	default:
		die('err');
}

$skin->WebBottom();

$MB->Close();
$DB->Close();





// 参数设置
function InfoSet(){
	global $DB,$skin,$mudi,$dataType,$dataTypeCN,$systemArr,$sysAdminArr;

	$infoSysArr = Cache::PhpFile('infoSys');

	$revexe=$DB->query('select * from '. OT_dbPref .'autoRunSys');
		if ($row = $revexe->fetch()){
			$ARS_runMode			= $row['ARS_runMode'];
			$ARS_runArea			= $row['ARS_runArea'];
			$ARS_isTimeRun			= $row['ARS_isTimeRun'];
			$ARS_timeRunTime		= $row['ARS_timeRunTime'];
			$ARS_timeRunMin			= $row['ARS_timeRunMin'];
			$ARS_timeRunItem		= $row['ARS_timeRunItem'];
			$ARS_isHtmlHome			= $row['ARS_isHtmlHome'];
			$ARS_htmlHomeTime		= $row['ARS_htmlHomeTime'];
			$ARS_htmlHomeWapTime	= $row['ARS_htmlHomeWapTime'];
			$ARS_htmlHomeMin		= $row['ARS_htmlHomeMin'];
			$ARS_isHtmlList			= $row['ARS_isHtmlList'];
			$ARS_htmlListTime		= $row['ARS_htmlListTime'];
			$ARS_htmlListWapTime	= $row['ARS_htmlListWapTime'];
			$ARS_htmlListMin		= $row['ARS_htmlListMin'];
			$ARS_htmlListNum		= $row['ARS_htmlListNum'];
			$ARS_htmlListMaxNum		= $row['ARS_htmlListMaxNum'];
			$ARS_isHtmlShow			= $row['ARS_isHtmlShow'];
			$ARS_htmlShowTime		= $row['ARS_htmlShowTime'];
			$ARS_htmlShowWapTime	= $row['ARS_htmlShowWapTime'];
			$ARS_htmlShowMin		= $row['ARS_htmlShowMin'];
			$ARS_htmlShowNum		= $row['ARS_htmlShowNum'];
			$ARS_htmlShowMaxNum		= $row['ARS_htmlShowMaxNum'];
			$ARS_htmlShowStartTime	= $row['ARS_htmlShowStartTime'];
			$ARS_isColl				= $row['ARS_isColl'];
			$ARS_collTime			= $row['ARS_collTime'];
			$ARS_collMin			= $row['ARS_collMin'];
			$ARS_collNum			= $row['ARS_collNum'];
			$ARS_collFailNum		= $row['ARS_collFailNum'];
			$ARS_isSoftBak			= $row['ARS_isSoftBak'];
			$ARS_softBakTime		= $row['ARS_softBakTime'];
			$ARS_softBakMin			= $row['ARS_softBakMin'];
			$ARS_softBakArea		= $row['ARS_softBakArea'];
			$ARS_isDbBak			= $row['ARS_isDbBak'];
			$ARS_dbBakTime			= $row['ARS_dbBakTime'];
			$ARS_dbBakMin			= $row['ARS_dbBakMin'];
			$ARS_dbBakMode			= $row['ARS_dbBakMode'];
		}
	unset($revexe);

	$todayTime = TimeDate::Get();
	$beforeURL	= GetUrl::CurrDir(1);

	if ($systemArr['SYS_isHtmlHome'] != 1){
		$ARS_isHtmlHome = 0;
		$htmlStyle = 'display:none;';
		$alertStyle = 'display:;';
	}else{
		$htmlStyle = 'display:;';
		$alertStyle = 'display:none;';
	}

	echo('
	<div class="padd8">
		<b style="color:red;">关于自动生成静态页和自动采集说明：</b><a href="http://otcms.com/news/8067.html" target="_blank" class="font1_2">http://otcms.com/news/8067.html</a><br />
	</div>

	<form id="dealForm" name="dealForm" method="post" action="autoRunSys_deal.php?mudi='. $mudi .'" onsubmit="return CheckForm()">
	<input type="hidden" id="dataType" name="dataType" value="'. $dataType .'" />
	<input type="hidden" id="dataTypeCN" name="dataTypeCN" value="'. $dataTypeCN .'" />
	<script language="javascript" type="text/javascript">document.write(\'<input type="hidden" name="backURL" value="\'+ document.location.href +\'" />\')</script>

	<div class="tabMenu">
	<ul>
		<li rel="tabBase" class="selected">基本设置</li>
		<li rel="tabBak" style="display:none;">定时备份</li>
		<li rel="tabHome">首页静态页</li>
		<li rel="tabHtml" style="'. (AppAutoHtml::Jud() ? '' : 'display:none;') .'">列表&内容静态页</li>
		<li rel="tabColl" style="'. (AppAutoColl::Jud() ? '' : 'display:none;') .'">自动采集</li>
	</ul>
	</div>


	<div class="tabMenuArea">
		<table id="tabBase" style="display:;" cellpadding="0" cellspacing="0" summary="" class="padd3">
		<tr><td style="width:150px;"></td><td></td></tr>
		<tr>
			<td align="right">执行模式：</td>
			<td align="left">
				<label><input type="radio" name="runMode" value="1" '. Is::Checked($ARS_runMode,1) .' />AJAX-全项目（默认）</label>&ensp;&ensp;
				<label><input type="radio" name="runMode" value="0" '. Is::Checked($ARS_runMode,0) .' />框架-全项目</label>&ensp;&ensp;
				<label><input type="radio" name="runMode" value="11" '. Is::Checked($ARS_runMode,11) .' />AJAX-单项目</label>&ensp;&ensp;
				<label><input type="radio" name="runMode" value="10" '. Is::Checked($ARS_runMode,10) .' />框架-单项目</label>&ensp;&ensp;
				&ensp;'. $skin->TishiBox('顺序：定时检查 &gt; 首页静态 &gt; 列表内容静态 &gt; 自动采集，单项目是指按顺序运行一个项目后停止，全项目是指按顺序所有项目检查运行过去') .'
			</td>
		</tr>
		<tr>
			<td align="right">执行范围：</td>
			<td align="left">
				<ul>
					<li style="float:left;padding-right:20px;"><label><input type="checkbox" name="runArea[]" value="|qiantai|" '. Is::InstrChecked($ARS_runArea,'|qiantai|') .' />前台触发</label>&ensp;'. $skin->TishiBox('如有生成静态页和采集不建议开启该项，会影响前台访问速度') .'</li>
					<li style="float:left;padding-right:20px;"><label><input type="checkbox" name="runArea[]" value="|duli|" '. Is::InstrChecked($ARS_runArea,'|duli|') .' />独立页触发<span style="color:red;">（推荐）</span></label></li>
				</ul>
			</td>
		</tr>
		<tr>
			<td align="right" valign="top">独立页网址：</td>
			<td align="left" style="line-height:1.6;">
				<div>电脑版：<a href="'. $beforeURL .'apiRun.php?mudi=autoRun&sec=300" target="_blank" style="color:red;">'. $beforeURL .'apiRun.php?mudi=autoRun&sec=300</a>&ensp;（300为定时刷新秒数，可自行设置，不低于60）</div>
				<div>手机版：<a href="'. $beforeURL .'apiRun.php?mudi=autoRun&mode=wap&sec=300" target="_blank" style="color:red;">'. $beforeURL .'apiRun.php?mudi=autoRun&mode=wap&sec=300</a></div>
			</td>
		</tr>
		<tr>
			<td align="right">定时检查项目：</td>
			<td align="left">
				<label><input type="radio" id="isTimeRun1" name="isTimeRun" value="1" onclick="CheckTimeRunBox()" '. Is::Checked($ARS_isTimeRun,1) .' />开启</label>&ensp;&ensp;
				<label><input type="radio" id="isTimeRun0" name="isTimeRun" value="0" onclick="CheckTimeRunBox()" '. Is::Checked($ARS_isTimeRun,0) .' />关闭</label>&ensp;&ensp;
			</td>
		</tr>
		<tbody id="timeRunBox" style="display:none;">
		<tr>
			<td align="right" class="font1_2d">最后生成时间：</td>
			<td align="left">'. $ARS_timeRunTime . CalcDiff($ARS_timeRunTime,$todayTime) .'</td>
		</tr>
		<tr>
			<td align="right" class="font1_2d">每次检查间隔：</td>
			<td align="left"><input type="text" id="timeRunMin" name="timeRunMin" size="50" style="width:50px;" value="'. $ARS_timeRunMin .'" />分钟</td>
		</tr>
		<tr>
			<td align="right" class="font1_2d">检查项目：</td>
			<td align="left">
				<ul>
					<li style="float:left;padding-right:20px;"><label><input type="checkbox" name="timeRunItem[]" value="|infoContent|" '. Is::InstrChecked($ARS_timeRunItem,'|infoContent|') .' />文章内容库分表（超'. $infoSysArr['IS_tabMaxNum'] .'篇分表）</label></li>
					'. AppTaobaoke::AutoRunSysItem($ARS_timeRunItem) .'
				</ul>
			</td>
		</tr>
		</tbody>
		</table>

		<table id="tabBak" style="display:none;" cellpadding="0" cellspacing="0" summary="" class="padd3">
		<tr><td style="width:150px;"></td><td></td></tr>
		<tr>
			<td align="right">定时备份网站文件：</td>
			<td align="left">
				<label><input type="radio" id="isSoftBak1" name="isSoftBak" value="1" onclick="CheckSoftBakBox()" '. Is::Checked($ARS_isSoftBak,1) .' />开启</label>&ensp;&ensp;
				<label><input type="radio" id="isSoftBak0" name="isSoftBak" value="0" onclick="CheckSoftBakBox()" '. Is::Checked($ARS_isSoftBak,0) .' />关闭</label>&ensp;&ensp;
			</td>
		</tr>
		<tbody id="softBakBox" style="display:none;">
		<tr>
			<td align="right" class="font1_2d">备份类型：</td>
			<td align="left">
				<label><input type="radio" name="softBakArea" value="all" '. Is::Checked($ARS_softBakArea,'all') .' />全部备份</label>&ensp;&ensp;
				<label><input type="radio" name="softBakArea" value="soft" '. Is::Checked($ARS_softBakArea,'soft') .' />程序文件备份</label>&ensp;&ensp;
				<label><input type="radio" name="softBakArea" value="upfiles" '. Is::Checked($ARS_softBakArea,'upfiles') .' />只要upFiles目录</label>&ensp;&ensp;
			</td>
		</tr>
		<tr>
			<td align="right" class="font1_2d">最后备份时间：</td>
			<td align="left">'. $ARS_softBakTime . CalcDiff($ARS_softBakTime,$todayTime) .'</td>
		</tr>
		<tr>
			<td align="right" class="font1_2d">每次备份间隔：</td>
			<td align="left"><input type="text" id="softBakMin" name="softBakMin" size="50" style="width:50px;" value="'. $ARS_softBakMin .'" />分钟</td>
		</tr>
		</tbody>
		<tr>
			<td align="right">定时备份数据库：</td>
			<td align="left">
				<label><input type="radio" id="isDbBak1" name="isDbBak" value="1" onclick="CheckDbBakBox()" '. Is::Checked($ARS_isDbBak,1) .' />开启</label>&ensp;&ensp;
				<label><input type="radio" id="isDbBak0" name="isDbBak" value="0" onclick="CheckDbBakBox()" '. Is::Checked($ARS_isDbBak,0) .' />关闭</label>&ensp;&ensp;
			</td>
		</tr>
		<tbody id="dbBakBox" style="display:none;">
		<tr>
			<td align="right" class="font1_2d">备份类型：</td>
			<td align="left">
				<label><input type="radio" name="dbBakMode" value="all" '. Is::Checked($ARS_dbBakMode,'all') .' />全部备份</label>&ensp;&ensp;
				<label><input type="radio" name="dbBakMode" value="ot" '. Is::Checked($ARS_dbBakMode,'ot') .' />网钛表备份</label>&ensp;&ensp;
			</td>
		</tr>
		<tr>
			<td align="right" class="font1_2d">最后备份时间：</td>
			<td align="left">'. $ARS_dbBakTime . CalcDiff($ARS_dbBakTime,$todayTime) .'</td>
		</tr>
		<tr>
			<td align="right" class="font1_2d">每次备份间隔：</td>
			<td align="left"><input type="text" id="dbBakMin" name="dbBakMin" size="50" style="width:50px;" value="'. $ARS_dbBakMin .'" />分钟</td>
		</tr>
		</tbody>
		</table>

		<table id="tabHome" style="display:none;" cellpadding="0" cellspacing="0" summary="" class="padd3">
		<tr><td style="width:150px;"></td><td></td></tr>
		<tr>
			<td align="right">自动生成首页静态页：</td>
			<td align="left">
				<span style="color:red;'. $alertStyle .'">首页静态页尚未开启，不能使用该功能</span>
				<span style="'. $htmlStyle .'">
					<label><input type="radio" id="isHtmlHome1" name="isHtmlHome" value="1" onclick="CheckHtmlHomeBox()" '. Is::Checked($ARS_isHtmlHome,1) .' />开启</label>&ensp;&ensp;
					<label><input type="radio" id="isHtmlHome0" name="isHtmlHome" value="0" onclick="CheckHtmlHomeBox()" '. Is::Checked($ARS_isHtmlHome,0) .' />关闭</label>&ensp;&ensp;
				</span>
			</td>
		</tr>
		<tbody id="htmlHomeBox" style="display:none;">
		<tr>
			<td align="right" class="font1_2d">PC最后生成时间：</td>
			<td align="left">'. $ARS_htmlHomeTime . CalcDiff($ARS_htmlHomeTime,$todayTime) .'</td>
		</tr>
		<tr>
			<td align="right" class="font1_2d">WAP最后生成时间：</td>
			<td align="left">'. $ARS_htmlHomeWapTime . CalcDiff($ARS_htmlHomeWapTime,$todayTime) .'</td>
		</tr>
		<tr>
			<td align="right" class="font1_2d">每次生成间隔：</td>
			<td align="left"><input type="text" id="htmlHomeMin" name="htmlHomeMin" size="50" style="width:50px;" value="'. $ARS_htmlHomeMin .'" />分钟</td>
		</tr>
		</tbody>
		</table>
		');

		if ($sysAdminArr['SA_isLan'] == 1 && $sysAdminArr['SA_sendUrlMode'] == 0){
			$skin->PaySoftBox('tabHome',$skin->LanPaySoft(),true);
			echo('<input type="hidden" id="authState" name="authState" value="false" />');
		}else{
			$collItemCount = -1;
			if (OT_Database == 'mysql' && AreaApp::Jud(5)){
				$collItemCount = $DB->GetOne('select count(CI_ID) from '. OT_dbPref .'collItem where CI_isAutoColl=1');
			}
			$paraArr = array(
				'ARS_isHtmlList'		=> $ARS_isHtmlList ,
				'ARS_htmlListTime'		=> $ARS_htmlListTime . CalcDiff($ARS_htmlListTime,$todayTime) ,
				'ARS_htmlListWapTime'	=> $ARS_htmlListWapTime . CalcDiff($ARS_htmlListWapTime,$todayTime) ,
				'ARS_htmlListMin'		=> $ARS_htmlListMin ,
				'ARS_htmlListNum'		=> $ARS_htmlListNum ,
				'ARS_htmlListMaxNum'	=> $ARS_htmlListMaxNum ,
				'ARS_isHtmlShow'		=> $ARS_isHtmlShow ,
				'ARS_htmlShowTime'		=> $ARS_htmlShowTime . CalcDiff($ARS_htmlShowTime,$todayTime) ,
				'ARS_htmlShowWapTime'	=> $ARS_htmlShowWapTime . CalcDiff($ARS_htmlShowWapTime,$todayTime) ,
				'ARS_htmlShowMin'		=> $ARS_htmlShowMin ,
				'ARS_htmlShowNum'		=> $ARS_htmlShowNum ,
				'ARS_htmlShowMaxNum'	=> $ARS_htmlShowMaxNum ,
				'ARS_htmlShowStartTime'	=> $ARS_htmlShowStartTime ,
				'ARS_isColl'			=> $ARS_isColl ,
				'ARS_collTime'			=> $ARS_collTime . CalcDiff($ARS_collTime,$todayTime) ,
				'ARS_collMin'			=> $ARS_collMin ,
				'ARS_collNum'			=> $ARS_collNum ,
				'ARS_collFailNum'		=> $ARS_collFailNum ,
				'collItemCount'			=> $collItemCount ,
				'judAppAutoHtml'		=> AppAutoHtml::Jud() ? 1 : 0 ,
				'judApp66'				=> AppAutoHtml::Jud() ? 1 : 0 ,
				'judAppAutoColl'		=> AppAutoColl::Jud() ? 1 : 0 ,
				'judApp67'				=> AppAutoColl::Jud() ? 1 : 0 ,
				);

			$getWebHtml = OTauthWeb('autoRunSys', 'autoRunSys_V1.00.php', $paraArr);
			if (strpos($getWebHtml,'(OTCMS)') === false){
				$authAlertStr='未知原因';
				if (strpos($getWebHtml,'<!-- noRemote -->') !== false){
					$authAlertStr='无法访问外网';
				}elseif (strpos($getWebHtml,'<!-- noUse -->') !== false){
					$authAlertStr='授权禁用';
				}else{
				
				}
				$getWebHtml = ''.
					$skin->PaySoftBox('tabBase','因'. $authAlertStr .'而无法使用',true,true) .
					'<input type="hidden" id="authState" name="authState" value="false" />';
			}
			echo($getWebHtml);
		}

		echo('
		<div class="tabMenuSubmit"><input type="submit" class="btnBg" value="保 存" /></div>
	</div>

	</form>

	<div class="font2_1" style="padding:20px;line-height:1.4;color:red;">
		提醒：该功能是页面触发型，分为 前台触发 和 独立页触发，虽然2种可以同时进行，但如果有条件建议只用独立页触发。<br />
		　　　前台触发可能会影响用户访问网站速度和体验，触发过程中页面会呈现加载运行状态，运行内容多时会造成感觉明显卡顿情况出现。<br />
		　　　如果是用宝塔可以用宝塔计划任务定时访问独立页网址，如果用服务器可以用里面浏览器访问独立页，也可以用<a href="http://otcms.com/news/8066.html" target="_blank" style="color:blue;font-weight:bold;">定时运行软件[下载]</a>运行独立页。
	</div>
	');
}

function CalcDiff($time1, $currTime){
	$diffMin = TimeDate::Diff('min',$time1,$currTime);
	if ($diffMin < 4320){
		return '<span style="color:red;">（'. $diffMin .'分钟前）</span>';
	}else{
		return '';
	}
}

?>
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
$MB->Open('','login');


switch ($mudi){
	case 'dynWeb':
	case 'dynWeb2':
		if ($MB->IsSecMenuRight('jud',15,$dataType)==false && $MB->IsSecMenuRight('jud',57,OT::PostInt('dataID'))==false){
			JS::AlertBackEnd('您无权限.');
		}
		dynWeb();
		break;

	case 'del':
		$menuFileID = 15;
		$MB->IsSecMenuRight('alert',$menuFileID,$dataType);
		del();
		break;

	default:
		die('err');
}

$MB->Close();
$DB->Close();





// 添加、修改
function dynWeb(){
	global $DB,$mudi,$menuFileID,$menuTreeID,$systemArr;

	$backURL	= OT::PostStr('backURL');
	$dataType	= OT::PostStr('dataType');
	$dataTypeCN	= OT::PostStr('dataTypeCN');
	$isOne		= OT::PostInt('isOne');
	$dataID		= OT::PostInt('dataID');
	$theme		= OT::PostStr('theme');
	$themeB		= OT::PostInt('themeB');
	$themeColor	= OT::PostStr('themeColor');
	$themeStyle	= '';
		if ($themeB == 1){ $themeStyle .= 'font-weight:bold;'; }
		if ($themeColor != ''){ $themeStyle .= 'color:'. $themeColor .';'; }

	$mode		= OT::PostStr('mode');
	$isTitle	= OT::PostInt('isTitle');
	$titleAddi	= OT::PostStr('titleAddi');
	$template	= OT::PostStr('template');
	$templateWap= OT::PostStr('templateWap');
	$webURL		= OT::PostStr('webURL');
	$isEncUrl	= OT::PostInt('isEncUrl');
	$webKey		= OT::PostStr('webKey');
	$webDesc	= OT::PostStr('webDesc');
	$content	= OT::PostStr('content');
	$isWap		= OT::PostInt('isWap');
	$contentWap	= OT::PostStr('contentWap');
	$upImgStr	= OT::PostStr('upImgStr');
	$listMode	= OT::PostInt('listMode');
	$pageNum	= OT::PostInt('pageNum');
	$rank		= OT::PostInt('rank');
	$state		= OT::PostInt('state');
	$wapState	= OT::PostInt('wapState');

	if ($theme == ''){
		JS::AlertBackEnd('表单内容接收不全');
	}

	$newUpImgStr = ServerFile::EditorImgStr($upImgStr,$content . $contentWap);

	if ($mode == ''){
		$mode='web';
	}elseif ($mode != 'url'){
		$webURL='';
	}

		$beforeURL = GetUrl::CurrDir(1);
		$imgUrl = $beforeURL . InfoImgDir;
		$content = str_replace($imgUrl,InfoImgAdminDir,$content);
		$contentWap = str_replace($imgUrl,InfoImgAdminDir,$contentWap);

	$SaveImg = new SaveImg();
	$conUeImgArr = $SaveImg->GetImgUrlArr($content . $contentWap,'');
	$conUeImgStr = '|';
	if (is_array($conUeImgArr)){
		foreach ($conUeImgArr as $ueImgUrl){
			if (strpos($ueImgUrl,InfoImgAdminDir .'ueditor/') !== false){
				$conUeImgStr .= str_replace($InfoImgAdminDir,'',$ueImgUrl) .'|';
			}
		}
		$newUpImgStr .= $conUeImgStr;
	}
	
	$record=array();
	if ($isOne == 0){
		$record['IW_type']		= $dataType;
		$record['IW_theme']		= $theme;
	}
	$record['IW_revTime']		= TimeDate::Get();
	$record['IW_mode']			= $mode;
	$record['IW_URL']			= $webURL;
	$record['IW_isEncUrl']		= $isEncUrl;
	$record['IW_webKey']		= $webKey;
	$record['IW_webDesc']		= $webDesc;
	$record['IW_content']		= $content;
	$record['IW_isWap']			= $isWap;
	$record['IW_upImgStr']		= $newUpImgStr;
	$record['IW_listMode']		= $listMode;
	$record['IW_pageNum']		= $pageNum;
	$record['IW_rank']			= $rank;
	$record['IW_state']			= $state;
	$record['IW_wapState']		= $wapState;
	if (AppBase::Jud()){
		$record['IW_themeStyle']	= $themeStyle;
		$record['IW_isTitle']		= $isTitle;
		$record['IW_titleAddi']		= $titleAddi;
		$record['IW_template']		= $template;
	}
	if (AppWap::Jud()){
		$record['IW_templateWap']	= $templateWap;
		$record['IW_contentWap']	= $contentWap;
	}
	$webrec=$DB->query('select IW_upImgStr from '. OT_dbPref .'infoWeb where IW_ID='. $dataID);
		if (! $row = $webrec->fetch()){
			$alertMode = '添加';
			$IW_upImgStr = '';
			$record['IW_type']	= $dataType;
			$record['IW_time']	= TimeDate::Get();
			$judResult = $DB->InsertParam('infoWeb',$record);
		}else{
			$alertMode = '修改';
			$IW_upImgStr = $record['IW_upImgStr'];
			$judResult = $DB->UpdateParam('infoWeb',$record,'IW_ID='. $dataID);
		}
	unset($webrec);

	if ($judResult == true){
		ServerFile::Editor($IW_upImgStr,$upImgStr,$content);

		Adm::AddLog(array(
			'theme'	=> $theme,
			'note'	=> '【'. $dataTypeCN .'】'. $alertMode .'成功！',
			));

		$makeStr = '';
		$makeSec = 1;
		$makeNum = 0;
		if ($systemArr['SYS_dynWebUrlMode'] == 'html-2.x'){
			if ($dataID == 0){ $dataID=$DB->GetOne('select max(IW_ID) from '. OT_dbPref .'infoWeb'); }
			if ($state == 1){
				$makeSec += 2;
				$makeNum ++;
				$makeStr .= '
				<br />生成电脑版静态页：<iframe id="infoIframe'. $dataID .'" name="infoIframe'. $dataID .'" frameborder="0" allowTransparency="true" scrolling="no" style="width:220px;height:20px;" src="makeHtml_deal.php?mudi=infoWeb&htmlEachSec=1&htmlEachNum=99&dataID='. $dataID .'&rnd='. time() .'"></iframe>
				';
			}
			if ($wapState == 1 && AppWap::Jud()){
				$makeSec += 2;
				$makeNum ++;
				$makeStr .= '
				<br />生成手机版静态页：<iframe id="infoIframeWap'. $dataID .'" name="infoIframeWap'. $dataID .'" frameborder="0" allowTransparency="true" scrolling="no" style="width:220px;height:20px;" src="makeHtml_deal.php?mudi=infoWeb&mode=wap&htmlEachSec=1&htmlEachNum=99&dataID='. $dataID .'&rnd='. time() .'"></iframe>
				';
			}
			echo('
			<br /><br />
			<table align="center"><tr><td style="font-size:14px;">
			操作完成，<span id="number" style="color:red;">'. ($makeSec+1) .'</span>秒后返回'. $makeStr .'<br /><br />
			<a href="'. $backURL .'">[立即返回]</a>
			</td></tr></table>
			
			<script language="javascript" type="text/javascript">
			var n = '. $makeSec .'; num = '. $makeNum .'; calcSkipTime=null;
			function SkipNum(){
				document.getElementById("number").innerHTML = n;
				if (n<=0 || num<=0){ document.location.href="'. $backURL .'";clearInterval(calcSkipTime); }
				n --;
			}
			function SetSkipNum(){
				calcSkipTime = setInterval("SkipNum()",1000);
			}
			SetSkipNum();
			</script>
			');
		}else{
			JS::AlertHrefEnd($alertMode .'成功！',$backURL);
		}

	}else{
		JS::AlertBackEnd($alertMode .'失败.');
	}
/*
	die('
	<script language="javascript" type="text/javascript">
	alert("['. $theme .']'. $alertMode .'成功！");document.location.href="'. $backURL .'";
	</script>
	');
*/
}



// 删除
function del(){
	global $DB,$skin,$mudi,$menuFileID,$menuTreeID,$dataType,$dataTypeCN,$systemArr;

	$dataID	= OT::GetInt('dataID');
	$theme	= OT::GetStr('theme');

	$upImgStr = '';
	$delrec=$DB->query('select IW_ID,IW_upImgStr from '. OT_dbPref .'infoWeb where IW_ID='. $dataID);
		if (! $row = $delrec->fetch()){
			JS::AlertEnd('不存在该记录！');
		}
		if (strlen($row['IW_upImgStr'])>4){
			$upImgStr = $row['IW_upImgStr'];
		}
	unset($delrec);

	$judResult = $DB->query('delete from '. OT_dbPref .'infoWeb where IW_ID='. $dataID);
		if ($judResult == true){
			$alertResult = '成功';

			ServerFile::UseCutMore($upImgStr);

			if ($systemArr['SYS_newsShowUrlMode'] == 'html-2.x'){
				File::Del(OT_ROOT . Url::WebID($dataID));
				File::Del(OT_ROOT .'wap/'. Url::WebID($dataID));
			}

		}else{
			$alertResult = '失败';
		}

	Adm::AddLog(array(
		'theme'	=> $theme,
		'note'	=> '【'. $dataTypeCN .'】删除'. $alertResult .'！',
		));

	echo('
	<script language="javascript" type="text/javascript">
	parent.$id("data'. $dataID .'").style.display="none";
	</script>
	');
}

?>
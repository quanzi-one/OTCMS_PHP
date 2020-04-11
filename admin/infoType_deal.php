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
	case 'add':
		$menuFileID = 93;
		$MB->IsSecMenuRight('alertBack',$menuFileID,$dataType);
		AddOrRev();
		break;

	case 'rev':
		$menuFileID = 94;
		$MB->IsSecMenuRight('alertBack',$menuFileID,$dataType);
		AddOrRev();
		break;

	case 'del':
		$menuFileID = 95;
		$MB->IsSecMenuRight('alert',$menuFileID,$dataType);
		del();
		break;

	case 'moreUpdateHtmlDir':
		$menuFileID = 94;
		$MB->IsSecMenuRight('alert',$menuFileID,$dataType);
		MoreUpdateHtmlDir();
		break;

	case 'moreUpdateTimeDir':
		$menuFileID = 94;
		$MB->IsSecMenuRight('alert',$menuFileID,$dataType);
		MoreUpdateTimeDir();
		break;

	case 'moreCountInfo':
		$menuFileID = 94;
		$MB->IsSecMenuRight('alert',$menuFileID,$dataType);
		MoreCountInfo();
		break;

	default:
		die('err');
}

$MB->Close();
$DB->Close();





// 添加与修改
function AddOrRev(){
	global $DB,$MB,$mudi,$menuFileID,$menuTreeID;

	$backURL		= OT::PostStr('backURL');
	$dataType		= OT::PostStr('dataType');
	$dataTypeCN		= OT::PostStr('dataTypeCN');
	$dataID			= OT::PostInt('dataID');

	$htmlNameOld	= OT::PostStr('htmlNameOld');
	$oldFatID		= OT::PostInt('oldFatID');

	$theme			= OT::PostReplaceStr('theme','html');
	$themeB			= OT::PostInt('themeB');
	$themeColor		= OT::PostStr('themeColor');
	$themeStyle		= '';
		if ($themeB == 1){ $themeStyle .= 'font-weight:bold;'; }
		if ($themeColor != ''){ $themeStyle .= 'color:'. $themeColor .';'; }
	$fatID			= OT::PostInt('fatID');
		if ($fatID > 0){ $level = 2; }else{ $level = 1; }
	$mode			= OT::PostStr('mode');
	$webURL			= OT::PostStr('webURL');
	$isEncUrl		= OT::PostInt('isEncUrl');
		if ($webURL == 'http://'){ $webURL=''; }
	$webID			= OT::PostInt('webID');
	$isTitle		= OT::PostInt('isTitle');
	$titleAddi		= OT::PostRStr('titleAddi');
	$template		= OT::PostStr('template');
	$templateWap	= OT::PostStr('templateWap');
	$webKey			= OT::PostStr('webKey');
	$webDesc		= Str::RegExp(OT::PostStr('webDesc'),'html');
	$openMode		= OT::PostStr('openMode');
	$showMode		= OT::PostInt('showMode');
	$showNum		= OT::PostInt('showNum');
		if ($showNum <= 0){ $showNum = 10; }
	$wapShowMode	= OT::PostInt('wapShowMode');
	$wapShowNum		= OT::PostInt('wapShowNum');
		if ($wapShowNum <= 0){ $wapShowNum = 10; }
	$listInfo		= OT::Post('listInfo');
		if (is_array($listInfo)){ $listInfo = implode(',',$listInfo); }
	$subNewNum		= OT::PostInt('subNewNum');
	$subRecomNum	= OT::PostInt('subRecomNum');
	$subHotNum		= OT::PostInt('subHotNum');
	$isRightMenu	= OT::PostInt('isRightMenu');
	$isMenu			= OT::PostInt('isMenu');
	$isSubMenu		= OT::PostInt('isSubMenu');
	$isHome			= OT::PostInt('isHome');
	$isHomeImg		= OT::PostInt('isHomeImg');
	$homeIco		= OT::PostInt('homeIco');
	$homeNum		= OT::PostInt('homeNum');
		if ($homeNum+$isHomeImg == 0){ $homeNum = 10; }
	$isWap			= OT::PostInt('isWap');
	$isWapHome		= OT::PostInt('isWapHome');
	$isWapHomeDate	= OT::PostInt('isWapHomeDate');
	$wapHomeIco		= OT::PostInt('wapHomeIco');
	$wapHomeImgNum	= OT::PostInt('wapHomeImgNum');
	$wapHomeNum		= OT::PostInt('wapHomeNum');
		if ($wapHomeNum+$wapHomeImgNum == 0){ $wapHomeNum = 6; }
	$isItemDate		= OT::PostInt('isItemDate');
	$isItemType		= OT::PostInt('isItemType');
	$isUser			= OT::PostInt('isUser');
	$rank			= OT::PostInt('rank');
	$itemRank		= OT::PostInt('itemRank');
	$wapItemRank	= OT::PostInt('wapItemRank');
	$isWapSubMenu	= OT::PostInt('isWapSubMenu');
	$htmlName		= OT::PostStr('htmlName');
	$isChangeHtmlName = OT::PostInt('isChangeHtmlName');
	$lookScore		= OT::PostInt('lookScore');
	$state			= OT::PostInt('state');
		if ($mode != 'item'){
			$isHome = 0;
			$isUser = 0;
		}
//		If fatID>0 And showMode=5 Then showMode=1
		switch ($mode){
			case 'topic':			$webID	= OT::PostInt('topicID');		break;
			case 'taobaoke':		$webID	= OT::PostInt('taobaokeID');	break;
			case 'idcPro':			$webID	= OT::PostInt('idcProID');		break;
			case 'urlHome':			$webURL	= '{%网站根路径%}';				break;
			case 'urlMessage':		$webURL	= '{%网站根路径%}message.php';	break;
			case 'urlBbs':			$webURL	= '{%网站根路径%}message/';		break;
			case 'urlGift':			$webURL	= '{%网站根路径%}gift.php';		break;
			case 'urlUserWork':		$webURL	= '{%网站根路径%}plugin_deal.php?mudi=userGroupWork&close=true'; $openMode = '_blank';	break;
			case 'urlQiandao':		$webURL	= '{%网站根路径%}plugin_deal.php?mudi=qiandao&close=true'; $openMode = '_blank';		break;
		}
	
	if ($theme==''){
		JS::AlertBackEnd('表单内容接收不全');
	}

	$record=array();
	$record['IT_revTime']		= TimeDate::Get();
	$record['IT_level']			= $level;
	$record['IT_fatID']			= $fatID;
	$record['IT_theme']			= $theme;
	$record['IT_mode']			= $mode;
	$record['IT_URL']			= $webURL;
	$record['IT_isEncUrl']		= $isEncUrl;
	$record['IT_webID']			= $webID;
	$record['IT_webKey']		= $webKey;
	$record['IT_webDesc']		= $webDesc;
	$record['IT_openMode']		= $openMode;
	$record['IT_showMode']		= $showMode;
	$record['IT_showNum']		= $showNum;
//	$record['IT_wapShowMode']	= $wapShowMode;
	$record['IT_wapShowNum']	= $wapShowNum;
	$record['IT_isMenu']		= $isMenu;
	$record['IT_isSubMenu']		= $isSubMenu;
	$record['IT_listInfo']		= $listInfo;
	$record['IT_subNewNum']		= $subNewNum;
	$record['IT_subRecomNum']	= $subRecomNum;
	$record['IT_subHotNum']		= $subHotNum;
	$record['IT_isWap']			= $isWap;
	$record['IT_isHome']		= $isHome;
	$record['IT_isHomeImg']		= $isHomeImg;
	$record['IT_homeIco']		= $homeIco;
	$record['IT_homeNum']		= $homeNum;
	$record['IT_isWapHome']		= $isWapHome;
	$record['IT_isWapHomeDate']	= $isWapHomeDate;
//	$record['IT_wapHomeIco']	= $wapHomeIco;
	$record['IT_wapHomeImgNum']	= $wapHomeImgNum;
	$record['IT_wapHomeNum']	= $wapHomeNum;
	$record['IT_isItemDate']	= $isItemDate;
	$record['IT_isItemType']	= $isItemType;
	$record['IT_isUser']		= $isUser;
	$record['IT_rank']			= $rank;
	$record['IT_itemRank']		= $itemRank;
	$record['IT_wapItemRank']	= $wapItemRank;
	$record['IT_isWapSubMenu']	= $isWapSubMenu;
	$record['IT_htmlName']		= $htmlName;
	$record['IT_state']			= $state;
	if (AppBase::Jud()){
		$record['IT_themeStyle']	= $themeStyle;
		$record['IT_isTitle']		= $isTitle;
		$record['IT_titleAddi']		= $titleAddi;
		$record['IT_template']		= $template;
		$record['IT_isRightMenu']	= $isRightMenu;
		$record['IT_lookScore']		= $lookScore;
	}
	if (AppWap::Jud()){
		$record['IT_templateWap']	= $templateWap;
	}

	$dealrec=$DB->query('select * from '. OT_dbPref .'infoType where IT_ID='. $dataID);
		if (! $row = $dealrec->fetch()){
			$alertMode='添加';
			$record['IT_time']	= TimeDate::Get();

			$judResult = $DB->InsertParam('infoType',$record);
		}else{
			$alertMode='修改';
			if ($oldFatID==0 && $fatID>0){
				$checkexe=$DB->query('select IT_ID from '. OT_dbPref .'infoType where IT_fatID='. $dataID);
				if ($checkexe->fetch()){
					JS::AlertBackEnd('该栏目下存在子栏目，请选转移或删除子栏目，再选择非顶级分类。');
				}
				unset($checkexe);
			}
			if ($oldFatID != $fatID){
				if ($oldFatID == 0){ $oldLevel=1; }else{ $oldLevel=2; }
				if ($fatID == 0){
					$setStr="IF_typeStr=',". $dataID .",',IF_type1ID=". $dataID .",IF_type2ID=0";
				}else{
					$setStr="IF_typeStr=',". $fatID .",". $dataID .",',IF_type1ID=". $fatID .",IF_type2ID=". $dataID ."";
				}
				$DB->query('update '. OT_dbPref .'info set '. $setStr .' where IF_type'. $oldLevel .'ID='. $dataID);
			}
			$judResult = $DB->UpdateParam('infoType',$record,'IT_ID='. $dataID);
		}
	unset($dealrec);

	if ($alertMode == '添加'){
		if ($dataID == 0){ $dataID=$DB->GetOne('select max(IT_ID) from '. OT_dbPref .'infoType'); }
		$backURL = 'infoType.php?mudi=add&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&dataID='. $dataID .'';
	}

	if ($alertMode == '修改' && $htmlNameOld != $htmlName && $htmlName != ''){
		if ($DB->GetOne('select count(IT_ID) from '. OT_dbPref .'infoType where IT_fatID='. $dataID) == 0){
			$DB->query("update ". OT_dbPref ."info set IF_infoTypeDir='". $DB->ForStr($htmlName) ."/' where IF_typeStr like '%,". $dataID .",%'");
		}
	}

	if ($judResult == true){
		$alertResult = '成功';
	}else{
		$alertResult = '失败';
	}

	Adm::AddLog(array(
		'theme'	=> $theme,
		'note'	=> '【'. $dataTypeCN .'】'. $alertMode . $alertResult .'！',
		));

	JS::AlertHrefEnd($alertMode . $alertResult .'！', $backURL);
}



// 删除
function del(){
	global $DB,$skin,$mudi,$menuFileID,$menuTreeID,$systemArr;

	$dataType	= OT::GetStr('dataType');
	$dataTypeCN	= OT::GetStr('dataTypeCN');
	$dataID		= OT::GetInt('dataID');
	$theme		= OT::GetStr('theme');

	if ($dataID<=0){
		JS::AlertEnd('指定ID错误！');
	}

	$IT_htmlName = '';
	$checkexe = $DB->query('select IT_ID,IT_level,IT_fatID,IT_htmlName from '. OT_dbPref .'infoType where IT_ID='. $dataID);
	if (! $row = $checkexe->fetch()){
		JS::AlertEnd('该栏目不存在');
	}else{
		if ($row['IT_level'] == 1 || $row['IT_fatID'] == 0){
			$checkexe2 = $DB->query('select IT_ID from '. OT_dbPref .'infoType where IT_fatID='. $dataID);
			if ($checkexe2->fetch()){
				JS::AlertEnd('该栏目下存在子栏目，请先删除子栏目。');
			}
			unset($checkexe2);
		}
		$IT_htmlName = $row['IT_htmlName'];
	}
	unset($checkexe);

	$checkexe = $DB->query("select IF_ID from ". OT_dbPref ."info where IF_typeStr like '%,". $dataID .",%'");
	if ($checkexe->fetch()){
		JS::AlertEnd('有文章占用该栏目，请先转移或删除被占用的文章。');
	}
	unset($checkexe);

	$judResult = $DB->query('delete from '. OT_dbPref .'infoType where IT_ID='. $dataID);
	if ($judResult){
		$alertResult = '成功';
		if ($systemArr['SYS_newsShowUrlMode'] == 'html-2.x'){
			File::Del(OT_ROOT . Url::ListID('',$IT_htmlName,$dataID,0));
			File::Del(OT_ROOT .'wap/'. Url::ListID('',$IT_htmlName,$dataID,0));
			$isDel = true;
			$delNum = 1;
			while ($isDel){
				$delNum ++;
				if (file_exists(OT_ROOT . Url::ListID('',$IT_htmlName,$dataID,$delNum))){
					File::Del(OT_ROOT . Url::ListID('',$IT_htmlName,$dataID,$delNum));
					File::Del(OT_ROOT .'wap/'. Url::ListID('',$IT_htmlName,$dataID,$delNum));
				}else{
					$isDel = false;
				}
			}
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



// 批量更新栏目文章的静态目录名
function MoreUpdateHtmlDir(){
	global $DB;

	$backURL		= OT::PostStr('backURL');
	$dataType		= OT::PostStr('dataType');
	$dataTypeCN		= OT::PostStr('dataTypeCN');
	$selDataID		= OT::Post('selDataID');

	if (count($selDataID)<=0){
		JS::AlertBackEnd('请先选择要更新的记录.');
	}

	
	echo('<script language="javascript" type="text/javascript">');
	for ($i=0; $i<count($selDataID); $i++){
		$selID = intval($selDataID[$i]);
		if ($selID == -1){
			$newHtmlName = 'announ';
			$DB->query('update '. OT_dbPref .'info set IF_infoTypeDir='. $DB->ForStr($newHtmlName .'/') ." where IF_typeStr='announ'");
			echo('
			try { parent.$id("infoType_1").innerHTML=\'<span style="color:green;">[静态目录更新]</span>\'; }catch (e){}
			');
		}elseif ($selID > 0){
			$newHtmlName = $DB->GetOne('select IT_htmlName from '. OT_dbPref .'infoType where IT_ID='. $selID);
			if (strlen($newHtmlName) > 0){
				$DB->query('update '. OT_dbPref .'info set IF_infoTypeDir='. $DB->ForStr($newHtmlName .'/') ." where IF_typeStr like '%,". $selID .",%'");
				echo('
				try { parent.$id("infoType'. $selID .'").innerHTML=\'<span style="color:green;">[静态目录更新]</span>\'; }catch (e){}
				');
			}else{
				echo('
				try { parent.$id("infoType'. $selID .'").innerHTML=\'<span style="color:red;">[静态目录为空]</span>\'; }catch (e){}
				');
			}
		}
	}
	echo('</script>');

}



// 批量更新栏目文章的时间目录名
function MoreUpdateTimeDir(){
	global $DB;

	$backURL		= OT::PostStr('backURL');
	$dataType		= OT::PostStr('dataType');
	$dataTypeCN		= OT::PostStr('dataTypeCN');
	$selDataID		= OT::Post('selDataID');

	if (count($selDataID)<=0){
		JS::AlertBackEnd('请先选择要更新的记录.');
	}

	$htmlDatetimeDir = $DB->GetOne('select SYS_htmlDatetimeDir from '. OT_dbPref .'system');

	@ini_set('max_execution_time', 0);
	@set_time_limit(0); 
	@ob_end_clean();
	ob_implicit_flush(1);

	ob_start();
	for ($i=0; $i<count($selDataID); $i++){
		$selID = intval($selDataID[$i]);
		if ($selID == -1){
			$infoexe = $DB->query('select IF_ID,IF_time,IF_datetimeDir from '. OT_dbPref ."info where IF_typeStr='announ'");
			while ($row = $infoexe->fetch()){
				$DB->UpdateParam('info', array('IF_datetimeDir'=>Area::DatetimeDirName($row['IF_time'],$htmlDatetimeDir) .'/'), 'IF_ID='. $row['IF_ID']);
			}
			$infoexe = null;
			
			echo('
			<script language="javascript" type="text/javascript">
			try { parent.$id("infoType_1").innerHTML=\'<span style="color:green;">[时间目录更新]</span>\'; }catch (e){}
			</script>
			');
		}elseif ($selID > 0){
			$infoexe = $DB->query('select IF_ID,IF_time,IF_datetimeDir from '. OT_dbPref ."info where IF_typeStr like '%,". $selID .",%'");
			while ($row = $infoexe->fetch()){
				$DB->UpdateParam('info', array('IF_datetimeDir'=>Area::DatetimeDirName($row['IF_time'],$htmlDatetimeDir) .'/'), 'IF_ID='. $row['IF_ID']);
			}
			$infoexe = null;

			echo('
			<script language="javascript" type="text/javascript">
			try { parent.$id("infoType'. $selID .'").innerHTML=\'<span style="color:green;">[时间目录更新]</span>\'; }catch (e){}
			</script>
			');
		}
		ob_flush();
		flush();
	}

}



// 批量统计文章
function MoreCountInfo(){
	global $DB;

	$backURL		= OT::PostStr('backURL');
	$dataType		= OT::PostStr('dataType');
	$dataTypeCN		= OT::PostStr('dataTypeCN');
	$selDataID		= OT::Post('selDataID');

	if (count($selDataID)<=0){
		JS::AlertBackEnd('请先选择要统计的记录.');
	}

	echo('<script language="javascript" type="text/javascript">');
	for ($i=0; $i<count($selDataID); $i++){
		$selID = intval($selDataID[$i]);
		if ($selID == -1){
			echo('
			try { parent.$id("infoType_1").innerHTML="('. $DB->GetOne('select count(IF_ID) from '. OT_dbPref .'info where IF_type1ID=-1') .')"; }catch (e){}
			');
		}elseif ($selID > 0){
			echo('
			try { parent.$id("infoType'. $selID .'").innerHTML="('. $DB->GetOne("select count(IF_ID) from ". OT_dbPref ."info where IF_typeStr like '%,". $selID .",%'") .')"; }catch (e){}
			');
		}
	}
	echo('</script>');

}

?>
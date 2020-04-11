<?php
require(dirname(__FILE__) .'/check.php');
$skin->CheckIframe();

$userSysArr = Cache::PhpFile('userSys');


/* **** 版权所有 ****

 网钛CMS(OTCMS)
 程序开发：网钛科技
 官方网站：http://otcms.com
 联系ＱＱ：877873666
 联系邮箱：877873666@qq.com

 **** 版权所有 **** */



//用户检测
$MB->Open('','login');


switch($mudi){
	case 'switch':
		SwitchDeal();
		break;

	case 'switchAddi':
		SwitchAddiDeal();
		break;

	case 'switchColl':
		SwitchCollDeal();
		break;

	case 'switchSelect':
		SwitchSelectDeal();
		break;

	case 'switchExsit':
		SwitchExsitDeal();
		break;

	default:
		die('err');
}

$MB->Close();
$DB->Close();





// 修改状态
function SwitchDeal(){
	global $DB,$MB,$menuFileID,$menuTreeID,$dataType,$systemArr;

	$tabName	= OT::GetStr('tabName');
	$fieldName	= OT::GetStr('fieldName');
	$fieldName2	= OT::GetStr('fieldName2');
	$dataID		= OT::GetInt('dataID');
		if (in_array($fieldName,array('state','wapState','userState','isUser','isMenu','isSubMenu','isHome','isWap','isWapHome','isHomeWap','isUse','isRunCode','isRecom','isLock','isSel','isList','isTop','isReg','isLogin'))==false){
			JS::AlertEnd('操作字段不再允许范围内('. $fieldName .').');
		}

	$tabArr = array(
		'menuTree'		=> array('MT_',	'admin',	'alert'),
		'fileVer'		=> array('FV_', 'admin',	'alert'),
		'paySoft'		=> array('PS_', 'admin',	'alert'),
		'makeDiy'		=> array('MD_', 'admin',	'alert'),
		'message'		=> array('MA_', 16,			'alert'),
		'infoMessage'	=> array('IM_', 151,		'alert'),
		'infoMove'		=> array('IM_', 13,			'alert'),
		'infoType'		=> array('IT_', 94,			'alert'),
		'infoWeb'		=> array('IW_', 15,			'alert'),
		'info'			=> array('IF_', 11,			'alert'),
		'keyWord'		=> array('KW_', 138,		'alert'),
		'ad'			=> array('AD_', 148,		'alert'),
		'banner'		=> array('BN_', 288,		'alert'),
		'users'			=> array('UE_', 187,		'alert'),
		'userApi'		=> array('UA_', 202,		'alert'),
		'userGroup'		=> array('UG_', 182,		'alert'),
		'userMoney'		=> array('UM_', 248,		'alert'),
		'vote'			=> array('VT_', 126,		'alert'),
		'bbsData'		=> array('BD_', 222,		'alert'),
		'formType'		=> array('FT_', 230,		'alert'),
		'formField'		=> array('FF_', 231,		'alert'),
		'goUrl'			=> array('GU_', 234,		'alert'),
		'taokeGoods'	=> array('TG_', 252,		'alert'),
		'taokeWord'		=> array('TW_', 250,		'alert'),
		'taokeItem'		=> array('TI_', 489,		'alert'),
		'giftData'		=> array('GD_', 274,		'alert'),
		'moneyPay'		=> array('MP_', 268,		'alert'),
		'moneyRecord'	=> array('MR_', 265,		'alert'),
		'quanData'		=> array('QD_', 405,		'alert'),
		'weixinMenu'	=> array('WM_', 236,		'alert'),
		'weixinUserGroup'=> array('WUG_', 237,		'alert'),
		'weixinTplSet'	=> array('WTS_', 240,		'alert'),
		'mailInfo'		=> array('MI_', 420,		'alert'),
		'mailTpl'		=> array('MT_', 415,		'alert'),
		'phoneTpl'		=> array('PT_', 426,		'alert'),
		'idcProType'	=> array('IPT_', 426,		'alert'),
		'idcProGroup'	=> array('IPG_', 426,		'alert'),
		'idcProData'	=> array('IPD_', 426,		'alert'),
		'bbsData'		=> array('BD_', 222,		'alert'),
		'bbsReply'		=> array('BR_', 226,		'alert'),
		'goUrlBrowser'	=> array('GUB_', 234,		'alert'),
		'moneyGive'		=> array('MG_', 264,		'alert'),
		'*****'			=> array('00_', 000,		'')
		);

	if (! isset($tabArr[$tabName]) ){
		JS::AlertEnd('操作表不再允许范围内('. OT_dbPref . $tabName .').');
	}
	$tabFiled1	= $tabArr[$tabName][0];
	$tabFiled2	= $fieldName;

	$whereStr = '';
	switch ($tabName){
		case 'infoMove':
			if ($MB->IsSecMenuRight('jud',13,$dataType)==false && $MB->IsSecMenuRight('jud',54,$dataType)==false){
				JS::AlertEnd('您无权限.');
			}
			break;
	
		case 'infoWeb':
			if ($MB->IsSecMenuRight('jud',15,$dataType)==false && $MB->IsSecMenuRight('jud',57,$dataID)==false){
				JS::AlertEnd('您无权限.');
			}
			break;

		default :
			if ($tabName=='info' && $tabFiled2=='state' && $systemArr['SYS_newsShowUrlMode']=='html-2.x'){
				$whereStr = ',IF_pageCount,IF_infoTypeDir,IF_datetimeDir';
			}elseif ($tabName=='paySoft' && $tabFiled2=='state'){
				$whereStr = ',PS_phpVer';
			}
			$menuFileID	= $tabArr[$tabName][1];
			if ($menuFileID == 'admin'){
				$MB->IsAdminRight($tabArr[$tabName][2]);
			}else{
				$MB->IsSecMenuRight($tabArr[$tabName][2],$menuFileID,$dataType);
			}
			break;
	}

	$staterec=$DB->query('select '. $tabFiled1 .'ID,'. $tabFiled1 . $tabFiled2 . $whereStr .' from '. OT_dbPref . $tabName .' where '. $tabFiled1 .'ID='. $dataID);
		if (! $row = $staterec->fetch()){
			JS::AlertEnd('出错！该记录已不存在，请刷新页面。');
		}else{
			$record=array();
			if ($row[$tabFiled1 . $tabFiled2] == 1){
				$record[$tabFiled1 . $tabFiled2] = 0;

				if ($tabName=='info' && $tabFiled2=='state' && $systemArr['SYS_newsShowUrlMode']=='html-2.x'){
					File::Del(OT_ROOT . Url::NewsID($row['IF_infoTypeDir'],$row['IF_datetimeDir'],$dataID,0));
					File::Del(OT_ROOT .'wap/'. Url::NewsID($row['IF_infoTypeDir'],$row['IF_datetimeDir'],$dataID,0));
					if ($row['IF_pageCount']>1){
						for ($i=2; $i<=$row['IF_pageCount']; $i++){
							File::Del(OT_ROOT . Url::NewsID($row['IF_infoTypeDir'],$row['IF_datetimeDir'],$dataID,$i));
							File::Del(OT_ROOT .'wap/'. Url::NewsID($row['IF_infoTypeDir'],$row['IF_datetimeDir'],$dataID,$i));
						}
					}
				}

				echo('<script language="javascript" type="text/javascript">parent.$id("'. $fieldName2 . $dataID .'").innerHTML=\''. Adm::SwitchCN(0,$fieldName2) .'\';</script>');
			}else{
				$record[$tabFiled1 . $tabFiled2] = 1;

				if ($tabName=='info' && ($tabFiled2=='state' || $tabFiled2=='wapState') && $systemArr['SYS_newsShowUrlMode']=='html-2.x'){
					if ($tabFiled2 == 'state'){
						echo('
						<script language="javascript" type="text/javascript">
						try {
							parent.$id("infoHtmlBox'. $dataID .'").innerHTML=\'<iframe id="infoIframe'. $dataID .'" name="infoIframe'. $dataID .'" frameborder="0" allowTransparency="true" scrolling="no" style="width:60px;height:20px;" src="makeHtml_deal.php?mudi=newsMini&htmlEachSec=1&htmlEachNum=99&startNum=0&dataID='. $dataID .'&rnd='. time() .'"></iframe>\';
							parent.WindowHeight(0);
						}catch (e) {}
						</script>
						');
					}elseif ($tabFiled2 == 'wapState' && AppWap::Jud()){
						echo('
						<script language="javascript" type="text/javascript">
						try {
							parent.$id("infoHtmlBox'. $dataID .'").innerHTML=\'<iframe id="infoIframeWap'. $dataID .'" name="infoIframeWap'. $dataID .'" frameborder="0" allowTransparency="true" scrolling="no" style="width:60px;height:20px;" src="makeHtml_deal.php?mudi=newsMini&mode=wap&htmlEachSec=1&htmlEachNum=99&startNum=0&dataID='. $dataID .'&rnd='. time() .'"></iframe>\';
							parent.WindowHeight(0);
						}catch (e) {}
						</script>
						');
					}
				}elseif ($tabName=='paySoft' && $tabFiled2=='state'){
					if (PHP_VERSION < floatval($row['PS_phpVer'])){
						JS::AlertEnd('该插件要求PHP版本不低于v'. $row['PS_phpVer'] .'，但你当前PHP版本是v'. PHP_VERSION .'，不符合使用要求。');
					}
				}

				echo('<script language="javascript" type="text/javascript">parent.$id("'. $fieldName2 . $dataID .'").innerHTML=\''. Adm::SwitchCN(1,$fieldName2) .'\';</script>');
			}
			$DB->UpdateParam($tabName,$record,$tabFiled1 .'ID='. $dataID);
		}
	unset($staterec);

	if ($tabName == 'ad'){
		if (! Ad::MakeJs()){
			echo('
			<script language="javascript" type="text/javascript">
			alert("生成 cache/ads.js 缓存文件失败，请检查根目录下的cache/目录是否有写入权限。");
			</script>
			');
		}

	}elseif ($tabName == 'userApi'){
		AdmArea::UserApiHtml();

	}elseif ($tabName == 'users'){
		AppRecom::AuditScore($dataID);

	}elseif ($tabName == 'paySoft'){
		AdmArea::PaySoftStateDeal($dataID);

	}

}



// 修改文章属性状态
function SwitchAddiDeal(){
	global $DB,$MB,$menuFileID,$menuTreeID,$dataType,$systemArr,$userSysArr;

	$tabName	= OT::GetStr('tabName');
	$fieldValue	= OT::GetStr('fieldValue');
	$fieldName	= OT::GetStr('fieldName');
	$fieldName2	= OT::GetStr('fieldName2');
	$fieldValue2= OT::GetStr('fieldValue2');
	$dataID		= OT::GetInt('dataID');
	$name		= OT::GetStr('name');
		if (strpos('|isAudit|isNew|isHomeThumb|isThumb|isImg|isFlash|isMarquee|isRecom|isTop|event|','|'. $fieldName .'|')===false){
			JS::AlertEnd('操作字段不再允许范围内.');
		}

	$whereStr = '';
	switch ($tabName){
		case 'info':
			$menuFileID = 10;
			$MB->IsSecMenuRight('alert',$menuFileID,$dataType);
			$tabFiled1 = 'IF_';
			$tabFiled2 = $fieldName;
			$whereStr = ',IF_isGetScore,IF_getScore1,IF_getScore2,IF_getScore3,IF_userID,IF_img,IF_theme,IF_pageCount,IF_infoTypeDir,IF_datetimeDir,IF_state,IF_wapState';
			break;

		case 'users':
			$menuFileID = 187;
			$MB->IsSecMenuRight('alert',$menuFileID,$dataType);
			$tabFiled1 = 'UE_';
			$tabFiled2 = $fieldName;
			break;

		default :
			JS::AlertEnd('操作表不再允许范围内('. OT_dbPref . $tabName .').');
			break;
	}

	if ($tabName == 'info'){
		$staterec=$DB->query('select IF_ID,IF_'. $tabFiled2 . $whereStr .' from '. OT_dbPref . $tabName .' where IF_ID='. $dataID);
		if (! $row = $staterec->fetch()){
			JS::AlertEnd('出错！该记录已不存在，请刷新页面。');
		}else{
			$record=array();
			if ($row['IF_'. $tabFiled2] == 1){
				$record['IF_'. $tabFiled2]	= 0;
				if ($tabFiled2=='isAudit'){
					if ($row['IF_isGetScore']==1 && $row['IF_userID']>0){
						$record['IF_isGetScore']	= 0;
						$record['IF_getScore1']		= 0;
						$record['IF_getScore2']		= 0;
						$record['IF_getScore3']		= 0;
						Users::UpdateScore($row['IF_userID'], '-', $row['IF_getScore1'], $row['IF_getScore2'], $row['IF_getScore3']);

						if (AppUserScore::IsAdd($row['IF_getScore1'], $row['IF_getScore2'], $row['IF_getScore3'])){
							$uexe = $DB->query('select UE_username,UE_score1,UE_score2,UE_score3 from '. OT_dbPref .'users where UE_ID='. $row['IF_userID']);
							if ($urow = $uexe->fetch()){
								$scoreArr = array();
								$scoreArr['UM_userID']		= $row['IF_userID'];
								$scoreArr['UM_username']	= $urow['UE_username'];
								$scoreArr['UM_type']		= 'audit0';
								$scoreArr['UM_dataID']		= $row['IF_ID'];
								$scoreArr['UM_score1']		= $row['IF_getScore1']*(-1);
								$scoreArr['UM_score2']		= $row['IF_getScore2']*(-1);
								$scoreArr['UM_score3']		= $row['IF_getScore3']*(-1);
								$scoreArr['UM_remScore1']	= $urow['UE_score1'];
								$scoreArr['UM_remScore2']	= $urow['UE_score2'];
								$scoreArr['UM_remScore3']	= $urow['UE_score3'];
								$scoreArr['UM_note']		= '文章审核不通过回收积分“'. $row['IF_theme'] .'”';
								AppUserScore::AddData($scoreArr);
							}
							unset($uexe);
						}
					}

					if ($systemArr['SYS_newsShowUrlMode']=='html-2.x'){
						File::Del(OT_ROOT . Url::NewsID($row['IF_infoTypeDir'],$row['IF_datetimeDir'],$dataID,0));
						File::Del(OT_ROOT .'wap/'. Url::NewsID($row['IF_infoTypeDir'],$row['IF_datetimeDir'],$dataID,0));
						if ($row['IF_pageCount']>1){
							for ($i=2; $i<=$row['IF_pageCount']; $i++){
								File::Del(OT_ROOT . Url::NewsID($row['IF_infoTypeDir'],$row['IF_datetimeDir'],$dataID,$i));
								File::Del(OT_ROOT .'wap/'. Url::NewsID($row['IF_infoTypeDir'],$row['IF_datetimeDir'],$dataID,$i));
							}
						}
					}

				}
				echo('
				<script language="javascript" type="text/javascript">
				parent.$id("'. $fieldName2 . $dataID .'").innerHTML=\''. Adm::Switch2CN($name,0) .'\';
				</script>
				');
			
			}else{
				$record['IF_'. $tabFiled2]	= 1;
				if ($tabFiled2=='isAudit'){
					if ($row['IF_isGetScore']==0 && $row['IF_userID']>0){
						$newsAddScoreArr = Area::UserScore('newsAdd');
						$record['IF_getScore1']		= $newsAddScoreArr['US_score1'];
						$record['IF_getScore2']		= $newsAddScoreArr['US_score2'];
						$record['IF_getScore3']		= $newsAddScoreArr['US_score3'];
						$record['IF_isGetScore']	= 1;
						Users::UpdateScore($row['IF_userID'], '+', $newsAddScoreArr['US_score1'], $newsAddScoreArr['US_score2'], $newsAddScoreArr['US_score3']);
						
						if (AppUserScore::IsAdd($newsAddScoreArr['US_score1'], $newsAddScoreArr['US_score2'], $newsAddScoreArr['US_score3'])){
							$uexe = $DB->query('select UE_username,UE_score1,UE_score2,UE_score3 from '. OT_dbPref .'users where UE_ID='. $row['IF_userID']);
							if ($urow = $uexe->fetch()){
								$scoreArr = array();
								$scoreArr['UM_userID']		= $row['IF_userID'];
								$scoreArr['UM_username']	= $urow['UE_username'];
								$scoreArr['UM_type']		= 'audit1';
								$scoreArr['UM_dataID']		= $row['IF_ID'];
								$scoreArr['UM_score1']		= $newsAddScoreArr['US_score1'];
								$scoreArr['UM_score2']		= $newsAddScoreArr['US_score2'];
								$scoreArr['UM_score3']		= $newsAddScoreArr['US_score3'];
								$scoreArr['UM_remScore1']	= $urow['UE_score1'];
								$scoreArr['UM_remScore2']	= $urow['UE_score2'];
								$scoreArr['UM_remScore3']	= $urow['UE_score3'];
								$scoreArr['UM_note']		= '文章审核通过“'. $row['IF_theme'] .'”';
								AppUserScore::AddData($scoreArr);
							}
							unset($uexe);
						}
					}

					if ($systemArr['SYS_newsShowUrlMode']=='html-2.x'){
						if ($dataID==0){ $dataID=$DB->GetOne('select max(IF_ID) from '. OT_dbPref .'info'); }
						$makeStr = '';
						if ($row['IF_state']==1){
							$makeStr .= '<div><iframe id="infoIframe'. $dataID .'" name="infoIframe'. $dataID .'" frameborder="0" allowTransparency="true" scrolling="no" style="width:60px;height:20px;" src="makeHtml_deal.php?mudi=newsMini&htmlEachSec=1&htmlEachNum=99&startNum=0&dataID='. $dataID .'&rnd='. time() .'"></iframe></div>';
						}
						if ($row['IF_wapState']==1 && AppWap::Jud()){
							$makeStr .= '<div><iframe id="infoIframeWap'. $dataID .'" name="infoIframeWap'. $dataID .'" frameborder="0" allowTransparency="true" scrolling="no" style="width:60px;height:20px;" src="makeHtml_deal.php?mudi=newsMini&mode=wap&htmlEachSec=1&htmlEachNum=99&startNum=0&dataID='. $dataID .'&rnd='. time() .'"></iframe></div>';
						}
						if (strlen($makeStr) > 0){
							echo('
							<script language="javascript" type="text/javascript">
							try {
								parent.$id("infoHtmlBox'. $dataID .'").innerHTML=\''. $makeStr .'\';
								parent.WindowHeight(0);
							}catch (e) {}
							</script>
							');
						}
					}

				}elseif (strpos('|isHomeThumb|isThumb|isImg|isFlash|','|'. $tabFiled2 .'|')!==false){
					if (strlen($row['IF_img'])<3){
						die('
						<script language="javascript" type="text/javascript">
						alert("该文章没有缩略图，不能开启该属性。");
						</script>
						');
					}

				}
				echo('
				<script language="javascript" type="text/javascript">
				parent.$id("'. $fieldName2 . $dataID .'").innerHTML=\''. Adm::Switch2CN($name,1) .'\';
				</script>
				');
			}
			$DB->UpdateParam($tabName, $record ,'IF_ID='. $dataID);
		}
		unset($staterec);
	
	}else{
		$staterec=$DB->query('select '. $tabFiled1 . $tabFiled2 . $whereStr .' from '. OT_dbPref . $tabName .' where '. $tabFiled1 .'ID='. $dataID);
		if (! $row = $staterec->fetch()){
			JS::AlertEnd('出错！该记录已不存在，请刷新页面。');
		}else{
			$record=array();
			if (empty($row[$tabFiled1 . $tabFiled2])){ $row[$tabFiled1 . $tabFiled2] = ' '; }
			if (strpos($row[$tabFiled1 . $tabFiled2],$fieldValue2) !== false){
				$record[$tabFiled1 . $tabFiled2] = str_replace($fieldValue2,'',$row[$tabFiled1 . $tabFiled2]);
				echo('
				<script language="javascript" type="text/javascript">
				parent.$id("'. $fieldName2 . $dataID .'").innerHTML=\''. Adm::Switch2CN($name,'') .'\';
				</script>
				');
			
			}else{
				$record[$tabFiled1 . $tabFiled2] = $row[$tabFiled1 . $tabFiled2] . $fieldValue2;
				echo('
				<script language="javascript" type="text/javascript">
				parent.$id("'. $fieldName2 . $dataID .'").innerHTML=\''. Adm::Switch2CN($name,$fieldValue2) .'\';
				</script>
				');
			}
			$DB->UpdateParam($tabName, $record ,$tabFiled1 .'ID='. $dataID);
		}
		unset($staterec);

	}

}



// 修改采集功能状态
function SwitchCollDeal(){
	global $collDB,$MB,$menuFileID,$menuTreeID,$dataType,$sysAdminArr,$collDbName;

	$tabName	= OT::GetStr('tabName');
	$fieldName	= OT::GetStr('fieldName');
	$fieldName2	= OT::GetStr('fieldName2');
	$dataID		= OT::GetInt('dataID');
		if (in_array($fieldName,array('state','userState','isUser','isMenu','isSubMenu','isHome','isWap','isWapHome','isUse','isRunCode','isLock','isList'))==false){
			JS::AlertEnd('操作字段不再允许范围内('. $fieldName .').');
		}

	require(OT_adminROOT .'collConobj.php');

	switch ($tabName){
		case 'collType':
			$menuFileID = 159;
			$MB->IsSecMenuRight('alert',$menuFileID,$dataType);
			$tabFiled1 = 'CT_';
			$tabFiled2 = $fieldName;
			break;

		case 'collItem':
			$menuFileID = 164;
			$MB->IsSecMenuRight('alert',$menuFileID,$dataType);
			$tabFiled1 = 'CI_';
			$tabFiled2 = $fieldName;
			break;

		default :
			JS::AlertEnd('操作表不再允许范围内('. OT_collDbPref . $tabName .').');
			break;
	}

	$staterec=$collDB->query('select '. $tabFiled1 .'ID,'. $tabFiled1 . $tabFiled2 .' from '. OT_collDbPref . $tabName .' where '. $tabFiled1 .'ID='. $dataID);
		if (! $row = $staterec->fetch()){
			JS::AlertEnd('出错！该记录已不存在，请刷新页面。');
		}else{
			$record=array();
			if ($row[$tabFiled1 . $tabFiled2]==1){
				$record[$tabFiled1 . $tabFiled2]=0;
				echo('<script language="javascript" type="text/javascript">parent.$id("'. $fieldName2 . $dataID .'").innerHTML=\''. Adm::SwitchCN(0,$fieldName2) .'\';</script>');
			}else{
				$record[$tabFiled1 . $tabFiled2]=1;
				echo('<script language="javascript" type="text/javascript">parent.$id("'. $fieldName2 . $dataID .'").innerHTML=\''. Adm::SwitchCN(1,$fieldName2) .'\';</script>');
			}
			$collDB->UpdateParam(OT_collDbPref . $tabName,$record,$tabFiled1 .'ID='. $dataID);
		}
	unset($staterec);

}



// 修改下拉框模式状态
function SwitchSelectDeal(){
	global $DB,$MB,$menuFileID,$menuTreeID,$dataType,$systemArr,$userSysArr;

	$tabName	= OT::GetStr('tabName');
	$fieldName	= OT::GetStr('fieldName');
	$selData	= OT::GetRegExpStr('selData','abcnum');
	$dataID		= OT::GetInt('dataID');
		if (strpos('|state|isHome|isList|isNews|','|'. $fieldName .'|')===false){
			JS::AlertEnd('操作字段不再允许范围内.');
		}
		if (strlen($selData)<=0){ JS::AlertEnd('选择值不能为空.'); }

	switch ($tabName){
		case 'taokeGoods':
			$menuFileID = 156;
//			$MB->IsSecMenuRight('alert',$menuFileID,$dataType);
			$tabFiled1 = 'TG_';
			$tabFiled2 = $fieldName;
			break;

		default :
			JS::AlertEnd('操作表不再允许范围内('. OT_dbPref . $tabName .').');
			break;
	}

	$dealrec=$DB->query('select '. $tabFiled1 .'ID,'. $tabFiled1 . $tabFiled2 .' from '. OT_dbPref . $tabName .' where '. $tabFiled1 .'ID='. $dataID);
		if (! $row = $dealrec->fetch()){
			JS::AlertEnd('出错！该记录已不存在，请刷新页面。');
		}else{
			$record=array();
			$record[$tabFiled1 . $tabFiled2] = $selData;
			$DB->UpdateParam($tabName, $record ,$tabFiled1 .'ID='. $dataID);

			echo('
			<script language="javascript" type="text/javascript">
			parent.$id("'. $fieldName . $dataID .'").value=\''. $selData .'\';
			parent.$id("'. $fieldName . $dataID .'Alert").innerHTML=\'<span style="color:green;">完成</span>\';
			</script>
			');

		}
	unset($dealrec);

}



// 修改状态
function SwitchExsitDeal(){
	global $DB,$MB,$menuFileID,$menuTreeID,$dataType;

	$tabName	= OT::GetStr('tabName');
	$fieldName	= OT::GetStr('fieldName');
	$fieldName2	= OT::GetStr('fieldName2');
	$fieldValue	= OT::GetStr('fieldValue');
	$addiExt	= OT::GetStr('addiExt');
	$dataID		= OT::GetInt('dataID');
		if (! in_array($fieldName,array('rightStr'))){
			JS::AlertEnd('操作字段不再允许范围内('. $fieldName .').');
		}

	switch ($tabName){
		case 'weixinUsers':
			$menuFileID = 16;
			$MB->IsSecMenuRight('alert',$menuFileID,$dataType);
			$tabFiled1 = 'WU_';
			$tabFiled2 = $fieldName;
			break;

		default :
			JS::AlertEnd('操作表不再允许范围内('. OT_dbPref . $tabName .').');
			break;
	}

	$staterec=$DB->query('select '. $tabFiled1 .'ID,'. $tabFiled1 . $tabFiled2 .' from '. OT_dbPref . $tabName .' where '. $tabFiled1 .'ID='. $dataID);
		if (! $row = $staterec->fetch()){
			JS::AlertEnd('出错！该记录已不存在，请刷新页面。');
		}else{
			if (strpos($row[$tabFiled1 . $tabFiled2],$fieldValue) === false){
				$retStr = $row[$tabFiled1 . $tabFiled2] . $fieldValue;
				echo('<script language="javascript" type="text/javascript">parent.$id("'. $fieldName2 . $dataID . $addiExt .'").innerHTML=\''. Adm::SwitchCN(1,$fieldName2) .'\';</script>');
			}else{
				$retStr = str_replace($fieldValue,'',$row[$tabFiled1 . $tabFiled2]);
				echo('<script language="javascript" type="text/javascript">parent.$id("'. $fieldName2 . $dataID . $addiExt .'").innerHTML=\''. Adm::SwitchCN(0,$fieldName2) .'\';</script>');
			}

			$record=array();
			$record[$tabFiled1 . $tabFiled2]=$retStr;

			$DB->UpdateParam($tabName,$record,$tabFiled1 .'ID='. $dataID);
		}
	unset($staterec);

}

?>
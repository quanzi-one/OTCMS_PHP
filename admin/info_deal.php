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
$MB->Open('','login',2);


@ini_set('max_execution_time', 0);
@set_time_limit(0); 


switch ($mudi){
	case 'add':
		$menuFileID = 9;
		$MB->IsSecMenuRight('alertBack',$menuFileID,$dataType);
		AddOrRev();
		break;

	case 'rev':
		$menuFileID = 10;
		$MB->IsSecMenuRight('alertBack',$menuFileID,$dataType);
		AddOrRev();
		break;

	case 'del':
		$menuFileID = 11;
		$MB->IsSecMenuRight('alert',$menuFileID,$dataType);
		del();
		break;

	case 'moreDel':
		$menuFileID = 11;
		$MB->IsSecMenuRight('alertBack',$menuFileID,$dataType);
		MoreDel();
		break;

	case 'moreMove':
		$menuFileID = 10;
		$MB->IsSecMenuRight('alertBack',$menuFileID,$dataType);
		MoreMoveDeal();
		break;

	case 'moreAddi':
		$menuFileID = 10;
		$MB->IsSecMenuRight('alertBack',$menuFileID,$dataType);
		MoreAddiDeal();
		break;

	case 'moreTopic':
		$menuFileID = 10;
		$MB->IsSecMenuRight('alertBack',$menuFileID,$dataType);
		MoreTopicDeal();
		break;

	case 'moreSet':
		$menuFileID = 10;
		$MB->IsSecMenuRight('alertBack',$menuFileID,$dataType);
		MoreSetDeal();
		break;

	default:
		die('err');
}

$MB->Close();
$DB->Close();




//添加、修改
function AddOrRev(){
	global $DB,$MB,$skin,$mudi,$menuFileID,$menuTreeID,$sysAdminArr,$systemArr;

	$userSysArr = Cache::PhpFile('userSys');

	$backURL		= OT::PostStr('backURL');
	$dataType		= OT::PostStr('dataType');
	$dataTypeCN		= OT::PostStr('dataTypeCN');
	$dataMode		= OT::PostStr('dataMode');
	$dataModeStr	= OT::PostStr('dataModeStr');
	$dataID			= OT::PostInt('dataID');

	$themeTime		= OT::PostStr('time');
	$isOri			= OT::PostInt('isOri');
	$theme			= OT::PostStr('theme');
	$themeMd5		= md5($theme);
	$themeB			= OT::PostInt('themeB');
	$themeColor		= OT::PostStr('themeColor');
	$themeStyle		= '';
		if ($themeB==1){ $themeStyle .= 'font-weight:bold;'; }
		if ($themeColor!=''){ $themeStyle .= 'color:'. $themeColor .';'; }
	$isTitle		= OT::PostInt('isTitle');
	$titleAddi		= OT::PostStr('titleAddi');
	$template		= OT::PostStr('template');
	$templateWap	= OT::PostStr('templateWap');
	$tplTop			= OT::PostStr('tplTop');
	$tplBottom		= OT::PostStr('tplBottom');
	$source			= OT::PostStr('source');
	$writer			= OT::PostStr('writer');
	$topicID		= OT::PostInt('topicID');
	$type1ID		= 0;
	$type2ID		= 0;
	$type3ID		= 0;
	$typeStr		= OT::PostStr('typeStr');
		if ($typeStr == 'announ'){
			$type1ID = -1;
		}else{
			$typeArr = explode(',',$typeStr);
			$typeArrCount=count($typeArr);
			if ($typeArrCount>=3){
				$typeCurrID = intval($typeArr[$typeArrCount-2]);
				$type1ID = intval($typeArr[1]);
				if ($typeArrCount>=4){
					$type2ID = intval($typeArr[2]);
					if ($typeArrCount>=5){
						$type3ID = intval($typeArr[3]);
					}
				}
			}
		}
		if (! isset($typeCurrID)){ $typeCurrID=$type1ID; }
	$webURL			= OT::PostStr('webURL');
	$isEncUrl		= OT::PostInt('isEncUrl');
	$oldTabID		= OT::PostInt('oldTabID');
	$tabID			= OT::PostInt('tabID');
	$content		= Adm::FilterEditor(OT::Post('content'));
	$encContent		= Adm::FilterEditor(OT::Post('encContent'));
	$upImgStr		= OT::PostStr('upImgStr');
		if (strlen($upImgStr)>1){ $upImgStr=substr($upImgStr,0,-1); }
	$mediaFile		= OT::PostStr('mediaFile');
	$mediaEvent		= OT::Post('mediaEvent');
		if (is_array($mediaEvent)){ $mediaEvent = implode(',',$mediaEvent); }

		$newUpImgStr = ServerFile::EditorImgStr($upImgStr,$content . $encContent);
	$themeKey		= OT::PostStr('themeKey');
		if ($themeKey != ''){ $themeKey = Area::FilterThemeKey($themeKey); }
	$contentKey		= Area::FilterContentKey(OT::PostRStr('contentKey'));
		if ($contentKey == ''){ $contentKey = Str::LimitChar(Area::FilterContentKey($content),140) .'...'; }
	$isRenameFile	= OT::PostInt('isRenameFile');
	$isUserFile		= OT::PostInt('isUserFile');
	$fileName		= OT::PostStr('fileName');
	$file			= OT::PostStr('file');
	$fileNum		= OT::PostInt('fileNum');
		$fileStr = '';
		if ($fileNum > 0){
			for ($i=1; $i<=$fileNum; $i++){
				if (strlen($fileStr) > 0){ $fileStr .= '<arr>'; }
				$fileStr .= OT::PostStr('file'. $i) .'|'. ($isRenameFile == 1 ? OT::PostRegExpStr('fileName'. $i,'sql') : OT::PostStr('fileName'. $i)) .'|';
			}
		}
	$isSaveContImg	= OT::PostInt('isSaveContImg');
	$isSaveImg		= OT::PostInt('isSaveImg');
	$pageNum		= OT::PostInt('pageNum');
	$addition		= OT::Post('addition');
		if (is_array($addition)){ $addition = implode(',',$addition); }
	$isAudit		= OT::PostInt('isAudit');
	$isNew			= OT::PostInt('isNew');
	$isHomeThumb	= OT::PostInt('isHomeThumb');
	$isThumb		= OT::PostInt('isThumb');
	$isImg			= OT::PostInt('isImg');
	$isFlash		= OT::PostInt('isFlash');
	$isMarquee		= OT::PostInt('isMarquee');
	$isRecom		= OT::PostInt('isRecom');
	$isTop			= OT::PostInt('isTop');
	$imgMode		= 0;
	$img			= OT::PostStr('img');
		if (strlen($imgMode)>7){
			if (Is::AbsUrl($imgMode)){
				$imgMode = 2;
			}else{
				$imgMode = 1;
			}
		}
	$voteMode	= OT::PostInt('voteMode');
	$voteStr	= '';
		if ($voteMode == 1){
			$voteItem1	= OT::PostInt('voteItem1');
			$voteItem2	= OT::PostInt('voteItem2');
			$voteItem3	= OT::PostInt('voteItem3');
			$voteItem4	= OT::PostInt('voteItem4');
			$voteItem5	= OT::PostInt('voteItem5');
			$voteItem6	= OT::PostInt('voteItem6');
			$voteItem7	= OT::PostInt('voteItem7');
			$voteItem8	= OT::PostInt('voteItem8');
			$voteStr = $voteItem1 .','. $voteItem2 .','. $voteItem3 .','. $voteItem4 .','. $voteItem5 .','. $voteItem6 .','. $voteItem7 .','. $voteItem8;
		}elseif ($voteMode == 2){
			$voteItem11	= OT::PostInt('voteItem11');
			$voteItem12	= OT::PostInt('voteItem12');
			$voteStr = $voteItem11 .','. $voteItem12 .',0,0,0,0,0,0';
		}
	$isMarkNews		= OT::PostInt('isMarkNews');
	$isReply		= OT::PostInt('isReply');
	$topAddiID		= OT::PostInt('topAddiID');
	$addiID			= OT::PostInt('addiID');
	$readNum		= OT::PostInt('readNum');
	$state			= OT::PostInt('state');
	$wapState		= OT::PostInt('wapState');
	$isCheckUser	= OT::PostInt('isCheckUser');
	$userGroupList	= OT::Post('userGroupList');
		if (is_array($userGroupList)){ $userGroupList = implode(',',$userGroupList); }
	$userLevel		= OT::PostInt('userLevel');
	$score1			= OT::PostInt('score1');
	$score2			= OT::PostInt('score2');
	$score3			= OT::PostInt('score3');
	$cutScore1		= OT::PostInt('cutScore1');
	$cutScore2		= OT::PostInt('cutScore2');
	$cutScore3		= OT::PostInt('cutScore3');
	$isEnc			= OT::PostInt('isEnc');
	$isSitemap		= OT::PostInt('isSitemap');
	$isXiongzhang	= OT::PostInt('isXiongzhang');
	$bdPing			= OT::PostInt('bdPing',9);
	$auditNote		= OT::PostStr('auditNote');

	$isChangeInfoTypeDir = OT::PostInt('isChangeInfoTypeDir');
	$isChangeDatetimeDir = OT::PostInt('isChangeDatetimeDir');


	if ($themeTime=='' || $theme=='' || $typeStr==''){
		JS::AlertBackEnd('表单内容接收不全.');
	}

	if (strpos($addition,'|themeAppr|') !== false){
		$theme = ApprWord_Get($theme);
	}
	if (strpos($addition,'|contentAppr|') !== false){
		$content = ApprWord_Get($content);
	}

	$reslutStr = '';
	$SaveImg = new SaveImg();
	$SaveImg->judProxy = false;
	$SaveImg->saveOss = $sysAdminArr['SA_upFileOss'];
	if ($isSaveContImg == 1){
		$content=$SaveImg->ReplaceContent($content, InfoImgAdminDir, 0, '');

		$newUpImgStr .= $SaveImg->imgStr;
		if (strlen($img)>0 && strpos($SaveImg->oldImgStr,$img) !== false){
			$remOldImgArr	= explode('|',$SaveImg->oldImgStr);
			$remImgArr		= explode('|',$SaveImg->imgStr);
			$remImgCount	= count($remImgArr);
			for ($n=1; $n<$remImgCount; $n++){
				if ($img==$remOldImgArr[$n]){ $img=$remImgArr[$n]; break; }
			}
		}
	}
	if ($isSaveImg == 1 && Is::AbsUrl($img)){
		$newImgExt = File::GetExt($img);	// 文件类型
		if (strlen($newImgExt)>8){ $newImgExt = 'jpg'; }
		
		// 判断保存文件不能为以下文件类型
		if (strpos('**|asp|asa|aspx|cer|cdx|exe|rar|zip|bat|','|'. $newImgExt .'|') === false){
			if (empty($sysImagesArr)){ $sysImagesArr = Cache::PhpFile('sysImages'); }
			$saveDirPath = '';
			if ($sysImagesArr['SI_isDir'] > 0){
				// 判断并创建本地目录
				switch ($sysImagesArr['SI_isDir']){
					case 1:	$saveDirPath = TimeDate::Get('Y') .'/';		break;
					case 2:	$saveDirPath = TimeDate::Get('Ym') .'/';	break;
					case 3:	$saveDirPath = TimeDate::Get('Ymd') .'/';	break;
				}
			}

			$newFileDir = 'coll/'. $saveDirPath;
				if ($SaveImg->CreateDir(InfoImgAdminDir . $newFileDir)==false){
					$newFileDir = 'coll/';
				}
			$newFileName = $newFileDir .'OT'. date('YmdHis') . OT::RndNum(3) .'.'. $newImgExt;
			// 执行保存操作
			$srfArr = $SaveImg->SaveRemoteFile(InfoImgAdminDir . $newFileName, $img, $newFileName, 0);
			if ($srfArr['res']){
				if (strlen($srfArr['url']) > 3){
					$img = $srfArr['url'];
				}else{
					$img = $newFileName;
				}
			}else{
				$reslutStr .= '<div style="color:red;font-weight:bold;">缩略图/图片 保存到本地失败（'. $srfArr['note'] .'）</div>';
			}
		}
	}

	$beforeURL=GetUrl::CurrDir(1);
	$imgUrl=$beforeURL . InfoImgDir;
	$content= str_replace($imgUrl,InfoImgAdminDir,$content);
	$encContent= str_replace($imgUrl,InfoImgAdminDir,$encContent);

	$conUeImgArr = $SaveImg->GetImgUrlArr($content,'');
	$conUeImgStr = '|';
	if (is_array($conUeImgArr)){
		foreach ($conUeImgArr as $ueImgUrl){
			if (strpos($ueImgUrl,InfoImgAdminDir .'ueditor/') !== false){
				$conUeImgStr .= str_replace(InfoImgAdminDir,'',$ueImgUrl) .'|';
			}
		}
		$newUpImgStr .= $conUeImgStr;
	}

	if (strlen($newUpImgStr)>10 && $img==''){
		$imgArr = explode('|',$newUpImgStr);
		foreach ($imgArr as $imgName){
			if ($imgName!='' && strpos('|.gif|.jpg|jpeg|.bmp|.png|',substr($imgName,-4)) !== false){
				$img = $imgName;
				break;
			}
		}
	}
	$pageCount = Content::CalcPageNum($content,$pageNum);

	$record=array();
	$record['IF_revTime']		= TimeDate::Get();
	$record['IF_time']			= $themeTime;
	$record['IF_isOri']			= $isOri;
	$record['IF_theme']			= $theme;
	$record['IF_themeMd5']		= $themeMd5;
	$record['IF_template']		= $template;
	$record['IF_templateWap']	= $templateWap;
	$record['IF_tplTop']		= $tplTop;
	$record['IF_tplBottom']		= $tplBottom;
	$record['IF_source']		= $source;
	$record['IF_writer']		= $writer;
	$record['IF_typeStr']		= $typeStr;
	$record['IF_type1ID']		= $type1ID;
	$record['IF_type2ID']		= $type2ID;
	$record['IF_type3ID']		= $type3ID;
	$record['IF_URL']			= $webURL;
	$record['IF_isEncUrl']		= $isEncUrl;
	$record['IF_tabID']			= $tabID;
	if ($tabID == 0){
		$record['IF_content']	= $content;
	}else{
		$record['IF_content']	= '';
	}
	$record['IF_upImgStr']		= $newUpImgStr;
	$record['IF_themeKey']		= $themeKey;
	$record['IF_contentKey']	= $contentKey;
	$record['IF_pageNum']		= $pageNum;
	$record['IF_pageCount']		= $pageCount;
	$record['IF_fileName']		= $fileName;
	$record['IF_file']			= $file;
	$record['IF_fileStr']		= $fileStr;
	$record['IF_isRenameFile']	= $isRenameFile;
	$record['IF_isAudit']		= $isAudit;
	$record['IF_isNew']			= $isNew;
	$record['IF_isHomeThumb']	= $isHomeThumb;
	$record['IF_isThumb']		= $isThumb;
	$record['IF_isImg']			= $isImg;
	$record['IF_isFlash']		= $isFlash;
	$record['IF_isMarquee']		= $isMarquee;
	$record['IF_isRecom']		= $isRecom;
	$record['IF_isTop']			= $isTop;
	$record['IF_imgMode']		= $imgMode;
	$record['IF_img']			= $img;
	$record['IF_voteMode']		= $voteMode;
	$record['IF_voteStr']		= $voteStr;
	$record['IF_isMarkNews']	= $isMarkNews;
	$record['IF_isReply']		= $isReply;
	$record['IF_topAddiID']		= $topAddiID;
	$record['IF_addiID']		= $addiID;
	$record['IF_readNum']		= $readNum;
	$record['IF_state']			= $state;
	$record['IF_wapState']		= $wapState;
	$record['IF_isCheckUser']	= $isCheckUser;
	$record['IF_userGroupList']	= $userGroupList;
	$record['IF_userLevel']		= $userLevel;
	$record['IF_score1']		= $score1;
	$record['IF_score2']		= $score2;
	$record['IF_score3']		= $score3;
	$record['IF_cutScore1']		= $cutScore1;
	$record['IF_cutScore2']		= $cutScore2;
	$record['IF_cutScore3']		= $cutScore3;
	$record['IF_addition']		= $addition;
	$record['IF_auditNote']		= $auditNote;
	if (AppBase::Jud()){
		$record['IF_themeStyle']	= $themeStyle;
		$record['IF_isTitle']		= $isTitle;
		$record['IF_titleAddi']		= $titleAddi;
		$record['IF_isUserFile']	= $isUserFile;
	}
	if (AppMapBaidu::Jud()){
		$record['IF_isSitemap']		= $isSitemap;
		$record['IF_isXiongzhang']	= $isXiongzhang;
	}
	if (AppNewsEnc::Jud()){
		$record['IF_isEnc']			= $isEnc;
		$record['IF_encContent']	= $encContent;
	}
	if (AppVideo::Jud()){
		$record['IF_mediaFile']		= $mediaFile;
		$record['IF_mediaEvent']	= $mediaEvent;
	}
	if (AppTopic::Jud()){
		$record['IF_topicID']		= $topicID;
	}

	$fileAddArr = $fileCutArr = $userSqlArr = array();
	$addrec=$DB->query('select * from '. OT_dbPref .'info where IF_ID='. $dataID);
	if (! $row = $addrec->fetch()){
		$alertMode='新增';

		$record['IF_type']		=  $dataType;
		$record['IF_adminID']	=  $MB->mUserID;

		$judResult = $DB->InsertParam('info',$record);
	}else{
		$alertMode='修改';

		if ($row['IF_userID'] > 0){
			if ($row['IF_isAudit'] != $isAudit){
				$newsAddScoreArr = Area::UserScore('newsAdd');
				if ($isAudit == 1 && $row['IF_isGetScore'] == 0){
					$record['IF_isGetScore']	= 1;
					$record['IF_getScore1']		= $newsAddScoreArr['US_score1'];
					$record['IF_getScore2']		= $newsAddScoreArr['US_score2'];
					$record['IF_getScore3']		= $newsAddScoreArr['US_score3'];
					$userSqlArr[] = Users::UpdateScore($row['IF_userID'], '+', $newsAddScoreArr['US_score1'], $newsAddScoreArr['US_score2'], $newsAddScoreArr['US_score3'], array(), 'get');

					if (AppUserScore::IsAdd($newsAddScoreArr['US_score1'], $newsAddScoreArr['US_score2'], $newsAddScoreArr['US_score3'])){
						$uexe = $DB->query('select UE_username,UE_score1,UE_score2,UE_score3 from '. OT_dbPref .'users where UE_ID='. $row['IF_userID']);
						if ($urow = $uexe->fetch()){
							$scoreArr = array();
							$scoreArr['UM_userID']		= $row['IF_userID'];
							$scoreArr['UM_username']	= $urow['UE_username'];
							$scoreArr['UM_type']		= 'audit1';
							$scoreArr['UM_dataID']		= $dataID;
							$scoreArr['UM_score1']		= $newsAddScoreArr['US_score1'];
							$scoreArr['UM_score2']		= $newsAddScoreArr['US_score2'];
							$scoreArr['UM_score3']		= $newsAddScoreArr['US_score3'];
							$scoreArr['UM_remScore1']	= $urow['UE_score1']+$newsAddScoreArr['US_score1'];
							$scoreArr['UM_remScore2']	= $urow['UE_score2']+$newsAddScoreArr['US_score2'];
							$scoreArr['UM_remScore3']	= $urow['UE_score3']+$newsAddScoreArr['US_score3'];
							$scoreArr['UM_note']		= '文章审核通过“'. $theme .'”';
							$userSqlArr[] = AppUserScore::AddData($scoreArr,'get');
						}
						unset($uexe);
					}

				}elseif (($isAudit==0 || $isAudit==2) && $row['IF_isGetScore']==1){
					$record['IF_isGetScore']	= 0;
					$record['IF_getScore1']		= 0;
					$record['IF_getScore2']		= 0;
					$record['IF_getScore3']		= 0;
					$userSqlArr[] = Users::UpdateScore($row['IF_userID'], '-', $row['IF_getScore1'], $row['IF_getScore2'], $row['IF_getScore3'], array(), 'get');

					if (AppUserScore::IsAdd($row['IF_getScore1'], $row['IF_getScore2'], $row['IF_getScore3'])){
						$uexe = $DB->query('select UE_username,UE_score1,UE_score2,UE_score3 from '. OT_dbPref .'users where UE_ID='. $row['IF_userID']);
						if ($urow = $uexe->fetch()){
							$scoreArr = array();
							$scoreArr['UM_userID']		= $row['IF_userID'];
							$scoreArr['UM_username']	= $urow['UE_username'];
							$scoreArr['UM_type']		= 'audit0';
							$scoreArr['UM_dataID']		= $dataID;
							$scoreArr['UM_score1']		= $row['IF_getScore1']*(-1);
							$scoreArr['UM_score2']		= $row['IF_getScore2']*(-1);
							$scoreArr['UM_score3']		= $row['IF_getScore3']*(-1);
							$scoreArr['UM_remScore1']	= $urow['UE_score1']-$newsAddScoreArr['US_score1'];
							$scoreArr['UM_remScore2']	= $urow['UE_score2']-$newsAddScoreArr['US_score2'];
							$scoreArr['UM_remScore3']	= $urow['UE_score3']-$newsAddScoreArr['US_score3'];
							$scoreArr['UM_note']		= '文章审核不通过回收积分“'. $theme .'”';
							$userSqlArr[] = AppUserScore::AddData($scoreArr,'get');
						}
						unset($uexe);
					}

				}
			}
		}
		$judResult = $DB->UpdateParam('info',$record,'IF_ID='. $dataID);
	}

	if ($judResult){
		$alertResult = '成功';
		if ($dataID==0){ $dataID = $DB->GetOne('select max(IF_ID) from '. OT_dbPref .'info'); }
		if ($tabID > 0){
			if ($alertMode == '新增'){
				$DB->InsertParam('infoContent'. $tabID, array('IC_ID'=>$dataID, 'IC_content'=>$content));
			}else{
				if ($oldTabID == $tabID){
					$DB->UpdateParam('infoContent'. $tabID, array('IC_content'=>$content), 'IC_ID='. $dataID);
				}else{
					$DB->InsertParam('infoContent'. $tabID, array('IC_ID'=>$dataID, 'IC_content'=>$content));
				}
			}
		}
		if ($alertMode == '修改' && $oldTabID > 0 && $oldTabID != $tabID){
			$DB->Delete('infoContent'. $oldTabID, 'IC_ID='. $dataID);
		}
		if ($row['IF_file'] != $file){
			$fileAddArr[] = $file;
			$fileCutArr[] = $row['IF_file'];
		}
		if ($row['IF_img'] != $img){
			$fileAddArr[] = $img;
			$fileCutArr[] = $row['IF_img'];
		}

		$IF_upImgStr = $row['IF_upImgStr'];

		$htmlDir = '';
		$IF_infoTypeDir = $row['IF_infoTypeDir'];
		$IF_datetimeDir = $row['IF_datetimeDir'];
		$dataArr = array();
		if (($alertMode=='新增' || $isChangeInfoTypeDir==1) && $systemArr['SYS_htmlInfoTypeDir']==1){
			if ($typeStr=='announ'){
				$IF_infoTypeDir = 'announ/';
			}else{
				$IF_infoTypeDir = $DB->GetOne('select IT_htmlName from '. OT_dbPref .'infoType where IT_ID='. $typeCurrID) .'/';
			}
			$dataArr['IF_infoTypeDir']		= $IF_infoTypeDir;
		}
		if ($alertMode=='新增' || $isChangeDatetimeDir>0){
			$IF_datetimeDir = Area::DatetimeDirName($themeTime,-1) .'/';
			$dataArr['IF_datetimeDir']		= $IF_datetimeDir;
		}
		if (count($dataArr)>0){
			$DB->UpdateParam('info',$dataArr,'IF_ID='. $dataID);
		}
		if ($systemArr['SYS_htmlInfoTypeDir']==1 && strlen($IF_infoTypeDir)>1){ $htmlDir .= $IF_infoTypeDir; }
		if ($systemArr['SYS_htmlDatetimeDir']>0 && strlen($IF_datetimeDir)>1){ $htmlDir .= $IF_datetimeDir; }
		ServerFile::UseAddMore($fileAddArr);
		ServerFile::UseCutMore($fileCutArr);
		ServerFile::Editor($IF_upImgStr,$upImgStr,$content);
		$runMoreArr = $DB->RunMore($userSqlArr);
		echo($runMoreArr['errSqlStr']);
	}else{
		$alertResult = '失败';
	}
	unset($addrec);

	Adm::AddLog(array(
		'theme'	=> $theme,
		'note'	=> '【'. $dataTypeCN .'】'. $alertMode . $alertResult .'！',
		));

	$makeStr = '';
	$makeSec = 1;
	$makeNum = 0;
	if ($systemArr['SYS_newsShowUrlMode'] == 'html-2.x' && $isAudit == 1 && ($state == 1 || $wapState == 1)){
		if ($dataID==0){ $dataID = GetOne('select max(IF_ID) from '. OT_dbPref .'info'); }

		$isMakeHtml=false;
		/*
		if InStr(addition,'|makeHtml|')>0 Then
			isMakeHtml=True
			For i=1 To pageCount
				If i>1 Then htmlFilePart = '_'& i Else htmlFilePart = ''
				infoFileStr = CD_GetCode(beforeURL &'news/?'. $dataID . htmlFilePart &'.html&rnd='& Timer())
				If SYS_diyInfoTypeDir=1 And SYS_htmlInfoTypeDir=1 Then
					htmlMakeUrl = htmlDir . $dataID . htmlFilePart &'.html'
				else
					htmlMakeUrl = SYS_newsShowFileName &'/'& htmlDir . $dataID . htmlFilePart &'.html'
				end if
				If InStr(infoFileStr,'webTypeName')>=1 Then
					judFileResult = CD_WriteFile(infoFileStr & Chr(13) &'<!-- Html For '& Now() &' -->',Server.MapPath('../'& htmlMakeUrl))
					If judFileResult=True Then
						makeStr = makeStr &'<br />'& htmlMakeUrl &' ...<span style='color:green;'>生成成功</span>'
					Else
						isMakeHtml=False
						makeStr = makeStr &'<br />'& htmlMakeUrl &' ...<span class='font2_2'>生成失败(写入文件失败)</span>'
					End If

				Else
					isMakeHtml=False
					makeStr = makeStr &'<br />'& htmlMakeUrl &' ...<span class='font2_2'>生成失败</span>'
				End If
			Next
		End If
		*/
		if (! $isMakeHtml){
			if ($state == 1){
				$makeSec += 2;
				$makeNum ++;
				$makeStr .= '
				<br />生成电脑版静态页：<iframe id="infoIframe'. $dataID .'" name="infoIframe'. $dataID .'" frameborder="0" allowTransparency="true" scrolling="no" style="width:220px;height:20px;" src="makeHtml_deal.php?mudi=newsOne&htmlEachSec=1&htmlEachNum=99&startNum=0&dataID='. $dataID .'&rnd='. time() .'"></iframe>
				';
			}
			if ($wapState == 1 && AppWap::Jud()){
				$makeSec += 2;
				$makeNum ++;
				$makeStr .= '
				<br />生成手机版静态页：<iframe id="infoIframeWap'. $dataID .'" name="infoIframeWap'. $dataID .'" frameborder="0" allowTransparency="true" scrolling="no" style="width:220px;height:20px;" src="makeHtml_deal.php?mudi=newsOne&mode=wap&htmlEachSec=1&htmlEachNum=99&startNum=0&dataID='. $dataID .'&rnd='. time() .'"></iframe>
				';
			}
		}
	}

//	JS::AlertHref(''. $alertMode .'成功',$backURL);
	if ($alertMode == '新增'){
		$alertStr = '新增'. $dataTypeCN;
		if ($dataID==0){ $dataID = GetOne('select max(IF_ID) from '. OT_dbPref .'info'); }
		$backURL = 'info.php?mudi=add&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&dataMode='. $dataMode .'&dataModeStr='. $dataModeStr .'&dataID='. $dataID .'';
	}else{
		$alertStr = $dataTypeCN .'管理';
	}

	$pageCountStr='';
	if ($pageCount > 20){
		$pageCountStr = '，<span style="color:red;">当前内容分页为'. $pageCount .'页偏大了，建议重新修改分页</span>';
		$backURL = 'info.php?mudi=rev&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&dataMode='. $dataMode .'&dataModeStr='. $dataModeStr .'&dataID='. $dataID .'';
	}

	if ($bdPing==0){
		if ($dataID==0){ $dataID = GetOne('select max(IF_ID) from '. OT_dbPref .'info'); }
		$getexe=$DB->query('select IF_ID,IF_infoTypeDir,IF_datetimeDir from '. OT_dbPref .'info where IF_ID='. $dataID);
		if ($row = $getexe->fetch()){
			$pingStr=''.
			'<?xml version="1.0" encoding="UTF-8"?>'.
			'<methodCall>'.
			'	<methodName>weblogUpdates.extendedPing</methodName>'.
			'	<params>'.
			'		<param>'.
			'			<value><string>'. $systemArr['SYS_title'] .'</string></value>'.
			'		</param>'.
			'		<param>'.
			'			<value><string>'. $beforeURL .'</string></value>'.
			'		</param>'.
			'		<param>'.
			'			<value><string>'. $beforeURL . Url::NewsID($row['IF_infoTypeDir'],$row['IF_datetimeDir'],$row['IF_ID'],0) .'</string></value>'.
			'		</param>'.
			'		<param>'.
			'			<value><string>'. $beforeURL .'rss.php?maxNum=100</string></value>'.
			'		</param>'.
			'	</params>'.
			'</methodCall>';
		}
		unset($getexe);

		$retStr = ReqUrl::UseAuto2($sysAdminArr['SA_getUrlMode'], 'POST', 'http://ping.baidu.com/ping/RPC2', 'UTF-8', $pingStr, 'note');

		if (strpos($retStr,'<int>0</int>') !== false){
			$reslutStr .= '<span style="color:green;font-weight:bold;">百度Ping成功。</span>';
			$DB->query('update '. OT_dbPref .'info set IF_bdPing=1 where IF_ID='. $dataID);
		}else{
			$reslutStr .= '<span style="color:red;font-weight:bold;">百度Ping失败。</span>';
		}
		
//		response.write('【'. $pingStr .'|'. $retStr .'|'. $reslutStr .'】')
//		response.end()
	}

	$skin->WebCommon();

	echo('
	<br /><br />
	<meta http-equiv="Content-Type" content="text/html; charset='. OT_Charset .'">
	<table align="center"><tr><td style="font-size:14px;">
	'. $reslutStr .'<br /><br />
	操作'. $alertResult . $pageCountStr .'，<span id="number" style="color:red;">'. ($makeSec+1) .'</span>秒后返回【'. $alertStr .'】'. $makeStr .'<br /><br />
	<a href="info.php?mudi=add&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&dataMode='. $dataMode .'&dataModeStr='. $dataModeStr .'&dataID='. $dataID .'">继续[新增'. $dataTypeCN .']</a>&ensp;&ensp;&ensp;&ensp;
	<a href="info.php?mudi=manage&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&dataMode='. $dataMode .'&dataModeStr='. $dataModeStr .'">回到['. $dataTypeCN .'管理]</a>
	</td></tr></table>
	
	<script language="javascript" type="text/javascript">
	var sec = '. $makeSec .'; num = '. $makeNum .'; calcSkipTime=null;
	function SkipNum(){
		document.getElementById("number").innerHTML = sec;
		if (sec<=0 || num<=0){ document.location.href="'. $backURL .'";clearInterval(calcSkipTime); }
		sec --;
	}
	function SetSkipNum(){
		calcSkipTime = setInterval("SkipNum()",1000);
	}
	SetSkipNum();
	WindowHeight(0);
	</script>
	');

}



// 删除
function del(){
	global $DB,$mudi,$menuFileID,$menuTreeID,$dataType,$dataTypeCN,$systemArr;

	$dataID	= OT::GetInt('dataID');
	$theme	= OT::GetStr('theme');

	$delrec=$DB->query('select IF_ID,IF_upImgStr,IF_file,IF_img,IF_pageCount,IF_userID,IF_infoTypeDir,IF_datetimeDir,IF_tabID from '. OT_dbPref .'info where IF_ID='. $dataID);
	if (! $row = $delrec->fetch()){
		JS::AlertEnd('不存在该记录');
	}
	$IF_upImgStr	= $row['IF_upImgStr'];
	$IF_file		= $row['IF_file'];
	$IF_img			= $row['IF_img'];
	$IF_pageCount	= $row['IF_pageCount'];
	$IF_userID		= $row['IF_userID'];
	$IF_infoTypeDir = $row['IF_infoTypeDir'];
	$IF_datetimeDir = $row['IF_datetimeDir'];
	$IF_tabID		= $row['IF_tabID'];
	unset($delrec);

	$judResult = $DB->query('delete from '. OT_dbPref .'info where IF_ID='. $dataID);
	if ($judResult == true){
		$alertResult = '成功';

		if ($IF_tabID > 0){ $DB->query('delete from '. OT_dbPref .'infoContent'. $IF_tabID .' where IC_ID='. $dataID); }
		if ($IF_userID>0){
			$DB->query('update '. OT_dbPref .'users set UE_newsCount=UE_newsCount-1 where UE_ID='. $IF_userID);
			Area::DelUserFile('UF_proID='. $dataID .' and UF_proType="info"');
		}
		if (strlen($IF_upImgStr .'|'. $IF_img)>8){
			$appSysArr = Cache::PhpFile('appSys');
			$fileArr=explode('|',$IF_upImgStr .'|'. $IF_img);
			foreach ($fileArr as $val){
				if (strlen($val)>3){
					$oneFileName = str_replace(array('../','..\\','%'), array('','',''), $val);
					if ( strlen($appSysArr['AS_qiniuUrl']) > 8 && strpos($oneFileName, $appSysArr['AS_qiniuUrl']) !== false ){
						AreaApp::OssDel('qiniu', $oneFileName);

					}elseif ( strlen($appSysArr['AS_upyunUrl']) > 8 && strpos($oneFileName, $appSysArr['AS_upyunUrl']) !== false ){
						AreaApp::OssDel('upyun', $oneFileName);

					}elseif ( strlen($appSysArr['AS_aliyunUrl']) > 8 && strpos($oneFileName, $appSysArr['AS_aliyunUrl']) !== false ){
						AreaApp::OssDel('aliyun', $oneFileName);

					}elseif ( strlen($appSysArr['AS_jinganUrl']) > 8 && strpos($oneFileName, $appSysArr['AS_jinganUrl']) !== false ){
						AreaApp::OssDel('jingan', $oneFileName);

					}elseif ( strlen($appSysArr['AS_ftpUrl']) > 8 && strpos($oneFileName, $appSysArr['AS_ftpUrl']) !== false ){
						AreaApp::OssDel('ftp', $oneFileName);

					}elseif ($oneFileName!='' && (substr($oneFileName,0,2)=='OT' || substr($oneFileName,0,5)=='coll/' || substr($oneFileName,0,8)=='ueditor/') ){
						File::Del(OT_ROOT . InfoImgDir . $oneFileName);

					}
				}
			}
		}

		ServerFile::UseCutMore($IF_upImgStr .'|'. $IF_file .'|'. $IF_img);

		if ($systemArr['SYS_newsShowUrlMode'] == 'html-2.x'){
			File::Del(OT_ROOT . Url::NewsID($IF_infoTypeDir,$IF_datetimeDir,$dataID,0));
			File::Del(OT_ROOT .'wap/'. Url::NewsID($IF_infoTypeDir,$IF_datetimeDir,$dataID,0));
			if ($IF_pageCount>1){
				for ($i=2; $i<=$IF_pageCount; $i++){
					File::Del(OT_ROOT . Url::NewsID($IF_infoTypeDir,$IF_datetimeDir,$dataID,$i));
					File::Del(OT_ROOT .'wap/'. Url::NewsID($IF_infoTypeDir,$IF_datetimeDir,$dataID,$i));
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



// 批量删除
function MoreDel(){
	global $DB,$mudi,$menuFileID,$menuTreeID,$systemArr;

	$backURL	= OT::PostStr('backURL');
	$dataType	= OT::PostStr('dataType');
	$dataTypeCN	= OT::PostStr('dataTypeCN');
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


	$fileCutStr = '';
	$dataNum = $delNum = 0;
	$delrec=$DB->query('select IF_ID,IF_upImgStr,IF_file,IF_img,IF_pageCount,IF_userID,IF_infoTypeDir,IF_datetimeDir,IF_tabID from '. OT_dbPref .'info where IF_ID in (0'. $whereStr .')');
	while ($row = $delrec->fetch()){
		$dataNum ++;
		$IF_ID			= $row['IF_ID'];
		$IF_userID		= $row['IF_userID'];
		$IF_infoTypeDir	= $row['IF_infoTypeDir'];
		$IF_datetimeDir	= $row['IF_datetimeDir'];
		$IF_tabID		= $row['IF_tabID'];
		$fileCutStr .= $row['IF_upImgStr'] .'|'. $row['IF_file'] .'|'. $row['IF_img'] .'|';
		if ($systemArr['SYS_newsShowUrlMode'] == 'html-2.x'){
			File::Del(OT_ROOT . Url::NewsID($IF_infoTypeDir,$IF_datetimeDir,$IF_ID,0));
			File::Del(OT_ROOT .'wap/'. Url::NewsID($IF_infoTypeDir,$IF_datetimeDir,$IF_ID,0));
			if ($row['IF_pageCount']>1){
				for ($i=2; $i<=$row['IF_pageCount']; $i++){
					File::Del(OT_ROOT . Url::NewsID($IF_infoTypeDir,$IF_datetimeDir,$IF_ID,$i));
					File::Del(OT_ROOT .'wap/'. Url::NewsID($IF_infoTypeDir,$IF_datetimeDir,$IF_ID,$i));
				}
			}
		}
		$judResult = $DB->query('delete from '. OT_dbPref .'info where IF_ID='. $IF_ID);
			if ($judResult){
				$delNum ++;
				if ($IF_tabID > 0){ $DB->query('delete from '. OT_dbPref .'infoContent'. $IF_tabID .' where IC_ID='. $IF_ID); }
				if ($IF_userID>0){
					$DB->query('update '. OT_dbPref .'users set UE_newsCount=UE_newsCount-1 where UE_ID='. $IF_userID);
					Area::DelUserFile('UF_proID='. $IF_ID .' and UF_proType="info"');
				}
			}
	}
	unset($delrec);

	$appSysArr = Cache::PhpFile('appSys');
	$fileArr=explode('|',$fileCutStr);
	foreach ($fileArr as $val){
		if (strlen($val)>3){
			$oneFileName = str_replace(array('../','..\\','%'), array('','',''), $val);
			if ( strlen($appSysArr['AS_qiniuUrl']) > 8 && strpos($oneFileName, $appSysArr['AS_qiniuUrl']) !== false ){
				AreaApp::OssDel('qiniu', $oneFileName);

			}elseif ( strlen($appSysArr['AS_upyunUrl']) > 8 && strpos($oneFileName, $appSysArr['AS_upyunUrl']) !== false ){
				AreaApp::OssDel('upyun', $oneFileName);

			}elseif ( strlen($appSysArr['AS_aliyunUrl']) > 8 && strpos($oneFileName, $appSysArr['AS_aliyunUrl']) !== false ){
				AreaApp::OssDel('aliyun', $oneFileName);

			}elseif ( strlen($appSysArr['AS_jinganUrl']) > 8 && strpos($oneFileName, $appSysArr['AS_jinganUrl']) !== false ){
				AreaApp::OssDel('jingan', $oneFileName);

			}elseif ( strlen($appSysArr['AS_ftpUrl']) > 8 && strpos($oneFileName, $appSysArr['AS_ftpUrl']) !== false ){
				AreaApp::OssDel('ftp', $oneFileName);

			}elseif ($oneFileName!='' && (substr($oneFileName,0,2)=='OT' || substr($oneFileName,0,5)=='coll/' || substr($oneFileName,0,8)=='ueditor/') ){
				File::Del(OT_ROOT . InfoImgDir . $oneFileName);

			}
		}
	}
	ServerFile::UseCutMore($fileCutStr);



	Adm::AddLog(array(
		'note'		=> '【'. $dataTypeCN .'】批量删除(共'. $dataNum .'条，成功'. $delNum .'条)！',
		));

	JS::AlertHrefEnd('批量删除(共'. $dataNum .'条，成功'. $delNum .'条).',$backURL);

}



// 栏目批量移动
function MoreMoveDeal(){
	global $DB,$mudi,$menuFileID,$menuTreeID,$dataType,$dataTypeCN,$systemArr;

	$backURL		= OT::PostStr('backURL');
	$dataType		= OT::PostStr('dataType');
	$dataTypeCN		= OT::PostStr('dataTypeCN');
	$moreMoveTo		= OT::PostRegExpStr('moreMoveTo','sql+,');
	$moreMoveToCN	= OT::PostRegExpStr('moreMoveToCN','sql');
	$selDataID		= OT::Post('selDataID');

	if (count($selDataID)<=0){
		JS::AlertBackEnd('请先选择要移动的记录.');
	}
	if ($moreMoveTo==''){
		JS::AlertBackEnd('请选择批量移动的栏目.');
	}

	$whereStr='';
	for ($i=0; $i<count($selDataID); $i++){
		$whereStr .= ','. intval($selDataID[$i]);
	}
	if ($whereStr == ''){
		JS::AlertBackEnd('请先选择要移动的记录.');
	}

	$type1ID		= 0;
	$type2ID		= 0;
	$type3ID		= 0;
	$typeStr		= $moreMoveTo;
		if ($typeStr == 'announ'){
			$type1ID = -1;
		}else{
			$typeArr = explode(',',$typeStr);
			$typeArrCount=count($typeArr);
			if ($typeArrCount>=3){
				$type1ID = intval($typeArr[1]);
				if ($typeArrCount>=4){
					$type2ID = intval($typeArr[2]);
					if ($typeArrCount>=5){
						$type3ID = intval($typeArr[3]);
					}
				}
			}
		}

	$judResult = $DB->query('update '. OT_dbPref .'info set IF_typeStr='. $DB->ForStr($moreMoveTo) .',IF_type1ID='. $type1ID .',IF_type2ID='. $type2ID .',IF_type3ID='. $type3ID .' where IF_ID in (0'. $whereStr .')');
	if ($judResult){
		$alertResult = '成功';
	}else{
		$alertResult = '失败';
	}

	Adm::AddLog(array(
		'note'		=> '【'. $dataTypeCN .'】栏目批量移动到['. $moreMoveToCN .']'. $alertResult .'！',
		));

	JS::AlertHref('栏目批量移动'. $alertResult .'.',$backURL);
}



// 附加内容批量移动
function MoreAddiDeal(){
	global $DB,$mudi,$menuFileID,$menuTreeID,$dataType,$dataTypeCN,$systemArr;

	$backURL		= OT::PostStr('backURL');
	$dataType		= OT::PostStr('dataType');
	$dataTypeCN		= OT::PostStr('dataTypeCN');
	$moreTxtSel		= OT::PostRegExpStr('moreTxtSel','sql');
	$selDataID		= OT::Post('selDataID');

	if (count($selDataID)<=0){
		JS::AlertBackEnd('请先选择要设置的记录.');
	}

	$whereStr='';
	for ($i=0; $i<count($selDataID); $i++){
		$whereStr .= ','. intval($selDataID[$i]);
	}
	if ($whereStr == ''){
		JS::AlertBackEnd('请先选择要设置的记录.');
	}

	$record = array();
	if (in_array($moreTxtSel,array('topAddiID','addiID'))){
		$moreAddiTo		= OT::PostInt('moreAddiTo',-1);
		$moreAddiToCN	= OT::PostRegExpStr('moreAddiToCN','sql');
		if ($moreAddiTo < 0){
			JS::AlertBackEnd('请选择批量设置的栏目.');
		}
		$record['IF_'. $moreTxtSel] = $moreAddiTo;

		if ($moreTxtSel == 'topAddiID'){ $selName = '正文头附加内容'; }else{ $selName = '正文尾附加内容'; }
		$alertStr = $selName .' 批量设置为['. $moreAddiToCN .']';

	}elseif (in_array($moreTxtSel,array('source','writer'))){
		$moreTxtVal		= OT::PostStr('moreTxtVal');
		$record['IF_'. $moreTxtSel] = $moreTxtVal;

		if ($moreTxtSel == 'source'){ $selName = '来源'; }else{ $selName = '作者'; }
		$alertStr = $selName .' 批量设置为['. $moreTxtVal .']';

	}elseif (in_array($moreTxtSel,array('readNum','score1','score2','score3','cutScore1','cutScore2','cutScore3'))){
		$moreTxtVal		= OT::PostInt('moreTxtVal');
		$record['IF_'. $moreTxtSel] = $moreTxtVal;

		switch ($moreTxtSel){
			case 'readNum':		$selName = '阅读量';		break;
			case 'score1':		$selName = '限制阅读积分1';	break;
			case 'score2':		$selName = '限制阅读积分2';	break;
			case 'score3':		$selName = '限制阅读积分3';	break;
			case 'cutScore1':	$selName = '付费阅读积分1';	break;
			case 'cutScore2':	$selName = '付费阅读积分2';	break;
			case 'cutScore3':	$selName = '付费阅读积分3';	break;
		}
		$alertStr = $selName .' 批量设置为['. $moreTxtVal .']';

	}else{
		JS::AlertBackEnd('请选择批量设置的字段.');
	}

	$judResult = $DB->UpdateParam('info', $record, 'IF_ID in (0'. $whereStr .')');
		if ($judResult){
			$alertResult = '成功';
		}else{
			$alertResult = '失败';
		}

	Adm::AddLog(array(
		'note'		=> '【'. $dataTypeCN .'】'. $alertStr . $alertResult .'！',
		));

	JS::AlertHref($alertStr . $alertResult .'！', $backURL);
}



// 专题批量移动
function MoreTopicDeal(){
	global $DB,$mudi,$menuFileID,$menuTreeID,$dataType,$dataTypeCN,$systemArr;

	$backURL		= OT::PostStr('backURL');
	$dataType		= OT::PostStr('dataType');
	$dataTypeCN		= OT::PostStr('dataTypeCN');
	$moreTopicTo	= OT::PostInt('moreTopicTo',-1);
	$moreTopicToCN	= OT::PostRegExpStr('moreTopicToCN','sql');
	$selDataID		= OT::Post('selDataID');

	if (count($selDataID)<=0){
		JS::AlertBackEnd('请先选择要移动的记录.');
	}
	if ($moreTopicTo < 0){
		JS::AlertBackEnd('请选择批量移动的栏目.');
	}

	$whereStr='';
	for ($i=0; $i<count($selDataID); $i++){
		$whereStr .= ','. intval($selDataID[$i]);
	}
	if ($whereStr == ''){
		JS::AlertBackEnd('请先选择要移动的记录.');
	}


	$judResult = $DB->query('update '. OT_dbPref .'info set IF_topicID='. $moreTopicTo .' where IF_ID in (0'. $whereStr .')');
	if ($judResult){
		$alertResult = '成功';
	}else{
		$alertResult = '失败';
	}

	Adm::AddLog(array(
		'note'		=> '【'. $dataTypeCN .'】专题批量移动到['. $moreTopicToCN .']'. $alertResult .'！',
		));

	JS::AlertHref('专题批量移动'. $alertResult .'！',$backURL);
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

	switch ($moreSetTo){
		case 'audit1':		$dealStr = 'IF_isAudit=1';		break;
		case 'audit0':		$dealStr = 'IF_isAudit=0';		break;
		case 'new1':		$dealStr = 'IF_isNew=1';		break;
		case 'new0':		$dealStr = 'IF_isNew=0';		break;
		case 'homeThumb1':	$dealStr = 'IF_isHomeThumb=1';	break;
		case 'homeThumb0':	$dealStr = 'IF_isHomeThumb=0';	break;
		case 'thumb1':		$dealStr = 'IF_isThumb=1';		break;
		case 'thumb0':		$dealStr = 'IF_isThumb=0';		break;
		case 'img1':		$dealStr = 'IF_isImg=1';		break;
		case 'img0':		$dealStr = 'IF_isImg=0';		break;
		case 'flash1':		$dealStr = 'IF_isFlash=1';		break;
		case 'flash0':		$dealStr = 'IF_isFlash=0';		break;
		case 'marquee1':	$dealStr = 'IF_isMarquee=1';	break;
		case 'marquee0':	$dealStr = 'IF_isMarquee=0';	break;
		case 'recom1':		$dealStr = 'IF_isRecom=1';		break;
		case 'recom0':		$dealStr = 'IF_isRecom=0';		break;
		case 'top1':		$dealStr = 'IF_isTop=1';		break;
		case 'top0':		$dealStr = 'IF_isTop=0';		break;
		case 'vote0':		$dealStr = 'IF_voteMode=0';		break;
		case 'vote1':		$dealStr = 'IF_voteMode=1';		break;
		case 'vote2':		$dealStr = 'IF_voteMode=2';		break;
		case 'vote11':		$dealStr = 'IF_voteMode=11';	break;
		case 'markNews1':	$dealStr = 'IF_isMarkNews=1';	break;
		case 'markNews0':	$dealStr = 'IF_isMarkNews=0';	break;
		case 'userFile1':	$dealStr = 'IF_isUserFile=1';	break;
		case 'userFile0':	$dealStr = 'IF_isUserFile=0';	break;
		case 'reply1':		$dealStr = 'IF_isReply=1';		break;
		case 'reply10':		$dealStr = 'IF_isReply=10';		break;
		case 'reply0':		$dealStr = 'IF_isReply=0';		break;
		case 'state1':		$dealStr = 'IF_state=1';		break;
		case 'state0':		$dealStr = 'IF_state=0';		break;
		case 'wapState1':	$dealStr = 'IF_wapState=1';		break;
		case 'wapState0':	$dealStr = 'IF_wapState=0';		break;
		case 'checkUser1':	$dealStr = 'IF_isCheckUser=1';	break;
		case 'checkUser2':	$dealStr = 'IF_isCheckUser=2';	break;
		case 'checkUser0':	$dealStr = 'IF_isCheckUser=0';	break;
		case 'isSitemap0':	$dealStr = 'IF_isSitemap=0';	break;
		case 'isSitemap2':	$dealStr = 'IF_isSitemap=2';	break;
		case 'isSitemap1':	$dealStr = 'IF_isSitemap=1';	break;
		case 'isXiongzhang0':	$dealStr = 'IF_isXiongzhang=0';	break;
		case 'isXiongzhang2':	$dealStr = 'IF_isXiongzhang=2';	break;
		case 'isXiongzhang1':	$dealStr = 'IF_isXiongzhang=1';	break;
		case 'bdPing0':		$dealStr = 'IF_bdPing=0';		break;
		case 'bdPing2':		$dealStr = 'IF_bdPing=2';		break;
		case 'bdPing1':		$dealStr = 'IF_bdPing=1';		break;
		case 'timeNew':		$dealStr = 'IF_time='. $DB->ForTime(TimeDate::Get());	break;
		default :
			JS::AlertBackEnd('目的不明确.');
			break;
	}

	$judResult = $DB->query('update '. OT_dbPref .'info set '. $dealStr .' where IF_ID in (0'. $whereStr .')');
	if ($judResult){
		$alertResult = '成功';
	}else{
		$alertResult = '失败';
	}

	Adm::AddLog(array(
		'note'		=> '【'. $dataTypeCN .'】批量设置成['. $moreSetToCN .']'. $alertResult .'！',
		));

	JS::AlertHref('批量设置'. $alertResult .'.',$backURL);
}

?>
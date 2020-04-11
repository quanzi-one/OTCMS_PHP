<?php
require(dirname(__FILE__) .'/check.php');

Area::CheckIsOutSubmit();	// 检测是否外部提交

$userSysArr = Cache::PhpFile('userSys');

	$userRow = Users::Open('get',',UE_username,UE_realname,UE_score1,UE_score2,UE_score3,UE_state','',$judUserErr);
		if ((! $userRow) || $judUserErr != ''){
			die('
			<br /><br />
			<center style="font-size:14px;">
				请先登录，该功能需要登录才能使用。（'. $judUserErr .'）<a class="font2_1" href="users.php?mudi=login&force=1">[登录]</a>
			</center>
			<br /><br />
			');
		}

	$UE_ID		= $userRow['UE_ID'];
	$UE_state	= $userRow['UE_state'];



switch ($mudi){
	case 'deal':
		$tpl = null;
		deal();
		break;

	case 'del':
		del();
		break;

}

$DB->Close();





// 添加/修改
function deal(){
	global $DB,$UE_ID,$UE_state,$userRow,$systemArr,$userSysArr;
	global $tpl,$GB_WebHost,$GB_JsHost;

	$infoSysArr = Cache::PhpFile('infoSys');

	$backURL		= OT::PostStr('backURL');
	$dataID			= OT::PostInt('dataID');
	$page			= OT::PostInt('page');

	$theme			= OT::PostReplaceStr('theme','html');
	$isOri			= OT::PostInt('isOri');
	$source			= OT::PostRegExpStr('source','html');
	$writer			= OT::PostRegExpStr('writer','html');
	$typeStr		= Str::Filter(OT::PostStr('typeStr'),'typeStr');
	$content		= Area::FilterEditor(OT::PostRStr('content'));
	$upImgStr		= Str::Filter(OT::PostStr('upImgStr'),'upImgStr');
	$pageNum		= OT::PostInt('pageNum');
	$pageCount		= Content::CalcPageNum($content,$pageNum);
	$themeKey		= Area::FilterThemeKey(OT::PostStr('themeKey'));
	$contentKey		= Area::FilterContentKey(OT::PostStr('contentKey'));
	$mediaFile		= OT::PostReplaceStr('mediaFile','input');
	$file			= OT::PostReplaceStr('file','input');
	$fileName		= OT::PostReplaceStr('fileName','input');
	$fileNum		= OT::PostInt('fileNum');
		$fileStr = '';
		if ($fileNum > 0){
			for ($i=1; $i<=$fileNum; $i++){
				if (strlen($fileStr) > 0){ $fileStr .= '<arr>'; }
				$fileStr .= OT::PostStr('file'. $i) .'|'. OT::PostStr('fileName'. $i) .'|';
			}
		}
	$isRenameFile	= OT::PostInt('isRenameFile');
	$isUserFile		= OT::PostInt('isUserFile');
	$isNew			= OT::PostInt('isNew');
	$isHomeThumb	= OT::PostInt('isHomeThumb');
	$isThumb		= OT::PostInt('isThumb');
	$isFlash		= OT::PostInt('isFlash');
	$isImg			= OT::PostInt('isImg');
	$isMarquee		= OT::PostInt('isMarquee');
	$isRecom		= OT::PostInt('isRecom');
	$isTop			= OT::PostInt('isTop');
	$img			= OT::PostReplaceStr('img','input');
	$voteMode		= OT::PostInt('voteMode');
		if ($userSysArr['US_isNewsVote'] != 99){ $voteMode = $userSysArr['US_isNewsVote']; }
	$isMarkNews		= OT::PostInt('isMarkNews');
		if ($userSysArr['US_isMarkNews'] != 99){ $isMarkNews = $userSysArr['US_isMarkNews']; }
	$isReply		= OT::PostInt('isReply');
		if ($userSysArr['US_isReply'] != 99){ $isReply = $userSysArr['US_isReply']; }
	$topicID		= OT::PostInt('topicID');
		if ($userSysArr['US_topicID'] != -1){ $topicID = $userSysArr['US_topicID']; }
	$topAddiID		= OT::PostInt('topAddiID');
		if ($userSysArr['US_topAddiID'] != -1){ $topAddiID = $userSysArr['US_topAddiID']; }
	$addiID			= OT::PostInt('addiID');
		if ($userSysArr['US_addiID'] != -1){ $addiID = $userSysArr['US_addiID']; }
	$isCheckUser	= OT::PostInt('isCheckUser',-1);
	$score1			= OT::PostInt('score1');
	$score2			= OT::PostInt('score2');
	$score3			= OT::PostInt('score3');
	$cutScore1		= OT::PostInt('cutScore1');
	$cutScore2		= OT::PostInt('cutScore2');
	$cutScore3		= OT::PostInt('cutScore3');
	$isEnc			= OT::PostInt('isEnc');
	$encContent		= Area::FilterEditor(OT::PostRStr('encContent'));
	$addition		= OT::Post('addition');
		if (is_array($addition)){ $addition = implode(',',$addition); }
	$verCode		= strtolower(OT::PostStr('verCode'));

	if (strpos($userSysArr['US_newsEvent'],'|noLink|') !== false){
		$content = Str::FilterMark($content,'a');
	}
	if (strpos($userSysArr['US_newsEvent'],'|noUrl|') !== false){
		$content = Str::RegExp($content,'filterUrl');
	}

	if ($theme=='' || $typeStr=='' || strlen($content)<6){
		JS::AlertBackEnd('表单接收不全');
	}

	if ($UE_state == 0){
		JS::AlertBackEnd('您尚未审核通过，该功能无法使用。');
	}

	if ($pageCount > 20){
		JS::AlertBackEnd('当前内容分页为'. $pageCount .'页超过最大限制的20页，请重新设置分页');
	}

	if (OT_OpenVerCode && strpos($systemArr['SYS_verCodeStr'],'|news|') !== false){
		if ($systemArr['SYS_verCodeMode'] == 20){
			$geetest = new Geetest();
			if (! $geetest->IsTrue('web')){
				JS::AlertBackEnd('验证码错误，请重新点击');
			}
		}else{
			if ($verCode=='' || $verCode != strtolower($_SESSION['VerCode'. $systemArr['SYS_verCodeMode']])){
				JS::AlertBackEnd('验证码错误.');
			}
			$_SESSION['VerCode'. $systemArr['SYS_verCodeMode']] = '';
		}
	}

	if (strpos($userSysArr['US_newsAddiStr'],'|new|') === false){ $isNew=0; }
	if (strpos($userSysArr['US_newsAddiStr'],'|homeThumb|') === false){ $isHomeThumb=0; }
	if (strpos($userSysArr['US_newsAddiStr'],'|thumb|') === false){ $isThumb=0; }
	if (strpos($userSysArr['US_newsAddiStr'],'|flash|') === false){ $isFlash=0; }
	if (strpos($userSysArr['US_newsAddiStr'],'|img|') === false){ $isImg=0; }
	if (strpos($userSysArr['US_newsAddiStr'],'|marquee|') === false){ $isMarquee=0; }
	if (strpos($userSysArr['US_newsAddiStr'],'|recom|') === false){ $isRecom=0; }
	if (strpos($userSysArr['US_newsAddiStr'],'|top|') === false){ $isTop=0; }
	
	if ($userSysArr['US_isRevSource'] == 0){ $source = $userSysArr['US_newsSource']; }
	if ($userSysArr['US_isRevWriter'] == 0){
		if (strpos($userSysArr['US_newsWriter'],'{username}') !== false){
			$writer		= str_replace('{username}', $userRow['UE_username'], $userSysArr['US_newsWriter']);
		}elseif (strpos($userSysArr['US_newsWriter'],'{会员用户名}') !== false){
			$writer		= str_replace('{会员用户名}', $userRow['UE_username'], $userSysArr['US_newsWriter']);
		}elseif (strpos($userSysArr['US_newsWriter'],'{会员用户名部分隐藏}') !== false){
			$writer		= str_replace('{会员用户名部分隐藏}', Str::PartHide($userRow['UE_username']), $userSysArr['US_newsWriter']);
		}
		if (strpos($userSysArr['US_newsWriter'],'{会员昵称}') !== false){
			$writer		= str_replace('{会员昵称}', $userRow['UE_realname'], $userSysArr['US_newsWriter']);
		}
	}

	if ($contentKey == ''){ $contentKey = Str::LimitChar(Area::FilterContentKey($content),140) .'...'; }

	$ug = new UserGroup($UE_ID);
	if (strpos($ug->row['UG_event'],'|禁止投稿|') !== false){
		JS::AlertBackEnd('您所在用户组禁止投稿，如有问题请联系管理员');
	}

	$badWordArr = explode('|',$userSysArr['US_newsBadWord']);
	foreach ($badWordArr as $str){
		if ($str != ''){
			if (strpos($content,$str) !== false){
				if ($userSysArr['US_newsBadMode'] == 1){
					$content = str_replace($str,'',$content);
				}elseif ($userSysArr['US_newsBadMode'] == 2){
					$content = str_replace($str,'[屏蔽词]',$content);
				}else{
					JS::AlertBackEnd('内容中含禁用词（'. $str .'）');
				}
			}
			if (strpos($contentKey,$str) !== false){
				$contentKey = str_replace($str,'',$contentKey);
			}
			if (strpos($theme,$str) !== false){
				if ($userSysArr['US_newsBadMode'] == 1){
					$theme = str_replace($str,'',$theme);
				}elseif ($userSysArr['US_newsBadMode'] == 2){
					$theme = str_replace($str,'[屏蔽词]',$theme);
				}else{
					JS::AlertBackEnd('标题中含禁用词（'. $str .'）');
				}
			}
		}
	}

	if ($score1 < 0){
		$score1 = 0;
	}elseif ($score1 > $ug->row['UG_infoScore1']){
		$score1 = $ug->row['UG_infoScore1'];
	}
	if ($score2 < 0){
		$score2 = 0;
	}elseif ($score2 > $ug->row['UG_infoScore2']){
		$score2 = $ug->row['UG_infoScore2'];
	}
	if ($score3 < 0){
		$score3 = 0;
	}elseif ($score3 > $ug->row['UG_infoScore3']){
		$score3 = $ug->row['UG_infoScore3'];
	}
	if ($cutScore1 < 0){
		$cutScore1 = 0;
	}elseif ($cutScore1 > $ug->row['UG_infoScore1']){
		$cutScore1 = $ug->row['UG_infoScore1'];
	}
	if ($cutScore2 < 0){
		$cutScore2 = 0;
	}elseif ($cutScore2 > $ug->row['UG_infoScore2']){
		$cutScore2 = $ug->row['UG_infoScore2'];
	}
	if ($cutScore3 < 0){
		$cutScore3 = 0;
	}elseif ($cutScore3 > $ug->row['UG_infoScore3']){
		$cutScore3 = $ug->row['UG_infoScore3'];
	}

	$beforeURL	= GetUrl::CurrDir();
	$imgUrl		= $beforeURL . InfoImgDir;
	$content	= str_replace($imgUrl,InfoImgAdminDir,$content);

	$type1ID	= 0;
	$type2ID	= 0;
	$type3ID	= 0;
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

	if (strlen($upImgStr)>10 && $img==''){
		$imgArr = explode('|',$upImgStr);
		foreach ($imgArr as $imgName){
			if ($imgName != '' && strpos('|.gif|.jpg|jpeg|.bmp|.png|',substr($imgName,-4)) !== false){
				$img = $imgName;
				break;
			}
		}
	}

	$IF_imgMode = 0;
	if (strlen($img)>4){ $IF_imgMode = 1; }
	
	$isMakeHtml = true;
	$htmlDir = '';

	if ($dataID == 0){
		$tabID = $infoSysArr['IS_tabID'];
	}else{
		$tabID = $DB->GetOne('select IF_tabID from '. OT_dbPref .'info where IF_ID='. $dataID);
	}
	$record = array();
	$record['IF_theme']			= $theme;
	$record['IF_themeMd5']		= md5($theme);
	$record['IF_isOri']			= $isOri;
	$record['IF_revTime']		= TimeDate::Get();
	$record['IF_type']			= 'news';
	$record['IF_source']		= $source;
	$record['IF_writer']		= $writer;
	$record['IF_typeStr']		= $typeStr;
	$record['IF_type1ID']		= $type1ID;
	$record['IF_type2ID']		= $type2ID;
	$record['IF_type3ID']		= $type3ID;
	$record['IF_tabID']			= $tabID;
	if ($tabID == 0){
		$record['IF_content']	= $content;
	}else{
		$record['IF_content']	= '';
	}
	$record['IF_upImgStr']		= $upImgStr;
	$record['IF_themeKey']		= $themeKey;
	$record['IF_contentKey']	= $contentKey;
	$record['IF_pageNum']		= $pageNum;
	$record['IF_pageCount']		= $pageCount;
//	$record['IF_mediaFile']		= $mediaFile;
	$record['IF_file']			= $file;
	$record['IF_fileName']		= $fileName;
	$record['IF_fileStr']		= $fileStr;
	$record['IF_isRenameFile']	= $isRenameFile;
	$record['IF_isUserFile']	= $isUserFile;
	$record['IF_isNew']			= $isNew;
	$record['IF_isHomeThumb']	= $isHomeThumb;
	$record['IF_isThumb']		= $isThumb;
	$record['IF_isImg']			= $isImg;
	$record['IF_isFlash']		= $isFlash;
	$record['IF_isMarquee']		= $isMarquee;
	$record['IF_isRecom']		= $isRecom;
	$record['IF_isTop']			= $isTop;
	$record['IF_imgMode']		= $IF_imgMode;
	$record['IF_img']			= $img;
	$record['IF_voteMode']		= $voteMode;
	$record['IF_isMarkNews']	= $isMarkNews;
	$record['IF_isReply']		= $isReply;
	$record['IF_topAddiID']		= $topAddiID;
	$record['IF_addiID']		= $addiID;
	if ($isCheckUser > -1){
		$record['IF_isCheckUser']	= $isCheckUser;
		$record['IF_score1']		= $score1;
		$record['IF_score2']		= $score2;
		$record['IF_score3']		= $score3;
		$record['IF_cutScore1']		= $cutScore1;
		$record['IF_cutScore2']		= $cutScore2;
		$record['IF_cutScore3']		= $cutScore3;
	}
	$record['IF_userID']		= $UE_ID;
	$record['IF_readNum']		= OT::RndNumTo($infoSysArr['IS_defReadNum1'],$infoSysArr['IS_defReadNum2']);
	if (AppMapBaidu::Jud()){
		$record['IF_isSitemap']		= $infoSysArr['IS_defIsSitemap'];
		$record['IF_isXiongzhang']	= $infoSysArr['IS_defIsXiongzhang'];
	}
	if (AppBase::Jud()){
		$record['IF_template']		= $infoSysArr['IS_defTemplate'];
	}
	if (AppWap::Jud()){
		$record['IF_templateWap']	= $infoSysArr['IS_defTemplateWap'];
	}
	if (AppNewsEnc::Jud()){
		$record['IF_isEnc']			= $isEnc;
		$record['IF_encContent']	= $encContent;
		$record['IF_addition']		= $addition;
	}
	if (AppTopic::Jud()){
		$record['IF_topicID']		= $topicID;
	}

	$newsAddScoreArr = Area::UserScore('newsAdd');

	if ($dataID > 0){
		$alertMode = '修改';

		if ($userSysArr['US_isNewsRev'] == 0){ JS::AlertBackEnd('不允许修改文章，有问题请联系管理员'); }

		$infoexe = $DB->query('select IF_isAudit,IF_infoTypeDir,IF_datetimeDir from '. OT_dbPref .'info where IF_ID='. $dataID .' and IF_userID='. $UE_ID .'');
			if (! $row = $infoexe->fetch()){ JS::AlertBackEnd('记录不存在'); }

		$IF_infoTypeDir = $row['IF_infoTypeDir'];
		$IF_datetimeDir = $row['IF_datetimeDir'];

		if ($userSysArr['US_isNewsRevAudit'] == 1 && strpos($ug->row['UG_event'],'|投稿免审核|') === false){
			$record['IF_isAudit'] = 0;
			$isMakeHtml = false;
		}elseif (strpos($ug->row['UG_event'],'|投稿免审核|') !== false){
			$record['IF_isAudit'] = 1;
		}elseif ($row['IF_isAudit'] == 2){
			$record['IF_isAudit'] = 0;
			$isMakeHtml = false;
		}
	}else{
		$alertMode = '发表';

		if ($userSysArr['US_isNewsAdd'] == 0){ JS::AlertBackEnd('文章发表已关闭，有问题请联系管理员'); }

		$retArr = $ug->InfoTotalNumArr();
			if (! $retArr['res']){
				JS::AlertBackEnd($retArr['note']);
			}

		$retArr = $ug->InfoDayNumArr();
			if (! $retArr['res']){
				JS::AlertBackEnd($retArr['note']);
			}

		if ($userRow['UE_score1'] + $newsAddScoreArr['US_score1'] < 0){
			JS::AlertBackEnd('您当前 '. $userSysArr['US_score1Name'] .'：'. $userRow['UE_score1'] .'，不足以扣除 '. $newsAddScoreArr['US_score1']*(-1));
		}
		if ($userSysArr['US_isScore2'] == 1 && $userRow['UE_score2'] + $newsAddScoreArr['US_score2'] < 0){
			JS::AlertBackEnd('您当前 '. $userSysArr['US_score2Name'] .'：'. $userRow['UE_score2'] .'，不足以扣除 '. $newsAddScoreArr['US_score2']*(-1));
		}
		if ($userSysArr['US_isScore3'] == 1 && $userRow['UE_score3'] + $newsAddScoreArr['US_score3'] < 0){
			JS::AlertBackEnd('您当前 '. $userSysArr['US_score3Name'] .'：'. $userRow['UE_score3'] .'，不足以扣除 '. $newsAddScoreArr['US_score3']*(-1));
		}

		$IF_datetimeDir = Area::DatetimeDirName(TimeDate::Get(),-1) .'/';
		$record['IF_time']			= TimeDate::Get();
		$record['IF_datetimeDir']	= $IF_datetimeDir;
		$record['IF_voteStr']		= '0,0,0,0,0,0,0,0';
		$record['IF_state']			= 1;
		$record['IF_wapState']		= 1;
		if ($userSysArr['US_isNewsAudit'] == 1 && strpos($ug->row['UG_event'],'|投稿免审核|') === false){
			$record['IF_isAudit']	= 0;
			$DB->query('update '. OT_dbPref .'users set UE_newsCount=UE_newsCount+1 where UE_ID='. $UE_ID);
			$isMakeHtml = false;
		}else{
			$record['IF_isAudit']	= 1;
			$record['IF_isGetScore']= 1;
			$record['IF_getScore1']	= $newsAddScoreArr['US_score1'];
			$record['IF_getScore2']	= $newsAddScoreArr['US_score2'];
			$record['IF_getScore3']	= $newsAddScoreArr['US_score3'];

		}

		if ($systemArr['SYS_htmlInfoTypeDir'] == 1){
			if ($typeStr == 'announ'){
				$IF_infoTypeDir = 'announ/';
			}else{
				$IF_infoTypeDir = $DB->GetOne('select IT_htmlName from '. OT_dbPref .'infoType where IT_ID='. $typeCurrID) .'/';
			}
			$record['IF_infoTypeDir']	= $IF_infoTypeDir;
		}
	}

	if ($systemArr['SYS_htmlInfoTypeDir'] == 1 && strlen($IF_infoTypeDir) > 1){ $htmlDir .= $IF_infoTypeDir; }
	if ($systemArr['SYS_htmlDatetimeDir'] > 0 && strlen($IF_datetimeDir) > 1){ $htmlDir .= $IF_datetimeDir; }

	if ($alertMode == '发表'){
		$judResult = $DB->InsertParam('info',$record);
		Area::TabCurrNumAdd();
	}else{
		$judResult = $DB->UpdateParam('info',$record,'IF_ID='. $dataID);
	}

	$writeFileStr = '';
	if ($judResult){
		$alertResult = '成功';
		if ($dataID == 0){ $dataID = $DB->GetOne('select max(IF_ID) from '. OT_dbPref .'info where IF_userID='. $UE_ID); }
		if ($alertMode == '发表'){
			if ($tabID > 0){
				$DB->InsertParam('infoContent'. $tabID, array('IC_ID'=>$dataID, 'IC_content'=>$content));
			}
			if ($userSysArr['US_isNewsAudit'] == 0 || strpos($ug->row['UG_event'],'|投稿免审核|') !== false){
				// 更新积分
				Users::UpdateScore($UE_ID, '+', $newsAddScoreArr['US_score1'], $newsAddScoreArr['US_score2'], $newsAddScoreArr['US_score3'], array('UE_newsCount'=>'UE_newsCount+1'));

				if (AppUserScore::IsAdd($newsAddScoreArr['US_score1'], $newsAddScoreArr['US_score2'], $newsAddScoreArr['US_score3'])){
					$IF_ID = $DB->GetOne('select max(IF_ID) from '. OT_dbPref .'info');
					$scoreArr = array();
					$scoreArr['UM_userID']		= $UE_ID;
					$scoreArr['UM_username']	= $userRow['UE_username'];
					$scoreArr['UM_type']		= 'addInfo';
					$scoreArr['UM_dataID']		= $IF_ID;
					$scoreArr['UM_score1']		= $newsAddScoreArr['US_score1'];
					$scoreArr['UM_score2']		= $newsAddScoreArr['US_score2'];
					$scoreArr['UM_score3']		= $newsAddScoreArr['US_score3'];
					$scoreArr['UM_remScore1']	= $userRow['UE_score1'] + $newsAddScoreArr['US_score1'];
					$scoreArr['UM_remScore2']	= $userRow['UE_score2'] + $newsAddScoreArr['US_score2'];
					$scoreArr['UM_remScore3']	= $userRow['UE_score3'] + $newsAddScoreArr['US_score3'];
					$scoreArr['UM_note']		= '发表文章“'. $theme .'”';
					AppUserScore::AddData($scoreArr);
				}
			}

			if (strlen($img) > 5 && Is::HttpUrl($img) == false){
				$DB->UpdateParam('userFile', array('UF_proID'=>$dataID), 'UF_proID=0 and UF_proType="info" and UF_name='. $DB->ForStr($img));
			}
			if ($fileNum > 0){
				for ($i=1; $i<=$fileNum; $i++){
					$file = OT::PostStr('file'. $i);
					if (strlen($file) > 5 && Is::HttpUrl($file) == false){
						$DB->UpdateParam('userFile', array('UF_proID'=>$dataID), 'UF_proID=0 and UF_proType="info" and UF_name='. $DB->ForStr($file));
					}
				}
			}
			if (strlen($upImgStr) > 8){
				$imgArr = explode('|',$upImgStr);
				foreach ($imgArr as $imgName){
					$DB->UpdateParam('userFile', array('UF_proID'=>$dataID), 'UF_proID=0 and UF_proType="info" and UF_name='. $DB->ForStr($imgName));
				}
			}

		}else{
			if ($tabID > 0){
				$DB->UpdateParam('infoContent'. $tabID, array('IC_content'=>$content), 'IC_ID='. $dataID);
			}
		}

		if ($systemArr['SYS_newsShowUrlMode'] == 'html-2.x' && $isMakeHtml){
			// 纯静态路径下生成静态页
			$makeSec = 3;
			$makeNum = 1;
			$makeStr = '
			<br />生成电脑版静态页：<iframe id="infoIframe'. $dataID .'" name="infoIframe'. $dataID .'" frameborder="0" allowTransparency="true" scrolling="no" style="width:220px;height:20px;" src="makeHtml_deal.php?mudi=newsOne&htmlEachSec=1&htmlEachNum=99&startNum=0&dataID='. $dataID .'&rnd='. time() .'"></iframe>
			';
			if (AppWap::Jud()){
				$makeSec = 5;
				$makeNum = 2;
				$makeStr .= '
				<br />生成手机版静态页：<iframe id="infoIframeWap'. $dataID .'" name="infoIframeWap'. $dataID .'" frameborder="0" allowTransparency="true" scrolling="no" style="width:220px;height:20px;" src="makeHtml_deal.php?mudi=newsOne&mode=wap&htmlEachSec=1&htmlEachNum=99&startNum=0&dataID='. $dataID .'&rnd='. time() .'"></iframe>
				';
			}
			echo('
			<br /><br />
			<meta http-equiv="Content-Type" content="text/html; charset='. OT_Charset .'">
			<table align="center"><tr><td style="font-size:14px;">
			操作'. $alertResult .'，<span id="number" style="color:red;">'. ($makeSec+1) .'</span>秒后返回【文章管理】'. $makeStr .'<br /><br />
			<a href="usersCenter.php?mudi=addNews">继续[新增文章]</a>&ensp;&ensp;&ensp;&ensp;
			<a href="usersCenter.php?mudi=newsManage&page='. $page .'">回到[文章管理]</a>
			</td></tr></table>

			<script language="javascript" type="text/javascript">
			var sec = '. $makeSec .'; num = '. $makeNum .'; calcSkipTime=null;
			function SkipNum(){
				document.getElementById("number").innerHTML = sec;
				if (sec<=0 || num<=0){ document.location.href="usersCenter.php?mudi=newsManage&page='. $page .'";clearInterval(calcSkipTime); }
				sec --;
			}
			function SetSkipNum(){
				calcSkipTime = setInterval("SkipNum()",1000);
			}
			SetSkipNum();
			WindowHeight(0);
			</script>
			');

		}else{
			JS::AlertHrefEnd($alertMode .'文章'. $alertResult .'。\n'. $writeFileStr, 'usersCenter.php?mudi=newsManage&page='. $page .'');
		}
	}else{
		$alertResult = '失败';
		JS::AlertBackEnd($alertMode .'文章'. $alertResult .'。\n'. $writeFileStr);
	}

}



// 删除
function del(){
	global $DB,$UE_ID,$userRow,$userSysArr;

	if ($userSysArr['US_isNewsDel'] == 0){
		die('alert("禁止会员删除文章，有问题请联系管理员");');
	}

	$dataID	= OT::GetInt('dataID');

	$userSqlStr = '';
	$userSqlStr2= '';
	$delrec = $DB->query('select IF_ID,IF_theme,IF_upImgStr,IF_file,IF_fileStr,IF_img,IF_userID,IF_isGetScore,IF_isAudit,IF_getScore1,IF_getScore2,IF_getScore3,IF_tabID from '. OT_dbPref .'info where IF_ID='. $dataID .' and IF_userID='. $UE_ID .'');
		if (! $row = $delrec->fetch()){
			JS::AlertEnd('不存在该记录');
		}
		if ($row['IF_isGetScore'] == 1){
			$remScore1 = $userRow['UE_score1'] - $row['IF_getScore1'];
			$remScore2 = $userRow['UE_score2'] - $row['IF_getScore2'];
			$remScore3 = $userRow['UE_score3'] - $row['IF_getScore3'];
			if ($remScore1 < 0){
				JS::AlertEnd('删除文章要回收获得的 '. $userSysArr['US_score1Name'] .'（'. $row['IF_getScore1'] .'），但您当前 '. $userSysArr['US_score1Name'] .'（'. $userRow['UE_score1'] .'）不足以扣除，禁止删除');
			}
			if ($userSysArr['US_isScore2'] == 1 && $remScore2 < 0){
				JS::AlertEnd('删除文章要回收获得的 '. $userSysArr['US_score2Name'] .'（'. $row['IF_getScore2'] .'），但您当前 '. $userSysArr['US_score2Name'] .'（'. $userRow['UE_score2'] .'）不足以扣除，禁止删除');
			}
			if ($userSysArr['US_isScore3'] == 1 && $remScore3 < 0){
				JS::AlertEnd('删除文章要回收获得的 '. $userSysArr['US_score3Name'] .'（'. $row['IF_getScore3'] .'），但您当前 '. $userSysArr['US_score3Name'] .'（'. $userRow['UE_score3'] .'）不足以扣除，禁止删除');
			}
			// IF_getScore1,IF_getScore2,IF_getScore3 扣分用这个得多少扣多少
			Users::UpdateScore($UE_ID, '-', $row['IF_getScore1'], $row['IF_getScore2'], $row['IF_getScore3'], array());

			if (AppUserScore::IsAdd($row['IF_getScore1'], $row['IF_getScore2'], $row['IF_getScore3'])){
				$scoreArr = array();
				$scoreArr['UM_userID']		= $UE_ID;
				$scoreArr['UM_username']	= $userRow['UE_username'];
				$scoreArr['UM_type']		= 'delInfo';
				$scoreArr['UM_dataID']		= $dataID;
				$scoreArr['UM_score1']		= $row['IF_getScore1']*(-1);
				$scoreArr['UM_score2']		= $row['IF_getScore2']*(-1);
				$scoreArr['UM_score3']		= $row['IF_getScore3']*(-1);
				$scoreArr['UM_remScore1']	= $remScore1;
				$scoreArr['UM_remScore2']	= $remScore2;
				$scoreArr['UM_remScore3']	= $remScore3;
				$scoreArr['UM_note']		= '删除文章回收积分“'. $row['IF_theme'] .'”';
				AppUserScore::AddData($scoreArr);
			}
		}
		if ($row['IF_isAudit'] == 0 || $row['IF_isAudit'] == 2){
			$userSqlStr2 = 'update '. OT_dbPref .'users set UE_newsCount=UE_newsCount-1 where UE_ID='. $row['IF_userID'];
		}
		$IF_upImgStr	= $row['IF_upImgStr'];
		$IF_file		= $row['IF_file'];
		$IF_fileStr		= $row['IF_fileStr'];
		$IF_img			= $row['IF_img'];
		$IF_tabID		= $row['IF_tabID'];
	unset($delrec);

	$judResult = $DB->query('delete from '. OT_dbPref .'info where IF_ID='. $dataID);

	if ($judResult){
		if ($IF_tabID > 0){ $DB->query('delete from '. OT_dbPref .'infoContent'. $IF_tabID .' where IC_ID='. $dataID); }
		Area::DelUserFile('UF_proID='. $dataID .' and UF_proType="info"');
		if (strlen($userSqlStr) > 0){ $DB->query($userSqlStr); }
		if (strlen($userSqlStr2) > 0){ $DB->query($userSqlStr2); }
	}

	echo('$id("data'. $dataID .'").style.display="none";');

}

?>
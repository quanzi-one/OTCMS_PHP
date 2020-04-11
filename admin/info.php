<?php
require(dirname(__FILE__) .'/check.php');
$skin->CheckIframe();

$infoSysArr = Cache::PhpFile('infoSys');
$userSysArr = Cache::PhpFile('userSys');


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
<script language="javascript" type="text/javascript" src="js/inc/trim.js?v='. OT_VERSION .'"></script>
<script language="javascript" type="text/javascript" src="js/info.js?v='. OT_VERSION .'"></script>
');



switch ($mudi){
	case 'add':
		$MB->IsSecMenuRight('alertBack',9,$dataType);
		AddOrRev();
		break;

	case 'rev':
		$MB->IsSecMenuRight('alertBack',10,$dataType);
		AddOrRev();
		break;

	case 'manage':
		$MB->IsSecMenuRight('alertBack',51,$dataType);
		manage();
		break;

	default:
		die('err');
}

$skin->WebBottom();

$MB->Close();
$DB->Close();





// 新增、修改信息
function AddOrRev(){
	global $DB,$MB,$skin,$mudi,$dataType,$dataTypeCN,$systemArr,$infoSysArr,$userSysArr;

	$mudi2			= OT::GetStr('mudi2');
	$dataMode		= OT::GetStr('dataMode');
	$dataModeStr	= OT::GetStr('dataModeStr');
	$backURL		= OT::GetStr('backURL');
	$dataID			= OT::GetInt('dataID');
	
	if ($mudi=='rev'){
		$revexe=$DB->query('select * from '. OT_dbPref .'info where IF_ID='. $dataID);
			if (! $row = $revexe->fetch()){
				JS::AlertBackEnd('无该记录！');		
			}
		$IF_ID			= $row['IF_ID'];
		$IF_time		= $row['IF_time'];
		$IF_isOri		= $row['IF_isOri'];
		$IF_typeStr		= $row['IF_typeStr'];
		$IF_type1ID		= $row['IF_type1ID'];
			AppAdminRightNews::InfoRevCheck($MB->mRightStr, $IF_type1ID, $row['IF_type2ID'], $row['IF_adminID'], $MB->mUserID);
		$IF_source		= $row['IF_source'];
		$IF_writer		= $row['IF_writer'];
		$IF_theme		= $row['IF_theme'];
		$IF_themeStyle	= $row['IF_themeStyle'];
		$IF_URL			= $row['IF_URL'];
		$IF_isEncUrl	= $row['IF_isEncUrl'];
		$IF_template	= $row['IF_template'];
		$IF_templateWap	= $row['IF_templateWap'];
		$IF_themeKey	= $row['IF_themeKey'];
		$IF_contentKey	= $row['IF_contentKey'];
		$IF_tabID		= $row['IF_tabID'];
		if ($row['IF_tabID'] > 0){
			$IF_content	= Area::GetTabContent($row['IF_tabID'], $dataID);
		}else{
			$IF_content	= $row['IF_content'];
		}
		$IF_upImgStr	= $row['IF_upImgStr'];
		$IF_pageNum		= $row['IF_pageNum'];
		$IF_isAudit		= $row['IF_isAudit'];
		$IF_isNew		= $row['IF_isNew'];
		$IF_isHomeThumb	= $row['IF_isHomeThumb'];
		$IF_isThumb		= $row['IF_isThumb'];
		$IF_isImg		= $row['IF_isImg'];
		$IF_isFlash		= $row['IF_isFlash'];
		$IF_isMarquee	= $row['IF_isMarquee'];
		$IF_isRecom		= $row['IF_isRecom'];
		$IF_isTop		= $row['IF_isTop'];
		$IF_img			= $row['IF_img'];
		$IF_fileName	= $row['IF_fileName'];
		$IF_file		= $row['IF_file'];
		$IF_fileStr		= $row['IF_fileStr'];
			if (strlen($IF_fileStr) == 0 && strlen($IF_file) > 0){
				$IF_fileStr = $IF_file .'|'. $IF_fileName .'|';
				$IF_fileName = '';
				$IF_file = '';
			}
		$IF_isRenameFile= $row['IF_isRenameFile'];
		$IF_voteMode	= $row['IF_voteMode'];
		$IF_voteStr		= $row['IF_voteStr'];
		$IF_isMarkNews	= $row['IF_isMarkNews'];
		$IF_isReply		= $row['IF_isReply'];
		$IF_topAddiID	= $row['IF_topAddiID'];
		$IF_addiID		= $row['IF_addiID'];
		$IF_readNum		= $row['IF_readNum'];
		$IF_state		= $row['IF_state'];
		$IF_wapState	= $row['IF_wapState'];

		$beforeURL	= GetUrl::CurrDir(1);
		$imgUrl		= $beforeURL . InfoImgDir;
		$IF_content	= str_replace(InfoImgAdminDir,$imgUrl,$IF_content);

		$IF_userID		= $row['IF_userID'];
		$IF_isCheckUser	= $row['IF_isCheckUser'];
		$IF_userGroupList = $row['IF_userGroupList'];
		$IF_userLevel	= $row['IF_userLevel'];
		$IF_score1		= $row['IF_score1'];
		$IF_score2		= $row['IF_score2'];
		$IF_score3		= $row['IF_score3'];
		$IF_cutScore1	= $row['IF_cutScore1'];
		$IF_cutScore2	= $row['IF_cutScore2'];
		$IF_cutScore3	= $row['IF_cutScore3'];
		$IF_infoTypeDir	= $row['IF_infoTypeDir'];
		$IF_datetimeDir	= $row['IF_datetimeDir'];
		$IF_adminID		= $row['IF_adminID'];
		$IF_addition	= $row['IF_addition'];
		$IF_auditNote	= $row['IF_auditNote'];

		if (AppVideo::Jud()){
			$IF_mediaFile	= $row['IF_mediaFile'];
			$IF_mediaEvent	= $row['IF_mediaEvent'];
		}else{
			$IF_mediaFile = '';
			$IF_mediaEvent = '';
		}
		if (AppBase::Jud()){
			$IF_titleAddi	= $row['IF_titleAddi'];
			$IF_isTitle		= $row['IF_isTitle'];
			$IF_isUserFile	= $row['IF_isUserFile'];
		}else{
			$IF_titleAddi	= '';
			$IF_isTitle		= 0;
			$IF_isUserFile	= 0;
		}
		if (AppMapBaidu::Jud()){
			$IF_isSitemap	= $row['IF_isSitemap'];
			$IF_isXiongzhang= $row['IF_isXiongzhang'];
			$IF_bdPing		= $row['IF_bdPing'];
		}else{
			$IF_isSitemap	= 0;
			$IF_isXiongzhang= 0;
			$IF_bdPing		= 0;
		}
		if (AppTopic::Jud()){
			$IF_topicID		= $row['IF_topicID'];
		}else{
			$IF_topicID		= 0;
		}
		if (AppNewsEnc::Jud()){
			$IF_isEnc		= $row['IF_isEnc'];
			$IF_encContent	= $row['IF_encContent'];
			$IF_encContent	= str_replace(InfoImgAdminDir,$imgUrl,$IF_encContent);
		}else{
			$IF_isEnc		= 0;
			$IF_encContent	= '';
		}

		$mudiCN='修改';
		$submitCN='修 改';
	}else{
		if ($dataID>0){
			$revexe=$DB->query('select * from '. OT_dbPref .'info where IF_ID='. $dataID);
			if ($row = $revexe->fetch()){
				$IF_isOri		= $row['IF_isOri'];
				$IF_typeStr		= $row['IF_typeStr'];
				$IF_type1ID		= $row['IF_type1ID'];
				$IF_source		= $row['IF_source'];
				$IF_writer		= $row['IF_writer'];
				$IF_themeStyle	= $row['IF_themeStyle'];
				$IF_URL			= $row['IF_URL'];
				$IF_isEncUrl	= $row['IF_isEncUrl'];
				$IF_template	= $row['IF_template'];
				$IF_templateWap	= $row['IF_templateWap'];
				$IF_pageNum		= $row['IF_pageNum'];
				$IF_isAudit		= $row['IF_isAudit'];
				$IF_isNew		= $row['IF_isNew'];
				$IF_isHomeThumb	= $row['IF_isHomeThumb'];
				$IF_isThumb		= $row['IF_isThumb'];
				$IF_isImg		= $row['IF_isImg'];
				$IF_isFlash		= $row['IF_isFlash'];
				$IF_isMarquee	= $row['IF_isMarquee'];
				$IF_isRecom		= $row['IF_isRecom'];
				$IF_isTop		= $row['IF_isTop'];
				if ($mudi2=='copy'){
					$IF_theme		= $row['IF_theme'];
					$IF_themeKey	= $row['IF_themeKey'];
					$IF_contentKey	= $row['IF_contentKey'];
					$IF_tabID		= $row['IF_tabID'];
					if ($row['IF_tabID'] > 0){
						$IF_content	= Area::GetTabContent($row['IF_tabID'], $dataID);
					}else{
						$IF_content	= $row['IF_content'];
					}
					$IF_upImgStr	= $row['IF_upImgStr'];
					$IF_img			= $row['IF_img'];
					$IF_fileName	= $row['IF_fileName'];
					$IF_file		= $row['IF_file'];
					$IF_fileStr		= $row['IF_fileStr'];
						if (strlen($IF_fileStr) == 0 && strlen($IF_file) > 0){
							$IF_fileStr = $IF_file .'|'. $IF_fileName .'|';
							$IF_fileName = '';
							$IF_file = '';
						}
					$IF_isRenameFile= $row['IF_isRenameFile'];
					$IF_isSitemap	= $infoSysArr['IS_defIsSitemap'];
					$IF_isXiongzhang= $infoSysArr['IS_defIsXiongzhang'];
					$IF_bdPing		= $infoSysArr['IS_defBdPing'];
					if (AppBase::Jud()){
						$IF_titleAddi	= $row['IF_titleAddi'];
						$IF_isTitle		= $row['IF_isTitle'];
						$IF_isUserFile	= $row['IF_isUserFile'];
					}else{
						$IF_titleAddi	= '';
						$IF_isTitle		= 0;
						$IF_isUserFile	= 0;
					}
				}else{
					$IF_theme		= '';
					$IF_isTitle		= 0;
					$IF_titleAddi	= '';
					$IF_themeKey	= '';
					$IF_contentKey	= '';
					$IF_tabID		= $infoSysArr['IS_tabID'];
					$IF_content		= '';
					$IF_upImgStr	= '';
					$IF_img			= '';
					$IF_fileName	= '';
					$IF_file		= '';
					$IF_fileStr		= '';
					$IF_isRenameFile= 0;
					$IF_isUserFile	= 0;
					if (AppMapBaidu::Jud()){
						$IF_isSitemap	= $row['IF_isSitemap'];
						$IF_isXiongzhang= $infoSysArr['IS_defIsXiongzhang'];
						$IF_bdPing		= $infoSysArr['IS_defBdPing'];
					}else{
						$IF_isSitemap	= 0;
						$IF_isXiongzhang= 0;
						$IF_bdPing		= 0;
					}
				}
				$IF_readNum		= OT::RndNumTo($infoSysArr['IS_defReadNum1'],$infoSysArr['IS_defReadNum2']);
				$IF_voteMode	= $row['IF_voteMode'];
				$IF_voteStr		= $row['IF_voteStr'];
				$IF_isMarkNews	= $row['IF_isMarkNews'];
				$IF_isReply		= $row['IF_isReply'];
				$IF_topAddiID	= $row['IF_topAddiID'];
				$IF_addiID		= $row['IF_addiID'];
				$IF_state		= $row['IF_state'];
				$IF_wapState	= $row['IF_wapState'];
				$IF_userID		= $row['IF_userID'];
				$IF_isCheckUser	= $row['IF_isCheckUser'];
				$IF_userGroupList = $row['IF_userGroupList'];
				$IF_userLevel	= $row['IF_userLevel'];
				$IF_score1		= $row['IF_score1'];
				$IF_score2		= $row['IF_score2'];
				$IF_score3		= $row['IF_score3'];
				$IF_cutScore1	= $row['IF_cutScore1'];
				$IF_cutScore2	= $row['IF_cutScore2'];
				$IF_cutScore3	= $row['IF_cutScore3'];
				$IF_addition	= $row['IF_addition'];
				if (AppVideo::Jud()){
					$IF_mediaFile	= $row['IF_mediaFile'];
					$IF_mediaEvent	= $row['IF_mediaEvent'];
				}else{
					$IF_mediaFile	= '';
					$IF_mediaEvent	= '';
				}
				if (AppTopic::Jud()){
					$IF_topicID		= $row['IF_topicID'];
				}else{
					$IF_topicID		= 0;
				}
				/* if (AppNewsEnc::Jud()){
					$beforeURL	= GetUrl::CurrDir(1);
					$imgUrl		= $beforeURL . InfoImgDir;

					$IF_isEnc		= $row['IF_isEnc'];
					$IF_encContent	= $row['IF_encContent'];
					$IF_encContent	= str_replace(InfoImgAdminDir,$imgUrl,$IF_encContent);
				}else{ */
					$IF_isEnc		= 0;
					$IF_encContent	= '';
				/* } */
			}
			$IF_datetimeDir	= '';
			$dataID = 0;
		}else{
			$IF_ID			= 0;
			$IF_time		= '';
			$IF_isOri		= 0;
			$IF_topicID		= $infoSysArr['IS_defTopicID'];
			$IF_typeStr		= '';
			$IF_type1ID		= 0;
			$IF_source		= '';
			$IF_writer		= '';
			$IF_theme		= '';
			$IF_themeStyle	= '';
			$IF_URL			= '';
			$IF_isEncUrl	= 0;
			$IF_isTitle		= 0;
			$IF_titleAddi	= '';
			$IF_template	= $infoSysArr['IS_defTemplate'];
			$IF_templateWap	= $infoSysArr['IS_defTemplateWap'];
			$IF_themeKey	= '';
			$IF_contentKey	= '';
			$IF_tabID		= $infoSysArr['IS_tabID'];
			$IF_content		= '';
			$IF_upImgStr	= '';
			$IF_pageNum		= 0;
			$IF_mediaFile	= '';
			$IF_mediaEvent	= '|autoPlay|mediaImg|';
			$IF_isAudit		= $infoSysArr['IS_defIsAudit'];
			$IF_isNew		= $infoSysArr['IS_defIsNew'];
			$IF_isHomeThumb	= $infoSysArr['IS_defIsHomeThumb'];
			$IF_isThumb		= $infoSysArr['IS_defIsThumb'];
			$IF_isImg		= $infoSysArr['IS_defIsImg'];
			$IF_isFlash		= $infoSysArr['IS_defIsFlash'];
			$IF_isMarquee	= $infoSysArr['IS_defIsMarquee'];
			$IF_isRecom		= $infoSysArr['IS_defIsRecom'];
			$IF_isTop		= $infoSysArr['IS_defIsTop'];
			$IF_addition	= $infoSysArr['IS_addition'];
			$IF_img			= '';
			$IF_fileName	= '';
			$IF_file		= '';
			$IF_fileStr		= '';
			$IF_isRenameFile= 0;
			$IF_isUserFile	= 0;
			$IF_voteMode	= $infoSysArr['IS_defVoteMode'];
			$IF_voteStr		= $infoSysArr['IS_defVoteStr'];
			$IF_isMarkNews	= $infoSysArr['IS_defMarkNews'];
			$IF_isReply		= $infoSysArr['IS_defIsReply'];
			$IF_topAddiID	= $infoSysArr['IS_defTopAddiID'];
			$IF_addiID		= $infoSysArr['IS_defAddiID'];
			$IF_readNum		= OT::RndNumTo($infoSysArr['IS_defReadNum1'],$infoSysArr['IS_defReadNum2']);
			$IF_state		= 1;
			$IF_wapState	= 1;
			$IF_userID		= 0;
			$IF_isCheckUser	= 0;
			$IF_userGroupList = '';
			$IF_userLevel	= 0;
			$IF_score1		= 0;
			$IF_score2		= 0;
			$IF_score3		= 0;
			$IF_cutScore1	= 0;
			$IF_cutScore2	= 0;
			$IF_cutScore3	= 0;
			$IF_isEnc		= 0;
			$IF_encContent	= '';
			$IF_infoTypeDir	= '';
			$IF_datetimeDir	= '';
			$IF_adminID		= 0;
			$IF_isSitemap	= $infoSysArr['IS_defIsSitemap'];
			$IF_isXiongzhang= $infoSysArr['IS_defIsXiongzhang'];
			$IF_bdPing		= $infoSysArr['IS_defBdPing'];
		}
		$IF_auditNote	= '';

		$mudiCN='新增';
		$submitCN='新 增';
	}

	$isSaveContImg = strpos($infoSysArr['IS_addition'],'|isSaveContImg|')!==false ? 1 : 0;
	if (empty($IF_time)){ $IF_time=TimeDate::Get(); }else{ $IF_time=TimeDate::Get('datetime',$IF_time); }

	$voteItemArr=array(0,0,0,0,0,0,0,0);
	$voteArr = explode(',',$IF_voteStr);
	for ($i=0; $i<count($voteArr); $i++){
		$voteItemArr[$i] = intval($voteArr[$i]);
	}

	$themeStyle_color	= Str::GetMark($IF_themeStyle,'color:',';');
	$themeStyle_b		= Str::GetMark($IF_themeStyle,'font-weight:',';');

	$moreStr = '';

	if ($mudi=='rev'){
		echo('<div onclick="history.back();" class="font2_1 padd8 pointer">&lt;&lt;&ensp;【返回上级】</div>');
	}

	echo('
	<form id="dealForm" name="dealForm" method="post" action="info_deal.php?mudi='. $mudi .'&nohrefStr=close" onsubmit="return CheckForm()">
	<input type="hidden" id="dataID" name="dataID" value="'. $dataID .'" />
	<input type="hidden" id="dataType" name="dataType" value="'. $dataType .'" />
	<input type="hidden" id="dataTypeCN" name="dataTypeCN" value="'. $dataTypeCN .'" />
	<input type="hidden" id="dataMode" name="dataMode" value="'. $dataMode .'" />
	<input type="hidden" id="dataModeStr" name="dataModeStr" value="'. $dataModeStr .'" />
	');
	if ($backURL != ''){
		echo('<input type="hidden" id="backURL" name="backURL" value="'. $backURL .'" />');
	}else{
		echo('<script language="javascript" type="text/javascript">document.write(\'<input type="hidden" id="backURL" name="backURL" value="\'+ document.location.href +\'" />\')</script>');
	}

	$moreArea = $infoSysArr['IS_moreArea'];
	echo('
	<div class="tabMenu">
	<ul>
	   <li rel="tabInfo" class="selected">'. $mudiCN . $dataTypeCN .'</li>
	   <li rel="tabFunc" '. (strlen($moreArea)<3?'style="display:none;"':"") .'>更多选项</li>
	</ul>
	</div>

	<div class="tabMenuArea">
		<table id="tabInfo" cellpadding="0" cellspacing="0" summary="" class="padd5td">
		<tr><td style="width:150px;"></td><td></td></tr>
		');
		if ($mudi == 'rev' && $IF_adminID > 0){
			$adminName = $DB->GetOne('select MB_realName from '. OT_dbPref .'member where MB_ID='. $IF_adminID);
			if (empty($adminName)){ $adminName = '[可能已不存在]'; }
			echo('
			<tr>
				<td align="right" style="color:red;">文章发布者：</td>
				<td align="left">'. $adminName .'</td>
			</tr>
			');
		}

		echo('
		<tr>
			<td align="right">发布时间：</td>
			<td align="left">
				<input type="text" id="time" name="time" size="22" style="width:170px;" value="'. $IF_time .'" onfocus=\'WdatePicker({dateFmt:"yyyy-MM-dd HH:mm:ss"})\' class="Wdate" />
				&ensp;<label><input type="checkbox" name="isOri" value="1" '. Is::Checked($IF_isOri,1) .' />原创</label>
				&ensp;'. $skin->TishiBox('原创打钩，会根据百度原创保护 星火计划2.0 在页头增加原创标签') .'
				');
				if ($systemArr['SYS_htmlDatetimeDir']>0){
					echo('&ensp;<label><input type="checkbox" name="isChangeDatetimeDir" value="1" />更新时间静态目录名('. $IF_datetimeDir .')</label>');
				}
			echo('
			</td>
		</tr>
		');
		$isAuditStr = $auditNoteStr = '';
		if ($IF_userID > 0){
			$userStr = '';
			$userexe=$DB->query('select UE_username,UE_realname from '. OT_dbPref .'users where UE_ID='. $IF_userID);
			if (! $row = $userexe->fetch()){
				$userStr = '[不存在]';
			}else{
				$userStr = $row['UE_username'] .'('. $row['UE_realname'] .')';
			}
			echo('
			<tr>
				<td align="right" style="color:red;">发表会员：</td>
				<td align="left">'. $userStr .'</td>
			</tr>
			');

			$isAuditStr = '<select id="isAudit" name="isAudit" onchange="CheckAudit()">
							<option value="1" '. Is::Selected($IF_isAudit,1) .'>已审核</option>
							<option value="0" '. Is::Selected($IF_isAudit,0) .'>未审核</option>
							<option value="2" '. Is::Selected($IF_isAudit,2) .'>被拒绝</option>
						</select>&ensp;&ensp;';
			$auditNoteStr = '
				<tr id="auditBox" style="display:none;">
					<td align="right" valign="top" style="padding-top:6px;">未审核通过原因：</td>
					<td align="left">
						<input type="text" id="auditNote" name="auditNote" size="50" style="width:300px;" value="'. $IF_auditNote .'" />
						<span style="color:red">（该选项仅对前台会员投稿显示说明 未审核 原因）</span>
					</td>
				</tr>
				';
		}else{
			$isAuditStr = '<label title="已审核的文章才会在前台显示"><input type="checkbox" id="isAudit" name="isAudit" onclick="CheckAddition()" value="1" '. Is::Checked($IF_isAudit,1) .' />已审核</label>&ensp;&ensp;';
		}

		echo('
		<tr>
			<td align="right">'. Skin::RedSign() .'文章标题：</td>
			<td align="left">
				<input type="text" id="theme" name="theme" size="50" style="width:380px;'. $IF_themeStyle .'" value="'. Str::MoreReplace($IF_theme,'input') .'" />&ensp;
				'. AppBase::InfoThemeB($themeStyle_b,$themeStyle_color) .'
			</td>
		</tr>
		');
		$currStr = AppBase::InfoTrBox1($IF_titleAddi, $IF_isTitle);
		InfoItem($moreArea,'titleAddi',$moreStr,$currStr);

		$currStr = '
			<tr class="infoBox">
				<td align="right">来源：</td>
				<td align="left">
					<input type="text" id="source" name="source" size="50" style="width:380px;" value="'. Str::MoreReplace($IF_source,'input') .'" />
					&ensp;&lt;=&ensp;
					<select id="sourceItem" name="sourceItem" onchange=\'ToSource("")\'>
					'. Adm::TypeOptionList('source', '在【文章来源管理】里设置候选项') .'
					</select>
				</td>
			</tr>
			';
		InfoItem($moreArea,'source',$moreStr,$currStr);

		$currStr = '
			<tr class="infoBox">
				<td align="right">作者：</td>
				<td align="left">
					<input type="text" id="writer" name="writer" size="50" style="width:380px;" value="'. Str::MoreReplace($IF_writer,'input') .'" />
					&ensp;&lt;=&ensp;
					<select id="writerItem" name="writerItem" onchange=\'ToWriter("")\'>
					'. Adm::TypeOptionList('writer', '在【文章作者管理】里设置候选项', array('realname'=>$MB->mRealname)) .'
					</select>
				</td>
			</tr>
			';
		InfoItem($moreArea,'writer',$moreStr,$currStr);

		$currStr = '
			<tr>
				<td align="right">外部链接：</td>
				<td align="left">
					<input type="text" id="webURL" name="webURL" size="50" style="width:380px;" value="'. $IF_URL .'" onkeyup="CheckWebUrl()" />
					'. AppTaobaoke::InfoEncUrl($IF_isEncUrl) .'
					&ensp;&ensp;'. $skin->TishiBox('非外部链接请留空。') .'
				</td>
			</tr>
			';
		InfoItem($moreArea,'webURL',$moreStr,$currStr);

		echo('
		<tr>
			<td align="right">'. Skin::RedSign() .'栏目：</td>
			<td align="left">
				<select id="typeStr" name="typeStr">
				<option value=""></option>
				');
				$aarnStr = AppAdminRightNews::InfoItem1($MB->mRightStr, $IF_typeStr);
				if (strlen($aarnStr) > 5){
					echo($aarnStr);
				}else{
					echo('
					<option value="announ" '. Is::Selected($IF_typeStr,'announ') .'>0、'. $systemArr['SYS_announName'] .'</option>
					');
					$typeNum = 0;
					$typeexe=$DB->query('select IT_ID,IT_theme,IT_mode from '. OT_dbPref ."infoType where IT_state=1 and IT_level=1 and IT_mode in ('item','url','web') order by IT_rank ASC");
					while ($row = $typeexe->fetch()){
						$typeNum ++;
						$type2exe=$DB->query('select IT_ID,IT_theme,IT_mode from '. OT_dbPref ."infoType where IT_state=1 and IT_level=2 and IT_fatID=". $row['IT_ID'] ." and IT_mode='item' order by IT_rank ASC");
						// if (! $row2 = $type2exe->fetch()){
							echo('<option value=",'. $row['IT_ID'] .'," '. Is::InstrSelected($IF_typeStr,','. $row['IT_ID'] .',') .'>'. $typeNum .'、'. $row['IT_theme'] . InfoTypeAddi($row['IT_mode']) .'</option>');
						// }else{
						//	echo('<optgroup label="'. $typeNum .'、'. $row['IT_theme'] .'" style="font-weight:normal;"></optgroup>');
							while ($row2 = $type2exe->fetch()){
								echo('<option value=",'. $row['IT_ID'] .','. $row2['IT_ID'] .'," '. Is::InstrSelected($IF_typeStr,','. $row2['IT_ID'] .',') .'>&ensp;&ensp;&ensp;┣&ensp;'. $row2['IT_theme'] .'</option>');
							}
						// }
					}
				}
				echo('
				</select>&ensp;
				');
				if ($systemArr['SYS_htmlInfoTypeDir']==1 && $mudiCN=='修改'){
					echo('
					<label><input type="checkbox" name="isChangeInfoTypeDir" value="1" />更新栏目静态目录名('. $IF_infoTypeDir .')</label>
					');
				}
				echo('
				&ensp;&ensp;'. $skin->TishiBox('如果有二级栏目,一级栏目不能选择') .'
			</td>
		</tr>
		');

		$currStr = AppBase::InfoTrBox2($IF_template) . AppWap::InfoTrBox2($IF_templateWap);
		InfoItem($moreArea,'template',$moreStr,$currStr);

		echo('
		<tr class="infoBox">
			<td align="right">内容库：</td>
			<td align="left">
				<input type="hidden" id="oldTabID" name="oldTabID" value="'. $IF_tabID .'" />
				<select id="tabID" name="tabID">
				<option value="0">自身表</option>
				');
				$maxNewsNum = 0;
				$tabArr = $DB->GetTabArr('xiao');
				for ($i=1; $i<=$infoSysArr['IS_tabNum']; $i++){
					if (in_array(strtolower(OT_dbPref) .'infocontent'. $i, $tabArr)){
						$maxNewsNum = $DB->GetOne('select count(IC_ID) from '. OT_dbPref .'infoContent'. $i);
						echo('<option value="'. $i .'" '. Is::Selected($IF_tabID,$i) .'>内容表'. $i .'（'. $maxNewsNum .'篇）</option>');
					}
				}
				echo('
				</select>
				&ensp;'. $skin->TishiBox('把文章内容存放在单独内容表，以减少文章总表容量大小压力。') .'
			</td>
		</tr>
		<tr class="infoBox">
			<td align="right" valign="top" style="padding-top:6px;">'. Skin::RedSign() .'内容：</td>
			<td align="left">
				<textarea id="content" name="content" cols="40" rows="4" style="width:650px;height:350px;" class="text" onclick=\'LoadEditor("content",0,0,"");\' title="点击开启编辑器模式">'. Str::MoreReplace($IF_content,'textarea') .'</textarea>
				<script language="javascript" type="text/javascript">LoadEditor("content",0,0,"");</script>
				<div>
				<input type="hidden" id="imgDir" name="imgDir" value="'. InfoImgDir .'" />
				<input type="hidden" id="imgAdminDir" name="imgAdminDir" value="'. InfoImgAdminDir .'" />
				<input type="hidden" id="upImgStr" name="upImgStr" value="'. $IF_upImgStr .'" />
				<input type="button" value="上传图片载入编辑器" onclick=\'OT_OpenUpImg("editor","content","","")\' />
				&ensp;<input type="button" onclick=\'InsertStrToEditor("content","[OT_page]")\' value="插入分页符" />
				&ensp;<input type="button" onclick=\'SetEditorHtml("content",GetEditorHTML("content").replace(/<a (.*?)>(.*?)<\/a>/gi,"\$2"))\' value="清除所有超链接" />
				'. AppBase::InfoSaveContImg($isSaveContImg) .'
				</div>
			</td>
		</tr>
		<tr class="infoBox">
			<td align="right">自动分页字数：</td>
			<td align="left">
				<input type="text" id="pageNum" name="pageNum" size="50" style="width:50px;" value="'. $IF_pageNum .'" />
				&ensp;&ensp;'. $skin->TishiBox('填写每页要显示的字数（而不是填分多少页）；如果在内容中加入了手动分页符或不想分页,请填写0或留空') .'
			</td>
		</tr>
		<tr class="infoBox">
			<td align="right">关键词(标签)：</td>
			<td align="left">
				<input type="text" id="themeKey" name="themeKey" size="50" style="width:380px;" value="'. $IF_themeKey .'" />
				&ensp;&ensp;<a href="javascript:void(0);" onclick=\'GetKeyWord("fc");return false;\' class="font1_2" style="text-decoration:underline;">分词获取</a>
				<!-- &ensp;&ensp;<a href="javascript:void(0);" onclick=\'GetKeyWord("dz");return false;\' class="font1_2" style="text-decoration:underline;">网络获取</a> -->
				&ensp;&ensp;<a href="javascript:void(0);" onclick=\'GetKeyWord("");return false;\' class="font1_2" style="text-decoration:underline;">本地获取</a>
				&ensp;<span id="onloadThemeKey" class="font3_2"></span>
				&ensp;'. $skin->TishiBox('多个关键词用空格、竖杆“|”或逗号“,”隔开；分词获取：分析文章中各种词汇出现的频率，挑选频率最高5个词汇；本地获取：通过本地词库（网站目录/inc/keyWord.txt，可自行添加需要的关键词）分析标题，筛选关键词') .'
			</td>
		</tr>
		<tr>
			<td align="right" valign="top" style="padding-top:6px;">内容摘要：</td>
			<td align="left">
				<textarea id="contentKey" name="contentKey" rows="5" cols="40" style="width:380px; height:80px;">'. $IF_contentKey .'</textarea>
				&ensp;<a href="javascript:void(0);" onclick="ToContentKey();return false;" class="font1_2" style="text-decoration:underline;">自动获取</a>
				&ensp;&ensp;'. $skin->TishiBox('若为空则自动获取') .'
			</td>
		</tr>
		'. AppVideo::InfoTrBox1($IF_mediaFile, $IF_mediaEvent, $IF_addition) .'
		<tr>
			<td align="right">文章属性：</td>
			<td align="left">
				'. $isAuditStr .'
				<label title="出现在前台最新消息处"><input type="checkbox" id="isNew" name="isNew" onclick="CheckAddition()" value="1" '. Is::Checked($IF_isNew,1) .' />最新消息</label>&ensp;&ensp;
				<label title="首页栏目显示图片文章需要此属性，同时所属栏目【在首页显示图片文章】需要开启"><input type="checkbox" id="isHomeThumb" name="isHomeThumb" onclick="CheckAddition()" value="1" '. Is::Checked($IF_isHomeThumb,1) .' />首页缩略图</label>&ensp;&ensp;
				<label title="列表页中显示"><input type="checkbox" id="isThumb" name="isThumb" onclick="CheckAddition()" value="1" '. Is::Checked($IF_isThumb,1) .' />缩略图</label>&ensp;&ensp;
				<label title="首页左上角幻灯片"><input type="checkbox" id="isFlash" name="isFlash" onclick="CheckAddition()" value="1" '. Is::Checked($IF_isFlash,1) .' />幻灯片</label>&ensp;&ensp;
				<label title="首页中部滚动图片"><input type="checkbox" id="isImg" name="isImg" onclick="CheckAddition()" value="1" '. Is::Checked($IF_isImg,1) .' />滚动图片</label>&ensp;&ensp;
				<label title="页头滚动信息"><input type="checkbox" id="isMarquee" name="isMarquee" onclick="CheckAddition()" value="1" '. Is::Checked($IF_isMarquee,1) .' />滚动信息</label>&ensp;&ensp;
				<label title="出现在精彩推荐/本类推荐里"><input type="checkbox" id="isRecom" name="isRecom" onclick="CheckAddition()" value="1" '. Is::Checked($IF_isRecom,1) .' />推荐</label>&ensp;&ensp;
				<label title="出现在最新消息第一条及列表页前几条"><input type="checkbox" id="isTop" name="isTop" onclick="CheckAddition()" value="1" '. Is::Checked($IF_isTop,1) .' />置顶</label>&ensp;&ensp;
			</td>
		</tr>
		'. $auditNoteStr .'
		<tr id="imgBox" style="display:none;">
			<td align="right" valign="top" style="padding-top:6px;">缩略图/图片：</td>
			<td align="left">
				<input type="text" id="img" name="img" size="50" style="width:300px;" value="'. $IF_img .'" onkeyup="CheckSaveImg()" />
				'. AppBase::InfoSaveImg(0) .'
				<span style="position:relative;"><span style="position:absolute; left:-200px; top:22px;"><img id="imgView" src="" width="200" style="display:none;" onerror=\'this.src="images/noPic2.gif";\' /></span></span>
				<input type="button" onclick=\'OT_OpenUpImg("input","img","info","")\' value="上传图片" />
				&ensp;<a href="javascript:void(0);" onclick="GetEditorImg();return false;" class="font1_2" style="text-decoration:underline;">从编辑器中获取</a>
				&ensp;&ensp;'. $skin->TishiBox('可使用远程图片http://；如果作用于flash幻灯片，缩略图图片格式必须是JPG，不然显示不出来。') .'
				<div id="editorImgBox"></div>
			</td>
		</tr>
		');

		// if (strlen($IF_fileStr) > 0){
		$fileArr = explode('<arr>', $IF_fileStr .'<arr><arr><arr><arr><arr><arr><arr><arr>');
		$fileNum = count(array_filter($fileArr));
		$fileOption = $fileList = '';
		for ($i=1; $i<=9; $i++){
			$itemArr = explode('|', $fileArr[$i-1] .'||');
			$fileOption .= '<option value="'. $i .'" '. Is::Selected($fileNum,$i) .'>'. $i .'</option>';
			$fileList .= '
				<div id="fileBox'. $i .'" style="padding-top:6px;line-height:2;display:none;">
					文件'. $i .'：<input type="text" id="file'. $i .'" name="file'. $i .'" size="50" style="width:350px;" value="'. $itemArr[0] .'" />
					<input type="button" onclick=\'OT_OpenUpFile("input","file'. $i .'","download","")\' value="上传附件" />
					<br />
					<span class="font1_2d">名称'. $i .'：</span><input type="text" id="fileName'. $i .'" name="fileName'. $i .'" size="50" style="width:350px;" value="'. $itemArr[1] .'" />
				</div>
				';
		}
		$currStr = '
			<tr class="infoBox">
				<td align="right" valign="top" style="padding-top:6px;">附件：</td>
				<td align="left">
					<div>
						数量：<select id="fileNum" name="fileNum" onchange="CheckFile();">
							<option value="0">0</option>
							'. $fileOption .'
						</select>
						<!-- &ensp;&ensp;<label><input type="checkbox" id="isRenameFile" name="isRenameFile" value="1" '. Is::Checked($IF_isRenameFile,1) .' />下载文件重命名</label>'. $skin->TishiBox('部分浏览器会异常，不建议；仅对本地附件有效') .' -->
						'. AppBase::InfoUserFile($IF_isUserFile) .'
						&ensp;&ensp;'. $skin->TishiBox('附件文件：可上传本地文件或者使用远程文件地址http://；附件名称：可选项') .'
					</div>
					'. $fileList .'
				</td>
			</tr>
			';
		/* }else{
			$currStr = '
				<tr class="infoBox">
					<td align="right">附件文件：</td>
					<td align="left">
						<input type="text" id="file" name="file" size="50" style="width:380px;" value="'. $IF_file .'" />
						<input type="button" onclick=\'OT_OpenUpFile("input","file","download","")\' value="上传附件" />
						&ensp;&ensp;'. $skin->TishiBox('可上传本地文件或者使用远程文件地址http://') .'
					</td>
				</tr>
				<tr class="infoBox">
					<td align="right">附件名称：</td>
					<td align="left">
						<input type="text" id="fileName" name="fileName" size="50" style="width:180px;" value="'. $IF_fileName .'" />'. $skin->TishiBox('可选') .'
						<!-- &ensp;&ensp;<label><input type="checkbox" id="isRenameFile" name="isRenameFile" value="1" '. Is::Checked($IF_isRenameFile,1) .' />下载文件重命名</label>'. $skin->TishiBox('部分浏览器会异常，不建议；仅对本地附件有效') .' -->
						'. AppBase::InfoUserFile($IF_isUserFile) .'
					</td>
				</tr>
				';
		} */
		InfoItem($moreArea,'file',$moreStr,$currStr);

		$topAddiStr = $addiStr = '';
		$addiexe=$DB->query('select IW_ID,IW_theme,IW_state from '. OT_dbPref ."infoWeb where IW_type='news' order by IW_state DESC,IW_rank ASC");
		while ($row = $addiexe->fetch()){
			if ($row['IW_state']==1){ $stateStr=''; }else{ $stateStr=' (隐藏)'; }
			$topAddiStr .= '<option value="'. $row['IW_ID'] .'" '. Is::Selected($IF_topAddiID,$row['IW_ID']) .'>'. $row['IW_theme'] . $stateStr .'</option>';
			$addiStr .= '<option value="'. $row['IW_ID'] .'" '. Is::Selected($IF_addiID,$row['IW_ID']) .'>'. $row['IW_theme'] . $stateStr .'</option>';
		}
		unset($addiexe);

		$currStr = '
			<tr class="infoBox">
				<td align="right">正文头附加内容：</td>
				<td align="left">
					<select id="topAddiID" name="topAddiID">
					<option value="0">无</option>
					'. $topAddiStr .'
					</select>&ensp;
					&ensp;&ensp;'. $skin->TishiBox('放在内容头部，如通知、申明；（文章管理-附加内容管理）') .'
				</td>
			</tr>
			';
		InfoItem($moreArea,'topAddiID',$moreStr,$currStr);

		$currStr = '
			<tr class="infoBox">
				<td align="right">正文尾附加内容：</td>
				<td align="left">
					<select id="addiID" name="addiID">
					<option value="0">无</option>
					'. $addiStr .'
					</select>&ensp;
					&ensp;&ensp;'. $skin->TishiBox('放在内容最下面，如转载申明；（文章管理-附加内容管理）') .'
				</td>
			</tr>
			';
		InfoItem($moreArea,'addiID',$moreStr,$currStr);

		if ($infoSysArr['IS_isNewsVote']>0){
			$currStr = '
				<tr class="infoBox">
					<td align="right">投票方式：</td>
					<td align="left">
						<label><input type="radio" id="voteMode0" name="voteMode" onclick="CheckVote()" value="0" '. Is::Checked($IF_voteMode,0) .' />关闭</label>&ensp;&ensp;
						<label><input type="radio" id="voteMode1" name="voteMode" onclick="CheckVote()" value="1" '. Is::Checked($IF_voteMode,1) .' />心情</label>&ensp;&ensp;
						<label><input type="radio" id="voteMode2" name="voteMode" onclick="CheckVote()" value="2" '. Is::Checked($IF_voteMode,2) .' />顶踩</label>&ensp;&ensp;
						<label><input type="radio" id="voteMode11" name="voteMode" onclick="CheckVote()" value="11" '. Is::Checked($IF_voteMode,11) .' />百度喜欢按钮</label>&ensp;&ensp;
					</td>
				</tr>
				<tr class="infoBox" id="vote1Box" style="display:none;">
					<td align="right">心情投票：</td>
					<td align="left">
						支持<input type="text" id="voteItem1" name="voteItem1" size="50" style="width:30px;" value="'. $voteItemArr[0] .'" />&ensp;&ensp;
						感动<input type="text" id="voteItem2" name="voteItem2" size="50" style="width:30px;" value="'. $voteItemArr[1] .'" />&ensp;&ensp;
						惊讶<input type="text" id="voteItem3" name="voteItem3" size="50" style="width:30px;" value="'. $voteItemArr[2] .'" />&ensp;&ensp;
						同情<input type="text" id="voteItem4" name="voteItem4" size="50" style="width:30px;" value="'. $voteItemArr[3] .'" />&ensp;&ensp;
						流汗<input type="text" id="voteItem5" name="voteItem5" size="50" style="width:30px;" value="'. $voteItemArr[4] .'" />&ensp;&ensp;
						鄙视<input type="text" id="voteItem6" name="voteItem6" size="50" style="width:30px;" value="'. $voteItemArr[5] .'" />&ensp;&ensp;
						愤怒<input type="text" id="voteItem7" name="voteItem7" size="50" style="width:30px;" value="'. $voteItemArr[6] .'" />&ensp;&ensp;
						难过<input type="text" id="voteItem8" name="voteItem8" size="50" style="width:30px;" value="'. $voteItemArr[7] .'" />&ensp;&ensp;
					</td>
				</tr>
				<tr class="infoBox" id="vote2Box" style="display:none;">
					<td align="right">顶踩投票：</td>
					<td align="left">
						顶一下<input type="text" id="voteItem11" name="voteItem11" size="50" style="width:30px;" value="'. $voteItemArr[0] .'" />&ensp;&ensp;
						踩一下<input type="text" id="voteItem12" name="voteItem12" size="50" style="width:30px;" value="'. $voteItemArr[1] .'" />&ensp;&ensp;
					</td>
				</tr>
				';
			InfoItem($moreArea,'voteMode',$moreStr,$currStr);
		}

		$currStr = '
			<tr class="infoBox">
				<td align="right">相关文章：</td>
				<td align="left">
					<label><input type="radio" name="isMarkNews" value="1" '. Is::Checked($IF_isMarkNews,1) .' />开启</label>&ensp;&ensp;
					<label><input type="radio" name="isMarkNews" value="0" '. Is::Checked($IF_isMarkNews,0) .' />关闭</label>&ensp;&ensp;
				</td>
			</tr>
			';
		InfoItem($moreArea,'isMarkNews',$moreStr,$currStr);

		if ($infoSysArr['IS_isNewsReply']>0){
			$currStr = '
				<tr class="infoBox">
					<td align="right">评论区：</td>
					<td align="left">
						<label><input type="radio" name="isReply" value="1" '. Is::Checked($IF_isReply,1) .' />开启</label>&ensp;&ensp;
						<label><input type="radio" name="isReply" value="10" '. Is::Checked($IF_isReply,10) .' />仅限会员</label>&ensp;&ensp;
						<label><input type="radio" name="isReply" value="0" '. Is::Checked($IF_isReply,0) .' />关闭</label>&ensp;&ensp;
					</td>
				</tr>
				';
			InfoItem($moreArea,'isReply',$moreStr,$currStr);
		}

		$currStr = AppTopic::InfoTrBox1($IF_topicID);
		InfoItem($moreArea,'topicID',$moreStr,$currStr);

		$currStr = AppMapBaidu::InfoTrBox1($IF_isSitemap, $IF_isXiongzhang, $IF_bdPing);
		InfoItem($moreArea,'bdPing',$moreStr,$currStr);

//		$currStr = AppBase::InfoTrBox3($IF_addition);
//		InfoItem($moreArea,'addition',$moreStr,$currStr);

		$currStr = '
			<tr>
				<td align="right">阅读量：</td>
				<td align="left"><input type="text" id="readNum" name="readNum" size="50" style="width:50px;" value="'. $IF_readNum .'" /></td>
			</tr>
			';
		InfoItem($moreArea,'readNum',$moreStr,$currStr);

		$currStr = '
			<tr>
				<td align="right">电脑版状态：</td>
				<td align="left">
					<label><input type="radio" name="state" value="1" '. Is::Checked($IF_state,1) .' />显示</label>&ensp;&ensp;
					<label><input type="radio" name="state" value="0" '. Is::Checked($IF_state,0) .' />隐藏</label>&ensp;&ensp;
				</td>
			</tr>
			<tr>
				<td align="right">手机版状态：</td>
				<td align="left">
					<label><input type="radio" name="wapState" value="1" '. Is::Checked($IF_wapState,1) .' />显示</label>&ensp;&ensp;
					<label><input type="radio" name="wapState" value="0" '. Is::Checked($IF_wapState,0) .' />隐藏</label>&ensp;&ensp;
				</td>
			</tr>
			';
		InfoItem($moreArea,'state',$moreStr,$currStr);

		$currStr = '
			<tr class="infoBox">
				<td align="right">限制/付费阅读：</td>
				<td align="left">
					<label><input type="radio" id="isCheckUser1" name="isCheckUser" value="1" '. Is::Checked($IF_isCheckUser,1) .' onclick="CheckIsCheckUser()" />仅限会员阅读</label>&ensp;&ensp;
					'. AppNewsVerCode::InfoOption1($IF_isCheckUser) .'
					<label><input type="radio" id="isCheckUser0" name="isCheckUser" value="0" '. Is::Checked($IF_isCheckUser,0) .' onclick="CheckIsCheckUser()" />关闭</label>&ensp;&ensp;
				</td>
			</tr>
			';
		InfoItem($moreArea,'isCheckUser',$moreStr,$currStr);
		echo('
		</table>

		<div id="tabFunc" style="display:none;">
		<table cellpadding="0" cellspacing="0" summary="" class="padd5td">
		<tr><td style="width:150px;"></td><td></td></tr>
		'. $moreStr .'
		</table>
		</div>
		');

		if ($userSysArr['US_isScore1'] == 1){ $score1Style = ''; }else{ $score1Style = 'display:none;'; }
		if ($userSysArr['US_isScore2'] == 1){ $score2Style = ''; }else{ $score2Style = 'display:none;'; }
		if ($userSysArr['US_isScore3'] == 1){ $score3Style = ''; }else{ $score3Style = 'display:none;'; }
		echo('
		<table id="checkUserBox" align="left" cellpadding="0" cellspacing="0" summary="" class="infoBox padd3" style="margin-top:10px;display:none;">
		<tr>
			<td style="width:150px;"></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tbody id="userScoreBox">
		<tr>
			<td align="right"></td>
			<td align="center" style="width:55px;'. $score1Style .'">'. $userSysArr['US_score1Name'] .'</td>
			<td align="center" style="width:55px;'. $score2Style .'">'. $userSysArr['US_score2Name'] .'</td>
			<td align="center" style="width:55px;'. $score3Style .'">'. $userSysArr['US_score3Name'] .'</td>
			<td align="center"></td>
		</tr>
		<tr>
			<td align="right">限制阅读积分：</td>
			<td align="center" style="'. $score1Style .'"><input type="text" id="score1" name="score1" value="'. $IF_score1 .'" size="3" style="width:42px;" onkeyUp="this.value=FiltInt(this.value)" /></td>
			<td align="center" style="'. $score2Style .'"><input type="text" id="score2" name="score2" value="'. $IF_score2 .'" size="3" style="width:42px;" onkeyUp="this.value=FiltInt(this.value)" /></td>
			<td align="center" style="'. $score3Style .'"><input type="text" id="score3" name="score3" value="'. $IF_score3 .'" size="3" style="width:42px;" onkeyUp="this.value=FiltInt(this.value)" /></td>
			<td align="left">
				<input type="hidden" id="userLevel" name="userLevel" value="'. $IF_userLevel .'" />
				<select id="userLevelStr" name="userLevelStr" onchange="SetUserLevel();">
					<option value="">阅读等级限制</option>
					');
					$levexe=$DB->query('select UL_theme,UL_num,UL_score1,UL_score2,UL_score3 from '. OT_dbPref .'userLevel order by UL_score1 ASC');
					while ($row = $levexe->fetch()){
						echo('<option value="'. $row['UL_num'] .'|'. $row['UL_score1'] .'|'. $row['UL_score2'] .'|'. $row['UL_score3'] .'">等级：'. $row['UL_num'] .'【'. $row['UL_theme'] .'】</option>');
					
					}
					unset($levexe);
				echo('
				</select>
			</td>
		</tr>
		<tr>
			<td align="right">付费阅读积分：</td>
			<td align="center" style="'. $score1Style .'"><input type="text" id="cutScore1" name="cutScore1" value="'. $IF_cutScore1 .'" size="3" style="width:42px;" onkeyUp="this.value=FiltInt(this.value)" /></td>
			<td align="center" style="'. $score2Style .'"><input type="text" id="cutScore2" name="cutScore2" value="'. $IF_cutScore2 .'" size="3" style="width:42px;" onkeyUp="this.value=FiltInt(this.value)" /></td>
			<td align="center" style="'. $score3Style .'"><input type="text" id="cutScore3" name="cutScore3" value="'. $IF_cutScore3 .'" size="3" style="width:42px;" onkeyUp="this.value=FiltInt(this.value)" /></td>
			<td align="center"></td>
		</tr>
		<tr>
			<td align="right">限制会员组：</td>
			<td align="left" colspan="4">
			');
			$groupexe = $DB->query('select UG_ID,UG_theme from '. OT_dbPref .'userGroup where UG_state=1 order by UG_rank ASC');
			while ($row = $groupexe->fetch()){
				echo('<label><input type="checkbox" name="userGroupList[]" value="['. $row['UG_ID'] .']" '. Is::InstrChecked($IF_userGroupList,'['. $row['UG_ID'] .']') .'>'. $row['UG_theme'] .'</label>&ensp;&ensp;');	// <div style="float:left;width:100px;"></div>
			}
			unset($groupexe);
			echo('
			<span style="color:red;">(都不选，等于允许所有用户组)</span>
			</td>
		</tr>
		</tbody>
		'. AppNewsEnc::InfoTrBox1($IF_isEnc, $IF_encContent, $IF_addition) .'
		</table>
		<div class="clr"></div>
		<!-- <div style="padding:20px 5px 5px 5px;color:red;line-height:1.4;">提醒：伪原创词库存放在网站目录/inc/apprWord.txt，可自行添加需要的伪原创词。</div> -->

		<div class="tabMenuSubmit"><input type="submit" class="btnBg" value="'. $submitCN .'" /></div>
	</div>

	</form>
	');
}



// 文章管理
function manage(){
	global $DB,$MB,$skin,$mudi,$dataType,$dataTypeCN,$pageCount,$recordCount,$systemArr,$userSysArr,$infoSysArr;

	$dataMode		= OT::GetStr('dataMode');
	$dataModeStr	= OT::GetStr('dataModeStr');

	$refTypeStr		= OT::GetStr('refTypeStr');
		if ($refTypeStr != '' && $refTypeStr != 'announ'){ $refTypeStr=','. Str::RegExp($refTypeStr,'sql') .','; }
	$refAddition	= OT::GetRegExpStr('refAddition','sql');
	$refTopicID		= OT::GetInt('refTopicID');
	$refState		= OT::GetInt('refState',-1);
	$refWapState	= OT::GetInt('refWapState',-1);
	$refTheme		= OT::GetRegExpStr('refTheme','sql');
	$refSource		= OT::GetRegExpStr('refSource','sql');
	$refWriter		= OT::GetRegExpStr('refWriter','sql');
	$refUsername	= OT::GetRegExpStr('refUsername','sql');
	$refRealname	= OT::GetRegExpStr('refRealname','sql');
	$refContent		= OT::GetRegExpStr('refContent','sql');
	$refDate1		= OT::GetStr('refDate1');
		if (! strtotime($refDate1)){ $refDate1=''; }
	$refDate2		= OT::GetStr('refDate2');
		if (! strtotime($refDate2)){ $refDate2=''; }

	if ($dataMode == 'user'){
		$SQLstr='select IF0.*,UE.UE_username,UE.UE_realname,UE.UE_score1,UE.UE_score2,UE.UE_score3 from (select IF_ID,IF_time,IF_theme,IF_themeStyle,IF_typeStr,IF_type1ID,IF_type2ID,IF_img,IF_isAudit,IF_isNew,IF_isHomeThumb,IF_isThumb,IF_isFlash,IF_isImg,IF_isMarquee,IF_isRecom,IF_isTop,IF_readNum,IF_infoTypeDir,IF_datetimeDir,IF_userID,IF_topicID,IF_state,IF_auditNote,IF_isGetScore,IF_getScore1,IF_getScore2,IF_getScore3,IF_adminID,IF_URL from '. OT_dbPref .'info where IF_userID>=1) as IF0 left join '. OT_dbPref .'users as UE on IF0.IF_userID=UE.UE_ID where (1=1)';
		if ($refUsername != ''){ $SQLstr .= " and UE_username like '%". $DB->ForStr($refUsername,false) ."%'"; }
		if ($refRealname != ''){ $SQLstr .= " and UE_realname like '%". $DB->ForStr($refRealname,false) ."%'"; }
	}else{
		$SQLstr='select IF_ID,IF_time,IF_theme,IF_themeStyle,IF_typeStr,IF_img,IF_isAudit,IF_isNew,IF_isHomeThumb,IF_isThumb,IF_isImg,IF_isFlash,IF_isMarquee,IF_isRecom,IF_isTop,IF_readNum,IF_infoTypeDir,IF_datetimeDir,IF_userID,IF_topicID,IF_state,IF_wapState,IF_auditNote,IF_URL from '. OT_dbPref .'info where 1=1';
	}

	if ($refTypeStr != ''){ $SQLstr .= " and IF_typeStr like '%". $DB->ForStr($refTypeStr,false) ."%'"; }
	if ($refAddition != ''){
		switch ($refAddition){
			case 'audit2':		$SQLstr .= ' and IF_isAudit=2';			break;
			case 'audit1':		$SQLstr .= ' and IF_isAudit=1';			break;
			case 'audit0':		$SQLstr .= ' and IF_isAudit=0';			break;
			case 'new1':		$SQLstr .= ' and IF_isNew=1';			break;
			case 'new0':		$SQLstr .= ' and IF_isNew=0';			break;
			case 'homeThumb1':	$SQLstr .= ' and IF_isHomeThumb=1';		break;
			case 'homeThumb0':	$SQLstr .= ' and IF_isHomeThumb=0';		break;
			case 'thumb1':		$SQLstr .= ' and IF_isThumb=1';			break;
			case 'thumb0':		$SQLstr .= ' and IF_isThumb=0';			break;
			case 'flash1':		$SQLstr .= ' and IF_isFlash=1';			break;
			case 'flash0':		$SQLstr .= ' and IF_isFlash=0';			break;
			case 'img1':		$SQLstr .= ' and IF_isImg=1';			break;
			case 'img0':		$SQLstr .= ' and IF_isImg=0';			break;
			case 'marquee1':	$SQLstr .= ' and IF_isMarquee=1';		break;
			case 'marquee0':	$SQLstr .= ' and IF_isMarquee=0';		break;
			case 'recom1':		$SQLstr .= ' and IF_isRecom=1';			break;
			case 'recom0':		$SQLstr .= ' and IF_isRecom=0';			break;
			case 'top1':		$SQLstr .= ' and IF_isTop=1';			break;
			case 'top0':		$SQLstr .= ' and IF_isTop=0';			break;
			case 'voteMode0':	$SQLstr .= ' and IF_voteMode=0';		break;
			case 'voteMode1':	$SQLstr .= ' and IF_voteMode=1';		break;
			case 'voteMode2':	$SQLstr .= ' and IF_voteMode=2';		break;
			case 'voteMode11':	$SQLstr .= ' and IF_voteMode=11';		break;
			case 'isMarkNews1':	$SQLstr .= ' and IF_isMarkNews=1';		break;
			case 'isMarkNews0':	$SQLstr .= ' and IF_isMarkNews=0';		break;
			case 'userFile1':	$SQLstr .= ' and IF_isUserFile=1';		break;
			case 'userFile0':	$SQLstr .= ' and IF_isUserFile=0';		break;
			case 'isReply1':	$SQLstr .= ' and IF_isReply=1';			break;
			case 'isReply10':	$SQLstr .= ' and IF_isReply=10';		break;
			case 'isReply0':	$SQLstr .= ' and IF_isReply=0';			break;
			case 'isCheckUser1':$SQLstr .= ' and IF_isCheckUser=1';		break;
			case 'isCheckUser2':$SQLstr .= ' and IF_isCheckUser=2';		break;
			case 'isCheckUser0':$SQLstr .= ' and IF_isCheckUser=0';		break;
			case 'isSitemap0':	$SQLstr .= ' and IF_isSitemap=0';		break;
			case 'isSitemap2':	$SQLstr .= ' and IF_isSitemap=2';		break;
			case 'isSitemap1':	$SQLstr .= ' and IF_isSitemap=1';		break;
			case 'isXiongzhang0':	$SQLstr .= ' and IF_isXiongzhang=0';		break;
			case 'isXiongzhang2':	$SQLstr .= ' and IF_isXiongzhang=2';		break;
			case 'isXiongzhang1':	$SQLstr .= ' and IF_isXiongzhang=1';		break;
			case 'bdPing0':		$SQLstr .= ' and IF_bdPing=0';			break;
			case 'bdPing2':		$SQLstr .= ' and IF_bdPing=2';			break;
			case 'bdPing1':		$SQLstr .= ' and IF_bdPing=1';			break;
		}
	}
	if ($refState > -1){ $SQLstr .= ' and IF_state='. $refState; }
	if ($refWapState > -1){ $SQLstr .= ' and IF_wapState='. $refWapState; }
	if ($refTopicID > 0){ $SQLstr .= ' and IF_topicID='. $refTopicID; }
	if ($refTheme != ''){ $SQLstr .= " and IF_theme like '%". $DB->ForStr($refTheme,false) ."%'"; }
	if ($refSource != ''){ $SQLstr .= " and IF_source like '%". $DB->ForStr($refSource,false) ."%'"; }
	if ($refWriter != ''){ $SQLstr .= " and IF_writer like '%". $DB->ForStr($refWriter,false) ."%'"; }
	if ($refContent != ''){
		$addiStr = '';
		$addiArr = array();
		for ($i=1; $i<=$infoSysArr['IS_tabNum']; $i++){
			$addiArr[] = "select IC_ID from ". OT_dbPref ."infoContent". $i ." where IC_content like '%". $DB->ForStr($refContent,false) ."%'";
		}
		if (count($addiArr) > 0){
			$addiStr = 'or IF_ID in ('. implode(' union ',$addiArr) .')';
		}
		$SQLstr .= " and (IF_content like '%". $DB->ForStr($refContent,false) ."%'". $addiStr .")";
	}
	if ($refDate1 != ''){ $SQLstr .= ' and IF_time>='. $DB->ForTime($refDate1); }
	if ($refDate2 != ''){ $SQLstr .= ' and IF_time<='. $DB->ForTime(TimeDate::Add('d',1,$refDate2)); }

	$orderName = OT::GetStr('orderName');
		if (in_array($orderName,array('IF_theme', 'IF_readNum', 'IF_typeStr', 'IF_state', 'IF_wapState', 'IF_time', 'IF_ID', 'UE_username', 'UE_realname', 'UE_score1', 'UE_score2', 'UE_score3'))==false){ $orderName='IF_time'; }
	$orderSort = OT::GetStr('orderSort');
		if ($orderSort!='ASC'){ $orderSort='DESC'; }

	$moveOptionStr = '';
	$topicOptionStr= '';
	$whereInfoTypeIdStr = '';
	$skin->TableTop('share_refer.gif','',$dataTypeCN .'查询');
		echo('
		<form id="refForm" name="refForm" method="get" action="">
		<input type="hidden" name="mudi" value="'. $mudi .'" />
		<input type="hidden" name="dataMode" value="'. $dataMode .'" />
		<input type="hidden" name="dataModeStr" value="'. $dataModeStr .'" />
		<input type="hidden" name="dataType" value="'. $dataType .'" />
		<input type="hidden" name="dataTypeCN" value="'. $dataTypeCN .'" />

		<table width="98%" align="center" border="0" cellSpacing="0" cellPadding="0" summary="" class="padd5">
		');

		$aarnArr = AppAdminRightNews::InfoItem2($MB->mRightStr, $refTypeStr, $MB->mUserID);
		if ( (! empty($aarnArr)) && count($aarnArr) >= 3 ){
			$infoTypeOption		= $aarnArr['infoTypeOption'];
			$moveOptionStr		= $aarnArr['moveOption'];
			$whereInfoTypeIdStr = $aarnArr['whereStr'];
		}else{
			$infoTypeOption = '<option value="announ" '. Is::Selected($refTypeStr,'announ') .'>0、'. $systemArr['SYS_announName'] .'</option>';
			$moveOptionStr .= '<option value="announ">0、'. $systemArr['SYS_announName'] .'</option>';
			$typeNum = 0;
			$typeexe=$DB->query('select IT_ID,IT_theme from '. OT_dbPref .'infoType where IT_state=1 and IT_level=1 order by IT_rank ASC');
			while ($row = $typeexe->fetch()){
				$typeNum ++;
				$infoTypeOption .= '<option value="'. $row['IT_ID'] .'" '. Is::InstrSelected($refTypeStr,','. $row['IT_ID'] .',') .'>'. $typeNum .'、'. $row['IT_theme'] .'</option>';
				$moveOptionStr .= '<option value=",'. $row['IT_ID'] .',">'. $typeNum .'、'. $row['IT_theme'] .'</option>';
				$type2exe=$DB->query('select IT_ID,IT_theme from '. OT_dbPref .'infoType where IT_state=1 and IT_level=2 and IT_fatID='. $row['IT_ID'] .' order by IT_rank ASC');
				while ($row2 = $type2exe->fetch()){
					$moveOptionStr .= '<option value=",'. $row['IT_ID'] .','. $row2['IT_ID'] .',">&ensp;&ensp;&ensp;┣&ensp;'. $row2['IT_theme'] .'</option>';
					$infoTypeOption .= '<option value="'. $row2['IT_ID'] .'" '. Is::Selected($refTypeStr,','. $row2['IT_ID'] .',') .'>&ensp;&ensp;&ensp;┣&ensp;'. $row2['IT_theme'] .'</option>';
				}
			}
		}

		if ($dataMode == 'user'){
			// 会员投稿 查询栏
			echo('
			<tr>
				<td style="width:32%;">
					栏目：<select id="refTypeStr" name="refTypeStr" style="width:180px;">
						<option value=""></option>
						'. $infoTypeOption .'
						</select>&ensp;
				</td>
				<td style="width:28%;">
					会员名：<input type="text" name="refUsername" size="20" style="width:120px;" value="'. $refUsername .'" />
				</td>
				<td style="width:40%;">
					&ensp;&ensp;&ensp;&ensp;审核：<select id="refAddition" name="refAddition">
						<option value=""></option>
						<option value="audit1" '. Is::Selected($refAddition,'audit1') .'>已审核</option>
						<option value="audit0" '. Is::Selected($refAddition,'audit0') .'>未审核</option>
						</select>
					&ensp;&ensp;&ensp;&ensp;状态：<select id="refState" name="refState">
						<option value=""></option>
						<option value="1" '. Is::Selected($refState,1) .'>显示</option>
						<option value="0" '. Is::Selected($refState,0) .'>隐藏</option>
						</select>
				</td>
			</tr>
			<tr>
				<td>
					标题：<input type="text" name="refTheme" size="20" style="width:175px;" value="'. $refTheme .'" />
				</td>
				<td>
					&ensp;&ensp;昵称：<input type="text" name="refRealname" size="20" style="width:120px;" value="'. $refRealname .'" />
				</td>
				<td>
					发布日期：<input type="text" name="refDate1" size="10" style="width:70px;" value="'. $refDate1 .'" onfocus="WdatePicker()" />&ensp;
					至&ensp;<input type="text" name="refDate2" size="10" style="width:70px;" value="'. $refDate2 .'" onfocus="WdatePicker()" />
				</td>
			</tr>
			');
		}else{
			// 文章管理 查询栏
			echo('
			<tr>
				<td style="width:32%;">
					栏目：<select id="refTypeStr" name="refTypeStr" style="width:180px;">
						<option value=""></option>
						'. $infoTypeOption .'
						</select>&ensp;
				</td>
				<td style="width:28%;">
					属性：<select id="refAddition" name="refAddition">
						<option value=""></option>
						<option value="audit2" style="color:red;" '. Is::Selected($refAddition,'audit2') .'>被拒绝ㄨ</option>
						<option value="audit1" style="color:#0066CC;" '. Is::Selected($refAddition,'audit1') .'>已审核√</option>
						<option value="audit0" style="color:#707070;" '. Is::Selected($refAddition,'audit0') .'>已审核ㄨ</option>
						<option value="new1" style="color:#0066CC;" '. Is::Selected($refAddition,'new1') .'>最新消息√</option>
						<option value="new0" style="color:#707070;" '. Is::Selected($refAddition,'new0') .'>最新消息ㄨ</option>
						<option value="homeThumb1" style="color:#0066CC;" '. Is::Selected($refAddition,'homeThumb1') .'>首页缩略图√</option>
						<option value="homeThumb0" style="color:#707070;" '. Is::Selected($refAddition,'homeThumb0') .'>首页缩略图ㄨ</option>
						<option value="thumb1" style="color:#0066CC;" '. Is::Selected($refAddition,'thumb1') .'>缩略图√</option>
						<option value="thumb0" style="color:#707070;" '. Is::Selected($refAddition,'thumb0') .'>缩略图ㄨ</option>
						<option value="flash1" style="color:#0066CC;" '. Is::Selected($refAddition,'flash1') .'>幻灯片√</option>
						<option value="flash0" style="color:#707070;" '. Is::Selected($refAddition,'flash0') .'>幻灯片ㄨ</option>
						<option value="img1" style="color:#0066CC;" '. Is::Selected($refAddition,'img1') .'>滚动图片√</option>
						<option value="img0" style="color:#707070;" '. Is::Selected($refAddition,'img0') .'>滚动图片ㄨ</option>
						<option value="marquee1" style="color:#0066CC;" '. Is::Selected($refAddition,'marquee1') .'>滚动信息√</option>
						<option value="marquee0" style="color:#707070;" '. Is::Selected($refAddition,'marquee0') .'>滚动信息ㄨ</option>
						<option value="recom1" style="color:#0066CC;" '. Is::Selected($refAddition,'recom1') .'>推荐√</option>
						<option value="recom0" style="color:#707070;" '. Is::Selected($refAddition,'recom0') .'>推荐ㄨ</option>
						<option value="top1" style="color:#0066CC;" '. Is::Selected($refAddition,'top1') .'>置顶√</option>
						<option value="top0" style="color:#707070;" '. Is::Selected($refAddition,'top0') .'>置顶ㄨ</option>
						<option value="voteMode0" style="color:#707070;" '. Is::Selected($refAddition,'voteMode0') .'>投票方式：关闭</option>
						<option value="voteMode1" style="color:#0066CC;" '. Is::Selected($refAddition,'voteMode1') .'>投票方式：心情</option>
						<option value="voteMode2" style="color:#0066CC;" '. Is::Selected($refAddition,'voteMode2') .'>投票方式：顶踩</option>
						<option value="voteMode11" style="color:#0066CC;" '. Is::Selected($refAddition,'voteMode11') .'>投票方式：百度喜欢</option>
						<option value="isMarkNews1" style="color:#0066CC;" '. Is::Selected($refAddition,'isMarkNews1') .'>相关文章：开启</option>
						<option value="isMarkNews0" style="color:#707070;" '. Is::Selected($refAddition,'isMarkNews0') .'>相关文章：关闭</option>
						<option value="userFile1" style="color:#0066CC;" '. Is::Selected($refAddition,'userFile1') .'>仅会员下载附件：开启</option>
						<option value="userFile0" style="color:#707070;" '. Is::Selected($refAddition,'userFile0') .'>仅会员下载附件：关闭</option>
						<option value="isReply1" style="color:#0066CC;" '. Is::Selected($refAddition,'isReply1') .'>评论区：开启</option>
						<option value="isReply10" style="color:#000;" '. Is::Selected($refAddition,'isReply10') .'>评论区：仅限会员</option>
						<option value="isReply0" style="color:#707070;" '. Is::Selected($refAddition,'isReply0') .'>评论区：关闭</option>
						<option value="isCheckUser1" style="color:#0066CC;" '. Is::Selected($refAddition,'isCheckUser1') .'>限制阅读：仅限会员</option>
						<option value="isCheckUser2" style="color:#000;" '. Is::Selected($refAddition,'isCheckUser2') .'>限制阅读：关注公众号</option>
						<option value="isCheckUser0" style="color:#707070;" '. Is::Selected($refAddition,'isCheckUser0') .'>限制阅读：关闭</option>
						'. AppMapBaidu::InfoOptionBox1($refAddition) .'
						</select>
				</td>
				<td style="width:40%;">
					电脑状态：<select id="refState" name="refState">
						<option value=""></option>
						<option value="1" '. Is::Selected($refState,1) .'>显示</option>
						<option value="0" '. Is::Selected($refState,0) .'>隐藏</option>
						</select>
					&ensp;&ensp;&ensp;&ensp;手机状态：<select id="refWapState" name="refWapState">
						<option value=""></option>
						<option value="1" '. Is::Selected($refWapState,1) .'>显示</option>
						<option value="0" '. Is::Selected($refWapState,0) .'>隐藏</option>
						</select>
				</td>
			</tr>
			<tr>
				<td>
					标题：<input type="text" name="refTheme" size="20" style="width:175px;" value="'. $refTheme .'" />
				</td>
				<td>
					内容：<input type="text" name="refContent" size="14" style="width:150px;" value="'. $refContent .'" />
				</td>
				<td>
					'. AppTopic::InfoBox1($refTopicID,$topicOptionStr) .'
				</td>
			</tr>
			<tr>
				<td>
					作者：<input type="text" name="refWriter" size="14" style="width:175px;" value="'. $refWriter .'" />
				</td>
				<td>
					来源：<input type="text" name="refSource" size="14" style="width:150px;" value="'. $refSource .'" />
				</td>
				<td>
					发布日期：<input type="text" name="refDate1" size="10" style="width:70px;" value="'. $refDate1 .'" onfocus="WdatePicker()" />&ensp;
					至&ensp;<input type="text" name="refDate2" size="10" style="width:70px;" value="'. $refDate2 .'" onfocus="WdatePicker()" />
				</td>
			</tr>
			');
		}
		echo('
		<tr>
			<td align="center" style="padding:5px;padding-top:20px" colspan="4">
				<input type="image" src="images/button_refer.gif" />
				&ensp;&ensp;&ensp;&ensp;
				<img src="images/button_reset.gif" onclick=\'document.location.href="?mudi='. $mudi .'&dataMode='. $dataMode .'&dataModeStr='. $dataModeStr .'&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'"\' style="cursor:pointer" alt="" />
			</td>
		</tr>
		</table>
		</form>
		');
	$skin->TableBottom();

	echo('<br />');

	if ($systemArr['SYS_isTimeInfo']==1){
		echo('
		<div class="font2_1" style="padding:5px;color:red;">提醒：已开启文章定时发布功能，所有发布时间超过当前时间的文章，均不会显示在前台。<span class="font1_2">（[网站参数设置]-[基本信息]可设置关闭）</span></div>
		');
	}

	echo('
	<form id="listForm" name="listForm" method="post" action="info_deal.php?mudi=moreDel" onsubmit="return CheckListForm()">
	<script language="javascript" type="text/javascript">document.write (\'<input type="hidden" name="backURL" value="\'+ document.location.href +\'" />\')</script>
	<input type="hidden" name="dataType" value="'. $dataType .'" />
	<input type="hidden" name="dataTypeCN" value="'. $dataTypeCN .'" />
	');

	$skin->TableTop2('share_list.gif','',$dataTypeCN .'列表');
	if ($dataMode == 'user'){
		// 会员投稿 查询栏
		$skin->TableItemTitle('4%,5%,5%,25%,14%,9%,9%,10%,7%,12%','<input type="checkbox" id="selAll" name="selAll" onclick="CheckBoxAll()" />,序号,'. $skin->ShowArrow('ID','IF_ID',$orderName,$orderSort) .','. $skin->ShowArrow('标题','IF_theme',$orderName,$orderSort) .',栏目,'. $skin->ShowArrow('发布日期','IF_time',$orderName,$orderSort) .','. $skin->ShowArrow('用户名','UE_username',$orderName,$orderSort) .'/'. $skin->ShowArrow('昵称','UE_realname',$orderName,$orderSort) .','. $skin->ShowArrow('积分1','UE_score1',$orderName,$orderSort) .'/'. $skin->ShowArrow('2','UE_score2',$orderName,$orderSort) .'/'. $skin->ShowArrow('3','UE_score3',$orderName,$orderSort) .','. $skin->ShowArrow('审核','IF_state',$orderName,$orderSort) .',复制　修改　删除');
	}else{
		// 文章管理 查询栏
		$skin->TableItemTitle('4%,5%,5%,26%,13%,13%,10%,6%,8%,11%','<input type="checkbox" id="selAll" name="selAll" onclick="CheckBoxAll()" />,序号,'. $skin->ShowArrow('ID','IF_ID',$orderName,$orderSort) .','. $skin->ShowArrow('标题','IF_theme',$orderName,$orderSort) .',属性,栏目/专题,'. $skin->ShowArrow('发布日期','IF_time',$orderName,$orderSort) .','. $skin->ShowArrow('阅读量','IF_readNum',$orderName,$orderSort) .','. $skin->ShowArrow('电脑','IF_state',$orderName,$orderSort) .'/'. $skin->ShowArrow('WAP状态','IF_wapState',$orderName,$orderSort) .',复制&ensp;修改&ensp;删除');
	}

	$pageSize	= $MB->mMbRow['MB_itemNum'];		//每页条数
	$page		= OT::GetInt('page');
	$showRow=$DB->GetLimit($SQLstr . $whereInfoTypeIdStr .' order by '. $orderName .' '. $orderSort .'',$pageSize,$page);
	if (! $showRow){
		$infoCount = $DB->GetOne('select count(1) from '. OT_dbPref .'info');
		if ($infoCount == 0){
			$noAlert = '暂无内容<span style="color:#000;">（如要文章ID重置为1，请到【管理员专区】→【程序文件检查】-[SQL语句调试] 快捷指令：◆文章ID重置为1）</span>';
		}else{
			$noAlert = '暂无内容';
		}
		$skin->TableNoData($noAlert);
	}else{
		$recordCount=$DB->GetRowCount();
		$pageCount=ceil($recordCount/$pageSize);
		if ($page < 1 || $page > $pageCount){$page=1;}

		$InfoType = new InfoType();
		echo('
		<tbody class="tabBody padd3td">
		');
		$number=1+($page-1)*$pageSize;
		$rowCount = count($showRow);
		for ($i=0; $i<$rowCount; $i++){
			if ($i % 2 == 1){ $bgcolor='class="tabColorTr"'; }else{ $bgcolor=''; }

			$imgStr = $auditNote = '';
			if (strlen($showRow[$i]['IF_img'])>0){
				if (Is::AbsUrl($showRow[$i]['IF_img'])){
					$imgStr .= '<a href="'. $showRow[$i]['IF_img'] .'" target="_blank"><img src="images/imgPc.gif" alt="远程缩略图" title="远程缩略图" /></a>';
				}else{
					$imgStr .= '<a href="'. InfoImgAdminDir . $showRow[$i]['IF_img'] .'" target="_blank"><img src="images/imgAlt.gif" alt="本地缩略图" title="本地缩略图" /></a>';
				}
			}
			if (strlen($showRow[$i]['IF_URL'])>3){
				$imgStr .= '<a href="'. $showRow[$i]['IF_URL'] .'" target="_blank"><img src="images/imgLink.png" alt="外部链接" title="外部链接" /></a>';
			}
			
			if ($showRow[$i]['IF_isAudit'] == 1){
				$infoHref='../'. Url::NewsID($showRow[$i]['IF_infoTypeDir'],$showRow[$i]['IF_datetimeDir'],$showRow[$i]['IF_ID']);
			}else{
				if ($showRow[$i]['IF_isAudit'] == 2 || strlen($showRow[$i]['IF_auditNote']) > 0){
					$auditNote = '<div style="padding-top:3px;color:red;">被拒绝原因：'. $showRow[$i]['IF_auditNote'] .'</div>';
				}
				$infoHref='../news/?'. $showRow[$i]['IF_ID'] .'.html&rnd=user'. $showRow[$i]['IF_userID'] .'';
			}
			$topicCN = $InfoType->TopicCN($showRow[$i]['IF_topicID']);
			if ($topicCN == $InfoType->noStr){
				$topicCN = '';
			}else{
				$topicCN = '<div style="margin-top:7px;color:green;"><b>专题:</b>'. $topicCN .'</div>';
			}

			if ($dataMode == 'user'){
				// 会员投稿 查询栏
				if ($showRow[$i]['IF_isGetScore'] == 1){
					$scoreStr = '已获得：&#10;';
					if ($userSysArr['US_isScore1'] == 1){
						$scoreStr .= $userSysArr['US_score1Name'] .'：'. $showRow[$i]['IF_getScore1'] .'&#10;';
					}
					if ($userSysArr['US_isScore2'] == 1){
						$scoreStr .= $userSysArr['US_score2Name'] .'：'. $showRow[$i]['IF_getScore2'] .'&#10;';
					}
					if ($userSysArr['US_isScore3'] == 1){
						$scoreStr .= $userSysArr['US_score3Name'] .'：'. $showRow[$i]['IF_getScore3'] .'&#10;';
					}
					$getScoreStr = '<div class="font1_2d" title="'. $scoreStr .'">[已得分]</div>';
				}else{
					$getScoreStr = '';
				}

				echo('
				<tr '. $bgcolor .' id="data'. $showRow[$i]['IF_ID'] .'">
					<td align="center"><input type="checkbox" name="selDataID[]" value="'. $showRow[$i]['IF_ID'] .'" /></td>
					<td align="center">'. $number .'</td>
					<td align="center">'. $showRow[$i]['IF_ID'] .'</td>
					<td align="left" style="word-break:break-all;'. $showRow[$i]['IF_themeStyle'] .'">
						'. $showRow[$i]['IF_theme'] .'&ensp;'. $imgStr .'&ensp;
						<a href="../news/?'. $showRow[$i]['IF_ID'] .'.html&rnd=user'. $showRow[$i]['IF_userID'] .'" target="_blank" class="font2_2">[预览]</a>
						'. $auditNote .'
					</td>
					<td align="left">'. $InfoType->TypeStrCN($showRow[$i]['IF_typeStr']) .'</td>
					<td align="center">'. $showRow[$i]['IF_time'] .'</td>
					<td align="center">'. $showRow[$i]['UE_username'] . AdmArea::UserInfoImg($showRow[$i]['IF_userID']) .'<div>'. $showRow[$i]['UE_realname'] .'</div></td>
					<td align="left">'. Area::UserScoreList($showRow[$i]['UE_score1'],$showRow[$i]['UE_score2'],$showRow[$i]['UE_score3']) .'</td>
					<td align="center">'. Adm::SwitchAddi('info',$showRow[$i]['IF_ID'],'已审核',$showRow[$i]['IF_isAudit'],'isAudit','') .'<br /><span id="infoHtmlBox'. $showRow[$i]['IF_ID'] .'"></span>'. $getScoreStr .'</td>
					<td align="center">
						<img src="images/img_copy.gif" style="cursor:pointer" onclick=\'document.location.href="?mudi=add&mudi2=copy&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&dataMode='. $dataMode .'&dataModeStr='. $dataModeStr .'&dataID='. $showRow[$i]['IF_ID'] .'&backURL="+ encodeURIComponent(document.location.href)\' alt="复制" title="复制" />&ensp;&ensp;
						<img src="images/img_rev.gif" style="cursor:pointer" onclick=\'document.location.href="?mudi=rev&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&dataMode='. $dataMode .'&dataModeStr='. $dataModeStr .'&dataID='. $showRow[$i]['IF_ID'] .'&backURL="+ encodeURIComponent(document.location.href)\' alt="修改" title="修改" />&ensp;&ensp;
						<img src="images/img_del.gif" style="cursor:pointer" onclick=\'if(confirm("你确定要删除？")==true){DataDeal.location.href="info_deal.php?mudi=del&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&theme='. urlencode($showRow[$i]['IF_theme']) .'&dataID='. $showRow[$i]['IF_ID'] .'"}\' alt="删除" title="删除" />
					</td>
				</tr>
				');
			}else{
				// 文章管理 查询栏
				echo('
				<tr '. $bgcolor .' id="data'. $showRow[$i]['IF_ID'] .'">
					<td align="center"><input type="checkbox" name="selDataID[]" value="'. $showRow[$i]['IF_ID'] .'" /></td>
					<td align="center">'. $number .'</td>
					<td align="center">'. $showRow[$i]['IF_ID'] .'</td>
					<td align="left" style="word-break:break-all;'. $showRow[$i]['IF_themeStyle'] .'">
						'. $showRow[$i]['IF_theme'] .'&ensp;'.  $imgStr .'&ensp;
						<a href="'. $infoHref .'" target="_blank" class="font2_2">[预览]</a>
						'. $auditNote .'
					</td>
					<td align="center" style="line-height:1.4;">'. $InfoType->AddiBtn($showRow[$i]['IF_ID'],$showRow[$i]['IF_isAudit'],$showRow[$i]['IF_isNew'],$showRow[$i]['IF_isHomeThumb'],$showRow[$i]['IF_isThumb'],$showRow[$i]['IF_isImg'],$showRow[$i]['IF_isFlash'],$showRow[$i]['IF_isMarquee'],$showRow[$i]['IF_isRecom'],$showRow[$i]['IF_isTop']) .'</td>
					<td align="left">'. $InfoType->TypeStrCN($showRow[$i]['IF_typeStr']) . $topicCN .'</td>
					<td align="center">'. $showRow[$i]['IF_time'] .'</td>
					<td align="center">'. $showRow[$i]['IF_readNum'] .'</td>
					<td align="center">
						'. Adm::SwitchBtn('info',$showRow[$i]['IF_ID'],$showRow[$i]['IF_state'],'state') .'/
						'. Adm::SwitchBtn('info',$showRow[$i]['IF_ID'],$showRow[$i]['IF_wapState'],'wapState','userState') .'
						<div id="infoHtmlBox'. $showRow[$i]['IF_ID'] .'"></div>
					</td>
					<td align="center">
						<img src="images/img_copy.gif" class="pointer" onclick=\'document.location.href="?mudi=add&mudi2=copy&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&dataMode='. $dataMode .'&dataModeStr='. $dataModeStr .'&dataID='. $showRow[$i]['IF_ID'] .'&backURL="+ encodeURIComponent(document.location.href)\' alt="复制" title="复制" />&ensp;
						<img src="images/img_rev.gif" class="pointer" onclick=\'document.location.href="?mudi=rev&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&dataMode='. $dataMode .'&dataModeStr='. $dataModeStr .'&dataID='. $showRow[$i]['IF_ID'] .'&backURL="+ encodeURIComponent(document.location.href)\' alt="修改" title="修改" />&ensp;
						<img src="images/img_del.gif" class="pointer" onclick=\'if(confirm("你确定要删除？")==true){DataDeal.location.href="info_deal.php?mudi=del&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&theme='. urlencode($showRow[$i]['IF_theme']) .'&dataID='. $showRow[$i]['IF_ID'] .'"}\' alt="删除" title="删除" />
					</td>
				</tr>
				');
			}
			$number ++;
		}
		echo('
		</tbody>
		<tr class="tabColorB padd5">
			<td align="left" colspan="20">
				<input type="button" value="全选" onclick="AllSelBox()" class="form_button1" />
				<input type="button" value="反选" onclick="RevSelBox()" class="form_button1" />
				&ensp;
				<input type="submit" value="批量删除" />
				&ensp;&ensp;
				<select id="moreSetTo" name="moreSetTo" onchange="MoreSetTo()" style="width:150px;">
					<option value="">批量设置成...</option>
					<option value="audit1" style="color:#0066CC;">已审核√</option>
					<option value="audit0" style="color:#707070;">已审核ㄨ</option>
					<option value="new1" style="color:#0066CC;">最新消息√</option>
					<option value="new0" style="color:#707070;">最新消息ㄨ</option>
					<option value="homeThumb1" style="color:#0066CC;">首页缩略图√</option>
					<option value="homeThumb0" style="color:#707070;">首页缩略图ㄨ</option>
					<option value="thumb1" style="color:#0066CC;">缩略图√</option>
					<option value="thumb0" style="color:#707070;">缩略图ㄨ</option>
					<option value="img1" style="color:#0066CC;">滚动图片√</option>
					<option value="img0" style="color:#707070;">滚动图片ㄨ</option>
					<option value="flash1" style="color:#0066CC;">幻灯片√</option>
					<option value="flash0" style="color:#707070;">幻灯片ㄨ</option>
					<option value="marquee1" style="color:#0066CC;">滚动信息√</option>
					<option value="marquee0" style="color:#707070;">滚动信息ㄨ</option>
					<option value="recom1" style="color:#0066CC;">推荐√</option>
					<option value="recom0" style="color:#707070;">推荐ㄨ</option>
					<option value="top1" style="color:#0066CC;">置顶√</option>
					<option value="top0" style="color:#707070;">置顶ㄨ</option>
					<option value="vote0">投票：关闭</option>
					<option value="vote1">投票：心情</option>
					<option value="vote2">投票：顶踩</option>
					<option value="vote11">投票：百度喜欢按钮</option>
					<option value="markNews1" style="color:#0066CC;">相关文章：开启</option>
					<option value="markNews0" style="color:red;">相关文章：关闭</option>
					<option value="userFile1" style="color:#0066CC;">仅限会员下载附件：开启</option>
					<option value="userFile0" style="color:red;">仅限会员下载附件：关闭</option>
					<option value="reply1">评论：开启</option>
					<option value="reply10">评论：仅限会员</option>
					<option value="reply0">评论：关闭</option>
					<option value="state1" style="color:#0066CC;">电脑版状态：显示</option>
					<option value="state0" style="color:red;">电脑版状态：隐藏</option>
					<option value="wapState1" style="color:#0066CC;">手机版状态：显示</option>
					<option value="wapState0" style="color:red;">手机版状态：隐藏</option>
					<option value="checkUser1" style="color:#0066CC;">限制阅读：仅限会员</option>
					'. (AppNewsVerCode::Jud() ? '<option value="checkUser2" style="color:green;">限制阅读：关注公众号</option>' : '') .'
					<option value="checkUser0" style="color:red;">限制阅读：关闭</option>
					'. AppMapBaidu::InfoOptionBox2() .'
					<option value="timeNew">发布时间：当前时间</option>
				</select>
				<input type="hidden" id="moreSetToCN" name="moreSetToCN" value="" />

				&ensp;
				<select id="moreMoveTo" name="moreMoveTo" onchange="MoreMoveTo()" style="width:150px;">
					<option value="">栏目批量移动到...</option>
					'. $moveOptionStr .'
				</select>
				<input type="hidden" id="moreMoveToCN" name="moreMoveToCN" value="" />

				&ensp;
				<select id="moreTxtSel" name="moreTxtSel" onchange="CheckMoreTxt();">
					<option value="topAddiID">头附加内容</option>
					<option value="addiID">尾附加内容</option>
					<option value="source">来源</option>
					<option value="writer">作者</option>
					<option value="readNum">阅读量</option>
					<option value="score1">限制阅读积分1</option>
					<option value="score2">限制阅读积分2</option>
					<option value="score3">限制阅读积分3</option>
					<option value="cutScore1">付费阅读积分1</option>
					<option value="cutScore2">付费阅读积分2</option>
					<option value="cutScore3">付费阅读积分3</option>
				</select>'.
				'<select id="moreAddiTo" name="moreAddiTo" onchange="MoreAddiTo()" style="width:150px;">
					<option value="">附加内容批量设置...</option>
					<option value="0">无</option>
					');
					$addiexe=$DB->query('select IW_ID,IW_theme,IW_state from '. OT_dbPref ."infoWeb where IW_type='news' order by IW_state DESC,IW_rank ASC");
					while ($row = $addiexe->fetch()){
						if ($row['IW_state']==1){ $stateStr=''; }else{ $stateStr=' (隐藏)'; }
						echo('<option value="'. $row['IW_ID'] .'">'. $row['IW_theme'] . $stateStr .'</option>');
					}
					unset($addiexe);
				echo('
				</select>
				<input type="hidden" id="moreAddiToCN" name="moreAddiToCN" value="" />'.
				'<span id="moreTxtBox" style="display:none;"><input type="text" id="moreTxtVal" name="moreTxtVal" /><input type="button" onclick="MoreTxtTo()" value="设置" /></span>
				');

				if (strlen($topicOptionStr)>3){
					echo('
					&ensp;
					<select id="moreTopicTo" name="moreTopicTo" onchange="MoreTopicTo()" style="width:150px;">
						<option value="">专题批量移动到...</option>
						<option value="0">无</option>
						'. $topicOptionStr .'
					</select>
					<input type="hidden" id="moreTopicToCN" name="moreTopicToCN" value="" />
					');
				}
			echo('
			</td>
		</tr>
		');
	}
	unset($showRow);

	echo('</form>');

	$skin->TableBottom2($pageCount, $pageSize, $recordCount);

	if ($dataMode == 'user'){
		echo('<div style="padding:6px;color:red;">提醒：如果要做【审核被拒绝】操作，请进入[修改]-[文章属性]选择[被拒绝]，就会出现拒绝原因填写框。</div>');
	}else{
		echo('<div style="padding:6px;color:red;">提醒：【WAP状态】手机版状态，如没有买手机版插件，可以忽略该选项。</div>');
	}
}



function InfoItem($moreArea, $currArea, &$moreStr, $currStr){
	if (strpos($moreArea,'|'. $currArea .'|') !== false){
		$moreStr .= $currStr;
	}else{
		echo($currStr);
	}
}

function InfoTypeAddi($str){
	if ($str == 'url'){
		return ' 【外部链接】';
	}elseif ($str == 'web'){
		return ' 【单篇页】';
	}
}

?>
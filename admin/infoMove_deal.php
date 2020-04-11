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
	case 'infoMove':
		InfoMove();
		break;

	case 'infoMoveSend':
		$menuFileID = 13;
		$MB->IsSecMenuRight('alert',$menuFileID,$dataType);
		InfoMoveSend();
		break;

	case 'infoMoveDel':
		$menuFileID = 14;
		$MB->IsSecMenuRight('alert',$menuFileID,$dataType);
		InfoMoveDel();
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





// 添加、修改
function InfoMove(){
	global $DB,$MB,$mudi,$menuFileID,$menuTreeID;

	$backURL	= OT::PostStr('backURL');
	$dataType	= OT::PostStr('dataType');
	$dataTypeCN	= OT::PostStr('dataTypeCN');
	$dataID		= OT::PostInt('dataID');
	$theme		= OT::PostStr('theme');
	$imgMode	= OT::PostStr('imgMode');
	$isImg		= 0;
	$img = '';
		if ($imgMode == 'upImg'){
			$isImg	= 1;
			$img	= OT::PostStr('upImg');
		}elseif ($imgMode == 'URL'){
			$isImg	= 2;
			$img	= OT::PostStr('imgURL');
		}
	$webURL		= OT::PostStr('webURL');
	$alt		= OT::PostStr('alt');
	$note		= OT::PostStr('note');
	$startDate	= OT::PostStr('startDate');
		if (! strtotime($startDate)){ $startDate=TimeDate::Get('date'); }
	$endDate	= OT::PostStr('endDate');
		if (! strtotime($endDate)){ $endDate='2029-12-31'; }
	$cost		= OT::PostFloat('cost');
	$rank		= OT::PostInt('rank');
	$state		= OT::PostInt('state');
	$wapState	= OT::PostInt('wapState');

	if ($dataType=='' || $theme=='' || $webURL==''){
		JS::AlertBackEnd('表单内容接收不全');
	}
	
	$record=array();
	$record['IM_imgMode']	= $imgMode;
	$record['IM_isImg']		= $isImg;
	$record['IM_img']		= $img;
	$record['IM_theme']		= $theme;
	$record['IM_URL']		= $webURL;
	$record['IM_alt']		= $alt;
	$record['IM_note']		= $note;
	$record['IM_startDate']	= $startDate;
	$record['IM_endDate']	= $endDate;
	$record['IM_cost']		= $cost;
	$record['IM_rank']		= $rank;
	$record['IM_state']		= $state;
	$record['IM_wapState']	= $wapState;

	$fileAddStr = $fileCutStr = '';
	$dealrec=$DB->query('select * from '. OT_dbPref .'infoMove where IM_ID='. $dataID);
	if (! $row = $dealrec->fetch()){
		if ($mudi=='infoMove3'){
			$menuFileID = 106;
			$MB->IsSecMenuRight('alertBack',$menuFileID,$dataType);
		}else{
			$menuFileID = 12;
			$MB->IsSecMenuRight('alertBack',$menuFileID,$dataType);
		}
		$alertMode='添加';
		$record['IM_time']	= TimeDate::Get();
		$record['IM_type']	= $dataType;
		$record['IM_source']= 0;
		if ($imgMode == 'upImg' && $img != ''){
			$fileAddStr = $img;
		}
		$judResult = $DB->InsertParam('infoMove',$record);

	}else{
		if ($mudi=='infoMove3'){
			$menuFileID = 107;
			$MB->IsSecMenuRight('alertBack',$menuFileID,$dataType);
		}else{
			$menuFileID = 13;
			$MB->IsSecMenuRight('alertBack',$menuFileID,$dataType);
		}
		$alertMode='修改';
		if ($imgMode == 'upImg' || $row['IM_imgMode'] == 'upImg'){
			if ($imgMode == 'upImg' && $img != $row['IM_img']){
				$fileAddStr = $img;
			}
			if ($row['IM_imgMode'] == 'upImg' && $row['IM_img'] != $img){
				$fileCutStr = $row['IM_img'];
			}
		}
		$judResult = $DB->UpdateParam('infoMove',$record,'IM_ID='. $dataID);
	}
	unset($dealrec);

		if ($judResult){
			$alertResult='成功';
			ServerFile::UseAddMore($fileAddStr);
			ServerFile::UseCutMore($fileCutStr);
		}else{
			$alertResult='失败';
		}


	Adm::AddLog(array(
		'theme'	=> $theme,
		'note'	=> '【'. $dataTypeCN .'】'. $alertMode . $alertResult .'！',
		));


	JS::AlertHrefEnd($alertMode . $alertResult,$backURL);

}



// 发送信息
function InfoMoveSend(){
	global $DB,$skin,$mudi,$menuFileID,$menuTreeID;

	$dataID = OT::GetInt('dataID');

	$sendexe=$DB->query('select * from '. OT_dbPref .'infoMove where IM_ID='. $dataID);
		if (! $row = $sendexe->fetch()){
			JS::AlertEnd('搜索不到指定记录');
		}
	echo('
	<script language="javascript" type="text/javascript">
	parent.$id("infoTitle").innerHTML="修改";
	parent.$id("dataID").value="'. $row['IM_ID'] .'";
	parent.$id("theme").value="'. Str::MoreReplace($row['IM_theme'],'js') .'";
	parent.$id("webURL").value="'. $row['IM_URL'] .'";
	parent.$id("rank").value="'. $row['IM_rank'] .'";
	');
	if ($row['IM_imgMode'] == 'upImg'){
		echo('
		try {
			parent.$name("imgMode")[1].checked=true;
			parent.$id("upImg").value="'. $row['IM_img'] .'";
			parent.CheckLogoMode();
		}catch (e){}
		');
	}elseif ($row['IM_imgMode'] == 'URL'){
		echo('
		try {
			parent.$name("imgMode")[2].checked=true;
			parent.$id("imgURL").value="'. $row['IM_img'] .'";
			parent.CheckLogoMode();
		}catch (e){}
		');
	}else{
		echo('
		try {
			parent.$name("imgMode")[0].checked=true;
			parent.CheckLogoMode();
		}catch (e){}
		');
	}
	echo('
	try {
		parent.$id("alt").value="'. $row['IM_alt'] .'";
		parent.$id("startDate").value="'. $row['IM_startDate'] .'";
		parent.$id("endDate").value="'. $row['IM_endDate'] .'";
		parent.$id("cost").value="'. $row['IM_cost'] .'";
		parent.$id("note").value="'. Str::MoreReplace($row['IM_note'],'js') .'";
	}catch (e){}
	');
	if ($row['IM_state'] == 1){
		echo('
		parent.$name("state")[0].checked=true;
		');
	}else{
		echo('
		parent.$name("state")[1].checked=true;
		');
	}
	if ($row['IM_wapState'] == 1){
		echo('
		parent.$name("wapState")[0].checked=true;
		');
	}else{
		echo('
		parent.$name("wapState")[1].checked=true;
		');
	}
	echo('
	parent.$id("subButton").src="images/button_rev.gif";
	</script>
	');
	
	unset($sendexe);
}



// 删除
function InfoMoveDel(){
	global $DB,$mudi,$menuFileID,$menuTreeID,$dataType,$dataTypeCN;

	$dataID	= OT::GetInt('dataID');
	$theme	= OT::GetStr('theme');

	if ($dataID<=0){
		JS::AlertEnd('指定ID错误');
	}

	$fileCutStr = '';
	$checkexe=$DB->query('select IM_imgMode,IM_img from '. OT_dbPref .'infoMove where IM_ID='. $dataID);
	if (! $row = $checkexe->fetch()){
		JS::AlertEnd('记录不存在');
	}
	if ($row['IM_imgMode'] == 'upImg'){
		$fileCutStr = $row['IM_img'];
	}
	unset($checkexe);

	$judResult = $DB->query('delete from '. OT_dbPref .'infoMove where IM_ID='. $dataID);
		if ($judResult){
			$alertResult='成功';
		}else{
			$alertResult='失败';
		}

	Adm::AddLog(array(
		'theme'	=> $theme,
		'note'	=> '【'. $dataTypeCN .'】删除'. $alertResult .'！',
		));

	echo('
	<script language="javascript" type="text/javascript">
	parent.data'. $dataID .'.style.display="none";
	</script>
	');
}



// 批量设置
function MoreSetDeal(){
	global $DB,$mudi,$menuFileID,$menuTreeID,$dataType,$dataTypeCN;

	$backURL		= OT::PostStr('backURL');
	$dataType		= OT::PostStr('dataType');
	$dataTypeCN		= OT::PostStr('dataTypeCN');
	$selDataID		= OT::Post('selDataID');
	$moreSetTo		= OT::PostInt('moreSetTo');
	$moreSetToCN	= OT::PostRegExpStr('moreSetToCN','sql');

	if (count($selDataID)<=0){
		JS::AlertBackEnd('请先选择要设置的记录.');
	}
	if ($moreSetTo<=0){
		JS::AlertBackEnd('请选择批量设置的操作.');
	}

	$whereStr='';
	for ($i=0; $i<count($selDataID); $i++){
		$whereStr .= ','. intval($selDataID[$i]);
	}
	if ($whereStr==''){
		JS::AlertBackEnd('请先选择要设置的记录.');
	}

	$succNum = $failNum = 0;
	$delrec=$DB->query('select IM_ID,IM_endDate from '. OT_dbPref .'infoMove where IM_ID in (0'. $whereStr .')');
	while ($row = $delrec->fetch()){
		$isResult = false;
		if (strtotime($row['IM_endDate'])){
			if (TimeDate::Diff('d', $row['IM_endDate'], '2029-12-31')>0){
				if ($moreSetTo<30){
					$isResult = $DB->UpdateParam('infoMove', array( 'IM_endDate'=>TimeDate::Add('d', $moreSetTo, $row['IM_endDate']) ), 'IM_ID='. $row['IM_ID']);
				}elseif ($moreSetTo % 30 == 0){
					$isResult = $DB->UpdateParam('infoMove', array( 'IM_endDate'=>TimeDate::Add('m', $moreSetTo/30, $row['IM_endDate']) ), 'IM_ID='. $row['IM_ID']);
				}else{
					JS::AlertBackEnd('您选择批量设置的操作有误.');
				}
			}
		}
		if ($isResult){
			$succNum ++;
		}else{
			$failNum ++;
		}
	}
	unset($delrec);

	Adm::AddLog(array(
		'note'		=> '【'. $dataTypeCN .'】批量设置成['. $moreSetToCN .']，成功'. $succNum .'条，失败'. $failNum .'条！',
		));

	JS::AlertHref('批量设置完成（成功'. $succNum .'条，失败'. $failNum .'条）.', $backURL);
}

?>
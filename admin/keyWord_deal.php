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



switch ($mudi){
	case 'add':
		$menuFileID = 137;
		$MB->IsSecMenuRight('alertBack',$menuFileID,$dataType);
		AddOrRev();
		break;

	case 'rev':
		$menuFileID = 138;
		$MB->IsSecMenuRight('alertBack',$menuFileID,$dataType);
		AddOrRev();
		break;

	case 'send':
		$menuFileID = 138;
		$MB->IsSecMenuRight('alert',$menuFileID,$dataType);
		Send();
		break;

	case 'del':
		$menuFileID = 139;
		$MB->IsSecMenuRight('alert',$menuFileID,$dataType);
		del();
		break;

	default:
		die('err');
}

$MB->Close();
$DB->Close();





// 添加与修改
function AddOrRev(){
	global $DB,$MB,$mudi,$menuFileID,$menuTreeID,$dataType,$dataTypeCN;

	$backURL		= OT::PostStr('backURL');
	$dataType		= OT::PostStr('dataType');
	$dataTypeCN		= OT::PostStr('dataTypeCN');
	$dataID			= OT::PostInt('dataID');
	$theme			= OT::PostStr('theme');
	$webURL			= OT::PostStr('webURL');
	$themeSize		= OT::PostInt('themeSize');
	$themeColor		= OT::PostStr('themeColor');
	$themeB			= OT::PostStr('themeB');
	$themeU			= OT::PostStr('themeU');
	$themeStyle	= '';
		if ($themeSize > 0){ $themeStyle .= 'font-size:'. $themeSize .'px;'; }
		if ($themeColor != ''){ $themeStyle .= 'color:'. $themeColor .';'; }
		if ($themeB != ''){ $themeStyle .= 'font-weight:'. $themeB .';'; }
		if ($themeU != ''){ $themeStyle .= 'text-decoration:'. $themeU .';'; }

	$useNum			= OT::PostInt('useNum');
	$rank			= OT::PostInt('rank');
	$isUse			= OT::PostInt('isUse');

	if ($theme=='' || $webURL==''){
		JS::AlertBackEnd('表单内容接收不全');
	}
	
	$record = array();
	$record['KW_theme']		= $theme;
	$record['KW_URL']		= $webURL;
	$record['KW_useNum']	= $useNum;
	$record['KW_rank']		= $rank;
	$record['KW_isUse']		= $isUse;
	if (AppBase::Jud()){
		$record['KW_themeStyle']= $themeStyle;
	}

	if ($dataID==0){
		$alertMode='添加';
		$record['KW_type']	= $dataType;
	
		$judResult = $DB->InsertParam('keyWord',$record);

	}else{
		$alertMode='修改';
		
		$judResult = $DB->UpdateParam('keyWord',$record,'KW_ID='. $dataID);

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


	JS::AlertHref($alertMode .'成功！', $backURL);
}



// 数据发送
function Send(){
	global $DB;

	$numID			= OT::GetInt('typeNum');
	$dataID			= OT::GetInt('dataID');
	$dataMode		= OT::GetStr('dataMode');
	$dataModeStr	= OT::GetStr('dataModeStr');

	$sendexe=$DB->query('select * from '. OT_dbPref .'keyWord where KW_ID='. $dataID);
		if (! $row = $sendexe->fetch()){
			JS::AlertEnd('搜索不到指定记录');
		}
		$themeStyle_size	= intval(Str::GetMark($row['KW_themeStyle'],'font-size:','px;'));
		$themeStyle_color	= Str::GetMark($row['KW_themeStyle'],'color:',';');
		$themeStyle_b		= Str::GetMark($row['KW_themeStyle'],'font-weight:',';');
		$themeStyle_u		= Str::GetMark($row['KW_themeStyle'],'text-decoration:',';');
	echo('
	<script language="javascript" type="text/javascript">
	parent.$id("numID").innerHTML="'. $numID .'";
	parent.$id("dataID").value="'. $row['KW_ID'] .'";
	parent.$id("theme").value="'. $row['KW_theme'] .'";
	parent.$id("webURL").value="'. $row['KW_URL'] .'";
	parent.$id("themeSize").value="'. $themeStyle_size .'";
	parent.$id("themeColor").value="'. $themeStyle_color .'";
	parent.$id("themeB").value="'. $themeStyle_b .'";
	parent.$id("themeU").value="'. $themeStyle_u .'";
	parent.$id("useNum").value="'. $row['KW_useNum'] .'";
	parent.$id("rank").value="'. $row['KW_rank'] .'";
	parent.$id("isUse").value="'. $row['KW_isUse'] .'";
	parent.$id("subButton").src="images/button_rev.gif";
	</script>
	');
	unset($sendexe);
}



// 删除
function del(){
	global $DB,$dataType,$dataTypeCN;

	$dataID	= OT::GetInt('dataID');
	$theme	= OT::GetStr('theme');

	if ($dataID<=0){
		JS::AlertEnd('指定ID错误！');
	}

	$judResult = $DB->query('delete from '. OT_dbPref .'keyWord where KW_ID='. $dataID);
	if ($judResult){
		$alertResult = '成功';
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
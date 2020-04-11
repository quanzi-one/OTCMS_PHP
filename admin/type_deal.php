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
		$menuFileID = 133;
		$MB->IsSecMenuRight('alertBack',$menuFileID,$dataType);
		AddOrRev();
		break;

	case 'rev':
		$menuFileID = 134;
		$MB->IsSecMenuRight('alertBack',$menuFileID,$dataType);
		AddOrRev();
		break;

	case 'send':
		$menuFileID = 134;
		$MB->IsSecMenuRight('alert',$menuFileID,$dataType);
		Send();
		break;

	case 'del':
		$menuFileID = 135;
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
	$rank			= OT::PostInt('rank');

	if ($theme==''){
		JS::AlertBackEnd('表单内容接收不全');
	}
	
	$record=array();
	$record['TP_theme']		= $theme;
	$record['TP_rank']		= $rank;
	$dealrec=$DB->query('select * from '. OT_dbPref .'type where TP_ID='. $dataID);
		if (! $row = $dealrec->fetch()){
			$alertMode='添加';
			$record['TP_type'] = $dataType;

			$judResult = $DB->InsertParam('type',$record);
		}else{
			$alertMode='修改';

			$judResult = $DB->UpdateParam('type',$record,'TP_ID='. $dataID);
		}
	unset($dealrec);

	if ($judResult){
		$alertResult = '成功';
	}else{
		$alertResult = '失败';
	}

	Adm::AddLog(array(
		'theme'	=> $theme,
		'note'	=> '【'. $dataTypeCN .'】'. $alertMode . $alertResult .'！',
		));

	JS::AlertHrefEnd($alertMode . $alertResult .'！',$backURL);

}



// 数据发送
function Send(){
	global $DB,$mudi,$menuFileID,$menuTreeID,$dataType,$dataTypeCN;

	$numID		= OT::GetInt('typeNum');
	$dataID		= OT::GetInt('dataID');
	$dataMode	= OT::GetStr('dataMode');
	$dataModeStr= OT::GetStr('dataModeStr');

	$sendexe=$DB->query('select * from '. OT_dbPref .'type where TP_ID='. $dataID);
	if (! $row = $sendexe->fetch()){
		JS::AlertEnd('搜索不到指定记录');
	}
	echo('
	<script language="javascript" type="text/javascript">
	parent.$id("numID").innerHTML="'. $numID .'";
	parent.$id("dataID").value="'. $row['TP_ID'] .'";
	parent.$id("theme").value="'. $row['TP_theme'] .'";
	parent.$id("rank").value="'. $row['TP_rank'] .'";
	parent.$id("subButton").src="images/button_rev.gif";
	</script>
	');
	unset($sendexe);
}



// 删除
function del(){
	global $DB,$mudi,$menuFileID,$menuTreeID,$dataType,$dataTypeCN;

	$dataID = OT::GetInt('dataID');
	$theme	= OT::GetStr('theme');

	if ($dataID<=0){
		JS::AlertEnd('指定ID错误！');
	}

	$judResult = $DB->query('delete from '. OT_dbPref .'type where TP_ID='. $dataID);
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
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



switch($mudi){
	case 'add': case 'rev':
		$menuFileID = 200;
		$MB->IsSecMenuRight('alertBack',$menuFileID,$dataType);
		AddOrRev();
		break;

	case 'addBad':
		AddBad();
		break;

	case 'send':
		$menuFileID = 200;
		$MB->IsSecMenuRight('alert',$menuFileID,$dataType);
		Send();
		break;

	case 'del':
		$menuFileID = 200;
		$MB->IsSecMenuRight('alert',$menuFileID,$dataType);
		del();
		break;

	case 'moreDel':
		$menuFileID = 200;
		$MB->IsSecMenuRight('alert',$menuFileID,$dataType);
		MoreDel();
		break;

	default:
		die('err');
}

$MB->Close();
$DB->Close();





// 添加与修改
function AddOrRev(){
	global $DB,$mudi,$menuFileID,$menuTreeID,$dataType,$dataTypeCN;

	$backURL	= OT::PostStr('backURL');
	$dataType	= OT::PostStr('dataType');
	$dataTypeCN	= OT::PostStr('dataTypeCN');

	$webType	= OT::PostStr('webType');
	$dataID		= OT::PostInt('dataID');
	$ip			= OT::PostStr('ip');
	$note		= OT::PostStr('note');

	if ($webType=='' || $ip==''){
		JS::AlertEnd('表单内容接收不全');
	}

	$record = array();
	$record['UI_ip']	= $ip;
	$record['UI_note']	= $note;
	if ($dataID==0){
		$alertMode='添加';
		$record['UI_type'] = $webType;
		$record['UI_time'] = TimeDate::Get();
		$record['UI_date'] = TimeDate::Get('date');
	
		$judResult = $DB->InsertParam('userIp',$record);

	}else{
		$alertMode='修改';

		$judResult = $DB->UpdateParam('userIp',$record,'UI_ID='. $dataID);
	}
	if ($judResult){
		$alertResult = '成功';
	}else{
		$alertResult = '失败';
	}

	Adm::AddLog(array(
		'note'		=> '【'. $dataTypeCN .'】'. $alertMode . $alertResult .'！',
		));

	JS::AlertHref(''. $alertMode .'成功！',$backURL);
}



// 加入黑名单
function AddBad(){
	global $DB,$mudi,$menuFileID,$menuTreeID;

	$dataType	= OT::GetStr('dataType');
	$dataTypeCN	= OT::GetStr('dataTypeCN');
	$theme		= OT::GetStr('theme');

	$ipStr		= OT::GetRegExpStr('ipStr','sql+.');
	$userID		= OT::GetInt('userID');

	if ($ipStr==''){
		JS::AlertEnd('表单内容接收不全');
	}
	
	$dealrec = $DB->query("select * from ". OT_dbPref ."userIp where UI_type='bad' and UI_ip=". $DB->ForStr($ipStr));
	if ($dealrec->fetch()){
		JS::AlertEnd('黑名单里已存在该IP');
	}
	$menuFileID = 200;
	$MB->IsSecMenuRight('alertBack',$menuFileID,$dataType);

	$record = array();
	$record['UI_type']	= 'bad';
	$record['UI_time']	= TimeDate::Get();
	$record['UI_ip']	= $ipStr;
	$record['UI_note']	= '来至用户注册（'. $theme .'）';

	$judResult = $DB->InsertParam('userIp',$record);
		if ($judResult){
			$alertResult='成功';
		}else{
			$alertResult='失败';
		}

	Adm::AddLog(array(
		'theme'	=> $theme,
		'note'	=> '【'. $dataTypeCN .'】加入黑名单'. $alertResult .'！',
		));

	JS::AlertHref('加入黑名单成功！',$backURL);
}



// 数据发送
function Send(){
	global $DB,$mudi,$menuFileID,$menuTreeID;

	$dataMode		= OT::GetStr('dataMode');
	$dataModeStr	= OT::GetStr('dataModeStr');
	$numID			= OT::GetInt('typeNum');
	$dataID			= OT::GetInt('dataID');

	$sendexe=$DB->query('select * from '. OT_dbPref .'userIp where UI_ID='. $dataID);
	if (! $row = $sendexe->fetch()){
		JS::AlertEnd('搜索不到指定记录');
	}
	echo('
	<script language="javascript" type="text/javascript">
	parent.$id("numID").innerHTML="'. $numID .'";
	parent.$id("dataID").value="'. $row['UI_ID'] .'";
	parent.$id("webType").value="'. $row['UI_type'] .'";
	parent.$id("ip").value="'. $row['UI_ip'] .'";
	parent.$id("note").value="'. $row['UI_note'] .'";
	parent.$id("subButton").src="images/button_rev.gif";
	</script>
	');
	unset($sendexe);
}



// 删除
function del(){
	global $DB,$mudi,$menuFileID,$menuTreeID;

	$dataType	= OT::GetStr('dataType');
	$dataTypeCN	= OT::GetStr('dataTypeCN');
	$dataID		= OT::GetInt('dataID');
	$theme		= OT::GetStr('theme');

	if ($dataID <= 0){
		JS::AlertEnd('指定ID错误');
	}

	$judResult = $DB->query('delete from '. OT_dbPref .'userIp where UI_ID='. $dataID);
		if ($judResult){
			$alertResult='成功';
		}else{
			$alertResult='失败';
		}

	Adm::AddLog(array(
		'theme'	=> $theme,
		'note'	=> '【'. $dataTypeCN .'】删除'. $alertResult .'！',
		));

	echo('<script language="javascript" type="text/javascript">parent.$id("data'. $dataID .'").style.display="none"</script>');
}



// 批量删除
Function MoreDel(){
	global $DB,$mudi,$menuFileID,$menuTreeID,$systemArr;

	$backURL	= OT::PostStr('backURL');
	$selDataID	= OT::Post('selDataID');
	$dataType	= OT::PostStr('dataType');
	$dataTypeCN	= OT::PostStr('dataTypeCN');

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

	$judResult = $DB->query('delete from '. OT_dbPref .'userIp where UI_ID in (0'. $whereStr .')');
	if ($judResult){
		$alertResult = '成功';
	}else{
		$alertResult = '失败';
	}

	echo('
	<script language="javascript" type="text/javascript">
	alert("批量删除'. $alertResult .'.");document.location.href="'. $backURL .'";
	</script>
	');

}

?>
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
	case 'del':
		$menuFileID = 142;
		$MB->IsSecMenuRight('alertClose',$menuFileID,$dataType);
		del();
		break;

	case 'moreDel':
		$menuFileID = 142;
		$MB->IsSecMenuRight('alertClose',$menuFileID,$dataType);
		MoreDel();
		break;

	case 'revContent':
		$menuFileID = 141;
		$MB->IsSecMenuRight('alertClose',$menuFileID,$dataType);
		RevContent();
		break;

	case 'reply':
		$menuFileID = 141;
		$MB->IsSecMenuRight('alertClose',$menuFileID,$dataType);
		reply();
		break;

	case 'setState':
		$menuFileID = 151;
		$MB->IsSecMenuRight('alertClose',$menuFileID,$dataType);
		SetState();
		break;

	default:
		die('err');
}

$MB->Close();
$DB->Close();





// 删除
function del(){
	global $DB,$mudi,$menuFileID,$menuTreeID,$dataType,$dataTypeCN;

	$dataID	= OT::GetInt('dataID');
	$theme	= OT::GetStr('theme');

	if ($dataID<=0){
		JS::AlertEnd('指定ID错误');
	}

	$judResult = $DB->query('delete from '. OT_dbPref .'infoMessage where IM_ID='. $dataID);
		if ($judResult){
			$alertResult = '成功';
		}else{
			$alertResult = '失败';
		}

	Adm::AddLog(array(
		'title'	=> '昵称',
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
	global $DB,$mudi,$menuFileID,$menuTreeID;

	$dataType	= OT::PostStr('dataType');
	$dataTypeCN	= OT::PostStr('dataTypeCN');
	$backURL	= OT::PostStr('backURL');
	$selDataID	= OT::Post('selDataID');

	if (count($selDataID)<=0){
		JS::AlertBackEnd('请先选择要删除的记录.');
	}

	$whereStr='';
	for ($i=0; $i<count($selDataID); $i++){
		$whereStr .= ','. intval($selDataID[$i]);
	}
	if ($whereStr==''){
		JS::AlertBackEnd('请先选择要删除的记录.');
	}

	$judResult = $DB->query('delete from '. OT_dbPref .'infoMessage where IM_ID in (0'. $whereStr .')');
		if ($judResult){
			$alertResult='成功';
		}else{
			$alertResult='失败';
		}

	Adm::AddLog(array(
		'note'		=> '【'. $dataTypeCN .'】批量删除'. $alertResult .'！',
		));

	JS::AlertHrefEnd('批量删除'. $alertResult .'.',$backURL);
}



// 修改内容
function RevContent(){
	global $DB,$mudi,$menuFileID,$menuTreeID,$dataType,$dataTypeCN;

	$backURL		= OT::PostStr('backURL');
	$dataType		= OT::PostStr('dataType');
	$dataTypeCN		= OT::PostStr('dataTypeCN');
	$dataID			= OT::PostInt('dataID');
	$vote1			= OT::PostInt('vote1');
	$vote2			= OT::PostInt('vote2');
	$newContent		= OT::PostRStr('newContent');

	$judResult = $DB->UpdateParam('infoMessage',array('IM_vote1'=>$vote1, 'IM_vote2'=>$vote2, 'IM_content'=>Str::MoreReplace($newContent,'html')),'IM_ID='. $dataID);
		if ($judResult){
			$alertResult='成功';
		}else{
			$alertResult='失败';
		}

	Adm::AddLog(array(
		'note'	=> '【'. $dataTypeCN .'】修改内容'. $alertResult .'！',
		));

	JS::AlertHref('修改成功',str_replace('&judRevContent=true','',$backURL));
}



// 保存回复
function reply(){
	global $DB,$mudi,$menuFileID,$menuTreeID;

	$backURL		= OT::PostStr('backURL');
	$theme			= OT::PostStr('theme');
	$dataType		= OT::PostStr('dataType');
	$dataTypeCN		= OT::PostStr('dataTypeCN');
	$dataID			= OT::PostInt('dataID');
	$replyContent	= OT::PostRStr('replyContent');
	$isReply		= 0;
		if (strlen($replyContent)>0){ $isReply=1; }

	$record = array();
	$record['IM_replyTime']	= TimeDate::Get();
	$record['IM_isReply']	= $isReply;
	$record['IM_reply']		= $replyContent;

	$judResult = $DB->UpdateParam('infoMessage',$record,'IM_ID='. $dataID);
		if ($judResult){
			$alertResult='成功';
		}else{
			$alertResult='失败';
		}

	Adm::AddLog(array(
		'title'	=> '昵称',
		'theme'	=> $theme,
		'note'	=> '【'. $dataTypeCN .'】回复保存'. $alertResult .'！',
		));

	JS::AlertClose('保存'. $alertResult .'.');
}



// 保存追加回复
function AddReply(){
	global $DB,$mudi,$menuFileID,$menuTreeID;

	$backURL		= OT::PostStr('backURL');
	$dataType		= OT::PostStr('dataType');
	$dataTypeCN		= OT::PostStr('dataTypeCN');
	$theme			= OT::PostStr('theme');
	$dataID			= OT::PostInt('dataID');
	$addReplyContent= OT::PostRStr('addReplyContent');

	$revrec=$DB->query('select IM_reply from '. OT_dbPref .'infoMessage where IM_ID='. $dataID);
		if (! $row = $revrec->fetch()){ JS::AlertBackEnd('找不到该记录.'); }

	$judResult = $DB->UpdateParam('infoMessage',array('IM_reply'=>$row['IM_reply'] .'<br /><div style="color:red;">管理员回复：<br />'. Str::MoreReplace($addReplyContent,'html') .'</div>'),'IM_ID='. $dataID);
	unset($revrec);
		if ($judResult){
			$alertResult='成功';
		}else{
			$alertResult='失败';
		}

	Adm::AddLog(array(
		'title'	=> '昵称',
		'theme'	=> $theme,
		'note'	=> '【'. $dataTypeCN .'】回复保存'. $alertResult .'！',
		));

	echo('
	<script language="javascript" type="text/javascript">
	alert("保存'. $alertResult .'");parent.document.location.reload();
	</script>
	');
}



// 设置状态
function SetState(){
	global $DB,$mudi,$menuFileID,$menuTreeID,$dataType,$dataTypeCN;

	$backURL		= OT::GetStr('backURL');
	$dataType		= OT::GetStr('dataType');
	$dataTypeCN		= OT::GetStr('dataTypeCN');
	$theme			= OT::PostStr('theme');
	$dataID			= OT::GetInt('dataID');
	$newState		= OT::GetInt('newState');

	$judResult = $DB->query('update '. OT_dbPref .'infoMessage set IM_state='. $newState .' where IM_ID='. $dataID);
		if ($judResult){
			$alertResult='成功';
		}else{
			$alertResult='失败';
		}

	if ($newState == 1){ $stateCN='审核通过'; }else{ $stateCN='待审核'; }

	Adm::AddLog(array(
		'title'	=> '昵称',
		'theme'	=> $theme,
		'note'	=> '【'. $dataTypeCN .'】设置状态（'. $stateCN .'）'. $alertResult .'！',
		));

	JS::AlertHref('状态修改'. $alertResult,$backURL);

}

?>
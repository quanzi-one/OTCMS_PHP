<?php
require(dirname(__FILE__) .'/check.php');
$skin->CheckIframe();



//打开用户表，并检测用户是否登录
$MB->Open('','login');

$skin->WebTop();

echo('
<script language="javascript" type="text/javascript" src="js/inc/list.js?v='. OT_VERSION .'"></script>
<script language="javascript" type="text/javascript" src="js/userGroup.js?v='. OT_VERSION .'"></script>
');


switch ($mudi){
	case 'add':
		$MB->IsSecMenuRight('alertBack',180,$dataType);
		AddOrRev();
		break;

	case 'rev':
		$MB->IsSecMenuRight('alertBack',182,$dataType);
		AddOrRev();
		break;

	case 'manage':
		$MB->IsSecMenuRight('alertBack',181,$dataType);
		manage();
		break;

	case 'show':
		$MB->IsSecMenuRight('alertClose',181,$dataType);
		show();
		break;

	default:
		die('err');
}

$skin->WebBottom();

$MB->Close();
$DB->Close();





// 添加或修改
function AddOrRev(){
	global $DB,$skin,$mudi,$dataType,$dataTypeCN;

	$dataMode	= OT::GetStr('dataMode');
	$dataModeStr= OT::GetStr('dataModeStr');
	$backURL	= OT::GetStr('backURL');
	$dataID		= OT::GetInt('dataID');

	if ($mudi=='rev'){
		$revexe=$DB->query('select * from '. OT_dbPref .'userGroup where UG_ID='. $dataID);
		if (! $row = $revexe->fetch()){
			JS::AlertBackEnd('无该记录！['. $dataID .']');
		}
		$UG_theme			= $row['UG_theme'];
		$UG_infoTotalNum	= $row['UG_infoTotalNum'];
		$UG_infoDayNum		= $row['UG_infoDayNum'];
		$UG_infoScore1		= $row['UG_infoScore1'];
		$UG_infoScore2		= $row['UG_infoScore2'];
		$UG_infoScore3		= $row['UG_infoScore3'];
		$UG_event			= $row['UG_event'];
		$UG_note			= $row['UG_note'];
		$UG_rank			= $row['UG_rank'];
		$UG_state			= $row['UG_state'];
		$UG_infoMoney		= floatval($row['UG_infoMoney']);
		$UG_kaitongDay		= $row['UG_kaitongDay'];
		$UG_kaitongMoney	= floatval($row['UG_kaitongMoney']);
		$UG_kaitongScore1	= $row['UG_kaitongScore1'];
		$UG_kaitongScore2	= $row['UG_kaitongScore2'];
		$UG_kaitongScore3	= $row['UG_kaitongScore3'];
		$UG_xufeiDay		= $row['UG_xufeiDay'];
		$UG_xufeiMoney		= floatval($row['UG_xufeiMoney']);
		$UG_xufeiScore1		= $row['UG_xufeiScore1'];
		$UG_xufeiScore2		= $row['UG_xufeiScore2'];
		$UG_xufeiScore3		= $row['UG_xufeiScore3'];
		$UG_workDay			= $row['UG_workDay'];
		$UG_workMoney		= floatval($row['UG_workMoney']);
		$UG_workScore1		= $row['UG_workScore1'];
		$UG_workScore2		= $row['UG_workScore2'];
		$UG_workScore3		= $row['UG_workScore3'];
		unset($revexe);

		$mudiCN = '修改';
	}else{
		$UG_theme			= '';
		$UG_infoTotalNum	= 0;
		$UG_infoDayNum		= 999;
		$UG_infoScore1		= 0;
		$UG_infoScore2		= 0;
		$UG_infoScore3		= 0;
		$UG_event			= '';
		$UG_note			= '';
		$UG_rank			= intval($DB->GetOne("select max(UG_rank) from ". OT_dbPref ."userGroup"))+10;
		$UG_state			= 1;
		$UG_infoMoney		= 0;
		$UG_kaitongDay		= 31;
		$UG_kaitongMoney	= '';
		$UG_kaitongScore1	= '';
		$UG_kaitongScore2	= '';
		$UG_kaitongScore3	= '';
		$UG_xufeiDay		= 31;
		$UG_xufeiMoney		= '';
		$UG_xufeiScore1		= '';
		$UG_xufeiScore2		= '';
		$UG_xufeiScore3		= '';
		$UG_workDay			= '';
		$UG_workMoney		= '';
		$UG_workScore1		= '';
		$UG_workScore2		= '';
		$UG_workScore3		= '';

		$mudiCN = '添加';
	}

	if ($mudi=='rev'){
		echo('<div onclick="history.back();" class="font2_1 padd8 pointer">&lt;&lt;&ensp;【返回上级】</div>');
	}

	echo('
	<form id="dealForm" name="dealForm" method="post" action="userGroup_deal.php?mudi='. $mudi .'&nohrefStr=close" onsubmit="return CheckForm()">
	<input type="hidden" id="dataType" name="dataType" value="'. $dataType .'" />
	<input type="hidden" id="dataTypeCN" name="dataTypeCN" value="'. $dataTypeCN .'" />
	<input type="hidden" id="dataMode" name="dataMode" value="'. $dataMode .'" />
	<input type="hidden" id="dataModeStr" name="dataModeStr" value="'. $dataModeStr .'" />
	<input type="hidden" id="dataID" name="dataID" value="'. $dataID .'" />
	');
	if ($backURL!=''){
		echo('<input type="hidden" id="backURL" name="backURL" value="'. $backURL .'" />');
	}else{
		echo('<script language="javascript" type="text/javascript">document.write(\'<input type="hidden" id="backURL" name="backURL" value="\'+ document.location.href +\'" />\')</script>');
	}

	$userSysArr = Cache::PhpFile('userSys');

	$disMoneyStr = '';
	if ($userSysArr['US_isScore2'] == 1){ $score2Style = ''; }else{ $score2Style = 'display:none;'; }
	if ($userSysArr['US_isScore3'] == 1){ $score3Style = ''; }else{ $score3Style = 'display:none;'; }

	$skin->TableTop('share_'. $mudi .'.gif','',$mudiCN . $dataTypeCN);
		echo('
		<table style="width:98%;" align="center" cellpadding="0" cellspacing="0" summary="" class="padd3td">
		<tr>
			<td align="right" style="width:150px;">名称：</td>
			<td><input type="text" id="theme" name="theme" size="25" style="width:300px;" value="'. $UG_theme .'" /></td>
		</tr>
		<tr>
			<td align="right">总投稿数限制：</td>
			<td><input type="text" id="infoTotalNum" name="infoTotalNum" size="25" style="width:50px;" value="'. $UG_infoTotalNum .'" /> 篇&ensp;&ensp;<span class="font2_2">（0表示不限制）</span></td>
		</tr>
		<tr>
			<td align="right">每日投稿限制：</td>
			<td><input type="text" id="infoDayNum" name="infoDayNum" size="25" style="width:50px;" value="'. $UG_infoDayNum .'" /> 篇</td>
		</tr>
		<tr>
			<td align="right">允许最大阅读/扣积分：</td>
			<td align="left" colspan="5">
				积分1（'. $userSysArr['US_score1Name'] .'）<input type="text" id="infoScore1" name="infoScore1" style="width:45px;" value="'. $UG_infoScore1 .'" onkeyup="if (this.value!=FiltInt(this.value)){this.value=FiltInt(this.value)}" />
				<span style="'. $score2Style .'">&ensp;，积分2（'. $userSysArr['US_score2Name'] .'）<input type="text" id="infoScore2" name="infoScore2" style="width:45px;" value="'. $UG_infoScore2 .'" onkeyup="if (this.value!=FiltInt(this.value)){this.value=FiltInt(this.value)}" /></span>
				<span style="'. $score3Style .'">&ensp;，积分3（'. $userSysArr['US_score3Name'] .'）<input type="text" id="infoScore3" name="infoScore3" style="width:45px;" value="'. $UG_infoScore3 .'" onkeyup="if (this.value!=FiltInt(this.value)){this.value=FiltInt(this.value)}" /></span>&ensp;
			</td>
		</tr>
		<tr>
			<td align="right">附加功能：</td>
			<td>
				<label title="打钩该选项，该会员组用户将禁止投稿。"><input type="checkbox" name="event[]" value="|禁止投稿|" '. Is::InstrChecked($UG_event,'|禁止投稿|') .' />前台禁止投稿</label>&ensp;&ensp;&ensp;
				<label title="当前台投稿开启审核时，打钩该选项，可让该会员组用户直接审核通过。"><input type="checkbox" name="event[]" value="|投稿免审核|" '. Is::InstrChecked($UG_event,'|投稿免审核|') .' />前台投稿免审核</label>'. $skin->TishiBox('当前台投稿开启审核时，打钩该选项，可让该用户直接审核通过。') .'&ensp;&ensp;&ensp;
			</td>
		</tr>
		<tr>
			<td align="right">备注：</td>
			<td style="padding:3px"><textarea id="note" name="note" style="width:300px;height:60px;">'. $UG_note .'</textarea></td>
		</tr>
		<tr>
			<td align="right">排序：</td>
			<td style="padding:3px"><input type="text" id="rank" name="rank" size="25" style="width:50px;" value="'. $UG_rank .'" />&ensp;</td>
		</tr>
		<tr>
			<td align="right">状态：</td>
			<td>
				<label><input type="radio" name="state" value="1" '. Is::Checked($UG_state,1) .' />启用</label>&ensp;&ensp;
				<label><input type="radio" name="state" value="0" '. Is::Checked($UG_state,0) .' />禁用</label>
			</td>
		</tr>
		'. AppUserGroup::UserGroupItem2($UG_kaitongDay, $UG_kaitongMoney, $UG_kaitongScore1, $UG_kaitongScore2, $UG_kaitongScore3, $UG_xufeiDay, $UG_xufeiMoney, $UG_xufeiScore1, $UG_xufeiScore2, $UG_xufeiScore3, $userSysArr, $disMoneyStr, $score2Style, $score3Style) .'
		'. AppUserGroupWork::UserGroupItem2($UG_workDay, $UG_workMoney, $UG_workScore1, $UG_workScore2, $UG_workScore3, $userSysArr, $disMoneyStr, $score2Style, $score3Style) .'
		</table>
		');
	$skin->TableBottom();

	echo('
	<table style="height:16px;" cellpadding="0" cellspacing="0" summary=""><tr><td></td></tr></table>

	<center><input type="image" src="images/button_'. $mudi .'.gif" /></center>

	</form>
	');
}



// 会员组管理
function manage(){
	global $DB,$MB,$skin,$mudi,$dataType,$dataTypeCN,$pageCount,$recordCount;

	$dataMode	= OT::GetStr('dataMode');
	$dataModeStr= OT::GetStr('dataModeStr');

	echo('
	<div style="padding:6px;">
		<input type="button" value="新增会员组" onclick=\'document.location.href="?mudi=add&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&backURL="+ encodeURIComponent(document.location.href);\' />
	</div>

	<form id="listForm" name="listForm" method="post" action="userGroup_deal.php?mudi=moreDel" onsubmit="return CheckListForm()">
	<script language="javascript" type="text/javascript">document.write(\'<input type="hidden" name="backURL" value="\'+ document.location.href +\'">\')</script>
	<input type="hidden" name="dataType" value="'. $dataType .'" />
	<input type="hidden" name="dataTypeCN" value="'. $dataTypeCN .'" />
	');

	$skin->TableTop2('share_list.gif','',$dataTypeCN .'列表');
	$skin->TableItemTitle('4%,4%,12%,5%,18%,37%,5%,5%,8%','序号,ID号,名称,人数,备注,属性,排序,状态,修改&ensp;&ensp;删除');

	$pageSize	= $MB->mMbRow['MB_itemNum'];		//每页条数
	$page		= OT::GetInt('page');
	$showRow=$DB->GetLimit('select * from '. OT_dbPref .'userGroup order by UG_rank ASC',$pageSize,$page);
	if ($showRow){
		$recordCount=$DB->GetRowCount();
		$pageCount=ceil($recordCount/$pageSize);
		if ($page < 1 || $page > $pageCount){$page=1;}

		echo('
		<tbody class="tabBody padd3td">
		');
		$number=1+($page-1)*$pageSize;
		$rowCount = count($showRow);
		for ($i=0; $i<$rowCount; $i++){
			if ($i % 2 == 1){$bgcolor='class="tabColorTr"';}else{$bgcolor='';}
			$themeAddi = '';
			if ($showRow[$i]['UG_ID'] == 1){ $themeAddi .= '<div style="color:red;">【内置默认组】</div>'; }

			$tougaoStr = '<span style="color:green;font-weight:bold;">允许</span>';
			$shenheStr = '<span style="color:red;font-weight:bold;">关闭</span>';
			if (strpos($showRow[$i]['UG_event'],'|禁止投稿|') !== false){
				$tougaoStr = '<span style="color:red;font-weight:bold;">禁止</span>';
			}
			if (strpos($showRow[$i]['UG_event'],'|投稿免审核|') !== false){
				$shenheStr = '<span style="color:green;font-weight:bold;">开启</span>';
			}

			echo('
			<tr '. $bgcolor .' id="data'. $showRow[$i]['UG_ID'] .'">
				<td align="center">'. $number .'</td>
				<td align="center">'. $showRow[$i]['UG_ID'] .'</td>
				<td align="center">'. $showRow[$i]['UG_theme'] . $themeAddi .'</td>
				<td align="center">'. $DB->GetOne('select count(UE_ID) from '. OT_dbPref .'users where UE_groupID='. $showRow[$i]['UG_ID']) .'</td>
				<td align="center">'. $showRow[$i]['UG_note'] .'</td>
				<td align="left" style="padding-left:6px;line-height:1.6;">
					总投稿数'. ($showRow[$i]['UG_infoTotalNum'] > 0 ? '≤ <span style="color:red;font-weight:bold;">'. $showRow[$i]['UG_infoTotalNum'] .'</span> 篇' : '<span style="color:red;font-weight:bold;">无限制</span>') .'；
					每日投稿≤ <span style="color:red;font-weight:bold;">'. $showRow[$i]['UG_infoDayNum'] .'</span> 篇；
					投稿：'. $tougaoStr .'；免审核：'. $shenheStr .'
					<div>最大阅读/扣积分（积分1≤ <span style="color:red;font-weight:bold;">'. $showRow[$i]['UG_infoScore1'] .'</span>，积分2≤ <span style="color:red;font-weight:bold;">'. $showRow[$i]['UG_infoScore2'] .'</span>，积分3≤ <span style="color:red;font-weight:bold;">'. $showRow[$i]['UG_infoScore3'] .'</span>）</div>
					'. AppUserGroup::UserGroupItem($showRow[$i]['UG_kaitongDay'], $showRow[$i]['UG_kaitongMoney'], $showRow[$i]['UG_kaitongScore1'], $showRow[$i]['UG_kaitongScore2'], $showRow[$i]['UG_kaitongScore3'], $showRow[$i]['UG_xufeiDay'], $showRow[$i]['UG_xufeiMoney'], $showRow[$i]['UG_xufeiScore1'], $showRow[$i]['UG_xufeiScore2'], $showRow[$i]['UG_xufeiScore3']) .'
					'. AppUserGroupWork::UserGroupItem($showRow[$i]['UG_workDay'], $showRow[$i]['UG_workMoney'], $showRow[$i]['UG_workScore1'], $showRow[$i]['UG_workScore2'], $showRow[$i]['UG_workScore3']) .'
				</td>
				<td align="center">'. $showRow[$i]['UG_rank'] .'</td>
				<td align="center">'. Adm::SwitchBtn('userGroup',$showRow[$i]['UG_ID'],$showRow[$i]['UG_state'],'state') .'</td>
				<td align="center">
					<!-- <img src="images/img_det.gif" style="cursor:pointer" onclick=\'var a=window.open("?mudi=show&nohrefStr=close&dataMode='. $dataMode .'&dataModeStr='. $dataModeStr .'&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&dataID='. $showRow[$i]['UG_ID'] .'")\' alt="" />&ensp;&ensp; -->
					<img src="images/img_rev.gif" style="cursor:pointer" onclick=\'document.location.href="?mudi=rev&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&dataMode='. $dataMode .'&dataID='. $showRow[$i]['UG_ID'] .'&backURL="+ encodeURIComponent(document.location.href)\' alt="" />&ensp;&ensp;
					<img src="images/img_del.gif" style="cursor:pointer" onclick=\'if(confirm("你确定要删除？")==true){DataDeal.location.href="userGroup_deal.php?mudi=del&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&theme='. urlencode($showRow[$i]['UG_theme']) .'&dataID='. $showRow[$i]['UG_ID'] .'"}\' alt="" />
				</td>
			</tr>
			');
		$number += 1;
		}
		echo('</tbody>');
	}
	unset($showRow);

	echo('</form>');

	$skin->TableBottom2($pageCount, $pageSize, $recordCount);

	echo('<div style="padding:6px;color:red;">提醒：【内置默认组】应设置为最低权限组，所有用户组到期后都会自动降为【内置默认组】</div>');
}



function show(){
	global $DB,$dataType,$dataTypeCN;

	$dataID		= OT::GetInt('dataID');
	$dataMode	= OT::GetStr('dataMode');
	$dataModeStr= OT::GetStr('dataModeStr');

	$showexe=$DB->query('select * from '. OT_dbPref .'userGroup where UG_ID='. $dataID);
	if ($row = $showexe->fetch()){
		JS::AlertCloseEnd('指定ID错误！');
	}else{
		echo('
		<script language="javascript" type="text/javascript">document.title="会员组详细信息";</script>
		<table style="width:700px;" align="center" cellpadding="0" cellspacing="0" border="0" summary=""><tr><td>
		');
		$skin->TableTop('share_list.gif','','会员组详细信息');
		echo('
		<table style="width:100%;" align="center" cellpadding="0" cellspacing="0" border="0" summary="">
		<tr><td width="60"></td><td></td></tr>
		<tr><td valign="top" align="right">名称：</td><td>'. $row['UG_theme'] .'</td></tr>
		<tr><td valign="top" align="right"></td></tr>
		<tr><td valign="top" align="right">排序：</td><td>'. $row['UG_rank'] .'</td></tr>
		<tr><td valign="top" align="right">状态：</td><td>'. StateCN($row['UG_state']) .'</td></tr>
		</table>');

		$skin->TableBottom();

		echo('
		</td></tr></table>
		');
	}

}

function StateCN($num){
	$retStr='[未知]';
	switch ($num){
		case 1:
			$retStr='已审核';
			break;
	
		case 0:
			$retStr='未审核';
			break;
	
	}

	return $retStr;
}

?>
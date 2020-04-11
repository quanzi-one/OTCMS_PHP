<?php
require(dirname(__FILE__) .'/check.php');
$skin->CheckIframe();



//打开用户表，并检测用户是否登录
$MB->Open('','login');

$skin->WebTop();

$MB->IsAdminRight('alertBack');
$dataTypeCN = '在线升级';


echo('
<script language="javascript" type="text/javascript" src="js/inc/list.js?v='. OT_VERSION .'"></script>
');


switch($mudi){
	case 'manage':
		manage();
		break;

	case 'show':
		show();
		break;

	default:
		die('err');
}

$skin->WebBottom();

$MB->Close();
$DB->Close();





function manage(){
	global $DB,$MB,$skin,$mudi,$dataType,$dataTypeCN,$pageCount,$recordCount;

	$dataMode		= OT::GetStr('dataMode');
	$dataModeStr	= OT::GetStr('dataModeStr');

	$refVer			= OT::GetInt('refVer','');
	$refVerTime		= OT::GetInt('refVerTime','');
	$refIsLock		= OT::GetInt('refIsLock',-1);
	$refState		= OT::PostInt('refState',-1);
	$refUpdateNote	=OT::GetRegExpStr('refUpdateNote','sql');
	$refDate1		= OT::GetStr('refDate1');
		if (! strtotime($refDate1)){ $refDate1=''; }
	$refDate2		= OT::GetStr('refDate2');
		if (! strtotime($refDate2)){ $refDate2=''; }

	$SQLstr='select * from '. OT_dbPref .'fileVer where 1=1';

	if ($refVer != ''){ $SQLstr .= " and FV_ver='". $refVer ."'"; }
	if ($refVerTime != ''){ $SQLstr .= " and FV_verTime='". $refVerTime ."'"; }
	if ($refIsLock > -1){ $SQLstr .= ' and FV_isLock='. $refIsLock; }
	if ($refState > -1){ $SQLstr .= ' and FV_state='. $refState; }
	if ($refUpdateNote != ''){ $SQLstr .= " and FV_updateNote like '%". $refUpdateNote ."%'"; }
	if ($refDate1 != ''){ $SQLstr .= ' and FV_time>='. $DB->ForTime($refDate1); }
	if ($refDate2 != ''){ $SQLstr .= ' and FV_time<='. $DB->ForTime(TimeDate::Add('d',1,$refDate2)); }

	$orderName = OT::GetStr('orderName');
		if (in_array($orderName,array('ver','verTime','fileNum','failNum','isLock','state'))==false){ $orderName='time'; }
	$orderSort = OT::GetStr('orderSort');
		if ($orderSort!='ASC'){ $orderSort='DESC'; }

	echo('
	<div class="padd5">
		<input type="button" value="修复文章缺失标题MD5" onclick=\'DataDeal.location.href="update_deal.php?mudi=updateField&ver=themeMd5";\' />
	</div>
	');

	$skin->TableTop('share_refer.gif','',$dataTypeCN .'查询');
		echo('
		<form id="refForm" name="refForm" method="get" action="">
		<input type="hidden" name="mudi" value="'. $mudi .'" />
		<input type="hidden" name="dataType" value="'. $dataType .'" />
		<input type="hidden" name="dataTypeCN" value="'. $dataTypeCN .'" />

		<table width="98%" align="center" border="0" cellSpacing="0" cellPadding="0" summary="" class="padd5">
		<tr>
			<td style="width:35%;">
				&ensp;&ensp;&ensp;&ensp;版本：<input type="text" name="refVer" size="20" value="'. $refVer .'" />
			</td>
			<td style="width:30%;">
				版本时间：<input type="text" name="refVerTime" size="14" value="'. $refVerTime .'" />
			</td>
			<td style="width:35%;">
				锁定：
				<select id="refIsLock" name="refIsLock">
				<option value=""></option>
				<option value="1" '. Is::Selected($refIsLock,1) .'>是</option>
				<option value="0" '. Is::Selected($refIsLock,0) .'>否</option>
				</select>
				&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;状态：
				<select id="refState" name="refState">
				<option value=""></option>
				<option value="1" '. Is::Selected($refState,1) .'>显示</option>
				<option value="0" '. Is::Selected($refState,0) .'>关闭</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				更新记录：<input type="text" name="refUpdateNote" size="20" value="'. $refUpdateNote .'" />
			</td>
			<td colspan="2">
				添加日期：<input type="text" name="refDate1" size="10" value="'. $refDate1 .'" onfocus="WdatePicker()" />&ensp;
				至&ensp;<input type="text" name="refDate2" size="10" value="'. $refDate2 .'" onfocus="WdatePicker()" />
			</td>
		</tr>
		<tr>
			<td align="center" style="padding:5px;padding-top:20px" colspan="3">
				<input type="image" src="images/button_refer.gif" />
				&ensp;&ensp;&ensp;&ensp;
				<img src="images/button_reset.gif" onclick=\'document.location.href="?mudi='. $mudi .'&dataMode='. $dataMode .'&dataModeStr='. $dataModeStr .'&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'"\' style="cursor:pointer" alt="" />
			</td>
		</tr>
		</table>
		</form>
		');
	$skin->TableBottom();

	echo('
	<br />

	<form id="listForm" name="listForm" method="post" action="update_deal.php?mudi=moreDel" onsubmit="return CheckListForm()">
	<script language="javascript" type="text/javascript">document.write (\'<input type="hidden" name="backURL" value="\'+ document.location.href +\'" />\')</script>
	<input type="hidden" name="dataType" value="'. $dataType .'" />
	<input type="hidden" name="dataTypeCN" value="'. $dataTypeCN .'" />
	');

	$skin->TableTop2('share_list.gif','',$dataTypeCN .'列表');
	$skin->TableItemTitle('4%,5%,6%,25%,9%,11%,11%,7%,8%,14%','<input type="checkbox" id="selAll" name="selAll" onclick="CheckBoxAll()" />,序号,'. $skin->ShowArrow('版本','ver',$orderName,$orderSort) .','. $skin->ShowArrow('升级后版本时间','verTime',$orderName,$orderSort) .','. $skin->ShowArrow('文件数','fileNum',$orderName,$orderSort) .','. $skin->ShowArrow('失败数','failNum',$orderName,$orderSort) .','. $skin->ShowArrow('添加日期','time',$orderName,$orderSort) .','. $skin->ShowArrow('锁定','isLock',$orderName,$orderSort) .','. $skin->ShowArrow('状态','state',$orderName,$orderSort) .',详细&ensp;删除');

	$pageSize	= $MB->mMbRow['MB_itemNum'];		//每页条数
	$page		= OT::GetInt('page');
	$showRow=$DB->GetLimit($SQLstr .' order by FV_'. $orderName .' '. $orderSort .'',$pageSize,$page);
	if (! $showRow){
		$skin->TableNoData();
	}else{
		$recordCount=$DB->GetRowCount();
		$pageCount=ceil($recordCount/$pageSize);
		if ($page < 1 || $page > $pageCount){$page=1;}

		echo('<tbody class="tabBody padd3">');
		$number=1+($page-1)*$pageSize;
		$rowCount = count($showRow);
		for ($i=0; $i<$rowCount; $i++){
			if ($i % 2 == 1){ $bgcolor='class="tabColorTr"'; }else{ $bgcolor=''; }

			echo('
			<tr '. $bgcolor .' id="data'. $showRow[$i]['FV_ID'] .'">
				<td align="center"><input type="checkbox" name="selDataID[]" value="'. $showRow[$i]['FV_ID'] .'" /></td>
				<td align="center">'. $number .'</td>
				<td align="center">'. $showRow[$i]['FV_ver'] .'</td>
				<td align="center"><span class="font1_2d">'. $showRow[$i]['FV_verTimeStart'] .' → </span>'. $showRow[$i]['FV_verTime'] .'</td>
				<td align="center">'. $showRow[$i]['FV_fileNum'] .'</td>
				<td align="center">'. $showRow[$i]['FV_failNum'] .'</td>
				<td align="center">'. $showRow[$i]['FV_time'] .'<br /></td>
				<td align="center">'. Adm::SwitchBtn('fileVer',$showRow[$i]['FV_ID'],$showRow[$i]['FV_isLock'],'isLock','') .'<br /></td>
				<td align="center">'. Adm::SwitchBtn('fileVer',$showRow[$i]['FV_ID'],$showRow[$i]['FV_state'],'state','') .'<br /></td>
				<td align="center">
					<img src="images/img_det.gif" style="cursor:pointer" onclick=\'var a=window.open("?mudi=show&nohrefStr=close&dataMode='. $dataMode .'&dataModeStr='. $dataModeStr .'&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&dataID='. $showRow[$i]['FV_ID'] .'")\' alt="" />&ensp;&ensp;
					<img src="images/img_del.gif" style="cursor:pointer" onclick=\'if(confirm("你确定要删除？")==true){DataDeal.location.href="update_deal.php?mudi=del&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&theme='. urlencode($showRow[$i]['FV_ver'] .' ('. $showRow[$i]['FV_verTime'] .')') .'&dataID='. $showRow[$i]['FV_ID'] .'"}\' alt="" />
				</td>
			</tr>
			');
			$number ++;
		}
		echo('
		</tbody>
		<!-- <tr class="tabColorB padd5">
			<td align="left" colspan="20">
				<input type="button" value="全选" onclick="AllSelBox()" class="form_button1" />
				<input type="button" value="反选" onclick="RevSelBox()" class="form_button1" />
				&ensp;
				<input type="submit" value="批量删除" />
			</td>
		</tr> -->
		');
	}
	unset($showRow);

	echo('</form>');

	$skin->TableBottom2($pageCount, $pageSize, $recordCount);
}



function show(){
	global $DB,$skin;

	$dataMode		= OT::GetStr('dataMode');
	$dataModeStr	= OT::GetStr('dataModeStr');
	$dataType		= OT::GetStr('dataType');
	$dataTypeCN		= OT::GetStr('dataTypeCN');
	$dataID			= OT::GetInt('dataID');

	$showexe=$DB->query('select * from '. OT_dbPref .'fileVer where FV_ID='. $dataID);
		if (! $row = $showexe->fetch()){
			JS::AlertCloseEnd('指定ID错误');
		}else{
			echo('<table width="700" align="center" cellpadding="0" cellspacing="0" border="0" summary=""><tr><td>');
			$skin->TableTop('share_list.gif','',''. $dataTypeCN .'更新记录');
				echo('
				<table style="width:100%" align="center" cellpadding="0" cellspacing="0" border="0" class="padd3">
				<tr>
					<td style="width:100px;" align="right" valign="top" style="padding-top:6px;">更新记录：</td>
					<td style="line-height:1.4;">'. str_replace(PHP_EOL,'<br />',$row['FV_updateNote']) .'</td>
				</tr>
				</table>
				');
			$skin->TableBottom();
			echo('</td></tr></table>');
		}
	unset($showexe);
}

?>
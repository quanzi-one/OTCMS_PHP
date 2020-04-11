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


//打开用户表，并检测用户是否登录
$MB->Open('','login');

$skin->WebTop();


echo('
<script language="javascript" type="text/javascript" src="js/inc/list.js?v='. OT_VERSION .'"></script>
<script language="javascript" type="text/javascript" src="js/ipRecord.js?v='. OT_VERSION .'"></script>
');


switch($mudi){
	case 'manage':
		$MB->IsSecMenuRight('alertBack',200,$dataType);
		manage();
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

	$refType	= OT::GetRegExpStr('refType','sql');
	$refIp		= OT::GetRegExpStr('refIp','sql+,');
	$refDate1	= OT::GetStr('refDate1');
		if (! strtotime($refDate1)){ $refDate1=''; }
	$refDate2	= OT::GetStr('refDate2');
		if (! strtotime($refDate2)){ $refDate2=''; }

	$SQLstr='select * from '. OT_dbPref .'userIp where 1=1';

	if ($refType != ''){ $SQLstr .= " and UI_type='". $refType ."'"; }
	if ($refIp != ''){ $SQLstr .= " and UI_ip like '%". $refIp ."%'"; }
	if ($refDate1 != ''){ $SQLstr .= ' and UI_time>='. $DB->ForTime($refDate1); }
	if ($refDate2 != ''){ $SQLstr .= ' and UI_time<='. $DB->ForTime(TimeDate::Add('d',1,$refDate2)); }

	$orderName = OT::GetStr('orderName');
		if (strpos('|time|ip|','|'. $orderName .'|') === false){ $orderName='time'; }
	$orderSort = OT::GetStr('orderSort');
		if ($orderSort!='ASC'){ $orderSort='DESC'; }


	$skin->TableTop('share_refer.gif','',$dataTypeCN .'查询');
	echo('
	<form id="refForm" name="refForm" method="get" action="">
	<input type="hidden" name="mudi" value="'. $mudi .'" />
	<input type="hidden" name="dataType" value="'. $dataType .'" />

	<table width="95%" align="center" border="0" cellSpacing="0" cellPadding="0" summary="">
	<tr>
		<td width="20%" align="left" style="padding:5px">
			类型：
			<select name="refType">
				<option value="">&ensp;</option>
				<option value="bad" '. Is::Selected($refType,'bad') .'>黑名单</option>
				<option value="reg" '. Is::Selected($refType,'reg') .'>注册</option>
			</select>
		</td>
		<td width="40%" align="left" style="padding:5px">
			&ensp;&ensp;&ensp;&ensp;昵称：
			<input type="text" name="refIp" size="18" value="'. $refIp .'" />
		</td>
		<td width="40%" align="left" style="padding:5px">
			发生日期：<input type="text" name="refDate1" size="10" value="'. $refDate1 .'" onfocus="WdatePicker()" />&ensp;
			至&ensp;<input type="text" name="refDate2" size="10" value="'. $refDate2 .'" onfocus="WdatePicker()" />
		</td>
	</tr>
	<tr>
		<td align="center" style="padding:5px;padding-top:20px" colspan="3">
			<input type="image" src="images/button_refer.gif" />
			&ensp;&ensp;&ensp;&ensp;
			<img src="images/button_reset.gif" onclick=\'document.location.href="?mudi='. $mudi .'&dataTypeCN='. urlencode($dataTypeCN) .'"\' style="cursor:pointer" alt="" />
		</td>
	</tr>
	</table>
	</form>
	');

	$skin->TableBottom();

	echo('
	<br />

	<form id="listForm" name="listForm" method="post" action="ipRecord_deal.php?mudi=moreDel&dataType='. $dataType .'" onsubmit="return CheckListForm()">
	<script language="javascript" type="text/javascript">document.write (\'<input type="hidden" name="backURL" value="\'+ document.location.href +\'" />\')</script>
	');

	$skin->TableTop2('share_list.gif','',$dataTypeCN .'列表');
	$skin->TableItemTitle('5%,5%,12%,20%,30%,18%,10%','<input type="checkbox" id="selAll" name="selAll" onclick="CheckBoxAll()" />,序号,'. $skin->ShowArrow('类型','type',$orderName,$orderSort) .','. $skin->ShowArrow('IP地址','ip',$orderName,$orderSort) .','. $skin->ShowArrow('备注','note',$orderName,$orderSort) .','. $skin->ShowArrow('发生时间','time',$orderName,$orderSort) .',修改&ensp;删除');

	$pageSize	= $MB->mMbRow['MB_itemNum'];		//每页条数
	$page		= OT::GetInt('page');
	$showRow=$DB->GetLimit($SQLstr .' order by UI_'. $orderName .' '. $orderSort,$pageSize,$page);
	if (! $showRow){
		$skin->TableNoData();
		$closeMoreBtn = false;
	}else{
		$closeMoreBtn = true;
		$recordCount=$DB->GetRowCount();
		$pageCount=ceil($recordCount/$pageSize);
		if ($page < 1 || $page > $pageCount){$page=1;}

		echo('
		<tbody class="tabBody padd3td">
		');
		$number=1+($page-1)*$pageSize;
		$rowCount = count($showRow);
		for ($i=0; $i<$rowCount; $i++){
			if ($i % 2 == 1){ $bgcolor='class="tabColorTr"'; }else{ $bgcolor=''; }

			echo('
			<tr id="data'. $showRow[$i]['UI_ID'] .'" '. $bgcolor .'>
				<td align="center"><input type="checkbox" name="selDataID[]" value="'. $showRow[$i]['UI_ID'] .'" /></td>
				<td align="center">'. $number .'</td>
				<td align="center">'. IpTypeCN($showRow[$i]['UI_type']) .'<br /></td>
				<td align="center">'. $showRow[$i]['UI_ip'] .'<br /></td>
				<td align="center">'. $showRow[$i]['UI_note'] .'<br /></td>
				<td align="center">'. $showRow[$i]['UI_time'] .'<br /></td>
				<td align="center">
					<img src="images/img_rev.gif" style="cursor:pointer;" onclick=\'DataDeal.location.href="ipRecord_deal.php?mudi=send&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&dataMode='. $dataMode .'&dataModeStr='. $dataModeStr .'&dataID='. $showRow[$i]['UI_ID'] .'&typeNum='. $number .'"\' alt="" />&ensp;&ensp;
					<img src="images/img_del.gif" style="cursor:pointer" onclick=\'if(confirm("你确定要删除？")==true){DataDeal.location.href="ipRecord_deal.php?mudi=del&dataType='. $dataType .'&dataID='. $showRow[$i]['UI_ID'] .'"}\' alt="" />
				</td>
			</tr>
			');
			$number ++;
		}
		echo('
		</tbody>
		');
	}
	echo('
	</form>

	<tbody class="tabBody padd5">
	<form method="post" name="dealForm" action="ipRecord_deal.php?mudi=add" onsubmit="return CheckDealForm()">
	<script language="javascript" type="text/javascript">document.write(\'<input type="hidden" name="backURL" value="\'+ document.location.href +\'">\')</script>
	<input type="hidden" id="dataType" name="dataType" value="'. $dataType .'" />
	<input type="hidden" id="dataTypeCN" name="dataTypeCN" value="'. $dataTypeCN .'" />
	<input type="hidden" id="dataID" name="dataID" value="0" />
	<tr>
		<td width="5%" align="center"><br /></td>
		<td width="5%" id="numID" align="center"><br /></td>
		<td width="12%" align="center">
			<select id="webType" name="webType">
				<option value="">&ensp;</option>
				<option value="bad" '. Is::Selected($refType,'bad') .'>黑名单</option>
				<option value="reg" '. Is::Selected($refType,'reg') .'>注册</option>
			</select>
		</td>
		<td width="20%" align="center"><input type="text" id="ip" name="ip" size="16" /></td>
		<td width="30%" align="center"><input type="text" id="note" name="note" size="20" /></td>
		<td width="18%" align="center"><br /></td>
		<td width="10%" align="center"><input id="subButton" type="image" src="images/button_add.gif" /></td>
	</tr>
	</form>
	</tbody>
	');
	unset($showRow);
	
	if ($closeMoreBtn){
		echo('
		<tr class="tabColorB">
			<td align="left" class="padd5" colspan="20">
				<input type="button" value="全选" onclick="AllSelBox()" class="form_button1" />
				<input type="button" value="反选" onclick="RevSelBox()" class="form_button1" />
				&ensp;
				<input type="button" value="批量删除" class="form_button2" onclick=\'if (CheckListForm()){ $id("listForm").submit(); }\' />
			</td>
		</tr>
		');
	}

	$skin->TableBottom2($pageCount, $pageSize, $recordCount);

}



function IpTypeCN($str){
	switch ($str){
		case 'bad':		return '黑名单';			break;
		case 'reg':		return '注册';			break;
	}
}
?>
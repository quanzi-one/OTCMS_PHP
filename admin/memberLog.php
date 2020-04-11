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
<script language="javascript" type="text/javascript" src="js/memberLog.js?v='. OT_VERSION .'"></script>
');


$dataTypeCN = '后台人员操作记录'; 


switch ($mudi){
	case 'manage':
		$MB->IsAdminRight('alertBack');
//		$MB->IsSecMenuRight('alertBack',147,$dataType);
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

	$refRealName= OT::GetRegExpStr('refRealName','sql');
	$refNote	= OT::GetRegExpStr('refNote','sql');
	$refDate1	= OT::GetStr('refDate1');
		if (! strtotime($refDate1)){$refDate1='';}
	$refDate2	= OT::GetStr('refDate2');
		if (! strtotime($refDate2)){$refDate2='';}

	$SQLstr='select * from '. OT_dbPref .'memberLog where (1=1)';

	if ($refRealName!=''){$SQLstr .= " and ML_realname like '%". $refRealName ."%'";}
	if ($refNote!=''){$SQLstr .= " and ML_note like '%". $refNote ."%'";}
	if ($refDate1!=''){$SQLstr .= ' and ML_time>='. $DB->ForTime($refDate1);}
	if ($refDate2!=''){$SQLstr .= ' and ML_time<'. $DB->ForTime(TimeDate::Add('d',1,$refDate2));}

	$orderName=OT::GetStr('orderName');
		if (in_array($orderName,array('realname','note','ipCN','readNum','time'))==false){$orderName='time';}
	$orderSort=OT::GetStr('orderSort');
		if ($orderSort!='ASC'){$orderSort='DESC';}

	$skin->TableTop('share_refer.gif','',$dataTypeCN .'查询');
	echo('
	<form id="refForm" name="refForm" method="get" action="">
	<input type="hidden" name="mudi" value="'. $mudi .'" />
	<input type="hidden" name="dataType" value="'. $dataType .'" />

	<table align="center" border="0" cellSpacing="0" cellPadding="0" summary="" class="padd5td">
	<tr>
		<td width="30%">
			&ensp;&ensp;&ensp;&ensp;称呼：
			<input type="text" name="refRealName" size="14" value="'. $refRealName .'" />
		</td>
		<td width="30%">
			&ensp;&ensp;&ensp;&ensp;记录：
			<input type="text" name="refNote" size="14" value="'. $refNote .'" />
		</td>
		<td width="40%">
			&ensp;&ensp;操作日期：<input type="text" name="refDate1" size="10" value="'. $refDate1 .'" onfocus="WdatePicker()" />&ensp;
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

	echo('<br />');

	$skin->TableTop2('share_list.gif','',$dataTypeCN .'列表');
	$skin->TableItemTitle('6%,14%,55%,7%,19%','序号,'. $skin->ShowArrow('称呼','realname',$orderName,$orderSort) .','. $skin->ShowArrow('记录','note',$orderName,$orderSort) .','. $skin->ShowArrow('次数','readNum',$orderName,$orderSort) .','. $skin->ShowArrow('操作时间','time',$orderName,$orderSort) .'');

	$pageSize	= $MB->mMbRow['MB_itemNum'];		//每页条数
	$page		= OT::GetInt('page');
	$showRow=$DB->GetLimit($SQLstr .' order by ML_'. $orderName .' '. $orderSort,$pageSize,$page);
	if ($showRow){
		$recordCount=$DB->GetRowCount();
		$pageCount=ceil($recordCount/$pageSize);
		if ($page < 1 || $page > $pageCount){$page=1;}

		echo('
		<tbody class="tabBody padd5td">
		');
		$number=1+($page-1)*$pageSize;
		$rowCount = count($showRow);
		for ($i=0; $i<$rowCount; $i++){
			if ($i % 2 == 1){ $bgcolor='class="tabColorTr"'; }else{ $bgcolor=''; }

			echo('
			<tr '. $bgcolor .'>
				<td align="center">'. $number .'</td>
				<td align="center">'. $showRow[$i]['ML_realname'] .'<br /></td>
				<td align="left">'. $showRow[$i]['ML_note'] .'<br /></td>
				<td align="center">'. $showRow[$i]['ML_readNum'] .'<br /></td>
				<td align="center">'. $showRow[$i]['ML_time'] .'<br /></td>
			</tr>
			');
		$number ++;
		}

		echo('
		</tbody>
		<tr class="tabColorB padd5td">
			<td align="left" colspan="20">
				<input type="button" value="3天前记录删除" onclick=\'if(confirm("你确定要删除3天前记录？")==true){document.location.href="memberLog_deal.php?mudi=monthDel&day=3&backURL="+ encodeURIComponent(document.location.href)}\' />
				<input type="button" value="7天前记录删除" onclick=\'if(confirm("你确定要删除7天前记录？")==true){document.location.href="memberLog_deal.php?mudi=monthDel&day=7&backURL="+ encodeURIComponent(document.location.href)}\' />
				<input type="button" value="14天前记录删除" onclick=\'if(confirm("你确定要删除14天前记录？")==true){document.location.href="memberLog_deal.php?mudi=monthDel&day=14&backURL="+ encodeURIComponent(document.location.href)}\' />
				<input type="button" value="30天前记录删除" onclick=\'if(confirm("你确定要删除30天前记录？")==true){document.location.href="memberLog_deal.php?mudi=monthDel&day=30&backURL="+ encodeURIComponent(document.location.href)}\' />
			</td>
		</tr>
		');
	}
	unset($showRow);

	$skin->TableBottom2($pageCount, $pageSize, $recordCount);

}

?>
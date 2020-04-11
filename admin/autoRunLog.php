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
<script language="javascript" type="text/javascript" src="js/share.js?v='. OT_VERSION .'"></script>
');

$MB->IsSecMenuRight('alertBack',502,$dataType);

switch ($mudi){
	case 'manage':
		manage();
		break;

	case 'infoTypeList':
		InfoTypeList();
		break;

	case 'infoList':
		InfoList();
		break;

	default:
		die('err');
}

$skin->WebBottom();

$MB->Close();
$DB->Close();





function manage(){
	global $DB,$MB,$skin,$mudi,$dataType,$dataTypeCN,$pageCount,$recordCount;

	$refType	= OT::GetRegExpStr('refType','sql');
	$refContent	= OT::GetRegExpStr('refContent','sql');
	$refDate1	= OT::GetStr('refDate1');
		if (! strtotime($refDate1)){$refDate1='';}
	$refDate2	= OT::GetStr('refDate2');
		if (! strtotime($refDate2)){$refDate2='';}

	$SQLstr='select * from '. OT_dbPref .'autoRunLog where (1=1)';

	if ($refType != ''){ $SQLstr .= " and ARL_type=". $DB->ForStr($refType); }
	if ($refContent != ''){ $SQLstr .= " and ARL_note like '%". $refContent ."%'"; }
	if ($refDate1 != ''){ $SQLstr .= ' and ARL_time>='. $DB->ForTime($refDate1); }
	if ($refDate2 != ''){ $SQLstr .= ' and ARL_time<'. $DB->ForTime(TimeDate::Add('d',1,$refDate2)); }

	$orderName = OT::GetStr('orderName');
		if (in_array($orderName,array('type','content','time'))==false){ $orderName = 'time'; }
	$orderSort = OT::GetStr('orderSort');
		if ($orderSort!='ASC'){$orderSort='DESC';}

	$skin->TableTop('share_refer.gif','',$dataTypeCN .'查询');
	echo('
	<form id="refForm" name="refForm" method="get" action="">
	<input type="hidden" name="mudi" value="'. $mudi .'" />
	<input type="hidden" name="dataType" value="'. $dataType .'" />

	<table align="center" border="0" cellSpacing="0" cellPadding="0" summary="" class="padd5td">
	<tr>
		<td width="30%">
			&ensp;&ensp;&ensp;&ensp;类型：
			<select name="refType">
				<option value=""></option>
				<option value="home" '. Is::Selected($refType, 'home') .'>首页</option>
				<option value="list" '. Is::Selected($refType, 'list') .'>列表页</option>
				<option value="show" '. Is::Selected($refType, 'show') .'>内容页</option>
				<option value="coll" '. Is::Selected($refType, 'coll') .'>采集</option>
			</select>
		</td>
		<td width="30%">
			&ensp;&ensp;&ensp;&ensp;备注：
			<input type="text" name="refContent" size="14" value="'. $refContent .'" />
		</td>
		<td width="40%">
			&ensp;&ensp;发生日期：<input type="text" name="refDate1" size="10" value="'. $refDate1 .'" onfocus="WdatePicker()" />&ensp;
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
	<div style="padding:6px;float:left;">
		<input type="button" value="列表页等待列表" onclick=\'document.location.href="?mudi=infoTypeList&dataTypeCN='. urlencode('列表页等待列表') .'";\' />&ensp;
		<input type="button" value="内容页等待列表" onclick=\'document.location.href="?mudi=infoList&dataTypeCN='. urlencode('内容页等待列表') .'";\' />&ensp;
	</div>
	');

	$skin->TableTop2('share_list.gif','',$dataTypeCN .'列表');
	$skin->TableItemTitle('5%,8%,16%,72%','序号,'. $skin->ShowArrow('类型','type',$orderName,$orderSort) .','. $skin->ShowArrow('发生时间','time',$orderName,$orderSort) .','. $skin->ShowArrow('内容','note',$orderName,$orderSort) .'');

	$pageSize	= $MB->mMbRow['MB_itemNum'];		//每页条数
	$page		= OT::GetInt('page');
	$showRow=$DB->GetLimit($SQLstr .' order by ARL_'. $orderName .' '. $orderSort,$pageSize,$page);
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
				<td align="center">'. TypeCN($showRow[$i]['ARL_type']) .'<br /></td>
				<td align="center">'. $showRow[$i]['ARL_time'] .'<br /></td>
				<td align="left">'. $showRow[$i]['ARL_content'] .'<br /></td>
			</tr>
			');
			$number ++;
		}

		echo('
		</tbody>
		<tr class="tabColorB padd5td">
			<td align="left" colspan="20">
				<input type="button" value="1天前记录删除" onclick=\'if(confirm("你确定要删除1天前记录？")==true){document.location.href="autoRunLog_deal.php?mudi=monthDel&day=1&backURL="+ encodeURIComponent(document.location.href)}\' />
				<input type="button" value="3天前记录删除" onclick=\'if(confirm("你确定要删除3天前记录？")==true){document.location.href="autoRunLog_deal.php?mudi=monthDel&day=3&backURL="+ encodeURIComponent(document.location.href)}\' />
				<input type="button" value="7天前记录删除" onclick=\'if(confirm("你确定要删除7天前记录？")==true){document.location.href="autoRunLog_deal.php?mudi=monthDel&day=7&backURL="+ encodeURIComponent(document.location.href)}\' />
				<input type="button" value="14天前记录删除" onclick=\'if(confirm("你确定要删除14天前记录？")==true){document.location.href="autoRunLog_deal.php?mudi=monthDel&day=14&backURL="+ encodeURIComponent(document.location.href)}\' />
				<input type="button" value="30天前记录删除" onclick=\'if(confirm("你确定要删除30天前记录？")==true){document.location.href="autoRunLog_deal.php?mudi=monthDel&day=30&backURL="+ encodeURIComponent(document.location.href)}\' />
			</td>
		</tr>
		');
	}
	unset($showRow);

	$skin->TableBottom2($pageCount, $pageSize, $recordCount);

}



function InfoTypeList(){
	global $DB,$MB,$skin,$mudi,$dataType,$dataTypeCN,$pageCount,$recordCount,$systemArr,$dbPathPart;

	$autoRunSysArr = Cache::PhpFile('autoRunSys');
	$todayTime = TimeDate::Get();

	echo('
	<div class="padd5">
		<input type="button" value="返回自动操作日志管理" onclick=\'document.location.href="?mudi=manage&amp;dataType='. $dataType .'&amp;dataTypeCN='. urlencode('自动操作日志') .'";\' />
	</div>
	');

	$skin->TableTop2('share_list.gif','',''. $dataTypeCN);
	$skin->TableItemTitle('5%,31%,8%,16%,12%,16%,12%','ID号,名称,层级,PC最后生成时间,PC当前/总页码,WAP最后生成时间,WAP当前/总页码');

	echo('
	<tbody class="tabBody padd3">
	<tr>
		<td align="center"><br /></td>
		<td align="left">0、网站公告('. $systemArr['SYS_announName'] .')</td>
		<td align="center">内置</td>
		<td align="center">'. $autoRunSysArr['ARS_announTime'] . DiffMin($autoRunSysArr['ARS_announTime'],$todayTime) .'</td>
		<td align="center">'. $autoRunSysArr['ARS_announCurrNum'] .'/'. $autoRunSysArr['ARS_announMaxNum'] .'</td>
		<td align="center">'. $autoRunSysArr['ARS_announWapTime'] . DiffMin($autoRunSysArr['ARS_announWapTime'],$todayTime) .'</td>
		<td align="center">'. $autoRunSysArr['ARS_announWapCurrNum'] .'/'. $autoRunSysArr['ARS_announWapMaxNum'] .'</td>
	</tr>
	<tr>
		<td align="center"><br /></td>
		<td align="left">0、最新消息</td>
		<td align="center">内置</td>
		<td align="center">'. $autoRunSysArr['ARS_newTime'] . DiffMin($autoRunSysArr['ARS_newTime'],$todayTime) .'</td>
		<td align="center">'. $autoRunSysArr['ARS_newCurrNum'] .'/'. $autoRunSysArr['ARS_newMaxNum'] .'</td>
		<td align="center">'. $autoRunSysArr['ARS_newWapTime'] . DiffMin($autoRunSysArr['ARS_newWapTime'],$todayTime) .'</td>
		<td align="center">'. $autoRunSysArr['ARS_newWapCurrNum'] .'/'. $autoRunSysArr['ARS_newWapMaxNum'] .'</td>
	</tr>
	');

	$showNum = 0;
	$showexe=$DB->query('select * from '. OT_dbPref .'infoType where IT_state=1 and IT_mode='. $DB->ForStr('item') .' order by IT_htmlTime ASC');
	while ($row = $showexe->fetch()){
		if ($showNum % 2 == 1){ $bgcolor='class="tabColorTr"'; }else{ $bgcolor=''; }
		$showNum ++;

		echo('
		<tr '. $bgcolor .'>
			<td align="center">'. $row['IT_ID'] .'</td>
			<td align="left">'. $showNum .'、'. $row['IT_theme'] .'</td>
			<td align="center">'. $row['IT_level'] .'</td>
			<td align="center">'. $row['IT_htmlTime'] . DiffMin($row['IT_htmlTime'],$todayTime) .'</td>
			<td align="center">'. $row['IT_htmlCurrNum'] .'/'. $row['IT_htmlMaxNum'] .'</td>
			<td align="center">'. $row['IT_htmlWapTime'] . DiffMin($row['IT_htmlWapTime'],$todayTime) .'</td>
			<td align="center">'. $row['IT_htmlWapCurrNum'] .'/'. $row['IT_htmlWapMaxNum'] .'</td>
		</tr>
		');
	}
	unset($showexe);

	echo('
	</tbody>
	</table>
	<div class="font2_2" style="padding:6px;color:red;">提醒：当前页码=总页码时，最后生成时间才更新。</div>
	');

}



function InfoList(){
	global $DB,$MB,$skin,$mudi,$dataType,$dataTypeCN,$pageCount,$recordCount,$systemArr,$dbPathPart;

	$autoRunSysArr = Cache::PhpFile('autoRunSys');
	$todayTime = TimeDate::Get();

	echo('
	<div class="padd5">
		<input type="button" value="返回自动操作日志管理" onclick=\'document.location.href="?mudi=manage&amp;dataType='. $dataType .'&amp;dataTypeCN='. urlencode('自动操作日志') .'";\' />
		<span style="color:red;">（根据设置，只显示发布时间 '. $autoRunSysArr['ARS_htmlShowStartTime'] .' 后的文章）</span>
	</div>
	');

	$skin->TableTop2('share_list.gif','',$dataTypeCN);
	$skin->TableItemTitle('4%,5%,37%,15%,13%,13%,13%','序号,ID,标题,栏目,发布日期,PC最后生成时间,WAP最后生成时间');

	$pageSize	= $MB->mMbRow['MB_itemNum'];		//每页条数
	$page		= OT::GetInt('page');
	$showRow=$DB->GetLimit('select * from '. OT_dbPref .'info where (IF_state=1 or IF_wapState=1) and IF_isAudit=1 and IF_time>='. $DB->ForTime($autoRunSysArr['ARS_htmlShowStartTime']) .' order by IF_htmlTime ASC',$pageSize,$page);
	if (! $showRow){
		$skin->TableNoData();
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

			echo('
			<tr '. $bgcolor .'>
				<td align="center">'. $number .'</td>
				<td align="center">'. $showRow[$i]['IF_ID'] .'</td>
				<td align="left" style="word-break:break-all;">'. $showRow[$i]['IF_theme'] .'</td>
				<td align="left">'. $InfoType->TypeStrCN($showRow[$i]['IF_typeStr']) .'</td>
				<td align="center">'. $showRow[$i]['IF_time'] .'</td>
				<td align="center">'. $showRow[$i]['IF_htmlTime'] . DiffMin($showRow[$i]['IF_htmlTime'],$todayTime) .'</td>
				<td align="center">'. $showRow[$i]['IF_htmlWapTime'] . DiffMin($showRow[$i]['IF_htmlWapTime'],$todayTime) .'</td>
			</tr>
			');
			$number ++;
		}
		echo('
		</tbody>
		');
	}
	unset($showRow);

	$skin->TableBottom2($pageCount, $pageSize, $recordCount);

}



function TypeCN($str){
	switch ($str){
		case 'home':	return '首页';
		case 'list':	return '列表页';
		case 'show':	return '内容页';
		case 'coll':	return '采集';
		case 'time':	return '定时检查';
		default :		return $str;
	}
}


function DiffMin($htmlTime, $currTime=''){
	if ($currTime == ''){ $currTime = TimeDate::Get(); }
	if (strtotime($htmlTime)){
		$diff = TimeDate::Diff('min',$htmlTime,$currTime);
		if ($diff > 1440){
			return '<div style="color:red;">（'. intval($diff/1440) .'天前）</div>';
		}elseif ($diff > 60){
			return '<div style="color:red;">（'. intval($diff/60) .'小时前）</div>';
		}else{
			return '<div style="color:red;">（'. $diff .'分钟前）</div>';
		}
	}
}

?>
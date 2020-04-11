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
<script language="javascript" type="text/javascript" src="js/type.js?v='. OT_VERSION .'"></script>
');


switch ($mudi){
	case 'manage':
		$MB->IsSecMenuRight('alertBack',132,$dataType);
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

	$dataMode	= OT::GetStr('dataMode');
	$dataModeStr= OT::GetStr('dataModeStr');

	$skin->TableTop2('share_list.gif','',$dataTypeCN .'管理');
	$skin->TableItemTitle('10%,55%,15%,20%','编号,名称,排序,修改　删除');

	$pageSize	= $MB->mMbRow['MB_itemNum'];		//每页条数
	$page		= OT::GetInt('page');
	$showRow=$DB->GetLimit("select * from ". OT_dbPref ."type where TP_type='". $dataType ."' order by TP_rank ASC",$pageSize,$page);
	if (! $showRow){
		// $skin->TableNoData();
	}else{
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
			<tr id="data'. $showRow[$i]['TP_ID'] .'" '. $bgcolor .'>
				<td align="center">'. $number .'</td>
				<td align="center">'. $showRow[$i]['TP_theme'] .'</td>
				<td align="center">'. $showRow[$i]['TP_rank'] .'</td>
				<td align="center">
					<img src="images/img_rev.gif" style="cursor:pointer;" onclick=\'DataDeal.location.href="type_deal.php?mudi=send&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&dataMode='. $dataMode .'&dataModeStr='. $dataModeStr .'&dataID='. $showRow[$i]['TP_ID'] .'&typeNum='. $number .'"\' alt="" />&ensp;&ensp;
					<img src="images/img_del.gif" style="cursor:pointer;" onclick=\'if(confirm("确定删除？")==true){DataDeal.location.href="type_deal.php?mudi=del&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&theme='. urlencode($showRow[$i]['TP_theme']) .'&dataID='. $showRow[$i]['TP_ID'] .'"}\' alt="" />
				</td>
			</tr>
			');
		$number ++;
		}
		echo('
		</tbody>
		');
	}
	unset($showRow);
		
	echo('
	<form method="post" id="dealForm" name="dealForm" action="type_deal.php?mudi=add" onsubmit="return CheckForm(this)">
	<script language="javascript" type="text/javascript">document.write(\'<input type="hidden" name="backURL" value="\'+ document.location.href +\'">\')</script>
	<input type="hidden" id="dataType" name="dataType" value="'. $dataType .'" />
	<input type="hidden" id="dataTypeCN" name="dataTypeCN" value="'. $dataTypeCN .'" />
	<input type="hidden" id="dataID" name="dataID" value="0" />
	<tr>
		<td id="numID" align="center"><br /></td>
		<td align="center"><input type="text" id="theme" name="theme" size="25" /></td>
		<td align="center"><input type="text" id="rank" name="rank" size="3" value="'. (intval($DB->GetOne("select max(TP_rank) from ". OT_dbPref ."type where TP_type='". $dataType ."'"))+10) .'" onkeyUp="this.value=FiltInt(this.value)" /></td>
		<td align="center"><input id="subButton" type="image" src="images/button_add.gif" /></td>
	</tr>
	</form>
	');

	$skin->TableBottom2($pageCount, $pageSize, $recordCount);

	if ($dataType == 'writer'){
		echo('<div style="padding:5px;color:red;">名称含{%昵称%}：会自动替换为后台用户的昵称</div>');
	}

}

?>
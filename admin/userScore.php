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
<script language="javascript" type="text/javascript" src="js/userScore.js?v='. OT_VERSION .'"></script>
');



switch ($mudi){
	case 'manage':
		$MB->IsSecMenuRight('alertBack',247,$dataType);
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

	$userSysArr = Cache::PhpFile('userSys');

	$dataMode	= OT::GetStr('dataMode');
	$dataModeStr= OT::GetStr('dataModeStr');

	$payArr = array(0);
	$payexe = $DB->query('select PS_appID from '. OT_dbPref .'paySoft where PS_state=1');
	while ($row = $payexe->fetch()){
		$payArr[] = $row['PS_appID'];
	}
	unset($payexe);

	if ($userSysArr['US_isScore3'] == 0){
		$score3Str = '<span style="color:#999999;">积分3未开启</span>';
		$score3Style = 'display:none;';
	}else{
		$score3Str = $userSysArr['US_score3Name'];
		$score3Style = 'display:;';
	}
	if ($userSysArr['US_isScore2'] == 0){
		$score2Str = '<span style="color:#999999;">积分2未开启</span>';
		$score2Style = 'display:none;';
	}else{
		$score2Str = $userSysArr['US_score2Name'];
		$score2Style = 'display:;';
	}

	$skin->TableTop2('share_list.gif','',$dataTypeCN .'管理');
	$skin->TableItemTitle('6%,15%,22%,13%,13%,13%,8%,10%','编号,类型,项目,'. $userSysArr['US_score1Name'] .','. $score2Str .','. $score3Str .',排序,修改　删除');

	$pageSize	= $MB->mMbRow['MB_itemNum'];		//每页条数
	$page		= OT::GetInt('page');
	$showRow=$DB->GetLimit('select * from '. OT_dbPref .'userScore where US_appID in ('. implode(',',$payArr) .') order by US_rank ASC',$pageSize,$page); // US_state=1 and 
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
			if (in_array($showRow[$i]['US_type'],array('newsDel','bbsWriteDel','bbsReplyDel'))){
				$styleStr = 'color:red;';
			}else{
				$styleStr = '';
			}

			echo('
			<tr id="data'. $showRow[$i]['US_ID'] .'" '. $bgcolor .'>
				<td align="center">'. $number .'</td>
				<td align="center">'. $showRow[$i]['US_type'] .'</td>
				<td align="left" style="padding-left:8px;">'. $showRow[$i]['US_theme'] .'</td>
				<td align="center" style="'. $styleStr .'">'. $showRow[$i]['US_score1'] .'</td>
				<td align="center" style="'. $styleStr .'">'. $showRow[$i]['US_score2'] .'<span style="'. $score2Style .'"></span></td>
				<td align="center" style="'. $styleStr .'">'. $showRow[$i]['US_score3'] .'<span style="'. $score3Style .'"></span></td>
				<td align="center">'. $showRow[$i]['US_rank'] .'</td>
				<td align="center">
					<img src="images/img_rev.gif" style="cursor:pointer;" onclick=\'DataDeal.location.href="userScore_deal.php?mudi=send&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&dataMode='. $dataMode .'&dataModeStr='. $dataModeStr .'&dataID='. $showRow[$i]['US_ID'] .'&typeNum='. $number .'"\' alt="" />&ensp;&ensp;
					<img src="images/img_del.gif" style="cursor:pointer;" onclick=\'if(confirm("确定删除？")==true){DataDeal.location.href="userScore_deal.php?mudi=del&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&theme='. urlencode($showRow[$i]['US_theme']) .'&dataID='. $showRow[$i]['US_ID'] .'"}\' alt="" />
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
	<form method="post" id="dealForm" name="dealForm" action="userScore_deal.php?mudi=add" onsubmit="return CheckForm()">
	<script language="javascript" type="text/javascript">document.write(\'<input type="hidden" name="backURL" value="\'+ document.location.href +\'">\')</script>
	<input type="hidden" id="dataType" name="dataType" value="'. $dataType .'" />
	<input type="hidden" id="dataTypeCN" name="dataTypeCN" value="'. $dataTypeCN .'" />
	<input type="hidden" id="dataID" name="dataID" value="0" />
	<tr>
		<td id="numID" align="center"><br /></td>
		<td align="center"><br /></td>
		<td align="left"><input type="text" id="theme" name="theme" size="15" /></td>
		<td align="center"><input type="text" id="score1" name="score1" size="8" /></td>
		<td align="center"><input type="text" id="score2" name="score2" size="8" /></td> <!-- style="'. $score2Style .'" -->
		<td align="center"><input type="text" id="score3" name="score3" size="8" /></td> <!-- style="'. $score3Style .'" -->
		<td align="center"><input type="text" id="rank" name="rank" size="3" value="'. (intval($DB->GetOne('select max(US_rank) from '. OT_dbPref .'userScore'))+10) .'" onkeyUp="this.value=FiltInt(this.value)" /></td>
		<td align="center"><input id="subButton" type="image" src="images/button_rev.gif" /></td>
	</tr>
	</form>
	');

	$skin->TableBottom2($pageCount, $pageSize, $recordCount);

	echo('<div style="padding:6px;color:red;">提示：红字表示数值虽为正数，但实际是扣积分操作</div>');
}

?>
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
<script language="javascript" type="text/javascript" src="js/userLevel.js?v='. OT_VERSION .'"></script>
');


switch($mudi){
	case 'manage':
		$MB->IsSecMenuRight('alertBack',179,$dataType);
		manage();
		break;

	default:
		die('err');
}

$skin->WebBottom();

$MB->Close();
$DB->Close();





function manage(){
	global $DB,$skin,$mudi,$dataType,$dataTypeCN,$pageCount,$recordCount;

	$userSysArr = Cache::PhpFile('userSys');

	if ($userSysArr['US_isScore1'] == 1){ $score1Style = ''; }else{ $score1Style = 'display:none;'; }
	if ($userSysArr['US_isScore2'] == 1){ $score2Style = ''; }else{ $score2Style = 'display:none;'; }
	if ($userSysArr['US_isScore3'] == 1){ $score3Style = ''; }else{ $score3Style = 'display:none;'; }

	$skin->TableTop2('share_list.gif','',$dataTypeCN .'管理');
	echo('
	<colgroup>
		<col></col>
		<col></col>
		<col></col>
		<col style="'. $score1Style .'"></col>
		<col style="'. $score2Style .'"></col>
		<col style="'. $score3Style .'"></col>
		<col></col>
	</colgroup>
	');

	$skin->TableItemTitle('7%,22%,25%,11%,11%,11%,14%','等级值,等级名称,等级图片,'. $userSysArr['US_score1Name'] .','. $userSysArr['US_score2Name'] .','. $userSysArr['US_score3Name'] .',修改　删除');
	echo('
	<tbody class="tabBody padd3">
	');
	$number=0;
	$showexe = $DB->query('select * from '. OT_dbPref .'userLevel where UL_ID>=2 order by UL_score1 ASC');
	while ($row = $showexe->fetch()){
		if ($number % 2 == 1){ $bgcolor='class="tabColorTr"'; }else{ $bgcolor=''; }
	
		echo('
		<tr id="data'. $row['UL_ID'] .'" '. $bgcolor .'>
			<td align="center">'. $row['UL_num'] .'</td>
			<td align="center" style="'. $row['UL_themeStyle'] .'">'. $row['UL_theme'] .'</td>
			<td align="center"><img src="'. UsersFileAdminDir . $row['UL_img'] .'" /><br /></td>
			<td align="center">'. $row['UL_score1'] .'</td>
			<td align="center">'. $row['UL_score2'] .'</td>
			<td align="center">'. $row['UL_score3'] .'</td>
			<td align="center">
				<img src="images/img_rev.gif" style="cursor:pointer;" onclick=\'DataDeal.location.href="userLevel_deal.php?mudi=send&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&dataID='. $row['UL_ID'] .'"\' alt="" />&ensp;&ensp;
				<img src="images/img_del.gif" style="cursor:pointer;" onclick=\'if(confirm("确定删除？")==true){DataDeal.location.href="userLevel_deal.php?mudi=del&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&theme='. urlencode($row['UL_theme']) .'&dataID='. $row['UL_ID'] .'"}\' alt="" />
			</td>
		</tr>
		');
		$number ++;
	}
	unset($showexe);

	echo('
	</tbody>
	<form method="post" id="listForm" name="listForm" action="userLevel_deal.php?mudi=add" onsubmit="return CheckForm()">
	<script language="javascript" type="text/javascript">document.write(\'<input type="hidden" name="backURL" value="\'+ document.location.href +\'">\')</script>
	<input type="hidden" id="dataType" name="dataType" value="'. $dataType .'" />
	<input type="hidden" id="dataType" name="dataTypeCN" value="'. $dataTypeCN .'" />
	<input type="hidden" id="dataID" name="dataID" value="0" />
	<tr>
		<!-- <td id="numID" align="center"><br /></td> -->
		<td align="center">
			<input type="text" id="num" name="num" size="25" style="width:30px;" />
		</td>
		<td align="center">
			<input type="text" id="theme" name="theme" size="25" style="width:80px;" />
			<input type="hidden" id="themeStyle" name="themeStyle" value="" />
			<input type="hidden" id="themeStyleColor" name="themeStyleColor" value="" />
			'. Skin::ColorBox('function','theme','theme') .'
			<label><input type="checkbox" id="themeStyleB" name="themeStyleB" value="1" onclick="CheckColorBox();" />加粗</label>
		</td>
		<td align="center">
			<input type="text" id="img" name="img" size="25" style="width:100px;" />
			<input type="button" onclick=\'OT_OpenUpImg("input","img","users","")\' value="上传图片" />
		</td>
		<td align="center"><input type="text" id="score1" name="score1" size="3" onkeyUp="this.value=FiltInt(this.value)" /></td>
		<td align="center"><input type="text" id="score2" name="score2" size="3" onkeyUp="this.value=FiltInt(this.value)" /></td>
		<td align="center"><input type="text" id="score3" name="score3" size="3" onkeyUp="this.value=FiltInt(this.value)" /></td>
		<td align="center"><input id="subButton" type="image" src="images/button_add.gif" /></td>
	</tr>
	</form>
	</tbody>
	</table>
	<div style="padding:6px;color:red;">提醒：必须有个等级是各项积分都是0,作为最底层等级。</div>
	');

}

?>
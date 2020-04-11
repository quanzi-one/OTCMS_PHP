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


$MB->IsAdminRight('alertBack');


switch ($mudi){
	case 'manage':
		manage();
		break;

	case 'childManage':
		ChildManage();
		break;

	default:
		die('err');
}

$skin->WebBottom();

$MB->Close();
$DB->Close();





function manage(){
	global $DB,$skin,$mudi,$pageCount,$recordCount;

	$skin->TableTop2('share_list.gif','','网站菜单管理');
	$skin->TableItemTitle('8%,32%,35%,10%,15%','编号,主菜单名,颜色/排序,状态,子目录');

	echo('
	<tbody class="tabBody padd3td">
	');

	$payArr = array(0);
	$payexe = $DB->query('select PS_appID from '. OT_dbPref .'paySoft where PS_state=1');
	while ($row = $payexe->fetch()){
		$payArr[] = $row['PS_appID'];
	}
	unset($payexe);

	$showrec=$DB->query('select * from '. OT_dbPref .'menuTree where MT_level=0 and MT_state=1 and MT_appID in ('. implode(',',$payArr) .') order by MT_rank ASC');
	$number = 0;
	while ($row = $showrec->fetch()){
		if ((++$number) % 2 == 0){ $bgcolor='class="tabColorTr"'; }else{ $bgcolor=''; }

		echo('
		<tr id="data'. $row['MT_ID'] .'" '. $bgcolor .'>
			<td align="center">'. $number .'</td>
			<td align="center" id="theme'. $row['MT_ID'] .'" style="'. $row['MT_themeStyle'] .'">'. $row['MT_theme'] .'<br /></td>
			<td align="center">
				<form method="post" action="adminMenu_deal.php?mudi=rev" >
				<script language="javascript" type="text/javascript">document.write(\'<input type="hidden" name="backURL" value="\'+ document.location.href +\'" />\')</script>
				<input type="hidden" name="dataID" value="'. $row['MT_ID'] .'" />
				'. AdmArea::StyleInput('theme'. $row['MT_ID'],$row['MT_themeStyle'],'|noB|') .'&ensp;
				排序：<input type="text" name="rank" style="width:40px;" value="'. $row['MT_rank'] .'" />
				<input type="submit" value="修改"  />
				</form>
			</td>
			<td align="center">'. Adm::SwitchBtn('menuTree',$row['MT_ID'],$row['MT_userState'],'userState','') .'<br /></td>
			<td align="center"><a href="?mudi=childManage&amp;dataTypeCN='. urlencode($row['MT_theme']) .'&amp;dataID='. $row['MT_ID'] .'" class="font3_2">[进入子菜单]</a><br /></td>
		</tr>
		');
	}
	unset($showrec);

	echo('
	</tbody>
	</table>
	');

}



function ChildManage(){
	global $DB,$skin,$mudi,$pageCount,$recordCount;

	$dataID		= OT::GetInt('dataID');
	$dataTypeCN	= OT::GetStr('dataTypeCN');

	$payArr = array(0);
	$payexe = $DB->query('select PS_appID from '. OT_dbPref .'paySoft where PS_state=1');
	while ($row = $payexe->fetch()){
		$payArr[] = $row['PS_appID'];
	}
	unset($payexe);

	$menuOptionStr='';
	$menuexe=$DB->query('select MT_ID,MT_theme from '. OT_dbPref .'menuTree where MT_level=0 and MT_state=1 and MT_appID in ('. implode(',',$payArr) .') order by MT_rank ASC');
		while ($row =  $menuexe->fetch()){
			$menuOptionStr .= '<option value="'. $row['MT_ID'] .'" '. Is::Selected($dataID,$row['MT_ID']) .'>'. $row['MT_theme'] .'</option>';
		}
	unset($menuexe);


	$skin->TableTop2('share_list.gif','','['. $dataTypeCN .']子菜单管理');
	$skin->TableItemTitle('7%,41%,42%,10%','序号,<select onchange=\'document.location.href="?mudi='. $mudi .'&dataTypeCN="+ encodeURI(this.options[this.selectedIndex].text) +"&dataID="+ this.value\'>'. $menuOptionStr .'</select>,颜色/排序,状态');

	echo('
	<tbody class="tabBody padd3td">
	');

	$payArr = array(0);
	$payexe = $DB->query('select PS_appID from '. OT_dbPref .'paySoft where PS_state=1');
	while ($row = $payexe->fetch()){
		$payArr[] = $row['PS_appID'];
	}
	unset($payexe);

	$showrec=$DB->query('select * from '. OT_dbPref .'menuTree where MT_level=1 and MT_state=1 and MT_menuID='. $dataID .' and MT_appID in ('. implode(',',$payArr) .') order by MT_rank ASC');
	$number = 0;
	while ($row = $showrec->fetch()){
		if ((++$number) % 2 == 0){$bgcolor='class="tabColorTr"';}else{$bgcolor='';}
		echo('
		<tr id="data'. $row['MT_ID'] .'" '. $bgcolor .'>
			<td align="center">'. $number .'</td>
			<td align="center" id="theme'. $row['MT_ID'] .'" style="'. $row['MT_themeStyle'] .'">'. $row['MT_theme'] .'</td>
			<td align="center">
				<form method="post" action="adminMenu_deal.php?mudi=rev" >
				<script language="javascript" type="text/javascript">document.write(\'<input type="hidden" name="backURL" value="\'+ document.location.href +\'" />\')</script>
				<input type="hidden" name="dataID" value="'. $row['MT_ID'] .'" />
				'. AdmArea::StyleInput('theme'. $row['MT_ID'],$row['MT_themeStyle'],'|noB|') .'&ensp;
				排序：<input type="text" name="rank" style="width:40px;" value="'. $row['MT_rank'] .'" />
				<input type="submit" value="修改"  />
				</form>
			</td>
			<td align="center" class="border1_4 font1_2" style="padding:3px">'. Adm::SwitchBtn('menuTree',$row['MT_ID'],$row['MT_userState'],'userState','') .'<br /></td>
		</tr>
		');
	}
	unset($showrec);

	echo('
	</tbody>
	</table>
	<br />
	<center><a href="?mudi=manage" class="font3_1">【回到 菜单管理】</a></center>
	');

}

?>
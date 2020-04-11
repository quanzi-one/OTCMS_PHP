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

$MB->IsAdminRight('alertBack');


echo('
<script language="javascript" type="text/javascript" src="js/inc/list.js?v='. OT_VERSION .'"></script>
<script language="javascript" type="text/javascript" src="js/memberOnline.js?v='. OT_VERSION .'"></script>
');


$dataTypeCN = '后台人员在线记录'; 


switch ($mudi){
	case 'manage':
		manage();
		break;

	default:
		die('err');
}

$skin->WebBottom();

$MB->Close();
$DB->Close();





function manage(){
	global $DB,$MB,$skin,$mudi,$dataType,$dataTypeCN,$pageCount,$recordCount,$sysAdminArr;

	$refUserName = OT::GetRegExpStr('refUserName','sql');
	$refRealName = OT::GetRegExpStr('refRealName','sql');

	$SQLstr='select MO_ID,MO_time,MB_username,MB_realname from '. OT_dbPref .'memberOnline as MO left join '. OT_dbPref .'member as MB on MO.MO_userID=MB.MB_ID where (1=1)';

	if ($refUserName!=''){$SQLstr .= " and MB_username like '%". $refUserName ."%'";}
	if ($refRealName!=''){$SQLstr .= " and MB_realname like '%". $refRealName ."%'";}

	$orderName=OT::GetStr('orderName');
		if (in_array($orderName,array('MB_username','MB_realname','MO_time'))==false){$orderName='MO_time';}
	$orderSort=OT::GetStr('orderSort');
		if ($orderSort!='ASC'){$orderSort='DESC';}

	$skin->TableTop('share_refer.gif','',$dataTypeCN .'查询');
		echo('
		<form id="refForm" name="refForm" method="get" action="">
		<input type="hidden" name="mudi" value="'. $mudi .'" />
		<input type="hidden" name="dataType" value="'. $dataType .'" />

		<table align="center" border="0" cellSpacing="0" cellPadding="0" summary="" class="padd5td">
		<tr>
			<td width="50%">
				&ensp;&ensp;&ensp;&ensp;用户名：
				<input type="text" name="refUserName" size="18" value="'. $refUserName .'" />
			</td>
			<td width="50%">
				&ensp;&ensp;&ensp;&ensp;称呼：
				<input type="text" name="refRealName" size="18" value="'. $refRealName .'" />
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

	<form id="listForm" name="listForm" method="post" action="memberOnline_deal.php?mudi=moreDel&dataType='. $dataType .'" onsubmit="return CheckListForm()">
	<script language="javascript" type="text/javascript">document.write (\'<input type="hidden" name="backURL" value="\'+ document.location.href +\'" />\')</script>
	');

	$skin->TableTop2('share_list.gif','',$dataTypeCN .'列表');
	$skin->TableItemTitle('5%,5%,30%,28%,22%,10%','<input type="checkbox" id="selAll" name="selAll" onclick="CheckBoxAll()" />,序号,'. $skin->ShowArrow('用户名','MB_username',$orderName,$orderSort) .','. $skin->ShowArrow('称呼','MB_realname',$orderName,$orderSort) .','. $skin->ShowArrow('最后时间','MO_time',$orderName,$orderSort) .',删除');

	$pageSize	= $MB->mMbRow['MB_itemNum'];		//每页条数
	$page		= OT::GetInt('page');
	$showRow=$DB->GetLimit($SQLstr .' order by '. $orderName .' '. $orderSort,$pageSize,$page);
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
			if ($i % 2 == 1){ $bgcolor='class="tabColorTr"'; }else{ $bgcolor=''; }

			echo('
			<tr id="data'. $showRow[$i]['MO_ID'] .'" '. $bgcolor .'>
				<td align="center"><input type="checkbox" name="selDataID[]" value="'. $showRow[$i]['MO_ID'] .'" /></td>
				<td align="center">'. $number .'</td>
				<td align="center">'. $showRow[$i]['MB_username'] .'<br /></td>
				<td align="center">'. $showRow[$i]['MB_realname'] .'<br /></td>
				<td align="center">'. $showRow[$i]['MO_time'] . (TimeDate::Add('min',$sysAdminArr['SA_exitMinute'],$showRow[$i]['MO_time'])<TimeDate::Get() && $sysAdminArr['SA_exitMinute']>0 ? '<span class="font2_2">[超时]</span>' : '') .'<br /></td>
				<td align="center">
					<img src="images/img_del.gif" style="cursor:pointer" onclick=\'if(confirm("你确定要删除？")==true){DataDeal.location.href="memberOnline_deal.php?mudi=del&dataType='. $dataType .'&dataID='. $showRow[$i]['MO_ID'] .'"}\' alt="" />
				</td>
			</tr>
			');
			$number ++;
		}

		echo('
		</tbody>
		<tr class="tabColorB padd5td">
			<td align="left" colspan="20">
				<input type="button" value="全选" onclick="AllSelBox()" class="form_button1" />
				<input type="button" value="反选" onclick="RevSelBox()" class="form_button1" />
				&ensp;
				<input type="submit" value="批量删除" class="form_button2" />
			</td>
		</tr>
		</form>
		');
	}
	unset($showRow);

	$skin->TableBottom2($pageCount, $pageSize, $recordCount);

}

?>
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
<script language="javascript" type="text/javascript" src="js/infoMove.js?v='. OT_VERSION .'"></script>
');


switch ($mudi){
	case 'infoMove':
		$MB->IsSecMenuRight('alertBack',50,$dataType);
		InfoMove();
		break;

	case 'infoMove3':
		$MB->IsSecMenuRight('alertBack',105,$dataType);
		InfoMove3();
		break;

	default:
		die('err');
}

$skin->WebBottom();

$MB->Close();
$DB->Close();





// 友情链接
function InfoMove(){
	global $DB,$MB,$skin,$mudi,$dataType,$dataTypeCN,$pageCount,$recordCount;

	$dataMode		= OT::GetStr('dataMode');
	$dataModeStr	= OT::GetStr('dataModeStr');

	$skin->TableTop('share_add.gif','','<span id="infoTitle">添加</span>'. $dataTypeCN);
		echo('
		<a name="writeBox"></a>
		<form id="dealForm" name="dealForm" method="post" action="infoMove_deal.php?mudi=infoMove" onsubmit="return CheckDealForm()">
		<script language="javascript" type="text/javascript">document.write (\'<input type="hidden" name="backURL" value="\'+ document.location.href +\'">\')</script>
		<input type="hidden" id="dataType" name="dataType" value="'. $dataType .'" />
		<input type="hidden" id="dataTypeCN" name="dataTypeCN" value="'. $dataTypeCN .'" />
		<input type="hidden" id="dataMode" name="dataMode" value="'. $dataMode .'" />
		<input type="hidden" id="dataModeStr" name="dataModeStr" value="'. $dataModeStr .'" />
		<input type="hidden" id="dataID" name="dataID" value="0" />
		<table width="85%" align="center" cellpadding="0" cellspacing="0" summary="" class="padd3">
		<tr>
			<td width="20%" align="right">网站名：</td>
			<td width="80%" align="left"><input type="text" id="theme" name="theme" size="60" style="width:400px;" /></td>
		</tr>
		<tr>
			<td align="right">LOGO图片：</td>
			<td align="left">
				<label><input type="radio" name="imgMode" value="" checked="checked" onclick="CheckLogoMode()" />无</label>&ensp;&ensp;
				<label><input type="radio" name="imgMode" value="upImg" onclick="CheckLogoMode()" />本地</label>&ensp;&ensp;
				<label><input type="radio" name="imgMode" value="URL" onclick="CheckLogoMode()" />网络</label>&ensp;&ensp;
			</td>
		</tr>
		<tr id="imgUrlBox" style="display:none;">
			<td align="right">网络LOGO图片：</td>
			<td align="left">
				<input type="text" id="imgURL" name="imgURL" value="http://" size="60" style="width:400px;" />
			</td>
		</tr>
		<tr id="upImgBox" style="display:none;">
			<td align="right">上传LOGO图片：</td>
			<td align="left">
				<input type="text" id="upImg" name="upImg" style="width:320px" /><input type="button" onclick=\'OT_OpenUpImg("input","upImg","images","&closeWater=1")\' value="上传图片" />
			</td>
		</tr>
		<tr>
			<td align="right">链接地址：</td>
			<td align="left"><input type="text" id="webURL" name="webURL" value="http://" size="60" style="width:400px;" /></td>
		</tr>
		<tr>
			<td align="right">链接注释信息：</td>
			<td align="left"><input type="text" id="alt" name="alt" value="" size="60" style="width:400px;" /></td>
		</tr>
		<tr>
			<td align="right" valign="top">备注：</td>
			<td align="left"><textarea id="note" name="note" rows="5" cols="40" style="width:400px; height:60px;"></textarea></td>
		</tr>
		<tr>
			<td align="right">加入时间：</td>
			<td align="left"><input type="text" id="startDate" name="startDate" size="22" style="width:90px;" onfocus="WdatePicker()" class="Wdate" value="'. TimeDate::Get('date') .'" /></td>
		</tr>
		<tr>
			<td align="right">过期时间：</td>
			<td align="left">
				<input type="text" id="endDate" name="endDate" size="22" style="width:90px;" onfocus="WdatePicker()" class="Wdate" />
				&ensp;&ensp;<input type="button" value="1个月" onclick="GetEndTime(1);" />
				&ensp;&ensp;<input type="button" value="3个月" onclick="GetEndTime(3);" />
				&ensp;&ensp;<input type="button" value="6个月" onclick="GetEndTime(6);" />
				&ensp;&ensp;<input type="button" value="1年" onclick="GetEndTime(12);" />
				&ensp;&ensp;<input type="button" value="永久" onclick="GetEndTime(-1);" />
				&ensp;'. $skin->TishiBox('有实际限制作用') .'
			</td>
		</tr>
		<tr>
			<td align="right">费用：</td>
			<td align="left"><input type="text" id="cost" name="cost" size="22" style="width:90px;" /></td>
		</tr>
		<tr>
			<td align="right">排序：</td>
			<td align="left"><input type="text" id="rank" name="rank" value="'. (intval($DB->GetOne('select max(IM_rank) from '. OT_dbPref .'infoMove'))+10) .'" size="4" />
				&ensp;'. $skin->TishiBox('排序值越小，排越前。排序值之间最好有一定间隔，好方便以后穿插。') .'
			</td>
		</tr>
		<tr>
			<td align="right">电脑版状态：</td>
			<td align="left">
				<label><input type="radio" name="state" value="1" checked="checked" />显示</label>&ensp;
				<label><input type="radio" name="state" value="0" />隐藏</label>
			</td>
		</tr>
		<tr>
			<td align="right">手机版状态：</td>
			<td align="left">
				<label><input type="radio" name="wapState" value="1" checked="checked" />显示</label>&ensp;
				<label><input type="radio" name="wapState" value="0" />隐藏</label>&ensp;&ensp;
			</td>
		</tr>
		<tr>
			<td style="padding-top:25px;" colspan="2" align="center" valign="bottom">
				<input id="subButton" type="image" src="images/button_add.gif" />
				&ensp;&ensp;
				<img src="images/button_reset.gif" style="cursor:pointer;" onclick="document.location.reload();" alt="" />
			</td>
		</tr>
		</table>
		</form>
		');
	$skin->TableBottom();

	echo('
	<br />

	<form id="listForm" name="listForm" method="post" action="info_deal.php?mudi=moreDel" onsubmit="return CheckListForm()">
	<script language="javascript" type="text/javascript">document.write (\'<input type="hidden" name="backURL" value="\'+ document.location.href +\'" />\')</script>
	<input type="hidden" name="dataType" value="'. $dataType .'" />
	<input type="hidden" name="dataTypeCN" value="'. $dataTypeCN .'" />
	');

	$todayDate = TimeDate::Get('date');
	$orderName=OT::GetStr('orderName');
		if (strpos('|source|imgMode|theme|URL|cost|startDate|endDate|rank|state|','|'. $orderName .'|') === false){ $orderName='rank'; }
	$orderSort=OT::GetStr('orderSort');
		if ($orderSort != 'DESC'){ $orderSort='ASC'; }

	$skin->TableTop2('share_list.gif','',$dataTypeCN .'管理');
	$skin->TableItemTitle('4%,5%,5%,6%,15%,19%,5%,9%,9%,6%,9%,8%','<input type="checkbox" id="selAll" name="selAll" onclick="CheckBoxAll()" />,序号,'. $skin->ShowArrow('来源','source',$orderName,$orderSort) .','. $skin->ShowArrow('LOGO图','imgMode',$orderName,$orderSort) .','. $skin->ShowArrow('网站名称','theme',$orderName,$orderSort) .','. $skin->ShowArrow('链接地址','URL',$orderName,$orderSort) .'/备注,'. $skin->ShowArrow('费用','cost',$orderName,$orderSort) .','. $skin->ShowArrow('加入时间','startDate',$orderName,$orderSort) .','. $skin->ShowArrow('过期时间','endDate',$orderName,$orderSort) .','. $skin->ShowArrow('排序','rank',$orderName,$orderSort) .','. $skin->ShowArrow('电脑','state',$orderName,$orderSort) .'/'. $skin->ShowArrow('WAP状态','wapState',$orderName,$orderSort) .',修改　删除');

	$pageSize	= $MB->mMbRow['MB_itemNum'];		//每页条数
	$page		= OT::GetInt('page');
	$showRow=$DB->GetLimit("select * from ". OT_dbPref ."infoMove where IM_type='". $dataType ."' order by IM_". $orderName ." ". $orderSort,$pageSize,$page);
	if (! $showRow){
		$skin->TableNoData();
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

			$endDateStr = '';
			if (strtotime($showRow[$i]['IM_endDate'])){
				if ($showRow[$i]['IM_endDate'] < '2029-12-31'){
					$endDateStr = $showRow[$i]['IM_endDate'] .'<br />';
					if ($showRow[$i]['IM_endDate']>=$todayDate){
						$endDateDiff = TimeDate::Diff('d',TimeDate::Get(),$showRow[$i]['IM_endDate'])+1;
						if ($endDateDiff == 1){
							$endDateStr .= '<span class="font2_2">(只剩今天)</span>';
						}else{
							$endDateStr .= '<span class="font2_2">(还剩'. $endDateDiff .'天)</span>';
						}
					}else{
						$endDateStr .= '<span class="font2_2">(已过期)</span>';
					}
				}else{
					$endDateStr = '永久';
				}
			}else{
				$endDateStr = '<br />';
			}

			$imgMode = '';
			if ($showRow[$i]['IM_imgMode'] == 'upImg'){
				$imgMode = '本地';
			}elseif ($showRow[$i]['IM_imgMode'] == 'URL'){
				$imgMode = '网络';
			}

			$sourceStr = '';
			if ($showRow[$i]['IM_source'] == 1){
				$sourceStr = '申请';
			}else{
				$sourceStr = '';
			}

			echo('
			<tr id="data'. $showRow[$i]['IM_ID'] .'" '. $bgcolor .'>
				<td align="center"><input type="checkbox" name="selDataID[]" value="'. $showRow[$i]['IM_ID'] .'" /></td>
				<td align="center">'. $number .'</td>
				<td align="center">'. $sourceStr .'<br /></td>
				<td align="center">'. $imgMode .'<br /></td>
				<td align="center" style="word-break:break-all;">'. $showRow[$i]['IM_theme'] .'</td>
				<td align="left" style="word-break:break-all;padding-left:3px;">
					<a href="'. $showRow[$i]['IM_URL'] .'" class="font1_2" target="_blank">'. $showRow[$i]['IM_URL'] .'</a>
					<div style="padding-top:3px;color:blue;">'. $showRow[$i]['IM_note'] .'</div>
				</td>
				<td align="center">'. $showRow[$i]['IM_cost'] .'<br /></td>
				<td align="center">'. $showRow[$i]['IM_startDate'] .'<br /></td>
				<td align="center">'. $endDateStr .'</td>
				<td align="center">'. $showRow[$i]['IM_rank'] .'</td>
				<td align="center">
					'. Adm::SwitchBtn('infoMove',$showRow[$i]['IM_ID'],$showRow[$i]['IM_state'],'state') .'/
					'. Adm::SwitchBtn('infoMove',$showRow[$i]['IM_ID'],$showRow[$i]['IM_wapState'],'wapState','userState') .'
				</td>
				<td align="center">
					<img src="images/img_rev.gif" style="cursor:pointer;" onclick=\'DataDeal.location.href="infoMove_deal.php?mudi=infoMoveSend&dataID='. $showRow[$i]['IM_ID'] .'";document.location.href="#writeBox";\' alt="" />&ensp;&ensp;
					<img src="images/img_del.gif" style="cursor:pointer;" onclick=\'if(confirm("你确定要删除？")==true){DataDeal.location.href="infoMove_deal.php?mudi=infoMoveDel&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&theme='. urlencode($showRow[$i]['IM_theme']) .'&dataID='. $showRow[$i]['IM_ID'] .'"}\' alt="" />
				</td>
			</tr>
			');
			$number ++;
		}
		echo('
		</tbody>
		<tr class="tabColorB padd5">
			<td align="left" colspan="20">
				<input type="button" value="全选" onclick="AllSelBox()" class="form_button1" />
				<input type="button" value="反选" onclick="RevSelBox()" class="form_button1" />
				&ensp;
				<select id="moreSetTo" name="moreSetTo" onchange="MoreSetTo()">
					<option value="">批量设置成...</option>
					<option value="7">过期时间+7天</option>
					<option value="14">过期时间+14天</option>
					<option value="30">过期时间+1个月</option>
					<option value="60">过期时间+2个月</option>
					<option value="90">过期时间+3个月</option>
					<option value="180">过期时间+6个月</option>
				</select>
				<input type="hidden" id="moreSetToCN" name="moreSetToCN" value="" />
				&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;
				有效友链：<span style="color:blue;font-weight:bold;">'. $DB->GetOne('select count(IM_ID) from '. OT_dbPref .'infoMove where IM_state=1 and IM_endDate>='. $DB->ForTime($todayDate) .'') .'</span>条，
				收费友链：<span style="color:blue;font-weight:bold;">'. $DB->GetOne('select count(IM_ID) from '. OT_dbPref .'infoMove where IM_state=1 and IM_endDate>='. $DB->ForTime($todayDate) .' and IM_cost>0') .'</span>条，
				总费用：<span style="color:red;font-weight:bold;">'. $DB->GetOne('select sum(IM_cost) from '. OT_dbPref .'infoMove where IM_state=1 and IM_endDate>='. $DB->ForTime($todayDate) .'') .'</span>元&ensp;&ensp;&ensp;&ensp;
			</td>
		</tr>
		');
	}
	unset($showRow);
	
	echo('</form>');

	$skin->TableBottom2($pageCount, $pageSize, $recordCount);

}



//在线客服
function InfoMove3(){
	global $DB,$skin,$mudi,$dataType,$dataTypeCN,$pageCount,$recordCount;

	$skin->TableTop('share_add.gif','','<span id="infoTitle">添加</span>'. $dataTypeCN);
	?>
	<form id="dealForm" name="dealForm" method="post" action="infoMove_deal.php?mudi=infoMove3" onsubmit="return CheckDealForm3()">
	<script language='javascript' type='text/javascript'>document.write ("<input type='hidden' name='backURL' value='"+ document.location.href +"' />")</script>
	<input type="hidden" id="dataType" name="dataType" value="<?php echo($dataType); ?>" />
	<input type="hidden" id="dataTypeCN" name="dataTypeCN" value="<?php echo($dataTypeCN); ?>" />
	<input type="hidden" id="dataID" name="dataID" value="0" />
	<table align='center' cellpadding='0' cellspacing='0' summary="">
	<tr>
		<td width="100" align="right">类型：</td>
		<td>
			<select id="imgMode" name="imgMode">
				<option value="QQ">QQ</option>
				<option value="MSN">MSN</option>
				<option value="WW">阿里旺旺</option>
				<option value="SKYPE">SKYPE</option>
			</select>
		</td>
	</tr>
	<tr>
		<td align="right">名称：</td>
		<td><input type="text" id="theme" name="theme" size='40' /></td>
	</tr>
	<tr>
		<td  align="right">帐号/号码/ID：</td>
		<td><input type="text" id="webURL" name="webURL" value="" size='40' /></td>
	</tr>
	<tr>
		<td align="right">排序：</td>
		<td><input type="text" id="rank" name="rank" value="" size='4' />
			&ensp;<span class="font2_2">提示：排序值越小，排越前。排序值之间最好有一定间隔，好方便以后穿插。</span>
		</td>
	</tr>
	<tr>
		<td align="right">状态：</td>
		<td>
			<input type="radio" name="state" value="1" checked='checked' />显示&ensp;
			<input type="radio" name="state" value="0" />关闭
		</td>
	</tr>
	<tr>
		<td style="padding-top:25px;" colspan='2' align='center' valign='bottom'>
			<input id="subButton" type="image" src="images/button_add.gif" />
			&ensp;&ensp;
			<img src="images/button_reset.gif" style="cursor:pointer;" onclick="document.location.reload();" alt="" />
		</td>
	</tr>
	</table>
	</form>
	<?php
	$skin->TableBottom();

	echo('
	<br />');

	$skin->TableTop2('share_list.gif','',$dataTypeCN .'管理');
	$skin->TableItemTitle('7%,10%,20%,20%,8%,10%,10%,15%','序号,类型,名称,帐号/号码/ID,排序,状态,加入时间,修改　删除');

	$showrec=$DB->query('select * from '. OT_dbPref .'infoMove where IM_type="'. $dataType .'" order by IM_rank ASC');
		$rankNum=0;
		if (! $showrec->fetchColumn()){
			$skin->TableNoData();
		}else{
			$pageSize=$memberexe->fields['MB_itemNum'];		//每页条数
			$recordCount=$showrec->RecordCount();
			$pageCount=ceil($recordCount/$pageSize);
			$page=OT::GetInt('page');
			if ($page < 1 || $page > $pageCount){$page=1;}

			echo('
			<tbody class="tabBody padd3td">
			');
			$number=1+($page-1)*$pageSize;
			$showrec->Move($number-1);
			for ($i=1; $i<=$pageSize; $i++){
				if (! $showrec->fetchColumn()){break;}
				if ($i % 2 == 0){$bgcolor='class="tabColorTr"';}else{$bgcolor='';}

				echo('
				<tr id="data'. $showrec->fields['IM_ID'] .'" '. $bgcolor .'>
					<td align="center">'. $number .'</td>
					<td align="center">'. $showrec->fields['IM_imgMode'] .'<br /></td>
					<td align="center">'. $showrec->fields['IM_theme'] .'<br /></td>
					<td align="center">'. $showrec->fields['IM_URL'] .'<br /></td>
					<td align="center">'. $showrec->fields['IM_rank'] .'<br /></td>
					<td align="center">'. Adm::SwitchBtn('infoMove',$showrec->fields['IM_ID'],$showrec->fields['IM_state'],'state') .'<br /></td>
					<td align="center">'. TimeDate::Get('date',$showrec->fields['IM_time']) .'<br /></td>
					<td align="center">
						<img src="images/img_rev.gif" style="cursor:pointer;" onclick=\'DataDeal.location.href="infoMove_deal.php?mudi=infoMoveSend3&dataID='. $showrec->fields['IM_ID'] .'"\' alt="" />&ensp;&ensp;
						<img src="images/img_del.gif" style="cursor:pointer;" onclick=\'if(confirm("你确定要删除？")==true){DataDeal.location.href="infoMove_deal.php?mudi=infoMoveDel3&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&theme='. urlencode($showrec->fields['IM_theme']) .'&dataID='. $showrec->fields['IM_ID'] .'"}\' alt="" />
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

	$skin->TableBottom2($pageCount, $pageSize, $recordCount);

}



?>
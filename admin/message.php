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
<script language="javascript" type="text/javascript" src="js/message.js?v='. OT_VERSION .'"></script>
');


switch ($mudi){
	case 'refer':
		$MB->IsSecMenuRight('alertBack',16,$dataType);
		refer();
		break;

	case 'show':
		$MB->IsSecMenuRight('alertClose',58,$dataType);
		show();
		break;

	default:
		die('err');
}

$skin->WebBottom();

$MB->Close();
$DB->Close();





// 查询
function refer(){
	global $DB,$MB,$skin,$mudi,$dataType,$dataTypeCN,$pageCount,$recordCount,$dbPathPart;

	$dataMode		= OT::GetStr('dataMode');
	$dataModeStr	= OT::GetStr('dataModeStr');

	$refUserName	= OT::GetRegExpStr('refUserName','sql');
	$refContent		= OT::GetRegExpStr('refContent','sql');
	$refDate1		= OT::GetStr('refDate1');
		if (! strtotime($refDate1)){ $refDate1=''; }
	$refDate2		= OT::GetStr('refDate2');
		if (! strtotime($refDate2)){ $refDate2=''; }
	$refState		= OT::GetInt('refState',-1);
	$refReply		= OT::GetInt('refReply',-1);

	$SQLstr='select * from '. OT_dbPref .'message where 1=1';

	if ($refUserName != ''){ $SQLstr .= " and MA_username like '%". $refUserName ."%'"; }
	if ($refContent != ''){ $SQLstr .= " and MA_content like '%". $refContent ."%'"; }
	if ($refDate1 != ''){ $SQLstr .= ' and MA_time>='. $DB->ForTime($refDate1); }
	if ($refDate2 != ''){ $SQLstr .= ' and MA_time<='. $DB->ForTime(TimeDate::Add('d',1,$refDate2)); }
	if ($refState > -1){ $SQLstr .= ' and MA_state='. $refState; }
	if ($refReply > -1){ $SQLstr .= ' and MA_reply='. $refReply; }

	$skin->TableTop('share_refer.gif','',$dataTypeCN .'查询');
		echo('
		<form id="refForm" name="refForm" method="get" action="">
		<input type="hidden" name="mudi" value="'. $mudi .'" />
		<input type="hidden" name="dataMode" value="'. $dataMode .'" />
		<input type="hidden" name="dataModeStr" value="'. $dataModeStr .'" />
		<input type="hidden" name="dataType" value="'. $dataType .'" />
		<input type="hidden" name="dataTypeCN" value="'. $dataTypeCN .'" />

		<table style="width:90%;" align="center" border="0" cellSpacing="0" cellPadding="0" summary="" class="padd5td">
		<tr>
			<td>
				&ensp;&ensp;&ensp;&ensp;昵称：<input type="text" name="refUserName" size="20" value="'. $refUserName .'" />
			</td>
			<td>
				&ensp;&ensp;&ensp;&ensp;审核：
				<select id="refState" name="refState">
				<option value=""></option>
				<option value="1" '. Is::Selected($refState,1) .'>已审核</option>
				<option value="0" '. Is::Selected($refState,0) .'>未审核</option>
				</select>
			</td>
			<td>
				&ensp;&ensp;&ensp;
				回复：
				<select id="refReply" name="refReply">
				<option value=""></option>
				<option value="1" '. Is::Selected($refReply,1) .'>已回复</option>
				<option value="0" '. Is::Selected($refReply,0) .'>未回复</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				留言内容：<input type="text" name="refContent" size="20" value="'. $refContent .'" />
			</td>
			<td>
				发布日期：<input type="text" name="refDate1" size="10" value="'. $refDate1 .'" onfocus="WdatePicker()" />&ensp;
				至&ensp;<input type="text" name="refDate2" size="10" value="'. $refDate2 .'" onfocus="WdatePicker()" />
			</td>
			<td>
			</td>
		</tr>
		<tr>
			<td align="center" style="padding:5px;padding-top:20px" colspan="3">
				<input type="image" src="images/button_refer.gif" />
				&ensp;&ensp;&ensp;&ensp;
				<img src="images/button_reset.gif" onclick=\'document.location.href="?mudi='. $mudi .'&dataMode='. $dataMode .'&dataModeStr='. $dataModeStr .'&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'"\' style="cursor:pointer;" alt="" />
			</td>
		</tr>
		</table>
		</form>
		');
	$skin->TableBottom();

	echo('
	<br />

	<form id="listForm" name="listForm" method="post" action="message_deal.php?mudi=moreDel" onsubmit="return CheckListForm()">
	<script language="javascript" type="text/javascript">document.write(\'<input type="hidden" name="backURL" value="\'+ document.location.href +\'">\')</script>
	<input type="hidden" name="dataType" value="'. $dataType .'" />
	<input type="hidden" name="dataTypeCN" value="'. $dataTypeCN .'" />
	<input type="hidden" id="dataMode" name="dataMode" value="'. $dataMode .'" />
	<input type="hidden" id="dataModeStr" name="dataModeStr" value="'. $dataModeStr .'" />
	');

	$skin->TableTop2('share_list.gif','',$dataTypeCN .'列表');
	$skin->TableItemTitle('5%,5%,37%,12%,13%,10%,8%,10%','<input type="checkbox" id="selAll" name="selAll" onclick="CheckBoxAll()" />,序号,留言内容,昵称,IP,添加日期,状态,详细&ensp;删除');

	$pageSize	= $MB->mMbRow['MB_itemNum'];		//每页条数
	$page		= OT::GetInt('page');
	$showRow=$DB->GetLimit($SQLstr .' order by MA_time DESC',$pageSize,$page);
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

			if (strlen($showRow[$i]['MA_ipCN']) > 0 && strpos($showRow[$i]['MA_ipCN'],'*') === false){
				$ipCN='<br /><span style="color:#c0c0c0;">['. $showRow[$i]['MA_ipCN'] .']</span>';
			}else{
				$ipCN='';
			}
			if ($showRow[$i]['MA_userID'] > 0){
				$userTdStr='<span style="word-break:break-all;color:red;" title="会员">'. $showRow[$i]['MA_username'] . AdmArea::UserInfoImg($showRow[$i]['MA_userID']) .'</span>';
			}else{
				$userTdStr='<span style="word-break:break-all;" title="游客">'. $showRow[$i]['MA_username'] .'</span>';
			}

			echo('
			<tr '. $bgcolor .' id="data'. $showRow[$i]['MA_ID'] .'">
				<td align="center"><input type="checkbox" name="selDataID[]" value="'. $showRow[$i]['MA_ID'] .'" /></td>
				<td align="center">'. $number .'</td>
				<td align="left" style="word-break:break-all;">'. Area::FaceSignToImg(Str::LimitChar(Str::RegExp($showRow[$i]['MA_content'],'html'),80),$dbPathPart) .'</td>
				<td align="center">'. $userTdStr .'</td>
				<td align="center">'. $showRow[$i]['MA_ip'] . $ipCN .'</td>
				<td align="center">'. $showRow[$i]['MA_time'] .'</td>
				<td align="center" style="line-height:1.4;">'. Adm::SwitchBtn('message',$showRow[$i]['MA_ID'],$showRow[$i]['MA_state'],'state','stateAudit') .'<br /><span class="pointer" onclick=\'var a=window.open("?mudi=show&nohrefStr=close&dataMode='. $dataMode .'&dataModeStr='. $dataModeStr .'&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&dataID='. $showRow[$i]['MA_ID'] .'")\'>'. Adm::Switch3CN('reply',$showRow[$i]['MA_reply']) .'</span></td>
				<td align="center">
					<img src="images/img_det.gif" style="cursor:pointer" onclick=\'var a=window.open("?mudi=show&nohrefStr=close&dataMode='. $dataMode .'&dataModeStr='. $dataModeStr .'&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&dataID='. $showRow[$i]['MA_ID'] .'")\' alt="" />&ensp;&ensp;
					<img src="images/img_del.gif" style="cursor:pointer" onclick=\'if(confirm("你确定要删除？")==true){DataDeal.location.href="message_deal.php?mudi=del&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&theme='. urlencode($showRow[$i]['MA_username']) .'&dataID='. $showRow[$i]['MA_ID'] .'"}\' alt="" />
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
				<input type="submit" value="批量删除" />
			</td>
		</tr>
		');
	}
	unset($showRow);
	
	echo('</form>');

	$skin->TableBottom2($pageCount, $pageSize, $recordCount);
}



function show(){
	global $DB,$skin,$mudi,$systemArr,$dataType,$dataTypeCN,$pageCount,$recordCount,$dbPathPart;

	$dataMode	= OT::GetStr('dataMode');
	$dataModeStr= OT::GetStr('dataModeStr');
	$dataType	= OT::GetStr('dataType');
	$dataTypeCN	= OT::GetStr('dataTypeCN');
	$dataID		= OT::GetInt('dataID');
	
	$showexe=$DB->query('select * from '. OT_dbPref .'message where MA_ID='. $dataID);
	if (! $row = $showexe->fetch()){
		JS::AlertCloseEnd('指定ID错误');
	}else{
		$revUrl = OT::GetParam(array('judRevContent'));

		echo('
		<script language="javascript" type="text/javascript">
		webPathPart = "../";
		</script>
		<script language="javascript" type="text/javascript" src="'. $dbPathPart .'js/face.js?v='. OT_VERSION .'"></script>
		<script language="javascript" type="text/javascript">document.title="'. $dataTypeCN .'详细信息";</script>

		<table width="700" align="center" cellpadding="0" cellspacing="0" border="0" summary=""><tr><td>
		');
		if ($row['MA_userID'] > 0){
			$userTitle = '<span style="color:red;">会员</span>';
		}else{
			$userTitle = '昵称';
		}

		$skin->TableTop('share_list.gif','',''. $dataTypeCN .'详细信息');
		echo('
		<table style="width:100%" align="center" cellpadding="0" cellspacing="0" border="0" class="padd3">
		<tr>
			<td style="width:100px;"align="right">添加时间：</td>
			<td>'. $row['MA_time'] .'</td>
		</tr>
		<tr>
			<td align="right">'. $userTitle .'：</td>
			<td align="left">'. $row['MA_username'] .'</td>
		</tr>
		<tr>
			<td align="right">顶踩：</td>
			<td align="left">
				<img src="../inc_img/userDing.gif" align="bottom" valign="top" alt="顶" title="顶" style="margin-right:2px;" />'. $row['MA_vote1'] .'&ensp;&ensp;
				<img src="../inc_img/userCai.gif" align="bottom" valign="top" alt="踩" title="踩" style="margin-right:2px;" />'. $row['MA_vote2'] .'&ensp;&ensp;
			</td>
		</tr>
		<tr>
			<td align="right" valign="top">内容：</td>
			<td align="left" valign="top" style="word-break:break-all;">
			');
			if (OT::GetStr('judRevContent')=='true'){
				echo('
				<form name="replyForm" method="post" action="message_deal.php?mudi=revContent">
				<input type="hidden" name="dataType" value="'. $dataType .'" />
				<input type="hidden" name="dataTypeCN" value="'. $dataTypeCN .'" />
				<script language="javascript" type="text/javascript">document.write(\'<input type="hidden" name="backURL" value="\'+ document.location.href +\'" />\')</script>
				<input type="hidden" name="dataID" value="'. $row['MA_ID'] .'" />
				<div style="padding-bottom:3px;">
					顶：<input type="text" name="vote1" style="width:35px;" value="'. $row['MA_vote1'] .'" />&ensp;&ensp;&ensp;&ensp;
					踩：<input type="text" name="vote2" style="width:35px;" value="'. $row['MA_vote2'] .'" />
				</div>
				<textarea id="newContent" name="newContent" cols="30" rows="4" style="width:340px; height:80px;">'. Str::MoreReplace($row['MA_content'],'filthtml') .'</textarea>
				<input type="submit" value="修改" />
				</form>
				');
			}else{
				echo('
				<div style="padding-bottom:6px;">'. Area::FaceSignToImg($row['MA_content'],$dbPathPart) .'</div>
				'. Area::GenTie($row['MA_ID'], $row['MA_addiContent'], $DB->GetOne('tplSys','TS_messageEvent'), 3, $dbPathPart) .'
				&ensp;<a href="'. $revUrl .'&judRevContent=true" class="font1_2" style="color:red;font-weight:bold;">[修改]</a>
				');
			}
			echo('
			</td>
		</tr>
		');

		if (strpos($dataModeStr,'|openReply|') !== false){
			echo('
			<tr><td align="right" valign="top">回复：</td><td align="left">
				<span style="font-weight:bold;" class="font2_2 pointer" onclick=\'FaceShow("faceInitBox","replyContent");\'>[表情]</span>
				<div id="faceInitBox" style="width:330px;display:none;"></div>
				<form name="replyForm" method="post" action="message_deal.php?mudi=reply">
				<input type="hidden" name="theme" value="'. Str::MoreReplace($row['MA_username'],'input') .'" />
				<input type="hidden" name="dataType" value="'. $dataType .'" />
				<input type="hidden" name="dataTypeCN" value="'. $dataTypeCN .'" />
				<script language="javascript" type="text/javascript">document.write(\'<input type="hidden" name="backURL" value="\'+ document.location.href +\'" />\')</script>
				<input type="hidden" id="dataID" name="dataID" value="'. $row['MA_ID'] .'" />
				<textarea id="replyContent" name="replyContent" cols="30" rows="4" style="width:340px; height:80px;">'. $row['MA_reply'] .'</textarea>
				<input type="submit" value="保存" />
				</form>

			</td></tr>
			');
		}
		if (strpos($dataModeStr,'|openAddReply|') !== false){
			echo('
			<tr><td align="right" valign="top">回复：</td><td align="left">
				'. $row['MA_reply'] .'
				<form name="replyForm" method="post" action="message_deal.php?mudi=addReply" target="DataDeal" onsubmit=\'if ($id("addReplyContent").value==""){alert("回复内容不能为空.");return false;}\'>
				<script language="javascript" type="text/javascript">document.write(\'<input type="hidden" name="backURL" value="\'+ document.location.href +\'" />\')</script>
				<input type="hidden" name="theme" value="'. Str::MoreReplace($row['MA_username'],'input') .'" />
				<input type="hidden" name="dataType" value="'. $dataType .'" />
				<input type="hidden" name="dataTypeCN" value="'. $dataTypeCN .'" />
				<input type="hidden" id="dataID" name="dataID" value="'. $row['MA_ID'] .'" />
				<textarea id="addReplyContent" name="addReplyContent" cols="30" rows="4"></textarea>
				<input type="submit" value="确定回复" />
				</form>
			</td></tr>
			');
		}
		if (strpos($dataModeStr,'|openSetState|') !== false){
			echo('
			<tr><td align="right">状态：</td><td align="left">
				<select id="selectState" name="selectState">
				<option value="0" '. Is::Selected($row['MA_state'],0) .'>待审核</option>
				<option value="1" '. Is::Selected($row['MA_state'],1) .'>审核通过</option>
				</select>
				<input type="button" value="设置" onclick=\'document.location.href="message_deal.php?mudi=setState&nohrefStr=close&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&dataID='. $row['MA_ID'] .'&theme='. urlencode($row['MA_username']) .'&backURL="+ encodeURIComponent(document.location.href) +"&newState="+ document.getElementById("selectState").value +""\' />
			</td></tr>
			');
		}
		echo('
		</table>
		');

		$skin->TableBottom();

		echo('
		</td></tr></table>
		');
	}

}
?>
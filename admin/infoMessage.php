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
	case 'manage':
		$MB->IsSecMenuRight('alertBack',140,$dataType);
		manage();
		break;

	case 'show':
		$MB->IsSecMenuRight('alertClose',144,$dataType);
		show();
		break;

	default:
		die('err');
}

$skin->WebBottom();

$MB->Close();
$DB->Close();





// 管理
function manage(){
	global $DB,$MB,$skin,$mudi,$dataType,$dataTypeCN,$pageCount,$recordCount,$dbPathPart;

	$dataMode		= OT::GetStr('dataMode');
	$dataModeStr	= OT::GetStr('dataModeStr');

	$refInfoID		= OT::GetInt('refInfoID');
	$refTheme		= OT::GetRegExpStr('refTheme','sql');
	$refUserName	= OT::GetRegExpStr('refUserName','sql');
	$refContent		= OT::GetRegExpStr('refContent','sql');
	$refDate1		= OT::GetStr('refDate1');
		if (! strtotime($refDate1)){ $refDate1=''; }
	$refDate2		= OT::GetStr('refDate2');
		if (! strtotime($refDate2)){ $refDate2=''; }
	$refState		= OT::GetInt('refState',-1);
	$refReply		= OT::GetInt('refReply',-1);

	$SQLstr='select IM.*,IF_theme from '. OT_dbPref .'infoMessage as IM left join '. OT_dbPref .'info as IF1 on IM.IM_infoID=IF1.IF_ID where 1=1';

	if ($refInfoID > 0){
		$infoIdStr = '<span class="font2_2">锁定</span>';
		$SQLstr .= ' and IM_infoID='. $refInfoID;
	}else{
		$infoIdStr = '';
	}
	if ($refTheme != ''){ $SQLstr .= " and IF_theme like '%". $refTheme ."%'"; }
	if ($refUserName != ''){ $SQLstr .= " and IM_username like '%". $refUserName ."%'"; }
	if ($refContent != ''){ $SQLstr .= " and IM_content like '%". $refContent ."%'"; }
	if ($refDate1 != ''){ $SQLstr .= ' and IM_time>='. $DB->ForTime($refDate1); }
	if ($refDate2 != ''){ $SQLstr .= ' and IM_time<='. $DB->ForTime(TimeDate::Add('d',1,$refDate2)); }
	if ($refState > -1){ $SQLstr .= ' and IM_state='. $refState .''; }
	if ($refReply > -1){ $SQLstr .= ' and IM_isReply='. $refReply; }

	$skin->TableTop('share_refer.gif','',$dataTypeCN .'查询');
		echo('
		<form id="refForm" name="refForm" method="get" action="">
		<input type="hidden" name="mudi" value="'. $mudi .'" />
		<input type="hidden" name="dataMode" value="'. $dataMode .'" />
		<input type="hidden" name="dataModeStr" value="'. $dataModeStr .'" />
		<input type="hidden" name="dataType" value="'. $dataType .'" />
		<input type="hidden" name="dataTypeCN" value="'. $dataTypeCN .'" />

		<table align="center" style="width:95%;" border="0" cellSpacing="0" cellPadding="0" summary="" class="padd5td">
		<tr>
			<td>
				&ensp;&ensp;&ensp;&ensp;昵称：<input type="text" name="refUserName" size="20" value="'. $refUserName .'" />
			</td>
			<td>
				留言内容：<input type="text" name="refContent" size="20" value="'. $refContent .'" />
			</td>
			<td>
				审核：
				<select id="refState" name="refState">
				<option value=""></option>
				<option value="1" '. Is::Selected($refState,1) .'>已审核</option>
				<option value="0" '. Is::Selected($refState,0) .'>未审核</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				文章标题：<input type="text" name="refTheme" size="20" value="'. $refTheme .'" />'. $infoIdStr .'
			</td>
			<td>
				发布日期：<input type="text" name="refDate1" size="10" value="'. $refDate1 .'" onfocus="WdatePicker()" />&ensp;
				至&ensp;<input type="text" name="refDate2" size="10" value="'. $refDate2 .'" onfocus="WdatePicker()" />
			</td>
			<td>
				回复：
				<select id="refReply" name="refReply">
				<option value=""></option>
				<option value="1" '. Is::Selected($refReply,1) .'>已回复</option>
				<option value="0" '. Is::Selected($refReply,0) .'>未回复</option>
				</select>
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

	<form id="listForm" name="listForm" method="post" action="infoMessage_deal.php?mudi=moreDel" onsubmit="return CheckListForm()">
	<input type="hidden" id="dataType" name="dataType" value="'. $dataType .'" />
	<input type="hidden" id="dataTypeCN" name="dataTypeCN" value="'. $dataTypeCN .'" />
	<input type="hidden" id="dataMode" name="dataMode" value="'. $dataMode .'" />
	<input type="hidden" id="dataModeStr" name="dataModeStr" value="'. $dataModeStr .'" />
	<script language="javascript" type="text/javascript">document.write(\'<input type="hidden" name="backURL" value="\'+ document.location.href +\'">\')</script>
	');

	$skin->TableTop2('share_list.gif','',$dataTypeCN .'列表');
	$skin->TableItemTitle('4%,5%,25%,10%,11%,20%,9%,7%,9%','<input type="checkbox" id="selAll" name="selAll" onclick="CheckBoxAll()" />,序号,留言内容,昵称,IP,文章标题,添加日期,状态,详细　删除');

	$pageSize	= $MB->mMbRow['MB_itemNum'];		//每页条数
	$page		= OT::GetInt('page');
	$showRow=$DB->GetLimit($SQLstr .' order by IM_time DESC',$pageSize,$page);
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
			if (strlen($showRow[$i]['IM_ipCN'])>0 && strpos($showRow[$i]['IM_ipCN'],'*') === false){
				$ipCN='<br /><span style="color:#c0c0c0;">['. $showRow[$i]['IM_ipCN'] .']</span>';
			}else{
				$ipCN='';
			}
			if ($showRow[$i]['IM_userID']>0){
				$userTdStr='<span style="word-break:break-all;color:red;" title="会员">'. $showRow[$i]['IM_username'] . AdmArea::UserInfoImg($showRow[$i]['IM_userID']) .'</span>';
			}else{
				$userTdStr='<span style="word-break:break-all;" title="游客">'. $showRow[$i]['IM_username'] .'</span>';
			}

			echo('
			<tr '. $bgcolor .' id="data'. $showRow[$i]['IM_ID'] .'">
				<td align="center"><input type="checkbox" name="selDataID[]" value="'. $showRow[$i]['IM_ID'] .'" /></td>
				<td align="center">'. $number .'</td>
				<td align="left" style="word-break:break-all;">'. Area::FaceSignToImg(Str::LimitChar(Str::RegExp($showRow[$i]['IM_content'],'html'),60),$dbPathPart) .'</td>
				<td align="center">'. $userTdStr .'</td>
				<td align="center">'. $showRow[$i]['IM_ip'] . $ipCN .'</td>
				<td align="left">'. $showRow[$i]['IF_theme'] .'<a href="../news/?'. $showRow[$i]['IM_infoID'] .'.html?rnd=user'. $showRow[$i]['IM_userID'] .'" target="_blank" class="font2_2">[预览]</a><a href="?mudi='. $mudi .'&dataMode='. $dataMode .'&dataModeStr='. $dataModeStr .'&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&refInfoID='. $showRow[$i]['IM_infoID'] .'" class="font2_2">[锁定]</a></td>
				<td align="center">'. $showRow[$i]['IM_time'] .'</td>
				<td align="center" style="line-height:1.4;">'. Adm::SwitchBtn('infoMessage',$showRow[$i]['IM_ID'],$showRow[$i]['IM_state'],'state','stateAudit') .'<br /><span class="pointer" onclick=\'var a=window.open("?mudi=show&nohrefStr=close&dataMode='. $dataMode .'&dataModeStr='. $dataModeStr .'&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&dataID='. $showRow[$i]['IM_ID'] .'")\'>'. Adm::Switch3CN('reply',$showRow[$i]['IM_reply']) .'</span></td>
				<td align="center">
					<img src="images/img_det.gif" style="cursor:pointer" onclick=\'var a=window.open("?mudi=show&nohrefStr=close&dataMode='. $dataMode .'&dataModeStr='. $dataModeStr .'&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&dataID='. $showRow[$i]['IM_ID'] .'")\' alt="" />&ensp;&ensp;
					<img src="images/img_del.gif" style="cursor:pointer" onclick=\'if(confirm("你确定要删除？")==true){ DataDeal.location.href="infoMessage_deal.php?mudi=del&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&theme='. urlencode($showRow[$i]['IM_username']) .'&dataID='. $showRow[$i]['IM_ID'] .'"}\' alt="" />
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
	global $DB,$skin,$mudi,$systemArr,$dbPathPart;

	$dataMode		= OT::GetStr('dataMode');
	$dataModeStr	= OT::GetStr('dataModeStr');
	$dataType		= OT::GetStr('dataType');
	$dataTypeCN		= OT::GetStr('dataTypeCN');
	$dataID			= OT::GetInt('dataID');
	$showexe=$DB->query('select * from '. OT_dbPref .'infoMessage where IM_ID='. $dataID .'');
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
		if ($row['IM_userID'] > 0){
			$userTitle = '<span style="color:red;">会员</span>';
		}else{
			$userTitle = '昵称';
		}

		$skin->TableTop('share_list.gif','',''. $dataTypeCN .'详细信息');
		echo('
		<table style="width:100%" align="center" cellpadding="0" cellspacing="0" border="0" class="padd3">
		<tr>
			<td style="width:100px;"align="right">添加时间：</td>
			<td>'. $row['IM_time'] .'</td>
		</tr>
		<tr>
			<td align="right">'. $userTitle .'：</td>
			<td align="left">'. $row['IM_username'] .'</td>
		</tr>
		<tr>
			<td align="right">顶踩：</td>
			<td align="left">
				<img src="../inc_img/userDing.gif" align="bottom" valign="top" alt="顶" title="顶" style="margin-right:2px;" />'. $row['IM_vote1'] .'&ensp;&ensp;
				<img src="../inc_img/userCai.gif" align="bottom" valign="top" alt="踩" title="踩" style="margin-right:2px;" />'. $row['IM_vote2'] .'&ensp;&ensp;
			</td>
		</tr>
		<tr style="min-height:60px;">
			<td align="right" valign="top">内容：</td>
			<td align="left" valign="top" style="word-break:break-all;padding-bottom:12px;">
			');
			if (OT::GetStr('judRevContent')=='true'){
				echo('
				<form name="replyForm" method="post" action="infoMessage_deal.php?mudi=revContent">
				<input type="hidden" name="dataType" value="'. $dataType .'" />
				<input type="hidden" name="dataTypeCN" value="'. $dataTypeCN .'" />
				<script language="javascript" type="text/javascript">document.write(\'<input type="hidden" name="backURL" value="\'+ document.location.href +\'" />\')</script>
				<input type="hidden" name="dataID" value="'. $row['IM_ID'] .'" />
				<div style="padding-bottom:3px;">
					顶：<input type="text" name="vote1" style="width:35px;" value="'. $row['IM_vote1'] .'" />&ensp;&ensp;&ensp;&ensp;
					踩：<input type="text" name="vote2" style="width:35px;" value="'. $row['IM_vote2'] .'" />
				</div>
				<textarea id="newContent" name="newContent" cols="30" rows="4" style="width:340px; height:80px;">'. Str::MoreReplace($row['IM_content'],'filthtml') .'</textarea>
				<input type="submit" value="修改" />
				</form>
				');
			}else{
				echo('
				<div style="padding-bottom:6px;">'. Area::FaceSignToImg($row['IM_content'],$dbPathPart) .'</div>
				'. Area::GenTie($row['IM_ID'], $row['IM_addiContent'], $DB->GetOne('tplSys','TS_messageEvent'), 3, $dbPathPart) .'
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
				<form name="replyForm" method="post" action="infoMessage_deal.php?mudi=reply">
				<input type="hidden" name="theme" value="'. Str::MoreReplace($row['IM_username'],'input') .'" />
				<input type="hidden" name="dataType" value="'. $dataType .'" />
				<input type="hidden" name="dataTypeCN" value="'. $dataTypeCN .'" />
				<script language="javascript" type="text/javascript">document.write(\'<input type="hidden" name="backURL" value="\'+ document.location.href +\'" />\')</script>
				<input type="hidden" id="dataID" name="dataID" value="'. $row['IM_ID'] .'" />
				<textarea id="replyContent" name="replyContent" cols="30" rows="4" style="width:340px; height:80px;">'. $row['IM_reply'] .'</textarea>
				<input type="submit" value="保存" />
				</form>

			</td></tr>
			');
		}
		if (strpos($dataModeStr,'|openAddReply|') !== false){
			echo('
			<tr><td align="right" valign="top">回复：</td><td align="left">
				'. $row['IM_reply'] .'
				<form name="replyForm" method="post" action="infoMessage_deal.php?mudi=addReply" target="DataDeal" onsubmit=\'if ($id("addReplyContent").value==""){alert("回复内容不能为空.");return false;}\'>
				<script language="javascript" type="text/javascript">document.write(\'<input type="hidden" name="backURL" value="\'+ document.location.href +\'" />\')</script>
				<input type="hidden" name="theme" value="'. Str::MoreReplace($row['IM_username'],'input') .'" />
				<input type="hidden" name="dataType" value="'. $dataType .'" />
				<input type="hidden" name="dataTypeCN" value="'. $dataTypeCN .'" />
				<input type="hidden" id="dataID" name="dataID" value="'. $row['IM_ID'] .'" />
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
				<option value="0" '. Is::Selected($row['IM_state'],0) .'>待审核</option>
				<option value="1" '. Is::Selected($row['IM_state'],1) .'>审核通过</option>
				</select>
				<input type="button" value="设置" onclick=\'document.location.href="infoMessage_deal.php?mudi=setState&nohrefStr=close&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&dataID='. $row['IM_ID'] .'&theme='. urlencode($row['IM_username']) .'&backURL="+ encodeURIComponent(document.location.href) +"&newState="+ document.getElementById("selectState").value +""\' />
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
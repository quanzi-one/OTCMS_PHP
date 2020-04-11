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
<script language="javascript" type="text/javascript" src="js/member.js?v='. OT_VERSION .'"></script>
');


$MB->IsMenuRight('alertBack','用户管理');


switch($mudi){
	default :
		$MB->IsMenuRight('alert','用户管理');
		UserManage();
		break;
}

$skin->WebBottom();

$MB->Close();
$DB->Close();





function UserManage(){
	global $DB,$MB,$skin,$MB,$mudi,$pageCount,$recordCount;

	$refUsername	= OT::GetRegExpStr('refUsername','sql');
	$refRealname	= OT::GetRegExpStr('refRealname','sql');
	$refGroupID		= OT::GetInt('refGroupID');
	$refState		= OT::GetInt('refState',-1);

	$SQLstr='select MB.MB_ID,MB.MB_username,MB.MB_realname,MB.MB_rightStr,MB.MB_time,MB.MB_state,MG.MG_theme from '. OT_dbPref .'member as MB LEFT JOIN '. OT_dbPref .'memberGroup as MG on MB.MB_groupID=MG.MG_ID where MB.MB_ID>=2';

	if ($refUsername != ''){ $SQLstr = $SQLstr ." and MB_username like '%". $refUsername ."%'"; }
	if ($refRealname != ''){ $SQLstr = $SQLstr ." and MB_realname like '%". $refRealname ."%'"; }
	if ($refGroupID > 0){ $SQLstr = $SQLstr .' and MB_groupID='. $refGroupID; }
	if ($refState > -1){ $SQLstr = $SQLstr .' and MB_state='. $refState; }

	$skin->TableTop('share_refer.gif','','用户查询');
	echo('
	<form id="refForm" name="refForm" method="get" action="">
	<input type="hidden" name="mudi" value="'. $mudi .'" />
	<table align="center" cellpadding="0" cellspacing="0" summary="" class="padd3td"><tr>
		<td>
			&ensp;&ensp;用户名：<input type="text" id="refUsername" name="refUsername" size="10" value="'. $refUsername .'" />
		</td>
		<td>
			&ensp;&ensp;称呼：<input type="text" id="refRealname" name="refRealname" size="8" value="'. $refRealname .'" />
		</td>
		<td>
			&ensp;&ensp;用户组：<select id="refGroupID" name="refGroupID">
					<option value="">&ensp;</option>
					');
					$readexe=$DB->query('select MG_ID,MG_theme from '. OT_dbPref .'memberGroup');
						while($row = $readexe->fetch()){
							echo('<option value="'. $row['MG_ID'] .'" '. Is::Selected($refGroupID,$row['MG_ID']) .'>'. $row['MG_theme'] .'</option>');
						}
					unset($readexe);
				echo('
				</select>
		</td>
		<td>
			&ensp;&ensp;状态：<select id="refState" name="refState">
					<option value="">&ensp;</option>
					<option value="0" '. Is::Selected($refState,0) .'>正常</option>
					<option value="10" '. Is::Selected($refState,10) .'>冻结</option>
				</select>
		</td>
		<td>&ensp;&ensp;</td>
		<td><input type="image" src="images/button_refer.gif" /></td>
	</tr></table>
	</form>
	');
	$skin->TableBottom();

	echo('<br />');

	$skin->TableTop('share_userAdd.gif','formTitle','添加用户');
		echo('
		<form name="userform" method="post" action="member_deal.php?mudi=deal" onsubmit="return CheckForm();">
		<input type="hidden" id="userMode" name="userMode" value="add" />
		<input type="hidden" id="userID" name="userID" value="0" />
		<table width="90%" align="center" cellpadding="0" cellspacing="0" summary="" class="padd3">
		<tr>
			<td>
				用户名：<input type="text" id="username" name="username" size="18" onkeyup="if (this.value!=FiltVarStr(this.value)){this.value=FiltVarStr(this.value)}" />
			</td>
			<td>
				&ensp;&ensp;密码：<input type="password" id="userpwd" name="userpwd" size="18" />
				<input type="button" onclick="ResetPwd()" value="默认" />
			</td>
			<td>
				&ensp;&ensp;称呼：<input type="text" id="realname" name="realname" size="16" />
			</td>
		</tr>
		<tr>
			<td>
				&ensp;&ensp;状态：<select id="state" name="state">
							<option value="0">正常</option>
							<option value="10">冻结</option>
						</select>
			</td>
			<td colspan="2">
				用户组：<select id="groupID" name="groupID">
						<option value="">&ensp;</option>
						');
						$readexe=$DB->query("select MG_ID,MG_theme,MG_rightStr from ". OT_dbPref ."memberGroup");
						while($row = $readexe->fetch()){
							if (! ($MB->GetRightStr() != "admin" && strpos($row["MG_rightStr"],"|用户管理|") !== false)){
								echo("<option value='". $row["MG_ID"] ."'>". $row["MG_theme"] ."</option>");
							}
						}
						unset($readexe);
					echo('
					</select>
			</td>
		</tr>
		<tr>
			<td style="padding:8px" id="formSubmit" colspan="3" align="center"><input type="image" src="images/button_submit.gif" /></td>
		</tr></table>
		</form>
		<span id="userAlertStr" class="font2_2"></span>
		');
	$skin->TableBottom();

	echo('<br />');

	$skin->TableTop2('share_userList.gif','','用户列表');
	$skin->TableItemTitle('6%,6%,17%,16%,9%,16%,18%,12%','序号,ID号,用户名,称呼,状态,用户组,加入时间,<!-- 授权　 -->修改　删除');

	$pageSize	= $MB->mMbRow['MB_itemNum'];		// 每页条数
	$page		= OT::GetInt('page');
	$showRow=$DB->GetLimit($SQLstr .' order by MB.MB_ID DESC',$pageSize,$page);
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
			<tr id="data'. $showRow[$i]['MB_ID'] .'" '. $bgcolor .'>
				<td align="center">'. $number .'</td>
				<td align="center">'. $showRow[$i]['MB_ID'] .'</td>
				<td>'. $showRow[$i]['MB_username'] .'</td>
				<td>'. $showRow[$i]['MB_realname'] .'</td>
				<td align="center">'. ($showRow[$i]['MB_state']==10 ? '<span class="font2_2">冻结</span>' : '正常') .'</td>
				<td align="center">'. $showRow[$i]['MG_theme'] .'<br /></td>
				<td align="center">'. $showRow[$i]['MB_time'] .'<br /></td>
				<td align="center">
					<img src="images/img_rev.gif" style="cursor:pointer" onclick=\'DataDeal.location.href="member_deal.php?mudi=send&dataID='. $showRow[$i]['MB_ID'] .'"\' alt="" />&ensp;&ensp;
					<img src="images/img_del.gif" style="cursor:pointer" onclick=\'if(confirm("你确定要删除？")==true){DataDeal.location.href="member_deal.php?mudi=del&dataID='. $showRow[$i]['MB_ID'] .'"}\' alt="" />
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
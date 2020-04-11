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


//用户检测
$MB->Open(',MB_foreUsername,MB_itemNum','login');

$skin->WebTop();


echo('
<script language="javascript" type="text/javascript" src="js/admin.js?v='. OT_VERSION .'"></script>
');


switch ($mudi){
	case 'revPWD':
		RevPWD();
		break;

	Case 'revOthers':
		revOthers();
		break;

	default:
		die('err');
} 

$skin->WebBottom();

$MB->Close();
$DB->Close();





//修改密码
function RevPWD(){
	global $skin,$user_name;

	echo('
	<form id="revform" name="revform" method="post" action="admin_cl.php?mudi=revPWD" onsubmit="return CheckForm(this)">
	');

	$skin->TableTop2('share_refer.gif','','修改密码');
	echo('
	<tbody class="padd3td">
	<tr>
		<td  class="tabColor1" style="width:200px;">　　是否修改用户名：</td>
		<td><label><input type="checkbox" id="judUsername" name="judUsername" value="true" onclick="judUserBox()" />修改<label></td>
	</tr>
	<tr id="usernameBox" style="display:none;">
		<td  class="tabColor1" style="width:200px;">　　用户名：</td>
		<td><input type="text" id="username" name="username" maxlength="16" size="20" value="'. $user_name .'" onkeyup="if (this.value!=FiltVarStr(this.value)){this.value=FiltVarStr(this.value)}" /></td>
	</tr>
	<tr>
		<td  class="tabColor1" style="width:200px;">　　是否修改密码：</td>
		<td><label><input type="checkbox" id="judUserpwd" name="judUserpwd" value="true" onclick="judUserBox()" />修改<label></td>
	</tr>
	</tbody>
	<tbody id="userpwdBox" style="display:none;" class="padd3td">
	<tr>
		<td class="tabColor1">　　原密码：</td>
		<td><input type="password" size="25" id="userpwd0" name="userpwd0" maxlength="50" /></td>
	</tr>
	<tr>
		<td class="tabColor1">　　新密码：</td>
		<td><input type="password" size="25" id="userpwd" name="userpwd" maxlength="50" /></td>
	</tr>
	<tr>
		<td class="tabColor1">　　确认密码：</td>
		<td><input type="password" size="25" id="userpwd2" name="userpwd2" maxlength="50" /></td>
	</tr>
	</tbody>
	</table>

	<br /><br />

	<center><input type="image" src="images/button_submit.gif" /></center>
	</form>
	');
}



// 修改其他信息
function revOthers(){
	global $MB,$skin;

	$skin->TableTop('share_refer.gif','','其他信息设置');
		echo('
		<form id="dealForm" name="dealForm" method="post" action="admin_cl.php?mudi=revOthers">
		<script language="javascript" type="text/javascript">document.write(\'<input type="hidden" id="backURL" name="backURL" value="\'+ document.location.href +\'" />\')</script>
		<div class="padd3">默认指向前台用户名：<input type="text" name="foreUsername" size="16" value="'. $MB->mMbRow['MB_foreUsername'] .'" /></div>
		<div class="padd3">列表每页显示记录数：<input type="text" name="itemNum" size="5" value="'. $MB->mMbRow['MB_itemNum'] .'" /></div>
		&ensp;<input type="submit" value="保存" />
		</form>
		');
	$skin->TableBottom();

}

?>
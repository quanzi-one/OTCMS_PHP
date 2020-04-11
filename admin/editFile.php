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
<script language="javascript" type="text/javascript" src="js/share.js"></script>
');


switch($mudi){
	default:
		$MB->IsAdminRight('alertBack');
		manage();
		break;

}

$skin->WebBottom();

$MB->Close();
$DB->Close();





function manage(){
	global $DB,$skin,$mudi,$sysAdminArr;

	$dataMode		= OT::GetStr('dataMode');
	$dataModeStr	= OT::GetStr('dataModeStr');
	$dataType		= OT::GetStr('dataType');
	$dataTypeCN		= '特定文档查看';	// OT::GetStr('dataTypeCN');
	$fileName		= OT::GetStr('fileName');
	$fileContent	= '';
	$fileAlert		= '';

	if (! in_array($fileName,array('robots.txt','.htaccess','web.config'))){
		$fileName = '';
	}else{
		$filePath = OT_ROOT . $fileName;
		if (file_exists($filePath)){
			$fileContent = File::Read($filePath);
			if ( in_array($fileName,array('robots.txt')) ){
				$fileContent = Str::GB2UTF($fileContent);
			}
		}else{
			$fileAlert = '该文件不存在，请编辑，系统会自动创建。';
		}
	}

	$optionStr = '';
	if (file_exists(OT_ROOT .'web.config')){
		$optionStr = '<option value="web.config" '. Is::Selected($fileName,'web.config') .'>web.config(IIS站点配置)</option>';
	}

	$skin->TableTop('share_show.gif','',$dataTypeCN);
		echo('
		<!-- <form name="addform" method="post" action="editFile_deal.asp?mudi=deal" onsubmit="return CheckForm()">
		<input type="hidden" id="dataType" name="dataType" value="'. $dataType .'" />
		<input type="hidden" id="dataTypeCN" name="dataTypeCN" value="'. $dataTypeCN .'" />
		<input type="hidden" id="dataMode" name="dataMode" value="'. $dataMode .'" />
		<input type="hidden" id="dataModeStr" name="dataModeStr" value="'. $dataModeStr .'" />
		<script language="javascript" type="text/javascript">document.write(\'<input type="hidden" name="backURL" value="\'+ document.location.href +\'" />\')</script> -->
		<table width="99%" align="center" cellpadding="0" cellspacing="0" summary="" class="padd3">
		<tr>
			<td style="width:12%;"></td>
			<td style="width:88%;"></td>
		</tr>
		<tr>
			<td class="font1_2" align="right">文件：</td>
			<td class="font1_2">
				<select id="fileName" name="fileName" onchange=\'document.location.href="'. OT::GetParam(array('fileName')) .'&fileName="+ this.value;\'>
					<option value="">请选择</option>
					<option value="robots.txt" '. Is::Selected($fileName,'robots.txt') .'>robots.txt(搜索蜘蛛遵循协议)</option>
					<option value=".htaccess" '. Is::Selected($fileName,'.htaccess') .'>.htaccess(伪静态规则)</option>
					'. $optionStr .'
				</select>
				<span class="font2_2">&nbsp;&nbsp;&nbsp;&nbsp;'. $fileAlert .'</span>
			</td>
		</tr>
		<tr>
			<td class="font1_2" align="right" valign="top" style="padding-top:6px;">内容：</td>
			<td class="font1_2">
				<textarea id="fileContent" name="fileContent" size="50" style="width:650px;height:350px;">'. $fileContent .'</textarea>
			</td>
		</tr>
		</table>
		<table style="height:16px;" cellpadding="0" cellspacing="0" summary=""><tr><td></td></tr></table>

		<!-- <center><input type="image" src="images/button_save.gif" /></center>
		</form> -->
		');
	$skin->TableBottom();

}

?>
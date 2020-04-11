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
<script language="javascript" type="text/javascript" src="js/sysImages.js?v='. OT_VERSION .'"></script>
');


switch($mudi){

	default:
		$MB->IsSecMenuRight('alertBack',122,$dataType);
		InfoSet();
		break;

}

$skin->WebBottom();

$MB->Close();
$DB->Close();





// 修改信息
function InfoSet(){
	global $DB,$skin,$mudi,$dataType,$dataTypeCN;

	$revexe=$DB->query('select * from '. OT_dbPref .'sysImages');
		if ($row = $revexe->fetch()){
			$SI_isDir				= $row['SI_isDir'];
			$SI_isThumb				= $row['SI_isThumb'];
			$SI_thumbW				= $row['SI_thumbW'];
			$SI_thumbH				= $row['SI_thumbH'];
			$SI_isWatermark			= $row['SI_isWatermark'];
			$SI_watermarkTrans		= $row['SI_watermarkTrans'];
			$SI_watermarkPath		= $row['SI_watermarkPath'];
			$SI_watermarkPos		= $row['SI_watermarkPos'];
			$SI_watermarkPadding	= $row['SI_watermarkPadding'];
			$SI_watermarkFontContent= $row['SI_watermarkFontContent'];
			$SI_watermarkFontSize	= $row['SI_watermarkFontSize'];
			$SI_watermarkFontColor	= $row['SI_watermarkFontColor'];
		}
	unset($revexe);

	echo('
	<form id="dealForm" name="dealForm" method="post" action="sysImages_deal.php?mudi=infoSet" onsubmit="return CheckForm()">
	<input type="hidden" id="dataType" name="dataType" value="'. $dataType .'" />
	<input type="hidden" id="dataTypeCN" name="dataTypeCN" value="'. $dataTypeCN .'" />
	<script language="javascript" type="text/javascript">document.write(\'<input type="hidden" name="backURL" value="\'+ document.location.href +\'" />\')</script>

	<div class="tabMenu">
	<ul>
	   <li rel="tabBase" class="selected">基本设置</li>
	   <li rel="tabThumb">缩略图</li>
	   '. AppBase::SysImagesTitle() .'
	</ul>
	</div>

	<div class="tabMenuArea">
		<table id="tabBase" cellpadding="0" cellspacing="0" summary="" class="padd5">
		<tr><td style="width:150px;"></td><td></td></tr>
		<tr>
			<td width="90" align="right">
				分目录存放：
			</td>
			<td>
				<label><input type="radio" id="isDir0" name="isDir" value="0" '. Is::Checked($SI_isDir,0) .' />关闭</label>&ensp;&ensp;
				<label><input type="radio" id="isDir1" name="isDir" value="1" '. Is::Checked($SI_isDir,1) .' />年目录</label>&ensp;&ensp;
				<label><input type="radio" id="isDir2" name="isDir" value="2" '. Is::Checked($SI_isDir,2) .' />年月目录</label>&ensp;&ensp;
				<label><input type="radio" id="isDir3" name="isDir" value="3" '. Is::Checked($SI_isDir,3) .' />年月日目录</label>&ensp;&ensp;
				&ensp;&ensp;'. $skin->TishiBox('如今天2014-05-18，年目录：2014/；年月目录：201405/；年月日目录：20140518/') .'
			</td>
		</tr>
		<tr>
			<td></td>
			<td style="color:red;padding-top:5px;">提示：若上传图片很多，建议开启【年月目录】选项。</td>
		</tr>
		</table>


		<table id="tabThumb" style="display:none;" cellpadding="0" cellspacing="0" summary="" class="padd5">
		<tr><td style="width:150px;"></td><td></td></tr>
		<tr>
			<td width="90" align="right">
				是否启用：
			</td>
			<td>
				<label><input type="radio" id="isThumb1" name="isThumb" value="true" '. Is::Checked($SI_isThumb,'true') .' onclick="ThumbMode()" />启用</label>&ensp;
				<label><input type="radio" id="isThumb0" name="isThumb" value="false" '. Is::Checked($SI_isThumb,'false') .' onclick="ThumbMode()" />禁用</label>&ensp;
				&ensp;&ensp;'. $skin->TishiBox('如果启用，上传图片时会自动生成缩略图') .'
			</td>
		</tr>
		<tbody id="thumb">
		<tr>
			<td align="right">缩略图宽度：</td>
			<td ><input type="text" id="thumbW" name="thumbW" size="4" value="'. $SI_thumbW .'" onkeyup="this.value=FiltInt(this.value)" />&ensp;px</td>
		</tr>
		<tr>
			<td align="right">缩略图高度：</td>
			<td><input type="text" id="thumbH" name="thumbH" size="4" value="'. $SI_thumbH .'" onkeyup="this.value=FiltInt(this.value)" />&ensp;px</td>
		</tr>
		<tr>
			<td></td>
			<td align="left" style="color:red;line-height:1.6;padding-top:5px;">
				提示：缩略图宽度和缩略图高度可以2个都填(都大于0)，也可以只填1个。<br />
				&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;当只填一个时，另一个会根据比例自动取值。
			</td>
		</tr>
		</tbody>
		</table>
		'. AppBase::SysImagesWater($SI_isWatermark, $SI_watermarkPos, $SI_watermarkPadding, $SI_watermarkTrans, $SI_watermarkPath, $SI_watermarkFontContent, $SI_watermarkFontSize, $SI_watermarkFontColor) .'

		<div class="tabMenuSubmit"><input type="submit" class="btnBg" value="保 存" /></div>
	</div>

	</form>
	');

}

?>
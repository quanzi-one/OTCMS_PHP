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
<script language="javascript" type="text/javascript" src="js/serverFile.js?v='. OT_VERSION .'"></script>

<link rel="stylesheet" type="text/css" media="screen" href="tools/MagicZoom/MagicZoom.css?v='. OT_VERSION .'" />
<script language="javascript" type="text/javascript" src="tools/MagicZoom/MagicZoom.js?v='. OT_VERSION .'"></script>
');


$extPath = GetUrl::Dir(true,1) .'upFiles/infoImg/ext/';

$appSysArr = Cache::PhpFile('appSys');

switch ($mudi){
	case 'manage':
		$MB->IsAdminRight('alertBack');
		manage();
		break;

	case 'selectFile':
		SelectFile();
		break;

	default:
		die('err');
}

$skin->WebBottom();

$MB->Close();
$DB->Close();





// 上传图片/文件管理
function manage(){
	global $DB,$MB,$skin,$mudi,$dataType,$dataTypeCN,$pageCount,$recordCount;

	$refOldName	= OT::GetRegExpStr('refOldName','sql');
	$refName	= OT::GetRegExpStr('refName','sql');
	$refOss		= OT::GetStr('refOss');
		if (! in_array($refOss,array('aliyun','qiniu','upyun','jingan'))){ $refOss = ''; }
	$refType	= OT::GetRegExpStr('refType','sql');
	$refDate1	= OT::GetStr('refDate1');
		if (! strtotime($refDate1)){ $refDate1=''; }
	$refDate2	= OT::GetStr('refDate2');
		if (! strtotime($refDate2)){ $refDate2=''; }

	$SQLstr='select * from '. OT_dbPref .'upFile where (1=1)';

	if ($refOldName != ''){ $SQLstr .= " and UF_oldName like '%". $refOldName ."%'"; }
	if ($refName != ''){ $SQLstr .= " and UF_name like '%". $refName ."%'"; }
	if ($refOss != ''){ $SQLstr .= " and UF_oss=". $DB->ForStr($refOss); }
	if ($refType != ''){ $SQLstr .= " and UF_type like '%". $refType ."%'"; }
	if ($refDate1 != ''){ $SQLstr .= " and UF_time>=". $DB->ForTime($refDate1); }
	if ($refDate2 != ''){ $SQLstr .= " and UF_time<=". $DB->ForTime(TimeDate::Add("d",1,$refDate2)); }
	//if ($refDate2 != ''){ $SQLstr .= " and UF_time<=". $DB->ForTime($refDate2);}

	$orderName=OT::GetStr('orderName');
		if (in_array($orderName,array('oldName','name','type','size','useNum','oss'))==false){$orderName='time';}
	$orderSort=OT::GetStr('orderSort');
		if ($orderSort != 'ASC'){ $orderSort='DESC'; }

	$skin->TableTop('share_refer.gif','',$dataTypeCN .'图片/文件查询');
		echo('
		<form id="refForm" name="refForm" method="get" action="">
		<input type="hidden" name="mudi" value="'. $mudi .'" />
		<input type="hidden" name="dataType" value="'. $dataType .'" />
		<input type="hidden" name="dataTypeCN" value="'. $dataTypeCN .'" />
		<table style="width:80%;" align="center" border="0" cellSpacing="0" cellPadding="0" summary="" class="padd5td">
		<tr>
			<td style="width:50%;">
				&ensp;&ensp;原文件名：<input type="text" name="refOldName" size="25" value="'. $refOldName .'" />
			</td>
			<td style="width:50%;">
				&ensp;&ensp;文件名：<input type="text" name="refName" size="25" value="'. $refName .'" />
			</td>
		</tr>
		<tr>
			<td>
				&ensp;&ensp;&ensp;&ensp;云存储：<select name="refOss" ><option value="">&ensp;</option>
					<option value="qiniu" '. Is::Selected($refOss,'qiniu') .'>七牛云</option>
					<option value="aliyun" '. Is::Selected($refOss,'aliyun') .'>阿里云OSS</option>
					<option value="upyun" '. Is::Selected($refOss,'upyun') .'>又拍云</option>
				</select>
				&ensp;&ensp;&ensp;&ensp;文件夹：<select name="refType" ><option value="">&ensp;</option>
					<option value="info" '. Is::Selected($refType,'info') .'>info</option>
					<option value="download" '. Is::Selected($refType,'download') .'>download</option>
					<option value="images" '. Is::Selected($refType,'images') .'>images</option>
					<option value="product" '. Is::Selected($refType,'product') .'>product</option>
					<option value="users" '. Is::Selected($refType,'users') .'>users</option>
				</select>
			</td>
			<td>
				上传日期：<input type="text" name="refDate1" size="10" value="'. $refDate1 .'" onfocus="WdatePicker()" />&ensp;
				至&ensp;<input type="text" name="refDate2" size="10" value="'. $refDate2 .'" onfocus="WdatePicker()" />
			</td>
		</tr>
		<tr>
			<td align="left" style="padding:5px;padding-top:20px" colspan="3">
				<br /><br />
				<center>
				<input type="image" src="images/button_refer.gif" />
				&ensp;&ensp;&ensp;&ensp;
				<img src="images/button_reset.gif" onclick=\'document.location.href="?mudi='. $mudi .'&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'"\' style="cursor:pointer" alt="" />
				</center>
			</td>
		</tr>
		</table>
		</form>
		');
	$skin->TableBottom();

	echo('
	<br />

	<form id="listForm" name="listForm" method="post" action="serverFile_deal.php?mudi=upFileMoreDel" onsubmit="return CheckListForm()">
	<script language="javascript" type="text/javascript">document.write (\'<input type="hidden" name="backURL" value="\'+ document.location.href +\'" />\')</script>
	');

	$skin->TableTop2('share_list.gif','',$dataTypeCN .'图片/文件列表');
	$skin->TableItemTitle('4%,5%,12%,19%,19%,9%,9%,10%,7%,6%','<input type="checkbox" id="selAll" name="selAll" onclick="CheckBoxAll()" />,序号,缩略图,'. $skin->ShowArrow('原文件名','oldName',$orderName,$orderSort) .','. $skin->ShowArrow('文件名','name',$orderName,$orderSort) .','. $skin->ShowArrow('云存储','oss',$orderName,$orderSort) .'/'. $skin->ShowArrow('文件夹','type',$orderName,$orderSort) .','. $skin->ShowArrow('文件大小','size',$orderName,$orderSort) .','. $skin->ShowArrow('上传时间','time',$orderName,$orderSort) .','. $skin->ShowArrow('占用','useNum',$orderName,$orderSort) .'/'. $skin->ShowArrow('时间','chkTime',$orderName,$orderSort) .',删除');

	$pageSize	= $MB->mMbRow['MB_itemNum'];		//每页条数
	$page		= OT::GetInt('page');
	$showRow=$DB->GetLimit($SQLstr .' order by UF_'. $orderName .' '. $orderSort,$pageSize,$page);
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

			echo('
			<tr id="data'. $showRow[$i]['UF_ID'] .'" '. $bgcolor .'>
				<td align="center"><input type="checkbox" name="selDataID[]" value="'. $showRow[$i]['UF_ID'] .'" /></td>
				<td align="center">'. $number .'</td>
				<td align="center">'. FileThumb($showRow[$i]['UF_oss'],$showRow[$i]['UF_name'],$showRow[$i]['UF_type'],$showRow[$i]['UF_width'],$showRow[$i]['UF_height'],$showRow[$i]['UF_fileName']) .'</td>
				<td align="left"><div style="word-break:break-all;">'. $showRow[$i]['UF_oldName'] .'</div></td>
				<td align="left"><a href="'. FileHref($showRow[$i]['UF_oss'], $showRow[$i]['UF_type'], $showRow[$i]['UF_name'], $showRow[$i]['UF_fileName']) .'" target="_blank" class="font1_2">'. $showRow[$i]['UF_name'] .'</a></td>
				<td align="center">'. AreaApp::OssTypeCN($showRow[$i]['UF_oss'],$showRow[$i]['UF_type']) .'</td>
				<td align="right">'. File::SizeUnit($showRow[$i]['UF_size']) .'</td>
				<td align="center">'. $showRow[$i]['UF_time'] .'</td>
				<td align="center">
					'. $showRow[$i]['UF_useNum'] .'
					<div style="color:#ccc;" title="最后检测占用情况时间：'. $showRow[$i]['UF_chkTime'] .'&#13&#10'. str_replace('；','；&#13&#10',$showRow[$i]['UF_chkNote']) .'">'. TimeDate::Get('date2',$showRow[$i]['UF_chkTime']) .'</div>
				</td>
				<td align="center">
					<img src="images/img_del.gif" style="cursor:pointer" onclick=\'if(confirm("你确定要删除？")==true){DataDeal.location.href="serverFile_deal.php?mudi=upFileDel&dataID='. $showRow[$i]['UF_ID'] .'"}\' alt="" />
				</td>
			</tr>
			');
			$number ++;
		}

		$countNum = $DB->GetOne('select sum(UF_size) as UF_count from '. OT_dbPref .'upFile');
		echo('
		</tbody>
		<tr class="tabColorB padd5td">
			<td align="left" colspan="20">
				<input type="button" value="全选" onclick="AllSelBox()" class="form_button1" />
				<input type="button" value="反选" onclick="RevSelBox()" class="form_button1" />
				&ensp;
				<input type="submit" value="批量删除" class="form_button2" />
				&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;全部文件所占容量：'. File::SizeUnit($countNum) .'
				&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;
				<input type="button" value="检测更新占用情况" onclick="CheckUseState()" title="该功能可以检测每个图片或文件被系统占用的次数情况."/>
				<input type="button" value="删除占用数为0的文件" onclick="CheckUseClear()" title="由于大部分板块都有删除记录并连同附带的图片/文件一起删除的功能，所以会造成部分文件已删除了，但该上传记录却还在的现象."/>
				&ensp;&ensp;<span id="chkInfo" style="color:red;"></span>
			</td>
		</tr>
		');
	}
	unset($showRow);
	echo('
	</form>
	');

	$skin->TableBottom2($pageCount, $pageSize, $recordCount);
}



// 选择图片/文件
function selectFile(){
	global $DB,$MB,$skin,$mudi,$dataType,$dataTypeCN,$pageCount,$recordCount,$appSysArr;

	$fileMode		= OT::GetStr('fileMode');
	$fileFormName	= OT::GetStr('fileFormName');
	$upPath			= OT::GetStr('upPath');
	$upMode			= OT::GetStr('upMode');

	$refOldName		= OT::GetRegExpStr('refOldName','sql');
	$refName		= OT::GetRegExpStr('refName','sql');
	$refType		= OT::GetStr('upPath')=='' ? 'info' : OT::GetRegExpStr('upPath','sql');
//	$refType=OT::GetRegExpStr('refType','sql');
	$refDate1		= OT::GetStr('refDate1');
		if (! strtotime($refDate1)){ $refDate1=''; }
	$refDate2		= OT::GetStr('refDate2');
		if (! strtotime($refDate2)){ $refDate2=''; }

		$SQLstr='select * from '. OT_dbPref .'upFile where (1=1)';

	if ($refOldName != ''){ $SQLstr .= " and UF_oldName like '%". $refOldName ."%'";}
	if ($refName != ''){ $SQLstr .= " and UF_name like '%". $refName ."%'";}
	if ($refType != ''){ $SQLstr .= " and UF_type like '%". $refType ."%'";}
	if ($refDate1 != ''){ $SQLstr .= " and UF_time>=". $DB->ForTime($refDate1);}
	if ($refDate2 != ''){ $SQLstr .= " and UF_time<=". $DB->ForTime(TimeDate::Add("d",1,$refDate2));}
	//if (refDate2 != ''){ $SQLstr .= " and UF_time<=". $DB->ForTime($refDate2);}

	$orderName = OT::GetStr('orderName');
		if (! in_array($orderName,array('oldName','name','type','size','useNum'))){$orderName='time';}
	$orderSort = OT::GetStr('orderSort');
		if ($orderSort!='ASC'){$orderSort='DESC';}

	$URL = OT::GetParam(array('upMode'));

	$urlPart = GetUrl::Dir(true,1) . StrInfo::FileDir($upPath);

	echo('
	<script language="javascript" type="text/javascript">document.title="服务器文件";</script>
	<table width="630" align="center" cellpadding="0" cellspacing="0"><tr><td>

		<div class="tabMenu" style="margin-top:8px;">
		<ul>
			<li><a href="info_upImg.php'. $URL .'" class="font3_1">上传图片</a></li>
			<li><a href="info_upFile.php'. $URL .'" class="font3_1">上传文件</a></li>
			'. AppUpload::UpFileBox1($URL) .'
			<li class="selected">服务器文件</a></li>
		</ul>
		</div>
		');

		$skin->TableTop('share_refer.gif','',$dataTypeCN .'图片/文件查询');
			echo('
			<form id="refForm" name="refForm" method="get" action="">
			<input type="hidden" id="mudi" name="mudi" value="'. $mudi .'" />
			<input type="hidden" id="dataType" name="dataType" value="'. $dataType .'" />
			<input type="hidden" id="dataTypeCN" name="dataTypeCN" value="'. $dataTypeCN .'" />

			<input type="hidden" id="fileMode" name="fileMode" value="'. $fileMode .'" />
			<input type="hidden" id="fileFormName" name="fileFormName" value="'. $fileFormName .'" />
			<input type="hidden" id="upPath" name="upPath" value="'. $upPath .'" />
			<input type="hidden" id="upMode" name="upMode" value="'. $upMode .'" />

			<table style="height:100%;" align="center" border="0" cellSpacing="0" cellPadding="0" summary="" class="padd5td">
			<tr>
				<td style="width:40%;">
					&ensp;&ensp;原文件名：<input type="text" name="refOldName" size="12" value="'. $refOldName .'" />
				</td>
				<td style="width:60%;">
					&ensp;&ensp;文件名：<input type="text" name="refName" size="12" value="'. $refName .'" />
				</td>
			</tr>
			<tr>
				<td>
					&ensp;&ensp;&ensp;&ensp;文件夹：<select name="refType" onchange=\'this.value="'. $refType .'"\' disabled="disabled">
						<option value="">&ensp;</option>
						<option value="info" '. Is::Selected($refType,'info') .'>info</option>
						<option value="download" '. Is::Selected($refType,'download') .'>download</option>
						<option value="images" '. Is::Selected($refType,'images') .'>images</option>
						<option value="product" '. Is::Selected($refType,'product') .'>product</option>
						<option value="users" '. Is::Selected($refType,'users') .'>face</option>
					</select>
				</td>
				<td>
					上传日期：<input type="text" name="refDate1" size="10" value="'. $refDate1 .'" onfocus="WdatePicker()" />&ensp;
					至&ensp;<input type="text" name="refDate2" size="10" value="'. $refDate2 .'" onfocus="WdatePicker()" />
				</td>
			</tr>
			<tr>
				<td align="left" style="padding:5px;padding-top:20px" colspan="3">
					<div class="font2_2" style="line-height:1.4; padding-bottom:10px;">
						1、[复制链接]可用于如复制视频、swf链接，在编辑器中插入视频、swf。<br />
						2、由于删除记录时会连同删除附属的文件，所以最好别载入已被其他记录使用的文件。<br />
					</div>
					<center>
					<input type="image" src="images/button_refer.gif" />
					&ensp;&ensp;&ensp;&ensp;
					<img src="images/button_reset.gif" onclick=\'document.location.href="?mudi='. $mudi .'&nohrefStr=close&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&fileMode='. $fileMode .'&fileFormName='. $fileFormName .'&upPath='. $upPath .'&upMode='. $upMode .'"\' style="cursor:pointer" alt="" />
					</center>
				</td>
			</tr>
			</table>
			</form>');

		$skin->TableBottom();

	echo('
	</td></tr></table>

	<br />

	<form id="listForm" name="listForm" method="post" action="" onsubmit="return CheckListForm()">
	<input type="hidden" id="urlPart" name="urlPart" value="'. $urlPart .'" />
	<table width="630" align="center" cellpadding="0" cellspacing="0"><tr><td>
	');

	$skin->TableTop2('share_list.gif','',$dataTypeCN .'图片/文件列表');
	$skin->TableItemTitle('7%,8%,19%,32%,8%,13%,13%','<input type="checkbox" id="selAll" name="selAll" onclick="CheckBoxAll()" />,序号,缩略图,'. $skin->ShowArrow('(原)文件名','oldName',$orderName,$orderSort) .','. $skin->ShowArrow('占用数','useNum',$orderName,$orderSort) .','. $skin->ShowArrow('上传时间','time',$orderName,$orderSort) .',操作');

	$pageSize	= $MB->mMbRow['MB_itemNum'];		//每页条数
	$page		= OT::GetInt('page');
	$showRow=$DB->GetLimit($SQLstr .' order by UF_'. $orderName .' '. $orderSort,$pageSize,$page);
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
			if ($i % 2 == 0){ $bgcolor='class="tabColorTr"'; }else{ $bgcolor=''; }
			$thumbBtnStr = '';
			if (in_array($showRow[$i]['UF_oss'], AreaApp::OssNameArr())){
				$urlPart = $appSysArr['AS_'. $showRow[$i]['UF_oss'] .'Url'];
				$ossName = '【'. AreaApp::OssTypeCN($showRow[$i]['UF_oss']) .'】';
				$selFile1 = '';
				$selFile2 = $urlPart . AreaApp::OssFileName($showRow[$i]['UF_name'],$showRow[$i]['UF_fileName']);
				$selFile3 = $selFile2;
			}else{
				$urlPart = GetUrl::Dir(true,1) . StrInfo::FileDir($upPath);
				$ossName = '';
				$selFile1 = $urlPart;
				$selFile2 = Area::ImgThumbPath($showRow[$i]['UF_name']);
				$selFile3 = $showRow[$i]['UF_name'];
			}
			if ($showRow[$i]['UF_isThumb'] == 1){
				$thumbBtnStr = '<span id="thumbBtn'. $showRow[$i]['UF_ID'] .'"><a href="javascript:void(0);" class="font2_2" onclick=\'SelectFile("'. Str::MoreReplace($showRow[$i]['UF_oldName'],'js') .'","'. $selFile1 .'","'. $selFile2 .'","'. $showRow[$i]['UF_ext'] .'");return false;\'>[选择缩图]</a></span><br />';
			}

			echo('
			<tr '. $bgcolor .'>
				<td align="center"><input type="checkbox" name="selDataID[]" value="'. $showRow[$i]['UF_ID'] .'" /></td>
				<td align="center">'. $number .'</td>
				<td align="center">'. FileThumb($showRow[$i]['UF_oss'],$showRow[$i]['UF_name'],$showRow[$i]['UF_type'],$showRow[$i]['UF_width'],$showRow[$i]['UF_height'],$showRow[$i]['UF_fileName']) .'</td>
				<td align="left">
					'. $ossName .'
					<div style="width:160px;word-break:break-all;word-wrap:break-word;">'. $showRow[$i]['UF_oldName'] .'</div>
					'. $showRow[$i]['UF_name'] .'
				</td>
				<td align="center">'. $showRow[$i]['UF_useNum'] .'</td>
				<td align="center">'. $showRow[$i]['UF_time'] .'</td>
				<td align="center" class="border1_4 font1_2" style="padding:3px;line-height:2;">
					<input type="hidden" id="data'. $showRow[$i]['UF_ID'] .'_name" name="data'. $showRow[$i]['UF_ID'] .'_name" value="'. $showRow[$i]['UF_name'] .'" />
					<input type="hidden" id="data'. $showRow[$i]['UF_ID'] .'_oldName" name="data'. $showRow[$i]['UF_ID'] .'_oldName" value="'. $showRow[$i]['UF_oldName'] .'" />
					<input type="hidden" id="data'. $showRow[$i]['UF_ID'] .'_ext" name="data'. $showRow[$i]['UF_ID'] .'_ext" value="'. $showRow[$i]['UF_ext'] .'" />
					'. $thumbBtnStr .'
					<a href="javascript:void(0);" class="font2_2" onclick=\'SelectFile("'. Str::MoreReplace($showRow[$i]['UF_oldName'],'js') .'","'. $selFile1 .'","'. $selFile3 .'","'. $showRow[$i]['UF_ext'] .'");return false;\'>[选择原件]</a><br />
					<a href="javascript:void(0);" class="font2_2" onclick=\'StrToCopy("'. $selFile1 . $selFile3 .'");isSend=false;return false;\'>[复制链接]</a><br />
				</td>
			</tr>
			');
			$number ++;
		}
		echo('
		</tbody>
		');
		if ($fileMode == 'editor'){
			echo('
			<tr class="tabColorB padd5">
				<td align="left" colspan="20">
					<input type="button" value="全选" onclick="AllSelBox()" class="form_button1" />
					<input type="button" value="反选" onclick="RevSelBox()" class="form_button1" />
					&ensp;
					<input type="button" value="批量导入编辑器" onclick="SelectMoreFile();" />
				</td>
			</tr>
			');
		}
	}
	unset($showRow);

	echo('</form>');

	$skin->TableBottom2($pageCount, $pageSize, $recordCount);

	echo('
	</td></tr></table>
	');
}



// 文件链接
function FileHref($oss,$type,$name,$name2=''){
	global $appSysArr;

	if (in_array($oss, AreaApp::OssNameArr())){
		return $appSysArr['AS_'. $oss .'Url'] . AreaApp::OssFileName($name,$name2);
	}else{
		return StrInfo::FileAdminDir($type) . $name;
	}
}

// 文件缩略图
function FileThumb($oss,$str,$pathStr,$imgW,$imgH,$str2=''){
	global $extPath,$appSysArr;

	$fileExt = File::GetExt($str);

	switch ($fileExt){
		case 'gif': case 'jpg': case 'jpeg': case 'bmp': case 'png':
			if ($imgW>0 && $imgH>0){
				if ($imgW > $imgH){
					$imgWH=' width="80"';
				}else{
					$imgWH=' height="80"';
				}
			}else{
				$imgWH=' width="80" height="80"';
			}
			if (in_array($oss, AreaApp::OssNameArr())){
				$fileDir = $appSysArr['AS_'. $oss .'Url'];
				$str = AreaApp::OssFileName($str,$str2);
				$thumbImg = $fileDir . $str;
			}else{
				$fileDir = StrInfo::FileAdminDir($pathStr);
				$thumbImg = $fileDir .'thumb_'. $str;
			}
			$retStr = '<a href="'. $fileDir . $str .'" target="_blank" class="MagicZoom"><img src="'. $thumbImg .'" '. $imgWH .' border="0" onerror=\'if (this.src.indexOf("/'. $str .'") != -1){ this.src="images/noPic.gif"; }else{ this.src="'. $fileDir . $str .'"; };\' /></a>';
			break;

		case 'swf': case 'doc': case 'xls': case 'ppt': case 'pdf': case 'txt': case 'docx': case 'xlsx': case 'pptx': case 'rar': case 'zip': case 'iso': case 'js': case 'mdb': case 'psd': case 'xml': case 'mp3':
			$retStr = '<img src="'. $extPath . $fileExt .'.gif" border="0" alt="'. $fileExt .'类型" title="'. $fileExt .'类型">';
			break;

		case 'avi': case 'mpeg': case 'mpg': case 'ra': case 'rm': case 'rmvb': case 'mov': case 'qt': case 'asf': case 'wmv': case 'mp4': case 'wma': case 'wav': case 'mod': case 'cd': case 'md': case 'aac': case 'mid': case 'ogg': case 'm4a':
			$retStr = '<img src="'. $extPath .'mov.gif" border="0" alt="视频音频类型" title="视频音频类型">';
			break;

		default:
			$retStr = '<img src="'. $extPath .'file.gif" border="0" alt="'. $fileExt .'类型" title="'. $fileExt .'类型">';
			break;

	}
	return $retStr;
}

?>
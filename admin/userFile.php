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
<script language="javascript" type="text/javascript" src="js/userFile.js?v='. OT_VERSION .'"></script>

<link rel="stylesheet" type="text/css" media="screen" href="tools/MagicZoom/MagicZoom.css?v='. OT_VERSION .'" />
<script language="javascript" type="text/javascript" src="tools/MagicZoom/MagicZoom.js?v='. OT_VERSION .'"></script>
');


$extPath = GetUrl::Dir(true,1) .'upFiles/infoImg/ext/';

$appSysArr = Cache::PhpFile('appSys');

switch ($mudi){
	case 'manage':
		$MB->IsSecMenuRight('alertBack',201,$dataType);
		manage();
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

	$refName	= OT::GetRegExpStr('refName','sql');
	$refType	= OT::GetRegExpStr('refType','sql');
	$refUsername= OT::GetRegExpStr('refUsername','sql');
	$refDate1	= OT::GetStr('refDate1');
		if (! strtotime($refDate1)){ $refDate1=''; }
	$refDate2	= OT::GetStr('refDate2');
		if (! strtotime($refDate2)){ $refDate2=''; }

	$SQLstr='select UF.*,UE.UE_username,UE.UE_realname from '. OT_dbPref .'userFile as UF left join '. OT_dbPref .'users as UE on UF.UF_userID=UE.UE_ID where (1=1)';

	if ($refName != ''){ $SQLstr .= " and UF_name like '%". $refName ."%'"; }
	if ($refType != ''){ $SQLstr .= " and UF_type like '%". $refType ."%'"; }
	if ($refUsername != ''){ $SQLstr .= " and UE_username like '%". $refUsername ."%'"; }
	if ($refDate1 != ''){ $SQLstr .= ' and UF_time>='. $DB->ForTime($refDate1); }
	if ($refDate2 != ''){ $SQLstr .= ' and UF_time<='. $DB->ForTime(TimeDate::Add('d',1,$refDate2)); }

	$orderName=OT::GetStr('orderName');
		if (strpos('|name|oss|type|size|username|','|'. $orderName .'|') === false){ $orderName='time'; }
	$orderSort=OT::GetStr('orderSort');
		if ($orderSort != 'ASC'){ $orderSort='DESC'; }

	$skin->TableTop('share_refer.gif','',$dataTypeCN .'图片/文件查询');
		echo('
		<form id="refForm" name="refForm" method="get" action="">
		<input type="hidden" name="mudi" value="'. $mudi .'" />
		<input type="hidden" name="dataType" value="'. $dataType .'" />
		<input type="hidden" name="dataTypeCN" value="'. $dataTypeCN .'" />
		<table style="width:98%;" align="center" border="0" cellSpacing="0" cellPadding="0" summary="" class="padd5td">
		<tr>
			<td style="width:23%;">
				&ensp;&ensp;文件名：<input type="text" name="refName" size="18" value="'. $refName .'" />
			</td>
			<td style="width:23%;">
				&ensp;&ensp;&ensp;&ensp;文件夹：<select name="refType" >
					<option value="">&ensp;</option>
					<option value="info" '. Is::Selected($refType,'info') .'>info</option>
					<option value="download" '. Is::Selected($refType,'download') .'>download</option>
					<option value="images" '. Is::Selected($refType,'images') .'>images</option>
					<option value="product" '. Is::Selected($refType,'product') .'>product</option>
					<option value="users" '. Is::Selected($refType,'users') .'>users</option>
				</select>
			</td>
			<td style="width:24%;">
				&ensp;&ensp;上传者：<input type="text" name="refUsername" size="18" value="'. $refUsername .'" />
			</td>
			<td style="width:30%;">
				上传日期：<input type="text" name="refDate1" size="10" value="'. $refDate1 .'" onfocus="WdatePicker()" />&ensp;
				至&ensp;<input type="text" name="refDate2" size="10" value="'. $refDate2 .'" onfocus="WdatePicker()" />
			</td>
		</tr>
		<tr>
			<td align="left" style="padding-top:20px" colspan="4">
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

	<form id="listForm" name="listForm" method="post" action="userFile_deal.php?mudi=moreDel" onsubmit="return CheckListForm()">
	<script language="javascript" type="text/javascript">document.write (\'<input type="hidden" name="backURL" value="\'+ document.location.href +\'" />\')</script>
	');

	$skin->TableTop2('share_list.gif','',$dataTypeCN .'图片/文件列表');
	$skin->TableItemTitle('4%,5%,12%,16%,11%,8%,6%,9%,9%,14%,6%','<input type="checkbox" id="selAll" name="selAll" onclick="CheckBoxAll()" />,序号,缩略图,'. $skin->ShowArrow('文件名','name',$orderName,$orderSort) .','. $skin->ShowArrow('上传者','username',$orderName,$orderSort) .','. $skin->ShowArrow('归属','proType',$orderName,$orderSort) .','. $skin->ShowArrow('归属ID','proID',$orderName,$orderSort) .','. $skin->ShowArrow('存放点','oss',$orderName,$orderSort) .'/'. $skin->ShowArrow('文件夹','type',$orderName,$orderSort) .','. $skin->ShowArrow('文件大小','size',$orderName,$orderSort) .','. $skin->ShowArrow('上传时间','time',$orderName,$orderSort) .',删除');

	$pageSize	= $MB->mMbRow['MB_itemNum'];		//每页条数
	$page		= OT::GetInt('page');
	$showRow=$DB->GetLimit($SQLstr .' order by '. ($orderName=='username'?'UE_':'UF_') . $orderName .' '. $orderSort,$pageSize,$page);
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
			if (strpos($showRow[$i]['UF_type'],'_oss') !== false){
				$ossDirFile = $showRow[$i]['UF_name'];
			}else{
				$ossDirFile = '';
			}
			echo('
			<tr id="data'. $showRow[$i]['UF_ID'] .'" '. $bgcolor .'>
				<td align="center"><input type="checkbox" name="selDataID[]" value="'. $showRow[$i]['UF_ID'] .'" /></td>
				<td align="center">'. $number .'</td>
				<td align="center">'. FileThumb($showRow[$i]['UF_oss'],$showRow[$i]['UF_ID'],$showRow[$i]['UF_name'],$showRow[$i]['UF_type'],$showRow[$i]['UF_width'],$showRow[$i]['UF_height'],$ossDirFile) .'</td>
				<td align="left"><a href="'. FileHref($showRow[$i]['UF_oss'], $showRow[$i]['UF_type'], $showRow[$i]['UF_name'], $ossDirFile) .'" target="_blank" class="font1_2">'. $showRow[$i]['UF_name'] .'</a></td>
				<td align="center">'. $showRow[$i]['UE_username'] . AdmArea::UserInfoImg($showRow[$i]['UF_userID']) .'<div style="color:#999;">'. $showRow[$i]['UE_realname'] .'</div></td>
				<td align="center">'. ProTypeCN($showRow[$i]['UF_proType']) .'</td>
				<td align="center">'. $showRow[$i]['UF_proID'] .'</td>
				<td align="center">'. AreaApp::OssTypeCN($showRow[$i]['UF_oss'],$showRow[$i]['UF_type']) .'</td>
				<td align="right">'. File::SizeUnit($showRow[$i]['UF_size']) .'</td>
				<td align="center">'. $showRow[$i]['UF_time'] .'</td>
				<td align="center">
					<img src="images/img_del.gif" style="cursor:pointer" onclick=\'if(confirm("你确定要删除？")==true){DataDeal.location.href="userFile_deal.php?mudi=del&dataID='. $showRow[$i]['UF_ID'] .'"}\' alt="" />
				</td>
			</tr>
			');
			$number ++;
		}
		echo('
		</tbody>
		');
			
		$totalSize = $DB->GetOne('select sum(UF_size) as UF_count from '. OT_dbPref .'userFile');
		echo('
		<tr class="tabColorB">
			<td align="left" class="padd5" colspan="20">
				<input type="button" value="全选" onclick="AllSelBox()" class="form_button1" />
				<input type="button" value="反选" onclick="RevSelBox()" class="form_button1" />
				&ensp;
				<input type="submit" value="批量删除" class="form_button2" />
				&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;全部文件所占容量：'. File::SizeUnit($totalSize) .'
				&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;
				<!-- <input type="button" value="自动清理已不存在的文件" onclick="CheckClearButton()" title="由于大部分板块都有删除记录并连同附带的图片/文件一起删除的功能，所以会造成部分文件已删除了，但该上传记录却还在的现象."/> -->
			</td>
		</tr>
		');
	}
	unset($showRow);

	echo('</form>');

	$skin->TableBottom2($pageCount, $pageSize, $recordCount);
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
function FileThumb($oss,$id,$str,$pathStr,$imgW,$imgH,$str2=''){
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
			$retStr = '<a href="'. $fileDir . $str .'" target="_blank" class="MagicZoom"><img src="'. $thumbImg .'" '. $imgWH .' border="0" onerror=\'if (this.src.indexOf("/'. $str .'") != -1){ this.src="images/noPic.gif"; }else{ this.src="'. $fileDir . $str .'"; };try {$id("thumbBtn'. $id .'").innerHTML="<span class=font1_2d title=找不到缩略图>[选择缩图]</span>";}catch(e){}\' /></a>';
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



// 归属中文
function ProTypeCN($str){
	switch ($str){
		case 'info':
			return '文章';

		case 'dashang':
			return '打赏';

		case 'form':
			return '表单';

		case 'workOrder':
			return '工单';

		default:
			return '['. $str .']';
	}

}
?>
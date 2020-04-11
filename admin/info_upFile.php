<?php
header('Content-Type: text/html; charset=UTF-8');
require(dirname(__FILE__) .'/check.php');


/* **** 版权所有 ****

 网钛CMS(OTCMS)
 程序开发：网钛科技
 官方网站：http://otcms.com
 联系ＱＱ：877873666
 联系邮箱：877873666@qq.com

 **** 版权所有 **** */


if (trim(@$_GET['mudi'])=='upFileDeal'){


	// 打开用户表，并检测用户是否登录
	$MB->Open('','login',2);
	$MB->Close();


	$upPathMode		= OT::PostStr('upPath');
	$saveTo			= OT::PostStr('saveTo');

	switch ($upPathMode){ 
		case 'product':
			$upPath = ProductFileAdminDir;
			break;

		case 'images':
			$upPath = ImagesFileAdminDir;
			break;

		case 'download':
			$upPath = DownloadFileAdminDir;
			break;

		case 'users':
			$upPath = UsersFileAdminDir;
			break;

		case 'adminFile':
			$upPath = FileAdminDir;

		default:
			$upPathMode = 'info';
			$upPath = InfoImgAdminDir;
			break;

	}

	// 更新缓存文件--获取上传图片配置
	if ($sysImagesFile = @include(OT_ROOT .'cache/php/sysImages.php')){
		$sysImagesArr = unserialize($sysImagesFile);
	}else{
		$Cache = new Cache();
		$Cache->Php('sysImages');
		die('
		<br /><br />
		<center>
			加载缓存文件失败，请重新刷新该页面。<a href="#" onclick="document.location.reload();">点击刷新</a>
		</center>
		');
	}

	$isDir	= $sysImagesArr['SI_isDir'];

	$saveDirPath = '';
	if ($isDir > 0){
		// 判断并创建本地目录
		switch ($isDir){
			case 1:	$saveDirPath = TimeDate::Get('Y') .'/';		break;
			case 2:	$saveDirPath = TimeDate::Get('Ym') .'/';	break;
			case 3:	$saveDirPath = TimeDate::Get('Ymd') .'/';	break;
		}
		if (! File::CreateDir(OT_ROOT . str_replace('../','',$upPath) . $saveDirPath)){
			$saveDirPath = '';
		}
	}


	$upFileArr=File::AddUploadPara($_FILES['upFile']);
	// die($upFileArr['ext']);
	if (! File::IsUploadType($upFileArr['ext'],'common')){
		die('
		<script language="javascript">
		alert("上传失败（格式：'. $upFileArr['ext'] .'）！请选择允许上传的文件格式");window.close();
		</script>
		');
	}

	$fileRelPath = $saveDirPath . $upFileArr['newName'];
	$fileAbsPath = OT_ROOT . str_replace('../','',$upPath) . $fileRelPath;
	if (! File::Upload($upFileArr['tmp_name'], $fileAbsPath)){
		die('
		<script language="javascript" type="text/javascript">
		alert("上传失败，请重新上传.（请检查目标目录是否可写，或服务器最大上传限制。'. (get_cfg_var('upload_max_filesize') ? get_cfg_var('upload_max_filesize') : '不允许上传附件') .'）");window.close();
		</script>
		');
	}

	$isThumb		= OT::PostStr('isThumb');
	$fileMode		= OT::Post('fileMode');
	$fileFormName	= OT::Post('fileFormName');
	$fileDir		= OT::Post('fileDir');

	$imgUrl = GetUrl::Dir(true,1) . str_replace('../','',$upPath) . $fileRelPath;

	$record=array();
	$record['UF_time']		= TimeDate::Get();
	$record['UF_oss']		= $saveTo;
	$record['UF_type']		= $upPathMode;
	$record['UF_oldName']	= $upFileArr['name'];
	$record['UF_name']		= $fileRelPath;
	$record['UF_fileName']	= $upFileArr['newName'];
	$record['UF_ext']		= $upFileArr['ext'];
	$record['UF_size']		= filesize($fileAbsPath);

	if (strpos('|gif|jpg|jpeg|png|bmp|','|'. $upFileArr['ext'] .'|') !== false){
		$imgPath = $upPath . $fileRelPath;
		/* if (Is::ImgMuma($imgPath)){
			File::Del($imgPath);
			JS::AlertCloseEnd('该图片含有可疑代码，疑似木马图片，禁止上传。\n\n请用图片处理软件重新保存下或更换张图片.');
		} */
	}

	$DB->InsertParam('upFile',$record);

	if ($saveTo != 'web'){
		$ossArr = AreaApp::OssDeal($saveTo, $fileRelPath, $upPath . $fileRelPath);
		if ($ossArr['res']){
			$imgUrl = $ossArr['path'];
			$fileRelPath = $ossArr['path'];
		}else{
			die('
			<script language="javascript" type="text/javascript">
			alert("上传失败，请检查【插件参数设置】-[云存储] - '. $ossArr['name'] .' 设置是否正确\n失败可能原因：'. Str::MoreReplace($ossArr['note'],'js') .'");window.close();
			</script>
			');
		}
	}


	echo('
	<script language="javascript" type="text/javascript">
	');
	if ($fileMode=='editor'){
		echo('
		opener.$id("upImgStr").value +="'. $fileRelPath .'|";
		opener.InsertStrToEditor(\''. $fileFormName .'\',\''. ExtHtmlStr($upFileArr['name'], $imgUrl, $upFileArr['ext']) .'\');
		');
	}elseif ($fileMode=='input'){
		echo('
		opener.document.getElementById("'. $fileFormName .'").value="'. $fileRelPath .'";
		');
	}else{
		echo('
		opener.'. $fileFormName .'.value="'. $imgUrl .'";
		');
	}
	echo('
	window.close();
	</script>
	');

	$DB->Close();
	die();
}


function ExtHtmlStr($oldFileName, $newFilePath, $fileExt){
	$extPath = GetUrl::Dir(true,1) .'upFiles/infoImg/ext/';
	switch ($fileExt){
		case 'bmp': case 'jpg': case 'jpeg': case 'gif': case 'png':
			$retStr = '<img src="'. $newFilePath .'" border="0">';
			break;

		case 'swf': case 'doc': case 'xls': case 'ppt': case 'pdf': case 'txt': case 'docx': case 'xlsx': case 'pptx': case 'rar': case 'zip': case 'iso': case 'js': case 'mdb': case 'psd': case 'xml': case 'mp3':
			$retStr = '<a href="'. $newFilePath .'" target="_blank"><img src="'. $extPath . $fileExt .'.gif" border="0" alt="'. $fileExt .'类型" title="'. $fileExt .'类型">'. $oldFileName .'</a>';
			break;

		case 'avi': case 'mpeg': case 'mpg': case 'ra': case 'rm': case 'rmvb': case 'mov': case 'qt': case 'asf': case 'wmv': case 'mp4': case 'wma': case 'wav': case 'mod': case 'cd': case 'md': case 'aac': case 'mid': case 'ogg': case 'm4a':
			$retStr = '<a href="'. $newFilePath .'" target="_blank"><img src="'. $extPath .'mov.gif" border="0" alt="视频音频类型" title="视频音频类型">'. $oldFileName .'</a>';
			break;

		default:
			$retStr = '<a href="'. $newFilePath .'" target="_blank"><img src="'. $extPath .'file.gif" border="0" alt="'. $fileExt .'类型" title="'. $fileExt .'类型">'. $oldFileName .'</a>';
			break;

	}
	
	return $retStr;
}

?>


<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><!-- big5  gb2312 gbk -->
	<title>上传文件</title>
	<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
	<link rel="stylesheet" type="text/css" href="style.css" />
</head>

<body style="margin:8px;">

<script language='javascript' type='text/javascript'>
// 检测表单
function CheckForm(){
	if (document.getElementById("upFile").value == ""){
		alert("请选择文件");return false;
	}

	initAd();
	return true;
}

</script>

<style type="text/css">
#sponsorAdDiv {position:absolute; height:1; width:1; top:100px; left:100px;}
</style>
<script language='javascript' type='text/javascript'>
adTime=8;
chanceAd=1;
var ns=(document.layers);
var ie=(document.all);
var w3=(document.getElementById && !ie);
adCount=0;

function initAd(){
	if(!ns && !ie && !w3) return;
	if(ie)	adDiv=eval('document.all.sponsorAdDiv.style');
	else if(ns)	adDiv=eval('document.layers["sponsorAdDiv"]');
	else if(w3)	adDiv=eval('document.getElementById("sponsorAdDiv").style');
	randAd=Math.ceil(Math.random()*chanceAd);
	if (ie||w3)
		adDiv.visibility="visible";
	else
		adDiv.visibility ="show";
	if(randAd==1) showAd();
}

function showAd(){
	if(adCount<adTime*10){
		adCount+=1;
		if (ie){
			documentWidth  =document.body.offsetWidth/2+document.body.scrollLeft-20;
			documentHeight =document.body.offsetHeight/2+document.body.scrollTop-20;
		}else if (ns){
			documentWidth=window.innerWidth/2+window.pageXOffset-20;
			documentHeight=window.innerHeight/2+window.pageYOffset-20;
		}else if (w3){
			documentWidth=self.innerWidth/2+window.pageXOffset-20;
			documentHeight=self.innerHeight/2+window.pageYOffset-20;
		}
		adDiv.left=documentWidth-150;adDiv.top =documentHeight-25;
		setTimeout("showAd()",100);
	}
}
function closeAd(){
	if (ie||w3)
		adDiv.display="none";
	else
		adDiv.visibility ="hide";
}
</script>

<?php
$URL = OT::GetParam(array('upMode'));
?>
<div id="sponsorAdDiv" style="visibility:hidden">
<table style="width:250px; height:70px;" border='0' cellspacing='1' class="border1_1" bgcolor="#ACD6FF"><tr><td align='center'>
	<table style="width:100%; height:100%;"  border='0' cellspacing='0' summary=''>
	<tr><td align="center" class="font2_2">
		正在上传文件，请稍候......
	</td></tr>
	</table>
</td></tr></table>
</div>


<form id='dealForm' name='dealForm' method='post' action="?mudi=upFileDeal" enctype="multipart/form-data" onsubmit="return CheckForm()" style="width:630px;margin:0 auto;">
<input type="hidden" name="upPath" value="<?php echo(urlencode(@$_GET['upPath'])); ?>" />
<input type="hidden" name="fileMode" value="<?php echo(urlencode(@$_GET['fileMode'])); ?>" />
<input type="hidden" name="fileFormName" value="<?php echo(urlencode(@$_GET['fileFormName'])); ?>" />

<input type="hidden" name="isThumb" value="<?php echo(urlencode(@$_GET['isThumb'])); ?>" />

<div class='tabMenu'>
<ul>
	<li><a href='info_upImg.php<?php echo($URL); ?>' class='font3_1'>上传图片</a></li>
	<li class='selected'>上传文件</li>
	<?php echo(AppUpload::UpFileBox1($URL)); ?>
	<li><a href='serverFile.php<?php echo($URL); ?>' class='font3_1'>服务器文件</a></li>
</ul>
</div>


<table align='center' cellpadding="0" cellspacing="0" border="0" summary=''>
<tr>
	<td>
		<fieldset><legend class='font2_2' style="font-size:14px;"><b>上传文件</b></legend>
		<table style="width:580px; height:100px;" border="0" align="center" summary=''>
		<tr style="height:30px;"> 
			<td class="font1_2">本地文件：</td>
			<td colspan="3" align="left">
				<input type="file" size="28" style="width:380px;" id="upFile" name="upFile" />
			</td>
		</tr>
		<tr style="height:30px;"> 
			<td class="font1_2">&ensp;&ensp;保存至：</td>
			<td colspan="3" align="left" class="font1_2">
				<label><input type="radio" id="saveToWeb" name="saveTo" value="web" checked="checked" />网站&ensp;</label>
				<?php
				echo(
				AppOssAliyun::UpImgItem($sysAdminArr['SA_upFileOss']) . 
				AppOssQiniu::UpImgItem($sysAdminArr['SA_upFileOss']) . 
				AppOssUpyun::UpImgItem($sysAdminArr['SA_upFileOss']) . 
				AppOssFtp::UpImgItem($sysAdminArr['SA_upFileOss'])
				);
				?>
			</td>
		</tr>
		<tr style="height:40px;">
			<td colspan="4" align="center">
				<div style="float:right;padding:8px 2px 3px 0;">
					<span title="php.ini 里可调整 post_max_size 参数">表单限制：<?php echo((get_cfg_var('post_max_size') ? ''. get_cfg_var('post_max_size') : '')); ?></span>，<span title="php.ini 里可调整 upload_max_filesize 参数">上传限制：<?php echo((get_cfg_var('upload_max_filesize') ? ''. get_cfg_var('upload_max_filesize') : '')); ?></span>
				</div>
				<input type="image" src="images/button_upload.gif" />
			</td>
		</tr>
		</table>
		</fieldset>
	</td>
</tr>
</table>
</form>

</body>
</html>
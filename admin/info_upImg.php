<?php
header('Content-Type: text/html; charset=UTF-8');
require(dirname(__FILE__) .'/check.php');

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


	$isDir					= $sysImagesArr['SI_isDir'];
	$isThumb				= $sysImagesArr['SI_isThumb'];
	$thumbW					= $sysImagesArr['SI_thumbW'];
	$thumbH					= $sysImagesArr['SI_thumbH'];
	$isWatermark			= $sysImagesArr['SI_isWatermark'];
	$watermarkPos			= $sysImagesArr['SI_watermarkPos'];
	$watermarkPadding		= $sysImagesArr['SI_watermarkPadding'];
	$watermarkFontContent	= $sysImagesArr['SI_watermarkFontContent'];
	$watermarkFontSize		= $sysImagesArr['SI_watermarkFontSize'];
	$watermarkFontColor		= $sysImagesArr['SI_watermarkFontColor'];
	$watermarkPath			= $sysImagesArr['SI_watermarkPath'];
	$watermarkTrans			= $sysImagesArr['SI_watermarkTrans'];

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
	if (! File::IsUploadType($upFileArr['ext'],'imageSwf')){
		die('
		<script language="javascript">
		alert("上传失败（格式：'. $upFileArr['ext'] .'）！请选择图片格式的文件");window.close();
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

	$fileMode		= OT::Post('fileMode');
	$fileFormName	= OT::Post('fileFormName');
	$fileDir		= OT::Post('fileDir');

	$isImgWH		= OT::PostInt('isImgWH');
	$imgW			= OT::PostInt('imgW');
	$imgH			= OT::PostInt('imgH');
	$judImgWater	= OT::PostInt('judImgWater');
	$isImgWater		= OT::PostInt('isImgWater');
	$imgWaterW		= OT::Post('imgWaterW');
	$imgWaterH		= OT::Post('imgWaterH');
	$imgWaterName	= OT::Post('imgWaterName');

	// 缩略图参数
	$isThumb2	= OT::PostStr('isThumb');
	$thumbW2	= OT::PostInt('thumbW');
	$thumbH2	= OT::PostInt('thumbH');
	// 文字水印参数
	$isWatermark2			= OT::PostStr('isWatermark');
	$watermarkPos2			= OT::PostStr('watermarkPos');
	$watermarkPadding2		= OT::PostInt('watermarkPadding');
	$watermarkFontContent2	= OT::PostStr('watermarkFontContent');
	$watermarkFontSize2		= OT::PostInt('watermarkFontSize');
	$watermarkFontColor2	= OT::PostStr('watermarkFontColor');
	// 图片水印
	$watermarkPath2	= OT::PostStr('watermarkPath');
	$areaMode		= OT::PostStr('areaMode');
		if ($isThumb2=='false'){$isThumb=$isThumb2;}
		if ($thumbW2>0){$thumbW=$thumbW2;}
		if ($thumbH2>0){$thumbH=$thumbH2;}
		if (in_array($isWatermark2,array('font','img','false'))){$isWatermark=$isWatermark2;}
		if ($watermarkPos2!=''){$watermarkPos=$watermarkPos2;}
		if ($watermarkPadding2>0){$watermarkPadding=$watermarkPadding2;}
		if ($watermarkFontContent2!=''){$watermarkFontContent=$watermarkFontContent2;}
		if ($watermarkFontSize2>0){$watermarkFontSize=$watermarkFontSize2;}
		if ($watermarkFontColor2!=''){$watermarkFontColor=$watermarkFontColor2;}
		if ($watermarkPath2!=''){$watermarkPath=$watermarkPath2;}

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
	if ($imgInfo=getimagesize($fileAbsPath)){
		$record['UF_width']		= $imgInfo[0];
		$record['UF_height']	= $imgInfo[1];
	}
	if (strpos('|gif|jpg|jpeg|png|bmp|','|'. $upFileArr['ext'] .'|') !== false){
		$imgPath = $upPath . $fileRelPath;
		/* if (Is::ImgMuma($imgPath)){
			File::Del($imgPath);
			JS::AlertCloseEnd('该图片含有可疑代码，疑似木马图片，禁止上传。\n\n请用图片处理软件重新保存下或更换张图片.');
		} */
	}


	$otherImgArr = array();

	// 重新制定上传图片宽高
	if ($isImgWH == 1){
		$imgSourceArr=array();
		$imgSourceArr['imgPath'] = $fileAbsPath;
		$imgSourceArr['thumbWidth'] = $imgW;
		$imgSourceArr['thumbHeight'] = $imgH;
		$imgSourceArr['thumbPrefix'] = '';
		$sourceTtp=Images::Thumb($imgSourceArr);
		$otherImgArr[] = $saveDirPath . $sourceTtp['name'];
	}

	if ($saveTo == 'web'){
		// 生成多个缩略图
		if ($isImgWater==1){
			$imgWaterWarr = explode(',',$imgWaterW);
			$imgWaterHarr = explode(',',$imgWaterH);
			$imgWaterNameArr = explode(',',$imgWaterName);
			for ($i=0; $i<count($imgWaterNameArr); $i++){
				if ($imgWaterNameArr[$i]!=''){
					$imgWatArr=array();
					$imgWatArr['imgPath'] = $fileAbsPath;
					$imgWatArr['thumbWidth'] = floatval($imgWaterWarr[$i]);
					$imgWatArr['thumbHeight'] = floatval($imgWaterHarr[$i]);
					$imgWatArr['thumbPrefix'] = $imgWaterNameArr[$i];
					$watTtp=Images::Thumb($imgWatArr);
					$otherImgArr[] = $saveDirPath . $watTtp['name'];
				}
			}
		}

		// 生成默认缩略图
		if ($isThumb != 'false'){
			$imgThumbArr=array();
			$imgThumbArr['imgPath'] = $fileAbsPath;
			$imgThumbArr['thumbWidth'] = $thumbW;
			$imgThumbArr['thumbHeight'] = $thumbH;
			$ttp=Images::Thumb($imgThumbArr);

			$record['UF_isThumb']	= 1;
			$otherImgArr[] = $saveDirPath . $ttp['name'];
		}
	
	}

	$record['UF_otherImgStr']	= implode(',',$otherImgArr);
	$DB->InsertParam('upFile',$record);


	if ($judImgWater == 0){
		//文字水印
		if ($isWatermark=='font'){
			$imgWaterArr=array();
			$imgWaterArr['upLoadImg']	= $fileAbsPath;
			$imgWaterArr['waterPos']	= $watermarkPos;
			$imgWaterArr['waterPadding']= $watermarkPadding;
			$imgWaterArr['waterText']	= $watermarkFontContent;
			$imgWaterArr['textFont']	= $watermarkFontSize;
			$imgWaterArr['textColor']	= $watermarkFontColor;
			Images::Watermark($imgWaterArr);

		//图片水印
		}elseif ($isWatermark=='img'){
			$imgWaterArr=array();
			$imgWaterArr['upLoadImg']	= $fileAbsPath;
			$imgWaterArr['waterPos']	= $watermarkPos;
			$imgWaterArr['waterPadding']= $watermarkPadding;
			$imgWaterArr['waterTrans']	= $watermarkTrans;
			$imgWaterArr['waterImg']	= OT_ROOT . ImagesFileDir . $watermarkPath;
			Images::Watermark($imgWaterArr);
		}
	}

	if ($saveTo != 'web'){
		$ossArr = AreaApp::OssDeal($saveTo, $fileRelPath, $upPath . $fileRelPath);	// $upFileArr['newName']
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
		opener.InsertStrToEditor(\''. $fileFormName .'\',\'<img src="'. $imgUrl .'" border="0">\');
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
?>

<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><!-- big5  gb2312 gbk -->
	<title>上传图片</title>
	<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
	<link rel="stylesheet" type="text/css" href="style.css" />
	<style>
	#preview{width:100px;height:100px;border:1px solid #000;overflow:hidden;}
	#imghead {filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=image);}
	</style>
</head>

<body style="margin:8px;">

<script language='javascript' type='text/javascript'>
// 检测表单
function CheckForm(){
	var re= new RegExp("\.(gif|jpg|jpeg|png|bmp|swf)","ig");   
	if ( ! re.test(document.getElementById("upFile").value))
	{alert("请选择gif/jpg/png/bmp图片");return false;}

	initAd();
	return true;
}

function previewImage(file)
{
  var MAXWIDTH  = 100; 
  var MAXHEIGHT = 100;
  var div = document.getElementById('preview');
  if (file.files && file.files[0])
  {
	  div.innerHTML ='<img id=imghead>';
	  var img = document.getElementById('imghead');
	  img.onload = function(){
		var rect = clacImgZoomParam(MAXWIDTH, MAXHEIGHT, img.offsetWidth, img.offsetHeight);
		img.width  =  rect.width;
		img.height =  rect.height;
//                 img.style.marginLeft = rect.left+'px';
		img.style.marginTop = rect.top+'px';
	  }
	  var reader = new FileReader();
	  reader.onload = function(evt){img.src = evt.target.result;}
	  reader.readAsDataURL(file.files[0]);
  }
  else //兼容IE
  {
	var sFilter='filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale,src="';
	file.select();
	file.blur();
	var src = document.selection.createRange().text;
	div.innerHTML = '<img id=imghead>';
	var img = document.getElementById('imghead');
	img.filters.item('DXImageTransform.Microsoft.AlphaImageLoader').src = src;
	var rect = clacImgZoomParam(MAXWIDTH, MAXHEIGHT, img.offsetWidth, img.offsetHeight);
	status =('rect:'+rect.top+','+rect.left+','+rect.width+','+rect.height);
	div.innerHTML = "<div id=divhead style='width:"+rect.width+"px;height:"+rect.height+"px;margin-top:"+rect.top+"px;"+sFilter+src+"\"'></div>";
  }


	if (document.getElementById('judImgWater')){
		if (file.value.substr(file.value.length-4).toLowerCase()==".gif"){
			document.getElementById('alertStr').style.display="";
		}else{
			document.getElementById('alertStr').style.display="none";
		}
	}

}

function clacImgZoomParam( maxWidth, maxHeight, width, height ){
	var param = { width:width, height:height, top:0, left:0 };

	if( width>maxWidth || height>maxHeight ){
		rateWidth = width / maxWidth;
		rateHeight = height / maxHeight;

		if( rateWidth > rateHeight ){
			param.width =  maxWidth;
			param.height = Math.round(height / rateWidth);
		}else{
			param.width = Math.round(width / rateHeight);
			param.height = maxHeight;
		}
	}
	 
	param.left = Math.round((maxWidth - param.width) / 2);
	param.top = Math.round((maxHeight - param.height) / 2);
	return param;
}

// 检测是否打开图片参数
function CheckParK(){
	if (document.getElementById("isImgWH").checked == true){
		document.getElementById("imgParK").style.display = "";
	}else{
		document.getElementById("imgParK").style.display = "none";
	}
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

$isImgWH		= intval(@$_GET['isImgWH']);
$imgWHread		= intval(@$_GET['imgWHread']);
$imgW			= intval(@$_GET['imgW']);
$imgH			= intval(@$_GET['imgH']);
$isImgWater		= intval(@$_GET['isImgWater']);
$imgWaterW		= urlencode(trim(@$_GET['imgWaterW']));
$imgWaterH		= urlencode(trim(@$_GET['imgWaterH']));
$imgWaterName	= urlencode(trim(@$_GET['imgWaterName']));

?>
<div id="sponsorAdDiv" style="visibility:hidden">
<table style="width:250px; height:70px;" border='0' cellspacing='1' class="border1_1" bgcolor="#ACD6FF"><tr><td align='center'>
	<table style="width:100%; height:100%;"  border='0' cellspacing='0' summary=''>
	<tr><td align="center" class="font2_2">
		正在上传图片，请稍等.....
	</td></tr>
	</table>
</td></tr></table>
</div>


<form id='dealForm' name='dealForm' method='post' action="?mudi=upFileDeal" enctype="multipart/form-data" onsubmit="return CheckForm()" style="width:630px;margin:0 auto;">
<input type="hidden" name="upPath" value="<?php echo(urlencode(@$_GET['upPath'])); ?>" />
<input type="hidden" name="fileMode" value="<?php echo(urlencode(@$_GET['fileMode'])); ?>" />
<input type="hidden" name="fileFormName" value="<?php echo(urlencode(@$_GET['fileFormName'])); ?>" />

<input type="hidden" name="isThumb" value="<?php echo(urlencode(@$_GET['isThumb'])); ?>" />
<input type="hidden" name="thumbW" value="<?php echo(urlencode(@$_GET['thumbW'])); ?>" />
<input type="hidden" name="thumbH" value="<?php echo(urlencode(@$_GET['thumbH'])); ?>" />

<input type="hidden" name="isWatermark" value="<?php echo(urlencode(@$_GET['isWatermark'])); ?>" />
<input type="hidden" name="watermarkPath" value="<?php echo(urlencode(@$_GET['watermarkPath'])); ?>" />
<input type="hidden" name="watermarkPos" value="<?php echo(urlencode(@$_GET['watermarkPos'])); ?>" />
<input type="hidden" name="watermarkPadding" value="<?php echo(urlencode(@$_GET['watermarkPadding'])); ?>" />
<input type="hidden" name="watermarkFontContent" value="<?php echo(urlencode(@$_GET['watermarkFontContent'])); ?>" />
<input type="hidden" name="watermarkFontSize" value="<?php echo(urlencode(@$_GET['watermarkFontSize'])); ?>" />
<input type="hidden" name="watermarkFontColor" value="<?php echo(urlencode(@$_GET['watermarkFontColor'])); ?>" />

<input type="hidden" name="isImgWater" value="<?php echo($isImgWater); ?>" />
<input type="hidden" name="imgWaterW" value="<?php echo($imgWaterW); ?>" />
<input type="hidden" name="imgWaterH" value="<?php echo($imgWaterH); ?>" />
<input type="hidden" name="imgWaterName" value="<?php echo($imgWaterName); ?>" />

<input type="hidden" name="areaMode" value="<?php echo(urlencode(@$_GET['areaMode'])); ?>" />


<div class='tabMenu'>
<ul>
	<li class='selected'>上传图片</li>
	<li><a href='info_upFile.php<?php echo($URL); ?>' class='font3_1'>上传文件</a></li>
	<?php echo(AppUpload::UpFileBox1($URL)); ?>
	<li><a href='serverFile.php<?php echo($URL); ?>' class='font3_1'>服务器文件</a></li>
</ul>
</div>


<table align='center' cellpadding="0" cellspacing="0" border="0" summary=''>
<tr>
	<td>
		<fieldset><legend class='font2_2' style="font-size:14px;"><b>上传图片</b></legend>
		<table style="width:450px; height:106px;" border="0" align="center" summary=''>
		<tr style="height:30px;"> 
			<td class="font1_2">本地图片：</td>
			<td colspan="3" align="left">
				<input type="file" size='20' style='width:280px;' id="upFile" name="upFile" onChange="previewImage(this)" />
				<div id="alertStr" style="color:red;display:none;">如果是gif动态图片，请关闭水印，不然会变静态图片.</div>
			</td>
		</tr>
		<tr style="height:30px;"> 
			<td class="font1_2">&ensp;&ensp;保存至：</td>
			<td colspan="3" align="left">
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
	<td style="padding-left:5px">
		<fieldset><legend class='font2_2' style="font-size:14px;"><b>预览</b></legend>
		<table style="width:100px; height:100px;" border="0" align="center" summary=''>
		<tr><td>
			<div id="preview"><img id="imghead" src="images/noPic2.gif" /></div>
		</td></tr>
		</table>
		</fieldset>
	</td>
</tr>
<tr>
	<td style="padding-top:5px;" colspan='2'>
		<fieldset><legend class='font2_2' style="font-size:14px;"><b>图片参数</b></legend>
			<div class="font1_2">
			<label><input type="checkbox" id="isImgWH" name="isImgWH" value="1" onclick="<?php if ($imgWHread==1){echo('this.checked="true";');} ?>CheckParK();" <?php if ($isImgWH==1){echo('checked="checked"');} ?> />启用宽高</label>&ensp;
			<span id="imgParK">
				宽<input type="text" size="2" id="imgW" name="imgW" style="width:40px;" <?php if ($imgWHread==1){echo('readonly="true" ');}if ($imgW>0){echo('value="'. $imgW .'"');} ?> />×高<input type="text" size="2" id="imgH" name="imgH" style="width:40px;" <?php if ($imgWHread==1){echo('readonly="true" ');}if ($imgH>0){echo('value="'. $imgH .'"');} ?> />
				<?php if ($imgWHread==1){echo('<span class="font3_2">[已锁定]</span>');} ?><span class="font2_2">(宽高如只填一个，另一个则自动自适应)</span>
			</span>
			<?php
			if ($sysImagesArr['SI_isWatermark'] != 'false' && @$_GET['isWatermark'] != 'false'){
				echo('&ensp;&ensp;<label><input type="checkbox" id="judImgWater" name="judImgWater" value="1" '. (intval(@$_GET['closeWater'])==1 ? 'checked="checked"' : '') .' />关闭水印</label>');
			}
			?>
			</div>
		</fieldset>
	</td>
</tr>
</table>

<script language='javascript' type='text/javascript'>
CheckParK();
</script>

</form>

</body>
</html>
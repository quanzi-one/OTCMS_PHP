<?php

if(!defined('OT_ROOT')) {
	exit('Access Denied');
}


class SaveImg{
	const IMG_EXT = 'jpg|jpeg|gif|bmp|png|webp';	// 远程图片保存类型
	public $oldImgStr;		// 源图片名集
	public $imgStr;			// 现图片名集
	public $errStr;			// 错误集
	public $sizeStr;		// 图片大小集
	public $collErrStr;		// 采集错误

	public $judProxy = false;		// 是否使用代理
	public $saveOss = 'web';		// 图片存放点


	function __construct(){
		$this->oldImgStr	= '';
		$this->imgStr		= '';
		$this->errStr		= '';
		$this->sizeStr		= '';
		$this->collErrStr	= '';
	}


	// 替换字符串中的远程文件为本地文件并保存远程文件
	// siContent：要处理的字符串；siSavePath：保存文件的路径；siImgMaxSize：采集的图片最大大小；currWebUrl：当前网页地址；siImgPath：图片文件的路径
	function ReplaceContent($siContent,$siSavePath,$siImgMaxSize=0,$currWebUrl='',$siImgPath=''){
		$this->oldImgStr= '';
		$this->imgStr	= '';
		$this->errStr	= '';
		$this->sizeStr	= '';
		$this->collErrStr	= '';

		if (substr($siSavePath,-1) != '/'){ $siSavePath .= '/'; }
		if (strlen($siImgPath) > 3){
			if (substr($siImgPath,-1) != '/'){ $siImgPath .= '/'; }
		}

		preg_match_all("/src\s*=\s*[\\\"|\']?((\s*[^>\\\"\'\s]*\.)(". self::IMG_EXT ."))/is",$siContent,$img_array);
	//	preg_match_all("/(src|src)=[\"|'| ]{0,}((.*).(jpg|jpeg|gif|bmp|png))/is",$siContent,$img_array);
		$img_array = array_unique($img_array[1]);	// 去掉重复图片
		$imgOld_array = $img_array;

		// 转换相对图片地址开始和已存在该网站上的图片
		if ($currWebUrl==''){
			$currWebUrl = dirname(GetUrl::HttpHead() . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
		}
		$nowWebUrl = strtolower(GetUrl::HttpHead() . $_SERVER['HTTP_HOST']);

		$appSysArr = Cache::PhpFile('appSys');

		foreach ($img_array as $key => $value) {
			$valTemp = strtolower($value);
			if (strpos($valTemp, $nowWebUrl) !== false || 
				(strlen($appSysArr['AS_qiniuUrl']) > 8 && strpos($valTemp, $appSysArr['AS_qiniuUrl']) !== false) || 
				(strlen($appSysArr['AS_upyunUrl']) > 8 && strpos($valTemp, $appSysArr['AS_upyunUrl']) !== false) || 
				(strlen($appSysArr['AS_aliyunUrl']) > 8 && strpos($valTemp, $appSysArr['AS_aliyunUrl']) !== false) || 
				(strlen($appSysArr['AS_jinganUrl']) > 8 && strpos($valTemp, $appSysArr['AS_jinganUrl']) !== false) || 
				(strlen($appSysArr['AS_ftpUrl']) > 8 && strpos($valTemp, $appSysArr['AS_ftpUrl']) !== false)){
				unset($img_array[$key]);
			}else{
				$img_array[$key] = $this->RealUrl(trim($value), $currWebUrl);
			}
		}

		// 如果没有图片则退出函数
		if (empty($img_array)){
			return $siContent;
		}

		$isSaveFile=true;

		$sysImagesArr = Cache::PhpFile('sysImages');
		$saveDirPath = '';
		if ($sysImagesArr['SI_isDir'] > 0){
			// 判断并创建本地目录
			switch ($sysImagesArr['SI_isDir']){
				case 1:	$saveDirPath = TimeDate::Get('Y') .'/';		break;
				case 2:	$saveDirPath = TimeDate::Get('Ym') .'/';	break;
				case 3:	$saveDirPath = TimeDate::Get('Ymd') .'/';	break;
			}
			/* if (! File::CreateDir($upPath . $saveDirPath)){
				$saveDirPath = '';
			} */
		}

		// 判断并创建本地目录，分年和月保存
		$childDir = 'coll/'. $saveDirPath;
		$siSavePath2 = $siSavePath . $childDir;
		// response.write "链接路径：" & siSavePath & "<br>"
		$pathArr	= explode('/',$siSavePath2);
		$pathTemp	= '';
		for ($Pi=0; $Pi<count($pathArr)-1; $Pi++){
			if ($Pi==0){
				$pathTemp = $pathArr[0] .'/';
			}else{
				$pathTemp .= $pathArr[$Pi] .'/';
			}
			
			if ($this->CreateDir($pathTemp)==false){
				$childDir = 'coll/';
				$siSavePath2 = $siSavePath . $childDir;
				if ($this->CheckDir($siSavePath2)==false){
					$childDir = '';
				}
				$isSaveFile=false;
			}
		}
		$siSavePath .= $childDir;
		if (strlen($siImgPath) > 3){
			$siImgPath .= $childDir;
		}

		// 图片替换/保存
		foreach ($img_array as $key => $value){
			$remFileUrl		= $value;
			$remFileUrlOld	= $imgOld_array[$key];
			if ($remFileUrl!='' && $isSaveFile==true){
				$newFileArr = explode('.',$remFileUrl);
				$newFileExt = strtolower($newFileArr[count($newFileArr)-1]);	// 文件类型
				if (strlen($newFileExt)>8){ $newFileExt = 'jpg'; }
				
				// 判断保存文件不能为以下文件类型
				if (strpos('|asp|asa|aspx|cer|cdx|exe|rar|zip|bat|','|'. $newFileExt .'|') !== false){
					return $siContent;
				}
				
				$newFileName = 'OT'. date('YmdHis') . OT::RndNum(3) .'.'. $newFileExt;
				$newFilePath = $siSavePath . $newFileName;
				
				// 执行保存操
				$srfArr = $this->SaveRemoteFile($newFilePath, $remFileUrl, $childDir . $newFileName, $siImgMaxSize, 1);
				if ($srfArr['res']){
					if (strlen($siImgPath) > 3){ $newFilePath = $siImgPath . $newFileName; }
					if (strlen($srfArr['url']) > 3){
						$srfImg = $srfArr['url'];
						$newFilePath = $srfArr['url'];
					}else{
						$srfImg = $childDir . $newFileName;
					}

					$this->oldImgStr	.= '|'. $remFileUrl;
					$this->imgStr		.= '|'. $srfImg;
					$this->sizeStr		.= '|'. $srfArr['size'];
					$siContent = str_replace($remFileUrlOld,$newFilePath,$siContent);
					
				}else{
					$this->errStr	.= '|'. $remFileUrl;
					$siContent = str_replace($remFileUrlOld,$remFileUrl,$siContent);
				}
			}elseif ($remFileUrl!='False' && $isSaveFile==false){	// 不保存图片
				$this->errStr	.= '|'. $remFileUrl;
				$siContent = str_replace($remFileUrlOld,$remFileUrl,$siContent);
			}
		}

		return $siContent;
	}


	// 获取内容图片集
	function GetImgUrlArr($imgContent, $currWebUrl){
		preg_match_all("/src\s*=\s*[\\\"|\']?((\s*[^>\\\"\'\s]*\.)(". self::IMG_EXT ."))/is",$imgContent,$img_array);
	//	preg_match_all("/(src|src)=[\"|'| ]{0,}((.*).(jpg|jpeg|gif|bmp|png))/is",$imgContent,$img_array);
		$img_array = array_unique($img_array[1]);
	//	echo("【". $imgContent ."|". $currWebUrl ."|/src\s*=\s*[\\\"|\']?((\s*[^>\\\"\'\s]*\.)(". self::IMG_EXT ."))/is】");
	//	print_r($img_array);
		foreach ($img_array as $key => $value) {
			$img_array[$key] = $this->RealUrl(trim($value), $currWebUrl);
		}
		return $img_array;
	}


	// 获取内容图片集（没用到）
	function GetImgRelUrlArr($imgContent){
		preg_match_all("/src\s*=\s*[\\\"|\']?((\s*[^>\\\"\'\s]*\.)(". self::IMG_EXT ."))/is",$imgContent,$img_array);
	//	preg_match_all("/(src|src)=[\"|'| ]{0,}((.*).(jpg|jpeg|gif|bmp|png))/is",$imgContent,$img_array);
		$img_array = array_unique($img_array[1]);
		return $img_array;
	}


	// 处理采集图片路径为绝对路径
	// siContent：要处理的字符串；siSavePath：保存文件的路径; currWebUrl：当前网页地址
	function ReplaceRealUrlContent($siContent,$currWebUrl){
		preg_match_all("/src\s*=\s*[\\\"|\']?((\s*[^>\\\"\'\s]*\.)(". self::IMG_EXT ."))/is",$siContent,$img_array);
	//	preg_match_all("/(src|src)=[\"|'| ]{0,}((.*).(jpg|jpeg|gif|bmp|png))/is",$siContent,$img_array);
		$img_array = array_unique($img_array[1]);
		
		if (! function_exists('newfuncImg')){
			function newfuncImg($a,$b){
				return strlen($a) < strlen($b);
			}
		}
		usort($img_array, 'newfuncImg');

		$imgArr = array();
		$imgNum = 0;
		foreach ($img_array as $value) {
			$imgArr[$imgNum] = $this->RealUrl(trim($value), $currWebUrl);
			$siContent = str_replace($value, '[OTarr'. $imgNum .']', $siContent);
			$imgNum ++;
		}
		$imgNum = 0;
		foreach ($imgArr as $val) {
			$siContent = str_replace('[OTarr'. $imgNum .']', $val, $siContent);
			$imgNum ++;
		}
		return $siContent;
	}


	// 保存远程的文件到本地
	// siLocalFile:本地文件名；siRemoteFile:远程文件URL；siOssFile:云存储文件名；siImgMaxSize:下载文件最大大小(KB)
	function SaveRemoteFile($siLocalFile, $siRemoteFile, $siOssFile='', $siImgMaxSize=10240, $siWater=1){
		$retArr = array('res'=>false, 'size'=>0, 'oss'=>'web', 'url'=>'', 'note'=>'');
		$getFileStr = $this->GetUrlContent($siRemoteFile,60);
		/* if(empty($getFileStr)){
			@sleep(1);
			$getFileStr = $this->GetUrlContent($siRemoteFile,30);
		} */
		if(! empty($getFileStr) ){
			$retSize = strlen($getFileStr);
			$retArr['size'] = $retSize;
			if ($siImgMaxSize>0 && $retSize>$siImgMaxSize*1024){
				$this->collErrStr = '图片/文件大小超出设定的最大值('. $retSize .'|'. $siImgMaxSize*1024 .')';
				$retArr['note'] = $this->collErrStr;
				return $retArr;
			}elseif ($retSize == 5 && strtolower($getFileStr) == 'false'){
				$this->collErrStr='图片/文件内容为false';
				$retArr['note'] = $this->collErrStr;
				return $retArr;
			}
			$fp = fopen($siLocalFile,'w');
			$isWrite = fwrite($fp,$getFileStr);
			fclose($fp);
			if(! $isWrite){
				$this->collErrStr='无法保存到本地';
				$retArr['note'] = $this->collErrStr;
				return $retArr;
			}else{
				$retArr['res'] = true;
				if ($siWater == 1){
					if ($sysImagesFile = @include(OT_ROOT .'cache/php/sysImages.php')){
						$sysImagesArr = unserialize($sysImagesFile);
	
						// 文字水印
						if ($sysImagesArr['SI_isWatermark'] == 'font'){
							$imgWaterArr=array();
							$imgWaterArr['upLoadImg']	= $siLocalFile;
							$imgWaterArr['waterPos']	= $sysImagesArr['SI_watermarkPos'];
							$imgWaterArr['waterPadding']= $sysImagesArr['SI_watermarkPadding'];
							$imgWaterArr['waterText']	= $sysImagesArr['SI_watermarkFontContent'];
							$imgWaterArr['textFont']	= $sysImagesArr['SI_watermarkFontSize'];
							$imgWaterArr['textColor']	= $sysImagesArr['SI_watermarkFontColor'];
							Images::Watermark($imgWaterArr);
	
						// 图片水印
						}elseif ($sysImagesArr['SI_isWatermark'] == 'img'){
							$waterPath = ImagesFileAdminDir . $sysImagesArr['SI_watermarkPath'];
							if (! file_exists($waterPath)){ $waterPath = ImagesFileDir . $sysImagesArr['SI_watermarkPath']; }
							$imgWaterArr=array();
							$imgWaterArr['upLoadImg']	= $siLocalFile;
							$imgWaterArr['waterPos']	= $sysImagesArr['SI_watermarkPos'];
							$imgWaterArr['waterPadding']= $sysImagesArr['SI_watermarkPadding'];
							$imgWaterArr['waterTrans']	= $sysImagesArr['SI_watermarkTrans'];
							$imgWaterArr['waterImg']	= $waterPath;
							Images::Watermark($imgWaterArr);
	
						}
					}
				}
				if ( strlen($siOssFile) > 0 && in_array($this->saveOss, AreaApp::OssNameArr()) ){
					$retArr['oss'] = $this->saveOss;
					$ossArr = AreaApp::OssDeal($this->saveOss, $siOssFile, $siLocalFile);	// pathinfo($siLocalFile,PATHINFO_BASENAME)
					if ($ossArr['res']){
						$retArr['url'] = $ossArr['path'];
					}
				}
			}
			$retArr['note'] = '保存成功';
			// @sleep(2);
		}else{
			$this->collErrStr='获取不到图片/文件';
			$retArr['note'] = $this->collErrStr;
			return $retArr;
		}
		return $retArr;
	}


	// 将相对地址转换为绝对地址
	// getStrUrl:要转换的相对地址；currUrl:当前网页地址
	function RealUrl($getStrUrl,$currUrl){
		$retStr = '';
		$Pi=0;
		$Ci=0;
		$getStrUrlArr=array();
		$currUrlArr=array();

		if ($getStrUrl=='' || $currUrl=='' || $getStrUrl=='False' || $currUrl=='False'){
			$retStr = $getStrUrl;
		}
		if (substr(strtolower($currUrl),0,7)!='http://' && substr(strtolower($currUrl),0,8)!='https://'){
			$currUrl= GetUrl::HttpHead() . $currUrl;
		}
		$currUrl	= str_replace(array("\\","://"),array("/",":\\\\"),$currUrl);
		$getStrUrl	= str_replace("\\","/",$getStrUrl);

		if (substr($currUrl,-1)!='/'){
			if (strpos($currUrl,'/') !== false){
				if (strpos(substr($currUrl,strrpos($currUrl,'/')+1),'.') !== false){
				}else{
					$currUrl .= '/';
				}
			}else{
				$currUrl .= '/';
			}
		}
		$currUrlArr=explode('/',$currUrl);

		if (substr($getStrUrl,0,2) == '//'){
			return 'http:'. $getStrUrl;

		}else if (substr(strtolower($getStrUrl),0,7) == 'http://' || substr(strtolower($getStrUrl),0,8) == 'https://'){
			$retStr = str_replace("://",":\\\\",$getStrUrl);

		}else if (substr($getStrUrl,0,1) == '/'){
			$retStr = $currUrlArr[0] . $getStrUrl;

		}else if (substr($getStrUrl,0,2) == './'){
			$getStrUrl=substr($getStrUrl,2);
			if (substr($currUrl,-1)=='/'){   
				$retStr = $currUrl . $getStrUrl;
			}else{
				$retStr = substr($currUrl,0,strrpos($currUrl,'/')+1) . $getStrUrl;
			}

		}else if (substr($getStrUrl,0,3)=='../'){
			while (substr($getStrUrl,0,3)=='../'){
				$getStrUrl=substr($getStrUrl,3);
				$Pi ++;
			}
			for ($Ci=0; $Ci<count($currUrlArr)-1-$Pi; $Ci++){
				if ($retStr!=''){
					$retStr .= '/'. $currUrlArr[$Ci];
				}else{
					$retStr = $currUrlArr[$Ci];
				}
			}
			if ($retStr==''){ $retStr = $currUrlArr[0]; }
			$retStr .= '/'. $getStrUrl;

		}else if (substr($getStrUrl,0,1)=='?'){
			if (strpos($currUrl,'?') !== false){   
				$retStr = substr($currUrl,0,strpos($currUrl,'?')) . $getStrUrl;
			}else{
				$retStr = $currUrl . $getStrUrl;
			}

		}else{
			if (strpos($getStrUrl,'/') !== false){
				$getStrUrlArr=explode('/',$getStrUrl);
				if (strpos($getStrUrlArr[0],'.') !== false){
					if (substr($getStrUrl,-1)=='/'){
						$retStr = 'http:\\'. $getStrUrl;
					}else{
						if (strpos($getStrUrlArr[count($getStrUrlArr)-2],'.') !== false){
							$retStr = 'http:\\'. $getStrUrl;
						}else{
							$retStr = 'http:\\'. $getStrUrl .'/';
						}
					}
				}else{
					if (substr($currUrl,-1)=='/'){ 
						$retStr = $currUrl . $getStrUrl;
					}else{
						$retStr = substr($currUrl,0,strrpos($currUrl,'/')+1) . $getStrUrl;
					}
				}
			}else{
				if (strpos($getStrUrl,'.') !== false){
					if (substr($currUrl,-1)=='/'){
						if (in_array(substr(strtolower($getStrUrl),-3),array('.cn','com','net','org'))){
							$retStr = 'http:\\'. $getStrUrl .'/';
						}else{
							$retStr = $currUrl . $getStrUrl;
						}
					}else{
						if (in_array(substr(strtolower($getStrUrl),-3),array('.cn','com','net','org'))){
							$retStr = 'http:\\'. $getStrUrl .'/';
						}else{
							$retStr = substr($currUrl,0,strrpos($currUrl,'/')+1) .'/'. $getStrUrl;
						}
					}
				}else{
					if (substr($currUrl,-1)=='/'){
						$retStr = $currUrl . $getStrUrl .'/';
					}else{
						$retStr = substr($currUrl,0,strrpos($currUrl,'/')+1) .'/'. $getStrUrl .'/';
					}         
				}
			}
		}

		if (substr($retStr,0,1)=='/'){
			$retStr = substr($retStr,1);
		}
		if ($retStr != ''){
			$retStr = str_replace('//','/',$retStr);
			$retStr = str_replace(":\\\\",'://',$retStr);
		}else{
			$retStr = $getStrUrl;
		}
		return $retStr;
	}


	// 获取需要的地址段
	// currUrl:待处理网址；rank:级数，0根地址，1子目录地址，2孙目录地址...
	function RankUrl($currUrl,$rank){
		$retStr = '';

		if ($currUrl==''){
			return $currUrl;
		}
		if (substr(strtolower($currUrl),0,7)!='http://' && substr(strtolower($currUrl),0,8)!='https://'){
			$currUrl= GetUrl::HttpHead() . $currUrl;
		}
		$currUrl	= str_replace(array("\\","://"),array("/",":\\\\"),$currUrl);

		if (substr($currUrl,-1)!='/'){
			if (strpos($currUrl,'/') !== false){
				if (strpos(substr($currUrl,strrpos($currUrl,'/')+1),'.') !== false){
				}else{
					$currUrl .= '/';
				}
			}else{
				$currUrl .= '/';
			}
		}
		$currUrlArr=explode('/',$currUrl);

		if ($rank>(count($currUrlArr)-2)){
			$rank = count($currUrlArr)-2;
		}
		for ($Ci=0; $Ci<=$rank; $Ci++){
			if ($retStr!=''){
				$retStr .= '/'. $currUrlArr[$Ci];
			}else{
				$retStr = $currUrlArr[$Ci];
			}
		}

		if ($retStr!=''){
			$retStr = str_replace(array("//",":\\\\"),array('/','://'),$retStr);
		}else{
			$retStr = $currUrl;
		}
		return $retStr;
	}


	// 建立目录
	function CreateDir($dir){
		$retJud=false;
		if (! is_dir($dir)){
			if (mkdir($dir, 0755)){
				$retJud=true;
			}
			//创建index.html,目的防止列目录漏洞
			//fclose(fopen($dir.'/index.html', 'w'));
			@chmod($dir, 0755);
		}else{
			$retJud=true;
		}
		return $retJud;
	}

	// 检查目录是否存在
	function CheckDir($dir){
		if (is_dir($dir)) { 
			return true;
		}else{ 
			return false;
		} 
	}


	function GetUrlContent($url, $timeout=30){
		global $DB,$systemArr;

		class_exists('ReqUrl',false) or require(OT_ROOT .'inc/classReqUrl.php');

		if ($this->judProxy && strlen($systemArr['SYS_proxyIpList']) > 8){
			$proxyIp = '';
			$proxyPort = 80;
			$currIp = Area::ListPoint('proxyIp',$systemArr['SYS_proxyIpList']);
			$oneArr = explode(':', $currIp);
			$proxyIp = $oneArr[0];
			if (count($oneArr) >= 2){ $proxyPort = $oneArr[1]; }

			$retArr = ReqUrl::ProxyCurl('GET', $url, array('ip'=>$proxyIp,'port'=>$proxyPort));
		}else{
			$retArr = ReqUrl::UseAuto(0, 'GET', $url);
		}
		if (! $retArr['res']){ $retStr='False'; }else{ $retStr=$retArr['note']; }
/*
		if ( function_exists('curl_init') ){
			$ch = curl_init();
			curl_setopt ($ch, curlopt_url, $url);
			curl_setopt ($ch, curlopt_returntransfer, 1);
			curl_setopt ($ch, curlopt_connecttimeout, $timeout);
			$retStr = curl_exec($ch);
			curl_close($ch);
		}elseif ( ini_get('allow_url_fopen') == 1 || strtolower(ini_get('allow_url_fopen')) == 'on' ){
			$retStr = @file_get_contents($url);
		}else {
			class_exists('Snoopy',false) or include(OT_ROOT .'inc/Snoopy.class.php');
			$snoopy = new Snoopy();
			$snoopy->fetch($url);
			$retStr = $snoopy->results;
		}
*/
		return $retStr;
	}

}
?>
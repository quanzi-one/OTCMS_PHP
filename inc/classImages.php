<?php
if(!defined('OT_ROOT')) {
	exit('Access Denied');
}


class Images{

	// 返回GD函数版本号
	public static function Ver(){
		if (function_exists('gd_info')){
			$GDArray = gd_info();
			$gd_version_number = $GDArray['GD Version'] ? $GDArray['GD Version'] : 0;
			unset($GDArray);
		} else {
			$gd_version_number = 0;
		}
		return $gd_version_number;
	}



	// 创建水印(支持图片或文字)
	/*
	数组参数：
	upLoadImg 背景图片，即需要加水印的图片，暂只支持GIF,JPG,PNG格式；
	waterImg 图片水印，即作为水印的图片，暂只支持GIF,JPG,PNG格式；
	waterPos 水印位置，有10种状态；
		1为顶端居左，2为顶端居中，3为顶端居右；
		4为中部居左，5为中部居中，6为中部居右；
		7为底端居左，8为底端居中，9为底端居右，其他为随机；
	waterPadding 图片水印的间距，默认为3；
	waterTrans 图片水印的透明度，0~100(0完全透明，100完全不透明)；
	waterText 文字水印，即把文字作为为水印，支持ASCII码，不支持中文；
	textFont 文字大小，值为1、2、3、4或5，默认为5；
	textColor 文字颜色，值为十六进制颜色值，默认为#FF0000(红色)；

	注意：Support GD 2.0，Support FreeType、GIF Read、GIF Create、JPG 、PNG
	$waterImg 和 $waterText 最好不要同时使用，选其中之一即可，优先使用 $waterImg。
	当$waterImg有效时，参数$waterString、$stringFont、$stringColor均不生效。
	加水印后的图片的文件名和 $upLoadImg 一样。
	*/
	public static function Watermark($ICWarray=array()){
		$upLoadImg		= isset($ICWarray['upLoadImg']) && empty($ICWarray['upLoadImg'])==false ? $ICWarray['upLoadImg'] : NULL;
		$waterImg		= isset($ICWarray['waterImg']) && empty($ICWarray['waterImg'])==false ? $ICWarray['waterImg'] : '';
			/* if (strlen($waterImg) < 3){
				die('您开启了水印功能，但没上传水印图，请先到【常规设置】-【图片生成设置】关闭水印功能或者上传水印图。');
			} */
		$waterPos		= isset($ICWarray['waterPos']) && empty($ICWarray['waterPos'])==false ? $ICWarray['waterPos'] : 'rightBottom';
		$waterPadding	= isset($ICWarray['waterPadding']) && empty($ICWarray['waterPadding'])==false ? $ICWarray['waterPadding'] : 3;
		$waterTrans		= isset($ICWarray['waterTrans']) && empty($ICWarray['waterTrans'])==false ? $ICWarray['waterTrans'] : 80;
			if ($waterTrans < 10){ $waterTrans = 80; }
		$waterText		= isset($ICWarray['waterText']) && empty($ICWarray['waterText'])==false ? $ICWarray['waterText'] : '';
		$textFont		= isset($ICWarray['textFont']) && empty($ICWarray['textFont'])==false ? $ICWarray['textFont'] : 5;
		$textColor		= isset($ICWarray['textColor']) && empty($ICWarray['textColor'])==false ? $ICWarray['textColor'] : '#FF0000';
		$fontFile = OT_ROOT .'tools/simsun.ttc';
		if (strpos(strtolower(PHP_OS), 'linux') !== false){
			// $fontFile = '/usr/share/fonts/simsun.ttc';
		}else{
			if (! file_exists($fontFile)){ $fontFile = 'C:\WINDOWS\Fonts\simsun.ttc'; }
		}
		// if (! file_exists($fontFile)){ $fontFile = OT_ROOT .'inc/simsun.ttc'; }
		if (! file_exists($fontFile)){ $fontFile = OT_ROOT .'inc/imageWatermark.ttf'; }
		// print_r(File::GetFileList(''));die();

		if (strtolower(substr($upLoadImg,-4))=='.gif' && strtolower(substr($waterImg,-4))=='.png'){
			$waterImg = str_replace('.png','.gif',$waterImg);
		}

		$isWaterImg = false;

		//读取水印文件
		if ((! empty($waterImg)) && file_exists($waterImg)){
			$isWaterImg = true;
			// 0 宽度，1 高度，2 类型（1 = GIF，2 = JPG，3 = PNG，4 = SWF，5 = PSD，6 = BMP，7 = TIFF(intel byte order)，8 = TIFF(motorola byte order)，9 = JPC，10 = JP2，11 = JPX，12 = JB2，13 = SWC，14 = IFF，15 = WBMP，16 = XBM），3 是文本字符串，内容为“height="yyy" width="xxx"”，可直接用于 IMG 标记。
			$water_info = getimagesize($waterImg);
			$water_w = $water_info[0];	//取得水印图片的宽
			$water_h = $water_info[1];	//取得水印图片的高

			switch($water_info[2]){	//取得水印图片的格式
				case 1: $water_im = imagecreatefromgif($waterImg);break;
				case 2: $water_im = imagecreatefromjpeg($waterImg);break;
				case 3: $water_im = imagecreatefrompng($waterImg);break;
				default: die('暂不支持该水印图文件格式（'. $water_info[2] .'），请用图片处理软件将图片转换为GIF、JPG、PNG格式。');
			}
		}

		//读取背景图片 
		if (! empty($upLoadImg) && file_exists($upLoadImg)){ 
			$ground_info = getimagesize($upLoadImg);
			$ground_w = $ground_info[0];	//取得背景图片的宽
			$ground_h = $ground_info[1];	//取得背景图片的高

			switch($ground_info[2]){	//取得背景图片的格式
				case 1: $ground_im = imagecreatefromgif($upLoadImg);break;
				case 2: $ground_im = imagecreatefromjpeg($upLoadImg);break;
				case 3: $ground_im = imagecreatefrompng($upLoadImg);break;
				default: return '';	// die('暂不支持该图片文件格式（'. $ground_info[2] .'），请用图片处理软件将图片转换为GIF、JPG、PNG格式。')
			} 
		} else {
			die('需要加水印的图片不存在！');
		}

		//水印位置
		if($isWaterImg){	//图片水印
			$w = $water_w;
			$h = $water_h;
			$label = '图片的';
		} else {	//文字水印
	//		$temp = imagettfbbox(ceil($textFont*2.5),0,dirname(__FILE__) .'/imageWatermark.ttf',$waterText);	//取得使用 TrueType 字体的文本的范围
			$temp = imagettfbbox($textFont,0,$fontFile,$waterText);	//取得使用 TrueType 字体的文本的范围
			$w = $temp[2] - $temp[6];
			$h = $temp[3] - $temp[7];
			unset($temp);
			$label = '文字区域';
		} 

		if ( ($ground_w<$w) || ($ground_h<$h) ){ 
			echo '需要加水印的图片的长度或宽度比水印'. $label .'还小，无法生成水印！';
			return;
		}

		switch ($waterPos){ 
			case 'leftTop'://1为顶端居左
				$posX = $waterPadding;
				$posY = $waterPadding;
				break;
			case 'centerTop'://2为顶端居中
				$posX = ($ground_w - $w) / 2 + $waterPadding;
				$posY = 0;
				break;
			case 'rightTop'://3为顶端居右
				$posX = $ground_w - $w - $waterPadding;
				$posY = $waterPadding;
				break;
			case 'leftMiddle'://4为中部居左
				$posX = $waterPadding;
				$posY = ($ground_h - $h) / 2;
				break;
			case 'centerMiddle'://5为中部居中
				$posX = ($ground_w - $w) / 2;
				$posY = ($ground_h - $h) / 2;
				break;
			case 'rightMiddle'://6为中部居右
				$posX = $ground_w - $w - $waterPadding;
				$posY = ($ground_h - $h) / 2;
				break;
			case 'leftBottom'://7为底端居左
				$posX = $waterPadding;
				$posY = $ground_h - $h - $waterPadding;
				break;
			case 'centerBottom'://8为底端居中
				$posX = ($ground_w - $w) / 2;
				$posY = $ground_h - $h - $waterPadding;
				break;
			case 'rightBottom'://9为底端居右
				$posX = $ground_w - $w - $waterPadding;
				$posY = $ground_h - $h - $waterPadding;
	//			die($ground_w .'-'. $w .'-'. $waterPadding);
				break;
			default://随机
				$posX = rand(0,($ground_w - $w));
				$posY = rand(0,($ground_h - $h));
				break;
		}

		//设定图像的混色模式
		imagealphablending($ground_im, true);

		if ($isWaterImg){	//图片水印
			//拷贝水印到目标文件
			if (substr(strtolower($waterImg),-4)=='.png'){
				if ($waterTrans >= 100){
					imagecopy($ground_im, $water_im, $posX, $posY, 0, 0, $water_w, $water_h);
				}else{
					self::imagecopymerge_alpha($ground_im, $water_im, $posX, $posY, 0, 0, $water_w, $water_h, $waterTrans);
				}
			}elseif (function_exists('move_uploaded_file')){
				imagecopymerge($ground_im, $water_im, $posX, $posY, 0, 0, $water_w, $water_h, $waterTrans);
			}else{
				imagecopy($ground_im, $water_im, $posX, $posY, 0, 0, $water_w, $water_h);
			}
		} else {	//文字水印
			if ( !empty($textColor) && (strlen($textColor)==7) ){ 
				$R = hexdec(substr($textColor,1,2));
				$G = hexdec(substr($textColor,3,2));
				$B = hexdec(substr($textColor,5));
			} else {
				die('水印文字颜色格式不正确！');
			}

	//		imagestring($ground_im, $textFont, $posX, $posY, $waterText, imagecolorallocate($ground_im, $R, $G, $B));
			imagettftext($ground_im, $textFont, 0, $posX, $posY, imagecolorallocate($ground_im, $R, $G, $B), $fontFile, $waterText);
		}

		//生成水印后的图片
		@unlink($upLoadImg);
		switch ($ground_info[2]){	//取得背景图片的格式
			case 1: imagegif($ground_im,$upLoadImg);break;
			case 2: imagejpeg($ground_im,$upLoadImg);break;
			case 3: imagepng($ground_im,$upLoadImg);break;
			default: die($errorMsg);
		}

		//释放内存 
		if(isset($water_info)) unset($water_info);
		if(isset($water_im)) imagedestroy($water_im);
		unset($ground_info);
		imagedestroy($ground_im);
	}





	//生成缩略图
	/*
	数组参数：
	imgPath  原图片路径，只支持GIF,JPG,PNG格式(如：img/image.gif)；
	thumbWidth  缩略图的宽度；
	thumbHeight  缩略图的高度；
		thumbWidth和thumbHeight都是可选，如都为空，则生成100*100图片；如只有一个有值，则以它为基数，按比例计算出另一个值；
	thumbDir  缩略图存放目录，如为空，则用原图片存放目录一致（如：img/）；
	thumbPrefix缩略图前缀名，默认名为'thumb_'；
	*/
	public static function Thumb($ICTarray=array()){
		$imgPath	= $ICTarray['imgPath'] ? $ICTarray['imgPath'] : NULL;
		$thumbWidth	= intval($ICTarray['thumbWidth'])>0 ? $ICTarray['thumbWidth'] : 0;
		$thumbHeight= intval($ICTarray['thumbHeight'])>0 ? $ICTarray['thumbHeight'] : 0;
		$thumbDir	= isset($ICTarray['thumbDir']) && empty($ICTarray['thumbDir'])==false ? $ICTarray['thumbDir'] : pathinfo($imgPath, PATHINFO_DIRNAME);
			if (isset($ICTarray['thumbPrefix'])==false){
				$thumbPrefix = 'thumb_';
			}else{
				$thumbPrefix = $ICTarray['thumbPrefix'];
			}
			if (substr($thumbDir,-1) != '/'){$thumbDir .= '/';}
		$imgName	= pathinfo($ICTarray['imgPath'], PATHINFO_BASENAME);
		$imgExt		= strtolower(pathinfo($imgPath, PATHINFO_EXTENSION));
		$thumbName	= $thumbPrefix . $imgName;

		$return = array();
		$return['name'] = '';
		$return['path'] = '';
		$image  = '';
	//	if ( $thumbWidth>0 || $thumbHeight>0 ){
			$imgInfo = @GetImageSize( $imgPath );
			if ( $imgInfo[0] < 1 && $imgInfo[1] < 1 ){
				$imgInfo = array();
				$imgInfo[0] = $thumbWidth;
				$imgInfo[1] = $thumbHeight;
				return $return;
			}
	//		if ( $imgInfo[0] > $thumbWidth || $imgInfo[1] > $thumbHeight ||  ){	//原图片的宽或高小于缩略图宽或高,则不生成缩略图
				$thumbArray = self::ThumbSize( array(
					'thumbWidth'  => $thumbWidth,
					'thumbHeight' => $thumbHeight,
					'imgWidth'    => $imgInfo[0],
					'imgHeight'   => $imgInfo[1]
				));
				$return['width']   = $thumbArray['newWidth'];
				$return['height']  = $thumbArray['newHeight'];
				if ( $imgExt == 'gif' ){
					if (function_exists('imagecreatefromgif')){
						if(!($image = @imagecreatefromgif($imgPath))){
							$return['err'] = 1;
							$return['errDes'] = '该文件扩展名虽是gif，但并不是gif格式.';
							return $return;
						}
					}
				} elseif ($imgExt == 'png'){
					if (function_exists('imagecreatefrompng')){
						if(!($image = @imagecreatefrompng($imgPath))){
							$return['err'] = 1;
							$return['errDes'] = '该文件扩展名虽是png，但并不是png格式.';
							return $return;
						}
						imagesavealpha($image,true);
					}
				} elseif ($imgExt == 'jpg' || $imgExt == 'jpeg'){
					if (function_exists('imagecreatefromjpeg')){
						if(!($image = @imagecreatefromjpeg($imgPath))){
							$return['err'] = 1;
							$return['errDes'] = '该文件扩展名虽是jpg，但并不是jpg格式.';
							return $return;
						}
					}
				} elseif ($imgExt == 'webp' && PHP_VERSION >= 5.4){
					if (function_exists('imagecreatefromwebp')){
						if(!($image = @imagecreatefromwebp($imgPath))){
							$return['err'] = 1;
							$return['errDes'] = '该文件扩展名虽是webp，但并不是webp格式.';
							return $return;
						}
					}
				} elseif ($imgExt == 'bmp' && PHP_VERSION >= 7.2){
					if (function_exists('imagecreatefrombmp')){
						if(!($image = @imagecreatefrombmp($imgPath))){
							$return['err'] = 1;
							$return['errDes'] = '该文件扩展名虽是bmp，但并不是bmp格式.';
							return $return;
						}
					}
				} else {
							$return['err'] = 1;
							$return['errDes'] = '不支持'. $imgExt .'式的，仅支持生成GIF/PNG/JPG这3种图片文件的缩略图.';
							return $return;
				}
				if ( $image ){
					if (function_exists('imagecreatetruecolor')){
						$thumb = @imagecreatetruecolor($thumbArray['newWidth'], $thumbArray['newHeight']);
						if ($imgExt == 'png'){
							$color=imagecolorallocate($thumb,255,255,255); 
							imagecolortransparent($thumb,$color); 
							imagefill($thumb,0,0,$color); 
							//imagealphablending($thumb,false);	// 不合并颜色,直接用$img图像颜色替换,包括透明色; 
							//imagesavealpha($thumb,true);		// 不要丢了$thumb图像的透明色; 
						}
						@imagecopyresampled($thumb, $image, 0, 0, 0, 0, $thumbArray['newWidth'], $thumbArray['newHeight'], $imgInfo[0], $imgInfo[1]);
					} else {
						$thumb = @imagecreate($thumbArray['newWidth'], $thumbArray['newHeight']);
						if ($imgExt == 'png'){
							$color=imagecolorallocate($thumb,255,255,255); 
							imagecolortransparent($thumb,$color); 
							imagefill($thumb,0,0,$color); 
							//imagealphablending($thumb,false);	// 不合并颜色,直接用$img图像颜色替换,包括透明色; 
							//imagesavealpha($thumb,true);		// 不要丢了$thumb图像的透明色; 
						}
						@imagecopyresized($thumb, $image, 0, 0, 0, 0, $thumbArray['newWidth'], $thumbArray['newHeight'], $imgInfo[0], $imgInfo[1]);
					}
					if (PHP_VERSION != '4.3.2') {
						self::UnsharpMask($thumb);
					}
					if (in_array($imgExt, array('jpg','jpeg')) && function_exists('imagejpeg')) {
						@imagejpeg( $thumb, $thumbDir . $thumbName );
						@imagedestroy( $thumb );
					} else if ($imgExt == 'png' && function_exists('imagepng'))	{
						@imagepng( $thumb, $thumbDir . $thumbName );
						@imagedestroy( $thumb );
					} else if ($imgExt == 'gif' && function_exists('imagegif'))	{
						@imagegif( $thumb, $thumbDir . $thumbName );
						@imagedestroy( $thumb );
					} else if ($imgExt == 'webp' && function_exists('imagewebp') && PHP_VERSION >= 5.4)	{
						@imagewebp( $thumb, $thumbDir . $thumbName );
						@imagedestroy( $thumb );
					} else if ($imgExt == 'bmp' && function_exists('imagebmp') && PHP_VERSION >= 7.2)	{
						@imagebmp( $thumb, $thumbDir . $thumbName );
						@imagedestroy( $thumb );
					} else {
						$return['err'] = 2;
						$return['errDes'] = '缩略图输出失败.';
						return $return;
					}
					$return['name'] = $thumbName;
					$return['path'] = $thumbDir . $thumbName;
					$return['err'] = 0;
					$return['errDes'] = '缩略图生成成功.';
					return $return;
				} else {
					$return['err'] = 1;
					$return['errDes'] = '缩略图生成失败.';
					return $return;
				}
	/*		} else {
				$return['err'] = 3;
				$return['errDes'] = '原图片的宽或高小于缩略图宽或高.';
				return $return;
			}
		} */
	}





	// 获取远程图片并把它保存到本地
	/*
	$imgUrl 是远程图片的完整URL地址，不能为空。
	$fileDir 可选变量；存放目录路径，如为空即为当前目录
	$fileName 是可选变量: 如果为空，本地文件名将基于时间自动生成
	*/
	public static function Remote($imgUrl,$fileDir='',$fileName=''){  
		if($imgUrl==''){return false;}

		$ext=strrchr($imgUrl,'.'); 
		if (in_array($ext,array('.gif','.jpg','.jpeg','.png','.bmp','.ico'))==true){
			$ext = substr($ext,1);
		}else{
			$img = getimagesize($imgUrl);
			if ($img==false){ return false; }

			switch ($img[2]){
				case 1:
					$ext = 'gif';
					break;

				case 2:
					$ext = 'jpg';
					break;

				case 3:
					$ext = 'png';
					break;

				case 6:
					$ext = 'bmp';
					break;

				default :
					return false;
			}
		}

		if ($fileDir != ''){
			if (substr($fileDir,-1) != '/'){$fileDir .= '/';}
		}

		if($fileName=='') {
	//		if(in_array($ext,array('.gif','.jpg','.jpeg','.bmp','.png'))==false){ return false; }
			$fileName = date('YmdHis') . rand(1000,9999) .'.'. $ext;
		}/* else{
			$fileName .= '.'. $ext;
		} */
		ob_start();
		readfile($imgUrl);
		$img = ob_get_contents();
		ob_end_clean();
		$size = strlen($img);

		$fp2=@fopen($fileDir . $fileName, 'a');
		@fwrite($fp2,$img);
		@fclose($fp2);

		return $fileName;
	} 

	/*
	$img=self::Remote('http://jp.phpip.com/img.php?type=s&format=JPEG&url=oneti.cn','');
	self::Thumb(array(
		'imgPath'		=> ''. $img,
		'thumbPrefix'	=> '',
		'thumbWidth'	=> 200,
		));
	print($img);
	*/



	//计算缩略图的大小
	public static function ThumbSize($IGTSarray) {
		if (intval($IGTSarray['thumbWidth'])==0 && intval($IGTSarray['thumbHeight'])==0){
			$IGTSarray['newWidth']	= 100;
			$IGTSarray['newHeight']	= 100;
		} elseif ( intval($IGTSarray['thumbHeight'])==0 ) {
			$IGTSarray['newWidth']	= $IGTSarray['thumbWidth'];
			$IGTSarray['newHeight']	= ceil( $IGTSarray['imgHeight'] * $IGTSarray['thumbWidth'] / $IGTSarray['imgWidth'] );
		} elseif ( intval($IGTSarray['thumbWidth'])==0 ) {
			$IGTSarray['newWidth']	= ceil( $IGTSarray['imgWidth'] * $IGTSarray['thumbHeight'] / $IGTSarray['imgHeight'] );
			$IGTSarray['newHeight']	= $IGTSarray['thumbHeight'];
		} else {
			$IGTSarray['newWidth']	= $IGTSarray['thumbWidth'];
			$IGTSarray['newHeight']	= $IGTSarray['thumbHeight'];
		}

		return $IGTSarray;
	}




	//滤镜
	public static function UnsharpMask($img, $amount = 100, $radius = .5, $threshold = 3){
		$amount = min($amount, 500);
		$amount = $amount * 0.016;
		if ($amount == 0) return true;

		$radius = min($radius, 50);
		$radius = $radius * 2;
		$threshold = min($threshold, 255);
		$radius = abs(round($radius));
		if ($radius == 0) return true;

		$w = ImageSX($img);
		$h = ImageSY($img);
		$imgCanvas  = ImageCreateTrueColor($w, $h);
		$imgCanvas2 = ImageCreateTrueColor($w, $h);
		$imgBlur    = ImageCreateTrueColor($w, $h);
		$imgBlur2   = ImageCreateTrueColor($w, $h);

		ImageCopy($imgCanvas,  $img, 0, 0, 0, 0, $w, $h);
		ImageCopy($imgCanvas2, $img, 0, 0, 0, 0, $w, $h);

		for ($i = 0; $i < $radius; $i++){
			ImageCopy($imgBlur, $imgCanvas, 0, 0, 1, 1, $w - 1, $h - 1);
			ImageCopyMerge($imgBlur, $imgCanvas, 1, 1, 0, 0, $w, $h, 50);
			ImageCopyMerge($imgBlur, $imgCanvas, 0, 1, 1, 0, $w - 1, $h, 33.33333);
			ImageCopyMerge($imgBlur, $imgCanvas, 1, 0, 0, 1, $w, $h - 1, 25);
			ImageCopyMerge($imgBlur, $imgCanvas, 0, 0, 1, 0, $w - 1, $h, 33.33333);
			ImageCopyMerge($imgBlur, $imgCanvas, 1, 0, 0, 0, $w, $h, 25);
			ImageCopyMerge($imgBlur, $imgCanvas, 0, 0, 0, 1, $w, $h - 1, 20 );
			ImageCopyMerge($imgBlur, $imgCanvas, 0, 1, 0, 0, $w, $h, 16.666667); // dow
			ImageCopyMerge($imgBlur, $imgCanvas, 0, 0, 0, 0, $w, $h, 50);
			ImageCopy($imgCanvas, $imgBlur, 0, 0, 0, 0, $w, $h);
			ImageCopy($imgBlur2, $imgCanvas2, 0, 0, 0, 0, $w, $h);
			ImageCopyMerge($imgBlur2, $imgCanvas2, 0, 0, 0, 0, $w, $h, 50);
			ImageCopyMerge($imgBlur2, $imgCanvas2, 0, 0, 0, 0, $w, $h, 33.33333);
			ImageCopyMerge($imgBlur2, $imgCanvas2, 0, 0, 0, 0, $w, $h, 25);
			ImageCopyMerge($imgBlur2, $imgCanvas2, 0, 0, 0, 0, $w, $h, 33.33333);
			ImageCopyMerge($imgBlur2, $imgCanvas2, 0, 0, 0, 0, $w, $h, 25);
			ImageCopyMerge($imgBlur2, $imgCanvas2, 0, 0, 0, 0, $w, $h, 20 );
			ImageCopyMerge($imgBlur2, $imgCanvas2, 0, 0, 0, 0, $w, $h, 16.666667);
			ImageCopyMerge($imgBlur2, $imgCanvas2, 0, 0, 0, 0, $w, $h, 50);
			ImageCopy($imgCanvas2, $imgBlur2, 0, 0, 0, 0, $w, $h);
		}

		for ($x = 0; $x < $w; $x++){
			for ($y = 0; $y < $h; $y++){
				$rgbOrig = ImageColorAt($imgCanvas2, $x, $y);
				$rOrig = (($rgbOrig >> 16) & 0xFF);
				$gOrig = (($rgbOrig >>  8) & 0xFF);
				$bOrig =  ($rgbOrig        & 0xFF);
				$rgbBlur = ImageColorAt($imgCanvas, $x, $y);
				$rBlur = (($rgbBlur >> 16) & 0xFF);
				$gBlur = (($rgbBlur >>  8) & 0xFF);
				$bBlur =  ($rgbBlur        & 0xFF);
				$rNew = (abs($rOrig - $rBlur) >= $threshold) ? max(0, min(255, ($amount * ($rOrig - $rBlur)) + $rOrig)) : $rOrig;
				$gNew = (abs($gOrig - $gBlur) >= $threshold) ? max(0, min(255, ($amount * ($gOrig - $gBlur)) + $gOrig)) : $gOrig;
				$bNew = (abs($bOrig - $bBlur) >= $threshold) ? max(0, min(255, ($amount * ($bOrig - $bBlur)) + $bOrig)) : $bOrig;
				if (($rOrig != $rNew) || ($gOrig != $gNew) || ($bOrig != $bNew)){
					$pixCol = ImageColorAllocate($img, $rNew, $gNew, $bNew);
					ImageSetPixel($img, $x, $y, $pixCol);
				}
			}
		}

		ImageDestroy($imgCanvas);
		ImageDestroy($imgCanvas2);
		ImageDestroy($imgBlur);
		ImageDestroy($imgBlur2);

		return true;
	}

	// 解决 imagecopy可以保留png自身透明度，但不能附加自己设置的透明度问题
	public static function imagecopymerge_alpha($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct){
        $opacity=$pct;
        // getting the watermark width
        $w = imagesx($src_im);
        // getting the watermark height
        $h = imagesy($src_im);
        
        // creating a cut resource
        $cut = imagecreatetruecolor($src_w, $src_h);
        // copying that section of the background to the cut
        imagecopy($cut, $dst_im, 0, 0, $dst_x, $dst_y, $src_w, $src_h);
        // inverting the opacity
        // $opacity = 100 - $opacity;
        
        // placing the watermark now
        imagecopy($cut, $src_im, 0, 0, $src_x, $src_y, $src_w, $src_h);
        imagecopymerge($dst_im, $cut, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $opacity);
    }



	// 二维码图片加入到海报
	/*
	$imageDefault = array(
		'left'=>435,
		'top'=>1268,
		'right'=>0,
		'bottom'=>0,
		'width'=>245,
		'height'=>245,
		'opacity'=>100
		);
	$textDefault = array(
		'text'=>'',
		'left'=>0,
		'top'=>0,
		'fontSize'=>32,       //字号
		'fontColor'=>'255,255,255', //字体颜色
		'angle'=>0,
		);

	$background = 'Public/images/bg.png';//海报最底层得背景
	$config['image'][]['url'] = 'Uploads/images/qrcode.jpg';
	$filename = 'Uploads/images/qrcode_bg.jpg';
	QrcodeImg($imageDefault,$textDefault,$background,$filename,$config);
	echo "<center><img src='".$filename."' width='400'/></center>";
	*/
	public static function QrcodeImg($imageDefault,$textDefault,$background,$filename="",$config=array()){
		// 如果要看报什么错，可以先注释调这个header
		if(empty($filename)) header("content-type: image/png");
		// 背景方法
		$backgroundInfo = getimagesize($background);
		$ext = image_type_to_extension($backgroundInfo[2], false);
		if (empty($ext)){ $ext = pathinfo($background,PATHINFO_EXTENSION); }
		if (strtolower($ext) == 'jpg'){ $ext = 'jpeg'; }
		$backgroundFun = 'imagecreatefrom'.$ext;
		$background = $backgroundFun($background);
		$backgroundWidth = imagesx($background);  //背景宽度
		$backgroundHeight = imagesy($background);  //背景高度
		$imageRes = imageCreatetruecolor($backgroundWidth,$backgroundHeight);
		$color = imagecolorallocate($imageRes, 0, 0, 0);
		imagefill($imageRes, 0, 0, $color);
		imagecopyresampled($imageRes,$background,0,0,0,0,imagesx($background),imagesy($background),imagesx($background),imagesy($background));
		// 处理了图片
		if(!empty($config['image'])){
			foreach ($config['image'] as $key => $val) {
				$val = array_merge($imageDefault,$val);
				$info = getimagesize($val['url']);
				$ext2 = image_type_to_extension($info[2], false);
				if (empty($ext2)){ $ext2 = pathinfo($val['url'],PATHINFO_EXTENSION); }
				if (strtolower($ext2) == 'jpg'){ $ext2 = 'jpeg'; }
				$function = 'imagecreatefrom'.$ext2;
				if(isset($val['stream']) && $val['stream']){   
					// 如果传的是字符串图像流
					$info = getimagesizefromstring($val['url']);
					$function = 'imagecreatefromstring';
				}
				$res = $function($val['url']);
				$resWidth = $info[0];
				$resHeight = $info[1];
				// 建立画板 ，缩放图片至指定尺寸
				$canvas=imagecreatetruecolor($val['width'], $val['height']);
				imagefill($canvas, 0, 0, $color);
				// 关键函数，参数（目标资源，源，目标资源的开始坐标x,y, 源资源的开始坐标x,y,目标资源的宽高w,h,源资源的宽高w,h）
				imagecopyresampled($canvas, $res, 0, 0, 0, 0, $val['width'], $val['height'],$resWidth,$resHeight);
				$val['left'] = $val['left']<0?$backgroundWidth- abs($val['left']) - $val['width']:$val['left'];
				$val['top'] = $val['top']<0?$backgroundHeight- abs($val['top']) - $val['height']:$val['top'];
				// 放置图像
				imagecopymerge($imageRes,$canvas, $val['left'],$val['top'],$val['right'],$val['bottom'],$val['width'],$val['height'],$val['opacity']);// 左，上，右，下，宽度，高度，透明度
			}
		}
		// 处理文字
		if(!empty($config['text'])){
			foreach ($config['text'] as $key => $val) {
				$val = array_merge($textDefault,$val);
				list($R,$G,$B) = explode(',', $val['fontColor']);
				$fontColor = imagecolorallocate($imageRes, $R, $G, $B);
				$val['left'] = $val['left']<0?$backgroundWidth- abs($val['left']):$val['left'];
				$val['top'] = $val['top']<0?$backgroundHeight- abs($val['top']):$val['top'];
				imagettftext($imageRes,$val['fontSize'],$val['angle'],$val['left'],$val['top'],$fontColor,$val['fontPath'],$val['text']);
			}
		}
		// 生成图片
		if(!empty($filename)){
			$res = imagejpeg ($imageRes,$filename,90); 
			// 保存到本地
			imagedestroy($imageRes);
		}else{
			imagejpeg ($imageRes);     
			// 在浏览器上显示
			imagedestroy($imageRes);
		}
	}

}
?>
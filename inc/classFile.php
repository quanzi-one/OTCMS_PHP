<?php

if(! defined('OT_ROOT')) {
	exit('Access Denied');
}


class File{
	/* ***** 目录 START ***** */

	// 创建目录；如果存在,不创建
	public static function CreateDir($dir){
		if (! is_dir($dir)){
			if (@mkdir($dir, 0755)){
				//创建index.html,目的防止列目录漏洞
				//fclose(fopen($dir.'/index.html', 'w'));
				@chmod($dir, 0755);
				return true;
			}else{
				return false;
			}
		}else{
			return true;
		}
	}


	// 目录的实际大小
	public static function DirSize($dir,$extArr='',$judIncDir=true){
		$judEmpty = empty($extArr);
		if (! is_array($extArr)){ $extArr = array($extArr); }

		$dh = @opendir($dir);
		$size = 0;
		while ($file = @readdir($dh)){
			if ($file != '.' && $file != '..'){
				$path = $dir .'/'. $file;
				if (@is_dir($path) && $judIncDir){
					$size += self::DirSize($path);
				} else {
					if ($judEmpty || in_array(self::GetExt($path),$extArr)){
						$size += @filesize($path);
					}
				}
			}
		}
		@closedir($dh);
		return $size;
	}


	// 目录个数
	public static function DirCount($dir){
		$dh = @opendir($dir);
		$count = 0;
		while ($file = @readdir($dh)){
			if ($file != '.' && $file != '..'){
				$path = $dir .'/'. $file;
				if (@is_dir($path)){
					$count++;
				}
			}
		}
		@closedir($dh);
		return $count;
	}


	// 复制目录以及目录下文件
	public static function CopyDir($source, $destination){
		$result = true;

		if(! is_dir($source)){
			trigger_error('Invalid Parameter', E_USER_ERROR);
		}
		if(! is_dir($destination)){
			if(! mkdir($destination, 0755)){
				trigger_error('Invalid Parameter', E_USER_ERROR);
			}
		}

		$handle = opendir($source);
		while(($file = readdir($handle)) !== false){
			if($file != '.' && $file != '..'){
				$src = $source . DIRECTORY_SEPARATOR . $file;
				$dtn = $destination . DIRECTORY_SEPARATOR . $file;
				if(is_dir($src)){
					File::CopyDir($src, $dtn);
				}else{
					if(! copy($src, $dtn)){
						$result = false;
						break;
					}
				}
			}
		}
		closedir($handle);

		return $result;
	}


	// 删除目录以及目录下文件
	public static function DelDir($dirName, $isDelDir=true){
		$result = false;

		if(! is_dir($dirName)){
			trigger_error('Invalid Parameter', E_USER_ERROR);
		}

		$handle = opendir($dirName);
		while(($file = readdir($handle)) !== false){
			if($file != '.' && $file != '..'){
				$dir = $dirName . DIRECTORY_SEPARATOR . $file;
				is_dir($dir) ? self::DelDir($dir, $isDelDir) : unlink($dir);
			}
		}
		closedir($handle);

		if ($isDelDir){
			$result = rmdir($dirName) ? true : false;
		}else{
			$result = true;
		}

		return $result;
	}

	// 修改目录名
	public static function RevName($source, $destination){
		if ($source == $destination){ return true; }
		if (@rename($source,$destination)){
			return true;
		}else{
			return false;
		}
	}

	/*
	// 统计目录下的文件数量
	public static function Count($dir){
		$handle = opendir($dir);
		$i = 0;
		while($file=(readdir($handle)) !== false){
			if($file!=='.' || $file!='..'){
				$i++ ;
			}
		}
		closedir($handle);
		return $i;
	}
	*/

	// 统计目录下文件数
	public static function Count($dirPath,$extArr=''){
		$fileArr = @scandir($dirPath);
		if ($fileArr){
			$judEmpty = empty($extArr);
			if (! is_array($extArr)){ $extArr = array($extArr); }

			if ($judEmpty){
				$count = count($fileArr)-2;	// 所有文件总数除./和../ 
			}else{
				$count = 0;
				foreach ($extArr as $ext){
					$count += count(preg_grep("/\.". $ext ."$/", $fileArr));
				}
			}
		}else{
			$count = -1;
		}

		return $count;
	}


	// 获取路径下的目录列表
	public static function GetDirList($dirPath){
		$retArr = array();
		if ($handle = opendir($dirPath)) {
			if (substr($dirPath,-1) != '/'){ $dirPath .= '/'; }
			while (($file = readdir($handle)) !== false) {
				if ($file != '.' && $file != '..' && is_dir($dirPath . $file)) {
					$retArr[] = $file;
				}
			}
		}
		closedir($handle);
		//print_r($retArr);die();
		return $retArr;
	}

	// 获取路径下的文件列表
	public static function GetFileList($dirPath,$extArr=''){
		$judEmpty = empty($extArr);
		if (! is_array($extArr)){ $extArr = array($extArr); }

		$retArr = array();
		if ($handle = opendir($dirPath)) {
			if (substr($dirPath,-1) != '/'){ $dirPath .= '/'; }
			while (($file = readdir($handle)) !== false) {
				if ($file != '.' && $file != '..' && (! is_dir($dirPath . $file)) && ($judEmpty || in_array(self::GetExt($file),$extArr))) {
					$retArr[] = $file;
				}
			}
		}
		closedir($handle);
		//print_r($retArr);die();
		return $retArr;
	}

	// 获取路径下的目录和文件列表
	public static function GetAllFileList($dirPath,$rootDir=''){
		$retArr = array();
		if ($handle = opendir($dirPath)) {
			if (substr($dirPath,-1) != '/'){ $dirPath .= '/'; }
			$dirArr = array();
			$dirListArr = array();
			$fileListArr = array();
			while (($file = readdir($handle)) !== false) {
				if ($file != '.' && $file != '..') {
					if (is_dir($dirPath . $file)){
						$dirArr[] = $file;
					}elseif ($file != 'thumbs.db'){
						$fileListArr[] = $rootDir . $file;
					}
				}
			}
			foreach ($dirArr as $dir){
				$dirListArr = array_merge($dirListArr,self::GetAllFileList($dirPath . $dir .'/', $rootDir . $dir .'/'));
			}
			$retArr = array_merge($dirListArr,$fileListArr);
		}
		closedir($handle);
		// print_r($retArr);die();
		// mb_convert_encoding($fileName,'UTF-8','GBK');
		return $retArr;
	}

	// 获取创建时间
	public static function GetCreateTime($filePath, $defVal=''){
		if (! file_exists($filePath)){
			return $defVal;
		}
		$a = filectime($filePath);
		if ($a === false){
			return $defVal;
		}else{
			return date('Y-m-d H:i:s',$a);
		}
	}

	// 获取修改时间
	public static function GetRevTime($filePath, $defVal=''){
		if (! file_exists($filePath)){
			return $defVal;
		}
		$a = filemtime($filePath);
		if ($a === false){
			return $defVal;
		}else{
			return date('Y-m-d H:i:s',$a);
		}
	}
	/* ***** 目录 END ***** */



	/* ***** 文件 START ***** */

	// 写文件
	public static function Write($source, $data, $isCreateDir=false, &$errStr=''){
		$judRes = true;
		$pathDir = str_replace("\\",'/',$source);
		if ($isCreateDir){
			$siteDir	= str_replace('//','/',str_replace("\\",'/',$_SERVER['DOCUMENT_ROOT']));
				if (substr($siteDir,-1) != '/'){ $siteDir .= '/'; }
			$siteArr	= explode('/',$siteDir);
			$siteCount	= count($siteArr)-1;
			$pathArr	= explode('/',$pathDir);
			$pathCount	= count($pathArr)-1;
			$pathTemp	= $siteDir;
			// echo($_SERVER['DOCUMENT_ROOT'] .'|'. $siteDir .'|'. $pathDir .'|'. $siteCount .'|'. $pathCount);
			for ($i=$siteCount; $i<$pathCount; $i++){
				if ($i==0){
					$pathTemp = $pathArr[0] .'/';
				}else{
					$pathTemp .= $pathArr[$i] .'/';
				}
				// echo('|'. $pathTemp .'|');
				
				if ($i >= 1){
					if (! file_exists($pathTemp)){
						self::CreateDir($pathTemp);
						if (! file_exists($pathTemp)){
							$errStr = '创建目录失败'. $pathTemp;
							return false;
						}
					}
				}
			}
		}
		try{
			if($fp = @fopen($pathDir, 'wb')) {
				@fwrite($fp, $data);
				@fclose($fp);
				@chmod($pathDir, 0755);
			} else {
				$judRes = false;
				$errStr = '无法写入'. $pathDir;
				//echo('不能写入该缓存文件，请检查该目录 ./cache/js/ .');
				//exit;
			}
		}catch (Exception $e){
			$judRes = false;
			$errStr = '错误：'. $e;
		}
		return $judRes;
	}


	// 采用锁定方式写文件
	public static function CacheWrite($pageurl,$pagedata){
		if(! $fso=fopen($pageurl,'w')){
			//$this->warns('无法打开缓存文件.');
			return false;
		}
		if(!flock($fso,LOCK_EX)){		//LOCK_NB,排它型锁定
			//$this->warns('无法锁定缓存文件.');
			return false;
		}
		if(!fwrite($fso,$pagedata)){	//写入字节流,serialize写入其他格式
			//$this->warns('无法写入缓存文件.');
			return false;
		}
		flock($fso,LOCK_UN);	//释放锁定
		fclose($fso);
		return true;
	}


	// 复制文件
	public static function Copy($source, $filePath){
		if (file_exists($source) == false) {
			return false;
		}
		$result=@copy($source, $filePath);
		@chmod($filePath, 0644);
		return $result;
	}


	// 删除文件
	public static function Del($filePath){
		if (! empty($filePath)){
			@chmod ($filePath, 0755);
			if (@unlink($filePath)){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}


	// 删除目录下所有或者指定后缀的文件
	public static function MoreDel($path, $extArr=''){
		//给定的目录不是一个文件夹
		if(! is_dir($path)){
			return 0;
		}

		$delNum = 0;
		$judEmpty = empty($extArr);
		if (! is_array($extArr)){ $extArr = array($extArr); }
		$fh = opendir($path);
		while(($row = readdir($fh)) !== false){
			//过滤掉虚拟目录
			if($row == '.' || $row == '..'){ continue; }
			
			if(! is_dir($path .'/'. $row)){
				if ($judEmpty){
					$extJud = true;
				}elseif ( in_array(self::GetExt($row),$extArr) ){
					$extJud = true;
				}else{
					$extJud = false;
				}
				if ($extJud){
					unlink($path .'/'. $row);
					$delNum ++;
				}
			}
			
		}
		// 关闭目录句柄，否则出Permission denied
		closedir($fh);

		return $delNum;
	}


	// 读取文件
	public static function Read($filePath,$mode=''){
		if (! file_exists($filePath)){
			if ($mode == 'det'){
				return '[noFile]';
			}else{
				return '';
			}
		}
		$fp = fopen($filePath,'r'); 
		$fsize = filesize($filePath);
			if ($fsize == 0){
				if ($mode == 'det'){
					return '[empty]';
				}else{
					return '';
				}
			}
		$content = fread($fp,$fsize);//读文件 
		fclose($fp);
			if (strlen($content) == 0){
				if ($mode == 'det'){
					return '[empty]';
				}else{
					return '';
				}
			}
		return $content;
	}


	// 读取文件一行
	public static function ReadOne($filePath,$mode=''){
		if (! file_exists($filePath)){
			if ($mode == 'det'){
				return '[noFile]';
			}else{
				return '';
			}
		}
		$fp= fopen($filePath,'r');
		if (! feof($fp)){
			return fgets($fp);
		}else{
			if ($mode == 'det'){
				return '[empty]';
			}
		}
		fclose($fp);
	}


	//获取文件属性
	public static function Attri($filePath){
		if (file_exists($filePath) == false) {
			return false;
		}

		$result=array();
		$result['size']=filesize($filePath);

		return $result;
	}


	// 字节级转换成相应级单位
	public static function SizeUnit($fileSize, $dzStr=' '){
		if (! is_numeric($fileSize)){ return $fileSize; }

		if ($fileSize >= 1073741824){
			$fileSize = round($fileSize / 1073741824 * 100) / 100 . $dzStr .'GB';

		} elseif ($fileSize >= 1048576){
			$fileSize = round($fileSize / 1048576 * 100) / 100 . $dzStr .'MB';

		} elseif ($fileSize >= 1024){
			$fileSize = round($fileSize / 1024 * 100) / 100 . $dzStr .'KB';

		} else {
			$fileSize = $fileSize . $dzStr .'字节';
		}

		return $fileSize;
	}


	// 获得文件扩展名
	public static function GetExt($fileName){
		/*
		如：/testweb/test.txt
		PATHINFO_DIRNAME - 只返回 dirname	如 /testweb
		PATHINFO_BASENAME - 只返回 basename	如 test.txt
		PATHINFO_EXTENSION - 只返回 extension	如 txt
		*/
		return pathinfo($fileName,PATHINFO_EXTENSION);
	}


	// 下载
	// $source:上传到服务器上的文件名；$newName:真实的文件名
	public static function Download($source, $fileName){
		$file = fopen($source, 'r'); // 打开文件
		$ua = $_SERVER['HTTP_USER_AGENT'];

		header('Content-Encoding: none');
		header('Content-type: application/octet-stream');
		header('Accept-Ranges: bytes');
		header('Accept-Length: '. filesize($source));
		if (preg_match('/MSIE/', $ua)) {
			$fileName = rawurlencode($fileName);
			header('Content-Disposition: attachment; filename="'. $fileName .'"');
		} else if (preg_match('/Firefox/', $ua)) {
			header('Content-Disposition: attachment; filename*="utf8\'\''. $fileName .'"');
		} else {
			header('Content-Disposition: attachment; filename="'. $fileName .'"');
		}
		header('Pragma: no-cache');
		header('Expires: 0');

		$download= fread($file, filesize($source)); // 输出文件内容
		echo $download;
		fclose($file);
	}


	public static function Download2($filePath, $fileName, $charset='UTF-8', $mimeType='application/octet-stream'){  
		// 文件名乱码问题
		$ua = $_SERVER['HTTP_USER_AGENT'];
		if (preg_match('/MSIE/', $ua)) {
			$fileName = rawurlencode($fileName);
			$attHeader = 'Content-Disposition: attachment; filename="'. $fileName .'"; charset='. $charset;
		}else if (preg_match('/Firefox/', $ua)) {
			$attHeader = 'Content-Disposition: attachment; filename*="utf8\'\''. $fileName .'"' ;
		}else{
			$attHeader = 'Content-Disposition: attachment; filename="'. $fileName .'"; charset='. $charset;
		}

		$filesize = filesize($filePath);

		//header('Pragma: public'); header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Content-Type: application/force-download');
		header('Content-Type: '. $mimeType);
		header($attHeader);
		header('Pragma: cache');
		header('Cache-Control: public, must-revalidate, max-age=0');
		header('Content-Length: '. $filesize);
		readfile($filePath);
		exit;
	}

	/* ***** 文件 END ***** */



	/* ***** 文件权限 START ***** */

	// 是否可读
	public static function IsRead($fileName,$mode=''){
		$jud = is_readable($fileName);
		if ($mode=='cn'){
			if ($jud){
				$jud='<span style="color:green;">可读</span>';
			}else{
				if (file_exists($fileName)){
					$jud='<span style="color:red;">不可读</span>';
				}else{
					$jud='<span style="color:red;">不存在</span>';
				}
			}
		}
		return $jud;
	}

	// 是否可写
	public static function IsWrite($fileName,$mode=''){
		$jud = is_writable($fileName);
		if ($mode=='cn'){
			if ($jud){
				$jud='<span style="color:green;">可写</span>';
			}else{
				if (file_exists($fileName)){
					$jud='<span style="color:red;">不可写</span>';
				}else{
					$jud='<span style="color:red;">不存在</span>';
				}
			}
		}
		return $jud;
	}

	// 是否可改名
	public static function IsRev($fileName,$mode=''){
		if (substr($fileName,-1)=='/'){ $fileName = substr($fileName,0,-1); }
		if (! file_exists($fileName)){
			if ($mode=='cn'){
				return '<span style="color:red;">不存在</span>';
			}else{
				return $jud;
			}
		}
		try{
			$jud = @rename($fileName,$fileName .'Tmp');
			if ($jud){ @rename($fileName .'Tmp',$fileName); }
		}
		catch (Exception $e){
			$jud = false;
		}
		if ($mode=='cn'){
			if ($jud){
				$jud='<span style="color:green;">可改</span>';
			}else{
				$jud='<span style="color:red;">不可改</span>';
			}
		}
		return $jud;
	}

	// 获取文件权限（字符型，如-rw-rw-rw-）
	public static function LimitChar($fileName){
		$perms = fileperms($fileName);
		if (($perms & 0xC000) == 0xC000) { $info = 's'; }
		elseif (($perms & 0xA000) == 0xA000) { $info = 'l'; }
		elseif (($perms & 0x8000) == 0x8000) { $info = '-'; }
		elseif (($perms & 0x6000) == 0x6000) { $info = 'b'; }
		elseif (($perms & 0x4000) == 0x4000) { $info = 'd'; }
		elseif (($perms & 0x2000) == 0x2000) { $info = 'c'; }
		elseif (($perms & 0x1000) == 0x1000) { $info = 'p'; }
		else { $info = 'u'; }

		$info .= (($perms & 0x0100) ? 'r' : '-');
		$info .= (($perms & 0x0080) ? 'w' : '-');
		$info .= (($perms & 0x0040) ? (($perms & 0x0800) ? 's' : 'x' ) : (($perms & 0x0800) ? 'S' : '-'));
		$info .= (($perms & 0x0020) ? 'r' : '-');
		$info .= (($perms & 0x0010) ? 'w' : '-');
		$info .= (($perms & 0x0008) ? (($perms & 0x0400) ? 's' : 'x' ) : (($perms & 0x0400) ? 'S' : '-'));
		$info .= (($perms & 0x0004) ? 'r' : '-');
		$info .= (($perms & 0x0002) ? 'w' : '-');
		$info .= (($perms & 0x0001) ? (($perms & 0x0200) ? 't' : 'x' ) : (($perms & 0x0200) ? 'T' : '-'));

		return $info;
	}

	// 获取文件权限（字符型，如-rw-rw-rw-）
	public static function LimitNum($fileName){
		return substr(sprintf("%o",fileperms($fileName)),-3);
	}

	// 检查目录下是否可创建目录
	public static function IsCreateDir($dir){
		if (substr($dir,-1) != '/'){ $dir .= '/'; }
		$dir .= 'OTCMS_test_v1.00';
		$txtPath = $dir .'/text.txt';
		if (@mkdir($dir, 0755)){
			fclose(fopen($txtPath, 'w'));
			@chmod($dir, 0755);
			self::DelDir($dir);
			return true;
		}else{
			return false;
		}
	}

	// 是否存在
	public static function IsExists($fileName,$mode=''){
		$jud = file_exists($fileName);
		if ($mode=='cn'){
			if ($jud){
				$jud='<span style="color:green;">存在</span>';
			}else{
				$jud='<span style="color:red;">不存在</span>';
			}
		}
		return $jud;
	}

	// 判断远程文件是否存在
	public static function IsRemoteExists($fileUrl){
		if (extension_loaded('curl')){
			$curl = curl_init($fileUrl); // 不取回数据
			curl_setopt($curl, CURLOPT_NOBODY, true);
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET'); // 发送请求
			$result = curl_exec($curl);
			$found = false; // 如果请求没有发送失败
			if ($result !== false) {
				// 再检查http响应码是否为200
				$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
				if ($statusCode == 200) {
					$found = true;
				}
			}
			curl_close($curl);

			return $found;
		}else{
			// 检测输入
			$fileUrl = trim($fileUrl);
			if (empty($fileUrl)) { return false; }
			$url_arr = parse_url($fileUrl);
			if (!is_array($url_arr) || empty($url_arr)){return false; }

			// 获取请求数据
			$host = $url_arr['host'];
			$path = $url_arr['path'] ."?".$url_arr['query'];
			$port = isset($url_arr['port']) ?$url_arr['port'] : "80";

			// 连接服务器
			$fp = fsockopen($host, $port, $err_no, $err_str,30);
			if (!$fp){ return false; }

			// 构造请求协议
			$request_str = "GET ".$path."HTTP/1.1";
			$request_str .= "Host:".$host."";
			$request_str .= "Connection:Close";

			// 发送请求
			fwrite($fp,$request_str);
			$first_header = fgets($fp, 1024);
			fclose($fp);

			//判断文件是否存在
			if (trim($first_header) == ""){ return false;}
			if (!preg_match("/200/", $first_header)){
				return false;
			}

			return true;
		}
	}


	// 清空File缓存
	// 受影响函数stat(),lstat(),file_exists(),is_writable(),is_readable(),is_executable(),is_file(),is_dir(),is_link(),filectime(),fileatime(),filemtime(),fileinode(),filegroup(),fileowner(),filesize(),filetype(),fileperms()
	public static function ClearCache($fileName){
		clearstatcache();
	}

	/* ***** 文件权限 END ***** */

	
	
	/* ***** 上传系列 START ***** */

	// 扩展上传文件参数
	//$fileArray:载入$_FILES[]
	public static function AddUploadPara($fileArray=array()){
		$fileArray['name']=strtolower($fileArray['name']);
		//文件扩展名
		$fileArray['ext']=self::GetExt($fileArray['name']);
		//生成新文件名
		srand();
		$fileArray['newName']=date('YmdHis') . rand(1000,9999) .'.'. $fileArray['ext'];
	//	$fileArray['newName']=md5(uniqid(microtime())) .'.'. $fileArray['ext'];
		return $fileArray;
	}


	// 上传文件
	public static function Upload($source, $target){
		// 如果一种函数上传失败，还可以用其他函数上传
		if (function_exists('move_uploaded_file') && @move_uploaded_file($source, $target)){
			@chmod($target, 0644);
			return $target;
		} elseif (@copy($source, $target)){
			@chmod($target, 0644);
			return $target;
		} elseif (@is_readable($source)){
			if ($fp = @fopen($source,'rb')){
				@flock($fp,2);
				$filedata = @fread($fp,@filesize($source));
				@fclose($fp);
			}
			if ($fp = @fopen($target, 'wb')){
				@flock($fp, 2);
				@fwrite($fp, $filedata);
				@fclose($fp);
				@chmod ($target, 0644);
				return $target;
			} else {
				return false;
			}
		}
	}


	// 判断文件是否是通过 HTTP POST 上传的
	public static function IsPostUpLoad($file){
		return function_exists('is_uploaded_file') && (is_uploaded_file($file) || is_uploaded_file(str_replace('\\\\', '\\', $file)));
	}


	// 判断上传文件类型
	public static function IsUpLoadType($fileExt,$fileType,$jud=true){
		if (empty($fileType)){
			if ($jud){
				return false;
			} else {
				die('<script language="javascript">alert("允许上传类型参数不能为空");history.back(-1);</script>');
			}
		} elseif (is_string($fileType)){
			switch ($fileType){
				case 'imageCommon':
					$fileTypeArr = array('gif','jpg','jpeg','png');
					break;
				case 'image':
					$fileTypeArr = array('bmp','gif','jpg','jpeg','png','ico','webp');
					break;
				case 'imageSwf':
					$fileTypeArr = array('bmp','gif','jpg','jpeg','png','ico','webp','swf');
					break;
				case 'html':
					$fileTypeArr = array('html','htm','txt');
					break;
				case 'office':
					$fileTypeArr = array('txt','doc','xls','ppt','docx','xlsx','pptx');
					break;
				case 'common':
					$fileTypeArr = array('bmp','jpg','jpeg','gif','png','ico','webp','tif','tiff','swf','txt','doc','xls','ppt','docx','xlsx','pptx','pdf','psd','rar','zip','avi','mp4','mpeg','mpg','ra','rm','rmvb','mov','qt','asf','wmv','iso','bin','img','mp3','wma','wav','mod','cd','md','aac','mid','ogg','m4a','apk','ipa','torrent');
					break;
				case 'commonNoSwf':
					$fileTypeArr = array('bmp','jpg','jpeg','gif','png','ico','webp','tif','tiff','txt','doc','xls','ppt','docx','xlsx','pptx','pdf','psd','rar','zip','avi','mp4','mpeg','mpg','ra','rm','rmvb','mov','qt','asf','wmv','iso','bin','img','mp3','wma','wav','mod','cd','md','aac','mid','ogg','m4a','apk','ipa','torrent');
					break;
				case 'noUpload':
					$fileTypeArr = array('asp','php','htm','html','exe','asa','cdx','cer','aspx');
					break;
				default :
					if ($jud){
						return false;
					} else {
						die('允许上传类型字符串参数值不存在（'. $fileType .'），请检查File_IsUpLoadType函数的参数。');
					}
					break;
			}
		} elseif (! is_array($fileTypeArr)){
			if ($jud){
				return false;
			} else {
				die('<script language="javascript">alert("参数值不正确");history.back(-1);</script>');
			}
		}

		if ($fileType=='noUpload'){
			if (in_array($fileExt,$fileTypeArr)){
				if ($jud){
					return false;
				} else {
					die('<script language="javascript">alert("不允许上传该文件类型。");history.back(-1);</script>');
				}
			} else {
				if ($jud){ return true; }
			}
		}else{
			if (! in_array($fileExt,$fileTypeArr)){
				if ($jud){
					return false;
				} else {
					die('<script language="javascript">alert("不允许上传该文件类型。");history.back(-1);</script>');
				}
			} else {
				if ($jud){ return true; }
			}
		}
	}

	/* ***** 上传系列 END ***** */

}

?>
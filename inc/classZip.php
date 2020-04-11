<?php

if(! defined('OT_ROOT')) {
	exit('Access Denied');
}



// ZIP压缩、解压
class Zip{
	// 压缩
	/*	如果返回值等于下面的属性，表示对应的错误 或者 返回TRUE 
		ZIPARCHIVE::OVERWRITE	总是创建一个新的文件，如果指定的zip文件存在，则会覆盖掉 
		ZIPARCHIVE::CREATE		如果指定的zip文件不存在，则新建一个 
		ZIPARCHIVE::EXCL		如果指定的zip文件存在，则会报错    
		ZIPARCHIVE::CHECKCONS
		$fileArr = array(
			array('type'=>'file','path'=>'index.php','name'=>'newname.php'),
			array('type'=>'dir','path'=>'news/','name'=>''),
			);
	*/
	public static function Yasuo($fileArr, $toPath, $addiArr=array()){
		ini_set('memory_limit','3072M');
		@ini_set('max_execution_time', 0);
		@set_time_limit(0); // 无时间限制

		$zip = new ZipArchive;
		// linux下使用 ZipArchive::OVERWRITE 不会自动创建ZIP包，只能用 ZipArchive::CREATE
		$res = $zip->open($toPath, ZipArchive::CREATE);
		if ($res === true) {
			if (! empty($addiArr['show'])){
				@ob_end_clean();
				ob_implicit_flush(1);
				ob_start();
				echo('<h2>正在压缩中......</h2><div id="yasuoBox"></div>');

				$judShow = true;
			}else{
				$judShow = false;
			}
			if (! empty($addiArr['pwd'])){
				// 设置压缩包的密码
				if (PHP_VERSION >= 5.6){
					$zip->setPassword($addiArr['pwd']);
					if ($judShow){ echo('<h3>解压密码：'. $addiArr['pwd'] .'</h3>'); }
				}
			}
			if (! empty($addiArr['note'])){
				// 设置压缩包的注释
				if ($judShow){ echo('<h3>压缩包注释：'. $addiArr['note'] .'</h3>'); }
				if (strpos(PHP_OS, 'WIN') !== false){
					$addiArr['note'] = iconv('UTF-8','GB2312//IGNORE',$addiArr['note']);
				}
				$zip->setArchiveComment($addiArr['note']);
			}

			$fileNum = 0;
			$fileTotal = count($fileArr);
			
			foreach($fileArr as $val){
				$fileNum ++;
				if ($val['type'] == 'dir'){
					if (file_exists($val['path'])){
						if (! empty($val['name'])){
							self::AddDir($zip, $val['path'], $val['name']);		// 添加目录，并改目录名
						}else{
							self::AddDir($zip, $val['path']);					// 添加目录
						}
					}else{
						if ($judShow){ echo('<h3>不存在目录，不加入zip：'. $val['path'] .'</h3>'); }
					}
				}else{
					if (file_exists($val['path'])){
						if (! empty($val['name'])){
							$zip->addFile($val['path'], $val['name']);   // 向压缩包中添加文件，并改名
						}else{
							$zip->addFile($val['path']);				 // 向压缩包中添加文件
						}
					}else{
						if ($judShow){ echo('<h3>不存在文件，不加入zip：'. $val['path'] .'</h3>'); }
					}
				}
				if ($judShow){
					echo('
					<script language="javascript" type="text/javascript">
					document.getElementById("yasuoBox").innerHTML = "<div>'. $fileNum .'/'. $fileTotal .'正在压缩文件 网站根目录/'. iconv('GB2312','UTF-8//IGNORE',str_replace('\\','/',str_replace(OT_ROOT, '', $val['path']))) .'</div>";
					</script>
					');
					ob_flush();
					flush();
				}
			}
			if ($judShow){
				echo('<h2>正在生成ZIP压缩文件中......</h2>');
				ob_flush();
				flush();
			}
			$zip->close();
			return array('res'=>true,'note'=>'');
		}else{
			switch($res){
				case ZipArchive::ER_EXISTS: 
					$errStr = '文件已经存在.';
					break;

				case ZipArchive::ER_INCONS: 
					$errStr = '压缩文件不一致.';
					break;
					
				case ZipArchive::ER_MEMORY: 
					$errStr = 'Malloc失败.';
					break;
					
				case ZipArchive::ER_NOENT: 
					$errStr = '不存在该文件.';
					break;
					
				case ZipArchive::ER_NOZIP: 
					$errStr = '不是个zip文件.';
					break;
					
				case ZipArchive::ER_OPEN: 
					$errStr = '不能打开该文件.';
					break;
					
				case ZipArchive::ER_READ: 
					$errStr = '读取错误.';
					break;
					
				case ZipArchive::ER_SEEK: 
					$errStr = '查找错误.';
					break;
				
				default: 
					$errStr = '未知('. $res .')';
					break;
					
			}
			return array('res'=>false,'note'=>$errStr);
		}
	}


	// 添加整个目录到zip文件
	public static function AddDir($zip, $path, $newPath=''){
		$handler=opendir($path);
		while(($filename=readdir($handler))!==false){
			if($filename != '.' && $filename != '..'){	// 文件夹文件名字为'.'和‘..’，不要对他们进行操作
				if(is_dir($path .'/'. $filename)){
					self::AddDir($zip, $path .'/'. $filename, $newPath .'/'. $filename);
				}else{	// 将文件加入zip对象
					if (strlen($newPath) > 0){
						$zip->addFile($path .'/'. $filename, $newPath .'/'. $filename);
					}else{
						$zip->addFile($path .'/'. $filename);
					}
				}
			}
		}
		@closedir($path);
	}


	// 解压
	/*	如果返回值等于下面的属性，表示对应的错误 或者 返回TRUE 
		$res == ZipArchive::ER_EXISTS	File already exists.（文件已经存在） 
		$res == ZipArchive::ER_INCONS	Zip archive inconsistent.（压缩文件不一致） 
		$res == ZipArchive::ER_INVAL	Invalid argument.（无效的参数） 
		$res == ZipArchive::ER_MEMORY	Malloc failure.（内存错误？这个不确定） 
		$res == ZipArchive::ER_NOENT	No such file.（没有这样的文件） 
		$res == ZipArchive::ER_NOZIP	Not a zip archive.（没有一个压缩文件） 
		$res == ZipArchive::ER_OPEN		Can't open file.（不能打开文件） 
		$res == ZipArchive::ER_READ		Read error.（读取错误） 
		$res == ZipArchive::ER_SEEK		Seek error.（查找错误）
	*/
	public static function Jieya($filePath, $toPath){
		ini_set('memory_limit','3072M');
		@ini_set('max_execution_time', 0);
		@set_time_limit(0); // 无时间限制
		@ob_end_flush();

		$zip = new ZipArchive;
		$res = $zip->open($filePath);
		if ($res === true) {
			$zip->extractTo($toPath);	// 解压缩到文件夹
			$zip->close();
			return array('res'=>true,'note'=>'');
		}else{
			$zip->close();
			return array('res'=>false,'note'=>$res);
		}
	}


	// 获取压缩包里的列表
	public static function FileList($filePath){
		$zip = new ZipArchive;
		$res = $zip->open($filePath);
		if ($res === true) {
			$retArr = array();
			for ($i = 0; $i < $zip->numFiles; $i++) {
				$retArr[] = $zip->getNameIndex($i);
			}
			$zip->close();
			return array('res'=>true,'note'=>$retArr);
		}else{
			$zip->close();
			return array('res'=>false,'note'=>$res);
		}
	}


	// 根据文件名称，获取该文件的文本流 
	public static function FileStream($filePath, $fileName){
		$zip = new ZipArchive;
		$res = $zip->open($filePath);
		if ($res === true) {
			$stream = $zip->getStream($fileName);
			$str = stream_get_contents($stream); // 这里注意获取到的文本编码 
			$zip->close();
			return array('res'=>true,'note'=>$str);
		}else{
			$zip->close();
			return array('res'=>false,'note'=>$res);
		}
	}


	// 根据压缩文件内的文件名/索引（从0开始），修改压缩文件内的文件名
	// $filePath zip文件路径；$fileName 要修改的文件名或索引值；$newName 更改后的名称
	public static function RevFileName($filePath, $fileName, $newName){
		$zip = new ZipArchive;
		$res = $zip->open($filePath);
		if ($res === TRUE){
			if (is_numeric($fileName)){
				$zip->renameIndex($fileName, $newName);
			}else{
				$zip->renameName($fileName, $newName); 
			}
			$zip->close();
			return array('res'=>true,'note'=>'');
		}else{
			$zip->close();
			return array('res'=>false,'note'=>$res);
		}
	}


	// 根据压缩文件内的索引删除压缩文件内的文件
	// $fileName 可以是索引值，文件名，或者数组（删除多个用数组）
	public static function DelFile($filePath, $fileName){
		$zip = new ZipArchive;
		$res = $zip->open($filePath);
		if ($res === TRUE){
			if (is_array($fileName)){
				foreach ($fileName as $val){
					$zip->deleteName($val); 
				}
			}elseif (is_numeric($fileName)){
				$zip->deleteIndex($fileName);
			}else{
				$zip->deleteName($fileName); 
			}
			$zip->close();
			return array('res'=>true,'note'=>'');
		}else{
			$zip->close();
			return array('res'=>false,'note'=>$res);
		}
	}
}

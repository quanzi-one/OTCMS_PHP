<?php
require(dirname(__FILE__) .'/check.php');

//打开用户表，并检测用户是否登录
$MB->Open('','login');

$MB->IsAdminRight('alertBack');

$MB->Close();
$DB->Close();



function recurDir($dir) {
	if(is_dir($dir)) {
		if($handle = opendir($dir)) {
			while(false !== ($file = readdir($handle))) {
				if(is_dir($dir .'/'. $file)) {
					if($file != '.' && $file != '..') {
						$path = $dir .'/'. $file;
						if (strpos($path,'cache/') !== false || 
							strpos($path,'inc/') !== false || 
							strpos($path,'inc_img/') !== false || 
							strpos($path,'js/') !== false || 
							strpos($path,'pluDef/') !== false || 
							strpos($path,'plugin/') !== false || 
							strpos($path,'smarty/') !== false || 
							strpos($path,'temp/') !== false || 
							strpos($path,'template/') !== false || 
							strpos($path,'upFiles/') !== false || 
							strpos($path,'wap/cache/') !== false || 
							strpos($path,'wap/images/') !== false || 
							strpos($path,'wap/inc/') !== false || 
							strpos($path,'wap/js/') !== false || 
							strpos($path,'wap/skin/') !== false || 
							strpos($path,'wap/template/') !== false){
							0644 ? chmod($path,0644) : false;
							echo $path .'&ensp;&ensp;644<br />';
						}else{
							0755 ? chmod($path,0755) : false;
							echo $path .'&ensp;&ensp;755<br />';
						}
						recurDir($path);
					}
				}else{
					$path = $dir .'/'. $file;
					0644 ? chmod($path,0644) : false;
					echo $path .'&ensp;&ensp;&ensp;644<br />';
				}
			}
		}
		closedir($handle);
	}
}
 
recurDir(dirname(dirname(__FILE__)));
?>
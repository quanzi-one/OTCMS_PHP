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
$MB->IsAdminRight('alertBack');
$MB->Close();


$backupPath	= OT_ROOT . OT_dbBakDir;


switch ($mudi){
	case 'backup':
		backup();
		break;

	case 'del':
		del();
		break;

	case 'download':
		download();
		break;

	default:
		die('err');
}

$DB->Close();





// 备份
function backup(){
	global $DB,$dbServerName,$dbPort,$dbUserName,$dbUserPwd,$dbName,$backupPath;

	$backURL		= OT::PostStr('backURL');
	$mode			= OT::PostStr('mode');
	$fileSize		= OT::PostInt('fileSize');
	$zipPwd			= OT::PostStr('zipPwd');
	$zipNote		= OT::PostStr('zipNote');

	if(! File::IsWrite($backupPath)){
		JS::AlertBackEnd('备份文件存放目录不可写，请修改目录属性。'. $backupPath);
	}
	if (! extension_loaded('zip')){
		JS::AlertBackEnd('不支持zip扩展，无法使用该功能。');
	}

	if ($fileSize < 1024){ $fileSize = 5120; }
	$backURL = 'softBak.php?mudi=manage';

	switch ($mode){
		case 'all':
			$selTable = array('softFile','dbFile','htmlFile','upFile');
			$title = '全部备份';
			break;

		case 'soft':
			$selTable = array('softFile');
			$title = '程序文件备份';
			break;

		case 'diy':
			$selTable = OT::Post('selTable');
			$title = '自定义备份';
			break;

		default:
			JS::AlertBackEnd('类型（'. $mode .'）错误');
			break;
	}

	$fileArr = array();
	if ($mode == 'all'){
		$getArr = File::GetAllFileList(OT_ROOT);
		foreach ($getArr as $filePath){
			// array('type'=>'file','path'=>'index.php','name'=>'newname.php'),
			$fileArr[] = array('type'=>'file', 'path'=>OT_ROOT . $filePath, 'name'=>$filePath);
		}
	}else{
		$adminURL	= GetUrl::CurrDir();
		$beforeURL	= GetUrl::CurrDir(1);
		$adminDirName = substr($adminURL,strlen($beforeURL),-1);

		foreach ($selTable as $val){
			switch ($val){
				case 'softFile':
					$fileArr[] = array('type'=>'dir',	'path'=>OT_ROOT .$adminDirName,		'name'=>'admin');
					$fileArr[] = array('type'=>'dir',	'path'=>OT_ROOT .'inc',				'name'=>'inc');
					$fileArr[] = array('type'=>'dir',	'path'=>OT_ROOT .'inc_img',			'name'=>'inc_img');
					$fileArr[] = array('type'=>'dir',	'path'=>OT_ROOT .'js',				'name'=>'js');
					$fileArr[] = array('type'=>'dir',	'path'=>OT_ROOT .'pluDef',			'name'=>'pluDef');
					$fileArr[] = array('type'=>'dir',	'path'=>OT_ROOT .'plugin',			'name'=>'plugin');
					$fileArr[] = array('type'=>'dir',	'path'=>OT_ROOT .'smarty',			'name'=>'smarty');
					$fileArr[] = array('type'=>'dir',	'path'=>OT_ROOT .'template',		'name'=>'template');
					$fileArr[] = array('type'=>'dir',	'path'=>OT_ROOT .'tools',			'name'=>'tools');
					$fileArr[] = array('type'=>'file',	'path'=>OT_ROOT .'news/index.php',	'name'=>'news/index.php');
					$fileArr[] = array('type'=>'file',	'path'=>OT_ROOT .'html/lock.txt',	'name'=>'html/lock.txt');
					$fileArr[] = array('type'=>'file',	'path'=>OT_ROOT .'robots.txt',		'name'=>'robots.txt');

					$getArr = File::GetFileList(OT_ROOT, array('html','php','ini','ico'));
					foreach ($getArr as $file){
						$fileArr[] = array('type'=>'file',	'path'=>OT_ROOT . $file,		'name'=>$file);
					}

					if (file_exists(OT_ROOT .'go')){
						$fileArr[] = array('type'=>'dir',	'path'=>OT_ROOT .'go',			'name'=>'go');
					}
					if (file_exists(OT_ROOT .'pay')){
						$fileArr[] = array('type'=>'dir',	'path'=>OT_ROOT .'pay',			'name'=>'pay');
					}
					if (file_exists(OT_ROOT .'weixin')){
						$fileArr[] = array('type'=>'dir',	'path'=>OT_ROOT .'weixin',		'name'=>'weixin');
					}
					if (file_exists(OT_ROOT .'wap')){
						$getArr = File::GetFileList(OT_ROOT .'wap', array('html','php','ini','ico','png'));
						foreach ($getArr as $file){
							$fileArr[] = array('type'=>'file',	'path'=>OT_ROOT .'wap/'. $file,			'name'=>'wap/'. $file);
						}
						if (AppWap::Jud()){
							$fileArr[] = array('type'=>'dir',	'path'=>OT_ROOT .'wap/images',			'name'=>'wap/images');
							$fileArr[] = array('type'=>'dir',	'path'=>OT_ROOT .'wap/inc',				'name'=>'wap/inc');
							$fileArr[] = array('type'=>'dir',	'path'=>OT_ROOT .'wap/js',				'name'=>'wap/js');
							$fileArr[] = array('type'=>'dir',	'path'=>OT_ROOT .'wap/skin',			'name'=>'wap/skin');
							$fileArr[] = array('type'=>'dir',	'path'=>OT_ROOT .'wap/template',		'name'=>'wap/template');
							$fileArr[] = array('type'=>'dir',	'path'=>OT_ROOT .'wap/tools',			'name'=>'wap/tools');
							$fileArr[] = array('type'=>'file',	'path'=>OT_ROOT .'wap/news/index.php',	'name'=>'wap/news/index.php');
							$fileArr[] = array('type'=>'file',	'path'=>OT_ROOT .'wap/html/lock.txt',	'name'=>'wap/html/lock.txt');
						}
					}
					break;

				case 'dbFile':
					$fileArr[] = array('type'=>'dir',	'path'=>OT_ROOT . substr(OT_dbDir,0,-1),	'name'=>'Data');
					break;
			
				case 'htmlFile':
					$getArr = File::GetDirList(OT_ROOT);
					foreach ($getArr as $dir){
						if (! in_array($dir,array($adminDirName, substr(OT_dbDir,0,-1), substr(OT_dbBakDir,0,-1), 'inc', 'inc_img', 'js', 'pluDef', 'plugin', 'smarty', 'template', 'tools', 'upFiles', 'wap', 'cache'))){
							$dirArr = File::GetAllFileList(OT_ROOT . $dir);
							foreach ($dirArr as $file){
								if (File::GetExt($file) == 'html'){
									$fileArr[] = array('type'=>'file',	'path'=>OT_ROOT . $dir .'/'. $file,	'name'=>$dir .'/'. $file);
								}
							}
						}
					}
					break;
			
				case 'upFile':
					$fileArr[] = array('type'=>'dir',	'path'=>OT_ROOT .'upFiles',			'name'=>'upFiles');
					break;
			
			}
		}
	}

	$filename = 'soft'. date('ymdHis') .'_'. $mode .'.zip';
	$resArr = Zip::Yasuo($fileArr, $backupPath . $filename, array('show'=>true,'pwd'=>$zipPwd,'note'=>$zipNote));
		if (! $resArr['res']){
			JS::AlertBackEnd('压缩文件失败，原因：'. $resArr['note']);
		}

	$record = array();
	$record['DB_time']		= TimeDate::Get();
	$record['DB_type']		= 'soft';
	$record['DB_fileType']	= $mode;
	$record['DB_filePath']	= $filename;
	$record['DB_fileSize']	= filesize($backupPath . $filename);
	$record['DB_fileNum']	= 1;
	$record['DB_ver']		= OT_DBVER;
	$record['DB_timeStr']	= OT_DBTIME;

	$judResult = $DB->InsertParam('dbBak',$record);
		if ($judResult == true){
			$alertStr = '成功';
		}else{
			$alertStr = '失败';
		}

	JS::AlertHref('备份'. $alertStr, $backURL);

}



// 删除备份文件
function del(){
	global $DB,$backupPath;

	$dataID = OT::GetInt('dataID');

	if ($dataID <= 0){
		JS::AlertEnd('指定ID错误');
	}

	$row=$DB->GetRow('select DB_fileNum,DB_filePath from '. OT_dbPref .'dbBak where DB_ID='. $dataID);
	if (! $row){
		JS::AlertEnd('搜索不到相关数据！');
	}
	$filePath = $row['DB_filePath'];
	if ($row['DB_fileNum'] <= 1){
		File::Del($backupPath . $filePath);
	}else{
		for ($i=1; $i<=$row['DB_fileNum']; $i++){
			File::Del($backupPath . str_replace('part1', 'part'. $i, $filePath));
		}
	}
	unset($row);

	$DB->query('delete from '. OT_dbPref .'dbBak where DB_ID='. $dataID);

	Adm::AddLog(array(
		'title'		=> '备份名称',
		'theme'		=> $filePath,
		'note'		=> '【程序/数据库备份】删除！',
		));

	echo('
	<script language="javascript" type="text/javascript">
	parent.$id("data'. $dataID .'").style.display="none";
	</script>
	');
}



// 下载备份数据库
function download(){
	global $DB,$dataType,$dataTypeCN,$currDbPath,$backupPath;

	if (! OT_IsDownloadDB){
		JS::AlertCloseEnd('配置文件中未开启备份数据库下载权限\n\n如想开启，请打开根目录下config.php文件更改 OT_IsDownloadDB 常量的值为 true');
	}

	$dataID = OT::GetInt('dataID');
	
	$dlexe=$DB->query('select DB_time,DB_filePath from '. OT_dbPref .'dbBak where DB_ID='. $dataID);
		if (! $row = $dlexe->fetch()){
			JS::AlertEnd('无该文件！');
		}else{
//			ob_clean();
			$alertResult='成功';
			Adm::AddLog(array(
				'title'		=> '路径',
				'theme'		=> $row['DB_filePath'],
				'note'		=> '【'. $dataTypeCN .'】下载'. $alertResult .'！',
				));

			File::Download($backupPath . $row['DB_filePath'],'备份于'. $row['DB_filePath']);
		}
	unset($dlexe);
}

?>
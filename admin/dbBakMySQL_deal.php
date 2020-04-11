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

	case 'compress':
		compress();
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

	if(! File::IsWrite($backupPath)){
		JS::AlertBackEnd('备份文件存放目录不可写，请修改目录属性。'. $backupPath);
	}

	if ($fileSize < 1024){ $fileSize = 5120; }
	$backURL = 'softBak.php?mudi=manage';

	$dbObj = new MySqlManage($dbServerName, $dbPort, $dbUserName, $dbUserPwd, $dbName);
	$dbObj->bakType = $mode;

	switch ($mode){
		case 'all':
			$selTable = array();
			$title = '全部备份';
			break;

		case 'ot':
			$selTable = array();

			$allArr = $dbObj->GetTableArr();
			$tabPrefLen = strlen(OT_dbPref);
			foreach ($allArr as $val){
				if (strcasecmp(substr($val,0,$tabPrefLen), OT_dbPref) == 0){
					$selTable[] = $val;
				}
			}
			unset($tabexe);

			$title = '网钛表备份';
			break;

		case 'common':
			$selTable = array();
			$title = '标准备份';
			break;

		case 'min':
			$selTable = array();
			$title = '最小备份';
			break;

		case 'diy':
			$selTable = OT::Post('selTable');
			$title = '自定义备份';
			break;

		default:
			JS::AlertBackEnd('类型（'. $mode .'）错误');
			break;
	}

	$infoArr = $dbObj->backup($selTable, $backupPath, $fileSize);

	$record = array();
	$record['DB_time']		= TimeDate::Get();
	$record['DB_type']		= 'db';
	$record['DB_fileType']	= $infoArr['type'];
	$record['DB_filePath']	= $infoArr['name'];
	$record['DB_fileSize']	= $infoArr['size'];
	$record['DB_fileNum']	= $infoArr['num'];
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



// 数据库恢复
function restore(){
	global $mudi,$BMS,$backupPath;

	$backURL		= OT::PostStr('backURL');
	$mode			= OT::PostStr('mode');
	$serverFile		= OT::PostStr('serverFile');

}

?>

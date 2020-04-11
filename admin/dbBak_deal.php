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


	$currDbPath	= OT_ROOT . $dbName;
	$backupPath	= OT_ROOT . OT_dbBakDir;


switch ($mudi){
	case 'backup':
		backup();
		break;

	case 'compress':
		compress();
		break;

	case 'restore':
		restore();
		break;

	case 'dbDeal':
		DbDeal();
		break;

	default:
		die('err');
}

$DB->Close();





// 数据库备份
function backup(){
	global $DB,$dataType,$dataTypeCN,$currDbPath,$backupPath;

	$backURL	= OT::PostStr('backURL');
	
	/* $backupName	= OT::PostStr('backupName');
	$backupFile	= $backupPath . $backupName .'.db';

	if ($backupName == ''){
		JS::AlertBackEnd('表单接收不全.');
	}
	*/
	$backupName	= 'bak_'. TimeDate::Get('Ymd_H_i_s') .'.db';
	$backupFile	= $backupPath . $backupName;

	$alertResult='失败';
	if (File::IsExists($currDbPath)){
		if (File::Copy($currDbPath,$backupFile)){
			$record = array();
			$record['DB_time']		= TimeDate::Get();
			$record['DB_type']		= 'db';
			$record['DB_filePath']	= $backupName;
			$record['DB_fileSize']	= filesize($currDbPath);
			$record['DB_fileNum']	= 1;
			$record['DB_ver']		= OT_DBVER;
			$record['DB_timeStr']	= OT_DBTIME;
			$judResult = $DB->InsertParam('dbBak',$record);

			$alertResult='成功';
			JS::AlertHref('备份数据库成功.',$backURL);
		
		}else{
			JS::AlertHref('备份数据库失败，复制操作失败.',$backURL);
		}
	}else{
		JS::AlertHref('备份数据库失败，源数据库路径不存在.',$backURL);
	}

	Adm::AddLog(array(
		'note'		=> '【'. $dataTypeCN .'】备份'. $alertResult .'！',
		));
}



// 数据库压缩
function compress(){
	global $DB,$dataType,$dataTypeCN,$currDbPath,$backupPath;

	$backURL		= OT::PostStr('backURL');
	$backupFileID	= OT::PostInt('backupFileID',0);

	if ($backupFileID < 1 && $backupFileID != -99){
		JS::AlertBackEnd('表单接收不全.');
	}

	$alertResult='';
	Adm::AddLog(array(
		'note'		=> '【'. $dataTypeCN .'】压缩数据库'. $alertResult .'！',
		));

	if ($backupFileID == -99){
		$DB->query('VACUUM');
		JS::AlertHref('压缩成功!',$backURL);
	}else{
		$pathexe=$DB->query('select DB_ID,DB_filePath from '. OT_dbPref .'dbBak where DB_ID='. $backupFileID);
			if (! $row = $pathexe->fetch()){
				JS::AlertBackEnd('该备份记录不存在.');
			}

			$backupFile = $backupPath . $row['DB_filePath'];

		unset($pathexe);

		$yasuoDB = new PdoDb( array('type'=>'sqlite', 'dsn'=>'sqlite:'. $backupFile, 'user'=>'', 'pwd'=>'') );
		$yasuoDB->query('VACUUM');
		unset($yasuoDB);

		$newFileSize = filesize($backupFile);
			if ($newFileSize > 0){
				$DB->query('update '. OT_dbPref .'dbBak set DB_fileSize='. $newFileSize .' where DB_ID='. $backupFileID);
			}
			JS::AlertHref('压缩成功!',$backURL);
	}

}



// 数据库维护
function DbDeal(){
	global $DB,$dataType,$dataTypeCN;

	$backURL		= OT::GetStr('backURL');
	$mudi2			= OT::GetStr('mudi2');

	switch ($mudi2){
		case 'shiwu0':
			$resTheme = '事务支持：关闭';
			$DB->query('PRAGMA journal_mode = off');
			break;
	
		case 'shiwu1':
			$resTheme = '事务支持：开启';
			$DB->query('PRAGMA journal_mode = DELETE');
			break;
	
		case 'check':
			$resTheme = '检查数据库异常';
			$DB->query('PRAGMA integrity_check');
			break;
	
		default :
			JS::AlertEnd('目的不明确.');
			break;
	}

	$dataTypeCN = '数据库维护';
	Adm::AddLog(array(
		'note'		=> '【'. $dataTypeCN .'】'. $resTheme .' 执行完成！',
		));

	JS::AlertEnd(''. $resTheme .' 执行完成.');

}

?>

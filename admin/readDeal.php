<?php
require(dirname(__FILE__) .'/check.php');


switch ($mudi){
	case 'updateWebCache':
		UpdateWebCache();
		break;

	case 'clearWebCache':
		ClearWebCache();
		break;

	case 'updateBackupCall':
		UpdateBackupCall();
		break;

	case 'checkEditorMode':
		CheckEditorMode();
		break;

	case 'readQrCode':
		ReadQrCode();
		break;

	default:
		die('err');
}

$DB->Close();





function UpdateWebCache(){
	global $DB,$dbName;

	$mode = OT::GetStr('mode');
	if ($mode == 'verCodeNum'){
		$DB->query('update '. OT_dbPref .'system set SYS_verCodeMode=1 where SYS_ID=1');
	}elseif ($mode == 'defTpl'){
		$DB->query('update '. OT_dbPref .'system set SYS_templateDir="default/" where SYS_ID=1');
	}elseif ($mode == 'showDb'){
		$tabNum = 0;
		$sizeexe = $DB->query("select TABLE_NAME from information_schema.tables where TABLE_SCHEMA = '". $dbName ."'");
			while ($row = $sizeexe->fetch()){
				$tabNum ++;
				echo('<div>'. str_replace(OT_dbPref,'前缀_',$row['TABLE_NAME']) .'（'. $DB->GetOne('select count(1) from '. $row['TABLE_NAME'] .'') .'）</div>');
			}
		unset($sizeexe);
		die('<div>共 <b>'. $tabNum .'</b> 张表</div>');
	}

	$Cache = new Cache();
	$Cache->Php('userSys');
	$Cache->Js('userSys');
	$Cache->Php('infoSys');
	$Cache->Js('infoSys');
	$Cache->Php('sysAdmin');
	$Cache->Php('system');
	$Cache->Js('system');
	$Cache->Php('sysImages');
	$Cache->Php('autoRunSys');
	$Cache->Js('autoRunSys');
	$Cache->Php('appSys');
	$Cache->Js('appSys');
	Cache::UpdateConfigJs();

	if (AppWap::Jud()){
		$DB->query('update '. OT_dbPref .'wap set WAP_htmlCacheTime='. $DB->ForTime(TimeDate::Get()));
		$Cache->Php('wap');
	}
	if (AppBbs::Jud()){
		$Cache->Php('bbsSys');
	}

	File::DelDir(OT_ROOT .'cache/smarty/templates_c/', false);
	Ad::MakeJs();

	JS::AlertEnd('更新配置缓存文件完成');
}



// 清空缓存
function ClearWebCache(){
	$mode = OT::GetStr('mode');

	$webCacheNum = File::Count(OT_ROOT .'cache/html/',array('html','png'));

	if ($webCacheNum>0){
		$retNum = File::MoreDel(OT_ROOT .'cache/html/',array('html','png'));

		if ($mode != 'noAlert'){
			echo('alert("共清空了'. $retNum .'个缓存。");');
		}
	}else{
		if ($mode != 'noAlert'){
			echo('alert("暂无页面缓存，无需清理。");');
		}
	}
/*
	global $DB;

	$todayTime = TimeDate::Get();
	$DB->query('update '. OT_dbPref .'system set SYS_htmlCacheTime='. $DB->ForTime($todayTime));

	$Cache = new Cache();
	$Cache->Php('system');

	if ($mode != 'noAlert'){
		echo('alert("页面缓存已更新'. $todayTime .'.");');
	}
*/

}



function UpdateBackupCall(){
	global $DB;

	$num = OT::GetInt('num');

	$newDate = '';
	switch ($num){
		case -1:
			$newDate=TimeDate::Get('date');
			break;

		case 1:
			$newDate=TimeDate::Add('d',1-$sysAdminArr['SA_backupCallDay'],TimeDate::Get('date'));
			break;

		case 7:
			$newDate=TimeDate::Add('d',7-$sysAdminArr['SA_backupCallDay'],TimeDate::Get('date'));
			break;

	}
	if (strtotime($newDate)){
		$DB->query('update '. OT_dbPref .'sysAdmin set SA_backupCallTime='. $DB->ForTime($newDate) .' where SA_ID=1');
	}
	
	$Cache = new Cache();
	$Cache->Php('sysAdmin');
}



function CheckEditorMode(){
	$editorMode = OT::GetStr('editorMode');

	switch ($editorMode){
		case 'kindeditor4.x':
			$fileStr = File::Read(OT_adminROOT .'tools/kindeditor4/kindeditor-all-min.js');
			if (strlen($fileStr)<=15){
				die(''.
				'alert("未检测到KindEditor4.x编辑器，请确定存在【后台目录/tools/kindeditor4/】该目录");'.
				'$id("editorMode1").checked=true;'.
				'');
			}
			break;

		case 'kindeditor3.x':
			$fileStr = File::Read(OT_adminROOT .'tools/kindeditor/kindeditor-min.js');
			if (strlen($fileStr)<=15){
				die(''.
				'alert("未检测到KindEditor3.x编辑器，请确定存在【后台目录/tools/kindeditor/】该目录");'.
				'$id("editorMode1").checked=true;'.
				'');
			}
			break;

		case 'ckeditor4.x':
			$fileStr = File::Read(OT_adminROOT .'tools/ckeditor4/ckeditor.js');
			if (strlen($fileStr)<=15){
				die(''.
				'alert("未检测到CKEditor4.x编辑器，请确定存在【后台目录/ckeditor4/】该目录");'.
				'$id("editorMode1").checked=true;'.
				'');
			}
			break;

		case 'ckeditor':
			$fileStr = File::Read(OT_adminROOT .'tools/ckeditor/ckeditor.js');
			if (strlen($fileStr)<=15){
				die(''.
				'alert("未检测到CKEditor3.x编辑器，请确定存在【后台目录/ckeditor/】该目录");'.
				'$id("editorMode1").checked=true;'.
				'');
			}
			break;

		case 'fckeditor':
			$fileStr = File::Read(OT_adminROOT .'tools/fckeditor/fckeditor.js');
			if (strlen($fileStr)<=15){
				die(''.
				'alert("未检测到FCKeditor编辑器，请确定存在【后台目录/fckeditor/】该目录");'.
				'$id("editorMode1").checked=true;'.
				'');
			}
			break;

		case 'ueditor':
			$fileStr = File::Read(OT_adminROOT .'tools/ueditor/ueditor.config.js');
			if (strlen($fileStr)<=15){
				die(''.
				'alert("未检测到ueditor编辑器，请确定存在【后台目录/tools/ueditor/】该目录");'.
				'$id("editorMode1").checked=true;'.
				'');
			}
			break;

		default :
			die(''.
			'alert("未知编辑器.");'.
			'$id("editorMode1").checked=true;'.
			'');
	}
}


function ReadQrCode(){
	$dir = OT::GetStr('dir');
	$img = OT::GetStr('img');
	if (strlen($img) == 0){ die('二维码图片路径为空'); }
	if (! Is::HttpUrl($img)){
		$img = StrInfo::FilePath($dir, $img);
		if (! file_exists($img)){ die('二维码图片不存在('. $img .')'); }
	}

	include_once(OT_ROOT .'inc/QrReader/QrReader.php');
	$qrcode = new QrReader($img);	// 图片路径
	$text = $qrcode->text();		//返回识别后的文本
	if (strlen($text) == 0){ die('二维码图片识别不了'); }
	die($text);
}
?>
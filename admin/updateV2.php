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


//用户检测
$MB->Open('','login');

$skin->WebTop();


echo('
<script language="javascript" type="text/javascript" src="js/updateV2.js?v='. OT_VERSION .'"></script>
');


$MB->IsAdminRight('alertBack');

$beforeURL = GetUrl::CurrDir(1);
$adminURL = GetUrl::CurrDir(0);

$dataTypeCN = '在线升级';

$appID		= OT::GetInt('appID');
	if ($appID==0){ $appID	= OT::PostInt('appID'); }
$appType	= OT::GetStr('appType');
	if ($appType==''){ $appType	= OT::PostStr('appType'); }

echo('
<form id="updateForm" name="updateForm" method="post" action="">
<input type="hidden" id="updateEventStr" name="updateEventStr" value="" /><!-- 升级事件集（提示备份、更新皮肤等） -->
<input type="hidden" id="updateFileNum" name="updateFileNum" value="" /><!-- 更新文件数量 -->
<input type="hidden" id="updateFileSize" name="updateFileSize" value="" /><!-- 更新文件总大小 -->

<input type="hidden" id="checkFileListStr" name="checkFileListStr" value="" /><!-- 检查升级文件权限列表 -->

<input type="hidden" id="updateVerInfo" name="updateVerInfo" value="" /><!-- 升级包信息 -->
<input type="hidden" id="updateVerTheme" name="updateVerTheme" value="" /><!-- 升级包名称 -->
<input type="hidden" id="updateFileListStr" name="updateFileListStr" value="" /><!-- 下载更新文件列表 -->
<input type="hidden" id="updateVerPoint" name="updateVerPoint" value="" />
<input type="hidden" id="updateFilePoint" name="updateFilePoint" value="" /><!-- 当前正在下载位置 -->

<input type="hidden" id="fileStr" name="fileStr" value="" />

<input type="hidden" id="runFileSkipStr" name="runFileSkipStr" value="" />

<input type="hidden" id="appID" name="appID" value="'. $appID .'" />
<input type="hidden" id="appType" name="appType" value="'. $appType .'" />


<div id="updateContent" style="line-height:1.6;">
');


switch ($mudi){
	case 'getUpdate':
		// 步骤1：链接官网升级库
		GetUpdate();
		break;

	case 'checkRight':
		// 步骤2：检测所需的目录文件权限
		CheckRight();
		break;

	case 'getVer':
		// 步骤3_1：获取版本信息
		GetVer();
		break;

	case 'getFile':
		// 步骤3_2：获取更新文件
		GetFile();
		break;

	case 'runUpdate':
		// 步骤4：运行升级过程
		RunUpdate();
		break;

	default:
		// 初始页
		defWeb();
		break;

}

$skin->WebBottom();

$MB->Close();
$DB->Close();

echo('
</div>
</form>
');





function defWeb(){
	global $beforeURL,$sysAdminArr,$appID,$appType;

	if ($appType != 'dataFree'){ $appType='dataApp'; }
//	$appType='dataFree';

	$updateUrl1	= 'http://php.otcms.com/';
	$updateUrl2	= 'http://update2.oneti.cn/';
	$updateUrl3	= 'http://update2.bai35.com/';
	$updateUrl4	= 'http://update2.otcms.org/';

	if ($appID > 0){
		echo('
		<b>插件线路选择：</b>
		<span onclick="ClickShowHidden(\'updateUrlBugBox\')">&ensp;</span><span id="updateUrlBugBox" style="display:none;"><input type="checkbox" id="isUpdateUrlBug" name="isUpdateUrlBug" value="1" />检测路径Bug模式</span>
		<br />
		<label><input type="radio" id="updateUrl1" name="updateUrl" value="'. $updateUrl1 . $appType .'.php" '. Is::Checked($sysAdminArr['SA_updateUrl'],''. $updateUrl1 . $appType .'.php') .' onclick=\'UpdateUrlBtn("'. $updateUrl1 . $appType .'.php");\' />主路线</label>&ensp;&ensp;
		<label style="color:#ccc;" title="主路线不能用时，再尝试该路线."><input type="radio" id="updateUrl2" name="updateUrl" value="'. $updateUrl2 . $appType .'.php" '. Is::Checked($sysAdminArr['SA_updateUrl'],''. $updateUrl2 . $appType .'.php') .' onclick=\'UpdateUrlBtn("'. $updateUrl2 . $appType .'.php");\' />国内备用</label>&ensp;&ensp;
		<label style="color:#ccc;" title="主路线不能用时，再尝试该路线，特别是用香港、国外空间的用户"><input type="radio" id="updateUrl3" name="updateUrl" value="'. $updateUrl3 . $appType .'.php" '. Is::Checked($sysAdminArr['SA_updateUrl'],''. $updateUrl3 . $appType .'.php') .' onclick=\'UpdateUrlBtn("'. $updateUrl3 . $appType .'.php");\' />香港备用</label>&ensp;&ensp;
		<label style="color:#ccc;" title="主路线不能用时，再尝试该路线，特别是用国外空间的用户"><input type="radio" id="updateUrl4" name="updateUrl" value="'. $updateUrl4 . $appType .'.php" '. Is::Checked($sysAdminArr['SA_updateUrl'],''. $updateUrl4 . $appType .'.php') .' onclick=\'UpdateUrlBtn("'. $updateUrl4 . $appType .'.php");\' />美国备用</label>&ensp;&ensp;
		<br /><br />
		');

	}else{
		echo('
		<b>升级线路选择：</b>
		<span onclick="ClickShowHidden(\'updateUrlBugBox\')">&ensp;</span><span id="updateUrlBugBox" style="display:none;"><input type="checkbox" id="isUpdateUrlBug" name="isUpdateUrlBug" value="1" />检测路径Bug模式</span>
		<br />
		<label><input type="radio" id="updateUrl1" name="updateUrl" value="'. $updateUrl1 .'dataVer.php" '. Is::Checked($sysAdminArr['SA_updateUrl'],''. $updateUrl1 .'dataVer.php') .' onclick=\'UpdateUrlBtn("'. $updateUrl1 .'dataVer.php");\' />主路线</label>&ensp;&ensp;
		<label style="color:#ccc;" title="主路线不能用时，再尝试该路线."><input type="radio" id="updateUrl2" name="updateUrl" value="'. $updateUrl2 .'dataVer.php" '. Is::Checked($sysAdminArr['SA_updateUrl'],''. $updateUrl2 .'dataVer.php') .' onclick=\'UpdateUrlBtn("'. $updateUrl2 .'dataVer.php");\' />国内备用</label>&ensp;&ensp;
		<label style="color:#ccc;" title="主路线不能用时，再尝试该路线，特别是用香港、国外空间的用户"><input type="radio" id="updateUrl2" name="updateUrl" value="'. $updateUrl3 .'dataVer.php" '. Is::Checked($sysAdminArr['SA_updateUrl'],''. $updateUrl3 .'dataVer.php') .' onclick=\'UpdateUrlBtn("'. $updateUrl3 .'dataVer.php");\' />香港备用</label>&ensp;&ensp;
		<label style="color:#ccc;" title="主路线不能用时，再尝试该路线，特别是用香港、国外空间的用户"><input type="radio" id="updateUrl2" name="updateUrl" value="'. $updateUrl4 .'dataVer.php" '. Is::Checked($sysAdminArr['SA_updateUrl'],''. $updateUrl4 .'dataVer.php') .' onclick=\'UpdateUrlBtn("'. $updateUrl4 .'dataVer.php");\' />美国备用</label>&ensp;&ensp;
		<label><input type="radio" id="updateUrl3" name="updateUrl" value="'. $updateUrl1 .'dataVerOther.php" '. Is::Checked($sysAdminArr['SA_updateUrl'],''. $updateUrl1 .'dataVerOther.php') .' onclick=\'UpdateUrlBtn("'. $updateUrl1 .'dataVerOther.php");\' />修复文件推送</label>&ensp;&ensp;
		<br /><br />
		');
	}

	echo('
	该功能需要开启全站的可读、可写权限。使用完后请恢复原来权限。<br />（注:查看历史升级记录在[管理员专区]-<span style="cursor:pointer;" onclick=\'parent.HrefTo("","在线升级记录管理","update.php?mudi=manage","999995");\'>[在线升级记录管理]</span>）<br />

	<input type="button" id="updateStartBtn" value="开始连接" onclick=\'if (confirm("如果有【修改过程序代码或定制过程序功能】或者【使用非官方模板】的，请不要升级！！\n如果有【修改过程序代码或定制过程序功能】或者【使用非官方模板】的，请不要升级！！\n如果有【修改过程序代码或定制过程序功能】或者【使用非官方模板】的，请不要升级！！\n重要事情说三遍！！！不然可能会造成程序错误或功能异常。\n\n要继续升级请点击[确定]，否则请点[取消]")==true){StartStep1("");}\' />
	&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;
	<input type="button" id="clearUpdateDbBtn" value="清空升级库" onclick="ClearUpdateDb();" />&ensp;&ensp;<span id="updateDbInfo"></span>
	');
}



// 步骤1：链接官网升级库
function GetUpdate(){
	global $DB,$beforeURL,$adminURL,$sysAdminArr,$appID,$appType;
	
	$updateRndStr = time() . OT::RndNum(5);
	File::Write(OT_ROOT .'cache/web/update.txt',md5($updateRndStr . $sysAdminArr['SA_softID']));

	$updatePartUrl = '&OT_UPDATETIME='. OT_UPDATETIME .'&OT_VERSION='. OT_VERSION .'&dataVer='. OT_UPDATEVER .'&phpVer='. PHP_VERSION .'&OT_URL='. urlencode($beforeURL) .'&updateRndStr='. $updateRndStr .'&username='. $sysAdminArr['SA_username'] .'&softID='. $sysAdminArr['SA_softID'] .'&softCode='. $sysAdminArr['SA_softCode'] .'&domainID='. $sysAdminArr['SA_domainID'] .'&domainCode='. $sysAdminArr['SA_domainCode'] .'&appID='. $appID .'&appType='. $appType .'&appVerTime=';	// &adminUrl='. urlencode($adminURL) .'

	$retArr	= ReqUrl::UseAuto($sysAdminArr['SA_getUrlMode'], 'GET', $sysAdminArr['SA_updateUrl'] .'?mudi=check'. $updatePartUrl .'&rnd='. time());
	if (! $retArr['res']){
		$retArr['note'] = updateImg(1,'no') .'连接不到网钛官网版本升级库（'. $sysAdminArr['SA_updateUrl'] .'）；<br />请换个路线尝试看看。<br /><br />'. BtnCode('重新尝试连接','updateStep1','load','check') .'&ensp;<input type="button" value="更换路线" onclick="ConfigWindow();" />';
	}

	echo($retArr['note']);
}



// 步骤2：检测所需的目录文件权限
function CheckRight(){
	global $DB,$beforeURL,$adminURL,$sysAdminArr,$appID,$appType;

	$updateFileNum		= OT::PostInt('updateFileNum');
	$updateFileSize		= OT::PostInt('updateFileSize');
	$checkFileListStr	= OT::PostStr('checkFileListStr');

	$adminDir = substr($adminURL,strlen($beforeURL));
	$checkFileListStr = str_replace('admin/',$adminDir,$checkFileListStr);
	$checkFileListStr = str_replace('Data/',OT_dbDir,$checkFileListStr);
	$fileListArr = explode('|',$checkFileListStr);

	if (! File::IsWrite(OT_adminROOT . UpdateAdminDir)){
		die(updateImg(2,'no') .'升级缓存目录无法写入（后台/'. UpdateAdminDir .'），请赋予该目录读写权限。<br /><br />'. BtnCode('重新连接','updateStep1','load','check') .'&ensp;&ensp;&ensp;&ensp;'. BtnCode('重新检测权限','updateStep2','load','backup') .'');
	}

	foreach ($fileListArr as $fileListStr){
		$fileArr = explode('[arr]',$fileListStr);
		if (! is_numeric($fileArr[0])){
			die(updateImg(2,'no') .'获取到的需备份信息错误'. $fileArr[0] .'<br />'. BtnCode('重新连接','updateStep1','load','check') .'&ensp;&ensp;&ensp;&ensp;'. BtnCode('重新检测权限','updateStep2','load','backup') .'');
		}

		$fileCount = count($fileArr);
		for ($i=0; $i<$fileCount; $i++){
			$filePath = OT_ROOT . $fileArr[$i];
			$fileExt = File::GetExt($filePath);
			$fileExtList = '|php|css|js|txt|inc|htm|html|shtml|';
			if (strpos($fileArr[$i],'/') !== false){
				$fileDirArr		= explode('/',$fileArr[$i]);
				$fileDirCount	= count($fileDirArr)-1;
				$fileDirPath	= OT_ROOT;
				for ($i2=0; $i2<$fileDirCount; $i2++){
					$fileDirPath .= $fileDirArr[$i2] .'/';
					if (! File::IsExists($fileDirPath)){
						if (! File::CreateDir($fileDirPath)){
							die(updateImg(2,'no') . $fileDirPath .' -- 该目录无法创建，请检查该网站是否有写入权限。<br />'. BtnCode('重新连接','updateStep1','load','check') .'&ensp;&ensp;&ensp;&ensp;'. BtnCode('重新检测权限','updateStep2','load','backup') .'');
						}
					}
				}
			}
			if (strpos($fileExtList,'|'. $fileExt .'|') !== false){
				if (! File::IsExists($filePath)){
					if (! File::Write($filePath,'OTCMS PHP v1.x')){
						die(updateImg(2,'no') . $fileArr[$i] .' -- 该文件无法创建，请检查该目录权限。<br />'. BtnCode('重新连接','updateStep1','load','check') .'&ensp;&ensp;&ensp;&ensp;'. BtnCode('重新检测权限','updateStep1','load','backup') .'');
					}
					File::Del($filePath);
				}else{
					if (! File::IsRead($filePath)){
						die(updateImg(2,'no') . $fileArr[$i] .' -- 该文件不可读，请检查该目录权限。<br />'. BtnCode('重新连接','updateStep1','load','check') .'&ensp;&ensp;&ensp;&ensp;'. BtnCode('重新检测权限','updateStep2','load','backup') .'');
					}
					if (! File::IsWrite($filePath)){
						die(updateImg(2,'no') . $fileArr[$i] .' -- 该文件不可写，请检查该目录权限。<br />'. BtnCode('重新连接','updateStep1','load','check') .'&ensp;&ensp;&ensp;&ensp;'. BtnCode('重新检测权限','updateStep2','load','backup') .'');
					}
				}
			}
		}
	}

	echo(updateImg(2,'yes') . $updateFileNum .'个更新文件(约'. File::SizeUnit($updateFileSize) .')检测通过，需要的权限均已打开。<br />'. BtnCode('下一步','updateStep3','load','getFile') .'<script language="javascript" type="text/javascript">EndStep2();</script>');
}



// 步骤3_1：获取版本信息
function GetVer(){
	global $DB,$beforeURL,$adminURL,$sysAdminArr,$appID,$appType;

	$fileNum	= OT::PostInt('updateFileNum');
	$fileSize	= OT::PostInt('updateFileSize');
	$verStr		= OT::PostStr('updateVerInfo');

	$verFileStr = ReqUrl::UseAuto($sysAdminArr['SA_getUrlMode'], 'GET', $sysAdminArr['SA_updateUrl'] .'?mudi=downloadVer&OT_UPDATETIME='. OT_UPDATETIME .'&OT_VERSION='. OT_VERSION .'&dataVer='. OT_UPDATEVER .'&phpVer='. PHP_VERSION .'&OT_URL='. urlencode($beforeURL) .'&username='. $sysAdminArr['SA_username'] .'&softID='. $sysAdminArr['SA_softID'] .'&softCode='. $sysAdminArr['SA_softCode'] .'&domainID='. $sysAdminArr['SA_domainID'] .'&domainCode='. $sysAdminArr['SA_domainCode'] .'&verStr='. $verStr .'&appID='. $appID .'&appType='. $appType .'&appVerTime=', 'UTF-8', array(), 'note');
	if (strpos($verFileStr,'[OT'.'CMS]') === false){
		die($verFileStr);
	}

	$FV_ver				= Str::GetMark($verFileStr,'[field:FV_ver]','[/field:FV_ver]');
	$FV_mode			= Str::GetMark($verFileStr,'[field:FV_mode]','[/field:FV_mode]');
	$FV_verTimeStart	= Str::GetMark($verFileStr,'[field:FV_verTimeStart]','[/field:FV_verTimeStart]');
	$FV_verTime			= Str::GetMark($verFileStr,'[field:FV_verTime]','[/field:FV_verTime]');
	$FV_verNum			= Str::GetMark($verFileStr,'[field:FV_verNum]','[/field:FV_verNum]');
	$FV_runFile			= Str::GetMark($verFileStr,'[field:FV_runFile]','[/field:FV_runFile]');
	$FV_runFileCode		= Str::GetMark($verFileStr,'[field:FV_runFileCode]','[/field:FV_runFileCode]');
	$FV_isRunCode		= Str::GetMark($verFileStr,'[field:FV_isRunCode]','[/field:FV_isRunCode]');
	$FV_fileNum			= Str::GetMark($verFileStr,'[field:FV_fileNum]','[/field:FV_fileNum]');
	$FV_updateNote		= Str::GetMark($verFileStr,'[field:FV_updateNote]','[/field:FV_updateNote]');
	$updateFile			= Str::GetMark($verFileStr,'[list:updateFile]','[/list:updateFile]');
		if ($FV_ver=='' || $FV_verTime=='' || is_numeric($FV_mode)==false || is_numeric($FV_fileNum)==false || $updateFile==''){
			die(updateImg(3,'no') .'获取版本信息残缺('. $FV_ver .'|'. $FV_verTime .'|'. $FV_mode .'|'. $FV_fileNum .'|'. $updateFile .')。<br />'. BtnCode('重新检测','updateStep1','load','check') .'');
		}
		if (is_numeric($FV_verTimeStart)==false || is_numeric($FV_verTime)==false){
			die(updateImg(3,'no') .'获取版本信息残缺('. is_numeric($FV_verTimeStart) .'|'. is_numeric($FV_verTime) .')。<br />'. BtnCode('重新检测','updateStep1','load','check') .'');
		}

	$updateLastFileName = '';
	$judUpdateLastFile = false;
	$verrec=$DB->query("select * from ". OT_dbPref ."fileVer where FV_type='". $appID ."' and FV_verTimeStart='". $FV_verTimeStart ."' and FV_verTime='". $FV_verTime ."'");
		if (! $row = $verrec->fetch()){
			$record = array();
			$record['FV_type']			= $appID;
			$record['FV_time']			= TimeDate::Get();
			$record['FV_ver']			= $FV_ver;
			$record['FV_mode']			= $FV_mode;
			$record['FV_verTimeStart']	= $FV_verTimeStart;
			$record['FV_verTime']		= $FV_verTime;
			$record['FV_verNum']		= $FV_verNum;
			$record['FV_runFile']		= $FV_runFile;
			$record['FV_runFileCode']	= $FV_runFileCode;
			$record['FV_isRunCode']		= $FV_isRunCode;
			$record['FV_fileNum']		= $FV_fileNum;
			$record['FV_fileSize']		= $fileSize;
			$record['FV_updateNote']	= $FV_updateNote;
			$record['FV_isLock']		= 0;
			$record['FV_state']			= 1;
			$judResult = $DB->InsertParam('fileVer',$record);
			
			$verID = $DB->GetOne('select max(FV_ID) from '. OT_dbPref .'fileVer');
		}else{
			if ($row['FV_isLock'] == 1){
				die(updateImg(3,'no') . $FV_verTime .' 升级包记录已存在，并锁定，下载文件停止。<br />请点击<input type="button" id="clearUpdateDbBtn" value="清空升级库" onclick="ClearUpdateDb();" /><span id="updateDbInfo"></span><br />然后'. BtnCode('重新检测','updateStep1','load','check') .'');
			}

			$verID = $row['FV_ID'];
			$judUpdateLastFile = true;
			$lastFileexe = $DB->query('select FD_fileID from '. OT_dbPref .'fileData where FD_verID='. $verID .' and FD_state=1 order by FD_ID DESC');
				if ($row2 = $lastFileexe->fetch()){ $updateLastFileName=$row2['FD_fileID']; }
			unset($lastFileexe);
		}
	unset($verrec);

	if ($FV_mode == 1){
		$verTimeStr = $FV_verTimeStart .' 升级至 '. $FV_verTime;
	}else{
		$verTimeStr = $FV_verTime;
	}

	if ($judUpdateLastFile){
		echo('
		<script language="javascript" type="text/javascript">
		parent.updateVerID='. $verID .';
		parent.$id("updateVerTheme").value="'. $verTimeStr .' '. $FV_verNum .'个补丁包整合";
		parent.updateLastFileName="'. $updateLastFileName .'";
		parent.judUpdateLastFile="true";
		parent.$id("updateFileListStr").value = "'. $updateFile .'";
		DownloadFileGet();
		</script>

		<span class="font3_2">'. $verTimeStr .' '. $FV_verNum .'个补丁包整合</span> -- 版本数据信息下载完成 &ensp;<br /><br />发现上次升级过程被迫中断，是否继续完成上次升级过程？<br /><input type="button" value="继 续" onclick=\'DownloadGoOn("go");\' />&ensp;&ensp;<input type="button" value="重新下载" onclick=\'DownloadGoOn("reload");\' /></span>
		');
	}else{
		echo('
		<script language="javascript" type="text/javascript">
		parent.updateVerID='. $verID .';
		parent.$id("updateVerTheme").value="'. $verTimeStr .' '. $FV_verNum .'个补丁包整合";
		parent.updateLastFileName="'. $updateLastFileName .'";
		parent.judUpdateLastFile="false";
		parent.$id("updateFileListStr").value = "'. $updateFile .'";
		DownloadFileGet();
		DownloadNext();
		</script>

		<span class="font3_2">'. $verTimeStr .' 补丁包</span> -- 版本数据信息下载完成 &ensp;<br />正获取更新文件数据中...
		');
	}
}



// 步骤3_2：获取更新文件
function GetFile(){
	global $DB,$beforeURL,$adminURL,$sysAdminArr,$appID,$appType;

	$fileEndNum			= OT::GetInt('fileEndNum');
	$filePoint			= OT::GetInt('filePoint');
	$verID				= OT::GetInt('verID');
	$judUpdateLastFile	= OT::GetStr('judUpdateLastFile');

	$updateFileSize		= OT::PostInt('updateFileSize');
	$fileStr			= OT::PostStr('fileStr');
	$updateVerTheme		= OT::PostStr('updateVerTheme');
		if ($verID == 0){
			die(updateImg(3,'no') . $updateVerTheme .'<br />发送版本ID信息错误。<br />'. BtnCode('重新检测','updateStep1','load','check') .'');	
		}

	$infoArr = explode(',',$fileStr);
		if (count($infoArr)<3){
			die(updateImg(3,'no') . $updateVerTheme .'<br />发送更新文件信息规则不正确。<br />'. BtnCode('重新检测','updateStep1','load','check') .'');	
		}

	if ($judUpdateLastFile == 'true'){
		$DB->query('delete from '. OT_dbPref .'fileData where FD_verID='. $verID);
		echo('<script language="javascript" type="text/javascript">parent.judUpdateLastFile="false";</script>');
	}

	$verFileStr = ReqUrl::UseAuto($sysAdminArr['SA_getUrlMode'],'GET',$sysAdminArr['SA_updateUrl'] .'?mudi=downloadFile&OT_UPDATETIME='. OT_UPDATETIME .'&OT_VERSION='. OT_VERSION .'&dataVer='. OT_UPDATEVER .'&phpVer='. PHP_VERSION .'&OT_URL='. urlencode($beforeURL) .'&username='. $sysAdminArr['SA_username'] .'&softID='. $sysAdminArr['SA_softID'] .'&softCode='. $sysAdminArr['SA_softCode'] .'&domainID='. $sysAdminArr['SA_domainID'] .'&domainCode='. $sysAdminArr['SA_domainCode'] .'&fileID='. $infoArr[0] .'&fileRnd='. $infoArr[2] .'&appID='. $appID .'&appType='. $appType .'&appVerTime=', 'UTF-8', array(), 'note');
	if (strpos($verFileStr,'[OT'.'CMS]') === false){
		die($verFileStr);
	}

	$FD_mode			= Str::GetMark($verFileStr,'[field:FD_mode]','[/field:FD_mode]');
	$FD_filePath		= Str::GetMark($verFileStr,'[field:FD_filePath]','[/field:FD_filePath]');
	$FD_fileDir			= Str::GetMark($verFileStr,'[field:FD_fileDir]','[/field:FD_fileDir]');
	$FD_fileName		= Str::GetMark($verFileStr,'[field:FD_fileName]','[/field:FD_fileName]');
	$FD_fileExt			= Str::GetMark($verFileStr,'[field:FD_fileExt]','[/field:FD_fileExt]');
	$FD_fileSize		= intval(Str::GetMark($verFileStr,'[field:FD_fileSize]','[/field:FD_fileSize]'));
	$FD_content			= Str::GetMarkEnd($verFileStr,'[field:FD_content]','[/field:FD_content]');
	$FD_contentMd5		= Str::GetMark($verFileStr,'[field:FD_contentMd5]','[/field:FD_contentMd5]');
	$filePath			= Str::GetMark($verFileStr,'[file:path]','[/file:path]');
		if ($FD_mode=='' || $FD_fileName=='' || $FD_fileExt=='' || $FD_contentMd5=='' || ($FD_mode==1 && $filePath=='')){
			die(updateImg(3,'no') . $updateVerTheme .'<br />获取更新文件信息残缺（'. $FD_filePath .'）。<br /><input type="button" value="重新获取更新文件" onclick="DownloadFile()" />&ensp;&ensp;&ensp;&ensp;'. BtnCode('重新检测','updateStep1','load','check') .'');
		}

	$record = array();
	$record['FD_time']			= TimeDate::Get();
	$record['FD_verID']			= $verID;
	$record['FD_mode']			= $FD_mode;
	$record['FD_fileID']		= $infoArr[0];
	$record['FD_filePath']		= $FD_filePath;
	$record['FD_fileDir']		= $FD_fileDir;
	$record['FD_fileName']		= $FD_fileName;
	$record['FD_fileExt']		= $FD_fileExt;
	$record['FD_fileSize']		= $FD_fileSize;
	$record['FD_content']		= $FD_content;
	$record['FD_contentMd5']	= $FD_contentMd5;
	$record['FD_state']			= ($FD_mode==1 ? 0 : 1);
	$judResult = $DB->InsertParam('fileData',$record);

	$fileID = $DB->GetOne('select max(FD_ID) from '. OT_dbPref .'fileData');

	if ($FD_mode == 1){
		$SaveImg = new SaveImg();
		$srfArr = $SaveImg->SaveRemoteFile(OT_adminROOT . UpdateAdminDir . $fileID .'_'. $FD_contentMd5 .'.OTtpl', $filePath, '', 51200, 0);
		if (! $srfArr['res']){
			die(updateImg(3,'no') .'<b>'. $updateVerTheme .'</b><br />获取远程更新文件出错（'. $FD_filePath .'）['. $srfArr['note'] .']['. strlen($srfArr['note']) .']。<!-- '. UpdateAdminDir . $fileID .'_'. $FD_contentMd5 .'.OTtpl|'. $filePath .' --><br /><input type="button" value="重新获取更新文件" onclick="DownloadFile()" />&ensp;&ensp;&ensp;&ensp;'. BtnCode('重新检测','updateStep1','load','check') .'');
		}
		$DB->Update('fileData',array('FD_state'=>1),'FD_ID='. $fileID);
	}

	echo('
	<b>'. $updateVerTheme .'</b><br />总大小：'. File::SizeUnit($updateFileSize) .'，数量进度：'. OT::NumFormat($filePoint/$fileEndNum*100) .'%<br />共<span style="color:red;font-weight:bold;">'. $fileEndNum .'</span>个，正在下载第<span style="color:red;font-weight:bold;">'. $filePoint .'</span>个，大小'. File::SizeUnit(intval($infoArr[3])) .'<br /><span class="font3_2">'. $FD_filePath .'</span> -- 下载100%
	<div id="reGetBtn" style="display:none;"><input type="button" value="重新获取该更新文件" onclick="DownloadNext()" /></div>
	<script language="javascript" type="text/javascript">CalcGetSecond();DownloadNext();</script>
	');
}



// 运行升级过程
function RunUpdate(){
	global $DB,$beforeURL,$adminURL,$sysAdminArr,$appID,$appType;

	$verID				= OT::GetInt('verID');
	$runFileSkipStr		= OT::PostStr('runFileSkipStr');
	$updateEventStr		= OT::PostStr('updateEventStr');

	$totalFailNum		= 0;	// 总错误文件数
	$updateOldVerTime	= '';	// 旧升级版本时间
	$updateNewVer		= '';	// 新升级版本
	$updateNewVerTime	= '';	// 新升级版本时间
	$updateFileNum		= 0;	// 升级文件数
	if ($verID <= 0){
		die(updateImg(4,'no') .'获取版本ID出错('. $verID .')。<br />'. BtnCode('重新连接检测','updateStep1','load','check') .'&ensp;&ensp;&ensp;&ensp;<input type="button" value="重新运行升级过程" onclick="RunUpdateStep4()" />&ensp;&ensp;&ensp;&ensp;<input type="button" value="关闭" onclick="CheckUpdateBox()" />');

	}else{
		$verexe = $DB->query('select FV_type,FV_ver,FV_verTimeStart,FV_verTime,FV_fileNum,FV_runFile,FV_isRunCode from '. OT_dbPref .'fileVer where FV_ID='. $verID);
			if (! $row = $verexe->fetch()){
				die(updateImg(4,'no') .'获取版本ID出错('. $verID .')。<br />'. BtnCode('重新连接检测','updateStep1','load','check') .'&ensp;&ensp;&ensp;&ensp;<input type="button" value="重新运行升级过程" onclick="RunUpdateStep4()" />&ensp;&ensp;&ensp;&ensp;<input type="button" value="关闭" onclick="CheckUpdateBox()" />');
			}
			$updateOldVerTime	= $row['FV_verTimeStart'];
			$updateNewVer		= $row['FV_ver'];
			$updateNewVerTime	= $row['FV_verTime'];
			$updateFileNum		= $row['FV_fileNum'];

			$fileexe = $DB->query('select * from '. OT_dbPref .'fileData where FD_verID='. $verID .' and FD_mode=10');
				while ($row2 = $fileexe->fetch()){
					$newFilePath = $row2['FD_filePath'];
					$newFilePath = OT_adminROOT . substr($newFilePath,6);

					if (! File::Write($newFilePath, $row2['FD_content'])){
						$FD_state = 0;
					}
				}
			unset($fileexe);

			if ($row['FV_isRunCode'] == 1){
				$runNum = 0;
				$infoNum = $DB->GetOne('select count(IF_ID) from '. OT_dbPref .'info');
				$runFileArr = explode('[arr]',$row['FV_runFile']);
				$runCount = count($runFileArr);
				foreach ($runFileArr as $runFile){
					if ($runFile != ''){
						$runNum ++;
						$retMode = 'return'; // 传递到执行脚本的变量
						if ($infoNum > 0){	// 8000
							$runFileResult = '切换为更稳妥的脚本运行方式';
						}else{
							if (! $runFileResult = @include(OT_adminROOT . $runFile)){
								$runFileResult = '';
							}
						}

						$runFileRnd = OT::RndNumTo(1000,9999);
						// $runFileResult = trim(ReqUrl::UseAuto($sysAdminArr['SA_getUrlMode'], 'GET', $adminURL . $runFile .'?runMode=back&retMode=return&rndNum='. $runFileRnd, 'UTF-8', array(), 'note'));
						if (strpos($runFileResult,'插件尚未购买') !== false){
							echo('点击【重新运行升级脚本】按钮试试！<iframe id="updateAppDeal" name="updateAppDeal" src="appShop_deal.php?mudi=getInfo&mode=no" width="0" height="0" style="display:none"></iframe>');
						}
						if (strpos($runFileResult,'OK')!==false || strpos($runFileSkipStr,'|'. $runFile .'|')!==false){
							File::Del($runFile);
						}else{
							die(updateImg(4,'no') .'
							该升级脚本共<span style="color:red;font-weight:bold;font-size:16px;">'. $runCount .'</span>个，当前正在运行第<span style="color:red;font-weight:bold;font-size:16px;">'. $runNum .'</span>个（'. $runFile .'）(<span class="font3_2">'. Str::MoreReplace(Str::RegExp($runFileResult,'html'),'js') .'</span>)。<br />
							<iframe width="99%" hei'.'ght="120" style="width:99%;hei'.'ght:120px;border:1px #e1e1e1 solid;" frameborder="0" scrolling="no" allowtransparency="true" src="'. $adminURL . $runFile .'?runMode=window&rndCode='. $runFileRnd .'"></iframe>
							<div style="margin:5px 0 8px 0;padding:8px; line-height:1.5; background:#fcf6e5; border:1px #f6e7c2 solid;">1、如上方显示“该插件尚未购买或下载”，请点击[插件平台]右侧【更新已购插件信息】按钮，然后点击下方【重新运行升级过程】 按钮。<br />2、如上方没显示出 [4位数字校验码] 来，请点击<a href="'. $adminURL . $runFile .'?runMode=window&rndCode='. $runFileRnd .'" target="_blank" class="font2_2">【重新运行升级脚本】</a>，如提示升级成功并出现 [4位数字校验码]，请填入该框 校验码:<input type="hidden" id="runFileName" name="runFileName" value="'. $runFile .'" /><input type="hidden" id="trueRndCode" name="trueRndCode" value="'. $runFileRnd .'" /><input type="input" id="runRndCode" name="runRndCode" value="" style="width:50px;" maxlength="4" onkeyup="CheckRunRndCode();" /><span id="rndCodeAlert"></span>，然后点击 【重新运行升级过程】 按钮。</div>
							'. BtnCode('重新连接检测','updateStep1','load','check') .'&ensp;&ensp;<input type="button" value="重新运行升级过程（'. $runNum .'/'. $runCount .'）" onclick="CheckUpdateStep4()" />&ensp;&ensp;<input type="button" value="关闭" onclick="CheckUpdateBox()" />&ensp;&ensp;
							');
						}
					}
				}
			}

			$failNum = 0;
			$failFileListStr = '';
			$retFailFileListStr = '';
			$fileexe = $DB->query('select * from '. OT_dbPref .'fileData where FD_verID='. $verID .' and FD_mode<10 and FD_state=1');
				while ($row2 = $fileexe->fetch()){
					$FD_state = 1;
					$errStr = '';
					$newFilePath = $row2['FD_filePath'];
					if (substr($newFilePath,0,6) == 'admin/'){
						$newFilePath = OT_adminROOT . substr($newFilePath,6);
					}elseif (substr($newFilePath,0,5) == 'Data/'){
						$newFilePath = OT_ROOT . str_replace('Data/',OT_dbDir,$newFilePath);
					}else{
						$newFilePath = OT_ROOT . $newFilePath;
					}
					if ($row2['FD_mode'] == 1){
						$downFilePath = OT_adminROOT .'upFile/updateVer/'. $row2['FD_ID'] .'_'. $row2['FD_contentMd5'] .'.OTtpl';
						if (! File::IsExists($downFilePath)){
							$FD_state = 0;
							$errStr = '(文件没找到)';
						}else{
							if (! File::Copy($downFilePath,$newFilePath)){
								$FD_state = 0;
								$errStr = '(文件复制失败)';
							}else{
								File::Del($downFilePath);
							}
						}
					}else{
						if (! File::Write($newFilePath,$row2['FD_content'])){
							$FD_state = 0;
							$errStr = '(文件写入失败)';
						}
					}
					if ($FD_state == 0){
						$failNum ++;
						$failFileListStr .= '<br />'. $row2['FD_filePath'] .' '. $errStr;
						$retFailFileListStr .= '|'. $row2['FD_filePath'];
					}
//					$DB->query('update '. OT_dbPref .'fileData set FD_state='. $FD_state .' where FD_ID='. $row2['FD_ID']);
					if ($FD_state == 1){
						$DB->query('delete from '. OT_dbPref .'fileData where FD_ID='. $row2['FD_ID']);
					}
				}
			unset($fileexe);
			$totalFailNum += $failNum;

			$DB->query('update '. OT_dbPref .'paySoft set PS_updateNum=PS_updateNum+1 where PS_appID='. $row['FV_type']);
			$DB->query('update '. OT_dbPref .'fileVer set FV_isRunCode=1,FV_failNum='. $failNum .',FV_isLock=1 where FV_ID='. $verID);
		unset($verexe);
	}

	$verStr = '';
	$alertStr = '';
	if (strpos($updateEventStr,'|compressDB|') !== false){ $alertStr .= '，该升级可能出现数据库占用大小虚增，建议<a href="javascript:void(0);" onclick=\'parent.HrefTo("","数据库压缩","database.php?mudi=compress","999996");return false;\' style="color:red;">压缩数据库</a>'; }

	$retIframeStr = '';
	$resultUrlStr = $sysAdminArr['SA_updateUrl'] .'?mudi=getResult&OT_UPDATETIME='. OT_UPDATETIME .'&OT_VERSION='. OT_VERSION .'&dataVer='. OT_UPDATEVER .'&phpVer='. PHP_VERSION .'&OT_URL='. urlencode($beforeURL) .'&OT_WebName='. urlencode(substr($DB->GetOne('select SYS_title from '. OT_dbPref .'system'),0,16)) .'&username='. $sysAdminArr['SA_username'] .'&softID='. $sysAdminArr['SA_softID'] .'&softCode='. $sysAdminArr['SA_softCode'] .'&domainID='. $sysAdminArr['SA_domainID'] .'&domainCode='. $sysAdminArr['SA_domainCode'] .'&verStr='. $verStr .'&updateFileNum='. $updateFileNum .'&updateOldVerTime='. $updateOldVerTime .'&updateNewVer='. $updateNewVer .'&updateNewVerTime='. $updateNewVerTime .'&updateEventStr='. $updateEventStr .'&retFailFileListStr='. urlencode($totalFailNum . $retFailFileListStr) .'&appID='. $appID .'&appType='. $appType .'&appVerTime=';
	$resultStr = ReqUrl::UseAuto($sysAdminArr['SA_getUrlMode'], 'GET', $resultUrlStr, 'UTF-8', array(), 'note');
	if (strpos(trim($resultStr),'[OT'.'CMS]') === false){
		$resultStr = '';
		$retIframeStr = '<iframe width="1" hei'.'ght="1" frameborder="0" scrolling="no" style="display:none;" src="'. $resultUrlStr .'"></iframe>';
	}

	$addiStr1 = '';
	$addiJsStr1 = '';
	if ($totalFailNum == 0){
		$addiStr1 = '';
		$addiJsStr1 = 'parent.$id("updateV2Step5").innerHTML=\'<img src="images/img_yes.gif" alt="升级完毕" />\';';
	}else{
		$addiStr1 = '，共有<span style="color:red;">'. $totalFailNum .'</span>个文件更新出错'. $failFileListStr;
		$addiJsStr1 = 'parent.$id("updateV2Step5").innerHTML=\'<img src="images/img_err.gif" alt="个别更新文件更新不上去" />\';';
	}
	echo('
	'. $retIframeStr .'升级完毕'. $alertStr . $addiStr1 .'。请刷新后台页面下才能看到升级后效果。'. $resultStr .'<br />
	');
	if ($appID == 0){
		echo('<input type="button" value="升级完成" onclick="parent.parent.document.location.reload();" />');
	}else{
		echo('<input type="button" value="升级完成" onclick="parent.window.close();" />');
	}
	
	echo('
	<iframe id="updateAppDeal" name="updateAppDeal" src="appShop_deal.php?mudi=getInfo&mode=no" width="0" height="0" style="display:none"></iframe>

	<script language="javascript" type="text/javascript">
	parent.$id("updateV2Step4").innerHTML=\'<img src="images/img_yes.gif" alt="升级完毕" />\';
	'. $addiJsStr1 .'
	parent.WindowHeight(0);
	</script>
	');
}



function updateImg($stepNum, $imgMode){
	if ($imgMode == 'yes'){
		$imgStr = '<img src="images/img_yes.gif" alt="连接读取完毕" />';
	}elseif ($imgMode == 'no'){
		$imgStr = '<img src="images/img_err.gif" alt="连接读取错误" />';
	}else{
		$imgStr = '<img src="images/onload.gif" alt="连接读取中" />';
	}

	return '<script language="javascript" type="text/javascript">parent.$id("updateV2Step'. $stepNum .'").innerHTML=\''. $imgStr .'\';</script>';
}


function BtnCode($btnName,$stepNname,$imgMode,$mudiStr){
	$clickStr = '';
	if ($mudiStr == 'check'){
		$clickStr='StartStep1("'. $imgMode .'");';
	}elseif ($mudiStr == 'backup'){
		$clickStr='StartStep2("'. $imgMode .'");';
	}elseif ($mudiStr == 'getFile'){
		$clickStr='StartStep3("'. $imgMode .'");';
	}
//	if (strpos($eventDeal,'|backup|') !== false){ $clickStr='if (confirm("您确定已备份好网站数据库了？（如没有请先备份数据库，再点击[确定]。）")){'. $clickStr .'}';
	return '<input type="button" value="'. $btnName .'" onclick=\''. $clickStr .'\' />';
}

?>
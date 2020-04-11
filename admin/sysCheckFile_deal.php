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

$MB->IsAdminRight('alertBack');


switch($mudi){
	case 'checkFile':
		CheckFile();
		break;

	case 'revLimit':
		RevLimit();
		break;

	case 'testPhpRun':
		TestPhpRun();
		break;

	case 'webConfigDeal':
		WebConfigDeal();
		break;

	case 'checkSoftDir':
		CheckSoftDir();
		break;

	case 'checkUpFilesDir':
		CheckUpFilesDir();
		break;

	case 'checkSoftDirFile':
		CheckSoftDirFile();
		break;

	case 'calcSiteSize':
		CalcSiteSize();
		break;

	case 'db':
		DbDeal();
		break;

	case 'sql':
		SqlDeal();
		break;

	case 'sqlMore':
		SqlMoreDeal();
		break;

	case 'optionFile':
		OptionFile();
		break;

	case 'del':
		Del();
		break;

	case 'upFilesLook':
		UpFilesLook();
		break;

	case 'upFilesDel':
		UpFilesDel();
		break;

	default:
		die('err');
}

$MB->Close();
$DB->Close();




// 程序文件对比
function CheckFile(){
	global $DB,$skin,$mudi,$menuFileID,$menuTreeID;

	$backURL		= OT::PostStr('backURL');
	$dataType		= OT::PostStr('dataType');
	$dataTypeCN		= OT::PostStr('dataTypeCN');

	$fileStart		= OT::PostInt('fileStart');
	$fileStep		= OT::PostInt('fileStep');
	$fileTotal		= OT::PostInt('fileTotal');
	$fileData		= OT::PostStr('fileData');
	$fileArr = explode('[arr]', $fileData);
//echo('1'. $fileStart .'|'. $fileStep .'|'. $fileTotal .'|'. $fileData);
	$fileEnd = $fileStart+$fileStep;
	for ($i=$fileStart; $i<$fileEnd; $i++){
		if ($i > $fileTotal){ break; }
/*		$pathInfo	= OT::PostStr('pathinfo'. $i);
		$sizeinfo	= OT::PostInt('sizeinfo'. $i);
		$md5info	= OT::PostStr('md5info'. $i);
		$sha1info	= OT::PostStr('sha1info'. $i);*/
//echo('2'. $pathInfo .'|'. $sizeinfo .'|'. $md5info .'|'. $sha1info .'|');
		$oneArr = explode('	', $fileArr[$i-1]);
		$pathInfo	= $oneArr[0];
		$sizeinfo	= $oneArr[1];
		$md5info	= $oneArr[2];
		$sha1info	= $oneArr[3];
		if (count($oneArr) >= 5){ $symd5info = $oneArr[4]; }else{ $symd5info = ''; }

		$chkFilePath = OT_ROOT . $pathInfo;
		if ($md5info == ''){
			if (! file_exists($chkFilePath)){
				echo('
				$id("result'. $i .'").innerHTML=\'<span style="color:red;">文件不存在</span>\';errFileNum++;
				$id("errFile'. $i .'").value="1";
				');
			}else{
				$fileSize = filesize($chkFilePath);
				if ($fileSize == $sizeinfo){
					echo('
					$id("result'. $i .'").innerHTML=\'<span style="color:green;">文件存在，大小一致</span>\';existFileNum++;
					$id("state'. $i .'").value="0";
					// $id("data'. $i .'").style.display="none";
					');
				}else{
					echo('
					$id("result'. $i .'").innerHTML=\'<span style="color:red;" title="当前文件：'. $fileSize .',原版文件：'. $sizeinfo .'">文件存在，大小不一('. $fileSize .')</span>\';errFileNum++;
					$id("errFile'. $i .'").value="1";
					');
				}
			}
		}else{
			if (! file_exists($chkFilePath)){
				echo('
				$id("result'. $i .'").innerHTML=\'<span style="color:red;">文件不存在</span>\';errFileNum++;
				$id("errFile'. $i .'").value="1";
				');
			}else{
				$fileMd5Str = @md5_file($chkFilePath);
				if ($fileMd5Str == $md5info){
					if ($sha1info == @sha1_file($chkFilePath)){
						echo('
						$id("result'. $i .'").innerHTML=\'<span style="color:green;">完全匹配</span>\';okFileNum++;
						$id("state'. $i .'").value="0";
						// $id("data'. $i .'").style.display="none";
						');
					}else{
						$fileSize = filesize($chkFilePath);
						if ($fileSize == $sizeinfo){
							echo('
							$id("result'. $i .'").innerHTML=\'<span style="color:green;">md5匹配,sha1不匹配,大小一致</span>\';existFileNum++;
							$id("state'. $i .'").value="0";
							');
						}else{
							echo('
							$id("result'. $i .'").innerHTML=\'<span style="color:red;" title="当前文件：'. $fileSize .',原版文件：'. $sizeinfo .'">md5匹配,sha1不匹配,大小不一致</span>\';errFileNum++;
							$id("errFile'. $i .'").value="1";
							');
						}
					}
				}elseif (strlen($symd5info) > 0 && $symd5info == md5(str_replace(array("\r","\n"),'',File::Read($chkFilePath)))){
					echo('
					$id("result'. $i .'").innerHTML=\'<span style="color:green;">完全匹配2</span>\';okFileNum++;
					$id("state'. $i .'").value="0";
					// $id("data'. $i .'").style.display="none";
					');
				}else{
					$fileSize = filesize($chkFilePath);
					if ($fileSize == $sizeinfo){
						echo('
						$id("result'. $i .'").innerHTML=\'<span style="color:#000000;" title="当前文件：'. $fileMd5Str .',原版文件：'. $md5info .'">内容不匹配，但大小一致</span>\';errFileNum++;
						$id("errFile'. $i .'").value="1";
						');
					}else{
						if ($sha1info == sha1_file($chkFilePath)){
							echo('
							$id("result'. $i .'").innerHTML=\'<span style="color:green;">md5值不匹配，sha1值匹配</span>\';okFileNum++;
							$id("state'. $i .'").value="0";
							');
						}else{
							echo('
							$id("result'. $i .'").innerHTML=\'<span style="color:red;">文件被改过或挂马</span>\';errFileNum++;
							$id("errFile'. $i .'").value="1";
							');
						}
					}
				}
			}
		}
	}

}



// 检查文件权限
function RevLimit(){
	global $DB,$skin,$mudi,$menuFileID,$menuTreeID;

	$backURL		= OT::PostStr('backURL');
	$dataType		= OT::PostStr('dataType');
	$dataTypeCN		= OT::PostStr('dataTypeCN');

	$newLimitNum		= OT::PostInt('newLimitNum');
	$limitFileList		= OT::PostStr('limitFileList');

	$fileArr = explode('[arr]', $limitFileList);
	$fileCount = count($fileArr);
	$succNum = $failNum = $existNum = 0;

	for ($i=0; $i<$fileCount; $i++){
		if (file_exists($fileArr[$i])){
			if (@chmod($fileArr[$i], substr('0000'. $newLimitNum,-4))){
				$succNum ++;
			}else{
				$existNum ++;
			}
		}else{
			$failNum ++;
		}
	}

	JS::AlertHrefEnd('修改权限值完毕(成功'. $succNum .'个，失败'. $failNum .'个，不存在'. $existNum .'个)', $backURL);
}



// Window目录权限 - 目录权限检查
function TestPhpRun(){
	global $DB,$skin,$mudi,$menuFileID,$menuTreeID;

	$dataType		= OT::GetStr('dataType');
	$dataTypeCN		= OT::GetStr('dataTypeCN');
	$mudi2			= OT::GetStr('mudi2');
	$fileType		= OT::GetStr('fileType');
		$dataArr = array(
			'cache','cache_html','cache_js','cache_php','cache_session','cache_smarty','cache_taobao','cache_web',
			'upFiles','upFiles_download','upFiles_images','upFiles_infoImg','upFiles_product','upFiles_users',
			'wap_cache'
			);
		if (! in_array($fileType,$dataArr)){
			JS::AlertEnd('fileType目的不明确（'. $fileType .'）');
		}

	$dir = str_replace('_','/',$fileType) .'/';
	switch ($mudi2){
		case 'run':
			$resArr = OT::TestPhpRun(OT_ROOT . $dir, GetUrl::CurrDir(1));
			if ($resArr['res'] == false && $resArr['code'] < 9){
				JS::AlertEnd($resArr['note']);
			}elseif ($resArr['res']){
				JS::AlertEnd('警告！该目录（'. $dir .'）有执行权限，建议关闭掉该目录执行权限。');
			}else{
				JS::AlertEnd('恭喜！该目录（'. $dir .'）没有执行权限。');
			}
			break;
	
		case 'create':
			$resArr = OT::TestPhpRun(OT_ROOT . $dir, GetUrl::CurrDir(1), '|noCheck|noDel|');
			if ($resArr['res'] == false && $resArr['code'] < 9){
				JS::AlertEnd($resArr['note']);
			}elseif ($resArr['res']){
				JS::AlertEnd('恭喜！测试文件创建成功，\r\n请点击【访问测试文件】按钮，最后点击【删除测试文件】按钮。');
			}else{
				JS::AlertEnd('出错！测试文件创建失败。');
			}
			break;
	
		case 'del':
			$resJud = File::Del(OT_ROOT . $dir .'testOtcmsRun.php');
			if ($resJud){
				JS::AlertEnd('删除成功。');
			}else{
				JS::AlertEnd('删除失败。');
			}
			break;
	
		default :
			JS::AlertEnd('mudi2目的不明确（'. $mudi2 .'）');
			break;
	}
}


// Window目录权限 - IIS环境 取消执行权限配置文件 列表
function WebConfigDeal(){
	global $DB,$skin,$mudi,$menuFileID,$menuTreeID;

	$dataType		= OT::GetStr('dataType');
	$dataTypeCN		= OT::GetStr('dataTypeCN');
	$mudi2			= OT::GetStr('mudi2');
	$fileType		= urldecode(OT::GetStr('fileType'));

	$dataArr = array('admin/images', 'admin/js', 'admin/temp', 'admin/tools', 'admin/upFile', 'cache', 'Data', 'Data_backup', 'html', 'inc_img', 'js', 'pay', 'pluDef', 'plugin', 'smarty', 'temp', 'template', 'tools', 'upFiles', 'web_config', 'wap/cache', 'wap/html', 'wap/images', 'wap/js', 'wap/skin', 'wap/template', 'wap/tools');
		if (! in_array($fileType,$dataArr)){
			JS::AlertEnd('fileType目的不明确（'. $fileType .'）');
		}
	if ($fileType == 'Data'){
		$filePath = OT_ROOT . OT_dbDir .'web.config';
	}elseif ($fileType == 'Data_backup'){
		$filePath = OT_ROOT . OT_dbBakDir .'web.config';
	}elseif (strpos($fileType,'admin/') !== false){
		$filePath = str_replace('admin/',OT_adminROOT,$fileType) .'/web.config';
	}else{
		$filePath = OT_ROOT . $fileType .'/web.config';
	}

	switch ($mudi2){
		case 'create':
			$fileData = '<?xml version="1.0" encoding="UTF-8"?>'. PHP_EOL .
						'<configuration>'. PHP_EOL .
						'	<system.webServer>'. PHP_EOL .
						'		<handlers accessPolicy="Read" />'. PHP_EOL .
						'	</system.webServer>'. PHP_EOL .
						'</configuration>'. PHP_EOL .
						'';
			$judRes = File::Write($filePath, $fileData, false, $errStr);
			if ($judRes){
				JS::AlertEnd('恭喜！创建配置文件（web.config）成功。');
			}else{
				JS::AlertEnd('创建失败，原因：'. $errStr);
			}
			break;
	
		case 'del':
			$resJud = File::Del($filePath);
			if ($resJud){
				JS::AlertEnd('删除成功。');
			}else{
				JS::AlertEnd('删除失败。');
			}
			break;
	
		default :
			JS::AlertEnd('mudi2目的不明确（'. $mudi2 .'）');
			break;
	}
}


// 检查异常文件
function CheckSoftDir(){
	global $DB,$skin,$mudi,$menuFileID,$menuTreeID;

	$adminURL	= GetUrl::CurrDir();
	$beforeURL	= GetUrl::CurrDir(1);
	$adminDirName = substr($adminURL,strlen($beforeURL),-1);

	if (OT_Database == 'mysql'){
		$softDirList = '|Data|';
	}else{
		$softDirList = '|'. substr(OT_dbDir,0,-1) .'|';
	}
	$softDirList .= ''. $adminDirName .'|'. substr(OT_dbBakDir,0,-1) .'|cache|go|goods|inc|inc_img|install|js|news|pay|pluDef|plugin|smarty|temp|template|tools|upFiles|wap|message|web_config|';

	$infoTypeDirList='|announ||new|';
	$checkexe=$DB->query('select IT_htmlName from '. OT_dbPref .'infoType');
	while ($row = $checkexe->fetch()){
		if (strlen($row['IT_htmlName']) > 0){ $infoTypeDirList .= '|'. $row['IT_htmlName'] .'|'; }
	}
	unset($checkexe);


	$folderI = 0;

	echo('
	<div style="color:red;padding:8px 0;">检查结果：</div>
	<table cellpadding="0" cellspacing="0" class="tabList1 padd5td">
	<tr><td>目录名</td><td>大小</td><td>创建时间</td><td>最后修改时间</td><td>状态</td></tr>
	');

	$SYS_htmlUrlDir = $DB->GetOne('select SYS_htmlUrlDir from '. OT_dbPref .'system');
	if ($handle = opendir(OT_ROOT)) {
		while (($file = readdir($handle)) !== false) {
			if ($file != '.' && $file != '..' && is_dir(OT_ROOT . $file)) {
				$folderI ++;
				if (strpos($softDirList,'|'. $file .'|') === false){
					$stateStr = '<span style="color:red;">非程序目录</span>';
					if (strpos($infoTypeDirList,'|'. $file .'|') !== false){
						$stateStr = '<span style="color:blue;">疑似栏目静态目录</span>';
					}elseif ($SYS_htmlUrlDir == $file){
						$stateStr = '<span style="color:green;">疑似纯静态目录</span>';
					}elseif (substr($file,0,8) == 'install.'){
						$stateStr = '<span style="color:red;">疑似安装向导目录</span>';
					}
					echo('
					<tr>
						<td>'. Str::GB2UTF($file) .'</td>
						<td>'. File::SizeUnit(filesize(OT_ROOT . $file)) .'</td>
						<td>'. File::GetCreateTime(OT_ROOT . $file) .'</td>
						<td>'. File::GetRevTime(OT_ROOT . $file) .'</td>
						<td>'. $stateStr .'</td>
					</tr>
					');
				}
			}
		}
	}
	closedir($handle);

	echo('
	</table>
	');

}


// upFiles/目录图片木马检查
function CheckUpFilesDir(){
	global $DB,$skin,$mudi,$menuFileID,$menuTreeID;

	$folderI = 0;

	echo('
	<div style="color:red;padding:8px 0;">检查结果：</div>
	<table cellpadding="0" cellspacing="0" class="tabList1 padd5td">
	<tr><td>文件名</td><td>大小</td><td>创建时间</td><td>最后修改时间</td><td>状态</td><td align="center">操作</td></tr>
	');

	$dirPath = OT_ROOT .'upFiles/';
	if ($handle = opendir($dirPath)) {
		while (($file = readdir($handle)) !== false) {
			if ($file != '.' && $file != '..' && is_dir($dirPath . $file)) {
				$folderI ++;
				$retTab2Str = GetDirAndFileImgList(OT_ROOT .'upFiles/'. $file .'/', '', $file);
				if (strlen($retTab2Str) > 3){
					$retTab2Str = '<tr><td colspan="6"><b>'. $file .'/ 目录</b></td></tr>'. $retTab2Str;
				}
				echo($retTab2Str);
			}
		}
	}
	closedir($handle);
	
	echo('
	</table>
	');

}


// 检查目录异常文件
function CheckSoftDirFile(){
	global $DB,$skin,$mudi,$menuFileID,$menuTreeID;

	$dirName	= OT::GetStr('dirName');

	$dirTitle = $dirName;
	$forePath = OT_ROOT . $dirName .'/';
//	$extList = '/html/htm/xml/js/css/bmp/jpg/jpeg/gif/png/tif/tiff/swf/doc/xls/txt/ppt/docx/xlsx/pptx/pdf/rar/zip/avi/mpeg/mpg/ra/rm/rmvb/mov/qt/asf/wmv/iso/bin/img/mp3/wma/wav/mod/cd/md/aac/mid/ogg/m4a/';
	$extList = '';
	$fileList='';
	switch ($dirName){
		case 'before':
			$dirTitle = '前台目录';
			$forePath = OT_ROOT;
			$extList = '/xml/';
			$fileList='|.htaccess|404.html|404.php|404gy.html|404gy.php|api.php|apiSuccess.php|apiWeb.php|appJs.php|autoRun.php|check.php|collRun2.php|config.php|configVer.php|conobj.php|deal.php|deal_js.php|form.php|gift.php|go.php|goodsDeal.php|goodsRead.php|idcPro.php|index.php|index.html|job.php|logoAdd.php|makeHtml_deal.php|makeHtml_run.php|makeHtml_runWap.php|message.php|news_deal.php|outCall.php|pay.php|pay_deal.php|payReturn.php|payServer.php|payWeb.php|plugin_deal.php|qrcode.php|read.php|readDeal2.php|readSoft.php|rss.php|selWapPc.php|url.php|users.php|users_deal.php|usersCenter.php|usersNews_deal.php|usersNewsUpFile.php|usersNewsUpImg.php|favicon.ico|httpd.ini|robots.txt|sitemap.html|历史更新记录.txt|使用教程【必看】.txt|apiRun.php|nginx.conf|usersCenter_deal.php|';
			break;

		case 'admin':
			$dirTitle = '后台目录';
			$forePath = OT_adminROOT;
			$extList = '/html/htm/xml/js/css/svg/bmp/jpg/jpeg/gif/png/swf/doc/xls/txt/ppt/docx/xlsx/pptx/pdf/pack/md/htc/ttf/';
			$fileList='|admin.php|adminMenu.php|adminMenu_deal.php|admin_cl.php|appShop.php|appShop_deal.php|autoRunLog.php|autoRunLog_deal.php|autoRunSys.php|autoRunSys_deal.php|banner.php|banner_deal.php|buyOrders.php|buyOrders_deal.php|ca.php|ca_deal.php|check.php|collConobj.php|collDatabase.php|collDatabase_deal.php|collHistory.php|collHistory_deal.php|collItem.php|collItem_deal.php|collResult.php|collResult_deal.php|collRun.php|collRun2.php|collType.php|collType_deal.php|dashang.php|dashang_deal.php|dbBak.php|dbBakMySQL.php|dbBakMySQL_deal.php|dbBak_deal.php|dbErr.php|dbErr_deal.php|extCheck.php|formType.php|formType_deal.php|formUsers.php|formUsers_deal.php|giftData.php|giftData_deal.php|giftSys.php|giftSys_deal.php|giftUsers.php|giftUsers_deal.php|goUrl.php|goUrl_deal.php|idcProData.php|idcProData_deal.php|idcProSys.php|idcProSys_deal.php|idcProType.php|idcProType_deal.php|index.php|ind_backstage.php|info.php|infoMessage.php|infoMessage_deal.php|infoMove.php|infoMove_deal.php|infoSys.php|infoSys_deal.php|infoType.php|infoType_deal.php|infoWeb.php|infoWeb_deal.php|info_deal.php|info_upBigFile.php|info_upBigFile_deal.php|info_upFile.php|info_upImg.php|ipRecord.php|ipRecord_deal.php|job.php|jobUsers.php|jobUsers_deal.php|job_deal.php|keyWord.php|keyWord_deal.php|left_menu.php|left_menuNote.php|mail.php|mailInfo.php|mailInfo_deal.php|mailOthers.php|mailTpl.php|mailTpl_deal.php|mail_deal.php|makeDiy.php|makeDiy_deal.php|makeHtml.php|makeHtml_deal.php|makeHtml_run.php|makeHtml_runWap.php|member.php|memberGroup.php|memberGroup_deal.php|memberLog.php|memberLog_deal.php|memberOnline.php|memberOnline_deal.php|memberRight.php|memberRight_deal.php|member_deal.php|message.php|message_deal.php|moneyPay.php|moneyPay_deal.php|moneyRecord.php|moneyRecord_deal.php|moneySys.php|moneySys_deal.php|outCall.php|outCall_deal.php|phone.php|phoneOthers.php|phoneSys.php|phoneSys_deal.php|phoneTpl.php|phoneTpl_deal.php|phone_deal.php|qiandaoSys.php|qiandaoSys_deal.php|quanData.php|quanData_deal.php|quanSys.php|quanSys_deal.php|read.php|readDeal.php|readDeal2.php|readTaoke.php|serverFile.php|serverFile_deal.php|share_switch.php|siteMap.php|siteMap_deal.php|softBak.php|softBak_deal.php|sysAdmin.php|sysAdmin_deal.php|sysCheckFile.php|sysCheckFile_deal.php|sysCheckFile_recur.php|sysImages.php|sysImages_deal.php|system.php|system_deal.php|taokeGoods.php|taokeGoods_deal.php|taokeGoods_outDeal.php|taokeItem.php|taokeItem_deal.php|taokeOrder.php|taokeOrderUsers.php|taokeOrderUsers_deal.php|taokeOrder_deal.php|taokeOut.php|taokeOut_deal.php|taokeReport.php|taokeSys.php|taokeSys_deal.php|taokeWord.php|taokeWord_deal.php|template.php|template_deal.php|tplSys.php|tplSys_deal.php|type.php|type_deal.php|update.php|updateV2.php|update_deal.php|userApi.php|userApi_deal.php|userFile.php|userFile_deal.php|userGroup.php|userGroup_deal.php|userLevel.php|userLevel_deal.php|userMoney.php|userMoney_deal.php|users.php|userScore.php|userScore_deal.php|userSys.php|userSys_deal.php|users_deal.php|vote.php|vote_deal.php|wap.php|wap_deal.php|weixinAdmin.php|weixinFormType.php|weixinFormType_deal.php|weixinFormUsers.php|weixinFormUsers_deal.php|weixinInfo.php|weixinInfo_deal.php|weixinLog.php|weixinLog_deal.php|weixinMenu.php|weixinMenu_deal.php|weixinNews.php|weixinNews_deal.php|weixinOther.php|weixinOther_deal.php|weixinReply.php|weixinReply_deal.php|weixinSys.php|weixinSys_deal.php|weixinTpl.php|weixinTplSend.php|weixinTplSend_deal.php|weixinTpl_deal.php|weixinUsers.php|weixinUsers_deal.php|actSys.php|actSys_deal.php|bbsData.php|bbsData_deal.php|bbsReply.php|bbsReply_deal.php|bbsSys.php|bbsSys_deal.php|collSys.php|collSys_deal.php|appSys.php|appSys_deal.php|userLog.php|userLog_deal.php|editFile.php|editFile_deal.php|gainHistory.php|gainHistory_deal.php|gainItem.php|gainItem_deal.php|gainMoney.php|gainMoney_deal.php|moneyGive.php|moneyGive_deal.php|workData.php|workData_deal.php|workUsers.php|workUsers_deal.php|idcProGroup.php|idcProGroup_deal.php|'.
			'images/web.config|js/web.config|temp/web.config|tools/web.config|upFile/web.config|'.
			'inc/classAd.php|inc/classAdm.php|inc/classAdmArea.php|inc/classIdcProType.php|inc/classInfoType.php|inc/classMember.php|inc/classServerFile.php|inc/classSkin.php|inc/classStrArr.php|inc/VerCode/VerCode1.php|inc/VerCode/VerCode2.php|inc/VerCode/VerCode3.php|inc/VerCode/VerCode4.php|'.
			'tools/submitShow.inc|';
			break;

		case 'Data':
			$dirTitle = '数据库目录';
			$forePath = OT_ROOT . OT_dbDir;
			$extList = '/db/';
			$fileList='|softErr.log|web.config|';
			break;

		case 'Data_backup':
			$dirTitle = '数据库备份目录';
			$forePath = OT_ROOT . OT_dbBakDir;
			$extList = '/txt/bak/zip/';
			$fileList='|web.config|';
			break;

		case 'cache':
			$extList = '/txt/html/js/log/css/xml/lock/';
			$fileList='|web.config|php/actSys.php|php/autoRunSys.php|php/bbsSys.php|php/buySys.php|php/giftSys.php|php/idcProSys.php|php/images.php|php/infoSys.php|php/moneySys.php|php/paySoft.php|php/phoneSys.php|php/qiandaoSys.php|php/quanSys.php|php/sysAdmin.php|php/sysImages.php|php/system.php|php/taokeSys.php|php/tplSys.php|php/userApi.php|php/userGroup.php|php/userSys.php|php/vpsSys.php|php/wap.php|php/weixinSys.php|php/wx_access_token.php|php/wx_jsapi_ticket.php|php/wx_siteAccessToken.php|php/wxTaoke_qiandao.php|php/wxTaoke_qiandao_arr.php|php/wxTaoke_qiandao.php|php/appSys.php|php/collSys.php|';
			break;

		case 'go': case 'goods':
			$fileList='|index.php|lock.txt|';
			break;

		case 'inc':
			$fileList='|classArea.php|classCache.php|classContent.php|classEncrypt.php|classFile.php|classGeetest.php|classGetUrl.php|classImages.php|classIpInfo.php|classIs.php|classJS.php|classKeyWord.php|classMakeDiy.php|classMySqlManage.php|classNav.php|classOT.php|classPayInfo.php|classPdoDb.php|classPinYin.php|classProvCity.php|classReqUrl.php|classSaveImg.php|classStr.php|classStrCN.php|classTemplate.php|classTemplateOTCMS.php|classTimeDate.php|classTplArea.php|classTplBottom.php|classTplIndex.php|classTplList.php|classTplTop.php|classUrl.php|classUserGroup.php|classUsers.php|classUsersCenter.php|classUsersNews.php|classWebCache.php|classWebHtml.php|classZip.php|phpqrcode.php|Snoopy.class.php|imageWatermark.ttf|keyWord.txt|classAreaApp.php|classStrInfo.php|classFtp.php|'.
			'QrReader/Binarizer.php|QrReader/BinaryBitmap.php|QrReader/ChecksumException.php|QrReader/FormatException.php|QrReader/GDLuminanceSource.php|QrReader/IMagickLuminanceSource.php|QrReader/LuminanceSource.php|QrReader/NotFoundException.php|QrReader/PlanarYUVLuminanceSource.php|QrReader/QrReader.php|QrReader/Reader.php|QrReader/ReaderException.php|QrReader/Result.php|QrReader/ResultPoint.php|QrReader/RGBLuminanceSource.php|QrReader/common/AbstractEnum.php|QrReader/common/BitArray.php|QrReader/common/BitMatrix.php|QrReader/common/BitSource.php|QrReader/common/CharacterSetEci.php|QrReader/common/customFunctions.php|QrReader/common/DecoderResult.php|QrReader/common/DefaultGridSampler.php|QrReader/common/DetectorResult.php|QrReader/common/GlobalHistogramBinarizer.php|QrReader/common/GridSampler.php|QrReader/common/HybridBinarizer.php|QrReader/common/PerspectiveTransform.php|QrReader/common/detector/MathUtils.php|QrReader/common/detector/MonochromeRectangleDetector.php|QrReader/common/reedsolomon/GenericGF.php|QrReader/common/reedsolomon/GenericGFPoly.php|QrReader/common/reedsolomon/ReedSolomonDecoder.php|QrReader/common/reedsolomon/ReedSolomonException.php|QrReader/qrcode/QRCodeReader.php|QrReader/qrcode/decoder/BitMatrixParser.php|QrReader/qrcode/decoder/DataBlock.php|QrReader/qrcode/decoder/DataMask.php|QrReader/qrcode/decoder/DecodedBitStreamParser.php|QrReader/qrcode/decoder/Decoder.php|QrReader/qrcode/decoder/ErrorCorrectionLevel.php|QrReader/qrcode/decoder/FormatInformation.php|QrReader/qrcode/decoder/Mode.php|QrReader/qrcode/decoder/Version.php|QrReader/qrcode/detector/AlignmentPattern.php|QrReader/qrcode/detector/AlignmentPatternFinder.php|QrReader/qrcode/detector/Detector.php|QrReader/qrcode/detector/FinderPattern.php|QrReader/qrcode/detector/FinderPatternFinder.php|QrReader/qrcode/detector/FinderPatternInfo.php|'.
			'VerCode/code_zh.php|VerCode/msyhbd.ttf|VerCode/VerCode.ttf|VerCode/VerCode1.php|VerCode/VerCode2.php|VerCode/VerCode3.php|VerCode/VerCode4.php|VerCode/VerCode_zh.ttf|'.
			'';
			break;

		case 'inc_img':
			$extList = '/bmp/jpg/jpeg/gif/png/css/';
			$fileList='|web.config|';
			break;

		case 'js':
			$extList = '/js/';
			$fileList='|web.config|';
			break;

		case 'message':
			$fileList='|index.php|posts.php|';
			break;

		case 'news':
			$extList = '/html/';
			$fileList='|index.php|lock.txt|';
			break;

		case 'pay':
			$fileList='|web.config|alipay/alipayConfig.php|alipay/alipayReturn.php|alipay/alipayServer.php|alipay/cacert.pem|alipay/classAlipayNotify.php|alipay/classAlipaySubmit.php|alipay/functionAlipay.php|alipay/log.txt|weixin/WxPay.Api.php|weixin/WxPay.Config.php|weixin/WxPay.Data.php|weixin/WxPay.Exception.php|weixin/WxPay.JsApiPay.php|weixin/WxPay.MicroPay.php|weixin/WxPay.NativePay.php|weixin/WxPay.Notify.php|weixin/cert/apiclient_cert.pem|weixin/cert/apiclient_key.pem|weixin/WxPay.Config.Interface.php|';
			break;

		case 'pluDef':
			$fileList='|web.config|classApiAlidayu.php|classApiQQ.php|classApiWeibo.php|classApiWeixin.php|classApiWeixinLogin.php|classAppAdminRightNews.php|classAppAutoColl.php|classAppAutoHtml.php|classAppBase.php|classAppBbs.php|classAppBuyOrders.php|classAppChangyan.php|classAppDashang.php|classAppDuoshuo.php|classAppForm.php|classAppGift.php|classAppIdcPro.php|classAppJob.php|classAppLogin.php|classAppLogoAdd.php|classAppMail.php|classAppMapBaidu.php|classAppMoneyPay.php|classAppMoneyRecord.php|classAppNewsEnc.php|classAppNewsGain.php|classAppNewsVerCode.php|classAppPhone.php|classAppQiandao.php|classAppQuan.php|classAppRecom.php|classAppRss.php|classAppTaobaoke.php|classAppTaobaokeDeal.php|classAppTaobaokeWap.php|classAppTopic.php|classAppToTop.php|classAppUpload.php|classAppUserScore.php|classAppVideo.php|classAppVote.php|classAppWap.php|classAppWeixin.php|classAppWeixinJs.php|classAppWxTaoke.php|classAppBbsDeal.php|classAppBbsTpl.php|classAppBbsTplWap.php|classAppOssAliyun.php|classAppOssJingan.php|classAppOssQiniu.php|classAppOssUpyun.php|classAppOssAliyunDeal.php|classAppOssQiniuDeal.php|classAppOssUpyunDeal.php|classAppGain.php|classAppMoneyAlipay.php|classAppMoneyGive.php|classAppMoneyPayDeal.php|classAppMoneyWeixin.php|classAppTaokeOrder.php|classAppUserGroup.php|classAppUserGroupWork.php|classAppUserState1.php|classAppWorkCenter.php|classAppLoginMail.php|classAppLoginPhone.php|classAppOssFtp.php|classAppOssFtpDeal.php|classAppCopyKouling.php|';
			break;

		case 'plugin':
			$fileList='|web.config|classApiAlidayu.php|classApiQQ.php|classApiTaobaoke.php|classApiWeibo.php|classApiWeixin.php|classApiWeixinLogin.php|classAppAdminRightNews.php|classAppAutoColl.php|classAppAutoHtml.php|classAppBase.php|classAppBbs.php|classAppBuyOrders.php|classAppChangyan.php|classAppDashang.php|classAppDuoshuo.php|classAppForm.php|classAppGift.php|classAppIdcPro.php|classAppJob.php|classAppLogin.php|classAppLogoAdd.php|classAppMail.php|classAppMapBaidu.php|classAppMoneyPay.php|classAppMoneyRecord.php|classAppNewsEnc.php|classAppNewsGain.php|classAppNewsVerCode.php|classAppPhone.php|classAppQiandao.php|classAppQuan.php|classAppRecom.php|classAppRss.php|classAppTaobaoke.php|classAppTaobaokeDeal.php|classAppTaobaokeWap.php|classAppTopic.php|classAppToTop.php|classAppTplBlog.php|classAppTplBlue.php|classAppTplDiy.php|classAppTplInfo.php|classAppTplQiyeBlue.php|classAppUpload.php|classAppUserScore.php|classAppVideo.php|classAppVote.php|classAppWap.php|classAppWeixin.php|classAppWeixinJs.php|classAppWxTaoke.php|classAppBbsDeal.php|classAppBbsTpl.php|classAppBbsTplWap.php|classAppTplBlack.php|classAppTplYule.php|classAppOssAliyun.php|classAppOssJingan.php|classAppOssQiniu.php|classAppOssUpyun.php|classAppOssAliyunDeal.php|classAppOssQiniuDeal.php|classAppOssUpyunDeal.php|classAppGain.php|classAppMoneyAlipay.php|classAppMoneyGive.php|classAppMoneyPayDeal.php|classAppMoneyWeixin.php|classAppOssUpyunDeal.php|classAppTaokeOrder.php|classAppTplWhite.php|classAppUserGroup.php|classAppUserGroupWork.php|classAppUserState1.php|classAppWapTplWhite.php|classAppWorkCenter.php|classAppLoginMail.php|classAppLoginPhone.php|classAppOssFtp.php|classAppOssFtpDeal.php|classAppCopyKouling.php|classAppTplXiaodao.php|classAppWapTplXiaodao.php|';
			/*
			.
			'del_classAppRss.php|del_classAppBase.php|del_classAppWap.php|del_classAppLogin.php|del_classAppBbs.php|del_classAppBbsDeal.php|del_classAppBbsTpl.php|del_classAppBbsTplWap.php|del_classAppTaobaoke.php|del_classAppTaobaokeWap.php|del_classAppTaobaokeDeal.php|del_classAppTplInfo.php|del_classAppTplBlog.php|del_classAppTplBlue.php|del_classAppWeixin.php|del_classAppMapBaidu.php|del_classAppUpload.php|del_classAppForm.php|del_classAppTopic.php|del_classAppLogoAdd.php|del_classAppMoneyPay.php|del_classAppUserScore.php|del_classAppGift.php|del_classAppDashang.php|del_classAppNewsEnc.php|del_classAppVideo.php|del_classAppChangyan.php|del_classAppBuyOrders.php|del_classAppNewsVerCode.php|del_classAppRecom.php|del_classAppQiandao.php|del_classAppQuan.php|del_classAppMail.php|del_classAppPhone.php|del_classAppNewsGain.php|del_classAppWeixinJs.php|del_classAppToTop.php|del_classAppTplQiyeBlue.php|del_classAppIdcPro.php|del_classAppAutoHtml.php|del_classAppAutoColl.php|del_classAppAdminRightNews.php|del_classAppTplYule.php|del_classAppTplBlack.php|del_classAppMoneyRecord.php|'
			*/
			break;

		case 'smarty':
			$fileList='|web.config|Autoloader.php|debug.tpl|Smarty.class.php|SmartyBC.class.php|plugins/block.textformat.php|plugins/function.counter.php|plugins/function.cycle.php|plugins/function.fetch.php|plugins/function.html_checkboxes.php|plugins/function.html_image.php|plugins/function.html_options.php|plugins/function.html_radios.php|plugins/function.html_select_date.php|plugins/function.html_select_time.php|plugins/function.html_table.php|plugins/function.mailto.php|plugins/function.math.php|plugins/modifier.capitalize.php|plugins/modifier.date_format.php|plugins/modifier.debug_print_var.php|plugins/modifier.escape.php|plugins/modifier.regex_replace.php|plugins/modifier.replace.php|plugins/modifier.spacify.php|plugins/modifier.truncate.php|plugins/modifiercompiler.cat.php|plugins/modifiercompiler.count_characters.php|plugins/modifiercompiler.count_paragraphs.php|plugins/modifiercompiler.count_sentences.php|plugins/modifiercompiler.count_words.php|plugins/modifiercompiler.default.php|plugins/modifiercompiler.escape.php|plugins/modifiercompiler.from_charset.php|plugins/modifiercompiler.indent.php|plugins/modifiercompiler.lower.php|plugins/modifiercompiler.noprint.php|plugins/modifiercompiler.string_format.php|plugins/modifiercompiler.strip.php|plugins/modifiercompiler.strip_tags.php|plugins/modifiercompiler.to_charset.php|plugins/modifiercompiler.unescape.php|plugins/modifiercompiler.upper.php|plugins/modifiercompiler.wordwrap.php|plugins/outputfilter.trimwhitespace.php|plugins/shared.escape_special_chars.php|plugins/shared.literal_compiler_param.php|plugins/shared.make_timestamp.php|plugins/shared.mb_str_replace.php|plugins/shared.mb_unicode.php|plugins/shared.mb_wordwrap.php|plugins/variablefilter.htmlspecialchars.php|sysplugins/smartycompilerexception.php|sysplugins/smartyexception.php|sysplugins/smarty_cacheresource.php|sysplugins/smarty_cacheresource_custom.php|sysplugins/smarty_cacheresource_keyvaluestore.php|sysplugins/smarty_data.php|sysplugins/smarty_internal_block.php|sysplugins/smarty_internal_cacheresource_file.php|sysplugins/smarty_internal_compilebase.php|sysplugins/smarty_internal_compile_append.php|sysplugins/smarty_internal_compile_assign.php|sysplugins/smarty_internal_compile_block.php|sysplugins/smarty_internal_compile_break.php|sysplugins/smarty_internal_compile_call.php|sysplugins/smarty_internal_compile_capture.php|sysplugins/smarty_internal_compile_config_load.php|sysplugins/smarty_internal_compile_continue.php|sysplugins/smarty_internal_compile_debug.php|sysplugins/smarty_internal_compile_eval.php|sysplugins/smarty_internal_compile_extends.php|sysplugins/smarty_internal_compile_for.php|sysplugins/smarty_internal_compile_foreach.php|sysplugins/smarty_internal_compile_function.php|sysplugins/smarty_internal_compile_if.php|sysplugins/smarty_internal_compile_include.php|sysplugins/smarty_internal_compile_include_php.php|sysplugins/smarty_internal_compile_insert.php|sysplugins/smarty_internal_compile_ldelim.php|sysplugins/smarty_internal_compile_make_nocache.php|sysplugins/smarty_internal_compile_nocache.php|sysplugins/smarty_internal_compile_private_block_plugin.php|sysplugins/smarty_internal_compile_private_foreachsection.php|sysplugins/smarty_internal_compile_private_function_plugin.php|sysplugins/smarty_internal_compile_private_modifier.php|sysplugins/smarty_internal_compile_private_object_block_function.php|sysplugins/smarty_internal_compile_private_object_function.php|sysplugins/smarty_internal_compile_private_php.php|sysplugins/smarty_internal_compile_private_print_expression.php|sysplugins/smarty_internal_compile_private_registered_block.php|sysplugins/smarty_internal_compile_private_registered_function.php|sysplugins/smarty_internal_compile_private_special_variable.php|sysplugins/smarty_internal_compile_rdelim.php|sysplugins/smarty_internal_compile_section.php|sysplugins/smarty_internal_compile_setfilter.php|sysplugins/smarty_internal_compile_shared_inheritance.php|sysplugins/smarty_internal_compile_while.php|sysplugins/smarty_internal_configfilelexer.php|sysplugins/smarty_internal_configfileparser.php|sysplugins/smarty_internal_config_file_compiler.php|sysplugins/smarty_internal_data.php|sysplugins/smarty_internal_debug.php|sysplugins/smarty_internal_extension_clear.php|sysplugins/smarty_internal_extension_handler.php|sysplugins/smarty_internal_method_addautoloadfilters.php|sysplugins/smarty_internal_method_adddefaultmodifiers.php|sysplugins/smarty_internal_method_append.php|sysplugins/smarty_internal_method_appendbyref.php|sysplugins/smarty_internal_method_assignbyref.php|sysplugins/smarty_internal_method_assignglobal.php|sysplugins/smarty_internal_method_clearallassign.php|sysplugins/smarty_internal_method_clearallcache.php|sysplugins/smarty_internal_method_clearassign.php|sysplugins/smarty_internal_method_clearcache.php|sysplugins/smarty_internal_method_clearcompiledtemplate.php|sysplugins/smarty_internal_method_clearconfig.php|sysplugins/smarty_internal_method_compileallconfig.php|sysplugins/smarty_internal_method_compilealltemplates.php|sysplugins/smarty_internal_method_configload.php|sysplugins/smarty_internal_method_createdata.php|sysplugins/smarty_internal_method_getautoloadfilters.php|sysplugins/smarty_internal_method_getconfigvars.php|sysplugins/smarty_internal_method_getdebugtemplate.php|sysplugins/smarty_internal_method_getdefaultmodifiers.php|sysplugins/smarty_internal_method_getglobal.php|sysplugins/smarty_internal_method_getregisteredobject.php|sysplugins/smarty_internal_method_getstreamvariable.php|sysplugins/smarty_internal_method_gettags.php|sysplugins/smarty_internal_method_gettemplatevars.php|sysplugins/smarty_internal_method_loadfilter.php|sysplugins/smarty_internal_method_loadplugin.php|sysplugins/smarty_internal_method_mustcompile.php|sysplugins/smarty_internal_method_registercacheresource.php|sysplugins/smarty_internal_method_registerclass.php|sysplugins/smarty_internal_method_registerdefaultconfighandler.php|sysplugins/smarty_internal_method_registerdefaultpluginhandler.php|sysplugins/smarty_internal_method_registerdefaulttemplatehandler.php|sysplugins/smarty_internal_method_registerfilter.php|sysplugins/smarty_internal_method_registerobject.php|sysplugins/smarty_internal_method_registerplugin.php|sysplugins/smarty_internal_method_registerresource.php|sysplugins/smarty_internal_method_setautoloadfilters.php|sysplugins/smarty_internal_method_setdebugtemplate.php|sysplugins/smarty_internal_method_setdefaultmodifiers.php|sysplugins/smarty_internal_method_unloadfilter.php|sysplugins/smarty_internal_method_unregistercacheresource.php|sysplugins/smarty_internal_method_unregisterfilter.php|sysplugins/smarty_internal_method_unregisterobject.php|sysplugins/smarty_internal_method_unregisterplugin.php|sysplugins/smarty_internal_method_unregisterresource.php|sysplugins/smarty_internal_nocache_insert.php|sysplugins/smarty_internal_parsetree.php|sysplugins/smarty_internal_parsetree_code.php|sysplugins/smarty_internal_parsetree_dq.php|sysplugins/smarty_internal_parsetree_dqcontent.php|sysplugins/smarty_internal_parsetree_tag.php|sysplugins/smarty_internal_parsetree_template.php|sysplugins/smarty_internal_parsetree_text.php|sysplugins/smarty_internal_resource_eval.php|sysplugins/smarty_internal_resource_extends.php|sysplugins/smarty_internal_resource_file.php|sysplugins/smarty_internal_resource_php.php|sysplugins/smarty_internal_resource_registered.php|sysplugins/smarty_internal_resource_stream.php|sysplugins/smarty_internal_resource_string.php|sysplugins/smarty_internal_runtime_cachemodify.php|sysplugins/smarty_internal_runtime_capture.php|sysplugins/smarty_internal_runtime_codeframe.php|sysplugins/smarty_internal_runtime_filterhandler.php|sysplugins/smarty_internal_runtime_foreach.php|sysplugins/smarty_internal_runtime_getincludepath.php|sysplugins/smarty_internal_runtime_inheritance.php|sysplugins/smarty_internal_runtime_make_nocache.php|sysplugins/smarty_internal_runtime_tplfunction.php|sysplugins/smarty_internal_runtime_updatecache.php|sysplugins/smarty_internal_runtime_updatescope.php|sysplugins/smarty_internal_runtime_writefile.php|sysplugins/smarty_internal_smartytemplatecompiler.php|sysplugins/smarty_internal_template.php|sysplugins/smarty_internal_templatebase.php|sysplugins/smarty_internal_templatecompilerbase.php|sysplugins/smarty_internal_templatelexer.php|sysplugins/smarty_internal_templateparser.php|sysplugins/smarty_internal_testinstall.php|sysplugins/smarty_internal_undefined.php|sysplugins/smarty_resource.php|sysplugins/smarty_resource_custom.php|sysplugins/smarty_resource_recompiled.php|sysplugins/smarty_resource_uncompiled.php|sysplugins/smarty_security.php|sysplugins/smarty_template_cached.php|sysplugins/smarty_template_compiled.php|sysplugins/smarty_template_config.php|sysplugins/smarty_template_resource_base.php|sysplugins/smarty_template_source.php|sysplugins/smarty_undefined_variable.php|sysplugins/smarty_variable.php|bootstrap.php|plugins/modifier.mb_wordwrap.php|sysplugins/smarty_internal_compile_block_child.php|sysplugins/smarty_internal_compile_block_parent.php|sysplugins/smarty_internal_compile_child.php|sysplugins/smarty_internal_compile_parent.php|sysplugins/smarty_internal_errorhandler.php|sysplugins/smarty_internal_method_getconfigvariable.php|sysplugins/smarty_internal_method_literals.php|sysplugins/smarty_internal_runtime_cacheresourcefile.php|';
			break;

		case 'temp':
			$fileList='|web.config|';
			break;

		case 'template':
			$extList = '/html/css/js/xml/txt/swf/bmp/jpg/jpeg/gif/png/eot/svg/ttf/woff/';
			$fileList='|web.config|';
			break;

		case 'tools':
			$extList = '/html/htm/css/js/xml/txt/swf/bmp/jpg/jpeg/gif/png/less/scss/';
			$fileList='|web.config|ip.dat|simsun.ttc|CuMp3Player/images/bigplay.svg|CuMp3Player/images/controls.svg|font-awesome/fonts/fontawesome-webfont.eot|font-awesome/fonts/fontawesome-webfont.svg|font-awesome/fonts/fontawesome-webfont.ttf|font-awesome/fonts/fontawesome-webfont.woff|font-awesome/fonts/fontawesome-webfont.woff2|font-awesome/fonts/FontAwesome.otf|geetest/class.geetestlib.php|'.
			'PHPMailer/class.phpmailer.php|PHPMailer/class.phpmaileroauth.php|PHPMailer/class.phpmaileroauthgoogle.php|PHPMailer/class.pop3.php|PHPMailer/class.smtp.php|PHPMailer/get_oauth_token.php|PHPMailer/PHPMailerAutoload.php|PHPMailer/extras/EasyPeasyICS.php|PHPMailer/extras/htmlfilter.php|PHPMailer/extras/ntlm_sasl_client.php|PHPMailer/extras/README.md|PHPMailer/language/phpmailer.lang-zh.php|PHPMailer/language/phpmailer.lang-zh_cn.php|'.
			'pscws4/pscws4.class.php|pscws4/xdb_r.class.php|pscws4/etc/00dict.utf8.xdb|pscws4/etc/dict.utf8.xdb|pscws4/etc/rules.ini|pscws4/etc/rules.utf8.ini|pscws4/etc/rules_cht.utf8.ini|swfobject/src/expressInstall.as|swfobject/src/expressInstall.fla|'.
			'taobaoApi/Autoloader.php|taobaoApi/TopSdk.php|taobaoApi/aliyun/AliyunClient.php|taobaoApi/dingtalk/DingTalkClient.php|taobaoApi/QimenCloud/QimenCloudClient.php|taobaoApi/top/ApplicationVar.php|taobaoApi/top/ClusterTopClient.php|taobaoApi/top/HttpdnsGetRequest.php|taobaoApi/top/RequestCheckUtil.php|taobaoApi/top/ResultSet.php|taobaoApi/top/SpiUtils.php|taobaoApi/top/TopClient.php|taobaoApi/top/TopLogger.php|taobaoApi/top/domain/Data.php|taobaoApi/top/domain/Extend.php|taobaoApi/top/domain/GenPwdIsvParamDto.php|taobaoApi/top/domain/Items.php|taobaoApi/top/domain/MapData.php|taobaoApi/top/domain/NTbkItem.php|taobaoApi/top/domain/NTbkShop.php|taobaoApi/top/domain/PaginationResult.php|taobaoApi/top/domain/Results.php|taobaoApi/top/domain/TbkCoupon.php|taobaoApi/top/domain/TbkEvent.php|taobaoApi/top/domain/TbkFavorites.php|taobaoApi/top/domain/TbkSpread.php|taobaoApi/top/domain/TbkSpreadRequest.php|taobaoApi/top/domain/TopItemQuery.php|taobaoApi/top/domain/Trackparams.php|taobaoApi/top/domain/UatmTbkItem.php|taobaoApi/top/request/JuItemsSearchRequest.php|taobaoApi/top/request/TbkCouponGetRequest.php|taobaoApi/top/request/TbkDgItemCouponGetRequest.php|taobaoApi/top/request/TbkDgNewuserOrderGetRequest.php|taobaoApi/top/request/TbkItemGetRequest.php|taobaoApi/top/request/TbkItemInfoGetRequest.php|taobaoApi/top/request/TbkItemRecommendGetRequest.php|taobaoApi/top/request/TbkJuTqgGetRequest.php|taobaoApi/top/request/TbkScMaterialOptionalRequest.php|taobaoApi/top/request/TbkScNewuserOrderGetRequest.php|taobaoApi/top/request/TbkShopGetRequest.php|taobaoApi/top/request/TbkShopRecommendGetRequest.php|taobaoApi/top/request/TbkSpreadGetRequest.php|taobaoApi/top/request/TbkTpwdCreateRequest.php|taobaoApi/top/request/TbkUatmEventGetRequest.php|taobaoApi/top/request/TbkUatmEventItemGetRequest.php|taobaoApi/top/request/TbkUatmFavoritesGetRequest.php|taobaoApi/top/request/TbkUatmFavoritesItemGetRequest.php|taobaoApi/top/request/WirelessShareTpwdCreateRequest.php|taobaoApi/top/request/WirelessShareTpwdQueryRequest.php|taobaoApi/top/security/iCache.php|taobaoApi/top/security/MagicCrypt.php|taobaoApi/top/security/README.txt|taobaoApi/top/security/SecretContext.php|taobaoApi/top/security/SecretCounterUtil.php|taobaoApi/top/security/SecretGetRequest.php|taobaoApi/top/security/SecurityClient.php|taobaoApi/top/security/SecurityTest.php|taobaoApi/top/security/SecurityUtil.php|taobaoApi/top/security/TopSdkFeedbackUploadRequest.php|taobaoApi/top/security/YacCache.php|'.
			'aliyunOSS/OssClient.php|aliyunOSS/Core/MimeTypes.php|aliyunOSS/Core/OssException.php|aliyunOSS/Core/OssUtil.php|aliyunOSS/Http/LICENSE|aliyunOSS/Http/RequestCore.php|aliyunOSS/Http/RequestCore_Exception.php|aliyunOSS/Http/ResponseCore.php|aliyunOSS/Model/BucketInfo.php|aliyunOSS/Model/BucketListInfo.php|aliyunOSS/Model/CnameConfig.php|aliyunOSS/Model/CorsConfig.php|aliyunOSS/Model/CorsRule.php|aliyunOSS/Model/GetLiveChannelHistory.php|aliyunOSS/Model/GetLiveChannelInfo.php|aliyunOSS/Model/GetLiveChannelStatus.php|aliyunOSS/Model/LifecycleAction.php|aliyunOSS/Model/LifecycleConfig.php|aliyunOSS/Model/LifecycleRule.php|aliyunOSS/Model/ListMultipartUploadInfo.php|aliyunOSS/Model/ListPartsInfo.php|aliyunOSS/Model/LiveChannelConfig.php|aliyunOSS/Model/LiveChannelHistory.php|aliyunOSS/Model/LiveChannelInfo.php|aliyunOSS/Model/LiveChannelListInfo.php|aliyunOSS/Model/LoggingConfig.php|aliyunOSS/Model/ObjectInfo.php|aliyunOSS/Model/ObjectListInfo.php|aliyunOSS/Model/PartInfo.php|aliyunOSS/Model/PrefixInfo.php|aliyunOSS/Model/RefererConfig.php|aliyunOSS/Model/StorageCapacityConfig.php|aliyunOSS/Model/UploadInfo.php|aliyunOSS/Model/WebsiteConfig.php|aliyunOSS/Model/XmlConfig.php|aliyunOSS/Result/AclResult.php|aliyunOSS/Result/AppendResult.php|aliyunOSS/Result/BodyResult.php|aliyunOSS/Result/CallbackResult.php|aliyunOSS/Result/CopyObjectResult.php|aliyunOSS/Result/DeleteObjectsResult.php|aliyunOSS/Result/ExistResult.php|aliyunOSS/Result/GetCnameResult.php|aliyunOSS/Result/GetCorsResult.php|aliyunOSS/Result/GetLifecycleResult.php|aliyunOSS/Result/GetLiveChannelHistoryResult.php|aliyunOSS/Result/GetLiveChannelInfoResult.php|aliyunOSS/Result/GetLiveChannelStatusResult.php|aliyunOSS/Result/GetLocationResult.php|aliyunOSS/Result/GetLoggingResult.php|aliyunOSS/Result/GetRefererResult.php|aliyunOSS/Result/GetStorageCapacityResult.php|aliyunOSS/Result/GetWebsiteResult.php|aliyunOSS/Result/HeaderResult.php|aliyunOSS/Result/InitiateMultipartUploadResult.php|aliyunOSS/Result/ListBucketsResult.php|aliyunOSS/Result/ListLiveChannelResult.php|aliyunOSS/Result/ListMultipartUploadResult.php|aliyunOSS/Result/ListObjectsResult.php|aliyunOSS/Result/ListPartsResult.php|aliyunOSS/Result/PutLiveChannelResult.php|aliyunOSS/Result/PutSetDeleteResult.php|aliyunOSS/Result/Result.php|aliyunOSS/Result/SymlinkResult.php|aliyunOSS/Result/UploadPartResult.php|'.
			'Qiniu/Auth.php|Qiniu/Config.php|Qiniu/Etag.php|Qiniu/functions.php|Qiniu/Zone.php|Qiniu/Cdn/CdnManager.php|Qiniu/Http/Client.php|Qiniu/Http/Error.php|Qiniu/Http/Request.php|Qiniu/Http/Response.php|Qiniu/Processing/ImageUrlBuilder.php|Qiniu/Processing/Operation.php|Qiniu/Processing/PersistentFop.php|Qiniu/Rtc/AppClient.php|Qiniu/Storage/ArgusManager.php|Qiniu/Storage/BucketManager.php|Qiniu/Storage/FormUploader.php|Qiniu/Storage/ResumeUploader.php|Qiniu/Storage/UploadManager.php|ossQiniu/src/Qiniu/Region.php|ossQiniu/src/Qiniu/Sms/Sms.php|'.
			'ossAliyun/OSS/OssClient.php|ossAliyun/OSS/Core/MimeTypes.php|ossAliyun/OSS/Core/OssException.php|ossAliyun/OSS/Core/OssUtil.php|ossAliyun/OSS/Http/LICENSE|ossAliyun/OSS/Http/RequestCore.php|ossAliyun/OSS/Http/RequestCore_Exception.php|ossAliyun/OSS/Http/ResponseCore.php|ossAliyun/OSS/Model/BucketInfo.php|ossAliyun/OSS/Model/BucketListInfo.php|ossAliyun/OSS/Model/CnameConfig.php|ossAliyun/OSS/Model/CorsConfig.php|ossAliyun/OSS/Model/CorsRule.php|ossAliyun/OSS/Model/GetLiveChannelHistory.php|ossAliyun/OSS/Model/GetLiveChannelInfo.php|ossAliyun/OSS/Model/GetLiveChannelStatus.php|ossAliyun/OSS/Model/LifecycleAction.php|ossAliyun/OSS/Model/LifecycleConfig.php|ossAliyun/OSS/Model/LifecycleRule.php|ossAliyun/OSS/Model/ListMultipartUploadInfo.php|ossAliyun/OSS/Model/ListPartsInfo.php|ossAliyun/OSS/Model/LiveChannelConfig.php|ossAliyun/OSS/Model/LiveChannelHistory.php|ossAliyun/OSS/Model/LiveChannelInfo.php|ossAliyun/OSS/Model/LiveChannelListInfo.php|ossAliyun/OSS/Model/LoggingConfig.php|ossAliyun/OSS/Model/ObjectInfo.php|ossAliyun/OSS/Model/ObjectListInfo.php|ossAliyun/OSS/Model/PartInfo.php|ossAliyun/OSS/Model/PrefixInfo.php|ossAliyun/OSS/Model/RefererConfig.php|ossAliyun/OSS/Model/StorageCapacityConfig.php|ossAliyun/OSS/Model/UploadInfo.php|ossAliyun/OSS/Model/WebsiteConfig.php|ossAliyun/OSS/Model/XmlConfig.php|ossAliyun/OSS/Result/AclResult.php|ossAliyun/OSS/Result/AppendResult.php|ossAliyun/OSS/Result/BodyResult.php|ossAliyun/OSS/Result/CallbackResult.php|ossAliyun/OSS/Result/CopyObjectResult.php|ossAliyun/OSS/Result/DeleteObjectsResult.php|ossAliyun/OSS/Result/ExistResult.php|ossAliyun/OSS/Result/GetCnameResult.php|ossAliyun/OSS/Result/GetCorsResult.php|ossAliyun/OSS/Result/GetLifecycleResult.php|ossAliyun/OSS/Result/GetLiveChannelHistoryResult.php|ossAliyun/OSS/Result/GetLiveChannelInfoResult.php|ossAliyun/OSS/Result/GetLiveChannelStatusResult.php|ossAliyun/OSS/Result/GetLocationResult.php|ossAliyun/OSS/Result/GetLoggingResult.php|ossAliyun/OSS/Result/GetRefererResult.php|ossAliyun/OSS/Result/GetStorageCapacityResult.php|ossAliyun/OSS/Result/GetWebsiteResult.php|ossAliyun/OSS/Result/HeaderResult.php|ossAliyun/OSS/Result/InitiateMultipartUploadResult.php|ossAliyun/OSS/Result/ListBucketsResult.php|ossAliyun/OSS/Result/ListLiveChannelResult.php|ossAliyun/OSS/Result/ListMultipartUploadResult.php|ossAliyun/OSS/Result/ListObjectsResult.php|ossAliyun/OSS/Result/ListPartsResult.php|ossAliyun/OSS/Result/PutLiveChannelResult.php|ossAliyun/OSS/Result/PutSetDeleteResult.php|ossAliyun/OSS/Result/Result.php|ossAliyun/OSS/Result/SymlinkResult.php|ossAliyun/OSS/Result/UploadPartResult.php|'.
			'ossQiniu/auth_digest.php|ossQiniu/conf.php|ossQiniu/fop.php|ossQiniu/http.php|ossQiniu/io.php|ossQiniu/pfop.php|ossQiniu/resumable_io.php|ossQiniu/rs.php|ossQiniu/rsf.php|ossQiniu/rs_utils.php|ossQiniu/utils.php|ossQiniu/autoload.php|ossQiniu/src/Qiniu/Auth.php|ossQiniu/src/Qiniu/Config.php|ossQiniu/src/Qiniu/Etag.php|ossQiniu/src/Qiniu/functions.php|ossQiniu/src/Qiniu/Zone.php|ossQiniu/src/Qiniu/Cdn/CdnManager.php|ossQiniu/src/Qiniu/Http/Client.php|ossQiniu/src/Qiniu/Http/Error.php|ossQiniu/src/Qiniu/Http/Request.php|ossQiniu/src/Qiniu/Http/Response.php|ossQiniu/src/Qiniu/Processing/ImageUrlBuilder.php|ossQiniu/src/Qiniu/Processing/Operation.php|ossQiniu/src/Qiniu/Processing/PersistentFop.php|ossQiniu/src/Qiniu/Rtc/AppClient.php|ossQiniu/src/Qiniu/Storage/ArgusManager.php|ossQiniu/src/Qiniu/Storage/BucketManager.php|ossQiniu/src/Qiniu/Storage/FormUploader.php|ossQiniu/src/Qiniu/Storage/ResumeUploader.php|ossQiniu/src/Qiniu/Storage/UploadManager.php|'.
			'ossUpyun/src/Upyun/Config.php|ossUpyun/src/Upyun/Signature.php|ossUpyun/src/Upyun/Uploader.php|ossUpyun/src/Upyun/Upyun.php|ossUpyun/src/Upyun/Util.php|ossUpyun/src/Upyun/Api/Form.php|ossUpyun/src/Upyun/Api/Pretreat.php|ossUpyun/src/Upyun/Api/Rest.php|ossUpyun/src/Upyun/Api/SyncVideo.php|ossUpyun/vendor/autoload.php|ossUpyun/vendor/composer/autoload_classmap.php|ossUpyun/vendor/composer/autoload_files.php|ossUpyun/vendor/composer/autoload_namespaces.php|ossUpyun/vendor/composer/autoload_psr4.php|ossUpyun/vendor/composer/autoload_real.php|ossUpyun/vendor/composer/autoload_static.php|ossUpyun/vendor/composer/ClassLoader.php|ossUpyun/vendor/composer/installed.json|ossUpyun/vendor/composer/LICENSE|ossUpyun/vendor/guzzlehttp/guzzle/CHANGELOG.md|ossUpyun/vendor/guzzlehttp/guzzle/composer.json|ossUpyun/vendor/guzzlehttp/guzzle/LICENSE|ossUpyun/vendor/guzzlehttp/guzzle/README.md|ossUpyun/vendor/guzzlehttp/guzzle/UPGRADING.md|ossUpyun/vendor/guzzlehttp/guzzle/src/Client.php|ossUpyun/vendor/guzzlehttp/guzzle/src/ClientInterface.php|ossUpyun/vendor/guzzlehttp/guzzle/src/functions.php|ossUpyun/vendor/guzzlehttp/guzzle/src/functions_include.php|ossUpyun/vendor/guzzlehttp/guzzle/src/HandlerStack.php|ossUpyun/vendor/guzzlehttp/guzzle/src/MessageFormatter.php|ossUpyun/vendor/guzzlehttp/guzzle/src/Middleware.php|ossUpyun/vendor/guzzlehttp/guzzle/src/Pool.php|ossUpyun/vendor/guzzlehttp/guzzle/src/PrepareBodyMiddleware.php|ossUpyun/vendor/guzzlehttp/guzzle/src/RedirectMiddleware.php|ossUpyun/vendor/guzzlehttp/guzzle/src/RequestOptions.php|ossUpyun/vendor/guzzlehttp/guzzle/src/RetryMiddleware.php|ossUpyun/vendor/guzzlehttp/guzzle/src/TransferStats.php|ossUpyun/vendor/guzzlehttp/guzzle/src/UriTemplate.php|ossUpyun/vendor/guzzlehttp/guzzle/src/Cookie/CookieJar.php|ossUpyun/vendor/guzzlehttp/guzzle/src/Cookie/CookieJarInterface.php|ossUpyun/vendor/guzzlehttp/guzzle/src/Cookie/FileCookieJar.php|ossUpyun/vendor/guzzlehttp/guzzle/src/Cookie/SessionCookieJar.php|ossUpyun/vendor/guzzlehttp/guzzle/src/Cookie/SetCookie.php|ossUpyun/vendor/guzzlehttp/guzzle/src/Exception/BadResponseException.php|ossUpyun/vendor/guzzlehttp/guzzle/src/Exception/ClientException.php|ossUpyun/vendor/guzzlehttp/guzzle/src/Exception/ConnectException.php|ossUpyun/vendor/guzzlehttp/guzzle/src/Exception/GuzzleException.php|ossUpyun/vendor/guzzlehttp/guzzle/src/Exception/RequestException.php|ossUpyun/vendor/guzzlehttp/guzzle/src/Exception/SeekException.php|ossUpyun/vendor/guzzlehttp/guzzle/src/Exception/ServerException.php|ossUpyun/vendor/guzzlehttp/guzzle/src/Exception/TooManyRedirectsException.php|ossUpyun/vendor/guzzlehttp/guzzle/src/Exception/TransferException.php|ossUpyun/vendor/guzzlehttp/guzzle/src/Handler/CurlFactory.php|ossUpyun/vendor/guzzlehttp/guzzle/src/Handler/CurlFactoryInterface.php|ossUpyun/vendor/guzzlehttp/guzzle/src/Handler/CurlHandler.php|ossUpyun/vendor/guzzlehttp/guzzle/src/Handler/CurlMultiHandler.php|ossUpyun/vendor/guzzlehttp/guzzle/src/Handler/EasyHandle.php|ossUpyun/vendor/guzzlehttp/guzzle/src/Handler/MockHandler.php|ossUpyun/vendor/guzzlehttp/guzzle/src/Handler/Proxy.php|ossUpyun/vendor/guzzlehttp/guzzle/src/Handler/StreamHandler.php|ossUpyun/vendor/guzzlehttp/promises/CHANGELOG.md|ossUpyun/vendor/guzzlehttp/promises/composer.json|ossUpyun/vendor/guzzlehttp/promises/LICENSE|ossUpyun/vendor/guzzlehttp/promises/Makefile|ossUpyun/vendor/guzzlehttp/promises/README.md|ossUpyun/vendor/guzzlehttp/promises/src/AggregateException.php|ossUpyun/vendor/guzzlehttp/promises/src/CancellationException.php|ossUpyun/vendor/guzzlehttp/promises/src/Coroutine.php|ossUpyun/vendor/guzzlehttp/promises/src/EachPromise.php|ossUpyun/vendor/guzzlehttp/promises/src/FulfilledPromise.php|ossUpyun/vendor/guzzlehttp/promises/src/functions.php|ossUpyun/vendor/guzzlehttp/promises/src/functions_include.php|ossUpyun/vendor/guzzlehttp/promises/src/Promise.php|ossUpyun/vendor/guzzlehttp/promises/src/PromiseInterface.php|ossUpyun/vendor/guzzlehttp/promises/src/PromisorInterface.php|ossUpyun/vendor/guzzlehttp/promises/src/RejectedPromise.php|ossUpyun/vendor/guzzlehttp/promises/src/RejectionException.php|ossUpyun/vendor/guzzlehttp/promises/src/TaskQueue.php|ossUpyun/vendor/guzzlehttp/promises/src/TaskQueueInterface.php|ossUpyun/vendor/guzzlehttp/psr7/CHANGELOG.md|ossUpyun/vendor/guzzlehttp/psr7/composer.json|ossUpyun/vendor/guzzlehttp/psr7/LICENSE|ossUpyun/vendor/guzzlehttp/psr7/README.md|ossUpyun/vendor/guzzlehttp/psr7/src/AppendStream.php|ossUpyun/vendor/guzzlehttp/psr7/src/BufferStream.php|ossUpyun/vendor/guzzlehttp/psr7/src/CachingStream.php|ossUpyun/vendor/guzzlehttp/psr7/src/DroppingStream.php|ossUpyun/vendor/guzzlehttp/psr7/src/FnStream.php|ossUpyun/vendor/guzzlehttp/psr7/src/functions.php|ossUpyun/vendor/guzzlehttp/psr7/src/functions_include.php|ossUpyun/vendor/guzzlehttp/psr7/src/InflateStream.php|ossUpyun/vendor/guzzlehttp/psr7/src/LazyOpenStream.php|ossUpyun/vendor/guzzlehttp/psr7/src/LimitStream.php|ossUpyun/vendor/guzzlehttp/psr7/src/MessageTrait.php|ossUpyun/vendor/guzzlehttp/psr7/src/MultipartStream.php|ossUpyun/vendor/guzzlehttp/psr7/src/NoSeekStream.php|ossUpyun/vendor/guzzlehttp/psr7/src/PumpStream.php|ossUpyun/vendor/guzzlehttp/psr7/src/Request.php|ossUpyun/vendor/guzzlehttp/psr7/src/Response.php|ossUpyun/vendor/guzzlehttp/psr7/src/ServerRequest.php|ossUpyun/vendor/guzzlehttp/psr7/src/Stream.php|ossUpyun/vendor/guzzlehttp/psr7/src/StreamDecoratorTrait.php|ossUpyun/vendor/guzzlehttp/psr7/src/StreamWrapper.php|ossUpyun/vendor/guzzlehttp/psr7/src/UploadedFile.php|ossUpyun/vendor/guzzlehttp/psr7/src/Uri.php|ossUpyun/vendor/guzzlehttp/psr7/src/UriNormalizer.php|ossUpyun/vendor/guzzlehttp/psr7/src/UriResolver.php|ossUpyun/vendor/psr/http-message/CHANGELOG.md|ossUpyun/vendor/psr/http-message/composer.json|ossUpyun/vendor/psr/http-message/LICENSE|ossUpyun/vendor/psr/http-message/README.md|ossUpyun/vendor/psr/http-message/src/MessageInterface.php|ossUpyun/vendor/psr/http-message/src/RequestInterface.php|ossUpyun/vendor/psr/http-message/src/ResponseInterface.php|ossUpyun/vendor/psr/http-message/src/ServerRequestInterface.php|ossUpyun/vendor/psr/http-message/src/StreamInterface.php|ossUpyun/vendor/psr/http-message/src/UploadedFileInterface.php|ossUpyun/vendor/psr/http-message/src/UriInterface.php|'.
			'iconfont/iconfont.eot|iconfont/iconfont.svg|iconfont/iconfont.ttf|iconfont/iconfont.woff|iconfont/iconfont.woff2|iconfont/iconfont.json|';
			break;

		case 'upFiles':
			$extList = '/html/htm/xml/js/css/bmp/jpg/jpeg/gif/png/webp/tif/tiff/swf/doc/xls/txt/ppt/docx/xlsx/pptx/pdf/rar/zip/avi/mpeg/mpg/ra/rm/rmvb/mov/qt/asf/wmv/iso/bin/img/mp3/mp4/wma/wav/mod/cd/md/aac/mid/ogg/m4a/';
			$fileList='|web.config|';
			break;

		case 'wap':
			$extList = '/html/htm/xml/js/css/bmp/jpg/jpeg/gif/png/tif/tiff/swf/doc/xls/txt/ppt/docx/xlsx/pptx/pdf/rar/zip/avi/mpeg/mpg/ra/rm/rmvb/mov/qt/asf/wmv/iso/bin/img/mp3/mp4/wma/wav/mod/cd/md/aac/mid/ogg/m4a/eot/svg/ttf/';
			$fileList='|404.php|api.php|apiSuccess.php|apiWeb.php|autoRun.php|deal.php|form.php|gift.php|idcPro.php|index.php|message.php|news_deal.php|pay.php|payReturn.php|payServer.php|pay_deal.php|plugin_deal.php|read.php|readDeal2.php|users.php|usersCenter.php|usersNewsUpFile.php|usersNewsUpImg.php|usersNews_deal.php|users_deal.php|wapCheck.php|goodsRead.php|makeHtml_runWap.php|payWeb.php|.htaccess|usersCenter_deal.php|deal_js.php|logoAdd.php|'.
			'goods/index.php|'.
			'inc/classTemplate.php|inc/classTemplateOTCMS.php|inc/classUsersCenter.php|inc/classUsersNews.php|inc/classWapArea.php|inc/classWapContent.php|inc/classWapIndex.php|inc/classWapJS.php|inc/classWapList.php|inc/VerCode/VerCode.ttf|inc/VerCode/VerCode1.php|inc/VerCode/VerCode2.php|inc/VerCode/VerCode3.php|inc/VerCode/VerCode4.php|'.
			'web.config|cache/web.config|html/web.config|images/web.config|js/web.config|skin/web.config|template/web.config|tools/web.config|'.
			'news/index.php|'.
			'tools/geetest/class.geetestlib.php|'.
			'weixin/api.php|weixin/form.php|weixin/form_deal.php|weixin/otcmsRec.php|weixin/receive.php|weixin/success.php|weixin/userInfo.php|weixin/userInfo2.php|weixin/userInfo_deal.php|weixin/webBottom.php|weixin/webTop.php|weixin/writeInfo_deal.php|'.
			'message/index.php|message/posts.php|';
			break;

		case 'message':
			$fileList='|index.php|posts.php|';
			break;

		case 'web_config':
			$fileList='|web.config|rewrite.config|lock.txt|';
			break;

	}

	$retTabStr='';
	switch ($dirName){
		case 'before':
			$filePath = OT_ROOT . 'config.php';
			$fileContent = File::Read($filePath);

			if (strpos($fileContent,'eval') !== false || strpos($fileContent,'request') !== false){
				$retTabStr .= '
				<tr>
					<td>config.php <span style="color:red;">（里面含[eval][request]等危险敏感字符，请检查）</span></td>
					<td>'. File::SizeUnit(filesize($filePath)) .'</td>
					<td>'. File::GetCreateTime($filePath) .'</td>
					<td>'. File::GetRevTime($filePath) .'</td>
					<td><br /></td>
				</tr>
				';
			}

			$retTabStr .= GetDirFileList(OT_ROOT, $fileList, $extList);

			$extList = '/html/htm/xml/js/css/bmp/jpg/jpeg/gif/png/tif/tiff/swf/doc/xls/txt/ppt/docx/xlsx/pptx/pdf/rar/zip/avi/mpeg/mpg/ra/rm/rmvb/mov/qt/asf/wmv/iso/bin/img/mp3/wma/wav/mod/cd/md/aac/mid/ogg/m4a/';
			$fileList='||';

			$adminURL	= GetUrl::CurrDir();
			$beforeURL	= GetUrl::CurrDir(1);
			$adminDirName = substr($adminURL,strlen($beforeURL),-1);

			$softDirList = '|'. $adminDirName .'|'. substr(OT_dbDir,0,-1) .'|'. substr(OT_dbBakDir,0,-1) .'|cache|go|goods|inc|inc_img|install|js|news|pay|pluDef|plugin|smarty|temp|template|tools|upFiles|wap|message|web_config|';

			$folderI = 0;

			if ($handle = opendir(OT_ROOT)) {
				while (($file = readdir($handle)) !== false) {
					if ($file != '.' && $file != '..' && is_dir(OT_ROOT . $file)) {
						$folderI ++;
						if (strpos($softDirList,'|'. $file .'|') === false){
							if ($file == 'html'){ $fileList = '|web.config|'; }else{ $fileList = '||'; }
							$retTab2Str = GetDirAndFileList(OT_ROOT . $file .'/', $fileList, $extList, '', $file);
							if (strlen($retTab2Str) > 3){
								$retTab2Str = '<tr><td colspan="4"><b>'. $file .'/ 目录</b></td></tr>'. $retTab2Str;
							}
							$retTabStr .= $retTab2Str;
						}
					}
				}
			}
			closedir($handle);

			echo('
			</table>
			');
			break;

		default:
			if (file_exists($forePath)){
				$retTabStr = GetDirAndFileList($forePath, $fileList, $extList, '', $dirName);
			}else{
				$retTabStr = 'False';
			}
	}

	if ($retTabStr == 'False'){
		echo('
		<div style="color:red;padding:8px 0;"><b>'. $dirTitle .'/</b> 检查结果：<span style="color:blue;">不存在</span></div>
		');
	}elseif (strlen($retTabStr) > 3){
		echo('
		<div style="color:red;padding:8px 0;"><b>'. $dirTitle .'/</b> 检查结果：</div>
		<table style="width:95%;" cellpadding="0" cellspacing="0" class="tabList1 padd5td">
		<tr><td width="40%">文件名</td><td width="10%">大小</td><td width="20%">创建时间</td><td width="20%">最后修改时间</td><td width="10%" align="center">操作</td></tr>
		'. $retTabStr .'
		</table>
		');
	}

}



// 检查文件
function CalcSiteSize(){
/*
	forePath = server.mappath('../')


	Dim fso,folderObj,subFolderObj,folderI,folderSize,fileI,fileSize

    Set fso = CreateObject("Scripting.FileSystemObject")
	Set folderObj=fso.GetFolder(forePath)

	folderI=0
	folderSize=0
	Set subFolderObj=folderObj.Subfolders
	For Each subFolder In subFolderObj
		folderI = folderI + 1 
		folderSize = folderSize + subFolder.size
	Next

	fileI=0
	fileSize=0
	Set fileObj=folderObj.Files
	For Each file In fileObj
		fileI = fileI + 1 
		fileSize = fileSize + file.size
	Next

	Set folderObj=Nothing
	Set subFolderObj=Nothing

	" 根目录下有'. $folderI .'个目录，'. $fileI .'个文件，
	echo('alert("程序空间占用预计'. File::SizeUnit(folderSize+fileSize) .'");')
	*/
}


// 批量设置表处理
function DbDeal(){
	global $DB,$mudi,$menuFileID,$menuTreeID,$dataType,$dataTypeCN,$systemArr,$skin;

	$backURL		= OT::PostStr('backURL');
	$dataType		= OT::PostStr('dataType');
	$dataTypeCN		= OT::PostStr('dataTypeCN');
	$mode			= OT::PostStr('mode');
	$selDataID		= OT::Post('selDataID');

	$itemName = $sqlStr = '';
	switch ($mode){
		case 'check':
			$itemName = '检查';
			$sqlStr = 'CHECK TABLE ';
			break;

		case 'optimize':
			$itemName = '优化';
			$sqlStr = 'OPTIMIZE TABLE ';
			break;

		case 'repair':
			$itemName = '修复';
			$sqlStr = 'REPAIR TABLE ';
			break;

		case 'analyze':
			$itemName = '分析';
			$sqlStr = 'ANALYZE TABLE ';
			break;
	}

	if (count($selDataID)<=0){
		JS::AlertBackEnd('请先选择要设置的记录.');
	}

	$tabArr = array();
	for ($i=0; $i<count($selDataID); $i++){
		$tabArr[] = '`'. Str::RegExp($selDataID[$i],'sql') .'`';
	}
	$tabCount = count($tabArr);
	if ($tabCount == 0){
		JS::AlertBackEnd('请先选择要设置的表记录.');
	}
	$whereStr = implode(',', $tabArr);

	$showStr = '';
	$judRes = $DB->query($sqlStr . $whereStr);
	if ($judRes){
		$alertResult = '成功';
		if ($row = $judRes->fetchAll()){
			// $showStr .= '<pre>'. print_r($row,true) .'</pre>';
			$number = 0;
			$titleStr = '
				<table cellpadding="0" cellspacing="0" class="tabList1 padd8td" style="margin-top:5px;">
				<tr>
					<td align="center">编号</td>
					';
			foreach($row as $val){
				$number ++;
				$showStr .= '
					<tr>
						<td align="center">'. $number .'</td>
						';
				foreach($val as $key => $val2){
					if ($number == 1){ $titleStr .= '<td align="center">'. $key .'</td>'; }
					$showStr .= '<td>'. $val2 .'</td>';
				}
				if ($number == 1){ $titleStr .= '</tr>'; }
				$showStr .= '</tr>';
			}
		}
		$showStr = $titleStr . $showStr .'</table>';
	}else{
		JS::AlertBackEnd(''. $itemName .' '. $whereStr .' 执行失败！',$backURL);
	}

	Adm::AddLog(array(
		'note'		=> '【获取数据库结构】'. $itemName .' '. $tabCount .'张表 完成！',
		));

	if (strlen($showStr) > 5){
		$skin->WebTop();

		echo('
		<div style="padding:10px;font-size:14px;">
		返回结果：'. $showStr .'<br />
		<div style="margin-top:15px;"><b>'. $itemName .'表 执行完毕</b>。&ensp;<a href="'. $backURL .'" style="color:red;">&gt;&gt;点击返回</a></div>
		<script language="javascript" type="text/javascript">
		WindowHeight(0);
		</script>
		</div>
		');
	}else{
		JS::AlertHref(''. $itemName .' '. $whereStr .' 完成！.',$backURL);
	}

}


// SQL语句调试
function SqlDeal(){
	global $DB,$MB,$skin,$mudi,$menuFileID,$menuTreeID;

	$backURL		= OT::PostStr('backURL');
	$sqlContent		= OT::PostStr('sqlContent');
	$pwdMode		= OT::PostStr('pwdMode');
	$pwdKey			= OT::PostStr('pwdKey');
	$userpwd		= OT::PostStr('userpwd');

	if (strlen($sqlContent) == 0){
		JS::AlertBackEnd('SQL语句不能为空.');
	}

	$newSql = strtolower($sqlContent);
	if (strpos($newSql,'into outfile') !== false){
		JS::AlertBackEnd('SQL语句中不能含有内容“into outfile”.');
	}elseif (strpos($newSql,'0x') !== false){
		JS::AlertBackEnd('SQL语句中不能含有内容“0x”.');
	}

	$urow=$DB->GetRow('select MB_userpwd,MB_userKey from '. OT_dbPref .'member where MB_ID='. $MB->mUserID);
	$userpwd	= OT::DePwdData($userpwd, $pwdMode, $pwdKey);
	$userpwd = md5(md5($userpwd) . $urow['MB_userKey']);
		if ($urow['MB_userpwd'] != $userpwd){
			JS::AlertBackEnd('登录密码错误！');
		}
	unset($urow);

	$sqlArr = array();
	if (strpos($sqlContent, ';') !== false){
		preg_match_all( "@([\s\S]+?;)\h*[\n\r]@" , $sqlContent . PHP_EOL , $sqlArr ); // 数据以分号;\n\r换行  为分段标记
		!empty( $sqlArr[1] ) && $sqlArr = $sqlArr[1];
		$sqlArr = array_filter($sqlArr);
	}else{
		$sqlArr[] = $sqlContent;
	}
	$count = count($sqlArr);

	$succNum = $failNum = 0;
	$showStr = '';
	for($i=0; $i<$count ;$i++){
		$judRes = $DB->query($sqlArr[$i]);
			if ($judRes){
				$succNum ++;
				if ($row = $judRes->fetchAll()){
					$showStr .= '<pre>'. print_r($row,true) .'</pre>';
				}
			}else{
				$failNum ++;
			}
	}


	if (strlen($showStr) > 5){
		echo('
		返回结果：'. $showStr .'<br />
		<div style="margin-top:15px;"><b>执行完毕</b>，共'. ($succNum+$failNum) .'条，成功'. $succNum .'条，失败'. $failNum .'条。<a href="'. $backURL .'">点击返回</a></div>
		');
		// JS::DiyEnd('setTimeout(\'alert("执行'. $alertResult .'");document.location.href="'. $backURL .'";\','. 1000 .');');
	}else{
		JS::AlertHrefEnd('执行完成，共'. ($succNum+$failNum) .'条，成功'. $succNum .'条，失败'. $failNum .'条。', $backURL);
	}

}


// SQL语句调试-快捷指令
function SqlMoreDeal(){
	global $DB;

	$type = OT::GetStr('type');

	if ($type == 'infoClear'){
		$total = $DB->GetOne('select count(1) from '. OT_dbPref .'info');
		if ($total > 0){
			JS::AlertHrefEnd('文章还有 '. $total .' 篇，请先删除所有文章再进行该操作','info.php?mudi=manage&dataType=news&dataTypeCN='. urlencode('文章'));
		}else{
			$DB->query('TRUNCATE TABLE '. OT_dbPref .'info');
			JS::AlertHrefEnd('执行完成','sysCheckFile.php?mudi=sql');
		}
	
	}elseif ($type == 'infoTypeClear'){
		$total = $DB->GetOne('select count(1) from '. OT_dbPref .'infoType');
		if ($total > 0){
			JS::AlertHrefEnd('栏目还有 '. $total .' 个，请先删除所有栏目再进行该操作','infoType.php?mudi=manage&dataType=news&dataTypeCN='. urlencode('栏目'));
		}else{
			$DB->query('TRUNCATE TABLE '. OT_dbPref .'infoType');
			JS::AlertHrefEnd('执行完成','sysCheckFile.php?mudi=sql');
		}

	}else{
		JS::AlertBackEnd('目的不明确（'. $type .'）');
	}
}


// 可选文件
function OptionFile(){
	global $DB,$skin,$mudi,$menuFileID,$menuTreeID;

	$mode = OT::GetStr('mode');
	$file = OT::GetStr('file');
		if (! in_array($file,array('ip','simsun','yahei','pscws4'))){
			JS::AlertBackEnd('文件参数有错.');
		}

	$fileUrl = 'http://d.otcms.com/phpExt/'. $file .'.zip';		// 要下载的文件地址
	$filePath = OT_ROOT .'upFiles/download/'. $file .'.zip';	// 本地存放位置

	@ini_set('max_execution_time', 0);
	@set_time_limit(0); 

	switch ($mode){
		// 下载文件
		case 'down':
			if (! File::IsRemoteExists($fileUrl)){
				JS::AlertBackEnd('文件不存在，下载失败');
			}
			?>
			<table border="1" width="300">
			<tr><td width="100">文件大小</td><td width="200"><div id="downSize">未知长度</div></td></tr>
			<tr><td>已经下载</td><td><div id="downRate">0</div></td></tr> <tr><td>完成进度</td>
			<td>
				<div id="downBar" style="float:left;width:1px;text-align:center;color:#FFFFFF;background-color:#0066CC"></div>
				<div id="downText" style=" float:left">0%</div>
			</td>
			</tr>
			</table>

			<script language='javascript' type='text/javascript'>
			// 文件长度
			var filesize=0;
			function $id(obj) {return document.getElementById(obj);}

			// 设置文件长度
			function SetFileSize(fsize) { filesize=fsize; $id("downSize").innerHTML=fsize; }

			// 设置已经下载的,并计算百分比
			function SetFileRate(fsize) {
				$id("downRate").innerHTML = fsize;
				if(filesize > 0) {
					var percent = Math.round(fsize*100/filesize);
					$id("downBar").style.width = (percent +"%");
					if(percent > 0) {
						$id("downBar").innerHTML = percent +"%";
						$id("downText").innerHTML = "";
					} else {
						$id("downText").innerHTML = percent +"%";
					}
				}
			}
			</script>
			<?php
			ob_start();
			$file = fopen ($fileUrl, "rb");
			if ($file) {
				// 获取文件大小
				$filesize = -1;
				$headers = get_headers($fileUrl, 1);
				if (! array_key_exists("Content-Length", $headers)) $filesize=0;
				$filesize = $headers["Content-Length"];
				
				// 不是所有的文件都会先返回大小的，有些动态页面不先返回总大小，这样就无法计算进度了
				if ($filesize != -1) {
					echo "<script language='javascript' type='text/javascript'>SetFileSize('". $filesize ."');</script>";//在前台显示文件大小
				}
				$newf = fopen ($filePath, "wb");
				$downlen=0;
				if ($newf) {
					while(!feof($file)) {
						$data = fread($file, 1024 * 8 );
						// 默认获取8K
						$downlen += strlen($data);
						// 累计已经下载的字节数
						fwrite($newf, $data, 1024 * 8 );
						echo "<script language='javascript' type='text/javascript'>SetFileRate('". $downlen ."');</script>";
						// 在前台显示已经下载文件大小
						ob_flush();
						flush();
					}
				}
				if ($file) { fclose($file); }
				if ($newf) { fclose($newf); }
			}
			$alertRes = '成功';
			/*
			$SaveImg = new SaveImg();
			$srfArr = $SaveImg->SaveRemoteFile(OT_ROOT .'upFiles/download/'. $file .'.zip', 'http://d.otcms.com/phpExt/'. $file .'.zip', 0, 0)
			if ($srfArr['res']){
				$alertRes = '成功';
			}else{
				$alertRes = '失败';
			} */
			$alertStr = '下载'. $alertRes;
			break;
	
		// 解压
		case 'jieya':
			if (! file_exists($filePath)){
				JS::AlertBackEnd('文件不存在，无法解压');
			}
			if (! extension_loaded('zip')){
				JS::AlertBackEnd('不支持zip扩展，无法解压');
			}
			switch ($file){
				case 'ip':		$toPath = OT_ROOT .'tools/'; break;
				case 'simsun':	$toPath = OT_ROOT .'tools/'; break;
				case 'yahei':	$toPath = OT_ROOT .'tools/'; break;
				case 'pscws4':	$toPath = OT_ROOT .'tools/pscws4/etc/'; break;
			}
			$resArr = Zip::Jieya($filePath,$toPath);
			if ($resArr['res']) {
				$alertRes = '成功';
			}else{
				$alertRes = '失败，原因：'. $resArr['note'];
			}
			$alertStr = '解压'. $alertRes;
			break;
	
		// 删除
		case 'del':
			if ( File::Del($filePath) ){
				$alertRes = '成功';
			}else{
				$alertRes = '失败，请检查文件是否存在';
			}
			$alertStr = '删除'. $alertRes;
			break;

		default :
			JS::AlertBackEnd('mode参数错误（'. $mode .'）');
			break;
	}

	JS::AlertHrefEnd($alertStr, 'sysCheckFile.php?mudi=file');
}


// 删除无用文件
function Del(){
	global $DB,$skin,$mudi,$menuFileID,$menuTreeID;

	$theme		= OT::GetStr('theme');
	$fileType	= OT::GetStr('fileType');

	switch ($fileType){
		case 'admin':
			File::Del(OT_adminROOT .'inc/classBackupMySql.php');
			File::Del(OT_adminROOT .'inc/classSaveImg.php');
			File::Del(OT_adminROOT .'inc/classWebHtml.php');
			File::Del(OT_adminROOT .'js/inc/jquery-1.11.0.min.js');
			File::Del(OT_adminROOT .'js/ad.js');
			File::Del(OT_adminROOT .'js/database.js');
			File::Del(OT_adminROOT .'js/databaseMySQL.js');
			File::Del(OT_adminROOT .'ad.php');
			File::Del(OT_adminROOT .'ad_deal.php');
			File::Del(OT_adminROOT .'database.php');
			File::Del(OT_adminROOT .'database_deal.php');
			File::Del(OT_adminROOT .'databaseMySQL.php');
			File::Del(OT_adminROOT .'databaseMySQL_deal.php');
			break;

		case 'before':
			File::Del(OT_ROOT .'configDeal.php');
			File::Del(OT_ROOT .'configJs.php');
			File::Del(OT_ROOT .'usersApi.php');
			File::Del(OT_ROOT .'usersNews.php');
			File::Del(OT_ROOT .'usersWeb.php');
			break;

		case 'inc':
			File::Del(OT_ROOT .'inc/class_ip.php');
			break;

		case 'pluDef':
			File::Del(OT_ROOT .'pluDef/classAppApiQQ.php');
			File::Del(OT_ROOT .'pluDef/classAppApiWeibo.php');
			File::Del(OT_ROOT .'pluDef/classAppApiWeixin.php');
			File::Del(OT_ROOT .'pluDef/classAppUserMoney.php');
			File::Del(OT_ROOT .'pluDef/classAppVpsApi.php');
			File::Del(OT_ROOT .'pluDef/classAppVpsBase.php');
			File::Del(OT_ROOT .'pluDef/classAppVpsXingwai.php');
			break;

		case 'plugin':
			File::Del(OT_ROOT .'plugin/classAppApiQQ.php');
			File::Del(OT_ROOT .'plugin/classAppApiWeibo.php');
			File::Del(OT_ROOT .'plugin/classAppUserMoney.php');
			File::Del(OT_ROOT .'plugin/classTplBlue.php');
			break;

		case 'smarty':
			File::Del(OT_ROOT .'smarty/plugins/shared.mb_wordwrap.php');
			File::Del(OT_ROOT .'smarty/sysplugins/smarty_internal_extension_clear.php');
			File::Del(OT_ROOT .'smarty/sysplugins/smarty_internal_extension_codeframe.php');
			File::Del(OT_ROOT .'smarty/sysplugins/smarty_internal_extension_config.php');
			File::Del(OT_ROOT .'smarty/sysplugins/smarty_internal_extension_defaulttemplatehandler.php');
			File::Del(OT_ROOT .'smarty/sysplugins/smarty_internal_filter_handler.php');
			File::Del(OT_ROOT .'smarty/sysplugins/smarty_internal_function_call_handler.php');
			File::Del(OT_ROOT .'smarty/sysplugins/smarty_internal_get_include_path.php');
			File::Del(OT_ROOT .'smarty/sysplugins/smarty_internal_utility.php');
			File::Del(OT_ROOT .'smarty/sysplugins/smarty_internal_write_file.php');
			break;

		case 'template':
			File::Del(OT_ROOT .'template/def_blog/bbs.html');
			File::Del(OT_ROOT .'template/def_blog/sitemap.html');
			File::Del(OT_ROOT .'template/def_blog/usersCenter.html');
			File::Del(OT_ROOT .'template/def_blue/sitemap.html');
			File::Del(OT_ROOT .'template/def_blue/usersCenter.html');
			break;

		case 'tools':
			File::Del(OT_ROOT .'tools/CuPlayer/CuSunV2set.xml');
			File::DelDir(OT_ROOT .'tools/kindeditor/');
			break;

		case 'wap':
			File::Del(OT_ROOT .'wap/css/share.css');
			File::Del(OT_ROOT .'wap/inc/classWapUrl.php');
			File::Del(OT_ROOT .'wap/js/webIndex.js');
			File::Del(OT_ROOT .'wap/js/webMessage.js');
			File::Del(OT_ROOT .'wap/js/webNewsShow.js');
			File::Del(OT_ROOT .'wap/js/webTop.js');
			File::Del(OT_ROOT .'wap/js/webUsers.js');
			File::Del(OT_ROOT .'wap/js/webUsersCenter.js');
			File::Del(OT_ROOT .'wap/form_deal.php');
			File::Del(OT_ROOT .'wap/usersNews.php');
			break;

	}

	echo('<script language="javascript" type="text/javascript">alert("删除完成！");parent.location.reload();</script>');

}


// 查看图片木马源码
function UpFilesLook(){
	global $DB,$skin,$mudi,$menuFileID,$menuTreeID;

	$file	= OT::GetStr('file');
	$file = str_replace(array('../',"..\\",'%'), array('','',''), $file);

	$filePath = OT_ROOT .'upFiles/'. $file;
	if (file_exists($filePath)){
		die(Str::MoreReplace(File::Read($filePath),'html'));
	}else{
		die('该文件不存在（../upFiles/'. $file .'）');
	}

}


// 删除图片木马
function UpFilesDel(){
	global $DB,$skin,$mudi,$menuFileID,$menuTreeID;

	$file	= OT::GetStr('file');
	$file = str_replace(array('../',"..\\",'%'), array('','',''), $file);

	$filePath = OT_ROOT .'upFiles/'. $file;
	if (Is::ImgMuma($filePath)){
		File::Del($filePath);
		echo('<script language="javascript" type="text/javascript">parent.$id("img'. md5($file) .'").style.display="none";</script>');
	}else{
		echo('<script language="javascript" type="text/javascript">alert("该图片('. $file .')不是图片木马，不提供删除！\n请刷新页面重新检查看看.");</script>');
	}

}



function GetDirFileList($dirPath, $fileList, $extList){
	$retStr='';
	$folderI=0;

	if ($handle = opendir($dirPath)) {
		while (($file = readdir($handle)) !== false) {
			if ($file != '.' && $file != '..' && (! is_dir($dirPath . $file))) {
				$folderI ++;
				if (strpos($fileList,'|'. $file .'|') === false){
					$fileExt = File::GetExt($file);
					if (strpos($extList,'/'. $fileExt .'/')===false && $file!='Thumbs.db'){
						$fileNote = '';
						switch ($file){
							case 'web.config':		$fileNote = '&ensp;&ensp;<span style="color:blue;">（IIS配置文件）</span>'; break;
							case '.htaccess':		$fileNote = '&ensp;&ensp;<span style="color:blue;">（Apache伪静态规则）</span>'; break;
							case '.user.ini':		$fileNote = '&ensp;&ensp;<span style="color:blue;">（防跨站配置文件）</span>'; break;
							default:
								if (strpos($file,'del_classApp') !== false){
									$fileNote = '&ensp;&ensp;<span style="color:blue;">（疑似禁用状态下的插件文件）</span>';
								}
								break;
						}
						$retStr .= '
						<tr>
							<td>'. Str::GB2UTF($file) . $fileNote .'</td>
							<td>'. File::SizeUnit(filesize($dirPath . $file)) .'</td>
							<td>'. File::GetCreateTime($dirPath . $file) .'</td>
							<td>'. File::GetRevTime($dirPath . $file) .'</td>
							<td align="center"><a href="../'. $file .'" target="_blank" style="color:blue;">查看</a></td>
						</tr>
						';
					}
				}
			}
		}
	}
	closedir($handle);

	return $retStr;
}


function GetDirAndFileList($path, $fileList, $extList, $dirPath, $chkDirName){
	$retStr = '';
	$dirArr = array();

	if ($handle = opendir($path)) {
		while (($file = readdir($handle)) !== false) {
			if ($file != '.' && $file != '..') {
				if (is_dir($path . $file)){
					$dirArr[] = $file;
				}else{
					if (strpos($fileList,'|'. $dirPath . $file .'|') === false){
						$fileExt = File::GetExt($file);
						if (strpos($extList,'/'. $fileExt .'/')===false && $file!='Thumbs.db'){
							if (in_array($chkDirName,array('Data','Data_backup'))){
								$lookPath = '';
							}elseif ($chkDirName == 'admin'){
								$lookPath = '<a href="'. $dirPath . $file .'" target="_blank" style="color:blue;">查看</a>';
							}else{
								$lookPath = '<a href="../'. $chkDirName .'/'. $dirPath . $file .'" target="_blank" style="color:blue;">查看</a>';
							}
							$fileNote = '';
							switch ($file){
								case 'web.config':		$fileNote = '&ensp;&ensp;<span style="color:blue;">（IIS配置文件）</span>'; break;
								case '.htaccess':		$fileNote = '&ensp;&ensp;<span style="color:blue;">（Apache伪静态规则）</span>'; break;
								case '.user.ini':		$fileNote = '&ensp;&ensp;<span style="color:blue;">（防跨站配置文件）</span>'; break;
								default:
									if (strpos($file,'del_classApp') !== false){
										$fileNote = '&ensp;&ensp;<span style="color:blue;">（疑似禁用状态下的插件文件）</span>';
									}
									break;
							}
							$retStr .= '
								<tr>
									<td>'. Str::GB2UTF($dirPath . $file) . $fileNote .'</td>
									<td>'. File::SizeUnit(filesize($path . $file)) .'</td>
									<td>'. File::GetCreateTime($path . $file) .'</td>
									<td>'. File::GetRevTime($path . $file) .'</td>
									<td align="center">'. $lookPath .'</td>
								</tr>
								';
						}
					}
				}
			}
		}
	}
	closedir($handle);

	foreach ($dirArr as $val){
		$nowpath=$path .'/'. $val .'/';
		$retStr .= GetDirAndFileList($nowpath, $fileList, $extList, $dirPath . $val .'/', $chkDirName);
	}

	return $retStr;
}


function GetDirAndFileImgList($path, $dirPath, $firstDir){
	$retStr = '';
	$extList = '/bmp/jpg/jpeg/gif/png/';
	$dirArr = array();

	if ($handle = opendir($path)) {
		while (($file = readdir($handle)) !== false) {
			if ($file != '.' && $file != '..') {
				if (is_dir($path . $file)){
					$dirArr[] = $file;
				}else{
					$fileExt = File::GetExt($file);
					if (strpos($extList,'/'. $fileExt .'/')!==false){
						$imgPath = $path . $file;
						if (Is::ImgMuma($imgPath)){
							$retStr .= '
								<tr id="img'. md5($firstDir .'/'. $dirPath . $file) .'">
									<td>'. $dirPath . $file .'</td>
									<td>'. File::SizeUnit(filesize($imgPath)) .'</td>
									<td>'. File::GetCreateTime($imgPath) .'</td>
									<td>'. File::GetRevTime($imgPath) .'</td>
									<td>疑似木马图片</td>
									<td>
										<a href="../upFiles/'. $firstDir .'/'. $dirPath . $file .'" target="_blank" style="color:blue;">访问</a>&ensp;
										<a href="sysCheckFile_deal.php?mudi=upFilesLook&file='. urlencode($firstDir .'/'. $dirPath . $file) .'" target="_blank">看源码</a>&ensp;
										<a href="#" onclick=\'DataDeal.location.href="sysCheckFile_deal.php?mudi=upFilesDel&file='. urlencode($firstDir .'/'. $dirPath . $file) .'";return false;\' style="color:red;">删除</a>
									</td>
								</tr>
								';
						}
					}
				}
			}
		}
	}

	foreach ($dirArr as $val){
		$nowpath=$path .'/'. $val .'/';
		$retStr .= GetDirAndFileImgList($nowpath,$dirPath . $val ."/", $firstDir);
	}

	return $retStr;
}


?>
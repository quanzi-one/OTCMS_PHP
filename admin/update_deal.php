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
$MB->Open('','login',2);


$MB->IsAdminRight('alertBack');


if ($mudi == 'dbInfo'){
	dbInfo();
	die();
}



$beforeURL=GetUrl::CurrDir(1);
$dataTypeCN = '在线升级';


switch ($mudi){
	case 'changUpdateUrl':
		// 更改升级网址
		ChangUpdateUrl();
		break;

	case 'clearUpdateData':
		// 清空升级库
		ClearUpdateData();
		break;

	case 'del':
		// 删除单条升级记录
		del();
		break;

	case 'updateField':
		// 版本字段修复
		updateField();
		break;

	default:
		die('err');
}

$MB->Close();
$DB->Close();





// 更改升级网址
function ChangUpdateUrl(){
	global $DB,$beforeURL,$updateUrlVerStr,$sysAdminArr;

	$updateUrl = OT::GetStr('updateUrl');

	$record = array();
	$record['SA_updateUrl']		= $updateUrl;
	$judResult = $DB->UpdateParam('sysAdmin',$record,'SA_ID=1');
		if ($judResult){
			$alertResult = '成功';

			$Cache = new Cache();
			$result = $Cache->Php('sysAdmin');	// 更新缓存文件
		}else{
			$alertResult = '失败';
		}

	echo('// 更改'. $alertResult);
}



// 清空升级库
function ClearUpdateData(){
	global $DB,$beforeURL,$updateUrlVerStr,$sysAdminArr;

	$fileexe = $DB->query('select FD_ID,FD_fileName,FD_contentMd5 from '. OT_dbPref .'fileData where FD_mode=1');
		while ($row = $fileexe->fetch()){
			File::Del(UpdateAdminDir . $row['FD_ID'] .'_'. $row['FD_contentMd5'] .'.OTtpl');
		}
	$fileexe = null;

	$DB->query('delete from '. OT_dbPref .'fileData');
	$DB->query('delete from '. OT_dbPref .'fileVer');

	File::MoreDel(UpdateAdminDir,'OTtpl');

	echo('alert("清空升级库成功");');
}



// 删除
function del(){
	global $DB,$beforeURL,$updateUrlVerStr,$sysAdminArr;

	$dataID	= OT::GetInt('dataID');
	$theme	= OT::GetStr('theme');

	if ($dataID <= 0){
		JS::AlertEnd('指定ID错误');
	}

	$fileexe = $DB->query('select FD_ID,FD_fileName,FD_contentMd5 from '. OT_dbPref .'fileData where FD_verID='. $dataID .' and FD_mode=1');
		while ($row = $fileexe->fetch()){
			File::Del(UpdateAdminDir . $row['FD_ID'] .'_'. $row['FD_contentMd5'] .'.OTtpl');
		}
	$fileexe = null;

	$DB->query('delete from '. OT_dbPref .'fileData where FD_verID='. $dataID);
	$DB->query('delete from '. OT_dbPref .'fileVer where FV_ID='. $dataID);


	echo('<script language="javascript" type="text/javascript">parent.$id("data'. $dataID .'").style.display="none";</script>');
}



// 升级库信息
function dbInfo(){
	global $DB;

	$infoStr = '$id("updateDbInfo").innerHTML=\'<span style="color:red;">(升级记录'. $DB->GetOne('select count(FV_ID) from '. OT_dbPref .'fileVer') .'条)</span>\';';

	echo($infoStr);
}



function updateField(){
	global $DB,$collDbName;

//	require(OT_adminROOT .'collConobj.php');

	$ver	= OT::GetStr('ver');
	$newStr = '';

	if ($ver == 'themeMd5'){
		$succNum = $failNum = 0;
		$mtexe = $DB->query('select IF_ID,IF_theme from '. OT_dbPref .'info where IF_themeMd5 = "" or IF_themeMd5 is null');
		while ($row = $mtexe->fetch()){
			$res = $DB->UpdateParam('info', array('IF_themeMd5'=>md5($row["IF_theme"])), 'IF_ID='. $row["IF_ID"]);
			if ($res){
				$succNum ++;
			}else{
				$failNum ++;
			}
		}
		unset($mtexe);
		$newStr .= '[修复文章缺失标题MD5，成功 '. $succNum .' 条，失败 '. $failNum .' 条.]';
	}

	JS::Alert('运行完成，如果还是有问题，请先备份下数据库，然后压缩下数据库，再进行该操作。\n\n'. $newStr);
}
?>
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
$MB->Open('','login',2);

$skin->WebTop();


	$tplPcDir='../template/';
	$tplWapDir='../wap/template/';


switch($mudi){
	case 'add':
		$menuFileID = 207;
		$MB->IsSecMenuRight('alertBack',$menuFileID,$dataType);
		AddOrRev();
		break;

	case 'rev':
		$menuFileID = 208;
		$MB->IsSecMenuRight('alertBack',$menuFileID,$dataType);
		AddOrRev();
		break;

	case 'del':
		$menuFileID = 209;
		$MB->IsSecMenuRight('alert',$menuFileID,$dataType);
		del();
		break;

	case 'addFile': case 'revFile':
		AddOrRevFile();
		break;

	case 'delFile':
		$menuFileID = 209;
		$MB->IsSecMenuRight('alert',$menuFileID,$dataType);
		DelFile();
		break;

	case 'change':
		$menuFileID = 208;
		$MB->IsSecMenuRight('alert',$menuFileID,$dataType);
		change();
		break;

	default:
		die('err');
}

$skin->WebBottom();

$MB->Close();
$DB->Close();





// 修改
function AddOrRev(){
	global $DB,$MB,$skin,$mudi,$dataType,$dataTypeCN;

	$backURL		= OT::PostStr('backURL');
	$dataType		= OT::PostStr('dataType');
	$dataTypeCN		= OT::PostStr('dataTypeCN');
	$dataMode		= OT::PostStr('dataMode');
	$dataModeStr	= OT::PostStr('dataModeStr');
	$dataID			= OT::PostInt('dataID');

	$type		= OT::PostStr('type');
	$theme		= OT::PostStr('theme');
	$dir		= OT::PostStr('dir');
	$rank		= OT::PostInt('rank');
	$state		= OT::PostInt('state');

	if (strpos($dir,'..') !== false){
		JS::AlertBackEnd('禁止目录名中出现“..”字符串');
	}

	$record = array();
	$record['TP_type']	= $type;
	$record['TP_theme']	= $theme;
	$record['TP_dir']	= $dir;
	$record['TP_rank']	= $rank;
	$record['TP_state']	= $state;

	if ($mudi == 'add'){
		$alertMode='添加';
		$judResult = $DB->InsertParam('template',$record);
	}else{
		$alertMode='修改';
		$judResult = $DB->UpdateParam('template',$record,'TP_ID='. $dataID);
	}
		if ($judResult){
			$alertResult = '成功';
		}else{
			$alertResult = '失败';
		}

	Adm::AddLog(array(
		'title'	=> '模板',
		'theme'	=> $theme,
		'note'	=> '【'. $dataTypeCN .'】'. $alertMode . $alertResult .'！',
		));

	JS::AlertHrefEnd($alertMode . $alertResult,'template.php?mudi=manage&sel='. $type .'&dataType=&dataTypeCN='. urlencode('模板') .'');

}



// 添加、修改文件
function AddOrRevFile(){
	global $DB,$MB,$skin,$mudi,$dataType,$dataTypeCN,$tplPcDir,$tplWapDir;

	$backURL		= OT::PostStr('backURL');
	$dataType		= OT::PostStr('dataType');
	$dataTypeCN		= OT::PostStr('dataTypeCN');
	$dataMode		= OT::PostStr('dataMode');
	$dataModeStr	= OT::PostStr('dataModeStr');

	$sel			= OT::GetStr('sel');
	$isBak			= OT::PostInt('isBak');
	$filePath		= OT::PostStr('filePath');
	$fileName		= OT::PostStr('fileName');
	$fileContent	= str_replace(array('%'. chr(62), chr(60) .'%', '?'. chr(62), chr(60) .'?'), array('%&gt;', '&lt;%', '?&gt;', '&lt;?'), OT::PostStr('fileContent'));

	if ($filePath=='' || $fileName=='' || $fileContent==''){
		JS::AlertBackEnd('表单内容接收不全');
	}
	if (strpos($filePath,'..') !== false){
		JS::AlertBackEnd('禁止查询路径中出现“..”字符串');
	}

	if ($sel == 'wap'){
		$templateDir = $tplWapDir;
	}else{
		$templateDir = $tplPcDir;
	}

	$filePath = $templateDir . $filePath;
//	$fileName	= Mid(filePath,InstrRev(filePath,'/')+1)
	if (! File::IsExists($filePath)){
		JS::AlertBackEnd('找不到该文件，无法修改');
	}
	if ($fileName != Str::Filter($fileName,'fileName')){
		JS::AlertBackEnd('文件名除了“空格”“.”“-”“_”外不允许出现其他特殊符号');
	}
	$revExtStr = 'htm|html|shtm|shtml|css|js|txt|xml|wml';
	$fileExt = File::GetExt($filePath);
	if (strpos('|'. $revExtStr .'|','|'. $fileExt .'|') === false){
		JS::AlertBackEnd('当前仅允许修改 '. $revExtStr .' 这些格式('. $fileExt .')。');
	}

	if ($mudi == 'add'){
		$menuFileID = 207;
		$MB->IsSecMenuRight('alertBack',$menuFileID,$dataType);
		$alertMode = '新增';
	}else{
		$menuFileID = 208;
		$MB->IsSecMenuRight('alertBack',$menuFileID,$dataType);
		$alertMode = '修改';
		if ($isBak == 1){
			File::Copy($filePath,substr($filePath,0,(strlen($fileExt)+1)*(-1)) .'_bak'. TimeDate::Get('datetimeStr') .'.'. $fileExt);
		}
	}

	$judResult = File::Write($filePath,$fileContent);
		if ($judResult){
			$alertResult = '成功';
		}else{
			$alertResult = '失败';
		}

	Adm::AddLog(array(
		'title'	=> '文件名',
		'theme'	=> $fileName,
		'note'	=> '【'. $dataTypeCN .'】'. $alertMode . $alertResult .'！',
		));

	JS::AlertHrefEnd($alertMode . $alertResult,$backURL);

}



// 删除文件
function DelFile(){
	global $DB,$MB,$skin,$mudi,$dataType,$dataTypeCN,$tplPcDir,$tplWapDir;

	$num		= OT::GetInt('num');
	$sel		= OT::GetStr('sel');
	$filePath	= OT::GetStr('filePath');

	if (strpos(substr($filePath,3),'..') !== false){
		JS::AlertBackEnd('禁止查询路径中出现“..”字符串');
	}

	if ($sel == 'wap'){
		$templateDir = $tplWapDir;
	}else{
		$templateDir = $tplPcDir;
	}

	$judResult = File::Del($templateDir . $filePath);
		if ($judResult){
			$alertResult = '成功';
			echo('<script language="javascript" type="text/javascript">parent.$id("data'. $num .'").style.display="none";</script>');
		}else{
			$alertResult = '失败';
			JS::AlertEnd('删除失败（'. $templateDir . $filePath .'）');
		}

	Adm::AddLog(array(
		'theme'	=> $theme,
		'note'	=> '【'. $dataTypeCN .'】删除'. $alertResult .'！',
		));

}



// 删除模板
function del(){
	global $DB,$MB,$skin,$mudi,$dataType,$dataTypeCN;

	$dataID	= OT::GetInt('dataID');
	$theme	= OT::GetStr('theme');

	if ($dataID <= 0){
		JS::AlertEnd('指定ID错误！');
	}

	$judResult = $DB->query('delete from '. OT_dbPref .'template where TP_ID='. $dataID);
		if ($judResult){
			$alertResult = '成功';
		}else{
			$alertResult = '失败';
		}

	Adm::AddLog(array(
		'theme'	=> $theme,
		'note'	=> '【'. $dataTypeCN .'】删除'. $alertResult .'！',
		));

	echo('<script language="javascript" type="text/javascript">parent.$id("data'. $dataID .'").style.display="none"</script>');

}



// 更改模板
function change(){
	global $DB,$MB,$skin,$mudi,$dataType,$dataTypeCN;

	$dataID		= OT::GetInt('dataID');
	$backURL	= OT::GetStr('backURL');
	$sel		= OT::GetStr('sel');

	if ($dataID <= 0){
		JS::AlertBackEnd('指定ID错误！');
	}

	$dataexe = $DB->query('select * from '. OT_dbPref .'template where TP_ID='. $dataID .' and TP_type='. $DB->ForStr($sel));
		if (! $row = $dataexe->fetch()){
			JS::AlertBackEnd('找不到相关模板信息！');
		}
		$TP_theme	= $row['TP_theme'];
		$TP_dir		= $row['TP_dir'];
	unset($dataexe);

	$alertStr = '';
	if ($sel == 'wap'){
		$selCN = '手机端';
		$judResult = $DB->query('update '. OT_dbPref .'wap set WAP_templateDir='. $DB->ForStr($TP_dir) .'');
			if ($judResult){
				$alertResult = '成功';
			}else{
				$alertResult = '失败';
			}

		$Cache = new Cache();
		$judRes = $Cache->Php('wap');
		if (! $judRes){ $alertStr = '\n../cache/php/wap.php 生成失败，请检查该目录或者文件是否有写入/修改权限！'; }
	}else{
		$selCN = '电脑端';
		$judResult = $DB->query('update '. OT_dbPref .'system set SYS_templateDir='. $DB->ForStr($TP_dir) .'');
			if ($judResult){
				$alertResult = '成功';
			}else{
				$alertResult = '失败';
			}

		$Cache = new Cache();
		$judRes = $Cache->Php('system');
		if (! $judRes){ $alertStr = '\n../cache/php/system.php 生成失败，请检查该目录或者文件是否有写入/修改权限！'; }
	}

	Adm::AddLog(array(
		'theme'	=> $TP_theme,
		'note'	=> '【'. $dataTypeCN .'】更换应用'. $selCN .'模板'. $alertResult .'！',
		));

	JS::AlertHref('更换应用'. $selCN .'模板'. $alertResult .'\n'. $alertStr, $backURL);

}

?>
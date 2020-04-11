<?php
require(dirname(__FILE__) .'/conobj.php');

$mudi	= OT::GetStr('mudi');


switch ($mudi){
	case 'newsDeal':
		NewsDeal();
		break;

}

$DB->Close();




// 文章信息处理，如增加文章阅读量
function NewsDeal(){
	global $DB;

	$mode		= OT::GetStr('mode');
	$dataID		= OT::GetInt('dataID');
	$isNoReturn	= OT::GetInt('isNoReturn');		// 0 纯静态或有缓存，1 无静态无缓存
		if ($mode != 'wap'){ $mode = 'pc'; }

	$judAdd = true;
	$infoSysArr = Cache::PhpFile('infoSys');
	if ($infoSysArr['IS_oneReadNum'] == 1){
		if (! isset($_SESSION[OT_SiteID .'readNumList'])){ $_SESSION[OT_SiteID .'readNumList'] = ''; }
		if (strpos($_SESSION[OT_SiteID .'readNumList'],'['. $dataID .']') !== false){
			$judAdd = false;
		}else{
			$_SESSION[OT_SiteID .'readNumList'] .= '['. $dataID .']';
		}
	}

	if ($judAdd){
		$addReadNum = OT::RndNumTo($infoSysArr['IS_readNum1'],$infoSysArr['IS_readNum2']);
		if ($addReadNum < 1){ $addReadNum = 1; }

		$DB->query('update '. OT_dbPref .'info set IF_readNum=IF_readNum+'. $addReadNum .',IF_readNumDay=IF_readNumDay+1,IF_readNumWeek=IF_readNumWeek+1,IF_readNumMonth=IF_readNumMonth+1,IF_readNumYear=IF_readNumYear+1 where IF_ID='. $dataID);
		echo('/* +'. $addReadNum .' */'. PHP_EOL);
	}else{
		echo('/* 已访问过 */'. PHP_EOL);
	}

	if ($isNoReturn == 0){
		$infoexe = $DB->query('select IF_readNum,IF_replyNum,IF_mediaFile from '. OT_dbPref .'info where IF_ID='. $dataID);
			if ($row = $infoexe->fetch()){
				echo('
				try {
					document.getElementById("infoReadNum").innerHTML="'. $row['IF_readNum'] .'";
				}catch (e) {}
				try {
					document.getElementById("infoReplyNum").innerHTML="'. $row['IF_replyNum'] .'";
				}catch (e) {}
				try {
					document.getElementById("replyBoxNum").innerHTML="'. $row['IF_replyNum'] .'";
				}catch (e) {}
				$(function (){
					try {
						document.getElementById("infoReadNum").innerHTML="'. $row['IF_readNum'] .'";
					}catch (e) {}
					try {
						document.getElementById("infoReplyNum").innerHTML="'. $row['IF_replyNum'] .'";
					}catch (e) {}
					try {
						document.getElementById("replyBoxNum").innerHTML="'. $row['IF_replyNum'] .'";
					}catch (e) {}
				});
				');
			}
		unset($infoexe);
	}
}

?>
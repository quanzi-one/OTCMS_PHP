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



switch ($mudi){
	case 'add':
		$menuFileID = 180;
		$MB->IsSecMenuRight('alertBack',$menuFileID,$dataType);
		AddOrRev();
		break;

	case 'rev':
		$menuFileID = 182;
		$MB->IsSecMenuRight('alertBack',$menuFileID,$dataType);
		AddOrRev();
		break;

	case 'del':
		$menuFileID = 183;
		$MB->IsSecMenuRight('alert',$menuFileID,$dataType);
		del();
		break;

	default:
		die('err');
}

$MB->Close();
$DB->Close();





// 新增/修改
function AddOrRev(){
	global $DB,$skin,$mudi,$dataType,$dataTypeCN;

	$dataType		= OT::PostStr('dataType');
	$dataTypeCN		= OT::PostStr('dataTypeCN');
	$dataID			= OT::PostInt('dataID');

	$theme			= OT::PostStr('theme');
	$infoTotalNum	= OT::PostInt('infoTotalNum');
	$infoDayNum		= OT::PostInt('infoDayNum');
	$infoScore1		= OT::PostInt('infoScore1');
	$infoScore2		= OT::PostInt('infoScore2');
	$infoScore3		= OT::PostInt('infoScore3');
	$note			= OT::PostStr('note');
	$event			= OT::Post('event');
		if (is_array($event)){ $event = implode(',',$event); }
	$rank		= OT::PostInt('rank');
	$state		= OT::PostInt('state');
	$infoMoney		= OT::PostInt('infoMoney');
	$kaitongDay		= OT::PostInt('kaitongDay');
	$kaitongMoney	= OT::PostInt('kaitongMoney');
	$kaitongScore1	= OT::PostInt('kaitongScore1');
	$kaitongScore2	= OT::PostInt('kaitongScore2');
	$kaitongScore3	= OT::PostInt('kaitongScore3');
	$xufeiDay		= OT::PostInt('xufeiDay');
	$xufeiMoney		= OT::PostInt('xufeiMoney');
	$xufeiScore1	= OT::PostInt('xufeiScore1');
	$xufeiScore2	= OT::PostInt('xufeiScore2');
	$xufeiScore3	= OT::PostInt('xufeiScore3');
	$workDay		= OT::PostInt('workDay');
	$workMoney		= OT::PostInt('workMoney');
	$workScore1		= OT::PostInt('workScore1');
	$workScore2		= OT::PostInt('workScore2');
	$workScore3		= OT::PostInt('workScore3');

	if ($theme == ''){
		JS::AlertBackEnd('表单数据接收不全');
	}

	$record = array();
	$record['UG_theme']			= $theme;
	$record['UG_infoTotalNum']	= $infoTotalNum;
	$record['UG_infoDayNum']	= $infoDayNum;
	$record['UG_infoScore1']	= $infoScore1;
	$record['UG_infoScore2']	= $infoScore2;
	$record['UG_infoScore3']	= $infoScore3;
	$record['UG_note']			= $note;
	$record['UG_event']			= $event;
	$record['UG_rank']			= $rank;
	$record['UG_state']			= $state;
	$record['UG_infoMoney']			= $infoMoney;
	$record['UG_kaitongDay']		= $kaitongDay;
	$record['UG_kaitongMoney']		= $kaitongMoney;
	$record['UG_kaitongScore1']		= $kaitongScore1;
	$record['UG_kaitongScore2']		= $kaitongScore2;
	$record['UG_kaitongScore3']		= $kaitongScore3;
	$record['UG_xufeiDay']			= $xufeiDay;
	$record['UG_xufeiMoney']		= $xufeiMoney;
	$record['UG_xufeiScore1']		= $xufeiScore1;
	$record['UG_xufeiScore2']		= $xufeiScore2;
	$record['UG_xufeiScore3']		= $xufeiScore3;
	$record['UG_workDay']			= $workDay;
	$record['UG_workMoney']			= $workMoney;
	$record['UG_workScore1']		= $workScore1;
	$record['UG_workScore2']		= $workScore2;
	$record['UG_workScore3']		= $workScore3;
	
	if ($dataID <= 0){
		$chkexe = $DB->query('select UG_ID from '. OT_dbPref .'userGroup where UG_theme='. $DB->ForStr($theme) .' limit 1');
			if ($chkexe->fetch()){
				JS::AlertBackEnd('该会员组名已存在，请更换.');
			}
		unset($chkexe);

		$alertMode = '新增';
		$judResult = $DB->InsertParam('userGroup',$record);

	}else{
		$alertMode = '修改';
		$judResult = $DB->UpdateParam('userGroup',$record,'UG_ID='. $dataID);

	}
		if ($judResult){
			$alertResult = '成功';

			$fileResultStr = '';
			$Cache = new Cache();
			$isCacheResult = $Cache->PhpTypeArr('userGroup');
				if ($isCacheResult){
					// $fileResultStr = '\n../cache/php/userGroup.php 生成成功！';
				}else{
					$fileResultStr = '\n../cache/php/userGroup.php 生成失败，请检查该目录或者文件是否有写入/修改权限！';
				}

		}else{
			$alertResult = '失败';
		}

	Adm::AddLog(array(
		'theme'	=> $theme,
		'note'	=> '【'. $dataTypeCN .'】'. $alertMode . $alertResult .'！',
		));

	echo('
	<script language="javascript" type="text/javascript">
	alert("'. $alertMode . $alertResult .'.'. $fileResultStr .'");
	');
//	if ($alertMode == '修改'){
		echo('document.location.href="userGroup.php?mudi=manage&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'";');
//	}else{
//		echo('document.location.href="userGroup.php?mudi=add&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'";');
//	}
	echo('
	</script>
	');
}



// 删除
function del(){
	global $DB,$dataType,$dataTypeCN;

	$dataID		= OT::GetInt('dataID');
	$theme		= OT::GetStr('theme');

	if ($dataID <= 0){
		JS::AlertEnd('指定ID错误.');
	}

	if ($dataID == 1){
		JS::AlertEnd('默认内置用户组，不能删除只能修改.');
	}

	$chkexe = $DB->query('select UE_ID from '. OT_dbPref .'users where UE_groupID='. $dataID .' limit 1');
		if ($chkexe->fetch()){
			JS::AlertEnd('有会员在占用该会员组，请先转移或删掉该组会员，再操作！');
		}
	unset($chkexe);

	if ($dataID == $DB->GetOne('select US_regGroupID from '. OT_dbPref .'userSys')){
		JS::AlertEnd('[会员参数设置]-[会员登录/注册系统] 【默认会员组】项正占用该会员组，不允许删除！');
	}

	$DB->query('delete from '. OT_dbPref .'userGroup where UG_ID='. $dataID);

	$Cache = new Cache();
	$Cache->PhpTypeArr('userGroup');

	Adm::AddLog(array(
		'theme'	=> $theme,
		'note'	=> '【'. $dataTypeCN .'】删除成功！',
		));

	echo('
	<script language="javascript" type="text/javascript">
	parent.$id("data'. $dataID .'").style.display="none";
	</script>
	');
}

?>
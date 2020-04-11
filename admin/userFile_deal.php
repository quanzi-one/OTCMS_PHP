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
$MB->Open('','login',10);


$menuFileID = 201;
$MB->IsSecMenuRight('alertBack',$menuFileID,$dataType);



switch($mudi){
	Case 'del':
		Del();
		break;

	Case 'moreDel':
		MoreDel();
		break;

	Case 'clear':
		ClearDeal();
		break;

	Case 'useClear':
		UseClear();
		break;

	default:
		die('err');
}

$MB->Close();
$DB->Close();





// 记录单个删除
function Del(){
	global $DB,$mudi,$menuFileID,$menuTreeID,$dataType,$dataTypeCN;

	$dataID	= OT::GetInt('dataID');

	$judResult = Area::DelUserFile('UF_ID='. $dataID);
	if ($judResult){
		$alertResult = '成功';
	}else{
		$alertResult = '失败';
	}

	echo('
	<script language="javascript" type="text/javascript">
	parent.$id("data'. $dataID .'").style.display="none";
	</script>
	');
}



// 批量删除
function MoreDel(){
	global $DB,$mudi,$menuFileID,$menuTreeID,$systemArr;

	$backURL	= OT::PostStr('backURL');
	$selDataID	= OT::Post('selDataID');
	$dataType	= OT::PostStr('dataType');
	$dataTypeCN	= OT::PostStr('dataTypeCN');

	if (count($selDataID)<=0){
		JS::AlertBackEnd('请先选择要删除的记录.');
	}

	$whereStr='';
	for ($i=0; $i<count($selDataID); $i++){
		$whereStr .= ','. intval($selDataID[$i]);
	}
	if ($whereStr == ''){
		JS::AlertBackEnd('请先选择要删除的记录.');
	}

	$judResult = Area::DelUserFile('UF_ID in (0'. $whereStr .')');
	if ($judResult){
		$alertResult = '成功';
	}else{
		$alertResult = '失败';
	}

	JS::AlertHref('批量删除'. $alertResult .'.',$backURL);
}



// 记录自动清理
function ClearDeal(){
	global $DB,$mudi,$menuFileID,$menuTreeID,$dataType,$dataTypeCN;

	$backURL = OT::GetStr('backURL');

	Area::DelUserFile('UF_proID=0');

	JS::AlertHref('自动清理完成.', $backURL);
}


?>
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


// 用户检测
$MB->Open('','login');

$MB->IsAdminRight('alertBack');


switch ($mudi){
	case 'del':
		del();
		break;

	case 'moreDel':
		MoreDel();
		break;

	default:
		die('err');
}

$MB->Close();
$DB->Close();





// 删除
function del(){
	global $DB;

	$dataID	= OT::GetInt('dataID');

	$DB->Delete('memberOnline','MO_ID='. $dataID);

	echo('
	<script language="javascript" type="text/javascript">
	parent.$id("data'. $dataID .'").style.display="none";
	</script>
	');

}



// 批量删除
function MoreDel(){
	global $DB,$dataType;

	$backURL	= OT::PostStr('backURL');
	$selDataID	= OT::Post('selDataID');

	if (count($selDataID)<=0){
		JS::AlertBackEnd('请先选择要删除的记录！');
	}

	$whereStr='';
	for ($i=0; $i<count($selDataID); $i++){
		$whereStr .= ','. intval($selDataID[$i]);
	}
	if ($whereStr == ''){
		JS::AlertBackEnd('请先选择要删除的记录.');
	}


	$DB->Delete('memberOnline','MO_ID in (0'. $whereStr .')');

	JS::AlertHrefEnd('批量删除成功.',$backURL);

}
?>
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


switch($mudi){
	case 'rev':
		$MB->IsAdminRight('alertBack');
		Rev();
		break;

	default:
		die('err');
}

$MB->Close();
$DB->Close();





//添加与修改
function Rev(){
	global $DB,$skin,$mudi;

	$backURL	= OT::PostStr('backURL');
	$dataID		= OT::PostInt('dataID');
	$rank		= OT::PostInt('rank');
	$themeB			= OT::PostInt('theme'. $dataID .'B');
	$themeColor		= OT::PostStr('theme'. $dataID .'Color');
	$themeStyle		= '';
		if ($themeB==1){ $themeStyle .= 'font-weight:bold;'; }
		if ($themeColor!=''){ $themeStyle .= 'color:'. $themeColor .';'; }

	$record = array();
	$record['MT_themeStyle']	= $themeStyle;
	$record['MT_rank']			= $rank;

	$DB->UpdateParam('menuTree',$record,'MT_ID='. $dataID);

	JS::AlertHrefEnd('修改成功！',$backURL);

}

?>
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
	case 'monthDel':
		$menuFileID = 504;
		$MB->IsSecMenuRight('alertBack',$menuFileID,$dataType);
		MonthDel();
		break;

	default:
		die('err');
}

$MB->Close();
$DB->Close();





// 按天数删除
function MonthDel(){
	global $DB;

	$backURL= OT::GetStr('backURL');
	$day	= OT::GetInt('day');

	if ($day < 1){
		JS::AlertBackEnd('天数指定错误.');
	}
	$day = $day*(-1);

	$DB->query('delete from '. OT_dbPref .'autoRunLog where ARL_time<'. $DB->ForTime(TimeDate::Add('d',$day,TimeDate::Get())));

	JS::AlertHref('删除成功.',$backURL);

}
?>
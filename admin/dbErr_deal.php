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
		$MB->IsAdminRight('alertBack');
		MonthDel();
		break;

	default:
		die('err');
}

$MB->Close();
$DB->Close();





// 批量删除记录
function MonthDel(){
	global $DB;

	$backURL= OT::GetStr('backURL');
	$day	= OT::GetInt('day');

	if ($day < 1){
		JS::AlertBackEnd('天数指定错误.');
	}
	$day = $day*(-1);

	$DB->query('delete from '. OT_dbPref .'dbErr where DE_time<'. $DB->ForTime(TimeDate::Add('d',$day,TimeDate::Get('date'))));

	JS::AlertHref('删除成功.',$backURL);

}
?>
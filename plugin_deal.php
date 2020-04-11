<?php
require(dirname(__FILE__) .'/check.php');

Area::CheckIsOutSubmit('alertStr');	// 检测是否外部提交


if ( in_array($mudi,array('buyOrders','gift','quan','taokeOrder','taokeOrderDel','taokeOrderJiesuan','qiandao','userGroupWork','userScoreMul')) ){
	$userSysArr = Cache::PhpFile('userSys');

	$addiField = '';
	switch ($mudi){
		case 'taokeOrder': case 'taokeOrderJiesuan': 	$addiField = ',UE_money,UE_score1,UE_score2,UE_score3';	break;
	}

	$userRow = Users::Open('get',',UE_username,UE_state,UE_authStr'. $addiField,'',$judUserErr);
		if ((! $userRow) || $judUserErr != ''){
			die('alert("请先登录，该功能需要登录才能使用。（'. $judUserErr .'）");');
		}
		if ($userRow['UE_state'] == 0){
			die('alert("您尚未审核通过，该功能无法使用。");');
		}
		// 检测用户邮箱、手机号是否需要强制验证
		AreaApp::UserTixing($userRow['UE_authStr'], $userSysArr, 1, 'str');
}



switch ($mudi){
	case 'buyOrders':
		// 会员订单录入 处理 需要 $DB $userRow $buySysArr
		AppBuyOrders::Deal(OT_MODE);
		break;

	case 'gift':
		// 积分商城 处理 需要 $DB $userRow
		AppGift::Deal(OT_MODE);
		break;

	case 'quan':
		// 卡券 处理 需要 $DB $userRow
		AppQuan::Deal(OT_MODE);
		break;

	case 'taokeOrder':
		// 淘客用户订单 处理 需要 $DB $userRow
		AppTaokeOrder::Deal(OT_MODE);
		break;

	case 'taokeOrderDel':
		// 淘客用户订单更多处理（删除/结算） 处理 需要 $DB $userRow
		AppTaokeOrder::DelDeal(OT_MODE);
		break;

	case 'taokeOrderJiesuan':
		// 淘客用户订单更多处理（删除/结算） 处理 需要 $DB $userRow
		AppTaokeOrder::JiesuanDeal(OT_MODE);
		break;

	case 'qiandao':
		// 签到
		AppQiandao::Deal(OT_MODE);
		break;

	case 'form':
		// 多用途表单 处理 需要 $DB $userRow
		AppForm::Deal(OT_MODE);
		break;

	case 'logoAdd':
		// 申请友情链接 处理 需要 $DB
		AppLogoAdd::Deal(OT_MODE);
		break;

	case 'newsVerCode':
		// 内容页输入验证码查看
		AppNewsVerCode::Deal(OT_MODE);
		break;

	case 'wxmpLogin':
		// 微信公众号登录
		AppWeixin::AutoLoginDeal(OT_MODE);
		break;

	case 'job':
		// 人才招聘
		AppJob::Deal(OT_MODE);
		break;

	case 'bbsWrite':
		// 简易小论坛 发帖
		AppBbsDeal::Write(OT_MODE);
		break;

	case 'bbsReply':
		// 简易小论坛 回复
		AppBbsDeal::Reply(OT_MODE);
		break;

	case 'userGroupWork':
		// 会员组 领工资
		AppUserGroupWork::Deal(OT_MODE);
		break;

	case 'userState1':
		// 会员转正
		AppUserState1::Deal(OT_MODE);
		break;

	case 'userScoreMul':
		// 金额兑换积分
		AppMoneyPayDeal::UserScoreMul(OT_MODE);
		break;

	case 'userGroupXufei':
		// 会员组续费/延长
		AppUserGroup::XufeiDeal(OT_MODE);
		break;

	case 'userGroupKaitong':
		// 会员组开通/更换
		AppUserGroup::KaitongDeal(OT_MODE);
		break;

	case 'workCenter':
		// 任务中心 领取
		AppWorkCenter::Deal(OT_MODE);
		break;

	default:
		die('err');

}

$DB->Close();

?>
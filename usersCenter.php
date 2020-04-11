<?php
// 初始化变量及引入初始文件
$dbPathPart		= '';
$webPathPart	= '';
$jsPathPart		= '';

require(dirname(__FILE__) .'/check.php');

$userSysArr = Cache::PhpFile('userSys');

	$username = Users::Username();

	if ($username == ''){
		$backURL = urlencode(GetUrl::Query());
		die('
		<br /><br />
		<center style="font-size:14px;">
			请先登录，该功能需要登录才能使用。
			<a class="font2_1" href="users.php?mudi=login&force=1&backURL='. $backURL .'">[登录]</a>/<a class="font2_1" href="users.php?mudi=reg&force=1&backURL='. $backURL .'">[注册]</a>
		</center>
		<br /><br />
		');
	}

	$userRow = Users::Open('get',',UE_username,UE_authStr,UE_groupID,UE_state,UE_pageNum','',$judUserErr);
		if ((! $userRow) || $judUserErr != ''){
			$backURL = urlencode(GetUrl::Query());
			die('
			<br /><br />
			<center style="font-size:14px;">
				请先登录，该功能需要登录才能使用。（'. $judUserErr .'）<a class="font2_1" href="users.php?mudi=login&force=1&backURL='. $backURL .'">[登录]</a>
			</center>
			<br /><br />
			');
		}

		if ($mudi != 'revInfo'){
			// 检测用户邮箱、手机号是否需要强制验证
			AreaApp::UserTixing($userRow['UE_authStr'], $userSysArr, 1);
		}
		if ($userRow['UE_pageNum'] < 1){ $userRow['UE_pageNum'] = 20; }

require(OT_ROOT .'inc/classTemplate.php');

$tpl = new Template;

// 初始化公共变量
$tpl->webTypeName	= 'usersCenter';
$tpl->webTitle		= '';
$tpl->webKey		= '*';
$tpl->webDesc		= '*';

$webContImg = '';
switch ($mudi){
	case 'revInfo':
		$webTitle	= '资料密码修改';
		$webContent = UsersCenter::RevInfo($userRow['UE_ID']);
		$webContImg = 'address-card-o';
		break;

	case 'userAndGroup':
		$webTitle	= '会员和会员组';
		$webContent = UsersCenter::UserAndGroup($userRow['UE_ID']);
		$webContImg = 'address-card-o';
		break;

	case 'userGroupRightList':
		$webTitle	= '会员组权限列表';
		$webContent = UserGroup::RightList($userRow['UE_ID']);
		$webContImg = 'address-card-o';
		break;

	case 'log':
		$webTitle	= '登录日志';
		$webContent = UsersCenter::LogWeb($userRow['UE_ID']);
		break;

	case 'addNews':
		$webTitle	= '发表文章';
		$webContent = CheckAudit($userRow['UE_state'], UsersNews::AddOrRev());
		$webContImg = 'newspaper-o';
		break;

	case 'revNews':
		$webTitle	= '修改文章';
		$webContent = CheckAudit($userRow['UE_state'], UsersNews::AddOrRev());
		$webContImg = 'newspaper-o';
		break;

	case 'newsManage':
		$webTitle	= '文章管理';
		$webContent = UsersNews::Manage();
		break;

	case 'loginApi':
		$webTitle	= '快捷登录绑定';
		$webContent = AppLogin::UcManage($userRow['UE_ID']);
		break;

	case 'quan':
		$webTitle	= '卡密充值';
		$webContent = AppQuan::UcPayWeb($userRow['UE_ID']);
		break;

	case 'onlinePay':
		$webTitle	= '在线充值';
		$webContent = AppMoneyPay::UcPayWeb($userRow['UE_ID'],$userRow['UE_username']);
		break;

	case 'payRecord':
		$webTitle	= '充值记录';
		$webContent = AppMoneyPay::UcManage($userRow['UE_ID']);
		break;

	case 'moneyRecord':
		$webTitle	= '财务明细';
		$webContent = AppMoneyRecord::UcManage($userRow['UE_ID']);
		break;

	case 'userScore':
		$webTitle	= '积分明细';
		$webContent = AppUserScore::UcManage($userRow['UE_ID']);
		break;

	case 'gift':
		$webTitle	= '积分兑换记录';
		$webContent = AppGift::UcManage($userRow['UE_ID']);
		break;

	case 'recom':
		$webTitle	= '邀请好友';
		$webContent = CheckAudit($userRow['UE_state'], AppRecom::UcManage($userRow['UE_ID']));
		break;

	case 'buyOrders':
		$webTitle	= '订单录入';
		$webContent = CheckAudit($userRow['UE_state'], AppBuyOrders::UcManage($userRow['UE_ID']));
		break;

	case 'goodsOrder':
		$webTitle	= '淘宝商品认领';
		$webContent = AppTaokeOrder::UcManage($userRow['UE_ID']);
		break;

	case 'workCenter':
		$webTitle	= '任务中心';
		$webContent = AppWorkCenter::UcManage($userRow['UE_ID']);
		break;

	case 'gainMoney':
		$webTitle	= '提现记录';
		$webContent = CheckAudit($userRow['UE_state'], AppGain::MoneyManage($userRow['UE_ID']));
		break;

	case 'gainItem':
		$webTitle	= '佣金记录';
		$webContent = AppGain::ItemManage($userRow['UE_ID']);
		break;

	case 'index':
	default :
		$mudi = '';
		$webTitle	= '会员中心';
		$webContent = UsersCenter::index($userRow['UE_ID']);
		break;

}
if (strlen($mudi) > 0){
	if (strlen($webContImg) == 0){ $webContImg = 'snowflake-o'; }
	$webContent = MainArea($webContent, $webTitle, $webContImg);
}

$tpl->areaName		= $webTitle;
$tpl->webTitle		= str_replace(array('{%标题附加%}','{%标题%}'), array('',$webTitle), $systemArr['SYS_titleWeb']);


// 解析页面
$tpl->WebTop();
$tpl->WebBottom();


$webTop = '
	<ul>
	<li style="border:none;">欢迎您，'. $userRow['UE_username'] .'</li>
	<li><a href="usersCenter.php">会员中心首页</a></li>
	'. AppMoneyPay::UcTopMenu() .'
	<li><a href="usersCenter.php?mudi=revInfo">资料密码修改</a></li>
	<li><a href="./">网站首页</a></li>
	<li><a href="#" onclick="UserExit();return false;">退出</a></li>
	</ul>
	';


// 推广返佣
$recomMenuStr = ''.
	AppRecom::UcMenu() .
	AppGain::UcMenu() .
	'';
if (strlen($recomMenuStr) > 5){
	$recomMenuStr = '
		<li>
			<div class="item"><i class="fa fa-share-alt fa-fw"></i> 推广返佣<i class="arrow"></i></div>
			<ul class="sub">
				'. $recomMenuStr .'
			</ul>
		</li>
		';
}


// 财务菜单
$moneyMenuStr = ''.
	AppQuan::UcMenu() .
	AppMoneyPay::UcMenu() .
	AppMoneyRecord::UcMenu() .
	AppUserScore::UcMenu() .
	'';
if (strlen($moneyMenuStr) > 5){
	$moneyMenuStr = '
		<li>
			<div class="item"><i class="fa fa-dollar fa-fw"></i> 财务相关<i class="arrow"></i></div>
			<ul class="sub">
				'. $moneyMenuStr .'
			</ul>
		</li>
		';
}


$webMenu = '
	<script language="javascript" type="text/javascript" src="js/usersCenter.js?v='. OT_VERSION .'"></script>

	<ul class="menu">
	<li>
		<div class="item"><i class="fa fa-paperclip fa-fw"></i> 菜单导航<i class="arrow"></i></div>
		<ul class="sub">
		<li><a href="usersCenter.php">会员中心首页</a></li>
		<li><a href="usersCenter.php?mudi=userAndGroup">会员和会员组</a></li>
		'. AppWorkCenter::UcMenu() .'
		'. UsersNews::UcMenu() .'
		'. AppGift::UcMenu() .'
		'. AppBuyOrders::UcMenu() .'
		'. AppTaokeOrder::UcMenu() .'
		</ul>
	</li>
	'. $recomMenuStr .'
	'. $moneyMenuStr .'
	<li>
		<div class="item"><i class="fa fa-user fa-fw"></i> 个人信息<i class="arrow"></i></div>
		<ul class="sub">
		<li><a href="usersCenter.php?mudi=revInfo">资料密码修改</a></li>
		'. AppLogin::UcMenu() .'
		<li><a href="usersCenter.php?mudi=log">登录日志</a></li>
		<li><a href="#" onclick="UserExit();return false;">退出</a></li>
		</ul>
	</li>
	</ul>
	</div>
	';


$tpl->Add('areaName',		$tpl->areaName);
$tpl->Add('webTop',			$webTop);
$tpl->Add('webMenu',		$webMenu);
$tpl->Add('webContent',		$webContent);

$tpl->Show('usersCenter.html');


function MainArea($content, $title, $faName='snowflake-o'){
	return '
		<div class="pointBox"><h4><i class="fa fa-'. $faName .'"></i>&ensp;&ensp;'. $title .'</h4></div>
		<div class="mainBox">'. $content .'</div>
		';
}

function CheckAudit($state, $contStr){
	if ($state == 0){
		return '<div class="alertBox">您账户当前状态为<span style="color:red;">未审核</span>，该功能暂时无法使用，请联系管理员给你审核通过。</div>';
	}else{
		return $contStr;
	}
}

?>
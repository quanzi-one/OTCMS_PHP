<?php

if(! defined('OT_ROOT')) {
	exit('Access Denied');
}


class StrCN{

	// 会员积分记录 类别
	public static function ScoreType($str){
		$typeArr = self::ScoreTypeArr();

		if (isset($typeArr[$str])){
			return $typeArr[$str];
		}else{
			return '['. $str .']';
		}
	}

	// 会员积分记录 类别
	public static function ScoreTypeArr(){
		return array(
			'reg'		=> '注册',
			'login'		=> '登录',
			'qiandao'	=> '签到',
			'recom'		=> '邀请',
			'addInfo'	=> '投稿',
			'read'		=> '看文章',
			'newsGain'	=> '文章积分分成',
			'audit1'	=> '文章审过',
			'audit0'	=> '文章审没过',
			'gift'		=> '积分兑换',
			'suff'		=> '用户充值',
			'orders'	=> '订单录入',
			'admin'		=> '后台',
			'other'		=> '其他'
			);
	}

	// 积分商城 用户兑换记录 状态
	public static function GiftUsersState($num){
		switch ($num){
			case -1:	return '<span style="color:red;">作废</span>';
			case 0:		return '<span style="color:#000;">待审核</span>';
			case 1:		return '<span style="color:green;">待发货</span>';
			case 2:		return '<span style="color:blue;font-weight:bold;">已发货</span>';
			case 3:		return '<span style="color:blue;font-weight:bold;">自动发货</span>';
			default:	return '[未知'. $num .']';
		}
	}

	// 会员订单录入 状态
	public static function BuyOrdersState($num){
		switch ($num){
			case -1:	return '<span style="color:red;">作废</span>';
			case 0:		return '<span style="color:#000;">待审核</span>';
			case 1:		return '<span style="color:green;">待结算</span>';
			case 2:		return '<span style="color:blue;">已结算</span>';
			default :	return '[未知'. $num .']';
		}
	}

	// 邀请好友 状态
	public static function RecomState($num){
		switch ($num){
			case 1:		return '<span style="color:green;">已审核</span>';		
			case 0:		return '<span style="color:red;">未审核</span>';		
			default :	return '[未知'. $num .']';
		}
	}

	// 邀请提现 状态
	public static function GainMoneyState($num){
		switch ($num){
			case 9:		return '<span style="color:red;">已拒绝</span>';		
			case 6:		return '<span style="color:red;">处理失败</span>';		
			case 1:		return '<span style="color:green;">已处理</span>';		
			case 0:		return '<span style="color:#000;">待处理</span>';		
			default :	return '[未知'. $num .']';
		}
	}

	// 邀请产品佣金 状态
	public static function GainItemState($num, $mode=''){
		switch ($num){
			case 9:		return $mode=='cn' ? '已退款' : '<span style="color:red;">已退款</span>';		
			case 6:		return $mode=='cn' ? '处理失败' : '<span style="color:red;">处理失败</span>';		
			case 1:		return $mode=='cn' ? '已结算' : '<span style="color:green;">已结算</span>';		
			case 0:		return $mode=='cn' ? '待结算' : '<span style="color:#000;">待结算</span>';		
			default :	return '[未知'. $num .']';
		}
	}

	// 未付/已付
	public static function IsPay($num){
		switch ($num){
			case 2:		return '<span style="color:green;" title="GET网址反馈">已付2</span>';	// 客户端反馈
			case 1:		return '<span style="color:green;" title="服务端反馈">已付</span>';		// 服务端反馈
			case 0:		return '<span style="color:red;">未付</span>';
			default:	return $num;
		}
	}


	// 打赏类型
	public static function DashangIcoCN($str,$mode=''){
		switch ($str){
			case 'weixin':
				if ($mode=='color'){
					return '#4cbe0d';
				}elseif ($mode=='theme'){
					return '微信扫一扫，打赏作者吧～';
				}else{
					return '微信';
				}
				break;
		
			case 'alipay':
				if ($mode=='color'){
					return '#089fd2';
				}elseif ($mode=='theme'){
					return '支付宝扫一扫，打赏作者吧～';
				}else{
					return '支付宝';
				}
				break;
		
			case 'qq':
				if ($mode=='color'){
					return '#0bb2ff';
				}elseif ($mode=='theme'){
					return 'QQ扫一扫，打赏作者吧～';
				}else{
					return 'QQ钱包';
				}
				break;
		
			case 'baifubao':
				if ($mode=='color'){
					return '#d70100';
				}elseif ($mode=='theme'){
					return '百度钱包扫一扫，打赏作者吧～';
				}else{
					return '百度钱包';
				}
				break;
		
			default :			return $str;
				break;
		}
	}

	// 登录状态保存时间
	public static function LoginExp($num){
		switch ($num){
			case 1:		return '30天';
			case 2:		return '15天';
			case 3:		return '7天';
			case 4:		return '3天';
			case 5:		return '1天';
			case 21:	return '12小时';
			case 22:	return '6小时';
			case 23:	return '3小时';
			case 24:	return '2小时';
			case 25:	return '1小时';
			case 31:	return '30分钟';
			case 32:	return '15分钟';
			default:	return $num;
		}
	}

	// 网址跳转 来源类型
	public static function GoUrlTypeCN($str){
		switch ($str){
			case 'alipay':		return '支付宝';
			case 'weixin':		return '微信';
			case 'QQ':			return 'QQ';
			case 'IE':			return 'IE浏览器';
			case 'opera':		return 'opera浏览器';
			case 'chrome':		return '谷歌浏览器';
			case 'safari':		return '苹果浏览器';
			case 'firefox':		return '火狐浏览器';
			default:			return $str;
		}
	}

}

?>
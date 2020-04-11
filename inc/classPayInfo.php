<?php

if(! defined('OT_ROOT')) {
	exit('Access Denied');
}


class PayInfo{

	// 在线支付 类型
	public static function TypeCN($str){
		switch ($str){
			case 'userMoney':	return '余额';
			case 'tenpay':		return '财付通';
			case 'alipay':		return '支付宝';
			case 'weixin':		return '微信';
			default:			return $str;
		}
	}

	// 在线支付 付款方式
	public static function ModeCN($str){
		switch ($str){
			case 'suff':		return '充值';
			case 'pay':			return '支付';
			case 'goods':		return '购买商品';
			case 'web':			return '付款';
			default :			return '[未知'. $str .']';
		}
	}

	// 颜色数值显示 >0 蓝色 <0 红色  =0黑色
	public static function ColorNum($num, $remMoney=0){
		if ($remMoney == -9){
			return '';
		}elseif ($remMoney == -7){
			return $num;
		}elseif ($num > 0){
			return '<span style="color:green;">+ '. $num .'</span>';
		}elseif ($num < 0){
			return '<span style="color:red;">'. str_replace('-','- ',$num) .'</span>';
		}else{
			return $num;
		}
	}

	// 账户余额处理
	public static function RemMoney($num){
		if (in_array($num, array(-9,-8,-7))){	// -9发生额和余额都不显示；-8余额不显示，发生额有+-符；-8余额不显示，发生额无+-符
			return '';
		}elseif ($num < 0){
			return '<span style="color:red;">'. str_replace('-','- ',$num) .'</span>';
		}else{
			return $num;
		}
	}

}

?>
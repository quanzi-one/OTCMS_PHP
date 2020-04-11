<?php

if(!defined('OT_ROOT')) {
	exit('Access Denied');
}


class AppGain{
	public static function Jud(){
		return false;
	}

	public static function UcMenu($mode='pc'){

	}

	public static function AppSysTrBox1($AS_isGain, $AS_gainRecomMoney, $AS_gainNewsMoney, $AS_gainUserMoney, $AS_gainMoney, $AS_gainDay){
		return '
		<tr>
			<td align="right" valign="top" style="padding-top:5px;">推广提现：</td>
			<td>您尚未购买该插件</td>
		</tr>
		';
	}

	public static function MoneyManage($userID,$mode='pc'){

	}

	public static function ItemManage($userID,$mode='pc'){

	}

	public static function MoneyDeal($mode='pc'){

	}

	public static function AddItemData($proArr=array(), $listArr=array()){

	}

	public static function RevItemData($dataArr=array(), $listArr=array(), $mode=''){
		return false;
	}

	public static function AddMoneyData($proArr=array(), $mode=''){
		return false;
	}

}
?>
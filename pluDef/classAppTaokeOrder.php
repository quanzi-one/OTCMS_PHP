<?php

if(! defined('OT_ROOT')) {
	exit('Access Denied');
}


class AppTaokeOrder{

	public static function Jud(){
		return false;
	}

	public static function UcMenu($mode='pc'){

	}

	public static function UcManage($userID,$mode='pc'){

	}

	public static function Deal($mode='pc'){

	}

	public static function DelDeal($mode='pc'){

	}

	public static function JiesuanDeal($mode='pc'){

	}

	public static function GetGoodsList($orderId){

	}

	public static function UsersState($num){
		switch ($num){
			case -1:	return '<span style="color:red;">作废</span>';
			case 0:		return '<span style="color:#000;">待审核</span>';
			case 1:		return '<span style="color:green;">待结算</span>';
			case 2:		return '<span style="color:blue;">已结算</span>';
			default :	return '[未知]';
		}
	}

	public static function GoodsListStyle($str){

	}
}

?>
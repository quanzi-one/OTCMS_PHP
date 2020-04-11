<?php

if(! defined('OT_ROOT')) {
	exit('Access Denied');
}


class AppUserState1{

	public static function Jud(){
		return false;
	}

	public static function AppSysTrBox1($AS_userState1Money, $AS_userState1Score1, $AS_userState1Score2, $AS_userState1Score3, $judMoneyPay, $userSysArr){
		return '
		<tr>
			<td align="right" valign="top" style="padding-top:5px;">会员积分转正：</td>
			<td>您尚未购买该插件</td>
		</tr>
		';
	}

	public static function UcArea($userID,$appSysArr,$userSysArr,$mode='pc'){

	}

	public static function Deal($mode='pc'){

	}

}

?>
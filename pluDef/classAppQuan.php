<?php

if(!defined('OT_ROOT')) {
	exit('Access Denied');
}


class AppQuan{
	private static $sysArr = array();

	public static function Jud(){
		return false;
	}

	public static function UcMenu($mode='pc'){

	}

	public static function JudReg(){
		return false;
	}

	public static function IsReg(){
		return 0;
	}

	public static function RegItem($mode='pc'){

	}

	public static function CheckUse($code,$type=''){
		return array('res'=>false, 'id'=>0, 'note'=>'尚未购买 卡券 插件');
	}

	public static function ToUse($code, $userID, $username, $type=''){
		return array('res'=>false, 'note'=>'尚未购买 卡券 插件');
	}

	public static function GiftDataTrBox($GD_quanType){

	}

	public static function UcPayWeb($UE_ID,$mode='pc'){

	}

	public static function Deal($mode='pc'){

	}

}
?>
<?php

if(! defined('OT_ROOT')) {
	exit('Access Denied');
}


class AppBbsTplWap{

	public static function Jud(){
		return false;
	}

	public static function WebList(){
		global $tpl;

		$tpl->Add('bbsSysArr',	array());
		$tpl->Add('pointStr',	'');	// 当前位置
		$tpl->Add('bbsContent',	'');
		$tpl->Add('webDataID',	0);		// 当前页面记录ID
	}


	public static function WebShow(){
		global $tpl;

		$tpl->Add('bbsSysArr',		array());
		$tpl->Add('pointStr',		'');	// 当前位置
		$tpl->Add('bbsContent',		'');
		$tpl->Add('webDataID',		0);		// 当前页面记录ID
	}

}
?>
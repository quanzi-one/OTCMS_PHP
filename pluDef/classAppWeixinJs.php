<?php

if(! defined('OT_ROOT')) {
	exit('Access Denied');
}


class AppWeixinJs{

	function __construct(){
		if (! self::Jud()){ die('未购买该插件，无法使用。'); }
	}

	public static function Jud(){
		return false;
	}

	function SetUrlMode($num){

	}

	function SetApiId($str){

	}

	function SetApiKey($str){

	}

	function SetApiUrl($str){

	}

	function HttpGet($url){

	}

	function HttpGetJson($url,$name,$errMode='',$errStr=''){

	}

	function MpAccessToken($errMode='',$reload=false){

	}

	public function GetJsSign($url='') {

	}

	private function GetJsTicket($reload=false) {

	}

	public static function ShareJs($link, $theme, $img, $desc){

	}

	public static function ShareDeal($url){

	}

}
?>
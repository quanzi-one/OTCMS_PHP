<?php

if(! defined('OT_ROOT')) {
	exit('Access Denied');
}


class ApiWeixinLogin{
	private $mId,$mKey,$mUrl;
	private $mUrlMode = 0;
	private static $isApp = 0;

	function __construct(){
		die('未安装该插件，无法使用。');
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

	function arrayRecursive(&$array, $function, $apply_to_keys_also = false){

	}

	function JSON($array) {

	}

	function CreateLoginUrl($mode='pc'){

	}
	
	function CheckLogin($stateAddi=''){

	}

	function GetAccessToken($token,$errMode=''){

	}

	function GetUserInfo($token,$errMode=''){

	}

	function GetUserName($json){

	}

}
?>
<?php

if(! defined('OT_ROOT')) {
	exit('Access Denied');
}


class ApiQQ{
	private $mId,$mKey,$mUrl;
	private $mUrlMode = 0;

	// 构造方法（初始化）
	function __construct(){
		die('未安装该插件，无法使用。');
	}

	public static function Jud(){
		return false;
	}

	function SetUrlMode($num){
		$this->mUrlMode = $num;
	}

	function SetApiId($str){
		$this->mId = $str;
	}

	function SetApiKey($str){
		$this->mKey = $str;
	}

	function SetApiUrl($str){
		$this->mUrl = $str;
	}

	function RequestUrl($url){

	}

	function UseCurl($url){

	}

	function UseFsockopen($url){

	}

	function UseFopen($url){

	}

	function UseSnoopy($url){

	}

	function CreateLoginUrl($mode='pc'){

	}
	
	function CheckLogin(){

	}

	function GetAccessToken($token,$mode='pc'){

	}

	function GetOpenid($token,$mode='pc'){

	}

	function GetUserInfo($token){

	}

	function GetUserName($json){

	}

}
?>
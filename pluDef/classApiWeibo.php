<?php

if(! defined('OT_ROOT')) {
	exit('Access Denied');
}


class ApiWeibo{
	private $mId,$mKey,$mUrl;
	private $mUrlMode = 0;

	// 构造方法（初始化）
	function __construct(){
		die('未安装该插件，无法使用。');
	}

	public static function Jud(){

	}

	// 设置获取网络数据模式
	function SetUrlMode($num){
		$this->mUrlMode = $num;
	}

	// 设置APP ID
	function SetApiId($str){
		$this->mId = $str;
	}

	// 设置APP KEY
	function SetApiKey($str){
		$this->mKey = $str;
	}

	// 设置返回路径
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

	function RequestPostUrl($url,$paraData){

	}

	function UsePostCurl($url,$paraData){

	}

	function UsePostFsockopen($url,$paraData){

	}

	function UsePostFopen($url,$paraData){

	}

	function UsePostSnoopy($url,$paraData){

	}

	function CreateLoginUrl($mode='pc'){

	}
	
	function CheckLogin(){

	}

	function GetAccessToken($token,$mode='pc'){

	}

	function GetUserInfo($token){

	}

	function GetUserName($json){

	}
	
	function GetNickName($json){

	}

}
?>
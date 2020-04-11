<?php

if(! defined('OT_ROOT')) {
	exit('Access Denied');
}


// 网钛CMS(OTCMS) 微信接口

class ApiWeixin{
	private $mId,$mKey,$mUrl;
	private $mUrlMode = 0;

	// 构造方法（初始化）
	function __construct(){
		die('未安装该插件，无法使用。');
	}

	public static function Jud(){

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
	
	function HttpPost($URL, $dataArr = array(), $dataType = ''){

	}

	function HttpGetJson($url,$name,$errMode='',$errStr=''){

	}

	function HttpPostJson($url,$data,$name,$errMode='',$errStr=''){

	}

	function HttpPostJsonJud($url,$data,$name,$errMode='',$errStr=''){

	}

	function arrayRecursive(&$array, $function, $apply_to_keys_also = false){

	}

	function JSON($array) {

	}

	function CreateLoginUrl($mode='pc'){

	}

	function MpLoginUrl($scope='',$stateAddi='',$mode='pc'){

	}
	
	function CheckLogin($stateAddi=''){

	}

	function GetAccessToken($token,$errMode=''){

	}

	function MpAccessToken($errMode=''){

	}

	function RefreshToken($token,$errMode=''){

	}

	function CheckAccessToken($token,$errMode=''){

	}

	function GetQrcode($token,$data,$errMode=''){

	}

	public function GetJsSign($url='') {

	}

	private function GetJsTicket() {

	}

	function GetUserList($token,$lastOpenid='',$errMode=''){

	}

	function GetUserInfo($token,$errMode=''){

	}

	function MoreGetUserInfo($token,$data,$errMode=''){

	}

	function SetUserMark($token,$data,$errMode=''){

	}

	function UserAddGroup($token,$data,$errMode=''){

	}

	function UserDelGroup($token,$data,$errMode=''){

	}

	function GetUserGroupList($token,$errMode=''){

	}

	function AddUserGroup($token,$data,$errMode=''){

	}

	function RevUserGroup($token,$data){

	}

	function DelUserGroup($token,$data){

	}

	function MoveUserGroup($token,$data){

	}

	function GetUserGroupIdArr($token,$data){

	}

	function GetNewsCount($token,$lastOpenid='',$errMode=''){

	}

	function GetNewsList($token,$data,$errMode=''){

	}

	function GetTplList($token,$errMode=''){

	}

	function UpNewsImg($token,$imgurl){

	}

	function MenuSend($token,$data){

	}

	function ServerSend($token,$data){

	}

	function TplSend($token,$data){

	}
}
?>
<?php

if(! defined('OT_ROOT')) {
	exit('Access Denied');
}


class AppWeixin{
	public static function Jud(){
		return false;
	}

	public static function UcInfo($userID,$mode='pc'){

	}

	public static function Login($announ=''){

	}

	public static function LoginDeal($code,$wxUserID,$wxUsername,$userID,$username){

	}

	public static function AutoLoginDeal($mode='pc'){

	}

	public static function ReplySend($type, $openid, $content){

	}

	public static function TplSend($type, $wxId, $dataArr){

	}

	public static function JsSdk($link, $theme, $img, $desc){

	}

	public static function JsSdkDeal($url){

	}

	public static function UserGroupArr(){
		return array();
	}

	public static function UserGroupCN($groupArr, $typeStr, $typeID=0){

	}

	public static function WeixinFaceList($inputId, $width = '100%'){

	}
}

?>
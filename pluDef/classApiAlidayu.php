<?php
/*
if(! defined('OT_ROOT')) {
	exit('Access Denied');
}
*/

class ApiAlidayu{
	function  __construct($accId, $accSecret){
		die('未安装该插件，无法使用。');
	}

	public function SendSms($dataArr){
		return array('res'=>false, 'note'=>'', 'reqId'=>'');;
	}

	private function MakeSign($paraArr, $accessKeySecret){
		return '';
	}

	public function SignEncode($source, $accessSecret){

	}
	
	public function StrEncode($str){

	}

	// 请求url,获取请求内容
	function HttpGet($url){

	}

}

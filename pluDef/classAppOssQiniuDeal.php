<?php

if(! defined('OT_ROOT')) {
	exit('Access Denied');
}


class AppOssQiniuDeal{

	public static function Jud(){
		return false;
	}

	public static function UpFile($fileName, $filePath){
		return array('res'=>false, 'code'=>0, 'note'=>'未购买该插件', 'path'=>'');
	}

	public static function DelFile($fileName){
		return false;
	}
}

?>
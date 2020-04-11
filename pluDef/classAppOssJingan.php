<?php

if(! defined('OT_ROOT')) {
	exit('Access Denied');
}


class AppOssJingan{

	public static function Jud(){
		return false;
	}

	public static function AppSysBox($AS_isJingan,$AS_jinganKey1,$AS_jinganKey2,$AS_jinganName,$AS_jinganEndPoint,$AS_jinganUrl){
		return '
			<tr>
				<td align="right">景安云存储：</td>
				<td>您尚未购买该插件</td>
			</tr>
			';
	}

	public static function CollSysItem($saveTo){

	}

	public static function UpImgItem($saveTo){

	}

	public static function UpFile($fileName, $filePath){
		return array('res'=>false, 'code'=>0, 'note'=>'未购买该插件', 'path'=>'');
	}

	public static function DelFile($fileName){
		return false;
	}
}

?>
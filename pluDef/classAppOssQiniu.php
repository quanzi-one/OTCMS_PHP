<?php

if(! defined('OT_ROOT')) {
	exit('Access Denied');
}


class AppOssQiniu{

	public static function Jud(){
		return false;
	}

	public static function AppSysBox($AS_isQiniu,$AS_qiniuKey1,$AS_qiniuKey2,$AS_qiniuName,$AS_qiniuUrl){
		return '
			<tr>
				<td align="right">七牛云云存储：</td>
				<td>您尚未购买该插件</td>
			</tr>
			';
	}

	public static function CollSysItem($saveTo){

	}

	public static function UpImgItem($saveTo){

	}
}

?>
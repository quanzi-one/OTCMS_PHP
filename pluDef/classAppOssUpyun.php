<?php

if(! defined('OT_ROOT')) {
	exit('Access Denied');
}


class AppOssUpyun{

	public static function Jud(){
		return false;
	}

	public static function AppSysBox($AS_isUpyun,$AS_upyunKey1,$AS_upyunKey2,$AS_upyunName,$AS_upyunUrl){
		return '
			<tr>
				<td align="right">又拍云云存储：</td>
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
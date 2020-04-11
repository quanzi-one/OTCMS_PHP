<?php

if(! defined('OT_ROOT')) {
	exit('Access Denied');
}


class AppOssAliyun{

	public static function Jud(){
		return false;
	}

	public static function AppSysBox($AS_isAliyun,$AS_aliyunKey1,$AS_aliyunKey2,$AS_aliyunName,$AS_aliyunEndPoint,$AS_aliyunUrl){
		return '
			<tr>
				<td align="right">阿里云OSS云存储：</td>
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
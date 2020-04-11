<?php

if(! defined('OT_ROOT')) {
	exit('Access Denied');
}


class AppOssFtp{

	public static function Jud(){
		return false;
	}

	public static function AppSysBox($AS_isFtp,$AS_ftpIp,$AS_ftpPort,$AS_ftpUser,$AS_ftpPwd,$AS_ftpDefDir,$AS_ftpUrl){
		return '
			<tr>
				<td align="right">FTP云存储：</td>
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
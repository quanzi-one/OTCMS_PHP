<?php

if(! defined('OT_ROOT')) {
	exit('Access Denied');
}


class StrInfo{

	// 登录保存状态秒数
	public static function LoginExpSec($num){
		switch ($num){
			case 1:		return 3600*24*30;
			case 2:		return 3600*24*15;
			case 3:		return 3600*24*7;
			case 4:		return 3600*24*3;
			case 5:		return 3600*24;
			case 21:	return 3600*12;
			case 22:	return 3600*6;
			case 23:	return 3600*3;
			case 24:	return 3600*2;
			case 25:	return 3600;
			case 31:	return 1800;
			case 32:	return 900;
			default:	return 0;
		}
	}


	// 文件路径
	public static function FilePath($str, $fileName){
		$fileDir = '';
		switch ($str){
			case 'product':
				$fileDir = ProductFileDir;
				break;

			case 'images':
				$fileDir = ImagesFileDir;
				break;

			case 'download':
				$fileDir = DownloadFileDir;
				break;

			case 'users':
				$fileDir = UsersFileDir;
				break;

			default:
				$fileDir = InfoImgDir;
				break;

		}

		return OT_ROOT . $fileDir . $fileName;
	}

	// 上传文件目录
	public static function FileDir($str){
		switch ($str){
			case 'product':
				return ProductFileDir;

			case 'images':
				return ImagesFileDir;

			case 'download':
				return DownloadFileDir;

			case 'users':
				return UsersFileDir;

			default:
				return InfoImgDir;

		}
	}

	// 上传文件后台目录
	public static function FileAdminDir($str){
		switch ($str){
			case 'product':
				return ProductFileAdminDir;

			case 'images':
				return ImagesFileAdminDir;

			case 'download':
				return DownloadFileAdminDir;

			case 'users':
				return UsersFileAdminDir;

			default:
				return InfoImgAdminDir;

		}
	}

}

?>
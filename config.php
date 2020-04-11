<?php
if(! defined('OT_ROOT')) {
	exit('Access Denied');
}


define('OT_IsInit',		0);				// 系统是否初始化过
define('OT_BugLevel',	0);				// 系统BUG级别
define('OT_Charset',	'utf-8');		// 网站采用的字符集 gb2312, gbk, utf-8
define('OT_SiteID',		'OTCMS_');		// 网站随机前缀
define('OT_Database',	'mysql');		// 网站采用的数据库 access, mysql, sqlite
// [OTCMS_ADDI_System]

if (OT_IsInit==0 && strpos($_SERVER['SCRIPT_NAME'],'install/')===false && strpos($_SERVER['SCRIPT_NAME'],'admin/')===false){
	if (! isset($dbPathPart)){ $dbPathPart=''; }
	die("
	<script language='javascript' type='text/javascript'>
	document.location.href='". $dbPathPart ."install/';
	</script>
	");
}


define('OT_dbDir',		'Data/');			// 数据库存放目录
define('OT_dbBakDir',	'Data_backup/');	// 数据库备份目录
define('OT_dbPref',		'OT_');				// 数据库表前缀


$dbServerName	= '';		// IP/服务器名
$dbPort			= '';		// 端口号
$dbUserName		= '';		// 用户名
$dbUserPwd		= '';		// 密码
if (OT_Database=='mysql'){
	$dbServerName	= '127.0.0.1';	// MySQL服务器名
	$dbPort			= '22669';		// 端口号
	$dbUserName		= 'root';		// MySQL用户名
	$dbUserPwd		= 'root';		// MySQL密码
	$dbName			= 'OTCMS';		// MySQL数据库名称

}elseif (OT_Database=='sqlite'){
	$dbName			= OT_dbDir .'# OTCMS@!db%22.db';	// sqlite数据库名称

}
$collDbName		= OT_dbDir .'# OTCMS_coll.db';		// 采集数据库路径



// 各上传类型目录常量
define('UpFilesDir',			'upFiles/');
define('InfoImgDir',			'upFiles/infoImg/');
define('DownloadFileDir',		'upFiles/download/');
define('ImagesFileDir',			'upFiles/images/');
define('UsersFileDir',			'upFiles/users/');
define('ProductFileDir',		'upFiles/product/');

define('UpFilesAdminDir',		'../upFiles/');
define('InfoImgAdminDir',		'../upFiles/infoImg/');
define('DownloadFileAdminDir',	'../upFiles/download/');
define('ImagesFileAdminDir',	'../upFiles/images/');
define('UsersFileAdminDir',		'../upFiles/users/');
define('ProductFileAdminDir',	'../upFiles/product/');

define('FileAdminDir',			'upFile/');
define('UpdateAdminDir',		'upFile/updateVer/');
// [OTCMS_ADDI_UploadDir]


// 全局变量
define('OT_IsDownloadDB',		false);	// 允许(true)/禁止(false)后台下载网站备份数据库
define('OT_OpenVerCode',		true);	// 开启(true)/关闭(false)网站所有验证码
define('OT_OpenIpDatabase',		false);	// 启用(true)/禁用(false)IP库
// [OTCMS_ADDI_System2]

?>
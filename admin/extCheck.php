<?php
error_reporting(E_ALL);	// E_ALL ^ E_NOTICE

header('Content-Type: text/html; charset=UTF-8');

define('OT_ROOT', dirname(dirname(__FILE__)) .'/');

// 防止 PHP 5.1.x 使用时间函数报错
if(function_exists('date_default_timezone_set')) {
	@date_default_timezone_set('PRC');	//PRC是中华人民共和国时区
}

// 关闭透明化session id的功能
ini_set('session.use_trans_sid',0);
// 只从cookie检查session id
ini_set('session.use_cookies',1);
ini_set('session.use_only_cookies',1);

// session_id();
// session_name(OT_SiteID .'sessionId');

session_start();



	// 字节级转换成相应级单位
	function SizeUnit($fileSize, $dzStr=' '){
		if (! is_numeric($fileSize)){ return $fileSize; }

		if ($fileSize >= 1073741824){
			$fileSize = round($fileSize / 1073741824 * 100) / 100 . $dzStr .'GB';

		} elseif ($fileSize >= 1048576){
			$fileSize = round($fileSize / 1048576 * 100) / 100 . $dzStr .'MB';

		} elseif ($fileSize >= 1024){
			$fileSize = round($fileSize / 1024 * 100) / 100 . $dzStr .'KB';

		} else {
			$fileSize = $fileSize . $dzStr .'字节';
		}

		return $fileSize;
	}

	// 是否可写
	function IsWrite($fileName,$mode=''){
		$jud = is_writable($fileName);
		if ($mode=='cn'){
			if ($jud){
				$jud='<span style="color:green;">可写</span>';
			}else{
				if (file_exists($fileName)){
					$jud='<span style="color:red;">不可写</span>';
				}else{
					$jud='<span style="color:red;">不存在</span>';
				}
			}
		}
		return $jud;
	}

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
<title>网钛CMS PHP版环境检测</title>
<meta name="author" content="网钛科技">
<meta name="robots" content="none">

<style type="text/css">
body	{ margin-top:20px; padding:0px; font-size:13px; line-height:1.7; color:#6d2f2f; }
a		{ font-weight:normal; text-decoration:underline; color:#9b0108; }
a:hover	{ color:#cc000a; text-decoration:none; }
input	{ padding:5px 3px 3px 3px; font-size:14px; color:#333; }
.select { font-size:13px; color:#333; width:84px; }
.title	{ font-size:16px; font-weight:bold; height:45px; line-height:45px; padding-left:12px; background:#f4e9e9; color:#6d2f2f; }
.title2	{ font-size:14px; height:30px; line-height:30px; padding-left:12px; background:#f4e9e9; color:#6d2f2f; }
.list	{ font-size:14px; height:25px; }
.desc	{ font-size:12px; color:#9b0108; width:150px; }
.finish	{ font-size:14px; line-height:150%; font-weight:normal; color:#000000; background-color:#FDFDFD; margin:120px 120px 0px 120px; padding:20px; border:1px solid #B6B6B6; }
.box1	{ background:#f4eaea; }
.box2	{ background:#c29494; }
.border2{ border:1px #c29494 solid; border-left:none;border-bottom:none; }
.border2 td{ border:1px #c29494 solid; border-top:none;border-right:none; }
.btnBox	{ font-size:14px; height:55px; background:#ffffff; }
.border1{ border-bottom:1px #e9d4d4 solid; }
.td1	{ font-size:14px; height:25px; background:#f9f4f4; }
.td2	{ font-size:14px; height:25px; background:#ffffff; }
</style>

</head>
<body>

<table width="800" border="0" align="center" cellpadding="3" cellspacing="0" class="box1">
<tr>
<td>
	<table width="100%" border="0" cellpadding="0" cellspacing="1" class="box2">
	<tr>
	<td>
		<table width="100%" border="0" align="center" cellpadding="5" cellspacing="0" class="border2">
		<colgroup>
			<col class="td1" />
			<col class="td2" />
			<col class="td2" />
			<col class="td2" />
			<col class="td2" />
			<col class="td2" />
		</colgroup>
		<tr>
			<td colspan='6' class='title'>环境检测</td>
		</tr>
		<?php
		try{
			$gdArr = gd_info();
			$gd_res = '<span style="color:green;">支持</span>&ensp;'. $gdArr['GD Version'] .'';
		}
		catch (Exception $e){
			$gd_res = '<span style="color:red;">不支持</span>';
		}

		if (function_exists('apache_get_modules')){
			$result = apache_get_modules();
			if(in_array('mod_rewrite', $result)) {
				$rewrite_res = '<span style="color:green;">支持</span>';
			}else{
				$rewrite_res = '<span style="color:red;">不支持</span>';
			}
		}else{
			$rewrite_res = '<a href="../readSoft.html" target="_blank" style="color:#000;">【点击访问该网址，有显示“[该访问地址存在]”代表支持】</a>';
		}
/*
		<tr>
			<td align='left' colspan='2'>MYSQL （暂时没用到）</td>
			<td align='left' colspan='4'>". ExtCN(function_exists('mysql')) ."</td>
		</tr>
		<tr>
			<td align='left' colspan='2'>sqlite （暂时没用到）</td>
			<td align='left' colspan='4'>". ExtCN(function_exists('sqlite')) ."</td>
		</tr>
		<tr>
			<td align='left' colspan='2'>ACCESS （暂时没用到）</td>
			<td align='left' colspan='4'>". ExtCN(function_exists('com_dotnet')) ."</td>
		</tr>
*/
		echo('
		<tr>
			<td align="left" colspan="2">操作系统</td>
			<td align="left" colspan="4">'. PHP_OS .' '. $_SERVER['SERVER_SOFTWARE'] .'</td>
		</tr>
		<tr>
			<td align="left" colspan="2">PHP 版本</td>
			<td align="left" colspan="4">'. PHP_VERSION .'&ensp;&ensp;'. (PHP_VERSION < 5.3 ? '<span style="color:red;">（建议PHP版本≥5.3）</span>' : '') .'</td>
		</tr>
		<tr>
			<td align="left" colspan="2">pdo</td>
			<td align="left" colspan="4">'. ExtCN(extension_loaded('pdo')) .'</td>
		</tr>
		<tr>
			<td align="left" colspan="2">pdo:mysql</td>
			<td align="left" colspan="3">'. ExtCN(extension_loaded('pdo_mysql')) .'</td>
			<td align="left" colspan="1" rowspan="2">两种只要其中一种支持即可 <span style="color:red;">*</span></td>
		</tr>
		<tr>
			<td align="left" colspan="2">pdo:sqlite</td>
			<td align="left" colspan="3">'. ExtCN(extension_loaded('pdo_sqlite')) .'</td>
		</tr>
		<!-- <tr>
			<td align="left" colspan="2">pdo:ACCESS （预留项，目前没用到）</td>
			<td align="left" colspan="4">'. ExtCN(extension_loaded('PDO_ODBC')) .'</td>
		</tr> -->
		<tr>
			<td align="left" colspan="2">Snoopy插件</td>
			<td align="left" colspan="3">'. ExtCN(function_exists('stream_socket_client')) .'</td>
			<td align="left" colspan="1" rowspan="3">三种只要其中一种支持即可 <span style="color:red;">*</span></td>
		</tr>
		<tr>
			<td align="left" colspan="2">curl模式</td>
			<td align="left" colspan="3">'. ExtCN(extension_loaded('curl')) .'</td>
		</tr>
		<tr>
			<td align="left" colspan="2">fsockopen模式</td>
			<td align="left" colspan="3">'. ExtCN(function_exists('fsockopen')) .'</td>
		</tr>
		<tr>
			<td align="left" colspan="2">openssl 扩展</td>
			<td align="left" colspan="4">'. ExtCN(extension_loaded('openssl')) .'&ensp;（https协议远程图片保存到本地时才会用到）</td>
		</tr>
		<tr>
			<td align="left" colspan="2">zip 扩展</td>
			<td align="left" colspan="4">'. ExtCN(extension_loaded('zip')) .'&ensp;（可选，压缩解压ZIP文件）</td>
		</tr>
		<tr>
			<td align="left" colspan="2">GD 库</td>
			<td align="left" colspan="4">'. $gd_res .'</td>
		</tr>
		<tr>
			<td align="left" colspan="2">Rewrite 伪静态</td>
			<td align="left" colspan="4">'. $rewrite_res .'</td>
		</tr>
		<tr>
			<td align="left" colspan="2">附件上传</td>
			<td align="left" colspan="4">'. (get_cfg_var('upload_max_filesize') ? '最大支持'. get_cfg_var('upload_max_filesize') : '不允许上传') .'</td>
		</tr>
		<tr>
			<td align="left" colspan="2">磁盘空间</td>
			<td align="left" colspan="4">'. SizeUnit(@disk_free_space('/')) .'</td>
		</tr>
		<tr>
			<td colspan="6" class="title">目录、文件权限检测</td>
		</tr>
		<tr>
			<td width="33%" align="center" colspan="2"><b>目录/文件名</b></td>
			<td width="33%" align="center" colspan="2"><b>需要状态</b></td>
			<td width="33%" align="center" colspan="2"><b>当前状态</b></td>
		</tr>
		<tr>
			<td align="left" colspan="2">news/</td>
			<td align="center" colspan="2">可写</td>
			<td align="center" colspan="2">'. IsWrite(OT_ROOT .'news/', 'cn') .'</td>
		</tr>
		<tr>
			<td align="left" colspan="2">cache/</td>
			<td align="center" colspan="2">可写</td>
			<td align="center" colspan="2">'. IsWrite(OT_ROOT .'cache/', 'cn') .'</td>
		</tr>
		<tr>
			<td align="left" colspan="2">cache/php/</td>
			<td align="center" colspan="2">可写</td>
			<td align="center" colspan="2">'. IsWrite(OT_ROOT .'cache/php/', 'cn') .'</td>
		</tr>
		<tr>
			<td align="left" colspan="2">cache/js/</td>
			<td align="center" colspan="2">可写</td>
			<td align="center" colspan="2">'. IsWrite(OT_ROOT .'cache/js/', 'cn') .'</td>
		</tr>
		<tr>
			<td align="left" colspan="2">cache/html/</td>
			<td align="center" colspan="2">可写</td>
			<td align="center" colspan="2">'. IsWrite(OT_ROOT .'cache/html/', 'cn') .'</td>
		</tr>
		<tr>
			<td align="left" colspan="2">cache/web/</td>
			<td align="center" colspan="2">可写</td>
			<td align="center" colspan="2">'. IsWrite(OT_ROOT .'cache/web/', 'cn') .'</td>
		</tr>
		<tr>
			<td align="left" colspan="2">upFiles/download/</td>
			<td align="center" colspan="2">可写</td>
			<td align="center" colspan="2">'. IsWrite(OT_ROOT .'upFiles/download/', 'cn') .'</td>
		</tr>
		<tr>
			<td align="left" colspan="2">upFiles/images/</td>
			<td align="center" colspan="2">可写</td>
			<td align="center" colspan="2">'. IsWrite(OT_ROOT .'upFiles/images/', 'cn') .'</td>
		</tr>
		<tr>
			<td align="left" colspan="2">upFiles/infoImg/</td>
			<td align="center" colspan="2">可写</td>
			<td align="center" colspan="2">'. IsWrite(OT_ROOT .'upFiles/infoImg/', 'cn') .'</td>
		</tr>
		<tr>
			<td align="left" colspan="2">upFiles/product/</td>
			<td align="center" colspan="2">可写</td>
			<td align="center" colspan="2">'. IsWrite(OT_ROOT .'upFiles/product/', 'cn') .'</td>
		</tr>
		<tr>
			<td align="left" colspan="2">upFiles/users/</td>
			<td align="center" colspan="2">可写</td>
			<td align="center" colspan="2">'. IsWrite(OT_ROOT .'upFiles/users/', 'cn') .'</td>
		</tr>
		');
		?>
		</table>
	</td>
	</tr>
	</table>
</td>
</tr>
</table>

</body>
</html>

<?php

function ExtCN($jud, &$failNum=0){
	if ($jud){
		return '<span style="color:green;">支持</span>';
	}else{
		$failNum ++;
		return '<span style="color:red;">不支持</span>';
	}
}

?>
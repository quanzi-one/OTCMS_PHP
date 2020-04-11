<?php
error_reporting(E_ALL);	// E_ALL ^ E_NOTICE

define('OT_ROOT', dirname(dirname(__FILE__)) .'/');
define('OT_insTime',	'20160913');

$dbPathPart='../';

require(OT_ROOT .'configVer.php');
require(OT_ROOT .'config.php');
require(OT_ROOT .'inc/classPdoDb.php');
require(OT_ROOT .'inc/classOT.php');
require(OT_ROOT .'inc/classStr.php');
require(OT_ROOT .'inc/classJS.php');
require(OT_ROOT .'inc/classFile.php');
require(OT_ROOT .'inc/classTimeDate.php');
require(OT_ROOT .'inc/classCache.php');
require(OT_ROOT .'inc/classGetUrl.php');
require(OT_ROOT .'inc/classReqUrl.php');
require(OT_ROOT .'inc/classMySqlManage.php');

header('Content-Type: text/html; charset='. OT_Charset);


$DB		= null;
$mudi	= OT::GetStr('mudi');
$dataID	= OT::GetInt('dataID');


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




if ( file_Exists(OT_ROOT .'cache/web/install.lock') && $mudi != 'finish'){
	die('<br /><br /><center>请先删除cache/web/install.lock文件，再刷新该页面，进行安装向导。</center>');
}

if (! isset($_SESSION['adminDir'])){ $_SESSION['adminDir']='admin'; }


if ( OT_insTime < OT_INSTALLTIME ){
	die('<center style="color:red;font-size:14px;">检查到该安装向导版本('. OT_insTime .')不是最新版('. OT_INSTALLTIME .')<br /><br /><b>请下载最新版，把里面install/目录提取出来覆盖该网站根目录下：</b><a href="http://otcms.com/news/7856.html" target="_blank" title="网钛CMS(OTCMS) 安装向导">http://otcms.com/news/7856.html</a></center>');
}



switch ($mudi){
	case 'setAdminDir':
		SetAdminDir();
		break;

	case 'checkDbState':
		CheckDbState();
		break;

	case 'check':
		WebTop();
		check();
		WebBottom();
		break;

	case 'config':
		WebTop();
		config();
		WebBottom();
		break;

	case 'run':
		WebTop();
		run();
		WebBottom();
		break;

	case 'finish':
		WebTop();
		FinishWeb();
		WebBottom();
		break;

	default:
		WebTop();
		DefWeb();
		WebBottom();
		break;

}



function WebTop(){
?>
	<!DOCTYPE html>
	<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
	<title>欢迎安装 <?php echo(OT_SOFTNAME ." V". OT_VERSION ." build ". OT_UPDATETIME); ?>（安装向导<?php echo(OT_insTime); ?></title>
	<meta name="keywords" content="asp文章管理系统,新闻发布系统,文章管理系统,文章系统,最好的文章系统,OTCMS,网钛CMS" />
	<meta name="description" content="网钛科技致力于文章管理系统、站长工具类的研发；我们坚持做最简单最好用的系统和软件(ASP/PHP/C#)，傻瓜式的操作,让您在最短的时间内就可以上手并建成一个功能强大的网站." />
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

	<script language='javascript' type='text/javascript'>
	function $id(str){
		return document.getElementById(str);
	}

	function $name(str){
		return document.getElementsByName(str);
	}
	</script>
	<script language="javascript" type="text/javascript" src="../js/inc/jquery.min.js?v=2.30"></script>

	</head>
	<body>
<?php
}


function WebBottom(){
	echo('
	</body>
	</html>
	');
}






function DefWeb(){
?>
	<table width="100" border="0" align="center" cellpadding="3" cellspacing="0" class="box1">
	<tr>
	<td>
		<table width="100" border="0" cellpadding="0" cellspacing="1" class="box2">
		<tr>
		<td>
			<table width="800" border="0" align="center" cellpadding="0" cellspacing="0">
			<tr>
				<td class="title border1">网钛CMS(OTCMS)使用许可协议</td>
			</tr>
			<tr>
				<td style="padding:5px 5px 5px 8px; background:#ffffff;" class="border1">
					<div style='width:100%; height:400px; overflow:auto;'>
						<p>感谢您选择网钛CMS(OTCMS)，本系统基于PHP+sqlite/mysql 技术开发。官方网址：<a href="http://otcms.com/" target="_blank">http://otcms.com</a></p>
						<p>为了使你正确并合法的使用本软件，请你在使用前务必阅读清楚下面的协议条款：</p>
						<p><strong>一、本协议仅适用于网钛CMS(OTCMS)，网钛科技对本协议有最终解释权。</strong></p>
						<p><strong>二、协议许可的权利</strong><br />
						1、您可以在完全遵守本最终用户授权协议的基础上，将本软件应用于商业或非商业用途，而不必支付软件版权授权费用。<br />
						2、您可以在协议规定的约束和限制范围内修改本系统源代码或界面风格以适应您的网站要求。<br />
						3、您拥有使用本软件构建的网站全部内容所有权，并独立承担与这些内容的相关法律义务。<br />
						4、获得商业授权之后，您可以依据所购买的授权类型中确定的技术支持内容，自购买时刻起，在技术支持期限内拥有通过指定的方式获得指定范围内的技术支持服务。商业授权用户享有反映和提出意见的权力，相关意见将被作为首要考虑，但没有一定被采纳的承诺或保证。</p>

						<p><strong>三、协议规定的约束和限制 </strong><br />
						1、不得将本软件用于国家不允许开设的网站（包括色qing、反dong、含有病毒，赌bo类网站）。<br />
						2、未经官方许可，不得对本软件或与之关联的商业授权进行出租、出售、抵押或发放子许可证。<br />
						3、不管你的网站是否整体使用本系统 ，还是部份栏目使用本软件，<span class="light">在你使用了本软件的网站主页上必须加上本软件官方网址(otcms.com)的链接</span>。<br />
						4、未经官方许可，禁止在本软件的整体或任何部分基础上以发展任何派生版本、修改版本或第三方版本用于重新分发。 <br />
						5、如果您未能遵守本协议的条款，您的授权将被终止，所被许可的权利将被收回，并承担相应法律责任。 </p>

						<p><strong>四、有限担保和免责声明 </strong><br />
						1、本软件及所附带的文件是作为不提供任何明确的或隐含的赔偿或担保的形式提供的。<br />
						2、用户出于自愿而使用本软件，您必须了解使用本软件的风险，在尚未购买产品技术服务之前，我们不承诺对免费用户提供任何形式的技术支持、使用担保，也不承担任何因使用本软件而产生问题的相关责任。<br />
						3、电子文本形式的授权协议如同双方书面签署的协议一样，具有完全的和等同的法律效力。您一旦开始确认本协议并安装本系统，即被视为完全理解并接受本协议的各项条款，在享有上述条款授予的权力的同时，受到相关的约束和限制。协议许可范围以外的行为，将直接违反本授权协议并构成侵权，我们有权随时终止授权，责令停止损害，并保留追究相关责任的权力。<br />
						4、如果本软件带有其它软件的整合API示范例子包，这些文件版权不属于本软件官方，并且这些文件是没经过授权发布的，请参考相关软件的使用许可合法的使用。</p>
						<p>版权所有 (c)2015-<?php echo(TimeDate::Get("Y")); ?>，网钛科技 保留所有权利。</p>
						<p>协议发布时间：  2016年05月01日 By 网钛科技</p>
					</div>
				</td>
			</tr>
			<tr>
				<td align="center" class="btnBox">
					<input type="button" value="我同意" onclick="if (document.getElementById('isSkip').checked){ document.location.href='index.php?mudi=config'; }else{ document.location.href='index.php?mudi=check'; }" />&ensp;&ensp;&ensp;&ensp;
					<input type="button" value="不同意" onclick="window.close();">
					&ensp;&ensp;<label style="color:#c9c8c8;"><input type="checkbox" id="isSkip" name="isSkip" value="1" />跳过环境检测</label>
				</td>
			</tr>
			</table>
		</td>
		</tr>
		</table>
		<div style="margin:5px;"><b>安装向导使用教程：</b><a href="http://otcms.com/news/8180.html" target="_blank">http://otcms.com/news/8180.html</a></div>
	</td>
	</tr>
	</table>
<?php
}



function check(){
	global $dbName;

	if (OT_Database=='mysql'){
		$dbNameArr = array('','');
	}else{
		$dbNameArr = explode('/',$dbName);
	}
	
	$failNum = 0;
?>
	<script language='javascript' type='text/javascript'>
	function CheckAdminDir(){
		if ($id('newAdminDir').value==""){
			alert("请先填写当前后台目录名");$id('newAdminDir').focus();return false;
		}
		document.location.href="index.php?mudi=setAdminDir&newAdminDir="+ $id('newAdminDir').value;
	}
	</script>

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
				$gd_res = $gdArr['GD Version'] .'';
			}
			catch (Exception $e){
				$gd_res = '不支持';
			}

			if (function_exists('apache_get_modules')){
				$result = apache_get_modules();
				if(in_array('mod_rewrite', $result)) {
					$rewrite_res = '<span style="color:green;">支持</span>';
				}else{
					$rewrite_res = '<span style="color:red;">不支持</span>';
				}
			}else{
				$retArr = ReqUrl::UseAuto(0,'GET',GetUrl::CurrDir(1) .'readSoft.html','UTF-8');
				if ($retArr['res'] && $retArr['note'] == '[该访问地址存在]'){
					$rewrite_res = '<span style="color:green;">支持</span>';
				}else{
					$rewrite_res = '<span style="color:#000;">未知</span>';
				}
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
				<td align="left" colspan="2">pdo:mysql</td>
				<td align="left" colspan="4">'. ExtCN(extension_loaded('pdo_mysql')) .'</td>
				<!-- <td align="left" colspan="1" rowspan="2">两种只要其中一种支持即可 <span style="color:red;">*</span></td> -->
			</tr>
			<tr>
				<td align="left" colspan="2">pdo:sqlite</td>
				<td align="left" colspan="4">'. ExtCN(extension_loaded('pdo_sqlite')) .' （预留项，目前没用到）</td>
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
				<td align="left" colspan="2">rewrite 扩展</td>
				<td align="left" colspan="4">'. $rewrite_res .'&ensp;（可选，选择伪静态路径才用到）</td>
			</tr>
			<tr>
				<td align="left" colspan="2">GD 库</td>
				<td align="left" colspan="4">'. $gd_res .'</td>
			</tr>
			<tr>
				<td align="left" colspan="2">附件上传</td>
				<td align="left" colspan="4">'. (get_cfg_var('upload_max_filesize') ? '最大支持'. get_cfg_var('upload_max_filesize') : '不允许上传') .'</td>
			</tr>
			<tr>
				<td align="left" colspan="2">磁盘空间</td>
				<td align="left" colspan="4">'. File::SizeUnit(@disk_free_space('/')) .'</td>
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
				<td align="center" colspan="2">'. File::IsWrite(OT_ROOT .'news/', 'cn') .'</td>
			</tr>
			<tr>
				<td align="left" colspan="2">cache/</td>
				<td align="center" colspan="2">可写</td>
				<td align="center" colspan="2">'. File::IsWrite(OT_ROOT .'cache/', 'cn') .'</td>
			</tr>
			<tr>
				<td align="left" colspan="2">cache/php/</td>
				<td align="center" colspan="2">可写</td>
				<td align="center" colspan="2">'. File::IsWrite(OT_ROOT .'cache/php/', 'cn') .'</td>
			</tr>
			<tr>
				<td align="left" colspan="2">cache/js/</td>
				<td align="center" colspan="2">可写</td>
				<td align="center" colspan="2">'. File::IsWrite(OT_ROOT .'cache/js/', 'cn') .'</td>
			</tr>
			<tr>
				<td align="left" colspan="2">cache/html/</td>
				<td align="center" colspan="2">可写</td>
				<td align="center" colspan="2">'. File::IsWrite(OT_ROOT .'cache/html/', 'cn') .'</td>
			</tr>
			<tr>
				<td align="left" colspan="2">cache/web/</td>
				<td align="center" colspan="2">可写</td>
				<td align="center" colspan="2">'. File::IsWrite(OT_ROOT .'cache/web/', 'cn') .'</td>
			</tr>
			<tr>
				<td align="left" colspan="2">upFiles/download/</td>
				<td align="center" colspan="2">可写</td>
				<td align="center" colspan="2">'. File::IsWrite(OT_ROOT .'upFiles/download/', 'cn') .'</td>
			</tr>
			<tr>
				<td align="left" colspan="2">upFiles/images/</td>
				<td align="center" colspan="2">可写</td>
				<td align="center" colspan="2">'. File::IsWrite(OT_ROOT .'upFiles/images/', 'cn') .'</td>
			</tr>
			<tr>
				<td align="left" colspan="2">upFiles/infoImg/</td>
				<td align="center" colspan="2">可写</td>
				<td align="center" colspan="2">'. File::IsWrite(OT_ROOT .'upFiles/infoImg/', 'cn') .'</td>
			</tr>
			<tr>
				<td align="left" colspan="2">upFiles/product/</td>
				<td align="center" colspan="2">可写</td>
				<td align="center" colspan="2">'. File::IsWrite(OT_ROOT .'upFiles/product/', 'cn') .'</td>
			</tr>
			<tr>
				<td align="left" colspan="2">upFiles/users/</td>
				<td align="center" colspan="2">可写</td>
				<td align="center" colspan="2">'. File::IsWrite(OT_ROOT .'upFiles/users/', 'cn') .'</td>
			</tr>
			<tr>
				<td colspan="6" class="title">高级功能需要的权限检查</td>
			</tr>
			');

			$OutUrlState	= '<span style="color:green;">可以访问外网</span>';
			$OtUrlState		= '<span style="color:green;">可以访问官网</span>';
			$retArr = ReqUrl::UseAuto(0,'GET','http://www.baidu.com','GB2312');
				if (! $retArr['res']){
					$OutUrlState	= '<span style="color:red;">无法访问外网</span>';
				}
			$retArr = ReqUrl::UseAuto(0,'GET','http://php.otcms.com/info.php');
				if (! $retArr['res']){
					$OutUrlState	= '<span style="color:red;">无法访问官网</span>';
				}
			echo('
			<tr>
				<td align="center" colspan="3">判断访问外网（采集功能）</td>
				<td align="center" colspan="3">'. $OutUrlState .'</td>
			</tr>
			<tr>
				<td align="center" colspan="3">判断访问官网（获取授权信息和页面、在线升级）</td>
				<td align="center" colspan="3">'. $OtUrlState .'</td>
			</tr>
			<tr>
				<td colspan="6" class="title">下一步操作（网站配置初始化）需要用到的目录/文件权限</td>
			</tr>
			<tr>
				<td align="left" colspan="2">config.php <span style="color:red;">*</span></td>
				<td align="center" colspan="2">可读、可写</td>
				<td align="center" colspan="2">'. File::IsRead(OT_ROOT .'config.php', 'cn') .'、'. File::IsWrite(OT_ROOT .'config.php', 'cn') .'</td>
			</tr>
			<tr>
				<td align="left" colspan="2">'. $_SESSION['adminDir'] .' <span style="color:red;">*</span></td>
				<td align="center" colspan="2">可改</td>
				<td align="center" colspan="2">
				');
					if (! File::IsWrite(OT_ROOT .'config.php')){ $failNum ++; }

					$adResult = File::IsRev(OT_ROOT .$_SESSION['adminDir'],'cn');
					if (strpos($adResult,'不存在') !== false){
						$failNum ++;
						$adAddiStr='<br />当前后台目录名：<input type="text" id="newAdminDir" name="newAdminDir" style="width:50px;padding:1px;font-size:12px;" /><input type="button" value="确定" style="padding:1px;font-size:12px;" onclick="CheckAdminDir();" />';
					}else{
						$adAddiStr='';
					}

				echo('
				'. $adResult . $adAddiStr .'
				</td>
			</tr>
			');
			if (OT_Database!='mysql'){
				echo('
				<tr>
					<td align="left" colspan="2">'. $dbNameArr[0] .'</td>
					<td align="center" colspan="2">可改</td>
					<td align="center" colspan="2">'. File::IsWrite(OT_ROOT . $dbNameArr[0], 'cn') .'</td>
				</tr>
				<tr>
					<td align="left" colspan="2">'. $dbName .'</td>
					<td align="center" colspan="2">可改</td>
					<td align="center" colspan="2">'. File::IsWrite(OT_ROOT . $dbName, 'cn') .'</td>
				</tr>
				');
			}
			echo('
			<tr>
				<td align="left" colspan="2">'. OT_dbBakDir .'</td>
				<td align="center" colspan="2">可改、可写</td>
				<td align="center" colspan="2">'. File::IsRev(OT_ROOT . OT_dbBakDir, 'cn') .'、'. File::IsWrite(OT_ROOT . OT_dbBakDir, 'cn') .'</td>
			</tr>
			');
			?>
			<tr>
				<td colspan="6" align="center" class="btnBox">
						<input id="updateBtn" type="button" value="上一步" style="margin:10px 0 10px 0;" onclick="document.location.href='index.php';" />
						&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;
					<?php if ($failNum > 0){ ?>
						<input id="updateBtn" type="button" value="带*星号项权限不足或文件/目录没找到，请设置好后刷新再试" style="width:410px; margin:10px 0 10px 0;" disabled="true" />
						<br />如不懂设置，请阅读先IIS权限设置：<a href="http://otcms.com/news/3251.html" target="_blank">http://otcms.com/news/3251.html</a>
					<?php }else { ?>
						<input id="updateBtn" type="button" value="下一步" style="margin:10px 0 10px 0;" onclick="document.location.href='index.php?mudi=config';" />
					<?php } ?>
					 <div style="color:red;font-weight:bold;text-align:left;line-height:1.2;">提醒！该程序仅适用于国内和香港空间，放国外空间可能导致程序和插件升级很卡甚至无法升级，部分功能很慢甚至无法使用，后果自负。</div>
				</td>
			</tr>
			</table>
		</td>
		</tr>
		</table>
	</td>
	</tr>
	</table>
<?php
}



function config(){
	$isSqlite		= OT::GetInt('isSqlite');
	if (OT_Database=='mysql'){
		$dbNameArr = array('','');
	}else{
		global $dbName;
		if (empty($dbName)){
			$dbNameArr = array('Data','# OTCMS@!db%22.db');
		}else{
			$dbNameArr = explode('/',$dbName);
		}
	}
	if ( defined('OT_dbBakDir') ){
		$dbBakDir = OT_dbBakDir;
		if (substr($dbBakDir,-1) == '/'){ $dbBakDir = substr($dbBakDir,0,-1); }
	}else{
		$dbBakDir = 'Data_backup';
	}

	$subBtnStr = '<input type="submit" value="确定设置" />';
	if (extension_loaded('pdo_mysql')){
		$mysqlJud = true;
		$mysqlChkStr = 'checked="checked"';
		$mysqlEnableStr = '';
		$mysqlAlert = '(推荐，适合所有网站)';
	}else{
		$mysqlJud = false;
		$mysqlChkStr = '';
		$mysqlEnableStr = 'disabled="true"';
		$mysqlAlert = '[不支持pdo_mysql扩展]';
	}
	if (extension_loaded('pdo_sqlite') && $isSqlite==1){
		$sqliteJud = true;
		if ($mysqlJud){
			$sqliteChkStr = '';
		}else{
			$sqliteChkStr = 'checked="checked"';
		}
		$sqliteEnableStr = '';
		$sqliteAlert = '(仅限测试和看效果，插件不支持，性能差，不建议正式使用)';
	}else{
		$sqliteJud = false;
		$sqliteChkStr = '';
		$sqliteEnableStr = 'disabled="true"';
		$sqliteAlert = '[不支持pdo_sqlite扩展]';
	}
	if ($mysqlJud==false && $sqliteJud==false){
		$subBtnStr = '<input type="button" value="MySQL和SQLite数据库都不支持，无法下一步" disabled="true" style="color:#999;" />';
		$nullChkStr = 'checked="checked"';
	}else{
		$nullChkStr = '';
	}
	?>

	<script language='javascript' type='text/javascript'>
	function CheckConfigForm(){
		if ($id('adminName').value==""){
			alert('后台登录帐号不能为空');$id('adminName').focus();return false;
		}
		if ($id('adminPwd').value==""){
			alert('后台登录密码不能为空');$id('adminPwd').focus();return false;
		}
		if ($id('adminDir').value==""){
			alert('后台目录名不能为空');$id('adminDir').focus();return false;
		}
		if ($id('dbType_mysql').checked){
			if ($id('mysqlState').value=="0" && $id("isDbSkip").checked==false){
				alert('请先【连接测试】，提示连接成功才能进行下一步操作。');return false;
			}
			if ($id('sqlPref').value==""){
				alert('数据库表前缀不能为空');$id('sqlPref').focus();return false;
			}
		}else{
			if ($id('accName').value==""){
				alert('网站数据库名称不能为空');$id('accName').focus();return false;
			}
			/*if (!/[\/\\"'<>\?\*]/gi.test($id('accName').value)){
				alert('网站数据库名称不能含这些字符 /\:*?"<>|');$id('accName').focus();return false;
			}*/
			var accNameExt = $id('accName').value.substr($id('accName').value.length-4)
			if (accNameExt.substr(accNameExt.length-3)!=".db" && accNameExt!=".php"){
				alert('网站数据库名称必须以“.db”或者“.php”结尾\n(如 123.db 或 123.php)');$id('accName').focus();return false;
			}
			if ($id('accDir').value==""){
				alert('数据库目录名不能为空');$id('accDir').focus();return false;
			}
			if ($id('accDir').value==$id('accBackupDir').value){
				alert('数据库目录名与数据库备份目录名不能相同');$id('accDir').focus();return false;
			}
		}
		if ($id('accBackupDir').value==""){
			alert('数据库备份目录名不能为空');$id('accBackupDir').focus();return false;
		}
		alertStr = "";
		alertNum = 0;
		if ($id('adminName').value=="admin"){
			alertNum ++;
			alertStr += alertNum +"、后台登录帐号:admin\n";
		}
		if ($id('adminPwd').value=="admin"){
			alertNum ++;
			alertStr += alertNum +"、后台登录密码:admin\n";
		}
		if ($id('adminDir').value=="admin"){
			alertNum ++;
			alertStr += alertNum +"、后台目录名:admin\n";
		}
		if ($id('dbType_sqlite').checked){
			if ($id('accName').value=="# OTCMS@!db%22.db"){
				alertNum ++;
				alertStr += alertNum +"、网站数据库名称:# OTCMS@!db%22.db\n";
			}
			if ($id('accDir').value=="Data"){
				alertNum ++;
				alertStr += alertNum +"、数据库目录名:Data\n";
			}
		}
/*		if ($id('accBackupDir').value=="Data_backup"){
			alertNum ++;
			alertStr += alertNum +"、数据库备份目录名:Data_backup\n";
		} */
		if (alertStr != ""){
			if (confirm(alertStr +"\n确定以上这"+ alertNum +"项采用系统默认的？\n（建议修改这些项，以提高网站安全性）")==false){
				return false;
			}
		}
		if ($id('judClearDB').checked){
			if (confirm("确定要初始化(清空)数据库？")==false){
				return false;
			}
		}
	}

	function CheckDbType(){
		if ($id('dbType_mysql').checked){
			$id('mysqlBox').style.display = '';
			$id('sqliteBox').style.display = 'none';
		}else if ($id('dbType_sqlite').checked){
			$id('mysqlBox').style.display = 'none';
			$id('sqliteBox').style.display = '';
		}else{
			$id('mysqlBox').style.display = 'none';
			$id('sqliteBox').style.display = 'none';
		}
	}

	function ConnMySql(){
		if ($id('isCreateDB').checked){
			isCreate = 1;
		}else{
			isCreate = 0;
		}
		var chkUrl = '?mudi=checkDbState&sqlIp='+ encodeURIComponent($id('sqlIp').value) +'&sqlPo='+ $id('sqlPo').value +'&sqlUsername='+ encodeURIComponent($id('sqlUsername').value) +'&sqlUserPwd='+ encodeURIComponent($id('sqlUserPwd').value) +'&sqlDbName='+ $id('sqlDbName').value +'&sqlPref='+ encodeURIComponent($id('sqlPref').value) +'&isCreateDB='+ isCreate;
		if ($id('isBug').checked){
			var a=window.open(chkUrl);
		}else{
			$.ajaxSetup({cache:false});
			$.get(chkUrl, function(result){
				if (result.indexOf('连接成功') != -1){
					$id('mysqlState').value = '1';
				}else{
					$id('mysqlState').value = '0';
				}
				alert(result);
			});
		}
	}

	function CheckIsImport(){
		if ($id('isImport0').checked){
			$id('canImportAlert').style.display = 'none';
			$id('noImportAlert').style.display = '';
		}else{
			$id('canImportAlert').style.display = '';
			$id('noImportAlert').style.display = 'none';
		}
	}

	// 过滤非数字、字母、下划线和点符号
	// 应用例子 onkeyup="if (this.value!=FiltFileName(this.value)){this.value=FiltFileName(this.value)}"
	// 应用例子 onkeyup="this.value=FiltFileName(this.value)"
	function FiltFileName(str){
		return str.replace(/(\\|\/|\:|\*|\?|\"|'|<|>|\|)/ig,'')
	}

	</script>

	<table width="100" border="0" align="center" cellpadding="3" cellspacing="0" class="box1">
	<tr>
	<td>
		<table width="100" border="0" cellpadding="0" cellspacing="1" class="box2">
		<tr>
		<td>
			<table width="800" border="0" align="center" cellpadding="5" cellspacing="0" class="border2">
			<colgroup>
				<col class="td1" style="width:140px;" />
				<col class="td2" style="width:660px;" />
			</colgroup>
			<form id="configForm" name="configForm" method="post" action="?mudi=run" onsubmit="return CheckConfigForm()">
			<tr>
				<td colspan="2" class="title">网站配置初始化</td>
			</tr>
			<tr>
				<td colspan="2" class="title2">后台帐号信息和路径</td>
			</tr>
			<tr>
				<td align="right">后台登录帐号:</td>
				<td>
					<input type="text" id="adminName" name="adminName" value="admin" style="width:250px;" />
					<span class="desc">建议修改</span>
				</td>
			</tr>
			<tr>
				<td align="right">后台登录密码:</td>
				<td>
					<input type="text" id="adminPwd" name="adminPwd" value="admin" style="width:250px;" />
					<span class="desc">建议修改</span>
				</td>
			</tr>
			<tr>
				<td align="right">后台目录名:</td>
				<td>
					<input type="text" id="adminDir" name="adminDir" value="<?php echo($_SESSION["adminDir"]); ?>" style="width:250px;" onkeyup="if (this.value!=FiltFileName(this.value)){this.value=FiltFileName(this.value)}" />
					<span class="desc">必须修改，不能用默认的admin，不然有严重安全问题</span>
				</td>
			</tr>
			<tr>
				<td colspan="2" class="title2">设置数据库路径</td>
			</tr>
			<tr>
				<td align="right">数据库类型:</td>
				<td>
					<?php
					echo('
					<label><input type="radio" id="dbType_mysql" name="dbType" value="mysql" onclick="CheckDbType()" '. $mysqlChkStr .' '. $mysqlEnableStr .' />MySQL<span style="color:red;">'. $mysqlAlert .'</span></label>&ensp;&ensp;
					');
					if ($isSqlite == 1){
						echo('
						<label style="color:#a9a9a9;"><input type="radio" id="dbType_sqlite" name="dbType" value="sqlite" onclick="CheckDbType()" '. $sqliteChkStr .' '. $sqliteEnableStr .' />SQLite<span style="color:red;">'. $sqliteAlert .'</span></label>&ensp;&ensp;
						');
					}
					echo('
					<label style="display:none;"><input type="radio" id="dbType_null" name="dbType" value="null" '. $nullChkStr .' />无</label>
					');
					?>
				</td>
			</tr>
			<tbody id="sqliteBox" style="display:none;">
			<tr>
				<td align="right">网站数据库名称:</td>
				<td>
					<input type="text" id="accName" name="accName" style="width:250px;" value="<?php echo($dbNameArr[1]); ?>" onkeyup="if (this.value!=FiltFileName(this.value)){this.value=FiltFileName(this.value)}" /> 
					<span class="desc"> 建议修改默认数据库文件名</span>
				</td>
			</tr>
			<tr>
				<td align="right">数据库目录名:</td>
				<td>
					<input type="text" id="accDir" name="accDir" style="width:250px;" value="<?php echo($dbNameArr[0]); ?>" onkeyup="if (this.value!=FiltFileName(this.value)){this.value=FiltFileName(this.value)}" /> 
					<span class="desc"> 建议修改默认数据库目录名</span>
				</td>
			</tr>
			</tbody>
			<tbody id="mysqlBox" style="display:none;">
			<tr>
				<td align="right">数据库地址:</td>
				<td>
					<input type="text" id="sqlIp" name="sqlIp" style="width:250px;" value="localhost" /> 
					<span class="desc"> 可以是域名或IP，默认为 <span style="color:red;cursor:pointer;" onclick="document.getElementById('sqlIp').value='localhost';">localhost</span> 或 <span style="color:red;cursor:pointer;" onclick="document.getElementById('sqlIp').value='127.0.0.1';">127.0.0.1</span></span>
				</td>
			</tr>
			<tr>
				<td align="right">数据库端口:</td>
				<td>
					<input type="text" id="sqlPo" name="sqlPo" style="width:250px;" value="3306" /> 
					<span class="desc"> 默认为 <span style="color:red;cursor:pointer;" onclick="document.getElementById('sqlPo').value='3306';">3306</span></span>
				</td>
			</tr>
			<tr>
				<td align="right">数据库账号:</td>
				<td>
					<input type="text" id="sqlUsername" name="sqlUsername" style="width:250px;" value="root" /> 
				</td>
			</tr>
			<tr>
				<td align="right">数据库密码:</td>
				<td>
					<input type="text" id="sqlUserPwd" name="sqlUserPwd" style="width:250px;" value="" /> 
				</td>
			</tr>
			<tr>
				<td align="right">数据库名:</td>
				<td>
					<input type="text" id="sqlDbName" name="sqlDbName" style="width:250px;" value="OTCMS" /> 
					<label title="针对该数据库名还不存在，同时该数据库连接账号具有创建数据库权限才需打勾"><input type="checkbox" id="isCreateDB" name="isCreateDB" value="1" />创建数据库名<span style="color:#a59ea3;">(连接账号要有创建权限且库名不存在)</span></label>
				</td>
			</tr>
			<tr>
				<td align="right">数据库表前缀:</td>
				<td>
					<input type="text" id="sqlPref" name="sqlPref" style="width:250px;" value="OT_" /> 
					<span class="desc"> 建议用默认，同一数据库安装多个网钛CMS时才需要修改以区分</span>
				</td>
			</tr>
			<tr>
				<td align="right">初始库:</td>
				<td>
					<?php if (file_exists(OT_ROOT .'install/OTCMS.sql')){ ?>
						<label><input type="radio" id="isImport1" name="isImport" value="1" onclick="CheckIsImport()" />导入数据库（含示例数据）</label>&ensp;&ensp;
						<label><input type="radio" id="isImport1" name="isImport" value="2" onclick="CheckIsImport()" checked="checked" />导入数据库（不含示例数据）</label>&ensp;&ensp;
						<label><input type="radio" id="isImport0" name="isImport" value="0" onclick="CheckIsImport()" />不导入，仅配置数据库连接信息</label>&ensp;&ensp;
					<?php }else{ ?>
						<span style="color:red;font-weight:bold;">没检测到 install/OTCMS.sql 数据库文件，无法导入数据库</span>&ensp;&ensp;
						<label><input type="radio" id="isImport0" name="isImport" value="0" checked="checked" />不导入，仅配置数据库连接信息</label>&ensp;&ensp;
					<?php } ?>
					<div id="canImportAlert" style="color:blue;">如果选择 <b>导入数据库</b> 出现导入失败，那请选择 <b>不导入</b> 项。</div>
					<div id="noImportAlert" style="color:blue;display:none;">可以使用 phpMyAdmin 或 Navicat 等数据库管理软件，把 <span style="color:#000;">install/OTCMS.sql</span> 文件导入到数据库里</div>
				</td>
			</tr>
			<tr>
				<td align="right"></td>
				<td>
					<input type="button" value=" 连接测试 " style="color:red;" onclick="ConnMySql()" />
					<input type='hidden' id='mysqlState' name='mysqlState' value='0' />
					&ensp;&ensp;<label style="color:#c9c8c8;"><input type="checkbox" id="isBug" name="isBug" value="1" />开启检测BUG模式</label>
					&ensp;&ensp;<label style="color:#c9c8c8;"><input type="checkbox" id="isMysqlClass" name="isMysqlClass" value="1" />使用MySqlManage导入函数</label>
				</td>
			</tr>
			</tbody>
			<tr>
				<td colspan="2" class="title2">设置备份目录</td>
			</tr>
			<tr>
				<td align="right">数据库备份目录名:</td>
				<td>
					<input type="text" id="accBackupDir" name="accBackupDir" style="width:250px;" value="<?php echo($dbBakDir); ?>" onkeyup="if (this.value!=FiltFileName(this.value)){this.value=FiltFileName(this.value)}" /> 
					<span class="desc"> 建议修改默认数据库备份目录名</span>
				</td>
			</tr>
			<tr>
				<td colspan="2" class="title2">数据库初始化设置</td>
			</tr>
			<tr>
				<td align="right">清空数据和文件:</td>
				<td style="color:#000;">
					<!-- <label title="清空的数据有：文章、文章评论、栏目、单篇内容、关键词、留言、来源管理、作者管理、上传文件记录、会员上传文件、会员IP管理、会员、投票"><input type="checkbox" id="judClearDB" name="judClearDB" value="true" />确定清空所有数据<span style="color:#a59ea3;">(清空的数据有：文章、文章评论、栏目、单篇内容、关键词、留言、来源管理、作者管理、上传文件记录、会员上传文件、会员IP管理、会员、投票。)</span></label>
					<span class="desc"> </span><br /> -->
					<label><input type="checkbox" id="judClearImg" name="judClearImg" value="true" />确定清空所有上传图片/附件</label>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center" class="btnBox">
					<input id="updateBtn" type="button" value="上一步" onclick="document.location.href='index.php?mudi=check';" />
					&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;
					<?php echo($subBtnStr); ?>
					&ensp;&ensp;<label style="color:#c9c8c8;"><input type="checkbox" id="isDbSkip" name="isDbSkip" value="1" />关闭数据库连接检测</label>
					&ensp;&ensp;<label style="color:#c9c8c8;"><input type="checkbox" id="isSkipChk" name="isSkipChk" value="1" />跳过导入后检测表数量</label>
				</td>
			</tr>
			</form>
			</table>
			<script language='javascript' type='text/javascript'>CheckDbType();</script>
		</td>
		</tr>
		</table>
		<div style="margin:5px;"><b>安装向导使用教程：</b><a href="http://otcms.com/news/8180.html" target="_blank">http://otcms.com/news/8180.html</a></div>
	</td>
	</tr>
	</table>
<?php
}



function run(){
	global $DB,$dbServerName,$dbName;

	$adminName		= OT::PostStr('adminName');
	$adminPwd		= OT::PostStr('adminPwd');
	$adminDir		= OT::PostRegExpStr('adminDir','fileName');

	$isSkipChk		= OT::PostInt('isSkipChk');
	$dbType			= OT::PostStr('dbType');
	$accDir			= OT::PostRegExpStr('accDir','fileName');
	$accName		= OT::PostRegExpStr('accName','fileName');

	$sqlIp			= OT::PostStr('sqlIp');
	$sqlPo			= OT::PostInt('sqlPo');
	$sqlUsername	= OT::PostStr('sqlUsername');
	$sqlUserPwd		= OT::PostStr('sqlUserPwd');
	$sqlDbName		= OT::PostStr('sqlDbName');
	$sqlPref		= OT::PostStr('sqlPref');
	$isImport		= OT::PostInt('isImport');
	$isMysqlClass	= OT::PostInt('isMysqlClass');

	$accBackupDir	= OT::PostRegExpStr('accBackupDir','fileName');

/*	$judDownloadDB	= OT::PostStr('judDownloadDB');
		if ($judDownloadDB != 'true'){ $judDownloadDB='false'; }
	$judDownloadCollDB	= OT::PostStr('judDownloadCollDB');
		if ($judDownloadCollDB != 'true'){ $judDownloadCollDB='false'; }*/

	$judClearDB		= OT::PostStr('judClearDB');
	$judClearImg	= OT::PostStr('judClearImg');

	if ($dbType == 'mysql'){ //  || $sqlUserPwd==''
		if ($adminName=='' || $adminPwd=='' || $adminDir=='' || $sqlIp=='' || $sqlPo=='' || $sqlUsername=='' || $sqlDbName=='' || $sqlPref==''){ 
			JS::AlertBackEnd('数据接收不全');
		}

	}elseif ($dbType == 'sqlite'){
		if ($adminName=='' || $adminPwd=='' || $adminDir=='' || $accDir=='' || $accName=='' || $accBackupDir==''){ 
			JS::AlertBackEnd('数据接收不全');
		}
	
	}else{
		JS::AlertBackEnd('数据库类型选择错误'. $dbType);
	}

	$alertStr = '';
	$dbBakDir = '';
	if ( defined('OT_dbBakDir') ){
		$dbBakDir = str_replace('/','',OT_dbBakDir);
	}
	if (strlen($dbBakDir) == 0){ $dbBakDir = 'Data_backup'; }


	// 创建数据库链接
	if ($dbType == 'mysql'){
		$DB = new PdoDb( array('type'=>'mysql', 'dsn'=>'mysql:host='. $sqlIp .';port='. $sqlPo .';dbname='. $sqlDbName, 'user'=>$sqlUsername, 'pwd'=>$sqlUserPwd, 'dbErr'=>'MySql数据库连接不上，请检查您填写的连接信息是否正确。') );

		if (in_array($isImport,array(1,2))){
			$sqlPath = dirname(__FILE__) .'/OTCMS.sql';
			if ($isMysqlClass == 1){
				// 使用MySqlManage导入函数
				$db = new MySqlManage($sqlIp, $sqlPo, $sqlUsername, $sqlUserPwd, $sqlDbName);
				$db->restore( $sqlPath );

			}else{
				$sqlFileStr = str_replace('`OT_', '`'. $sqlPref, file_get_contents($sqlPath));

				$sqlFileArr = array(); // 数据段
				preg_match_all( "@([\s\S]+?;)\h*[\n\r]@" , $sqlFileStr , $sqlFileArr ); // 数据以分号;\n\r换行  为分段标记
				!empty( $sqlFileArr[1] ) && $sqlFileArr = $sqlFileArr[1];
				$count = count($sqlFileArr);
				if ( $count <= 0 ) {
					exit( 'mysql数据文件: '. $sqlPath .' ,不是正确的数据文件. 请检查安装包.' );
				}

				$sqlTabArr = array(); // 表名列表
				preg_match_all( '@CREATE\h+TABLE\h+[`]?([^`]+)[`]?@' , $sqlFileStr , $sqlTabArr );
				!empty( $sqlTabArr[1] ) && $sqlTabArr = $sqlTabArr[1];
				$sqlTabCount = count($sqlTabArr);

				ob_start();

				$alertStr .= '处理数据段落：'. $count .'个，创建表：'. $sqlTabCount .'个<br />';
				echo('
				正在处理数据段落：<span id="dataNum">0</span>/'. $count .'个，忽略<span id="skipNum">0</span>个，正在建表：<span id="tabNum">0</span>/'. $sqlTabCount .'个<br />
				<div id="tabListStr" style="width:320px;height:250px;overflow:auto;"></div>
				');
				ob_flush();
				flush();

				// 开始循环执行
				$tabNum = 0;
				$skipNum = 0;
				for($i=0; $i<$count ;$i++){
					$sqlStr = '';
					$sql = $sqlFileArr[$i] ;
					if ($isImport == 2){
						$skipArr = array('info','infoMessage','infoType','infoWeb','message','memberLog','paySoft','upFile','userFile','userIp','users');
						foreach ($skipArr as $tabVal){
							$chkStr = 'INSERT INTO `'. $sqlPref . $tabVal .'`';
							//echo(substr($sql,0,strlen($chkStr)) .'|'. $chkStr .'|'. strlen($chkStr) .'<br /><br />');
							//die($sql . $chkStr . strlen($chkStr) . substr('INSERT INTO `OT_info` 1222',0,strlen($chkStr)));
							if ( strpos($sql,$chkStr) !== false){ $skipNum++; continue 2; } // die('44');
						}
					}
					$result = $DB->query($sql);
					
					// 建表数量
					if ( $i < $sqlTabCount ) {
						$tabNum ++;
						$sqlStr .= '<span style="display:inline-block; width:250px;">创建表['. $tabNum .']: '. $sqlTabArr[$i] .'</span>';

						if($result){
							$sqlStr .= ' <font color="green">成功</font>';
						}else{
							$sqlStr .= ' <font color="red">失败</font>';
						}
						$sqlStr .= '<br />\n';
					}else{
						// 执行其它语句
						if(! $result){
							$sqlStr .= '\n<br /> sql语句（'. $sql .'）执行<font color="red">失败</font>';
						}
					}

					$alertStr .= $sqlStr;
					echo('
					<script language="javascript" type="text/javascript">
					document.getElementById("dataNum").innerHTML="'. ($i+1) .'";
					document.getElementById("skipNum").innerHTML="'. $skipNum .'";
					document.getElementById("tabNum").innerHTML="'. $tabNum .'";
					document.getElementById("tabListStr").innerHTML=\''. $sqlStr .'\'+ document.getElementById("tabListStr").innerHTML;
					</script>
					');
					ob_flush();
					flush();
				}
			}
		}

		if ($isSkipChk != 1){
			$tabNum = $DB->GetOne("select count(TABLE_NAME) from information_schema.tables where TABLE_SCHEMA='". $sqlDbName ."' and TABLE_NAME in ('". $sqlPref ."info','". $sqlPref ."infoType','". $sqlPref ."system')");
			if ($tabNum < 3){
				if ($isImport == 0){
					JS::AlertBackEnd('您选择不导入模式，请先手动把数据库导入到mysql里，在进行该步骤。');
				}else{
					JS::AlertBackEnd('数据库表不存在，可能数据库导入出错，请重新试下，如果还是该提示，请手动把数据库导入到mysql里，初始库 项选择 不导入 模式。');
				}
			}
		}

	}elseif ($dbType == 'sqlite'){
		$sqlPref = 'OT_';
		$DB = new PdoDb( array('type'=>'sqlite', 'dsn'=> 'sqlite:'. OT_ROOT . $dbName) );

	}else{
		JS::AlertBackEnd('数据库类型不对。');
	}
	$DB->SetTabPref($sqlPref);


	$newUserKey	= OT::RndChar(5);
	$adminPwd	= md5(md5($adminPwd) . $newUserKey);

	$record = array();
	$record['MB_username']	= $adminName;
	$record['MB_userpwd']	= $adminPwd;
	$record['MB_userKey']	= $newUserKey;
	$judResult = $DB->UpdateParam('member', $record, 'MB_ID=1');

	$DB->query("update ". $sqlPref ."userSys set US_loginKey='". OT::RndChar(36) ."'");
	$DB->query("update ". $sqlPref ."sysAdmin set SA_adminLoginKey='". OT::RndChar(36) ."'");

	$Cache = new Cache();
	$Cache->Php('userSys');
	$Cache->Js('userSys');
	$Cache->Php('infoSys');
	$Cache->Js('infoSys');
	$Cache->Php('sysAdmin');
	$Cache->Php('system');
	$Cache->Js('system');
	$Cache->Php('sysImages');

	if ($judClearDB == 'true'){
		if ($dbType == 'mysql'){
			$DB->query('TRUNCATE TABLE '. $sqlPref .'info');
			$DB->query('TRUNCATE TABLE '. $sqlPref .'infoMessage');
			$DB->query('TRUNCATE TABLE '. $sqlPref .'infoType');
			$DB->query('TRUNCATE TABLE '. $sqlPref .'infoWeb');
			$DB->query('TRUNCATE TABLE '. $sqlPref .'message');
			$DB->query('TRUNCATE TABLE '. $sqlPref .'memberLog');
			$DB->query('TRUNCATE TABLE '. $sqlPref .'paySoft');
			$DB->query('TRUNCATE TABLE '. $sqlPref .'upFile');
			$DB->query('TRUNCATE TABLE '. $sqlPref .'userFile');
			$DB->query('TRUNCATE TABLE '. $sqlPref .'userIp');
			$DB->query('TRUNCATE TABLE '. $sqlPref .'users');

		}else{
			$DB->query('delete from '. $sqlPref .'info');
			$DB->query('delete from '. $sqlPref .'infoMessage');
	//		$DB->query('delete from '. $sqlPref .'infoMove');
			$DB->query('delete from '. $sqlPref .'infoType'); //  where IT_mode<>'urlHome' or IT_mode<>'urlMessage'
			$DB->query('delete from '. $sqlPref .'infoWeb');
	//		$DB->query('delete from '. $sqlPref .'keyWord');
			$DB->query('delete from '. $sqlPref .'message');
			$DB->query('delete from '. $sqlPref .'memberLog');
			$DB->query('delete from '. $sqlPref .'paySoft');
	//		$DB->query('delete from '. $sqlPref .'type');
			$DB->query('delete from '. $sqlPref .'upFile');
			$DB->query('delete from '. $sqlPref .'userFile');
			$DB->query('delete from '. $sqlPref .'userIp');
			$DB->query('delete from '. $sqlPref .'users');
			if ($dbType == 'sqlite'){
				$DB->query("UPDATE sqlite_sequence SET seq=0 WHERE name in ('". $sqlPref ."info','". $sqlPref ."infoMessage','". $sqlPref ."infoType','". $sqlPref ."infoWeb','". $sqlPref ."message','". $sqlPref ."memberLog','". $sqlPref ."paySoft','". $sqlPref ."upFile','". $sqlPref ."userFile','". $sqlPref ."userIp','". $sqlPref ."users')");
				$DB->query('VACUUM');
			}
		}
	}
	$DB = null;

	if ($judClearImg == 'true'){ 
		File::DelDir(OT_ROOT .'upFiles/infoImg/coll/');
		File::DelDir(OT_ROOT .'upFiles/infoImg/ueditor/');
		File::MoreDel(OT_ROOT .'upFiles/infoImg/');
		File::CreateDir(OT_ROOT .'upFiles/infoImg/coll/');
		File::CreateDir(OT_ROOT .'upFiles/infoImg/ueditor/');
	}

	$webSiteID		= OT::RndABC(5) .'_';
	$webBackupDir	= $accBackupDir;
	$webDbName		= $accName;

	$dbNameArr = explode('/',$dbName);
	$alertNum = 0;
	$isAdminDir		= File::RevName(OT_ROOT . $_SESSION['adminDir'], OT_ROOT . $adminDir);
		if (! $isAdminDir){ 
			$alertNum ++;
			$alertStr .= $alertNum .'、后台目录名重命名（新名称:'. $adminDir .'）失败；<br />';
		}else{
			$_SESSION['adminDir'] = $adminDir;	
		}

	if ($dbType == 'sqlite'){
		$isDatabaseName	= File::RevName(OT_ROOT . $dbName, OT_ROOT . $dbNameArr[0] .'/'. $accName);
			if (! $isDatabaseName){ 
				$alertNum ++;
				$alertStr .= $alertNum .'、网站数据库名称重命名（新名称:'. $accName .'）失败；<br />';
				$webDbName = $dbNameArr[1];
				$accDir = $dbNameArr[0];
			}else{
				$isDatabaseDir	= File::RevName(OT_ROOT . $dbNameArr[0], OT_ROOT . $accDir);
					if (! $isDatabaseDir){ 
						$alertNum ++;
						$alertStr .= $alertNum .'、数据库目录名重命名（新名称:'. $accDir .'）失败；<br />';
						$accDir = $dbNameArr[0];
					}
			
			}
	}

	$isBackupDir	= File::RevName(OT_ROOT . $dbBakDir, OT_ROOT . $accBackupDir);
		if (! $isBackupDir){ 
			$alertNum ++;
			$alertStr .= $alertNum .'、数据库备份目录名重命名（新名称:'. $accBackupDir .'）失败；<br />';
			$webBackupDir = $dbBakDir;
		}

	$configContent = File::Read('config.OTtpl');
	$configContent = str_replace(array('{%SiteID%}','{%DbType%}','{%DbDir%}','{%DbName%}','{%sqlIp%}','{%sqlPort%}','{%sqlUsername%}','{%sqlUserPwd%}','{%sqlDbName%}','{%sqlPref%}','{%BackupDir%}'), array($webSiteID,$dbType,$accDir,$webDbName,$sqlIp,$sqlPo,$sqlUsername,$sqlUserPwd,$sqlDbName,$sqlPref,$webBackupDir), $configContent);
	File::Write(OT_ROOT .'config.php', $configContent);

	File::Write(OT_ROOT .'cache/web/install.lock', 'Powered By 网钛科技 Copyright 2010-'. TimeDate::Get('Y'));
	if ($alertNum>0 && $alertStr!=''){ 
		$alertStr .= '<br />建议重命名以上'. $alertNum .'个目录名，然后修改config.php文件里的相关信息。';
	}else{
		$alertStr .= '<span style="color:green;">设置成功!</span>';
	}

	echo('
	<form id="resultForm" name="resultForm" method="post" action="index.php?mudi=finish">
	<textarea id="result" name="result" style="display:none;">'. str_replace('\n','',$alertStr) .'</textarea>
	<input type="submit" id="subBtn" name="subBtn" value="" style="display:none;" />
	</form>
	<script language="javascript" type="text/javascript">
	document.getElementById("resultForm").submit();
	</script>
	');
}



function FinishWeb(){
	$result	= OT::PostStr('result');

	$beforeURL = GetUrl::CurrDir(1);

?>
<table width="100" border="0" align="center" cellpadding="3" cellspacing="0" class="box1">
<tr>
<td>
	<table width="100" border="0" cellpadding="0" cellspacing="1" class="box2">
	<tr>
	<td>
		<table width="800" border="0" align="center" cellpadding="0" cellspacing="0">
		<tr>
			<td class="title border1">
				安装向导初始化结束
			</td>
		</tr>
		<tr>
			<td align="left" class="td1" style="padding:10px 0 10px 0;">
				<table align="center"><tr><td style="line-height:1.8;">
					<?php echo($result); ?><br />
					<b>后台地址：</b><?php echo('<a href="'. $beforeURL . $_SESSION['adminDir'] .'/">'. $beforeURL . $_SESSION['adminDir'] .'/</a>'); ?><br />
					为了安全，请重命名(或删除)根目录下install(安装向导)文件夹
				</td></tr></table>
			</td>
		</tr>
		<tr>
			<td align="center" class="btnBox">
				<input type="button" value="进入首页" onclick="document.location.href='../';" />
				&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;
				<input type="button" value="登录后台" onclick="document.location.href='../<?php echo($_SESSION['adminDir']); ?>';" />
				&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;
				<input type="button" value=" 关 闭 " onclick="window.close();">
			</td>
		</tr>
		</table>
	</td>
	</tr>
	</table>
</td>
</tr>
</table>
<?php
}



function ExtCN($jud, &$failNum=0){
	if ($jud){
		return '<span style="color:green;">支持</span>';
	}else{
		$failNum ++;
		return '<span style="color:red;">不支持</span>';
	}
}



function SetAdminDir(){
	$newAdminDir = OT::GetStr('newAdminDir');
	$_SESSION['adminDir'] = $newAdminDir;

	JS::HrefEnd('index.php?mudi=check');
}



function CheckDbState(){
	error_reporting(0);

	$sqlIp			= OT::GetStr('sqlIp');
	$sqlPo			= OT::GetInt('sqlPo');
	$sqlUsername	= OT::GetStr('sqlUsername');
	$sqlUserPwd		= OT::GetStr('sqlUserPwd');
	$sqlDbName		= OT::GetStr('sqlDbName');
	$sqlPref		= OT::GetStr('sqlPref');
	$isCreateDB		= OT::GetInt('isCreateDB');

	$DB = new PdoDb( array('type'=>'mysql', 'dsn'=>'mysql:host='. $sqlIp .';port='. $sqlPo, 'user'=>$sqlUsername, 'pwd'=>$sqlUserPwd, 'dbErr'=>'MySql数据库连接不上，请检查您填写的连接信息是否正确。') );
	if ($isCreateDB == 1){
		$judResult = $DB->query('CREATE DATABASE IF NOT EXISTS '. $sqlDbName .' DEFAULT CHARSET utf8 COLLATE utf8_general_ci;');
		if (! $judResult){
			die('创建数据库（'. $sqlDbName .'）失败，可能你的连接账号不具有创建数据库权限，请填写已存在的数据库名。');
		}else{
			echo('MySql连接成功,数据库（'. $sqlDbName .'）已创建！');
		}
	}else{
		$DB = new PdoDb( array('type'=>'mysql', 'dsn'=>'mysql:host='. $sqlIp .';port='. $sqlPo .';dbname='. $sqlDbName, 'user'=>$sqlUsername, 'pwd'=>$sqlUserPwd, 'dbErr'=>'MySql数据库已连接上，但找不到数据库（'. $sqlDbName .'），请检查您填写的数据库名是否正确。') );
		echo('MySql连接成功！');
	}
}

?>
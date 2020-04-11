<?php
require(dirname(__FILE__) .'/check.php');
$skin->CheckIframe();

/* **** 版权所有 ****

 网钛CMS(OTCMS)
 程序开发：网钛科技
 官方网站：http://otcms.com
 联系ＱＱ：877873666
 联系邮箱：877873666@qq.com

 **** 版权所有 **** */


//打开用户表，并检测用户是否登录
$MB->Open('','login');

$skin->WebTop();


$MB->IsAdminRight('alertBack');


$tabColorArr = array(
	'manage'	=> '',
	'winRight'	=> '',
	'linuxRight'=> '',
	'check'		=> '',
	'del'		=> '',
	'db'		=> '',
	'sql'		=> '',
	'del'		=> '',
	'file'		=> '',
	);

switch($mudi){
	case 'manage':	
	case 'winRight':
	case 'linuxRight':
	case 'check':
	case 'del':
	case 'db':
	case 'sql':
	case 'del':
	case 'file':
		$tabColorArr[$mudi]	= 'class="selected"';
		break;
}


echo('
<script language="javascript" type="text/javascript" src="js/inc/list.js?v='. OT_VERSION .'"></script>
<script language="javascript" type="text/javascript" src="js/inc/trim.js?v='. OT_VERSION .'"></script>
<script language="javascript" type="text/javascript" src="js/sysCheckFile.js?v='. OT_VERSION .'"></script>

<div class="tabMenu">
<ul>
   <li '. $tabColorArr['manage'] .' onclick=\'document.location.href="?mudi=manage";\'>程序文件对比</li>
   <li '. $tabColorArr['check'] .' onclick=\'document.location.href="?mudi=check";\'>检查多余/问题文件</li>
   <li '. $tabColorArr['del'] .' onclick=\'document.location.href="?mudi=del";\'>无用旧文件删除</li>
   <li '. $tabColorArr['winRight'] .' onclick=\'document.location.href="?mudi=winRight";\'>Window目录权限</li>
   <li '. $tabColorArr['linuxRight'] .' onclick=\'document.location.href="?mudi=linuxRight";\'>Linux文件权限</li>
   <li '. $tabColorArr['db'] .' onclick=\'document.location.href="?mudi=db";\'>获取数据库结构</li>
   <li '. $tabColorArr['sql'] .' onclick=\'document.location.href="?mudi=sql";\'>SQL语句调试</li>
   <li '. $tabColorArr['file'] .' onclick=\'document.location.href="?mudi=file";\'>可选文件下载</li>
</ul><!-- <input type="button" value="计算网站占用空间" onclick="CalcSiteSize();" /> -->
</div>
');


switch ($mudi){
	case 'manage':
		manage();
		break;

	case 'winRight':
		winRight();
		break;

	case 'linuxRight':
		linuxRight();
		break;

	case 'check':
		check();
		break;

	case 'del':
		del();
		break;

	case 'db':
		db();
		break;

	case 'sql':
		sql();
		break;

	case 'file':
		fileWeb();
		break;

	case 'del':
		del();
		break;

	default:
		die('err');
}

$skin->WebBottom();

$MB->Close();
$DB->Close();





// 程序文件对比
function manage(){
	global $DB,$skin,$mudi,$sysAdminArr,$dataType,$dataTypeCN;

	$mudi2		= OT::GetStr('mudi2');
	$noReadImg	= OT::GetInt('noReadImg');
	if ($mudi2 == 'startStep2'){
		@ini_set('max_execution_time', 0);
		@set_time_limit(0); 

		$step1BtnStyle = 'disabled="true"';
		$step2BtnStyle = 'disabled="true"';
		$step2BtnStr = '第2步：请稍等，待加载完才能点该按钮...';
	}else{
		$step1BtnStyle = '';
		$step2BtnStyle = 'disabled="true"';
		$step2BtnStr = '第2步：与网站文件做对比';
	}

	$alertStr3 = '';
	if ($sysAdminArr['SA_isLan'] == 1){
		$alertStr3 .= '已开启<span style="color:red;">内网模式</span>；';
	}
	if ($sysAdminArr['SA_sendUrlMode'] == 1){
		$alertStr3 .= '授权页面获取模式：<span style="color:red;">AJAX</span>；';
	}
	if ($sysAdminArr['SA_checkUrlMode'] > 0){
		$alertStr3 .= '授权页面获取路线：<span style="color:red;">'. Adm::UrlWebMode($sysAdminArr['SA_checkUrlMode']) .'</span>；';
	}
	if ($sysAdminArr['SA_updateUrlMode'] > 0){
		$alertStr3 .= '授权信息获取路线：<span style="color:red;">'. Adm::UrlWebMode($sysAdminArr['SA_updateUrlMode']) .'</span>；';
	}
	if ($sysAdminArr['SA_getUrlMode'] > 0){
		$alertStr3 .= '授权调用模式：<span style="color:red;">'. Adm::UrlGetMode($sysAdminArr['SA_getUrlMode']) .'</span>；';
	}
	if ($sysAdminArr['SA_collUrlMode'] > 0){
		$alertStr3 .= '授权信息获取路线：<span style="color:red;">'. Adm::UrlGetMode($sysAdminArr['SA_collUrlMode']) .'</span>；';
	}
	if (strlen($alertStr3) > 0){ $alertStr3 = '<div style="color:#000;margin-bottom:12px;">'. $alertStr3 .'</div>'; }

	echo('
	<div style="padding:5px;">
		'. $alertStr3 .'
		<input type="button" value="第1步：获取原版文件信息" onclick=\'document.location.href="?mudi=manage&mudi2=startStep2&noReadImg="+ IsChecked("noReadImg");\' '. $step1BtnStyle .' />&ensp;&ensp;
		<input type="button" value="'. $step2BtnStr .'" onclick="CheckFile()" id="step2Btn" '. $step2BtnStyle .' />&ensp;&ensp;
		<span id="resultAlertStr" style="color:blue;"></span>
		<div style="padding-top:5px;">
			<input type="hidden" id="runStopState" name="runStopState" value="0" />
			<input type="button" value="暂停" onclick="RunStop()" id="runStopBtn" /><span id="runStopAlert" style="color:red;"></span>&ensp;&ensp;
			<input type="button" value="复制异常文件列表" onclick="CopyErrFile()" />&ensp;&ensp;
			<label><input type="checkbox" id="noReadImg" name="noReadImg" value="1" checked="checked" />忽略图片文件</label>&ensp;&ensp;
			<label title="文件对比功能卡在那边没法进行时，可以开启该模式，然后点【第2步】按钮，会弹出新窗口，看看有什么错误信息出现造成的"><input type="checkbox" id="isBugMode" name="isBugMode" value="1" />调试模式</label>&ensp;&ensp;
		</div>
		<div><textarea id="errFileList" style="width:450px;height:100px;display:none;"></textarea></div>
	</div>
	');

	if ($mudi2 == 'startStep2'){
		echo('
		<form id="listForm" name="listForm" method="post" action="sysCheckFile_deal.php?mudi=checkFile">
		<script language="javascript" type="text/javascript">document.write (\'<input type="hidden" name="backURL" value="\'+ document.location.href +\'" />\')</script>
		<input type="hidden" name="dataType" value="'. $dataType .'" />
		<input type="hidden" name="dataTypeCN" value="'. $dataTypeCN .'" />
		');

		$skin->TableTop2('share_list.gif','','异常文件列表');
		$skin->TableItemTitle('8%,50%,8%,15%,19%','序号,文件路径,文件大小,最后修改时间,结果');

		$adminURL	= GetUrl::CurrDir();
		$beforeURL	= GetUrl::CurrDir(1);
		$adminDirName = substr($adminURL,strlen($beforeURL),-1);

		$number = 0;
		$postDataArr = array();

		echo('
		<tbody class="tabBody padd3">
		<tr><td align="left" colspan="5" style="background:#f3f3f3;padding:6px;font-weight:bold;font-size:14px;">
			程序文件 v'. OT_VERSION .' '. OT_UPDATETIME .'
			&ensp;&ensp;<a href="http://d.otcms.com/php/OTCMS_PHP_V'. OT_VERSION . (OT_UPDATETIME >= 20200223 ? '.zip' : '.rar') .'" target="_blank" style="font-size:12px;color:red;">[点击下载该安装包，提取源文件覆盖异常文件修复]</a>
		</td></tr>
		');
		CheckSoftVerList('dataVer'. OT_UPDATEVER .'/verFileList/OTCMS_0_V'. OT_VERSION .'.txt', $adminDirName, $noReadImg, $number, $postDataArr);

		echo('<tr><td align="left" colspan="5" style="background:#f3f3f3;padding:6px;font-weight:bold;font-size:14px;">插件文件&ensp;&ensp;<span style="font-size:12px;color:red;">[插件平台]-[个人中心]-[域名管理]-[插件列表]可对各插件进行[重新升级]修复</span></td></tr>');
		$payexe = $DB->query('SELECT PS_theme,PS_appType,PS_appID,PS_currVer,PS_currTime FROM '. OT_dbPref .'paySoft where PS_state=1');
		while ($row = $payexe->fetch()){
			echo('<tr><td align="left" colspan="5" style="background:#f3f3f3;color:blue;">插件：'. $row['PS_theme'] .' v'. $row['PS_currVer'] .' '. $row['PS_currTime'] .'</td></tr>');
			CheckSoftVerList($row['PS_appType'] .'/verFileList/OTCMS_'. $row['PS_appID'] .'_V'. $row['PS_currVer'] .'.txt', $adminDirName, $noReadImg, $number, $postDataArr);
		}

		echo('
		</tbody>
		</table>
		<input type="hidden" id="fileStart" name="fileStart" value="0" />
		<input type="hidden" id="fileStep" name="fileStep" value="50" />
		<input type="hidden" id="fileTotal" name="fileTotal" value="'. $number .'" />
		<input type="hidden" id="fileData" name="fileData" value="'. implode('[arr]',$postDataArr) .'" />
		</form>

		<div style="color:red;">
			<b>提醒：</b>【程序文件】如果对比出异常文件，可点击<a href="http://d.otcms.com/php/OTCMS_PHP_V'. OT_VERSION . (OT_UPDATETIME >= 20200223 ? '.zip' : '.rar') .'" target="_blank" style="font-size:12px;color:red;">[下载安装包，提取源文件覆盖异常文件]</a>，下载该版本安装包提取对应文件覆盖。<br />
			【插件文件】如果对比出异常文件，可进入[插件平台]-[个人中心]-[域名管理]-[插件列表]，对相关插件进行[重新升级]操作。
		</div>

		<script language="javascript" type="text/javascript">
		$id("step2Btn").value = "第2步：与网站文件做对比"; $id("step2Btn").disabled=false;
		</script>
		');
	}else{
		echo('
		<div class="padd5" style="color:red;">（由于程序文件匹配规则很严，故提示异常的不一定是真的有问题文件，结果仅供参考.）</div>
		');
	}

}



// Win文件权限
function winRight(){
	global $DB,$skin,$mudi,$sysAdminArr,$dataType,$dataTypeCN;

	$startUrl = 'sysCheckFile_deal.php?mudi=testPhpRun&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'';
	$dataArr = array(
		'cache','cache_html','cache_js','cache_php','cache_session','cache_smarty','cache_taobao','cache_web',
		'upFiles','upFiles_download','upFiles_images','upFiles_infoImg','upFiles_product','upFiles_users',
		'wap_cache'
		);

	$skin->TableTop2('share_list.gif','','目录权限检查');
	$skin->TableItemTitle('8%,30%,22,40%','序号,文件路径,自动操作,手动操作');
	echo('<tbody class="tabBody padd3">');

	$dataNum = 0;
	foreach ($dataArr as $val){
		$dataNum ++;
		$newVal = str_replace('_', '/', $val);
		echo('
		<tr>
			<td align="center">'. $dataNum .'</td>
			<td align="left">'. $newVal .'/</td>
			<td align="center">
				<input type="button" value="检查执行权限" style="cursor:pointer;" onclick=\'DataDeal.location.href="'. $startUrl .'&mudi2=run&fileType='. $val .'"\' />
			</td>
			<td align="center">
				<input type="button" value="创建测试文件" style="cursor:pointer;'. (file_exists(OT_ROOT . $newVal .'/testOtcmsRun.php') ? 'color:blue;font-weight:bold;' : '') .'" onclick=\'DataDeal.location.href="'. $startUrl .'&mudi2=create&fileType='. $val .'"\' />
				<input type="button" value="访问测试文件" style="cursor:pointer;" onclick=\'var a=window.open("../'. $newVal .'/testOtcmsRun.php");\' />
				<input type="button" value="删除测试文件" style="cursor:pointer;" onclick=\'DataDeal.location.href="'. $startUrl .'&mudi2=del&fileType='. $val .'"\' />
			</td>
		</tr>
		');
	}
	echo('
	</tbody>
	</table>
	<div style="color:blue;margin:6px;">提醒：如果【自动操作】不能用，请用【手动操作】（先创建，然后访问，看是否有显示内容来判断是否能运行PHP文件，再然后删除）</div>
	<br /><br />
	');


	$startUrl = 'sysCheckFile_deal.php?mudi=webConfigDeal&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'';
	$dataArr = array(
		'admin/images'	=> '后台图片目录',
		'admin/js'		=> '后台js文件目录',
		'admin/temp'	=> '后台临时图片目录',
		'admin/tools'	=> '后台工具目录',
		'admin/upFile'	=> '后台上传图片目录<span style="color:red;">【建议有】</span>',
		'cache'			=> '缓存目录<span style="color:red;">【建议有】</span>',
		'Data'			=> '数据库目录<span style="color:red;">【建议有】</span>',
		'Data_backup'	=> '备份目录<span style="color:red;">【建议有】</span>',
		'html'			=> '静态页存放目录<span style="color:red;">【建议有】</span>',
		'inc_img'		=> '图片目录',
		'js'			=> 'js文件目录',
		'pay'			=> '支付核心文件目录<span style="color:red;">【建议有】</span>',
		'pluDef'		=> '插件默认配置目录<span style="color:red;">【建议有】</span>',
		'plugin'		=> '插件目录<span style="color:red;">【建议有】</span>',
		'smarty'		=> '模板引擎目录<span style="color:red;">【建议有】</span>',
		'temp'			=> '临时文件目录',
		'template'		=> '模板目录',
		'tools'			=> '工具目录',
		'upFiles'		=> '上传图片附件目录<span style="color:red;">【建议有】</span>',
		'web_config'	=> '环境配置目录'
		);
	if (AppWap::Jud()){
		$dataArr['wap/cache']		= '手机版缓存目录<span style="color:red;">【建议有】</span>';
		$dataArr['wap/html']		= '手机版静态页目录<span style="color:red;">【建议有】</span>';
		$dataArr['wap/images']		= '手机版图片目录';
		$dataArr['wap/js']			= '手机版js文件目录';
		$dataArr['wap/skin']		= '手机版多皮肤目录';
		$dataArr['wap/template']	= '手机版模板目录';
		$dataArr['wap/tools']		= '手机版工具目录';
	}

	$skin->TableTop2('share_list.gif','','IIS环境 取消执行权限配置文件 列表');
	$skin->TableItemTitle('8%,25%,15%,20%,32','序号,配置文件路径,状态,备注,操作');
	echo('<tbody class="tabBody padd3">');

	$dataNum = 0;
	foreach ($dataArr as $key => $val){
		$dataNum ++;
		if ($key == 'Data'){
			$filePath = OT_ROOT . OT_dbDir .'web.config';
		}elseif ($key == 'Data_backup'){
			$filePath = OT_ROOT . OT_dbBakDir .'web.config';
		}elseif (strpos($key,'admin/') !== false){
			$filePath = str_replace('admin/',OT_adminROOT,$key) .'/web.config';
		}else{
			$filePath = OT_ROOT . $key .'/web.config';
		}
		if (is_file($filePath)){
			if (md5(str_replace(array("\r","\n"),'',File::Read($filePath))) == 'd527f243a4607c2fb74349361adf51e7'){
				$stateCN = '<span style="color:green;">存在</span>';
			}else{
				$stateCN = '<span style="color:;">存在，但md5不一致</span>';
			}
		}else{
			$stateCN = '<span style="color:red;">不存在</span>';
		}
		
		echo('
		<tr>
			<td align="center">'. $dataNum .'</td>
			<td align="left">'. $key .'/<span style="color:blue;">web.config</span></td>
			<td align="center">'. $stateCN .'</td>
			<td align="left">'. $val .'</td>
			<td align="center">
				<input type="button" value="创建配置文件" style="cursor:pointer;" onclick=\'DataDeal.location.href="'. $startUrl .'&mudi2=create&fileType='. urlencode($key) .'"\' />
				<input type="button" value="删除配置文件" style="cursor:pointer;" onclick=\'DataDeal.location.href="'. $startUrl .'&mudi2=del&fileType='. urlencode($key) .'"\' />
			</td>
		</tr>
		');
	}
	echo('
	</tbody>
	</table>
	');
}



// Linux文件权限
function linuxRight(){
	global $DB,$skin,$mudi,$sysAdminArr,$dataType,$dataTypeCN;

	echo('
	<div style="padding:5px;">
		文件路径：<input type="text" id="refPath" name="refPath" size="20" style="width:180px;" />&ensp;&ensp;
		权限值：<input type="text" id="refLimitNum" name="refLimitNum" size="20" style="width:50px;" />&ensp;&ensp;
		<input type="button" value=" 查 找 " onclick="RefLimitList()" />&ensp;&ensp;
	</div>
	');

	$skin->TableTop2('share_list.gif','','文件权限列表');
	$skin->TableItemTitle('4%,5%,57%,12%,10%,12%','<input type="checkbox" id="selAll" name="selAll" onclick="CheckBoxAll()" />,序号,文件路径,权限符,权限值,文件大小');

	$number = 0;
	echo('
	<tbody class="tabBody padd3">
	');

	File_Each(OT_ROOT,'',$number);

	echo('
	<input type="hidden" id="fileCount" name="fileCount" value="'. $number .'" />
	</tbody>
	<tr class="tabColorB padd5">
		<td align="left" colspan="20">
			<form id="listForm" name="listForm" method="post" action="sysCheckFile_deal.php?mudi=revLimit" onsubmit="return CheckListForm()">
			<script language="javascript" type="text/javascript">document.write (\'<input type="hidden" name="backURL" value="\'+ document.location.href +\'" />\')</script>
			<input type="hidden" name="dataType" value="'. $dataType .'" />
			<input type="hidden" name="dataTypeCN" value="'. $dataTypeCN .'" />
			<input type="hidden" id="limitFileList" name="limitFileList" value="" />

			<input type="button" value="全选" onclick="AllSelBox()" class="form_button1" />
			<input type="button" value="反选" onclick="RevSelBox()" class="form_button1" />
			&ensp;
			修改权限值为：<input type="text" id="newLimitNum" name="newLimitNum" maxlength="3" style="width:50px;" onkeyup="if (this.value!=FiltInt(this.value)){this.value=FiltInt(this.value)}" />
			<input type="submit" value="确定批量修改" />
			&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;<a href="#" onclick=\'ConfirmHref("警告！慎点！如果网站权限没问题，请不要点。\n如果继续点【确定】，否者点【取消】","sysCheckFile_recur.php","");return false;\' target="_blank" style="color:blue;font-weight:bold;">&gt;&gt;警告！慎点！点击运行全站目录755和文件644权限调整脚本（网站权限没问题的不要点）。</a>
			</form>
		</td>
	</tr>
	</table>
	');
}



function File_Each($dirPath, $rootDir='', &$number){
	$retArr = array();
	if ($handle = opendir($dirPath)) {
		if (substr($dirPath,-1) != "/"){ $dirPath .= "/"; }
		$dirArr = array();
		$dirListArr = array();
		$fileListArr = array();
		while (($file = readdir($handle)) !== false) {
			if ($file != "." && $file != "..") {
				if (is_dir($dirPath . $file)){
					$dirArr[] = $file;
				}elseif ( strpos(strtolower("|thumbs.db|历史更新记录.txt|使用教程【必看】.txt|风格目录名称说明.txt|favicon.ico|# OTCMS@!db%22.db|# otcms@!db%22.ldb|# otcms@!db%22.mdb|# otcms_coll.mdb|ip库说明.txt|filelist.php|config.php|sitemap.html|narEnd.gif|onload.gif|close.gif|button_true.gif|httpd.ini|robots.txt|lock.txt|123.txt|"),"|". strtolower(Str::ChangeCharset($file,"GBK")) ."|") === false ){
					if (strpos("|.png|.jpg|jpeg|.gif|.swf|.rar|.mdb|.ico|.cab|.exe|", "|". strtolower(substr($file,-4) ."|")) === false){
						$number ++;
						if ($number % 2 == 0){ $bgcolor='class="tabColorTr"'; }else{ $bgcolor=''; }
						$filePath = $dirPath . $file;
						$fileSize	= filesize($filePath);
						$fileLimit	= File::LimitNum($filePath);
						echo('
						<input type="hidden" id="state'. $number .'" name="state'. $number .'" value="1" />
						<input type="hidden" id="pathinfo'. $number .'" name="pathinfo'. $number .'" value="'. $filePath .'" />
						<input type="hidden" id="sizeinfo'. $number .'" name="sizeinfo'. $number .'" value="'. $fileSize .'" />
						<input type="hidden" id="limitinfo'. $number .'" name="limitinfo'. $number .'" value="'. $fileLimit .'" />
						<tr '. $bgcolor .' id="data'. $number .'">
							<td align="center"><input type="checkbox" id="sel'. $number .'" name="selDataID[]" value="'. $number .'" /></td>
							<td align="center">'. $number .'</td>
							<td id="filePath'. $number .'" align="left">'. Str::ChangeCharset($rootDir . $file) .'</td>
							<td align="center" id="limitChar'. $number .'">'. File::LimitChar($filePath) .'</td>
							<td align="center" id="limitNum'. $number .'">'. $fileLimit .'</td>
							<td align="right">'. File::SizeUnit($fileSize) .'</td>
							<!-- <td align="center"><br /></td> -->
						</tr>
						');
					}
				}
			}
		}
		foreach ($dirArr as $dir){
			if (strpos('|install/|upFiles/|temp/|smarty/|'. OT_dbDir .'|'. OT_dbBakDir .'|','|'. $dir .'/|') === false){
				$dirListArr = array_merge($dirListArr,File_Each($dirPath . $dir .'/', $rootDir . $dir .'/', $number));
			}
		}
		$retArr = array_merge($fileListArr, $dirListArr);
	}
	closedir($handle);
	return $retArr;
}



// 检查多余/问题文件
function check(){
	global $DB,$skin,$mudi,$sysAdminArr;

	$skin->TableTop('share_rev.gif','','非程序目录检查');
		echo('
		<div><input type="button" value="检查" onclick="CheckSoftDir();" /></div>
		<div id="dirErrList" style="line-height:1.4;"></div>
		');
	$skin->TableBottom();

	echo('
	<br />
	');

	$skin->TableTop('share_rev.gif','','非程序文件检查');
		echo('
		<input type="button" value="检查" onclick="CheckSoftDirFile();" />&ensp;&ensp;<span style="color:red;">（点击【检查】按钮前，请先点击 <input type="button" value="清理缓存" onclick="UpdateAllCache();return false;" /> 和 <input type="button" id="clearUpdateDbBtn" value="清空升级库" onclick="ClearUpdateDb();" />，避免把一些缓存视为异常文件列出）</span>
		<div style="line-height:2;margin-top:8px;">
		<div><span style="font-weight:bold;">检查选择区域范围，范围越大所花时间越多。</span><span style="color:red;">（列出的文件仅是说明不属于程序文件，不一定是异常文件，请自行判断）</span></div>
		<ul>
			<li style="float:left;width:130px;"><label><input type="checkbox" name="area[]" checked="checked" value="before" />前台目录</label><span id="beforeStateStr"></span></li>
			<li style="float:left;width:130px;"><label><input type="checkbox" name="area[]" checked="checked" value="admin" />后台目录</label><span id="adminStateStr"></span></li>
			<li style="float:left;width:130px;"><label><input type="checkbox" name="area[]" checked="checked" value="Data" />数据库目录</label><span id="DataStateStr"></span></li>
			<li style="float:left;width:130px;"><label><input type="checkbox" name="area[]" checked="checked" value="Data_backup" />数据库备份目录</label><span id="Data_backupStateStr"></span></li>
			<li style="float:left;width:130px;"><label><input type="checkbox" name="area[]" checked="checked" value="cache" />cache/</label><span id="cacheStateStr"></span></li>
			<li style="float:left;width:130px;"><label><input type="checkbox" name="area[]" checked="checked" value="go" />go/</label><span id="goStateStr"></span></li>
			<li style="float:left;width:130px;"><label><input type="checkbox" name="area[]" checked="checked" value="goods" />goods/</label><span id="goodsStateStr"></span></li>
			<li style="float:left;width:130px;"><label><input type="checkbox" name="area[]" checked="checked" value="inc" />inc/</label><span id="incStateStr"></span></li>
			<li style="float:left;width:130px;"><label><input type="checkbox" name="area[]" checked="checked" value="inc_img" />inc_img/</label><span id="inc_imgStateStr"></span></li>
			<li style="float:left;width:130px;"><label><input type="checkbox" name="area[]" checked="checked" value="js" />js/</label><span id="jsStateStr"></span></li>
			<li style="float:left;width:130px;"><label><input type="checkbox" name="area[]" checked="checked" value="news" />news/</label><span id="newsStateStr"></span></li>
			<li style="float:left;width:130px;"><label><input type="checkbox" name="area[]" checked="checked" value="pay" />pay/</label><span id="payStateStr"></span></li>
			<li style="float:left;width:130px;"><label><input type="checkbox" name="area[]" checked="checked" value="pluDef" />pluDef/</label><span id="pluDefStateStr"></span></li>
			<li style="float:left;width:130px;"><label><input type="checkbox" name="area[]" checked="checked" value="plugin" />plugin/</label><span id="pluginStateStr"></span></li>
			<li style="float:left;width:130px;"><label><input type="checkbox" name="area[]" checked="checked" value="smarty" />smarty/</label><span id="smartyStateStr"></span></li>
			<li style="float:left;width:130px;"><label><input type="checkbox" name="area[]" checked="checked" value="temp" />temp/</label><span id="tempStateStr"></span></li>
			<li style="float:left;width:130px;"><label><input type="checkbox" name="area[]" checked="checked" value="template" />template/</label><span id="templateStateStr"></span></li>
			<li style="float:left;width:130px;"><label><input type="checkbox" name="area[]" checked="checked" value="tools" />tools/</label><span id="toolsStateStr"></span></li>
			<li style="float:left;width:130px;"><label><input type="checkbox" name="area[]" checked="checked" value="upFiles" />upFiles/</label><span id="upFilesStateStr"></span></li>
			<li style="float:left;width:130px;"><label><input type="checkbox" name="area[]" checked="checked" value="wap" />wap/</label><span id="wapStateStr"></span></li>
			<li style="float:left;width:130px;"><label><input type="checkbox" name="area[]" checked="checked" value="message" />message/</label><span id="messageStateStr"></span></li>
			<li style="float:left;width:130px;"><label><input type="checkbox" name="area[]" checked="checked" value="web_config" />web_config/</label><span id="web_configStateStr"></span></li>
		</ul>
		</div>
		<div class="clr"></div>
		<div id="beforeErrList" style="line-height:1.4;"></div>
		<div id="adminErrList" style="line-height:1.4;"></div>
		<div id="DataErrList" style="line-height:1.4;"></div>
		<div id="Data_backupErrList" style="line-height:1.4;"></div>
		<div id="cacheErrList" style="line-height:1.4;"></div>
		<div id="goErrList" style="line-height:1.4;"></div>
		<div id="goodsErrList" style="line-height:1.4;"></div>
		<div id="incErrList" style="line-height:1.4;"></div>
		<div id="inc_imgErrList" style="line-height:1.4;"></div>
		<div id="inc_tempErrList" style="line-height:1.4;"></div>
		<div id="jsErrList" style="line-height:1.4;"></div>
		<div id="newsErrList" style="line-height:1.4;"></div>
		<div id="payErrList" style="line-height:1.4;"></div>
		<div id="pluDefErrList" style="line-height:1.4;"></div>
		<div id="pluginErrList" style="line-height:1.4;"></div>
		<div id="smartyErrList" style="line-height:1.4;"></div>
		<div id="tempErrList" style="line-height:1.4;"></div>
		<div id="templateErrList" style="line-height:1.4;"></div>
		<div id="toolsErrList" style="line-height:1.4;"></div>
		<div id="upFilesErrList" style="line-height:1.4;"></div>
		<div id="wapErrList" style="line-height:1.4;"></div>
		<div id="messageErrList" style="line-height:1.4;"></div>
		<div id="web_configErrList" style="line-height:1.4;"></div>
		');
	$skin->TableBottom();

	echo('
	<br />
	');

	$skin->TableTop('share_rev.gif','','upFiles/目录图片木马检查');
		echo('
		<div><input type="button" value="检查" onclick="CheckUpFilesDir();" /></div>
		<div id="upFilesErrImgList" style="line-height:1.4;"></div>
		<div style="color:blue;margin:5px 0 5px 0;">（如果检测出疑似木马图片很多，可能部分不是木马，删除时会再次检测，不是木马图片会提示不允许删除）</div>
		');
	$skin->TableBottom();

}



// 获取数据库结构
function db(){
	global $DB,$skin,$mudi,$sysAdminArr,$dbName;

	$dbStr = '';
	$infoStr = '';
	$sqlStr = '';
	$judRevSql = false;
	$tableCount = 0;
	$tableNum = 0;
	$tabPrefLen = strlen(OT_dbPref);

	if (OT_Database == 'mysql'){
		$sizeArr = array();
		$sizeexe = $DB->query("select TABLE_NAME, concat(truncate(data_length/1024/1024,2),'') as data_size,
			concat(truncate(data_free/1024/1024,2),'') AS data_free,
			concat(truncate(index_length/1024/1024,2),'') as index_size
			from information_schema.tables where TABLE_SCHEMA = '". $dbName ."'
			group by TABLE_NAME
			order by data_length desc;");
			while ($row = $sizeexe->fetch()){
				$sizeArr[$row['TABLE_NAME']] = array('data_size'=>$row['data_size'], 'index_size'=>$row['index_size'], 'data_free'=>$row['data_free']);
			}
		unset($sizeexe);

		$tabDataSize = $tabIndexSize = $tabFreeSize = 0;

		$tabexe = $DB->query("select TABLE_NAME from information_schema.tables where TABLE_SCHEMA='". $dbName ."'");
		while ($row = $tabexe->fetch()){
			$tabName = $row['TABLE_NAME'];
			if (strcasecmp(substr($tabName,0,$tabPrefLen), OT_dbPref) == 0){
				if (strlen($dbStr) > 0){ $dbStr .= PHP_EOL .'【'. $tabName .'】'. PHP_EOL; }else{ $dbStr .= '【'. $tabName .'】'. PHP_EOL; }
				$tableNum ++;

				$fieldexe = $DB->query("select COLUMN_NAME from information_schema.COLUMNS where TABLE_SCHEMA='". $dbName ."' and TABLE_NAME='". $tabName ."' order by ORDINAL_POSITION ASC");
				$fieldRow = $fieldexe->fetchAll();
				//print_r($fieldRow);die();
				$fieldNum = count($fieldRow);
				for ($i=0; $i<$fieldNum; $i++){
					$dbStr .= '['. $fieldRow[$i]['COLUMN_NAME'] .']';
				}
				unset($fieldRow,$fieldexe);

				if (! isset($sizeArr[$tabName])){
					$sizeArr[$tabName] = array('data_size'=>'', 'index_size'=>'', 'data_free'=>'');
				}
				if ($judRevSql){
					$sqlStr .= '$DB->query("ALTER  TABLE '. str_replace('ot_', '". $prefl ."', strtolower($tabName)) .' RENAME TO '. str_replace('OT_', '". OT_dbPref ."', $tabName) .'");<br />';
				}
				$infoStr .= '
				<tr>
					<td align="center"><input type="checkbox" name="selDataID[]" value="'. $tabName .'" /></td>
					<td align="center">'. $tableNum .'</td>
					<td align="left" style="padding-left:15px;">'. $tabName .'</td>
					<td align="center">'. $fieldNum .'</td>
					<td align="center">'. $DB->GetOne('select count(1) from '. $tabName .'') .'</td>
					<td align="right" style="padding-right:10px;">'. $sizeArr[$tabName]['data_size'] .' MB</td>
					<td align="right" style="padding-right:10px;">'. $sizeArr[$tabName]['index_size'] .' MB</td>
					<td align="right" style="padding-right:10px;">'. $sizeArr[$tabName]['data_free'] .' MB</td>
				</tr>
				';
				$tabDataSize += OT::ToFloat($sizeArr[$tabName]['data_size']);
				$tabIndexSize += OT::ToFloat($sizeArr[$tabName]['index_size']);
				$tabFreeSize += OT::ToFloat($sizeArr[$tabName]['data_free']);
			}
		}
		$infoStr .= '
		<tr>
			<td align="center"></td>
			<td align="center"></td>
			<td align="left"></td>
			<td align="center"></td>
			<td align="center">总：'. ($tabDataSize + $tabIndexSize + $tabFreeSize) .' MB</td>
			<td align="right" style="padding-right:10px;">'. $tabDataSize .' MB</td>
			<td align="right" style="padding-right:10px;">'. $tabIndexSize .' MB</td>
			<td align="right" style="padding-right:10px;">'. $tabFreeSize .' MB</td>
		</tr>
		';

	}elseif (OT_Database == 'sqlite'){
		$tabexe = $DB->query("select name,sql from sqlite_master where type='table' order by name ASC"); // select name from sqlite_sequence ORDER BY name ASC
		while ($row = $tabexe->fetch()){
			$tabName = $row['name'];
			if (substr($tabName,0,$tabPrefLen) == OT_dbPref){
				$dbStr .= '【'. $tabName .'】';
				$tableNum ++;

				$fieldexe = $DB->query('PRAGMA table_info('. $tabName .')'); // SHOW COLUMNS FROM
				$fieldRow = $fieldexe->fetchAll();
				//print_r($fieldRow);die();
				$fieldNum = count($fieldRow);
				for ($i=0; $i<$fieldNum; $i++){
					$dbStr .= '['. $fieldRow[$i]['name'] .']';
				}

				unset($fieldRow,$fieldexe);

				$infoStr .= '
				<tr>
					<td align="center"><input type="checkbox" name="selDataID[]" value="'. $tabName .'" /></td>
					<td align="center">'. $tableNum .'</td>
					<td align="left" style="padding-left:15px;">'. $tabName .'</td>
					<td align="center">'. $fieldNum .'</td>
					<td align="center">'. $DB->GetOne('select count(1) from '. $tabName .'') .'</td>
					<td align="center"></td>
					<td align="center"></td>
					<td align="center"></td>
				</tr>
				';
			}
		}

	}else{
		$infoStr = '数据库类型错误';
	}

	if ($judRevSql){ echo($sqlStr); }
	$skin->TableTop('share_rev.gif','','数据库结构数据');
		echo('
		<div><input type="button" value="复制内容" onclick=\'ValueToCopy("dbInfo");\' />，然后发给网钛客服。</div>
		<textarea id="dbInfo" name="dbInfo" style="width:750px;height:150px;">'. $dbStr .'</textarea>
		');
	$skin->TableBottom();

	echo('
	<br />

	<form id="listForm" name="listForm" method="post" action="sysCheckFile_deal.php?mudi=db" onsubmit="return CheckListForm()">
	<script language="javascript" type="text/javascript">document.write (\'<input type="hidden" name="backURL" value="\'+ document.location.href +\'" />\')</script>
	<input type="hidden" id="mode" name="mode" value="" />
	');

	$skin->TableTop2('share_list.gif','','数据库基本信息');
	$skin->TableItemTitle('4%,5%,31%,12%,12%,12%,12%,12%','<input type="checkbox" id="selAll" name="selAll" onclick="CheckBoxAll()" />,序号,数据表名,字段数,记录数,数据大小,索引大小,碎片空间');
		echo('
		<tbody class="tabBody padd5td">
		'. $infoStr .'
		</tbody>
		<tr class="tabColorB padd5">
			<td align="left" colspan="20">
				<input type="button" value="全选" onclick="AllSelBox()" class="form_button1" />
				<input type="button" value="反选" onclick="RevSelBox()" class="form_button1" />
				&ensp;
				<input type="button" value="检查表" onclick="CheckDbForm(\'check\')" />
				&ensp;
				<input type="button" value="优化表" onclick="CheckDbForm(\'optimize\')" />
				&ensp;
				<input type="button" value="修复表" onclick="CheckDbForm(\'repair\')" />
				&ensp;
				<input type="button" value="分析表" onclick="CheckDbForm(\'analyze\')" />
			</td>
		</tr>
	</table>
	</form>
	');

}



// SQL语句调试
function sql(){
	global $DB,$skin,$mudi;

	$skin->TableTop('share_rev.gif','','SQL语句调试');
		echo('
		<form id="dealForm" name="dealForm" method="post" action="sysCheckFile_deal.php?mudi='. $mudi .'" onsubmit="return CheckSqlForm()">
		<script language="javascript" type="text/javascript">document.write(\'<input type="hidden" name="backURL" value="\'+ document.location.href +\'">\')</script>
		<input type="hidden" id="pwdMode" name="pwdMode" value="" />
		<input type="hidden" id="pwdKey" name="pwdKey" value="" />
		<input type="hidden" id="pwdEnc" name="pwdEnc" value="" />
		<textarea id="sqlContent" name="sqlContent" style="width:750px;height:250px;"></textarea>
		<div style="padding:5px;color:red;">
			登录密码：<input type="password" style="width:210px" id="userpwd" name="userpwd" maxlength="50"/>
			&ensp;（为了安全，请输入登录密码以确保是本人操作）
		</div>
		<div style="padding-top:5px;"><input type="submit" value="立即执行" style="font-size:14px;padding:8px 15px;" /></div>
		</form>
		<div style="padding:15px 8px;font-size:14px;">
			<b>快捷指令：<b>
			◆<a href="sysCheckFile_deal.php?mudi=sqlMore&type=infoTypeClear" style="color:blue;">栏目ID重置为1</a>
			◆<a href="sysCheckFile_deal.php?mudi=sqlMore&type=infoClear" style="color:blue;">文章ID重置为1</a>
		</div>
		');
	$skin->TableBottom();

}



// 可选文件下载
function fileWeb(){
	global $DB,$skin;

	echo('
	<div style="padding:0 6px 6px 6px;color:blue;">
		zip 扩展（解压功能）：'. (extension_loaded('zip') ? '<span style="color:green;">支持</span>' : '<span style="color:red;">不支持，无法使用第二种操作</span>') .'
	</div>
	');

	$skin->TableTop2('share_list.gif','','可选文件列表');
	$skin->TableItemTitle('4%,8%,22%,6%,31%,21%,8%','序号,文件名称,功能描述,大小,ZIP存放位置,解压位置,操作');

	echo('
	<tbody class="tabBody padd3td">
	<tr>
		<td align="center">1</td>
		<td align="center">IP库</td>
		<td align="left">留言、评论、后台登录、多功能表单，IP地址转为地址信息</td>
		<td align="right">4.65 MB</td>
		<td align="left">
			<div style="float:right;">
				<a href="sysCheckFile_deal.php?mudi=optionFile&mode=down&file=ip" class="font3_2">[下载]</a>'.
				'<a href="sysCheckFile_deal.php?mudi=optionFile&mode=jieya&file=ip" class="font3_2">[解压]</a>'.
				'<a href="sysCheckFile_deal.php?mudi=optionFile&mode=del&file=ip" class="font3_2">[删除]</a>
			</div>
			upFiles/download/ip.zip
			&ensp;'. File::IsExists(OT_ROOT .'upFiles/download/ip.zip','cn') .'
		</td>
		<td align="left">tools/ip.dat&ensp;'. File::IsExists(OT_ROOT .'tools/ip.dat','cn') .'</td>
		<td align="center">
			<a href="http://d.otcms.com/phpExt/ip.zip" class="font1_2" target="_blank">下载到本地</a>
		</td>
	</tr>
	<tr class="tabColorTr">
		<td align="center">2</td>
		<td align="center">宋体字库</td>
		<td align="left">linux系统下使用文字水印有中文的需要加载该字库才能用，不然文字水印不能含有中文</td>
		<td align="right">7.53 MB</td>
		<td align="left">
			<div style="float:right;">
				<a href="sysCheckFile_deal.php?mudi=optionFile&mode=down&file=simsun" class="font3_2">[下载]</a>'.
				'<a href="sysCheckFile_deal.php?mudi=optionFile&mode=jieya&file=simsun" class="font3_2">[解压]</a>'.
				'<a href="sysCheckFile_deal.php?mudi=optionFile&mode=del&file=simsun" class="font3_2">[删除]</a>
			</div>
			upFiles/download/simsun.zip
			&ensp;'. File::IsExists(OT_ROOT .'upFiles/download/simsun.zip','cn') .'
		</td>
		<td align="left">tools/simsun.ttc&ensp;'. File::IsExists(OT_ROOT .'tools/simsun.ttc','cn') .'</td>
		<td align="center">
			<a href="http://d.otcms.com/phpExt/simsun.zip" class="font1_2" target="_blank">下载到本地</a>
		</td>
	</tr>
	<tr>
		<td align="center">3</td>
		<td align="center">微软雅黑字库</td>
		<td align="left">同上，与宋体二选一，微软雅黑和宋体使用率都很高，根据自己喜好选择</td>
		<td align="right">11.35 MB</td>
		<td align="left">
			<div style="float:right;">
				<a href="sysCheckFile_deal.php?mudi=optionFile&mode=down&file=yahei" class="font3_2">[下载]</a>'.
				'<a href="sysCheckFile_deal.php?mudi=optionFile&mode=jieya&file=yahei" class="font3_2">[解压]</a>'.
				'<a href="sysCheckFile_deal.php?mudi=optionFile&mode=del&file=yahei" class="font3_2">[删除]</a>
			</div>
			upFiles/download/yahei.zip
			&ensp;'. File::IsExists(OT_ROOT .'upFiles/download/yahei.zip','cn') .'
		</td>
		<td align="left">tools/simsun.ttc&ensp;'. File::IsExists(OT_ROOT .'tools/simsun.ttc','cn') .'</td>
		<td align="center">
			<a href="http://d.otcms.com/phpExt/yahei.zip" class="font1_2" target="_blank">下载到本地</a>
		</td>
	</tr>
	<tr class="tabColorTr">
		<td align="center">4</td>
		<td align="center">分词字库</td>
		<td align="left">使用分词获取关键词需要用到该字库</td>
		<td align="right">5.80 MB</td>
		<td align="left">
			<div style="float:right;">
				<a href="sysCheckFile_deal.php?mudi=optionFile&mode=down&file=pscws4" class="font3_2">[下载]</a>'.
				'<a href="sysCheckFile_deal.php?mudi=optionFile&mode=jieya&file=pscws4" class="font3_2">[解压]</a>'.
				'<a href="sysCheckFile_deal.php?mudi=optionFile&mode=del&file=pscws4" class="font3_2">[删除]</a>
			</div>
			upFiles/download/pscws4.zip
			&ensp;'. File::IsExists(OT_ROOT .'upFiles/download/pscws4.zip','cn') .'
		</td>
		<td align="left">tools/pscws4/etc/dict.utf8.xdb&ensp;'. File::IsExists(OT_ROOT .'tools/pscws4/etc/dict.utf8.xdb','cn') .'</td>
		<td align="center">
			<a href="http://d.otcms.com/phpExt/pscws4.zip" class="font1_2" target="_blank">下载到本地</a>
		</td>
	</tr>
	<tr>
		<td align="center">5</td>
		<td align="center">视频转换工具</td>
		<td align="left">视频音乐播放器 插件用，用于转换为支持边播边加载的MP4文件</td>
		<td align="right">28.52 MB</td>
		<td align="left">&ensp;</td>
		<td align="left">&ensp;</td>
		<td align="center">
			<a href="http://d.otcms.com/app/ViedoToMp4.rar" class="font1_2" target="_blank">下载到本地</a>
		</td>
	</tr>
	</tbody>
	</table>
	<div style="padding:6px;font-size:14px;color:red;line-height:1.4;">
		提醒：使用可选文件操作有2种。
		第一种点击【下载到本地】，把文件下载到本地，解压后通过FTP把文件上传到网站的【解压位置】即可。
		第二种点击【ZIP存放位置】里的【下载】，提示下载成功，点击【解压】，提示解压成功即可。
		由于第二种要求空间支持下载大文件，有写入权限和有开启zip扩展，所以不一定您空间能支持，如果第二种操作不成功，请用第一种。
	</div>
	');
}



// 无用旧文件删除
function del(){
	global $DB,$skin,$mudi,$dataType,$dataTypeCN,$sysAdminArr;

	$skin->TableTop2('share_list.gif','','无用旧文件删除');
	$skin->TableItemTitle('20%,65%,15%','路径,备注,操作');

	echo('
	<tbody class="tabBody padd5" style="line-height:1.6;">
	');

	$jud_classBackupMySql	= file_exists(OT_adminROOT .'inc/classBackupMySql.php');
	$jud_classSaveImg		= file_exists(OT_adminROOT .'inc/classSaveImg.php');
	$jud_classWebHtml		= file_exists(OT_adminROOT .'inc/classWebHtml.php');
	$jud_jquery				= file_exists(OT_adminROOT .'js/inc/jquery-1.11.0.min.js');
	$jud_js_ad				= file_exists(OT_adminROOT .'js/ad.js');
	$jud_js_database		= file_exists(OT_adminROOT .'js/database.js');
	$jud_js_databaseMySQL	= file_exists(OT_adminROOT .'js/databaseMySQL.js');
	$jud_ad					= file_exists(OT_adminROOT .'ad.php');
	$jud_ad_deal			= file_exists(OT_adminROOT .'ad_deal.php');
	$jud_database			= file_exists(OT_adminROOT .'database.php');
	$jud_database_deal		= file_exists(OT_adminROOT .'database_deal.php');
	$jud_databaseMySQL		= file_exists(OT_adminROOT .'databaseMySQL.php');
	$jud_databaseMySQL_deal	= file_exists(OT_adminROOT .'databaseMySQL_deal.php');
	if ($jud_classBackupMySql || $jud_classSaveImg || $jud_classWebHtml || $jud_jquery || $jud_js_ad || $jud_js_database || $jud_js_databaseMySQL || $jud_ad || $jud_ad_deal || $jud_database || $jud_database_deal || $jud_databaseMySQL || $jud_databaseMySQL_deal){
		echo('
		<tr>
			<td align="center">后台</td>
			<td align="left">
				早期淘汰功能或现已没用的文件
				<div style="color:red;">
					'. ExistFileCN($jud_classBackupMySql) .'admin/inc/classBackupMySql.php<br />
					'. ExistFileCN($jud_classSaveImg) .'admin/inc/classSaveImg.php<br />
					'. ExistFileCN($jud_classWebHtml) .'admin/inc/classWebHtml.php<br />
					'. ExistFileCN($jud_jquery) .'admin/js/inc/jquery-1.11.0.min.js<br />
					'. ExistFileCN($jud_js_ad) .'admin/js/ad.js<br />
					'. ExistFileCN($jud_js_database) .'admin/js/database.js<br />
					'. ExistFileCN($jud_js_databaseMySQL) .'admin/js/databaseMySQL.js<br />
					'. ExistFileCN($jud_ad) .'admin/ad.php<br />
					'. ExistFileCN($jud_ad_deal) .'admin/ad_deal.php<br />
					'. ExistFileCN($jud_database) .'admin/database.php<br />
					'. ExistFileCN($jud_database_deal) .'admin/database_deal.php<br />
					'. ExistFileCN($jud_databaseMySQL) .'admin/databaseMySQL.php<br />
					'. ExistFileCN($jud_databaseMySQL_deal) .'admin/databaseMySQL_deal.php
				</div>
			</td>
			<td align="center">
				<input type="button" value="删除" style="cursor:pointer;" onclick=\'if(confirm("确定删除？")==true){DataDeal.location.href="sysCheckFile_deal.php?mudi=del&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&theme='. urlencode('前台没用文件') .'&fileType=admin"}\' />
			</td>
		</tr>
		');
	}

	$jud_configDeal		= file_exists(OT_ROOT .'configDeal.php');
	$jud_configJs		= file_exists(OT_ROOT .'configJs.php');
	$jud_usersApi		= file_exists(OT_ROOT .'usersApi.php');
	$jud_usersNews		= file_exists(OT_ROOT .'usersNews.php');
	$jud_usersWeb		= file_exists(OT_ROOT .'usersWeb.php');
	if ($jud_configDeal || $jud_configJs || $jud_usersApi || $jud_usersNews || $jud_usersWeb){
		echo('
		<tr>
			<td align="center">前台</td>
			<td align="left">
				早期淘汰功能或现已没用的文件
				<div style="color:red;">
					'. ExistFileCN($jud_configDeal) .'configDeal.php<br />
					'. ExistFileCN($jud_configJs) .'configJs.php<br />
					'. ExistFileCN($jud_usersApi) .'usersApi.php<br />
					'. ExistFileCN($jud_usersNews) .'usersNews.php<br />
					'. ExistFileCN($jud_usersWeb) .'usersWeb.php
				</div>
			</td>
			<td align="center">
				<input type="button" value="删除" style="cursor:pointer;" onclick=\'if(confirm("确定删除？")==true){DataDeal.location.href="sysCheckFile_deal.php?mudi=del&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&theme='. urlencode('前台没用文件') .'&fileType=before"}\' />
			</td>
		</tr>
		');
	}

	$jud_class_ip			= file_exists(OT_ROOT .'inc/class_ip.php');
	if ($jud_class_ip){
		echo('
		<tr>
			<td align="center">inc/</td>
			<td align="left">
				早期淘汰功能或现已没用的文件
				<div style="color:red;">
					'. ExistFileCN($jud_class_ip) .'inc/class_ip.php
				</div>
			</td>
			<td align="center">
				<input type="button" value="删除" style="cursor:pointer;" onclick=\'if(confirm("确定删除？")==true){DataDeal.location.href="sysCheckFile_deal.php?mudi=del&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&theme='. urlencode('前台没用文件') .'&fileType=inc"}\' />
			</td>
		</tr>
		');
	}

	$jud_classAppApiQQ			= file_exists(OT_ROOT .'pluDef/classAppApiQQ.php');
	$jud_classAppApiWeibo		= file_exists(OT_ROOT .'pluDef/classAppApiWeibo.php');
	$jud_classAppApiWeixin		= file_exists(OT_ROOT .'pluDef/classAppApiWeixin.php');
	$jud_classAppUserMoney		= file_exists(OT_ROOT .'pluDef/classAppUserMoney.php');
	$jud_classAppVpsApi			= file_exists(OT_ROOT .'pluDef/classAppVpsApi.php');
	$jud_classAppVpsBase		= file_exists(OT_ROOT .'pluDef/classAppVpsBase.php');
	$jud_classAppVpsXingwai		= file_exists(OT_ROOT .'pluDef/classAppVpsXingwai.php');
	if ($jud_classAppApiQQ || $jud_classAppApiWeibo || $jud_classAppApiWeixin || $jud_classAppUserMoney || $jud_classAppVpsApi || $jud_classAppVpsBase || $jud_classAppVpsXingwai){
		echo('
		<tr>
			<td align="center">pluDef/</td>
			<td align="left">
				早期淘汰功能或现已没用的文件
				<div style="color:red;">
					'. ExistFileCN($jud_classAppApiQQ) .'pluDef/classAppApiQQ.php<br />
					'. ExistFileCN($jud_classAppApiWeibo) .'pluDef/classAppApiWeibo.php<br />
					'. ExistFileCN($jud_classAppApiWeixin) .'pluDef/classAppApiWeixin.php<br />
					'. ExistFileCN($jud_classAppUserMoney) .'pluDef/classAppUserMoney.php<br />
					'. ExistFileCN($jud_classAppVpsApi) .'pluDef/classAppVpsApi.php<br />
					'. ExistFileCN($jud_classAppVpsBase) .'pluDef/classAppVpsBase.php<br />
					'. ExistFileCN($jud_classAppVpsXingwai) .'pluDef/classAppVpsXingwai.php
				</div>
			</td>
			<td align="center">
				<input type="button" value="删除" style="cursor:pointer;" onclick=\'if(confirm("确定删除？")==true){DataDeal.location.href="sysCheckFile_deal.php?mudi=del&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&theme='. urlencode('前台没用文件') .'&fileType=pluDef"}\' />
			</td>
		</tr>
		');
	}

	$jud_classAppApiQQ			= file_exists(OT_ROOT .'plugin/classAppApiQQ.php');
	$jud_classAppApiWeibo		= file_exists(OT_ROOT .'plugin/classAppApiWeibo.php');
	$jud_classAppUserMoney		= file_exists(OT_ROOT .'plugin/classAppUserMoney.php');
	$jud_classTplBlue			= file_exists(OT_ROOT .'plugin/classTplBlue.php');
	if ($jud_classAppApiQQ || $jud_classAppApiWeibo || $jud_classAppUserMoney || $jud_classTplBlue){
		echo('
		<tr>
			<td align="center">plugin/</td>
			<td align="left">
				早期淘汰功能或现已没用的文件
				<div style="color:red;">
					'. ExistFileCN($jud_classAppApiQQ) .'plugin/classAppApiQQ.php<br />
					'. ExistFileCN($jud_classAppApiWeibo) .'plugin/classAppApiWeibo.php<br />
					'. ExistFileCN($jud_classAppUserMoney) .'plugin/classAppUserMoney.php<br />
					'. ExistFileCN($jud_classTplBlue) .'plugin/classTplBlue.php
				</div>
			</td>
			<td align="center">
				<input type="button" value="删除" style="cursor:pointer;" onclick=\'if(confirm("确定删除？")==true){DataDeal.location.href="sysCheckFile_deal.php?mudi=del&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&theme='. urlencode('前台没用文件') .'&fileType=plugin"}\' />
			</td>
		</tr>
		');
	}

	$jud_plugins1			= file_exists(OT_ROOT .'smarty/plugins/shared.mb_wordwrap.php');
	$jud_sysplugins1		= file_exists(OT_ROOT .'smarty/sysplugins/smarty_internal_extension_clear.php');
	$jud_sysplugins2		= file_exists(OT_ROOT .'smarty/sysplugins/smarty_internal_extension_codeframe.php');
	$jud_sysplugins3		= file_exists(OT_ROOT .'smarty/sysplugins/smarty_internal_extension_config.php');
	$jud_sysplugins4		= file_exists(OT_ROOT .'smarty/sysplugins/smarty_internal_extension_defaulttemplatehandler.php');
	$jud_sysplugins5		= file_exists(OT_ROOT .'smarty/sysplugins/smarty_internal_filter_handler.php');
	$jud_sysplugins6		= file_exists(OT_ROOT .'smarty/sysplugins/smarty_internal_function_call_handler.php');
	$jud_sysplugins7		= file_exists(OT_ROOT .'smarty/sysplugins/smarty_internal_get_include_path.php');
	$jud_sysplugins8		= file_exists(OT_ROOT .'smarty/sysplugins/smarty_internal_utility.php');
	$jud_sysplugins9		= file_exists(OT_ROOT .'smarty/sysplugins/smarty_internal_write_file.php');
	if ($jud_plugins1 || $jud_sysplugins1 || $jud_sysplugins2 || $jud_sysplugins3 || $jud_sysplugins4 || $jud_sysplugins5 || $jud_sysplugins6 || $jud_sysplugins7 || $jud_sysplugins8 || $jud_sysplugins9){
		echo('
		<tr>
			<td align="center">smarty/</td>
			<td align="left">
				早期淘汰功能或现已没用的文件
				<div style="color:red;">
					'. ExistFileCN($jud_plugins1) .'smarty/plugins/shared.mb_wordwrap.php<br />
					'. ExistFileCN($jud_sysplugins1) .'smarty/sysplugins/smarty_internal_extension_clear.php<br />
					'. ExistFileCN($jud_sysplugins2) .'smarty/sysplugins/smarty_internal_extension_codeframe.php<br />
					'. ExistFileCN($jud_sysplugins3) .'smarty/sysplugins/smarty_internal_extension_config.php<br />
					'. ExistFileCN($jud_sysplugins4) .'smarty/sysplugins/smarty_internal_extension_defaulttemplatehandler.php<br />
					'. ExistFileCN($jud_sysplugins5) .'smarty/sysplugins/smarty_internal_filter_handler.php<br />
					'. ExistFileCN($jud_sysplugins6) .'smarty/sysplugins/smarty_internal_function_call_handler.php<br />
					'. ExistFileCN($jud_sysplugins7) .'smarty/sysplugins/smarty_internal_get_include_path.php<br />
					'. ExistFileCN($jud_sysplugins8) .'smarty/sysplugins/smarty_internal_utility.php<br />
					'. ExistFileCN($jud_sysplugins9) .'smarty/sysplugins/smarty_internal_write_file.php
				</div>
			</td>
			<td align="center">
				<input type="button" value="删除" style="cursor:pointer;" onclick=\'if(confirm("确定删除？")==true){DataDeal.location.href="sysCheckFile_deal.php?mudi=del&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&theme='. urlencode('前台没用文件') .'&fileType=smarty"}\' />
			</td>
		</tr>
		');
	}

	$jud_blog_bbs			= file_exists(OT_ROOT .'template/def_blog/bbs.html');
	$jud_blog_sitemap		= file_exists(OT_ROOT .'template/def_blog/sitemap.html');
	$jud_blog_usersCenter	= file_exists(OT_ROOT .'template/def_blog/usersCenter.html');
	$jud_blue_sitemap		= file_exists(OT_ROOT .'template/def_blue/sitemap.html');
	$jud_blue_usersCenter	= file_exists(OT_ROOT .'template/def_blue/usersCenter.html');
	if ($jud_blog_bbs || $jud_blog_sitemap || $jud_blog_usersCenter || $jud_blue_sitemap || $jud_blue_usersCenter){
		echo('
		<tr>
			<td align="center">template/</td>
			<td align="left">
				早期淘汰功能或现已没用的文件
				<div style="color:red;">
					'. ExistFileCN($jud_blog_bbs) .'template/def_blog/bbs.html<br />
					'. ExistFileCN($jud_blog_sitemap) .'template/def_blog/sitemap.html<br />
					'. ExistFileCN($jud_blog_usersCenter) .'template/def_blog/usersCenter.html<br />
					'. ExistFileCN($jud_blue_sitemap) .'template/def_blue/sitemap.html<br />
					'. ExistFileCN($jud_blue_usersCenter) .'template/def_blue/usersCenter.html
				</div>
			</td>
			<td align="center">
				<input type="button" value="删除" style="cursor:pointer;" onclick=\'if(confirm("确定删除？")==true){DataDeal.location.href="sysCheckFile_deal.php?mudi=del&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&theme='. urlencode('前台没用文件') .'&fileType=template"}\' />
			</td>
		</tr>
		');
	}

	$jud_CuPlayer1			= file_exists(OT_ROOT .'tools/CuPlayer/CuSunV2set.xml');
	$jud_kindeditor1		= file_exists(OT_ROOT .'tools/kindeditor/');
	if ($jud_CuPlayer1 || $jud_kindeditor1){
		echo('
		<tr>
			<td align="center">tools/</td>
			<td align="left">
				早期淘汰功能或现已没用的文件
				<div style="color:red;">
					'. ExistFileCN($jud_CuPlayer1) .'tools/CuPlayer/CuSunV2set.xml<br />
					'. ExistFileCN($jud_kindeditor1) .'tools/kindeditor/
				</div>
			</td>
			<td align="center">
				<input type="button" value="删除" style="cursor:pointer;" onclick=\'if(confirm("确定删除？")==true){DataDeal.location.href="sysCheckFile_deal.php?mudi=del&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&theme='. urlencode('前台没用文件') .'&fileType=tools"}\' />
			</td>
		</tr>
		');
	}

	$jud_wap_share		= file_exists(OT_ROOT .'wap/css/share.css');
	$jud_wap_url		= file_exists(OT_ROOT .'wap/inc/classWapUrl.php');
	$jud_wap_js1		= file_exists(OT_ROOT .'wap/js/webIndex.js');
	$jud_wap_js2		= file_exists(OT_ROOT .'wap/js/webMessage.js');
	$jud_wap_js3		= file_exists(OT_ROOT .'wap/js/webNewsShow.js');
	$jud_wap_js4		= file_exists(OT_ROOT .'wap/js/webTop.js');
	$jud_wap_js5		= file_exists(OT_ROOT .'wap/js/webUsers.js');
	$jud_wap_js6		= file_exists(OT_ROOT .'wap/js/webUsersCenter.js');
	$jud_wap_form		= file_exists(OT_ROOT .'wap/form_deal.php');
	$jud_wap_usersNews	= file_exists(OT_ROOT .'wap/usersNews.php');
	if ($jud_wap_share || $jud_wap_url || $jud_wap_js1 || $jud_wap_js2 || $jud_wap_js3 || $jud_wap_js4 || $jud_wap_js5 || $jud_wap_js6 || $jud_wap_form || $jud_wap_usersNews){
		echo('
		<tr>
			<td align="center">wap/</td>
			<td align="left">
				早期淘汰功能或现已没用的文件
				<div style="color:red;">
					'. ExistFileCN($jud_wap_share) .'wap/css/share.css<br />
					'. ExistFileCN($jud_wap_url) .'wap/inc/classWapUrl.php<br />
					'. ExistFileCN($jud_wap_js1) .'wap/js/webIndex.js<br />
					'. ExistFileCN($jud_wap_js2) .'wap/js/webMessage.js<br />
					'. ExistFileCN($jud_wap_js3) .'wap/js/webNewsShow.js<br />
					'. ExistFileCN($jud_wap_js4) .'wap/js/webTop.js<br />
					'. ExistFileCN($jud_wap_js5) .'wap/js/webUsers.js<br />
					'. ExistFileCN($jud_wap_js6) .'wap/js/webUsersCenter.js<br />
					'. ExistFileCN($jud_wap_form) .'wap/form_deal.php<br />
					'. ExistFileCN($jud_wap_usersNews) .'wap/usersNews.php
				</div>
			</td>
			<td align="center">
				<input type="button" value="删除" style="cursor:pointer;" onclick=\'if(confirm("确定删除？")==true){DataDeal.location.href="sysCheckFile_deal.php?mudi=del&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&theme='. urlencode('前台没用文件') .'&fileType=wap"}\' />
			</td>
		</tr>
		');
	}

	echo('
	</tbody>
	</table>
	<div style="padding:6px;color:blue;">提醒：如果出现删除失败或删除出错，可直接手动删除。</div>
	');

}



function ExistFileCN($existJud){
	if ($existJud){ return '<span style="color:blue;">[存在]</span>'; }else{ return '<span style="color:red;">[已删除]</span>'; }
}


function CheckSoftVerList($remReqUrl, $adminDirName, $noReadImg, &$number, &$postDataArr){
	global $sysAdminArr;
//	$softVerFileList = OTauthWeb('sysCheckFile',$remReqUrl, array());
	$softGetUrl = ReqUrl::SelUpdateUrl($sysAdminArr['SA_updateUrlMode']);
	$softVerFileList= ReqUrl::UseAuto($sysAdminArr['SA_getUrlMode'], 'GET', $softGetUrl . $remReqUrl, 'UTF-8', array(), 'note');

	$isData = false;
	if (substr($softVerFileList,0,1) == '1'){
		$isData = true;
		$softVerFileList = str_replace('	admin/', '	'. $adminDirName .'/', $softVerFileList);
		$fileListArr = explode(PHP_EOL, $softVerFileList);
		//if (count($fileListArr) <= 2){ $fileListArr = array('0	'. $softVerFileList .'<input type="button" value="刷新" onclick="document.location.reload();" />		0'); }
	}else{
		$fileListArr = array('0	系统无法访问数据'. $remReqUrl .'<input type="button" value="刷新" onclick="document.location.reload();" />		0');
	}

	foreach ($fileListArr as $fileInfo){
		$fileInfoArr = explode('	', str_replace(array("\r","\n"),'',$fileInfo) .'				');
		if ($noReadImg == 1 && strpos('|.gif|.jpg|.png|.bmp|jpeg|','|'. substr($fileInfoArr[1],-4) .'|') !== false){
		
		}else{
			$number ++;
			if ($number % 2 == 0){ $bgcolor='class="tabColorTr"'; }else{ $bgcolor=''; }
			if ($isData){
				$postDataArr[] = $fileInfoArr[1] .'	'. $fileInfoArr[2] .'	'. $fileInfoArr[3] .'	'. $fileInfoArr[4] .'	'. $fileInfoArr[5];
			}

			echo('
			<input type="hidden" id="state'. $number .'" name="state'. $number .'" value="1" />
			<input type="hidden" id="pathinfo'. $number .'" name="pathinfo'. $number .'" value="'. Str::RegExp($fileInfoArr[1],'html') .'" />
			<input type="hidden" id="sizeinfo'. $number .'" name="sizeinfo'. $number .'" value="'. $fileInfoArr[2] .'" />
			<input type="hidden" id="md5info'. $number .'" name="md5info'. $number .'" value="'. $fileInfoArr[3] .'" />
			<input type="hidden" id="sha1info'. $number .'" name="sha1info'. $number .'" value="'. $fileInfoArr[4] .'" />
			<input type="hidden" id="symd5info'. $number .'" name="symd5info'. $number .'" value="'. $fileInfoArr[5] .'" />
			<input type="hidden" id="errFile'. $number .'" name="errFile'. $number .'" value="0" />
			<tr '. $bgcolor .' id="data'. $number .'">
				<td align="center">'. $number .'</td>
				<td id="filePath'. $number .'" align="left">'. $fileInfoArr[1] .'</td>
				<td align="right">'. File::SizeUnit($fileInfoArr[2]) .'</td>
				<td align="center">'. File::GetRevTime(OT_ROOT . $fileInfoArr[1]) .'</td>
				<td align="center" id="result'. $number .'"><br /></td>
			</tr>
			');
		}
	}
}

?>
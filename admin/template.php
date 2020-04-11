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


echo('
<script language="javascript" type="text/javascript" src="js/inc/list.js?v='. OT_VERSION .'"></script>
<script language="javascript" type="text/javascript" src="js/template.js?v='. OT_VERSION .'"></script>
');

$tplPcDir		= '../template/';
$tplWapDir		= '../wap/template/';
$tplPcPath		= OT_ROOT .'template/';
$tplWapPath		= OT_ROOT .'wap/template/';


switch($mudi){
	case 'add':
		$MB->IsSecMenuRight('alertBack',207,$dataType);
		AddOrRev();
		break;

	case 'rev':
		$MB->IsSecMenuRight('alertBack',208,$dataType);
		AddOrRev();
		break;

	case 'addFile':
		$MB->IsSecMenuRight('alertBack',207,$dataType);
		AddOrRevFile();
		break;

	case 'revFile':
		$MB->IsSecMenuRight('alertBack',208,$dataType);
		AddOrRevFile();
		break;

	case 'manage':
		$MB->IsSecMenuRight('alertBack',205,$dataType);
		manage();
		break;

	default:
		die('err');
}

$skin->WebBottom();

$MB->Close();
$DB->Close();





// 新增、修改文件
function AddOrRev(){
	global $DB,$MB,$skin,$mudi,$dataType,$dataTypeCN,$systemArr;

	$backURL		= OT::GetStr('backURL');
	$dataMode		= OT::GetStr('dataMode');
	$dataModeStr	= OT::GetStr('dataModeStr');
	$dataTypeCN		= '模板';

	$dataID			= OT::GetInt('dataID');
	$sel			= OT::GetStr('sel');

	if ($dataID > 0){
		$revexe=$DB->query('select * from '. OT_dbPref .'template where TP_ID='. $dataID);
			if (! $row = $revexe->fetch()){
				JS::AlertBackEnd('无该记录！');
			}
		$TP_theme		= $row['TP_theme'];
		$TP_dir			= $row['TP_dir'];
		$TP_rank		= $row['TP_rank'];
		$TP_state		= $row['TP_state'];
		unset($revexe);

		$mudiCN='修改';

	}else{
		$theme		= OT::GetStr('theme');
		$dirName	= OT::GetStr('dirName');
			if (strpos($dirName,'..') !== false){
				JS::AlertBackEnd('禁止目录名中出现“..”字符串');
			}
		$TP_theme		= $theme;
		$TP_dir			= $dirName .'/';
		$TP_rank		= intval($DB->GetOne('select max(TP_rank) from '. OT_dbPref .'template where TP_type='. $DB->ForStr($sel))) + 10;
		$TP_state		= 1;

		$mudiCN='添加';
	}

	if ($sel == 'wap'){
		$typeCN = '手机端';
		$tplDir = 'wap/'. $TP_dir;
	}else{
		$typeCN = '电脑端';
		$tplDir = $TP_dir;
		$sel	= 'pc';
	}

	if ($mudi=='rev'){
		echo('<div onclick="history.back();" class="font2_1 padd8 pointer">&lt;&lt;&ensp;【返回上级】</div>');
	}

	echo('
	<form id="dealForm" name="dealForm" method="post" action="template_deal.php?mudi='. $mudi .'&nohrefStr=close" onsubmit="return CheckForm()">
	<input type="hidden" id="dataType" name="dataType" value="'. $dataType .'" />
	<input type="hidden" id="dataTypeCN" name="dataTypeCN" value="'. $dataTypeCN .'" />
	<input type="hidden" id="dataMode" name="dataMode" value="'. $dataMode .'" />
	<input type="hidden" id="dataModeStr" name="dataModeStr" value="'. $dataModeStr .'" />
	<input type="hidden" id="dataID" name="dataID" value="'. $dataID .'" />
	<input type="hidden" id="state" name="state" value="'. $TP_state .'" />
	<input type="hidden" id="type" name="type" value="'. $sel .'" />
	<input type="hidden" id="dir" name="dir" value="'. $TP_dir .'" />
	');
	if ($backURL != ''){
		echo('<input type="hidden" id="backURL" name="backURL" value="'. $backURL .'" />');
	}else{
		echo('<script language="javascript" type="text/javascript">document.write(\'<input type="hidden" id="backURL" name="backURL" value="\'+ document.location.href +\'" />\')</script>');
	}

	$skin->TableTop('share_'. $mudi .'.gif','',$mudiCN . $dataTypeCN);
		echo('
		<table width="98%" align="center" cellpadding="0" cellspacing="0" summary="" class="padd3">
		<tr>
			<td width="10%"></td>
			<td width="90%"></td>
		</tr>
		<tr>
			<td align="right">类型：</td>
			<td align="left">'. $typeCN .'</td>
		</tr>
		<tr>
			<td align="right">'. Skin::RedSign() .'名称：</td>
			<td align="left"><input type="text" id="theme" name="theme" size="50" style="width:250px;" value="'. $TP_theme .'" /></td>
		</tr>
		<tr>
			<td align="right">目录：</td>
			<td align="left">'. $tplDir .'</td>
		</tr>
		<tr>
			<td align="right">排序：</td>
			<td align="left"><input type="text" id="rank" name="rank" size="50" style="width:50px;" value="'. $TP_rank .'" /></td>
		</tr>
		</table>
		');
	$skin->TableBottom();

	echo('
	<table style="height:16px;" cellpadding="0" cellspacing="0" summary=""><tr><td></td></tr></table>

	<center><input type="image" src="images/button_'. $mudi .'.gif" /></center>

	</form>
	');

}



// 新增、修改文件
function AddOrRevFile(){
	global $DB,$skin,$mudi,$dataType,$dataTypeCN,$tplPcDir,$tplWapDir,$tplPcPath,$tplWapPath;

	$backURL		= OT::GetStr('backURL');
	$dataMode		= OT::GetStr('dataMode');
	$dataModeStr	= OT::GetStr('dataModeStr');

	$revExtStr	= 'htm|html|shtm|shtml|css|js|txt|xml|wml';
	$sel		= OT::GetStr('sel');
	$filePath	= OT::GetStr('filePath');
		if ($sel == 'wap'){
			$tplDir = $tplWapDir;
			$tplPath= $tplWapPath;
		}else{
			$tplDir = $tplPcDir;
			$tplPath= $tplPcPath;
		}

		if (strpos($filePath,'..') !== false){
			JS::AlertBackEnd('禁止查询路径中出现“..”字符串');
		}
		if (! File::IsExists($tplPath . $filePath)){
			JS::AlertBackEnd('找不到该文件，无法修改');
		}

	$fileName		= pathinfo($tplPath . $filePath,PATHINFO_BASENAME);
	$fileExt		= File::GetExt($tplPath . $filePath);
	$fileContent	= File::Read($tplPath . $filePath);
		if ($mudi == 'addFile'){
			$fileNameAttr = '';
			$filePath = $filePath . $fileName;
		}else{
			$fileNameAttr = 'readonly=true title="修改状态禁止修改文件名"';
		}
		if (strpos('|'. $revExtStr .'|','|'. $fileExt .'|') === false){
			JS::AlertBackEnd('当前仅允许修改 '. $revExtStr .' 这些格式。');
		}

	echo('
	<div onclick="history.back();" class="font2_1 padd8 pointer">&lt;&lt;&ensp;【返回上级】</div>
	
	<form id="dealForm" name="dealForm" method="post" action="template_deal.php?mudi='. $mudi .'&sel='. $sel .'&nohrefStr=close" onsubmit="return CheckFileForm()">
	<input type="hidden" id="dataType" name="dataType" value="'. $dataType .'" />
	<input type="hidden" id="dataTypeCN" name="dataTypeCN" value="'. $dataTypeCN .'" />
	<input type="hidden" id="dataMode" name="dataMode" value="'. $dataMode .'" />
	<input type="hidden" id="dataModeStr" name="dataModeStr" value="'. $dataModeStr .'" />
	<input type="hidden" id="filePath" name="filePath" value="'. $filePath .'" />
	');
	if ($backURL != ''){
		echo('<input type="hidden" id="backURL" name="backURL" value="'. $backURL .'" />');
	}else{
		echo('<script language="javascript" type="text/javascript">document.write(\'<input type="hidden" id="backURL" name="backURL" value="\'+ document.location.href +\'" />\')</script>');
	}

	$skin->TableTop('share_'. substr($mudi,0,3) .'.gif','',$dataTypeCN);
		echo('
		<table width="98%" align="center" cellpadding="0" cellspacing="0" summary="" class="padd3">
		<tr>
			<td width="10%"></td>
			<td width="90%"></td>
		</tr>
		<tr>
			<td align="right">文件路径：</td>
			<td align="left">'. $tplDir . $filePath .'</td>
		</tr>
		<tr>
			<td align="right">'. Skin::RedSign() .'文件名称：</td>
			<td align="left">
				<input type="text" id="fileName" name="fileName" size="50" style="width:200px;" value="'. $fileName .'" '. $fileNameAttr .' />
				&ensp;&ensp;&ensp;&ensp;
				<label><input type="checkbox" name="isBak" value="1" checked="checked" />保存时，创建一个备份文件</label>
			</td>
		</tr>
		<tr>
			<td align="right" valign="top" style="padding-top:6px;">'. Skin::RedSign() .'文件内容：</td>
			<td align="left" style="line-height:1.4;">
				<textarea id="fileContent" name="fileContent" style="width:800px;height:700px;">'. Str::MoreReplace($fileContent,'textarea') .'</textarea>
			</td>
		</tr>
		</table>
		');
	$skin->TableBottom();
             
	echo('
	<table style="height:16px;" cellpadding="0" cellspacing="0" summary=""><tr><td></td></tr></table>

	<center><input type="image" src="images/button_'. substr($mudi,0,3) .'.gif" /></center>

	</form>
	');

}



function manage(){
	global $DB,$skin,$mudi,$dataType,$dataTypeCN,$tplPcDir,$tplWapDir,$tplPcPath,$tplWapPath,$pcTplArr,$wapTplArr;

	$dirStr	= OT::GetStr('dirStr');
		if (strpos($dirStr,'..') !== false){
			JS::AlertBackEnd('禁止查询路径中出现“..”字符串');
		}
	$sel	= OT::GetStr('sel');
		if ($sel == 'wap'){
			$currTplDir = $DB->GetOne('select WAP_templateDir from '. OT_dbPref .'wap');
			$tabPcStr	= 'onclick="document.location.href=\'?mudi='. $mudi .'&sel=pc&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'\'"';
			$tabWapStr	= 'class="selected"';
			$tplDir		= $tplWapDir;
			$tplPath	= $tplWapPath;
			$defTplArr	= $wapTplArr;
		}else{
			$currTplDir = $DB->GetOne('select SYS_templateDir from '. OT_dbPref .'system');
			$tabPcStr	= 'class="selected"';
			$tabWapStr	= 'onclick="document.location.href=\'?mudi='. $mudi .'&sel=wap&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'\'"';
			$tplDir		= $tplPcDir;
			$tplPath	= $tplPcPath;
			$defTplArr	= $pcTplArr;
			$sel		= 'pc';
		}

	$templateDir	= $tplDir . $dirStr;
	$templatePath	= $tplPath . $dirStr;
	$folderArr		= File::GetDirList($templatePath);
	$folderNum		= count($folderArr);


	$hrefPartStr = '?mudi='. $mudi .'&sel='. $sel .'&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&dirStr=';
	$revPartStr = '?mudi=revFile&sel='. $sel .'&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&backURL="+ encodeURIComponent(document.location.href) +"&filePath=';

	if (strlen($dirStr) == 0){
		// $skin->TableTop('share_rev.gif','',$dataTypeCN .'管理');
		echo('
		<div class="tabMenu">
		<ul>
		   <li rel="tabPc" '. $tabPcStr .'>电脑端模板</li>
		   <li rel="tabWap" '. $tabWapStr .' '. (AppWap::Jud() ? '' : 'style="display:none;"') .'>手机端模板</li>
		</ul>
		<div style="padding:10px 0 0 0;font-size:14px;color:red;">&ensp;&ensp;&ensp;&ensp;模板各区域功能与后台菜单：<a href="http://otcms.com/news/8125.html" target="_blank" style="color:#000;font-size:14px;">http://otcms.com/news/8125.html</a></div>
		</div>

		<div class="tabMenuArea">
		');
			$tempListStr = '';
			$tempexe = $DB->query('select * from '. OT_dbPref .'template where TP_state=1 and TP_type='. $DB->ForStr($sel) .' order by TP_rank ASC');
				while ($row = $tempexe->fetch()){
					$configFilePath = $templatePath . $row['TP_dir'] .'config.xml';
					if (! File::IsExists($configFilePath)){
						echo('
						<div id="data'. $row['TP_ID'] .'" class="tempBox">
							<div class="imgBox" style="text-align:center;"><br /><br />该模板目录不存在</div>
							<b>名称：</b>'. $row['TP_theme'] .'<br />
							<b>目录：</b>'. $row['TP_dir'] .'<br />
							<b>版本：</b><br />
							<b>作者：</b><br />
							<b>排序：</b>'. $row['TP_rank'] .'<br />
							<p>
								<a href="javascript:void(0);" onclick=\'if(confirm("确定删除？")==true){DataDeal.location.href="template_deal.php?mudi=del&sel='. $sel .'&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&theme='. urlencode($row['TP_theme']) .'&dataID='. $row['TP_ID'] .'"}return false;\' class="font1_2" style="color:red;">[删除]</a>
							</p>
						</div>
						');
					}else{
						$configStr = File::Read($configFilePath);
						$tempListStr .= '|'. $row['TP_dir'] .'|';
						if ($currTplDir == $row['TP_dir']){
							$selBoxClass	= 'tempBox2';
							$selBoxUserBtn	= '<span class="font1_2" style="color:red;">应用中</span>&ensp;';
							$selBoxDelBtn	= '<a href="javascript:void(0);" class="font1_2d" onclick=\'alert("请先选择其他模板，再删除该模板。");return false;\'>[删除]</a>';
						}else{
							$selBoxClass	= '';
							$selBoxUserBtn	= '<a href="javascript:void(0);" class="font1_2" onclick=\'document.location.href="template_deal.php?mudi=change&sel='. $sel .'&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&dataID='. $row['TP_ID'] .'&backURL="+ encodeURIComponent(document.location.href);return false;\'>[应用]</a>&ensp;';
							$selBoxDelBtn	= '<a href="javascript:void(0);" class="font1_2d" onclick=\'if(confirm("确定删除？\n\n删除该记录后，请手动删除该模板目录及其文件。")==true){DataDeal.location.href="template_deal.php?mudi=del&sel='. $sel .'&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&theme='. urlencode($row['TP_theme']) .'&dataID='. $row['TP_ID'] .'"}return false;\'>[删除]</a>';
						}
						if (in_array($row['TP_dir'], $defTplArr)){
							$themeImgStr = '<img src="images/img_guan.png" alt="官方模板" title="官方模板" />';	// <span style="color:red;font-weight:bold;">[官]</span>
						}else{
							$themeImgStr = '';
						}
						echo('
						<div id="data'. $row['TP_ID'] .'" class="tempBox '. $selBoxClass .'">
							<div class="imgBox"><img src="'. $tplDir . $row['TP_dir'] .'thumb.jpg" /></div>
							<b>名称：</b>'. $row['TP_theme'] . $themeImgStr .'<br />
							<b>目录：</b>'. $row['TP_dir'] .'<br />
							<b>版本：</b>'. Str::GetMark($configStr,'<version>','</version>') .'&ensp;<a href="'. $tplDir . $row['TP_dir'] .'note.txt" target="_blank" class="font2_2">[说明]</a>&ensp;<a href="'. Str::GetMark($configStr,'<demoUrl>','</demoUrl>') .'" target="_blank" class="font2_2">[演示]</a><br />
							<b>作者：</b><a href="'. Str::GetMark($configStr,'<site>','</site>') .'" target="_blank" class="font1_2">'. Str::GetMark($configStr,'<author>','</author>') .'</a><br />
							<b>排序：</b>'. $row['TP_rank'] .'<br />
							<p>
								'. $selBoxUserBtn .'');
								if ($row['TP_ID'] == 1){
									echo('<a href="'. $hrefPartStr . $dirStr . $row['TP_dir'] .'" class="font1_2">[管理]</a>');
								}else{
									echo(''.
									'<a href="'. $hrefPartStr . $dirStr . $row['TP_dir'] .'" class="font1_2">[管理]</a>&ensp;'.
									'<a href="?mudi=rev&sel='. $sel .'&dataID='. $row['TP_ID'] .'" class="font1_2">[修改]</a>&ensp;'.
									''. $selBoxDelBtn .'');
								}
							echo('
							</p>
						</div>
						');
					}
				}
			unset($tempexe);

			foreach ($folderArr as $folder){
				$foldArr = explode(',',$folder);
				if (strpos($tempListStr,'|'. $foldArr[0] .'/|') === false){
					$configFilePath = $templatePath . $foldArr[0] .'/config.xml';
					$configStr = File::Read($configFilePath);
					$tempName = Str::GetMark($configStr,'<name>','</name>');
					echo('
					<div class="tempBox">
						<div class="imgBox"><img src="'. $tplDir . $foldArr[0] .'/thumb.jpg" /></div>
						<b>名称：</b>'. $tempName .'<br />
						<b>目录：</b>'. $foldArr[0] .'/<br />
						<b>版本：</b>'. Str::GetMark($configStr,'<version>','</version>') .'<br />
						<b>作者：</b><a href="'. Str::GetMark($configStr,'<site>','</site>') .'" target="_blank" class="font1_2">'. Str::GetMark($configStr,'<author>','</author>') .'</a><br />
						<b>排序：</b>-<br />
						<p>
							<a href="?mudi=add&sel='. $sel .'&theme='. urlencode($tempName) .'&dirName='. $foldArr[0] .'" class="font3_2">[请先激活]</a>
						</p>
					</div>
					');
				}
			}

			echo('
			<div class="tempBox pointer" onclick=\'parent.HrefToShop("appShop.php?dataMode=tpl")\'>
				<div class="imgBox" style="text-align:center;"><br /><br />插件平台-模板插件<br />还有更多模板可选择</div>
				<b>名称：</b>点击获取更多模板<br />
				<b>目录：</b><br />
				<b>版本：</b><br />
				<b>作者：</b>网钛科技<br />
				<b>排序：</b><br />
				<p><span class="font1_2" style="color:red;">[点击查看]</span></p>
			</div>

			<div class="clr"></div>
			<div style="margin-top:15px;color:red;">
				提醒：<br />
				1.添加模板只需把整个模板文件夹放到网站根目录下的template/目录下，刷新下该页面，点击[请先激活]即可使用。<br />
				2.请勿直接在系统内置模板上修改，因为后期更新会覆盖该目录文件，建议复制一份作为新模板再修改。
			</div>
		</div>
		');
		// $skin->TableBottom();

	}else{
		$fileArr		= File::GetFileList($templatePath);
		$fileNum		= count($fileArr);

		echo('<div onclick="history.back();" class="font2_1 padd8 pointer">&lt;&lt;&ensp;【返回上级】</div>');

		$skin->TableTop2('share_list.gif','',''. $dataTypeCN .'管理（当前读取路径：'. $templateDir .'）');
		$skin->TableItemTitle('5%,22%,14%,9%,16%,16%,10%','序号,文件名,类型,文件大小,创建时间,修改时间,操作');

		echo('<tbody class="tabBody padd5">');

		if ($posNum = strrpos(substr($dirStr,0,strlen($dirStr)-1),'/') !== false){
			echo('
			<tr>
				<td align="left" colspan="7"><a href="'. $hrefPartStr . substr($dirStr,0,$posNum+1) .'" class="font2_2 fontB"><img src="images/ext/prev.gif" />&ensp;回到上级</a><br /></td>
			</tr>
			');
		}

		if (strpos($templateDir,'template/def') !== false){
			echo('
			<tr style="background:#f6de7b;color:red;">
				<td align="left" colspan="7">该模板为系统内置模板，后期升级可能会覆盖该模板文件。建议复制一份作为新模板再修改，以防后期升级被覆盖了。</td>
			</tr>
			');
		}
		
		$number = 0;
		foreach ($folderArr as $folder){
			$number ++;
			if ($number % 2 == 0){ $bgcolor='class="tabColorTr"'; }else{ $bgcolor=''; }
			echo('
			<tr '. $bgcolor .'>
				<td align="center">'. $number .'</td>
				<td align="left" onclick=\'document.location.href="'. $hrefPartStr . $dirStr . $folder .'/";\' title="点击进入该目录" class="pointer">'. ExtToImg('文件夹') .'&ensp;'. $folder .'<br /></td>
				<td align="center">文件夹</td>
				<td align="right" style="padding-right:6px;"><br /></td>
				<td align="center">'. File::GetCreateTime($templatePath . $folder) .'<br /></td>
				<td align="center">'. File::GetRevTime($templatePath . $folder) .'<br /></td>
				<td align="center">
					<a href="'. $hrefPartStr . $dirStr . $folder .'/" class="font1_2">进入目录</a>
				</td>
			</tr>
			');
		}
		foreach ($fileArr as $fileName){
			$fileName2 = Str::GB2UTF($fileName);
			if ($fileName2 != 'Thumbs.db'){
				$fileExt = File::GetExt($fileName2);
				if (strlen($fileExt) == 0){ $fileExt='empty'; }
				$number ++;
				if ($number % 2 == 0){ $bgcolor='class="tabColorTr"'; }else{ $bgcolor=''; }
				if (strpos('shtml,css,js,txt,xml,wml',$fileExt) != false){
					if (strpos('|bottom.html|config.xml|index.html|list.html|message.html|news.html|news_right.html|note.txt|sitemap.html|thumb.jpg|top.html|web.html|','|'. $fileName2 .'|') !== false){
						$aOnclick	= 'document.location.href="'. $revPartStr . $dirStr . $fileName2 .'";';
						$funcStr	= '
							<img src="images/img_rev.gif" class="pointer" onclick=\'document.location.href="'. $revPartStr . $dirStr . $fileName2 .'";\' alt="修改" />&ensp;&ensp;
							<img src="images/img_del.gif" class="pointer" onclick=\'alert("模板内置文件文件，不允许删除。")\' alt="不允许删除" style="filter:gray;" />';
					}else{
						$aOnclick	= 'document.location.href="'. $revPartStr . $dirStr . $fileName2 .'";';
						$funcStr	= '
							<img src="images/img_rev.gif" class="pointer" onclick=\'document.location.href="'. $revPartStr . $dirStr . $fileName2 .'";\' alt="修改" />&ensp;&ensp;
							<img src="images/img_del.gif" class="pointer" onclick=\'if(confirm("确定删除？")==true){DataDeal.location.href="template_deal.php?mudi=delFile&sel='. $sel .'&num='. $number .'&filePath='. $dirStr . $fileName2 .'";}\' alt="删除" />';
					}
				}elseif (strpos('gif,jpg,jpeg,bmp,png',$fileExt) !== false){
					$aOnclick	= 'var a=window.open("'. $templateDir . $fileName2 .'");';
					$funcStr	= '<br />';
				}else{
					$aOnclick	= 'alert("该文件为非编辑文件，禁止编辑");';
					$funcStr	= '<br />';
				}
				echo('
				<tr id="data'. $number .'" '. $bgcolor .'>
					<td align="center">'. $number .'</td>
					<td align="left" onclick=\''. $aOnclick .'\' title="点击编辑该文件" class="pointer">'. ExtToImg($fileExt) .'&ensp;'. $fileName2 .'<br /></td>
					<td align="center">'. ExtToCN($fileName2,$fileExt) .'</td>
					<td align="right" style="padding-right:6px;">'. File::SizeUnit(filesize($templateDir . $fileName)) .'<br /></td>
					<td align="center">'. File::GetCreateTime($templateDir . $fileName) .'<br /></td>
					<td align="center">'. File::GetRevTime($templateDir . $fileName) .'<br /></td>
					<td align="center">'. $funcStr .'</td>
				</tr>
				');
			}
		}

		echo('
		</tbody>
		</table>
		<div style="padding:5px;color:red;">提醒：如果有用到页面缓存/纯静态，修改完模板文件，需要清空缓存/重新生成静态页才能看到最新效果。</div>
		');
	}

}


function ExtToImg($extStr){
	if (strpos('rar,js,css,xml,gif,jpg,mdb,txt',$extStr) !== false){
		return '<img  border=0 src="images/ext/'. $extStr .'.gif" align="absmiddle" />';
	}else{
		if ($extStr == '文件夹'){
			return '<img border=0 src="images/ext/folder.gif" align="absmiddle" />';
		}elseif (strpos('htm,html,shtml,shtm',$extStr) !== false){
			return '<img  border=0 src="images/ext/html.gif" align="absmiddle" />';
		}else{
			return '<img border=0 src="images/ext/file.gif" align="absmiddle" />';
		}
	}
}


function ExtToCN($filename,$extStr){
	if (strpos('gif,jpg,jpeg,bmp,png',$extStr) !== false){
		switch($filename){
			case 'thumb.jpg':	return '<span style="font-weight:bold;">模板缩略图</span>';
			default:			return '图片';
		}
	}elseif ($extStr == 'css'){
		return '样式';
	}elseif ($extStr == 'js'){
		return '脚本';
	}elseif ($extStr == 'txt'){
		switch($filename){
			case 'note.txt':	return '<span style="color:blue;">模板说明文档</span>';
			default:			return '文本文档';
		}
	}elseif ($extStr == 'xml'){
		switch($filename){
			case 'config.xml':	return '<span style="font-weight:bold;">模板配置文件</span>';
			default:			return 'xml文档';
		}
	}elseif ($extStr == 'wml'){
		return '手机网页';
	}elseif (strpos('htm,html,shtm,shtml',$extStr) !== false){
		switch($filename){
			case 'index.html':		return '<span style="color:red;">首页模板</span>';
			case 'top.html':		return '<span style="color:red;">页头模板</span>';
			case 'bottom.html':		return '<span style="color:red;">页尾模板</span>';
			case 'list.html':		return '<span style="color:red;">列表页模板</span>';
			case 'message.html':	return '<span style="color:red;">留言本模板</span>';
			case 'news.html':		return '<span style="color:red;">内容页模板</span>';
			case 'news_right.html':	return '<span style="color:red;">列表内容页右侧模板</span>';
			case 'web.html':		return '<span style="color:red;">单篇页模板</span>';
			case 'webFull.html':	return '<span style="color:red;">全屏单篇页模板</span>';
			case 'sitemap.html':	return '<span style="color:red;">网站地图模板</span>';
			default:				return '网页文件';
		}
	}elseif ($extStr == 'asp'){
		switch($filename){
			default:				return 'ASP脚本';
		}
	}else{
		return '其他文件';
	}
}

?>
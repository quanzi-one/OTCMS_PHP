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
<script language="javascript" type="text/javascript" src="js/softBak.js?v='. OT_VERSION .'"></script>
');


$MB->IsAdminRight('alertBack');


switch ($mudi){
	case 'backup':
		backup();
		break;

	case 'manage':
		manage();
		break;

	default:
		die('err');
}

$skin->WebBottom();

$MB->Close();
$DB->Close();





// 数据库备份
function backup(){
	global $DB,$skin,$dbName;

	echo('
	<div style="padding:6px;color:blue;">zip 扩展（解压功能）：'. (extension_loaded('zip') ? '<span style="color:green;">支持</span>' : '<span style="color:red;">不支持，无法使用该功能</span>') .'</div>
	<form action="softBak_deal.php?mudi=backup" method="post" onsubmit="return BackupForm()">
	<script language=\'javascript\' type=\'text/javascript\'>document.write("<input type=\'hidden\' name=\'backURL\' value=\'"+ document.location.href +"\' />")</script>
	');

	$adminURL	= GetUrl::CurrDir();
	$beforeURL	= GetUrl::CurrDir(1);
	$adminDirName = substr($adminURL,strlen($beforeURL),-1);

	$allSize = File::SizeUnit(File::DirSize(OT_ROOT));
	$softSize = 
		File::DirSize(OT_ROOT . $adminDirName) + 
		File::DirSize(OT_ROOT .'inc') + 
		File::DirSize(OT_ROOT .'inc_img') + 
		File::DirSize(OT_ROOT .'js') + 
		File::DirSize(OT_ROOT .'pluDef') + 
		File::DirSize(OT_ROOT .'plugin') + 
		File::DirSize(OT_ROOT .'smarty') + 
		File::DirSize(OT_ROOT .'template') + 
		File::DirSize(OT_ROOT .'tools') + 
		File::DirSize(OT_ROOT, array('html','php','ini','ico'), false) + 
		filesize(OT_ROOT .'news/index.php')
		;
		if (file_exists(OT_ROOT .'go')){
			$softSize += File::DirSize(OT_ROOT .'go');
		}
		if (file_exists(OT_ROOT .'pay')){
			$softSize += File::DirSize(OT_ROOT .'pay');
		}
		if (file_exists(OT_ROOT .'weixin')){
			$softSize += File::DirSize(OT_ROOT .'weixin');
		}
		if (file_exists(OT_ROOT .'wap')){
			$softSize += File::DirSize(OT_ROOT .'wap', array('html','php','ini','ico'), false);
			if (AppWap::Jud()){
				$softSize += File::DirSize(OT_ROOT .'wap/images');
				$softSize += File::DirSize(OT_ROOT .'wap/inc');
				$softSize += File::DirSize(OT_ROOT .'wap/js');
				$softSize += File::DirSize(OT_ROOT .'wap/skin');
				$softSize += File::DirSize(OT_ROOT .'wap/template');
				$softSize += File::DirSize(OT_ROOT .'wap/tools');
				$softSize += filesize(OT_ROOT .'wap/news/index.php');
			}
		}
	$softSize = File::SizeUnit($softSize);

	$skin->TableTop('share_other.gif','','备份类型');
		echo('
		<table width="98%" cellspacing="1" cellpadding="3" >
		<tr>
			<td><label><input type="radio" id="mode_all" name="mode" value="all" checked="checked" onclick="CheckBakType()">全部备份</label></td>
			<td>备份网站目录下所有文件（mysql数据库除外），<span style="color:red;font-weight:bold;">推荐！</span>&ensp;&ensp;<span style="color:blue;">（预计：'. $allSize .'）</span></td>
		</tr>
		<tr>
			<td><label><input type="radio" id="mode_ot" name="mode" value="soft" onclick="CheckBakType()">程序文件备份</label></td>
			<td>只备份程序文件，主要用于后期遇到同版本异常文件可以提取覆盖。&ensp;&ensp;<span style="color:blue;">（预计：'. $softSize .'）</span></td>
		</tr>
		<tr>
			<td><label><input type="radio" id="mode_diy"  name="mode" value="diy" onclick="CheckBakType()">自定义备份</label></td>
			<td>根据自行选择备份数据表</td>
		</tr>
		</table>

		<div id="showTable" style="padding-left:15px;display:none;">
			<div style="padding:6px;">
				<div style="float:left;width:185px;"><label><input type="checkbox" name="selTable[]" value="softFile" />程序文件</label></div>
				<div style="float:left;width:185px;"><label><input type="checkbox" name="selTable[]" value="dbFile" />Sqlite数据库</label></div>
				<div style="float:left;width:185px;"><label><input type="checkbox" name="selTable[]" value="htmlFile" />HTML静态页</label></div>
				<div style="float:left;width:185px;"><label><input type="checkbox" name="selTable[]" value="upFile" />上传图片和附件</label></div>
			</div>
			<div class="clr"></div>
			<div style="padding:6px;">
				<input type="button" value="全选" onclick="CheckAllSel()" />
				&ensp;
				<input type="button" value="反选" onclick="CheckRevSel()" />
			</div>
		</div>
		');
	$skin->TableBottom();

	echo('<br />');

	$prow = $DB->GetRow('select SP_bakZipPwd,SP_bakZipNote from '. OT_dbPref .'sysParam');

	$skin->TableTop('share_other.gif','','其他选项');
		echo('
		<table cellspacing="1" cellpadding="3" >
		'. (PHP_VERSION >= 5.6 ? '
			<tr>
				<td align="right">设置解压密码</td>
				<td><input type="text" name="zipPwd" size="30" style="width:150px;" value="">&ensp;&ensp;<span style="color:red;">（如不设密码请留空）</span><!-- '. $prow['SP_bakZipPwd'] .' onblur=\'RevSysParam("bakZipPwd",this.value)\' --></td>
			</tr>
			' : '') .'
		<tr>
			<td align="right" valign="top" style="padding-top:6px;">压缩包注释</td>
			<td><textarea name="zipNote" size="30" style="width:500px;height:80px;" onblur=\'RevSysParam("bakZipNote",this.value)\'>'. $prow['SP_bakZipNote'] .'</textarea>
		</tr>
		<tr>
			<td align="right">备份位置：</td>
			<td>
				<label><input type="radio" name="backupSpace" value="server" checked="checked" />备份到服务器</label>&ensp;&ensp;&ensp;&ensp;&ensp;
				<!-- <label><input type="radio" name="backupSpace" value="localpc" />备份到本地</label> -->
			</td>
		</tr>
		<!-- <tr>
			<td align="right">分卷大小：</td>
			<td>
				<input type="text" id="fileSize" name="fileSize" value="5120"style="width:60px;" > KB
				<input type="button" value="5M" onclick=\'$id("fileSize").value="5120"\' />
				<input type="button" value="8M" onclick=\'$id("fileSize").value="8192"\' />
				<input type="button" value="20M" onclick=\'$id("fileSize").value="20480"\' />
				<input type="button" value="100M" onclick=\'$id("fileSize").value="102400"\' />
				<input type="button" value="1G" onclick=\'$id("fileSize").value="1048576"\' />
				<span class="font2_2" style="color:red;">&ensp;&ensp;（当数据库大小超过分卷大小时，备份会分多个文件保存）</span>
			</td>
		</tr>
		<tr>
			<td>备份文件名</td>
			<td><input type="text" name="backupFileName" size="30" value="<?php echo(TimeDate::Get("Ymd_Hi") ."_". OT::RndNum(4)); ?>.sql"></td>
		</tr>
		<tr>
			<td>备份文件夹名</td>
			<td><input type="text" name="backupDirName" size="30" value="<?php echo(TimeDate::Get("YmdHis")); ?>"></td>
		</tr> -->
		</table>
		');
	$skin->TableBottom();

		echo('
		<div style="padding:6px;color:red;font-size:14px;line-height:1.4;">
			提醒：该功能尚在测试阶段，备份后的文件建议检查下是否完整，保险起见建议用通过FTP或其他方式再备份下。
		</div>
		<br /><center><input type="submit" class="btnBg" value="开始备份" /></center>
	</form>
	');
}



// 数据库还原
function restore(){
	global $DB,$skin;

	$skin->TableTop('share_other.gif','','数据库恢复');
		?>
		<div class="font1_1" style="line-height:1.6;">
			<b style='color:red;'>严重警告，到万不得已，再使用数据库还原，特别是非同一版本的数据库。如果是升级出问题，请先联系网钛客服：800166366 <a href="http://wpa.b.qq.com/cgi/wpa.php?ln=1&key=XzgwMDE2NjM2Nl8zNTU2NTlfODAwMTY2MzY2XzJf" target="_blank"><img src="http://asp.otcms.com/qq.png" border="0" /></a>。</b>
			<br /><br />
			<b>数据库还原方法：</b>从数据库备份目录<span class="font1_2d">(程序默认：****_backup/)</span>下找到要还原的数据库文件(.sql格式)，时间前缀相同的为同个数据库，后面part数字.sql为分卷序列号，把备份文件通过phpmyadmin或其他数据库管理工具来还原。
		</div>
		<?php
	$skin->TableBottom();
}



// 备份管理
function manage(){
	global $DB,$MB,$skin,$pageCount,$recordCount;

	$skin->TableTop2('share_list.gif','','程序/数据库备份管理');
	$skin->TableItemTitle('6%,6%,30%,11%,9%,15%,16%,7%','编号,类型,备份名称,备份类型,文件大小,数据库版本,备份时间,<!-- 下载&ensp;&ensp; -->删除');

	$pageSize	= $MB->mMbRow['MB_itemNum'];		//每页条数
	$page		= OT::GetInt('page');
	$showRow=$DB->GetLimit('select * from '. OT_dbPref .'dbBak order by DB_ID DESC',$pageSize,$page);
	if (! $showRow){
		$skin->TableNoData();
	}else{
		$recordCount=$DB->GetRowCount();
		$pageCount=ceil($recordCount/$pageSize);
		if ($page < 1 || $page > $pageCount){$page=1;}

		echo('
		<tbody class="tabBody padd3td">
		');
		$number=1+($page-1)*$pageSize;
		$rowCount = count($showRow);
		for ($i=0; $i<$rowCount; $i++){
			if ($i % 2 == 1){ $bgcolor='class="tabColorTr"'; }else{ $bgcolor=''; }

			echo('
			<tr id="data'. $showRow[$i]['DB_ID'] .'" '. $bgcolor .'>
				<td align="center">'. $number .'</td>
				<td align="center">'. TypeCN($showRow[$i]['DB_type']) .'</td>
				<td align="center">备份于'. $showRow[$i]['DB_time'] .'&ensp;（分卷 '. $showRow[$i]['DB_fileNum'] .' 个）</td>
				<td align="center">'. FileTypeCN($showRow[$i]['DB_fileType'],$showRow[$i]['DB_filePath']) .'</td>
				<td align="right">'. File::SizeUnit($showRow[$i]['DB_fileSize']) .'</td>
				<td align="center">V'. $showRow[$i]['DB_ver'] .'_'. $showRow[$i]['DB_timeStr'] .'</td>
				<td align="center">'. $showRow[$i]['DB_time'] .'</td>
				<td align="center">
					<!-- <img src="images/img_down.gif" style="cursor:pointer" onclick=\'var a=window.open("softBak_deal.php?mudi=download&nohrefStr=close&isDownload=true&dataID='. $showRow[$i]['DB_ID'] .'")\' alt="" />
					&ensp;&ensp; -->
					<img src="images/img_del.gif" style="cursor:pointer" onclick=\'if(confirm("你确定要删除？")==true){DataDeal.location.href="softBak_deal.php?mudi=del&dataID='. $showRow[$i]['DB_ID'] .'"}\' alt="" />
				</td>
			</tr>
			');
		$number ++;
		}
		echo('
		</tbody>
		');
	}
	unset($showRow);

	$skin->TableBottom2($pageCount, $pageSize, $recordCount);
}



function TypeCN($str){
	if ($str == 'db'){
		return '数据库';
	}elseif ($str == 'soft'){
		return '程序';
	}else{
		return '['. $str .']';
	}
}


function FileTypeCN($type, $path){
	switch ($type){
		case 'all':		return '全部备份';
		case 'ot':		return '网钛表备份';
		case 'soft':	return '程序文件备份';
		case 'common':	return '标准备份';
		case 'min':		return '最小备份';
		case 'diy':		return '自定义备份';
		default :
			if (strpos($path,'_all_') !== false){
				return '全部备份';
			}elseif (strpos($path,'_ot_') !== false){
				return '网钛表备份';
			}elseif (strpos($path,'_common_') !== false){
				return '标准备份';
			}elseif (strpos($path,'_min_') !== false){
				return '最小备份';
			}elseif (strpos($path,'_diy_') !== false){
				return '自定义备份';
			}else{
				return $type;
			}
			break;
	}
}
?>
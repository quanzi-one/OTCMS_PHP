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
<script language="javascript" type="text/javascript" src="js/dbBakMySQL.js?v='. OT_VERSION .'"></script>
');


$MB->IsAdminRight('alertBack');


switch ($mudi){
	case 'backup':
		backup();
		break;

	case 'compress':
		compress();
		break;

	case 'restore':
		restore();
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
	<form action="dbBakMySQL_deal.php?mudi=backup" method="post" onsubmit="return BackupForm()">
	<script language=\'javascript\' type=\'text/javascript\'>document.write("<input type=\'hidden\' name=\'backURL\' value=\'"+ document.location.href +"\' />")</script>
	');

	$skin->TableTop('share_other.gif','','备份类型');
		$allNum = $otNum = 0;
		$tabListStr = '';
		$tabPrefLen = strlen(OT_dbPref);
		$tabexe = $DB->query("SHOW TABLES");
		while ($row = $tabexe->fetch(PDO::FETCH_NUM)){
			$allNum ++;
			if (strcasecmp(substr($row[0],0,$tabPrefLen), OT_dbPref) == 0){ $otNum ++; }
			$tabListStr .= '<div style="float:left;width:185px;"><label><input type="checkbox" name="selTable[]" value="'. $row[0] .'" />'. $row[0] .'</label></div>';
		}
		unset($tabexe);

		echo('
		<table width="500" cellspacing="1" cellpadding="3" >
		<tr>
			<td><label><input type="radio" id="mode_all" name="mode" value="all" checked="checked" onclick="CheckBakType()">全部备份</label></td>
			<td>备份数据库所有表，如有其他程序表也会备份（'. $allNum .'个表）</td>
		</tr>
		<tr>
			<td><label><input type="radio" id="mode_ot" name="mode" value="ot" onclick="CheckBakType()">网钛表备份</label></td>
			<td>备份数据库中网钛程序的表（'. $otNum .'个表）</td>
		</tr>
		<!-- <tr>
			<td><label><input type="radio" id="mode_common" name="mode" value="common" onclick="CheckBakType()">标准备份（推荐）</label></td>
			<td>备份常用的数据表</td>
		</tr>
		<tr style="display:none;">
			<td><label><input type="radio" id="mode_min" name="mode" value="min" onclick="CheckBakType()">最小备份</label></td>
			<td>仅包括栏目表，文章表，用户表</td>
		</tr> -->
		<tr>
			<td><label><input type="radio" id="mode_diy"  name="mode" value="diy" onclick="CheckBakType()">自定义备份</label></td>
			<td>根据自行选择备份数据表</td>
		</tr><!--  -->
		</table>

		<div id="showTable" style="padding-left:15px;display:none;">
			<div style="padding:6px;">'. $tabListStr .'</div>
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

	$dbRow = $DB->GetRow("
		SELECT CONCAT(TRUNCATE(SUM(data_length)/1024/1024,2),'MB') AS data_size,
		CONCAT(TRUNCATE(SUM(max_data_length)/1024/1024,2),'MB') AS max_data_size,
		CONCAT(TRUNCATE(SUM(data_free)/1024/1024,2),'MB') AS data_free,
		CONCAT(TRUNCATE(SUM(index_length)/1024/1024,2),'MB') AS index_size
		FROM information_schema.tables WHERE TABLE_SCHEMA = '". $dbName ."';
		");
	$prow = $DB->GetRow('select SP_bakDbSize,SP_bakDbIsZip from '. OT_dbPref .'sysParam');
	if ($prow['SP_bakDbSize'] == 0){ $prow['SP_bakDbSize'] = 5120; }

	$skin->TableTop('share_other.gif','','其他选项');
		echo('
		<table cellspacing="1" cellpadding="3" >
		<tr>
			<td align="right">数据库信息：</td>
			<td>数据大小：<span style="color:red;">'. $dbRow['data_size'] .'</span>，索引大小：'. $dbRow['index_size'] .'，碎片空间：'. $dbRow['data_free'] .'，最大容量：'. $dbRow['max_data_size'] .'，服务器允许占用内存：<span style="color:blue;">'. ini_get('memory_limit') .'</span></td>
		</tr>
		<tr>
			<td align="right">分卷大小：</td>
			<td>
				<input type="text" id="fileSize" name="fileSize" value="'. $prow['SP_bakDbSize'] .'"style="width:60px;" onblur=\'RevSysParam("bakDbSize",this.value)\' > KB
				<input type="button" value="5M" onclick=\'$id("fileSize").value="5120"\' />
				<input type="button" value="8M" onclick=\'$id("fileSize").value="8192"\' />
				<input type="button" value="20M" onclick=\'$id("fileSize").value="20480"\' />
				<input type="button" value="100M" onclick=\'$id("fileSize").value="102400"\' />
				<input type="button" value="1G" onclick=\'$id("fileSize").value="1048576"\' />
				<span class="font2_2" style="color:red;">&ensp;&ensp;（当数据库大小超过分卷大小时，备份会分多个文件保存）</span>
			</td>
		</tr>
		<tr>
			<td align="right">备份位置：</td>
			<td>
				<label><input type="radio" name="backupSpace" value="server" checked="checked" />备份到服务器</label>&ensp;&ensp;&ensp;&ensp;&ensp;
				<!-- <label><input type="radio" name="backupSpace" value="localpc" />备份到本地</label> -->
			</td>
		</tr>
		<!-- <tr>
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
			提醒：【数据大小】(红字数值)如果超过【服务器允许占用内存】(蓝色数值)，可能备份会出错，如出错，请用其他数据库管理软件备份。<br />
			　　　该功能尚在测试阶段，备份后的数据库文件建议测试下是否可以完整导入和正常使用，保险起见建议用其他数据库管理软件再备份下。<br />
			　　　建议用 phpMyAdmin 或者 Navicat 软件备份导出或导入数据库。
		</div>
		<br /><center><input type="submit" class="btnBg" value="开始备份" /></center>
	</form>
	');
}



// 数据库压缩
function compress(){
	global $DB;

	$skin->TableTop('share_other.gif','','数据库压缩');
		echo('暂不支持');
	$skin->TableBottom();
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

?>
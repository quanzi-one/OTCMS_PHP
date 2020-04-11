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
<script language="javascript" type="text/javascript" src="js/dbBak.js?v='. OT_VERSION .'"></script>
');


$MB->IsAdminRight('alertBack');


switch ($mudi){
	case 'manage':
		manage();
		break;

	default:
		manage();
		break;
}

$skin->WebBottom();

$MB->Close();
$DB->Close();





// 数据库维护操作
function manage(){
	global $DB,$skin,$dbName;

	$skin->TableTop('share_other.gif','','备份数据库');
	?>
	
	<form action="dbBak_deal.php?mudi=backup" method="post" onsubmit="return BackupForm()">
	<script language='javascript' type='text/javascript'>document.write("<input type='hidden' name='backURL' value='"+ document.location.href +"' />")</script>
	<table style='width:92%;' align="center" border="0" cellpadding="0" cellspacing="0" summary=''><tr><td class='padd5'>
		<input type="submit" value="开始备份" />
		&ensp;&ensp;&ensp;&ensp;（当前数据库大小：<?php echo(File::SizeUnit(filesize(OT_ROOT . $dbName))); ?>）
	</td></tr></table>
	</form>
	<?php
	$skin->TableBottom();

	echo('<br />');

	$skin->TableTop('share_other.gif','','数据库压缩');
	?>

	<form action="dbBak_deal.php?mudi=compress" method="post" onsubmit="return CompressForm()">
	<script language='javascript' type='text/javascript'>document.write("<input type='hidden' name='backURL' value='"+ document.location.href +"' />")</script>
	<table style='width:92%;' align="center" border="0" cellpadding="0" cellspacing="0" summary=''><tr><td class="padd5">
		压缩数据库选择：<select id="backupFileID" name="backupFileID">
				<option value=""> ===== 请选择备份数据库 ===== </option>
				<option value="-99">（当前正在使用的数据库）</option>
				<?php
				$infoexe=$DB->query('select * from '. OT_dbPref .'dbBak order by DB_ID DESC');
					while ($row = $infoexe->fetch()){
						echo('<option value="'. $row['DB_ID'] .'">'. $row['DB_filePath'] .'（备份于'. $row['DB_time'] .'）['. File::SizeUnit($row['DB_fileSize']) .']</option>');
					}
				unset($infoexe);
				?>
			</select>
		<br /><br />
		<input type="submit" value="开始压缩" /><br /><br />

		<span style="color:red;">如果压缩正在使用中的数据库，压缩前，建议先备份数据库，以免发生意外错误。</span>
	</td></tr></table>
	</form>
	<?php
	$skin->TableBottom();


	echo('<br />');


	$skin->TableTop('share_other.gif','','数据库恢复');
		?>
		<div class="font1_1" style="line-height:1.6;">
			<b style='color:red;'>严重警告，到万不得已，再使用数据库还原，特别是非同一版本的数据库。如果是升级出问题，请先联系网钛客服：800166366 <a href="http://wpa.b.qq.com/cgi/wpa.php?ln=1&key=XzgwMDE2NjM2Nl8zNTU2NTlfODAwMTY2MzY2XzJf" target="_blank"><img src="http://asp.otcms.com/qq.png" border="0" /></a>。</b>
			<br /><br />
			<b>数据库还原方法：</b>从数据库备份目录<span class="font1_2d">(程序默认：****_backup/)</span>下找到要还原的数据库文件(.db格式)，重命名为当前数据库名<span class="font1_2d">(程序默认:# ****@!database%.db)</span>覆盖当前数据库即可完成数据库还原。
		</div>
		<?php
	$skin->TableBottom();


	echo('<br />');


	$skin->TableTop('share_other.gif','','数据库维护');
	?>
		<ul style='list-style:none;'>
			<li style='width:120px;float:left;'><input type="button" value="事务支持：关闭" onclick="AjaxGetDeal('dbBak_deal.php?mudi=dbDeal&mudi2=shiwu0');" /></li>
			<li style='width:120px;float:left;'><input type="button" value="事务支持：开启" onclick="AjaxGetDeal('dbBak_deal.php?mudi=dbDeal&mudi2=shiwu1');" /></li>
			<!-- <li style='width:120px;float:left;'><input type="button" value="检查数据库异常" onclick="AjaxGetDeal('dbBak_deal.php?mudi=dbDeal&mudi2=check');" /></li> -->
		</ul>
	<?php
	$skin->TableBottom();
}

?>
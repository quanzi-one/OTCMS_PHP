<?php
$autoOpenStr='';

function MenuStr2($menuSelID,$menuID,$menuName,$URL,$styleStr=''){
	global $MB,$autoOpenStr;
	$mainMenuName = '';

	if ($menuID==''){$menuID=0;}
	if ($MB->IsMenuRight('jud',$menuID)==true){
		if ($autoOpenStr==''){
			echo('
			<input type="hidden" id="input'. $menuSelID .'" name="input'. $menuSelID .'" value=\'HrefTo("'. $mainMenuName .'","'. $menuName .'","'. $URL .'","'. $menuID .'")\' />
			');
			$autoOpenStr='
			<script language="javascript" type="text/javascript">
			window.onload=function(){ HrefTo("'. $mainMenuName .'","'. $menuName .'","'. $URL .'","'. $menuID .'"); }
			</script>
			';	//获取自动载入菜单信息
		}
		echo('
		<table style=\'width:178px; height:31px; background:url("images/left_menuBg.gif");\' summary=""><tr>
		<td style="padding-top:4px !important; padding-top:0px; padding-left:30px;">
			<a href="#" onclick=\'HrefTo("'. $mainMenuName .'","'. $menuName .'","'. $URL .'","'. $menuID .'");return false;\' class="fontLeft" style="'. $styleStr .'"><b>'. $menuName .'</b></a>
		</td>
		</tr></table>
		');
	}
}

$autoOpenStr='';
$menuSelID	= 'revSelf';
echo('
<div id="leftMenuID'. $menuSelID .'" style="display:none;">');
	MenuStr2($menuSelID,'修改用户名或密码','修改用户名或密码','admin.php?mudi=revPWD');
	MenuStr2($menuSelID,'其他信息设置','其他信息设置','admin.php?mudi=revOthers');;

$autoOpenStr='';
$menuSelID	= 'user';
echo('
</div>
<div id="leftMenuID'. $menuSelID .'" style="display:none;">');
	AppBase::LeftMenuBox1($menuSelID);
	MenuStr2($menuSelID,'用户管理','用户管理','member.php?mudi=manage');

$autoOpenStr='';
$menuSelID	= 'database';
echo('
</div>
<div id="leftMenuID'. $menuSelID .'" style="display:none;">');
		if (OT_Database=='sqlite'){
	MenuStr2($menuSelID,999901,'数据库备份/压缩','dbBak.php?mudi=manage');
		}elseif (OT_Database=='mysql'){
	MenuStr2($menuSelID,999902,'数据库备份','dbBakMySQL.php?mudi=backup');
	MenuStr2($menuSelID,999903,'数据库恢复','dbBakMySQL.php?mudi=restore');
//	MenuStr2($menuSelID,999904,'数据表优化','dbBakMySQL.php?mudi=table');
		}
	MenuStr2($menuSelID,999905,'程序文件备份','softBak.php?mudi=backup');
	MenuStr2($menuSelID,999906,'程序/数据库备份管理','softBak.php?mudi=manage');
	MenuStr2($menuSelID,999907,'后台参数配置','sysAdmin.php?mudi=manage');
	MenuStr2($menuSelID,999908,'程序文件检查','sysCheckFile.php?mudi=manage');
	MenuStr2($menuSelID,999910,'后台菜单排序','adminMenu.php?mudi=manage');
	MenuStr2($menuSelID,999912,'上传图片/文件管理','serverFile.php?mudi=manage');
	MenuStr2($menuSelID,999913,'后台人员操作日志','memberLog.php?mudi=manage');
	MenuStr2($menuSelID,999914,'后台人员在线记录','memberOnline.php?mudi=manage');
	MenuStr2($menuSelID,999915,'在线升级记录管理','update.php?mudi=manage');
	MenuStr2($menuSelID,999916,'数据库错误日志','dbErr.php?mudi=manage');
	MenuStr2($menuSelID,999917,'特定文档查看','editFile.php?mudi=manage');

	$menuSelID=0;
	$menuexe=$DB->query('select MT_ID,MT_theme,MT_themeStyle,MT_URL,MT_menuID from '. OT_dbPref .'menuTree where MT_level=1 and MT_userState=1 and MT_state=1 and MT_appID in ('. implode(',',$payArr) .') order by MT_menuID ASC,MT_rank ASC');
		while ($row = $menuexe->fetch()){
			if ($menuSelID != $row['MT_menuID']){
				$menuSelID = $row['MT_menuID'];
				
				$autoOpenStr='';
				echo('</div><div id="leftMenuID'. $menuSelID .'" style="display:none;">');
			}
			MenuStr2($menuSelID,$row['MT_ID'],$row['MT_theme'],$row['MT_URL'],$row['MT_themeStyle']);
		}
	unset($menuexe);
echo('
</div>
');
?>
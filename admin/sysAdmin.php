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
<script language="javascript" type="text/javascript" src="js/sysAdmin.js?v='. OT_VERSION .'"></script>
');


switch($mudi){
	default:
		$MB->IsAdminRight('alertBack');
		manage();
		break;

}

$skin->WebBottom();

$MB->Close();
$DB->Close();





// 设置
function manage(){
	global $DB,$skin,$mudi,$sysAdminArr;

	$dataMode = OT::GetStr('dataMode');

	$revexe=$DB->query('select * from '. OT_dbPref .'sysAdmin');
		if ($row = $revexe->fetch()){
			$SA_adminLoginKey	= $row['SA_adminLoginKey'];
			$SA_isAutoLogin		= $row['SA_isAutoLogin'];
			$SA_skinWidth		= $row['SA_skinWidth'];
			$SA_userSaveMode	= $row['SA_userSaveMode'];
			$SA_loginMode		= $row['SA_loginMode'];
			$SA_isLan			= $row['SA_isLan'];
			$SA_sendUrlMode		= $row['SA_sendUrlMode'];
			$SA_checkUrlMode	= $row['SA_checkUrlMode'];
			$SA_updateUrlMode	= $row['SA_updateUrlMode'];
			$SA_exitMinute		= $row['SA_exitMinute'];
			$SA_leftMenuNote	= $row['SA_leftMenuNote'];
			$SA_editorMode		= $row['SA_editorMode'];
			$SA_memberLogRank	= $row['SA_memberLogRank'];
			$SA_updateUrl		= $row['SA_updateUrl'];
			$SA_getUrlMode		= $row['SA_getUrlMode'];
			$SA_collUrlMode		= $row['SA_collUrlMode'];
			$SA_softUpdateDay	= $row['SA_softUpdateDay'];
			$SA_softLastTime	= $row['SA_softLastTime'];
			$SA_softVerUpdateDay= $row['SA_softVerUpdateDay'];
			$SA_softVerLastTime	= $row['SA_softVerLastTime'];
			$SA_copyrightName	= $row['SA_copyrightName'];
			$SA_copyrightUrl	= $row['SA_copyrightUrl'];
			$SA_isConnInternet	= $row['SA_isConnInternet'];
			$SA_isAnnounShow	= $row['SA_isAnnounShow'];
			$SA_backupCallDay	= $row['SA_backupCallDay'];
			$SA_backupCallTime	= $row['SA_backupCallTime'];
			$SA_isSubMenu		= $row['SA_isSubMenu'];
			$SA_upFileArea		= $row['SA_upFileArea'];
			$SA_upFileOss		= $row['SA_upFileOss'];
				if (! strtotime($SA_backupCallTime)){
					$SA_backupCallTime='2015-1-1';
				}else{
					$SA_backupCallTime = TimeDate::Get('date',$SA_backupCallTime);
				}
		}
	unset($revexe);


	echo('
	<form id="dealForm" name="dealForm" method="post" action="sysAdmin_deal.php?mudi=deal" onsubmit="return CheckForm()">
	<script language="javascript" type="text/javascript">document.write(\'<input type="hidden" name="backURL" value="\'+ document.location.href +\'" />\')</script>

	<div class="tabMenu">
	<div style="float:right;padding:6px 5px 0 0;"><input type="button" style="color:blue;" value="模式、路线恢复默认值" onclick=\'DataDeal.location.href="sysAdmin_deal.php?mudi=saveDef&backURL="+ encodeURIComponent(document.location.href);\' /></div>
	<ul>
	   <li rel="tabSite" class="selected">网站模式</li>
	   <li rel="tabDef">引导页设置</li>
	</ul>
	</div>

	<div class="tabMenuArea">
		<table id="tabSite" cellpadding="0" cellspacing="0" summary="" class="padd5">
		<tr><td style="width:150px;"></td><td></td></tr>
		<tr>
			<td align="right">通信密钥：</td>
			<td class="font2_2">
				<input type="text" id="adminLoginKey" name="adminLoginKey" style="width:260px;" maxlength="36" value="'. $SA_adminLoginKey .'" />
				&ensp;<input type="button" value="随机36位" onclick=\'$id("adminLoginKey").value=RndNum(36);\' />
				<br />（会直接影响后台在登录状态中用户，致使他们要重新登录。）
			</td>
		</tr>
		<!-- <tr>
			<td align="right">后台快捷登录：</td>
			<td>
				<label><input type="radio" name="isAutoLogin" value="1" '. Is::Checked($SA_isAutoLogin,1) .' />开启</label>&ensp;&ensp;
				<label><input type="radio" name="isAutoLogin" value="0" '. Is::Checked($SA_isAutoLogin,0) .' />关闭</label>&ensp;&ensp;
			</td>
		</tr> -->
		<tr>
			<td align="right">后台界面宽度：</td>
			<td>
				<label><input type="radio" name="skinWidth" value="1003" '. Is::Checked($SA_skinWidth,1003) .' />1024px(不推荐)</label>&ensp;&ensp;
				<label><input type="radio" name="skinWidth" value="1131" '. Is::Checked($SA_skinWidth,1131) .' />1152px(不推荐)</label>&ensp;&ensp;
				<label><input type="radio" name="skinWidth" value="1259" '. Is::Checked($SA_skinWidth,1259) .' />1280px</label>&ensp;&ensp;
				<label><input type="radio" name="skinWidth" value="1339" '. Is::Checked($SA_skinWidth,1339) .' />1360px</label>&ensp;&ensp;
				<label><input type="radio" name="skinWidth" value="1579" '. Is::Checked($SA_skinWidth,1579) .' />1600px</label>&ensp;&ensp;
				<label><input type="radio" name="skinWidth" value="1659" '. Is::Checked($SA_skinWidth,1659) .' />1680px</label>&ensp;&ensp;
				<label><input type="radio" name="skinWidth" value="0" '. Is::Checked($SA_skinWidth,0) .' />宽屏100%</label>&ensp;&ensp;
			</td>
		</tr>
		<tr>
			<td align="right">后台用户信息保存方式：</td>
			<td>
				<label><input type="radio" name="userSaveMode" value="1" '. Is::Checked($SA_userSaveMode,1) .' />仅session保存<span class="font2_2">(安全)</span></label>&ensp;&ensp;
				<label><input type="radio" name="userSaveMode" value="2" '. Is::Checked($SA_userSaveMode,2) .' />session + cookies保存<span class="font2_2">(持久)</span></label>
			</td>
		</tr>
		<tr>
			<td align="right">后台用户登录模式：</td>
			<td>
				<label><input type="radio" name="loginMode" value="0" '. Is::Checked($SA_loginMode,0) .' />允许一个账号多人同用</label>&ensp;&ensp;&ensp;&ensp;
				<label><input type="radio" name="loginMode" value="1" '. Is::Checked($SA_loginMode,1) .' />仅允许一个账号一人使用</label>
			</td>
		</tr>
		<tr>
			<td align="right">内网使用：</td>
			<td>
				<label><input type="radio" name="isLan" value="1" '. Is::Checked($SA_isLan,1) .' />是</label>&ensp;&ensp;&ensp;&ensp;
				<label><input type="radio" name="isLan" value="0" '. Is::Checked($SA_isLan,0) .' />否</label>&ensp;&ensp;&ensp;&ensp;
			</td>
		</tr>
		<tr>
			<td align="right">标签菜单卡：</td>
			<td>
				<label><input type="radio" name="isSubMenu" value="1" '. Is::Checked($SA_isSubMenu,1) .' />开启</label>&ensp;&ensp;
				<label><input type="radio" name="isSubMenu" value="0" '. Is::Checked($SA_isSubMenu,0) .' />关闭</label>&ensp;&ensp;
			</td>
		</tr>
		<tr>
			<td align="right">后台授权页面获取模式：</td>
			<td>
				<label><input type="radio" name="sendUrlMode" value="0" '. Is::Checked($SA_sendUrlMode,0) .' />默认</label>&ensp;&ensp;
				<label><input type="radio" name="sendUrlMode" value="1" '. Is::Checked($SA_sendUrlMode,1) .' />AJAX</label>&ensp;&ensp;&ensp;&ensp;
			</td>
		</tr>
		<tr>
			<td align="right">后台授权页面获取路线：</td>
			<td>
				<label><input type="radio" name="checkUrlMode" value="0" '. Is::Checked($SA_checkUrlMode,0) .' />主路线</label>&ensp;&ensp;&ensp;&ensp;
				<label><input type="radio" name="checkUrlMode" value="1" '. Is::Checked($SA_checkUrlMode,1) .' />国内备用路线</label>&ensp;&ensp;&ensp;&ensp;
				<label><input type="radio" name="checkUrlMode" value="2" '. Is::Checked($SA_checkUrlMode,2) .' />香港备用路线</label>&ensp;&ensp;<span style="cursor:pointer;" onclick=\'ClickShowHidden("usUrlBox1");ClickShowHidden("usUrlBox2");\'>&ensp;&ensp;</span>
				<label id="usUrlBox1" style="display:;"><input type="radio" name="checkUrlMode" value="3" '. Is::Checked($SA_checkUrlMode,3) .' />美国备用路线</label>
			</td>
		</tr>
		<tr>
			<td align="right">后台授权信息获取路线：</td>
			<td>
				<label><input type="radio" name="updateUrlMode" value="0" '. Is::Checked($SA_updateUrlMode,0) .' />主路线</label>&ensp;&ensp;&ensp;&ensp;
				<label><input type="radio" name="updateUrlMode" value="1" '. Is::Checked($SA_updateUrlMode,1) .' />国内备用路线</label>&ensp;&ensp;&ensp;&ensp;
				<label><input type="radio" name="updateUrlMode" value="2" '. Is::Checked($SA_updateUrlMode,2) .' />香港备用路线</label>&ensp;&ensp;&ensp;&ensp;
				<label id="usUrlBox2" style="display:;"><input type="radio" name="updateUrlMode" value="3" '. Is::Checked($SA_updateUrlMode,3) .' />美国备用路线</label>
			</td>
		</tr>
		<tr>
			<td align="right">后台授权调用模式：</td>
			<td>
				<label><input type="radio" name="getUrlMode" value="0" '. Is::Checked($SA_getUrlMode,0) .' />自动(默认)</label>&ensp;&ensp;&ensp;
				<label><input type="radio" name="getUrlMode" value="2" '. Is::Checked($SA_getUrlMode,2) .' />curl模式<span style="color:red;">[推荐]</span>&ensp;'. ExtCN(extension_loaded('curl')) .'</label>&ensp;&ensp;&ensp;
				<label><input type="radio" name="getUrlMode" value="1" '. Is::Checked($SA_getUrlMode,1) .' />Snoopy插件&ensp;'. ExtCN(function_exists('stream_socket_client')) .'</label>&ensp;&ensp;&ensp;
				<label><input type="radio" name="getUrlMode" value="3" '. Is::Checked($SA_getUrlMode,3) .' />fsockopen模式<span style="color:red;">(用万网空间选这个)</span>&ensp;'. ExtCN(function_exists('fsockopen')) .'</label>&ensp;&ensp;&ensp;
				<label><input type="radio" name="getUrlMode" value="4" '. Is::Checked($SA_getUrlMode,4) .' />fopen模式&ensp;'. ExtCN(ini_get('allow_url_fopen')==1 || strtolower(ini_get('allow_url_fopen'))=='on') .'</label>
			</td>
		</tr>
		<tr>
			<td align="right">后台采集调用模式：</td>
			<td>
				<label><input type="radio" name="collUrlMode" value="0" '. Is::Checked($SA_collUrlMode,0) .' />自动(默认)</label>&ensp;&ensp;&ensp;
				<label><input type="radio" name="collUrlMode" value="2" '. Is::Checked($SA_collUrlMode,2) .' />curl模式<span style="color:red;">[推荐]</span>&ensp;'. ExtCN(extension_loaded('curl')) .'</label>&ensp;&ensp;&ensp;
				<label><input type="radio" name="collUrlMode" value="1" '. Is::Checked($SA_collUrlMode,1) .' />Snoopy插件&ensp;'. ExtCN(function_exists('stream_socket_client')) .'</label>&ensp;&ensp;&ensp;
				<label><input type="radio" name="collUrlMode" value="3" '. Is::Checked($SA_collUrlMode,3) .' />fsockopen模式<span style="color:red;">(用万网空间选这个)</span>&ensp;'. ExtCN(function_exists('fsockopen')) .'</label>&ensp;&ensp;&ensp;
				<label><input type="radio" name="collUrlMode" value="4" '. Is::Checked($SA_collUrlMode,4) .' />fopen模式&ensp;'. ExtCN(ini_get('allow_url_fopen')==1 || strtolower(ini_get('allow_url_fopen'))=='on') .'</label>
			</td>
		</tr>
		<tr>
			<td align="right">后台编辑器：</td>
			<td class="editorBox">
				'. (AreaApp::Jud(36) ? '<label><input type="radio" id="editorMode4" name="editorMode" value="ckeditor4.x" '. Is::Checked($SA_editorMode,'ckeditor4.x') .' onclick="CheckEditorMode();" /><span><img src="temp/editor_ckeditor4.x.png" style="display:none;" /></span>CKEditor4.7.3</label><span style="color:red;">（推荐）</span>&ensp;&ensp;&ensp;' : '') .'
				<label><input type="radio" id="editorMode6" name="editorMode" value="kindeditor4.x" '. Is::Checked($SA_editorMode,'kindeditor4.x') .' onclick="CheckEditorMode();" /><span><img src="temp/editor_kindeditor4.x.png" style="display:none;" /></span>KindEditor4.1.11</label>&ensp;&ensp;&ensp;
				'. (AreaApp::Jud(34) ? '<label><input type="radio" id="editorMode1" name="editorMode" value="kindeditor3.x" '. Is::Checked($SA_editorMode,'kindeditor3.x') .' onclick="CheckEditorMode();" /><span><img src="temp/editor_kindeditor3.x.png" style="display:none;" /></span>KindEditor3.5.6</label><span style="color:red;">（不推荐，不兼容新版浏览器）</span>&ensp;&ensp;&ensp;' : '') .'
				'. (AreaApp::Jud(35) ? '<label><input type="radio" id="editorMode5" name="editorMode" value="fckeditor" '. Is::Checked($SA_editorMode,'fckeditor') .' onclick="CheckEditorMode();" /><span><img src="temp/editor_fckeditor.png" style="display:none;" /></span>FCKeditor2.6.4</label>&ensp;&ensp;&ensp;' : '') .'
				'. (AreaApp::Jud(37) ? '<label><input type="radio" id="editorMode3" name="editorMode" value="ckeditor" '. Is::Checked($SA_editorMode,'ckeditor') .' onclick="CheckEditorMode();" /><span><img src="temp/editor_ckeditor3.x.png" style="display:none;" /></span>CKEditor3.6.6</label>&ensp;&ensp;&ensp;' : '') .'
				'. (AreaApp::Jud(38) ? '<label><input type="radio" id="editorMode7" name="editorMode" value="ueditor" '. Is::Checked($SA_editorMode,'ueditor') .' onclick="CheckEditorMode();" /><span><img src="temp/editor_ueditor.png" style="display:none;" /></span>UEditor1.4.3.3(百度编辑器)</label>&ensp;&ensp;&ensp;' : '') .'
			</td>
		</tr>
		<tr>
			<td align="right">后台上传文件默认保存在：</td>
			<td>
				<label><input type="radio" name="upFileOss" value="web" '. Is::Checked($SA_upFileOss,'web') .' />网站</label><span style="color:red;">（默认）</span>&ensp;&ensp;&ensp;
				'. (AppOssQiniu::Jud() ? '<label><input type="radio" name="upFileOss" value="qiniu" '. Is::Checked($SA_upFileOss,'qiniu') .' />七牛云</label>&ensp;&ensp;&ensp;' : '') .'
				'. (AppOssUpyun::Jud() ? '<label><input type="radio" name="upFileOss" value="upyun" '. Is::Checked($SA_upFileOss,'upyun') .' />又拍云</label>&ensp;&ensp;&ensp;' : '') .'
				'. (AppOssAliyun::Jud() ? '<label><input type="radio" name="upFileOss" value="aliyun" '. Is::Checked($SA_upFileOss,'aliyun') .' />阿里云</label>&ensp;&ensp;&ensp;' : '') .'
				'. (AppOssFtp::Jud() ? '<label><input type="radio" name="upFileOss" value="ftp" '. Is::Checked($SA_upFileOss,'ftp') .' />FTP云存储</label>&ensp;&ensp;&ensp;' : '') .'
			</td>
		</tr>
		<tr>
			<td align="right">后台用户登录超时时间：</td>
			<td>
				<input type="text" name="exitMinute" value="'. $SA_exitMinute .'" style="width:50px;" onkeyup="if (this.value!=FiltInt(this.value)){this.value=FiltInt(this.value)}" />分钟
				<span class="font2_2">&ensp;(值如为0，则表示无时间限制)</span>
			</td>
		</tr>
		<!-- <tr>
			<td align="right">后台右侧默认页：</td>
			<td>
				<label><input type="radio" name="leftMenuNote" value="server" '. Is::Checked($SA_leftMenuNote,'server') .' />服务器信息</label>&ensp;&ensp;&ensp;&ensp;
				<label><input type="radio" name="leftMenuNote" value="shop" '. Is::Checked($SA_leftMenuNote,'shop') .' />网店信息</label>&ensp;&ensp;&ensp;&ensp;
				<label><input type="radio" name="leftMenuNote" value="menu" '. Is::Checked($SA_leftMenuNote,'menu') .' />常用菜单列表</label>
			</td>
		</tr>
		<tr>
			<td align="right">后台人员操作日志等级：</td>
			<td>
				<label><input type="radio" name="memberLogRank" value="0" '. Is::Checked($SA_memberLogRank,0) .' />关闭</label>&ensp;&ensp;&ensp;&ensp;
				<label><input type="radio" name="memberLogRank" value="10" '. Is::Checked($SA_memberLogRank,10) .' />只记录用户登录/退出</label>&ensp;&ensp;&ensp;&ensp;
				<label><input type="radio" name="memberLogRank" value="20" '. Is::Checked($SA_memberLogRank,20) .' />全部简约记录</label>&ensp;&ensp;&ensp;&ensp;
				<label><input type="radio" name="memberLogRank" value="30" '. Is::Checked($SA_memberLogRank,30) .' />全部详细记录</label>
			</td>
		</tr> -->
		<tr>
			<td align="right">在线升级网址：</td>
			<td class="font2_2">
				<input type="text" id="updateUrl" name="updateUrl" style="width:380px;" value="'. $SA_updateUrl .'" />
				&ensp;<span onclick=\'if($id("copyrightBox").style.display==""){$id("copyrightBox").style.display="none";}else{$id("copyrightBox").style.display="";}WindowHeight(0);\' style="cursor:pointer;">&ensp;</span>
			</td>
		</tr>
		<tbody id="copyrightBox" style="display:none;">
		<tr>
			<td align="right">后台底部版权信息：</td>
			<td class="font2_2">
				<input type="text" id="copyrightName" name="copyrightName" style="width:380px;" value="'. $SA_copyrightName .'" />
			</td>
		</tr>
		<tr>
			<td align="right">后台底部版权网址：</td>
			<td class="font2_2">
				<input type="text" id="copyrightUrl" name="copyrightUrl" style="width:380px;" value="'. $SA_copyrightUrl .'" />
			</td>
		</tr>
		</tbody>
		</table>


		<table id="tabDef" style="display:none;" cellpadding="0" cellspacing="0" summary="" class="padd3">
		<tr><td style="width:150px;"></td><td></td></tr>
		<tr>
			<td align="right">连接网络获取程序信息：</td>
			<td>
				<label><input type="radio" name="isConnInternet" value="1" '. Is::Checked($SA_isConnInternet,1) .' />开启</label>&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;
				<label><input type="radio" name="isConnInternet" value="0" '. Is::Checked($SA_isConnInternet,0) .' />关闭</label>
				<span class="font2_2">&ensp;&ensp;(开启【内网使用】项，等于该项[关闭])</span>
			</td>
		</tr>
		<tr>
			<td align="right">网钛信息：</td>
			<td>
				<label><input type="radio" name="isAnnounShow" value="1" '. Is::Checked($SA_isAnnounShow,1) .' />自动刷新</label>&ensp;&ensp;&ensp;
				<label><input type="radio" name="isAnnounShow" value="0" '. Is::Checked($SA_isAnnounShow,0) .' />手动刷新</label>
			</td>
		</tr>
		<tr>
			<td align="right">数据库备份定期提醒：</td>
			<td>
				<input type="text" name="backupCallDay" value="'. $SA_backupCallDay .'" style="width:30px;" onkeyup="if (this.value!=FiltNumber(this.value)){this.value=FiltNumber(this.value)}" />&ensp;天以后
				<span class="font2_2">&ensp;(值如为0表示关闭提醒)</span>
				&ensp;&ensp;（最后提醒日期：'. $SA_backupCallTime .'，下一次提醒日期：'. TimeDate::Add('d',$SA_backupCallDay,$SA_backupCallTime) .'）
			</td>
		</tr>
		<tr>
			<td align="right">授权信息更新：</td>
			<td>
				<input type="text" name="softUpdateDay" value="'. $SA_softUpdateDay .'" style="width:30px;" onkeyup="if (this.value!=FiltNumber(this.value)){this.value=FiltNumber(this.value)}" />&ensp;天/次
				<span class="font2_2">&ensp;(值如为0表示立即更新；-1表示不更新)</span>
				&ensp;&ensp;（最后更新时间：'. $SA_softLastTime .'）
			</td>
		</tr>
		<tr>
			<td align="right">程序最新版本检测：</td>
			<td>
				<input type="text" name="softVerUpdateDay" value="'. $SA_softVerUpdateDay .'" style="width:30px;" onkeyup="if (this.value!=FiltNumber(this.value)){this.value=FiltNumber(this.value)}" />&ensp;天/次
				<span class="font2_2">&ensp;(值如为0表示立即检测；-1表示不检测)</span>
				&ensp;&ensp;（最后检测时间：'. $SA_softVerLastTime .'）
			</td>
		</tr>
		</table>

		<div class="tabMenuSubmit"><input type="submit" class="btnBg" value="保 存" /></div>
	</div>

	</form>
	');

}



function ExtCN($jud){
	if ($jud){
		return '<span style="color:green;">支持</span>';
	}else{
		return '<span style="color:red;">不支持</span>';
	}
}

?>
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
<script language="javascript" type="text/javascript" src="js/infoWeb.js?v='. OT_VERSION .'"></script>
');


switch ($mudi){
	case 'dynWeb':
	case 'dynWeb2':
		$MB->IsSecMenuRight('alertBack',15,$dataType);
		dynWeb();
		break;

	default:
		die('err');
}

$skin->WebBottom();

$MB->Close();
$DB->Close();





function dynWeb(){
	global $DB,$skin,$mudi,$dataType,$dataTypeCN;

	$dataID		= OT::GetInt('dataID');
	$dataMode	= OT::GetStr('dataMode');
	$dataModeStr= OT::GetStr('dataModeStr');

	if ($mudi == 'dynWeb2'){ $isOne=1; }else{ $isOne=0; }
	$webexe=$DB->query('select * from '. OT_dbPref .'infoWeb where IW_ID='. $dataID);
		if ($row = $webexe->fetch()){
			$IW_theme		= $row['IW_theme'];
			$IW_themeStyle	= $row['IW_themeStyle'];
			$IW_isTitle		= $row['IW_isTitle'];
			$IW_titleAddi	= $row['IW_titleAddi'];
			$IW_mode		= $row['IW_mode'];
			$IW_URL			= $row['IW_URL'];
			$IW_isEncUrl	= $row['IW_isEncUrl'];
			$IW_template	= $row['IW_template'];
			$IW_templateWap	= $row['IW_templateWap'];
			$IW_webKey		= $row['IW_webKey'];
			$IW_webDesc		= $row['IW_webDesc'];
			$IW_content		= $row['IW_content'];
			$IW_isWap		= $row['IW_isWap'];
			$IW_contentWap	= $row['IW_contentWap'];
				$beforeURL=GetUrl::CurrDir(1);
				$imgUrl=$beforeURL . InfoImgDir;
				$IW_content	= str_replace(InfoImgAdminDir, $imgUrl, $IW_content);
				$IW_contentWap	= str_replace(InfoImgAdminDir, $imgUrl, $IW_contentWap);
			$IW_upImgStr	= $row['IW_upImgStr'];
			$IW_listMode	= $row['IW_listMode'];
				if ($IW_listMode == 0){ $IW_listMode = 2; }
			$IW_pageNum		= $row['IW_pageNum'];
			$IW_rank		= $row['IW_rank'];
			$IW_state		= $row['IW_state'];
			$IW_wapState	= $row['IW_wapState'];


			$mudi	= 'rev';
			$mudiCN	= '修改';
		}else{
			$IW_theme		= '';
			$IW_themeStyle	= '';
			$IW_isTitle		= 0;
			$IW_titleAddi	= '';
			$IW_mode		= '';
			$IW_URL			= 'http://';
			$IW_isEncUrl	= 0;
			$IW_template	= '';
			$IW_templateWap	= '';
			$IW_webKey		= '';
			$IW_webDesc		= '';
			$IW_content		= '';
			$IW_isWap		= 0;
			$IW_contentWap	= '';
			$IW_upImgStr	= '';
			$IW_listMode	= 2;
			$IW_pageNum		= 15;
			$IW_rank		= intval($DB->GetOne("select max(IW_rank) from ". OT_dbPref ."infoWeb where IW_type='". $dataType ."'"))+10;
			$IW_state		= 1;
			$IW_wapState	= 1;

			$mudi	= 'add';
			$mudiCN	= '添加';
		}
	unset($webexe);

	$themeStyle_color	= Str::GetMark($IW_themeStyle,'color:',';');
	$themeStyle_b		= Str::GetMark($IW_themeStyle,'font-weight:',';');

	if (! $isOne){
		$skin->TableTop2('share_list.gif','',$dataTypeCN .'列表');
		if (strpos('|bottom|topic|','|'. $dataType .'|') === false){
			echo('
			<colsgroup>
				<col>
				<col>
				<col>
				<col>
				<col style="display:none;">
			</colsgroup>
			');
		}
		$skin->TableItemTitle('8%,8%,50%,8%,11%,15%','编号,ID号,名称,排序,电脑/WAP状态,预览&ensp;修改&ensp;删除');
			
		echo('
		<tbody class="tabBody padd3td">
		');
		$number=1;
		$webexe = $DB->query("select * from ". OT_dbPref ."infoWeb where IW_type='". $dataType ."' order by IW_rank ASC");
		while ($row = $webexe->fetch()){
			if ($number % 2 == 0){ $bgcolor='class="tabColorTr"'; }else{ $bgcolor=''; }
			switch ($dataType){
				case 'topic':	$webURL='../news/?list_topic-'. $row['IW_ID'] .'.html';
					break;
			
				case 'sitemap':	$webURL='../sitemap.html';
					break;
			
				case 'message':	$webURL='../message.php';
					break;
			
				case 'url':		$webURL=$row['IW_URL'];
					break;
			
				default :		$webURL='../news/?web_'. $row['IW_ID'] .'.html';
					break;
			}
			echo('
			<tr id="data'. $row['IW_ID'] .'" '. $bgcolor .'>
				<td align="center">'. $number .'</td>
				<td align="center">'. $row['IW_ID'] .'</td>
				<td align="center" style="'. $row['IW_themeStyle'] .'">'. $row['IW_theme'] .'</td>
				<td align="center">'. $row['IW_rank'] .'</td>
				<td align="center">
					'. Adm::SwitchBtn('infoWeb',$row['IW_ID'],$row['IW_state'],'state') .'/
					'. Adm::SwitchBtn('infoWeb',$row['IW_ID'],$row['IW_wapState'],'wapState','userState') .'
				</td>
				<td align="center">
					<img src="images/img_det.gif" style="cursor:pointer" onclick=\'window.open("'. $webURL .'")\' alt="" />&ensp;&ensp;
					<img src="images/img_rev.gif" style="cursor:pointer;" onclick=\'document.location.href="infoWeb.php?mudi=dynWeb&dataMode='. $dataMode .'&dataModeStr='. $dataModeStr .'&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&dataID='. $row['IW_ID'] .'"\' alt="" />&ensp;&ensp;
					<img src="images/img_del.gif" style="cursor:pointer;" onclick=\'if(confirm("确定删除？")==true){DataDeal.location.href="infoWeb_deal.php?mudi=del&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&theme='. urlencode($row['IW_theme']) .'&dataID='. $row['IW_ID'] .'"}\' alt="" />
				</td>
			</tr>
			');
			$number ++;
		}
		unset($webexe);

		if ($dataType == 'topic'){
			$dataModeCN = '专题页';
		}else{
			$dataModeCN = '单篇页';
		}

		echo('
		</tbody>
		</table>
		<div class="font2_2" style="margin:5px;">提醒：新增/编辑栏目-模式['. $dataModeCN .']，可以调用该信息。</div>

		<br />
		');
	}

	$skin->TableTop('share_show.gif','',$mudiCN . $dataTypeCN);
		echo('
		<form id="dealForm" name="dealForm" method="post" action="infoWeb_deal.php?mudi=dynWeb" onsubmit="return CheckForm()">
		<script language="javascript" type="text/javascript">document.write(\'<input type="hidden" name="backURL" value="\'+ document.location.href +\'">\')</script>
		<input type="hidden" id="dataType" name="dataType" value="'. $dataType .'" />
		<input type="hidden" id="dataTypeCN" name="dataTypeCN" value="'. $dataTypeCN .'" />
		<input type="hidden" id="dataMode" name="dataMode" value="'. $dataMode .'" />
		<input type="hidden" id="dataModeStr" name="dataModeStr" value="'. $dataModeStr .'" />
		<input type="hidden" id="isOne" name="isOne" value="'. $isOne .'" />
		<input type="hidden" id="dataID" name="dataID" value="'. $dataID .'" />
		<table width="99%" align="center" cellpadding="0" cellspacing="0" summary="" class="padd3">
		<tr>
			<td style="width:12%;"></td>
			<td style="width:88%;"></td>
		</tr>
		<tr>
			<td align="right">标题：</td>
			<td>
			');
				if ($isOne){
					echo($IW_theme .'<input type="hidden" id="theme" name="theme" size="50" value="'. $IW_theme .'" />&ensp;');
				}else{
					echo('<input type="text" id="theme" name="theme" size="50" style="width:480px;'. $IW_themeStyle .'" value="'. $IW_theme .'" />&ensp;');
				}
				if (strpos('|bottom|','|'. $dataType .'|') !== false){
					echo(AppBase::InfoWebThemeB($themeStyle_b,$themeStyle_color));
				}
			echo('
			</td>
		</tr>
		');
		if ($dataType != 'news'){
			echo(AppBase::InfoWebTrBox1($IW_titleAddi,$IW_isTitle));
		}
		if (strpos($dataModeStr,'|URL|') !== false){
			echo('
			<tr>
				<td align="right">模式：</td>
				<td>
					<select id="mode" name="mode" onchange="CheckMode()">
						<option value="web">单篇页</option>
						<option value="url" '. Is::Selected($IW_mode,'url') .'>外部链接(http://)</option>
						<option value="sitemap" '. Is::Selected($IW_mode,'sitemap') .'>网站地图(sitemap.html)</option>
						<option value="message" '. Is::Selected($IW_mode,'message') .'>留言本</option>
					</select>
				</td>
			</tr>
			<tr id="urlBox">
				<td align="right">外部链接：</td>
				<td>
					<input type="text" name="webURL" size="50" style="width:480px;" value="'. $IW_URL .'" />
					'. AppTaobaoke::InfoWebEncUrl($IW_isEncUrl) .'
				</td>
			</tr>
			');
		}
		echo('
		<tbody id="webBox">
		'. AppBase::InfoWebTrBox2($IW_template) .'
		'. AppWap::InfoWebTrBox2($IW_templateWap) .'
		');
		if ($dataType != 'news'){
			echo('
			<tr>
				<td align="right" valign="top" style="padding-top:6px;">
					关键字：<br />(Keywords)
				</td>
				<td style="padding-bottom:4px;"><textarea id="webKey" name="webKey" rows="5" cols="40" style="width:480px; height:60px;">'. $IW_webKey .'</textarea></td>
			</tr>
			<tr>
				<td align="right" valign="top" style="padding-top:6px;">
					描述：<br />(Description)
				</td>
				<td style="padding-bottom:4px;"><textarea id="webDesc" name="webDesc" rows="5" cols="40" style="width:480px; height:60px;">'. $IW_webDesc .'</textarea><span class="font2_2">星号“*”表示使用网站关键字/描述</span></td>
			</tr>
			');
		}
		if ($dataType == 'topic'){
			echo('
			<tr>
				<td align="right">列表页模式：</td>
				<td align="left">
					<label><input type="radio" name="listMode" value="7" '. Is::Checked($IW_listMode,7) .' />标题</label>&ensp;&ensp;
					<label><input type="radio" name="listMode" value="1" '. Is::Checked($IW_listMode,1) .' />标题+摘要</label>&ensp;&ensp;
					<label><input type="radio" name="listMode" value="2" '. Is::Checked($IW_listMode,2) .' />图+摘要1</label>&ensp;&ensp;
					<label><input type="radio" name="listMode" value="4" '. Is::Checked($IW_listMode,4) .' />图+摘要2</label>&ensp;&ensp;
					<label><input type="radio" name="listMode" value="3" '. Is::Checked($IW_listMode,3) .' />图+标题</label>&ensp;&ensp;
				</td>
			</tr>
			<tr>
				<td align="right">每页数量：</td>
				<td>
					<input type="text" id="pageNum" name="pageNum" size="3" value="'. $IW_pageNum .'" />
				</td>
			</tr>
			');
		}
		if ($dataType != 'topic'){
			echo('
			<tr>
				<td align="right" valign="top" style="margin-top:6px;">内容：</td>
				<td>
					<textarea id="content" name="content" cols="40" rows="4" style="width:650px;height:500px;" class="text" onclick=\'LoadEditor("content",0,0,"");\' title="点击开启编辑器模式">'. Str::MoreReplace($IW_content,'textarea') .'</textarea>
					<script language="javascript" type="text/javascript">LoadEditor("content",0,0,"");</script>
					<div>
					<input type="hidden" id="upImgStr" name="upImgStr" value="'. $IW_upImgStr .'" />
					<input type="button" onclick=\'OT_OpenUpImg("editor","content","","")\' value="上传图片载入编辑器" />
					</div>
				</td>
			</tr>
			'. AppWap::InfoWebBox1($IW_isWap, $IW_contentWap) .'
			');
		}
		echo('
		</tbody>
		<tr>
			<td align="right">排序：</td>
			<td>
				<input type="text" id="rank" name="rank" size="3" value="'. $IW_rank .'" />
			</td>
		</tr>
		');
		if (strpos('|bottom|topic|news|','|'. $dataType .'|') !== false){
			echo('
			<tr>
				<td align="right">电脑版状态：</td>
				<td align="left">
					<label><input type="radio" name="state" value="1" '. Is::Checked($IW_state,1) .' />显示</label>&ensp;&ensp;
					<label><input type="radio" name="state" value="0" '. Is::Checked($IW_state,0) .' />隐藏</label>&ensp;&ensp;
				</td>
			</tr>
			<tr>
				<td align="right">手机版状态：</td>
				<td align="left">
					<label><input type="radio" name="wapState" value="1" '. Is::Checked($IW_wapState,1) .' />显示</label>&ensp;&ensp;
					<label><input type="radio" name="wapState" value="0" '. Is::Checked($IW_wapState,0) .' />隐藏</label>&ensp;&ensp;
				</td>
			</tr>
			');
		}else{
			echo('
			<input type="hidden" name="state" value="1" />
			');
		}
		echo('
		</table>
		<table style="height:16px;" cellpadding="0" cellspacing="0" summary=""><tr><td></td></tr></table>

		<center><input type="image" src="images/button_'. $mudi .'.gif" /></center>
		</form>
		');

	$skin->TableBottom();

}

?>
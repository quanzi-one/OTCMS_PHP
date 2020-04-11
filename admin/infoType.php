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
<script language="javascript" type="text/javascript" src="js/infoType.js?v='. OT_VERSION .'"></script>
');


switch ($mudi){
	case 'add':
		$MB->IsSecMenuRight('alertBack',93,$dataType);
		AddOrRev();
		break;

	case 'rev':
		$MB->IsSecMenuRight('alertBack',94,$dataType);
		AddOrRev();
		break;

	case 'manage':
		$MB->IsSecMenuRight('alertBack',92,$dataType);
		manage();
		break;

	default:
		die('err');
}

$skin->WebBottom();

$MB->Close();
$DB->Close();





// 新增、修改
function AddOrRev(){
	global $DB,$MB,$skin,$mudi,$dataType,$dataTypeCN,$systemArr;

	$dataMode		= OT::GetStr('dataMode');
	$dataModeStr	= OT::GetStr('dataModeStr');
	$backURL		= OT::GetStr('backURL');
	$dataID			= OT::GetInt('dataID');

	if ($mudi == 'rev'){
		$revexe=$DB->query('select * from '. OT_dbPref .'infoType where IT_ID='. $dataID);
			if (! $row = $revexe->fetch()){
				JS::AlertBackEnd('无该记录！');		
			}
		$IT_ID				= $row['IT_ID'];
		$IT_fatID			= $row['IT_fatID'];
		$IT_isTitle			= $row['IT_isTitle'];
		$IT_titleAddi		= $row['IT_titleAddi'];
		$IT_template		= $row['IT_template'];
		$IT_templateWap		= $row['IT_templateWap'];
		$IT_htmlName		= $row['IT_htmlName'];
		$IT_theme			= $row['IT_theme'];
		$IT_themeStyle		= $row['IT_themeStyle'];
		$IT_mode			= $row['IT_mode'];
		$IT_webKey			= $row['IT_webKey'];
		$IT_webDesc			= $row['IT_webDesc'];
		$IT_URL				= $row['IT_URL'];
		$IT_isEncUrl		= $row['IT_isEncUrl'];
		$IT_webID			= $row['IT_webID'];
		$IT_openMode		= $row['IT_openMode'];
		$IT_showMode		= $row['IT_showMode'];
		$IT_showNum			= $row['IT_showNum'];
		$IT_wapShowMode		= $row['IT_wapShowMode'];
		$IT_wapShowNum		= $row['IT_wapShowNum'];
		$IT_listInfo		= $row['IT_listInfo'];
		$IT_subNewNum		= $row['IT_subNewNum'];
		$IT_subRecomNum		= $row['IT_subRecomNum'];
		$IT_subHotNum		= $row['IT_subHotNum'];
		$IT_isRightMenu		= $row['IT_isRightMenu'];
		$IT_homeIco			= $row['IT_homeIco'];
		$IT_homeNum			= $row['IT_homeNum'];
		$IT_isHomeImg		= $row['IT_isHomeImg'];
		$IT_isItemDate		= $row['IT_isItemDate'];
		$IT_isItemType		= $row['IT_isItemType'];
		$IT_isUser			= $row['IT_isUser'];
		$IT_isMenu			= $row['IT_isMenu'];
		$IT_isSubMenu		= $row['IT_isSubMenu'];
		$IT_isWap			= $row['IT_isWap'];
		$IT_isWapHome		= $row['IT_isWapHome'];
		$IT_isWapHomeDate	= $row['IT_isWapHomeDate'];
		$IT_wapHomeIco		= $row['IT_wapHomeIco'];
		$IT_wapHomeImgNum	= $row['IT_wapHomeImgNum'];
		$IT_wapHomeNum		= $row['IT_wapHomeNum'];
		$IT_isHome			= $row['IT_isHome'];
		$IT_rank			= $row['IT_rank'];
		$IT_itemRank		= $row['IT_itemRank'];
		$IT_state			= $row['IT_state'];
		$IT_wapItemRank		= $row['IT_wapItemRank'];
		$IT_isWapSubMenu	= $row['IT_isWapSubMenu'];
		if (AppBase::Jud()){
			$IT_lookScore	= $row['IT_lookScore'];
		}else{
			$IT_lookScore	= 5;
		}
		unset($revexe);
		$mudiCN='修改';
	}else{
		if ($dataID>0){
			$revexe=$DB->query('select * from '. OT_dbPref .'infoType where IT_ID='. $dataID);
				if (! $row = $revexe->fetch()){
					JS::AlertBackEnd('无该记录！');		
				}
			$IT_ID				= $row['IT_ID'];
			$IT_fatID			= $row['IT_fatID'];
			$IT_isTitle			= $row['IT_isTitle'];
			$IT_titleAddi		= $row['IT_titleAddi'];
			$IT_template		= $row['IT_template'];
			$IT_templateWap		= $row['IT_templateWap'];
			$IT_htmlName		= '';
			$IT_theme			= '';
			$IT_themeStyle		= $row['IT_themeStyle'];
			$IT_mode			= $row['IT_mode'];
			$IT_webKey			= $row['IT_webKey'];
			$IT_webDesc			= $row['IT_webDesc'];
			$IT_URL				= '';
			$IT_isEncUrl		= 0;
			$IT_webID			= 0;
			$IT_openMode		= $row['IT_openMode'];
			$IT_showMode		= $row['IT_showMode'];
			$IT_showNum			= $row['IT_showNum'];
			$IT_wapShowMode		= $row['IT_wapShowMode'];
			$IT_wapShowNum		= $row['IT_wapShowNum'];
			$IT_listInfo		= $row['IT_listInfo'];
			$IT_subNewNum		= $row['IT_subNewNum'];
			$IT_subRecomNum		= $row['IT_subRecomNum'];
			$IT_subHotNum		= $row['IT_subHotNum'];
			$IT_isRightMenu		= $row['IT_isRightMenu'];
			$IT_isWapSubMenu	= $row['IT_isWapSubMenu'];
			$IT_homeIco			= $row['IT_homeIco'];
			$IT_homeNum			= $row['IT_homeNum'];
			$IT_isHomeImg		= $row['IT_isHomeImg'];
			$IT_isItemDate		= $row['IT_isItemDate'];
			$IT_isItemType		= $row['IT_isItemType'];
			$IT_isUser			= $row['IT_isUser'];
			$IT_isMenu			= $row['IT_isMenu'];
			$IT_isSubMenu		= $row['IT_isSubMenu'];
			$IT_isWap			= $row['IT_isWap'];
			$IT_isWapHome		= $row['IT_isWapHome'];
			$IT_isWapHomeDate	= $row['IT_isWapHomeDate'];
			$IT_wapHomeIco		= $row['IT_wapHomeIco'];
			$IT_wapHomeImgNum	= $row['IT_wapHomeImgNum'];
			$IT_wapHomeNum		= $row['IT_wapHomeNum'];
			$IT_isHome			= $row['IT_isHome'];
			if (AppBase::Jud()){
				$IT_lookScore	= $row['IT_lookScore'];
			}else{
				$IT_lookScore	= 5;
			}
			unset($revexe);
			$dataID = 0;
		}else{
			$IT_ID				= 0;
			$IT_fatID			= 0;
			$IT_isTitle			= 0;
			$IT_titleAddi		= '';
			$IT_template		= '';
			$IT_templateWap		= '';
			$IT_htmlName		= '';
			$IT_theme			= '';
			$IT_themeStyle		= '';
			$IT_mode			= 'item';
			$IT_webKey			= '';
			$IT_webDesc			= '';
			$IT_URL				= '';
			$IT_isEncUrl		= 0;
			$IT_webID			= 0;
			$IT_openMode		= '_self';
			$IT_showMode		= 2;
			$IT_showNum			= 10;
			$IT_wapShowMode		= 2;
			$IT_wapShowNum		= 10;
			$IT_listInfo		= '';
			$IT_subNewNum		= 10;
			$IT_subRecomNum		= 10;
			$IT_subHotNum		= 10;
			$IT_isRightMenu		= 0;
			$IT_isWapSubMenu	= 0;
			$IT_homeIco			= 0;
			$IT_homeNum			= 10;
			$IT_isHomeImg		= 1;
			$IT_isItemDate		= 1;
			$IT_isItemType		= 0;
			$IT_isUser			= 1;
			$IT_isMenu			= 1;
			$IT_isSubMenu		= 1;
			$IT_isWap			= 1;
			$IT_isWapHome		= 1;
			$IT_isWapHomeDate	= 1;
			$IT_wapHomeIco		= 0;
			$IT_wapHomeImgNum	= 1;
			$IT_wapHomeNum		= 6;
			$IT_isHome			= 1;
			$IT_lookScore		= 5;
		}
		$IT_rank			= intval($DB->GetOne("select max(IT_rank) from ". OT_dbPref ."infoType where IT_mode not in ('urlMessage','urlBbs')")) + 10;
		$IT_itemRank		= 0;
		$IT_wapItemRank		= 0;
		$IT_state			= 1;
		$mudiCN='添加';
	}

	if (strlen($IT_URL) == 0){ $IT_URL='http://'; }

	$themeStyle_color	= Str::GetMark($IT_themeStyle,'color:',';');
	$themeStyle_b		= Str::GetMark($IT_themeStyle,'font-weight:',';');

	$icoOptionStr = '';
	for ($i=1; $i<=28; $i++){
		$icoOptionStr .= '<option value="'. $i .'" '. Is::Selected($IT_homeIco,$i) .'>图标'. $i .'</option>';
	}

	echo('
	<form id="dealForm" name="dealForm" method="post" action="infoType_deal.php?mudi='. $mudi .'&nohrefStr=close" onsubmit="return CheckForm()">
	<input type="hidden" id="dataID" name="dataID" value="'. $dataID .'" />
	<input type="hidden" id="dataTypeCN" name="dataTypeCN" value="'. $dataTypeCN .'" />
	<input type="hidden" id="dataType" name="dataType" value="'. $dataType .'" />
	<input type="hidden" id="dataMode" name="dataMode" value="'. $dataMode .'" />
	<input type="hidden" id="dataModeStr" name="dataModeStr" value="'. $dataModeStr .'" />
	<input type="hidden" id="htmlInfoTypeDir" name="htmlInfoTypeDir" value="'. $systemArr['SYS_htmlInfoTypeDir'] .'" />
	');
	if ($backURL != ''){
		echo('<input type="hidden" id="backURL" name="backURL" value="'. $backURL .'" />');
	}else{
		echo('<script language="javascript" type="text/javascript">document.write(\'<input type="hidden" id="backURL" name="backURL" value="\'+ document.location.href +\'" />\')</script>');
	}

	echo('
	<div class="tabMenu">
	<ul>
	   <li rel="tabBase" class="selected">'. $mudiCN . $dataTypeCN .'</li>
	   <li rel="tabPc">电脑版设置</li>
	   <li id="wapBox" rel="tabWap" style="display:'. (AppWap::Jud() ? '' : 'none') .';">手机版设置</li>
	</ul>
	</div>

	<div class="tabMenuArea">
		<table id="tabBase" cellpadding="0" cellspacing="0" summary="" class="padd3">
		<tr><td style="width:150px;"></td><td></td></tr>
		<tr>
			<td align="right">'. Skin::RedSign() .'栏目名称：</td>
			<td align="left">
				<input type="text" id="theme" name="theme" size="50" style="width:400px;'. $IT_themeStyle .'" value="'. $IT_theme .'" />
				'. AppBase::InfoTypeThemeB($themeStyle_b,$themeStyle_color) .'
			</td>
		</tr>
		'. AppBase::InfoTypeTrBox5($IT_titleAddi,$IT_isTitle) .'
		<tr>
			<td align="right">栏目所属：</td>
			<td align="left">
				<input type="hidden" id="oldFatID" name="oldFatID" value="'. $IT_fatID .'" />
				<select id="fatID" name="fatID" onchange="CheckFatID()">
				<option value="">作为顶级分类</option>
				');
				$typeNum = 0;
				$typeexe=$DB->query('select * from '. OT_dbPref .'infoType where IT_level=1 order by IT_rank ASC');
				while ($row = $typeexe->fetch()){
					$typeNum ++;
					echo('<option value="'. $row['IT_ID'] .'" '. Is::Selected($row['IT_ID'],$IT_fatID) .'>'. $typeNum .'、'. $row['IT_theme'] .'</option>');
				
				}
				unset($typeexe);
				echo('
				</select>
			</td>
		</tr>
		<tr>
			<td align="right">打开方式：</td>
			<td align="left">
				<select id="openMode" name="openMode">
					<option value="_self" '. Is::Selected($IT_openMode,'_self') .'>_self（当前窗体打开）</option>
					<option value="_blank" '. Is::Selected($IT_openMode,'_blank') .'>_blank（新窗口打开）</option>
					<!-- <option value="_parent" '. Is::Selected($IT_openMode,'_parent') .'>_parent(在父窗体中打开链接)</option>
					<option value="_top" '. Is::Selected($IT_openMode,'_top') .'>_top(在当前窗体打开链接，并替换当前的整个窗体)</option> -->
				</select>
			</td>
		</tr>
		<tr>
			<td align="right">模式：</td>
			<td>
				<select id="mode" name="mode" onchange="CheckMode()">
					<option value="item">栏目</option>
					<option value="url" '. Is::Selected($IT_mode,'url') .'>外部链接(http://)</option>
					<option value="web" '. Is::Selected($IT_mode,'web') .'>单篇页</option>
					'. AppTopic::InfoTypeOptionBox1($IT_mode) .'
					'. AppTaobaoke::InfoTypeOptionBox1($IT_mode) .'
					'. AppIdcPro::InfoTypeOptionBox1($IT_mode) .'
					<option value="urlHome" '. Is::Selected($IT_mode,'urlHome') .'>●网站首页</option>
					<option value="urlMessage" '. Is::Selected($IT_mode,'urlMessage') .'>●留言板</option>
					'. AppGift::InfoTypeOptionBox1($IT_mode) .'
					'. AppBbs::InfoTypeOptionBox1($IT_mode) .'
					'. AppUserGroupWork::InfoTypeOptionBox1($IT_mode) .'
					'. AppQiandao::InfoTypeOptionBox1($IT_mode) .'
				</select>
			</td>
		</tr>
		<tr id="webBox">
			<td align="right">单篇页：</td>
			<td>
				<select id="webID" name="webID">
					<option value="">选择单篇页</option>
					<optgroup label="单篇页" style="font-weight:normal;"></optgroup>
					');
					$webexe=$DB->query("select IW_ID,IW_theme from ". OT_dbPref ."infoWeb where IW_type='newsWeb' and IW_mode='web' order by IW_rank ASC");
					if (! $row = $webexe->fetch()){
						echo('<option value="" style="color:#9d9d9d;">无记录，请到【常规设置】-【单篇页管理】里添加</option>');
					}else{
						do {
							echo('<option value="'. $row['IW_ID'] .'" '. Is::Selected($row['IW_ID'],$IT_webID) .'>&ensp;&ensp;'. $row['IW_theme'] .'</option>');
						}while ($row = $webexe->fetch());
					}
					unset($webexe);

					echo('
					<optgroup label="底部栏目" style="font-weight:normal;"></optgroup>
					');

					$webexe=$DB->query("select IW_ID,IW_theme from ". OT_dbPref ."infoWeb where IW_type='bottom' and IW_mode='web' order by IW_rank ASC");
					if (! $row = $webexe->fetch()){
						echo('<option value="" style="color:#9d9d9d;">无记录，请到【常规设置】-【底部栏目】里添加</option>');
					}else{
						do {
							echo('<option value="'. $row['IW_ID'] .'" '. Is::Selected($row['IW_ID'],$IT_webID) .'>&ensp;&ensp;'. $row['IW_theme'] .'</option>');
						}while ($row = $webexe->fetch());
					}
					unset($webexe);
				echo('
				</select>
			</td>
		</tr>
		<tbody id="topicBox">
		'. AppTopic::InfoTypeTrBox1($IT_webID) .'
		</tbody>
		<tbody id="taobaokeBox">
		'. AppTaobaoke::InfoTypeTrBox1($IT_webID) .'
		</tbody>
		<tbody id="idcProBox">
		'. AppIdcPro::InfoTypeTrBox1($IT_webID) .'
		</tbody>
		<tr id="urlBox">
			<td align="right" valign="top" style="padding-top:8px;">外部链接：</td>
			<td align="left">
				<input type="text" id="webURL" name="webURL" size="50" style="width:400px;" value="'. $IT_URL .'" />&ensp;
				'. AppTaobaoke::InfoTypeEncUrl($IT_isEncUrl) .'
				<!-- 快捷：
				<span class="pointer font1_2" onclick=\'InputToText("webURL","{%网站根路径%}bbs/")\'>[论坛]</span>&ensp; -->
				<div style="padding:3px;">
					<span class="pointer font2_2" onclick=\'InputAddText("webURL","{%网站根路径%}")\'>{%网站根路径%}</span>：前台网站根路径，如“./”&ensp;
				</div>
			</td>
		</tr>
		<tbody id="itemBox">
		<tr>
			<td align="right" valign="top" style="padding-top:6px;">
				栏目关键字(Keywords)：<br />
				<span class="font2_2">（便于搜索引擎搜到）</span>
			</td>
			<td align="left"><textarea id="webKey" name="webKey" rows="5" cols="40" style="width:400px; height:60px;">'. $IT_webKey .'</textarea>&ensp;'. $skin->TishiBox('多个用英文逗号“,”隔开') .'</td>
		</tr>
		<tr>
			<td align="right" valign="top" style="padding-top:6px;">
				栏目描述(Description)：<br />
				<span class="font2_2">（便于搜索引擎搜到）</span>
			</td>
			<td align="left"><textarea id="webDesc" name="webDesc" rows="5" cols="40" style="width:400px; height:60px;">'. $IT_webDesc .'</textarea>&ensp;'. $skin->TishiBox('星号“*”表示使用网站关键字/描述') .'</td>
		</tr>
		<tr>
			<td align="right">允许用户投稿：</td>
			<td align="left">
				<label><input type="radio" name="isUser" value="1" '. Is::Checked($IT_isUser,1) .' />是</label>&ensp;&ensp;&ensp;&ensp;
				<label><input type="radio" name="isUser" value="0" '. Is::Checked($IT_isUser,0) .' />否</label>&ensp;&ensp;&ensp;&ensp;
				'. $skin->TishiBox('如果有允许二级栏目，对应的一级栏目必须允许，不然不会显示二级栏目。') .'
			</td>
		</tr>
		<tr>
			<td align="right">'. Skin::PluSign('商业版基础包') .'静态目录名：</td>
			<td align="left">
				<input type="hidden" id="htmlNameOld" name="htmlNameOld" value="'. $IT_htmlName .'" />
				<input type="text" id="htmlName" name="htmlName" size="50" style="width:80px;ime-mode:disabled;" maxlength="25" value="'. $IT_htmlName .'" onkeyup=\'if (this.value!=FiltAbcNum_(this.value)){this.value=FiltAbcNum_(this.value)}\' />
				<span class="font2_2">&ensp;纯静态模式，并开启分栏目目录存放时用到。'. $skin->TishiBox('仅限数字、字母、下划线，第一个字符必须是字母。') .'</span>
			</td>
		</tr>
		<!-- <tr>
			<td align="right">静态目录名改动影响：</td>
			<td align="left">
				<select name="isChangeHtmlName">
					<option value="0">仅对后期添加文章生效</option>
					<option value="1">所属的文章全部生效</option>
				</select>
				<span class="font2_2">&ensp;（后期修改静态目录名时，是让之前的文章保持原路径还是采用新静态目录名）</span>
			</td>
		</tr> -->
		'. AppBase::InfoTypeTrBox4($IT_lookScore) .'
		</tbody>
		<tr>
			<td align="right">排序：</td>
			<td align="left"><input type="text" id="rank" name="rank" size="50" style="width:30px;" value="'. $IT_rank .'" /></td>
		</tr>
		<tr>
			<td align="right">状态：</td>
			<td align="left">
				<label><input type="radio" name="state" value="1" '. Is::Checked($IT_state,1) .' />显示</label>&ensp;&ensp;
				<label><input type="radio" name="state" value="0" '. Is::Checked($IT_state,0) .' />隐藏</label>&ensp;&ensp;
			</td>
		</tr>
		</table>


		<table id="tabPc" style="display:none;" cellpadding="0" cellspacing="0" summary="" class="padd3">
		<tr><td style="width:150px;"></td><td></td></tr>
		'. AppBase::InfoTypeTrBox2($IT_template) .'
		'. AdmArea::ListMode('列表页显示模式','showMode',$IT_showMode) .'
		<tr>
			<td align="right" class="font1_2d">列表页每页数量：</td>
			<td align="left"><input type="text" id="showNum" name="showNum" size="50" style="width:30px;" value="'. $IT_showNum .'" />&ensp;&ensp;'. $skin->TishiBox('如显示样式为[分类列表]，该文本框则表示每个分类显示的数量。') .'</td>
		</tr>
		<tr>
			<td align="right" class="font1_2d">列表页文章信息：</td>
			<td align="left">
				<label><input type="checkbox" name="listInfo[]" value="|noTime|" '. Is::InstrChecked($IT_listInfo,'|noTime|') .' />隐藏时间</label>&ensp;&ensp;
				<label><input type="checkbox" name="listInfo[]" value="|noReply|" '. Is::InstrChecked($IT_listInfo,'|noReply|') .' />隐藏评论数</label>&ensp;&ensp;
				<label><input type="checkbox" name="listInfo[]" value="|noRead|" '. Is::InstrChecked($IT_listInfo,'|noRead|') .' />隐藏阅读数</label>&ensp;&ensp;
				<label><input type="checkbox" name="listInfo[]" value="|noUser|" '. Is::InstrChecked($IT_listInfo,'|noUser|') .' />隐藏会员名</label>&ensp;&ensp;
				<label><input type="checkbox" name="listInfo[]" value="|noMark|" '. Is::InstrChecked($IT_listInfo,'|noMark|') .' />隐藏标签</label>&ensp;&ensp;
				<label><input type="checkbox" name="listInfo[]" value="|noInfoType|" '. Is::InstrChecked($IT_listInfo,'|noInfoType|') .' />隐藏栏目</label>&ensp;&ensp;
				<label><input type="checkbox" name="listInfo[]" value="|noWriter|" '. Is::InstrChecked($IT_listInfo,'|noWriter|') .' />隐藏作者</label>&ensp;&ensp;
				'. $skin->TishiBox('有些选项是针对特定模板') .'
			</td>
		</tr>
		<tr>
			<td align="right" class="font1_2d">列表页右侧最新文章数：</td>
			<td align="left"><input type="text" id="subNewNum" name="subNewNum" size="50" style="width:30px;" value="'. $IT_subNewNum .'" />&ensp;'. $skin->TishiBox('值为0会隐藏该模块') .'</td>
		</tr>
		<tr>
			<td align="right" class="font1_2d">列表页右侧推荐文章数：</td>
			<td align="left"><input type="text" id="subRecomNum" name="subRecomNum" size="50" style="width:30px;" value="'. $IT_subRecomNum .'" />&ensp;'. $skin->TishiBox('值为0会隐藏该模块') .'</td>
		</tr>
		<tr>
			<td align="right" class="font1_2d">列表页右侧热门文章数：</td>
			<td align="left"><input type="text" id="subHotNum" name="subHotNum" size="50" style="width:30px;" value="'. $IT_subHotNum .'" />&ensp;'. $skin->TishiBox('值为0会隐藏该模块') .'</td>
		</tr>
		'. AppBase::InfoTypeTrBox3($IT_isRightMenu) .'
		<tr>
			<td align="right">导航菜单：</td>
			<td align="left">
				<label><input type="radio" name="isMenu" value="1" '. Is::Checked($IT_isMenu,1) .' />显示</label>&ensp;&ensp;&ensp;&ensp;
				<label><input type="radio" name="isMenu" value="0" '. Is::Checked($IT_isMenu,0) .' />隐藏</label>&ensp;&ensp;&ensp;&ensp;
			</td>
		</tr>
		<tr id="subMenuBox" style="display:none;">
			<td align="right">导航二级菜单：</td>
			<td align="left">
				<label><input type="radio" name="isSubMenu" value="1" '. Is::Checked($IT_isSubMenu,1) .' />显示</label>&ensp;&ensp;&ensp;&ensp;
				<label><input type="radio" name="isSubMenu" value="0" '. Is::Checked($IT_isSubMenu,0) .' />隐藏</label>&ensp;&ensp;&ensp;&ensp;
			</td>
		</tr>
		<tr>
			<td align="right">首页栏目：</td>
			<td align="left">
				<label><input type="radio" id="isHome1" name="isHome" value="1" '. Is::Checked($IT_isHome,1) .' onclick="CheckHomeItme()" />显示</label>&ensp;&ensp;&ensp;&ensp;
				<label><input type="radio" id="isHome0" name="isHome" value="0" '. Is::Checked($IT_isHome,0) .' onclick="CheckHomeItme()" />隐藏</label>&ensp;&ensp;&ensp;&ensp;
			</td>
		</tr>
		<tr class="homeItemClass">
			<td align="right" class="font1_2d">首页栏目图标：</td>
			<td align="left" class="newsListImg">
				<label>
				<select id="homeIco" name="homeIco">
					<option value="0">默认</option>
					<option value="99">无</option>
					'. $icoOptionStr .'
				</select>
				<span><img src="temp/ico.png" style="display:none;" /></span>
				</label>
				&ensp;&ensp;'. $skin->TishiBox('图标文件存放在 inc_img/ico/，可自行替换，图标宽高要一致') .'
			</td>
		</tr>
		<tr class="homeItemClass">
			<td align="right" class="font1_2d">首页栏目图文数量：</td>
			<td align="left"><input type="text" id="isHomeImg" name="isHomeImg" size="50" style="width:30px;" value="'. $IT_isHomeImg .'" /></td>
		</tr>
		<tr class="homeItemClass">
			<td align="right" class="font1_2d">首页栏目文章数量：</td>
			<td align="left"><input type="text" id="homeNum" name="homeNum" size="50" style="width:30px;" value="'. $IT_homeNum .'" /></td>
		</tr>
		<tr class="homeItemClass">
			<td align="right" class="font1_2d">首页栏目日期：</td>
			<td align="left">
				<label><input type="radio" name="isItemDate" value="1" '. Is::Checked($IT_isItemDate,1) .' />显示</label>&ensp;&ensp;&ensp;&ensp;
				<label><input type="radio" name="isItemDate" value="0" '. Is::Checked($IT_isItemDate,0) .' />隐藏</label>&ensp;&ensp;&ensp;&ensp;
			</td>
		</tr>
		<tr class="homeItemClass">
			<td align="right" class="font1_2d">首页栏目分类：</td>
			<td align="left">
				<label><input type="radio" name="isItemType" value="1" '. Is::Checked($IT_isItemType,1) .' />显示</label>&ensp;&ensp;&ensp;&ensp;
				<label><input type="radio" name="isItemType" value="0" '. Is::Checked($IT_isItemType,0) .' />隐藏</label>&ensp;&ensp;&ensp;&ensp;
			</td>
		</tr>
		<tr>
			<td align="right" class="font1_2d">首页栏目排序：</td>
			<td align="left"><input type="text" id="itemRank" name="itemRank" size="50" style="width:30px;" value="'. $IT_itemRank .'" />&ensp;&ensp;'. $skin->TishiBox('值越小排越前，值相等，根据[排序]项升序。') .'</td>
		</tr>
		</table>


		<table id="tabWap" style="display:none;" cellpadding="0" cellspacing="0" summary="" class="padd3">
		<tr><td style="width:150px;"></td><td></td></tr>
		'. AppWap::InfoTypeTrBox2($IT_templateWap) .'
		'. AdmArea::WapListMode(Skin::ImgWap() .'列表页显示模式','wapsShowMode',$IT_wapsShowMode) .'
		<tr>
			<td align="right" class="font1_2d">'. Skin::ImgWap() .'列表页每页数量：</td>
			<td align="left"><input type="text" id="wapShowNum" name="wapShowNum" size="50" style="width:30px;" value="'. $IT_wapShowNum .'" /></td>
		</tr>
		<tr>
			<td align="right" class="font1_2d">'. Skin::ImgWap() .'列表页文章信息：</td>
			<td align="left">
				<label><input type="checkbox" name="listInfo[]" value="|noWapTime|" '. Is::InstrChecked($IT_listInfo,'|noWapTime|') .' />隐藏时间</label>&ensp;&ensp;
				<label><input type="checkbox" name="listInfo[]" value="|noWapReply|" '. Is::InstrChecked($IT_listInfo,'|noWapReply|') .' />隐藏评论数</label>&ensp;&ensp;
				<label><input type="checkbox" name="listInfo[]" value="|noWapRead|" '. Is::InstrChecked($IT_listInfo,'|noWapRead|') .' />隐藏阅读数</label>&ensp;&ensp;
				<label><input type="checkbox" name="listInfo[]" value="|noWapUser|" '. Is::InstrChecked($IT_listInfo,'|noWapUser|') .' />隐藏会员名</label>&ensp;&ensp;
			</td>
		</tr>
			<tr>
				<td align="right" class="font1_2d">'. Skin::ImgWap() .'列表页二级导航：</td>
				<td align="left">
					<label><input type="radio" name="isWapSubMenu" value="0" '. Is::Checked($IT_isWapSubMenu,0) .' />关闭</label>&ensp;&ensp;
					<label><input type="radio" name="isWapSubMenu" value="1" '. Is::Checked($IT_isWapSubMenu,1) .' />一行1个</label>&ensp;&ensp;
					<label><input type="radio" name="isWapSubMenu" value="2" '. Is::Checked($IT_isWapSubMenu,2) .' />一行2个</label>&ensp;&ensp;
					<label><input type="radio" name="isWapSubMenu" value="3" '. Is::Checked($IT_isWapSubMenu,3) .' />一行3个</label>&ensp;&ensp;
					<label><input type="radio" name="isWapSubMenu" value="4" '. Is::Checked($IT_isWapSubMenu,4) .' />一行4个</label>&ensp;&ensp;
				</td>
			</tr>
		<tr>
			<td align="right">'. Skin::ImgWap() .'导航菜单：</td>
			<td align="left">
				<label><input type="radio" name="isWap" value="1" '. Is::Checked($IT_isWap,1) .' />显示</label>&ensp;&ensp;&ensp;&ensp;
				<label><input type="radio" name="isWap" value="0" '. Is::Checked($IT_isWap,0) .' />隐藏</label>&ensp;&ensp;&ensp;&ensp;
			</td>
		</tr>
		<tr>
			<td align="right">'. Skin::ImgWap() .'首页栏目：</td>
			<td align="left">
				<label><input type="radio" id="isWapHome1" name="isWapHome" value="1" '. Is::Checked($IT_isWapHome,1) .' onclick="CheckWapItme()" />显示</label>&ensp;&ensp;&ensp;&ensp;
				<label><input type="radio" id="isWapHome0" name="isWapHome" value="0" '. Is::Checked($IT_isWapHome,0) .' onclick="CheckWapItme()" />隐藏</label>&ensp;&ensp;&ensp;&ensp;
			</td>
		</tr>
		<tr class="wapItemClass">
			<td align="right" class="font1_2d">'. Skin::ImgWap() .'首页栏目图文数量：</td>
			<td align="left"><input type="text" id="wapHomeImgNum" name="wapHomeImgNum" size="50" style="width:30px;" value="'. $IT_wapHomeImgNum .'" /></td>
		</tr>
		<tr class="wapItemClass">
			<td align="right" class="font1_2d">'. Skin::ImgWap() .'首页栏目文章数量：</td>
			<td align="left"><input type="text" id="wapHomeNum" name="wapHomeNum" size="50" style="width:30px;" value="'. $IT_wapHomeNum .'" /></td>
		</tr>
		<tr class="wapItemClass">
			<td align="right" class="font1_2d">'. Skin::ImgWap() .'首页栏目日期：</td>
			<td align="left">
				<label><input type="radio" name="isWapHomeDate" value="1" '. Is::Checked($IT_isWapHomeDate,1) .' />显示</label>&ensp;&ensp;&ensp;&ensp;
				<label><input type="radio" name="isWapHomeDate" value="0" '. Is::Checked($IT_isWapHomeDate,0) .' />隐藏</label>&ensp;&ensp;&ensp;&ensp;
			</td>
		</tr>
		<tr>
			<td align="right" class="font1_2d">'. Skin::ImgWap() .'首页栏目排序：</td>
			<td align="left"><input type="text" id="wapItemRank" name="wapItemRank" size="50" style="width:30px;" value="'. $IT_wapItemRank .'" />&ensp;&ensp;'. $skin->TishiBox('值越小排越前，值相等，根据[排序]项升序。') .'</td>
		</tr>
		</table>
	</div>

	<table style="height:16px;" cellpadding="0" cellspacing="0" summary=""><tr><td></td></tr></table>

	<center><input type="image" src="images/button_'. $mudi .'.gif" /></center>

	</form>
	');

}



function manage(){
	global $DB,$MB,$skin,$mudi,$dataType,$dataTypeCN,$pageCount,$recordCount,$systemArr,$dbPathPart;

	if (AppWap::Jud()){
		$tabWidthStr = '4%,5%,20%,8%,5%,5%,5%,5%,5%,6%,7%,7%,5%,12%';
		$tabTitleStr = '<input type="checkbox" id="selAll" name="selAll" onclick="CheckBoxAll()" />,ID号,名称,模式,打开<br />方式,用户<br />投稿,主<br />导航,二级<br />导航,WAP<br />导航,排序,PC首页<br />栏目/排序,WAP首页<br />栏目/排序,状态,预览　修改　删除';
		$app6StyleStr='display:;';
	}else{
		$tabWidthStr = '4%,5%,20%,9%,5%,6%,6%,6%,6%,8%,5%,12%';
		$tabTitleStr = '<input type="checkbox" id="selAll" name="selAll" onclick="CheckBoxAll()" />,ID号,名称,模式,打开<br />方式,用户<br />投稿,主<br />导航,二级<br />导航,排序,首页<br />栏目/排序,状态,预览　修改　删除';
		$app6StyleStr='display:none;';
	}

	echo('
	<div class="padd5">
		<input type="button" id="infoType2Btn" value="隐藏二级栏目" onclick="CheckInfoType(2)" />
	</div>

	<form id="listForm" name="listForm" method="post" action="infoType_deal.php?mudi=moreDel" onsubmit="return CheckListForm()">
	<script language="javascript" type="text/javascript">document.write (\'<input type="hidden" name="backURL" value="\'+ document.location.href +\'" />\')</script>
	<input type="hidden" name="dataType" value="'. $dataType .'" />
	<input type="hidden" name="dataTypeCN" value="'. $dataTypeCN .'" />
	');

	$skin->TableTop2('share_list.gif','',''. $dataTypeCN .'管理');
	$skin->TableItemTitle($tabWidthStr,$tabTitleStr);

	$infoTypeStr='';
	$infoTypeNum=0;
	$showNum=0;
	$showexe=$DB->query('select * from '. OT_dbPref .'infoType where IT_level=1 order by IT_rank ASC');
	echo('
	<tbody class="tabBody padd3">
	<tr>
		<td align="center"><input type="checkbox" name="selDataID[]" value="-1" /></td>
		<td align="center"><br /></td>
		<td align="left">0、网站公告('. $systemArr['SYS_announName'] .')&ensp;<span id="infoType_1" class="font2_2"></span><br /></td>
		<td align="center">栏目</td>
		<td align="center" colspan="11">名称修改：网站参数设置-页面设置-网站公告名称；栏目归属：新增/修改文章-栏目项；</td>
	</tr>
	</tbody>
	');
	while ($row = $showexe->fetch()){
		if ($infoTypeNum % 2 == 1){ $bgcolor='class="tabColorTr"'; }else{ $bgcolor=''; }
		$infoTypeStr .= ','. $row['IT_ID'];
		$infoTypeNum ++;
		$showNum ++;

		$modeArr = InfoTypeMode($row['IT_mode'], $row['IT_showMode'], $row['IT_ID'], $row['IT_webID'], $row['IT_URL']);
		echo('
		<tbody class="tabBody padd3">
		<tr id="data'. $row['IT_ID'] .'" '. $bgcolor .'>
			<td align="center"><input type="checkbox" name="selDataID[]" value="'. $row['IT_ID'] .'" /></td>
			<td align="center">'. $row['IT_ID'] .'</td>
			<td align="left">'. $showNum .'、<span style="'. $row['IT_themeStyle'] .'">'. $row['IT_theme'] .'</span>&ensp;<span id="infoType'. $row['IT_ID'] .'" class="font2_2"></span></td>
			<td align="center">'. $modeArr['modeCN'] .'</td>
			<td align="center">'. InfoTypeOpenMode($row['IT_openMode']) .'</td>
			<td align="center">'. Adm::SwitchBtn('infoType',$row['IT_ID'],$row['IT_isUser'],'isUser') .'</td>
			<td align="center">'. Adm::SwitchBtn('infoType',$row['IT_ID'],$row['IT_isMenu'],'isMenu') .'</td>
			<td align="center"></td>
			<td align="center" style="'. $app6StyleStr .'">'. Adm::SwitchBtn('infoType',$row['IT_ID'],$row['IT_isWap'],'isWap') .'</td>
			<td align="center">'. $row['IT_rank'] .'</td>
			<td align="center">'. Adm::SwitchBtn('infoType',$row['IT_ID'],$row['IT_isHome'],'isHome') .','. $row['IT_itemRank'] .'</td>
			<td align="center" style="'. $app6StyleStr .'">'. Adm::SwitchBtn('infoType',$row['IT_ID'],$row['IT_isWapHome'],'isWapHome') .','. $row['IT_wapItemRank'] .'</td>
			<td align="center">'. Adm::SwitchBtn('infoType',$row['IT_ID'],$row['IT_state'],'state') .'</td>
			<td align="center">
				<img src="images/img_det.gif" style="cursor:pointer" onclick=\'window.open("'. $modeArr['hrefStr'] .'")\' alt="" />&ensp;&ensp;
				<img src="images/img_rev.gif" class="pointer" onclick=\'document.location.href="infoType.php?mudi=rev&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&dataID='. $row['IT_ID'] .'&backURL="+ encodeURIComponent(document.location.href)\' alt="修改" />&ensp;&ensp;
				<img src="images/img_del.gif" class="pointer" onclick=\'if(confirm("确定删除？")==true){DataDeal.location.href="infoType_deal.php?mudi=del&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&theme='. urlencode($row['IT_theme']) .'&dataID='. $row['IT_ID'] .'"}\' alt="删除" />
			</td>
		</tr>
		</tbody>
		');

		$show2exe=$DB->query('select * from '. OT_dbPref .'infoType where IT_fatID='. $row['IT_ID'] .' order by IT_rank ASC');
		while ($row2 = $show2exe->fetch()){
			if ($infoTypeNum % 2 == 1){ $bgcolor='class="tabColorTr"'; }else{ $bgcolor=''; }
			$infoTypeStr .= ','. $row2['IT_ID'];
			$infoTypeNum ++;
			$modeArr = InfoTypeMode($row2['IT_mode'], $row2['IT_showMode'], $row2['IT_ID'], $row2['IT_webID'], $row2['IT_URL']);
			echo('
			<tbody class="infoType2 tabBody padd3">
			<tr id="data'. $row2['IT_ID'] .'" '. $bgcolor .'>
				<td align="center"><input type="checkbox" name="selDataID[]" value="'. $row2['IT_ID'] .'" /></td>
				<td align="center">'. $row2['IT_ID'] .'</td>
				<td align="left">&ensp;&ensp;&ensp;┣&ensp;<span style="'. $row2['IT_themeStyle'] .'">'. $row2['IT_theme'] .'</span>&ensp;<span id="infoType'. $row2['IT_ID'] .'" class="font2_2"></span></td>
				<td align="center">'. $modeArr['modeCN'] .'</td>
				<td align="center">'. InfoTypeOpenMode($row2['IT_openMode']) .'</td>
				<td align="center">'. Adm::SwitchBtn('infoType',$row2['IT_ID'],$row2['IT_isUser'],'isUser') .'</td>
				<td align="center">'. Adm::SwitchBtn('infoType',$row2['IT_ID'],$row2['IT_isMenu'],'isMenu') .'</td>
				<td align="center">'. Adm::SwitchBtn('infoType',$row2['IT_ID'],$row2['IT_isSubMenu'],'isSubMenu') .'</td>
				<td align="center" style="'. $app6StyleStr .'">'. Adm::SwitchBtn('infoType',$row2['IT_ID'],$row2['IT_isWap'],'isWap') .'</td>
				<td align="center">'. $row2['IT_rank'] .'</td>
				<td align="center">'. Adm::SwitchBtn('infoType',$row2['IT_ID'],$row2['IT_isHome'],'isHome') .','. $row2['IT_itemRank'] .'</td>
				<td align="center" style="'. $app6StyleStr .'">'. Adm::SwitchBtn('infoType',$row2['IT_ID'],$row2['IT_isWapHome'],'isWapHome') .','. $row2['IT_wapItemRank'] .'</td>
				<td align="center">'. Adm::SwitchBtn('infoType',$row2['IT_ID'],$row2['IT_state'],'state') .'</td>
				<td align="center">
					<img src="images/img_det.gif" style="cursor:pointer" onclick=\'window.open("'. $modeArr['hrefStr'] .'")\' alt="" />&ensp;&ensp;
					<img src="images/img_rev.gif" style="cursor:pointer;" onclick=\'document.location.href="infoType.php?mudi=rev&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&dataID='. $row2['IT_ID'] .'&backURL="+ encodeURIComponent(document.location.href)\' alt="修改" />&ensp;&ensp;
					<img src="images/img_del.gif" style="cursor:pointer;" onclick=\'if(confirm("确定删除？")==true){DataDeal.location.href="infoType_deal.php?mudi=del&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&theme='. urlencode($row2['IT_theme']) .'&dataID='. $row2['IT_ID'] .'"}\' alt="删除" />
				</td>
			</tr>
			<tbody>
			');
		}
		unset($show2exe);

	}
	unset($showexe);

	if ($infoTypeNum < $DB->GetOne('select count(IT_ID) from '. OT_dbPref .'infoType')){
		echo('
		<tr class="tabColor1">
			<td align="left" colspan="20" class="padd8">被遗弃的栏目</td>
		</tr>
		<tbody class="padd3">
		');
		$showexe=$DB->query('select * from '. OT_dbPref .'infoType where IT_ID not in ('. substr($infoTypeStr,1) .')');
		while ($row = $showexe->fetch()){
			$modeArr = InfoTypeMode($row['IT_mode'], $row['IT_showMode'], $row['IT_ID'], $row['IT_webID'], $row['IT_URL']);
			echo('
			<tr id="data'. $row['IT_ID'] .'" '. $bgcolor .'>
				<td align="center"><input type="checkbox" name="selDataID[]" value="'. $row['IT_ID'] .'" /></td>
				<td align="center">'. $row['IT_ID'] .'</td>
				<td align="left">'. $row['IT_theme'] .'&ensp;<span id="infoType'. $row['IT_ID'] .'" class="font2_2"></span></td>
				<td align="center">'. $modeArr['modeCN'] .'</td>
				<td align="center">'. InfoTypeOpenMode($row['IT_openMode']) .'</td>
				<td align="center">'. Adm::SwitchBtn('infoType',$row['IT_ID'],$row['IT_isUser'],'isUser') .'</td>
				<td align="center">'. Adm::SwitchBtn('infoType',$row['IT_ID'],$row['IT_isMenu'],'isMenu') .'</td>
				<td align="center">'. Adm::SwitchBtn('infoType',$row['IT_ID'],$row['IT_isSubMenu'],'isSubMenu') .'</td>
				<td align="center" style="'. $app6StyleStr .'">'. Adm::SwitchBtn('infoType',$row['IT_ID'],$row['IT_isWap'],'isWap') .'</td>
				<td align="center">'. $row['IT_rank'] .'</td>
				<td align="center">'. Adm::SwitchBtn('infoType',$row['IT_ID'],$row['IT_isHome'],'isHome') .'</td>
				<td align="center" style="'. $app6StyleStr .'">'. Adm::SwitchBtn('infoType',$row['IT_ID'],$row['IT_isWapHome'],'isWapHome') .'</td>
				<td align="center">'. $row['IT_itemRank'] .'</td>
				<td align="center">'. Adm::SwitchBtn('infoType',$row['IT_ID'],$row['IT_state'],'state') .'</td>
				<td align="center">
					<img src="images/img_det.gif" style="cursor:pointer" onclick=\'window.open("'. $modeArr['hrefStr'] .'")\' alt="" />&ensp;&ensp;
					<img src="images/img_rev.gif" style="cursor:pointer;" onclick=\'document.location.href="infoType.php?mudi=rev&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&dataID='. $row['IT_ID'] .'&backURL="+ encodeURIComponent(document.location.href)\' alt="修改" />&ensp;&ensp;
					<img src="images/img_del.gif" style="cursor:pointer;" onclick=\'if(confirm("确定删除？")==true){DataDeal.location.href="infoType_deal.php?mudi=del&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&theme='. urlencode($row['IT_theme']) .'&dataID='. $row['IT_ID'] .'"}\' alt="删除" />
				</td>
			</tr>
			');
		}
		unset($showexe);
		echo('
		</tbody>
		');
	}
	echo('
	<tr class="tabColorB padd5">
		<td align="left" colspan="20">
			<input type="button" value="全选" onclick="AllSelBox()" class="form_button1" />
			<input type="button" value="反选" onclick="RevSelBox()" class="form_button1" />
			<input type="submit" value="提交" class="display" />
			&ensp;&ensp;&ensp;&ensp;
			'. AppBase::InfoTypeStrBox1() .'
			<input type="button" value="批量统计栏目文章总数" onclick="MoreCountInfo()" />

		</td>
	</tr>
	</form>
	</table>
	<div class="padd5 font2_2" style="color:red;">提醒：1、网站首页、留言板 菜单添加方式，新增栏目-模式。<br />　　　2、[首页栏目排序]仅针对电脑版和手机版的首页栏目，升序排序；如果[首页栏目排序]值相同，按照[排序]升序排列。</div>
	');

}


function InfoTypeOpenMode($str){
	switch ($str){
		case '_self':	return '<span class="font1_2d">默认</span>';
		case '_blank':	return '新窗口';
		default :		return $str;
	}
}


function InfoTypeMode($mode, $showMode, $id, $webID, $url){
	global $dbPathPart;

	$modeCN = $hrefStr = '';
	switch ($mode){
		case 'item':		$modeCN='栏目'. ShowModeCN($showMode);		$hrefStr='../news/?list_'. $id .'.html';	break;
		case 'url':			$modeCN='外部链接';		$hrefStr=Url::ListUrl($mode,$url,0,0,$dbPathPart);		break;
		case 'web':			$modeCN='单篇页';		$hrefStr='../news/?web_'. $webID .'.html';				break;
		case 'topic':		$modeCN='专题';			$hrefStr='../news/?list_topic-'. $webID .'.html';		break;
		case 'taobaoke':	$modeCN='淘客商品';		$hrefStr='../goods/?list_'. $webID .'.html';			break;
		case 'idcPro':		$modeCN='IDC产品';		$hrefStr='../idcPro.php?dataID='. $webID .'';			break;
		case 'urlHome':		$modeCN='网站首页';		$hrefStr=Url::ListUrl($mode,$url,0,0,$dbPathPart);		break;
		case 'urlMessage':	$modeCN='留言板';		$hrefStr=Url::ListUrl($mode,$url,0,0,$dbPathPart);		break;
		case 'urlBbs':		$modeCN='论坛';			$hrefStr=Url::ListUrl($mode,$url,0,0,$dbPathPart);		break;
		case 'urlGift':		$modeCN='积分商城';		$hrefStr=Url::ListUrl($mode,$url,0,0,$dbPathPart);		break;
		case 'urlUserWork': $modeCN='领工资';	$hrefStr=Url::ListUrl($mode,$url,0,0,$dbPathPart);		break;
		case 'urlQiandao':	$modeCN='签到';			$hrefStr=Url::ListUrl($mode,$url,0,0,$dbPathPart);		break;
		default: 			$modeCN='['. mode .']';	$hrefStr=Url::ListUrl($mode,$url,0,0,$dbPathPart);		break;
	}

	return array('modeCN'=>$modeCN, 'hrefStr'=>$hrefStr);
}

function ShowModeCN($showMode){
	$retStr = '';
	switch ($showMode){
		case 1:		$retStr = '标+摘';	break;
		case 2:		$retStr = '图+摘1';	break;
		case 3:		$retStr = '图+标';	break;
		case 4:		$retStr = '图+摘2';	break;
		case 5:		$retStr = '分类';	break;
		case 6:		$retStr = '分类2';	break;
		case 7:		$retStr = '标题';	break;
	}
	if (strlen($retStr) > 0){
		return '<span style="color:#9a9999;">('. $retStr .')</span>';
	}
}

?>
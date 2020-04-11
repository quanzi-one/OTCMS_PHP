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
<script language="javascript" type="text/javascript" src="js/ca.js?v='. OT_VERSION .'"></script>
');



switch ($mudi){
	case 'add': case 'rev':
		$MB->IsSecMenuRight('alertBack',147,$dataType);
		AddOrRev();
		break;

	case 'manage':
		$MB->IsSecMenuRight('alertBack',146,$dataType);
		manage();
		break;

	default:
		die('err');
}

$skin->WebBottom();

$MB->Close();
$DB->Close();





// 新增、修改信息
function AddOrRev(){
	global $DB,$MB,$skin,$mudi,$dataType,$dataTypeCN,$systemArr;

	$dataMode		= OT::GetStr('dataMode');
	$dataModeStr	= OT::GetStr('dataModeStr');
	$backURL		= OT::GetStr('backURL');
	$dataID			= OT::GetInt('dataID');

	$noAdStr = '';
	$existAdArr[] = '[编号23](全栏广告位)<br />全站顶部通栏【1036*X】';
	$existAdArr[] = '[编号21](logo+1广告位)<br />全站顶部右侧【728*60】';
	$existAdArr[] = '[编号3]全站导航下方图片通栏【1036*X】';
	$existAdArr[] = '[编号4]全站导航下方文字通栏【1036*X】';
	$existAdArr[] = '[编号18]首页幻灯下方【300*X】';
	$existAdArr[] = '[编号5]首页中部通栏【1036*X】';
	$existAdArr[] = '[编号14]首页右侧上部【300*X】';
	$existAdArr[] = '[编号8]首页右侧中部【300*X】';
	$existAdArr[] = '[编号20]首页右侧中下部【300*X】';
	$existAdArr[] = '[编号9]首页右侧下部【300*X】';
	$existAdArr[] = '[编号16]内容页摘要上方【716*60】';
	$existAdArr[] = '[编号17]内容页正文左边';
	$existAdArr[] = '[编号22]内容页正文分页上面【700*X】';
	$existAdArr[] = '[编号10]内容页正文下面【700*X】';
	$existAdArr[] = '[编号24]内容页投票下面【700*X】';
	$existAdArr[] = '[编号25]内容页相关文章上面【700*X】';
	$existAdArr[] = '[编号26]内容页相关评论上面【700*X】';
	$existAdArr[] = '[编号11]列表页、内容页右侧上部【300*X】';
	$existAdArr[] = '[编号15]列表页、内容页右侧中部【300*X】';
	$existAdArr[] = '[编号12]列表页、内容页右侧下部【300*X】';
	$existAdArr[] = '[编号13]全站尾部通栏【1036*X】';
	$existAdArr[] = '[编号19]弹窗和富媒体';
	$existAdArr[] = '[编号51]首页栏目1【728*X或1036*X】';
	$existAdArr[] = '[编号52]首页栏目2【728*X或1036*X】';
	$existAdArr[] = '[编号53]首页栏目3【728*X或1036*X】';
	$existAdArr[] = '[编号54]首页栏目4【728*X或1036*X】';
	$existAdArr[] = '[编号55]首页栏目5【728*X或1036*X】';
	$existAdArr[] = '[编号56]首页栏目6【728*X或1036*X】';
	$existAdArr[] = '[编号57]首页栏目7【728*X或1036*X】';
	$existAdArr[] = '[编号58]首页栏目8【728*X或1036*X】';
	$existAdArr[] = '[编号59]首页栏目9【728*X或1036*X】';
	$existAdArr[] = '[编号60]首页栏目10【728*X或1036*X】';
	$existAdArr[] = '[编号61]列表页分类列表1【728*X】';
	$existAdArr[] = '[编号62]列表页分类列表2【728*X】';
	$existAdArr[] = '[编号63]列表页分类列表3【728*X】';
	$existAdArr[] = '[编号64]列表页分类列表4【728*X】';
	$existAdArr[] = '[编号65]列表页分类列表5【728*X】';
	$existAdArr[] = '[编号66]列表页分类列表6【728*X】';
	$existAdArr[] = '[编号67]列表页分类列表7【728*X】';
	$existAdArr[] = '[编号68]列表页分类列表8【728*X】';
	$existAdArr[] = '[编号69]列表页分类列表9【728*X】';
	$existAdArr[] = '[编号70]列表页分类列表10【728*X】';

	if (AppWap::Jud()){
		$existAdArr[] = '[编号101]WAP手机版页头';
		$existAdArr[] = '[编号102]WAP手机版页尾';
		$existAdArr[] = '[编号103]WAP手机版弹窗富媒体';
		$existAdArr[] = '[编号104]WAP手机版最新消息底部';
		$existAdArr[] = '[编号105]WAP手机版精彩推荐底部';
		$existAdArr[] = '[编号151]WAP手机版首页栏目1';
		$existAdArr[] = '[编号152]WAP手机版首页栏目2';
		$existAdArr[] = '[编号153]WAP手机版首页栏目3';
		$existAdArr[] = '[编号154]WAP手机版首页栏目4';
		$existAdArr[] = '[编号155]WAP手机版首页栏目5';
		$existAdArr[] = '[编号156]WAP手机版首页栏目6';
		$existAdArr[] = '[编号157]WAP手机版首页栏目7';
		$existAdArr[] = '[编号158]WAP手机版首页栏目8';
		$existAdArr[] = '[编号159]WAP手机版首页栏目9';
		$existAdArr[] = '[编号160]WAP手机版首页栏目10';
		$existAdArr[] = '[编号106]WAP手机版内容页正文头部';
		$existAdArr[] = '[编号107]WAP手机版内容页正文尾部';
		$existAdArr[] = '[编号108]WAP手机版内容页相关文章区';
		$existAdArr[] = '[编号109]WAP手机版内容页相关评论区';
		$existAdArr[] = '[编号110]WAP手机版网站公告底部';
		$existAdArr[] = '[编号111]WAP手机版热门文章底部';
		$existAdArr[] = '[编号112]WAP手机版滚图/图文底部';
	}

	$existIdStr = '';
	$showexe=$DB->query('select AD_num from '. OT_dbPref .'ad');
	while ($row = $showexe->fetch()){
		$existIdStr .= '['. $row['AD_num'] .']';
	}
	unset($showexe);

	foreach ($existAdArr as $val){
		if (strpos($existIdStr,'['. str_replace('[编号', '', substr($val,0,strpos($val,']'))) .']') === false){
			$noAdStr .= '<option value="'. $val .'">'. $val .'</option>';
		}
	}
	if (strlen($noAdStr)>0){ $noAdStr = '&ensp;&ensp;&ensp;&ensp;<select id="selAdId" name="selAdId" onchange="SelAdId()"><option value="">快捷选择未添加的内置广告位</option>'. $noAdStr .'</select>'; }

	if ($mudi == 'rev'){
		$revexe=$DB->query('select * from '. OT_dbPref .'ad where AD_ID='. $dataID);
			if (! $row = $revexe->fetch()){
				JS::AlertBackEnd('无该记录！');
			}
			$AD_num			= $row['AD_num'];
			$AD_theme		= $row['AD_theme'];
			$AD_code		= $row['AD_code'];
				$beforeURL = GetUrl::CurrDir(1);
				$imgUrl = $beforeURL . InfoImgDir;
				$AD_code	= str_replace(InfoImgAdminDir, $imgUrl, ''. $AD_code);
			$AD_upImgStr	= $row['AD_upImgStr'];
			$AD_divStyle	= $row['AD_divStyle'];
			$AD_areaStr		= $row['AD_areaStr'];
			$AD_width		= $row['AD_width'];
			$AD_height		= $row['AD_height'];
			$AD_isTime		= $row['AD_isTime'];
			$AD_startTime	= $row['AD_startTime'];
			$AD_endTime		= $row['AD_endTime'];
			$AD_price		= $row['AD_price'];
			$AD_rank		= $row['AD_rank'];
			$AD_state		= $row['AD_state'];
		unset($revexe);

		$mudiCN='修改';
	}else{
		$AD_num			= intval($DB->GetOne('select max(AD_num) from '. OT_dbPref .'ad'))+1;
		$AD_theme		= '';
		$AD_code		= '';
		$AD_upImgStr	= '';
		$AD_divStyle	= '';
		$AD_areaStr		= '';
		$AD_width		= '';
		$AD_height		= '';
		$AD_isTime		= '';
		$AD_startTime	= TimeDate::Get();
		$AD_endTime		= '';
		$AD_price		= '';
		$AD_rank		= intval($DB->GetOne('select max(AD_rank) from '. OT_dbPref .'ad'))+10;
		$AD_state		= 1;

		$mudiCN='添加';
	}

	$divStyle_top		= intval(Str::GetMark($AD_divStyle,'margin-top:','px'));
	$divStyle_right		= intval(Str::GetMark($AD_divStyle,'margin-right:','px'));
	$divStyle_bottom	= intval(Str::GetMark($AD_divStyle,'margin-bottom:','px'));
	$divStyle_left		= intval(Str::GetMark($AD_divStyle,'margin-left:','px'));
	$divStyle_textAlign	= Str::GetMark($AD_divStyle,'text-align:',';');

	if ($mudi=='rev'){
		echo('<div onclick="history.back();" class="font2_1 padd8 pointer">&lt;&lt;&ensp;【返回上级】</div>');
	}

	echo('
	<form id="dealForm" name="dealForm" method="post" action="ca_deal.php?mudi='. $mudi .'&nohrefStr=close" onsubmit="return CheckForm()">
	<input type="hidden" id="dataID" name="dataID" value="'. $dataID .'" />
	<input type="hidden" id="dataType" name="dataType" value="'. $dataType .'" />
	<input type="hidden" id="dataTypeCN" name="dataTypeCN" value="'. $dataTypeCN .'" />
	<input type="hidden" id="dataMode" name="dataMode" value="'. $dataMode .'" />
	<input type="hidden" id="dataModeStr" name="dataModeStr" value="'. $dataModeStr .'" />
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
			<td width="12%"></td>
			<td width="88%"></td>
		</tr>
		<tr>
			<td align="right">编号：</td>
			<td align="left">
				<input type="text" id="num" name="num" size="50" style="width:50px;" value="'. $AD_num .'" />
				'. $noAdStr .'
			</td>
		</tr>
		<tr>
			<td align="right">'. Skin::RedSign() .'广告位置：</td>
			<td align="left">
				<input type="text" id="theme" name="theme" size="50" style="width:400px;" value="'. Str::MoreReplace($AD_theme,'input') .'" />
			</td>
		</tr>
		<tr>
			<td align="right" valign="top" style="padding-top:6px;">'. Skin::RedSign() .'广告内容：</td>
			<td align="left">
			<span style="color:red;">点击↓<b>[源码]</b>，即可在<b>代码模式</b>和<b>可视化编辑模式</b>切换编辑</span>
			<textarea id="code" name="code" cols="40" rows="4" style="width:650px;height:350px;" class="text" onclick=\'LoadEditor("code",0,0,"|source|");\' title="点击开启编辑器模式">'. Str::MoreReplace($AD_code,'textarea') .'</textarea>
			<script language="javascript" type="text/javascript">LoadEditor("code",0,0,"|source|");</script>
			<div>
			<input type="hidden" id="upImgStr" name="upImgStr" value="'. $AD_upImgStr .'" />
			<input type="button" onclick=\'OT_OpenUpImg("editor","code","")\' value="上传图片载入编辑器" title="切换到可视化编辑模式才可上传图片后反馈到编辑器里" />
			<span style="color:red;">(切换到<b>可视化编辑模式</b>才可上传图片后反馈到编辑器里)</span>
			</div>
			');
			if ($AD_num == 4){
				echo('
				<div><input type="button" value="文字广告范例" onclick="DefAdContent('. $AD_num .')" /><span style="color:red;">(切换到<b>可视化编辑模式</b>才可使用)</span></div>
				');
			}
			echo('
			</td>
		</tr>
		<tr>
			<td align="right">外框间距：</td>
			<td align="left">
				上<input type="text" id="divStyle_top" name="divStyle_top" size="5" style="width:25px;" onkeyup="if (this.value!=FiltInt(this.value)){this.value=FiltInt(this.value)}" value="'. $divStyle_top .'" />&ensp;&ensp;&ensp;&ensp;
				右<input type="text" id="divStyle_right" name="divStyle_right" size="5" style="width:25px;" onkeyup="if (this.value!=FiltInt(this.value)){this.value=FiltInt(this.value)}" value="'. $divStyle_right .'" />&ensp;&ensp;&ensp;&ensp;
				下<input type="text" id="divStyle_bottom" name="divStyle_bottom" size="5" style="width:25px;" onkeyup="if (this.value!=FiltInt(this.value)){this.value=FiltInt(this.value)}" value="'. $divStyle_bottom .'" />&ensp;&ensp;&ensp;&ensp;
				左<input type="text" id="divStyle_left" name="divStyle_left" size="5" style="width:25px;" onkeyup="if (this.value!=FiltInt(this.value)){this.value=FiltInt(this.value)}" value="'. $divStyle_left .'" />&ensp;&ensp;&ensp;&ensp;
				<!-- &ensp;&ensp;&ensp;&ensp;内容<select id="divStyle_textAlign" name="divStyle_textAlign"></select>&ensp;&ensp;&ensp;&ensp; -->
			</td>
		</tr>
		<tr>
			<td align="right">显示区域：</td>
			<td align="left">
				<label><input type="checkbox" name="areaStr[]" value="[home]" '. Is::InstrChecked($AD_areaStr,'[home]') .'>首页</label>&ensp;&ensp;
				<label><input type="checkbox" name="areaStr[]" value="[list]" '. Is::InstrChecked($AD_areaStr,'[list]') .'>列表页</label>&ensp;&ensp;
				<label><input type="checkbox" name="areaStr[]" value="[show]" '. Is::InstrChecked($AD_areaStr,'[show]') .'>内容页</label>&ensp;&ensp;
				<label><input type="checkbox" name="areaStr[]" value="[message]" '. Is::InstrChecked($AD_areaStr,'[message]') .'>留言板</label>&ensp;&ensp;
				<label><input type="checkbox" name="areaStr[]" value="[web]" '. Is::InstrChecked($AD_areaStr,'[web]') .'>单篇页</label>&ensp;&ensp;
				<span style="display:'. (AppTaobaoke::Jud() ? '' : 'none') .';">
					<label><input type="checkbox" name="areaStr[]" value="[goodsList]" '. Is::InstrChecked($AD_areaStr,'[goodsList]') .'>淘客商品列表'. Area::PluSign('淘宝客基础包','bottom') .'</label>&ensp;&ensp;
				</span>
				<span style="display:'. (AppBbs::Jud() ? '' : 'none') .';">
					<!-- <label><input type="checkbox" name="areaStr[]" value="[bbsHome]" '. Is::InstrChecked($AD_areaStr,'[bbsHome]') .'>论坛首页'. Area::PluSign('简易小论坛','bottom') .'</label>&ensp;&ensp; -->
					<label><input type="checkbox" name="areaStr[]" value="[bbsList]" '. Is::InstrChecked($AD_areaStr,'[bbsList]') .'>论坛列表页'. Area::PluSign('简易小论坛','bottom') .'</label>&ensp;&ensp;
					<label><input type="checkbox" name="areaStr[]" value="[bbsShow]" '. Is::InstrChecked($AD_areaStr,'[bbsShow]') .'>论坛内容页'. Area::PluSign('简易小论坛','bottom') .'</label>&ensp;&ensp;
					<label><input type="checkbox" name="areaStr[]" value="[bbsWrite]" '. Is::InstrChecked($AD_areaStr,'[bbsWrite]') .'>论坛发帖页'. Area::PluSign('简易小论坛','bottom') .'</label>&ensp;&ensp;
				</span>
				<div style="color:red;">（该功能仅作用于多区域显示的广告位，全不选=全显示）</div>
			</td>
		</tr>
		<tr>
			<td align="right">开始时间：</td>
			<td align="left"><input type="text" id="startTime" name="startTime" size="22" style="width:160px;" value="'. $AD_startTime .'" onfocus=\'WdatePicker({dateFmt:"yyyy-MM-dd HH:mm:ss"})\' class="Wdate" /></td>
		</tr>
		<tr>
			<td align="right">结束时间：</td>
			<td align="left">
				<input type="text" id="endTime" name="endTime" size="22" style="width:160px;" value="'. $AD_endTime .'" onfocus=\'WdatePicker({dateFmt:"yyyy-MM-dd HH:mm:ss"})\' class="Wdate" />
				&ensp;<span class="font2_2">仅供查看，无实际限制作用</span>
			</td>
		</tr>
		<tr>
			<td align="right">价格：</td>
			<td align="left"><input type="text" id="price" name="price" size="50" style="width:50px;" value="'. $AD_price .'" /> 元</td>
		</tr>
		<tr>
			<td align="right">排序：</td>
			<td align="left"><input type="text" id="rank" name="rank" size="50" style="width:50px;" value="'. $AD_rank .'" /></td>
		</tr>
		<tr>
			<td align="right">状态：</td>
			<td align="left">
				<label><input type="radio" name="state" value="1" '. Is::Checked($AD_state,1) .' />显示</label>&ensp;&ensp;
				<label><input type="radio" name="state" value="0" '. Is::Checked($AD_state,0) .' />隐藏</label>&ensp;&ensp;
			</td>
		</tr>
		</table>
		<div style="padding:10px;" class="font2_2">提醒：由于后续官方会增加广告位，所以个人自定义广告建议从编号500开始，以防到时跟官方冲突了。</div>
		');

	$skin->TableBottom();

	echo('
	<table style="height:16px;" cellpadding="0" cellspacing="0" summary=""><tr><td></td></tr></table>

	<center><input type="image" src="images/button_'. $mudi .'.gif" /></center>

	</form>
	');
}



function manage(){
	global $DB,$MB,$skin,$mudi,$dataType,$dataTypeCN,$pageCount,$recordCount,$systemArr,$dbPathPart;

	$dataMode		= OT::GetStr('dataMode');
	$dataModeStr	= OT::GetStr('dataModeStr');

	echo('
	<div class="padd8" style="line-height:1.4;">
		<b style="color:red;">广告位具体位置说明：</b><a href="http://otcms.com/news/3092.html" target="_blank" class="font1_2">http://otcms.com/news/3092.html</a><br />
		点击【前台广告位提示】后，打开前台，可看到所有广告位置说明，再点击【更新广告缓存】，就恢复正常。
	</div>

	<div class="padd5">
		<input type="button" value="新增广告位" onclick=\'document.location.href="ca.php?mudi=add&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&dataMode='. $dataMode .'&dataModeStr='. $dataModeStr .'&backURL="+ encodeURIComponent(document.location.href) +"";\' />&ensp;&ensp;&ensp;&ensp;
		<input type="button" value="全部显示" onclick=\'document.location.href="ca_deal.php?mudi=stateUpdate&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&dataMode='. $dataMode .'&dataModeStr='. $dataModeStr .'&backURL="+ encodeURIComponent(document.location.href) +"&state=1";\' />&ensp;
		<input type="button" value="全部隐藏" onclick=\'document.location.href="ca_deal.php?mudi=stateUpdate&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&dataMode='. $dataMode .'&dataModeStr='. $dataModeStr .'&backURL="+ encodeURIComponent(document.location.href) +"&state=0";\' />&ensp;
		&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;
		<input type="button" value="更新广告缓存" onclick=\'document.location.href="ca_deal.php?mudi=cacheUpdate&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&dataMode='. $dataMode .'&dataModeStr='. $dataModeStr .'&backURL="+ encodeURIComponent(document.location.href) +"&state=0";\' />&ensp;
		<input type="button" value="前台广告位置提示" onclick=\'document.location.href="ca_deal.php?mudi=cacheDefUpdate&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&dataMode='. $dataMode .'&dataModeStr='. $dataModeStr .'&backURL="+ encodeURIComponent(document.location.href) +"&state=0";\' />&ensp;
	</div>
	');

	if (AppWap::Jud()){
		$sqlWhereStr = '';
	}else{
		$sqlWhereStr = ' and (AD_num<=100 or AD_num>=200)';
	}

	$skin->TableTop2('share_list.gif','','正在使用的广告位');
	$skin->TableItemTitle('6%,24%,25%,5%,9%,9%,5%,5%,12%','编号,广告位置,广告内容,价格,开始时间,结束时间,排序,状态,JS码　修改　删除');

	echo('
	<tbody class="tabBody padd3">
	');
	
	$TS_topLogoMode = $DB->GetOne('select TS_topLogoMode from '. OT_dbPref .'tplSys');
	$todayDate = TimeDate::Get('date');
	$number=1;
	$showexe=$DB->query('select * from '. OT_dbPref .'ad where AD_state=1'. $sqlWhereStr .' order by AD_rank ASC');
	while ($row = $showexe->fetch()){
		if ($number % 2 == 0){ $bgcolor='class="tabColorTr"'; }else{ $bgcolor=''; }

		$endDateStr = '';
		if (strtotime($row['AD_endTime'])){
			if ($row['AD_endTime'] < '2029-12-31'){
				$endDateStr = $row['AD_endTime'] .'<br />';
				if ($row['AD_endTime']>=$todayDate){
					$endDateDiff = TimeDate::Diff('d',TimeDate::Get(),$row['AD_endTime'])+1;
					if ($endDateDiff == 1){
						$endDateStr .= '<span class="font2_2">(只剩今天)</span>';
					}else{
						$endDateStr .= '<span class="font2_2">(还剩'. $endDateDiff .'天)</span>';
					}
				}else{
					$endDateStr .= '<span class="font2_2">(已过期)</span>';
				}
			}else{
				$endDateStr = '永久';
			}
		}else{
			$endDateStr = '<br />';
		}

		$themeAlertStr = '';
		$revBtnStr = '<img src="images/img_rev.gif" class="pointer" onclick=\'document.location.href="ca.php?mudi=rev&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&dataMode='. $dataMode .'&dataModeStr='. $dataModeStr .'&dataID='. $row['AD_ID'] .'&typeNum='. $number .'&backURL="+ encodeURIComponent(document.location.href) +""\' alt="修改" />';

		if ($row['AD_num'] == 21 && $TS_topLogoMode != 2){
			$themeAlertStr='<div style="color:red;">(非logo+1广告位模式，无法使用)</div>';
			$revBtnStr='<img src="images/img_rev.gif" class="gray" alt="当前模式无法修改" onclick=\'alert("非logo+1广告位模式，无法修改。请到【模板参数设置】-[页头布局设置]：页头logo模式 设置开启");\' />';
		}elseif ($row['AD_num'] == 23 && $TS_topLogoMode != 4){
			$themeAlertStr='<div style="color:red;">(非全栏广告位模式，无法使用)</div>';
			$revBtnStr='<img src="images/img_rev.gif" class="gray" alt="当前模式无法修改" onclick=\'alert("非全栏广告位模式，无法修改。请到【模板参数设置】-[页头布局设置]：页头logo模式 设置开启");\' />';
		}
			
		echo('
		<tr id="data'. $row['AD_ID'] .'" '. $bgcolor .'>
			<td align="center">'. $row['AD_num'] .'</td>
			<td align="left">'. $row['AD_theme'] . $themeAlertStr .'</td>
			<td align="center">
				<textarea id="jsCode'. $row['AD_ID'] .'" name="jsCode'. $row['AD_ID'] .'" style="width:98%;height:50px;display:none;" readonly="true" title="点击复制"><script type="text/javascript">OTca("ot'. Str::FixLen($row['AD_num'],3) .'");</script></textarea>
				<div><a href="#" class="font1_2" onclick="ShowCode('. $row['AD_ID'] .');return false;">点击查看代码</a></div>
				<div id="code'. $row['AD_ID'] .'" style="display:none;">
					<div style="color:red;">仅供查看，如需修改请点击右侧修改按钮</div>
					<textarea style="width:230px; height:200px;" readonly="true" title="该区域仅供查看，如需修改请点击右侧修改按钮">'. Str::MoreReplace($row['AD_code'],'textarea') .'</textarea>
				</div>
				'. AreaStrCN($row['AD_areaStr']) .'
			</td>
			<td align="center">'. $row['AD_price'] .'</td>
			<td align="center">'. $row['AD_startTime'] .'<br /></td>
			<td align="center">'. $endDateStr .'</td>
			<td align="center">'. $row['AD_rank'] .'</td>
			<td align="center">'. Adm::SwitchBtn('ad',$row['AD_ID'],$row['AD_state'],'state') .'</td>
			<td align="center">
				<img src="images/img_copy.gif" class="pointer" onclick=\'ValueToCopy("jsCode'. $row['AD_ID'] .'");\' alt="复制JS调用代码" />&ensp;&ensp;
				'. $revBtnStr .'&ensp;&ensp;
				<img src="images/img_del.gif" class="pointer" onclick=\'if(confirm("确定删除？")==true){DataDeal.location.href="ca_deal.php?mudi=del&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&theme='. urlencode($row['AD_theme']) .'&dataID='. $row['AD_ID'] .'"}\' alt="" />
			</td>
		</tr>
		');
		$number ++;
	}
	
	echo('
	</tbody>
	<tr class="tabColorB">
		<td colspan="20" align="center" style="padding:8px;">
			有效广告：<span style="color:blue;font-weight:bold;">'. intval($DB->GetOne('select count(AD_ID) from '. OT_dbPref .'ad where AD_state=1 and AD_endTime>='. $DB->ForTime($todayDate) .'')) .'</span>条，
			收费广告：<span style="color:blue;font-weight:bold;">'. intval($DB->GetOne('select count(AD_ID) from '. OT_dbPref .'ad where AD_state=1 and AD_endTime>='. $DB->ForTime($todayDate) .' and AD_price>0')) .'</span>条，
			总费用：<span style="color:red;font-weight:bold;">'. floatval($DB->GetOne('select sum(AD_price) from '. OT_dbPref .'ad where AD_state=1 and AD_endTime>='. $DB->ForTime($todayDate) .'')) .'</span>元
		</td>
	</tr>
	');
	unset($showexe);

	echo('
	</table>
	<div class="padd5" style="color:red;line-height:1.4;margin-bottom:20px;">
		提醒：1、由于浏览器有缓存，如果关闭隐藏后前台还显示广告，请清空浏览器缓存或者换个新浏览器访问即可看到最新效果。<br />
		　　　2、WAP开头的广告位是针对手机版预留选项，如果你没购买手机版插件，无任何作用。<br />
		　　　3、广告位仅是提供放广告的空间位置，不具有任何特效。编号19【弹窗和富媒体】，仅是把空间位置设在页尾，这样才能把页面打开速度影响降到最低，本身是不具有弹窗浮窗等特效功能。
	</div>
	');

	$skin->TableTop2('share_list.gif','','停用的广告位');
	$skin->TableItemTitle('6%,24%,25%,5%,9%,9%,5%,5%,12%','编号,广告位置,广告内容,价格,开始时间,结束时间,排序,状态,JS码　修改　删除');

	echo('
	<tr><td id="adStopStr" colspan="9" class="padd8 pointer" align="center" style="font-size:16px;font-weight:bold;color:red;" onclick="CheckStopBox();">点击显示【停用的广告位'. intval($DB->GetOne('select count(AD_ID) from '. OT_dbPref .'ad where AD_state=0'. $sqlWhereStr .'')) .'个】</td></tr>
	<tbody id="adStopBox" class="tabBody padd3" style="display:none;">
	');

	$showexe=$DB->query('select * from '. OT_dbPref .'ad where AD_state=0'. $sqlWhereStr .' order by AD_rank ASC');
	while ($row = $showexe->fetch()){
		if ($number % 2 == 0){ $bgcolor='class="tabColorTr"'; }else{ $bgcolor=''; }

		$endDateStr = '';
		if (strtotime($row['AD_endTime'])){
			if ($row['AD_endTime'] < '2029-12-31'){
				$endDateStr = $row['AD_endTime'] .'<br />';
				if ($row['AD_endTime']>=$todayDate){
					$endDateDiff = TimeDate::Diff('d',TimeDate::Get(),$row['AD_endTime'])+1;
					if ($endDateDiff == 1){
						$endDateStr .= '<span class="font2_2">(只剩今天)</span>';
					}else{
						$endDateStr .= '<span class="font2_2">(还剩'. $endDateDiff .'天)</span>';
					}
				}else{
					$endDateStr .= '<span class="font2_2">(已过期)</span>';
				}
			}else{
				$endDateStr = '永久';
			}
		}else{
			$endDateStr = '<br />';
		}

		$themeAlertStr = '';
		$revBtnStr = '<img src="images/img_rev.gif" style="cursor:pointer;" onclick=\'document.location.href="ca.php?mudi=rev&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&dataMode='. $dataMode .'&dataModeStr='. $dataModeStr .'&dataID='. $row['AD_ID'] .'&typeNum='. $number .'&backURL="+ encodeURIComponent(document.location.href) +""\' alt="修改" />';

		if ($row['AD_num'] == 21 && $TS_topLogoMode != 2){
			$themeAlertStr='<div style="color:red;">(非logo+1广告位模式，无法使用)</div>';
			$revBtnStr='<img src="images/img_rev.gif" class="gray" alt="当前模式无法修改" onclick=\'alert("非logo+1广告位模式，无法修改。请到【模板参数设置】-[页头布局设置]：页头logo模式 设置开启");\' />';
		}elseif ($row['AD_num'] == 23 && $TS_topLogoMode != 4){
			$themeAlertStr='<div style="color:red;">(非全栏广告位模式，无法使用)</div>';
			$revBtnStr='<img src="images/img_rev.gif" class="gray" alt="当前模式无法修改" onclick=\'alert("非全栏广告位模式，无法修改。请到【模板参数设置】-[页头布局设置]：页头logo模式 设置开启");\' />';
		}
			
		echo('
		<tr id="data'. $row['AD_ID'] .'" '. $bgcolor .'>
			<td align="center">'. $row['AD_num'] .'</td>
			<td align="left">'. $row['AD_theme'] . $themeAlertStr .'</td>
			<td align="center">
				<textarea id="jsCode'. $row['AD_ID'] .'" name="jsCode'. $row['AD_ID'] .'" style="width:98%;height:50px;display:none;" readonly="true" title="点击复制"><script type="text/javascript">OTca("ot'. Str::FixLen($row['AD_num'],3) .'");</script></textarea>
				<div><a href="#" class="font1_2" onclick="ShowCode('. $row['AD_ID'] .');return false;">点击查看代码</a></div>
				<div id="code'. $row['AD_ID'] .'" style="display:none;">
					<div style="color:red;">仅供查看，如需修改请点击右侧修改按钮</div>
					<textarea style="width:230px; height:200px;" readonly="true" title="该区域仅供查看，如需修改请点击右侧修改按钮">'. Str::MoreReplace($row['AD_code'],'textarea') .'</textarea>
				</div>
			</td>
			<td align="center">'. $row['AD_price'] .'</td>
			<td align="center">'. $row['AD_startTime'] .'<br /></td>
			<td align="center">'. $endDateStr .'</td>
			<td align="center">'. $row['AD_rank'] .'</td>
			<td align="center">'. Adm::SwitchBtn('ad',$row['AD_ID'],$row['AD_state'],'state') .'</td>
			<td align="center">
				<img src="images/img_copy.gif" class="pointer" onclick=\'ValueToCopy("jsCode'. $row['AD_ID'] .'");\' alt="复制JS调用代码" />&ensp;&ensp;
				'. $revBtnStr .'&ensp;&ensp;
				<img src="images/img_del.gif" style="cursor:pointer;" onclick=\'if(confirm("确定删除？")==true){DataDeal.location.href="ca_deal.php?mudi=del&dataType='. $dataType .'&dataTypeCN='. urlencode($dataTypeCN) .'&theme='. urlencode($row['AD_theme']) .'&dataID='. $row['AD_ID'] .'"}\' alt="" />
			</td>
		</tr>
		');
		$number ++;
	}

	echo('
	</tbody>
	');
	unset($showexe);

	echo('
	</table>
	');
}


function AreaStrCN($str){
	$retArr = array();
	if (strpos($str,'[home]') !== false){ $retArr[] = '首页'; }
	if (strpos($str,'[list]') !== false){ $retArr[] = '列表页'; }
	if (strpos($str,'[show]') !== false){ $retArr[] = '内容页'; }
	if (strpos($str,'[message]') !== false){ $retArr[] = '留言板'; }
	if (strpos($str,'[web]') !== false){ $retArr[] = '单篇页'; }
	if (strpos($str,'[goodsList]') !== false){ $retArr[] = '淘客商品列表'; }
	if (strpos($str,'[bbsHome]') !== false){ $retArr[] = '论坛首页'; }
	if (strpos($str,'[bbsList]') !== false){ $retArr[] = '论坛列表页'; }
	if (strpos($str,'[bbsShow]') !== false){ $retArr[] = '论坛内容页'; }
	if (strpos($str,'[bbsWrite]') !== false){ $retArr[] = '论坛发帖页'; }

	if (empty($retArr)){
		return '';
	}else{
		return '<div style="padding:5px 0 2px 0;color:blue;width:98%;text-align:left;">显示区域：'. implode($retArr,'、') .'</div>';
	}
}
?>